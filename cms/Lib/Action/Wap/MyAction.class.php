<?php
class MyAction extends BaseAction{
	public $now_user;
	public function __construct(){
		parent::__construct();
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				$this->error_tips(L('_HFDASJ_'),U('Login/index',$location_param));
			}else{
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				redirect(U('Login/index',$location_param));
			}
		}
		$now_user = D('User')->get_user($this->user_session['uid']);

		if(empty($now_user)){
			session('user',null);
			$this->error_tips(L('_B_MY_NOACCOUNT_'),U('Login/index'));
		}
		$now_user['now_money'] = floatval($now_user['now_money']);
		$now_user['now_money_two'] = number_format(floatval($now_user['now_money']),2);

		$this->now_user = $now_user;
		$this->assign('now_user',$now_user);

        //获取倒计时时间 web app 时间不同
        $config = D('Config')->get_config();
        $web_count_down = $config['pay_count_down_web'];

        $this->assign('count_down',$web_count_down*60);
	}

	//	新的个人中心页面
	public function index(){

		if($this->config['now_scenic'] == 2){
			redirect(U('Scenic_user/index'));
		}
		$uid	=	$this->user_session['uid'];

		//	商家优惠券
//		$mer_list = D('Member_card_coupon')->get_all_coupon($uid);

		$mer_list = D('Card_new_coupon')->get_user_all_coupon_list($uid,1);
		if($mer_list){
			$mer_number	=	count($mer_list);
		}else{
			$mer_number = 0;
		}
		$this->assign('mer_number',$mer_number);

		//	平台优惠券
		$coupon_list = D('System_coupon')->get_user_coupon_list($uid,$this->user_session['phone'],1);
        $event_coupon_list = D('New_event')->getUserCoupon($uid,0);
		$coupon_number	=	count($coupon_list) + count($event_coupon_list);

		$this->assign('coupon_number',$coupon_number);


		//	统计我参与的活动
		$sql	=	"SELECT COUNT(*) AS tp_count FROM `pigcms_extension_activity_record` `ear`,`pigcms_extension_activity_list` `eal`,`pigcms_merchant` `m` WHERE(`ear`.`activity_list_id` = `eal`.`pigcms_id` AND `eal`.`mer_id` = `m`.`mer_id`AND `ear`.`uid` = '$uid') GROUP BY `eal`.`pigcms_id` ";
		$mod = new Model();
		$result = $mod->query($sql);
		$activity_number	=	count($result);
		$this->assign('activity_number',$activity_number);
		//	统计我的会员卡
		//$card_number = D('Member_card_set')->get_all_card_count($uid);
		$sql = 'SELECT c.card_id,c.bg,c.diybg,m.name,c.discount,cl.id as cardid,cl.card_money,cl.card_money_give,m.pic_info,m.mer_id FROM '
				.C('DB_PREFIX').'card_userlist `cl`  left join '
				.C('DB_PREFIX').'card_new `c` on cl.card_id = c.card_id left join '
				.C('DB_PREFIX').'merchant m on c.mer_id  = m.mer_id WHERE ( cl.uid = '.$uid.' ) ';
		$res =  M('')->query($sql);
		foreach ($res as $v) {
			$tmp[$v['card_id']]['id'] = $v['cardid'];
		}
		$card_number = count($tmp);
		$this->assign('card_number', $card_number);
		$share = new WechatShare($this->config,$_SESSION['openid']);
		$this->BioAuthticMethod = $share->getBioAuthticMethod($this->static_path);
		$this->assign('BioAuthticMethod', $this->BioAuthticMethod);

		//底部导航
		$home_menu_list = D('Home_menu')->getMenuList('plat_footer');
		if($home_menu_list){
			$this->assign('home_menu_list',$home_menu_list);
		}

		$database_house_worker = D('House_worker');
		$house_worker_condition['status'] = 1;
		$house_worker_condition['openid'] = $this->user_session['openid'];
		if($now_house_worker = $database_house_worker->where($house_worker_condition)->find()){
			$this->assign('now_house_worker' , $now_house_worker);
		}
		if($this->config['show_scroll_msg']){
			$scroll_msg = D('Scroll_msg')->get_msg();
			$this->assign('scroll_msg',$scroll_msg);
		}

		//获取邀请活动是否存在
        $event_list = D('New_event')->getEventList(1,2);
		if($event_list){
		    $event = reset($event_list);
            $event['name'] = lang_substr($event['name'],C('DEFAULT_LANG'));
            $event['desc'] = lang_substr($event['desc'],C('DEFAULT_LANG'));
		    $this->assign('event',$event);
        }

		$this->display();

	}
	//	老的个人中心页面
	public function index__old(){
		if($this->config['im_appid'] && $_SESSION['openid'] && $this->config['user_center_redirect_friend']){
			redirect(U('Api/go_im',array('hash'=>'myList','title'=>urlencode(L('_B_MY_MEMBERCENTER_')))));exit;
		}
		$this->display();
	}
	//	个人信息页面
	public function myinfo(){
		$find	=	M('User_authentication')->field('authentication_id')->where(array('uid'=>$this->user_session['uid']))->order('authentication_time DESC')->find();
		$this->assign('find',$find);
		$find_car	=	M('User_authentication_car')->field('car_id')->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		$this->assign('find_car', $find_car);
		session('merchant_session', null);
		if ($merchant = M('Merchant')->where(array('uid' => $this->user_session['uid']))->find()) {
			session('merchant_session', serialize($merchant));
			$merchant_url = $this->config['site_url'] . '/index.php?g=WapMerchant&c=Index&a=index';
		} else {
			$merchant_url = $this->config['site_url'] . '/index.php?g=WapMerchant&c=Index&a=merreg&uid=' . $this->user_session['uid'];
		}
        $invitationcode = D('User')->getUserInvitationCode($this->user_session['uid']);
        $this->assign('back_url', U("My/index"));
        $this->assign('invitationcode', $invitationcode);
		$this->assign('merchant_url', $merchant_url);
		$this->display();
	}
	//	我的钱包页面
	public function my_money(){

	    if(isset($_GET['status'])){
            $this->assign('status',$_GET['status']);
        }else{
            $this->assign('status',"-1");
        }
		if($_GET['source'] == 1){
			$_SESSION['source']	=	1;
		}else{
			$_SESSION['source']	=	2;
		}
        if($_POST['money']){
            if(IS_POST){
                $data_user_recharge_order['uid'] = $this->now_user['uid'];
                $money = floatval($_POST['money']);
                if(empty($money) || $money > 10000){
                    $this->error('请输入有效的金额！最高不能超过1万元。');
                }
                if($_POST['label']){
                    $data_user_recharge_order['label'] = $_POST['label'];
                }
                $data_user_recharge_order['money'] = $money;
                // $data_user_recharge_order['order_name'] = '帐户余额在线充值';
                $data_user_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
                $data_user_recharge_order['is_mobile_pay'] = 1;

                if($order_id = D('User_recharge_order')->data($data_user_recharge_order)->add()){
                    if($_GET['type']=='gift'){
                        redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'gift')));
                    }elseif($_GET['type']=='classify') {
                        redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'classify')));
                    }else{
                        redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'recharge')));
                    }
                }
            }
        }else{
            $config = D('Config')->get_config();
            $recharge_txt = $config['recharge_discount'];
            $recharge = explode(",",$recharge_txt);
            $recharge_list = array();
            foreach ($recharge as $v){
                $v_a = explode("|",$v);
                $recharge_list[$v_a[0]] = $v_a[1];
            }
            //krsort($recharge_list);

            $this->assign('back_url',U("My/index"));
            $this->assign('recharge_list',$recharge_list);
            $this->display();
        }
	}
	//	完善资料页面
	public function inputinfo(){
		$this->display();
	}
	//	交易记录
	public function transaction(){
		$this->display();
	}

	public function money_list(){
		$this->display();
	}
	//	交易记录json
	public function transaction_json(){
		$page	=	$_POST['page'];
		$transaction	=	D('User_money_list')->get_list($this->now_user['uid'],$page,20);
		$transaction['count'] = count($transaction['money_list']);
		foreach($transaction['money_list'] as $k=>$v){
			$transaction['money_list'][$k]['time_s']	=	date('Y/m/d H:i',$v['time']);
            if(C('DEFAULT_LANG') != 'zh-cn' && $v['desc_en'] != ''){
                $transaction['money_list'][$k]['desc'] = $v['desc_en'];
            }
		}
		echo json_encode($transaction);
	}
	//	积分记录
	public function integral(){
		$this->display();
	}

	//新积分记录
	public function score_list(){
		$this->display();
	}
	//	积分记录json
	public function integral_json(){
		$page	=	$_POST['page'];
		$integral	=	D('User_score_list')->get_list($this->now_user['uid'],$page,20);
		$integral['count'] = count($integral['score_list']);
		foreach($integral['score_list'] as $k=>$v){
			$integral['score_list'][$k]['time_s']	=	date('Y/m/d H:i',$v['time']);
		}
		echo json_encode($integral);
	}
	//	关于我们
	public function about(){
		$this->display();
	}
	//	关于我们json
	public function about_json(){
		$activity_arr = array();
		$intro = D('Appintro');
		$count	=	$intro->count();
		if($count){
			$intro_info = $intro->select();
			foreach($intro_info as $v){
				$activity_arr[] = array(
						'title'=>$v['title'],
						'url'=>$this->config['site_url'] .'/wap.php?g=Wap&c=Appintro&a=intro&id='.$v['id']
				);
			}
		}else{
			$activity_arr  = array();
		}
		echo json_encode($activity_arr);
	}
	public function savemyinfo(){
		$_POST['truename']=trim($_POST['truename']);
		if(empty($_POST['truename'])) $this->dexit(array('error'=>1,'msg'=>L('_B_MY_MUSTNAME_')));
		if(empty($_POST['youaddress'])) $this->dexit(array('error'=>1,'msg'=>L('_B_MY_MUSTADDRESS_')));
		if(M('User')->where(array('uid'=>$this->now_user['uid']))->data($_POST)->save()){
			$this->dexit(array('error'=>0,'msg'=>L('_B_MY_SAVEACCESS_')));
		}
		$this->dexit(array('error'=>1,'msg'=>L('_B_MY_SAVELOSE_')));
	}
	public function username(){
		if($_POST['nickname']){
			if(empty($_POST['nickname'])){
				$this->assign('error',L('_B_MY_ENTERNEWNAME_'));
			}else if($_POST['nickname'] == $this->now_user['nickname']){
				$this->assign('error',L('_B_MY_NOCHANGENAME_'));
			}else if($_POST['nickname'] == $this->config['site_name']){
				$this->assign('error',L('_B_MY_NAMESAMEASUS_'));
			}else{
				$result = D('User')->save_user($this->now_user['uid'],'nickname',$_POST['nickname']);
				if($result['error']){
					$this->assign('error',$result['msg']);
				}else{
					redirect(U('My/myinfo',array('OkMsg'=>urlencode(L('_B_MY_NICKNAMECHANGE_')))));
				}
			}
		}
		$this->display();
	}
	public function email(){
        if($_POST['email']){
            $result = D('User')->save_user($this->now_user['uid'],'email',$_POST['email']);
            if($result['error']){
                $this->assign('error',$result['msg']);
            }else{
                redirect(U('My/myinfo',array('OkMsg'=>urlencode(L('_B_MY_SAVEACCESS_')))));
            }
        }
	    $this->display();
    }
    public function language(){
	    $this->assign('curr_lang',C('DEFAULT_LANG'));
        $this->assign('back_url', U("My/index"));
	    $this->display();
    }
	public function password(){
		if(IS_POST){
			if(!empty($this->now_user['pwd']) && md5($_POST['currentpassword']) != $this->now_user['pwd']){
				$this->assign('error',L('_B_MY_WRONGKEY_'));
			}else if($_POST['currentpassword'] == $_POST['password']){
				$this->assign('error',L('_B_MY_NEWSAMENOW_'));
			}else if($_POST['password2'] != $_POST['password']){
				$this->assign('error',L('_B_LOGIN_DIFFERENTKEY_'));
			}else{
				$result = D('User')->save_user($this->now_user['uid'],'pwd',md5($_POST['password']));
				if($result['error']){
					$this->assign('error',$result['msg']);
				}else{
					unset($_SESSION['veriry_token']);
					redirect(U('My/myinfo',array('OkMsg'=>urlencode(L('_B_LOGIN_CHANGEKEYSUCESS_')))));
				}
			}
		}
		$this->display();
	}
	//发送验证码
	public function SmsCodeverify() {
		$user_modifypwdDb = M('User_modifypwd');
		if(isset($_POST['phone']) && !empty($_POST['phone'])){
			$chars = '0123456789';
			mt_srand((double)microtime() * 1000000 * getmypid());
			$vcode = "";

			while (strlen($vcode) < 6)
				$vcode .= substr($chars, (mt_rand() % strlen($chars)), 1);
			$content = L('_B_LOGIN_YOURCODE_'). $vcode . L('_B_LOGIN_CODEPOINT_');
			Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $_POST['phone'], 'uid' => $this->now_user['uid'], 'type' => 'bindphone'));
			$addtime = time();
			$expiry = $addtime + 20 * 60; /*             * **二十分钟有效期*** */
			$data = array('telphone' => $_POST['phone'], 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
			$insert_id = $user_modifypwdDb->add($data);
			$this->ajaxReturn(array('error' => false));
			exit();

		}
	}

	public function bind_user(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		if(IS_POST){
			$database_user = D('User');
			if(empty($_POST['phone'])){
				$this->error(L('_B_LOGIN_ENTERPHONENO_'));
			}

			if(!empty($this->user_session['phone'])){
				$condition_user['phone'] = $_POST['phone'];
				if($database_user->field(true)->where($condition_user)->find()){
					$this->error(L('_B_MY_HAVETELNUM_'));
				}
			}

			//验证短信验证码

			//if ($this->config['bind_phone_verify_sms']&&$this->config['sms_key']&&substr($_POST['phone'],0,10)!='1321234567') {
            if($_POST['sms_code']){
				$sms_verify_result = D('Smscodeverify')->verify($_POST['sms_code'], $_POST['phone']);
				if ($sms_verify_result['error_code']) {
					$this->error($sms_verify_result['msg']);
				} else {
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}


			//if (!empty($modifypwd)||$this->config['bind_phone_verify_sms']=='0'||empty($this->config['sms_key'])) {
			//$nowtime = time();
			//if ($modifypwd['expiry'] > $nowtime||$this->config['bind_phone_verify_sms']=='0'||empty($this->config['sms_key'])) {

			$condition_user['phone'] = $_POST['phone'];
			$res = $database_user->field('`uid`,`pwd`')->where($condition_user)->find();
			if($res && empty($this->user_session['phone']) && !empty($this->user_session['openid'])){
				if($_POST['bind_exist']){
					$openid = $database_user->field('`openid`')->where(array('uid'=>$res['uid']))->find();
					if(!empty($openid['openid'])){
						$this->error("_B_MY_HAVEBANDINGWECHAT_");
					}
					$login_result = D('User')->checkin($_POST['phone'],$_POST['password']);
					if($login_result['error_code']){
						$this->error($login_result['msg']);
					}else{
						if($database_user->where(array('uid'=>$res['uid']))->setField('openid',$this->now_user['openid'])){
							$data_use['openid'] = $this->now_user['openid'].'~no_use';
							$data_use['status'] = 0;
							$database_user->where(array('uid'=>$this->now_user['uid']))->save($data_use);
							session_destroy();
							unset($_SESSION);
							$this->success(L('_B_MY_BANDINGACCESS1_'));
						}else {
							$this->error(L('_B_LOGIN_BINDINGLOSERETRY_'));
						}
					}
				}else {
					$this->error('phone_exist');
				}
			}

			$condition_save_user['uid'] = $this->now_user['uid'];
			$data_save_user['phone'] = $_POST['phone'];
			if(!empty($_POST['password'])){
				if(!empty($this->now_user['phone'])){
					$condition_save_user['pwd'] = md5($_POST['password']);
				}else{
					$data_save_user['pwd'] = md5($_POST['password']);
				}
			}


			if($database_user->where($condition_save_user)->data($data_save_user)->save()){
				$_SESSION['user']['phone'] = $_POST['phone'];
				session_destroy();
				unset($_SESSION);


				$database_house_village_user_bind = D('House_village_user_bind');
				$bind_where['uid'] = $this->now_user['uid'];
				$database_house_village_user_bind->where($bind_where)->data(array('phone'=>$_POST['phone']))->save();


				$this->success(L('_B_MY_BANDINGACCESS2_'));
			}else{
				$this->error(L('_B_LOGIN_BINDINGLOSERETRY_'));
			}
			exit();
			//}
			//	}
		}
		$now_user = D('User')->where(array('uid'=>$this->user_session['uid']))->find();

		if($now_user['phone'] != ''){
		    redirect(U('My/index'));
        }
		$referer = !empty($_GET['referer']) ? $_GET['referer'] : U('My/index');
		$this->assign('referer',$referer);
		$this->assign('now_user',$now_user);
		$this->display();

	}

	//验证原手机
	public function verify_original_phone(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		if(IS_POST) {
			$database_user = D('User');
			if (empty($_POST['phone'])) {
				$this->error(L('_B_LOGIN_ENTERPHONENO_'));
			}

			if (empty($_POST['sms_code'])) {
				$this->error(L('_B_MY_ENTERCODE_'));
			}
			//print_r($_POST);
			//验证短信验证码
			if (substr($_POST['phone'], 0, 10) != '1321234567') {
				$sms_verify_result = D('Smscodeverify')->verify($_POST['sms_code'], $_POST['phone']);
				if ($sms_verify_result['error_code']) {
					$this->error($sms_verify_result['msg']);
				} else {
					$modifypwd = $sms_verify_result['modifypwd'];
				}
			}
			$_SESSION['veriry_token'] =1 ;
			$this->success(L('_B_MY_BANDINGACCESS2_'),U('My/bind_user',array('bind'=>1)));

		}else{
			$referer = !empty($_GET['referer']) ? $_GET['referer'] : $_SERVER['HTTP_REFERER'];
			$this->assign('referer',$referer);
			$this->display();
		}
	}
	/*优惠券操作*/
	public function card(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$coupon_list = D('Member_card_coupon')->get_all_coupon($this->user_session['uid']);
		$this->assign('coupon_list',$coupon_list);

		$this->display();
	}

	/*checkout 选择优惠券*/
	public function select_card(){

		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		//以下代码是为了得到商户的mer_id ，并且判断此订单是否存在！
		if($_GET['type'] == 'group'){
			$now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
			if($now_order['order_info']['group_share_num']>0||$now_order['order_info']['pin_num']>0)$group_pay_offline=false;
		}else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
			$now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']), false, $_GET['type']);
			if ($now_order['order_info']['paid'] == 2) $this->assign('notCard',true);
			$_GET['type']  = 'meal';
		}else if($_GET['type'] == 'weidian'){
			$now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
			$this->assign('notCard',true);
		}else if($_GET['type'] == 'recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
			$this->assign('notCard',true);
		}else if($_GET['type'] == 'appoint'){
			$now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
		}else if($_GET['type'] == 'wxapp'){
			$_GET['notOffline'] = true;
			$now_order = D('Wxapp_order')->get_pay_order($_GET['uid'],intval($_GET['order_id']));
			$this->assign('notCard',true);
		}else if($_GET['type'] == 'store'){
			$_GET['notOffline'] = true;
			$now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
			//$this->assign('notCard',true);
		}else if($_GET['type'] == 'shop'||$_GET['type'] == 'mall'){
			$now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
		}else if($_GET['type'] == 'plat'){
			$now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
		}else if($_GET['type'] == 'balance-appoint'){
			$now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_GET['order_id']));
		}else{
			$this->error_tips('非法的订单');
		}

		$now_order = $now_order['order_info'];
		$now_order['total_money'] = $now_order['order_total_money'];

		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_！'));
		}

		if($_SESSION['discount']>0){
			$now_order['total_money']=$now_order['total_money']*$_SESSION['discount']/10;
			unset($_SESSION['discount']);
		}

		if($_SESSION['wx_cheap']>0){
			$now_order['total_money'] -=$_SESSION['wx_cheap'];
			unset($_SESSION['wx_cheap']);
		}
		$now_order['uid'] = $this->user_session['uid'];
		$this->assign('back_url',U('Pay/check',$_GET));
		if($this->is_app_browser){
            $platform = 'app';
        }else if($this->is_wexin_browser){
            $platform = 'weixin';
        }else{
            $platform = 'wap';
        }
		if($_GET['coupon_type']=='mer') {
            //$card_list = D('Member_card_coupon')->get_coupon($now_order['mer_id'], $this->user_session['uid']);
            if(!empty($now_order['business_type'])){
                $coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($now_order,$_GET['type'],$platform,$now_order['business_type']);
            }else{
                $coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($now_order, $_GET['type'],$platform);
            }
        }else if($_GET['coupon_type']=='system') {  //目前使用

			if($_SESSION['card_discount']>0){
				$now_order['total_money'] -= $_SESSION['card_discount'];
				unset($_SESSION['card_discount']);
			}

			if(!empty($now_order['business_type'])) {
				$coupon_list = D('System_coupon')->get_noworder_coupon_list($now_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform,$now_order['business_type']);
			}else{
				$coupon_list = D('System_coupon')->get_noworder_coupon_list($now_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform);
			}

            //获取活动优惠券
            $event_coupon_list = D('New_event')->getUserCoupon($this->user_session['uid'],0);
            if(!$coupon_list) $coupon_list = array();
            if(count($event_coupon_list) > 0){
                foreach ($event_coupon_list as &$v){
                    $v['id'] = $v['coupon_id'].'_'.$v['id'];
                    //当前页面is_use的值为是否可以使用
                    if($v['order_money'] <= $now_order['goods_price'])
                        $v['is_use'] = 1;
                    else
                        $v['is_use'] = 0;
                }
                $coupon_list = array_merge($coupon_list,$event_coupon_list);
            }
		}

		if(!empty($coupon_list)){

            $cmf_arr = array_column($coupon_list, 'discount');
            array_multisort($cmf_arr, SORT_DESC, $coupon_list);
            $cmf_arr = array_column($coupon_list, 'is_use');
            array_multisort($cmf_arr, SORT_DESC, $coupon_list);

			$param = $_GET;

			foreach($coupon_list as &$value){

                //如果存在平台优惠 而且 delivery_discount_type=0，优惠券 也是可用的
                //if ((float)$now_order['delivery_discount']>0 && $now_order['delivery_discount_type']==0){
                if (($now_order['order_type'] == 0 && (float)$now_order['delivery_discount']>0 && $now_order['delivery_discount_type']==0)||
                    ((float)$now_order['merchant_reduce']>0 && $now_order['merchant_reduce_type']==0)){
                    //那么就要提示用户，互斥提示 仅外卖订单
                    $value['need_notify_delivery_discount'] = "1";
                }else{
                    //否则，随便用户使用优惠券
                    $value['need_notify_delivery_discount'] = "0";
                }
//                //如果存在平台优惠 而且 delivery_discount_type=0，优惠券 也是可用的
//			    if ((float)$now_order['delivery_discount']>0 && $now_order['delivery_discount_type']==0 && $value['is_use']==1){
//                    //那么就要提示用户，互斥提示
//                    $value['delivery_discount'] = "1";
//			    }else{
//			        //否则，随便用户使用优惠券
//                    $value['delivery_discount'] = "0";
//                }

				if($_GET['coupon_type']=='mer'){
					$param['merc_id'] =$value['id'];
					unset($param['unmer_coupon']);
				}else{
					unset($param['unsys_coupon']);
					$param['sysc_id'] =$value['id'];
				}
				$value['select_url'] = U('Pay/check',$param);
			}
			$this->assign('coupon_list',$coupon_list);
			//var_dump($coupon_list);die();
		}

		$param = $_GET;

		if($_GET['coupon_type']=='mer'){
			unset($param['merc_id']);
			$param['unmer_coupon']=1;
		}else{
			unset($param['sysc_id']);
			$param['unsys_coupon']=1;
		}

		if($_GET['delivery_type']){
		    //减免配送和店铺满减 只要有一个与系统优惠券互斥
            if($_GET['merchant_type']){
                if($_GET['merchant_type'] == 0){
                    $_GET['delivery_type'] = $_GET['merchant_type'];
                }
            }
		    $this->assign('delivery_type',$_GET['delivery_type']);
        }

		$this->assign('unselect',U('Pay/check',$param));
		$this->display();
	}

	/*-- 地址操作  --*/
	public function adress(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

        $adress_list = D('User_adress')->get_adress_list($this->user_session['uid']);
        $sid = $_GET['store_id'] ? $_GET['store_id'] : 0;
        
        if ($_GET["from"]=="shop"){
            $_GET["from"]="address";
        }

        if($sid != 0){
            $store = D('Store')->get_store_by_id($sid);
        }else{
            $store = null;
        }

        $param = $_GET;

        //$adress_list=null;
        if ($_GET['buy_type']=='shop'){ //从购物车选择配送地址
            $page_title=L('V2_PAGETITLE_ADDRESS_SHOP');
            $param['adress_id']=0;
            $this->assign('new_url',U('My/edit_adress',$param));

        }else{                          //从个人中心进入收货地址
            $page_title=L('V2_PAGETITLE_ADDRESS');
            $this->assign('back_url',U('My/myinfo'));
            $this->assign('new_url',U('My/edit_adress'));
        }

        $this->assign('page_title',$page_title);
		if(empty($adress_list)){ //如果没有地址数据，就自动跳转到----->210305 如果地址数据为空，就停在列表里
		    //redirect(U('My/edit_adress',$_GET));
            $select_url = 'Shop/confirm_order';
            $this->assign('back_url',U($select_url,$_GET));
            $this->assign('adress_list_count',0);
		}else{
			if($_GET['group_id']){
				$select_url = 'Group/buy';
			} elseif ($_GET['store_id']) {
				if ($_GET['buy_type'] == 'waimai') {
					$select_url = 'Takeout/sureOrder';
				} elseif ($_GET['buy_type'] == 'shop') {
					$select_url = 'Shop/confirm_order';
                } elseif ($_GET['buy_type'] == 'check') {
                    $select_url = 'Pay/check';
				} elseif ($_GET['buy_type'] == 'mall') {
					$select_url = 'Mall/confirm_order';
				} else {
					$select_url = 'Meal/cart';
				}
			}elseif(!empty($_GET['gift_id'])){
				$select_url = 'Gift/order';
			}elseif(!empty($_GET['classify_userinput_id'])){
				$select_url = 'Classify/order';
			}

			if($select_url){
				$this->assign('back_url',U($select_url,$_GET));
                //$this->assign('back_url',"");
			}else{
				$this->assign('back_url',U('My/myinfo'));
			}

            $param = $_GET;

            foreach ($adress_list as $key => &$value) {
                $param['adress_id'] = $value['adress_id'];
                if (!empty($select_url)) {
                    if ($param['buy_type'] == "check") {
                        $param_x["order_id"] = $param["order_id"];
                        $param_x["type"] = "shop";
                        $param_x["adress_id"] = $param['adress_id'];
                        $adress_list[$key]['select_url'] = U($select_url, $param_x);
                    } else {
                        $adress_list[$key]['select_url'] = U($select_url, $param);
                    }
                }
                $adress_list[$key]['edit_url'] = U('My/edit_adress', $param);
                $adress_list[$key]['del_url'] = U('My/del_adress', $param);

                $value['distance'] = 0;

                if ($store) {
                    if($store['city_id'] != $value['city']){
                        $value['is_allow'] = 0;
                    }else {
                        $distance = getDistance($store['lat'], $store['lng'], $value['latitude'], $value['longitude']);
                        $value['distance'] = $distance;
                        if ($distance <= $store['delivery_radius'] * 1000) {
                            //获取特殊城市属性
                            $city = D('Area')->where(array('area_id' => $store['city_id']))->find();
                            if ($city['range_type'] != 0) {
                                switch ($city['range_type']) {
                                    case 1://按照纬度限制的城市 小于某个纬度
                                        if ($value['latitude'] >= $city['range_para']) $value['is_allow'] = 0;
                                        else $value['is_allow'] = 1;
                                        break;
                                    case 2://自定义区域
                                        import('@.ORG.RegionalCalu.RegionalCalu');
                                        $region = new RegionalCalu();
                                        if ($region->checkCity($city, $value['longitude'], $value['latitude'])) {
                                            $value['is_allow'] = 1;
                                        } else {
                                            $value['is_allow'] = 0;
                                        }
                                        break;
                                    default:
                                        $value['is_allow'] = 1;
                                        break;
                                }
                            } else {
                                $value['is_allow'] = 1;
                            }
                        } else {
                            $value['is_allow'] = 0;
                        }
                    }
                } else {
                    $value['is_allow'] = 1;
                }
            }
            if ($store) {
                $cmf_arr = array_column($adress_list, 'distance');
                array_multisort($cmf_arr, SORT_ASC, $adress_list);
            }

            $address_list_allow = array();

            foreach ($adress_list as $v) {
                if ($v['is_allow'] == 1) {
                    $address_list_allow[] = $v;
                }
            }
            //var_dump($address_list_allow);die();
            foreach ($adress_list as $v) {
                if ($v['is_allow'] == 0) {
                    $address_list_not_allow[] = $v;
                }
            }
            //var_dump($address_list_allow);
            //die();

			$this->assign('adress_list',$adress_list);
            $this->assign('adress_list_count',count($adress_list));

			$this->assign('adress_list_allow',$address_list_allow);
            $this->assign('adress_list_not_allow',$address_list_not_allow);

			//-------------------------------------------------------------------

			$database_area = D('Area');
			$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
			$this->assign('now_city_area',$now_city_area);

			$province_list = $database_area->get_arealist_by_areaPid(0);
			$this->assign('province_list',$province_list);

			$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
			$this->assign('city_list',$city_list);

			$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
			$this->assign('area_list',$area_list);

			$id = $_GET['adress_id'];
			if(cookie('user_address') === '0' || cookie("user_address") == "") {
				$now_adress = D('User_adress')->get_adress($this->user_session['uid'], $id);
				if ($now_adress) {
					$this->assign('now_adress', $now_adress);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
					$this->assign('city_list', $city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
					$this->assign('area_list', $area_list);
				} else {
					$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
					$this->assign('now_city_area',$now_city_area);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
					$this->assign('city_list',$city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
					$this->assign('area_list',$area_list);
				}
			} else {
				$cookie = json_decode($_COOKIE['user_address'], true);
				$now_adress = $cookie;
				$now_adress['default'] = $now_adress['defaul'];
				$now_adress['adress_id'] = $now_adress['id'];
				$this->assign('now_adress', $now_adress);
				$province_list = $database_area->get_arealist_by_areaPid(0);
				$this->assign('province_list',$province_list);

				$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
				$this->assign('city_list', $city_list);

				$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
				$this->assign('area_list', $area_list);
				$params = $_GET;
				unset($params['adress_id']);
				$this->assign('params',$params);
			}
		}

        $this->display();
	}

	public function credit(){
        if(empty($this->user_session)){
            $this->error_tips(L('_B_MY_LOGINFIRST_'));
        }

        if($_GET['order_id']){
            $this->assign('order_id',$_GET['order_id']);
        }

        if($_GET['type']){
            $this->assign('type',$_GET['type']);
        }else{
            $this->assign('type','');
        }

        $card_list = D('User_card')->getCardListByUid($this->user_session['uid']);
        $this->assign('card_list',$card_list);
        $this->display();
    }

    public function del_card(){
	    if($_GET['id']){
            D('User_card')->field(true)->where(array('id'=>$_GET['id']))->delete();
            $this->success(L('_OPERATION_SUCCESS_'));
        }
        $this->error(L('_OPERATION_FAIL_'));
    }

    public function edit_card(){
	    if($_POST){

	        $data['name'] = $_POST['name'];
	        $data['card_num'] = $_POST['card_num'];
	        $data['expiry'] = transYM($_POST['expiry']);

	        //如果 is_default 存在，清空之前的default
	        if($_POST['is_default']){
                D('User_card')->clearIsDefaultByUid($this->user_session['uid']);
            }
	        $data['is_default'] = $_POST['is_default'] ? $_POST['is_default'] : 0;

	        $data['uid'] = $this->user_session['uid'];

	        if($_POST['id'] && $_POST['id'] != ''){
                $data['id'] = $_POST['id'];
                $card = D('User_card')->field(true)->where(array('id'=>$data['id']))->find();
                if($card['card_num'] != $data['card_num'] || $card['expiry'] != $data['expiry']) {
                    //garfunkel 如果修改信用卡信息将验证信息清零
                    $data['cvd'] = '';
                    $data['verification_time'] = '';
                    $data['status'] = 0;
                }
                D('User_card')->field(true)->where(array('id' => $data['id']))->save($data);
                $this->success(L('_OPERATION_SUCCESS_'));
            }else{
                $isC = D('User_card')->getCardByUserAndNum($data['uid'],$data['card_num']);
                if($isC){
                    $this->error(L('_CARD_EXIST_'));
                }else{
                    $data['create_time'] = date("Y-m-d H:i:s");
                    D('User_card')->field(true)->add($data);
                    $this->success(L('_OPERATION_SUCCESS_'));
                }
            }
        }else if($_GET['id']){
	        $card = D('User_card')->field(true)->where(array('id'=>$_GET['id']))->find();
            $this->assign('card',$card);
            $this->assign('card_id',$_GET['id']);
        }
	    $this->display();
    }

	public function pick_address(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$flag = $_GET['buy_type'] == 'shop' || $_GET['buy_type'] == 'mall' ? true : false;
		$adress_list = D('Pick_address')->get_pick_addr_by_merid($_GET['mer_id'], $flag);
		if(empty($adress_list)){
			$this->error_tips(L('_B_MY_WRONGADDRESS_'));
		}else{
			if($_GET['group_id']){
				$select_url = 'Group/buy';
			} elseif ($_GET['store_id']) {
				if ($_GET['buy_type'] == 'waimai') {
					$select_url = 'Takeout/sureOrder';
				} elseif ($_GET['buy_type'] == 'shop') {
					$select_url = 'Shop/confirm_order';
                } elseif ($_GET['buy_type'] == 'check') {
                    $select_url = 'Pay/check';
				} elseif ($_GET['buy_type'] == 'mall') {
					$select_url = 'Mall/confirm_order';
				} else {
					$select_url = 'Meal/cart';
				}
			}

			if($select_url){
				$this->assign('back_url',U($select_url,$_GET));
			}else{
				$this->assign('back_url',U('My/myinfo'));
			}

			$param = $_GET;

			foreach($adress_list as $key=>$value){
				$param['pick_addr_id'] = $value['pick_addr_id'];
				$adress_list[$key]['distance'] = $this->wapFriendRange($value['distance']);
				if(!empty($select_url)){
					$adress_list[$key]['select_url'] = U($select_url,$param);
				}
			}
			//dump($adress_list);
			$this->assign('pick_list',$adress_list);
			$this->display();
		}

	}
	/*添加编辑地址*/
	public function edit_adress(){
		$id=0;
	    if(IS_POST){
			if(empty($_POST['adress'])){
				$this->error(L('_B_MY_NOPOSITION_'));
			}

			if(D('User_adress')->post_form_save($this->user_session['uid']) !== false){
				cookie('user_address', 0);
				$this->success(L('_B_MY_SAVEACCESS_'));
			}else{
				$this->error(L('_B_MY_SAVEPOSITIONLOSE_').'----');
			}
		}else{

			$database_area = D('Area');
			$id = $_GET['adress_id'];
            $from = $_GET['from'];
            $this->assign('from',$from);
            $this->assign('address_id',$id);
			if(cookie('user_address') === '0' || cookie("user_address") == "") {
// 				$where['address_id'] = $id;
// 				$where['uid'] = $this->_uid;
				$now_adress = D('User_adress')->get_adress($this->user_session['uid'], $id);
				if ($now_adress) {
                    $city = $database_area->where(array('area_id'=>$now_adress['city']))->find();
                    $now_adress['city_name'] = $city['area_name'];

                    $this->assign('now_adress', $now_adress);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
					$this->assign('city_list', $city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
					$this->assign('area_list', $area_list);
				} else {
					$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
					$this->assign('now_city_area',$now_city_area);

					$province_list = $database_area->get_arealist_by_areaPid(0);
					$this->assign('province_list',$province_list);

					$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
					$this->assign('city_list',$city_list);

					$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
					$this->assign('area_list',$area_list);
				}
			} else {
				$cookie = json_decode($_COOKIE['user_address'], true);
				$now_adress = $cookie;

				$now_adress['default'] = $now_adress['defaul'];
				$now_adress['adress_id'] = $now_adress['id'];

				$this->assign('now_adress', $now_adress);
				$province_list = $database_area->get_arealist_by_areaPid(0);
				$this->assign('province_list',$province_list);

				$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
				$this->assign('city_list', $city_list);

				$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
				$this->assign('area_list', $area_list);
			}


// 			if($_GET['adress_id']){
// 				$now_adress = D('User_adress')->get_adress($this->user_session['uid'],$_GET['adress_id']);
// 				if(empty($now_adress)){
// 					$this->error_tips(L('_B_MY_NOTAADDRESS_'));
// 				}
// 				$this->assign('now_adress',$now_adress);

// 				$province_list = $database_area->get_arealist_by_areaPid(0);
// 				$this->assign('province_list',$province_list);

// 				$city_list = $database_area->get_arealist_by_areaPid($now_adress['province']);
// 				$this->assign('city_list',$city_list);

// 				$area_list = $database_area->get_arealist_by_areaPid($now_adress['city']);
// 				$this->assign('area_list',$area_list);
// 			}else{
// 				$now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
// 				$this->assign('now_city_area',$now_city_area);

// 				$province_list = $database_area->get_arealist_by_areaPid(0);
// 				$this->assign('province_list',$province_list);

// 				$city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
// 				$this->assign('city_list',$city_list);

// 				$area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
// 				$this->assign('area_list',$area_list);
// 			}

			$params = $_GET;
			unset($params['adress_id']);
			$this->assign('params',$params);
		}


		$this->display();
	}

	/* 地图 */
	public function adres_map()
	{
		$cookie = json_decode($_COOKIE['user_address'], true);
//		if (empty($cookie['province']) || empty($cookie['city'])) {
//			$this->error(L('_B_MY_CHOOSECITY_'));
//		}
		//$list = D('Area')->field(true)->where("area_id IN ({$cookie['province']}, {$cookie['city']}, {$cookie['area']})")->order('area_type ASC')->select();
		$address = '';
//		foreach ($list as $row) {
//			$address .= $row['area_name'];
//		}
		//$this->assign('address', $address);
		$params = $_GET;
		unset($params['adress_id']);
		$this->assign('params',$params);
		$this->display();
	}
	/*删除地址*/
	public function del_adress(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$result = D('User_adress')->delete_adress($this->user_session['uid'],$_GET['adress_id']);
		if($result){
			$this->success(L('_B_MY_DELACCESS_'));
		}else{
			$this->error(L('_B_MY_DELLOSE_'));
		}
	}


	/*删除地址*/
	public function ajax_del_adress(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$result = D('User_adress')->delete_adress($this->user_session['uid'],$_GET['adress_id']);
		if($result){
			exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELACCESS_'))));
		}else{
			exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELLOSE_'))));
		}
	}

	public function select_area(){
		$area_list = D('Area')->get_arealist_by_areaPid($_POST['pid']);
		if(!empty($area_list)){
			$return['error'] = 0;
			$return['list'] = $area_list;
		}else{
			$return['error'] = 1;
		}
		echo json_encode($return);
	}
	/*全部团购*/
	public function group_order_list(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$order_list = D('Group')->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));
		$this->assign('order_list',$order_list);

		$this->display();
	}

	public function classify_order_list(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$database_classify_userinput = D('Classify_userinput');
		$order_list = $database_classify_userinput->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));
		$this->assign('order_list',$order_list);

		$this->display();
	}

	public function ajax_classify_order_list(){
		if(IS_AJAX){
			$database_classify_userinput = D('Classify_userinput');
			$order_list = $database_classify_userinput->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));

			if(!empty($order_list)){
				exit(json_encode(array('status'=>1,'order_list'=>$order_list)));
			}else{
				exit(json_encode(array('status'=>0,'order_list'=>$order_list)));
			}
		}else{
			$this->error('访问页面不存在！~~');
		}
	}

	public function ajax_group_order_list(){
		if(IS_AJAX){
			$order_list = D('Group')->wap_get_order_list($this->user_session['uid'],intval($_GET['status']));
			if(!empty($order_list)){
				exit(json_encode(array('status'=>1,'order_list'=>$order_list)));
			}else{
				exit(json_encode(array('status'=>0,'order_list'=>$order_list)));
			}

		}else{
			$this->error('访问页面不存在！~~');
		}
	}

	/*全部预约*/
	public function appoint_order_list(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$mer_id = $_GET['mer_id'] + 0;
		$status = $_GET['status'] ? $_GET['status'] + 0 : 0;

		$database_appoint = D('Appoint');

		if($status == 1){
			$where['service_status'] = 0;
		}elseif($status == 2){
			$where['service_status'] = 1;
		}

		if(!empty($mer_id)){
			$where['mer_id'] = $mer_id;
			$where['uid'] = $this->user_session['uid'];
			$order_list = $database_appoint->wap_order_list($where);
		}else{
			$order_list = $database_appoint->wap_get_order_list($this->user_session['uid'], $status);
		}

		$this->assign('order_list', $order_list);
		$this->display();
	}
	# 删除预约
	public function ajax_appoint_order_del(){
		$database_appoint_order = D('Appoint_order');
		$now_order = $database_appoint_order->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_！'));
		}else if($now_order['paid']){
			$this->error_tips('当前订单已付款，不能删除。');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['is_del'] = 5;
		if($database_appoint_order->where($condition_group_order)->data($data_group_order)->save()){
			exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELACCESS_'))));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_DELLOSE_'))));
		}
	}

	public function ajax_appoint_order_list(){
		$status = $_GET['status'] ? $_GET['status'] + 0 : 0;
		$database_appoint = D('Appoint');

		$order_list = $database_appoint->wap_get_order_list($this->user_session['uid'], $status);

		if(!empty($order_list)){
			exit(json_encode(array('status' => 1 , 'order_list'=>$order_list)));
		}else{
			exit(json_encode(array('status'=> 0 , 'order_list'=>$order_list)));
		}
	}

	public function gift_order_list(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$database_gift_order = D('Gift_order');
		$order_list = $database_gift_order->get_order_list($this->user_session['uid'],intval($_GET['status']),true,99999);

		$this->assign('order_list',$order_list);

		$this->display();
	}


	public function ajax_gift_order_list(){
		if(IS_AJAX){
			if(empty($this->user_session)){
				$this->error_tips(L('_B_MY_LOGINFIRST_'));
			}

			$database_gift_order = D('Gift_order');
			$order_list = $database_gift_order->get_order_list($this->user_session['uid'],intval($_GET['status']),true,99999);

			foreach($order_list['order_list'] as &$order){
				$order['order_url'] = U('My/gift_order',array('order_id'=>$order['order_id']));
			}

			if($order_list['order_list']){
				exit(json_encode(array('status'=>1,'order_list'=>$order_list['order_list'])));
			}else{
				exit(json_encode(array('status'=>0,'order_list'=>$order_list['order_list'])));
			}
		}else{
			$this->error_tips(L('_B_MY_PAGEWRONG_'));
		}
	}

	public function gift_order(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$order_id = $_GET['order_id'] + 0;
		if(!$order_id){
			$this->error_tips(L('_B_MY_PASSWRONG_'));
		}

		$database_gift_order = D('Gift_order');
		$now_order = $database_gift_order->get_order_detail_by_id($this->user_session['uid'],$order_id);

		$this->assign('now_order',$now_order);
		$this->display();
	}

	/*团购收藏*/
	public function group_collect(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$this->assign(D('Group')->wap_get_group_collect_list($this->user_session['uid']));

		$this->display();
	}

	//预约收藏
	public function appoint_collect(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$this->assign(D('Appoint')->wap_get_appoint_collect_list($this->user_session['uid']));
		$this->display();
	}

	/*预约详情*/
	public function appoint_order(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$now_order = D('Appoint_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
		$now_order['order_type'] = 'appoint';
		$laste_order_info = D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
		if(!$now_order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$now_order = D('Appoint_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
			}
		}
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}

		$now_supply = D('Appoint_supply')->where(array('order_id'=>intval($_GET['order_id']),'status'=>3))->find();
		$this->assign('now_supply',$now_supply);
		$this->assign('now_order',$now_order);
		$this->display();
	}
	/*团购详情*/
	public function group_order(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$otherrm = isset($_GET['otherrm']) ? intval($_GET['otherrm']) : 0;
		$otherrm && $_SESSION['otherwc'] = null;
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);

		$now_order['order_type'] = 'group';
		$laste_order_info=D('Tmp_orderid')->get_laste_order_info($now_order['order_type'],$now_order['order_id']);
		if(!$now_order['paid'] && !empty($laste_order_info)) {
			if ($laste_order_info['pay_type']=='weixin') {
				$redirctUrl = C('config.site_url') . '/wap.php?g=Wap&c=Pay&a=weixin_back&order_type='.$now_order['order_type'].'&order_id=' . $laste_order_info['orderid'];
				file_get_contents($redirctUrl);
				$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'], intval($_GET['order_id']), true);
			}
		}
		$now_group = D('Group')->where(array('group_id'=>$now_order['group_id']))->find();

		$database_merchant = D('Merchant');
		$now_merchant = $database_merchant->get_info($now_group['mer_id']);
		$now_group['merchant_name'] = $now_merchant['name'];

		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if(empty($now_order['paid'])){
			$now_order['status_txt'] = '未付款';
		}else if(empty($now_order['third_id']) && $now_order['pay_type'] == 'offline'){
			$now_order['status_txt'] = '线下未付款';
		}else if(empty($now_order['status'])){
			if($now_order['tuan_type'] != 2){
				$now_order['status_txt'] = '未消费';
			}else{
				$now_order['status_txt'] = '未发货';
			}
		}else if($now_order['status'] == '1'){
			$now_order['status_txt'] = '待评价';
		}else if($now_order['status'] == '2'){
			$now_order['status_txt'] = '已完成';
		}else if($now_order['status'] == '3'){
			$now_order['status_txt'] = '已退款';
			$now_order['group_pass_txt'] = '退款订单无法查看';
		}


		$uid = $this->user_session['uid'];
		$group_share_num = D('Group_share_relation')->get_share_num($uid,$now_order['order_id']);
		$is_shared = D('Group_share_relation')->check_share($uid,$now_order['order_id']);
		$pic = explode(';',$now_group['pic']);
		foreach($pic as &$v){
			$v = preg_replace('/,/','/',$v);
		}
		$now_group['pic'] = $pic;
		if($now_group['pin_num']>0 && $now_order['single_buy']==0 && $now_order['status']<3 && $now_order['paid']){
			$my_group_join = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
			if(empty($my_group_join)&&$now_order['paid']==1){
				$this->error_tips('当前订单出错');
			}
			$buyer = D('Group_start')->get_buyerer_by_order_id($now_order['order_id']);
			$robot_list = M('Robot_list')->where(array('mer_id'=>$now_order['mer_id']))->getField('id,robot_name,avatar');

			foreach ($buyer as &$v) {
				if($v['type']==1||$v['uid']!=$now_order['uid']){
					$tmp_name = $robot_list[$v['uid']]['robot_name'];
					$strlen     = mb_strlen($tmp_name, 'utf-8');
					$firstStr     = mb_substr($tmp_name, 0, 1, 'utf-8');
					$lastStr     = mb_substr($tmp_name, -1, 1, 'utf-8');
					$v['nickname'] =  $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($tmp_name, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 1) ;
					$v['avatar'] = $robot_list[$v['uid']]['avatar'];
				}
			}
			//$group_share_info = D('Group_start')->get_group_start_user_by_gid($my_group_join['id']);
			$end_time = $my_group_join['start_time'] + $now_group['pin_effective_time'] * 3600;
			$effective_time = $end_time - $_SERVER['REQUEST_TIME'];
			$efftime['h'] = floor($effective_time / 3600);
			$efftime['m'] = floor(($effective_time - $efftime['h'] * 3600) / 60);
			$efftime['s'] = $effective_time - $efftime['h'] * 3600 - $efftime['m'] * 60;

			if ($effective_time > 0) {
				$this->assign('effective_time', $efftime);
			}
			$end_time = $my_group_join['start_time']+$now_group['pin_effective_time']*3600;
			$effective_time= $end_time-$_SERVER['REQUEST_TIME'];
			if($effective_time<=0&&$my_group_join['status']==0){
				D('Group_start')->timeout($now_order['order_id']);
				$my_group_join['status'] = 2;
			}
			//$this->assign('effective_time',$effective_time);
			$this->assign('buyer',$buyer);
			$this->assign('my_group_join',$my_group_join);
		}else {
			if ($now_group['group_share_num'] == 0 && $now_group['open_now_num'] == 0 && $now_group['open_num'] == 0) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			} else if ($now_group['group_share_num'] != 0 && $now_group['group_share_num'] <= $group_share_num) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			} else if ($now_group['open_now_num'] <= $now_group['sale_count'] && $now_group['open_now_num'] != 0 && $now_group['group_share_num'] == 0) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			} else if ($now_group['open_num'] <= $now_group['sale_count'] && $now_group['open_num'] != 0 && $now_group['open_now_num'] == 0 && $now_group['group_share_num'] == 0) {
				M('Group_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_share_group' => 2));
				$now_order['is_share_group'] = 2;
			}
			if ($now_group['group_share_num'] > 0) {
				$share_user = D('Group_share_relation')->get_share_user($this->user_session['uid'], $now_order['order_id']);
				$this->assign('share_user', $share_user);
			}
		}
		//$now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
		$has_pay = $now_order['wx_cheap']+$now_order['merchant_balance']+$now_order['balance_pay']+$now_order['score_deducte']+$now_order['coupon_price']+$now_order['payment_money'];

		if($now_order['pass_array']){
			$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;
			$this->assign('pass_array',$pass_array);
		}
		if($now_order['status']==6){
			//$total_pay = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
			if($now_order['num']!=$unconsume_pass_num){
				$refund_money = $now_order['refund_money'];
			}else{
				$refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money'];
			}
			$now_order['refund_total'] = $refund_money;
		}else{
			$now_order['refund_total'] = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance'];
		}

		if($now_order['trade_info']){
			$trade_info_arr = unserialize($now_order['trade_info']);
			if($trade_info_arr['type'] == 'hotel'){
				$trade_hotel_info = D('Trade_hotel_category')->format_order_trade_info($now_order['trade_info']);
				$has_refund = $trade_hotel_info['has_refund'];
				$trade_refund = true;
				if($has_refund==1){
					$trade_refund = false;
				}elseif($has_refund==2&&$now_order['add_time']+3600*$trade_hotel_info['refund_hour']>time()){
					$trade_refund = false;
				}
				$trade_hotel_info['refund'] = $trade_refund;
				$this->assign('trade_hotel_info',$trade_hotel_info);
			}
		}

		$lng_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
		$this->assign('lat',$lng_lat['lat']);
		$this->assign('lng',$lng_lat['long']);
		$this->assign('now_order',$now_order);
		$this->assign('now_group',$now_group);
		$this->assign('group_share_num',$group_share_num);
		$this->assign('is_shared',$is_shared);
		$this->assign('now_merchant',$now_merchant);
		if($now_group['pin_num']>0){
			$this->display('pin_group_order');
		}else{
			$this->display();
		}
	}
	/*团购详情*/
	public function meal_order_refund(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$orderid = intval($_GET['order_id']);
		$store_id = intval($_GET['store_id']);
		$now_order = M("Meal_order")->where(array('order_id' => $orderid, 'mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		if (empty($now_order)) {
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if ($now_order['is_confirm']) {
			$this->error_tips(L('_B_MY_ORDERDEALING_'));
		}
		if(empty($now_order['paid'])){
			$this->error_tips(L('_B_MY_ORDERNOPAY_'));
		}
		if ($now_order['meal_type']) {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'), U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		} else {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'), U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		}

		$now_order['price'] = $now_order['pay_money'];
		$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'],$now_order['is_mobile_pay']);
		$this->assign('now_order',$now_order);
		$this->display();
	}
	//取消订单
	public function meal_order_check_refund(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$orderid = intval($_GET['orderid']);
		$store_id = intval($_GET['store_id']);
		$now_order = M("Meal_order")->where(array('order_id' => $orderid, 'mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		//dump($now_order);
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if ($now_order['is_confirm']) {
			$this->error_tips(L('_B_MY_ORDERDEALING_'));
		}
		if(empty($now_order['paid'])){
			$this->error_tips(L('_B_MY_ORDERNOPAY_'));
		}
		if ($now_order['meal_type']) {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'), U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		} else {
			if ($now_order['status'] > 0 && $now_order['status'] < 3) {
				$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'), U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			} elseif ($now_order['status'] > 2) {
				$this->redirect(U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
			}
		}

// 		$now_order['price'] = $now_order['pay_money'];
// 		$data_meal_order['pay_money'] = 0;
// 		$data_meal_order['paid'] = 0;
		$my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
		//在线付款退款
		if($now_order['pay_type'] == 'offline'){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize(array('refund_time'=>time()));
			$data_meal_order['status'] = 3;
			if(D('Meal_order')->data($data_meal_order)->save()){
				//退款打印
				$msg = ArrayToStr::array_to_str($now_order['order_id']);
				$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
				$op->printit($this->mer_id, $store_id, $msg, 3);

				$str_format = ArrayToStr::print_format($now_order['order_id']);
				foreach ($str_format as $print_id => $print_msg) {
					$print_id && $op->printit($this->mer_id, $store_id, $print_msg, 3, $print_id);
				}

				$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
				$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
				if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = L('_B_MY_YOUAT_ ') . $mer_store['name'] . L('_B_MY_SHOPORDERNUM_') . $orderid . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED_');
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $mer_store['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = L('_B_MY_CUSTOMER_') . $now_order['name'] . L('_B_MY_BOOKINGNUM_') . $orderid . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED2_');
					Sms::sendSms($sms_data);
				}
				//如果使用了优惠券
				if($now_order['card_id']){
					$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//如果使用了积分 2016-1-15
				if ($now_order['score_used_count']!=='0') {
					$order_info=unserialize($now_order['info']);
					$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
					$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_').$order_name.' '.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//平台余额退款
				if($now_order['balance_pay'] != '0.00'){
					$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'订单退款 (订单号:'.$now_order['order_name'].')-1',0,0,0,'Order Cancellation (Order #'.$now_order['order_name'].')');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = '平台余额退款成功';
				}
				//商家会员卡余额退款
				if($now_order['merchant_balance'] != '0.00'){
					//$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额');
					$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_meal_order['order_id'] = $now_order['order_id'];
					$data_meal_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_meal_order['status'] = 3;
					D('Meal_order')->data($data_meal_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}
				if ($now_order['meal_type']) {
					$this->success_tips(L('_B_MY_USEOFFLINECHANGEREFUND_'),U('Takeout/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
				} else {
					$this->success_tips(L('_B_MY_USEOFFLINECHANGEREFUND_'),U('Food/order_detail',array('order_id'=>$now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
				}
				exit;
			}else{
				$this->error_tips(L('_B_MY_CANCELLLOSE_'));
			}
		}
		if($now_order['payment_money'] != '0.00'){
			if($now_order['is_own']){
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_order['mer_id']))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
				if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
					$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
					$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
					$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
					$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
					$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
					$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
					$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
					$pay_method['weixin']['config']['is_own'] = 1 ;
				}
			}else{
				$pay_method = D('Config')->get_pay_method(0,0,1);
			}

			if(empty($pay_method)){
				$this->error_tips(L('_B_MY_NOPAIMENTMETHOD_'));
			}
			if(empty($pay_method[$now_order['pay_type']])){
				$this->error_tips(L('_B_MY_CHANGEPAIMENT_'));
			}

			$pay_class_name = ucfirst($now_order['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result)){
				$this->error_tips(L('_B_MY_THISPAIMENTNOTOPEN_'));
			}

			if ($now_order['meal_type'] == 1) {
				$now_order['order_type'] = 'takeout';
			} elseif ($now_order['meal_type'] == 2) {
				$now_order['order_type'] = 'foodPad';
			} else {
				$now_order['order_type'] = 'food';
			}
			$order_id = $now_order['order_id'];
			$now_order['order_id'] = $now_order['orderid'];

			if($now_order['is_mobile_pay']==3){
				$pay_method[$now_order['pay_type']]['config'] =array(
						'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
						'pay_weixin_key'=>$this->config['pay_wxapp_key'],
						'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
						'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
				);
			}

			$pay_class = new $pay_class_name($now_order,$now_order['payment_money'],$now_order['pay_type'],$pay_method[$now_order['pay_type']]['config'],$this->user_session,1);
			$go_refund_param = $pay_class->refund();

			$now_order['order_id'] = $orderid;
			$data_meal_order['order_id'] = $orderid;
			$data_meal_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$data_meal_order['status'] = 3;
			}

			D('Meal_order')->data($data_meal_order)->save();
			if($data_meal_order['status'] != 3){
				$this->error_tips($go_refund_param['msg']);
			}
		}
		//如果使用了优惠券
		if($now_order['card_id']){
			$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}


		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count']!=='0') {
			$order_info=unserialize($now_order['info']);
			$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_').$order_name.' '.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'订单退款 (订单号:'.$now_order['order_name'].')-2',0,0,0,'Order Cancellation (Order #'.$now_order['order_name'].')');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}
		//商家会员卡余额退款
		if($now_order['merchant_balance'] != '0.00'){
			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}
		if(empty($now_order['pay_type'])){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			$go_refund_param['msg'] = L('_B_MY_ORDERCANCELLEDACCESS_');
		}

		//退款时销量回滚
		if ($now_order['paid'] == 1 && date('m', $now_order['dateline']) == date('m')) {
			foreach (unserialize($now_order['info']) as $menu) {
				D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
			}
		}
		D("Merchant_store_meal")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);

		//退款打印
		$msg = ArrayToStr::array_to_str($now_order['order_id']);
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->mer_id, $store_id, $msg, 3);


		$str_format = ArrayToStr::print_format($now_order['order_id']);
		foreach ($str_format as $print_id => $print_msg) {
			$print_id && $op->printit($this->mer_id, $store_id, $print_msg, 3, $print_id);
		}

		$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
		if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = L('_B_MY_YOUAT_') . $mer_store['name'] . L('_B_MY_SHOPORDERNUM_') . $orderid . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED_');
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = L('_B_MY_CUSTOMER_') . $now_order['name'] . L('_B_MY_BOOKINGNUM_') . $orderid . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED2_');
			Sms::sendSms($sms_data);
		}

		if ($now_order['meal_type'] == 1) {
			$this->success_tips($go_refund_param['msg'], U('Takeout/order_detail',array('order_id'=>$orderid, 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
		} else {
			$this->success_tips($go_refund_param['msg'], U('Food/order_detail',array('order_id'=>$orderid, 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
		}
	}

	/*团购详情*/
	public function group_order_refund(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
		$now_group = D('Group')->where(array('group_id'=>$now_order['group_id']))->find();

		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if(empty($now_order['paid'])){
			$this->error_tips(L('_B_MY_ORDERNOPAY_'));
		}
		if ($now_order['status'] > 0 && $now_order['status'] < 3) {
			$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'),U('My/group_order',array('order_id'=>$now_order['order_id'])));
		} elseif ($now_order['status'] > 2) {
			$this->redirect(U('My/group_order',array('order_id'=>$now_order['order_id'])));
		}

		$now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
		$has_pay =$now_order['wx_cheap']+$now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money']+$now_order['score_deducte']+$now_order['coupon_price']+$now_order['card_give_money'];
		$tmp_price = ($has_pay-$now_order['wx_cheap'])/$now_order['num'];
		//未消费数
		if($now_order['pass_array']){
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;
		}elseif($now_order['tuan_type']==2){
			$unconsume_pass_num = $now_order['num'];
		}else{
			$unconsume_pass_num=1;
		}
		$this->assign('unconsume_pass_num',$unconsume_pass_num);

		//退款金额
		$res = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
		if($now_group['group_share_num']>0&&$now_group['pin_num']==0){
			if($now_order['num']!=$unconsume_pass_num){
				$refund_money = round($has_pay-$now_order['price']*$consume_num-$now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100>0? $has_pay-$now_order['price']*$consume_num-$now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100:0,2);
				$refund_fee = round($now_group['group_refund_fee']*$now_order['price']*$unconsume_pass_num/100,2);
			}else{
				$refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money']+$now_order['card_give_money'];
				$refund_fee=0;
			}
		}elseif($now_group['pin_num'] != 0&&!$now_order['single_buy'] && $res['status']!=2){

			if($res['status']){
				if($unconsume_pass_num == 1){
					$consume_num = 1;
				}
				if($consume_num == 0){
					$consume_num = $now_order['num'];
				}
				$refund_fee = round($now_order['price']*$now_group['group_refund_fee']/100*$consume_num,2);
				$refund_money =$has_pay-$refund_fee;

			}
		}elseif($now_order['num']!=$unconsume_pass_num){
			$refund_money = $unconsume_pass_num*$tmp_price;
		}else{
			$refund_money = $now_order['merchant_balance']+$now_order['balance_pay']+$now_order['payment_money']+$now_order['card_give_money'];
			$refund_fee=0;
		}
		$this->assign('refund_money',$refund_money);
		$this->assign('refund_fee',$refund_fee);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	//取消订单
	public function group_order_check_refund(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);

		$now_group = M('Group')->where(array('group_id'=>$now_order['group_id']))->find();
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if(empty($now_order['paid'])){
			$this->error_tips(L('_B_MY_ORDERNOPAY_'));
		}
		if ($now_order['status'] > 0 && $now_order['status'] < 3) {
			$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'),U('My/group_order',array('order_id'=>$now_order['order_id'])));
		} elseif ($now_order['status'] > 2) {
			$this->redirect(U('My/group_order',array('order_id'=>$now_order['order_id'])));
		}

		if($now_order['is_share_group']==2){
			$need_refund_fee = true;
		}

		if($now_order['pass_array']){
			$consume_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'],1);
			$unconsume_pass_num = $now_order['num']-$consume_num;
		}elseif($now_order['tuan_type']==2){
			$unconsume_pass_num = $now_order['num'];
		}else{
			$unconsume_pass_num = 1;
		}

		//线下付款退款
		if($now_order['pay_type'] == 'offline'){
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize(array('refund_time'=>time()));
			$data_group_order['status'] = 3;
			if(D('Group_order')->data($data_group_order)->save()){
				//2015-12-24     线下退款时销量回滚
				$update_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();
				if ($update_group['type'] == 3) {
					$sale_count = $update_group['sale_count'] - $now_order['num'];
					$sale_count = $sale_count > 0 ? $sale_count : 0;
					$update_group_data = array('sale_count' => $sale_count);
					if ($update_group['count_num'] > 0 && $sale_count < $update_group['count_num']) {
						$update_group_data['type'] = 1;
					}
					D('Group')->where(array('group_id' => $now_order['group_id']))->save($update_group_data);
				} else {
					//退款时销量回滚
					D('Group')->where(array('group_id' => $now_order['group_id']))->setDec('sale_count', $now_order['num']);
				}


				//用户积分退款是回滚
				if ($now_order['score_used_count']!==0&&$unconsume_pass_num==$now_order['num']) {

					$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_').$now_order['order_name'].C('config.score_name').L('_B_MY_ROLLBACK_'));
					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}

				//平台余额退款
				if($now_order['balance_pay'] != '0.00'){
					$result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'订单退款 (订单号:'.$now_order['order_name'].')-3',0,0,0,'Order Cancellation (Order #'.$now_order['order_name'].')');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}

					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = '平台余额退款成功';
				}
				//商家会员卡余额退款
				if($now_order['merchant_balance'] != '0.00'){
//					if($now_order['num']>$unconsume_pass_num){
//						$now_order['merchant_balance'] =  ($has_pay-$now_order['balance_pay']-$consume_num*$now_order['price'])>0?($has_pay-$now_order['balance_pay']-$consume_num*$now_order['price']):0;
//					}
					$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');

					$param = array('refund_time' => time());
					if($result['error_code']){
						$param['err_msg'] = $result['msg'];
					} else {
						$param['refund_id'] = $now_order['order_id'];
					}
					$data_group_order['order_id'] = $now_order['order_id'];
					$data_group_order['refund_detail'] = serialize($param);
					$result['error_code'] || $data_group_order['status'] = 3;
					D('Group_order')->data($data_group_order)->save();
					if ($result['error_code']) {
						$this->error_tips($result['msg']);
					}
					$go_refund_param['msg'] = $result['msg'];
				}
				$this->success_tips(L('_B_MY_USEOFFLINECHANGEREFUND_'),U('My/group_order',array('order_id'=>$now_order['order_id'])));
				exit;
			}else{
				$this->error_tips(L('_B_MY_CANCELLLOSE_'));
			}
		}
		$total_pay = $now_order['balance_pay']+$now_order['payment_money']+$now_order['merchant_balance']+$now_order['card_give_money'];
		$balance_percent  = round($now_order['balance_pay']/$total_pay,4);
		$payment_percent  = round($now_order['payment_money']/$total_pay,4);
		$merchant_percent = round($now_order['merchant_balance']/$total_pay,4);
		$card_give_percent = round($now_order['card_give_money']/$total_pay,4);

		//线上支付退款
		//商家会员卡余额退款
		$need_pay_price = $total_pay/$now_order['num'];
		$need_pay_tmp = $total_pay-$need_pay_price*$unconsume_pass_num;
		$need_refund_tmp = $total_pay-$need_pay_tmp;

		if( $now_group['pin_num']>0&&!$now_order['single_buy']) {
			$now_group_start = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
		}

		if($now_order['merchant_balance'] != '0.00'){
			if( $now_group['pin_num']>0&&!$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status']!=2) {
				if(($now_group_start['status']<2&&$now_group['group_refund_fee']!=100&&!$now_order['is_head'])){
					$now_order['merchant_balance'] = $now_order['merchant_balance']/$now_order['num'] * $unconsume_pass_num- round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $merchant_percent, 2);
					$now_order['merchant_balance'] = $now_order['merchant_balance']>0?$now_order['merchant_balance']:0;
				}else{
					$this->error_tips(L('_B_MY_YOUCANTREFUND_'));
				}
			}elseif($now_group['group_share_num']>0 ) {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['merchant_balance'] = round($now_order['merchant_balance']/$now_order['num'] * $unconsume_pass_num * (1 - $now_group['group_refund_fee'] / 100) , 2);
				}
			}elseif($now_order['num']!=$unconsume_pass_num){
				if($now_order['merchant_balance']<=$need_pay_price*$unconsume_pass_num){
					$need_pay_tmp = $need_pay_price*$unconsume_pass_num-$now_order['merchant_balance'];
					$need_refund_tmp = 0;
				}else{
					$need_refund_tmp = $now_order['merchant_balance']-$need_pay_price*$unconsume_pass_num;
					$need_pay_tmp= 0;
				}
				$now_order['merchant_balance'] = $need_refund_tmp;
			}

			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],0,0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		if($now_order['card_give_money'] != '0.00'){
			if( $now_group['pin_num']>0&&!$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status']!=2) {

				if(($now_group_start['status']<2&&$now_group['group_refund_fee']!=100&&!$now_order['is_head'])){
					$now_order['card_give_money']  = $now_order['card_give_money']/$now_order['num'] * $unconsume_pass_num - round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $card_give_percent, 2);
					$now_order['card_give_money'] = $now_order['card_give_money']>0?$now_order['card_give_money']:0;
				}else{
					$this->error_tips(L('_B_MY_YOUCANTREFUND_'));
				}
			}elseif($now_group['group_share_num']>0 ) {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['card_give_money'] = round($now_order['merchant_balance']/$now_order['num'] * $unconsume_pass_num * (1 - $now_group['group_refund_fee'] / 100) , 2);
				}
			}elseif($now_order['num']!=$unconsume_pass_num){
				if($now_order['card_give_money']<=$need_pay_price*$unconsume_pass_num){
					$need_pay_tmp = $need_pay_price*$unconsume_pass_num-$now_order['card_give_money'];
					$need_refund_tmp = 0;
				}else{
					$need_refund_tmp = $now_order['card_give_money']-$need_pay_price*$unconsume_pass_num;
					$need_pay_tmp= 0;
				}
				$now_order['card_give_money'] = $need_refund_tmp;
			}

			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],0,$now_order['card_give_money'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			if( $now_group['pin_num']>0&&!$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status']!=2) {

				if(($now_group_start['status']<2&&$now_group['group_refund_fee']!=100&&!$now_order['is_head'])){
					$now_order['balance_pay'] =$now_order['balance_pay']/$now_order['num'] * $unconsume_pass_num- round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $balance_percent, 2);
					$now_order['balance_pay'] = $now_order['balance_pay']>0?$now_order['balance_pay']:0;
				}else{
					$this->error_tips(L('_B_MY_YOUCANTREFUND_'));
				}
			}elseif($now_group['group_share_num']>0 ) {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['balance_pay'] = round($now_order['balance_pay']/$now_order['num'] * $unconsume_pass_num* (1 - $now_group['group_refund_fee'] / 100) , 2);
				}
			}elseif($now_order['num']!=$unconsume_pass_num){
				if($need_pay_tmp>0){
					if($now_order['balance_pay']<=$need_pay_tmp){
						$need_pay_tmp = $need_pay_tmp - $now_order['balance_pay'];
						$need_refund_tmp = 0;
					}else{
						$need_refund_tmp =$now_order['balance_pay']-$need_pay_tmp;
						$need_pay_tmp=0;
					}
				}else{
					$need_refund_tmp = $now_order['balance_pay'];
					$need_pay_tmp= 0;
				}
				$now_order['balance_pay'] = $need_refund_tmp;
			}
			if($now_order['balance_pay']>0){

				$result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'订单退款 (订单号:'.$now_order['order_name'].')-4',0,0,0,'Order Cancellation (Order #'.$now_order['order_name'].')');
				$param = array('refund_time' => time());
				if($result['error_code']){
					$param['err_msg'] = $result['msg'];
				} else {
					$param['refund_id'] = $now_order['order_id'];
				}
				$data_group_order['order_id'] = $now_order['order_id'];
				$data_group_order['refund_detail'] = serialize($param);
				$result['error_code'] || $data_group_order['status'] = 3;
				D('Group_order')->data($data_group_order)->save();
				if ($result['error_code']) {
					$this->error_tips($result['msg']);
				}
				$go_refund_param['msg'] = '平台余额退款成功';
			}
		}

		//线上支付
		if($now_order['payment_money'] != '0.00'){
			if( $now_group['pin_num']>0&&!$now_order['single_buy'] && !empty($now_group_start) && $now_group_start['status']!=2 ) {
				if(($now_group_start['status']<2&&$now_group['group_refund_fee']!=100&&!$now_order['is_head'])){
					$now_order['payment_money'] =$now_order['payment_money']/$now_order['num'] * $unconsume_pass_num - round($now_order['total_money'] * $now_group['group_refund_fee'] / 100 * $payment_percent, 2);
					$now_order['payment_money'] = $now_order['payment_money']>0?$now_order['payment_money']:0;
				}else{
					$this->error_tips('您不能退款');
				}
			}elseif($now_group['group_share_num']>0 ) {
				if ($now_order['num'] > $unconsume_pass_num) {
					$now_order['payment_money'] = round($now_order['payment_money']/$now_order['num'] * $unconsume_pass_num * (1 - $now_group['group_refund_fee'] / 100), 2);
				}
			}elseif($now_order['num']!=$unconsume_pass_num){
				if($need_pay_tmp>0){
					if($now_order['payment_money']<=$need_pay_tmp){
						$need_pay_tmp = $need_pay_tmp - $now_order['payment_money'];
						$need_refund_tmp = 0;
					}else{
						$need_refund_tmp =$now_order['payment_money'] - $need_pay_tmp;
						$need_pay_tmp=0;
					}
				}else{
					$need_pay_tmp= 0;
					$need_refund_tmp = $now_order['payment_money'];
				}
			}else{
				$need_refund_tmp = $now_order['payment_money'];
			}
			if($now_order['is_own']){
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_order['mer_id']))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
				if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
					$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
					$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
					$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
					$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
					$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
					$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
					$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
					$pay_method['weixin']['config']['is_own'] = 1 ;
				}
			}else{
				$pay_method = D('Config')->get_pay_method(0,0,1);
			}
			if(empty($pay_method)){
				$this->error_tips(L('_B_MY_NOPAIMENTMETHOD_'));
			}
			if(empty($pay_method[$now_order['pay_type']])){
				$this->error_tips(L('_B_MY_CHANGEPAIMENT_'));
			}
			$pay_class_name = ucfirst($now_order['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result)){
				$this->error_tips(L('_B_MY_THISPAIMENTNOTOPEN_'));
			}
			$now_order['order_type'] = 'group';
			if(!empty($now_order['orderid'])){
				$now_order['order_id']=$now_order['orderid'];
			}
			if($now_order['is_mobile_pay']==3){
				$pay_method[$now_order['pay_type']]['config'] =array(
						'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
						'pay_weixin_key'=>$this->config['pay_wxapp_key'],
						'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
						'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
				);
			}
			$pay_class = new $pay_class_name($now_order,$need_refund_tmp,$now_order['pay_type'],$pay_method[$now_order['pay_type']]['config'],$this->user_session,$now_order['is_mobile_pay']);
			$go_refund_param = $pay_class->refund();

			$now_order['order_id'] = $_GET['order_id'];
			$data_group_order['order_id'] = $_GET['order_id'];
			$data_group_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$data_group_order['status'] = 3;
			}
			D('Group_order')->data($data_group_order)->save();
			if($data_group_order['status'] != 3){
				$this->error_tips($go_refund_param['msg']);
			}
		}

		//用户积分退款是回滚
		if ($now_order['score_used_count']>0&&$unconsume_pass_num==$now_order['num']) {
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_').$now_order['order_name'].' '.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_group_order['order_id'] = $now_order['order_id'];
			$data_group_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_group_order['status'] = 3;
			D('Group_order')->data($data_group_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] = $result['msg'];
		}

		//2015-12-9     退款时销量回滚  (多消费码且部分消费了)
		if($now_order['num']>$unconsume_pass_num) {
			$order_num = $now_order['num'];
			$now_order['num'] = $unconsume_pass_num;
			if ($now_group['pin_num'] > 0 && !$now_order['single_buy']) {
				$now_group_start = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
				if ($now_group_start&&$now_group_start['status'] == 1) {
					$refund_fee = round($now_group['group_refund_fee'] * $now_order['price'] * $unconsume_pass_num / 100, 2);
					$refund_money = round($total_pay + $now_order['score_deducte'] + $now_order['coupon_price'] - $now_order['price'] * $consume_num - $refund_fee, 2);
				}
			}elseif($now_group['group_share_num']>0){
				$refund_fee = round($now_group['group_refund_fee'] * $now_order['price'] * $unconsume_pass_num / 100, 2);
				$refund_money = round($total_pay + $now_order['score_deducte'] + $now_order['coupon_price'] - $now_order['price'] * $consume_num - $refund_fee, 2);
			}else{
				$refund_money =$need_refund_tmp;
				$refund_fee = 0;
			}
			$data_group_order['refund_money'] = $refund_money;
			$data_group_order['order_id'] = $now_order['order_id'];
			if($refund_fee>0){
				$data_group_order['refund_fee'] = $refund_fee;
			}
			if ($refund_money > 0) {
				$now_order['order_type'] = 'group';
				$now_order['refund'] = true;
				$now_order['refund_money'] = $refund_fee;
				D('Merchant_money_list')->add_money($now_order['mer_id'], '团购退款手续费', $now_order);
			}

			//退款 原来没有成团的拼团组人数减1
			if($now_group['pin_num']!=0){
				$now_group_start = D('Group_start')->get_group_start_by_order_id($now_order['order_id']);
				if($now_group_start['status']==0){
					D('Group_start')->buyer_refund_dec_by_orderid($now_order['order_id'],$now_group_start['id']);
				}
				if($order_num==$unconsume_pass_num){
					$data_group_order['status']=3;
				}
			}else{
				$data_group_order['status'] = 6;
			}
			D('Group_order')->data($data_group_order)->save();
		}else{
			$data_group_order['order_id'] = $_GET['order_id'];
			$data_group_order['refund_money'] = $total_pay;
			$data_group_order['refund_fee'] = 0;
			$data_group_order['status']=3;
			D('Group_order')->data($data_group_order)->save();
			$go_refund_param['msg'] = "退款成功！";
		}

		$update_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();

		if ($update_group['type'] == 3) {
			$sale_count = $update_group['sale_count'] - $now_order['num'];
			$sale_count = $sale_count > 0 ? $sale_count : 0;
			$update_group_data = array('sale_count' => $sale_count);
			if ($update_group['count_num'] > 0 && $sale_count < $update_group['count_num']) {
				$update_group_data['type'] = 1;
			}
			D('Group')->where(array('group_id' => $now_order['group_id']))->save($update_group_data);
		} else {
			//退款时销量回滚
			D('Group')->where(array('group_id' => $now_order['group_id'], 'sale_count' => array('egt', $now_order['num'])))->setDec('sale_count', $now_order['num']);
		}

		//酒店等其他业务增加库存
		if(!empty($trade_info)){
			$trade_info = unserialize($now_order['trade_info']);
			switch($trade_info['type']){
				case 'hotel':
					$where['mer_id']=$now_group['mer_id'];
					$where['cat_id']=$trade_info['cat_id'];
					$where['_string']="stock_day >=".$trade_info['dep_time'] .' AND stock_time <'.$trade_info['end_time'];
					M('Trade_hotel_sotck')->where($where)->setInc('stock',1);
					break;
			}
		}

		//短信提醒
		$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => 0, 'type' => 'group');
		if ($this->config['sms_group_cancel_order'] == 1 || $this->config['sms_group_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您购买 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED_');
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_group_cancel_order'] == 2 || $this->config['sms_group_cancel_order'] == 3) {
			$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $merchant['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = L('_B_MY_CUSTOMER_').'购买的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED2_');
			Sms::sendSms($sms_data);
		}
		D('Group_pass_relation')->change_refund_status($now_order['order_id']);
		$this->success_tips($go_refund_param['msg'],U('My/group_order',array('order_id'=>$_GET['order_id'])));
	}

	/*删除团购订单*/
	public function group_order_del(){
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}else if($now_order['paid']){
			$this->error_tips('当前订单已付款，不能删除。');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['status'] = 4;
		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
			//退款时销量回滚
			$now_group = D('Group')->where(array('group_id' => $now_order['group_id']))->find();
			if($now_group['stock_reduce_method']){
				D('Group')->where(array('group_id' => $now_order['group_id'], 'sale_count' => array('egt', $now_order['num'])))->setDec('sale_count', $now_order['num']);
			}

			$this->success_tips(L('_B_MY_DELACCESS_'),U('My/group_order_list'));
		}else{
			$this->error_tips(L('_B_MY_DELLOSE_'));
		}
	}

	//plat_order_refund

	public function plat_order_refund(){
		$business_type = $_GET['business_type'];
		$order_id = $_GET['order_id'];
		$table = ucfirst($business_type).'_order';
		$model = D($table);
		$now_order = $model->get_order_by_orderid($order_id);
		$param['business_id'] = $_GET['order_id'];
		$param['business_type'] = $business_type;
		$plat_order = D('Plat_order')->get_order_by_business_id($param);

		if (empty($now_order)||empty($plat_order)) {
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		$can_refund = $model->can_refund_status($now_order);
		if ($plat_order['paid'] != 1 || !$can_refund) {
			$this->error_tips(L('_B_MY_ORDERDEALING_'));
		}
		if (empty($plat_order['paid'])) {
			$this->error_tips(L('_B_MY_ORDERNOPAY_'));
		}
		$now_order['pay_order'] = $plat_order;

		$this->assign('now_order', $now_order);
		$this->display($business_type.'_order_refund');
	}

	public function plat_order_check_refund(){

		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$orderid = intval($_GET['order_id']);

		$now_order = M("Plat_order")->where(array('order_id' => $orderid))->find();
		$now_order['order_type'] = 'plat';
		$table = ucfirst($now_order['business_type']).'_order';
		$model =D($table);
		$business_order = $model->get_order_by_orderid($now_order['business_id']);

		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		$can_refund = $model->can_refund_status($business_order);
		if ($now_order['paid'] != 1 || !$can_refund) {
			$this->error_tips(L('_B_MY_ORDERDEALING_'));
		}

		if(empty($now_order['paid'])){
			$this->error_tips(L('_B_MY_ORDERNOPAY_'));
		}
		//dump($now_order);die;
		//$my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
		$refund_status =false;
		if($now_order['pay_money'] != '0.00'){
			if($now_order['is_own']){
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$business_order['mer_id']))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
				if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
					$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
					$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
					$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
					$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
					$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
					$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
					$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
					$pay_method['weixin']['config']['is_own'] = 1 ;
				}
			}else{
				$pay_method = D('Config')->get_pay_method(0,0,1);
			}

			if(empty($pay_method)){
				$this->error_tips(L('_B_MY_NOPAIMENTMETHOD_'));
			}
			if(empty($pay_method[$now_order['pay_type']])){
				$this->error_tips(L('_B_MY_CHANGEPAIMENT_'));
			}

			$pay_class_name = ucfirst($now_order['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result)){
				$this->error_tips(L('_B_MY_THISPAIMENTNOTOPEN_'));
			}

			$order_id = $now_order['order_id'];
			$now_order['order_id'] = $now_order['orderid'];
			if($now_order['is_mobile_pay']==3){
				$pay_method[$now_order['pay_type']]['config'] =array(
						'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
						'pay_weixin_key'=>$this->config['pay_wxapp_key'],
						'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
						'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
				);
				$is_mobile = 3;
			}else{
				$is_mobile = 1;
			}
			$pay_class = new $pay_class_name($now_order,$now_order['pay_money'],$now_order['pay_type'],$pay_method[$now_order['pay_type']]['config'],$this->user_session,$is_mobile);
			$go_refund_param = $pay_class->refund();

			$now_order['order_id'] = $orderid;
			$data_plat_order['order_id'] = $orderid;
			$data_plat_order['refund_detail'] = serialize($go_refund_param['refund_param']);
			if(empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok'){
				$refund_status = true;
			}else{
				$this->error_tips($go_refund_param['msg']);
			}
			D('Plat_order')->data($data_plat_order)->save();

		}elseif($now_order['pay_type'] == 'offline') {
			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize(array('refund_time'=>time()));
			D('Plat_order')->data($data_plat_order)->save();
		}

		//如果使用了积分 2016-1-15
		if ($now_order['system_score']!=='0') {

			$order_name=$now_order['order_name'];
			$result = D('User')->add_score($now_order['uid'],$now_order['system_score'],L('_B_MY_REFUND_').$order_name.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize($param);
			D('Plat_order')->data($data_plat_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}else{
				$refund_status = true;
			}
			$go_refund_param['msg'] .= $result['msg'];
		}

		//平台余额退款
		if($now_order['system_balance'] != '0.00'){
			$result = D('User')->add_money($now_order['uid'],$now_order['system_balance'],'订单退款 (订单号:'.$now_order['order_name'].')-5',0,0,0,'Order Cancellation (Order #'.$now_order['order_name'].')');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize($param);
			D('Plat_order')->data($data_plat_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}else{
				$refund_status = true;
			}
			$go_refund_param['msg'] = '平台余额退款成功';
		}

		//商家会员卡余额退款
		if($now_order['merchant_balance_pay'] != '0.00'||$now_order['merchant_balance_give']!='0.00'){
			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance_pay'],$now_order['merchant_balance_give'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_plat_order['order_id'] = $now_order['order_id'];
			$data_plat_order['refund_detail'] = serialize($param);
			D('Plat_order')->data($data_plat_order)->save();
			if ($result['error_code']) {
				$this->error_tips($result['msg']);
			}else{
				$refund_status = true;
			}
			$go_refund_param['msg'] = $result['msg'];
		}


		//if($refund_status){
			//调用 打印 发短信 发模板消息 回滚库存 回调跳转地址
			$refund_result = $model->afert_refund($business_order);
			if(!$refund_status['error_code']){
				if($now_order['pay_type']=='offline'){
					$this->success_tips(L('_B_MY_USEOFFLINECHANGEREFUND_'),$refund_result['url']);
				}else{
					$this->success_tips($go_refund_param['msg'],$refund_result['url']);
				}
			}else{
				$this->error_tips($refund_result['msg']);
			}
		//}

	}

	public function ajax_group_order_del(){
		$database_group_order = D('Group_order');
		$now_order = $database_group_order->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_NOORDER_'))));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_group_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELACCESS_'))));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_DELLOSE_'))));
		}
	}

	public function ajax_gift_order_del(){
		if(IS_AJAX){
			$order_id = $_GET['order_id'] + 0;
			$database_gift_order = D('Gift_order');
			$now_order = $database_gift_order->get_order_detail_by_id($this->user_session['uid'],$order_id);

			if(empty($now_order)){
				exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_NOORDER_！'))));
			}

			$order_condition['order_id'] = $now_order['order_id'];
			$data['is_del'] = 1;
			$data['del_time'] = time();
			if($database_gift_order->where($order_condition)->data($data)->save()){
				exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELACCESS_'))));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_DELLOSE_'))));
			}
		}else{
			$this->error_tips(L('_B_MY_PAGEWRONG_'));
		}
	}


	public function ajax_meal_order_del(){
		$database_meal_order = D('Meal_order');
		$now_order = $database_meal_order->get_order_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_NOORDER_！'))));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_meal_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELACCESS_'))));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_DELLOSE_'))));
		}
	}


	public function ajax_shop_order_del(){
		$database_shop_order = D('Shop_order');
		$now_order = $database_shop_order->get_order_by_id($this->user_session['uid'],intval($_GET['order_id']));

		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_NOORDER_！'))));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_shop_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELACCESS_'))));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_DELLOSE_'))));
		}
	}


	/*删除预约订单*/
	public function appoint_order_del(){
		$database_appoint_order = D('Appoint_order');
		$now_order = $database_appoint_order->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']));
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}else if($now_order['paid']){
			$this->error_tips('当前订单已付款，不能删除。');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['paid'] = 3;
		if($database_appoint_order->where($condition_group_order)->data($data_group_order)->save()){
			$this->success_tips(L('_B_MY_DELACCESS_'),U('My/appoint_order_list'));
		}else{
			$this->error_tips(L('_B_MY_DELLOSE_'));
		}
	}


	/*店铺收藏*/
	public function group_store_collect(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$this->assign(D('Merchant_store')->wap_get_store_collect_list($this->user_session['uid']));
		$this->display();
	}

	//手艺人收藏
	public function worker_collect(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$this->assign(D('Merchant_workers')->wap_get_worker_collect_list($this->user_session['uid']));
		$this->display();
	}

	/*商家收藏***商家中心暂时没有手机版***/
	public function merchant_collect(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$this->assign(D('Merchant')->get_collect_list($this->user_session['uid']));
		$this->display();
	}
	/*     * *图片上传** */

	public function ajaxImgUpload() {
		$mulu=isset($_GET['ml']) ? trim($_GET['ml']):'group';
		$mulu=!empty($mulu) ? $mulu : 'group';
		$filename = trim($_POST['filename']);
		$img = $_POST[$filename];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$imgdata = base64_decode($img);
		$img_order_id = sprintf("%09d",$this->user_session['uid']);
		$rand_num = mt_rand(10,99).'/'.substr($img_order_id,0,3).'/'.substr($img_order_id,3,3).'/'.substr($img_order_id,6,3);
		$getupload_dir = "/upload/reply/".$mulu."/" .$rand_num;

		$upload_dir = "." . $getupload_dir;
		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
        $newfilename = $mulu.'_' . date('YmdHis') . '.jpg';
        $save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
        $save = file_put_contents($upload_dir . '/m_' . $newfilename, $imgdata);
        $save = file_put_contents($upload_dir . '/s_' . $newfilename, $imgdata);
		if ($save) {
			$this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
		} else {
			$this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => L('_B_MY_SAVELOSE_'))));
		}
	}
	/*团购评价*/
	public function group_feedback(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$now_order = D('Group_order')->get_order_detail_by_id($this->user_session['uid'],intval($_GET['order_id']),true);
		$this->assign('now_order',$now_order);
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if(empty($now_order['paid'])){
			$this->error_tips(L('_B_MY_NOTCONSUMENOCOMMENT_'));
		}
		if(empty($now_order['status'])){
			$this->error_tips('当前订单未消费！无法评论');
		}
		if($now_order['status'] == 2){
			$this->error_tips(L('_B_MY_HAVECOMMENTD_'));
		}
		if(IS_POST){
			$score = intval($_POST['score']);
			if($score > 5 || $score < 1){
				$this->error_tips(L('_B_MY_ONLY1-5_'));
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$pic_ids=array();
			if(!empty($inputimg)){
				$database_reply_pic = D('Reply_pic');
				foreach($inputimg as $imgv){
					$imgv=str_replace('/upload/reply/group/','',$imgv);
					$imgtmp=explode('/',$imgv);
					$imgname=$imgtmp[count($imgtmp)-1];
					$reply_pic['name'] = $imgname;
					$reply_pic['pic'] = str_replace('/'.$imgname,'',$imgv).','.$imgname;
					$reply_pic['uid'] = $this->user_session['uid'];
					$reply_pic['order_type'] = '0';
					$reply_pic['order_id'] = intval($now_order['order_id']);
					$reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
					$pic_ids[] = $database_reply_pic->data($reply_pic)->add();
				}
			}
			$database_reply = D('Reply');
			$data_reply['parent_id'] = $now_order['group_id'];
			$data_reply['store_id'] = $now_order['store_id'];
			$data_reply['mer_id'] = $now_order['mer_id'];
			$data_reply['score'] = $score;
			$data_reply['order_type'] = '0';
			$data_reply['order_id'] = intval($now_order['order_id']);
			$data_reply['anonymous'] = intval($_POST['anonymous']);
			$data_reply['comment'] = $_POST['comment'];
			$data_reply['uid'] = $this->user_session['uid'];
			$data_reply['pic'] = !empty($pic_ids) ? implode(',',$pic_ids):'';
			$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_reply['add_ip'] = get_client_ip(1);
			if($database_reply->data($data_reply)->add()){
				D('Group')->setInc_group_reply($now_order,$score);
				D('Group_order')->change_status($now_order['order_id'],2);
				$database_merchant_score = D('Merchant_score');
				$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
				if(empty($now_merchant_score)){
					$data_merchant_score['parent_id'] = $now_order['mer_id'];
					$data_merchant_score['type'] = '1';
					$data_merchant_score['score_all'] = $score;
					$data_merchant_score['reply_count'] = 1;
					$database_merchant_score->data($data_merchant_score)->add();
				}else{
					$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
					$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
				}
				$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
				if(empty($now_store_score)){
					$data_store_score['parent_id'] = $now_order['store_id'];
					$data_store_score['type'] = '2';
					$data_store_score['score_all'] = $score;
					$data_store_score['reply_count'] = 1;
					$database_merchant_score->data($data_store_score)->add();
				}else{
					$data_store_score['score_all'] = $now_store_score['score_all']+$score;
					$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
				}
				if($this->config['feedback_score_add']>0){
				  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['meal_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].'个'.$this->config['score_name']);

					D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],L('_B_MY_USER_').$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'对'.$this->config['meal_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].'个'.$this->config['score_name']);
				}

				$this->success_tips(L('_B_MY_COMMENTACCESS_'),U('My/group_order',array('order_id'=>$now_order['order_id'])));
			}else{
				$this->error_tips(L('_B_MY_COMMENTLOSE_'));
			}
		}
		$this->display();
	}
	/*订餐OR外卖评价*/
	public function meal_feedback(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$now_order = D('Meal_order')->where(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])))->find();
		$this->assign('now_order',$now_order);
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if(empty($now_order['paid'])){
			$this->error_tips(L('_B_MY_NOTCONSUMENOCOMMENT_'));
		}
		if(empty($now_order['status'])){
			$this->error_tips('当前订单未消费！无法评论');
		}
		if($now_order['status'] == 2){
			$this->error_tips(L('_B_MY_HAVECOMMENTD_'));
		}
		if(IS_POST){
			$score = intval($_POST['score']);
			if($score > 5 || $score < 1){
				$this->error_tips(L('_B_MY_ONLY1-5_'));
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$pic_ids=array();
			if(!empty($inputimg)){
				$database_reply_pic = D('Reply_pic');
				foreach($inputimg as $imgv){
					$imgv=str_replace('/upload/reply/meal/','',$imgv);
					$imgtmp=explode('/',$imgv);
					$imgname=$imgtmp[count($imgtmp)-1];
					$reply_pic['name'] = $imgname;
					$reply_pic['pic'] = str_replace('/'.$imgname,'',$imgv).','.$imgname;
					$reply_pic['uid'] = $this->user_session['uid'];
					$reply_pic['order_type'] = '1';
					$reply_pic['order_id'] = intval($now_order['order_id']);
					$reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
					$pic_ids[] = $database_reply_pic->data($reply_pic)->add();
				}
			}
			$database_reply = D('Reply');
			$data_reply['parent_id'] = $now_order['store_id'];
			$data_reply['store_id'] = $now_order['store_id'];
			$data_reply['mer_id'] = $now_order['mer_id'];
			$data_reply['score'] = $score;
			$data_reply['order_type'] = '1';
			$data_reply['order_id'] = intval($now_order['order_id']);
			$data_reply['anonymous'] = intval($_POST['anonymous']);
			$data_reply['comment'] = $_POST['comment'];
			$data_reply['uid'] = $this->user_session['uid'];
			$data_reply['pic'] = !empty($pic_ids) ? implode(',',$pic_ids):'';
			$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_reply['add_ip'] = get_client_ip(1);
			if ($database_reply->data($data_reply)->add()) {
				D('Merchant_store')->setInc_meal_reply($now_order['store_id'],$score);
				D('Meal_order')->change_status($now_order['order_id'],2);

				$database_merchant_score = D('Merchant_score');
				$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
				if(empty($now_merchant_score)){
					$data_merchant_score['parent_id'] = $now_order['mer_id'];
					$data_merchant_score['type'] = '1';
					$data_merchant_score['score_all'] = $score;
					$data_merchant_score['reply_count'] = 1;
					$database_merchant_score->data($data_merchant_score)->add();
				}else{
					$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
					$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
				}
				$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
				if(empty($now_store_score)){
					$data_store_score['parent_id'] = $now_order['store_id'];
					$data_store_score['type'] = '2';
					$data_store_score['score_all'] = $score;
					$data_store_score['reply_count'] = 1;
					$database_merchant_score->data($data_store_score)->add();
				}else{
					$data_store_score['score_all'] = $now_store_score['score_all']+$score;
					$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
				}

				if ($now_order['meal_type'] == 1) {
					if($this->config['feedback_score_add']>0){
					  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['group_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].$this->config['score_name']);
					  	D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],L('_B_MY_USER_').$this->user_session['nickname'].date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).L('_B_MY_COMMENT_').$this->config['group_alias_name'].L('_B_MY_GET_').$this->config['feedback_score_add'].$this->config['score_name']);
					}
					$this->success_tips(L('_B_MY_COMMENTACCESS_'), U('Takeout/order_detail', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				} else {
					if($this->config['feedback_score_add']>0){
					  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['group_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].$this->config['score_name']);

				  		D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],L('_B_MY_USER_').$this->user_session['nickname'].date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).L('_B_MY_COMMENT_').$this->config['group_alias_name'].L('_B_MY_GET_').$this->config['feedback_score_add'].$this->config['score_name']);
					}
					$this->success_tips(L('_B_MY_COMMENTACCESS_'), U('Food/order_detail', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
				}
			} else{
				$this->error_tips(L('_B_MY_COMMENTLOSE_'));
			}
		}
		$this->display();
	}


	/*预约评论*/
	public function appoint_feedback(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$order_id = $_GET['order_id'] + 0;
		$now_order = D('Appoint_order')->where(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])))->find();
		$this->assign('now_order',$now_order);
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}

		if($now_order['type'] == 0){
			if(empty($now_order['service_status'])){
				$this->error_tips(L('_B_MY_NOTCONSUMENOCOMMENT_'));
			}
			if(empty($now_order['service_status'])){
				$this->error_tips('当前订单未消费！无法评论');
			}
		}


		$where['order_id'] = $order_id;
		$database_appoint_comment = D('Appoint_comment');
		$appoint_order_num = $database_appoint_comment->where($where)->count();
		if($appoint_order_num > 0){
			$this->error_tips(L('_B_MY_HAVECOMMENTD_'));
		}

		if(IS_POST){
			$score = intval($_POST['score']);
			$profession_total_score = $_POST['profession_score'] + 0;
			$communicate_total_score = $_POST['communicate_score'] + 0;
			$speed_total_score = $_POST['speed_score'] + 0;

			if($score > 5 || $score < 1 || $profession_total_score > 5 || $profession_total_score < 1 || $communicate_total_score > 5 || $communicate_total_score < 1|| $speed_total_score > 5 || $speed_total_score < 1 ){
				$this->error_tips(L('_B_MY_ONLY1-5_'));
			}
			$inputimg=isset($_POST['inputimg']) ? $_POST['inputimg'] :'';
			$pic_ids=array();
			if(!empty($inputimg)){
				$database_reply_pic = D('Reply_pic');
				foreach($inputimg as $imgv){
					$imgv=str_replace('/upload/reply/appoint/','',$imgv);
					$imgtmp=explode('/',$imgv);
					$imgname=$imgtmp[count($imgtmp)-1];
					$reply_pic['name'] = $imgname;
					$reply_pic['pic'] = str_replace('/'.$imgname,'',$imgv).','.$imgname;
					$reply_pic['uid'] = $this->user_session['uid'];
					$reply_pic['order_type'] = '2';
					$reply_pic['order_id'] = intval($now_order['order_id']);
					$reply_pic['add_time'] = $_SERVER['REQUEST_TIME'];
					$pic_ids[] = $database_reply_pic->data($reply_pic)->add();
				}
			}
			$database_reply = D('Reply');
			$data_reply['parent_id'] = $now_order['appoint_id'];
			$data_reply['store_id'] = $now_order['store_id'];
			$data_reply['mer_id'] = $now_order['mer_id'];
			$data_reply['score'] = $score;
			$data_reply['order_type'] = '2';
			$data_reply['order_id'] = intval($now_order['order_id']);
			$data_reply['anonymous'] = intval($_POST['anonymous']);
			$data_reply['comment'] = $_POST['comment'];
			$data_reply['uid'] = $this->user_session['uid'];
			$data_reply['pic'] = !empty($pic_ids) ? implode(',',$pic_ids):'';
			$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
			$data_reply['add_ip'] = get_client_ip(1);
			if ($database_reply->data($data_reply)->add()) {
				D('Appoint')->setInc_appoint_reply($now_order, $score);
				D('Appoint_order')->change_status($now_order['order_id'], 2);

				$database_merchant_score = D('Merchant_score');
				$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
				if(empty($now_merchant_score)){
					$data_merchant_score['parent_id'] = $now_order['mer_id'];
					$data_merchant_score['type'] = '1';
					$data_merchant_score['score_all'] = $score;
					$data_merchant_score['reply_count'] = 1;
					$database_merchant_score->data($data_merchant_score)->add();
				}else{
					$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
					$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
				}
				$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
				if(empty($now_store_score)){
					$data_store_score['parent_id'] = $now_order['store_id'];
					$data_store_score['type'] = '2';
					$data_store_score['score_all'] = $score;
					$data_store_score['reply_count'] = 1;
					$database_merchant_score->data($data_store_score)->add();
				}else{
					$data_store_score['score_all'] = $now_store_score['score_all']+$score;
					$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
					$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
				}

				//工作人员评分start
				$database_merchant_workers = D('Merchant_workers');
				$database_appoint_visit_order_info = D('Appoint_visit_order_info');
				$database_appoint_supply = D('Appoint_supply');
				$Map['appoint_order_id'] = $now_order['order_id'];
				$appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();

				if(!$appoint_visit_order_info){
					$Map['order_id'] = $now_order['order_id'];
					$appoint_visit_order_info = $database_appoint_supply->where($Map)->find();
				}

				if($appoint_visit_order_info){
					$_Map['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'] > 0 ? $appoint_visit_order_info['merchant_worker_id'] : $appoint_visit_order_info['worker_id'];
					$merchant_workers_info = $database_merchant_workers->appoint_worker_info($_Map);
					$profession_total_score = $merchant_workers_info['profession_total_score'];
					$communicate_total_score = $merchant_workers_info['communicate_total_score'];
					$speed_total_score = $merchant_workers_info['speed_total_score'];
					$profession_num = $merchant_workers_info['profession_num'];
					$communicate_num = $merchant_workers_info['communicate_num'];
					$speed_num = $merchant_workers_info['speed_num'];

					if($merchant_workers_info){
						$profession_total_score += $_POST['profession_score'] + 0;
						$communicate_total_score += $_POST['communicate_score'] + 0;
						$speed_total_score += $_POST['speed_score'] + 0;
						$profession_num++;
						$communicate_num++;
						$speed_num++;

						$merchant_workers_data['profession_total_score'] = $profession_total_score;
						$merchant_workers_data['communicate_total_score'] = $communicate_total_score;
						$merchant_workers_data['speed_total_score'] = $speed_total_score;
						$merchant_workers_data['profession_num'] = $profession_num;
						$merchant_workers_data['communicate_num'] = $communicate_num;
						$merchant_workers_data['speed_num'] = $speed_num;
						$merchant_workers_data['profession_avg_score'] = $profession_total_score/$profession_num;
						$merchant_workers_data['communicate_avg_score'] = $communicate_total_score/$communicate_num;
						$merchant_workers_data['speed_avg_score'] = $speed_total_score/$speed_num;
						$merchant_workers_data['all_avg_score'] = ($merchant_workers_data['profession_avg_score'] + $merchant_workers_data['communicate_avg_score'] + $merchant_workers_data['speed_avg_score']) / 3;
						$merchant_workers_data['mer_id'] =  $now_order['mer_id'];
						$result = $database_merchant_workers->where($_Map)->data($merchant_workers_data)->save();
						if(!$result){
							$this->error_tips(L('_B_MY_WORKCOMMENTLOSE_'));
						}

						$database_appoint_comment = D('Appoint_comment');
						$_data['uid'] = $this->user_session['uid'];
						$_data['merchant_worker_id'] =  $appoint_visit_order_info['merchant_worker_id'] > 0 ? $appoint_visit_order_info['merchant_worker_id'] : $appoint_visit_order_info['worker_id'];
						$_data['appoint_id'] = $now_order['appoint_id'];
						$_data['profession_score'] = $_POST['profession_score'];
						$_data['communicate_score'] = $_POST['communicate_score'];
						$_data['speed_score'] = $_POST['speed_score'];
						if($inputimg){
							$_data['comment_img'] = serialize($inputimg);
						}
						$_data['content'] = $_POST['comment'];
						$_data['add_time'] = time();
						$_data['order_id'] = $now_order['order_id'];
						$_data['mer_id'] = $now_order['mer_id'];

						if($database_appoint_comment->data($_data)->add()){

							$worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'] > 0 ?$appoint_visit_order_info['merchant_worker_id'] : $appoint_visit_order_info['worker_id'];
							$database_merchant_workers->where($worker_where)->setInc('comment_num');
							$database_appoint = D('Appoint');
							$database_appoint->where(array('appoint_id'=>$now_order['appoint_id']))->setInc('comment_num');
						}
					}
				}
				//工作人员评分end
				if($this->config['feedback_score_add']>0){
				  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['appoint_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].$this->config['score_name']);
			  		D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],L('_B_MY_USER_').$this->user_session['nickname'].date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).L('_B_MY_COMMENT_').$this->config['appoint_alias_name'].L('_B_MY_GET_').$this->config['feedback_score_add'].$this->config['score_name']);
				}
				$this->success_tips(L('_B_MY_COMMENTACCESS_'), U('My/appoint_order', array('order_id' => $now_order['order_id'])));
			} else{
				$this->error_tips(L('_B_MY_COMMENTLOSE_'));
			}
		}
		$this->display();
	}


	/*全部订餐订单列表*/
	public function meal_order_list(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$where = " uid={$this->user_session['uid']} AND status<=3";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
		if ($status == -1) {
			$where .= " AND paid=0";
			$where['paid'] = 0;
		} elseif ($status == 1) {
			$where .= " AND paid=1 AND status=0";
		} elseif ($status == 2) {
			$where .= " AND paid=1 AND status=1";
		}
// 		$status == -1 && $where['paid'] = 0;
// 		$status == 1 && $where['status'] = 0;
// 		$status == 2 && $where['status'] = 1;

 		$where .= " AND is_del = 0";
		$order_list = D("Meal_order")->field(true)->where($where)->order('order_id DESC')->select();
		//$temp = $store_ids = array();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}

		foreach($list as $key=>$val){
			$list[$key]['order_url'] = U('Meal/order_detail', array('mer_id' => $val['mer_id'], 'store_id' => $val['store_id'], 'order_id' => $val['order_id']));
		}

		$this->assign('order_list', $list);

		$this->display();
	}

	public function ajax_meal_order_list(){
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$where = " uid={$this->user_session['uid']} AND status<=3";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
		if ($status == -1) {
			$where .= " AND paid=0";
			$where['paid'] = 0;
		} elseif ($status == 1) {
			$where .= " AND paid=1 AND status=0";
		} elseif ($status == 2) {
			$where .= " AND paid=1 AND status=1";
		}
// 		$status == -1 && $where['paid'] = 0;
// 		$status == 1 && $where['status'] = 0;
// 		$status == 2 && $where['status'] = 1;
 		$where .= " AND is_del = 0";
		$order_list = D("Meal_order")->field(true)->where($where)->order('order_id DESC')->select();
		//$temp = $store_ids = array();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}


		foreach($list as $key=>$val){
			$list[$key]['order_url'] = U('meal/order_detail', array('mer_id' => $val['mer_id'], 'store_id' => $val['store_id'], 'order_id' => $val['order_id']));
		}

		if(!empty($list)){
			exit(json_encode(array('status'=>1,'order_list'=>$list)));
		}else{
			exit(json_encode(array('status'=>0,'order_list'=>$list)));
		}
	}

	/*网页加载---订餐订单列表 默认显示 upcoming的列表*/
	public function shop_order_list(){
//        $list = array();
//        $list = $this->SHARE_shop_order_list();
//		$this->assign('order_list', $list);
        $this->assign('param', $_GET);
		$this->display();
	}
    /*Ajax加载——订餐订单列表 默认显示 upcoming的列表*/
	public function ajax_shop_order_list(){

        $list = array();
        $list = $this->SHARE_shop_order_list();
		if(!empty($list)){
			exit(json_encode(array('status'=>1,'order_list'=>$list)));
		}else{
			exit(json_encode(array('status'=>0,'order_list'=>$list)));
		}
	}
    //peter
    //网页和Ajax使用通用的订单列表加载程序
	public function SHARE_shop_order_list(){

        if(empty($this->user_session)){
            $this->error_tips(L('_B_MY_LOGINFIRST_'));
        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : 3;
        $where = "is_del=0 AND uid={$this->user_session['uid']}";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
        if(!empty($_GET['store_id'])){
            $where .= " AND store_id=".intval($_GET['store_id']);
        }

        switch ($status){
            case 0:
                $where .= " AND paid=0 AND status<4";
                break;
            case 1:
                $where .= " AND paid=1 AND status=2";
                break;
            case 2:
                $where .= " AND paid=1 AND (status=4 OR status=5)";
                break;
            case 3:
                $where .= " AND status<2";
                break;
            case 4:
                $where .= " AND status=2";
                break;
            case 5:
                $where .= " AND status=3";
                break;
            case 6:
                $where .= " AND paid=1 AND (status=2 OR status=3 OR status=4 OR status=5)";
                break;
            default://付款超时，待删除
                $where .= " AND status<>6";
                break;
        }

        $order_list = D("Shop_order")->field(true)->where($where)->order('order_id DESC')->select();
        foreach ($order_list as &$st) {
            $st['real_total_price']=$st['price']+$st['tip_charge']-$st['merchant_reduce']-$st['delivery_discount']-$st['coupon_price'];
            $score=D('Reply')->field("score")->where(array('order_id'=>$st['order_id']))->find();
            if ($score['score']==null) {
                $st['rate_score'] = 0;
            }else{
                $st['rate_score'] = $score['score'];
            }
            $store_ids[] = $st['store_id'];
        }

        $m = array();
        if ($store_ids) {
            $store_image_class = new store_image();

            $merchant_list = D('Merchant_store_shop')->field('store_shop.background,store.*')->join('as store_shop left join '.C('DB_PREFIX').'merchant_store store ON store_shop.store_id = store.store_id')->where(array('store_shop.store_id'=>array('in', $store_ids)))->select();
            //$merchant_list = M('Merchant_store_shop')->join('as store_shop left join '.C('DB_PREFIX').'merchant_store store ON store_shop.store_id = store.store_id')->where(array('store_shop.store_id'=>array('in', $store_ids)))->select();
            //$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
            //var_dump($merchant_list);die();
            //$merchant_shop_list = D("Merchant_store_shop")->where(array('store_id' => array('in', $store_ids)))->select();
            foreach ($merchant_list as $li) {
                //$images = $store_image_class->get_allImage_by_path($li['background_']);
                $image_tmp = explode(',', $li['background']);
                $li['image'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
                //$li['image'] = $images ? array_shift($images) : array();
                unset($li['status']);
                $city = D('Area')->where(array('area_id'=>$li['city_id']))->find();
                $li['jetlag'] = $city['jetlag'];
                $m[$li['store_id']] = $li;
            }
        }

        $list = array();
        foreach ($order_list as $ol) {
            if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
                $list[] = array_merge($ol, $m[$ol['store_id']]);
            } else {
                $list[] = $ol;
            }
        }

        foreach($list as $key=>$val){

            $list[$key]['name'] = lang_substr($val['name'],C('DEFAULT_LANG'));
            $list[$key]['order_url'] = U('Shop/order_detail', array('order_id' => $val['order_id']));
            if($val['pay_time']==0) {
                $list[$key]['create_time_show'] = date('Y-m-d h:i', $val['create_time']);
            }else{
                $list[$key]['create_time_show'] = date('Y-m-d h:i', $val['pay_time']);
            }

            ///$list[$key]['create_time_show'] = date('Y-m-d h:i:s',time());
            ///$list[$key]['create_time_show'] = date_default_timezone_get();
            //------------------------------ 更新status等信息 ------------------------------------peter

            $status = D('Shop_order_log')->field(true)->where(array('order_id' => $val['order_id']))->order('id DESC')->find();
            $status['status'] = $status['status'] == 33 ? 2 : $status['status'];
            $list[$key]['statusLog'] = $status['status'];
            $list[$key]['statusLogName'] = D('Store')->getOrderStatusLogName($status['status'],$val['order_type']);

            //-------------------------------------------------------------------------------------

            $supply = D('Deliver_supply')->where(array('order_id'=>$val['order_id']))->find();
            if($supply['status'] > 1 && $supply['status'] < 5){
                $t_deliver = D('Deliver_user')->field(true)->where(array('uid'=>$supply['uid']))->find();
                $list[$key]['deliver'] = $t_deliver;
            }
        }
        return $list;
    }
    //个人中心-优惠券
    public function coupon(){
        $coupon_list = D('System_coupon')->get_user_coupon_list($this->user_session['uid'], $this->user_session['phone']);
        //var_dump($coupon_list);die();
        $this->assign('cate_platform', D('System_coupon')->cate_platform());

        // var_dump(D('System_coupon')->cate_platform());die();

        //获取活动优惠券
        $event_coupon_list = D('New_event')->getUserCoupon($this->user_session['uid']);
        if(!$coupon_list) $coupon_list = array();
        if(count($event_coupon_list) > 0){
            $coupon_list = array_merge($coupon_list,$event_coupon_list);
        }

        $tmp = array();
        foreach ($coupon_list as $key => &$v) {
            $v['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            $v['des'] = lang_substr($v['des'],C('DEFAULT_LANG'));
            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
            } else {
                $tmp[$v['is_use']][$v['coupon_id']] = $v;
                $mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
                $tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
                switch($v['type']){
                    case 'all':
                        $url = $this->config['site_url'].'/wap.php';
                        break;
                    case 'group':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Group&a=index';
                        break;
                    case 'meal':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Meal_list&a=index';
                        break;
                    case 'appoint':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Appoint&a=index';
                        break;
                    case 'shop':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Shop&a=index';
                        break;
                }
                $tmp[$v['is_use']][$v['coupon_id']]['url'] = $url;
            }
        }
        if($tmp[0]){
            //$this->assign('coupon_list', array_shift($tmp[0]));
            $this->assign('coupon_list', $tmp[0]);

        }
        //$this->assign('coupon_list', $coupon_list);
        //$this->card_list();
        $this->assign('back_url', U("My/index"));
        $this->display();
    }

    /*优惠券列表*/
	public function card_list(){
		// if(!$this->is_wexin_browser){
		// $this->error_tips('请使用微信浏览优惠券！');
		// }
		$use = empty($_GET['use']) ? '0' : $_GET['use'];
        $use = 0;
		if($use == 0){
		    $title = 'Available';
		    $class_name = 'Muse';
        }else{
		    $title = 'History';
            $class_name = 'Expired';
        }

        $this->assign('title',$title);
		$this->assign('className',$class_name);

		if($_GET['coupon_type']=='mer') {
			$coupon_list = D('Card_new_coupon')->get_user_all_coupon_list($this->user_session['uid']);
			$this->assign('cate_platform', D('Card_new_coupon')->cate_platform());
		}else{
			$coupon_list=array();

			$coupon_list = D('System_coupon')->get_user_coupon_list($this->user_session['uid'], $this->user_session['phone']);

			$this->assign('cate_platform', D('System_coupon')->cate_platform());

            //获取活动优惠券
            $event_coupon_list = D('New_event')->getUserCoupon($this->user_session['uid']);
            if(!$coupon_list) $coupon_list = array();
            if(count($event_coupon_list) > 0){
                $coupon_list = array_merge($coupon_list,$event_coupon_list);
            }
            //var_dump($coupon_list);die();
		}

		$tmp = array();
		foreach ($coupon_list as $key => $v) {
		    $v['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
		    $v['des'] = lang_substr($v['des'],C('DEFAULT_LANG'));
			if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
				$tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
			} else {
				$tmp[$v['is_use']][$v['coupon_id']] = $v;
				$mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
				$tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
				$tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
				switch($v['type']){
                    case 'all':
                        $url = $this->config['site_url'].'/wap.php';
                        break;
                    case 'group':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Group&a=index';
                        break;
                    case 'meal':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Meal_list&a=index';
                        break;
                    case 'appoint':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Appoint&a=index';
                        break;
                    case 'shop':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Shop&a=index';
                        break;
                }
				$tmp[$v['is_use']][$v['coupon_id']]['url'] = $url;
			}

		}
		//var_dump($tmp);
		if($use == 0)
		    $this->assign('coupon_list', $tmp[0]);
		else
            $this->assign('coupon_list', array_merge($tmp[1],$tmp[2]));
		$this->display();
	}

    public function card_list_with_use(){
        // if(!$this->is_wexin_browser){
        // $this->error_tips('请使用微信浏览优惠券！');
        // }
        $use = 0;
        if($use == 0){
            $title = 'Available';
            $class_name = 'Muse';
        }else{
            $title = 'History';
            $class_name = 'Expired';
        }

        if($_GET['coupon_type']=='mer') {
            $coupon_list = D('Card_new_coupon')->get_user_all_coupon_list($this->user_session['uid']);
            $this->assign('cate_platform', D('Card_new_coupon')->cate_platform());
        }else{
            $coupon_list=array();

            $coupon_list = D('System_coupon')->get_user_coupon_list($this->user_session['uid'], $this->user_session['phone']);

            $this->assign('cate_platform', D('System_coupon')->cate_platform());

            //获取活动优惠券
            $event_coupon_list = D('New_event')->getUserCoupon($this->user_session['uid']);
            if(!$coupon_list) $coupon_list = array();
            if(count($event_coupon_list) > 0){
                $coupon_list = array_merge($coupon_list,$event_coupon_list);
            }
            //var_dump($coupon_list);die();
        }


        $tmp = array();
        foreach ($coupon_list as $key => $v) {
            $v['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            $v['des'] = lang_substr($v['des'],C('DEFAULT_LANG'));
            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
            } else {
                $tmp[$v['is_use']][$v['coupon_id']] = $v;
                $mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
                $tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
                switch($v['type']){
                    case 'all':
                        $url = $this->config['site_url'].'/wap.php';
                        break;
                    case 'group':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Group&a=index';
                        break;
                    case 'meal':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Meal_list&a=index';
                        break;
                    case 'appoint':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Appoint&a=index';
                        break;
                    case 'shop':
                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Shop&a=index';
                        break;
                }
                $tmp[$v['is_use']][$v['coupon_id']]['url'] = $url;
            }

        }
        //var_dump($tmp);
        if($use == 0)
            $this->assign('coupon_list', $tmp[0]);
        else
            $this->assign('coupon_list', array_merge($tmp[1],$tmp[2]));

    }


	public function cards()
	{
//		$card_list = D('Member_card_set')->get_all_card($this->user_session['uid']);
//		$this->assign('card_list',$card_list);
		//新商家会员卡
		$uid = $this->user_session['uid'];

		$card_list = D('Card_new')->get_user_all_card($uid);
		$this->assign('card_list',$card_list);

		$this->display('card_new');
	}



	public function order_list()
	{
		$type = isset($_GET['type']) ? intval($_GET['type']) : 1 ;
		if ($type == 1) {
			$order_list = D('Group')->wap_get_order_list($this->user_session['uid']);
			$this->assign('order_list',$order_list);
		} else {
			$where = array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
			$order_list = D("Meal_order")->field(true)->where($where)->order('order_id DESC')->select();
			$temp = $store_ids = array();
			foreach ($order_list as $st) {
				$store_ids[] = $st['store_id'];
			}
			$m = array();
			if ($store_ids) {
				$store_image_class = new store_image();
				$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
				foreach ($merchant_list as $li) {
					$images = $store_image_class->get_allImage_by_path($li['pic_info']);
					$li['image'] = $images ? array_shift($images) : array();
					unset($li['status']);
					$m[$li['store_id']] = $li;
				}
			}
			$list = array();
			foreach ($order_list as $ol) {
				if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
					$list[] = array_merge($ol, $m[$ol['store_id']]);
				} else {
					$list[] = $ol;
				}
			}
			$this->assign('order_list', $list);
		}
		$this->assign('type', $type);
		$this->display();
	}
	public function join_activity(){
		$uid = $this->user_session['uid'];
		import('@.ORG.wap_group_page');
		$tp_count = D('')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m'))->where("`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `eal`.`mer_id`=`m`.`mer_id` AND `ear`.`uid`='$uid'")->group('`eal`.`pigcms_id`')->count();
		$P = new Page($tp_count,20,'page');
		$order_list = D('')->field('`eal`.`name` AS `product_name`,`m`.`name` AS `merchant_name`,`eal`.*,`m`.*')->table(array(C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'merchant'=>'m'))->where("`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `eal`.`mer_id`=`m`.`mer_id` AND `ear`.`uid`='$uid'")->group('`eal`.`pigcms_id`')->order('`eal`.`pigcms_id` DESC')->limit($P->firstRow.','.$P->listRows)->select();
		// dump($order_list);
		if($order_list){
			$extension_image_class = new extension_image();
			foreach($order_list as &$value){
				$value['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$value['pic'])),'s');
				$value['url'] = U('My/join_activity_detail',array('id'=>$value['pigcms_id']));
				$value['money'] = floatval($value['money']);
				$value['type_txt'] = $this->activity_type_txt($value['type']);
			}
		}
		$this->assign('order_list',$order_list);
		$this->assign('pagebar',$P->show());
		$this->display();
	}
	public function join_activity_detail(){
		$condition_extension_activity_list['pigcms_id'] = $_GET['id'];
		$now_activity = D('Extension_activity_list')->field(true)->where($condition_extension_activity_list)->find();
		if(empty($now_activity)){
			$this->error_tips('该活动不存在');
		}
		$now_activity['type_txt'] = $this->activity_type_txt($now_activity['type']);
		$extension_image_class = new extension_image();
		$now_activity['list_pic'] = $extension_image_class->get_image_by_path(array_shift(explode(';',$now_activity['pic'])),'s');
		$now_activity['url'] = U('Wapactivity/detail',array('id'=>$now_activity['pigcms_id']));
		$now_activity['money'] = floatval($now_activity['money']);

		//活动归属的商家信息
		$now_merchant = D('Merchant')->field(true)->where(array('mer_id'=>$now_activity['mer_id']))->find();

		$record_list = D('Extension_activity_record')->field(true)->where(array('activity_list_id'=>$now_activity['pigcms_id'],'uid'=>$this->user_session['uid']))->order('`pigcms_id` DESC')->select();
		if(empty($record_list)){
			$this->error_tips('您未参与该活动');
		}
		$record_id_arr = array();
		foreach($record_list as $value){
			$record_id_arr[] = $value['pigcms_id'];
		}
		if($now_activity['type'] == 1){
			$number_list = D('Extension_yiyuanduobao_record')->field('`number`')->where(array('record_id'=>array('in',$record_id_arr)))->select();
			// shuffle($number_list);
			$this->assign('number_list',$number_list);
		}else if($now_activity['type'] == 2){
			$number_list = D('Extension_coupon_record')->field('`number`,`check_time`')->where(array('record_id'=>array('in',$record_id_arr)))->select();
			$this->assign('number_list',$number_list);
		}
		$this->assign('now_merchant',$now_merchant);
		$this->assign('now_activity',$now_activity);
		$this->assign('number_list',$number_list);
		$this->display();
	}
	protected function activity_type_txt($type){
		switch($type){
			case '1':
				return '一元夺宝';
			case '2':
				return '优惠券';
			case '3':
				return '秒杀';
			case '4':
				return '红包';
			case '5':
				return '卡券';
		}
	}
	public function join_lottery()
	{
		$result = D('Lottery')->join_lottery($this->user_session['uid']);
		$this->assign($result);
		$this->display();
	}

	public function follow_merchant()
	{
		$mod = new Model();
		$this->user_session['openid'];
//		$sql = "SELECT b.* FROM  ". C('DB_PREFIX') . "merchant_user_relation AS a INNER JOIN  ". C('DB_PREFIX') . "merchant as b ON a.mer_id=b.mer_id WHERE a.openid='onfo6t5WPe6wJswql3ljRX9aeEUA'";
		$sql = "SELECT b.* FROM  ". C('DB_PREFIX') . "merchant_user_relation AS a INNER JOIN  ". C('DB_PREFIX') . "merchant as b ON a.mer_id=b.mer_id WHERE a.openid='{$_SESSION['openid']}'";
		$res = $mod->query($sql);
		$merchant_image_class = new merchant_image();
		foreach ($res as &$r) {
			$images = explode(";", $r['pic_info']);
			$images = explode(";", $images[0]);
			$r['img'] = $merchant_image_class->get_image_by_path($images[0]);
			$r['url'] = C('config.site_url').'/wap.php?c=Index&a=index&token=' . $r['mer_id'];
		}
		$this->assign('follow_list', $res);
		$this->display();
	}

	public function cancel_follow()
	{
		$mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
		if (D('Merchant_user_relation')->where(array('mer_id' => $mer_id, 'openid' => $_SESSION['openid']))->delete()) {
			D('Merchant')->where(array('mer_id' => $mer_id, 'fans_count' => array('gt', 0)))->setDec('fans_count');
			$this->success('取消关注成功', U('My/follow_merchant'));
		} else {
			$this->error('取消关注失败，请稍后重试', U('My/follow_merchant'));
		}
	}

	public function recharge(){
		if($_POST['money']){
			if(IS_POST){
				$data_user_recharge_order['uid'] = $this->now_user['uid'];
				$money = floatval($_POST['money']);
				if(empty($money) || $money > 10000){
					$this->error('请输入有效的金额！最高不能超过1万元。');
				}
				if($_POST['label']){
					$data_user_recharge_order['label'] = $_POST['label'];
				}
				$data_user_recharge_order['money'] = $money;
				// $data_user_recharge_order['order_name'] = '帐户余额在线充值';
				$data_user_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
				$data_user_recharge_order['is_mobile_pay'] = 1;

				if($order_id = D('User_recharge_order')->data($data_user_recharge_order)->add()){
					if($_GET['type']=='gift'){
						redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'gift')));
					}elseif($_GET['type']=='classify') {
						redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'classify')));
					}else{
						redirect(U('Pay/check',array('order_id'=>$order_id,'type'=>'recharge')));
					}

				}
			}
		}else{
		    $config = D('Config')->get_config();
		    $recharge_txt = $config['recharge_discount'];
		    $recharge = explode(",",$recharge_txt);
		    $recharge_list = array();
		    foreach ($recharge as $v){
		        $v_a = explode("|",$v);
		        $recharge_list[$v_a[0]] = $v_a[1];
            }
            //krsort($recharge_list);
            $this->assign('recharge_list',$recharge_list);
			$this->display();
		}
	}

	public function withdraw(){
		if($this->config['company_pay_open']=='0') {
			$this->error_tips('平台没有开启提现功能！');
		}
		$user_info = $this->now_user;
		$can_withdraw_money = $user_info['now_money']>=$user_info['score_recharge_money']?floatval((int)(($user_info['now_money']-$user_info['score_recharge_money'])*100)/100):$user_info['now_money'];
		if ($user_info['frozen_time'] < $_SERVER['REQUEST_TIME'] && $_SERVER['REQUEST_TIME'] < $user_info['free_time']) {
			$user_info['can_withdraw_money'] = $can_withdraw_money-$user_info['frozen_money']>0? $can_withdraw_money-$user_info['frozen_money']:0;
		}else{
			$user_info['can_withdraw_money'] = $can_withdraw_money;
		}
		$this->assign('user_info',$user_info);
		if(empty($user_info['openid'])){
			$this->error_tips('您没有绑定微信');
		}
		if($_POST['money']){
			if(IS_POST){
				$money = $_POST['money'];
				if($money<$this->config['company_least_money']){
					$this->error_tips('不能低于最低提款额 '.$this->config['company_least_money'].' 元!');
				}
				if($money>$can_withdraw_money){
					$this->error_tips('提款超出限额，请求失败！');
				}
				$data_companypay['pay_type'] = 'user';
				$data_companypay['pay_id'] = $user_info['uid'];
				$data_companypay['openid'] = $user_info['openid'];
				$data_companypay['nickname'] = $_POST['truename'];
				$data_companypay['phone'] = $user_info['phone'];
				$data_companypay['money'] = bcmul($money*((100-$this->config['company_pay_user_percent'])/100),100);
				$data_companypay['desc'] = "用户提现对账订单|用户ID ".$user_info['uid']." |转账 ".$money." 元" ;
				if($this->config['company_pay_user_percent']>0){
					$data_companypay['desc'] .= '|手续费 '.$money*($this->config['company_pay_user_percent'])/100 .' 比例 '.$this->config['company_pay_user_percent'].'%';
				}
				$data_companypay['status'] = 0;
				$data_companypay['add_time'] = time();

				$use_result = D('User')->user_money($user_info['uid'],$money,'提款 '.$money.' 扣除余额',0,0,1);
				if($use_result['error_code']){
					$this->error_tips($use_result['msg']);
				}else{
					D('Companypay')->add($data_companypay);
					D('Scroll_msg')->add_msg('user_withdraw',$user_info['uid'],L('_B_MY_USER_').$user_info['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '提现成功！');
					$this->success_tips("申请成功，请等待审核！");
				}
			}
		}else{
			$where['pay_type']='user';
			$where['pay_id']=$user_info['uid'];
			$withdraw = M('Companypay');
			$count_withdraw = $withdraw->where($where)->count();
			import('@.ORG.system_page');
			$p = new Page($count_withdraw, 5);
			$withdraw_list = $withdraw->field('money,status,add_time,pay_time')->where($where)->order('pigcms_id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
			$pagebar = $p->show();
			$share = new WechatShare($this->config,$_SESSION['openid']);
			$this->BioAuthticMethod = $share->getBioAuthticMethod($this->static_path);
			$this->assign('BioAuthticMethod', $this->BioAuthticMethod);
			$this->assign('pagebar', $pagebar);
			$this->assign('draw_info',$withdraw_list);
			$this->display();
		}
	}

	//积分充值余额，这部分余额不能提现
	public function score_recharge(){
		if(!$this->config['score_recharge']||$this->config['open_extra_price']==1) {
			$this->error_tips('平台没有开启'.$this->config['score_name'].'兑换余额功能！');
		}
		$user_score_use_percent = C('config.user_score_recharge_percent');
		$score_count = D('User')->where(array('uid'=>$this->user_session['uid']))->getField('score_count');
		if($_POST['score']){
			if(IS_POST && $score_count > 0){
				$score_count = $_POST['score'];
				$score_deducte = bcdiv($score_count,$user_score_use_percent,2);
				if($res = D('User')->add_money($this->user_session['uid'],$score_deducte,$this->config['score_name'].'兑换 '.$score_deducte.' 元到账户余额')){
					D('User')->user_score($this->user_session['uid'],$score_count,''.$this->config['score_name'].'兑换余额，减扣'.$this->config['score_name'].' '.$score_count.' 个');
					D('User')->add_score_recharge_money($this->user_session['uid'],$score_deducte,'保存'.$this->config['score_name'].'兑换记录 '.$score_deducte.' 元');
					D('Scroll_msg')->add_msg('score_recharge',$this->user_session['uid'],L('_B_MY_USER_').$this->user_session['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'使用'.$this->config['score_name'].'兑换余额');

					$this->success_tips($this->config['score_name']."兑换余额成功！");
				}else{
					$this->error_tips($res['msg']);
				}
			}else{
				$this->error_tips('非法请求');
			}
		}else{
			$score_count = D('User')->where(array('uid'=>$this->user_session['uid']))->getField('score_count');
			$score_deducte = bcdiv($score_count,$user_score_use_percent,2);
			$this->assign('score_count',$score_count);
			$this->assign('score_deducte',$score_deducte);
			$share = new WechatShare($this->config,$_SESSION['openid']);
			$this->BioAuthticMethod = $share->getBioAuthticMethod($this->static_path);
			$this->assign('BioAuthticMethod', $this->BioAuthticMethod);
			$this->display();
		}
	}

	public function lifeservice(){
		$order_list = D('Service_order')->field(true)->where(array('uid'=>$this->user_session['uid'],'status'=>array('neq','0')))->order('`order_id` DESC')->select();
		foreach($order_list as &$value){
			$value['type_txt'] = $this->lifeservice_type_txt($value['type']);
			$value['type_eng'] = $this->lifeservice_type_eng($value['type']);
			$value['infoArr'] = unserialize($value['info']);
			$value['order_url'] = U('My/lifeservice_detail',array('id'=>$value['order_id']));
		}
		$this->assign('order_list', $order_list);
		// dump($order_list);
		$this->display();
	}
	public function lifeservice_detail(){
		$now_order = D('Service_order')->field(true)->where(array('order_id'=>$_GET['id']))->find();
		$now_order['infoArr'] = unserialize($now_order['info']);
		$now_order['type_txt'] = $this->lifeservice_type_txt($now_order['type']);
		$now_order['type_eng'] = $this->lifeservice_type_eng($now_order['type']);
		$now_order['pay_money'] = floatval($now_order['pay_money']);
		$this->assign('now_order', $now_order);
		// dump($order_list);
		$this->display();
	}
	public function spread_list(){
		if(!isset($_GET['status'])){
			//待结算订单
			$spread_list = D('User_spread_list')->field(true)->where(array('status'=>'0',array('_string'=>'uid = '.$this->user_session['uid'].' OR change_uid='.$this->user_session['uid'])))->order('`pigcms_id` DESC')->select();
			if($spread_list){
				foreach($spread_list as $key=>$value){
					if($value['order_type'] == 'group'){
						$order_info = $spread_list[$key]['order_info'] = D('Group_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
						if($order_info['status'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['group_info'] = $spread_list[$key]['group_info'] = D('Group')->field('`group_id`,`name`')->where(array('group_id'=>$value['third_id']))->find();
					}else if($value['order_type']=='shop'){
						$order_info = $spread_list[$key]['order_info'] = D('Shop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();

						if($order_info['status'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['shop_info'] = $spread_list[$key]['shop_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();

					}else if($value['order_type']=='store'|| $value['order_type']=='cash'){
						$order_info = $spread_list[$key]['order_info'] = D('Store_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
						if($order_info['paid'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['store_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();

					}else if($value['order_type']=='meal'){
						$order_info = $spread_list[$key]['order_info'] = D('Foodshop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
						if($order_info['paid'] == 0){
							unset($spread_list[$key]);
							continue;
						}
						$value['meal_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();

					}
					if($value['spread_uid']){
						$value['spread_user'] = $spread_list[$key]['spread_user'] = D('User')->get_user($value['spread_uid']);
					}
					$value['get_user'] = $spread_list[$key]['get_user'] = D('User')->get_user($value['get_uid']);

					//组成描述语句
					if($value['spread_user']){
						$spread_list[$key]['desc']['txt'] = '子用户 '.$value['spread_user']['nickname'].' 推广用户 '.$value['get_user']['nickname'].' 购买';
					}else{
						$spread_list[$key]['desc']['txt'] = '推广用户 '.$value['get_user']['nickname'].' 购买';
					}

					if($value['change_uid']!=0){
						if($this->user_session['uid']!=$value['change_uid']){
							$change_user = D('User')->get_user($value['change_uid'],'uid');
							$spread_list[$key]['desc']['txt'] .= '（佣金已结算给'.$change_user['nickname'].')';
						}else{
							$spread_list[$key]['desc']['txt'] .= '（佣金由'.$value['get_user']['nickname'].'处结算过来)';
						}
					}

					if($value['order_type'] == 'group'){
						$spread_list[$key]['desc']['url'] =  U('Group/detail',array('group_id'=>$value['group_info']['group_id']));
						$spread_list[$key]['desc']['info'] = $order_info['total_money'].'元产品';
					}elseif($value['order_type']=='shop'){
						$spread_list[$key]['desc']['url'] =  U('Shop/detail',array('store_id'=>$value['shop_info']['store_id']));
						$spread_list[$key]['desc']['info'] = $order_info['total_price'].'元产品';
					}elseif($value['order_type']=='store'|| $value['order_type']=='cash'){
						$spread_list[$key]['desc']['url'] =  U('My/store_order_list');
						$spread_list[$key]['desc']['info'] = $order_info['total_price'].'元产品';
					}elseif($value['order_type']=='meal'){
						$spread_list[$key]['desc']['url'] =  U('My/foodshop_order_list');
						$spread_list[$key]['desc']['info'] = $order_info['total_price'].'元产品';
					}
				}
			}
		}else{
			$condition_spread_list['_string'] ='uid = '.$this->user_session['uid'].' OR change_uid='.$this->user_session['uid'];
			//$condition_spread_list['uid'] = $this->user_session['uid'];
			if(in_array($_GET['status'],array(0,1,2))){
				$condition_spread_list['status'] = $_GET['status'];
			}
			$spread_list = D('User_spread_list')->field(true)->where($condition_spread_list)->order('`pigcms_id` DESC')->select();
			foreach($spread_list as $key=>$value){
				if($value['spread_uid']){
					$value['spread_user'] = $spread_list[$key]['spread_user'] = D('User')->get_user($value['spread_uid']);
				}
				$value['get_user'] = $spread_list[$key]['get_user'] = D('User')->get_user($value['get_uid']);

				if($value['order_type'] == 'group'){
					$value['group_info'] = $spread_list[$key]['group_info'] = D('Group')->field('`group_id`,`name`')->where(array('group_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Group_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'shop'){
					$value['shop_info'] = $spread_list[$key]['shop_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Shop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'store'|| $value['order_type']=='cash'){
					$value['store_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Store_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}else if($value['order_type'] == 'meal'){
					$value['meal_info'] = $spread_list[$key]['store_info'] = D('Merchant_store')->field('`store_id`,`name`')->where(array('store_id'=>$value['third_id']))->find();
					//if($value['status'] == 0){
					$value['order_info'] = $spread_list[$key]['order_info'] = D('Foodshop_order')->field(true)->where(array('order_id'=>$value['order_id']))->find();
					//}
				}

				//组成描述语句
				if($value['spread_user']){
					$spread_list[$key]['desc']['txt'] = '子用户 '.$value['spread_user']['nickname'].' 推广用户 '.$value['get_user']['nickname'].' 购买';
				}else{
					$spread_list[$key]['desc']['txt'] = '推广用户 '.$value['get_user']['nickname'].' 购买';
				}

				if($value['change_uid']!=0){
					if($this->user_session['uid']!=$value['change_uid']){
						$change_user = D('User')->get_user($value['change_uid'],'uid');
						$spread_list[$key]['desc']['txt'] = '（佣金已结算给'.$change_user['nickname'].')';
					}else{
						$spread_list[$key]['desc']['txt'] = '（佣金由'.$value['get_user']['nickname'].'处结算过来)';
					}
				}
				if($value['order_type'] == 'group'){
					$spread_list[$key]['desc']['url'] = U('Group/detail',array('group_id'=>$value['group_info']['group_id']));
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_money'].'元产品';
				}else if($value['order_type'] == 'shop'){
					$spread_list[$key]['desc']['url'] = U('shop/detail',array('shop_id'=>$value['shop_info']['shop_id']));
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_price'].'元产品';
				}else if($value['order_type'] == 'store'|| $value['order_type']=='cash'){
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_price'].'元产品';
					$spread_list[$key]['desc']['url'] =  U('My/store_order_list');
					$alia_name = array('store'=>'优惠买单','cash'=>'到店付');
					$spread_list[$key]['desc']['info'] = $value['store_info']['name'] .$alia_name[$value['order_type']];
				}else if($value['order_type'] == 'meal'){
					$spread_list[$key]['desc']['info'] = $value['order_info']['total_price'].'元产品';
					$spread_list[$key]['desc']['url'] =  U('My/foodshop_order_list');
					$alia_name = array('store'=>C('config.meal_alias_name'));
					$spread_list[$key]['desc']['info'] = $value['meal_info']['name'] .$alia_name[$value['order_type']];
				}
			}
		}

		$this->assign('spread_list',$spread_list);
		$this->display();
	}
	public function spread_check(){
		if($this->config['open_extra_price']==1){
			$money_name = C('config.extra_price_alias_name');
		}else{
			$money_name = '佣金';
		}
		$where = array(
				'pigcms_id'=>$_GET['id'],
				'_string'=>'uid='.$this->user_session['uid'].' OR change_uid='.$this->user_session['uid']
		);

		$now_spread = D('User_spread_list')->where($where)->find();
		//dump($now_spread);
		if($now_spread && $now_spread['status'] == 0){
			if($now_spread['order_type'] == 'group'){
				$order_info = D('Group_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
				if($order_info['status'] == '1' || $order_info['status'] == '2'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得佣金');
						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得'.$money_name.'('.$money_name.'过户)');

						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得'.$money_name);
							}else{
								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['group_alias_name'].'商品获得'.$money_name);

								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],L('_B_MY_USER_').$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}
				}else if($order_info['status'] == '3' || $order_info['status'] == '6'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>2))->save()){
						$this->success('用户已退款');
					}else{
						$this->error('操作失败');
					}
				}
			}else if ($now_spread['order_type']=='shop'){
				$order_info = D('Shop_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();
				if($order_info['status'] == '1' || $order_info['status'] == '2' || $order_info['status'] == '3'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得佣金');

						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得'.$money_name.'('.$money_name.'过户)');
						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得'.$money_name);
							}else{
								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['shop_alias_name'].'商品获得'.$money_name);
								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],L('_B_MY_USER_').$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}
				}else if($order_info['status'] == '4'||$order_info['status'] == '5'){
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>2))->save()){
						$this->success('用户已退款');
					}else{
						$this->error('操作失败');
					}
				}
			}else if ($now_spread['order_type']=='store'||$now_spread['order_type']=='cash'){
				$order_info = D('Store_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

					$alia_name = array('store'=>'优惠买单','cash'=>'到店付');
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name.'('.$money_name.'过户)');
						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name);
							}else{

								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$alia_name[$now_spread['order_type']].'商品获得'.$money_name);
								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],L('_B_MY_USER_').$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}

			}else if ($now_spread['order_type']=='meal'){
				$order_info = D('Foodshop_order')->field(true)->where(array('order_id'=>$now_spread['order_id']))->find();

					$alia_name = array('store'=>C('config.meal_alias_name'));
					if(D('User_spread_list')->where(array('pigcms_id'=>$_GET['id']))->data(array('status'=>1))->save()){
						//D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买餐饮商品获得佣金');
						if($now_spread['change_uid']!=0){
							D('User')->add_money($now_spread['change_uid'],$now_spread['money'],'推广用户购买餐饮商品获得'.$money_name.'('.$money_name.'过户)');
						}else{
							if($this->config['open_extra_price']==1){
								D('User')->add_score($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['meal_alias_name'].'商品获得'.$money_name);
							}else{
								D('User')->add_money($now_spread['uid'],$now_spread['money'],'推广用户购买'.$this->config['meal_alias_name'].'商品获得'.$money_name);
								D('Scroll_msg')->add_msg('spread',$now_spread['uid'],L('_B_MY_USER_').$now_spread['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'获得推广用户的消费佣金');
							}
						}
						$this->success('结算完成');
					}else{
						$this->error('操作失败');
					}

			}
		}
	}


	public function spread_user_list(){
		if(!empty($_GET['uid'])){
			$user = M('User')->where(array('uid'=>$_GET['uid']))->find();
		}else{
			$user = $_SESSION['user'];
		}

		$res = D('User_spread')->get_spread_user($user['openid'],$user['uid']);
		$this->assign('res',$res);
		$this->assign('user',$user);
		$this->display();
	}

	//计算用户列表
	public function my_settlement_user(){

		$res = D('User_spread')->get_spread_change_user($this->user_session['uid']);
//		dump($res);
		$this->assign('res',$res);
		//$this->assign('user',$user);
		$this->display();
	}

	//解绑关系
	public function unbind_spread_change(){
		$uid=$_POST['uid'];
		if(M('User')->where(array('uid'=>$uid))->setField('spread_change_uid',0)){

			$this->AjaxReturn(array('error_code'=>0,'msg'=>L('_B_MY_UNTIEDACCESS_')));
		}else{
			$this->AjaxReturn(array('error_code'=>1,'msg'=>L('_B_MY_UNTIEDLOSE_')));
		}
		exit;
	}

	protected function lifeservice_type_txt($type){
		switch($type){
			case '1':
				$type_txt = '水费';
				break;
			case '2':
				$type_txt = '电费';
				break;
			case '3':
				$type_txt = '煤气费';
				break;
			default:
				$type_txt = '生活服务';
		}
		return $type_txt;
	}
	protected function lifeservice_type_eng($type){
		switch($type){
			case '1':
				$type_txt = 'water';
				break;
			case '2':
				$type_txt = 'electric';
				break;
			case '3':
				$type_txt = 'gas';
				break;
			default:
				$type_txt = 'life';
		}
		return $type_txt;
	}
	protected function getPayName($label){
		$payName = array(
				'weixin' => L('_B_MY_WECHATPAIMENT_'),
				'tenpay' => '财付通支付',
				'yeepay' => L('_B_MY_CARDPAIMENT_'),
				'allinpay' => L('_B_MY_CARDPAIMENT_'),
				'chinabank' => L('_B_MY_CARDPAIMENT_'),
		);
		return $payName[$label];
	}
	/****等级升级****/
	public function levelUpdate(){
		if($this->config['level_onoff']==0){
			$this->error_tips('平台没有开启该功能');
		}
		$next = $this->user_session['level'];
		if($_GET['nextlevel']){
			$next = $_GET['nextlevel']-1;
		}
		$nextlevel = M('User_level')->where(array('level'=>array('gt',$next)))->find();
		$this->assign('nextlevel',$nextlevel['level']);
		$this->display();
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

	public function pay()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();

		if($now_store['status']!=1){
			$this->error_tips(L('_B_MY_SHOPHAVECLOSED_'),U('Wap/Home/index'));
		}
		$this->assign('now_store', $now_store);
		$now_store['discount_txt'] = unserialize($now_store['discount_txt']);
		$count = D('Store_order')->get_storeorder_count_today($store_id);
		if($now_store['discount_txt']['discount_type']==1&&$this->config['open_extra_price']==1&&$count<$now_store['discount_txt']['discount_limit']){
			$now_store['discount_txt']['discount_percent'] = $now_store['discount_txt']['discount_limit_percent'];
		}
		$this->assign('discount_type', isset($now_store['discount_txt']['discount_type']) ? $now_store['discount_txt']['discount_type'] : 0);
		$this->assign('discount_percent', isset($now_store['discount_txt']['discount_percent']) ? $now_store['discount_txt']['discount_percent'] : 0);
		$this->assign('condition_price', isset($now_store['discount_txt']['condition_price']) ? $now_store['discount_txt']['condition_price'] : 0);
		$this->assign('minus_price', isset($now_store['discount_txt']['minus_price']) ? $now_store['discount_txt']['minus_price'] : 0);
		$this->display();
	}
	public function store_order_before(){
		$order_id  = $_GET['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();

		if(empty($now_order)){
			$this->error(L('_B_MY_NOORDER_'));
		}else if($now_order['paid'] == '1'){
			$this->error(L('_B_MY_PAIDTHISORDER_'));
		}else if($now_order['uid'] != $this->user_session['uid']){
			if(M("Store_order")->where(array('order_id'=>$order_id))->data(array('uid'=>$this->user_session['uid']))->save()){
				redirect(U('Pay/check',array('type'=>'store','order_id'=>$order_id)));
			}else{
				$this->error(L('_B_MY_DATASAVELOSE_'));
			}
		}else{
			redirect(U('Pay/check',array('type'=>'store','order_id'=>$order_id)));
		}
	}
	public function shop_order_before(){
		$order_id  = $_GET['order_id'];
		$now_order = M("Shop_order")->where(array('order_id'=>$order_id))->find();

		if(empty($now_order)){
			$this->error(L('_B_MY_NOORDER_'));
		}else if($now_order['paid'] == '1'){
			$this->error(L('_B_MY_PAIDTHISORDER_'));
		}else if($now_order['uid'] != $this->user_session['uid']){
			if(M("Shop_order")->where(array('order_id'=>$order_id))->data(array('uid'=>$this->user_session['uid'], 'username' => $this->user_session['nickname'], 'userphone' => $this->user_session['phone']))->save()){
				redirect(U('Pay/check',array('type'=>'shop','order_id'=>$order_id)));
			}else{
				$this->error(L('_B_MY_DATASAVELOSE_'));
			}
		}else{
			redirect(U('Pay/check',array('type'=>'shop','order_id'=>$order_id)));
		}
	}

	public function house_order_before(){
		$order_id  = $_GET['order_id'] + 0;
		if(!$order_id){
			$this->error(L('_B_MY_PASSWRONG_'));
		}

		$now_order = M("House_village_pay_order")->where(array('order_id'=>$order_id))->find();
        $recharge_where['label'] = 'wap_village_'.$order_id;
		$recharge_order_info = M('User_recharge_order')->where($recharge_where)->find();
		$recharge_order_id = $recharge_order_info['order_id'];

		if(empty($now_order)){
			$this->error(L('_B_MY_NOORDER_'));
		}else if($now_order['paid'] == '1'){
			$this->error(L('_B_MY_PAIDTHISORDER_'));
		}else if($now_order['uid'] != $this->user_session['uid']){
			if(M("House_village_pay_order")->where(array('order_id'=>$order_id))->data(array('uid'=>$this->user_session['uid']))->save()){
				if(!$recharge_order_info){
					$recharge_data['uid'] = $this->user_session['uid'];
					$recharge_data['add_time'] = time();
					$recharge_data['money'] = $now_order['money'];
					$recharge_data['is_mobile_pay'] = 1;
					$recharge_data['label'] = 'wap_village_'.$order_id;
					$recharge_order_id = M('User_recharge_order')->data($recharge_data)->add();
				}
				redirect(U('Pay/check',array('type'=>'recharge','order_id'=>$recharge_order_id)));
			}else{
				$this->error(L('_B_MY_DATASAVELOSE_'));
			}
		}else{
			if(!$recharge_order_info){
				$recharge_data['uid'] = $this->user_session['uid'];
				$recharge_data['add_time'] = time();
				$recharge_data['money'] = $now_order['money'];
				$recharge_data['is_mobile_pay'] = 1;
				$recharge_data['label'] = 'wap_village_'.$order_id;
				$recharge_order_id = M('User_recharge_order')->data($recharge_data)->add();
			}
			redirect(U('Pay/check',array('type'=>'recharge','order_id'=>$recharge_order_id)));
		}
	}


	public function store_order()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$total_money = isset($_POST['total_money']) ? (intval($_POST['total_money'] * 100) / 100) : 0;
		$no_discount_money = isset($_POST['no_discount_money']) ? (intval($_POST['no_discount_money'] * 100) / 100) : 0;

		$total_price = isset($_POST['total_price']) ? (intval($_POST['total_price'] * 100) / 100) : 0;
		$minus_price = isset($_POST['minus_price']) ? (intval($_POST['minus_price'] * 100) / 100) : 0;
		$price = isset($_POST['price']) ? (intval($_POST['price'] * 100) / 100) : 0;

		$now_store = D('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($now_store)) {
			$this->error('店铺不存在');
		}
		if ($total_money <= 0) $this->error(L('_B_MY_PAYMONEYLESS0_'));
		$minus_price_true = $price_true = 0;
		$now_store['discount_txt'] = unserialize($now_store['discount_txt']);
		$count = D('Store_order')->get_storeorder_count_today($store_id);
		if($now_store['discount_txt']['discount_type']==1&&$this->config['open_extra_price']==1&&$count<$now_store['discount_txt']['discount_limit']){
			$now_store['discount_txt']['discount_percent'] = $now_store['discount_txt']['discount_limit_percent'];
		}

		if (isset($now_store['discount_txt']['discount_type'])) {
			if ($now_store['discount_txt']['discount_type'] == 1) {
				if (isset($now_store['discount_txt']['discount_percent']) && $now_store['discount_txt']['discount_percent'] > 0) {
					$price_true = ($total_money - $no_discount_money) * $now_store['discount_txt']['discount_percent'] / 10 + $no_discount_money;
					$minus_price_true = $total_money - $price_true;
					$extra_price = $minus_price_true*$this->config['user_score_use_percent'];
				}
			} elseif ($now_store['discount_txt']['discount_type'] == 2) {
				if (isset($now_store['discount_txt']['condition_price']) && $now_store['discount_txt']['condition_price'] > 0 && isset($now_store['discount_txt']['minus_price']) && $now_store['discount_txt']['minus_price']) {
					$minus_price_true = floor(($total_money - $no_discount_money) / $now_store['discount_txt']['condition_price']) * $now_store['discount_txt']['minus_price'];
					$price_true = $total_money - $minus_price_true;
					$extra_price = 0;
				}
			}
		}else{
			$extra_price = 0;
		}

		if ($minus_price_true == 0 && $price_true == 0) {
			$minus_price_true = 0;
			$price_true = $total_money;
		}

		$data = array('store_id' => $now_store['store_id']);
		$data['mer_id'] = $now_store['mer_id'];
		$data['uid'] = $this->user_session['uid'];
		$data['orderid'] = date("YmdHis") . mt_rand(10000000, 99999999);
		$data['name'] = L('_B_MY_CUSTOMERDIYPAY_') . $now_store['name'];
		$data['total_price'] = $total_money;
		$data['discount_price'] = $minus_price_true;
		$data['price'] = $price_true;
		$data['dateline'] = time();
		$data['from_plat'] = 1;
		$data['extra_price'] =$extra_price ;
		$order_id = D("Store_order")->add($data);
		if ($order_id) {
			$this->success(L('_B_MY_ORDERSAVEPAYNOW_'), U('Pay/check',array('order_id' => $order_id, 'type'=>'store')));
		} else {
			$this->error(L('_B_MY_ORDERLOSETRYLATER_'));
		}
	}


	/*全部订餐订单列表*/
	public function store_order_list()
	{
		$where = "uid={$this->user_session['uid']} AND paid=1";
		$order_list = D("Store_order")->field(true)->where($where)->order('order_id DESC')->select();
		$temp = $store_ids = array();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			$ol['price'] = floatval($ol['balance_pay']+$ol['payment_money']+$ol['merchant_balance']+$ol['card_give_money']) ;
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}
		$this->assign('order_list', $list);
		$this->display();
	}


	public function cardcode(){
		if(!empty($this->now_user['cardid'])){
			$_SESSION['tmp_cardid'] = substr($this->now_user['cardid'],0,1).substr($this->now_user['cardid'],-1).substr(uniqid('', true), 18).substr(microtime(), 2, 6);
			D('Physical_card')->where(array('cardid'=>$this->now_user['cardid']))->setField('t_id',$_SESSION['tmp_cardid']);
			$this->assign('cardid',$this->now_user['cardid']);
			$this->display();
		}else{
			$this->error_tips(L('_B_MY_YOUHAVENOCARD_'));
		}

	}

	public function cardbarcode(){
		import('@.ORG.barcode');
		$colorFront = new BCGColor(0, 0, 0);
		$colorBack = new BCGColor(255, 255, 255);

		// Barcode Part
		$code = new BCGcode128();
		$code->setScale(2);
		$code->setColor($colorFront, $colorBack);
		$code->parse($_SESSION['tmp_cardid']);

		// Drawing Part
		$drawing = new BCGDrawing('', $colorBack);
		$drawing->setBarcode($code);
		$drawing->draw();

		header('Content-Type: image/png');
		$drawing->finish(BCGDrawing::IMG_FORMAT_PNG);
	}

	public function cardqrcode(){
		import('@.ORG.phpqrcode');
		QRcode::png($_SESSION['tmp_cardid'],false,2,8,2);
	}


	private function meal_after_refund($now_order)
	{
		$msg = '';
		//如果使用了优惠券
		if($now_order['card_id']){
			$result = D('Member_card_coupon')->add_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg = $result['msg'];
		}


		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count']!=='0') {
			$order_info=unserialize($now_order['info']);
			$order_name=$order_info[0]['name']."*".$order_info[0]['num'];
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_').$order_name.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Group_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg .= $result['msg'];
		}

		//平台余额退款
		if($now_order['balance_pay'] != '0.00'){
			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'订单退款 (订单号:'.$now_order['order_name'].')-6',0,0,0,'Order Cancellation (Order #'.$now_order['order_name'].')');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg .= '平台余额退款成功';
			// 			if($add_result['error_code']){
			// 				$this->error_tips($add_result['msg']);
			// 			}
			// 			$go_refund_param['msg'] = $add_result['msg'];

			// 			$data_meal_order['order_id'] = $now_order['order_id'];
			// 			$data_meal_order['refund_detail'] = serialize(array('refund_time'=>time()));
			// 			$data_meal_order['status'] = 3;
			// 			D('Meal_order')->data($data_meal_order)->save();
		}
		//商家会员卡余额退款
		if($now_order['merchant_balance'] != '0.00'){
			$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			if ($result['error_code']) {
				return array('error' => 1, 'msg' => $result['msg']);
				$this->error_tips($result['msg']);
			}
			$msg .= $result['msg'];
		}
		if(empty($now_order['pay_type'])){
			$data_meal_order['order_id'] = $now_order['order_id'];
			$data_meal_order['status'] = 3;
			D('Meal_order')->data($data_meal_order)->save();
			$msg .= L('_B_MY_ORDERCANCELLEDACCESS_');
		}

		//退款时销量回滚
		if ($now_order['paid'] == 1 && date('m', $now_order['dateline']) == date('m')) {
			foreach (unserialize($now_order['info']) as $menu) {
				D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
			}
		}
		D("Merchant_store_meal")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);

		//退款打印
		$msg = ArrayToStr::array_to_str($now_order['order_id']);
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->mer_id, $store_id, $msg, 3);


		$str_format = ArrayToStr::print_format($now_order['order_id']);
		foreach ($str_format as $print_id => $print_msg) {
			$print_id && $op->printit($this->mer_id, $store_id, $print_msg, 3, $print_id);
		}

		$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();
		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'food');
		if ($this->config['sms_cancel_order'] == 1 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['phone'] ? $now_order['phone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = L('_B_MY_YOUAT_') . $mer_store['name'] . L('_B_MY_SHOPORDERNUM_') . $orderid . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED_');
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_cancel_order'] == 2 || $this->config['sms_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = L('_B_MY_CUSTOMER_') . $now_order['name'] . L('_B_MY_BOOKINGNUM_') . $orderid . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED2_');
			Sms::sendSms($sms_data);
		}
		return array('error' => 0, 'msg' => $msg);
	}

	public function return_refund()
	{
		echo "<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>";
		die;
		$pay_class_name = ucfirst($_GET['pay_type']);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips(L('_B_MY_THISPAIMENTNOTOPEN_'));
		}
		$pay_class = new $pay_class_name('', '', $_GET['pay_type'], $pay_method[$_GET['pay_type']]['config'], '', 1);
		$go_refund_param = $pay_class->return_refund();
		if ($go_refund_param['error']) {
			$this->error_tips($go_refund_param['msg']);
		}
		$data = $go_refund_param['order_param'];
		if ($data['order_type'] == 'group') {

		} else {
			$now_order = M("Meal_order")->where(array('orderid' => $data['order_id']))->find();
		}
		$refund_param['refund_id'] = $data['sp_refund_no'];
		$refund_param['ret_code'] = $data['ret_code'];
		$refund_param['ret_detail'] = $data['ret_detail'];
		$refund_param['refund_time'] = $data['refund_time'];

		$data_meal_order['order_id'] = $now_order['order_id'];
		$data_meal_order['refund_detail'] = serialize($refund_param);
		if (empty($go_refund_param['error']) && $data['ret_code'] == 1) {
			echo "<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>";
			$data_meal_order['status'] = 3;
		}
		D('Meal_order')->data($data_meal_order)->save();
		if($data_meal_order['status'] != 3){
			$this->error_tips($go_refund_param['msg']);
		}
		$result = $this->meal_after_refund($now_order);
		if ($result['error']) {
			$this->error_tips($result['msg']);
		}
		if ($now_order['meal_type'] == 1) {
			$this->success_tips($result['msg'], U('Takeout/order_detail', array('order_id' => $now_order['order_id'], 'store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id'])));
		} else {
			$this->success_tips($result['msg'], U('Food/order_detail', array('order_id' => $now_order['order_id'], 'store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id'])));
		}
	}

	//微信蓝牙
	public function wxblue(){
		C('open_authorize_wxpay',true);
		$share = new WechatShare($this->config,$_SESSION['openid']);
		$this->hideScript = $share->gethideOptionMenu(1);
		$this->assign('hideScript', $this->hideScript);
		$this->display();
	}

	//推广二维码
	public function my_spread_qrcode(){
		if(empty($this->now_user['openid'])){
			$this->error_tips("_B_MY_YOUHAVENATBANDINGWECHAT_");
		}else{
			$this->assign('uid',$this->now_user['uid']);
			$spread = M('User_spread_qrcode');
			$spread_info = $spread->where(array('uid'=>$this->now_user['uid']))->find();
			$now_time = time();

			if(!empty($spread_info)){
				if($spread_info['qrcode_type']==0&&$now_time>strtotime(date('Y-m-d',$spread_info['last_time']))+86400){
					$this->get_spread_qrcode($spread_info['url'],$this->now_user['uid']);
					$spread_info = $spread->where(array('uid'=>$this->now_user['uid']))->find();
				}
				$effective_date = date('m月d号',$now_time+2592000);
				$spread= array("error_code"=>false,'id'=>$spread_info['qrcode_id'],'url'=>$spread_info['url'],'ticket'=>$spread_info['ticket'],'effective_date'=>$effective_date);
				$this->assign('spread_info',$spread);
			}
			$this->display();
		}
	}

	//@param 推广海报
	public function my_spread_hb(){
		$where['id'] = $_GET['id'];
		$spread_info = M('User_spread_qrcode')->where($where)->find();

//		$promote = D('Store_promote_setting')->where(array('status'=>1,'type'=>1))->order('rand()')->find();
		$res = D('Store_promote_setting')->where(array('status'=>1,'type'=>1))->select();
		$key = array_rand($res,1);
		$promote = $res[$key];
		//$ticket = $this->get_spread_qrcode($spread_info['url'], $this->now_user['uid'], true);
		$promote['qrcode'] = $spread_info['ticket'];
		$image_url = D('Store_promote_setting')->createImage($promote,  $spread_info['ticket'], $this->now_user, '');
		$this->assign('image',$image_url);
		$this->display();
	}

	public function my_spread(){
		$this->display();
	}
	public function get_spread_qrcode($spread_url='',$uid=0,$is_func=false){
		$_POST['url'] = empty($spread_url)?$_POST['url']:$spread_url;
		$_POST['uid'] = empty($uid)?$_POST['uid']:$this->now_user['uid'];
		$url = $_POST['url'];

		$url_info = parse_url($url);
		$n = preg_match('/(.*\.)?(\w+\.\w+)$/',$url_info['host'], $matches);

		if(!strpos($_POST['url'], $matches[2])&&empty($spread_url)){
			echo json_encode(array('error_code'=>1,'msg'=>L('_B_MY_WRONGDOMAINNAME_')));exit;
		}
		$spread = M('User_spread_qrcode');
		$qrcode_id =900000000+$_POST['uid'];
		if($this->config['user_spread_qrcode_tmp']){
			$date['qrcode_type']=0;
			$res = D('Recognition')->get_tmp_qrcode($qrcode_id);
		}else{
			$date['qrcode_type']=1;
			$res = D('Recognition')->get_new_qrcode('spread',$qrcode_id);
			$res['ticket']=$res['qrcode'];
		}
		if($is_func){
			return $res['ticket'];
		}
		$date['qrcode_id']=$qrcode_id;
		if(strpos($url,'://')){
			$date['url']=$url;
		}else{
			$date['url']='http://'.$url;
		}
		if(!strpos($url,'openid')) {
			if (strpos($url, '?')) {
				$date['url'] .= "&openid=" . $this->now_user['openid'];
			} else {
				$date['url'] .= "?openid=" . $this->now_user['openid'];
			}
		}
		$date['url'] = html_entity_decode($date['url']);
		$date['ticket']=$res['ticket'];
		$date['last_time']=time();
		$where['uid']=$_POST['uid'];
		if($result=$spread->where($where)->find()){
			$spread->where($where)->save($date);
		}else{
			$date['create_time']=time();
			$date['uid']=$_POST['uid'];
			$id = $spread->data($date)->add();
		}
		if(!empty($result)){
			$id = $result['id'];
		}
		$res['qrcode_type']=$date['qrcode_type'];
		if(empty($spread_url)){
			echo json_encode(array('error_code'=>0,'msg'=>$res,'id'=>$id));exit;
		}
	}

	//佣金过户
	public function my_spread_change(){
		if(IS_POST){
			if($_POST['change_user']==$this->now_user['phone']){
				$this->AjaxReturn(array('error_code'=>1,'msg'=>'过户用户不能是自己'));
			}
			$bind__user = D('User')->get_user( $_POST['change_user'],'phone');
			if(empty($bind__user)){
				$this->AjaxReturn(array('error_code'=>1,'msg'=>'用户不存在'));
			}else{
				M('User')->where(array('uid'=>$this->now_user['uid']))->setField('spread_change_uid',$bind__user['uid']);
				$this->AjaxReturn(array('error_code'=>0,'msg'=>'绑定成功'));
			}
		}else{
			if($this->now_user['spread_change_uid']>0){
				$this->assign('change_user',D('User')->get_user($this->now_user['spread_change_uid']));
			}
			$this->display();
		}
	}

	//ajax 获取用户列表
	public function ajax_search_user(){
		$key = $_POST['key'];
		$value = $_POST['value'];
		$res = M('User')->field('nickname,phone')->where(array($key=>array('like','%'.$value.'%')))->select();
		if(empty($res)){
			$this->AjaxReturn(array('error_code'=>1,'msg'=>L('_B_MY_NOTHAVEUSER_')));
		}else{
			$this->AjaxReturn(array('error_code'=>0,'msg'=>$res));
		}
	}

	//	我的实名认证
	public function authentication(){
		$this->user_sessions();
		$uid	=	$this->user_session['uid'];
		$find	=	M('User_authentication')->field(true)->where(array('uid'=>$uid))->find();
		$this->assign('find',$find);
		$this->display();
	}
	//	我的实名认证提交
	public function authentication_json(){
		$where['uid']	=	$this->user_session['uid'];
		$find	=	M('User_authentication')->where($where)->find();
		$auth_data	=	array(
			'user_truename'			=>	$_POST['user_truename'],
			'user_id_number'		=>	$_POST['user_id_number'],
			'authentication_img'	=>	$_POST['authentication_img'],
			'authentication_back_img'=>	$_POST['authentication_back_img'],
			'hand_authentication'	=>	$_POST['hand_authentication'],
			'authentication_time'	=>	$_SERVER['REQUEST_TIME'],
			'examine_time'			=>	0,
			'authentication_status'	=>	0,
		);
		if($find){
			$user_authentication	=	M('User_authentication')->where($where)->data($auth_data)->save();
		}else{
			$auth_data['uid']	=	$this->user_session['uid'];
			$user_authentication	=	M('User_authentication')->data($auth_data)->add();
		}
		if(empty($user_authentication)){
			$this->returnCode('40000031');
		}else{
			$data['truename']	=	$_POST['user_truename'];
			$data['real_name']	=	2;
			$save	=	D('User')->scenic_save_user($where,$data);
			if($save){
				$_SESSION['user']['real_name']	=	2;
				$_SESSION['user']['truename']	=	$data['truename'];
			}
		}

		if(define(IS_HOUSE) == true){
			$url	=	U('village_my',array('village_id'=>$_POST['village_id']));
		}else{
			$url	=	$this->config['site_url'].U('myinfo');
		}
		$this->returnCode(0,$url);
	}
	//	我的实名认证展示
	public function authentication_index(){
		$this->user_sessions();
		$authentication	=	D('User_authentication')->field(true)->order('authentication_time DESC')->where(array('uid'=>$this->user_session['uid']))->find();
		if($authentication){
			$store_image_class = new scenic_image();
			$a_img = strstr($authentication['authentication_img'], ',',true);
			$b_img = strstr($authentication['authentication_back_img'], ',',true);
			if($a_img){
				$authentication['authentication_img'] = $store_image_class->get_image_by_path($authentication['authentication_img'],$this->config['site_url'],'aguide','1');
			}
			if($b_img){
				$authentication['authentication_back_img'] = $store_image_class->get_image_by_path($authentication['authentication_back_img'],$this->config['site_url'],'aguide','1');
			}
		}else{
			redirect(U('authentication'));
		}
		$this->assign('authentication',$authentication);
		$this->display();
	}
	/*     * *图片上传** */
	public function authenticationUpload() {
		$mulu=isset($_GET['ml']) ? trim($_GET['ml']):'group';
		$mulu=!empty($mulu) ? $mulu : 'group';
		$filename = trim($_POST['filename']);
		$img = $_POST[$filename];
		$img = str_replace('data:image/png;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$imgdata = base64_decode($img);
//		$img_order_id = sprintf("%09d",$this->user_session['uid']);
//		$rand_num = mt_rand(10,99).'/'.substr($img_order_id,0,3).'/'.substr($img_order_id,3,3).'/'.substr($img_order_id,6,3);
		$getupload_dir = "/upload/".$mulu."/" .$this->user_session['uid'];

		$upload_dir = "." . $getupload_dir;
		if (!is_dir($upload_dir)) {
			mkdir($upload_dir, 0777, true);
		}
        $newfilename = $mulu.'_' . date('YmdHis') . '.jpg';
		$save = file_put_contents($upload_dir . '/' . $newfilename, $imgdata);
		if ($save) {
			$this->dexit(array('error' => 0, 'data' => array('code' => 1, 'siteurl'=>$this->config['site_url'],'imgurl' =>$getupload_dir . '/' . $newfilename, 'msg' => '')));
		} else {
			$this->dexit(array('error' => 1, 'data' => array('code' => 0, 'url' => '', 'msg' => L('_B_MY_SAVELOSE_'))));
		}
	}

	public function is_group_share(){
		if(!M('Group_order')->where(array('order_id'=>$_POST['order_id']))->save(array('is_share_group'=>1))){
			$this->error('更新组团购分享失败！');
		}else{
			$date['fid']=$_POST['order_id'];
			$date['uid']=$_POST['uid'];
			$date['order_id']=$_POST['order_id'];
			$result = M('Group_share_relation')->where($date)->find();
			if($result){
				$this->success('已经生成团购分组，不能再生成了！');
			}
			M('Group_share_relation')->add($date);
			$this->success('更新组团购分享成功！');
		}
	}

	public function ajax_group_share_num(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>L('_B_MY_PASSWRONG_'))));
		}
		$num = D('Group_share_relation')->get_share_num($uid,$order_id);

		exit(json_encode(array('error_code'=>0,'num'=>(int)$num)));

	}

	public function ajax_now_pin_num(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>L('_B_MY_PASSWRONG_'))));
		}
		$num = D('Group_start')->get_group_start_by_order_id($order_id);

		exit(json_encode(array('error_code'=>0,'num'=>$num)));

	}


	public  function  ajax_group_user(){
		$uid = $_POST['uid'];
		$order_id = $_POST['order_id'];
		$uids = explode(',',substr($_POST['uids'],0,-1));

		if(!$order_id||!uid){
			exit(json_encode(array('error_code'=>1,'msg'=>L('_B_MY_PASSWRONG_'))));
		}
		if(empty($_POST['type'])){

			$res['user_arr'] = D('Group_share_relation')->get_share_user($uid,$order_id);
		}else{
			$res['user_arr'] =  D('Group_start')->get_buyerer_by_order_id($order_id);
		}
		foreach($res['user_arr'] as $v){
			if(in_array($v['uid'],$uids)){
				$res['in'][] = $v['uid'];
			}
		}
		foreach($uids as $vv){
			if(!in_array($vv,$res['in'])){
				$res['not_in'][] = $vv;
			}
		}
		exit(json_encode(array('error_code'=>0,'res'=>$res)));
	}

	public function change_is_share(){
		if(!M('Group_order')->where(array('order_id'=>$_POST['order_id']))->save(array('is_share_group'=>2))){
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_CANCELLLOSE1_'))));
		}
	}
	public function ajax_wap_user_del(){
		if(IS_AJAX){
			$order_id = $_POST['order_id'] + 0;
			if(empty($order_id)){
				exit(json_encode(array('msg' => L('_B_MY_PASSWRONG_'),'status' => 0)));
			}

			$database_appoint_order = D('Appoint_order');
			$where['order_id'] = $order_id;
			$data['is_del'] = 5;
			$data['del_time']= time();
			$result = $database_appoint_order->where($where)->data($data)->save();
			if(!empty($result)){
				exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_CANCELLACCESS1_'))));
			}else{
				exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_CANCELLLOSE1_'))));
			}
		}else{
			$this->error_tips(L('_B_MY_NOPAGE_'));
		}
	}



	public function ajax_wap_appoint_del(){
		if(IS_AJAX){
			$order_id = $_POST['order_id'] + 0;
			if(empty($order_id)){
				exit(json_encode(array('msg' => L('_B_MY_PASSWRONG_'),'status' => 0)));
			}

			if($this->config['appoint_rule']){
				$database_appoint_order = D('Appoint_order');
				$now_order = $database_appoint_order->get_order_by_id($this->user_session['uid'] , $order_id);
				$appoint_service_time = strtotime($now_order['appoint_date']  . ' ' .  $now_order['appoint_time']);
				$appoint_before_cancel_time = $this->config['appoint_before_cancel_time'] * 60;

				if((time() > ($appoint_service_time - $appoint_before_cancel_time)) && ($now_order['payment_status'] > 0)){
					if($now_order['payment_status'] > 0){
					$where['order_id'] = $order_id;
					$fields['paid'] = 3;
					$fields['del_time'] = time();
					if($database_appoint_order->where($where)->data($fields)->save()){
						$now_order['order_type']='appoint';
						$now_appoint = M('Appoint')->field('appoint_name')->where(array('appoint_id'=>$now_order['appoint_id']))->find();

						if($now_order['product_payment_price']){
							$tmp_price = $now_order['product_payment_price'];
						}else{
							$tmp_price = $now_order['payment_price'];
						}


						$order_info['order_id'] = $now_order['order_id'];
						$order_info['store_id'] = $now_order['store_id'];
						$order_info['order_type'] = 'appoint';
						$order_info['balance_pay'] = $now_order['balance_pay'];
						$order_info['score_deducte'] = $now_order['score_deducte'];
						$order_info['payment_money'] = $now_order['pay_money'];
						$order_info['is_own'] = $now_order['is_own'];
						$order_info['merchant_balance'] = $now_order['merchant_balance'];
						$order_info['score_used_count'] = $now_order['score_used_count'];
						$order_info['money'] = $order_info['balance_pay'] + $order_info['score_deducte'] + $order_info['payment_money'] + $order_info['merchant_balance'];

						if($now_order['product_id'] > 0){
							$order_info['total_money'] = $now_order['product_price'];
						}else{
							$order_info['total_money'] = $now_order['appoint_price'];
						}

						$result = D('Merchant_money_list')->add_money($now_order['mer_id'] , '用户预约 '.$now_appoint['appoint_name'].'*1 取消预约，定金' . $order_info['money'] . '记入收入',$order_info);
						if(!$result['error_code']){
							exit(json_encode(array('status' => 1,'msg' => L('_B_MY_CANCELLACCESS1_'))));
						}else{
							exit(json_encode(array('status' => 0,'msg' => L('_B_MY_CANCELLLOSE1_'))));
						}
					}
				}
			}else{
				$order_id = $_POST['order_id'] + 0;
				if(empty($order_id)){
					exit(json_encode(array('msg' => L('_B_MY_PASSWRONG_'),'status' => 0)));
				}

				$database_appoint_order = D('Appoint_order');
				$where['order_id'] = $order_id;
				$data['is_del'] = 5;
				$data['del_time']= time();
				$result = $database_appoint_order->where($where)->data($data)->save();
				if(!empty($result)){
					exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_CANCELLACCESS1_'))));
				}else{
					exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_CANCELLLOSE1_'))));
				}
			}
			}}else{
			$this->error_tips(L('_B_MY_NOPAGE_'));
		}
	}


	public function ajax_wap_appoint_pay_balance(){
		if(IS_POST){
			$order_id = $_POST['order_id'] + 0;
			if(empty($order_id)){
				exit(json_encode(array('msg' => L('_B_MY_PASSWRONG_'),'status' => 0)));
			}

			if($this->config['appoint_rule']){
				$database_appoint_order = D('Appoint_order');
				$now_order = $database_appoint_order->get_order_by_id($this->user_session['uid'],$order_id);

				if(empty($now_order)){
					exit(json_encode(array('msg' => '该订单不存在！','status' => 0)));
				}

				//$now_user = D('User')->get_user($now_order['uid']);
				$now_pay_money = $now_order['product_price'] - $now_order['product_payment_price'];

				if(!$now_pay_money){
					$now_pay_money = $now_order['appoint_price'] - $now_order['payment_money'];
				}

				if($now_pay_money <= 0){
					exit(json_encode(array('status' => 0,'msg' => L('_B_MY_DATADEALWRONG_'))));
				}

				$href = U('Pay/check', array('order_id' => $order_id, 'type' => 'balance-appoint'));
				exit(json_encode(array('url' => $href,'status' => 1,'msg'=>L('_B_MY_NOMONEY_'))));
			}
		}else{
			$this->error_tips(L('_B_MY_NOPAGE_'));
		}
	}
    //取消订单
	public function shop_order_refund()
	{
		if (empty($this->user_session)) {
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$order_id = intval($_GET['order_id']);
		$now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
		if (empty($now_order)) {
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips(L('_B_MY_ORDERDEALING_'),U("Shop/order_detail",array('order_id'=>$order_id)));
		}
		if (empty($now_order['paid'])) {
			$this->error_tips(L('_B_MY_ORDERNOPAY_'),U("Shop/order_detail",array('order_id'=>$order_id)));
		}

		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'), U('Shop/status',array('order_id' => $now_order['order_id'])));
		} elseif ($now_order['status'] > 3 && !($now_order['paid'] == 1 && $now_order['status'] == 5)) {
			$this->redirect(U('Shop/status',array('order_id' => $now_order['order_id'])));
		}
		$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], $now_order['is_mobile_pay']);
		$this->assign('now_order', $now_order);
		$this->display();
	}

	//取消订单
	public function shop_order_check_refund()
	{
		if (empty($this->user_session)) {
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$order_id = intval($_GET['order_id']);
		$now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
		if(empty($now_order)){
			$this->error_tips(L('_B_MY_NOORDER_'));
		}
		$store_id = $now_order['store_id'];
		$this->mer_id = $now_order['mer_id'];

		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips(L('_B_MY_ORDERDEALING_'));
		}
		if (empty($now_order['paid'])) {
			$this->error_tips(L('_B_MY_ORDERNOPAY_'));
		}
		if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
			$this->error_tips(L('_B_MY_ORDERMUSTNOPAID_'), U('Shop/status',array('order_id' => $now_order['order_id'])));
		} elseif ($now_order['status'] > 3 && !($now_order['paid'] == 1 && $now_order['status'] == 5)) {
			$this->redirect(U('Shop/status',array('order_id' => $now_order['order_id'])));
		}

		$mer_store = D('Merchant_store')->where(array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id']))->find();
		$my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();

		//线下支付退款
		$data_shop_order['cancel_type'] = 5;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
		if ($now_order['pay_type'] == 'offline' || $now_order['pay_type'] == 'Cash') {

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
			$data_shop_order['status'] = 4;

			if (D('Shop_order')->data($data_shop_order)->save()) {
				$return = $this->shop_refund_detail($now_order, $store_id);
				if ($return['error_code']) {
					$this->error_tips($return['msg']);
				} else {
                    //add garfunkel 取消订单成功 发送消息
                    if (C('config.sms_shop_cancel_order') == 1 || C('config.sms_shop_cancel_order') == 3) {

                        $userInfo = D('User')->field(true)->where(array('uid'=>$now_order['uid']))->find();
                        if($userInfo['device_id'] != ""){
                            $message = 'Your order ('.$order_id.') has been successfully canceled at '.date('Y-m-d H:i:s').' at '.lang_substr($mer_store['name'], 'en-us').' store, we are looking forward to seeing you again.';
                            Sms::sendMessageToGoogle($userInfo['device_id'],$message);
                        }else {
                            $sms_data['uid'] = $now_order['uid'];
                            $sms_data['mobile'] = $now_order['userphone'] ? $now_order['userphone'] : $my_user['phone'];
                            $sms_data['sendto'] = 'user';
                            $sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
                            $sms_data['params'] = [
                                $order_id,
                                date('Y-m-d H:i:s'),
                                lang_substr($mer_store['name'], 'en-us')
                            ];
                            $sms_data['tplid'] = 171187;
                            //Sms::sendSms2($sms_data);
                            $sms_txt = "Your order (".$order_id.") has been successfully canceled at ".date('Y-m-d H:i:s')." at ".lang_substr($mer_store['name'], 'en-us')." store, we are looking forward to seeing you again.";
                            //Sms::telesign_send_sms($sms_data['mobile'],$sms_txt,0);
                            Sms::sendTwilioSms($sms_data['mobile'],$sms_txt);
                        }
                    }
                    if (C('config.sms_shop_cancel_order') == 2 || C('config.sms_shop_cancel_order') == 3) {

                        $sms_data['uid'] = 0;
                        $sms_data['mobile'] = $mer_store['phone'];
                        $sms_data['sendto'] = 'merchant';
                        $sms_data['content'] = '顾客' . $now_order['username'] . '的预定订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
                        $sms_data['params'] = [
                            $now_order['username'],
                            $order_id,
                            date('Y-m-d H:i:s')
                        ];
                        $sms_data['tplid'] = 169151;
                        //Sms::sendSms2($sms_data);

                        //add garfunkel 添加语音
                        $txt = "This is a important message from island life , the customer has canceled the last order.";
                        Sms::send_voice_message($sms_data['mobile'],$txt);
                    }
                    //$this->success_tips(L('_B_MY_USEOFFLINECHANGEREFUND_'),U('Shop/status',array('order_id' => $now_order['order_id'], 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
                    //peter 21-03-09 Wap&c=My&a=shop_order_list&select=history
                    if($mer_store['link_type'] == 1) {
                        $now_order['link_id'] = $mer_store['link_id'];

                        import('@.ORG.Deliverect.Deliverect');
                        $deliverect = new Deliverect();
                        $result = $deliverect->createDelOrder($now_order);
                    }

                    $this->success_tips(L('_B_MY_USEOFFLINECHANGEREFUND_'),U('Wap/My/shop_order_list',array('select' => "history")));

                }
			} else {
				$this->error_tips(L('_B_MY_CANCELLLOSE_'));
			}
		}else{
            if($now_order['pay_type'] == 'moneris'){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
                $resp = $moneris_pay->refund($this->user_session['uid'],$now_order['order_id']);
//                var_dump($resp);die();
                if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->error_tips($resp['message']);
                }
            }else if($now_order['pay_type'] == 'weixin' || $now_order['pay_type'] == 'alipay'){
                import('@.ORG.pay.IotPay');
                $IotPay = new IotPay();
                $result = $IotPay->refund($this->user_session['uid'],$now_order['order_id'],'WEB');
                if ($result['retCode'] == 'SUCCESS' && $result['resCode'] == 'SUCCESS'){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->error_tips($result['retMsg']);
                }
            }
//			else if ($now_order['payment_money'] != '0.00') {
//				if ($now_order['is_own']) {
//					$pay_method = array();
//					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
//					foreach($merchant_ownpay as $ownKey=>$ownValue){
//						$ownValueArr = unserialize($ownValue);
//						if($ownValueArr['open']){
//							$ownValueArr['is_own'] = true;
//							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
//						}
//					}
//					$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
//					if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
//						$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
//						$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
//						$pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
//						$pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
//						$pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
//						$pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
//						$pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
//						$pay_method['weixin']['config']['is_own'] = 1 ;
//					}
//				} else {
//					$pay_method = D('Config')->get_pay_method(0,0,1);
//				}
//
//				if (empty($pay_method)) {
//					$this->error_tips(L('_B_MY_NOPAIMENTMETHOD_'));
//				}
//				if (empty($pay_method[$now_order['pay_type']])) {
//					$this->error_tips(L('_B_MY_CHANGEPAIMENT_'));
//				}
//
//				$pay_class_name = ucfirst($now_order['pay_type']);
//				$import_result = import('@.ORG.pay.'.$pay_class_name);
//				if(empty($import_result)){
//					$this->error_tips(L('_B_MY_THISPAIMENTNOTOPEN_'));
//				}
//				D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_refund' => 1));
//				$now_order['order_type'] = 'shop';
//				$now_order['order_id'] = $now_order['orderid'];
//				if($now_order['is_mobile_pay']==3){
//					$pay_method[$now_order['pay_type']]['config'] =array(
//							'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
//							'pay_weixin_key'=>$this->config['pay_wxapp_key'],
//							'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
//							'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
//					);
//				}
//				$pay_class = new $pay_class_name($now_order, $now_order['payment_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, 1);
//				$go_refund_param = $pay_class->refund();
//
//				$now_order['order_id'] = $order_id;
//				$data_shop_order['order_id'] = $order_id;
//				$data_shop_order['refund_detail'] = serialize($go_refund_param['refund_param']);
//				if (empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok') {
//					$data_shop_order['status'] = 4;
//				}
//				$data_shop_order['last_time'] = time();
//				D('Shop_order')->data($data_shop_order)->save();
//				if($data_shop_order['status'] != 4){
//					$this->error_tips($go_refund_param['msg']);
//				}else{
//					$go_refund_param['msg'] ="在线支付退款成功 ";
//				}
//			}

			$return = $this->shop_refund_detail($now_order, $store_id);
			if ($return['error_code']) {
				$this->error_tips($return['msg']);
			} else {
				$go_refund_param['msg'] .= $return['msg'];
			}

			if (empty($now_order['pay_type'])) {
				$data_shop_order['order_id'] = $now_order['order_id'];
				$data_shop_order['status'] = 4;
				$data_shop_order['last_time'] = time();
				D('Shop_order')->data($data_shop_order)->save();
				$go_refund_param['msg'] = L('_B_MY_ORDERCANCELLEDACCESS_');
			}
			if(empty($go_refund_param['msg'])){
                //die("empty2");
				$go_refund_param['msg'] = L('_B_MY_ORDERCANCELLEDACCESS_');
			}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));

            if($mer_store['link_type'] == 1) {
                $now_order['link_id'] = $mer_store['link_id'];

                import('@.ORG.Deliverect.Deliverect');
                $deliverect = new Deliverect();
                $result = $deliverect->createDelOrder($now_order);
            }
			//$this->success_tips($go_refund_param['msg'], U('Shop/status',array('order_id' => $order_id, 'store_id' => $store_id, 'mer_id' => $this->mer_id)));
            $this->success_tips($go_refund_param['msg'], U('Wap/My/shop_order_list',array('select' => "history")));

		}
	}

	private function shop_refund_detail($now_order, $store_id)
	{
		$order_id  = $now_order['order_id'];

		$mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();

		//如果使用了优惠券
//		if ($now_order['card_id']) {
//			$result = D('Member_card_coupon')->add_card($now_order['card_id'], $now_order['mer_id'], $now_order['uid']);
//			$param = array('refund_time' => time());
//			if ($result['error_code']) {
//				$param['err_msg'] = $result['msg'];
//			} else {
//				$param['refund_id'] = $now_order['order_id'];
//			}
//			$data_shop_order['order_id'] = $now_order['order_id'];
//			$data_shop_order['refund_detail'] = serialize($param);
//			$result['error_code'] || $data_shop_order['status'] = 4;
//			D('Shop_order')->data($data_shop_order)->save();
//			if ($result['error_code']) {
//				return $result;
//				$this->error_tips($result['msg']);
//			}
//			$go_refund_param['msg'] .= ' '.$result['msg'];
//		}

		//如果使用了积分 2016-1-15
		if ($now_order['score_used_count'] != 0) {
			$result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_') . $mer_store['name'] . '(' . $order_id . ') '.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}
			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= ' '.$result['msg'];
		}

		//平台余额退款
		if ($now_order['balance_pay'] != '0.00') {
            //var_dump($now_order);die('---------------');
			$add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'订单退款 (订单号:'.$now_order['order_id'].').',0,0,0,'Order Cancellation (Order #'.$now_order['order_id'].')');

			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= L('_B_MY_USEOFFLINECHANGEREFUND_');
		}
		//商家会员卡余额退款
		if ($now_order['merchant_balance'] != '0.00'||$now_order['card_give_money']!='0.00') {
			//$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],L('_B_MY_REFUND_') . $mer_store['name'] . '(' . $order_id . ')  增加余额');
			$result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');

			$param = array('refund_time' => time());
			if ($result['error_code']) {
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $now_order['order_id'];
			}

			$data_shop_order['order_id'] = $now_order['order_id'];
			$data_shop_order['refund_detail'] = serialize($param);
			$result['error_code'] || $data_shop_order['status'] = 4;
			D('Shop_order')->data($data_shop_order)->save();
			if ($result['error_code']) {
				return $result;
				$this->error_tips($result['msg']);
			}
			$go_refund_param['msg'] .= $result['msg'];
		}

		//退款打印
		$msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->mer_id, $store_id, $msg, 3);

		$str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
		foreach ($str_format as $print_id => $print_msg) {
			$print_id && $op->printit($this->mer_id, $store_id, $print_msg, 3, $print_id);
		}

		$sms_data = array('mer_id' => $mer_store['mer_id'], 'store_id' => $mer_store['store_id'], 'type' => 'shop');
		if ($this->config['sms_shop_cancel_order'] == 1 || $this->config['sms_shop_cancel_order'] == 3) {
			$sms_data['uid'] = $now_order['uid'];
			$sms_data['mobile'] = $now_order['userphone'] ? $now_order['userphone'] : $my_user['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = L('_B_MY_YOUAT_') . $mer_store['name'] . L('_B_MY_SHOPORDERNUM_') . $order_id . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED_');
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_shop_cancel_order'] == 2 || $this->config['sms_shop_cancel_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $mer_store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = L('_B_MY_CUSTOMER_') . $now_order['username'] . L('_B_MY_BOOKINGNUM_') . $order_id . L('_B_MY_AT_') . date('Y-m-d H:i:s') . L('_B_MY_TIMECANCELLED2_');
			Sms::sendSms($sms_data);
		}

		//退款时销量回滚
		if (($now_order['paid'] == 1 || $now_order['reduce_stock_type'] == 1) && $now_order['is_rollback'] == 0) {
			$goods_obj = D("Shop_goods");
			foreach ($now_order['info'] as $menu) {
				$goods_obj->update_stock($menu, 1);//修改库存
			}
			D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_rollback' => 1));
		}

		D("Merchant_store_shop")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);
		//退款时销量回滚

		$go_refund_param['error_code'] = false;
		return $go_refund_param;
	}

	/**
	 * 快店评论
	 */
	public function shop_feedback()
	{
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])));

		if (empty($now_order)) {
			$this->error_tips(L('_B_MY_NOORDER_！'));
		}
		if (empty($now_order['paid'])) {
			$this->error_tips(L('_B_MY_NOTCONSUMENOCOMMENT_'));
		}
		if ($now_order['status'] < 2) {
			$this->error_tips('当前订单未消费！无法评论');
		}
		if ($now_order['status'] == 3) {
			$this->error_tips(L('_B_MY_HAVECOMMENTD_'));
		}

		if (isset($now_order['info'])) {
			$list = array();
			$goods_ids = array();
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goods_ids)) {
					$goods_ids[] = $row['goods_id'];
					$list[] = $row;
				}
			}
			$now_order['info'] = $list;
		}

		$this->assign('now_order', $now_order);
        $c_title = replace_lang_str(L('V3_ORDER_REVIEW_DELIVERY'), $now_order['deliver_user_info']['name']);
        $s_title = replace_lang_str(L('V3_ORDER_REVIEW_STORE'), $now_order['site_name']);
        $this->assign('c_title', $c_title);
        $this->assign('s_title', $s_title);
		$this->display();
	}

	public function add_comment()
	{

		if(empty($this->user_session)){
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_LOGINFIRST_'))));
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}

		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		//$goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : 0;

		$score_send = isset($_POST['send']) ? $_POST['send'] : 5;       //快递星级
        $score_whole = isset($_POST['whole']) ? $_POST['whole'] : 5;    //商铺星级

		$comment_send = isset($_POST['send_textArea']) ? htmlspecialchars($_POST['send_textArea']) : 0;
        $comment_whole = isset($_POST['whole_textArea']) ? htmlspecialchars($_POST['whole_textArea']) : 0;

		$now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $order_id));

		if (empty($now_order)) {
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_NOORDER_'))));
		}
		if (empty($now_order['paid'])) {
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_NOTCONSUMENOCOMMENT_'))));
		}
		if ($now_order['status'] < 2) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单未消费！无法评论')));
		}
		if ($now_order['status'] == 3) {
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_HAVECOMMENTD_'))));
		}

		$goodsids = array();
		$goods = 'a';
		$pre = '';
		if (isset($now_order['info'])) {
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goodsids)) {
					$goodsids[] = $row['goods_id'];
					if (in_array($row['goods_id'], $goods_ids)) {
						$goods .= $pre . $row['name'];
						$pre = '#@#';
					}
				}
			}
		}
		$database_reply = D('Reply');
		$data_reply['parent_id'] = $now_order['store_id'];
		$data_reply['store_id'] = $now_order['store_id'];
		$data_reply['mer_id'] = $now_order['mer_id'];

        $data_reply['order_type'] = 3;
		$data_reply['order_id'] = intval($now_order['order_id']);
		$data_reply['anonymous'] = 1;

        $data_reply['uid'] = $this->user_session['uid'];
		$data_reply['pic'] = '';
		$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_reply['add_ip'] = get_client_ip(1);
		$data_reply['goods'] = $goods;

		//商铺星级
        $data_reply['score'] = $score_whole;
        $data_reply['score_store'] = $score_whole;
        //快递星级
        $data_reply['score_deliver'] = $score_send;
        //评论内容
        $data_reply['comment'] = $comment_whole;
        $data_reply['comment_deliver'] = $comment_send;

		//只把英文翻译成中文
        if(!checkEnglish($comment_whole) && trim($comment_whole) != ''){
            $data_reply['comment_en'] = translationCnToEn($comment_whole);
        }else{
            $data_reply['comment_en'] = '';
        }
        if(!checkEnglish($comment_send) && trim($comment_send) != ''){
            $data_reply['comment_deliver_en'] = translationCnToEn($comment_send);
        }else{
            $data_reply['comment_deliver_en'] = '';
        }

