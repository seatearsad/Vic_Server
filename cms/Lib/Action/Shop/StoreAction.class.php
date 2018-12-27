<?php
class StoreAction extends BaseAction
{
	public function index()
	{
		$this->select_address();
		$return['category_list'] = D('Shop_category')->lists(true);
		//modify garfunkel
        foreach ($return['category_list'] as $k => $v){
            $return['category_list'][$k]['cat_name'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
            foreach ($v['son_list'] as $kk => $vv){
                $return['category_list'][$k]['son_list'][$kk]['cat_name'] = lang_substr($vv['cat_name'],C('DEFAULT_LANG'));
            }
        }

		$cat_url = isset($_GET['cat_url']) ? $_GET['cat_url'] : 'all';
		$sort_url = isset($_GET['sort_url']) ? $_GET['sort_url'] : 'juli';
		$type_url = isset($_GET['type_url']) ? intval($_GET['type_url']) : '-1';
		
		$cat_fid = $cat_id = 0;
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
		$return['type_url'] = $type_url;
		$return['sort_url'] = $sort_url;
		$return['cat_url'] = $cat_url;
		$return['keyword'] = '';
		$return['cat_fid'] = $cat_fid;
		$return['cat_id'] = $cat_id;

		$lat = $_COOKIE['shop_select_lat'];
		$long = $_COOKIE['shop_select_lng'];
		$where = array('deliver_type_pc' => $type_url, 'order' => $sort_url, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => 1);
// 		$key && $where['key'] = $key;
		
		$lists = D('Merchant_store_shop')->get_list_by_option($where, 2);
		
		$result = $this->format_store_data($lists);
		
		$return['store_list'] = $result;
		$return['keyword'] = '';
		$return['total'] = $lists['total'];
		$return['store_count'] = count($result);
		$return['next_page'] = $lists['next_page'];

        $index_top_adver = D('Adver')->get_adver_by_key('index_top');
        $this->assign('index_top_adver',$index_top_adver);
		
		$this->assign($return);
		$this->display();
	}
	
	
	private function format_store_data($lists) 
	{
		$result = array();
		$now_time = date('H:i:s');
		foreach ($lists['shop_list'] as $row) {
			$temp = array();
			$temp['id'] = $row['store_id'];//店铺ID
			$temp['name'] = lang_substr($row['name'],C('DEFAULT_LANG'));//店铺名称
            //modify garfunkel 大于999取整
            if($row['range'] > 999)
                $temp['range'] = intval($row['range']).' km';
            else
			    $temp['range'] = $row['range'];
            //距离
			$temp['image'] = $row['image'];//图片
			$temp['star'] = $row['score_mean'];//评分
			$temp['month_sale_count'] = $row['sale_count'];//销量
			$temp['delivery'] = $deliver_type == 2 ? 0 : $row['deliver'];//是否支持配送
			$temp['delivery_time'] = $row['send_time'];//配送时长
			$temp['delivery_price'] = floatval($row['basic_price']);//起送价
			$temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
			$temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
			$temp['deliver_type'] = $row['deliver_type'];//配送类型
			$temp['is_close'] = 1;
			$temp['store_notice'] = lang_substr($row['store_notice'],C('DEFAULT_LANG'));
			$temp['mean_money'] = $row['mean_money'];
			$temp['qrcode_url'] = $this->config['site_url'] . '/index.php?g=Index&c=Recognition&a=see_qrcode&type=shop&id=' . $row['store_id'];//$row['store_notice'];
			$temp['detail_url'] = $this->config['site_url'] . '/shop/' . $row['store_id'] . '.html';//$row['store_notice'];
			$temp['isverify'] = $row['isverify'];
			$temp['pack_fee'] = $row['pack_fee'];

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
            //end  @wangchuanyuan
			$temp['coupon_list'] = array();
			if ($row['isverify']) {
				$temp['coupon_list']['verify'] = true;
			}
		
			if ($row['is_invoice']) {
				$temp['coupon_list']['invoice'] = floatval($row['invoice_price']);//满足多少开发票
			}
			$temp['is_store_discount'] = 0;//折，是否有店铺折扣
			if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
				$temp['coupon_list']['discount'] = $row['store_discount'] / 10;//店铺折扣
				$temp['is_store_discount'] = 1;
			}
			$system_delivery = array();
			$system_delivery_text = array();
			foreach ($row['system_discount'] as $row_d) {
				if ($row_d['type'] == 0) {//新单
					$temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					$temp['coupon_text']['system_newuser'][] = '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元';
				} elseif ($row_d['type'] == 1) {//满减
					$temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
					$temp['coupon_text']['system_minus'][] = '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元';
				} elseif ($row_d['type'] == 2) {//配送
					if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
						$system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
						$system_delivery_text[] = '满' . floatval($row_d['full_money']) . '元减' . floatval($row_d['reduce_money']) . '元';
					}
				}
			}
			foreach ($row['merchant_discount'] as $row_m) {
				if ($row_m['type'] == 0) {
					$temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
					$temp['coupon_text']['newuser'][] = '满' . floatval($row_m['full_money']) . '元减' .  floatval($row_m['reduce_money']) . '元';
				} elseif ($row_m['type'] == 1) {
					$temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
					$temp['coupon_text']['minus'][] = '满' . floatval($row_m['full_money']) . '元减' . floatval($row_m['reduce_money']) . '元';
				}
			}
			if ($row['deliver']) {
				if ($temp['delivery_system']) {
					$system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
					$system_delivery_text && $temp['coupon_text']['delivery'] = $system_delivery_text;
				} else {
					if ($row['is_have_two_time']) {
						if ($row['reach_delivery_fee_type2'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
								$temp['coupon_text']['delivery'][] = '满' . floatval($row['basic_price']) . '元减' . floatval($row['delivery_fee2']) . '元';
							}
						} elseif ($row['reach_delivery_fee_type2'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type2'] == 2) {
							$row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
							$row['delivery_fee2'] && $temp['coupon_text']['delivery'][] = '满' . floatval($row['no_delivery_fee_value2']) . '元减' . floatval($row['delivery_fee2']) . '元';
						}
					} else {
						if ($row['reach_delivery_fee_type'] == 0) {
							if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
								$temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
								$temp['coupon_text']['delivery'][] = '满' . floatval($row['basic_price']) . '元减' . floatval($row['delivery_fee']) . '元';
							}
						} elseif ($row['reach_delivery_fee_type'] == 1) {
							//$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
						} elseif ($row['reach_delivery_fee_type'] == 2) {
							$row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
							$row['delivery_fee'] && $temp['coupon_text']['delivery'][] = '满' . floatval($row['no_delivery_fee_value']) . '元减' . floatval($row['delivery_fee']) . '元';
						}
					}
				}
			}
				
			foreach ($temp['coupon_text'] as $key => $row) {
				switch($key){
					case 'system_newuser':
						$temp['system_newuser_text'] = '平台首单' . implode(',', $temp['coupon_text'][$key]);
						break;
					case 'system_minus':
						$temp['system_minus_text'] = '平台优惠' . implode(',', $temp['coupon_text'][$key]);
						break;
					case 'newuser':
						$temp['newuser_text'] = '店铺首单' . implode(',', $temp['coupon_text'][$key]);
						break;
					case 'minus':
						$temp['minus_text'] = '店铺优惠' . implode(',', $temp['coupon_text'][$key]);
						break;
					case 'system_minus':
						$temp['system_minus_text'] = '平台优惠' . implode(',', $temp['coupon_text'][$key]);
						break;
					case 'delivery':
						$temp['delivery_text'] = '配送费' . implode(',', $temp['coupon_text'][$key]);
						break;
				}
			}
			$temp['coupon_count'] = count($temp['coupon_list']);
			$result[] = $temp;
		}
		
