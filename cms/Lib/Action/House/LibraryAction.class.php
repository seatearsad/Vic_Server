<?php
class LibraryAction extends BaseAction{
    protected $village_id;

    public function _initialize(){
        parent::_initialize();
        $this->village_id = $this->house_session['village_id'];
    }

    public function express_service_list(){
        $village_id = $this->house_session['village_id'];
        $database_house_village_express = D('House_village_express');

        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');
        $this->assign('has_express_service',$has_express_service);

        if($has_express_service){
            $where['village_id'] = $village_id;
            $list = $database_house_village_express->express_service_page_list($where);
            if(!$list){
                $this->error('处理数据有误！');
            }else{
                $this->assign('list',$list['list']);
            }
        }

        $this->display();
    }

    public function express_add(){
        $village_id = $this->house_session['village_id'];
        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');

        if($has_express_service){
            $database_house_village_express = D('House_village_express');
            $database_express = D('Express');

            if(IS_POST){
                $where['phone'] = $_POST['phone'];
                $where['village_id'] = $village_id;

//                $bind_user = D('House_village_user_bind')->house_village_user_bind_detail($where);
//                if($bind_user['status']==0){
//                    $this->error($bind_user['info']);
//                }

                $result = $database_house_village_express->village_express_add($_POST);
				
                if(!$result){
                    $this->error('数据处理有误！');
                }else{
                    if($result['status']){
                        $this->success($result['msg']);
                    }else{
                        $this->error($result['msg']);
                    }
                }
            }else{
                $express_list = $database_express->get_express_list();
                $config = M('House_village_express_config')->where(array('village_id'=>$village_id))->find();
                $express_money_status = $config['status'];
                $this->assign('config',$config);
                $this->assign('express_list',$express_list);
                $this->assign('express_money_status',$express_money_status);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }

    public function ajax_get_unit(){
		if(IS_AJAX){
			$village_id = $this->house_session['village_id'];
			$phone = $_POST['phone'];
			$floor_list = M('House_village_user_bind')->field('u.address,f.floor_id,f.floor_name')->join('as u LEFT JOIN '.C('DB_PREFIX').'house_village_floor f ON u.floor_id = f.floor_id')->where(array('u.phone'=>$phone,'u.status'=>1,'u.village_id'=>$village_id))->select();
			if(!empty($floor_list)){
	
				echo json_encode(array('status'=>1,'floor_list'=>$floor_list));exit;
			}else{
				echo json_encode(array('status'=>0,'floor_list'=>$floor_list));exit;
			}
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
            $database_house_village_express_order->house_village_express_order_edit($order_where,$data);

            $result = $database_house_village_express->house_village_express_edit($where,$data);
            if(!$result){
                exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
            }else{
                exit(json_encode(array('status'=>$result['status'],'msg'=>$result['msg'])));
            }
        }
    }

    public function express_del(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_express = D('House_village_express');
        $where['id'] = $id;
        $result = $database_house_village_express->village_express_del($where);
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }
    }


    public function express_detail(){
        $id = $_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }

        $database_house_village_express = D('House_village_express');
        $where['id'] = $id;
        $detail = $database_house_village_express->house_village_express_detail($where);
        if(!$detail){
            $this->error('数据处理有误！');
        }else{
            $this->assign('detail',$detail['detail']);
        }
        $this->display();
    }

