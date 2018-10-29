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
        }

        $this->returnCode(0,'info',$userInfo);
    }

    public function userLogin(){
        $userName = $_POST['userName'];
        $password = $_POST['password'];

        $userInfo = $this->loadModel()->getUserInfo($userName,$password);

        if($userInfo['msg'] != "")
            $code = 1;
        else
            $code = 0;

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

        $result = $this->loadModel()->reg_phone_pwd_vcode($phone,$vcode,$pwd);

        if ($result['error_code'])
            $code = 1;
        else
            $code = 0;

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

        $this->returnCode(0,'',$result,'success');
    }

    public function saveOrder(){
        $uid = $_POST['uid'];
        $cartList = $_POST['cart_list'];
        $note = $_POST['order_mark'];

        $adr_id = $_POST['addr_item_id'];

        $cart_array = json_decode(html_entity_decode($cartList),true);

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
        $order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['store']['pack_fee'];//订单总价  商品价格+打包费+配送费
        //$order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
        $order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['store']['pack_fee'];//实际要支付的价格
        //$order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['packing_charge'];//实际要支付的价格
        $order_data['price'] = $order_data['price'] * 1.05; //税费

        $order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情

        $order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'

        //订单来源
        $order_data['order_from'] = $_POST['cer_type'];
        //记录支付类型
        if($_POST['pay_type'] == 3){
            $order_data['pay_type'] = "moneris";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }else{
            $order_data['tip_charge'] = 0;
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
            $order_param['is_mobile'] = 1;
            $order_param['is_own'] = 0;
            $order_param['third_id'] = 0;

            D('Shop_order')->after_pay($order_param);
        }else if($_POST['pay_type'] == 3){//信用卡支付

        }

        if($order_id != 0)
            $this->returnCode(0,'main_id',$order_id,'success');
        else
            $this->returnCode(1,'info',array(),'success');
    }

    public function getOrderList(){
        $uid = $_POST['uid'];
        $status = $_POST['status'];
        $_GET['page'] = $_POST['page'];

        $where = "is_del=0 AND uid={$uid}";
        if ($status == 0) {
            $where .= " AND paid=0";
        } elseif ($status == 1) {
            $where .= " AND paid=1 AND status<2";
        } elseif ($status == 2) {
            $where .= " AND paid=1 AND status=2";
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
            $data['createDate'] = date('Y-m-d',$v['dateline']);

            $result[] = $data;
        }

        $this->returnCode(0,'info',$result,'success');
    }

    public function getOrderDetail(){
        $order_id = $_POST['order_id'];
        $order = D('Shop_order')->field(true)->where(array('real_orderid'=>$order_id))->find();

        $order_detail['statusname'] = D('Store')->getOrderStatusName($order['status']);
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
        $order_detail['pay_type'] = $order['pay_type'];
        $order_detail['payname'] = $order['pay_type'] == 'moneris' ? 'Paid Online' : 'Cash';


        $order_detail['promotion_discount'] = "0";
        $order_detail['discount'] = "0";

        $store = D('Store')->get_store_by_id($order['store_id']);
        $order_detail['site_name'] = $store['site_name'];
        $order_detail['tel'] = $store['phone'];

        $result['order'] = $order_detail;

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

            $food[] = $goods;
        }

        $result['food'] = $food;

        $delivery = D('Deliver_supply')->field(true)->where(array('order_id'=>$order['order_id']))->find();
        if($delivery) {
            $deliver = D('Deliver_user')->field(true)->where(array('uid'=>$delivery['uid']))->find();
            $result['order']['empname'] = $deliver['name'].'('.$deliver['phone'].')';
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
        $resp = $moneris_pay->payment($_POST,$_POST['uid']);
//        var_dump($resp);die();
        if($resp['responseCode'] < 50){
            $order = explode("_",$_POST['order_id']);
            $order_id = $order[1];
            $url =U("Wap/Shop/status",array('order_id'=>$order_id));

            $this->returnCode(0,'info',array(),'success');
            //$this->success(L('_PAYMENT_SUCCESS_'),$url,true);
        }else{
            $this->returnCode(1,'info',array(),'fail');
//            $this->error($resp['message'],'',true);
        }
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
        $data['expiry'] = $_POST['expiry'];

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

        $this->returnCode(0,'info',$card_list,'success');
    }

    public function getCouponByUser(){
        $uid = $_POST['uid'];
        $coupon_list = D('System_coupon')->get_user_coupon_list($uid);

        $tmp = array();
        foreach ($coupon_list as $key => $v) {
            $v['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            $v['des'] = lang_substr($v['des'],C('DEFAULT_LANG'));
            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
            } else {
                $tmp[$v['is_use']][$v['coupon_id']] = $v;
                $mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
                $tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
            }

        }
        $this->returnCode(0,'info',$tmp,'success');
    }
    //获取订单可使用的优惠券
    public function getCanCoupon(){
        $uid = $_POST['uid'];
        //订单金额
        $amount = $_POST['amount'];
        $today = time();

        $sql = 'select c.coupon_id,h.id,c.discount,c.order_money from '.C('DB_PREFIX').'system_coupon_hadpull as h left join '.C('DB_PREFIX').'system_coupon as c on h.coupon_id = c.coupon_id';
        $sql .= ' where h.uid = '.$uid.' and h.is_use = 0 and c.start_time <='.$today.' and c.end_time >='.$today.' and c.order_money <='.$amount;
        $sql .= ' order by c.discount desc,c.end_time asc';

        $model = new Model();
        $coupon_list = $model->query($sql);

        $this->returnCode(0,'info',$coupon_list,'success');
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
}