		return $result;
	}

	private function select_address()
	{
		$lat = $_COOKIE['shop_select_lat'];
		$lng = $_COOKIE['shop_select_lng'];
		if (empty($lat) || empty($lng)) {
		    //add garfunkel 如果用户已经登录，获取默认地址坐标
		    if($_SESSION['user']){
                $default_adr = D('User_adress')->get_one_adress($_SESSION['user']['uid']);
                if($default_adr){
                    $lat = $default_adr['latitude'];
                    $lng = $default_adr['longitude'];
                }
            }
            if (empty($lat) || empty($lng)) {
                redirect('/shop/change.html?referer=' . urlencode($_SERVER['REDIRECT_URL']));
            }
		}
		$this->assign('shop_select_address', $_COOKIE['shop_select_address']);
	}
	public function ajax_list()
	{
		$key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		$cat_url = isset($_POST['cat_url']) ? htmlspecialchars($_POST['cat_url']) : 'all';
		$order = isset($_POST['sort_url']) ? htmlspecialchars($_POST['sort_url']) : 'juli';
		$deliver_type = isset($_POST['type_url']) ? intval($_POST['type_url']) : '-1';
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
		$lat = $_COOKIE['shop_select_lat'];
		$long = $_COOKIE['shop_select_lng'];
		$where = array('deliver_type_pc' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
		$key && $where['key'] = $key;

		$lists = D('Merchant_store_shop')->get_list_by_option($where, 2);
		$return = $this->format_store_data($lists);
		
		echo json_encode(array('total' => $lists['total'], 'store_count' => count($return), 'store_list' => $return, 'next_page' => $lists['next_page']));
	}


	public function detail()
	{
// 		$this->select_address();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

		$sort = isset($_GET['sort']) && $_GET['sort'] ? intval($_GET['sort']) : 0;
		$keyword = isset($_GET['keyword']) && $_GET['keyword'] ? trim(htmlspecialchars(($_GET['keyword']))) : '';
		$store = $this->now_store($store_id);
		if (empty($store)) {
			$this->error(L('_NOT_SHOP_INFO_'));
		}else{//add garfunkel
		    $store['store_notice'] = lang_substr($store['store_notice'],C('DEFAULT_LANG'));
        }
		$today = date('Ymd');

		$product_list = D('Shop_goods')->get_list_by_storeid($store_id, $sort, $keyword);
		$count = 0;
		foreach ($product_list as $row) {
			$temp = array();
			if ($row['sort_id'] == false) {
				$temp['cat_id'] = 0;
				$temp['cat_name'] = 0;
				$temp['sort_discount'] = 0;
			} else {
				$temp['cat_id'] = $row['sort_id'];
				//modify garfunkel 语言判断
				$temp['cat_name'] = lang_substr($row['sort_name'],C('DEFAULT_LANG'));
				$temp['sort_discount'] = $row['sort_discount']/10;
			}

			foreach ($row['goods_list'] as $r) {
				$glist = array();
				$glist['product_id'] = $r['goods_id'];
                //modify garfunkel 语言判断
				$glist['product_name'] = lang_substr($r['name'],C('DEFAULT_LANG'));
				$glist['product_price'] = $r['price'];
				$glist['is_seckill_price'] = $r['is_seckill_price'];
				$glist['o_price'] = $r['o_price'];
				$glist['number'] = $r['number'];
				$glist['packing_charge'] = floatval($r['packing_charge']);
				$glist['unit'] = $r['unit'];
				//garfunkel add
                $glist['tax_num'] = $r['tax_num'];
                $glist['deposit_price'] = $r['deposit_price'];
                //
				$glist['extra_pay_price'] = $r['extra_pay_price'];
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
				$count++;
			}
			$list[] = $temp;
		}
		//dump($list);
		$this->assign('product_list', $list);
		$this->assign('store', $store);
		$this->assign('sort', $sort);
		$this->assign('keyword', $keyword);
		$this->assign('count', $count);
		$this->display();
	}


	private function now_store($store_id)
	{
		$where = array('store_id' => $store_id);
		$now_store = D('Merchant_store')->field(true)->where($where)->find();
		if ($now_store['status'] != 1) {
			return null;
		}
		//资质认证
		if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
			return null;
		}
		$merchant = D('Merchant')->field(true)->where(array('mer_id' => $now_store['mer_id']))->find();
		if ($merchant['status'] != 1) {
			return null;
		}
		$now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();

		if (empty($now_shop) || empty($now_store)) {
			return null;
		}
		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$row = array_merge($now_store, $now_shop);

		$store = array();
		$store_image_class = new store_image();
		$images = $store_image_class->get_allImage_by_path($row['pic_info']);

		$store['id'] = $row['store_id'];

		$store['phone'] = $row['phone'];
		$store['isverify'] = $merchant['isverify'];
		$store['long'] = $row['long'];
		$store['lat'] = $row['lat'];
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
//			if ($row['open_2'] != '00:00:00' && $row['close_2'] != '00:00:00') {
//				$store['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
//				if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
//					$store['is_close'] = 0;
//				}
//			}
//			if ($row['open_3'] != '00:00:00' && $row['close_3'] != '00:00:00') {
//				$store['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
//				if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
//					$store['is_close'] = 0;
//				}
//			}
//		}
        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
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
                $store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
                $store['time'] .= ';' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
                $store['time'] .= ';' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
                break;
            case 2 ://周二
                if ($row['open_4'] != '00:00:00' || $row['close_4'] != '00:00:00') {
                    if ($row['open_4'] < $now_time && $now_time < $row['close_4']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_5'] != '00:00:00' || $row['close_5'] != '00:00:00') {
                    if ($row['open_5'] < $now_time && $now_time < $row['close_5']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_6'] != '00:00:00' || $row['close_6'] != '00:00:00') {
                    if ($row['open_6'] < $now_time && $now_time < $row['close_6']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_4'], 0, -3) . '~' . substr($row['close_4'], 0, -3);
                $store['time'] .= ';' . substr($row['open_5'], 0, -3) . '~' . substr($row['close_5'], 0, -3);
                $store['time'] .= ';' . substr($row['open_6'], 0, -3) . '~' . substr($row['close_6'], 0, -3);
                break;
            case 3 ://周三
                if ($row['open_7'] != '00:00:00' || $row['close_7'] != '00:00:00') {
                    if ($row['open_7'] < $now_time && $now_time < $row['close_7']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_8'] != '00:00:00' || $row['close_8'] != '00:00:00') {
                    if ($row['open_8'] < $now_time && $now_time < $row['close_8']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_9'] != '00:00:00' || $row['close_9'] != '00:00:00') {
                    if ($row['open_9'] < $now_time && $now_time < $row['close_9']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_7'], 0, -3) . '~' . substr($row['close_7'], 0, -3);
                $store['time'] .= ';' . substr($row['open_8'], 0, -3) . '~' . substr($row['close_8'], 0, -3);
                $store['time'] .= ';' . substr($row['open_9'], 0, -3) . '~' . substr($row['close_9'], 0, -3);

                break;
            case 4 :
                if ($row['open_10'] != '00:00:00' || $row['close_10'] != '00:00:00') {
                    if ($row['open_10'] < $now_time && $now_time < $row['close_10']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_11'] != '00:00:00' || $row['close_11'] != '00:00:00') {
                    if ($row['open_11'] < $now_time && $now_time < $row['close_11']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_12'] != '00:00:00' || $row['close_12'] != '00:00:00') {
                    if ($row['open_12'] < $now_time && $now_time < $row['close_12']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_10'], 0, -3) . '~' . substr($row['close_10'], 0, -3);
                $store['time'] .= ';' . substr($row['open_11'], 0, -3) . '~' . substr($row['close_11'], 0, -3);
                $store['time'] .= ';' . substr($row['open_12'], 0, -3) . '~' . substr($row['close_12'], 0, -3);
                break;
            case 5 :
                if ($row['open_13'] != '00:00:00' || $row['close_13'] != '00:00:00') {
                    if ($row['open_13'] < $now_time && $now_time < $row['close_13']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_14'] != '00:00:00' || $row['close_14'] != '00:00:00') {
                    if ($row['open_14'] < $now_time && $now_time < $row['close_14']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_15'] != '00:00:00' || $row['close_15'] != '00:00:00') {
                    if ($row['open_15'] < $now_time && $now_time < $row['close_15']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_13'], 0, -3) . '~' . substr($row['close_13'], 0, -3);
                $store['time'] .= ';' . substr($row['open_14'], 0, -3) . '~' . substr($row['close_14'], 0, -3);
                $store['time'] .= ';' . substr($row['open_15'], 0, -3) . '~' . substr($row['close_15'], 0, -3);
                break;
            case 6 :
                if ($row['open_16'] != '00:00:00' || $row['close_16'] != '00:00:00') {
                    if ($row['open_16'] < $now_time && $now_time < $row['close_16']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_17'] != '00:00:00' || $row['close_17'] != '00:00:00') {
                    if ($row['open_17'] < $now_time && $now_time < $row['close_17']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_18'] != '00:00:00' || $row['close_18'] != '00:00:00') {
                    if ($row['open_18'] < $now_time && $now_time < $row['close_18']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_16'], 0, -3) . '~' . substr($row['close_16'], 0, -3);
                $store['time'] .= ';' . substr($row['open_17'], 0, -3) . '~' . substr($row['close_17'], 0, -3);
                $store['time'] .= ';' . substr($row['open_18'], 0, -3) . '~' . substr($row['close_18'], 0, -3);
                break;
            case 0 :
                if ($row['open_19'] != '00:00:00' || $row['close_19'] != '00:00:00') {
                    if ($row['open_19'] < $now_time && $now_time < $row['close_19']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_20'] != '00:00:00' || $row['close_20'] != '00:00:00') {
                    if ($row['open_20'] < $now_time && $now_time < $row['close_20']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_21'] != '00:00:00' || $row['close_21'] != '00:00:00') {
                    if ($row['open_21'] < $now_time && $now_time < $row['close_21']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_19'], 0, -3) . '~' . substr($row['close_19'], 0, -3);
                $store['time'] .= ';' . substr($row['open_20'], 0, -3) . '~' . substr($row['close_20'], 0, -3);
                $store['time'] .= ';' . substr($row['open_21'], 0, -3) . '~' . substr($row['close_21'], 0, -3);
                break;
            default :
                $store['is_close'] = 1;
                $store['time']= '营业时间未知';
        }
        //end  @wangchuanyuan
		$delivers = array('平台配送', '商家配送', '客户自提', '平台配送或自提', '商家配送或自提', '快递配送');
        //modify garfunkel 增加语言判断
		$store['name'] = lang_substr($row['name'],C('DEFAULT_LANG'));

		$store['store_notice'] = $row['store_notice'];
		$store['txt_info'] = $row['txt_info'];
		$store['image'] = isset($images[0]) ? $images[0] : '';
		$store['star'] = $row['score_mean'];
		$store['month_sale_count'] = $row['sale_count'];
		$store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
		$store['delivery_time'] = $row['send_time'];//配送时长
		$store['delivery_price'] = floatval($row['basic_price']);//起送价
		$store['deliver_name'] = $delivers[$row['deliver_type']];
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


        //modify garfunkel
        $store['delivery_money'] = C('config.delivery_distance_1');
        //$store['delivery_money'] = floatval($store['delivery_money']);
// 		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
// 		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
		$store['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
		if (in_array($row['deliver_type'], array(2, 3, 4))) {
			$store['pick'] = 1;//是否支持自提
		} else {
			$store['pick'] = 0;//是否支持自提
		}
		$store['pack_alias'] = $row['pack_alias'];//打包费别名
        //modify garfunkel
        $store['pack_fee'] = $row['pack_fee'];
		$store['freight_alias'] = $row['freight_alias'];//运费别名
		$store['coupon_list'] = array();
		if ($row['is_invoice']) {
			$store['coupon_list']['invoice'] = floatval($row['invoice_price']);
		}
		if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
			$store['coupon_list']['discount'] = $row['store_discount']/10;
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
		return $store;
	}
	public function comment()
	{
		$this->select_address();
		$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
		$store = $this->now_store($store_id);
		if (empty($store)) {
			$this->error('不存在的店铺信息');
		}
		$tab = isset($_GET['tab']) ? trim(htmlspecialchars($_GET['tab'])) : '';
		$reply_return = D('Reply')->get_page_reply_list($store_id, 3, $tab, '', '', true, 1);
		$this->assign('tab', $tab);

		$this->assign($reply_return);
		$this->assign('store', $store);
		$this->display();
	}


	public function ajax_goods()
	{
		$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 1;
		$database_shop_goods = D('Shop_goods');
		$now_goods = $database_shop_goods->get_goods_by_id($goods_id);
		if($now_goods['list']){
			foreach($now_goods['list'] as $k => &$value){
				if ($now_goods['is_seckill_price']) {
					$value['price'] = $value['seckill_price'];
				}
                //$now_goods['list'][]
			}
		}
        //add garfunkel
        if ($now_goods['properties_list']){
		    foreach ($now_goods['properties_list'] as $k => $v){
		        $now_goods['properties_list'][$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
		        foreach($v['val'] as $kk => $vv){
                    $now_goods['properties_list'][$k]['val'][$kk] = lang_substr($vv,C('DEFAULT_LANG'));
                }
            }
        }
        if($now_goods['spec_list']){
		    foreach($now_goods['spec_list'] as $k => $v){
                $now_goods['spec_list'][$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
                foreach($v['list'] as $kk => $vv){
                    $now_goods['spec_list'][$k]['list'][$kk]['name'] = lang_substr($vv['name'],C('DEFAULT_LANG'));
                }
            }
        }
        $now_goods['name'] = lang_substr($now_goods['name'],C('DEFAULT_LANG'));
        $now_goods['unit'] = lang_substr($now_goods['unit'],C('DEFAULT_LANG'));
        //
// 		echo '<pre/>';
// 		print_r($now_goods);die;
		if(empty($now_goods)){
			$this->error_tips('商品不存在！');
		}
		echo json_encode(array('status' => 1, 'data' => $now_goods));
	}
}