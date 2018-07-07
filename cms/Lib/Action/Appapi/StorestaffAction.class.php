<?php
/*
 * 店员中心 APP版
 */
class StorestaffAction extends BaseAction{
    protected $staff_session;
    protected $store;
	protected $database_store_staff;
	protected $DEVICE_ID;
	protected $order_froms = array('wap快店', 'wap商城', 'AndroidApp', 'IOSApp', '小程序', 'web快店', '线下零售');
    public function __construct(){
        parent::__construct();
        $this->DEVICE_ID = trim($_REQUEST['Device-Id']);
        if (! $this->DEVICE_ID) {
            $this->returnCode('20044004');
        }
        if (ACTION_NAME != 'config' && ACTION_NAME != 'login' && ACTION_NAME != 'reg') {
        	$ticket = I('ticket', false);
        	if ($ticket) {
	            $info = ticket::get($ticket, $this->DEVICE_ID, true);
	            if ($info) {
	                $this->staff_session = M('Merchant_store_staff')->field(true)->where(array('id'=>$info['uid']))->find();
				}
			}else{
				$this->returnCode('20044013');
			}
            $condition_merchant_store['store_id'] = $this->staff_session['store_id'];
            $this->store = M('Merchant_store')->field(true)->where($condition_merchant_store)->find();
            if (empty($this->store)) {
				$this->returnCode('20130004');
            }
        }
    }
	public function config(){
		$config['can_register'] = true;
		$config['site_phone'] = $this->config['site_phone'];
		$this->returnCode('0',$config);
	}
	public function reg(){
		$this->returnCode('0');
	}
	//	登录
    public function login(){
    	$ios	=	I('ios');
    	$ticket =	I('ticket', false);
		$client = 1;
    	if($ios == 1 ){
			$old_device	=	I('old_device');
			if (empty($old_device)) {
	            $this->returnCode('20130029');
	        }
			$ios_info = ticket::get($ticket, $old_device, true);
			if($ios_info){
				$tickets = ticket::create($ios_info['uid'], $this->DEVICE_ID, true);
				$ticket	=	$tickets['ticket'];
			}else{
				$this->returnCode('20130030');
			}
			$save = true;

    	}else{
			$client = 2;
		}

		$database_store_staff = D('Merchant_store_staff');
		if ($ticket){
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $now_staff = $database_store_staff->field(true)->where(array('id'=>$info['uid']))->find();
                unset($now_staff['password']);
                $return = array(
                    'ticket'    =>	$ticket,
                    'user'      =>	$now_staff,
                );
            }
            $where['id'] = $now_staff['id'];
	        $data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
        }else{
			if(empty($_POST['account'])){
				$this->returnCode('20130001');
			}
			$condition_store_staff['username'] = trim($_POST['account']);
	        $now_staff = $database_store_staff->field(true)->where($condition_store_staff)->find();
	        if (empty($now_staff)) {
	            $this->returnCode('20130001');
	        }
	        $pwd = md5(trim($_POST['passwd']));
	        if ($pwd != $now_staff['password']) {
	            $this->returnCode('20130002');
	        }
	        unset($now_staff['password']);
	        $ticket = ticket::create($now_staff['id'], $this->DEVICE_ID, true);
	        $return = array(
                'ticket'    =>	$ticket['ticket'],
                'user'      =>	$now_staff,
            );
	        $where['id'] = $now_staff['id'];
	        $data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
        }
		if($client==2){
			$data_store_staff['device_id'] = $this->DEVICE_ID;
			$data_store_staff['client'] = $client;
			$save=$database_store_staff->where($where)->data($data_store_staff)->save();
		}
        if ($save) {
			$condition_merchant_store['store_id'] = $return['user']['store_id'];
            $return['user']['mer_id'] = M('Merchant_store')->field(true)->where($condition_merchant_store)->getField('mer_id');
	        $this->returnCode(0,$return);
	    } else {
	        $this->returnCode('20130003');
	    }
    }

	public function logout(){
		M('Merchant_store_staff')->where(array('id'=>$this->staff_session['id']))->setField('last_time',0);
		$this->returnCode(0);
	}
    //	首页
    public function index() {
    	$arr['have_group']	=	$this->store['have_group'];		//团购
    	$arr['have_meal']	=	$this->store['have_meal'];		//餐饮
		if(!M('Merchant_store_meal')->field('store_id')->where(array('store_id'=>$this->store['store_id']))->find()){
			$arr['have_meal'] = 0;
		}
    	$arr['have_shop']	=	$this->store['have_shop'];		//快店
		if(!M('Merchant_store_shop')->field('store_id')->where(array('store_id'=>$this->store['store_id']))->find()){
			$arr['have_shop'] = 0;
		}
    	$arr['have_appoint']	=	isset($this->config['appoint_page_row']) ? 1 : 0;//预约
    	$arr['have_store']	=	isset($this->config['pay_in_store']) ? 1 : 0;//到店
    	$arr['have_group_name']	=	$this->config['group_alias_name'];	//团购
    	$arr['have_meal_name']	=	$this->config['meal_alias_name'];	//餐饮
    	$arr['have_shop_name']	=	$this->config['shop_alias_name'];	//快店
    	$arr['have_appoint_name']=	$this->config['appoint_alias_name'];//预约
    	$arr['pay_in_store']=	$this->config['pay_in_store'];//预约

		$arr['have_store_name']=	'优惠买单';
		$arr['have_cash_name']	=	'到店支付';

		$config = M('Appapi_app_config')->select();
		foreach($config as $v){
			if($v['var']=='staff_android_v'){
				$arr['android_version'] = $v['value'];
			}elseif($v['var']=='staff_android_url'){
				$arr['android_downurl'] = $v['value'];
			}elseif($v['var']=='staff_android_vcode'){
				$arr['android_version_code'] = $v['value'];
			}elseif($v['var']=='staff_android_vdesc'){
				$arr['android_version_version_desc'] = $v['value'];
			}

		}
		$arr['time'] = time();//预约
        $this->returnCode(0,$arr);
    }
	//	团购订单列表
    public function group_list(){
        $this->check_group();
        $store_id = $this->store['store_id'];
        $order_id	=	$_POST['order_id'];
		$condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`store_id`='$store_id'";
        $condition_table = array(C('DB_PREFIX') . 'group' => 'g', C('DB_PREFIX') . 'group_order' => 'o', C('DB_PREFIX') . 'user' => 'u');
		$count = D('')->where($condition_where)->table($condition_table)->count();
		if($order_id){
			$condition_where .= " AND `o`.`order_id` < '$order_id'";
		}
        $order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`g`.`pic`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->page('1','10')->order('`o`.`add_time` DESC')->select();
		if(empty($order_list)){
			$arr['order_list']	=	array();
		}else{
			$arr['order_list']	=	$this->group_format($order_list);
		}
		$arr['page']	=	ceil($count/10);
		$arr['count'] = $count;
		$arr['status'] = 1;
		$this->returnCode(0,$arr);
    }
    //	团购新单统计数量
	public function group_count()
	{
		$this->check_group();
		$time = I('time', time());
		$where = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'pay_time' => array('gt', $time));
		$count = M('Group_order')->where($where)->count();
		$data_store_staff['device_id'] = $this->DEVICE_ID;
		$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_store_staff['client'] = I('client');
		if($data_store_staff['client'] ==2){
			$save=M('Merchant_store_staff')->where($where)->data($data_store_staff)->save();
		}

		if ($count < 0) {
			$this->returnCode('20130027');
		}
		$this->returnCode(0, array('count' => $count, 'time' => time()));
	}
    //	团购搜索
    public function group_find(){
    	$this->check_group();
    	$order_id	=	$_POST['order_id'];
        $mer_id = $this->store['mer_id'];
        $condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`mer_id`='$mer_id'";
        $find_value = $_POST['find_value'];
        $store_id = $this->store['store_id'];
        $res	=	'';
        if ($_POST['find_type'] == 1 && strlen($find_value) == 14) {
            $res = D('Group_pass_relation')->get_orderid_by_pass($find_value);
            if(!empty($res)){
                $condition_where .= " AND `o`.`order_id`=".$res['order_id'];
            }else{
                $condition_where .= " AND `o`.`group_pass`='$find_value'";
            }
            //$condition_where .= " AND `o`.`group_pass`='$find_value'";
        } else {
            $condition_where .= " AND `o`.`store_id`='$store_id'";
            if ($_POST['find_type'] == 1) {
                $condition_where .= " AND `o`.`group_pass` like '$find_value%'";
            } else if ($_POST['find_type'] == 2) {
                $condition_where .= " AND `o`.`express_id` like '$find_value%'";
            } else if ($_POST['find_type'] == 3) {
                $condition_where .= " AND `o`.`real_orderid`='$find_value'";
            } else if ($_POST['find_type'] == 4) {
                $condition_where .= " AND `o`.`group_id`='$find_value'";
            } else if ($_POST['find_type'] == 5) {
                $condition_where .= " AND `o`.`uid`='$find_value'";
            } else if ($_POST['find_type'] == 6) {
                $condition_where .= " AND `u`.`nickname` like '$find_value%'";
            } else if ($_POST['find_type'] == 7) {
                $condition_where .= " AND `o`.`phone` like '$find_value%'";
            }
        }
        $condition_table = array(C('DB_PREFIX') . 'group' => 'g', C('DB_PREFIX') . 'group_order' => 'o', C('DB_PREFIX') . 'user' => 'u');
        $count = D('')->where($condition_where)->table($condition_table)->count();
        if($order_id){
        	if(empty($res)){
				$condition_where .= " AND `o`.`order_id` < '$order_id%'";
        	}
        }
        $order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`g`.`pic`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->page('1','10')->order('`o`.`add_time` DESC')->select();
        if ($order_list) {
        	$arr['order_list']	=	$this->group_format($order_list);
        }else{
			$arr['order_list']	=	array();
        }
        $arr['page'] = ceil($count/10);
        $arr['count'] = $count;
        $arr['status'] = 2;
        $this->returnCode(0,$arr);
    }
    /* 团购相关 */
    protected function check_group(){
        if (empty($this->store['have_group'])){
			$this->returnCode('20130005');
        }
    }
    //	团购状态格式化
    private function group_format($order_list){
		if($order_list){
			foreach ($order_list as $v){
				$status_format	=	$this->status_format($v['status'],$v['paid'],$v['pay_type'],$v['tuan_type']);
				$group_image_class = new group_image();
				$all_pic = $group_image_class->get_allImage_by_path($v['pic']);
				$arr[]	=	array(
					's_name'	=>	$v['s_name'],	//名称
					'num'		=>	$v['num'],		//数量
					'total_money'=>	$v['total_money'],		//总价
					'status'	=>	$status_format['status'],		// 状态
					'type'		=>	$status_format['type'],		// 团购券状态
					'order_id'	=>	$v['order_id'],		// 团购ID
					'pic'		=>	$all_pic[0]['image'],		// 图片
					'pass_array'=>	$v['pass_array'],	//判断多个优惠券还是单个
					'is_pick_in_store'=>	$v['is_pick_in_store'],	//取货确认
				);
	        }
	        return $arr;
		}
		return array();
    }
    //	团购状态格式化
    private function status_format($order_status,$paid,$third_id,$pay_type,$tuan_type){
    	$type	=	0;
		$status	=	0;
		if($order_status	==	3){
			$status	=	1;	//已取消
		}else if($paid){
			if($third_id==3 && $pay_type=='offline' && $order_status==0){
				$status	=	2;	//线下未付款
			}else if($order_status==0){
				$status	=	3;	//已付款
				if($tuan_type!=2){
					$type	=	1;	//未消费
				}else{
					$type	=	2;	//未发货
				}
			}else if($order_status==1){
				$status	=	4;	//待评价
				if($tuan_type!=2){
					$type	=	3;	//已消费
				}else{
					$type	=	4;	//已发货
				}
			}else{
				$status	=	5;	//已完成
			}
		}else{
			$status	=	6;	//未付款
		}
		$arr	=	array(
			'type'	=>	$type,
			'status'	=>	$status,
		);
		return $arr;
    }
    //	验证团购劵
    public function group_verify(){
        $this->check_group();
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'], $_POST['order_id'], false);
		if(empty($now_order['paid'])){
			$this->returnCode('20130019');
		}
		if($now_order['status']!=0){
			$this->returnCode('20130038');
		}
        if (empty($now_order)) {
            $this->returnCode('20130006');
        } else if ($now_order['paid'] && $now_order['status'] == 0) {
            $condition_group_order['order_id'] = $now_order['order_id'];
            if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
                $data_group_order['third_id'] = $now_order['order_id'];
            }
            $data_group_order['status'] = '1';
            $data_group_order['store_id'] = $this->store['store_id'];
            $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
            $data_group_order['last_staff'] = $this->staff_session['name'];
            if ($database_group_order->where($condition_group_order)->data($data_group_order)->save()) {
            	$this->group_notice($now_order,1);
                D('Group_pass_relation')->change_refund_status($now_order['order_id'],1);

				//验证增加商家余额
				$now_order['order_type'] = 'group';
				$now_order['verify_all'] = 1;
				$now_order['store_id'] = $this->store['store_id'];
				D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买'.$now_order['name'].'记入收入',$now_order);

                $this->returnCode(0);
            } else {
                $this->returnCode('20130007');
            }
        } else {
            $this->returnCode('20130008');
        }
    }
    //	验证多个团购券(单个验证)
    public function group_array_verify(){
        $this->check_group();
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_POST['order_id'],false);
		$verify_all = false;
		if(empty($now_order['paid'])){
			$this->returnCode('20130019');
		}
		if($now_order['status']!=0){
			$this->returnCode('20130038');
		}
        if(empty($now_order)){
            $this->returnCode('20130006');
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
                        $now_order['total_money'] = $now_order['price'];
                        $now_order['res'] = $res;
                    }
                    $data_group_order['store_id'] = $this->store['store_id'];
                    $condition_group_order['order_id'] = $where['order_id'];
                    $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
                    $data_group_order['last_staff'] = $this->staff_session['name'];
                    if(D('Group_order')->where($condition_group_order)->data($data_group_order)->save()){
						$this->group_notice($now_order, $verify_all);
						//验证增加商家余额
						$now_order['order_type'] = 'group';
						$now_order['verify_all'] = 0;
						D('Merchant_money_list')->add_money($this->store['mer_id'],'验证团购订单'.$now_order['real_orderid'].'的消费码</br>'.$_POST['group_pass'].'记入收入',$now_order);
						$this->returnCode(0);
                    }else{
                        $this->returnCode('20130007');
                    }
                }else{
                    $this->returnCode('20130022');
                }
            }else{
                $this->returnCode('20130021');
            }
        }
    }
    //	团购积分、短信、打印
    private function group_notice($order,$verify_all){
		if($verify_all) {
			//积分
			
			D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay'])* $this->config['score_get']), '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.$this->config['score_name']);
			D('Scroll_msg')->add_msg('yydb',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).  '购买 ' . $order['order_name'] . '成功并消费获得'.$this->config['score_name']);
			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['total_money'], '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.$this->config['score_name']);
			
			//商家推广分佣
			$now_user = M('User')->where(array('uid' => $order['uid']))->find();
			D('Merchant_spread')->add_spread_list($order, $now_user, 'group', $now_user['nickname'] . '购买' . C('config.group_alias_name') . '获得佣金');
		}
    	//短信
    	$sms_data = array('mer_id' => $order['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'group');
    	if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
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

    	if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
    		$sms_data['uid'] = 0;
    		$sms_data['mobile'] = $this->store['phone'];
    		$sms_data['sendto'] = 'merchant';
    		$sms_data['content'] = '顾客购买的' . $order['order_name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
    		Sms::sendSms($sms_data);
    	}
    	//小票打印
    	$msg = ArrayToStr::array_to_str($order['order_id'], 'group_order');
    	$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
    	$op->printit($this->store['mer_id'], $this->store['store_id'], $msg, 2);
    }
    //	团购详情
    public function group_edit(){
        $this->check_group();
        $order_id	=	$_POST['order_id'];
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'], $order_id, false);
        if (empty($now_order)) {
            $this->returnCode('20130006');
        }
		if(!empty($now_order['paid'])){
			if($now_order['is_pick_in_store']){
				$now_order['paytypestr']="到店自提";
			}else{
				$now_order['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);
			}
			if(($now_order['pay_type']=='offline') && !empty($now_order['third_id']) && ($now_order['paid']==1)){
				$paytypestr	=	'已支付';
			}else if(($now_order['pay_type']!='offline') && ($now_order['paid']==1)){
				$paytypestr	=	'已支付';
			}else{
				$paytypestr	=	'未支付';
			}
		}else{
		    $now_order['paytypestr'] = '未支付';
		}
		if($now_order['tuan_type'] == 0){
			$order_type	=	$this->config['group_alias_name'].'劵';
		}else if($now_order['tuan_type'] == 1){
			$order_type	=	'代金券';
		}else{
			$order_type	=	'实物';
		}
		$status_format	=	$this->status_format($now_order['status'],$now_order['paid'],$now_order['pay_type'],$now_order['tuan_type']);
		if($now_order['status']>0 && $now_order['status']<3){
			if($now_order['tuan_type'] != 2){
				$operation	=	'消费';
			}else{
				$operation	=	'发货';
			}
		}
		$group_image_class = new group_image();
		$all_pic = $group_image_class->get_allImage_by_path($now_order['pic']);
		$arr['now_order']	=	array(
			's_name'	=>	$now_order['s_name'],				//团购名
			'pic'	=>	$all_pic[0]['image'],					//团购名
			'order_id'	=>	$now_order['order_id'],				//订单ID
			'real_orderid'	=>	$now_order['real_orderid'],		//订单ID
			'group_id'	=>	$now_order['group_id'],				//团购ID
			'status_s'	=>	$now_order['status'],				//状态
			'is_pick_in_store'	=>	$now_order['is_pick_in_store'],				//状态
			'order_type'=>	$order_type,						//订单类型
			'status'	=>	$status_format['status'],			//订单状态
			'type'		=>	$status_format['type'],				//订单状态
			'pass_array'=>	isset($now_order['pass_array'])?$now_order['pass_array']:'',		//操作
			'group_pass'=>	$now_order['group_pass'],
			'num'		=>	(int)$now_order['num'],						//数量
			'price'		=>	$now_order['price'],						//单价
			'add_time'	=>	date('Y-m-d H:i',$now_order['add_time']),	//下单时间
			'pay_time'	=>	date('Y-m-d H:i:s',$now_order['pay_time']),	//付款时间
			'operation'	=>	isset($operation)?$operation:'',			//消费 发货
			'use_time'	=>	date('Y-m-d H:i:s',$now_order['use_time']),	//消费 发货  时间
			'last_staff'=>	$now_order['last_staff'],			//操作店员
			'paystatus'	=>	isset($paytypestr)?$paytypestr:'',	//已支付	未支付
			'paytypestr'=>	$now_order['paytypestr'],			//货到付款  未支付
			'delivery_comment'=>	$now_order['delivery_comment'],			//备注
			'total_money'	=>	$now_order['total_money'],			//总金额
			'tuan_type'	=>	$now_order['tuan_type'],			//总金额
		);
		if($paytypestr == '未支付'||$now_order['paytypestr']=='未支付'){
			$arr['now_order']['pay_time']	=	'未支付';
		}
		if($now_order['third_id']==0 && $now_order['pay_type']=='offline'){
			$arr['now_order']['total_moneys']	=	$now_order['total_money'];			//总金额
			$arr['now_order']['balance_pay']	=	$now_order['balance_pay'];			//平台余额支付
			$arr['now_order']['merchant_balance']	=	$now_order['merchant_balance'];	//商家会员卡余额支付
			if($now_order['wx_cheap']!='0.00'){
				$arr['now_order']['wx_cheap']	=	$now_order['wx_cheap'];				//微信优惠
			}else{
				$arr['now_order']['wx_cheap']	=	0;
			}
			$arr['now_order']['payment_money']	=	0;									//在线支付金额
			$arr['now_order']['payment']	=	$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price'];	//线下需向商家付金额 红色字体
		}else{
			$arr['now_order']['total_moneys']	=	0;									//总金额
			$arr['now_order']['balance_pay']	=	$now_order['balance_pay'];			//平台余额支付
			$arr['now_order']['merchant_balance']=	$now_order['merchant_balance'];		//商家会员卡余额支付
			$arr['now_order']['wx_cheap']		=	0;									//微信优惠
			$arr['now_order']['payment_money']	=	$now_order['payment_money'];		//在线支付金额
			$arr['now_order']['payment']		=	0;
		}
		$arr['user']	=	array(
			'uid'	=>	$now_order['uid'],						//用户ID
			'nickname'	=>	$now_order['nickname'],				//用户名
			'phone'	=>	$now_order['phone'],					//订单手机号
			'user_phone'=>	$now_order['user_phone'],			//用户手机
		);
		$arr['distribution']	=	array(
			'contact_name'	=>	$now_order['contact_name'],			//联系名
			'phone'		=>	$now_order['phone'],					//联系电话
			'zipcode'	=>	$now_order['zipcode'],					//邮编
			'adress'	=>	$now_order['adress'],					//地址
			'express_id'	=>	$now_order['express_id'], 			//快递单号
			'express_type'	=>	$now_order['express_type'], 		//快递公司
			'merchant_remark'	=>	$now_order['merchant_remark'], //标记
		);
		switch($now_order['delivery_type']){
			case 1:
				$arr['distribution']['delivery_type']	=	'工作日、双休日与假日均可送货';
				break;
			case 2:
				$arr['distribution']['delivery_type']	=	'只工作日送货';
				break;
			case 3:
				$arr['distribution']['delivery_type']	=	'只双休日、假日送货';
				break;
			case 4:
				$arr['distribution']['delivery_type']	=	'白天没人，其它时间送货';
				break;
		}
        $express_list = D('Express')->get_express_list();
        if($express_list){
			foreach($express_list as &$v){
				if($v['id'] == $now_order['express_type']){
					$arr['distribution']['express_name']	=	$v['name'];
				}
				$v['ids']	=	$v['id'];
				unset($v['code'],$v['url'],$v['sort'],$v['add_time'],$v['status'],$v['id']);
			}
			if(empty($arr['distribution']['express_name'])){
				$arr['distribution']['express_name']	=	$express_list[0]['name'];
				$arr['distribution']['express_type']	=	$express_list[0]['ids'];
			}
        }else{
			$express_list	=	array();
        }
        $arr['express_list']	=	$express_list;
        $this->returnCode(0,$arr);
    }
    //	团购填写快递信息
    public function group_express(){
        $this->check_group();
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'], $_POST['order_id'], false);
        if (empty($now_order)) {
            $this->returnCode('20130006');
        }
		if(empty($now_order['paid'])){
			$this->returnCode('20130019');
		}
		if($now_order['status']!=0){
			$this->returnCode('20130038');
		}

        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['express_type'] = $_POST['express_type'];
        $data_group_order['express_id'] = $_POST['express_id'];
        $data_group_order['last_staff'] = $this->staff_session['name'];
        if ($now_order['paid'] == 1 && $now_order['status'] == 0) {
            $data_group_order['status'] = 1;
            $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
            $data_group_order['store_id'] = $this->store['store_id'];
        }
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
        	$this->group_notice($now_order,1);
            $this->returnCode(0);
        } else {
            $this->returnCode('20130020');
        }
    }
    //	团购额外信息
    public function group_remark(){
        $this->check_group();
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'], $_POST['order_id'], true, false);
        if (empty($now_order)) {
            $this->returnCode('20130006');
        }
        if (empty($now_order['paid'])) {
            $this->returnCode('20130019');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['merchant_remark'] = $_POST['merchant_remark'];
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20130020');
        }
    }
    //	团购多消费券
    public function group_pass_array(){
        $this->check_group();
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'],$_POST['order_id'],false);
        $now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
        $un_pay =$now_order['total_money']-$now_order['wx_cheap']-$now_order['merchant_balance']-$now_order['balance_pay']-$now_order['score_deducte']-$now_order['coupon_price'];
        $has_pay = $now_order['total_money']-$un_pay;
        $pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
        foreach($pass_array as &$v){
            $v['need_pay'] = 100;
//            $v['need_pay'] = $has_pay>$now_order['price']?0:$now_order['price']-$has_pay;
            $has_pay=($has_pay-$now_order['price'])>0?$has_pay-$now_order['price']:0;
            unset($v['id']);
        }
        $arr['pass_array']	=	isset($pass_array)?$pass_array:array();
        if($now_order){
			$arr['now_order']	=	array(
				'status'	=>	$now_order['status'],
			);
        }else{
			$arr['now_order']	=	array();
        }
        $this->returnCode(0,$arr);
    }
    //	客户自提
    public function group_pick(){
	    $this->check_group();
	    $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'],$_POST['order_id'],false);
	    if(empty($now_order)){
	        $this->returnCode('20130006');
	    }
		if(empty($now_order['paid'])){
			$this->returnCode('20130019');
		}
		if($now_order['status']!=0){
			$this->returnCode('20130038');
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
	        $this->group_notice($now_order,1);
	        $this->returnCode(0);
	    }else{
	        $this->returnCode('20130020');
	    }
    }
    //	餐饮列表
    public function meal_list(){
        $this->check_meal();
        $store_id = intval($this->store['store_id']);
        $order_id	=	$_POST['order_id'];
        $where = array('mer_id' => $this->store['mer_id'], 'store_id' => $store_id);
		$stauts=isset($_POST['st']) ? intval(trim($_POST['st'])) :false;
        $ftype = isset($_POST['ft']) ? trim($_POST['ft']) : '';
        $fvalue = isset($_POST['fv']) ? trim(htmlspecialchars($_POST['fv'])) : '';
        $page	=	1;
		if(empty($ftype) && ($stauts==1)){
			$where['paid']=1; $where['status']=0;$ftype='st';
		}else{
	        switch ($ftype) {
	            case 'oid': //订单id
	                $fvalue && $where['order_id'] = array('like', "%$fvalue%");
	                break;
	            case 'xm':  //下单人姓名
	                $fvalue && $where['name'] = array('like', "%$fvalue%");
	                break;
	            case 'dh':  //下单人电话
	                $fvalue && $where['phone'] = array('like', "%$fvalue%");
	                break;
	            case 'mps': //消费码
	                $fvalue && $where['meal_pass'] = array('like', "%$fvalue%");
	                break;
	            default:
	                break;
	        }
        }
        $Meal_orderDb = D("Meal_order");
        $count = $Meal_orderDb->where($where)->count();
        if($order_id && $ftype != 'oid'){
			$where['order_id']	=	array('lt',$order_id);
        }else if($_POST['page']){
			$page	=	$_POST['page'];
        }
        import('@.ORG.wap_group_page');
        $notOffline = 1;
        $pay_offline_open = $this->config['pay_offline_open'];
        if ($pay_offline_open == 1) {
        	$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
        	if ($now_merchant) {
        		$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
        	}
        }

        $list = $Meal_orderDb->where($where)->order("order_id DESC")->page($page,10)->select();
        foreach ($list as $k=>&$l) {
            if ($notOffline && $l['paid'] == 0) $l['is_confirm'] = 2;
            $order_list[$k]	=	array(
				'order_id'	=>	$l['order_id'],
				'name'	=>	$l['name'],
				'phone'	=>	$l['phone'],
				'price'	=>	$l['price'],
				'dateline'	=>	date('Y-m-d H:i:s',$l['dateline']),
				'address'	=>	$l['address'],
				'type'	=>	0,
				'clerk_status'	=>	0,
            );
            if($l['status'] == 3){
				$order_list[$k]['status']	=	1;	//已取消并退款	红色字体
            }else if(empty($l['third_id']) && ($l['pay_type'] == 'offline')){
				$order_list[$k]['status']	=	2;	//线下未支付	红色字体
            }else{
				if($l['paid'] == 0){
					$order_list[$k]['status']	=	3;	//未支付	红色字体
				}else{
					$order_list[$k]['status']	=	4;	//已支付	绿色字体
				}
				if($l['status'] == 0){
					$order_list[$k]['type']	=	1;	//未消费
				}else if($l['status'] == 1){
					$order_list[$k]['type']	=	2;	//已消费
				}else if($l['status'] == 2){
					$order_list[$k]['type']	=	3;	//已消费并且已评价
				}
            }
            if($l['status'] > 2){
				$order_list[$k]['clerk_status']	=	1;	//订单已取消	红色字体
            }else if($l['is_confirm'] == 1){
				$order_list[$k]['clerk_status']	=	2;	//已接单	绿色字体
            }else if($l['is_confirm'] == 0){
				$order_list[$k]['clerk_status']	=	3;	//接单	绿色字体
            }else{
				$order_list[$k]['clerk_status']	=	4;	//未支付，不能接单	红色字体
            }
        }
		$arr['order_list']	=	isset($order_list)?$order_list:array();
		$arr['page']		=	ceil($count/10);
		$arr['count']		=	$count;
		if(empty($stauts) && empty($ftype) && empty($fvalue)){
			$arr['status'] 		= 	1;
		}else{
			$arr['status'] 		= 	1;
		}
		$this->returnCode(0,$arr);
    }
    /* 检查是否开启餐饮 */
    protected function check_meal(){
        if (empty($this->store['have_meal'])) {
			$this->returnCode('20130009');
        }
        $store_id = intval($this->store['store_id']);
        $meal	=	M('Merchant_store_meal')->field('store_id')->where(array('store_id'=>$store_id))->find();
        if(empty($meal)){
			$this->returnCode('20130028');
        }
    }
    //	餐饮新单统计数量
	public function meal_count()
	{
		$this->check_meal();
		$time =	I('time', time());
		$where = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'pay_time' => array('gt', $time));
		$count = M('Meal_order')->where($where)->count();
		$data_store_staff['device_id'] = $this->DEVICE_ID;
		$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_store_staff['client'] = I('client');
		if($data_store_staff['client'] ==2){
			$save=M('Merchant_store_staff')->where($where)->data($data_store_staff)->save();
		}
		if ($count < 0) {
			$this->returnCode('20130027');
		}
		$this->returnCode(0, array('count' => $count, 'time' => time()));
	}
    //	餐饮详情
    public function meal_edit(){
    	$this->check_meal();
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $store_id = intval($this->store['store_id']);
        $order = D('Meal_order')->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find();

		$order['info'] = unserialize($order['info']);
		if ($order['store_uid']) {
			$staff = M('Merchant_store_staff')->where(array('id' => $order['store_uid']))->find();
			$order['store_uname'] = $staff['name'];
		}
		if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
			$order['paid'] = 0;
		}
		if(!empty($order['paid'])){
			$order['paytypestr'] = D('Pay')->get_pay_name($order['pay_type']);
			if(($order['pay_type']=='offline') && !empty($order['third_id']) && ($order['paid']==1)){
				$paytypestr	=	'已支付';
			}else if(($order['pay_type']!='offline') && ($order['paid']==1)){
				$paytypestr	=	'已支付';
			}else{
				$paytypestr	=	'未支付';
			}
		}else{
			$order['paytypestr'] = '未支付';
		}
		$arr['order_details']	=	array(
			'order_id'	=>	$order['order_id'],
			'name'	=>	$order['name'],
			'phone'	=>	$order['phone'],
			'price'	=>	$order['price'],
			'dateline'	=>	date('Y-m-d H:i:s',$order['dateline']),
			'address'	=>	$order['address'],
			'note'	=>	$order['note'],
			'paytypestr'	=>	$order['paytypestr'],
			'paystatus'	=>	isset($paytypestr)?$paytypestr:'',	//已支付	未支付
			'status'	=>	$order['status'],
			'clerk_status'	=>	0,
			'meal_type'	=>	$order['meal_type'],
		);
		if($order['status'] > 2){
			$arr['order_details']['clerk_status']	=	1;	//订单已取消	红色字体
		}else if($order['is_confirm'] == 1){
			$arr['order_details']['clerk_status']	=	2;	//已接单	绿色字体
		}else if($order['is_confirm'] == 0){
			$arr['order_details']['clerk_status']	=	3;	//接单	绿色字体
		}else{
			$arr['order_details']['clerk_status']	=	4;	//未支付，不能接单	红色字体
		}
		if($order['status'] == 3){
			$arr['order_details']['order_status']	=	'已取消并退款';
		}else if(empty($order['third_id']) && ($order['pay_type'] == 'offline')){
			$arr['order_details']['order_status']	=	'线下未支付';
		}else if($order['paid'] == 0){
			$arr['order_details']['order_status']	=	'未付款';
		}else{
			$arr['order_details']['order_status']	=	'已付款';
		}
		if($order['third_id'] == '0' && $order['pay_type'] == 'offline'){
			$arr['order_details']['total_price']	=	$order['total_price'];
			$arr['order_details']['balance_pay']	=	$order['balance_pay'];
			$arr['order_details']['merchant_balance']	=	$order['merchant_balance'];
			$arr['order_details']['payment']	=	$order['total_price']-$order['merchant_balance']-$order['balance_pay'];
			$arr['order_details']['payment_money']	=	0;
		}else{
			$arr['order_details']['total_price']	=	0;
			$arr['order_details']['balance_pay']	=	$order['balance_pay'];
			$arr['order_details']['merchant_balance']	=	$order['merchant_balance'];
			$arr['order_details']['payment']	=	0;
			$arr['order_details']['payment_money']	=	$order['payment_money'];
		}
		if(!empty($now_order['use_time'])){
			if($order['tuan_type'] != 2){
				$arr['order_details']['ptime']	=	'消费';
			}else{
				$arr['order_details']['ptime']	=	'发货';
			}
			$arr['order_details']['use_time']	=	date('Y-m-d H:i:s',$now_order['use_time']);
			$arr['order_details']['last_staff']	=	$now_order['last_staff'];
		}else{
			$arr['order_details']['ptime']	=	'';
			$arr['order_details']['use_time']	=	'';
			$arr['order_details']['last_staff']	=	'';
		}
		if($order['status'] == 0){
			$arr['order_details']['notes']	=	'注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，状态修改后就不能修改了';
		}else{
			$arr['order_details']['notes']	=	'';
		}
		$arr['info']	=	$order['info'];

		foreach($arr['info'] as &$v){
			$v['total']	=	$v['price']*$v['num'];
		}

    	if(empty($arr['info'])){
			$arr['info'] = array();
    	}
        $this->returnCode(0,$arr);
    }
    //	餐饮确定消费
    public function meal_consumption(){
    	$this->check_meal();
    	$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $store_id = intval($this->store['store_id']);
        $status = intval($_POST['status']);
        if(empty($status)){
			$this->returnCode('20130010');
        }
        $order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find();
        if ($order) {
			if ($order['status'] > 2) {
				$this->returnCode('20130011');
			}
            $data = array('store_uid' => $this->staff_session['id'], 'status' => $status,'use_time'=>time(),'last_staff'=>$this->staff_session['name']);
			if(empty($order['third_id']) && $order['pay_type'] == 'offline'){
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
					$this->returnCode('20130012');
	            }
	        }
            if ($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
                $data['third_id'] = $order['order_id'];
                $data['pay_type'] = 'offline';
                $data['paid'] = 1;
                $data['pay_time'] = $_SERVER['REQUEST_TIME'];
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
				$this->returnCode(0);
			} else {
				$this->returnCode('20130013');
			}
        } else {
			$this->returnCode('20130014');
        }
    }
    //	餐饮积分、短信、打印
    private function meal_notice($order){

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
		D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) *$this->config['score_get']), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
		D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.  $this->store['name'] . ' 中消费获得'.$this->config['score_name']);
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
		//小票打印
		$msg = ArrayToStr::array_to_str($order['order_id']);
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->store['mer_id'], $order['store_id'], $msg, 2);

		$str_format = ArrayToStr::print_format($order['order_id']);
		foreach ($str_format as $print_id => $print_msg) {
			$print_id && $op->printit($this->store['mer_id'], $order['store_id'], $print_msg, 2, $print_id);
		}
    }
    //	餐饮接单
    public function check_confirm(){
    	$this->check_meal();
		$database = D('Meal_order');
		$order_id = $condition['order_id'] = intval($_POST['order_id']);
		$condition['store_id'] = $this->store['store_id'];
		$order = $database->field(true)->where($condition)->find();
		if(empty($order)){
			$this->returnCode('20130006');
		}
		if ($order['paid'] == 0){
			$this->returnCode('20130016');
		}
		if ($order['status'] > 2){
			$this->returnCode('20130015');
		}
		$notOffline = 1;
		$pay_offline_open = $this->config['pay_offline_open'];
		if ($pay_offline_open == 1) {
			$now_merchant = D('Merchant')->get_info($this->store['mer_id']);
			if ($now_merchant) {
				$notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
			}
		}
		if ($notOffline && $order['paid'] == 0) {
			$this->returnCode('20130016');
		}

		if ($order['meal_type'] == 1) {
			$deliverCondition['store_id'] = $this->store['store_id'];
			$deliverCondition['mer_id'] = $this->store['mer_id'];
// 			if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
    			$old = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find();
    			if (empty($old)) {
					$deliverType = $deliver['type'];
					$address_id = $order['address_id'];
					$address_info = D('User_adress')->where(array('adress_id' => $address_id))->find();

					$supply['order_id'] = $order_id;
					$supply['paid'] = $order['paid'];
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
						$this->returnCode('20130017');
					}
    			}
// 			} else {
// 				$this->returnCode('20130018');
// 			}
		}

		$data['is_confirm'] = 1;
		$data['order_status'] = 3;
		$data['store_uid'] = $this->staff_session['id'];
		$data['last_staff'] = $this->staff_session['name'];
		if ($database->where($condition)->save($data)) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20130019');
		}
	}
    //	快店列表
    public function shop_list(){
    	$this->check_shop();
        $store_id = intval($this->store['store_id']);
        $where = array('mer_id' => $this->store['mer_id'], 'store_id' => $store_id);
		$stauts = isset($_POST['st']) ? intval(trim($_POST['st'])) :false;
        $ftype = isset($_POST['ft']) ? trim($_POST['ft']) : '';
        $fvalue = isset($_POST['fv']) ? trim(htmlspecialchars($_POST['fv'])) : '';
        $order_id	=	$_POST['order_id'];
		if (empty($ftype) && ($stauts == 1)) {
			$where['paid'] = 1;
			$where['status'] = 0;
			$ftype = 'st';
		}else{
	        switch ($ftype) {
	            case 'oid': //订单id
	                $fvalue && $where['real_orderid'] = array('like', "%$fvalue%");
	                break;
	            case 'xm':  //下单人姓名
	                $fvalue && $where['username'] = array('like', "%$fvalue%");
	                break;
	            case 'dh':  //下单人电话
	                $fvalue && $where['phone'] = array('like', "%$fvalue%");
	                break;
	            case 'mps': //消费码
	                $fvalue && $where['orderid'] = $fvalue;
	                break;
	            default:
	                break;
	        }
        }
        if(empty($where['paid'])){
        	$where['paid'] = 1;
        }
        $count = M('Shop_order')->where($where)->count();
        if($order_id){
        	$where['order_id']	=	array('lt',$order_id);
        }
        $where['mer_id'] = $this->store['mer_id'];
        $where['store_id'] = $store_id;
        $shop_order	=	D('Shop_order')->get_order_list($where, 'order_id DESC', 10);
        if($shop_order['order_list']){
			foreach($shop_order['order_list'] as $k=>$v){
				if($v['pay_type_str'] == '未支付'){
						$pay_status	=	'';
		        }else{
					if(empty($v['third_id']) && ($v['pay_type'] == 'offline')){
						$pay_status	=	'线下未支付';
			        }else if($v['paid'] == 0){
						$pay_status	=	'未支付';
			        }else{
						$pay_status	=	'已支付';
			        }
		        }
		        $arr['shop_order'][$k]	=	array(
					'order_id'	=>	$v['order_id'],
					'username'	=>	$v['username'],
					'userphone'	=>	$v['userphone'],
					'address'	=>	$v['address'],
					'create_time'	=>	date('Y-m-d H:i:s',$v['create_time']),
					'price'	=>	$v['price'],
					'pay_type_str'	=>	$v['pay_type_str'],
					'status'	=>	$v['status'],
					'paid'		=>	$v['paid'],
					'pay_status'	=>	$pay_status,
		        );
			}
        }else{
			$arr['shop_order']	=	array();
        }
        $arr['page']	=	ceil($count/10);;
        $arr['count']	=	$count;
        if(empty($stauts) && empty($ftype) && empty($fvalue)){
			$arr['status'] 		= 	1;
        }else{
			$arr['status'] 		= 	2;
        }
        $this->returnCode(0,$arr);
    }
    /* 检查是否开启餐饮 */
    protected function check_shop(){
        if (empty($this->store['have_shop'])) {
			$this->returnCode('20130025');
        }
        $store_id = intval($this->store['store_id']);
        $meal	=	M('Merchant_store_shop')->field('store_id')->where(array('store_id'=>$store_id))->find();
        if(empty($meal)){
			$this->returnCode('20130028');
        }
    }
    //	快店新单统计数量
	public function shop_count()
	{
		$this->check_shop();
		$time = I('time', time());

		$where = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'pay_time' => array('gt', $time));
		$count = M('Shop_order')->where($where)->count();
		$data_store_staff['device_id'] = $this->DEVICE_ID;
		$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_store_staff['client'] = I('client');
		if($data_store_staff['client'] ==2){
			$save=M('Merchant_store_staff')->where($where)->data($data_store_staff)->save();
		}
		if ($count < 0) {
			$this->returnCode('20130027');
		}
		$this->returnCode(0, array('count' => $count, 'time' => time()));
	}
    //	快店接单
    public function shop_order_confirm(){
    	$this->check_shop();
    	$database = D('Shop_order');
    	$order_id = $condition['order_id'] = intval($_POST['order_id']);
    	$condition['store_id'] = $this->store['store_id'];
    	$order = $database->field(true)->where($condition)->find();
    	if(empty($order)){
    		$this->returnCode('20130006');
    	}
    	if ($order['status'] > 0){
			$this->returnCode('20130015');
    	}
		if ($order['paid'] == 0){
			$this->returnCode('20130016');
		}
		if ($order['is_refund']) {
			$this->returnCode(1, null, '用户正在退款中~！');
			exit;
		}

		$data['status'] = 1;
		$data['order_status'] = 1;
		$data['last_staff'] = $this->staff_session['name'];
		$data['last_time'] = time();
    	if ($database->where($condition)->data($data)->save()) {
			if ($order['is_pick_in_store'] != 2 && $order['is_pick_in_store'] != 3) {
			    $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
			    if ($result['error_code']) {
			        D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
			        $this->returnCode(1, null, $result['msg']);
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
// 					$supply['deliver_cash'] = round($order['price'] - round($order['card_price']+$order['merchant_balance']+$order['card_give_money']+$order['balance_pay']+$order['payment_money']+$order['score_deducte']+$order['coupon_price'], 2), 2);
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
// 					$supply['appoint_time'] = $order['expect_use_time'];
// 					$supply['note'] = $order['desc'];

// 					$supply['order_time'] = $order['create_time'];
// 					$supply['freight_charge'] = $order['freight_charge'];
// 					$supply['distance'] = round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2);

// 					if ($supply_db_table->create($supply) != false) {
// 						if ($addResult = D('Deliver_supply')->add($supply)) {
// 						} else {
// 							$this->returnCode('20130017');
// 						}
// 					} else {
// 						$this->returnCode('20130018');
// 					}
// 				}
			}
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
    		$this->returnCode(0);
    	} else {
    		$this->returnCode('20130017');
    	}
    }
    //	快店详情
	public function shop_edit(){
		$this->check_shop();
    	$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    	$store_id = intval($this->store['store_id']);
    	$order = D("Shop_order")->get_order_detail(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id));
//     	$User_adress	=	M('User_adress')->field(array('longitude','latitude'))->where(array('adress_id'=>$order['address_id']))->find();
    	if($order){
    		if($order['pay_type'] == 'offline' && empty($order['third_id'])){
//				$payment	=	floatval($order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']));
				$payment	=	rtrim(rtrim(number_format($order['price']-$order['card_price']-$order['merchant_balance']-$order['card_give_money']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']),2,'.',''),'0'),'.');
    		}
    		$discount_price = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'] - $order['merchant_reduce'] - $order['balance_reduce'], 2));
    		$order['card_discount'] = $order['card_discount'] == 0 ? 10 : $order['card_discount'];
			$arr['order_details']	=	array(
				'orderid'	=>	$order['orderid'],
				'order_id'	=>	$order['order_id'],
				'real_orderid'	=>	$order['real_orderid'],
				'username'	=>	$order['username'],
				'userphone'	=>	$order['userphone'],
				'create_time'	=>	date('Y-m-d H:i:s',$order['create_time']),
				'pay_time'	=>	date('Y-m-d H:i:s',$order['pay_time']),
				'expect_use_time'	=>	$order['expect_use_time']!=0 ? (date('Y-m-d H:i',$order['expect_use_time'])) : '尽快',
				'is_pick_in_store'	=>	$order['is_pick_in_store'],
				'address'	=>	$order['address'],
				'deliver_str'	=>	$order['deliver_str'],
				'deliver_status_str'	=>	$order['deliver_status_str'],
				'note'	=>	isset($order['desc'])?$order['desc']:'',
				'invoice_head'	=>	$order['invoice_head'],
				'pay_status'	=>	$order['pay_status_print'],
				'pay_type_str'	=>	$order['pay_type_str'],
				'status_str'	=>	$order['status_str'],
				'score_used_count'	=>	$order['score_used_count'],//抵用的积分
				'score_deducte'	=>	strval(floatval($order['score_deducte'])),//积分兑现的金额
				'card_give_money'	=>	strval(floatval($order['card_give_money'])),//会员卡赠送余额
				'merchant_balance'	=>	strval(floatval($order['merchant_balance'])),//商家余额
				'balance_pay'	=>	strval(floatval($order['balance_pay'])),//平台余额
				'payment_money'	=>	strval(floatval($order['payment_money'])),//在线支付的金额
				'change_price'	=>	strval(floatval($order['change_price'])),//店员修改前的原始价格（如果是0表示没有修改过，可不显示）
				'change_price_reason'	=>	$order['change_price_reason'],//店员修改价格的理由
				'card_id'	=>	$order['card_id'],
				'card_price'	=>	strval(floatval($order['card_price'])),//商家优惠券的金额
				'coupon_price'	=>	strval(floatval($order['coupon_price'])),//平台优惠券的金额
				'payment'	=>	isset($payment)?$payment:0,
				'use_time'	=>	$order['use_time']!=0 ? (date('Y-m-d H:i:s',$order['use_time'])) : '0',
				'last_staff'	=>	$order['last_staff'],
				'status'	=>	$order['status'],
				'paid'	=>	$order['paid'],
				'register_phone'	=>	$order['register_phone'],//注册时的用户手机号
				'lat'	=>	$order['lat'],
				'lng'	=>	$order['lng'],
				'cue_field'	=> empty($order['cue_field']) ? array() : $order['cue_field'],//商家自定义字段值（如果没有的话是空 即：''）
				'card_discount'	=>	$order['card_discount'],//会员卡折扣
				'goods_price'	=>	strval(floatval($order['goods_price'])),//商品的总价
				'freight_charge'	=>	strval(floatval($order['freight_charge'])),//配送费
				'packing_charge'	=>	strval(floatval($order['packing_charge'])),//打包费
				'total_price'	=>	strval(floatval($order['total_price'])),//订单总价
				'merchant_reduce'	=>	strval(floatval($order['merchant_reduce'])),//商家优惠的金额
				'balance_reduce'	=>	strval(floatval($order['balance_reduce'])),//平台优惠的金额
				'price'	=>	strval(floatval($order['price'])),//实际支付金额
				'discount_price'	=>	strval($discount_price),//折扣与优惠后的总价
				'minus_price'	=>	strval(floatval(round($order['total_price'] - $discount_price, 2))),//折扣与优惠的优惠金额
				'minus_card_discount'	=>	strval($discount_price * (1 - $order['card_discount'] * 0.1)),//折扣与优惠的优惠金额
				'notes'	=>	'注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，状态修改后就不能修改了',
			);
			foreach($order['info'] as $k=>$v){
			    $discount_price = floatval($v['discount_price']) > 0 ? floatval($v['discount_price']) : floatval($v['price']);
				$arr['info'][]	=	array(
					'name'	=>	$v['name'],
					'price'	=>	strval(floatval($v['price'])),
					'discount_price'	=>	strval($discount_price),
					'spec'	=>	empty($v['spec']) ? '' : $v['spec'],
					'num'	=>	$v['num'],
					'total'	=>	strval(floatval($v['price'] * $v['num'])),
					'discount_total'	=>	strval(floatval($discount_price * $v['num'])),
				);
			}
			$arr['discount_detail'] = $order['discount_detail'] ?: '';
    	}else{
			$arr['order_details']	=	array();
    	}
    	if(empty($arr['info'])){
			$arr['info']	=	array();
    	}
    	$this->returnCode(0,$arr);
    }
    //	快店确定消费
    public function shop_consumption(){
    	$this->check_shop();
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    	$store_id = intval($this->store['store_id']);
    	$status = intval($_POST['status']);
    	if(empty($status)){
			$this->returnCode('20130010');
        }
    	if ($order = D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
    		if ($order['status'] > 3) {
    			$this->returnCode('20130011');
    		}
    		$data = array('status' => $status, 'order_status' => 6, 'use_time' => time(), 'last_staff' => $this->staff_session['name']);
    		if(empty($order['third_id']) && $order['pay_type'] == 'offline'){
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
    				$this->returnCode('20130012');
    			}
    		}
    		if ($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
    			$data['third_id'] = $order['order_id'];
    			$data['pay_type'] = 'offline';
    			$data['paid'] = 1;
    		}
    		$data['cancel_type'] = 2;////取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
    		if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
    			if ($status == 2 && $order['status'] < 2) {
    				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
    				if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find()) {
    					if ($supply['status'] < 2) {
    						D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->delete();
    					} else {
    						D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 5));
    					}
    				}
    				$this->shop_notice($order);
    			}
    			$this->returnCode(0);
    		} else {
    			$this->returnCode('20130013');
    		}
    	} else {
			$this->returnCode('20130014');
    	}
    }
    //	快店积分、短信、打印
    private function shop_notice($order, $is_staff = false){
		
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
		}
		//验证增加商家余额
		$order['order_type']='shop';
		D('Merchant_money_list')->add_money($this->store['mer_id'],'用户购买快店订单记入收入',$order);

		//商家推广分佣
		$now_user = M('User')->where(array('uid' => $order['uid']))->find();
		D('Merchant_spread')->add_spread_list($order, $now_user, 'shop', $now_user['nickname'] . '用户购买快店商品获得佣金');

    	//积分
    
		D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
		D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.  $this->store['name'] . ' 中消费获得'.$this->config['score_name']);
		

		D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');

		//短信
		$sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'shop');
		if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
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
		if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
			$sms_data['uid'] = 0;
			$sms_data['mobile'] = $this->store['phone'];
			$sms_data['sendto'] = 'merchant';
			$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
			Sms::sendSms($sms_data);
		}

		//小票打印
		$msg = ArrayToStr::array_to_str($order['order_id'], 'shop_order');
		$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
		$op->printit($this->store['mer_id'], $order['store_id'], $msg, 2);



		$str_format = ArrayToStr::print_format($order['order_id'], 'shop_order');
		foreach ($str_format as $print_id => $print_msg) {
			$print_id && $op->printit($this->store['mer_id'], $order['store_id'], $print_msg, 2, $print_id);
		}
    }
    //	预约列表
	public function appoint_list() {
        $store_id = $this->store['store_id'];
		$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
    	$order_id	=	$_POST['order_id'];
    	$where['store_id'] = $store_id;
    	$count = $database_order->field(true)->where($where)->count();
    	if($order_id){
			$where['order_id']	=	array('lt',$order_id);
    	}
    	$order_info = $database_order->field(true)->where($where)->page(1,10)->order('`order_id` DESC')->select();
        $uidArr = array();
        foreach($order_info as $v){
        	array_push($uidArr,$v['uid']);
        }
        $uidArr = array_unique($uidArr);
    	$user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid'=>array('in',$uidArr)))->select();
    	$appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
    	$store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
    	$order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
    	if($order_list){
    		foreach($order_list as $v){
    			$truename	=	'';
				if($v['truename']){
					$truename	=	$v['truename'];
    			}else{
					$truename	=	$v['nickname'];
    			}
				$arr['order_list'][]	=	array(
					'order_id'	=>	$v['order_id'],
					'appoint_name'	=>	$v['appoint_name'],
					'truename'	=>	$truename,
					'phone'	=>	$v['phone'],
					'appoint_date'	=>	$v['appoint_date'].' '.$v['appoint_time'],
					'payment_money'	=>	floatval($v['payment_money']),
					'appoint_price'	=>	floatval($v['appoint_price']),
					'paid'			=>	$v['paid'],
					'service_status'=>	$v['service_status'],
				);
    		}
    	}else{
			$arr['order_list']	=	array();
    	}
    	$arr['count']	=	$count;
    	$arr['page'] = ceil($count/10);
    	$arr['status']	=	1;
    	$this->returnCode(0,$arr);
    }
    /* 预约相关 */
    protected function check_appoint(){
        if (empty($this->config['appoint_page_row'])) {
			$this->returnCode('20130026');
        }
    }
    //	预约新单统计数量
	public function appoint_count()
	{
		$this->check_appoint();
		$time = I('time', time());
		$where = array('store_id' => $this->store['store_id'], 'pay_time' => array('gt', $time));
		$count = M('Appoint_order')->where($where)->count();
		$data_store_staff['device_id'] = $this->DEVICE_ID;
		$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_store_staff['client'] = I('client');
		if($data_store_staff['client'] ==2){
			$save=M('Merchant_store_staff')->where($where)->data($data_store_staff)->save();
		}
		if ($count < 0) {
			$this->returnCode('20130027');
		}
		$this->returnCode(0, array('count' => $count, 'time' => time()));
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
	/*验证预约服务*/
	public function appoint_verify(){
		$database_order = D('Appoint_order');
		$where['store_id'] = $this->store['store_id'];
		$where['order_id'] = $_POST['order_id'];
		$now_order = $database_order->field(true)->where($where)->find();
		if(empty($now_order)){
			$this->returnCode('20130006');
		} else if ($now_order['paid']!=2 && $now_order['service_status'] == 0) {
			$fields['store_id'] = $this->staff_session['store_id'];
			$fields['last_staff'] = $this->staff_session['name'];
			$fields['last_time'] = time();
			$fields['service_status'] = 1;
			if($database_order->where($where)->data($fields)->save()){

				//验证增加商家余额
				$order_info['order_id'] = $now_order['order_id'];
				$order_info['store_id'] = $now_order['store_id'];
				$order_info['order_type'] = 'appoint';
				$order_info['balance_pay'] = $now_order['balance_pay'];
				$order_info['score_deducte'] = $now_order['score_deducte'];
				$order_info['payment_money'] = $now_order['pay_money'];
				$order_info['is_own'] = $now_order['is_own'];
				$order_info['merchant_balance'] = $now_order['merchant_balance'];
				$order_info['score_used_count'] = $now_order['score_used_count'];
				$order_info['money'] = $order_info['balance_pay'] + $order_info['score_deducte'] + $order_info['payment_money'] + $order_info['merchant_balance'];

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

				$appoint_name = M('Appoint')->field('appoint_name')->where(array('appoint'=>$now_order['appoint_id']))->find();
				D('Merchant_money_list')->add_money($this->store['mer_id'],'用户预约'.$appoint_name['appoint_name'].'记入收入',$order_info);

				if($this->config['open_score_get_percent']==1){
					$score_get = $this->config['score_get_percent']/100;
				}else{
					$score_get = $this->config['user_score_get'];
				}

				D('User')->add_score($order_info['uid'], round(($order_info['balance_pay']+$order_info['payment_money']) * $score_get), '购买预约商品获得'.$this->config['score_name']);

				$this->returnCode(0);
			} else {
				$this->returnCode('20130007');
			}
		}else{
			$this->returnCode('20130008');
		}
	}
	/*预约订单查找*/
	public function appoint_find(){
		$database_order = D('Appoint_order');
	    $database_user = D('User');
	    $database_appoint = D('Appoint');
	    $database_store = D('Merchant_store');
		$order_id	=	$_POST['order_id'];
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
		if($order_id && $_POST['find_type'] != 2){
			$appoint_where['order_id']	=	array('lt',$order_id);
    	}
    	$count = $database_order->where($appoint_where)->count();
	    $order_info = $database_order->field(true)->where($appoint_where)->order('`order_id` DESC')->select();
	    $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($user_where)->select();
	    $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
	    $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
	    $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);

	    if($order_list){
	    	foreach($order_list as $k=>$v){
	    		if($_POST['find_type'] == 5){
                    if(!isset($v['nickname'])){
                        unset($order_list[$k]);
                        continue;
                    }
                }else if($_POST['find_type'] == 6){
                    if(!isset($v['phone'])){
                        unset($order_list[$k]);
                        continue;
                    }
                }
    			$truename	=	'';
				if($v['truename']){
					$truename	=	$v['truename'];
    			}else{
					$truename	=	$v['nickname'];
    			}
				$arr['order_list'][]	=	array(
					'order_id'	=>	$v['order_id'],
					'appoint_name'	=>	isset($v['appoint_name'])?$v['appoint_name']:'',
					'truename'	=>	$truename,
					'phone'	=>	$v['phone'],
					'appoint_date'	=>	$v['appoint_date'].' '.$v['appoint_time'],
					'payment_money'	=>	floatval($v['payment_money']),
					'appoint_price'	=>	floatval($v['appoint_price']),
					'paid'			=>	$v['paid'],
					'service_status'=>	$v['service_status'],
				);
    		}
	    }else{
			$arr['order_list']	=	array();
	    }

		if($arr['order_list']){
			$arr['count'] = $count;
			$arr['page'] = ceil($count/10);
		}else{
			$arr['count'] = 0;
			$arr['page'] = 0;
			$arr['order_list']	=	array();
		}
		$arr['status']	=	2;
		$this->returnCode(0,$arr);
	}
	/*预约订单详情*/
	public function appoint_edit(){
		$where['order_id'] = $_POST['order_id'];
		$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
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
    		$address	=	'';
    		if(!empty($val['value'])){
    			if($val['type'] == 2){
    				$address = $val['address'];
    			}
    			if($val['long'] && $val['lat']){
					$long	=	$val['long'];
					$lat	=	$val['lat'];
    			}
    			$cue_list[]	=	array(
					'name'	=>	$val['name'],
					'value'	=>	$val['value'],
					'type'	=>	$val['type'],
					'address'=>	isset($address)?$address:'',
    			);
    		}
    	}
    	if($now_order){
			$arr['now_order']	=	array(
				'appoint_id'	=>	$now_order['appoint_id'],
				'appoint_name'	=>	$now_order['appoint_name'],
				'order_id'	=>	$now_order['order_id'],
				'appoint_date'	=>	$now_order['appoint_date'].' '.$now_order['appoint_time'],
				'paid'	=>	$now_order['paid'],
				'service_status'	=>	$now_order['service_status'],
				'payment_money'	=>	floatval($now_order['payment_money']),
				'appoint_price'	=>	floatval($now_order['appoint_price']),
				'order_time'	=>	date('Y-m-d H:i',$now_order['order_time']),
				'pay_time'	=>	$now_order['pay_time']!=0 ? date('Y-m-d H:i',$now_order['pay_time']) :'',
				'paytypestr'	=>	isset($now_order['paytypestr'])?$now_order['paytypestr']:'',
				'balance_pay'	=>	$now_order['balance_pay'],
				'merchant_balance'	=>	$now_order['merchant_balance'],
				'pay_money'	=>	$now_order['pay_money'],
				'last_time'	=>	$now_order['last_time']!=0 ? date('Y-m-d H:i',$now_order['last_time']) :'',
				'last_staff'	=>	isset($now_order['last_staff'])?$now_order['last_staff']:'',
				'content'	=>	isset($now_order['content'])?$now_order['content']:'',
				'uid'	=>	$now_order['uid'],
				'nickname'	=>	$now_order['nickname'],
				'phone'	=>	$now_order['phone'],
				'longs'	=>	isset($long)?$long:0,
				'lats'	=>	isset($lat)?$lat:0,
			);
    	}else{
			$arr['now_order']	=	array();
    	}
    	$arr['cue_list']	=	$cue_list;
    	$this->returnCode(0,$arr);
	}
    /*     * *团购扫二维码验证*** */
    public function group_qrcode(){
        $group_pass = trim($_POST['group_pass']);
        $this->check_group();
        if(empty($group_pass)){
			$this->returnCode('20130023');
        }
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->where(array('mer_id' => $this->store['mer_id'], 'group_pass' => $group_pass))->find();
        if (empty($now_order)) {
            $this->returnCode('20130006');
        } else if ($now_order['paid'] && $now_order['status'] == 0) {
            $condition_group_order['order_id'] = $now_order['order_id'];
            if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
                $data_group_order['third_id'] = $now_order['order_id'];
            }
            $data_group_order['status'] = '1';
            $data_group_order['store_id'] = $this->store['store_id'];
            $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
            $data_group_order['last_staff'] = $this->staff_session['name'];
            if ($database_group_order->where($condition_group_order)->data($data_group_order)->save()) {
            	$this->group_notice($now_order,1);
                D('Group_pass_relation')->change_refund_status($now_order['order_id'],1);
                $this->returnCode(0);
            } else {
                $this->returnCode('20130007');
            }
        } else {
            $this->returnCode('20130008');
        }
    }
    /*     * *餐饮扫二维码验证*** */
    public function meal_qrcode(){
        $order_id = trim($_POST['order_id']);
        $this->check_meal();
        if(empty($order_id)){
			$this->returnCode('20130024');
        }
        $store_id = intval($this->store['store_id']);
        if (!empty($order_id)) {
            if ($order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
				if ($order['status'] > 2) {
					$this->returnCode('20130011');
				}
                $data = array('store_uid' => $this->staff_session['id'], 'status' => 1,'use_time'=>time(),'last_staff'=>$this->staff_session['name']);

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
                		$this->returnCode('20130012');
                	}
                }
				if($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))){
					$data['third_id'] = $order['order_id'];
                    $data['pay_type'] = 'offline';
                    $data['paid'] = 1;
                    $data['pay_time'] = $_SERVER['REQUEST_TIME'];
				}
				$data['use_time'] = $_SERVER['REQUEST_TIME'];
                if (D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
                	if ($order['status'] == 0) {
                		if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find()) {
                			if ($supply['status'] < 2) {
                				D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->delete();
                			} else {
                				D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->save(array('status' => 5));
                			}
                		}
                		$this->meal_notice($order);
                	}
	                $this->returnCode(0);
                } else {
                	$this->returnCode('20130007');
                }
            } else {
                $this->returnCode('20130006');
            }
        } else {
            $this->returnCode('20130006');
        }
    }
	/*     * *快店扫二维码验证*** */
    public function shop_qrcode(){
        $order_id = trim($_POST['order_id']);
        $this->check_shop();
        if(empty($order_id)){
			$this->returnCode('20130024');
        }
        $store_id = intval($this->store['store_id']);
		$status = 2;
		if ($order = D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
			if ($order['status'] > 3) {
				$this->returnCode('20130011');
			}
			$data = array('status' => $status, 'order_status' => 6, 'use_time' => time(), 'last_staff' => $this->staff_session['name']);
  			if(empty($order['third_id']) && $order['pay_type'] == 'offline'){
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
					$this->returnCode('20130012');
				}
			}
			if ($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
				$data['third_id'] = $order['order_id'];
				$data['pay_type'] = 'offline';
				$data['paid'] = 1;
			}

			if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
				if ($status == 2 && $order['status'] < 2) {
					D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
					if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find()) {
						if ($supply['status'] < 2) {
 							D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->delete();
						} else {
							D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 5));
						}
					}
  					$this->shop_notice($order);
				}
				$this->returnCode(0);
			} else {
				$this->returnCode('20130013');
			}
		} else {
 			$this->returnCode('20130014');
		}
    }

	//到店消费

    /*
     *
     *
     *	到店支付  begin
     *
     *
     */
    //到店付订单列表
    public function store_arrival()
    {
		$store_order = D('Store_order');
		$res = $store_order->where(array('orderid'=>$_POST['order_id']))->find();

		$order_id = $res['order_id'];

		$condition = '';
		if($_POST['condition']&&$_POST['keyword']){
			if($_POST['condition']=='order_id'){
				$_POST['condition']='orderid';
			}
			$condition='AND '.$_POST['condition'].' like "%'.$_POST['keyword'].'%"';
		}

        $store_order = D('Store_order');

		if($order_id&&empty($condition)){
			$condition .= ' AND order_id < '.$order_id;
		}

        $sql = "SELECT s.*, u.nickname, u.phone FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} AND s.from_plat=2 {$condition} ORDER BY s.order_id DESC LIMIT 10";
        $order_list = $store_order->query($sql);
		$sql2 = "SELECT count(*) as count FROM " . C('DB_PREFIX') . "store_order AS s LEFT JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} AND s.from_plat=2 {$condition} ORDER BY s.order_id DESC";

		$count = $store_order->query($sql2);
		$pagenum = ceil($count[0]['count'] /10);

        foreach ($order_list as &$l) {
			$arr[]=array(
				'order_id'=>$l['orderid'],
				'phone'=>empty($l['phone'])?'':$l['phone'],
				'total_price'=>$l['total_price'],
				'pay_time'=>date('Y/m/d H:i:s',$l['pay_time'])
			);
        }
		if($order_list){

			$this->returnCode(0,array('order_list' => $arr,'pagenum'=>$pagenum));

		}else{
			$this->returnCode(0,array('order_list' => array(),'pagenum'=>$pagenum));
		}
    }

    //创建到店付订单
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
			if($_POST['pay_money']==0){
				$data['paid']=1;
			}
            $data['dateline'] = time();
            $data['from_plat'] = 2;
            $order_id = M("Store_order")->add($data);

			if($_POST['pay_money']==0){
				$this->returnCode(0);
			}
            if($order_id){
                $this->returnCode(0,array('orderid'=>$order_id,'order_id'=> $data['orderid'],'img'=>$this->config['site_url'].'/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id='.floor($order_id+3600000000)));
            }else{
                $this->returnCode('20130031');
            }
        }else{
            $this->returnCode('20130014');
        }
    }

    public function store_arrival_add_info(){
        $arr['discount_txt'] = unserialize($this->store['discount_txt']);
        $info['has_discount']=$arr['discount_txt'] ? true : false;
        $info['discount_type']= isset($arr['discount_txt']['discount_type']) ? $arr['discount_txt']['discount_type'] : 0;
        $info['discount_percent']= isset($arr['discount_txt']['discount_percent']) ? $arr['discount_txt']['discount_percent'] : 0;
        $info['condition_price']= isset($arr['discount_txt']['condition_price']) ? $arr['discount_txt']['condition_price'] : 0;
        $info['minus_price']= isset($arr['discount_txt']['minus_price']) ? $arr['discount_txt']['minus_price'] : 0;
        $this->returnCode(0,$info);

    }

    //到店付详情
    public function store_arrival_order(){

        $order_id  = $_POST['order_id'];
        $now_order = M("Store_order")->where(array('orderid'=>$order_id))->find();
		if(empty($now_order['uid'])){
			$user['uid'] = '';
			$user['nickname'] = '';
			$user['phone'] = '';
		}else{
			$now_user=M('User')->where(array('uid'=>$now_order['uid']))->find();
			$user['uid'] = $now_user['uid'];
			$user['nickname'] = $now_user['nickname'];
			$user['phone'] = $now_user['phone'];
		}
		if($now_order){
			$arr['order_id'] = $now_order['orderid'];
			$arr['total_price'] = $now_order['total_price'];
			$arr['discount_price'] = $now_order['discount_price'];
			$arr['price'] = $now_order['price'];
			$arr['desc'] = $now_order['desc'];
			$arr['pay_time'] =date('Y/m/d H:i:s', $now_order['pay_time']);
			$arr['pay_type'] = D('Pay')->get_pay_name($now_order['pay_type'],1);

			$this->returnCode(0,array('order'=>$arr,'user'=>$user));
		}else{
			$this->returnCode(0,array('order'=>array(),'user'=>$user));
		}
        //$orderprinter = M("Orderprinter")->where(array('store_id'=>$this->store['store_id']))->order('`is_main` DESC')->find();

    }
    //到店付打印小票
    public function store_arrival_print(){
        $now_order = M("Store_order")->where(array('orderid'=>$_POST['order_id']))->find();
        $msg = ArrayToStr::array_to_str($_POST['order_id'], 'store_order');
        $op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
        $op->printit($now_order['mer_id'], $now_order['store_id'], $msg);
		$this->returnCode(0);
    }

    //到店确认支付
    public function store_arrival_pay(){
        $order_id  = $_POST['order_id'];
        $now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
		if($now_order['paid']){
			$this->returnCode('20130037');
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

        if($_POST['re_call']){
            $param = array();
            $param['appid'] = $this->config['pay_weixin_appid'];
            $param['mch_id'] = $this->config['pay_weixin_mchid'];
            $param['nonce_str'] = $this->createNoncestr();
            $param['out_trade_no'] = 'store_'.$now_order['orderid'];
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
            $param['out_trade_no'] = 'store_'.$now_order['orderid'];
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

		if($return['return_code'] == 'FAIL'){

			$this->returnCode('1',array(),$return['return_msg']);
		}
		if($return['result_code'] == 'FAIL'){
			if($return['trade_state'] == 'NOTPAY'){
				$data['orderid'] = date("YmdHis") . mt_rand(10000000, 99999999);
				M('Store_order')->where(array('order_id'=>$order_id))->save($data);
				$this->returnCode('20130036');
			}else if($return['trade_state'] == 'USERPAYING'){
				$this->returnCode('20130035');
			}
			$this->returnCode('1',array(),$return['err_code_des']);
		}


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
        $data['payment_money'] = $return['total_fee']/100;
        $data['pay_type'] = 'weixin';
        $data['third_id'] = $return['transaction_id'];
		$data['is_own'] = $is_own;
        if(M("Store_order")->where(array('order_id'=>$order_id))->data($data)->save()){
			$order = M("Store_order")->where(array('order_id'=>$order_id))->find();

			

            D('User')->add_score($now_order['uid'], round(($order['payment_money'] + $order['balance_pay'])  * $this->config['score_get']), '在' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得'.$this->config['score_name']);

            D('Scroll_msg')->add_msg('store',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).  '在' . $this->store['name'] . ' 中使用到店消费获得'.$this->config['score_name']);

            D('Userinfo')->add_score($now_order['uid'], $now_order['mer_id'], $now_order['price'], '在 ' . $this->store['name'] . ' 中使用到店消费支付了' . floatval($now_order['price']) . '元 获得积分');

            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $href = C('config.site_url').'/wap.php?c=My&a=store_order_list';
            $model->sendTempMsg('OPENTM201752540', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => $this->store['name'], 'keyword1' => '店内消费支付提醒', 'keyword2' => $now_order['orderid'], 'keyword3' => $now_order['price'], 'keyword4' => date('Y-m-d H:i:s'), 'remark' => '付款成功，感谢您的使用'));
			$now_order = M("Store_order")->where(array('order_id'=>$order_id))->find();
			$now_order['order_type']='cash';
			//商家余额增加
			D('Merchant_money_list')->add_money($now_order['mer_id'],'用户到店支付计入收入',$now_order);

			//商家推广关系
			D('Merchant_spread')->add_spread_list($now_order,$now_user,$now_order['order_type'],$now_user['nickname'].'用户到店支付获得佣金');
            $this->returnCode(0);
        }else{
            $this->returnCode('20130034');
        }
    }

	//优惠买单
	public function store_order()
	{
		$store_order = D('Store_order');
		$res = $store_order->where(array('orderid'=>$_POST['order_id']))->find();
		$order_id = $res['order_id'];
		$condition = '';
		if($_POST['condition']&&$_POST['keyword']){
			if($_POST['condition']=='order_id'){
				$_POST['condition']='orderid';
			}
			$condition='AND '.$_POST['condition'].' like "%'.$_POST['keyword'].'%"';
		}



		if($condition){
			$where = array('paid' => 1, 'store_id' => $this->store['store_id'], 'from_plat' => 1,'_string'=>substr($condition,4));
		}else{
			$where = array('paid' => 1, 'store_id' => $this->store['store_id'], 'from_plat' => 1);
		}
		if($order_id&&empty($condition)){
			$condition .= ' AND order_id < '.$order_id;
		}

		$sql = "SELECT s.*, u.nickname, u.phone FROM " . C('DB_PREFIX') . "store_order AS s INNER JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} AND s.from_plat=1 {$condition} ORDER BY s.order_id DESC LIMIT 10";
		$sql2 = "SELECT count(*) as count FROM " . C('DB_PREFIX') . "store_order AS s INNER JOIN " . C('DB_PREFIX') . "user AS u ON s.uid=u.uid WHERE s.paid=1 AND s.store_id={$this->store['store_id']} AND s.from_plat=1 {$condition} ORDER BY s.order_id DESC ";

		$order_list = $store_order->query($sql);
		$count = $store_order->query($sql2);
		$pagenum = ceil($count[0]['count'] /10);

		$arr=array();
		foreach ($order_list as &$l) {
			$l['pay_type_show'] = D('Pay')->get_pay_name($l['pay_type'],1);
			$arr[]=array(
				'order_id'=>$l['orderid'],
				'phone'=>empty($l['phone'])?'':$l['phone'],
				'total_price'=>$l['total_price'],
				'pay_time'=>date('Y/m/d H:i:s',$l['pay_time'])
			);
		}
		if(empty($arr)){
			$this->returnCode(0,array('order_list' => array(),'pagenum'=>$pagenum));
		}else{
			$this->returnCode(0,array('order_list' => $arr,'pagenum'=>$pagenum));
		}
	}

	public function store_order_detail()
	{
		$store_order = D('Store_order');
		$where['orderid'] = $_POST['order_id'];
		$res = M('Store_order')->where($where)->find();
		$user = M('User')->where(array('uid'=>$res['uid']))->find();
		$arr['order']['order_id'] = $res['orderid'];
		$arr['order']['total_price'] = $res['total_price'];
		$arr['order']['discount_price'] = $res['discount_price'];
		$arr['order']['price'] = $res['price'];
		$arr['order']['pay_time'] = date('Y/m/d H:i:s',$res['pay_time']);
		$arr['order']['pay_type'] = D('Pay')->get_pay_name($res['pay_type'],1);
		if($user){
			$arr['user'] = array(
				'uid'=>$user['uid'],
				'nickname'=>$user['nickname'],
				'phone'=>$user['phone']
			);
		}

		$this->returnCode(0,$arr);
	}

	public function check_store_arrival_order(){
		$where['order_id'] = $_POST['order_id'];
		$res = M('Store_order')->where($where)->find();
		if($res['paid']){
			$this->returnCode(0);
		}else{
			$this->returnCode('20130035');
		}
	}

	public function store_count()
	{

		$time = I('time', time());
		$where = array('store_id' => $this->store['store_id'], 'pay_time' => array('gt', $time),'from_plat'=>1);
		$count = M('Store_order')->where($where)->count();
		$data_store_staff['device_id'] = $this->DEVICE_ID;
		$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_store_staff['client'] = I('client');
		if($data_store_staff['client'] ==2){
			$save=M('Merchant_store_staff')->where($where)->data($data_store_staff)->save();
		}
		if ($count < 0) {
			$this->returnCode('20130027');
		}
		$this->returnCode(0, array('count' => $count, 'time' => time()));
	}

	public function cash_count()
	{

		$time = I('time', time());
		$where = array('store_id' => $this->store['store_id'], 'pay_time' => array('gt', $time),'from_plat'=>2);
		$count = M('Store_order')->where($where)->count();
		$data_store_staff['device_id'] = $this->DEVICE_ID;
		$data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_store_staff['client'] = I('client');
		if($data_store_staff['client'] ==2){
			$save=M('Merchant_store_staff')->where($where)->data($data_store_staff)->save();
		}
		if ($count < 0) {
			$this->returnCode('20130027');
		}
		$this->returnCode(0, array('count' => $count, 'time' => time()));
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

	/**
	 * 
	 */
	public function foodshop()
	{
		$data = array();
		$data['queue_count'] = M('Foodshop_queue')->where(array('store_id' => $this->store['store_id'], 'status' => 0))->count();
		$count_list = M('Foodshop_table')->field('count(1) AS cnt, status')->where(array('store_id' => $this->store['store_id']))->group('status')->select();
		foreach ($count_list as $row) {
			if ($row['status'] == 1) {
				$data['lock_count'] = $row['cnt'];
			} else {
				$data['open_count'] = $row['cnt'];
			}
		}
		
		$count_list = M('Foodshop_order')->field('count(1) AS cnt, status, running_state')->where(array('store_id' => $this->store['store_id'], 'status' => array('gt', 0)))->group('status, running_state')->select();

// 		print_r($count_list);die;
		$data['book_count'] = 0;
		$data['confirm_count'] = 0;
		$data['eating_count'] = 0;
		$data['all_count'] = 0;
		$data['pay_count'] = 0;
		$data['cancel_count'] = 0;
		
		foreach ($count_list as $row) {
			if ($row['status'] == 1) {
				$data['book_count'] += $row['cnt'];
			}
			if ($row['running_state'] == 1) {
				$data['confirm_count'] += $row['cnt'];
			}
			if ($row['status'] == 2) {
				$data['eating_count'] += $row['cnt'];
			}
			if ($row['status'] > 2 && $row['status'] < 5) {
				$data['pay_count'] += $row['cnt'];
			}
			if ($row['status'] == 5) {
				$data['cancel_count'] += $row['cnt'];
			}
			$data['all_count'] += $row['cnt'];
		}
		
		//桌台列表
		$table_list = M('Foodshop_table')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		$data['table_lock'] = 0;	//锁定
		$data['table_unlock'] = 0;	//解锁
		foreach($table_list as $value){
			if($value['status'] == 0){
				$data['table_unlock']++;
			}else{
				$data['table_lock']++;
			}
		}
		
		$this->returnCode(0, $data);
	}
	
	/**
	 * 创建与编辑订单
	 */
	public function foodshop_order_before()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$where = array('order_id' => $order_id, 'store_id' => $this->store['store_id']);
		$now_order = D('Foodshop_order')->field(true)->where($where)->find();
		
		$table_type = M('Foodshop_table_type')->where(array('store_id' => $this->store['store_id']))->order('`id` ASC')->select();
		if (empty($table_type)) {
			$this->returnCode(1, null, '该店铺没有设置桌台分类');
		}
		$temp_list = M('Foodshop_table')->where(array('store_id' => $this->store['store_id'], 'status' => '0'))->order('`id` ASC')->select();
		$table_list = array();
		foreach ($temp_list as $row) {
			$table_list[$row['tid']][] = $row;
		}
		if ($now_order) {
			$row = M('Foodshop_table')->where(array('store_id' => $this->store['store_id'], 'id' => $now_order['table_id']))->find();
			$table_list[$row['tid']][] = $row;
		}
		$list = array();
		foreach ($table_type as $table) {
			if (isset($table_list[$table['id']])) {
				$table['list'] = $table_list[$table['id']];
				$list[] = $table;
			}
		}
		
		$this->returnCode(0, array('table_type' => isset($now_order['table_type']) ? $now_order['table_type'] : 0, 'table_id' => isset($now_order['table_id']) ? $now_order['table_id'] : 0, 'order_id' => $order_id, 'list' => $list));
	}
	
	/**
	 * 创建与编辑订单的保存
	 */
	public function foodshop_add_order()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$book_num = isset($_POST['book_num']) ? intval($_POST['book_num']) : 0;
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;
		$table_id = isset($_POST['table_id']) ? intval($_POST['table_id']) : 0;
		$note = isset($_POST['note']) ? htmlspecialchars($_POST['note']) : '';
		if (empty($table_type) || empty($table_id)) $this->returnCode(1, null, '餐台必须选择一个');
		if ($now_order = M('Foodshop_order')->where(array('order_id' => $order_id, 'store_id' => $this->store['store_id']))->find()) {
			$condition_order['order_id'] = $order_id;
			$condition_order['store_id'] = $this->store['store_id'];
			$data['book_num'] = $book_num;
			$data['table_type'] = $table_type;
			$data['table_id'] = $table_id;
			if(M('Foodshop_order')->where($condition_order)->data($data)->save()){
				M('Foodshop_table')->where(array('id' => $now_order['table_id']))->data(array('status'=>'0'))->save();
				M('Foodshop_table')->where(array('id' => $data['table_id']))->data(array('status'=>'1'))->save();
				$this->returnCode(0, '编辑成功');
			} else {
				$this->returnCode(1, null, '编辑订单失败，请重试');
			}
		} else {
			$data['real_orderid'] = date('ymdhis') . substr(microtime(), 2, 8 - strlen($this->store['store_id'])) . $this->store['store_id'];
			$data['mer_id'] = $this->store['mer_id'];
			$data['store_id'] = $this->store['store_id'];
			$data['book_num'] = $book_num;
			$data['table_type'] = $table_type;
			$data['table_id'] = $table_id;
			$data['note'] = $note;
			$data['order_from'] = 2;
			$data['create_time'] = $_SERVER['REQUEST_TIME'];
			$data['status'] = 1;
			if ($order_id = M('Foodshop_order')->data($data)->add()){
				M('Foodshop_table')->where(array('id' => $data['table_id']))->data(array('status' => '1'))->save();
				$this->returnCode(0, $order_id);
			} else{
				$this->returnCode(1, null, '创建订单失败，请重试');
			}
		}
	}

	
	/**
	 * status = 1 预定待确认 is_order = 0 未点餐 1已点餐
	 * status = 2 店员已确认，在用餐中， is_order = 0 就餐中，1店员待确认菜品
	 * status = 3买单完成
	 */
	
	public function foodshop_order()
	{
		$status = isset($_POST['status']) ? intval($_POST['status']) : -1;
		$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
		
		$where = array('store_id' => $this->store['store_id']);
		
		switch ($status) {
			case 1:
				$where['status'] = 1;//预定中
				break;
			case 2:
				$where['status'] = 2;//就餐中
				break;
			case 3:
				$where['status'] = array('in', array(3, 4));//已买单
				break;
			case 4:
				$where['running_state'] = 1;//待确认菜品
				break;
			case 5:
				$where['status'] = 5;//已取消
				break;
			default:
				$where['status'] = array('gt', 0);
		}
		if ($keyword) {
			$where['name|phone'] = $keyword;
		}
		$data = D('Foodshop_order')->get_order_list($where, 'order_id DESC', 2);
		$this->returnCode(0, $data);
	}
	
	
	/**
	 * 订单的菜品详情
	 */
	public function foodshop_detail()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$where = array('order_id' => $order_id, 'store_id' => $this->store['store_id']);
