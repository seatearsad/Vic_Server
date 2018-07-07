<?php
class Plat_orderModel extends Model{
	/*根据 支付平台的英文 和 是否移动端支付 得到中文名称*/
	public function get_pay_name($pay_type,$is_mobile_pay, $paid = 1){
		switch($pay_type){
			case 'alipay':
				$pay_type_txt = '支付宝';
				break;
			case 'tenpay':
				$pay_type_txt = '财付通';
				break;
			case 'yeepay':
				$pay_type_txt = '易宝支付';
				break;
			case 'allinpay':
				$pay_type_txt = '通联支付';
				break;
			case 'chinabank':
				$pay_type_txt = '网银在线';
				break;
			case 'weixin':
				$pay_type_txt = '微信支付';
				break;
			case 'baidu':
				$pay_type_txt = '百度钱包';
				break;
			case 'unionpay':
				$pay_type_txt = '银联支付';
				break;
			case 'offline':
				$pay_type_txt = '货到付款';
				break;
			default:
				if ($paid) {
					$pay_type_txt = '余额支付';
				} else {
					$pay_type_txt = '未支付';
					return '未支付';
				}
				
		}
		if($is_mobile_pay){
			$pay_type_txt .= '(移动端)';
		}
		return $pay_type_txt;
	}
	public function add_order($param){
		if(empty($param['business_type'])){
			return array('error_code' => true, 'error_msg' => '请携带业务类型');
		}
		if(empty($param['total_money'])){
			return array('error_code' => true, 'error_msg' => '请携带订单总价');
		}
		if(empty($param['order_name'])){
			return array('error_code' => true, 'error_msg' => '请携带订单名称');
		}
		$param['add_time'] = $_SERVER['REQUEST_TIME'];
		if($order_id = $this->data($param)->add()){
			return array('error_code' => false, 'order_id' => $order_id);
		}else{
			return array('error_code' => true, 'error_msg' => '订单创建失败，请重试');
		}
	}
	public function get_pay_order($uid,$order_id,$is_web=false){
		$now_order = $this->where(array('order_id'=>$order_id))->find();
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单已失效或不存在！');
		}
		$order_param = D(ucfirst($now_order['business_type']).'_order')->get_pay_order($now_order['business_id']);
		$order_info = array(
			'order_id'			=>	$now_order['order_id'],
			'mer_id'			=>	$order_param['order_info']['mer_id'],
			'order_type'		=>	'plat',
			'order_name'		=>	$now_order['order_name'],
			'order_num'			=>	$order_param['order_info']['order_num'],
			'order_total_money'	=>	floatval($now_order['total_money']),
			'business_type'    	=>$now_order['business_type'],
			'pay_offline' 			=> true,			//线下支付
			'pay_merchant_balance' 	=> true,		//商家余额
			'pay_merchant_coupon' 	=> true,		//商家优惠券
			'pay_merchant_ownpay' 	=> $order_param['pay_merchant_ownpay'],		//商家自有支付
			'pay_system_balance' 	=> true,		//平台余额
			'pay_system_coupon' 	=> true,		//平台优惠券
			'pay_system_score' 		=> true,		//平台积分抵现
			'discount_status' 		=> $order_param['status'],		//是否有折扣
		);
		if($order_param){
			$order_info = array_merge($order_info,$order_param);
		}
		return array('error'=>0,'order_info'=>$order_info);
	}

	public  function get_order_by_business_id($param){
		$now_order = $this->where($param)->find();
		if ($now_order['pay_type']) {
			$now_order['pay_type_txt'] = D('Pay')->get_pay_name($now_order['pay_type'], 0);
 		}
		return $now_order;
	}

	public function wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user){
		//return array('error_code' => false, 'pay_money' => $order_info['order_total_money']);
		//去除微信优惠的金额
		
		$pay_money = $order_info['order_total_money'];
		if($pay_money<=0){
			return $this->wap_after_pay_before($order_info);
		}
		//去掉折扣
		if($merchant_balance['card_discount']>0){
			$data_plat_order['card_discount'] = $merchant_balance['card_discount'];
			$tmp_pay_money = sprintf("%.2f",$pay_money*$merchant_balance['card_discount']/10);
			$data_plat_order['merchant_discount_money'] = sprintf("%.2f",$pay_money - $tmp_pay_money );
			$pay_money = $tmp_pay_money;
		}
		if(C('config.open_extra_price')==1){
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
			$pay_money +=$order_info['order_extra_price'];
		}

		//判断优惠券
		if($now_coupon['card_price']>0) {
			$data_plat_order['card_id'] = $now_coupon['merc_id'];
			$data_plat_order['card_price'] = $now_coupon['card_price'];
			if ($now_coupon['card_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_plat_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['card_price'];
			$data_plat_order['pay_money'] = $pay_money;
		}

		//系统优惠券
		if($now_coupon['coupon_price']>0){
			$data_plat_order['coupon_id'] = $now_coupon['sysc_id'];
			$data_plat_order['coupon_price'] = $now_coupon['coupon_price'];
			if ($now_coupon['coupon_price'] >= $pay_money) {
				$order_result = $this->wap_pay_save_order($order_info, $data_plat_order);
				if ($order_result['error_code']) {
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['coupon_price'];
			$data_plat_order['pay_money'] = $pay_money;
		}

		// 使用积分
		if(!empty($order_info['score_deducte'])&&$order_info['use_score']){
			$data_plat_order['score_used_count']  = $order_info['score_used_count'];
			$data_plat_order['score_deducte']     = (float)$order_info['score_deducte'];
			if($order_info['score_deducte'] >= $pay_money){
				//扣除积分
				$order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
				if($order_result['error_code']){
					return $order_result;
				}
				$order_info['pay_money'] = $pay_money;
				return $this->wap_after_pay_before($order_info);
			}


			$pay_money -= $order_info['score_deducte'];
			//$data_plat_order['system_pay'] += $data_plat_order['score_deducte'];
		}

		//判断商家余额
		if(!empty($merchant_balance['card_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_money'] >= $pay_money){
				$data_plat_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_plat_order['merchant_balance'] = $merchant_balance['card_money'];
			}
			$pay_money -= $merchant_balance['card_money'];
		}

		if(!empty($merchant_balance['card_give_money'])&&$order_info['use_merchant_balance']){
			if($merchant_balance['card_give_money'] >= $pay_money){
				$data_plat_order['card_give_money'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_plat_order['card_give_money'] = $merchant_balance['card_give_money'];
			}
			$pay_money -= $merchant_balance['card_give_money'];
		}


		//判断帐户余额
		if(!empty($now_user['now_money'])&&$order_info['use_balance']){
			if($now_user['now_money'] >= $pay_money){
				$data_plat_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_plat_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付

		$order_result = $this->wap_pay_save_order($order_info,$data_plat_order);
		if($order_result['error_code']){
			return $order_result;
		}

		return array('error_code'=>false,'pay_money'=>$pay_money);
	}

	public function wap_pay_save_order($order_info,$data_plat_order){
		$condition_shop_order['order_id'] 		= $order_info['order_id'];
		$data_plat_order['system_coupon_id'] 			= !empty($data_plat_order['coupon_id']) ? $data_plat_order['coupon_id'] : 0;
		$data_plat_order['system_coupon_price'] 		= !empty($data_plat_order['coupon_price']) ? $data_plat_order['coupon_price'] : 0;
		$data_plat_order['merchant_coupon_id'] 			= !empty($data_plat_order['card_id']) ? $data_plat_order['card_id'] : 0;
		$data_plat_order['merchant_coupon_price'] 			= !empty($data_plat_order['card_price']) ? $data_plat_order['card_price'] : 0;
		$data_plat_order['merchant_balance_pay'] 	= !empty($data_plat_order['merchant_balance']) ? $data_plat_order['merchant_balance'] : 0;
		$data_plat_order['merchant_balance_give'] 	= !empty($data_plat_order['card_give_money']) ? $data_plat_order['card_give_money'] : 0;
		$data_plat_order['merchant_discount'] 	= !empty($data_plat_order['card_discount']) ? $data_plat_order['card_discount'] : 0;
		$data_plat_order['merchant_discount_money'] 	= !empty($data_plat_order['merchant_discount_money']) ? $data_plat_order['merchant_discount_money'] : 0;
		$data_plat_order['system_balance'] 		= !empty($data_plat_order['balance_pay']) ? $data_plat_order['balance_pay'] : 0;
		$data_plat_order['system_score']  	= !empty($data_plat_order['score_used_count'])?$data_plat_order['score_used_count']:0;
		$data_plat_order['system_score_money']     	= !empty($data_plat_order['score_deducte'])?(float)$data_plat_order['score_deducte']:0;
		$data_plat_order['last_time'] 			= $_SERVER['REQUEST_TIME'];
		$data_plat_order['submit_order_time'] 	= $_SERVER['REQUEST_TIME'];

		if ($this->where($condition_shop_order)->data($data_plat_order)->save()) {
			return array('error_code' => false, 'msg' => '保存订单成功！');
		} else {
			return array('error_code' => true, 'msg' => '保存订单失败！请重试或联系管理员。');
		}
	}

	public function wap_after_pay_before($order_info){
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'mer_id' => $order_info['mer_id'],
				'pay_type' => '',
				'third_id' => '',
				'is_mobile' => 0,
		);
		$result_after_pay = $this->after_pay($order_param);
		if($result_after_pay['error']){
			return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
		}
		return array('error_code'=>false,'msg'=>'支付成功！','url'=>str_replace('/source/','/',$result_after_pay['url']));
	}



	public function after_pay($order_param){
		if ($order_param['pay_type'] != '') {
			$where['orderid'] = $order_param['order_id'];
		} else {
			$where['order_id'] = $order_param['order_id'];
		}
		$now_order = $this->field(true)->where($where)->find();

		$business_order_table = D(ucfirst($now_order['business_type']).'_order');
		if (empty($now_order)) {
			return array('error' => 1, 'msg' => '当前订单不存在！');
		} elseif ($now_order['paid'] == 1){
			$url = $business_order_table->get_order_url($now_order['business_id'],$order_param['is_mobile']);
			return array('error' => 1, 'msg' => '该订单已付款！', 'url' => $url);
		} else {
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$tOrder = $business_order_table->where(array('order_id'=>$now_order['business_id']))->find();
			$order_param['mer_id'] = $tOrder['mer_id'];
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}

			if($now_order['merchant_coupon_id']){
				$now_coupon = D('Card_new_coupon')->get_coupon_by_id($now_order['merchant_coupon_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//判断平台优惠券
			if($now_order['system_coupon_id']){
				$now_coupon = D('System_coupon')->get_coupon_by_id($now_order['system_coupon_id']);
				if(empty($now_coupon)){
					return $this->wap_after_pay_error($now_order,$order_param,'您选择的优惠券不存在！');
				}
			}

			//判断会员卡余额
			$merchant_balance = floatval($now_order['merchant_balance_pay']);
			if($merchant_balance){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$order_param['mer_id']);
				if($user_merchant_balance['card_money'] < $merchant_balance){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}

			$card_give_money = floatval($now_order['merchant_balance_give']);
			
			if($card_give_money){
				$user_merchant_balance = D('Card_new')->get_card_by_uid_and_mer_id($now_order['uid'],$order_param['mer_id']);
				if($user_merchant_balance['card_money_give'] < $card_give_money){
					return $this->wap_after_pay_error($now_order,$order_param,'您的会员卡余额不够此次支付！');
				}
			}
			//判断帐户余额
			$balance_pay = floatval($now_order['system_balance']);
			if($balance_pay){
				if($now_user['now_money'] < $balance_pay){
					return $this->wap_after_pay_error($now_order,$order_param,'您的帐户余额不够此次支付！');
				}
			}

			//如果使用了商家优惠券
//			if($now_order['card_id']){
//				$use_result = D('Member_card_coupon')->user_card($now_order['card_id'],$now_order['mer_id'],$now_order['uid']);
//				if($use_result['error_code']){
//					return array('error'=>1,'msg'=>$use_result['msg']);
//				}
//			}

			if($now_order['merchant_coupon_id']){
				$use_result = D('Card_new_coupon')->user_coupon($now_order['merchant_coupon_id'],$now_order['business_id'],$now_order['business_type'],$order_param['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
			//如果使用了平台优惠券
			if($now_order['system_coupon_id']){

				$use_result = D('System_coupon')->user_coupon($now_order['system_coupon_id'],$now_order['business_id'],$now_order['business_type'],$order_param['mer_id'],$now_order['uid']);
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}


			//如果用户使用了积分抵扣，则扣除相应的积分
			//判断积分数量是否正确
			$score_used_count=$now_order['system_score'];
			if($now_user['score_count']<$score_used_count){
				return array('error'=>1,'msg'=>'保存订单失败！请重试或联系管理员。');
			}
			if($score_used_count>0){
				$use_result = D('User')->user_score($now_order['uid'],$score_used_count,'购买 '.$now_order['order_name'].' 扣除'.C('config.score_name'));
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			//如果使用会员卡余额
			if($merchant_balance){
				$use_result = D('Card_new')->use_money($now_order['uid'],$order_param['mer_id'],$merchant_balance,'购买 '.$now_order['order_name'].' 扣除会员卡余额');
				
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}

			if($card_give_money){
				$use_result = D('Card_new')->use_give_money($now_order['uid'],$order_param['mer_id'],$card_give_money,'购买 '.$now_order['order_name'].' 扣除会员卡赠送余额');
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


			$data_order = array();
			$data_order['pay_time'] = isset($order_param['pay_time']) && $order_param['pay_time'] ? strtotime($order_param['pay_time']) : $_SERVER['REQUEST_TIME'];
			$data_order['pay_money'] = floatval($order_param['pay_money']);//在线支付的钱
			$data_order['pay_type'] = $order_param['pay_type'];
			$data_order['third_id'] = $order_param['third_id'];
			$data_order['is_mobile_pay'] = $order_param['is_mobile'];
			$data_order['is_own'] = isset($order_param['sub_mch_id'])?2:$order_param['is_own'];
			$data_order['paid'] = 1;
			if($this->where($where)->data($data_order)->save()){
				$now_order = $this->field(true)->where($where)->find();
				if(isset($order_param['sub_mch_id']) && $order_param['sub_mch_id'] >0){
					$now_order['sub_mch_id'] = $order_param['sub_mch_id'];
				}

				$business_order_table->after_pay($now_order['business_id'],$now_order);
				$url = $business_order_table->get_order_url($now_order['business_id'],$order_param['is_mobile']);
				return array('error' => 0, 'url' => $url);
			}else{
				return array('error' => 1, 'msg' => '修改订单状态失败，请联系系统管理员！');
			}
		}
	}
	public function get_order_url($order_id,$is_mobile){
		$now_order = $this->where(array('order_id'=>$order_id))->find();

		$business_order_table = D(ucfirst($now_order['business_type']).'_order');

		return $business_order_table->get_order_url($now_order['business_id'],$is_mobile);
	}

	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips){

		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		$this->where(array('order_id'=>$now_order['order_id']))->setField('status',3);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			if($order_param['is_mobile']){
				$return_url = str_replace('/source/','/',U('My/group_order',array('order_id'=>$now_order['order_id'])));
			}else{
				$return_url = U('User/Index/group_order_view',array('order_id'=>$now_order['order_id']));
			}
			return array('error'=>1,'msg'=>$error_tips.'，已将您充值的金额添加到您的余额内。','url'=>$return_url);
		}
	}
}

?>