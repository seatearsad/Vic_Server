<?php
class OrderModel extends Model
{
	public function __construct() {}


	public function get_mer_bill($condition_merchant,$page_count=15){
		$database_merchant = M('Merchant');
		foreach($condition_merchant as $k=>$v){
			$condition_merchant['m.'.$k] = $v;
			unset($condition_merchant[$k]);
		}

		$count_merchant = $database_merchant->where($condition_merchant)->count();
		import('@.ORG.system_page');
		$p = new Page($count_merchant,$page_count);
		$merchant_list = $database_merchant->join('as m left join '.C('DB_PREFIX').'bill_time t ON m.mer_id = t.merid ')->field(true)->where($condition_merchant)->order('m.bill_time ASC ,m.mer_id DESC')->limit($p->firstRow.','.$p->listRows)->select();
		foreach($merchant_list as $key=>$val){
			$val['meal_bill_info']&&$merchant_list[$key]['bill_info']['meal']=unserialize($val['meal_bill_info']);
			$val['group_bill_info']&&$merchant_list[$key]['bill_info']['group']=unserialize($val['group_bill_info']);
			$val['appoint_bill_info']&&$merchant_list[$key]['bill_info']['appoint']=unserialize($val['appoint_bill_info']);
			$val['store_bill_info']&&$merchant_list[$key]['bill_info']['store']=unserialize($val['store_bill_info']);
			$val['weidian_bill_info']&&$merchant_list[$key]['bill_info']['weidian']=unserialize($val['weidian_bill_info']);
			$val['waimai_bill_info']&&$merchant_list[$key]['bill_info']['waimai']=unserialize($val['waimai_bill_info']);
			$val['wxapp_bill_info']&&$merchant_list[$key]['bill_info']['wxapp']=unserialize($val['wxapp_bill_info']);
			$val['shop_bill_info']&&$merchant_list[$key]['bill_info']['shop']=unserialize($val['shop_bill_info']);
		}
//		dump($merchant_list);
		$pagebar = $p->show();
		return array('merchant_list'=>$merchant_list,'pagebar'=>$pagebar);
	}

	public function get_mer_billed($condition_merchant,$page_count=15){
		$database_merchant = M('Merchant m');
		foreach($condition_merchant as $k=>$v){
			$condition_merchant['m.'.$k] = $v;
			unset($condition_merchant[$k]);
		}
		$count_merchant = M('Bill_time')->count();
		import('@.ORG.system_page');
		$p = new Page($count_merchant,$page_count);
		$merchant_list = $database_merchant->join('RIGHT JOIN '.C('DB_PREFIX').'bill_time t ON m.mer_id = t.merid ')->field(true)->where($condition_merchant)->order('t.update_time DESC')->limit($p->firstRow.','.$p->listRows)->select();

		$pagebar = $p->show();
		foreach($merchant_list as $key=>$val){
			$val['meal_bill_info']    &&$merchant_list[$key]['bill_info']['meal']=unserialize($val['meal_bill_info']);
			$val['group_bill_info']   &&$merchant_list[$key]['bill_info']['group']=unserialize($val['group_bill_info']);
			$val['appoint_bill_info'] &&$merchant_list[$key]['bill_info']['appoint']=unserialize($val['appoint_bill_info']);
			$val['store_bill_info']   &&$merchant_list[$key]['bill_info']['store']=unserialize($val['store_bill_info']);
			$val['weidian_bill_info'] &&$merchant_list[$key]['bill_info']['weidian']=unserialize($val['weidian_bill_info']);
			$val['waimai_bill_info']  &&$merchant_list[$key]['bill_info']['waimai']=unserialize($val['waimai_bill_info']);
			$val['wxapp_bill_info']   &&$merchant_list[$key]['bill_info']['wxapp']=unserialize($val['wxapp_bill_info']);
			$val['shop_bill_info']    &&$merchant_list[$key]['bill_info']['shop']=unserialize($val['shop_bill_info']);
			$bill_info_id =array(
					$merchant_list[$key]['bill_info']['meal']['bill_id'],
					$merchant_list[$key]['bill_info']['group']['bill_id'],
					$merchant_list[$key]['bill_info']['appoint']['bill_id'],
					$merchant_list[$key]['bill_info']['store']['bill_id'],
					$merchant_list[$key]['bill_info']['weidian']['bill_id'],
					$merchant_list[$key]['bill_info']['waimai']['bill_id'],
					$merchant_list[$key]['bill_info']['wxapp']['bill_id'],
					$merchant_list[$key]['bill_info']['shop']['bill_id']
			);
			$tmp = M('Bill_info')->where(array('id'=>array('in',$bill_info_id)))->select();
			foreach($tmp as $k=>$v){
				$merchant_list[$key]['bill_info'][$v['name']]['money']=$v['money'];
			}
		}
		return array('merchant_list'=>$merchant_list,'pagebar'=>$pagebar);
	}

