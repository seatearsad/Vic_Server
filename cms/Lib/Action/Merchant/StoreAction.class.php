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
				$staff_type=array(0=>'店小二',1=>'核销',2=>'店长');
				$this->staff_session['type_name'] = $staff_type[$this->staff_session['type']];
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

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$condition_where .= " AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
				$condition_where .= " AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
			} elseif ($_GET['searchtype'] == 'name') {
				$condition_where .= " AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			} elseif ($_GET['searchtype'] == 'phone') {
				$condition_where .= " AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
			} elseif ($_GET['searchtype'] == 's_name') {
				$condition_where .= " AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .= " AND `o`.`third_id` =".$_GET['keyword'];
			}
		}


		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;

		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';

		if ($status != -1) {
			$condition_where .= " AND `o`.`status`={$status}";
		}
		if($pay_type){
			if($pay_type=='balance'){
				$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
			}else{
				$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}

			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}

		$condition_table = array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'group_order'=>'o',C('DB_PREFIX').'user'=>'u');

		$order_count = D('')->where($condition_where)->table($condition_table)->count();

		import('@.ORG.merchant_page');
		$p = new Page($order_count, 15);

		$order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();


		$this->assign('order_list',$order_list);
		$this->assign('pagebar',$p->show());
		$pay_method = D('Config')->get_pay_method('','',0);
		$this->assign('pay_method',$pay_method);
		$this->assign(array( 'status' => $status,'pay_type'=>$pay_type));
		$this->assign('status_list', D('Group_order')->status_list);
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

		if($now_order['trade_info']){
			$trade_info_arr = unserialize($now_order['trade_info']);
			if($trade_info_arr['type'] == 'hotel'){
				$trade_hotel_info = D('Trade_hotel_category')->format_order_trade_info($now_order['trade_info']);
				$this->assign('trade_hotel_info',$trade_hotel_info);
			}
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
		$data_group_order['last_time'] = $_SERVER['REQUEST_TIME'];
		if($now_order['paid'] == 1 && $now_order['status'] == 0){
			if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
				$data_group_order['third_id'] = $now_order['order_id'];
			}
			$data_group_order['status'] = 0; //原来是1
			$data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
			$data_group_order['store_id'] = $this->store['store_id'];
		}

		if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){

			//验证增加商家余额
			//$now_order['order_type']='group';
			//$now_order['verify_all']=1;
			//$now_order['store_id'] =$this->store['store_id'];
			//D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);
			$now_user = D('User')->get_user($now_order['uid'],'uid');
			$express_nmae = D('Express')->get_express($now_order['express_type']);
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=group_order_list';
			$model->sendTempMsg('TM00017', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $this->config['group_alias_name'].'快递发货通知', 'OrderSn' => $now_order['real_order_id'], 'OrderStatus' =>$this->staff_session['name'].'已为您发货', 'remark' =>'快递号：'.$now_order['order_id'].'('.$express_nmae['name'].'),请尽快确认'));


			$this->group_notice($now_order,1);
			$this->success('修改成功！');
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
			$this->success('修改成功！');
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
			$this->success('修改成功！');
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
		//$database_user = D('User');
		$database_appoint = D('Appoint');
		//$database_store = D('Merchant_store');
		$where['a.store_id'] = $store_id;
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$where['a.order_id'] = $_GET['keyword'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['a.order_id'] = $tmp_result['order_id'];
			}elseif ($_GET['searchtype'] == 'name') {
				$where['u.username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['u.phone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['a.third_id'] =$_GET['keyword'];
			}
		}
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type){
			if($pay_type=='balance'){
				$where['_string'] = "(`a`.`balance_pay`<>0 OR `a`.`merchant_balance` <> 0 OR a.product_merchant_balance <> 0 OR a.product_balance_pay <> 0 )";
			}else{
				$where['a.pay_type'] = $pay_type;
			}
		}

		$now_appoint = $database_appoint->field(true)->where(array('appoint_id'=>$_GET['appoint_id']))->find();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .= (empty($where['_string'])?'':' AND ')." (a.order_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$order_count = $database_order->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->count();

		import('@.ORG.merchant_page');
		$page = new Page($order_count, 20);
		$order_list = $database_order->field('a.*,ap.appoint_name,a.appoint_type,a.mer_id,u.uid,u.nickname,a.order_time,a.pay_time,u.phone,s.name as store_name,s.adress as store_adress')
				->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->order('`order_id` DESC')->limit($page->firstRow.','.$page->listRows)->select();



//		$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
//
//		$uidArr = array();
//		foreach($order_info as $v){
//			array_push($uidArr,$v['uid']);
//		}
//
//		$uidArr = array_unique($uidArr);
//		$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
//		$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
//		$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
//		$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
		$pagebar = $page->show();
		$pay_method = D('Config')->get_pay_method('','',0);
		$this->assign('pay_method',$pay_method);
		$this->assign('pay_type',$pay_type);
		$this->assign('pagebar', $pagebar);
		$this->assign('order_list', $order_list);
		$this->display();
	}


	public function allot_appoint_list(){
		$store_id = $this->store['store_id'];

		$database_order = D('Appoint_order');
		$database_user = D('User');
		$database_appoint = D('Appoint');
		$database_store = D('Merchant_store');
		//$database_merchant_workers = D('Merchant_workers');

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
		$where['order_id'] = $_GET['order_id'] + 0;

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
		$order_id = $_GET['order_id'] + 0;
		$where['order_id'] = $order_id;

		$database_order = D('Appoint_order');
		$database_user = D('User');
		$database_appoint = D('Appoint');
		$database_store = D('Merchant_store');
		$database_appoint_visit_order_info = D('Appoint_visit_order_info');
		$database_merchant_workers = D('Merchant_workers');
		$database_appoint_supply = D('Appoint_supply');

		/*$order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
		$uidArr = array();
		foreach($order_info as $v){
			array_push($uidArr,$v['uid']);
		}

		$uidArr = array_unique($uidArr);
		$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();

		$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
		$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
		$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
		$now_order = $order_list[0];*/

		$now_order = $database_order->where($where)->find();
		$where_user['uid'] = $now_order['uid'];
		//$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($where_user)->find();
		$where_appoint['appoint_id'] = $now_order['appoint_id'];
		$appoint_info = $database_appoint->field(true)->where($where_appoint)->find();
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

		$appoint_supply_where['order_id'] = $order_id;
		$appoint_supply_where['worker_id'] = 0;
		$appoint_supply_count = $database_appoint_supply->where($appoint_supply_where)->count();
		$this->assign('appoint_supply_count', $appoint_supply_count);
		$this->display();
	}


	public function ajax_worker_edit(){
		$merchant_worker_id = $_POST['merchant_worker_id'] + 0;
		$order_id = $_POST['order_id'] + 0;
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
			if(C('config.store_worker_sms_order')){
				$now_order = $database_appoint_order->where(array('order_id'=>$order_id))->find();
				$worker_where['appoint_order_id'] = $order_id;
				$now_worker = $database_appoint_visit_order_info->appoint_visit_order_detail($worker_where);
				$now_worker = $now_worker['detail'];
				$database_appoint = D('Appoint');
				$appoint_info = $database_appoint->get_appoint_by_appointId($now_order['appoint_id']);

				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'], 'type' => 'appoint');
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $now_worker['mobile'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '有份新的' . $appoint_info['appoint_name'] . '被预约，订单号：' . $now_order['order_id'] . '请您注意查看并处理!';
				Sms::sendSms($sms_data);
			}

			$database_appoint_supply = D('Appoint_supply');
			$now_supply_order = $database_appoint_supply->where(array('order_id'=>$now_order['order_id']))->find();
			$supply_data['appoint_id'] = $now_order['appoint_id'];
			$supply_data['mer_id'] = $now_order['mer_id'];
			$supply_data['store_id'] = $now_order['store_id'];
			$supply_data['create_time'] = time();
			$supply_data['status'] =  1;
			if($merchant_worker_id > 0){
				$supply_data['worker_id'] = $merchant_worker_id;
				$supply_data['get_type'] = 1;
				$supply_data['status'] =  2;
			}

			$supply_data['start_time'] = $_SERVER['REQUEST_TIME'];
			$supply_data['paid'] = $now_order['paid'];
			$supply_data['pay_type'] = $now_order['pay_type'];
			$supply_data['order_time'] = $now_order['order_time'];
			$supply_data['deliver_cash'] = floatval($now_order['product_price'] - $now_order['product_card_price'] - $now_order['product_merchant_balance'] - $now_order['product_balance_pay'] - $now_order['product_payment_money'] - $now_order['product_score_deducte'] - $now_order['product_coupon_price']);
			$supply_data['uid'] = $now_order['uid'];

			if(!$now_supply_order){
				$supply_data['order_id'] = $now_order['order_id'];
				$database_appoint_supply->data($supply_data)->add();
			}else{
				$supply_where['order_id'] = $now_order['order_id'];
				$database_appoint_supply->where($supply_where)->data($supply_data)->save();
			}

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
		$database_merchant_store = D('Merchant_store');


		$where['order_id'] = $_GET['order_id'];
		$now_order = $database_order->field(true)->where($where)->find();
		$now_store = $database_merchant_store->get_store_by_storeId($now_order['store_id']);
		if(empty($now_order)){
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
					if (empty($now_order['phone'])) {
						$user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();
					}
					$sms_data['uid'] = $now_order['uid'];
					$sms_data['mobile'] = $user['phone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $now_store['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
					Sms::sendSms($sms_data);
				}

				//验证增加商家余额
				$order_info['order_type']='appoint';
				$order_info['order_id'] = $now_order['order_id'];
				$order_info['store_id'] = $now_order['store_id'];
				$order_info['balance_pay'] = $now_order['balance_pay'];
				$order_info['score_deducte'] = $now_order['score_deducte'];
				$order_info['payment_money'] = $now_order['pay_money'];
				$order_info['is_own'] = $now_order['is_own'];
				$order_info['merchant_balance'] = $now_order['merchant_balance'];
				$order_info['score_used_count'] = $now_order['score_used_count'];
				$order_info['money'] = $order_info['balance_pay'] + $order_info['score_deducte'] + $order_info['pay_money'] + $order_info['merchant_balance'];

				if($now_order['product_id'] > 0){
					$order_info['total_money'] = $now_order['product_price'];
				}else{
					$order_info['total_money'] = $now_order['appoint_price'];
				}
				$order_info['payment_money'] = $now_order['pay_money'] + $now_order['pay_money'];
				$order_info['balance_pay'] = $now_order['balance_pay'] + $now_order['product_balance_pay'];
				$order_info['merchant_balance'] = $now_order['merchant_balance'] + $now_order['product_merchant_balance'];
				$order_info['card_give_money'] = $now_order['card_give_money'] + $now_order['product_card_give_money'];
				$order_info['uid'] = $now_order['uid'];

				$appoint_name = M('Appoint')->field('appoint_name')->where(array('appoint_id'=>$order_info['appoint_id']))->find();
				D('Merchant_money_list')->add_money($now_order['mer_id'],'用户预约'.$appoint_name['appoint_name'].'记入收入',$order_info);

				$where['status'] = array('neq',3);
				$database_appoint_supply = D('Appoint_supply');
				$now_supply = $database_appoint_supply->where($where)->find();
				if($now_supply){
					$supply_data['status'] = 3;
					$supply_data['end_time'] = time();
					$supply_data['check_source'] = 2;
					$supply_data['check_time'] = time();
					$database_appoint_supply->where($where)->data($supply_data)->save();
				}
				if(C('config.open_extra_price')==1){
					$score = D('Percent_rate')->get_extra_money($order_info);
					if($score>0){
						D('User')->add_score($order_info['uid'], floor($score), '用户预约'.$appoint_name['appoint_name'].' 获得'.C('config.extra_price_alias_name'));
					}

				}else{
					if($this->config['open_score_get_percent']==1){
						$score_get = $this->config['score_get_percent']/100;
					}else{
						$score_get = $this->config['user_score_get'];
					}

					D('User')->add_score($now_order['uid'], round(($order_info['balance_pay']+$order_info['payment_money']) * $score_get), '购买预约商品获得'.$this->config['score_name']);

				}

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

		//商家推广分佣
		$now_user = M('User')->where(array('uid' => $order['uid']))->find();
		D('Merchant_spread')->add_spread_list($order, $now_user, 'meal', $now_user['nickname'] . '用户购买餐饮商品获得佣金');

		//积分
		if(C('config.open_extra_price')==1){
			$score = D('Percent_rate')->get_extra_money($order);
			if($score>0){
				D('User')->add_score($order['uid'], floor($score),'在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.extra_price_alias_name'));
			}

		}else {
			D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);

			D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). $this->store['name'] . ' 中消费获得'.$this->config['score_name']);

			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name'].'');
		}
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
			$now_user = M('User')->where(array('uid'=>$order['uid']))->find();
			if(C('config.open_extra_price')==1){
				$order['order_type'] = 'group';
				$score = D('Percent_rate')->get_extra_money($order);
				if($score>0){
					D('User')->add_score($order['uid'], floor($score),'购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.C('config.extra_price_alias_name'));
				}

			}else {
				D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.$this->config['score_name']);
				D('Scroll_msg')->add_msg('group',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '购买 ' . $order['order_name'] . '成功并消费获得'.$this->config['score_name']);
				D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['total_money'], '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得积分');
			}
			//商家推广分佣
			
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
// 			if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
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
					$supply['deliver_cash'] = max(0, $supply['deliver_cash']);
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
// 			} else {
// 				$this->error('您还没有接入配送机制');
// 			}
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
			$add_result = D('User')->add_money($order['uid'],$order['balance_pay'],'退款 '.$order['order_name'].' 增加余额');
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
		$this->error('非法访问登录！');
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
		$this->assign('pagebar',$p->show());
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
			$order_sort .= 'pay_time DESC';
		} else {
			if ($status != -1) {
				$order_sort .= 'pay_time DESC';
			} else {
				$order_sort .= 'order_id DESC';
			}

		}
		if ($status != -1) {
			$where['status'] = $status;
			if ($status === 0) $where['paid'] = 1;
		}

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['order_id'] = $tmp_result['order_id'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['userphone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['third_id'] =$_GET['keyword'];
			}
		}

		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';

		if($status == 100){
			$where['paid'] = 0;
		}else if ($status != -1) {
			$where['status'] = $status;
		}

		if($pay_type&&$pay_type!='balance'){
			$where['pay_type'] = $pay_type;
		}else if($pay_type=='balance'){
			$where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		$this->assign(D("Shop_order")->get_order_list($where, $order_sort, 2));
		$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$shop = array_merge($this->store, $shop);
		$this->assign('now_store', $shop);

		$this->assign('status_list', D('Shop_order')->status_list);
		//$this->assign($order_lsit);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));

		$field = 'sum(price) AS total_price, sum(price+extra_price - card_price - merchant_balance - card_give_money - balance_pay - payment_money - score_deducte - coupon_price) AS offline_price, sum(card_price + merchant_balance + card_give_money + balance_pay + payment_money + score_deducte + coupon_price) AS online_price';
		$count_where = "store_id='{$this->store['store_id']}' AND paid=1 AND status<>4 AND status<>5 AND (pay_type<>'offline' OR (pay_type='offline' AND third_id<>''))";
		$result_total = D('Shop_order')->field($field)->where($count_where)->select();

		$result_total = isset($result_total[0]) ? $result_total[0] : '';
		$this->assign($result_total);
		$this->assign('is_change', $this->staff_session['is_change']);
		$pay_method = D('Config')->get_pay_method('','',0);
		$this->assign('pay_method',$pay_method);
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
		if ($order['is_refund']) {
			$this->error('用户正在退款中~！');
			exit;
		}
		if ($order['paid'] == 0) {
			$this->error('订单未支付，不能接单！');
			exit;
		}


		$data['status'] = 1;
		$data['order_status'] = 1;
		$condition['status'] = 0;
		$data['last_staff'] = $this->staff_session['name'];
		$data['last_time'] = time();
		if($database->where($condition)->save($data)){
			if ($order['is_pick_in_store'] != 2 && $order['is_pick_in_store'] != 3) {
			    $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
			    if ($result['error_code']) {
			        D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
			        $this->error($result['msg']);
			        exit;
			    }
// 				$supply_db_table = D('Deliver_supply');
// 				$old = $supply_db_table->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
// 				if (empty($old)) {
// 					$supply = array();
// 					if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
// 					$supply['order_id'] = $order_id;
// 					$supply['paid'] = $order['paid'];
// 					$supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
// 					$supply['pay_type'] = $order['pay_type'];
// 					$supply['money'] = $order['price'];
// 					$supply['deliver_cash'] = round($order['price']+$order['extra_price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
// 					$supply['deliver_cash'] = max(0, $supply['deliver_cash']);
// 					$supply['store_id'] = $this->store['store_id'];
// 					$supply['store_name'] = $this->store['name'];
// 					$supply['mer_id'] = $this->store['mer_id'];
// 					$supply['from_site'] = $this->store['adress'];
// 					$supply['from_lnt'] = $this->store['long'];
// 					$supply['from_lat'] = $this->store['lat'];

// 					//目的地
// 					$supply['aim_site'] =  $order['address'];
// 					$supply['aim_lnt'] = $order['lng'];
// 					$supply['aim_lat'] = $order['lat'];
// 					$supply['name']  = $order['username'];
// 					$supply['phone'] = $order['userphone'];

// 					$supply['status'] =  1;
// 					$supply['type'] = $order['is_pick_in_store'];
// 					$supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店
// 					$supply['create_time'] = $_SERVER['REQUEST_TIME'];
// 					//$supply['start_time'] = $_SERVER['REQUEST_TIME'];
// 					$supply['appoint_time'] = $order['expect_use_time'];
// 					$supply['note'] = $order['desc'];

// 					$supply['order_time'] = $order['pay_time'];
// 					$supply['freight_charge'] = $order['freight_charge'];
// 					$supply['distance'] = round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2);

// 					if ($supply_db_table->create($supply) != false) {
// 						if ($addResult = D('Deliver_supply')->add($supply)) {
// 						} else {
// 							$this->error('接单失败');
// 						}
// 					} else {
// 						$this->error('已接单');
// 					}
// 				}
			}
			$phones = explode(' ', $this->store['phone']);
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));

            $userInfo = D('User')->field(true)->where(array('uid'=>$order['uid']))->find();
            if($userInfo['device_id'] != ""){
                $message = 'Your order has been accepted by the store, they are preparing your order now. Our Courier is on the way, thank you for your patient.';
                Sms::sendMessageToGoogle($userInfo['device_id'],$message);
            }else{
                //add garfunkel
                $sms_data['uid'] = $order['uid'];
                $sms_data['mobile'] = $order['userphone'];
                $sms_data['sendto'] = 'user';
                $sms_data['tplid'] = 172700;
                $sms_data['params'] = [];
                Sms::sendSms2($sms_data);
            }

			//发送信息
			//获取所有的配送员
//			$rs = D('Deliver_user')->field(true)->where(array('status' => 1, 'work_status' => 0))->select();
//			foreach($rs as $r){
//				$sms_data = [
////					'tplid' => 86914,
//                    'tplid' => 247173,
//					'mobile' => $r['phone'],
//					'params' => [],
//					'content' => '有一个新的订单可以配送，请前往个人中心抢单。'
//				];
//				Sms::sendSms2($sms_data);
//			}

			$this->success('已接单');
		} else {
			$this->error('接单失败');
		}

	}

	public function shop_order_detail()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
		$tax_price = 0;
		$deposit = 0;
		foreach ($order['info'] as $v){
            $good = D('Shop_goods')->field(true)->where(array('goods_id'=>$v['goods_id']))->find();
            $deposit += $good['deposit_price']*$v['num'];
            $tax_price += $good['price'] * $good['tax_num']/100 * $v['num'];
        }
		$store = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();

		$shop = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
		$tax_price = $tax_price + ($order['freight_charge'] + $order['packing_charge'])*$shop['tax_num']/100;
		$order['tax_price'] = $tax_price;
		$order['deposit'] = $deposit;
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
				if ($order['is_refund']) {
					$this->error('用户正在退款中~！');
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
						$phones = explode(' ', $this->store['phone']);
						if ($status == 1) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
						} elseif ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {
							$this->shop_notice($order);
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
						} elseif ($status == 4) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
						} elseif ($status == 5) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
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
					} elseif ($status == 1) {
						$data['order_status'] = 1;
					}
					$data['use_time'] = $_SERVER['REQUEST_TIME'];
					$data['last_time'] = $_SERVER['REQUEST_TIME'];
					if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {

						if (($status == 0 || $status == 5) && $order['status'] == 1 && $order['is_pick_in_store'] < 2) {//is_pick_in_store : 配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
							D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 0));
						}
						$phones = explode(' ', $this->store['phone']);
						if ($status == 5) {
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));

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

						if ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {//当订单由未消费修改成已消费时做的通知
						    D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
							$this->shop_notice($order);
						}

						if ($status == 1 && $order['status'] == 0 && $order['is_pick_in_store'] != 2) {//接单
                		    $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
                		    if ($result['error_code']) {
                		        D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save(array('status' => 0, 'last_time' => time()));
                		        $this->error($result['msg']);
                		        exit;
                		    }
							D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));

