<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2022/3/8
 * Time: 6:55 AM
 */

class Cloud_messageModel extends Model
{
    public $messageType = array(
        "New registration",
        "Last order",
        "Coupon reminder after redeem",
        "Coupon reminder before expire"
    );

    public function getUserListFromType($type,$days){
        $today = strtotime(date("Y-m-d"));
        $search_begin_day = $today - 86400*$days;
        $search_end_day = $search_begin_day + 86399;

        $dayData['begin_time'] = $search_begin_day;
        $dayData['end_time'] = $search_end_day;
        //var_dump($type.">>>>>>>".date("Y-m-d H:i:s",$dayData['begin_time'])."----".date("Y-m-d H:i:s",$dayData['end_time']));
        switch ($type){
            case 0:
                $list = $this->getNewRegistration($dayData);
                break;
            case 1:
                $list = $this->getLastOrder($dayData);
                break;
            case 2:
                $list = $this->getAfterRedeem($dayData);
                break;
            case 3:
                $today = strtotime(date("Y-m-d"));
                $search_begin_day = $today + 86400*$days;
                $search_end_day = $search_begin_day + 86399;

                $dayData['begin_time'] = $search_begin_day;
                $dayData['end_time'] = $search_end_day;
                
                $list = $this->getBeforeExpire($dayData);
                break;
            default:
                $list = array();
                break;
        }

        return $list;
    }

    private function getNewRegistration($dayData){
        $where['add_time'] = array('between',array($dayData['begin_time'],$dayData['end_time']));
        $where['status'] = 1;
        $where['is_send_message'] = 0;
        $where['last_order_time'] = 0;

        $list = D("User")->where($where)->select();

        return $list;
    }

    private function getLastOrder($dayData){
        $where['last_order_time'] = array('between',array($dayData['begin_time'],$dayData['end_time']));
        $where['status'] = 1;
        $where['is_send_message'] = 0;

        $list = D("User")->where($where)->select();

        return $list;
    }

    private function getAfterRedeem($dayData){
        $where['receive_time'] = array('between',array($dayData['begin_time'],$dayData['end_time']));
        $where['is_use'] = 0;

        $had_list = D('System_coupon_hadpull')->where($where)->select();

        $user_list = array();

        foreach ($had_list as $v){
            if(!in_array($v['uid'],$user_list)) $user_list[] = $v['uid'];
        }

        $event_coupon_list = D('New_event_user')->where(array('is_use'=>0,'create_time'=>array('between',array($dayData['begin_time'],$dayData['end_time']))))->select();
        foreach ($event_coupon_list as $e){
            if(!in_array($e['uid'],$user_list)) $user_list[] = $e['uid'];
        }

        $list = D('User')->where(array('uid'=>array('in',$user_list),'status'=>1,'is_send_message'=>0))->select();

        return $list;
    }

    private function getBeforeExpire($dayData){
        $where['c.end_time'] = array('between',array($dayData['begin_time'],$dayData['end_time']));
        $where['c.status'] = 1;
        $where['h.is_use'] = 0;

        $had_list = D('System_coupon')->join('as c left join '.C('DB_PREFIX').'system_coupon_hadpull h ON h.coupon_id = c.coupon_id')->where($where)->select();

        $user_list = array();

        foreach ($had_list as $v){
            if(!in_array($v['uid'],$user_list)) $user_list[] = $v['uid'];
        }

        $event_coupon_list = D('New_event_user')->where(array('is_use'=>0,'expiry_time'=>array('between',array($dayData['begin_time'],$dayData['end_time']))))->select();
        foreach ($event_coupon_list as $e){
            if(!in_array($e['uid'],$user_list)) $user_list[] = $e['uid'];
        }

        $list = D('User')->where(array('uid'=>array('in',$user_list),'status'=>1,'is_send_message'=>0))->select();

        return $list;
    }
}