<?php
class Foodshop_goodsModel extends Model
{

	public function save_post_form($goods, $store_id)
	{
		$goods_id = isset($goods['goods_id']) ? intval($goods['goods_id']) : 0;
		
		$data = array('name' => $goods['name']);
		$data['unit'] = $goods['unit'];
		$data['old_price'] = empty($goods['old_price']) ? $goods['price'] : $goods['old_price'];
		$data['price'] = $goods['price'];
		$data['extra_pay_price'] = isset($goods['extra_pay_price']) ? $goods['extra_pay_price'] : 0;
		$data['seckill_price'] = isset($goods['seckill_price']) ? $goods['seckill_price'] : 0;
		$data['seckill_stock'] = isset($goods['seckill_stock']) ? $goods['seckill_stock'] : -1;
		$data['seckill_type'] = isset($goods['seckill_type']) ? $goods['seckill_type'] : 0;
		$data['seckill_open_time'] = isset($goods['seckill_open_time']) ? $goods['seckill_open_time'] : 0;
		$data['seckill_close_time'] = isset($goods['seckill_close_time']) ? $goods['seckill_close_time'] : 0;
// 		$data['packing_charge'] = $goods['packing_charge'];
		$data['stock_num'] = isset($goods['stock_num']) ? $goods['stock_num'] : -1;
		$data['sort'] = isset($goods['sort']) ? $goods['sort'] : 0;
		$data['status'] = $goods['status'];
		$data['print_id'] = $goods['print_id'];
		$data['sort_id'] = $goods['sort_id'];
		$data['des'] = $goods['des'];
		$data['image'] = $goods['pic'];
		$data['number'] = $goods['number'];
		$data['is_must'] = $goods['is_must'];
		$data['is_hot'] = $goods['is_hot'];
		$data['store_id'] = $store_id;
		$data['last_time'] = $goods['last_time'];
		
		$delete_spec_ids = array();
		$delete_spec_val_ids = array();
		$delete_properties_ids = array();
		
		$spec_obj = M('Foodshop_goods_spec'); //规格表
		$spec_value_obj = M('Foodshop_goods_spec_value');//规格对应的属性值
		$properties_obj = M('Foodshop_goods_properties');//属性表
		
		if ($check_data = $this->field(true)->where(array('store_id' => $store_id, 'goods_id' => $goods_id))->find()) {
			if ($this->where(array('store_id' => $store_id, 'goods_id' => $goods_id))->save($data)) {
				//查找已有的属性和规格
				$old_spec = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->select();
				foreach ($old_spec as $os) {
					$delete_spec_ids[] = $os['id'];
				}
				if ($delete_spec_ids) {
					$old_spec_val = $spec_value_obj->field(true)->where(array('sid' => array('in', $delete_spec_ids)))->select();
					foreach ($old_spec_val as $osv) {
						$delete_spec_val_ids[] = $osv['id'];
					}
				}
				$old_properties = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->select();
				foreach ($old_properties as $op) {
					$delete_properties_ids[] = $op['id'];
				}
				unset($old_spec, $old_spec_val, $old_properties);
			} else {
				return false;
			}
		} else {
			$goods_id = $this->add($data);
			if (empty($goods_id)) return false;
		}

		//配置属性
		$properties = array();
		$spec = array();
		$list = array();
		
		$data_spec = array('store_id' => $store_id, 'goods_id' => $goods_id);
		foreach ($goods['spec_id'] as $k => $id) {//规格id集合
			$name = $data_spec['name'] = isset($goods['specs'][$k]) ? $goods['specs'][$k] : '';//规格名称
			$spec_val_id = isset($goods['spec_val_id'][$k]) ? $goods['spec_val_id'][$k] : array();//规格属性值的ID集合
			$spec_val = isset($goods['spec_val'][$k]) ? $goods['spec_val'][$k] : array();//规格属性值的名称集合
			
			$list[$k] = array();
			if ($spec_obj->field(true)->where(array('id' => $id, 'goods_id' => $goods_id))->find()) {
				$spec_obj->where(array('id' => $id))->save($data_spec);
			} else {
				$id = $spec_obj->add($data_spec);
			}
			if ($id) {//规格id
				$delete_spec_ids = array_diff($delete_spec_ids, array($id));
				
				$data_spec_val = array('sid' => $id);
				foreach ($spec_val_id as $i => $vid) {
					$data_spec_val['name'] = $spec_val[$i];
					if ($spec_value_obj->where(array('id' => $vid, 'sid' => $id))->find()) {
						$spec_value_obj->where(array('id' => $vid))->save($data_spec_val);
					} else {
						$vid = $spec_value_obj->add($data_spec_val);
					}
					if ($vid) {
						$delete_spec_val_ids = array_diff($delete_spec_val_ids, array($vid));
// 						$list[$k][$i] = array('spec_id' => $id, 'spec_name' => $name, 'spec_val_id' => $vid, 'spec_val_name' => $spec_val[$i]);
						$list[$k][$i] = $vid;
					}
				}
			}
		}
		$spec_value = array();
		$this->format_str($list, 0, array(), $spec_value);
		


		$properties = array();
		$is_properties = 0;
		foreach ($goods['properties_id'] as $pi => $pid) {//属性id集合
			$is_properties = 1;
			$name = isset($goods['properties'][$pi]) ? $goods['properties'][$pi] : '';//属性名称
			$num = isset($goods['properties_num'][$pi]) ? intval($goods['properties_num'][$pi]) : 1;//属性值可选的数量
			$val = isset($goods['properties_val'][$pi]) ? implode(',', $goods['properties_val'][$pi]) : '';//属性的属性值
			if ($properties_obj->field(true)->where(array('goods_id' => $goods_id, 'id' => $pid))->find()) {
				$properties_obj->where(array('goods_id' => $goods_id, 'id' => $pid))->save(array('name' => $name, 'val' => $val, 'num' => $num));
			} else {
				$pid = $properties_obj->add(array('goods_id' => $goods_id, 'name' => $name, 'val' => $val, 'num' => $num));
			}
			if ($pid) {
				$delete_properties_ids = array_diff($delete_properties_ids, array($pid));
				
				$properties[] = array('id' => $pid, 'name' => $name, 'val' => $val);
			}
		}		
		
		$specs = '';
		$pre = '';
		foreach ($spec_value as $k => $v) {
			$old_price = isset($goods['old_prices'][$k]) ? $goods['old_prices'][$k] : 0;
			$number = isset($goods['numbers'][$k]) ? $goods['numbers'][$k] : '';
			$price = isset($goods['prices'][$k]) ? $goods['prices'][$k] : 0;
			$seckill_price = isset($goods['seckill_prices'][$k]) ? $goods['seckill_prices'][$k] : 0;
			$stock_num = isset($goods['stock_nums'][$k]) ? intval($goods['stock_nums'][$k]) : 0;
			$old_price = $old_price ? $old_price : $price;
			$specs .= $pre . $v . '|' . $old_price . ':' . $price . ':' . $seckill_price . ':' . $stock_num;
			if ($properties) {
				$specs .= '|';
				$ppre = '';
				foreach ($properties as $ti => $ps) {
					$num = isset($goods['num' . $ti][$k]) && $goods['num' . $ti][$k] ? intval($goods['num' . $ti][$k]) : 1;
					$specs .= $ppre . $ps['id'] . '=' . $num;
					$ppre = ':';
				}
			}
			$number && $specs .= '|' . $number;
			$pre = '#';
		}
		//规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数#规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数#...#规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数
		if ($this->where(array('goods_id' => $goods_id))->save(array('spec_value' => $specs, 'is_properties' => $is_properties, 'last_time' => $data['last_time'] + 1))) {
			$delete_spec_ids && $spec_obj->where(array('id' => array('in', $delete_spec_ids)))->delete();
			$delete_spec_val_ids && $spec_value_obj->where(array('id' => array('in', $delete_spec_val_ids)))->delete();
			$delete_properties_ids && $properties_obj->where(array('id' => array('in', $delete_properties_ids)))->delete();
			//配置属性
			return $goods_id;
		} else {
			return false;
		}
	}	
	
