<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/8/31
 * Time: 16:08
 */

class CartModel extends Model
{
    public function add_cart($uid,$fid,$num=1,$spec = "",$proper = "",$dish_id = ""){
        $data['uid'] = $uid;
        $data['fid'] = $fid;

        $good = D('Shop_goods')->field(true)->where(array('goods_id' => $fid))->find();
        $data['sid'] = $good['store_id'];

        $data['num'] = $num;
        $data['spec'] = $spec;
        $data['proper'] = $proper;
        $data['dish_id'] = $dish_id;
        $data['time'] = date("Y-m-d H:i:s");

        $where = array('uid'=>$uid,'fid'=>$fid,'spec'=>$spec,'proper'=>$proper);
        if($dish_id != "")
            $where['dish_id'] = $dish_id;


        $item = $this->field(true)->where($where)->find();

        if(empty($item) && $data['num'] > 0){
            $id = $this->data($data)->add();
        }else{
            $item['num'] += $num;
            $item['time'] = $data['time'];
            if ($item['num']<=0)
                $this->field(true)->where(array('itemId'=>$item['itemId']))->delete();
            else
                $this->field(true)->data($item)->where(array('itemId'=>$item['itemId']))->save();
        }

        return true;
    }

    public function get_cart($uid,$storeId = 0){
        $where['uid'] = $uid;

        if($storeId != 0){
            $where['sid'] = $storeId;
        }

        $cartList = $this->field(true)->where($where)->order('itemId asc')->select();
        $result = array();

        $allnum = 0;
        $allmoney = 0;

        $resid = 0;

        $goodList = array();

        //garfunkel获取减免配送费的活动
        $eventList = D('New_event')->getEventList(1,3);
        $delivery_coupon = "";
        if(count($eventList) > 0) {
            foreach ($eventList as $event) {
                $delivery_coupon = D('New_event_coupon')->where(array('event_id' => $event['id']))->find();
            }
        }
        //获取用户默认地址
        $address = D('Store')->getDefaultAdr($uid);

        foreach($cartList as $v){
            $allnum += $v['num'];
            $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['fid']))->find();
            //获取规格价格
            $specData = D('Shop_goods')->format_spec_value($good['spec_value'], $good['goods_id'], $good['is_properties']);
            if($specData['list'] != "" && $v['spec'] != ""){
                foreach ($specData['list'] as $kk=>$vv){
                    if($v['spec'] == $kk){
                        $good['price'] = $vv['price'];
                    }
                }
            }

