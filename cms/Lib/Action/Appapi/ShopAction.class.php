<?php
/*
 * 地图处理
 *
 */
class ShopAction extends BaseAction{
	public function index(){
		/*最多5个*/
		$return = array();
		$return['banner_list'] = D('Adver')->get_adver_by_key('wap_shop_index_top', 5);
		if(empty($return['banner_list'])){
			$return['banner_list'] = array();
		}else{
			foreach($return['banner_list'] as &$banner_value){
				unset($banner_value['id']);
				unset($banner_value['bg_color']);
				unset($banner_value['cat_id']);
				unset($banner_value['status']);
				unset($banner_value['last_time']);
				unset($banner_value['sub_name']);
			}
		}
		$return['slider_list'] = D('Slider')->get_slider_by_key('wap_shop_slider', 0);
		if(empty($return['slider_list'])){
			$return['slider_list'] = array();
		}else{
			foreach($return['slider_list'] as &$slider_value){
				unset($slider_value['id']);
				unset($slider_value['cat_id']);
				unset($slider_value['sort']);
				unset($slider_value['status']);
				unset($slider_value['last_time']);
			}
		}
		$return['adver_list'] = D('Adver')->get_adver_by_key('wap_shop_index_cente', 3);
		if(empty($return['adver_list'])){
			$return['adver_list'] = array();
		}else{
			foreach($return['adver_list'] as &$adver_value){
				unset($adver_value['id']);
				unset($adver_value['bg_color']);
				unset($adver_value['cat_id']);
				unset($adver_value['status']);
				unset($adver_value['last_time']);
				unset($adver_value['sub_name']);
			}
		}
		$return['category_list'] = D('Shop_category')->lists(true);
		if(empty($return['category_list'])){
			$return['category_list'] = array();
		}else{
			foreach($return['category_list'] as &$cat_value){
				unset($cat_value['cat_id']);
				unset($cat_value['cat_fid']);
				unset($cat_value['cat_sort']);
				unset($cat_value['cat_status']);
				unset($cat_value['show_method']);
				unset($cat_value['cue_field']);
				if($cat_value['son_list']){
					foreach($cat_value['son_list'] as &$cat_v){
						unset($cat_v['cat_id']);
						unset($cat_v['cat_fid']);
						unset($cat_v['cat_sort']);
						unset($cat_v['cat_status']);
						unset($cat_v['show_method']);
						unset($cat_v['cue_field']);
					}
				}
			}
		}
		$return['sort_list'] = array(
			array(
					'name' => '智能排序',
					'sort_url' => 'juli'
			),
			array(
					'name' => '销售数量最高',
					'sort_url'=>'sale_count'
			),
			array(
					'name' => '配送时间最短',
					'sort_url'=>'send_time'
			),
			array(
					'name' => '起送价最低',
					'sort_url' => 'basic_price'
			),
			array(
					'name' => '评分最高',
					'sort_url' => 'score_mean'
			),
			array(
					'name' => '最新发布',
					'sort_url' => 'create_time'
			)
		);
		$return['type_list'] = array(
			array(
				'name' => '全部',
				'type_url' => 'all'
			),
			array(
				'name' => '配送',
				'type_url' => 'delivery'
			),
			array(
				'name' => '自提',
				'type_url' => 'pick'
			)
		);
		$this->returnCode(0,$return);
	}
	public function ajax_list(){
		if(empty($_POST)){
			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
		}
		$key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		$cat_url = isset($_POST['cat_url']) ? htmlspecialchars($_POST['cat_url']) : 'all';
		$order = isset($_POST['sort_url']) ? htmlspecialchars($_POST['sort_url']) : 'juli';
		$deliver_type = isset($_POST['type_url']) ? htmlspecialchars($_POST['type_url']) : 'all';
		$lat = isset($_POST['user_lat']) ? htmlspecialchars($_POST['user_lat']) : 0;
		$long = isset($_POST['user_long']) ? htmlspecialchars($_POST['user_long']) : 0;
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$page = max(1, $page);
		$cat_id = 0;
		$cat_fid = 0;
		if ($cat_url != 'all') {
			$now_category = D('Shop_category')->get_category_by_catUrl($cat_url);
			if ($now_category) {
				if ($now_category['cat_fid']) {
					$cat_id = $now_category['cat_id'];
					$cat_fid = $now_category['cat_fid'];
				} else {
					$cat_id = 0;
					$cat_fid = $now_category['cat_id'];
				}
			}
		}

		$where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
		$key && $where['key'] = $key;

		$lists = D('Merchant_store_shop')->get_list_by_option($where);
		$return = array();
		$now_time = date('H:i:s');
		foreach ($lists['shop_list'] as $row) {
			$temp = array();
			$temp['store_id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['store_theme'] = $row['store_theme'];
			$temp['isverify'] = $row['isverify'];
			$temp['juli_wx'] = $row['juli'];
			$temp['range'] = $row['range'];
			$temp['image'] = $this->config['site_url'].'/index.php?c=Image&a=thumb&width=180&height=120&url='.urlencode($row['image']);
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery'] = $temp['delivery'] ? true : false;
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送

            $temp['is_close'] = 1;

//			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
//				$temp['time'] = '24小时营业';
//				$temp['is_close'] = 0;
//			} else {
//				$temp['time'] = $row['open_1'] . '~' . $row['close_1'];
//				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
//					$temp['is_close'] = 0;
//				}
//				if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
//					$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
//					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
//						$temp['is_close'] = 0;
//					}
//				}
//				if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
//					$temp['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
//					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
//						$temp['is_close'] = 0;
//					}
//				}
//			}

            if($row['store_is_close'] != 0){
                $row = checkAutoOpen($row);
            }
            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            $now_time = date('H:i:s');
            switch ($date){
                case 1 :
                    if ($row['open_1'] != '00:00:00' || $row['close_1'] != '00:00:00'){
                        if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
                            $temp['is_close'] = 0;
                        }
                    }
                    if($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00'){
                        if($row['open_2'] < $now_time && $now_time < $row['close_2']) {
                            $temp['is_close'] = 0;
                        }
                    }
                    if($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00'){
                        if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = $row['open_1']. '~' . $row['close_1'];
                    $temp['time'] .= ';' . $row['open_2']. '~' . $row['close_2'];
                    $temp['time'] .= ';' . $row['open_3']. '~' . $row['close_3'];
                    break;
                case 2 ://周二
                    if ($row['open_4'] != '00:00:00' || $row['close_4'] != '00:00:00') {
                        if ($row['open_4'] < $now_time && $now_time < $row['close_4']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_5'] != '00:00:00' || $row['close_5'] != '00:00:00') {
                        if ($row['open_5'] < $now_time && $now_time < $row['close_5']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_6'] != '00:00:00' || $row['close_6'] != '00:00:00') {
                        if ($row['open_6'] < $now_time && $now_time < $row['close_6']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = $row['open_4'] . '~' . $row['close_4'];
                    $temp['time'] .= ';' . $row['open_5'] . '~' . $row['close_5'];
                    $temp['time'] .= ';' . $row['open_6'] . '~' . $row['close_6'];
                    break;
                case 3 ://周三
                    if ($row['open_7'] != '00:00:00' || $row['close_7'] != '00:00:00') {
                        if ($row['open_7'] < $now_time && $now_time < $row['close_7']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_8'] != '00:00:00' || $row['close_8'] != '00:00:00') {
                        if ($row['open_8'] < $now_time && $now_time < $row['close_8']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_9'] != '00:00:00' || $row['close_9'] != '00:00:00') {
                        if ($row['open_9'] < $now_time && $now_time < $row['close_9']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = $row['open_7'] . '~' . $row['close_7'];
                    $temp['time'] .= ';' . $row['open_8'] . '~' . $row['close_8'];
                    $temp['time'] .= ';' . $row['open_9'] . '~' . $row['close_9'];

                    break;
                case 4 :
                    if ($row['open_10'] != '00:00:00' || $row['close_10'] != '00:00:00') {
                        if ($row['open_10'] < $now_time && $now_time < $row['close_10']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_11'] != '00:00:00' || $row['close_11'] != '00:00:00') {
                        if ($row['open_11'] < $now_time && $now_time < $row['close_11']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_12'] != '00:00:00' || $row['close_12'] != '00:00:00') {
                        if ($row['open_12'] < $now_time && $now_time < $row['close_12']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = $row['open_10'] . '~' . $row['close_10'];
                    $temp['time'] .= ';' . $row['open_11'] . '~' . $row['close_11'];
                    $temp['time'] .= ';' . $row['open_12'] . '~' . $row['close_12'];
                    break;
                case 5 :
                    if ($row['open_13'] != '00:00:00' || $row['close_13'] != '00:00:00') {
                        if ($row['open_13'] < $now_time && $now_time < $row['close_13']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_14'] != '00:00:00' || $row['close_14'] != '00:00:00') {
                        if ($row['open_14'] < $now_time && $now_time < $row['close_14']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_15'] != '00:00:00' || $row['close_15'] != '00:00:00') {
                        if ($row['open_15'] < $now_time && $now_time < $row['close_15']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = $row['open_13'] . '~' . $row['close_13'];
                    $temp['time'] .= ';' . $row['open_14'] . '~' . $row['close_14'];
                    $temp['time'] .= ';' . $row['open_15'] . '~' . $row['close_15'];
                    break;
                case 6 :
                    if ($row['open_16'] != '00:00:00' || $row['close_16'] != '00:00:00') {
                        if ($row['open_16'] < $now_time && $now_time < $row['close_16']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_17'] != '00:00:00' || $row['close_17'] != '00:00:00') {
                        if ($row['open_17'] < $now_time && $now_time < $row['close_17']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_18'] != '00:00:00' || $row['close_18'] != '00:00:00') {
                        if ($row['open_18'] < $now_time && $now_time < $row['close_18']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = $row['open_16'] . '~' . $row['close_16'];
                    $temp['time'] .= ';' . $row['open_17'] . '~' . $row['close_17'];
                    $temp['time'] .= ';' . $row['open_18'] . '~' . $row['close_18'];
                    break;
                case 0 :
                    if ($row['open_19'] != '00:00:00' || $row['close_19'] != '00:00:00') {
                        if ($row['open_19'] < $now_time && $now_time < $row['close_19']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_20'] != '00:00:00' || $row['close_20'] != '00:00:00') {
                        if ($row['open_20'] < $now_time && $now_time < $row['close_20']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_21'] != '00:00:00' || $row['close_21'] != '00:00:00') {
                        if ($row['open_21'] < $now_time && $now_time < $row['close_21']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] .= $row['open_19'] . '~' . $row['close_19'];
                    $temp['time'] .= ';' . $row['open_20'] . '~' . $row['close_20'];
                    $temp['time'] .= ';' . $row['open_21'] . '~' . $row['close_21'];
                    break;
                default :
                    $temp['is_close'] = 1;
                    $temp['time']= '营业时间未知';
            }
            //end  @wangchuanyuan

            //garfunkel add
            if($row['store_is_close'] != 0){
                $temp['is_close'] = 1;
            }








            $temp['coupon_list'] = array();
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
			}
			if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
				$temp['coupon_list']['discount'] = $row['store_discount']/10;
			}
			$system_delivery = array();
			foreach ($row['system_discount'] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 1) {//满减
					$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 2) {//配送
					if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
						$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					}
				}
			}
			foreach ($row['merchant_discount'] as $row_m) {
				if ($row_m['type'] == 0) {
					$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				} elseif ($row_m['type'] == 1) {
					$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				}
			}
			if ($row['deliver']) {
				if ($temp['delivery_system']) {
					$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
				} else {
					if ($row['is_have_two_time']) {
						if ($row['reach_delivery_fee_type2'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
							}
						} elseif ($row['reach_delivery_fee_type2'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type2'] == 2) {
							$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
						}
					} else {
						if ($row['reach_delivery_fee_type'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
							}
						} elseif ($row['reach_delivery_fee_type'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type'] == 2) {
							$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
						}
					}
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$temp['coupon_list'] = $this->parseCoupon($temp['coupon_list'],'array');
			
			if($_POST['Device-Id'] != 'wxapp' || $temp['is_close'] == 0){
				$return[] = $temp;
			}
		}
		$array = array('store_list' => $return, 'has_more' => $lists['has_more'] ? true : false);
		$this->returnCode(0,$array);
	}
	public function parseCoupon($obj,$type){
		$returnObj = array();
		foreach($obj as $key=>$value){
			if($key=='invoice'){
				$returnObj[$key] = '满'.$obj[$key].'元支持开发票，请在下单时填写发票抬头';
			}else if($key=='discount'){
				$returnObj[$key] = '店内全场'.$obj[$key].'折';
			}else{
				$returnObj[$key] = [];
				foreach($obj[$key] as $k=>$v){
					if ($key == 'delivery')  {
						$returnObj[$key][] = '商品满'.$obj[$key][$k]['money'].'元,配送费减'.$obj[$key][$k]['minus'].'元';
					} else {
						$returnObj[$key][] = '满'.$obj[$key][$k]['money'].'元减'.$obj[$key][$k]['minus'].'元';
					}
				}
			}
		}
		
		$textObj = array();
		foreach($returnObj as $key=>$value){
			if($key=='invoice' || $key=='discount'){
				$textObj[$key] = $value;
			}else{
				switch($key){
					case 'system_newuser':
						$textObj[$key] = '平台首单'.implode(',',$value);
						break;
					case 'system_minus':
						$textObj[$key] = '平台优惠'.implode(',',$value);
						break;
					case 'newuser':
						$textObj[$key] = '店铺首单'.implode(',',$value);
						break;
					case 'minus':
						$textObj[$key] = '店铺优惠'.implode(',',$value);
						break;
					case 'system_minus':
						$textObj[$key] = '平台优惠'.implode(',',$value);
						break;
					case 'delivery':
						$textObj[$key] = implode(',',$value);
						break;
				}
			}
		}
		if($type == 'text'){
			$tmpObj = array();
			foreach($textObj as $key=>$value){
				$tmpObj[] = $value;
			}
			return implode(';',$tmpObj);
		}else{
			$returnObj = array();
			foreach($textObj as $key=>$value){
				$returnObj[] = array(
					'type'=>$key,
					'value'=>$value
				);
			}
			return $returnObj;
		}
	}
	public function ajax_shop(){
		if(empty($_POST)){
			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
		}
		$store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
		$where = array('store_id' => $store_id);
		$now_store = D('Merchant_store')->field(true)->where($where)->find();	
		$now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
		$now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
		if (empty($now_shop) || empty($now_store)) {
			echo json_encode(array());
			exit;
		}
		$auth_files = array();
		if ($now_store['auth'] > 2) {
			if (!empty($now_store['auth_files'])) {
				$auth_file_class = new auth_file();
				$tmp_pic_arr = explode(';', $now_store['auth_files']);
				foreach($tmp_pic_arr as $key => $value){
					$auth_file_temp = $auth_file_class->get_image_by_path($value);//array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
					$auth_files[] = $auth_file_temp['image'];
				}
			}
		}
		$now_store['auth_files'] = $auth_files;
		
		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$row = array_merge($now_store, $now_shop);
		
		$store = array();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($row['pic_info']);
	
		$store['id'] = $row['store_id'];
		
		$store['phone'] = trim($row['phone']);
		$store['long'] = $row['long'];
		$store['lng'] = $row['long'];
		$store['lat'] = $row['lat'];
		$store['isverify'] = $now_mer['isverify'];
		$store['store_theme'] = $row['store_theme'];
		$store['adress'] = $row['adress'];

		$store['is_close'] = 1;
		$now_time = date('H:i:s');

//		if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
//			$store['time'] = '24小时营业';
//			$store['is_close'] = 0;
//		} else {
//			$store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
//			if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
//				$store['is_close'] = 0;
//			}
//			if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
//				$store['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
//				if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
//					$store['is_close'] = 0;
//				}
//			}
//			if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
//				$store['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
//				if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
//					$store['is_close'] = 0;
//				}
//			}
//		}
        if($row['store_is_close'] != 0){
            $row = checkAutoOpen($row);
        }
        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171103
        switch ($date){
            case 1 :
                if ($row['open_1'] != '00:00:00' || $row['close_1'] != '00:00:00'){
                    if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00'){
                    if($row['open_2'] < $now_time && $now_time < $row['close_2']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00'){
                    if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($store['open_1'], 0, -3) . '~' . substr($store['close_1'], 0, -3);
                $store['time'] .= ';' . substr($store['open_2'], 0, -3) . '~' . substr($store['close_2'], 0, -3);
                $store['time'] .= ';' . substr($store['open_3'], 0, -3) . '~' . substr($store['close_3'], 0, -3);
                break;
            case 2 ://周二
                if ($row['open_4'] != '00:00:00' || $row['close_4'] != '00:00:00'){
                    if ($row['open_4'] < $now_time && $now_time < $row['close_4']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_5'] != '00:00:00' || $row['close_5'] != '00:00:00'){
                    if($row['open_5'] < $now_time && $now_time < $row['close_5']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_6'] != '00:00:00' || $row['close_6'] != '00:00:00'){
                    if ($row['open_6'] < $now_time && $now_time < $row['close_6']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($store['open_4'], 0, -3) . '~' . substr($store['close_4'], 0, -3);
                $store['time'] .= ';' . substr($store['open_5'], 0, -3) . '~' . substr($store['close_5'], 0, -3);
                $store['time'] .= ';' . substr($store['open_6'], 0, -3) . '~' . substr($store['close_6'], 0, -3);
                break;
            case 3 ://周三
                if ($row['open_7'] != '00:00:00' || $row['close_7'] != '00:00:00'){
                    if ($row['open_7'] < $now_time && $now_time < $row['close_7']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_8'] != '00:00:00' || $row['close_8'] != '00:00:00'){
                    if($row['open_8'] < $now_time && $now_time < $row['close_8']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_9'] != '00:00:00' || $row['close_9'] != '00:00:00'){
                    if ($row['open_9'] < $now_time && $now_time < $row['close_9']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($store['open_7'], 0, -3) . '~' . substr($store['close_7'], 0, -3);
                $store['time'] .= ';' . substr($store['open_8'], 0, -3) . '~' . substr($store['close_8'], 0, -3);
                $store['time'] .= ';' . substr($store['open_9'], 0, -3) . '~' . substr($store['close_9'], 0, -3);
                break;
            case 4 :
                if ($row['open_10'] != '00:00:00' || $row['close_10'] != '00:00:00'){
                    if ($row['open_10'] < $now_time && $now_time < $row['close_10']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_11'] != '00:00:00' || $row['close_11'] != '00:00:00'){
                    if($row['open_11'] < $now_time && $now_time < $row['close_11']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_12'] != '00:00:00' || $row['close_12'] != '00:00:00'){
                    if ($row['open_12'] < $now_time && $now_time < $row['close_12']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($store['open_10'], 0, -3) . '~' . substr($store['close_10'], 0, -3);
                $store['time'] .= ';' . substr($store['open_11'], 0, -3) . '~' . substr($store['close_11'], 0, -3);
                $store['time'] .= ';' . substr($store['open_12'], 0, -3) . '~' . substr($store['close_12'], 0, -3);
                break;
            case 5 :
                if ($row['open_13'] != '00:00:00' || $row['close_13'] != '00:00:00'){
                    if ($row['open_13'] < $now_time && $now_time < $row['close_13']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_14'] != '00:00:00' || $row['close_14'] != '00:00:00'){
                    if($row['open_14'] < $now_time && $now_time < $row['close_14']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_15'] != '00:00:00' || $row['close_15'] != '00:00:00'){
                    if ($row['open_15'] < $now_time && $now_time < $row['close_15']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($store['open_13'], 0, -3) . '~' . substr($store['close_13'], 0, -3);
                $store['time'] .= ';' . substr($store['open_14'], 0, -3) . '~' . substr($store['close_14'], 0, -3);
                $store['time'] .= ';' . substr($store['open_15'], 0, -3) . '~' . substr($store['close_15'], 0, -3);
                break;
            case 6 :
                if ($row['open_16'] != '00:00:00' || $row['close_16'] != '00:00:00'){
                    if ($row['open_16'] < $now_time && $now_time < $row['close_16']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_17'] != '00:00:00' || $row['close_17'] != '00:00:00'){
                    if($row['open_17'] < $now_time && $now_time < $row['close_17']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_18'] != '00:00:00' || $row['close_18'] != '00:00:00'){
                    if ($row['open_18'] < $now_time && $now_time < $row['close_18']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($store['open_16'], 0, -3) . '~' . substr($store['close_16'], 0, -3);
                $store['time'] .= ';' . substr($store['open_17'], 0, -3) . '~' . substr($store['close_17'], 0, -3);
                $store['time'] .= ';' . substr($store['open_18'], 0, -3) . '~' . substr($store['close_18'], 0, -3);
                break;
            case 0 :
                if ($row['open_19'] != '00:00:00' || $row['close_19'] != '00:00:00'){
                    if ($row['open_19'] < $now_time && $now_time < $row['close_19']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_20'] != '00:00:00' || $row['close_20'] != '00:00:00'){
                    if($row['open_20'] < $now_time && $now_time < $row['close_20']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_21'] != '00:00:00' || $row['close_21'] != '00:00:00'){
                    if ($row['open_21'] < $now_time && $now_time < $row['close_21']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($store['open_19'], 0, -3) . '~' . substr($store['close_19'], 0, -3);
                $store['time'] .= ';' . substr($store['open_20'], 0, -3) . '~' . substr($store['close_20'], 0, -3);
                $store['time'] .= ';' . substr($store['open_21'], 0, -3) . '~' . substr($store['close_21'], 0, -3);
                break;
            default :
                $store['is_close'] = 1;
                $store['time']= '营业时间未知';
        }
        //garfunkel add
        if($row['store_is_close'] != 0){
            $store['is_close'] = 1;
        }
        //end  @wangchuanyuan







        $store['name'] = $row['name'];
		$store['store_notice'] = $row['store_notice'];
		$store['txt_info'] = $row['txt_info'];
		$store['image'] = isset($images[0]) ? $images[0] : '';
		$store['images'] = $images ? $images : array();
		$store['auth_files'] = $auth_files;
		$store['star'] = $row['score_mean'];
		$store['month_sale_count'] = $row['sale_count'];
		$store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
		$store['delivery_time'] = $row['send_time'];//配送时长
		$store['delivery_price'] = floatval($row['basic_price']);//起送价
		
		$is_have_two_time = 0;//是否是第二时段的配送显示
		
		if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
			if ($this->config['delivery_time']) {
				$delivery_times = explode('-', $this->config['delivery_time']);
				$start_time = $delivery_times[0] . ':00';
				$stop_time = $delivery_times[1] . ':00';
				if (!($start_time == $stop_time && $start_time == '00:00:00')) {
					if ($this->config['delivery_time2']) {
						$delivery_times2 = explode('-', $this->config['delivery_time2']);
						$start_time2 = $delivery_times2[0] . ':00';
						$stop_time2 = $delivery_times2[1] . ':00';
						if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
							$is_have_two_time = 1;
						}
					}
				}
			}
			
			if ($is_have_two_time) {
				if ($now_time <= $stop_time || $now_time > $stop_time2) {
					$is_have_two_time = 0;
				} 
			}
			
			if ($row['s_is_open_own']) {
				if ($is_have_two_time) {
					$store['delivery_money'] = $row['s_free_type2'] == 0 ? 0 : $row['s_delivery_fee2'];
				} else {
					$store['delivery_money'] = $row['s_free_type'] == 0 ? 0 : $row['s_delivery_fee'];
				}
			} else {
				$store['delivery_money'] = $is_have_two_time ? C('config.delivery_fee2') : C('config.delivery_fee');
			}
		} else {
			if (!($row['delivertime_start'] == $row['delivertime_stop'] && $row['delivertime_start'] == '00:00:00')) {
				if (!($row['delivertime_start2'] == $row['delivertime_stop2'] && $row['delivertime_start2'] == '00:00:00')) {
					$is_have_two_time = 1;
				}
			}
			
			if ($is_have_two_time) {
				if ($now_time <= $row['delivertime_stop'] || $now_time > $row['delivertime_stop2']) {
					$is_have_two_time = 0;
				} 
			}
			
			$store['delivery_money'] = $is_have_two_time ? $row['delivery_fee2'] : $row['delivery_fee'];
		}
		
		
		
		$store['delivery_money'] = floatval($store['delivery_money']);
// 		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
// 		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
		$store['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
		if (in_array($row['deliver_type'], array(2, 3, 4))) {
			$store['pick'] = 1;//是否支持自提
		} else {
			$store['pick'] = 0;//是否支持自提
		}
		$store['pack_alias'] = $row['pack_alias'];//打包费别名
		$store['freight_alias'] = $row['freight_alias'];//运费别名
		$store['coupon_list'] = array();
		if ($row['is_invoice']) {
			$store['coupon_list']['invoice'] = floatval($row['invoice_price']);
		}
		if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
			$store['coupon_list']['discount'] = floatval(round($row['store_discount']/10, 2));
		}
		$system_delivery = array();
		if (isset($discounts[0]) && $discounts[0]) {
			foreach ($discounts[0] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$store['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 1) {//满减
					$store['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				} elseif ($row_d['type'] == 2) {//配送
					$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
				}
			}
		}
		if (isset($discounts[$store_id]) && $discounts[$store_id]) {
			foreach ($discounts[$store_id] as $row_m) {
				if ($row_m['type'] == 0) {
					$store['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				} elseif ($row_m['type'] == 1) {
					$store['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
				}
			}
		}
		if ($store['delivery']) {
			if ($store['delivery_system']) {
				$system_delivery && $store['coupon_list']['delivery'] = $system_delivery;
			} else {
				if ($is_have_two_time) {
					if ($row['reach_delivery_fee_type2'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
							$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} else {
						$row['delivery_fee2'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
					}
				} else {
					if ($row['reach_delivery_fee_type'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
							$store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} else {
						$row['delivery_fee'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
					}
				}
			}
		}
		$store['coupon_list'] = $this->parseCoupon($store['coupon_list'],'array');
		
		$today = date('Ymd');
		
		$product_list = D('Shop_goods')->get_list_by_storeid($store_id);
		$list = array();
		foreach ($product_list as $row) {
			$temp = array();
			$temp['cat_id'] = $row['sort_id'];
			$temp['cat_name'] = $row['sort_name'];
			$temp['sort_discount'] = strval(round(intval($row['sort_discount']) * 0.1, 2));
			foreach ($row['goods_list'] as $r) {
				$glist = array();
				$glist['product_id'] = $r['goods_id'];
				$glist['product_name'] = $r['name'];
				$glist['product_price'] = $r['price'];
				$glist['is_seckill_price'] = $r['is_seckill_price'];
				$glist['o_price'] = $r['o_price'];
				$glist['number'] = $r['number'];
				$glist['packing_charge'] = floatval($r['packing_charge']);
				$glist['unit'] = $r['unit'];
				if (isset($r['pic_arr'][0])) {
					$glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
				}
				$glist['product_sale'] = $r['sell_count'];
				$glist['product_reply'] = $r['reply_count'];
				$glist['has_format'] = false;
				if ($r['spec_value'] || $r['is_properties']) {
					$glist['has_format'] = true;
				}
				
				$r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
				if ($today == $r['sell_day']) {
					$glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
				} else {
					$glist['stock'] = $r['stock_num'];
				}
				$temp['product_list'][] = $glist;
			}
			$list[] = $temp;
		}
		$array = array('store' => $store, 'product_list' => $list);
		$this->returnCode(0,$array);
	}
	public function ajax_goods(){
		$goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 1;
		$now_goods = D('Shop_goods')->get_goods_by_id($goods_id);
		foreach($now_goods['spec_list'] as &$value){
			$value['list'] = array_values($value['list']);
		}
		
		if($now_goods['spec_list']){
			foreach($now_goods['spec_list'] as &$value){
				$value['id_'] = $value['id'];
				unset($value['id']);
				foreach($value['list'] as &$v){
					$v['id_'] = $v['id'];
					unset($v['id']);
				}
			}
			$now_goods['spec_list'] = array_values($now_goods['spec_list']);
		}else{
			$now_goods['spec_list'] = array();
		}
		
		if($now_goods['properties_list']){
			foreach($now_goods['properties_list'] as &$value){
				$value['id_'] = $value['id'];
				unset($value['id']);
			}
			$now_goods['properties_list'] = array_values($now_goods['properties_list']);
		}else{
			$now_goods['properties_list'] = array();
		}
		
		if($now_goods['list']){
			foreach($now_goods['list'] as &$value){
				if ($now_goods['is_seckill_price']) {
					$value['price'] = $value['seckill_price'];
				}
				if($value['properties']){
					foreach($value['properties'] as &$v){
						$v['id_'] = $v['id'];
						unset($v['id']);
					}
				}else{
					$value['properties'] = array();
				}
			}
		}else{
			$now_goods['list'] = $this->getObj();
		}
		
		
		$this->returnCode(0,$now_goods);
	}
	
	public function ajax_reply(){
		C('VAR_PAGE','page');
		$reply_return = D('Reply')->get_page_reply_list(intval($_POST['store_id']), 3, $_POST['tab'], '', 0, true);
		if(!$reply_return['count']){
			$reply_return['count'] = 0;
		}else{
			foreach($reply_return['list'] as &$value){
				if(empty($value['goods'])){
					$value['goods'] = array();
				}
			}
		}
		$this->returnCode(0,$reply_return);
	}
	
	public function ajax_cart()
	{
		if (empty($_POST)) {

			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
		}
		

		//post
		//store_id   店铺ID
		//productCart   数组购物车，和COOKIE里数据一致
		
		
		$productCart = $_POST['productCart'];
		if (empty($productCart)) {
			$this->returnCode(10110003);
		}
		if (!is_array($productCart)) {
			$productCart = json_decode(htmlspecialchars_decode($productCart), true);
		}
		$store_id = intval($_POST['store_id']);
// 		$return = $this->check_cart($store_id, $productCart);
		$return = D('Shop_goods')->checkCart($store_id, $this->_uid, $productCart);
		
		if ($return['error_code'] == 1) {
			$this->returnCode(1, null, $return['msg']);
		} elseif ($return['error_code']) {
			$this->returnCode($return['error_code']);
		}
		
		$is_own = 0;
		$merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id' => $return['mer_id']))->find();
		foreach ($merchant_ownpay as $ownKey => $ownValue) {
			$ownValueArr = unserialize($ownValue);
			if($ownValueArr['open']){
				$is_own = 1;
			}
		}
		if ($is_own) {
			if ($return['delivery_type'] == 0) {
				$this->returnCode(1, null, '商家配置的配送信息不正确');
			} elseif ($return['delivery_type'] == 3) {
				$return['delivery_type'] = 2;
			}
		}
		
		
		$address_id = isset($_POST['adress_id']) ? intval($_POST['adress_id']) : 0;
		$user_address_temp = D('User_adress')->field(true)->where(array('uid' => $this->_uid))->order('`default` DESC,`adress_id` DESC')->select();
		$user_adress = array();
		$distance_array = array();
		$user_address_list = array();
		foreach ($user_address_temp as $address) {
			$address['distance'] = getDistance($address['latitude'], $address['longitude'], $return['store']['lat'], $return['store']['long']);
			$distance = $address['distance'] / 1000;
			if ($return['store']['delivery_radius'] >= $distance) {
				$address['is_deliver'] = true;
			} else {
				$address['is_deliver'] = false;
			}
			if ($address_id == $address['adress_id']) {
				$user_adress = $address;
				continue;
			}
			$distance_array[] = $address['distance'];
			$user_address_list[] = $address;
		}
		
		if (empty($user_adress) && $distance_array && $user_address_list) {
			array_multisort($distance_array, SORT_ASC, $user_address_list);
			$user_adress = $user_address_list[0];
// 			if ($user_adress) {
// 				array_unshift($user_address_list, $user_adress);
// 			} else {
// 				$user_adress = $user_address_list[0];
// 			}
		}
		if($user_adress){
			if (!($user_adress['latitude'] > 0 &&  $user_adress['longitude'] > 0)) 
				$this->returnCode(1, null, '地址不正确');
			$province = D('Area')->get_area_by_areaId($user_adress['province'],false);
			$user_adress['province_txt'] = $province['area_name'];
				
			$city = D('Area')->get_area_by_areaId($user_adress['city'],false);
			$user_adress['city_txt'] = $city['area_name'];
				
			$area = D('Area')->get_area_by_areaId($user_adress['area'],false);
			$user_adress['area_txt'] = $area['area_name'];
		}
		
		
		
		
		
// 		$user_adress = D('User_adress')->get_one_adress($this->_uid, $address_id);
// 		if ($user_adress) {
// 			$distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
// 			$distance = $distance / 1000;
// 			if ($return['store']['delivery_radius'] >= $distance) {
// 				$user_adress['is_deliver'] = true;
// 			} else {
// 				$user_adress['is_deliver'] = false;
// 			}
// 		}
		
		
		
		//实际要支付的价格
		if ($return['store']['basic_price'] > $return['price']) {
			if (in_array($return['delivery_type'], array(2, 3, 4))) {
				$return['delivery_type'] = 2;
			} else {
				$this->returnCode(1, null, '没有达到起送价，不予以配送');
			}
		}
		
		//计算配送费
		$delivery_fee_reduce = $return['delivery_fee_reduce'];
		$delivery_fee_reduce2 = $return['delivery_fee_reduce'];
		if ($user_adress && $user_adress['is_deliver']) {
			//$return['discount_list']
			$distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
			$distance = $distance / 1000;
			$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
			$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
			
			if ($return['delivery_fee'] < $return['delivery_fee_reduce']) {
				$delivery_fee_reduce = $return['delivery_fee'];
			}
			
			$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
			
			$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
		
			$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
			$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
			
			if ($return['delivery_fee2'] < $return['delivery_fee_reduce']) {
				$delivery_fee_reduce2 = $return['delivery_fee2'];
			}
			
			$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
			$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
		} else {
			$return['delivery_fee'] = 0;
			$return['delivery_fee2'] = 0;
			$delivery_fee_reduce = 0;
			$delivery_fee_reduce2 = 0;
		}
		
		$advance_day = $return['store']['advance_day'];
		$advance_day = empty($advance_day) ? 1 : $advance_day;
		if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
			$delivery_times = explode('-', $this->config['delivery_time']);
			$start_time = $delivery_times[0] . ':00';
			$stop_time = $delivery_times[1] . ':00';
			$delivery_times2 = explode('-', $this->config['delivery_time2']);
			$start_time2 = $delivery_times2[0] . ':00';
			$stop_time2 = $delivery_times2[1] . ':00';
		} else {
			$start_time = $return['store']['delivertime_start'];
			$stop_time = $return['store']['delivertime_stop'];
			$start_time2 = $return['store']['delivertime_start2'];
			$stop_time2 = $return['store']['delivertime_stop2'];
		}
		
		$time = time() + $return['store']['send_time'] * 60;//默认的期望送达时间
		if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
			$start_time = '00:00:00';
			$stop_time = '23:59:59';
		} elseif ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
			$stop_time2 = $start_time2 = 0;
		}
		
		$date_list = array();
		for ($day = 0; $day <= $advance_day; $day ++) {
			$stime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $start_time);
			$etime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $stop_time);
			if ($etime < $stime) $etime = $etime + 86400;
			if ($stime < $time) $stime = $time;
			if ($etime < $stime) continue;
			$bigen_time = $stime;
			$_m_15 = strtotime(date('Y-m-d H:15', $stime));
			$_m_30 = strtotime(date('Y-m-d H:30', $stime));
			$_m_45 = strtotime(date('Y-m-d H:45', $stime));
			if ($_m_15 >= $stime) {
				$stime = $_m_15;
			} elseif ($_m_30 >= $stime) {
				$stime = $_m_30;
			} elseif ($_m_45 >= $stime) {
				$stime = $_m_45;
			} else {
				$stime = strtotime(date('Y-m-d H:00', $stime + 3600));
			}
			$date_list[date('Y-m-d', $bigen_time)][date('H:i', $bigen_time)] = array('hour_minute' => date('H:i', $bigen_time), 'time_select' => 1, 'delivery_fee' => floatval(round($return['delivery_fee'], 2)));
			for ($now_date = $stime + 900; $now_date <= $etime;) {
				$date_list[date('Y-m-d', $now_date)][date('H:i', $now_date)] = array('hour_minute' => date('H:i', $now_date), 'time_select' => 1, 'delivery_fee' => floatval(round($return['delivery_fee'], 2)));
				$now_date += 900;
			}
			if ($start_time2 != 0 && $stop_time2 != 0) {
				$stime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $start_time2);
				$etime = strtotime(date('Y-m-d', strtotime('+' . $day . ' day')) . ' ' . $stop_time2);
				if ($etime < $stime) $etime = $etime + 86400;
				if ($stime < $time) $stime = $time;
				if ($etime < $stime) continue;
				$bigen_time = $stime;
				$_m_15 = strtotime(date('Y-m-d H:15', $stime));
				$_m_30 = strtotime(date('Y-m-d H:30', $stime));
				$_m_45 = strtotime(date('Y-m-d H:45', $stime));
				if ($_m_15 >= $stime) {
					$stime = $_m_15;
				} elseif ($_m_30 >= $stime) {
					$stime = $_m_30;
				} elseif ($_m_45 >= $stime) {
					$stime = $_m_45;
				} else {
					$stime = strtotime(date('Y-m-d H:00', $stime + 3600));
				}
				if (!isset($date_list[date('Y-m-d', $bigen_time)][date('H:i', $bigen_time)])) {
					$date_list[date('Y-m-d', $bigen_time)][date('H:i', $bigen_time)] = array('hour_minute' => date('H:i', $bigen_time), 'time_select' => 2, 'delivery_fee' => floatval(round($return['delivery_fee2'], 2)));
				}
				for ($now_date = $stime + 900; $now_date <= $etime;) {
					if (isset($date_list[date('Y-m-d', $now_date)][date('H:i', $now_date)])) continue;
					$date_list[date('Y-m-d', $now_date)][date('H:i', $now_date)] = array('hour_minute' => date('H:i', $now_date), 'time_select' => 2, 'delivery_fee' => floatval(round($return['delivery_fee2'], 2)));
					$now_date += 900;
				}
			
			}
		}
		$d_list = array();
		foreach ($date_list as $key => $rowset) {
			$temp = array();
			foreach ($rowset as $rv) {
				$temp[] = $rv;
			}
			if ($key == date('Y-m-d')) {
				$d_list[] = array('ymd' => $key, 'show_date' => '今日', 'date_list' => $temp);
			} else {
				$d_list[] = array('ymd' => $key, 'show_date' => date('m月d日', strtotime($key)), 'date_list' => $temp);
			}
		}
		
		
		$pick_addr_id = isset($_GET['pick_addr_id']) ? $_GET['pick_addr_id'] : '';
		$pick_list = D('Pick_address')->get_pick_addr_by_merid($return['mer_id'], true);
		if ($pick_addr_id) {
			foreach ($pick_list as $k => $v) {
				if ($v['pick_addr_id'] == $pick_addr_id) {
					$pick_address = $v;
					break;
				}
			}
		} else {
			$pick_address = $pick_list[0];
		}
		if (isset($pick_address['long'])) $pick_address['lng'] = $pick_address['long'];
		unset($pick_address['long']);
		$now_store_category_relation = M('Shop_category_relation')->where(array('store_id'=>$return['store_id']))->find();
		$now_store_category = M('Shop_category')->where(array('cat_id'=>$now_store_category_relation['cat_id']))->find();
		foreach($return['goods'] as &$v) {
		    $v['discount_type'] = $v['discount_type'] ? true : false;
		    $v['discount_type_data'] = in_array($v['discount_type'], array(1, 3, 4)) ? 1 : (in_array($v['discount_type'], array(2, 5)) ? 2 : 0);
		}
		$result = array('goods_list' => $return['goods']);
		$result['cue_field'] = array();
		if ($now_store_category['cue_field']) {
			$result['cue_field'] = unserialize($now_store_category['cue_field']);
		}
		
		$new_discount_list = array();
		$deliver_minus_list = array();
		if (isset($return['discount_list']) && $return['discount_list']) {
			foreach ($return['discount_list'] as $key => $dval) {
				switch($key){
					case 'system_newuser':
						$text = '平台首单' . '满' . floatval($dval['money']) . '元减' . floatval($dval['minus']) . '元';
						$new_discount_list[] = array('type' => $key, 'time_select' => 1, 'value' => $text, 'minus' => floatval($dval['minus']));
						break;
					case 'system_minus':
						$text = '平台优惠' . '满' . floatval($dval['money']) . '元减' . floatval($dval['minus']) . '元';
						$new_discount_list[] = array('type' => $key, 'time_select' => 1, 'value' => $text, 'minus' => floatval($dval['minus']));
						break;
					case 'newuser':
						$text = '店铺首单' . '满' . floatval($dval['money']) . '元减' . floatval($dval['minus']) . '元';
						$new_discount_list[] = array('type' => $key, 'time_select' => 1, 'value' => $text, 'minus' => floatval($dval['minus']));
						break;
					case 'minus':
						$text = '店铺优惠' . '满' . floatval($dval['money']) . '元减' . floatval($dval['minus']) . '元';
						$new_discount_list[] = array('type' => $key, 'time_select' => 1, 'value' => $text, 'minus' => floatval($dval['minus']));
						break;
					case 'system_minus':
						$text = '平台优惠' . '满' . floatval($dval['money']) . '元减' . floatval($dval['minus']) . '元';
						$new_discount_list[] = array('type' => $key, 'time_select' => 1, 'value' => $text, 'minus' => floatval($dval['minus']));
						break;
					case 'delivery':
						$text = '商品' . '满' . floatval($dval['money']) . '元,配送费减' . floatval($dval['minus']) . '元';
						$deliver_minus_list[] = array('type' => $key, 'time_select' => 1, 'value' => $text, 'minus' => floatval($delivery_fee_reduce));
						$deliver_minus_list[] = array('type' => $key, 'time_select' => 2, 'value' => $text, 'minus' => floatval($delivery_fee_reduce2));
						break;
				}
			}
		}
		
		$result['deliver_minus_list'] = $deliver_minus_list;
		$result['discount_list'] = $new_discount_list;
		$result['price'] = strval($return['price']);
		$result['pay_price'] = strval(round($return['vip_discount_money'] - $return['sto_first_reduce'] - $return['sto_full_reduce'] - $return['sys_first_reduce'] - $return['sys_full_reduce'], 2));
		$result['discount_price'] = strval(round($return['price'] - $result['pay_price'], 2));
		
		$result['delivery_type'] = $return['delivery_type'];
		$result['user_adress'] = $user_adress ? $user_adress : array('adress_id' => '', 'uid' => '', 'name' => '', 'phone' => '', 'province' => '', 'city' => '', 'area' => '', 'adress' => '', 'zipcode' => '', 'default' => '', 'longitude' => '', 'latitude' => '', 'detail' => '', 'province_txt' => '', 'city_txt' => '', 'area_txt' => '', 'is_deliver' => false);
		
		
		$result['deliver_time_list'] = $d_list;
		$result['pick_address'] = $pick_address ? $pick_address : array('name' => '', 'area_info' => array('province' => '', 'city' => '', 'area' => ''), 'pick_addr_id' => '', 'phone' => '', 'lat' => '', 'lng' => '', 'addr_type' => '');
		$result['pack_alias'] = $return['store']['pack_alias'];
		$result['freight_alias'] = isset($return['store']['freight_alias']) && $return['store']['freight_alias'] ? $return['store']['freight_alias'] : '配送费';
		$result['pack_alias'] = isset($return['store']['pack_alias']) && $return['store']['pack_alias'] ? $return['store']['pack_alias'] : '打包费';
		$result['packing_charge'] = floatval($return['packing_charge']);
		$result['store_id'] = $return['store']['store_id'];
		$result['mer_id'] = $return['store']['mer_id'];
		$result['name'] = $return['store']['name'];
		$result['images'] = $return['store']['images'];
		
		
		$result['delivery_fee'] = floatval($return['delivery_fee']);//起步配送费
		$result['basic_distance'] = floatval($return['basic_distance']);//起步距离
		$result['per_km_price'] = floatval($return['per_km_price']);//超出起步距离部分的距离每公里的单价
		$result['delivery_fee_reduce'] = floatval($delivery_fee_reduce);//配送费减免的金额
		$result['delivery_fee_reduce2'] = floatval($delivery_fee_reduce2);//配送费减免的金额
		
		$result['delivery_fee2'] = floatval($return['delivery_fee2']);//起步配送费
		$result['basic_distance2'] = floatval($return['basic_distance2']);//起步距离
		$result['per_km_price2'] = floatval($return['per_km_price2']);//超出起步距离部分的距离每公里的单价
		$result['is_invoice'] = ($return['store']['is_invoice'] && $return['store']['invoice_price'] <= $result['price']) ? 1 : 0;
		
		$result['userphone'] = $return['userphone'];
// 		echo '<pre/>';
// 		print_r($result);
// 		print_r($return);
		$this->returnCode(0, $result);
	}
	
	
	
	private function check_cart($store_id, $productCart)
	{
		$store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
		
		if ($store['have_shop'] == 0 || $store['status'] != 1) {
			return array('error_code' => 10110001);
		}
		
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($store['pic_info']);
		$store['images'] = $images ? array_shift($images) : '';
		$now_time = date('H:i:s');
		$is_open = 0;


//		if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
//			$is_open = 1;
//		} else {
//			if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
//				$is_open = 1;
//			}
//			if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
//				if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
//					$is_open = 1;
//				}
//			}
//			if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
//				if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
//					$is_open = 1;
//				}
//			}
//		}
        if($store['store_is_close'] != 0){
            $store = checkAutoOpen($store);
        }
        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171103
        switch ($date){
            case 1 :
                if ($store['open_1'] != '00:00:00' || $store['close_1'] != '00:00:00'){
                    if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
                        $is_open = 1;
                    }
                }
                if($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00'){
                    if($store['open_2'] < $now_time && $now_time < $store['close_2']) {
                        $is_open = 1;
                    }
                }
                if($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00'){
                    if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
                        $is_open = 1;
                    }
                }
                break;
            case 2 ://周二
                if ($store['open_4'] != '00:00:00' || $store['close_4'] != '00:00:00'){
                    if ($store['open_4'] < $now_time && $now_time < $store['close_4']) {
                        $is_open = 1;
                    }
                }
                if($store['open_5'] != '00:00:00' || $store['close_5'] != '00:00:00'){
                    if($store['open_5'] < $now_time && $now_time < $store['close_5']) {
                        $is_open = 1;
                    }
                }
                if($store['open_6'] != '00:00:00' || $store['close_6'] != '00:00:00'){
                    if ($store['open_6'] < $now_time && $now_time < $store['close_6']) {
                        $is_open = 1;
                    }
                }
                break;
            case 3 ://周三
                if ($store['open_7'] != '00:00:00' || $store['close_7'] != '00:00:00'){
                    if ($store['open_7'] < $now_time && $now_time < $store['close_7']) {
                        $is_open = 1;
                    }
                }
                if($store['open_8'] != '00:00:00' || $store['close_8'] != '00:00:00'){
                    if($store['open_8'] < $now_time && $now_time < $store['close_8']) {
                        $is_open = 1;
                    }
                }
                if($store['open_9'] != '00:00:00' || $store['close_9'] != '00:00:00'){
                    if ($store['open_9'] < $now_time && $now_time < $store['close_9']) {
                        $is_open = 1;
                    }
                }

                break;
            case 4 :
                if ($store['open_10'] != '00:00:00' || $store['close_10'] != '00:00:00'){
                    if ($store['open_10'] < $now_time && $now_time < $store['close_10']) {
                        $is_open = 1;
                    }
                }
                if($store['open_11'] != '00:00:00' || $store['close_11'] != '00:00:00'){
                    if($store['open_11'] < $now_time && $now_time < $store['close_11']) {
                        $is_open = 1;
                    }
                }
                if($store['open_12'] != '00:00:00' || $store['close_12'] != '00:00:00'){
                    if ($store['open_12'] < $now_time && $now_time < $store['close_12']) {
                        $is_open = 1;
                    }
                }

                break;
            case 5 :
                if ($store['open_13'] != '00:00:00' || $store['close_13'] != '00:00:00'){
                    if ($store['open_13'] < $now_time && $now_time < $store['close_13']) {
                        $is_open = 1;
                    }
                }
                if($store['open_14'] != '00:00:00' || $store['close_14'] != '00:00:00'){
                    if($store['open_14'] < $now_time && $now_time < $store['close_14']) {
                        $is_open = 1;
                    }
                }
                if($store['open_15'] != '00:00:00' || $store['close_15'] != '00:00:00'){
                    if ($store['open_15'] < $now_time && $now_time < $store['close_15']) {
                        $is_open = 1;
                    }
                }
                break;
            case 6 :
                if ($store['open_16'] != '00:00:00' || $store['close_16'] != '00:00:00'){
                    if ($store['open_16'] < $now_time && $now_time < $store['close_16']) {
                        $is_open = 1;
                    }
                }
                if($store['open_17'] != '00:00:00' || $store['close_17'] != '00:00:00'){
                    if($store['open_17'] < $now_time && $now_time < $store['close_17']) {
                        $is_open = 1;
                    }
                }
                if($store['open_18'] != '00:00:00' || $store['close_18'] != '00:00:00'){
                    if ($store['open_18'] < $now_time && $now_time < $store['close_18']) {
                        $is_open = 1;
                    }
                }
                break;
            case 0 :
                if ($store['open_19'] != '00:00:00' || $store['close_19'] != '00:00:00'){
                    if ($store['open_19'] < $now_time && $now_time < $store['close_19']) {
                        $is_open = 1;
                    }
                }
                if($store['open_20'] != '00:00:00' || $store['close_20'] != '00:00:00'){
                    if($store['open_20'] < $now_time && $now_time < $store['close_20']) {
                        $is_open = 1;
                    }
                }
                if($store['open_21'] != '00:00:00' || $store['close_21'] != '00:00:00'){
                    if ($store['open_21'] < $now_time && $now_time < $store['close_21']) {
                        $is_open = 1;
                    }
                }
                break;
            default :
                $is_open = 1;
        }
        //garfunkel add
        if($store['store_is_close'] != 0){
            $is_open = 0;
        }
        //end  @wangchuanyuan




		if ($is_open == 0) {
			return array('error_code' => 10110002);
		}
	
		$store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store) || empty($store_shop)) return array('error_code' => 10110001);
		
		$store_level_list = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) : '';
		
		$store = array_merge($store, $store_shop);
		$mer_id = $store['mer_id'];
	
		$goods = array();
		$price = 0;//商品原价总价
		$total = 0;//商品个数
		$packing_charge = 0;//打包费总价
		//店铺优惠条件
		$sorts_discout = D('Shop_goods_sort')->get_sorts_discount($store_id);
		$store_discount_money = 0;//店铺折扣后的总价
		
		$vip_discount = 100;
		$user = D('User')->field($field)->where(array('uid' => $this->_uid))->find();
		$user_level = isset($user['level']) ? intval($user['level']) : 0;
		if (isset($store_level_list[$user_level]) && isset($this->user_level[$user_level])) {
			$level_off = $store_level_list[$user_level];
			if ($level_off['type'] == 1) {
				$vip_discount = $level_off['vv'];
			}
		}
		
		foreach ($productCart as $row) {
			$goods_id = $row['productId'];
			$num = $row['count'];
			if ($num < 1) continue;
			$spec_ids = array();
			$str_s = array(); $str_p = array();
			foreach ($row['productParam'] as $r) {
				if ($r['type'] == 'spec') {
					$spec_ids[] = $r['id'];
					$str_s[] = $r['name'];
				} else {
					foreach ($r['data'] as $d) {
						$str_p[] = $d['name'];
					}
				}
			}
			$spec_str = $spec_ids ? implode('_', $spec_ids) : '';
			$t_return = D('Shop_goods')->check_stock($goods_id, $num, $spec_str, $store_shop['stock_type'], $store_id);
			if ($t_return['status'] == 0) {
				return array('error_code' => 1, 'msg' => $t_return['msg']);
				exit;
			} elseif ($t_return['status'] == 2) {
				return array('error_code' => 1, 'msg' => $t_return['msg']);
				exit();
			}
			$total += $num;
			$price += $t_return['price'] * $num;
			$packing_charge += $t_return['packing_charge'] * $num;
				
			$t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? intval($sorts_discout[$t_return['sort_id']]['discount']) : 100;
			$t_discount_type = $sorts_discout[$t_return['sort_id']]['discount_type'];
			
			//该商品的折扣类型 0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
			$discount_type = 0;
			//折扣率 0：无折扣
			$discount_rate = 0;
			if ($t_discount < 100) {
			    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
			        $discount_type = 2;
			    } else {
			        $discount_type = 1;
			    }
			    $discount_rate = $t_discount;
			}
			
			//$store_discount_money += $num * $t_return['price'] * $t_discount / 100;
			$this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);
			$only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);
			if ($sorts_discout['discount_type'] == 0) {//折上折
			    if ($vip_discount < 100) {
			        $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
			        $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
			    }
				$this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
				$only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
			} else {//折扣最优
			    $t_vip_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2);
			    if ($t_vip_price < $this_goods_total_price) {
			        $this_goods_total_price = $t_vip_price;
			        
			        if ($vip_discount < 100) {
			            $discount_type = 3;
			            $discount_rate = $vip_discount;
			        }
			        $only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2);
			    }
				    
// 				$this_goods_total_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2) > $this_goods_total_price ? $this_goods_total_price : $num * round($t_return['price'] * $vip_discount * 0.01, 2);
// 				$only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2) > $only_discount_price ? $only_discount_price : round($t_return['price'] * $vip_discount * 0.01, 2);
			}
			
			$store_discount_money += $this_goods_total_price;
			$str = '';
			$str_s && $str = implode(',', $str_s);
			$str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
			//discount_type折扣类型，false：店铺折扣，true：分类折扣
			
			$goods[] = array('name' => $row['productName'],
					'discount_type' => $t_discount_type ? true : false,
					'discount_type_data' => $discount_type,
                    'discount_rate' => $discount_rate,//折扣率
					'is_discount' => ($t_discount != 100 && $t_discount != 0) ? true : false,
					'is_seckill_price' => $t_return['is_seckill_price'] ? true : false,
					'num' => $num,
					'goods_id' => $goods_id,
					'cost_price' => floatval(round($t_return['cost_price'], 2)),
					'price' => floatval(round($t_return['price'], 2)),
					'old_price' => floatval(round($t_return['old_price'], 2)),
					'discount_price' => floatval(round($only_discount_price, 2)),
					'number' => $t_return['number'],
					'image' => $t_return['image'],
				    'sort_id' => $t_return['sort_id'],
					'packing_charge' => floatval(round($t_return['packing_charge'], 2)),
					'unit' => $t_return['unit'],
					'str' => $str,
					'spec_id' => $spec_str);
		}
	
		$minus_price = 0;
		//会员等级优惠  外卖费不参加优惠
		$vip_discount_money = round($store_discount_money, 2);
// 		$level_off = false;
// 		if (!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])) {
// 			if (isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])) {
// 				$level_off = $this->leveloff[$this->user_session['level']];
// 				if ($sorts_discout['discount_type'] == 0) {
// 					if ($level_off['type'] == 1) {
// 						$vip_discount_money = $store_discount_money *($level_off['vv'] / 100);
// 						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 						$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
// 					} elseif($level_off['type'] == 2) {
// 						$vip_discount_money = $store_discount_money - $level_off['vv'];
// 						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 						$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
// 					}
	
// 				} else {
// 					if ($level_off['type'] == 1) {
// 						$vip_discount_money = $total_money *($level_off['vv'] / 100);
// 						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 						$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
// 					} elseif($level_off['type'] == 2) {
// 						$vip_discount_money = $total_money - $level_off['vv'];
// 						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
// 						$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
// 					}
// 					$vip_discount_money = $vip_discount_money > $store_discount_money ? $store_discount_money : $vip_discount_money;
// 				}
// 			}
// 		}
	
	
	
		$vip_discount_money = round($vip_discount_money, 2);
		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discount_list = null;
	
		//优惠
		$sys_first_reduce = 0;//平台首单优惠
		$sto_first_reduce = 0;//店铺首单优惠
		$sys_full_reduce = 0;//平台满减
		$sto_full_reduce = 0;//店铺满减
		$shop_order_obj = D("Shop_order");
	
		$sys_count = $shop_order_obj->where(array('uid' => $this->_uid))->count();
		if (empty($sys_count)) {
			if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money)) {
				$d_tmp['discount_type'] = 1;//平台首单
				$d_tmp['money'] = $d_tmp['full_money'];
				$d_tmp['minus'] = $d_tmp['reduce_money'];
				$discount_list['system_newuser'] = $d_tmp;
				$sys_first_reduce = $d_tmp['reduce_money'];
			}
		}
	
	
		if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money)) {
			$d_tmp['discount_type'] = 2;//平台满减
			$d_tmp['money'] = $d_tmp['full_money'];
			$d_tmp['minus'] = $d_tmp['reduce_money'];
			$discount_list['system_minus'] = $d_tmp;
			$sys_full_reduce = $d_tmp['reduce_money'];
		}
	
		$sto_count = $shop_order_obj->where(array('uid' => $this->_uid, 'store_id' => $store_id))->count();
		$sto_first_reduce = 0;
		if (empty($sto_count)) {
			if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money, $store_id)) {
				$d_tmp['discount_type'] = 3;//店铺首单
				$d_tmp['money'] = $d_tmp['full_money'];
				$d_tmp['minus'] = $d_tmp['reduce_money'];
				$discount_list['newuser'] = $d_tmp;
				$sto_first_reduce = $d_tmp['reduce_money'];
			}
		}
		$sto_full_reduce = 0;
		if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money, $store_id)) {
			$d_tmp['discount_type'] = 4;//店铺满减
			$d_tmp['money'] = $d_tmp['full_money'];
			$d_tmp['minus'] = $d_tmp['reduce_money'];
			$discount_list['minus'] = $d_tmp;
			$sto_full_reduce = $d_tmp['reduce_money'];
		}
	
		//起步运费
		$delivery_fee = 0;
		//超出距离部分的单价
		$per_km_price = 0;
		//起步距离
		$basic_distance = 0;
		//减免配送费的金额
		$delivery_fee_reduce = 0;
	
		//起步运费
		$delivery_fee2 = 0;
		//超出距离部分的单价
		$per_km_price2 = 0;
		//起步距离
		$basic_distance2 = 0;
		//减免配送费的金额
		// 		$delivery_fee_reduce2 = 0;
	
		if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {//平台配送|平台或自提
			if ($store_shop['s_is_open_own']) {//开启了店铺的独立配送费的设置
				//配送时段一的配置
				if ($store_shop['s_free_type'] == 0) {//免配送费
						
				} elseif ($store_shop['s_free_type'] == 1) {//不免
					$delivery_fee = $store_shop['s_delivery_fee'];
					$per_km_price = $store_shop['s_per_km_price'];
					$basic_distance = $store_shop['s_basic_distance'];
				} elseif ($store_shop['s_free_type'] == 2) {//满免
					if ($price < $store_shop['s_full_money']) {
						$delivery_fee = $store_shop['s_delivery_fee'];
						$per_km_price = $store_shop['s_per_km_price'];
						$basic_distance = $store_shop['s_basic_distance'];
					}
				}
				//配送时段二的配送
				if ($store_shop['s_free_type2'] == 0) {//免配送费
						
				} elseif ($store_shop['s_free_type2'] == 1) {//不免
					$delivery_fee2 = $store_shop['s_delivery_fee2'];
					$per_km_price2 = $store_shop['s_per_km_price2'];
					$basic_distance2 = $store_shop['s_basic_distance2'];
				} elseif ($store_shop['s_free_type2'] == 2) {//满免
					if ($price < $store_shop['s_full_money2']) {
						$delivery_fee2 = $store_shop['s_delivery_fee2'];
						$per_km_price2 = $store_shop['s_per_km_price2'];
						$basic_distance2 = $store_shop['s_basic_distance2'];
					}
				}
			} else {
				$delivery_fee = $this->config['delivery_fee'];
				$per_km_price = $this->config['per_km_price'];
				$basic_distance = $this->config['basic_distance'];
	
				$delivery_fee2 = $this->config['delivery_fee2'];
				$per_km_price2 = $this->config['per_km_price2'];
				$basic_distance2 = $this->config['basic_distance2'];
			}
			// 			$delivery_fee = $this->config['delivery_fee'];
			//使用平台的优惠（配送费的减免）
			if ($d_tmp = $this->get_reduce($discounts, 2, $price)) {
				$d_tmp['discount_type'] = 5;//平台配送费满减
				$d_tmp['money'] = $d_tmp['full_money'];
				$d_tmp['minus'] = $d_tmp['reduce_money'];
				$discount_list['delivery'] = $d_tmp;
				$delivery_fee_reduce = $d_tmp['reduce_money'];
			}
			// 			$delivery_fee = $delivery_fee - $delivery_fee_reduce;
			// 			$delivery_fee = $delivery_fee >= 0 ? $delivery_fee : 0;
		} else {//商家配送|商家或自提|快递配送
			if ($store_shop['reach_delivery_fee_type'] == 0) {
	
			} elseif ($store_shop['reach_delivery_fee_type'] == 1) {
				$delivery_fee = $store_shop['delivery_fee'];
				$per_km_price = $store_shop['per_km_price'];
				$basic_distance = $store_shop['basic_distance'];
	
				$delivery_fee2 = $store_shop['delivery_fee2'];
				$per_km_price2 = $store_shop['per_km_price2'];
				$basic_distance2 = $store_shop['basic_distance2'];
			} elseif ($store_shop['reach_delivery_fee_type'] == 2)  {
				if ($price < $store_shop['no_delivery_fee_value']) {
					$delivery_fee = $store_shop['delivery_fee'];
					$per_km_price = $store_shop['per_km_price'];
					$basic_distance = $store_shop['basic_distance'];
						
					$delivery_fee2 = $store_shop['delivery_fee2'];
					$per_km_price2 = $store_shop['per_km_price2'];
					$basic_distance2 = $store_shop['basic_distance2'];
				}
			}
			if ($store_shop['reach_delivery_fee_type2'] == 0) {
	
			} elseif ($store_shop['reach_delivery_fee_type2'] == 1) {
				$delivery_fee2 = $store_shop['delivery_fee2'];
				$per_km_price2 = $store_shop['per_km_price2'];
				$basic_distance2 = $store_shop['basic_distance2'];
			} elseif ($store_shop['reach_delivery_fee_type2'] == 2)  {
				if ($price < $store_shop['no_delivery_fee_value2']) {
					$delivery_fee2 = $store_shop['delivery_fee2'];
					$per_km_price2 = $store_shop['per_km_price2'];
					$basic_distance2 = $store_shop['basic_distance2'];
				}
			}
		}
	
		if (empty($goods)) {
			return array('error_code' => 10110003);
		} else {
			$data = array('error_code' => false);
			$data['total'] = $total;
			$data['price'] = $price;//商品实际总价
			$data['discount_price'] = $vip_discount_money;//折扣后的总价
			$data['goods'] = $goods;
			$data['store_id'] = $store_id;
			$data['mer_id'] = $mer_id;
			$data['store'] = $store;
			

			$data['discount_list'] = $discount_list;
				
			$data['delivery_type'] = $store_shop['deliver_type'];
				
			$data['sys_first_reduce'] = floatval($sys_first_reduce);//平台新单优惠的金额
			$data['sys_full_reduce'] = floatval($sys_full_reduce);//平台满减优惠的金额
			$data['sto_first_reduce'] = floatval($sto_first_reduce);//店铺新单优惠的金额
			$data['sto_full_reduce'] = floatval($sto_full_reduce);//店铺满减优惠的金额
				
			$data['store_discount_money'] = floatval($store_discount_money);//店铺折扣后的总价
			$data['vip_discount_money'] = floatval($vip_discount_money);//VIP折扣后的总价
			$data['packing_charge'] = floatval($packing_charge);//总的打包费
				
			$data['delivery_fee'] = floatval($delivery_fee);//起步配送费
			$data['basic_distance'] = floatval($basic_distance);//起步距离
			$data['per_km_price'] = floatval($per_km_price);//超出起步距离部分的距离每公里的单价
			$data['delivery_fee_reduce'] = floatval($delivery_fee_reduce);//配送费减免的金额
				
			$data['delivery_fee2'] = floatval($delivery_fee2);//起步配送费
			$data['basic_distance2'] = floatval($basic_distance2);//起步距离
			$data['per_km_price2'] = floatval($per_km_price2);//超出起步距离部分的距离每公里的单价
			$data['userphone'] = isset($user['phone']) && $user['phone'] ? $user['phone'] : '';	
			return $data;
		}
	}
	
	private function get_reduce($discounts, $type, $price, $store_id = 0)
	{
		$reduce_money = 0;
		$return = null;
		if (isset($discounts[$store_id])) {
			foreach ($discounts[$store_id] as $row) {
				if ($row['type'] == $type) {
					if ($price >= $row['full_money']) {
						if ($reduce_money < $row['reduce_money']) {
							$reduce_money = $row['reduce_money'];
							$return = $row;
						}
					}
				}
			}
		}
		return $return;
	}
	
	public function save_order()
	{
		if (empty($_POST)) {

			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
		}
		

		//post
		//store_id   店铺ID
		//productCart   数组购物车，和COOKIE里数据一致
		
		
		$productCart = $_POST['productCart'];
		if (empty($productCart)) {
			$this->returnCode(10110003);
		}
		if (!is_array($productCart)) {
			$productCart = json_decode(htmlspecialchars_decode($productCart), true);
		}
		$store_id = intval($_POST['store_id']);
		
		$return = D('Shop_goods')->checkCart($store_id, $this->_uid, $productCart);
// 		$return = $this->check_cart($store_id, $productCart);
		
		if ($return['error_code']) {
			$this->returnCode(1, null, $return['msg']);
		}
// 		if (IS_POST) {
// 			$phone = isset($_POST['ouserTel']) ? htmlspecialchars($_POST['ouserTel']) : '';
// 			$name = isset($_POST['ouserName']) ? htmlspecialchars($_POST['ouserName']) : '';
// 			$address = isset($_POST['ouserAddres']) ? htmlspecialchars($_POST['ouserAddres']) : '';
// 			$pick_address = isset($_POST['pick_address']) ? htmlspecialchars($_POST['pick_address']) : '';
			$invoice_head = isset($_POST['invoice_head']) ? htmlspecialchars($_POST['invoice_head']) : '';  //cue_field
			$order_from = isset($_POST['order_from']) ? intval($_POST['order_from']) : 3;
			$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
			$pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : 0;
// 			$pick_id = substr($pick_id, 1);
			$deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
			$expect_use_time_post = isset($_POST['expect_use_time']) ? htmlspecialchars($_POST['expect_use_time']) : 0;
// 			$arrive_date = isset($_POST['oarrivalDate']) ? htmlspecialchars($_POST['oarrivalDate']) : 0;
			$note = isset($_POST['desc']) ? htmlspecialchars($_POST['desc']) : '';
			if ($return['price'] < $return['store']['basic_price']) {
				if (in_array($return['store']['deliver_type'], array(2, 3, 4))) {
					$deliver_type = 1;
				} else {
					$this->returnCode(1, null, '订单没有达到起送价，不予配送');
				}
			}
			if ($deliver_type != 1) {//配送方式是：非自提和非快递配送
				if ($user_address = D('User_adress')->field(true)->where(array('adress_id' => $address_id, 'uid' => $this->_uid))->find()) {
					if ($user_address['longitude'] > 0 && $user_address['latitude'] > 0) {
						$distance = getDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
						$delivery_radius = $return['store']['delivery_radius'] * 1000;
						if ($distance > $delivery_radius && $return['delivery_type'] != 5) {
							$this->returnCode(1, null, '已有地址均不在配送范围');
						}
						$province = D('Area')->get_area_by_areaId($user_adress['province'],false);
						$user_adress['province_txt'] = $province['area_name'];
							
						$city = D('Area')->get_area_by_areaId($user_adress['city'],false);
						$user_adress['city_txt'] = $city['area_name'];
							
						$area = D('Area')->get_area_by_areaId($user_adress['area'],false);
						$user_adress['area_txt'] = $area['area_name'];
					} else {
						$this->returnCode(1, null, '您选择的地址没有完善，请先编辑地址，点击“点击选择位置”进行完善');
					}
				} else {
					$this->returnCode(1, null, '地址信息不存在');
				}
			}
			$user_info = D('User')->where(array('uid' => $this->_uid))->find();
			$now_time = time();
			$order_data = array();
			$order_data['mer_id'] = $return['mer_id'];
			$order_data['store_id'] = $return['store_id'];
			$order_data['uid'] = $this->_uid;
	
			$order_data['desc'] = $note;
			$order_data['create_time'] = $now_time;
			$order_data['last_time'] = $now_time;
			$order_data['invoice_head'] = $invoice_head;
			$order_data['village_id'] = 0;
	
			$order_data['num'] = $return['total'];
			$order_data['packing_charge'] = $return['packing_charge'];//打包费
			$order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
			$order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
			$orderid = date('ymdhis') . substr(microtime(), 2, 8 - strlen($this->_uid)) . $this->_uid;
			$order_data['real_orderid'] = $orderid;
			$order_data['no_bill_money'] = 0;//无需跟平台对账的金额
				
			if ($deliver_type == 1) {//自提
				if (empty($pick_id)) {
					$this->returnCode(1, null, '抱歉!该商家暂未添加自提点,无法购买');
				} else {
					$pre = substr($pick_id, 0, 1);
					$pick_id = substr($pick_id, 1);
					if ($pre == 's') {
						if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $pick_id))->find()) {//get_storelist_by_merId($mer_id);
							$area[] = $store['province_id'];
							$area[] = $store['city_id'];
							$area[] = $store['area_id'];
							$pick_addr = array('name' => $store['adress'] . ' ' . $store['name'], 'area_info' => array('province' => $store['province_id'], 'city' => $store['city_id'], 'area' => $store['area_id']), 'pick_addr_id' => 's' . $store['store_id'], 'phone' => $store['phone'], 'long' => $store['long'],'lat' => $store['lat'], 'addr_type' => 1);
						} else {
							$this->returnCode(1, null, '不存在的自提点');
						}
					} else {
						if ($address_p = M('Pick_address')->field(true)->where(array('id' => $pick_id))->find()) {
							$area[] = $address_p['province_id'];
							$area[] = $address_p['city_id'];
							$area[] = $address_p['area_id'];
							$pick_addr = array('name' => $address_p['pick_addr'], 'area_info' => array('province' => $address_p['province_id'], 'city' => $address_p['city_id'], 'area' => $address_p['area_id']), 'pick_addr_id' => 'p' . $address_p['id'], 'phone' => $address_p['phone'], 'long' => $address_p['long'], 'lat' => $address_p['lat'], 'addr_type' => 2);
						} else {
							$this->returnCode(1, null, '抱歉!该商家暂未添加自提点,无法购买');
						}
					}
					$where['area_id'] = array('in', implode(',', $area));
					$area_name = M('Area')->where($where)->getField('area_id,area_name');
					$pick_addr['area_info']['province'] = $area_name[$pick_addr['area_info']['province']];
					$pick_addr['area_info']['city'] = $area_name[$pick_addr['area_info']['city']];
					$pick_addr['area_info']['area'] = $area_name[$pick_addr['area_info']['area']];
				}
				
				$order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
				$delivery_fee = $order_data['freight_charge'] = 0;//运费
				
				$order_data['username'] = isset($user_info['nickname']) && $user_info['nickname'] ? $user_info['nickname'] : '';
				$order_data['userphone'] = isset($user_info['phone']) && $user_info['phone'] ? $user_info['phone'] : '';
				$order_data['address'] = $pick_addr['area_info']['province'] . ' ' . $pick_addr['area_info']['city'] . ' ' . $pick_addr['area_info']['area'] . ' ' . $pick_addr['name'] . ' 电话：' . $pick_addr['phone'];
				$order_data['address_id'] = 0;
				$order_data['pick_id'] = $pick_id;
				$order_data['status'] = 7;
				$order_data['expect_use_time'] = time() + $return['store']['send_time'] * 60;//客户期望使用时间
			} else {//配送
				$order_data['username'] = $user_address['name'];
				$order_data['userphone'] = $user_address['phone'];
				$order_data['address'] = $user_address['province_txt'] . ' ' . $user_address['city_txt'] . ' ' . $user_address['area_txt'] . ' ' . $user_address['adress'] . ' ' . $user_address['detail'];
				$order_data['address_id'] = $address_id;
				$order_data['lat'] = $user_address['latitude'];
				$order_data['lng'] = $user_address['longitude'];
				if ($expect_use_time_post == 0) {
					$arrive_date = date('Y-m-d');
				}
				if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
					$delivery_times = explode('-', $this->config['delivery_time']);
					$start_time = $delivery_times[0] . ':00';
					$stop_time = $delivery_times[1] . ':00';
	
					$delivery_times2 = explode('-', $this->config['delivery_time2']);
					$start_time2 = $delivery_times2[0] . ':00';
					$stop_time2 = $delivery_times2[1] . ':00';
				} else {
					$start_time = $return['store']['delivertime_start'];
					$stop_time = $return['store']['delivertime_stop'];
	
					$start_time2 = $return['store']['delivertime_start2'];
					$stop_time2 = $return['store']['delivertime_stop2'];
				}
	
				if ($start_time == $stop_time && $start_time == '00:00:00') {
					$stop_time = '23:59:59';
				}
				$if_start_time = strtotime(date('Y-m-d ') . $start_time);
				$if_stop_time = strtotime(date('Y-m-d ') . $stop_time);
	
				if ($if_start_time > $if_stop_time) {
					$if_stop_time = $if_stop_time + 86400;
				}
	
				$if_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
				$if_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
				if ($if_start_time2 > $if_stop_time2) {
					$if_stop_time2 = $if_stop_time2 + 86400;
				}
	
				if ($expect_use_time_post == 0) {
					if ($arrive_date != date('Y-m-d')) {
						$arrive_time = strtotime($expect_use_time_post);
					} else {
						$arrive_time = time() + $return['store']['send_time'] * 60;
						if ($start_time == $stop_time && $start_time == '00:00:00') {
	
						} else {
							$_start_time = strtotime(date('Y-m-d ') . $start_time);
							$_stop_time = strtotime(date('Y-m-d ') . $stop_time);
							if ($_start_time > $_stop_time) {
								$_stop_time = $_stop_time + 86400;
							}
							if ($arrive_time < $_start_time) {
								$arrive_time = $_start_time;
							} elseif ($_start_time <= $arrive_time && $arrive_time <= $_stop_time) {
	
							} else {
								$_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
								$_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
								if ($_start_time2 > $_stop_time2) {
									$_stop_time2 = $_stop_time2 + 86400;
								}
								if ($arrive_time < $_start_time2) {
									$arrive_time = $_start_time2;
								} elseif ($_start_time2 <= $arrive_time && $arrive_time <= $_stop_time2) {
	
								} else {
									$arrive_time = $_start_time + 86400;
								}
							}
						}
					}
				} else {
					$arrive_time = strtotime($expect_use_time_post);
				}
	
				$order_data['expect_use_time'] = $arrive_time ? $arrive_time : time() + $return['store']['send_time'] * 60;//客户期望使用时间
	
	
				//计算配送费
				$distance = $distance / 1000;
				if ($return['delivery_type'] == 5) {//快递配送
					$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
					$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
					$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
					$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
					$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
				} else {
					$expect_use_time_temp = strtotime(date('Y-m-d ') . date('H:i:s', $order_data['expect_use_time']));
					if ($if_start_time <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time) {//时间段一
						$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
						$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
						$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
						$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
						$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
					} elseif ($if_start_time2 <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time2) {//时间段二
						$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
						$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
						$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
						$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
						$delivery_fee = $order_data['freight_charge'] = $return['delivery_fee2'];//运费
					} else {
						$this->returnCode(1, null, '您选择的时间不在配送时间段内！');
					}
				}
				if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {//平台配送
					$order_data['is_pick_in_store'] = 0;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
					$order_data['no_bill_money'] = $delivery_fee;
				} elseif ($return['delivery_type'] == 1 || $return['delivery_type'] == 4)  {
					$order_data['is_pick_in_store'] = 1;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
				} else {
					$order_data['is_pick_in_store'] = 3;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
				}
			}
			$order_data['order_from'] = $order_from;//订单来源:0：wap快店，1：wap商城，2：Android，3：ios,4:小程序,5：pc快店
			$order_data['goods_price'] = $return['price'];//商品的价格
			$order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
			$order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
			$order_data['price'] = $order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'];//实际要支付的价格
			$order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情
// 			if ($return['price'] - $return['store_discount_money'] > 0) {
// 				$order_data['discount_detail'] = '店铺折扣优惠：' . floatval($return['price'] - $return['store_discount_money']);
// 			}
// 			if ($return['store_discount_money'] - $return['vip_discount_money'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']) : 'VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']);
// 			}
// 			if ($return['sys_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台首单减：' . $return['sys_first_reduce'] : '平台首单减：' . $return['sys_first_reduce'];
// 			}
// 			if ($return['sys_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台满减：' . $return['sys_full_reduce'] : '平台满减：' . $return['sys_full_reduce'];
// 			}
// 			if ($return['sto_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺首单减：' . $return['sto_first_reduce'] : '店铺首单减：' . $return['sto_first_reduce'];
// 			}
// 			if ($return['sto_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺满减：' . $return['sto_full_reduce'] : '店铺满减：' . $return['sto_full_reduce'];
// 			}
			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
				
			//自定义字段
			if($_POST['cue_field']){
				$order_data['cue_field'] = serialize(json_decode(htmlspecialchars_decode($_POST['cue_field']), true));
			}
				
			if ($order_id = D('Shop_order')->add($order_data)) {
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
				if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
					D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
					//D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
				}
				$detail_obj = D('Shop_order_detail');
				$goods_obj = D("Shop_goods");
				foreach ($return['goods'] as $grow) {
					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time());
					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
					$detail_data['discount_type'] = intval($grow['discount_type_data']);
					$detail_data['discount_rate'] = $grow['discount_rate'];
					$detail_data['sort_id'] = $grow['sort_id'];
					$detail_data['old_price'] = floatval($grow['old_price']);
					$detail_data['discount_price'] = floatval($grow['discount_price']);
					D('Shop_order_detail')->add($detail_data);
					$order_data['reduce_stock_type'] && $goods_obj->update_stock($grow);//修改库存
				}
				if ($user_info['openid']) {
					$keyword2 = '';
					$pre = '';
					foreach ($return['goods'] as $menu) {
						$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
						$pre = '\n\t\t\t';
					}
					$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order_id . '&mer_id=' . $order_data['mer_id'] . '&store_id=' . $order_data['store_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $user_info['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['shop_alias_name'].'下单成功，感谢您的使用！'));
				}
	
				$msg = ArrayToStr::array_to_str($order_id, 'shop_order');
				$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
				$op->printit($return['mer_id'], $return['store_id'], $msg, 0);
	
				$str_format = ArrayToStr::print_format($order_id, 'shop_order');
				foreach ($str_format as $print_id => $print_msg) {
					$print_id && $op->printit($return['mer_id'], $return['store_id'], $print_msg, 0, $print_id);
				}
	
	
				$sms_data = array('mer_id' => $return['mer_id'], 'store_id' => $return['store_id'], 'type' => 'shop');
				if ($this->config['sms_shop_place_order'] == 1 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = $user_info['uid'];
					$sms_data['mobile'] = $order_data['userphone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您' . date("Y-m-d H:i:s") . '在【' . $return['store']['name'] . '】中下了一个订单，订单号：' . $orderid;
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_shop_place_order'] == 2 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $return['store']['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客【' . $order_data['username'] . '】在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $orderid . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}
	
				$this->returnCode(0, array('order_id' => $order_id, 'type' => 'shop'));
			} else {
				$this->returnCode(1, null, '订单保存失败');
			}
// 		} else {
// 			$this->returnCode(1, null, '不合法的提交');
// 		}
	}
	
	
	public function order_list()
	{
		$status = isset($_POST['status']) ? intval($_POST['status']) : 0;
		
		import('@.ORG.new_reply_ajax_page');
		
		$where = "is_del=0 AND uid={$this->_uid}";//array('uid' => $this->user_session['uid'], 'status' => array('lt', 3));
		if ($status == -1) {
			$where .= " AND paid=0";
		} elseif ($status == 1) {
			$where .= " AND paid=1 AND status<2";
		} elseif ($status == 2) {
			$where .= " AND paid=1 AND status=2";
		}
		
		$order_count = D("Shop_order")->where($where)->count();
		$order_count = intval($order_count);
		$page_size = 10;
		$p = new Page($order_count, $page_size);
		
		
		$order_list = D("Shop_order")->field(true)->where($where)->order('order_id DESC')->limit($p->firstRow . ',' . $page_size)->select();
		foreach ($order_list as $st) {
			$store_ids[] = $st['store_id'];
		}
		$m = array();
		if ($store_ids) {
			$store_image_class = new store_image();
			$merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_list as $li) {
				$images = $store_image_class->get_allImage_by_path($li['pic_info']);
				$li['image'] = $images ? array_shift($images) : array();
				unset($li['status']);
				$m[$li['store_id']] = $li;
			}
		}
		$list = array();
		foreach ($order_list as $ol) {
			$temp = array('real_orderid' => $ol['real_orderid']);
			$temp['create_time'] = date('Y-m-d H:i:s', $ol['create_time']);
			if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
				$temp['image'] = $m[$ol['store_id']]['image'];
				$temp['name'] = $m[$ol['store_id']]['name'];
			} else {
				$temp['image'] = '';
				$temp['name'] = '';
			}
			
			$temp['num'] = $ol['num'];
			$temp['order_id'] = $ol['order_id'];
			$temp['paid'] = $ol['paid'];
			$temp['status'] = $ol['status'];
			$temp['price'] = strval(floatval($ol['price']));
			$list[] = $temp;
		}
	
		$return['count'] = $order_count;
		$return['list']  = $list;
// 		$return['page']  = $p->show();
// 		$return['now']  = $p->nowPage;
		$return['total']  = $p->totalPage;
		$this->returnCode(0, $return);
	}
	
	public function order_detail()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		if ($order = D('Shop_order')->field(true)->where(array('order_id' => $order_id, 'uid' => $this->_uid))->find()) {
			
			$store = D("Merchant_store")->where(array('store_id' => $order['store_id']))->find();
			if (empty($store)) $this->returnCode(1, null, '订单信息不合法');
			
			$store_image_class = new store_image();
			$images = $store_image_class->get_allImage_by_path($store['pic_info']);
			$return = array('real_orderid' => $order['real_orderid']);
			$return['image'] = $images ? array_shift($images) : array();
			$return['name'] = $store['name'];
			$return['num'] = $order['num'];
			$return['order_id'] = $order['order_id'];
			$return['paid'] = $order['paid'];
			$return['status'] = $order['status'];
			$return['price'] = floatval($order['price']);
			$return['create_time'] = date('Y-m-d H:i:s', $order['create_time']);
			
			$this->returnCode(0, $return);
		} else {
			$this->returnCode(1, null, '订单信息不合法');
		}
	}
}