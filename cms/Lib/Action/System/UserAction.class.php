<?php

/*
 * 用户中心
 *
 */

class UserAction extends BaseAction {
    public function index() {
        //if($this->system_session['level'] == 3){
            //if($this->system_session['area_id'] != 0){
                $sql_count = "SELECT count(*) FROM ". C('DB_PREFIX') . "user as u ";
                $sql = "SELECT u.* FROM ". C('DB_PREFIX') . "user as u ";

                $where = "WHERE u.openid not like '%no_use'";

                if($this->system_session['level'] == 3 && $this->system_session['area_id'] != 0){
                    $sql_count .= " LEFT JOIN ". C('DB_PREFIX') . "user_adress as a on a.uid = u.uid ";
                    $sql = "SELECT u.* , a.city as city_id FROM ". C('DB_PREFIX') . "user as u LEFT JOIN ". C('DB_PREFIX') . "user_adress as a on a.uid = u.uid ";
                    $where .= " and a.default=1 and a.city=".$this->system_session['area_id'];
                }

                if($_GET['city_id']){
                    $this->assign('city_id',$_GET['city_id']);
                    if($_GET['city_id'] != 0){
                        $sql_count .= " LEFT JOIN ". C('DB_PREFIX') . "user_adress as a on a.uid = u.uid ";
                        $sql = "SELECT u.* , a.city as city_id FROM ". C('DB_PREFIX') . "user as u LEFT JOIN ". C('DB_PREFIX') . "user_adress as a on a.uid = u.uid ";
                        $where .= " and a.default=1 and a.city=".$_GET['city_id'];
                    }
                }else{
                    $this->assign('city_id',0);
                }
                $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
                $this->assign('city',$city);

                if (!empty($_GET['keyword'])) {
                    if ($_GET['searchtype'] == 'uid') {
                        $where .= " and u.uid=".$_GET['keyword'];
                    } else if ($_GET['searchtype'] == 'nickname') {
                        $where .= " and u.nickname like '%".$_GET['keyword']. "%'";
                    } else if ($_GET['searchtype'] == 'phone') {
                        $where .= " and u.phone like '%".$_GET['keyword']. "%'";
                    }else if($_GET['searchtype'] == 'email'){
                        $where .= " and u.email like '%".$_GET['keyword']. "%'";
                    }
                }
                if ($_GET['status'] != '') {
                    $where .= " and u.status=".$_GET['status'];
                }
                if (!empty($_GET['begin_time']) && !empty($_GET['end_time'])) {
                    if ($_GET['begin_time'] > $_GET['end_time']) {
                        $this->error_tips("结束时间应大于开始时间");
                    }
                    $period = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));
                    $where .= " and u.add_time>".$period[0]." and u.add_time<".$period[1];
                }

                //排序
                $order_string = ' order by u.`uid` DESC';
                if ($_GET['sort']) {
                    switch ($_GET['sort']) {
                        case 'uid':
                            $order_string = ' order by u.`uid` DESC';
                            break;
                        case 'lastTime':
                            $order_string = ' order by u.`last_time` DESC';
                            break;
                        case 'money':
                            $order_string = ' order by u.`now_money` DESC';
                            break;
                        case 'score':
                            $order_string = ' order by u.`score_count` DESC';
                            break;
                        case 'invi_reg':
                            $order_string = ' order by u.`invitation_reg_num` DESC';
                            break;
                        case 'invi_order':
                            $order_string = ' order by u.`invitation_order_num` DESC';
                            break;
                    }
                }
                //var_dump($sql.$where.$order_string);die();
                $count = D()->query($sql_count.$where.$order_string);
                $count_user = $count[0]['count(*)'];
                if($count_user > 0){
                    import('@.ORG.system_page');
                    $p = new Page($count_user, 15);
                    $limit = " LIMIT {$p->firstRow}, {$p->listRows}";
                    $user_list = D()->query($sql.$where.$order_string.$limit);

                    $pagebar = $p->show2();
                    $this->assign('pagebar', $pagebar);
                }

                if (!empty($user_list)) {
                    import('ORG.Net.IpLocation');
                    $IpLocation = new IpLocation();
                    foreach ($user_list as &$value) {
                        $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
                        $value['last_ip_txt'] = iconv('GBK', 'UTF-8', $last_location['country']);
                    }
                }

                $database_user = D('User');
                $user_balance	=	array(
                    'count'	=>	$database_user->sum('now_money'),
                    'open'	=>	$database_user->where(array('status'=>1))->sum('now_money'),
                    'close'	=>	$database_user->where(array('status'=>2))->sum('now_money'),
                );
                $this->assign('user_balance', $user_balance);

            //}
