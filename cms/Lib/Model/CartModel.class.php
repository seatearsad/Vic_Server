<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/8/31
 * Time: 16:08
 */

class CartModel extends Model
{
    public function add_cart($uid,$fid,$num=1){
        $data['uid'] = $uid;
        $data['fid'] = $fid;

        $good = D('Shop_goods')->field(true)->where(array('goods_id' => $fid))->find();
        $data['sid'] = $good['store_id'];

        $data['num'] = $num;
        $data['time'] = date("Y-m-d H:i:s");

        $item = $this->field(true)->where(array('uid'=>$uid,'fid'=>$fid))->find();

        if(empty($item)){
            $id = $this->data($data)->add();
        }else{
            $item['num'] += $num;
            if ($item['num']<=0)
                $this->field(true)->where(array('itemId'=>$item['itemId']))->delete();
            else
                $this->field(true)->data($item)->where(array('itemId'=>$item['itemId']))->save();
        }

        return true;
    }

    public function get_cart($uid){
        $where['uid'] = $uid;

        $cartList = $this->field(true)->where($where)->order('sid ASC')->select();
        $result = array();

        $allnum = 0;
        $allmoney = 0;

        $resid = 0;

        $goodList = array();
        foreach($cartList as $v){
            $allnum += $v['num'];
            $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['fid']))->find();

            $allmoney += $good['price']*$v['num'];
            if ($resid != $good['store_id']){
                $store = D('Store')->get_store_by_id($good['store_id']);

                $resid = $good['store_id'];

                $result['info'][] = $store;
            }
            $good['quantity'] = $v['num'];
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

        foreach ($cartList as $v){
            $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['fid']))->find();
            $t_good['fname'] = $good['name'];
            $t_good['stock'] = $v['stock'];
            $t_good['attr'] = $good['unit'];
            $t_good['price'] = $good['price'];

            $total_price += $good['price'];
            $total_pay_price += $good['price'];
            $total_market_price += $good['old_price'];

            $list[] = $t_good;
        }

        $result['info'] = $list;

        $sid = $this->field(true)->where(array('uid'=>$uid,'fid'=>$cartList[0]['fid']))->find()['sid'];
        $store = D('Store')->get_store_by_id($sid);
        $result['packing_fee'] = $store['pack_fee'];
        $total_pay_price += $store['pack_fee'];
        //获取配送费
        $delivey_fee = D('Store')->CalculationDeliveryFee($uid,$sid);
        $result['ship_fee'] = $delivey_fee;
        $total_pay_price += $delivey_fee;
        //获取预计到达时间
        $delivery_time = D('Store')->get_store_delivery_time($sid);

        $result['expect_time'] = date('Y-m-d H:i',$delivery_time);
        $result['hongbao'] = array();
        $result['total_market_price'] = $total_market_price;
        $result['food_total_price'] = $total_price;
        $result['total_pay_price'] = $total_pay_price * 1.05;

        $result['full_discount'] = '0';

        return $result;
    }
}