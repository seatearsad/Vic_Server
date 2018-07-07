<?php
class Merchant_store_foodshopModel extends Model
{
	public function get_list_group_by_option()
	{
		$now_time = time();
		$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id WHERE s.have_meal=1 AND s.status=1';
		$temps = D()->query($sql);
		$result = array();
		foreach ($temps as $tmp) {
			$tmp['group_list'] = '';
			$tmp['group_count'] = 0;
			$tmp['discount_txt'] = unserialize($tmp['discount_txt']);
			$result[$tmp['store_id']] = $tmp;
		}
		$store_id_arr = array_keys($result);
		if ($store_id_arr) {
			$store_ids = implode(',', $store_id_arr);
			$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id IN (' . $store_ids . ')';
			$groups = D()->query($sql);
			$group_image_class = new group_image();
			foreach ($groups as $row) {
				$tmp_pic_arr = explode(';', $row['pic']);
				$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
				$row['url'] = $this->get_group_url($row['group_id'], true);

				$row['price'] = floatval($row['price']);
				$row['old_price'] = floatval($row['old_price']);
				$row['wx_cheap'] = floatval($row['wx_cheap']);
				$row['is_start'] = 1;
				if ($now_time < $row['begin_time']) {
					$row['is_start'] = 0;
				}
				$row['begin_time'] = date("Y-m-d H:i:s", $row['begin_time']);
				if (isset($result[$row['store_id']])) {
					$result[$row['store_id']]['group_count']++;
					$result[$row['store_id']]['group_list'][] = $row;
				}
			}
		}
		return $result;
	}
	/*wap版得到指定分类ID或分类父ID下的分类，带有分页功能*/
// 	public function wap_get_storeList_by_catid($area_id = 0, $circle_id = 0, $order = '', $lat = 0, $long = 0, $cat_fid = 0, $cat_id = 0, $is_queue = -1)
	public function wap_get_storeList_by_catid($params,$is_wap)
	{
		$area_id = $params['area_id'];
		$circle_id = $params['circle_id'];
		$order = $params['sort'];
		$lat = $params['lat'];
		$long = $params['long'];
		$cat_fid = $params['cat_fid'];
		$cat_id = $params['cat_id'];
		$is_queue = $params['queue'];
		$keyword = $params['keyword'];
		$site_url  = C('config.site_url');

		$condition_field = '`f`.*, `s`.*';

// 		$condition_where = 's.have_meal=1 AND s.status=1';
		$condition_where = "s.city_id='" . C('config.now_city') . "' AND s.have_meal=1 AND s.status=1";
		if ($is_queue == 1) {
			$condition_where .= ' AND f.is_queue=1';
		} elseif ($is_queue == 0) {
			$condition_where .= ' AND f.is_queue=0';
		}
		//区域
		if($area_id || $circle_id){
			if ($circle_id) {
				$condition_where .= " AND `s`.`circle_id`='$circle_id'";
			} else {
				$condition_where .= " AND `s`.`area_id`='$area_id'";
			}
		}


		if ($cat_fid || $cat_id) {
			if ($cat_fid && $cat_id) {
				$relation = D('Meal_store_category_relation')->where(array(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id)))->select();
			} elseif ($cat_fid) {
				$relation = D('Meal_store_category_relation')->where(array(array('cat_fid' => $cat_fid)))->select();
			} else {
				$relation = D('Meal_store_category_relation')->where(array(array('cat_id' => $cat_id)))->select();
			}
			$store_ids = array();
			foreach ($relation as $r) {
				if (!in_array($r['store_id'], $store_ids)) {
					$store_ids[] = $r['store_id'];
				}
			}
			if ($store_ids) {
				$condition_where .= ' AND s.store_id IN (' . implode(',', $store_ids) . ')';
			} else {
				return array('store_list' => null, 'store_count' => 0, 'totalPage' => 0);
			}
		}
		if ($keyword) {
			$condition_where .= " AND `s`.`name` LIKE '%{$keyword}%'";
		}

		if ($lat && $long) {
			if ($order == 'juli') {
				$condition_field .= ", ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli,m.isverify";
			}
		} else {
			$order = $order == 'juli' ? '' : $order;
		}
		//排序
		switch($order){
			case 'rating':
				$order = '`f`.`score_mean` DESC, `f`.`store_id` DESC';
				break;
			case 'start':
				$order = '`f`.`create_time` DESC,`f`.`store_id` DESC';
				break;
			case 'juli':
				$order = '`juli` asc, `f`.`store_id` DESC';
				break;
			default:
				$order = '`f`.`create_time` DESC,`f`.`store_id` DESC';
		}

