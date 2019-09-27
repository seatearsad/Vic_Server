<?php
/*
 * 商户管理
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/07 14:45
 *
 */

class MerchantAction extends BaseAction{
    public function index(){
		$database_merchant = D('Merchant');
		//搜索
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'mer_id'){
				$condition_merchant['mer_id'] = $_GET['keyword'];
			}else if($_GET['searchtype'] == 'account'){
				$condition_merchant['account'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'name'){
				$condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'phone'){
				$condition_merchant['phone'] = array('like','%'.$_GET['keyword'].'%');
			}
		}
		$searchstatus = intval($_GET['searchstatus']);
		switch($searchstatus){
			case 0:
				//3 是欠款状态 该状态下商户业务暂停，不能支付，但是可以管理，充值
				$condition_merchant['_string'] = 'status = 1 OR status = 3';
				break;
			case 1:
				$condition_merchant['status'] = 2;
				break;
			case 2:
				$condition_merchant['status'] = 0;
				break;
		}

		switch($_GET['searchorder']){
			case 0:
				$order = 'mer_id DESC';
				break;
			case 1:
				$order = 'money DESC,mer_id DESC';
				break;

		}
        if($_GET['city_id']){
            $this->assign('city_id',$_GET['city_id']);
            if($_GET['city_id'] != 0){
                $condition_merchant['city_id'] = $_GET['city_id'];
            }
        }else{
            $this->assign('city_id',0);
        }
        $city = D('Area')->where(array('area_type'=>2))->select();
        $this->assign('city',$city);

		if ($this->system_session['area_id']) {
			$area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
			$condition_merchant[$area_index] = $this->system_session['area_id'];
		}
		$mer_withdraw_list = D('Merchant_money_list');
		$all_money = $mer_withdraw_list->get_all_mer_money();
		$this->assign('all_money',$all_money);

		$count_merchant = $database_merchant->where($condition_merchant)->count();
		import('@.ORG.system_page');
		$p = new Page($count_merchant,15);
		$merchant_list = $database_merchant->field(true)->where($condition_merchant)->order($order)->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('merchant_list',$merchant_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		$this->display();
    }
	public function wait_merchant(){
		$condition_merchant['status'] = 2;
		//搜索
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'mer_id'){
				$condition_merchant['mer_id'] = $_GET['keyword'];
			}else if($_GET['searchtype'] == 'account'){
				$condition_merchant['account'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'name'){
				$condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'phone'){
				$condition_merchant['phone'] = array('like','%'.$_GET['keyword'].'%');
			}
		}
		if ($this->system_session['area_id']) {
			$area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
			$condition_merchant[$area_index] = $this->system_session['area_id'];
		}
		$database_merchant = D('Merchant');

