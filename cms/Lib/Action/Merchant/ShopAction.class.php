<?php

class ShopAction extends BaseAction
{

    const GOODS_SORT_LEVEL = 3;
    /* 店铺管理 */
    public function index()
    {
        $mer_id = $this->merchant_session['mer_id'];
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['mer_id'] = $mer_id;
        $condition_merchant_store['have_shop'] = '1';
        $condition_merchant_store['status'] = '1';
        $count_store = $database_merchant_store->where($condition_merchant_store)->count();

        $db_arr = array(C('DB_PREFIX') . 'area' => 'a', C('DB_PREFIX') . 'merchant_store' => 's');
        import('@.ORG.merchant_page');
        $p = new Page($count_store, 30);

        $sql = "SELECT `s`.`store_id`, `s`.`mer_id`, `s`.`name`, `s`.`adress`, `s`.`phone`, `s`.`sort`, `ss`.`store_theme`, `ss`.`store_id` AS sid FROM ". C('DB_PREFIX') . "merchant_store AS s LEFT JOIN  ". C('DB_PREFIX') . "merchant_store_shop AS ss ON `s`.`store_id`=`ss`.`store_id`";
        $sql .= " WHERE `s`.`mer_id`={$mer_id} AND `s`.`status`='1' AND `s`.`have_shop`='1'";
        $sql .= " ORDER BY `s`.`sort` DESC,`s`.`store_id` ASC";
        $sql .= " LIMIT {$p->firstRow}, {$p->listRows}";
        $store_list = D()->query($sql);
        // 		echo D()->_sql();
        // 		$store_list = D()->table($db_arr)->field(true)->where("`s`.`mer_id`='$mer_id' AND `s`.`status`='1' AND `s`.`have_shop`='1' AND `s`.`area_id`=`a`.`area_id`")->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
        $this->assign('store_list', $store_list);

        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);