// 		$now_order = D('Foodshop_order')->field(true)->where($where)->find();
		
		$now_order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']), 2);
		
		if (empty($now_order)) $this->returnCode(1, null, '订单不存在');
		
		$price = D('Foodshop_order')->count_price($now_order);
		
		$goods_detail_list = $now_order['info'];
// 		$price = $now_order['price'];
		$package_list = array();
		$must_list = array();
		$old_goods_list = array();
		$total_num = 0;
		$total_price = 0;
		foreach ($goods_detail_list as $new) {
			$new['price'] = floatval($new['price']);
			$new['num'] = floatval($new['num']);
			$total_num += $new['num'];
			if ($new['package_id']) {
				if (isset($package_list[$new['package_id']])) {
					if (isset($package_list[$new['package_id']]['list'][$new['goods_id']])) {
						$package_list[$new['package_id']]['list'][$new['goods_id']]['num'] += $new['num'];
					} else {
						$package_list[$new['package_id']]['list'][$new['goods_id']] = $new;
					}
				} else {
					$package_list[$new['package_id']] = array('list' => array($new['goods_id'] => $new), 'name' => '', 'num' => 0, 'price' => 0);
				}
			} /*elseif ($new['is_must']) {
				$must_list[] = $new;
			} */else {
// 				$total_price += $new['num'] * $new['price'];
				$old_goods_list[] = $new;
			}
		}
		if ($now_order['package_ids']) {
			$package_ids = json_decode($now_order['package_ids'], true);
			$packages = D('Foodshop_goods_package')->field(true)->where(array('in' => array('id', $package_ids)))->select();
			foreach ($package_ids as $pid) {
				foreach ($packages as $p) {
					if ($pid == $p['id']) {
						$package_list[$pid]['num']++;
						$package_list[$pid]['price'] += $p['price'];
						$package_list[$pid]['name'] = $p['name'];
// 						$total_price += $p['price'];
					}
				}
			}
		}
		
		$total_price = floatval($price + $now_order['book_price']);
