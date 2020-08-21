<?php

//团购 AJAX服务
class GroupserviceAction extends BaseAction{
	public function indexRecommendList(){
		$this->header_json();
		$page	=	$_GET['page']?$_GET['page']:0;
		$page_count	=	10;

		$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);

		$content_type = $this->config['guess_content_type'];
		$limit = $this->config['guess_num'];
		$page_all = 0;
		$page_max = floor($limit/$page_count)+($limit%$page_count>0?1:0);


		if($content_type=='group'){
			$new_group_list = D('Group')->get_group_list('index_sort',$page.','.$page_count,true);
			$page_all = $page_max;
			//判断是否微信浏览器，
			$group_list = array();
			foreach($new_group_list as $storeGroupValue){
				if($new_group_list && $user_long_lat) {
					$group_store_database = D('Group_store');
					$rangeSort = array();
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if ($tmpStoreList) {
						foreach ($tmpStoreList as &$tmpStore) {
							$tmpStore['Srange'] = getDistance($user_long_lat['lat'], $user_long_lat['long'], $tmpStore['lat'], $tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'], false);
							$rangeSort[] = $tmpStore['Srange'];
						}
						array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
						//$tmp['store_list'] = $tmpStoreList;
						$tmp['range'] = $tmpStoreList[0]['range'];
						$tmp['Srange'] = $tmpStoreList[0]['Srange'];
					}
				}else{
					$tmp['range'] = '';
				}

				$tmp['group_id'] = $storeGroupValue['group_id'];
				$tmp['list_pic'] = $storeGroupValue['list_pic'];
				$tmp['group_name'] = $storeGroupValue['group_name'];
				$tmp['price'] = $storeGroupValue['price'];
				$tmp['old_price'] = $storeGroupValue['old_price'];
				$tmp['wx_cheap'] = $storeGroupValue['wx_cheap'];
				$tmp['pin_num'] = $storeGroupValue['pin_num'];
				$tmp['merchant_name'] = $storeGroupValue['merchant_name'];
				$tmp['s_name'] = $storeGroupValue['s_name'];
				$tmp['tuan_type'] = $storeGroupValue['tuan_type'];
				$tmp['sale_txt'] = $storeGroupValue['sale_txt'];
				$tmp['url'] = $storeGroupValue['url'];
				$tmp['extra_pay_price'] = $storeGroupValue['extra_pay_price'];
				$group_list[] = $tmp;
			}
			$new_group_list = sortArrayAsc($group_list,'Srange');
		}elseif($content_type=='shop'){
			$key = '';
			$sort = $_GET['sort'] ? $_GET['sort'] : 0;
			if($sort == 0) $order = 'juli';
			else $order = 'create_time';

			$deliver_type =  'all';
			// $d_value=	$limit-$page;
			// if($d_value < $page_count){
			// $page_count	= $d_value;
			// }
			$lat = isset($user_long_lat['lat']) ? $user_long_lat['lat'] : 0;
			$long = isset($user_long_lat['long']) ? $user_long_lat['long'] : 0;
			$cat_id = 0;
			$cat_fid = 0;

            //$city_id = D('Store')->geocoderGoogle($lat,$long);
            $city_id = $city_id ? $city_id : 105;

			if($_GET['lat'] != 'null' && $_GET['lat'] != 'undefined' && $_GET['long'] != 'null' && $_GET['long'] != 'undefined'){
				$lat = $_GET['lat'];
				$long = $_GET['long'];
			}

			$where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
			$key && $where['key'] = $key;

			$lists = D('Merchant_store_shop')->get_list_by_option($where,3);
			$return = array();
			$now_time = date('H:i:s');
			$n= 1;

			//garfunkel获取减免配送费的活动
            $eventList = D('New_event')->getEventList(1,3,$city_id);
            $delivery_coupon = "";
            if(count($eventList) > 0) {
                foreach ($eventList as $event) {
                    $delivery_coupon = D('New_event_coupon')->where(array('event_id' => $event['id']))->find();
                }
            }

			foreach ($lists['shop_list'] as $row) {
//				if($n>$page_count ||$page>$page_max || ($page == $page_max && $n>$limit%$page_count&&$limit%$page_count!=0) )
//					break;
//				$n++;
				$temp = array();
				$temp['id'] = $row['store_id'];
				//modify garfunkel 判断语言
				$temp['name'] = lang_substr($row['name'],C('DEFAULT_LANG'));
				$temp['store_theme'] = $row['store_theme'];
				$temp['isverify'] = $row['isverify'];
				$temp['juli_wx'] = $row['juli'];
				$temp['range'] = $row['range'];
				$temp['image'] = $this->config['site_url'].'/index.php?c=Image&a=thumb&width=180&height=120&url='.urlencode($row['image']);
				$temp['image_list'] = $row['image_list'];
                $temp['image_count'] = $row['image_count'];
				$temp['star'] = $row['score_mean'];
				$temp['month_sale_count'] = $row['sale_count'];
                $temp['merchant_store_month_sale_count'] = $row['merchant_store_month_sale_count'];//月售量
				$temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
				$temp['delivery'] = $temp['delivery'] ? true : false;
				$temp['delivery_time'] = $row['send_time'];//配送时长
				$temp['delivery_price'] = floatval($row['basic_price']);//起送价
				if($lat != 0 && $long != 0){
                    $temp['delivery_money'] = getDeliveryFee($row['lat'],$row['long'],$lat,$long,$row['city_id']);
				}else{
                    $temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
                }
				//modify garfunkel
                		$temp['pack_alias'] = $row['pack_alias'];
                		$temp['pack_fee'] = $row['pack_fee'];
                		//
				$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
                $temp['is_close'] = 1;

                $keywords = D('Keywords')->where(array('third_type' => 'Merchant_store', 'third_id' => $row['store_id']))->select();
                $str = "";
                foreach ($keywords as $key) {
                    $str .= $key['keyword'] . " ";
                }
                $temp['keywords'] = $str;

//				if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
//					$temp['time'] = '24小时营业';
//					$temp['is_close'] = 0;
//				} else {
//					$temp['time'] = $row['open_1'] . '~' . $row['close_1'];
//					if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
//						$temp['is_close'] = 0;
//					}
//					if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
//						$temp['time'] .= ',' . $row['open_2'] . '~' . $row['close_2'];
//						if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
//							$temp['is_close'] = 0;
//						}
//					}
//					if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
//						$temp['time'] .= ',' . $row['open_3'] . '~' . $row['close_3'];
//						if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
//							$temp['is_close'] = 0;
//						}
//					}
//				}

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
                        $row['time'] = $row['open_1']. '~' . $row['close_1'];
                        $row['time'] .= ';' . $row['open_2']. '~' . $row['close_2'];
                        $row['time'] .= ';' . $row['open_3']. '~' . $row['close_3'];
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
                        $row['time'] = $row['open_4'] . '~' . $row['close_4'];
                        $row['time'] .= ';' . $row['open_5'] . '~' . $row['close_5'];
                        $row['time'] .= ';' . $row['open_6'] . '~' . $row['close_6'];
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
                        $row['time'] = $row['open_7'] . '~' . $row['close_7'];
                        $row['time'] .= ';' . $row['open_8'] . '~' . $row['close_8'];
                        $row['time'] .= ';' . $row['open_9'] . '~' . $row['close_9'];

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
                        $row['time'] = $row['open_10'] . '~' . $row['close_10'];
                        $row['time'] .= ';' . $row['open_11'] . '~' . $row['close_11'];
                        $row['time'] .= ';' . $row['open_12'] . '~' . $row['close_12'];
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
                        $row['time'] = $row['open_13'] . '~' . $row['close_13'];
                        $row['time'] .= ';' . $row['open_14'] . '~' . $row['close_14'];
                        $row['time'] .= ';' . $row['open_15'] . '~' . $row['close_15'];
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
                        $row['time'] = $row['open_16'] . '~' . $row['close_16'];
                        $row['time'] .= ';' . $row['open_17'] . '~' . $row['close_17'];
                        $row['time'] .= ';' . $row['open_18'] . '~' . $row['close_18'];
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
                        $row['time'] .= $row['open_19'] . '~' . $row['close_19'];
                        $row['time'] .= ';' . $row['open_20'] . '~' . $row['close_20'];
                        $row['time'] .= ';' . $row['open_21'] . '~' . $row['close_21'];
                        break;
                    default :
                        $temp['is_close'] = 1;
                        $row['time']= '营业时间未知';
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

				$temp['free_delivery'] = 0;
				$temp['event'] = "";

				if($delivery_coupon != "" && $delivery_coupon['limit_day']*1000 >= $row['juli']){
					$temp['free_delivery'] = 1;
					$t_event['use_price'] = $delivery_coupon['use_price'];
					$t_event['discount'] = $delivery_coupon['discount'];
					$t_event['miles'] = $delivery_coupon['limit_day']*1000;
					$t_event['desc'] = $delivery_coupon['desc'];

					$temp['event'] = $t_event;

                    //$temp['delivery_money'] =  $temp['delivery_money'] - $delivery_coupon['discount'];
                    //$temp['delivery_money'] = $temp['delivery_money'] < 0 ? 0 : $temp['delivery_money'];
				}

                //garfunkel店铺满减活动
                $eventList = D('New_event')->getEventList(1,4);
                $store_coupon = "";
                if(count($eventList) > 0) {
                    $store_coupon = D('New_event_coupon')->where(array('event_id' => $eventList[0]['id'],'limit_day'=>$row['store_id']))->order('use_price asc')->select();
                    if(count($store_coupon) > 0){
                        if(C('DEFAULT_LANG') == 'zh-cn'){
                            $temp['merchant_reduce_list'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),'$'.$store_coupon[0]['use_price']).replace_lang_str(L('_MAN_REDUCE_NUM_'),'$'.$store_coupon[0]['discount']);
                        }else{
                            $temp['merchant_reduce_list'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),'$'.$store_coupon[0]['discount']).'$'.$store_coupon[0]['use_price'];
                        }
                    }
                }

				$return[] = $temp;
			}
			$new_group_list['store'] =$return;

