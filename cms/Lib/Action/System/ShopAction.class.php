<?php
/*
 * 订餐管理
 *
 * @  BuildTime  2014/11/18 11:21
 */

class ShopAction extends BaseAction
{
    public function index()
    {
        $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
        $city_name = "通用";
        if($_GET['city_id']){
            $this->assign('city_id',$_GET['city_id']);
            if($_GET['city_id'] != 0){
                $where_cate['city_id'] = $_GET['city_id'];
                $where_list['city_id'] = $_GET['city_id'];
            }
        }else{
            $this->assign('city_id',0);
        }
        $this->assign('city_name',$city_name);

        $where_cate['cat_id'] = $parentid;
        $database_shop_category = D('Shop_category');
        $category = $database_shop_category->field(true)->where($where_cate)->find();

        $where_list['cat_fid'] = $parentid;
        $category_list = $database_shop_category->field(true)->where($where_list)->order('`cat_sort` DESC,`cat_id` ASC')->select();
        foreach ($category_list as &$v){
            if($v['city_id'] == 0)
                $v['city_name'] = "通用";
            else {
                $c = D('Area')->where(array('area_type' => 2, 'is_open' => 1, 'area_id' => $v['city_id']))->find();
                $v['city_name'] = $c['area_name'];
            }
            if($parentid == 0){
                $allList = D('Shop_category_relation')->field('store_id')->where(array('cat_fid' => $v['cat_id']))->group('store_id')->select();
            }else{
                $allList = D('Shop_category_relation')->field('store_id')->where(array('cat_id' => $v['cat_id']))->group('store_id')->select();
            }

            $v['store_num'] = count($allList);
        }
        $this->assign('category', $category);
        $this->assign('category_list', $category_list);
        $this->assign('parentid', $parentid);

        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);

