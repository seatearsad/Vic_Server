<?php
class SystemcouponAction extends BaseAction{
	public function index(){
		$coupon  = D('System_coupon');
		$coupon_list = $coupon->get_coupon_list();
		if(!empty($this->user_session)) {
			$now_user_coupon  = $coupon->get_coupon_category_by_phone($this->user_session['uid']);
			foreach($now_user_coupon as $v){
				if(!empty($coupon_list[$v['coupon_id']])) {
					$coupon_list[$v['coupon_id']]['selected'] = 1;
				}
			}
		}
		$category = array(
				'all' => '通用券',
				'group' => C('config.group_alias_name'),
				'meal' => C('config.meal_alias_name'),
				'appoint' => C('config.appoint_alias_name'),
				'shop' => C('config.shop_alias_name'),
				'store' => '优惠买单',
		);
		if(empty($this->config['appoint_page_row'])){
			unset($category['appoint']);
		}
		$platform = array('wap' => '移动网页', 'app' => 'App', 'weixin' => '微信');
		foreach ($coupon_list as $vv) {
			if($vv['status']==2 && $vv['last_time']<$_SERVER['REQUEST_TIME']-86400)
				continue;
			$vv['platform'] = unserialize($vv['platform']);
			$tmp_platform = '';
			foreach ($vv['platform'] as $vt) {
				$tmp_platform .= $platform[$vt] . '/';
			}
			$vv['platform'] = substr($tmp_platform, 0, -1);
			$tmp[$vv['cate_name']][] = $vv;
		}

		foreach ($category as $k => $c) {
			if (empty($tmp[$k])) {
				$tmp[$k] = array();
				$category_tmp[$k]['count'] = 0;
			}else{
				$category_tmp[$k]['count'] = count($tmp[$k]);
			}
		}

		arsort($category_tmp);
		$max_category = array_keys($category_tmp);
		$this->assign('max_category', $max_category[0]);
		$this->assign('category', $category);
		$this->assign('category_tmp', $category_tmp);
		$this->assign('isnew', D('User')->check_new($this->user_session['uid'],'all'));
		$this->assign('coupon_list',$tmp);
		$this->display();
	}

