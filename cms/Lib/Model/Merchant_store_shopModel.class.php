<?php
class Merchant_store_shopModel extends Model
{

	/**
	 * 根据条件获取商家列表
	 * @param array $where
	 * @param number $limit
	 */
	public function get_list_by_option($where = array(), $is_wap = 1)
	{
		$deliver_type = isset($where['deliver_type']) ? $where['deliver_type'] : 'all';
		$order_str = isset($where['order']) ? $where['order'] : 'juli';
		$lat = isset($where['lat']) ? $where['lat'] : 0;
		$long = isset($where['long']) ? $where['long'] : 0;
		$cat_id = isset($where['cat_id']) ? $where['cat_id'] : 0;
		$cat_fid = isset($where['cat_fid']) ? $where['cat_fid'] : 0;


//		$condition_where = "s.city_id='".C('config.now_city')."' AND s.have_meal=1 AND s.status=1 AND s.store_id=m.store_id";
		$condition_where = "s.status=1 AND s.store_id=m.store_id AND s.have_shop=1";
		if (C('config.store_shop_auth') == 1) {
			$condition_where .= " AND s.auth>2";
		}
		if (isset($where['deliver_type_pc'])) {
			if ($where['deliver_type_pc'] == 0) {
				$condition_where .= " AND ((`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 1, 3, 4)  AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
				$condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
			} elseif ($where['deliver_type_pc'] == 1) {
				$condition_where .= " AND ((`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 3)  AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
				$condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
			} elseif ($where['deliver_type_pc'] == 2) {
				$condition_where .= " AND `m`.`deliver_type` IN (2, 3, 4)";
			} elseif ($where['deliver_type_pc'] == 5) {
				$condition_where .= " AND `m`.`deliver_type`=5";
			} else {
				$condition_where .= " AND (`m`.`deliver_type` IN (2, 3, 4, 5) OR (`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 1) AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
				$condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
			}
		} else {
			if ($deliver_type == 'delivery') {
	// 			$condition_where .= " AND `m`.`deliver_type`<>2";
				$condition_where .= " AND ((`m`.`delivery_range_type`=0 AND `m`.`deliver_type`<>2 AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
				$condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
			} elseif ($deliver_type == 'pick') {
				$condition_where .= " AND `m`.`deliver_type` IN (2, 3, 4)";
			} else {
				$condition_where .= " AND (`m`.`deliver_type` IN (2, 3, 4, 5) OR (`m`.`delivery_range_type`=0 AND `m`.`deliver_type` IN (0, 1) AND ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) < `m`.`delivery_radius`*1000)";
				$condition_where .= " OR (`m`.`delivery_range_type`=1 AND MBRContains(PolygonFromText(`m`.`delivery_range_polygon`),PolygonFromText('Point({$long} {$lat})'))>0))";
			}
		}

		if (isset($where['key']) && $where['key']) {
			$condition_where .= " AND `s`.`name` LIKE '%{$where['key']}%'";
		}
// 		$area_id && $condition_where .= " AND s.area_id={$area_id}";
// 		$circle_id && $condition_where .= " AND s.circle_id={$circle_id}";

		if ($cat_id) {
			$category = M('Shop_category')->where(array('cat_id' => $cat_id))->find();
		} elseif ($cat_fid) {
			$category = M('Shop_category')->where(array('cat_id' => $cat_fid))->find();
		}
		$show_method = isset($category) && $category ? $category['show_method'] : 2;


		$condition_field = 's.*, m.*,mm.isverify';
		$order = '';
		$time = date('H:i:s');


//		if ($show_method == 2) {//靠后显示
//			$time = date("H:i:s");
//			$condition_field .= ",(CASE
//			WHEN (`s`.`open_1`='00:00:00' and `s`.`open_2`='00:00:00' and `s`.`open_3`='00:00:00' and `s`.`close_1`='00:00:00' and `s`.`close_2`='00:00:00' and `s`.`close_3`='00:00:00') then 2
//			WHEN (`m`.`is_reserve` = 1) then 1
//			WHEN ((`s`.`open_1`<'$time' and `s`.`close_1`>'$time') OR (`s`.`open_2`<'$time' and `s`.`close_2`>'$time') OR (`s`.`open_3`<'$time' and `s`.`close_3`>'$time')) then 2
//			ELSE 0
//			END) as `t_sort`";
//			$order .= '`t_sort` DESC, ';
//		} elseif ($show_method == 0) {//不显示
//			$condition_where .= " AND ((`s`.`open_1`='00:00:00' AND `s`.`close_1`='00:00:00') OR ((`s`.`open_1`<'$time' AND `s`.`close_1`>'$time') OR (`s`.`open_2`<'$time' AND `s`.`close_2`>'$time') OR (`s`.`open_3`<'$time' AND `s`.`close_3`>'$time')))";
//		}

        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
        switch ($date){
            case 1 :
                if ($show_method == 2) {//靠后显示
                    $condition_field .= ",(CASE
                    WHEN (`s`.`open_1`='00:00:00' and `s`.`open_2`='00:00:00' and `s`.`open_3`='00:00:00' and `s`.`close_1`='00:00:00' and `s`.`close_2`='00:00:00' and `s`.`close_3`='00:00:00') then 0
                    WHEN (`m`.`is_reserve` = 1) then 1
                    WHEN ((`s`.`open_1`<'$time' and `s`.`close_1`>'$time') OR (`s`.`open_2`<'$time' and `s`.`close_2`>'$time') OR (`s`.`open_3`<'$time' and `s`.`close_3`>'$time')) then 2
                    ELSE 0
                    END) as `t_sort`";
                    $order .= '`t_sort` DESC, ';
                } elseif ($show_method == 0) {//不显示
                    $condition_where .= " AND ((`s`.`open_1`='00:00:00' AND `s`.`close_1`='00:00:00') OR ((`s`.`open_1`<'$time' AND `s`.`close_1`>'$time') OR (`s`.`open_2`<'$time' AND `s`.`close_2`>'$time') OR (`s`.`open_3`<'$time' AND `s`.`close_3`>'$time')))";
                }
                break;
            case 2 ://周二
                if ($show_method == 2) {//靠后显示
                    $condition_field .= ",(CASE
                    WHEN (`s`.`open_4`='00:00:00' and `s`.`open_5`='00:00:00' and `s`.`open_6`='00:00:00' and `s`.`close_4`='00:00:00' and `s`.`close_5`='00:00:00' and `s`.`close_6`='00:00:00') then 0
                    WHEN (`m`.`is_reserve` = 1) then 1
                    WHEN ((`s`.`open_4`<'$time' and `s`.`close_4`>'$time') OR (`s`.`open_5`<'$time' and `s`.`close_5`>'$time') OR (`s`.`open_6`<'$time' and `s`.`close_6`>'$time')) then 2
                    ELSE 0
                    END) as `t_sort`";
                    $order .= '`t_sort` DESC, ';
                } elseif ($show_method == 0) {//不显示
                    $condition_where .= " AND ((`s`.`open_4`='00:00:00' AND `s`.`close_4`='00:00:00') OR ((`s`.`open_4`<'$time' AND `s`.`close_4`>'$time') OR (`s`.`open_5`<'$time' AND `s`.`close_5`>'$time') OR (`s`.`open_6`<'$time' AND `s`.`close_6`>'$time')))";
                }
                break;
            case 3 ://周三
                if ($show_method == 2) {//靠后显示
                    $condition_field .= ",(CASE
                    WHEN (`s`.`open_7`='00:00:00' and `s`.`open_8`='00:00:00' and `s`.`open_9`='00:00:00' and `s`.`close_7`='00:00:00' and `s`.`close_8`='00:00:00' and `s`.`close_9`='00:00:00') then 0
                    WHEN (`m`.`is_reserve` = 1) then 1
                    WHEN ((`s`.`open_7`<'$time' and `s`.`close_7`>'$time') OR (`s`.`open_8`<'$time' and `s`.`close_8`>'$time') OR (`s`.`open_9`<'$time' and `s`.`close_9`>'$time')) then 2
                    ELSE 0
                    END) as `t_sort`";
                    $order .= '`t_sort` DESC, ';
                } elseif ($show_method == 0) {//不显示
                    $condition_where .= " AND ((`s`.`open_7`='00:00:00' AND `s`.`close_7`='00:00:00') OR ((`s`.`open_7`<'$time' AND `s`.`close_7`>'$time') OR (`s`.`open_8`<'$time' AND `s`.`close_8`>'$time') OR (`s`.`open_9`<'$time' AND `s`.`close_9`>'$time')))";
                }
                break;
            case 4 :
                if ($show_method == 2) {//靠后显示
                    $condition_field .= ",(CASE
                    WHEN (`s`.`open_10`='00:00:00' and `s`.`open_11`='00:00:00' and `s`.`open_12`='00:00:00' and `s`.`close_10`='00:00:00' and `s`.`close_11`='00:00:00' and `s`.`close_12`='00:00:00') then 0
                    WHEN (`m`.`is_reserve` = 1) then 1
                    WHEN ((`s`.`open_10`<'$time' and `s`.`close_10`>'$time') OR (`s`.`open_11`<'$time' and `s`.`close_11`>'$time') OR (`s`.`open_12`<'$time' and `s`.`close_12`>'$time')) then 2
                    ELSE 0
                    END) as `t_sort`";
                    $order .= '`t_sort` DESC, ';
                } elseif ($show_method == 0) {//不显示
                    $condition_where .= " AND ((`s`.`open_10`='00:00:00' AND `s`.`close_10`='00:00:00') OR ((`s`.`open_10`<'$time' AND `s`.`close_10`>'$time') OR (`s`.`open_11`<'$time' AND `s`.`close_11`>'$time') OR (`s`.`open_12`<'$time' AND `s`.`close_12`>'$time')))";
                }
                break;
            case 5 :
                if ($show_method == 2) {//靠后显示
                    $condition_field .= ",(CASE
                    WHEN (`s`.`open_13`='00:00:00' and `s`.`open_14`='00:00:00' and `s`.`open_15`='00:00:00' and `s`.`close_13`='00:00:00' and `s`.`close_14`='00:00:00' and `s`.`close_15`='00:00:00') then 0
                    WHEN (`m`.`is_reserve` = 1) then 1
                    WHEN ((`s`.`open_13`<'$time' and `s`.`close_13`>'$time') OR (`s`.`open_14`<'$time' and `s`.`close_14`>'$time') OR (`s`.`open_15`<'$time' and `s`.`close_15`>'$time')) then 2
                    ELSE 0
                    END) as `t_sort`";
                    $order .= '`t_sort` DESC, ';
                } elseif ($show_method == 0) {//不显示
                    $condition_where .= " AND ((`s`.`open_13`='00:00:00' AND `s`.`close_13`='00:00:00') OR ((`s`.`open_13`<'$time' AND `s`.`close_13`>'$time') OR (`s`.`open_14`<'$time' AND `s`.`close_14`>'$time') OR (`s`.`open_15`<'$time' AND `s`.`close_15`>'$time')))";
                }
                break;
            case 6 :
                if ($show_method == 2) {//靠后显示
                    $condition_field .= ",(CASE
                    WHEN (`s`.`open_16`='00:00:00' and `s`.`open_17`='00:00:00' and `s`.`open_18`='00:00:00' and `s`.`close_16`='00:00:00' and `s`.`close_17`='00:00:00' and `s`.`close_18`='00:00:00') then 0
                    WHEN (`m`.`is_reserve` = 1) then 1
                    WHEN ((`s`.`open_16`<'$time' and `s`.`close_16`>'$time') OR (`s`.`open_17`<'$time' and `s`.`close_17`>'$time') OR (`s`.`open_18`<'$time' and `s`.`close_18`>'$time')) then 2
                    ELSE 0
                    END) as `t_sort`";
                    $order .= '`t_sort` DESC, ';
                } elseif ($show_method == 0) {//不显示
                    $condition_where .= " AND ((`s`.`open_16`='00:00:00' AND `s`.`close_16`='00:00:00') OR ((`s`.`open_16`<'$time' AND `s`.`close_16`>'$time') OR (`s`.`open_17`<'$time' AND `s`.`close_17`>'$time') OR (`s`.`open_18`<'$time' AND `s`.`close_18`>'$time')))";
                }
                break;
            case 0 :
                if ($show_method == 2) {//靠后显示
                    $time = date("H:i:s");
                    $condition_field .= ",(CASE
                    WHEN (`s`.`open_19`='00:00:00' and `s`.`open_20`='00:00:00' and `s`.`open_21`='00:00:00' and `s`.`close_19`='00:00:00' and `s`.`close_20`='00:00:00' and `s`.`close_21`='00:00:00') then 0
                    WHEN (`m`.`is_reserve` = 1) then 1
                    WHEN ((`s`.`open_19`<'$time' and `s`.`close_19`>'$time') OR (`s`.`open_20`<'$time' and `s`.`close_20`>'$time') OR (`s`.`open_21`<'$time' and `s`.`close_21`>'$time')) then 2
                    ELSE 0
                    END) as `t_sort`";
                    $order .= '`t_sort` DESC, ';
                } elseif ($show_method == 0) {//不显示
                    $condition_where .= " AND ((`s`.`open_19`='00:00:00' AND `s`.`close_19`='00:00:00') OR ((`s`.`open_19`<'$time' AND `s`.`close_19`>'$time') OR (`s`.`open_20`<'$time' AND `s`.`close_20`>'$time') OR (`s`.`open_21`<'$time' AND `s`.`close_21`>'$time')))";
                }

                break;
            default :
                break;
        }
        //end  @wangchuanyuan

		//排序
		switch($order_str){
			case 'score_mean':
				$order .= '`m`.`score_mean` DESC,`s`.`store_id` DESC';
				break;
			case 'create_time':
				$order .= '`m`.`create_time` DESC,`s`.`store_id` DESC';
				break;
			case 'sale_count':
				$order .= '`m`.`sale_count` DESC,`s`.`store_id` DESC';
				break;
			case 'send_time':
				$order .= '`m`.`send_time` ASC,`s`.`store_id` DESC';
				break;
			case 'basic_price':
				$order .= '`m`.`basic_price` ASC,`s`.`store_id` DESC';
				break;
			case 'delivery_fee':
				$order .= '`m`.`delivery_fee` ASC,`s`.`store_id` DESC';
				break;
			case 'store_id':
				$order .= '`s`.`store_id` ASC';
				break;
			case 'juli'://智能排序
			default:
				$condition_field .= ", ROUND(6378.137 * 2 * ASIN(SQRT(POW(SIN(({$lat}*PI()/180-`s`.`lat`*PI()/180)/2),2)+COS({$lat}*PI()/180)*COS(`s`.`lat`*PI()/180)*POW(SIN(({$long}*PI()/180-`s`.`long`*PI()/180)/2),2)))*1000) AS juli";
				$order .= 'juli ASC';
				break;
		}

		$mod = new Model();
		if ($cat_fid || $cat_id) {
			if ($cat_fid && $cat_id) {
				$relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id))->select();
			} elseif ($cat_fid) {
				$relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid))->select();
			} else {
				$relation = D('Shop_category_relation')->where(array('cat_id' => $cat_id))->select();
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
				return array('shop_list' => null, 'pagebar' => null, 'total' => 0, 'next_page' => 0);
			}
		}


		$sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id LEFT JOIN ".C('DB_PREFIX')."merchant AS mm ON s.mer_id=mm.mer_id  WHERE {$condition_where} AND mm.status = 1";
		$count = $mod->query($sql_count);
		$total = isset($count[0]['count']) ? $count[0]['count'] : 0;
		if ($is_wap == 1) {
			$page = isset($where['page']) ? intval($where['page']) : 1;
			$pagesize = 20;
			$totalPage = ceil($total / $pagesize);
			$star = $pagesize * ($page - 1);
			$return['has_more'] = $totalPage > $page ? true : false;
		} elseif ($is_wap == 2) {
			$page = isset($where['page']) ? intval($where['page']) : 1;
			$pagesize = 10;
			$totalPage = ceil($total / $pagesize);
			$star = $pagesize * ($page - 1);
			$return['next_page'] = $totalPage > $page ? intval($page + 1) : 0;
		} elseif ($is_wap == 3) {
//			$star	=	isset($where['page']) ? intval($where['page']) : 0;
//			$pagesize	=	5;
            $page = isset($where['page']) ? intval($where['page']) : 1;
            $pagesize = $where['limit'] ? $where['limit'] : 5;
//            $totalPage = ceil($total / $pagesize);
            $star = $pagesize * ($page - 1);
		} else {
			import('@.ORG.group_page');
			$p = new Page($total, C('config.meal_page_row'), C('config.meal_page_val'));
			$star = $p->firstRow;
			$pagesize = $p->listRows;
		}


		$sql = "SELECT {$condition_field} FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant as mm ON mm.mer_id = s.mer_id  WHERE {$condition_where} AND mm.status = 1 ORDER BY {$order} LIMIT {$star}, {$pagesize}";
		$res = $mod->query($sql);
		//fdump($mod->_sql(), 'qqqqqq');
		//print_r($mod);
		// echo $mod->_sql();die;
		$ids = array();
		$store_ids = array();
		foreach ($res as $r) {
			if (!in_array($r['circle_id'], $ids)) {
				$ids[] = $r['circle_id'];
			}
			if (!in_array($r['store_id'], $store_ids)) {
				$store_ids[] = $r['store_id'];
			}
		}
		$discounts = D('Shop_discount')->get_discount_byids($store_ids);
		$temp = array();
		if ($ids) {
			$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
			foreach ($areas as $a) {
				$temp[$a['area_id']] = $a;
			}
		}

		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$store_image_class = new store_image();
        $begin_month=mktime(0,0,0,date('m'),1,date('Y'));
        $end_month=mktime(23,59,59,date('m'),date('t'),date('Y'));
		foreach ($res as &$v) {
			$v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
			$v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['mean_money'] = floatval($v['mean_money']);
			$v['wap_url'] = U('Shop/shop', array('mer_id' => $v['mer_id'], 'store_id' => $v['store_id']));
			$v['deliver'] = $v['deliver_type'] == 2 ? false : true;

			if ($v['juli']) {
				$v['range'] = getRange($v['juli']);
				$jl = $v['juli'];
			} else {
				$location2 = $longlat_class->gpsToBaidu($v['lat'], $v['long']);//转换腾讯坐标到百度坐标
				$jl = getDistance($location2['lat'], $location2['lng'], $lat, $long);
				$v['range'] = getRange($jl);
			}

			if (in_array($v['deliver_type'], array(3, 4)) && $jl > $v['delivery_radius'] * 1000) {
				$v['deliver'] = 0;
			}
            $merchant_store_month_sale_count = M('shop_order')->where(array('store_id'=>array('eq',$v['store_id']),'status'=>array('eq','2'),'create_time'=>array('between',"{$begin_month},{$end_month}")))->count('order_id');
            $v['merchant_store_month_sale_count'] = $merchant_store_month_sale_count?$merchant_store_month_sale_count:0;
//			$v['state'] = 0;//根据营业时间判断
//			$time = date('H:i:s');
//			if ($v['open_1'] == '00:00:00' && $v['close_1'] == '00:00:00') {
//				$v['state'] = 1;
//			} elseif (($v['open_1'] < $time && $v['close_1'] > $time) || ($v['open_2'] < $time && $v['close_2'] > $time) || ($v['open_3'] < $time && $v['close_3'] > $time)) {
//				$v['state'] = 1;
//			}

            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            switch ($date){
                case 1 :
                    if (($v['open_1'] < $time && $v['close_1'] > $time) || ($v['open_2'] < $time && $v['close_2'] > $time) || ($v['open_3'] < $time && $v['close_3'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 2 ://周二
                    if (($v['open_4'] < $time && $v['close_4'] > $time) || ($v['open_5'] < $time && $v['close_5'] > $time) || ($v['open_6'] < $time && $v['close_6'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 3 ://周三
                    if (($v['open_7'] < $time && $v['close_7'] > $time) || ($v['open_8'] < $time && $v['close_8'] > $time) || ($v['open_9'] < $time && $v['close_9'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 4 :
                    if (($v['open_10'] < $time && $v['close_10'] > $time) || ($v['open_11'] < $time && $v['close_11'] > $time) || ($v['open_12'] < $time && $v['close_12'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 5 :
                    if (($v['open_13'] < $time && $v['close_13'] > $time) || ($v['open_14'] < $time && $v['close_14'] > $time) || ($v['open_15'] < $time && $v['close_15'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 6 :
                    if (($v['open_16'] < $time && $v['close_16'] > $time) || ($v['open_17'] < $time && $v['close_17'] > $time) || ($v['open_18'] < $time && $v['close_18'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 0 :
                    if (($v['open_19'] < $time && $v['close_19'] > $time) || ($v['open_20'] < $time && $v['close_20'] > $time) || ($v['open_21'] < $time && $v['close_21'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                default :
                    $v['state'] = 0;
            }
            //end  @wangchuanyuan

			$v['system_discount'] = isset($discounts[0]) ? $discounts[0] : null;
			$v['merchant_discount'] = isset($discounts[$v['store_id']]) ? $discounts[$v['store_id']] : null;

			$is_have_two_time = 0;//是否是第二时段的配送显示
			if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
				if ($delivery_time = C('config.delivery_time')) {
					$delivery_times = explode('-', $delivery_time);
					$start_time = $delivery_times[0] . ':00';
					$stop_time = $delivery_times[1] . ':00';
					if (!($start_time == $stop_time && $start_time == '00:00:00')) {
						if ($delivery_time2 = C('config.delivery_time2')) {
							$delivery_times2 = explode('-', $delivery_time2);
							$start_time2 = $delivery_times2[0];
							$stop_time2 = $delivery_times2[1];
							if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
								$is_have_two_time = 1;
							}
						}
					}
				}

				if ($is_have_two_time) {
					if ($time <= $stop_time || $time > $stop_time2) {
						$is_have_two_time = 0;
					}
				}

				if ($v['s_is_open_own']) {
					if ($is_have_two_time) {
						//$v['delivery_fee'] = $v['s_free_type2'] == 0 ? 0 : $v['s_delivery_fee2'];
					} else {
						//$v['delivery_fee'] = $v['s_free_type'] == 0 ? 0 : $v['s_delivery_fee'];
					}
				} else {
					//$v['delivery_fee'] = $is_have_two_time ? C('config.delivery_fee2') : C('config.delivery_fee');
				}
			} else {
				if (!($v['delivertime_start'] == $v['delivertime_stop'] && $v['delivertime_start'] == '00:00:00')) {
					if (!($v['delivertime_start2'] == $v['delivertime_stop2'] && $v['delivertime_start2'] == '00:00:00')) {
						$is_have_two_time = 1;
					}
				}

				if ($is_have_two_time) {
					if ($time <= $v['delivertime_stop'] || $time > $v['delivertime_stop2']) {
						$is_have_two_time = 0;
					}
				}

				//$v['delivery_fee'] = $is_have_two_time ? $v['delivery_fee2'] : $v['delivery_fee'];
			}

            //modify garfunkel
            $v['delivery_fee'] = C('config.delivery_distance_1');

			$v['is_have_two_time'] = $is_have_two_time;



// 			if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
// 				if ($v['s_is_open_own']) {
// 					$v['delivery_fee'] = $v['s_free_type'] == 0 ? 0 : $v['s_delivery_fee'];
// 				} else {
// 					$v['delivery_fee'] = C('config.delivery_fee');
// 				}
// 			}
// 			$v['delivery_fee'] = $v['deliver_type'] == 0 || $v['deliver_type'] == 3 ? C('config.delivery_fee') : $v['delivery_fee'];
		}
		$return['shop_list'] = $res;
		$return['total'] = $total;
		if (!$is_wap) {
			$return['totalPage'] = $p->totalPage;
			$return['pagebar'] = $p->show();
		}

// 		echo '<pre/>';$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
// 		print_r($return);die;

		return $return;
	}

	/**
	 * 根据条件获取商家列表
	 * @param array $where
	 * @param number $limit
	 */
	public function get_list_by_ids($ids, $params = array())
	{
		$lat = isset($params['lat']) ? $params['lat'] : 0;
		$long = isset($params['long']) ? $params['long'] : 0;

		if (empty($ids)) return null;

		$sql = "SELECT s.*, m.* FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE m.store_id IN ({$ids})";
		$res = $this->query($sql);
// 		echo $mod->_sql();die;
		// dump($mod);
		$ids = array();
		$store_ids = array();
		foreach ($res as $r) {
			if (!in_array($r['circle_id'], $ids)) {
				$ids[] = $r['circle_id'];
			}
			if (!in_array($r['store_id'], $store_ids)) {
				$store_ids[] = $r['store_id'];
			}
		}
		$discounts = D('Shop_discount')->get_discount_byids($store_ids);
		$temp = array();
		if ($ids) {
			$areas = M("Area")->where(array('area_id' => array('in', $ids)))->select();
			foreach ($areas as $a) {
				$temp[$a['area_id']] = $a;
			}
		}

		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$store_image_class = new store_image();
		$list = array();
		foreach ($res as $v) {
			$v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
			$v['area_name'] = isset($temp[$v['circle_id']]) ? $temp[$v['circle_id']]['area_name'] : '';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['mean_money'] = floatval($v['mean_money']);
			$v['wap_url'] = U('Shop/shop', array('mer_id' => $v['mer_id'], 'store_id' => $v['store_id']));
			$v['deliver'] = $v['deliver_type'] == 2 ? false : true;

			$location2 = $longlat_class->gpsToBaidu($v['lat'], $v['long']);//转换腾讯坐标到百度坐标
			$jl = getDistance($location2['lat'], $location2['lng'], $lat, $long);
			$v['range'] = getRange($jl);

			if (in_array($v['deliver_type'], array(3, 4)) && $jl > $v['delivery_radius'] * 1000) {
				$v['deliver'] = 0;
			}

			$v['state'] = 0;//根据营业时间判断
			$time = date('H:i:s');



//			if ($v['open_1'] == '00:00:00' && $v['close_1'] == '00:00:00') {
//				$v['state'] = 1;
//			} elseif (($v['open_1'] < $time && $v['close_1'] > $time) || ($v['open_2'] < $time && $v['close_2'] > $time) || ($v['open_3'] < $time && $v['close_3'] > $time)) {
//				$v['state'] = 1;
//			}


            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            switch ($date){
                case 1 :
                    if (($v['open_1'] < $time && $v['close_1'] > $time) || ($v['open_2'] < $time && $v['close_2'] > $time) || ($v['open_3'] < $time && $v['close_3'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 2 ://周二
                    if (($v['open_4'] < $time && $v['close_4'] > $time) || ($v['open_5'] < $time && $v['close_5'] > $time) || ($v['open_6'] < $time && $v['close_6'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 3 ://周三
                    if (($v['open_7'] < $time && $v['close_7'] > $time) || ($v['open_8'] < $time && $v['close_8'] > $time) || ($v['open_9'] < $time && $v['close_9'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 4 :
                    if (($v['open_10'] < $time && $v['close_10'] > $time) || ($v['open_11'] < $time && $v['close_11'] > $time) || ($v['open_12'] < $time && $v['close_12'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 5 :
                    if (($v['open_13'] < $time && $v['close_13'] > $time) || ($v['open_14'] < $time && $v['close_14'] > $time) || ($v['open_15'] < $time && $v['close_15'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 6 :
                    if (($v['open_16'] < $time && $v['close_16'] > $time) || ($v['open_17'] < $time && $v['close_17'] > $time) || ($v['open_18'] < $time && $v['close_18'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                case 0 :
                    if (($v['open_19'] < $time && $v['close_19'] > $time) || ($v['open_20'] < $time && $v['close_20'] > $time) || ($v['open_21'] < $time && $v['close_21'] > $time)){
                        $v['state'] = 1;
                    }
                    break;
                default :
                    $v['state'] = 0;
            }
            //end  @wangchuanyuan

			$v['system_discount'] = isset($discounts[0]) ? $discounts[0] : null;
			$v['merchant_discount'] = isset($discounts[$v['store_id']]) ? $discounts[$v['store_id']] : null;

			$is_have_two_time = 0;//是否是第二时段的配送显示

			if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
				if ($delivery_time = C('config.delivery_time')) {
					$delivery_times = explode('-', $delivery_time);
					$start_time = $delivery_times[0];
					$stop_time = $delivery_times[1];
					if (!($start_time == $stop_time && $start_time == '00:00:00')) {
						if ($delivery_time2 = C('config.delivery_time2')) {
							$delivery_times2 = explode('-', $delivery_time2);
							$start_time2 = $delivery_times2[0];
							$stop_time2 = $delivery_times2[1];
							if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
								$is_have_two_time = 1;
							}
						}
					}
				}
				if ($is_have_two_time) {
					if ($time <= $stop_time || $time > $stop_time2) {
						$is_have_two_time = 0;
					}
				}
				if ($v['s_is_open_own']) {
					if ($is_have_two_time) {
						//$v['delivery_fee'] = $v['s_free_type2'] == 0 ? 0 : $v['s_delivery_fee2'];
					} else {
						//$v['delivery_fee'] = $v['s_free_type'] == 0 ? 0 : $v['s_delivery_fee'];
					}
				} else {
					//$v['delivery_fee'] = $is_have_two_time ? C('config.delivery_fee2') : C('config.delivery_fee');
				}
			} else {
				if (!($v['delivertime_start'] == $v['delivertime_stop'] && $v['delivertime_start'] == '00:00:00')) {
					if (!($v['delivertime_start2'] == $v['delivertime_stop2'] && $v['delivertime_start2'] == '00:00:00')) {
						$is_have_two_time = 1;
					}
				}
				if ($is_have_two_time) {
					if ($time <= $v['delivertime_stop'] || $time > $v['delivertime_stop2']) {
						$is_have_two_time = 0;
					}
				}
				//$v['delivery_fee'] = $is_have_two_time ? $v['delivery_fee2'] : $v['delivery_fee'];
			}
			//modify garfunkel
            $v['delivery_fee'] = C('config.delivery_distance_1');

            $v['is_have_two_time'] = $is_have_two_time;

// 			if ($v['deliver_type'] == 0 || $v['deliver_type'] == 3) {
// 				if ($v['s_is_open_own']) {
// 					$v['delivery_fee'] = $v['s_free_type'] == 0 ? 0 : $v['s_delivery_fee'];
// 				} else {
// 					$v['delivery_fee'] = C('config.delivery_fee');
// 				}
// 			}
// 			$v['delivery_fee'] = $v['deliver_type'] == 0 || $v['deliver_type'] == 3 ? C('config.delivery_fee') : $v['delivery_fee'];
			$list[$v['store_id']] = $v;
		}
		$return['shop_list'] = $list;
		return $return;
	}



	public function get_qrcode($id){
		$condition_store['store_id'] = $id;
		$now_store = $this->field('`store_id`,`qrcode_id`')->where($condition_store)->find();
		if(empty($now_store)){
			return false;
		}
		return $now_store;
	}
	public function save_qrcode($id,$qrcode_id){
		$condition_store['store_id'] = $id;
		$data_store['qrcode_id'] = $qrcode_id;
		if($this->where($condition_store)->data($data_store)->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至'.C('config.group_alias_name').'失败！请重试。'));
		}
	}
	public function del_qrcode($id){
		$condition_store['store_id'] = $id;
		$data_store['qrcode_id'] = '';
		if($this->where($condition_store)->data($data_store)->save()){
			return(array('error_code'=>false));
		}else{
			return(array('error_code'=>true,'msg'=>'保存二维码至'.C('config.group_alias_name').'失败！请重试。'));
		}
	}

	public function get_store_by_search($where)
	{
		$cat_id = isset($where['cat_id']) ? $where['cat_id'] : 0;
		$cat_fid = isset($where['cat_fid']) ? $where['cat_fid'] : 0;


		$condition_where = "s.city_id='" . C('config.now_city') . "' AND s.status=1 AND s.store_id=m.store_id AND s.have_shop=1";
		if (C('config.store_shop_auth') == 1) {
			$condition_where .= " AND s.auth>2";
		}
		if (isset($where['key']) && $where['key']) {
			$condition_where .= " AND `s`.`name` LIKE '%{$where['key']}%'";
		}

		if ($cat_fid || $cat_id) {
			if ($cat_fid && $cat_id) {
				$relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id))->select();
			} elseif ($cat_fid) {
				$relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid))->select();
			} else {
				$relation = D('Shop_category_relation')->where(array('cat_id' => $cat_id))->select();
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
				return array('shop_list' => null, 'pagebar' => null, 'total' => 0, 'next_page' => 0);
			}
		}


		$sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE {$condition_where}";
		$count = $this->query($sql_count);
		$total = isset($count[0]['count']) ? $count[0]['count'] : 0;

		$page = isset($where['page']) ? intval($where['page']) : 1;

		$pagesize = 10;
		$totalPage = ceil($total / $pagesize);
		$star = $pagesize * ($page - 1);
		$return['next_page'] = $totalPage > $page ? intval($page + 1) : 0;
		$return['total_page'] = $totalPage;
		$return['total'] = $total;


		$sql = "SELECT `s`.`name`, `s`.`pic_info`, `s`.`store_id`, `m`.`score_mean` FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE {$condition_where} LIMIT {$star}, {$pagesize}";
		$res = $this->query($sql);

		$store_ids = array();
		$store_list = array();
		$store_image_class = new store_image();
		foreach ($res as $v) {
			$v['url'] = U('Mall/store', array('store_id' => $v['store_id']));//C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
			$images = $store_image_class->get_allImage_by_path($v['pic_info']);
			$v['image'] = $images ? array_shift($images) : array();
			$v['goods_count'] = 0;
			$store_ids[] = $v['store_id'];
			$store_list[$v['store_id']] = $v;
		}

		$goods_list = D('Shop_goods')->field('store_id, count(1) as cnt')->where(array('store_id' => array('in', $store_ids), 'status' => 1))->group('store_id')->select();
		foreach ($goods_list as $g) {
			if (isset($store_list[$g['store_id']])) {
				$store_list[$g['store_id']]['goods_count'] = $g['cnt'];
			}
		}
		$return['store_list'] = array_values($store_list);
		return $return;
	}
	
	
	
    /**
     * 根据条件获取快店店铺列表
     * @param array $where array(
     *  'order' => 排序，可选值（只能填写一个）['store_id'(默认)，'score_mean'(好评)，'permoney'(人均消费)]
     *  'cat_id' => 分类ID
     *  'cat_fid' => 父分类ID
     *  'area_id' => 区域ID
     *  'circle_id' => 商圈ID
     *  'key' => 搜索的关键词
     * );
     * @param number $isverify (-1 ：不筛选，0：非签约商家，1：签约商家)
     */
    public function getStores($where = array(), $isverify = -1)
    {
        $order_str = isset($where['order']) ? $where['order'] : 'juli';
        $cat_id = isset($where['cat_id']) ? $where['cat_id'] : 0;
        $cat_fid = isset($where['cat_fid']) ? $where['cat_fid'] : 0;
        $area_id = isset($where['area_id']) ? $where['area_id'] : 0;
        $circle_id = isset($where['circle_id']) ? $where['circle_id'] : 0;
    
        
        $condition_where = "s.city_id='" . C('config.now_city') . "' AND s.status=1 AND s.store_id=m.store_id AND s.have_shop=1";
        if (C('config.store_shop_auth') == 1) {
            $condition_where .= " AND s.auth>2";
        }
    
        if (isset($where['key']) && $where['key']) {
            $condition_where .= " AND `s`.`name` LIKE '%{$where['key']}%'";
        }
    
        $area_id && $condition_where .= " AND `s`.`area_id`={$area_id}";
        $circle_id && $condition_where .= " AND `s`.`circle_id`={$circle_id}";
        
        if ($cat_id) {
            $category = M('Shop_category')->where(array('cat_id' => $cat_id))->find();
        } elseif ($cat_fid) {
            $category = M('Shop_category')->where(array('cat_id' => $cat_fid))->find();
        }
        $show_method = isset($category) && $category ? $category['show_method'] : 2;
    
    
        $condition_field = 's.*, m.*,mm.isverify';
        
        //排序
        $order = '';
        switch($order_str){
            case 'score_mean':
                $order .= '`m`.`score_mean` DESC,`s`.`store_id` DESC';
                break;
            case 'permoney':
                $order .= '`m`.`mean_money` ASC,`s`.`store_id` DESC';
                break;
            case 'store_id':
                $order .= '`s`.`store_id` ASC';
                break;
            default:
                $order .= '`s`.`store_id` ASC';
                break;
        }

        $mod = new Model();
        if ($cat_fid || $cat_id) {
            if ($cat_fid && $cat_id) {
                $relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid, 'cat_id' => $cat_id))->select();
            } elseif ($cat_fid) {
                $relation = D('Shop_category_relation')->where(array('cat_fid' => $cat_fid))->select();
            } else {
                $relation = D('Shop_category_relation')->where(array('cat_id' => $cat_id))->select();
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
                return array('shop_list' => null, 'pagebar' => null, 'total' => 0, 'next_page' => 0);
            }
        }

        if ($isverify != -1) {
            $condition_where .= ' AND mm.isverify=' . $isverify;
            $sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant as mm ON mm.mer_id=s.mer_id WHERE {$condition_where}";
        } else {
            $sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id WHERE {$condition_where}";
        }
        $count = $this->query($sql_count);
        
        $total = isset($count[0]['count']) ? $count[0]['count'] : 0;
        $nowPage = isset($where['p']) ? max($where['p'], 1) : 1;
        $pagesize = isset($where['pagesize']) ? intval($where['pagesize']) : 10;
        $pagesize = $pagesize > 0 && $pagesize < 100 ? $pagesize : 10;
        $star = ($nowPage - 1) * $pagesize;
        
        $sql = "SELECT {$condition_field} FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop as m ON m.store_id=s.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant as mm ON mm.mer_id=s.mer_id WHERE {$condition_where} ORDER BY {$order} LIMIT {$star}, {$pagesize}";
        $res = $this->query($sql);
        $store_ids = array();
        foreach ($res as $r) {
            if (!in_array($r['store_id'], $store_ids)) {
                $store_ids[] = $r['store_id'];
            }
        }

        $items = D('Shop_category')->field(true)->order('cat_id DESC')->select();
        $tmpMap = array();
        foreach ($items as $item) {
            $tmpMap[$item['cat_id']] = $item;
        }

        $store_image_class = new store_image();
        foreach ($res as &$v) {
            $v['url'] = C('config.site_url') . '/shop/' . $v['store_id'] . '.html';
            $images = $store_image_class->get_allImage_by_path($v['pic_info']);
            $v['image'] = $images ? array_shift($images) : array();
            $v['mean_money'] = floatval($v['mean_money']);
            $catNamef = isset($tmpMap[$v['cat_fid']]) ? $tmpMap[$v['cat_fid']]['cat_name'] : '';
            $catName = isset($tmpMap[$v['cat_id']]) ? $tmpMap[$v['cat_id']]['cat_name'] : '';
            $v['cat_name'] = $catNamef ? $catNamef . ($catName ? '-' . $catName : '') : $catName;
        }
        
        $return['shop_list'] = $res;
        $return['total'] = $total;
        $return['total_page'] = ceil($total / $pagesize);
        return $return;
    }

    /**
     * Api 获取店铺信心整理
     * garfunkel add
     * @param array $where
     * @param int $is_wap
     * @param int $type 1 链接get_list_by_option
     * @param $limit
     * @param int $page
     * @param int $page_count
     * @return array
     */
    public function get_list_arrange($where = array(), $is_wap = 1,$type = 1,$limit,$page = 1,$lat=0,$long=0)
    {
        switch ($type){
            case 1:
                $t_list = $this->get_list_by_option($where,$is_wap);
                break;

            default:
                $t_list = array();
                break;

        }

//        $total = $t_list['total'];
//        $total_page = ceil($total / $limit);
//        $page_begin = ($page - 1)*$limit;
//        $page_end = $page_begin + $limit;
//        die($total.'-'.$limit.'---'.$total_page.'-'.$page_begin.'-'.$page_end.'--'.count($t_list['shop_list']));
//        //$page_max = floor($limit/$page_count)+($limit%$page_count>0?1:0);
//
        $n = 1;
//        $return = array();
//        if($page > $total_page || $page < 0){
//            return $return;
//        }
        foreach ($t_list['shop_list'] as $row) {

//            if(!($n > $page_begin && $n <= $page_end)){
//                $n++;
//                continue;
//            }

            $n++;
            $temp = array();
            $temp['site_id'] = $row['store_id'];
            $temp['logo'] = $row['image'];
            //modify garfunkel 判断语言
            $temp['site_name'] = lang_substr($row['name'],C('DEFAULT_LANG'));
            $temp['shop_sale'] = $row['sale_count'];
            $temp['store_theme'] = $row['store_theme'];
            $temp['isverify'] = $row['isverify'];
            $temp['juli_wx'] = $row['juli'];
            $temp['range'] = $row['range'];
            $temp['score'] = $row['score_mean'];
            $temp['merchant_store_month_sale_count'] = $row['merchant_store_month_sale_count'];//月售量
            $temp['delivery'] = $row['deliver'];//是否支持配送
            $temp['delivery'] = $temp['delivery'] ? true : false;
            $temp['time'] = $row['send_time'];//配送时长
            $temp['delivery_price'] = floatval($row['basic_price']);//起送价
            if($lat != 0 && $long != 0){
                $temp['delivery_money'] = getDeliveryFee($row['lat'],$row['long'],$lat,$long);
            }else{
                $temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
            }
            //modify garfunkel
            $temp['pack_fee'] = $row['pack_fee'];
            //
            $temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
            $temp['is_close'] = 1;

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
            //$temp['coupon_list'] = $this->parseCoupon($temp['coupon_list'],'array');
            $return['list'][] = $temp;
        }
        $return['count'] = $t_list['total'];

        return $return;
    }
}
?>