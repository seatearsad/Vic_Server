<?php
/*
 * 店员中心
 */

class StoreAction extends BaseAction{
	protected $staff_session;
	protected $store;
	public function _initialize(){
		parent::_initialize();
		$this->staff_session = session('staff');
		
		if(ACTION_NAME != 'login' && ACTION_NAME != 'cashierBack'){
			if(empty($this->staff_session)){
				redirect(U('Store/login'));
				exit();
			}else{
				$this->assign('staff_session',$this->staff_session);
				$database_merchant_store = D('Merchant_store');
				$condition_merchant_store['store_id'] = $this->staff_session['store_id'];
				$this->store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
				if(empty($this->store)){
					$this->error('店铺不存在！');
				}
				$this->assign('store',$this->store);
			}
		}
	}
	public function login(){
		if(IS_POST){
			if(md5($_POST['verify']) != $_SESSION['merchant_store_login_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}
			
			$database_store_staff = D('Merchant_store_staff');
			$condition_store_staff['username'] = $_POST['account'];
			$now_staff = $database_store_staff->field(true)->where($condition_store_staff)->find();

			if(empty($now_staff)){
				exit(json_encode(array('error'=>'2','msg'=>'帐号不存在！','dom_id'=>'account')));
			}
			$pwd = md5($_POST['pwd']);
			if($pwd != $now_staff['password']){
				exit(json_encode(array('error'=>'3','msg'=>'密码错误！','dom_id'=>'pwd')));
			}
			$data_store_staff['id'] = $now_staff['id'];
			$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
			if($database_store_staff->data($data_store_staff)->save()){
				session('staff',$now_staff);
				exit(json_encode(array('error'=>'0','msg'=>'登录成功,现在跳转~','dom_id'=>'account')));
			}else{
				exit(json_encode(array('error'=>'6','msg'=>'登录信息保存失败,请重试！','dom_id'=>'account')));
			}
		}else{
			$this->display();
		}
	}
    public function index(){
		$this->display();
    	}
	public function coupon_list(){
		$store_id = $this->store['store_id'];
		$condition_where = "`ear`.`uid`=`u`.`uid` AND `ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `ecr`.`store_id`='$store_id'";

		$condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr',C('DB_PREFIX').'user'=>'u');
		$order_list = D('')->field('`eal`.`name`,`ecr`.*,`ear`.`time`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`ecr`.`check_time` DESC')->select();
		$this->assign('order_list',$order_list);
		$this->display();
	}
	public function coupon_find(){
		if(IS_POST){
			$mer_id = $this->store['mer_id'];
			$condition_where = "`ear`.`uid`=`u`.`uid` AND `ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `eal`.`mer_id`='$mer_id'";
			$find_value = $_POST['find_value'];
			$store_id = $this->store['store_id'];
			if($_POST['find_type'] == 1 && strlen($find_value) == 16){
				$condition_where .= " AND `ecr`.`number`='$find_value'";
			}else{
				$condition_where .= " AND `ecr`.`store_id`='$store_id'";
				if($_POST['find_type'] == 1){
					$condition_where .= " AND `ecr`.`number` like '$find_value%'";
				}else if($_POST['find_type'] == 2){
					$condition_where .= " AND `eal`.`pigcms_id` like '$find_value%'";
				}else if($_POST['find_type'] == 3){
					$condition_where .= " AND `u`.`uid`='$find_value'";
				}else if($_POST['find_type'] == 4){
					$condition_where .= " AND `u`.`nickname`='$find_value'";
				}else if($_POST['find_type'] == 5){
					$condition_where .= " AND `u`.`phone` like '$find_value%'";
				}
			}
			$condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr',C('DB_PREFIX').'user'=>'u');
			$order_list = D('')->field('`eal`.`name`,`ecr`.*,`ear`.`time`,`u`.`uid`,`u`.`nickname`,`u`.`phone`,`ecr`.`check_time`')->where($condition_where)->table($condition_table)->order('`ecr`.`check_time` DESC')->select();
			if($order_list){
				foreach($order_list as $key=>$value){
					$order_list[$key]['time_txt'] = date('Y-m-d H:i:s',$value['time']);
					$order_list[$key]['check_time_txt'] = date('Y-m-d H:i:s',$value['check_time']);
				}
			}
			$return['list'] = $order_list;
			$return['row_count'] = count($order_list);
			echo json_encode($return);
		}else{
			$this->display();
		}
	}
	public function coupon_verify(){
		$mer_id = $this->store['mer_id'];
		$condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr');
		$condition_where = "`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `eal`.`mer_id`='$mer_id' AND `ecr`.`pigcms_id`='{$_GET['id']}'";
		$now_order = D('')->field('`ecr`.`pigcms_id`,`eal`.`pigcms_id` as id,`eal`.`money`,`eal`.`name`')->where($condition_where)->table($condition_table)->find();
		if(!empty($now_order)){
			if(D('Extension_coupon_record')->where(array('pigcms_id'=>$now_order['pigcms_id']))->data(array('check_time'=>time(),'store_id'=>$this->store['store_id'],'last_staff'=>$this->staff_session['name']))->save()){
				//验证增加商家余额
				if($now_order['money']>0){
					$now_order['order_type'] ='coupon';
					$now_order['order_id'] =$now_order['pigcms_id'];
					$now_order['mer_id']  = $mer_id;
					D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);

					//商家推广分佣
					$now_user = M('User')->where(array('uid'=>$now_order['uid']))->find();
					D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户购买平台活动商品获得佣金');
				}
				$this->success('验证成功！');
			}else{
				$this->error('验证失败！请重试。');
			}
		}else{
			$this->error('当前订单不存在！');
		}
	}
	/* 团购相关 */
	protected function check_group(){
		if(empty($this->store['have_group'])){
			$this->error('您访问的店铺没有开通'.$this->config['group_alias_name'].'功能！');
		}
	}
	public function group_list(){
		$this->check_group();
		$store_id = $this->store['store_id'];

		$condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`store_id`='$store_id'";

		$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'user'=>'u');

		$order_count = D('')->where($condition_where)->table($condition_table)->count();
		
		import('@.ORG.merchant_page');
		$p = new Page($order_count, 15);
		
		$order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
		

		$this->assign('order_list',$order_list);
		$this->assign('pagebar',$p->show());

		$this->display();
	}
	public function group_find(){
		if(IS_POST){
			$mer_id = $this->store['mer_id'];
			$condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`mer_id`='$mer_id'";
			$find_value = $_POST['find_value'];
			$store_id = $this->store['store_id'];
			if($_POST['find_type'] == 1 && strlen($find_value) == 14){
				$res = D('Group_pass_relation')->get_orderid_by_pass($find_value);
				if(!empty($res)){
					$condition_where .= " AND `o`.`order_id`=".$res['order_id'];
				}else{
					$condition_where .= " AND `o`.`group_pass`='$find_value'";
				}
			}else{
				$condition_where .= " AND `o`.`store_id`='$store_id'";
				if($_POST['find_type'] == 1){
					$condition_where .= " AND `o`.`group_pass` like '$find_value%'";
				}else if($_POST['find_type'] == 2){
					$condition_where .= " AND `o`.`express_id` like '$find_value%'";
				}else if($_POST['find_type'] == 3){
					$condition_where .= " AND `o`.`real_orderid`='$find_value'";
				}else if($_POST['find_type'] == 4){
					$condition_where .= " AND `o`.`group_id`='$find_value'";
				}else if($_POST['find_type'] == 5){
					$condition_where .= " AND `o`.`uid`='$find_value'";
				}else if($_POST['find_type'] == 6){
					$condition_where .= " AND `u`.`nickname` like '$find_value%'";
				}else if($_POST['find_type'] == 7){
					$condition_where .= " AND `o`.`phone` like '$find_value%'";
				}
			}
			$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'user'=>'u');
			$order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->select();
			if($order_list){
				foreach($order_list as $key=>$value){
//					$value['coupon_price'] = D('Group_order')->get_coupon_info($value['order_id']);
//					$order_list[$key]['un_pay'] =$value['total_money']-$value['wx_cheap']-$value['merchant_balance']-$value['balance_pay']-$value['score_deducte']-$value['coupon_price'];
//					$order_list[$key]['un_pay_num'] = $value['num']-round(($value['total_money']-$order_list[$key]['un_pay'])/$value['price']);
					//$order_list[$key]['un_pay_num'] = D('Group_pass_relation')->get_pass_num($value['order_id'],3);
					$order_list[$key]['add_time'] = date('Y-m-d H:i:s',$value['add_time']);
					$order_list[$key]['pay_time'] = date('Y-m-d H:i:s',$value['pay_time']);
					$order_list[$key]['use_time'] = !empty($value['use_time']) ? date('Y-m-d H:i:s',$value['use_time']):'';
				}
			}

			$return['list'] = $order_list;
			$return['row_count'] = count($order_list);
			echo json_encode($return);
		}else{
			$this->check_group();
			$this->display();
		}
	}
	public function group_verify(){
		$this->check_group();
		$database_group_order = D('Group_order');
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}
		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else if($now_order['paid'] && $now_order['status'] == 0){
			$condition_group_order['order_id'] = $now_order['order_id'];
			if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
				$data_group_order['third_id'] = $now_order['order_id'];
			}
			$data_group_order['status'] = '1';
			$data_group_order['store_id'] = $this->store['store_id'];
			$data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['last_staff'] = $this->staff_session['name'];
			if($database_group_order->where($condition_group_order)->data($data_group_order)->save()){
				D('Group_pass_relation')->change_refund_status($now_order['order_id'],1);
				//验证增加商家余额
				$now_order['order_type'] = 'group';
				$now_order['verify_all'] = 1;
				$now_order['store_id'] =$this->store['store_id'];

				D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);

				$this->group_notice($now_order,1);
				$this->success('验证消费成功！');

			}else{
				$this->error('验证失败！请重试。');
			}
		}else{
			$this->error('当前订单的状态并不是未消费。');
		}
	}

	public function group_pass_array(){
		$this->check_group();
		$database_group_order = D('Group_order');
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		$now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
		$un_pay =$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price'];
		$has_pay = $now_order['total_money']-$un_pay;
		$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
		$un_consume_num  = D('Group_pass_relation')->get_pass_num($now_order['order_id'],0);
		foreach($pass_array as &$v){
			$v['need_pay'] = $has_pay>$now_order['price']?0:$now_order['price']-$has_pay;
			$has_pay=($has_pay-$now_order['price'])>0?$has_pay-$now_order['price']:0;
		}
		$this->assign('un_consume_num',$un_consume_num);
		$this->assign('pass_array',$pass_array);
		$this->assign('now_order',$now_order);
		$this->display();
	}

	public function group_array_verify(){
		$this->check_group();
		$database_group_order = D('Group_order');
		$verify_all = false;
		$now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_POST['order_id'],false);
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}
		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}
		if(empty($now_order)){
			$this->error('当前订单不存在！');
		}else{
			$where['order_id'] =$_POST['order_id'];
			$where['group_pass'] =$_POST['group_pass'];
			$group_pass_rela = D('Group_pass_relation');
			$res = $group_pass_rela->where($where)->find();
			if(!empty($res)){
				$date['status']=1;
				if($group_pass_rela->where($where)->data($date)->save()){
					$count = $group_pass_rela->get_pass_num($where['order_id']);
					$count += $group_pass_rela->get_pass_num($where['order_id'],3);

					if($count==0){
						if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
							$data_group_order['third_id'] = $now_order['order_id'];
						}
						$data_group_order['status'] = '1';
						$verify_all = true;
					}else{
						$now_order['res'] = $res;
					}
					$data_group_order['store_id'] = $this->store['store_id'];
					$now_order['store_id'] = $this->store['store_id'];
					$condition_group_order['order_id'] = $where['order_id'];
					$data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
					$data_group_order['last_staff'] = $this->staff_session['name'];
					if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
						//验证增加商家余额
						$now_order['order_type'] = 'group';
						$now_order['verify_all'] = 0;
						D('Merchant_money_list')->add_money($this->store['mer_id'],'验证团购订单'.$now_order['real_orderid'].'的消费码</br>'.$_POST['group_pass'].'记入收入',$now_order);
						$this->group_notice($now_order,$verify_all);
						$this->success('验证消费成功！');
					}else{
						$this->error('验证失败！请重试。');
					}
				}else{
					$this->error("验证消费成功！");
				}
			}else{
				exit('此消费码不存在！');
			}
		}
	}
	
	public function group_edit(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		
		if(empty($now_order)){
			exit('此订单不存在！');
		}
		if($now_order['tuan_type'] == 2 && $now_order['paid'] == 1){
			$express_list = D('Express')->get_express_list();
			$this->assign('express_list',$express_list);
		}
		if(!empty($now_order['pay_type'])){
			if($now_order['is_pick_in_store']){
				$now_order['paytypestr']="到店自提";
			}else{
				$now_order['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);
			}
			 if(($now_order['pay_type']=='offline') && !empty($now_order['third_id']) && ($now_order['paid']==1)){
			     $now_order['paytypestr'] .='<span style="color:green">&nbsp; 已支付</span>';
			 }else if(($now_order['pay_type']!='offline') && ($now_order['paid']==1)){
			     $now_order['paytypestr'] .='<span style="color:green">&nbsp; 已支付</span>';
			 }else{
			     $now_order['paytypestr'] .='<span style="color:red">&nbsp; 未支付</span>';
			 }
		}else{
		    $now_order['paytypestr'] = '未知';
		}
		if(!empty($now_order['coupon_id'])) {
			$system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$now_order['coupon_price'] = $system_coupon['price'];
			$this->assign('system_coupon',$system_coupon);
		}else if(!empty($now_order['card_id'])) {
			$card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$now_order['coupon_price'] = $card['price'];
			$this->assign('card', $card);
		}

		$pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
		$this->assign('pass_array',$pass_array);
		$this->assign('now_order',$now_order);
		$this->display();
	}
	public function group_express(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		if(empty($now_order)){
			$this->error('此订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}

		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}
		
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['express_type'] = $_POST['express_type'];
		$data_group_order['express_id'] = $_POST['express_id'];
		$data_group_order['last_staff'] = $this->staff_session['name'];
		if($now_order['paid'] == 1 && $now_order['status'] == 0){
			if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
				$data_group_order['third_id'] = $now_order['order_id'];
			}
			$data_group_order['status'] = 1;
			$data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['store_id'] = $this->store['store_id'];
		}
		
		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){

			//验证增加商家余额
			$now_order['order_type']='group';
			$now_order['verify_all']=1;
			$now_order['store_id'] =$this->store['store_id'];
			D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);

			$this->group_notice($now_order,1);
			$this->success('Success');
		}else{
			$this->error('修改失败！请重试。');
		}
	}

	public function group_pick(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],false);
		if(empty($now_order)){
			$this->error('此订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}


		if($now_order['status']!=0){
			$this->error('此订单尚不是未消费！');
		}

		$condition_group_order['order_id'] = $now_order['order_id'];
		$date['status']=1;
		$date['paid'] = 1;
		$date['last_staff'] = $this->staff_session['name'];
		$date['use_time'] = $_SERVER['REQUEST_TIME'];
		if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
			$date['third_id'] = $now_order['order_id'];
		}
		if(D('Group_order')->where($condition_group_order)->data($date)->save()){

			//验证增加商家余额
			$now_order['order_type']='group';
			$now_order['verify_all']=1;
			$now_order['store_id'] =$this->store['store_id'];
			D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);

			$this->group_notice($now_order,1);
			$this->success('Success');
		}else{
			$this->error('修改失败！请重试。');
		}
	}

	public function group_remark(){
		$this->check_group();
		$now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_GET['order_id'],true,false);
		if(empty($now_order)){
			$this->error('此订单不存在！');
		}
		if(empty($now_order['paid'])){
			$this->error('此订单尚未支付！');
		}
		$condition_group_order['order_id'] = $now_order['order_id'];
		$data_group_order['merchant_remark'] = $_POST['merchant_remark'];
		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
			$this->success('Success');
		}else{
			$this->error('修改失败！请重试。');
		}
	}

	
	/*检查是否开启订餐*/
	protected function check_meal(){
		if(empty($this->store['have_meal'])){
			$this->error('您访问的店铺没有开通'.$this->config['meal_alias_name'].'功能！');
		}
	}
	
	
	public function meal_list()
	{
		$this->check_meal();
		$store_id = intval($this->store['store_id']);
		$where = array();
		if (IS_POST) {
			$order_id = isset($_POST['order_id']) ? htmlspecialchars($_POST['order_id']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$meal_pass = isset($_POST['meal_pass']) ? htmlspecialchars($_POST['meal_pass']) : '';
			$table_name = isset($_POST['table_name']) ? htmlspecialchars($_POST['table_name']) : '';
			$order_id && $where['order_id'] = array('like', "%$order_id%");
			$name && $where['name'] = array('like', "%$name%");
			$phone && $where['phone'] = array('like', "%$phone%");
			$meal_pass && $where['meal_pass'] = array('like', "%$meal_pass%");
			if ($table_name) {
				$tables = D('Merchant_store_table')->where(array('name' => array('like', "%$table_name%"), 'store_id' => $store_id))->select();
				$tableids = array();
				foreach ($tables as $table) {
					$tableids[] = $table['pigcms_id'];
				}
				$tableids && $where['tableid'] = array('in', $tableids);
			}
			$this->assign('meal_pass', $meal_pass);
			$this->assign('order_id', $order_id);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
			$this->assign('table_name', $table_name);
		}
		
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'order_id DESC';
		} else {
			$order_sort .= 'order_id DESC';
		}
		if ($status != -1) {
			$where['status'] = $status;
		}
		
		$this->assign(D("Meal_order")->get_order_list($this->store['mer_id'], $store_id, $where, $order_sort));
		$this->assign('now_store', $this->store);
		
		$this->assign('status_list', D('Meal_order')->status_list);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status));
		$this->display();
	}

	public function order_detial() {
		$now_order = D('Meal_order')->get_order_by_orderid( $_GET['order_id']);
		if (empty($now_order)) {
			exit('此订单不存在！');
		}
		$now_order['info']=unserialize($now_order['info']);
		if(!empty($now_order['coupon_id'])) {
			$system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$now_order['coupon_price'] = $system_coupon['price'];
			$this->assign('system_coupon',$system_coupon);
		}else if(!empty($now_order['card_id'])) {
			$card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$now_order['coupon_price'] = $card['price'];
			$this->assign('card', $card);
		}
		if ($now_order['total_price'] > 0) {
			$now_order['offline_money'] = (floor($now_order['total_price'] * 100) - floor($now_order['minus_price'] * 100) - floor($now_order['balance_pay'] * 100) - floor($now_order['merchant_balance'] * 100) - floor($now_order['coupon_price'] * 100) - floor($now_order['card_price'] * 100) - floor($now_order['score_deducte'] * 100) - floor($now_order['payment_money'] * 100))/100;
		} else {
			$now_order['offline_money'] = (floor($now_order['price'] * 100) - floor($now_order['balance_pay'] * 100) - floor($now_order['merchant_balance'] * 100) - floor($now_order['coupon_price'] * 100) - floor($now_order['card_price'] * 100) - floor($now_order['score_deducte'] * 100) - floor($now_order['payment_money'] * 100))/100;
		}
		
		$mode = new Model();
		$sql = "SELECT u.name, u.phone FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON u.uid=s.uid WHERE s.order_id={$now_order['order_id']} AND s.item=0";
		$res = $mode->query($sql);
		$res = isset($res[0]) && $res[0] ? $res[0] : '';
		$now_order['deliver_user_info'] = $res;
		$this->assign('order',$now_order);
		$this->display();
	}
	
	public function meal_edit(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$store_id = intval($this->store['store_id']);
		if (IS_POST) {
			if (isset($_POST['status'])) {
				$status = intval($_POST['status']);
				if ($order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
					$data = array('store_uid' => $this->staff_session['id'], 'status' => $status);
					$data['last_staff'] = $this->staff_session['name'];
					if ($order['paid'] == 0) $this->error('当前订单的状态并不是未消费。');
					if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
						$order['paid'] = 0;
						$notOffline = 1;
						if ($this->config['pay_offline_open'] == 1) {
							$now_merchant = D('Merchant')->get_info($order['mer_id']);
							if ($now_merchant) {
								$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
							}
						}
						if ($notOffline) {
							$this->error('当前订单的状态并不是未消费。');
							exit;
						}
					}

					if ($status && $order['paid'] == 0) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
						$data['third_id'] = $order['order_id'];
						$order['pay_type'] = $data['pay_type'] = 'offline';
						$data['paid'] = 1;
						$price = $order['total_price'] > 0 ? $order['total_price'] : $order['price'];
						$data['pay_money'] = $price - $order['minus_price'];
						$order['pay_time'] = $_SERVER['REQUEST_TIME'];
					}
					$data['use_time'] = $_SERVER['REQUEST_TIME'];
					if (D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
						if ($status && $order['status'] == 0) {
							if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find()) {
								if ($supply['status'] < 2) {
									D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->delete();
								} else {
									D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->save(array('status' => 5));
								}
							}
							$this->meal_notice($order);
						}

						$this->success("更新成功", U('Store/meal_list'));
					} else {
						$this->success("更新失败，稍后重试", U('Store/meal_list'));
					}
				} else {
					$this->error('不合法的请求');
				}
			} else {
				$this->redirect(U('Store/meal_list'));
			}
		} else {
			$order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find();
			$order['info'] = unserialize($order['info']);
			if ($order['store_uid']) {
				$staff = D("Merchant_store_staff")->where(array('id' => $order['store_uid']))->find();
				$order['store_uname'] = $staff['name'];
			}
			if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
				$order['paid'] = 0;
			}

			if (empty($order['tableid'])) {
				$order['tablename'] = '不限';
			} else {
				$table = D('Merchant_store_table')->where(array('pigcms_id' => $order['tableid'], 'store_id' => $store_id))->find();
				$order['tablename'] = isset($table['name']) ? $table['name'] : '不限';
			}
			if(!empty($order['coupon_id'])) {
				$system_coupon = D('System_coupon')->get_coupon_info($order['coupon_id']);
				$order['coupon_price'] = $system_coupon['price'];
				$this->assign('system_coupon',$system_coupon);
			}else if(!empty($now_order['card_id'])) {
				$card = D('Member_card_coupon')->get_coupon_info($order['card_id']);
				$order['coupon_price'] = $card['price'];
				$this->assign('card', $card);
			}
			$this->assign('order', $order);
			$this->display();
		}
	}

	/*预约订单列表*/
	public function appoint_list(){
        $store_id = $this->store['store_id'];

        $database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
    	$where['store_id'] = $store_id;
        $where['type'] = 0;
       // $where['is_del'] = 0;
    	$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
        
        $uidArr = array();
        foreach($order_info as $v){
                array_push($uidArr,$v['uid']);
        }

        $uidArr = array_unique($uidArr);
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
    	$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);

    	$this->assign('order_list', $order_list);
    	$this->display();
	}


        public function allot_appoint_list(){
            $store_id = $this->store['store_id'];

            $database_order = D('Appoint_order');
            $database_user = D('User');
            $database_appoint = D('Appoint');
            $database_store = D('Merchant_store');
            $database_merchant_workers = D('Merchant_workers');

            $where['store_id'] = $store_id;
            $where['type'] = array('in',array(1,2));
            //$where['is_del'] = 0;
            $order_info = $database_order->field(true)->where($where)->order('`merchant_allocation_time` desc ,`order_id` DESC')->select();
            $uidArr = array();
            foreach($order_info as $v){
                    array_push($uidArr,$v['uid']);
            }

            $uidArr = array_unique($uidArr);
            $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
            $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
            $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
            $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);

            $this->assign('order_list', $order_list);
            $this->display();
        }

	/*预约订单查找*/
	public function appoint_find(){
		if(IS_POST){
			$database_order = D('Appoint_order');
	    	$database_user = D('User');
	    	$database_appoint = D('Appoint');
	    	$database_store = D('Merchant_store');

			$appoint_where['mer_id'] = $this->store['mer_id'];
			if($_POST['find_type'] == 1 && strlen($_POST['find_value']) == 16){
				$appoint_where['appoint_pass'] = $_POST['find_value'];
			} else {
				if($_POST['find_type'] == 1){
					$appoint_where['appoint_pass'] = array('LIKE', '%'.$_POST['find_value'].'%');
				} else if($_POST['find_type'] == 2){
					$appoint_where['order_id'] = $_POST['find_value'];
				} else if($_POST['find_type'] == 3){
					$appoint_where['appoint_id'] = $_POST['find_value'];
				} else if($_POST['find_type'] == 4){
					$user_where['uid'] = $_POST['find_value'];
				} else if($_POST['find_type'] == 5){
					$user_where['nickname'] = array('LIKE', '%'.$_POST['find_value'].'%');
				} else if($_POST['find_type'] == 6){
					$user_where['phone'] = array('LIKE', '%'.$_POST['find_value'].'%');
				}
			}

	    	$order_info = $database_order->field(true)->where($appoint_where)->order('`order_id` DESC')->select();
	    	$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($user_where)->select();
	    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
	    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
	    	$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
	    	if($order_list){
	    		foreach($order_list as $key=>$val){
					if($_POST['find_type'] == 5){
						if(!isset($val['nickname'])){
							unset($order_list[$key]);
							continue;
						}
					}else if($_POST['find_type'] == 6){
						if(!isset($val['phone'])){
							unset($order_list[$key]);
							continue;
						}
					}
	    			$order_list[$key]['pay_time'] = date('Y-m-d H:i:s', $order_list[$key]['pay_time']);
	    			$order_list[$key]['order_time'] = date('Y-m-d H:i:s', $order_list[$key]['order_time']);
	    		}
	    	}

	    	$return['list'] = array_values($order_list);
			$return['row_count'] = count($order_list);
			echo json_encode($return);
		} else {
			$this->display();
		}
	}

	/*订单详情*/
	public function appoint_detail(){
		$where['order_id'] = $_GET['order_id'];

		$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_merchant_workers = D('Merchant_workers');
		$database_appoint_product = D('Appoint_product');

    	$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
        
        $uidArr = array();
        foreach($order_info as $v){
                array_push($uidArr,$v['uid']);
        }

        $uidArr = array_unique($uidArr);
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();

    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
    	$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
    	$now_order = $order_list[0];
    	$cue_info = unserialize($now_order['cue_field']);
    	$cue_list = array();
    	foreach($cue_info as $key=>$val){
    		if(!empty($cue_info[$key]['value'])){
    			$cue_list[$key]['name'] = $val['name'];
    			$cue_list[$key]['value'] = $val['value'];
    			$cue_list[$key]['type'] = $val['type'];
    			if($cue_info[$key]['type'] == 2){
    				$cue_list[$key]['long'] = $val['long'];
    				$cue_list[$key]['lat'] = $val['lat'];
    				$cue_list[$key]['address'] = $val['address'];
    			}
    		}
    	}

		$product_detail = $database_appoint_product->get_product_info($now_order['product_id']);
		if($product_detail['status']){
			$now_order['product_detail'] = $product_detail['detail'];
		}

	//上门预约工作人员信息start
		$tmp_order_info=reset($order_info);

	    $Map['appoint_order_id'] = $tmp_order_info['order_id'];
	    $Map['uid'] = $tmp_order_info['uid'];
	    $appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();
	    $service_address=  unserialize($appoint_visit_order_info['service_address']);
	    if($tmp_order_info['appoint_type'] == 1){
	    $service_address_info = array();
		foreach($service_address as $key=>$val){
		    if(!empty($service_address[$key]['value'])){
			    $service_address_info[$key]['name'] = $val['name'];
			    $service_address_info[$key]['value'] = $val['value'];
			    $service_address_info[$key]['type'] = $val['type'];
			    if($appoint_visit_order_info[$key]['type'] == 2){
				    $service_address_info[$key]['long'] = $val['long'];
				    $service_address_info[$key]['lat'] = $val['lat'];
				    $service_address_info[$key]['address'] = $val['address'];
			    }
		    }
	    }
	    }
            
            if($service_address_info){
                $cue_list = $service_address_info;
            }
	    

	    $worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'];
	    $worker_field=array('merchant_worker_id','name','mobile');
	    $merchant_workers_info = $database_merchant_workers->appoint_worker_info($worker_where,$worker_field);
	    $this->assign('merchant_workers_info',$merchant_workers_info);

	//上门预约工作人员信息end

    	$this->assign('cue_list', $cue_list);
    	$this->assign('now_order', $now_order);
    	$this->display();
	}


        public function allot_appoint_detail(){
                $where['order_id'] = $_GET['order_id'];

		$database_order = D('Appoint_order');
                $database_user = D('User');
                $database_appoint = D('Appoint');
                $database_store = D('Merchant_store');
                $database_appoint_visit_order_info = D('Appoint_visit_order_info');
                $database_merchant_workers = D('Merchant_workers');

                $order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
                $uidArr = array();
                foreach($order_info as $v){
                        array_push($uidArr,$v['uid']);
                }

                $uidArr = array_unique($uidArr);
                $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();

                $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
                $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
                $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
                $now_order = $order_list[0];
                $cue_info = unserialize($now_order['cue_field']);
                $cue_list = array();
                foreach($cue_info as $key=>$val){
                        if(!empty($cue_info[$key]['value'])){
                                $cue_list[$key]['name'] = $val['name'];
                                $cue_list[$key]['value'] = $val['value'];
                                $cue_list[$key]['type'] = $val['type'];
                                if($cue_info[$key]['type'] == 2){
                                        $cue_list[$key]['long'] = $val['long'];
                                        $cue_list[$key]['lat'] = $val['lat'];
                                        $cue_list[$key]['address'] = $val['address'];
                                }
                        }
                }

                //上门预约工作人员信息start
                $tmp_order_info=reset($order_info);

                    $Map['appoint_order_id'] = $tmp_order_info['order_id'];
                    $Map['uid'] = $tmp_order_info['uid'];
                    $appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();
                    $service_address=  unserialize($appoint_visit_order_info['service_address']);
                    if($tmp_order_info['appoint_type'] == 1){
                    $service_address_info = array();
                        foreach($service_address as $key=>$val){
                            if(!empty($service_address[$key]['value'])){
                                    $service_address_info[$key]['name'] = $val['name'];
                                    $service_address_info[$key]['value'] = $val['value'];
                                    $service_address_info[$key]['type'] = $val['type'];
                                    if($appoint_visit_order_info[$key]['type'] == 2){
                                            $service_address_info[$key]['long'] = $val['long'];
                                            $service_address_info[$key]['lat'] = $val['lat'];
                                            $service_address_info[$key]['address'] = $val['address'];
                                    }
                            }
                    }
                    }
                    $cue_list = $service_address_info;

                    $worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'];
                    $worker_field=array('merchant_worker_id','name','mobile');
                    $merchant_workers_info = $database_merchant_workers->appoint_worker_info($worker_where,$worker_field);
                    $this->assign('merchant_workers_info',$merchant_workers_info);

                //上门预约工作人员信息end


                //技师列表start
                $where['merchant_store_id'] = $now_order['store_id'];
                $where['status'] = 1;
                $worker_list = $database_merchant_workers->where($where)->getField('merchant_worker_id,name');
                $this->assign('worker_list', $worker_list);
                //技师列表end

                $this->assign('cue_list', $cue_list);
                $this->assign('now_order', $now_order);
                $this->display();
        }


        public function ajax_worker_edit(){
            $merchant_worker_id = $this->_post('merchant_worker_id');
            $order_id = $this->_post('order_id');
            if(!$merchant_worker_id || !$order_id){
                exit(json_encode(array('status'=>0,'msg'=>'传递参数有误！')));
            }

            $database_appoint_visit_order_info = D('Appoint_visit_order_info');
            $database_appoint_order = D('Appoint_order');
            $Map['appoint_order_id'] = $order_id;
            $_data['merchant_worker_id'] = $merchant_worker_id ? $merchant_worker_id : 0;
            $where['order_id'] = $order_id;
            $database_appoint_order->where($where)->data($_data)->save();

            $insert_id = $database_appoint_visit_order_info->where($where)->data($_data)->save();
            if($insert_id){
                exit(json_encode(array('status'=>1,'msg'=>'订单分配成功！')));
            }else{
                exit(json_encode(array('status'=>0,'msg'=>'订单分配失败！')));
            }
        }

	/*验证预约服务*/
	public function appoint_verify(){
		$database_order = D('Appoint_order');
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_merchant_workers = D('Merchant_workers');
		//$database_appoint = D('Appoint');
		$database_merchant_store = D('Merchant_store');


		//$where['store_id'] = $this->store['store_id'];
		$where['order_id'] = $_GET['order_id'];
		$order_info = $database_order->field(true)->where($where)->find();
		$now_store = $database_merchant_store->get_store_by_storeId($order_info['store_id']);
		if(empty($order_info)){
			$this->error('当前订单不存在！');
		} else {
			$fields['store_id'] = $this->staff_session['store_id'];
			$fields['last_staff'] = $this->staff_session['name'];
			$fields['last_time'] = time();
			$fields['service_status'] = 1;
			$fields['paid'] = 1;
			if($database_order->where($where)->data($fields)->save()){
			    $Map['appoint_order_id'] =  $_GET['order_id'] + 0;
			    $appoint_visit_order_info = $database_appoint_visit_order_info->where($Map)->find();

			    $worker_where['merchant_worker_id'] = $appoint_visit_order_info['merchant_worker_id'];
			    $pay_money_count = $database_appoint_visit_order_info->order_appoint_price_sum($worker_where);
			    $database_merchant_workers->where($worker_where)->where($worker_where)->setField('appoint_price',$pay_money_count);
			    $database_merchant_workers->where($worker_where)->setInc('order_num');
                            
				$sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'appoint');
				if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
						if (empty($order_info['phone'])) {
								$user = D('User')->field(true)->where(array('uid' => $order_info['uid']))->find();
						}
						$sms_data['uid'] = $order_info['uid'];
						$sms_data['mobile'] = $user['phone'];
						$sms_data['sendto'] = 'user';
						//$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $order_info['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
						$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $now_store['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
						Sms::sendSms($sms_data);
				}

				//验证增加商家余额
				$order_info['order_type']='appoint';
				$appoint_name = M('Appoint')->field('appoint_name')->where(array('appoint'=>$order_info['appoint_id']))->find();
				D('Merchant_money_list')->add_money($this->store['mer_id'],'用户预约'.$appoint_name['appoint_name'].'记入收入',$order_info);

			    $this->success('验证成功！');
			} else {
				$this->error('验证失败！请重试。');
			}
		}
	}

	/* 格式化订单数据  */
    protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info){
    	if(!empty($user_info)){
    		$user_array = array();
    		foreach($user_info as $val){
    			$user_array[$val['uid']]['phone'] = $val['phone'];
    			$user_array[$val['uid']]['nickname'] = $val['nickname'];
    		}
    	}
    	if(!empty($appoint_info)){
    		$appoint_array = array();
    		foreach($appoint_info as $val){
    			$appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
    			$appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
    			$appoint_array[$val['appoint_id']]['appoint_price'] = $val['appoint_price'];
    		}
    	}
    	if(!empty($store_info)){
    		$store_array = array();
    		foreach($store_info as $val){
    			$store_array[$val['store_id']]['store_name'] = $val['name'];
    			$store_array[$val['store_id']]['store_adress'] = $val['adress'];
    		}
    	}
    	if(!empty($order_info)){
    		foreach($order_info as &$val){
    			$val['phone'] = $user_array[$val['uid']]['phone'];
    			$val['nickname'] = $user_array[$val['uid']]['nickname'];
    			$val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
    			$val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
    			$val['appoint_price'] = $appoint_array[$val['appoint_id']]['appoint_price'];
    			$val['store_name'] = $store_array[$val['store_id']]['store_name'];
    			$val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
    		}
    	}
    	return $order_info;
    }

	public function logout(){
		session('staff_session',null);
		redirect(U('Store/login'));
	}

	public function bill()
	{
		$mer_id = intval($this->store['mer_id']);
		$this->assign(D("Meal_order")->get_offlineorder_by_mer_id($mer_id, $this->staff_session['name']));
		$this->display();
	}
	private function meal_notice($order)
	{
		//验证增加商家余额
		$order['order_type']='meal';
		$info = unserialize($order['info']);
		$info_str = '';
		foreach($info as $v){
			$info_str.=$v['name'].':'.$v['price'].'*'.$v['num'].'</br>';
		}
		D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$info_str.'记入收入',$order);

		//积分
		D('User')->add_score($order['uid'], floor($order['price'] * C('config.user_score_get')), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
		D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
		//短信
		$sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'food');
		if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
			if (empty($order['phone'])) {
				$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
				$order['phone'] = $user['phone'];
			}
			$sms_data['uid'] = $order['uid'];
			$sms_data['mobile'] = $order['phone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//打印
		$msg = ArrayToStr::array_to_str($order['order_id']);
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->store['mer_id'], $this->store['store_id'], $msg, 2);


		$str_format = ArrayToStr::print_format($order['order_id']);
		foreach ($str_format as $print_id => $print_msg) {
			$print_id && $op->printit($this->store['mer_id'], $this->store['store_id'], $print_msg, 2, $print_id);
		}

	}

	private function group_notice($order,$verify_all)
	{
		//积分
		if($verify_all){
			D('User')->add_score($order['uid'],floor($order['total_money']*C('config.user_score_get')),'购买 '.$order['order_name'].' 消费'.floatval($order['total_money']).'元 获得积分');
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['total_money'], '购买 '.$order['order_name'].' 消费'.floatval($order['total_money']).'元 获得积分');
			//商家推广分佣
			$now_user = M('User')->where(array('uid'=>$order['uid']))->find();
			D('Merchant_spread')->add_spread_list($order,$now_user,'group',$now_user['nickname'].'购买'.C('config.group_alias_name').'获得佣金');
			
		}

		//短信
		$sms_data = array('mer_id' => $order['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'group');
		if ($this->config['sms_group_finish_order'] == 1 || $this->config['sms_group_finish_order'] == 3) {
			$sms_data['uid'] = $order['uid'];
			$sms_data['mobile'] = $order['phone'];
			$sms_data['sendto'] = 'user';
			if(empty($order['res'])){
				$sms_data['content'] = '您购买 '.$order['order_name'].'的订单(订单号：' . $order['real_orderid'] . ')已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
			}else{
				$sms_data['content'] = '您购买 '.$order['order_name'].'的订单(消费码：' . $order['res']['group_pass'] . ')已经完成了消费，如有任何疑意，请您及时联系我们！';
			}
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_group_finish_order'] == 2 || $this->config['sms_group_finish_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['order_name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//打印
		$msg = ArrayToStr::array_to_str($order['order_id'], 'group_order');
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->store['mer_id'], $this->store['store_id'], $msg, 2);
	}

	public function check_confirm()
	{
		$database = D('Meal_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
		}
		if ($order['status'] > 2) $this->error('订单已取消');
		if ($order['paid'] == 0) $this->error('未支付，不能接单。');
		if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
			$notOffline = 1;
			if ($this->config['pay_offline_open'] == 1) {
				$now_merchant = D('Merchant')->get_info($order['mer_id']);
				if ($now_merchant) {
					$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
				}
			}
			if ($notOffline) {
				$this->error('未支付，不能接单。');
				exit;
			}
		}

		if ($order['meal_type'] == 1) {
			$deliverCondition['store_id'] = $this->store['store_id'];
			$deliverCondition['mer_id'] = $this->store['mer_id'];
			// 商家是否接入配送
			if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
				$old = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find();
				if (empty($old)) {
					$deliverType = $deliver['type'];
					$address_id = $order['address_id'];
					$address_info = D('User_adress')->where(array('adress_id' => $address_id))->find();
					
					$supply['order_id'] = $order_id;
					$supply['paid'] = $order['paid'];
					$supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
					$supply['pay_type'] = $order['pay_type'];
					$supply['money'] = $order['price'];
					$supply['deliver_cash'] = floatval($order['price'] - $order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-$order['coupon_price']);
					$supply['store_id'] = $this->store['store_id'];
					$supply['store_name'] = $this->store['name'];
					$supply['mer_id'] = $this->store['mer_id'];
					$supply['from_site'] = $this->store['adress'];
					$supply['from_lnt'] = $this->store['long'];
					$supply['from_lat'] = $this->store['lat'];
					
					if ($address_info) {
						$supply['aim_site'] =  $address_info['adress'].' '.$address_info['detail'];
						$supply['aim_lnt'] = $address_info['longitude'];
						$supply['aim_lat'] = $address_info['latitude'];
						$supply['name']  = $address_info['name'];
						$supply['phone'] = $address_info['phone'];
					}
					$supply['status'] =  1;
					$supply['type'] = $deliverType;
					$supply['item'] = 0;
					$supply['create_time'] = $_SERVER['REQUEST_TIME'];
					$supply['start_time'] = $_SERVER['REQUEST_TIME'];
					$supply['appoint_time'] = $_SERVER['REQUEST_TIME'];
					if ($addResult = D('Deliver_supply')->add($supply)) {
					} else {
						$this->error('接单失败');
					}
				}
			} else {
				$this->error('您还没有接入配送机制');
			}
		}
		$data['is_confirm'] = 1;
		$data['order_status'] = 3;
		$data['store_uid'] = $this->staff_session['id'];
		$data['last_staff'] = $this->staff_session['name'];
		if($database->where($condition)->save($data)){
			$this->success('已接单');
		} else {
			$this->error('接单失败');
		}

	}
	public function waimai(){
		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);
	
		$condition['store_id'] = $store_id;
		$condition['mer_id'] = $mer_id;
	
		if (IS_POST) {
			$order_id = isset($_POST['order_id']) ? htmlspecialchars($_POST['order_id']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$meal_pass = isset($_POST['meal_pass']) ? htmlspecialchars($_POST['meal_pass']) : '';
			$order_id && $condition['order_id'] = array('like', "%$order_id%");
			$name && $condition['username'] = array('like', "%$name%");
			$phone && $condition['userphone'] = array('like', "%$phone%");
			$meal_pass && $condition['code'] = array('like', "%$meal_pass%");
			$this->assign('meal_pass', $meal_pass);
			$this->assign('order_id', $order_id);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
		}

		$count = D('waimai_order')->where($condition)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		$list = D('waimai_order')->where($condition)->order("order_id DESC")->limit($p->firstRow . ',' . $p->listRows)->select();
		$pay_method = D('Config')->get_pay_method();
		
		if(count($list)){
			foreach ($list as $listInfo){
				$addressId[$listInfo['address_id']] = $listInfo['address_id'];
				$orderId[$listInfo['order_id']] = $listInfo['order_id'];
			}
			$allGoodsOrder = D('Waimai_goods')->get_all_order_goods($orderId);
			
			foreach ($list as $k=>$listInfo){
				if($listInfo['address']){
					$list[$k]['address_info'] = unserialize($listInfo['address']);
				}
				if($pay_method[$listInfo['pay_type']]){
					$list[$k]['pay_method'] = 	$pay_method[$listInfo['pay_type']]['name'];
				}
				if($allGoodsOrder[$listInfo['order_id']]){
					$list[$k]['order_info'] = $allGoodsOrder[$listInfo['order_id']];
				}
			}
		}
		$pagebar =  $p->show();
		$this->assign('pagebar',$pagebar);
		$this->assign('order_list',$list);
		$this->assign('now_store', $this->store);
		$this->display();
	}
	
	public function waimai_add(){
		
		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);
		$status = intval($_POST['status']);
		$order_id = intval($_POST['order_id']);
		
		$storeInfo =  D('Waimai_store')->where(array('store_id'=>$store_id))->find();
		
		$condition['store_id'] = $store_id;
		$condition['mer_id'] = $mer_id;
		$deliverCondition = $condition;
		$condition['order_id'] = $order_id;
		
		$this->check_waimai();
		$order = D('Waimai_order')->where($condition)->find();
		if(!$order){
			$this->error('非法操作');
		}
		$order_status = 3;
		if($status == 2){
			$order_status = 7;
			// 取消订单 申请 
			
		}
		$result = D('Waimai_order')->field('order_id,code,address_id')->where($condition)->save(array('order_status'=>$order_status));
		if(!$result){
			$this->error('操作失败');
		}
		// 如果商家已接单 并且已经配送
		if($status == 2 && $result){
			$this->success('取消订单成功');
		}
		// 商家是否接入配送
		$deliver = D('Deliver_store')->where($deliverCondition)->find();
