<?php
//超时强制接单
class plan_supply_order extends plan_base
{
	
	public function runTask()
	{
		$now_time = time();
		$deliver_timeout = 60 * C('config.deliver_timeout');
		$deliver_timeout2 = 60 * C('config.deliver_timeout2');
		if (empty($deliver_timeout)) return true;
		
		if ($deliver_time = C('config.delivery_time')) {
			$delivery_times = explode('-', $deliver_time);
			$start_time = $delivery_times[0] . ':00';
			$stop_time = $delivery_times[1] . ':00';
		} else {
			$start_time = 0;
			$stop_time = 0;
			$deliver_timeout2  = 0;
		}
		
		if ($delivery_time2 = C('config.delivery_time2')) {
			$delivery_times2 = explode('-', $delivery_time2);
			$start_time2 = $delivery_times2[0] . ':00';
			$stop_time2 = $delivery_times2[1] . ':00';
		} else {
			$start_time2 = 0;
			$stop_time2 = 0;
			$deliver_timeout2 = 0;
		}
			

		

		$deliver_timeout1 = $deliver_timeout2 ? min($deliver_timeout, $deliver_timeout2) : $deliver_timeout;
		$time = $now_time - $deliver_timeout1;
// 		$last_time = $now_time - $cancel_time - 5 * 60;//超时五分钟内的订单
		$list = D('Deliver_supply')->field(true)->where(array('type' => 0, 'item' => 2, 'status' => 1, 'create_time' => array('lt', $time)))->select();
// 		$list = D('Deliver_supply')->field(true)->where(array('type' => 0, 'item' => 2, 'status' => 1, 'create_time' => array(array('lt', $time), array('gt', $last_time))))->select();
		$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
		$href = C('config.site_url').'/wap.php?c=Deliver&a=pick';
		$now_time = time();
		foreach ($list as $row) {
			$this->keepThread();
			if ($row['appoint_time']) {
				$appoint_time = date('H:i:s', $row['appoint_time']);
				if ($start_time != 0 && $stop_time != 0 && $start_time <= $appoint_time && $appoint_time <= $stop_time) {
					if (($now_time - $row['create_time']) < $deliver_timeout) {
						continue;
					}
				}
				if ($start_time2 != 0 && $stop_time2 != 0 && $start_time2 <= $appoint_time && $appoint_time <= $stop_time2) {
					if (($now_time - $row['create_time']) < $deliver_timeout2) {
						continue;
					}
				}
			}
			
			$user = $this->distance($row);
			if ($user == null) continue;
			$result = D('Deliver_supply')->where(array('supply_id' => $row['supply_id']))->save(array('uid' => $user['uid'], 'status' => 2, 'get_type' => 1));
			if ($result) {
				if ($user['openid']) {
					$model->sendTempMsg('OPENTM405486394', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => $user['name'] . '您好！', 'keyword1' => '系统分配一个配送订单给您，请注意及时查收。', 'keyword2' => date('Y年m月d日 H:s'), 'keyword3' => '订单号：' . $row['real_orderid'], 'remark' => '请您及时处理！'));
				}
				$deliver_info = serialize(array('uid' => $user['uid'], 'name' => $user['name'], 'phone' => $user['phone'], 'store_id' => $user['store_id']));
				D('Shop_order')->where(array('order_id' => $row['order_id']))->data(array('order_status' => 2, 'deliver_info' => $deliver_info))->save();
				D('Shop_order_log')->add_log(array('order_id' => $row['order_id'], 'status' => 3, 'name' => $user['name'], 'phone' => $user['phone']));
			}
		}
		return true;
	}
	
	private function distance($supply)
	{
		if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $supply['store_id']))->find()) {
			$users = D('Deliver_user')->field(true)->where(array('circle_id' => $store['circle_id'], 'group' => 1, 'status' => 1))->select();
			$users || $users = D('Deliver_user')->field(true)->where(array('city_id' => $store['city_id'], 'group' => 1, 'status' => 1))->select();
			$users || $users = D('Deliver_user')->field(true)->where(array('province_id' => $store['province_id'], 'group' => 1, 'status' => 1))->select();
			if (empty($users)) return null;
			
// 			$uids = '';
// 			$pre = '';
			$distance = 0;
			$return_user = null;
			foreach ($users as $user) {
				$range = getDistance($supply['aim_lat'], $supply['aim_lnt'], $user['lat'], $user['lng']);
				if ($return_user == null) {
					$distance = $range;
					$return_user = $user;
				} elseif ($distance > $range) {
					$distance = $range;
					$return_user = $user;
				}
// 				$uids .= $pre . $user['uid'];
// 				$pre = ',';
			}
			return $return_user;
			$sql = "SELECT a.pigcms_id, a.uid, a.lat, a.lng FROM " . C('DB_PREFIX') . "deliver_user_location_log AS a INNER JOIN (SELECT uid, MAX(pigcms_id) AS pigcms_id FROM " . C('DB_PREFIX') . "deliver_user_location_log GROUP BY uid) AS b ON a.uid = b.uid AND a.pigcms_id = b.pigcms_id WHERE a.uid IN ({$uids})";
			$now_users = D()->query($sql);
			foreach ($now_users as $v) {
				$range = getDistance($supply['aim_lat'], $supply['aim_lnt'], $v['lat'], $v['lng']);
				if ($uid == 0) {
					$distance = $range;
					$uid = $v['uid'];
				} elseif ($distance > $range) {
					$distance = $range;
					$uid = $v['uid'];
				}
			}
			if ($uid) {
				$return_user = D('Deliver_user')->field(true)->where(array('uid' => $uid))->find();
			}
			return $return_user;
		}
		return null;
	}
}
?>