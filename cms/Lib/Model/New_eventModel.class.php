<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2019/8/21
 * Time: 15:24
 */

class New_eventModel extends Model
{
    /**
     * @param $status
     * -1 全部
     * 0 禁止
     * 1 正常
     * 2 过期
     */
    public function getEventList($status=-1,$type=-1,$city_id=0){
        $where = array();
        if($status != -1){
            $where['status'] = $status;
        }
        if($type != -1){
            $where['type'] = $type;
        }
        //配送费减免 需要关联城市 去掉&& $city_id != 0
        if($type == 3){
            $where['city_id'] = $city_id;
        }

        $list = $this->field(true)->where($where)->select();
        $new_list = array();
        foreach ($list as &$v){
            $v['type_name'] = $this->getTypeName($v['type']);
            $v = $this->checkExpiry($v);
            $v['status_name'] = $this->getStausName($v['status']);
            $v['coupon_amount'] = $this->getEventCouponAmount($v['id']);
            if($v['city_id'] == 0){
                $v['city_name'] = L('G_UNIVERSAL');
            }else{
                $c = D('Area')->where(array('area_type' => 2, 'is_open' => 1, 'area_id' => $v['city_id']))->find();
                $v['city_name'] = $c['area_name'];
            }
            if($status != -1){
                if($v['status'] == $status) {
                    if($status == 1 && $v['begin_time'] != 0){
                        if($v['begin_time'] < time())
                            $new_list[] = $v;
                    }else{
                        $new_list[] = $v;
                    }
                }
            }else{
                $new_list[] = $v;
            }
        }

        return $new_list;
    }

    /**
     * 获取活动所有优惠劵的金额
     * @param $event_id
     * @param int $type
     * @return int
     */
    public function getEventCouponAmount($event_id,$type=0){
        $coupon_list = D('New_event_coupon')->where(array('event_id'=>$event_id,'type'=>$type))->select();

        $amount = 0;
        foreach ($coupon_list as $v){
            $amount += $v['discount'];
        }

        return $amount;
    }

    /**
     * @param $type
     * 1 新用户注册
     * 2 新用户邀请
     * 3 规定范围内免配送费
     * 4 店铺满减活动
     * 5 店铺减免配送费
     */
    public function getTypeName($type){
        $typeName = ['无效活动',L('G_NEW_USER_REGISTRATION'),L('G_FRIEND_REFERRAL'),L('G_FREE_DISTANCE'),L('G_MERCHANT_DISCOUNT'),L('G_FREE_SELECTED')];
        if($type == -1)
            return $typeName;
        else
            return $typeName[$type];
    }

    public function getStausName($status){
        $statusName = [L('_BACK_BANNED_'),L('G_ACTIVE'),L('G_EXPIRED')];
        return $statusName[$status];
    }

