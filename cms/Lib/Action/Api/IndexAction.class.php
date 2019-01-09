<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/8/20
 * Time: 14:57
 */

class IndexAction extends BaseAction
{
    public function index(){
        //顶部广告
        $head_adver = D('Adver')->get_adver_by_key('app_index_top',5);
        if(empty($head_adver)){
            $head_adver = D('Adver')->get_adver_by_key('wap_index_top',5);
        }
        if(!empty($head_adver)){
            $banner = array();
            foreach($head_adver as &$head_adver_value){
                //unset($head_adver_value['id'],$head_adver_value['bg_color'],$head_adver_value['cat_id'],$head_adver_value['status'],$head_adver_value['last_time'],$head_adver_value['sub_name']);
                $b_one = array();
                $b_one['id'] = $head_adver_value['id'];
                $b_one['url'] = $head_adver_value['pic'];
                $b_one['zt_url'] = $head_adver_value['url'];
                $b_one['title'] = $head_adver_value['name'];

                $banner[] = $b_one;
            }
            $arr['banner']['status'] = 1;
            $arr['banner']['info'] = $banner;
        }else{
            $arr['banner']['status'] = 0;
            $arr['banner']['info'] = array();
        }

        //获取店铺列表
        $page	=	$_POST['page']?$_POST['page']:0;
        $limit = 5;

        $order = 'juli';
        $deliver_type =  'all';

        if($_GET['lat'] != 'null' && $_GET['long'] != 'null'){
            $lat = $_POST['lat'];
            $long = $_POST['long'];
        }

        $cat_id = 0;
        $cat_fid = 0;

        $key = '';
        $where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
        $key && $where['key'] = $key;

        $shop_list = D('Merchant_store_shop')->get_list_arrange($where,3,1,$limit,$page);
//
//        foreach ($shop_list as $k => $v) {
//            $product_list = D('Shop_goods')->get_list_by_storeid($v['site_id']);
//            $shop_list[$k]['goods'] = $product_list;
//        }

        if(!$shop_list['list']){
            $shop_list['list'] = array();
            $shop_list['count'] = '0';
        }

        $arr['best']['status'] = 1;
        $arr['best']['info'] = $shop_list['list'];
        $arr['best']['count'] = $shop_list['count'];

        $this->returnCode(0,'data',$arr);
    }

    public function getStore(){
        $sid = $_POST['sid'];
        $store = $this->loadModel()->get_store_by_id($sid);

        $this->returnCode(0,'info',$store);

    }
    public function loadModel(){
        $this_model = D('Store');

        return $this_model;
    }
    public function getStoreGoods(){
        $sid = $_POST['sid'];

        $t_store = $this->loadModel()->get_store_by_id($sid);
        //店铺状态
        $store['shopstatus'] = $t_store['is_close'] == 0 ? "1" : "0";
        $store['shopname'] = $t_store['site_name'];

        $group = $this->loadModel()->get_goods_group_by_storeId($sid);

        $goods = $this->loadModel()->get_goods_by_storeId($sid);

        $store['group'] = $this->loadModel()-> arrange_group_for_goods($group);
        $store['foods'] = $this->loadModel()-> arrange_goods_for_goods($goods);
        $store['count'] = count($store['foods']);

        $this->returnCode(0,'info',$store);
    }

