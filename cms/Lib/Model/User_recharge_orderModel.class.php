<?php
class User_recharge_orderModel extends Model{
	public function get_pay_order($uid,$order_id,$is_web=false){
		$now_order = $this->get_order_by_id($uid,$order_id);
		//dump($now_order);exit;
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在！');
		}

		if($is_web){
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'order_type'		=>	($_GET['type'] == 'waimai-recharge' || $_POST['order_type'] == 'waimai-recharge') ? 'waimai-recharge' : 'recharge',
					'order_total_money'	=>	floatval($now_order['money']),
					'order_name'		=>	'在线充值',
					'order_num'			=>	1,
					'order_content'    =>  array(
							0=>array(
									'name'  		=> '在线充值',
									'num'   		=> 1,
									'price' 		=> floatval($now_order['money']),
									'money' 	=> floatval($now_order['money']),
							)
					)
			);
		}else{
			$order_info = array(
					'order_id'			=>	$now_order['order_id'],
					'order_type'		=>	($_GET['type'] == 'waimai-recharge' || $_POST['order_type'] == 'waimai-recharge') ? 'waimai-recharge' : 'recharge',
					'order_name'		=>	'在线充值',
					'order_num'			=>	1,
					'order_price'		=>	floatval($now_order['money']),
					'order_total_money'	=>	floatval($now_order['money']),
			);
		}
		return array('error'=>0,'order_info'=>$order_info);
	}
	public function get_order_by_id($uid,$order_id){
		$condition_user_recharge_order['uid'] = $uid;
		$condition_user_recharge_order['order_id'] = $order_id;
		return $this->field(true)->where($condition_user_recharge_order)->find();
	}
	//电脑站支付前订单处理
	public function web_befor_pay($order_info,$now_user){

		$data_user_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user_recharge_order['submit_order_time'] = $_SERVER['REQUEST_TIME'];
		$condition_user_recharge_order['order_id'] = $order_info['order_id'];
		if(!$this->where($condition_user_recharge_order)->data($data_user_recharge_order)->save()){
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
		return array('error_code'=>false,'pay_money'=>$order_info['order_total_money']);

	}
	//手机端支付前订单处理
	public function wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user){

		//去除微信优惠的金额
		$pay_money = $order_info['order_total_money'];

		//判断优惠券
		if(!empty($now_coupon['price'])){
			$data_weidian_order['card_id'] = $now_coupon['record_id'];
			if($now_coupon['price'] >= $pay_money){
				$order_result = $this->wap_pay_save_order($order_info,$data_weidian_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}
			$pay_money -= $now_coupon['price'];
		}

		//判断商家余额
		if(!empty($merchant_balance)){
			if($merchant_balance >= $pay_money){
				$data_weidian_order['merchant_balance'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_weidian_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_weidian_order['merchant_balance'] = $merchant_balance;
			}
			$pay_money -= $merchant_balance;
		}

		//判断帐户余额
		if(!empty($now_user['now_money'])){
			if($now_user['now_money'] >= $pay_money){
				$data_weidian_order['balance_pay'] = $pay_money;
				$order_result = $this->wap_pay_save_order($order_info,$data_weidian_order);
				if($order_result['error_code']){
					return $order_result;
				}
				return $this->wap_after_pay_before($order_info);
			}else{
				$data_weidian_order['balance_pay'] = $now_user['now_money'];
			}
			$pay_money -= $now_user['now_money'];
		}
		//在线支付
		$order_result = $this->wap_pay_save_order($order_info,$data_weidian_order);
		if($order_result['error_code']){
			return $order_result;
		}
		return array('error_code'=>false,'pay_money'=>$pay_money);
	}
	//手机端支付前保存各种支付信息
	public function wap_pay_save_order($order_info,$data_weidian_order){
		$data_weidian_order['card_id'] 			= !empty($data_weidian_order['card_id']) ? $data_weidian_order['card_id'] : 0;
		$data_weidian_order['merchant_balance'] 	= !empty($data_weidian_order['merchant_balance']) ? $data_weidian_order['merchant_balance'] : 0;
		$data_weidian_order['balance_pay']	 	= !empty($data_weidian_order['balance_pay']) ? $data_weidian_order['balance_pay'] : 0;
		$data_weidian_order['last_time'] = $_SERVER['REQUEST_TIME'];
		$condition_weidian_order['order_id'] = $order_info['order_id'];
		if($this->where($condition_weidian_order)->data($data_weidian_order)->save()){
			return array('error_code'=>false,'msg'=>'保存订单成功！');
		}else{
			return array('error_code'=>true,'msg'=>'保存订单失败！请重试或联系管理员。');
		}
	}
	//如果无需调用在线支付，使用此方法即可。
	public function wap_after_pay_before($order_info){
		$order_param = array(
				'order_id' => $order_info['order_id'],
				'pay_type' => '',
				'third_id' => '',
		);
		$result_after_pay = $this->after_pay($order_param);
		if($result_after_pay['error']){
			return array('error_code'=>true,'msg'=>$result_after_pay['msg']);
		}else{
			return array('error_code'=>false,'msg'=>'支付成功','url'=>$result_after_pay['url']);
		}
	}
	public function after_pay($order_param){
//		if($order_param['pay_type']!=''){
//			$where['orderid'] = $order_param['order_id'];
//		}else{
//			$where['order_id'] = $order_param['order_id'];
//		}

        $where['order_id'] = $order_param['order_id'];

		$now_order = $this->field(true)->where($where)->find();
		if(empty($now_order)){
			return array('error'=>1,'msg'=>'当前订单不存在');
		}else if($now_order['paid'] == 1){
			if($order_param['order_type'] == 'waimai-recharge'){
				if($order_param['is_mobile_pay']){
					return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('Waimai/User/index'));
				}else{
					return array('error'=>1,'msg'=>'该订单已付款！','url'=>U('Waimai/Asset/balance'));
				}
			}else{
				return array('error'=>1,'msg'=>'该订单已付款！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
			}
		}else{
			//得到当前用户信息，不将session作为调用值，因为可能会失效或错误。
			$now_user = D('User')->get_user($now_order['uid']);
			if(empty($now_user)){
				return array('error'=>1,'msg'=>'没有查找到此订单归属的用户，请联系管理员！');
			}

			$data_user_recharge_order = array();
			$data_user_recharge_order['pay_time'] = $_SERVER['REQUEST_TIME'];
			$data_user_recharge_order['payment_money'] = floatval($order_param['pay_money']);
			$data_user_recharge_order['pay_type'] = $order_param['pay_type'];
			$data_user_recharge_order['third_id'] = $order_param['third_id'];
			$data_user_recharge_order['paid'] = 1;

            //garfunkel add 添加充值赠送
            $config = D('Config')->get_config();
            $recharge_txt = $config['recharge_discount'];
            $recharge = explode(",",$recharge_txt);
            $recharge_list = array();
            foreach ($recharge as $v){
                $v_a = explode("|",$v);
                $recharge_list[$v_a[0]] = $v_a[1];
            }
            //逆序
            krsort($recharge_list);

            $money_plus = 0;
            foreach ($recharge_list as $k=>$v){
                if($data_user_recharge_order['payment_money'] >= $k){
                    $money_plus = $v;
                    break;
                }
            }
            $data_user_recharge_order['label'] = $money_plus;

			if($this->where($where)->save($data_user_recharge_order)){
                $add_money = $data_user_recharge_order['payment_money'] + $money_plus;
				D('User')->add_money($now_order['uid'],$add_money,'在线充值+'.$money_plus);
				D('Scroll_msg')->add_msg('user_recharge',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'充值成功！');
				if($order_param['order_type'] == 'waimai-recharge'){
					return array('error'=>0,'msg'=>'充值成功！','url'=>U('Waimai/User/index'));
				} else {
					return array('error'=>0,'msg'=>'充值成功！','url'=>$this->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay'],$now_order));
				}
			}else{
				return array('error'=>1,'msg'=>'修改订单状态失败，请联系系统管理员！');
			}
		}
	}

	public function get_pay_after_url($label,$is_mobile = false,$now_order){
		if($label){
			$labelArr = explode('_',$label);
			if($labelArr[0] == 'wap'){
				switch($labelArr[1]){
					case 'village':
						//直接验证订单
						$order_id = $labelArr[2];
						$now_order = D('House_village_pay_order')->field(true)->where(array('order_id'=>$order_id))->find();
						if($now_order['paid']){
							return U('House/pay_order',array('order_id'=>$order_id));
						}
						$use_result = D('User')->user_money($now_order['uid'],$now_order['money'],$now_order['order_name'].' 扣除余额');
						if(empty($use_result['error_code'])){
							$data_order['order_id'] = $order_id;
							$data_order['pay_time'] = $_SERVER['REQUEST_TIME'];
							$data_order['paid'] = 1;
							D('House_village_pay_order')->data($data_order)->save();
							$now_user_info = D('House_village_user_bind')->get_one($now_order['village_id'],$now_order['bind_id'],'pigcms_id');
							if($now_order['order_type'] != 'custom'){
								switch($now_order['order_type']){
									case 'property':
										$database_house_village_property_paylist = D('House_village_property_paylist');
										$paylist_data['bind_id'] = $now_user_info['pigcms_id'];
										$paylist_data['uid'] = $now_user_info['uid'];
										$paylist_data['village_id'] = $now_user_info['village_id'];
										$paylist_data['property_month_num'] = $now_order['property_month_num'];
										$paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'];
										$paylist_data['house_size'] = $now_order['house_size'];
										$paylist_data['property_fee'] = $now_order['property_fee'];
										$paylist_data['floor_type_name'] = $now_order['floor_type_name'];

										$now_pay_info = $database_house_village_property_paylist->where(array('bind_id'=>$now_user_info['pigcms_id']))->order('add_time desc')->find();
										if(!empty($now_pay_info)){
											$paylist_data['start_time'] = $now_pay_info['end_time'] ;
											$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_pay_info['end_time']);
										}else{
											if($now_user_info['add_time'] > 0){
												$paylist_data['start_time'] = $now_user_info['add_time'];
												$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
											}else{
												$paylist_data['start_time'] = time();
												$paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
											}

										}
										$paylist_data['add_time'] = time();
										$paylist_data['order_id'] = $order_id;
										$database_house_village_property_paylist->data($paylist_data)->add();


										$bind_field = 'property_price';
										$data_bind['property_month_num'] =  $now_user_info['property_month_num'] + $now_order['property_month_num'];
										$data_bind['presented_property_month_num'] = $now_user_info['presented_property_month_num'] + $now_order['presented_property_month_num'];

										break;
									case 'water':
										$bind_field = 'water_price';
										break;
									case 'electric':
										$bind_field = 'electric_price';
										break;
									case 'gas':
										$bind_field = 'gas_price';
										break;
									case 'park':
										$bind_field = 'park_price';
										break;
									default:
										$bind_field = '';
								}

								if(!empty($bind_field)){
									$data_bind['pigcms_id'] = $now_user_info['pigcms_id'];
									if($now_user_info[$bind_field] - $now_order['money'] >= 0){
										$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'];
									}else{
										$data_bind[$bind_field] = 0;
									}
									$data_bind[$bind_field] = $now_user_info[$bind_field] - $now_order['money'] >= 0 ? $now_user_info[$bind_field] - $now_order['money'] : 0;
									D('House_village_user_bind')->data($data_bind)->save();
								}
							}
							$database_user = D('User');
							$now_user = $database_user->get_user($now_order['uid']);
							if(!empty($now_user['openid'])){
								$href = $this->config['site_url'].'/wap.php?g=Wap&c=House&a=village_my_paylists&village_id='.$now_order['village_id'];
								$model = new templateNews($this->config['wechat_appid'],$this->config['wechat_appsecret']);
								$model->sendTempMsg('TM01008', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' =>  ' 缴费成功提醒', 'keynote1' =>$now_order['order_name'], 'keynote2' =>'物业号 '. $now_user_info['usernum'], 'remark' => '缴费时间：'.date('Y年n月j日 H:i',$now_order['time']).'\n'.'缴费金额：$'.$now_order['money']));
							}
						}


						return U('House/pay_order',array('order_id'=>$order_id));
					case 'activity':
						$activity_id = $labelArr[2];
						$quantity = $labelArr[3];
						return "/index.php?c=Activity&a=submit&r=1&id=".$activity_id."&q=".$quantity;
					case 'gift':
						$gift_id = $labelArr[2];
						$quantity = $labelArr[3];
						return "/wap.php?c=Gift&a=submit&r=1&order_id=".$gift_id."&q=".$quantity;
					case 'classify':
						$order_id = $labelArr[2];
						$quantity = $labelArr[3];
						return "/wap.php?c=Classify&a=submit&r=1&order_id=".$order_id."&q=".$quantity;
					case 'appoint':
						$order_id = $labelArr[2];
						return "/wap.php?c=My&a=submit&r=1&order_id=" . $order_id . '&is_initiative=1&user_recharge_order_id='.$now_order['order_id'];
					case 'crowdsourcing':
						$wap = $labelArr[2];
						$order_id = $labelArr[3];
						$status = $labelArr[4];
						if($wap == 1){
							return "/wap_house.php?c=Crowdsourcing&a=grab_single&package_id=".$order_id."&status=" . $status;
						}else{
							return "/wap.php?c=Crowdsourcing&a=grab_single&package_id=".$order_id."&status=" . $status;
						}
					case 'ride':
						$wap = $labelArr[2];
						$order_id = $labelArr[3];
						$status = $labelArr[4];
						if($wap == 1){
							return "/wap_house.php?c=Ride&a=ride_place_order&ride_id=".$order_id."&status=" . $status;
						}else{
							return "/wap.php?c=Ride&a=ride_place_order&ride_id=".$order_id."&status=" . $status;
						}
					case 'express':
						$village_express_order_id = $labelArr[2];
						$data_village_express_order['paid']=1;
						$data_village_express_order['pay_time']=$_SERVER['REQUEST_TIME'];
						return "/wap.php?g=Wap&c=Library&a=express_submit&order_id=".$village_express_order_id;

				}
			}
		}else{
			if($is_mobile){
				return U('My/transaction');
			}else{
				return U('User/Credit/index');
			}
		}
	}

	//支付时，金额不够，记录到帐号
	public function wap_after_pay_error($now_order,$order_param,$error_tips){
		//记录充值的金额，因为 Pay/return_url 处没有返回order的具体信息，故在此调用。
		$user_result = D('User')->add_money($now_order['uid'],$order_param['pay_money'],'支付订单：'.$now_order['order_id'].'发生错误！原因是：'.$error_tips);
		if($order_param['pay_type']=='weixin'&&ACTION_NAME=='return_url'){
			exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
		}
		if($user_result['error_code']){
			return array('error'=>1,'msg'=>$user_result['msg']);
		}else{
			return array('error'=>1,'msg'=>$error_tips.'已将您充值的金额添加到您的余额内。');
		}
	}
}
?>