// 							$old = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
// 							if (empty($old)) {
// 								$deliverType = $order['is_pick_in_store'];
// 								// 订单的配送地址
// 								if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
// 								$supply['order_id'] = $order_id;
// 								$supply['paid'] = $order['paid'];
// 								$supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
// 								$supply['pay_type'] = $order['pay_type'];
// 								$supply['money'] = $order['price'];
// 								$supply['deliver_cash'] = round($order['price']+$order['extra_price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
// 								$supply['deliver_cash'] = max(0, $supply['deliver_cash']);
// 								$supply['store_id'] = $this->store['store_id'];
// 								$supply['store_name'] = $this->store['name'];
// 								$supply['mer_id'] = $this->store['mer_id'];
// 								$supply['from_site'] = $this->store['adress'];
// 								$supply['from_lnt'] = $this->store['long'];
// 								$supply['from_lat'] = $this->store['lat'];

// 								//目的地
// 								$supply['aim_site'] =  $order['address'];
// 								$supply['aim_lnt'] = $order['lng'];
// 								$supply['aim_lat'] = $order['lat'];
// 								$supply['name']  = $order['username'];
// 								$supply['phone'] = $order['userphone'];


// 								$supply['status'] =  1;
// 								$supply['type'] = $deliverType;
// 								$supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店
// 								$supply['create_time'] = $_SERVER['REQUEST_TIME'];
// 								//$supply['start_time'] = $_SERVER['REQUEST_TIME'];
// 								$supply['appoint_time'] = $order['expect_use_time'];
// 								$supply['note'] = $order['desc'];


// 								$supply['order_time'] = $order['pay_time'];
// 								$supply['freight_charge'] = $order['freight_charge'];
// 								$supply['distance'] = round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2);