// 		echo "<pre/>";
 		//print_r($data_reply);die;

		if ($database_reply->data($data_reply)->add()) {

			D('Merchant_store')->setInc_shop_reply($now_order['store_id'], $score_whole);
			D('Shop_order')->change_status($now_order['order_id'], 3);
			D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
			foreach ($goods_ids as $goods_id) {
				if (in_array($goods_id, $goodsids)) {
					D('Shop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
					D('Shop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->save(array('is_goods' => 1));
				}
			}

// 			$database_merchant_score = D('Merchant_score');
// 			$now_merchant_score = $database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['mer_id'],'type'=>'1'))->find();
// 			if(empty($now_merchant_score)){
// 				$data_merchant_score['parent_id'] = $now_order['mer_id'];
// 				$data_merchant_score['type'] = '1';
// 				$data_merchant_score['score_all'] = $score;
// 				$data_merchant_score['reply_count'] = 1;
// 				$database_merchant_score->data($data_merchant_score)->add();
// 			}else{
// 				$data_merchant_score['score_all'] = $now_merchant_score['score_all']+$score;
// 				$data_merchant_score['reply_count'] = $now_merchant_score['reply_count']+1;
// 				$database_merchant_score->where(array('pigcms_id'=>$now_merchant_score['pigcms_id']))->data($data_merchant_score)->save();
// 			}
// 			$now_store_score=$database_merchant_score->field('`pigcms_id`,`score_all`,`reply_count`')->where(array('parent_id'=>$now_order['store_id'],'type'=>'2'))->find();
// 			if(empty($now_store_score)){
// 				$data_store_score['parent_id'] = $now_order['store_id'];
// 				$data_store_score['type'] = '2';
// 				$data_store_score['score_all'] = $score;
// 				$data_store_score['reply_count'] = 1;
// 				$database_merchant_score->data($data_store_score)->add();
// 			}else{
// 				$data_store_score['score_all'] = $now_store_score['score_all']+$score;
// 				$data_store_score['reply_count'] = $now_store_score['reply_count']+1;
// 				$database_merchant_score->where(array('pigcms_id'=>$now_store_score['pigcms_id']))->data($data_store_score)->save();
// 			}

			if($this->config['feedback_score_add']>0){
			  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['shop_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].$this->config['score_name']);
			  	D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],L('_B_MY_USER_').$this->user_session['nickname'].date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).L('_B_MY_COMMENT_').$this->config['shop_alias_name'].L('_B_MY_GET_').$this->config['feedback_score_add'].$this->config['score_name']);
			}
			exit(json_encode(array('status' => 1, 'msg' => L('_B_MY_COMMENTACCESS_'),  'url' => U('My/shop_order_list'))));
			//$this->success_tips(L('_B_MY_COMMENTACCESS_'), U('My/shop_order_list'));
		}else{
            //die("111");
        }
	}

	public function refund_back()
	{
		import('@.ORG.pay.Unionpay');

		$pay_class = new Unionpay('', '', 'unionpay', $pay_method['unionpay']['config'], '', 1);
		$get_pay_param = $pay_class->return_url();
		if ($get_pay_param['error']) {
			//TODO 退款失败的操作
		}
	}
	/*全部订餐订单列表*/
	public function foodshop_order_list()
	{
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;

		$where['uid'] = $this->user_session['uid'];
		$where['is_del'] = 0;
		if ($status != -1) {
			$where['status'] = $status;
		}


		$order_list = D("Foodshop_order")->get_order_list($where, 'order_id DESC', 0);
		$order_list = $order_list['order_list'];

		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}
		foreach ($list as $key => $val) {
			if ($val['status'] <= 1 || $val['status'] == 5) {
				$list[$key]['order_url'] = U('Foodshop/book_success', array('order_id' => $val['order_id']));
			} else {
				$list[$key]['order_url'] = U('Foodshop/order_detail', array('order_id' => $val['order_id']));
			}
		}
		$this->assign('order_list', $list);
		$this->display();
	}
	# 删除餐饮订单
	public function ajax_foodshop_order_del(){
		$database_shop_order = D('Foodshop_order');
		$now_order = $database_shop_order->get_order_by_id($this->user_session['uid'],intval($_GET['order_id']));

		if(empty($now_order)){
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_NOORDER_'))));
		}

		$order_condition['order_id'] = $now_order['order_id'];
		$data['is_del'] = 1;
		if($database_shop_order->where($order_condition)->data($data)->save()){
			exit(json_encode(array('status'=>1,'msg'=>L('_B_MY_DELACCESS_'))));
		}else{
			exit(json_encode(array('status'=>0,'msg'=>L('_B_MY_DELLOSE_'))));
		}
	}
	public function ajax_foodshop_order_list()
	{
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;

		$where['uid'] = $this->user_session['uid'];
		$where['is_del'] = 0;
		if ($status != -1) {
			$where['status'] = $status;
		}


		$order_list = D("Foodshop_order")->get_order_list($where, 'order_id DESC', 0);
		$order_list = $order_list['order_list'];

		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$list[] = array_merge($ol, $m[$ol['store_id']]);
			} else {
				$list[] = $ol;
			}
		}

		foreach($list as $key => $val){
			if ($val['status'] <= 1) {
				$list[$key]['order_url'] = U('Foodshop/book_success', array('order_id' => $val['order_id']));
			} else {
				$list[$key]['order_url'] = U('Foodshop/order_detail', array('order_id' => $val['order_id']));
			}
		}
		if (!empty($list)) {
			exit(json_encode(array('status' => 1, 'order_list' => $list)));
		} else {
			exit(json_encode(array('status' => 0, 'order_list' => $list)));
		}
	}
	/**
	 * 快店评论
	 */
	public function foodshop_feedback()
	{
		if(empty($this->user_session)){
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$now_order = D('Foodshop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => intval($_GET['order_id'])));


		if (empty($now_order)) {
			$this->error_tips(L('_B_MY_NOORDER_'));
		}

		if ($now_order['status'] < 3) {
			$this->error_tips('当前订单未消费！无法评论');
		}
		if ($now_order['status'] == 4) {
			$this->error_tips(L('_B_MY_HAVECOMMENTD_'));
		}

		if (isset($now_order['info'])) {
			$list = array();
			$goods_ids = array();
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goods_ids) && empty($row['is_must'])) {
					$goods_ids[] = $row['goods_id'];
					$list[] = $row;
				}
			}
			$now_order['info'] = $list;
		}

		$this->assign('now_order', $now_order);
		$this->display();
	}

	public function foodshop_comment()
	{
		if(empty($this->user_session)){
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_LOGINFIRST_'))));
			$this->error_tips(L('_B_MY_LOGINFIRST_'));
		}
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$goods_ids = isset($_POST['goods_ids']) ? $_POST['goods_ids'] : 0;
		$score = isset($_POST['whole']) ? $_POST['whole'] : 5;
		$comment = isset($_POST['textAre']) ? htmlspecialchars($_POST['textAre']) : 0;
		if (empty($comment)) {
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_COMMENTNOTHING_'))));
		}
		$now_order = D('Foodshop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $order_id));

		if (empty($now_order)) {
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_NOORDER_'))));
		}

		if ($now_order['status'] < 3) {
			exit(json_encode(array('status' => 0, 'msg' => '当前订单未消费！无法评论')));
		}
		if ($now_order['status'] == 4) {
			exit(json_encode(array('status' => 0, 'msg' => L('_B_MY_HAVECOMMENTD_'))));
		}


		$goodsids = array();

		$goods = '';
		$pre = '';
		if (isset($now_order['info'])) {
			foreach ($now_order['info'] as $row) {
				if (!in_array($row['goods_id'], $goodsids)) {
					$goodsids[] = $row['goods_id'];
					if (in_array($row['goods_id'], $goods_ids)) {
						$goods .= $pre . $row['name'];
						$pre = '#@#';
					}
				}
			}
		}
		$database_reply = D('Reply');
		$data_reply['parent_id'] = $now_order['store_id'];
		$data_reply['store_id'] = $now_order['store_id'];
		$data_reply['mer_id'] = $now_order['mer_id'];
		$data_reply['score'] = $score;
		$data_reply['order_type'] = 4;//新的餐饮
		$data_reply['order_id'] = intval($now_order['order_id']);
		$data_reply['anonymous'] = 1;
		$data_reply['comment'] = $comment;
		$data_reply['uid'] = $this->user_session['uid'];
		$data_reply['pic'] = '';
		$data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
		$data_reply['add_ip'] = get_client_ip(1);
		$data_reply['goods'] = $goods;
		if ($database_reply->data($data_reply)->add()) {
			D('Merchant_store')->setInc_foodshop_reply($now_order['store_id'], $score);
			D('Foodshop_order')->change_status($now_order['order_id'], 4);
// 			D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
			foreach ($goods_ids as $goods_id) {
				if (in_array($goods_id, $goodsids)) {
					D('Foodshop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
					D('Foodshop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->save(array('is_goods' => 1));
				}
			}
			if($this->config['feedback_score_add']>0){
			  	D('User')->add_extra_score($this->user_session['uid'],$this->config['feedback_score_add'],$this->config['meal_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].$this->config['score_name']);
			  	D('Scroll_msg')->add_msg('feedback',$this->user_session['uid'],L('_B_MY_USER_').$this->user_session['nickname'].date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).L('_B_MY_COMMENT_').$this->config['meal_alias_name'].L('_B_MY_GET_').$this->config['feedback_score_add'].$this->config['score_name']);
			}
			exit(json_encode(array('status' => 1, 'msg' => L('_B_MY_COMMENTACCESS_'),  'url' => U('Foodshop/order_detail', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])))));
		}
	}
	# 公共接口
	public function user_sessions(){
		if(empty($this->user_session)){
			$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
			redirect(U('Login/index',$location_param));
		}
	}
	public function user_sessions_json(){
		if(empty($this->user_session)){
			$this->returnCode('20020008');
		}
	}
	# 车主实名认证
	public function car_owner(){
		$find	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		if($find){
			$image_class = new scenic_image();
			$find['authentication_img'] = $image_class->get_car_by_path($find['authentication_img'],$this->config['site_url'],'authentication_car','s');
			$find['authentication_back_img'] = $image_class->get_car_by_path($find['authentication_back_img'],$this->config['site_url'],'authentication_car','s');
			$find['drivers_license'] = $image_class->get_car_by_path($find['drivers_license'],$this->config['site_url'],'authentication_car','s');
			$find['driving_license'] = $image_class->get_car_by_path($find['driving_license'],$this->config['site_url'],'authentication_car','s');
		}
		$this->assign('find',$find);
		$this->display();
	}
	# 申请车主认证
	public function car_apply(){
		$plate_number	=	$this->plate_number();
		$find	=	M('User_authentication_car')->field(true)->where(array('uid'=>$this->user_session['uid']))->order('add_time DESC')->find();
		if($find){
			if($find['status'] == 2){
				$find	=	0;
			}
		}
		$this->assign('plate_number',$plate_number);
		$this->assign('find',$find);
		$this->display();
	}
	public function car_apply_json(){
		$_POST['uid']	=	$this->user_session['uid'];
		$_POST['add_time']	=	$_SERVER['REQUEST_TIME'];
		$add	=	M('User_authentication_car')->data($_POST)->add();
		if($add){
			$this->returnCode(0,U('car_apply'));
		}else{
			$this->returnCode('20046028');
		}
	}
	# 车牌
	public function plate_number(){
		$arr	=	array(
			array('id'=>1,'name'=>'北京','front'=>'京'),
			array('id'=>2,'name'=>'天津','front'=>'津'),
			array('id'=>3,'name'=>'上海','front'=>'沪'),
			array('id'=>4,'name'=>'重庆','front'=>'渝'),
			array('id'=>5,'name'=>'内蒙古自治区','front'=>'蒙'),
			array('id'=>6,'name'=>'维吾尔自治区','front'=>'新'),
			array('id'=>7,'name'=>'西藏自治区','front'=>'藏'),
			array('id'=>8,'name'=>'宁夏回族自治区','front'=>'宁'),
			array('id'=>9,'name'=>'广西壮族自治区','front'=>'桂'),
			array('id'=>10,'name'=>'香港特别行政区','front'=>'港'),
			array('id'=>11,'name'=>'澳门特别行政区','front'=>'澳'),
			array('id'=>12,'name'=>'黑龙江省','front'=>'黑'),
			array('id'=>13,'name'=>'吉林省','front'=>'吉'),
			array('id'=>14,'name'=>'辽宁省','front'=>'辽'),
			array('id'=>15,'name'=>'山西省','front'=>'晋'),
			array('id'=>16,'name'=>'河北省','front'=>'冀'),
			array('id'=>17,'name'=>'青海省','front'=>'青'),
			array('id'=>18,'name'=>'山东省','front'=>'鲁'),
			array('id'=>19,'name'=>'河南省','front'=>'豫'),
			array('id'=>20,'name'=>'江苏省','front'=>'苏'),
			array('id'=>21,'name'=>'安徽省','front'=>'皖'),
			array('id'=>22,'name'=>'浙江省','front'=>'浙'),
			array('id'=>23,'name'=>'福建省','front'=>'闽'),
			array('id'=>24,'name'=>'江西省','front'=>'赣'),
			array('id'=>25,'name'=>'湖南省','front'=>'湘'),
			array('id'=>26,'name'=>'湖北省','front'=>'鄂'),
			array('id'=>27,'name'=>'广东省','front'=>'粤'),
			array('id'=>28,'name'=>'海南省','front'=>'琼'),
			array('id'=>29,'name'=>'甘肃省','front'=>'甘'),
			array('id'=>30,'name'=>'陕西省','front'=>'陕'),
			array('id'=>31,'name'=>'贵州省','front'=>'黔'),
			array('id'=>32,'name'=>'云南省','front'=>'滇'),
			array('id'=>33,'name'=>'四川省','front'=>'川'),
		);
		return $arr;
	}
	/* 图片上传 */
    public function ajaxWebUpload(){
		if ($_FILES['file']['error'] != 4) {
        	$width = '900,450';
        	$height = '500,250';
			$param = array('size' => 2);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->user_session['uid'], 'authentication_car', 1, $param);
			if ($image['error']) {
				exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
			} else {
				exit(json_encode(array('error' => 0, 'url' => $image['url']['file'], 'title' => $image['title']['file'])));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
    }

	public function group_recive_confirm()
	{
		$order_id = $_GET['order_id'];
		$condition_group_order['order_id'] = $order_id;
		$now_order = M('Group_order')->where($condition_group_order)->find();

		if ($now_order['paid'] == 1 && $now_order['status'] == 0) {
			$data_group_order['status'] = 1; //原来是1
			$data_group_order['use_time'] = time();
		}


		if (M('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {

			$now_user = D('User')->get_user($now_order['uid'],'uid');
			$express_nmae = D('Express')->get_express($now_order['express_type']);
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=group_order_list';
			$model->sendTempMsg('TM00017', array('href' => $href,
					'wecha_id' => $now_user['openid'],
					'first' => $this->config['group_alias_name'].L('_B_MY_KUAIDISEND_'),
					'OrderSn' => $now_order['real_orderid'],
					'OrderStatus' =>$this->staff_session['name'].L('_B_MY_HAVESENDFORYOU_'),
					'remark' =>'快递号：'.$now_order['order_id'].'('.$express_nmae['name'].L('_B_MY_CONFIRMSOON_')));

			D('Group')->group_notice($now_order,1);

			$this->success_tips(L('_B_MY_ORDERCHAGEACCESS1_'),U('My/group_order',array('order_id'=>$order_id)));
		}else{

			$this->error_tips(L('_B_MY_ORDERCHANGELOSE1_'),U('My/group_order',array('order_id'=>$order_id)));
		}

	}


	/*
	 * 签到功能
	 * */
	public  function sign(){
		$return = D('User')->sign_in($this->user_session['uid']);
		if($return['error_code']){
			$this->error_tips($return['msg'],U('Wap/Home/index'));
		}else{
			$this->success_tips($return['msg'],U('Wap/Home/index'));
		}
	}

	public function map(){
		$this->display();
	}


	/*
	 * 分润钱包
	 * */
	public function fenrun_money(){
		$this->display();
	}

	public function fenrun_recharge_money(){

		$fenrun_money = $_POST['fenrn_money'];
		if($fenrun_money <=0 && $fenrun_money>$this->user_session['fenrun_money']){
			$this->error_tips('您输入的分润金额有误');
		}
		$result = D('User')->fenrun_recharge($fenrun_money);
		if($result['error_code']){
			$this->error_tips($result['msg']);
		}else{
			$this->success_tips($result['msg']);
		}
		$this->display();
	}

    public function exchangeCode(){
	    if($_POST) {

            $code = $_POST['code'];
            $uid = $this->user_session['uid'];
            $order_id=$_POST['order_id'];
            $order=D('Shop_order')->field("goods_price")->where(array('order_id' => $order_id))->find();
            $order_price=$order['goods_price']; // order  subtotal
           // die($order);
            $coupon = D('System_coupon')->field(true)->where(array('notice' => $code))->find();
            //var_dump($coupon);die();
            $cid = $coupon['coupon_id'];
            $order_money =$coupon['order_money'];

            //die($order_price." >>>>>>>> ".$order_money);

            if ($cid) {

                $l_id = D('System_coupon_hadpull')->field(true)->where(array('uid' => $uid, 'coupon_id' => $cid))->find();
                if ($l_id == null) {    //之前没有领用过
                    $result = D('System_coupon')->had_pull($cid, $uid);
                    //var_dump($result);die();
                    if ($result['error_code']==0){ //兑换成功
                        if ($order_price!="" && ($order_price<$order_money)){         //新加的优惠券当前订单不可用
                            exit(json_encode(array('error_code' => 2, 'msg' => L('_AL_EXCHANGE_CANTUSER_CODE_'))));   //当前订单不可用
                        }else{
                            //echo json_encode($result);                            //当前订单可用
                            $now_order=D('Shop_order')->field(true)->where(array('order_id' => $order_id))->find();

                            //if ((float)$now_order['delivery_discount']>0 && $now_order['delivery_discount_type']==0){
                            if (((float)$now_order['delivery_discount']>0 && $now_order['delivery_discount_type']==0)||
                                ((float)$now_order['merchant_reduce']>0 && $now_order['merchant_reduce_type']==0)){
                                //那么就要提示用户，互斥提示
                                exit(json_encode(array('error_code' => 98,'sysc_id'=>$result['coupon']['id'], 'msg' => L('_AL_EXCHANGE_CANUSER_CODE_'))));
                            }else{
                                //否则，随便用户使用优惠券
                                exit(json_encode(array('error_code' => 99,'sysc_id'=>$result['coupon']['id'], 'msg' => L('_AL_EXCHANGE_CANUSER_CODE_'))));
                            }

                        }
                    }else{
                        echo json_encode($result);
                    }
                }else
                    exit(json_encode(array('error_code' => 1, 'msg' => L('_AL_EXCHANGE_CODE_'))));
            } else {
                exit(json_encode(array('error_code' => 1, 'msg' => L('_NOT_EXCHANGE_CODE_'))));
            }
        }else{
	        $this->display();
        }
    }

    public function ajax_city_name(){
        $city_name = $_POST['city_name'];
        //$where = array('area_name'=>$city_name,'area_type'=>2);
        //$area = D('Area')->where($where)->find();

        $city_id = 0;
        $area_list = D('Area')->where(array('area_type'=>2))->select();
        foreach ($area_list as $city){
            $city_arr = explode("|",$city['area_ip_desc']);
            if(in_array($city_name,$city_arr)){
                $city_id = $city['area_id'];
            }
        }

        $data = array();
        //if($area){
        $data['area_id'] = 0;
        $data['city_id'] = $city_id;
        $data['province_id'] = 0;

        $return['error'] = 0;
        //}else{
        //    $return['error'] = 1;
        //}
        $return['info'] = $data;
        exit(json_encode($return));
    }

    public function order_track(){
	    $order_id = $_GET['order_id'];
	    $supply = D('Deliver_supply')->where(array('order_id'=>$order_id))->find();
	    if($supply['uid']){
	        $deliver = D('Deliver_user')->where(array('uid'=>$supply['uid']))->find();
	        $this->assign('deliver',$deliver);
	        $this->display();
        }
    }

    public function getDeliver(){
	    if($_POST['deliver_id']){
	        $uid = $_POST['deliver_id'];
	        $deliver = D('Deliver_user')->where(array('uid'=>$uid))->find();
	        $data['lat'] = $deliver['lat'];
	        $data['lng'] = $deliver['lng'];
            $this->success('Success',$data);
        }
    }

    public function invitation(){
//        $user = D('User')->where(array('uid'=>$this->user_session['uid']))->find();
//
//        if($user['invitation_code'] == '') {
//            $code = $this->getInvitationCode(6);
//            $data['invitation_code'] = $code;
//            D('User')->where(array('uid'=>$this->user_session['uid']))->save($data);
//        }else {
//            $code = $user['invitation_code'];
//        }
        $code = D('User')->getUserInvitationCode($this->user_session['uid']);

        $link = C('config.site_url')."/invite/".base64_encode($code);
        //$url_str = base64_encode($code);

        //var_dump(strtoupper($code));
        $this->assign('code',strtoupper($code));
        $this->assign('link',$link);

        //获取邀请活动是否存在
        $event_list = D('New_event')->getEventList(1,2);
        if($event_list){
            $event = reset($event_list);
            $event['name'] = lang_substr($event['name'],C('DEFAULT_LANG'));
            $event['desc'] = lang_substr($event['desc'],C('DEFAULT_LANG'));
            $this->assign('event',$event);

            $msg = $this->user_session['nickname']." invites you to order delivery from Tutti! Sign up using your code ".strtoupper($code)." or the link below to get $".$event['coupon_amount']." in coupons when you place your first order! (".$link.")";
            $this->assign('send_msg',$msg);
        }

        $this->display();
    }

    function send_email_invi(){
        $where = array('tab_id'=>'gmail','gid'=>42);
        $result = D('Config')->field(true)->where($where)->find();
        $password = $result['value'];
        
        $address = $_POST['address'];
        $code = $_POST['code'];
        $link = $_POST['link'];
        $coupon_amount = $_POST['amount'];
        $title = $this->user_session['nickname']." sent you coupons!";
        //$body = "Looking for delivery services of your favourite restaurants? ".$this->user_session['nickname']." invites you to order with Tutti Delivery! Sign up using your code or the link below to get $".$coupon_amount." in coupons when you place your first order!";
        //$body .= "<br><br><a href='".$link."' style='font-size: 18px;'>Sign Up Here</a>";
        //$body .= "<br><br>Your code is ".$code;
        //$body .= "<br><br>Term may apply";

        $body = '<table style="width: 98%; position: relative;margin: 0 auto">
                    <tr>
                        <td>
                            <img src="'.C('config.site_url').'/tpl/Static/blue/images/new/mail_back.png" style="width: 100%;"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Looking for delivery services of your favourite restaurants? '.$this->user_session['nickname'].' invites you to order with Tutti Delivery! Sign up using code '.$code.' or the link below to get $'.$coupon_amount.' in coupons when you place your first order!
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="invi_btn" style="width: 40%;height: 50px;margin: 20px auto;border-radius: 5px;background-color: #ffa52d;line-height: 50px;text-align: center;">
                                <a href="'.$link.'" style="color: white;text-decoration: none;display: block;font-size: 18px;">
                                    SIGN UP HERE
                                </a>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="width: 100%;background-color: #cccccc;padding:2% 2%;color: #333333;font-size: 12px;">
                            <div>
                                * This offer is valid for new users only.
                            </div>
                            <div>
                                * Minimum purchase is required and may very from different coupons.
                            </div>
                            <div>
                                * Only one coupon can be used for each order.
                            </div>
                
                            <div style="margin-top: 120px; font-size: 10px; text-align: center">
                                © 2019 Kavl Technology Ltd.All rights reserved
                            </div>
                        </td>
                    </tr>
                </table>';

        require './mailer/PHPMailer.php';
        require './mailer/SMTP.php';
        require './mailer/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->CharSet ='UTF-8';
        $mail->Encoding = "base64";

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'donotreply.tutti@gmail.com';                    // SMTP username 这里改成自己的gmail邮箱，最好新注册一个，因为后期设置会导致安全性降低
        $mail->Password = $password;                 // SMTP password 这里改成对应邮箱密码
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;


        $mail->setFrom('donotreply.tutti@gmail.com', 'Tutti');
        $mail->addAddress($address, $address);

        $mail->isHTML(true);
        $subject = "=?UTF-8?B?".base64_encode($title)."?=";
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = '';

        if($mail->send())
            exit(json_encode(array('status' => 1, 'msg' => "Success")));
        else
            exit(json_encode(array('status' => 0, 'msg' => $mail->ErrorInfo)));
    }

    function send_sms_invi(){
        //var_dump($_POST);
        $address = $_POST['address'];

        $user_name = $this->user_session['nickname'];
        $code = $_POST['code'];
        $link = $_POST['link'];
        $coupon_amount = '$'.$_POST['amount'];

        $sms_data['uid'] = 0;
        $sms_data['mobile'] = $address;
        $sms_data['sendto'] = 'user';
        $sms_data['tplid'] = 407667;
        $sms_data['params'] = [
            $user_name,
            $code,
            $coupon_amount,
            $link
        ];
        //Sms::sendSms2($sms_data);
        $sms_txt = $user_name." has invited you to order delivery from Tutti! Sign up using the code ".$code." or follow the link below to get ".$coupon_amount." in coupons after you place your first order! (".$link.")";
        //Sms::telesign_send_sms($address,$sms_txt,0);
        Sms::sendTwilioSms($address,$sms_txt);

        exit(json_encode(array('status' => 1, 'msg' => "Success")));

    }

    public function privacy(){
        $this->display();
    }

}
?>