        $this->display();
    }

    /* 店铺信息修改 */
    public function shop_edit()
    {
        if (!empty($_SESSION['system'])) {
            $this->assign('login_system',true);
        }
        $now_store = $this->check_store($_GET['store_id']);
        $store_id = $now_store['store_id'];
        if(IS_POST){
            if ($_POST['delivery_range_type'] == 1) {
                if ($_POST['delivery_range_polygon']) {
                    $latLngArray = explode('|', $_POST['delivery_range_polygon']);
                    if (count($latLngArray) < 3) {
                        $this->error('请绘制一个合理的服务范围！');
                    } else {
                        $latLngData = array();
                        foreach ($latLngArray as $row) {
                            $latLng = explode('-', $row);
                            // 		                    $latLngData[] = array('lat' => $latLng[0], 'lng' => $latLng[1]);
                            $latLngData[] = $latLng[1] . ' ' . $latLng[0];//array('lat' => $latLng[0], 'lng' => $latLng[1]);
                        }
                        $latLngData[] = $latLngData[0];
                        $_POST['delivery_range_polygon'] = 'POLYGON((' . implode(',', $latLngData) . '))';
                    }
                } else {
                    $this->error('请绘制您的服务范围！');
                }
                unset($_POST['delivery_radius']);
            } else {
                unset($_POST['delivery_range_polygon']);
            }
            $_POST['store_id'] = $now_store['store_id'];
            if(substr($_POST['store_notice'], -1) == ' '){
                $_POST['store_notice'] = trim($_POST['store_notice']);
            }else{
                $_POST['store_notice'] = trim($_POST['store_notice']);
            }
            if(empty($_POST['store_category'])){
                $this->error('请至少选一个分类！');
            }
            $cat_ids = array();
            foreach ($_POST['store_category'] as $cat_a) {
                $a = explode('-', $cat_a);
                $cat_ids[] = array('cat_fid' => $a[0], 'cat_id' => $a[1]);
            }

            $leveloff = isset($_POST['leveloff']) ? $_POST['leveloff'] :false;
            unset($_POST['leveloff']);

            $newleveloff = array();
            if (!empty($leveloff)) {
                foreach ($leveloff as $kk => $vv) {
                    $vv['type'] = intval($vv['type']);
                    $vv['vv'] = intval($vv['vv']);
                    if (($vv['type'] > 0) && ($vv['vv'] > 0)) {
                        $vv['level'] = $kk;
                        $newleveloff[$kk] = $vv;
                    }
                }
            }

            $_POST['store_discount'] = intval(floatval($_POST['store_discount']) * 10);
            $_POST['store_discount'] = ($_POST['store_discount'] > 100 || $_POST['store_discount'] < 0) ? 0 : $_POST['store_discount'];

            $_POST['discount_type'] = isset($_POST['discount_type']) ? intval($_POST['discount_type']) : 0;
            $_POST['reduce_stock_type'] = isset($_POST['reduce_stock_type']) ? intval($_POST['reduce_stock_type']) : 0;
            $_POST['rollback_time'] = isset($_POST['rollback_time']) ? intval($_POST['rollback_time']) : 20;
            $_POST['rollback_time'] = max(10, $_POST['rollback_time']);

            $_POST['leveloff'] = !empty($newleveloff) ? serialize($newleveloff) : '';
            if($leveloff === false) unset($_POST['leveloff']);
            $database_merchant_store_shop = D('Merchant_store_shop');
            $deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
            //unset($_POST['deliver_type']);
            fdump($_POST, 'post');
            /******************************数据同步导入***************************************/
            $sysnc = isset($_POST['sysnc']) ? intval($_POST['sysnc']) : 0;
            unset($_POST['sysnc']);
            $store_shop = $database_merchant_store_shop->field(true)->where(array('store_id' => $store_id))->find();
            /*****************************数据同步导入****************************************/
            if ($store_shop) {

                if (in_array($store_shop['deliver_type'], array(0, 3)) && in_array($deliver_type, array(0, 3))) {//平台=>平台 配送距离不修改
                    unset($_POST['delivery_radius']);
                }

                if (in_array($store_shop['deliver_type'], array(1, 2, 4)) && in_array($deliver_type, array(0, 3))) {//商家=>平台 配送距离设置为0
                    $_POST['delivery_radius'] == 0;
                }
                if (empty($store_shop['create_time'])) $_POST['create_time'] = time();
                $operat_shop = $database_merchant_store_shop->data($_POST)->save();
            } else {
                if ($deliver_type == 0 || $deliver_type == 3) {
                    $_POST['delivery_radius'] == 0;
                }
                $_POST['create_time'] = time();
                $operat_shop = $database_merchant_store_shop->add($_POST);
                if ($sysnc) {
                    /*****************************数据同步导入****************************************/
                    $where = array('store_id' => $store_id);
                    $sorts = D('Meal_sort')->field(true)->where($where)->select();
                    $root_path = dirname(dirname(dirname(dirname(dirname(__FILE__)))));
                    foreach ($sorts as $sort) {
                        $old_sort_id = $sort['sort_id'];
                        unset($sort['sort_id']);
                        if ($sort_id = D('Shop_goods_sort')->add($sort)) {
                            $meals = D('Meal')->field(true)->where(array('store_id' => $store_id, 'sort_id' => $old_sort_id))->select();
                            foreach ($meals as $meal) {
                                //移动图片
                                if ($meal['image']) {
                                    $image_tmp = explode(',', $meal['image']);
                                    $dest_dir = $root_path.'/upload/goods/'.$image_tmp[0];
                                    if (file_exists($root_path . '/upload/meal/' . $image_tmp[0] . '/' . $image_tmp[1])) {
                                        dmkdir($dest_dir . '/' . $image_tmp[1]);
                                        copy($root_path . '/upload/meal/' . $image_tmp[0] . '/' . $image_tmp[1], $dest_dir . '/' . $image_tmp[1]);
                                    }
                                    if (file_exists($root_path . '/upload/meal/' . $image_tmp[0] . '/m_' . $image_tmp[1])) {
                                        dmkdir($dest_dir . '/m_' . $image_tmp[1]);
                                        copy($root_path . '/upload/meal/' . $image_tmp[0] . '/m_' . $image_tmp[1], $dest_dir . '/m_' . $image_tmp[1]);
                                    }
                                    if (file_exists($root_path . '/upload/meal/' . $image_tmp[0] . '/s_' . $image_tmp[1])) {
                                        dmkdir($dest_dir . '/s_' . $image_tmp[1]);
                                        copy($root_path . '/upload/meal/' . $image_tmp[0] . '/s_' . $image_tmp[1], $dest_dir . '/s_' . $image_tmp[1]);
                                    }

                                }
                                unset($meal['meal_id'], $meal['label'], $meal['vip_price']);
                                $meal['stock_num'] = $meal['stock_num'] == 0 ? -1 : $meal['stock_num'];
                                $meal['sort_id'] = $sort_id;
                                $meal['sell_day'] = 0;
                                $meal['today_sell_count'] = 0;
                                $meal['sell_mouth'] = 0;
                                D('Shop_goods')->add($meal);
                            }
                        }
                    }
                    /*****************************数据同步导入****************************************/
                }
            }

            //处理配送
            $deliver_store = D('Deliver_store')->field(true)->where(array('store_id' => $now_store['store_id']))->find();
            if ($deliver_type != 2) {
                $t_type = ($deliver_type == 0 || $deliver_type == 3) ? 0 : 1;
                $deliver['store_id'] = $now_store['store_id'];
                $deliver['mer_id'] = $now_store['mer_id'];
                $deliver['site'] = $now_store['adress'];
                $deliver['type'] = $t_type;
                $deliver['range'] = $_POST['delivery_radius'];
                if ($deliver_store) {
                    D('Deliver_store')->where(array('pigcms_id' => $deliver_store['pigcms_id']))->save($deliver);
                } else {
                    D('Deliver_store')->data($deliver)->add();
                }
            } elseif ($deliver_type == 2 && $deliver_store) {
                D('Deliver_store')->field(true)->where(array('store_id' => $now_store['store_id']))->save(array('type' => 2));
            }


            // 			if($database_merchant_store_meal->data($_POST)->save()){
            $database_shop_category_relation = D('Shop_category_relation');
            $condition_shop_category_relation['store_id'] = $now_store['store_id'];
            $database_shop_category_relation->where($condition_shop_category_relation)->delete();
            foreach($cat_ids as $key => $value){
                $data_shop_category_relation[$key]['cat_id'] = $value['cat_id'];
                $data_shop_category_relation[$key]['cat_fid'] = $value['cat_fid'];
                $data_shop_category_relation[$key]['store_id'] = $now_store['store_id'];
            }
            $database_shop_category_relation->addAll($data_shop_category_relation);

            //支付方式选择
            //$pay_method = D('Config')->get_pay_method();
            $pay_method = '';
            foreach ($_POST as $k=>$v){
                if(strpos($k,'paymethod') !== false){
                    $pay = explode('_',$k);
                    if($pay_method == '')
                        $pay_method = $pay[1];
                    else
                        $pay_method = $pay_method.'|'.$pay[1];
                }
            }
            $store_data['pay_method'] = $pay_method;
            D('Merchant_store')->where(array('store_id'=>$store_id))->save($store_data);

            $this->success('编辑成功！');
            // 			}else{
            // 				$this->error('编辑失败！请重试。');
            // 			}
        } else {
            $database_merchant_store_shop = D('Merchant_store_shop');
            $condition_merchant_store_shop['store_id'] = $now_store['store_id'];
            $store_shop = $database_merchant_store_shop->field(true)->where($condition_merchant_store_shop)->find();
            if ($store_shop && $store_shop['delivery_range_polygon']) {
                $store_shop['delivery_range_polygon'] = substr($store_shop['delivery_range_polygon'], 9, strlen($store_shop['delivery_range_polygon']) - 11);
                $lngLatData = explode(',', $store_shop['delivery_range_polygon']);
                array_pop($lngLatData);
                $lngLats = array();
                foreach ($lngLatData as $lnglat) {
                    $lng_lat = explode(' ', $lnglat);
                    $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
                }
                $store_shop['delivery_range_polygon'] = json_encode(array($lngLats));
            }
            $sysnc = empty($store_shop) ? 1 : 0;
            $this->assign('sysnc', $sysnc);

            $close_old_store = $now_store['store_type'] == 0 || $now_store['store_type'] == 2 ? 1 : 0;

            $this->assign('close_old_store', $close_old_store);
            //所有分类
            $database_shop_category = D('Shop_category');
            $category_list = $database_shop_category->lists();//field(true)->where(array('cat_status' => 1))->order('`cat_sort` DESC,`cat_id` ASC')->select();
            $this->assign('category_list', $category_list);

            //此店铺有的分类
            $database_shop_category_relation = D('Shop_category_relation');
            $condition_shop_category_relation['store_id'] = $now_store['store_id'];
            $relation_list = $database_shop_category_relation->field(true)->where($condition_shop_category_relation)->select();
            $relation_array = array();
            foreach ($relation_list as $key => $value) {
                array_push($relation_array, $value['cat_id']);
            }
            // 			echo '<pre/>';
            // 			print_r($now_store);die;

            $store_shop['store_discount'] *= 0.1;
            $this->assign('relation_array', $relation_array);
            $this->assign('store_shop', $store_shop);
            $this->assign('now_store', $now_store);

            $leveloff = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) :false;
            $tmparr = M('User_level')->order('id ASC')->select();
            $levelarr = array();
            if ($tmparr && $this->config['level_onoff']) {
                foreach ($tmparr as $vv) {
                    if (!empty($leveloff) && isset($leveloff[$vv['level']])) {
                        $vv['vv'] = $leveloff[$vv['level']]['vv'];
                        $vv['type'] = $leveloff[$vv['level']]['type'];
                    } else {
                        $vv['vv'] = '';
                        $vv['type'] = '';
                    }
                    $levelarr[$vv['level']] = $vv;
                }
            }
            unset($tmparr);
            $this->assign('levelarr', $levelarr);

            $pay_method = D('Config')->get_pay_method();
            //var_dump($pay_method);die();
            $this->assign('pay_method',$pay_method);

            $this->display();
        }
    }

    /**
     * 商品分类
     */
    public function goods_sort()
    {
        $fid = isset($_GET['fid']) ? intval($_GET['fid']) : 0;
        $now_store = $this->check_store(intval($_GET['store_id']));
        $shopGoodsSortDB = D('Shop_goods_sort');
        $sortList = array();
        if ($sort = $shopGoodsSortDB->field(true)->where(array('store_id' => $now_store['store_id'], 'sort_id' => $fid))->find()) {
            $ids = $shopGoodsSortDB->getIds($fid, $now_store['store_id']);
            if (count($ids) > 1) {
                $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $now_store['store_id'], 'sort_id' => array('in', $ids)))->order('sort_id ASC')->select();
            } else {
                $sortList = array($sort);
            }
        } else {
            $fid = 0;
        }
        $this->assign('now_store', $now_store);
        $this->assign('fid', $fid);
        $this->assign('sortList', $sortList);

        $where = array('store_id' => $now_store['store_id']);
        $where['fid'] = $fid;

        $sort_list = $shopGoodsSortDB->field(true)->where($where)->order('`sort` DESC,`sort_id` ASC')->select();
        foreach ($sort_list as &$value) {
            if ($now_store['is_mult_class'] == 0 && $value['operation_type'] == 2) {
                $value['operation_type'] = 0;
            }
            if ($value['week'] != null) {
                $week_arr = explode(',', $value['week']);
                $week_str = '';
                foreach ($week_arr as $k => $v) {
                    $week_str .= $this->get_week($v) . ' ';
                }
                $value['week_str'] = $week_str;
            }
        }
        $this->assign('sort_list', $sort_list);
        $this->display();
    }

    protected function get_week($num)
    {
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

    /**
     * 添加商品分类
     */
    public function sort_add()
    {
        $fid = isset($_REQUEST['fid']) ? intval($_REQUEST['fid']) : 0;
        $now_store = $this->check_store(intval($_GET['store_id']));

        if ($sort = M('Shop_goods_sort')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
            if ($now_store['is_mult_class'] == 0) {
                $this->error($sort['sort_name'] . '店铺暂未开启多级分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                exit;
            }
            if ($sort['level'] == self::GOODS_SORT_LEVEL) {
                $this->error($sort['sort_name'] . '分类下不能再增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                exit;
            }
        } else {
            $fid = 0;
            $sort = null;
        }

        if (IS_POST) {
            if (empty($_POST['sort_name'])) {
                $error_tips = '分类名称必填！'.'<br/>';
            } else {
                $database_goods_sort = D('Shop_goods_sort');
                $data_goods_sort['store_id'] = $now_store['store_id'];
                $data_goods_sort['sort_name'] = $_POST['sort_name'];
                $data_goods_sort['sort'] = intval($_POST['sort']);
                $data_goods_sort['sort_discount'] = intval(floatval($_POST['sort_discount']) * 10);
                $data_goods_sort['sort_discount'] = ($data_goods_sort['sort_discount'] > 100 || $data_goods_sort['sort_discount'] < 0) ? 0 : $data_goods_sort['sort_discount'];
                $data_goods_sort['is_weekshow'] = intval($_POST['is_weekshow']);
                $data_goods_sort['print_id'] = intval($_POST['print_id']);
                $data_goods_sort['fid'] = $fid;
                $data_goods_sort['level'] = 1;
                if ($sort) {
                    $data_goods_sort['level'] = $sort['level'] + 1;
                    if (M('Shop_goods')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
                        $this->error($sort['sort_name'] . '分类下有归属商品了，不能给它增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                        exit;
                    }
                }

                if ($data_goods_sort['level'] < self::GOODS_SORT_LEVEL) {
                    $data_goods_sort['operation_type'] = 2;
                } else {
                    $data_goods_sort['operation_type'] = 0;
                }
                if ($_POST['week']) {
                    $data_goods_sort['week'] = strval(implode(',', $_POST['week']));
                }
                if ($_FILES['image']['error'] != 4) {
                    $param = array('size' => $this->config['meal_pic_size']);
                    $param['thumb'] = true;
                    $param['imageClassPath'] = 'ORG.Util.Image';
                    $param['thumbPrefix'] = 'm_,s_';
                    $param['thumbMaxWidth'] = $this->config['meal_pic_width'];
                    $param['thumbMaxHeight'] = $this->config['meal_pic_height'];
                    $param['thumbRemoveOrigin'] = false;

                    $image = D('Image')->handle($this->merchant_session['mer_id'], 'goods_sort', 1, $param);

                    if ($image['error']) {
                        $error_tips .= $image['msg'] . '<br/>';
                    } else {
                        $_POST = array_merge($_POST, $image['title']);
                    }
                }
                if(!empty($_POST['image_select'])){
                    $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
                    $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

                    $tmp_img = explode(',',$_POST['image_select']);
                    $_POST['image'] = $rand_num.','.$tmp_img[1];
                }
                $data_goods_sort['image'] = $_POST['image'] ?: '';
                if ($database_goods_sort->data($data_goods_sort)->add()) {
                    if ($sort && $sort['operation_type'] == 2) {
                        $database_goods_sort->where(array('sort_id' => $sort['sort_id']))->save(array('operation_type' => 1));
                    }
                    $this->success('添加成功！！', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                    die;
                } else {
                    echo $database_goods_sort->_sql();
                    $this->error('添加失败！！请重试。', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                    die;
                }
            }
        }

        $print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
        foreach ($print_list as &$l) {
            if ($l['is_main']) {
                $l['name'] .= '(主打印机)';
            } else {
                $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
            }
        }
        $this->assign('sort', $sort);
        $this->assign('print_list', $print_list);
        $this->assign('now_store', $now_store);
        $this->assign('fid', $fid);
        $this->display();
    }


    /**
     * 修改商品分类
     */
    public function sort_edit()
    {
        $now_sort = $this->check_sort(intval($_GET['sort_id']));
        $now_store = $this->check_store($now_sort['store_id']);
        $fid = isset($_REQUEST['fid']) ? intval($_REQUEST['fid']) : 0;
        if ($sort = M('Shop_goods_sort')->field(true)->where(array('sort_id' => $fid))->find()) {
            // 	        if ($now_store['is_mult_class'] == 0) {
            // 	            $this->error($sort['sort_name'] . '店铺暂未开启多级分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
            // 	            exit;
            // 	        }
            if ($sort['level'] == self::GOODS_SORT_LEVEL) {
                $this->error($sort['sort_name'] . '分类下不能再增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                exit;
            }
        } else {
            $fid = 0;
            $sort = null;
        }
        if (IS_POST) {
            if (empty($_POST['sort_name'])) {
                $error_tips = '分类名称必填！'.'<br/>';
            } else {
                $database_goods_sort = D('Shop_goods_sort');
                $data_goods_sort['sort_id'] = $now_sort['sort_id'];
                $data_goods_sort['sort_name'] = $_POST['sort_name'];
                $data_goods_sort['sort'] = intval($_POST['sort']);
                $data_goods_sort['sort_discount'] = intval(floatval($_POST['sort_discount']) * 10);
                $data_goods_sort['sort_discount'] = ($data_goods_sort['sort_discount'] > 100 || $data_goods_sort['sort_discount'] < 0) ? 0 : $data_goods_sort['sort_discount'];
                $data_goods_sort['is_weekshow'] = intval($_POST['is_weekshow']);
                $data_goods_sort['week'] = implode(',',$_POST['week']);

                $data_goods_sort['print_id'] = intval($_POST['print_id']);
                $data_goods_sort['fid'] = $fid;

                //$data_goods_sort['level'] = 1;
                if ($sort) {
                    // 				    $data_goods_sort['level'] = $sort['level'] + 1;
                    if (M('Shop_goods')->field(true)->where(array('sort_id' => $fid, 'store_id' => $now_store['store_id']))->find()) {
                        $this->error($sort['sort_name'] . '分类下有归属商品了，不能给它增加子分类', U('Shop/goods_sort', array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                        exit;
                    }
                }

                // 				if ($data_goods_sort['level'] < self::GOODS_SORT_LEVEL) {
                // 				    $data_goods_sort['operation_type'] = 2;
                // 				} else {
                // 				    $data_goods_sort['operation_type'] = 0;
                // 				}

                if($_FILES['image']['error'] != 4){
                    $param = array('size' => $this->config['meal_pic_size']);
                    $param['thumb'] = true;
                    $param['imageClassPath'] = 'ORG.Util.Image';
                    $param['thumbPrefix'] = 'm_,s_';
                    $param['thumbMaxWidth'] = $this->config['meal_pic_width'];
                    $param['thumbMaxHeight'] = $this->config['meal_pic_height'];
                    $param['thumbRemoveOrigin'] = false;

                    $image = D('Image')->handle($this->merchant_session['mer_id'], 'goods_sort', 1, $param);

                    if ($image['error']) {
                        $error_tips .= $image['msg'] . '<br/>';
                    } else {
                        $_POST = array_merge($_POST, $image['title']);
                    }
                }
                if(!empty($_POST['image_select'])){
                    $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
                    $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);

                    $tmp_img = explode(',',$_POST['image_select']);
                    $_POST['image']=$rand_num.','.$tmp_img[1];
                }
                if ($_POST['image']) {
                    $data_goods_sort['image'] = $_POST['image'];
                }
                if ($database_goods_sort->data($data_goods_sort)->save()) {
                    if ($sort && $sort['operation_type'] == 2) {
                        $database_goods_sort->where(array('sort_id' => $sort['sort_id']))->save(array('operation_type' => 1));
                    }
                    $this->success('保存成功！！', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                    die;
                } else {
                    $this->error('保存失败！！您是不是没做过修改？请重试。', U('Shop/goods_sort',array('store_id' => $now_store['store_id'], 'fid' => $fid)));
                    die;
                }
            }
            $_POST['sort_id'] = $now_sort['sort_id'];
            $this->assign('now_sort', $_POST);
            $this->assign('ok_tips', $ok_tips);
            $this->assign('error_tips', $error_tips);
        }
        $now_sort['sort_discount'] *= 0.1;
        $this->assign('fid', $fid);
        $this->assign('now_sort', $now_sort);
        $this->assign('now_store', $now_store);
        $print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
        foreach ($print_list as &$l) {
            if ($l['is_main']) {
                $l['name'] .= '(主打印机)';
            } else {
                $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
            }
        }
        $this->assign('sort', $sort);
        $this->assign('print_list', $print_list);
        $this->display();
    }

    /* 分类状态 */
    public function sort_status()
    {
        $now_sort = $this->check_sort($_POST['id']);
        $now_store = $this->check_store($now_sort['store_id']);
        $database_goods_sort = D('Shop_goods_sort');
        $condition_goods_sort['sort_id'] = $now_sort['sort_id'];
        $data_goods_sort['is_weekshow'] = $_POST['type'] == 'open' ? '1' : '0';
        if ($database_goods_sort->where($condition_goods_sort)->data($data_goods_sort)->save()) {
            exit('1');
        } else {
            exit;
        }
    }

    /* 删除分类 */
    public function sort_del()
    {
        $now_sort = $this->check_sort($_GET['sort_id']);
        $now_store = $this->check_store($now_sort['store_id']);

        $count = D('Shop_goods')->where(array('sort_id' => $now_sort['sort_id'], 'store_id' => $now_sort['store_id']))->count();
        if ($count) $this->error('该分类下有商品，先删除商品后再来删除该分类');

        $sortCount = D('Shop_goods_sort')->where(array('fid' => $now_sort['sort_id'], 'store_id' => $now_sort['store_id']))->count();
        if ($sortCount) $this->error('该分类下有子分类，先删除子分类后再来删除该分类');

        $database_goods_sort = D('Shop_goods_sort');
        $condition_goods_sort['sort_id'] = $now_sort['sort_id'];
        if ($database_goods_sort->where($condition_goods_sort)->delete()) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    /* 菜品管理 */
    public function goods_list()
    {
        $now_sort = $this->check_sort($_GET['sort_id']);
        $now_store = $this->check_store($now_sort['store_id']);
        $this->assign('now_sort', $now_sort);
        $this->assign('now_store', $now_store);

        $database_goods = D('Shop_goods');
        $condition_goods['sort_id'] = $now_sort['sort_id'];
        $count_goods = $database_goods->where($condition_goods)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count_goods, 20);
        $goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();

        $plist = array();
        $prints = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
        foreach ($prints as $l) {
            if ($l['is_main']) {
                $l['name'] .= '(主打印机)';
            } else {
                $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
            }
            $plist[$l['pigcms_id']] = $l;
        }
        $today = date('Ymd');
        foreach ($goods_list as &$rl) {
            $rl['print_name'] = isset($plist[$rl['print_id']]['name']) ? $plist[$rl['print_id']]['name'] : '';
            if ($rl['sell_day'] != $today) {
                $rl['today_sell_count'] = 0;
            }
            if ($rl['stock_num'] == -1) {
                $rl['stock_num_t'] = '无限';
            } else {
                $rl['stock_num_t'] = max(0, $rl['stock_num'] - $rl['today_sell_count']);
            }
        }

        $this->assign('goods_list', $goods_list);
        $this->assign('pagebar', $p->show());

        $this->display();
    }
    /* 添加店铺 */
    public function goods_add()
    {
        $now_sort = $this->check_sort(intval($_GET['sort_id']));
        $now_store = $this->check_store($now_sort['store_id']);
        if (IS_POST) {
            //  			echo "<pre/>";
            //  			print_r($_POST);
            //  			die;

            if (empty($_POST['name'])) {
                $error_tips .= '商品名称必填！'.'<br/>';
            }
            if (empty($_POST['unit'])) {
                $error_tips .= '商品单位必填！'.'<br/>';
            }
            if (empty($_POST['price'])&&!$this->config['open_extra_price']) {
                $error_tips .= '商品价格必填！'.'<br/>';
            }
            if (empty($_POST['pic'])) {
                //$error_tips .= '请至少上传一张照片！'.'<br/>';
            }

            $_POST['des'] = fulltext_filter($_POST['des']);

            $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
            $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
            foreach($_POST['pic'] as $kp => $vp){
                $tmp_vp = explode(',', $vp);
                $_POST['pic'][$kp] = $rand_num . ',' . $tmp_vp[1];
            }
            $_POST['pic'] = implode(';', $_POST['pic']);
            $_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;


            if ($_POST['specs']) {
                foreach ($_POST['specs'] as $val) {
                    if (empty($val)) {
                        $error_tips .= '请给规格取名，若不需要的请删除后重新生成'.'<br/>';
                    }
                }
            }

            if ($_POST['spec_val']) {
                foreach ($_POST['spec_val'] as $rowset) {
                    foreach ($rowset as $val) {
                        if (empty($val)) {
                            $error_tips .= '请给规格的属性值取名，若不需要的请删除后重新生成'.'<br/>';
                        }
                    }
                }
            }


            if ($_POST['properties']) {
                foreach ($_POST['properties'] as $val) {
                    if (empty($val)) {
                        $error_tips .= '请给属性取名，若不需要的请删除后重新生成'.'<br/>';
                    }
                }
            }

            if ($_POST['properties_val']) {
                foreach ($_POST['properties_val'] as $rowset) {
                    foreach ($rowset as $val) {
                        if (empty($val)) {
                            $error_tips .= '请给属性的属性值取名，若不需要的请删除后重新生成'.'<br/>';
                        }
                    }
                }
            }

            if (isset($_POST['prices']) && $_POST['prices']) {
                foreach ($_POST['prices'] as $rowset) {
                    foreach ($rowset as $val) {
                        if (empty($val)) {
                            $error_tips .= '所有的现价必须要填写'.'<br/>';
                        }
                    }
                }
            }

            $sort_id = $now_sort['sort_id'];
            for ($i = 1; $i <= self::GOODS_SORT_LEVEL; $i++) {
                if (isset($_POST['sort_id_' . $i]) && intval($_POST['sort_id_' . $i])) {
                    $sort_id = intval($_POST['sort_id_' . $i]);
                    unset($_POST['sort_id_' . $i]);
                }
            }
            $shopGoodsSortDB = M('Shop_goods_sort');
            if ($sort = $shopGoodsSortDB->field(true)->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
                if ($fsort = $shopGoodsSortDB->field(true)->where(array('fid' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
                    $error_tips .= '该分类有子分类，不能直接添加商品'.'<br/>';
                } elseif ($sort['operation_type'] != 0) {
                    $shopGoodsSortDB->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->save(array('operation_type' => 0));
                }
            } else {
                $error_tips .= '商品分类不存在'.'<br/>';
            }
            $_POST['seckill_open_time'] = strtotime($_POST['seckill_open_time'] . ":00");
            $_POST['seckill_close_time'] = strtotime($_POST['seckill_close_time'] . ":00");
            if (empty($error_tips)) {
                $_POST['sort_id'] = $sort_id;
                $_POST['store_id'] = $now_store['store_id'];
                $_POST['last_time'] = $_SERVER['REQUEST_TIME'];

                if ($goods_id = D('Shop_goods')->save_post_form($_POST, $now_store['store_id'])) {
                    D('Image')->update_table_id($_POST['image'], $goods_id, 'goods');
                    $this->success('添加成功！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'])));
                    die;
                    $ok_tips = '添加成功！';
                } else {
                    $this->error('添加失败！请重试！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'])));
                    die;
                    $error_tips = '添加失败！请重试。';
                }
            } else {
                $return = $this->format_data($_POST);
                $_POST['json'] = isset($return['json']) ? json_encode($return['json']) : '';
                $_POST['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
                $_POST['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
                $_POST['list'] = isset($return['list']) ? $return['list'] : '';

                $this->assign('now_goods', $_POST);
            }

            $this->assign('ok_tips', $ok_tips);
            $this->assign('error_tips', $error_tips);
        } else {
            $this->assign('now_goods', array('seckill_open_time' => strtotime(date('Y-m-d') . ' 08:00:00'), 'seckill_close_time' => strtotime(date('Y-m-d') . ' 10:00:00')));
        }
        $print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
        foreach ($print_list as &$l) {
            if ($l['is_main']) {
                $l['name'] .= '(主打印机)';
            } else {
                $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
            }
        }
        $this->assign('print_list', $print_list);
        $category_list = D('Goods_category')->get_list();
        $this->assign('category_list', json_encode($category_list));

        $sort_list = D('Shop_goods_sort')->lists($now_store['store_id'], false);
        $this->assign('sort_list', json_encode($sort_list));
        $ids = D('Shop_goods_sort')->getIds($now_sort['sort_id'], $now_store['store_id']);
        $this->assign('select_ids', json_encode($ids));

        $this->assign('now_sort', $now_sort);
        $this->assign('now_store', $now_store);

        $this->assign('express_template', D('Express_template')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->select());
        $this->display();
    }

    public function ajax_goods_properties()
    {
        $cat_id = isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
        $goods_id = isset($_POST['goods_id']) ? intval($_POST['goods_id']) : 0;
        $properties = D('Goods_properties')->field(true)->where(array('status' => 1, 'cat_id' => $cat_id))->select();
        if ($properties) {
            $value_ids = array();
            $relations = D('Goods_properties_relation')->field(true)->where(array('gid' => $goods_id))->select();
            foreach ($relations as $r) {
                $value_ids[] = $r['pid'];
            }
            $pids = array();
            $list = array();
            foreach ($properties as $row) {
                $pids[] = $row['id'];
                $row['value_list'] = null;
                $list[$row['id']] = $row;
            }
            $value_list = D('Goods_properties_value')->field(true)->where(array('pid' => array('in', $pids)))->select();
            foreach ($value_list as $v) {
                if (isset($list[$v['pid']])) {
                    if (in_array($v['id'], $value_ids)) {
                        $v['checked'] = 1;
                    } else {
                        $v['checked'] = 0;
                    }
                    $list[$v['pid']]['value_list'][] = $v;
                }
            }
            $data = array();
            foreach ($list as $row) {
                if (isset($row['value_list']) && $row['value_list']) {
                    $data[] = $row;
                }
            }
            exit(json_encode(array('error_code' => false, 'data' => $data)));
        } else {
            exit(json_encode(array('error_code' => true, 'msg' => '没有数据')));
        }
    }

    public function ajax_upload_pic()
    {
        if ($_FILES['file']['error'] != 4) {
            $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
            $shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
            $store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
            if ($store_theme) {
                $width = '900,450';
                $height = '900,450';
            } else {
                $width = '900,450';
                $height = '500,250';
            }
            $param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
            $image = D('Image')->handle($this->merchant_session['mer_id'], 'goods', 1, $param);
            if ($image['error']) {
                exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
            } else {
                $title = $image['title']['file'];
                $goods_image_class = new goods_image();
                $url = $goods_image_class->get_image_by_path($title, 's');
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            }
        } else {
            exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
        }
    }

    /* 编辑商品 */
    public function goods_edit()
    {
        $now_goods = $this->check_goods($_GET['goods_id']);
        $now_sort = $this->check_sort($now_goods['sort_id']);
        $now_store = $this->check_store($now_sort['store_id']);
        if (IS_POST) {
            if (empty($_POST['name'])) {
                $error_tips .= '商品名称必填！'.'<br/>';
            }
            if (empty($_POST['unit'])) {
                $error_tips .= '商品单位必填！'.'<br/>';
            }
            if (empty($_POST['price'])&&!$this->config['open_extra_price']) {
                $error_tips .= '商品价格必填！'.'<br/>';
            }
            if (empty($_POST['pic'])) {
                //$error_tips .= '请至少上传一张照片！'.'<br/>';
            }

            $_POST['des'] = fulltext_filter($_POST['des']);

            $img_mer_id = sprintf("%09d", $this->merchant_session['mer_id']);
            $rand_num = substr($img_mer_id, 0, 3) . '/' . substr($img_mer_id, 3, 3) . '/' . substr($img_mer_id, 6, 3);
            foreach($_POST['pic'] as $kp => $vp){
                $tmp_vp = explode(',', $vp);
                $_POST['pic'][$kp] = $rand_num . ',' . $tmp_vp[1];
            }
            $_POST['pic'] = implode(';', $_POST['pic']);
            $_POST['print_id'] = isset($_POST['print_id']) ? intval($_POST['print_id']) : 0;

            if ($_POST['specs']) {
                foreach ($_POST['specs'] as $val) {
                    if (empty($val)) {
                        $error_tips .= '请给规格取名，若不需要的请删除后重新生成'.'<br/>';
                    }
                }
            }

            if ($_POST['spec_val']) {
                foreach ($_POST['spec_val'] as $rowset) {
                    foreach ($rowset as $val) {
                        if (empty($val)) {
                            $error_tips .= '请给规格的属性值取名，若不需要的请删除后重新生成'.'<br/>';
                        }
                    }
                }
            }

            if ($_POST['properties']) {
                foreach ($_POST['properties'] as $val) {
                    if (empty($val)) {
                        $error_tips .= '请给属性取名，若不需要的请删除后重新生成'.'<br/>';
                    }
                }
            }

            if ($_POST['properties_val']) {
                foreach ($_POST['properties_val'] as $rowset) {
                    foreach ($rowset as $val) {
                        if (empty($val)) {
                            $error_tips .= '请给属性的属性值取名，若不需要的请删除后重新生成'.'<br/>';
                        }
                    }
                }
            }

            $sort_id = $now_sort['sort_id'];
            for ($i = 1; $i <= self::GOODS_SORT_LEVEL; $i++) {
                if (isset($_POST['sort_id_' . $i]) && intval($_POST['sort_id_' . $i])) {
                    $sort_id = intval($_POST['sort_id_' . $i]);
                    unset($_POST['sort_id_' . $i]);
                }
            }
            $shopGoodsSortDB = M('Shop_goods_sort');
            if ($sort = $shopGoodsSortDB->field(true)->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
                if ($fsort = $shopGoodsSortDB->field(true)->where(array('fid' => $sort_id, 'store_id' => $now_store['store_id']))->find()) {
                    $error_tips .= '该分类有子分类，不能直接添加商品'.'<br/>';
                } elseif ($sort['operation_type'] != 0) {
                    $shopGoodsSortDB->where(array('sort_id' => $sort_id, 'store_id' => $now_store['store_id']))->save(array('operation_type' => 0));
                }
            } else {
                $error_tips .= '商品分类不存在'.'<br/>';
            }

            $_POST['seckill_open_time'] = strtotime($_POST['seckill_open_time'] . ":00");
            $_POST['seckill_close_time'] = strtotime($_POST['seckill_close_time'] . ":00");

            if (empty($error_tips)) {
                $_POST['goods_id'] = $now_goods['goods_id'];
                $_POST['sort_id'] = $sort_id;
                $_POST['store_id'] = $now_store['store_id'];
                $_POST['last_time'] = $_SERVER['REQUEST_TIME'];

                if ($goods_id = D('Shop_goods')->save_post_form($_POST, $now_store['store_id'])) {
                    D('Image')->update_table_id($_POST['image'], $goods_id, 'goods');
                    $this->success('保存成功！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'],'page'=>$_GET['page'])));
                    die;
                    $ok_tips = '保存成功！';
                } else {
                    $this->error('保存失败！请重试！', U('Shop/goods_list', array('sort_id' => $now_sort['sort_id'])));
                    die;
                    $error_tips = '保存失败！请重试。';
                }
            } else {
                $return = $this->format_data($_POST);
                $_POST['json'] = isset($return['json']) ? json_encode($return['json']) : '';
                $_POST['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
                $_POST['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
                $_POST['list'] = isset($return['list']) ? $return['list'] : '';
                $this->assign('now_goods', $_POST);
            }

            $this->assign('ok_tips', $ok_tips);
            $this->assign('error_tips', $error_tips);
        }

        $print_list = D('Orderprinter')->where(array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']))->select();
        foreach ($print_list as &$l) {
            if ($l['is_main']) {
                $l['name'] .= '(主打印机)';
            } else {
                $l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
            }
        }
        $this->assign('print_list', $print_list);
        $category_list = D('Goods_category')->get_list();
        $this->assign('category_list', json_encode($category_list));

        $sort_list = D('Shop_goods_sort')->lists($now_store['store_id'], false);
        $this->assign('sort_list', json_encode($sort_list));
        $ids = D('Shop_goods_sort')->getIds($now_sort['sort_id'], $now_store['store_id']);
        $this->assign('select_ids', json_encode($ids));

        $this->assign('now_goods', $now_goods);
        $this->assign('now_sort', $now_sort);
        $this->assign('now_store', $now_store);
        $this->assign('express_template', D('Express_template')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id']))->select());
        $this->display();
    }


    /* 商品删除 */
    public function goods_del()
    {
        $now_goods = $this->check_goods($_GET['goods_id']);
        $now_sort = $this->check_sort($now_goods['sort_id']);
        $now_store = $this->check_store($now_sort['store_id']);

        $database_goods = D('Shop_goods');
        $condition_goods['goods_id'] = $now_goods['goods_id'];
        if ($database_goods->where($condition_goods)->delete()) {
            $spec_obj = M('Shop_goods_spec'); //规格表
            $old_spec = $spec_obj->field(true)->where(array('goods_id' => $now_goods['goods_id'], 'store_id' => $now_sort['store_id']))->select();
            foreach ($old_spec as $os) {
                $delete_spec_ids[] = $os['id'];
            }
            $spec_obj->where(array('goods_id' => $now_goods['goods_id'], 'store_id' => $now_sort['store_id']))->delete();
            if ($delete_spec_ids) {
                $old_spec_val = M('Shop_goods_spec_value')->where(array('sid' => array('in', $delete_spec_ids)))->delete();
            }
            M('Shop_goods_properties')->where(array('goods_id' => $now_goods['goods_id']))->delete();
            $this->success('删除成功！');
        }else{
            $this->error('删除失败！请检查后重试。');
        }
    }
    /* 商品复制 */
    public function goods_copy(){
        $now_goods = $this->check_goods($_GET['goods_id']);
        $database_goods = D('Shop_goods');
        $condition_goods['goods_id'] = $now_goods['goods_id'];

        $goods = $database_goods->where($condition_goods)->find();
        if($goods){
            //添加新产品 去掉id 清零
            unset($goods['goods_id']);
            $goods['name'] = $goods['name'].'_Copy';
            $goods['sell_count'] = 0;
            $goods['sell_mouth'] = 0;
            $goods['today_sell_count'] = 0;
            $goods['sell_day'] = 0;
            $goods['today_sell_spec'] = '';

            $new_goods_id = $database_goods->add($goods);
            //如果有规格设置
            if($goods['spec_value'] != ''){
                $spec = D('Shop_goods_spec')->where(array('goods_id'=>$now_goods['goods_id']))->select();

                foreach ($spec as $v){
                    $new_spac['goods_id'] = $new_goods_id;
                    $new_spac['store_id'] = $v['store_id'];
                    $new_spac['name'] = $v['name'];

                    $spac_id = D('Shop_goods_spec')->add($new_spac);

                    $spac_val = D('Shop_goods_spec_value')->where(array('sid'=>$v['id']))->order('id asc')->select();

                    foreach ($spac_val as $val){
                        $new_val['sid'] = $spac_id;
                        $new_val['name'] = $val['name'];

                        $new_val_id = D('Shop_goods_spec_value')->add($new_val);

                        $goods['spec_value'] = str_replace($val['id'],$new_val_id,$goods['spec_value']);
                    }
                }

                $database_goods->where(array('goods_id'=>$new_goods_id))->save(array('spec_value'=>$goods['spec_value']));
            }
            //如果有属性
            if($goods['is_properties'] == 1){
                $properties = D('Shop_goods_properties')->where(array('goods_id'=>$now_goods['goods_id']))->select();
                $add_list = array();
                foreach($properties as $pro){
                    $new_pro['goods_id'] = $new_goods_id;
                    $new_pro['name'] = $pro['name'];
                    $new_pro['val'] = $pro['val'];
                    $new_pro['num'] = $pro['num'];

                    $add_list[] = $new_pro;
                }

                D('Shop_goods_properties')->addAll($add_list);
            }
        }
        $this->success('复制成功！');
    }
    /* 商品状态 */
    public function goods_status()
    {
        $now_goods = $this->check_goods($_POST['id']);
        $now_sort = $this->check_sort($now_goods['sort_id']);
        $now_store = $this->check_store($now_sort['store_id']);

        $database_goods = D('Shop_goods');
        $condition_goods['goods_id'] = $now_goods['goods_id'];
        $data_goods['status'] = $_POST['type'] == 'open' ? '1' : '0';
        if($database_goods->where($condition_goods)->data($data_goods)->save()){
            exit('1');
        }else{
            exit;
        }
    }

    /* 检测店铺存在，并检测是不是归属于商家 */
    protected function check_store($store_id)
    {
        $database_merchant_store = D('Merchant_store');
        $condition_merchant_store['store_id'] = $store_id;
        $condition_merchant_store['mer_id'] = $this->merchant_session['mer_id'];
        $now_store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
        if (empty($now_store)) {
            $this->error('店铺不存在！');
        } else {
            //return $now_store;
            if ($now_shop = D('Merchant_store_shop')->field(true)->where($condition_merchant_store)->find()) {
                if (!empty($now_shop['background'])) {
                    $image_tmp = explode(',', $now_shop['background']);
                    $now_shop['background_image'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
                }
                return array_merge($now_store, $now_shop);
            }
            return $now_store;
            $now_shop = D('Merchant_store_shop')->field(true)->where($condition_merchant_store)->find();
            return array_merge($now_store, $now_shop);
        }
    }
    /* 检测分类存在 */
    protected function check_sort($sort_id)
    {
        $database_goods_sort = D('Shop_goods_sort');
        $condition_goods_sort['sort_id'] = $sort_id;
        $now_sort = $database_goods_sort->field(true)->where($condition_goods_sort)->find();
        if (empty($now_sort)) {
            $this->error('分类不存在！');
        }
        if(!empty($now_sort['image'])){
            $sort_image_class = new goods_sort_image();
            $now_sort['see_image'] = $sort_image_class->get_image_by_path($now_sort['image'],$this->config['site_url'],'s');
        }
        if ($now_sort['week'] != null) {
            $now_sort['week'] = explode(',', $now_sort['week']);
        }
        return $now_sort;
    }
    /* 检测商品存在 */
    protected function check_goods($goods_id)
    {
        $database_shop_goods = D('Shop_goods');
        $condition_goods['goods_id'] = $goods_id;
        $now_goods = $database_shop_goods->field(true)->where($condition_goods)->find();
        if(empty($now_goods)){
            $this->error('商品不存在！');
        }
        if(!empty($now_goods['image'])){
            $goods_image_class = new goods_image();
            $tmp_pic_arr = explode(';', $now_goods['image']);
            foreach ($tmp_pic_arr as $key => $value) {
                $now_goods['pic_arr'][$key]['title'] = $value;
                $now_goods['pic_arr'][$key]['url'] = $goods_image_class->get_image_by_path($value, 's');
            }
        }

        $return = $database_shop_goods->format_spec_value($now_goods['spec_value'], $now_goods['goods_id']);
        $now_goods['json'] = isset($return['json']) ? json_encode($return['json']) : '';
        $now_goods['properties_list'] = isset($return['properties_list']) ? $return['properties_list'] : '';
        $now_goods['spec_list'] = isset($return['spec_list']) ? $return['spec_list'] : '';
        $now_goods['list'] = isset($return['list']) ? $return['list'] : '';

        // 		if ($now_goods['spec_value']) {
        // 			$now_goods['spec_value'] = unserialize($now_goods['spec_value']);
        // 			$specs = array();
        // 			$properties = array();
        // 			$json = array();
        // 			foreach ($now_goods['spec_value'] as &$row) {
        // 				$index = '';
        // 				$pre = '';
        // 				foreach ($row['spec'] as $r) {
        // 					$index .= $pre . 'id_' . $r['spec_val_id'];
        // 					$pre = '_';
        // 					if (!isset($specs[$r['spec_id']])) {
        // 						$specs[$r['spec_id']] = array('id' => $r['spec_id'], 'name' => $r['spec_name']);
        // 					}
        // 					$specs[$r['spec_id']]['val'][$r['spec_val_id']] = array('id' => $r['spec_val_id'], 'name' => $r['spec_val_name']);
        // 				}
        // 				$tdata = array();
        // 				foreach ($row['properties'] as $k => $r) {
        // 					$tdata['num' . $k . '[]'] = $r['num'];
        // 					if (!isset($properties[$r['id']])) {
        // 						$properties[$r['id']] = array('id' => $r['id'], 'name' => $r['name'], 'val' => explode(',', $r['val']));
        // 					}
        // 				}
        // 				$row['index'] = $index;
        // 				$json[$index] = $tdata;
        // 				$json[$index]['old_prices[]'] = $row['old_price'];
        // 				$json[$index]['prices[]'] = $row['price'];
        // 				$json[$index]['seckill_prices[]'] = $row['seckill_price'];
        // 				$json[$index]['stock_nums[]'] = $row['stock_num'];
        // 			}
        // // 			foreach ($specs as $tr) {
        // // 				$now_goods['specs'][] = $tr;
        // // 			}
        // // 			foreach ($properties as $pr) {
        // // 				$now_goods['properties'][] = $pr;
        // // 			}

        // 			$now_goods['json'] = json_encode($json);
        // 			$now_goods['specs'] = $specs;
        // 			$now_goods['properties'] = $properties;

        // 			echo "<Pre/>";
        // 			print_r($now_goods);die;
        // 		}
        return $now_goods;
    }

    private function format_spec($a, $i, $str, &$return)
    {
        if ($i == 0) {
            $ii = $i + 1;
            foreach ($a[$i] as $val) {
                $t = $str ? $str . '_' : '';
                if ($ii == count($a)) {
                    $return[] = $t . $val;
                } else {
                    $this->format_spec($a, $ii, $t . $val, $return);
                }
            }
        } else if ($i == count($a) - 1) {
            foreach ($a[$i] as $val) {
                $t = $str ? $str . '_' : '';
                $return[] = $t . $val;
            }
        } else {
            $ii = $i + 1;
            foreach ($a[$i] as $val) {
                $t = $str ? $str . '_' : '';
                $this->format_spec($a, $ii, $t . $val, $return);
            }
        }
    }

    public function format_data($data)
    {
        $spec_list = array();
        foreach ($data['spec_id'] as $i => $id) {
            $id = intval($id);
            $t_i = $id ? $id : 'i_' . $i;
            $spec_list[$t_i] = array('id' => $id, 'name' => $data['specs'][$i]);

            foreach ($data['spec_val_id'][$i] as $ii => $vid) {
                $vid = intval($vid);
                $v_i = $vid ? $vid : 'v_' . $ii;
                $spec_list[$t_i]['list'][$v_i] = array('id' => $vid,  'name' => $data['spec_val'][$i][$ii]);
            }
        }

        $properties_list = array();
        foreach ($data['properties_id'] as $pi => $pid) {
            $pid = intval($pid);
            $p_i = $pid ? $pid : 'p_' . $pi;
            $properties_list[$p_i] = array('id' => $pid, 'name' => $data['properties'][$pi], 'val' => $data['properties_val'][$pi]);
        }


        $for_data = array();
        foreach ($data['spec_val_id'] as $di => $dr) {
            foreach ($dr as $d => $id_t) {
                $for_data[$di][$d] = $di . '_' . $d;
            }
        }

        $formart_data = array();
        $this->format_spec($for_data, 0, '', $formart_data);

        // 		echo "<Pre/>";
        // 		print_r($formart_data);
        // 		die;

        $list = array();
        foreach ($formart_data as $fi => $string) {
            $array = explode('_', $string);
            $array = array_chunk($array, 2);
            // 		foreach ($data['spec_val_id'] as $k => $rowset) {
            $index = $pre = '';
            $tdata = array();
            foreach ($array as $irow) {
                $k = $irow[0];
                $ki = $irow[1];
                $r = $data['spec_val_id'][$irow[0]][$irow[1]];
                // 			foreach ($rowset as $ki => $r) {
                if ($r) {
                    $index .= $pre . 'id_' . $r;
                } else {
                    $index .= $pre . 'index_' . $ki;
                }
                $pre = '_';
                $tdata[] = array('spec_val_id' => $r, 'spec_val_name' => $data['spec_val'][$k][$ki]);
            }
            $list[$index]['index'] = $index;
            $list[$index]['spec'] = $tdata;
            $list[$index]['old_price'] = $data['old_prices'][$fi];
            $list[$index]['price'] = $data['prices'][$fi];
            $list[$index]['seckill_price'] = $data['seckill_prices'][$fi];
            $list[$index]['stock_num'] = $data['stock_nums'][$fi];
            $list[$index]['number'] = $data['numbers'][$fi];
            $pt_data = array();
            foreach ($data['properties'] as $pin => $pr) {
                $pt_data[] = array('id' => $data['properties_id'][$pin], 'num' => $data['num' . $fi][$pin], 'name' => $pr);
                $ptdata['num' . $pin . '[]'] = $data['num' . $fi][$pin];
            }
            $list[$index]['properties'] = $pt_data;

            $json[$index] = $ptdata;
            $json[$index]['old_prices[]'] = $data['old_prices'][$fi];
            $json[$index]['prices[]'] = $data['prices'][$fi];
            $json[$index]['seckill_prices[]'] = $data['seckill_prices'][$fi];
            $json[$index]['stock_nums[]'] = $data['stock_nums'][$fi];
            $json[$index]['numbers[]'] = $data['numbers'][$fi];
        }
        // 		echo "<Pre/>";
        // 		print_r(array('spec_list' => $spec_list, 'properties_list' => $properties_list, 'list' => $list, 'json' => $json));
        // 		die;
        return array('spec_list' => $spec_list, 'properties_list' => $properties_list, 'list' => $list, 'json' => $json);

    }

    public function discount()
    {
        $now_store = $this->check_store($_GET['store_id']);
        $this->assign('store', $now_store);
        $discount = D('Shop_discount')->field(true)->where(array('store_id' => $now_store['store_id']))->select();
        $this->assign('discount_list', $discount);
        $this->display();
    }

    public function discount_add()
    {
        $now_store = $this->check_store($_GET['store_id']);
        $this->assign('now_store',$now_store);

        if (IS_POST) {
            $database_discount = D('Shop_discount');
            $data_discount['store_id'] = $now_store['store_id'];
            $data_discount['mer_id'] = $now_store['mer_id'];
            $data_discount['full_money'] = $_POST['full_money'];
            $data_discount['reduce_money'] = $_POST['reduce_money'];
            $data_discount['type'] = intval($_POST['type']);
            $data_discount['status'] = intval($_POST['status']);
            $data_discount['source'] = 1;
            if ($database_discount->data($data_discount)->add()) {
                $this->success('添加成功！！', U('Shop/discount',array('store_id' => $now_store['store_id'])));
                die;
                $ok_tips = '添加成功！！';
            }else{
                $this->error('添加失败！！请重试。', U('Shop/discount',array('store_id' => $now_store['store_id'])));
                die;
                $error_tips = '添加失败！！请重试。';
            }
            if(!empty($error_tips)){
                $this->assign('now_discount', $_POST);
            }
            $this->assign('ok_tips', $ok_tips);
            $this->assign('error_tips', $error_tips);
        }
        $this->display();
    }

    public function discount_edit()
    {
        $now_store = $this->check_store($_GET['store_id']);
        if (!($discount = D('Shop_discount')->field(true)->where(array('id' => intval($_GET['id']), 'store_id' => $now_store['store_id']))->find())) {
            $this->error('不存在的优惠，请查证后修改。', U('Shop/discount',array('store_id' => $now_store['store_id'])));
        }
        $this->assign('now_store',$now_store);

        if (IS_POST) {
            $database_discount = D('Shop_discount');
            $data_discount['id'] = $discount['id'];
            $data_discount['store_id'] = $now_store['store_id'];
            $data_discount['mer_id'] = $now_store['mer_id'];
            $data_discount['full_money'] = $_POST['full_money'];
            $data_discount['reduce_money'] = $_POST['reduce_money'];
            $data_discount['type'] = intval($_POST['type']);
            $data_discount['status'] = intval($_POST['status']);
            $data_discount['source'] = 1;
            if ($database_discount->data($data_discount)->save()) {
                $this->success('添加成功！！', U('Shop/discount',array('store_id' => $now_store['store_id'])));
                die;
                $ok_tips = '添加成功！！';
            }else{
                $this->error('添加失败！！请重试。', U('Shop/discount',array('store_id' => $now_store['store_id'])));
                die;
                $error_tips = '添加失败！！请重试。';
            }
            if(!empty($error_tips)){
                $this->assign('now_discount', $_POST);
            }
            $this->assign('ok_tips', $ok_tips);
            $this->assign('error_tips', $error_tips);
        } else {
            $this->assign('now_discount', $discount);
        }
        $this->display();
    }

    public function order()
    {
        $now_store = $this->check_store($_GET['store_id']);
        $this->assign('now_store', $now_store);
        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= $type . ' ' . $sort . ',';
            $order_sort .= 'pay_time DESC';
        } else {
            $order_sort .= 'pay_time DESC';
        }

        $where = array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']);
        if ($status != -1) {
            $where['status'] = $status;
        }

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'real_orderid') {
                $where['real_orderid'] = htmlspecialchars($_GET['keyword']);
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
                unset($where['orderid']);
                $where['order_id'] = $tmp_result['order_id'];
            } elseif ($_GET['searchtype'] == 'name') {
                $where['username'] = htmlspecialchars($_GET['keyword']);
            } elseif ($_GET['searchtype'] == 'phone') {
                $where['userphone'] = htmlspecialchars($_GET['keyword']);
            } elseif ($_GET['searchtype'] == 'third_id') {
                $where['third_id'] =$_GET['keyword'];
            }
        }

        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';

        if($status == 100){
            $where['paid'] = 0;
        }else if ($status != -1) {
            $where['status'] = $status;
        }
        if($pay_type&&$pay_type!='balance'){
            $where['pay_type'] = $pay_type;
        }else if($pay_type=='balance'){
            $where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition_where['_string']=$time_condition;
        }

        $order_lsit = D('Shop_order')->get_order_list($where, $order_sort, 2);
        $this->assign('status_list', D('Shop_order')->status_list);
        $this->assign($order_lsit);
        $this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));

        $field = 'sum(price) AS total_price, sum(price - card_price - merchant_balance - balance_pay - payment_money - score_deducte - coupon_price) AS offline_price, sum(card_price + merchant_balance + balance_pay + payment_money + score_deducte + coupon_price) AS online_price';
        $count_where = "store_id='{$now_store['store_id']}' AND paid=1 AND is_del=0 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
        $result_total = D('Shop_order')->field($field)->where($count_where)->select();
        $result_total = isset($result_total[0]) ? $result_total[0] : '';
        $this->assign($result_total);
        $pay_method = D('Config')->get_pay_method('','',0);
        $this->assign('pay_method',$pay_method);

        $this->display();
    }

    public function order_detail()
    {
        if(strlen($_GET['order_id'])>=20){
            $now_shop_order = D('Shop_order')->where(array('real_orderid'=>$_GET['order_id']))->find();
            $_GET['order_id'] = $now_shop_order['order_id'];
        }

        // 		echo "<pre/>";
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'mer_id' => $this->merchant_session['mer_id']));
        // 		print_r($order);
        // 		die;
        $store = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
        $this->assign('store', $store);
        $this->assign('order', $order);
        $this->display();
    }

    public function ajax_del_pic() {
        // 		$group_image_class = new goods_image();
        // 		$group_image_class->del_image_by_path($_POST['path']);
    }

    public function clone_goods()
    {
        $source_store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $store_ids = isset($_POST['store_ids']) ? $_POST['store_ids'] : 0;

        if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $source_store_id, 'have_shop' => 1))->find()) {
            if ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $source_store_id))->find()) {

            } else {
                $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
            }
        } else {
            $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
        }
        foreach ($store_ids as $store_id) {
            if ($target_store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
                if (!$target_shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
                    continue;
                }
            } else {
                continue;
            }

            $goods_sorts = M('Shop_goods_sort')->field(true)->where(array('store_id' => $source_store_id))->order('level ASC')->select();
            foreach ($goods_sorts as $sv) {
                $oldIds[$sv['sort_id']] = $sv['fid'];//id ==> fid
            }
            $listOldFidToNewFid = array();
            foreach ($goods_sorts as $sort) {
                $source_sort_id = $sort['sort_id'];
                if ($target_sort = M('Shop_goods_sort')->field(true)->where(array('store_id' => $store_id, 'sort_name' => $sort['sort_name']))->find()) {
                    $target_sort_id = $target_sort['sort_id'];
                } else {
                    $sort['store_id'] = $store_id;
                    unset($sort['sort_id']);
                    $sort['fid'] = 0;
                    if (isset($oldIds[$source_sort_id]) && $oldIds[$source_sort_id]) {// oldFId = $oldIds[$source_sort_id];
                        $sort['fid'] = isset($listOldFidToNewFid[$oldIds[$source_sort_id]]) ? $listOldFidToNewFid[$oldIds[$source_sort_id]] : 0;
                    }
                    $target_sort_id = M('Shop_goods_sort')->add($sort);
                }
                $listOldFidToNewFid[$source_sort_id] = $target_sort_id;//oldID ==> newID

                $goods_list = M('Shop_goods')->field(true)->where(array('store_id' => $source_store_id, 'sort_id' => $source_sort_id))->select();
                foreach ($goods_list as $goods) {
                    if ($tmp_goods = M('Shop_goods')->field(true)->where(array('name' => $goods['name'], 'store_id' => $store_id))->find()) {
                        continue;
                    } else {
                        $source_goods_id = $goods['goods_id'];
                        unset($goods['goods_id']);
                        $goods['store_id'] = $store_id;
                        $goods['sort_id'] = $target_sort_id;
                        $goods['print_id'] = 0;
                        $target_goods_id = M('Shop_goods')->add($goods);
                        $pro_map = $spec_map = $spec_value_map = array();
                        if ($goods['is_properties']) {
                            $properties = M('Shop_goods_properties')->field(true)->where(array('goods_id' => $source_goods_id))->select();
                            foreach ($properties as $pro_data) {
                                $source_pro_id = $pro_data['id'];
                                unset($pro_data['id']);
                                $pro_data['goods_id'] = $target_goods_id;
                                $pro_map[$source_pro_id] = M('Shop_goods_properties')->add($pro_data);
                            }
                        }
                        if ($goods['spec_value']) {
                            $spec_list = M('Shop_goods_spec')->field(true)->where(array('goods_id' => $source_goods_id, 'store_id' => $source_store_id))->select();
                            foreach ($spec_list as $spec) {
                                $source_spec_id = $spec['id'];
                                unset($spec['id']);

                                $spec['store_id'] = $store_id;
                                $spec['goods_id'] = $target_goods_id;

                                if ($new_spec_id = M('Shop_goods_spec')->add($spec)) {

                                    $spec_value_list = M('Shop_goods_spec_value')->field(true)->where(array('sid' => $source_spec_id))->select();
                                    foreach ($spec_value_list as $spec_value) {
                                        $source_spec_value_id = $spec_value['id'];
                                        unset($spec_value['id']);
                                        $spec_value['sid'] = $new_spec_id;
                                        $spec_value_map[$source_spec_value_id] = M('Shop_goods_spec_value')->add($spec_value);
                                    }
                                }
                            }

                            //规格值ID:规格值ID:...:规格值ID|old_price:price:seckill_price:stock_num:cost_price|属性ID=可选值个数:属性ID=可选值个数:...:属性ID=可选值个数|商品编号#
                            $spec_array = explode('#', $goods['spec_value']);
                            $target_spec_value_array = array();
                            foreach ($spec_array as $str) {
                                $row_array = explode('|', $str);
                                $spec_val_ids = explode(':', $row_array[0]);
                                $new_ids = array();
                                foreach ($spec_val_ids as $tid) {
                                    $new_ids[] = $spec_value_map[$tid];
                                }
                                $row_array[0] = implode(':', $new_ids);
                                if (count($row_array) > 2 && $row_array[2] && strstr($row_array[2], '=')) {
                                    $pro_str_ids = explode(':', $row_array[2]);
                                    $new_pro_ids = array();
                                    foreach ($pro_str_ids as $pstr) {
                                        $v_k_a = explode('=', $pstr);
                                        $new_pro_ids[] = $pro_map[$v_k_a[0]] . '=' . $v_k_a[1];
                                    }
                                    $row_array[2] = implode(':', $new_pro_ids);
                                }
                                $target_spec_value_array[] = implode('|', $row_array);
                            }
                            M('Shop_goods')->where(array('goods_id' => $target_goods_id))->save(array('spec_value' => implode('#', $target_spec_value_array)));
                        }
                    }
                }
            }
            $this->success('克隆完成');
        }
    }

    public function store()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        if ($store = M('Merchant_store')->field(true)->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id, 'have_shop' => 1))->find()) {
            if ($shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {

            } else {
                $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
            }
        } else {
            $this->error('店铺不存在，或是店铺没有开启' . $this->config['shop_alias_name'] . ',所以不能被克隆商品');
        }

        $sql = "SELECT s.store_id, s.name FROM " . C('DB_PREFIX') . "merchant_store AS s INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON sh.store_id=s.store_id WHERE s.have_shop=1 AND s.status=1 AND s.store_id<>{$store_id} AND s.mer_id={$this->merchant_session['mer_id']}";
        $res = D()->query($sql);
        $this->assign('stores', $res);
        $this->assign('store_id', $store_id);
        $this->display();
    }


    public function export()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = '订单信息';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet

        $where = array();
        $condition_where = 'WHERE o.store_id = '.$_GET['store_id'];
        $where['store_id'] =$_GET['store_id'];


        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'real_orderid') {
                $where['real_orderid'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND o.real_orderid = "'. htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
                unset($where['orderid']);
                $where['order_id'] = $tmp_result['order_id'];
                $condition_where .= ' AND o.order_id = '. $tmp_result['order_id'];
            } elseif ($_GET['searchtype'] == 'name') {
                $where['username'] = htmlspecialchars($_GET['keyword']);
                $condition_where .=  ' AND o.username = "'.  htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'phone') {
                $where['userphone'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND o.userphone = "'.  htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'third_id') {
                $where['third_id'] =$_GET['keyword'];
            }

        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';

        if($status == 100){
            $where['paid'] = 0;
            $condition_where .= ' AND o.paid=0';
        }else if ($status != -1) {
            $where['status'] = $status;
            $condition_where .= ' AND o.status='.$status;
        }else if($status == -1){
            $where['status'] = array(array('gt', 1), array('lt', 4));
            $condition_where .= ' AND (o.status=2 or o.status=3)';
        }

        if($pay_type&&$pay_type!='balance'){
            $where['pay_type'] = $pay_type;
            $condition_where .= ' AND o.pay_type="'.$pay_type.'"';
        }else if($pay_type=='balance'){
            $where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
            $condition_where .= ' AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )';
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $condition_where.=" AND o.is_del=0";
        $where['is_del'] = 0;
        $count = D('Shop_order')->where($where)->count();

        $length = ceil($count / 1000);
        for ($i = 0; $i < $length; $i++) {
            $i && $objExcel->createSheet();
            $objExcel->setActiveSheetIndex($i);
            $objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
            $objActSheet = $objExcel->getActiveSheet();
            $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
//            $objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
//            $objExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
//            $objExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
//            $objExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
//            $objExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
//            $objExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
//            $objExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);

            $objActSheet->setCellValue('A1', 'Order Number|订单编号');
            $objActSheet->setCellValue('B1', 'Item|商品名称');
            $objActSheet->setCellValue('C1', 'Quantity|数量');
            $objActSheet->setCellValue('D1', 'Price|单价');
            $objActSheet->setCellValue('E1', 'Restaurant Name|店铺名称');
            $objActSheet->setCellValue('F1', 'Customer Name|客户姓名');
            $objActSheet->setCellValue('G1', 'Total Price Before Tax|商品总价（税前）');//无
            $objActSheet->setCellValue('H1', 'Total Tax|商品税费');
            $objActSheet->setCellValue('I1', 'Delivery Fee|配送费');
            $objActSheet->setCellValue('J1', 'Delivery Tax|配送费税');
            $objActSheet->setCellValue('K1', 'Packing Fee|包装费');
            $objActSheet->setCellValue('L1', 'Packing Tax|包装税费');
            $objActSheet->setCellValue('M1', 'Total Tax|总税费');
            $objActSheet->setCellValue('N1', 'Bottle Deposit');
            $objActSheet->setCellValue('O1', 'Total Price|总价');
            $objActSheet->setCellValue('P1', 'Cash|现金');
            $objActSheet->setCellValue('Q1', 'Status|订单状态');
            $objActSheet->setCellValue('R1', 'Time|时间');

            //$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
            $sql = "SELECT  o.*, m.name AS merchant_name,g.name as good_name,g.tax_num as good_tax,g.deposit_price,s.tax_num as store_tax,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id`  LEFT JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `g`.`goods_id`=`d`.`goods_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";

            $result_list = D()->query($sql);
            //计算订单税费及押金
            $all_tax = 0;
            $all_deposit = 0;
            $all_record = array();
            $record_id = '';
            $freight_tax = 0;
            $packing_tax = 0;
            $total_tax = 0;
            $curr_cash = 0;
            //subtotal
            $total_goods_price = 0;
            $total_goods_tax = 0;
            $total_freight_price = 0;
            $total_freight_tax = 0;
            $total_packing_price = 0;
            $total_packing_tax = 0;
            $total_all_tax = 0;
            $total_deposit = 0;
            $total_all_price = 0;
            $total_cash = 0;


            //结束循环后是否存储最后一张订单，如果最后一张是代客下单为 false;
            $is_last = true;
            foreach ($result_list as &$val){
                $curr_order = $val['real_orderid'];
                if($val['uid'] == 0){//当用户ID(uid)为0时 -- 代客下单
                    //记录上一张订单的税费和押金
                    $all_record[$curr_order]['all_tax'] = $val['discount_price'];
                    $all_record[$curr_order]['all_deposit'] = $val['packing_charge'];
                    $val['packing_charge'] = 0;
                    $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                    $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                    $all_record[$curr_order]['total_tax'] = $val['discount_price'] + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                    $all_record[$curr_order]['cash'] = $val['price'];
                    $is_last = false;

                    $total_goods_price += $val['goods_price'];
                    $total_goods_tax += $val['discount_price'];
                    $total_freight_price += $val['freight_charge'];
                    $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                    $total_packing_price += $val['packing_charge'];
                    $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                    $total_all_tax += $all_record[$curr_order]['total_tax'];
                    $total_deposit += $all_record[$curr_order]['all_deposit'];
                    $total_all_price += $val['price'];
                    $total_cash += $val['price'];
                }else{
                    if($curr_order != $record_id){
                        //记录上一张订单的税费和押金
                        if($record_id != '') {
                            $all_record[$record_id]['all_tax'] = $all_tax;
                            $all_record[$record_id]['all_deposit'] = $all_deposit;
                            $all_record[$record_id]['total_tax'] = $all_tax + $all_record[$record_id]['freight_tax'] + $all_record[$record_id]['packing_tax'];

                            $total_goods_tax += $all_tax;
                            $total_deposit += $all_deposit;
                            $total_all_tax += $all_record[$record_id]['total_tax'];
                        }
                        //记录最新订单的基本数值
                        $total_goods_price += $val['goods_price'];
                        $total_freight_price += $val['freight_charge'];
                        $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                        $total_packing_price += $val['packing_charge'];
                        $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                        $total_all_price += $val['price'];

                        $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                        $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                        if($val['pay_type'] == 'offline' || $val['pay_type'] == 'cash') {
                            $all_record[$curr_order]['cash'] = $val['price'];
                            $total_cash += $val['price'];
                        }

                        //清空商品税费
                        $all_tax = 0;//($val['freight_charge'] + $val['packing_charge'])*$val['store_tax']/100;
                        //清空押金
                        $all_deposit = 0;
                    }

                    $all_tax += $val['good_price'] * $val['good_tax']/100*$val['good_num'];
                    $all_deposit += $val['deposit_price']*$val['good_num'];
                    $total_tax = $all_tax + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                    $record_id = $curr_order;
                    $is_last = true;
                }
            }
            //记录最后一张订单
            if ($is_last){
                $all_record[$record_id]['all_tax'] = $all_tax;
                $all_record[$record_id]['all_deposit'] = $all_deposit;
                $all_record[$record_id]['total_tax'] = $total_tax;

                $total_goods_tax += $all_tax;
                $total_deposit += $all_deposit;
                $total_all_tax += $total_tax;
            }

            ////
            $tmp_id = 0;
            if (!empty($result_list)) {
                $index = 1;
                foreach ($result_list as $value) {
                    if($tmp_id == $value['real_orderid']){
                        $objActSheet->setCellValueExplicit('A' . $index, '');//Order Number|订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);//Item|商品名称
                        $objActSheet->setCellValueExplicit('C' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        $objActSheet->setCellValueExplicit('D' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('E' . $index,'');//店铺名称
                        $objActSheet->setCellValueExplicit('F' . $index, '');//客户姓名
                        $objActSheet->setCellValueExplicit('G' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）//无
                        $objActSheet->setCellValueExplicit('H' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品税费
                        $objActSheet->setCellValueExplicit('I' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费//无
                        $objActSheet->setCellValueExplicit('J' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送税费
                        $objActSheet->setCellValueExplicit('K' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Fee|包装费
                        $objActSheet->setCellValueExplicit('L' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Tax|包装税费
                        $objActSheet->setCellValueExplicit('M' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Total Tax|总税费
                        $objActSheet->setCellValueExplicit('N' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                        $objActSheet->setCellValueExplicit('O' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Total Price|总价
                        $objActSheet->setCellValueExplicit('P' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//Cash|现金
                        $objActSheet->setCellValueExplicit('Q' . $index, '');//订单状态
                        $objActSheet->setCellValueExplicit('R' . $index, '');//时间
//                        $objActSheet->setCellValueExplicit('R' . $index, $value['unit']);//单位
//                        $objActSheet->setCellValueExplicit('S' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//平台优惠
//                        $objActSheet->setCellValueExplicit('T' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
//                        $objActSheet->setCellValueExplicit('U' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//在线支付金额
//                        $objActSheet->setCellValueExplicit('V' . $index, '');//客户地址
//                        $objActSheet->setCellValueExplicit('W' . $index, '');//客户电话
//                        $objActSheet->setCellValueExplicit('X' . $index, '');//送达时间
                        $index++;
                    }else{
                        $index++;
                        $objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);//订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);//商品名称
                        $objActSheet->setCellValueExplicit('C' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        $objActSheet->setCellValueExplicit('D' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('E' . $index, $value['store_name']);//店铺名称
                        $objActSheet->setCellValueExplicit('F' . $index, $value['username']);//客户姓名
                        $objActSheet->setCellValueExplicit('G' . $index, $value['goods_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）////无
                        $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['all_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//税费
                        $objActSheet->setCellValueExplicit('I' . $index, $value['freight_charge'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                        $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['freight_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                        $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $value['packing_charge'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Fee|包装费
                        $objActSheet->setCellValueExplicit('L' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['packing_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Tax|包装税费
                        $objActSheet->setCellValueExplicit('M' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['total_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Total Tax|总税费
                        $objActSheet->setCellValueExplicit('N' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['all_deposit'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                        $objActSheet->setCellValueExplicit('O' . $index, floatval($value['price']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//订单总价
                        $objActSheet->setCellValueExplicit('P' . $index, floatval(sprintf("%.2f", $all_record[$value['real_orderid']]['cash'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Cash|现金
                        $objActSheet->setCellValueExplicit('Q' . $index, D('Shop_order')->status_list[$value['status']]);//订单状态
                        $objActSheet->setCellValueExplicit('R' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');//送达时间
//                        $objActSheet->setCellValueExplicit('P' . $index, $value['merchant_name']);//商家名称
//                        $objActSheet->setCellValueExplicit('Q' . $index, $value['cost_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品进价
//                        $objActSheet->setCellValueExplicit('R' . $index, $value['unit']);//单位
//                        $objActSheet->setCellValueExplicit('S' . $index, floatval($value['balance_reduce']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//平台优惠
//                        $objActSheet->setCellValueExplicit('T' . $index, floatval($value['merchant_reduce']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
//                        $objActSheet->setCellValueExplicit('W' . $index, floatval($value['payment_money']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//在线支付金额
//                        $objActSheet->setCellValueExplicit('U' . $index, $value['address'] . ' ');//客户地址
//                        $objActSheet->setCellValueExplicit('V' . $index, $value['userphone'] . ' ');//客户电话
//                        $objActSheet->setCellValueExplicit('W' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');//送达时间
                        $index++;
                    }
                    $tmp_id = $value['real_orderid'];

                }
                //添加最后一行 subtotal
                $objActSheet->setCellValueExplicit('A' . $index, 'Subtotal');//订单编号
                $objActSheet->setCellValueExplicit('B' . $index, '');//商品名称
                $objActSheet->setCellValueExplicit('C' . $index, '');//数量
                $objActSheet->setCellValueExplicit('D' . $index, '');//单价
                $objActSheet->setCellValueExplicit('E' . $index, '');//店铺名称
                $objActSheet->setCellValueExplicit('F' . $index, '');//客户姓名
                $objActSheet->setCellValueExplicit('G' . $index, floatval(sprintf("%.2f", $total_goods_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）////无
                $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", $total_goods_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//税费
                $objActSheet->setCellValueExplicit('I' . $index, floatval(sprintf("%.2f", $total_freight_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $total_freight_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $total_packing_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Fee|包装费
                $objActSheet->setCellValueExplicit('L' . $index, floatval(sprintf("%.2f", $total_packing_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Packing Tax|包装税费
                $objActSheet->setCellValueExplicit('M' . $index, floatval(sprintf("%.2f", $total_all_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Total Tax|总税费
                $objActSheet->setCellValueExplicit('N' . $index, floatval(sprintf("%.2f", $total_deposit)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                $objActSheet->setCellValueExplicit('O' . $index, floatval(sprintf("%.2f", $total_all_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//订单总价
                $objActSheet->setCellValueExplicit('P' . $index, floatval(sprintf("%.2f", $total_cash)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Cash|现金
                $objActSheet->setCellValueExplicit('Q' . $index, '');//订单状态
                $objActSheet->setCellValueExplicit('R' . $index, '');//送达时间
            }
            sleep(2);
        }
        //输出
        $objWriter = new PHPExcel_Writer_Excel5($objExcel);
        ob_end_clean();
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function ajax_upload_shoppic()
    {
        if ($_FILES['file']['error'] != 4) {
            $param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            // 			$param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = 640;
            $param['thumbMaxHeight'] = 420;
            $param['thumbRemoveOrigin'] = false;
            $image = D('Image')->handle($this->merchant_session['mer_id'], 'background', 1, $param);
            if ($image['error']) {
                exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
            } else {
                $title = $image['title']['file'];
                $image_tmp = explode(',', $title);
                $url = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            }
        } else {
            exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
        }
    }

    public function ajax_del_shoppic()
    {
        if (!empty($_POST['path'])) {
            $image_tmp = explode(',', $_POST['path']);
            unlink('./upload/background/' . $image_tmp[0] . '/' . $image_tmp['1']);
            unlink('./upload/background/' . $image_tmp[0] . '/m_' . $image_tmp['1']);
            unlink('./upload/background/' . $image_tmp[0] . '/s_' . $image_tmp['1']);
            return true;
        } else {
            return false;
        }
    }

    public function change_mall()
    {
        $now_store = $this->check_store(intval($_POST['id']));
        $store_theme = $_POST['type'] == 'open' ? '1' : '0';
        if (D('Merchant_store_shop')->where(array('store_id' => $now_store['store_id'], 'mer_id' => $this->merchant_session['mer_id']))->save(array('store_theme' => $store_theme))) {
            exit('1');
        } else {
            exit;
        }
    }


    public function sort_order()
    {
        $sortId = isset($_GET['sort_id']) ? intval($_GET['sort_id']) : 0;
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $now_store = $this->check_store($store_id);

        $stime = isset($_GET['stime']) && $_GET['stime'] ? htmlspecialchars($_GET['stime']) : '';
        $etime = isset($_GET['etime']) && $_GET['etime'] ? htmlspecialchars($_GET['etime']) : '';
        $shopGoodsSortDB = D('Shop_goods_sort');
        $sortList = $shopGoodsSortDB->field(true)->where(array('store_id' => $store_id, 'fid' => 0))->select();

        if ($sort = D('Shop_goods_sort')->field(true)->where(array('sort_id' => $sortId, 'store_id' => $store_id))->find()) {
            if ($sort['fid']) {
                $this->error('暂时不支持子分类的查询');
            } else {
                $ids = $shopGoodsSortDB->getAllSonIds($sortId, $store_id);
                if ($ids) {
                    $where = array('sort_id' => array('in', $ids));
                    if ($stime && $etime) {
                        $where['create_time'] = array(array('gt', strtotime($stime)), array('lt', strtotime($etime)));
                    }
                    $orders = D('Shop_order_detail')->field(true)->where($where)->select();
                }
                $this->assign('order_list', $orders);
                $this->assign('sortId', $sortId);
                $this->assign('sort_list', $sortList);
                $this->display();
            }
        } else {
            $this->error('分类信息有误');
        }
    }

    public function goods_tax(){
        $tax_num = $_POST['tax_num'];
        $data['tax_num'] = $tax_num;
        $where['store_id'] = $_POST['store_id'];
        if($_POST['sort_id'] != 0) $where['sort_id'] = $_POST['sort_id'];
        D('Shop_goods')->where($where)->save($data);
        $this->success('Success');
    }

    public function export_pdf(){
        $store = D('Merchant_store')->where(array('store_id'=>$_GET['store_id']))->find();

        $where = array();
        $condition_where = 'WHERE o.store_id = '.$_GET['store_id'];
        $where['store_id'] =$_GET['store_id'];

        if(!empty($_GET['keyword'])){
            if ($_GET['searchtype'] == 'real_orderid') {
                $where['real_orderid'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND o.real_orderid = "'. htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'orderid') {
                $where['orderid'] = htmlspecialchars($_GET['keyword']);
                $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
                unset($where['orderid']);
                $where['order_id'] = $tmp_result['order_id'];
                $condition_where .= ' AND o.order_id = '. $tmp_result['order_id'];
            } elseif ($_GET['searchtype'] == 'name') {
                $where['username'] = htmlspecialchars($_GET['keyword']);
                $condition_where .=  ' AND o.username = "'.  htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'phone') {
                $where['userphone'] = htmlspecialchars($_GET['keyword']);
                $condition_where .= ' AND o.userphone = "'.  htmlspecialchars($_GET['keyword']).'"';
            } elseif ($_GET['searchtype'] == 'third_id') {
                $where['third_id'] =$_GET['keyword'];
            }

        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';

        if($status == 100){
            $where['paid'] = 0;
            $condition_where .= ' AND o.paid=0';
        }else if ($status != -1) {
            $where['status'] = $status;
            $condition_where .= ' AND o.status='.$status;
        }else if($status == -1){
            $where['status'] = array(array('gt', 1), array('lt', 4));
            $condition_where .= ' AND (o.status=2 or o.status=3)';
        }

        if($pay_type&&$pay_type!='balance'){
            $where['pay_type'] = $pay_type;
            $condition_where .= ' AND o.pay_type="'.$pay_type.'"';
        }else if($pay_type=='balance'){
            $where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
            $condition_where .= ' AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )';
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        $condition_where.=" AND o.is_del=0";
        $where['is_del'] = 0;

        $sql = "SELECT  o.*, m.name AS merchant_name,g.name as good_name,g.tax_num as good_tax,g.deposit_price,s.tax_num as store_tax,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id`  LEFT JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `g`.`goods_id`=`d`.`goods_id` ".$condition_where." ORDER BY o.order_id DESC";

        $result_list = D()->query($sql);

        //计算订单税费及押金
        $all_tax = 0;
        $all_deposit = 0;
        $all_record = array();
        $record_id = '';
        $freight_tax = 0;
        $packing_tax = 0;
        $total_tax = 0;
        $curr_cash = 0;
        //subtotal
        $total_goods_price = 0;
        $total_goods_tax = 0;
        $total_freight_price = 0;
        $total_freight_tax = 0;
        $total_packing_price = 0;
        $total_packing_tax = 0;
        $total_all_tax = 0;
        $total_deposit = 0;
        $total_all_price = 0;
        $total_cash = 0;


        //结束循环后是否存储最后一张订单，如果最后一张是代客下单为 false;
        $is_last = true;
        foreach ($result_list as &$val){
            $curr_order = $val['real_orderid'];
            if($val['uid'] == 0){//当用户ID(uid)为0时 -- 代客下单
                //记录上一张订单的税费和押金
                $all_record[$curr_order]['all_tax'] = $val['discount_price'];
                $all_record[$curr_order]['all_deposit'] = $val['packing_charge'];
                $val['packing_charge'] = 0;
                $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                $all_record[$curr_order]['total_tax'] = $val['discount_price'] + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                $all_record[$curr_order]['cash'] = $val['price'];
                $is_last = false;

                $total_goods_price += $val['goods_price'];
                $total_goods_tax += $val['discount_price'];
                $total_freight_price += $val['freight_charge'];
                $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                $total_packing_price += $val['packing_charge'];
                $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                $total_all_tax += $all_record[$curr_order]['total_tax'];
                $total_deposit += $all_record[$curr_order]['all_deposit'];
                $total_all_price += $val['price'];
                $total_cash += $val['price'];
            }else{
                if($curr_order != $record_id){
                    //记录上一张订单的税费和押金
                    if($record_id != '') {
                        $all_record[$record_id]['all_tax'] = $all_tax;
                        $all_record[$record_id]['all_deposit'] = $all_deposit;
                        $all_record[$record_id]['total_tax'] = $all_tax + $all_record[$record_id]['freight_tax'] + $all_record[$record_id]['packing_tax'];

                        $total_goods_tax += $all_tax;
                        $total_deposit += $all_deposit;
                        $total_all_tax += $all_record[$record_id]['total_tax'];
                    }
                    //记录最新订单的基本数值
                    $total_goods_price += $val['goods_price'];
                    $total_freight_price += $val['freight_charge'];
                    $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                    $total_packing_price += $val['packing_charge'];
                    $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                    $total_all_price += $val['price'];

                    $all_record[$curr_order]['freight_tax'] = $val['freight_charge']*$val['store_tax']/100;
                    $all_record[$curr_order]['packing_tax'] = $val['packing_charge']*$val['store_tax']/100;
                    if($val['pay_type'] == 'offline' || $val['pay_type'] == 'cash') {
                        $all_record[$curr_order]['cash'] = $val['price'];
                        $total_cash += $val['price'];
                    }

                    //清空商品税费
                    $all_tax = 0;//($val['freight_charge'] + $val['packing_charge'])*$val['store_tax']/100;
                    //清空押金
                    $all_deposit = 0;
                }

                $all_tax += $val['good_price'] * $val['good_tax']/100*$val['good_num'];
                $all_deposit += $val['deposit_price']*$val['good_num'];
                $total_tax = $all_tax + ($val['freight_charge']+$val['packing_charge'])*$val['store_tax']/100;

                $record_id = $curr_order;
                $is_last = true;
            }
        }
        //记录最后一张订单
        if ($is_last){
            $all_record[$record_id]['all_tax'] = $all_tax;
            $all_record[$record_id]['all_deposit'] = $all_deposit;
            $all_record[$record_id]['total_tax'] = $total_tax;

            $total_goods_tax += $all_tax;
            $total_deposit += $all_deposit;
            $total_all_tax += $total_tax;
        }

        $begin_time = date('m/d/Y',strtotime($_GET['begin_time'].' 00:00:00'));
        $end_time = date('m/d/Y',strtotime($_GET['end_time'].' 00:00:00'));

        import('@.ORG.mpdf.mpdf');
        $mpdf = new mPDF();
        $html = $this->get_html($store,$begin_time,$end_time,$total_goods_price,$total_goods_tax,$total_packing_price,$total_deposit);

        $mpdf->WriteHTML($html);
        $fileName = $store['name'].'('.$begin_time.' - '.$end_time.').pdf';
        $mpdf->Output($fileName,'I');
    }

    public function get_html($store,$begin_time,$end_time,$good_price,$good_tax,$packing,$deposit){
        $good_pro = $good_price * $store['proportion'] / 100;
        $tax_pro = $good_tax * $store['proportion'] / 100;

        $all_price = $good_price + $good_tax + $packing + $deposit - $good_pro - $tax_pro;

        $html = '<table style="font-family:Roboto;border-collapse: collapse; width: 900px; position: relative;">
                    <tbody>
                        <tr>
                            <td width="120">
                                <img src="./static/tutti_branding.png" width="100" height="100" />
                            </td>
                            <td>
                                <p style="color: #666;">TUTTI
                                <p style="font-size: 12px;color:#999999;line-height: 20px;">801-747 Fort Street</p>
                                <p style="font-size: 12px;color:#999999;line-height: 20px;">Victoria, BC V8W 3E9</p>
                                <p style="font-size: 12px;color:#999999;line-height: 20px;">1-888-399-6668</p>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 20px"></td>
                        </tr>
                        <tr>
                            <td style="font-size: 24px;font-weight: bold" colspan="2">
                                Semi-monthly Statement
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="color:#777;font-size: 12px;font-weight: bold">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                '.$begin_time.' - '.$end_time.'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 20px"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="color:#333;font-size: 12px;font-weight: bold">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Statement for
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="color:#333;font-size: 12px;">
                                &nbsp;&nbsp;
                                '.$store['name'].'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="color:#333;font-size: 12px;">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                '.$store['adress'].'
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 20px;border-bottom: 1px solid #999"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 20px"></td>
                        </tr>
                        <tr>
                            <td colspan="3" style="color:#333;font-size: 12px;font-weight: bold;height: 25px">
                                &nbsp;&nbsp;&nbsp;&nbsp;
                                Description
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" align="right">
                                <table style="border-bottom: 1px solid #999;">
                                    <tr>
                                        <td style="color:#333;font-size: 11px;width: 580px;height: 20px;" align="left">
                                            &nbsp;Earnings before tax
                                        </td>
                                        <td align="right" style="color:#666;font-size: 11px;width: 70px;">
                                            '.floatval(sprintf("%.2f", $good_price)).'
                                            &nbsp;&nbsp;
                                        </td>
                                    </tr>
                                </table>
                                <table style="border-bottom: 1px solid #999;">
                                    <tr>
                                        <td style="color:#333;font-size: 11px;width: 580px;height: 20px;" align="left">
                                            &nbsp;Tax received from sales
                                        </td>
                                        <td align="right" style="color:#666;font-size: 11px;width: 70px;">
                                            '.floatval(sprintf("%.2f", $good_tax)).'
                                            &nbsp;&nbsp;
                                        </td>
                                    </tr>
                                </table>
                                <table style="border-bottom: 1px solid #999;">
                                    <tr>
                                        <td style="color:#333;font-size: 11px;width: 580px;height: 20px;" align="left">
                                            &nbsp;Packing Fee
                                        </td>
                                        <td align="right" style="color:#666;font-size: 11px;width: 70px;">
                                            '.floatval(sprintf("%.2f", $packing)).'
                                            &nbsp;&nbsp;
                                        </td>
                                    </tr>
                                </table>
                                <table style="border-bottom: 1px solid #999;">
                                    <tr>
                                        <td style="color:#333;font-size: 11px;width: 580px;height: 20px;" align="left">
                                            &nbsp;Bottle Deposit
                                        </td>
                                        <td align="right" style="color:#666;font-size: 11px;width: 70px;">
                                            '.floatval(sprintf("%.2f", $deposit)).'
                                            &nbsp;&nbsp;
                                        </td>
                                    </tr>
                                </table>
                                <table style="border-bottom: 1px solid #999;">
                                    <tr>
                                        <td style="color:#333;font-size: 11px;width: 580px;height: 20px;" align="left">
                                            &nbsp;'.$store['proportion'].'% (service charge on sales)
                                        </td>
                                        <td align="right" style="color:#666;font-size: 11px;width: 70px;">
                                            -'.floatval(sprintf("%.2f", $good_pro)).'
                                            &nbsp;&nbsp;
                                        </td>
                                    </tr>
                                </table>
                                <table style="border-bottom: 1px solid #999;">
                                    <tr>
                                        <td style="color:#333;font-size: 11px;width: 580px;height: 20px;" align="left">
                                            &nbsp;GST (GST #721938728RT0001) (service charge on tax)
                                        </td>
                                        <td align="right" style="color:#666;font-size: 11px;width: 70px;">
                                            -'.floatval(sprintf("%.2f", $tax_pro)).'
                                            &nbsp;&nbsp;
                                        </td>
                                    </tr>
                                </table>
                                <table>
                                    <tr>
                                        <td style="color:#333;font-size: 11px;width: 580px;height: 30px;" align="left">
                                            &nbsp;Net amount to be sent to vendor
                                        </td>
                                        <td align="right" style="color:#333;font-size: 12px;font-weight: bold;width: 70px;">
                                            '.floatval(sprintf("%.2f", $all_price)).'
                                            &nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="height: 100px"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="font-size: 10px;font-family: Arial" align="center">
                                2019 © Tutti Technologies * Please allow three to five business days for the funds to arrive.
                            </td>
                        </tr>
                    </tbody>
                </table>';

        return $html;
    }

}