// 								if ($addResult = D('Deliver_supply')->add($supply)) {
// 								} else {
// 									$this->error('没有接单成功');
// 								}
// 							}
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

	private function shop_notice($order, $is_staff = false)
	{

	    //验证增加商家余额
	    $order['order_type'] = 'shop';
		if ($is_staff) {
			if($order['card_id']){
				$use_result = D('Card_new_coupon')->user_coupon($order['card_id'], $order['order_id'], 'shop', $order['mer_id'], $order['uid']);
				if($use_result['error_code']){
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}
				
			if(floatval($order['merchant_balance']) > 0){
				$use_result = D('Card_new')->use_money($order['uid'], $order['mer_id'], $order['merchant_balance'], '购买 '. $order['real_orderid'].' 扣除会员卡余额');
				if($use_result['error_code']){
					return array('error'=>1,'msg'=>$use_result['msg']);
				}
			}
				
			if(floatval($order['card_give_money']) > 0){
				$use_result = D('Card_new')->use_give_money($order['uid'], $order['mer_id'], $order['card_give_money'], '购买 ' . $order['real_orderid'] . ' 扣除会员卡赠送余额');
				if ($use_result['error_code']) {
					return array('error' => 1, 'msg' => $use_result['msg']);
				}
			}
			
			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
			$goods_obj = D("Shop_goods");
			foreach ($goods as $gr) {
				$goods_obj->update_stock($gr);//修改库存
			}

			$order['order_type'] = 'shop_offline';
		}
		D('Merchant_money_list')->add_money($this->store['mer_id'],'用户在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元记入收入',$order);

		//商家推广分佣
		$now_user = M('User')->where(array('uid'=>$order['uid']))->find();
		D('Merchant_spread')->add_spread_list($order,$now_user,'shop',$now_user['nickname'].'用户购买快店商品获得佣金');

		//积分
		if(C('config.open_extra_price')==1){
			$score = D('Percent_rate')->get_extra_money($order);
			if($score>0){
				D('User')->add_score($order['uid'], floor($score),'在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.extra_price_alias_name'));
			}

		}else{
			D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
			D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). $this->store['name'] . ' 中消费获得'.$this->config['score_name']);
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name'].'');
		}
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
			if ($order['is_refund']) {
				$this->error('用户正在退款中~！');
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
				$type = $type == 's' ? 1 : 0;//0:表示商家添加的自提点，1：默认的店铺
				$pick_id = substr($pick_id, 1);
				$pick_address = D('Pick_address')->field(true)->where(array('id' => $pick_id, 'mer_id' => $this->store['mer_id']))->find();

			}
			if (empty($pick_address)) {
				$this->error('没有分配自提点！', U('Store/pick', array('order_id' => $order_id)));
				exit;
			}
			if (empty($pick_order)) {
				D('Pick_order')->add(array('store_id' => $order['store_id'], 'order_id' => $order['order_id'], 'type' => $type, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
				D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 7, 'order_status' => 1, 'pick_id' => $pick_id));
				$phones = explode(' ', $this->store['phone']);
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));//分配到自提点
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
				if ('p' . $order['pick_id'] == $v['pick_addr_id']) {
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
		if ($order['is_refund']) {
			$this->error('用户正在退款中~！');
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
			$phones = explode(' ', $this->store['phone']);
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 12, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));//发货
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

		$offline_pay_list = M('Store_pay')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		foreach($offline_pay_list as $key=>$value){
			$offline_pay_array[$value['id']] = $value['name'];
		}

		foreach ($order_list as &$l) {
			if($l['pay_type'] == 'offline'){
				$l['pay_type_show'] = '线下支付';
				if($l['offline_pay'] && $offline_pay_array[$l['offline_pay']]){
					$l['pay_type_show'].= ' ('.$offline_pay_array[$l['offline_pay']].')';
				}
			}else{
				$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'], $l['is_mobile_pay']);
			}
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
			$data['name'] = $_POST['pay_title'] ? $_POST['pay_title'] : '顾客现场自助支付';
			$data['desc'] = $_POST['txt_info'];
			$data['total_price'] = $_POST['total_price'];
			$data['discount_price'] = $_POST['discount_money'];
			$data['price'] = $_POST['pay_money'];
			$data['dateline'] = time();
			$data['from_plat'] = 2;
			$data['staff_id'] = $this->staff_session['id'];
			$data['staff_name'] = $this->staff_session['name'];
			$data['extra_price'] = $_POST['extra_price'];
			$now_user = D('User')->get_user($_POST['user_phone'],'phone');
			if(!empty($_POST['user_phone'])) {
				if (empty($now_user)) {
					if(!preg_match('/^[0-9]{11}$/',$_POST['user_phone'])){
						$this->error('请输入有效的手机号，无法自动注册');
					}
					$data_user['phone'] = $_POST['user_phone'];
					D('User')->autoreg($data_user);
				} else {
					$data['uid'] = $now_user['uid'];
				}
			}

			if($_POST['business_type']){
				$data['business_type'] = $_POST['business_type'];
				$data['business_id'] = $_POST['business_id'];
			}

			if (floatval($data['price']) == 0) {
			    $data['paid'] = '1';
			    $data['pay_time'] = $_SERVER['REQUEST_TIME'];
			    $data['payment_money'] = 0;
			}
			$order_id = M("Store_order")->add($data);
			if($order_id){
			    if(floatval($data['price']) == 0){
			        $now_order = M("Store_order")->where(array('order_id' => $order_id))->find();
			        if($now_order['business_type']){
			            switch($now_order['business_type']){
			                case 'foodshop':
			                    $now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
			                    D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
			                    M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
			                    break;
			            }
			        }
			    
			        if (C('config.open_extra_price') == 1 && $now_order['uid'] > 0) {
			            $now_order['order_type'] = 'cash';
			            $score = D('Percent_rate')->get_extra_money($now_order);
			    
			            if ($score > 0) {
			                M('Store_order')->where(array('order_id'=>$order_id))->setField('score_give',$score);
			                //到店付给用户积分时需要商家预支积分
			                M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
			                $send_score_data['mer_id'] = $this->store['mer_id'];
			                $send_score_data['score_count'] = $score;
			                $send_score_data['add_time'] = time();
			                M('Merchant_score_send_log')->add($send_score_data);
			    
			                M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
			                D('User')->add_score($now_order['uid'], floor($score),$this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.C('config.extra_price_alias_name'));
			            }
			    
			        }
			        $this->success('SUCCESS');
			    } else {
			        $this->success($order_id);
			    }
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

			if($_GET['business_type']){
				switch($_GET['business_type']){
					case 'foodshop':
						$now_order = D('Foodshop_order')->get_order_detail(array('order_id'=>$_GET['business_id']));
						$pay_money = D('Foodshop_order')->count_price($now_order);
						$pay_title = '餐饮订单：'.$now_order['real_orderid'];
						break;
				}
				$this->assign('pay_money',$pay_money);
				$this->assign('pay_title',$pay_title);
			}

			$this->display();
		}
	}
	public function store_arrival_order(){
		$order_id  = $_GET['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
		$this->assign('now_order',$now_order);

		$orderprinter = M("Orderprinter")->where(array('store_id'=>$this->store['store_id']))->order('`is_main` DESC')->find();
		$this->assign('orderprinter',$orderprinter);

		$offline_pay_list = M('Store_pay')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		$this->assign('offline_pay_list',$offline_pay_list);

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

				if($now_order['business_type']){
					switch($now_order['business_type']){
						case 'foodshop':
							$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
							D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
							M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
							break;
					}
				}

				$this->success('支付成功！');
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

					if($now_order['business_type']){
						switch($now_order['business_type']){
							case 'foodshop':
								$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
								D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
								M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
								break;
						}
					}

					$this->success('支付成功！');
				}else{
					$this->error('支付失败！请联系管理员处理。');
				}
			}else{
				$this->error('支付失败！支付宝返回：'.$returnArr['alipay_trade_pay_response']['sub_msg']);
			}
		}
	}
	public function store_arrival_offline_pay($order_id,$now_order){
		$data['paid'] = '1';
		$data['pay_time'] = $_SERVER['REQUEST_TIME'];
		$data['pay_type'] = 'offline';
		$data['offline_pay'] = $_POST['offline_pay'];
		if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
			$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
			if($now_order['business_type']){
				switch($now_order['business_type']){
					case 'foodshop':
						$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
						D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
						M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
						break;
				}
			}

			if(C('config.open_extra_price')==1&&$now_order['uid']>0){
				$now_order['order_type'] ='cash';
				$score = D('Percent_rate')->get_extra_money($now_order);

				if($score>0){
					M('Store_order')->where(array('order_id'=>$order_id))->setField('score_give',$score);
					//到店付给用户积分时需要商家预支积分
					M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
					$send_score_data['mer_id'] = $this->store['mer_id'];
					$send_score_data['score_count'] = $score;
					$send_score_data['add_time'] = time();
					M('Merchant_score_send_log')->add($send_score_data);

					M('Merchant')->where(array('mer_id'=>$this->store['mer_id']))->setInc('extra_price_pay_for_system',$score);
					D('User')->add_score($now_order['uid'], floor($score),$this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.C('config.extra_price_alias_name'));

				}

			}
			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}

	}
	public function store_arrival_pay(){
		$order_id  = $_POST['order_id'];
		$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();

		if($_POST['offline_pay']){
			$this->store_arrival_offline_pay($order_id,$now_order);
			die;
		}
		if($_POST['auth_type'] == 'alipay'){
			$this->store_arrival_alipay_pay($order_id,$now_order);
			die;
		}

		$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
		$sub_mch_pay = false;
		$is_own = 0;
		if ($this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
			$this->config['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
			$sub_mch_pay = ture;
			$sub_mch_id = $now_merchant['sub_mch_id'];
			$is_own = 2 ;
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
			if($sub_mch_pay){
				$param['out_trade_no'] = 'store_'.$now_order['order_id'].'_1';
				$param['sub_mch_id'] = $sub_mch_id;
			}
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
			if($sub_mch_pay){
				$param['out_trade_no'] = 'store_'.$now_order['order_id'].'_1';
				$param['sub_mch_id'] = $sub_mch_id;
			}


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
		$data['is_own'] = $is_own;
		$data['payment_money'] = $return['total_fee']/100;
		if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
			$href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
			$model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $this->store['name'], 'keyword1' => '店内消费支付提醒', 'keyword2' => $now_order['orderid'], 'keyword3' => $now_order['price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'));
			if($now_user['uid']){
				$order = M("Store_order")->where(array('order_id'=>$order_id))->find();

				if(C('config.open_extra_price')==1){
					$order['order_type'] ='cash';
					$score = D('Percent_rate')->get_extra_money($order);
					if($score>0){
						M('Store_order')->where(array('order_id'=>$order_id))->setField('score_give',$score);
						//到店付给用户积分时需要商家预支积分
						D('User')->add_score($order['uid'], floor($score),$this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.C('config.extra_price_alias_name'));
					}

				}else{
					D('User')->add_score($now_user['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.$this->config['score_name']);
					D('Scroll_msg')->add_msg('store',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). $this->store['name'] . ' 中消费获得'.$this->config['score_name']);

					D('Userinfo')->add_score($now_user['uid'], $now_order['mer_id'], $now_order['price'], '在 ' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.$this->config['score_name'].'');
				}

				// fdump(D('User'),'user');

				// fdump(D('Userinfo'),'userinfo');
			}
			$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
			$now_order['order_type']='cash';
			//商家余额增加
			D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店微信支付计入收入',$now_order);

			D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户到店支付获得佣金');

			if($now_order['business_type']){
				switch($now_order['business_type']){
					case 'foodshop':
						$now_food_order = D('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->find();
						D('Foodshop_order')->after_pay($now_order['business_id'], null, 1);
						M('Foodshop_order')->where(array('order_id'=>$now_order['business_id']))->data(array('price'=>$now_order['payment_money']+$now_food_order['book_price'],'pay_type'=>'1'))->save();
						break;
				}
			}

			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}
	}
	public function store_arrival_check(){
		$now_order = M('Store_order')->where(array('order_id'=>$_POST['order_id']))->find();
		if($now_order['paid']){
			$this->success('支付成功！');
		}else{
			$this->error('还未支付');
		}
	}
	//作用：产生随机字符串，不长于32位
	public function createNoncestr( $length = 32 ){
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
	public function ajax_foodshop_storestaff_order(){
		$where['store_id'] = $this->store['store_id'];
		$where['status'] = array('lt',3);
		$where['running_state'] = '1';
		$where['running_time'] = array('egt',$_POST['time']);
		$count = M("Foodshop_order")->where($where)->count();
		if($count > 0){
			$this->success('您有新订单');
		}else{
			$this->error($_SERVER['REQUEST_TIME']);
		}
	}
	public function foodshop()
	{
		$store_id = intval($this->store['store_id']);
		$where = array();
		if (IS_POST) {
			$real_orderid = isset($_POST['real_orderid']) ? htmlspecialchars($_POST['real_orderid']) : '';
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$real_orderid && $where['real_orderid'] = array('like', "%$real_orderid%");
			$name && $where['name'] = array('like', "%$name%");
			$phone && $where['phone'] = array('like', "%$phone%");
			$orderid && $where['orderid'] = $orderid;

			$this->assign('meal_pass', $meal_pass);
			$this->assign('real_orderid', $real_orderid);
			$this->assign('name', $name);
			$this->assign('phone', $phone);
			$this->assign('orderid', $orderid);
		}
		$where['store_id'] = $store_id;

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
		if ($status != -1 && $status <= 10) {
			$where['status'] = $status;
			$condition_where .= ' AND o.status ='.$status;
		}else if($status == 11){
			$where['status'] = array('lt',3);
			$where['running_state'] = '1';
			$condition_where .= ' AND o.status <3 AND o.running_state=1';
		}

//		$where = array('mer_id' => $now_store['mer_id'], 'store_id' => $now_store['store_id']);
		$condition_where = 'Where o.mer_id = '.$this->store['mer_id'].' AND o.store_id = '.$this->store['store_id'];
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.real_orderid ='.$where['real_orderid'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.orderid ='.$where['orderid'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['name'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.name ='.$where['name'];
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['phone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.phone ='.$where['phone'];
			} elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .=' AND p.third_id ='.$_GET['keyword'];
			}
		}
		
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type&&$pay_type!='balance'){
			$condition_where .=' AND p.pay_type ="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$condition_where .= "  AND  (`p`.`system_balance`<>0 OR `p`.`merchant_balance_pay` <> 0 )";
		}


		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition_where .=" AND  (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$sql_cont = 'SELECT count(o.order_id) as count from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where.' ORDER BY o.order_id DESC';
		$count  = M()->query($sql_cont);
		$count = $count[0]['count'];

		import('@.ORG.merchant_page');
		$p = new Page($count, 20);
		//$list = D("Foodshop_order")->where($where)->order($order_sort)->limit($p->firstRow . ',' . $p->listRows)->select();
		$sql = 'SELECT o.*,p.pay_type as pay_method from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where.' ORDER BY o.order_id DESC'.' limit '.$p->firstRow.','.$p->listRows;
		$list = M('')->query($sql);

		$mer_ids = $store_ids = array();
		foreach ($list as $l) {
			$mer_ids[] = $l['mer_id'];
			$store_ids[] = $l['store_id'];
			$table_types[] = $l['table_type'];
			$tids[] = $l['table_id'];

		}


		$type_list = array();
		if ($table_types) {
			$temp_type_list = M('Foodshop_table_type')->field(true)->where(array('id' => array('in', $table_types)))->select();
			foreach ($temp_type_list as $tmp) {
				$type_list[$tmp['id']] = $tmp;
			}
		}
		$table_list = array();
		if ($tids) {
			$temp_table_list = M('Foodshop_table')->field(true)->where(array('id' => array('in', $tids)))->select();
			foreach ($temp_table_list as $temp) {
				$table_list[$temp['id']] = $temp;
			}
		}


		$store_temp = $mer_temp = array();
		if ($mer_ids) {
			$merchants = D("Merchant")->where(array('mer_id' => array('in', $mer_ids)))->select();
			foreach ($merchants as $m) {
				$mer_temp[$m['mer_id']] = $m;
			}
		}
		if ($store_ids) {
			$merchant_stores = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
			foreach ($merchant_stores as $ms) {
				$store_temp[$ms['store_id']] = $ms;
			}
		}
		foreach ($list as &$li) {
			$li['merchant_name'] = isset($mer_temp[$li['mer_id']]['name']) ? $mer_temp[$li['mer_id']]['name'] : '';
			$li['store_name'] = isset($store_temp[$li['store_id']]['name']) ? $store_temp[$li['store_id']]['name'] : '';

			$li['table_type_name'] = isset($type_list[$li['table_type']]) ? $type_list[$li['table_type']]['name'] . '(' . $type_list[$li['table_type']]['min_people'] . '-' . $type_list[$li['table_type']]['max_people'] . '人)' : '';
			$li['table_name'] = isset($table_list[$li['table_id']]) ? $table_list[$li['table_id']]['name'] : '';
			$li['show_status'] = D('Foodshop_order')->status_list[$li['status']];
		}
		$this->assign('order_list', $list);

		$pagebar = $p->show();
		$this->assign('status_list', D('Foodshop_order')->status_list);
		$this->assign('pagebar',$pagebar);

		//$this->assign(D("Foodshop_order")->get_order_list($where, $order_sort, 1));
		$shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$shop = array_merge($this->store, $shop);
		$this->assign('now_store', $shop);

		$this->assign('status_list', D('Foodshop_order')->status_list);
		$this->assign(array('type' => $type, 'sort' => $sort, 'status' => $status,'pay_type'=>$pay_type));
		$pay_method = D('Config')->get_pay_method('','',0);
		$this->assign('pay_method',$pay_method);
		$this->display();
	}
	public function foodshop_order_before(){
		$table_type = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();

		if(empty($table_type)){
			$this->error('该店铺没有设置桌台分类');
		}

		$this->assign('table_type',$table_type);


		$table_list = M('Foodshop_table')->where(array('tid'=>$table_type[0]['id'],'status'=>'0'))->order('`id` ASC')->select();

		$this->assign('table_list',$table_list);

		$this->display();
	}
	public function get_table_list(){
		$table_list = M('Foodshop_table')->where(array('tid'=>$_GET['table_type'],'store_id'=>$this->store['store_id'],'status'=>'0'))->order('`id` ASC')->select();
		echo json_encode($table_list);
	}
	public function foodshop_add_order(){
		$data['real_orderid'] = date('ymdhis').substr(microtime(),2,8-strlen($this->store['store_id'])).$this->store['store_id'];
		$data['mer_id'] = $this->store['mer_id'];
		$data['store_id'] = $this->store['store_id'];
		$data['book_num'] = $_POST['book_num'];
		$data['table_type'] = $_POST['table_type'];
		$data['table_id'] = $_POST['table_id'];
		$data['create_time'] = $_SERVER['REQUEST_TIME'];
		$data['status'] = 1;
		if($order_id = M('Foodshop_order')->data($data)->add()){
			M('Foodshop_table')->where(array('id'=>$data['table_id']))->data(array('status'=>'1'))->save();
			$this->success($order_id);
		}else{
			$this->error('创建订单失败，请重试');
		}
	}
	public function foodshop_order(){
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']), 3);

		$price = D('Foodshop_order')->count_price($order);
		if($this->config['open_extra_price']){
			$extra_price = D('Foodshop_order')->count_extra_price($order);
		}else{
			$extra_price = 0;
		}
		$this->assign('order', $order);
		$this->assign('price', $price);
		$this->assign('total_price', $price + $order['book_price']);
		$this->assign('extra_price', $extra_price);

		$goods_package = D('Foodshop_goods_package')->get_list_by_store_id($this->store['store_id']);
		$this->assign('goods_package', $goods_package);
		if ($order['status'] < 3) {
			$this->display();
		} else {
			$this->display('foodshop_order_detail');
		}
	}
	public function foodshop_getmenu(){
		$search	=	$_GET['search'];
		$return['lists'] = D('Foodshop_goods')->get_list_by_storeid($this->store['store_id']);

		$return['package'] = D('Foodshop_goods_package')->get_list_by_store_id($this->store['store_id']);

		$return['tmp_order'] = M('Foodshop_order_temp')->where(array('store_id'=>$this->store['store_id'],'order_id'=>$_GET['order_id']))->order('`id` ASC')->select();
		foreach($return['tmp_order'] as &$tmp_value){
			$tmp_value['price'] = floatval($tmp_value['price']);
			if($this->config['open_extra_price']==0){
				$tmp_value['extra_pay_price']=0;
			}
		}

		$return['order_detail'] = M('Foodshop_order_detail')->where(array('store_id'=>$this->store['store_id'],'order_id'=>$_GET['order_id']))->order('`id` ASC')->select();
		foreach($return['order_detail'] as &$detail_value){
			$detail_value['price'] = floatval($detail_value['price']);
			$detail_value['num'] = floatval($detail_value['num']);
		}
		echo json_encode($return);
	}
	public function foodshop_getgroup_detail(){
		echo json_encode(D('Foodshop_goods_package_detail')->get_detail_by_pid($_GET['group_id']));
	}
	public function foodshop_change_order(){
		$condition_where['store_id'] = $this->store['store_id'];
		$condition_where['order_id'] = $_GET['order_id'];
		$condition_where['id'] = $_POST['detail_id'];
		if($_POST['number'] > 0){
			if(M('Foodshop_order_detail')->where($condition_where)->data(array('num'=>$_POST['number']))->save()){
				$order = D('Foodshop_order')->get_order_detail(array('order_id' => intval($_GET['order_id']), 'store_id' => $this->store['store_id']), 2);
				$price = D('Foodshop_order')->count_price($order);
				if($this->config['open_extra_price']){
					$extra_price = D('Foodshop_order')->count_extra_price($order);
				}else{
					$extra_price = 0;
				}
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode(array('status' => 1, 'info' => '保存成功！', 'total_price' => floatval($price + $order['book_price']), 'book_price' => floatval($order['book_price']), 'unpaid_price' => floatval($price),'extra_price'=>$extra_price)));
				$this->success('保存成功！');
			}else{
				dump(M());
				$this->error('保存失败！');
			}
		}else{
			if(M('Foodshop_order_detail')->where($condition_where)->delete()){
				$order = D('Foodshop_order')->get_order_detail(array('order_id' => intval($_GET['order_id']), 'store_id' => $this->store['store_id']), 2);
				$price = D('Foodshop_order')->count_price($order);
				if($this->config['open_extra_price']){
					$extra_price = D('Foodshop_order')->count_extra_price($order);
				}else{
					$extra_price = 0;
				}
				header('Content-Type:application/json; charset=utf-8');
				exit(json_encode(array('status' => 1, 'info' => '保存成功！', 'total_price' => floatval($price + $order['book_price']), 'book_price' => floatval($order['book_price']), 'unpaid_price' => floatval($price),'extra_price'=>$extra_price)));
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！');
			}
		}
	}
	public function foodshop_save_order()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$package_id = isset($_POST['package_id']) ? intval($_POST['package_id']) : 0;
		$carts = isset($_POST['cart']) ? $_POST['cart'] : null;
		if (empty($carts)) {
			$this->error('没有点菜');
		}
		if ($order = M('Foodshop_order')->field(true)->where(array('store_id' => $this->store['store_id'], 'order_id' => $order_id))->find()) {
			$productCart = array();
			foreach ($carts as $row) {
				$params = '';
				$tmp_id = 0;
				if (isset($row['tmpOrderId']) && $row['tmpOrderId']) {
					if ($goods_temp = D('Foodshop_order_temp')->field(true)->where(array('order_id' => $order_id, 'id' => $row['tmpOrderId']))->find()) {
						$t_cookie = array('goods_id' => $goods_temp['goods_id'], 'num' => $row['count'], 'name' => $goods_temp['name'], 'price' => floatval($goods_temp['price']),'extra_price'=>$goods_temp['extra_price']);
						$temp_params = '';
						if ($goods_temp['spec_id']) {
							$temp_params = D('Foodshop_goods')->format_spec_ids($goods_temp, $temp_params);
						}
						if ($goods_temp['spec']) {
							$temp_params = D('Foodshop_goods')->format_properties_ids($goods_temp, $temp_params);
						}
						$t_cookie['params'] = $temp_params;
						$productCart[] = $t_cookie;
						continue;
					}
				} elseif (isset($row['productParam'])) {
					foreach ($row['productParam'] as $r) {
						if ($r['type'] == 'spec') {
							$t_data = array('id' => $r['spec_id'], 'name' => '', 'type' => $r['type'], 'data' => array(
									array('id' => $r['id'], 'name' => $r['name'])
							));
						} else {
							$t_data = array('id' => 0, 'name' => '', 'type' => $r['type'], 'data' => '');
							$td = array();
							foreach ($r['data'] as $i => $d) {
								$t_data['id'] = $d['list_id'];
								$td[] = array('id' => $i, 'name' => $d['name']);
							}
							$t_data['data'] = $td;
						}
						$params[] = $t_data;
					}
				}
				$productCart[] = array('name' => $row['productName'], 'goods_id' => $row['productId'], 'num' => $row['count'], 'params' => $params,'extra_price'=>$row['extra_price']);
			}

			$cart_data = D('Foodshop_goods')->format_cart($productCart, $this->store['store_id'], $order_id);

			if ($cart_data['err_code']) {
				$this->error($cart_data['msg']);
			}
			$new_goods_list = $cart_data['data'];
			$total = $cart_data['total'];
			$price = $cart_data['price'];
			$now_time = time();
			if ($package_data = M('Foodshop_goods_package')->field(true)->where(array('id' => $package_id, 'store_id' => $this->store['store_id']))->find()) {
				$price = $package_data['price'];
				foreach ($new_goods_list as $index => $new_row) {
					$new_row['order_id'] = $order_id;
					$new_row['create_time'] = $now_time;
					$new_row['package_id'] = $package_id;
					$new_row['store_id'] = $this->store['store_id'];
					$new_row['extra_price'] = empty($new_row['extra_price'])?0:$new_row['extra_price'];
					D('Foodshop_order_detail')->add($new_row);
				}
				if ($order['package_ids']) {
					$package_ids = json_decode($order['package_ids'], true);
					$package_ids[] = $package_id;
				} else {
					$package_ids = array($package_id);
				}

				$save_order_data = array('package_ids' => json_encode($package_ids));
// 				$save_order_data = array('price' => $price + $order['price'], 'package_ids' => json_encode($package_ids));
			} else {
// 				$save_order_data = array('price' => $price + $order['price']);
				$goods_list = D('Foodshop_order_detail')->field(true)->where(array('order_id' => $order_id, 'store_id' => $this->store['store_id'], 'package_id' => 0))->select();
				$temp_list = array();
				foreach ($goods_list as $_row) {
					$_t_index = $_row['goods_id'];
					if (strlen($_row['spec']) > 0) {
						$_t_index = $_row['goods_id'] . '_' . md5($_row['spec']);
					}
					$temp_list[$_t_index] = $_row;
				}

				foreach ($new_goods_list as $index => $new_row) {
					if (isset($temp_list[$index])) {
						D('Foodshop_order_detail')->where(array('id' => $temp_list[$index]['id']))->save(array('num' => $new_row['num'] + $temp_list[$index]['num']));
						unset($temp_list[$index]);
					} else {
						$new_row['create_time'] = $now_time;
						$new_row['order_id'] = $order_id;
						$new_row['store_id'] = $this->store['store_id'];
						$new_row['extra_price'] = empty($new_row['extra_price'])?0:$new_row['extra_price'];
						D('Foodshop_order_detail')->add($new_row);
					}
				}

				D('Foodshop_order_temp')->where(array('order_id' => $order_id, 'store_id' => $this->store['store_id']))->delete();
			}
			if ($order['status'] < 2) {
				$save_order_data['status'] = 2;
			}
			$save_order_data['running_state'] = 0;
			$save_order_data['last_time'] = $_SERVER['REQUEST_TIME'];
			$save_order_data['running_time'] = $_SERVER['REQUEST_TIME'];

			if (M('Foodshop_order')->where(array('store_id' => $this->store['store_id'], 'order_id' => $order_id))->save($save_order_data)) {
				//配置打印
				D('Foodshop_order')->order_notice($order_id, $new_goods_list);
				$this->success('订单保存成功');
			} else {
				$this->error('订单保存失败，稍后重试！');
			}
		} else {
			$this->error('订单信息不存在！');
		}
	}

	public function foodshop_edit_order(){
		$now_order = M('Foodshop_order')->where(array('order_id'=>$_GET['order_id'],'store_id'=>$this->store['store_id']))->find();
		$this->assign('now_order',$now_order);

		$table_type = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();

		if(empty($table_type)){
			$this->error('该店铺没有设置桌台分类');
		}

		$this->assign('table_type',$table_type);


		$table_list = M('Foodshop_table')->where(array('tid'=>$now_order['table_type']))->order('`id` ASC')->select();

		$this->assign('table_list',$table_list);


		$this->display();
	}
	public function foodshop_edit_order_save(){
		$now_order = M('Foodshop_order')->where(array('order_id'=>$_POST['order_id'],'store_id'=>$this->store['store_id']))->find();

		$condition_order['order_id'] = $_POST['order_id'];
		$condition_order['store_id'] = $this->store['store_id'];
		$data['book_num'] = $_POST['book_num'];
		$data['table_type'] = $_POST['table_type'];
		$data['table_id'] = $_POST['table_id'];
		if(M('Foodshop_order')->where($condition_order)->data($data)->save()){
			M('Foodshop_table')->where(array('id'=>$now_order['table_id']))->data(array('status'=>'0'))->save();
			M('Foodshop_table')->where(array('id'=>$data['table_id']))->data(array('status'=>'1'))->save();
			$this->success('编辑成功');
		}else{
			$this->error('编辑订单失败，请重试');
		}
	}

	//只打印订单里面的商品，而且只用主打印机打印，一般用于用户结算前的打印。
	public function foodshop_print_order()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		D('Foodshop_order')->order_notice($order_id);//(array('order_id'=>$_GET['order_id'],'store_id'=>$this->store['store_id']))->find();
		$this->success('打印成功');
	}

	public function tmp_table(){

		$table_type = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		if(empty($table_type)){
			$this->error('该店铺没有设置桌台分类');
		}

		$this->assign('table_type',$table_type);

		if($_GET['type_id']){
			$now_table = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id'],'id'=>$_GET['type_id']))->find();
		}
		if(empty($now_table)){
			$now_table = $table_type[0];
		}
		$this->assign('now_table',$now_table);

		$table_list = M('Foodshop_table')->where(array('tid'=>$now_table['id']))->order('`id` ASC')->select();
		$this->assign('table_list',$table_list);

		$this->display();
	}

	public function book_list()
	{
		$table_id = isset($_GET['table_id']) && $_GET['table_id'] ? intval($_GET['table_id']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'table_id' => $table_id, 'status' => array('lt', 2), 'book_time' => array('gt', time()));
		$list = D('Foodshop_order')->field(true)->where($where)->order('book_time ASC')->select();
		$this->assign('list', $list);
		$this->display();
	}

	public function tmp_table_lock(){
		if(M('Foodshop_table')->where(array('store_id'=>$this->store['store_id'],'id'=>$_POST['id']))->setField('status',$_POST['lock'])){
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}

	/**
	 * 餐饮的排号管理
	 */
	public function queue()
	{
		$foodshop = M('Merchant_store_foodshop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		if ($foodshop['is_queue'] == 0) {
			$this->error('店铺没有排号功能');
		}

		$this->assign('queue_list', $this->queue_list(1));
		$store = array_merge($this->store, $foodshop);
		$this->assign('store', $store);
		$this->display();
	}

	public function change_queue()
	{
		$where = array('store_id' => $this->store['store_id']);
		$foodshop = M('Merchant_store_foodshop')->field(true)->where($where)->find();
		if (empty($foodshop)) {
			$this->error('店铺信息有问题');
		}
		if (M('Merchant_store_foodshop')->where($where)->save(array('queue_is_open' => 1 - intval($foodshop['queue_is_open']), 'queue_open_time' => time()))) {
			if ($foodshop['queue_is_open'] == 0) {
				//开启排号的时候清空以前的排号记录
				M('Foodshop_queue')->where($where)->delete();
				$msg = '点击关闭排号';
			} else {
				$msg = '点击开启排号';
			}
			$this->success($msg);
		} else {
			$this->error('状态修改失败');
		}
	}

	public function queue_list($param = 0)
	{
		$where = array('store_id' => $this->store['store_id']);
		$foodshop = M('Merchant_store_foodshop')->field(true)->where($where)->find();
		if ($foodshop['is_queue'] == 0) {
			if ($param) {
				$this->error('店铺没有排号功能');
				exit;
			} else {
				exit(json_encode(array('status' => 0)));
			}
		}
		if ($foodshop['queue_is_open'] == 0) {
			if ($param) {
// 				$this->error('店铺没有排号功能');
// 				exit;
				return false;
			} else {
				exit(json_encode(array('status' => 0)));
			}
		}

		//排队例表
		$queue_list = M('Foodshop_queue')->field(true)->where(array('store_id' => $this->store['store_id'], 'status' => 0))->order('id ASC')->select();
		$now_number_ids = null;
		$next_number_ids = null;
		$wait_number_list = array();
		foreach ($queue_list as $row) {
			if (!isset($now_number_ids[$row['table_type']])) {
				$now_number_ids[$row['table_type']] = $row['number'];
			} elseif (!isset($next_number_ids[$row['table_type']])) {
				$next_number_ids[$row['table_type']] = $row['number'];
			}
			if (isset($wait_number_list[$row['table_type']])) {
				$wait_number_list[$row['table_type']] ++;
			} else {
				$wait_number_list[$row['table_type']] = 1;
			}
		}
		$table_total = M('Foodshop_table')->field('count(tid) AS cnt, tid')->where(array('store_id' => $this->store['store_id'], 'status' => 0))->group('tid')->select();

		$temp = array();
		foreach ($table_total as $v) {
			$temp[$v['tid']] = $v['cnt'];
		}

		$table_type_list = M('Foodshop_table_type')->field(true)->where($where)->select();
		foreach ($table_type_list as &$t_row) {
			$t_row['now_number'] = '';
			$t_row['next_number'] = '';
			$t_row['wait'] = 0;
			$t_row['free'] = 0;
			if (isset($now_number_ids[$t_row['id']])) {
				$t_row['now_number'] = $now_number_ids[$t_row['id']];
			}
			if (isset($next_number_ids[$t_row['id']])) {
				$t_row['next_number'] = $next_number_ids[$t_row['id']];
			}
			if (isset($wait_number_list[$t_row['id']])) {
				$t_row['wait'] = $wait_number_list[$t_row['id']];
			}
			if (isset($temp[$t_row['id']])) {
				$t_row['free'] = $temp[$t_row['id']];
			}
		}
		if ($param) {
			return $table_type_list;
		} else {
			exit(json_encode(array('status' => 1, 'data' => $table_type_list)));
		}
	}

	public function queue_call()
	{
		$tid = isset($_POST['tid']) ? intval($_POST['tid']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'table_type' => $tid, 'status' => 0);
		if ($queue = M('Foodshop_queue')->where($where)->order('id ASC')->limit(1)->find()) {
			//TODO 发送模板消息

			if ($user = D('User')->where(array('uid' => $queue['uid']))->find()) {
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				$href = C('config.site_url').'/wap.php?c=Foodshop&a=queue&store_id=' . $this->store['store_id'];
				$model->sendTempMsg('OPENTM205984119', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => '尊敬的用户您好，已经排到您的号了', 'keyword1' => $queue['number'], 'keyword2' => date('Y.m.d H:i', $queue['create_time']), 'keyword3' => 1, 'remark' => '【' . $this->store['name'] . '】通知您进店用餐！'));
			}

			exit(json_encode(array('err_code' => false, 'msg' => '已叫号')));
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '没有排号了')));
		}
	}

	public function queue_cancel()
	{
		$tid = isset($_POST['tid']) ? intval($_POST['tid']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'status' => 0, 'table_type' => $tid);
		if ($queue = M('Foodshop_queue')->where($where)->order('id ASC')->limit(1)->find()) {
			$where['id'] = $queue['id'];
			if (M('Foodshop_queue')->where($where)->save(array('status' => 1))) {
				exit(json_encode(array('err_code' => false, 'msg' => 'ok')));
			} else {
				exit(json_encode(array('err_code' => true, 'msg' => '跳号失败，稍后重试')));
			}
		} else {
			exit(json_encode(array('err_code' => true, 'msg' => '不存在的信息')));
		}
	}

	public function queue_table()
	{
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
		$queue = M('Foodshop_queue')->where(array('table_type' => $tid, 'store_id' => $this->store['store_id'], 'status' => 1))->find();
		if (empty($queue)) {
			$this->error('号码不存在', 'javascript:parent.location.reload();');
		}
		$now_table = M('Foodshop_table_type')->where(array('store_id' => $this->store['store_id'], 'id' => $tid))->find();
		if (empty($now_table)) {
			$this->error('该店铺没有设置桌台分类', 'javascript:parent.location.reload();');
		}
		$this->assign('now_table', $now_table);
		$table_list = M('Foodshop_table')->where(array('tid' => $now_table['id'], 'status' => 0))->order('`id` ASC')->select();
		if (empty($table_list)) {
			$this->error('暂无空闲桌台', 'javascript:parent.location.reload();');
		}
		$this->assign('table_list', $table_list);
		$this->assign('tid', $tid);
		$this->display();
	}

	public function queue_save()
	{
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
		$queue = M('Foodshop_queue')->where(array('table_type' => $tid, 'store_id' => $this->store['store_id'], 'status' => 1))->find();
		if (empty($queue)) {
			$this->error('号码不存在');
		}
		if(M('Foodshop_table')->where(array('store_id'=>$this->store['store_id'],'id'=>$_POST['id']))->setField('status',$_POST['lock'])){
			$where = array('store_id' => $this->store['store_id'], 'id' => $queue['id'], 'table_type' => $tid);
			M('Foodshop_queue')->where($where)->save(array('status' => 2));
			$this->success('操作成功');
		}else{
			$this->error('操作失败');
		}
	}


	public function shop_export()
	{
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = $this->store['name'] . '订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		$condition_where = 'WHERE o.store_id = '.$this->store['store_id'];
		$where['store_id'] =$this->store['store_id'];


		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .= ' AND o.real_orderid = "'. htmlspecialchars($_GET['keyword']).'"';
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['order_id'] = $tmp_result['order_id'];
				$condition_where .= ' AND o.order_id = '. $tmp_result['order_id'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['username'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=  ' AND o.username = "'.  htmlspecialchars($_GET['keyword']).'"';
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['userphone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .= ' AND o.userphone = "'.  htmlspecialchars($_GET['keyword']).'"';
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['third_id'] =$_GET['keyword'];
			}

		}
		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';

		if($status == 100){
			$where['paid'] = 0;
			$condition_where .= ' AND o.paid=0';
		}else if ($status != -1) {
			$where['status'] = $status;
			$condition_where .= ' AND o.status='.$status;
		}

		if($pay_type&&$pay_type!='balance'){
			$where['pay_type'] = $pay_type;
			$condition_where .= ' AND o.pay_type="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$where['_string'] = "(`balance_pay`<>0 OR `merchant_balance` <> 0 )";
			$condition_where .= ' AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )';
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] =( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition_where .=  " AND (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		$count = D('Shop_order')->where($where)->count();
		//$count = D('Shop_order')->where(array('store_id' => $this->store['store_id']))->count();

		$length = ceil($count / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);
			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();
			$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);

			$objActSheet->setCellValue('A1', '订单编号');
			$objActSheet->setCellValue('B1', '商品名称');
			$objActSheet->setCellValue('C1', '商品进价');
			$objActSheet->setCellValue('D1', '单价');
			$objActSheet->setCellValue('E1', '单位');
			$objActSheet->setCellValue('F1', '数量');
			$objActSheet->setCellValue('G1', '商家名称');
			$objActSheet->setCellValue('H1', '店铺名称');
			$objActSheet->setCellValue('I1', '客户姓名');
			$objActSheet->setCellValue('J1', '客户电话');
			$objActSheet->setCellValue('K1', '客户地址');
			$objActSheet->setCellValue('L1', '订单总价');
			$objActSheet->setCellValue('M1', '平台优惠');
			$objActSheet->setCellValue('N1', '商家优惠');
			$objActSheet->setCellValue('O1', '实付总价');
			$objActSheet->setCellValue('P1', '在线支付金额');
			$objActSheet->setCellValue('Q1', '支付时间');
			$objActSheet->setCellValue('R1', '送达时间');
			$objActSheet->setCellValue('S1', '订单状态');
			$objActSheet->setCellValue('T1', '支付情况');
			//$objActSheet->setCellValue('R1', '支付情况');

			$sql = "SELECT  o.*, m.name AS merchant_name,d.name as good_name,d.price as good_price ,d.cost_price, d.num as good_num, s.name AS store_name FROM " . C('DB_PREFIX') . "shop_order AS o INNER JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=o.store_id INNER JOIN " . C('DB_PREFIX') . "merchant AS m ON `s`.`mer_id`=`m`.`mer_id` INNER JOIN " . C('DB_PREFIX') . "shop_order_detail AS d ON `d`.`order_id`=`o`.`order_id` ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";

			$result_list = D()->query($sql);

			$tmp_id = 0;
			if (!empty($result_list)) {
				$index = 1;
				foreach ($result_list as $value) {
					if($tmp_id == $value['real_orderid']){
						$objActSheet->setCellValueExplicit('A' . $index, '');
						$objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['cost_price']);
						$objActSheet->setCellValueExplicit('D' . $index, $value['good_price']);
						$objActSheet->setCellValueExplicit('E' . $index, $value['unit']);
						$objActSheet->setCellValueExplicit('F' . $index, $value['good_num']);
						$objActSheet->setCellValueExplicit('G' . $index,'');
						$objActSheet->setCellValueExplicit('H' . $index,'');
						$objActSheet->setCellValueExplicit('I' . $index, '');
						$objActSheet->setCellValueExplicit('J' . $index, '');
						$objActSheet->setCellValueExplicit('K' . $index, '');
						$objActSheet->setCellValueExplicit('L' . $index, '');
						$objActSheet->setCellValueExplicit('M' . $index, '');
						$objActSheet->setCellValueExplicit('N' . $index, '');
						$objActSheet->setCellValueExplicit('O' . $index, '');
						$objActSheet->setCellValueExplicit('P' . $index, '');
						$objActSheet->setCellValueExplicit('Q' . $index, '');
						$objActSheet->setCellValueExplicit('R' . $index, '');
						$objActSheet->setCellValueExplicit('S' . $index, '');
						$objActSheet->setCellValueExplicit('T' . $index, '');
						$index++;
					}else{
						$index++;
						$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['good_name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['cost_price']);
						$objActSheet->setCellValueExplicit('D' . $index, $value['good_price']);
						$objActSheet->setCellValueExplicit('E' . $index, $value['unit']);
						$objActSheet->setCellValueExplicit('F' . $index, $value['good_num']);
						$objActSheet->setCellValueExplicit('G' . $index, $value['merchant_name']);
						$objActSheet->setCellValueExplicit('H' . $index, $value['store_name']);
						$objActSheet->setCellValueExplicit('I' . $index, $value['username']);
						$objActSheet->setCellValueExplicit('J' . $index, $value['userphone'] . ' ');
						$objActSheet->setCellValueExplicit('K' . $index, $value['address'] . ' ');
						$objActSheet->setCellValueExplicit('L' . $index, floatval($value['total_price']));
						$objActSheet->setCellValueExplicit('M' . $index, floatval($value['balance_reduce']));
						$objActSheet->setCellValueExplicit('N' . $index, floatval($value['merchant_reduce']));
						$objActSheet->setCellValueExplicit('O' . $index, floatval($value['price']));
						$objActSheet->setCellValueExplicit('P' . $index, floatval($value['payment_money']));
						$objActSheet->setCellValueExplicit('Q' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('R' . $index, $value['use_time'] ? date('Y-m-d H:i:s', $value['use_time']) : '');
						$objActSheet->setCellValueExplicit('S' . $index, D('Shop_order')->status_list[$value['status']]);
						$objActSheet->setCellValueExplicit('T' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));
						$index++;
					}
					$tmp_id = $value['real_orderid'];

				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	public function shop_change_price()
	{
		if (empty($this->staff_session['is_change'])) {
			$this->error('您没有修改价格的权限!');
		}
		if (IS_POST || IS_AJAX) {
			$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
			$where = array('order_id' => $order_id, 'store_id' => $this->store['store_id']);
			$order = D('Shop_order')->get_order_detail($where);
			if (empty($order)) {
				$this->error('不存在的订单信息!');
			}
			if (!($order['paid'] == 0 && $order['status'] == 0)) {
				$this->error('该订单已经不能修改支付价格了!');
			}
			$change_price = isset($_POST['change_price']) ? floatval($_POST['change_price']) : $order['price'];
			$change_reason = isset($_POST['change_reason']) ? htmlspecialchars($_POST['change_reason']) : '';
			if ($change_price == $order['price']) {
				$this->error('您没有修改价格!');
			}
			if ($change_price <= 0) {
				$this->error('您不能把价格改成小于等于0的数');
			}
			$data = array('price' => $change_price);
			$data['last_staff'] = $this->staff_session['name'];
			$data['last_time'] = $_SERVER['REQUEST_TIME'];
			if (floatval($order['change_price']) == 0) {
				$data['change_price'] = $order['price'];
			}
			$data['change_price_reason'] = $change_reason;
			
			if (D('Shop_order')->where($where)->save($data)) {
				$phones = explode(' ', $this->store['phone']);
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 30, 'name' => $this->staff_session['name'], 'phone' => $phones[0], 'note' => $change_price));
				$this->success('修改成功');
			} else {
				$this->error('修改出错，稍后重试！');
			}
		} else {
			$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
			$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
			$this->assign('order', $order);

			$this->display();
		}
	}


	private function get_deliver_fee($store_id)
	{
		$store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find();
		//起步运费
		$delivery_fee = 0;
		//超出距离部分的单价
		$per_km_price = 0;
		//起步距离
		$basic_distance = 0;
		//减免配送费的金额
		$delivery_fee_reduce = 0;

		//起步运费
		$delivery_fee2 = 0;
		//超出距离部分的单价
		$per_km_price2 = 0;
		//起步距离
		$basic_distance2 = 0;

		if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {//平台配送|平台或自提
			if ($store_shop['s_is_open_own']) {//开启了店铺的独立配送费的设置
				//配送时段一的配置
				if ($store_shop['s_free_type'] == 0) {//免配送费

				} elseif ($store_shop['s_free_type'] == 1) {//不免
					$delivery_fee = $store_shop['s_delivery_fee'];
					$per_km_price = $store_shop['s_per_km_price'];
					$basic_distance = $store_shop['s_basic_distance'];
				} elseif ($store_shop['s_free_type'] == 2) {//满免
					if ($price < $store_shop['s_full_money']) {
						$delivery_fee = $store_shop['s_delivery_fee'];
						$per_km_price = $store_shop['s_per_km_price'];
						$basic_distance = $store_shop['s_basic_distance'];
					}
				}
				//配送时段二的配送
				if ($store_shop['s_free_type2'] == 0) {//免配送费

				} elseif ($store_shop['s_free_type2'] == 1) {//不免
					$delivery_fee2 = $store_shop['s_delivery_fee2'];
					$per_km_price2 = $store_shop['s_per_km_price2'];
					$basic_distance2 = $store_shop['s_basic_distance2'];
				} elseif ($store_shop['s_free_type2'] == 2) {//满免
					if ($price < $store_shop['s_full_money2']) {
						$delivery_fee2 = $store_shop['s_delivery_fee2'];
						$per_km_price2 = $store_shop['s_per_km_price2'];
						$basic_distance2 = $store_shop['s_basic_distance2'];
					}
				}
			} else {
				$delivery_fee = $this->config['delivery_fee'];
				$per_km_price = $this->config['per_km_price'];
				$basic_distance = $this->config['basic_distance'];

				$delivery_fee2 = $this->config['delivery_fee2'];
				$per_km_price2 = $this->config['per_km_price2'];
				$basic_distance2 = $this->config['basic_distance2'];
			}
			//使用平台的优惠（配送费的减免）
			// 			if ($d_tmp = $this->get_reduce($discounts, 2, $price)) {
			// 				$delivery_fee_reduce = $d_tmp['reduce_money'];
			// 			}
		} else {//商家配送|商家或自提|快递配送
			if ($store_shop['reach_delivery_fee_type'] == 0) {

			} elseif ($store_shop['reach_delivery_fee_type'] == 1) {
				$delivery_fee = $store_shop['delivery_fee'];
				$per_km_price = $store_shop['per_km_price'];
				$basic_distance = $store_shop['basic_distance'];

				$delivery_fee2 = $store_shop['delivery_fee2'];
				$per_km_price2 = $store_shop['per_km_price2'];
				$basic_distance2 = $store_shop['basic_distance2'];
			} elseif ($store_shop['reach_delivery_fee_type'] == 2)  {
				if ($price < $store_shop['no_delivery_fee_value']) {
					$delivery_fee = $store_shop['delivery_fee'];
					$per_km_price = $store_shop['per_km_price'];
					$basic_distance = $store_shop['basic_distance'];

					$delivery_fee2 = $store_shop['delivery_fee2'];
					$per_km_price2 = $store_shop['per_km_price2'];
					$basic_distance2 = $store_shop['basic_distance2'];
				}
			}
			if ($store_shop['reach_delivery_fee_type2'] == 0) {

			} elseif ($store_shop['reach_delivery_fee_type2'] == 1) {
				$delivery_fee2 = $store_shop['delivery_fee2'];
				$per_km_price2 = $store_shop['per_km_price2'];
				$basic_distance2 = $store_shop['basic_distance2'];
			} elseif ($store_shop['reach_delivery_fee_type2'] == 2)  {
				if ($price < $store_shop['no_delivery_fee_value2']) {
					$delivery_fee2 = $store_shop['delivery_fee2'];
					$per_km_price2 = $store_shop['per_km_price2'];
					$basic_distance2 = $store_shop['basic_distance2'];
				}
			}
		}

		return array('delivery_fee_reduce' => $delivery_fee_reduce, 'basic_distance' => $basic_distance, 'per_km_price' => $per_km_price, 'delivery_fee' => $delivery_fee, 'basic_distance2' => $basic_distance2, 'per_km_price2' => $per_km_price2, 'delivery_fee2' => $delivery_fee2);
	}
	public function mall_order_detail()
	{
		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
		$order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));

		$store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
		if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {
			$delivery_times = explode('-', $this->config['delivery_time']);
			$start_time = $delivery_times[0] . ':00';
			$stop_time = $delivery_times[1] . ':00';

			$delivery_times2 = explode('-', $this->config['delivery_time2']);
			$start_time2 = $delivery_times2[0] . ':00';
			$stop_time2 = $delivery_times2[1] . ':00';
		} else {
			$start_time = $store_shop['delivertime_start'];
			$stop_time = $store_shop['delivertime_stop'];

			$start_time2 = $store_shop['delivertime_start2'];
			$stop_time2 = $store_shop['delivertime_stop2'];
		}

		$have_two_time = 1;//是否两个时段 0：没有，1有

		$is_cross_day_1 = 0;//第一时间段是否跨天 0：不跨天，1：跨天
		$is_cross_day_2 = 0;//第二时间段是否跨天 0：不跨天，1：跨天

		$time = time() + $store_shop['send_time'] * 60;//默认的期望送达时间

		$format_second_time = 1;//是否要格式化时间段二

		$now_time_value = 1;//当前所处的时间段
		if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
			$start_time = strtotime(date('Y-m-d ') . '00:00');
			$stop_time = strtotime(date('Y-m-d ') . '23:59');
			$have_two_time = 0;
		} else {
			$start_time = strtotime(date('Y-m-d ') . $start_time);
			$stop_time = strtotime(date('Y-m-d ') . $stop_time);
			if ($stop_time < $start_time) {
				$stop_time = $stop_time + 86400;
				$is_cross_day_1 = 1;
			}

			if ($time < $start_time) {
				$time = $start_time;
			} elseif ($start_time <= $time && $time <= $stop_time) {

			} else {
				$format_second_time = 0;
				if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
					$have_two_time = 0;
					$time = $start_time + 86400;
					$start_time2 = strtotime(date('Y-m-d ') . '00:00');
					$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
				} else {
					$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
					$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
					if ($stop_time2 < $start_time2) {
						$stop_time2 = $stop_time2 + 86400;
						$is_cross_day_2 = 1;
					}

					if ($time < $start_time2) {
						$time = $start_time2;
						$now_time_value = 2;
					} elseif ($start_time2 <= $time && $time <= $stop_time2) {
						$now_time_value = 2;
					} else {
						$time = $start_time + 86400;
					}
				}
			}
		}
		if ($format_second_time) {//是否要格式化时间段二
			if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {
				$have_two_time = 0;
				$start_time2 = strtotime(date('Y-m-d ') . '00:00');
				$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
			} else {
				$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
				$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
				if ($stop_time2 < $start_time2) {
					$stop_time2 = $stop_time2 + 86400;
					$is_cross_day_2 = 1;
				}
			}
		}

		if ($have_two_time) {
			$this->assign(array('time_select_1' => date('H:i', $start_time) . '-' . date('H:i', $stop_time), 'time_select_2' => date('H:i', $start_time2) . '-' . date('H:i', $stop_time2)));
		} else {
			$this->assign(array('time_select_1' => date('H:i', $start_time) . '-' . date('H:i', $stop_time)));
		}
		$this->assign('have_two_time', $have_two_time);

		$distance = getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);

		$distance = $distance / 1000;

		$return = $this->get_deliver_fee($order['store_id']);

		$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
		$delivery_fee = $return['delivery_fee'] + round($pass_distance * $return['per_km_price'], 2);
		$delivery_fee = $delivery_fee - $return['delivery_fee_reduce'];
		$delivery_fee = $delivery_fee > 0 ? $delivery_fee : 0;

		$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
		$delivery_fee2 = $return['delivery_fee2'] + round($pass_distance * $return['per_km_price2'], 2);
		$delivery_fee2 = $delivery_fee2 - $return['delivery_fee_reduce'];
		$delivery_fee2 = $delivery_fee2 > 0 ? $delivery_fee2 : 0;


		$this->assign(array('delivery_fee' => $delivery_fee, 'delivery_fee2' => $delivery_fee2));
		$this->assign('arrive_datetime', date('Y-m-d H:i', $time));

		$this->assign('distance', round($distance, 2));
		$this->assign('store', $store_shop);
		$this->assign('order', $order);
		$this->display();

	}

	/**
	 * 商城订单更改配送方式  将快递 更改成 其他配送方式
	 */
	public function check_deliver()
	{
		$database = D('Shop_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$expect_use_time = isset($_POST['expect_use_time']) ? strtotime(htmlspecialchars($_POST['expect_use_time'])) : 0;
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->error('订单不存在！');
			exit;
		}

		if ($order['is_refund']) {
			$this->error('用户正在退款中~！');
			exit;
		}
		if ($order['paid'] == 0) {
			$this->error('订单未支付，不能接单！');
			exit;
		}
		if ($order['status'] > 0) {
			$this->error('该订单已处理，不能更改！');
			exit;
		}

		if ($order['is_pick_in_store'] != 3) {
			$this->error('不是快递配送，不能修改配送方式！');
			exit;
		}
		$d_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();

		if (in_array($d_shop['deliver_type'], array(2, 5))) {
			$this->error('店铺不支持快递以外的配送，不能修改配送方式！');
			exit;
		}
		$is_pick_in_store = $d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3 ? 0 : 1;



		if ($d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3) {
			$delivery_times = explode('-', $this->config['delivery_time']);
			$start_time = $delivery_times[0] . ':00';
			$stop_time = $delivery_times[1] . ':00';

			$delivery_times2 = explode('-', $this->config['delivery_time2']);
			$start_time2 = $delivery_times2[0] . ':00';
			$stop_time2 = $delivery_times2[1] . ':00';
		} else {
			$start_time = $d_shop['delivertime_start'];
			$stop_time = $d_shop['delivertime_stop'];

			$start_time2 = $d_shop['delivertime_start2'];
			$stop_time2 = $d_shop['delivertime_stop2'];
		}



		$time = $expect_use_time ? $expect_use_time : (time() + $d_shop['send_time'] * 60);//默认的期望送达时间


		$now_time_value = 1;//当前所处的时间段
		if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
		} else {
			$start_time = strtotime(date('Y-m-d ') . $start_time);
			$stop_time = strtotime(date('Y-m-d ') . $stop_time);
			if ($stop_time < $start_time) {
				$stop_time = $stop_time + 86400;
			}

			if ($time < $start_time) {
				$time = $start_time;
			} elseif ($start_time <= $time && $time <= $stop_time) {

			} else {
				if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
					$time = $start_time + 86400;
					$start_time2 = strtotime(date('Y-m-d ') . '00:00');
					$stop_time2 = strtotime(date('Y-m-d ') . '23:59');
				} else {
					$start_time2 = strtotime(date('Y-m-d ') . $start_time2);
					$stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
					if ($stop_time2 < $start_time2) {
						$stop_time2 = $stop_time2 + 86400;
					}

					if ($time < $start_time2) {
						$time = $start_time2;
						$now_time_value = 2;
					} elseif ($start_time2 <= $time && $time <= $stop_time2) {
						$now_time_value = 2;
					} else {
						$time = $start_time + 86400;
					}
				}
			}
		}
		$distance = getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);
		$distance = $distance / 1000;
		$return = $this->get_deliver_fee($order['store_id']);
		if ($now_time_value == 1) {
			$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
			$delivery_fee = $return['delivery_fee'] + round($pass_distance * $return['per_km_price'], 2);
			$delivery_fee = $delivery_fee - $return['delivery_fee_reduce'];
			$delivery_fee = $delivery_fee > 0 ? $delivery_fee : 0;
		} else {
			$pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
			$delivery_fee = $return['delivery_fee2'] + round($pass_distance * $return['per_km_price2'], 2);
			$delivery_fee = $delivery_fee - $return['delivery_fee_reduce'];
			$delivery_fee = $delivery_fee > 0 ? $delivery_fee : 0;
		}


		$data['status'] = 1;
		$condition['status'] = 0;
		$data['order_status'] = 1;
		$data['is_pick_in_store'] = $is_pick_in_store;
		$data['last_staff'] = $this->staff_session['name'];
		$data['last_time'] = time();
		$data['expect_use_time'] = $time;
		$data['last_staff'] = $this->staff_session['name'];
		if ($d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3) {
			$data['no_bill_money'] = $delivery_fee;
		}

		if ($database->where($condition)->save($data)) {
		    $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
		    if ($result['error_code']) {
		        D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
		        $this->error($result['msg']);
		        exit;
		    }
// 			$supply_db_table = D('Deliver_supply');
// 			$old = $supply_db_table->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
// 			if (empty($old)) {
// 				$supply = array();
// 				if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
// 				$supply['order_id'] = $order_id;
// 				$supply['paid'] = $order['paid'];
// 				$supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
// 				$supply['pay_type'] = $order['pay_type'];
// 				$supply['money'] = $order['price'];
// 				$supply['deliver_cash'] = round($order['price']+$order['extra_price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
// 				$supply['deliver_cash'] = max(0, $supply['deliver_cash']);
// 				$supply['store_id'] = $this->store['store_id'];
// 				$supply['store_name'] = $this->store['name'];
// 				$supply['mer_id'] = $this->store['mer_id'];
// 				$supply['from_site'] = $this->store['adress'];
// 				$supply['from_lnt'] = $this->store['long'];
// 				$supply['from_lat'] = $this->store['lat'];

// 				//目的地
// 				$supply['aim_site'] =  $order['address'];
// 				$supply['aim_lnt'] = $order['lng'];
// 				$supply['aim_lat'] = $order['lat'];
// 				$supply['name']  = $order['username'];
// 				$supply['phone'] = $order['userphone'];

// 				$supply['status'] =  1;
// 				$supply['type'] = $is_pick_in_store;
// 				$supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店
// 				$supply['create_time'] = $_SERVER['REQUEST_TIME'];
// 				$supply['appoint_time'] = $time;
// 				$supply['note'] = $order['desc'];

// 				$supply['order_time'] = $order['create_time'];
// 				$supply['freight_charge'] = $delivery_fee;
// 				$supply['distance'] = round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2);

// 				if ($supply_db_table->create($supply) != false) {
// 					if ($addResult = D('Deliver_supply')->add($supply)) {
// 					} else {
// 						$this->error('接单失败');
// 					}
// 				} else {
// 					$this->error('已接单');
// 				}
// 			}
			$phones = explode(' ', $this->store['phone']);
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
			$this->success('已接单');
		} else {
			$this->error('接单失败');
		}
	}

	public function check_shop_goods_stock()
	{
		$now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		if ($result = D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type'])) {
			exit(json_encode(array('status' => 1)));
		} else {
			exit(json_encode(array('status' => 0)));
		}
	}

	public function shop_goods_stock()
	{
		$now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$this->assign('goods_list', D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type']));
		$this->display();
	}

	public function goods_stock_export()
	{
		set_time_limit(0);

		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = $this->store['name'] . '库存警报提醒';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();
		$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

		$objActSheet->setCellValue('A1', '商品编号');
		$objActSheet->setCellValue('B1', '商品名称');
		$objActSheet->setCellValue('C1', '商品单价');
		$objActSheet->setCellValue('D1', '剩余库存');

		$now_shop = D('Merchnat_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
		$result_list = D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type']);
		if (!empty($result_list)) {
			$index = 2;
			foreach ($result_list as $value) {
				$objActSheet->setCellValueExplicit('A' . $index, $value['number']);
				$objActSheet->setCellValueExplicit('B' . $index, $value['name']);
				$objActSheet->setCellValueExplicit('C' . $index, $value['price']);
				$objActSheet->setCellValueExplicit('D' . $index, $value['stock_num']);
				$index ++;
			}
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="' . $title . ':' . date("Y-m-d H:i:s") . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}


	public function report()
	{
		$this->assign('type', isset($_GET['type']) ? intval($_GET['type']) : 0);
		$this->display();
	}

	public function ajax_report()
	{
		$type = isset($_POST['type']) ? intval($_POST['type']) : 0;
		$day = isset($_POST['day']) ? intval($_POST['day']) : 0;
		$period = isset($_POST['period']) ? htmlspecialchars($_POST['period']) : '';
		$stime = $etime = 0;
		if ($day) {
			$stime = strtotime("-{$day} day");
			$etime = time();
		}
		if ($period) {
			$time_array = explode('-', $period);
			$stime = strtotime($time_array[0]);
			$etime = strtotime($time_array[1]);
		}
		$where = array('paid' => 1, 'store_id' => $this->store['store_id']);
		if ($stime && $etime) {
			$where['pay_time'] = array(array('gt', $stime), array('lt', $etime));
		}

		$store_pays = D('Store_pay')->field(true)->where(array('store_id' => $this->store['store_id']))->select();
		$offline_pay_type = array(0 => array('name' => '现金支付', 'id' => 0, 'store_id' => $this->store['store_id']));
		foreach ($store_pays as $pay) {
			$offline_pay_type[$pay['id']] = $pay;
		}
		if ($type == 0) {//餐饮
			$field = 'sum(total_money - wx_cheap) AS total_price, sum(total_money - system_balance - system_coupon_price - system_score_money - merchant_balance_pay - merchant_balance_give - merchant_discount_money - merchant_coupon_price - pay_money) AS offline_price, sum(pay_money) AS online_price, sum(system_balance + system_coupon_price + system_score_money + merchant_balance_pay + merchant_balance_give + merchant_discount_money + merchant_coupon_price) as balance, 0 as offline_pay';
			$where['business_type'] = 'foodshop';
			$order = D('Plat_order')->field($field)->where($where)->find();

			$field = 'sum(price) AS total_price, sum(price - balance_pay - card_price - payment_money - merchant_balance - card_give_money - score_deducte) AS offline_price, sum(payment_money) AS online_price, sum(balance_pay + card_price + merchant_balance + card_give_money + score_deducte) as balance, offline_pay';
			$where['business_type'] = 'foodshop';

			$order_list = D('Store_order')->field($field)->where($where)->group('offline_pay')->select();
			if (empty($order_list)) $order_list = array();
			if ($order) $order_list[] = $order;
		} else {
			$field = 'sum(price) AS total_price, sum(price - balance_pay - card_price - payment_money - merchant_balance - card_give_money - score_deducte) AS offline_price, sum(payment_money) AS online_price, sum(balance_pay + card_price + merchant_balance + card_give_money + score_deducte) as balance, offline_pay';
			$order_list = D('Store_order')->field($field)->where($where)->group('offline_pay')->select();
		}
		$money_list = array();
		foreach ($order_list as $order) {

			if (isset($money_list['total_price'])) {
				$money_list['total_price']['money'] += floatval($order['total_price']);
			} else {
				$money_list['total_price'] = array('pay_type' => '支付总金额', 'money' => floatval($order['total_price']), 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'all')), 'report_detail' => U('report_detail', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'all')));
			}

			if (isset($money_list['online_price'])) {
				$money_list['online_price']['money'] += floatval($order['balance']) + floatval($order['online_price']);
			} else {
				$money_list['online_price'] = array('pay_type' => '在线支付', 'money' => floatval($order['balance']) + floatval($order['online_price']), 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'online')), 'report_detail' => U('report_detail', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => 'online')));
			}

			if (isset($money_list['offline_' + $order['offline_pay']])) {
				$money_list['offline_' . $order['offline_pay']]['money'] += floatval($order['offline_price']);
			} else {
				$money_list['offline_' . $order['offline_pay']] = array('pay_type' => $offline_pay_type[$order['offline_pay']]['name'], 'money' => floatval($order['offline_price']), 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $order['offline_pay'])), 'report_detail' => U('report_detail', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $order['offline_pay'])));
			}
		}
		exit(json_encode(array('error_code' => false, 'data' => $money_list, 'count' => count($money_list))));
	}

	public function report_detail()
	{
		$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$stime = isset($_GET['stime']) ? intval($_GET['stime']) : 0;
		$etime = isset($_GET['etime']) ? intval($_GET['etime']) : 0;
		$pay_type = isset($_GET['pay_type']) ? htmlspecialchars($_GET['pay_type']) : 'all';

		$store_pays = D('Store_pay')->field(true)->where(array('store_id' => $this->store['store_id']))->select();
		$offline_pay_type = array(0 => array('name' => '现金支付', 'id' => 0, 'store_id' => $this->store['store_id']));
		foreach ($store_pays as $pay) {
			$offline_pay_type[$pay['id']] = $pay;
		}
		if ($pay_type == 'all') {
			$pay_type_title = '总的支付';
		} elseif ($pay_type == 'online') {
			$pay_type_title = '在线支付';
		} else {
			$pay_type_title = $offline_pay_type[$pay_type]['name'];
		}
		$mode = new Model();
		if ($type == 0) {
			$sql = "SELECT f.order_id, f.real_orderid, f.name as username, 0 as offline_pay, f.phone as userphone, (p.total_money - p.system_balance - p.system_coupon_price - p.system_score_money - p.merchant_balance_pay - p.merchant_balance_give - p.merchant_discount_money - p.merchant_coupon_price - p.pay_money) AS offline_price, f.total_price, f.price, (p.pay_money+p.system_balance + p.system_coupon_price + p.system_score_money + p.merchant_balance_pay + p.merchant_balance_give + p.merchant_discount_money + p.merchant_coupon_price) as online_price, (f.total_price-f.price) as discount_price, p.pay_time, p.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "plat_order AS p ON p.business_id=f.order_id AND p.business_type='foodshop' WHERE p.paid=1 AND p.pay_time>'{$stime}' AND p.pay_time<'{$etime}' AND f.store_id='{$this->store['store_id']}' AND f.status>2";
			if ($pay_type != 'all' && $pay_type != 'online') {
				$sql .= " AND p.pay_type=''";
			} elseif ($pay_type == 'online') {
				$sql .= " AND (p.system_balance>0 OR p.system_coupon_price>0 OR p.system_score_money>0 OR p.merchant_balance_pay>0 OR p.merchant_balance_give>0 OR p.merchant_discount_money>0 OR p.merchant_coupon_price>0 OR p.pay_money>0)";
			}
			$temp_list = $mode->query($sql);
			foreach ($temp_list as $row) {
				if (isset($order_list[$row['order_id']])) {
					$order_list[$row['order_id']]['online_price'] += $row['online_price'];
					$order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
				} else {
					$order_list[$row['order_id']] = $row;
				}
			}
			$sql = "SELECT f.order_id, f.real_orderid, f.name as username, s.offline_pay, f.phone as userphone, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, f.total_price, f.price, (f.total_price-f.price) as discount_price, s.pay_time, s.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "store_order AS s ON s.business_id=f.order_id AND s.business_type='foodshop' WHERE s.paid=1 AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}' AND f.store_id='{$this->store['store_id']}' AND f.status>2";
			if ($pay_type != 'all' && $pay_type != 'online') {
				$sql .= " AND s.offline_pay='{$pay_type}'";
			} elseif ($pay_type == 'online') {
				$sql .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
			}
			$temp_list = $mode->query($sql);
			foreach ($temp_list as $row) {
				if (isset($order_list[$row['order_id']])) {
					$order_list[$row['order_id']]['online_price'] += $row['online_price'];
					$order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
					$order_list[$row['order_id']]['offline_pay'] = $row['offline_pay'];
				} else {
					$order_list[$row['order_id']] = $row;
				}
			}
		} else {
			$where = "s.paid=1 AND s.store_id={$this->store['store_id']}";
			if ($stime && $etime) {
				$where .= " AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}'";
			}
			if ($pay_type != 'all' && $pay_type != 'online') {
				$where .= " AND s.offline_pay='{$pay_type}'";
			} elseif ($pay_type == 'online') {
				$where .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
			}
			$sql = "SELECT s.total_price, s.price, s.discount_price, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, u.nickname as username, u.phone as userphone, s.order_id as real_orderid, s.pay_time, s.pay_type, s.offline_pay FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user as u ON s.uid=u.uid WHERE {$where}";
			$order_list = $mode->query($sql);
		}
		$total_money = 0;
		foreach ($order_list as &$order) {
			if ($pay_type == 'all') {
				$total_money += $order['price'];
			} elseif ($pay_type == 'online') {
				$total_money += $order['online_price'];
			} else {
				$total_money += $order['offline_price'];
			}
			if ($order['pay_type']) {
				$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], 0);
			} else {
				$order['pay_type'] = $offline_pay_type[$order['offline_pay']]['name'];
			}
			$order['online_price'] = floatval($order['online_price']);
		}
		$this->assign(array('order_list' => $order_list, 'stime' => date('Y-m-d', $stime), 'etime' => date('Y-m-d', $etime), 'pay_type_title' => $pay_type_title, 'total_money' => $total_money, 'report_export' => U('report_export', array('type' => $type, 'stime' => $stime, 'etime' => $etime, 'pay_type' => $pay_type))));
		$this->display();
	}

	public function report_export()
	{
		set_time_limit(0);

		$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$stime = isset($_GET['stime']) ? intval($_GET['stime']) : 0;
		$etime = isset($_GET['etime']) ? intval($_GET['etime']) : 0;
		$pay_type = isset($_GET['pay_type']) ? htmlspecialchars($_GET['pay_type']) : 'all';

		$store_pays = D('Store_pay')->field(true)->where(array('store_id' => $this->store['store_id']))->select();
		$offline_pay_type = array(0 => array('name' => '现金支付', 'id' => 0, 'store_id' => $this->store['store_id']));
		foreach ($store_pays as $pay) {
			$offline_pay_type[$pay['id']] = $pay;
		}
		if ($pay_type == 'all') {
			$pay_type_title = '总的支付';
		} elseif ($pay_type == 'online') {
			$pay_type_title = '在线支付';
		} else {
			$pay_type_title = $offline_pay_type[$pay_type]['name'];
		}
		$mode = new Model();
		if ($type == 0) {
			$sql = "SELECT f.order_id, f.real_orderid, f.name as username, 0 as offline_pay, f.phone as userphone, (p.total_money - p.system_balance - p.system_coupon_price - p.system_score_money - p.merchant_balance_pay - p.merchant_balance_give - p.merchant_discount_money - p.merchant_coupon_price - p.pay_money) AS offline_price, f.total_price, f.price, (p.pay_money+p.system_balance + p.system_coupon_price + p.system_score_money + p.merchant_balance_pay + p.merchant_balance_give + p.merchant_discount_money + p.merchant_coupon_price) as online_price, (f.total_price-f.price) as discount_price, p.pay_time, p.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "plat_order AS p ON p.business_id=f.order_id AND p.business_type='foodshop' WHERE p.paid=1 AND p.pay_time>'{$stime}' AND p.pay_time<'{$etime}' AND f.store_id='{$this->store['store_id']}' AND f.status>2";
			if ($pay_type != 'all' && $pay_type != 'online') {
				$sql .= " AND p.pay_type=''";
			} elseif ($pay_type == 'online') {
				$sql .= " AND (p.system_balance>0 OR p.system_coupon_price>0 OR p.system_score_money>0 OR p.merchant_balance_pay>0 OR p.merchant_balance_give>0 OR p.merchant_discount_money>0 OR p.merchant_coupon_price>0 OR p.pay_money>0)";
			}
			$temp_list = $mode->query($sql);
			foreach ($temp_list as $row) {
				if (isset($order_list[$row['order_id']])) {
					$order_list[$row['order_id']]['online_price'] += $row['online_price'];
					$order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
				} else {
					$order_list[$row['order_id']] = $row;
				}
			}
			$sql = "SELECT f.order_id, f.real_orderid, f.name as username, s.offline_pay, f.phone as userphone, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, f.total_price, f.price, (f.total_price-f.price) as discount_price, s.pay_time, s.pay_type FROM " . C('DB_PREFIX') . "foodshop_order AS f INNER JOIN " . C('DB_PREFIX') . "store_order AS s ON s.business_id=f.order_id AND s.business_type='foodshop' WHERE s.paid=1 AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}' AND f.store_id='{$this->store['store_id']}' AND f.status>2";
			if ($pay_type != 'all' && $pay_type != 'online') {
				$sql .= " AND s.offline_pay='{$pay_type}'";
			} elseif ($pay_type == 'online') {
				$sql .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
			}
			$temp_list = $mode->query($sql);
			foreach ($temp_list as $row) {
				if (isset($order_list[$row['order_id']])) {
					$order_list[$row['order_id']]['online_price'] += $row['online_price'];
					$order_list[$row['order_id']]['offline_price'] += $row['offline_price'];
					$order_list[$row['order_id']]['offline_pay'] = $row['offline_pay'];
				} else {
					$order_list[$row['order_id']] = $row;
				}
			}
		} else {
			$where = "s.paid=1 AND s.store_id={$this->store['store_id']}";
			if ($stime && $etime) {
				$where .= " AND s.pay_time>'{$stime}' AND s.pay_time<'{$etime}'";
			}
			if ($pay_type != 'all' && $pay_type != 'online') {
				$where .= " AND s.offline_pay='{$pay_type}'";
			} elseif ($pay_type == 'online') {
				$where .= " AND (s.balance_pay>0 OR s.card_price OR s.payment_money>0 OR s.merchant_balance>0 OR s.card_give_money>0 OR s.score_deducte>0)";
			}
			$sql = "SELECT s.total_price, s.price, s.discount_price, (s.price - s.balance_pay - s.card_price - s.payment_money - s.merchant_balance - s.card_give_money - s.score_deducte) AS offline_price, (s.balance_pay + s.card_price + s.payment_money + s.merchant_balance + s.card_give_money + s.score_deducte) AS online_price, u.nickname as username, u.phone as userphone, s.order_id as real_orderid, s.pay_time, s.pay_type, s.offline_pay FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user as u ON s.uid=u.uid WHERE {$where}";
			$order_list = $mode->query($sql);
		}
		$total_money = 0;
		foreach ($order_list as &$order) {
			if ($pay_type == 'all') {
				$total_money += $order['price'];
			} elseif ($pay_type == 'online') {
				$total_money += $order['online_price'];
			} else {
				$total_money += $order['offline_price'];
			}
			if ($order['pay_type']) {
				$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], 0);
			} else {
				$order['pay_type'] = $offline_pay_type[$order['offline_pay']]['name'];
			}
			$order['online_price'] = floatval($order['online_price']);
		}

		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = $this->store['name'] . $pay_type_title . '统计报表';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		$objExcel->setActiveSheetIndex(0);
		$objActSheet = $objExcel->getActiveSheet();
		$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
		$objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);

		$objActSheet->setCellValue('A1', '订单号');
		$objActSheet->setCellValue('B1', '用户姓名');
		$objActSheet->setCellValue('C1', '用户电话');
		$objActSheet->setCellValue('D1', '订单总价');
		$objActSheet->setCellValue('E1', '优惠金额');
		$objActSheet->setCellValue('F1', '实付金额');
		$objActSheet->setCellValue('G1', '在线支付金额');
		$objActSheet->setCellValue('H1', '线下支付金额');
		$objActSheet->setCellValue('I1', '支付时间');
		$objActSheet->setCellValue('J1', '支付类型');
		if (!empty($order_list)) {
			$index = 2;
			foreach ($order_list as $value) {
				$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
				$objActSheet->setCellValueExplicit('B' . $index, $value['username']);
				$objActSheet->setCellValueExplicit('C' . $index, $value['userphone']);
				$objActSheet->setCellValueExplicit('D' . $index, $value['total_price']);
				$objActSheet->setCellValueExplicit('E' . $index, $value['discount_price']);
				$objActSheet->setCellValueExplicit('F' . $index, $value['price']);
				$objActSheet->setCellValueExplicit('G' . $index, $value['online_price']);
				$objActSheet->setCellValueExplicit('H' . $index, $value['offline_price']);
				$objActSheet->setCellValueExplicit('I' . $index, date('Y-m-d H:i:s', $value['pay_time']));
				$objActSheet->setCellValueExplicit('J' . $index, $value['pay_type']);
				$index ++;
			}
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="' . $title . ':' . date("Y-m-d H:i:s") . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	public function market()
	{
		$this->assign(array('name' => $this->staff_session['name'], 'date' => date('Y-m-d')));
		$offline_pay_list = M('Store_pay')->where(array('store_id' => $this->store['store_id']))->order('`id` ASC')->select();
		$this->assign('offline_pay_list', $offline_pay_list);
		$this->assign('store_id', $this->store['store_id']);
		$this->assign('is_change', $this->staff_session['is_change']);
		$this->display();
	}

	public function ajax_shop_goods()
	{
		$is_refresh = isset($_POST['refresh']) ? intval($_POST['refresh']) : 1;
		$product_list = D('Shop_goods')->get_list($this->store['store_id'], $is_refresh);
		if ($product_list) {
			exit(json_encode(array('err_code' => false, 'data' => $product_list)));
		} else {
			exit(json_encode(array('err_code' => true, 'data' => '暂无商品')));
		}
	}
	
	/**
	 * 获取会员卡信息
	 */
	public function ajax_card()
	{
		$key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
		if (strlen($key) == 11 && $user = D('User')->field(true)->where(array('phone' => $key))->find()) {
			$card = D('Card_userlist')->field(true)->where(array('uid' => $user['uid'], 'mer_id' => $this->store['mer_id']))->find();
			$card_source = M('Card_new')->where(array('mer_id' => $this->store['mer_id'], 'status' => 1))->find();
			if (empty($card) || empty($card_source)) {
				exit(json_encode(array('err_code' => true, 'data' => '没有会员卡信息')));
			} else {
				$return = array('name' => $user['truename'] ? $user['truename'] : $user['nickname'], 'sex' => $user['sex'] == 1 ? '男' : '女', 'card_id' => $card['id'], 'phone' => $user['phone']);
				$return['card_money'] = $card['card_money'] + $card['card_money_give'];
				$return['card_score'] = $card['card_score'];
				$return['physical_id'] = $card['physical_id'];
				$return['uid'] = $user['uid'];
				$return['discount'] = $card_source['discount'];
				
				$return['card_new'] = D('Card_new')->get_use_coupon_by_params($user['uid'], $this->store['mer_id'], 'shop');
				exit(json_encode(array('err_code' => false, 'data' => $return)));
			}
		} else {
			if ($card = D('Card_userlist')->field(true)->where(array('id' => $key, 'mer_id' => $this->store['mer_id']))->find()) {
				$card_source = M('Card_new')->where(array('mer_id' => $this->store['mer_id'], 'status' => 1))->find();
				if (empty($card_source)) {
					exit(json_encode(array('err_code' => true, 'data' => '没有会员卡信息')));
				}
				if ($user = D('User')->field(true)->where(array('uid' => $card['uid']))->find()) {
					$return = array('name' => $user['truename'] ? $user['truename'] : $user['nickname'], 'sex' => $user['sex'] == 1 ? '男' : '女', 'card_id' => $card['id'], 'phone' => $user['phone']);
					$return['card_money'] = $card['card_money'] + $card['card_money_give'];
					$return['card_score'] = $card['card_score'];
					$return['physical_id'] = $card['physical_id'];
					$return['uid'] = $user['uid'];
					$return['discount'] = $card_source['discount'];
					
					$return['card_new'] = D('Card_new')->get_use_coupon_by_params($user['uid'], $this->store['mer_id'], 'shop');
					exit(json_encode(array('err_code' => false, 'data' => $return)));
				} else {
					exit(json_encode(array('err_code' => true, 'data' => '此卡找不到相应的用户信息')));
				}
			} else {
				exit(json_encode(array('err_code' => true, 'data' => '没有会员卡信息')));
			}
		}
	}
	
	public function shop_order_save()
	{
		//order_from = 6;
		$data = isset($_POST['data']) ? ($_POST['data']) : '';
		$uid = isset($data['card_data']['uid']) && $data['card_data']['uid'] ? intval($data['card_data']['uid']) : 0;
// 		echo '<pre/>';
// 		print_r($_POST);die;
		$store_id = $this->store['store_id'];
		$return = D('Shop_goods')->checkCart($store_id, $uid, $data['goods_data'], 2);
// 		$return = $this->check_cart($data['goods_data'], $uid);
		if ($return['error_code']) exit(json_encode($return));
		if (IS_POST) {
			
			if (!($user = D('User')->field(true)->where(array('uid' => $uid))->find())) {
				$uid = 0;
			}
			
			D('Shop_order')->where(array('staff_id' => $this->staff_session['id'], 'order_from' => 6, 'paid' => 0, 'is_del' => 0))->save(array('is_del' => 1));
			
			$now_time = time();
			$order_data = array();
			$order_data['mer_id'] = $return['mer_id'];
			$order_data['store_id'] = $return['store_id'];
			$order_data['uid'] = $uid;//TODO
			$order_data['staff_id'] = $this->staff_session['id'];

			$order_data['desc'] = '';
			$order_data['create_time'] = $now_time;
			$order_data['last_time'] = $now_time;
			$order_data['invoice_head'] = '';
			$order_data['village_id'] = 0;

			$order_data['num'] = $return['total'];
			$order_data['packing_charge'] = $return['packing_charge'];//打包费
			$order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
			$order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
			$orderid  = date('ymdhis').substr(microtime(), 2, 8 - strlen($uid)). $uid;
			$order_data['real_orderid'] = $orderid;
			$order_data['no_bill_money'] = 0;//无需跟平台对账的金额
			
			$order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
			$delivery_fee = $order_data['freight_charge'] = 0;//运费
			$order_data['username'] = isset($user['nickname']) && $user['nickname'] ? $user['nickname'] : '';
			$order_data['userphone'] = isset($user['phone']) && $user['phone'] ? $user['phone'] : '';
			$order_data['address'] = '';
			$order_data['address_id'] = 0;
			$order_data['pick_id'] = 0;
			$order_data['status'] = 0;
			$order_data['order_from'] = 6;
			$order_data['expect_use_time'] = 0;//客户期望使用时间
			
			
			$order_data['goods_price'] = $return['price'];//商品的价格
			$order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
			$order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
			$order_data['total_price'] = $return['price'] + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
			$order_data['price'] = $order_data['discount_price'] + $delivery_fee + $return['packing_charge'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'];//实际要支付的价格
			$order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情
			
			$discountMsg = '';
			foreach($return['discount_list'] as $row) {
			    switch ($row['discount_type']) {
			        case 1:
			            $discountMsg .= '平台首单优惠,';
			            break;
			        case 2:
			            $discountMsg .= '平台满减优惠,';
			            break;
			        case 3:
			            $discountMsg .= '店铺首单优惠,';
			            break;
			        case 4:
			            $discountMsg .= '店铺满减优惠,';
			            break;
			    }
			}
			
			foreach ($return['goods'] as $goods) {
			    switch ($goods['discount_type']) {
			        case 1:
			            $discountMsg .= '店铺折扣优惠,';
			            break;
			        case 2:
			            $discountMsg .= '分类折扣优惠,';
			            break;
			    }
			}
// 			if ($return['price'] - $return['store_discount_money'] > 0) {
// 				$order_data['discount_detail'] = '店铺折扣优惠：' . floatval($return['price'] - $return['store_discount_money']);
// 			}
// 			if ($return['store_discount_money'] - $return['vip_discount_money'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']) : 'VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']);
// 			}
// 			if ($return['sys_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台首单减：' . $return['sys_first_reduce'] : '平台首单减：' . $return['sys_first_reduce'];
// 			}
// 			if ($return['sys_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台满减：' . $return['sys_full_reduce'] : '平台满减：' . $return['sys_full_reduce'];
// 			}
// 			if ($return['sto_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺首单减：' . $return['sto_first_reduce'] : '店铺首单减：' . $return['sto_first_reduce'];
// 			}
// 			if ($return['sto_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺满减：' . $return['sto_full_reduce'] : '店铺满减：' . $return['sto_full_reduce'];
// 			}

			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
			
			if ($order_id = D('Shop_order')->add($order_data)) {
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
// 				if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
// 					D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
// 					//D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
// 				}
				$detail_obj = D('Shop_order_detail');
				$goods_obj = D("Shop_goods");
				foreach ($return['goods'] as $grow) {
					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time(),'extra_price'=>$grow['extra_price']);
					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
					$detail_data['discount_type'] = intval($grow['discount_type']);
					$detail_data['discount_rate'] = $grow['discount_rate'];
					$detail_data['sort_id'] = $grow['sort_id'];
					$detail_data['old_price'] = floatval($grow['old_price']);
					$detail_data['discount_price'] = floatval($grow['discount_price']);
					D('Shop_order_detail')->add($detail_data);
					$order_data['reduce_stock_type'] && $goods_obj->update_stock($grow);//修改库存
				}
				if ($user['openid']) {
					$keyword2 = '';
					$pre = '';
					foreach ($return['goods'] as $menu) {
						$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
						$pre = '\n\t\t\t';
					}
					$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order_id . '&mer_id=' . $order_data['mer_id'] . '&store_id=' . $order_data['store_id'];
					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['shop_alias_name'].'下单成功，感谢您的使用！'));
				}

				$msg = ArrayToStr::array_to_str($order_id, 'shop_order');
				$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
				$op->printit($return['mer_id'], $return['store_id'], $msg, 0);

				$str_format = ArrayToStr::print_format($order_id, 'shop_order');
				foreach ($str_format as $print_id => $print_msg) {
					$print_id && $op->printit($return['mer_id'], $return['store_id'], $print_msg, 0, $print_id);
				}
				
				
				$sms_data = array('mer_id' => $return['mer_id'], 'store_id' => $return['store_id'], 'type' => 'shop');
				if ($this->config['sms_shop_place_order'] == 1 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = $user['uid'];
					$sms_data['mobile'] = $order_data['userphone'];
					$sms_data['sendto'] = 'user';
					$sms_data['content'] = '您' . date("H时i分") . '在【' . $return['store']['name'] . '】中下了一个订单，订单号：' . $orderid;
					Sms::sendSms($sms_data);
				}
				if ($this->config['sms_shop_place_order'] == 2 || $this->config['sms_shop_place_order'] == 3) {
					$sms_data['uid'] = 0;
					$sms_data['mobile'] = $return['store']['phone'];
					$sms_data['sendto'] = 'merchant';
					$sms_data['content'] = '顾客【' . $order_data['username'] . '】在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $orderid . '请您注意查看并处理!';
					Sms::sendSms($sms_data);
				}
				$pay_qrcode_url = $this->config['site_url'] . '/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id=' . (3700000000 + $order_id);
				exit(json_encode(array('error_code' => false, 'real_orderid' => $order_data['real_orderid'], 'order_id' => $order_id, 'price' => $order_data['price'], 'pay_qrcode_url' => $pay_qrcode_url, 'discount_msg' => trim($discountMsg, ','))));
			} else {
				exit(json_encode(array('error_code' => true, 'msg' => '订单保存失败')));
			}
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '不合法的提交')));
		}
	}
	
	private function check_cart($productCart, $uid)
	{
		$store_id = $this->store['store_id'];
	
		$store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
		if ($store['have_shop'] == 0 || $store['status'] != 1) {
			return array('error_code' => true, 'msg' => '商家已经关闭了该业务,不能下单了!');
		}
		if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
			return array('error_code' => true, 'msg' => '您查看的'.$this->config['shop_alias_name'].'没有通过资质审核！');
			exit;
		}
		$now_time = date('H:i:s');
		$is_open = 0;
