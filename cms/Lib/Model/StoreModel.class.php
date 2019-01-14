<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/8/24
 * Time: 12:07
 */

class StoreModel extends Model
{
    public function get_store_by_id($store_id)
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

        $store['site_id'] = $row['store_id'];

        $store['phone'] = $row['phone'];
        $store['isverify'] = $merchant['isverify'];
        $store['lng'] = $row['long'];
        $store['lat'] = $row['lat'];
        $store['store_theme'] = $row['store_theme'];
        $store['address'] = $row['adress'];

        $store['is_close'] = 1;
        $now_time = date('H:i:s');

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
//                $store['time'] = substr($row['open_4'], 0, -3) . '~' . substr($row['close_4'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_5'], 0, -3) . '~' . substr($row['close_5'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_6'], 0, -3) . '~' . substr($row['close_6'], 0, -3);
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
//                $store['time'] = substr($row['open_7'], 0, -3) . '~' . substr($row['close_7'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_8'], 0, -3) . '~' . substr($row['close_8'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_9'], 0, -3) . '~' . substr($row['close_9'], 0, -3);

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
//                $store['time'] = substr($row['open_10'], 0, -3) . '~' . substr($row['close_10'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_11'], 0, -3) . '~' . substr($row['close_11'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_12'], 0, -3) . '~' . substr($row['close_12'], 0, -3);
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
//                $store['time'] = substr($row['open_13'], 0, -3) . '~' . substr($row['close_13'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_14'], 0, -3) . '~' . substr($row['close_14'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_15'], 0, -3) . '~' . substr($row['close_15'], 0, -3);
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
//                $store['time'] = substr($row['open_16'], 0, -3) . '~' . substr($row['close_16'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_17'], 0, -3) . '~' . substr($row['close_17'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_18'], 0, -3) . '~' . substr($row['close_18'], 0, -3);
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
//                $store['time'] = substr($row['open_19'], 0, -3) . '~' . substr($row['close_19'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_20'], 0, -3) . '~' . substr($row['close_20'], 0, -3);
//                $store['time'] .= ';' . substr($row['open_21'], 0, -3) . '~' . substr($row['close_21'], 0, -3);
                break;
            default :
                $store['is_close'] = 1;
                $store['time']= '营业时间未知';
        }
        //garfunkel add
        $date = $date == 0 ? 7 : $date;
        $t_num = $date * 3;
        if($row['open_'.($t_num - 2)] != $row['close_'.($t_num - 2)])
            $store['time'] = substr($row['open_'.($t_num - 2)], 0, -3) . '~' . substr($row['close_'.($t_num - 2)], 0, -3);
        if($row['open_'.($t_num - 1)] != $row['close_'.($t_num - 1)])
            $store['time'] .= ';' . substr($row['open_'.($t_num - 1)], 0, -3) . '~' . substr($row['close_'.($t_num - 1)], 0, -3);
        if($row['open_'.$t_num] != $row['close_'.$t_num])
            $store['time'] .= ';' . substr($row['open_'.$t_num], 0, -3) . '~' . substr($row['close_'.$t_num], 0, -3);
        //end  @wangchuanyuan
        $delivers = array('平台配送', '商家配送', '客户自提', '平台配送或自提', '商家配送或自提', '快递配送');
        //modify garfunkel 增加语言判断
        $store['site_name'] = lang_substr($row['name'],C('DEFAULT_LANG'));

        $store['notice'] = $row['store_notice'];
        $store['txt_info'] = $row['txt_info'];
        $store['logo'] = isset($images[0]) ? $images[0] : '';
        $store['score'] = $row['score_mean'];
        $store['shop_sale'] = $row['sale_count'];
        $store['send'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
        $store['delivery_time'] = $row['send_time'];//配送时长
        $store['delivery_price'] = floatval($row['basic_price']);//起送价
        $store['tax_num'] = $row['tax_num'];
        $store['deposit_price'] = floatval($row['deposit_price']);

        //$store['deliver_name'] = $delivers[$row['deliver_type']];
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
        $store['shipfee'] = C('config.delivery_distance_1');
        //$store['delivery_money'] = floatval($store['delivery_money']);
// 		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
// 		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
        $store['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
        if (in_array($row['deliver_type'], array(2, 3, 4))) {
            $store['pick'] = 1;//是否支持自提
        } else {
            $store['pick'] = 0;//是否支持自提
        }
//        $store['pack_alias'] = $row['pack_alias'];//打包费别名
        //modify garfunkel
        $store['pack_fee'] = $row['pack_fee'];
        //$store['freight_alias'] = $row['freight_alias'];//运费别名
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
        $store['shopstatus'] = $store['is_close'] == 0 ? "1" : "0";

        return $store;
    }

    public function get_goods_group_by_storeId($storeId){
        $database_goods_sort = D('Shop_goods_sort');
        $condition_goods_sort['store_id'] = $storeId;
        $sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort_id` ASC')->select();

        return $sort_list;
    }

    public function get_goods_by_storeId($storeId){
        $data_goods = D('Shop_goods');
        $good_list = $data_goods ->field(true)->where(array('store_id' => $storeId, 'status' => 1))->order('goods_id ASC')->select();

        return $good_list;
    }

    public function arrange_group_for_goods($groupList){
        $returnList = array();
        foreach($groupList as $k=>$v){
            $returnList[$k]['id'] = $v['sort_id'];
            $returnList[$k]['sid'] = $v['store_id'];
            $returnList[$k]['title'] = lang_substr($v['sort_name'],C('DEFAULT_LANG'));
        }

        return $returnList;
    }

    public function arrange_goods_for_goods($goodList){
        $goods_image_class = new goods_image();
        $returnList = array();
        foreach($goodList as $k=>$v){
            $returnList[$k]['fid'] = $v['goods_id'];
            $returnList[$k]['group_id'] = $v['sort_id'];
            $returnList[$k]['sid'] = $v['store_id'];
            $returnList[$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            $returnList[$k]['price'] = $v['price'];
            $returnList[$k]['market_price'] = $v['old_price'];
            $returnList[$k]['desc'] = $v['des'];
            $returnList[$k]['stock'] = 10000;//库存
            $tmp_pic_arr = explode(';', $v['image']);
            if ($tmp_pic_arr[0] != '')
                $returnList[$k]['default_image'] = $goods_image_class->get_image_by_path($tmp_pic_arr[0])['image'];
            else
                $returnList[$k]['default_image'] = '';
            //$returnList[$k]['default_image'] = $v['image'];
            $returnList[$k]['sales'] = $v['sell_mouth'];
            //购物车使用
            $returnList[$k]['quantity'] = empty($v['quantity'])? "0" : $v['quantity'];
            $returnList[$k]['spec'] = empty($v['spec']) ? "" : $v['spec'];
            $returnList[$k]['proper'] = empty($v['proper']) ? "" : $v['proper'];
            //是否有规格及属性选择
            if($v['spec_value'] || $v['is_properties'])
                $returnList[$k]['has_format'] = true;
            else
                $returnList[$k]['has_format'] = false;

            $spec_desc = "";
            if($returnList[$k]['spec'] != ""){
                $spec_list = explode("_",$returnList[$k]['spec']);
                foreach($spec_list as $vv){
                    $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                    $spec_desc = $spec_desc == '' ? lang_substr($spec['name'],C('DEFAULT_LANG')) : $spec_desc.','.lang_substr($spec['name'],C('DEFAULT_LANG'));
                }
            }
            $returnList[$k]['spec_desc'] = $spec_desc;

            $proper_desc = "";
            if($returnList[$k]['proper'] != ""){
                $pro_list = explode("_",$returnList[$k]['proper']);
                foreach ($pro_list as $vv){
                    $ids = explode(',',$vv);
                    $proId = $ids[0];
                    $sId = $ids[1];

                    $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                    $nameList = explode(',',$pro['val']);
                    $name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));

                    $proper_desc = $proper_desc == '' ? $name : $proper_desc.','.$name;
                }
            }
            $returnList[$k]['proper_desc'] = $proper_desc;

            $returnList[$k]['deposit'] = $v['deposit_price'];
            $returnList[$k]['tax_num'] = $v['tax_num'];
        }

        return $returnList;
    }

    public function getUserInfo($phone,$password){
        $info = D('User')->checkin($phone,$password);
        $user = $info['user'];

        $userInfo = array();
//        $userInfo = array("uid"=>"1","uname"=>"garfunkel","password"=>"123456","login_type"=>"1",
//            "outsrc"=>"http://thirdqq.qlogo.cn/qqapp/1106028245/ED09815DE876D237105B7BF6F40DEFCA/100",
//            "openid"=>"ED09815DE876D237105B7BF6F40DEFCA"
//        );
        $userInfo['uid'] = $user['uid'];
        $userInfo['uname'] = $user['nickname'];
        $userInfo['password'] = $user['pwd'];
        $userInfo['outsrc'] = $user['avatar'];
        $userInfo['openid'] = $user['openid'];
        $userInfo['login_type'] = "1";

        $userInfo['msg'] = $info['error_code'] ? $info['msg'] : "";

        return $userInfo;
    }

    public function sendVerificationCode($phone){
        if(empty($phone)){
            return array('error_code' => true, 'msg' => 'No phone number');
        }
        $result = D('User')->check_phone($phone);
        if (!empty($result)){
            return $result;
        }

        $user_modifypwdDb = M('User_modifypwd');
        $chars = '0123456789';
        mt_srand((double)microtime() * 1000000 * getmypid());
        $vcode = "";

        while (strlen($vcode) < 6)
            $vcode .= substr($chars, (mt_rand() % strlen($chars)), 1);
        /*
        $content = '您的验证码是：'. $vcode . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
        Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $_POST['phone'], 'uid' => $this->now_user['uid'], 'type' => 'bindphone'));
        */
        /* add garfunkel new send sms*/
        $sms_data['uid'] = 0;
        $sms_data['mobile'] = $_POST['phone'];
        $sms_data['sendto'] = 'user';
        $sms_data['tplid'] = 169243;
        $sms_data['params'] = [
            $vcode
        ];
        Sms::sendSms2($sms_data);

        ///
        $addtime = time();
        $expiry = $addtime + 5 * 60; /*             * **五分钟有效期*** */
        $data = array('telphone' => $phone, 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
        $user_modifypwdDb->add($data);
        return array('error_code' => false, 'msg' => '');
    }

    public function reg_phone_pwd_vcode($phone,$vcode,$pwd){
        $verify_result = D('Smscodeverify')->verify($vcode, $phone);

        if($verify_result['error_code'])
            return $verify_result;

        $result = D('User')->checkreg($phone, $pwd);

        if (!empty($result['user'])) {
           $userInfo = $this->getUserInfo($phone,$pwd);

           return $userInfo;
        }else{
            return $result;
        }
    }

    public function get_comment_sid($sid,$type,$order_type = 3){
        if($type == 1)
            $tab = 'wrong';
        else if($type = 0)
            $tab = '';
        else
            $tab = 'good';

        $reply_return = D('Reply')->get_page_reply_list($sid,$order_type,$tab,'','',true);

        $result = array();
        $result['number'] = 20;

        $result['num']['score_best'] = 0;
        $result['num']['score_good'] = $reply_return['good_count'];
        $result['num']['score_bad'] = $reply_return['wrong_count'];
        $result['num']['all'] = $reply_return['all_count'];

        foreach ($reply_return['list'] as $k => $v) {
            $tarr = array();
            $tarr['itemid'] = $v['pigcms_id'];
            $tarr['sid'] = $v['store_id'];
            $tarr['pid'] = $v['uid'];
            $tarr['order_id'] = $v['order_id'];
            $tarr['name'] = $v['nickname'];
            $tarr['comment'] = $v['comment'];
            $tarr['addtime'] = $v['add_time_hi'];
            $tarr['score'] = $v['score'];
            $tarr['score1'] = $v['score'];
            $tarr['score2'] = $v['score'];
            $tarr['righttime'] = $v['anonymous'];
            $tarr['img_url'] = $v['avatar'];
            $tarr['reply'] = $v['merchant_reply_content'];
            $tarr['send_done_time'] = $v['add_time'];

            $result['list'][] = $tarr;
        }

        if(empty($reply_return['list']))
            $result['list'] = array();

        return $result;
    }

    public function getDefaultAdr($uid){
        $addressModle = D('User_adress');

        $address = $addressModle->field(true)->where(array('uid'=>$uid,'default'=>1))->find();
        if ($address == null)
            $address = $addressModle->field(true)->where(array('uid'=>$uid))->find();

        if($address != null)
            $result = $this->arrange_address($address);

        return $result;
    }

    public function getUserAdr($uid){
        $addressModle = D('User_adress');

        $adr = $addressModle->field(true)->where(array('uid'=>$uid))->order('`default` DESC')->select();

        foreach ($adr as $v){
            $result[] = $this->arrange_address($v);
        }

        return $result;
    }

    public function setDefaultAdr($uid,$aid){
        $addressModle = D('User_adress');

        return $addressModle->set_default($uid,$aid);
    }

    public function addUserAddress($data){
        $addressModle = D('User_adress');
        if($data['adress_id'] != 0){
            $condition_user_adress['adress_id'] = $data['adress_id'];
            $condition_user_adress['uid'] = $data['uid'];
            if(!empty($data['default'])){
                $condition_default_user_adress['uid'] = $data['uid'];
                $addressModle->where($condition_default_user_adress)->setField('default','0');
            }else{
                $data['default'] = 0;
            }

            return $addressModle->where($condition_user_adress)->data($data)->save();
        }else{
            if(!empty($_POST['default'])){
                $condition_default_user_adress['uid'] = $data['uid'];
                $addressModle->where($condition_default_user_adress)->setField('default','0');
            }else{
                $data['default'] = 0;
            }
            return $addressModle->data($data)->add();
        }
    }

    public function delUserAddress($uid,$aid){
        $addressModle = D('User_adress');

        return $addressModle->delete_adress($uid,$aid);
    }

    public function arrange_address($address){
        $data['rowID'] = $address['adress_id'];
        $data['zoneID'] = $address['city'];
        $data['zoneName'] = '';
        $data['areaName'] = $address['area'];
        $data['userName'] = $address['name'];
        $data['phoneNum'] = $address['phone'];
        $data['address'] = $address['adress'];
        $data['isDefault'] = $address['default'];
        $data['mapAddress'] = $address['adress'];
        $data['mapNumber'] = $address['zipcode'];
        $data['mapLat'] = $address['latitude'];
        $data['mapLng'] = $address['longitude'];
        $data['mapLocation'] = $address['detail'];

        return $data;
    }

    public function CalculationDeliveryFee($uid,$sid){
        $address = $this->getDefaultAdr($uid);
        $store = $this->get_store_by_id($sid);
        
        $distance = getDistance($address['mapLat'], $address['mapLng'], $store['lat'], $store['lng']);
        $distance = $distance / 1000;

        $deliveryCfg = [];
        $deliverys = D("Config")->get_gid_config(20);
        foreach($deliverys as $r){
            $deliveryCfg[$r['name']] = $r['value'];
        }

        if($distance < 5) {
            $delivery_fee = round($deliveryCfg['delivery_distance_1'], 2);
        }elseif($distance > 5 && $distance <= 8) {
            $delivery_fee = round($deliveryCfg['delivery_distance_2'], 2);
        }elseif($distance > 8 && $distance <= 10) {
            $delivery_fee = round($deliveryCfg['delivery_distance_3'], 2);
        }elseif($distance > 10 && $distance <= 15) {
            $delivery_fee = round($deliveryCfg['delivery_distance_4'], 2);
        }elseif($distance > 15 && $distance <= 20) {
            $delivery_fee = round($deliveryCfg['delivery_distance_5'], 2);
        }else{
            $delivery_fee = round($deliveryCfg['delivery_distance_more'], 2);
        }

        return $delivery_fee;
    }

    public function get_store_delivery_time($sid){
        $shop_store = D("Merchant_store_shop")->field(true)->where(array('store_id' => $sid))->find();

        $delivery_time = time() + $shop_store['send_time']*60;
        
        return $delivery_time;
    }

    public function getPayTypeName($type){
        $r_name = "";
        if($type == 1 || $type == '1'){
            $r_name = L('_ALIPAY_TXT_');
        }elseif($type == 2 || $type == '2'){
            $r_name = L('_WEICHAT_PAY_');
        }else{
            $r_name = L('_OFFLINE_PAY_');
        }

        return $r_name;
    }

    public function getOrderStatusStr($status){
        $status_list = array(
            L('_ORDER_STATUS_0_'),
            L('_ORDER_STATUS_1_'),
            L('_ORDER_STATUS_2_'),
            L('_ORDER_STATUS_3_'),
            L('_ORDER_STATUS_4_') ,
            L('_ORDER_STATUS_5_'),
            L('_ORDER_STATUS_6_'),
            L('_ORDER_STATUS_7_'),
            L('_ORDER_STATUS_8_'),
            L('_ORDER_STATUS_9_'),
            L('_ORDER_STATUS_10_'),
            L('_ORDER_STATUS_11_'),
            L('_ORDER_STATUS_12_'),
            L('_ORDER_STATUS_13_'),
            L('_ORDER_STATUS_14_'),
            L('_ORDER_STATUS_15_'),
            30 => L('_ORDER_STATUS_30_'));

        return $status_list[$status];
    }

    public function getOrderStatusName($status){
        $name_list = array(L('_B_PURE_MY_71_'),L('_B_PURE_MY_72_'),L('_B_PURE_MY_73_'),L('_B_PURE_MY_74_'),L('_B_PURE_MY_75_')
        ,L('_B_PURE_MY_76_'),L('_B_PURE_MY_77_'),L('_B_PURE_MY_78_'),L('_B_PURE_MY_79_'),L('_B_PURE_MY_80_'));

        return $name_list[$status];
    }

    public function WeixinAndAli($pay_type,$order_id,$price,$ip){
        //获取支付的相关配置数据
        $where = array('tab_id'=>'alipay','gid'=>7);
        $result = D('Config')->field(true)->where($where)->select();
        foreach ($result as $payData){
            if($payData['name'] == 'pay_alipay_name')
                $pay_id = $payData['value'];
            if($payData['name'] == 'pay_alipay_key')
                $pay_key = $payData['value'];
            if($payData['name'] == 'pay_alipay_pid')
                $pay_url = $payData['value'];
        }

        $channelId = '';
        //微信支付
        if($pay_type == 2) {
            $channelId = 'WX_APP';
        }
        //支付宝支付
        if($pay_type == 1)
            $channelId = 'ALIPAY_MOBILE';

        $data['mchId'] = $pay_id;
        $data['mchOrderNo'] = 'Tuttishop_'.$order_id.'_'.time();
        $data['channelId'] = $channelId;
        $data['currency'] = 'CAD';
        //单位分
        $data['amount'] = $price * 100;
        $data['clientIp'] = $ip;//real_ip();
        $data['device'] = 'APP';
        //支付结果回调URL
        $data['notifyUrl'] = 'https://www.tutti.app/notify';
        $data['subject'] = 'Tutti Order '.$order_id;
        $data['body'] = 'Tutti Order';
        $data['sign'] = $this->getSign($data,$pay_key);

        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlPost($pay_url,'params='.json_encode($data));
        file_put_contents("./test_log.txt",date("Y/m/d")."   ".date("h:i:sa")."   "."Request" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($data)."\r\n",FILE_APPEND);
        return $result;
    }

    private function getSign($params,$key){
        if(!empty($params)){
            $p =  ksort($params);
            if($p){
                $str = '';
                foreach ($params as $k=>$val){
                    if ($val != '')
                        $str .= $k .'=' . $val . '&';
                }
                $strs = rtrim($str, '&');
                //var_dump($strs);
                $sign = md5($strs.'&key='.$key);
                return strtoupper($sign);
            }
        }
    }
}