	private function format_str($a, $i, $str, &$return)
	{
		if ($i == 0) {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$t = $str ? $str . ':' : '';
				if ($ii == count($a)) {
					$return[] = $t . $val;
				} else {
					$this->format_str($a, $ii, $t . $val, $return);
				}
			}
		} else if ($i == count($a) - 1) {
			foreach ($a[$i] as $val) {
				$t = $str ? $str . ':' : '';
				$return[] = $t . $val;
			}
		} else {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$t = $str ? $str . ':' : '';
				$this->format_str($a, $ii, $t . $val, $return);
			}
		}
	}
	
	
	public function format_spec_value($str, $goods_id, $is_prorerties = 1)
	{
		if ($is_prorerties || $str) {
			$properties_obj = M('Foodshop_goods_properties');
			$goods_properties_temp = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->select();
			$goods_properties_list = array();
			foreach ($goods_properties_temp as $goods_p) {
				$goods_p['val'] = explode(',', $goods_p['val']);
				$goods_properties_list[$goods_p['id']] = $goods_p;
			}
			unset($goods_properties_temp);
		}
		
		if ($str) {
			$spec_obj = M('Foodshop_goods_spec'); //规格表
			$spec_value_obj = M('Foodshop_goods_spec_value');//规格对应的属性值
			$goods_spec_temp = $spec_obj->field(true)->where(array('goods_id' => $goods_id))->select();
			$goods_spec_list = array();
			$specids = array();
			foreach ($goods_spec_temp as $goods_t) {
				$specids[] = $goods_t['id'];
				$goods_spec_list[$goods_t['id']] = $goods_t;
			}
			unset($goods_spec_temp);
			$spec_valuse_list = array();
			if ($specids) {
				$spec_valuse_temp = $spec_value_obj->field(true)->where(array('sid' => array('in', $specids)))->select();
				foreach ($spec_valuse_temp as $v_temp) {
					$spec_valuse_list[$v_temp['id']] = $v_temp;
					$goods_spec_list[$v_temp['sid']]['list'][$v_temp['id']] = $v_temp;
				}
				unset($spec_valuse_temp, $specids);
			}
			
			$return = array();
			$json = array();
			$spec_array = explode('#', $str);
			$p_ids = array();
			foreach ($spec_array as $row) {
				$row_array = explode('|', $row);
				$spec_ids = explode(':', $row_array[0]);
				$t_index = '';
				$t_pre = '';
				$spec_data = array();
				foreach ($spec_ids as $id) {
					$t_index .= $t_pre . 'id_' . $id;
					$t_pre = '_';
					$spec_data[] = array('spec_val_id' => $id, 'spec_val_name' => isset($spec_valuse_list[$id]['name']) ? $spec_valuse_list[$id]['name'] : '');
				}
				$index = implode('_', $spec_ids);
				
				$return[$index]['index'] = $t_index;
				$return[$index]['spec'] = $spec_data;
				
				$prices = explode(':', $row_array[1]);
				$return[$index]['old_price'] = floatval($prices[0]);
				$return[$index]['price'] = floatval($prices[1]);
				$return[$index]['seckill_price'] = floatval($prices[2]);
				$return[$index]['stock_num'] = $prices[3];
				$return[$index]['number'] = isset($row_array[3]) ? $row_array[3] : '';
				
				if (isset($row_array[2])) {
					$p_data = array();
					$tdata = array();
					$properties = explode(':', $row_array[2]);
					foreach ($properties as $k => $pro) {
						$pro_array = explode('=', $pro);
						$p_data[] = array('id' => $pro_array[0], 'num' => $pro_array[1], 'name' => isset($goods_properties_list[$pro_array[0]]['name']) ? $goods_properties_list[$pro_array[0]]['name'] : '');
						$tdata['num' . $k . '[]'] = $pro_array[1];
					}
					$return[$index]['properties'] = $p_data;
					$json[$t_index] = $tdata;
				}
				$json[$t_index]['old_prices[]'] = $prices[0];
				$json[$t_index]['prices[]'] = $prices[1];
				$json[$t_index]['seckill_prices[]'] = $prices[2];
				$json[$t_index]['stock_nums[]'] = $prices[3];
				$json[$t_index]['numbers[]'] = isset($row_array[3]) ? $row_array[3] : '';
			}
		}
		
		$data = array();
		$goods_spec_list && $data['spec_list'] = $goods_spec_list;
		$goods_properties_list && $data['properties_list'] = $goods_properties_list;
		$return && $data['list'] = $return;
		$json && $data['json'] = $json;
		return $data = $data ? $data : null;
	}
	
	
	public function get_list_by_storeid($store_id)
	{
		$database_goods_sort = D('Foodshop_goods_sort');
		$condition_goods_sort['store_id'] = $store_id;
// 		$count_sort = $database_goods_sort->where($condition_goods_sort)->count();
		$sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
		$sort_image_class = new goods_sort_image();
		$s_list = array();
		$today = date('w');
		foreach ($sort_list as $value) {
			if (!empty($value['is_weekshow'])) {
				$week_arr = explode(',',$value['week']);
				if (!in_array($today, $week_arr)) {
					continue;
				}
				$week_str = '';
				foreach ($week_arr as $k=>$v){
					$week_str .= $this->get_week($v).' ';
				}
				$value['week_str'] = $week_str;
			}
			$value['see_image'] = $sort_image_class->get_image_by_path($value['image'],$this->config['site_url'],'s');
			$s_list[$value['sort_id']] = $value;
		}

		$goods_image_class = new foodshop_goods_image();
		$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1, 'is_must' => 0))->order('sort DESC, goods_id ASC')->select();
		foreach ($g_list as $row) {
			if ($row['seckill_type'] == 1) {
				$now_time = date('H:i');
				$open_time = date('H:i', $row['seckill_open_time']);
				$close_time = date('H:i', $row['seckill_close_time']);
			} else {
				$now_time = time();
				$open_time = $row['seckill_open_time'];
				$close_time = $row['seckill_close_time'];
			}
			$row['is_seckill_price'] = false;
			$row['o_price'] = floatval($row['price']);
			if(C('config.open_extra_price')){

				$row['extra_pay_price'] = floatval($row['extra_pay_price']);
			}else{
				$row['extra_pay_price'] = 0;
			}
				$row['extra_price_name'] = C('config.extra_price_alias_name');

			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
				$row['price'] = floatval($row['seckill_price']);
				$row['is_seckill_price'] = true;
			} else {
				$row['price'] = floatval($row['price']);
			}