		import('@.ORG.wap_group_page');



		$sql_count = 'SELECT count(1) as cnt  FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id LEFT JOIN '.C('DB_PREFIX').'merchant mm ON mm.mer_id = s.mer_id WHERE ' . $condition_where .' AND mm.status = 1';

		$count_result = D()->query($sql_count);
// 		echo D()->_sql();die;
		$count = isset($count_result[0]['cnt']) ? intval($count_result[0]['cnt']) : 0;

		$p = new Page($count, 10, 'page');
		if($is_wap == 1){
			$pagesize	=	10;
			$star	=	((isset($params['page']) ? intval($params['page']): 0))*$pagesize;
		}else{
			$star	=	$p->firstRow;
			$pagesize	=	$p->listRows;
		}

		$sql = 'SELECT ' . $condition_field . ' FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id LEFT JOIN ' . C('DB_PREFIX') . 'merchant AS m ON m.mer_id = s.mer_id WHERE ' . $condition_where . ' AND m.status=1 ORDER BY ' . $order . ' LIMIT ' . $star . ',' . $pagesize;
		//fdump($sql,'sql',1);
		$store_list = D()->query($sql);
//	echo D()->_sql();die;
		import('@.ORG.longlat');
		$longlat_class = new longlat();


		foreach ($store_list as $tmp) {

			$tmps['name'] = $tmp['name'];
			$tmps['phone'] = $tmp['phone'];
			$tmps['adress'] = $tmp['adress'];
			$tmps['is_book'] = $tmp['is_book'];
			$tmps['is_queue'] = $tmp['is_queue'];
			$tmps['is_takeout'] = $tmp['is_takeout'];
			$tmps['long'] = $tmp['long'];
			$tmps['lat'] = $tmp['lat'];
			if ($tmp['juli']) {
				$tmps['range'] = getRange($tmp['juli']);
			} else {
				$location2 = $longlat_class->gpsToBaidu($tmp['lat'], $tmp['long']);//转换腾讯坐标到百度坐标
				$jl = getDistance($location2['lat'], $location2['lng'], $lat, $long);
				$tmps['range'] = getRange($jl);
			}
			$tmps['state'] = 0;//根据营业时间判断
			$time = date('H:i:s');
//			if ($tmp['open_1'] == '00:00:00' && $tmp['close_1'] == '00:00:00') {
//				$tmps['state'] = 1;
//			} elseif (($tmp['open_1'] < $time && $tmp['close_1'] > $time) || ($tmp['open_2'] < $time && $tmp['close_2'] > $time) || ($tmp['open_3'] < $time && $tmp['close_3'] > $time)) {
//				$tmps['state'] = 1;
//			}
            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            switch ($date){
                case 1 :
                    if (($tmp['open_1'] < $time && $tmp['close_1'] > $time) || ($tmp['open_2'] < $time && $tmp['close_2'] > $time) || ($tmp['open_3'] < $time && $tmp['close_3'] > $time)){
                        $tmps['state'] = 1;
                    }
                    break;
                case 2 ://周二
                    if (($tmp['open_4'] < $time && $tmp['close_4'] > $time) || ($tmp['open_5'] < $time && $tmp['close_5'] > $time) || ($tmp['open_6'] < $time && $tmp['close_6'] > $time)){
                        $tmps['state'] = 1;
                    }
                    break;
                case 3 ://周三
                    if (($tmp['open_7'] < $time && $tmp['close_7'] > $time) || ($tmp['open_8'] < $time && $tmp['close_8'] > $time) || ($tmp['open_9'] < $time && $tmp['close_9'] > $time)){
                        $tmps['state'] = 1;
                    }
                    break;
                case 4 :
                    if (($tmp['open_10'] < $time && $tmp['close_10'] > $time) || ($tmp['open_11'] < $time && $tmp['close_11'] > $time) || ($tmp['open_12'] < $time && $tmp['close_12'] > $time)){
                        $tmps['state'] = 1;
                    }
                    break;
                case 5 :
                    if (($tmp['open_13'] < $time && $tmp['close_13'] > $time) || ($tmp['open_14'] < $time && $tmp['close_14'] > $time) || ($tmp['open_15'] < $time && $tmp['close_15'] > $time)){
                        $tmps['state'] = 1;
                    }
                    break;
                case 6 :
                    if (($tmp['open_16'] < $time && $tmp['close_16'] > $time) || ($tmp['open_17'] < $time && $tmp['close_17'] > $time) || ($tmp['open_18'] < $time && $tmp['close_18'] > $time)){
                        $tmps['state'] = 1;
                    }
                    break;
                case 0 :
                    if (($tmp['open_19'] < $time && $tmp['close_19'] > $time) || ($tmp['open_20'] < $time && $tmp['close_20'] > $time) || ($tmp['open_21'] < $time && $tmp['close_21'] > $time)){
                        $tmps['state'] = 1;
                    }
                    break;
                default :
                    $tmps['state'] = 0;
            }
            //end  @wangchuanyuan
			$tmps['group_list'] = array();
			$tmps['group_count'] = 0;
			$tmps['pay_in_store'] = C('config.pay_in_store');
			$tmps['store_pay'] = $site_url.str_replace('appapi.php','wap.php',U('My/pay', array('store_id' => $tmp['store_id'])));
			$tmps['url'] = $site_url.str_replace('appapi.php','wap.php',U('Foodshop/shop', array('store_id' => $tmp['store_id'])));
			$tmps['discount_txt'] = $tmp['discount_txt'] ? unserialize($tmp['discount_txt']) : array();
			$tmps['score_mean'] = $tmp['score_mean']==0 ? 5.0 : $tmp['score_mean'];


			$result[$tmp['store_id']] = $tmps;
		}

