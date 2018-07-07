<?php
class LibraryAction extends BaseAction{
      public function express_service_list(){
          if(!$this->user_session){
                $this->error_tips('请先进行登录',U('Login/index'));
            }
          $village_id = $_GET['village_id'] + 0;
          
          $this->get_village($village_id);
          $database_house_village_express = D('House_village_express');

          $has_express_service = $this->getHasConfig($village_id, 'has_express_service');
          if($has_express_service){
                $where['village_id'] = $village_id;
                $where['uid'] = $this->user_session['uid'];
                $list = $database_house_village_express->express_service_list($where);
                $express_config = M('House_village_express_config')->where(array('village_id'=>$village_id))->find();
                $express_config['status'] = $express_config['status'];
                $this->assign('list',$list['list']);
                $this->assign('express_config',$express_config);
                $this->display();
          }else{
              $this->error_tips('小区未开通相关服务！');
          }
      }

    public function ajax_express_appoint(){
        if(IS_AJAX){

            if (empty($this->user_session)) {
                exit(json_encode(array('status'=>0,'info'=>'未获取到您的帐号信息，请重试')));
            }


            $now_user = D('User')->get_user($this->user_session['uid']);
            if(empty($now_user)){
                exit(json_encode(array('status'=>0,'info'=>'未获取到您的帐号信息，请重试')));
            }

            $use_money = $_POST['express_collection_price'] + 0;


            $database_house_village_express_order = D('House_village_express_order');

            $order_where['express_id'] = $_POST['express_id'] + 0;
            $order_where['paid'] = 0;
            $now_order = $database_house_village_express_order->house_village_express_order_detail($order_where);
            $now_order = $now_order['detail'];
            $express_config =M('House_village_express_config')->where(array('village_id'=>$_POST['village_id']))->find();
            $time = strtotime($_POST['send_time']);
            $start_time = strtotime(date('G:m:i',$express_config['start_time']));
            $end_time = strtotime(date('G:m:i',$express_config['end_time']));

            if(empty($_POST['send_time'])){
                exit(json_encode(array('status'=>1,'info'=>'预约时间不能为空！')));
            }
            if($time<$start_time){
                exit(json_encode(array('status'=>1,'info'=>'预约时间不能比当前时间小')));
            }

            if($time>$end_time){
                exit(json_encode(array('status'=>1,'info'=>'该时间段物业不送货上门！')));
            }

            if($now_order){
                if($use_money != 0){
                    if($now_user['now_money'] < $use_money){
                        exit(json_encode(array('order_id'=> $now_order['order_id'],'status'=>-4,'info'=>'您的帐户余额为 <span>'.$now_user['now_money'].'</span> 元，请先充值帐户余额','recharge'=>$use_money-$now_user['now_money'])));
                    }
                    $save_result = D('User')->user_money($now_user['uid'],$use_money,'快递送达。');
                    if($save_result['error_code']){
                        exit(json_encode(array('status'=>0,'info'=>$save_result['error_code'])));
                    }

                    $data['paid'] = 1;
                    $data['pay_time'] = time();

                    $edit_where['order_id'] = $now_order['order_id'];
                    $result = $database_house_village_express_order->house_village_express_order_edit($edit_where , $data);

                    if(!$result){
                        exit(json_encode(array('status'=>0,'info'=>'数据处理有误！')));
                    }else{
                        if($result['status']){
                            exit(json_encode(array('status'=>1,'info'=>'付费成功！')));
                        }else{
                            exit(json_encode(array('status'=>0,'info'=>'付费失败！')));
                        }
                    }
                }else{
                    $data['paid'] = 1;
                    $data['pay_time'] = time();

                    $edit_where['order_id'] = $now_order['order_id'];
                    $result = $database_house_village_express_order->house_village_express_order_edit($edit_where , $data);
                    exit(json_encode(array('status'=>1,'info'=>'提交成功！')));
                }
            }

            $result = $database_house_village_express_order->house_village_express_order_add($_POST);
            if(!$result){
                exit(json_encode(array('status'=>0,'info'=>'数据处理有误！')));
            }else{
                if($result['status']){
                    if($use_money != 0){
                        if($now_user['now_money'] < $use_money){
                            exit(json_encode(array('order_id'=>$database_house_village_express_order->getLastInsID(),'status'=>-4,'info'=>'您的帐户余额为 <span>'.$now_user['now_money'].'</span> 元，请先充值帐户余额','recharge'=>$use_money-$now_user['now_money'])));
                        }
                        $save_result = D('User')->user_money($now_user['uid'],$use_money,'快递送达。');

                        if($save_result['error_code']){
                            exit(json_encode(array('status'=>0,'info'=>$save_result['error_code'])));
                        }

                        $data['paid'] = 1;
                        $data['pay_time'] = time();

                        $edit_where['order_id'] = $result['order_id'];
                        $result = $database_house_village_express_order->house_village_express_order_edit($edit_where , $data);

                        if(!$result){
                            exit(json_encode(array('status'=>0,'info'=>'数据处理有误！')));
                        }else{
                            if($result['status']){
                                exit(json_encode(array('status'=>1,'info'=>'付费成功！')));
                            }else{
                                exit(json_encode(array('status'=>1,'info'=>'付费失败！')));
                            }
                        }
                        exit(json_encode(array('status'=>1,'info'=>$result['msg'])));
                    }else{
                        $data['paid'] = 1;
                        $data['pay_time'] = time();

                        $edit_where['order_id'] = $result['order_id'];
                        $result = $database_house_village_express_order->house_village_express_order_edit($edit_where , $data);
                        exit(json_encode(array('status'=>1,'info'=>'提交成功！')));
                    }

                }else{
                    exit(json_encode(array('status'=>0,'info'=>$result['msg'])));
                }
            }
        }else{
            $this->error_tips('访问页面有误！');
        }
    }

