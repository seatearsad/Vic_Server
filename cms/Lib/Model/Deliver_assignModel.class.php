<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/11/12
 * Time: 11:46
 */

class Deliver_assignModel extends Model
{
    //更换配送员时间
    const CHANGE_TIME = 30;
    //更换配送员中间的缓冲时间
    const CHANGE_BUFFER_TIME = 5;
    //总共可更换配送员的次数
    const CHANGE_TOTAL_TIMES = 3;
    public function createAssign($supply,$supply_id){
        $data['order_id'] = $supply['order_id'];
        $data['supply_id'] = $supply_id;
        $data['deliver_id'] = $this->easyLogic($data['supply_id']);
        $data['assign_time'] = time();
        $data['assign_num'] = 1;
        $data['record'] = $data['deliver_id'];

        $this->field(true)->add($data);
    }
    //最简单的逻辑
    public function easyLogic()
    {
        return 18;
    }
    //检测派单的状态
    public function check_assign(){
        $curr_time = time();
        $where = 'deliver_id <> 0 and (status = 0 or status = 99)';
        $list = $this->field(true)->where($where)->select();
        foreach ($list as $k=>$v){
            $list[$k]['cha'] = $curr_time - $v['assign_time'];
            $where = array('supply_id'=>$v['supply_id']);
            //上一个指派超时未抢
            if($v['status'] == 0 && $list[$k]['cha'] > CHANGE_TIME){
                //总派单次数已到
                if((int)$v['assign_num'] >= CHANGE_TOTAL_TIMES){
                    $data['deliver_id'] = 0;
                }else{//准备变换派单人选
                    $data['deliver_id'] = -1;
                    $data['status'] = 99;
                }
                $data['assign_time'] = $curr_time;
                $this->field(true)->where($where)->save($data);
            }else if($v['status'] == 99 && $list[$k]['cha'] > CHANGE_BUFFER_TIME){
                //重新选择派单人选
                $data['status'] = 0;
                $data['deliver_id'] = $this->easyLogic();
                $data['assign_time'] = $curr_time;
                $data['assign_num'] = $v['assign_num'] + 1;
                $data['record'] = $v['record'].','.$data['deliver_id'];
                $this->field(true)->where($where)->save($data);
            }
        }
        var_dump($list);
    }
}