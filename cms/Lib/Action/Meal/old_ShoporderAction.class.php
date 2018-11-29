<?php
/*
 * 订餐
 *
 */
class ShoporderAction extends BaseAction
{
	
	public function index()
	{
		//所有分类 包含2级分类
		$all_category_list = D('Group_category')->get_category();
		$this->assign('all_category_list',$all_category_list);
		//delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提
    	$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
    	$cookieData = isset($_POST['foodshop_cart']) && $_POST['foodshop_cart'] ? htmlspecialchars_decode($_POST['foodshop_cart']) : $_SESSION['foodshop_cart'];
    	$_SESSION['foodshop_cart'] = $cookieData;
    	$cookieData = json_decode($cookieData, true);
    	if (empty($cookieData)) $this->error('您的购物车是空的');
    	$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
// 		$return = $this->check_cart();

		if ($return['error_code']) $this->error($return['msg']);
	
		$village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
		$this->assign('village_id', $village_id);
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
				$this->error('商家配置的配送信息不正确');
			} elseif ($return['delivery_type'] == 3) {
				$return['delivery_type'] = 2;
			}
		}
	
		$return['basic_price'] = $basic_price = $return['price'];
		$return['price'] = round($return['vip_discount_money'] - round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'], 2), 2);//实际要支付的价格
	
	
		$advance_day = $return['store']['advance_day'];
		$advance_day = empty($advance_day) ? 1 : $advance_day;
		$date['min_date'] = date('Y-m-d H:i:s', time() + $return['store']['send_time']);
		$date['max_date'] = date('Y-m-d H:i:s', strtotime("+{$advance_day} day") + $return['store']['send_time']);
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
	
		$have_two_time = 1;//是否两个时段 0：没有，1有
	
		$is_cross_day_1 = 0;//第一时间段是否跨天 0：不跨天，1：跨天
		$is_cross_day_2 = 0;//第二时间段是否跨天 0：不跨天，1：跨天
	
		$time = time() + $return['store']['send_time'] * 60;//默认的期望送达时间
	
		$format_second_time = 1;//是否要格式化时间段二
	
		$now_time_value = 1;//当前所处的时间段
		if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
			$start_time = strtotime(date('Y-m-d ') . '00:00');
			$stop_time = strtotime(date('Y-m-d ') . '23:59');
			$have_two_time = 0;
		} else {
			$start_time = strtotime(date('Y-m-d ') . $start_time);
			$stop_time = strtotime(date('Y-m-d ') . $stop_time);
			if ($stop_time < $start_time) {
				$stop_time = $stop_time + 86400;
				$is_cross_day_1 = 1;
			}
				
			if ($time < $start_time) {
				$time = $start_time;
			} elseif ($start_time <= $time && $time <= $stop_time) {
	
			} else {
				$format_second_time = 0;
				if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
					$have_two_time = 0;
					$time = $start_time + 86400;
					$start_time2 = strtotime(date('Y-m-d ') . '00:00');
					$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
				} else {
					$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
					$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
					if ($stop_time2 < $start_time2) {
						$stop_time2 = $stop_time2 + 86400;
						$is_cross_day_2 = 1;
					}
	
					if ($time < $start_time2) {
						$time = $start_time2;
						$now_time_value = 2;
					} elseif ($start_time2 <= $time && $time <= $stop_time2) {
						$now_time_value = 2;
					} else {
						$time = $start_time + 86400;
					}
				}
			}
		}
		if ($format_second_time) {//是否要格式化时间段二
			if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {
				$have_two_time = 0;
				$start_time2 = strtotime(date('Y-m-d ') . '00:00');
				$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
			} else {
				$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
				$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
				if ($stop_time2 < $start_time2) {
					$stop_time2 = $stop_time2 + 86400;
					$is_cross_day_2 = 1;
				}
			}
		}
	
		if ($have_two_time) {
			$this->assign(array('time_select_1' => date('H:i', $start_time) . '-' . date('H:i', $stop_time), 'time_select_2' => date('H:i', $start_time2) . '-' . date('H:i', $stop_time2)));
		}
		$this->assign('have_two_time', $have_two_time);
		$this->assign('arrive_date', date('Y-m-d', $time));
		$this->assign('arrive_time', date('H:i', $time));
		$this->assign('now_date', date('Y-m-d H:i', $time));
		$this->assign('now_time_value', $now_time_value);
	
	
	
		$date['minYear'] = date('Y', $time);
		$date['minMouth'] = date('n', $time) - 1;
		$date['minDay'] = date('j', $time);
	
	
	
		$date['minHour_today'] = date('G', $time);
		$date['minMinute_today'] = date('i', $time);
	
		$date['minHour_tomorrow'] = date('G', $start_time);
		$date['minMinute_tomorrow'] = date('i', $start_time);
	
		if ($time < $start_time2) {
			$date['minHour_today2'] = date('G', $start_time2);
			$date['minMinute_today2'] = date('i', $start_time2);
		} else {
			$date['minHour_today2'] = date('G', $time);
			$date['minMinute_today2'] = date('i', $time);
		}
		$date['minHour_tomorrow2'] = date('G', $start_time2);
		$date['minMinute_tomorrow2'] = date('i', $start_time2);
	
	
		$date['maxYear_today'] = date('Y', $stop_time);
		$date['maxMouth_today'] = date('n', $stop_time) - 1;
		$date['maxDay_today'] = date('j', $stop_time);
	
		$date['maxYear_today2'] = date('Y', $stop_time2);
		$date['maxMouth_today2'] = date('n', $stop_time2) - 1;
		$date['maxDay_today2'] = date('j', $stop_time2);
	
	
		$time = strtotime("+{$advance_day} day") + $return['store']['send_time'] * 60;
		$date['maxYear'] = date('Y', $time);
		$date['maxMouth'] = date('n', $time) - 1;
		$date['maxDay'] = date('j', $time);
	
	
	
	
		$date['maxHour'] = date('G', $stop_time);
		$date['maxMinute'] = date('i', $stop_time);
	
		$date['maxHour2'] = date('G', $stop_time2);
		$date['maxMinute2'] = date('i', $stop_time2);
	
		$date['today'] = date('Y-m-d');
	
		$date['is_cross_day_1'] = $is_cross_day_1;
		$date['is_cross_day_2'] = $is_cross_day_2;
		// 		echo "<Pre/>";
		// 		print_r($date);die;
		$this->assign($date);
	
		if ($return['store']['basic_price'] <= $basic_price) {
			$address_id = isset($_GET['adress_id']) ? intval($_GET['adress_id']) : cookie('userLocationId');
			$user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
			$this->assign('user_adress', $user_adress);
		} else {
			if (in_array($return['delivery_type'], array(2, 3, 4))) {
				$return['delivery_type'] = 2;
			} else {
				$this->error_tips('没有达到起送价，不予以配送');
			}
		}
	
		
	
		//计算配送费
		if ($user_adress) {
			$distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
			$distance = $distance / 1000;
			
			//获取配送费用
			$deliveryCfg = [];
			$deliverys = D("Config")->get_gid_config(20);
			foreach($deliverys as $r){
				$deliveryCfg[$r['name']] = $r['value'];
			}
			
			if($distance < 5) {
				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_1'], 2);
			}elseif($distance > 5 && $distance <= 8) {
				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_2'], 2);
			}elseif($distance > 8 && $distance <= 10) {
				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_3'], 2);
			}elseif($distance > 10 && $distance <= 15) {
				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_4'], 2);
			}elseif($distance > 15 && $distance <= 20) {
				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_5'], 2);
			}elseif($distance > 20) {
				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_more'], 2);
			}
			$return['delivery_fee2'] = $return['delivery_fee'];

			/*$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
			$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
			$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
			$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
				
			$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
			$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
			$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
			$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;*/
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
// 		echo '<pre/>';

		//计算总价
		$sum = $return['delivery_fee'] + $return['price'] + $return['packing_charge'];
		$return['totalPrice'] = $sum * 1.05;
 		//print_r($return);die;
		$this->assign($return);
		$this->assign('pick_addr_id', $pick_addr_id);
		$this->assign('pick_address', $pick_address);
	
		$now_store_category_relation = M('Shop_category_relation')->where(array('store_id'=>$return['store_id']))->find();
		$now_store_category = M('Shop_category')->where(array('cat_id'=>$now_store_category_relation['cat_id']))->find();
		if($now_store_category['cue_field']){
			$this->assign('cue_field',unserialize($now_store_category['cue_field']));
		}
	
		$this->display();
	
	
	}
	
	private function check_cart()
	{
		$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
		$store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
		if ($store['have_shop'] == 0 || $store['status'] != 1) {
			return array('error_code' => true, 'msg' => '商家已经关闭了该业务,不能下单了!');
		}
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

        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
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
                if ($store['open_4'] != '00:00:00' || $store['close_4'] != '00:00:00') {
                    if ($store['open_4'] < $now_time && $now_time < $store['close_4']){
                        $is_open = 1;
                    }
                }
                if ($store['open_5'] != '00:00:00' || $store['close_5'] != '00:00:00') {
                    if ($store['open_5'] < $now_time && $now_time < $store['close_5']){
                        $is_open = 1;
                    }
                }
                if ($store['open_6'] != '00:00:00' || $store['close_6'] != '00:00:00') {
                    if ($store['open_6'] < $now_time && $now_time < $store['close_6']){
                        $is_open = 1;
                    }
                }
                break;
            case 3 ://周三
                if ($store['open_7'] != '00:00:00' || $store['close_7'] != '00:00:00') {
                    if ($store['open_7'] < $now_time && $now_time < $store['close_7']){
                        $is_open = 1;
                    }
                }
                if ($store['open_8'] != '00:00:00' || $store['close_8'] != '00:00:00') {
                    if ($store['open_8'] < $now_time && $now_time < $store['close_8']){
                        $is_open = 1;
                    }
                }
                if ($store['open_9'] != '00:00:00' || $store['close_9'] != '00:00:00') {
                    if ($store['open_9'] < $now_time && $now_time < $store['close_9']){
                        $is_open = 1;
                    }
                }
                break;
            case 4 :
                if ($store['open_10'] != '00:00:00' || $store['close_10'] != '00:00:00') {
                    if ($store['open_10'] < $now_time && $now_time < $store['close_10']){
                        $is_open = 1;
                    }
                }
                if ($store['open_11'] != '00:00:00' || $store['close_11'] != '00:00:00') {
                    if ($store['open_11'] < $now_time && $now_time < $store['close_11']){
                        $is_open = 1;
                    }
                }
                if ($store['open_12'] != '00:00:00' || $store['close_12'] != '00:00:00') {
                    if ($store['open_12'] < $now_time && $now_time < $store['close_12']){
                        $is_open = 1;
                    }
                }
                break;
            case 5 :
                if ($store['open_13'] != '00:00:00' || $store['close_13'] != '00:00:00') {
                    if ($store['open_13'] < $now_time && $now_time < $store['close_13']){
                        $is_open = 1;
                    }
                }
                if ($store['open_14'] != '00:00:00' || $store['close_14'] != '00:00:00') {
                    if ($store['open_14'] < $now_time && $now_time < $store['close_14']){
                        $is_open = 1;
                    }
                }
                if ($store['open_15'] != '00:00:00' || $store['close_15'] != '00:00:00') {
                    if ($store['open_15'] < $now_time && $now_time < $store['close_15']){
                        $is_open = 1;
                    }
                }
                break;
            case 6 :
                if ($store['open_16'] != '00:00:00' || $store['close_16'] != '00:00:00') {
                    if ($store['open_16'] < $now_time && $now_time < $store['close_16']){
                        $is_open = 1;
                    }
                }
                if ($store['open_17'] != '00:00:00' || $store['close_17'] != '00:00:00') {
                    if ($store['open_17'] < $now_time && $now_time < $store['close_17']){
                        $is_open = 1;
                    }
                }
                if ($store['open_18'] != '00:00:00' || $store['close_18'] != '00:00:00') {
                    if ($store['open_18'] < $now_time && $now_time < $store['close_18']){
                        $is_open = 1;
                    }
                }
                break;
            case 0 :
                if ($store['open_19'] != '00:00:00' || $store['close_19'] != '00:00:00') {
                    if ($store['open_19'] < $now_time && $now_time < $store['close_19']){
                        $is_open = 1;
                    }
                }
                if ($store['open_20'] != '00:00:00' || $store['close_20'] != '00:00:00') {
                    if ($store['open_20'] < $now_time && $now_time < $store['close_20']){
                        $is_open = 1;
                    }
                }
                if ($store['open_21'] != '00:00:00' || $store['close_21'] != '00:00:00') {
                    if ($store['open_21'] < $now_time && $now_time < $store['close_21']){
                        $is_open = 1;
                    }
                }
                break;
            default :
                $is_open = 0;
        }
        //end  @wangchuanyuan





		if ($is_open == 0) {
			return array('error_code' => true, 'msg' => '店铺休息中');
		}
		//资质认证
		if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
			return array('error_code' => true, 'msg' => '您查看的'.$this->config['shop_alias_name'].'没有通过资质审核！');
		}
	
		$store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store) || empty($store_shop)) return array('error_code' => true, 'msg' => '');
		$store = array_merge($store, $store_shop);
		$mer_id = $store['mer_id'];
		$this->assign('store', $store);
		$productCart = isset($_POST['foodshop_cart']) && $_POST['foodshop_cart'] ? htmlspecialchars_decode($_POST['foodshop_cart']) : $_SESSION['foodshop_cart'];
		$_SESSION['foodshop_cart'] = $productCart;
		$productCart = json_decode($productCart, true);
		if (empty($productCart)) return array('error_code' => true, 'msg' => '您的购物车是空的');
	
	
	
		$goods = array();
		$price = 0;//原始总价
		$total = 0;//商品总数
		$extra_price = 0;//额外价格的总价
		$packing_charge = 0;//打包费
		//店铺优惠条件
		$sorts_discout = D('Shop_goods_sort')->get_sorts_discount($store_id);
		$store_discount_money = 0;//店铺折扣后的总价
		foreach ($productCart as $row) {
			$goods_id = $row['productId'];
			$num = $row['count'];
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
				$this->error_tips($t_return['msg']);
				exit();
			} elseif ($t_return['status'] == 2) {
				$this->error_tips($t_return['msg']);
				exit();
			}
			$total += $num;
			$price += $t_return['price'] * $num;
			$extra_price +=$row['productExtraPrice']*$num;
			$packing_charge += $t_return['packing_charge'] * $num;

				$t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;
