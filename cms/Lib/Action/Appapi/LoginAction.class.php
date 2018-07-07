<?php
/**
 * Login Controller
 */
class LoginAction extends BaseAction {
    //登录
    public function login() {
        $ticket = I('ticket', false);
        $client = I('client', 0);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $uid = $info['uid'];
                $user = D('User')->field(true)->where(array('uid'=>$uid))->find();
                unset($user['pwd']);
                $doorList	=	$this->door_list($uid,1);
                $return = array(
                    'ticket'    =>	$ticket,
                    'user'      =>	$user,
                    'door'		=>	isset($doorList)?$doorList:array(),
                );
                $this->addLog($user,$client);
                $this->upDevice($client,$uid);
                $this->returnCode(null, $return);
            }
        }
        $phone = I('phone', false);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $passwd = I('passwd', false);
        if (! $passwd) {
            $this->returnCode('20042002');
        }
        $result = D("User")->checkin($phone, $passwd);
        if ($result['error_code']) {
            $this->returnCode('20044006');
        }
        unset($result['user']['pwd']);
        $doorList	=	$this->door_list($result['user']['uid'],1);
        $ticket = ticket::create($result['user']['uid'], $this->DEVICE_ID, true);
        $return = array(
            'ticket'=>	$ticket['ticket'],
            'user'	=>	$result['user'],
            'door'	=>	isset($doorList)?$doorList:array(),
        );
        $this->addLog($result['user'],$client);
        $this->upDevice($client,$result['user']['uid']);
        $this->returnCode(null, $return);
    }
    //注册
    public function register() {
        $code = I('code', 0);
        if (! $code) {
            $this->returnCode('20044008');
        }
        $phone = I("phone", false);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $passwd = I("passwd", false);
        if (! $passwd) {
            $this->returnCode('20042002');
        }
        $client = I('client', 0);
        //判断验证码
        //if ($code != '1234') {
//             $this->returnCode('20044009');
//        }
        $where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type']   = 1;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();
        if (! $result) {
           $this->returnCode('20044009');
        }
        //注册账号
        $result = D('User')->where(array('phone'=>$phone))->find();
        if ($result) {
            $this->returnCode('20044005');
        }


        $result = D('User')->checkreg($phone, $passwd);
        if ($result['error_code']) {
            $this->returnCode('20044003');
        }


        unset($result['user']['pwd']);

        $uid = $result['user']['uid'];
//        if ($this->config['register_give_money_condition'] == 2 || $this->config['register_give_money_condition'] == 3) {
//            D('User')->add_money($uid, $this->config['register_give_money'], '新用户注册平台赠送余额');
//            //D('User_score_list')->add_row($uid,1, $this->config['register_give_score'], '新用户注册平台赠送'.$this->config['score_name']);
//        }
        $ticket = ticket::create($uid, $this->DEVICE_ID, true);
        //统一数据结构
        $result['user']['coupon_count'] = 0;
        $return = array(
            'ticket' => $ticket["ticket"],
            'user' => $result['user'],
        );
        $this->addLog($result['user'],$client);
        $this->upDevice($client,$result['user']['uid']);
        $this->returnCode(null, $return);
    }
    //发送验证码
    public function sendCode() {
        $phone = I('phone', 0);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $type = I('type', 1);
        $code = mt_rand(1000, 9999);
        $text = '您的验证码是：' . $code . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
        if($type == 1){        //注册帐号
            //查看用户是否存在
            $where = array();
            $where['phone'] = $phone;
            $result = D("User")->field('`uid`')->where($where)->find();
            if($result){
                $this->returnCode('20044005');
            }
        }else if ($type == 2) {  //忘记密码
            //查看用户是否存在
            $where = array();
            $where['phone'] = $phone;
            $result = D("User")->field('`uid`')->where($where)->find();
            if(!$result){
                $this->returnCode('20044011');
            }
        }

        $columns = array();
        $columns['phone'] = $phone;
        $columns['text'] = $text;
        $columns['extra'] = $code;
        $columns['device_id'] = $this->DEVICE_ID;
        $columns['type'] = $type;
        $columns['status'] = 0;
        $columns['send_time'] = time();
        $columns['expire_time'] = $columns['send_time'] + 72000;
        $result = D("App_sms_record")->add($columns);
        if (! $result) {
            $this->returnCode('20044007');
        }

        $return = Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $text, 'mobile' => $phone, 'uid' => 0, 'type' => 'waimai_register'));
        if ($result != 0) {
            $this->returnCode(self::ConverSmsCode($return));
        }
        $this->returnCode(null, $return);
    }
    //  发送更改密码验证码
    public function verifyCode() {
        $phone = I('phone', 0);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $code = I('code', 0);
        if (! $code) {
            $this->returnCode('20044008');
        }

        $type = I('type', 2);
        $where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type'] = $type;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();
        if (! $result) {
            $this->returnCode('20044009');
        }
        $result = D("App_sms_record")->where($where)->data(array('status'=>1))->save();
        $this->returnCode();
    }
    //  忘记密码-更改密码
    public function forget() {
        $code = I('code', 0);
        if (! $code) {
            $this->returnCode('20044008');
        }
        $phone = I("phone", false);
        if (! $phone) {
            $this->returnCode('20042001');
        }
        $passwd = I("passwd", false);
        if (! $passwd) {
            $this->returnCode('20042002');
        }

        //查看用户是否存在
        $where = array();
        $where['phone'] = $phone;
        $result = D("User")->field('`uid`')->where($where)->find();
        if (! $result) {
            $this->returnCode('20044011');
        }

        $result = D('User')->save_user($result['uid'],'pwd',md5($passwd));
        if ($result['error_code']) {
            $this->returnCode('20044012');
        }

        $this->returnCode();
    }
    //微信登录
    public function weixin_login() {
        $client = I('client', 0);
        $weixin_open_id = I('weixin_open_id', 0, "trim");
        if (! $weixin_open_id) {
            $this->returnCode('20045001');
        }
        $weixin_union_id = I('weixin_union_id', 0, "trim");
        if (! $weixin_union_id) {
            $this->returnCode('20045002');
        }
        $nickname = I('nickname', "weixin_user", "trim");
        $avatar = I('avatar');
        $sex = I('sex', 1);
        //如果已经绑定账号，直接登录，返回ticket
        $user = D("User")->get_user($weixin_open_id, "app_openid");
        if($user&&$user['status'] !=1 ){
            $this->returnCode('20120008');
        }

        if ($user) {
            $columns = array();
            $columns['last_time'] = $_SERVER['REQUEST_TIME'];
            $columns['last_ip'] = get_client_ip(1);
            D('User')->where(array('uid' => $user['uid']))->save($columns);
            unset($user['pwd']);
            $ticket = ticket::create($user['uid'], $this->DEVICE_ID, true);
            $return = array(
                'ticket' => $ticket['ticket'],
                'user'=>$user
            );
            $this->addLog($user,$client);
            $this->upDevice($client,$user['uid']);
            $this->returnCode(null, $return);
        }
        //如果已经绑定账号，直接登录，返回ticket
        $user = D("User")->get_user($weixin_union_id, "union_id");
        if ($user) {
            $columns = array();
            $columns['app_openid'] = $weixin_open_id;
            $columns['last_time'] = $_SERVER['REQUEST_TIME'];
            $columns['last_ip'] = get_client_ip(1);
            D('User')->where(array('uid' => $user['uid']))->save($columns);
            unset($user['pwd']);
            $ticket = ticket::create($user['uid'], $this->DEVICE_ID, true);
            $return = array(
                'ticket' => $ticket['ticket'],
                'user'=>$user
            );
            $this->addLog($user,$client);
            $this->upDevice($client,$user['uid']);
            $this->returnCode(null, $return);
        }
        //未绑定，注册新账号，并登录，返回ticket
        $columns = array();
        $columns['app_openid'] = $weixin_open_id;
        $columns['union_id'] = $weixin_union_id;
        $columns['sex'] = $sex;
        $columns['avatar'] = $avatar;
        $columns['add_time'] = $_SERVER['REQUEST_TIME'];
        $columns['last_time'] = $_SERVER['REQUEST_TIME'];
        $columns['add_ip'] = get_client_ip(1);
        $columns['last_ip'] = get_client_ip(1);
		if($nickname == $this->config['site_name']){
       		$columns['nickname'] = '昵称';
		}else{
        	$columns['nickname'] = $nickname;
		}
        $userId = D("User")->data($columns)->add();
        if (! $userId) {
            $this->returnCode('20045004');
        }

       	if ($this->config['register_give_money_condition'] == 2 || $this->config['register_give_money_condition'] == 3) {
			if($this->config['register_give_money_type']==1 ||$this->config['register_give_money_type']==2 ){
				D('User')->add_money($userId, $this->config['register_give_money'], '新用户注册平台赠送余额');
				D('Scroll_msg')->add_msg('reg',$userId,'用户'.$nickname.'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'APP端注册成功获得赠送余额');
			}
			
			if($this->config['register_give_money_type']==0 ||$this->config['register_give_money_type']==2 ){
				D('User')->add_score($userId,1, $this->config['register_give_score'], '新用户注册平台赠送'.$this->config['score_name']);
				D('Scroll_msg')->add_msg('reg',$userId,'用户'.$nickname.'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'APP端注册成功获得赠送'.$this->config['score_name']);
			}
		}

        $user = D("User")->get_user($userId, "uid");
        $this->addLog($user,$client);
        $this->upDevice($client,$user['uid']);
        unset($user['pwd']);
        $ticket = ticket::create($user['uid'], $this->DEVICE_ID, true);
        $return = array(
            'ticket' => $ticket['ticket'],
            'user'=>$user
        );

        $this->returnCode(null, $return);
    }
    // 手机号绑定
    public function bind_user(){
    	if($_POST['type'] != 3){
			$code 		= I('code', 0);
	        $phone      =   I('phone');
	        $passwd     =   I('passwd');
	        $ticket     =   I('ticket', false);
	        $client     =   I('client');
	        if (!$code) {
	            $this->returnCode('20044008');
	        }
	        $where = array();
	        $where['phone'] = $phone;
	        $where['extra'] = $code;
	        $where['type']   = 1;
	        $where['status'] = 0;
	        $where['expire_time'] = array('gt', time());
	        $result = D("App_sms_record")->where($where)->find();
	        if (! $result) {
	           $this->returnCode('20044009');
	        }
	        if ($ticket) {
	            $info = ticket::get($ticket, $this->DEVICE_ID, true);
	            if ($info) {
	                $uid = $info['uid'];
	            }
	            if(empty($phone)){
	                $this->returnCode('20042001');
	            }
	            if(empty($passwd)){
	                $this->returnCode('20042002');
	            }
	            $database_user = D('User');
	            $condition_user['phone'] = $phone;
	            if($database_user->field('`uid`')->where($condition_user)->find()){
	                $this->returnCode('20044014');
	            }
	            $condition_save_user['uid'] = $uid;
	            $data_save_user['phone'] = $phone;
	            $data_save_user['pwd'] = md5($passwd);
	            if($database_user->where($condition_save_user)->data($data_save_user)->save()){
	                $user = array(
	                    'uid'    =>  $uid,
	                    'phone'    =>  $phone,
	                );
	                $this->addLog($user,$client);
	                $_SESSION['user']['phone'] = $phone;
	                $arr    =   $database_user->where($condition_save_user)->find();
	                $this->returnCode(0,$arr);
	            }else{
	                $this->returnCode('20045006');
	            }
	        }else{
	            $this->returnCode('20044013');
	        }
    	}else{
			$this->weixin_bind_user();
    	}

    }
    //错误代码
    static private function ConverSmsCode($smscode) {
        $errCode = array(
            '2'    => '20060001',
            '400'  => '20060002',
            '401'  => '20060003',
            '402'  => '20060004',
            '403'  => '20060005',
            '4030' => '20060006',
            '404'  => '20060007',
            '405'  => '20060008',
            '4050' => '20060009',
            '4051' => '20060010',
            '4052' => '20060011',
            '406'  => '20060012',
            '407'  => '20060013',
            '4070' => '20060014',
            '4071' => '20060015',
            '4072' => '20060016',
            '4073' => '20060017',
            '408'  => '20060018',
            '4085' => '20060019',
            '4084' => '20060020',
        );
        return $errCode[$smscode];
    }
    //  记录手机登录，Appapi_app_login_log
    public function addLog($user = array(),$client){
        $log = array(
            'client' => $client,
            'device_id' => $this->DEVICE_ID,
            'uid' => $user['uid'],
            'phone' => $user['phone'],
            'create_time' => time(),
        );
        M("Appapi_app_login_log")->add($log);
    }
    //  更新user表里的Device-Id
    public function upDevice($client,$uid){
        $userUpdata =   array(
            'device_id' =>  $this->DEVICE_ID,
            'client'    =>  $client
        );
        M('User')->where(array('uid' => $uid))->save($userUpdata);
    }
    //	获取用户的门禁列表
    public function door_list($uid='',$type=2){
    	if($type == 1){
			if(empty($uid)){
				return	array();
			}
    	}else{
    		$ticket = I('ticket', false);
	        if ($ticket) {
	            $info = ticket::get($ticket, $this->DEVICE_ID, true);
	        	if ($info) {
	            	$uid = $info['uid'];
				}
			}else{
				$this->returnCode('20044010');
			}
			if(empty($uid)){
				$this->returnCode('20090009');
			}
    	}
		$where['uid']	=	$uid;
		$aUserSelect	=	M('House_village_user_bind')->distinct(true)->field(array('village_id','floor_id'))->where($where)->select();
		if($type	==	1){
			if(empty($aUserSelect)){
				return	array();
			}
		}else{
			if(empty($aUserSelect)){
				$this->returnCode('20090009');
			}
		}
		foreach($aUserSelect as $k=>$v){
			$condition_door['door_status']	=	1;
			$condition_door['village_id'] = $v['village_id'];
			$condition_door['floor_id'] = array(array('eq',-1),array('eq',$v['floor_id']),'or');
			$aDoorList	=	M('House_village_door')->distinct(true)->field(true)->where($condition_door)->select();
			foreach($aDoorList as $kk=>$vv){
				$userWhere	=	array(
					'user_id'	=>	$uid,
					'door_fid'	=>	$vv['door_id'],
				);
				$aDoorFind	=	M('House_village_door_user')->field(true)->where($userWhere)->find();
				if(empty($aDoorFind)){
					$aDoorList[$kk]['open_status']	=	1;
				}else if($aDoorFind['status'] == 1){
					if($aDoorFind == 0 || time()>$aDoorFind['end_time']){
						$aDoorList[$kk]['open_status']	=	1;	//允许使用
					}else{
						$aDoorList[$kk]['open_status']	=	2;	//时间过期
					}
				}else{
					$aDoorList[$kk]['open_status']	=	0;		//禁止使用
				}
			}

			$aSelect[]	=	$aDoorList;
		}
		if($aSelect){
			foreach($aSelect as $k=>$v){
				if(empty($v)){
					continue;
				}
				foreach($v as $kk=> $vv){
					if($vv['floor_id'] != "-1"){
						$aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->where(array('floor_id'=>$vv['floor_id']))->find();
						$vv['floor_name']	=	strval($aFloor['floor_name']);
						$vv['floor_layer']	=	strval($aFloor['floor_layer']);
					}else{
						$vv['floor_name']	=	'小区';
						$vv['floor_layer']	=	'大门';
					}
					$aDoor[]	=	isset($vv)?$vv:array();
				}
			}
		}
		if($type == 1){
			return	$aDoor;
		}else{
			if(empty($aDoor)){
				$aDoor	=	array();
			}
			$this->returnCode(0,$aDoor);
		}
    }

    public function modify_phone(){
        $ticket     =   I('ticket', false);
        $code		=	I('code',false);
        if(empty($ticket)){
            $this->returnCode('20044013');
        }else{
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info['uid']) {
                $uid = $info['uid'];
            }else{
                $this->returnCode('20000009');
            }
        }

        $phone		=	$_POST['phone'];
        if(empty($phone)){
            $this->returnCode('20042001');
        }
        $database_user = D('User');
        $now_user = $database_user->where(array('uid'=>$uid))->find();
        if(!empty($now_user['phone'])){
            $condition_user['phone'] = $phone;
            if($database_user->field(true)->where($condition_user)->find()){
                $this->returnCode('10044005');
            }
        }

        $where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type']   = 3;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();

        if (!$result) {
            $this->returnCode('20044009');
        }

        $condition_save_user['uid'] = $uid;
        $data_save_user['phone'] = $phone;

        if($database_user->where($condition_save_user)->data($data_save_user)->save()){
            $_SESSION['user']['phone'] = $phone;
            $this->returnCode(0);
        }else{
            $this->returnCode('20120006');
        }
    }
    //	微信绑定手机
    public function weixin_bind_user(){
    	$ticket     =   I('ticket', false);
    	$code		=	I('code',false);
		if(empty($ticket)){
			$this->returnCode('20044013');
		}else{
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			if ($info['uid']) {
                $uid = $info['uid'];
            }else{
				$this->returnCode('20000009');
            }
		}

		$phone		=	$_POST['phone'];
		$password	=	$_POST['passwd'];
		if(empty($phone)){
			$this->returnCode('20042001');
		}
        $database_user = D('User');
        $now_user = $database_user->where(array('uid'=>$uid))->find();
        if(!empty($now_user['phone'])){
            $condition_user['phone'] = $phone;
            if($database_user->field(true)->where($condition_user)->find()){
                $this->returnCode('10044005');
            }
        }

		$where = array();
        $where['phone'] = $phone;
        $where['extra'] = $code;
        $where['type']   = 3;
        $where['status'] = 0;
        $where['expire_time'] = array('gt', time());
        $result = D("App_sms_record")->where($where)->find();

        if (!$result) {
           $this->returnCode('20044009');
        }
        $openidPost	=	$_POST['openid'];
        $unionidPost	=	$_POST['union_id'];
        if(!$openidPost){
			$this->returnCode('20120010');
        }
		//$database_user = D('User');
		$condition_user['phone'] = $phone;
		if(($res = $database_user->field('`uid`,`pwd`')->where($condition_user)->find())&&empty($now_user['phone'])){
			$openid = $database_user->field('`app_openid`')->where('uid='.$res['uid'])->find();
			if(!empty($openid['app_openid'])){
				$this->returnCode('20120005');
			}
			$login_result = D('User')->checkin($phone,$password,'app');
			if($login_result['error_code']){
				$this->returnCode($login_result['msg']);
			}else{
				if($database_user->where('`uid`='.$res['uid'])->setField('app_openid',$openidPost)){
					$database_user->where('`uid`='.$info['uid'])->setField('union_id',$unionidPost);
					$database_user->where('`uid`='.$info['uid'])->setField('app_openid',$openidPost.'~no_use');
					session_destroy();
					unset($_SESSION);
					$this->returnCode(0);
				}else{
					$this->returnCode('20120006');
				}
			}
		}
		$condition_save_user['uid'] = $uid;
		$data_save_user['phone'] = $phone;
        if(!empty($password)){
            $data_save_user['pwd'] = md5($password);
        }
		if($database_user->where($condition_save_user)->data($data_save_user)->save()){
			$_SESSION['user']['phone'] = $phone;
			$this->returnCode(0);
		}else{
			$this->returnCode('20120006');
		}
	}
}