    public function user_third_Login(){
//        $userInfo = array("uid"=>"1","uname"=>"garfunkel","password"=>"123456","login_type"=>"1",
//            "outsrc"=>"http://thirdqq.qlogo.cn/qqapp/1106028245/ED09815DE876D237105B7BF6F40DEFCA/100",
//            "openid"=>"ED09815DE876D237105B7BF6F40DEFCA"
//        );
        $nickname = $_POST['nickname'];
        $openid = $_POST['openid'];
        $face_pic = $_POST['face_pic'];
        $sex = $_POST['sex'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $type = $_POST['type'];

        $token = $_POST['token'];
        if($type == 2){//微信登录
            $result = D('User')->autologin('openid', $openid);

            if(!$result['user']){
                $data_user = array(
                    'openid' 	=> $openid,
                    'union_id' 	=> '',
                    'nickname' 	=> $nickname,
                    'sex' 		=> $sex,
                    'province' 	=> $province,
                    'city' 		=> $city,
                    'avatar' 	=> $face_pic,
                    'is_follow' => 1,
                );
                $reg_result = D('User')->autoreg($data_user);
                if($reg_result['error_code']){
                    $user['uid'] = '0';
                }else{
                    $user = D('User')->get_user($openid,'openid');
                }
            }else{
                $user = $result['user'];
            }


            $userInfo['uid'] = $user['uid'];
            $userInfo['uname'] = $user['nickname'];
            $userInfo['password'] = $user['pwd'];
            $userInfo['outsrc'] = $user['avatar'];
            $userInfo['openid'] = $user['openid'];
            $userInfo['login_type'] = $type;
            //记录设备号
            D('User')->where(array('uid'=>$userInfo['uid']))->save(array('device_id'=>$token));
        }

        $this->returnCode(0,'info',$userInfo);
    }

    public function userLogin(){
        $userName = $_POST['userName'];
        $password = $_POST['password'];
        $token = $_POST['token'];

        $userInfo = $this->loadModel()->getUserInfo($userName,$password);

        if($userInfo['msg'] != "")
            $code = 1;
        else{
            $code = 0;
            D('User')->where(array('uid'=>$userInfo['uid']))->save(array('device_id'=>$token));
        }

        $this->returnCode($code,'info',$userInfo,$userInfo['msg']);
    }

    public function getVerificationCode(){
        $phone = $_POST['phone'];
        $result = $this->loadModel()->sendVerificationCode($phone);

        if ($result['error_code'])
            $code = 1;
        else
            $code = 0;

        $this->returnCode($code,'info',array(),$result['msg']);
    }

    public function userReg(){
        $phone = $_POST['phone'];
        $vcode = $_POST['vcode'];
        $pwd = $_POST['password'];
        $token = $_POST['token'];

        $result = $this->loadModel()->reg_phone_pwd_vcode($phone,$vcode,$pwd);

        if ($result['error_code'])
            $code = 1;
        else{
            $code = 0;
            D('User')->where(array('uid'=>$result['uid']))->save(array('device_id'=>$token));
        }

        $this->returnCode($code,'info',$result,$result['msg']);
    }

    public function getCommentByStore(){
        $sid = $_POST['sid'];
        $type = $_POST['type'];

        $result = $this->loadModel()->get_comment_sid($sid,$type);

        $this->returnCode(0,'',$result,$result['msg']);
    }

    public function addCart(){
        $uid = $_POST['uid'];
        $fid = $_POST['fid'];
        $num = !empty($_POST['num']) ? $_POST['num']:1;
        $spec = empty($_POST['spec']) ? "" : $_POST['spec'];
        $proper = empty($_POST['proper']) ? "" : $_POST['proper'];

        D('Cart')->add_cart($uid,$fid,$num,$spec,$proper);

        $this->returnCode(0,'info',array(),'success');
    }

    public function addCartAndSpec(){
        $uid = $_POST['uid'];
        $fid = $_POST['fid'];
        $num = !empty($_POST['num']) ? $_POST['num']:1;
        $spec = $_POST['spec'];
        $proper = $_POST['proper'];

        D('Cart')->add_cart($uid,$fid,$num,$spec,$proper);

        $this->returnCode(0,'info',array(),'success');
    }

    public function getCart(){
        $uid = $_POST['uid'];

        $result = D('Cart')->get_cart($uid);

        $this->returnCode(0,'',$result,'success');
    }

    public function getUserDefaultAddress(){
        $uid = $_POST['uid'];

        $adr = $this->loadModel()->getDefaultAdr($uid);

        if ($adr == null)
            $this->returnCode(1,'info',array(),'success');
        else{
            $result[] = $adr;
            $this->returnCode(0,'info',$result,'success');
        }
    }

    public function getUserAddress(){
        $uid = $_POST['uid'];

        $result = $this->loadModel()->getUserAdr($uid);

        $this->returnCode(0,'info',$result,'success');
    }

    public function addUserAddress(){
        $data['uid'] = $_POST['uid'];
        $data['adress_id'] = $_POST['itemid'];
        $data['name'] = $_POST['uname'];
        $data['phone'] = $_POST['phone'];
        $data['adress'] = $_POST['map_addr'];
        $data['zipcode'] = $_POST['map_number'];
        $data['longitude'] = $_POST['lng'];
        $data['latitude'] = $_POST['lat'];
        $data['detail'] = $_POST['map_location'];

        $result = $this->loadModel()->addUserAddress($data);

        $this->returnCode(0,'info',$result,'success');
    }

    public function delUserAddress(){
        $uid = $_POST['uid'];
        $aid = $_POST['itemid'];

        $this->loadModel()->delUserAddress($uid,$aid);

        $this->returnCode(0,'info',array(),'success');
    }

    public function setDefaultAdr(){
        $uid = $_POST['uid'];
        $aid = $_POST['itemid'];

        $this->loadModel()->setDefaultAdr($uid,$aid);

        $this->returnCode(0,'info',array(),'success');
    }

    public function confirmCart(){
        $uid = $_POST['uid'];
        $cartList = $_POST['cart_list'];

        $cart_array = json_decode(html_entity_decode($cartList),true);

        $result = D('Cart')->getCartList($uid,$cart_array);
        //平台优惠劵
        $_POST['amount'] = $result['total_pay_price'];
        $coupon = $this->getCanCoupon();

        $result['coupon'] = $coupon;
        //账户余额
        $userInfo = D('User')->get_user($uid);
        $result['now_money'] = round($userInfo['now_money'],2);

        $this->returnCode(0,'',$result,'success');
    }

    public function saveOrder(){
        $uid = $_POST['uid'];
        $cartList = $_POST['cart_list'];
        $note = $_POST['order_mark'];

        $adr_id = $_POST['addr_item_id'];

        $cart_array = json_decode(html_entity_decode($cartList),true);
        $tax_price = 0;
        $deposit_price = 0;
        $orderData = array();
        foreach ($cart_array as $v){
            $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['fid']))->find();
            $t_good['productId'] = $v['fid'];
            $t_good['productName'] = lang_substr($good['name'],C('DEFAULT_LANG'));
            $specData = D('Shop_goods')->format_spec_value($good['spec_value'], $good['goods_id'], $good['is_properties']);
            if($specData['list'] != "" && $v['spec'] != ""){
                foreach ($specData['list'] as $kk=>$vv){
                    if($v['spec'] == $kk){
                        $good['price'] = $vv['price'];
                    }
                }
            }
            $t_good['productPrice'] = $good['price'];
            $t_good['productStock'] = $good['stock_num'];
            $t_good['productParam'] = array();
            if($v['spec'] != ''){
                $spec_list = explode('_',$v['spec']);
                foreach ($spec_list as $vv){
                    $t_spec['id'] = $vv;
                    $t_spec['type'] = 'spec';
                    $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                    $t_spec['name'] = lang_substr($spec['name'],C('DEFAULT_LANG'));

                    $t_good['productParam'][] = $t_spec;
                }
            }
            if($v['proper'] != ''){
                $t_pro['data'] = array();
                $pro_list = explode('_',$v['proper']);
                foreach ($pro_list as $vv){
                    $t_pro['type'] = 'pro';

                    $ids = explode(',',$vv);
                    $proId = $ids[0];
                    $sId = $ids[1];

                    $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                    $nameList = explode(',',$pro['val']);
                    $name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));

                    $t_pro['data'][] = array('list_id' => $proId,'id' => $sId,'name'=>$name);

                    $t_good['productParam'][] = $t_pro;
                }
            }

            $t_good['count'] = $v['stock'];
            $t_good['tax_num'] = $good['tax_num'];
            $t_good['deposit_price'] = $good['deposit_price'];

            $tax_price += $good['price'] * $good['tax_num']/100 * $v['stock'];
            $deposit_price += $good['deposit_price']*$v['stock'];

            $orderData[] = $t_good;
        }

        $sid = D('Cart')->field(true)->where(array('uid'=>$uid,'fid'=>$cart_array[0]['fid']))->find()['sid'];

        $return = D('Shop_goods')->checkCart($sid, $uid, $orderData);

        $now_time = time();
        $order_data = array();
        $order_data['mer_id'] = $return['mer_id'];
        $order_data['store_id'] = $return['store_id'];
        $order_data['uid'] = $uid;

        $order_data['desc'] = $note;
        $order_data['create_time'] = $now_time;
        $order_data['last_time'] = $now_time;
        $order_data['invoice_head'] = "";
        $order_data['village_id'] = 0;

        $order_data['num'] = $return['total'];
        $order_data['packing_charge'] = $return['store']['pack_fee'];//打包费

        $order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_redu[ce'];//店铺优惠
        $order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
        $orderid  = date('ymdhis').substr(microtime(),2,8-strlen($uid)).$uid;
        $order_data['real_orderid'] = $orderid;
        $order_data['no_bill_money'] = 0;//无需跟平台对账的金额

        $address = D('User_adress')->field(true)->where(array('adress_id' => $adr_id, 'uid' => $uid))->find();

        $order_data['username'] = $address['name'];
        $order_data['userphone'] = $address['phone'];
        $order_data['address'] = $address['adress'].' '.$address['detail'].' '.$address['zipcode'];
        $order_data['address_id'] = $adr_id;
        $order_data['lat'] = $address['latitude'];
        $order_data['lng'] = $address['longitude'];

        $order_data['expect_use_time'] = D('Store')->get_store_delivery_time($sid);
        $order_data['freight_charge'] = $delivery_fee = D('Store')->CalculationDeliveryFee($uid,$sid);

        $order_data['is_pick_in_store'] = 0;

        $order_data['goods_price'] = $return['price'];//商品的价格
        $order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
        $order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
        //modify garfunkel
        //$order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['store']['pack_fee'];//订单总价  商品价格+打包费+配送费
        //$order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
        //$order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['store']['pack_fee'];//实际要支付的价格
        //$order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['packing_charge'];//实际要支付的价格
        //$order_data['price'] = $order_data['price'] * 1.05; //税费

        $tax_price = $tax_price + ($delivery_fee + $return['store']['pack_fee'])*$return['store']['tax_num']/100;
        $order_data['total_price'] = $return['price'] + $tax_price + $deposit_price + $delivery_fee + $return['store']['pack_fee'];
        $order_data['price'] = $order_data['total_price'];

        $order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情

        $order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'

        //订单来源
        $order_data['order_from'] = $_POST['cer_type'];
        //记录支付类型
        if($_POST['pay_type'] == 3){
            $order_data['pay_type'] = "moneris";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }elseif ($_POST['pay_type'] == 4){//余额支付
            $order_data['pay_type'] = "";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }elseif ($_POST['pay_type'] == 2){//微信支付
            $order_data['pay_type'] = "weixin";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }elseif ($_POST['pay_type'] == 1){//支付宝
            $order_data['pay_type'] = "alipay";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }else{
            $order_data['tip_charge'] = 0;
        }

        //处理优惠券
        if($_POST['coupon_id'] && $_POST['coupon_id'] != -1){
            $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
            if(!empty($now_coupon)){
                $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id'=>$_POST['coupon_id']))->find();
                $coupon_real_id = $coupon_data['coupon_id'];
                $coupon = D('System_coupon')->get_coupon($coupon_real_id);
                $order_data['coupon_id'] = $_POST['coupon_id'];
                $order_data['coupon_price'] = $coupon['discount'];
            }
        }


        $order_id = D('Shop_order')->saveOrder($order_data, $return);
        //清除购物车中的内容
        D('Cart')->delCart($uid,$cart_array);
        //die($order_id);
        if($_POST['pay_type'] == 0){//线下支付 直接进入支付流程
            $order_param['order_id'] = $order_id;
            $order_param['order_from'] = 0;
            $order_param['order_type'] = 'shop';
            $order_param['pay_time'] = date();
            $order_param['pay_type'] = 'Cash';
            $order_param['is_mobile'] = 2;
            $order_param['is_own'] = 0;
            $order_param['third_id'] = 0;

            D('Shop_order')->after_pay($order_param);
        }elseif($_POST['pay_type'] == 4){//余额支付
            //账户余额
            $userInfo = D('User')->get_user($uid);
            $now_money = round($userInfo['now_money'],2);

            $data['balance_pay'] = $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'];
            if($now_money >= $data['balance_pay']){
                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($data);

                $order_param = array(
                    'order_id' => $order_id,
                    'pay_type' => '',
                    'order_type'=> 'shop',
                    'third_id' => '',
                    'is_mobile' => 2,
                    'pay_money' => 0,
                    'order_total_money' => $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'],
                    'balance_pay' => $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'],
                    'merchant_balance' => 0,
                    'is_own'	=> 0
                );

                D('Shop_order')->after_pay($order_param);
            }else {
                $this->returnCode(1, 'info', array(), L('_B_MY_NOMONEY_'));
            }
        }else if($_POST['pay_type'] == 3){//信用卡支付

        }else if($_POST['pay_type'] == 1){//支付宝
            $price = $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'];
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price);
            if($result['resCode'] == 'SUCCESS'){
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),'fail');
            }
        }else if($_POST['pay_type'] == 2){//微信支付
            $price = $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'];
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price);
            if($result['resCode'] == 'SUCCESS'){
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),'fail');
            }
        }

        if($order_id != 0)
            $this->returnCode(0,'main_id',$order_id,'success');
        else
            $this->returnCode(1,'info',array(),'fail');
    }

    public function getOrderList(){
        $uid = $_POST['uid'];
        $status = $_POST['status'];
        $_GET['page'] = $_POST['page'];

        $where = "is_del=0 AND uid={$uid}";
        if ($status == 0) {
            $where .= " AND paid=0";
        } elseif ($status == 1) {
            $where .= " AND paid=1 AND status=2";
        } elseif ($status == 2) {
            $where .= " AND paid=1 AND status=4";
        }

        $where .= " AND is_del = 0";

        $count = D("Shop_order")->where($where)->count();

        $order_list = D("Shop_order")->get_order_list($where, 'order_id DESC', false);//field(true)->where($where)->order('order_id DESC')->select();
        $order_list = $order_list['order_list'];

        foreach ($order_list as $st) {
            $store_ids[] = $st['store_id'];
        }
        $m = array();
        if ($store_ids) {
            $store_image_class = new store_image();
            $merchant_list = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
            foreach ($merchant_list as $li) {
                $images = $store_image_class->get_allImage_by_path($li['pic_info']);
                $li['image'] = $images ? array_shift($images) : array();
                unset($li['status']);
                $m[$li['store_id']] = $li;
            }
        }
        $list = array();
        foreach ($order_list as $ol) {
            if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
                $list[] = array_merge($ol, $m[$ol['store_id']]);
            } else {
                $list[] = $ol;
            }
        }

        foreach($list as $key=>$val){
            //modify garfunkel
            $t['rowID'] = $val['real_orderid'];
            $t['userID'] = $val['uid'];
            $t['payType'] = $val['pay_type'];
            $t['payTypeName'] = D('Store')->getPayTypeName($val['pay_type']);
            $t['storeID'] = $val['store_id'];
            $t['storeName'] = lang_substr($val['name'],C('DEFAULT_LANG'));
            $t['createDate'] = date('Y-m-d',$val['create_time']);
            $t['goodsCount'] = $val['num'];
            $t['goodsPrice'] = $val['goods_price'];
            $t['status'] = $val['status'];
            $t['isComment'] = "1";
            $t['statusName'] = D('Store')->getOrderStatusName($val['status']);
            $t['goodsImage'] = $val['image'];
            $t['orderType'] = "0";
            $t['tip_fee'] = $val['tip_charge'];
            $t['total_price'] = $val['price'];
            $t['paid'] = $val['paid'];
            $t['order_id'] = $val['order_id'];
            $t['discount'] = $val['coupon_price'];

            $result['info'][] = $t;
        }

        $result['count'] = 10;
        $result['number'] = count($result['info']);
        $result['total_page'] = ceil($count / 10);


        $this->returnCode(0,'',$result,'success');
    }

    public function getOrderStatus(){
        $order_id = $_POST['order_id'];

        $order = D('Shop_order')->field(true)->where(array('real_orderid'=>$order_id))->find();
        $status = D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id']))->order('id DESC')->select();
        foreach ($status as $v){
            $data['status'] = $v['status'];
            $data['mark'] = D('Store')->getOrderStatusStr($v['status']);
            $data['name'] = $data['mark'];
            $data['createDate'] = date('Y-m-d H:i:s',$v['dateline']);

            $result[] = $data;
        }

        $this->returnCode(0,'info',$result,'success');
    }

    public function getOrderDetail(){
        $order_id = $_POST['order_id'];
        $order = D('Shop_order')->field(true)->where(array('real_orderid'=>$order_id))->find();

        if($order['paid'] == 0){
            $order_detail['statusname'] = L('_UNPAID_TXT_');
            $order_detail['payname'] = L('_UNPAID_TXT_');
        }else{
            $order_detail['statusname'] = D('Store')->getOrderStatusName($order['status']);
            $order_detail['pay_type'] = $order['pay_type'];
            //$order_detail['payname'] = $order['pay_type'] == 'moneris' ? 'Paid Online' : 'Cash';
            if($order['pay_type'] == 'moneris'){
                $order_detail['payname'] = 'Paid Online';
            }elseif ($order['pay_type'] == ''){
                $order_detail['payname'] = 'Pay by balance';
            }else{
                $order_detail['payname'] = 'Cash';
            }
        }

        $order_detail['add_time'] = date('Y-m-d H:i:s',$order['create_time']);
        //$order_detail['payname'] = $order_detail['paymodel'] = D('Store')->getPayTypeName($order['pay_type']);
        $order_detail['packing_fee'] = $order['packing_charge'];
        $order_detail['ship_fee'] = $order['freight_charge'];
        $order_detail['tip_fee'] = $order['tip_charge'];
        $order_detail['food_amount'] = $order['goods_price'];
        $order_detail['order_id'] = $order_id;
        $order_detail['expect_time'] = date('Y-m-d H:i:s',$order['expect_use_time']);
        $order_detail['site_id'] = $order['store_id'];
        $order_detail['uname'] = $order['username'];
        $order_detail['phone'] = $order['userphone'];
        $order_detail['address2'] = $order['address'];
        $order_detail['address1'] = "";

        $order_detail['promotion_discount'] = "0";
        $order_detail['discount'] = $order['coupon_price'];

        $store = D('Store')->get_store_by_id($order['store_id']);
        $order_detail['site_name'] = $store['site_name'];
        $order_detail['tel'] = $store['phone'];

        $result['order'] = $order_detail;

        $tax_price = 0;
        $deposit_price = 0;

        $order_good = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
        foreach($order_good as $v){
            $goods['fname'] = $v['name'];
            $goods['quantity'] = $v['num'];
            $goods['price'] = $v['price'];

            $spec_desc = '';
            $spec_ids = explode('_',$v['spec_id']);
            foreach ($spec_ids as $vv){
                $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                $spec_desc = $spec_desc == '' ? lang_substr($spec['name'],$lang) : $spec_desc.','.lang_substr($spec['name'],$lang);
            }

            if($v['pro_id'] != '')
                $pro_ids = explode('|',$v['pro_id']);
            else
                $pro_ids = array();

            foreach ($pro_ids as $vv){
                $ids = explode(',',$vv);
                $proId = $ids[0];
                $sId = $ids[1];

                $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                $nameList = explode(',',$pro['val']);
                $name = lang_substr($nameList[$sId],$lang);

                $spec_desc = $spec_desc == '' ? $name : $spec_desc.','.$name;
            }
            $goods['spec_desc'] = $spec_desc;

            $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['goods_id']))->find();
            $tax_price += $v['price'] * $good['tax_num']/100 * $v['num'];
            $deposit_price += $good['deposit_price']*$v['num'];

            $food[] = $goods;
        }

        $result['food'] = $food;
        $tax_price = $tax_price + ($order['packing_charge'] + $order['freight_charge'])*$store['tax_num']/100;
        $result['order']['tax_price'] = $tax_price;
        $result['order']['deposit_price'] = $deposit_price;
        $result['order']['subtotal'] = $order['price'];

        $delivery = D('Deliver_supply')->field(true)->where(array('order_id'=>$order['order_id']))->find();
        if($delivery) {
            $deliver = D('Deliver_user')->field(true)->where(array('uid'=>$delivery['uid']))->find();
            $result['order']['empname'] = $deliver['name'].'('.$deliver['phone'].')';
            $result['order']['deliver_lng'] = $deliver['lng'];
            $result['order']['deliver_lat'] = $deliver['lat'];
        }

        $this->returnCode(0,'',$result,'success');
    }

    public function getGoodsSpec(){
        $uid = $_POST['uid'];
        $fid = $_POST['fid'];

        $database_shop_goods = D('Shop_goods');
        $now_goods = $database_shop_goods->get_goods_by_id($fid);
        //modify garfunkel 判断语言
        $now_goods['name'] = lang_substr($now_goods['name'],C('DEFAULT_LANG'));
        $now_goods['unit'] = lang_substr($now_goods['unit'],C('DEFAULT_LANG'));
        foreach ($now_goods['properties_list'] as $k => $v){
            $now_goods['properties_list'][$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            foreach ($v['val'] as $kk => $vv){
                $now_goods['properties_list'][$k]['val'][$kk] = lang_substr($vv,C('DEFAULT_LANG'));
            }
            $result['properties_list'][] = $now_goods['properties_list'][$k];
        }

        foreach($now_goods['spec_list'] as $k => $v){
            $now_goods['spec_list'][$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            foreach($v['list'] as $kk => $vv){
                $now_goods['spec_list'][$k]['list'][$kk]['name'] = lang_substr($vv['name'],C('DEFAULT_LANG'));
            }
        }

        $result['spec_list'] = $now_goods['spec_list'];

        $result['list'] = $now_goods['list'];

        $result['cart'] = D('Cart')->field(true)->where(array("uid"=>$uid,"fid"=>$fid))->order('time desc')->select();

        $this->returnCode(0,'',$result,'success');
    }

    public function credit_pay(){
        import('@.ORG.pay.MonerisPay');
        $moneris_pay = new MonerisPay();
        //app 支付标识
        $_POST['rvarwap'] = 2;
        $resp = $moneris_pay->payment($_POST,$_POST['uid']);
        if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
            //$order = explode("_",$_POST['order_id']);
            //$order_id = $order[1];
            //$url =U("Wap/Shop/status",array('order_id'=>$order_id));

            $this->returnCode(0,'info',array(),'success');
            //$this->success(L('_PAYMENT_SUCCESS_'),$url,true);
        }else{
            $this->returnCode(1,'info',array(),$resp['message']);
//            $this->error($resp['message'],'',true);
        }
    }

    public function ToPay(){
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];
        $price = $_POST['price'];
        $tip = $_POST['tip'];

        if($_POST['pay_type'] == 0){//线下支付 直接进入支付流程
            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save(array('tip_charge'=>$tip));
            $order_param['order_id'] = $order_id;
            $order_param['order_from'] = 0;
            $order_param['order_type'] = 'shop';
            $order_param['pay_time'] = date();
            $order_param['pay_type'] = 'Cash';
            $order_param['is_mobile'] = 2;
            $order_param['is_own'] = 0;
            $order_param['third_id'] = 0;

            D('Shop_order')->after_pay($order_param);
        }elseif($_POST['pay_type'] == 4){//余额支付
            //账户余额
            $userInfo = D('User')->get_user($uid);
            $now_money = round($userInfo['now_money'],2);

            $data['balance_pay'] = $price + $tip;
            if($now_money >= $data['balance_pay']){
                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($data);

                $order_param = array(
                    'order_id' => $order_id,
                    'pay_type' => '',
                    'order_type'=> 'shop',
                    'third_id' => '',
                    'is_mobile' => 2,
                    'pay_money' => 0,
                    'order_total_money' => $price + $tip,
                    'balance_pay' => $price + $tip,
                    'merchant_balance' => 0,
                    'is_own'	=> 0
                );

                D('Shop_order')->after_pay($order_param);
            }else {
                $this->returnCode(1, 'info', array(), L('_B_MY_NOMONEY_'));
            }
        }else if($_POST['pay_type'] == 1){//支付宝
            $price = $price + $tip;
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price);
            if($result['resCode'] == 'SUCCESS'){
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),'fail');
            }
        }else if($_POST['pay_type'] == 2){//微信支付
            $price = $price + $tip;
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price);
            if($result['resCode'] == 'SUCCESS'){
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),'fail');
            }
        }

        $this->returnCode(0,'info',array(),'success');
    }

    public function user_card_default(){
        $uid = $_POST['uid'];
        $card = D('User_card')->getCardListByUid($uid);

        if($card)
            $this->returnCode(0,'info',$card[0],'success');
        else
            $this->returnCode(0,'info',array('id'=>'0'),'success');
    }

    public function getUserCard(){
        $uid = $_POST['uid'];
        $card_list = D('User_card')->getCardListByUid($uid);
        if(!$card_list)
            $card_list = array();
        else{
            foreach ($card_list as $k=>$v){
                $card_list[$k]['expiry'] = transYM($v['expiry']);
            }
        }

        $this->returnCode(0,'info',$card_list,'success');
    }

    public function setDefaultCard(){
        $uid = $_POST['uid'];
        $card_id = $_POST['card_id'];

        D('User_card')->clearIsDefaultByUid($uid);

        D('User_card')->field(true)->where(array('id'=>$card_id))->save(array('is_default'=>1));

        $card_list = D('User_card')->getCardListByUid($uid);

        $this->returnCode(0,'info',$card_list,'success');
    }

    public function edit_card(){
        $uid = $_POST['uid'];
        $data['name'] = $_POST['name'];
        $data['card_num'] = $_POST['card_num'];
        $data['expiry'] = transYM($_POST['expiry']);

        //如果 is_default 存在，清空之前的default
        if($_POST['is_default']){
            D('User_card')->clearIsDefaultByUid($uid);
        }
        $data['is_default'] = $_POST['is_default'] ? $_POST['is_default'] : 0;

        $data['uid'] = $uid;

        if($_POST['card_id'] && $_POST['card_id'] != ''){
            $data['id'] = $_POST['card_id'];
            D('User_card')->field(true)->where(array('id'=>$data['id']))->save($data);
            $this->returnCode(0,'info',array(),'success');
        }else {
            $isC = D('User_card')->getCardByUserAndNum($data['uid'], $data['card_num']);
            if ($isC) {
//                $this->error(L('_CARD_EXIST_'));
                $this->returnCode(1,'info',array(),'fail');
            } else {
                $data['create_time'] = date("Y-m-d H:i:s");
                D('User_card')->field(true)->add($data);
                $this->returnCode(0,'info',array(),'success');
            }
        }
    }

    public function delCard(){
        $uid = $_POST['uid'];

        if($_POST['card_id']){
            D('User_card')->field(true)->where(array('id'=>$_POST['card_id']))->delete();
            //$this->success(L('_OPERATION_SUCCESS_'));
        }

        $card_list = D('User_card')->getCardListByUid($uid);
        if(!$card_list) $card_list = array();

        $this->returnCode(0,'info',$card_list,'success');
    }

    public function getCouponByUser(){
        $uid = $_POST['uid'];
        $coupon_list = D('System_coupon')->get_user_coupon_list($uid);

        $tmp = array();
        foreach ($coupon_list as $key => $v) {
            if(!$v['is_use']){
                $coupon = $this->arrange_coupon($v);
                $tmp[] = $coupon;
            }
//            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
//                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
//            } else {
//                $tmp[$v['is_use']][$v['coupon_id']] = $v;
//                $mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
//                $tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
//                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
//            }

        }
        $this->returnCode(0,'info',$tmp,'success');
    }

    public function arrange_coupon($coupon){
        $coupon['name'] = lang_substr($coupon['name'],C('DEFAULT_LANG'));
        $coupon['des'] = lang_substr($coupon['des'],C('DEFAULT_LANG'));

        $data['name'] = $coupon['name'];
        $data['desc'] = $coupon['des'];
        $data['rowiID'] = $coupon['id'];
        $data['limitMoney'] = $coupon['order_money'];
        $data['money'] = $coupon['discount'];
        $data['beginDate'] = date('Y.m.d',$coupon['start_time']);
        $data['endDate'] = date('Y.m.d',$coupon['end_time']);
        $data['type'] = "2";

        if($coupon['is_use'] == 0)
            $data['status'] = $coupon['is_use'];
        elseif ($coupon['is_use'] == 1)
            $data['status'] = "2";
        else
            $data['status'] = "3";


        return $data;
    }
    //获取订单可使用的优惠券
    public function getCanCoupon(){
        $uid = $_POST['uid'];
        //订单金额
        $amount = $_POST['amount'];
//        $today = time();

//        $sql = 'select c.coupon_id,h.id,c.discount,c.order_money from '.C('DB_PREFIX').'system_coupon_hadpull as h left join '.C('DB_PREFIX').'system_coupon as c on h.coupon_id = c.coupon_id';
//        $sql .= ' where h.uid = '.$uid.' and h.is_use = 0 and c.start_time <='.$today.' and c.end_time >='.$today.' and c.order_money <='.$amount;
//        $sql .= ' order by c.discount desc,c.end_time asc';
//
//        $model = new Model();
//        $coupon_list = $model->query($sql);
        $coupon_list = D('System_coupon')->get_user_coupon_list($uid);

        $tmp = array();
        foreach ($coupon_list as $key => $v) {
            if (!$v['is_use'] && $v['order_money']<=$amount) {
                $coupon = $this->arrange_coupon($v);
                $tmp[] = $coupon;
            }
        }
        return $tmp;
//        $this->returnCode(0,'info',$tmp,'success');
    }

    public function orderRefund(){
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];

        $now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $uid));
        if(empty($now_order)){
            $this->returnCode(1,'info',array(),L('_B_MY_NOORDER_'));
        }
        $store_id = $now_order['store_id'];
        //$mer_id = $now_order['mer_id'];
        if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERDEALING_'));
        }

        $data_shop_order['cancel_type'] = 5;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
        if ($now_order['pay_type'] == 'offline' || $now_order['pay_type'] == 'Cash') {
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
            $data_shop_order['status'] = 4;
            if (D('Shop_order')->data($data_shop_order)->save()) {
                $return = $this->shop_refund_detail($now_order, $store_id);
                if ($return['error_code']) {
                    $this->returnCode(1,'info',array(),$result['msg']);
                }
            } else {
                $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE_'));
            }
        }else{
            if($now_order['pay_type'] == 'moneris'){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
                $resp = $moneris_pay->refund($uid,$now_order['order_id']);
//                var_dump($resp);die();
                if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->returnCode(1,'info',array(),$resp['message']);
                }
            }

            $return = $this->shop_refund_detail($now_order, $store_id);
            if ($return['error_code']) {
                $this->returnCode(1,'info',array(),$return['msg']);
            }