// 				$store_discount_money += $num * round($t_return['price'] * $t_discount / 100, 2);
				$discount_type = 0;
				$discount_rate = 0;
				if ($t_discount < 100) {
				    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
				        $discount_type = 2;
				    } else {
				        $discount_type = 1;
				    }
				    $discount_rate = $t_discount;
				}
				// 				$store_discount_money += $num * round($t_return['price'] * $t_discount / 100, 2);
				
				
				$this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的折扣总价
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
				}
				
				$store_discount_money += $this_goods_total_price;
				
				$str = '';
				$str_s && $str = implode(',', $str_s);
				$str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
				$goods[] = array(
				    'name' => $row['productName'], 
				    'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
				    'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
				    'discount_rate' => $discount_rate,//折扣率
				    'num' => $num, 
				    'goods_id' => $goods_id, 
				    'old_price' => floatval($t_return['old_price']),//商品原始价
				    'price' => floatval($t_return['price']),//是秒杀的时候是秒杀价，不是的时候是原始价
				    'discount_price' => floatval($only_discount_price),//折扣价
				    'cost_price' => floatval($t_return['cost_price']), 
				    'number' => $t_return['number'], 
				    'image' => $t_return['image'],
				    'sort_id' => $t_return['sort_id'],
				    'packing_charge' => $t_return['packing_charge'], 
				    'unit' => $t_return['unit'], 
				    'str' => $str, 
				    'spec_id' => $spec_str,
				    'extra_price'=>$row['productExtraPrice']
				);
		}
	
		$minus_price = 0;
		//会员等级优惠  外卖费不参加优惠
		$vip_discount_money = round($store_discount_money, 2);
		
		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discount_list = null;
	
		//优惠
		$sys_first_reduce = 0;//平台首单优惠
		$sto_first_reduce = 0;//店铺首单优惠
		$sys_full_reduce = 0;//平台满减
		$sto_full_reduce = 0;//店铺满减
		$shop_order_obj = D("Shop_order");
	
		$sys_count = $shop_order_obj->where(array('uid' => $this->user_session['uid']))->count();
		if (empty($sys_count)) {//平台首单优惠
			if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money)) {
				$d_tmp['discount_type'] = 1;//平台首单
				$d_tmp['money'] = $d_tmp['full_money'];
				$d_tmp['minus'] = $d_tmp['reduce_money'];
				$discount_list['system_newuser'] = $d_tmp;
// 				$discount_list[] = $d_tmp;
				$sys_first_reduce = $d_tmp['reduce_money'];
			}
		}
	
	
		if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money)) {
			$d_tmp['discount_type'] = 2;//平台满减
			$d_tmp['money'] = $d_tmp['full_money'];
			$d_tmp['minus'] = $d_tmp['reduce_money'];
			$discount_list['system_minus'] = $d_tmp;
// 			$discount_list[] = $d_tmp;
			$sys_full_reduce = $d_tmp['reduce_money'];
		}
	
		$sto_count = $shop_order_obj->where(array('uid' => $this->user_session['uid'], 'store_id' => $store_id))->count();
		$sto_first_reduce = 0;
		if (empty($sto_count)) {
			if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money, $store_id)) {
				$d_tmp['discount_type'] = 3;//店铺首单
				$d_tmp['money'] = $d_tmp['full_money'];
				$d_tmp['minus'] = $d_tmp['reduce_money'];
				$discount_list['newuser'] = $d_tmp;
// 				$discount_list[] = $d_tmp;
				$sto_first_reduce = $d_tmp['reduce_money'];
			}
		}
		$sto_full_reduce = 0;
		if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money, $store_id)) {
			$d_tmp['discount_type'] = 4;//店铺满减
			$d_tmp['money'] = $d_tmp['full_money'];
			$d_tmp['minus'] = $d_tmp['reduce_money'];
			$discount_list['minus'] = $d_tmp;
// 			$discount_list[] = $d_tmp;
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
// 			redirect(U('Shop/index') . '#shop-' . $store_id);
			return array('error_code' => true, 'msg' => '购物车是空的');
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
			$data['extra_price'] = $extra_price;

			$data['delivery_type'] = $store_shop['deliver_type'];
				
			$data['sys_first_reduce'] = $sys_first_reduce;//平台新单优惠的金额
			$data['sys_full_reduce'] = $sys_full_reduce;//平台满减优惠的金额
			$data['sto_first_reduce'] = $sto_first_reduce;//店铺新单优惠的金额
			$data['sto_full_reduce'] = $sto_full_reduce;//店铺满减优惠的金额
				
			$data['store_discount_money'] = $store_discount_money;//店铺折扣后的总价
			$data['vip_discount_money'] = $vip_discount_money;//VIP折扣后的总价
			$data['packing_charge'] = $packing_charge;//总的打包费
				
			$data['delivery_fee'] = $delivery_fee;//起步配送费
			$data['basic_distance'] = $basic_distance;//起步距离
			$data['per_km_price'] = $per_km_price;//超出起步距离部分的距离每公里的单价
			$data['delivery_fee_reduce'] = $delivery_fee_reduce;//配送费减免的金额
				
			$data['delivery_fee2'] = $delivery_fee2;//起步配送费
			$data['basic_distance2'] = $basic_distance2;//起步距离
			$data['per_km_price2'] = $per_km_price2;//超出起步距离部分的距离每公里的单价
				
			return $data;
		}
	}
	
	private function get_reduce($discounts, $type, $price, $store_id = 0)
	{
		$reduce_money = 0;
		if (isset($discounts[$store_id])) {
			foreach ($discounts[$store_id] as $row) {
				if ($row['type'] == $type) {
					if ($price >= $row['full_money']) {
						$reduce_money = max($reduce_money, $row['reduce_money']);
					}
				}
			}
		}
		return $reduce_money;
	}
	
	public function saveorder()
	{
    	//判断登录
    	if(empty($this->user_session)){
    		$this->assign('jumpUrl',U('Index/Login/index'));
    		$this->error('请先登录！');
    	}
    	//---------------edit 2017-3-7---------------
    	$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
    	$cookieData = isset($_POST['foodshop_cart']) && $_POST['foodshop_cart'] ? htmlspecialchars_decode($_POST['foodshop_cart']) : $_SESSION['foodshop_cart'];
    	$_SESSION['foodshop_cart'] = $cookieData;
    	$cookieData = json_decode($cookieData, true);
    	if (empty($cookieData)) return array('error_code' => true, 'msg' => '您的购物车是空的');
    	$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
        //----------------edit line---------------------------
        //$return = $this->check_cart();
        //---------------edit 2017-3-7---------------
    	if ($return['error_code']) exit(json_encode($return));
    	
    	if (IS_POST) {
    		$pick_address = isset($_POST['pick_address']) ? htmlspecialchars($_POST['pick_address']) : '';
    		$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
    		$pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : '';
    		$pick_id = substr($pick_id, 1);
    		$deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
    		$arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
    		$note = isset($_POST['desc']) ? htmlspecialchars($_POST['desc']) : '';
    		if ($deliver_type != 1) {
    			if ($user_address = D('User_adress')->field(true)->where(array('adress_id' => $address_id, 'uid' => $this->user_session['uid']))->find()) {
    				if ($user_address['longitude'] != 0 && $user_address['latitude'] != 0) {
					    if ($return['store']['delivery_range_type'] == 0) {
							$distance = getDistance($user_address['latitude'], $user_address['longitude'], $return['store']['lat'], $return['store']['long']);
							$delivery_radius = $return['store']['delivery_radius'] * 1000;
							if ($distance > $delivery_radius && $return['delivery_type'] != 5) {
								//$this->error_tips('您到本店的距离是' . $distance . '米,超过了' . $delivery_radius . '米的配送范围');
							}
					    } else {
					        if ($return['store']['delivery_range_polygon']) {
						        if (!isPtInPoly($user_address['longitude'], $user_address['latitude'], $return['store']['delivery_range_polygon'])) {
						            $this->error_tips('您的地址不在本店指定的配送区域');
						        }
					        } else {
					            $this->error_tips('您的地址不在本店指定的配送区域');
					        }
					    }
					}
    			} else {
    				exit(json_encode(array('error_code' => true, 'msg' => '不存在的地址')));
    			}
    		}
    		$now_time = time();
			$orderid = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];
			$order_data = array();
			$order_data['real_orderid'] = $orderid;
			$order_data['mer_id'] = $return['mer_id'];
    		$order_data['store_id'] = $return['store_id'];
    		$order_data['uid'] = $this->user_session['uid'];
    	
    		$order_data['desc'] = $note;
    		$order_data['create_time'] = $now_time;
    		$order_data['last_time'] = $now_time;

// 			$order_data['is_mobile_pay'] = 1;
    		$order_data['num'] = $return['total'];
			$order_data['packing_charge'] = $return['packing_charge'];//打包费
    		$order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
    		$order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
// 			$orderid  = date('ymdhis').substr(microtime(),2,8-strlen($this->user_session['uid'])).$this->user_session['uid'];
// 			$order_data['real_orderid'] = $orderid;
    		$order_data['no_bill_money'] = 0;//无需跟平台对账的金额
    		if ($deliver_type == 1) {//自提
				$order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
    			$delivery_fee = $order_data['freight_charge'] = 0;//运费
				$order_data['username'] = isset($this->user_session['nickname']) && $this->user_session['nickname'] ? $this->user_session['nickname'] : '';
				$order_data['userphone'] = isset($this->user_session['phone']) && $this->user_session['phone'] ? $this->user_session['phone'] : '';
    			$order_data['address'] = $pick_address;
    			$order_data['address_id'] = 0;
				$order_data['pick_id'] = $pick_id;
				$order_data['status'] = 7;
				$order_data['expect_use_time'] = time() + $return['store']['send_time'] * 60;//客户期望使用时间
    		} else {//配送
    			$order_data['username'] = $user_address['name'];
    			$order_data['userphone'] = $user_address['phone'];
    			$order_data['address'] = $user_address['adress'] . $user_address['detail'];
    			$order_data['address_id'] = $address_id;
				$order_data['lat'] = $user_address['latitude'];
				$order_data['lng'] = $user_address['longitude'];
// 				if ($arrive_date == 0) {
// 					$arrive_date = date('Y-m-d');
// 				}
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
				
				if ($arrive_time == 0) {
// 					if ($arrive_date != date('Y-m-d')) {
// 						$arrive_time = strtotime($arrive_date . $start_time);
// 					} else {
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
// 					}
				} else {
					$arrive_time = strtotime($arrive_time);
					if ($start_time == $stop_time && $start_time == '00:00:00') {
							
					} else {
						$_start_time = strtotime(date('Y-m-d ') . $start_time);
						$_stop_time = strtotime(date('Y-m-d ') . $stop_time);
						if ($_start_time > $_stop_time) {
							$_stop_time = $_stop_time + 86400;
						}
						
						if (!($_start_time <= $arrive_time && $arrive_time <= $_stop_time)) {
							$_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
							$_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
							if ($_start_time2 > $_stop_time2) {
								$_stop_time2 = $_stop_time2 + 86400;
							}
							if (!($_start_time2 <= $arrive_time && $arrive_time <= $_stop_time2)) {
								exit(json_encode(array('error_code' => true, 'msg' => '您选择的时间不在配送时间段内')));
							}
						}
					}
				}
				
				$order_data['expect_use_time'] = $arrive_time ? $arrive_time : time() + $return['store']['send_time'] * 60;//客户期望使用时间
				
				
				//计算配送费
				$distance = $distance / 1000;
				/*
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
						exit(json_encode(array('error_code' => true, 'msg' => '您选择的时间不在配送时间段内！')));
					}
				}*/

				//获取配送费用
				$deliveryCfg = [];
				$deliverys = D("Config")->get_gid_config(20);
				foreach($deliverys as $r){
					$deliveryCfg[$r['name']] = $r['value'];
				}
				if($distance < 5) {
					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_1'], 2);
				}elseif($distance > 5 && $distance <= 8) {
					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_2'], 2);
				}elseif($distance > 8 && $distance <= 10) {
					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_3'], 2);
				}elseif($distance > 10 && $distance <= 15) {
					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_4'], 2);
				}elseif($distance > 15 && $distance <= 20) {
					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_5'], 2);
				}elseif($distance > 20) {
					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_more'], 2);
				}
				$return['delivery_fee2'] = $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];
				
				if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {//平台配送
					$order_data['is_pick_in_store'] = 0;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
					$order_data['no_bill_money'] = $delivery_fee;
				} elseif ($return['delivery_type'] == 1 || $return['delivery_type'] == 4)  {
					$order_data['is_pick_in_store'] = 1;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
				} else {
					$order_data['is_pick_in_store'] = 3;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
    			}
    		}

    		$order_data['order_from'] = 5;//订单来源:0：wap快店，1：wap商城，2：Android，3：ios,4:小程序,5：pc快店
    		$order_data['goods_price'] = $return['price'];//商品的价格
    		$order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
    		$order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
			$order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
			$order_data['price'] = ($order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce']) * 1.05;//实际要支付的价格
    		$order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情
//     		if ($return['price'] - $return['store_discount_money'] > 0) {
//     			$order_data['discount_detail'] = '店铺折扣优惠：' . floatval($return['price'] - $return['store_discount_money']);
//     		}
//     		if ($return['store_discount_money'] - $return['vip_discount_money'] > 0) {
//     			$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']) : 'VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']);
//     		}
//     		if ($return['sys_first_reduce']> 0) {
//     			$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台首单减：' . $return['sys_first_reduce'] : '平台首单减：' . $return['sys_first_reduce'];
//     		}
//     		if ($return['sys_full_reduce'] > 0) {
//     			$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台满减：' . $return['sys_full_reduce'] : '平台满减：' . $return['sys_full_reduce'];
//     		}
//     		if ($return['sto_first_reduce']> 0) {
//     			$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺首单减：' . $return['sto_first_reduce'] : '店铺首单减：' . $return['sto_first_reduce'];
//     		}
//     		if ($return['sto_full_reduce'] > 0) {
//     			$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺满减：' . $return['sto_full_reduce'] : '店铺满减：' . $return['sto_full_reduce'];
//     		}
			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
    			
    		if ($order_id = D('Shop_order')->add($order_data)) {
    			
    			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
				if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
					D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
					//D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
				}
				$_SESSION['foodshop_cart'] = null;
    			$detail_obj = D('Shop_order_detail');
				$goods_obj = D("Shop_goods");
				foreach ($return['goods'] as $grow) {
					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time(),'extra_price'=>$grow['extra_price']);
					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
					$detail_data['discount_type'] = intval($grow['discount_type']);
					$detail_data['discount_rate'] = $grow['discount_rate'];
					$detail_data['sort_id'] = $grow['sort_id'];
					$detail_data['old_price'] = floatval($grow['old_price']);
					$detail_data['discount_price'] = floatval($grow['discount_price']);
					D('Shop_order_detail')->add($detail_data);
					$order_data['reduce_stock_type'] && $goods_obj->update_stock($grow);//修改库存
    			}
    			if ($this->user_session['openid']) {
    				$keyword2 = '';
    				$pre = '';
    				foreach ($return['goods'] as $menu) {
    					$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
    					$pre = '\n\t\t\t';
    				}
					$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order_id . '&mer_id=' . $order_data['mer_id'] . '&store_id=' . $order_data['store_id'];
    				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
    				$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['shop_alias_name'].'下单成功，感谢您的使用！'));
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
    				$sms_data['uid'] = $this->user_session['uid'];
    				$sms_data['mobile'] = $order_data['userphone'];
    				$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您' . date("Y-m-d H:i:s") . '在 ' . $return['store']['name'] . ' 中下了一个订单，订单号：' . $orderid;
					//Sms::sendSms2($sms_data);
    			}
    			if ($this->config['sms_shop_place_order'] == 2 || $this->config['sms_shop_place_order'] == 3) {
    				/*$sms_data['uid'] = 0;
    				$sms_data['mobile'] = $return['store']['phone'];
    				$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客 ' . $order_data['username'] . ' 在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $orderid . '请您注意查看并处理!';*/
					//Sms::sendSms($sms_data);
					//查询所有的店员
					$rs = D("Merchant_store_staff")->field(true)->where(array("store_id" => $return['store_id'], 'work_status' => 0))->select();
					foreach($rs as $r){
						if(empty($r['tel'])){
							continue;
						}
						$sms_data = [
							'mobile' => $r['tel'],
							'tplid' => 87321,
							'params' => [
								$order_data['username'],
								date("Y-m-d H:i:s"),
								$orderid
							],
							'content' => '顾客 ' . $order_data['username'] . ' 在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $orderid . '请您注意查看并处理!'
						];
                                                
                                                //客户支付成功后，向店家发送消息，创建订单消息的延时处理任务，若三分钟后店家没有接单处理，再发消息 ydhl-llx@20171213
                                                //require_once APP_PATH . 'Lib/ORG/crontab/creat_file.php';
                                                //creat_check_file_when_online_order($order_id, $sms_data['mobile'], $sms_data['content']);
						Sms::sendSms2($sms_data);
					}
				}
				
    			cookie('foodshop_cart_' . $return['store_id'], null);
    			exit(json_encode(array('error_code' => 0, 'msg' => '', 'data' => U('Index/Pay/check', array('order_id' => $order_id,'type'=>'shop')))));
    		} else {
    			exit(json_encode(array('error_code' => 0, 'msg' => '订单保存失败')));
    		}
    	} else {
    		exit(json_encode(array('error_code' => 0, 'msg' => '不合法的提交')));
    	}
	}
	
	public function ajax_prices()
	{
		$address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
		$type = isset($_POST['type']) ? intval($_POST['type']) : 0;
		
    	$store_id = isset($_REQUEST['store_id']) ? intval($_REQUEST['store_id']) : 0;
    	$cookieData = isset($_POST['foodshop_cart']) && $_POST['foodshop_cart'] ? htmlspecialchars_decode($_POST['foodshop_cart']) : $_SESSION['foodshop_cart'];
    	$_SESSION['foodshop_cart'] = $cookieData;
    	$cookieData = json_decode($cookieData, true);
    	if (empty($cookieData)) exit(json_encode(array('error_code' => true, 'msg' => '您的购物车是空的！')));
    	$return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
// 		$return = $this->check_cart();
		if ($return['error_code']) exit(json_encode($return));
		$arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
		if (empty($this->user_session)) {
			exit(json_encode(array('error_code' => true, 'msg' => '请先登录！')));
		}

		if ($type == 1) {
			$price = $return['vip_discount_money'] + $return['packing_charge'] - round(($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce']), 2);//实际要支付的价格
			$data = array('error_code' => false, 'price' => $price * 1.05, 'delivery_fee' => 0, 'delivery_fee1' => 0, 'delivery_fee2' => 0);
			exit(json_encode($data));
		}
		
		$user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
		if (empty($user_adress)) exit(json_encode(array('error_code' => true, 'msg' => 'Please Enter Address')));
		
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
		$arrive_time = $arrive_time ? strtotime($arrive_time) : 0;
		$arrive_time = $arrive_time ? $arrive_time : time() + $return['store']['send_time'] * 60;//客户期望使用时间
		$expect_use_time_temp = strtotime(date('Y-m-d ') . date('H:i:s', $arrive_time));
		

		//获取配送费用
		$deliveryCfg = [];
		$deliverys = D("Config")->get_gid_config(20);
		foreach($deliverys as $r){
			$deliveryCfg[$r['name']] = $r['value'];
		}

		//计算配送费
		$distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
		$distance = $distance / 1000;
		
		if($distance < 5) {
			$delivery_fee = floatval($deliveryCfg['delivery_distance_1']);
		}elseif($distance > 5 && $distance <= 8) {
			$delivery_fee = floatval($deliveryCfg['delivery_distance_2']);
		}elseif($distance > 8 && $distance <= 10) {
			$delivery_fee = floatval($deliveryCfg['delivery_distance_3']);
		}elseif($distance > 10 && $distance <= 15) {
			$delivery_fee = floatval($deliveryCfg['delivery_distance_4']);
		}elseif($distance > 15 && $distance <= 20) {
			$delivery_fee = floatval($deliveryCfg['delivery_distance_5']);
		}elseif($distance > 20) {
			$delivery_fee = floatval($deliveryCfg['delivery_distance_more']);
		}
		$delivery_fee1 = $delivery_fee;

		/*
		var_dump($distance);exit;
// 		echo $return['delivery_fee_reduce'];die;
		$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
		$return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
		$return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
		$return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
		
		$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
		$return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
		$return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
		$return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
				
		if ($if_start_time <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time) {//时间段一
			$delivery_fee  = $return['delivery_fee'];//运费
		} elseif ($if_start_time2 <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time2) {//时间段二
			$delivery_fee = $return['delivery_fee2'];//运费
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '您选择的时间不在配送时间段内！')));
		}*/

		$price = round(($return['vip_discount_money'] + $return['packing_charge'] + $delivery_fee), 2) - round(($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce']), 2);//实际要支付的价格
		
		$price = $price * 1.05;

		
		if($this->config['open_extra_price']){
			$extra_price = $return['extra_price'];
		}else{
			$extra_price = 0;
		}
		
		$data = array('error_code' => false, 'price' => $price, 'delivery_fee' => $delivery_fee, 'delivery_fee1' => $delivery_fee1, 'delivery_fee2' => $delivery_fee1,'extra_price'=>$extra_price);
		exit(json_encode($data));
	}
}
