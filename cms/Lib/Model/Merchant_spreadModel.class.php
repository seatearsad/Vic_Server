<?php
//商家推广关系

class Merchant_spreadModel extends Model{
	//推广关系增加
	public function spread_add($mer_id,$openid,$from_type,$store_id){
		//自动领卡
		$auto_result = D('Card_new')->auto_reg_or_bind($mer_id,$openid);

		//绑定商家推广关系，不能是已绑定商家推广关系和用户推广关系
		if(!$this->where(array('openid'=>$openid))->find()&&!M('User_spread')->where(array('openid'=>$openid))->find()) {
			//如果用户以前是商家的粉丝，则让其成为最早关注的商家的推广关系
			if($user_mer = M('Merchant_user_relation')->where(array('openid'=>$openid))->order('dateline ASC')->find()) {
				$date['mer_id'] = $user_mer['mer_id'];
			}else{
				$date['mer_id'] = $mer_id;
			}

			$date['openid'] = $openid;
			$date['spread_time'] = $_SERVER['REQUEST_TIME'];
			$date['from_type'] = $from_type;
			$date['store_id'] = $store_id;
			$this->add($date);
		}
	}

	//增加推广佣金记录
	public function add_spread_list($order_info,$buyer,$type,$des){
		$spread_user= M('Merchant_spread')->where(array('openid'=>$buyer['openid']))->find();
		$spread_rate = D('Percent_rate')->get_rate($order_info['mer_id'],$type);
		$date['mer_id'] = $spread_user['mer_id'];
		if(empty($spread_user)||$spread_rate==0||$spread_user['mer_id']==$order_info['mer_id']||empty($buyer)){
			return true;
		}

		if($order_info['is_own']){
			$order_info['payment_money']=0;
		}

		$date['uid'] = $order_info['uid'];
		$date['openid'] = $buyer['openid'];
		$date['money'] = floor(($order_info['balance_pay']+$order_info['payment_money'])*$spread_rate)/100;
		$date['order_type'] = $type;
		switch($type){
			case 'group':
			case 'shop':
			case 'meal':
			case 'appoint':
				$type_name = C('config.'.$type.'_alias_name');
				break;
			case 'store':
				$type_name = '优惠买单';
				break;
			case 'cash':
				$type_name='到店支付';
				break;
			case 'wxapp':
				$type_name = '微信营销';
				break;
			case 'weidian':
				$type_name = '微店';
				break;
			case 'coupon':
				$type_name  = '平台活动';
				break;
			case 'yydb':
				$type_name  = '平台活动';
				break;
			case 'waimai':
				$type_name  = '外卖';
				break;
		}
		$date['order_id'] = $order_info['order_id'];
		$date['add_time'] = $_SERVER['REQUEST_TIME'];
		$date['des'] = $des;
		$date['status'] = 0;
		if($id = M('Merchant_spread_list')->add($date)&&$date['money']>0){  //增加商家余额
			$now_order['money'] = $date['money'];
			$now_order['order_type'] = 'spread';
			$now_order['order_id'] = $id;
			$now_order['mer_id']  = $spread_user['mer_id'];
			D('Merchant_money_list')->add_money($spread_user['mer_id'],'您的推广用户'.$buyer['nickname'].'购买'.$type_name.'商品获得佣金'.$date['money'].'元',$now_order);
		}
	}

	//商家获取推广用户列表（不包括商家粉丝）
	public function mer_spread_list($mer_id){
		$spread_list = $this->where(array('mer_id'=>$mer_id))->select();
		return $spread_list;
	}
}
?>