// 		$goods_temp_list = D('Foodshop_order_temp')->field(true)->where($where)->select();
		$goods_temp_list = $now_order['info_temp'];
		foreach ($goods_temp_list as &$temp) {
			$temp['price'] = floatval($temp['price']);
			$temp['num'] = floatval($temp['num']);
			$total_num += $temp['num'];
			$total_price += $temp['num'] * $temp['price'];
		}
		$this->returnCode(0, array('goods_list' => $old_goods_list, 'package_list' => $package_list, 'temp_list' => $goods_temp_list, 'total_price' => floatval($total_price), 'total_num' => $total_num));
	}

	/**
	 * 编辑的菜单页详情
	 */
	public function foodshop_goods()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$where = array('order_id' => $order_id, 'store_id' => $this->store['store_id']);
		
		$now_order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']), 2);
		if (empty($now_order)) $this->returnCode(1, null, '订单不存在');
		
		$price = D('Foodshop_order')->count_price($now_order);
// 		$extra_price = D('Foodshop_order')->count_extra_price($order);
		
		//已确认的菜品
		$sql = 'SELECT `d`.*, `g`.image FROM ' . C('DB_PREFIX') . 'foodshop_order_detail AS d INNER JOIN ' . C('DB_PREFIX') . 'foodshop_goods AS g ON `g`.`goods_id`=`d`.`goods_id` WHERE `d`.order_id=' . $order_id . ' AND `d`.`store_id`=' . $this->store['store_id'];
		$goods_detail_list = D()->query($sql);
		$goods_image_class = new foodshop_goods_image();
		$total_num = 0;
		foreach ($goods_detail_list as &$g) {
			$g['price'] = floatval($g['price']);
			$g['num'] = floatval($g['num']);
			$total_num += $g['num'];
// 			$total_price += $g['num'] * $g['price'];
			$tmp_pic_arr = explode(';', $g['image']);
			isset($tmp_pic_arr[0]) && $tmp_pic_arr[0] && $g['image'] = $goods_image_class->get_image_by_path($tmp_pic_arr[0], 's');
		}
		//全部的菜品分类和菜品信息
		$lists = D('Foodshop_goods')->get_list_by_storeid($this->store['store_id']);
		
		
		//用户待	确认菜品
		$temp_list = null;