//		if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
//			$is_open = 1;
//		} else {
//			if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
//				$is_open = 1;
//			}
//			if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
//				if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
//					$is_open = 1;
//				}
//			}
//			if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
//				if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
//					$is_open = 1;
//				}
//			}
//		}
        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171103
        switch ($date){
            case 1 :
                if ($store['open_1'] != '00:00:00' || $store['close_1'] != '00:00:00'){
                    if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
                        $is_open = 1;
                    }
                }
                if($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00'){
                    if($store['open_2'] < $now_time && $now_time < $store['close_2']) {
                        $is_open = 1;
                    }
                }
                if($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00'){
                    if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
                        $is_open = 1;
                    }
                }
                break;
            case 2 ://周二
                if ($store['open_4'] != '00:00:00' || $store['close_4'] != '00:00:00'){
                    if ($store['open_4'] < $now_time && $now_time < $store['close_4']) {
                        $is_open = 1;
                    }
                }
                if($store['open_5'] != '00:00:00' || $store['close_5'] != '00:00:00'){
                    if($store['open_5'] < $now_time && $now_time < $store['close_5']) {
                        $is_open = 1;
                    }
                }
                if($store['open_6'] != '00:00:00' || $store['close_6'] != '00:00:00'){
                    if ($store['open_6'] < $now_time && $now_time < $store['close_6']) {
                        $is_open = 1;
                    }
                }
                break;
            case 3 ://周三
                if ($store['open_7'] != '00:00:00' || $store['close_7'] != '00:00:00'){
                    if ($store['open_7'] < $now_time && $now_time < $store['close_7']) {
                        $is_open = 1;
                    }
                }
                if($store['open_8'] != '00:00:00' || $store['close_8'] != '00:00:00'){
                    if($store['open_8'] < $now_time && $now_time < $store['close_8']) {
                        $is_open = 1;
                    }
                }
                if($store['open_9'] != '00:00:00' || $store['close_9'] != '00:00:00'){
                    if ($store['open_9'] < $now_time && $now_time < $store['close_9']) {
                        $is_open = 1;
                    }
                }

                break;
            case 4 :
                if ($store['open_10'] != '00:00:00' || $store['close_10'] != '00:00:00'){
                    if ($store['open_10'] < $now_time && $now_time < $store['close_10']) {
                        $is_open = 1;
                    }
                }
                if($store['open_11'] != '00:00:00' || $store['close_11'] != '00:00:00'){
                    if($store['open_11'] < $now_time && $now_time < $store['close_11']) {
                        $is_open = 1;
                    }
                }
                if($store['open_12'] != '00:00:00' || $store['close_12'] != '00:00:00'){
                    if ($store['open_12'] < $now_time && $now_time < $store['close_12']) {
                        $is_open = 1;
                    }
                }

                break;
            case 5 :
                if ($store['open_13'] != '00:00:00' || $store['close_13'] != '00:00:00'){
                    if ($store['open_13'] < $now_time && $now_time < $store['close_13']) {
                        $is_open = 1;
                    }
                }
                if($store['open_14'] != '00:00:00' || $store['close_14'] != '00:00:00'){
                    if($store['open_14'] < $now_time && $now_time < $store['close_14']) {
                        $is_open = 1;
                    }
                }
                if($store['open_15'] != '00:00:00' || $store['close_15'] != '00:00:00'){
                    if ($store['open_15'] < $now_time && $now_time < $store['close_15']) {
                        $is_open = 1;
                    }
                }
                break;
            case 6 :
                if ($store['open_16'] != '00:00:00' || $store['close_16'] != '00:00:00'){
                    if ($store['open_16'] < $now_time && $now_time < $store['close_16']) {
                        $is_open = 1;
                    }
                }
                if($store['open_17'] != '00:00:00' || $store['close_17'] != '00:00:00'){
                    if($store['open_17'] < $now_time && $now_time < $store['close_17']) {
                        $is_open = 1;
                    }
                }
                if($store['open_18'] != '00:00:00' || $store['close_18'] != '00:00:00'){
                    if ($store['open_18'] < $now_time && $now_time < $store['close_18']) {
                        $is_open = 1;
                    }
                }
                break;
            case 0 :
                if ($store['open_19'] != '00:00:00' || $store['close_19'] != '00:00:00'){
                    if ($store['open_19'] < $now_time && $now_time < $store['close_19']) {
                        $is_open = 1;
                    }
                }
                if($store['open_20'] != '00:00:00' || $store['close_20'] != '00:00:00'){
                    if($store['open_20'] < $now_time && $now_time < $store['close_20']) {
                        $is_open = 1;
                    }
                }
                if($store['open_21'] != '00:00:00' || $store['close_21'] != '00:00:00'){
                    if ($store['open_21'] < $now_time && $now_time < $store['close_21']) {
                        $is_open = 1;
                    }
                }
                break;
            default :
                $is_open = 1;
        }
        //end  @wangchuanyuan




		if ($is_open == 0) {
			return array('error_code' => true, 'msg' => 'Store closed');
		}
	
		$store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store) || empty($store_shop)) return array('error_code' => true, 'msg' => '');
		
		
		$this->leveloff = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) : '';
		$store = array_merge($store, $store_shop);
		$mer_id = $store['mer_id'];
		
		if (empty($productCart)) return array('error_code' => true, 'msg' => '还没有购物');
	
		$goods = array();
		$price = 0;
		$total = 0;
		$extra_price= 0;
		$packing_charge = 0;//打包费
		//店铺优惠条件
		$sorts_discout = D('Shop_goods_sort')->get_sorts($store_id);
		$store_discount_money = 0;//店铺折扣后的总价
		
		foreach ($productCart as $row) {
			$num = $row['num'];
			$ids = explode('_', $row['goods_id']);
			$goods_id = array_shift($ids);
			$spec_str = $ids ? implode('_', $ids) : '';
			$t_return = D('Shop_goods')->check_stock($goods_id, $num, $spec_str, $store_shop['stock_type'], $store_id);
			if ($t_return['status'] == 0) {
				return array('error_code' => true, 'msg' => $t_return['msg']);
			} elseif ($t_return['status'] == 2) {
				return array('error_code' => true, 'msg' => $t_return['msg']);
			}
			$total += $num;
			$price += $t_return['price'] * $num;
			$extra_price += $row['productExtraPrice'] * $num;
			$packing_charge += $t_return['packing_charge'] * $num;

			$t_discount = isset($sorts_discout[$t_return['sort_id']]) && $sorts_discout[$t_return['sort_id']] ? $sorts_discout[$t_return['sort_id']] : 100;
			$store_discount_money += $num * round($t_return['price'] * $t_discount / 100, 2);
			
			$str = str_replace(array($t_return['name'], '(', ')'), '', $row['name']);
// 			$str = '';
// 			$str_s && $str = implode(',', $str_s);
// 			$str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
			$goods[] = array('name' => $t_return['name'], 'is_seckill_price' => $t_return['is_seckill_price'], 'num' => $num, 'goods_id' => $goods_id, 'price' => floatval($t_return['price']), 'cost_price' => floatval($t_return['cost_price']), 'number' => $t_return['number'], 'image' => $t_return['image'], 'packing_charge' => $t_return['packing_charge'], 'unit' => $t_return['unit'], 'str' => $str, 'spec_id' => $spec_str,'extra_price'=> 0);
		}
	
		$minus_price = 0;
		//会员等级优惠  外卖费不参加优惠
		$vip_discount_money = round($store_discount_money, 2);
		$level_off = false;
		if (!empty($this->user_level) && !empty($this->leveloff) && !empty($this->user_session) && isset($this->user_session['level'])) {
			if (isset($this->leveloff[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])) {
				$level_off = $this->leveloff[$this->user_session['level']];
				if ($sorts_discout['discount_type'] == 0) {
					if ($level_off['type'] == 1) {
						$vip_discount_money = $store_discount_money *($level_off['vv'] / 100);
						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
						$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
					} elseif($level_off['type'] == 2) {
						$vip_discount_money = $store_discount_money - $level_off['vv'];
						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
						$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
					}
	
				} else {
					if ($level_off['type'] == 1) {
						$vip_discount_money = $total_money *($level_off['vv'] / 100);
						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
						$level_off['offstr'] = '按此次总价' . $level_off['vv'] . '%来结算';
					} elseif($level_off['type'] == 2) {
						$vip_discount_money = $total_money - $level_off['vv'];
						$vip_discount_money = $vip_discount_money > 0 ? $vip_discount_money : 0;
						$level_off['offstr'] = '此次总价立减' . $level_off['vv'] . '元';
					}
					$vip_discount_money = $vip_discount_money > $store_discount_money ? $store_discount_money : $vip_discount_money;
				}
			}
		}
	
	
	
		$vip_discount_money = round($vip_discount_money, 2);
		$discounts = D('Shop_discount')->get_discount_byids(array($store_id));
		$discount_list = null;
	
		//优惠
		$sys_first_reduce = 0;//平台首单优惠
		$sto_first_reduce = 0;//店铺首单优惠
		$sys_full_reduce = 0;//平台满减
		$sto_full_reduce = 0;//店铺满减
		$shop_order_obj = D("Shop_order");
	
		$sys_count = $shop_order_obj->where(array('uid' => $uid))->count();
		if ($uid && empty($sys_count)) {
			if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money)) {
				$discount_list[] = $d_tmp;
				$sys_first_reduce = $d_tmp['reduce_money'];
			}
		}
	
	
		if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money)) {
			$discount_list[] = $d_tmp;
			$sys_full_reduce = $d_tmp['reduce_money'];
		}
	
		$sto_count = $shop_order_obj->where(array('uid' => $uid, 'store_id' => $store_id))->count();
		$sto_first_reduce = 0;
		if ($uid && empty($sto_count)) {
			if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money, $store_id)) {
				$discount_list[] = $d_tmp;
				$sto_first_reduce = $d_tmp['reduce_money'];
			}
		}
		$sto_full_reduce = 0;
		if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money, $store_id)) {
			$discount_list[] = $d_tmp;
			$sto_full_reduce = $d_tmp['reduce_money'];
		}
	
		//起步运费
		$delivery_fee = 0;
		//超出距离部分的单价
		$per_km_price = 0;
		//起步距离
		$basic_distance = 0;
		//减免配送费的金额
		$delivery_fee_reduce = 0;
	
		//起步运费
		$delivery_fee2 = 0;
		//超出距离部分的单价
		$per_km_price2 = 0;
		//起步距离
		$basic_distance2 = 0;
		//减免配送费的金额
