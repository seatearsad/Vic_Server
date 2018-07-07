<?php
class WapMerchantAction extends BaseAction {
	protected $merchant_session;
    protected $store;
    protected $merid;

    protected function _initialize() {
        parent::_initialize();
        $ticket = I('ticket', false);
		if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $condition_merchant['mer_id'] = $info['uid'];
            }
            $database_merchant = D('Merchant');
            $this->merchant_session	=	$database_merchant->field(true)->where($condition_merchant)->find();
            $this->merid =  $info['uid'];
        }
    }
    # 商户中心获取信息
    public function indexshow(){
		$arr['site_phone']		=	$this->config['site_phone'];		//电话
    	$arr['have_group_name']	=	$this->config['group_alias_name'];	//团购
    	$arr['have_meal_name']	=	$this->config['meal_alias_name'];	//餐饮
    	$arr['have_shop_name']	=	$this->config['shop_alias_name'];	//快店
    	$arr['have_appoint_name'] =	$this->config['appoint_alias_name'];//预约
    	$arr['discount_prompt']	=	'请填写0~100之间的整数，0和100都是表示无折扣，98表示9.8折';
    	$arr['sort_prompt']	=	'默认添加顺序排序！手动调值，数值越大，排序越前';
		$arr['out_img']		=	$this->config['site_url'].'/static/appapi/merchant/out.png';			//退出
		$arr['more_img']	=	$this->config['site_url'].'/static/appapi/merchant/more.png';			//更多
		$arr['morecolor_img']	=	$this->config['site_url'].'/static/appapi/merchant/morecolor.png';	//更多-彩
		$arr['closer_img']	=	$this->config['site_url'].'/static/appapi/merchant/close.png';			//关闭
		$arr['app_android_shop']	=	isset($this->config['app_android_shop'])?$this->config['app_android_shop']:'';//店员中心APP包名
		$arr['app_ios_shop']	=	isset($this->config['app_ios_shop'])?$this->config['app_ios_shop']:'';//店员中心APP包名
		// $arr['time'] = time();


    	$this->returnCode(0,$arr);
    }
	# 登录
	public function login() {
		$ticket = I('ticket', false);
		$database_merchant = D('Merchant');
		if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if ($info) {
                $condition_merchant['mer_id'] = $info['uid'];
            }
        }else{
        	$account	=	I('account');
			$condition_merchant['account'] = trim($account);
        }
		$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if (empty($now_merchant)) {
			$this->returnCode('20140001');
		}
		if(empty($ticket)){
			$pwd	=	I('pwd');
			$pwd = md5(trim($pwd));
			if ($pwd != $now_merchant['pwd']) {
				$this->returnCode('20140002');
			}
			$aTicket = ticket::create($now_merchant['mer_id'], $this->DEVICE_ID, true);
			$ticket	=	$aTicket['ticket'];
		}

		if ($now_merchant['status'] == 0) {
			$this->returnCode('20140003');
		} else if ($now_merchant['status'] == 2) {
			$this->returnCode('20140004');
		}
		$arr	=	array(
			'mer_id'	=>	$now_merchant['mer_id'],
			'name'	=>	$now_merchant['name'],
			'phone'	=>	$now_merchant['phone'],
			'email'	=>	$now_merchant['email'],
			'txt_info'	=>	$now_merchant['txt_info'],
		);
        $return = array(
            'ticket'=>	$ticket,
            'user'	=>	$arr,
        );

		$data_merchant['mer_id'] = $now_merchant['mer_id'];
		$data_merchant['last_ip'] = get_client_ip(1);
		$data_merchant['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_merchant['login_count'] = $now_merchant['login_count'] + 1;
		if ($database_merchant->data($data_merchant)->save()) {
			$now_merchant['login_count'] += 1;
			if (!empty($now_merchant['last_ip'])) {
				import('ORG.Net.IpLocation');
				$IpLocation = new IpLocation();
				$last_location = $IpLocation->getlocation(long2ip($now_merchant['last_ip']));
				$now_merchant['last']['country'] = iconv('GBK', 'UTF-8', $last_location['country']);
				$now_merchant['last']['area'] = iconv('GBK', 'UTF-8', $last_location['area']);
			}
			$this->returnCode(0,$return);
		} else {
			$this->returnCode('20140005');
		}
	}
	# 商家入驻
	public function mer_reg() {
		//帐号
		$database_merchant = D('Merchant');
		$arr['account'] =	$condition_merchant['account'] = I('account');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140006');
		}
		//名称
		$arr['name'] = $condition_merchant['name'] = I('mername');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140007');
		}
		//邮箱
		$arr['email'] =	$condition_merchant['email'] = I('email');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140008');
		}
		//手机号
		$arr['phone'] =	$condition_merchant['phone'] = I('phone');
		$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
		if (!empty($now_merchant)) {
			$this->returnCode('20140009');
		}
		$config = D('Config')->get_config();
		$arr['mer_id'] = null;
		if ($config['merchant_verify']) {
			$arr['status'] = 2;
		} else {
			$arr['status'] = 1;
		}
		$pwd	=	I('pwd');
		$city_id	=	I('city_id');
		$area_id	=	I('area_id');
		$arr['pwd'] = md5($pwd);
		$arr['reg_ip'] = get_client_ip(1);
		$arr['reg_time'] = $_SERVER['REQUEST_TIME'];
		$arr['city_id'] = $city_id;
		$arr['area_id'] = $area_id;
		$arr['login_count'] = 0;
		$arr['reg_from'] = 0;
		if ($insert_id = $database_merchant->data($arr)->add()) {
			M('Merchant_score')->add(array('parent_id' => $insert_id, 'type' => 1));
			if ($config['merchant_verify']) {
				$this->returnCode(0,array('type'=>2));	//注册成功,请耐心等待审核或联系工作人员审核。
			} else {
				$this->returnCode(0,array('type'=>1));
			}
		} else {
			$this->returnCode('20140010');
		}
	}
	# 商家后台管理首页
	public function index() {
		$this->ticket();
		$allincomecount = $this->getallincomecount();
		$wap_MerchantAd = D('Adver')->get_adver_by_key('wap_Merchant', 7);
		if($wap_MerchantAd){
			foreach($wap_MerchantAd as $v){
				$Ad[]	=	array(
					'url'	=>	$v['url'],
					'pic'	=>	$v['pic'],
				);
			}
		}
	    if (empty($this->merchant_session['qrcode_id'])) {
	        $qrcode_return = D('Recognition')->get_new_qrcode('merchant', $this->merchant_session['mer_id']);
	    } else {
	        $qrcode_return = D('Recognition')->get_qrcode($this->merchant_session['qrcode_id']);
	    }
		$number	=	$this->getallordercount();
		$arr	=	array(
			'wap_merchantAd'	=>	$wap_MerchantAd==false?array():$Ad,		//广告牌
			'qrcodeinfo'		=>	isset($qrcode_return['qrcode'])?$qrcode_return['qrcode']:'',	//二维码
			'count_number'		=>	array(
				'allincomecount'	=>	(int)$allincomecount,					//收入总数
				'webviwe'			=>	(int)$this->merchant_session['hits'],	//浏览总数
				'allordercount'		=>	(int)$number['allordercount'],			//订单总数
				'monthordercount'	=>	(int)$number['monthordercount'],		//本月订单总数
				'todayordercount'	=>	(int)$number['todayordercount'],		//本日订单总数
				'fans_count'		=>	(int)$number['fans_count'],				//粉丝总数
				'logo'				=>	$this->config['site_merchant_logo'],	//商户logo
//				'appoint_page_row'	=>	isset($this->config['appoint_page_row']) ? 1 : 0,	预约判断
			),
		);
		$arr['type_name'] = $this->get_alias_c_name();         //业务类型.
		$config = M('Appapi_app_config')->select();
		foreach($config as $v){
			if($v['var']=='mer_android_v'){
				$arr['android_version'] = $v['value'];
			}elseif($v['var']=='mer_android_url'){
				$arr['android_downurl'] = $v['value'];
			}elseif($v['var']=='mer_android_vcode'){
				$arr['android_version_code'] = $v['value'];
			}elseif($v['var']=='mer_android_vdesc'){
				$arr['android_version_version_desc'] = $v['value'];
			}

		}
		$this->returnCode(0,$arr);
	}
	# 商家后台管理左划
	public function leftMenu(){
		$mer_id		=	$this->ticket();
		$appoint_row	=	isset($this->config['appoint_page_row']) ? 1 : 0;	//预约判断
		$database_merchant = D('Merchant');
		$merchant 	= $database_merchant->field('menus')->where(array('mer_id'=>$mer_id))->find();
		$mer		=	array(
			'name'	=>	$this->config['merchant_alias_name'].'管理',	//快店名
			'label'	=>	'merchant',
			'img'	=>	$this->config['site_url'].'/static/appapi/merchant/merchant.png',
			'img_color'	=>	$this->config['site_url'].'/static/appapi/merchant/merchantcolor.png',
			'so_list'	=>	array(
				//array(
//					'name'	=>	$this->config['merchant_alias_name'].'列表',
//					'label'	=>	'list',
//				),
//				array(
//					'name'	=>	$this->config['merchant_alias_name'].'订单',
//					'label'	=>	'order',
//				),
			),
		);
		$group		=	array(
			'name'	=>	$this->config['group_alias_name'].'管理',	//团购名
			'label'	=>	'group',
			'img'	=>	$this->config['site_url'].'/static/appapi/merchant/group.png',
			'img_color'	=>	$this->config['site_url'].'/static/appapi/merchant/groupcolor.png',
			'so_list'	=>	array(),
		);
		$show	=	array(
			'name'	=>	$this->config['shop_alias_name'].'管理',	//快店名
			'label'	=>	'shop',
			'img'	=>	$this->config['site_url'].'/static/appapi/merchant/merchant.png',
			'img_color'	=>	$this->config['site_url'].'/static/appapi/merchant/merchantcolor.png',
			'so_list'	=>	array(),
		);
    	$meal		=	array(
			'name'		=>	$this->config['meal_alias_name'].'管理',	//餐饮名
			'label'		=>	'meal',
			'img'		=>	$this->config['site_url'].'/static/appapi/merchant/meal.png',
			'img_color'	=>	$this->config['site_url'].'/static/appapi/merchant/mealcolor.png',
			'so_list'	=>	array(),
    	);
    	$appoint	=	array(
			'name'		=>	$this->config['appoint_alias_name'].'管理',//预约名
			'label'		=>	'appoint',
			'img'		=>	$this->config['site_url'].'/static/appapi/merchant/appoint.png',
			'img_color'	=>	$this->config['site_url'].'/static/appapi/merchant/appointcolor.png',
			'so_list'	=>	array(),
    	);
    	$staff		=	array(
			'name'		=>	'店员管理',				//店员名
			'label'		=>	'staff',
			'img'		=>	$this->config['site_url'].'/static/appapi/merchant/staff.png',
			'img_color'	=>	$this->config['site_url'].'/static/appapi/merchant/staffcolor.png',
			'so_list'	=>	array(),
    	);
    	$hardware	=	array(
			'name'		=>	'打印机管理',			//打印机
			'label'		=>	'hardware',
			'img'		=>	$this->config['site_url'].'/static/appapi/merchant/hardware.png',
			'img_color'	=>	$this->config['site_url'].'/static/appapi/merchant/hardwarecolor.png',
			'so_list'	=>	array(),
    	);
    	if($merchant){
    		$arr	=	array();
    		$arr[]	=	$mer;	//店铺
			if(stripos($merchant , ',8,')!==FALSE){
				$arr[]	=	$group;	//团购
			}
			if(stripos($merchant , ',108,')!==FALSE){
				$arr[]	=	$show;	//快店
			}
			if(stripos($merchant , ',6,')!==FALSE){
				$arr[]	=	$meal;	//餐饮
			}
			if(stripos($merchant , ',60,')!==FALSE){
				$arr[]	=	$appoint;	//预约
			}
			if(stripos($merchant , ',47,')!==FALSE){
				$arr[]	=	$staff;	//店员列表
			}
			if(stripos($merchant , ',49,')!==FALSE){
				$arr[]	=	$hardware;	//打印机
			}
    	}else{
			$arr	=	array($group,$shop,$meal);
			if($appoint_row){
				$arr[]	=	$appoint;
			}
			$arr[]	=	$staff;
			$arr[]	=	$hardware;
    	}
		$this->returnCode(0,$arr);
	}
	/***首页图标统计数据***/
    public function getchart() {
    	$this->ticket();
        $nowtime = time();
        $todaystartime = strtotime(date('Y-m-d'));
        $startime = $todaystartime - (7 * 24 * 3600);
        $act	=	I('act');
        $action = trim($act);
        $newdatas = array();
        for ($d = 0; $d < 8; $d++) {
            $datekey = date('m-d', $startime + $d * 24 * 3600);
            $newdatas[$datekey] = 0;
        }
        $meal_orderDb = M('Meal_order');
        $group_orderDb = M('Group_order');
        switch ($action) {
            case 'order' :
                $mdatas = $meal_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND dateline >' . $startime . ' AND dateline <=' . $nowtime . " AND status!=3")->field('count(order_id) as percount,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($mdatas as $mvv) {
                    $newdatas[$mvv['perdate']] = (int)$mvv['percount'];
                }
                unset($mdatas);
                $gdatas = $group_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND add_time  >' . $startime . ' AND add_time  <=' . $nowtime . " AND status!=3")->field('count(order_id) as percount,FROM_UNIXTIME(add_time,"%m-%d") as perdate')->group('perdate')->select();
                foreach ($gdatas as $gvv) {
                    $newdatas[$gvv['perdate']] = isset($newdatas[$gvv['perdate']]) ? $newdatas[$gvv['perdate']] + $gvv['percount'] : $gvv['percount'];
                }
                break;
            case 'income' :
                $mdatas = $meal_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND paid="1" AND dateline >' . $startime . ' AND dateline <=' . $nowtime . " AND status!=3")->field('sum(if(total_price>0,total_price,price)) as tprice,sum(minus_price) as offprice,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($mdatas)) {
                    foreach ($mdatas as $mvv) {
                        $newdatas[$mvv['perdate']] = (int)$mvv['tprice'] - (int)$mvv['offprice'];
                    }
                }
                unset($mdatas);
                $gdatas = $group_orderDb->where('mer_id=' . $this->merchant_session['mer_id'] . ' AND paid="1" AND add_time  >' . $startime . ' AND add_time  <=' . $nowtime . " AND status!=3")->field('sum(total_money) as tprice,sum(wx_cheap) as offprice,FROM_UNIXTIME(add_time,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($gdatas)) {
                    foreach ($gdatas as $gvv) {
                        $perprice = $gvv['tprice'] - $gvv['offprice'];
                        $newdatas[$gvv['perdate']] = isset($newdatas[$gvv['perdate']]) ? $newdatas[$gvv['perdate']] + $perprice : $perprice;
                    }
                }
                break;
            case 'member' :
                $fansdata = M('')->table(array(C('DB_PREFIX') . 'merchant_user_relation' => 'm', C('DB_PREFIX') . 'user' => 'u'))->where("`m`.`openid`=`u`.`openid` AND `m`.`mer_id`='" . $this->merchant_session['mer_id'] . "' AND dateline >" . $startime . " AND dateline <=" . $nowtime)->field('count(dateline) as percount,FROM_UNIXTIME(dateline,"%m-%d") as perdate')->group('perdate')->select();
                if (!empty($fansdata)) {
                    foreach ($fansdata as $fvv) {
                        $newdatas[$fvv['perdate']] = (int)$fvv['percount'];
                    }
                }
                break;
            default:
                break;
        }
        $arr	=	array(
			'key'	=>	array_keys($newdatas),
			'value'	=>	array_values($newdatas),
        );
        $this->returnCode(0,$arr);
    }
    # 收入总数
	private function getallincomecount() {
		$meal_orderDb = M('Meal_order');
		$group_orderDb = M('Group_order');
		$appoint_orderDb = M('Appoint_order');
		$tmp_m_price = $meal_orderDb->where('mer_id=' . $this->merid . ' AND paid="1" AND status != 3')->field('price as tprice')->find();
		$tmp_m_price['tprice'] = number_format($tmp_m_price['tprice']);
		$meal_price = $tmp_m_price['tprice'] ;
		$tmp_g_price = $group_orderDb->where('mer_id=' . $this->merid . ' AND paid="1" AND status != 3')->field('sum(total_money) as tprice')->find();
		$group_price = $tmp_g_price['tprice'] ;
		$tmp_g_price = $appoint_orderDb->where('mer_id=' . $this->merid . ' AND paid="1" AND status != 3')->field('sum(pay_money) as tprice')->find();
		$appoint_price = $tmp_g_price['tprice'] ;
		return ($meal_price + $group_price+$appoint_price);
	}
    # 订单总数 月订单总数 日订单总数 粉丝数量
    private function getallordercount() {
    	$meal_orderDb = M('Meal_order');
    	$group_orderDb = M('Group_order');
        $meal_order_all = $meal_orderDb->where(array('mer_id' => $this->merid, 'status' => array('neq', 3)))->count();
        $nowtime = time();
        $todaystartime = strtotime(date('Y-m-d'));
        $monthstartime = strtotime(date('Y-m') . '-01 00:00:00');
        $meal_order_m = $meal_orderDb->where('mer_id=' . $this->merid . ' AND status!=3 AND dateline >' . $monthstartime . ' AND dateline <=' . $nowtime)->count();
        $meal_order_d = $meal_orderDb->where('mer_id=' . $this->merid . ' AND status!=3 AND dateline >' . $todaystartime . ' AND dateline <=' . $nowtime)->count();
        $group_order_all = $group_orderDb->where(array('paid' => 1, 'mer_id' => $this->merid, 'status' => array('neq', 3)))->count();
        $group_order_m = $group_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND status!=3 AND add_time >' . $monthstartime . ' AND add_time <=' . $nowtime)->count();
        $group_order_d = $group_orderDb->where('paid=1 AND mer_id=' . $this->merid . ' AND status!=3 AND add_time >' . $todaystartime . ' AND add_time <=' . $nowtime)->count();
        $fans_count = M('')->table(array(C('DB_PREFIX') . 'merchant_user_relation' => 'm', C('DB_PREFIX') . 'user' => 'u'))->where("`m`.`openid`=`u`.`openid` AND `m`.`mer_id`='$this->merid'")->count();
        $arr	=	array(
			'allordercount'	=>	intval($meal_order_all + $group_order_all),
			'monthordercount'	=>	intval($meal_order_m + $group_order_m),
			'todayordercount'	=>	intval($meal_order_d + $group_order_d),
			'fans_count'	=>	$fans_count,
        );
        return $arr;
    }
    # 店铺列表
    public function store_list() {
    	$where['mer_id']	=	$this->merchant_session['mer_id'];
    	$where['status']	=	array('neq', 4);
    	$page	=	I('pindex',1);
    	$all	=	M('Merchant_store')->where($where)->count();
        $data	=	M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`,`phone`'))->where($where)->page($page,10)->select();
        $arr['data']	=	isset($data)?$data:array();
        $arr['all']		=	$all;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }
    # 店铺修改状态
    public function store_status(){
		$where['store_id']	=	I('store_id');
		$data['status']	=	I('status',1);
		$data['status']	= $data['status'] == 1 ? 1 : 0;
		if($where['store_id']){
			$save	=	M('Merchant_store')->where($where)->data($data)->save();
		}else{
			$this->returnCode('20140029');
		}
		if($save){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140028');
		}
    }
    # 快店列表
    public function shop_list() {
    	$where['mer_id']	=	$this->merchant_session['mer_id'];
    	$where['status']		=	1;
    	$where['have_shop']		=	1;
    	$page	=	I('pindex',1);
    	$all	=	M('Merchant_store')->where($where)->count();
        $data	=	M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`,`phone`'))->where($where)->page($page,10)->select();
        foreach($data as &$v){
			$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $v['store_id']))->find();
        	$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
        	if ($store_theme) {
        		$v['width'] = '900';
        		$v['height'] = '900';
        	} else {
        		$v['width'] = '900';
        		$v['height'] = '500';
        	}
		}
        $arr['data']	=	isset($data)?$data:array();
        $arr['all']		=	$all;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }
    # 快店详情
    public function store_details(){
    	$store_id	=	I('store_id');
    	if(empty($store_id)){
			$this->returnCode('20140029');
    	}
        $data = M('Merchant_store')->where(array('store_id' => $store_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
        if (!empty($data)) {
            $data['office_time'] = unserialize($data['office_time']);
            if (!empty($data['pic_info'])) {
                $store_image_class = new store_image();
                $tmp_pic_arr = explode(';', $data['pic_info']);
                foreach ($tmp_pic_arr as $key => $value) {
                    $data['pic'][$key] = "'" . $store_image_class->get_image_by_path($value) . "'";
                }
//                $data['picstr'] = implode(',', $data['pic']);
            }
        }
        $keywords = D('Keywords')->where(array('third_type' => 'Merchant_store', 'third_id' => $data['store_id']))->select();
        $str = "";
        foreach ($keywords as $key) {
            $str .= $key['keyword'] . " ";
        }


        $arr	=	array(
			'store_id'	=>	$data['store_id'],		//商铺ID
			'mer_id'	=>	$data['mer_id'],		//商户ID
			'ismain'	=>	$data['ismain'],		//是否是主点	1主店 0不是主店
			'phone'		=>	$data['phone'],			//手机
			'weixin'	=>	$data['weixin'],		//微信
			'qq'		=>	$data['qq'],			//QQ
			'keywords'	=>	$str,					//关键词
			'permoney'	=>	$data['permoney'],		//人均消费
			'feature'	=>	$data['feature'],		//店铺特色
			'province_id'=>	$data['province_id'],	//省
			'city_id'	=>	$data['city_id'],		//市
			'area_id'	=>	$data['area_id'],		//区
			'circle_id'	=>	$data['circle_id'],		//商圈
			'adress'	=>	$data['adress'],		//地址
			'trafficroute'=>$data['trafficroute'],	//交通路线
			'sort'		=>	$data['sort'],			//排序
			'have_meal'	=>	$data['have_meal'],		//餐饮是否开启  0关闭  1开启
			'have_group'=>	$data['have_group'],	//团购是否开启  0关闭  1开启
			'open_1'	=>	$data['open_1'],		//打开时间1
			'open_2'	=>	$data['open_2'],		//打开时间2
			'open_3'	=>	$data['open_3'],		//打开时间3
			'close_1'	=>	$data['close_1'],		//结束时间1
			'close_2'	=>	$data['close_2'],		//结束时间2
			'close_3'	=>	$data['close_3'],		//结束时间3
			'lat'		=>	$data['lat'],			//经
			'long'		=>	$data['long'],			//纬
			'txt_info'	=>	$data['txt_info'],		//简介
			'pic'		=>	isset($data['pic'])?$data['pic']:array(),//图片
        );
        $this->returnCode(0,$arr);
    }
    # 快店二维码
    public function erwm($id='',$type='meal') {
        $type = trim($type);
        $id = trim(2);
        if ($type == 'group') {
            $pigcms_return = D('Group')->get_qrcode($id);
        } elseif ($type == 'meal') {
            $pigcms_return = D('Merchant_store')->get_qrcode($id);
        }

        if (empty($pigcms_return['qrcode_id'])) {
            $qrcode_return = D('Recognition')->get_new_qrcode($type, $id);
        } else {
            $qrcode_return = D('Recognition')->get_qrcode($pigcms_return['qrcode_id']);
        }
        if(empty($qrcode_return['error_code'])){
			return $qrcode_return['qrcode'];
        }
        return '';
    }
    # 快店订单
    public function sorder(){
    	$shop_order_obj = D('Shop_order');
        $store_id	=	I('store_id');
        $status	=	I('status');
        $keyword	=	I('keyword');
        $status = isset($status) ? trim($status) : 'all';
        $keyword = isset($keyword) ? trim($keyword) : '';
        $where = '`s`.`store_id`=' . $store_id;
        if ($status != 'all') {
            $status = intval($status);
            if ($status == 0) {
                $where.=' AND (`s`.`paid`="0" OR (`s`.`third_id` ="0" AND `s`.`pay_type`="offline"))';
            } elseif ($status == 1) {
                $where.=' AND `s`.`status`<2';
            } else {
                $where.=' AND `s`.`status`=' . $status;
            }
        }
        if (!empty($keyword)) {
            $where.=' AND (`s`.`userphone` like "%' . $keyword . '%" OR `s`.`username` like "%' . $keyword . '%")';
        }
        //订单列表
        $tp_count = "SELECT COUNT(*) as tp_count FROM " . C('DB_PREFIX') . "merchant_store AS ms INNER JOIN " . C('DB_PREFIX') . "shop_order AS s ON `s`.`store_id`=`ms`.`store_id` WHERE {$where}";
        $order_count = $shop_order_obj->query($tp_count);
        $pindex	=	I('pindex');
        $pindex = max(1, intval(trim($pindex)));
        $pagsize = 10;
        $offsize = ($pindex - 1) * 10;
        $sql = "SELECT `s`.*, `ms`.`name` AS storename FROM " . C('DB_PREFIX') . "merchant_store AS ms INNER JOIN " . C('DB_PREFIX') . "shop_order AS s ON `s`.`store_id`=`ms`.`store_id` WHERE {$where} ORDER BY `s`.`order_id` DESC LIMIT {$offsize}, {$pagsize}";
        $order_list = $shop_order_obj->query($sql);
        $hasmore = $order_count[0]['tp_count'] > ($pindex * $pagsize) ? 1 : 0;
        $newdatas = array();
		if (!empty($order_list)) {
			foreach ($order_list as $kk => $vv) {
				$order_status	=	'';
				$temp = array();
				$temp['order_status'] = '';
				if ($vv['paid']) {
					if (empty($vv['third_id']) && $vv['pay_type'] == 'offline') {
						$temp['order_status'] = '线下未付款';
					} else {
						$temp['order_status'] = '已付款';
					}
				}
				switch ($vv['status']) {
					case 0:
						$order_status	= '未确认';
						break;
					case 1:
						$order_status	= '已确认';
						break;
					case 2:
						$order_status	= '已消费';
						break;
					case 3:
						$order_status	= '已评价';
						break;
					case 4:
						$order_status	= '已退款';
						break;
					case 5:
						$order_status	= '已取消';
						break;
				}
				$temp['order_statuss'] =	isset($order_status)?$order_status:'';
				$temp['order_id'] = $vv['order_id'];
                $temp['nickname'] = $vv['username'];
                $temp['storename'] = $vv['storename'];
                $temp['phone'] = $vv['userphone'];
                $temp['address'] = $vv['address'];
                $temp['final_price'] = $vv['price'];
                $temp['num'] = $vv['num'] . '道菜';
                $temp['created'] = date('Y-m-d H:i:s', $vv['create_time']);
                $newdatas[] = $temp;
            }
        }
        unset($order_list);
        $arr	=	array(
			'order_count'	=>	$order_count[0]['tp_count'],
			'page'	=>	ceil($order_count[0]['tp_count']/10),
			'list'	=>	$newdatas,
        );
        $this->returnCode(0,$arr);
	}
	# 快店订单详情
	public function sdetail(){
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $this->merid = $this->merchant_session['mer_id'];
        $order = D("Shop_order")->get_order_detail(array('mer_id' => $this->merid, 'order_id' => $order_id));
        if($order){
    		if($order['pay_type'] == 'offline' && empty($order['third_id'])){
				$payment	=	floatval($order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']));
    		}
			$arr['order_details']	=	array(
				'orderid'	=>	$order['orderid'],
				'order_id'	=>	$order['order_id'],
				'real_orderid'	=>	$order['real_orderid'],
				'username'	=>	$order['username'],
				'userphone'	=>	$order['userphone'],
				'create_time'	=>	date('Y-m-d H:i:s',$order['create_time']),
				'pay_time'	=>	date('Y-m-d H:i:s',$order['pay_time']),
				'expect_use_time'	=>	$order['expect_use_time']!=0 ? (date('Y-m-d H:i:s',$order['expect_use_time'])) : '0',
				'is_pick_in_store'	=>	$order['is_pick_in_store'],
				'address'	=>	$order['address'],
				'deliver_str'	=>	$order['deliver_str'],
				'deliver_status_str'	=>	$order['deliver_status_str'],
				'note'	=>	isset($order['desc'])?$order['desc']:'',
				'invoice_head'	=>	$order['invoice_head'],
				'pay_status'	=>	$order['pay_status_print'],
				'pay_type_str'	=>	$order['pay_type_str'],
				'status_str'	=>	$order['status_str'],
				'score_used_count'	=>	$order['score_used_count'],
				'score_deducte'	=>	floatval($order['score_deducte']),
				'merchant_balance'	=>	floatval($order['merchant_balance']),
				'balance_pay'	=>	floatval($order['balance_pay']),
				'payment_money'	=>	floatval($order['payment_money']),
				'card_id'	=>	$order['card_id'],
				'card_price'	=>	$order['card_price'],
				'coupon_price'	=>	$order['coupon_price'],
				'payment'	=>	isset($payment)?$payment:0,
				'use_time'	=>	$order['use_time']!=0 ? (date('Y-m-d H:i:s',$order['use_time'])) : '0',
				'last_staff'	=>	$order['last_staff'],
				'status'	=>	$order['status'],
				'paid'	=>	$order['paid'],
				'goods_price'	=>	floatval($order['goods_price']),
				'freight_charge'	=>	floatval($order['freight_charge']),
				'total_price'	=>	floatval($order['total_price']),
				'merchant_reduce'	=>	floatval($order['merchant_reduce']),
				'balance_reduce'	=>	floatval($order['balance_reduce']),
				'price'	=>	floatval($order['price']),
				'notes'	=>	'注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，状态修改后就不能修改了',
			);
			foreach($order['info'] as $k=>$v){
				$arr['info'][]	=	array(
					'name'	=>	$v['name'],
					'price'	=>	floatval($v['price']),
					'num'	=>	$v['num'],
					'total'	=>	floatval($v['price']*$v['num']),
				);
			}
    	}else{
			$arr['order_details']	=	array();
    	}
    	if(empty($arr['info'])){
			$arr['info']	=	array();
    	}
    	$this->returnCode(0,$arr);
    }
	# 快店商品分类
	public function goods_sort(){
		$page	=	I('pindex',1);
		$store_id	=	I('store_id');
		$arr	=	array();
		if($store_id){
			$database_goods_sort = D('Shop_goods_sort');
			$sort_image_class = new goods_sort_image();
			$condition_goods_sort['store_id'] = $store_id;
			$count = $database_goods_sort->field(true)->where($condition_goods_sort)->count();
			$sort_list = $database_goods_sort->field(true)->where($condition_goods_sort)->order('`sort` DESC,`sort_id` ASC')->page($page,10)->select();
			if($sort_list){
				foreach ($sort_list as $key => $value) {
					if (!empty($value['week'])) {
						$week_arr = explode(',',$value['week']);
						$week_str = '';
						foreach ($week_arr as $k=>$v){
							$week_str .= $this->get_week($v).' ';
						}
						$value['week_str'] = $week_str;
					}
					$image	=	$sort_image_class->get_image_by_path($value['image'],$this->config['site_url'],'s');
					$value['image'] = $image===false?'':$image;
					$arr['list'][]	=	$value;
				}
			}
		}else{
			$this->returnCode('20140029');
		}
		$arr['count']	=	ceil($count/10);
		$this->returnCode(0,$arr);
	}
	# 添加商品分类
	public function sort_add(){
		$store_id	=	I('store_id');
		$sort_name	=	I('sort_name');
		$sort		=	I('sort');
		$is_weekshow=	I('is_weekshow');
		$week		=	I('week');
		$sort_discount=	I('sort_discount');
		if (empty($sort_name)) {
			$this->returnCode('20140030');
		} else {
			$database_goods_sort = D('Shop_goods_sort');
			$data_goods_sort['store_id'] = $store_id;
			$data_goods_sort['sort_name'] = $sort_name;
			$data_goods_sort['sort'] = intval($sort);
			$data_goods_sort['is_weekshow'] = intval($is_weekshow);
			$data_goods_sort['sort_discount'] = intval($sort_discount);
			if ($week) {
				$data_goods_sort['week'] = $week;
			}
			if ($database_goods_sort->data($data_goods_sort)->add()) {
				$this->returnCode(0);
			}else{
				$this->returnCode('20140031');
			}
		}
	}
	# 修改商品分类
	public function sort_edit(){
		$sort_id	=	I('sort_id');
		$sort_name	=	I('sort_name');
		$sort		=	I('sort');
		$is_weekshow=	I('is_weekshow');
		$week		=	I('week');
		$sort_discount=	I('sort_discount');
		if (empty($sort_name)) {
			$this->returnCode('20140030');
		} else {
			$database_goods_sort = D('Shop_goods_sort');
			$data_goods_sort['sort_name'] = $sort_name;
			$data_goods_sort['sort'] = intval($sort);
			$data_goods_sort['is_weekshow'] = intval($is_weekshow);
			$data_goods_sort['sort_discount'] = intval($sort_discount);
			if ($week) {
				$data_goods_sort['week'] = $week;
			}
			//$files	=	move_uploaded_file($_FILES['file']['tmp_name'], "./upload/".$_FILES["file"]["name"]);
//			if(empty($files)){
//				$this->returnCode('20140032');
//			}
//			$image	=	$this->config['site_url']."/upload/".$_FILES["file"]["name"];
//			$data_goods_sort['image'] = $image;
			if ($database_goods_sort->where(array('sort_id'=>$sort_id))->data($data_goods_sort)->save()) {
				$this->returnCode(0);
			} else {
				$this->returnCode('20140033');
			}
		}
	}
	# 删除分类
	public function sort_del(){
		$sort_id	=	I('sort_id');
		$store_id	=	I('store_id');
		if(empty($sort_id)){
			$this->returnCode('20140034');
		}
		if(empty($store_id)){
			$this->returnCode('20140033');
		}
		$count = D('Shop_goods')->where(array('sort_id' => $sort_id, 'store_id' => $store_id))->count();
		if ($count){
			$this->returnCode('20140035');
		}
		$database_goods_sort = D('Shop_goods_sort');
		$condition_goods_sort['sort_id'] = $sort_id;
		if ($database_goods_sort->where($condition_goods_sort)->delete()) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20140036');
		}
	}
	# 快店商品分类下的商品
	public function goods_list(){
		$sort_id	=	I('sort_id');
		$store_id	=	I('store_id');
		$page		=	I('pindex',1);
		$database_goods = D('Shop_goods');
		$condition_goods['sort_id'] = $sort_id;
		$count_goods = $database_goods->where($condition_goods)->count();
		$goods_list = $database_goods->field(true)->where($condition_goods)->order('`sort` DESC, `goods_id` ASC')->page($page,10)->select();
		if(empty($goods_list)){
			$arr['list']	=	array();
			$arr['count']	=	0;
		}else{
			$plist = array();
			$sort_image_class = new goods_image();
			$prints = D('Orderprinter')->where(array('mer_id' => $this->merchant_session['mer_id'], 'store_id' => $store_id))->select();
			foreach ($prints as $l) {
				if ($l['is_main']) {
					$l['name'] .= '(主打印机)';
				} else {
					$l['name'] = $l['name'] ? $l['name'] : '打印机-' . $l['pigcms_id'];
				}
				$plist[$l['pigcms_id']] = $l;
			}
			foreach ($goods_list as &$rl) {
				$image_tmp = explode(';', $rl['image']);
				foreach($image_tmp as $v){
					$tmp_image	=	$sort_image_class->get_image_by_path($v,'-1');
					$image[]	=	array(
						'url'	=>	$tmp_image['image'],
						'sql'	=>	$v,
					);
				}

				$rl['images'] = $image===false?'':$image;
				$arr['list'][] = array(
					'print_name'=>	isset($plist[$rl['print_id']]['name']) ? $plist[$rl['print_id']]['name'] : '',
					'goods_id'	=>	$rl['goods_id'],
					'sort_id'	=>	$rl['sort_id'],
					'store_id'	=>	$rl['store_id'],
					'number'	=>	$rl['number'],
					'name'		=>	$rl['name'],
					'price'		=>	strval(floatval($rl['price'])),
					'unit'		=>	$rl['unit'],
					'stock_num'	=>	$rl['stock_num'],
					'sell_count'=>	$rl['sell_count'],
					'last_time'=>	date('Y-m-d H:i:s',$rl['last_time']),
					'unit'		=>	$rl['unit'],
					'old_price'	=>	strval(floatval($rl['old_price'])),
					'seckill_price'	=>	strval(floatval($rl['seckill_price'])),
					'seckill_open_time'=>	date('Y-m-d H:i:s',$rl['seckill_open_time']),
					'seckill_close_time'=>	date('Y-m-d H:i:s',$rl['seckill_close_time']),
					'seckill_type'=>	$rl['seckill_type'],
					'seckill_stock'=>	$rl['seckill_stock'],
					'sort'=>	$rl['sort'],
					'status'=>	$rl['status'],
					'sell_mouth'=>	$rl['sell_mouth'],
					'today_sell_count'=>	$rl['today_sell_count'],
					'reply_count'=>	$rl['reply_count'],
					'is_properties'=>	$rl['is_properties'],
					'number'	=>	$rl['number'],
					'image'		=>	$image,
				);
				unset($image);
			}
			$arr['count']	=	$count_goods;
			$arr['page'] 	=	ceil($count_goods/10);
		}
		$this->returnCode(0,$arr);
	}
	# 商品状态
	public function goods_status(){
		$goods_id	=	I('goods_id');
		if($goods_id){
			$this->returnCode('20140023');
		}
		$type	=	I('type',1);
		$database_goods = D('Shop_goods');
		$condition_goods['goods_id'] = $goods_id;
		$data_goods['status'] =	$type;
		if($database_goods->where($condition_goods)->data($data_goods)->save()){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140024');
		}
	}
	# 添加店铺商品
	public function goods_add(){
		$sort_id	=	I('sort_id');	//分类ID
		$store_id	=	I('store_id');	//店铺ID
		$name		=	I('name');		//商品名
		$unit		=	I('unit');		//单位
		$old_price	=	I('price');		//老价格
		$price		=	I('price');		//新价格
		$stock_num	=	'-1';			//库存
		$pic		=	I('pic');		//图片
		if(empty($sort_id)){
			$this->returnCode('20140034');
		}
		if(empty($store_id)){
			$this->returnCode('20140029');
		}
		if (empty($name)) {
			$this->returnCode('20140037');
		}
		if (empty($unit)) {
			$this->returnCode('20140038');
		}
		if (empty($price)) {
			$this->returnCode('20140039');
		}
        if (empty($pic)) {
            $this->returnCode('20140040');
        }
		$arr	=	array(
			'sort_id'	=>	$sort_id,
			'store_id'	=>	$store_id,
			'name'		=>	$name,
			'unit'		=>	$unit,
			'old_price'	=>	$old_price,
			'price'		=>	$price,
			'stock_num'	=>	$stock_num,
			'image'		=>	$pic,
			'last_time'	=>	$_SERVER['REQUEST_TIME'],
		);
		$goods_id = D('Shop_goods')->data($arr)->add();
		if ($goods_id) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20140041');
		}
	}
	/* 编辑商品 */
	public function goods_edit(){
		$goods_id	=	I('goods_id');
		if(empty($goods_id)){
			$this->returnCode('20140023');
		}
		$sort_id	=	I('sort_id');
		$store_id	=	I('store_id');
		$name		=	I('name');
		$number		=	I('number');
		$unit		=	I('unit');
		$old_price	=	I('old_price');
		$price		=	I('price');
//		$stock_num	=	I('stock_num');
		$pic		=	I('pic');
		$des		=	I('des');
		$print_id	=	I('print_id');
		$specs		=	I('specs');
		$spec_val	=	I('spec_val');
		$properties	=	I('properties');
		$properties_val	=	I('properties_val');
		$prices		=	I('prices');
		if (empty($name)) {
			$this->returnCode('20140037');
		}
		if (empty($unit)) {
			$this->returnCode('20140038');
		}
		if (empty($price)) {
			$this->returnCode('20140039');
		}
        if (empty($pic)) {
            $this->returnCode('20140040');
        }
		$arr	=	array(
			'sort_id'	=>	$sort_id,
			'store_id'	=>	$store_id,
			'name'		=>	$name,
			'number'	=>	$number,
			'unit'		=>	$unit,
			'old_price'	=>	$old_price,
			'price'		=>	$price,
//			'stock_num'	=>	$stock_num,
			'image'		=>	$pic,
			'last_time'	=>	$_SERVER['REQUEST_TIME'],
		);
		$goods_id = D('Shop_goods')->where(array('goods_id'=>$goods_id))->data($arr)->save();
		if ($goods_id) {
			$this->returnCode(0);
		} else {
			$this->returnCode('20140041');
		}
	}
	# 删除店铺商品
	public function goods_del(){
		$goods_id	=	I('goods_id');
		$store_id	=	I('store_id');
		$database_goods = D('Shop_goods');
		$condition_goods['goods_id'] = $goods_id;
		if ($database_goods->where($condition_goods)->delete()) {
			$spec_obj = M('Shop_goods_spec'); //规格表
			$old_spec = $spec_obj->field(true)->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->select();
			foreach ($old_spec as $os) {
				$delete_spec_ids[] = $os['id'];
			}
			$spec_obj->where(array('goods_id' => $goods_id, 'store_id' => $store_id))->delete();
			if ($delete_spec_ids) {
				$old_spec_val = M('Shop_goods_spec_value')->where(array('sid' => array('in', $delete_spec_ids)))->delete();
			}
			M('Shop_goods_properties')->where(array('goods_id' => $goods_id))->delete();
			$this->returnCode(0);
		}else{
			$this->returnCode('20140045');
		}
	}
    # 团购列表
    public function glist() {
        $database_group = D('Group');
        $condition_group['mer_id'] = $this->merchant_session['mer_id'];
        $keyword	=	I('keyword');
        $keyword 	=	isset($keyword) ? trim($keyword) : '';
        if (!empty($keyword)) {
            $condition_group.=' AND (s_name like "%' . $keyword . '%" OR name like "%' . $keyword . '%")';
        }
        $group_count = $database_group->where($condition_group)->count();
        $page	=	I('pindex',1);
        $group_list = $database_group->field(true)->where($condition_group)->order('`group_id` DESC')->page($page,10)->select();

        $group_image_class = new group_image();
        foreach ($group_list as $key => $value) {
            $tmp_pic_arr = explode(';', $value['pic']);
            if($value['begin_time'] > $_SERVER['REQUEST_TIME']){
				$type	=	'未开团';
            }else if($value['end_time'] < $_SERVER['REQUEST_TIME']){
				$type	=	'已结束';
            }else if($value['type'] == 3){
				$type	=	'已结束';
            }else if($value['type'] == 4){
				$type	=	'结束失败';
            }else{
				$type	=	'进行中';
            }
            $tmp_group_list[]	=	array(
				'group_id'	=>	$value['group_id'],
				's_name'	=>	$value['s_name'],
				'price'		=>	$value['price'],
				'old_price'	=>	$value['old_price'],
				'sale_count'	=>	$value['sale_count'],
				'count_num'	=>	$value['count_num'],	//库存	0是无限制
				'virtual_num'	=>	$value['virtual_num'],
				'begin_time'	=>	date('Y-m-d H:i:s',$value['begin_time']),
				'end_time'	=>	date('Y-m-d H:i:s',$value['end_time']),
				'deadline_time'	=>	date('Y-m-d H:i:s',$value['deadline_time']),
				'hits'		=>	$value['hits'],
				'reply_count'	=>	$value['reply_count'],
				'qrcode'	=>	$this->config['site_url'].'/index.php?g=Index&c=Recognition&a=see_qrcode&type=group&id='.$value['group_id'].'&img=1',
				'type'		=>	$type,
				'status'	=>	$value['status'],
				'list_pic'	=>	$group_image_class->get_image_by_path($tmp_pic_arr[0], 's'),
            );
        }
		$arr	=	array(
			'group_list'	=>	isset($tmp_group_list)?$tmp_group_list:array(),
			'group_count'	=>	$group_count,
			'page' 	=>	ceil($group_count/10),
		);
        $this->returnCode(0,$arr);
    }
    # 团购状态
    public function gorder_status(){
    	$condition_group['group_id']	=	I('group_id');
    	$data['status']	=	I('status',1);
    	$database_group = D('Group');
    	if($condition_group){
			$save	=	$database_group->where($condition_group)->data($data)->save();
    	}else{
			$this->returnCode('20140049');
    	}
    	if($save){
			$this->returnCode(0);
    	}else{
			$this->returnCode('20140028');
    	}
    }
    # 团购订单
    public function gorder() {
        $group_id	=	I('group_id');
        if(empty($group_id)){
			$this->returnCode('20140048');
        }
        $status	=	I('status');
        $keyword	=	I('keyword');
        $status = isset($status) ? trim($status) : 'all';
        $keyword = isset($keyword) ? trim($keyword) : '';
        $where = 'gord.group_id=' . $group_id;
        if ($status != 'all') {
            $status = intval($status);
            if ($status == 0) {
                $where.=' AND (gord.paid="0" OR (gord.third_id ="0" AND gord.pay_type="offline"))';
            } else {
                $where.=' AND gord.status="' . ($status - 1) . '"';
            }
        }
        if (!empty($keyword)) {
            $where.=' AND (gord.phone like "%' . $keyword . '%" OR u.nickname like "%' . $keyword . '%" OR u.truename like "%' . $keyword . '%")';
        }
        //订单列表
        $group_orderDb = M('Group_order');
        $jointable = C('DB_PREFIX') . 'user';
        $group_orderDb->join('as gord LEFT JOIN ' . $jointable . ' as u on gord.uid=u.uid');
        $order_count = $group_orderDb->where($where)->count();
        $pindex	=	I('pindex');
        $pindex = intval(trim($pindex));
        $pindex = $pindex > 0 ? $pindex : 1;
        $pagsize = 10;
        $offsize = ($pindex - 1) * 20;
        $newdatas = array();
        $group_orderDb->join('as gord LEFT JOIN ' . $jointable . ' as u on gord.uid=u.uid');
        $order_list = $group_orderDb->field('gord.*,u.nickname,u.truename')->where($where)->order('gord.add_time DESC')->limit($offsize . ',' . $pagsize)->select();
//        $hasmore = $order_count > ($pindex * $pagsize) ? 1 : 0;
        if (!empty($order_list)) {
            foreach ($order_list as $kk => $vv) {
            	$order_statuss	=	'';
                if ($vv['status'] == 3) {
                    $newdatas[$kk]['order_status'] = '已取消';
                } elseif ($vv['status'] == 4) {
                    $newdatas[$kk]['order_status'] = '已删除';
                } elseif ($vv['paid'] > 0) {
                    if (($vv['third_id'] == "0") && ($vv['pay_type'] == 'offline')) {
                        $newdatas[$kk]['order_status'] = '线下未付款';
                    } elseif ($vv['status'] == 0) {
                        $newdatas[$kk]['order_status'] = '已付款';
                        if ($vv['tuan_type'] != 2) {
                            $order_statuss='未消费';
                        } else {
                            $order_statuss='未发货';
                        }
                    } elseif ($vv['status'] == 1) {
                        if ($vv['tuan_type'] != 2) {
                            $newdatas[$kk]['order_status'] = '已消费';
                        } else {
                            $newdatas[$kk]['order_status'] = '已发货';
                        }
                    } else {
                        $newdatas[$kk]['order_status'] = '已完成';
                    }
                } else {
                    $newdatas[$kk]['order_status'] = '未付款';
                }
                $newdatas[$kk]['order_statuss']	=	isset($order_statuss)?$order_statuss:'';
                $newdatas[$kk]['order_id'] = $vv['order_id'];
                $newdatas[$kk]['nickname'] = !empty($vv['truename']) ? $vv['truename'] : $vv['nickname'];
                $newdatas[$kk]['phone'] = $vv['phone'];
                $newdatas[$kk]['address'] = $vv['adress'];
                $newdatas[$kk]['final_price'] = $vv['total_money'] - $vv['wx_cheap'];
                $newdatas[$kk]['num'] = $vv['num'] . '份';
                $newdatas[$kk]['created'] = date('Y-m-d H:i:s', $vv['add_time']);
            }
        }
        unset($order_list);
        $arr	=	array(
        	'order_count'	=>	$order_count,
			'list'		=>	$newdatas,
			'page' 	=>	ceil($order_count/10),
        );
        $this->returnCode(0,$arr);
    }
    # 团购订单详情
    public function group_edit() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'],$order_id,false);
        if (empty($now_order)) {
            $this->returnCode('20140026');
        }
        $arr['store_id']	=	$now_order['store_id'];
        $arr['order_id']	=	$now_order['order_id'];
        $arr['s_name']	=	$now_order['s_name'];
        if($now_order['tuan_type'] == 0){
			$now_order['tuan_type']	=	$this->config['group_alias_name'].'券';
        }else if($now_order['tuan_type'] == 1){
			$now_order['tuan_type']	=	'代金券';
        }else{
			$now_order['tuan_type']	=	'实物';
        }
        if($now_order['status'] == 3){
			$arr['status']	=	'已取消';
        }else if($now_order['paid'] == 1){
        	if($now_order['third_id'] == 0 && $now_order['pay_type'] == 'offline'){
				$arr['status']	=	'线下未付款';
        	}else if($now_order['status'] == 0){
				$arr['status']	=	'已付款';
				if($now_order['tuan_type'] != 2){
					$arr['statuss']	=	'未消费';
				}else{
					$arr['statuss']	=	'未发货';
				}
        	}else if($now_order['status'] == 1){
				$arr['status']	=	'待评价';
				if($now_order['tuan_type'] != 2){
					$arr['statuss']	=	'已消费';
				}else{
					$arr['statuss']	=	'已发货';
				}
        	}else{
				$arr['status']	=	'已完成';
        	}
        }else{
			$arr['status']	=	'未付款';
        }
        $arr['num']	=	$now_order['num'];
        $arr['price']	=	$now_order['price'];
        $arr['total_money']	=	$now_order['total_money'];
        $arr['score_used_count']	=	$now_order['score_used_count'];
        $arr['score_deducte']	=	$now_order['score_deducte'];
        if(!empty($now_order['coupon_id'])) {
            $system_coupon = D('System_coupon')->get_coupon_info($now_order['coupon_id']);
			$arr['system_coupon']	=	$system_coupon['price'];
        }else if(!empty($now_order['card_id'])) {
            $card = D('Member_card_coupon')->get_coupon_info($now_order['card_id']);
			$arr['card_coupon']	=	$card['price'];
        }
        if(empty($arr['system_coupon'])){
			$arr['system_coupon']	=	'';
        }
        if(empty($arr['card_id'])){
			$arr['card_coupon']	=	'';
        }
        $arr['pay']	=	$now_order['payment_money']+$now_order['balance_pay'];
        $arr['add_time']	=	date('Y-m-d H:i:s',$now_order['add_time']);
        $arr['pay_time']	=	date('Y-m-d H:i:s',$now_order['pay_time']);
        $arr['use_time']	=	date('Y-m-d H:i:s',$now_order['use_time']);
        $arr['last_staff']	=	$now_order['last_staff'];
        if($now_order['status'] > 0 && $now_order['status'] < 3){
			if($now_order['tuan_type'] != 2){
				$arr['font']	=	'消费';
			}else{
				$arr['font']	=	'发货';
			}
        }
        if (!empty($now_order['pay_type'])) {
            $arr['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);
            if (($now_order['pay_type'] == 'offline') && !empty($now_order['third_id']) && ($now_order['paid'] == 1)) {
                $arr['paytypestrs'] ='已支付';
            } else if (($now_order['pay_type'] != 'offline') && ($now_order['paid'] == 1)) {
                $arr['paytypestrs'] ='已支付';
            } else {
                $arr['paytypestrs'] ='未支付';
            }
        } else {
        	if ($now_order['balance_pay'] > 0) {
        		$arr['paytypestr'] = '平台余额支付';
        	} elseif ($now_order['merchant_balance'] > 0) {
        		$arr['paytypestr'] = '商家余额支付';
        	} elseif ($now_order['paid']) {
        		$arr['paytypestr'] = '其他';
        	} else {
        		$arr['paytypestr'] = '未支付';
        	}
        }
        $arr['delivery_comment']	=	$now_order['delivery_comment'];
        if($now_order['paid'] == 1){
			$arr['uid']	=	$now_order['uid'];
	        $arr['nickname']	=	$now_order['nickname'];
	        $arr['order_phone']	=	$now_order['phone'];
	        $arr['user_phone']	=	$now_order['user_phone'];
	        if($now_order['tuan_type'] == 2){
	        	$arr['contact_name']	=	$now_order['contact_name'];
	        	$arr['phone']	=	$now_order['phone'];
	        	$arr['zipcode']	=	$now_order['zipcode'];
	        	$arr['adress']	=	$now_order['adress'];
	        	$arr['delivery_type']	=	$this->order_distribution($now_order['delivery_type']);
	        }else{
				$arr['contact_name']	=	'';
	        	$arr['phone']	=	'';
	        	$arr['zipcode']	=	'';
	        	$arr['adress']	=	'';
	        	$arr['delivery_type']	=	'';
	        }
			$arr['merchant_remark']	=	$now_order['merchant_remark'];
        }else{
			$arr['uid']	=	'';
	        $arr['nickname']	=	'';
	        $arr['order_phone']	=	'';
	        $arr['user_phone']	=	'';
	        $arr['contact_name']	=	'';
	        $arr['phone']	=	'';
	        $arr['zipcode']	=	'';
	        $arr['adress']	=	'';
	        $arr['delivery_type']	=	'';
	        $arr['merchant_remark']	=	'';
        }
        $this->returnCode(0,$arr);
    }
    //	团购详情
    public function gdetail(){
		$order_id	=	I('order_id');
		if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'], $order_id, false);
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
		);
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
    # 修改团购订单归属店铺
    public function order_store_id() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'],$order_id, true, false);
        if (empty($now_order)) {
            $this->returnCode('20140026');
        }
        if (empty($now_order['paid'])) {
            $this->returnCode('20140027');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['store_id'] = I('store_id');
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140028');
        }
    }
    # 团购订单额外信息
    public function group_remark() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->merchant_session['mer_id'], $order_id, true, false);
        if (empty($now_order)) {
            $this->returnCode('20140026');
        }
        if (empty($now_order['paid'])) {
            $this->returnCode('20140027');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['merchant_remark'] = I('merchant_remark');
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140028');
        }
    }
    # 团购商品管理
    public function gpro() {
        $database_group = D('Group');
        $condition_group = 'mer_id=' . $this->merchant_session['mer_id'];
        $keyword	=	I('keyword');
        $keyword = isset($keyword) ? trim($keyword) : '';
        if (!empty($keyword)) {
            $condition_group.=' AND (s_name like "%' . $keyword . '%" OR name like "%' . $keyword . '%")';
        }
        $group_count = $database_group->where($condition_group)->count();
        $pindex	=	I('pindex');
        $pindex = intval(trim($pindex));
        $pindex = $pindex > 0 ? $pindex : 1;
        $pagsize = 20;
        $offsize = ($pindex - 1) * 20;
        $group_list = $database_group->field('group_id,mer_id,prefix_title,name,s_name,pic ,old_price,price,wx_cheap,discount,sale_count,status,type,tuan_type,qrcode_id')->where($condition_group)->order('`group_id` DESC')->limit($offsize . ',' . $pagsize)->select();
        $group_image_class = new group_image();
        foreach ($group_list as $key => $value) {
            $tmp_pic_arr = explode(';', $value['pic']);
			$group[]	=	array(
				'list_pic'	=>	$group_image_class->get_image_by_path($tmp_pic_arr[0], 's'),
				's_name'	=>	$value['s_name'],
				'old_price'	=>	floatval($value['old_price']),
				'price'		=>	floatval($value['price']),
				'wx_cheap'	=>	floatval($value['wx_cheap']),
				'sale_count'=>	$value['sale_count'],
				'group_id'	=>	$value['group_id'],
			);
        }
        $hasmore = $group_count > ($pindex * $pagsize) ? 1 : 0;
        $arr	=	array(
        	'group_count'	=>	$group_count,
        	'page'	=>	ceil($group_count/10),
			'list' => !empty($group) ? $group : array(),
        );
        $this->returnCode(0,$arr);
    }
    # 餐饮列表
    public function mlist(){
		$where['mer_id']	=	$this->merchant_session['mer_id'];
    	$where['status']	=	array('elt','2');
    	$page	=	I('pindex',1);
        $data = M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`'))->where($where)->page($page,10)->select();
        if ($data != false) {
        	foreach($data as &$v){
				$v['qrcode']	=	$this->erwm($v['store_id']);
        	}
            $arr['data']	=	$data;
            $arr['all']		=	M('Merchant_store')->where($where)->count();
            $arr['status1'] =	M('Merchant_store')->where(array('status' => 1, 'mer_id' => $this->merchant_session['mer_id']))->count();
            $arr['status2'] =	M('Merchant_store')->where(array('status' => 2, 'mer_id' => $this->merchant_session['mer_id']))->count();
            $arr['page']	=	ceil($arr['all']/10);
        }else{
			$arr	=	array(
				'data' 		=>	array(),
				'all'		=>	array(),
				'status1'	=>	array(),
				'status2'	=>	array(),
			);
        }
        $this->returnCode(0,$arr);
    }
    # 餐饮店铺列表
    public function meal_list() {
    	$where['mer_id']	=	$this->merchant_session['mer_id'];
    	$where['status']		=	array('neq','2');
    	$where['have_meal']		=	array('eq','1');
    	$page	=	I('pindex',1);
    	$all	=	M('Merchant_store')->where($where)->count();
        $data	=	M('Merchant_store')->field(array('`mer_id`,`name`,`store_id`,`status`,`phone`'))->where($where)->page($page,10)->select();
        foreach($data as &$v){
			$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $v['store_id']))->find();
        	$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
        	if ($store_theme) {
        		$v['width'] = '900';
        		$v['height'] = '900';
        	} else {
        		$v['width'] = '900';
        		$v['height'] = '500';
        	}
		}
        $arr['data']	=	isset($data)?$data:array();
        $arr['all']		=	$all;
        $arr['page'] 	=	ceil($arr['all']/10);
        $this->returnCode(0,$arr);
    }
    # 餐饮订单
    public function morder() {
        $mer_id = $this->merchant_session['mer_id'];
        $status	=	I('status');
        $keyword	=	I('keyword');
        $status = isset($status) ? trim($status) : 'all';
        $keyword = isset($keyword) ? trim($keyword) : '';
        $where = 'mord.mer_id=' . $mer_id;
        if ($status != 'all') {
            $status = intval($status);
            if ($status == 0) {
                $where.=' AND (mord.paid="0" OR (mord.third_id ="0" AND mord.pay_type="offline"))';
            } else {
                $where.=' AND mord.status="' . ($status - 1) . '"';
            }
        }
        if (!empty($keyword)) {
            $where.=' AND (mord.phone like "%' . $keyword . '%" OR mord.name like "%' . $keyword . '%")';
        }
        //订单列表
        $meal_orderDb = M('Meal_order');
        $jointable = C('DB_PREFIX') . 'merchant_store';
        $order_count = $meal_orderDb->join('as mord LEFT JOIN ' . $jointable . ' as ms on mord.store_id=ms.store_id')->where($where)->count();
        $pindex	=	I('pindex');
        $pindex = intval(trim($pindex));
        $pindex = $pindex > 0 ? $pindex : 1;
        $pagsize = 20;
        $offsize = ($pindex - 1) * 20;
        $newdatas = array();
        $order_list = $meal_orderDb->join('as mord LEFT JOIN ' . $jointable . ' as ms on mord.store_id=ms.store_id')->field('mord.*,ms.name as storename')->where($where)->order('order_id  DESC')->limit($offsize . ',' . $pagsize)->select();
        $hasmore = $order_count > ($pindex * $pagsize) ? 1 : 0;
        if (!empty($order_list)) {
            foreach ($order_list as $kk => $vv) {
            	$order_statuss='';
                if ($vv['status'] == 3) {
                    $newdatas[$kk]['order_status'] = '已取消';
                } elseif ($vv['status'] == 4) {
                    $newdatas[$kk]['order_status'] = '已删除';
                } elseif ($vv['paid'] > 0) {
                    if (($vv['third_id'] == "0") && ($vv['pay_type'] == 'offline')) {
                        $newdatas[$kk]['order_status'] = '线下未付款';
                    } elseif ($vv['status'] == 0) {
                        $newdatas[$kk]['order_status'] = '已付款';
                        if ($vv['tuan_type'] != 2) {
                            $order_statuss='未消费';
                        } else {
                            $order_statuss='未发货';
                        }
                    } elseif ($vv['status'] == 1) {
                        if ($vv['tuan_type'] != 2) {
                            $newdatas[$kk]['order_status'] = '已消费';
                        } else {
                            $newdatas[$kk]['order_status'] = '已发货';
                        }
                    } else {
                        $newdatas[$kk]['order_status'] = '已完成';
                    }
                } else {
                    $newdatas[$kk]['order_status'] = '未付款';
                }
				$newdatas[$kk]['order_statuss']	=	isset($order_statuss)?$order_statuss:'';
                $newdatas[$kk]['order_id'] = $vv['order_id'];
                $newdatas[$kk]['nickname'] = $vv['name'];
                $newdatas[$kk]['storename'] = $vv['storename'];
                $newdatas[$kk]['phone'] = $vv['phone'];
                $newdatas[$kk]['address'] = $vv['address'];
                $newdatas[$kk]['final_price'] = $vv['total_price'] > 0 ? $vv['total_price'] - $vv['minus_price'] : $vv['price'] - $vv['minus_price'];
                $newdatas[$kk]['num'] = $vv['total'] . '道菜';
                $newdatas[$kk]['created'] = date('Y-m-d H:i:s', $vv['dateline']);
            }
        }
        unset($order_list);
        $arr	=	array(
			'order_count'	=>	$order_count,
			'list'	=>	$newdatas,
			'page'	=>	ceil($order_count/10),
        );
        $this->returnCode(0,$arr);
    }
    # 餐饮订单详情
    public function mdetail() {
    	$order_id	=	I('order_id');
    	if(empty($order_id)){
			$this->returnCode('20140025');
    	}
    	$Meal_order	=	M('Meal_order');
        $order = $Meal_order->where(array('mer_id' => $this->merchant_session['mer_id'], 'order_id' => $order_id))->find();
        if(empty($order)){
			$this->returnCode('20140026');
        }
        $order['info'] = unserialize($order['info']);
        if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
            $order['paid'] = 0;
        }
        if (!empty($order['pay_type'])) {
            $order['paytypestr'] = D('Pay')->get_pay_name($order['pay_type']);
            if (($order['pay_type'] == 'offline') && !empty($order['third_id']) && ($order['paid'] == 1)) {
                $order['paytypestrs'] =' 已支付';
            } else if (($order['pay_type'] != 'offline') && ($order['paid'] == 1)) {
                $order['paytypestrs'] =' 已支付';
            } else {
                $order['paytypestrs'] =' 未支付';
            }
        } else {
        	if ($order['balance_pay'] > 0) {
        		$order['paytypestr'] = '平台余额支付';
        	} elseif ($order['merchant_balance'] > 0) {
        		$order['paytypestr'] = '商家余额支付';
        	} elseif ($order['paid']) {
        		$order['paytypestr'] = '其他';
        	} else {
        		$order['paytypestr'] = '未支付';
        	}
        }
        $arr['order_id']	=	$order['order_id'];
        if(!empty($order['coupon_id'])) {
            $system_coupon = D('System_coupon')->get_coupon_info($order['coupon_id']);
            $this->assign('system_coupon',$system_coupon);
        }else if(!empty($order['card_id'])) {
            $card = D('Member_card_coupon')->get_coupon_info($order['card_id']);
            $this->assign('card', $card);
        }
		$mode = new Model();
		$sql = "SELECT u.name, u.phone FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON u.uid=s.uid WHERE s.order_id={$order['order_id']} AND s.item=0";
		$res = $mode->query($sql);
		$res = isset($res[0]) && $res[0] ? $res[0] : '';
        $arr	=	array(
			'order_id'	=>	$order['order_id'],
			'name'	=>	$order['name'],
			'phone'	=>	$order['phone'],
			'price'	=>	$order['price'],
			'address'	=>	$order['address'],
			'dateline'	=>	date('Y-m-d H:i:s',$order['dateline']),
			'arrive_time'	=>	$order['arrive_time']==0?0:date('Y-m-d H:i:s',$order['arrive_time']),
			'use_time'	=>	$order['use_time']==0?0:date('Y-m-d H:i:s',$order['use_time']),
			'note'	=>	$order['note'],
			'tuan_type'	=>	isset($order['tuan_type'])?$order['tuan_type']:0,	//不等于2 消费时间use_time，2发货时间use_time
			'balance_pay'	=>	$order['balance_pay'],
			'score_deducte'	=>	$order['score_deducte'],
			'score_used_count'	=>	$order['score_used_count'],
			'payment_money'	=>	$order['payment_money'],
			'merchant_balance'	=>	$order['merchant_balance'],
			'coupon_price'	=>	$order['coupon_price'],
			'card_price'	=>	floatval($order['card_price']),
			'paytypestr'	=>	$order['paytypestr'],
			'paytypestrs'	=>	$order['paytypestrs'],
			'info'			=>	isset($order['info'])?$order['info']:array(),
			'status'		=>	$order['status'],	//0未使用	1已使用	2已评价	3已退款	4已取消
			'user_name'		=>	isset($order['deliver_user_info']['name'])?$order['deliver_user_info']['name']:'',
			'user_phone'	=>	isset($order['deliver_user_info']['phone'])?$order['deliver_user_info']['phone']:'',
			'deliver_user_info'	=>	$res,
        );
        if($order['total_price'] == 0){
			$arr['total_price']	=	$order['total_price']-$order['minus_price']-$order['balance_pay']-$order['merchant_balance']-$order['coupon_price']-$order['card_price']-floatval($order['score_deducte']);
        }else{
			$arr['total_price']	=	$order['price']-$order['balance_pay']-$order['merchant_balance']-$order['coupon_price']-$order['card_price']-floatval($order['score_deducte']);
        }
        $this->returnCode(0,$arr);
    }
    # 餐饮商品分类
    public function meal_sort(){
    	$store_id	=	I('store_id');
    	$page		=	I('pindex',1);
		$database_meal_sort = D('Meal_sort');
		$condition_merchant_sort['store_id'] = $store_id;
		$count_sort = $database_meal_sort->where($condition_merchant_sort)->count();
		$sort_list = $database_meal_sort->field(true)->where($condition_merchant_sort)->order('`sort` DESC,`sort_id` ASC')->page($page,10)->select();
		foreach($sort_list as $key=>$value){
			if(!empty($value['week'])){
				$week_arr = explode(',',$value['week']);
				$week_str = '';
				foreach($week_arr as $k=>$v){
					$week_str .= $this->get_week($v).' ';
				}
				$sort_list[$key]['week_str'] = $week_str;
			}
		}
		$arr	=	array(
			'sort_list'	=>	$sort_list,
			'count'		=>	$count_sort,
			'page'		=>	ceil($count_sort/10),
		);
		$this->returnCode(0,$arr);
    }
    # 餐饮商品
    public function mpro() {
        $database_meal = D('Meal');
        $sort_id	=	I('sort_id');
        $condition_meal = 'sort_id in (' . $sort_id . ')';
        $keyword	=	I('keyword');
        $keyword = isset($keyword) ? trim($keyword) : '';
        if (!empty($keyword)) {
            $condition_meal.=' AND (name like "%' . $keyword . '%")';
        }
        $count_meal = $database_meal->where($condition_meal)->count();
        $pindex	=	I('pindex',1);
        $pagsize = 20;
        $offsize = ($pindex - 1) * 20;
        $meal_list = $database_meal->field(true)->where($condition_meal)->order('`sort` DESC,`meal_id` ASC')->limit($offsize . ',' . $pagsize)->select();
        $meal_image_class = new meal_image();
        if (!empty($meal_list)) {
            foreach ($meal_list as $mk => $mv) {
                $meal[$mk]['list_pic'] = $meal_image_class->get_image_by_path($mv['image'], $this->config['site_url'], 's');
                $meal[$mk]['s_name'] = $mv['name'];
                $meal[$mk]['meal_id'] = $mv['meal_id'];
                $meal[$mk]['sort_id'] = $mv['sort_id'];
                $meal[$mk]['store_id'] = $mv['store_id'];
                $meal[$mk]['sell_count'] = $mv['sell_count'];
                $meal[$mk]['statusstr'] = $mv['status'] == 1 ? '在售' : '停售';
                $meal[$mk]['statusoptstr'] = $mv['status'] == 1 ? '下架' : '上架';
                $meal[$mk]['statusopt'] = $mv['status'] == 1 ? '0' : '1';
                $meal[$mk]['old_price'] = floatval($mv['old_price']);
                $meal[$mk]['price'] = floatval($mv['price']);
            }
        }
        $hasmore = $count_meal > ($pindex * $pagsize) ? 1 : 0;
        $arr	=	array(
			'list'	=>	!empty($meal) ? $meal : array(),
			'count'	=>	$count_meal,
			'page'		=>	ceil($count_meal/10),
        );
        $this->returnCode(0,$arr);
    }
    public function getstore_id_Bymerid($mer_id, $name = false) {
        $tmpdatas = M('merchant_store')->field('store_id,name')->where(array('mer_id' => $mer_id, 'have_meal' => '1', 'status' => '1'))->select();
        if ($name)
            return $tmpdatas;
        $storeids = array();
        if (!empty($tmpdatas)) {
            foreach ($tmpdatas as $vv) {
                $storeids[] = $vv['store_id'];
            }
        }
        return $storeids;
    }
    # 餐饮商品上架、下架
    public function mstatusopt() {
    	$status		=	I('status');
    	$meal_id	=	I('meal_id');
    	$store_id	=	I('store_id');
        if ($store_id > 0 && $meal_id > 0) {
            if (M('Meal')->where(array('store_id' => $store_id, 'meal_id' => $meal_id))->save(array('status' => $status))) {
                $this->returnCode(0);
            }else{
				$this->returnCode('20140046');
            }
        }
        $this->returnCode('20140047');
    }
    # 餐饮商品删除
    public function mdel() {
    	$meal_id	=	I('meal_id');
    	$store_id	=	I('store_id');
        if (M('Meal')->where(array('store_id' => $store_id, 'meal_id' => $meal_id))->delete()) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140045');
        }
    }
    # 预约列表
    public function appoint(){
        $database_appoint = D('Appoint');
        $database_merchant = D('Merchant');
        $database_category = D('Appoint_category');
        $condition_appoint['mer_id'] = $this->merchant_session['mer_id'];
        $appoint_count = $database_appoint->where($condition_appoint)->count();
		$pindex	=	I('pindex',1);
        $appoint_info = $database_appoint->field(true)->where($condition_appoint)->order('`appoint_id` DESC')->page($pindex,10)->select();
        $merchant_info = $database_merchant->field(true)->where('mer_id = ' . $this->merchant_session['mer_id'] . '')->select();
        $category_info = $database_category->field(true)->where($condition_appoint)->select();
        $appoint_list = $this->formatArray($appoint_info, $merchant_info, $category_info);
        foreach($appoint_list as &$v){
			$v['start_time']	=	date('Y-m-d H:i:s',$v['start_time']);
        	$v['end_time']	=	date('Y-m-d H:i:s',$v['end_time']);
        	if($v['appoint_status'] == 1){
				$v['appoint_status'] = 0;
        	}else{
				$v['appoint_status'] = 1;
        	}
        	$tmp_pic_arr = explode(';', $v['pic']);
	        $appoint_image_class = new appoint_image();
	        foreach ($tmp_pic_arr as $key => $value) {
	            $pic_list[$key]['title'] = $value;
	            $pic_list[$key]['url'] = $appoint_image_class->get_image_by_path($value, 's');
	        }
	        $v['pics']	=	$pic_list[0]['url'];
	        $v['qrcode_id']	=	$this->config['site_url'].'/index.php?g=Index&c=Recognition&a=see_qrcode&type=appoint&id='.$v['appoint_id'].'&img=1';
			unset($v['office_time'],$v['appoint_pic_content'],$v['is_store'],$v['cat_fid'],$v['cat_id'],$v['create_time'],$v['mer_id'],$v['pics']);
        }
        $arr	=	array(
			'count'			=>	isset($appoint_count)?$appoint_count:0,
			'appoint_list'	=>	isset($appoint_list)?$appoint_list:array(),
			'page'	=>	ceil($appoint_count/10),
        );
        $this->returnCode(0,$arr);
    }
    # 预约列表--修改状态
    public function appoint_status(){
		$database_appoint = D('Appoint');
		$condition_appoint['appoint_id']	=	I('appoint_id');
		$data['appoint_status']	=	I('appoint_status');
		if($data['appoint_status'] == 1){
			$data['appoint_status']	=	0;
		}else{
			$data['appoint_status']	=	1;
		}
		if(empty($condition_appoint)){
			$this->returnCode('20140050');
		}
		$appoint_info = $database_appoint->where($condition_appoint)->data($data)->save();
		if($appoint_info){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140028');
		}
    }
    # 预约订单--访店员中心
    public function order_list(){
		$store_id = I('appoint_id');
		$database_order = D('Appoint_order');
    	$database_user = D('User');
    	$database_appoint = D('Appoint');
    	$database_store = D('Merchant_store');
    	$order_id	=	I('order_id');
    	$where['appoint_id'] = $store_id;
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
    # 预约订单
    public function order_list1(){
        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');
        $database_merchant_workers_appoint = D('Merchant_workers_appoint');
        $appoint_id	=	I('appoint_id');
        if ($appoint_id) {
            $where['appoint_id'] = $appoint_id;
        }else{
			$this->returnCode('20140050');
        }
		$merchant_worker_id	=	I('merchant_worker_id');
        if ($merchant_worker_id) {
            $where['merchant_worker_id'] = $merchant_worker_id;
        }
        $where['mer_id'] = $this->merchant_session['mer_id'];
        $where['store_id'] = array('neq', 0);
        $order_count = $database_order->where($where)->count();
		$pindex	=	I('pindex',1);
        $order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->page($pindex,10)->select();
        $uidArr = array();
        foreach ($order_info as $v) {
            array_push($uidArr, $v['uid']);
        }
        $uidArr = array_unique($uidArr);
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid' => array('in', $uidArr)))->select();
        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`')->select();
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
        $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
        if($order_list){
	        foreach($order_list as $v){
				$order[]	=	array(
					'order_id'	=>	$v['order_id'],
					'payment_money'	=>	$v['payment_money'],
					'store_name'	=>	$v['store_name'],
					'store_adress'	=>	$v['store_adress'],
					'appoint_type'	=>	$v['appoint_type'],
					'uid'		=>	$v['uid'],
					'nickname'	=>	$v['nickname'],
					'phone'		=>	$v['phone'],
					'content'	=>	isset($v['content'])?$v['content']:'',
					'paid'		=>	$v['paid'],
					'service_status'=>	$v['service_status'],
					'is_del'	=>	$v['is_del'],
					'order_time'=>	date('Y-m-d H:i:s',$v['order_time']),
					'pay_time'	=>	$v['pay_time']==0?0:date('Y-m-d H:i:s',$v['pay_time']),
					'type'		=>	$v['type'],
				);
	        }
        }
        $arr	=	array(
			'count'	=>	$order_count,
			'order_list'	=>	isset($order)?$order:array(),
			'page'	=>	ceil($order_count/10),
        );
        $this->returnCode(0,$arr);
    }
    /*预约订单查找*/
	public function appoint_find(){
		$database_order = D('Appoint_order');
	    $database_user = D('User');
	    $database_appoint = D('Appoint');
	    $database_store = D('Merchant_store');
		$order_id	=	I('order_id');
		$find_type	=	I('find_type');
		$find_value	=	I('find_value');
		$appoint_where['mer_id'] = $this->merchant_session['mer_id'];
		if($find_type == 1 && strlen($find_value) == 16){
			$appoint_where['appoint_pass'] = $find_value;
		} else {
			if($find_type == 1){
				$appoint_where['appoint_pass'] = array('LIKE', '%'.$find_value.'%');
			} else if($find_type == 2){
				$appoint_where['order_id'] = $find_value;
			} else if($find_type == 3){
				$appoint_where['appoint_id'] = $find_value;
			} else if($find_type == 4){
				$user_where['uid'] = $find_value;
			} else if($find_type == 5){
				$user_where['nickname'] = array('LIKE', '%'.$find_value.'%');
			} else if($find_type == 6){
				$user_where['phone'] = array('LIKE', '%'.$find_value.'%');
			}
		}
		if($order_id && $find_type != 2){
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
			$arr['count'] = 1;
			$arr['page'] = 1;
		}else{
			$arr['count'] = 0;
			$arr['page'] = 0;
			$arr['order_list']	=	array();
		}
		$arr['status']	=	2;
		$this->returnCode(0,$arr);
	}
	//删除预约订单
    public function appoint_del(){
    	$order_id	=	I('order_id');
        if (empty($order_id)) {
            $this->returnCode('20140050');
        }
        $database_appoint_order = D('Appoint_order');
        $where['order_id'] = $order_id;
        $data['del_time'] = time();
        $data['is_del'] = 3;
        $result = $database_appoint_order->where($where)->data($data)->save();
        if ($result) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140053');
        }
    }
    # 格式化订单数据
    protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info){
        if (!empty($user_info)) {
            $user_array = array();
            foreach ($user_info as $val) {
                $user_array[$val['uid']]['phone'] = $val['phone'];
                $user_array[$val['uid']]['nickname'] = $val['nickname'];
            }
        }
        if (!empty($appoint_info)) {
            $appoint_array = array();
            foreach ($appoint_info as $val) {
                $appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
                $appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
            }
        }
        if (!empty($store_info)) {
            $store_array = array();
            foreach ($store_info as $val) {
                $store_array[$val['store_id']]['store_name'] = $val['name'];
                $store_array[$val['store_id']]['store_adress'] = $val['adress'];
            }
        }
        if (!empty($order_info)) {
            foreach ($order_info as &$val) {
                $val['phone'] = $user_array[$val['uid']]['phone'];
                $val['nickname'] = $user_array[$val['uid']]['nickname'];
                $val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
                $val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
                $val['store_name'] = $store_array[$val['store_id']]['store_name'];
                $val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
            }
        }
        return $order_info;
    }
    # 预约工作人员列表
    public function merchant_worker(){
		//工作人员列表
        $Map['status'] = 1;
        $Map['mer_id'] = $this->merchant_session['mer_id'];
        $database_merchant_workers = D('Merchant_workers');
        $merchant_worker_list = $database_merchant_workers->where($Map)->field(array('merchant_worker_id','name'))->select();
        if(empty($merchant_worker_list)){
			$merchant_worker_list	=	array();
        }
        $this->returnCode(0,$merchant_worker_list);
    }
    # 预约更改服务人员
    public function worker(){
    	$order_id	=	I('order_id');
        if (empty($order_id)) {
            $this->returnCode('20140050');
        }
        $database_appoint_order = D('Appoint_order');
        $where['order_id'] = $order_id;
		$data['merchant_worker_id']	=	I('merchant_worker_id');
		$data['merchant_allocation_time']	=	time();
		if(empty($where)){
			$this->returnCode('20140054');
		}
		$result = $database_appoint_order->where($where)->data($data)->save();
		if($result){
			$this->returnCode(0);
		}else{
			$this->returnCode('20140028');
		}
    }
    # 订单详情
    public function order_detail(){
        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');
        $database_merchant_workers = D('Merchant_workers');
        $database_appoint_visit_order_info = D('Appoint_visit_order_info');
        $database_appoint_product = D('Appoint_product');
        $order_id = I('order_id');
        if(empty($order_id)){
			$this->returnCode('20140025');
        }
        $where['order_id'] = $order_id;
        $where['mer_id'] = $this->merchant_session['mer_id'];

        $now_order = $database_order->field(true)->where($where)->find();
        $where_user['uid'] = $now_order['uid'];
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($where_user)->find();
        $where_appoint['appoint_id'] = $now_order['appoint_id'];
        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->where($where_appoint)->find();
        $where_store['store_id'] = $now_order['store_id'];
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->where($where_store)->find();

        $now_order['phone'] = $user_info['phone'];
        $now_order['nickname'] = $user_info['nickname'];
        $now_order['appoint_name'] = $appoint_info['appoint_name'];
        $now_order['appoint_type'] = $appoint_info['appoint_type'];
        $now_order['appoint_price'] = $appoint_info['appoint_price'];
        $now_order['store_name'] = $store_info['name'];
        $now_order['store_adress'] = $store_info['adress'];


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
        $product_detail = $database_appoint_product->get_product_info($now_order['product_id']);
        if ($product_detail['status']) {
            $now_order['product_detail'] = $product_detail['detail'];
        }
		$order	=	array(
			'order_id'	=>	$now_order['order_id'],
			'store_id'	=>	$now_order['store_id'],
			'store_name'	=>	$now_order['store_name'],
			'appoint_name'	=>	$now_order['appoint_name'],
			'nickname'	=>	$now_order['nickname'],
			'phone'	=>	$now_order['phone'],
			'appoint_date'	=>	$now_order['appoint_date'],
			'appoint_time'	=>	$now_order['appoint_time'],
			'order_time'	=>	$now_order['order_time']==0?0:date('Y-m-d H:i:s',$now_order['order_time']),
			'payment_money'	=>	$now_order['payment_money'],
			'appoint_type'	=>	$now_order['appoint_type'],
			'appoint_price'	=>	$now_order['appoint_price'],
			'paid'			=>	$now_order['paid'],
			'service_status'=>	$now_order['service_status'],
			'is_del'		=>	$now_order['is_del'],
			'payment_money'	=>	$now_order['payment_money'],
			'del_time'	=>	$now_order['del_time']==0?0:date('Y-m-d H:i:s',$now_order['del_time']),
			'detail_name'	=>	isset($now_order['product_detail']['name'])?$now_order['product_detail']['name']:'',
			'detail_price'	=>	isset($now_order['product_detail']['price'])?$now_order['product_detail']['price']:'',
			'content'		=>	isset($now_order['content'])?$now_order['content']:'',
			'store_name'	=>	$now_order['store_name'],
			'tuan_type'		=>	isset($now_order['tuan_type'])?$now_order['tuan_type']:'',
			'use_time'	=>	$now_order['use_time']==0?0:date('Y-m-d H:i:s',$now_order['use_time']),
			'last_staff'	=>	$now_order['last_staff'],
			'uid'			=>	$now_order['uid'],
			'merchant_balance'=>	$now_order['merchant_balance'],
			'balance_pay'	=>	$now_order['balance_pay'],
			'pay_money'	=>	$now_order['pay_money'],
			'last_time'	=>	$now_order['last_time']==0?0:date('Y-m-d H:i:s',$now_order['last_time']),
			'longs'	=>	isset($long)?$long:0,
			'lats'	=>	isset($lat)?$lat:0,
		);
        //上门预约工作人员信息end
		$arr	=	array(
			'cue_list'	=>	$cue_list,
			'now_order'	=>	$order,
		);
		$this->returnCode(0,$arr);
    }
    # 预约格式化数据
    protected function formatArray($appoint_info, $merchant_info, $category_info){
        if (!empty($merchant_info)) {
            $merchant_array = array();
            foreach ($merchant_info as $val) {
                $merchant_array[$val['mer_id']]['mer_name'] = $val['name'];
            }
        }
        if (!empty($category_info)) {
            $category_array = array();
            foreach ($category_info as $val) {
                $category_array[$val['cat_id']]['category_name'] = $val['cat_name'];
                $category_array[$val['cat_id']]['is_autotrophic'] = $val['is_autotrophic'];
            }
        }
        if (!empty($appoint_info)) {
            foreach ($appoint_info as &$val) {
                $val['mer_name'] = $merchant_array[$val['mer_id']]['mer_name'];
                $val['category_name'] = $category_array[$val['cat_id']]['category_name'];
                $val['is_autotrophic'] = $category_array[$val['cat_id']]['is_autotrophic'];
            }
        }
        return $appoint_info;
    }
    # 店铺列表--选择使用
    public function Merchant_store($type=''){
		$store = M('Merchant_store')->field('store_id,name')->where(array('status' => 1, 'mer_id' => $this->merchant_session['mer_id']))->select();
        if ($store == false){
			$this->returnCode('20140015');
        }
        if($type == 1){
			return $store;
        }
        $this->returnCode(0,$store);
    }
	# 店员列表
	public function staff() {
		$database_merchant_store = M('Merchant_store');
        $mer_id = $this->merchant_session['mer_id'];
        $all_store = $database_merchant_store->where(array('mer_id' => $mer_id, 'status' => 1))->field('store_id,mer_id,name,status')->order('sort desc,store_id  ASC')->select();
        if (empty($all_store)) {
            $this->error_tips('店铺不存在！');
        }
        $allstore = array();
        foreach ($all_store as $vv) {
            $allstore[$vv['store_id']] = $vv;
        }

		$staffList = M('Merchant_store_staff')->where(array('token' => $mer_id))->order('`id` desc')->select();
        $arr = array();
        if (!empty($staffList)) {
            foreach ($staffList as $sv) {
            	$sv['staff_id']	=	$sv['id'];
            	$ticket = ticket::create($sv['id'], $this->DEVICE_ID, true);
            	$sv['ticket']	=	$ticket['ticket'];
                if (isset($allstore[$sv['store_id']])) {
                    $sv['storename'] = $allstore[$sv['store_id']]['name'];
                    $sv['mer_id'] = $allstore[$sv['store_id']]['mer_id'];
                    unset($sv['id'],$sv['password']);
                    $arr[] = $sv;
                }
            }
        }
		unset($staff_list, $allstore, $all_store);
		$this->returnCode(0,$arr);
    }
    # 店员添加
    public function staff_add() {
        $data['tel'] = I('tel');
        $data['name'] = I('name');
        $data['username'] = I('username');
        $data['store_id'] = I('store_id');
        $data['time'] = $_SERVER['REQUEST_TIME'];
        if(empty($data['store_id'])){
			$this->returnCode('20140048');
        }
        $data['password'] = md5(I('password'));
        $data['token'] = $this->merchant_session['mer_id'];
        $checkUserName = M('Merchant_store_staff')->where(array('username' => $data['username']))->find();
        if ($checkUserName) {
            $this->returnCode('20140017');
        }
        $sql = M('Merchant_store_staff')->add($data);
        if ($sql == false) {
            $this->returnCode('20140018');
        } else {
        	$sql['staff_id']	=	$sql['id'];
        	unset($sql['id']);
            $this->returnCode(0);
        }
    }
    # 店员修改
    public function staff_edit() {
//    	$statua		=	I('statua',2);
//        if ($statua == 1) {
            $data['tel'] = I('tel');
            $data['name'] = I('name');
            $password	=	I('password');
            if($password){
				$data['password'] = md5(I('password'));
            }
            $where['token'] = $this->merchant_session['mer_id'];
            $where['id'] = I('staff_id');
            $sql = M('Merchant_store_staff')->where($where)->save($data);
            if ($sql == false) {
                $this->returnCode('20140019');
            } else {
                $this->returnCode(0);
            }
        //} else {
//        	$id	=	I('staff_id');
//            if ($id == false){
//            	$this->returnCode('20140020');
//            }
//            $staff = M('Merchant_store_staff')->where(array('id' => $id, 'token' => $this->merchant_session['mer_id']))->find();
//            if ($staff == false){
//            	$this->returnCode('20140021');
//            }
//            $staff['staff_id']	=	$staff['id'];
//            unset($staff['token'],$staff['last_time'],$staff['time'],$staff['openid'],$staff['id']);
//            $this->returnCode(0,$staff);
//        }
    }
    # 店员删除
    public function staff_dell() {
        $id = I('staff_id');
        if ($id == false)
            $this->returnCode('20140020');
        $staff = M('Merchant_store_staff')->where(array('id' => $id, 'token' => $this->merchant_session['mer_id']))->delete();
        if ($staff == false) {
            $this->returnCode('20140022');
        } else {
            $this->returnCode(0);
        }
    }
	# 打印机设备列表
	public function hardware() {
        $staffList = M('Orderprinter')->where(array('mer_id' => $this->merchant_session['mer_id']))->select();
        foreach($staffList as &$v){
			$store = M('Merchant_store')->field('name')->where(array('store_id'=>$v['store_id']))->find();
			$v['store_name']	=	$store['name'];
        }
        if(empty($staffList)){
			$staffList	=	array();
        }
        $this->returnCode(0,$staffList);
    }
	# 添加和修改打印机
	public function hardware_add() {
		$status	=	I('status',2);
        if ($status == 1) {
            $data['mcode']		=	I('mcode');
            $data['username']	=	I('username');
            $data['mkey']		=	I('mkey');
            $data['mp']			=	I('mp');
            $data['count']		=	I('count');
            $data['paid']		=	I('paid');
            $data['store_id']	=	I('store_id');
            $data['mer_id']		=	$this->merchant_session['mer_id'];
			$pigcms_id			=	I('pigcms_id');
			if($pigcms_id >0){
            	$sql = M('Orderprinter')->where(array('pigcms_id'=>$pigcms_id,'mer_id'=>$data['mer_id']))->save($data);
			}else{
				$sql = M('Orderprinter')->add($data);
			}
            if ($sql == false) {
            	if($pigcms_id){
					$this->returnCode('20140014');
            	}else{
					$this->returnCode('20140013');
            	}
            } else {
            	$this->returnCode(0,$pigcms_id>0?'修改成功':'添加成功');
            }
        } else {
			$pigcms_id=I('pigcms_id');
			if($pigcms_id>0){
			   $Orderprinter	=	M('Orderprinter')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->find();
			   if(empty($Orderprinter)){
				   $Orderprinter	=	array();
			   }
			}else{
			   $pigcms_id=0;
			   $Orderprinter=array();
			}
			$this->returnCode(0,$Orderprinter);
        }
    }
    # 删除打印机
    public function hardware_dell() {
    	$pigcms_id	=	I('pigcms_id');
        if ($pigcms_id == false)
            $this->error_tips('非法操作');
        $staff = M('Orderprinter')->where(array('pigcms_id' => $pigcms_id, 'mer_id' => $this->merchant_session['mer_id']))->delete();
        if ($staff !== false) {
            $this->returnCode(0);
        } else {
            $this->returnCode('20140016');
        }
    }
    # 换取星期
	protected function get_week($num) {
        switch ($num) {
            case 1:
                return '星期一';
            case 2:
                return '星期二';
            case 3:
                return '星期三';
            case 4:
                return '星期四';
            case 5:
                return '星期五';
            case 6:
                return '星期六';
            case 0:
                return '星期日';
            default:
                return '';
        }
    }
    # 订单配送时间
    protected function order_distribution($num) {
        switch ($num) {
            case 1:
                return '工作日、双休日与假日均可送货';
            case 2:
                return '只工作日送货';
            case 3:
                return '只双休日、假日送货';
            case 4:
                return '白天没人，其它时间送货';
            default:
                return '';
        }
    }
    # 验证ticket
    private function ticket() {
    	$ticket = I('ticket', false);
		if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            if (!$info) {
                $this->returnCode('20140012');
            }
        }else{
			$this->returnCode('20140011');
        }
        return $info['uid'];
	}
	# 获取省市区
	public function select_area(){
		$pid	=	I('pid',0);
		$city	=	$this->select_area_array($pid);
		$area_type	=	I('area_type',1);
		if(!empty($city)){
			if($area_type == 1){
				$arr = isset($city[0])?$this->del_field($city[0],2):array();
			}else if($area_type){
				$city_list =	M('Area')->where(array('area_pid'=>$pid,'is_open'=>1))->find();
				if($city_list){
					$city_p =	M('Area')->where(array('area_id'=>$city_list['area_pid'],'is_open'=>1))->find();
					if($city_p){
						$city_pp = D('Area')->get_arealist_by_areaPid($city_p['area_pid'],1);
						if($city_pp){
							$arr =	$this->del_field($city_pp,1);
						}else{
							$arr =	$this->del_field($city_p,1);
						}
					}else{
						$arr[] =	$this->del_field($city_list,1);
					}
				}else{
					$arr =	array();
				}
			}
			$this->returnCode(0,$arr);
		}else{
			$this->returnCode('20046027');
		}
	}
	# 获取省市区
	private function select_area_array($pid){
		$area_list[] = D('Area')->get_arealist_by_areaPid($pid,1);
		if($area_list){
			if($area_list[0][0]['area_type'] == 3){
				return $area_list;
			}else{
				$area_list[] = D('Area')->get_arealist_by_areaPid($area_list[0][0]['area_id'],1);
				return $area_list;
			}
		}else{
			return null;
		}
	}
	# 删除多余字段
	private function del_field($arr,$type){
		if($type == 2){
			foreach($arr as &$v){
				unset($v['area_sort'],$v['first_pinyin'],$v['is_open'],$v['area_url'],$v['area_ip_desc'],$v['area_type'],$v['is_hot'],$v['url']);
			}
		}else if($type == 1){
			unset($arr['area_sort'],$arr['first_pinyin'],$arr['is_open'],$arr['area_url'],$arr['area_ip_desc'],$arr['area_type'],$arr['is_hot'],$arr['url']);
		}
		return $arr;
	}
	# 图片上传
	public function up_img(){
		if ($_FILES['imgFile']['error'] != 4) {
			$store_id = I('store_id');
        	$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
        	$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
        	if ($store_theme) {
        		$width = '900,450';
        		$height = '900,450';
        	} else {
        		$width = '900,450';
        		$height = '500,250';
        	}
			$param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
			$image = D('Image')->handle($this->merchant_session['mer_id'], 'goods', 1, $param);
			if ($image['error']) {
				$this->returnCode('20140052');
			} else {
				$arr	=	array(
					'url'	=>	$this->config['site_url'].$image['url']['file'],
					'title'	=>	$image['title']['file'],
				);
				$this->returnCode(0,$arr);
			}
		} else {
			$this->returnCode('20140051');
		}
	}

	//商家余额接口
	public function merchant_money_info(){
		$mer_id  = $this->merchant_session['mer_id'];

		$type = !empty($_POST['type'])?$_POST['type']:'group';
		//$arr['type'] = $this->get_alias_name();
		$now_mer = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
		$arr['merchant_money'] = $now_mer['money'];            //商家余额
		$arr['store_list'] = $this->get_store_name($mer_id,$type);
		$this->returnCode(0,$arr);
	}

	//获取业务类型
	public  function get_alias_name(){
		$arr[]='group';
		$arr[]='shop';
		$arr[]='group';
		$arr[]='meal';
		if(C('config.appoint_page_row')>0){
			$arr[]='appoint';
		}
		if(C('config.is_cashier')	||C('config.pay_in_store')){
			$arr[]='store';
		}
		if(C('config.is_open_weidian')){
			$arr[]='weidian';
		}
		if(C('config.wxapp_url')){
			$arr[]='wxapp';
		}
		return $arr;
	}

	//业务类型中文
	public  function get_alias_c_name(){
		$arr[]=array('type'=>'group','name'=>$this->config['group_alias_name']);
		$arr[]=array('type'=>'shop','name'=>$this->config['shop_alias_name']);
		$arr[]=array('type'=>'meal','name'=>$this->config['meal_alias_name']);
		if(C('config.appoint_page_row')>0) {
			$arr[] = array('type' => 'appoint', 'name' => $this->config['appoint_alias_name']);
		}
		if(C('config.is_cashier')	||C('config.pay_in_store')) {
			$arr[] = array('type' => 'store', 'name' => '到店');
		}
		if(C('config.is_open_weidian')) {
			$arr[] = array('type' => 'weidian', 'name' => '微店');
		}
		if(C('config.wxapp_url')) {
			$arr[] = array('type' => 'wxapp', 'name' => '营销');
		}
		$arr[]=array('type'=>'withdraw','name'=>'提现');
		$arr[]=array('type'=>'activity','name'=>'平台活动');
		return $arr;
	}

	/**
	 * @return 选择分类
     */
	public  function get_alias_c_name2(){
		return array(
				'all'=>'选择分类',
				'group'=>$this->config['group_alias_name'],
				'shop'=>$this->config['shop_alias_name'],
				'meal'=>$this->config['meal_alias_name'],
				'appoint'=>$this->config['appoint_alias_name'],
				'waimai'=>$this->config['waimai_alias_name'],
				'store'=>'到店',
				'weidian'=>'微店',
				'wxapp'=>'营销',
				'withdraw'=>'提现',
				'coupon'=>'提现',
				'withdraw'=>'提现',
				'activity'=>'平台活动',
				'spread'=>'商家推广佣金'
		);
	}

	public function get_store_name($mer_id,$type){
		$store_list = D('Merchant_store')->field('store_id,name,have_group,have_meal,have_shop')->where(array('mer_id'=>$mer_id))->select();
		if($type=='group'){
			foreach($store_list as $key=>$value){
				if(empty($value['have_group'])){
					unset($store_list[$key]);
				}
			}
		}else if($type=='meal'){
			foreach($store_list as $key=>$value){
				if(empty($value['have_meal'])){
					unset($store_list[$key]);
				}
			}
		}else if($type=='shop'){
			foreach($store_list as $key=>$value){
				if(empty($value['have_shop'])){
					unset($store_list[$key]);
				}
			}
		}else{
			$store_list=array();
		}
		$arr=array();
		foreach($store_list as $key=>$value){
			unset($store_list[$key]['have_group']);
			unset($store_list[$key]['have_meal']);
			unset($store_list[$key]['have_shop']);
			$arr[]=$store_list[$key];
		}
		return $arr;
	}

	//获取商家余额按时间统计数据
	public function merchant_money_date(){
		$mer_id = intval($this->merchant_session['mer_id']);
		$store_id = $_POST['store_id'];
		$day  = $_POST['day'];
		$period = false;
		if(isset($_POST['period'])&&!empty($_POST['period'])){
			$period = explode('-',$_POST['period']);
			$_POST['begin_time'] = $period[0];
			$_POST['end_time'] = $period[1];
			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->returnCode('20140055'); //##
			}
			$period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
			if($_POST['store_id']){
				$time_condition = " (l.use_time BETWEEN ".$period[0].' AND '.$period[1].")";
			}else{
				$time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
			}
			$condition_merchant_request['_string']=$time_condition;
			$period = true;
		}

		if(isset($_POST['type'])&&!empty($_POST['type'])){
			$type=$_POST['type'];
			if($type=='activity'){
				$condition_merchant_request['type'] = 'coupon or yydb';
			}else{
				$condition_merchant_request['type'] = $type;
			}
		}else{
			$type='group';
			$condition_merchant_request['type'] = $type;
		}
		if($_POST['store_id']!=0&&$type!='wxapp'&&$type!='activity'){
			foreach($condition_merchant_request as $k=>$v){
				if($k != '_string'){
					$condition_merchant_request['l.'.$k] = $v;
					unset($condition_merchant_request[$k]);
				}
			}
			$condition_merchant_request['o.store_id'] = $_POST['store_id'];
		}
		$now_time = $_SERVER['REQUEST_TIME'];
		$today_zero_time = mktime(0,0,0,date('m',$now_time),date('d',$now_time), date('Y',$now_time));

		if(empty($day)){
			$day =2;
		}
		if($day < 1){
			$this->returnCode('20140056');
		}
		if($day==1&&!$period){
			if(!empty($store_id)){
				$condition_merchant_request['l.use_time'] = array(array('egt',$today_zero_time),array('elt',$now_time));
			}else{
				$condition_merchant_request['use_time'] = array(array('egt',$today_zero_time),array('elt',$now_time));
			}

			if(!empty($store_id)){
				$request_list = M('Merchant_money_list l')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join(C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
			}else{
				$condition_merchant_request['mer_id'] =$mer_id;
				$request_list = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
			}
		}else{
			if(!$period) {
				if ($day == 2) {
					//本月
					$today_zero_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
					if (!empty($store_id)) {
						$condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time), array('elt', $now_time));
					} else {
						$condition_merchant_request['use_time'] = array(array('egt', $today_zero_time), array('elt', $now_time));

					}
				} else {
					if (!empty($store_id)) {
						$condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time - (($day - 1) * 86400)), array('elt', $today_zero_time));
					} else {
						$condition_merchant_request['use_time'] = array(array('egt', $today_zero_time - (($day) * 86400)), array('elt', $now_time));
					}
				}
			}
			if(!empty($store_id)){
				$request_list = M('Merchant_money_list l')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join(C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
			}else{
				$condition_merchant_request['mer_id'] = $mer_id;
				$request_list = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
			}
		}
		$tmp_array=array();
		if(($day==1&&!$period)||($period&&($_POST['end_time']==$_POST['begin_time']))){
			foreach($request_list as $value){
				$tmp_time = date('H',$value['use_time']);
				if($tmp_array[$tmp_time][$value['type']]['count']){
					$tmp_array[$tmp_time][$value['type']]['count']=1;
				}else{
					$tmp_array[$tmp_time][$value['type']]['count']++;
				}
				if($value['income']==1){
					$tmp_array[$tmp_time][$value['type']]['income'] += $value['money'];
				}else{
					$tmp_array[$tmp_time][$value['type']]['expend'] += $value['money'];
				}
			}
		}else{
			foreach($request_list as $value){
				if($day==2&&!$period){
					$tmp_time = date('d',$value['use_time']);
				}else{
					$tmp_time = date('ymd',$value['use_time']);
				}
				if(empty($tmp_array[$tmp_time][$value['type']]['count'])){
					$tmp_array[$tmp_time][$value['type']]['count']=1;
				}else{
					$tmp_array[$tmp_time][$value['type']]['count']++;
				}
				if($value['income']==1){
					$tmp_array[$tmp_time][$value['type']]['income'] += $value['money'];
				}else{
					$tmp_array[$tmp_time][$value['type']]['expend'] += $value['money'];
				}
			}
		}

		ksort($tmp_array);
		$alias_name = $this->get_alias_name();
		if(($day==1&&!$period)||($period&&($_POST['end_time']==$_POST['begin_time']))){
			for($i=0;$i<=date('H',$now_time);$i++){
				$pigcms_list['xAxis_arr'][]  = $i.'时';
				$time_arr[]=$i;
			}
		}else{
			if($day==2){
				$days = date('d',$now_time);
				for($i=1;$i<=$days;$i++){
					$pigcms_list['xAxis_arr'][]  = $i.'日';
					$time_arr[]=$i;
				}
			}else{
				for($i=$day-1;$i>=0;$i--){
					$pigcms_list['xAxis_arr'][]  = date('y-m-d',$today_zero_time-$i*86400);
					$time_arr[]=date('ymd',$today_zero_time-$i*86400);
				}
			}
		}
		if($period){
			unset($pigcms_list['xAxis_arr']);
			unset($time_arr);
			$start_day =strtotime($_POST['end_time']);
			$day = (strtotime($_POST['end_time'])-strtotime($_POST['begin_time']))/86400;
			if($day==0){
				for($i=0;$i<24;$i++){
					$pigcms_list['xAxis_arr'][]  = $i.'时';
					$time_arr[]=$i;
				}
			}else{
				for($i=$day;$i>=0;$i--){
					$pigcms_list['xAxis_arr'][]  = date('y-m-d',$start_day-$i*86400);
					$time_arr[]=date('ymd',$start_day-$i*86400);
				}
			}
		}
		$no_data_time= array();
		foreach($time_arr as $v){
			if($tmp_array[$v]){
					$pigcms_list['income'][] = floor($tmp_array[$v][$type]['income']);
					$pigcms_list['income_all'] += floor($tmp_array[$v][$type]['income']);
					$pigcms_list['order_count'][]   = intval($tmp_array[$v][$type]['count']);
					$pigcms_list['order_count_all'] += intval($tmp_array[$v][$type]['count']);
			}else{
				if(!in_array($v,$no_data_time)){
					$pigcms_list['income'][] = 0;
					$pigcms_list['order_count'][]   = 0;
				}
			}
		}
		$this->returnCode(0,$pigcms_list);
	}

	//商家收入
	public function get_income_list(){

		$_GET['page'] = $_POST['page'];
		$mer_id = intval($this->merchant_session['mer_id']);
		if(!empty($_POST['store_id'])){
			$condition['store_id'] = $_POST['store_id'];
		}

		if($_POST['type']!='all'&&!empty($_POST['type'])){
			$condition['type'] = $_POST['type'];
		}
		if(isset($_POST['period'])&&!empty($_POST['period'])){
			$period = explode('-',$_POST['period']);
			$_POST['begin_time'] = $period[0];
			$_POST['end_time'] = $period[1];

			if ($_POST['begin_time']>$_POST['end_time']) {
				$this->returnCode('20140055');
			}
			$period = $_POST['begin_time']==$_POST['end_time']?array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['begin_time']." 23:59:59")):array(strtotime($_POST['begin_time']),strtotime($_POST['end_time']));
			$time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
			$condition['_string']=$time_condition;
		}
		$res = D('Merchant_money_list')->get_income_list($mer_id,0,$condition);
		$alias_name = $this->get_alias_c_name2();
		$income_list = array();
		foreach ( $res['income_list'] as $inc) {
			$arr=array();
			$arr['id']=$inc['id'];
			if($inc['store_id']>0){
				$arr['store_name'] = $inc['store_name'];
			}else{
				$arr['store_name'] = '';
			}
			$arr['type'] = $inc['type'];
			$arr['type_name'] = $alias_name[$inc['type']];
			$arr['desc'] = str_replace('</br>','',$inc['desc']);
			$arr['money'] = strval(pow(-1,($inc['income']+1))*$inc['money']);
			$income_list[]=$arr;
		}
		$this->returnCode(0,array('income_list'=>$income_list,'page_num'=>$res['page_num']));

	}

	//商家提现记录
	public function withdraw_list(){
		$mer_id = intval($this->merchant_session['mer_id']);
		$_GET['page'] = $_POST['page'];
		$res = D('Merchant_money_list')->get_withdraw_list($mer_id);

		$withdraw=array();
		foreach($res['withdraw_list'] as $v){
			$arr = array();
			$arr['id'] = $v['id'];
			$arr['time'] = date('Y/m/d H:i:s',$v['withdraw_time']);
			$arr['money'] = strval($v['money']/100);
			if($v['status']==0){
				$arr['status'] = '待审核';
			}elseif($v['status']==1){
				$arr['status'] = '已通过';
			}elseif($v['status']==2){
				$arr['status'] = '被驳回';
			}
			$arr['remark'] = $v['remark'];
			$withdraw[]=$arr;
		}
		$return['withdraw']=$withdraw;
		$return['page_num']=$res['page_num'];
		$this->returnCode(0,$return);
	}

	//提现记录详情
	public function withdraw_info(){
		$withdraw = M('Merchant_withdraw')->where(array('id'=>$_POST['id']))->find();
		$withdraw['name'] = $this->merchant_session['name'];
		return $withdraw;
	}

	public function income_info(){
		$id = $_POST['id'];
		$res = M('Merchant_money_list')->where(array('id'=>$id))->find();
		if(empty($res)){
			$this->returnCode('20140064');
		}

		$type = $res['type'];
		$alias_name = $this->get_alias_c_name2();
		$mer_id = $this->merchant_session['mer_id'];
		$merchant = M('Merchant')->field(true)->where(array('mer_id '=> $mer_id))->find();
		$arr['percent'] =  $res['percent'];

		$order_id = $res['order_id'];
		$income[]=array('name'=>'订单编号','value'=>$res['order_id']);
		$income[]=array('name'=>'数量','value'=>$res['num']);
		if($type!='withdraw'){
			$income[]=array('name'=>'平台抽佣比例'.$arr['percent'].'%','value'=>$res['system_take']);
		}
		$income[]=array('name'=>'当前商家余额','value'=>strval($res['now_mer_money']));
		if($type!='withdraw'){
			$income[]=array('name'=>'支付时间','value'=>date('Y/m/d H:i:s',$res['use_time']));
		}else{
			$income[]=array('name'=>'提现时间','value'=>date('Y/m/d H:i:s',$res['use_time']));
		}
		if($type=='withdraw'){
			$income[]=array('name'=>'总额','value'=>strval($res['money']));
		}else{
			$income[]=array('name'=>'总额','value'=>strval($res['total_money']));
		}
		$income[]=array('name'=>'描述','value'=>$res['desc']);
		$income[]=array('name'=>'类型','value'=>$alias_name[$res['type']]);

		switch($type){
			case 'group':
				$where['real_orderid']=$order_id;
				$order = M('Group_order')->where($where)->find();
				$pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				$income[]=array('name'=>'支付方式','value'=>$pay_method);
				$income[]=array('name'=>'订单流水','value'=>$order['orderid']);
				break;
			case 'meal':
				$where['order_id']=$order_id;
				$order = M('Meal_order')->where($where)->find();
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				$pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
				$income[]=array('name'=>'支付方式','value'=>$pay_method);
				$income[]=array('name'=>'订单流水','value'=>$order['orderid']);
				break;
			case 'shop':
				$where['real_orderid']=$order_id;
				$order = M('Shop_order')->where($where)->find();
				$income[]=array('name'=>'手机号码','value'=>$order['userphone']);
				$pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
				$income[]=array('name'=>'支付方式','value'=>$pay_method);
				$income[]=array('name'=>'订单流水','value'=>$order['orderid']);
				break;
			case 'appoint':
				$where['order_id']=$order_id;
				$order = M('Appoint_order')->where($where)->find();
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				$pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
				$income[]=array('name'=>'支付方式','value'=>$pay_method);
				$income[]=array('name'=>'订单流水','value'=>$order['orderid']);
				$income[]=array('name'=>'预约时间','value'=>$order['appoint_date'].'/'.$order['appoint_time']);
				break;
			case 'store':
				$where['order_id']=$order_id;
				$order = M('Store_order s')->field(true)->join(C('DB_PREFIX').'user u ON s.uid = u.uid ')->where($where)->find();
				$pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				$income[]=array('name'=>'支付方式','value'=>$pay_method);
				$income[]=array('name'=>'订单流水','value'=>$order['orderid']);
				break;
			case 'wxapp':
				$where['order_id']=$order_id;
				$order = M('Wxapp_order w')->field(true)->join(C('DB_PREFIX').'user u ON w.uid = u.uid ')->where($where)->find();
				$pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				$income[]=array('name'=>'支付方式','value'=>$pay_method);
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				$income[]=array('name'=>'订单流水','value'=>$order['orderid']);
				break;
			case 'weidian':
				$where['order_id']=$order_id;
				$order = M('Weidian_order w')->field(true)->join(C('DB_PREFIX').'user u ON w.uid = u.uid ')->where($where)->find();
				$pay_method = D('Pay')->get_pay_name($order['pay_type'],0);
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				$income[]=array('name'=>'支付方式','value'=>$pay_method);
				$income[]=array('name'=>'订单流水','value'=>$order['orderid']);
				break;
			case 'coupon':
				$condition_table = array(C('DB_PREFIX').'extension_activity_list'=>'eal',C('DB_PREFIX').'extension_activity_record'=>'ear',C('DB_PREFIX').'extension_coupon_record'=>'ecr',C('DB_PREFIX').'user'=>'u');
				$condition_where = "`ear`.`activity_list_id`=`eal`.`pigcms_id` AND `ecr`.`record_id`=`ear`.`pigcms_id` AND `eal`.`mer_id`='{$mer_id}' AND `ecr`.`pigcms_id`='{$order_id}' AND `ear`.`uid`=`u`.`uid`";
				$order= D('')->field('`ecr`.`pigcms_id`,`ecr`.`number`,`eal`.`title`,`ear`.`uid`,`eal`.`pigcms_id` as id,`eal`.`money`,`eal`.`name`,`u`.`nickname`,`u`.`phone`,`ecr`.`check_time`,`ecr`.`last_staff`')->where($condition_where)->table($condition_table)->find();
				$income[]=array('name'=>'优惠券码','value'=>$order['number']);
				$income[]=array('name'=>'消费者','value'=>$order['nickname']);
				$income[]=array('name'=>'手机号码','value'=>$order['phone']);
				break;
			case 'yydb':
				$where['pigcms_id'] = $order_id;
				$order = M('Extension_activity_list e')->field(true)->join(C('DB_PREFIX').'user u ON e.lottery_uid = u.uid ')->where($where)->find();
				$income[] = array('name'=>'项目名称','value'=>$order['title']);
				$income[] = array('name'=>'提现商家','value'=>$this->merchant_session['name']);
				$income[] = array('name'=>'夺宝用户','value'=>$order['nickname']);
				$income[] = array('name'=>'夺宝用户','value'=>$order['phone']);
				break;
			case 'withdraw':
				$order = M('Merchant_withdraw')->where(array('id'=>$_POST['id']))->find();
				$income[] = array('name'=>'提现人','value'=>$order['name']);
				$income[] = array('name'=>'提现商家','value'=>$this->merchant_session['name']);
			break;
		}

		$this->returnCode(0,array('income_info'=>$income));

	}
	//提现
	public function withdraw(){
		if($this->config['company_pay_open']=='0') {
			$this->returnCode('20140059');
		}
		$mer_id = intval($this->merchant_session['mer_id']);
		$now_merchant = M('Merchant')->where(array('mer_id'=>$mer_id))->find();

		if(M('Merchant_withdraw')->where(array('mer_id'=>$mer_id,'status'=>0))->find()){
			$this->returnCode('20140060');
		}
		if($_POST['money']){
			if($_POST['money']>$now_merchant['money']){
				$this->returnCode('20140061');
			}
			$money = floatval(($_POST['money']))*100;
			if($_POST['money']<$this->config['min_withdraw_money']){
				$this->returnCode('20140062');
			}
			$res = D('Merchant_money_list')->withdraw($mer_id,$_POST['name'],$money,$_POST['remark']);
			if($res['error_code']){
				$this->returnCode('20140063');
			}else{
				$this->returnCode(0);
			}
		}else{
			$this->returnCode('20045014');
		}
	}
}
?>