	public function show(){
		if(empty($_GET['coupon_id'])){
			$this->error_tips('没有相关优惠券！');
		}else{
			if(!empty($this->user_session)&&!empty($this->user_session['phone'])){
				unset($_SESSION['unlogin_user_coupon']['phone']);
				$this->assign('phone',$this->user_session['phone']);
				$phone = $this->user_session['phone'];
				$has_get = D('System_coupon')->get_coupon_count_by_phone($_GET['coupon_id'],$this->user_session['phone']);
				if($has_get==0&&!isset($_GET['type'])){
					$res = $this->had_pull($_GET['coupon_id'],$phone);
					$has_get = $res['coupon']['has_get'];
					$msg = $this->coupon_error($res['error_code']);
					$this->assign('msg',$msg['msg']);
				}
			}else if(!empty($_SESSION['unlogin_user_coupon']['phone'])){
				$this->assign('unlogin_phone',$_SESSION['unlogin_user_coupon']['phone']);
				$phone = $_SESSION['unlogin_user_coupon']['phone'];
				$has_get = D('System_coupon')->get_coupon_count_by_phone($_GET['coupon_id'],$_SESSION['unlogin_user_coupon']['phone']);
				if($has_get>1){
					$this->error_tips('您已经拥有了该优惠券，请先进行登录再领取！',U('Login/index'));
				}
			}
			$_SESSION[$phone]['browse_coupon_count']+=1;
			$coupon = D('System_coupon')->get_coupon($_GET['coupon_id']);
			if($coupon['status']==2){
				$msg = $this->coupon_error(2);
				$coupon['url'] = $this->config['site_url'].'/wap.php';
				$this->error_tips($msg['msg'],$coupon['url']);
			}
			if(!empty($phone)){
				$is_new = D('User')->check_new($phone,$coupon['cate_name']);
				$this->assign('is_new',$is_new);
			}
			$coupon['has_get']=$has_get;
			if($coupon['status']!=1&&!isset($_GET['type'])){
				$msg = $this->coupon_error($res['error_code']);
				$this->error_tips($msg['msg']);
			}
			$coupon['des_detial']=explode(PHP_EOL,$coupon['des_detial']);
			if(($coupon['num']-$coupon['had_pull'])>($coupon['limit']-$has_get)){
				if($coupon['limit']-$has_get>0){
					$coupon['can_get'] = $coupon['limit']-$has_get;
				}else{
					$coupon['can_get']=0;
				}
			}else{
				if($coupon['num']-$coupon['had_pull']>0){
					$coupon['can_get'] = $coupon['num']-$coupon['had_pull'];
				}else{
					$coupon['can_get'] = 0;
				}
			}
			$coupon['cate_id']=unserialize($coupon['cate_id']);
			switch($coupon['cate_name']){
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
			$coupon['url'] = $url;
			$this->assign('coupon',$coupon);
			$this->assign('browse_coupon_count',$_SESSION[$phone]['browse_coupon_count']);
		}
		$this->display();
	}

	public function coupon_error($error_code){
		switch($error_code){
			case 0:
				return array("msg"=>"领取成功！");
			break;
			case 1:
				return array("msg"=>"领取失败！");
				break;
			case 2:
				return array("msg"=>"该优惠券已过期");
				break;
			case 3:
				return array("msg"=>"该优惠券已经被领完了！");
				break;
			case 4:
				return array("msg"=>"该优惠券只能新用户领取！");
				break;
		}
	}

	//领取优惠券
	public function had_pull()
	{
		$coupon_id = $_POST['coupon_id'];
		$uid = $this->user_session['uid'];
		if (empty($this->user_session)) {
			echo json_encode(array('error_code' => 6, 'msg' => '未登录'));
			die;
		}elseif(empty($this->user_session['phone'])){
			echo json_encode(array('error_code' => 7, 'msg' => '未绑定手机号码'));
		}
		$model = D('System_coupon');
		$has_get = $model->get_coupon_count_by_uid($coupon_id, $uid);
		$return['has_get'] = $has_get;

		$result = $model->had_pull($coupon_id, $uid);
		$model->decrease_sku(0,1,$coupon_id);//网页领取完，微信卡券库存需要同步减少

		if ($result['error_code'] != 0) {
			switch ($result['error_code']) {
				case '1':
					$error_msg = '领取失败';
					break;
				case '2':
					$error_msg = '优惠券已过期';
					break;
				case '3':
					$error_msg = '优惠券已经领完了';
					break;
				case '4':
					$error_msg = '只允许新用户领取';
					break;
				case '5':
					$error_msg = '不能再领取了';
					break;
			}
			echo json_encode(array('error_code' => $result['error_code'], 'msg' => $error_msg));
			die;
		}

		echo json_encode(array('error_code' => 0, 'msg' => '领取成功', 'coupon' => $result['coupon']));
		die;

	}

	public function had_pull_($coupon_id,$phone){
		if(IS_POST){
			if(empty($_POST['coupon_id'])){
				$return = array('error_code'=>1);
				$this->ajaxReturn($return);exit;
			}else{

				$coupon_id = $_POST['coupon_id'];
				$phone = $_POST['phone'];
			}
		}
		$coupon = D('System_coupon');
		$has_get = $coupon->get_coupon_count_by_phone($coupon_id,$phone);

		$return['phone']=$_POST['phone'];
		$return['has_get'] =$has_get;
		if((empty($this->user_session)||$this->user_session['phone']!=$phone)&&$has_get>=1){
			unset($_SESSION['unlogin_user_coupon']['phone']);
			$return['login'] = 0;
			$this->ajaxReturn($return);exit;
		}else{
			$return['login'] = 1;
		}
		if(empty($this->user_session)) {
			if($_POST['verify_type']=='sms'){
				if ($this->config['bind_phone_verify_sms']!='0'&&!empty($this->config['sms_key'])) {
					if (isset($_POST['verify']) && !empty($_POST['verify'])) {
						$sms_verify_result = D('Smscodeverify')->verify($_POST['verify'], $phone);
						if ($sms_verify_result['error_code']) {
							exit(json_encode(array('error_code' => '1', 'msg' => '验证码不正确！', 'dom_id' => 'verify')));
						}
					}
				}else{
					exit(json_encode(array('error_code' => '1', 'msg' => '短信功能异常！', 'dom_id' => 'verify')));
				}
			}else if($_POST['verify_type']=='nosms'){
				if (md5($_POST['verify']) != $_SESSION['merchant_reg_verify']) {
					exit(json_encode(array('error_code' => '1', 'msg' => '验证码不正确！', 'dom_id' => 'verify')));
				}
			}
		}
		$had_pull_res = $coupon->had_pull($coupon_id,$phone);
		$return  =array_merge($return,$had_pull_res);
		if(!$return['error_code']||$return['error_code']==2){
			$_SESSION['unlogin_user_coupon']['phone']=$_POST['phone'];
		}
		$coupon_info = $return['coupon'];
		if(($coupon_info['num']-$coupon_info['had_pull'])>($coupon_info['limit']-$coupon_info['has_get'])){
			if($coupon_info['limit']-$coupon_info['has_get']>0){
				$return['can_get'] = $coupon_info['limit']-$coupon_info['has_get'];
			}else{
				$return['can_get']=0;
			}
		}else{
			if($coupon_info['num'] - $coupon_info['had_pull']>0){
				$return['can_get'] = $coupon_info['num'] - $coupon_info['had_pull'];
			}else{
				$return['can_get'] = 0;
			}
		}
		if(IS_POST){
			$this->ajaxReturn($return);exit;
		}else{
			return $return;
		}
	}

	public function ajax_check_login(){
		if (empty($this->user_session)) {
			echo json_encode(array('error_code' => 6, 'msg' => '未登录,登录后才能领取'));
		}else{
			echo json_encode(array('error_code' => 0, 'msg' => '已登录'));
		}
		exit;
	}

	public function verify(){
		$verify_type = $_GET['type'];
		if(empty($verify_type)){exit;}
		import('ORG.Util.Image');
		Image::buildImageVerify(4,1,'jpeg',53,26,'merchant_'.$verify_type.'_verify');
	}

}