		$count_merchant = $database_merchant->where($condition_merchant)->count();
		import('@.ORG.system_page');
		$p = new Page($count_merchant,15);
		$merchant_list = $database_merchant->field(true)->where($condition_merchant)->order('`mer_id` DESC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('merchant_list',$merchant_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		$this->display();
    }
	public function add(){
		$this->assign('bg_color','#F3F3F3');
		$city_list = D('Area')->where(array('area_type'=>2))->order('`area_sort` asc')->select();
		$this->assign('city',$city_list);
		$this->display();
	}
	public function modify(){
		if(IS_POST){
			$_POST['account'] = trim($_POST['account']);
			$_POST['pwd'] = md5($_POST['pwd']);
			$_POST['reg_time'] = $_SERVER['REQUEST_TIME'];
			$_POST['reg_ip'] = get_client_ip(1);
			$_POST['from'] = '1';
			//$_POST['area_id'] = $this->system_session['area_id'];
            //garfunkel add
            $area = D('Area')->where(array('area_id'=>$_POST['city_id']))->find();
            $_POST['province_id'] = $area['area_pid'];
            $_POST['area_id'] = 0;
			if($_POST['merchant_end_time']){
				$_POST['merchant_end_time'] = strtotime($_POST['merchant_end_time']);
			}else{
				$_POST['merchant_end_time'] = '0';
			}
                        //$_POST['is_new_auth'] = 1;
			$database_merchant = D('Merchant');
			if ($database_merchant->field(true)->where(array('account' => htmlspecialchars($_POST['account'])))->find()) {
				$this->error('账号已存在，请更换！');
			}
			if($insert_id=$database_merchant->data($_POST)->add()){
				D('Scroll_msg')->add_msg('mer_reg',$insert_id,'商家'.$_POST['name'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '注册成功！');
				M('Merchant_score')->add(array('parent_id'=>$insert_id,'type'=>1));
				//添加分佣比例记录
				M('Merchant_percent_rate')->add(array('mer_id'=>$insert_id));
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function edit(){
		$this->assign('bg_color','#F3F3F3');

		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = intval($_GET['mer_id']);
		$merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($merchant['merchant_end_time'])){
			$merchant['merchant_end_time'] = '';
		}else{
			$merchant['merchant_end_time'] = date('Y-m-d H:i',$merchant['merchant_end_time']);
		}
		if(empty($merchant)){
			$this->frame_error_tips('数据库中没有查询到该商户的信息！');
		}

		if(empty($merchant['city_id'])){
			$now_area = D('Area')->field('`area_pid`')->where(array('area_id'=>$merchant['area_id']))->find();
			$merchant['city_id'] = $now_area['area_pid'];
		}
		$now_city = D('Area')->field('`area_pid`')->where(array('area_id'=>$merchant['city_id']))->find();
		$merchant['province_id'] = $now_city['area_pid'];

		$this->assign('merchant',$merchant);

		$home_share = D('Home_share')->where(array('mer_id' => $condition_merchant['mer_id']))->find();
		$this->assign('home_share', $home_share);

        $city_list = D('Area')->where(array('area_type'=>2))->order('`area_sort` asc')->select();
        $this->assign('city',$city_list);

		$this->display();
	}

	public function amend(){
		if(IS_POST){
			if($_POST['pwd']){
				$_POST['pwd'] = md5($_POST['pwd']);
			}else{
				unset($_POST['pwd']);
			}
			if($_POST['merchant_end_time']){
				$_POST['merchant_end_time'] = strtotime($_POST['merchant_end_time']);
			}else{
				$_POST['merchant_end_time'] = '0';
			}
    		$data['a_href'] = isset($_POST['a_href']) && $_POST['a_href'] ? htmlspecialchars_decode($_POST['a_href']) : $this->config['site_url'] . '/wap.php?c=Index&a=index&token=' . $_POST['mer_id'];
    		$data['a_name'] = isset($_POST['a_name']) && $_POST['a_name'] ? htmlspecialchars($_POST['a_name']) : '进入';
    		$data['title'] = isset($_POST['a_title']) && $_POST['a_title'] ? htmlspecialchars($_POST['a_title']) : '您是' . $_POST['name'] . '的粉丝';
    		unset($_POST['a_name'], $_POST['a_title'], $_POST['a_href']);

            //garfunkel add
            $area = D('Area')->where(array('area_id'=>$_POST['city_id']))->find();
            $_POST['province_id'] = $area['area_pid'];
            $_POST['area_id'] = 0;

			$database_merchant = D('Merchant');
			$database_merchant->data($_POST)->save();
// 			if(){
			$home_share = D('Home_share')->where(array('mer_id' => $_POST['mer_id']))->find();
			if ($home_share) {
				D('Home_share')->where(array('mer_id' => $_POST['mer_id']))->save($data);
			} else {
				$data['mer_id'] = $_POST['mer_id'];
				D('Home_share')->add($data);
			}
			if($_POST['is_open_scenic']){
				$scenic_list	=	M('Scenic_list')->where(array('company_id'=>$_POST['mer_id']))->find();
				if(empty($scenic_list)){
					$arr	=	array(
						'company_id'	=>	$_POST['mer_id'],
						'add_time'		=>	$_SERVER['REQUEST_TIME'],
						'update_time'	=>	$_SERVER['REQUEST_TIME'],
					);
					$add	=	M('Scenic_list')->data($arr)->add();
					if(empty($add)){
						$this->error('开通景区失败，请联系管理员！');
					}
				}
			}
			$this->success('Success');
// 			}else{
// 				$this->error('修改失败！请检查内容是否有过修改（必须修改）后重试~');
// 			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function del(){
		if(IS_POST){
			$database_merchant_store = D('Merchant_store');
			$condition_merchant_store['mer_id'] = intval($_POST['mer_id']);
			if($database_merchant_store->where($condition_merchant_store)->find()){
				$this->error('商家存在店铺，请先清空店铺！');
			}
			$database_merchant = D('Merchant');
			$condition_merchant['mer_id'] = intval($_POST['mer_id']);
			if($database_merchant->where($condition_merchant)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function merchant_login(){
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = $_GET['mer_id'];
		$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($now_merchant) || $now_merchant['status'] == 0 || $now_merchant['status'] == 2){
			exit('<html><head><script>window.top.toggleMenu(0);window.top.msg(0,"该商户的状态不存在！请查阅。",true,5);window.history.back();</script></head></html>');
		}
		if(!empty($now_merchant['last_ip'])){
			import('ORG.Net.IpLocation');
			$IpLocation = new IpLocation();
			$last_location = $IpLocation->getlocation(long2ip($now_merchant['last_ip']));
			$now_merchant['last']['country'] = iconv('GBK','UTF-8',$last_location['country']);
			$now_merchant['last']['area'] = iconv('GBK','UTF-8',$last_location['area']);
		}
		session('merchant',$now_merchant);
		$script_name = trim($_SERVER['SCRIPT_NAME'],'/');
		if($_GET['group_id']){
			redirect($this->config['site_url'].'/merchant.php?c=Group&a=frame_edit&group_id='.$_GET['group_id'].'&system_file='.$script_name);
		}else if($_GET['activity_id']){
			redirect($this->config['site_url'].'/merchant.php?c=Activity&a=frame_edit&id='.$_GET['activity_id'].'&system_file='.$script_name);
		}else if($_GET['appoint_id']){
			redirect($this->config['site_url'].'/merchant.php?c=Appoint&a=frame_edit&appoint_id='.$_GET['appoint_id'].'&system_file='.$script_name);
		}else{
			redirect($this->config['site_url'].'/merchant.php');
		}
	}
	/*店铺管理*/
	public function store(){
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = intval($_GET['mer_id']);
		$merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($merchant)){
			$this->error_tips('数据库中没有查询到该商户的信息！',5,U('Merchant/index'));
		}
		$this->assign('merchant',$merchant);

		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['mer_id'] = $merchant['mer_id'];
// 		$condition_merchant_store['status'] = array('neq',4);
		$count_store = $database_merchant_store->where($condition_merchant_store)->count();
		import('@.ORG.system_page');
		$p = new Page($count_store,15);
		$store_list = $database_merchant_store->field(true)->where($condition_merchant_store)->order('`sort` DESC,`store_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('store_list',$store_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		$this->display();
	}

	public function wait_store(){
		$where = array('status' => 2);
		//搜索
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'store_id'){
				$where['store_id'] = $_GET['keyword'];
			}else if($_GET['searchtype'] == 'name'){
				$where['name'] = array('like','%'.$_GET['keyword'].'%');
			}else if($_GET['searchtype'] == 'phone'){
				$where['phone'] = array('like','%'.$_GET['keyword'].'%');
			}
		}
		if ($this->system_session['area_id']) {
			$area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
			$where[$area_index] = $this->system_session['area_id'];
		}
		$database = D('Merchant_store');
		$count = $database->where($where)->count();
		import('@.ORG.system_page');
		$p = new Page($count, 15);
		$list = $database->field(true)->where($where)->order('`store_id` DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
		$this->assign('store_list', $list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);

		$this->display();
    }
	public function store_add(){
		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = intval($_GET['mer_id']);
		$merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($merchant)){
			$this->frame_error_tips('数据库中没有查询到该商户的信息！无法添加店铺。',5);
		}
		$this->assign('merchant',$merchant);

		$this->assign('bg_color','#F3F3F3');

        $city_list = D('Area')->where(array('area_type'=>2))->order('`area_sort` asc')->select();
        $this->assign('city',$city_list);

		$this->display();
	}
	public function store_modify(){
		if(IS_POST){
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['long'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
			$_POST['add_from'] = '1';
			//garfunkel add
            $area = D('Area')->where(array('area_id'=>$_POST['city_id']))->find();
            $_POST['province_id'] = $area ? $area['area_pid'] : 0;
            $_POST['area_id'] = 0;
			$database_merchant_store = D('Merchant_store');
			if($insert_id=$database_merchant_store->data($_POST)->add()){
				M('Merchant_score')->add(array('parent_id'=>$insert_id,'type'=>2));
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

	public function store_edit(){
		$database_merchant_store = D('Merchant_store');
		$condition_merchant_store['store_id'] = intval($_GET['store_id']);
		$store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
		if(empty($store)){
			$this->frame_error_tips('数据库中没有查询到该店铺的信息！',5);
		}

        $city = D('Area')->where(array('area_id'=>$store['city_id']))->find();
		$store['city_name'] = $city['area_name'];

        $this->assign('store',$store);

		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}

	public function store_amend(){
		if(IS_POST){
			$long_lat = explode(',',$_POST['long_lat']);
			$_POST['long'] = $long_lat[0];
			$_POST['lat'] = $long_lat[1];
			$_POST['last_time'] = $_SERVER['REQUEST_TIME'];
            //garfunkel add
            $area = D('Area')->where(array('area_id'=>$_POST['city_id']))->find();
            $_POST['province_id'] = $area ? $area['area_pid'] : 0;
            $_POST['area_id'] = 0;
			$database_merchant_store = D('Merchant_store');
			if($database_merchant_store->data($_POST)->save()){
				$this->success('Success');
			}else{
				$this->error('修改失败！请检查内容是否有过修改（必须修改）后重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	public function store_del(){
		if(IS_POST){
			$database_group_store = D('Group_store');
			$condition_group_store['store_id'] = intval($_POST['store_id']);
			if($database_group_store->where($condition_group_store)->find()){
				$this->error('该店铺下有'.$this->config['group_alias_name'].'，请先解除店铺与对应'.$this->config['group_alias_name'].'的关系才能删除！');
			}

			$database_merchant_store = D('Merchant_store');
			$condition_merchant_store['store_id'] = intval($_POST['store_id']);
			/**$database_merchant_store->where($condition_merchant_store)->delete();**改软删除*4禁用***/
			if($database_merchant_store->where($condition_merchant_store)->save(array('status'=>4))){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

	/*商户公告*/
	public function news(){
		$database_merchant_news = D('Merchant_news');
		$news_list = $database_merchant_news->order('`is_top` DESC,`add_time` DESC')->select();
		$this->assign('news_list',$news_list);

		$this->display();
	}
	public function news_add(){
		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}
	public function news_modify(){
		$database_merchant_news = D('Merchant_news');
		$_POST['content'] = fulltext_filter($_POST['content']);
		$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
		if($database_merchant_news->data($_POST)->add()){
			$this->success('添加成功！');
		}else{
			$this->error('添加失败！');
		}
	}
	public function news_edit(){
		$database_merchant_news = D('Merchant_news');
		$condition_merchant_news['id'] = $_GET['id'];
		$now_news = $database_merchant_news->field(true)->where($condition_merchant_news)->find();
		if(empty($now_news)){
			$this->frame_error_tips('数据库中没有查询到该条公告！',5);
		}
		$this->assign('now_news',$now_news);

		$this->assign('bg_color','#F3F3F3');

		$this->display();
	}
	public function news_amend(){
		$database_merchant_news = D('Merchant_news');
		$_POST['content'] = fulltext_filter($_POST['content']);
		$_POST['add_time'] = $_SERVER['REQUEST_TIME'];
		if($database_merchant_news->data($_POST)->save()){
			$this->success('编辑成功！');
		}else{
			$this->error('编辑失败！');
		}
	}
	public function news_del(){
		if(IS_POST){
			$database_merchant_news = D('Merchant_news');
			$condition_merchant_news['id'] = $_POST['id'];
			if($database_merchant_news->where($condition_merchant_news)->delete()){
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

	public function order(){
		$percent = 0;
        $period = 0;
        $time = '';
		if (!$_GET['begin_time']) {
            $mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
            $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        }else{
            $mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
            $type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
        }
		switch($type){
			case 'meal':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				break;
			case 'group':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status in (1,2,6) AND (pay_type<>'offline' OR balance_pay<>'0.00')";
				break;
			case 'weidian':
				$where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
				break;
			case 'wxapp':
				$where = "mer_id=".$mer_id." AND paid=1 AND pay_type<>'offline'";
				break;
			case 'appoint':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND pay_type<>'offline' AND service_status=1";
				break;
			case 'store':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND refund=0";
				break;
			case 'waimai':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 ";
				break;
			case 'shop':
				$where = "mer_id=".$mer_id." AND paid=1 AND is_own=0 AND status IN (2,3) AND (pay_type<>'offline' OR balance_pay<>'0.00' OR coupon_price<>'0.00' OR payment_money<>'0.00' OR score_deducte<>'0.00')";
				break;
		}
		if($type=='waimai'){
			$un_bill_count = D($type.'_order')->where($where." AND is_pay_bill=0 ")->count();
		}else if($type=='appoint'){
			$un_bill_count = D($type.'_order')->join('as o left join '.C('DB_PREFIX') ."appoint a ON o.appoint_id = a.appoint_id")->where("o.mer_id=".$mer_id." AND o.paid=1 AND o.is_own=0 AND o.pay_type<>'offline' AND o.service_status=1 AND o.is_pay_bill=0 AND a.payment_status=1")->count();
		}else if($type=='store'){
			$un_bill_count = D($type.'_order')->where($where." AND is_pay_bill=0 AND (payment_money<>'0.00' OR balance_pay<>'0.00') ")->count();
		}else{
			$un_bill_count = D($type.'_order')->where($where." AND is_pay_bill=0 ")->count();
		}

        $pay_time=D($type.'_order')->where($where." AND pay_time<>0 AND pay_time<>''")->field('pay_time')->order('pay_time ASC')->limit('0,1')->getField('pay_time');
        $start_year =empty($pay_time)?"":date('Y',$pay_time);
        if ($_GET['year']) {
            $time = array('year'=>$_GET['year'],'month'=>$_GET['month'],'period'=>$period);
            $time = serialize($time);
        }elseif(isset($_GET['begin_time'])&&isset($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period =array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            $time = array('year'=>$_GET['year'],'month'=>$_GET['month'],'period'=>$period);
            $time = serialize($time);

        }
        $selected_year = isset($_GET['year'])?$_GET['year']:0;
        $selected_month = isset($_GET['month'])?$_GET['month']:0;

		$merchant = D('Merchant')->field(true)->where('mer_id=' . $mer_id)->find();
		if ($merchant['percent']) {
			$percent = $merchant['percent'];
		} elseif ($this->config['platform_get_merchant_percent']) {
			$percent = $this->config['platform_get_merchant_percent'];
		}
		$this->assign('percent', $percent);
		$result = D("Order")->get_order_by_mer_id($mer_id, $type, 1,$time,$_GET['is_pay_bill']);
		$this->assign($result);
		$this->assign('total_percent', $result['total'] * $percent * 0.01);
		$this->assign('all_total_percent', ($result['alltotal']+$result['alltotalfinsh']) * $percent * 0.01);

// 		$this->assign(D("Meal_order")->get_order_by_mer_id($mer_id, 1));
		$this->assign('un_bill_count',$un_bill_count);
        $this->assign('start_year',$start_year);
        $this->assign('selected_year',$selected_year);
        $this->assign('selected_month',$selected_month);
		$this->assign('now_merchant', $merchant);
		$this->assign('mer_id', $mer_id);
		$this->assign('type', $type);
		$this->display();
	}

	public function export()
	{
		set_time_limit(0);
		$mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
		$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'meal';
		$title = '';
		switch ($type) {
			case 'meal':
				$title = '餐饮账单';
				break;
			case 'group':
				$title = '团购账单';
				break;
			case 'weidian':
				$title = '微店账单';
				break;
			case 'wxapp':
				$title = '预约账单';
				break;
			case 'appoint':
				$title = '营销账单';
				break;
			case 'store':
				$title = '收银账单';
				break;
			case 'waimai':
				$title = '外卖账单';
				break;
			case 'shop':
				$title = '快店账单';
				break;
			case 'income':
				$title = '收入明细';
				break;
		}
		require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
		$objExcel = new PHPExcel();
		$objProps = $objExcel->getProperties();
		// 设置文档基本属性
		$objProps->setCreator($title);
		$objProps->setTitle($title);
		$objProps->setSubject($title);
		$objProps->setDescription($title);
		// 设置当前的sheet
		$objExcel->setActiveSheetIndex(0);


		$objExcel->getActiveSheet()->setTitle($type);
		$objActSheet = $objExcel->getActiveSheet();
		$cell_meal    = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> $this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_group   = array('store_name'=>'门店名称','real_orderid'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> $this->config['score_name'].'抵扣','refund_money'=>'退款金额','refund_fee'=>'退款手续费','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_appoint = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> $this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_shop    = array('store_name'=>'门店名称','real_orderid'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'coupon_price'=> '优惠券','score_deducte'=> $this->config['score_name'].'抵扣','pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_waimai  = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_store   = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_weidian = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_wxapp   = array('store_name'=>'门店名称','order_id'=>'订单编号','orderid'=>'流水号', 'num'=>'数量', 'order_price'=>'金额','balance_pay'=> '余额支付金额','payment_money'=> '在线支付金额', 'pay_time'=>'支付时间','pay_type'=> '支付类型','bill_money'=>'应对账金额');
		$cell_income  = array('type'=>'类型','order_id'=>'订单编号', 'num'=>'数量', 'money'=>'金额','score'=>'送出'.$this->config['score_name'],'score_count'=>$this->config['score_name'].'使用数量','use_time'=>'记账时间','desc'=>'描述');

		// 开始填充头部
		$cell_name = 'cell_'.$type;
		$cell_count = count($$cell_name);
		$cell_start = 1;
		for($f='A';$f<='Z';$f++,$cell_start++){
				if($cell_start>$cell_count){
					break;
				}
				$col_char[]=$f;
		}
		$col_k=0;
		foreach($$cell_name as $key=>$v){

			$objActSheet->setCellValue($col_char[$col_k].'1', $v);
			$col_k++;
		}
		$i = 2;
		if ($_GET['bill_id']) {
			$res = M('Bill_info')->where(array('id' => $_GET['bill_id']))->find();
			$result = D('Order')->export_order_by_mid($mer_id, $type,1,$res['id_list']);

		}else if($type=='income'){
			$where['mer_id']=$mer_id;
			if($_GET['order_type']&&$_GET['order_type']!='all'){
				$where['type']=$_GET['order_type'];
			}
			if($_GET['order_id']){
				$where['order_id']=$_GET['order_id'];
			}
			if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
				if ($_GET['begin_time']>$_GET['end_time']) {
					$this->error_tips("结束时间应大于开始时间");
				}
				$period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
				$time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
				$where['_string']=$time_condition;

			}
			$result = M('Merchant_money_list')->field('type,order_id,num,pow(-1,income+1)*money as money,use_time,desc,score,score_count')->where($where)->order('use_time DESC')->select();
			//dump(M());
		}else {
			$result = D("Order")->export_order_by_mid($mer_id, $type);
		}
		//dump(D());die;
		foreach ($result as $row) {
			$col_k=0;
			foreach($$cell_name as $k=>$vv){

				switch($k){
					case 'order_id':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
						break;
					case 'real_orderid':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
						break;
					case 'orderid':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k].' ');
						break;
					case 'pay_time':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
						break;
					case 'use_time':
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]?date('Y-m-d H:i:s', $row[$k]) : '');
						break;
					case 'desc':
						$objActSheet->setCellValue($col_char[$col_k] . $i, str_replace('</br>','',$row[$k]).' ');
						break;
					default:
						$objActSheet->setCellValue($col_char[$col_k] . $i, $row[$k]);
				}
				$col_k++;
			}
			if($type!='income'){
				$objActSheet->setCellValue($col_char[$cell_count-1] . $i, $row['balance_pay']+$row['coupon_price']+$row['score_deducte']+$row['payment_money']);
			}
			$i++;
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

	public function weidian_order(){

		$percent = 0;
		$mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
		$merchant = D('Merchant')->field(true)->where('mer_id=' . $mer_id)->find();
		if ($merchant['percent']) {
			$percent = $merchant['percent'];
		} elseif ($this->config['platform_get_merchant_percent']) {
			$percent = $this->config['platform_get_merchant_percent'];
		}
		$this->assign('percent', $percent);

		$result = D("Weidian_order")->get_order_by_mer_id($mer_id, 1);
		$this->assign($result);
		$this->assign('total_percent', $result['total'] * $percent * 0.01);
		$this->assign('all_total_percent', ($result['alltotal']+$result['alltotalfinsh']) * $percent * 0.01);

// 		$this->assign(D("Meal_order")->get_order_by_mer_id($mer_id, 1));
		$this->assign('now_merchant', $merchant);
		$this->assign('mer_id', $mer_id);
		$this->display();
	}

	public function change()
	{
		$mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
		$strids = isset($_GET['strids']) ? htmlspecialchars($_GET['strids']) : '';
		$type = isset($_GET['type']) ? htmlspecialchars($_GET['type']) : '';
		if ($strids) {
			$array = explode(',', $strids);
			$mealids = $groupids = array();
			foreach ($array as $val) {
				switch ($type) {
					case 'meal' :
						$mealids[] = intval($val);
						break;
					case 'group' :
						$groupids[] = intval($val);
						break;
					case 'weidian' :
						$weidianids[] = intval($val);
						break;
					case 'appoint' :
						$appointids[] = intval($val);
						break;
					case 'wxapp' :
						$wxappids[] = intval($val);
						break;
					case 'store' :
						$storeids[] = intval($val);
						break;
					case 'waimai' :
						$waimaiids[] = intval($val);
						break;
					case 'shop' :
						$shopids[] = intval($val);
						break;
				}
			}

			$now_time=time();
			$date_bill_info['mer_id']=$mer_id;
			$date_bill_info['name']=$type;
			$date_bill_info['money']=$_GET['money']*100;
			$date_bill_info['num']=count($array);
			$date_bill_info['bill_time']=$now_time;
			$date_bill_info['id_list']=$strids;
			if(!$res = M('Bill_info')->add($date_bill_info)){
				$this->error("保存对账信息失败！");
			}
			$res_bill = M('Bill_time')->where(array('merid'=>$mer_id))->find();
			if(!$res_bill){
				$date_bill_time['merid']=$mer_id;
				$date_bill_time[$type.'_time']=$now_time;
				$date_bill_time['update_time']=$now_time;
				$date_bill_time[$type.'_bill_info']=serialize(array('bill_id'=>$res,'num'=>$date_bill_info['num']));
				if(!M('Bill_time')->add($date_bill_time)){
					$this->error("添加对账汇总信息失败！");
				}
			}else{
				$date_bill_time['merid']=$mer_id;
				$date_bill_time[$type.'_time']=$now_time;
				$date_bill_time['update_time']=$now_time;
				$date_bill_time[$type.'_bill_info']=serialize(array('bill_id'=>$res,'num'=>$date_bill_info['num']));
				if(!M('Bill_time')->where(array('id'=>$res_bill['id']))->save($date_bill_time)){
					$this->error("保存对账汇总信息失败！");
				}

			}

			$data = array('is_pay_bill'=>1);
			$mealids && D('Meal_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $mealids)))->save($data);
			$groupids && D('Group_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $groupids)))->save(array('is_pay_bill' => 1));
			$weidianids && D('Weidian_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $weidianids)))->save(array('is_pay_bill' => 1));
			$appointids && D('Appoint_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $appointids)))->save(array('is_pay_bill' => 1));
			$wxappids && D('Wxapp_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $wxappids)))->save(array('is_pay_bill' => 1));
			$storeids && D('Store_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $storeids)))->save(array('is_pay_bill' => 1));
			$waimaiids && D('Waimai_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $waimaiids)))->save(array('is_pay_bill' => 1));
			$shopids && D('Shop_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $shopids)))->save(array('is_pay_bill' => 1));
		}
		exit(json_encode(array('error_code' => 0)));
	}

	public function change_money(){
		$this->display();
	}


	//平台提现
	public function companypay(){
		if(IS_POST){
			if(!$res=D('Merchant')->where(array('mer_id'=>$_POST['mer_id']))->find()){
				$this->error('商家不存在！');
			}
			sort($_POST['orderid']);
			$type=$_POST['pay_type'];
			$mer_id=$_POST['mer_id'];
			$orderids = implode(',',$_POST['orderid']);
			$data['pay_type'] = 'merchant';
			$data['pay_id'] = $_POST['mer_id'];
			$data['phone'] = $res['phone'];
			$data['money'] = $_POST['money'];
			$data['desc'] = '商家'.$this->config[$_POST['pay_type'].'_alias_name'].'订单对账|订单号'.'('.$orderids.')'.'|转账 '.(float)($_POST['money']/100).' 元';
			$data['status'] = 0;
			$now_time = time();
			$data['add_time'] = $now_time;

			$data_bill = array('is_pay_bill'=>1);

			$date_bill_info['mer_id']=$_POST['mer_id'];
			$date_bill_info['name']=$_POST['pay_type'];
			$date_bill_info['money']=$_POST['money'];
			$date_bill_info['num']=count($_POST['orderid']);

			$date_bill_info['id_list']=$orderids;
			if(!$res = M('Bill_info')->add($date_bill_info)){
				$this->error("保存对账信息失败！");
			}
			$res_bill = M('Bill_time')->where(array('merid'=>$mer_id))->find();
			if(!$res_bill){
				$date_bill_time['merid']=$mer_id;
				$date_bill_time[$type.'_time']=$now_time;
				$date_bill_time['update_time']=$now_time;
				$date_bill_time[$type.'_bill_info']=serialize(array('bill_id'=>$res,'num'=>$date_bill_info['num']));
				if(!M('Bill_time')->add($date_bill_time)){
					$this->error("添加对账汇总信息失败！");
				}
			}else{
				$date_bill_time['merid']=$mer_id;
				$date_bill_time[$type.'_time']=$now_time;
				$date_bill_time['update_time']=$now_time;
				$date_bill_time[$type.'_bill_info']=serialize(array('bill_id'=>$res,'num'=>$date_bill_info['num']));
				if(!M('Bill_time')->where(array('id'=>$res_bill['id']))->save($date_bill_time)){
					$this->error("保存对账汇总信息失败！");
				}
			}
			$model=new Model();
			$where['order_id']=array('in',$orderids);
			if($model->table(C('DB_PREFIX').'companypay')->add($data)&&$model->table(C('DB_PREFIX').$_POST['pay_type'].'_order')->where($where)->setField($data_bill)){
				$this->success("提现申请成功！");
			}else{
				$this->error("提现失败！请联系管理员！");
			}
		}else{
			$this->error('您提交的数据不正确');
		}
	}
	//微店对账
	public function weidian_change()
	{
		$mer_id = isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
		$strids = isset($_GET['strids']) ? htmlspecialchars($_GET['strids']) : '';
		if ($strids) {
			$array = explode(',', $strids);
			$ids = array();
			foreach ($array as $val) {
				$ids[] = $val;
			}
			$ids && D('Weidian_order')->where(array('mer_id' => $mer_id, 'order_id' => array('in', $ids)))->save(array('is_pay_bill' => 1));
		}
		exit(json_encode(array('error_code' => 0)));
	}

	public function menu()
	{
		$this->assign('bg_color','#F3F3F3');

		$database_merchant = D('Merchant');
		$condition_merchant['mer_id'] = intval($_GET['mer_id']);
		$merchant = $database_merchant->field(true)->where($condition_merchant)->find();
		if(empty($merchant)){
			$this->frame_error_tips('数据库中没有查询到该商户的信息！');
		}
		$merchant['menus'] = explode(',', $merchant['menus']);
		$this->assign('merchant',$merchant);
		$menus = D('New_merchant_menu')->field(true)->where(array('status' => 1, 'show' => 1))->order('`sort` DESC,`id` ASC')->select();
		$list = arrayPidProcess($menus);
		$this->assign('menus', $list);
		$this->display();
	}

	public function savemenu()
	{
		if (IS_POST) {
			$mer_id = isset($_POST['mer_id']) ? intval($_POST['mer_id']) : 0;
			$menus = isset($_POST['menus']) ? $_POST['menus'] : '';
			$database_new_merchant_menu = D('New_merchant_menu');
			$where['fid'] = 0;
			$top_menus = $database_new_merchant_menu->where($where)->getField('id',true);

			$menus = array_merge($menus,$top_menus);
			$menus = implode(',', $menus);
			$database_merchant = D('Merchant');

			$data['menus'] = $menus;
			//$data['is_new_auth'] = 1;
			$database_merchant->where(array('mer_id' => $mer_id))->save($data);
			$this->success('权限设置成功！');
		} else {
			$this->error('非法提交,请重新提交~');
		}
	}
	//	商店分类
	public	function	category_list(){
		$database_group_category = D('Merchant_category');
		$condition_group_category['cat_fid'] = intval($_GET['cat_fid']);

		$count_group_category = $database_group_category->where($condition_group_category)->count();
		import('@.ORG.system_page');
		$p = new Page($count_group_category,50);
		$category_list = $database_group_category->field(true)->where($condition_group_category)->order('`cat_sort` DESC,`cat_id` ASC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('category_list',$category_list);
		$pagebar = $p->show();
		$this->assign('pagebar',$pagebar);

		if($_GET['cat_fid']){
			$condition_now_group_category['cat_id'] = intval($_GET['cat_fid']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
			if(empty($now_category)){
				$this->error_tips('没有找到该分类信息！',3,U('category_list'));
			}
			$this->assign('now_category',$now_category);
		}

		$this->display();
	}
	//	分类添加
	public function category_add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	//	添加完成
	public function category_modify(){
		if(IS_POST){
			$database_group_category = D('Merchant_category');
			if($database_group_category->data($_POST)->add()){
				$this->success('添加成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	//	分类编辑
	public function category_edit(){
		$this->assign('bg_color','#F3F3F3');

		$database_group_category = D('Merchant_category');
		$condition_now_group_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		$this->assign('now_category',$now_category);
		$this->display();
	}
	//	编辑成功
	public function category_amend(){
		if(IS_POST){
			$image = D('Image')->handle($this->system_session['id'], 'system/merchant', 0, array('size' => 10));
			if (!$image['error']) {
				$_POST = array_merge($_POST, str_replace('/upload/system/merchant/', '', $image['url']));
			}
			$database_group_category = D('Merchant_category');
			if($database_group_category->data($_POST)->save()){
				D('Image')->update_table_id('/upload/system/merchant/' . $_POST['cat_pic'], $_POST['cat_id'], 'merchant_category');
				$this->frame_submit_tips(1,'编辑成功！');
			}else{
				$this->frame_submit_tips(0,'编辑失败！请重试~');
			}
		}else{
			$this->frame_submit_tips(0,'非法提交,请重新提交~');
		}
	}
	//	删除分类
	public function category_del(){
		if(IS_POST){
			$database_group_category = D('Merchant_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
			if($database_group_category->where($condition_now_group_category)->delete()){
				if(empty($now_category['cat_fid'])){
					$condition_son_group_category['cat_fid'] = $now_category['cat_id'];
					$database_group_category->where($condition_son_group_category)->delete();
					$condition_group['cat_fid'] = $now_category['cat_id'];
				}else{
					$condition_group['cat_id'] = $now_category['cat_id'];
				}
//				D('Group')->where($condition_group)->delete();
				$this->success('删除成功！');
			}else{
				$this->error('删除失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	//	商品字段管理
	public function category_field(){
		$database_group_category = D('Merchant_category');
		$condition_now_group_category['cat_id'] = intval($_GET['cat_id']);
		$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();
		if(empty($now_category)){
			$this->frame_error_tips('没有找到该分类信息！');
		}
		if(!empty($now_category['cat_fid'])){
			$this->frame_error_tips('该分类不是主分类，无法使用商品字段功能！');
		}
		if(!empty($now_category['cat_field'])){
			$now_category['cat_field'] = unserialize($now_category['cat_field']);
			foreach($now_category['cat_field'] as $key=>$value){
				if($value['use_field'] == 'area'){
					$now_category['cat_field'][$key]['name'] = '区域(内置)';
					$now_category['cat_field'][$key]['url'] = 'area';
				}
				if($value['use_field'] == 'price'){
					$now_category['cat_field'][$key]['name'] = '价格(内置)';
					$now_category['cat_field'][$key]['url'] = 'area';
				}
			}
		}
		$this->assign('now_category',$now_category);

		$this->display();
	}
	//	商品字段管理添加
	public function category_field_add(){
		$this->assign('bg_color','#F3F3F3');
		$this->display();
	}
	//	商品字段管理修改
	public function category_field_modify(){
		if(IS_POST){
			$database_group_category = D('Merchant_category');
			$condition_now_group_category['cat_id'] = intval($_POST['cat_id']);
			$now_category = $database_group_category->field(true)->where($condition_now_group_category)->find();

			if(!empty($now_category['cat_field'])){
				$cat_field = unserialize($now_category['cat_field']);
				foreach($cat_field as $key=>$value){
					if( (!empty($_POST['use_field']) && $value['use_field'] == $_POST['use_field']) || (!empty($_POST['url']) && $value['url'] == $_POST['url']) ){
						$this->error('字段已经添加，请勿重复添加！');
					}
				}
			}else{
				$cat_field = array();
			}
			if(count($cat_field) >= 5){
				$this->error('添加字段失败，最多5个自定义字段！');
			}
			if(empty($_POST['use_field'])){
				$post_data['name'] = $_POST['name'];
				$post_data['url'] = $_POST['url'];
				$post_data['value'] = explode(PHP_EOL,$_POST['value']);
				$post_data['type'] = $_POST['type'];
			}else{
				$post_data['use_field'] = $_POST['use_field'];
			}

			array_push($cat_field,$post_data);
			$data_group_category['cat_field'] = serialize($cat_field);
			$data_group_category['cat_id'] = $now_category['cat_id'];
			if($database_group_category->data($data_group_category)->save()){
				$this->success('添加字段成功！');
			}else{
				$this->error('添加失败！请重试~');
			}
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}

	public function reply()
	{
		$where = ' WHERE r.status<2';
		if(!empty($_GET['keyword'])){
			if($_GET['searchtype'] == 'm_name'){
				$where .= " AND m.name LIKE '%" . htmlspecialchars($_GET['keyword']) . "%'";
				$condition_merchant['mer_id'] = $_GET['keyword'];
			}else if($_GET['searchtype'] == 's_name'){
				$where .= " AND s.name LIKE '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}else if($_GET['searchtype'] == 'nickname'){
				$where .= " AND u.nickname LIKE '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}else if($_GET['searchtype'] == 'phone'){
				$where .= " AND u.phone LIKE '%" . htmlspecialchars($_GET['keyword']) . "%'";
			}
		}
		$sql_count = "SELECT count(1) AS count FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "reply AS r ON r.mer_id = m.mer_id INNER JOIN " . C('DB_PREFIX') . "user AS u ON r.uid=u.uid LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=r.store_id {$where}";
		$count_res = D()->query($sql_count);
		$count = isset($count_res[0]['count']) ? intval($count_res[0]['count']) : 0;
		import('@.ORG.system_page');
		$p = new Page($count, 50);

		$sql = "SELECT r.*, m.name AS m_name, s.name AS s_name, u.nickname, u.phone FROM " . C('DB_PREFIX') . "merchant AS m INNER JOIN " . C('DB_PREFIX') . "reply AS r ON r.mer_id = m.mer_id INNER JOIN " . C('DB_PREFIX') . "user AS u ON r.uid=u.uid LEFT JOIN " . C('DB_PREFIX') . "merchant_store AS s ON s.store_id=r.store_id {$where} ORDER BY r.pigcms_id DESC LIMIT {$p->firstRow},{$p->listRows}";
		$reply_list = D()->query($sql);
		$this->assign('reply_list', $reply_list);
		$this->assign('pagebar', $p->show());
		$this->display();
	}

	public function replydel()
	{
		if (IS_POST) {
			$reply_id = intval($_POST['reply_id']);
			if ($reply = D('Reply')->field(true)->where(array('pigcms_id' => $reply_id))->find()) {
				if (D('Reply')->where(array('pigcms_id' => $reply_id))->save(array('status' => 2))) {
					if ($reply['order_type'] == 0) {
						D('Group')->setDec_group_reply($reply);
					} elseif ($reply['order_type'] == 1) {
						D('Merchant_store')->setDec_meal_reply($reply);
					} elseif ($reply['order_type'] == 2) {
						D('Appoint')->setDec_meal_reply($reply);
					} elseif ($reply['order_type'] == 3) {
						D('Merchant_store')->setDec_shop_reply($reply);
					}
					$this->success('删除成功！');
				} else {
					$this->error('删除失败！请重试~');
				}
			}
		}
		$this->error('非法提交,请重新提交~');
	}

	public function replyinfo()
	{
		$reply_id = isset($_GET['reply_id']) ? intval($_GET['reply_id']) : 0;
		$reply = D('Reply')->field(true)->where(array('pigcms_id' => $reply_id))->find();
		if (empty($reply)) $this->error('不存在的评论或该评论已经被删除！');
		if ($user = D('User')->field(true)->where(array('uid' => $reply['uid']))->find()) {
			$reply['nickname'] = $user['nickname'];
			$reply['phone'] = $user['phone'];
		}
		if ($merchant = D('Merchant')->field(true)->where(array('mer_id' => $reply['mer_id']))->find()) {
			$reply['m_name'] = $merchant['name'];
		}

		if ($merchant_store = D('Merchant_store')->field(true)->where(array('store_id' => $reply['store_id']))->find()) {
			$reply['s_name'] = $merchant_store['name'];
		}

		if($reply['order_type'] == 0){
			$pic_filepath = 'group';
			$reply['type_name'] = $this->config['group_alias_name'];
		}elseif($reply['order_type'] == 1){
			$pic_filepath = 'meal';
			$reply['type_name'] = $this->config['meal_alias_name'];
		} elseif ($reply['order_type'] == 2) {
			$pic_filepath = 'appoint';
			$reply['type_name'] = $this->config['appoint_alias_name'];
		} elseif ($reply['order_type'] == 3) {
			$reply['type_name'] = $this->config['shop_alias_name'];
		}

		if($reply['pic']){

			$reply_pic_list = D('Reply_pic')->field('`pigcms_id`,`pic`')->where(array('pigcms_id' => array('in', explode(',', $reply['pic']))))->select();
			$reply_image_class = new reply_image();
			foreach($reply_pic_list as $key=>$value){
				$tmp_value = $reply_image_class->get_image_by_path($value['pic'], $pic_filepath);
				$reply['pics'][] = $tmp_value;
			}
		}
		$this->assign('bg_color','#F3F3F3');
		$this->assign('reply', $reply);
		$this->display();
	}

	public function edit_percent(){
		$where['mer_id'] = $_POST['mer_id'];
		if(IS_POST){
			foreach($_POST as $key=>&$vv){
				if($vv=='' && $vv!==0){
					$_POST[$key] = -1;
				}
			}
			//fdump($_POST);
			if(empty($_POST['type'])) {
				if (M('Merchant_percent_rate')->where($where)->find()) {
					if (M('Merchant_percent_rate')->where($where)->save($_POST)) {
						$this->success('保存字段成功！');
					} else {
						$this->error('请检查数据是否修改!~');
					}
				} else {
					if (M('Merchant_percent_rate')->add($_POST)) {
						$this->success('添加字段成功！');
					} else {
						$this->error('添加失败！请重试~');
					}
				}
			}else{
				if (M('Merchant_percent_rate')->where($where)->find()) {
					$result_save = M('Merchant_percent_rate')->where($where)->save($_POST);
				} else {
					$result_add = M('Merchant_percent_rate')->add($_POST);
				}
				$data['fid'] = $_POST['mer_id'];
				$percent_arr = $_POST['money_percent'];
				$data[$_POST['type'].'_percent_detail'] = implode(',',$percent_arr);
				if(M('Percent_detail_by_type')->where(array('fid'=>$_POST['mer_id']))->find()){
					$result_detail_save = M('Percent_detail_by_type')->where(array('fid'=>$_POST['mer_id']))->save($data);
				}else{
					$result_detail_add = M('Percent_detail_by_type')->add($data);
				}

				if($result_detail_add||$result_add||$result_save!==false||$result_detail_save!==false){
					$this->success('保存字段成功！');die;
				} else {
					$this->error('请检查数据是否修改!~');
				}
			}
		}else{
			$percent_detail = M('Percent_detail')->select();
			$where['mer_id'] = $_GET['mer_id'];
			if($mer_pr = M('Merchant_percent_rate')->where($where)->find()){
				$this->assign('mer_pr',$mer_pr);
			}
			if(!empty($_GET['type'])){
				$result = M('Percent_detail_by_type')->where(array('fid'=> $_GET['mer_id']))->find();
				$detail = explode(',',$result[$_GET['type'].'_percent_detail']);
				$this->assign('detail',$detail);
			}

			$this->assign('percent_detail', $percent_detail);
			$mer_id = $_GET['mer_id'];
			$this->assign('mer_id',$mer_id);
		}
		$this->display();
	}

	public function edit_rate(){
		$where['mer_id'] = $_GET['mer_id'];
		if($mer_pr = M('Merchant_percent_rate')->where($where)->find()){
			$this->assign('mer_pr',$mer_pr);
		}
		$this->display();
	}

	public function edit_score(){
		$where['mer_id'] = $_GET['mer_id'];
		if($mer_pr = M('Merchant_percent_rate')->where($where)->find()){
			$this->assign('mer_pr',$mer_pr);
		}
		$this->display();
	}

	public function edit_offline(){
		$where['mer_id'] = $_GET['mer_id'];
		if($mer_pr = M('Merchant_percent_rate')->where($where)->find()){
			$this->assign('mer_pr',$mer_pr);
		}
		$this->display();
	}

	public function edit_user_rate(){
		if(IS_POST){
			$where['mer_id'] = $_POST['mer_id'];
			if(M('Merchant_percent_rate')->where($where)->find()){
				if(M('Merchant_percent_rate')->where($where)->save($_POST)){
					$this->success('保存字段成功！');
				}else{
					$this->error('保存失败！请重试~');
				}
			}else{
				if(M('Merchant_percent_rate')->add($_POST)){
					$this->success('添加字段成功！');
				}else{
					$this->error('添加失败！请重试~');
				}
			}
		}else{
			$where['mer_id'] = $_GET['mer_id'];
			if($mer_pr = M('Merchant_percent_rate')->where($where)->find()){
				$this->assign('mer_pr',$mer_pr);
			}
			$mer_id = $_GET['mer_id'];
			$this->assign('mer_id',$mer_id);
		}
		$this->display();
	}
	
	public function store_auth()
	{
		$database_merchant_store = D('Merchant_store');
		$status = isset($_GET['status']) ? intval($_GET['status']) : 0;
		if ($status == 0) {
			$condition_merchant = array('auth' => array('in','1, 4'));
		} else {
			$condition_merchant = array('auth' => array('in','2, 3, 5'));
		}
		//搜索
		if(!empty($_GET['keyword'])){
			$condition_merchant['name'] = array('like','%'.$_GET['keyword'].'%');
		}
		if ($this->system_session['area_id']) {
			$area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
			$condition_merchant[$area_index] = $this->system_session['area_id'];
		}
		$count_merchant = $database_merchant_store->where($condition_merchant)->count();
		import('@.ORG.system_page');
		$p = new Page($count_merchant, 15);
		$store_list = $database_merchant_store->field(true)->where($condition_merchant)->order('auth_time ASC')->limit($p->firstRow.','.$p->listRows)->select();
		$this->assign('store_list', $store_list);
		$pagebar = $p->show();
		$this->assign('pagebar', $pagebar);
		$this->assign('status', $status);
		$this->display();
	}
	
	public function auth_edit()
	{
		$database_merchant_store = D('Merchant_store');
		$database_store_authfile = D('Merchant_store_authfile');
		
		$store_id = intval($_REQUEST['store_id']);
		$now_store = $database_merchant_store->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($now_store)) {
			$this->error('店铺不存在！');
		}
		$store_authfile = $database_store_authfile->field(true)->where(array('store_id' => $store_id))->find();
		if (empty($store_authfile)) {
			$this->error('申请资料不存在！');
		}
		
		if(IS_POST){
			$status = isset($_POST['status']) ? intval($_POST['status']) : 0;
			$reason = isset($_POST['reason']) ? htmlspecialchars($_POST['reason']) : '';
			if ($status == 1) {
				$auth = 3;
			} else {
				$auth = $now_store['auth'] < 3 ? 2 : 5;
			}
			$data = array('auth' => $auth, 'reason' => $reason);
			if ($auth == 3) {
				$data['auth_files'] = $store_authfile['auth_files'];
			}
			if ($database_merchant_store->where(array('store_id' => $store_id))->save($data)) {
				$this->success('审核成功');
			} else {
				$this->error('审核失败');
			}
			exit;
		}
		$auth_files = array();
		if (!empty($store_authfile['auth_files'])) {
			$auth_file_class = new auth_file();
			$tmp_pic_arr = explode(';', $store_authfile['auth_files']);
			foreach($tmp_pic_arr as $key => $value){
			    $image = $auth_file_class->get_image_by_path($value);
				$auth_files[] = array('title' => $value, 'url' => $image['image']);
			}
		}
		$now_store['reason'] = $store_authfile['reason'];
		$now_store['auth_files'] = $auth_files;
		$this->assign('now_store', $now_store);
		$this->display();
	}

	//商家权限价格
	public function authority_price(){
		if($this->system_session['level']<2){
			$this->error('您的权限不够');
		}else{
			$menus = D('New_merchant_menu')->where(array('status'=>1))->select();
			$list = array();
			$list = arrayPidProcess($menus);

			$this->assign('menus', $list);
			$this->display();
		}
	}

	public function change_authority_price(){
		if($this->system_session['level']<2){
			echo json_encode(array('error_code'=>1,'msg'=>'您的权限不够','price'=>$_POST['price']));exit;
		}else{
			$res = D('New_merchant_menu')->where(array( 'id' =>$_POST['id']))->setField('price',$_POST['price']);
			if($res){
				echo json_encode(array('error_code'=>0,'msg'=>'价格改变成功'));exit;
			}else{
				echo json_encode(array('error_code'=>1,'msg'=>'价格改变失败','price'=>$_POST['price']));exit;
			}
		}
	}

	public function store_apply(){
        $database_merchant_store = D('Merchant_apply');

        if ($this->system_session['area_id']) {
            $area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
            $condition_merchant[$area_index] = $this->system_session['area_id'];
        }
        $condition_merchant = array();
        $count_merchant = $database_merchant_store->where($condition_merchant)->count();
        import('@.ORG.system_page');
        $p = new Page($count_merchant, 15);
        $store_list = $database_merchant_store->field(true)->where($condition_merchant)->order('id DESC')->limit($p->firstRow.','.$p->listRows)->select();
        $this->assign('store_list', $store_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
	    $this->display();
    }
}