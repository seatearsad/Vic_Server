<?php

/*
 * 小区业主
 *
 */

class UserAction extends BaseAction
{
    protected $village_id;
    protected $village;

    public function _initialize()
    {
        parent::_initialize();

        $this->village_id = $this->house_session['village_id'];
        $this->village = D('House_village')->where(array('village_id' => $this->village_id))->find();
        if (empty($this->village)) {
            $this->error('该小区不存在！');
        }
        if ($this->village['status'] == 0) {
            $this->assign('jumpUrl', U('Index/index'));
            $this->error('您需要先完善信息才能继续操作');
        }
    }

    // 所有业主列表
    public function index()
    {
        $find_type = $_POST['find_type'];
        $find_value = $_POST['find_value'];
        $is_platform = $_POST['is_platform'] + 0;
        if ($find_value) {
            if ($find_type == 1) {
                $where['usernum'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 2) {
                $where['name'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 3) {
                $where['phone'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 4) {
                $where['address'] = array('like', '%' . $find_value . '%');
            }
        }

        if($is_platform){
            $where['is_platform'] = $is_platform;
        }

        $village_id = $this->village_id;
        if (empty($where)) {
            $user_list = D('House_village_user_bind')->get_limit_list_page($village_id);
        } else {
            $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 99999, $where);
        }


        $village_info = D('House_village')->field(true)->where(array('village_id'=>$village_id))->find();
        $village_info['long'] = floatval($village_info['long']);
        $village_info['lat'] = floatval($village_info['lat']);

        $this->assign('village_info',$village_info);
        $this->assign('find_value', $find_value);
        $this->assign('find_type', $find_type);
        $this->assign('user_list', $user_list);
        $this->display();
    }

    public function bind_list(){
        $pigcms_id = $_GET['pigcms_id'] + 0;
        $where['parent_id'] = $pigcms_id;
        $user_list = D('House_village_user_bind')->field(true)->where($where)->order('`pigcms_id` DESC')->select();
        $this->assign('user_list' , $user_list);
        $this->display();
    }


    public function audit_index(){
        $village_id = $this->village_id;
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $where['village_id'] = $village_id;
        $where['is_del'] = 0;
		$where['phone'] = array('neq' , "");
		$where['name'] = array('neq' , "");
		$where['uid'] = array('neq' , 0);
        $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($where);
		
        if(!$result){
            $this->error('数据处理有误！');
        }

        $this->assign('user_list',$result['result']);
        $this->display();
    }

    public function audit_edit(){
        $pigcms_id = $_GET['pigcms_id'] + 0;
        $usernum = $_GET['usernum'];

        $bind_condition['pigcms_id'] = $pigcms_id;
        $bind_condition['usernum'] = $usernum;
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $info = $database_house_village_user_vacancy->house_village_user_vacancy_detail($bind_condition);
        if(!$info){
            $this->error('数据处理有误！');
        }
        $info = $info['detail'];

        if(IS_POST) {
            if(empty($_POST['usernum'])){
                $this->error('业主编号不能为空！');
            }

            if(empty($_POST['user_name'])){
                $this->error('业主名不能为空！');
            }

            if(empty($_POST['phone'])){
                $this->error('手机号不能为空！');
            }
            $status = $_POST['status'] + 0;

            $data['usernum'] = $_POST['usernum'];
            $database_house_village_floor = D('House_village_floor');
            $database_house_village_user_bind = D('House_village_user_bind');
            $database_user = D('User');

            $where['floor_id'] = $_POST['floor_id'] + 0;
            $where['status'] = $status;
            $where['village_id'] = $this->village_id;
            $house_village_floor_info = $database_house_village_floor->where($where)->find();

            if($status == 1){
                //检测用户是否已存在
                $Map['usernum'] =  $data['usernum'];
               // if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
                   // $this->error('业主名为已存在！物业编号重复。');
               // }

                if (!isset($house_village_floor_info)) {
                    $this->error('单元不存在，请查看社区中心，单元管理-单元列表！');
                }

                $now_user = $database_user->get_user($_POST['phone'], 'phone');
                if ($now_user) {
                    $data['uid'] = $now_user['uid'];
                }
                $layer_num = $_POST['layer_num'];
                $room_num = $_POST['room_num'];

                $data['name'] = $_POST['user_name'];
                $data['phone'] = $_POST['phone'];
                $data['floor_id'] = $_POST['floor_id'] + 0;
                $data['room_addrss'] =  $room_num;

                if($memo = htmlspecialchars(trim($_POST['memo']))){
                    $data['memo'] = $memo;
                }

                $data['layer_num'] = $layer_num;
                $data['address'] = $_POST['address'];
                $data['village_id'] = $this->village_id;
                $data['housesize'] = $info['housesize'];
                $data['park_flag'] = $info['park_flag'];
                $data['add_time'] = time();
                $data['vacancy_id'] = $info['pigcms_id'];
                $data['type'] = $info['type'];
				
				$find_info = $database_house_village_user_bind->where($Map)->count();
				if($find_info>0){
					$insert_id = $database_house_village_user_bind->where($Map)->data($data)->save();	
				}else{
					$insert_id = $database_house_village_user_bind->data($data)->add();
				}
                if($insert_id){
					$data_room['status'] = 3;
					$data_room['name'] = $_POST['user_name'];
					$data_room['phone'] = $_POST['phone'];
					$data_room['memo'] = $memo ? $memo : "";
                    //$database_house_village_user_vacancy->where($bind_condition)->setField('status',3);
					$database_house_village_user_vacancy->where($bind_condition)->data($data_room)->save();
                    $this->success('添加成功！',U('index'));
                }else{
                    $this->error('添加失败！');
                }
            }else{
                $edit_data['status'] = 0;
                $insert_id = $database_house_village_user_vacancy->where($bind_condition)->data($edit_data)->save();
                if($insert_id){
                    $this->success('修改成功！');
                }else{
                    $this->error('修改失败！');
                }
            }
            exit;
        }else if ($pigcms_id && $usernum) {
            $this->assign('info', $info);
        }
        $this->display();
    }


    public function audit_del(){
        $pigcms_id = $_GET['pigcms_id'] + 0;
        if(!$pigcms_id){
            $this->error('传递参数有误！');
        }

        $where['pigcms_id'] = $pigcms_id;

        $database_house_village_user_vacancy = D('House_village_user_vacancy');

        $data['is_del'] = 1;
        $data['del_time'] = time();
        $insert_id = $database_house_village_user_vacancy->where($where)->data($data)->save();

        if($insert_id){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

	# update 2017-03-21 - wangdong
    public function bind_edit(){
        $pigcms_id = $_GET['pigcms_id'] + 0;
        if(!$pigcms_id){
            $this->error('传递参数有误！~~~');
        }

        $database_house_village_user_bind = D('House_village_user_bind');
        $now_user = $database_house_village_user_bind->get_one_by_bindId($pigcms_id);
        if(!$now_user){
            $this->error('信息暂时不存在！');
        }

        if(!$_GET['no_bind']) {
            if ($now_user['status'] == 1) {
                $this->error('已通过审核！');
            }
        }	
		
        if($now_user['type'] == 3){
            $user_data['uid'] = $now_user['uid'];
            $user_data['name'] = $now_user['name'];
            $user_data['phone'] = $now_user['phone'];
            $user_data['pass_time'] = time();
			$user_data['parent_id'] = 0;
            $insert_id = $database_house_village_user_bind->where(array('pigcms_id'=>$now_user['parent_id']))->data($user_data)->save();
			
			#要修改 房间house_village_user_vacancy 表信息
			$vacancy_data['uid'] = $now_user['uid'];
			$vacancy_data['name'] = $now_user['name'];
			$vacancy_data['phone'] = $now_user['phone'];
			$vacancy_data['status'] = 3;
			
			$vacancy_where['pigcms_id'] = $now_user['vacancy_id'];
			$vacancy_where['floor_id']  = $now_user['floor_id'];
			$vacancy_where['village_id'] = $now_user['village_id'];
			$database_house_village_user_vacancy = D('House_village_user_vacancy');
			$database_house_village_user_vacancy->where($vacancy_where)->data($vacancy_data)->save();
			#禁止之前房主ID
			$database_house_village_user_bind->where(array('pigcms_id'=>$now_user['parent_id']))->data(array('status'=>0))->save();
			#修改之前房主下面的绑定亲属/租客 替换到现在房主下面
			$database_house_village_user_bind->where(array('parent_id'=>$now_user['parent_id']))->data(array('parent_id'=>$now_user['pigcms_id']))->save();
			
			
        }

        if($_GET['no_bind']){
            $insert_id = $database_house_village_user_bind->where(array('pigcms_id'=>$pigcms_id))->data(array('pass_time'=>time(),'status'=>0))->save();
        }else{
            $insert_id = $database_house_village_user_bind->where(array('pigcms_id'=>$pigcms_id))->data(array('pass_time'=>time(),'status'=>1))->save();
        }

        if($insert_id){
            $this->success('修改成功！');
        }else{
            $this->error('修改失败！');
        }
    }

    public function edit(){
        if (IS_POST) {
            $condition['usernum'] = $_POST['usernum'];
            $condition['pigcms_id'] = $_POST['pigcms_id'];
            $condition['village_id'] = $this->village_id;

            $_POST['add_time'] = $_SERVER['REQUEST_TIME'];

            $database_house_village_user_bind = D('House_village_user_bind');
            $database_house_village_floor = D('House_village_floor');
            $database_house_village_user_vacancy = D('house_village_user_vacancy');

            $_POST['uid'] = D('User')->get_user_by_phone($_POST['phone']);
            $_POST['floor_id'] = $_POST['floor_id'] + 0;

            $where['floor_id'] = $_POST['floor_id'];
            $where['status'] = 1;
            $house_village_floor_info = $database_house_village_floor->where($where)->find();

            if (!isset($house_village_floor_info)) {
                $this->error('单元不存在，请查看社区中心，单元管理-单元列表！');
            }
            $vacancy_where['status'] = array('in' , '1,2,3');
            $vacancy_where['pigcms_id'] = $_POST['layer_room'] + 0;
            $vacancy_info = $database_house_village_user_vacancy->where($vacancy_where)->find();

            if (!isset($vacancy_info)) {
                $this->error('该信息不存在！');
            }
			
           // $Map['usernum'] =  $vacancy_info['usernum'];
			
           // if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
				//$this->error('业主名为' . $_POST['user_name'] . ' 已存在！物业编号重复');
            //}
            $_POST['usernum'] =  $vacancy_info['usernum'];
			
			//没有修改房间表 house_village_user_vacancy
				
            if ($database_house_village_user_bind->where($condition)->data($_POST)->save()) {
				
				//修改房间表 信息
				$condition_vacancy['usernum']    = $vacancy_info['usernum'];
				$condition_vacancy['village_id'] = $this->village_id;
				
				$data_vacancy['status'] = 3;
				$data_vacancy['uid'] = $_POST['uid'];
				$data_vacancy['name'] = $_POST['name'];
				$data_vacancy['phone'] = $_POST['phone'];
				$data_vacancy['memo'] = $_POST['memo'];
				$data_vacancy['housesize'] = $_POST['housesize'];
				$data_vacancy['park_flag'] = $_POST['park_flag'];
				$data_vacancy['type'] = 0;
				//$database_house_village_user_vacancy->where($condition_vacancy)->data($data_vacancy)->save();
				
                $this->success('Success', U('User/index'));
                exit;
            }

            $this->error('保存失败');
            exit;
        } else {
            $pigcms_id = $_GET['pigcms_id'];
            $usernum = $_GET['usernum'];
            if ($pigcms_id && $usernum) {
                $bind_condition['pigcms_id'] = $pigcms_id;
                $bind_condition['usernum'] = $usernum;
                $info = D('House_village_user_bind')->where($bind_condition)->find();

                $database_house_village_property_paylist = D('House_village_property_paylist');
                $pay_list = $database_house_village_property_paylist->where(array('bind_id'=>$info['pigcms_id']))->order('add_time asc')->select();

                if(!empty($pay_list)){
                    $first_pay_info = reset($pay_list);
                    $end_pay_info = end($pay_list);
                    if($first_pay_info && $end_pay_info){
                        $info['property_month'] =  date('Y-m-d',$first_pay_info['start_time']) .' 至 '. date('Y-m-d', $end_pay_info['end_time']);
                    }else{
                        $info['property_month'] =  date('Y-m-d',$pay_list['start_time']) .' 至 '. date('Y-m-d', $pay_list['end_time']);
                    }
                }

                $database_house_village_floor = D('House_village_floor');
                if($info['floor_id']){
                    $floor_type = $database_house_village_floor->where(array('floor_id'=>$info['floor_id']))->getField('floor_type');
                    $info['floor_type_name'] = D('House_village_floor_type')->where(array('id'=>$floor_type))->getField('name');
                }
                $this->assign('info', $info);

                $condition['village_id'] = $this->village_id;
                $condition['status'] = 1;
                $floor_list = $database_house_village_floor->house_village_floor_page_list($condition , true ,'floor_id desc' , 99999);

                if(!$floor_list){
                    $this->error('数据处理有误！');
                }


                $database_house_village_user_vacancy = D('House_village_user_vacancy');
                $vacancy_where['status'] = array('in' , '1,2,3');
                $vacancy_where['village_id'] = $this->village_id;
                $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($vacancy_where ,true ,'pigcms_id desc' , 999999999);
				
                $this->assign('vacancy_list',$result['result']['list']);

                if(!$floor_list['status']){
                    $this->error($floor_list['msg']);
                }else{
                    $this->assign('floor_list' ,$floor_list['list']);
                }
            }
            $this->display();
        }
    }

    public function user_add(){
        $database_house_village_user_vacancy = D('House_village_user_vacancy');
        $database_house_village_floor = D('House_village_floor');
		$vacancy_where['status'] = 1;
        $vacancy_where['village_id'] = $this->village_id;
		
		//先判断单元
		$floor_num = $database_house_village_floor->where(array('village_id'=>$this->village_id,'status'=>1))->count();
		if($floor_num <= 0){
            $this->error('请先添加单元',U('Unit/index'));
        }
		
        $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($vacancy_where ,true ,'pigcms_id desc' , 999999999);

        if(!$result['result']['list']){
            $this->error('请先导入房间',U('Unit/import_village'));
        }
        $this->assign('vacancy_list' , $result['result']['list']);

        if (IS_POST) {
            /*if(empty($_POST['usernum'])){
                $this->error('业主编号不能为空！');
            }*/

            if(empty($_POST['user_name'])){
                $this->error('业主名不能为空！');
            }

            if(empty($_POST['phone'])){
                $this->error('手机号不能为空！');
            }

            if(empty($_POST['floor_id'])){
                $this->error('单元名称不能为空！');
            }

            /*if(empty($_POST['floor_name'])){
                $this->error('单元名称不能为空！');
            }

            if(empty($_POST['floor_layer'])){
                $this->error('楼号不能为空！');
            }*/

            if(empty($_POST['layer_num'])){
                $this->error('层号不能为空！');
            }

            if(empty($_POST['room_num'])){
                $this->error('门牌号不能为空！');
            }

            if(empty($_POST['housesize'])){
                $this->error('房子平方不能为空！');
            }

            $floor_name = $_POST['floor_name'];
            $floor_layer = $_POST['floor_layer'];
            $layer_num = $_POST['layer_num'];
            $room_num = $_POST['room_num'];
            //$data['usernum'] = $this->village_id . '-' . $_POST['usernum'];

            $database_house_village_floor = D('House_village_floor');
            $database_house_village_user_bind = D('House_village_user_bind');
            $database_house_village_user_vacancy = D('House_village_user_vacancy');
            $database_user = D('User');
           // $where['floor_name'] = $floor_name;
          //  $where['floor_layer'] = $_POST['floor_layer'];
            $where['floor_id'] = $_POST['floor_id'] + 0;
            $where['status'] = 1;
            $where['village_id'] = $this->village_id;

            $house_village_floor_info = $database_house_village_floor->where($where)->find();
            if (!isset($house_village_floor_info)) {
                $this->error('单元不存在，请查看社区中心，单元管理-单元列表！');
            }


            $vacancy_where['status'] = 1;
            $vacancy_where['pigcms_id'] = $_POST['layer_room'] + 0;
            $vacancy_info = $database_house_village_user_vacancy->where($vacancy_where)->find();

            if (!isset($vacancy_info)) {
                $this->error('该信息不存在！');
            }
			
			$now_user = $database_user->get_user($_POST['phone'], 'phone');
            if ($now_user) {
                $data['uid'] = $now_user['uid'];
            }else{
				 $this->error('没有您添加的业主信息！');	
			}
			
            //检测用户是否已存在
            $Map['usernum'] =  $vacancy_info['usernum'];
            if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
                $this->error('业主名为' . $_POST['user_name'] . ' 已存在！物业编号重复');
            }
            

            $data['name'] = $_POST['user_name'];
            $data['phone'] = $_POST['phone'];
            $data['floor_id'] = $_POST['floor_id'];
            $data['water_price'] = $_POST['water_price'];
            $data['electric_price'] = $_POST['electric_price'];
            $data['gas_price'] = $_POST['gas_price'];
            $data['park_flag'] = $_POST['park_flag'] + 0;
            $data['room_addrss'] = $room_num;
            $data['usernum'] = $vacancy_info['usernum'];
            $data['vacancy_id'] = $_POST['vacancy_id'] + 0;

            if($memo = htmlspecialchars(trim($_POST['memo']))){
                $data['memo'] = $memo;
            }

            if(isset($_POST['park_flag'])){
                $data['park_price'] = $_POST['park_price'];
            }

            $data['housesize'] = $_POST['housesize'];
            $data['layer_num'] = $layer_num;
            $data['address'] = $house_village_floor_info['floor_name'] . $house_village_floor_info['floor_layer'] . $room_num;
            $data['village_id'] = $this->village_id;
            $data['add_time'] = time();
		
            $insert_id = $database_house_village_user_bind->data($data)->add();
            if($insert_id){
				
				//更改房间房主信息  uid,name,phone,type=0,status=3,housesize,park_flag,add_time,				
				$data_info['uid'] = $data['uid'];
				$data_info['name'] = $_POST['user_name'];
				$data_info['phone'] = $_POST['phone'];
				$data_info['type'] = 0;
				$data_info['status'] = 3;
				$data_info['housesize'] = $_POST['housesize'];
				$data_info['park_flag'] = $_POST['park_flag'];
				$data_info['add_time'] = time();
				
				$where_info['pigcms_id'] = $_POST['layer_room'];
				$where_info['village_id'] = $this->village_id;
				
				
				$database_house_village_user_vacancy->data($data_info)->where($where_info)->save();
				
                $this->success('添加成功！',U('index'));
            }else{
                $this->error('添加失败！');
            }
        } else {
            $database_house_village_floor = D('House_village_floor');
            $condition['village_id'] = $this->village_id;
            $condition['status'] = 1;
            $floor_list = $database_house_village_floor->house_village_floor_page_list($condition , true ,'floor_id desc' , 99999);

            if(!$floor_list){
                $this->error('数据处理有误！');
            }

            if(!$floor_list['status']){
                $this->error($floor_list['msg']);
            }else{

                if(!count($floor_list['list']['list'])){
                    $this->error('单元不存在，请先添加！');
                }

                $this->assign('floor_list' ,$floor_list['list']);
                $this->display();
            }

        }
    }


    public function ajax_get_layer(){
        if(IS_AJAX){
            $floor_id = $_POST['floor_id'] + 0;
            $database_house_village_user_vacancy = D('House_village_user_vacancy');

            $where['floor_id'] = $floor_id;
            $where['status'] = 1;
            $result = $database_house_village_user_vacancy->house_village_user_vacancy_page_list($where ,true ,'pigcms_id desc' , 999999999);

            if(!$result){
                exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
            }else{
                if(!$result['status']){
                    exit(json_encode(array('status'=>0,'msg'=>$result['msg'])));
                }else{
                    exit(json_encode(array('status'=>1,'list'=>$result['result']['list'])));
                }
            }
        }else{
            $this->error('访问页面有误！~~~~');
        }
    }


    public function ajax_user_bind(){
        //检测用户是否已存在
        $Map['usernum'] =  $this->village_id . '-' . $_POST['usernum'];
        $database_house_village_user_bind = D('House_village_user_bind');
        if ($database_house_village_user_bind->field('`usernum`')->where($Map)->find()) {
            exit(json_encode(array('status'=>1)));
        }else{
            exit(json_encode(array('status'=>0)));
        }
    }
    public function user_import()
    {
        if (IS_POST) {
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

                    $database_user = D('User');
                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        foreach ($result as $kk => $vv) {
                            if (array_sum($vv) == 0) {
                                continue;
                            }
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null && $vv['F'] === null && $vv['G'] === null && $vv['H'] === null && $vv['I'] === null && $vv['J'] === null && $vv['K'] === null && $vv['L'] === null && $vv['M'] === null && $vv['N'] === null) continue;

                            // $vv['N'] = floatval($vv['N']);
                            if (empty($vv['A'])) {
                                $err_msg = '请填写业主编号！';
                                continue;
                            }
                            if (empty($vv['B'])) {
                                $err_msg = '请填写业主名！';
                                continue;
                            }
                            if (empty($vv['C'])) {
                                $err_msg = '请填写手机号！';
                                continue;
                            }
                            if (empty($vv['I'])) {
                                $err_msg = '请填写单元名称！';
                                continue;
                            }

                            if (empty($vv['J'])) {
                                $err_msg = '请填写楼号！';
                                continue;
                            }


                            if (empty($vv['K'])) {
                                $err_msg = '请填写层号！';
                                continue;
                            }


                            if (empty($vv['L'])) {
                                $err_msg = '请填写门牌号！';
                                continue;
                            }

                            if (empty($vv['M'])) {
                                $err_msg = '请填写房子平方！';
                                continue;
                            }

                            $floor_name = htmlspecialchars(trim($vv['I']), ENT_QUOTES);
                            $floor_layer = htmlspecialchars(trim($vv['J']), ENT_QUOTES);
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
                            if (D('House_village_user_bind')->field('`usernum`')->where(array('usernum' => $tmpdata['usernum']))->find()) {
                                $err_msg = '业主名为' . $vv['B'] . ' 已存在！物业编号重复';
                                continue;
                            }
                            $tmpdata['name'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $tmpdata['phone'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $tmpdata['water_price'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
                            $tmpdata['electric_price'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                            $tmpdata['gas_price'] = htmlspecialchars(trim($vv['F']), ENT_QUOTES);
                            $tmpdata['park_flag'] = htmlspecialchars(trim($vv['G']), ENT_QUOTES);
                            $tmpdata['park_price'] = htmlspecialchars(trim($vv['H']), ENT_QUOTES);
                            $tmpdata['layer_num'] = $vv['K'];
                            $room_addrss = htmlspecialchars(trim($vv['L']), ENT_QUOTES);
                            $tmpdata['room_addrss'] = $room_addrss;
                            $tmpdata['address'] = $floor_name . $floor_layer . $room_addrss;
                            $tmpdata['floor_id'] = $house_village_floor_info['floor_id'];

                            $tmpdata['housesize'] = $vv['M'];
                            $tmpdata['village_id'] = $this->village_id;
                            if($memo = htmlspecialchars(trim($vv['N']), ENT_QUOTES)){
                                $tmpdata['memo'] = $memo;
                            }


                            $user = $database_user->get_user($tmpdata['phone'], 'phone');
                            if ($user) {
                                $tmpdata['uid'] = $user['uid'];
                            }

                            $tmpdata['add_time'] = time();
                            $last_user_id = D('House_village_user_bind')->data($tmpdata)->add();
                            if (!$last_user_id) {
                                $err_msg = '业主名为' . $vv['B'] . ' 导入失败！';
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
        } else {
            $this->display();
        }
    }

    // 缴费明细
    public function pay_detail()
    {
        $village_id = $this->village_id;
        $usernum = $_GET['usernum'];

        if ($village_id && $usernum) {
            $list = D('House_village_user_paylist')->get_limit_list_page($usernum, $village_id);

            $this->assign('user_list', $list);
        }

        $this->display();
    }

    public function pay_one_del(){
        $village_id = $this->village_id;
        $pigcms_id = $_GET['pigcms_id'];

        $pay_condition['village_id'] = $village_id;
        $pay_condition['pigcms_id'] = $pigcms_id;
        $database_house_village_user_paylist = D('House_village_user_paylist');
        $insert_id = $database_house_village_user_paylist->where($pay_condition)->delete();

        if($insert_id){
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
        echo $pigcms_id;
    }

    public function detail_import()
    {
        if (IS_POST) {
            if (!$_POST['paytime']) {
                $this->error('请选择时间');
                exit;
            }
            $yearArray = explode('年', $_POST['paytime']);
            $year = $yearArray[0];
            $m = str_replace('月', '', $yearArray[1]);

            unset($_POST['paytime']);
            $_POST['ydate'] = $year;
            $_POST['mdate'] = intval($m);

            if ($_FILES['file']['error'] != 4) {
                $upload_dir = './upload/house/excel/paydetail/' . date('Ymd') . '/';
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

                    $old_end_user_id = D('House_village_user_paylist')->field('pigcms_id')->order('pigcms_id DESC')->find();

                    if (!empty($result) && is_array($result)) {
                        unset($result[1]);
                        $last_user_id = 0;
                        $err_msg = '';
                        foreach ($result as $kk => $vv) {
                            if ($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null && $vv['F'] === null && $vv['G'] === null && $vv['H'] === null) continue;

                            if (empty($vv['A'])) {
                                $err_msg = '请填写业主编号！';
                                continue;
                            }
                            if (empty($vv['B'])) {
                                $err_msg = '请填写业主名！';
                                continue;
                            }
                            if (empty($vv['C'])) {
                                $err_msg = '请填写手机号！';
                                continue;
                            }
                            if (empty($vv['I'])) {
                                $err_msg = '请填写住址！';
                                continue;
                            }

                            $tmpdata = array();
                            $tmpdata['mdate'] = $_POST['mdate'];
                            $tmpdata['ydate'] = $_POST['ydate'];
                            $tmpdata['village_id'] = $this->village_id;
                            $tmpdata['usernum'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                            //检测业主是否已经导入
                            $condition = array('usernum' => $tmpdata['usernum'], 'ydate' => $tmpdata['ydate'], 'mdate' => $tmpdata['mdate']);
                            $pay_list = D('House_village_user_paylist')->field('`usernum`')->where($condition)->find();
                            if ($pay_list) {
                                $err_msg = '业主 ' . $vv['B'] . ' 当月帐单已导入';
                                continue;
                            }

                            $conditionBind = array('village_id' => $this->village_id, 'usernum' => $tmpdata['usernum']);
                            //$bindInfo = D('House_village_user_bind')->field('`pigcms_id`,`usernum`,`uid`,`housesize`')->where($conditionBind)->find();
                            $bindInfo = D('House_village_user_bind')->where($conditionBind)->find();
                            if (!$bindInfo) {
                                $err_msg = '通过业主编号没找到 ' . $vv['B'];
                                continue;
                            }

                            if($bindInfo['address'] != htmlspecialchars(trim($vv['I']), ENT_QUOTES)){
                                $err_msg = '地址填写不一致！ ';
                                continue;
                            }

                            $tmpdata['name'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                            $tmpdata['phone'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                            $tmpdata['use_water'] = floatval(htmlspecialchars(trim($vv['D']), ENT_QUOTES));
                            $tmpdata['use_electric'] = floatval(htmlspecialchars(trim($vv['E']), ENT_QUOTES));
                            $tmpdata['use_gas'] = floatval(htmlspecialchars(trim($vv['F']), ENT_QUOTES));
                            $tmpdata['use_property'] = intval(htmlspecialchars(trim($vv['G']), ENT_QUOTES));
                            $tmpdata['use_park'] = intval(htmlspecialchars(trim($vv['H']), ENT_QUOTES));
                            $tmpdata['address'] = htmlspecialchars(trim($vv['I']), ENT_QUOTES);
                            $tmpdata['bind_id'] = $bindInfo['pigcms_id'];
                            $tmpdata['uid'] = $bindInfo['uid'];
                            $tmpdata['add_time'] = $_SERVER['REQUEST_TIME'];

                            if ($tmpdata['use_water']) {
                                $tmpdata['water_price'] = $tmpdata['use_water'] * $this->village['water_price'];
                                D('House_village_user_bind')->where($conditionBind)->setInc('water_price', $tmpdata['water_price']);
                            }
                            if ($tmpdata['use_electric']) {
                                $tmpdata['electric_price'] = $tmpdata['use_electric'] * $this->village['electric_price'];
                                D('House_village_user_bind')->where($conditionBind)->setInc('electric_price', $tmpdata['electric_price']);
                            }
                            if ($tmpdata['use_gas']) {
                                $tmpdata['gas_price'] = $tmpdata['use_gas'] * $this->village['gas_price'];
                                D('House_village_user_bind')->where($conditionBind)->setInc('gas_price', $tmpdata['gas_price']);
                            }
                            if ($tmpdata['use_property']) {
                                $floor_where['floor_id'] = $bindInfo['floor_id'];
                                $floor_where['status'] = 1;
                                $database_house_village_floor = D('House_village_floor');
                                $floor_info = $database_house_village_floor->house_village_floor_detail($floor_where);
                                $floor_info = $floor_info['detail'];

                                if(($floor_info['property_fee'] == '0.00') || (!isset($floor_info['property_fee']))){
                                    $property_fee = $this->village['property_price'];
                                }else{
                                    $property_fee = $floor_info['property_fee'];
                                }
                                $tmpdata['property_price'] = $property_fee * $bindInfo['housesize'];
                                D('House_village_user_bind')->where($conditionBind)->setInc('property_price', $tmpdata['property_price']);
                            }
                            if ($tmpdata['use_park']) {
                                $tmpdata['park_price'] = $this->village['park_price'];
                                D('House_village_user_bind')->where($conditionBind)->setInc('park_price', $this->village['park_price']);
                            }
                            $last_user_id = D('House_village_user_paylist')->data($tmpdata)->add();
                            if (!$last_user_id) {
                                $err_msg = $vv['B'] . ' 帐单导入失败';
                            }
                        }
                        if (!empty($last_user_id)) {
                            // 模板消息
                            $this->send($old_end_user_id['pigcms_id'], $last_user_id);

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
        } else {
            $this->display();
        }
    }

    public function orders()
    {
        //$uid = $_GET['uid'];
        $bind_id = $_GET['bind_id'] + 0;
        if ($bind_id) {
            //$condtion['uid'] = $uid;
            $condition['bind_id'] = $bind_id;
            $condition['village_id'] = $this->village_id;
            $condition['paid'] = 1;

            import('@.ORG.merchant_page');
            $count_order = D('House_village_pay_order')->where($condition)->count();
            $p = new Page($count_order, 20, 'page');
            $order_list = D('House_village_pay_order')->where($condition)->order('order_id desc')->limit($p->firstRow . ',' . $p->listRows)->select();

            $database_house_village_property_paylist = D('House_village_property_paylist');
            $pay_list = $database_house_village_property_paylist->where(array('village_id'=>$this->village_id))->select();

            if(!empty($pay_list)){
                foreach($order_list as $Key=>$order){
                    foreach($pay_list as $pay_info){
                        if($order['order_id'] ==  $pay_info['order_id']){
                            $order_list[$Key]['property_time_str'] = date('Y-m-d',$pay_info['start_time']) . '至' . date('Y-m-d',$pay_info['end_time']);
                        }
                    }
                }
            }

            $result['order_list'] = $order_list;
            $result['pagebar'] = $p->show();
            $this->assign('order_list', $result);

        }
        $this->display();
    }

    public function village_order()
    {
        $village_id = $this->village_id;
        if ($village_id) {
            $condition['village_id'] = $this->village_id;
            $condition['paid'] = 1;

            $result = D('House_village_pay_order')->get_limit_list_page($condition);

            $this->assign('order_list', $result);
        }

        $this->display();
    }

    public function change()
    {
        $village_id = $this->village_id;
        $strids = isset($_POST['strids']) ? htmlspecialchars($_POST['strids']) : '';
        if ($strids) {
            $array = explode(',', $strids);
            $usernums = $orderids = array();
            foreach ($array as $val) {
                $t = explode('_', $val);
                if ($t[1]) {
                    $orderids[] = $t[1];
                }
            }

            $orderids && D('House_village_pay_order')->where(array('village_id' => $village_id, 'order_id' => array('in', $orderids)))->save(array('is_pay_bill' => 1));
        }
        exit(json_encode(array('error_code' => 0)));
    }

    public function send($old_end_user_id, $last_user_id)
    {
        $users = D('House_village_user_bind')->get_pay_list_open($this->village_id, 20, $last_user_id, $old_end_user_id);

        if ($users) {
            $page = $_GET['page'] ? intval($_GET['page']) : 1;

            if ($page > $users['totalPage']) {
                $this->success('导入成功！');
                exit;
            } else {
                // 模板消息
                foreach ($users['user_list'] as $userInfo) {
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_my_pay&village_id=' . $this->village_id;

                    $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                    $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $userInfo['openid'], 'first' => '您好，社区发布了一条账单信息', 'keynote2' => $userInfo['address'], 'keynote1' => $this->village['property_name'], 'remark' => '请点击查看详细信息！'));
                }

                $this->success('发送微信消息完毕，正在跳转下一页', U('User/detail_import', array('page' => $page + 1)));
                exit;
            }
        } else {
            $this->success('导入成功。');
            exit;
        }
    }

    public function send_property(){
        if(IS_AJAX){
            $database_house_village_user_bind = D('House_village_user_bind');
			$database_house_village_pay_order = D('House_village_pay_order');
            $database_user =D('User');
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            if($_POST['is_collective']){
                $bind_condition['village_id'] = $this->village_id;
                $bind_condition['parent_id'] = 0;
                $user_bind_list = $database_house_village_user_bind->where($bind_condition)->select();

                $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_pay&village_id='.$this->village_id.'&type=property';
                foreach($user_bind_list as $Key => $User){
                    $now_user = $database_user->get_user($User['uid']);
					//添加一个统计物业总时间
					$wy_num = 0;
					$wy_num_buy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('property_month_num');
					$wy_num_sbuy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('presented_property_month_num');
					$wy_num = $wy_num_buy + $wy_num_sbuy + 0; 

                    if(!empty($now_user['openid'])){
                        if($User['add_time'] > 0){
                            $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => ' 尊敬的业主，您缴纳的物业费将于 '.date('Y-m-d H:i:s',strtotime("+" .$wy_num. " months", $User['add_time'])).' 到期！', 'keynote2' => $User['address'], 'keynote1' => $this->village['property_name'], 'remark' => '请点击查看详细信息！'));
                        }else{
                            $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => ' 尊敬的业主，请您尽快缴纳您的物业费！', 'keynote2' => $User['address'], 'keynote1' => $this->village['property_name'], 'remark' => '请点击查看详细信息！'));
                        }
                    }
                }
            }else{
                $pigcms_id = $_POST['pigcms_id'] + 0;
                $usernum = $_POST['usernum'];

                if(empty($pigcms_id) || empty($usernum)){
                    exit(json_encode(array('msg'=>'传递参数有误！','status'=>0)));
                }

                $bind_condition['pigcms_id'] = $pigcms_id;
                $bind_condition['usernum'] = $usernum;

                $house_village_user_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
                if(!$house_village_user_bind_info){
                    exit(json_encode(array('msg'=>'该用户不存在！','status'=>0)));
                }

                $now_user = $database_user->get_user($house_village_user_bind_info['uid']);
				
				//添加一个统计物业总时间
				$wy_num_buy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('property_month_num');
				$wy_num_sbuy = $database_house_village_pay_order->where(array('bind_id'=>$house_village_user_bind_info['pigcms_id'],'uid'=>$house_village_user_bind_info['uid'],'order_type'=>'property','paid'=>1))->Sum('presented_property_month_num');
				$wy_num = $wy_num_buy + $wy_num_sbuy + 0;
				
                if(!empty($now_user['openid'])){
                    $href = C('config.site_url') . '/wap.php?g=Wap&c=House&a=village_pay&village_id='.$this->village_id.'&type=property';
                    $model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>   '尊敬的业主，您缴纳的物业费将于 '.date('Y-m-d H:i:s',strtotime("+" .$wy_num. " months", $house_village_user_bind_info['add_time'])).' 到期！', 'keynote1' =>$this->village['property_name'], 'keynote2' =>$house_village_user_bind_info['address'], 'remark' => '请点击查看详细信息！'));
                }
            }
            exit(json_encode(array('msg'=>'发送微信消息完毕！','status'=>1)));
        }else{
            $this->error('访问页面有误！~~~');
        }
    }


    public function user_delete(){
        $pigcms_id = $_GET['pigcms_id'] + 0;
        $usernum = $_GET['usernum'];

        $database_house_village_user_bind = D('House_village_user_bind');
        $bind_condition['pigcms_id'] = $pigcms_id;
        $bind_condition['usernum'] = $usernum;
        $bind_condition['parent_id'] = 0;
        $now_bind_info = $database_house_village_user_bind->where($bind_condition)->find();
        if(!$now_bind_info){
            $this->error('该信息不存在！');
        }

        $insert_id = $database_house_village_user_bind->where($bind_condition)->delete();
        if($insert_id){
			$family_condition['village_id'] = $now_bind_info['village_id'];
			$family_condition['parent_id'] = $now_bind_info['pigcms_id'];
			$database_house_village_user_bind->where($family_condition)->delete();
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！');
        }
    }

    public function user_data(){
        $database_house_village_user_bind = D('House_village_user_bind');
        $where['village_id'] = $this->village_id;
        $where['parent_id'] = 0;
        //业主分析表start
        $user_list = $database_house_village_user_bind->where($where)->group('uid')->field('*,count(*) as house_sum')->select();

        $uidArr = array();
        foreach($user_list as $user){
            $uidArr[] = $user['uid'];
        }

        $database_user = D('User');
        $user_condition['uid'] = array('in',$uidArr);
        $user_condition['open_id'] != '';
        $wx_user_list = $database_user->where($user_condition)->select();

        $wx_sum = 0;
        foreach($user_list as $Key => $user){
            foreach($wx_user_list as $wx_key => $wx_user){
                if(($user['uid'] == $wx_user['uid']) && !empty($wx_user['openid'])){
                    $wx_sum += $user['house_sum'];
                }
            }
        }

        $count_user = 0;
        foreach($user_list as $user){
            $count_user += $user['house_sum'];
        }
        $this->assign('count_user' , $count_user);
        $this->assign('wx_user_count' , $wx_sum);
        //业主分析表end

        //停车位start
        $part_count = $database_house_village_user_bind->where(array('park_flag'=>1,'village_id'=>$this->village_id))->count();
        $this->assign('part_count' , $part_count);
        //停车位end


        $this->display();
    }



    public function user_export(){
        $find_type = $_GET['find_type'];
        $find_value = $_GET['find_value'];
        $is_platform = $_GET['is_platform'] + 0;
        if ($find_value) {
            if ($find_type == 1) {
                $where['usernum'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 2) {
                $where['name'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 3) {
                $where['phone'] = array('like', '%' . $find_value . '%');
            } else if ($find_type == 4) {
                $where['address'] = array('like', '%' . $find_value . '%');
            }
        }

        $where['is_platform'] = $is_platform;

        $village_id = $this->village_id;
        $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 99999999, $where);
        $user_list = $user_list['user_list'];

        if(count($user_list) <= 0 ){
            $this->error('无数据导出！');
        }

        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

        $title = $this->village['village_name'] . '社区-业主列表';

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $length = ceil(count($user_list)/1000);

        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);

            $objExcel->getActiveSheet()->setTitle('共' . count($user_list) . '个用户');
            $objActSheet = $objExcel->getActiveSheet();

            $objActSheet->setCellValue('A1', '业主编号');
            $objActSheet->setCellValue('B1', '姓名');
            $objActSheet->setCellValue('C1', '手机号');
            $objActSheet->setCellValue('D1', '住宅类型');
            $objActSheet->setCellValue('E1', '住址');
            $objActSheet->setCellValue('F1', '待缴费用');
            $objActSheet->setCellValue('G1', '停车位');
            $objActSheet->setCellValue('H1', '房子大小');

            if (!empty($user_list)) {
                $index = 2;

                $cell_list = range('A','H');
                foreach ($cell_list as $cell) {
                    $objActSheet->getColumnDimension($cell)->setWidth(40);
                }

                foreach ($user_list as $value) {
                    $objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
                    $objActSheet->setCellValueExplicit('B' . $index, $value['name']);
                    $objActSheet->setCellValueExplicit('C' . $index, $value['phone']);

                    if($value['floor_type_name']){
                        $objActSheet->setCellValueExplicit('D' . $index, $value['floor_type_name']);
                    }else{
                        $objActSheet->setCellValueExplicit('D' . $index, '暂无');
                    }

                    $objActSheet->setCellValueExplicit('E' . $index, $value['address']);

                    $village_price = "";
                    if($water_price = floatval($value['water_price'])){
                        $village_price .= "水费：" . $water_price . chr(10);
                    }

                    if($electric_price = floatval($value['electric_price'])){
                        $village_price .= "电费：" . $electric_price . chr(10);
                    }

                    if($gas_price = floatval($value['gas_price'])){
                        $village_price .= "燃气费：" . $gas_price . chr(10);
                    }

                    if($park_price = floatval($value['park_price'])){
                        $village_price .= "停车费：" . $park_price . chr(10);
                    }

                    $objActSheet->setCellValueExplicit('F' . $index, $village_price);

                    if($value['park_flag'] > 0){
                        $objActSheet->setCellValueExplicit('G' . $index, '有');
                    }else{
                        $objActSheet->setCellValueExplicit('G' . $index, '无');
                    }

                    $objActSheet->setCellValueExplicit('H' . $index, $value['housesize']);
                    $index++;
                }
            }
            sleep(2);
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
            header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }
    }
	
	#申请解绑审核 - wangdong
	public function audit_unbind(){

		$database_house_village_user_unbind = D('House_village_user_unbind');	

		$database_house_village_floor = D('House_village_floor');

		$database_house_village_user_vacancy = D('House_village_user_vacancy');

		$condition['village_id'] = $this->village_id;

		$lists = $database_house_village_user_unbind->where($condition)->order('itemid DESC')->select();

		foreach($lists as $k=>&$v){

			$floor_info = $database_house_village_floor->where(array('floor_id'=>$v['floor_id']))->find();

			$room_info = $database_house_village_user_vacancy->where(array('pigcms_id'=>$v['room_id']))->find();

			$v['address'] = $floor_info['floor_layer']." - ".$floor_info['floor_name']." - ".$room_info['layer']."#".$room_info['room'];

		}

		$this->assign("lists" , $lists);

		$this->display();
		
	}
	
	#修改/审核用户申请解绑信息 - wangdong
	
	public function audit_unbind_edit(){
		
		$database_house_village_user_unbind = D('House_village_user_unbind');	
			
		$database_house_village_floor = D('House_village_floor');
		
		$database_house_village_user_bind = D('House_village_user_bind');

		$database_house_village_user_vacancy = D('House_village_user_vacancy');
		
		if(IS_POST){
			
			$itemid = $_POST['itemid'] + 0;
			
			$status = $_POST['status'] + 0;
			
			if(!$itemid)  $this->error('传递参数错误！~~~');
			
			$where['itemid'] = $itemid;
			
			$data['status']   = $status;	
			$data['edittime'] = time();
			$save_id = $database_house_village_user_unbind -> where($where) -> data($data)->save();
			
			#审核不通过/审核中 不删除绑定信息
			if($status==1 || $status==2){
				
				if($save_id) $this->success('操作成功！',U('User/audit_unbind')); else $this->error('操作失败！');
			
			# 审核通过 删除绑定（要判断是否是管理员 如果是 那么删除房间绑定信息和管理员绑定信息 以及 该房间绑定的亲属/租客）	
			}elseif($status==3){
		
				if($save_id){
				
					$info_where['itemid']     = $itemid;
					$info_where['village_id'] = $this->village_id;
					$info = $database_house_village_user_unbind->where($info_where)->find();
					#如果是管理员/替换管理员 先清空房间绑定信息 vacancy 在删除绑定信息/亲属/租客 bind 
					if($info['type']==0 || $info['type']==3){
						
						$room['status'] = 1;
						$room['uid'] = $room['type'] = $room['park_flag']= 0;
						$room['name'] = $room['phone'] = $room['memo'] = "";
						$room['housesize'] = 0.00;
						
						$room_where['pigcms_id'] = $info['room_id'];
						$room_where['village_id'] = $info['village_id'];
						$reset_id = $database_house_village_user_vacancy->where($room_where)->data($room)->save();
						
						#房间清除完成 删除绑定信息
						if($reset_id){
							
							#先删除房主信息
							
							$bind_info['uid']        = $info['uid'];
							$bind_info['name']       = $info['name'];
							$bind_info['phone']      = $info['phone'];
							$bind_info['floor_id']   = $info['floor_id'];
							$bind_info['vacancy_id'] = $info['room_id'];
							$bind_info['village_id'] = $info['village_id'];
							$bind_info['village_id'] = $info['village_id'];
							$bind_info['type']       = $info['type'];
							$bind_info['pigcms_id']  = $info['bind_id'];
							$del_0_id = $database_house_village_user_bind->where($bind_info)->delete();
						
							#再删除亲属/租客信息
							if($del_0_id){
								$bind_info_1['parent_id'] = $info['bind_id'];
								$bind_info_1['type']      = array('in' , '1,2');	
								$del_1_id = $database_house_village_user_bind->where($bind_info_1)->delete(); 
							}
							$this->success('操作成功！',U('User/audit_unbind'));
						}else{
							
							$this->error('操作失败！');
						}
						
					#如果是亲属/租客	删除绑定信息 bind
					}elseif($info['type']==1 || $info['type']==2){
						
						$bind_info['uid']        = $info['uid'];
						$bind_info['name']       = $info['name'];
						$bind_info['phone']      = $info['phone'];
						$bind_info['floor_id']   = $info['floor_id'];
						$bind_info['vacancy_id'] = $info['room_id'];
						$bind_info['village_id'] = $info['village_id'];
						$bind_info['village_id'] = $info['village_id'];
						$bind_info['type']       = $info['type'];
						$bind_info['pigcms_id']  = $info['bind_id'];
						$del_0_id = $database_house_village_user_bind->where($bind_info)->delete();
						if($del_0_id) $this->success('操作成功！',U('User/audit_unbind')); else $this->error('操作失败！');
					}
				
				}else{
					$this->error('操作失败！');
				}
				
			}
			
		}else{
		
			$itemid = $_GET['itemid']+0;
			
			if(!$itemid)  $this->error('传递参数错误！~~~');

			$condition['itemid'] = $itemid;
			
			$edit = $database_house_village_user_unbind->where($condition)->find();
			
			$floor_info = $database_house_village_floor->where(array('floor_id'=>$edit['floor_id']))->find();
	
			$room_info = $database_house_village_user_vacancy->where(array('pigcms_id'=>$edit['room_id']))->find();
	
			$edit['address'] = $floor_info['floor_layer']." - ".$floor_info['floor_name']." - ".$room_info['layer']."#".$room_info['room'];
			
			$this->assign('edit' , $edit);
		
			$this->display();
		}
		
	}
	
	#删除 解绑信息 - wangdong
	public function audit_unbind_del(){
		
		$itemid = $_GET['itemid'] + 0;
		
		$village_id = $this->village_id;
		
		if(!$itemid || !$village_id)  $this->error('传递参数错误！~~~');
		
		$condition['itemid']     = $itemid;
		$condition['village_id'] = $village_id;
		 
		$database_house_village_user_unbind = D('House_village_user_unbind');
		$delete_id = $database_house_village_user_unbind->where($condition)->delete();
		
		if($delete_id) $this->success('删除成功！',U('User/audit_unbind')); else $this->error('删除失败！');
		
			
	}
	
	#检测是否存在用户
	public function ajax_empty_user_info(){
		
		if(IS_AJAX){
			
			$phone = trim($_POST['phone']);
			if(!$phone) $this->error('参数传递错误！~~~~');
			
			$batabase_user = D('User');
			$info = $batabase_user->where(array('phone'=>$phone,'status'=>1))->count();
			if($info>0){
				exit(json_encode(array('status'=>0,'msg'=>'')));
			}else{
				exit(json_encode(array('status'=>1,'msg'=>'不存在此用户')));
			}
		}
			
	}
	
}