//            if (empty($now_order['pay_type'])) {
//                $data_shop_order['order_id'] = $now_order['order_id'];
//                $data_shop_order['status'] = 4;
//                $data_shop_order['last_time'] = time();
//                D('Shop_order')->data($data_shop_order)->save();
//            }
//            if(empty($go_refund_param['msg'])){
//                $go_refund_param['msg'] .= L('_B_MY_ORDERCANCELLEDACCESS_');
//            }
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
        }
        $this->returnCode(0,'info',array(),'success');
    }

    private function shop_refund_detail($now_order, $store_id)
    {
        $order_id  = $now_order['order_id'];

        $mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();

        //如果使用了积分 2016-1-15
        if ($now_order['score_used_count'] != 0) {
            $result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_') . $mer_store['name'] . '(' . $order_id . ') '.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
            $param = array('refund_time' => time());
            if ($result['error_code']) {
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $result['error_code'] || $data_shop_order['status'] = 4;
            D('Shop_order')->data($data_shop_order)->save();
            if ($result['error_code']) {
                return $result;
            }
            $go_refund_param['msg'] .= ' '.$result['msg'];
        }

        //平台余额退款
        if ($now_order['balance_pay'] != '0.00') {
            $add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],L('_B_MY_REFUND_') . $mer_store['name'] . '(' . $order_id . ') 增加余额');

            $param = array('refund_time' => time());
            if($result['error_code']){
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }

            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $result['error_code'] || $data_shop_order['status'] = 4;
            D('Shop_order')->data($data_shop_order)->save();
            if ($result['error_code']) {
                return $result;
            }
            $go_refund_param['msg'] .= ' 平台余额退款成功';
        }
        //商家会员卡余额退款
        if ($now_order['merchant_balance'] != '0.00'||$now_order['card_give_money']!='0.00') {
            //$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],L('_B_MY_REFUND_') . $mer_store['name'] . '(' . $order_id . ')  增加余额');
            $result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');

            $param = array('refund_time' => time());
            if ($result['error_code']) {
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }

            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $result['error_code'] || $data_shop_order['status'] = 4;
            D('Shop_order')->data($data_shop_order)->save();
            if ($result['error_code']) {
                return $result;
            }
            $go_refund_param['msg'] .= $result['msg'];
        }

        //退款打印
        $msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
        $op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
        $op->printit($this->mer_id, $store_id, $msg, 3);

        $str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
        foreach ($str_format as $print_id => $print_msg) {
            $print_id && $op->printit($this->mer_id, $store_id, $print_msg, 3, $print_id);
        }

        //退款时销量回滚
        if (($now_order['paid'] == 1 || $now_order['reduce_stock_type'] == 1) && $now_order['is_rollback'] == 0) {
            $goods_obj = D("Shop_goods");
            foreach ($now_order['info'] as $menu) {
                $goods_obj->update_stock($menu, 1);//修改库存
            }
            D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_rollback' => 1));
        }
        D("Merchant_store_shop")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);
        //退款时销量回滚

        $go_refund_param['error_code'] = false;
        return $go_refund_param;
    }

    public  function coupon_code(){
        $code = $_POST['code'];
        $uid = $_POST['uid'];

        $coupon = D('System_coupon')->field(true)->where(array('notice'=>$code))->find();
        $cid = $coupon['coupon_id'];

        if($cid){
            $l_id = D('System_coupon_hadpull')->field(true)->where(array('uid'=>$uid,'coupon_id'=>$cid))->find();

            if($l_id == null)
                $result = D('System_coupon')->had_pull($cid,$uid);
            else
                $this->returnCode(1,'info',array(),L('_AL_EXCHANGE_CODE_'));
        }else{
            $this->returnCode(1,'info',array(),L('_NOT_EXCHANGE_CODE_'));
        }

        $this->returnCode(0,'info',$result,'success');
    }
    //未支付 取消订单
    public function cancelOrder(){
        $uid = $_POST['uid'];
        $id = $_POST['order_id'];

        if ($order = M('Shop_order')->where(array('order_id' => $id, 'uid' => $uid))->find()) {
// 			if ($order['status'] != 0 ) $this->error_tips('商家已经处理了此订单，现在不能取消了！');
            if ($order['paid'] == 1 )
                $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE1_'));

// 			D("Merchant_store_meal")->where(array('store_id' => $order['store_id']))->setDec('sale_count', 1);
            /* 粉丝行为分析 */
//            $this->behavior(array('mer_id' => $order['mer_id'], 'biz_id' => $order['store_id']));

            M('Shop_order')->where(array('order_id' => $id, 'uid' => $uid))->save(array('status' => 5, 'is_rollback' => 1));//取消未支付的订单
            D('Shop_order_log')->add_log(array('order_id' => $id, 'status' => 10));

            if (($order['paid'] == 1 || $order['reduce_stock_type'] == 1) && $order['is_rollback'] == 0) {
                $details = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
                $goods_db = D("Shop_goods");
                foreach ($details as $menu) {
                    $goods_db->update_stock($menu, 1);//修改库存
                }
            }

            $this->returnCode(0,'info',array(),L('_B_MY_CANCELLACCESS1_'));
        } else {
            $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE1_'));
        }
    }

    //已支付 取消订单
    public function delOrder()
    {
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];

        $now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $uid));
        if(empty($now_order)){
            $this->returnCode(1,'info',array(),L('_B_MY_NOORDER_'));
        }
        $store_id = $now_order['store_id'];
        $this->mer_id = $now_order['mer_id'];
        if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERDEALING_'));
        }
        if (empty($now_order['paid'])) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERNOPAY_'));
        }
        if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERMUSTNOPAID_'));
        } elseif ($now_order['status'] > 3 && !($now_order['paid'] == 1 && $now_order['status'] == 5)) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERMUSTNOPAID_'));
        }
        $mer_store = D('Merchant_store')->where(array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id']))->find();
        $my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();

        //线下支付退款
        $data_shop_order['cancel_type'] = 5;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
        if ($now_order['pay_type'] == 'offline' || $now_order['pay_type'] == 'Cash') {
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
            $data_shop_order['status'] = 4;
            if (D('Shop_order')->data($data_shop_order)->save()) {
                $return = $this->shop_refund_detail($now_order, $store_id);
                if ($return['error_code']) {
                    $this->returnCode(1,'info',array(),$result['msg']);
                } else {
                    //add garfunkel 取消订单成功 发送消息
                    if (C('config.sms_shop_cancel_order') == 1 || C('config.sms_shop_cancel_order') == 3) {
                        $sms_data['uid'] = $now_order['uid'];
                        $sms_data['mobile'] = $now_order['userphone'] ? $now_order['userphone'] : $my_user['phone'];
                        $sms_data['sendto'] = 'user';
                        $sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
                        $sms_data['params'] = [
                            $order_id,
                            date('Y-m-d H:i:s'),
                            lang_substr($mer_store['name'],'en-us')
                        ];
                        $sms_data['tplid'] = 171187;
                        Sms::sendSms2($sms_data);
                    }
                    if (C('config.sms_shop_cancel_order') == 2 || C('config.sms_shop_cancel_order') == 3) {
                        $sms_data['uid'] = 0;
                        $sms_data['mobile'] = $mer_store['phone'];
                        $sms_data['sendto'] = 'merchant';
                        $sms_data['content'] = '顾客' . $now_order['username'] . '的预定订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
                        $sms_data['params'] = [
                            $now_order['username'],
                            $order_id,
                            date('Y-m-d H:i:s')
                        ];
                        $sms_data['tplid'] = 169151;
                        //Sms::sendSms2($sms_data);

                        //add garfunkel 添加语音
                        $txt = "This is a important message from island life , the customer has canceled the last order.";
                        Sms::send_voice_message($sms_data['mobile'],$txt);
                    }
                    $this->returnCode(0,'info',array(),L('_B_MY_USEOFFLINECHANGEREFUND_'));
                }
            } else {
                $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE_'));
            }
        }else{
            if($now_order['pay_type'] == 'moneris'){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
                $resp = $moneris_pay->refund($uid,$now_order['order_id']);
//                var_dump($resp);die();
                if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->returnCode(1,'info',array(),$resp['message']);
                }
            }else if ($now_order['payment_money'] != '0.00') {
                if ($now_order['is_own']) {
                    $pay_method = array();
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                    $now_merchant = D('Merchant')->get_info($now_order['mer_id']);
                    if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                        $pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
                        $pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
                        $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                        $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                        $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                        $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
                        $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
                        $pay_method['weixin']['config']['is_own'] = 1 ;
                    }
                } else {
                    $pay_method = D('Config')->get_pay_method(0,0,1);
                }

                if (empty($pay_method)) {
                    $this->returnCode(1,'info',array(),L('_B_MY_NOPAIMENTMETHOD_'));
                }
                if (empty($pay_method[$now_order['pay_type']])) {
                    $this->returnCode(1,'info',array(),L('_B_MY_CHANGEPAIMENT_'));
                }

                $pay_class_name = ucfirst($now_order['pay_type']);
                $import_result = import('@.ORG.pay.'.$pay_class_name);
                if(empty($import_result)){
                    $this->returnCode(1,'info',array(),L('_B_MY_THISPAIMENTNOTOPEN_'));
                }
                D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_refund' => 1));
                $now_order['order_type'] = 'shop';
                $now_order['order_id'] = $now_order['orderid'];
                if($now_order['is_mobile_pay']==3){
                    $pay_method[$now_order['pay_type']]['config'] =array(
                        'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
                        'pay_weixin_key'=>$this->config['pay_wxapp_key'],
                        'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
                        'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
                    );
                }
                $pay_class = new $pay_class_name($now_order, $now_order['payment_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, 1);
                $go_refund_param = $pay_class->refund();

                $now_order['order_id'] = $order_id;
                $data_shop_order['order_id'] = $order_id;
                $data_shop_order['refund_detail'] = serialize($go_refund_param['refund_param']);
                if (empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok') {
                    $data_shop_order['status'] = 4;
                }
                $data_shop_order['last_time'] = time();
                D('Shop_order')->data($data_shop_order)->save();
                if($data_shop_order['status'] != 4){
                    $this->returnCode(1,'info',array(),$go_refund_param['msg']);
                }else{
                    $go_refund_param['msg'] ="在线支付退款成功 ";
                }
            }


            $return = $this->shop_refund_detail($now_order, $store_id);
            if ($return['error_code']) {
                $this->returnCode(1,'info',array(),$return['msg']);
            } else {
                $go_refund_param['msg'] .= $return['msg'];
            }

            if (empty($now_order['pay_type'])) {
                $data_shop_order['order_id'] = $now_order['order_id'];
                $data_shop_order['status'] = 4;
                $data_shop_order['last_time'] = time();
                D('Shop_order')->data($data_shop_order)->save();
                $go_refund_param['msg'] .= L('_B_MY_ORDERCANCELLEDACCESS_');
            }
            if(empty($go_refund_param['msg'])){
                $go_refund_param['msg'] .= L('_B_MY_ORDERCANCELLEDACCESS_');
            }
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
            $this->returnCode(0,'info',array(),$go_refund_param['msg']);
        }
    }

    public function add_comment(){
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];
        $comment = $_POST['comment'];
        $score = $_POST['score'];
        $score_store = $_POST['score1'];
        $score_deliver = $_POST['score2'];
        $is_late = $_POST['righttime'] == 1 ? 0 : 1;

        $now_order = D('Shop_order')->get_order_detail(array('uid' => $uid, 'order_id' => $order_id));

        if (empty($now_order)) {
            $this->returnCode(1,'info',array(),L('_B_MY_NOORDER_'));
        }
        if (empty($now_order['paid'])) {
            $this->returnCode(1,'info',array(),L('_B_MY_NOTCONSUMENOCOMMENT_'));
        }
        if ($now_order['status'] == 3) {
            $this->returnCode(1,'info',array(),L('_B_MY_HAVECOMMENTD_'));
        }

        $goodsids = array();
        $goods_ids = array();
        $goods = '';
        $pre = '';
        if (isset($now_order['info'])) {
            foreach ($now_order['info'] as $row) {
                if (!in_array($row['goods_id'], $goodsids)) {
                    $goodsids[] = $row['goods_id'];
                    if (in_array($row['goods_id'], $goods_ids)) {
                        $goods .= $pre . $row['name'];
                        $pre = '#@#';
                    }
                }
            }
        }
        $database_reply = D('Reply');

        $data_reply['parent_id'] = $now_order['store_id'];
        $data_reply['store_id'] = $now_order['store_id'];
        $data_reply['mer_id'] = $now_order['mer_id'];
        $data_reply['score'] = $score;
        $data_reply['order_type'] = 3;
        $data_reply['order_id'] = intval($now_order['order_id']);
        $data_reply['anonymous'] = 1;
        $data_reply['comment'] = $comment;
        $data_reply['uid'] = $uid;
        $data_reply['pic'] = '';
        $data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
        $data_reply['add_ip'] = get_client_ip(1);
        $data_reply['goods'] = $goods ? $goods : "";
        $data_reply['score_store'] = $score_store;
        $data_reply['score_deliver'] = $score_deliver;
        $data_reply['is_late'] = $is_late;

        $data_reply['merchant_reply_content'] = "";
        $data_reply['merchant_reply_time'] = 0;