    /**
     * @param $type 活动类型
     * @param int $event_id 0检测所有活动
     *
     * 返回true 为不存在此类型的活动 false 为存在
     */
    public function checkEventType($type,$event_id=0,$city_id=0){
        if($event_id != 0){
            $where['id'] = array('neq',$event_id);
        }

        $where['type'] = $type;
        $where['status'] = 1;
        //暂时先判断是否为减免配送费活动
        if($type == 3){
            $where['city_id'] = $city_id;
        }

        //店铺减免配送费不判断是否存在
        if($type != 5) {
            if ($this->where($where)->find()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $event
     * @return mixed
     * 检测活动是否过期
     */
    public function checkExpiry($event){
        $t_time = time();
        if($event['end_time'] > 0) {
            $end_time = $event['end_time'] + 24 * 3600;
            if ($t_time > $end_time) {
                $event['status'] = 2;
            }
            $this->where(array('id' => $event['id']))->save($event);
        }

        return $event;
    }

    /**
     * @param $type 活动类型
     * @param $user_id 用户id
     * 为用户添加coupon
     */
    public function addEventCouponByType($type,$user_id){
        if($this->userIsEvent($type,$user_id)) {
            $event = $this->where(array('type' => $type, 'status' => 1))->find();
            $event = $this->checkExpiry($event);

            if($event['status'] != 1){
                return false;
            }

            if ($event) {
                if ($event['begin_time'] != 0 && $event['begin_time'] > time()){
                    return false;
                }else {
                    $today = strtotime(date('Y-m-d'));

                    $event_id = $event['id'];
                    $coupon_list = D('New_event_coupon')->where(array('event_id' => $event_id))->select();
                    //添加给自己的优惠券
                    $coupon_add_self = array();
                    //添加给邀请者的优惠券
                    $coupon_add_invi = array();

                    $user = D('User')->where(array('uid' => $user_id))->find();

                    foreach ($coupon_list as $v) {
                        $data = array();
                        $data['event_coupon_id'] = $v['id'];

                        $data['create_time'] = time();
                        $data['expiry_time'] = $today + ($v['limit_day'] + 1) * 24 * 3600 - 1;
                        //////////////
                        /**
                         * 使用于type=3的活动-即为立即使用类型
                         */
                        if($event['type'] == 3 || $event['type'] == 4){
                            $data['use_time'] = $data['expiry_time'];
                            $data['is_use'] = 1;
                            $data['uid'] = $user_id;
                            $coupon_add_self[] = $data;
                        } else {
                            if ($v['type'] == 0) {
                                $data['uid'] = $user_id;
                                $coupon_add_self[] = $data;
                            } else {
                                if ($user['invitation_user'] != 0) {
                                    $data['uid'] = $user['invitation_user'];
                                    $coupon_add_invi[] = $data;
                                }
                            }
                        }
                    }

                    if (count($coupon_add_self) > 0)
                        D('New_event_user')->addAll($coupon_add_self);

                    if (count($coupon_add_invi) > 0)
                        D('New_event_user')->addAll($coupon_add_invi);

                    return true;
                }
            }
            return false;
        }
        return false;
    }

    /**用户是否符合活动要求
     * @param $type 活动类型
     * @param $uid 用户id
     */
    public function userIsEvent($type,$uid){
        $is_re = false;
        switch ($type){
            case 1:
                $is_re = true;
                break;
            case 2:
                $user = D('User')->where(array('uid'=>$uid))->find();
                if($user['invitation_user'] != 0){
                    $is_re = true;
                }
                break;
            case 3:
                $is_re = true;
                break;
            case 4:
                $is_re = true;
                break;
            default:
                break;
        }

        return $is_re;
    }

    /**
     * @param $uid
     * 获取用户在活动中获取的优惠券
     */
    public function getUserCoupon($uid,$status=-1,$order_money=-1,$coupon_id=-1){

        $where['u.uid'] = $uid;
        if($order_money != -1)
            $where['c.use_price'] = array('ELT',$order_money);

        if($status != -1){
            $where['u.is_use'] = $status;
        }

        if($coupon_id != -1){
            $where['u.id'] = $coupon_id;
        }

        $coupon_list = M('New_event_user')->join('as u left join '.C('DB_PREFIX').'new_event_coupon as c ON u.event_coupon_id=c.id')->field('u.*')->where($where)->select();

        $list = array();
        foreach ($coupon_list as &$v){
            if(time() > $v['expiry_time']) {
                //更新已经过期的优惠券
                $v['is_use'] = 2;
                D('New_event_user')->where(array('id'=>$v['id']))->save($v);
                continue;
            }

            $v['coupon_id'] = 'event_'.$v['event_coupon_id'];
            $coupon = D('New_event_coupon')->where(array('id'=>$v['event_coupon_id']))->find();
            $v['name'] = $coupon['name'];
            $v['desc'] = $coupon['desc'];
            $v['discount'] = $coupon['discount'];
            $v['order_money'] = $coupon['use_price'];
            $v['type'] = 'all';
            $v['start_time'] = $v['create_time'];
            $v['end_time'] = $v['expiry_time'];
            if(C('DEFAULT_LANG') == 'zh-cn'){
                $v['discount_desc'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),$v['order_money']).replace_lang_str(L('_MAN_REDUCE_NUM_'),$v['discount']);
            }else{
                $v['discount_desc'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),$v['discount']).replace_lang_str(L('_MAN_REDUCE_NUM_'),$v['order_money']);
            }

            $list[] = $v;
        }
        return $list;
    }

    /**
     * 整理某活动所有coupon的状态
     * @param $coupon 某活动的couponList
     * @return mixed
     */
    public function getCouponUserNum($coupon){
        $user_list = D('New_event_user')->where(array('event_coupon_id'=>$coupon['id']))->select();
        //var_dump($user_list);var_dump(time());die();
        foreach($user_list as $v){
            if(time() > $v['expiry_time'] && $v['is_use'] == 0) {
                $v['is_use'] = 2;
                D('New_event_user')->where(array('id'=>$v['id']))->save($v);
            }
        }

        $coupon['all_num'] = D('New_event_user')->where(array('event_coupon_id'=>$coupon['id']))->count();
        $coupon['use_num'] = D('New_event_user')->where(array('event_coupon_id'=>$coupon['id'],'is_use'=>1))->count();
        $coupon['expiry_num'] = D('New_event_user')->where(array('event_coupon_id'=>$coupon['id'],'is_use'=>2))->count();

        return $coupon;
    }

    public function getFreeDeliverCoupon($store_id,$city_id=0){
        //首先获取平台减免获得
        $eventList = $this->getEventList(1,3,$city_id);
        $delivery_coupon = "";
        if(count($eventList) > 0) {
            foreach ($eventList as $event) {
                $store_coupon = D('New_event_coupon')->where(array('event_id' => $event['id']))->find();
                if($store_coupon) {
                    $delivery_coupon = $store_coupon;
                    $delivery_coupon['event_type'] = 0;
                }
            }
        }

        //店铺减免配送费 如果店铺减免存在便替换平台减免
        $eventList = $this->getEventList(1,5);
        if(count($eventList) > 0) {
            foreach ($eventList as $event) {
                $store_coupon = D('New_event_coupon')->where(array('event_id' => $event['id'], 'limit_day' => $store_id))->order('use_price asc')->find();
                if ($store_coupon) {
                    $delivery_coupon = $store_coupon;
                    //暂时设定为20公里内减免
                    $delivery_coupon['limit_day'] = 20;
                    $delivery_coupon['event_type'] = $event['id'];
                }
            }
        }
//        if(is_array($delivery_coupon)){
//            var_dump($delivery_coupon);
//            die();
//        }
        return $delivery_coupon;
    }
}