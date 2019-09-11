<?php
class PayAction extends BaseAction{
	public function check(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index'));
		}
		if(!in_array($_GET['type'],array('group','meal','takeout','food','recharge','appoint', 'shop','gift'))){
			$this->error_tips('订单来源无法识别，请重试。');
		}
		$group_pay_offline = true;
		if($_GET['type'] == 'group'){
			$now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true);
			if($now_order['order_info']['group_share_num']>0||$now_order['order_info']['pin_num']>0)$group_pay_offline=false;
		}else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food'){
			$now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true, $_GET['type']);
			$_GET['type']  = 'meal';
		}else if($_GET['type'] == 'recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true);
		}else if($_GET['type'] == 'appoint'){
			$now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true);
		} elseif ($_GET['type'] == 'shop') {
			$now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']),true);
		}elseif($_GET['type'] == 'gift'){
			$now_order = D('Gift_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']),true);
		}else{
			$this->error_tips('非法的订单');
		}
		if($now_order['error'] == 1){
			if($now_order['url']){
				$this->error_tips($now_order['msg'],$now_order['url']);
			}else{
				$this->error_tips($now_order['msg']);
			}
		}

		//判断线下支付
		$pay_offline = D('Percent_rate')->pay_offline($now_order['mer_id'],$_GET['type']);
		$this->assign('pay_offline',$pay_offline&&$group_pay_offline);

		$order_info = $now_order['order_info'];
		if($this->config['open_extra_price']==1){
			$user_score_use_percent=(float)$this->config['user_score_use_percent'];
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
		}else{
			$order_info['order_extra_price'] = 0;
		}
		$this->assign('order_info',$order_info);
		$leveloff=isset($order_info['leveloff']) && !empty($order_info['leveloff']) ? unserialize($order_info['leveloff']) : false;

		$this->assign('leveloff',$leveloff);
		$now_user = D('User')->get_user($this->user_session['uid']);

		if(empty($now_user)){
			$this->error_tips('未获取到您的帐号信息，请重试！');
		}
		$now_user['now_money'] = floatval($now_user['now_money']);
		$this->assign('now_user',$now_user);

		if($_GET['type'] != 'recharge'){
			$pay_money = $order_info['order_total_money'] - $now_user['now_money'];
		}else{
			$pay_money = $order_info['order_total_money'];
		}

		//使用积分
		$score_can_use_count = 0;
		$score_deducte = 0;
		$user_score_use_percent = (float)$this->config['user_score_use_percent'];
		//定制元宝判断条件
		//if($this->config['open_extra_pirce']!=0&&$order_info['order_extra_price']!=0) {
			if ($_GET['type'] == 'group' || $_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'appoint' || $_GET['type'] == 'shop' || $_GET['type'] == 'gift') {
				$type_ = $_GET['type'];
//			if($order_info['business_type']=='foodshop'){
//				$type_ = 'meal';
//			}
				//$score_config = D('Config')->field('name,value')->where('tab_id="user_score"')->getField('name,value');
				$user_score_use_condition = $this->config['user_score_use_condition'];
				$user_score_max_use = D('Percent_rate')->get_max_core_use($order_info['mer_id'], $type_);;
				//$user_score_max_use=$score_config['user_score_max_use'];

				if ($_GET['type'] == 'group') {
					$group_info = D('Group')->where(array('group_id' => $order_info['group_id']))->find();
					if ($group_info['score_use']) {
						if ($group_info['group_max_score_use'] != 0) {
							$user_score_max_use = $group_info['group_max_score_use'];
						}
					} else {
						$user_score_max_use = 0;
					}
				}

				$score_max_deducte = bcdiv($user_score_max_use, $user_score_use_percent, 2);
				if ($user_score_use_percent > 0 && $score_max_deducte > 0 && $user_score_use_condition > 0 && $now_user['score_count'] > 0) {   //如果设置没有错误
					$total_ = $order_info['order_total_money'];

					if ($total_ >= $user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
						if ($total_ > $score_max_deducte) {                    //判断积分最大抵扣金额是否比这个订单的总额大
							$score_can_use_count = (int)($now_user['score_count'] > $user_score_max_use ? $user_score_max_use : $now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
							//$score_deducte = sprintf("%.2f",substr(sprintf("%.3f", (float)$score_can_use_count/$user_score_use_percent), 0, -2));
							$score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
							$score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
						} else {
							$score_can_use_count = $total_ * $user_score_use_percent > $now_user['score_count'] ? $now_user['score_count'] : $total_ * $user_score_use_percent;
							$score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
							$score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
						}
					}
				}
				if ($_GET['type'] == 'gift') {
					$score_can_use_count = $order_info['total_integral'];
				}

			}
		//}
		$this->assign('score_can_use_count', $score_can_use_count);
		$this->assign('user_score_use_percent', $user_score_use_percent);
		$this->assign('score_deducte', $score_deducte);
		$this->assign('score_count', $now_user['score_count']);

		$this->assign('pay_money',$pay_money);
		//调出支付方式
		$notOnline = intval($_GET['notOnline']);
		if($_GET['type'] != 'recharge' && $_GET['type'] != 'appoint'){
			$notOffline = intval($_GET['notOffline']);
		}else{
			$notOffline = 1;
		}
		
		$now_merchant = D('Merchant')->get_info($order_info['mer_id']);
		if ($now_merchant) {
			$notOffline = ($pay_offline && $now_merchant['is_offline'] == 1) ? 0 : 1;
		}
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				break;
			case '2':
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_merchant['mer_id']))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);

					}
				}
				unset($pay_method['weixin']);
				if(empty($pay_method)){
					$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				}
				break;			
		}

        $store = D('Merchant_store')->field(true)->where(array('store_id'=>$order_info['store_id']))->find();
        $store_pay = explode('|',$store['pay_method']);
        $pay_list = array();
        foreach ($pay_method as $k=>$v){
            if(in_array($k,$store_pay)){
                $pay_method[$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
                $pay_list[$k] = $pay_method[$k];
            }
        }

//		if ($_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad') {
//			$order_table = 'Meal_order';
//		}else if($_GET['type']=='recharge'){
//			$order_table = 'User_recharge_order';
//		}else{
//			$order_table = ucfirst($_GET['type']).'_order';
//		}

//		$nowtime = date("ymdHis");
//		$orderid = $nowtime.rand(10,99).sprintf("%08d",$order_info['order_id']);
//		$save_pay_id = D($order_table)->where(array('order_id'=>$order_info['order_id']))->setField('orderid',$orderid);
//		if(!$save_pay_id){
//			$this->error_tips('更新失败，请联系管理员');
//		}else{
//			$order_info['order_id']=$orderid;
//		}

		if(empty($pay_list)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		$this->assign('pay_method',$pay_list);
		//garfunkel add 获取信用卡
        $card_list = D('User_card')->getCardListByUid($this->user_session['uid']);
        $this->assign('card_list',$card_list);

		$this->display();
	}
	protected function getPayName($label){
		$payName = array(
			'weixin' => '微信支付',
			'tenpay' => '财付通支付',
			'yeepay' => '银行卡支付(易宝支付)',
			'allinpay' => '银行卡支付(通联支付)',
			'chinabank' => '银行卡支付(网银在线)',
		);
		return $payName[$label];
	}
	public function go_pay(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index'));
		}
		if(!in_array($_POST['order_type'],array('group','meal','takeout','food','recharge','appoint','waimai','waimai-recharge', 'shop','gift'))){
			$this->error_tips('订单来源无法识别，请重试。');
		}
		if($_POST['order_type'] == 'group'){
			$now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else if($_POST['order_type'] == 'meal' || $_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food'){
			$now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true, $_POST['order_type']);
		}else if($_POST['order_type'] == 'recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else if($_POST['order_type'] == 'appoint'){
			$now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else if($_POST['order_type'] == 'waimai'){
			$now_order = D('Waimai_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
			if ($now_order['order_info']['pay_type'] !== $_POST['pay_type']) {
				$this->error_tips('非法的订单');
			}
		}else if($_POST['order_type'] == 'waimai-recharge'){
			$now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		} elseif ($_POST['order_type'] == 'shop') {
			$now_order = D('Shop_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);

			if($_POST['pay_type'] == 'offline' && $now_order['order_info']['tip_charge'] != 0)
			    D('Shop_order')->where(array('order_id'=>intval($_POST['order_id'])))->save(array('tip_charge'=>0));
		} elseif ($_POST['order_type'] == 'gift') {
			$now_order = D('Gift_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),true);
		}else{
			$this->error_tips('非法的订单');
		}
		if($now_order['error'] == 1){
			if($now_order['url']){
				$this->error_tips($now_order['msg'],$now_order['url']);
			}else{
				$this->error_tips($now_order['msg']);
			}
		}
		$order_info = $now_order['order_info'];

		//用户信息
		$now_user = D('User')->get_user($this->user_session['uid']);

		if(empty($now_user)){
			$this->error_tips('未获取到您的帐号信息，请重试！');
		}
                
                //判断积分是否够用 防止支付同时积分被改动
		
		if ($_POST['use_score']) {
			if($now_user['score_count']<$_POST['score_used_count']){
				$this->error_tips('账户'.$this->config['score_name'].'不够，请重试！');
			}
			if($this->config['open_extra_price']){

				if(isset($order_info['extra_price']) && $order_info['extra_price']>0 &&$order_info['extra_price']<$_POST['score_deducte']){
					$this->error_tips($this->config['score_name'].'抵扣金额错误！');
				}
			}else{
				if($order_info['order_total_money']<$_POST['score_deducte']){
					$this->error_tips($this->config['score_name'].'抵扣金额错误！');
				}
			}

			$order_info['score_used_count']=$_POST['score_used_count'];
			$order_info['score_deducte']=$_POST['score_deducte'];
		}else{
			$order_info['score_used_count']=0;
			$order_info['score_deducte']=0;
		}

		if($_POST['use_balance']==0||!isset($_POST['use_balance'])){
			$order_info['use_balance'] = 0;
		}else{
			$order_info['use_balance'] = 1;
		}
		//dump($order_info);die;
        //garfunkel add 添加小费记录
        $order_info['tip'] = $_POST['tip'];
        if($_POST['pay_type'] == 'Cash' || $_POST['pay_type'] == 'offline')
            $order_info['tip'] = 0;
        if($order_info['tip'] > 0){
            $order_info['order_total_money'] = $order_info['order_total_money'] + $order_info['tip'];
            $data['tip_charge'] = $order_info['tip'];
            if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
                D('Shop_order')->field(true)->where(array('order_id'=>$order_info['order_id']))->save($data);
            }
        }
		//如果用户存在余额或使用了优惠券，则保存至订单信息。如果金额满足订单总价，则实时扣除并返回支付成功！若不够则不实时扣除，防止用户在付款过程中取消支付
		if($order_info['order_type'] == 'group'){
			$save_result = D('Group_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'meal' || $order_info['order_type'] == 'takeout' || $order_info['order_type'] == 'food'){
			$save_result = D('Meal_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'recharge'){
			$save_result = D('User_recharge_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'appoint'){
			$save_result = D('Appoint_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'waimai'){
			$save_result = D('Waimai_order')->web_befor_pay($order_info,$now_user);
		}else if($order_info['order_type'] == 'waimai-recharge'){
			$save_result = D('User_recharge_order')->web_befor_pay($order_info,$now_user);
		} elseif($order_info['order_type'] == 'shop') {
			$save_result = D('Shop_order')->web_befor_pay($order_info,$now_user);
		}elseif($order_info['order_type'] == 'gift'){
			$save_result = D('Gift_order')->web_befor_pay($order_info,$now_user);
		}
		
		if($save_result['error_code']){
			$this->error($save_result['msg']);exit();
		}else if($save_result['url']){
			$this->assign('jumpUrl',$save_result['url']);
			$this->success($save_result['msg']);exit();
		}
		//需要支付的钱
		$pay_money = $save_result['pay_money'];
		
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		if(in_array($order_info['order_type'],array('group','meal','takeout','food','appoint','waimai','shop')) && $order_info['mer_id']){
			$mer_id = $order_info['mer_id'];
		}else{
			$this->config['merchant_ownpay'] = 0;
		}
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open'] && $ownKey != 'weixin'){	//PC端不使用商家的微信支付
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				break;
			case '2':
				$pay_method = array();
				$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
				foreach($merchant_ownpay as $ownKey=>$ownValue){
					$ownValueArr = unserialize($ownValue);
					if($ownValueArr['open']){
						$ownValueArr['is_own'] = true;
						$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
					}
				}
				unset($pay_method['weixin']);
				if(empty($pay_method)){
					$pay_method = D('Config')->get_pay_method($notOnline,$notOffline);
				}
				break;			
		}
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$_POST['pay_type']])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}
		if($order_info['order_type'] == 'recharge' && $_POST['pay_type'] == 'offline'){
			$this->error_tips('在线充值只能使用在线支付');
		}
		$pay_class_name = ucfirst($_POST['pay_type']);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
		
		//用pay_id代替参数order_id传进在线支付
		$order_id = $order_info['order_id'];
		if ($_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food' || $_POST['order_type'] == 'foodPad') {
			$order_table = 'Meal_order';
        }else if($_POST['order_type']=='recharge'){
			$order_table = 'User_recharge_order';
		}else{
			$order_table = ucfirst($_POST['order_type']).'_order';
		}
		//if($_POST['pay_type']!='offline'){
			$nowtime = date("ymdHis");
			$orderid = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
			$data_tmp['pay_type'] = $_POST['pay_type'];
			$data_tmp['order_type'] = $_POST['order_type'];
			$data_tmp['order_id'] = $order_id;
			$data_tmp['orderid'] = $orderid;
			$data_tmp['addtime'] = $nowtime;
			if(!D('Tmp_orderid')->add($data_tmp)){
				$this->error_tips('更新失败，请联系管理员');
			}
			$save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
			if(!$save_pay_id){
				$this->error_tips('更新失败，请联系管理员');
			}else{
				$order_info['order_id']=$orderid;
			}
		//}
		
		$pay_class = new $pay_class_name($order_info,$pay_money,$_POST['pay_type'],$pay_method[$_POST['pay_type']]['config'],$this->user_session,0);
		$go_pay_param = $pay_class->pay();
		
		if(empty($go_pay_param['error'])){
			if($pay_class_name == 'Weixin' || $pay_class_name == 'Weifutong'){
				$returnArr = array(
					'status'=>1,
					'info'=>$go_pay_param['qrcode'],
					'orderid'=>$orderid,
				);
				$this->header_json();
				echo json_encode($returnArr);
				exit;
			}else if(!empty($go_pay_param['url'])){
				$this->assign('url',$go_pay_param['url']);
			}else if(!empty($go_pay_param['form'])){
				$this->assign('form',$go_pay_param['form']);
			}else{
				$this->error_tips('调用支付发生错误，请重试。');
			}
		}else{
			$this->error_tips($go_pay_param['msg']);
		}
		
		$this->display();
	}
	
	//异步通知
	public function notify_url(){
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$_GET['pay_type']])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}
		
		$pay_class_name = ucfirst($_GET['pay_type']);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
		
		$pay_class = new $pay_class_name('','',$_GET['pay_type'],$pay_method[$_GET['pay_type']]['config'],$this->user_session,0);
		$notify_return = $pay_class->notice_url();
		
		if(empty($notify_return['error'])){

		}else{
			$this->error_tips($notify_return['msg']);
		}
	}
	
	//跳转通知
	public function return_url(){
		$pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method();
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
				}				
				break;
			case '2':
				$pay_method = array();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
					unset($pay_method['weixin']);
					if(empty($pay_method)){
						$pay_method = D('Config')->get_pay_method();
					}
				}
				break;			
		}
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$pay_type])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}

		$pay_class_name = ucfirst($pay_type);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
	
		$pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,0);
		$get_pay_param = $pay_class->return_url();
		$get_pay_param['order_param']['return']=1;

		$offline = $pay_type!='offline'?false:true;
		if(empty($get_pay_param['error'])){
			if($get_pay_param['order_param']['order_type'] == 'group'){
				$now_order = $this->get_orderid('Group_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food'){
				$now_order = $this->get_orderid('Meal_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Meal_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'recharge'){
				$now_order = $this->get_orderid('User_recharge_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'appoint'){
				$now_order = $this->get_orderid('Appoint_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai'){
				$now_order = $this->get_orderid('Waimai_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai-recharge'){
				$now_order = $this->get_orderid('User_recharge_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'shop'){
				$now_order = $this->get_orderid('Shop_order',$get_pay_param['order_param']['order_id'],$offline);
				$get_pay_param['order_param']['order_id']=$now_order['orderid'];
				$pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);			
			}else{
				$this->error_tips('订单类型非法！请重新下单。');
			}
			// if($get_pay_param['order_param']['pay_type'] == 'yeepay' && $pay_info['url']){
				// $pay_info['url'] = str_replace('/source/web_yeepay.php','/index.php',$pay_info['url']);
			// }
			if($pay_info['url']){
				$pay_info['url'] = preg_replace('#/source/(\w+).php#','/index.php',$pay_info['url']);
			}
			$urltype = isset($_GET['urltype']) ? $_GET['urltype'] : '';
			if(empty($pay_info['error'])){
				if($get_pay_param['order_param']['pay_type'] == 'weixin'){
					exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
				} elseif ($get_pay_param['order_param']['pay_type'] == 'baidu') {//百度的异步通知返回
                	exit("<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>");
                } elseif ('unionpay' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
                	exit("验签成功");
                }
				if(!empty($pay_info['url'])){
					$this->assign('jumpUrl',$pay_info['url']);
					$this->success(L('_PAY_SUCCESS_JUMP_'));
					exit();
				}
			}
			if(empty($pay_info['url'])){
				$this->error_tips($pay_info['msg']);
			}else{
                if ($pay_info['error'] && $urltype == 'front') {
                	$this->redirect($pay_info['url']);
                	exit;
                }
				$this->error_tips($pay_info['msg'],$pay_info['url']);
			}
		}else{
			$this->error_tips($get_pay_param['msg']);
		}
	}

	public function get_orderid($table,$orderid,$offline=0){
		$order =  D($table);
		$tmp_orderid = D('Tmp_orderid');
		if($offline){
			$now_order = $order->where(array('orderid'=>$orderid))->find();
		}else{
			$now_order = $order->where(array('orderid'=>$orderid))->find();
			if(empty($now_order)){
				$res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
				$now_order = $order->where(array('order_id'=>$res['order_id']))->find();
				$order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
				$now_order['orderid']=$orderid;
			}
		}
		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}else{
			//$tmp_orderid->where(array('order_id'=>$now_order['order_id']))->delete();
		}
		return $now_order;
	}


	//微信同步回调页面
	public function weixin_back(){
		switch($_GET['order_type']){
			case 'group':
				//$now_order = D('Group_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Group_order',$_GET['order_id']);
				break;
			case 'meal':
			case 'takeout':
			case 'food':
				//$now_order = D('Meal_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
				break;
			case 'recharge':
				//$now_order = D('User_recharge_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('User_recharge_order',$_GET['order_id']);
				break;
			case 'appoint':
				//$now_order = D('Appoint_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Appoint_order',$_GET['order_id']);
				break;
			case 'waimai':
				//$now_order = D('Waimai_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Waimai_order',$_GET['order_id']);
				break;
			case 'shop':
				//$now_order = D('Waimai_order')->where(array('orderid'=>$_GET['order_id']))->find();
				$now_order =$this->get_orderid('Shop_order',$_GET['order_id']);
				break;
			default:
				$this->error_tips('非法的订单');
		}

		if(empty($now_order)){
			$this->error_tips('该订单不存在');
		}
		$now_order['order_type'] = $_GET['order_type'];
		if($now_order['paid']){
			switch($_GET['order_type']){
				case 'group':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
					break;
				case 'recharge':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
					break;
				case 'appoint':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
					break;
				case 'waimai':
					$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
					break;
				case 'shop':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
					break;
			}
			redirect($redirctUrl);exit;
		}else if($_GET['pay_type'] == 'weifutong'){
			$this->error_tips('您还未支付');
		}
		$tmpid = $now_order['order_id'];
		$now_order['order_id']  =   $now_order['orderid'];
		$import_result = import('@.ORG.pay.Weixin');
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		$pay_class = new Weixin($now_order,0,'weixin',$pay_method['weixin']['config'],$this->user_session,1);
		$go_query_param = $pay_class->query_order();
		if($go_query_param['error'] === 0){
			switch($_GET['order_type']){
				case 'group':
					D('Group_order')->after_pay($go_query_param['order_param']);
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					D('Meal_order')->after_pay($go_query_param['order_param']);
					break;
				case 'recharge':
					D('User_recharge_order')->after_pay($go_query_param['order_param']);
					break;
				case 'appoint':
					D('Appoint_order')->after_pay($go_query_param['order_param']);
					break;
				case 'waimai':
					D('Waimai_order')->after_pay($go_query_param['order_param']);
					break;
				case 'shop':
					D('Shop_order')->after_pay($go_query_param['order_param']);
					break;
			}
		}
		$now_order['order_id']  =   $tmpid;
		switch($_GET['order_type']){
			case 'group':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
				break;
			case 'meal':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
				break;
			case 'recharge':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
				break;
			case 'appoint':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
				break;
			case 'waimai':
				$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
				break;
			case 'shop':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
				break;
		}
		redirect($redirctUrl);
	}
	//支付宝支付同步回调
	public function alipay_return(){
		$order_id_arr = explode('_',$_GET['out_trade_no']);				
		$order_type = $order_id_arr[0];
		$order_id = $order_id_arr[1];
		switch($order_type){
			case 'group':
				$now_order = D('Group_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'meal':
			case 'takeout':
			case 'food':
				$now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'recharge':
				$now_order = D('User_recharge_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'appoint':
				$now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'waimai':
				$now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
				break;
			case 'shop':
				$now_order = D('Shop_order')->where(array('orderid'=>$order_id))->find();
				break;
			default:
				$this->error('非法的订单');
		}
		if($now_order['paid']){
			switch($order_type){
				case 'group':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
					break;
				case 'recharge':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
					break;
				case 'appoint':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
					break;
				case 'waimai':
					$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
					break;
				case 'shop':
					$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
					break;
			}
			redirect($redirctUrl);exit;
		}
		$pay_method = D('Config')->get_pay_method();
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		$import_result = import('@.ORG.pay.Alipay');
		$pay_class = new Alipay('','',$order_type,$pay_method['alipay']['config'],$this->user_session,0);
		$go_query_param = $pay_class->query_order();
		if($go_query_param['error'] === 0){
			switch($order_type){
				case 'group':
					D('Group_order')->after_pay($go_query_param['order_param']);
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					D('Meal_order')->after_pay($go_query_param['order_param']);
					break;
				case 'recharge':
					D('User_recharge_order')->after_pay($go_query_param['order_param']);
					break;
				case 'appoint':
					D('Appoint_order')->after_pay($go_query_param['order_param']);
					break;
				case 'waimai':
					D('Waimai_order')->after_pay($go_query_param['order_param']);
					break;
				case 'shop':
					D('Shop_order')->after_pay($go_query_param['order_param']);
					break;
			}
		}
		switch($order_type){
			case 'group':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
				break;
			case 'meal':
			case 'takeout':
			case 'food':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
				break;
			case 'recharge':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index&order_id='.$now_order['order_id'];
				break;
			case 'appoint':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
				break;
			case 'waimai':
				$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
				break;
			case 'shop':
				$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
				break;
		}
		redirect($redirctUrl);
	}
	
	//百度支付同步通知
	public function baidu_back()
	{
		$pay_type = 'baidu';
		$this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
		switch($this->config['merchant_ownpay']){
			case '0':
				$pay_method = D('Config')->get_pay_method();
				break;
			case '1':
				$pay_method = D('Config')->get_pay_method();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
				}				
				break;
			case '2':
				$pay_method = array();
				if($_GET['own_mer_id']){
					$mer_id = $_GET['own_mer_id'];
				}else if($_SESSION['own_mer_id']){
					$mer_id = $_SESSION['own_mer_id'];
					unset($_SESSION['own_mer_id']);
				}
				if($mer_id){
					$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
					foreach($merchant_ownpay as $ownKey=>$ownValue){
						$ownValueArr = unserialize($ownValue);
						if($ownValueArr['open']){
							$ownValueArr['is_own'] = true;
							$pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
						}
					}
					unset($pay_method['weixin']);
					if(empty($pay_method)){
						$pay_method = D('Config')->get_pay_method();
					}
				}
				break;			
		}
		if(empty($pay_method)){
			$this->error_tips('系统管理员没开启任一一种支付方式！');
		}
		if(empty($pay_method[$pay_type])){
			$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
		}

		$pay_class_name = ucfirst($pay_type);
		$import_result = import('@.ORG.pay.'.$pay_class_name);
		if(empty($import_result)){
			$this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
		}
	
		$pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,0);
		$get_pay_param = $pay_class->return_url();
		if($pay_type!='offline'){
			$get_pay_param['order_param']['return']=1;
		}
		if(empty($get_pay_param['error'])){
			
			switch($get_pay_param['order_param']['order_type']){
				case 'group':
					$now_order = D('Group_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'meal':
				case 'takeout':
				case 'food':
					$now_order = D('Meal_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'recharge':
					$now_order = D('User_recharge_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'appoint':
					$now_order = D('Appoint_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'waimai':
					$now_order = D('Waimai_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				case 'shop':
					$now_order = D('Shop_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
					break;
				default:
					$this->error_tips('非法的订单');
			}
			
			if(empty($now_order)){
				$this->error_tips('该订单不存在');
			}
			$now_order['order_type'] = $get_pay_param['order_param']['order_type'];
			if($now_order['paid']){
				switch($get_pay_param['order_param']['order_type']){
					case 'group':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=group_order_view&order_id='.$now_order['order_id'];
						break;
					case 'meal':
					case 'takeout':
					case 'food':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=meal_order_view&order_id='.$now_order['order_id'];
						break;
					case 'recharge':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Credit&a=index';
						break;
					case 'appoint':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=appoint_order_view&order_id='.$now_order['order_id'];
						break;
					case 'waimai':
						$redirctUrl = C('config.site_url').'/index.php?g=Waimai&c=Order&a=detail&order_id='.$now_order['order_id'];
						break;
					case 'shop':
						$redirctUrl = C('config.site_url').'/index.php?g=User&c=Index&a=shop_order_view&order_id='.$now_order['order_id'];
						break;
				}
				redirect($redirctUrl);exit;
			}
			
			if($get_pay_param['order_param']['order_type'] == 'group'){
				$pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food'){
				$pay_info = D('Meal_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'recharge'){
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'appoint'){
				$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai'){
				$pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);			
			}else if($get_pay_param['order_param']['order_type'] == 'waimai-recharge'){
				$pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);			
			} elseif ($get_pay_param['order_param']['order_type'] == 'shop') {
				$pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);			
			}else{
				$this->error_tips('订单类型非法！请重新下单。');
			}
			
			if($pay_info['url']){
				$pay_info['url'] = preg_replace('#/source/(\w+).php#','/index.php',$pay_info['url']);
			} 
			if(empty($pay_info['error'])){
				if(!empty($pay_info['url'])){
					$this->assign('jumpUrl',$pay_info['url']);
					$this->success(L('_PAY_SUCCESS_JUMP_'));
					exit();
				}
			}
			if(empty($pay_info['url'])){
				$this->error_tips($pay_info['msg']);
			}else{
				$this->error_tips($pay_info['msg'],$pay_info['url']);
			}
		}else{
			$this->error_tips($get_pay_param['msg']);
		}

	}

    public function moneris(){
	    //var_dump($_POST);die('henhao');
	    $order_id = $_POST['response_order_id'];
	    $response_code = $_POST['response_code'];
	    $date_stamp = $_POST['date_stamp'];
	    $time_stamp = $_POST['time_stamp'];
	    $bank_approval_code = $_POST['bank_approval_code'];
        $result = $_POST['result'];//1 = approved ,  0 = declined, incomplete
        $trans_name = $_POST['trans_name'];
        $cardholder = $_POST['cardholder'];
        $charge_total = $_POST['charge_total'];//支付金额
        $card = $_POST['card'];
        $f4l4 = $_POST['f4l4'];
        $message = $_POST['message'];
        $iso_code = $_POST['iso_code'];
        $bank_transaction_id = $_POST['bank_transaction_id'];
        $transactionKey = $_POST['transactionKey'];
        $Ticket = $_POST['Ticket'];
        $txn_num = $_POST['txn_num'];//交易号，退款时使用
        $avs_response_code = $_POST['avs_response_code'];
        $cvd_response_code = $_POST['cvd_response_code'];
        $cavv_result_code = $_POST['cavv_result_code'];
        $is_visa_debit = $_POST['is_visa_debit'];
        $is_wap = $_POST['rvarwap'];//自定义 是否来自wap 1是 0否 web

        $order = explode("_",$order_id);
        $order_id = $order[1];

        if($result == 1){//支付成功
            $order_param['order_id'] = $order_id;
            $order_param['order_from'] = 0;
            $order_param['order_type'] = 'shop';
            $order_param['pay_time'] = $date_stamp.' '.$time_stamp;
            $order_param['pay_money'] = $charge_total;
            $order_param['pay_type'] = 'moneris';
            $order_param['is_mobile'] = $is_wap;
            $order_param['is_own'] = 0;
            $order_param['third_id'] = 0;
            $order_param['invoice_head'] = $txn_num;//借用发票头这个字段存储交易号

            $pay_info = D('Shop_order')->after_pay($order_param);

        }else{

        }

        if($is_wap == 1){
            header("location:".U("Wap/Shop/status",array('order_id'=>$order_id)));
        }else{
            header("location:".U("User/Index/shop_order_view",array('order_id'=>$order_id)));
        }
    }

    public function MonerisPay(){
        import('@.ORG.pay.MonerisPay');
        $moneris_pay = new MonerisPay();
        $resp = $moneris_pay->payment($_POST,$this->user_session['uid']);
        //var_dump($resp);
        if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
            $order = explode("_",$_POST['order_id']);
            $order_id = $order[1];
            $url =U("User/Index/shop_order_view",array('order_id'=>$order_id));

            $this->success(L('_PAYMENT_SUCCESS_'),$url,true);
        }else{
            $this->error($resp['message'],'',true);
        }
    }

    public function WeixinAndAli(){
        //获取支付的相关配置数据
        $where = array('tab_id'=>'alipay','gid'=>7);
        $result = D('Config')->field(true)->where($where)->select();
        foreach ($result as $payData){
            if($payData['name'] == 'pay_alipay_name')
                $pay_id = $payData['value'];
            if($payData['name'] == 'pay_alipay_key')
                $pay_key = $payData['value'];
            if($payData['name'] == 'pay_alipay_pid')
                $pay_url = $payData['value'];
        }
        //var_dump($_POST);
        //var_dump($this->user_session);
        //支付类型 weixin alipay
        $channelId = '';
        $pay_type = $_POST['pay_type'];
        //微信支付
        if($pay_type == 'weixin')
            $channelId = 'WX_NATIVE';

        //支付宝支付
        if($pay_type == 'alipay')
            $channelId = 'ALIPAY_PC';

        $order_id = explode('_',$_POST['order_id'])[1];
        $data['mchId'] = $pay_id;
        $data['mchOrderNo'] = $_POST['order_id'].'_'.time();
        $data['channelId'] = $channelId;
        $data['currency'] = 'CAD';
        //单位分
        $data['amount'] = $_POST['charge_total'] * 100;
        $data['clientIp'] = real_ip();
        $data['device'] = 'WEB';
        //支付结果回调URL
        $data['notifyUrl'] = 'https://www.tutti.app/notify';
        $data['subject'] = 'Tutti Order '.$order_id;
        $data['body'] = 'Tutti Order';
        if($pay_type == 'weixin') {
            $data['extra'] = json_encode(array('productId' => $_POST['order_id']));
        }
        $data['sign'] = $this->getSign($data,$pay_key);
        //echo $pay_url;
        //ksort($data);
        //var_dump($data);
        //var_dump(json_encode($data));
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlPost($pay_url,'params='.json_encode($data));
        //var_dump($result);
        //通信标识 及 创建支付订单是否成功
        if ($result['retCode'] == 'SUCCESS') {
            //交易结果
            if ($result['resCode'] == 'SUCCESS') {
                //先处理一下订单信息
                //处理小费
                $order_data = array('tip_charge'=>$_POST['tip']);
                //处理优惠券
                if($_POST['coupon_id']){
                    $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                    if(!empty($now_coupon)){
                        $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id'=>$_POST['coupon_id']))->find();
                        $coupon_real_id = $coupon_data['coupon_id'];
                        $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                        $in_coupon = array('coupon_id'=>$data['coupon_id'],'coupon_price'=>$coupon['discount']);
                        $order_data = array_merge($order_data,$in_coupon);
                    }
                }
                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_data);

                if($pay_type == 'weixin')
                    $this->success('', $result['codeUrl']);
                if($pay_type == 'alipay')
                    $this->success('', $result['payUrl']);
            } else {
                $this->error($result['errCodeDes'].' - errCode:'.$result['errcode']);
            }
        } else {
            $this->error($result['retMsg'].' - errCode:'.$result['errcode']);
        }
    }

    public function receipt(){
        $now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $_GET['order_id']));
        $pay_record = D('Pay_moneris_record')->field(true)->where(array('order_id'=>$_GET['order_id'],'complete'=>'true'))->find();
        if($pay_record)
            $now_order = array_merge($now_order,$pay_record);
//        var_dump($now_order);
        foreach($now_order['info'] as $k=>$v){
            $goods = D('Shop_goods')->field(true)->where(array('goods_id'=>$v['goods_id']))->find();
            $now_order['info'][$k]['name'] = lang_substr($goods['name'],C('DEFAULT_LANG'));

            //garfunkel 显示规格和分类
            $spec_desc = '';
            $spec_ids = explode('_',$v['spec_id']);
            foreach ($spec_ids as $vv){
                $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                $spec_desc = $spec_desc == '' ? lang_substr($spec['name'],C('DEFAULT_LANG')) : $spec_desc.','.lang_substr($spec['name'],C('DEFAULT_LANG'));
            }

            if($v['pro_id'] != '')
                $pro_ids = explode('|',$v['pro_id']);
            else
                $pro_ids = array();

            foreach ($pro_ids as $vv){
                $ids = explode(',',$vv);
                $proId = $ids[0];
                $sId = $ids[1];

                $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                $nameList = explode(',',$pro['val']);
                $name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));

                $spec_desc = $spec_desc == '' ? $name : $spec_desc.','.$name;
            }

            if ($spec_desc != '')
                $now_order['info'][$k]['spec'] = $spec_desc;
        }


        $this->assign('order',$now_order);
        $this->display();
    }

    private function getSign($params,$key){
        if(!empty($params)){
            $p =  ksort($params);
            if($p){
                $str = '';
                foreach ($params as $k=>$val){
                    if ($val != '')
                        $str .= $k .'=' . $val . '&';
                }
                $strs = rtrim($str, '&');
                //var_dump($strs);
                $sign = md5($strs.'&key='.$key);
                return strtoupper($sign);
            }
        }
    }

    public function notify_wa_return(){
	    //是否跳转
	    $is_jump = false;
	    if($_GET){
            $_POST = $_GET;
            $is_jump = true;
        }

	    $p = json_encode($_POST);
        //存储返回记录 暂时使用 正式上线注释掉
        file_put_contents("./test_log.txt",date("Y/m/d")."   ".date("h:i:sa")."   "."Notify" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.$p."\r\n",FILE_APPEND);

        $where = array('tab_id'=>'alipay','gid'=>7);
        $result = D('Config')->field(true)->where($where)->select();
        foreach ($result as $payData){
            if($payData['name'] == 'pay_alipay_key')
                $key = $payData['value'];
        }
        //$json_data = '{"payOrderId":"P00181221140838553334009319","amount":"1","mchId":"10000029","mchOrderNo":"Tuttishop_11377_1545372518","subject":"Tutti+Order+11377","paySuccTime":"1545372518567","sign":"66D651F73E42758E2015B5C2D1BCF526","channelOrderNo":"2018122122001343950505016972","backType":"1","param1":"","param2":"","clientIp":"127.0.0.1","currency":"CAD","device":"WEB","channelId":"ALIPAY_PC","status":"2"}';
        //$_POST = json_decode($json_data,true);
        if($_POST){
            $rData['payOrderId'] = $_POST['payOrderId'];
            $rData['amount'] = $_POST['amount'];
            $rData['mchId'] = $_POST['mchId'];
            $rData['mchOrderNo'] = $_POST['mchOrderNo'] ? $_POST['mchOrderNo'] : '';
            $rData['subject'] = $_POST['subject'];
            $rData['paySuccTime'] = $_POST['paySuccTime'];
            $rData['channelOrderNo'] = $_POST['channelOrderNo'];
            $rData['backType'] = $_POST['backType'];
            $rData['param1'] = $_POST['param1'];
            $rData['param2'] = $_POST['param2'];
            $rData['clientIp'] = $_POST['clientIp'] ? $_POST['clientIp'] : '';
            $rData['currency'] = $_POST['currency'];
            $rData['device'] = $_POST['device'];
            $rData['channelId'] = $_POST['channelId'];
            $rData['status'] = $_POST['status'];

            //获取订单id
            $order = explode("_",$rData['mchOrderNo']);
            $order_id = $order[1];

            if($rData['status'] == 2){
                $sign = $this->getSign($rData,$key);
                //var_dump($sign.'=='. $_POST['sign']);
                //验证签名
                if($sign == $_POST['sign']){
                    $now_order = D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->find();
                    if($now_order['pain'] == 0){
                        if($rData['channelId'] == 'WX_JSAPI') {//如果是公众号支付
                            $order = explode("_", $rData['param1']);
                            $order_id = $order[1];
                        }
                        //获取支付方式
                        $channel = explode("_",$rData['channelId']);
                        $payment = $channel[0] == 'WX' ? 'weixin' : 'alipay';
                        //换算支付金额
                        $amount = $rData['amount'] / 100;

                        $order_param['order_id'] = $order_id;
                        $order_param['order_from'] = 0;
                        $order_param['order_type'] = 'shop';
                        $order_param['pay_time'] = date('Y-m-d H:i:s',substr($rData['paySuccTime'],0,10));
                        $order_param['pay_money'] = $amount;
                        $order_param['pay_type'] = $payment;
                        $order_param['is_own'] = 0;
                        $order_param['third_id'] = 0;
                        $order_param['invoice_head'] = $rData['payOrderId'];//借用发票头这个字段存储交易号
                        $result = D('Shop_order')->after_pay($order_param);
                    }
                    echo 'success';
                    if($is_jump){
                        if($rData['channelId'] == 'WX_JSAPI' || $rData['channelId'] == 'ALIPAY_WAP' || $rData['channelId'] == 'WX_MWEB'){

                            $url = '/wap.php?g=Wap&c=Shop&a=status&order_id='.$order_id;
                        }else{
                            $url =U("User/Index/shop_order_view",array('order_id'=>$order_id));
                        }
                        sleep(1);

                        header('Location:'.$url);
                    }
                }
            }

        }else{
            echo 'Error';
        }

    }
}
?>