    public function express_submit(){
        $order_id = $_GET['order_id'];
        $database_house_village_express_order = D('House_village_express_order');
        $where['order_id'] = $order_id;
        $order_info = $database_house_village_express_order->house_village_express_order_detail($where);
        $order_info = $order_info['detail'];

        if($order_info['paid'] > 0){
            $this->error_tips('该订单已支付。');
        }

        if(empty($this->user_session)){
            $this->error_tips('请先进行登录',U('Login/index'));
        }

        $now_user = D('User')->get_user($this->user_session['uid']);
        if(empty($now_user)){
            $this->error_tips('未获取到您的帐号信息，请重试');
        }

        $use_money = $order_info['express_collection_price'];
        if($now_user['now_money'] < $use_money){
            $this->error_tips('您的帐户余额为 <span>'.$now_user['now_money'].'</span> 元，请先充值帐户余额');
        }
        $save_result = D('User')->user_money($now_user['uid'],$use_money,'快递送达。');
        if($save_result['error_code']){
            $this->error_tips($save_result['error_code']);
        }

        $data['paid'] = 1;
        $data['pay_time'] = time();

        $edit_where['order_id'] = $order_id;
        $result = $database_house_village_express_order->house_village_express_order_edit($edit_where , $data);
        if(!$result){
            $this->error_tips('数据处理有误！');
        }else{
            if($result['status']){
                $village_user['uid'] = $now_user['uid'];
                $village_user['village_id'] = $order_info['village_id'];

                $bind_user = D('House_village_user_bind')->house_village_user_bind_detail($village_user);

                $data_village_order['order_name'] = '快递代收';
                $data_village_order['order_type'] = 'express';
                $data_village_order['uid'] = $now_user['uid'];
                $data_village_order['village_id'] = $order_info['village_id'];
                $data_village_order['money'] = $order_info['express_collection_price'];
                $data_village_order['time'] = $data['pay_time'];
                $data_village_order['pay_time'] = $data['pay_time'];
                $data_village_order['paid'] = 1;
                $data_village_order['third_id'] =  $order_id;
                $data_village_order['check_time'] = $data['pay_time'];
                $data_village_order['bind_id'] = $bind_user['info']['pigcms_id'];
                $data_village_order['pay_type'] = 2; //在线支付
                M('House_village_pay_order')->add($data_village_order);
                $this->success_tips('付费成功！',U('Library/express_service_list',array('village_id'=>$village_user['village_id'])));
            }else{
                $this->error_tips('付费失败！');
            }
        }


    }

        public function express_appoint(){
            $village_id = $_GET['village_id'] + 0;
            $this->get_village($village_id);

            $datatabase_house_village_express_order = D('House_village_express_order');

            $where['express_id'] = $_GET['id'] + 0;
            $now_order = $datatabase_house_village_express_order->house_village_express_order_detail($where);

            $now_order  = $now_order['detail'];

            $now_express = M('House_village_express')->where(array('id'=>$_GET['id']))->find();
            if($now_order){
                if($now_order['paid'] > 0){
                    $this->error_tips('订单已支付。请您耐心等待。');
                }
            }
            $express_config = M('House_village_express_config')->where(array('village_id'=>$village_id))->find();
            $this->assign('express_config',$express_config);
            $this->assign('now_express',$now_express);
            $this->display();
        }