	public function get_order_by_mer_id($mer_id, $type = 'meal', $is_system = false,$time,$is_pay_bill)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		$time_condition = '';
		if($time){
			$time = unserialize($time);
			if ($time['period']){
				if (is_array($time['period'])) {
					$time_condition = " AND (pay_time BETWEEN ".$time['period'][0].' AND '.$time['period'][1].")";
				}else{
					$time_condition = " AND pay_time=".$time['period'];
				}
			}  elseif ($time['month']) {
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))='.$time['year'].' AND month(FROM_UNIXTIME(pay_time))='.$time['month'];
			}elseif($time['year']){
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))='.$time['year'];
			}

		}

		if($is_pay_bill==1){
			$tmp_condition = " AND is_pay_bill in (1,2)";
		}else if($is_pay_bill==2){
			$tmp_condition = " AND is_pay_bill=0";
		}
		$time_condition .=$tmp_condition;
		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '1, 2')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '1, 2'),'_string'=>substr($time_condition,5)))->count();
				}
				$p = new Page($count, 20);
				$sql = "SELECT order_id, info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,score_deducte, card_id, merchant_balance, is_pay_bill,bill_time FROM ". C('DB_PREFIX') . "meal_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00') AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
				$sql .= " ORDER BY is_pay_bill, dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";

				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'group':
				$db = D('Group_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6'),'_string'=>substr($time_condition,5)))->count();
				}
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price,use_time as dateline, pay_time, paid, status, pay_type, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price, card_id, merchant_balance,score_deducte , refund_fee,refund_money ,is_pay_bill,real_orderid FROM ". C('DB_PREFIX') . "group_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2,6) AND (pay_type<>'offline' OR balance_pay<>'0.00') AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00') ".$time_condition;
				$sql .= " ORDER BY  is_pay_bill ASC ,use_time DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}' {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}'")->group('is_pay_bill')->select();
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'")->group('is_pay_bill')->select();
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1 {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT o.order_id, o.appoint_type as order_name, o.uid,o.mer_id, o.store_id, 1 as total, (pay_money+balance_pay) as price, o.payment_money as order_price, order_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance,coupon_price,score_deducte, is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order o LEFT JOIN ". C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id  WHERE a.payment_status=1 AND  o.mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);

				$total_list = $db->field('sum(balance_pay + pay_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1")->group('is_pay_bill')->select();
				break;
			case 'store':
				$db = D('Store_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, name as order_name, 0 as uid, mer_id, store_id, 1 as total, (payment_money+balance_pay) as price, (payment_money+balance_pay) as order_price, dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "store_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'waimai':
				$db = D('Waimai_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 {$time_condition}")->count();
				$p = new Page($count, 20);
				$sql = "SELECT order_id, uid, `desc` as order_name ,mer_id, store_id, 1 as total, (online_pay+balance_pay) as price, (online_pay+balance_pay) as order_price,create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, online_pay as payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "waimai_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND (online_pay<>'0.00' OR balance_pay<>'0.00')".$time_condition;
				$sql .= " ORDER BY  is_pay_bill,order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + online_pay) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0  AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'shop':
				$db = D('Shop_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '2,3')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, 'pay_type' => array('NEQ', 'offline'), 'status' => array('in', '2,3'),'_string'=>substr($time_condition,5)))->count();
				}
				$p = new Page($count, 20);
				$sql = "SELECT order_id, 3 as order_name, uid, mer_id, store_id, userphone as phone, num as total, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as price, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as order_price, create_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,card_price,score_deducte, card_id, merchant_balance, is_pay_bill,real_orderid FROM ". C('DB_PREFIX') . "shop_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND (pay_type<>'offline' OR balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')".$time_condition;
				$sql .= " ORDER BY is_pay_bill, dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";

				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay+payment_money+balance_reduce-no_bill_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND (pay_type<>'offline' OR balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')")->group('is_pay_bill')->select();
				break;
		}

		/** total: 本页的总额 ; finshtotal:本页已对账的总额; alltotal:未对账的总额; alltotalfinsh:全部已对账总额*/
		$total = $finshtotal = $alltotal = $alltotalfinsh =0;
		foreach ($total_list as $row) {
			$row['is_pay_bill'] && $alltotalfinsh += $row['price'];//已对账的总额
			$row['is_pay_bill'] || $alltotal += $row['price'];     //未对账的总额
		}
		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}

		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type_show'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);
			
			if($order['coupon_price']){
				$total += $order['price']+$order['coupon_price']+$order['score_deducte']; //本页的总额
			}else{
				$total += $order['price'];
			}
			//$order['system_pay']=$system_pay;
			$order['is_pay_bill'] && $finshtotal += $order['price'];	//本页已对账的总额
		}
		return array('order_list' => $order_list, 'pagebar' => $p->show(), 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);

	}

	public function bill_order($mer_id, $type = 'meal', $is_system = false,$time,$order_id)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		$time_condition = '';
		if($time){
			//$time = unserialize($time);
			if ($time['period']){
				if (is_array($time['period'])) {
					$time_condition = " AND (pay_time BETWEEN ".$time['period'][0].' AND '.$time['period'][1].")";
				}else{
					$time_condition = " AND pay_time=".$time['period'];
				}

			}

		}
		if($order_id){
			$time_condition .= ' AND order_id IN ('.$order_id.') ';
		}else{

			$time_condition .= " AND is_pay_bill=0";
		}

		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				if(empty($time)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0,'_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '1, 2'),'is_pay_bill'=>0))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0,'_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '1, 2'),'_string'=>substr($time_condition,5)))->count();
				}
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,score_deducte, card_id, merchant_balance, is_pay_bill,bill_time FROM ". C('DB_PREFIX') . "meal_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (payment_money <> '0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00')".$time_condition;
				$sql .= " ORDER BY dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'group':
				$db = D('Group_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, '_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id,'_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'paid' => 1, 'is_own' => 0, 'status' => array('in', '1, 2, 6'),'_string'=>substr($time_condition,5)))->count();
				}
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id, orderid,real_orderid,order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price, pay_time, paid, status, pay_type, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price, card_id, merchant_balance,score_deducte , refund_fee,refund_money ,is_pay_bill FROM ". C('DB_PREFIX') . "group_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2,6) AND ( payment_money <> '0.00' OR balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00')".$time_condition;
				$sql .= " ORDER BY use_time DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}' {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id, orderid,order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY  order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND pay_time<'{$time}'")->group('is_pay_bill')->select();
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid,order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'".$time_condition;
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'")->group('is_pay_bill')->select();
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1 {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT o.order_id,o.orderid, o.appoint_type as order_name, o.uid,o.mer_id, o.store_id, 1 as total, (pay_money+balance_pay) as price, o.payment_money as order_price, order_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance,coupon_price,score_deducte, is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order o LEFT JOIN ". C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id  WHERE a.payment_status=1 AND  o.mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline'  AND service_status=1".$time_condition;
				$sql .= " ORDER BY  order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);

				$total_list = $db->field('sum(balance_pay + pay_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1")->group('is_pay_bill')->select();
				break;
			case 'store':
				$db = D('Store_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, name as order_name, 0 as uid, mer_id, store_id, 1 as total, (payment_money+balance_pay) as price, (payment_money+balance_pay) as order_price, dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "store_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00' )".$time_condition;
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + payment_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'waimai':
				$db = D('Waimai_order');
				$count = $db->where("mer_id={$mer_id} AND paid=1 AND is_own=0 {$time_condition}")->count();
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, uid, `desc` as order_name ,mer_id, store_id, 1 as total, (online_pay+balance_pay) as price, (online_pay+balance_pay) as order_price,create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, online_pay as payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "waimai_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND (online_pay<>'0.00' OR balance_pay<>'0.00' )".$time_condition;
				$sql .= " ORDER BY order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay + online_pay) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0  AND (payment_money<>'0.00' OR balance_pay<>'0.00')")->group('is_pay_bill')->select();
				break;
			case 'shop':
				$db = D('Shop_order');
				if(empty($time_condition)){
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, '_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '2,3')))->count();
				}else{
					$count = $db->where(array('mer_id' => $mer_id, 'paid' => 1, 'is_own' => 0, '_string'=>"balance_pay<>'0.00' OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <>'0.00'", 'status' => array('in', '2,3'),'_string'=>substr($time_condition,5)))->count();
				}
				$limit =$time?$count:20;
				$p = new Page($count, $limit);
				$sql = "SELECT order_id,orderid, real_orderid,3 as order_name, uid, mer_id, store_id, userphone as phone, num as total, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as price, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as order_price, create_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money,coupon_price,card_price,score_deducte, card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "shop_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')".$time_condition;
				$sql .= " ORDER BY  dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";

				$order_list = $mode->query($sql);
				$total_list = $db->field('sum(balance_pay+payment_money+balance_reduce-no_bill_money) as price, is_pay_bill')->where("mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')")->group('is_pay_bill')->select();
				break;
		}

		/** total: 本页的总额 ; finshtotal:本页已对账的总额; alltotal:未对账的总额; alltotalfinsh:全部已对账总额*/
		$total = $finshtotal = $alltotal = $alltotalfinsh =0;
		foreach ($total_list as $row) {
			$row['is_pay_bill'] && $alltotalfinsh += $row['price'];//已对账的总额
			$row['is_pay_bill'] || $alltotal += $row['price'];     //未对账的总额
		}
		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}

		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type_show'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);
			if($type=='group'){
				$total+=$order['balance_pay']+$order['payment_money']+$order['score_deducte']+$order['coupon_price']-$order['refund_money'];
			}else if($type=='meal'||$type=='shop'||$type=='appoint'){
				$total+=$order['balance_pay']+$order['payment_money']+$order['score_deducte']+$order['coupon_price'];
			}else{
				$total+=$order['order_price'];
			}
			$order['is_pay_bill'] && $finshtotal += $order['price'];	//本页已对账的总额
		}
		return array('order_list' => $order_list, 'pagebar' => $p->show(), 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);

	}
	public function mer_bill($mer_id, $is_system = false,$type,$time,$store_id)
	{
		if ($is_system) {
			import('@.ORG.system_page');
		} else {
			import('@.ORG.merchant_page');
		}
		$time_condition = '';
		if($time) {
			$time = unserialize($time);
			if ($time['period']) {
				if (is_array($time['period'])) {
					$time_condition = " AND (pay_time BETWEEN " . $time['period'][0] . ' AND ' . $time['period'][1] . ")";
				} else {
					$time_condition = " AND pay_time=" . $time['period'];
				}
			} elseif ($time['month']) {
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))=' . $time['year'] . ' AND month(FROM_UNIXTIME(pay_time))=' . $time['month'];
			} elseif ($time['year']) {
				$time_condition = ' AND year(FROM_UNIXTIME(pay_time))=' . $time['year'];
			}

		}
		if (!empty($store_id)) {
			$time_condition .= ' AND store_id=' . $store_id;
		}
		if ($type=='waimai') {
			if(empty($time_condition)){
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1))->count();
			}else{
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1,'_string'=>substr($time_condition,5)))->count();
			}
		} elseif($type == 'shop') {
			if(empty($time_condition0)){
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'is_own' => 0, 'paid' => 1, 'status' => array('in', '3,2')))->count();
			}else{
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'is_own' => 0, 'paid' => 1, 'status' => array('in', '3,2'),'_string'=>substr($time_condition,5)))->count();
			}
