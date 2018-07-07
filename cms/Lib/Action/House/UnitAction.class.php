<?php
/*
    小区单元控制器
 */
class UnitAction extends BaseAction{
    protected $village_id;

    public function _initialize(){
        parent::_initialize();
        $this->village_id = $this->house_session['village_id'];
    }

    public function index(){
        $database_house_village_floor = D('House_village_floor');
        $where['village_id'] = $this->house_session['village_id'];
        $list = $database_house_village_floor->house_village_floor_page_list($where);
        if(!$list){
            $this->error('数据处理有误！');
        }else{
            $this->assign('list',$list['list']);
        }
        $this->display();
    }
    
    public function unit_add(){
        if(IS_POST){
            $database_house_village_floor = D('House_village_floor');
            $result = $database_house_village_floor->house_village_floor_add($_POST);
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $this->_get_floor_type_list();
            $this->_get_property_list();
            $this->display();
        }
    }

    public function import_village(){
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['village_id'] = $this->village_id;
        $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($where);

        if(!$result){
            $this->error('数据处理有误！');
        }

        $this->assign('result',$result['result']);
        $this->display();
    }
	
	//提取老用户的房间信息
	public function updata_old_village_room_info(){
			
			set_time_limit(0);
			$database_house_village_user_bind = D('House_village_user_bind');
			//$database_house_village_user_bind_test = D('House_village_user_bind_test');
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			
			$is_true_old_user = $_GET['$is_true_old_user'] + 0;
			
			if(empty($is_true_old_user)){
				$old_condition['status']     = 1;
				$old_condition['parent_id']  = 0;
				$old_condition['type']       = 0;
				$old_condition['vacancy_id'] = 0;
				$old_condition['layer_num'] = array('neq' , "");
				$old_condition['room_address'] = array('neq' , "");
				$is_true_old_user = $database_house_village_user_bind -> where($old_condition) -> count();
			}
			//$is_true_old_user = $_GET['is_true_old_user'] + 0;
			$limit = $_GET['limit'] + 0;
			if(!$limit) $limit = 1;
			
			if(empty($is_true_old_user) || empty($this->village_id))  $this->error('参数错误！');
			
			
			
			$old_condition['status']     = 1;
			$old_condition['parent_id']  = 0;
			$old_condition['type']       = 0;
			$old_condition['vacancy_id'] = 0;
			$old_condition['layer_num'] = array('neq' , "");
			$old_condition['room_address'] = array('neq' , "");
			//$old_condition['village_id'] = $this->village_id; //需要修改成动态小区ID

			$old_data = $database_house_village_user_bind -> field('pigcms_id,village_id,usernum,floor_id,layer_num,room_addrss,uid,name,phone,housesize,park_flag,add_time') -> where($old_condition)->limit($limit) -> select();
			if($old_data){
				foreach($old_data as $k=>$v){
					
					$insert_id = $update_id = 0;
					
					$data = array();
					$data['usernum']     =   $v['usernum'];
					$data['floor_id']    =   $v['floor_id'];
					$data['layer']       =   $v['layer_num'];
					$data['room']        =   $v['room_addrss'];
					$data['status']      =   3;
					$data['village_id']  =   $v['village_id'];
					$data['add_time']    =   $v['add_time'] ? $v['add_time'] : time();
					$data['uid']         =   $v['uid'];
					$data['name']        =   $v['name'];
					$data['phone']       =   $v['phone'];
					$data['type']        =   0;
					$data['memo']        =   $v['memo'] ? $v['memo'] : 'null';
					$data['is_del']      =   0;
					$data['del_time']    =   0;
					$data['housesize']   =   $v['housesize'];
					$data['park_flag']   =   $v['park_flag'];
					
					//生成房间信息
					$insert_id = $database_house_village_user_vacancy->data($data)->add();
					
					if($insert_id){
						$now_data = array();
						$now_data['vacancy_id'] = $insert_id;
						$now_data['pass_time'] = time();
						//更新业主绑定数据的房间ID
						$update_id = $database_house_village_user_bind->where(array('pigcms_id'=>$v['pigcms_id']))->data($now_data)->save();
						if($update_id){	
							//查询业主是否绑定亲属 如果绑定 那么也更新信息
							$relatives = array();
							$relatives['housesize']     = $v['housesize'];
							$relatives['park_flag']     = $v['park_flag'];
							$relatives['address']       = $v['address'];
							$relatives['layer_num']     = $v['layer_num'];
							$relatives['room_address']  = $v['room_address'];
							$relatives['floor_id']      = $v['floor_id'];
							$relatives['type']          = $v['type'];
							$relatives['vacancy_id']    = $v['vacancy_id'];
							$database_house_village_user_bind->where(array('parent_id'=>$v['pigcms_id']))->data($relatives)->save();
							
						}
					}
					
				}
				$is_true_old_user = $is_true_old_user < $limit ? $is_true_old_user : $is_true_old_user - $limit;
				$this->success('剩余'.($is_true_old_user).'个房间正在导入，请勿关闭页面，耐心等待！',U('updata_old_village_room_info',array('is_true_old_user'=>$is_true_old_user,'limit'=>50)),0);
			}else{
				$this->success('导入成功，正在跳转',U('import_village'));
			}
	}

