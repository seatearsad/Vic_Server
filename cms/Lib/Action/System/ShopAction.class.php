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
        $database_shop_category = D('Shop_category');
        $category = $database_shop_category->field(true)->where(array('cat_id' => $parentid))->find();
        $category_list = $database_shop_category->field(true)->where(array('cat_fid' => $parentid))->order('`cat_sort` DESC,`cat_id` ASC')->select();
        $this->assign('category', $category);
        $this->assign('category_list', $category_list);
        $this->assign('parentid', $parentid);
        $this->display();
    }

    public function cat_add()
    {
        $this->assign('bg_color','#F3F3F3');
        $parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
        $this->assign('parentid', $parentid);
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
        if (empty($now_category)) {
            $this->frame_error_tips('没有找到该分类信息！');
        }
        $this->assign('parentid', $parentid);
        $this->assign('now_category', $now_category);
        $this->display();
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

        $result = D("Shop_order")->get_order_list($where, $order_sort, 3);
        $list = isset($result['order_list']) ? $result['order_list'] : '';
        $store_ids = array();
        foreach ($list as $l) {
            $store_ids[] = $l['store_id'];
        }
        $temp = array();
        if ($store_ids) {
            $store_ids = implode(',', $store_ids);
            $sql = "SELECT `m`.`name` AS merchant_name, `s`.`name` AS store_name, `s`.`phone` AS store_phone, `s`.`store_id` FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON `s`.`mer_id`=`m`.`mer_id` WHERE `s`.`store_id` IN ($store_ids)";
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
        }
        $this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));
        $this->assign('status_list', D('Shop_order')->status_list_admin);
        $this->assign($result);

        $field = 'sum(price) AS total_price, sum(price - card_price - merchant_balance - balance_pay - payment_money - score_deducte - coupon_price - card_give_money) AS offline_price, sum(card_price + merchant_balance + balance_pay + payment_money + score_deducte + coupon_price + card_give_money) AS online_price';
        $count_where = "paid=1 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
        $result_total = D('Shop_order')->field($field)->where($count_where)->select();
        $result_total = isset($result_total[0]) ? $result_total[0] : '';
        $this->assign($result_total);
        $pay_method = D('Config')->get_pay_method('','',0);
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
        if (empty($order)) {
            $this->frame_error_tips('没有找到该订单的信息！');
        }else{//garfunkel 重新获取商品名称
            foreach ($order['info'] as $k => $v){
                $g_id = $v['goods_id'];
                $goods = D('Shop_goods')->get_goods_by_id($g_id);
                $order['info'][$k]['name'] = $goods['name'];
            }
        }
        $this->assign('store', D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find());
        $this->assign('order', $order);
        $this->display();
    }

    //admin 修改订单
    public function edit_order(){
        $order_id['order_id']= intval($_GET['order_id']);//订单id
        $shop_order = M('Shop_order');
        $shop_order_data = $shop_order->field(true)->where($order_id)->find();
        if ($shop_order_data){
            $this->assign('order',$shop_order_data);
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

            //同时修改配送员端的价格
            $deliver_data['money'] = $data['price'];
            if($shop_order_data['pay_type'] != 'moneris')
                $deliver_data['deliver_cash'] = $data['price'];
            D('Deliver_supply')->field(true)->where(array('order_id'=>$order_id))->save($deliver_data);

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
            }

            ////////
            if ($shop_order->where("order_id=$order_id")->data($data)->save()){
                $this->success('修改成功！');
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
            }
            ///////
            if ($shop_order->where("order_id=$order_id")->data($data)->save()){
                $this->success('删除成功！');
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
        $order = $database_shop_order->field('`order_id`,`mer_id`')->where($condition_shop_order)->find();
        if(empty($order)){
            $this->error('此订单不存在！');
        }
        $data['status'] = 4;
        $data['last_time'] = time();
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
        $title = '订单信息';
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
            $condition_where .= ' AND o.paid=0';
        }else if ($status != -1) {
            $where['status'] = $status;
            $condition_where .= ' AND o.status='.$status;
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
            $objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
            $objExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
            $objActSheet->setCellValue('A1', '订单编号');
            $objActSheet->setCellValue('B1', '商品名称');
            $objActSheet->setCellValue('C1', '数量');
            $objActSheet->setCellValue('D1', '单价');
            $objActSheet->setCellValue('E1', '店铺名称');
            $objActSheet->setCellValue('F1', '客户姓名');
            $objActSheet->setCellValue('G1', '商品总价（税前）');//无
            $objActSheet->setCellValue('H1', '商品税费');
            $objActSheet->setCellValue('I1', '配送费');
            $objActSheet->setCellValue('J1', '配送费税');
            $objActSheet->setCellValue('K1', '实付总价');
            $objActSheet->setCellValue('L1', '支付时间');
            $objActSheet->setCellValue('M1', '订单状态');
            $objActSheet->setCellValue('N1', '支付情况');
            $objActSheet->setCellValue('O1', '订单总价');
            $objActSheet->setCellValue('P1', '商家名称');
            $objActSheet->setCellValue('Q1', '商品进价');
            $objActSheet->setCellValue('R1', '单位');
            $objActSheet->setCellValue('S1', '平台优惠');
            $objActSheet->setCellValue('T1', '商家优惠');
            $objActSheet->setCellValue('U1', '在线支付金额');
            $objActSheet->setCellValue('V1', '客户地址');
            $objActSheet->setCellValue('W1', '客户电话');
            $objActSheet->setCellValue('X1', '送达时间');
            $sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
            //
            //$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.unit,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";

            $result_list = D()->query($sql);
            fdump(D()->getDbError());
            //			dump($result_list);die;
            $tmp_id = 0;
            if (!empty($result_list)) {
                $index = 1;
                foreach ($result_list as $value) {
                    if($tmp_id == $value['real_orderid']){
                        $objActSheet->setCellValueExplicit('A' . $index, '');//订单编号
                        $objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);//商品名称
                        $objActSheet->setCellValueExplicit('C' . $index, $value['good_num'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//数量
                        $objActSheet->setCellValueExplicit('D' . $index, $value['good_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//单价
                        $objActSheet->setCellValueExplicit('E' . $index,'');//店铺名称
                        $objActSheet->setCellValueExplicit('F' . $index, '');//客户姓名
                        $objActSheet->setCellValueExplicit('G' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品总价（税前）//无
                        $objActSheet->setCellValueExplicit('H' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品税费
                        $objActSheet->setCellValueExplicit('I' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费（含税）//无
                        $objActSheet->setCellValueExplicit('J' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                        $objActSheet->setCellValueExplicit('K' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//实付总价
                        $objActSheet->setCellValueExplicit('L' . $index, '');//支付时间
                        $objActSheet->setCellValueExplicit('M' . $index, '');//订单状态
                        $objActSheet->setCellValueExplicit('N' . $index, '');//支付情况
                        $objActSheet->setCellValueExplicit('O' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//订单总价
                        $objActSheet->setCellValueExplicit('P' . $index,'');//商家名称
                        $objActSheet->setCellValueExplicit('Q' . $index, $value['cost_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品进价
                        $objActSheet->setCellValueExplicit('R' . $index, $value['unit']);//单位
                        $objActSheet->setCellValueExplicit('S' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//平台优惠
                        $objActSheet->setCellValueExplicit('T' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
                        $objActSheet->setCellValueExplicit('U' . $index, '',PHPExcel_Cell_DataType::TYPE_NUMERIC);//在线支付金额
                        $objActSheet->setCellValueExplicit('V' . $index, '');//客户地址
                        $objActSheet->setCellValueExplicit('W' . $index, '');//客户电话
                        $objActSheet->setCellValueExplicit('X' . $index, '');//送达时间
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
                        $objActSheet->setCellValueExplicit('H' . $index, floatval(sprintf("%.2f", $value['goods_price'] * 0.05)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//税费
                        $objActSheet->setCellValueExplicit('I' . $index, $value['freight_charge'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费（含税）
                        $objActSheet->setCellValueExplicit('J' . $index, floatval(sprintf("%.2f", $value['freight_charge'] * 0.05)),PHPExcel_Cell_DataType::TYPE_NUMERIC);//配送费
                        $objActSheet->setCellValueExplicit('K' . $index, floatval(sprintf("%.2f", $value['price'])),PHPExcel_Cell_DataType::TYPE_NUMERIC);//实付总价
                        $objActSheet->setCellValueExplicit('L' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');//支付时间
                        $objActSheet->setCellValueExplicit('M' . $index, D('Shop_order')->status_list[$value['status']]);//订单状态
                        $objActSheet->setCellValueExplicit('N' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));//支付情况
                        $objActSheet->setCellValueExplicit('O' . $index, floatval($value['total_price']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//订单总价
                        $objActSheet->setCellValueExplicit('P' . $index, $value['merchant_name']);//商家名称
                        $objActSheet->setCellValueExplicit('Q' . $index, $value['cost_price'],PHPExcel_Cell_DataType::TYPE_NUMERIC);//商品进价
                        $objActSheet->setCellValueExplicit('R' . $index, $value['unit']);//单位
                        $objActSheet->setCellValueExplicit('S' . $index, floatval($value['balance_reduce']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//平台优惠
                        $objActSheet->setCellValueExplicit('T' . $index, floatval($value['merchant_reduce']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//商家优惠
                        $objActSheet->setCellValueExplicit('W' . $index, floatval($value['payment_money']),PHPExcel_Cell_DataType::TYPE_NUMERIC);//在线支付金额
                        $objActSheet->setCellValueExplicit('U' . $index, $value['address'] . ' ');//客户地址
                        $objActSheet->setCellValueExplicit('V' . $index, $value['userphone'] . ' ');//客户电话
                        $objActSheet->setCellValueExplicit('W' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');//送达时间
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
}