// 		echo D('Deliver_store')->_sql();die;
		if(!$deliver){
			$this->success('已接单');
		}
		$deliverType = $deliver['type'];
		// 订单的配送地址
		$address_id = $order['address_id'];
		$address_info = D('Waimai_user_address')->where(array('address_id'=>$address_id))->find();
		$supply['order_id'] = $order_id;
		$supply['store_id'] = $store_id;
		$supply['mer_id'] = $mer_id;
		$supply['from_site'] = $this->store['adress'];
		$supply['from_lnt'] = $this->store['long'];
		$supply['from_lat'] = $this->store['lat'];
		$supply['aim_site'] =  $address_info['address'].' '.$address_info['detail'];
		$supply['aim_lnt'] = $address_info['longitude'];
		$supply['aim_lat'] = $address_info['latitude'];
		$supply['status'] =  1;
		$supply['type'] = $deliverType;
		$supply['item'] = 1;
		$supply['code'] = $order['code'];//'收货码',
		$supply['name']  = $address_info['name'];
		$supply['phone'] = $address_info['phone'];
		$supply['create_time'] = $_SERVER['REQUEST_TIME'];
		$supply['start_time'] = $_SERVER['REQUEST_TIME'];
		$supply['appoint_time'] = $_SERVER['REQUEST_TIME'];
		if($storeInfo && $storeInfo['close'] == '1'){
			$supply['appoint_time'] = strtotime(date('Y-m-d').''.$storeInfo['start_time_2']);
		}
		
		$addResult = D('Deliver_supply')->data($supply)->add();

		if(!$addResult){
			$this->error('接单失败');
		}

		//添加订单日志
		$log = array();
		$log['status'] = $order_status;
		$log['order_id'] = $order_id;
		$log['store_id'] = $store_id;
		$log['uid'] = $this->staff_session['id'];
		$log['time'] = time();
		$log['group'] = 2;
		$result = D("Waimai_order_log")->add($log);
		if (!$result) {
			//$this->error("添加订单日志失败");exit;
		}

		$this->success('已接单');
	}
	
	public function waimai_num(){
		$count = 0;
		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);
		$condition['store_id'] = $store_id;
		$condition['mer_id'] = $mer_id;
		$condition['order_status'] = 2;
		$count = D('waimai_order')->where($condition)->count();
		
		$this->success($count);
	}
	
	/*
	 * 外卖取消订单退款
	 */
	public function waimai_cancel() {
		$store_id = intval($this->store['store_id']);
		$mer_id = intval($this->store['mer_id']);
		$order_id = I('order_id', 0, 'intval');
		if (!$order_id) {
			$this->error("订单信息错误");exit;
		}
		//查找订单
		$where = array();
		$where['store_id'] = $store_id;
		$where['mer_id'] = $mer_id;
		$where['order_id'] = $order_id;
		$where['order_status'] = array('in',"2,3,4,5");
		$orderModel = D("Waimai_order");
		$order = $orderModel->field(true)->where($where)->find();
		if (!$order) {
			$this->error("订单不存在或已取消");exit;
		}
		
		D()->startTrans();
		//更新订单状态为取消状态
		$result = $orderModel->where($where)->data(array('order_status'=>7))->save();
		if (!$result) {
			D()->rollback();
			$this->error("订单状态修改失败");
		}
		
		//商家商品数回归
		$sell_log = D("Waimai_sell_log")->field(true)->where(array('order_id'=>$order_id, 'store_id'=>$store_id))->select();
		if (!$sell_log) {
			$this->error("销售记录为空");
		}
		foreach ($sell_log as $val) {
			$result = D("Waimai_goods")->where(array('goods_id'=>$val['goods_id']))->setDec("sell_count", $val['num']);
			if (!$result) {
				D()->rollback();
				$this->error("商品数量修改失败");exit;
			}
		}
		//订单到付
		if($now_order['pay_type'] == 'offline') {
			$update = array();
			$update['order_status'] = 7;
			$update['refund_detail'] = serialize(array('refund_time'=>time()));
			$result = $orderModel->where($where)->data($update)->save();
			if (!$result) {
				D()->rollback();
				$this->error("订单状态修改失败");
			}
			D()->commit();
			$this->success("取消成功");
		}
		
		$order_refund_params = array();
		//平台余额退款
		if ($order['balance_pay'] != '0.00') {
			$add_result = D('User')->add_money($order['uid'],$order['balance_pay'],'退款 '.$order['order_id'].' 增加余额',0,0,0,"Order Cancellation (Order # ".$order['order_id'].")");
			if (!$add_result) {
				D()->rollback();
				$this->error("平台余额退款失败");
			}
				
			$param = array('refund_time' => time());
			if($result['error_code']){
				$param['err_msg'] = $result['msg'];
			} else {
				$param['refund_id'] = $order['order_id'];
			}
			$param['balance_pay'] = $order['balance_pay'];	
			$order_refund_params['balance_pay_refund'] = serialize($param);
		}

		//线上支付退款
		if ($order['online_pay'] != '0.00') {
			$pay_method = D('Config')->get_pay_method();
			if(empty($pay_method)){
				$this->error('系统管理员没开启任一一种支付方式！');
			}
			if(empty($pay_method[$order['pay_type']])){
				$this->error('您选择的支付方式不存在，请更新支付方式！');
			}
		
			$pay_class_name = ucfirst($order['pay_type']);
			$import_result = import('@.ORG.pay.'.$pay_class_name);
			if(empty($import_result)){
				$this->error('系统管理员暂未开启该支付方式，请更换其他的支付方式');
			}
			$order['order_type'] = 'waimai';
			$order['submit_order_time'] = $order['create_time'];
			$pay_class = new $pay_class_name($order, $order['online_pay'], $order['pay_type'], $pay_method[$order['pay_type']]['config'], $this->staff_session, 1);
			$go_refund_param = $pay_class->refund();
			$order_refund_params['online_pay_refund'] = serialize($go_refund_param['refund_param']);
			if ($go_refund_param['type'] != 'ok') {
				//退款失败
				D()->rollback();
				$this->error($go_refund_param['msg']);
			}
		}
		//先保证退款完整，再更新退款信息
		D()->commit();
		
		$update = array();
		$update['refund_detail'] = $order_refund_params;
		$result = D('Waimai_order')->where(array('order_id'=>$order_id))->data($update)->save();
		if(! $result){
			//退款成功，修改退款信息失败，记录日志
			error_log(date("Y-m-d H:i:s")."=>TYPE:Waimai OrderID:".$order_id." Refund:".$order['online_pay'].PHP_EOL, 3, RUNTIME_PATH."Logs/waimai_payement".date("Y-m-d").".log");
		}

		//添加订单日志
		$log = array();
		$log['status'] = 7;
		$log['order_id'] = $order_id;
		$log['store_id'] = $store_id;
		$log['uid'] = $this->staff_session['id'];
		$log['time'] = time();
		$log['group'] = 2;
		$result = D("Waimai_order_log")->add($log);
		if (!$result) {
			$this->error("添加订单日志失败");exit;
		}
		
		$this->success("订单取消成功");
	}

	/*检查是否开启订餐*/
	protected function check_waimai(){
		if(empty($this->store['have_waimai'])){
			$this->error('您访问的店铺没有开通'.$this->config['waimai_alias_name'].'功能！');
		}
	}
	
	/***收银台返回处理****/
	public function cashierBack()
	{
		$lgcode=isset($_GET['lgcode']) ? trim($_GET['lgcode']) :'';
		if($lgcode){
			$staff_session = session('staff');
			$database_store_staff = D('Merchant_store_staff');
			$condition_store_staff['username'] = $staff_session['account'];
			$now_staff = $database_store_staff->field(true)->where($condition_store_staff)->find();
			if(!empty($now_staff)){
				$tmplgcode = md5($now_staff['username']);
				if($lgcode == $tmplgcode){
					session('staff',$now_staff);
					Header('Location:/store.php?g=Merchant&c=Store&a=meal_list');
					exit();
				}
			}
		}
		session('merchant',null);
		$this->error('非法访问登陆！');
	}
	
	public function cashier()
	{
		$siteurl = $this->config['site_url'];
		$siteurl = rtrim($siteurl,'/');
		
		if(empty($siteurl)){
			$siteurl=isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			$siteurl = strtolower($siteurl);
			if(strpos($siteurl,"http:")===false && strpos($siteurl,"https:")===false) $siteurl='http://'.$siteurl;
			$siteurl = rtrim($siteurl,'/');
		}
		
		$postdata = array('account' => $this->staff_session['username'], 'mer_id' => $this->staff_session['token'], 'store_id' => $this->staff_session['store_id'], 'domain' => ltrim($siteurl, 'http://'));
		$postdata['sign'] = $this->getSign($postdata);
		$postdataStr = json_encode($postdata);
		$postdataStr = $this->Encryptioncode($postdataStr,'ENCODE');
		$postdataStr = base64_encode($postdataStr);

		header('Location: '. $siteurl .'/merchants.php?m=Index&c=auth&a=elogin&code=' . $postdataStr);
	}
	
	private function getSign($data) {
		foreach ($data as $key => $value) {
			if (is_array($value)) {
				$validate[$key] = $this->getSign($value);
			} else {
				$validate[$key] = $value;
			}
		}
		$validate['salt'] = 'pigcmso2oCashier';	//salt
		sort($validate, SORT_STRING);
		return sha1(implode($validate));
	}
	
	/**
	 * 加密和解密函数
	 *
	 * @access public
	 * @param  string  $string    需要加密或解密的字符串
	 * @param  string  $operation 默认是DECODE即解密 ENCODE是加密
	 * @param  string  $key       加密或解密的密钥 参数为空的情况下取全局配置encryption_key
	 * @param  integer $expiry    加密的有效期(秒)0是永久有效 注意这个参数不需要传时间戳
	 * @return string
	 */
	private function Encryptioncode($string, $operation = 'DECODE', $key = '', $expiry = 0) 
	{
		$ckey_length = 4;
		$key = md5($key != '' ? $key : 'lhs_simple_encryption_code_87063');
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';
	
		$cryptkey = $keya . md5($keya . $keyc);
		$key_length = strlen($cryptkey);
	
		$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
		$string_length = strlen($string);
	
		$result = '';
		$box = range(0, 255);
	
		$rndkey = array();
		for ($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
	
		for ($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
	
		for ($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if ($operation == 'DECODE') {
			if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc . str_replace('=', '', base64_encode($result));
		}
	}
	
	public function table()
	{
		$this->assign('now_store', $this->store);
	
		$database = D('Merchant_store_table');
		$where['store_id'] = $this->store['store_id'];
		$count = $database->where($where)->count();
		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		$list = $database->field(true)->where($where)->order('`pigcms_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('list', $list);
		$this->display();
	}
	
	public function table_order()
	{
		$tableid = intval($_GET['id']);
		$database = D('Merchant_store_table');
		$condition['pigcms_id'] = $tableid;
		$now_table = $database->field(true)->where($condition)->find();
		if(empty($now_table)){
			$this->error('桌台不存在！');
		}
		$this->assign('table', $now_table);
		$this->assign('now_store', $this->store);
		$order_list = D('Meal_order')->field(true)->where(array('tableid' => $tableid, 'mer_id' => $this->store['mer_id'], 'status' => 0, 'arrive_time' => array('gt', time() - 10800)))->order('arrive_time ASC')->select();
		$this->assign('order_list', $order_list);
		$this->display();
	}
	
	/* 分类状态 */
	public function table_status()
	{
		$database = D('Merchant_store_table');
		$condition['pigcms_id'] = intval($_POST['id']);
		$now_table = $database->field(true)->where($condition)->find();
		if(empty($now_table)){
			$this->error('桌台不存在！');
		}
		$data['status'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database->where($condition)->data($data)->save()){
			exit('1');
		}else{
			exit;
		}
	}
	
	public function table_check()
	{
		$database = D('Meal_order');
		$order_id = $condition['order_id'] = intval($_POST['id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
		}
		$data['is_confirm'] = $_POST['type'] == 'open' ? '1' : '0';
		if($database->where($condition)->data($data)->save()){
			exit('1');
		}else{
			exit;
		}
	}
        
	public function ajax_staff_del(){
	    $order_id = $this->_post('order_id');
	    if(!$order_id){
			exit(json_encode(array('msg'=>'传递参数有误！','status'=>0)));
	    }
	    
	    $database_appoint_order = D('Appoint_order');
	    $where['order_id'] = $order_id;
	    $data['del_time'] = time();
	    $data['is_del'] = 4;
	    $data['del_staff_id'] = $_SESSION['staff']['id'];
	    $result = $database_appoint_order->where($where)->data($data)->save();
	    if($result){
			exit(json_encode(array('msg'=>'取消成功！','status'=>1)));
	    }else{
			exit(json_encode(array('msg'=>'取消失败！','status'=>0)));
	    }
	}
	
	public function shop_list()
	{
		$store_id = intval($this->store['store_id']);
		$where = array();
		if (IS_POST) {
			$real_orderid = isset($_POST['real_orderid']) ? htmlspecialchars($_POST['real_orderid']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$meal_pass = isset($_POST['meal_pass']) ? htmlspecialchars($_POST['meal_pass']) : '';
			$orderid = isset($_POST['orderid']) ? htmlspecialchars($_POST['orderid']) : '';
			$real_orderid && $where['real_orderid'] = array('like', "%$real_orderid%");
			$name && $where['username'] = array('like', "%$name%");
			$phone && $where['userphone'] = array('like', "%$phone%");
			$orderid && $where['orderid'] = $orderid;
			
			$this->assign('meal_pass', $meal_pass);
			$this->assign('real_orderid', $real_orderid);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
			$this->assign('orderid', $orderid);
		}
		$where['mer_id'] = $this->store['mer_id'];
		$where['store_id'] = $store_id;
		
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= $type . ' ' . $sort . ',';
			$order_sort .= 'order_id DESC';
		} else {
			$order_sort .= 'order_id DESC';
		}
		if ($status != -1) {
			$where['status'] = $status;
			if ($status === 0) $where['paid'] = 1;
		}
		
		$this->assign(D("Shop_order")->get_order_list($where, $order_sort, 2));
		$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$shop = array_merge($this->store, $shop);
		$this->assign('now_store', $shop);
		
		$this->assign('status_list', D('Shop_order')->status_list);
		$this->assign($order_lsit);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status));
		
		$this->display();
	}
	public function ajax_shop_neworder(){
		$result = M('Shop_order')->where(array('store_id' => $this->store['store_id'],'status'=>'0','pay_time'=>array('egt',$_POST['time'])))->find();		
		if($result){
			$this->success(time());
		}else{
			$this->error(time());
		}
	}

	public function check_shop()
	{
		$database = D('Shop_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}
        if ($order['status'] > 0) {
        	$this->error('该单已接，不要重复接单');
        	exit;
        }
        if ($order['status'] == 4 || $order['status'] == 5) {
        	$this->error('订单已取消，不能接单！');
        	exit;
        }
        if ($order['paid'] == 0) {
        	$this->error('订单未支付，不能接单！');
        	exit;
        }
		

		$data['status'] = 1;
		$condition['status'] = 0;
		$data['order_status'] = 1;
		$data['last_staff'] = $this->staff_session['name'];
		if($database->where($condition)->save($data)){
		if ($order['is_pick_in_store'] != 2 && $order['is_pick_in_store'] != 3) {
			$deliverCondition['store_id'] = $this->store['store_id'];
			$deliverCondition['mer_id'] = $this->store['mer_id'];
			
			// 商家是否接入配送
			if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
				$supply_db_table = D('Deliver_supply');
				$old = $supply_db_table->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
				if (empty($old)) {
					$supply = array();
					if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
					$supply['order_id'] = $order_id;
					$supply['paid'] = $order['paid'];
					$supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
					$supply['pay_type'] = $order['pay_type'];
					$supply['money'] = $order['price'];
					$supply['deliver_cash'] = round($order['price'] - round($order['card_price']+$order['merchant_balance']+$order['balance_pay']+$order['payment_money']+$order['score_deducte']+$order['coupon_price'], 2), 2);
					$supply['store_id'] = $this->store['store_id'];
					$supply['store_name'] = $this->store['name'];
					$supply['mer_id'] = $this->store['mer_id'];
					$supply['from_site'] = $this->store['adress'];
					$supply['from_lnt'] = $this->store['long'];
					$supply['from_lat'] = $this->store['lat'];
					
					//目的地
					$supply['aim_site'] =  $order['address'];
					$supply['aim_lnt'] = $order['lng'];
					$supply['aim_lat'] = $order['lat'];
					$supply['name']  = $order['username'];
					$supply['phone'] = $order['userphone'];
						
					$supply['status'] =  1;
					$supply['type'] = $order['is_pick_in_store'];
					$supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店
					$supply['create_time'] = $_SERVER['REQUEST_TIME'];
					$supply['start_time'] = $_SERVER['REQUEST_TIME'];
					$supply['appoint_time'] = $order['expect_use_time'];
					$supply['note'] = $order['desc'];
					if ($supply_db_table->create($supply) != false) {
						if ($addResult = D('Deliver_supply')->add($supply)) {
						} else {
							$this->error('接单失败');
						}
					} else {
						$this->error('已接单');
					}
				}
			} else {
				$this->error('您还没有接入配送机制');
			}
		}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
			$this->success('已接单');
		} else {
			$this->error('接单失败');
		}
	
	}
	
	public function shop_order_detail()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
		$store = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
		
		$sure = false;
		if($order['is_pick_in_store'] == 3){
			$express_list = D('Express')->get_express_list();
			$this->assign('express_list',$express_list);
		} elseif ($order['is_pick_in_store'] == 0) {
			$supply = D('Deliver_supply')->field('uid')->where(array('order_id' => $order['order_id'], 'item' => 2))->find();
			if (isset($supply['uid']) && $supply['uid']) {
				$sure = true;
			}
		}
		
		$this->assign('sure', $sure);
		$this->assign('store', $store);
		$this->assign('order', $order);
		$this->display();
		
	}
	
	public function shop_edit()
	{
		if (IS_POST) {
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			$store_id = $this->store['store_id'];
			$status = intval($_POST['status']);
			if ($order = D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
				if ($status == 1 && $order['status'] > 0) {
					$this->error('该单已接，不要重复接单');
					exit;
				}
				if ($order['status'] == 4 || $order['status'] == 5) {
					$this->error('订单已取消，不能再做其他操作。');
					exit;
				}
				
				$data = array('store_uid' => $this->staff_session['id']);
				$data['status'] = $status;
				$data['last_staff'] = $this->staff_session['name'];
				
				if ($order['is_pick_in_store'] == 3) {
					$data['status'] = $status = 2;
					$express_id = isset($_POST['express_id']) ? intval($_POST['express_id']) : 0;
					$express_number = isset($_POST['express_number']) ? htmlspecialchars($_POST['express_number']) : 0;
					if ($status == 2 && (empty($express_id) || empty($express_number))) $this->error('快递公司和快递单号都不能为空。');
					if ($order['paid'] == 0 && $status != 5) {
						$this->error('未付款的订单只能进行取消操作。');
						exit;
					}
					if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
						$order['paid'] = 0;
					}
					if ($order['paid'] == 0) {
						$notOffline = 1;
						if ($this->config['pay_offline_open'] == 1) {
							$now_merchant = D('Merchant')->get_info($order['mer_id']);
							if ($now_merchant) {
								$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
							}
						}
						if ($notOffline) {
							$this->error('不支持线下支付。');
							exit;
						}
					}
					$data['last_staff'] = $this->staff_session['name'];
					$data['express_id'] = $express_id;
					$data['express_number'] = $express_number;
					$data['use_time'] = $_SERVER['REQUEST_TIME'];
					$data['last_time'] = $_SERVER['REQUEST_TIME'];
					if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
						if ($status == 1) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
						} elseif ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {
							$this->shop_notice($order);
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
						} elseif ($status == 4) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
						} elseif ($status == 5) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
						}
						$this->success("更新成功");
					} else {
						$this->error("更新失败，稍后重试");
					}
				} else {
					if ($order['paid'] == 0 && $status != 5) {
						$this->error('未付款的订单只能进行取消操作。');
						exit;
					}
					//0未确认，1已确认，2已消费，3已评价，4已退款，5已取消，
					if ($status == 3) {
						$this->error('您不能将该订单改成已评价状态。');
						exit;
					}
// 					if ($status == 5 && $order['paid'] == 1) {
// 						$this->error('当前订单已支付，您不能将改成取消状态。');
// 						exit;
// 					}
					$supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
					
					if ($order['is_pick_in_store'] == 0 && $status == 2 && $supply && $supply['uid']) {//平台配送，当配送员接单后店员就不能把订单修改成已消费状态
						$this->error('您不能将该订单改成已消费状态。');
					}
					if ($status == 0 && $order['status'] == 1 && $order['is_pick_in_store'] < 2) {
						if ($supply && $supply['status'] > 1) {
							$this->error('当前订单已进入了配送状态，不能修改成未确认状态。');
							exit;
						}
					}
					
					if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
						$order['paid'] = 0;
					}
					if ($order['paid'] == 0) {
						$notOffline = 1;
						if ($this->config['pay_offline_open'] == 1) {
							$now_merchant = D('Merchant')->get_info($order['mer_id']);
							if ($now_merchant) {
								$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
							}
						}
						if ($notOffline) {
							$this->error('不支持线下支付。');
							exit;
						}
					}
					
					if ($status == 2 && $order['paid'] == 0) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
						$data['third_id'] = $order['order_id'];
						$order['pay_type'] = $data['pay_type'] = 'offline';
						$data['paid'] = 1;
						$order['pay_time'] = $_SERVER['REQUEST_TIME'];
					}
					
					if ($status == 2) {
						$data['order_status'] = 6;//配送完成
						$supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
						if ($supply) {
							if ($supply['status'] < 2) {
								D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->delete();
							} else {
								D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 5));
							}
						}
					}
					$data['use_time'] = $_SERVER['REQUEST_TIME'];
					$data['last_time'] = $_SERVER['REQUEST_TIME'];
					if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {

						if (($status == 0 || $status == 5) && $order['status'] == 1 && $order['is_pick_in_store'] < 2) {//is_pick_in_store : 配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
							D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 0));
						}
						if ($status == 5) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
							
							//销量回滚reduce_stock_type
							if (($order['paid'] == 1 || $order['reduce_stock_type'] == 1) && $order['is_rollback'] == 0) {
								$goods_obj = D('Shop_goods');
								$details = D('Shop_order_detail')->field(true)->where(array('order_id' => $order_id))->select();
								foreach ($details as $menu) {
									$goods_obj->update_stock($menu, 1);//修改库存
								}
								D('Shop_order')->where(array('order_id' => $order_id))->save(array('is_rollback' => 1));
							}
							
						}
						
						if ($status == 2 && $order['status'] < 2) {//当订单由未消费修改成已消费时做的通知
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
							$this->shop_notice($order);
						}
						
						if ($status == 1 && $order['status'] == 0 && $order['is_pick_in_store'] != 2) {//接单
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
							$deliverCondition['store_id'] = $this->store['store_id'];
							$deliverCondition['mer_id'] = $this->store['mer_id'];
							// 商家是否接入配送
							if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {


								$old = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
								if (empty($old)) {
									$deliverType = $order['is_pick_in_store'];
									// 订单的配送地址
// 									$address_id = $order['address_id'];
// 									$address_info = D('User_adress')->where(array('adress_id' => $address_id))->find();
									if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
									$supply['order_id'] = $order_id;
									$supply['paid'] = $order['paid'];
									$supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
									$supply['pay_type'] = $order['pay_type'];
									$supply['money'] = $order['price'];
									$supply['deliver_cash'] = round($order['price'] - round($order['card_price']+$order['merchant_balance']+$order['balance_pay']+$order['payment_money']+$order['score_deducte']+$order['coupon_price'], 2), 2);
									$supply['store_id'] = $this->store['store_id'];
									$supply['store_name'] = $this->store['name'];
									$supply['mer_id'] = $this->store['mer_id'];
									$supply['from_site'] = $this->store['adress'];
									$supply['from_lnt'] = $this->store['long'];
									$supply['from_lat'] = $this->store['lat'];
									
// 									if ($address_info) {
// 										$supply['aim_site'] =  $address_info['adress'].' '.$address_info['detail'];
// 										$supply['aim_lnt'] = $address_info['longitude'];
// 										$supply['aim_lat'] = $address_info['latitude'];
// 										$supply['name']  = $address_info['name'];
// 										$supply['phone'] = $address_info['phone'];
// 									}

									//目的地
									$supply['aim_site'] =  $order['address'];
									$supply['aim_lnt'] = $order['lng'];
									$supply['aim_lat'] = $order['lat'];
									$supply['name']  = $order['username'];
									$supply['phone'] = $order['userphone'];
									
									
									$supply['status'] =  1;
									$supply['type'] = $deliverType;
									$supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店
									$supply['create_time'] = $_SERVER['REQUEST_TIME'];
									$supply['start_time'] = $_SERVER['REQUEST_TIME'];
									$supply['appoint_time'] = $order['expect_use_time'];
									$supply['note'] = $order['desc'];
									if ($addResult = D('Deliver_supply')->add($supply)) {
									} else {
										$this->error('没有接单成功');
									}
								}
							} else {
								$this->success('更新成功');
							}
						}
						$this->success("更新成功");
					} else {
						$this->error("更新失败，稍后重试");
					}
				}
			} else {
				$this->error('不合法的请求');
			}
		}
	}
	
	private function shop_notice($order)
	{
		//验证增加商家余额
		$order['order_type']='shop';
		D('Merchant_money_list')->add_money($this->store['mer_id'],'用户在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元记入收入',$order);

		//商家推广分佣
		$now_user = M('User')->where(array('uid'=>$order['uid']))->find();
		D('Merchant_spread')->add_spread_list($order,$now_user,'shop',$now_user['nickname'].'用户购买快店商品获得佣金');

		//积分
		D('User')->add_score($order['uid'], floor($order['price'] * C('config.user_score_get')), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
		D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
		//短信
		$sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'shop');
		if ($this->config['sms_shop_finish_order'] == 1 || $this->config['sms_shop_finish_order'] == 3) {
			if (empty($order['phone'])) {
				$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
				$order['phone'] = $user['phone'];
			}
			$sms_data['uid'] = $order['uid'];
			$sms_data['mobile'] = $order['userphone'];
			$sms_data['sendto'] = 'user';
			$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
			Sms::sendSms($sms_data);
		}
		if ($this->config['sms_shop_finish_order'] == 2 || $this->config['sms_shop_finish_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//打印
		$msg = ArrayToStr::array_to_str($order['order_id'], 'shop');
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->store['mer_id'], $this->store['store_id'], $msg, 2);

		$str_format = ArrayToStr::print_format($order['order_id'], 'shop');
		foreach ($str_format as $print_id => $print_msg) {
			$print_id && $op->printit($this->store['mer_id'], $this->store['store_id'], $print_msg, 2, $print_id);
		}

	}
	
	public function store_order()
	{
		$store_order = D('Store_order');
		import('@.ORG.merchant_page');
		$where = array('paid' => 1, 'store_id' => $this->store['store_id'], 'from_plat' => 1);
		
		$count = $store_order->where($where)->count();
		$p = new Page($count, 20);
		
		$sql = "SELECT s.*, u.nickname, u.phone FROM " . C('DB_PREFIX') . "store_order AS s INNER JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} AND s.from_plat=1 ORDER BY s.order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = $store_order->query($sql);
		foreach ($order_list as &$l) {
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
		}
		$pagebar = $p->show();
		
		$this->assign(array('order_list' => $order_list, 'pagebar' => $pagebar));
		$this->display();
	}
	
	public function pick()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}
		$pick_order = D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->find();
		if (IS_POST) {
			if ($order['status'] == 4 || $order['status'] == 5) {
				$this->error('订单已取消，不能接单！');
				exit;
			}
			if ($order['paid'] == 0) {
				$this->error('订单未支付，不能接单！');
				exit;
			}
			$pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : '';
			$pick_address = null;
			if ($pick_id) {
				$type = substr($pick_id, 0, 1);
				$type = $type == 's' ? 1 : 0;
				$pick_id = substr($pick_id, 1);
				$pick_address = D('Pick_address')->field(true)->where(array('id' => $pick_id, 'mer_id' => $this->store['mer_id']))->find();
				
			}
			if (empty($pick_address)) {
				$this->error('没有分配自提点！', U('Store/pick', array('order_id' => $order_id)));
				exit;
			}
			if (empty($pick_order)) {
				D('Pick_order')->add(array('store_id' => $order['store_id'], 'order_id' => $order['order_id'], 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
				D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 7));
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
				$this->success('分配成功！', U('Store/pick', array('order_id' => $order_id)));
				exit;
			} else {
				$this->error('不要重复分配！', U('Store/pick', array('order_id' => $order_id)));
				exit;
			}
		}
		
		$user = D('User_adress')->where(array('adress_id' => $order['address_id'], 'uid' => $order['uid']))->find();
		$pick_addr = D('Pick_address')->get_pick_addr_by_merid($this->store['mer_id'],true);
		if ($order['pick_id']) {
			$t_pick = array();
			foreach ($pick_addr as $v) {
				if ($order['pick_id'] == $v['pick_addr_id']) {
					$t_pick = $v;
				}
			}
			$pick_addr = array($t_pick);
		} else {
			foreach ($pick_addr as &$v) {
				$v['range'] = getRange(getDistance($v['lat'], $v['long'], $user['latitude'], $user['longitude']));
			}
		}
		$this->assign('order', $order);
		$pick_order['pick_id'] = isset($pick_order['pick_id']) && $pick_order['pick_id'] ? ($pick_order['type'] == 1 ? 's' . $pick_order['pick_id'] : 'p' . $pick_order['pick_id']) : '';

		$this->assign('pick_order', $pick_order);
		$this->assign('pick_addr', $pick_addr);
		$this->display();
	}
	
	public function deliver_goods()
	{
		$database = D('Shop_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}
		if ($order['status'] == 4 || $order['status'] == 5) {
			$this->error('订单已取消，不能发货！');
			exit;
		}
		if ($order['paid'] == 0) {
			$this->error('订单未支付，不能发货！');
			exit;
		}
		$data = array('status' => 8);
		$data['last_staff'] = $this->staff_session['name'];
		if (D('Shop_order')->where(array('order_id' => $order_id))->save($data)) {//发货
			D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 1));
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 12, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//发货
			$this->success('已发货');
		} else {
			$this->error('发货失败，稍后重试！');
		}
	}
	
	public function goods()
	{
		$begin_time = strtotime(date('Y-m-d', strtotime('-1 day')) . ' 00:00:01');
		$end_time = strtotime(date('Y-m-d', strtotime('-1 day')) . ' 23:59:59');
		if (IS_POST) {
			$begin_time = isset($_POST['begin_time']) ? strtotime($_POST['begin_time']) : $begin_time;
			$end_time = isset($_POST['end_time']) ? strtotime($_POST['end_time']) : $end_time;
		}
		$this->assign(array('end_time' => date('Y-m-d H:i:s', $end_time), 'begin_time' => date('Y-m-d H:i:s', $begin_time)));
		$data = array('store_id' => $this->store['store_id']);
		$data['begin_time'] = $begin_time;
		$data['end_time'] = $end_time;
		$this->assign('list', D('Shop_order')->order_count($data));
		$this->display();
	}
	/*
	 *
	 *
	 *	到店支付  begin
	 *
	 *
	 */
	public function store_arrival()
	{
		$store_order = D('Store_order');
		import('@.ORG.merchant_page');
		$where = array('paid' => 1, 'store_id' => $this->store['store_id'], 'from_plat' => 2);
		
		$count = $store_order->where($where)->count();
		$p = new Page($count, 20);
		
		$sql = "SELECT s.*, u.nickname, u.phone FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} AND s.from_plat=2 ORDER BY s.order_id DESC LIMIT {$p->firstRow}, {$p->listRows}";
		$order_list = $store_order->query($sql);
		foreach ($order_list as &$l) {
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
		}
		$pagebar = $p->show();
		
		$this->assign(array('order_list' => $order_list, 'pagebar' => $pagebar));
		$this->display();
	}
	public function store_arrival_add(){
		if(IS_POST){
			$data = array('store_id' => $this->store['store_id']);
			$data['mer_id'] = $this->store['mer_id'];
			$data['orderid'] = date("YmdHis") . mt_rand(10000000, 99999999);
			$data['name'] = '顾客现场自助支付';
			$data['desc'] = $_POST['txt_info'];
			$data['total_price'] = $_POST['total_price'];
			$data['discount_price'] = $_POST['discount_money'];
			$data['price'] = $_POST['pay_money'];
			$data['dateline'] = time();
			$data['from_plat'] = 2;
			$data['staff_id'] = $this->staff_session['id'];
			$data['staff_name'] = $this->staff_session['name'];
			$order_id = M("Store_order")->add($data);
			if($order_id){
				$this->success($order_id);
			}else{
				$this->error('订单创建失败，请重试');
			}
		}else{
			$now_store['discount_txt'] = unserialize($this->store['discount_txt']);
			
			$this->assign('has_discount',$now_store['discount_txt'] ? true : false);
			$this->assign('discount_type', isset($now_store['discount_txt']['discount_type']) ? $now_store['discount_txt']['discount_type'] : 0);
			$this->assign('discount_percent', isset($now_store['discount_txt']['discount_percent']) ? $now_store['discount_txt']['discount_percent'] : 0);
			$this->assign('condition_price', isset($now_store['discount_txt']['condition_price']) ? $now_store['discount_txt']['condition_price'] : 0);
			$this->assign('minus_price', isset($now_store['discount_txt']['minus_price']) ? $now_store['discount_txt']['minus_price'] : 0);
			
			
			$this->display();
		}
	}
	public function store_arrival_order(){
		$order_id  = $_GET['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
		$this->assign('now_order',$now_order);
		
		$orderprinter = M("Orderprinter")->where(array('store_id'=>$this->store['store_id']))->order('`is_main` DESC')->find();
		// dump($orderprinter);
		$this->display();
	}
	public function store_arrival_print(){
		$now_order = M("Store_order")->where(array('order_id'=>$_POST['order_id']))->find();
		$msg = ArrayToStr::array_to_str($_POST['order_id'], 'store_order');
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($now_order['mer_id'], $now_order['store_id'], $msg);
	}
	public function store_arrival_alipay_pay($order_id,$now_order){
		if(empty($this->config['arrival_alipay_open'])){
			$this->error('平台未开启支付宝收银');
		}
		$param['app_id'] = $this->config['arrival_alipay_app_id'];
		$param['method'] = 'alipay.trade.pay';
		$param['charset'] = 'utf-8';
		$param['sign_type'] = 'RSA';
		$param['timestamp'] = date('Y-m-d H:i:s');
		$param['version'] = '1.0';
		$biz_content = array(
			'out_trade_no' => 'store_'.$now_order['order_id'], 
			'scene' => 'bar_code',
			'auth_code' => $_POST['auth_code'],
			'total_amount' => $now_order['price'],
			'subject' => $now_order['name'],
		);
		$param['biz_content'] = json_encode($biz_content,JSON_UNESCAPED_UNICODE);
	
		ksort($param);
		$stringToBeSigned = "";
		$i = 0;
		foreach ($param as $k => $v) {
			if (!empty($v) && "@" != substr($v, 0, 1)) {
				if ($i == 0) {
					$stringToBeSigned .= "$k" . "=" . "$v";
				} else {
					$stringToBeSigned .= "&" . "$k" . "=" . "$v";
				}
				$i++;
			}
		}
		
		$priKey = $this->config['arrival_alipay_app_prikey'];;
		$res = "-----BEGIN RSA PRIVATE KEY-----\n".wordwrap($priKey, 64, "\n", true)."\n-----END RSA PRIVATE KEY-----";
		
		openssl_sign($stringToBeSigned, $sign, $res);
		if(empty($sign)){
			$this->error('支付宝收银商户密钥错误，请联系管理员解决。');
		}
		
		$sign = base64_encode($sign);
		
		$param['sign'] = $sign;
		$requestUrl = "https://openapi.alipay.com/gateway.do?";
		foreach ($param as $sysParamKey => $sysParamValue) {
			$requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}
		$requestUrl = substr($requestUrl, 0, -1);
		// echo $requestUrl;die;
		import('ORG.Net.Http');
		$http = new Http();
		
		$return = Http::curlGet($requestUrl);
		$returnArr = json_decode($return,true);
		// fdump($returnArr,'returnArr');
		if(!empty($returnArr['alipay_trade_pay_response']) && "10000" == $returnArr['alipay_trade_pay_response']['code']){
			$data['paid'] = '1';
			$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
			$data['pay_type'] = 'alipay';
			$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
			$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
			if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
				$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
				$now_order['order_type']='cash';
				//商家余额增加
				D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付宝支付计入收入',$now_order);

				$this->success(L('_PAYMENT_SUCCESS_'));
			}else{
				$this->error('支付失败！请联系管理员处理。');
			}
		}else if(!empty($returnArr['alipay_trade_pay_response']) && "10003" == $returnArr['alipay_trade_pay_response']['code']){	//需要用户处理，下次查询订单
			
		}else{
			if($returnArr['alipay_trade_pay_response']['sub_code'] == 'ACQ.TRADE_HAS_SUCCESS' && $now_order['paid'] != 1){
				$data['paid'] = '1';
				$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
				$data['pay_type'] = 'alipay';
				$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
				$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
				if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
					$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
					$now_order['order_type']='cash';
					//商家余额增加
					D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付宝支付计入收入',$now_order);

					$this->success(L('_PAYMENT_SUCCESS_'));
				}else{
					$this->error('支付失败！请联系管理员处理。');
				}
			}else{
				$this->error('支付失败！支付宝返回：'.$returnArr['alipay_trade_pay_response']['sub_msg']);
			}
		}
	}
	public function store_arrival_pay(){
		$order_id  = $_POST['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
		if($_POST['auth_type'] == 'alipay'){
			$this->store_arrival_alipay_pay($order_id,$now_order);
			die;
		}
		
		import('ORG.Net.Http');
		$http = new Http();
			
		$session_key = 'store_order_userpaying_'.$order_id;
		if($_SESSION[$session_key]){
			$param = array();
			$param['appid'] = $this->config['pay_weixin_appid']; 
			$param['mch_id'] = $this->config['pay_weixin_mchid']; 
			$param['nonce_str'] = $this->createNoncestr(); 
			$param['out_trade_no'] = 'store_'.$now_order['order_id']; 
			$param['sign'] = $this->getWxSign($param);
			
			$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/orderquery', $this->arrayToXml($param));
		}else{
			$param = array();
			$param['appid'] = $this->config['pay_weixin_appid']; 
			$param['mch_id'] = $this->config['pay_weixin_mchid']; 
			$param['nonce_str'] = $this->createNoncestr(); 
			$param['body'] = $now_order['name']; 
			$param['out_trade_no'] = 'store_'.$now_order['order_id']; 
			$param['total_fee'] = floatval($now_order['price']*100); 
			$param['spbill_create_ip'] = get_client_ip(); 
			$param['auth_code'] = $_POST['auth_code']; 
			$param['sign'] = $this->getWxSign($param);
			
			$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/micropay', $this->arrayToXml($param));
		}
		// fdump($return,'return');
		if($return['return_code'] == 'FAIL'){
			$this->error('支付失败！微信返回：'.$return['return_msg']);
		}
		if($return['result_code'] == 'FAIL'){
			if($return['err_code'] == 'USERPAYING'){	
				$_SESSION[$session_key] = '1';
				$this->error('用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”');
			}
			$this->error('支付失败！微信返回：'.$return['err_code_des']);
		}
		unset($_SESSION[$session_key]);
		$now_user = D('User')->get_user($return['openid'],'openid');
		if(empty($now_user)){
			$access_token_array = D('Access_token_expires')->get_access_token();
			if (!$access_token_array['errcode']) {
				$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$return['openid'].'&lang=zh_CN');
				$userifo = json_decode($return,true);

				//商家推广绑定关系
				D('Merchant_spread')->spread_add($now_order['mer_id'],  $userifo['openid'],'storepay');
				$data_user = array(
					'openid' 	=> $userifo['openid'],
					'union_id' 	=> ($userifo['unionid'] ? $userifo['unionid'] : ''),
					'nickname' 	=> $userifo['nickname'],
					'sex' 		=> $userifo['sex'],
					'province' 	=> $userifo['province'],
					'city' 		=> $userifo['city'],
					'avatar' 	=> $userifo['headimgurl'],
					'is_follow' => $userifo['subscribe'],
				);
				$reg_result = D('User')->autoreg($data_user);
				if($reg_result['error_code']){
					$now_user['uid'] = '0';
				}else{
					$now_user = D('User')->get_user($userifo['openid'],'openid');
				}
			}else{
				$now_user['uid'] = '0';
			}
		}
		$data['uid'] = $now_user['uid'];
		$data['paid'] = '1';
		$data['pay_time'] = time();
		$data['pay_type'] = 'weixin';
		$data['third_id'] = $return['transaction_id'];
		$data['payment_money'] = $return['total_fee']/100;
		if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){	
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
			$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $this->store['name'], 'keyword1' => '店内消费支付提醒', 'keyword2' => $now_order['orderid'], 'keyword3' => $now_order['price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'));		
			
			if($now_user['uid']){
				D('User')->add_score($now_user['uid'], floor($now_order['price'] * C('config.user_score_get')), '在' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得积分');

				// fdump(D('User'),'user');
				D('Userinfo')->add_score($now_user['uid'], $now_order['mer_id'], $now_order['price'], '在 ' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得积分');

				// fdump(D('Userinfo'),'userinfo');
			}
			$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
			$now_order['order_type']='cash';
			//商家余额增加
			D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店微信支付计入收入',$now_order);

			D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户到店支付获得佣金');
			$this->success(L('_PAYMENT_SUCCESS_'));
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}
	}
	public function store_arrival_check(){
		$now_order = M('Store_order')->where(array('order_id'=>$_POST['order_id']))->find();
		if($now_order['paid']){
			$this->success(L('_PAYMENT_SUCCESS_'));
		}else{
			$this->error('还未支付');
		}
	}
	//作用：产生随机字符串，不长于32位
	public function createNoncestr( $length = 32 ) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyz0123456789";  
		$str ="";
		for ( $i = 0; $i < $length; $i++ )  {  
			$str.= substr($chars, mt_rand(0, strlen($chars)-1), 1);  
		}  
		return $str;
	}
	//微信支付密钥
	public function getWxSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = $this->formatBizQueryParaMap($Parameters, false);
		$String = $String."&key=".$this->config['pay_weixin_key'];
		$String = md5($String);
		$result_ = strtoupper($String);
		return $result_;
	}
	//格式化参数，签名过程需要使用
	function formatBizQueryParaMap($paraMap, $urlencode){
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar='';
		if (strlen($buff) > 0) 
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}
	//数组转XML
	function arrayToXml($arr){
        $xml = "<xml>";
        foreach ($arr as $key=>$val){
        	if (is_numeric($val)){
        	 	$xml.="<".$key.">".$val."</".$key.">"; 
			}else{
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";  
			}
        }
        $xml.="</xml>";
        return $xml; 
    }
	/*
	 *
	 *
	 *	到店支付  end
	 *
	 *
	 */
	
	public function foodshop_order(){
		$this->display();
	}
	public function tmp_table(){
		$this->display();
	}
}