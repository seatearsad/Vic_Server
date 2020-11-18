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
    //出餐需添加时间 分钟
    const DINING_ADD_TIME = 5;
    //每个节点添加时间 分钟
    const NODE_ADD_TIME = 6;

    //派单逻辑中 每个节点需要停留的时间 分钟
    const LOGIC_STAY_TIME = 6;
    public function createAssign($supply,$supply_id){
        $data['order_id'] = $supply['order_id'];
        $data['supply_id'] = $supply_id;
        $data['deliver_id'] = $this->assignLogic($data['supply_id']);
        $data['assign_time'] = time();
        $data['assign_num'] = 1;
        $data['record'] = $data['deliver_id'];

        $this->field(true)->add($data);

        $this->sendMsg($data['deliver_id']);
    }
    //最简单的逻辑
    public function easyLogic($supply_id)
    {
        //获取正在上班的配送员 work_status=0为上班状态
        $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>0))->order('uid asc')->select();
        //没有配送员 返回0 等待抢单
        if(count($user_list) > 0){
            $user_count_list = array();
            foreach ($user_list as $k=>$v){
                //获取所有配送员手中的单数
                $hand_order_num = D('Deliver_supply')->field(true)->where(array('uid'=> $v['uid'],'status' => array(array('gt', 1), array('lt', 5))))->count();

                //获取之前的派单记录
                $record = D('Deliver_assign')->field(true)->where(array('supply_id'=>$supply_id))->find();
                if($record){
                    $record_list = explode(',',$record['record']);
                    //如果之前的记录中存在此用户 跳过
                    if(in_array($v['uid'],$record_list))
                        continue;
                }
//
                //第一个没有订单的人获得派单权
                if($hand_order_num == 0){
                    return $v['uid'];
                    break;
                }

                $user_count_list[$v['uid']] = $hand_order_num;
            }
            //没有配送员手中订单是0的情况 找最少的
            sort($user_count_list);
            return key($user_count_list);
        }else{
            return 0;
        }
    }
    //检测派单的状态
    public function check_assign(){
        $curr_time = time();
        $where = 'deliver_id <> 0 and (status = 0 or status = 99)';
        $list = $this->field(true)->where($where)->select();
        //记录一下 已经发送过短信的用户
        $send_list = array();
        foreach ($list as $k=>$v){
            $list[$k]['cha'] = $curr_time - $v['assign_time'];
            $where = array('supply_id'=>$v['supply_id']);
            //上一个指派超时未抢
            if($v['status'] == 0 && $list[$k]['cha'] > self::CHANGE_TIME){
                //总派单次数已到
                if((int)$v['assign_num'] >= self::CHANGE_TOTAL_TIMES){
                    $data['deliver_id'] = 0;
                    //获取当前订单的相关信息
                    $supply = D('Deliver_supply')->field(true)->where(array('supply_id'=>$v['supply_id']))->find();
                    //获取店铺信息
                    $store = D('Merchant_store')->field(true)->where(array('store_id'=>$supply['store_id']))->find();
                    //群发短信 筛选城市
                    $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>0,'city_id'=>$store['city_id']))->order('uid asc')->select();
                    $record = explode(',',$v['record']);
                    foreach ($user_list as $deliver){
                        if(!in_array($deliver['uid'],$record) && !in_array($deliver['uid'],$send_list)){
                            $this->sendMsg($deliver['uid']);
                            $send_list[] = $deliver['uid'];
                        }
                    }
                    //清除之前的记录 让所有都能抢
                    $data['record'] = '';
                }else{//准备变换派单人选
                    $data['deliver_id'] = -1;
                    $data['status'] = 99;
                }
                $data['assign_time'] = $curr_time;
                $this->field(true)->where($where)->save($data);
            }else if($v['status'] == 99 && $list[$k]['cha'] > self::CHANGE_BUFFER_TIME){
                //重新选择派单人选
                $data['status'] = 0;
                $data['deliver_id'] = $this->assignLogic($v['supply_id']);
                $data['assign_time'] = $curr_time;
                $data['assign_num'] = $v['assign_num'] + 1;

                if($data['deliver_id'] == 0)
                    $data['record'] = '';
                else
                    $data['record'] = $v['record'].','.$data['deliver_id'];

                $this->field(true)->where($where)->save($data);

                $this->sendMsg($data['deliver_id']);
            }
        }
        var_dump($list);
    }
    //初步实现的派单逻辑
