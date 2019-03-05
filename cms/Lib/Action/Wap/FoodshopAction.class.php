<?php
class FoodshopAction extends BaseAction
{
	
	protected $weeks = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
	/**
	 * 店铺列表
	 */
	public function index()
	{
		//导航条
		$adver	=	D('Adver')->get_adver_by_key('wap_foodshop_index_top', 5);
		$this->assign('wap_foodshop_index_top', $adver);
		$this->assign('wap_foodshop_slider', D('Slider')->get_slider_by_key('wap_foodshop_slider', 8));
// 		$this->assign('wap_foodshop_slider', D('Slider')->get_slider_by_key('wap_slider', 8));
		
		
		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
		$this->assign('now_area_url', $area_url);
		$circle_id = 0;
		$area_id = 0;
		if (!empty($area_url)) {
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}
			$this->assign('now_area', $tmp_area);
		
			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$this->assign('now_circle', $now_circle);
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$this->assign('top_area', $now_area);
			$area_id = $now_area['area_id'];
		}
		
		//判断排序信息

		$sort = !empty($_GET['sort']) ? htmlspecialchars(trim($_GET['sort'])) : 'juli';
		$queue = isset($_GET['queue']) ? intval($_GET['queue']) : -1;
		
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
		if (empty($long_lat)) {
			$sort = $sort == 'juli' ? 'defaults' : $sort;
			$sort_array = array(
					array('sort_id'=>'defaults','sort_value'=>'智能排序'),
					array('sort_id'=>'rating','sort_value'=>'评价最高'),
			);
		} else {
			$sort_array = array(
					array('sort_id'=>'juli', 'sort_value'=>'离我最近'),
					array('sort_id'=>'rating', 'sort_value'=>'评价最高'),
					array('sort_id'=>'defaults', 'sort_value'=>'智能排序'),
			);
			$this->assign('long_lat', $long_lat);
		}
		foreach ($sort_array as $key => $value) {
			if ($sort == $value['sort_id']) {
				$now_sort_array = $value;
				break;
			}
		}
		
		$queue_array = array('-1' => '不限', '0' => '无排号', '1' => '可排号');
		$other = $now_sort_array['sort_value'] . '/' . $queue_array[$queue];
		$this->assign('other', $other);
		$this->assign('sort_array', $sort_array);
		$this->assign('now_sort_array', $now_sort_array);
		
		$all_area_list = D('Area')->get_all_area_list();
		$this->assign('all_area_list', $all_area_list);
		
		$cat_url = isset($_GET['cat_url']) ? htmlspecialchars(trim(($_GET['cat_url']))) : 'all';
		$this->assign('now_cat_url', $cat_url);
		$this->assign('now_queue', $queue);
		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
			if (empty($now_category)) {
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);
				
			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
		
				$this->assign('top_category',$f_category);
					
				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$this->assign('top_category',$now_category);
					
				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}
		$all_category_list = D('Meal_store_category')->get_all_category();
		$this->assign('all_category_list', $all_category_list);
		$this->display();
	}
	
	public function ajaxList()
	{
		$this->header_json();

		//判断地区信息
		$area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';

		$circle_id = 0;
		$area_id = 0;
		if (!empty($area_url)) {
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->error('当前区域不存在！');
			}

			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->error('当前区域不存在！');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$area_id = $now_area['area_id'];
		}

		$cat_url = isset($_GET['cat_url']) ? htmlspecialchars(trim(($_GET['cat_url']))) : 'all';
		
		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
			if (empty($now_category)) {
				$this->error_tips('此分类不存在！');
			}
			$this->assign('now_category',$now_category);
				
			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
		
				$this->assign('top_category',$f_category);
					
				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$this->assign('top_category',$now_category);
				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}
		
		$long_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);

		//判断排序信息
		$sort = isset($_GET['sort']) ? htmlspecialchars(trim($_GET['sort'])) : 'juli';
		$queue = isset($_GET['queue']) ? intval($_GET['queue']) : -1;
		$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
		
		$where = array('area_id' => $area_id, 'circle_id' => $circle_id, 'cat_fid' => $cat_fid, 'cat_id' => $cat_id, 'lat' => $long_lat['lat'], 'long' => $long_lat['long'], 'sort' => $sort, 'queue' => $queue, 'keyword' => $keyword);
		
		$return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($where);