		$store_id_arr = array_keys($result);
		if ($store_id_arr) {
			$store_ids = implode(',', $store_id_arr);
			$now_time = time();
			$sql = 'SELECT g.name,g.pic,g.group_id,g.price,g.old_price,g.wx_cheap,g.pin_num,g.sale_count,g.virtual_num,g.begin_time,gs.store_id FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id IN (' . $store_ids . ') AND `g`.`status`=1 AND `g`.`type`=1 AND `g`.`end_time`>\'' . $now_time . '\' ORDER BY `g`.`sort` DESC,`g`.`group_id` DESC';
			$groups = D()->query($sql);
			$group_image_class = new group_image();
			foreach ($groups as $row) {
				$tmp_pic_arr = explode(';', $row['pic']);
				$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
				$row['url'] =$site_url.$this->get_group_url($row['group_id'], true);

				$row['price'] = floatval($row['price']);
				$row['old_price'] = floatval($row['old_price']);
				$row['wx_cheap'] = floatval($row['wx_cheap']);
				$row['is_start'] = 1;
				if ($now_time < $row['begin_time']) {
					$row['is_start'] = 0;
				}
				if($row['begin_time']+864000>time()&&$row['sale_count']==0){
					$row['sale_txt'] = '新品上架';
				}elseif($row['begin_time']+864000<time()&&$row['sale_count']==0){
					$row['sale_txt'] = '';
				}else{
					$row['sale_txt'] = '已售'.floatval($row['sale_count']+$row['virtual_num']);
				}
				$row['begin_time'] = date("Y-m-d H:i:s", $row['begin_time']);
				if (isset($result[$row['store_id']])) {
					if ($result[$row['store_id']]['group_count'] < 2) {
						$result[$row['store_id']]['group_list'][] = $row;
					}
					$result[$row['store_id']]['group_count']++;
				}
			}
		}
		$_store_list = array();
		foreach ($result as $row) {
			$_store_list[] = $row;
		}
		$return['totalPage'] = $p->totalPage;//ceil($count / 10);


