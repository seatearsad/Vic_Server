<?php
class Shop_order_logModel extends Model
{
	public function add_log($param)
	{
		if (empty($param['order_id'])) return false;
		if ($order = D('Shop_order')->field(true)->where(array('order_id' => $param['order_id']))->find()) {
			//状态(0：用户下单，1：用户支付，2：店员接单，3：配送员接单，4：配送员取货，5：配送员送达，6：配送结束，7：店员验证消费，8：用户完成评论，9用户退款，10用户取消订单)
            //garfunkel add
            $store = D('Merchant_store')->where(array('store_id'=>$order['store_id']))->find();
            $area = D('Area')->where(array('area_id'=>$store['city_id']))->find();
            $dateline = time() + $area['jetlag']*3600;
            $data = array('dateline' => $dateline);
			$data['order_id'] = intval($param['order_id']);
			$data['status'] = isset($param['status']) ? intval($param['status']) : 0;
			$data['phone'] = isset($param['phone']) ? $param['phone'] : '';
			$data['name'] = isset($param['name']) ? $param['name'] : '';
			$data['note'] = isset($param['note']) ? $param['note'] : '';
			
			$now_user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
			if ($now_user['openid'] && $data['status'] != 0 && $data['status'] != 1) {
				$status = array('下单成功', '支付成功', '店员已接单', '配送员已接单', '配送员已取货' , '配送员配送中', '配送结束', '店员验证消费', '完成评论', '已完成退款', '已取消订单', '商家分配自提点', '商家发货到自提点', '自提点已接货', '自提点已发货', '您在自提完成取货', 30 => '店员修改价格');
				$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order['order_id'] . '&mer_id=' . $order['mer_id'] . '&store_id=' . $order['store_id'];
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.shop_alias_name').'订单', 'OrderSn' => $order['real_orderid'], 'OrderStatus' => $status[$data['status']], 'remark' => date('Y-m-d H:i:s')));
			}
			if ($data['status'] != 0) {
				import('@.ORG.Apppush');
				$order['status'] = $data['status'];
				$apppush = new Apppush();
				$apppush->send($order, 'shop');
			}
			return $this->add($data);
		}
	}
}
?>