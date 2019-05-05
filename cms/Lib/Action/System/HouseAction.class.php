<?php

/*
 * 社区O2O功能
 *
 */

class HouseAction extends BaseAction{
    public function village(){
        //搜索
        if (!empty($_POST['keyword'])) {
            if ($_POST['searchtype'] == 'village_id') {
                $condition_house_village['village_id'] = $_POST['keyword'];
            } else if ($_POST['searchtype'] == 'village_name') {
                $condition_house_village['village_name'] = array('like', '%' . $_POST['keyword'] . '%');
            } else if ($_POST['searchtype'] == 'property_name') {
                $condition_house_village['property_name'] = array('like', '%' . $_POST['keyword'] . '%');
            } else if ($_POST['searchtype'] == 'property_phone') {
                $condition_house_village['property_phone'] = array('like', '%' . $_POST['keyword'] . '%');
            }
        }
        if($condition_house_village){
			$count	=	10000;
        }else{
			$count	=	30;
        }
        $database_house_village = D('House_village');
        $count_village = $database_house_village->where($condition_house_village)->count();
        import('@.ORG.system_page');
        $p = new Page($count_village, $count);
        $village_list = $database_house_village->field(true)->where($condition_house_village)->order('`village_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $this->assign('village_list', $village_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->display();
    }

	//小区添加
	public function village_add(){
		if(IS_POST){
			$_POST['pwd'] = md5($_POST['pwd']);
			$database_house_village = D('House_village');
			$_POST['add_time'] = time();
			if($database_house_village->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->display();
		}
	}
	//小区添加
	public function village_edit(){
		$database_house_village = D('House_village');
		if(IS_POST){
			if($_POST['pwd']){
				$_POST['pwd'] = md5($_POST['pwd']);
			}else{
				unset($_POST['pwd']);
			}
			if($database_house_village->data($_POST)->save()){
				$this->success('编辑成功！');
			}else{
				$this->error('编辑失败！请重试~');
			}
		}else{
			$now_village = $database_house_village->field(true)->where(array('village_id'=>$_GET['village_id']))->find();
			if(empty($now_village)){
				$this->frame_error_tips('当前小区不存在');
			}
			$this->assign('now_village',$now_village);

			$this->display();
		}
	}

	//小区导入
	public function village_import(){
		if(IS_POST){
			if ($_FILES['file']['error'] != 4) {
				$upload_dir = './upload/excel/village/'.date('Ymd').'/';
				if (!is_dir($upload_dir)) {
					mkdir($upload_dir, 0777, true);
				}
				import('ORG.Net.UploadFile');
				$upload = new UploadFile();
				$upload->maxSize = 10 * 1024 * 1024;
				$upload->allowExts = array('xls','xlsx');
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
						$database_house_village = D('House_village');
						$last_village_id = 0;
						$err_msg = '';
						foreach ($result as $kk => $vv) {
							if($vv['A'] === null && $vv['B'] === null && $vv['C'] === null && $vv['D'] === null && $vv['E'] === null && $vv['F'] === null && $vv['G'] === null && $vv['H'] === null && $vv['I'] === null && $vv['J'] === null && $vv['K'] === null && $vv['L'] === null) continue;
							if(empty($vv['A'])){
								$err_msg = '请填写小区名称！';
								continue;
							}
							if(empty($vv['B'])){
								$err_msg = '请填写小区地址！';
								continue;
							}
							if(empty($vv['C'])){
								$err_msg = '请填写物业公司名称！';
								continue;
							}
							if(empty($vv['D'])){
								$err_msg = '请填写物业联系地址！';
								continue;
							}
							if(empty($vv['E'])){
								$err_msg = '请填写物业联系电话！';
								continue;
							}
							if(empty($vv['F'])){
								$err_msg = '请填写管理帐号！';
								continue;
							}
							if(empty($vv['G'])){
								$err_msg = '请填写管理密码！';
								continue;
							}

							$tmpdata = array();
							$tmpdata['village_name'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
							//检测小区是否已存在
							if($database_house_village->field('`village_id`')->where(array('village_name'=>$tmpdata['village_name']))->find()){
								$err_msg = $vv['A'].' 已存在！';
								continue;
							}

							$tmpdata['village_address'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
							$tmpdata['property_name'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
							$tmpdata['property_address'] = htmlspecialchars(trim($vv['D']), ENT_QUOTES);
							$tmpdata['property_phone'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
							$tmpdata['account'] = htmlspecialchars(trim($vv['F']), ENT_QUOTES);
							$tmpdata['pwd'] = md5(htmlspecialchars(trim($vv['G']), ENT_QUOTES));
							!empty($vv['H']) && $tmpdata['property_price'] = htmlspecialchars(trim($vv['H']));
							!empty($vv['I']) && $tmpdata['water_price'] = htmlspecialchars(trim($vv['I']), ENT_QUOTES);
							!empty($vv['J']) && $tmpdata['electric_price'] = htmlspecialchars(trim($vv['J']));
							!empty($vv['K']) && $tmpdata['gas_price'] = htmlspecialchars(trim($vv['K']), ENT_QUOTES);
							!empty($vv['L']) && $tmpdata['park_price'] = htmlspecialchars(trim($vv['L']), ENT_QUOTES);
							$tmpdata['status'] = 0;
							$tmpdata['add_time'] = time();
							$last_village_id = $database_house_village->data($tmpdata)->add();
							if(!$last_village_id){
								$err_msg = $vv['A'].' 导入失败！';
							}
						}
						if(!empty($last_village_id)){
							$this->frame_submit_tips(1,'导入成功');
							// $this->success('导入成功');
						}else{
							// $this->error('导入失败');
							$this->frame_submit_tips(0,'导入失败！原因：'.$err_msg);
						}
					}
				} else {
					// $this->error($upload->getErrorMsg());
					$this->frame_submit_tips(0,$upload->getErrorMsg());
				}
			}
			// $this->error('文件上传失败');
			$this->frame_submit_tips(0,'文件上传失败');
		}else{
			$this->display();
		}
	}

    public function edit() {
        $this->assign('bg_color', '#F3F3F3');

        $database_user = D('User');
        $condition_user['uid'] = intval($_GET['uid']);
        $now_user = $database_user->field(true)->where($condition_user)->find();
        if (empty($now_user)) {
            $this->frame_error_tips('没有找到该用户信息！');
        }

        $levelDb = M('User_level');
        $tmparr = $levelDb->field(true)->order('id ASC')->select();
        $levelarr = array();
        if ($tmparr) {
            foreach ($tmparr as $vv) {
                $levelarr[$vv['level']] = $vv;
            }
        }

        $this->assign('levelarr', $levelarr);
        $this->assign('now_user', $now_user);

        $this->display();
    }

    public function amend() {
        if (IS_POST) {
            $database_user = D('User');
            $condition_user['uid'] = intval($_POST['uid']);
            $now_user = $database_user->field(true)->where($condition_user)->find();
            if (empty($now_user)) {
                $this->error('没有找到该用户信息！');
            }
            $condition_user['uid'] = $now_user['uid'];
            $data_user['nickname'] = $_POST['nickname'];
            $data_user['phone'] = $_POST['phone'];
            if ($_POST['pwd']) {
                $data_user['pwd'] = md5($_POST['pwd']);
            }
            $data_user['sex'] = $_POST['sex'];
            $data_user['province'] = $_POST['province'];
            $data_user['city'] = $_POST['city'];
            $data_user['qq'] = $_POST['qq'];
            $data_user['status'] = $_POST['status'];
			$data_user['youaddress'] = trim($_POST['youaddress']);
			$data_user['truename'] = trim($_POST['truename']);

            $_POST['set_money'] = floatval($_POST['set_money']);
            if (!empty($_POST['set_money'])) {
                if ($_POST['set_money_type'] == 1) {
                    $data_user['now_money'] = $now_user['now_money'] + $_POST['set_money'];
                } else {
                    $data_user['now_money'] = $now_user['now_money'] - $_POST['set_money'];
                }
                if ($data_user['now_money'] < 0) {
                    $this->error('修改后，余额不能小于0');
                }
            }

            $_POST['set_score'] = intval($_POST['set_score']);
            if (!empty($_POST['set_score'])) {
                if ($_POST['set_score_type'] == 1) {
                    $data_user['score_count'] = $now_user['score_count'] + $_POST['set_score'];
                } else {
                    $data_user['score_count'] = $now_user['score_count'] - $_POST['set_score'];
                }
                if ($data_user['score_count'] < 0) {
                    $this->error('修改后，'.$this->config['score_name'].'不能小于0');
                }
            }

            $data_user['level'] = intval($_POST['level']);

            if ($database_user->where($condition_user)->data($data_user)->save()) {
                if (!empty($_POST['set_money'])) {
                    D('User_money_list')->add_row($now_user['uid'], $_POST['set_money_type'], $_POST['set_money'], '管理员后台操作', false);
                }
                if (!empty($_POST['set_score'])) {
                    D('User_score_list')->add_row($now_user['uid'], $_POST['set_score_type'], $_POST['set_score'], '管理员后台操作', false);
                }
                $this->success('Success');
            } else {
                $this->error('修改失败！请重试。');
            }
        } else {
            $this->error('非法访问！');
        }
    }

	//平台提现
	public function companypay(){
		if(IS_POST){
			if(!$village_info=D('House_village')->field('village_name,property_phone')->where('village_id='.(int)$_POST['village_id'])->select()){
				$this->error('小区不存在！');
			}
			sort($_POST['orderid']);
			$orderids = implode(',',$_POST['orderid']);
			$data['pay_type'] = 'house';
			$data['pay_id'] = $_POST['village_id'];
			$data['phone'] = $village_info[0]['property_phone'];
			$data['money'] = $_POST['money'];
			$data['desc'] = '小区'.$village_info[0]['village_name'].'订单对账|订单号('.$orderids.')'.'|转账'.(float)($_POST['money']/100).' 元';
			$data['status'] = 0;
			$data['add_time'] = time();

			$model=new Model();
			$where['order_id']=array('in',$orderids);
			if($model->table(C('DB_PREFIX').'companypay')->add($data)&&$model->table(C('DB_PREFIX').'house_village_pay_order')->where($where)->setField('is_pay_bill',1)){
				$this->success("提现申请成功！");
			}else{
				$this->error("提现失败！请联系管理员！");
			}
		}else{
			$this->error('您提交的数据不正确');
		}
	}

    public function money_list() {
        $this->assign('bg_color', '#F3F3F3');


        $database_user_money_list = D('User_money_list');
        $condition_user_money_list['uid'] = intval($_GET['uid']);

        $count = $database_user_money_list->where($condition_user_money_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);

        $money_list = $database_user_money_list->field(true)->where($condition_user_money_list)->order('`time` DESC')->select();

        $this->assign('pagebar', $p->show());
        $this->assign('money_list', $money_list);
        $this->display();
    }

    public function score_list() {
        $this->assign('bg_color', '#F3F3F3');


        $database_user_score_list = D('User_score_list');
        $condition_user_score_list['uid'] = intval($_GET['uid']);

        $count = $database_user_score_list->where($condition_user_score_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);

        $score_list = $database_user_score_list->field(true)->where($condition_user_score_list)->order('`time` DESC')->select();

        $this->assign('pagebar', $p->show());
        $this->assign('score_list', $score_list);
        $this->display();
    }

    /*     * *导入客户页**** */

    public function import() {

        $this->display();
    }

    /*     * *导入客户页**** */

    public function execimport() {
        if ($_FILES['file']['error'] != 4) {

            $getupload_dir = "/upload/excel/user/" . date('Ymd') . '/';
            $upload_dir = "." . $getupload_dir;
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
                //$reader = PHPExcel_IOFactory::createReader('Excel5');
                $fileType = PHPExcel_IOFactory::identify($path); //文件名自动判断文件类型
                $objReader = PHPExcel_IOFactory::createReader($fileType);
                $excelObj = $objReader->load($path);
                $result = $excelObj->getActiveSheet()->toArray(null, true, true, true);
                if (!empty($result) && is_array($result)) {
                    unset($result[1]);
                    $user_importDb = D('User_import');
                    foreach ($result as $kk => $vv) {
                        if (empty($vv['A']) || empty($vv['B']) || empty($vv['C']))
                            continue;
                        $tmpdata = array();
                        $tmpdata['ppname'] = htmlspecialchars(trim($vv['A']), ENT_QUOTES);
                        $tmpdata['telphone'] = htmlspecialchars(trim($vv['B']), ENT_QUOTES);
                        $tmpdata['address'] = htmlspecialchars(trim($vv['C']), ENT_QUOTES);
                        !empty($vv['D']) && $tmpdata['mer_id'] = intval(trim($vv['D']));
                        !empty($vv['E']) && $tmpdata['memberid'] = htmlspecialchars(trim($vv['E']), ENT_QUOTES);
                        !empty($vv['F']) && $tmpdata['level'] = intval(trim($vv['F']));
                        !empty($vv['G']) && $tmpdata['qq'] = htmlspecialchars(trim($vv['G']), ENT_QUOTES);
                        !empty($vv['H']) && $tmpdata['email'] = htmlspecialchars(trim($vv['H']), ENT_QUOTES);
                        !empty($vv['I']) && $tmpdata['money'] = intval(trim($vv['I']));
                        !empty($vv['J']) && $tmpdata['integral'] = htmlspecialchars(trim($vv['J']), ENT_QUOTES);
                        !empty($vv['K']) && $tmpdata['useraccount'] = htmlspecialchars(trim($vv['K']), ENT_QUOTES);
                        if (!empty($vv['L'])) {
                            $tmpdata['pwdmw'] = trim($vv['L']);
                            $tmpdata['pwd'] = md5($tmpdata['pwdmw']);
                        }
                        $tmpdata['isuse'] = 0;
                        $tmpdata['addtime'] = time();
                        $user_importDb->add($tmpdata);
                    }
                    if (!empty($tmpdata)) {
                        $this->dexit(array('error' => 0));
                    } else {
                        $this->dexit(array('error' => 1, 'msg' => '导入失败！'));
                    }
                }
            } else {
                $this->dexit(array('error' => 1, 'msg' => $upload->getErrorMsg()));
            }
        }
        $this->dexit(array('error' => 1, 'msg' => '文件上传失败！'));
    }

    /*     * *导入客户的列表页**** */

    public function importlist() {
        $user_importDb = D('User_import');
        $count_userimportDb = $user_importDb->where('22=22')->count();
        import('@.ORG.system_page');
        $p = new Page($count_userimportDb, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $tmpdatas = $user_importDb->where('22=22')->order('id ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('userimport', $tmpdatas);
        $this->display();
    }

    /*     * *导入客户的列表页**** */

    public function levellist() {
        $user_levelDb = D('User_level');
        $count_userlevelDb = $user_levelDb->count();
        import('@.ORG.system_page');
        $p = new Page($count_userlevelDb, 20);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $tmpdatas = $user_levelDb->where('22=22')->order('id ASC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('userlevel', $tmpdatas);
        $this->display();
    }

    /*     * *添加等级**** */

    public function addlevel() {
        $levelDb = M('User_level');
        $tmparr = $levelDb->where('22=22')->order('level DESC')->find();
        $level = 0;
        if (!empty($tmparr)) {
            $level = $tmparr['level'];
        }
        $level = $level + 1;
        if (IS_POST) {
            $lid = intval($_POST['lid']);
            if (!($lid > 0)) {
                $newdata = array('level' => $level);
            }
            $lname = trim($_POST['lname']);
            if (empty($lname))
                $this->error('等级名称没有填写！');
            $newdata['lname'] = $lname;

            $integral = intval($_POST['integral']);
            if (!($integral > 0))
                $this->error('等级'.$this->config['score_name'].'没有填写！');
            $newdata['integral'] = $integral;

            $newdata['icon'] = trim($_POST['icon']);
            $newdata['type'] = trim($_POST['fltype']);
            $newdata['boon'] = trim($_POST['boon']);
            $newdata['description'] = trim($_POST['description']);

            if ($lid > 0) {
                $inser_id = $levelDb->where(array('id' => $lid))->save($newdata);
            } else {
                $inser_id = $levelDb->add($newdata);
            }
            if ($inser_id) {
                $this->success('保存成功！');
            } else {
                $this->error('保存失败！');
            }
        } else {
            $lid = intval($_GET['lid']);
            $tmpdata = $levelDb->where(array('id' => $lid))->find();
            if (empty($tmpdata)) {
                $tmpdata = array('id' => 0, 'level' => $level, 'lname' => '', 'integral' => '', 'icon' => '', 'boon' => '', 'type' => 0, 'description' => '');
            }
            $this->assign('leveldata', $tmpdata);
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


        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
        }

        if(isset($_GET['searchstatus'])){
            $condition['is_pay_bill'] = $_GET['searchstatus'];
        }


    	$village_id = $_GET['village_id'];
    	if($village_id){
    		$condition['village_id'] = $village_id;
    		$condition['paid'] = 1;
			$condition['pay_type'] = 1;
    		$result = D('House_village_pay_order')->get_limit_list_page($condition,20,true);

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

    public function change(){
    	$village_id = $_POST['village_id'];
    	$strids = isset($_POST['strids']) ? htmlspecialchars($_POST['strids']) : '';
    	if ($strids && $village_id) {
    		$array = explode(',', $strids);
            $array && D('House_village_pay_order')->where(array('village_id' => $village_id, 'order_id' => array('in', $array)))->save(array('is_pay_bill' => 1));
    	}
    	exit(json_encode(array('error_code' => 0)));
    }

    /*     * **删除一条导入的记录**** */

    function delimportuser() {
        $idx = (int) trim($_POST['id']);
        $user_importDb = D('User_import');
        if ($user_importDb->where(array('id' => $idx))->delete()) {
            $this->dexit(array('error' => 0));
        } else {
            $this->dexit(array('error' => 1));
        }
    }

    /*     * json 格式封装函数* */

    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }
	public function village_login(){
		$database_village = D('House_village');
		$condition_village['village_id'] = $_GET['village_id'];
		$now_village = $database_village->field(true)->where($condition_village)->find();
		if(empty($now_village) || $now_village['status'] == 2){
			exit('<html><head><script>window.top.toggleMenu(0);window.top.msg(0,"该小区的状态不存在！请查阅。",true,5);window.history.back();</script></head></html>');
		}
		session('house',$now_village);
		$script_name = trim($_SERVER['SCRIPT_NAME'],'/');
		if($_GET['group_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Group&a=frame_edit&group_id='.$_GET['group_id'].'&system_file='.$script_name);
		}else if($_GET['activity_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Activity&a=frame_edit&id='.$_GET['activity_id'].'&system_file='.$script_name);
		}else if($_GET['appoint_id']){
			redirect($this->config['site_url'].'/shequ.php?c=Appoint&a=frame_edit&appoint_id='.$_GET['appoint_id'].'&system_file='.$script_name);
		}else{
			redirect($this->config['site_url'].'/shequ.php');
		}
	}
	
	public function market_order()
	{
		$village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
		$where = "village_id='$village_id' AND (status=2 OR status=3)";//array('village_id' => 0, 'status' => array('in', array(2,3)));
		
		$begin_time = isset($_POST['begin_time']) ? $_POST['begin_time'] : 0;
		$end_time = isset($_POST['end_time']) ? $_POST['end_time'] : 0;
		
		$this->assign(array('village_id' => $village_id, 'begin_time' => $begin_time, 'end_time' => $end_time));

		if ($begin_time && $end_time) {
			$where .= ' AND pay_time>' .  strtotime($begin_time) . ' AND pay_time<' . strtotime($end_time . '23:59:59');
		}
		$result = D("Shop_order")->get_order_list($where, "order_id DESC", 3);
		$this->assign($result);
		$this->display();
	}

    //不同业务对账返点
    public function merchant_order(){
        $type=I('type')?I('type'):'group';
        $village_id = I('village_id');
        $time_condition ='';
        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (o.pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition['_string']=$time_condition;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }

        $order_list = D('House_village_group')->get_order_list($type,$village_id,$time_condition,1);
        $this->assign($order_list);
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

        // 设置当前的sheet
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'order_name') {
                $condition['order_name'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'phone') {
                $condition['phone'] = $_GET['keyword'] ;
            }
        }


        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['pay_time_str']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
        }

        if(isset($_GET['searchstatus'])){
            $condition['is_pay_bill'] = $_GET['searchstatus'];
        }

        $village_id = $_GET['village_id'] + 0;
        if($village_id){
            $now_village = D('House_village')->get_one($village_id);
            $condition['village_id'] = $village_id;
            $condition['paid'] = 1;
            $count = D('House_village_pay_order')->where($condition)->count();
            $length = ceil($count / 1000);

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

            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千订单');
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


    public function market_export(){
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '社区超市账单';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
        $where = "village_id='$village_id' AND (status=2 OR status=3)";

        $begin_time = isset($_GET['begin_time']) ? $_GET['begin_time'] : 0;
        $end_time = isset($_GET['end_time']) ? $_GET['end_time'] : 0;

        if ($begin_time && $end_time) {
            $where .= ' AND pay_time>' .  strtotime($begin_time) . ' AND pay_time<' . strtotime($end_time . '23:59:59');
        }

        if($village_id){
            $now_village = D('House_village')->get_one($village_id);

            $count = D('Shop_order')->where($where)->count();
            $length = ceil($count / 1000);

            $objActSheet = $objExcel->getActiveSheet();
            $objActSheet->setCellValue('A1', '订单编号');
            $objActSheet->setCellValue('B1', '下单人');
            $objActSheet->setCellValue('C1', '电话');
            $objActSheet->setCellValue('D1', '支付时间');
            $objActSheet->setCellValue('E1', '总价');
            $objActSheet->setCellValue('F1', '订单状态');
            $objActSheet->setCellValue('G1', '支付情况');

            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);
                $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千订单');

                $objActSheet->getColumnDimension('A')->setWidth(50);
                $objActSheet->getColumnDimension('B')->setWidth(50);
                $objActSheet->getColumnDimension('C')->setWidth(50);
                $objActSheet->getColumnDimension('D')->setWidth(50);
                $objActSheet->getColumnDimension('E')->setWidth(50);
                $objActSheet->getColumnDimension('F')->setWidth(50);
                $objActSheet->getColumnDimension('G')->setWidth(50);

                $result = D("Shop_order")->get_order_list($where, "order_id DESC", 3);
                if (!empty($result['order_list'])) {
                    $index = 2;
                    foreach ($result['order_list'] as $value) {
                        $objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['username']);
                        $objActSheet->setCellValueExplicit('C' . $index,  $value['userphone']);
                        $objActSheet->setCellValueExplicit('D' . $index, date('Y-m-d H:i:s',$value['pay_time']));
                        $objActSheet->setCellValueExplicit('E' . $index, $value['price']);
                        $objActSheet->setCellValueExplicit('F' . $index, strip_tags($value['status_str']));
                        $objActSheet->setCellValueExplicit('G' . $index, $value['pay_type_str']);
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
}