// 			echo D(ucwords($type).'_order')->_sql();die;
		}else{
			if(empty($time_condition0)){
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'status' => array('in', '1, 2')))->count();
			}else{
				$count = D(ucwords($type).'_order')->where(array('mer_id' => $mer_id, 'pay_type' => array('NEQ', 'offline'), 'paid' => 1, 'status' => array('in', '1, 2'),'_string'=>substr($time_condition,5)))->count();
			}
		}

		$p = new Page($count, 20);


		switch ($type) {
			case 'meal':
				$sql = "SELECT order_id,  1 as name,info as order_name, uid, mer_id, store_id, phone, total, (balance_pay+payment_money) as price, price as order_price, dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, coupon_price,score_deducte,card_id, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "meal_order WHERE mer_id={$mer_id} AND paid=1 AND status in (1,2)  AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00')";
				break;
			case 'group':
				$sql = "SELECT order_id,real_orderid, order_name, uid, mer_id, store_id, phone, num as total, (balance_pay+payment_money) as price, total_money as order_price, use_time as dateline, paid, status, pay_type, pay_time, third_id, is_mobile_pay, balance_pay, payment_money, coupon_price,score_deducte,card_id, merchant_balance,refund_fee,refund_money, is_pay_bill FROM ". C('DB_PREFIX') . "group_order WHERE mer_id={$mer_id} AND paid=1 AND status in (1,2,6) AND (payment_money<>'0.00' OR balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00')";
				break;
			case 'weidian':
				$time = time() - 10 * 86400;
				$sql = "SELECT order_id, order_name, uid, mer_id, store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "weidian_order WHERE mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline' ";
				break;
			case 'appoint':
				$sql = "SELECT o.order_id, o.appoint_type as order_name, o.uid, o.mer_id, o.store_id, 1 as total, (pay_money+balance_pay) as price, o.payment_money as order_price, order_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, pay_money as payment_money, merchant_balance ,coupon_price,card_pirce,score_deducte,is_pay_bill FROM ". C('DB_PREFIX') . "appoint_order o LEFT JOIN ". C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id  WHERE a.payment_status=1 AND o.mer_id={$mer_id} AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1 AND (o.payment_money<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'wxapp':
				$sql = "SELECT order_id, order_name, uid, mer_id, 0 as store_id, order_num as total, (payment_money+balance_pay) as price, money as order_price, add_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money, merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "wxapp_order WHERE mer_id={$mer_id} AND paid=1 AND pay_type<>'offline' AND (payment_money<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'store':
				$sql = "SELECT order_id, name as order_name, 0 as uid, mer_id, store_id, 1 as total, (payment_money+balance_pay) as price, (payment_money+balance_pay) as order_price, dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money,  merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "store_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'waimai':
				$sql = "SELECT order_id, `desc` as order_name,  uid, mer_id, store_id, 1 as total, (online_pay+balance_pay) as price, (online_pay+balance_pay) as order_price, create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, online_pay as payment_money,  merchant_balance, is_pay_bill FROM ". C('DB_PREFIX') . "waimai_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND (online_pay<>'0.00' OR balance_pay<>'0.00')";
				break;
			case 'shop':
				$sql = "SELECT order_id, real_orderid, 3 as name, 3 as order_name,  uid, mer_id, store_id, num as total, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as price, (balance_pay+payment_money+coupon_price+score_deducte+balance_reduce-no_bill_money) as order_price, create_time as dateline, paid, pay_type, pay_time, third_id, balance_pay, payment_money,  merchant_balance, is_pay_bill, coupon_price, card_price, score_deducte FROM ". C('DB_PREFIX') . "shop_order WHERE mer_id={$mer_id} AND paid=1 AND is_own=0 AND status IN (2,3) AND (pay_type<>'offline' OR balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')";
				break;
			default:
				break;
		}

		$sql .=$time_condition. " ORDER BY is_pay_bill ASC, dateline DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$mode = new Model();
		$res = $mode->query($sql);
// 		echo $mode->_sql();
		$stores = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$temp = array();
		foreach ($stores as $store) {
			$temp[$store['store_id']] = $store;
		}

		$total = $finshtotal = 0;
		foreach ($res as &$l) {
			$l['store_name'] = isset($temp[$l['store_id']]['name']) ? $temp[$l['store_id']]['name'] : '';
			$l['name'] == 1 && $l['order_name'] = unserialize($l['order_name']);
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
			$l['score_deducte'] = isset($l['score_deducte']) ? $l['score_deducte'] : 0;
			$l['coupon_price'] = isset($l['coupon_price']) ? $l['coupon_price'] : 0;
			$l['card_price'] = isset($l['card_price']) ? $l['card_price'] : 0;
			$total += $l['price'] + $l['score_deducte'] + $l['coupon_price'];
			$l['is_pay_bill'] && $finshtotal += $l['price'];	//本页已对账的总额
			$l['order_price'] = round($l['order_price'], 2);
		}
		$pagebar = $p->show();
		return array('order_list' => $res, 'pagebar' => $pagebar, 'total' => $total, 'finshtotal' => $finshtotal, 'alltotalfinsh' => $alltotalfinsh, 'alltotal' => $alltotal);

	}

	public function export_order_by_mid($mer_id, $type = 'meal', $is_pay_bill = 0,$order_id)
	{
		$condition='';
		$is_pay_bill=' is_pay_bill = '.$is_pay_bill;
		if($order_id){
			$condition .= ' AND order_id IN ('.$order_id.') ';
		}
		$mode = new Model();
		switch ($type) {
			case 'meal':
				$db = D('Meal_order');
				$sql = "SELECT order_id, orderid, info as order_name, uid, mer_id, store_id, phone, total as num, price as order_price, dateline, paid, status, pay_type, pay_time,score_deducte, balance_pay, payment_money, coupon_price FROM ". C('DB_PREFIX') . "meal_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2) AND ( balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <> '0.00')".$condition;
				$sql .= " ORDER BY dateline DESC ";

				$order_list = $mode->query($sql);
				break;
			case 'group':
				$db = D('Group_order');


				$sql = "SELECT order_id, orderid,real_orderid,  order_name, uid, mer_id, store_id, phone, num , total_money as order_price,  pay_type, pay_time,  balance_pay, payment_money,  score_deducte,coupon_price,refund_money,refund_fee FROM ". C('DB_PREFIX') . "group_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (1,2,6) AND (balance_pay<>'0.00'  OR score_deducte <> '0.00' OR coupon_price <> '0.00' OR payment_money <> '0.00')".$condition;
				$sql .= " ORDER BY order_id DESC ";

				$order_list = $mode->query($sql);
				break;
			case 'weidian':
				$db = D('Weidian_order');
				$time = time() - 10 * 86400; //十天前的订单才能对账
				$sql = "SELECT order_id, orderid, order_name, uid, mer_id, store_id, order_num as num,  money as order_price, pay_type, pay_time, balance_pay, payment_money FROM ". C('DB_PREFIX') . "weidian_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND pay_time<'{$time}' AND pay_type<>'offline'".$condition;
				$sql .= " ORDER BY order_id DESC ";
				$order_list = $mode->query($sql);
				break;
			case 'wxapp':
				$db = D('Wxapp_order');
				$sql = "SELECT order_id,  orderid, order_name, uid, mer_id, 0 as store_id, order_num as num, money as order_price, pay_type, pay_time, balance_pay, payment_money FROM ". C('DB_PREFIX') . "wxapp_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND pay_type<>'offline'".$condition;
				$sql .= " ORDER BY order_id ";
				$order_list = $mode->query($sql);
				break;
			case 'appoint':
				$db = D('Appoint_order');
				$sql = "SELECT order_id,  orderid, appoint_id as order_name, uid, mer_id, store_id, 1 as num,  payment_money as order_price, pay_type, pay_time, balance_pay, pay_money as payment_money,  score_deducte,coupon_price FROM ". C('DB_PREFIX') . "appoint_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0  AND pay_type<>'offline' AND service_status=1".$condition;
				$sql .= " ORDER BY order_id DESC ";
				$order_list = $mode->query($sql);
				break;
			case 'store':
				$db = D('Store_order');
				$sql = "SELECT order_id, orderid, name as order_name, 0 as uid, mer_id, store_id, 1 as num, total_price as order_price, pay_type, pay_time, balance_pay, payment_money FROM ". C('DB_PREFIX') . "store_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND refund=0".$condition;
				$sql .= " ORDER BY order_id DESC";
				$order_list = $mode->query($sql);
				break;
			case 'waimai':
				$db = D('Waimai_order');
				$sql = "SELECT order_id, orderid, `desc` as order_name,  uid, mer_id, store_id, 1 as num, price as order_price, pay_type, pay_time, balance_pay, online_pay as payment_money FROM ". C('DB_PREFIX') . "waimai_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 ".$condition;
				$sql .= " ORDER BY order_id DESC";
				$order_list = $mode->query($sql);
				break;
			case 'shop':
				$db = D('Shop_order');
				$sql = "SELECT order_id,orderid, real_orderid, 3 as order_name, uid, mer_id, store_id, userphone as phone, num ,  (balance_pay+payment_money+balance_reduce-no_bill_money) as order_price,  pay_type, pay_time, balance_pay, payment_money,coupon_price,score_deducte FROM ". C('DB_PREFIX') . "shop_order WHERE {$is_pay_bill} AND mer_id={$mer_id} AND paid=1 AND is_own=0 AND status in (3,2) AND ( balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')".$condition;
				$sql .= " ORDER BY order_id DESC";
				$order_list = $mode->query($sql);
				break;
		}


		//商家的门店信息
		$store_list = D('Merchant_store')->field('store_id, name')->where(array('mer_id' => $mer_id))->select();
		$store_idkey_list = array();
		foreach ($store_list as $store) {
			$store_idkey_list[$store['store_id']] = $store;
		}

		foreach ($order_list as &$order) {
			$order['store_name'] = isset($store_idkey_list[$order['store_id']]['name']) ? $store_idkey_list[$order['store_id']]['name'] : '';
			if ($type == 'meal') {
				$order['order_name'] = unserialize($order['order_name']);
			} elseif ($type == 'appoint') {
				$order['order_name'] = $order['order_name'] == 0 ? '到店' : '上门';
			}
			$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay']);
		}
		return $order_list;
		//return array('order_list' => $order_list);

	}	
	
	
	public function get_offlineorder_by_mer_id($mer_id, $staff_name = '', $type = 'shop')
	{
		import('@.ORG.merchant_page');
		
		$field = '';
		if ($type == 'shop') {
			$field = "3 as name, order_id, orderid, price, payment_money, balance_pay, merchant_balance, coupon_price, card_price, score_deducte, score_deducte, (price-payment_money-balance_pay-merchant_balance-coupon_price-card_price-score_deducte-score_deducte) as cash, create_time, pay_time, is_pay_bill";
			$table_name = 'Shop_order';
			$where = " mer_id={$mer_id} AND paid=1 AND status IN (3,2) AND pay_type='offline'";
		} elseif ($type == 'meal') {
			$field = "1 as name, order_id, orderid, total_price-minus_price as price, payment_money, balance_pay, merchant_balance, coupon_price, card_price, score_deducte, score_deducte, (total_price-minus_price-payment_money-balance_pay-merchant_balance-coupon_price-card_price-score_deducte-score_deducte) as cash, dateline as create_time, pay_time, is_pay_bill";
			$table_name = 'Meal_order';
			$where = " mer_id={$mer_id} AND paid=1 AND status IN (1,2) AND pay_type='offline'";
		} elseif ($type == 'group') {
			$field = "2 as name, order_id, orderid, price, payment_money, balance_pay, merchant_balance, coupon_price, card_price, score_deducte, score_deducte, (price-payment_money-balance_pay-merchant_balance-coupon_price-card_price-score_deducte-score_deducte) as cash, add_time as create_time, pay_time, is_pay_bill";
			$table_name = 'Group_order';
			$where = " mer_id={$mer_id} AND paid=1 AND status IN (1,2) AND pay_type='offline'";
		}
		$staff_name && $where .= " AND last_staff='{$staff_name}'";
		$count = D($table_name)->where($where)->count();
		$uncount = D($table_name)->where($where . ' AND is_pay_bill=0')->count();
		$p = new Page($count, 20);
		$list = D($table_name)->field($field)->where($where)->order('is_pay_bill ASC')->limit("{$p->firstRow}, {$p->listRows}")->select();
		return array('order_list' => $list, 'pagebar' => $pagebar, 'uncount' => $uncount);
	}

}
?>