// 		$delivery_fee_reduce2 = 0;
	
// 		if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {//平台配送|平台或自提
// 			if ($store_shop['s_is_open_own']) {//开启了店铺的独立配送费的设置
// 				//配送时段一的配置
// 				if ($store_shop['s_free_type'] == 0) {//免配送费
						
// 				} elseif ($store_shop['s_free_type'] == 1) {//不免
// 					$delivery_fee = $store_shop['s_delivery_fee'];
// 					$per_km_price = $store_shop['s_per_km_price'];
// 					$basic_distance = $store_shop['s_basic_distance'];
// 				} elseif ($store_shop['s_free_type'] == 2) {//满免
// 					if ($price < $store_shop['s_full_money']) {
// 						$delivery_fee = $store_shop['s_delivery_fee'];
// 						$per_km_price = $store_shop['s_per_km_price'];
// 						$basic_distance = $store_shop['s_basic_distance'];
// 					}
// 				}
// 				//配送时段二的配送
// 				if ($store_shop['s_free_type2'] == 0) {//免配送费
						
// 				} elseif ($store_shop['s_free_type2'] == 1) {//不免
// 					$delivery_fee2 = $store_shop['s_delivery_fee2'];
// 					$per_km_price2 = $store_shop['s_per_km_price2'];
// 					$basic_distance2 = $store_shop['s_basic_distance2'];
// 				} elseif ($store_shop['s_free_type2'] == 2) {//满免
// 					if ($price < $store_shop['s_full_money2']) {
// 						$delivery_fee2 = $store_shop['s_delivery_fee2'];
// 						$per_km_price2 = $store_shop['s_per_km_price2'];
// 						$basic_distance2 = $store_shop['s_basic_distance2'];
// 					}
// 				}
// 			} else {
// 				$delivery_fee = $this->config['delivery_fee'];
// 				$per_km_price = $this->config['per_km_price'];
// 				$basic_distance = $this->config['basic_distance'];
	