// 		echo "<pre/>";
// 		print_r($data_reply);die;
        if ($database_reply->data($data_reply)->add()) {
            D('Merchant_store')->setInc_shop_reply($now_order['store_id'], $score);
            D('Shop_order')->change_status($now_order['order_id'], 3);
            D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
            foreach ($goods_ids as $goods_id) {
                if (in_array($goods_id, $goodsids)) {
                    D('Shop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
                    D('Shop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->save(array('is_goods' => 1));
                }
            }

            if($this->config['feedback_score_add']>0){
                $user = D('User')->field(true)->where(array('uid'=>$uid))->find();

                D('User')->add_extra_score($this->$uid,$this->config['feedback_score_add'],$this->config['shop_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].$this->config['score_name']);
                D('Scroll_msg')->add_msg('feedback',$uid,L('_B_MY_USER_').$user['nickname'].date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).L('_B_MY_COMMENT_').$this->config['shop_alias_name'].L('_B_MY_GET_').$this->config['feedback_score_add'].$this->config['score_name']);
            }

            $this->returnCode(0,'info',array(),L('_B_MY_COMMENTACCESS_'));
//            exit(json_encode(array('status' => 1, 'msg' => L('_B_MY_COMMENTACCESS_'),  'url' => U('Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])))));
//            $this->success_tips(L('_B_MY_COMMENTACCESS_'), U('Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
        }
        $this->returnCode(1,'info',array(),L('_B_MY_COMMENTLOSE_'));
    }

    public function get_user_info(){
        $uid = $_POST['uid'];

        $userInfo = D('User')->get_user($uid);
        $info['now_money'] = round($userInfo['now_money'],2);

        $this->returnCode(0,'info',$info,'success');
    }

    public function getRechargeDis(){
        $config = D('Config')->get_config();
        $recharge_txt = $config['recharge_discount'];
        $recharge = explode(",",$recharge_txt);
        $recharge_list = array();
        foreach ($recharge as $v){
            $v_a = explode("|",$v);
            $recharge_list[$v_a[0]] = $v_a[1];
        }

        krsort($recharge_list);
        $this->returnCode(0,'info',$recharge_list,'success');
    }

    public function createRechargeOrder(){
        $uid = $_POST['uid'];
        $data_user_recharge_order['uid'] = $uid;
        $money = floatval($_POST['money']);
        if(empty($money) || $money > 10000){
            //$this->error('请输入有效的金额！最高不能超过1万元。');
            $this->returnCode(1,'info',array(),'请输入有效的金额！最高不能超过1万元。');
        }
        if($_POST['label']){
            $data_user_recharge_order['label'] = $_POST['label'];
        }
        $data_user_recharge_order['money'] = $money;
        // $data_user_recharge_order['order_name'] = '帐户余额在线充值';
        $data_user_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
        $data_user_recharge_order['is_mobile_pay'] = 2;

        if($order_id = D('User_recharge_order')->data($data_user_recharge_order)->add()){
            $this->returnCode(0,'info',$order_id,'success');
        }
    }

    public function testDistance(){
//        die('henhao');
        $from = $_GET['from'];
        $aim = $_GET['aim'];
        $url = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$from.'&destination='.$aim.'&key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&language=en';
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlGet($url);
        $result = json_decode($result);

        $this->returnCode(0,'info',$result,'success');
    }

    public function updateAssign(){
        $id = D('Deliver_assign')->check_assign();
        $this->deliver_e_call();
        //var_dump($id);
    }
    //监控配送员 是否发送 紧急召唤 所有送餐员的预计路程时间超过60分钟
    public function deliver_e_call(){
        //是否发送
        $is_send = false;
        $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>0))->order('uid asc')->select();

        //记录超出时间的配送员id
        $record_list = array();
        foreach ($user_list as $deliver){
            //获取所有配送员手中的订单
            $where = array('uid'=>$deliver['uid'],'status' => array(array('gt', 1), array('lt', 5)));
            $user_order = D('Deliver_supply')->field(true)->where($where)->select();
            //如果有配送员手中无订单则跳出
            if(count($user_order) == 0) break;
            //总时间
            $all_time = 0;
            foreach ($user_order as $order){
                if($order['status'] == 2 || $order['status'] == 3){
                    //出餐剩余时间
                    $chu_s = ($order['create_time'] + $order['dining_time']*60) - time();
                    $chu_s = $chu_s > 0 ? $chu_s : 0;
                    //计算到达店铺的时间
                    $to_shop = $this->getDistanceTime($deliver['lat'],$deliver['lng'],$order['from_lat'],$order['from_lnt']);

                    if($to_shop > $chu_s) $all_time += $to_shop;
                    else $all_time += $chu_s;
                }

                if($order['status'] == 4){
                    $to_user = $this->getDistanceTime($deliver['lat'],$deliver['lng'],$order['aim_lat'],$order['aim_lnt']);
                }else{
                    $to_user = $this->getDistanceTime($order['from_lat'],$order['from_lnt'],$order['aim_lat'],$order['aim_lnt']);
                }

                $all_time += $to_user;
                if($all_time/60 > 60){
                    $record_list[] = $deliver['uid'];
                }
            }
        }
        //全部配送人员都超过规定时间 发送紧急信息
        if(count($user_list) != 0 && count($user_list) == count($record_list)){
            $is_send = true;
        }

        //发送紧急召唤
        if($is_send){
            //三个小时内不重复发送
            $record_time = D('System_record')->field(true)->where(array('id'=>1))->find();
            if(time() > $record_time['record']){
                //获取发送记录
                $this->e_call();
                //存储发送紧急召唤的记录
                $data_time['record'] = time() + 3*60*60;
                D('System_record')->field(true)->where(array('id'=>1))->save($data_time);
            }
        }
    }
    public function getDistanceTime($from_lat,$from_lng,$aim_lat,$aim_lng){
        //获取两点之间的距离
        $distance = getDistance($from_lat,$from_lng,$aim_lat,$aim_lng);
        //获取预计到达时间
        $use_time = $distance / 100;
        //返回值为分钟
        return $use_time;
    }
    public function e_call(){
        $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>1))->order('uid asc')->select();
        foreach ($user_list as $deliver){
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $deliver['phone'];
            $sms_data['sendto'] = 'deliver';
            $sms_data['tplid'] = 247163;
            $sms_data['params'] = [];
            Sms::sendSms2($sms_data);
        }
    }

    public function userToken(){
        $uid = $_POST['uid'];
        $token = $_POST['token'];

        $user = D('User')->field(true)->where(array('uid'=> $uid))->find();

        if($user['device_id'] != $token){
            $data['device_id'] = $token;
            D('User')->field(true)->where(array('uid'=> $uid))->save($data);
        }
        $this->returnCode(0,'info',array(),'success');

    }

    public function AlipayTest(){
        $result = $this->loadModel()->WeixinAndAli(2,111,1);
        var_dump($result);
    }

    public function TestGoogle(){
        $device_id = 'cx-enHUoavg:APA91bFZbnqoVg4wtewEDjPQ6cAgZyZctCAK4-wlOEfpbC91xRouYjtJZon5GlbAUE6cMw4p4ft63mkanr6RgLJ0HHnO51gyw3y2Z6Be9plqKCTy2yI3hiaPtxl9vHwSUtxp7hmy1Kx3';
        $message = 'Your order (1133999) has been successfully canceled at 2019-01-07 07:10:01 at vicisland store, we are looking forward to seeing you again.';
        $result = Sms::sendMessageToGoogle($device_id,$message);
        var_dump($result);
    }
}