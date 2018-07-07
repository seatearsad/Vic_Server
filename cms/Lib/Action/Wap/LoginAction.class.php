<?php
class LoginAction extends BaseAction{
	public function index(){
		if(IS_POST){
			$login_result = D('User')->checkin($_POST['phone'],$_POST['password']);
			if($login_result['error_code']){
				$this->error($login_result['msg']);
			}else{
				$now_user = $login_result['user'];
				session('user',$now_user);
                session('openid', $now_user['openid']);
				setcookie('login_name',$now_user['phone'],$_SERVER['REQUEST_TIME']+10000000,'/');
				$this->success('登录成功！');
			}
		}else{
			if(!empty($this->user_session)){
				if(cookie('is_house')){
					redirect(C('INDEP_HOUSE_URL'));
				}else{
					redirect(U('My/index'));
				}
			}

			if($_GET['referer']){
				$referer = htmlspecialchars_decode($_GET['referer']);
			}else{
				$referer = $_SERVER['HTTP_REFERER'];
			}
			$this->assign('referer',$referer);

			if($this->is_wexin_browser){
				redirect(U('Login/weixin',array('referer'=>urlencode($referer))));exit;
			}
			$this->display();
		}
	}
	public function reg(){
		if(IS_POST){
			$condition_user['phone'] = $data_user['phone'] = trim($_POST['phone']);

			$database_user = D('User');
			if($database_user->field('`uid`')->where($condition_user)->find()){
				$this->error('手机号已存在');
			}

			if(empty($data_user['phone'])){
				$this->error('请输入手机号');
			}else if(empty($_POST['password'])){
				$this->error('请输入密码');
			}

			if(is_numeric($data_user['phone']) == false){
				$this->error('请输入有效的手机号');
			}
			if ($this->config['reg_verify_sms']&&$this->config['sms_key']&&substr($_POST['phone'],0,10)!='1321234567') {
				$sms_verify_result = D('Smscodeverify')->verify($_POST['sms_code'], $_POST['phone']);

				if ($sms_verify_result['error_code']) {
					$this->error($sms_verify_result['msg']);
				}else{
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}
			$data_user['pwd'] = md5($_POST['password']);

			$data_user['nickname'] = substr($data_user['phone'],0,3).'****'.substr($data_user['phone'],7);

			$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);

			/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$condition_user['phone'],'isuse'=>'0'))->find();
			if(!empty($user_import)){
			   $data_user['truename']=$user_import['ppname'];
			   $data_user['qq']=$user_import['qq'];
			   $data_user['email']=$user_import['email'];
			   $data_user['level']=$user_import['level'];
			   $data_user['score_count']=$user_import['integral'];
			   $data_user['now_money']=$user_import['money'] ? $user_import['money'] : 0;
			   $data_user['importid']=$user_import['id'];
			   $data_user['youaddress'] = !empty($user_import['address']) ? str_replace('|', ' ', $user_import['address']) : '';
				if($this->config['reg_verify_sms']){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   // $data_user['now_money'] = 0;
			}
			$data_user['source'] = 'wap';
			$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];