        $this->display();
    }

    public function cat_add()
    {
        $this->assign('bg_color','#F3F3F3');
        $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
        $this->assign('parentid', $parentid);
        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);

        if($parentid != 0){
            $database_shop_category = D('Shop_category');
            $condition_now_shop_category['cat_id'] = $parentid;
            $now_category = $database_shop_category->field(true)->where($condition_now_shop_category)->find();
            if($now_category['city_id'] != 0) {
                $c = D('Area')->where(array('area_type' => 2, 'is_open' => 1, 'area_id' => $now_category['city_id']))->find();
                $now_category['city_name'] = $c['area_name'];
            }else{
                $now_category['city_name'] = "通用";
            }
            $this->assign('category',$now_category);
        }
        $this->display();
    }
    public function cat_modify()
    {
        if(IS_POST){
            $database_shop_category = D('Shop_category');
            if($database_shop_category->data($_POST)->add()){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
    public function cat_edit()
    {
        $this->assign('bg_color','#F3F3F3');

        $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
        $database_shop_category = D('Shop_category');
        $condition_now_shop_category['cat_id'] = intval($_GET['cat_id']);
        $now_category = $database_shop_category->field(true)->where($condition_now_shop_category)->find();
        $cate_image_class = new category_image();
        $now_category['img_url'] = $cate_image_class->get_image_by_path($now_category['cat_img']);
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('parentid', $parentid);
        $this->assign('now_category', $now_category);

        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);

        if($parentid != 0){
            $database_shop_category = D('Shop_category');
            $condition_now_shop_category['cat_id'] = $parentid;
            $now_category = $database_shop_category->field(true)->where($condition_now_shop_category)->find();
            if($now_category['city_id'] != 0) {
                $c = D('Area')->where(array('area_type' => 2, 'is_open' => 1, 'area_id' => $now_category['city_id']))->find();
                $now_category['city_name'] = $c['area_name'];
            }else{
                $now_category['city_name'] = "通用";
            }

            $this->assign('category',$now_category);
        }

        $this->display();
    }

    public function cat_service(){
        if($_POST){
            $cat_id = $_POST['cat_id'];
            $cat_fid = $_POST['cat_fid'];

            if($cat_id && $cat_id != 0) {
                if ($cat_fid == 0) {
                    $where['cat_fid'] = $cat_id;
                }else{
                    $where['cat_id'] = $cat_id;
                }
                $list = D('Shop_category_relation')->field('store_id')->where($where)->select();
                $all_list = array();
                foreach($list as $v){
                    $all_list[] = intval($v['store_id']);
                }
                //var_dump($all_list);die();
                D('Merchant_store')->where(array('store_id'=>array('in',$all_list)))->save(array('service_fee'=>$_POST['service_fee']));
                $this->success('编辑成功！');
            }else{
                $this->error('分类不存在！');
            }
        }else {
            $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
            $database_shop_category = D('Shop_category');
            $condition_now_shop_category['cat_id'] = intval($_GET['cat_id']);
            $now_category = $database_shop_category->field(true)->where($condition_now_shop_category)->find();
            if (empty($now_category)) {
                $this->frame_error_tips('没有找到该分类信息！');
            }
            $this->assign('parentid', $parentid);
            $this->assign('now_category', $now_category);
            $this->display();
        }
    }

    public function cat_amend()
    {
        if (IS_POST) {
            $database_shop_category = D('Shop_category');
            $where = array('cat_id' => $_POST['cat_id']);
            unset($_POST['cat_id']);
            if ($database_shop_category->where($where)->save($_POST)) {
                $this->success('编辑成功！');
            } else {
                $this->error('编辑失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function cat_del()
    {
        if (IS_POST) {
            $database_shop_category = D('Shop_category');
            $condition_now_shop_category['cat_id'] = intval($_POST['cat_id']);
            if ($obj = $database_shop_category->field(true)->where($condition_now_shop_category)->find()) {
                $t_list = $database_shop_category->field(true)->where(array('cat_fid' => $obj['cat_id']))->select();
                if ($t_list) {
                    $this->error('该分类下有子分类，先清空子分类，再删除该分类');
                }
            }
            if ($database_shop_category->where($condition_now_shop_category)->delete()) {
                $database_shop_category_relation = D('Shop_category_relation');
                $condition_shop_category_relation['cat_id'] = intval($_POST['cat_id']);
                $database_shop_category_relation->where($condition_shop_category_relation)->delete();
                $this->success('删除成功！');
            } else {
                $this->error('删除失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function ajax_upload_pic() {
        if ($_FILES['file']['error'] != 4) {
            $image = D('Image')->handle($_GET['cat_fid'], 'category', 1);
            if ($image['error']) {
                exit(json_encode(array('error' => 1,'message' =>$image['message'])));
            } else {
                $title = $image['title']['file'];
                $cate_image_class = new category_image();
                $url = $cate_image_class->get_image_by_path($title);
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title)));
            }
        } else {
            exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
        }
    }

    public function cat_store(){
        if($_POST){
            $cat_id = $_POST['cat_id'];
            $cat_fid = $_POST['cat_fid'];

            $where['cat_id'] = $cat_id;
            $where['cat_fid'] = $cat_fid;
            foreach ($_POST as $k=>$v){
                if(strpos($k,'cat_sort') !== false){
                    $str_arr = explode('_',$k);
                    $where['store_id'] = $str_arr[2];
                    $data['store_sort'] = $v;
                    D('Shop_category_relation')->where($where)->save($data);
                }
            }
            $this->success('编辑成功！');
        }else {
            $cat_id = $_GET['cat_id'];
            $cat_fid = $_GET['parentid'];
            $this->assign('cat_id', $cat_id);
            $this->assign('cat_fid', $cat_fid);

            if ($cat_id && $cat_id != 0) {
                if ($cat_fid == 0) {
                    $where['cat_fid'] = $cat_id;
                } else {
                    $where['cat_id'] = $cat_id;
                }

                $list = D('Shop_category_relation')->where($where)->order('store_sort desc')->select();
                $store_id_list = array();
                $store_sort = array();
                foreach ($list as $v) {
                    $store_id_list[] = $v['store_id'];
                    $store_sort[$v['store_id']] = $v['store_sort'];
                }
                $where_store['store_id'] = array('in', $store_id_list);


                $store_list = D('Merchant_store')->field('store_id,`name`,`status`')->where($where_store)->order('status asc')->select();
                foreach ($store_list as &$store) {
                    $store['cat_sort'] = $store_sort[$store['store_id']];
                }
                $cmf_arr = array_column($store_list, 'cat_sort');
                array_multisort($cmf_arr, SORT_DESC, $store_list);
                $this->assign('store_list', $store_list);

                $this->display();
            }
        }
    }

    // 预约自定义表单所有字段展示
    public function cue_field(){
        $condition_now_appoint_category['cat_id'] = intval($_GET['cat_id']);
        $now_category = M('Shop_category')->field(true)->where($condition_now_appoint_category)->find();

        if(empty($now_category)){
            $this->frame_error_tips('没有找到该分类信息！');
        }
        if(!empty($now_category['cue_field'])){
            $now_category['cue_field'] = unserialize($now_category['cue_field']);
            foreach ($now_category['cue_field'] as $val){
                $sort[] = $val['sort'];
            }
            array_multisort($sort, SORT_DESC, $now_category['cue_field']);
        }
        $this->assign('now_category',$now_category);
        $this->display();
    }

    // 预约自定义表单添加字段
    public function cue_field_add(){
        $this->assign('bg_color','#F3F3F3');

        $this->display();
    }

    // 预约自定义表单添加字段 操作
    public function cue_field_modify(){
        if(IS_POST){
            $database_appoint_category = M('Shop_category');
            $condition_now_appoint_category['cat_id'] = intval($_POST['cat_id']);
            $now_category = $database_appoint_category->field(true)->where($condition_now_appoint_category)->find();

            if(!empty($now_category['cue_field'])){
                $cue_field = unserialize($now_category['cue_field']);
                foreach($cue_field as $key=>$value){
                    if($value['name'] == $_POST['name']){
                        $this->error('该填写项已经添加，请勿重复添加！');
                    }
                }
            }else{
                $cue_field = array();
            }

            $post_data['name'] = $_POST['name'];
            $post_data['type'] = $_POST['type'];
            $post_data['sort'] = strval($_POST['sort']);
            $post_data['iswrite'] = $_POST['iswrite'];
            if(!empty($_POST['use_field'])){
                $post_data['use_field'] = explode(PHP_EOL, $_POST['use_field']);
            }

            array_push($cue_field,$post_data);
            $data_group_category['cue_field'] = serialize($cue_field);
            $data_group_category['cat_id'] = $now_category['cat_id'];
            if($database_appoint_category->data($data_group_category)->save()){
                $this->success('添加成功！');
            }else{
                $this->error('添加失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }

    public function cue_field_del(){
        if(IS_POST){
            $database_group_category = M('Shop_category');
            $condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
            $now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();

            if(!empty($now_category['cue_field'])){
                $cue_field = unserialize($now_category['cue_field']);
                $new_cue_field = array();

                foreach($cue_field as $key=>$value){
                    if($value['name'] != $_POST['name']){
                        array_push($new_cue_field,$value);
                    }
                }
            }else{
                $this->error('此填写项不存在！');
            }
            $data_group_category['cue_field'] = serialize($new_cue_field);
            $data_group_category['cat_id'] = $now_category['cat_id'];
            if($database_group_category->data($data_group_category)->save()){
                $this->success('删除成功！');
            }else{
                $this->error('删除失败！请重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }
    }
    public function discount()
    {
        $discounts = D('Shop_discount')->field(true)->where(array('source' => 0))->select();
        $this->assign('discount_list', $discounts);
        $this->display();
    }

    public function discount_add()
    {
        $this->assign('bg_color','#F3F3F3');
        $this->display();
    }
    public function discount_edit()
    {
        $this->assign('bg_color','#F3F3F3');
        $database_shop_discount = D('Shop_discount');
        $condition_now_shop_discount['id'] = intval($_GET['id']);
        $now_discount = $database_shop_discount->field(true)->where($condition_now_shop_discount)->find();
        if (empty($now_discount)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('now_discount', $now_discount);
        $this->display();
    }

    public function discount_modify()
    {
        if (IS_POST) {
            $database_shop_category = D('Shop_discount');
            $_POST['source'] = 0;
            if ($database_shop_category->data($_POST)->add()) {
                $this->success('添加成功！');
            } else {
                $this->error('添加失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function discount_amend()
    {
        if (IS_POST) {
            $database_shop_discount = D('Shop_discount');
            $where = array('id' => $_POST['id']);
            unset($_POST['id']);
            if ($database_shop_discount->where($where)->save($_POST)) {
                $this->success('编辑成功！');
            } else {
                $this->error('编辑失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function order()
    {
        $where_store = null;
        if(!empty($_GET['keyword']) && $_GET['searchtype'] == 's_name'){
            $where_store['name'] = array('like', '%'.$_GET['keyword'].'%');
        }

        if ($this->system_session['area_id']) {
            $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
            $where_store[$area_index] = $this->system_session['area_id'];
        }

        if($_GET['city_id']){
            $this->assign('city_id',$_GET['city_id']);
            if($_GET['city_id'] != 0){
                $where_store['city_id'] = $_GET['city_id'];
            }
        }else{
            $this->assign('city_id',0);
        }
        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);

        $store_ids = array();
        $where = array();
        if ($where_store) {
            $stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
            foreach ($stores as $row) {
                $store_ids[] = $row['store_id'];
            }
            if ($store_ids) {
                $where['store_id'] = array('in', $store_ids);
            } else {
                import('@.ORG.system_page');
                $p = new Page(0, 20);
                $this->assign('order_list', null);
                $this->assign('pagebar', $p->show());
                $this->display();
                exit;
            }
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
            }elseif ($_GET['searchtype'] == 'third_id') {
                $where['third_id'] =$_GET['keyword'];
            }elseif ($_GET['searchtype'] == 'id'){
                $where['uid'] =$_GET['keyword'];
            }
        }
        $status = isset($_GET['status']) ? intval($_GET['status']) : -1;
        $type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
        $sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
        $pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
        if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
        if ($type != 'price' && $type != 'pay_time') $type = '';
        $order_sort = '';
        if ($type && $sort) {
            $order_sort .= $type . ' ' . $sort . ',';
            $order_sort .= 'pay_time DESC';
        } else {
            $order_sort .= 'pay_time DESC';
        }
        if($status == 100){
            $where['paid'] = 0;
        }elseif ($status == 2){
            $where['_string'] = "(`status`=2 OR `status`=3)";
        }else if ($status != -1) {
            $where['status'] = $status;
        }
        if($pay_type&&$pay_type!='balance'&&$pay_type!='offline'){
            $where['pay_type'] = $pay_type;
        }elseif($pay_type=='offline'){
            $where['_string'] = $where['_string'] == "" ? "(`pay_type`='offline' OR `pay_type`='Cash' )" : $where['_string']." and (`pay_type`='offline' OR `pay_type`='Cash' )";
        }
        else if($pay_type=='balance'){
            $where['_string'] = $where['_string'] == "" ? "`pay_type`<>'Cash' and (`balance_pay`<>0 OR `merchant_balance` <> 0 )" : $where['_string']." and `pay_type`<>'Cash' and (`balance_pay`<>0 OR `merchant_balance` <> 0 )";
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition_where['_string']=$time_condition;
        }

        $result = D("Shop_order")->get_order_list($where, $order_sort, 3);
        $list = isset($result['order_list']) ? $result['order_list'] : '';
        $store_ids = array();
        foreach ($list as $l) {
            $store_ids[] = $l['store_id'];
        }
        $temp = array();
        if ($store_ids) {
            $store_ids = implode(',', $store_ids);
            $sql = "SELECT `m`.`name` AS merchant_name, `s`.`name` AS store_name, `s`.`phone` AS store_phone, `s`.`store_id`,`s`.`tax_num` FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` WHERE `s`.`store_id` IN ($store_ids)";
            $mod = new Model();
            $res = $mod->query($sql);
            foreach ($res as $r) {
                $temp[$r['store_id']] = $r;
            }
        }
        foreach ($result['order_list'] as &$li) {
            $li['merchant_name'] = isset($temp[$li['store_id']]['merchant_name']) ? $temp[$li['store_id']]['merchant_name'] : '';
            $li['store_name'] = isset($temp[$li['store_id']]['store_name']) ? $temp[$li['store_id']]['store_name'] : '';
            $li['store_phone'] = isset($temp[$li['store_id']]['store_phone']) ? $temp[$li['store_id']]['store_phone'] : '';
            $li['duty_price'] = sprintf("%.2f", $li['total_price'] * 0.05);
            $tax_price = 0;
            $order = D('Shop_order')->get_order_detail(array('order_id' => $li['order_id']));
            foreach ($order['info'] as $k => $v){
                $g_id = $v['goods_id'];
                $goods = D('Shop_goods')->get_goods_by_id($g_id);
                $tax_price += $v['price'] * $goods['tax_num']/100 *$v['num'];
            }
            if($order['num'] == 0){
                $tax_price = $order['packing_charge'];
                $li['packing_charge'] = 0;
            }
            $li['duty_price'] = $tax_price + ($li['packing_charge'] + $li['freight_charge'])*$temp[$li['store_id']]['tax_num']/100;
            $li['duty_price'] = round($li['duty_price'],2);
            if($li['status'] > 0){
                $deliver = D('Deliver_supply')->field(true)->where(array('order_id'=>$li['order_id']))->find();
                if($deliver){
                    $li['dining_time'] = $deliver['dining_time'];
                }
            }
        }
        $this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));
        $this->assign('status_list', D('Shop_order')->getStatusList());
        $this->assign($result);

        $field = 'sum(price) AS total_price, sum(price - card_price - merchant_balance - balance_pay - payment_money - score_deducte - coupon_price - card_give_money - merchant_reduce) AS offline_price, sum(card_price + merchant_balance + balance_pay + payment_money + score_deducte + coupon_price + card_give_money) AS online_price';
        $count_where = "paid=1 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
        $result_total = D('Shop_order')->field($field)->where($count_where)->select();
        $result_total = isset($result_total[0]) ? $result_total[0] : '';
        $this->assign($result_total);
        $pay_method = D('Config')->get_pay_method('','',0);
        foreach ($pay_method as $k=>&$v){
            switch ($k){
                case 'offline':
                    $v['name'] = 'Cash';
                    break;
                case 'alipay':
                    $v['name'] = 'AliPay';
                    break;
                case 'weixin':
                    $v['name'] = 'Wechat Pay';
                    break;
                default:
                    break;
            }
        }
        $this->assign('pay_method',$pay_method);
        $this->display();
    }

    public function order_detail()
    {
        $this->assign('bg_color', '#F3F3F3');
        if(strlen($_GET['order_id'])>10){
            $res = M('Shop_order')->field('order_id')->where(array('real_orderid'=>$_GET['order_id']))->find();
            $_GET['order_id']=$res['order_id'];
        }

        $order = D('Shop_order')->get_order_detail(array('order_id' => intval($_GET['order_id'])));
        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        if (empty($order)) {
            $this->frame_error_tips('没有找到该订单的信息！');
        }else{//garfunkel 重新获取商品名称
            $tax_price = 0;
            $deposit_price = 0;
            if($order['num'] == 0){
                $order['deposit_price'] = $order['packing_charge'];
                $order['good_tax_price'] = $order['discount_price'];
                $order['packing_charge'] = 0;
                $order['tax_price'] = $order['good_tax_price'] + ($order['freight_charge'] + $order['packing_charge']) * $store['tax_num']/100;
            }else {
                foreach ($order['info'] as $k => $v) {
                    $g_id = $v['goods_id'];
                    $goods = D('Shop_goods')->get_goods_by_id($g_id);
                    $order['info'][$k]['name'] = $goods['name'];
                    $order['info'][$k]['tax_num'] = $goods['tax_num'];
                    $order['info'][$k]['deposit_price'] = $goods['deposit_price'];
                    $deposit_price += $goods['deposit_price'] * $v['num'];
                    $tax_price += $v['price'] * $goods['tax_num'] / 100 * $v['num'];

                    if($v['dish_id'] != "" && $v['dish_id'] != null){
                        $dish_desc = "";
                        $dish_list = explode("|",$v['dish_id']);
                        foreach($dish_list as $vv){
                            $one_dish = explode(",",$vv);
                            //0 dish_id 1 id 2 num 3 price

                            $dish_vale = D('Side_dish_value')->where(array('id'=>$one_dish[1]))->find();
                            $dish_vale['name'] = lang_substr($dish_vale['name'],C('DEFAULT_LANG'));

                            $add_str = $one_dish[2] > 1 ? $dish_vale['name']."*".$one_dish[2] : $dish_vale['name'];

                            $dish_desc = $dish_desc == "" ? $add_str : $dish_desc.";".$add_str;
                        }

                        $order['info'][$k]['spec'] = $order['info'][$k]['spec'] == "" ? $dish_desc : $order['info'][$k]['spec'] ." " .$dish_desc;
                    }
                }
                $order['deposit_price'] = $deposit_price;
                $order['tax_price'] = $tax_price + ($order['freight_charge'] + $order['packing_charge']) * $store['tax_num'] / 100;
            }
        }

        $this->assign('store', D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find());
        $this->assign('order', $order);
        $this->display();
    }

    //admin 修改订单
    public function edit_order(){
        $order = D('Shop_order')->get_order_detail(array('order_id' => intval($_GET['order_id'])));
        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        if (empty($order)) {
            $this->frame_error_tips('没有找到该订单的信息！');
        }else{//garfunkel 重新获取商品名称
            $tax_price = 0;
            $deposit_price = 0;
            if($order['num'] == 0){
                $order['deposit_price'] = $order['packing_charge'];
                $order['good_tax_price'] = $order['discount_price'];
                $order['packing_charge'] = 0;
                $order['tax_price'] = $order['good_tax_price'] + ($order['freight_charge'] + $order['packing_charge']) * $store['tax_num']/100;
            }else{
                foreach ($order['info'] as $k => $v){
                    $g_id = $v['goods_id'];
                    $goods = D('Shop_goods')->get_goods_by_id($g_id);
                    $order['info'][$k]['name'] = $goods['name'];
                    $order['info'][$k]['tax_num'] = $goods['tax_num'];
                    $order['info'][$k]['deposit_price'] = $goods['deposit_price'];
                    $deposit_price += $goods['deposit_price']*$v['num'];
                    $tax_price += $v['price'] * $goods['tax_num']/100 * $v['num'];

                    if($v['dish_id'] != "" && $v['dish_id'] != null){
                        $dish_desc = "";
                        $dish_list = explode("|",$v['dish_id']);
                        foreach($dish_list as $vv){
                            $one_dish = explode(",",$vv);
                            //0 dish_id 1 id 2 num 3 price

                            $dish_vale = D('Side_dish_value')->where(array('id'=>$one_dish[1]))->find();
                            $dish_vale['name'] = lang_substr($dish_vale['name'],C('DEFAULT_LANG'));

                            $add_str = $one_dish[2] > 1 ? $dish_vale['name']."*".$one_dish[2] : $dish_vale['name'];

                            $dish_desc = $dish_desc == "" ? $add_str : $dish_desc.";".$add_str;
                        }

                        $order['info'][$k]['spec'] = $order['info'][$k]['spec'] == "" ? $dish_desc : $order['info'][$k]['spec'] ." " .$dish_desc;
                    }
                }
                $order['deposit_price'] = $deposit_price;
                $order['tax_price'] = $tax_price + ($order['freight_charge'] + $order['packing_charge']) * $store['tax_num']/100;
            }
        }
        if ($order){
            $this->assign('order',$order);
            $this->assign('store',$store);
        }else{
            $this->frame_error_tips('订单不存在');
        }
        $this->display();
    }
    public function save_edit_order(){
        if (IS_POST){
            $order_id= intval($_POST['order_id']);//订单id
            $shop_order = M('Shop_order');
            $shop_order_data = $shop_order->field(true)->find($order_id);
            if (!$shop_order_data){
                $this->error('订单不存在或已经删除，请刷新后重试');
            }

            //garfunkel add 记录原始价格
            $data['change_price'] = $shop_order_data['price'];

            $price=floatval(sprintf("%.2f", $_POST['price']));//实际要支付的金额
            $goods_price=floatval(sprintf("%.2f", $_POST['goods_price']));//商品总价
            $goods_price_taxation=floatval(sprintf("%.2f", $_POST['goods_price'] * 0.05));//商品税费
            $freight_charge=floatval(sprintf("%.2f", $_POST['freight_charge']));//运费、配送费
            $freight_charge_taxation=floatval(sprintf("%.2f", $_POST['freight_charge'] * 0.05));//运费、配送费 税费
            $total_price=floatval(sprintf("%.2f", $_POST['total_price']));//总价
            //$coupon_price = $shop_order_data['coupon_price'];//优惠券金额
            $merchant_reduce = $shop_order_data['merchant_reduce'];//商家优惠的金额
            $balance_reduce = $shop_order_data['balance_reduce'];//平台优惠的金额
            //如果修改了实际要支付的金额，则以修改的数据为准，否则重新计算实际要支付的金额
            if ($shop_order_data['price']!=$price){
                $data['price'] = sprintf("%.2f", $_POST['price']);
            }else{
                //实际支付=商品总价+商品税费+商品配送费+商品配送费税-商家优惠-平台优惠
                $data['price'] =$goods_price+$freight_charge+$goods_price_taxation+$freight_charge_taxation-$merchant_reduce-$balance_reduce;
            }
            //如果修改了总价，则以修改的数据为准，否则重新计算总价
            if ($shop_order_data['total_price']!=$total_price){
                $data['total_price'] = sprintf("%.2f", $_POST['total_price']);
            }else{
                //总价=商品总价+商品税费+商品配送费+商品配送费税
                $data['total_price'] =$goods_price+$freight_charge+$freight_charge_taxation+$goods_price_taxation;
            }
            $data['goods_price'] = $goods_price;
            $data['freight_charge'] =$freight_charge;

            //garfunkel add 记录原始价格
            $data['change_price'] = $shop_order_data['price'];
            //记录此订单被后台修改过
            $data['is_refund'] = 1;
            //计算修改后的价格差 原始价格 - 修改价格
            $cha = $shop_order_data['price'] - $data['price'];

            //是否使用线上付款
            if($shop_order_data['pay_type'] == 'moneris' && $shop_order_data['paid'] == 1){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
//                if($cha > 0){//需要退款
//                    $cha = sprintf("%.2f", $cha);
//                    $resp = $moneris_pay->refund($shop_order_data['uid'],$order_id,$cha);
//                    if(!($resp['responseCode'] != 'null' && $resp['responseCode'] < 50)){
//                        $this->error($resp['message']);
//                    }else{//更新线上支付金额
//                        $data['payment_money'] = $shop_order_data['payment_money'] - $cha;
//                    }
//                }elseif($cha < 0){//需要追加付款
//                    $cha = sprintf("%.2f", $cha*-1);
//                    $resp = $moneris_pay->addPay($shop_order_data['uid'],$order_id,$cha);
//                    if(!($resp['responseCode'] != 'null' && $resp['responseCode'] < 50)){
//                        $this->error($resp['message']);
//                    }else{//更新线上支付金额
//                        $data['payment_money'] = $shop_order_data['payment_money'] + $cha;
//                    }
//                }
                //如果有差价首先删除之前的支付 然后添加一个新的支付
                if($cha != 0){
                    $resp = $moneris_pay->refund($shop_order_data['uid'],$order_id);
                    if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                        $cha = $shop_order_data['payment_money'] - $cha;
                        $cha = $cha < 0 ? 0 : $cha;
                        $cha = sprintf("%.2f", $cha);
                        $resp = $moneris_pay->addPay($shop_order_data['uid'],$order_id,$cha);
                        if(!($resp['responseCode'] != 'null' && $resp['responseCode'] < 50)){
                            $this->error($resp['message']);
                        }else{//更新线上支付金额
                            $data['payment_money'] = $cha;
                        }
                    }else{
                        $this->error($resp['message']);
                    }
                }
            }else if($shop_order_data['pay_type'] == '' && $shop_order_data['paid'] == 1 && $shop_order_data['balance_pay'] > 0){
                $new_price = $shop_order_data['balance_pay'] - $cha;
                if($cha > 0){//需要退款
                    $cha = sprintf("%.2f", $cha);
                    //$add_result = D('User')->add_money($shop_order_data['uid'],$cha,L('_B_MY_REFUND_')  . '(' . $order_id . ') 修改价格 退还金额');
                    $add_result = D('User')->add_money($shop_order_data['uid'],$cha,'修改价格：退还余额 (' . $order_id . ')',0,0,0,'Adjustment: Credit Return (' . $order_id . ')');
                }elseif($cha < 0){//需要追加付款
                    $user = D('User')->field(true)->where(array('uid'=>$shop_order_data['uid']))->find();
                    $cha = sprintf("%.2f", $cha*-1);
                    if($user['now_money'] >= $cha){
                        //$use_result = D('User')->user_money($shop_order_data['uid'], $cha, '购买 ' . $shop_order_data['order_id'] . ' 修改价格 追加付款');
                        $use_result = D('User')->user_money($shop_order_data['uid'], $cha, '修改价格：追加消费 (' . $order_id . ')',0,0,0,'Adjustment: Credit Charge (' . $order_id . ')');
                        if ($use_result['error_code']) {
                            $this->error( $use_result['msg']);
                        }
                    }else{
                        $this->error("用户余额不足，不能修改订单价格");
                    }
                }

                $new_price = $new_price < 0 ? 0 : $new_price;
                $data['balance_pay'] = $new_price;
            }

            ////////
            if ($shop_order->where("order_id=$order_id")->data($data)->save()){
                //更新订单商品
                $good_list = D('Shop_order_detail')->field(true)->where(array('order_id'=>$order_id))->select();
                foreach ($good_list as $good){
                    $good_id = 'good_'.$good['goods_id'];
                    //如果商品数量更新
                    if($_POST[$good_id] != $good['num']){
                        $good['num'] = $_POST[$good_id];
                        D('Shop_order_detail')->where(array('order_id'=>$order_id,'goods_id'=>$good['goods_id']))->save($good);
                    }
                }
                //同时修改配送员端的价格
                $deliver_data['money'] = $data['price'];
                $deliver_data['freight_charge'] = $freight_charge;
                if($shop_order_data['pay_type'] != 'moneris' && $shop_order_data['pay_type'] != '')
                    $deliver_data['deliver_cash'] = $data['price'];
                D('Deliver_supply')->field(true)->where(array('order_id'=>$order_id))->save($deliver_data);

                $this->success('Success');
            }else{
                $this->error('修改失败！请检查内容是否有过修改（必须修改）后重试~');
            }
        }else{
            $this->error('非法提交,请重新提交~');
        }

    }
    //保存修改
    //admin删除订单，实际不删出只是改变状态
    public function del(){
        if ($_GET){
            $shop_order = M('Shop_order');
            $order_id= intval($_GET['id']);//订单id
            $data['is_del']=1;
            //garfunkel add
            $now_order = $shop_order -> where("order_id=$order_id")->find();
            if($now_order['pay_type'] == 'moneris' && $now_order['paid'] == 1){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
                //判读此订单是否修改过价格
                $record_type = $now_order['is_refund'] == 1 ? 3 : 1;
                $resp = $moneris_pay->refund($now_order['uid'],$now_order['order_id'],-1,$record_type);
//                var_dump($now_order['order_id']);die();
                if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
//                    $data_shop_order['order_id'] = $now_order['order_id'];
//                    $data_shop_order['status'] = 4;
//                    $data_shop_order['last_time'] = time();
//                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->error('删除失败！请重试~');
                }
            }else if($now_order['pay_type'] == 'weixin' || $now_order['pay_type'] == 'alipay'){
                //判断订单状态 已退款或已取消 不退款
                if($now_order['status'] != 4 && $now_order['status'] != 5) {
                    import('@.ORG.pay.IotPay');
                    $IotPay = new IotPay();
                    $result = $IotPay->refund($now_order['uid'], $now_order['order_id'], 'WEB');
                    if ($result['retCode'] == 'SUCCESS' && $result['resCode'] == 'SUCCESS') {
//                    $data_shop_order['order_id'] = $now_order['order_id'];
//                    $data_shop_order['status'] = 4;
//                    $data_shop_order['last_time'] = time();
//                    D('Shop_order')->data($data_shop_order)->save();
                    } else {
                        $this->error('删除失败！--' . $result['retMsg']);
                    }
                }
            }elseif($now_order['pay_type'] == '' && $now_order['paid'] == 1 && $now_order['balance_pay'] > 0){
                //判断订单状态 已退款或已取消 不退款
                if($now_order['status'] != 4 && $now_order['status'] != 5)
                    $add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$order_id.' 增加余额',0,0,0,"Order Cancellation (Order # ".$order_id.")");
            }
            ///////
            if ($shop_order->where("order_id=$order_id")->data($data)->save()){
                $this->success('Order Deleted!');
            }else{
                $this->error('删除失败！请重试~');
            }
        }
    }

    public function shop()
    {
        $where = "s.status=1 AND s.have_shop=1 AND sh.deliver_type IN (0, 3)";//array('status' => 1);

        if(!empty($_GET['keyword'])){
            $where .= " AND s.name LIKE '%{$_GET['keyword']}%'";
        }
        if ($this->system_session['area_id']) {
            $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
            $where .= " AND s.{$area_index} = '{$this->system_session['area_id']}'";
        }
        $sql_count = "SELECT count(1) AS cnt FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where}";
        $mode = new model();
        $count = $mode->query($sql_count);
        $count = isset($count[0]['cnt']) ? $count[0]['cnt'] : 0;
        import('@.ORG.system_page');
        $p = new Page($count, 20);

        $sql = "SELECT `m`.`name` AS merchant_name, `s`.`name` AS store_name, `m`.`phone` AS merchant_phone, `s`.`phone` AS store_phone, `s`.`store_id`, `sh`.* FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "merchant_store_shop AS sh ON `sh`.`store_id`=`s`.`store_id` WHERE {$where} ORDER BY `sh`.`delivery_radius` ASC LIMIT {$p->firstRow}, {$p->listRows}";
        $order_list = $mode->query($sql);

        $this->assign('order_list', $order_list);
        $this->assign('pagebar', $p->show());
        $this->display();
    }

    public function shop_edit()
    {
        $this->assign('bg_color','#F3F3F3');
        $database = D('Merchant_store_shop');
        $where['store_id'] = intval($_GET['store_id']);
        $now_shop = $database->field(true)->where($where)->find();

        if (empty($now_shop)) {
            $this->frame_error_tips('没有找到该店铺信息！');
        }
        if ($now_shop['delivery_range_polygon']) {
            $now_shop['delivery_range_polygon'] = substr($now_shop['delivery_range_polygon'], 9, strlen($now_shop['delivery_range_polygon']) - 11);
            $lngLatData = explode(',', $now_shop['delivery_range_polygon']);
            array_pop($lngLatData);
            $lngLats = array();
            foreach ($lngLatData as $lnglat) {
                $lng_lat = explode(' ', $lnglat);
                $lngLats[] = array('lng' => $lng_lat[0], 'lat' => $lng_lat[1]);
            }
            $now_shop['delivery_range_polygon'] = json_encode(array($lngLats));
        }

        $is_have_two_time = 0;
        if ($this->config['delivery_time2']) {
            $delivery_times2 = explode('-', $this->config['delivery_time2']);
            $start_time2 = $delivery_times2[0];
            $stop_time2 = $delivery_times2[1];
            if ($start_time2 != $stop_time2) {
                $is_have_two_time = 1;
            }
        }
        $this->assign('is_have_two_time', $is_have_two_time);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();
        $now_shop = array_merge($now_shop, $now_store);
        $this->assign('now_shop', $now_shop);
        $this->display();
    }

    public function shop_amend()
    {
        if (IS_POST) {
            $where = array('store_id' => $_POST['id']);
            unset($_POST['id']);
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
            if (D('Merchant_store_shop')->where($where)->save($_POST)) {
                $this->success('编辑成功！');
            } else {
                $this->error('编辑失败！请重试~');
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function refund_update(){
        $database_shop_order = D('Shop_order');
        $condition_shop_order['order_id'] = $_GET['order_id'];
        $now_order = $database_shop_order->field(true)->where($condition_shop_order)->find();
        if(empty($now_order)){
            $this->error('此订单不存在！');
        }
        $data['status'] = 4;
        $data['last_time'] = time();
        if($now_order['pay_type'] == 'moneris' && $now_order['paid'] == 1){
            import('@.ORG.pay.MonerisPay');
            $moneris_pay = new MonerisPay();
            //判读此订单是否修改过价格
            $record_type = $now_order['is_refund'] == 1 ? 3 : 1;
            $resp = $moneris_pay->refund($now_order['uid'],$now_order['order_id'],-1,$record_type);
//                var_dump($now_order['order_id']);die();
            if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
//                    $data_shop_order['order_id'] = $now_order['order_id'];
//                    $data_shop_order['status'] = 4;
//                    $data_shop_order['last_time'] = time();
//                    D('Shop_order')->data($data_shop_order)->save();
            }else{
                $this->error('删除失败！请重试~');
            }
        }elseif($now_order['pay_type'] == '' && $now_order['paid'] == 1 && $now_order['balance_pay'] > 0){
            $add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$_GET['order_id'].' 增加余额',0,0,0,"Order Cancellation (Order # ".$_GET['order_id'].")");
        }
        if($database_shop_order->where($condition_shop_order)->setField('status',4)){
            $this->success('订单状态已改为已退款！');
        }else{
            $this->error('订单状态改变失败！');
        }
    }

    public function export()
    {
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Order Summary';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        // 设置当前的sheet

        $where_store = null;
        if(!empty($_GET['keyword']) && $_GET['searchtype'] == 's_name'){
            $where_store['name'] = array('like', '%'.$_GET['keyword'].'%');
        }

        if ($this->system_session['area_id']) {
            $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
            $where_store[$area_index] = $this->system_session['area_id'];
        }

        $store_ids = array();
        $where = array();
        $condition_where = 'WHERE 1=1';
        if ($where_store) {
            $stores = D('Merchant_store')->field('store_id')->where($where_store)->select();
            foreach ($stores as $row) {
                $store_ids[] = $row['store_id'];
            }
            if ($store_ids) {
                $where['store_id'] = array('in', $store_ids);
                //                 $condition_where .= ' AND o.store_id IN ('.explode(',',$store_ids).')';
                $condition_where .= ' AND o.store_id IN ('.implode(',',$store_ids).')';//implode,explode
            }
        }

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
            }elseif ($_GET['searchtype'] == 'third_id') {
                $where['third_id'] =$_GET['keyword'];
                $condition_where .= ' AND o.third_id = "'.  $_GET['keyword'].'"';
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
            //$condition_where .= ' AND o.paid=0';
            $condition_where .= ' AND paid=0';
        }else if ($status != -1) {
            $where['status'] = $status;
            //$condition_where .= ' AND o.status='.$status;
            $condition_where .= ' AND status='.$status;
        }

        if($pay_type&&$pay_type!='balance'){
            $where['pay_type'] = $pay_type;
            //$condition_where .= ' AND o.pay_type="'.$pay_type.'"';
            $condition_where .= ' AND pay_type="'.$pay_type.'"';
        }else if($pay_type=='balance'){
            $where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
            //$condition_where .= ' AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )';
            $condition_where .= ' AND (`balance_pay`<>0 OR `merchant_balance` <> 0 )';
        }

        if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            //$condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_where .=  " AND (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }
        //$condition_where.=" AND o.is_del=0";
        $condition_where.=" AND is_del=0";
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
            $objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('Y')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('Z')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('AA')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('AB')->setAutoSize(true);

            $objActSheet->setCellValue('A1', 'Order # 订单编号');
            $objActSheet->setCellValue('B1', 'User ID');
            $objActSheet->setCellValue('C1', 'Order Status订单状态');
            $objActSheet->setCellValue('D1', 'Store Name店铺名称');
            $objActSheet->setCellValue('E1', '商品名称');
            $objActSheet->setCellValue('F1', '数量');
            $objActSheet->setCellValue('G1', '单价');
            $objActSheet->setCellValue('H1', 'Subtotal商品总价（税前）');
            $objActSheet->setCellValue('I1', 'Tax on Subtotal商品税费');
            $objActSheet->setCellValue('J1', 'Delivery Fee配送费');
            $objActSheet->setCellValue('K1', 'Tax on Delivery Fee配送费税');
            $objActSheet->setCellValue('L1', 'Packing Fee打包费');
            $objActSheet->setCellValue('M1', 'Tax on Packing Fee打包费税');
            $objActSheet->setCellValue('N1', 'Bottle Deposit');
            $objActSheet->setCellValue('O1', 'Service Fee服务费');
            $objActSheet->setCellValue('P1', 'Order Amount订单总价');
            $objActSheet->setCellValue('Q1', 'Tips小费');
            $objActSheet->setCellValue('R1', 'Delivery Discount配送费优惠');
            $objActSheet->setCellValue('S1', 'Coupon优惠卷');
            $objActSheet->setCellValue('T1', '(Merchant Discount商家优惠)');
            $objActSheet->setCellValue('U1', 'Amount Paid实付总价');
            $objActSheet->setCellValue('V1', 'Payment Info支付情况');
            $objActSheet->setCellValue('W1', 'Payment Time支付时间');
            $objActSheet->setCellValue('X1', 'Complete Time送达时间');
            $objActSheet->setCellValue('Y1', '出餐时间');
            $objActSheet->setCellValue('Z1', 'User Name客户姓名');
            $objActSheet->setCellValue('AA1', 'User Phone Number客户电话');
            $objActSheet->setCellValue('AB1', 'Address客户地址');



            $sql = "SELECT o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num,g.tax_num,g.deposit_price, s.default_tax,ds.dining_time,s.name AS store_name FROM (select * from pigcms_shop_order ".$condition_where." LIMIT ". $i*1000 .",1000)o LEFT JOIN pigcms_merchant_store AS s ON s.store_id=o.store_id LEFT JOIN pigcms_merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN pigcms_shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` LEFT JOIN pigcms_shop_goods AS g ON `g`.`goods_id`=`d`.`goods_id` LEFT JOIN pigcms_deliver_supply AS ds ON `ds`.`order_id`=`o`.`order_id` ORDER BY o.order_id DESC";
            //$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
            //
            //$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";

            $result_list = D()->query($sql);
            fdump(D()->getDbError());
            //dump($result_list);die;
            $tmp_id = 0;
            if (!empty($result_list)) {
                $curr_order = '';
                $record_list = array();

                $curr_tax = 0;
                $curr_deposit = 0;

                $curr_num = 0;
                foreach ($result_list as $v){
                    if($curr_order != $v['order_id']){
                        if($curr_order == ''){
                            $curr_order = $v['order_id'];
                        }else{
                            $record_list[$curr_order]['goods_tax'] = $curr_tax;
                            $record_list[$curr_order]['deposit_price'] = $curr_deposit;

                            $curr_order = $v['order_id'];
                        }

                        $curr_tax = $v['good_price']*$v['good_num']*$v['tax_num']/100;
                        $curr_deposit = $v['good_num']*$v['deposit_price'];
                    }else{
                        $curr_tax += $v['good_price']*$v['good_num']*$v['tax_num']/100;
                        $curr_deposit += $v['good_num']*$v['deposit_price'];
                    }

                    $curr_num++;

                    if($curr_num == count($result_list)) {
                        $record_list[$curr_order]['goods_tax'] = $curr_tax;
                        $record_list[$curr_order]['deposit_price'] = $curr_deposit;
                    }
                }


                $index = 1;
                foreach ($result_list as $value) {
                    if($tmp_id == $value['real_orderid']){
                        $objActSheet->setCellValueExplicit('A' . $index, '');//订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, '');
                        $objActSheet->setCellValueExplicit('C' . $index, '');
                        $objActSheet->setCellValueExplicit('D' . $index, '');
                        $objActSheet->setCellValueExplicit('E' . $index, $value['good_name']);//商品名称
                        $objActSheet->setCellValueExplicit('F' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        $objActSheet->setCellValueExplicit('G' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('H' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objActSheet->setCellValueExplicit('I' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objActSheet->setCellValueExplicit('J' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objActSheet->setCellValueExplicit('K' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);
                        $objActSheet->setCellValueExplicit('L' . $index, '');
                        $objActSheet->setCellValueExplicit('M' . $index, '');
                        $objActSheet->setCellValueExplicit('N' . $index, '');
                        $objActSheet->setCellValueExplicit('O' . $index, '');
                        $objActSheet->setCellValueExplicit('P' . $index,'');
                        $objActSheet->setCellValueExplicit('Q' . $index, '');
                        $objActSheet->setCellValueExplicit('R' . $index, '');
                        $objActSheet->setCellValueExplicit('S' . $index, '');
                        $objActSheet->setCellValueExplicit('T' . $index, '');
                        $objActSheet->setCellValueExplicit('U' . $index, '');
                        $objActSheet->setCellValueExplicit('V' . $index, '');
                        $objActSheet->setCellValueExplicit('W' . $index, '');
                        $objActSheet->setCellValueExplicit('X' . $index, '');
                        $objActSheet->setCellValueExplicit('Y' . $index, '');
                        $objActSheet->setCellValueExplicit('Z' . $index, '');
                        $objActSheet->setCellValueExplicit('AA' . $index, '');
                        $objActSheet->setCellValueExplicit('AB' . $index, '');
                        $index++;
                    }else{
                        $index++;
                        $objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);//订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, $value['uid']);//User ID
                        $objActSheet->setCellValueExplicit('C' . $index, D('Shop_order')->status_list[$value['status']]);//订单状态
                        $objActSheet->setCellValueExplicit('D' . $index, $value['store_name']);//店铺名称
                        $objActSheet->setCellValueExplicit('E' . $index, $value['good_name']);//商品名称
                        $objActSheet->setCellValueExplicit('F' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        $objActSheet->setCellValueExplicit('G' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('H' . $index, $value['goods_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）
                        $objActSheet->setCellValueExplicit('I' . $index, floatval(sprintf("%.2f", $record_list[$value['order_id']]['goods_tax'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品税费
                        $objActSheet->setCellValueExplicit('J' . $index, $value['freight_charge'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                        $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $value['freight_charge'] * $value['default_tax']/100)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费(税费)
                        $objActSheet->setCellValueExplicit('L' . $index, $value['packing_charge'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//打包费
                        $objActSheet->setCellValueExplicit('M' . $index, floatval(sprintf("%.2f", $value['packing_charge'] * $value['default_tax']/100)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//打包费(税费)
                        $objActSheet->setCellValueExplicit('N' . $index, floatval(sprintf("%.2f", $record_list[$value['order_id']]['deposit_price'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//Bottle Deposit
                        $objActSheet->setCellValueExplicit('O' . $index, $value['service_fee'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//服务费
                        $objActSheet->setCellValueExplicit('P' . $index, floatval($value['total_price'] + $value['tip_charge'] - $value['coupon_price'] - $value['delivery_discount'] - $value['merchant_reduce']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//订单总价
                        $objActSheet->setCellValueExplicit('Q' . $index, floatval(sprintf("%.2f",$value['tip_charge'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//小费
                        $objActSheet->setCellValueExplicit('R' . $index, floatval(sprintf("%.2f",$value['delivery_discount'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//减免配送费
                        $objActSheet->setCellValueExplicit('S' . $index, floatval(sprintf("%.2f",$value['coupon_price'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//优惠券金额
                        $objActSheet->setCellValueExplicit('T' . $index, floatval(sprintf("%.2f",$value['merchant_reduce'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
                        $objActSheet->setCellValueExplicit('U' . $index, floatval(sprintf("%.2f", $value['total_price'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//实付总价
                        $objActSheet->setCellValueExplicit('V' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));//支付情况
                        $objActSheet->setCellValueExplicit('W' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');//支付时间
                        $objActSheet->setCellValueExplicit('X' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');//送达时间
                        $objActSheet->setCellValueExplicit('Y' . $index, $value['dining_time'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//出餐时间
                        $objActSheet->setCellValueExplicit('Z' . $index, $value['username']);//客户姓名
                        $objActSheet->setCellValueExplicit('AA' . $index, $value['userphone'] . ' ');//客户电话
                        $objActSheet->setCellValueExplicit('AB' . $index, $value['address'] . ' ');//客户地址


                        $index++;
                    }
                    $tmp_id = $value['real_orderid'];
                }
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

    public function export_store(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Store Ranking';

        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error('请选择时间！');
        }else{
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $condition_where ="where o.is_del=0  AND (o.status=2 or o.status=3) and o.paid=1";
            $title .= '('.$_GET['begin_time'].' - '.$_GET['end_time'].')';
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if($_GET['city_id']){
            $condition_where .= " AND s.city_id=".$_GET['city_id'];
            $city = D('Area')->where(array('area_id'=>$_GET['city_id']))->find();
            $title .= ' - '.$city['area_name'];

            $where['city_id'] = $_GET['city_id'];
        }

        $sql = "SELECT COUNT(o.order_id) as count,SUM(o.price) as sum,s.`name` as store_name,s.store_id FROM " . C('DB_PREFIX') . "merchant_store AS s LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON s.store_id=o.store_id ".$condition_where." GROUP BY s.store_id ORDER BY SUM(o.price) DESC";
        $list = D()->query($sql);

        $where['status'] = 1;
        $store_list = D('Merchant_store')->where($where)->select();

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle('Ranking');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        $objActSheet->setCellValue('A1', '订单数量');
        $objActSheet->setCellValue('B1', '销售金额');
        $objActSheet->setCellValue('C1', '店铺名称');
        $index = 2;

        $store_id_list = array();
        foreach ($list as $store) {
            $objActSheet->setCellValueExplicit('A' . $index, $store['count'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", $store['sum'])), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('C' . $index, $store['store_name']);
            $index++;

            $store_id_list[] = $store['store_id'];
        }

        foreach ($store_list as $store){
            if(!in_array($store['store_id'],$store_id_list)){
                $merchant = D('Merchant')->where(array('mer_id'=>$store['mer_id']))->find();
                if($merchant['status'] == 1) {
                    $objActSheet->setCellValueExplicit('A' . $index, 0, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objActSheet->setCellValueExplicit('B' . $index, 0.00, PHPExcel_Cell_DataType::TYPE_NUMERIC);
                    $objActSheet->setCellValueExplicit('C' . $index, $store['name']);
                    $index++;
                }
            }
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
        header('Content-Disposition:attachment;filename="'.$title.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function export_user(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'User Ranking';

        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error('请选择时间！');
        }else{
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $condition_where ="where o.is_del=0  AND (o.status=2 or o.status=3) and o.paid=1";
            $title .= '('.$_GET['begin_time'].' - '.$_GET['end_time'].')';
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if($_GET['city_id']){
            $condition_where .= " AND s.city_id=".$_GET['city_id'];
            $city = D('Area')->where(array('area_id'=>$_GET['city_id']))->find();
            $title .= ' - '.$city['area_name'];

            $where['city_id'] = $_GET['city_id'];
        }

        $sql = "SELECT COUNT(o.order_id) as count,SUM(o.price) as sum,o.username,u.add_time FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "user as u ON u.uid=o.uid ".$condition_where." GROUP BY o.uid ORDER BY SUM(o.price) DESC";
        $list = D()->query($sql);

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle('Ranking');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

        $objActSheet->setCellValue('A1', '订单数量');
        $objActSheet->setCellValue('B1', '销售金额');
        $objActSheet->setCellValue('C1', '用户名');
        $objActSheet->setCellValue('D1', '注册时间');
        $index = 2;

        foreach ($list as $store) {
            $objActSheet->setCellValueExplicit('A' . $index, $store['count'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", $store['sum'])), PHPExcel_Cell_DataType::TYPE_NUMERIC);
            $objActSheet->setCellValueExplicit('C' . $index, $store['username']);
            $objActSheet->setCellValueExplicit('D' . $index, date('Y-m-d',$store['add_time']));
            $index++;
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
        header('Content-Disposition:attachment;filename="'.$title.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function export_total(){
        set_time_limit(0);
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $title = 'Order Total';

        if(!$_GET['begin_time'] || !$_GET['end_time']){
            $this->error('请选择时间！');
        }else{
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error_tips("结束时间应大于开始时间");
            }

            $condition_where ="where o.is_del=0  AND (o.status=2 or o.status=3) and paid=1";
            $title .= '('.$_GET['begin_time'].' - '.$_GET['end_time'].')';
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
        }

        if($_GET['city_id']){
            $condition_where .= " AND s.city_id=".$_GET['city_id'];
        }

        $sql = "SELECT  o.*, m.name AS merchant_name,g.name as good_name,g.tax_num as good_tax,g.deposit_price,s.tax_num as store_tax,s.proportion as store_pro,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id`  LEFT JOIN " . C('DB_PREFIX') . "shop_goods AS g ON `g`.`goods_id`=`d`.`goods_id` ".$condition_where." ORDER BY o.order_id DESC";

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
        $total_reduce = 0;
        //商品抽成总数
        $total_goods_price_pro = 0;
        //商品税点抽成总数
        $total_goods_tax_pro = 0;
        //订单总数
        $order_count = 0;
        //小费总数
        $total_tip = 0;
        //优惠券使用金额总数
        $total_coupon_discount = 0;
        //配送费减免总数
        $total_delivery_discount = 0;

        //结束循环后是否存储最后一张订单，如果最后一张是代客下单为 false;
        $is_last = true;
        $last_pro = 0;
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
                $total_goods_price_pro += ($val['goods_price'] - $val['merchant_reduce']) * $val['store_pro'] / 100;
                $total_goods_tax += $val['discount_price'];
                $total_goods_tax_pro += $val['discount_price'] * $val['store_pro'] / 100;
                $total_freight_price += $val['freight_charge'];
                $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                $total_packing_price += $val['packing_charge'];
                $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                $total_all_tax += $all_record[$curr_order]['total_tax'];
                $total_deposit += $all_record[$curr_order]['all_deposit'];
                $total_all_price += $val['price'];
                $total_cash += $val['price'];
                $total_tip += $val['tip_charge'];
                $total_reduce += $val['merchant_reduce'];
                $total_coupon_discount += $val['coupon_price'];
                $total_delivery_discount += $val['delivery_discount'];

                $order_count++;
            }else{
                if($curr_order != $record_id){
                    $order_count++;
                    //记录上一张订单的税费和押金
                    if($record_id != '') {
                        $all_record[$record_id]['all_tax'] = $all_tax;
                        $all_record[$record_id]['all_deposit'] = $all_deposit;
                        $all_record[$record_id]['total_tax'] = $all_tax + $all_record[$record_id]['freight_tax'] + $all_record[$record_id]['packing_tax'];

                        $total_goods_tax += $all_tax;
                        $total_goods_tax_pro += $all_tax * $val['store_pro'] / 100;
                        $total_deposit += $all_deposit;
                        $total_all_tax += $all_record[$record_id]['total_tax'];
                    }
                    //记录最新订单的基本数值
                    $total_goods_price += $val['goods_price'];
                    $total_goods_price_pro += ($val['goods_price'] - $val['merchant_reduce']) * $val['store_pro'] / 100;
                    $total_freight_price += $val['freight_charge'];
                    $total_freight_tax += $val['freight_charge']*$val['store_tax']/100;
                    $total_packing_price += $val['packing_charge'];
                    $total_packing_tax += $val['packing_charge']*$val['store_tax']/100;
                    $total_all_price += $val['price'];
                    $total_tip += $val['tip_charge'];
                    $total_reduce += $val['merchant_reduce'];
                    $total_coupon_discount += $val['coupon_price'];
                    $total_delivery_discount += $val['delivery_discount'];

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
                $last_pro = $val['$all_tax'];
            }
        }
        //记录最后一张订单
        if ($is_last){
            $all_record[$record_id]['all_tax'] = $all_tax;
            $all_record[$record_id]['all_deposit'] = $all_deposit;
            $all_record[$record_id]['total_tax'] = $total_tax;

            $total_goods_tax += $all_tax;
            $total_goods_tax_pro += $all_tax * $last_pro / 100;
            $total_deposit += $all_deposit;
            $total_all_tax += $total_tax;
        }

        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);

        $objExcel->createSheet();
        $objExcel->setActiveSheetIndex(0);
        $objExcel->getActiveSheet()->setTitle('Order Total');
        $objActSheet = $objExcel->getActiveSheet();
        $objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

        $objActSheet->setCellValue('A1', '订单数量');
        $objActSheet->setCellValue('B1', '税前商品总金额');
        $objActSheet->setCellValue('C1', '商品税费总金额');
        $objActSheet->setCellValue('D1', '配送费总金额');
        $objActSheet->setCellValue('E1', '配送费税费总金额');
        $objActSheet->setCellValue('F1', '税前商品抽成总金额');
        $objActSheet->setCellValue('G1', '商品税费抽成总金额');//无
        $objActSheet->setCellValue('H1', '订单总金额');
        $objActSheet->setCellValue('I1', '小费总数');
        $objActSheet->setCellValue('J1', '优惠券金额总数');
        $objActSheet->setCellValue('K1', '免配送费金额总数');
        $index = 2;
        $objActSheet->setCellValueExplicit('A' . $index, $order_count,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('B' . $index, floatval(sprintf("%.2f", ($total_goods_price - $total_reduce))),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('C' . $index, floatval(sprintf("%.2f", $total_goods_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('D' . $index, floatval(sprintf("%.2f", $total_freight_price)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('E' . $index, floatval(sprintf("%.2f", $total_freight_tax)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('F' . $index, floatval(sprintf("%.2f", $total_goods_price_pro)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('G' . $index, floatval(sprintf("%.2f", $total_goods_tax_pro)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", ($total_all_price - $total_reduce))),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('I' . $index, floatval(sprintf("%.2f", $total_tip)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $total_coupon_discount)),PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $total_delivery_discount)),PHPExcel_Cell_DataType::TYPE_NUMERIC);

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
        header('Content-Disposition:attachment;filename="'.$title.'.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();
    }

    public function get_order_status(){
        if($_POST){
            $order_id = $_POST['order_id'];
            $list = D('Shop_order_log')->where(array('order_id'=>$order_id))->select();

            if($list){
                $show_list = array();
                foreach ($list as $v){
                    if($v['status'] != 0) {
                        $status_txt = "";
                        switch ($v['status']) {
                            case 1:
                                $status_txt = "<div>顾客下单：";
                                break;
                            case 2:
                                $status_txt = "<div style='color: #ffa52d'>商家接单：";
                                break;
                            case 3:
                                $status_txt = "<div>送餐员接单：";
                                break;
                            case 4:
                                $status_txt = "<div style='color: #008037'>已取货：";
                                break;
                            case 5:
                                $status_txt = "<div style='color: #004aad'>开始配送：";
                                break;
                            case 6:
                                $status_txt = "<div>送达时间：";
                                break;
                            case 33:
                                $status_txt = "<div style='color: #ff5757'>增加出餐时间：".$v['note'].'分钟</div>';
                                break;

                        }
                        if($v['status'] != 33)
                            $show_list[] = $status_txt . ' ' . date('H:i', $v['dateline']).'</div>';
                        else
                            $show_list[] = $status_txt;
                        if($v['status'] == 2){
                            $supply = D('Deliver_supply')->where(array("order_id"=>$order_id))->find();
                            $show_list[] = "<div style='color: #ff5757'>预计出餐：" . ' ' . date('H:i', $v['dateline']+$supply['dining_time']*60).'</div>';
                            if(!$supply['uid'])
                                array_unshift($show_list,'<div>顾客下单： ' . date('H:i', $v['dateline']).'</div>');
                        }
                    }
                }
                $data['error'] = 0;
                $data['list'] = $show_list;
                exit(json_encode($data));
            }else{
                $data['error'] = 1;
                exit(json_encode($data));
            }
        }
    }
}