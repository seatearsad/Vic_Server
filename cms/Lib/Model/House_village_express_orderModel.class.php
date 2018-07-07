<?php
class House_village_express_orderModel extends Model{
    public function house_village_express_order_add($data){
        if(!$data){
            return false;
        }

        $send_time = strtotime($data['send_time']);

        if($send_time <= 0){
            return array('status'=>0,'msg'=>'送达时间不能为空！');
        }

        if($send_time < time()){
            return array('status'=>0,'msg'=>'送达时间不能小于当前时间！');
        }


        $express_id = $data['express_id'] + 0;
        $info = $this->where(array('express_id'=>$express_id,'paid'=>1))->find();

        if($info){
            return array('status'=>0,'msg'=>'已成功上报，请勿重复操作！');
        }

        $data['express_id'] = $express_id;

        $data['send_time'] = strtotime($data['send_time']);
        $data['uid'] = $_SESSION['user']['uid'];
        $data['phone'] = $_SESSION['user']['phone'];
        $data['express_collection_price'] = $data['express_collection_price'] + 0;
        $data['village_id'] = $_SESSION['now_village_bind']['village_id'];
        $data['add_time'] = time();

        $insert_id = $this->data($data)->add();
        if($insert_id){
            return array('status'=>1,'msg'=>'添加成功！','order_id'=>$insert_id);
        }else{
            return array('status'=>0,'msg'=>'添加失败！');
        }
    }

    public function house_village_express_order_detail($where,$fields = true){
        if(!$where){
            return false;
        }

        $detail = $this->where($where)->field($fields)->find();
        if($detail){
            return array('status'=>1,'detail'=>$detail);
        }else{
            return array('status'=>0,'detail'=>$detail);
        }
    }

    public function house_village_express_order_edit($where , $data){
        if(!$where || !$data){
            return false;
        }

        $insert_id = $this->where($where)->data($data)->save();
        if($insert_id){
            return array('status'=>1,'msg'=>'修改成功！');
        }else{
            return array('status'=>0,'msg'=>'修改失败！');
        }
    }


    public function house_village_express_order_page_list($where ,$fields = true , $order = 'order_id desc',$pageSize = 20){
        if(!$where){
            return false;
        }

        import('@.ORG.merchant_page');
        $count = $this->where($where)->count();
        $p = new Page($count,$pageSize,'page');

        $house_village_express_order_list = $this->where($where)->field($fields)->order($order)->limit($p->firstRow.','.$p->listRows)->select();

        $list['list'] = $house_village_express_order_list;
        $list['pagebar'] = $p->show();
        if($list){
            return array('status'=>1,'result'=>$list);
        }else{
            return array('status'=>0,'result'=>$list);
        }
    }
}
?>