    public function import_village_add(){
        if(IS_POST){
            if ($_FILES['file']['error'] != 4) {
                set_time_limit(0);
                $upload_dir = './upload/excel/villageuser/' . date('Ymd') . '/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                import('ORG.Net.UploadFile');
                $upload = new UploadFile();
                $upload->maxSize = 10 * 1024 * 1024;
                $upload->allowExts = array('xls', 'xlsx');
                $upload->allowTypes = array(); // 允许上传的文件类型 留空不做检查
                $upload->savePath = $upload_dir;
                $upload->thumb = false;
                $upload->thumbType = 0;
                $upload->imageClassPath = '';
                $upload->thumbPrefix = '';
                $upload->saveRule = 'uniqid';
                if ($upload->upload()) {
                    $uploadList = $upload->getUploadFileInfo();
                    require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel/IOFactory.php';
                    $path = $uploadList['0']['savepath'] . $uploadList['0']['savename'];
                    $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                    $objReader = PHPExcel_IOFactory::createReader($fileType);
                    $excelObj = $objReader->load($path);
                    $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);

                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        foreach ($result as $kk => $vv) {
                            if (array_sum($vv) == 0) {
                                continue;
                            }
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null) continue;

                            if (empty($vv['A'])) {
                                $err_msg = '请填写物业编号！';
                                continue;
                            }
                            if (empty($vv['B'])) {
                                $err_msg = '请填写单元号！';
                                continue;
                            }
                            if (empty($vv['C'])) {
                                $err_msg = '请填写楼号！';
                                continue;
                            }

                            if (empty($vv['D'])) {
                                $err_msg = '请填写层号！';
                                continue;
                            }

                            if (empty($vv['E'])) {
                                $err_msg = '请填写房号！';
                                continue;
                            }

                            $floor_name = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $floor_layer = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $where['floor_name'] = $floor_name;
                            $where['floor_layer'] = $floor_layer;
                            $where['status'] = 1;
                            $where['village_id'] = $this->village_id;
                            $database_house_village_floor = D('House_village_floor');
                            $house_village_floor_info = $database_house_village_floor->where($where)->find();
                            if (!$house_village_floor_info) {
                                $err_msg = '单元不存在，请查看社区中心，单元管理-单元列表！';
                                continue;
                            }

                            $tmpdata = array();
                            $tmpdata['usernum'] = $this->village_id . '-' . htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            //检测用户是否已存在
                            if (D('House_village_user_vacancy')->field('`usernum`')->where(array('usernum' => $tmpdata['usernum']))->find()) {
                                $err_msg = '业主已存在。';
                                continue;
                            }

                            if (D('House_village_user_bind')->field('`usernum`')->where(array('usernum' => $tmpdata['usernum']))->find()) {
                                $err_msg = '业主已存在。';
                                continue;
                            }

                            $tmpdata['layer'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
                            $tmpdata['room'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                            $tmpdata['floor_id'] = $house_village_floor_info['floor_id'];
                            $tmpdata['village_id'] = $this->village_id;
                            $tmpdata['status'] = 1;
                            $tmpdata['add_time'] = time();
                            $last_user_id = D('House_village_user_vacancy')->data($tmpdata)->add();
                            if (!$last_user_id) {
                                $err_msg = '业主编号为' . $vv['A'] . ' 导入失败！';
                            }
                        }
                        if (!empty($last_user_id)) {
                            $this->success('导入成功');
                            exit;
                        } else {
                            $this->error('导入失败！原因：' . $err_msg);
                            exit;
                        }
                    }
                } else {
                    $this->error($upload->getErrorMsg());
                    exit;
                }
            }
            $this->error('文件上传失败');
            exit;
        }else{
            $this->display();
        }
    }

    public function import_village_edit(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['pigcms_id'] = $id;

        if(IS_POST){
			
            $result = $database_house_village_user_vacancy->house_village_user_vacancy_edit_find($where,$_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $detail = $database_house_village_user_vacancy->house_village_user_vacancy_detail($where);

            if(!$detail){
                $this->error('数据处理有误！');
            }else{
                if($detail['status']){
                    $this->assign('detail',$detail['detail']);
                }else{
                    $this->error('信息不存在！');
                }
                $this->display();
            }
        }
    }

    public function import_village_del(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['pigcms_id'] = $id;
		
		$yz_info = $database_house_village_user_vacancy->where(array('pigcms_id'=>$id))->find();
		if($yz_info['uid'] || $yz_info['name'] || $yz_info['phone']){
			$this->error('已绑定业主，无法删除！');	
		}
		
        $result = $database_house_village_user_vacancy->import_village_del($where);

        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }

    public function unit_del(){
        $floor_id = $_GET['floor_id'] + 0;
        if(!$floor_id){
            $this->error('传递参数有误！');
        }
        
        $database_house_village_floor = D('House_village_floor');
        $where['floor_id'] = $floor_id;
        $result = $database_house_village_floor->house_village_floor_del($where);
        
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }
    
    
    public function unit_edit(){
        $floor_id = $_GET['floor_id'] + 0;
        if(!$floor_id){
            $this->error('传递参数有误！');
        }
        
        $database_house_village_floor = D('House_village_floor');
        $where['floor_id'] = $floor_id;
        if(IS_POST){
            $result = $database_house_village_floor->house_village_floor_edit($where,$_POST);
            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $detail = $database_house_village_floor->house_village_floor_detail($where);
            if(!$detail){
                $this->error('数据处理有误！');
            }else{
                $this->assign('detail',$detail['detail']);
            }

            $this->_get_floor_type_list();
            $this->display();
        }
    }


    public function unittype_list(){
        $database_house_village_floor_type = D('House_village_floor_type');

        $where['village_id'] = $_SESSION['house']['village_id'];
        $list = $database_house_village_floor_type->house_village_floor_type_page_list($where);

        if(!$list){
            $this->error('数据处理有误！~~~');
        }else{
            $this->assign('list' , $list['list']);
        }

        $this->display();
    }

    public function unittype_add(){
        if(IS_POST){
            $database_house_village_floor_type = D('House_village_floor_type');
            $result = $database_house_village_floor_type->house_village_floor_type_add($_POST);

            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }else{
            $this->display();
        }
    }


    public function unittype_edit(){
        $database_house_village_floor_type = D('House_village_floor_type');
        $id = $_GET['id'] + 0;
        if(empty($id)){
            $this->error('传递参数有误！~~~');
        }

        $where['id'] = $id;
        if(IS_POST){
            $result = $database_house_village_floor_type->house_village_floor_type_edit($where , $_POST);
            if(!$result){
                $this->error('数据处理有误！~~~~');
            }else{
                if($result['status']==0){
                    $this->error($result['msg']);
                }else{
                    $this->success($result['msg']);
                }
            }

        }else{
            $detail = $database_house_village_floor_type->house_village_floor_type_detail($where);

            if(!$detail){
                $this->error('数据处理有误！~~~');
            }else{
                if($detail['status'] == 0){
                    $this->error('该信息不存在！');
                }else{
                    $this->assign('detail',$detail['detail']);
                }
            }
            $this->display();
        }
    }

    public function pay_order(){
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'order_name') {
                $condition['order_name'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'phone') {
                $condition['phone'] = $_GET['keyword'] ;
            }
        }

        if(isset($_GET['searchstatus'])){
            $condition['is_pay_bill'] = $_GET['searchstatus'];
        }


        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
        }

        $village_id = $_SESSION['house']['village_id'];
        if($village_id){
            $condition['village_id'] = $village_id;
            $condition['paid'] = 1;
            $result = D('House_village_pay_order')->get_limit_list_page($condition,20);

            $finshtotal = $total = 0;
            if($result){
                foreach ($result['order_list'] as $v){
                    $total += $v['money'];								//本页的总额
                    $v['is_pay_bill'] && $finshtotal += $v['money'];	//本页已对账的总额
                }
            }
            $this->assign('finshtotal',$finshtotal);
            $this->assign('total',$total);
            $this->assign('order_list',$result);
        }
        $this->assign('village_id',$village_id);
        $this->display();
    }


    public function export(){
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '社区账单';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'order_name') {
                $condition['order_name'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'phone') {
                $condition['phone'] = $_GET['keyword'] ;
            }
        }

        if(isset($_GET['searchstatus'])){
            $condition['is_pay_bill'] = $_GET['searchstatus'];
        }

        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
        }

        $village_id = $_SESSION['house']['village_id'];
        if($village_id){
            $now_village = D('House_village')->get_one($village_id);
            $condition['village_id'] = $village_id;
            $condition['paid'] = 1;
            $count = D('House_village_pay_order')->where($condition)->count();
            $length = ceil($count / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千订单');
                $objActSheet = $objExcel->getActiveSheet();
                $objActSheet->setCellValue('A1', '缴费项');
                $objActSheet->setCellValue('B1', '已缴金额');
                $objActSheet->setCellValue('C1', '支付时间');
                $objActSheet->setCellValue('D1', '业主名');
                $objActSheet->setCellValue('E1', '联系方式');
                $objActSheet->setCellValue('F1', '住址');
                $objActSheet->setCellValue('G1', '编号');
                $objActSheet->setCellValue('H1', '物业服务周期');
                $objActSheet->setCellValue('I1', '自定义内容/赠送物业服务时间');
                $objActSheet->setCellValue('J1', '服务时间');
                $objActSheet->setCellValue('K1', '对账状态');

                $result = D('House_village_pay_order')->get_limit_list_page($condition , ($i+1)*1000 , true);
                if (!empty($result['order_list'])) {
                    $index = 2;
                    foreach ($result['order_list'] as $value) {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['order_name']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['money']);
                        $objActSheet->setCellValueExplicit('C' . $index, date('Y-m-d H:i:s',$value['time']));
                        $objActSheet->setCellValueExplicit('D' . $index, $value['username']);
                        $objActSheet->setCellValueExplicit('E' . $index, $value['phone']);
                        $objActSheet->setCellValueExplicit('F' . $index, $value['address']);
                        $objActSheet->setCellValueExplicit('G' . $index, $value['usernum']);
                        if($value['property_month_num']){
                            $objActSheet->setCellValueExplicit('H' . $index, $value['property_month_num'].'个月');
                        }else{
                            $objActSheet->setCellValueExplicit('H' . $index, '暂无');
                        }

                        if(!empty($value["presented_property_month_num"]) AND ($value["diy_type"] == 0)){
                            $objActSheet->setCellValueExplicit('I' . $index, $value['presented_property_month_num'].'个月');
                        }elseif($value["diy_type"] == 1){
                            $objActSheet->setCellValueExplicit('I' . $index, $value['diy_content']);
                        }else{
                            $objActSheet->setCellValueExplicit('I' . $index, '暂无');
                        }

                        if($value['property_time_str']){
                            $objActSheet->setCellValueExplicit('J' . $index, $value['property_time_str']);
                        }else{
                            $objActSheet->setCellValueExplicit('J' . $index, '暂无');
                        }

                        if($value['is_pay_bill'] == 0){
                            $objActSheet->setCellValueExplicit('K' . $index, '未对账');
                        }else{
                            $objActSheet->setCellValueExplicit('K' . $index, '已对账');
                        }
                        $index++;
                    }
                }
                sleep(2);
            }
        }

        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$now_village['village_name'].'_'.$title.'_' . date("Y-m-d h:i:s", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function merchant_order(){
        $type=I('type')?I('type'):'group';
        $village_id = $_SESSION['house']['village_id'];
        $time_condition ='';
        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (o.pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }

        $order_list = D('House_village_group')->get_order_list($type,$village_id,$time_condition);
        $this->assign($order_list);
        $this->display();
    }

    public function preferential_list(){
        $database_house_village_property = D('House_village_property');

        $where['village_id'] = $_SESSION['house']['village_id'];
        $list = $database_house_village_property->house_village_proerty_page_list($where,true,'id desc',20);

        if(!$list){
            $this->error('数据处理有误！');
        }else{
            $this->assign('list',$list['list']);
        }

        $this->display();
    }

    public function preferential_add(){
        if(IS_POST){
            $database_house_village_property = D('House_village_property');

            $_POST['village_id'] = $_SESSION['house']['village_id'];
            $result = $database_house_village_property->house_village_property_add($_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $this->display();
        }
    }

    public function preferential_del(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_property = D('House_village_property');
        $where['id'] = $id;
        $result = $database_house_village_property->house_village_property_del($where);

        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }

    public function preferential_edit(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_property = D('House_village_property');
        $where['id'] = $id;

        if(IS_POST){
            $result = $database_house_village_property->house_village_property_edit($where,$_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }else{
                if($result['status']){
                    $this->success($result['msg']);
                }else{
                    $this->error($result['msg']);
                }
            }
        }else{
            $detail = $database_house_village_property->house_village_property_detail($where ,true);

            if(!$detail){
                $this->error('数据处理有误！');
            }else{
                $this->assign('detail',$detail['detail']);
            }
            $this->display();
        }
    }

    public function unittype_del(){
        $id = $_GET['id'] + 0;

        $database_house_village_floor_type = D('House_village_floor_type');
        $where['id'] = $id;
        $where['village_id'] = $_SESSION['house']['village_id'];
        $result = $database_house_village_floor_type->village_floor_type_delete($where);

        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }


    private function _get_floor_type_list(){
        $database_house_village_floor_type = D('House_village_floor_type');
        $house_village_floor_type_condition['status'] = 1;
        $house_village_floor_type_condition['village_id'] = $_SESSION['house']['village_id'];
        $house_village_floor_type_num = $database_house_village_floor_type->where($house_village_floor_type_condition)->count();

        if($house_village_floor_type_num <= 0){
            $this->error('请先添加单位类型！',U('unittype_add'));
        }

        $house_village_floor_type_list = $database_house_village_floor_type->house_village_floor_type_page_list($house_village_floor_type_condition , true , 'id desc' , 9999);
        $this->assign('house_village_floor_type_list' , $house_village_floor_type_list['list']);
    }

    private function _get_property_list(){
        $database_house_village_property = D('House_village_property');
        $house_village_property_condition['status'] = 1;
        $house_village_property_condition['village_id'] = $_SESSION['house']['village_id'];

        $house_village_property_list = $database_house_village_property->house_village_proerty_page_list($house_village_property_condition , true , 'id desc' , 9999);
        $this->assign('house_village_property_list' , $house_village_property_list['list']);
    }
}
?>