//        }else {
//            //搜索
//            if (!empty($_GET['keyword'])) {
//                if ($_GET['searchtype'] == 'uid') {
//                    $condition_user['uid'] = $_GET['keyword'];
//                } else if ($_GET['searchtype'] == 'nickname') {
//                    $condition_user['nickname'] = array('like', '%' . $_GET['keyword'] . '%');
//                } else if ($_GET['searchtype'] == 'phone') {
//                    $condition_user['phone'] = array('like', '%' . $_GET['keyword'] . '%');
//                }
//            }
//
//
//            $condition_user['openid'] = array('notlike', '%no_use');
//            //排序
//            $order_string = '`uid` DESC';
//            if ($_GET['sort']) {
//                switch ($_GET['sort']) {
//                    case 'uid':
//                        $order_string = '`uid` DESC';
//                        break;
//                    case 'lastTime':
//                        $order_string = '`last_time` DESC';
//                        break;
//                    case 'money':
//                        $order_string = '`now_money` DESC';
//                        break;
//                    case 'score':
//                        $order_string = '`score_count` DESC';
//                        break;
//                }
//            }
//
//            //状态
//            if ($_GET['status'] != '') {
//                $condition_user['status'] = $_GET['status'];
//            }
//            if (!empty($_GET['begin_time']) && !empty($_GET['end_time'])) {
//                if ($_GET['begin_time'] > $_GET['end_time']) {
//                    $this->error_tips("结束时间应大于开始时间");
//                }
//                $period = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));
//                $condition_user['_string'] = " (add_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
//            }
//            $database_user = D('User');
//
//            $count_user = $database_user->where($condition_user)->count();
//            import('@.ORG.system_page');
//            $p = new Page($count_user, 15);
//            $user_list = $database_user->field(true)->where($condition_user)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
//
//            if (!empty($user_list)) {
//                import('ORG.Net.IpLocation');
//                $IpLocation = new IpLocation();
//                foreach ($user_list as &$value) {
//                    $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
//                    $value['last_ip_txt'] = iconv('GBK', 'UTF-8', $last_location['country']);
//                }
//            }
//            $user_balance	=	array(
//                'count'	=>	$database_user->sum('now_money'),
//                'open'	=>	$database_user->where(array('status'=>1))->sum('now_money'),
//                'close'	=>	$database_user->where(array('status'=>2))->sum('now_money'),
//            );
//
//            $this->assign('user_balance', $user_balance);
//            $pagebar = $p->show();
//            $this->assign('pagebar', $pagebar);
//        }

        $this->assign('user_list', $user_list);
        $this->assign('client', array(0=>'WAP',1=>'Apple',2=>'Android',3=>'PC',4=>'小程序',5=>'Wechat'));
        $this->display();
    }

    public function edit() {
        $this->assign('bg_color', '#F3F3F3');
        if($this->system_session['level']!=2&&!in_array(198,$this->system_session['menus'])){
            $can_recharge = 0;
        }else{
            $can_recharge = 1;
        }
        $this->assign('can_recharge',$can_recharge);

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
		if(!empty($now_user['cardid'])){
			$balance_money = D('Physical_card')->where(array('cardid'=>$now_user['cardid']))->getField('balance_money');
			$this->assign('balance_money',$balance_money);
		}
        if($now_user['free_time']>$_SERVER['REQUEST_TIME']){
            $now_user['frozen_time'] = $now_user['frozen_time']>0?date('Y-m-d',$now_user['frozen_time']):0;
            $now_user['free_time'] = $now_user['free_time']>0?date('Y-m-d',$now_user['free_time']):0;
        }else{
            $now_user['frozen_money'] = 0;
            $now_user['frozen_time'] = 0;
            $now_user['frozen_reason'] = '';
            $now_user['free_time'] = 0;
        }

        $this->assign('levelarr', $levelarr);
        $this->assign('now_user', $now_user);

        $this->display();
    }

    public function export() {
        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error(L('J_SPECIFY_TIME'));
        }else {
            set_time_limit(0);
            require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
            $title = 'Customer Summary';
            $objExcel = new PHPExcel();
            $objProps = $objExcel->getProperties();
            // 设置文档基本属性
            $objProps->setCreator($title);
            $objProps->setTitle($title);
            $objProps->setSubject($title);
            $objProps->setDescription($title);

            // 设置当前的sheet
            $begin_time = strtotime($_GET['begin_time']);
            $end_time = strtotime($_GET['end_time']);

            $where['add_time'] = array('between',array($begin_time,$end_time));

            $database_user = D('User');
            $count_user = $database_user->where($where)->count();

            $length = ceil($count_user / 1000);
            for ($i = 0; $i < $length; $i++) {
                $i && $objExcel->createSheet();
                $objExcel->setActiveSheetIndex($i);

                //$objExcel->getActiveSheet()->setTitle('第' . ($i + 1) . '个一千个用户');
                $objExcel->getActiveSheet()->setTitle( strval($i*1000+1).' - '.strval($i*1000+1000));
                $objActSheet = $objExcel->getActiveSheet();

                $objActSheet->setCellValue('A1', '用户ID');
                $objActSheet->setCellValue('B1', '昵称');
                $objActSheet->setCellValue('C1', ' ');
                $objActSheet->setCellValue('D1', '手机号');
                $objActSheet->setCellValue('E1', 'Email');
//                $objActSheet->setCellValue('F1', '省份');
//                $objActSheet->setCellValue('G1', '城市');
//                $objActSheet->setCellValue('H1', 'QQ');
                $objActSheet->setCellValue('F1', '注册时间');
//                $objActSheet->setCellValue('J1', '注册IP');
//                $objActSheet->setCellValue('K1', '最后登录时间');
//                $objActSheet->setCellValue('L1', '最后登录IP');
//                $objActSheet->setCellValue('M1', $this->config['score_name']);
//                $objActSheet->setCellValue('N1', '余额');
//                $objActSheet->setCellValue('O1', '不可提现的余额');
//                $objActSheet->setCellValue('P1', '是否手机认证');
//                $objActSheet->setCellValue('Q1', '是否关注公众号');
//                $objActSheet->setCellValue('R1', '账号是否正常');


                $user_list = $database_user->field(true)->where($where)->limit($i * 1000 . ',1000')->order('add_time desc')->select();
                if (!empty($user_list)) {
                    import('ORG.Net.IpLocation');
                    $IpLocation = new IpLocation();
                    $index = 2;
                    foreach ($user_list as $value) {

                        $objActSheet->setCellValueExplicit('A' . $index, $value['uid']);
                        $objActSheet->setCellValueExplicit('B' . $index, $value['nickname']);
                        $objActSheet->setCellValueExplicit('C' . $index, $value['truename']);
                        $objActSheet->setCellValueExplicit('D' . $index, $value['phone'] . ' ');
                        $objActSheet->setCellValueExplicit('E' . $index, $value['email']);
                        //$sex = $value['sex'] == 0 ? '未知' : ($value['sex'] == 1 ? '男' : '女');
                        //$objActSheet->setCellValueExplicit('E' . $index, $sex);

//                        $objActSheet->setCellValueExplicit('F' . $index, $value['province']);
//                        $objActSheet->setCellValueExplicit('G' . $index, $value['city']);
//                        $objActSheet->setCellValueExplicit('H' . $index, $value['qq'] . ' ');
                        $objActSheet->setCellValueExplicit('F' . $index, date('Y-m-d H:i:s', $value['add_time']));

//                        $last_location = $IpLocation->getlocation(long2ip($value['add_ip']));
//                        $add_ip = iconv('GBK', 'UTF-8', $last_location['country']);
//                        $objActSheet->setCellValueExplicit('J' . $index, $add_ip);
//
//                        $objActSheet->setCellValueExplicit('K' . $index, date('Y-m-d H:i:s', $value['last_time']));
//
//                        $last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
//                        $last_ip = iconv('GBK', 'UTF-8', $last_location['country']);
//                        $objActSheet->setCellValueExplicit('L' . $index, $last_ip);
//
//                        $objActSheet->setCellValueExplicit('M' . $index, $value['score_count'] . ' ');
//                        $objActSheet->setCellValueExplicit('N' . $index, $value['now_money'] . ' ');
//                        $objActSheet->setCellValueExplicit('O' . $index, $value['score_recharge_moeny'] . ' ');
//                        $is_check_phone = $value['is_check_phone'] == 0 ? '否' : '是';
//                        $objActSheet->setCellValueExplicit('P' . $index, $is_check_phone);
//                        $is_follow = $value['is_follow'] ? '是' : '否';
//                        $objActSheet->setCellValueExplicit('Q' . $index, $is_follow);
//                        $status = $value['status'] ? '正常' : '禁用';
//                        $objActSheet->setCellValueExplicit('R' . $index, $status);

                        $index++;
                    }
                }
                sleep(2);
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
            //header('Content-Disposition:attachment;filename="' . $title . '_' . date("Y-m-d h:i:sa", time()) . '.xls"');
            header('Content-Disposition:attachment;filename="' . $title . '_' . $_GET['begin_time'].' - '.$_GET['end_time']. '.xls"');
            header("Content-Transfer-Encoding:binary");
            $objWriter->save('php://output');
            exit();
        }
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
            $data_user['frozen_money'] = $_POST['frozen_money'];
            $data_user['frozen_reason'] = trim($_POST['frozen_reason']);
            if($_POST['frozen_money']>0){
                if(empty($_POST['frozen_time'])||empty($_POST['free_time'])){
                    $this->error("设置冻结时间必须设置【冻结时间】");
                }
                if($now_user['now_money']<$_POST['frozen_money']){
                    $this->error("冻结金额不能比当前金额大");
                }

                if(empty($_POST['frozen_reason'])){
                    $this->error("设置冻结时间必须设置【冻结理由】");
                }
            }
            if(!empty($_POST['frozen_time'])&&!empty($_POST['free_time'])){

                if ($_POST['frozen_time']>$_POST['free_time']) {
                    $this->error("结束时间应大于开始时间");
                }
                $data_user['frozen_time'] = strtotime($_POST['frozen_time']." 00:00:00");
                $data_user['free_time'] = strtotime($_POST['free_time']." 23:59:59");
            }

            $data_user['status'] = $_POST['status'];
            $data_user['youaddress'] = trim($_POST['youaddress']);
            $data_user['truename'] = trim($_POST['truename']);

            $_POST['set_money'] = floatval($_POST['set_money']);
            if (!empty($_POST['set_money']) && D('User_rechange_code')->where(array('code'=>$_POST['user_code_curr']))->find()) {
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

			$cardid = $_POST['cardid'];
			$data_user['cardid'] = $cardid;
			$card = M('Physical_card');
			if(!empty($cardid)){

				$condition_card['cardid']=$cardid;
				$res = $card->where($condition_card)->getField('cardid,uid,status');
				if(empty($res)&&!empty($res[$cardid]['uid'])&&$res[$cardid]['status']!=0&&!empty($now_user['cardid'])){
					$this->error('实体卡ID不存在,或者实体卡已绑定用户，请检查');
				}else{
					$card_data['uid'] = $now_user['uid'];
					$card_data['status'] = 1;
					$card_data['regtime'] = time();
					$card_data['last_time'] = time();
					$card_data['balance_money'] = $_POST['balance_money'];
					if(!$card->where($condition_card)->save($card_data)){
						$this->error('保存实体卡是失败');
					}
				}
			}else{
				$card->where(array('uid'=>$now_user['uid']))->save(array('uid'=>NULL,'regtime'=>NULL,'last_time'=>time()));
			}


            $data_user['level'] = intval($_POST['level']);

            if ($database_user->where($condition_user)->data($data_user)->save()) {
                if (!empty($_POST['set_money'])) {
                    $user_code = D('User_rechange_code')->where(array('code'=>$_POST['user_code_curr']))->find();
                    if($_POST['set_money_type'] == 1)
                        $msg = 'Added by Tutti Administrator '.$user_code['name'];
                    else
                        $msg = 'Deducted by Tutti Administrator '.$user_code['name'];
                    D('User_money_list')->add_row($now_user['uid'], $_POST['set_money_type'], $_POST['set_money'], '管理员后台操作 '.$user_code['name'], false,0,0,true,$msg);
                }
                if (!empty($_POST['set_score'])) {
                    D('User_score_list')->add_row($now_user['uid'], $_POST['set_score_type'], $_POST['set_score'], '管理员后台操作', false,0,0,true);
                }
                $this->success('Success');
            } else {
                $this->error(L('K_MODI_FAILED!'));
            }
        } else {
            $this->error('非法访问！');
        }
    }

    public function money_list() {
        $this->assign('bg_color', '#F3F3F3');
        $database_user_money_list = D('User_money_list');
        $condition_user_money_list['uid'] = intval($_GET['uid']);
        if($_GET['ask']){
			$condition_user_money_list['ask'] = intval($_GET['ask']);
        }
		if($_GET['ask_id']){
			$condition_user_money_list['ask_id'] = intval($_GET['ask_id']);
        }
        $count = $database_user_money_list->where($condition_user_money_list)->count();
        import('@.ORG.system_page');
        $p = new Page($count, 15);

        $money_list = $database_user_money_list->field(true)->where($condition_user_money_list)->order('`time` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

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

        $score_list = $database_user_score_list->field(true)->where($condition_user_score_list)->order('`time` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

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

            $use_money = intval($_POST['use_money']);
            if (!($use_money >= 0))
                $this->error('等级金额没有填写！');
            $newdata['use_money'] = $use_money;

            $newdata['icon'] = trim($_POST['icon']);
            $newdata['validity'] = trim($_POST['validity']);
            $newdata['type'] = trim($_POST['fltype']);
            $newdata['boon'] = trim($_POST['boon']);
            $newdata['description'] = trim($_POST['description']);
            $newdata['spread_user_give_score'] = empty($_POST['spread_user_give_score'])?0:$_POST['spread_user_give_score'];
            $newdata['spread_user_give_moeny'] =  empty($_POST['spread_user_give_moeny'])?0:$_POST['spread_user_give_moeny'];
            $newdata['score_clean_time'] =empty($_POST['score_clean_time'])?'':$_POST['score_clean_time'];
            $newdata['score_clean_percent'] =empty($_POST['score_clean_percent'])?0:$_POST['score_clean_percent'];

            if ($lid > 0) {
                $inser_id = $levelDb->where(array('id' => $lid))->save($newdata);
            } else {
                $inser_id = $levelDb->add($newdata);
            }
            if ($inser_id) {
                $this->success(L('J_SUCCEED3'));
            } else {
                $this->error(L('J_FAILED_SAVE'));
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

    /*     * **删除一条导入的记录**** */

    function delimportuser() {
        $idx = (int) trim($_POST['id']);
        $user_importDb = D('User_import');
        if ($user_importDb->where(array('id' => $idx))->delete()) {
        	$this->success(L('J_DELETION_SUCCESS'));
        } else {
        	$this->error('删除失败' . $this->_get('id'));
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
    //	用户实名认证列表
	public function authentication(){
		$status	=	$_GET['status'];
		if(empty($status)){
			$where['authentication_status']	=	0;
			$order['authentication_time']	=	'desc';
        }else{
			$where['authentication_status']	=	array('neq',0);
			$order['examine_time']	=	'desc';
        }
		$card = M('User_authentication');
        $count_card = $card->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count_card, 20);
        $card_list	=	$card->field(true)->order($order)->limit($p->firstRow . ',' . $p->listRows)->where($where)->select();
        foreach($card_list as &$v){
			if(strstr($v['authentication_img'], ',')){
				$merchant_image_class = new scenic_image();
				$v['authentication_img'] = $merchant_image_class->get_image_by_path($v['authentication_img'],$this->config['site_url'],'aguide','1');
			}else{
				$v['authentication_img'] =	$this->config['site_url'].$v['authentication_img'];
			}
        }
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->assign('card_list',$card_list);
		$this->assign('status',$status);
		$this->display();
	}
	//	审核用户实名认证
	public function check(){
		if(IS_POST){
			$where['authentication_id']	=	$_POST['authentication_id'];
			$data	=	array(
				'examine_remarks'	=>	$_POST['examine_remarks'],
				'examine_time'	=>	time(),
			);
			if($_POST['examine_remarks'] == 0){
				$data['authentication_status']	=	1;
				$user_data['real_name']	=	1;
			}else{
				$data['authentication_status']	=	2;
				$user_data['real_name']	=	3;
			}
			$save	=	M('User_authentication')->where($where)->data($data)->save();
			if($save){
				$user_where['uid']	=	$_POST['uid'];
				$sReal	=	M('User')->where($user_where)->data($user_data)->save();
				if($sReal){
					$this->success('审核成功');
				}else{
					$this->error('用户审核失败');
				}
			}else{
				$this->error('审核失败');
			}
		}
		$where['authentication_id']	=	$_GET['authentication_id'];
		$status	=	$_GET['status'];
		$userAuth	=	M('User_authentication')->field(true)->where($where)->find();
		$merchant_image_class = new scenic_image();
		if(strstr($userAuth['authentication_img'], ',')){
			$userAuth['authentication_img'] = $merchant_image_class->get_image_by_path($userAuth['authentication_img'],$this->config['site_url'],'aguide','1');
		}
		if(strstr($userAuth['authentication_back_img'], ',')){
			$userAuth['authentication_back_img'] = $merchant_image_class->get_image_by_path($userAuth['authentication_back_img'],$this->config['site_url'],'aguide','1');
		}
		if(strstr($userAuth['hand_authentication'], ',')){
			$userAuth['hand_authentication'] = $merchant_image_class->get_image_by_path($userAuth['hand_authentication'],$this->config['site_url'],'aguide','1');
		}
		$this->assign('userAuth',$userAuth);
		$this->assign('status',$status);
		$this->display();
	}
	# 车主认证
	public function authentication_car(){
		$status	=	$_GET['status'];
		if(empty($status)){
			$where['status']	=	0;
			$order['add_time']	=	'desc';
        }else{
			$where['status']	=	array('neq',0);
			$order['examine_time']	=	'desc';
        }
		$card = M('User_authentication_car');
        $count_card = $card->where($where)->count();
        import('@.ORG.system_page');
        $p = new Page($count_card, 20);
        $card_list	=	$card->field(true)->order($order)->limit($p->firstRow . ',' . $p->listRows)->where($where)->select();
        $merchant_image_class = new scenic_image();
        foreach($card_list as &$v){
			$v['authentication_img'] = $merchant_image_class->get_car_by_path($v['authentication_img'],$this->config['site_url'],'authentication_car','s');
        }
		$pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
		$this->assign('card_list',$card_list);
		$this->assign('status',$status);
		$this->display();
	}
	# 车主审核
	public function car_check(){
		if(IS_POST){
			$where['car_id']	=	$_POST['car_id'];
			$data	=	array(
				'examine_remarks'	=>	$_POST['examine_remarks'],
				'status'		=>	$_POST['status'],
				'examine_time'	=>	time(),
			);
			$save	=	M('User_authentication_car')->where($where)->data($data)->save();
			if($save){
				$this->success('审核成功');
			}else{
				$this->error('审核失败');
			}
		}
		$where['car_id']	=	$_GET['car_id'];
		$statuss	=	$_GET['statuss'];
		$userAuth	=	M('User_authentication_car')->field(true)->where($where)->find();
		$merchant_image_class = new scenic_image();
		$userAuth['authentication_img'] = $merchant_image_class->get_car_by_path($userAuth['authentication_img'],$this->config['site_url'],'authentication_car','1');
		$userAuth['authentication_back_img'] = $merchant_image_class->get_car_by_path($userAuth['authentication_back_img'],$this->config['site_url'],'authentication_car','1');
		$userAuth['drivers_license'] = $merchant_image_class->get_car_by_path($userAuth['drivers_license'],$this->config['site_url'],'authentication_car','1');
		$userAuth['driving_license'] = $merchant_image_class->get_car_by_path($userAuth['driving_license'],$this->config['site_url'],'authentication_car','1');
		$this->assign('userAuth',$userAuth);
		$this->assign('statuss',$statuss);
		$this->display();
	}

    public function recharge_list(){

        $condition_where = "`o`.`uid`=`u`.`uid` ";
        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
            } elseif ($_GET['searchtype'] == 'name') {
                $condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            } elseif ($_GET['searchtype'] == 'phone') {
                $condition_where .= " AND `u`.`phone` like '%" . htmlspecialchars($_GET['keyword']) ."%'";
            } elseif ($_GET['searchtype'] == 'order_id') {
                $condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($_GET['keyword']) . "'";
            }elseif($_GET['searchtype'] == 'uid'){
                $condition_where .= " AND `u`.`uid` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
            }

        }

//        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= 'o.' . $type . ' ' . $sort . ',';
            $order_sort .= 'o.order_id DESC';
        } else {
            $order_sort .= 'o.order_id DESC';
        }

        //if ($status != -1) {
            $condition_where .= " AND `o`.`paid`=1";
//        }

        $condition_table = array( C('DB_PREFIX').'user_recharge_order'=>'o', C('DB_PREFIX').'user'=>'u');
        $order_count = D('')->where($condition_where)->table($condition_table)->count();
        import('@.ORG.system_page');
        $p = new Page($order_count,30);
        $order_list = D('')->field('`o`.*,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order($order_sort)->limit($p->firstRow.','.$p->listRows)->select();
        $this->assign('order_list',$order_list);
        $pagebar = $p->show2();
        $this->assign('pagebar',$pagebar);
//        $this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status));
        $this->assign(array('type' => $type, 'sort' => $sort));
        $this->assign('status_list', D('Group_order')->status_list);
        $this->display();
    }

    /**
     * @return    充值详情
     */
    public function order_detail(){
        $this->assign('bg_color','#F3F3F3');
        $order = D('User_recharge_order');
        $condition_group_order['o.order_id'] = $_GET['order_id'];
        $order = $order->join('as o left join '.C('DB_PREFIX').'user u ON u.uid = o.uid')->where($condition_group_order)->find();

        if(empty($order)){
            $this->frame_error_tips('此订单不存在！');
        }

        $this->assign('now_order',$order);
        $this->display();
    }

    /**
     * @return    商家线下充值的记录
     */
    public function card_recharge_list(){
        $this->assign(D('Card_new')->offline_recharge_list(1));
        $this->display();
    }

    /**
     * @return  在线充值的记录
     */
    public function online_recharge_list(){
        $list = D('Card_new')->online_recharge_list(1);
        $this->assign($list);
        $this->display();
    }

    /**
     * @return  管理员充值列表
     */
    public function admin_recharge_list(){
        $recharge_list = M('User_money_list')->where(array('admin_id'=>array('neq','')))->select();
        $admin_list = M('Admin')->where(array('status'=>1))->select();
        $this->assign('admin_list',$admin_list);
        $where['l.admin_id'] = array('neq', 0);
        if(!empty($_GET['admin_id'])) {
            if ($_GET['admin_id'] == '0') {
                $where['l.admin_id'] = array('neq', 0);
            } else{
                $where['l.admin_id'] = $_GET['admin_id'];
            }
        }
        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =" (l.time BETWEEN ".$period[0].' AND '.$period[1].")";

        }
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'uid') {
                $where['l.uid'] = $_GET['keyword'];
            }
        }
        $recharge_list =  D('User_money_list')->get_admin_recharge_list($where,1);

        $this->assign('recharge_list',$recharge_list['list']);
        $this->assign('pagebar',$recharge_list['pagebar']);
        $this->display();
    }
    //garfunkel add
    public function send_coupon(){
        $uid = $_GET['uid'];
        $coupon = D('System_coupon');

        $where = array('status' => 1,'allow_new'=>0);
        if($this->system_session['level'] == 3)
            $where['city_id'] = $this->system_session['area_id'];

        $list = $coupon->field(true)->where($where)->order('`last_time` DESC')->select();

        foreach ($list as $k=>$v){
            $l_id = D('System_coupon_hadpull')->field(true)->where(array('uid'=>$uid,'coupon_id'=>$v['coupon_id']))->find();
            if(empty($l_id))
                $list[$k]['is_l'] = 0;
            else
                $list[$k]['is_l'] = 1;
        }

        $this->assign('list',$list);
        $this->assign('uid',$uid);

        $this->display();
    }

    public function sendCouponToUser(){
        $uid = $_POST['uid'];
        $coupon_id = $_POST['cid'];

        $uList = explode(',',$uid);

        if (count($uList) == 1){
            $result = D('System_coupon')->had_pull($coupon_id,$uid,0,$this->system_session['account']);
            if($result['error_code'] == 0){
                $sms_data['uid'] = $uid;
                $userInfo = D('User')->get_user($uid);
                $sms_data['mobile'] = $userInfo['phone'];
                $sms_data['sendto'] = 'user';
                $sms_data['tplid'] = 326488;
                $sms_data['params'] = [];
                //Sms::sendSms2($sms_data);
                $sms_txt = "Tutti has added a new coupon to your account! Please log in to your account and check available coupons for details.";
                //Sms::telesign_send_sms($userInfo['phone'],$sms_txt,1);
                //Sms::sendTwilioSms($userInfo['phone'],$sms_txt);

            }
            exit(json_encode($result));
        }else{
            foreach($uList as $v){
                $result = D('System_coupon')->had_pull($coupon_id,$v,0,$this->system_session['account']);
                if($result['error_code'] == 0){
                    $sms_data['uid'] = $v;
                    $userInfo = D('User')->get_user($v);
                    $sms_data['mobile'] = $userInfo['phone'];
                    $sms_data['sendto'] = 'user';
                    $sms_data['tplid'] = 326488;
                    $sms_data['params'] = [];
                    //Sms::sendSms2($sms_data);
                    $sms_txt = "Tutti has added a new coupon to your account! Please log in to your account and check available coupons for details.";
                    //Sms::telesign_send_sms($userInfo['phone'],$sms_txt,1);
                    Sms::sendTwilioSms($userInfo['phone'],$sms_txt);
                }
            }

            echo json_encode(array('error_code'=> 0,'msg'=>''));
        }
    }

    public function send_user_code(){
        $code = $_POST['code'];
        $user_code = D('User_rechange_code')->where(array('code'=>$code))->find();
        if($user_code){
            exit(json_encode(array('status'=>1,'result'=>$user_code,'msg'=>'Code Success!')));
        }else{
            exit(json_encode(array('status'=>0,'result'=>'','msg'=>'Code Error!')));
        }
    }

//    public function recharge_refund(){
//        /**  tpl/System/User/recharge_list add javascript
//         * function recharge_refund(order_id,uid) {
//            $.post("{pigcms{:U('User/recharge_refund')}",{'order_id':order_id,'uid':uid},function(data){
//            if (data.status == 1) {
//            alert(data.info);
//            //window.location.reload();
//            }else{
//            alert('Fail');
//            }
//            },'JSON');
//            }
//         */
//        $order_id = $_POST['order_id'];
//        $uid = $_POST['uid'];
//
//        import('@.ORG.pay.MonerisPay');
//        $moneris_pay = new MonerisPay();
//
//        $resp = $moneris_pay->refund($uid, $order_id,-1,1,1);
//
//        if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
//            $data_shop_order['order_id'] = $order_id;
//            //$data_shop_order['status'] = 4;
//            $data_shop_order['last_time'] = time();
//            D('User_recharge_order')->data($data_shop_order)->save();
//
//            $this->success(L('_PAYMENT_SUCCESS_'),'',true);
//        }
//
//        $this->error('Fail','',true);
//    }
}
