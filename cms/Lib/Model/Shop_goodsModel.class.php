<?php
class Shop_goodsModel extends Model
{

	public function save_post_form($goods, $store_id)
	{
		$goods_id = isset($goods['goods_id']) ? intval($goods['goods_id']) : 0;
		
		$data = array('name' => $goods['name']);
		$data['unit'] = $goods['unit'];
		$data['old_price'] = empty($goods['old_price']) ? $goods['price'] : $goods['old_price'];
		$data['cost_price'] = empty($goods['cost_price']) ? 0 : $goods['cost_price'];
		$data['price'] = $goods['price'];
		$data['extra_pay_price'] = floatval($goods['extra_pay_price']);
		$data['seckill_price'] = $goods['seckill_price'];
		$data['seckill_stock'] = $goods['seckill_stock'];
		$data['seckill_type'] = $goods['seckill_type'];
		$data['seckill_open_time'] = $goods['seckill_open_time'];
		$data['seckill_close_time'] = $goods['seckill_close_time'];
		$data['packing_charge'] = $goods['packing_charge'];
		$data['stock_num'] = $goods['stock_num'];
		$data['sort'] = $goods['sort'];
		$data['status'] = $goods['status'];
		$data['print_id'] = $goods['print_id'];
		$data['sort_id'] = $goods['sort_id'];
		$data['des'] = $goods['des'];
		$data['image'] = empty($goods['pic']) ? '' : $goods['pic'];
		$data['number'] = $goods['number'];
		$data['store_id'] = $store_id;
		//garfunkel add
		$data['tax_num'] = $goods['tax_num'];
		$data['deposit_price'] = $goods['deposit_price'];
		$data['allergens'] = $goods['allergens'];

		$data['freight_template'] = intval($goods['freight_template']);
		$data['freight_type'] = intval($goods['freight_type']);
		$data['freight_value'] = floatval($goods['freight_value']);
		
		$data['last_time'] = time();
		
		//2016-11-08新增系统的商品分类▽
		$data['cat_fid'] = intval($goods['cat_fid']);
		$data['cat_id'] = intval($goods['cat_id']);
		//2016-11-08新增系统的商品分类△
		
		$delete_spec_ids = array();
		$delete_spec_val_ids = array();
		$delete_properties_ids = array();
		
		$spec_obj = M('Shop_goods_spec'); //规格表
		$spec_value_obj = M('Shop_goods_spec_value');//规格对应的属性值
		$properties_obj = M('Shop_goods_properties');//属性表
		
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
			$cost_price = isset($goods['cost_prices'][$k]) ? $goods['cost_prices'][$k] : 0;
			$number = isset($goods['numbers'][$k]) ? $goods['numbers'][$k] : '';
			$price = isset($goods['prices'][$k]) ? $goods['prices'][$k] : 0;
			$seckill_price = isset($goods['seckill_prices'][$k]) ? floatval($goods['seckill_prices'][$k]) : 0;
			$stock_num = isset($goods['stock_nums'][$k]) ? intval($goods['stock_nums'][$k]) : 0;
			$old_price = $old_price ? $old_price : $price;
			$specs .= $pre . $v . '|' . $old_price . ':' . $price . ':' . $seckill_price . ':' . $stock_num . ':' . $cost_price  . '|';
			if ($properties) {
// 				$specs .= '|';
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
		
		//2016-11-08新增系统的商品分类属性值与商品直接的关系▽
		D('Goods_properties_relation')->where(array('gid' => $goods_id))->delete();
		if (isset($goods['goodsproperties'])) {
			foreach ($goods['goodsproperties'] as $pid) {
				D('Goods_properties_relation')->add(array('gid' => $goods_id, 'pid' => $pid));
			}
		}
		//2016-11-08新增系统的商品分类属性值与商品直接的关系△
		
		//
		//规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号 # 规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#...#规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号
		if ($this->where(array('goods_id' => $goods_id))->save(array('spec_value' => $specs, 'is_properties' => $is_properties, 'last_time' => $data['last_time'] + 1))) {
			$delete_spec_ids && $spec_obj->where(array('id' => array('in', $delete_spec_ids)))->delete();
			$delete_spec_val_ids && $spec_value_obj->where(array('id' => array('in', $delete_spec_val_ids)))->delete();
			$delete_properties_ids && $properties_obj->where(array('id' => array('in', $delete_properties_ids)))->delete();
			//配置属性
			return $goods_id;
		} else {
			return false;
		}
// 		$specs = array();
// 		foreach ($spec_value as $k => $v) {
// 			$index = '';
// 			$pre = '';
// 			foreach ($v as $r) {
// 				$index .= $pre . $r['spec_val_id'];
// 				$pre = '_';
// 			}
// 			$specs[$index] = array('spec' => $v, 'old_price' => $goods['old_prices'][$k], 'price' => $goods['prices'][$k], 'seckill_price' => $goods['seckill_prices'][$k], 'stock_num' => $goods['stock_nums'][$k]);
			
// 			foreach ($properties as $ti => $ps) {
// 				$ps['num'] = isset($goods['num' . $ti][$k]) ? intval($goods['num' . $ti][$k]) : 1;
// 				$specs[$index]['properties'][] = $ps;
// 			}
// 		}
		
// 		$config_file = './tg.php';
// 		$fp = fopen($config_file, 'a+');
// 		fwrite($fp, "<?php \nreturn " . stripslashes(var_export($specs, true)) . ";");
// 		fclose($fp);
		
// 		$this->where(array('goods_id' => $goods_id))->save(array('spec_value' => $specs ? serialize($specs) : ''));
		
		
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
	
	private function format_html($a, $i, $str, &$return)
	{
		if ($i == 0) {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$ta = $str;
				array_push($ta, $val);
				if ($ii == count($a)) {
					$return[] = $ta;
				} else {
					$this->format_html($a, $ii, $ta, $return);
				}
			}
		} else if ($i == count($a) - 1) {
			foreach ($a[$i] as $val) {
				$ta = $str;
				array_push($ta, $val);
				$return[] = $ta;
			}
		} else {
			$ii = $i + 1;
			foreach ($a[$i] as $val) {
				$ta = $str;
				array_push($ta, $val);
				$this->format_html($a, $ii, $ta, $return);
			}
		}
	}
	
	public function format_spec_value($str, $goods_id, $is_prorerties = 1)
	{
// 		if (empty($str)) return false;

		if ($is_prorerties || $str) {
			$properties_obj = M('Shop_goods_properties');
			$goods_properties_temp = $properties_obj->field(true)->where(array('goods_id' => $goods_id))->order('id asc')->select();
			$goods_properties_list = array();
			foreach ($goods_properties_temp as $goods_p) {
				$goods_p['val'] = explode(',', $goods_p['val']);
				$goods_properties_list[$goods_p['id']] = $goods_p;
			}
			unset($goods_properties_temp);
		}
		
		if ($str) {
			$spec_obj = M('Shop_goods_spec'); //规格表
			$spec_value_obj = M('Shop_goods_spec_value');//规格对应的属性值
			$goods_spec_temp = $spec_obj->field(true)->where(array('goods_id' => $goods_id))->order('id ASC')->select();
			$goods_spec_list = array();
			$specids = array();
			foreach ($goods_spec_temp as $goods_t) {
				$specids[] = $goods_t['id'];
				$goods_spec_list[$goods_t['id']] = $goods_t;
			}
			unset($goods_spec_temp);
			$spec_valuse_list = array();
			if ($specids) {
				$spec_valuse_temp = $spec_value_obj->field(true)->where(array('sid' => array('in', $specids)))->order('id ASC')->select();
				foreach ($spec_valuse_temp as $v_temp) {
					$spec_valuse_list[$v_temp['id']] = $v_temp;
					$goods_spec_list[$v_temp['sid']]['list'][$v_temp['id']] = $v_temp;
				}
				unset($spec_valuse_temp, $specids);
			}
			
			$return = array();
			$json = array();
// 		if ($str) {
			$spec_array = explode('#', $str);
			$p_ids = array();
			$is_sort = true;
			$new_goods_spec_list = array();
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
					if ($is_sort && isset($spec_valuse_list[$id]['sid']) && isset($goods_spec_list[$spec_valuse_list[$id]['sid']])) {
						$new_goods_spec_list[] = $goods_spec_list[$spec_valuse_list[$id]['sid']];
					}
				}
				$is_sort = false;
				$index = implode('_', $spec_ids);
				
				$return[$index]['index'] = $t_index;
				$return[$index]['spec'] = $spec_data;
				
				$prices = explode(':', $row_array[1]);
				$return[$index]['old_price'] = floatval($prices[0]);
				$return[$index]['price'] = floatval($prices[1]);
				$return[$index]['seckill_price'] = floatval($prices[2]);
				$return[$index]['stock_num'] = $prices[3];
				$return[$index]['cost_price'] = isset($prices[4]) ? $prices[4] : 0;
				
				$return[$index]['number'] = isset($row_array[3]) ? $row_array[3] : '';
				
				if (isset($row_array[2]) && $row_array[2] && strstr($row_array[2], '=')) {
					$p_data = array();
					$tdata = array();
					$properties = explode(':', $row_array[2]);
					foreach ($properties as $k => $pro) {
						$pro_array = explode('=', $pro);
						$p_data[] = array('id' => intval($pro_array[0]), 'num' => intval($pro_array[1]), 'name' => isset($goods_properties_list[$pro_array[0]]['name']) ? $goods_properties_list[$pro_array[0]]['name'] : '');
						$tdata['num' . $k . '[]'] = $pro_array[1];
					}
					$return[$index]['properties'] = $p_data;
					$json[$t_index] = $tdata;
				}
				if (empty($return[$index]['number']) && isset($row_array[2]) && $row_array[2] && !strstr($row_array[2], '=')) {
					$return[$index]['number'] = $row_array[2];
				}
				$json[$t_index]['old_prices[]'] = $prices[0];
				$json[$t_index]['prices[]'] = $prices[1];
				$json[$t_index]['seckill_prices[]'] = $prices[2];
				$json[$t_index]['stock_nums[]'] = $prices[3];
				$json[$t_index]['numbers[]'] = isset($row_array[3]) ? $row_array[3] : '';
			}
		}
		
