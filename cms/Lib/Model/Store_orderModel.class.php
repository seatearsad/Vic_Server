<?php
class Store_orderModel extends Model
{
	
	public function get_order_by_id($uid, $order_id)
	{
		$condition = array();
		$condition['order_id'] = $order_id;
		$condition['uid'] = $uid;
		return $this->field(true)->where($condition)->find();
	}

	public function get_pay_order($uid, $order_id, $is_web=false)
	{
		$now_order = $this->get_order_by_id($uid, $order_id);
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}
		if(!empty($now_order['paid'])){
			return array('error'=>1,'msg'=>'您已经支付过此订单！','url'=>str_replace('/source/','/',U('My/store_order_list',array('order_id'=>$now_order['order_id']))));
		}

		$merchant_store = M("Merchant_store")->where(array('store_id' => $now_order['store_id'], 'mer_id' => $now_order['mer_id']))->find();
		//$imgs = M('Merchant_store')->field('pic_info')->where(array('store_id'=>$now_order['store_id']))->find();
		$imgs =explode(';', $merchant_store['pic_info']);
		foreach($imgs as &$v){
			$v = preg_replace('/,/','/',$v);
		}

		$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'mer_id'			=>	$now_order['mer_id'],
					'order_type'		=>	'store',
					'order_name'		=>	$now_order['name'],
					'order_num'			=>	0,
					'order_price'		=>	floatval($now_order['price']),
					'order_total_money'	=>	floatval($now_order['price']),
					'extra_price'	=>	floatval($now_order['extra_price']),
					'img'				=> C('config.site_url').'/upload/store/'.$imgs[0],
			);
		return array('error' => 0,'order_info' => $order_info);
	}
	
	//手机端支付前订单处理
	public function wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user)
	{
		//去除微信优惠的金额
		$pay_money = $order_info['order_total_money'];
		if($merchant_balance){
			$pay_money = sprintf("%.2f",$pay_money*$merchant_balance['card_discount']/10);
		}
		if(C('config.open_extra_price')==1){
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
			$pay_money +=$order_info['order_extra_price'];
		}
		
		//判断优惠券
		if ($now_coupon['card_price'] >0) {
			$data_store_order['card_id'] = $now_coupon['merc_id'];
			$data_store_order['card_price'] = round($now_coupon['card_price'] * 100)/100;
			if ($now_coupon['card_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['card_price'];
		}

		if($now_coupon['coupon_price']>0){
			$data_store_order['coupon_id'] = $now_coupon['sysc_id'];
			$data_store_order['coupon_price'] = $now_coupon['coupon_price'];
			if ($now_coupon['coupon_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['coupon_price'];
			$data_store_order['pay_money'] = $pay_money;
		}

		if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
			$data_store_order['score_used_count']  = $order_info['score_used_count'];
			$data_store_order['score_deducte']     = (float)$order_info['score_deducte'];
			if($order_info['score_deducte'] >= $pay_money){
				//扣除积分
				$order_result = $this->wap_pay_save_order($order_info,$data_store_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}


			$pay_money -= $order_info['score_deducte'];
			//$data_group_order['system_pay'] += $data_group_order['score_deducte'];
		}
		//判断商家余额
//		if (!empty($merchant_balance)) {
//			if($merchant_balance >= $pay_money){
//				$data_store_order['merchant_balance'] = $pay_money;
//				$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
//				if ($order_result['error_code']) {
//					return $order_result;
//				}
//				return $this->wap_after_pay_before($order_info);
//			} else {
//				$data_store_order['merchant_balance'] = $merchant_balance;
//			}
//			$pay_money -= $merchant_balance;
//		}
		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_money'] >= $pay_money){
				$data_store_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_store_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_store_order['merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_store_order['card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_store_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_store_order['card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}
		
		//判断帐户余额
		if (!empty($now_user['now_money'])&&$order_info['use_balance']) {
			if ($now_user['now_money'] >= $pay_money) {
				$data_store_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			} else {
				$data_store_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info, $data_store_order);
		if ($order_result['error_code']) {
			return $order_result;
		}
		return array('error_code' => false, 'pay_money' => $pay_money);
	}
	
	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info, $data_store_order)
	{
		$data_store_order['card_id'] 			= !empty($data_store_order['card_id']) ? $data_store_order['card_id'] : 0;
		$data_store_order['merchant_balance'] 	= !empty($data_store_order['merchant_balance']) ? $data_store_order['merchant_balance'] : 0;
		$data_store_order['card_give_money'] 	= !empty($data_store_order['card_give_money']) ? $data_store_order['card_give_money'] : 0;
		$data_store_order['card_discount'] 	= !empty($data_store_order['card_discount']) ? $data_store_order['card_discount'] : 0;
		$data_store_order['balance_pay']	 	= !empty($data_store_order['balance_pay']) ? $data_store_order['balance_pay'] : 0;
		$data_store_order['score_used_count']  	= !empty($data_store_order['score_used_count'])?$data_store_order['score_used_count']:0;
		$data_store_order['score_deducte']     	= !empty($data_store_order['score_deducte'])?(float)$data_store_order['score_deducte']:0;
		$data_store_order['dateline'] = $_SERVER['REQUEST_TIME'];
		$data_store_order['card_price'] =  !empty($data_store_order['card_price']) ? $data_store_order['card_price'] : 0;
		$condition_store_order['order_id'] = $order_info['order_id'];
		$result = $this->where($condition_store_order)->data($data_store_order)->save();
		if ($result) {
			return array('error_code' => false, 'msg' => '保存订单成功！');
		} else {
			return array('error_code' => true, 'msg' => '保存订单失败！请重试或联系管理员。1');
		}
	}
	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info)
	{
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => 1,
			);
			$result_after_pay = $this->after_pay($order_param);
			if ($result_after_pay['error']) {
				return array('error_code' => true,'msg'=>$result_after_pay['msg']);
			}
			return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',U('My/store_order_list')));
	}
	
	//支付之后
	public function after_pay($order_param)
	{
		if($order_param['pay_type']!=''){
			$condition_order['orderid'] = $order_param['order_id'];
		}else{
			$condition_order['order_id'] = $order_param['order_id'];
		}
		$now_order = $this->field(true)->where($condition_order)->find();
		if (empty($now_order)) {
			return array('error' => 1, 'msg' => '当前订单不存在！');
		} elseif($now_order['paid'] == 1) {
			return array('error' => 1, 'msg' => '该订单已付款！', 'url' => U('My/store_order_list',array('order_id'=>$now_order['order_id'])));
		} else {
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if (empty($now_user)) {
				return array('error' => 1, 'msg' => '没有查找到此订单归属的用户，请联系管理员！');
			}
			
			//判断优惠券是否正确
//			if($now_order['card_id']){
//				$now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($now_order['card_id'],$now_order['uid']);
//				if(empty($now_coupon)){
//					return $this->wap_after_pay_error($now_order, $order_param, '您选择的优惠券不存在！');
//				}
//			}
			if($now_order['card_id']){
				$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['card_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//判断平台优惠券
			if($now_order['coupon_id']){
				$now_coupon = D('System_coupon')->get_coupon_by_id($now_order['coupon_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			$score_used_count=$now_order['score_used_count'];
			if($now_user['score_count']<$score_used_count){
				return array('error'=>1,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
			if($score_used_count>0){
				$use_result = D('User')->user_score($now_order['uid'],$score_used_count,'购买 '.$now_order['order_name'].' 扣除'.C('config.score_name'));
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
			
			//判断会员卡余额
			$merchant_balance = floatval($now_order['merchant_balance']);
			if($merchant_balance){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money'] < $merchant_balance){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}

			$card_give_money = floatval($now_order['card_give_money']);
			if($card_give_money){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$now_order['mer_id']);
				if($user_merchant_balance['card_money_give'] < $card_give_money){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}
			//判断帐户余额
			$balance_pay = floatval($now_order['balance_pay']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order, $order_param, '您的帐户余额不够此次支付！');
				}
			}
			
			//如果使用了商家优惠券
//			if($now_order['card_id']){
//				$use_result = D('Member_card_coupon')->user_card($now_order['card_id'], $now_order['mer_id'], $now_order['uid']);
//				if($use_result['error_code']){
//					return array('error'=>1,'msg'=>$use_result['msg']);
//				}
//			}

			if($now_order['card_id']){
				$use_result = D('Card_new_coupon')->user_coupon($now_order['card_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//如果使用了平台优惠券
			if($now_order['coupon_id']){

				$use_result = D('System_coupon')->user_coupon($now_order['coupon_id'],$now_order['order_id'],$order_param['order_type'],$now_order['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}


			//如果使用会员卡余额
//			if ($merchant_balance) {
//				$use_result = D('Member_card')->use_card($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
//				if($use_result['error_code']){
//					return array('error'=>1,'msg'=>$use_result['msg']);
//				}
//			}

			if($merchant_balance){
				$use_result = D('Card_new')->use_money($now_order['uid'],$now_order['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if($card_give_money){
				$use_result = D('Card_new')->use_give_money($now_order['uid'],$now_order['mer_id'],$card_give_money,'购买 '.$now_order['order_name'].' 扣除会员卡赠送余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//如果用户使用了余额支付，则扣除相应的金额。
			if(!empty($balance_pay)){
				$use_result = D('User')->user_money($now_order['uid'],$balance_pay,'购买 '.$now_order['order_name'].' 扣除余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//$condition_store_order['order_id'] = $order_param['order_id'];
			
			$data_store_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_store_order['payment_money'] = floatval($order_param['pay_money']);
			$data_store_order['pay_type'] = $order_param['pay_type'];
			$data_store_order['third_id'] = $order_param['third_id'];
			//$data_group_order['is_mobile_pay'] = $order_param['is_mobile'];
			$data_store_order['is_own'] = isset($order_param['sub_mch_id'])?2:$order_param['is_own'];
			$data_store_order['paid'] = 1;
			if($this->where($condition_order)->data($data_store_order)->save()){
				//积分
				$update_order = $this->where($condition_order)->find();
				$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();

				D('Scroll_msg')->add_msg('store',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.cash_alias_name').'成功');

				if(C('config.open_extra_price')==1){
					$order = $now_order;
					$order['order_type'] ='store';
					$score = D('Percent_rate')->get_extra_money($order);
					if($score>0){
						D('User')->add_score($order['uid'], floor($score),'在 ' . $store['name'] . ' 中使用优惠买单支付了' . floatval($now_order['balance_pay']+$now_order['merchant_balance']+$order_param['pay_money']) . '元 +'.$now_order['score_used_count'].C('config.extra_price_alias_name').' 获得'.C('config.extra_price_alias_name'));
						D('Scroll_msg')->add_msg('store',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买'.C('config.cash_alias_name').'成功获得'.C('config.score_name'));
						
					}
				}else{

					if(C('config.open_score_get_percent')==1){
						$score_get = C('config.score_get_percent')/100;
					}else{
						
						$score_get = C('config.user_score_get');
					}

					D('User')->add_score($now_order['uid'], round(($update_order['payment_money']+$update_order['balance_pay'] )* $score_get), '在' . $store['name'] . ' 中使用优惠买单支付了' . floatval($now_order['price']) . '元 获得'.C('config.score_name'));
					D('Userinfo')->add_score($now_order['uid'], $now_order['mer_id'], $now_order['price'], '在 ' . $store['name'] . ' 中使用优惠买单支付了' . floatval($now_order['price']) . '元 获得积分');
				}

				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				//分佣
				if(!empty($now_user['openid'])&&C('config.open_user_spread')){
					//上级分享佣金
					if($now_order['from_plat']==1){
						$spread_rate = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'],'store');
						$spread_type = 'store';
					}else{
						$spread_rate = D('Percent_rate')->get_user_spread_rate($now_order['mer_id'],'cash');
						$spread_type = 'cash';
					}
					$open_extra_price = C('config.open_extra_price');
					$spread_users[]=$now_user['uid'];
					$now_user_spread = D('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$now_user['openid']))->find();
					if($now_order['is_own']){
						$data_group_order['payment_money']=0;
					}
					$href = C('config.site_url') . '/wap.php?g=Wap&c=My&a=spread_list&status=-1';
					if(!empty($now_user_spread)){
						$spread_user = D('User')->get_user($now_user_spread['spread_openid'],'openid');
						//$user_spread_rate = $update_group['spread_rate'] > 0 ? $update_group['spread_rate'] : C('config.user_spread_rate');
						$user_spread_rate = $spread_rate['first_rate'];
						if($spread_user && $user_spread_rate&&!in_array($spread_user['uid'],$spread_users)){
							$spread_money = round(($balance_pay + $data_store_order['payment_money']) * $user_spread_rate / 100, 2);
							$spread_data = array(
									'uid'=>$spread_user['uid'],
									'spread_uid'=>0,
									'get_uid'=>$now_user['uid'],
									'money'=>$spread_money,
									'order_type'=>$spread_type,
									'order_id'=>$now_order['order_id'],
									'third_id'=>$now_order['store_id'],
									'add_time'=>$_SERVER['REQUEST_TIME']
							);
							if($spread_user['spread_change_uid']!=0){
								$spread_data['change_uid'] = 	$spread_user['spread_change_uid'];
							}
							D('User_spread_list')->data($spread_data)->add();
							$buy_user = D('User')->get_user($now_user_spread['openid'],'openid');
							if($spread_money>0){
								if($open_extra_price){
									$money_name = C('config.extra_price_alias_name');
								}else{
									$money_name = '佣金';
								}
								$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $now_user_spread['spread_openid'], 'first' => $buy_user['nickname'] . '通过您的分享购买了优惠买单，验证消费后您将获得'.$money_name.'。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'));
							}
							$spread_users[]=$spread_user['uid'];
							// D('User')->add_money($spread_user['uid'],$spread_money,'推广用户 '.$now_user['nickname'].' 购买 ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
						}

						//第二级分享佣金
						$second_user_spread = D('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$spread_user['openid']))->find();
						if(!empty($second_user_spread)&&!$open_extra_price) {
							$second_user = D('User')->get_user($second_user_spread['spread_openid'], 'openid');
//							//$sub_user_spread_rate = $update_group['sub_spread_rate'] > 0 ? $update_group['sub_spread_rate'] : C('config.user_first_spread_rate');
							$sub_user_spread_rate = $spread_rate['second_rate'];
							if ($second_user && $sub_user_spread_rate&&!in_array($second_user['uid'],$spread_users)) {
								$spread_money = round(($balance_pay + $data_store_order['payment_money']) * $sub_user_spread_rate / 100, 2);
								$spread_sec_data =array('uid' => $second_user['uid'], 'spread_uid' => $spread_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
								if($second_user['spread_change_uid']!=0){
									$spread_sec_data['change_uid'] = 	$second_user['spread_change_uid'];
								}
								D('User_spread_list')->data($spread_sec_data)->add();
								$sec_user = D('User')->get_user($second_user_spread['openid'], 'openid');
								if($spread_money>0) {
									$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $second_user_spread['spread_openid'], 'first' => $sec_user['nickname'] .'的子用户'.$buy_user['nickname'] . '通过您的分享购买了优惠买单，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'));
								}
								$spread_users[]=$second_user['uid'];
								// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
							}

							//顶级分享佣金
							$first_user_spread = D('User_spread')->field('`spread_openid`, `openid`')->where(array('openid' => $second_user['openid']))->find();

							if (!empty($first_user_spread) && C('config.user_third_level_spread')&&!$open_extra_price) {
								$first_spread_user = D('User')->get_user($first_user_spread['spread_openid'], 'openid');
//								//$sub_user_spread_rate = $update_group['third_spread_rate'] > 0 ? $update_group['third_spread_rate'] : C('config.user_second_spread_rate');
								$sub_user_spread_rate = $spread_rate['third_rate'];
								if ($first_spread_user && $sub_user_spread_rate&&!in_array($first_spread_user['uid'],$spread_users)) {
									$spread_money = round(($balance_pay + $data_store_order['payment_money']) * $sub_user_spread_rate / 100, 2);
									$spread_thd_data=array('uid' => $first_spread_user['uid'], 'spread_uid' => $second_user['uid'], 'get_uid' => $now_user['uid'], 'money' => $spread_money, 'order_type' => $spread_type, 'order_id' => $now_order['order_id'], 'third_id' => $now_order['store_id'], 'add_time' => $_SERVER['REQUEST_TIME']);
									if($first_spread_user['spread_change_uid']!=0){
										$spread_thd_data['change_uid'] = 	$first_spread_user['spread_change_uid'];
									}
									D('User_spread_list')->data($spread_thd_data)->add();

									$fir_user = D('User')->get_user($first_user_spread['openid'], 'openid');
									if($spread_money>0) {
										$model->sendTempMsg('OPENTM201812627', array('href' => $href, 'wecha_id' => $first_user_spread['spread_openid'], 'first' =>$fir_user['nickname'].'的子用户的子用户'.$buy_user['nickname'] . '通过您的分享购买了优惠买单，验证消费后您将获得佣金。', 'keyword1' => $spread_money, 'keyword2' => date("Y年m月d日 H:i"), 'remark' => '点击查看详情！'));
									}
									// D('User')->add_money($first_spread_user['uid'],$spread_money,'子用户推广用户 '.$now_user['nickname'].' 购买ID为 '.$now_order['group_id'].' 的商品 获得的佣金');
								}
							}

						}
					}
				}
				$price_str =  floatval($now_order['balance_pay']+$data_store_order['payment_money']+$now_order['merchant_balance']+$now_order['card_give_money']) ;

				if($now_order['from_plat'] && $now_user['openid']){
					if($now_order['from_plat'] == 2){
						$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
						$href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '到店买单提醒', 'keyword1' => $now_order['name'], 'keyword2' => $now_order['order_id'], 'keyword3' => $price_str, 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'));
					}else{
						$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
						$href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
						$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => '优惠买单提醒', 'keyword1' => '到店优惠买单', 'keyword2' => $now_order['order_id'], 'keyword3' => $price_str, 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'));
					}
				}
				
				
				//支付成功增加商家余额
				$now_order = $this->field(true)->where($condition_order)->find();
				$now_user = M('User')->where(array('uid'=>$now_order['uid']))->find();
				if($now_order['from_plat']==1){
					$now_order['order_type']='store';
					D('Merchant_money_list')->add_money($now_order['mer_id'],'用户优惠买单支付计入收入',$now_order);
					//商家推广分佣
					D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户优惠买单获得佣金');
				}else{
					$now_order['order_type']='cash';
					D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付计入收入',$now_order);
					//商家推广分佣
					D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户到店支付获得佣金');
				}

				//小票打印
// 				$msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
// 				$op = new orderPrint(C('config.print_server_key'), C('config.print_server_topdomain'));
// 				$op->printit($now_order['mer_id'], $now_order['store_id'], $msg, 1);

// 				$str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
// 				foreach ($str_format as $print_id => $print_msg) {
// 					$print_id && $op->printit($now_order['mer_id'], $now_order['store_id'], $print_msg, 1, $print_id);
// 				}

				//短信提醒
				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['mer_id'], 'type' => 'store');
				$store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['store_id']))->find();
// 				if (C('config.sms_shop_success_order') == 1 || C('config.sms_shop_success_order') == 3) {

// 				$sms_data['uid'] = $now_order['uid'];
// 				$sms_data['mobile'] = $now_user['phone'];
// 				$sms_data['sendto'] = 'user';
// 				$sms_data['content'] = '您在' . date("Y-m-d H:i:s") . '时，通过优惠买单,给【' . $store['name'] . '】店,支付了' . floatval($now_order['price']) . '元';
// 				Sms::sendSms($sms_data);

// 				}
// 				if (C('config.sms_shop_success_order') == 2 || C('config.sms_shop_success_order') == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';
				$sms_data['content'] = '顾客' . $now_user['username'] . '在' . date("Y-m-d H:i:s") . '时，通过优惠买单，支付了：' .$price_str. '元,单号：' . ($now_order['real_orderid']?$now_order['real_orderid']:$now_order['order_id']);
				Sms::sendSms($sms_data);
// 				}

				
				if($now_order['business_type']){
					switch($now_order['business_type']){
						case 'foodshop':
							$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
							D('Foodshop_order')->after_pay($now_order['business_id']);
							M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['price']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
							break;
					}
				}


				if($order_param['is_mobile']){
					return array('error'=>0,'url'=>str_replace('/source/','/',U('My/store_order_list',array('order_id'=>$now_order['order_id']))));
				}else{
					return array('error'=>0,'url'=>U('User/Index/store_order_list',array('order_id'=>$now_order['order_id'])));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}
	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips)
	{
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('My/store_order_list',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('User/Index/store_order_list',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}

	/**
	 * 店铺当天订单数量
	 */

	public function get_storeorder_count_today($order_id){
		$today = strtotime(date('Ymd',time()));
		$where['store_id'] = $order_id;
		$where['pay_time'] = array('gt',$today);
		return $this->where($where)->count();
	}
}
?>