// 			$row['price'] = floatval($row['price']);
			$row['old_price'] = floatval($row['old_price']);
			$row['seckill_price'] = floatval($row['seckill_price']);
			$tmp_pic_arr = explode(';', $row['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$row['pic_arr'][$key]['title'] = $value;
				$row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
			}
			
			$return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
// 			$row['json'] = isset($return['json']) ? json_encode($return['json']) : '';
			$row['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
			$row['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
			$row['list'] = isset($return['list']) ? $return['list'] : '';
			
			if (isset($s_list[$row['sort_id']])) {
				if (isset($s_list[$row['sort_id']]['goods_list'])) {
					$s_list[$row['sort_id']]['goods_list'][] = $row;
				} else {
					$s_list[$row['sort_id']]['goods_list'] = array($row);
				}
			}
		}
		foreach ($s_list as $k => $r) {
			if (!isset($r['goods_list'])) {
				unset($s_list[$k]);
			}
		}
		return $s_list;
	}
	
	protected function get_week($num){
		switch($num){
			case 1:
				return '星期一';
			case 2:
				return '星期二';
			case 3:
				return '星期三';
			case 4:
				return '星期四';
			case 5:
				return '星期五';
			case 6:
				return '星期六';
			case 0:
				return '星期日';
			default:
				return '';
		}
	}
	
	public function get_goods_by_id($goods_id)
	{
		$now_goods = $this->field(true)->where(array('goods_id' => $goods_id))->find();
		if(empty($now_goods)){
			return false;
		}
		$shop = D('Merchant_store_foodshop')->where(array('store_id' => $now_goods['store_id']))->find();
		if (empty($shop)) return false; 
		$stock_type = $shop['stock_type'];
		if(!empty($now_goods['image'])){
			$goods_image_class = new goods_image();
			$tmp_pic_arr = explode(';', $now_goods['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$now_goods['pic_arr'][$key]['title'] = $value;
				$now_goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 'm');
			}
		}

		if ($now_goods['seckill_type'] == 1) {
			$now_time = date('H:i');
			$open_time = date('H:i', $now_goods['seckill_open_time']);
			$close_time = date('H:i', $now_goods['seckill_close_time']);
		} else {
			$now_time = time();
			$open_time = $now_goods['seckill_open_time'];
			$close_time = $now_goods['seckill_close_time'];
		}
		$now_goods['is_seckill_price'] = false;
		$now_goods['o_price'] = floatval($now_goods['price']);
		if ($open_time < $now_time && $now_time < $close_time && floatval($now_goods['seckill_price']) > 0) {
			$now_goods['price'] = floatval($now_goods['seckill_price']);
			$now_goods['is_seckill_price'] = true;
		} else {
			$now_goods['price'] = floatval($now_goods['price']);
		}
		
		$now_goods['old_price'] = floatval($now_goods['old_price']);
		$now_goods['seckill_price'] = floatval($now_goods['seckill_price']);
		$now_goods['packing_charge'] = floatval($now_goods['packing_charge']);
		
		
		$return = $this->format_spec_value($now_goods['spec_value'], $now_goods['goods_id'], $now_goods['is_properties']);
		$now_goods['json'] = isset($return['json']) ? json_encode($return['json']) : '';
		$now_goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
		$now_goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
		$now_goods['list'] = isset($return['list']) ? $return['list'] : '';
		
		$today = date('Ymd');
		
		$now_goods['sell_day'] = $stock_type ? $today : $now_goods['sell_day'];
		$now_goods['today_sell_spec'] = json_decode($now_goods['today_sell_spec'], true);
		foreach ($now_goods['list'] as $key => &$row) {
			$t_count = isset($now_goods['today_sell_spec'][$key]) ? intval($now_goods['today_sell_spec'][$key]) : 0;
			if ($now_goods['sell_day'] == $today) {
				$row['stock_num'] = $row['stock_num'] == -1 ? -1 : (intval($row['stock_num'] - $t_count) > 0 ? intval($row['stock_num'] - $t_count) : 0);
			}
		}
		
		if ($now_goods['sell_day'] == $today) {
			$now_goods['stock_num'] = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
		}
		return $now_goods;
	}
	/**
	 * 检查库存
	 * @param int $goods_id
	 * @param int $num
	 * @param string $spec_ids = 'id_id'
	 * @return multitype:number string |multitype:number unknown |multitype:number string Ambigous <number, mixed>
	 */
	public function check_stock($goods_id, $num, $spec_ids = '', $stock_type = 0)
	{
		$now_goods = $this->field(true)->where(array('goods_id' => $goods_id))->find();
		$image = '';
		if(!empty($now_goods['image'])){
			$goods_image_class = new goods_image();
			$tmp_pic_arr = explode(';', $now_goods['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				if (empty($image)) {
					$image = $goods_image_class->get_image_by_path($value, 's');
					break;
				}
			}
		}
		$stock_num = 0;
		if (empty($now_goods)) return array('status' => 0, 'msg' => '商品不存在');
		$price = floatval($now_goods['price']);
		/**$today = date('Ymd');
		$now_goods['sell_day'] = $stock_type ? $today : $now_goods['sell_day'];
		
		if ($now_goods['seckill_type'] == 1) {
			$now_time = date('H:i');
			$open_time = date('H:i', $now_goods['seckill_open_time']);
			$close_time = date('H:i', $now_goods['seckill_close_time']);
		} else {
			$now_time = time();
			$open_time = $now_goods['seckill_open_time'];
			$close_time = $now_goods['seckill_close_time'];
		}
		if ($open_time < $now_time && $now_time < $close_time && floatval($now_goods['seckill_price']) > 0) {
			$price = floatval($now_goods['seckill_price']);
		} else {
			$price = floatval($now_goods['price']);
		}**/
		
		
// 		$price = $now_goods['price'];
		if ($spec_ids && $now_goods['spec_value']) {
			$return = $this->format_spec_value($now_goods['spec_value'], $now_goods['goods_id'], $now_goods['is_properties']);
			$list = isset($return['list']) ? $return['list'] : '';
			if (isset($list[$spec_ids])) {
				$price = $list[$spec_ids]['price'];
				$number = $list[$spec_ids]['number'];
				/**$today_sell_spec = json_decode($now_goods['today_sell_spec'], true);
				
				if ($now_goods['seckill_type'] == 1) {
					$now_time = date('H:i');
					$open_time = date('H:i', $now_goods['seckill_open_time']);
					$close_time = date('H:i', $now_goods['seckill_close_time']);
				} else {
					$now_time = time();
					$open_time = $now_goods['seckill_open_time'];
					$close_time = $now_goods['seckill_close_time'];
				}
				if ($open_time < $now_time && $now_time < $close_time && $list[$spec_ids]['seckill_price'] > 0) {
					$price = $list[$spec_ids]['seckill_price'];
// 					$price = floatval($now_goods['seckill_price']);
				} else {
					$price = $list[$spec_ids]['price'];
// 					$price = floatval($now_goods['price']);
				}
				$number = $list[$spec_ids]['number'];
				if ($today == $now_goods['sell_day']) {
					$sell_count = isset($today_sell_spec[$spec_ids]) ? intval($today_sell_spec[$spec_ids]) : 0;
					$stock_num = $list[$spec_ids]['stock_num'] == -1 ? -1 : (intval($list[$spec_ids]['stock_num'] - $sell_count) > 0 ? intval($list[$spec_ids]['stock_num'] - $sell_count) : 0);
				} else {
					$stock_num = $list[$spec_ids]['stock_num'];
				}**/
			} else {
				return array('status' => 0, 'msg' => '您选择的规格可能被商家修改了');
			}
		} else {
			if ($today == $now_goods['sell_day']) {
				$stock_num = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
			} else {
				$stock_num = $now_goods['stock_num'];
			}
			$number = $now_goods['number'];
		}
		return array('status' => 1, 'num' => $num, 'price' => $price, 'image' => $image, 'unit' => $now_goods['unit'], 'number' => $number, 'sort_id' => $now_goods['sort_id']);
		if ($stock_num == -1) {
			return array('status' => 1, 'num' => $num, 'price' => $price, 'image' => $image, 'packing_charge' => $now_goods['packing_charge'], 'unit' => $now_goods['unit'], 'number' => $number, 'sort_id' => $now_goods['sort_id']);
		} elseif ($stock_num == 0) {
			return array('status' => 0, 'msg' => '商品库存不足');
		} elseif ($stock_num - $num >= 0) {
			return array('status' => 1, 'num' => $num, 'price' => $price, 'image' => $image, 'packing_charge' => $now_goods['packing_charge'], 'unit' => $now_goods['unit'], 'number' => $number, 'sort_id' => $now_goods['sort_id']);
		} else {
			return array('status' => 2, 'num' => $stock_num, 'msg' => '最多能购买' . $stock_num . $now_goods['unit'], 'number' => $number, 'price' => $price, 'image' => $image, 'packing_charge' => $now_goods['packing_charge'], 'unit' => $now_goods['unit'], 'sort_id' => $now_goods['sort_id']);
		}
	}
	
	/**
	 * 更新库存
	 * $id_index = 1_1
	 */
	public function update_stock($goods_id, $num, $id_index = '')
	{
		static $shops;
		$today = date('Ymd');
		$now_goods = $this->field(true)->where(array('goods_id' => $goods_id))->find();
		if (empty($now_goods)) return array('status' => 0, 'msg' => '商品不存在');
		
		if (isset($shops[$now_goods['store_id']])) {
			$shop = $shops[$now_goods['store_id']];
		} else {
			$shop = D('Merchant_store_foodshop')->field(true)->where(array('store_id' => $now_goods['store_id']))->find();
			$shops[$shop['store_id']] = $shop;
		}
		
		$now_goods['sell_day'] = $shop['stock_type'] ? $today : $now_goods['sell_day'];
		
		$today_sell_count = $now_goods['today_sell_count'];
		$sell_count = $now_goods['sell_count'];
		$today_sell_spec = $now_goods['today_sell_spec'] ? json_decode($now_goods['today_sell_spec'], true) : '';
		
		if ($id_index) {
			isset($today_sell_spec[$id_index]) || $today_sell_spec[$id_index] = 0;
			if ($today == $now_goods['sell_day']) {
				$today_sell_spec[$id_index] += $num;
				$today_sell_count += $num;
			} else {
				$today_sell_spec[$id_index] = $num;
				$today_sell_count = $num;
			}
			$sell_count += $num;
			$today_sell_spec[$id_index] = max(0, $today_sell_spec[$id_index]);
		} else {
			if ($today == $now_goods['sell_day']) {
				$today_sell_count += $num;
			} else {
				$today_sell_count = $num;
			}
			$sell_count += $num;
			$today_sell_count = max(0, $today_sell_count);
		}
		$sell_count = max(0, $sell_count);
		$today_sell_count = max(0, $today_sell_count);
		
		$this->where(array('goods_id' => $goods_id))->save(array('sell_day' => $today, 'sell_count' => $sell_count, 'today_sell_count' => $today_sell_count, 'today_sell_spec' => $today_sell_spec ? json_encode($today_sell_spec) : ''));
	}
	
	public function format_cart($data, $store_id, $order_id)
	{
		$new_goods_list = array();
		$total = 0;
		$price = 0;
		foreach ($data as $row) {
			$goods_id = $row['goods_id'];
			$num = $row['num'];
			$spec_ids = array();
			$str_s = array();
			$str_p = array();
			foreach ($row['params'] as $r) {
				if ($r['type'] == 'spec') {
					foreach ($r['data'] as $d) {
						$spec_ids[] = $d['id'];
						$str_s[] = $d['name'];
					}
				} else {
					foreach ($r['data'] as $d) {
						$str_p[] = $d['name'];
					}
				}
			}
				
			$spec_str = $spec_ids ? implode('_', $spec_ids) : '';
				
				
			$t_return = $this->check_stock($goods_id, $num, $spec_str);
			if ($t_return['status'] == 0) {
				return array('err_code' => true, 'msg' => $t_return['msg']);
			}
			
			$total += $num;
			$price += $t_return['price'] * $num;
			// 			$packing_charge += $t_return['packing_charge'] * $num;
			// 			$t_discount = isset($sorts_discout[$t_return['sort_id']]) && $sorts_discout[$t_return['sort_id']] ? $sorts_discout[$t_return['sort_id']] : 100;
			// 			$store_discount_money += $num * $t_return['price'] * $t_discount / 100;
			$extra_price = $row['extra_price'];
			$str = '';
			$str_s && $str = implode(',', $str_s);
			$str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
			$_index = $goods_id;
			if (strlen($str) > 0) {
				$_index = $goods_id . '_' . md5($str);
			}
			$new_goods_list[$_index] = array('name' => $row['name'], 'store_id' => $store_id, 'order_id' => $order_id, 'num' => $num, 'goods_id' => $goods_id, 'price' => floatval($t_return['price']), 'number' => $t_return['number'], 'unit' => $t_return['unit'], 'sort_id' => $t_return['sort_id'], 'spec' => $str, 'spec_id' => $spec_str,'extra_price'=>$extra_price);
		}
		return array('err_code' => false, 'data' => $new_goods_list, 'total' => $total, 'price' => $price);
	}
	
	
	public function format_spec_ids($goods, $params)
	{
		$_sids = explode('_', $goods['spec_id']);
		$detail_names = explode(';', $goods['spec']);
		$specs = D('Foodshop_goods_spec')->where(array('goods_id' => $goods['goods_id']))->select();
		$sids = array();
		$temp = array();
		foreach ($specs as $s) {
			$sids[] = $s['id'];
			$temp[$s['id']] = $s;
		}
		if ($sids) {
			$spec_vals = D('Foodshop_goods_spec_value')->where(array('sid' => array('in', $sids)))->select();
			foreach ($spec_vals as $sv) {
				if (in_array($sv['id'], $_sids)) {
					$params[] = array('id' => $sv['sid'], 'name' => $temp[$sv['sid']]['name'], 'type' => 'spec', 'data' => array(array('id' => $sv['id'], 'name' => $sv['name'])));
				}
			}
		}
		return $params;
	}
	
	public function format_properties_ids($goods, $params)
	{
		$detail_names = explode(';', $goods['spec']);
		if ($goods['spec_id']) {
			if (count($detail_names) > 1) {
				$detail_names = $detail_names[1];
			} else {
				$detail_names = '';
			}
		} else {
			$detail_names = $detail_names[0];
		}
		if (empty($detail_names)) return $params;
		$detail_names = explode(',', $detail_names);
		$properties = D('Foodshop_goods_properties')->where(array('goods_id' => $goods['goods_id']))->select();
		foreach ($properties as $pv) {
			$vals = explode(',', $pv['val']);
			$data = '';
			foreach ($vals as $i => $v) {
				if (in_array($v, $detail_names)) {
					$data[] = array('id' => $i, 'name' => $v);
				}
			}
			$params[] = array('id' => $pv['id'], 'name' => $pv['name'], 'type' => 'properties', 'data' => $data);
		}
		return $params;
	}
	
}
?>