		$data = array();
		$new_goods_spec_list && $data['spec_list'] = $new_goods_spec_list;
		$goods_properties_list && $data['properties_list'] = $goods_properties_list;
		$return && $data['list'] = $return;
		$json && $data['json'] = $json;
		return $data = $data ? $data : null;
// 		return array('spec_list' => $goods_spec_list, 'properties_list' => $goods_properties_list, 'list' => $return, 'json' => $json);
	}
	
	public function get_list_by_storeid_diypage($store_id){
		$where = array('store_id' => $store_id, 'status' => 1);
		if ($_POST['keyword']){
			$where['name'] = array('like','%'.$_POST['keyword'].'%');
		}
		$count = $this->where($where)->count();
		import('@.ORG.diypage');
		$Page = new Page($count,8);
		
		$good_list = $this->field(true)->where($where)->order('`last_time` DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
		
		$goods_image_class = new goods_image();
		
		foreach ($good_list as &$value) {
			$tmp_pic_arr = explode(';', $value['image']);
			foreach ($tmp_pic_arr as $k=>$v) {
				$value['pic_arr'][$k]['title'] = $v;
				$value['pic_arr'][$k]['url'] = $goods_image_class->get_image_by_path($v);
			}
			
		}
		return array('good_list'=>$good_list,'page_bar'=>$Page->show());
	}
    
    public function getGoodsBySortIds($sortIds, $store_id)
    {
        if (count($sortIds) < 1) {
            return null;
        } elseif (count($sortIds) == 1) {
            $where = array('sort_id' => $sortIds[0], 'store_id' => $store_id);
        } else {
            $where = array('sort_id' => array('in', $sortIds), 'store_id' => $store_id);
        }
        
        $sort_list = D('Shop_goods_sort')->field(true)->where($where)->order('`sort` DESC,`sort_id` ASC')->select();
        $s_list = array();
        $today = date('w');
        foreach ($sort_list as $value) {
            if (!empty($value['is_weekshow'])) {
                $week_arr = explode(',',$value['week']);
                if (!in_array($today, $week_arr)) {
                    continue;
                }
            }
            $s_list[$value['sort_id']] = $value;
        }
        $where['status'] = 1;
        $g_list = $this->field(true)->where($where)->order('sort DESC, goods_id ASC')->select();
        $goods_image_class = new goods_image();
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
            if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
                $row['price'] = floatval($row['seckill_price']);
                $row['is_seckill_price'] = true;
            } else {
                $row['price'] = floatval($row['price']);
            }

            $row['old_price'] = floatval($row['old_price']);
            $row['seckill_price'] = floatval($row['seckill_price']);
            $tmp_pic_arr = explode(';', $row['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                $row['pic_arr'][$key]['title'] = $value;
                $row['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value);
            }
            	
            $return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
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
    
	public function get_list_by_storeid($store_id, $sort = 0, $keyword = '')
	{
		$database_goods_sort = D('Shop_goods_sort');
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
			$value['see_image'] = $sort_image_class->get_image_by_path($value['image'],C('config.site_url'),'s');
			$s_list[$value['sort_id']] = $value;
		}

		$goods_image_class = new goods_image();
		if ($sort == 1) {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sell_count DESC, goods_id ASC')->select();
		} elseif ($sort == 2) {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('price DESC, goods_id ASC')->select();
		} elseif ($keyword != '') {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'name' => array('like', '%' . $keyword . '%'), 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		} else {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		}

        //获取商品折扣活动
        $store_discount = D('New_event')->getStoreNewDiscount($store_id);
        $goodsDiscount = $store_discount['goodsDiscount'];
        $goodsDishDiscount = $store_discount['goodsDishDiscount'];
		
		$sort_result = array();
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
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
				$row['price'] = floatval($row['seckill_price']);
				$row['is_seckill_price'] = true;
			} else {
				$row['price'] = floatval($row['price']);
			}

 			$row['price'] = $row['price']*$goodsDiscount;
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
			
			if ($sort == 1 || $sort == 2 || $keyword != '') {//pc端的排序与搜索
				$sort_result[] = $row;
			} else {
				if (isset($s_list[$row['sort_id']])) {
					if (isset($s_list[$row['sort_id']]['goods_list'])) {
						$s_list[$row['sort_id']]['goods_list'][] = $row;
					} else {
						$s_list[$row['sort_id']]['goods_list'] = array($row);
					}
				}
			}
		}
		if ($sort == 1 || $sort == 2 || $keyword != '') {//pc端的排序与搜索
			$s_list = array(array('goods_list' => $sort_result, 'sort_id' => false));
		} else {
			foreach ($s_list as $k => $r) {
				if (!isset($r['goods_list'])) {
					unset($s_list[$k]);
				}
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

        //获取商品折扣活动
        $store_discount = D('New_event')->getStoreNewDiscount($now_goods['store_id']);
        $goodsDiscount = $store_discount['goodsDiscount'];
        $goodsDishDiscount = $store_discount['goodsDishDiscount'];

		if(empty($now_goods)){
			return false;
		}else{
            $now_goods['price'] = $now_goods['price']*$goodsDiscount;
            $now_goods['goodsDiscount'] = $goodsDiscount;
            $now_goods['goodsDishDiscount'] = $goodsDishDiscount;
        }
		$shop = D('Merchant_store_shop')->where(array('store_id' => $now_goods['store_id']))->find();
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
			
			//秒杀库存的计算
			if ($today == $now_goods['sell_day']) {
				$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
			} else {
				$seckill_stock_num = $now_goods['seckill_stock'];
			}
		} else {
			$now_time = time();
			$open_time = $now_goods['seckill_open_time'];
			$close_time = $now_goods['seckill_close_time'];
			$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
		}
		$now_goods['is_seckill_price'] = false;
		$now_goods['o_price'] = floatval($now_goods['price']);
		if ($open_time < $now_time && $now_time < $close_time && floatval($now_goods['seckill_price']) > 0 && $seckill_stock_num != 0) {
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
//		if($now_goods['spec_value']!=''){
//			$now_goods['extra_pay_price']=0;
//		}
		if($now_goods['extra_pay_price']>0){
			$now_goods['extra_pay_price_name']='元宝';
		}
		
		if ($now_goods['is_seckill_price']) {
			$now_goods['stock_num'] = $seckill_stock_num;
		} else {
			if ($now_goods['sell_day'] == $today) {
				$now_goods['stock_num'] = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
			}
		}
		foreach ($now_goods['list'] as $key => &$row) {
			if ($now_goods['is_seckill_price']) {
				$row['stock_num'] = $seckill_stock_num;
			} else {
				$t_count = isset($now_goods['today_sell_spec'][$key]) ? intval($now_goods['today_sell_spec'][$key]) : 0;
				if ($now_goods['sell_day'] == $today) {
					$row['stock_num'] = $row['stock_num'] == -1 ? -1 : (intval($row['stock_num'] - $t_count) > 0 ? intval($row['stock_num'] - $t_count) : 0);
				}
			}
		}
		$template_id = intval($now_goods['freight_template']);
		if ($template_id) {
			if ($min = D('Express_template_value')->field(true)->where(array('tid' => $template_id, 'full_money' => array('gt', 0)))->find('freight')) {
				$min = 0;
			} else {
				$min = D('Express_template_value')->where(array('tid' => $template_id))->min('freight');
				$min = min($min, $now_goods['freight_value']);
			}
			$max = D('Express_template_value')->where(array('tid' => $template_id))->max('freight');
			$max = max($max, $now_goods['freight_value']);
			if ($min < $max) {
				$now_goods['deliver_fee'] = floatval($min) . '~' . floatval($max);
			} else {
				$now_goods['deliver_fee'] = floatval($min);
			}
		} else {
			$now_goods['deliver_fee'] = floatval($now_goods['freight_value']);
		}
		$nowtime = time();
		$now_goods['is_new'] = (($nowtime - $now_goods['last_time']) > 864000) ? 0 : 1;

		$now_sort = D('Shop_goods_sort')->where(array('sort_id'=>$now_goods['sort_id']))->find();
		$now_goods['is_time'] = $now_sort['is_time'];
		if($now_sort['is_time'] == 1){
            $show_time = explode(',',$now_sort['show_time']);
            $now_goods['begin_time'] = $show_time[0];
            $now_goods['end_time'] = $show_time[1];
        }
        $now_goods['is_weekshow'] = $now_sort['is_weekshow'];
		$weekStr = "";
        $weekList = explode(',',$now_sort['week']);

        if($now_sort['is_weekshow'] == 1) {
            $is_weekend = true;
            $is_workTime = true;

            if (count($weekList) == 2) {
                foreach ($weekList as $week) {
                    if ($week != 6 || $week != 0) {
                        $is_weekend = false;
                    }
                }

                if ($is_weekend) $weekStr = "on weekends";
            } else {
                $is_weekend = false;
            }

            if (count($weekList) == 5) {
                foreach ($weekList as $week) {
                    if ($week == 6 || $week == 0) {
                        $is_workTime = false;
                    }
                }

                if ($is_workTime) $weekStr = "on weekdays";
            } else {
                $is_workTime = false;
            }

            if (!$is_workTime && !$is_weekend) {
                if (count($weekList) != 7) {
                    $weekEn = "";
                    $i = 1;
                    foreach ($weekList as $week) {
                        switch ($week) {
                            case 0:
                                $weekEn = "Sunday";
                                break;
                            case 1:
                                $weekEn = "Monday";
                                break;
                            case 2:
                                $weekEn = "Tuesday";
                                break;
                            case 3:
                                $weekEn = "Wednesday";
                                break;
                            case 4:
                                $weekEn = "Thursday";
                                break;
                            case 5:
                                $weekEn = "Friday";
                                break;
                            case 6:
                                $weekEn = "Saturday";
                                break;

                            default:
                                break;
                        }

                        if ($i > 1 && $i == count($weekList)) {
                            $weekStr = $weekStr.", and ".$weekEn;
                        } else if ($i == 1) {
                            $weekStr = $weekStr."on ".$weekEn;
                        } else {
                            $weekStr = $weekStr.", ".$weekEn;
                        }
                        $i++;
                    }
                } else {
                    $weekStr = "every day";
                }
            }
        }else{
            $weekStr = "every day";
        }

        $now_goods['weekStr'] = $weekStr;

		return $now_goods;
	}
	
	/**
	 * 检查库存
	 * @param int $store_id
	 * @param int $goods_id
	 * @param int $num
	 * @param string $spec_ids = 'id_id'
	 * @return multitype:number string |multitype:number unknown |multitype:number string Ambigous <number, mixed>
	 */
	public function check_stock($goods_id, $num, $spec_ids = '', $stock_type = 0, $store_id = 0,$menu_version = 1,$goodsDiscount)
	{
		if ($store_id) {
            if($menu_version == 1) {
                $now_goods = $this->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->find();
                $now_goods['cost_price'] = $now_goods['price'];
                $now_goods['price'] = $now_goods['price']*$goodsDiscount;
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
            }else if($menu_version == 2) {
                $now_goods = D('StoreMenuV2')->getProduct($goods_id, $store_id);
                $image = $now_goods['image'];
            }
		} else {
			$now_goods = $this->field(true)->where(array('goods_id' => $goods_id))->find();
            $now_goods['cost_price'] = $now_goods['price'];
            $now_goods['price'] = $now_goods['price']*$goodsDiscount;
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
		}
		if (empty($now_goods)) return array('status' => 0, 'msg' => '商品不存在');

        if ($now_goods['status'] != 1) return array('status' => 0, 'msg' => 'Sorry, '.$now_goods['name'] . ' is currently unavailable');



        if($menu_version == 2){
            return array('status' => 1, 'num' => $num, 'is_seckill_price' => false, 'old_price' => 0, 'cost_price' => 0, 'price' => $now_goods['price']/100, 'image' => $image, 'packing_charge' => 0, 'freight_type' => 0, 'freight_value' => 0.00, 'freight_template' => 0, 'unit' => "", 'number' => 0, 'sort_id' => 0, 'name' => $now_goods['name'],'tax_num'=>$now_goods['tax']/1000,'deposit_price'=>0);
        }

		$stock_num = 0;

		$today = date('Ymd');
		//商品的库存类型（0：每日更新相同的库存，1:商品的总库存不会自动更新）
		$now_goods['sell_day'] = $stock_type ? $today : $now_goods['sell_day'];
		
		if ($now_goods['seckill_type'] == 1) {//秒杀类型（0：固定时间段，1：每天的时间段）
			$now_time = date('H:i');
			$open_time = date('H:i', $now_goods['seckill_open_time']);
			$close_time = date('H:i', $now_goods['seckill_close_time']);
			//秒杀库存的计算			
			if ($today == $now_goods['sell_day']) {
				$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
			} else {
				$seckill_stock_num = $now_goods['seckill_stock'];
			}
			
		} else {
			$now_time = time();
			$open_time = $now_goods['seckill_open_time'];
			$close_time = $now_goods['seckill_close_time'];
			//秒杀库存的计算
			$seckill_stock_num = $now_goods['seckill_stock'] == -1 ? -1 : (intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) > 0 ? intval($now_goods['seckill_stock'] - $now_goods['today_seckill_count']) : 0);
		}
		
		$is_seckill_price = false;
		if ($open_time < $now_time && $now_time < $close_time && floatval($now_goods['seckill_price']) > 0 && $seckill_stock_num != 0) {
			$price = floatval($now_goods['seckill_price']);
			$is_seckill_price = true;
		} else {
			$price = floatval($now_goods['price']);
		}
		$old_price = floatval($now_goods['cost_price']);
		$cost_price = floatval($now_goods['cost_price']);
// 		$price = $now_goods['price'];
		if ($spec_ids && $now_goods['spec_value']) {
			$return = $this->format_spec_value($now_goods['spec_value'], $now_goods['goods_id'], $now_goods['is_properties']);
			$list = isset($return['list']) ? $return['list'] : '';
			if (isset($list[$spec_ids])) {
				$today_sell_spec = json_decode($now_goods['today_sell_spec'], true);
				
				if ($now_goods['seckill_type'] == 1) {
					$now_time = date('H:i');
					$open_time = date('H:i', $now_goods['seckill_open_time']);
					$close_time = date('H:i', $now_goods['seckill_close_time']);
				} else {
					$now_time = time();
					$open_time = $now_goods['seckill_open_time'];
					$close_time = $now_goods['seckill_close_time'];
				}
				if ($open_time < $now_time && $now_time < $close_time && $list[$spec_ids]['seckill_price'] > 0 && $seckill_stock_num != 0) {
					$price = $list[$spec_ids]['seckill_price'];
					$is_seckill_price = true;
				} else {
					$price = $list[$spec_ids]['price'];
				}
				$old_price = floatval($list[$spec_ids]['price']);
				$cost_price = floatval($list[$spec_ids]['cost_price']);
				$number = $list[$spec_ids]['number'];
				if ($is_seckill_price) {
					$stock_num = $seckill_stock_num;
				} else {
					if ($today == $now_goods['sell_day']) {
						$sell_count = isset($today_sell_spec[$spec_ids]) ? intval($today_sell_spec[$spec_ids]) : 0;
						$stock_num = $list[$spec_ids]['stock_num'] == -1 ? -1 : (intval($list[$spec_ids]['stock_num'] - $sell_count) > 0 ? intval($list[$spec_ids]['stock_num'] - $sell_count) : 0);
					} else {
						$stock_num = $list[$spec_ids]['stock_num'];
					}
				}
			} else {
				return array('status' => 0, 'msg' => '您选择的规格可能被商家修改了');
			}
		} else {
			if ($is_seckill_price) {
				$stock_num = $seckill_stock_num;
			} else {
				if ($today == $now_goods['sell_day']) {
					$stock_num = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
				} else {
					$stock_num = $now_goods['stock_num'];
				}
			}
			$number = $now_goods['number'];
		}
		
		if ($stock_num == -1) {
			return array('status' => 1, 'num' => $num, 'is_seckill_price' => $is_seckill_price, 'old_price' => $old_price, 'cost_price' => $cost_price, 'price' => $price, 'image' => $image, 'packing_charge' => $now_goods['packing_charge'], 'freight_type' => $now_goods['freight_type'], 'freight_value' => $now_goods['freight_value'], 'freight_template' => $now_goods['freight_template'], 'unit' => $now_goods['unit'], 'number' => $number, 'sort_id' => $now_goods['sort_id'], 'name' => $now_goods['name'],'tax_num'=>$now_goods['tax_num'],'deposit_price'=>$now_goods['deposit_price']);
		} elseif ($stock_num == 0) {
			return array('status' => 0, 'msg' => 'Out of Stock!');
		} elseif ($stock_num - $num >= 0) {
			return array('status' => 1, 'num' => $num, 'is_seckill_price' => $is_seckill_price, 'old_price' => $old_price, 'cost_price' => $cost_price, 'price' => $price, 'image' => $image, 'packing_charge' => $now_goods['packing_charge'], 'freight_type' => $now_goods['freight_type'], 'freight_value' => $now_goods['freight_value'], 'freight_template' => $now_goods['freight_template'], 'unit' => $now_goods['unit'], 'number' => $number, 'sort_id' => $now_goods['sort_id'], 'name' => $now_goods['name'],'tax_num'=>$now_goods['tax_num'],'deposit_price'=>$now_goods['deposit_price']);
		} else {
			return array('status' => 2, 'num' => $stock_num, 'is_seckill_price' => $is_seckill_price, 'old_price' => $old_price, 'cost_price' => $cost_price, 'msg' => '最多能购买' . $stock_num . $now_goods['unit'], 'number' => $number, 'price' => $price, 'image' => $image, 'packing_charge' => $now_goods['packing_charge'], 'freight_type' => $now_goods['freight_type'], 'freight_value' => $now_goods['freight_value'], 'freight_template' => $now_goods['freight_template'], 'unit' => $now_goods['unit'], 'sort_id' => $now_goods['sort_id'], 'name' => $now_goods['name'],'tax_num'=>$now_goods['tax_num'],'deposit_price'=>$now_goods['deposit_price']);
		}
	}
	
	/**
	 * 更新库存
	 * $id_index = 1_1
	 * $goods 是shop_goods_order_detail 的一条记录
	 * $type 操作类型 0：加销量，减库存，1：加库存，减销量
	 */
// 	public function update_stock($goods_id, $num, $id_index = '', $is_seckill = 0)
	public function update_stock($goods, $type = 0)
	{
		static $shops;
		$today = date('Ymd');
		$now_goods = $this->field(true)->where(array('goods_id' => $goods['goods_id']))->find();
		if (empty($now_goods)) return array('status' => 0, 'msg' => '商品不存在');
		
		if (isset($shops[$now_goods['store_id']])) {
			$shop = $shops[$now_goods['store_id']];
		} else {
			$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $now_goods['store_id']))->find();
			$shops[$shop['store_id']] = $shop;
		}
		//$shop['stock_type']库存变更类型，0:每天更新成固定库存，1：不会自动更新库存
		$now_goods['sell_day'] = $shop['stock_type'] ? $today : $now_goods['sell_day'];
		
		if ($type == 0) {//加销量
			$num = $goods['num'];
			$total_num = $goods['num'];
			$seckill_num = $goods['num'];
		} else {//减销量
			$total_num = $goods['num'] * -1;
			if ($today == date('Ymd', $goods['create_time'])) {//下单是就是今天时候要实时回滚销量
				$num = $goods['num'] * -1;
				$seckill_num = $goods['num'] * -1;
			} else {//下单不是今天的情况
				if ($shop['stock_type'] == 0) {//每天固定库存的情况下，就无需回滚今天的销量
					$num = 0;
				} else {
					$num = $goods['num'] * -1;
				}
				if ($now_goods['seckill_type'] == 1) {//秒杀库存类型，1：每天固定库存，就无需回滚今天的销量
					$seckill_num = 0;
				} else {//0：固定库存
					$seckill_num = $goods['num'] * -1;
				}
			}
		}
		
		$today_sell_count = $now_goods['today_sell_count'];//今日销量
		$sell_count = $now_goods['sell_count'];//总销量
		$today_sell_spec = $now_goods['today_sell_spec'] ? json_decode($now_goods['today_sell_spec'], true) : '';//今日每种规格下的销量
		$today_seckill_count = $now_goods['today_seckill_count'];//今日秒杀的销量
		
		if ($goods['spec_id']) {//某种规格
			$id_index = $goods['spec_id'];
			isset($today_sell_spec[$id_index]) || $today_sell_spec[$id_index] = 0;
			if ($today == $now_goods['sell_day']) {
				$today_sell_spec[$id_index] += $num;
				$today_sell_count += $num;
			} else {
				$today_sell_spec[$id_index] = $num;
				$today_sell_count = $num;
			}
			$sell_count += $total_num;
			$today_sell_spec[$id_index] = max(0, $today_sell_spec[$id_index]);
			
			if ($goods['is_seckill']) {
				if ($now_goods['seckill_type'] == 1) {
					if ($today == $now_goods['sell_day']) {
						$today_seckill_count += $seckill_num;
					} else {
						$today_seckill_count = $seckill_num;
					}
				} else {
					$today_seckill_count += $seckill_num;
				}
			} elseif ($today != $now_goods['sell_day']) {
				$today_seckill_count = 0;
			}
		} else {
			if ($today == $now_goods['sell_day']) {
				$today_sell_count += $num;
			} else {
				$today_sell_count = $num;
			}
			$sell_count += $total_num;
			$today_sell_count = max(0, $today_sell_count);
			
			if ($goods['is_seckill']) {
				if ($now_goods['seckill_type'] == 1) {
					if ($today == $now_goods['sell_day']) {
						$today_seckill_count += $seckill_num;
					} else {
						$today_seckill_count = $seckill_num;
					}
				} else {
					$today_seckill_count += $seckill_num;
				}
			} elseif ($today != $now_goods['sell_day']) {
				$today_seckill_count = 0;
			}
		}
		$sell_count = max(0, $sell_count);
		$today_sell_count = max(0, $today_sell_count);
		$today_seckill_count = max(0, $today_seckill_count);
		$this->where(array('goods_id' => $goods['goods_id']))->save(array('sell_day' => $today, 'today_seckill_count' => $today_seckill_count, 'sell_count' => $sell_count, 'today_sell_count' => $today_sell_count, 'today_sell_spec' => $today_sell_spec ? json_encode($today_sell_spec) : ''));
	}
	
	
	public function get_list_by_option($where, $sort, $sort_type = 1)
	{
// 		if (empty($where['cat_fid'])) {
// // 			return array('goods_list' => null, 'total' => 0, 'next_page' => 0, 'total_page' => 0);
// 		}
		$order = 'ORDER BY ';
		if ($sort == 1) {
			$order .= '`g`.`sell_count` DESC, `g`.`price` ASC';
		} elseif ($sort == 2) {
			$order .= '`g`.`sell_count`';
		} elseif ($sort == 3) {
			$order .= '`g`.`price`';
		}
		if ($sort != 1) {
			if ($sort_type == 1) {
				$order .= ' DESC';
			} else {
				$order .= ' ASC';
			}
		}
		$condition = '`g`.`status`=1';
		if ($where['cat_fid']) {
			$condition .= " AND `g`.`cat_fid`='{$where['cat_fid']}'";
		}
		if ($where['cat_id']) {
			$condition .= " AND `g`.`cat_id`='{$where['cat_id']}'";
		}
		if ($where['store_id']) {
			$condition .= " AND `g`.`store_id`='{$where['store_id']}'";
		}
		
		if ($where['key']) {
			$condition .= " AND `g`.`name` LIKE '%{$where['key']}%'";
		}
		if ($where['pids']) {
			$pids = implode(',', $where['pids']);
			$p_sql = "SELECT gid, pid FROM  ". C('DB_PREFIX') . "goods_properties_relation WHERE `pid` IN ({$pids})";
			$list = $this->query($p_sql);
			$pids_arr1 = $pids_arr2 = $pids_arr3 = $pids_arr4 = '';
			$goods_ids = array();
			switch (count($where['pids'])) {
				case 1:
					$pids_arr1 = explode(',', $where['pids'][0]);
					foreach ($list as $vo) {
						if (!in_array($vo['gid'], $goods_ids)) $goods_ids[] = $vo['gid'];
					}
					break;
				case 2:
					$pids_arr1 = explode(',', $where['pids'][0]);
					$pids_arr2 = explode(',', $where['pids'][1]);
					$goods_ids1 = $goods_ids2 = array();
					foreach ($list as $vo) {
						if (in_array($vo['pid'], $pids_arr1)) {
							$goods_ids1[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr2)) {
							$goods_ids2[] = $vo['gid'];
						}
					}
					$goods_ids = array_intersect($goods_ids1, $goods_ids2);
					break;
				case 3:
					$pids_arr1 = explode(',', $where['pids'][0]);
					$pids_arr2 = explode(',', $where['pids'][1]);
					$pids_arr3 = explode(',', $where['pids'][2]);
					$goods_ids1 = $goods_ids2 = $goods_ids3 = array();
					foreach ($list as $vo) {
						if (in_array($vo['pid'], $pids_arr1)) {
							$goods_ids1[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr2)) {
							$goods_ids2[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr3)) {
							$goods_ids3[] = $vo['gid'];
						}
					}
					$goods_ids = array_intersect($goods_ids1, $goods_ids2, $goods_ids3);
					break;
				case 4:
					$pids_arr1 = explode(',', $where['pids'][0]);
					$pids_arr2 = explode(',', $where['pids'][1]);
					$pids_arr3 = explode(',', $where['pids'][2]);
					$pids_arr4 = explode(',', $where['pids'][3]);
					$goods_ids1 = $goods_ids2 = $goods_ids3 = $goods_ids4 = array();
					foreach ($list as $vo) {
						if (in_array($vo['pid'], $pids_arr1)) {
							$goods_ids1[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr2)) {
							$goods_ids2[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr3)) {
							$goods_ids3[] = $vo['gid'];
						} elseif (in_array($vo['pid'], $pids_arr4)) {
							$goods_ids4[] = $vo['gid'];
						}
					}
					$goods_ids = array_intersect($goods_ids1, $goods_ids2, $goods_ids3, $goods_ids4);
					break;
			}
			if (empty($goods_ids)) {
				return array('goods_list' => null, 'total' => 0, 'next_page' => 0, 'total_page' => 0);
			} else {
				$condition .= ' AND `g`.`goods_id` IN (' . implode(',', $goods_ids) . ')';
			}
		}
		$sql_count = "SELECT count(1) as count FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `s`.`status`=1 AND `s`.`have_shop`=1 AND `sh`.`store_theme`=1 AND `s`.`store_id`=`sh`.`store_id` INNER JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `sh`.`store_id`=`g`.`store_id` AND `s`.`status`=1 WHERE {$condition}";
		$count = $this->query($sql_count);
		$total = isset($count[0]['count']) ? intval($count[0]['count']) : 0;
		
		$page = isset($where['page']) ? intval($where['page']) : 1;
		$pagesize = 10;
		$totalPage = ceil($total / $pagesize);
		$star = $pagesize * ($page - 1);
		$return['next_page'] = $totalPage > $page ? intval($page + 1) : 0;
		$return['total_page'] = $totalPage;
		$return['total'] = $total;
		
		$sql = "SELECT `g`.`goods_id`, `g`.`name`, `g`.`seckill_type`, `g`.`seckill_open_time`, `g`.`last_time`, `g`.`seckill_close_time`, `g`.`price`, `g`.`old_price`, `g`.`seckill_price`, `g`.`unit`, `g`.`image`, `g`.`sell_count` ,`g`.`extra_pay_price`,`g`.`spec_value` FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `s`.`status`=1 AND `s`.`have_shop`=1 AND `sh`.`store_theme`=1 AND `s`.`store_id`=`sh`.`store_id` INNER JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `sh`.`store_id`=`g`.`store_id` AND `s`.`status`=1 WHERE {$condition} {$order} LIMIT {$star}, {$pagesize}";
		
		$temp_list = $this->query($sql);
		$goods_image_class = new goods_image();
		$nowtime = time();
		foreach ($temp_list as &$row) { 
			$row['price'] = floatval($row['price']);
//			if($row['spec_value']!=''){
//				$row['extra_pay_price']=0;
//			}
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
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
				$row['price'] = floatval($row['seckill_price']);
				$row['is_seckill_price'] = true;
			} else {
				$row['price'] = floatval($row['price']);
			}
			$row['is_new'] = (($nowtime - $row['last_time']) > 864000) ? 0 : 1;
			$row['old_price'] = floatval($row['old_price']);
			$row['url'] = U('Mall/detail', array('goods_id' => $row['goods_id']), true, false, true);
			$tmp_pic_arr = explode(';', $row['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$temp_image = $goods_image_class->get_image_by_path($value);
				if ($temp_image) {
					$row['image'] = $temp_image['image'];
					break;
				}
			}
		}
		$return['goods_list'] = $temp_list;
		return $return;
	}
	
	public function get_list_by_condition($where, $sort = 1, $sort_type = 1)
	{

		$condition = array('status' => 1, 'store_id' => $where['store_id']);
		$where['sort_id'] && $condition['sort_id'] = $where['sort_id'];
		$order = '';
		if ($sort == 1) {
			$order .= 'sell_count';
		} elseif ($sort == 2) {
			$order .= 'price';
		} elseif ($sort == 3) {
			$order .= 'reply_count';
		}
		if ($sort_type == 1) {
			$order .= ' DESC';
		} else {
			$order .= ' ASC';
		}
		$goods_image_class = new goods_image();
		$g_list = $this->field(true)->where($condition)->order($order . ', goods_id ASC')->select();
		/*elseif ($keyword != '') {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'name' => array('like', '%' . $keyword . '%'), 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		} else {
			$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		}*/
	
		$sort_result = array();
		$nowtime = time();
		foreach ($g_list as &$row) {
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
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
				$row['price'] = floatval($row['seckill_price']);
				$row['is_seckill_price'] = true;
			} else {
				$row['price'] = floatval($row['price']);
			}
			$row['is_new'] = (($nowtime - $row['last_time']) > 864000) ? 0 : 1;
			$row['old_price'] = floatval($row['old_price']);
			$row['seckill_price'] = floatval($row['seckill_price']);

			$row['url'] = U('Mall/detail', array('goods_id' => $row['goods_id']), true, false, true);
			$tmp_pic_arr = explode(';', $row['image']);
			foreach ($tmp_pic_arr as $key => $value) {
				$temp_image = $goods_image_class->get_image_by_path($value);
				if ($temp_image) {
					$row['image'] = $temp_image['image'];
					break;
				}
			}
		}
		return $g_list;
	}
	
	
	
	


	/**
	 * 检查库存
	 * @param int $goods_id
	 * @param int $num
	 * @param string $spec_ids = 'id_id'
	 * @param string $stock_type //店铺更新库存的类型 0：每日更新相同的库存 1：库存不会每天自动更新
	 * @return multitype:number string |multitype:number unknown |multitype:number string Ambigous <number, mixed>
	 */
	public function check_stock_list($store_id = 2, $stock_type = 0, $num = 10)
	{
		$goods_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->select();
		
		$today = date('Ymd');
		
		$waring_list = array();
		foreach ($goods_list as $now_goods) {
			$now_goods['sell_day'] = $stock_type ? $today : $now_goods['sell_day'];
			if ($now_goods['spec_value']) {
				$return = $this->format_spec_value($now_goods['spec_value'], $now_goods['goods_id'], $now_goods['is_properties']);
				$list = isset($return['list']) ? $return['list'] : '';
				$today_sell_spec = json_decode($now_goods['today_sell_spec'], true);
				
				foreach ($list as $key => $value) {
					
					if ($today == $now_goods['sell_day']) {
						$sell_count = isset($today_sell_spec[$key]) ? intval($today_sell_spec[$key]) : 0;
						$stock_num = $value['stock_num'] == -1 ? -1 : (intval($value['stock_num'] - $sell_count) > 0 ? intval($value['stock_num'] - $sell_count) : 0);
					} else {
						$stock_num = $value['stock_num'];
					}
					$name = $pre = '';
					foreach ($value['spec'] as $spec_val) {
						$name .= $pre . $spec_val['spec_val_name'];
						$pre = ',';
					}
					if ($name) $name = ' (' . $name . ')';
					if ($stock_num != -1 && $stock_num < $num) {
						$waring_list[] = array('goods_id' => $now_goods['goods_id'], 'number' => $value['number'], 'price' => $value['price'], 'stock_num' => $stock_num, 'name' => $now_goods['name'] . $name);
					}
				}
				
	
			} else {
				
				if ($today == $now_goods['sell_day']) {
					$stock_num = $now_goods['stock_num'] == -1 ? -1 : (intval($now_goods['stock_num'] - $now_goods['today_sell_count']) > 0 ? intval($now_goods['stock_num'] - $now_goods['today_sell_count']) : 0);
				} else {
					$stock_num = $now_goods['stock_num'];
				}
				
				if ($stock_num != -1 && $stock_num < $num) {
					$waring_list[] = array('goods_id' => $now_goods['goods_id'], 'number' => $now_goods['number'], 'price' => $now_goods['price'], 'stock_num' => $stock_num, 'name' => $now_goods['name']);
				}
			}
		}

		return $waring_list;
	}
	
	
	public function get_list($store_id, $is_cache = true)
	{
		if ($is_cache) {
			$s_list = S('shop_goods_by_storeid_' . $store_id);
		} else {
			$s_list = null;
		}
		if ($s_list) return $s_list;
		
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['store_id'] = $store_id;
		$sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->select();
		$s_list = array();
		$today = date('w');
		foreach ($sort_list as $value) {
			if (!empty($value['is_weekshow'])) {
				$week_arr = explode(',', $value['week']);
				if (!in_array($today, $week_arr)) {
					continue;
				}
				$week_str = '';
				foreach ($week_arr as $k => $v){
					$week_str .= $this->get_week($v) . ' ';
				}
				$value['week_str'] = $week_str;
			}
			$s_list[$value['sort_id']] = $value;
		}
		
		$g_list = $this->field(true)->where(array('store_id' => $store_id, 'status' => 1))->order('sort DESC, goods_id ASC')->select();
		
		$today = date('Ymd');
		$store_shop = D("Merchant_store_shop")->field('stock_type')->where(array('store_id' => $store_id))->find();
		$stock_type = intval($store_shop['stock_type']);
		
		foreach ($g_list as $row) {
			$row['sell_day'] = $stock_type ? $today : $row['sell_day'];
			
			$temp_goods = array('name' => $row['name'], 'goods_id' => $row['goods_id']);
			$temp_goods['seckill_type'] = $row['seckill_type'];
			
			if ($row['seckill_type'] == 1) {
				$now_time = date('H:i');
				$open_time = date('H:i', $row['seckill_open_time']);
				$close_time = date('H:i', $row['seckill_close_time']);
			} else {
				$now_time = time();
				$open_time = $row['seckill_open_time'];
				$close_time = $row['seckill_close_time'];
			}
			$temp_goods['open_time'] = $open_time;
			$temp_goods['close_time'] = $close_time;
			$temp_goods['price'] = floatval($row['price']);
			$temp_goods['seckill_price'] = floatval($row['seckill_price']);
			$temp_goods['number'] = $row['number'];
			$temp_goods['unit'] = $row['unit'];
			
			$temp_goods['sort_id'] = $row['sort_id'];
			if ($open_time < $now_time && $now_time < $close_time && floatval($row['seckill_price']) > 0) {
				$temp_goods['is_seckill_price'] = true;
			} else {
				$temp_goods['is_seckill_price'] = false;
			}
			if ($today == $row['sell_day']) {
				$stock_num = $row['stock_num'] == -1 ? -1 : (intval($row['stock_num'] - $row['today_sell_count']) > 0 ? intval($row['stock_num'] - $row['today_sell_count']) : 0);
			} else {
				$stock_num = $row['stock_num'];
			}
			$temp_goods['stock_num'] = $stock_num;
			
			$return = $this->format_spec_value($row['spec_value'], $row['goods_id'], $row['is_properties']);
			if (isset($return['list'])) {
				$today_sell_spec = json_decode($row['today_sell_spec'], true);
				foreach ($return['list'] as $id_key => $spec_goods) {
					$temp_goods['goods_id'] = $row['goods_id'] . '_' . $id_key;
					$temp_goods['price'] = floatval($spec_goods['price']);
					$temp_goods['seckill_price'] = floatval($spec_goods['seckill_price']);
					$temp_goods['number'] = $spec_goods['number'];
					
					if ($today == $row['sell_day']) {
						$sell_count = isset($today_sell_spec[$id_key]) ? intval($today_sell_spec[$id_key]) : 0;
						$stock_num = $spec_goods['stock_num'] == -1 ? -1 : (intval($spec_goods['stock_num'] - $sell_count) > 0 ? intval($spec_goods['stock_num'] - $sell_count) : 0);
					} else {
						$stock_num = $spec_goods['stock_num'];
					}
					
					$temp_goods['stock_num'] = $stock_num;
					$t_names = array();
					foreach ($spec_goods['spec'] as $spec_row) {
						$t_names[] = $spec_row['spec_val_name'];
					}
					$temp_goods['name'] = $row['name'] . '(' . implode(',', $t_names) . ')';
					
					if (isset($s_list[$row['sort_id']])) {
						if (isset($s_list[$row['sort_id']]['goods_list'])) {
							$s_list[$row['sort_id']]['goods_list'][] = $temp_goods;
						} else {
							$s_list[$row['sort_id']]['goods_list'] = array($temp_goods);
						}
					}
				}
			} else {
				if (isset($s_list[$row['sort_id']])) {
					if (isset($s_list[$row['sort_id']]['goods_list'])) {
						$s_list[$row['sort_id']]['goods_list'][] = $temp_goods;
					} else {
						$s_list[$row['sort_id']]['goods_list'] = array($temp_goods);
					}
				}
			}
		}
		
		foreach ($s_list as $k => $r) {
			if (!isset($r['goods_list'])) {
				unset($s_list[$k]);
			}
		}
		
		S('shop_goods_by_storeid_' . $store_id, $s_list);
		return $s_list;
    }
    
    public function checkCart($store_id, $uid, $goodsData, $isCookie = 1, $address_id = 0,$goodsDiscount = 1)
    {
        $store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
        if ($store['have_shop'] == 0 || $store['status'] != 1) {
            return array('error_code' => true, 'msg' => L('_STORE_IS_CLOSE_'));
        }
        if (C('config.store_shop_auth') == 1 && $store['auth'] < 3) {
            return array('error_code' => true, 'msg' => '您查看的' . C('config.shop_alias_name') . '没有通过资质审核！');
        }
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($store['pic_info']);
        $store['images'] = $images ? array_shift($images) : '';
        $now_time = date('H:i:s');
        $is_open = 0;

//        if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
//            $is_open = 1;
//        } else {
//            if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
//                $is_open = 1;
//            }
//            if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
//                if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
//                    $is_open = 1;
//                }
//            }
//            if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
//                if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
//                    $is_open = 1;
//                }
//            }
//        }
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
            return array('error_code' => true, 'msg' => L('_STORE_IS_CLOSE_'));
        }
        
        $store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
        if (empty($store) || empty($store_shop)) return array('error_code' => true, 'msg' => '');
        $store = array_merge($store, $store_shop);
        $mer_id = $store['mer_id'];
        
        $store['delivery_range_polygon'] = substr($store['delivery_range_polygon'], 9, strlen($store['delivery_range_polygon']) - 11);
        $lngLatData = explode(',', $store['delivery_range_polygon']);
        array_pop($lngLatData);
        $lngLats = array();
        foreach ($lngLatData as $lnglat) {
            $lng_lat = explode(' ', $lnglat);
            $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
        }
        $store['delivery_range_polygon'] = $lngLats ? array($lngLats) : '';
        //用户的VIP折扣率
        $vip_discount = 100;
        //店铺设置的vip等级折扣率
        $storeShopLevel = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) : '';
        $user = M('User')->field(true)->where(array('uid' => $uid))->find();
        if ($storeShopLevel && $user) {
            if ($user['level']) {
                //系统设置的用户等级
                $tmpArr = M('User_level')->field(true)->order('`id` ASC')->select();
                $levelArray = array();
                foreach ($tmpArr as $vv) {
                    $levelArray[$vv['level']] = $vv;
                }
                if (isset($storeShopLevel[$user['level']]) && isset($levelArray[$user['level']])) {
                    $levelOff = $storeShopLevel[$user['level']];
                    if ($levelOff['type'] == 1) {
                        $vip_discount = $levelOff['vv'];
                    }
                }
            }
        }

        $goods = array();
        $price = 0;//原始总价
        $total = 0;//商品总数
        $extra_price = 0;//额外价格的总价
        $packing_charge = 0;//打包费
        $deposit_price = 0;//押金
        $tax_price = 0;//税费
        //店铺优惠条件
        $sorts_discout = D('Shop_goods_sort')->get_sorts_discount($store_id);
        $store_discount_money = 0;//店铺折扣后的总价
        if ($isCookie == 0) {
            foreach ($goodsData as $row) {
                $goods_id = $row['goods_id'];
                $num = $row['num'];
                $t_return = $this->check_stock($goods_id, $num, $row['spec_id'], $store_shop['stock_type'], $store_id);
                if ($t_return['status'] == 0) {
                    continue;
                } elseif ($t_return['status'] == 2) {
                    continue;
                }
                $total += $num;
                $price += $t_return['price'] * $num;
                $extra_price += $row['extra_price'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;
        
        
                //折扣($sorts_discout[$t_return['sort_id']]['discount_type'] == 1 ? '分类折扣' : '店铺折扣')
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;
        
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
        
                $this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣后的总价
                $only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣的单价
                if ($sorts_discout['discount_type'] == 0) {//折上折
                    if ($vip_discount < 100) {
                        $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                        $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                    }
                    $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);//本商品的VIP折扣后的总价
                    $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);//本商品的VIP折扣的单价
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
        
                $store_discount_money += $this_goods_total_price;//折扣后的商品总价（店铺，分类，VIP折扣都计算在内）
        
                $goods[] = array(
                    'name' => $row['name'],
                    'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                    'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                    'discount_rate' => $discount_rate,//折扣率
                    'num' => $num,
                    'goods_id' => $goods_id,
                    'old_price' => floatval($t_return['old_price']),//商品原始价
                    'price' => floatval($t_return['price']),//
                    'discount_price' => floatval($only_discount_price),//折扣价
                    'cost_price' => floatval($t_return['cost_price']),
                    'number' => $t_return['number'],
                    'image' => $t_return['image'],
                    'sort_id' => $t_return['sort_id'],
                    'packing_charge' => $t_return['packing_charge'],
                    'unit' => $t_return['unit'],
                    'str' => $row['spec'],
                    'spec_id' => $row['spec_id'],
                    'extra_price' => $row['extra_price']
                );
            }
        } elseif ($isCookie == 1) { //wap走的这里
            
            if ($address_id) {
                $user_adress = D('User_adress')->get_one_adress($uid, $address_id);
                $express_freight = array();
                $delivery_list = D('Express_template')->get_deliver_list($store['mer_id'], $store['store_id']);
                $goods_id_array = array();
                $delivery_money_total = 0;
                $max_freight = 0;
                $template_total_price = 0;
            }

            $new_goodsData = array();
            foreach ($goodsData as $key=>$row) {
                $goods_id = $row['productId'];
                $num = $row['count'];
                $spec_ids = array();
                $pro_ids = array();
                $dish_ids = array();
                $str_s = array(); $str_p = array();$str_d = array();
                foreach ($row['productParam'] as $r) {
                    if ($r['type'] == 'spec') {
                        $spec_ids[] = $r['id'];
                        $str_s[] = $r['name'];
                    }elseif ($r['type'] == 'side_dish'){//garfunkel add dish
                        if($r['data']) {
                            foreach ($r['data'] as $d) {
                                if ($d['dish_num'] > 1) {
                                    $str_d[] = $d['dish_val_name'] . '*' . $d['dish_num'];
                                } else {
                                    $str_d[] = $d['dish_val_name'];
                                }

                                $dish_ids[] = $d['dish_id'] . ',' . $d['dish_val_id'] . ',' . $d['dish_num'] . ',' . $d['dish_price'];
                            }
                        }else if($r['dish_id']){
                            $curr_dish = explode("|",$r['dish_id']);
                            $dish_desc = "";
                            foreach($curr_dish as $vv){
                                $one_dish = explode(",",$vv);
                                if($store['menu_version'] == 1) {
                                    $dish_vale = D('Side_dish_value')->where(array('id' => $one_dish[1]))->find();
                                    $dish_vale['name'] = lang_substr($dish_vale['name'], C('DEFAULT_LANG'));
                                }else if($store['menu_version'] == 2){
                                    $dish_vale = D('StoreMenuV2')->getProduct($one_dish[1],$store_id);
                                }

                                $add_str = $one_dish[2] > 1 ? $dish_vale['name']."*".$one_dish[2] : $dish_vale['name'];

                                $dish_desc = $dish_desc == "" ? $add_str : $dish_desc.";".$add_str;
                            }
                            $dish_ids = array_merge($dish_ids,$curr_dish);
                            $str_d[] = $dish_desc;
                        }
                    } else {
                        foreach ($r['data'] as $d) {
                            $str_p[] = $d['name'];
                            $pro_ids[] = $d['list_id'].','.$d['id'];
                        }
                    }
                }
                $spec_str = count($spec_ids)>0 ? implode('_', $spec_ids) : '';

                $pro_str = count($pro_ids)>0 ? implode('|',$pro_ids) : '';

                $dish_str = count($dish_ids)>0 ? implode('|',$dish_ids) : '';

                $t_return = $this->check_stock($goods_id, $num, $spec_str, $store_shop['stock_type'], $store_id,$store['menu_version'],$goodsDiscount);
                if ($t_return['status'] == 0) {
                    unset($goodsData[$key]);
                    //var_dump(json_encode($goodsData));die();
                    $newList = array();
                    foreach ($goodsData as $n){
                        $newList[] = $n;
                    }
                    setCookie('shop_cart_'.$store_id, json_encode($newList));
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                } elseif ($t_return['status'] == 2) {
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                }else{
                    $new_goodsData[] = $row;
                }
                //garfunkel add dish
                if(count($dish_ids) > 0){
                    foreach ($dish_ids as $v){
                        $dish = explode(',',$v);
                        $t_return['price'] += $dish[3]*$dish[2];
                        //存储单品的原始价格
                        $curr_dish_value = D("Side_dish_value")->where(array('id'=>$dish[1]))->find();
                        $t_return['cost_price'] += $curr_dish_value['price'] * $dish[2];
                    }
                }
                $total += $num;
                $price += $t_return['price'] * $num;
                $extra_price += $row['productExtraPrice'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;
                $deposit_price += $t_return['deposit_price'] * $num;
                if($store['menu_version'] == 1) {
                    $tax_price += ($t_return['price'] * $t_return['tax_num'] / 100) * $num;
                }else{
                    $orderDetail = array('goods_id'=>$goods_id,'num'=>$num,'store_id'=>$store_id,'dish_id'=>$dish_str);
                    $tax_price += D('StoreMenuV2')->calculationTaxFromOrder($orderDetail);
                }
        
                if ($address_id) {
                    //-----计算运费--------  freight_type ==> 0:最大，1：单独
                    if ($t_return['freight_type'] == 0) {
                        $template_id = intval($t_return['freight_template']);
                        if ($user_adress) {
                            if (isset($delivery_list[$template_id][$user_adress['city']])) {
                                $express_freight_tmp = $delivery_list[$template_id][$user_adress['city']];
                            } elseif (isset($delivery_list[$template_id][$user_adress['province']])) {
                                $express_freight_tmp = $delivery_list[$template_id][$user_adress['province']];
                            } else {
                                $template_id = 0;
                                $express_freight_tmp = array('freight' => $t_return['freight_value'], 'full_money' => 0, 'tid' => 0);
                            }
                        } else {
                            $template_id = 0;
                            $express_freight_tmp = array('freight' => $t_return['freight_value'], 'full_money' => 0, 'tid' => 0);
                        }
                        if ($max_freight < $express_freight_tmp['freight']) {
                            $express_freight = $express_freight_tmp;
                            $max_freight = $express_freight_tmp['freight'];
                        }
                        $template_total_price += $t_return['price'] * $num;
                    } else {
                        if (!in_array($goods_id, $goods_id_array)) {
                            $template_id = intval($t_return['freight_template']);
                            if ($user_adress) {
                                if (isset($delivery_list[$template_id][$user_adress['city']])) {
                                    $delivery_money_total += $delivery_list[$template_id][$user_adress['city']]['freight'];
                                } elseif (isset($delivery_list[$template_id][$user_adress['province']])) {
                                    $delivery_money_total += $delivery_list[$template_id][$user_adress['province']]['freight'];
                                } else {
                                    $delivery_money_total += $t_return['freight_value'];
                                }
                            }
                            $goods_id_array[] = $goods_id;
                        }
                    }
                    //-----计算运费--------
                }
                
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;

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
                $str_d && $str = $str ? $str . ';' . implode(',',$str_d) : implode(',',$str_d);
                //echo $str."----------";
                $str=str_replace(",","<br/>",$str);
                $str=str_replace(";","; ",$str);
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
                    'pro_id' => $pro_str,
                    'dish_id' => $dish_str,
                    'extra_price' => $row['productExtraPrice'],
                    'tax_num'   => $t_return['tax_num'],
                    'deposit_price' =>  $t_return['deposit_price']
                );
            }
        } elseif ($isCookie == 2) {
            foreach ($goodsData as $row) {
                $num = $row['num'];
                $ids = explode('_', $row['goods_id']);
                $goods_id = array_shift($ids);
                $spec_str = $ids ? implode('_', $ids) : '';
                $t_return = D('Shop_goods')->check_stock($goods_id, $num, $spec_str, $store_shop['stock_type'], $store_id);
                if ($t_return['status'] == 0) {
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                } elseif ($t_return['status'] == 2) {
                    return array('error_code' => true, 'msg' => $t_return['msg']);
                }
                $total += $num;
                $price += $t_return['price'] * $num;
                $extra_price += $row['productExtraPrice'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;
            
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;

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
                
                $str = str_replace(array($t_return['name'], '(', ')'), '', $row['name']);
                $goods[] = array(
                    'name' => $t_return['name'],
                    'is_seckill_price' => $t_return['is_seckill_price'],
                    'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                    'discount_rate' => $discount_rate,//折扣率
                    'num' => $num,
                    'goods_id' => $goods_id,
                    'old_price' => floatval($t_return['old_price']),//商品原始价
                    'price' => floatval($t_return['price']),
                    'discount_price' => floatval($only_discount_price),//折扣价
                    'cost_price' => floatval($t_return['cost_price']),
                    'number' => $t_return['number'],
                    'image' => $t_return['image'],
                    'sort_id' => $t_return['sort_id'],
                    'packing_charge' => $t_return['packing_charge'],
                    'unit' => $t_return['unit'],
                    'str' => $str,
                    'spec_id' => $spec_str,
                    'extra_price'=> 0,
                    'tax_num'   => $t_return['tax_num'],
                    'deposit_price' =>  $t_return['deposit_price']
                );
            }
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
        $shopOrderDB = D("Shop_order");
        
        $sys_count = $shopOrderDB->where(array('uid' => $uid))->count();
        if (empty($sys_count) && $uid) {//平台首单优惠
            if ($d_tmp = $this->getReduce($discounts, 0, $vip_discount_money)) {
                $d_tmp['discount_type'] = 1;//平台首单
                $d_tmp['money'] = $d_tmp['full_money'];
                $d_tmp['minus'] = $d_tmp['reduce_money'];
                $discount_list['system_newuser'] = $d_tmp;
                $sys_first_reduce = $d_tmp['reduce_money'];
            }
        }
        
        
        if ($uid && ($d_tmp = $this->getReduce($discounts, 1, $vip_discount_money))) {
            $d_tmp['discount_type'] = 2;//平台满减
            $d_tmp['money'] = $d_tmp['full_money'];
            $d_tmp['minus'] = $d_tmp['reduce_money'];
            $discount_list['system_minus'] = $d_tmp;
            $sys_full_reduce = $d_tmp['reduce_money'];
        }
        
        $sto_count = $shopOrderDB->where(array('uid' => $uid, 'store_id' => $store_id))->count();
        $sto_first_reduce = 0;
        if (empty($sto_count)) {
            if ($d_tmp = $this->getReduce($discounts, 0, $vip_discount_money, $store_id)) {
                $d_tmp['discount_type'] = 3;//店铺首单
                $d_tmp['money'] = $d_tmp['full_money'];
                $d_tmp['minus'] = $d_tmp['reduce_money'];
                $discount_list['newuser'] = $d_tmp;
                $sto_first_reduce = $d_tmp['reduce_money'];
            }
        }
        $sto_full_reduce = 0;
        if ($d_tmp = $this->getReduce($discounts, 1, $vip_discount_money, $store_id)) {
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
                $delivery_fee = C('config.delivery_fee');
                $per_km_price = C('config.per_km_price');
                $basic_distance = C('config.basic_distance');
        
                $delivery_fee2 = C('config.delivery_fee2');
                $per_km_price2 = C('config.per_km_price2');
                $basic_distance2 = C('config.basic_distance2');
            }
            //使用平台的优惠（配送费的减免）
            if ($d_tmp = $this->getReduce($discounts, 2, $price)) {
                $d_tmp['discount_type'] = 5;//平台配送费满减
                $d_tmp['money'] = $d_tmp['full_money'];
                $d_tmp['minus'] = $d_tmp['reduce_money'];
                $discount_list['delivery'] = $d_tmp;
                $delivery_fee_reduce = $d_tmp['reduce_money'];
            }
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
            return array('error_code' => true, 'msg' => '购物车是空的');
        } else {
            $data = array('error_code' => false);
            $data['total'] = $total;
            $data['price'] = $price;//商品实际总价
            $data['extra_price'] = $extra_price;//商品实际总价
            $data['discount_price'] = $vip_discount_money;//折扣后的总价
            $data['deposit_price'] = $deposit_price;
            $data['tax_price'] = $tax_price;
            $data['goods'] = $goods;
            $data['store_id'] = $store_id;
            $data['mer_id'] = $mer_id;
            $data['store'] = $store;
            $data['discount_list'] = $discount_list;
    
            $data['delivery_type'] = $store_shop['deliver_type'];
    
            $data['sys_first_reduce'] = $sys_first_reduce;//平台新单优惠的金额
            $data['sys_full_reduce'] = $sys_full_reduce;//平台满减优惠的金额
            $data['sto_first_reduce'] = $sto_first_reduce;//店铺新单优惠的金额
            $data['sto_full_reduce'] = $sto_full_reduce;//店铺满减优惠的金额
    
            $data['store_discount_money'] = $store_discount_money;//店铺折扣后的总价
            $data['vip_discount_money'] = $vip_discount_money;//VIP折扣后的总价
            $data['packing_charge'] = $packing_charge;//总的打包费

            $data['delivery_fee'] = $delivery_fee;//起步配送费
            if ($address_id) {
                $full_money = floatval($express_freight['full_money']);
                if (!($full_money != 0 && $template_total_price >= $full_money)) {
                    $delivery_money_total += $express_freight['freight'];
                }
                $data['delivery_fee'] = $delivery_money_total;//起步配送费
            }
            
            $data['basic_distance'] = $basic_distance;//起步距离
            $data['per_km_price'] = $per_km_price;//超出起步距离部分的距离每公里的单价
            $data['delivery_fee_reduce'] = $delivery_fee_reduce;//配送费减免的金额
    
            $data['delivery_fee2'] = $delivery_fee2;//起步配送费
            $data['basic_distance2'] = $basic_distance2;//起步距离
            $data['per_km_price2'] = $per_km_price2;//超出起步距离部分的距离每公里的单价
            $data['userphone'] = isset($user['phone']) && $user['phone'] ? $user['phone'] : '';
            return $data;
        }
        
    }


    private function getReduce($discounts, $type, $price, $store_id = 0)
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
}
?>