            //$allmoney += $good['price']*$v['num'];
            if ($resid != $good['store_id']){
                $store = D('Store')->get_store_by_id($good['store_id']);
                $resid = $good['store_id'];

                $store['free_delivery'] = 0;
                $store['event'] = array("use_price"=>"0","discount"=>"0","miles"=>0);
                if($address){
                    $distance = getDistance($store['lat'], $store['lng'], $address['mapLat'], $address['mapLng']);
                    if($delivery_coupon != "" && $delivery_coupon['limit_day']*1000 >= $distance){
                        $store['free_delivery'] = 1;
                        $t_event['use_price'] = $delivery_coupon['use_price'];
                        $t_event['discount'] = $delivery_coupon['discount'];
                        $t_event['miles'] = $delivery_coupon['limit_day']*1000;

                        $store['event'] = $t_event;

                        //$temp['delivery_money'] =  $temp['delivery_money'] - $delivery_coupon['discount'];
                    }
                }

                if(!in_array($store,$result['info']))
                    $result['info'][] = $store;
            }
            $good['quantity'] = $v['num'];
            $good['spec'] = $v['spec'];
            $good['proper'] = $v['proper'];
            $good['dish_id'] = $v['dish_id'];
            $goodList[] = $good;
        }
        $goodList = D('Store')->arrange_goods_for_goods($goodList);

        foreach($goodList as $v){
            //$good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['fid']))->find();
            foreach($result['info'] as $kk => $vv){
                if ($vv['site_id'] == $v['sid']){
                    $result['info'][$kk]['foods'][] = $v;
                }
            }
            $allmoney += $v['price']*$v['quantity'];
        }


        $result['allnum'] = $allnum;
        $result['allmoney'] = $allmoney;

        return $result;
    }

    public function getCartList($uid,$cartList){
        $list = array();
        $total_price = 0;
        $total_market_price = 0;
        $total_pay_price = 0;
        $tax_price = 0;
        $deposit_price = 0;

        foreach ($cartList as $v){
            $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['fid']))->find();
            $t_good['fname'] = lang_substr($good['name'],C('DEFAULT_LANG'));
            $t_good['stock'] = $v['stock'];

            //处理商品规格
            $t_good['spec'] = $v['spec'];
            $t_good['proper'] = $v['proper'];
            $specData = D('Shop_goods')->format_spec_value($good['spec_value'], $good['goods_id'], $good['is_properties']);
            if($specData['list'] != "" && $v['spec'] != ""){
                foreach ($specData['list'] as $kk=>$vv){
                    if($v['spec'] == $kk){
                        $good['price'] = $vv['price'];
                    }
                }
            }

            $spec_desc = "";
            if($t_good['spec'] != ""){
                $spec_list = explode("_",$t_good['spec']);
                foreach($spec_list as $vv){
                    $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                    $spec_desc = $spec_desc == '' ? lang_substr($spec['name'],C('DEFAULT_LANG')) : $spec_desc.','.lang_substr($spec['name'],C('DEFAULT_LANG'));
                }
            }
            $t_good['spec_desc'] = $spec_desc;

            $proper_desc = "";
            if($t_good['proper'] != ""){
                $pro_list = explode("_",$t_good['proper']);
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
            $t_good['proper_desc'] = $proper_desc;

            $t_good['dish_id'] = $v['dish_id'];
            $dish_desc = "";
            $add_price = 0;
            if($v['dish_id'] != "" && $v['dish_id'] != null){
                $dish_list = explode("|",$v['dish_id']);
                foreach($dish_list as $vv){
                    $one_dish = explode(",",$vv);
                    //0 dish_id 1 id 2 num 3 price
                    if($one_dish[3] > 0){
                        $add_price += $one_dish[3]*$one_dish[2];
                    }

                    $dish_vale = D('Side_dish_value')->where(array('id'=>$one_dish[1]))->find();
                    $dish_vale['name'] = lang_substr($dish_vale['name'],C('DEFAULT_LANG'));

                    $add_str = $one_dish[2] > 1 ? $dish_vale['name']."*".$one_dish[2] : $dish_vale['name'];

                    $dish_desc = $dish_desc == "" ? $add_str : $dish_desc.";".$add_str;
                }

                $good['price'] = $good['price'] + $add_price;
            }
            $t_good['dish_desc'] = $dish_desc;

            $t_good['attr'] = $spec_desc;
            $t_good['attr'].= $t_good['attr'] == "" ? $proper_desc : ";".$proper_desc;
            $t_good['attr'].= $t_good['attr'] == "" ? $dish_desc : ";".$dish_desc;

            $t_good['price'] = $good['price'];
            $t_good['tax_num'] = $good['tax_num'];
            $t_good['deposit_price'] = $good['deposit_price'];

            $total_price += $good['price']*$v['stock'];
            $total_pay_price += $good['price']*$v['stock'];
            $total_market_price += $good['old_price']*$v['stock'];

            $tax_price += $good['price']*$good['tax_num']/100*$v['stock'];
            $deposit_price += $good['deposit_price'] * $v['stock'];

            $list[] = $t_good;
        }

        $result['info'] = $list;

        $sid = $this->field(true)->where(array('uid'=>$uid,'fid'=>$cartList[0]['fid']))->find()['sid'];
        $store = D('Store')->get_store_by_id($sid);
        $result['packing_fee'] = $store['pack_fee'] ? $store['pack_fee'] :0;
        $total_pay_price += $store['pack_fee'];
        //获取配送费
        $delivey_fee = D('Store')->CalculationDeliveryFee($uid,$sid);
        //garfunkel获取减免配送费的活动
        $eventList = D('New_event')->getEventList(1,3);
        $delivery_coupon = "";
        if(count($eventList) > 0) {
            foreach ($eventList as $event) {
                $delivery_coupon = D('New_event_coupon')->where(array('event_id' => $event['id']))->find();
            }
        }

        $address = D('Store')->getDefaultAdr($uid);
        $store = D('Store')->get_store_by_id($sid);

        $distance = getDistance($store['lat'], $store['lng'], $address['mapLat'], $address['mapLng']);
        $store['free_delivery'] = 0;
        $store['event'] = "";

        $result['is_free_delivery'] = 0;
        $result['delivery_discount'] = 0;
        $result['delivery_free_type'] = 1;

        if($delivery_coupon != "" && $delivery_coupon['limit_day']*1000 >= $distance){
            $store['free_delivery'] = 1;
            $t_event['use_price'] = $delivery_coupon['use_price'];
            $t_event['discount'] = $delivery_coupon['discount'];
            $t_event['miles'] = $delivery_coupon['limit_day']*1000;

            $store['event'] = $t_event;

            if($total_price >= $delivery_coupon['use_price']){
                //$delivey_fee = getDeliveryFee($store['lat'], $store['lng'], $address['mapLat'], $address['mapLng']);
                $result['is_free_delivery'] = 1;
                if($delivey_fee < $delivery_coupon['discount'])
                    $result['delivery_discount'] = $delivey_fee;
                else
                    $result['delivery_discount'] = $delivery_coupon['discount'];

                $result['delivery_free_type'] = $delivery_coupon['type'];
            }

            //$temp['delivery_money'] =  $temp['delivery_money'] - $delivery_coupon['discount'];
        }
        ///////-garfunkel-减免配送费////////

        ///////-garfunkel-店铺满减////////
        $result['merchant_reduce'] = 0;
        $result['merchant_reduce_type'] = 0;
        $eventList = D('New_event')->getEventList(1,4);
        $store_coupon = "";
        if(count($eventList) > 0) {
            $store_coupon = D('New_event_coupon')->where(array('event_id' => $eventList[0]['id'],'limit_day'=>$sid))->order('use_price asc')->select();
        }
        if(count($store_coupon) > 0) {
            foreach ($store_coupon as $c) {
                if ($total_price >= $c['use_price']) {
                    $result['merchant_reduce'] = $c['discount'];
                    $result['merchant_reduce_type'] = $c['type'];
                }
            }
        }
        ///////-garfunkel-店铺满减////////
        $result['ship_fee'] = $delivey_fee;
        $total_pay_price += $delivey_fee;
        //获取预计到达时间
        $delivery_time = D('Store')->get_store_delivery_time($sid);
        //计算税费
        $tax_price = $tax_price + ($store['pack_fee'] + $delivey_fee)*$store['tax_num']/100;
        $total_pay_price = $total_pay_price + $tax_price + $deposit_price;

        $result['expect_time'] = date('Y-m-d H:i',$delivery_time);
        $result['hongbao'] = array();
        $result['total_market_price'] = $total_market_price;
        $result['food_total_price'] = $total_price;
        $result['total_pay_price'] = round($total_pay_price,2);
        $result['tax_price'] = round($tax_price,2);
        $result['deposit_price'] = round($deposit_price,2);
        $result['pay_method'] = explode('|',$store['pay_method']);

        $result['full_discount'] = '0';

        $config = D('Config')->get_gid_config(43);
        $not_touch = array();
        foreach ($config as $v){
            if($v['name'] == 'not_touch'){
                $txt = explode('|',$v['value']);
                $not_touch['title'] = $txt[0];
                $not_touch['content'] = $txt[1];
            }

            if($v['name'] == 'not_touch_enable'){
                if($v['value'] == '1'){
                    $not_touch['status'] = 1;
                }else{
                    $not_touch['status'] = 0;
                }
            }
        }
        $result['not_touch'] = $not_touch;

        return $result;
    }

    public function delCart($uid,$cartList){
        foreach($cartList as $v){
            $this->field(true)->where(array('uid'=>$uid,'fid'=>$v['fid']))->delete();
        }
    }
}