// 				$delivery_fee2 = $this->config['delivery_fee2'];
// 				$per_km_price2 = $this->config['per_km_price2'];
// 				$basic_distance2 = $this->config['basic_distance2'];
// 			}
// 			//使用平台的优惠（配送费的减免）
// 			if ($d_tmp = $this->get_reduce($discounts, 2, $price)) {
// 				$discount_list[] = $d_tmp;
// 				$delivery_fee_reduce = $d_tmp['reduce_money'];
// 			}
// 		} else {//商家配送|商家或自提|快递配送
// 			if ($store_shop['reach_delivery_fee_type'] == 0) {
	
// 			} elseif ($store_shop['reach_delivery_fee_type'] == 1) {
// 				$delivery_fee = $store_shop['delivery_fee'];
// 				$per_km_price = $store_shop['per_km_price'];
// 				$basic_distance = $store_shop['basic_distance'];
	
// 				$delivery_fee2 = $store_shop['delivery_fee2'];
// 				$per_km_price2 = $store_shop['per_km_price2'];
// 				$basic_distance2 = $store_shop['basic_distance2'];
// 			} elseif ($store_shop['reach_delivery_fee_type'] == 2)  {
// 				if ($price < $store_shop['no_delivery_fee_value']) {
// 					$delivery_fee = $store_shop['delivery_fee'];
// 					$per_km_price = $store_shop['per_km_price'];
// 					$basic_distance = $store_shop['basic_distance'];
						