		$return['store_list'] = $_store_list;
		$return['store_count'] = $count;
		return $return;

	}
	public function get_group_url($group_id, $is_wap)
	{
		if ($is_wap) {
			return str_replace('appapi.php', 'wap.php', U('Wap/Group/detail', array('group_id' => $group_id)));
		} else {
			return C('config.site_url') . '/group/' . $group_id . '.html';
		}
	}

	public function get_shop_detail($store_id)
	{
		$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'merchant_store_foodshop AS f INNER JOIN ' . C('DB_PREFIX') . 'merchant_store AS s ON f.store_id=s.store_id WHERE s.have_meal=1 AND s.status=1 AND s.store_id=' . $store_id;
		$temps = D()->query($sql);
// 		echo '<Pre/>';
// 		print_r($temps);
		$shop = isset($temps[0]) ? $temps[0] : '';
		if ($shop) {
//			if ($shop['open_1'] == '00:00:00' && $shop['close_1'] == '00:00:00') {
//				$shop['business_time'] = '24小时营业';
//			} else {
//				$shop['business_time'] = $shop['open_1'] . '~' . $shop['close_1'];
//				if ($shop['open_2'] != '00:00:00' && $shop['close_2'] != '00:00:00') {
//					$shop['business_time'] = ',' . $shop['open_2'] . '~' . $shop['close_2'];
//				}
//				if ($shop['open_3'] != '00:00:00' && $shop['close_3'] != '00:00:00') {
//					$shop['business_time'] = ',' . $shop['open_3'] . '~' . $shop['close_3'];
//				}
//			}
            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            switch ($date){
                case 1 :
                    if ($shop['open_1'] != '00:00:00' && $shop['close_1'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_1'] . '~' . $shop['close_1'];
                    }
                    if ($shop['open_2'] != '00:00:00' && $shop['close_2'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_2'] . '~' . $shop['close_2'];
                    }
                    if ($shop['open_3'] != '00:00:00' && $shop['close_3'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_3'] . '~' . $shop['close_3'];
                    }
                    break;
                case 2 ://周二
                    if ($shop['open_4'] != '00:00:00' && $shop['close_4'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_4'] . '~' . $shop['close_4'];
                    }
                    if ($shop['open_5'] != '00:00:00' && $shop['close_5'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_5'] . '~' . $shop['close_5'];
                    }
                    if ($shop['open_6'] != '00:00:00' && $shop['close_6'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_6'] . '~' . $shop['close_6'];
                    }
                    break;
                case 3 ://周三
                    if ($shop['open_7'] != '00:00:00' && $shop['close_7'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_7'] . '~' . $shop['close_7'];
                    }
                    if ($shop['open_8'] != '00:00:00' && $shop['close_8'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_8'] . '~' . $shop['close_8'];
                    }
                    if ($shop['open_9'] != '00:00:00' && $shop['close_9'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_9'] . '~' . $shop['close_9'];
                    }
                    break;
                case 4 :
                    if ($shop['open_10'] != '00:00:00' && $shop['close_10'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_10'] . '~' . $shop['close_10'];
                    }
                    if ($shop['open_10'] != '00:00:00' && $shop['close_10'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_10'] . '~' . $shop['close_10'];
                    }
                    if ($shop['open_12'] != '00:00:00' && $shop['close_12'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_12'] . '~' . $shop['close_12'];
                    }
                    break;
                case 5 :
                    if ($shop['open_13'] != '00:00:00' && $shop['close_13'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_13'] . '~' . $shop['close_13'];
                    }
                    if ($shop['open_14'] != '00:00:00' && $shop['close_14'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_14'] . '~' . $shop['close_14'];
                    }
                    if ($shop['open_15'] != '00:00:00' && $shop['close_15'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_15'] . '~' . $shop['close_15'];
                    }
                    break;
                case 6 :
                    if ($shop['open_16'] != '00:00:00' && $shop['close_16'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_16'] . '~' . $shop['close_16'];
                    }
                    if ($shop['open_17'] != '00:00:00' && $shop['close_17'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_17'] . '~' . $shop['close_17'];
                    }
                    if ($shop['open_18'] != '00:00:00' && $shop['close_18'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_18'] . '~' . $shop['close_18'];
                    }
                    break;
                case 0 :
                    if ($shop['open_19'] != '00:00:00' && $shop['close_19'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_19'] . '~' . $shop['close_19'];
                    }
                    if ($shop['open_20'] != '00:00:00' && $shop['close_20'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_20'] . '~' . $shop['close_20'];
                    }
                    if ($shop['open_21'] != '00:00:00' && $shop['close_21'] != '00:00:00') {
                        $shop['business_time'] = ',' . $shop['open_21'] . '~' . $shop['close_21'];
                    }
                    break;
                default :
                    $shop['business_time'] = '未营业';
            }
            //end  @wangchuanyuan
			$shop['group_list'] = '';
			$shop['group_count'] = 0;
			$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id=' . $store_id;
			$groups = D()->query($sql);
			$group_image_class = new group_image();
			foreach ($groups as $row) {
				$tmp_pic_arr = explode(';', $row['pic']);
				$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
				$row['url'] = $this->get_group_url($row['group_id'], true);

				$row['price'] = floatval($row['price']);
				$row['old_price'] = floatval($row['old_price']);
				$row['wx_cheap'] = floatval($row['wx_cheap']);
				$row['is_start'] = 1;
				if ($now_time < $row['begin_time']) {
					$row['is_start'] = 0;
				}
				$row['begin_time'] = date("Y-m-d H:i:s", $row['begin_time']);
				$shop['group_list'][] = $row;
				$shop['group_count']++;
			}
		}
		return $shop;
	}
}
?>