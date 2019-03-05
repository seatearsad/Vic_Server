<?php
class SpecialAction extends BaseAction{
	public function index(){
		
		if(empty($this->user_session)){
			if($this->is_app_browser){
				$location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
				$this->error_tips('请先进行登录！',U('Login/index',$location_param));
			}
		}

		if($_GET['lat']&&$_GET['long']){
			$long_lat['lat'] = $_GET['lat'];
			$long_lat['long'] = $_GET['long'];
		}else{
			$long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		}
		if(!empty($long_lat)){
			import('@.ORG.longlat');
			$longlat_class = new longlat();
			$long_lat = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);
			$long_lat['long'] = $long_lat['lng'];
			$this->assign('long_lat',$long_lat);
		}
		
		$database_special = D('Special');
		$condition_special['pigcms_id'] = $_GET['id'];
        $now_special = $database_special->where($condition_special)->find();
		
		$coupon_id_arr = array();
		if($now_special['coupon']){
			$now_special['coupon'] = unserialize($now_special['coupon']);
			foreach($now_special['coupon'] as $value){
				$coupon_id_arr[] = $value['id'];
			}
		}
		$now_special['coupon_id'] = implode(',',$coupon_id_arr);
		
		$now_special['product_list'] = unserialize($now_special['product_list']);
		$product_list = array();
		foreach($now_special['product_list'] as $key=>$value){
			$tmp_arr = array();
			foreach($value['product'] as $v){
				$tmp_arr[$key][] = $v['id'];
			}
			$product_list[$key] = implode(',',$tmp_arr[$key]);
		}
		// dump($product_list);
		$now_special['product_id_arr'] = $product_list;
		
		
		// dump($now_special);
		$this->assign('now_special',$now_special);
		
		$database_special->where($condition_special)->setInc('hits');
		
		$this->display();
	}
	public function ajax_get_coupon_by_ids(){
		$ids = $_POST['ids'];
		$ids_arr = explode(',',$ids);
		$res = D('System_coupon')->get_coupon_list_by_ids($ids);
		$time = time();
		foreach($ids_arr as $v){
			$can = true;
			if($res[$v]['end_time']<$time||$res[$v]['start_time']>$time||$res[$v]['status']!=1||$res[$v]['had_pull']==$res[$v]['num']){
				$can = false;
			}
			$arr[]=array(
				'id'=>$res[$v]['coupon_id'],
				'name'=>$res[$v]['name'],
				'order_money'=>floatval($res[$v]['order_money']),
				'discount'=>floatval($res[$v]['discount']),
				'can'=>$can,
			);
		}
		$this->success($arr);
	}
	public function ajax_get_shop_by_ids(){
		//和快店列表返回一致$_POST['ids']
		$ids = $_POST['ids'];
		
		$where = array('lat' => $_POST['user_lat'], 'long' => $_POST['user_long']);
		
		$lists = D('Merchant_store_shop')->get_list_by_ids($ids, $where);
		// dump(D('Merchant_store_shop'));
		$return = array();
		$now_time = date('H:i:s');
		$id_array = explode(',', $ids);
		foreach ($id_array as $store_id) {
			$row = isset($lists['shop_list'][$store_id]) ? $lists['shop_list'][$store_id] : '';
			if (empty($row)) continue;
			$temp = array();
			$temp['id'] = $row['store_id'];
			$temp['name'] = $row['name'];
			$temp['range'] = $row['range'];
			$temp['image'] = $row['image'];
			$temp['star'] = $row['score_mean'];
			$temp['month_sale_count'] = $row['sale_count'];
			$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
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
//				if ($row['open_2'] != '00:00:00' && $row['close_2'] != '00:00:00') {
//					$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
//					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
//						$temp['is_close'] = 0;
//					}
//				}
//				if ($row['open_3'] != '00:00:00' && $row['close_3'] != '00:00:00') {
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
            //garfunkel add
            if($row['store_is_close'] != 0){
                $temp['is_close'] = 1;
            }
            //end  @wangchuanyuan


				
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
					if ($row['reach_delivery_fee_type'] == 0) {
						if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
							$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
						}
					} elseif ($row['reach_delivery_fee_type'] == 1) {
						//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
					} elseif ($row['reach_delivery_fee_type'] == 2) {
						$temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
					}
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$return[] = $temp;
		}
		$this->success($return);
	}
}
	
?>