// 					$delivery_fee2 = $store_shop['delivery_fee2'];
// 					$per_km_price2 = $store_shop['per_km_price2'];
// 					$basic_distance2 = $store_shop['basic_distance2'];
// 				}
// 			}
// 			if ($store_shop['reach_delivery_fee_type2'] == 0) {
	
// 			} elseif ($store_shop['reach_delivery_fee_type2'] == 1) {
// 				$delivery_fee2 = $store_shop['delivery_fee2'];
// 				$per_km_price2 = $store_shop['per_km_price2'];
// 				$basic_distance2 = $store_shop['basic_distance2'];
// 			} elseif ($store_shop['reach_delivery_fee_type2'] == 2)  {
// 				if ($price < $store_shop['no_delivery_fee_value2']) {
// 					$delivery_fee2 = $store_shop['delivery_fee2'];
// 					$per_km_price2 = $store_shop['per_km_price2'];
// 					$basic_distance2 = $store_shop['basic_distance2'];
// 				}
// 			}
// 		}
	
		if (empty($goods)) {
			return array('error_code' => true, 'msg' => '购物车是空的');
		} else {
			$data = array('error_code' => false);
			$data['total'] = $total;
			$data['price'] = $price;//商品实际总价
			$data['extra_price'] = $extra_price;//商品实际总价
			$data['discount_price'] = $vip_discount_money;//折扣后的总价
			$data['goods'] = $goods;
			$data['store_id'] = $store_id;
			$data['mer_id'] = $mer_id;
			$data['store'] = $store;
			$data['discount_list'] = $discount_list;
				
			$data['delivery_type'] = $store_shop['deliver_type'];
				
			$data['sys_first_reduce'] = $sys_first_reduce;//平台新单优惠的金额
			$data['sys_full_reduce'] = $sys_full_reduce;//平台满减优惠的金额
			$data['sto_first_reduce'] = $sto_first_reduce;//店铺新单优惠的金额
			$data['sto_full_reduce'] = $sto_full_reduce;//店铺满减优惠的金额
				
			$data['store_discount_money'] = $store_discount_money;//店铺折扣后的总价
			$data['vip_discount_money'] = $vip_discount_money;//VIP折扣后的总价
			$data['packing_charge'] = $packing_charge;//总的打包费
				
			$data['delivery_fee'] = $delivery_fee;//起步配送费
			$data['basic_distance'] = $basic_distance;//起步距离
			$data['per_km_price'] = $per_km_price;//超出起步距离部分的距离每公里的单价
			$data['delivery_fee_reduce'] = $delivery_fee_reduce;//配送费减免的金额
				
			$data['delivery_fee2'] = $delivery_fee2;//起步配送费
			$data['basic_distance2'] = $basic_distance2;//起步距离
			$data['per_km_price2'] = $per_km_price2;//超出起步距离部分的距离每公里的单价
			return $data;
		}
	}
	private function get_reduce($discounts, $type, $price, $store_id = 0)
	{
		$reduce_money = 0;
		$return = null;
		if (isset($discounts[$store_id])) {
			foreach ($discounts[$store_id] as $row) {
				if ($row['type'] == $type) {
					if ($price >= $row['full_money']) {
						if ($reduce_money < $row['reduce_money']) {
							$reduce_money = $row['reduce_money'];
							$return = $row;
						}
					}
				}
			}
		}
		return $return;
	}
	
	public function shop_arrival_check()
	{
		$now_order = M('Shop_order')->where(array('order_id'=>$_POST['order_id']))->find();
		if ($now_order['paid']) {
			$this->success('支付成功！');
		} else {
			$this->error('还未支付');
		}
	}
	
	public function arrival_pay()
	{
		$table = isset($_POST['table']) ? htmlspecialchars($_POST['table']) : 'shop';
		$change_price_reason = isset($_POST['change_reason']) ? htmlspecialchars($_POST['change_reason']) : 'shop';
// 		$discount = isset($_POST['discount']) ? intval($_POST['discount']) : 10;
		$coupon = isset($_POST['coupon']) ? intval($_POST['coupon']) : 0;
		$uid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;
		$card_id = isset($_POST['card_id']) ? intval($_POST['card_id']) : 0;
		$card_money = isset($_POST['card_money']) ? floatval($_POST['card_money']) : 0;
		$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;
		$table = strtolower($table);
		$table_name = ucfirst($table) . '_order'; 
		$order_id  = intval($_POST['order_id']);
		$now_order = M($table_name)->where(array('order_id' => $order_id))->find();
		
		if (empty($now_order)) $this->error('订单信息错误');
		
		//检查会员卡的信息已经余额  TODO
		$discount = 10;
		$data['merchant_balance'] = 0;//商家余额
		$data['card_give_money'] = 0;//会员卡赠送余额
		$data['card_id'] = 0;//优惠券ID
		$data['card_price'] = 0;//优惠券的金额
		$coupon_price = 0;
		if ($card = D('Card_userlist')->field(true)->where(array('id' => $card_id, 'uid' => $uid, 'mer_id' => $this->store['mer_id']))->find()) {
			$card_source = M('Card_new')->where(array('mer_id' => $this->store['mer_id'], 'status' => 1))->find();
			if (empty($card_source)) $this->error('会员卡不可用');
			$discount = floatval($card_source['discount']);
			$user_card_total_moeny = $card['card_money'] + $card['card_money_give'];
			if ($user_card_total_moeny < $card_money) {
				$this->error('会员卡余额不足');
			}
			if ($card_money <= $card['card_money']) {
				$data['merchant_balance'] = $card_money;
				$data['card_give_money'] = 0;
			} else {
				$data['merchant_balance'] = $card['card_money'];
				$data['card_give_money'] = $card_money - $card['card_money'];
			}
			
			$discount_price = floatval(round($now_order['price'] * $discount * 0.1, 2));
			$data['price'] = $discount_price;
			if ($user_coupon = M('Card_new_coupon_hadpull')->field(true)->where(array('id' => $coupon, 'is_use' => 0))->find()) {
				if ($new_coupon = M('card_new_coupon')->field(true)->where(array('coupon_id' => $user_coupon['coupon_id'], 'use_with_card' => 1))->find()) {
					$now_time = time();
					if (($new_coupon['cate_name'] == 'all' || $new_coupon['cate_name'] == 'shop') && $new_coupon['end_time'] > $now_time && $discount_price >= $new_coupon['order_money']) {
						$data['card_id'] = $coupon;
						$coupon_price = $data['card_price'] = $new_coupon['discount'];
					}
				}
			}
			$discount = floatval($card_source['discount']);
		} else {
			$card_money = 0;
			$discount = 10;
		}
		if ($this->staff_session['is_change']) {
			$true_price = floatval(round(floatval(round($now_order['price'] * $discount * 0.1, 2)) - $coupon_price, 2));//订单会员卡折扣和优惠券后的钱  
			if ($true_price != $price) {
				$data['change_price'] = floatval(round($now_order['price'] * $discount * 0.1, 2));
				$data['price'] = $price;
				$data['change_price_reason'] = $change_price_reason;//修改价格的理由
			} else {
				$price = $true_price;
			}
			$now_order['price'] = floatval(round($price - $card_money, 2));
		} else {
			$now_order['price'] = floatval(round(floatval(round($now_order['price'] * $discount * 0.1, 2)) - $card_money - $coupon_price, 2));
		}
		$data['card_discount'] = $discount;
		$data['last_staff'] = $this->store_session['name'];
		
		if ($now_order['price'] > 0) {
			$offline_pay = isset($_POST['offline_pay']) ? intval($_POST['offline_pay']) : -1;
			if($offline_pay >= 0){
				$this->arrival_offline_pay($order_id, $now_order, $table_name, $data);
				die;
			}
			if($_POST['auth_type'] == 'alipay'){
				$this->arrival_alipay_pay($order_id, $now_order, $table_name, $data);
				die;
			}

			$now_merchant = D('Merchant')->get_info($now_order['mer_id']);
			$sub_mch_pay = false;
			$is_own = 0;
			if ($this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
				$this->config['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
				$sub_mch_pay = ture;
				$sub_mch_id = $now_merchant['sub_mch_id'];
				$is_own = 2 ;
			}
			
			import('ORG.Net.Http');
			$http = new Http();
			$session_key = $table . '_order_userpaying_'.$order_id;
			if($_SESSION[$session_key]){
				$param = array();
				$param['appid'] = $this->config['pay_weixin_appid'];
				$param['mch_id'] = $this->config['pay_weixin_mchid'];
				$param['nonce_str'] = $this->createNoncestr();
				$param['out_trade_no'] = $table . '_' . $now_order['order_id'];
				if($sub_mch_pay){
					$param['out_trade_no'] = 'store_'.$now_order['order_id'].'_1';
					$param['sub_mch_id'] = $sub_mch_id;
				}
				$param['sign'] = $this->getWxSign($param);
				$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/orderquery', $this->arrayToXml($param));
			} else {
				$param = array();
				$param['appid'] = $this->config['pay_weixin_appid'];
				$param['mch_id'] = $this->config['pay_weixin_mchid'];
				$param['nonce_str'] = $this->createNoncestr();
				$param['body'] = $now_order['real_orderid'];
				$param['out_trade_no'] = $table . '_' . $now_order['order_id'];
				$param['total_fee'] = floatval($now_order['price']*100);
				$param['spbill_create_ip'] = get_client_ip();
				$param['auth_code'] = $_POST['auth_code'];
				if($sub_mch_pay){
					$param['out_trade_no'] = 'store_'.$now_order['order_id'].'_1';
					$param['sub_mch_id'] = $sub_mch_id;
				}
				$param['sign'] = $this->getWxSign($param);
		
				$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/micropay', $this->arrayToXml($param));
			}
			if ($return['return_code'] == 'FAIL') {
				$this->error('支付失败！微信返回：'. $return['return_msg']);
			}
			if ($return['result_code'] == 'FAIL') {
				if ($return['err_code'] == 'USERPAYING') {
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
						$uid = $now_user['uid'];
					}
				}
			} else {
				$uid = $now_user['uid'];
			}
			
			$data['third_id'] = $return['transaction_id'];
			$data['payment_money'] = $return['total_fee']/100;
			$data['pay_type'] = 'weixin';
		}
		
		
		$data['uid'] = $uid;
		$data['paid'] = 1;
		$data['status'] = 2;
		$data['order_status'] = 5;
		$data['pay_time'] = time();
		$data['use_time'] = time();
		
		if(M($table_name)->where(array('order_id' => $order_id))->data($data)->save()){
			$now_order = M($table_name)->where(array('order_id'=>$order_id))->find();
			$this->shop_notice($now_order, true);
			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}
	}
	public function arrival_offline_pay($order_id, $now_order, $table_name, $data = array())
	{
		$data['paid'] = 1;
		$data['status'] = 2;
		$data['order_status'] = 5;
		$data['pay_time'] = $_SERVER['REQUEST_TIME'];
		$data['use_time'] = time();
		$data['pay_type'] = 'offline';
		$data['offline_pay'] = $_POST['offline_pay'];
		$data['third_id'] = $order_id;
		if(M($table_name)->where(array('order_id' => $order_id))->data($data)->save()){
			$now_order = M($table_name)->where(array('order_id' => $order_id))->find();
			$this->shop_notice($now_order, true);
			$this->success('支付成功！');
		}else{
			$this->error('支付失败！请联系管理员处理。');
		}
	}
	
	
	public function arrival_alipay_pay($order_id, $now_order, $table_name, $data = array())
	{
		if (empty($this->config['arrival_alipay_open'])) {
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
				'subject' => $now_order['real_orderid'],
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
		if (empty($sign)) {
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
		if (!empty($returnArr['alipay_trade_pay_response']) && "10000" == $returnArr['alipay_trade_pay_response']['code']) {
			$data['paid'] = 1;
			$data['status'] = 2;
			$data['order_status'] = 5;
			$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
			$data['use_time'] = time();
			$data['pay_type'] = 'alipay';
			$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
			$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
			if (M($table_name)->where(array('order_id'=>$order_id))->data($data)->save()) {
				$now_order = M($table_name)->where(array('order_id' => $order_id))->find();
				$this->shop_notice($now_order, true);
				$this->success('支付成功！');
			} else {
				$this->error('支付失败！请联系管理员处理。');
			}
		} elseif (!empty($returnArr['alipay_trade_pay_response']) && "10003" == $returnArr['alipay_trade_pay_response']['code']) {	//需要用户处理，下次查询订单
	
		} else {
			if ($returnArr['alipay_trade_pay_response']['sub_code'] == 'ACQ.TRADE_HAS_SUCCESS' && $now_order['paid'] != 1) {
				$data['paid'] = 1;
				$data['status'] = 2;
				$data['order_status'] = 5;
				$data['pay_time'] = strtotime($returnArr['alipay_trade_pay_response']['gmt_payment']);
				$data['use_time'] = time();
				$data['pay_type'] = 'alipay';
				$data['third_id'] = $returnArr['alipay_trade_pay_response']['trade_no'];
				$data['payment_money'] = $returnArr['alipay_trade_pay_response']['receipt_amount'];
				if (M($table_name)->where(array('order_id' => $order_id))->data($data)->save()) {
					$now_order = M($table_name)->where(array('order_id' => $order_id))->find();
					$this->shop_notice($now_order, true);
					$this->success('支付成功！');
				} else {
					$this->error('支付失败！请联系管理员处理。');
				}
			} else {
				$this->error('支付失败！支付宝返回：'.$returnArr['alipay_trade_pay_response']['sub_msg']);
			}
		}
	}


	public function appoint_export()
	{
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '预约订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		//$where['a.appoint_id'] = intval($_GET['appoint_id']);
		$store_id = $this->store['store_id'];
		$where['a.store_id'] = $store_id;
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'order_id') {
				$where['a.order_id'] = $_GET['keyword'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
				unset($where['orderid']);
				$where['a.order_id'] = $tmp_result['order_id'];
			}elseif ($_GET['searchtype'] == 'name') {
				$where['u.username'] = htmlspecialchars($_GET['keyword']);
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['u.phone'] = htmlspecialchars($_GET['keyword']);
			}elseif ($_GET['searchtype'] == 'third_id') {
				$where['a.third_id'] =$_GET['keyword'];
			}
		}
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type){
			if($pay_type=='balance'){
				$where['_string'] = "(`a`.`balance_pay`<>0 OR `a`.`merchant_balance` <> 0 OR a.product_merchant_balance <> 0 OR a.product_balance_pay <> 0 )";
			}else{
				$where['a.pay_type'] = $pay_type;
			}
		}
		$database_appoint = D('Appoint');
		$database_order = D('Appoint_order');
		$now_appoint = $database_appoint->field(true)->where(array('appoint_id'=>$_GET['appoint_id']))->find();
		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .= (empty($where['_string'])?'':' AND ')." (a.order_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}
		$order_count = $database_order->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
				->where($where)->count();

		$length = ceil($order_count / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '订单编号');
			$objActSheet->setCellValue('B1', '定金');
			$objActSheet->setCellValue('C1', '总价');
			$objActSheet->setCellValue('D1', '类型');
			$objActSheet->setCellValue('E1', '用户昵称');
			$objActSheet->setCellValue('F1', '手机号码');
			$objActSheet->setCellValue('G1', '订单状态');
			$objActSheet->setCellValue('H1', '服务状态');
			$objActSheet->setCellValue('I1', '平台余额支付');
			$objActSheet->setCellValue('J1', '商家会员卡支付');
			$objActSheet->setCellValue('K1', '在线支付金额');
			$objActSheet->setCellValue('L1', '下单时间');
			$objActSheet->setCellValue('M1', '支付时间');
			$objActSheet->setCellValue('N1', '支付方式');
			$order_list = $database_order->field('a.*,ap.appoint_name,a.appoint_type,a.mer_id,u.uid,u.nickname,a.order_time,a.pay_time,u.phone,s.name as store_name,s.adress as store_adress')
					->join('AS a left join '.C('DB_PREFIX').'appoint ap on a.appoint_id =ap.appoint_id left join '.C('DB_PREFIX').'user u ON a.uid = u.uid left join '.C('DB_PREFIX').'merchant_store s ON s.store_id = a.store_id ')
					->where($where)->limit(($i*1000).',1000')->order('`order_id` DESC')->select();
			$result_list = $order_list;

			if (!empty($result_list)) {
				$index = 2;
				foreach ($result_list as $value) {
					$objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
					if($value['product_id']>0){
						$objActSheet->setCellValueExplicit('B' . $index,floatval($value['product_payment_price']));
					}else{
						$objActSheet->setCellValueExplicit('B' . $index, floatval($value['payment_money']));
					}
					if($value['product_price']>0){
						$objActSheet->setCellValueExplicit('C' . $index,floatval($value['product_price']));
					}else{
						$objActSheet->setCellValueExplicit('C' . $index, floatval($value['appoint_price']));
					}
					if($value['type']==1){
						$objActSheet->setCellValueExplicit('D' . $index, '自营');
					}else{
						$objActSheet->setCellValueExplicit('D' . $index, '商家');
					}
					$objActSheet->setCellValueExplicit('E' . $index, $value['nickname'] . ' ');
					$objActSheet->setCellValueExplicit('F' . $index, $value['phone'] . ' ');
					if($value['paid']==0){
						$objActSheet->setCellValueExplicit('G' . $index, '未支付');
					}elseif($value['paid']==1){
						$objActSheet->setCellValueExplicit('G' . $index, '已支付');
					}elseif($value['paid']==2){
						$objActSheet->setCellValueExplicit('G' . $index, '已退款');
					}
					if($value['service_status']==1){
						$objActSheet->setCellValueExplicit('H' . $index, '未服务');
					}elseif($value['service_status']==2){
						$objActSheet->setCellValueExplicit('H' . $index, '已服务');
					}elseif($value['service_status']==3){
						$objActSheet->setCellValueExplicit('H' . $index, '已评价');
					}
					$objActSheet->setCellValueExplicit('I' . $index, floatval($value['balance_pay']));
					$objActSheet->setCellValueExplicit('J' . $index, floatval($value['merchant_balance']));
					$objActSheet->setCellValueExplicit('K' . $index, floatval($value['pay_money']));
					$objActSheet->setCellValueExplicit('L' . $index, $value['order_time'] ? date('Y-m-d H:i:s', $value['order_time']) : '');
					$objActSheet->setCellValueExplicit('M' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
					$objActSheet->setCellValueExplicit('N' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));


					$index++;
				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}


	public function group_export()
	{
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '团购订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);

		// 设置当前的sheet
		$store_id = $this->store['store_id'];

		$condition_where = " WHERE `o`.`store_id`='$store_id'";
		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$condition_where .= "AND `o`.`real_orderid`='" . htmlspecialchars($_GET['keyword'])."'";
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$tmp_result = M('Tmp_orderid')->where(array('orderid'=>$_GET['keyword']))->find();
				$condition_where .= "AND `o`.`order_id`='" . htmlspecialchars($tmp_result['order_id'])."'";
			} elseif ($_GET['searchtype'] == 'name') {
				$condition_where .= "AND `u`.`nickname` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			} elseif ($_GET['searchtype'] == 'phone') {
				$condition_where .= "AND `u`.`phone`='" . htmlspecialchars($_GET['keyword']) . "'";
			} elseif ($_GET['searchtype'] == 's_name') {
				$condition_where .= "AND `g`.`s_name` like '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .= " AND `o`.`third_id` =".$_GET['keyword'];
			}
		}
		if ($this->system_session['area_id']) {
			$area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
			$condition_where .= " AND `m`.`{$area_index}`={$this->system_session['area_id']}";
		}

		$status = isset($_GET['status']) ? intval($_GET['status']) : -1;
		$type = isset($_GET['type']) && $_GET['type'] ? $_GET['type'] : '';
		$sort = isset($_GET['sort']) && $_GET['sort'] ? $_GET['sort'] : '';
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if ($sort != 'DESC' && $sort != 'ASC') $sort = '';
		if ($type != 'price' && $type != 'pay_time') $type = '';
		$order_sort = '';
		if ($type && $sort) {
			$order_sort .= 'o.' . $type . ' ' . $sort . ',';
			$order_sort .= 'o.order_id DESC';
		} else {
			$order_sort .= 'o.order_id DESC';
		}

		if ($status != -1) {
			$condition_where .= " AND `o`.`status`={$status}";
		}
		if($pay_type){
			if($pay_type=='balance'){
				$condition_where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 )";
			}else{
				$condition_where .= " AND `o`.`pay_type`='{$pay_type}'";
			}
		}

		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$condition_where .= " AND (o.add_time BETWEEN ".$period[0].' AND '.$period[1].")";
			//$condition_where['_string']=$time_condition;
		}


		$sql = "SELECT count(order_id) as count FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC ";
		$count = D()->query($sql);

		$length = ceil($count[0]['count'] / 1000);
		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();

			$objActSheet->setCellValue('A1', '订单编号');
			$objActSheet->setCellValue('B1', '商家名称');
			$objActSheet->setCellValue('C1', '客户姓名');
			$objActSheet->setCellValue('D1', '客户电话');
			$objActSheet->setCellValue('E1', '订单总价');
			$objActSheet->setCellValue('F1', '平台余额');
			$objActSheet->setCellValue('G1', '商家余额');
			$objActSheet->setCellValue('H1', '在线支付金额');
			$objActSheet->setCellValue('I1', '平台'.$this->config['score_name'].'');
			$objActSheet->setCellValue('J1', '平台优惠券');
			$objActSheet->setCellValue('K1', '商家优惠券');
			$objActSheet->setCellValue('L1', '商家折扣');
			$objActSheet->setCellValue('M1', '支付时间');
			$objActSheet->setCellValue('N1', '订单状态');
			$objActSheet->setCellValue('O1', '支付情况');
			$sql = "SELECT o.*, m.name AS merchant_name,u.nickname as username FROM " . C('DB_PREFIX') . "group_order AS o  LEFT JOIN " . C('DB_PREFIX') . "group g ON g.group_id = o.group_id  LEFT JOIN " . C('DB_PREFIX') . "merchant AS m ON `o`.`mer_id`=`m`.`mer_id` LEFT JOIN " . C('DB_PREFIX') . "user u ON u.uid = o.uid ".$condition_where." ORDER BY o.order_id DESC LIMIT " . $i * 1000 . ",1000";
			$result_list = D()->query($sql);

			if (!empty($result_list)) {
				$index = 2;
				foreach ($result_list as $value) {
					$objActSheet->setCellValueExplicit('A' . $index, $value['real_orderid']);
					$objActSheet->setCellValueExplicit('B' . $index, $value['merchant_name']);
					$objActSheet->setCellValueExplicit('C' . $index, $value['username'] . ' ');
					$objActSheet->setCellValueExplicit('D' . $index, $value['phone'] . ' ');
					$objActSheet->setCellValueExplicit('E' . $index, floatval($value['total_money']));
					$objActSheet->setCellValueExplicit('F' . $index, floatval($value['balance_pay']));
					$objActSheet->setCellValueExplicit('G' . $index, floatval($value['merchant_balance']));
					$objActSheet->setCellValueExplicit('H' . $index, floatval($value['payment_money']));
					$objActSheet->setCellValueExplicit('I' . $index, floatval($value['score_reducte']));
					$objActSheet->setCellValueExplicit('J' . $index, floatval($value['coupon_price']));
					$objActSheet->setCellValueExplicit('K' . $index, floatval($value['card_price']));
					$objActSheet->setCellValueExplicit('L' . $index, floatval($value['card_discount'])?floatval($value['card_discount']). '折':'');
					$objActSheet->setCellValueExplicit('M' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
					$objActSheet->setCellValueExplicit('N' . $index, $this->get_order_status($value));
					$objActSheet->setCellValueExplicit('O' . $index, D('Pay')->get_pay_name($value['pay_type'], $value['is_mobile_pay'], $value['paid']));


					$index++;
				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	public function get_order_status($order){
		$status = '';
		if($order['paid']){
			if($order['pay_type']=='offline' && empty($order['third_id'])&& $order['status'] == 0){
				$status='线下支付，未付款';
			}elseif($order['status']==0){
				$status='已付款';
				if($order['tuan_type'] != 2){
					$status.='已付款';
				}else{
					if($order['is_pick_in_store']){
						$status.='未取货';
					}else{
						$status.='未发货';
					}
				}
			}elseif($order['status']==1){
				if($order['tuan_type'] != 2){
					$status='已消费';
				}else{
					if($order['is_pick_in_store']){
						$status='已取货';
					}else{
						$status='已发货';
					}
				}
				$status.='待评价';
			}elseif($order['status']==2){
				$status='已完成';
			}elseif($order['status']==3){
				$status='已退款';
			}elseif($order['status']==4){
				$status='已取消';
			}
		}else{
			if($status==4){
				$status='已取消';
			}else{
				$status='未付款';
			}
		}

		return $status;
	}


	public function foodshop_export(){
		$store_id = $this->store['store_id'];


		$condition_where = 'Where o.mer_id = '.$this->store['mer_id'].' AND o.store_id = '.$this->store['store_id'];

		if(!empty($_GET['keyword'])){
			if ($_GET['searchtype'] == 'real_orderid') {
				$where['real_orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.real_orderid ='.$where['real_orderid'];
			} elseif ($_GET['searchtype'] == 'orderid') {
				$where['orderid'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND p.orderid ='.$where['orderid'];
			} elseif ($_GET['searchtype'] == 'name') {
				$where['name'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.name ='.$where['name'];
			} elseif ($_GET['searchtype'] == 'phone') {
				$where['phone'] = htmlspecialchars($_GET['keyword']);
				$condition_where .=' AND o.phone ='.$where['phone'];
			} elseif ($_GET['searchtype'] == 'third_id') {
				$condition_where .=' AND p.third_id ='.$_GET['keyword'];
			}
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
		$pay_type = isset($_GET['pay_type']) && $_GET['pay_type'] ? $_GET['pay_type'] : '';
		if($pay_type&&$pay_type!='balance'){
			$condition_where .=' AND p.pay_type ="'.$pay_type.'"';
		}else if($pay_type=='balance'){
			$condition_where .= " AND  (`p`.`system_balance`<>0 OR `p`.`merchant_balance_pay` <> 0 )";
		}


		if(!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
			if ($_GET['begin_time']>$_GET['end_time']) {
				$this->error_tips("结束时间应大于开始时间");
			}
			$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
			$where['_string'] .=( $where['_string']?' AND ':''). " (create_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition_where .=" AND  (o.create_time BETWEEN ".$period[0].' AND '.$period[1].")";
		}

		if ($status != -1) {
			$where['status'] = $status;
			$condition_where .= ' AND o.status ='.$status;
		}
// 		echo '<pre/>';
// 		print_r($where);die;
		//$count = D("Foodshop_order")->where($where)->count();
		$sql_cont = 'SELECT count(o.order_id) as count from pigcms_foodshop_order o LEFT JOIN (select a.* from pigcms_plat_order a,(select max(order_id) as order_id,business_id from pigcms_plat_order group by business_id) b where a.order_id=b.order_id and a.business_id=b.business_id) p on o.order_id  = p.business_id '.$condition_where;
		$count  = M()->query($sql_cont);

		$count = $count[0]['count'];
		set_time_limit(0);
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$title = '订单信息';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		$length = ceil($count / 1000);


		for ($i = 0; $i < $length; $i++) {
			$i && $objExcel->createSheet();
			$objExcel->setActiveSheetIndex($i);

			$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个订单信息');
			$objActSheet = $objExcel->getActiveSheet();
			$objExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
			$objExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);

			$objActSheet->setCellValue('A1', '订单流水号');
			$objActSheet->setCellValue('B1', '商家名称');
			$objActSheet->setCellValue('C1', '店铺名称');
			$objActSheet->setCellValue('D1', '客户名称');
			$objActSheet->setCellValue('E1', '客户电话');
			$objActSheet->setCellValue('F1', '预定金');
			$objActSheet->setCellValue('G1', '预定时间');
			$objActSheet->setCellValue('H1', '桌台类型');
			$objActSheet->setCellValue('I1', '桌台名称');
			$objActSheet->setCellValue('J1', '订单状态');
			$objActSheet->setCellValue('K1', '订单总价');
			$objActSheet->setCellValue('L1', '余额支付');
			$objActSheet->setCellValue('M1', '平台在线支付');
			$objActSheet->setCellValue('N1', '商家余额支付');
			$objActSheet->setCellValue('O1', $this->config['score_name']);
			$objActSheet->setCellValue('P1', '支付时间');
			$objActSheet->setCellValue('Q1', '支付方式');
			$objActSheet->setCellValue('R1', '支付类型');


			//$objActSheet->setCellValue('R1', '支付情况');

			$sql = 'SELECT o.*,p.pay_type as pay_method,p.system_balance,p.merchant_balance_pay,p.pay_money,p.system_score_money,p.pay_time ,p.paid from pigcms_foodshop_order o LEFT JOIN  pigcms_plat_order  p on o.order_id  = p.business_id '.$condition_where.' ORDER BY o.order_id DESC ' .'limit '.($i*1000).',1000';

			$list = M('')->query($sql);
			//appdump(M());
			$mer_ids = $store_ids = array();
			foreach ($list as $l) {
				$mer_ids[] = $l['mer_id'];
				$store_ids[] = $l['store_id'];
				$table_types[] = $l['table_type'];
				$tids[] = $l['table_id'];

			}


			$type_list = array();
			if ($table_types) {
				$temp_type_list = M('Foodshop_table_type')->field(true)->where(array('id' => array('in', $table_types)))->select();
				foreach ($temp_type_list as $tmp) {
					$type_list[$tmp['id']] = $tmp;
				}
			}
			$table_list = array();
			if ($tids) {
				$temp_table_list = M('Foodshop_table')->field(true)->where(array('id' => array('in', $tids)))->select();
				foreach ($temp_table_list as $temp) {
					$table_list[$temp['id']] = $temp;
				}
			}


			$store_temp = $mer_temp = array();
			if ($mer_ids) {
				$merchants = D("Merchant")->where(array('mer_id' => array('in', $mer_ids)))->select();
				foreach ($merchants as $m) {
					$mer_temp[$m['mer_id']] = $m;
				}
			}
			if ($store_ids) {
				$merchant_stores = D("Merchant_store")->where(array('store_id' => array('in', $store_ids)))->select();
				foreach ($merchant_stores as $ms) {
					$store_temp[$ms['store_id']] = $ms;
				}
			}
			foreach ($list as &$li) {
				$li['merchant_name'] = isset($mer_temp[$li['mer_id']]['name']) ? $mer_temp[$li['mer_id']]['name'] : '';
				$li['store_name'] = isset($store_temp[$li['store_id']]['name']) ? $store_temp[$li['store_id']]['name'] : '';

				$li['table_type_name'] = isset($type_list[$li['table_type']]) ? $type_list[$li['table_type']]['name'] . '(' . $type_list[$li['table_type']]['min_people'] . '-' . $type_list[$li['table_type']]['max_people'] . '人)' : '';
				$li['table_name'] = isset($table_list[$li['table_id']]) ? $table_list[$li['table_id']]['name'] : '';
				$li['show_status'] = D('Foodshop_order')->status_list[$li['status']];
			}


			//dump($result_list);die;
			$tmp_id = 0;
			if (!empty($list)) {
				$index = 1;
				foreach ($list as $value) {
					if($tmp_id == $value['order_id']){
						$objActSheet->setCellValueExplicit('A' . $index, '');
						$objActSheet->setCellValueExplicit('B' . $index, '');
						$objActSheet->setCellValueExplicit('C' . $index, '');
						$objActSheet->setCellValueExplicit('D' . $index, '');
						$objActSheet->setCellValueExplicit('E' . $index,'');
						$objActSheet->setCellValueExplicit('F' . $index, '');
						$objActSheet->setCellValueExplicit('G' . $index,'');
						$objActSheet->setCellValueExplicit('H' . $index,'');
						$objActSheet->setCellValueExplicit('I' . $index, '');
						$objActSheet->setCellValueExplicit('J' . $index, '');
						$objActSheet->setCellValueExplicit('K' . $index, floatval($value['total_money']));
						$objActSheet->setCellValueExplicit('L' . $index, floatval($value['system_balance']));
						$objActSheet->setCellValueExplicit('M' . $index, floatval($value['pay_money']));
						$objActSheet->setCellValueExplicit('N' . $index, floatval($value['merchant_balance_pay']));
						$objActSheet->setCellValueExplicit('O' . $index, floatval($value['system_score_money']));
						$objActSheet->setCellValueExplicit('P' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('Q' . $index,D('Pay')->get_pay_name($value['pay_method'], $value['is_mobile_pay'], $value['paid']));
						$objActSheet->setCellValueExplicit('R' . $index,'支付余额');
						$index++;
					}else{
						$index++;
						$objActSheet->setCellValueExplicit('A' . $index, $value['order_id']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['merchant_name']);
						$objActSheet->setCellValueExplicit('C' . $index, $value['store_name']);
						$objActSheet->setCellValueExplicit('D' . $index, $value['name']);
						$objActSheet->setCellValueExplicit('E' . $index,$value['phone']);
						$objActSheet->setCellValueExplicit('F' . $index, $value['book_pricRe']);
						$objActSheet->setCellValueExplicit('G' . $index, $value['book_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('H' . $index,$value['table_type_name']);
						$objActSheet->setCellValueExplicit('I' . $index,$value['table_name']);
						$objActSheet->setCellValueExplicit('J' . $index, $value['show_status']);
						$objActSheet->setCellValueExplicit('K' . $index, floatval($value['total_money']));
						$objActSheet->setCellValueExplicit('L' . $index, floatval($value['system_balance']));
						$objActSheet->setCellValueExplicit('M' . $index, floatval($value['pay_money']));
						$objActSheet->setCellValueExplicit('N' . $index, floatval($value['merchant_balance_pay']));
						$objActSheet->setCellValueExplicit('O' . $index, floatval($value['system_score_money']));
						$objActSheet->setCellValueExplicit('P' . $index, $value['pay_time'] ? date('Y-m-d H:i:s', $value['pay_time']) : '');
						$objActSheet->setCellValueExplicit('Q' . $index,D('Pay')->get_pay_name($value['pay_method'], $value['is_mobile_pay'], $value['paid']));
						$objActSheet->setCellValueExplicit('R' . $index,'支付定金');
						$index++;
					}
					$tmp_id = $value['order_id'];

				}
			}
			sleep(2);
		}
		//输出
		$objWriter = new PHPExcel_Writer_Excel5($objExcel);
		ob_end_clean();
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
		header("Content-Type:application/force-download");
		header("Content-Type:application/vnd.ms-execl");
		header("Content-Type:application/octet-stream");
		header("Content-Type:application/download");
		header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
		header("Content-Transfer-Encoding:binary");
		$objWriter->save('php://output');
		exit();
	}

	/**
	 * 实体卡管理
	 *
	 */
	public  function physical_card(){
		$card_list = D('Physical_card')->get_cardid_by_mer_id($this->store['mer_id']);
		$this->assign($card_list);
		$this->display();
	}

	public  function physical_card_add(){
		$mer_id = $this->store['mer_id'];
		if(IS_POST){
			$cardid = $_POST['cardid'];
			$card  = D('Physical_card');
			$result =$card->check_card($cardid,$_POST['phone'],$mer_id);
			if($result['error_code']){
				$this->error($result['msg']);
			}
			$card->bind_user($result['card_info'],$result['user'],$this->staff_session['name'].'用实体卡（卡号：'.$_POST['cardid'].'）为用户充值'.$result['card']['balance_money'].'元');
			$log['staff_id'] = $this->staff_session['id'];
			$log['mer_id'] =$this->store['mer_id'];
			$log['card_id'] = $_POST['cardid'];
			$log['des'] = '店员 '.$this->staff_session['name'].' 为用户（'.$result['user']['uid'].'）绑定实体卡（卡号：'.$_POST['cardid'].'）';
			$card->add_log($log);
			$this->success('绑定成功');
		}else{
			$this->display();
		}
	}

	public function physical_card_log(){
		$log = D('Physical_card')->card_log(0,$this->staff_session['id'],0);
		$this->assign($log);
		$this->display();
	}
}