    public function submit(){
        if (empty($this->user_session)) {
            exit(json_encode(array('status'=>0,'info'=>'未获取到您的帐号信息，请重试')));
        }
        $now_user = D('User')->get_user($this->user_session['uid']);
        if(empty($now_user)){
            exit(json_encode(array('status'=>0,'info'=>'未获取到您的帐号信息，请重试')));
        }

        $use_money = $_POST['express_collection_price'] + 0;

        if($use_money != 0){
            if($now_user['now_money'] < $use_money){
                exit(json_encode(array('status'=>-4,'info'=>'您的帐户余额为 <span>'.$now_user['now_money'].'</span> 元，请先充值帐户余额','recharge'=>$use_money-$now_user['now_money'])));
            }
            $save_result = D('User')->user_money($now_user['uid'],$use_money,'快递代送');
            if($save_result['error_code']){
                exit(json_encode(array('status'=>0,'info'=>$save_result['error_code'])));
            }
        }
    }
      
      public function visitor_list(){
           if(!$this->user_session){
                $this->error_tips('请先进行登录',U('Login/index'));
           } 


          $database_house_village_visitor = D('House_village_visitor');

          $village_id = $_GET['village_id'] + 0;
          $this->get_village($village_id);
          $has_visitor = $this->getHasConfig($village_id, 'has_visitor');
          if($has_visitor){
            $where['village_id'] = $village_id;
            $where['owner_uid'] = $this->user_session['uid'];
            $list = $database_house_village_visitor->house_village_visitor_list($where);
            if(!$list){
                $this->error('数据处理有误！');
            }else{
                $this->assign('list',$list['list']);
                $this->assign('visitor_type',$database_house_village_visitor->visitor_type);
            }
            $this->display();
          }else{
              $this->error_tips('小区未开通相关服务！');
          }
          
       }
       
       public function express_edit(){
          $database_house_village_express = D('House_village_express');
          if(IS_POST){
                $id = $_POST['id'] + 0;
                $status = $_POST['status'] + 0;
                if(!$id || !$status){
                    $this->error('传递参数有误！');
                }

                $where['id'] = $order_where['express_id'] = $id;
                $data['status'] = $status;

                $database_house_village_express_order = D('House_village_express_order');
                $database_house_village_express_order->house_village_express_order_edit($order_where , $data);
                $result = $database_house_village_express->house_village_express_edit($where,$data);
                if(!$result){
                    exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
                }else{
                    if($status==1){
                        $express_config =M('House_village_express_config')->where(array('village_id'=>$_POST['village_id']))->find();
                        $order_info = M('House_village_express')->field('e.id,o.send_time,u.name,ex.name as express_name , e.express_no,v.village_name')->join('as e LEFT JOIN '.C('DB_PREFIX').'house_village_express_order  o ON e.id = o.express_id left join '.C('DB_PREFIX').'express ex ON e.express_type = ex.id left join '.C('DB_PREFIX').'house_village v ON e.village_id = v.village_id left join  '.C('DB_PREFIX').'house_village_user_bind u ON e.uid = u.uid')->where(array('e.village_id'=>$_POST['village_id'],'e.id'=>$id))->find();
                        if ($this->config['village_sms']&&$order_info['send_time']>0) {
                            $sms_data = array('mer_id' => $express_config['village_id'],'store_id'=>0,'type' => 'village_express');
                            $sms_data['uid'] = 0;
                            $sms_data['mobile'] = $express_config['notice_phone'];
                            $sms_data['sendto'] = 'village';
                            $sms_data['content'] = '【提醒】您好，'.$order_info['village_name'].$order_info['name'].'业主，已预约快递代送服务，代送时间:'.date('Y-m-d H:i',$order_info['send_time']).'，'.$order_info['express_name'].'，单号：'.$order_info['express_no'].'。['.$this->config['site_name'].']';
                            fdump($sms_data);
                            Sms::sendSms($sms_data);
                        }
                    }
                    exit(json_encode(array('status'=>$result['status'],'msg'=>$result['msg'])));
                }
          }
      }
      
      
        public function chk_visitor_info(){
              $id = $_POST['id'] + 0;
              $status = $_POST['status'] + 0;
              if(!$id){
                  $this->error('传递参数有误！');
              }
              $database_house_village_visitor = D('House_village_visitor');
              $where['id'] = $id;
              $data['status'] = $status;
              $result = $database_house_village_visitor->house_village_visitor_edit($where,$data);
              if(!$result){
                  exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
              }else{
                  exit(json_encode(array('status'=>$result['status'],'msg'=>$result['msg'])));
              }
          }
      
      
      private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
     }
     
     private function get_village($village_id){
		$now_village = D('House_village')->get_one($village_id);
		if(empty($now_village)){
			$this->error_tips('当前访问的小区不存在或未开放');
		}
		$this->assign('now_village',$now_village);
		return $now_village;
	}
}
?>