            $category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>0,'city_id'=>$city_id))->order('cat_sort desc')->select();
            if(count($category) == 0){
                $category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>0,'city_id'=>0))->order('cat_sort desc')->select();
            }
            $nav_list = array();
            $categoryList = array();
            foreach ($category as $v){
                $nav['title'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
                $nav['image'] = 'https://www.tutti.app/static/images/category/'.$v['cat_url'].'.png?v=1.2.0';
                $nav['id'] = $v['cat_id'];
                $categoryList[] = $v['cat_id'];
                $nav_list[] = $nav;
            }
            $arr['nav'] = $nav_list;

            $sub_where['cat_fid'] = array('in',$categoryList);
            $sub_where['cat_status'] = 1;
            $subCategory = D('Shop_category')->where($sub_where)->order('cat_sort desc')->select();
            $sub_nav_list = array();
            foreach ($subCategory as $v){
                $sub_nav['title'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
                $cate_image_class = new category_image();
                if($v['cat_img'] != '') {
                    $sub_nav['image'] = $cate_image_class->get_image_by_path($v['cat_img']);
                }else{
                    $sub_nav['image'] = '';
                }
                $sub_nav['id'] = $v['cat_id'];
                $sub_nav['fid'] = $v['cat_fid'];

                $sub_nav_list[] = $sub_nav;
            }
            $new_group_list['sub_nav'] = $sub_nav_list;

            $recommend_list = $this->getRecommendList($city_id);

            $new_group_list['recommend'] = $recommend_list;

            $new_group_list['has_more'] = $lists['total'] > $page*5 ? true : false;
			//echo json_encode(array('store_list' => $return, 'has_more' => $lists['has_more'] ? true : false));
		}elseif($content_type=='meal'){
			$this->header_json();
// 		//判断分类信息
// 		$cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
			//判断地区信息
			$circle_id = 0;
			$area_id = 0;
			//判断排序信息
			$sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
			$cat_url = isset($_GET['cat_url']) ? intval($_GET['cat_url']) : -1;
//			$d_value=	$limit-$page;
//			if($d_value < $page_count){
//				$page_count	= $d_value;
//			}
			$params['area_id'] = $area_id;
			$params['circle_id'] = $circle_id;
			$params['sort'] = $sort_id;
			$params['lat'] = $user_long_lat['lat'];
			$params['long'] = $user_long_lat['long'];
			$params['cat_fid'] = 0;
			$params['cat_id'] = 0;
			$params['queue'] = -1;
			$params['page'] = $page;

			$return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($params,1);

			$page_all = $return['totalPage']>$page_max?$page_max:$return['totalPage'];
			$n=1;
			if(!empty($return)) {
				foreach ($return['store_list'] as $v) {
					if ($n > $page_count ||$page>$page_max || ($page == $page_max && $n>$limit%$page_count&&$limit%$page_count!=0) )
						break;
					$n++;
					if($v['discount_txt']['discount_type']==1){
						$v['discount_txt']  = $v['discount_txt']['discount_percent'].'折';
					}else if($v['discount_txt']['discount_type']==2) {
						$v['discount_txt']  = '每满'.$v['discount_txt']['condition_price'].'减'.$v['discount_txt']['minus_price'].'元';
					}else{
						$v['discount_txt']  = '';
					}

					$tmp_store_list[] = $v;
				}
				if(empty($return)) {
					foreach ($return['store_list'] as $v) {
						if ($n > $page_count)
							break;
						$n++;
						$tmp_store_list[] = $v;
					}
					$return['store_list'] = $tmp_store_list;
				}
			}

			$new_group_list =$return;

		}
//		if(!empty($new_group_list)){
//			$this->returnCode(0,array('type'=>$content_type,'content'=>$new_group_list,'page_all'=>$page_all));exit;
//		}else{
//			$this->returnCode(0,array('type'=>$content_type,'content'=>array(),'page_all'=>$page_all));exit;
//		}
		if(!empty($new_group_list)){
			echo json_encode($new_group_list);
		}else{
			echo '';
		}
	}
	//得到搜索的团购列表
	public function search(){
		$this->header_json();
		$group_return = D('Group')->get_group_list_by_keywords($_GET['w'],$_GET['sort'],true);
		echo json_encode($group_return);
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

	public function getRecommendList($city_id){
        //获取推荐列表
        $re_category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>1,'city_id'=>$city_id))->order('cat_sort desc')->select();
        $categoryList = array();
        foreach ($re_category as $v){
            $categoryList[] = $v['cat_id'];
        }
        $sub_where['cat_fid'] = array('in',$categoryList);
        $sub_where['cat_status'] = 1;
        $subCategory = D('Shop_category')->where($sub_where)->order('cat_sort desc')->select();
        $recommend_list = array();
        foreach ($subCategory as $v){
            $sub_recommend['title'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
            $sub_recommend['id'] = $v['cat_id'];
            $sub_recommend['fid'] = $v['cat_fid'];
            $sub_recommend['info'] = array();
            $closeArr = array();
            $openArr = array();
            $storeList = D('Shop_category_relation')->where(array('cat_id'=>$v['cat_id']))->order('store_sort desc')->select();
            $allClose = true;
            foreach ($storeList as $store){
                $storeRow = D('Merchant_store')->field('st.*,sh.background')->join('as st left join ' . C('DB_PREFIX') . 'merchant_store_shop sh on st.store_id = sh.store_id ')->where(array('st.store_id' => $store['store_id']))->find();
                $storeMemo['store_id'] = $storeRow['store_id'];
                $storeMemo['name'] = lang_substr($storeRow['name'], C('DEFAULT_LANG'));
                $image_tmp = explode(',', $storeRow['background']);
                $storeMemo['background'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
                $storeMemo['txt_info'] = $storeRow['txt_info'];
                $storeMemo['is_close'] = 1;

                //@wangchuanyuan 周一到周天
                $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
                $now_time = date('H:i:s');
                switch ($date){
                    case 1 :
                        if ($storeRow['open_1'] != '00:00:00' || $storeRow['close_1'] != '00:00:00'){
                            if ($storeRow['open_1'] < $now_time && $now_time < $storeRow['close_1']) {
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if($storeRow['open_2'] != '00:00:00' || $storeRow['close_2'] != '00:00:00'){
                            if($storeRow['open_2'] < $now_time && $now_time < $storeRow['close_2']) {
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if($storeRow['open_3'] != '00:00:00' || $storeRow['close_3'] != '00:00:00'){
                            if ($storeRow['open_3'] < $now_time && $now_time < $storeRow['close_3']) {
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        break;
                    case 2 ://周二
                        if ($storeRow['open_4'] != '00:00:00' || $storeRow['close_4'] != '00:00:00') {
                            if ($storeRow['open_4'] < $now_time && $now_time < $storeRow['close_4']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_5'] != '00:00:00' || $storeRow['close_5'] != '00:00:00') {
                            if ($storeRow['open_5'] < $now_time && $now_time < $storeRow['close_5']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_6'] != '00:00:00' || $storeRow['close_6'] != '00:00:00') {
                            if ($storeRow['open_6'] < $now_time && $now_time < $storeRow['close_6']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        break;
                    case 3 ://周三
                        if ($storeRow['open_7'] != '00:00:00' || $storeRow['close_7'] != '00:00:00') {
                            if ($storeRow['open_7'] < $now_time && $now_time < $storeRow['close_7']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_8'] != '00:00:00' || $storeRow['close_8'] != '00:00:00') {
                            if ($storeRow['open_8'] < $now_time && $now_time < $storeRow['close_8']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_9'] != '00:00:00' || $storeRow['close_9'] != '00:00:00') {
                            if ($storeRow['open_9'] < $now_time && $now_time < $storeRow['close_9']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        break;
                    case 4 :
                        if ($storeRow['open_10'] != '00:00:00' || $storeRow['close_10'] != '00:00:00') {
                            if ($storeRow['open_10'] < $now_time && $now_time < $storeRow['close_10']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_11'] != '00:00:00' || $storeRow['close_11'] != '00:00:00') {
                            if ($storeRow['open_11'] < $now_time && $now_time < $storeRow['close_11']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_12'] != '00:00:00' || $storeRow['close_12'] != '00:00:00') {
                            if ($storeRow['open_12'] < $now_time && $now_time < $storeRow['close_12']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        break;
                    case 5 :
                        if ($storeRow['open_13'] != '00:00:00' || $storeRow['close_13'] != '00:00:00') {
                            if ($storeRow['open_13'] < $now_time && $now_time < $storeRow['close_13']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_14'] != '00:00:00' || $storeRow['close_14'] != '00:00:00') {
                            if ($storeRow['open_14'] < $now_time && $now_time < $storeRow['close_14']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_15'] != '00:00:00' || $storeRow['close_15'] != '00:00:00') {
                            if ($storeRow['open_15'] < $now_time && $now_time < $storeRow['close_15']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        break;
                    case 6 :
                        if ($storeRow['open_16'] != '00:00:00' || $storeRow['close_16'] != '00:00:00') {
                            if ($storeRow['open_16'] < $now_time && $now_time < $storeRow['close_16']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_17'] != '00:00:00' || $storeRow['close_17'] != '00:00:00') {
                            if ($storeRow['open_17'] < $now_time && $now_time < $storeRow['close_17']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_18'] != '00:00:00' || $storeRow['close_18'] != '00:00:00') {
                            if ($storeRow['open_18'] < $now_time && $now_time < $storeRow['close_18']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        break;
                    case 0 :
                        if ($storeRow['open_19'] != '00:00:00' || $storeRow['close_19'] != '00:00:00') {
                            if ($storeRow['open_19'] < $now_time && $now_time < $storeRow['close_19']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_20'] != '00:00:00' || $storeRow['close_20'] != '00:00:00') {
                            if ($storeRow['open_20'] < $now_time && $now_time < $storeRow['close_20']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        if ($storeRow['open_21'] != '00:00:00' || $storeRow['close_21'] != '00:00:00') {
                            if ($storeRow['open_21'] < $now_time && $now_time < $storeRow['close_21']){
                                $storeMemo['is_close'] = 0;
                            }
                        }
                        break;
                    default :
                        $storeMemo['is_close'] = 1;
                }
                //garfunkel add
                if($storeRow['store_is_close'] != 0){
                    $storeMemo['is_close'] = 1;
                }

                if($storeMemo['is_close'] == 0){
                    $allClose = false;
                    $openArr[] = $storeMemo;
                }else{
                    $closeArr[] = $storeMemo;
                }
            }
            $sub_recommend['info'] = array_slice(array_merge($openArr,$closeArr),0,5);

            if(count($sub_recommend['info']) > 0 && !$allClose) {
                $recommend_list[] = $sub_recommend;
            }
        }
        return $recommend_list;
	}

}

?>