// 		$return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($area_id, $circle_id, $sort_id, $long_lat['lat'], $long_lat['long'], $cat_url);
		echo json_encode($return);
	}
	
	/**
	 * 店铺详情
	 */
	public function shop()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;

		$store = M('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store)) {
			$this->error_tips('不存在的店铺');
			exit;
		}  else {
            $store['business_time'] = '';
            $store['is_close'] = 1;

//
//            if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
//				$store['is_close'] = 0;
//				$store['business_time'] = '24小时营业';
//			} else {
//				$now_time = date('H:i:s');
//				$store['business_time'] = substr($store['open_1'], 0, -3) . '~' . substr($store['close_1'], 0, -3);
//				if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
//					$store['is_close'] = 0;
//				}
//				if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
//					$store['business_time'] .= ';' . substr($store['open_2'], 0, -3) . '~' . substr($store['close_2'], 0, -3);
//					if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
//						$store['is_close'] = 0;
//					}
//				}
//				if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
//					$store['business_time'] .= ';' . substr($store['open_3'], 0, -3) . '~' . substr($store['close_3'], 0, -3);
//					if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
//						$store['is_close'] = 0;
//					}
//				}
//			}

            if($store['store_is_close'] != 0){
                $store = checkAutoOpen($store);
            }
            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            $now_time = date('H:i:s');
            switch ($date){
                case 1 :
                    if ($store['open_1'] != '00:00:00' || $store['close_1'] != '00:00:00'){
                        if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
                            $store['is_close'] = 0;
                        }
                    }
                    if($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00'){
                        if($store['open_2'] < $now_time && $now_time < $store['close_2']) {
                            $store['is_close'] = 0;
                        }
                    }
                    if($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00'){
                        if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
                            $store['is_close'] = 0;
                        }
                    }
                    $store['business_time'] = substr($store['open_1'], 0, -3) . '~' . substr($store['close_1'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_2'], 0, -3) . '~' . substr($store['close_2'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_3'], 0, -3) . '~' . substr($store['close_3'], 0, -3);
                    break;
                case 2 ://周二
                    if ($store['open_4'] != '00:00:00' || $store['close_4'] != '00:00:00') {
                        if ($store['open_4'] < $now_time && $now_time < $store['close_4']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_5'] != '00:00:00' || $store['close_5'] != '00:00:00') {
                        if ($store['open_5'] < $now_time && $now_time < $store['close_5']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_6'] != '00:00:00' || $store['close_6'] != '00:00:00') {
                        if ($store['open_6'] < $now_time && $now_time < $store['close_6']){
                            $store['is_close'] = 0;
                        }
                    }
                    $store['business_time'] = substr($store['open_4'], 0, -3) . '~' . substr($store['close_4'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_5'], 0, -3) . '~' . substr($store['close_5'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_6'], 0, -3) . '~' . substr($store['close_6'], 0, -3);
                    break;
                case 3 ://周三
                    if ($store['open_7'] != '00:00:00' || $store['close_7'] != '00:00:00') {
                        if ($store['open_7'] < $now_time && $now_time < $store['close_7']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_8'] != '00:00:00' || $store['close_8'] != '00:00:00') {
                        if ($store['open_8'] < $now_time && $now_time < $store['close_8']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_9'] != '00:00:00' || $store['close_9'] != '00:00:00') {
                        if ($store['open_9'] < $now_time && $now_time < $store['close_9']){
                            $store['is_close'] = 0;
                        }
                    }
                    $store['business_time'] = substr($store['open_7'], 0, -3) . '~' . substr($store['close_7'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_8'], 0, -3) . '~' . substr($store['close_8'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_9'], 0, -3) . '~' . substr($store['close_9'], 0, -3);

                    break;
                case 4 :
                    if ($store['open_10'] != '00:00:00' || $store['close_10'] != '00:00:00') {
                        if ($store['open_10'] < $now_time && $now_time < $store['close_10']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_11'] != '00:00:00' || $store['close_11'] != '00:00:00') {
                        if ($store['open_11'] < $now_time && $now_time < $store['close_11']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_12'] != '00:00:00' || $store['close_12'] != '00:00:00') {
                        if ($store['open_12'] < $now_time && $now_time < $store['close_12']){
                            $store['is_close'] = 0;
                        }
                    }
                    $store['business_time'] = substr($store['open_10'], 0, -3) . '~' . substr($store['close_10'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_11'], 0, -3) . '~' . substr($store['close_11'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_12'], 0, -3) . '~' . substr($store['close_12'], 0, -3);
                    break;
                case 5 :
                    if ($store['open_13'] != '00:00:00' || $store['close_13'] != '00:00:00') {
                        if ($store['open_13'] < $now_time && $now_time < $store['close_13']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_14'] != '00:00:00' || $store['close_14'] != '00:00:00') {
                        if ($store['open_14'] < $now_time && $now_time < $store['close_14']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_15'] != '00:00:00' || $store['close_15'] != '00:00:00') {
                        if ($store['open_15'] < $now_time && $now_time < $store['close_15']){
                            $store['is_close'] = 0;
                        }
                    }
                    $store['business_time'] = substr($store['open_13'], 0, -3) . '~' . substr($store['close_13'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_14'], 0, -3) . '~' . substr($store['close_14'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_15'], 0, -3) . '~' . substr($store['close_15'], 0, -3);
                    break;
                case 6 :
                    if ($store['open_16'] != '00:00:00' || $store['close_16'] != '00:00:00') {
                        if ($store['open_16'] < $now_time && $now_time < $store['close_16']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_17'] != '00:00:00' || $store['close_17'] != '00:00:00') {
                        if ($store['open_17'] < $now_time && $now_time < $store['close_17']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_18'] != '00:00:00' || $store['close_18'] != '00:00:00') {
                        if ($store['open_18'] < $now_time && $now_time < $store['close_18']){
                            $store['is_close'] = 0;
                        }
                    }
                    $store['business_time'] = substr($store['open_16'], 0, -3) . '~' . substr($store['close_16'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_17'], 0, -3) . '~' . substr($store['close_17'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_18'], 0, -3) . '~' . substr($store['close_18'], 0, -3);
                    break;
                case 0 :
                    if ($store['open_19'] != '00:00:00' || $store['close_19'] != '00:00:00') {
                        if ($store['open_19'] < $now_time && $now_time < $store['close_19']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_20'] != '00:00:00' || $store['close_20'] != '00:00:00') {
                        if ($store['open_20'] < $now_time && $now_time < $store['close_20']){
                            $store['is_close'] = 0;
                        }
                    }
                    if ($store['open_21'] != '00:00:00' || $store['close_21'] != '00:00:00') {
                        if ($store['open_21'] < $now_time && $now_time < $store['close_21']){
                            $store['is_close'] = 0;
                        }
                    }
                    $store['business_time'] = substr($store['open_19'], 0, -3) . '~' . substr($store['close_19'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_20'], 0, -3) . '~' . substr($store['close_20'], 0, -3);
                    $store['business_time'] .= ';' . substr($store['open_21'], 0, -3) . '~' . substr($store['close_21'], 0, -3);
                    break;
                default :
                    $store['is_close'] = 1;
                    $store['business_time']= '营业时间未知';
            }
            //end  @wangchuanyuan
            //garfunkel add
            if($store['store_is_close'] != 0){
                $store['is_close'] = 1;
            }
		}

		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($store['pic_info']);
		$store['image_list'] = $images;
		$store['image'] = $images ? array_shift($images) : array();
			
		$merchant = M('Merchant')->field(true)->where(array('mer_id' => $store['mer_id']))->find();
		if (empty($merchant)) {
			$this->error_tips('不存在的商家');
			exit;
		}
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($foodshop)) {
			$this->error_tips('不存在的餐饮店铺');
			exit;
		}
		
		$foodshop = array_merge($store, $foodshop);
		
		$card_info = D('Card_new')->get_card_by_mer_id($foodshop['mer_id']);
		$coupon_list = D('Card_new_coupon')->get_coupon_list_by_type_merid('meal',$foodshop['mer_id'],0,5,-1);
	
		$this->assign('card_info',$card_info);
		$this->assign('coupon_list',$coupon_list);

// 		echo '<pre/>';
// 		print_r($foodshop);die;
		$now_time = time();
		$sql = 'SELECT * FROM ' . C('DB_PREFIX') . 'group_store AS gs INNER JOIN ' . C('DB_PREFIX') . 'group AS g ON g.group_id=gs.group_id WHERE gs.store_id=' . $store_id . ' AND `g`.`status`=1  AND `g`.`type`=1 AND `g`.`end_time`>\'' . $now_time . '\' ORDER BY `g`.`sort` DESC,`g`.`group_id` DESC';
		$groups = D()->query($sql);
		$group_image_class = new group_image();
		foreach ($groups as $row) {
			$tmp_pic_arr = explode(';', $row['pic']);
			$row['list_pic'] = $group_image_class->get_image_by_path($tmp_pic_arr[0], 's');
			$row['url'] =  U('Group/detail', array('pin_num' => 0, 'group_id' => $row['group_id']), true, false, true);
		
			$row['price'] = floatval($row['price']);
			$row['old_price'] = floatval($row['old_price']);
			$row['wx_cheap'] = floatval($row['wx_cheap']);
			$row['is_start'] = 1;
			$row['pin_num'] = $row['pin_num'];
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
			$foodshop['group_list'][] = $row;
		}

		$this->assign('shop', $foodshop);

		$goods_list = M('Foodshop_goods')->field('name')->where(array('store_id' => $store_id, 'status' => 1, 'is_hot' => 1))->select();
		$this->assign('goods_list', $goods_list);
		
		$reply_list = D('Reply')->get_reply_list($store_id, 4, 1, 3);
		
		$reply_count = D('Reply')->where(array('status' => array('lt', 2), 'parent_id' => $store_id, 'order_type' => 4))->count();
// 		echo '<pre/>';
// 		print_r($reply_list);die;
		$this->assign('reply_list', $reply_list);
		$this->assign('merchant', $merchant);
		$this->assign('reply_count', $reply_count);
		$this->display();
	}
	
	/**
	 * 预约下单
	 */
	public function book_order()
	{
		$this->isLogin();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];
		if ($foodshop['is_book'] == 0) {
			$this->error_tips('该店铺不支预订');
			exit;
		}
		
		
		//最少可约的人数
		$table_type_data = D('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->order('min_people asc')->select();
		if (empty($table_type_data)) {
			$this->error_tips('没有可预约的桌台');
			exit;
		}
		$table_type_data = $table_type_data[0];
		$book_num = isset($table_type_data['min_people']) ? $table_type_data['min_people'] : 2;
		$table_type = $table_type_data['id'];
		$now_time = time() + $foodshop['advance_time'] * 60;
		
		$order_list = M('Foodshop_order')->field(true)->where(array('table_type' => $table_type, 'book_time' => array('egt', $now_time), 'status' => array('in', array(1, 2))))->select();
		$order_table_list = array();
		foreach ($order_list as $order) {
			$order_table_list[date('YmdHi', $order['book_time'])][] = $order;
		}
		
		$order_date_count = array();
		if ($order_table_list) {
			foreach ($order_table_list as $index => $row) {
				if ($table_type_data['num'] <= count($row)) {
					$order_date_count[$index] = 1;
				} else {
					$order_date_count[$index] = 0;
				}
			}
		}
		
		$now_time = time() + $foodshop['advance_time'] * 60;
		$loop_time = $foodshop['book_time'] * 60;
		$start_time = $foodshop['book_start'];
		$stop_time = $foodshop['book_stop'];
		if ($start_time == '00:00:00' && $stop_time == '00:00:00') {
			$stop_time = '23:59:59';
		}
		$book_time = 0;
		for ($d = 0; $d <= $foodshop['book_day']; $d++) {
			$this_start_time = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $start_time);
			$this_stop_time = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $stop_time);
			if ($this_start_time < $this_stop_time) {
				for ($t = $this_start_time; $t <= $this_stop_time; $t += $loop_time) {
					if ($t < $now_time) {
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
						} else {
							$book_time = date('Y-m-d H:i', $t);
							break;
						}
					}
				}
				if ($book_time) break;
			} else {
				$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . '23:59:59');
				for ($t = $this_start_time; $t <= $stop_time_t; $t += $loop_time) {
					if ($t < $now_time) {
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
						} else {
							$book_time = date('Y-m-d H:i', $t);
							break;
						}
					}
				}
				if ($book_time) break;
				$d_t = $d + 1;
				if ($d_t < $foodshop['book_day']) {
					$start_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . '00:00:00');
					$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . $stop_time);
					for ($t = $start_time_t; $t <= $stop_time_t; $t += $loop_time) {
						if ($t < $now_time) {
						} else {
							if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
							} else {
								$book_time = date('Y-m-d H:i', $t);
								break;
							}
						}
					}
					if ($book_time) break;
				}
				if ($book_time) break;
			}
		}
		$return = $this->format_data($foodshop, strtotime($book_time), $book_num, $table_type);
		if ($return['err_code']) {
			$this->error_tips($return['msg']);
			exit;
		}
// 		$table_type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->select();
// 		$this->assign('table_list', $table_type_data);
		$this->assign($return);
		$this->assign(array('sex' => $this->user_session['sex'] == 1 ? 1 : 0, 'name' => $this->user_session['truename'] ? $this->user_session['truename'] : $this->user_session['nickname'], 'phone' => $this->user_session['phone']));
		$this->assign('store', $foodshop);
		$this->display();
	}

	private function now_store($store_id, $is_return = true) 
	{
		$store = M('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store) && $is_return) {
			return array('err_code' => true, 'msg' => '不存在的店铺');
		} elseif ($store['status'] != 1 && $is_return) {
			return array('err_code' => true, 'msg' => '店铺状态异常');
		}  elseif ($store['have_meal'] != 1 && $is_return) {
			return array('err_code' => true, 'msg' => '店铺不支持餐饮');
		} else {
            //			if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
//				$store['business_time'] = '24小时营业';
//			} else {
//				$now_time = date('H:i:s');
//				$is_close = 1;
//				$store['business_time'] = $store['open_1'] . '~' . $store['close_1'];
//				if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
//					$is_close = 0;
//				}
//				if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
//					$store['business_time'] .= ';' . $store['open_2'] . '~' . $store['close_2'];
//					if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
//						$is_close = 0;
//					}
//				}
//				if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
//					$store['business_time'] .= ';' . $store['open_3'] . '~' . $store['close_3'];
//					if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
//						$is_close = 0;
//					}
//				}
//			}

            if($store['store_is_close'] != 0){
                $store = checkAutoOpen($store);
            }

            $store['business_time'] = '';
            $is_close = 1;
            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            $now_time = date('H:i:s');
            switch ($date){
                case 1 :
                    if ($store['open_1'] != '00:00:00' || $store['close_1'] != '00:00:00'){
                        if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
                            $is_close = 0;
                        }
                    }
                    if($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00'){
                        if($store['open_2'] < $now_time && $now_time < $store['close_2']) {
                            $is_close = 0;
                        }
                    }
                    if($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00'){
                        if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
                            $is_close = 0;
                        }
                    }
                    $store['business_time'] = $store['open_1']. '~' . $store['close_1'];
                    $store['business_time'] .= ';' . $store['open_2']. '~' . $store['close_2'];
                    $store['business_time'] .= ';' . $store['open_3']. '~' . $store['close_3'];
                    break;
                case 2 ://周二
                    if ($store['open_4'] != '00:00:00' || $store['close_4'] != '00:00:00') {
                        if ($store['open_4'] < $now_time && $now_time < $store['close_4']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_5'] != '00:00:00' || $store['close_5'] != '00:00:00') {
                        if ($store['open_5'] < $now_time && $now_time < $store['close_5']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_6'] != '00:00:00' || $store['close_6'] != '00:00:00') {
                        if ($store['open_6'] < $now_time && $now_time < $store['close_6']){
                            $is_close = 0;
                        }
                    }
                    $store['business_time'] = $store['open_4'] . '~' . $store['close_4'];
                    $store['business_time'] .= ';' . $store['open_5'] . '~' . $store['close_5'];
                    $store['business_time'] .= ';' . $store['open_6'] . '~' . $store['close_6'];
                    break;
                case 3 ://周三
                    if ($store['open_7'] != '00:00:00' || $store['close_7'] != '00:00:00') {
                        if ($store['open_7'] < $now_time && $now_time < $store['close_7']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_8'] != '00:00:00' || $store['close_8'] != '00:00:00') {
                        if ($store['open_8'] < $now_time && $now_time < $store['close_8']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_9'] != '00:00:00' || $store['close_9'] != '00:00:00') {
                        if ($store['open_9'] < $now_time && $now_time < $store['close_9']){
                            $is_close = 0;
                        }
                    }
                    $store['business_time'] = $store['open_7'] . '~' . $store['close_7'];
                    $store['business_time'] .= ';' . $store['open_8'] . '~' . $store['close_8'];
                    $store['business_time'] .= ';' . $store['open_9'] . '~' . $store['close_9'];

                    break;
                case 4 :
                    if ($store['open_10'] != '00:00:00' || $store['close_10'] != '00:00:00') {
                        if ($store['open_10'] < $now_time && $now_time < $store['close_10']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_11'] != '00:00:00' || $store['close_11'] != '00:00:00') {
                        if ($store['open_11'] < $now_time && $now_time < $store['close_11']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_12'] != '00:00:00' || $store['close_12'] != '00:00:00') {
                        if ($store['open_12'] < $now_time && $now_time < $store['close_12']){
                            $is_close = 0;
                        }
                    }
                    $store['business_time'] = $store['open_10'] . '~' . $store['close_10'];
                    $store['business_time'] .= ';' . $store['open_11'] . '~' . $store['close_11'];
                    $store['business_time'] .= ';' . $store['open_12'] . '~' . $store['close_12'];
                    break;
                case 5 :
                    if ($store['open_13'] != '00:00:00' || $store['close_13'] != '00:00:00') {
                        if ($store['open_13'] < $now_time && $now_time < $store['close_13']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_14'] != '00:00:00' || $store['close_14'] != '00:00:00') {
                        if ($store['open_14'] < $now_time && $now_time < $store['close_14']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_15'] != '00:00:00' || $store['close_15'] != '00:00:00') {
                        if ($store['open_15'] < $now_time && $now_time < $store['close_15']){
                            $is_close = 0;
                        }
                    }
                    $store['business_time'] = $store['open_13'] . '~' . $store['close_13'];
                    $store['business_time'] .= ';' . $store['open_14'] . '~' . $store['close_14'];
                    $store['business_time'] .= ';' . $store['open_15'] . '~' . $store['close_15'];
                    break;
                case 6 :
                    if ($store['open_16'] != '00:00:00' || $store['close_16'] != '00:00:00') {
                        if ($store['open_16'] < $now_time && $now_time < $store['close_16']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_17'] != '00:00:00' || $store['close_17'] != '00:00:00') {
                        if ($store['open_17'] < $now_time && $now_time < $store['close_17']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_18'] != '00:00:00' || $store['close_18'] != '00:00:00') {
                        if ($store['open_18'] < $now_time && $now_time < $store['close_18']){
                            $is_close = 0;
                        }
                    }
                    $store['business_time'] = $store['open_16'] . '~' . $store['close_16'];
                    $store['business_time'] .= ';' . $store['open_17'] . '~' . $store['close_17'];
                    $store['business_time'] .= ';' . $store['open_18'] . '~' . $store['close_18'];
                    break;
                case 0 :
                    if ($store['open_19'] != '00:00:00' || $store['close_19'] != '00:00:00') {
                        if ($store['open_19'] < $now_time && $now_time < $store['close_19']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_20'] != '00:00:00' || $store['close_20'] != '00:00:00') {
                        if ($store['open_20'] < $now_time && $now_time < $store['close_20']){
                            $is_close = 0;
                        }
                    }
                    if ($store['open_21'] != '00:00:00' || $store['close_21'] != '00:00:00') {
                        if ($store['open_21'] < $now_time && $now_time < $store['close_21']){
                            $is_close = 0;
                        }
                    }
                    $store['business_time'] .= $store['open_19'] . '~' . $store['close_19'];
                    $store['business_time'] .= ';' . $store['open_20'] . '~' . $store['close_20'];
                    $store['business_time'] .= ';' . $store['open_21'] . '~' . $store['close_21'];
                    break;
                default :
                    $is_close = 1;
                    $store['business_time']= '营业时间未知';
            }
            //garfunkel add
            if($store['store_is_close'] != 0){
                $is_close = 1;
            }
            //end  @wangchuanyuan
			if ($is_close && $is_return) {
				return array('err_code' => true, 'msg' => '店铺不在营业中');
			}
		}
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($store['pic_info']);
		$store['image_list'] = $images;
		$store['image'] = $images ? array_shift($images) : array();
			
		$merchant = M('Merchant')->field(true)->where(array('mer_id' => $store['mer_id']))->find();
		if (empty($merchant) && $is_return) {
			return array('err_code' => true, 'msg' => '不存在的商家');
		} elseif ($merchant['status'] != 1 && $is_return) {
			return array('err_code' => true, 'msg' => '商家状态异常');
		} else {
			
		}
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($foodshop) && $is_return) {
			return array('err_code' => true, 'msg' => '不存在的餐饮店铺');
		}
		return array('err_code' => false, 'data' => array_merge($store, $foodshop));
	}
	
	public function book_save()
	{
		$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
		$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
		if (empty($name)) {
			exit(json_encode(array('err_code' => true, 'msg' => '您的姓名不能为空')));
		}
		if (empty($phone)) {
			exit(json_encode(array('err_code' => true, 'msg' => '您的电话不能为空')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) exit(json_encode($foodshop));
		$foodshop = $foodshop['data'];
		
		$now_time = time() + $foodshop['advance_time'] * 60;
		$book_time = isset($_POST['book_time']) ? htmlspecialchars($_POST['book_time']) : 0;
		if (empty($book_time)) {
			exit(json_encode(array('err_code' => true, 'msg' => '预订时间不能为空')));
		}
		$book_time = strtotime($book_time);
		if ($now_time > $book_time) {
			exit(json_encode(array('err_code' => true, 'msg' => '至少提前' . $foodshop['advance_time'] . '分钟预定')));
		}
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;
		$table_type_data = M('Foodshop_table_type')->where(array('store_id' => $store_id, 'id' => $table_type))->find();
		if (empty($table_type_data)) {
			exit(json_encode(array('err_code' => true, 'msg' => '没有您选择的桌位')));
		}
		$book_num = isset($_POST['book_num']) ? intval($_POST['book_num']) : 2;
		if ($book_num < $table_type_data['min_people']) {
			exit(json_encode(array('err_code' => true, 'msg' => '请您选择更少人数的桌位')));
		}
		if ($table_type_data['is_add'] == 0 && $book_num > $table_type_data['max_people']) {
			exit(json_encode(array('err_code' => true, 'msg' => '请您选择更多人数的桌位')));
		}
		
		//
		$orders = M('Foodshop_order')->field(true)->where(array('store_id' => $store_id, 'book_time' => $book_time, 'table_type' => $table_type, 'status' => array('in', array(1, 2))))->select();
		if (count($orders) >= $table_type_data['num']) {
			exit(json_encode(array('err_code' => true, 'msg' => '该时段该桌台已订满')));
		}
		$tids = array();
		foreach ($orders as $order) {
			$tids[] = $order['table_id'];
		}
		$tables = M('Foodshop_table')->field(true)->where(array('store_id' => $store_id, 'tid' => $table_type))->select();
		$table_id = 0;
		foreach ($tables as $table) {
			if (!in_array($table['id'], $tids)) {
				$table_id = $table['id'];
				break;
			}
		}
		$sex = isset($_POST['sex']) ? intval($_POST['sex']) : 1;
		$note = isset($_POST['note']) ? htmlspecialchars($_POST['note']) : '';
		
		$data = array('mer_id' => $foodshop['mer_id'], 'uid' => $this->user_session['uid'], 'store_id' => $store_id, 'name' => $name, 'phone' => $phone, 'sex' => $sex, 'book_num' => $book_num, 'book_time' => $book_time, 'table_id' => $table_id, 'table_type' => $table_type, 'book_price' => $table_type_data['deposit'], 'note' => $note);
		$data['create_time'] = time();
		$data['price'] = $table_type_data['deposit'];
		$data['real_orderid'] = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];//real_orderid
		if ($order_id = D('Foodshop_order')->save_order($data)) {
			$pay_order_param = array(
					'business_type' => 'foodshop',
					'business_id' => $order_id,
					'order_name' => '餐饮订单',
					'uid' => $this->user_session['uid'],
					'store_id' => $store_id,
					'total_money' => $table_type_data['deposit'],
					'wx_cheap' => 0,
			);
			$result = D('Plat_order')->add_order($pay_order_param);
			if($result['error_code']){
				exit(json_encode(array('err_code' => true, 'msg' => '订座失败，稍后重试！')));
			}else{
				//exit(json_encode(array('err_code' => false, 'url' => U('Foodshop/book_success', array('order_id' => $order_id)))));
				exit(json_encode(array('err_code' => false, 'url' => U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'plat')))));
				redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'foodshop')));
			}

		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '订座失败，稍后重试！')));
		}
	
	}
	
	public function book_success()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = M('Foodshop_order')->field(true)->where(array('uid' => $this->user_session['uid'], 'order_id' => $order_id))->find();
		if (empty($order)) {
			$this->error_tips('不合法的订单！');
			exit;
		}
		$foodshop = $this->now_store($order['store_id']);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];
		
		$cart_detail = M('Foodshop_order_temp')->field('goods_id')->where(array('order_id' => $order_id))->limit(1)->find();
		$goods_detail = M('Foodshop_order_detail')->field('goods_id')->where(array('order_id' => $order_id))->limit(1)->find();
		$plat_order =D('Plat_order')->get_order_by_business_id(array('business_id'=>$order_id,'order_type'=>'foodshop'));
		$order['is_own'] = $plat_order['is_own'];
		$order['pay_type'] = $plat_order['pay_type'];
		if ($cart_detail || $goods_detail) {
			redirect(U('Foodshop/order_detail', array('order_id' => $order_id)));
			exit;
		}
		$table = M('Foodshop_table')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $order['table_id']))->find();
		$table_name = isset($table['name']) ? $table['name'] : '';
		$table_type = M('Foodshop_table_type')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $order['table_type']))->find();
		$order['table_type_name'] = isset($table_type['name']) ? $table_name . '(' . $table_type['min_people'] . '-' . $table_type['max_people'] . '人)' : $table_name;
		$order['book_time_show'] = date('m月d日 H:i', $order['book_time']);
		$this->assign('order', $order);
		$this->assign('store', $foodshop);
		$now_merchant = D('Merchant')->get_info($order['mer_id']);

		$this->assign('now_merchant',$now_merchant );
		$this->display();
	}
	
	public function get_data()
	{
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 2;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) exit(json_encode($foodshop));
		$foodshop = $foodshop['data'];
		
		$book_time = isset($_POST['book_time']) ? htmlspecialchars($_POST['book_time']) : 0;
		if (empty($book_time)) {
			exit(json_encode(array('err_code' => true, 'msg' => '预订时间不能为空')));
		}
		
		$book_time = strtotime($book_time);
		
		$book_num = isset($_POST['book_num']) ? intval($_POST['book_num']) : 2;
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;
		$return = $this->format_data($foodshop, $book_time, $book_num, $table_type);
		exit(json_encode($return));
	}
	
	
	
	/**
	 * @param array $foodshop	店铺的详情
	 * @param int $book_time	预定时间，时间戳格式
	 * @param int $book_num		预订人数
	 * @param int $table_type	桌台类型ID
	 * @return array
	 */
	private function format_data($foodshop, $book_time, $book_num, $table_type)
	{
		$store_id = $foodshop['store_id'];
		//根据预订人数查找对应的桌台
		$table_type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->select();
		$type_list = array();
		$type_ids = array();
		foreach ($table_type_data as $type) {
			if ($type['min_people'] <= $book_num && ($type['max_people'] >= $book_num || $type['is_add'] == 1)) {
				$type_list[$type['id']] = $type;
				$type_ids[] = $type['id'];
			}
		}
		
		if ($type_ids) {
			if (!in_array($table_type, $type_ids)) {
				$table_type = $type_ids[0];
			}
		} else {
			return array('err_code' => true, 'msg' => '没有可供选择的桌台');
		}
		
		//检验已选的桌台类型的各个时间点的预订情况
		$order_list = M('Foodshop_order')->field(true)->where(array('table_type' => $table_type, 'book_time' => array('gt', time()), 'status' => array('in', array(1, 2))))->select();
		$order_table_list = array();
		foreach ($order_list as $order) {
			$order_table_list[date('YmdHi', $order['book_time'])][] = $order;
		}
// 		$order_table_list = isset($temp[$table_type]) ? $temp[$table_type] : '';
		$order_date_count = array();
		if ($order_table_list) {
			foreach ($order_table_list as $index => $row) {
				if ($type_list[$table_type]['num'] <= count($row)) {
					$order_date_count[$index] = 1;
				} else {
					$order_date_count[$index] = 0;
				}
			}
		}

		$weeks = array('周日', '周一', '周二', '周三', '周四', '周五', '周六');
		$time_list = array();
		$day_list = array();
		$now_time = time() + $foodshop['advance_time'] * 60;//开始预约时间
		$foodshop['book_time'] = $foodshop['book_time'] > 0 ? $foodshop['book_time'] : 60;
		$loop_time = $foodshop['book_time'] * 60;//预订时间间隔
		
		$start_time = $foodshop['book_start'];
		$stop_time = $foodshop['book_stop'];
		if ($start_time == '00:00:00' && $stop_time == '00:00:00') {
			$stop_time = '23:59:59';
		}
		
		$select_date_flag = false;
		for ($d = 0; $d <= $foodshop['book_day']; $d++) {
			$index = date('Ymd', strtotime("+{$d} day"));
			//日期的列表
			$day_list[$index] = array('date' => date('Y-m-d', strtotime("+{$d} day")), 'title' => $weeks[date('w', strtotime("+{$d} day"))], 'day' => '<i class="m">' . date('m', strtotime("+{$d} day")) . '</i>-<i class="d">' . date('d', strtotime("+{$d} day")) . '</i>');
				
			//每日可供预约的时间点
			$this_start_time = strtotime(date('YmdHi', strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $start_time)));
			$this_stop_time = strtotime(date('YmdHi', strtotime(date('Y-m-d ', strtotime("+{$d} day")) . $stop_time)));
			$temp = null;
			if ($this_start_time < $this_stop_time) {
				
				$temp['date'] = date('Y-m-d', $this_start_time);
				for ($t = $this_start_time; $t <= $this_stop_time; $t += $loop_time) {
					$class = '';
					if ($t < $now_time) {
						$class = 'End';
						$t_a = array('class' => 'End', 'time' => date('H:i', $t));
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
							$class = 'End';
							$t_a = array('class' => 'End', 'time' => date('H:i', $t));
						} else {
							$t_a = array('class' => '', 'time' => date('H:i', $t));
						}
					}
					if ($book_time == $t) {
						if ($class == 'End') {
							return array('err_code' => true, 'msg' => '您选择餐桌在该时间不能再预订了！');
							$book_time += $loop_time;
						} else {
							$select_date_flag = true;
							$t_a = array('class' => 'on', 'time' => date('H:i', $t));
						}
					}
					$temp['time_list'][] = $t_a;
				}
				$time_list[$index] = $temp;
			} else {
				$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d} day")) . '23:59:59');
				$temp['date'] = date('Y-m-d', $this_start_time);
				for ($t = $this_start_time; $t <= $stop_time_t; $t += $loop_time) {
					$class = '';
					if ($t < $now_time) {
						$class = 'End';
						$t_a = array('class' => 'End', 'time' => date('H:i', $t));
					} else {
						if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
							$class = 'End';
							$t_a = array('class' => 'End', 'time' => date('H:i', $t));
						} else {
							$t_a = array('class' => '', 'time' => date('H:i', $t));
						}
					}
					if ($book_time == $t) {
						if ($class == 'End') {
							return array('err_code' => true, 'msg' => '您选择餐桌在该时间不能再预订了！');
							$book_time += $loop_time;
						} else {
							$select_date_flag = true;
							$t_a = array('class' => 'on', 'time' => date('H:i', $t));
						}
					}
					$temp['time_list'][] = $t_a;
				}
				$time_list[$index] = $temp;
				$d_t = $d + 1;
				if ($d_t < $foodshop['book_day']) {
					$start_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . '00:00:00');
					$stop_time_t = strtotime(date('Y-m-d ', strtotime("+{$d_t} day")) . $stop_time);
					$temp['date'] = date('Y-m-d', $start_time_t);
					for ($t = $start_time_t; $t <= $stop_time_t; $t += $loop_time) {
						$class = '';
						if ($t < $now_time) {
							$class = 'End';
							$t_a = array('class' => 'End', 'time' => date('H:i', $t));
						} else {
							if (isset($order_date_count[date('YmdHi', $t)]) && $order_date_count[date('YmdHi', $t)]) {
								$class = 'End';
								$t_a = array('class' => 'End', 'time' => date('H:i', $t));
							} else {
								$t_a = array('class' => '', 'time' => date('H:i', $t));
							}
						}
						if ($book_time == $t) {
							if ($class == 'End') {
								return array('err_code' => true, 'msg' => '您选择餐桌在该时间不能再预订了！');
								$book_time += $loop_time;
							} else {
								$select_date_flag = true;
								$t_a = array('class' => 'on', 'time' => date('H:i', $t));
							}
						}
						$temp['time_list'][] = $t_a;
					}
					$time_list[date('Ymd', strtotime("+{$d_t} day"))] = $temp;
				}
			}
		}
		if ($select_date_flag) {
			$data = array('m' => date('m', $book_time), 'd' => date('d', $book_time), 'w' => $this->weeks[date('w', $book_time)], 'o' => date('H:i', $book_time), 'selectdate' => date('Y-m-d', $book_time));
			$data['err_code'] = false;
			$data['table_list'] = $type_list;
			$data['table_type'] = $table_type;
			$data['day_list'] = $day_list;
			$data['time_list'] = $time_list;
			$data['book_time'] = date('Y-m-d H:i', $book_time);
			$data['book_num'] = $book_num;
			$data['book_price'] = floatval($type_list[$table_type]['deposit']);
			return $data;//array('err_code' => false, 'table_list' => $type_list, 'table_type' => $table_type, 'day_list' => $day_list, 'time_list' => $time_list);
		} else {
			return array('err_code' => true, 'msg' => '没有可供预约的时间');
		}
		
	}
	
	private function isLogin()
	{
		if (empty($this->user_session)) {
			if($this->is_app_browser){
				$this->error_tips('请先进行登录！',U('Login/index'));
			}else{
				redirect(U('Login/index',array('referer'=>urlencode($_SERVER["REQUEST_URI"]))));
			}
		}
	}
	
	/**
	 * 取消预订
	 */
	public function cancel_book()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

		$cancel_reason = isset($_POST['cancel_reason']) ? htmlspecialchars($_POST['cancel_reason']) : '';
		if (empty($cancel_reason)) {
			exit(json_encode(array('err_code' => true, 'msg' => '取消理由不能为空！')));
		}
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '请先进行登录！')));
		}
		
		$now_order = D('Foodshop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find();
		if ($now_order['status'] > 1) {
			exit(json_encode(array('err_code' => true, 'msg' => '您不能取消订单了')));
		}
		$foodshop = $this->now_store($now_order['store_id']);
		if ($foodshop['err_code']) {
// 			exit(json_encode($foodshop));
// 			$this->error_tips($foodshop['msg']);
// 			exit;
		}
		$foodshop = $foodshop['data'];
		
		if ($now_order['book_time'] - time() < $foodshop['cancel_time'] * 60) {
			exit(json_encode(array('err_code' => true, 'msg' => '当前时间已经超出可取消的时间，现在已经不能取消了！')));
		}
		
		if (D('Foodshop_order')->where(array('order_id' => $order_id))->save(array('cancel_time' => time(), 'cancel_reason' => $cancel_reason))) {
			exit(json_encode(array('err_code' => false, 'url' => U('My/plat_order_refund', array('order_id' => $now_order['order_id'], 'business_type' => 'foodshop')))));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '取消时出现错误稍后重试！')));
		}
		
	}
	
	public function show_menu()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];
		
		$lists = D('Foodshop_goods')->get_list_by_storeid($foodshop['store_id']);
		$goods_detail = array();
		foreach ($lists as $rowset) {
			foreach ($rowset['goods_list'] as $row) {
				if ($row['list']) {
					$goods_detail[$row['goods_id']] = array();
					foreach ($row['list'] as $index => $r) {
						$goods_detail[$row['goods_id']][$index] = array('price' => $r['price'], 'stock_num' => $r['stock_num']);
					}
				}
			}
		}
		$this->assign('goods_list', $lists);
		$this->assign('all_goods', json_encode($goods_detail));
		$this->assign('store', $foodshop);
		$this->display();
		
	}
	public function menu()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];
		
		$now_order = M('Foodshop_order')->where(array('order_id' => $order_id, 'store_id' => $store_id, 'uid' => $this->user_session['uid']))->find();
		if (empty($now_order)) {
			$this->error_tips('订单信息不正确');
		}
		
		if ($now_order['status'] < 1) {
			$this->error_tips('未交预订金，不能点菜');
		}
		
		$lists = D('Foodshop_goods')->get_list_by_storeid($foodshop['store_id']);
		
		$goods_detail = array();
		foreach ($lists as $rowset) {
			foreach ($rowset['goods_list'] as $row) {
				if ($row['list']) {
					$goods_detail[$row['goods_id']] = array();
					foreach ($row['list'] as $index => $r) {
						$goods_detail[$row['goods_id']][$index] = array('price' => $r['price'], 'stock_num' => $r['stock_num']);
					}
				}
			}
		}
		//记录是不是刷新detail页面
		$_SESSION['is_refresh_order_detail'] = 0;
		$productCart = json_decode(cookie('foodshop_cart_' . $store_id . '_order_' . $order_id),true);
		if (empty($productCart)) {
			$goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();
			$cookie_data = array();
			foreach ($goods_list as $go) {
				$t_cookie = array('goods_id' => $go['goods_id'], 'num' => $go['num'], 'name' => $go['name'], 'price' => floatval($go['price']));
				$params = '';
				if ($go['spec_id']) {
					$params = D('Foodshop_goods')->format_spec_ids($go, $params);
				}
				if ($go['spec']) {
					$params = D('Foodshop_goods')->format_properties_ids($go, $params);
				}
				$t_cookie['params'] = $params;
				$cookie_data[] = $t_cookie;
			}
			cookie('foodshop_cart_' . $store_id . '_order_' . $order_id, json_encode($cookie_data));
		}
		
		
		$this->assign('order', $now_order);
		$this->assign('goods_list', $lists);
		$this->assign('all_goods', json_encode($goods_detail));
		$this->assign('store', $foodshop);
		$this->display();
	}
	

	
	
	/**
	 * 保存菜单
	 */
	public function order_detail()
	{
		$this->isLogin();
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$now_order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']), 3);
		if (empty($now_order)) {
			$this->error_tips('订单信息不正确');
		}
// 		echo '<Pre/>';
// 		print_r($now_order);die;
		$store_id = $now_order['store_id'];
		$foodshop = $this->now_store($store_id, false);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];
		//三个按钮的可用，默认不可能
		$is_pay = 0;
		$is_call_store = 0;
		$is_add_menu = 0;
		
		if ($now_order['status'] < 3) {
			$total = 0;
			$price = $now_order['price'] - $now_order['book_price'];//菜品的总价
			$total_price = $now_order['price'];
			$extra_price= 0;
			$productCart = json_decode(cookie('foodshop_cart_' . $store_id . '_order_' . $order_id), true);

			cookie('foodshop_cart_' . $store_id . '_order_' . $order_id, null);

			if ($now_order['running_state']) {//0：用户处理，1:用户不可处理;当是1的时候不能加减菜
				$productCart = null;
			}
			
			
			$new_goods_list = null;
			if ($productCart) {
				$cart_data = D('Foodshop_goods')->format_cart($productCart, $store_id, $order_id);
				if ($cart_data['err_code']) {
					$this->error_tips($cart_data['msg']);
				}
				$new_goods_list = $cart_data['data'];
			}


			$goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();
			$temp_list = array();
			foreach ($goods_list as $_row) {
				$_t_index = $_row['goods_id'];
				if (strlen($_row['spec']) > 0) {
					$_t_index = $_row['goods_id'] . '_' . md5($_row['spec']);
				}
				$temp_list[$_t_index] = $_row;
			}
			
			if ($new_goods_list) {
				foreach ($new_goods_list as $index => $new_row) {
					if (isset($temp_list[$index])) {
						if ($temp_list[$index]['num'] != $new_row['num']) {
							D('Foodshop_order_temp')->where(array('id' => $temp_list[$index]['id']))->save(array('num' => $new_row['num']));
						}
						unset($temp_list[$index]);
					} else {
						$new_row['order_id'] = $order_id;
						$new_row['store_id'] = $store_id;
						$new_row['extra_price'] = empty($new_row['extra_price'])?0:$new_row['extra_price'];
						D('Foodshop_order_temp')->add($new_row);
					}
				}
			}
			//记录是不是刷新detail页面
			$is_refresh_order_detail = $_SESSION['is_refresh_order_detail'];
			$_SESSION['is_refresh_order_detail'] = 1;
			if ($temp_list && $now_order['running_state'] == 0 && empty($is_refresh_order_detail)) {
				$del_ids = array();
				foreach ($temp_list as $tmp) {
					$del_ids[] = $tmp['id'];
				}
				D('Foodshop_order_temp')->where(array('id' => array('in', $del_ids)))->delete();
			}


			
			//预定信息
			$table = M('Foodshop_table_type')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $now_order['table_id']))->find();
			$table_name = isset($table['name']) ? $table['name'] : '';
			$table_type = M('Foodshop_table_type')->field(true)->where(array('store_id' => $foodshop['store_id'], 'id' => $now_order['table_type']))->find();
			$now_order['table_type_name'] = isset($table_type['name']) ? $table_name . '(' . $table_type['min_people'] . '-' . $table_type['max_people'] . '人)' : $table_name;
			$now_order['book_time_show'] = date('m月d日 H:i', $now_order['book_time']);
			
			$goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();
			
			$new_goods_list = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();

			$package_list = array();
			$old_goods_list = array();
			foreach ($goods_detail_list as $new) {

				if ($new['package_id']) {
					if (isset($package_list[$new['package_id']])) {
						if (isset($package_list[$new['package_id']]['list'][$new['goods_id']])) {
							$package_list[$new['package_id']]['list'][$new['goods_id']]['num'] += $new['num'];
						} else {
							$package_list[$new['package_id']]['list'][$new['goods_id']] = $new;
						}
						
					} else {
						$package_list[$new['package_id']] = array('list' => array($new['goods_id'] => $new), 'name' => '', 'num' => 0, 'price' => 0);
					}
				} elseif ($new['is_must']) {
// 					$price += $new['price'] * $new['num'];
// 					$total += $new['num'];
				} else {
					$price += $new['price'] * $new['num'];
					$total += $new['num'];
					$extra_price +=$new['extra_price']*$new['num'];
					$old_goods_list[] = $new;
				}
			}

			if ($now_order['package_ids']) {
				$package_ids = json_decode($now_order['package_ids'], true);
				$packages = D('Foodshop_goods_package')->field(true)->where(array('in' => array('id', $package_ids)))->select();
				foreach ($package_ids as $pid) {
					foreach ($packages as $p) {
						if ($pid == $p['id']) {
							$package_list[$pid]['num']++;
							$package_list[$pid]['price'] += $p['price'];
							$package_list[$pid]['name'] = $p['name'];
							$price += $p['price'];
							$total ++;
						}
					}
				}
			}
			foreach ($new_goods_list as $new) {
				$price += $new['price'] * $new['num'];
				$total += $new['num'];
			}
			
			$must_list = D('Foodshop_goods')->field(true)->where(array('store_id' => $now_order['store_id'], 'status' => 1, 'is_must' => 1))->select();
			$now_time = time();
			$save_data = array();
			foreach ($must_list as &$mgoods) {
				$mgoods['num'] = $now_order['book_num'];
				$price += $mgoods['price'] * $now_order['book_num'];
				$total += $now_order['book_num'];
			}
			
			
			if (in_array($now_order['status'], array(1, 2))) {
				if ($now_order['running_state'] == 0) {
					$is_add_menu = 1;
					if (($old_goods_list || $package_list) && empty($new_goods_list)) {
						$is_pay = 1;
					}
					if ($new_goods_list) {
						$is_call_store = 1;
					}
				}
			} elseif ($now_order['status'] == 0) {
				$is_pay = 1;
			}

			$this->assign('goods_list', $new_goods_list);
		} else {
			$goods_detail_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $store_id))->select();
			$price = $now_order['price'];

			$package_list = array();
			$must_list = array();
			$old_goods_list = array();
			foreach ($goods_detail_list as $new) {
				if ($new['package_id']) {
					if (isset($package_list[$new['package_id']])) {
						if (isset($package_list[$new['package_id']]['list'][$new['goods_id']])) {
							$package_list[$new['package_id']]['list'][$new['goods_id']]['num'] += $new['num'];
						} else {
							$package_list[$new['package_id']]['list'][$new['goods_id']] = $new;
						}
					} else {
						$package_list[$new['package_id']] = array('list' => array($new['goods_id'] => $new), 'name' => '', 'num' => 0, 'price' => 0);
					}
				} elseif ($new['is_must']) {
					$must_list[] = $new;
				} else {
					$old_goods_list[] = $new;
				}
			}
			if ($now_order['package_ids']) {
				$package_ids = json_decode($now_order['package_ids'], true);
				$packages = D('Foodshop_goods_package')->field(true)->where(array('in' => array('id', $package_ids)))->select();
				foreach ($package_ids as $pid) {
					foreach ($packages as $p) {
						if ($pid == $p['id']) {
							$package_list[$pid]['num']++;
							$package_list[$pid]['price'] += $p['price'];
							$package_list[$pid]['name'] = $p['name'];
						}
					}
				}
			}
		}

		$this->assign(array('is_pay' => $is_pay, 'is_add_menu' => $is_add_menu, 'is_call_store' => $is_call_store));
		$this->assign('old_goods_list', $old_goods_list);
		$this->assign('package_list', $package_list);
		$this->assign('must_list', $must_list);
		$this->assign('order', $now_order);
		$this->assign('store', $foodshop);
		$this->assign('price', $price);
		$this->assign('extra_price', $extra_price);
		$this->display();
	}
	
	
	public function queue()
	{
		$this->isLogin();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit;
		}
		$foodshop = $foodshop['data'];

		if ($foodshop['is_queue'] == 0) {
			$this->error_tips('该店铺不支持排号');
		}
		if ($foodshop['queue_is_open'] == 0) {
			$notice = M('Foodshop_queue_notice')->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find();
			$this->assign('notice', $notice);
		}
		
		$foodshop_queue_db = M('Foodshop_queue');
		$queue_data = array();
		if ($queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find()) {
			
// 			$type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id, 'id' => $queue['table_type']))->find();
			
			$count = $foodshop_queue_db->where(array('id' => array('elt', $queue['id']), 'table_type' => $queue['table_type'], 'status' => 0))->count();
			if (empty($count)) {
				$queue['wait'] = 0;
			} else {
				$queue['wait'] = $count;
			}
			$now_time = time();
			if ($queue['use_time'] > $now_time) {
				$queue['wait_time'] = '预计等待   <i>' . ceil(($queue['use_time'] - $now_time) / 60) . '</i>分钟';
			} else {
				$queue['wait_time'] = '请耐心等待店员叫号';
			}
			
			$queue['use_time'] = $now_time;
			if (empty($queue['wait'])) {
				$queue['wait_time'] = '请耐心等待店员叫号';
			}
			$queue['create_time'] = date('Y-m-d H:i', $queue['create_time']);
			$queue_data = $queue;
		}
		
		
		
		$queue_list = M('Foodshop_queue')->field(true)->where(array('status' => 0, 'store_id' => $store_id))->select();
		$temp = array();
		foreach ($queue_list as $queue) {
			if ($queue_data && $queue_data['table_type'] == $queue['table_type']) {
				if ($queue_data['id'] >= $queue['id']) {
					if (isset($temp[$queue['table_type']])) {
						$temp[$queue['table_type']] ++;
					} else {
						$temp[$queue['table_type']] = 1;
					}
				}
			} else {
				if (isset($temp[$queue['table_type']])) {
					$temp[$queue['table_type']] ++;
				} else {
					$temp[$queue['table_type']] = 1;
				}
			}
		}
		
		$table_type_data = D('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id))->select();
		foreach ($table_type_data as &$row) {
			$row['wait_time'] = $row['wait'] = 0;
			if (isset($temp[$row['id']]) && $foodshop['queue_is_open'] == 1) {
				$row['wait'] = $temp[$row['id']];
				$row['wait_time'] = ceil($temp[$row['id']] / $row['num']) * $row['use_time'];
			}
			if ($queue_data && $queue_data['table_type'] == $row['id']) {
				$queue_data['name'] = $row['name'];
			}
		}
		$this->assign('queue_list', $table_type_data);
		$this->assign('store', $foodshop);
		$this->assign('queue', $queue_data);
		$this->display();
	}
	
	
	public function queue_save()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		$table_type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id, 'id' => $table_type))->find();
		if (empty($table_type_data)) {
			exit(json_encode(array('err_code' => true, 'msg' => '不存在的桌台类型')));
		}
		$foodshop_queue_db = M('Foodshop_queue');
		if ($queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find()) {
			exit(json_encode(array('err_code' => true, 'msg' => '您已经取过号了，不要重新取号，如果重新取号，请先取消已经取的号')));
		}
		
		$fp = fopen('./runtime/' . md5(C('config.site_url') . $table_type) . '_lock.txt', "w+");
		flock($fp, LOCK_EX);
		if ($new_queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'table_type' => $table_type))->order('id DESC')->find()) {
			$number = str_replace($table_type_data['number_prefix'], '', $new_queue['number']);
		} else {
			$number = 0;
		}
		$number = intval($number) + 1;
		$new_number = $table_type_data['number_prefix'] . $number;
		$now_time = time();
		
		$num = isset($_POST['num']) ? intval($_POST['num']) : 1;
		$num = max($num, 1);
		
		$count = $foodshop_queue_db->where(array('store_id' => $store_id, 'table_type' => $table_type, 'status' => 0))->count();
		
		$use_time =  $now_time + ceil(($count + 1) / $table_type_data['num']) * $table_type_data['use_time'] * 60;
		
		$data = array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'table_type' => $table_type, 'number' => $new_number, 'create_time' => $now_time, 'use_time' => $use_time, 'num' => $num, 'status' => 0);
		$queue_id = $foodshop_queue_db->add($data);
		
		flock($fp, LOCK_UN);
		fclose($fp);
		
		if ($queue_id) {
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=Foodshop&a=queue&store_id=' . $store_id;
			$model->sendTempMsg('OPENTM205984119', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '尊敬的用户您好，您的排号信息如下', 'keyword1' => $new_number, 'keyword2' => date('Y.m.d H:i'), 'keyword3' => $count + 1, 'remark' => '感谢您的支持！'));
			exit(json_encode(array('err_code' => false, 'number' => $new_number, 'time' => $table_type_data['use_time'])));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '稍后重试')));
		}
	}
	public function queue_cancel()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		if (!(M('Foodshop_queue')->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find())) {
			exit(json_encode(array('err_code' => true, 'msg' => '您已经没有在等待的号码了！')));
		}
		if (M('Foodshop_queue')->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid']))->save(array('status' => 1))) {
			exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '取消失败，稍后重试')));
		}
	}
	
	public function notice_save()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		if ($notice = M('Foodshop_queue_notice')->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find()) {
			exit(json_encode(array('err_code' => true, 'msg' => '已经设置了提醒')));
		} else {
			$data = array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0, 'openid' => $this->user_session['openid'], 'create_time' => time());
			if (M('Foodshop_queue_notice')->add($data)) {
				exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
			} else {
				exit(json_encode(array('err_code' => true, 'msg' => '稍后重试')));
			}
		}
	}
	
	/**
	 * 通知店员
	 */
	public function call_store()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = M('Foodshop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find()) {
			if ($order['running_state']) {
				exit(json_encode(array('err_code' => true, 'msg' => '已通知店员了,不要重复操作')));
			} else {
				M('Foodshop_order')->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->save(array('running_state' => 1, 'running_time' => time()));
				cookie('foodshop_cart_' . $order['store_id'] . '_order_' . $order_id, null);
				exit(json_encode(array('err_code' => false, 'msg' => '通知店成功')));
			}
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '不存在的订单信息')));
		}
	}
	
	/**
	 * 通知店员
	 */
	public function check_status()
	{
		if (empty($this->user_session)) {
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = M('Foodshop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find()) {
			if ($order['status'] > 2) exit(json_encode(array('err_code' => true, 'msg' => 'no')));
			$old_goods = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id))->limit(1)->find();
			
			$new_goods = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id))->limit(1)->find();
			
			if ($old_goods && empty($new_goods)) {
				exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
			}
			exit(json_encode(array('err_code' => true, 'msg' => 'no')));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '不存在的订单信息')));
		}
	}
	
	
	public function pay()
	{
		if (empty($this->user_session)) {
			$this->error_tips('先登录');
			exit(json_encode(array('err_code' => true, 'msg' => '先登录，再取消')));
		}
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		if ($order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))) {
			if ($order['status'] == 0) {
				$price = $order['price'];
			} else {
				$price = D('Foodshop_order')->count_price($order);
			}
			//$order_data['price'] = $price;
			$order_data['extra_price'] = D('Foodshop_order')->count_extra_price($order);
			M('Foodshop_order')->where(array('order_id'=>$order_id))->save($order_data);
			if ($result = D('Plat_order')->field(true)->where(array('business_id' => $order_id, 'business_type' => 'foodshop', 'paid' => 0))->find()) {
				if (floatval($result['total_money']) != floatval($price)) {
					if (D('Plat_order')->where(array('order_id' => $result['order_id']))->save(array('total_money' => $price))) {
						$result['error_code'] = false;
					} else {
						$result['error_code'] = true;
					}
				} else {
					$result['error_code'] = false;
				}
			} else {
				$pay_order_param = array(
						'business_type' => 'foodshop',
						'business_id' => $order_id,
						'order_name' => '餐饮订单',
						'uid' => $this->user_session['uid'],
						'total_money' => $price,
						'wx_cheap' => 0,
				);
				$result = D('Plat_order')->add_order($pay_order_param);
			}

			if ($result['error_code']) {
				$this->error_tips('支付失败稍后重试');
			} else {
				redirect(U('Pay/check', array('order_id' => $result['order_id'], 'type' => 'plat')));
			}
		} else {
			$this->error_tips('不存在的订单信息');
		}
		
	}
	
	public function reply()
	{
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
		
		$store = M('Merchant_store')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store)) {
			$this->error_tips('不存在的店铺');
			exit;
		}
		$merchant = M('Merchant')->field(true)->where(array('mer_id' => $store['mer_id']))->find();
		if (empty($merchant)) {
			$this->error_tips('不存在的商家');
			exit;
		}
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($foodshop)) {
			$this->error_tips('不存在的餐饮店铺');
			exit;
		}
		$reply_list = D('Reply')->get_page_reply_list($store_id, 4, '', '', 0);
		$foodshop = array_merge($foodshop, $store);
		$this->assign('foodshop', $foodshop);
		$this->display();
	}
	
	public function replyList()
	{
		$this->header_json();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			exit(json_encode($foodshop));
		}
		$reply_list = D('Reply')->get_page_reply_list($store_id, 4, '', '', 0);
		$reply_list['err_code'] = false;
		exit(json_encode($reply_list));
	}
	
	
	public function addressinfo()
	{
		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		$this->assign('now_store',$now_store);

		$this->display();
	}
	
	
	public function get_route()
	{
		$now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
		if(empty($now_store)){
			$this->error_tips('该店铺不存在！');
		}
		$this->assign('now_store',$now_store);

		$this->display();
	}
	
	public function scan_qcode()
	{
		$this->isLogin();
		$table_id = isset($_GET['table_id']) ? intval($_GET['table_id']) : 0;
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$foodshop = $this->now_store($store_id);
		if ($foodshop['err_code']) {
			$this->error_tips($foodshop['msg']);
			exit();
		}
		$foodshop = $foodshop['data'];
		if ($table = D('Foodshop_table')->field(true)->where(array('id' => $table_id, 'store_id' => $store_id))->find()) {
			if ($order = D('Foodshop_order')->field(true)->where(array('table_id' => $table_id, 'status' => 2))->find()) {
				if ($order['uid'] == $this->user_session['uid']) {
					redirect(U('Foodshop/order_detail', array('order_id' => $order['order_id'])));
				} else {
					$this->error_tips('此餐桌正在使用中！');
					exit;
				}
			} else {
				if ($order = D('Foodshop_order')->field(true)->where(array('table_id' => $table_id, 'status' => 1, 'uid' => $this->user_session['uid']))->order('book_time ASC')->find()) {
					redirect(U('Foodshop/order_detail', array('order_id' => $order['order_id'])));
					exit;
				}
				$table_type = D('Foodshop_table_type')->field(true)->where(array('id' => $table['tid']))->find();
				$book_num = isset($table_type['min_people']) ? $table_type['min_people'] : 1;
				$data = array('mer_id' => $foodshop['mer_id'], 'uid' => $this->user_session['uid'], 'store_id' => $store_id, 'name' => $this->user_session['nickname'], 'phone' => $this->user_session['phone'], 'sex' => $this->user_session['sex'], 'table_id' => $table_id, 'table_type' => $table['tid']);
				$data['create_time'] = time();
				$data['status'] = 1;
				$data['order_from'] = 1;
				$data['book_num'] = $book_num;
				$data['real_orderid'] = date('ymdhis') . substr(microtime(), 2, 8 - strlen($this->user_session['uid'])) . $this->user_session['uid'];//real_orderid
				if ($order_id = D('Foodshop_order')->save_order($data)) {
					redirect(U('Foodshop/menu', array('order_id' => $order_id, 'store_id' => $store_id)));
				} else {
					$this->error_tips('系统出错了，稍后重试！');
					exit;
				}
			}
			if ($table['status']) {
				
			}
		} else {
			$this->error_tips('不存在的餐台信息！');
		}
	}
	
	
	public function search()
	{
		$this->display();
	}
}
?>