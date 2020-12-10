<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2019/8/20
 * Time: 20:00
 */

class EventAction extends BaseAction
{
    public function index(){
        $event_list = D('New_event')->getEventList(-1);
        $this->assign('event_list',$event_list);
        $this->display();
    }

    public function add(){
        $type_list = D('New_event')->getTypeName(-1);

        $this->assign('type',$type_list);
        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);
        $this->display();
    }

    public function edit(){
        $type_list = D('New_event')->getTypeName(-1);
        $this->assign('type',$type_list);

        $event = D('New_event')->where(array('id'=>$_GET['id']))->find();
        $event['type_name'] = D('New_event')->getTypeName($event['type']);
        $this->assign('event',$event);
        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);
        $this->display('add');
    }

    public function modify(){
        $data['name'] = $_POST['name'];
        $data['desc'] = $_POST['desc'];
        $data['type'] = $_POST['type'];
        $data['city_id'] = $_POST['city_id'] ? $_POST['city_id'] : 0;

        if($_POST['begin_time'])
            $data['begin_time'] = strtotime($_POST['begin_time']);
        else
            $data['begin_time'] = 0;

        if($_POST['end_time'])
            $data['end_time'] = strtotime($_POST['end_time']);
        else
            $data['end_time'] = 0;

        if($_POST){
            if($_POST['event_id'] && $_POST['event_id'] != 0){
                if(D('New_event')->checkEventType($data['type'],$_POST['event_id'],$data['city_id'])) {
                    $where['id'] = $_POST['event_id'];
                    D('New_event')->where($where)->save($data);
                    $this->frame_submit_tips(1, 'Success！');
                }
                $this->frame_submit_tips(0,'此活动类型已存在！');
            }else{
                if(D('New_event')->checkEventType($data['type'],0,$data['city_id'])){
                    D('New_event')->add($data);
                    $this->frame_submit_tips(1, 'Success！');
                }else{
                    $this->frame_submit_tips(0,'此活动类型已存在！');
                }
            }
        }
    }

    public function coupon_list(){
        $event_id = $_GET['id'];
        if($event_id){
            $event = D('New_event')->where(array('id'=>$event_id))->find();

            $event['type_name'] = $this->getTypeSetName($event);

            $this->assign('event',$event);

            $coupon_list = D('New_event_coupon')->where(array('event_id'=>$event_id))->select();
            foreach ($coupon_list as &$v){
                $v = D('New_event')->getCouponUserNum($v);
                if($event['type'] == 4 || $event['type'] == 5){
                    $store = D('Merchant_store')->field('name')->where(array('store_id'=>$v['limit_day']))->find();
                    $v['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
                }
            }
            $this->assign('coupon_list',$coupon_list);

            $this->display();
        }else{
            $this->error('未定义活动~');
        }
    }



    public function add_coupon(){
        if($_GET['event_id']){
            $event_id = $_GET['event_id'];
            $this->assign('event_id',$event_id);
            $event = D('New_event')->where(array('id'=>$event_id))->find();
            $this->assign('event_type',$event['type']);
            $this->assign('type_name',$this->getTypeSetName($event));
            $this->display();
        }else{
            $this->frame_submit_tips(0,'请先选择活动！');
        }
    }

    public function edit_coupon(){
        if($_GET['id']) {
            $coupon = D('New_event_coupon')->where(array('id'=>$_GET['id']))->find();
            $this->assign('coupon',$coupon);
            $this->assign('event_id',$coupon['event_id']);
            $event = D('New_event')->where(array('id'=>$coupon['event_id']))->find();
            $this->assign('event_type',$event['type']);
            $this->assign('type_name',$this->getTypeSetName($event));
            $this->display('add_coupon');
        }else{
            $this->frame_submit_tips(0,'请先选择优惠券！');
        }
    }

    public function getTypeSetName($event){
        $type_name = '限制天数';
        if($event['type'] == 3){
            $type_name = '限制公里数';
        }else if($event['type'] == 4 || $event['type'] == 5){
            $type_name = '店铺ID';
        }

        return $type_name;
    }

    public function coupon_modify(){
        if ($_POST){
            $data['event_id'] = $_POST['event_id'];
            $data['name'] = $_POST['name'];
            $data['desc'] = $_POST['desc'];
            $data['use_price'] = $_POST['use_price'];
            $data['discount'] = $_POST['discount'];
            $data['limit_day'] = $_POST['limit_day'];
            $data['type'] = $_POST['type'];

            if($_POST['coupon_id'] != 0){
                D('New_event_coupon')->where(array('id'=>$_POST['coupon_id']))->save($data);
            }else{
                D('New_event_coupon')->add($data);
            }

            $this->frame_submit_tips(1, 'Success！');
        }
    }

    public function coupon_del(){
        $id = $_POST['id'];
        $msg = '';
        if($id){
            $coupon = D('New_event_coupon')->where(array('id'=>$id))->find();
            if($coupon){
                $event = D('New_event')->where(array('id'=>$coupon['event_id']))->find();
                if($event['type'] == 4){
                    D('New_event_coupon')->where(array('id'=>$id))->delete();
                    $this->success('Success');
                }else{
                    $msg = "Can not Delete!";
                }
            }else{
                $msg = "Coupon Error";
            }
        }else{
            $msg = "ID Error";
        }
        $this->error($msg);
    }
}