// 		$goods_temp_list = D('Foodshop_order_temp')->field(true)->where($where)->select();
		$goods_temp_list = $now_order['info_temp'];
		foreach ($goods_temp_list as $temp) {
			$t_cookie = array('goods_id' => $temp['goods_id'], 'num' => $temp['num'], 'name' => $temp['name'], 'price' => floatval($temp['price']));
			$params = '';
			if ($temp['spec_id']) {
				$params = D('Foodshop_goods')->format_spec_ids($temp, $params);
			}
			if ($go['spec']) {
				$params = D('Foodshop_goods')->format_properties_ids($temp, $params);
			}
			$t_cookie['params'] = $params;
			$temp_list[] = $t_cookie;
		}
		
		
// 		$goods_package = D('Foodshop_goods_package')->get_list_by_store_id($this->store['store_id']);
		
		$goods_package = D('Foodshop_goods_package')->where(array('store_id' => $this->store['store_id'],'status' => 1))->order('`id` ASC')->select();
		foreach($goods_package as &$value){
			$value['price'] = floatval($value['price']);
			$value['goods_id'] = 0;
		}
		
		$lists[] = array('goods_list' => $goods_package, 'sort_name' => '团购套餐', 'sort_id' => -1, 'store_id' => $this->store['store_id']);
		$this->returnCode(0, array('goods_list' => $goods_detail_list, 'package_list' => $goods_package, 'temp_list' => $temp_list, 'lists' => $lists, 'total_price' => floatval($price + $now_order['book_price']), 'unpaid_price' => floatval($price), 'total_num' => $total_num, 'book_price' => floatval($now_order['book_price'])));
	}
	

	public function foodshop_getgroup_detail()
	{
		$group_id = isset($_POST['group_id']) ? intval($_POST['group_id']) : 0;
		$data = D('Foodshop_goods_package')->get_detail_by_id(array('store_id' => $this->store['store_id'], 'status' => 1, 'id' => $group_id), true);
		if ($data) {
			$this->returnCode(0, $data);
		} else {
			$this->returnCode(1, null, '不存在的团购套餐');
		}
	}
	
	
	public function foodshop_change_order()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$num = isset($_POST['num']) ? intval($_POST['num']) : 0;
		$id = isset($_POST['detail_id']) ? intval($_POST['detail_id']) : 0;
		
		$condition_where['store_id'] = $this->store['store_id'];
		$condition_where['order_id'] = $order_id;
		$condition_where['id'] = $id;
		if ($num > 0) {
			if (M('Foodshop_order_detail')->where($condition_where)->data(array('num' => $num))->save()) {
				$order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']), 2);
				$price = D('Foodshop_order')->count_price($order);
				$extra_price = D('Foodshop_order')->count_extra_price($order);
				
				$data = array('total_price' => floatval($price + $order['book_price']), 'book_price' => floatval($order['book_price']), 'unpaid_price' => floatval($price),'extra_price' => $extra_price);
				$this->returnCode(0, $data);
			} else {
				$this->returnCode(1, null, '保存失败！');
			}
		} else {
			if (M('Foodshop_order_detail')->where($condition_where)->delete()) {
				$order = D('Foodshop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']), 2);
				$price = D('Foodshop_order')->count_price($order);
				$extra_price = D('Foodshop_order')->count_extra_price($order);
				
				$data = array('total_price' => floatval($price + $order['book_price']), 'book_price' => floatval($order['book_price']), 'unpaid_price' => floatval($price),'extra_price' => $extra_price);
				$this->returnCode(0, $data);
			} else {
				$this->returnCode(1, null, '保存失败！');
			}
		}
	}
	
	
	public function foodshop_save_order()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$package_id = isset($_POST['package_id']) ? intval($_POST['package_id']) : 0;
		$carts = isset($_POST['cart']) ? $_POST['cart'] : null;
		if (empty($carts)) {
			$this->returnCode(1, null, '没有点菜');
		}
		if ($order = M('Foodshop_order')->field(true)->where(array('store_id' => $this->store['store_id'], 'order_id' => $order_id))->find()) {
	
			$cart_data = D('Foodshop_goods')->format_cart($carts, $this->store['store_id'], $order_id);
	
			if ($cart_data['err_code']) {
				$this->returnCode(1, null, $cart_data['msg']);
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
				$this->returnCode(0, '订单保存成功');
			} else {
				$this->returnCode(1, null, '订单保存失败，稍后重试！');
			}
		} else {
			$this->returnCode(1, null, '订单信息不存在！');
		}
	}
	//只打印订单里面的商品，而且只用主打印机打印，一般用于用户结算前的打印。
	public function foodshop_print_order()
	{
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		if ($order = M('Foodshop_order')->field(true)->where(array('store_id' => $this->store['store_id'], 'order_id' => $order_id))->find()) {
			D('Foodshop_order')->order_notice($order_id);//(array('order_id'=>$_GET['order_id'],'store_id'=>$this->store['store_id']))->find();
			$this->returnCode(0, '打印成功');
		} else {
			$this->returnCode(1, null, '订单信息不存在！');
		}
	}
	
	public function foodshop_table_list(){
		$table_type = M('Foodshop_table_type')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		
		if(empty($table_type)){
			$this->returnCode(1,array(),'该店铺没有设置桌台分类');
		}
		
		$table_list = M('Foodshop_table')->where(array('store_id'=>$this->store['store_id']))->order('`id` ASC')->select();
		
		foreach($table_type as $value){
			$value['table_list'] = array();
			$table_type_arr[$value['id']] = $value;
		}
		foreach($table_list as $value){
			$table_type_arr[$value['tid']]['table_list'][] = $value;
		}
		
		foreach($table_type_arr as $key=>$value){
			if(empty($value['table_list'])){
				unset($table_type_arr[$key]);
			}
		}
		
		$return = array_values($table_type_arr);

		$this->returnCode(0,$return);
	}
	public function tmp_table_lock(){
		if(M('Foodshop_table')->where(array('store_id'=>$this->store['store_id'],'id'=>$_POST['id']))->setField('status',$_POST['lock'])){
			$this->returnCode(0,array());
		}else{
			$this->returnCode(1,array(),'操作失败');
		}
	}
	public function book_list(){
		$table_id = isset($_POST['table_id']) && $_POST['table_id'] ? intval($_POST['table_id']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'table_id' => $table_id, 'status' => array('lt', 2), 'book_time' => array('gt', time()));
		// $where = array('store_id' => $this->store['store_id'], 'table_id' => $table_id);
		$return = D('Foodshop_order')->field(true)->where($where)->order('book_time ASC')->select();
		
		if(empty($return)){
			$return = array();
		}else{
			foreach($return as &$value){
				$value['book_price_txt'] = floatval($value['book_price']);
				$value['book_time_txt'] = date('Y-m-d H:i',$value['book_time']);
			}
		}
		
		$this->returnCode(0,$return);
	}
	
	

	/**
	 * 排号例表
	 */
	public function queue_list()
	{
		$where = array('store_id' => $this->store['store_id']);
		$foodshop = M('Merchant_store_foodshop')->field(true)->where($where)->find();
		if ($foodshop['is_queue'] == 0) {
			$this->returnCode(1, null, '店铺没有排号功能');
		}
		$foodshop['queue_open_time'] = date('Y-m-d H:i:s', $foodshop['queue_open_time']);
		//排队例表
		$queue_list = M('Foodshop_queue')->field(true)->where(array('store_id' => $this->store['store_id'], 'status' => 0))->order('id ASC')->select();
		$now_number_ids = null;
		$next_number_ids = null;
		$wait_number_list = array();
		$now_data = null;
		$next_data = null;
		foreach ($queue_list as $row) {
			if (!isset($now_number_ids[$row['table_type']])) {
				$now_number_ids[$row['table_type']] = $row['number'];
				$now_data = $row;
			} elseif (!isset($next_number_ids[$row['table_type']])) {
				$next_number_ids[$row['table_type']] = $row['number'];
				$next_data = $row;
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
				$t_row['now_use_num'] = $now_data['num'];
				$t_row['now_queue_from'] = $now_data['queue_from'];
			}
			if (isset($next_number_ids[$t_row['id']])) {
				$t_row['next_number'] = $next_number_ids[$t_row['id']];
				$t_row['next_use_num'] = $next_data['num'];
				$t_row['next_queue_from'] = $next_data['queue_from'];
			}
			if (isset($wait_number_list[$t_row['id']])) {
				$t_row['wait'] = $wait_number_list[$t_row['id']];
			}
			if (isset($temp[$t_row['id']])) {
				$t_row['free'] = $temp[$t_row['id']];
			}
		}
		$this->returnCode(0, array('list' => $table_type_list, 'foodshop' => $foodshop));
	}

	//开启与关闭排号
	public function change_queue()
	{
		$where = array('store_id' => $this->store['store_id']);
		$foodshop = M('Merchant_store_foodshop')->field(true)->where($where)->find();
		if (empty($foodshop)) {
			$this->returnCode(1, null, '店铺信息有问题');
		}
		if (M('Merchant_store_foodshop')->where($where)->save(array('queue_is_open' => 1 - intval($foodshop['queue_is_open']), 'queue_open_time' => time()))) {
			if ($foodshop['queue_is_open'] == 0) {
				//开启排号的时候清空以前的排号记录
				M('Foodshop_queue')->where($where)->delete();
				
				$this->queue_list();
			} else {
				$this->returnCode(0, '状态修改成功');
			}
			
		} else {
			$this->returnCode(1, null, '状态修改失败');
		}
	}

	//叫号
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
			$this->returnCode(0, '已叫号');
		} else {
			$this->returnCode(0, '没有排号了');
		}
	}
	
	//跳号
	public function queue_cancel()
	{
		$tid = isset($_POST['tid']) ? intval($_POST['tid']) : 0;
		$where = array('store_id' => $this->store['store_id'], 'status' => 0, 'table_type' => $tid);
		if ($queue = M('Foodshop_queue')->where($where)->order('id ASC')->limit(1)->find()) {
			$where['id'] = $queue['id'];
			if (M('Foodshop_queue')->where($where)->save(array('status' => 1))) {
				$this->queue_list();
			} else {
				$this->returnCode(1, null, '跳号失败，稍后重试');
			}
		} else {
			$this->returnCode(1, null, '不存在的信息');
		}
	}
	
	public function queue_create()
	{
		$table_type = M('Foodshop_table_type')->where(array('store_id' => $this->store['store_id']))->order('`id` ASC')->select();
		$this->returnCode(0, $table_type);
	}
	//现场取号
	public function queue_save()
	{
		$store_id = $this->store['store_id'];
		$table_type = isset($_POST['table_type']) ? intval($_POST['table_type']) : 0;

		
		$table_type_data = M('Foodshop_table_type')->field(true)->where(array('store_id' => $store_id, 'id' => $table_type))->find();
		if (empty($table_type_data)) {
			$this->returnCode(1, null, '不存在的桌台类型');
		}
		
		$foodshop_queue_db = M('Foodshop_queue');
// 		if ($queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'uid' => $this->user_session['uid'], 'status' => 0))->find()) {
// 			exit(json_encode(array('err_code' => true, 'msg' => '您已经取过号了，不要重新取号，如果重新取号，请先取消已经取的号')));
// 		}
		
		$fp = fopen('./runtime/' . md5(C('config.site_url') . $table_type) . '_lock.txt', "w+");
		flock($fp, LOCK_EX);
		if ($new_queue = $foodshop_queue_db->field(true)->where(array('store_id' => $store_id, 'table_type' => $table_type))->order('id DESC')->find()) {
			$number = str_replace($table_type_data['number_prefix'], '', $new_queue['number']);
		} else {
			$number = 0;
		}
		$number = intval($number) + 1;
		$new_number = $table_type_data['number_prefix'] . $number;
		$now_time = time();
		
		$num = isset($_POST['num']) ? intval($_POST['num']) : 1;
		$num = max($num, 1);
		
		$count = $foodshop_queue_db->where(array('store_id' => $store_id, 'table_type' => $table_type, 'status' => 0))->count();
		
		$use_time =  $now_time + ceil(($count + 1) / $table_type_data['num']) * $table_type_data['use_time'] * 60;
		
		$data = array('store_id' => $store_id, 'uid' => 0, 'table_type' => $table_type, 'number' => $new_number, 'create_time' => $now_time, 'use_time' => $use_time, 'num' => $num, 'status' => 0, 'queue_from' => 1);
		$queue_id = $foodshop_queue_db->add($data);
		
		flock($fp, LOCK_UN);
		fclose($fp);
		
		if ($queue_id) {
			$this->returnCode(0, $new_number);
			$this->queue_list();
// 			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
// 			$href = C('config.site_url').'/wap.php?c=Foodshop&a=queue&store_id=' . $store_id;
// 			$model->sendTempMsg('OPENTM205984119', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '尊敬的用户您好，您的排号信息如下', 'keyword1' => $new_number, 'keyword2' => date('Y.m.d H:i'), 'keyword3' => $count + 1, 'remark' => '感谢您的支持！'));
// 			exit(json_encode(array('err_code' => false, 'number' => $new_number, 'time' => $table_type_data['use_time'])));
		} else {
			$this->returnCode(1, null, '稍后重试');
		}
	}
    
    
    /**
     * 修改快店的未支付订单的价格
     */
    public function shopChangePrice()
    {
        if (empty($this->staff_session['is_change'])) {
            $this->returnCode(1, null, '您没有修改价格的权限!');
        }
        $type = isset($_POST['type']) ? htmlspecialchars($_POST['type']) : 'c';
        $change_price_reason = isset($_POST['change_price_reason']) ? htmlspecialchars($_POST['change_price_reason']) : '';
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $where = array('order_id' => $order_id, 'store_id' => $this->store['store_id']);
        $order = D('Shop_order')->get_order_detail($where);
        if (empty($order)) {
            $this->returnCode(1, null, '不存在的订单信息!');
        }
        if (!($order['paid'] == 0 && $order['status'] == 0)) {
            $this->returnCode(1, null, '该订单已经不能修改支付价格了!');
        }

        $data = array('last_staff' => $this->staff_session['name']);
        $data['last_time'] = $_SERVER['REQUEST_TIME'];
        $change_price = 0;
        if ($type == 'r') {
            if ($order['change_price'] > 0) {
                $change_price = $order['change_price'];
                $data['price'] = $order['change_price'];
            } else {
                $change_price = $order['price'];
            }
            $data['change_price'] = 0;
            $data['change_price_reason'] = '';
        } else {
            $change_price = isset($_POST['change_price']) ? floatval($_POST['change_price']) : $order['price'];
            if ($change_price == $order['price']) {
                $this->returnCode(1, null, '您没有修改价格!');
            }
            if ($change_price <= 0) {
                $this->returnCode(1, null, '您不能把价格改成小于等于0的数');
            }
            $data['price'] = $change_price;
            $data['last_staff'] = $this->staff_session['name'];
            $data['last_time'] = $_SERVER['REQUEST_TIME'];
            if (floatval($order['change_price']) == 0) {
                $data['change_price'] = $order['price'];
            }
            $data['change_price_reason'] = $change_price_reason;
        }
        if (D('Shop_order')->where($where)->save($data)) {
            $phones = explode(' ', $this->store['phone']);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 30, 'name' => $this->staff_session['name'], 'phone' => $phones[0], 'note' => $change_price));
            $this->returnCode(0, floatval($change_price));
        } else {
            echo D('Shop_order')->_sql();
            $this->returnCode(1, null, '修改出错，稍后重试！');
        }
    }
    
    /**
     * 快店的订单详情（打包app使用）
     */
    public function shopDetail()
    {
        $this->check_shop();
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $store_id = intval($this->store['store_id']);
        if ($store_shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find()) {
            $arr['deliver_type'] = $store_shop['deliver_type'];
            $arr['is_open_pick'] = $store_shop['is_open_pick'];
            $arr['is_change'] = $this->staff_session['is_change'];
        } else {
            $this->returnCode(1, null, '店铺信息不存在');
        }
        $order = D("Shop_order")->get_order_detail(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id));
        if ($order) {
            if($order['pay_type'] == 'offline' && empty($order['third_id'])){
                $payment = rtrim(rtrim(number_format($order['price']-$order['card_price']-$order['merchant_balance']-$order['card_give_money']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']),2,'.',''),'0'),'.');
            }
            $discount_price = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'], 2));
            $order['card_discount'] = $order['card_discount'] == 0 ? 10 : $order['card_discount'];
            $arr['order_details'] = array(
                'orderid' => $order['orderid'],
                'order_id' => $order['order_id'],
                'real_orderid' => $order['real_orderid'],
                'username' => $order['username'],
                'userphone' => $order['userphone'],
                'create_time' => date('Y-m-d H:i:s',$order['create_time']),
                'pay_time' => date('Y-m-d H:i:s',$order['pay_time']),
                'expect_use_time' => $order['expect_use_time'] != 0 ? date('Y-m-d H:i',$order['expect_use_time']) : '尽快',
                'is_pick_in_store' => $order['is_pick_in_store'],//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                'address' => $order['address'],
                'deliver_str' => $order['deliver_str'],
                'deliver_status_str' => $order['deliver_status_str'],
                'note' => isset($order['desc']) ? $order['desc'] : '',
                'invoice_head' => $order['invoice_head'],//发票抬头
                'pay_status' => $order['pay_status_print'],
                'pay_type_str' => $order['pay_type_str'],
                'status_str' => $order['status_str'],
                'score_used_count' => $order['score_used_count'],//抵用的积分
                'score_deducte' => strval(floatval($order['score_deducte'])),//积分兑现的金额
                'card_give_money' => strval(floatval($order['card_give_money'])),//会员卡赠送余额
                'merchant_balance' => strval(floatval($order['merchant_balance'])),//商家余额
                'balance_pay' => strval(floatval($order['balance_pay'])),//平台余额
                'payment_money' => strval(floatval($order['payment_money'])),//在线支付的金额
                'change_price' => strval(floatval($order['change_price'])),//店员修改前的原始价格（如果是0表示没有修改过，可不显示）
                'change_price_reason' => $order['change_price_reason'],//店员修改价格的理由
                'card_id' => $order['card_id'],
                'card_price' => strval(floatval($order['card_price'])),//商家优惠券的金额
                'coupon_price' => strval(floatval($order['coupon_price'])),//平台优惠券的金额
                'payment' => isset($payment) ? $payment : 0,
                'use_time' => $order['use_time'] != 0 ? date('Y-m-d H:i:s',$order['use_time']) : '0',
                'last_staff' => $order['last_staff'],
                'status' => $order['status'],
                'paid' => $order['paid'],
                'register_phone' => $order['register_phone'],//注册时的用户手机号
                'lat' => $order['lat'],
                'lng' => $order['lng'],
                'cue_field' => $order['cue_field'],//商家自定义字段值（如果没有的话是空 即：''）
                'card_discount' => $order['card_discount'],//会员卡折扣
                'goods_price' => strval(floatval($order['goods_price'])),//商品的总价
                'freight_charge' => strval(floatval($order['freight_charge'])),//配送费
                'packing_charge' => strval(floatval($order['packing_charge'])),//打包费
                'total_price' => strval(floatval($order['total_price'])),//订单总价
                'merchant_reduce' => strval(floatval($order['merchant_reduce'])),//商家优惠的金额
                'balance_reduce' => strval(floatval($order['balance_reduce'])),//平台优惠的金额
                'price' => strval(floatval($order['price'])),//实际支付金额
                'distance' => round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2),//距离
                'discount_price' => strval($discount_price),//折扣后的总价  = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'], 2));
                'minus_price' => strval(floatval(round($order['merchant_reduce'] + $order['balance_reduce'], 2))),//平台和商家的优惠金额
                'go_pay_price' => strval(floatval(round($discount_price - $order['merchant_reduce'] - $order['balance_reduce'], 2))),//应付的金额
                'minus_card_discount' => strval(floatval(round(($discount_price - $order['merchant_reduce'] - $order['balance_reduce'] - $order['freight_charge']) * (1 - $order['card_discount'] * 0.1), 2))),//折扣与优惠的优惠金额
                'notes' => '注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，状态修改后就不能修改了',
                'order_from_txt' => $this->order_froms[$order['order_from']],
                'deliver_log_list' => $order['deliver_log_list'],
            );
            foreach($order['info'] as $v) {
                $discount_price = floatval($v['discount_price']) > 0 ? floatval($v['discount_price']) : floatval($v['price']);
                $arr['info'][] = array(
                    'name' => $v['name'],
                    'discount_type' => $v['discount_type'],
                    'price' => strval(floatval($v['price'])),
                    'discount_price' => strval($discount_price),
                    'spec' => empty($v['spec']) ? '' : $v['spec'],
                    'num' => $v['num'],
                    'total' => strval(floatval($v['price'] * $v['num'])),
                    'discount_total' => strval(floatval($discount_price * $v['num'])),
                );
            }
            $arr['discount_detail'] = $order['discount_detail'] ?: '';
    	} else {
            $this->returnCode(1, null, '订单信息错误！');
        }
        if(empty($arr['info'])){
            $arr['info'] = array();
        }
        $this->returnCode(0, $arr);
    }
    
    
    /**
     * 快店的商城订单修改配送方式的订单详情
     */
    public function mallOrderDetail()
    {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
        if (empty($order)) $this->returnCode(1, null, '订单信息错误！');
        
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
    
        $return = $this->getDeliverFee($order['store_id']);
    
        $pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
        $delivery_fee = $return['delivery_fee'] + round($pass_distance * $return['per_km_price'], 2);
        $delivery_fee = $delivery_fee - $return['delivery_fee_reduce'];
        $delivery_fee = $delivery_fee > 0 ? $delivery_fee : 0;
    
        $pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
        $delivery_fee2 = $return['delivery_fee2'] + round($pass_distance * $return['per_km_price2'], 2);
        $delivery_fee2 = $delivery_fee2 - $return['delivery_fee_reduce'];
        $delivery_fee2 = $delivery_fee2 > 0 ? $delivery_fee2 : 0;
    
        
        $data = array('delivery_fee' => floatval($delivery_fee), 'delivery_fee2' => floatval($delivery_fee2));
        $data['freight_charge'] = floatval($order['freight_charge']);
        $data['deliver_type'] = $store_shop['deliver_type'];
        $data['distance'] = floatval(round($distance, 2));
        $data['address'] = $order['address'];
        $data['time_select_1'] = date('H:i', $start_time) . '-' . date('H:i', $stop_time);
        if ($have_two_time) {
            $data['time_select_2'] = date('H:i', $start_time2) . '-' . date('H:i', $stop_time2);
        } else {
            $data['time_select_2'] = 0;
        }
        $data['arrive_datetime'] = date('Y-m-d H:i', $time);
        
        $this->returnCode(0, $data);
    }
    
    private function getDeliverFee($store_id)
    {
        $store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
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
    
    /**
     * 商城订单更改配送方式  将快递 更改成 其他配送方式
     */
    public function checkDeliver()
    {
        $database = D('Shop_order');
        $order_id = $condition['order_id'] = intval($_POST['order_id']);
        $expect_use_time = isset($_POST['expect_use_time']) ? strtotime(htmlspecialchars($_POST['expect_use_time'])) : 0;
        $condition['store_id'] = $this->store['store_id'];
        $order = $database->field(true)->where($condition)->find();
        if(empty($order)){
            $this->returnCode(1, null, '订单不存在！');
        }
    
        if ($order['is_refund']) {
            $this->returnCode(1, null, '用户正在退款中~！');
        }
        if ($order['paid'] == 0) {
            $this->returnCode(1, null, '订单未支付，不能接单！');
        }
        if ($order['status'] > 0) {
            $this->returnCode(1, null, '该订单已处理，不能更改！');
        }
    
        if ($order['is_pick_in_store'] != 3) {
            $this->returnCode(1, null, '不是快递配送，不能修改配送方式！');
        }
        $d_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
    
        if (in_array($d_shop['deliver_type'], array(2, 5))) {
            $this->returnCode(1, null, '店铺不支持快递以外的配送，不能修改配送方式！');
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
        $return = $this->getDeliverFee($order['store_id']);
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
        $data['expect_use_time'] = $time;
        $data['last_staff'] = $this->staff_session['name'];
        if ($d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3) {
            $data['no_bill_money'] = $delivery_fee;
        }
        if ($database->where($condition)->save($data)) {
            $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
            if ($result['error_code']) {
                D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
                $this->returnCode(1, null, $result['msg']);
                exit;
            }
            $phones = explode(' ', $this->store['phone']);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
            $this->returnCode(0, '已接单');
        } else {
            $this->returnCode(1, null, '接单失败');
        }
    }
    
    /**
     * 快店的快递配送时填写快递单号
     */
    public function getExpress()
    {
        $order_id = intval($_POST['order_id']);
        $order = M('Shop_order')->field(true)->where(array('store_id' => $this->store['store_id'], 'order_id' => $order_id))->find();
        if (empty($order)) $this->returnCode(1, null, '订单信息错误');
        $express_list = D('Express')->get_express_list();
        $data = array('express_id' => $order['express_id'], 'express_number' => $order['express_number']);
        $data['express_list'] = $express_list;
        $distance = getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);
        $data['distance'] = floatval(round($distance / 1000, 2));
        $data['address'] = $order['address'];
        $data['order_id'] = $order['order_id'];
        $this->returnCode(0, $data);
    }
    
    
    /**
     * 打包APP修改快店订单状态的接口
     */
    public function shopOrderEdit()
    {
        if (IS_POST) {
            $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
            $store_id = $this->store['store_id'];
            $status = intval($_POST['status']);
            if ($order = D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
                if ($status == 1 && $order['status'] > 0) {
                    $this->returnCode(1, null, '该单已接，不要重复接单');
                }
                if ($order['status'] == 4 || $order['status'] == 5) {
                    $this->returnCode(1, null, '订单已取消，不能再做其他操作。');
                }
                if ($order['is_refund']) {
                    $this->returnCode(1, null, '用户正在退款中~！');
                }
                if ($order['paid'] == 0 && $status != 5) {
                    $this->returnCode(1, null, '未付款的订单只能进行取消操作。');
                }
                //0未确认，1已确认，2已消费，3已评价，4已退款，5已取消，
                if ($status == 3) {
                    $this->returnCode(1, null, '您不能将该订单改成已评价状态。');
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
                        $this->returnCode(1, null, '不支持线下支付。');
                    }
                }
                
                $data = array('store_uid' => $this->staff_session['id']);
                $data['status'] = $status;
                $data['cancel_type'] = 4;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
                $data['last_staff'] = $this->staff_session['name'];
                if ($order['is_pick_in_store'] == 3) {
                    $data['status'] = $status = 2;
                    $express_id = isset($_POST['express_id']) ? intval($_POST['express_id']) : 0;
                    $express_number = isset($_POST['express_number']) ? htmlspecialchars($_POST['express_number']) : 0;
                    if ($status == 2 && (empty($express_id) || empty($express_number))) $this->returnCode(1, null, '快递公司和快递单号都不能为空。');
 
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
                        $this->returnCode(0, "更新成功");
                    } else {
                        $this->returnCode(1, null, "更新失败，稍后重试");
                    }
                } else {
                    $supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
    
                    if ($order['is_pick_in_store'] == 0 && $status == 2 && $supply && $supply['uid']) {//平台配送，当配送员接单后店员就不能把订单修改成已消费状态
                        $this->returnCode(1, null, '您不能将该订单改成已消费状态。');
                    }
                    if ($status == 0 && $order['status'] == 1 && $order['is_pick_in_store'] < 2) {
                        if ($supply && $supply['status'] > 1) {
                            $this->returnCode(1, null, '当前订单已进入了配送状态，不能修改成未确认状态。');
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
                                D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
                                $this->returnCode(1, null, $result['msg']);
                                exit;
                            }
                            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
                        }
                        $this->returnCode(0, "更新成功");
                    } else {
                        $this->returnCode(1, null, "更新失败，稍后重试");
                    }
                }
            } else {
                $this->returnCode(1, null, '不合法的请求');
            }
        }
    }

    /**
     * 检查快店是否有库存报警
     */
    public function checkShopGoodsStock()
    {
        if ($now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find()) {
            if ($result = D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type'])) {
                $this->returnCode(0, 1);
            } else {
                $this->returnCode(0, 0);
            }
        } else {
            //$this->returnCode(1, null, '不合法的请求');
        }
    }
    /**
     * 快店商品库存报警列表
     */
    public function shopGoodsStock()
    {
        if ($now_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find()) {
            $goods_list = D('Shop_goods')->check_stock_list($this->store['store_id'], $now_shop['stock_type']);
            $data = array('count' => count($goods_list), 'goods_list' => $goods_list);
            $this->returnCode(0, $data);
        } else {
            $this->returnCode(1, null, '不合法的请求');
        }
    }
    

    /**
     * 自提点列表
     */
    public function getPickAddress()
    {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
        if(empty($order)){
            $this->returnCode(1, null, '订单不存在！');
        }
        $pick_addr = D('Pick_address')->get_pick_addr_by_merid($this->store['mer_id'], true);
        foreach ($pick_addr as &$v) {
            $v['range'] = getRange(getDistance($v['lat'], $v['long'], $order['lat'], $order['lng']));
        }
        $this->returnCode(0, array('order_id' => $order_id, 'pick_list' => $pick_addr));
    }
    
    /**
     * 保存分配到自提点
     */
    public function pick()
    {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
        if(empty($order)){
            $this->returnCode(1, null, '订单不存在！');
        }
        $pick_order = D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->find();
        
        if ($order['status'] == 4 || $order['status'] == 5) {
            $this->returnCode(1, null, '订单已取消，不能接单！');
            exit;
        }
        if ($order['is_refund']) {
            $this->returnCode(1, null, '用户正在退款中~！');
            exit;
        }
        if ($order['paid'] == 0) {
            $this->returnCode(1, null, '订单未支付，不能接单！');
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
            $this->returnCode(1, null, '请选择自提点');
        }
        
        if (empty($pick_order)) {
            D('Pick_order')->add(array('store_id' => $order['store_id'], 'order_id' => $order['order_id'], 'type' => $type, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
            D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 7, 'order_status' => 1, 'pick_id' => $pick_id));
            $phones = explode(' ', $this->store['phone']);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));//分配到自提点
            $this->returnCode(0, 'SUCCESS');
        } else {
            $this->returnCode(1, null, '不要重复分配');
        }
    }
    //	快店列表
    public function shopList()
    {
    	$this->check_shop();
        $pay_types = array('balance' => '余额支付', 'alipay' => '支付宝', 'tenpay' => '财付通', 'yeepay' => '易宝支付', 'allinpay' => '通联支付', 'chinabank' => '网银在线', 'weixin' => '微信支付', 'baidu' => '百度钱包', 'unionpay' => '银联支付', 'offline' => '货到付款');
        $store_id = intval($this->store['store_id']);
        $where = array('mer_id' => $this->store['mer_id'], 'store_id' => $store_id);
        
        $stauts = isset($_POST['st']) ? intval(trim($_POST['st'])) : -1;//订单状态
        $ftype = isset($_POST['ft']) ? trim($_POST['ft']) : '';//搜索关键词类型
        $fvalue = isset($_POST['fv']) ? trim(htmlspecialchars($_POST['fv'])) : '';//搜索关键词
        $order_id = $_POST['order_id'];
        
        $pay_type = isset($_POST['pay_type']) ? htmlspecialchars(trim($_POST['pay_type'])) : -2;//支付类型
        $order_from = isset($_POST['order_from']) ? intval(trim($_POST['order_from'])) : -2;//订单来源
        
        $stime = isset($_POST['stime']) ? trim(htmlspecialchars($_POST['stime'])) : '';//搜索开始时间
        $etime = isset($_POST['etime']) ? trim(htmlspecialchars($_POST['etime'])) : '';//搜索结束时间
        if ($stime && $etime) {
            $where['create_time'] = array(array('gt', strtotime($stime . ' 00:00:01')), array('lt', strtotime($etime . ' 23:59:59'))) ;
        }
        
        if ($stauts != -1 && $stauts != -2) {
            $where['status'] = $stauts;
        }
        if ($pay_type != -2) {
            if ($pay_type == 'balance') {
                $where['paid'] = 1;
                $where['pay_type'] = '';
            } else {
                $where['pay_type'] = $pay_type;
            }
        }
        if ($order_from != -2) {
            $where['order_from'] = $order_from;
        }
        
        switch ($ftype) {
            case 'oid': //订单id
                $fvalue && $where['real_orderid'] = array('like', "%$fvalue%");
                break;
            case 'xm':  //下单人姓名
                $fvalue && $where['username'] = array('like', "%$fvalue%");
                break;
            case 'dh':  //下单人电话
                $fvalue && $where['userphone'] = array('like', "%$fvalue%");
                break;
            case 'mps': //消费码
                $fvalue && $where['orderid'] = $fvalue;
                break;
            default:
                break;
        }
        
        $count = M('Shop_order')->where($where)->count();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $_GET['page'] = $page;
        $shop_order	= D('Shop_order')->get_order_list($where, 'order_id DESC');
        if ($shop_order['order_list']) {
            foreach ($shop_order['order_list'] as $k => $v) {
                if ($v['pay_type_str'] == '未支付') {
                    $pay_status	=	'';
                } else {
                    if (empty($v['third_id']) && ($v['pay_type'] == 'offline')) {
                        $pay_status = '线下未支付';
                    } elseif($v['paid'] == 0) {
                        $pay_status = '未支付';
                    } else {
                        $pay_status = '已支付';
                    }
                }
                
                $arr['shop_order'][$k] = array(
                    'order_id' => $v['order_id'],
                    'username' => $v['username'],
                    'userphone' => $v['userphone'],
                    'address' => $v['address'],
                    'create_time' => date('Y-m-d H:i:s',$v['create_time']),
                    'price' => $v['price'],
                    'pay_type_str' => $v['pay_type_str'],
                    'status' => $v['status'],
                    'paid' => $v['paid'],
                    'is_pick_in_store' => $v['is_pick_in_store'],
                    'pay_status' => $pay_status,
                    'total_price' => floatval($v['total_price']),
                    'change_price' => floatval($v['change_price']),
                    'price' => floatval($v['price']),
                    'change_price_reason' => $v['change_price_reason'],
                    'order_from_name' => $this->order_froms[$v['order_from']],
                );
            }
        } else {
			$arr['shop_order'] = array();
        }
        $arr['page'] = ceil($count / 10);;
        $arr['count'] = $count;
        if ($page == 1) {
           $shop = M('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
           $arr['deliver_type'] = $shop['deliver_type'];
           $arr['is_open_pick'] = $shop['is_open_pick'];
           $arr['is_change'] = $this->staff_session['is_change'];
        }
        $arr['pay_type'] = $pay_types;
        $arr['order_from'] = $this->order_froms;
        $arr['status_list'] = D('Shop_order')->status_list;
        $this->returnCode(0,$arr);
    }
    

    /**
     * 快店商品的销量统计
     */
    public function statistics()
    {
        $begin_time = isset($_POST['begin_time']) ? strtotime($_POST['begin_time'] . ' 00:00:01') : 0;
        $end_time = isset($_POST['end_time']) ? strtotime($_POST['end_time'] . ' 23:59:59') : 0;
        $arr = array();
        if ($end_time && $begin_time) {
            $data = array('store_id' => $this->store['store_id']);
            $data['begin_time'] = $begin_time;
            $data['end_time'] = $end_time;
            $arr = D('Shop_order')->order_count($data);
        }
        $this->returnCode(0, $arr);
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
				$this->returnCode(1000,array(),'没有会员卡信息');
			} else {
				$return = array('name' => $user['truename'] ? $user['truename'] : $user['nickname'], 'sex' => $user['sex'] == 1 ? '男' : '女', 'card_id' => $card['id'], 'phone' => $user['phone']);
				$return['card_money'] = $card['card_money'] + $card['card_money_give'];
				$return['card_score'] = $card['card_score'];
				$return['physical_id'] = $card['physical_id'];
				$return['uid'] = $user['uid'];
				$return['discount'] = $card_source['discount'];
				
				$return['card_new'] = D('Card_new')->get_use_coupon_by_params($user['uid'], $this->store['mer_id'], 'shop');
				if(empty($return['card_new'])){
					$return['card_can_had'] = D('Card_new_coupon')->get_can_had_coupon_by_params($user['uid'], $this->store['mer_id'], 'shop');
				}
				$this->returnCode(0,$return);
			}
		} else {
			if ($card = D('Card_userlist')->field(true)->where(array('id' => $key, 'mer_id' => $this->store['mer_id']))->find()) {
				$card_source = M('Card_new')->where(array('mer_id' => $this->store['mer_id'], 'status' => 1))->find();
				if (empty($card_source)) {
					$this->returnCode(1000,array(),'没有会员卡信息');
				}
				if ($user = D('User')->field(true)->where(array('uid' => $card['uid']))->find()) {
					$return = array('name' => $user['truename'] ? $user['truename'] : $user['nickname'], 'sex' => $user['sex'] == 1 ? '男' : '女', 'card_id' => $card['id'], 'phone' => $user['phone']);
					$return['card_money'] = $card['card_money'] + $card['card_money_give'];
					$return['card_score'] = $card['card_score'];
					$return['physical_id'] = $card['physical_id'];
					$return['uid'] = $user['uid'];
					$return['discount'] = $card_source['discount'];
					
					$return['card_new'] = D('Card_new')->get_use_coupon_by_params($user['uid'], $this->store['mer_id'], 'shop');
					if(empty($return['card_new'])){
						$return['card_can_had'] = D('Card_new_coupon')->get_can_had_coupon_by_params($user['uid'], $this->store['mer_id'], 'shop');
					}
					$this->returnCode(0,$return);
				} else {
					$this->returnCode(1000,array(),'此卡找不到相应的用户信息');
				}
			} else {
				$this->returnCode(1000,array(),'没有会员卡信息');
			}
		}
	}
	public function ajax_shop_goods()
	{
		$is_refresh = isset($_POST['refresh']) ? intval($_POST['refresh']) : 1;
		$product_list = D('Shop_goods')->get_list($_POST['store_id'], $is_refresh);
		if ($product_list) {
			$this->returnCode(0,$product_list);
		} else {
			$this->returnCode(1000,array(),'暂无商品');
		}
	}
	public function shop_order_save()
	{
		$data = isset($_POST['data']) ? ($_POST['data']) : '';
		$uid = isset($data['card_data']['uid']) && $data['card_data']['uid'] ? intval($data['card_data']['uid']) : 0;
		$store_id = $this->staff_session['store_id'];
		$return = D('Shop_goods')->checkCart($store_id, $uid, $data['goods_data'], 2);
		if ($return['error_code']) $this->returnCode(1000,array(),$return['msg']);
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
			$order_data['discount_detail'] = '';//优惠详情
			if ($return['price'] - $return['store_discount_money'] > 0) {
				$order_data['discount_detail'] = '店铺折扣优惠：' . floatval($return['price'] - $return['store_discount_money']);
			}
			if ($return['store_discount_money'] - $return['vip_discount_money'] > 0) {
				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']) : 'VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']);
			}
			if ($return['sys_first_reduce']> 0) {
				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台首单减：' . $return['sys_first_reduce'] : '平台首单减：' . $return['sys_first_reduce'];
			}
			if ($return['sys_full_reduce'] > 0) {
				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台满减：' . $return['sys_full_reduce'] : '平台满减：' . $return['sys_full_reduce'];
			}
			if ($return['sto_first_reduce']> 0) {
				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺首单减：' . $return['sto_first_reduce'] : '店铺首单减：' . $return['sto_first_reduce'];
			}
			if ($return['sto_full_reduce'] > 0) {
				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺满减：' . $return['sto_full_reduce'] : '店铺满减：' . $return['sto_full_reduce'];
			}
			$order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'
			
			if ($order_id = D('Shop_order')->add($order_data)) {
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
				$detail_obj = D('Shop_order_detail');
				$goods_obj = D("Shop_goods");
				foreach ($return['goods'] as $grow) {
					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time(),'extra_price'=>$grow['extra_price']);
					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
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
				$data = array('real_orderid' => $order_data['real_orderid'], 'order_id' => $order_id, 'price' => $order_data['price'], 'pay_qrcode_url' => $pay_qrcode_url);
				$this->returnCode(0, $data);
			} else {
				$this->returnCode(1000,array(),'订单保存失败');
			}
		} else {
			$this->returnCode(1000,array(),'不合法的提交');
		}
	}
	public function get_pay_method(){
		$return['offline_pay_list'] = M('Store_pay')->where(array('store_id' => $this->staff_session['store_id']))->order('`id` ASC')->select();
		$return['open_alipay'] = $this->config['arrival_alipay_open'] ? $this->config['arrival_alipay_open'] : 0;
		$this->returnCode(0,$return);
	}
	public function shop_change_price()
	{
		if (!empty($this->staff_session['is_change'])) {
			$this->returnCode(1000,array(),'您没有修改价格的权限!');
		}
		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
		$where = array('order_id' => $order_id, 'store_id' => $this->staff_session['store_id']);
		$order = D('Shop_order')->get_order_detail($where);
		
		if (empty($order)) {
			$this->returnCode(1000,array(),'不存在的订单信息!');
		}
		if (!($order['paid'] == 0 && $order['status'] == 0)) {
			$this->returnCode(1000,array(),'该订单已经不能修改支付价格了!');
		}
		$change_price = isset($_POST['change_price']) ? floatval($_POST['change_price']) : $order['price'];
		if ($change_price == $order['price']) {
			$this->returnCode(1000,array(),'您没有修改价格!');
		}
		if ($change_price <= 0) {
			$this->returnCode(1000,array(),'您不能把价格改成小于等于0的数!');
		}
		$data = array('price' => $change_price);
		$data['last_staff'] = $this->staff_session['name'];
		$data['last_time'] = $_SERVER['REQUEST_TIME'];
		if (floatval($order['change_price']) == 0) {
			$data['change_price'] = $order['price'];
		}
		$data['change_price_reason'] = $_POST['change_price_reason'];
		if (D('Shop_order')->where($where)->save($data)) {
			$phones = explode(' ', $this->staff_session['phone']);
			D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 30, 'name' => $this->staff_session['name'], 'phone' => $phones[0], 'note' => $change_price));
			$this->returnCode(0,'修改成功');
		} else {
			$this->returnCode(1000,array(),'修改出错，稍后重试！');
		}
	}
	public function shop_arrival_check()
	{
		$now_order = M('Shop_order')->where(array('order_id'=>$_POST['order_id']))->find();
		if ($now_order['paid']) {
			$this->returnCode(0,'支付成功');
		} else {
			$this->returnCode(1000,'支付失败');
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

		if (empty($now_order)) $this->returnCode(1000,array(),'订单信息错误');
		
		//检查会员卡的信息已经余额  TODO
		$discount = 10;
		$data['merchant_balance'] = 0;//商家余额
		$data['card_give_money'] = 0;//会员卡赠送余额
		$data['card_id'] = 0;//优惠券ID
		$data['card_price'] = 0;//优惠券的金额
		$coupon_price = 0;
		if ($card = D('Card_userlist')->field(true)->where(array('id' => $card_id, 'uid' => $uid, 'mer_id' => $this->store['mer_id']))->find()) {
			$card_source = M('Card_new')->where(array('mer_id' => $this->store['mer_id'], 'status' => 1))->find();
			if (empty($card_source)) $this->returnCode(1000,array(),'会员卡不可用');
			$discount = floatval($card_source['discount']);
			$user_card_total_moeny = $card['card_money'] + $card['card_money_give'];
			if ($user_card_total_moeny < $card_money) {
				$this->returnCode(1000,array(),'会员卡余额不足');
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
		// dump($this->store);
		// dump($data);
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
		$data['last_staff'] = $this->staff_session['name'];
		
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
			
			import('ORG.Net.Http');
			$http = new Http();
			$session_key = $table . '_order_userpaying_'.$order_id;
			if($_SESSION[$session_key]){
				$param = array();
				$param['appid'] = $this->config['pay_weixin_appid'];
				$param['mch_id'] = $this->config['pay_weixin_mchid'];
				$param['nonce_str'] = $this->createNoncestr();
				$param['out_trade_no'] = $table . '_' . $now_order['order_id'];
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
				$param['sign'] = $this->getWxSign($param);
		
				$return = Http::curlPostXml('https://api.mch.weixin.qq.com/pay/micropay', $this->arrayToXml($param));
			}
			if ($return['return_code'] == 'FAIL') {
				$this->returnCode(1000,array(),'支付失败！微信返回：'. $return['return_msg']);
			}
			if ($return['result_code'] == 'FAIL') {
				if ($return['err_code'] == 'USERPAYING') {
					$_SESSION[$session_key] = '1';
					$this->returnCode(1000,array(),'用户支付中，需要输入密码！请询问用户输入完成后，再点击“确认支付”');
				}
				$this->returnCode(1000,array(),'支付失败！微信返回：'.$return['err_code_des']);
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
			$this->returnCode(0,'支付成功');
		}else{
			$this->returnCode(1000,array(),'支付失败！请联系管理员处理。');
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
			$this->returnCode(0,'支付成功');
		}else{
			$this->returnCode(1000,array(),'支付失败！请联系管理员处理。');
		}
	}
	
	
	public function arrival_alipay_pay($order_id, $now_order, $table_name, $data = array())
	{
		if (empty($this->config['arrival_alipay_open'])) {
			$this->returnCode(1000,array(),'平台未开启支付宝收银');
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
			$this->returnCode(1000,array(),'支付宝收银商户密钥错误，请联系管理员解决。');
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
				$this->returnCode(0,'支付成功');
			} else {
				$this->returnCode(1000,array(),'支付失败！请联系管理员处理。');
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
					$this->shop_notice($now_order);
					$this->returnCode(0,'支付成功');
				} else {
					$this->returnCode(1000,array(),'支付失败！请联系管理员处理。');
				}
			} else {
				$this->returnCode(1000,array(),'支付失败！支付宝返回：'.$returnArr['alipay_trade_pay_response']['sub_msg']);
			}
		}
	}
	
    
    public function appointList()
    {
        $key = isset($_POST['key']) ? htmlspecialchars($_POST['key']) : '';
        $searchType = isset($_POST['searchtype']) ? htmlspecialchars($_POST['searchtype']) : '';
        $stime = isset($_POST['stime']) ? htmlspecialchars($_POST['stime']) : '';
        $etime = isset($_POST['etime']) ? htmlspecialchars($_POST['etime']) : '';
        $payType = isset($_POST['pay_type']) && $_POST['pay_type'] ? htmlspecialchars($_POST['pay_type']) : '';
        
        $where = " `o`.`store_id`={$this->store['store_id']}";
        if ($key) {
            switch ($searchType) {
                case 'order_id':
                    $where .= " AND `o`.`order_id`={$key}";
                    break;
                case 'orderid':
                    $tmp_result = M('Tmp_orderid')->where(array('orderid'=>$where['orderid']))->find();
                    $where .= " AND `o`.`order_id`={$tmp_result['order_id']}";
                    break;
                case 'name':
                    $where .= " AND `u`.`username`='{$key}'";
                    break;
                case 'phone':
                    $where .= " AND `u`.`phone`='{$key}'";
                    break;
                case 'third_id':
                    $where .= " AND `o`.`third_id`='{$key}'";
                    break;
            }
        }

        if ($payType) {
            if ($payType == 'balance') {
                $where .= " AND (`o`.`balance_pay`<>0 OR `o`.`merchant_balance` <> 0 OR `o`.`product_merchant_balance` <> 0 OR `o`.`product_balance_pay` <> 0 )";
            } else {
                $where .= " AND `o`.`pay_type`='{$payType}'";
            }
        }
        
        if ($stime && $etime) {
            $where .= ' AND `o`.order_time >=' . strtotime($stime . ' 00:00:00') . ' AND `o`.order_time <=' . strtotime($etime . ' 23:59:59');
        }
        
        $sqlCount = " SELECT count(1) as cnt FROM " . C('DB_PREFIX') . "appoint AS a RIGHT JOIN " . C('DB_PREFIX') . "appoint_order AS o ON o.appoint_id=a.appoint_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON o.uid=u.uid WHERE {$where}";
        
        $res = D()->query($sqlCount);
       
        $count = isset($res[0]['cnt']) ? intval($res[0]['cnt']) : 0;
        
        import('@.ORG.merchant_page');
        $_GET['page'] = $_POST['page'];
        $page = new Page($count, 10);
        
        $sql = " SELECT `o`.*, `a`.`appoint_name`, `a`.`appoint_price`, `o`.`mer_id`, `u`.`uid`, `u`.`nickname`, `u`.`phone` FROM " . C('DB_PREFIX') . "appoint AS a RIGHT JOIN " . C('DB_PREFIX') . "appoint_order AS o ON o.appoint_id=a.appoint_id LEFT JOIN " . C('DB_PREFIX') . "user AS u ON o.uid=u.uid WHERE {$where} ORDER BY `o`.`order_id` DESC LIMIT {$page->firstRow}, {$page->listRows}";
        
        $orderList = D()->query($sql);
        foreach ($orderList as &$val) {
            $val['store_name'] = $this->store['name'];
            $val['store_adress'] = $this->store['adress'];
            $val['order_time'] = date('Y-m-d H:i:s', $val['order_time']);
        }
        $arr = array('order_list' => $orderList, 'pay_list' => D('Config')->get_pay_method('', '', 0));
        $arr['page'] = ceil($count / 10);;
        $arr['count'] = $count;
        $this->returnCode(0, $arr);
    }
    


    /*验证预约服务*/
    public function appointVerify()
    {
        $database_order = D('Appoint_order');
        $database_appoint_visit_order_info = D('Appoint_visit_order_info');
        $database_merchant_workers = D('Merchant_workers');
        $database_merchant_store = D('Merchant_store');
    
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        
        $where = array('store_id' => $this->store['store_id']);
        $where['order_id'] = $order_id;
        $now_order = $database_order->field(true)->where($where)->find();

        if(empty($now_order)){
            $this->returnCode(1, null, '当前订单不存在！');
        } else {
            $fields['store_id'] = $this->staff_session['store_id'];
            $fields['last_staff'] = $this->staff_session['name'];
            $fields['last_time'] = time();
            $fields['service_status'] = 1;
            $fields['paid'] = 1;
            if($database_order->where($where)->data($fields)->save()){
                $Map['appoint_order_id'] =  $order_id;
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
                    $sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $now_order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
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
    
                $this->returnCode(0);
            } else {
                $this->returnCode(1, null, '验证失败！请重试。');
            }
        }
    }


    /*订单详情*/
    public function appointDetail()
    {
        $where = array('store_id' => $this->store['store_id']);
        $where['order_id'] = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    
    
        $orderInfo = D('Appoint_order')->field(true)->where($where)->find();
        if (empty($orderInfo)) {
            $this->returnCode(1, null, '订单信息不存在');
        }
    
        $orderInfo['order_time'] = $orderInfo['order_time'] ? date('Y-m-d H:i:s', $orderInfo['order_time']) : 0;
        $orderInfo['user_pay_time'] = $orderInfo['user_pay_time'] ? date('Y-m-d H:i:s', $orderInfo['user_pay_time']) : 0;
        
        $orderInfo['user_name'] = '';
        $orderInfo['user_phone'] = '';
        if ($userInfo = D('User')->field('`uid`, `phone`, `nickname`')->where(array('uid' => $orderInfo['uid']))->find()) {
            $orderInfo['user_name'] = $userInfo['nickname'];
            $orderInfo['user_phone'] = $userInfo['phone'];
        }
    
       
        $orderInfo['appoint_name'] = $appointInfo['appoint_name'];
        if ($appointInfo = D('Appoint')->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->where(array('appoint_id' => $orderInfo['appoint_id']))->find()) {
            $orderInfo['appoint_name'] = $appointInfo['appoint_name'];
        }

        $cue_info = unserialize($orderInfo['cue_field']);
        $cue_list = array();
        foreach ($cue_info as $key => $val) {
            if (!empty($cue_info[$key]['value'])) {
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
        $orderInfo['product_detail'] = '';
        $product_detail = D('Appoint_product')->field(true)->where(array('id' => $orderInfo['product_id']))->find();;
        if ($product_detail['status']) {
            $orderInfo['product_detail'] = $product_detail['detail'];
        }

        $service_address_info = array();
        $orderInfo['worker_name'] = '';
        $orderInfo['worker_phone'] = '';
        $orderInfo['worker_time'] = 0;
        if ($orderInfo['appoint_type'] == 1) {
            if ($appoint_visit_order_info = D('Appoint_visit_order_info')->where(array('appoint_order_id' => $orderInfo['order_id'], 'uid' => $orderInfo['uid']))->find()) {
                $service_address = unserialize($appoint_visit_order_info['service_address']);
                foreach ($service_address as $key => $val) {
                    if (!empty($service_address[$key]['value'])) {
                        $service_address_info[$key]['name'] = $val['name'];
                        $service_address_info[$key]['value'] = $val['value'];
                        $service_address_info[$key]['type'] = $val['type'];
                        if($appoint_visit_order_info['type'] == 2){
                            $service_address_info[$key]['long'] = $val['long'];
                            $service_address_info[$key]['lat'] = $val['lat'];
                            $service_address_info[$key]['address'] = $val['address'];
                        }
                    }
                }
                if ($merchant_workers_info = M('Merchant_workers')->field(array('merchant_worker_id', 'name', 'mobile'))->where(array('merchant_worker_id' => $appoint_visit_order_info['merchant_worker_id'])) ->find()) {
                    $orderInfo['worker_name'] = $merchant_workers_info['name'];
                    $orderInfo['worker_phone'] = $merchant_workers_info['mobile'];
                    $orderInfo['worker_time'] = date('Y-m-d H:i:s', $appoint_visit_order_info['add_time']);
                }
            }
        }
    
        if($service_address_info){
            $cue_list = $service_address_info;
        }
        
        $orderInfo['cue_list'] = $cue_list ?: '';
        $this->returnCode(0, $orderInfo);
        
    }
}