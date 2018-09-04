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
}