			if($uid = $database_user->data($data_user)->add()){
				if ($this->config['register_give_money_condition'] == 2 || $this->config['register_give_money_condition'] == 3) {
					if($this->config['register_give_money_type']==1 ||$this->config['register_give_money_type']==2 ){
						D('User')->add_money($uid, $this->config['register_give_money'], '新用户注册平台赠送余额');
						D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送余额'.$this->config['register_give_money'].'元');

					}
					if($this->config['register_give_money_type']==0 ||$this->config['register_give_money_type']==2 ){
						D('User')->add_score($uid,$this->config['register_give_score'], '新用户注册平台赠送'.$this->config['score_name']);
						D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送'.$this->config['score_name'].$this->config['register_give_score'].'个');
					}
					
				}
				$session['uid'] = $uid;
				$session['phone'] = $data_user['phone'];
				session('user',$session);

				setcookie('login_name',$session['phone'],$_SERVER['REQUEST_TIME']+1000000,'/');
				if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>2));
				}
				$this->success('注册成功');
			}else{
				$this->error('注册失败！请重试。');
			}
		}else{
			if(!empty($this->user_session)){
				redirect(U('My/index'));
			}
			$this->display();
		}
	}
    /*     * *****手机短信验证***** */

	//忘记密码
	public function forgetpwd() {
		$accphone = isset($_GET['accphone']) ? trim($_GET['accphone']) : '';
		$this->assign('accphone', $accphone);
		if($this->config['sms_key']){
			$this->display();
		}else{
			$this->error_tips('平台未开启短信验证功能，请联系系统管理员！');
		}
	}

	public function pwdModify() {
		$pm = trim($_GET['pm']);
		if (!empty($pm)) {
			$pm = str_replace(' ', '+', $pm);
			$tmpstr = Encryptioncode($pm, 'DECODE');
			$modfyinfo = json_decode(base64_decode($tmpstr), TRUE);
			if (!empty($modfyinfo)) {
				$phone = $modfyinfo['phone'];
				$tmp = session($phone . 'Generate_Pwd_Modify');
				if ($tmp) {
					$modifypwd = M('User_modifypwd')->where(array('id' => $modfyinfo['vfycode_id'], 'telphone' => $phone))->find();
					$nowtime = time();
					if ($modifypwd['expiry'] < $nowtime) {
						$this->error('链接时间已经过期失效了', U('Index/Login/index'));
						exit();
					}
					$this->assign('pm', $pm);
					$this->display();
					exit();
				}
			}
		}
		redirect(U('Wap/Login/index'));
	}

	public function pwdModifying() {
		$pm = trim($_GET['pm']);
		$newpwd = trim($_POST['newpwd']);
		$new_pwd = trim($_POST['new_pwd']);
		if ($newpwd != $new_pwd) {
			exit(json_encode(array('error_code' => 1, 'msg' => '两次密码输入不一样！')));
		}
		if (!empty($pm)) {
			$pm = str_replace(' ', '+', $pm);
			$tmpstr = Encryptioncode($pm, 'DECODE');
			$modfyinfo = json_decode(base64_decode($tmpstr), TRUE);
			if (!empty($modfyinfo)) {
				$phone = $modfyinfo['phone'];
				$tmp = session($phone . 'Generate_Pwd_Modify');
				if ($tmp) {
					if (M('User')->where(array('uid' => $modfyinfo['uid'], 'phone' => $phone))->save(array('pwd' => md5($newpwd)))) {
						session($phone . 'Generate_Pwd_Modify', null);
						exit(json_encode(array('error_code' => 0, 'msg' => '密码修改成功！')));
					} else {
						exit(json_encode(array('error_code' => 2, 'msg' => '密码修改失败！')));
					}
				}
			}
		}
		//exit(json_encode(array('error_code' => 2, 'msg' => '参数出错！')));
	}

    public function SmsCodeverify($tphone) {
        $user_modifypwdDb = M('User_modifypwd');
        if (isset($_POST['vcode']) && !empty($_POST['vcode'])) {
            $vfycode = trim($_POST['vcode']);
            $modifypwd = $user_modifypwdDb->where(array('vfcode' => $vfycode, 'telphone' => $tphone))->find();
            if (!empty($modifypwd)) {
                $nowtime = time();
                if ($modifypwd['expiry'] > $nowtime) {
                    return true;
                }
            }
            $this->error('验证码失效了，务必在20分钟内完成验证');
            exit();
        } else {
            $vcode = createRandomStr(6, true, true);
            $content = '您的验证码是：'. $vcode . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
            Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $tphone, 'uid' => 0, 'type' => 'regvfy'));
            $addtime = time();
            $expiry = $addtime + 20 * 60; /*             * **二十分钟有效期*** */
            $data = array('telphone' => $tphone, 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
            $insert_id = $user_modifypwdDb->add($data);
            $this->error('vfcode');
            exit();
        }
    }
	public function logout(){
		session('user',null);
		session('openid',null);
		redirect(U('Home/index'));
	}
	public function weixin(){
		$_SESSION['weixin']['referer'] = !empty($_GET['referer']) ? htmlspecialchars_decode($_GET['referer']) : U('Home/index');
		$_SESSION['weixin']['state']   = md5(uniqid());

		$customeUrl = $this->config['site_url'].'/wap.php?c=Login&a=weixin_back';
		$oauthUrl='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->config['wechat_appid'].'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_userinfo&state='.$_SESSION['weixin']['state'].'#wechat_redirect';
		redirect($oauthUrl);
	}
	public function weixin_back(){

		$referer = !empty($_SESSION['weixin']['referer']) ? $_SESSION['weixin']['referer'] : U('Home/index');

		// if (isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['weixin']['state'])){
		if (isset($_GET['code'])){
			unset($_SESSION['weixin']['state']);
			import('ORG.Net.Http');
			$http = new Http();
			$return = $http->curlGet('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->config['wechat_appid'].'&secret='.$this->config['wechat_appsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code');
			$jsonrt = json_decode($return,true);
			if($jsonrt['errcode']){
				$error_msg_class = new GetErrorMsg();
				$this->error_tips('授权发生错误：'.$error_msg_class->wx_error_msg($jsonrt['errcode']),U('Login/index'));
			}

			$return = $http->curlGet('https://api.weixin.qq.com/sns/userinfo?access_token='.$jsonrt['access_token'].'&openid='.$jsonrt['openid'].'&lang=zh_CN');

			$jsonrt = json_decode($return,true);
			if ($jsonrt['errcode']) {
				$error_msg_class = new GetErrorMsg();
				$this->error_tips('授权发生错误：'.$error_msg_class->wx_error_msg($jsonrt['errcode']),U('Login/index'));
			}
			$is_follow = 0;
			$access_token_array = D('Access_token_expires')->get_access_token();
			if (!$access_token_array['errcode']) {
				$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$jsonrt['openid'].'&lang=zh_CN');
				$userifo = json_decode($return,true);
				$is_follow = $userifo['subscribe'];
			}

			if(!empty($this->user_session)){
				$data_user = array(
					'union_id' 	=> ($jsonrt['unionid'] ? $jsonrt['unionid'] : ''),
					'sex' 		=> $jsonrt['sex'],
					'nickname' 	=> $jsonrt['nickname'],
					'province' 	=> $jsonrt['province'],
					'city' 		=> $jsonrt['city'],
					'avatar' 	=> $jsonrt['headimgurl'],
					'last_weixin_time' 	=> $_SERVER['REQUEST_TIME'],
// 					'is_follow' 	=> $is_follow,
				);
				D('User')->where(array('uid'=>$this->user_session['uid']))->data($data_user)->save();
				redirect($referer);
			}else{
				/*优先使用 unionid 登录*/
				if(!empty($jsonrt['unionid'])){
					$this->autologin('union_id',$jsonrt['unionid'],$referer);
				}else{
					/*再次使用 openid 登录*/
					$this->autologin('openid',$jsonrt['openid'],$referer);
				}

				/*注册用户*/
				$data_user = array(
					'openid' 	=> $jsonrt['openid'],
					'union_id' 	=> ($jsonrt['unionid'] ? $jsonrt['unionid'] : ''),
					'nickname' 	=> $jsonrt['nickname'],
					'sex' 		=> $jsonrt['sex'],
					'province' 	=> $jsonrt['province'],
					'city' 		=> $jsonrt['city'],
					'avatar' 	=> $jsonrt['headimgurl'],
					'is_follow' 	=> $is_follow,
				);
				$_SESSION['weixin']['user'] = $data_user;
				$this->assign('referer',$referer);
				if($this->config['weixin_login_bind']){
					$this->display();
				}else{
					redirect(U('Login/weixin_nobind'));
				}
			}
		}else{
			$this->error_tips('访问异常！请重新登录。',U('Login/index',array('referer'=>urlencode($referer))));
		}
	}
	public function weixin_bind(){
		if(empty($_SESSION['weixin']['user'])){
			$this->error('微信授权失效，请重新登录！');
		}
		$login_result = D('User')->checkin($_POST['phone'],$_POST['password']);
		if($login_result['error_code']){
			$this->error($login_result['msg']);
		}else{
			$now_user = $login_result['user'];
			$condition_user['uid'] = $now_user['uid'];
			$data_user['openid'] = $_SESSION['weixin']['user']['openid'];
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];
			if($_SESSION['weixin']['user']['union_id']){
				$data_user['union_id'] 	= $_SESSION['weixin']['user']['union_id'];
			}
			if(empty($now_user['avatar'])){
				$data_user['avatar'] 	= $_SESSION['weixin']['user']['avatar'];
			}
			if(empty($now_user['sex'])){
				$data_user['sex']		= $_SESSION['weixin']['user']['sex'];
			}
			if(empty($now_user['province'])){
				$data_user['province'] 	= $_SESSION['weixin']['user']['province'];
			}
			if(empty($now_user['city'])){
				$data_user['city'] 		= $_SESSION['weixin']['user']['city'];
			}
			/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$condition_user['phone']))->find();
			if(!empty($user_import)){
			 if($user_import['isuse']==0){
			   $data_user['truename']=$user_import['ppname'];
			   $data_user['qq']=$user_import['qq'];
			   $data_user['email']=$user_import['email'];
			   $data_user['level']=$user_import['level'];
			   $data_user['score_count']=$user_import['integral'];
			   $data_user['now_money']=$user_import['money'];
			   $data_user['importid']=$user_import['id'];
 			   $data_user['youaddress'] = !empty($user_import['address']) ? str_replace('|', ' ', $user_import['address']) : '';
			 }
				if($this->config['reg_verify_sms']){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   $mer_id=$user_import['mer_id'];
			   if($mer_id>0){
			      $merchant_user_relationDb=M('Merchant_user_relation');
				  $mwhere=array('openid'=>$data_user['openid'],'mer_id'=>$mer_id);
				  $mtmp=$merchant_user_relationDb->where($mwhere)->find();
				  if(empty($mtmp)){
					 $mwhere['dateline']=time();
					 $mwhere['from_merchant']=3;
				     $merchant_user_relationDb->add($mwhere);
				  }
			   }
			}
			if(D('User')->where($condition_user)->data($data_user)->save()){
				unset($_SESSION['weixin']);
				session('user',$now_user);
				setcookie('login_name',$now_user['phone'],$_SERVER['REQUEST_TIME']+10000000,'/');
				if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>1));
				}
				$this->success('登录成功！');
			}else{
				$this->error('绑定失败！请重试。');
			}
		}
	}
	public function weixin_bind_reg(){
		if(IS_POST){
			if(empty($_SESSION['weixin']['user'])){
				$this->error('微信授权失效，请重新登录！');
			}

			$database_user = D('User');
			$condition_user['phone'] = $data_user['phone'] = trim($_POST['phone']);

			if(empty($data_user['phone'])){
				$this->error('请输入手机号');
			}else if(!preg_match('/^[0-9]{11}$/',$data_user['phone'])){
				$this->error('请输入有效的手机号');
			}else if(empty($_POST['password'])){
				$this->error('请输入密码');
			}

			if($database_user->field('`uid`')->where($condition_user)->find()){
				$this->error('手机号已存在');
			}


			$where['openid'] = trim($_POST['openid']);
			if($database_user->field('`uid`')->where($where)->find()){
				$this->error('-1');
			}

			//技术测试号码
			if ($this->config['reg_verify_sms']&&$this->config['sms_key']&&substr($data_user['phone'],0,10)!='1321234567') {
				$sms_verify_result = D('Smscodeverify')->verify($_POST['sms_code'], $_POST['phone']);
				if ($sms_verify_result['error_code']) {
					$this->error($sms_verify_result['msg']);
				}else{
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}

			$data_user['pwd'] = md5($_POST['password']);

			$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);

//			if($nickname == $this->config['site_name']){
//       			$data_user['nickname']  = '昵称';
//			}else{
//        		$data_user['nickname'] = $_SESSION['weixin']['user']['nickname'];
//			}
			$data_user['nickname'] = $_SESSION['weixin']['user']['nickname'];
			$data_user['openid'] = $_SESSION['weixin']['user']['openid'];
			if($_SESSION['weixin']['user']['union_id']){
				$data_user['union_id'] 	= $_SESSION['weixin']['user']['union_id'];
			}
			$data_user['avatar'] 	= $_SESSION['weixin']['user']['avatar'];
			$data_user['sex']		= $_SESSION['weixin']['user']['sex'];
			$data_user['province'] 	= $_SESSION['weixin']['user']['province'];
			$data_user['city'] 		= $_SESSION['weixin']['user']['city'];
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];

			/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$condition_user['phone'],'isuse'=>'0'))->find();
			if(!empty($user_import)){
			   $data_user['truename']=$user_import['ppname'];
			   $data_user['qq']=$user_import['qq'];
			   $data_user['email']=$user_import['email'];
			   $data_user['level']=$user_import['level'];
			   $data_user['score_count']=$user_import['integral'];
			   $data_user['now_money']=$user_import['money'] ? $user_import['money'] : 0;;
			   $data_user['importid']=$user_import['id'];
			   $data_user['youaddress'] = !empty($user_import['address']) ? str_replace('|', ' ', $user_import['address']) : '';
				if($this->config['reg_verify_sms']){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   $mer_id=$user_import['mer_id'];
			   if($mer_id>0){
			      $merchant_user_relationDb=M('Merchant_user_relation');
				  $mwhere=array('openid'=>$data_user['openid'],'mer_id'=>$mer_id);
				  $mtmp=$merchant_user_relationDb->where($mwhere)->find();
				  if(empty($mtmp)){
					 $mwhere['dateline']=time();
					 $mwhere['from_merchant']=3;
				     $merchant_user_relationDb->add($mwhere);
				  }
			   }
			}
			$data_user['source'] = 'weixin_bind_reg';
			$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];
			if($uid = $database_user->data($data_user)->add()){
				if ($this->config['register_give_money_condition'] == 2 || $this->config['register_give_money_condition'] == 3) {
					if($this->config['register_give_money_type']==1 ||$this->config['register_give_money_type']==2 ){
						D('User')->add_money($uid, $this->config['register_give_money'], '新用户注册平台赠送余额');
						D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送余额'.$this->config['register_give_money'].'元');

					}
					if($this->config['register_give_money_type']==0 ||$this->config['register_give_money_type']==2 ){
						D('User')->add_score($uid,$this->config['register_give_score'], '新用户注册平台赠送'.$this->config['score_name']);
						D('Scroll_msg')->add_msg('reg',$uid,'用户'.$data_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'注册成功获得赠送'.$this->config['score_name'].$this->config['register_give_score'].'个');

					}
				}

				if($this->config['spread_user_give_type']!=3&&!empty($data_user['openid'])){
					$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$data_user['openid']))->find();
					if($now_user_spread) {
						$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
						$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();

						if ($this->config['spread_user_give_type'] == 0 || $this->config['spread_user_give_type'] == 2) {
							$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level:$this->config['spread_give_money'];
							if($this->config['open_score_fenrun']){
								D('User')->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
							}else{
								D('User')->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');
							}
							D('Scroll_msg')->add_msg('spread_reg',$this->now_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
						}

						if ($this->config['spread_user_give_type'] == 1 || $this->config['spread_user_give_type'] == 2) {
							$spread_give_score = $now_level['spread_user_give_score']>0?$now_level:$this->config['spread_give_score'];
							D('User')->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . $this->config['score_name']);
							D('Scroll_msg')->add_msg('spread_reg',$this->now_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.$this->config['score_name'].$spread_give_score.'个');
						}
					}

				}
				$session['uid'] = $uid;
				$session['phone'] = $data_user['phone'];
				session('user',$session);

				setcookie('login_name',$session['phone'],$_SERVER['REQUEST_TIME']+1000000,'/');
				if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>1));
				}
				$this->success('注册成功');
			}else{
				$this->error('注册失败！请重试。');
			}
		}
	}
	public function weixin_nobind(){
		if(empty($_SESSION['weixin']['user'])){
			$this->error('微信授权失效，请重新登录！');
		}
		$_SESSION['weixin']['user']['source'] = 'weixin_nobind_reg';
		$reg_result = D('User')->autoreg($_SESSION['weixin']['user']);
		if($reg_result['error_code']){
			$this->error_tips($reg_result['msg']);
		}else{
			$login_result = D('User')->autologin('openid',$_SESSION['weixin']['user']['openid']);
			if($login_result['error_code']){
				$this->error_tips($login_result['msg'],U('Login/index'));
			}else{
				$now_user = $login_result['user'];
				session('user',$now_user);
				$referer = !empty($_SESSION['weixin']['referer']) ? $_SESSION['weixin']['referer'] : U('Home/index');

				unset($_SESSION['weixin']);
				redirect($referer);
				exit;
			}
		}
	}
	protected function autologin($field,$value,$referer){
		$result = D('User')->autologin($field,$value);
		if(empty($result['error_code'])){
			if($field == 'union_id' && empty($result['user']['openid']) && !empty($_SESSION['openid'])){
				$condition_user['union_id'] = $value;
				D('User')->where($condition_user)->data(array('openid'=>$_SESSION['openid']))->save();
			}
			$now_user = $result['user'];
			session('user',$now_user);
			redirect($referer);
			exit;
		}else if($result['error_code'] && $result['error_code'] != 1001){
			$this->error_tips($result['msg'],U('Login/index'));
		}
	}

    public function frame_login() {
        $pigcms_assign['referer'] = !empty($_GET['referer']) ? strip_tags($_GET['referer']) : (!empty($_SERVER['HTTP_REFERER']) ? strip_tags($_SERVER['HTTP_REFERER']) : U('Index/Index/index'));
        $pigcms_assign['url_referer'] = urlencode($pigcms_assign['referer']);
        $this->assign($pigcms_assign);

        $this->display();
    }

    public function login()
    {
    	$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    	$pwd = isset($_POST['pwd']) ? $_POST['pwd'] : '';
    	$result = D('User')->checkin($phone, $pwd);
    	if (empty($result['error_code'])) {
    		session('user', $result['user']);
    		session('openid', $result['user']['openid']);
    	}
    	exit(json_encode($result));
    }

	public function see_login_qrcode(){
		$qrcode_return = D('Recognition')->get_login_qrcode();
		if($qrcode_return['error_code']){
			echo '<html><head></head><body>'.$qrcode_return['msg'].'<br/><br/><font color="red">请关闭此窗口再打开重试。</font></body></html>';
		}else{
			$this->assign($qrcode_return);
			$this->display();
		}
	}

    public function ajax_weixin_login() {
        for ($i = 0; $i < 6; $i++) {
            $database_login_qrcode = D('Login_qrcode');
            $condition_login_qrcode['id'] = $_GET['qrcode_id'];
            $now_qrcode = $database_login_qrcode->field('`uid`')->where($condition_login_qrcode)->find();
            if (!empty($now_qrcode['uid'])) {
                if ($now_qrcode['uid'] == -1) {
                    $data_login_qrcode['uid'] = 0;
                    $database_login_qrcode->where($condition_login_qrcode)->data($data_login_qrcode)->save();
                    exit('reg_user');
                }
                $database_login_qrcode->where($condition_login_qrcode)->delete();
                $result = D('User')->autologin('uid', $now_qrcode['uid']);
                if (empty($result['error_code'])) {
                    session('user', $result['user']);
                    exit('true');
                } else if ($result['error_code'] == 1001) {
                    exit('no_user');
                } else if ($result['error_code']) {
                    exit('false');
                }
            }
            if ($i == 5) {
                exit('false');
            }
            sleep(3);
        }
    }
}

?>