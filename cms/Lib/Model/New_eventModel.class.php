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
    public function getEventList($status=-1,$type=-1){
        $where = array();
        if($status != -1){
            $where['status'] = $status;
        }
        if($type != -1){
            $where['type'] = $type;
        }

        $list = $this->field(true)->where($where)->select();
        $new_list = array();
        foreach ($list as &$v){
            $v['type_name'] = $this->getTypeName($v['type']);
            $v = $this->checkExpiry($v);
            $v['status_name'] = $this->getStausName($v['status']);
            $v['coupon_amount'] = $this->getEventCouponAmount($v['id']);
            if($status != -1){
                if($v['status'] == $status)
                    $new_list[] = $v;
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
     */
    public function getTypeName($type){
        $typeName = ['无效活动','新用户注册','新用户邀请'];
        return $typeName[$type];
    }

    public function getStausName($status){
        $statusName = ['禁用','正常','过期'];
        return $statusName[$status];
    }

    /**
     * @param $type 活动类型
     * @param int $event_id 0检测所有活动
     *
     * 返回true 为不存在此类型的活动 false 为存在
     */
    public function checkEventType($type,$event_id=0){
        if($event_id != 0){
            $where['id'] = array('neq',$event_id);
        }

        $where['type'] = $type;
        $where['status'] = 1;

        if($this->where($where)->find()){
            return false;
        }

        return true;
    }

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
     */
    public function addEventCouponByType($type,$user_id){
        if($this->userIsEvent($type,$user_id)) {
            $event = $this->where(array('type' => $type, 'status' => 1))->find();
            if ($event) {
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

                if (count($coupon_add_self) > 0)
                    D('New_event_user')->addAll($coupon_add_self);

                if (count($coupon_add_invi) > 0)
                    D('New_event_user')->addAll($coupon_add_invi);

                return true;
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

        //$coupon_list = D('New_event_user')->where($where)->select();
        $coupon_list = M('New_event_user')->join('as u left join '.C('DB_PREFIX').'new_event_coupon as c ON u.event_coupon_id=c.id')->field('u.*')->where($where)->select();
        //var_dump($coupon_list);die();
        $list = array();
        foreach ($coupon_list as &$v){
            if(time() > $v['expiry_time']) {
                $v['is_user'] = 2;
                D('New_event_user')->where(array('id'=>$v['id']))->save($v);
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
            $list[] = $v;
        }

        return $list;
    }

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
}