//    public function assignLogic($supply_id){
//        //获取当前订单的相关信息
//        $supply = D('Deliver_supply')->field(true)->where(array('supply_id'=>$supply_id))->find();
//        //获取店铺信息
//        //$store = D('Merchant_store')->field(true)->where(array('store_id'=>$supply['store_id']))->find();
//        //获取当前所有上班状态的配送员
//        $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>0))->order('uid asc')->select();
//        //节点数组
//        $node_list = array();
//        //遍历每个配送员
//        foreach ($user_list as $deliver){
//            //获取之前的派单记录
//            $record = D('Deliver_assign')->field(true)->where(array('supply_id'=>$supply_id))->find();
//            if($record){
//                $record_list = explode(',',$record['record']);
//                //如果之前的记录中存在此用户 跳过
//                if(in_array($deliver['uid'],$record_list))
//                    continue;
//            }
//
//            //记录自己的当前位置
//            $node_list[$deliver['uid']]['self'] = $deliver['lat'].','.$deliver['lng'];
//            //记录新订单的店家以及客户位置
//            $node_list[$deliver['uid']]['store'][$supply['supply_id']]['position'] = $supply['from_lat'].','.$supply['from_lnt'];
//            $node_list[$deliver['uid']]['store'][$supply['supply_id']]['din_time'] = $supply['dining_time'];
//            $node_list[$deliver['uid']]['store'][$supply['supply_id']]['store_id'] = $supply['store_id'];
//            $node_list[$deliver['uid']]['custom'][$supply['supply_id']]['position'] = $supply['aim_lat'].','.$supply['aim_lnt'];
//
//            //获取配送员手中正在处理的单子信息
//            $hand_order_list = D('Deliver_supply')->field(true)->where(array('uid'=> $deliver['uid'],'status' => array(array('gt', 1), array('lt', 5))))->select();
//            //记录所有的参考节点
//            if($hand_order_list){//有未完成订单的配送员
//                foreach ($hand_order_list as $order){
//                    if($order['status'] == 2){//未取餐的 先记录店家位置
//                        $node_list[$deliver['uid']]['store'][$order['supply_id']]['position'] = $order['from_lat'].','.$order['from_lnt'];
//                        $node_list[$deliver['uid']]['store'][$order['supply_id']]['din_time'] = $order['dining_time'];
//                        $node_list[$deliver['uid']]['store'][$order['supply_id']]['store_id'] = $order['store_id'];
//                    }
//                    //记录客户位置
//                    $node_list[$deliver['uid']]['custom'][$order['supply_id']]['position'] = $order['aim_lat'].','.$order['aim_lnt'];
//                }
//            }
//        }
//
//        var_dump($node_list);die();
//    }
    //初步实现的派单逻辑
    public function assignLogic($supply_id){
        //获取当前订单的相关信息
        $supply = D('Deliver_supply')->field(true)->where(array('supply_id'=>$supply_id))->find();
        //获取店铺信息
        $store = D('Merchant_store')->field(true)->where(array('store_id'=>$supply['store_id']))->find();

        //获取当前所有上班状态的配送员 包含现在手中订单数量及状态 06.02 add 过滤城市
        $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>0,'city_id'=>$store['city_id']))->order('uid asc')->select();
        $deliver_list = array();
        foreach ($user_list as $k => $v){
            //获取之前的派单记录
            $record = D('Deliver_assign')->field(true)->where(array('supply_id'=>$supply_id))->find();
            if($record){
                $record_list = explode(',',$record['record']);
                //如果之前的记录中存在此用户 跳过
                if(in_array($v['uid'],$record_list))
                    continue;
                else
                    $deliver = $v;
            }else{
                $deliver = $v;
            }
            $where = array('uid'=>$v['uid'],'status' => array(array('gt', 1), array('lt', 5)));
            $user_order = D('Deliver_supply')->field(true)->where($where)->select();
            //送餐员当前手中订单数量
            $deliver['count'] = count($user_order);
            foreach ($user_order as $o){
                //状态
                $deliver['order'][$o['supply_id']]['status'] = $o['status'];
                //出餐时间
                $deliver['order'][$o['supply_id']]['dining_time'] = $o['dining_time'];
                //店铺坐标
                $deliver['order'][$o['supply_id']]['store_lat'] = $o['from_lat'];
                $deliver['order'][$o['supply_id']]['store_lng'] = $o['from_lnt'];
                //客户坐标
                $deliver['order'][$o['supply_id']]['user_lat'] = $o['aim_lat'];
                $deliver['order'][$o['supply_id']]['user_lng'] = $o['aim_lnt'];
                //接单时间戳
                $deliver['order'][$o['supply_id']]['create_time'] = $o['create_time'];
            }
            $deliver_list[] = $deliver;
        }

        $user_id = $this->step_first($deliver_list,$supply);
        //如果第一步成功 便返回 否则进入第二部
        if($user_id){
            return $user_id;
        }else{//2
            $user_id = $this->step_second($deliver_list,$supply);
            if($user_id){
                return $user_id;
            }else{//3
                //第三步//////////////////////////////////////////
                //在都大于出餐时间的情况下 有没有小于10分钟的
                $user_id = $this->step_first($deliver_list,$supply,1);
                if($user_id){
                    return $user_id;
                }else{
                    $user_id = $this->step_second($deliver_list,$supply,1);
                    if($user_id){
                        return $user_id;
                /////////////////////////////////////////////////
                    }else{//4
                        $user_id = $this->step_fourth($deliver_list,$supply);
                        return $user_id;
                    }
                }
            }
        }
    }
    /*派单逻辑第一步
        获取所有送餐员中当前状态为-空闲
        比较所有空闲送餐员当中到达店铺时间最短的
        且这个时间小于 店铺出餐时间+5分钟
        如果没有 返回null

        type = 0 时候为第一步
        type = 1 第三步 判断是否有小于十分钟的配送员
    */
    public function step_first($user_list,$supply,$type = 0){
        //订单出餐时间
        $comparison_time = $supply['create_time'] + ($supply['dining_time']+self::DINING_ADD_TIME) * 60;
//        var_dump(date('Y-m-d H:i:s',$supply['create_time']).'--'.date('Y-m-d H:i:s',$comparison_time));
        //初始化一个比较数组
        $comparison = array();
        //遍历送餐员
        foreach ($user_list as $user){
            //空闲的送餐员参与计算
            if($user['count'] == 0){
                //获取配送员到达店铺的时间 使用google获取 时间过长 换做距离计算
//                $self_position = $user['lat'].','.$user['lng'];
//                $store_position = $supply['from_lat'].','.$supply['from_lnt'];
//                $distance = $this->getDistance($self_position,$store_position);
//                if(count($distance['info']['routes']) > 0){
//                    $routes = $distance['info']['routes'];
//                    var_dump($routes[0]['legs'][0]['duration']['text']);
//                }

                //获取两点之间的距离 并 获取预计到达时间
                $use_time = $this->getDistanceTime($user['lat'],$user['lng'],$supply['from_lat'],$supply['from_lnt']);
                //第三步的时候使用
                if($type == 1){
                    $eta = time() + $use_time * 60;
                    if($eta - $comparison_time < 10){
                        return $user['uid'];
                    }
                }
                $comparison[$user['uid']] = $use_time;
            }
        }

        if(count($comparison) > 0){
            //排序
            asort($comparison);
            //key 数组
            $u_list = array_keys($comparison);
            //使用时间最短的 即获取第一个
            $user_id = $u_list[0];
            $min_time = $comparison[$user_id];

            $eta = time() + $min_time * 60;
            //如果小于出餐时间 返回改送餐员id 否则返回空 进入第二步
            if($comparison_time >= $eta){
                return $user_id;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    /*
     * 派单逻辑 第二步
     * 此步计算手中已有订单的送餐员
     * 先获取该送餐员处理完手中订单所需要的时间
     * type = 0 时候为第二步
     * type = 1 第三步 判断是否有小于十分钟的配送员
     */
    public function step_second($user_list,$supply,$type=0){
        //订单出餐时间
        $comparison_time = $supply['create_time'] + ($supply['dining_time']+self::DINING_ADD_TIME) * 60;

        //初始化一个比较数组
        $comparison = array();
        //遍历送餐员
        foreach ($user_list as $user) {
            //仅有一张订单未完成的派送员参与计算
            if ($user['count'] > 0) {
                $i = 1;
                //初始当前用户时间
                $total_time = 0;
                foreach ($user['order'] as $order){
                    $new_store_time = 0;
                    if($i == count($user['order'])){
                        //如果这个是最后一张订单 首先计算出 从送达客户后 到达 新店铺的时间
                        $new_store_time = $this->getDistanceTime($order['user_lat'],$order['user_lng'],$supply['from_lat'],$supply['from_lnt']);
                    }
                    //var_dump($order);
                    switch ($order['status']){
                        case 2://抢单完成
                            //计算到达店铺时间 + 送往客户时间 + 到达新店铺时间
                            //到达老店铺时间
                            $old_store_time = $this->take_meal_time($user,$order);
                            //到达老客户时间
                            $old_user_time = $this->getDistanceTime($order['store_lat'],$order['store_lng'],$order['user_lat'],$order['user_lng']);
                            //统计总时间 加上每个几点固定时间
                            $total_time = $old_store_time + $old_user_time + self::LOGIC_STAY_TIME * 2 + $new_store_time;
                            break;
                        case 3://到达店铺取餐
                            //计算等待出餐时间 + 送往客户时间 + 到达新店铺时间
                            $chu_time = $order['create_time'] + ($order['dining_time']+self::DINING_ADD_TIME) * 60;
                            //如果未到取餐时间 就加上取餐时间
                            if($chu_time > time()){
                                $old_store_time =  ($chu_time - time()) / 60;
                            }else{
                                $old_store_time = 0;
                            }
                            //到达老客户时间
                            $old_user_time = $this->getDistanceTime($order['store_lat'],$order['store_lng'],$order['user_lat'],$order['user_lng']);
                            //统计总时间 加上每个几点固定时间
                            $total_time = $old_store_time + $old_user_time + self::LOGIC_STAY_TIME * 2 + $new_store_time;

                            break;
                        case 4://送往客户途中
                            //送达客户时间 + 到达新店铺时间
                            //到达老客户时间
                            $old_user_time = $this->getDistanceTime($user['lat'],$user['lng'],$order['user_lat'],$order['user_lng']);
                            //统计总时间 加上每个几点固定时间
                            $total_time = $old_user_time + self::LOGIC_STAY_TIME + $new_store_time;
                            break;
                    }
                    $i++;

                }
                //第三步的时候使用
                if($type == 1){
                    $eta = time() + $total_time * 60;
                    if($eta - $comparison_time < 10){
                        return $user['uid'];
                    }
                }
                $comparison[$user['uid']] = $total_time;
            }
        }
        if(count($comparison) > 0){
            //排序
            asort($comparison);
            //key 数组
            $u_list = array_keys($comparison);
            //使用时间最短的 即获取第一个
            $user_id = $u_list[0];
            $min_time = $comparison[$user_id];

            $eta = time() + $min_time * 60;
            //如果小于出餐时间 返回改送餐员id 否则返回空 进入第二步
            if($comparison_time >= $eta){
                return $user_id;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    /* 派单逻辑 第四步 最后一步
     * 获取配送员到达新店铺的路程时间
     * 获取老餐厅和新餐厅的出餐时间的时间差 绝对值
     * 获取所有老顾客到新顾客的时间和
     *
     * 最终计算 【|T餐厅路程-T出餐时间差值|（取绝对值）+T餐厅路程+T顾客路程】+【剩余节点数*5】+【送餐员订单数*10】
     */
    public function step_fourth($user_list,$supply){
        //初始化一个比较数组
        $comparison = array();
        //遍历送餐员
        foreach ($user_list as $user) {
            if($user['count'] == 0){
                //T餐厅路程
                $t_c_l = $this->getDistanceTime($user['lat'],$user['lng'],$supply['from_lat'],$supply['from_lnt']);
                //预计出餐时间
                $chu_time = $supply['create_time'] + ($supply['dining_time']+self::DINING_ADD_TIME) * 60;
                //T出餐时间差值
                $t_c_t = ($chu_time - time()) / 60;
                $t_c_t = $t_c_t < 0 ? 0 : $t_c_t;
                //T顾客路程
                $t_g_l = 0;
                //剩余节点数
                $s_j_n = 0;
            }else{
                $i = 1;
                //初始当前用户时间
                $t_c_l = 0;
                $t_c_t = 0;
                $t_g_l = 0;
                $s_j_n = 0;
                foreach ($user['order'] as $order) {
                    switch ($order['status']){
                        case 2://抢单完成
                            $t_c_l  += $this->getDistanceTime($order['lat'],$order['lng'],$supply['from_lat'],$supply['from_lnt']);
                            //预计出餐时间
                            $chu_time = $supply['create_time'] + ($supply['dining_time']+self::DINING_ADD_TIME) * 60;
                            //T出餐时间差值
                            $old_c = ($chu_time - time()) / 60;
                            $old_c = $old_c < 0 ? 0 : $old_c;
                            //预计出餐时间
                            $chu_time = $supply['create_time'] + ($supply['dining_time']+self::DINING_ADD_TIME) * 60;
                            //T出餐时间差值
                            $new_c = ($chu_time - time()) / 60;
                            $new_c = $new_c < 0 ? 0 : $new_c;
                            $t_c_t += abs($new_c - $old_c);

                            $s_j_n += 2;
                            break;
                        case 3://到达店铺取餐
                            $t_c_l += $this->getDistanceTime($order['lat'],$order['lng'],$supply['from_lat'],$supply['from_lnt']);
                            //预计出餐时间
                            $chu_time = $supply['create_time'] + ($supply['dining_time']+self::DINING_ADD_TIME) * 60;
                            //T出餐时间差值
                            $t_c_t += ($chu_time - time()) / 60;
                            $t_c_t = $t_c_t < 0 ? 0 : $t_c_t;
                            $s_j_n += 1;
                            break;
                        case 4://送往客户途中
                            $t_c_l = 0;
                            $t_c_t = 0;
                            $s_j_n += 1;
                            break;
                    }
                    //计算新老客户距离
                    $t_g_l += $this->getDistanceTime($order['user_lat'],$order['user_lng'],$supply['aim_lat'],$supply['aim_lnt']);
                }
            }

            $value = abs($t_c_l - $t_c_t) + $t_c_l + $t_g_l + $s_j_n*5 + $user['count']*10;
            $comparison[$user['uid']] = $value;
        }

        if(count($comparison) > 0){
            //var_dump($comparison);
            asort($comparison);
            //key 数组
            $u_list = array_keys($comparison);
            //使用时间最短的 即获取第一个
            $user_id = $u_list[0];
        }else{
            $user_id = 0;
        }
        
        return $user_id;
    }

    /*
     * 计算送餐员达到店铺取餐时间
     * 先根据距离测算
     * 再算店铺出餐时间
     * 两个值相比较 选取更大的那个值
     */
    public function take_meal_time($user,$order){
        //到达老店铺时间 配送员当前位置 到老店铺位置
        $old_store_time = $this->getDistanceTime($user['lat'],$user['lng'],$order['from_lat'],$order['from_lnt']);
        //老店铺出餐时间
        $chu_time = $order['create_time'] + ($order['dining_time']+self::DINING_ADD_TIME) * 60;
        //到达老店铺时间
        $dao_time = time() + $old_store_time*60;
        //如果到达老店铺时间比老店铺出餐时间段 那么该值 使用出餐时间
        if($dao_time > $chu_time){
            return $old_store_time;
        }else{
            return $order['dining_time']+self::DINING_ADD_TIME;
        }
    }

    public function getDistanceTime($from_lat,$from_lng,$aim_lat,$aim_lng){
        //获取两点之间的距离
        $distance = getDistance($from_lat,$from_lng,$aim_lat,$aim_lng);
        //获取预计到达时间
        $use_time = $distance / 100;
        //返回值为分钟
        return $use_time;
    }

    private function sendMsg($uid){
        $deliver = D('Deliver_user')->field(true)->where(array('uid'=>$uid))->find();
        if($deliver['device_id'] && $deliver['device_id'] != ''){
            $message = 'There is a new order for you to pick up. Please go to “Pending List” to take the order.';
            Sms::sendMessageToGoogle($deliver['device_id'],$message,3);
        }else{
            $sms_data = [
                'mobile' => $deliver['phone'],
//            'tplid' => 86914,
                'tplid'=>247173,
                'params' => [],
                'content' => '有一个新的订单可以配送，请前往个人中心抢单。'
            ];
            //Sms::sendSms2($sms_data);
            $sms_txt = "There is a new order for you to pick up. Please go to “Pending List” to take the order.";
            //Sms::telesign_send_sms($deliver['phone'],$sms_txt,0);
            Sms::sendTwilioSms($deliver['phone'],$sms_txt);
        }
    }

//    public function getDistance($from,$aim){
//        $url = 'http://54.190.29.18/index.php?g=Api&c=Index&a=testDistance&from='.$from.'&aim='.$aim;
//
//        import('ORG.Net.Http');
//        $http = new Http();
//        $result = $http->curlGet($url);
//
//        return json_decode($result,true);
//    }
}