    public function express_search(){
        $village_id = $this->house_session['village_id'];
        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');

        if($has_express_service){
            $database_house_village_express = D('House_village_express');
            $database_express = D('Express');
            if(IS_POST){
                $keyword = $_POST['keyword'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];

                if($keyword){
                    $where['phone|express_no'] = array('like','%'.$keyword.'%');
                }

                if($start_time && $end_time){
                    $start_time = strtotime($start_time);
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('between',array($start_time,$end_time));
                }else if($start_time){
                    $start_time = strtotime($start_time);
                    $where['add_time'] = array('egt',$start_time);
                }else if($end_time){
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('lt',$end_time);
                }
                $result = $database_house_village_express->ajax_vllage_express_search($where);
                exit(json_encode($result));
            }else{
                $express_list = $database_express->get_express_list();
                $this->assign('express_list',$express_list);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }

    public function visitor_list(){
        $database_house_village_visitor = D('House_village_visitor');
        $database_house_village = D('House_village');

        $village_id =  $this->house_session['village_id'];
        $has_visitor = $this->getHasConfig($village_id, 'has_visitor');
        $this->assign('has_visitor',$has_visitor);
        if($has_visitor){
            $where['village_id'] = $village_id;
            $list = $database_house_village_visitor->house_village_visitor_page_list($where);
            if(!$list){
                $this->error('数据处理有误！');
            }else{
                $this->assign('list',$list['list']);
                $this->assign('visitor_type',$database_house_village_visitor->visitor_type);
            }
        }
        $this->display();
    }

    public function visitor_add(){
        $village_id = $this->house_session['village_id'];
        $has_visitor = $this->getHasConfig($village_id, 'has_visitor');
        if($has_visitor){
            $database_house_village_visitor = D('House_village_visitor');
            if(IS_POST){
                $result =$database_house_village_visitor->house_village_visitor_add($_POST);
                if(!$result){
                    $this->error('数据处理有误！');
                }else{
                    if($result['status']){
                        $this->success($result['msg']);
                    }else{
                        $this->error($result['msg']);
                    }
                }
            }else{
                $visitor_type = $database_house_village_visitor->visitor_type;
                $this->assign('visitor_type' , $visitor_type);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }

    public function visitor_del(){
        $id =$_GET['id'] + 0;
        if(!$id){
            $this->error('传递参数有误！');
        }
        $database_house_village_visitor = D('House_village_visitor');
        $where['id'] = $id;
        $result = $database_house_village_visitor->house_village_visitor_del($where);
        if(!$result){
            $this->error('数据处理有误！');
        }else{
            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
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

    public function visitor_detail(){
        $id = $_GET['id'] + 0;

        if(!$id){
            $this->error('传递参数有误！');
        }
        $database_house_village_visitor = D('House_village_visitor');
        $where['id'] = $id;
        $detail = $database_house_village_visitor->house_village_visitor_detail($where);

        if(!$detail['status']){
            $this->error('该信息不存在！');
        }
        $this->assign('detail',$detail['detail']);
        $this->display();
    }


    public function visitor_search(){
        $village_id = $this->house_session['village_id'];
        $has_visitor = $this->getHasConfig($village_id, 'has_visitor');

        if($has_visitor){
            $database_house_village_visitor = D('House_village_visitor');
            if(IS_POST){
                $visitor_keyword = $_POST['visitor_keyword'];
                $owner_keyword = $_POST['owner_keyword'];
                $start_time = $_POST['start_time'];
                $end_time = $_POST['end_time'];
                $visitor_type = $_POST['visitor_type'];

                if($visitor_keyword){
                    $where['visitor_name|visitor_phone'] = array('like','%'.$visitor_keyword.'%');
                }

                if($owner_keyword){
                    $where['owner_name|owner_phone'] = array('like','%'.$owner_keyword.'%');
                }

                if($start_time && $end_time){
                    $start_time = strtotime($start_time);
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('between',array($start_time,$end_time));
                }else if($start_time){
                    $start_time = strtotime($start_time);
                    $where['add_time'] = array('egt',$start_time);
                }else if($end_time){
                    $end_time = strtotime($end_time.'23:59:59');
                    $where['add_time'] = array('lt',$end_time);
                }

                if($visitor_type){
                    $where['visitor_type'] = $visitor_type;
                }
                $result = $database_house_village_visitor->ajax_house_village_visitor_search($where);
                exit(json_encode($result));
            }else{
                $this->assign('visitor_type',$database_house_village_visitor->visitor_type);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }

    }


    public function ajax_get_owner_info(){
        $database_house_village_user_bind = D('House_village_user_bind');
        $owner_phone = $_POST['owner_phone'];
        $village_id = $this->house_session['village_id'];
        $where['village_id'] = $village_id;
        $where['phone'] = $owner_phone;
        $where['parent_id'] = 0;

        $field = array('pigcms_id' , 'name' , 'address');
        $result = $database_house_village_user_bind->house_village_user_bind_detail($where , $field);
        if(!$result){
            exit(json_encode('数据处理有误！'));
        }else{
            exit(json_encode($result));
        }
    }


    public function owner_arrival(){
        $this->display();
    }

    public function owner_arrival_add(){
        if(IS_POST){
            $database_house_village_property = D('House_village_property');
            $database_house_village_user_bind = D('House_village_user_bind');
            $database_house_village_floor = D('House_village_floor');

            $bind_where['usernum'] = $_POST['usernum'];
            $now_bind_info = $database_house_village_user_bind->where($bind_where)->find();

            if(!$now_bind_info){
                $this->error('该物业编号不存在！');
            }

            $now_floor_info = $database_house_village_floor->get_floor_info($now_bind_info['floor_id']);

            $property_where['id'] = $_POST['property_id'] + 0;
            $now_property_info = $database_house_village_property->house_village_property_detail($property_where);
            $now_property_info = $now_property_info['detail'];

            if(!$now_property_info){
                $this->error('物业缴费周期不存在！');
            }

            $data['order_name'] = '缴纳物业费';
            $data['order_type'] = 'property';
            $data['village_id'] = $this->house_session['village_id'];
            $data['time'] = time();
            $data['property_month_num'] = $now_property_info['property_month_num'];
            $data['floor_type_name'] = $now_floor_info['name'] ? $now_floor_info['name'] : '';
            $data['house_size'] = $now_bind_info['housesize'];
            $data['bind_id'] = $now_bind_info['pigcms_id'];
            $data['uid'] = $now_bind_info['uid'];
            $data['diy_type'] = $now_property_info['diy_type'];
            if($now_property_info['diy_type'] > 0){
                $data['diy_content'] = $now_property_info['diy_content'];
            }else{
                $data['presented_property_month_num'] = $now_property_info['presented_property_month_num'] ? $now_property_info['presented_property_month_num'] : 0;
            }

            if(($now_floor_info['property_fee'] != '0.00') && isset($now_floor_info['property_fee'])){
                $data['money'] = $now_floor_info['property_fee'] * $now_bind_info['housesize'] * $now_property_info['property_month_num'];
                $data['property_fee'] = $now_floor_info['property_fee'];
            }else{
                $data['money'] = $this->house_session['property_price'] * $now_bind_info['housesize'] * $now_property_info['property_month_num'];
                $data['property_fee'] = $this->house_session['property_price'];
            }

            $order_id = M("House_village_pay_order")->add($data);
            if($order_id){
                $this->success('添加成功',U('owner_arrival_order',array('order_id'=>$order_id)));
            }else{
                $this->error('订单创建失败，请重试');
            }
        }else{
            $database_house_village_property = D('House_village_property');
            $where['village_id'] = $_SESSION['house']['village_id'];
            $where['status'] = 1;
			
		
			
            $list = $database_house_village_property->house_village_proerty_page_list($where , true , 'property_month_num desc' , 99999);

            if(!$list){
                $this->error_tips('数据处理有误！');
            }else{
                if($list['status']){
                    $this->assign('list' , $list['list']);
                }else{
                    $this->error_tips('请先添加缴费优惠。',U('Unit/preferential_add'));
                }
            }
            $this->display();
        }
    }

    public function ajax_user_list(){
        if(IS_JAX){
            $find_type = $_POST['find_type'];
            $find_value = $_POST['find_value'];

            if ($find_value) {
                if ($find_type == 1) {
                    $where['name'] = array('like', '%' . $find_value . '%');
                } else if ($find_type == 2) {
                    $where['phone'] = array('like', '%' . $find_value . '%');
                } else if ($find_type == 3) {
                    $where['usernum'] = array('like', '%' . $find_value . '%');
                }
            }

            $village_id = $this->village_id;
            if (empty($where)) {
                $user_list = D('House_village_user_bind')->get_limit_list_page($village_id);
            } else {
                $user_list = D('House_village_user_bind')->get_limit_list_page($village_id, 99999, $where);
            }

            $user_list = $user_list['user_list'];
            if($user_list){
                exit(json_encode(array('status'=>1,'user_list'=>$user_list)));
            }else{
                exit(json_encode(array('status'=>0,'user_list'=>$user_list)));
            }
        }else{
            $this->error_tips('访问页面有误！');
        }
    }

    public function owner_arrival_order(){
        $order_id = $_GET['order_id'] + 0;
        if(!$order_id){
            $this->error('传递参数有误！');
        }

        $database_house_village_pay_order = D('House_village_pay_order');

        $now_order = $database_house_village_pay_order->get_one($order_id);
        if($now_order['paid'] > 0){
            $this->error('该订单已支付。',U('Unit/pay_order'));
        }

        $this->assign('now_order',$now_order);
        $this->display();
    }

    public function chk_cash(){
        $order_id = $_GET['order_id'] + 0;
        if(!$order_id){
            $this->error('传递参数有误！');
        }

        $database_house_village_pay_order = D('House_village_pay_order');
        $now_order = $database_house_village_pay_order->get_one($order_id);

        if($now_order['paid'] > 0){
            $this->error('该订单已经支付！');
        }

        $data['paid'] = 1;
        $data['pay_time'] = time();
        $data['pay_type'] = 1;
        $result = $database_house_village_pay_order->where(array('order_id'=>$order_id))->data($data)->save();

        if($result){
            $database_house_village_property_paylist = D('House_village_property_paylist');

            $paylist_data['bind_id'] = $now_order['bind_id'];
            $paylist_data['uid'] = $now_order['uid'];
            $paylist_data['village_id'] = $now_order['village_id'];
            $paylist_data['property_month_num'] = $now_order['property_month_num'] + 0;
            $paylist_data['presented_property_month_num'] = $now_order['presented_property_month_num'] + 0;
            $paylist_data['house_size'] = $now_order['house_size'];
            $paylist_data['property_fee'] = $now_order['property_fee'];
            $paylist_data['floor_type_name'] = $now_order['floor_type_name'];

            $now_user_info = D('House_village_user_bind')->get_one_by_bindId($now_order['bind_id']);
            $now_pay_info = $database_house_village_property_paylist->where(array('bind_id'=>$now_order['bind_id']))->order('add_time desc')->find();
            if(!empty($now_pay_info)){
                $paylist_data['start_time'] = $now_pay_info['end_time'] ;
                $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_pay_info['end_time']);
            }else{
                if($now_user_info['add_time'] > 0){
                    $paylist_data['start_time'] = $now_user_info['add_time'] ;
                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", $now_user_info['add_time']);
                }else{
                    $paylist_data['start_time'] = time();
                    $paylist_data['end_time'] = strtotime("+" .($paylist_data['property_month_num'] + $paylist_data['presented_property_month_num']). " months", time());
                }

            }
            $paylist_data['add_time'] = time();
            $paylist_data['order_id'] = $order_id;
            $database_house_village_property_paylist->data($paylist_data)->add();

            $this->success('提交成功！',U('Unit/pay_order'));
        }else{
            $this->error('提交失败！');
        }
    }

    public function ajax_unit(){
        $database_house_village_floor = D('House_village_floor');
        $condition['village_id'] =  $this->village_id;
        $condition['status'] =  1;

        $unit_list = $database_house_village_floor->field(true)->group('floor_name')->where($condition)->select();
        if(count($unit_list) == 1){
            $return['error'] = 2;
            $return['id'] = $unit_list[0]['id'];
            $return['name'] = $unit_list[0]['floor_name'];
        }else if(!empty($unit_list)){
            $return['error'] = 0;
            $return['list'] = $unit_list;
        }else{
            $return['error'] = 1;
            $return['info'] = '没有已开启的单元！';
        }
        exit(json_encode($return));
    }


    public function ajax_floor(){
        $database_house_village_floor = D('House_village_floor');
        $condition['village_id'] =  $this->village_id;
        $condition['status'] =  1;
        $condition['floor_name'] =  $_POST['name'];
        $floor_list = $database_house_village_floor->where($condition)->select();

        if(count($floor_list) == 1 && !$_POST['type']){
            $return['error'] = 2;
            $return['id'] = $floor_list[0]['id'];
            $return['name'] = $floor_list[0]['name'];
        }else if(!empty($floor_list)){
            $return['error'] = 0;
            $return['list'] = $floor_list;
        }else{
            $return['error'] = 1;
            $return['info'] = $_POST['name'] .' 该单元下未有楼层！';
        }
        exit(json_encode($return));
    }

    public function ajax_layer(){
        $database_house_village_user_bind = D('House_village_user_bind');
        $condition['village_id'] =  $this->village_id;
        $condition['status'] =  1;
        $condition['floor_id'] = $_POST['id'] + 0;
        $condition['parent_id'] = 0;

        $layer_list = $database_house_village_user_bind->where($condition)->select();
        if(count($layer_list) == 1 && !$_POST['type']){
            $return['error'] = 2;
            $return['id'] = $layer_list[0]['id'];
            $return['name'] = $layer_list[0]['name'];
        }else if(!empty($layer_list)){
            $return['error'] = 0;
            $return['list'] = $layer_list;
        }else{
            $return['error'] = 1;
            $return['info'] = '未有业主';
        }
        exit(json_encode($return));
    }

    public function ajax_owner(){
        $database_house_village_user_bind = D('House_village_user_bind');
        $condition['village_id'] =  $this->village_id;
        $condition['pigcms_id'] = $_POST['id'] + 0;
        $condition['parent_id'] = 0;

        $owner_list = $database_house_village_user_bind->where($condition)->select();
        if(count($owner_list) == 1 && !$_POST['type']){
            $return['error'] = 2;
            $return['id'] = $owner_list[0]['id'];
            $return['name'] = $owner_list[0]['name'];
        }else if(!empty($owner_list)){
            $return['error'] = 0;
            $return['list'] = $owner_list;
        }else{
            $return['error'] = 1;
            $return['info'] = '未有业主';
        }
        exit(json_encode($return));
    }

    public function search_owner_info(){
        $pigcms_id = $_GET['owner_id'] + 0;

        if(!$pigcms_id){
            $this->error_tips('传递参数有误！');
        }

        $condition['pigcms_id'] = $pigcms_id;
        $database_house_village_user_bind = D('House_village_user_bind');
        $database_house_village_property_paylist = D('House_village_property_paylist');
        $now_bind_user = $database_house_village_user_bind->get_one($this->village_id,$pigcms_id,'pigcms_id');

        $condition_pay['bind_id'] = $pigcms_id;
        $now_bind_user['expire_time'] = $database_house_village_property_paylist->where($condition_pay)->order('add_time desc')->getField('end_time');
        if(!$now_bind_user){
            $this->error_tips('该业主不存在！');
        }else{
            $this->assign('now_bind_user' , $now_bind_user);
            $this->display();
        }
    }

    public function search_owner_pay_list(){
        $pigcms_id = $_GET['pigcms_id'];
        $where['bind_id'] = $pigcms_id;
        $where['village_id'] = $this->village_id;

        $database_house_village_property_paylist = D('House_village_property_paylist');
        $list = $database_house_village_property_paylist->where($where)->order('add_time asc')->select();
        $this->assign('list' , $list);
        $this->display();
    }


    public function ajax_property_info(){
        if(IS_JAX){
            $property_id = $_POST['property_id'] + 0;

            if(!$property_id){
                exit(json_encode(array('status'=>0,'msg'=>'传递参数有误！')));
            }

            $database_house_village_property = D('House_village_property');

            $where['id'] = $property_id;
            $where['village_id'] = $this->village_id;
            $where['status'] = 1;
            $detail = $database_house_village_property->house_village_property_detail($where);

            if(!$detail){
                exit(json_encode(array('status'=>0,'msg'=>'数据处理有误！')));
            }

            if($detail['status']){
                exit(json_encode(array('status'=>1,'detail'=>$detail['detail'])));
            }else{
                exit(json_encode(array('status'=>0,'detail'=>$detail['detail'])));
            }
        }else{
            $this->error_tips('访问页面有误！');
        }
    }


    private function getHasConfig($village_id,$field){
        $database_house_village = D('House_village');
        $house_village_info = $database_house_village->get_one($village_id,$field);
        $config_info = $house_village_info[$field];
        return $config_info;
    }

    //代送配置
    public function express_config(){

        $village_id = $this->house_session['village_id'];
        $has_express_service = $this->getHasConfig($village_id, 'has_express_service');

        if($has_express_service){
            $mode = D('House_village_express_config');
            if(IS_POST){
                $_POST['start_time'] = strtotime($_POST['start_time']);
                $_POST['end_time'] = strtotime($_POST['end_time']);
                if($_POST['start_time']>=$_POST['end_time']){
                    $this->error('起送时间不能比结束时间大');
                }
                if(!is_numeric($_POST['notice_phone'])){
                    $this->error('数据处理有误！');
                }
                if(!$mode->where(array('village_id'=>$village_id))->find()) {
                    $_POST['village_id'] = $village_id;
                    $result = $mode->add($_POST);
                }else {
                    $result = $mode->where(array('village_id'=>$village_id))->save($_POST);
                }

                if(!$result){
                    $this->error('数据处理有误！');
                }else{

                    $this->success('保存成功');

                }
            }else{
                $express_config = $mode->where(array('village_id'=>$village_id))->find();
                $this->assign('express_config',$express_config);
                $this->display();
            }
        }else{
            $this->error('非法访问！');
        }
    }

    public function index_nav(){
        $database_house_village_nav = D('House_village_nav');

        $where['village_id'] = $this->house_session['village_id'];
        $result = $database_house_village_nav->house_village_nav_page_list($where , true , 'sort desc');

        if(!$result){
            $this->error('数据处理有误！');
        }

        $this->assign('result',$result['result']);
        $this->display();
    }

    public function nav_add(){
        if(IS_POST){
            $_POST['url'] = htmlspecialchars_decode($_POST['url']);
            $_POST['add_time'] = time();
            $_POST['village_id'] = $this->house_session['village_id'];
            $database_house_village_nav = D('House_village_nav');
            $result = $database_house_village_nav->house_village_nav_add($_POST);

            if(!$result){
                $this->error('数据处理有误！');
            }

            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }else{
            $this->display();
        }
    }

    public function nav_edit(){
        $database_house_village_nav = D('House_village_nav');
        $where['id'] = $_GET['id'] + 0;

        if(IS_POST){
            $result  = $database_house_village_nav->house_village_nav_edit($where,$_POST);
            if(!$result){
                $this->error('信息处理有误！');
            }

            if($result['status']){
                $this->success($result['msg']);
            }else{
                $this->error($result['msg']);
            }
        }else{
            $result = $database_house_village_nav->house_village_nav_detail($where);
            if(!$result){
                $this->error('数据处理有误！');
            }

            $this->assign('detail',$result['detail']);
            $this->display();
        }
    }


    public function nav_del(){
        $database_house_village_nav = D('House_village_nav');
        $where['id'] = $_GET['id'] + 0;

        $result = $database_house_village_nav->house_village_nav_del($where);

        if(!$result){
            $this->error('数据处理有误！');
        }

        if($result['status']){
            $this->success($result['msg']);
        }else{
            $this->error($result['msg']);
        }
    }
}
?>

