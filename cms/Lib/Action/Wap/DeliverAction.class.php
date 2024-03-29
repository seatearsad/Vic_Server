<?php
/**
 * 配送员登录
 * @author yanleilei
 */
class DeliverAction extends BaseAction 
{
    //更换配送员时间
    const CHANGE_TIME = 45;

	protected $deliver_session;
	protected $item = array(0 => "老的餐饮外送", 1 => "外卖", 2 => "新快店");
	protected $deliver_supply;
	
	public function __construct()
	{
		parent::__construct();
		$this->deliver_session = session('deliver_session');
		$this->deliver_session = !empty($this->deliver_session)? unserialize($this->deliver_session): false;
		if (ACTION_NAME != 'logout') {
			if (empty($this->deliver_session) && $this->is_wexin_browser && !empty($_SESSION['openid'])) {
				if ($user = D('Deliver_user')->field(true)->where(array('openid' => trim($_SESSION['openid'])))->find()) {
					session('deliver_session', serialize($user));
					$this->deliver_session = $user;
				}
			}
			
			if (empty($this->deliver_session)) {
				if (ACTION_NAME != 'login' && ACTION_NAME != 'reg' &&  ACTION_NAME != 'ajax_city_name' && ACTION_NAME != 'forgetpwd' && ACTION_NAME != 'policy') {
					redirect(U('Deliver/login', array('referer' => urlencode('http://' . $_SERVER['HTTP_HOST'] . (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'])))));
					exit();
				}
			} else {
				if ($user = D('Deliver_user')->field(true)->where(array('uid' => $this->deliver_session['uid']))->find()) {
                    //garfunkel add && $now_user['reg_status'] == 0 判断是否完成注册步骤
				    if (empty($user['status']) && $user['reg_status'] == 0) {
						session('deliver_session', null);
						$this->error_tips("您的账号已禁止");
						exit;
					}
                    //garfunkel add 更新一下session
                    $this->deliver_session = $user;
                    session('deliver_session', serialize($user));
				}
// 				if (ACTION_NAME == 'login') {
// 					redirect(U('Deliver/grab'));
// 				}

				$this->assign('deliver_session', $this->deliver_session);
			}
		}
		$this->assign('merchantstatic_path', $this->config['site_url'] . '/tpl/Merchant/static/');
		$this->deliver_supply = D("Deliver_supply");
		
		//查看骑士是否需要上传位置
		$where = array();
		$where['status'] = array('in', '3, 4');
		// $where['item'] = 1;
		$where['uid'] = $this->deliver_session['uid'];
		$have_send = $this->deliver_supply->field("`supply_id`")->where($where)->find();
		if ($have_send) {
			$this->assign("have_send", true);
		} else {
			$this->assign("have_send", false);
		}
// 		if (ACTION_NAME == 'index') {
// 			redirect(U('grab'));
// 		}

        //garfunke add 更新城市紧急呼叫状态
        $city = D('Area')->where(array('area_type'=>2))->select();
        foreach ($city as $v){
            if($v['urgent_time'] != 0 && $v['urgent_time']+7200 <= time()){
                D('Area')->where(array('area_id'=>$v['area_id']))->save(array('urgent_time'=>0));
                $this->updateDeliverWorkStatus($v);
            }
        }

        $save_address = array('login','reg','ajax_city_name',"ajax_save_city_id_for_deliver_user",'ajax_upload','ajax_upload_photo','forgetpwd','account','change_pwd','bank_info','step_1','step_2','step_3','step_4','step_5','support','ver_info','activate_deliver');
        if(!in_array(ACTION_NAME,$save_address)) {
            $deliver = D('Deliver_user')->field('reg_status')->where(['uid' => $this->deliver_session['uid']])->find();

            if ($deliver['reg_status'] != 0)
                header('Location:' . U('Deliver/step_').$deliver['reg_status']);
        }else{
            if(ACTION_NAME == 'step_5')
                header('Location:' . U('Deliver/step_4'));
            if(ACTION_NAME == 'step_0')
                header('Location:' . U('Deliver/index'));
        }
	}

	public function updateDeliverWorkStatus($city){
        $week_num = date("w");
        $hour = date('H');

        if($hour >= 0 && $hour < 5) {
            $hour = $hour + 24;
            $week_num = $week_num - 1 < 0 ? 6 : $week_num - 1;
        }

        $all_list = D('Deliver_schedule_time')->where(array('city_id'=>$city['area_id']))->select();
        $time_ids = array();
        foreach ($all_list as $v){
            $new_hour = $hour + $city['jetlag'];
            if($new_hour == $v['start_time']){
                $daylist = explode(',', $v['week_num']);
                if (in_array($week_num, $daylist)) {
                    $time_ids[] = $v['id'];
                }
            }
        }

        //获取所有上班送餐员的id
        $schedule_list = D('Deliver_schedule')->where(array('time_id' => array('in', $time_ids),'week_num'=>$week_num,'whether'=>1,'status'=>1))->select();
        $work_delver_list = array();
        foreach ($schedule_list as $v){
            $curr_user = D('Deliver_user')->where(array('uid'=>$v['uid']))->find();
            if($curr_user['work_status'] == 0) $work_delver_list[] = $v['uid'];
            //如果为不repeat的 此时删除
            //if($v['is_repeat'] != 1){
                //D('Deliver_schedule')->where($v)->delete();
            //}
        }

        $have_order_list = D('Deliver_supply')->where(array('status' => array(array('gt', 1), array('lt', 5))))->select();
        foreach($have_order_list as $h){
            if(!in_array($h['uid'],$work_delver_list)){
                $work_delver_list[] = $h['uid'];
            }
        }
        //全部下班
        D('Deliver_user')->where(array('status'=>1,'work_status'=>0,'city_id'=>$city['area_id']))->save(array('work_status'=>1,'inaction_num'=>0));
        //执行上班
        D('Deliver_user')->where(array('status'=>1,'uid'=>array('in',$work_delver_list),'city_id'=>$city['area_id']))->save(array('work_status'=>0,'inaction_num'=>0));
    }
	
	/**
	 * 登录
	 */
	public function login()
	{
		if (IS_POST) {
			$condition_deliver_user['phone'] = trim($_POST['phone']);
			$database_deliver_user = D('Deliver_user');
			$now_user = $database_deliver_user->field(true)->where($condition_deliver_user)->find();
			if (empty($now_user)) {
				exit(json_encode(array('error' => 2, 'msg' => L('_DE_ACCOUNT_NOT_'), 'dom_id' => 'account')));
			}
			//garfunkel add && $now_user['reg_status'] == 0 判断是否完成注册步骤
			if (empty($now_user['status']) && $now_user['reg_status'] == 0) {
				exit(json_encode(array('error' => 2, 'msg' => L('_DE_ACCOUNT_SUS_'), 'dom_id' => 'account')));
			}
			$pwd = md5(trim($_POST['pwd']));
			if ($pwd != $now_user['pwd']) {
				exit(json_encode(array('error' => 3, 'msg' => L('_DE_PASS_ERROR_'), 'dom_id' => 'pwd')));
			}
			$data_deliver_user['last_time'] = $_SERVER['REQUEST_TIME'];
			if ($database_deliver_user->where(array('uid'=>$now_user['uid']))->data($data_deliver_user)->save()) {
				session('deliver_session', serialize($now_user));
				$is_bind = $now_user['openid'] ? 1 : 0;
				exit(json_encode(array('error' => 0, 'msg' => '登录成功,现在跳转~', 'dom_id' => 'account', 'is_bind' => $is_bind)));
			} else {
				exit(json_encode(array('error' => 6, 'msg' => '登录信息保存失败,请重试！', 'dom_id' => 'account')));
			}
		} else {
			if ($this->is_wexin_browser && !empty($_SESSION['openid'])) {
				$this->assign('openid', $_SESSION['openid']);
			}
			if($this->deliver_session){
                redirect(U('Deliver/index'));
            }else {
                $referer = isset($_GET['referer']) ? htmlspecialchars_decode(urldecode($_GET['referer']), ENT_QUOTES) : '';
                $this->assign('refererUrl', $referer);
                $this->display();
            }
		}
	}
	
	
	public function logout()
	{
		$_SESSION['deliver_session'] = null;
		redirect(U('Deliver/login'));
	}
	
	/**
	 * 绑定微信，下次免登录
	 */
	public function freeLogin()
	{
		if(IS_POST && $this->is_wexin_browser && !empty($_SESSION['openid']) && is_array($this->deliver_session)){
			if ($old_user = D('Deliver_user')->where(array('openid' => trim($_SESSION['openid'])))->find()) {
				exit(json_encode(array('error' => 1, 'msg' => '改微信号已被绑定了' . $old_user['phone'] . '账号，不能重复绑定')));
			} else {
				if (D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('openid' => trim($_SESSION['openid']), 'last_time' => time()))) {
					exit(json_encode(array('error' => 0)));
				} else {
					exit(json_encode(array('error' => 1, 'msg' => '绑定失败，请下次登录再试')));
				}
			}
		}
		exit(json_encode(array('error' => 1, 'msg' => '绑定失败，请下次登录再试')));
	}
	
	
	public function index() 
	{
	    //$deliver = D('Deliver_user')->field('reg_status')->where(['uid' => $this->deliver_session['uid']])->find();
	    //if($deliver['reg_status'] != 0)
        //    header('Location:'.U('Deliver/step_'.$deliver['reg_status']));
        $city_id = $this->deliver_session['city_id'];
        $city = D('Area')->where(array('area_id'=>$city_id))->find();
        $this->assign('city',$city);

        $config = D('Config')->get_config();
        $max_order = $config['deliver_max_order'];

        $current_order_num = D('Deliver_supply')->where(array('uid'=>$this->deliver_session['uid'],'status'=>array('lt',5)))->count();

        $min = date("i");

        $week_num = date("w");
        $hour = date('H');

        //if($min >= 50) $hour += 1;
        if($hour >= 0 && $hour < 5) {
            $hour = $hour + 24;
            $week_num = $week_num - 1 < 0 ? 6 : $week_num - 1;
        }

        $all_list = D('Deliver_schedule_time')->where(array('city_id' => $city_id))->select();
        $time_ids = array();
        foreach ($all_list as $v) {
            $new_hour = $hour + $city['jetlag'];
            if ($new_hour == $v['start_time'] || ($min >= 50 && $new_hour+1 == $v['start_time'])) {
                $daylist = explode(',', $v['week_num']);
                if (in_array($week_num, $daylist)) {
                    $time_ids[] = $v['id'];
                }
            }
        }

        $work_list = $this->getDeliverWorkList();
        if(count($work_list) == 0){
            $show_time = 'No shift scheduled';
            $is_scheduled = 0;
        }else {
            $show_time = '';
            $is_scheduled = 1;
        }

        $header_time_show = "";

        $schedule_list = D('Deliver_schedule')->where(array('uid'=>$this->deliver_session['uid'],'time_id' => array('in', $time_ids),'week_num' => $week_num, 'whether' => 1, 'status' => 1))->select();
        if($schedule_list){
            $is_change_work_status = 1;
            $curr_list = $work_list[$week_num];
            foreach ($curr_list['ids'] as $ids){
                $new_hour = $hour + $city['jetlag'];
                if(($new_hour >= $ids['start_time'] && $new_hour < $ids['end_time']) || ($min >= 50 && $new_hour+1 >= $ids['start_time'] && $new_hour+1 < $ids['end_time'])){
                    $show_time = "Today, ".$ids['start_time'].':00 - '.$ids['end_time'].':00';
                    $header_time_show = $ids['start_time'].':00 - '.$ids['end_time'].':00';
                }
            }
        }else{
            for($i = 0;$i < 7;++$i){
                $new_week = date("w") + $i;
                if($new_week >= 7) $new_week -= 7;
                if($work_list[$new_week]){
                    $curr_list = $work_list[$new_week];
                    $new_hour = date("H") + $city['jetlag'];
                    if($i == 0){
                        foreach ($curr_list['ids'] as $ids){
                            if($ids['start_time'] >= $new_hour){
                                $show_time = "Today, ".$ids['start_time'].':00 - '.$ids['end_time'].':00';
                                break;
                            }
                        }
                        if($show_time != '') break;
                    }else{
                        $timestr = date('Y-m-d');
                        $timestr = strtotime($timestr)+$i * (3600*24);
                        $date = date('M d (D), ',$timestr);
                        $curr_list = $work_list[date("w",$timestr)];
                        if($curr_list) {
                            $show_time = $date . $curr_list['ids'][0]['start_time'] . ':00 - ' . $curr_list['ids'][0]['end_time'] . ':00';
                            break;
                        }
                    }
                }
            }
            $is_change_work_status = 0;
        }

        if($header_time_show == "" && $city['urgent_time'] != 0){
            $header_time_show = date("H:i",$city['urgent_time'])." - ".date("H:i",$city['urgent_time']+7200);
        }

        $this->assign('is_change',$is_change_work_status);
        $this->assign('is_scheduled',$is_scheduled);
        $this->assign('show_time',$show_time);
        $this->assign('header_time_show',$header_time_show);

        //修改上下班状态 只有在紧急状态下才能修改上班状态
		if($_GET['action'] == 'changeWorkstatus') {// && ($city['urgent_time'] != 0 || $is_change_work_status == 1)
		    if($_GET['type'] == 1){
                $current_order_num = D('Deliver_supply')->where(array('uid'=>$this->deliver_session['uid'],'status'=>array('lt',5)))->count();
            }
            if($_GET['type'] == 0 || ($_GET['type'] == 1 && $current_order_num == 0)) {
                D('Deliver_user')->where(['uid' => $this->deliver_session['uid']])->save(['work_status' => $_GET['type'], 'inaction_num' => 0]);
                $this->deliver_session['work_status'] = $_GET['type'];
                session('deliver_session', serialize($this->deliver_session));
                exit(json_encode(array('error' => 0, 'msg' => '')));
            }else{
                exit(json_encode(array('error' => 1, 'msg' => 'All accepted orders must be completed before you clock out.')));
            }
		}
//		$data = $this->routeAssign($this->deliver_session['uid']);
//		var_dump($data);die();
		if ($this->deliver_session['store_id']) {
			$store = D('Merchant_store')->field(true)->where(array('store_id' => $this->deliver_session['store_id']))->find();
			$store_image_class = new store_image();
			$images = $store_image_class->get_allImage_by_path($store['pic_info']);
			$store['image'] = $images ? array_shift($images) : '';
			$this->assign('store', $store);
		}

        $img = D("Deliver_img")->where(array('uid'=>$this->deliver_session['uid']))->find();
        $expiry = array();
        if($img['insurace_expiry'] != '' && strtotime($img['insurace_expiry']." 23:59:59") < time()){
            $expiry[] = "Vehicle Insurance";
        }

        if($img['certificate_expiry'] != '-1' && $img['certificate_expiry'] != '' && strtotime($img['certificate_expiry']." 23:59:59") < time()) {
            $expiry[] = "Work Eligibility";
        }

        $this->assign('expiry',$expiry);
        if(count($expiry) > 0){
            $this->display('expiry');
            exit();
        }


        if($this->deliver_session['work_status'] == 1){
            $gray_count = 0;

            $this->display('checkin');
        }else {
            $my_distance = $this->deliver_session['range'] * 1000;
            $time = time();
            $where = "`create_time`<$time AND `status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$this->deliver_session['lat']}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$this->deliver_session['lat']}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$this->deliver_session['lng']}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
            if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
                $where = "`type`= 1 AND `store_id`=" . $this->deliver_session['store_id'] . " AND " . $where;
            } else {
                $where = "`type`= 0 AND " . $where;
            }
            //$gray_count = D("Deliver_supply")->where($where)->count();
            //garfunkel 添加派单逻辑
            $gray_list = D("Deliver_supply")->where($where)->select();
            $gray_count = 0;
            foreach ($gray_list as $k => $v) {
                $store = D('Merchant_store')->field(true)->where(array('store_id' => $v['store_id']))->find();
                if ($store['city_id'] == $city_id) {
                    $supply_id = $v['supply_id'];
                    $deliver_assign = D('Deliver_assign')->field(true)->where(array('supply_id' => $supply_id))->find();
                    $record_array = explode(',', $deliver_assign['record']);
                    //派单列表中不存在 || 派单列表中开放 || 指定派单 && 不在转接等候期
                    if ((!$deliver_assign && $current_order_num < $max_order) || ($deliver_assign['deliver_id'] == 0 && !in_array($this->deliver_session['uid'], $record_array) && $current_order_num < $max_order) || $deliver_assign['deliver_id'] == $this->deliver_session['uid']) {
                        $gray_count += 1;
                    }
                }
            }

            $deliver_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => array(array('gt', 1), array('lt', 5))))->count();
            $finish_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => 5))->count();

            //获取送餐员的当前路线
            $is_route = 0;
            $route = D('Deliver_route')->where(array('deliver_id' => $this->deliver_session['uid']))->find();
            if ($route) $is_route = 1;

            $this->assign(array('gray_count' => $gray_count, 'deliver_count' => $deliver_count, 'finish_count' => $finish_count, 'is_route' => $is_route, 'route' => $route));
            $this->display();
        }
	}
	public function index_count($from = 0)//如果其他的调用 from=1
	{
        $city_id = $this->deliver_session['city_id'];
        $city = D('Area')->where(array('area_id'=>$city_id))->find();

        $config = D('Config')->get_config();
        $max_order = $config['deliver_max_order'];

        $current_order_num = D('Deliver_supply')->where(array('uid'=>$this->deliver_session['uid'],'status'=>array('lt',5)))->count();

        //是否有新单提醒
        $just_new = 0;
        if($this->deliver_session['work_status'] == 0 || $city['urgent_time'] != 0) {
            $my_distance = $this->deliver_session['range'] * 1000;
            $time = time();
            $where = "`create_time`<$time AND `status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$this->deliver_session['lat']}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$this->deliver_session['lat']}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$this->deliver_session['lng']}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
            if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
                $where = "`type`= 1 AND `store_id`=" . $this->deliver_session['store_id'] . " AND " . $where;
            } else {
                $where = "`type`= 0 AND " . $where;
            }

            //$gray_count = D("Deliver_supply")->where($where)->count();
            //garfunkel 添加派单逻辑
            $gray_list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->select();
            $gray_count = 0;

            foreach ($gray_list as $k => $v) {
                $store = D('Merchant_store')->field(true)->where(array('store_id' => $v['store_id']))->find();
                if ($store['city_id'] == $city_id) {
                    $supply_id = $v['supply_id'];
                    $deliver_assign = D('Deliver_assign')->field(true)->where(array('supply_id' => $supply_id))->find();
                    $record_array = explode(',', $deliver_assign['record']);
                    //派单列表中不存在 || 派单列表中开放 && 未拒单 || 指定派单 && 不在转接等候期
                    if($deliver_assign['deliver_id'] == $this->deliver_session['uid'] && $deliver_assign['send_sound'] == 0){
                        $just_new = 1;
                        D('Deliver_assign')->field(true)->where(array('supply_id' => $supply_id))->save(array("send_sound"=>1));
                    }
                    //派单列表中不存在 || 派单列表中开放 || 指定派单 && 不在转接等候期
                    if ((!$deliver_assign && $current_order_num < $max_order) || ($deliver_assign['deliver_id'] == 0 && !in_array($this->deliver_session['uid'], $record_array) && $current_order_num < $max_order) || $deliver_assign['deliver_id'] == $this->deliver_session['uid']) {
                        $gray_count += 1;
                    }
                }
            }
        }else{
            $gray_count = 0;
        }
		
		$deliver_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => array(array('gt', 0), array('lt', 5))))->count();
		$finish_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => 5))->count();

		if($from == 0)
		    exit(json_encode(array('err_code' => false, 'gray_count' => $gray_count, 'deliver_count' => $deliver_count, 'finish_count' => $finish_count,'work_status'=>$this->deliver_session['work_status'],'just_new'=>$just_new)));
		else
		    return array('gray_count' => $gray_count, 'deliver_count' => $deliver_count, 'finish_count' => $finish_count,'work_status'=>$this->deliver_session['work_status'],'just_new'=>$just_new);
	}
	
	private function rollback($supply_id, $status)
	{
		$data = array();
		switch ($status) {
			case 1:
				$data = array('uid' => 0, 'status' => 1);
				break;
			case 2:
				$data = array('status' => 2, 'start_time' => 0);
				break;
			case 3:
				$data = array('status' => 3);
				break;
			case 4:
				$data = array('status' => 4, 'end_time' => 0);
				break;
		}
		$this->deliver_supply->where(array("supply_id" => $supply_id))->save($data);
	}

	//拒单
	public function reject(){
	    if(IS_POST && $_POST['supply_id']){
            $supply_id = $_POST['supply_id'];
            $supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id))->find();
            if (empty($supply)) {
                $this->error("This order has been removed from you");
                exit;
            }

            $assign = D('Deliver_assign')->field(true)->where(array('supply_id'=>$supply_id))->find();
            if (empty($assign)) {//如果派单记录不存在 添加一个
                $data['order_id'] = $supply['order_id'];
                $data['supply_id'] = $supply_id;
                $data['deliver_id'] = 0;
                $data['assign_time'] = time();
                $data['assign_num'] = 0;
                $data['record'] = $this->deliver_session['uid'];
                $data['reject_record'] = $this->deliver_session['uid'];

                D('Deliver_assign')->field(true)->add($data);
            }else{//如果派单记录存在
                $reject_array = explode(',',$assign['reject_record']);
                if(!in_array($this->deliver_session['uid'],$reject_array)){
                    $data['reject_record'] = $assign['reject_record'] == '' ? $this->deliver_session['uid'] : $assign['reject_record'].','.$this->deliver_session['uid'];
                }else{
                    $data['reject_record'] = $assign['reject_record'];
                }

                if($assign['assign_num'] == 5) {
                    $data['record'] = $data['reject_record'];
                    $data['deliver_id'] = 0;
                    $data['assign_time'] = time();
                    if($assign['is_send_all'] == 0) {
//                        $send_list = array();
//                        //获取店铺信息
//                        $store = D('Merchant_store')->field(true)->where(array('store_id' => $supply['store_id']))->find();
//                        //群发短信 筛选城市
//                        $user_list = D('Deliver_user')->field(true)->where(array('status' => 1, 'work_status' => 0, 'city_id' => $store['city_id']))->order('uid asc')->select();
//                        $record = explode(',', $data['record']);
//                        foreach ($user_list as $deliver) {
//                            if (!in_array($deliver['uid'], $record) && !in_array($deliver['uid'], $send_list)) {
//                                $this->sendMsg($deliver['uid']);//需要添加这个函数
//                                $send_list[] = $deliver['uid'];
//                            }
//                        }
                        $data['is_send_all'] = 1;
                    }
                }else{
                    //如果该送餐员没在记录列表中 添加记录该送餐员
                    $record_array = explode(',', $assign['record']);
                    if (!in_array($this->deliver_session['uid'], $record_array)) {
                        $data['record'] = $assign['record'] == '' ? $this->deliver_session['uid'] : $assign['record'] . ',' . $this->deliver_session['uid'];
                    }
                    if($assign['deliver_id'] == $this->deliver_session['uid']) {
                        $data['deliver_id'] = -1;
                        $data['status'] = 99;
                        $data['assign_time'] = time();
                    }else{
                        $data['assign_time'] = $assign['assign_time'];
                    }
                }

                D('Deliver_assign')->field(true)->where(array('supply_id'=>$supply_id))->save($data);
            }
            //清空送餐员无作为次数
            D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('inaction_num'=>0));

            $this->success("Rejected");exit;
        }
    }
	//抢
	public function grab()
	{
		if (IS_POST) {
			if ($user = D('Deliver_user')->where(array('uid' => $this->deliver_session['uid']))->find()) {
				if (empty($user['status'])) {
					$this->error("您的账号已禁止，不能抢单");
					exit;
				}
			}

			if($this->deliver_session['work_status'] != 0){
                $this->error(L('_PLEASE_WORKING_'));
                exit;
            }

			$supply_id = intval(I("supply_id"));
			if (!$supply_id) {
				$this->error("参数错误");
				exit;
			}
			
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id))->find();
			if (empty($supply)) {
				$this->error("This order has been removed from you");
				exit;
			}
			
			if ($supply['status'] != 1) {
				$this->error("Sorry, this order has already been accepted by others");
				exit;
			}
			
			$uid = $this->deliver_session['uid'];
			$columns = array('uid' => $this->deliver_session['uid'], 'status' => 2);
			$columns['start_time'] = time();
			$result = $this->deliver_supply->where(array("supply_id" => $supply_id, 'status' => 1))->save($columns);
			if (false === $result) {
				$this->error("抢单失败");
				exit;
			}
            //garfunkel 更新派单状态
            $assign_data['status'] = 1;
			$assign_data['grab_deliver_id'] = $this->deliver_session['uid'];
			D('Deliver_assign')->field(true)->where(array('supply_id'=>$supply_id))->save($assign_data);
            //清空送餐员无作为次数
            D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('inaction_num'=>0));

            $order_id = $supply['order_id'];
			
			if ($supply['item'] == 1) {
				$order = D("Waimai_order")->find($order_id);
				if ($order['order_status'] != 3) {
					$this->rollback($supply_id, 1);
					$this->error("订单信息错误");
					exit;
				}
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>8))->save();
				if (!$result) {
					$this->rollback($supply_id, 1);
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 8;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 1);
					$this->error("添加订单日志失败");
					exit;
				}
// 				D()->commit();
			} elseif ($supply['item'] == 0) {
// 				D()->commit();
				//更新订单状态
				$order = D("Meal_order")->where(array('order_id' => $order_id))->find();
				if ($order['order_status'] != 3) {
					$this->rollback($supply_id, 1);
					$this->error("订单信息错误");
					exit;
				}
				//更新订单状态
				$result = D("Meal_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>8))->save();
				if (!$result) {
					$this->rollback($supply_id, 1);
					$this->error("更新订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {
// 				D()->commit();
				//更新订单状态
				$order = D("Shop_order")->where(array('order_id' => $order_id))->find();
				if ($order['order_status'] != 1) {
					$this->rollback($supply_id, 1);
					$this->error("订单信息错误");
					exit;
				}
				//更新订单状态
				$deliver_info = serialize(array('uid' => $this->deliver_session['uid'], 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone'], 'store_id' => $this->deliver_session['store_id']));
				$result = D("Shop_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>2, 'deliver_info' => $deliver_info))->save();
				if (!$result) {
					$this->rollback(1);
					$this->error("更新订单信息错误");
					exit;
				}
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 3, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
			}
			//添加送餐员路线记录
            $route = D('Deliver_route')->where(array('deliver_id'=>$this->deliver_session['uid']))->find();
			//如果不存在路线记录 去当前订单店铺为第一路线 存在的话不做更改
			if(!$route){
                $data['deliver_id'] = $this->deliver_session['uid'];
                $data['order_id'] = $order_id;
                $data['destination_lat'] = $supply['from_lat'];
                $data['destination_lng'] = $supply['from_lnt'];
                $data['type'] = 0;

                D('Deliver_route')->add($data);
            }
			$this->success("Order Accepted");exit;
		}
		
		if (IS_AJAX) {
            $city_id = $this->deliver_session['city_id'];
            $city = D('Area')->where(array('area_id'=>$city_id))->find();

            $config = D('Config')->get_config();
            $max_order = $config['deliver_max_order'];

            $current_order_num = D('Deliver_supply')->where(array('uid'=>$this->deliver_session['uid'],'status'=>array('lt',5)))->count();

            if($this->deliver_session['work_status'] == 0 || $city['urgent_time'] != 0) {
                //garfunkel add 更新送餐员位置信息
//                $lat = $_GET['lat'] ? $_GET['lat'] : 0;
//                $lng = $_GET['lng'] ? $_GET['lng'] : 0;
//                $deliver_id = $this->deliver_session['uid'];
//
//                if($lat != 0 && $lng != 0){
//                    $data['lng'] = $lng;
//                    $data['lat'] = $lat;
//                    D('Deliver_user')->field(true)->where(array('uid'=>$deliver_id))->save($data);
//                    $this->deliver_session['lat'] = $lat;
//                    $this->deliver_session['lng'] = $lng;
//                }
                /////
                $lat = isset($_GET['lat']) && $_GET['lat'] ? $_GET['lat'] : $this->deliver_session['lat'];
                $lng = isset($_GET['lng']) && $_GET['lng'] ? $_GET['lng'] : $this->deliver_session['lng'];

                $my_lnt = $this->deliver_session['lng'];
                $my_lat = $this->deliver_session['lat'];
                $my_distance = $this->deliver_session['range'] * 1000;

                $where = "`status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$my_lat}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$my_lat}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$my_lnt}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
                if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
                    $where = "`type`= 1 AND `store_id`=" . $this->deliver_session['store_id'] . " AND " . $where;
                } else {
                    $where = "`type`= 0 AND " . $where;
                }

                //$list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->select();
                //garfunkel 添加派单逻辑
                $first_list = array();
                $second_list = array();
                $third_list = array();
                $grab_list = D('Deliver_supply')->field(true)->where($where)->order("`create_time` DESC")->select();
                foreach ($grab_list as $k => $v) {
                    $store = D('Merchant_store')->field(true)->where(array('store_id' => $v['store_id']))->find();
                    if ($store['city_id'] == $city_id) {
                        $supply_id = $v['supply_id'];
                        $deliver_assign = D('Deliver_assign')->field(true)->where(array('supply_id' => $supply_id))->find();
                        $record_array = explode(',', $deliver_assign['record']);
                        $v['just'] = 0;
                        //派单列表中不存在 || 派单列表中开放 && 未拒单 || 指定派单 && 不在转接等候期
                        if($deliver_assign['deliver_id'] == $this->deliver_session['uid']){
                            $v['just'] = 1;
                            $v['diff_time'] = self::CHANGE_TIME - (time() - $deliver_assign['assign_time']);
                            $v['diff_time'] = $v['diff_time'] > 0 ? $v['diff_time'] : 0;
                            $first_list[] = $v;
                        }
                        if($deliver_assign['deliver_id'] == 0 && !in_array($this->deliver_session['uid'], $record_array) && $current_order_num < $max_order){
                            $second_list[] = $v;
                        }
                        if (!$deliver_assign && $current_order_num < $max_order){
                            $third_list[] = $v;
                        }
                        //if ((!$deliver_assign && $current_order_num < $max_order) || ($deliver_assign['deliver_id'] == 0 && !in_array($this->deliver_session['uid'], $record_array) && $current_order_num < $max_order) || $deliver_assign['deliver_id'] == $this->deliver_session['uid']) {
                        //    $list[] = $v;
                        //}
                    }
                }

                $list = array();
                //最先显示指派的订单
                if(count($first_list) > 0){
                    $list[] = $first_list[0];
                }
                //
                if(count($list) == 0 &&  count($second_list) > 0){
                    $list[] = $second_list[0];
                }

                if(count($list) == 0 &&  count($third_list) > 0){
                    $list[] = $third_list[0];
                }
            }

            $num_arr = $this->index_count(1);

			if (empty($list)) {
				exit(json_encode(array('err_code' => true,'gray_count' => $num_arr['gray_count'], 'deliver_count' => $num_arr['deliver_count'], 'finish_count' => $num_arr['finish_count'],'work_status'=>$num_arr['work_status'],'just_new'=>$num_arr['just_new'])));
			}
			
			foreach ($list as &$val) {
				switch ($val['pay_type']) {
					case 'offline':
                    case 'Cash':
						$val['pay_method'] = 0;
						break;
					default:
						if ($val['paid']) {
							$val['pay_method'] = 1;
						} else {
							$val['pay_method'] = 0;
						}
						break;
				}

                $show_create_time = time() -$val['create_time'];
                $show_dining_time = time() - ($val['create_time'] + $val['dining_time']*60);

                if($show_dining_time < 0) {
                    $val['is_dinning'] = 0;
                    $val['show_dining_time'] = show_time($show_dining_time*-1);
                }else {
                    $val['is_dinning'] = 1;
                    $val['show_dining_time'] = show_time_min_ago($show_dining_time);
                }

                $val['show_create_time'] = show_time_min_ago($show_create_time);
                //$val['show_dining_time'] = show_time_ago($show_dining_time);

                $val['deliver_cash'] = floatval($val['deliver_cash']);
				$val['distance'] = floatval($val['distance']);
				$val['freight_charge'] = floatval($val['freight_charge']);
                $val['meal_time'] = date('Y-m-d H:i',($val['create_time'] + $val['dining_time']*60));
				$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
				$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
				$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
				$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
				$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
                $val['user_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $val['aim_lat'], $val['aim_lnt']));
				$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));

				$order = D('Shop_order')->get_order_by_orderid($val['order_id']);
				$val['tip_charge'] = $order['tip_charge'];
				$val['deliver_income'] = $val['freight_charge']+$val['tip_charge'];
                $val['uid'] = $order['uid'];
                $val['total_price'] = $order['total_price'];

                $address = D('User_adress')->where(array('adress_id'=>$order['address_id']))->find();
                if(!$address) {
                    $address = array();
                    $address['adress_id'] = 0;
                    $address['uid'] = $order['uid'];
                    $address['adress'] = $val['aim_site'];
                    $address['longitude'] = $val['aim_lnt'];
                    $address['latitude'] = $val['aim_lat'];
                }

                $val['user_address'] = $address;

                $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
                $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
			}
			//array('gray_count' => $gray_count, 'deliver_count' => $deliver_count, 'finish_count' => $finish_count,'work_status'=>$this->deliver_session['work_status']);
			exit(json_encode(array('err_code' => false, 'list' => $list,'gray_count' => $num_arr['gray_count'], 'deliver_count' => $num_arr['deliver_count'], 'finish_count' => $num_arr['finish_count'],'work_status'=>$num_arr['work_status'],'just_new'=>$num_arr['just_new'])));
		}
		
		//$this->display();
	}

	public function process(){
        $city_id = $this->deliver_session['city_id'];
        $city = D('Area')->where(array('area_id'=>$city_id))->find();

        if($this->deliver_session['work_status'] == 0 || $city['urgent_time'] != 0) {
            $my_distance = $this->deliver_session['range'] * 1000;
            $time = time();
            $where = "`create_time`<$time AND `status`=1 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$this->deliver_session['lat']}*PI()/180-`from_lat`*PI()/180)/2),2)+COS({$this->deliver_session['lat']}*PI()/180)*COS(`from_lat`*PI()/180)*POW(SIN(({$this->deliver_session['lng']}*PI()/180-`from_lnt`*PI()/180)/2),2)))*1000) < $my_distance ";
            if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
                $where = "`type`= 1 AND `store_id`=" . $this->deliver_session['store_id'] . " AND " . $where;
            } else {
                $where = "`type`= 0 AND " . $where;
            }

            //$gray_count = D("Deliver_supply")->where($where)->count();
            //garfunkel 添加派单逻辑
            $gray_list = D("Deliver_supply")->where($where)->select();
            $gray_count = 0;
            foreach ($gray_list as $k => $v) {
                $store = D('Merchant_store')->field(true)->where(array('store_id' => $v['store_id']))->find();
                if ($store['city_id'] == $city_id) {
                    $supply_id = $v['supply_id'];
                    $deliver_assign = D('Deliver_assign')->field(true)->where(array('supply_id' => $supply_id))->find();
                    $record_array = explode(',', $deliver_assign['record']);
                    //派单列表中不存在 || 派单列表中开放 || 指定派单 && 不在转接等候期
                    if (!$deliver_assign || ($deliver_assign['deliver_id'] == 0 && !in_array($this->deliver_session['uid'], $record_array)) || $deliver_assign['deliver_id'] == $this->deliver_session['uid']) {
                        $gray_count += 1;
                    }
                }
            }
        }else{
            $gray_count = 0;
        }

        $deliver_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'status' => array(array('gt', 0), array('lt', 5))))->count();

        $this->assign(array('gray_count' => $gray_count, 'deliver_count' => $deliver_count));
	    $this->display();
    }

    public function get_process(){
	    if(IS_POST){
	        $status = $_POST['status'];

            $uid = $this->deliver_session['uid'];
            $where = array();
            if($status == 0)
                $where['status'] = array("between","2,4");
            else
                $where['status'] = $status;

            $where['uid'] = $uid;
            if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
                $where['store_id'] = $this->deliver_session['store_id'];
            }
            $list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->select();
            if (false === $list) {
                $this->error("系统错误");
                exit;
            }

            if(!$list){
                $list = array();
            }

            foreach ($list as &$val) {
                switch ($val['pay_type']) {
                    case 'offline':
                    case 'Cash':
                        $val['pay_method'] = 0;
                        break;
                    default:
                        if ($val['paid']) {
                            $val['pay_method'] = 1;
                        } else {
                            $val['pay_method'] = 0;
                        }
                        break;
                }

                $show_create_time = time() -$val['create_time'];
                $show_dining_time = time() - ($val['create_time'] + $val['dining_time']*60);

                if($show_dining_time < 0) {
                    $val['is_dinning'] = 0;
                    $val['show_dining_time'] = show_time($show_dining_time*-1);
                }else {
                    $val['is_dinning'] = 1;
                    $val['show_dining_time'] = show_time_ago($show_dining_time);
                }

                $val['show_create_time'] = show_time_ago($show_create_time);
                //$val['show_dining_time'] = show_time_ago($show_dining_time);

                $val['deliver_cash'] = floatval($val['deliver_cash']);
                $val['distance'] = floatval($val['distance']);
                $val['freight_charge'] = floatval($val['freight_charge']);
                $val['meal_time'] = date('Y-m-d H:i',($val['create_time'] + $val['dining_time']*60));
                $val['create_time'] = date('Y-m-d H:i', $val['create_time']);
                $val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
                $val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
                $val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
 			    $val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $this->deliver_session['lat'], $this->deliver_session['lng']));
                $val['user_distance'] = getRange(getDistance($val['aim_lat'], $val['aim_lnt'], $this->deliver_session['lat'], $this->deliver_session['lng']));
                $val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
                if ($val['change_log']) {
                    $changes = explode(',', $val['change_log']);
                    $uid = array_pop($changes);
                    $val['change_name'] = $this->getDeliverUser($uid);
                }

                $order = D('Shop_order')->get_order_by_orderid($val['order_id']);
                $val['customer_id'] = $order['uid'];
                $val['tip_charge'] = $order['tip_charge'];
                $address = D('User_adress')->where(array('adress_id'=>$order['address_id']))->find();
                if(!$address){
                    $address['adress'] = $val['aim_site'];
                    $address['detail'] = '';
                }
                //获取翻译
                if(C('DEFAULT_LANG') != 'zh-cn'){
                    if(!checkEnglish($address['detail']) && $address['detail'] != '' && $address['detail_en'] == ''){
                        $detail_en = translationCnToEn($address['detail']);
                        D('User_adress')->where(array('adress_id'=>$order['address_id']))->save(array('detail_en'=>$detail_en));
                        $address['detail_en'] = $detail_en;
                    }
                }
                if(C('DEFAULT_LANG') != 'zh-cn' && $address['detail_en'] != ''){
                    $address['detail'] = $address['detail_en'];
                }
                $val['user_address'] = $address;
                $val['not_touch'] = $order['not_touch'];

                $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
                $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
            }

            $acc_num = $this->deliver_supply->where(array('status'=>2,'uid'=>$uid))->count();
            $pick_num = $this->deliver_supply->where(array('status'=>3,'uid'=>$uid))->count();
            $route_num = $this->deliver_supply->where(array('status'=>4,'uid'=>$uid))->count();

            if(count($list) == 0)
                exit(json_encode(array('error_code' => true, 'list' => $list,'anum'=>$acc_num,'pnum'=>$pick_num,'rnum'=>$route_num)));
            else
                exit(json_encode(array('error_code' => false, 'list' => $list,'anum'=>$acc_num,'pnum'=>$pick_num,'rnum'=>$route_num)));
        }
    }
	
	//取
	public function pick() 
	{
		$uid = $this->deliver_session['uid'];
		if (IS_POST) {
			$supply_id = intval(I("supply_id"));
			if (!$supply_id) {
				$this->error("参数错误");
				exit;
			}
			
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->find();
			if (empty($supply)) {
				$this->error("This order has been removed from you");
				exit;
			}
			if ($supply['status'] != 2) {
				$this->error("此单暂时不能进行取货操作");
				exit;
			}
			
			$order_id = $supply['order_id'];
			
			$columns = array();
			$columns['uid'] = $uid;
			$columns['status'] = 3;
			$result = $this->deliver_supply->where(array("supply_id"=>$supply_id, 'status'=>2))->data($columns)->save();
			if (false === $result) {
				$this->error("更新状态失败");
				exit;
			}
			if ($supply['item'] == 1) {
				//获取订单信息
				$order = D("Waimai_order")->find($order_id);
				if (!$order) {
					$this->rollback($supply_id, 2);
					$this->error("订单信息错误");
					exit;
				}
				
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>4))->save();
				if (!$result) {
					$this->rollback($supply_id, 2);
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 4;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 2);
					$this->error("添加订单日志失败");
					exit;
				}
			} elseif ($supply['item'] == 0) {
				//更新订单状态
				$result = D("Meal_order")->where(array('order_id' => $order_id))->data(array('order_status' => 4))->save();
				if (!$result) {
					$this->rollback($supply_id, 2);
					$this->error("更新订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {
				//更新订单状态
				$result = D("Shop_order")->where(array('order_id' => $order_id))->data(array('order_status' => 3))->save();
				$curr_order = D("Shop_order")->where(array('order_id' => $order_id))->find();
				if (!$result && $curr_order['order_status'] != 3) {
					$this->rollback($supply_id, 2);
					$this->error("更新订单信息错误");
					exit;
				}
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 4, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
			}
			$this->success("Successful");
			exit;
		}
//		$where = array();
//		$where['status'] = 2;
//		$where['uid'] = $uid;
//		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
//			$where['store_id'] = $this->deliver_session['store_id'];
//		}
//		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->select();
//		if (false === $list) {
//			$this->error("系统错误");
//			exit;
//		}
//
//		foreach ($list as &$val) {
//			switch ($val['pay_type']) {
//				case 'offline':
//                case 'Cash':
//					$val['pay_method'] = 0;
//					break;
//				default:
//					if ($val['paid']) {
//						$val['pay_method'] = 1;
//					} else {
//						$val['pay_method'] = 0;
//					}
//					break;
//			}
//			$val['deliver_cash'] = floatval($val['deliver_cash']);
//			$val['distance'] = floatval($val['distance']);
//			$val['freight_charge'] = floatval($val['freight_charge']);
//            $val['meal_time'] = date('Y-m-d H:i',($val['create_time'] + $val['dining_time']*60));
//            $val['create_time'] = date('Y-m-d H:i', $val['create_time']);
//			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
//			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
//			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
//// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
//			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
//			if ($val['change_log']) {
//				$changes = explode(',', $val['change_log']);
//				$uid = array_pop($changes);
//				$val['change_name'] = $this->getDeliverUser($uid);
//			}
////            $order = D('Shop_order')->get_order_by_orderid($val['order_id']);
////            $val['tip_charge'] = $order['tip_charge'];
////            $val['uid'] = $order['uid'];
////            $val['deliver_cash'] = $val['deliver_cash'];
//            $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
//            $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
//		}
//		$this->assign('list', $list);
//		$this->display();
	}
	
	private function getDeliverUser($uid)
	{
		$user = D('Deliver_user')->field(true)->where(array('uid' => $uid))->find();
		return isset($user['name']) ? $user['name'] : '';
	}
	//送
	public function send() 
	{
		$uid = $this->deliver_session['uid'];
		if (IS_POST) {
			$supply_id = intval(I("supply_id"));
			if (!$supply_id) {
				$this->error("参数错误");
				exit;
			}
			
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->find();
			if (empty($supply)) {
				$this->error("This order has been removed from you");
				exit;
			}
			if ($supply['status'] != 3) {
				$this->error("此单暂时不能进行配送操作");
				exit;
			}
			
			$order_id = $supply['order_id'];
			
// 			D()->startTrans();
			$columns = array();
			$columns['uid'] = $uid;
			$columns['status'] = 4;
			//$columns['end_time'] = time();
			$result = $this->deliver_supply->where(array("supply_id"=>$supply_id, 'status'=>3))->data($columns)->save();
			if (false === $result) {
				$this->error("更新状态失败");exit;
			}
			if ($supply['item'] == 1) {
				//获取订单信息
				$order = D("Waimai_order")->find($order_id);
				if (!$order) {
					$this->rollback($supply_id, 3);
					$this->error("订单信息错误");
					exit;
				}
				
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>5))->save();
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 5;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("添加订单日志失败");
					exit;
				}
			} elseif ($supply['item'] == 0) {
				//更新订单状态
				$result = D("Meal_order")->where(array('order_id' => $order_id))->data(array('order_status' => 5))->save();
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("更新订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {
// 				D()->commit();
				//更新订单状态
				$result = D("Shop_order")->where(array('order_id' => $order_id))->data(array('order_status' => 4))->save();
				if (!$result) {
					$this->rollback($supply_id, 3);
					$this->error("更新订单信息错误");
					exit;
				}
				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 5, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
			}
			//添加送餐员路线记录
            $where = array('uid'=>$uid,'status' => array(array('gt', 1), array('lt', 5)));
			//获取该送餐员所有未完成订单
            $user_order = D('Deliver_supply')->field(true)->where($where)->select();
            if(count($user_order) == 1){//如果只有一张订单 即当前订单
                $data['deliver_id'] = $uid;
                $data['order_id'] = $order_id;
                $data['destination_lat'] = $supply['aim_lat'];
                $data['destination_lng'] = $supply['aim_lnt'];
                $data['type'] = 1;
            }else{//如果有多张订单 需要进行逻辑判断
                $data = $this->routeAssign($uid);
            }
            //记录去往下一个节点的信息
            $route = D('Deliver_route')->where(array('deliver_id'=>$uid))->find();
            if($route){
                D('Deliver_route')->where(array('deliver_id'=>$uid))->save($data);
            }else{
                D('Deliver_route')->add($data);
            }

			$this->success("Successful");
			exit;
		}
//		$where = array();
//		$where['status'] = 3;
//		// $where['item'] = 1;
//		$where['uid'] = $uid;
//		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
//			$where['store_id'] = $this->deliver_session['store_id'];
//		}
//		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->select();
//		if (false === $list) {
//			$this->error("系统错误");exit;
//		}
//
//		foreach ($list as &$val) {
//			switch ($val['pay_type']) {
//				case 'offline':
//                case 'Cash':
//					$val['pay_method'] = 0;
//					break;
//				default:
//					if ($val['paid']) {
//						$val['pay_method'] = 1;
//					} else {
//						$val['pay_method'] = 0;
//					}
//					break;
//			}
//			$val['deliver_cash'] = floatval($val['deliver_cash']);
//			$val['distance'] = floatval($val['distance']);
//			$val['freight_charge'] = floatval($val['freight_charge']);
//			$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
//			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
//			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
//			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
//// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
//			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
//			if ($val['change_log']) {
//				$changes = explode(',', $val['change_log']);
//				$uid = array_pop($changes);
//				$val['change_name'] = $this->getDeliverUser($uid);
//			}
//
//            $order = D('Shop_order')->get_order_by_orderid($val['order_id']);
//            $val['tip_charge'] = $order['tip_charge'];
//            $val['uid'] = $order['uid'];
//            $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
//            $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
//		}
//		$this->assign('list', $list);
//		$this->display();
	}
	
	//我的
	public function my()
	{
		$uid = $this->deliver_session['uid'];
		if (IS_POST) {
			$supply_id = intval(I("supply_id"));
			if (! $supply_id) {
				$this->error("参数错误");exit;
			}
			
			$supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->find();
			if (empty($supply)) {
				$this->error("This order has been removed from you");
				exit;
			}
			if ($supply['status'] != 4) {
				$this->error("此单暂时不能进行配送完成操作");
				exit;
			}
			
			$order_id = $supply['order_id'];
			
// 			D()->startTrans();
			$columns = array();
			$columns['uid'] = $uid;
			$columns['status'] = 5;
			$columns['paid'] = 1;
			$columns['end_time'] = time();

			if($_POST['photo'] && $_POST['photo'] != '') $columns['photo'] = $_POST['photo'];

			if ($supply['type'] == 0 && $supply['pay_type'] == 'offline') {
				$columns['pay_type'] = 'Cash';
			}
				
			$result = $this->deliver_supply->where(array("supply_id"=>$supply_id, 'status'=>4))->data($columns)->save();
			if (false === $result) {
				$this->error("更新状态失败");exit;
			}
			
			if ($supply['item'] == 1) {
			
				//获取订单信息
				$order = D("Waimai_order")->find($order_id);
				if (!$order) {
					$this->rollback($supply_id, 4);
					$this->error("订单信息错误");
				}
				
				//更新订单状态
				$result = D("Waimai_order")->where(array('order_id'=>$order_id))->data(array('order_status'=>1))->save();
				if (!$result) {
					$this->rollback($supply_id, 4);
// 					D()->rollback();
					$this->error("更新订单信息错误");
					exit;
				}
				//添加订单日志
				$log = array();
				$log['status'] = 1;
				$log['order_id'] = $order_id;
				$log['store_id'] = $order['store_id'];
				$log['uid'] = $uid;
				$log['time'] = time();
				$log['group'] = 4;
				$result = D("Waimai_order_log")->add($log);
				if (!$result) {
					$this->rollback($supply_id, 4);
					$this->error("添加订单日志失败");
					exit;
// 					D()->rollback();
				}
// 				D()->commit();
			} elseif ($supply['item'] == 0) {
// 				D()->commit();
				if ($order = D("Meal_order")->field(true)->where(array('order_id' => $order_id))->find()) {
					$data = array('order_status' => 1, 'status' => 1);//配送状态更改成已完成，订单状态改成已消费
					if ($order['paid'] == 0) {
						$data['paid'] = 1;
						if (empty($data['pay_type']) && empty($data['pay_time'])) $data['pay_type'] = 'offline';
					}
					if (empty($order['pay_time'])) $data['pay_time'] = time();
					if (empty($order['use_time'])) $data['use_time'] = time();
					if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
					if ($result = D("Meal_order")->where(array('order_id' => $order_id))->data($data)->save()) {
						$this->meal_notice($order);
					} else {
						$this->rollback($supply_id, 4);
						$this->error("更新订单信息错误");
						exit;
					}
				} else {
					$this->rollback($supply_id, 4);
					$this->error("订单信息错误");
					exit;
				}
			} elseif ($supply['item'] == 2) {//快店的配送
// 				D()->commit();
				if ($order = D("Shop_order")->field(true)->where(array('order_id' => $order_id))->find()) {
					//配送状态更改成已完成，订单状态改成已消费
					$data = array('order_status' => 5, 'status' => 2);

					if ($order['is_pick_in_store'] == 0) {//平台配送
						if ($order['paid'] == 0 || (($order['pay_type'] == 'offline' || $order['pay_type'] == 'Cash') && empty($order['third_id']))) {
							$data['paid'] = $order['paid'] == 0 ? 1 : $order['paid'];
							$data['pay_type'] = 'Cash';
							$data['balance_pay'] = $supply['deliver_cash'];
							$order['balance_pay'] = $supply['deliver_cash'];
						}
					} else {
						if ($order['paid'] == 0) {
							$data['paid'] = 1;
							if (empty($order['pay_type']) && empty($order['pay_time'])) $data['pay_type'] = 'offline';
						}
					}
					
					if (empty($order['pay_time'])) $data['pay_time'] = time();
					if (empty($order['use_time'])) $data['use_time'] = time();
					if (empty($order['third_id'])) $data['third_id'] = $order['order_id'];
					if ($result = D("Shop_order")->where(array('order_id' => $order_id))->data($data)->save()) {
						
						if ($order['is_pick_in_store'] == 0) {//平台配送
							if ($order['paid'] == 0 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
								//D('User_money_list')->add_row($order['uid'], 1, $supply['deliver_cash'], '配送员模拟手动充值');
								//D('User_money_list')->add_row($order['uid'], 2, $supply['deliver_cash'], '用户购买快店产品');
							}
						}
						
						D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
						$this->shop_notice($order);
						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 6, 'name' => $this->deliver_session['name'], 'phone' => $this->deliver_session['phone']));
                        //更新用户订单数量信息
						$user = D('User')->where(array('uid'=>$order['uid']))->find();
						$userData = array('order_num'=>($user['order_num']+1),'last_order_time'=>$data['use_time']);
						D('User')->where(array('uid'=>$order['uid']))->save($userData);
                        //***如果送完此单后送餐员手中已无订单，并未在紧急模式，且未在排版时间内，送餐员自动下线///
                        $current_order_num = D('Deliver_supply')->where(array('uid'=>$this->deliver_session['uid'],'status'=>array('lt',5)))->count();

                        if($current_order_num == 0){
                            $city_id = $this->deliver_session['city_id'];
                            $city = D('Area')->where(array('area_id'=>$city_id))->find();
                            if($city['urgent_time'] == 0) {
                                $min = date("i");

                                $week_num = date("w");
                                $hour = date('H');

                                //if($min >= 50) $hour += 1;
                                if ($hour >= 0 && $hour < 5) {
                                    $hour = $hour + 24;
                                    $week_num = $week_num - 1 < 0 ? 6 : $week_num - 1;
                                }

                                $all_list = D('Deliver_schedule_time')->where(array('city_id' => $city_id))->select();
                                $time_ids = array();
                                foreach ($all_list as $v) {
                                    $new_hour = $hour + $city['jetlag'];
                                    if ($new_hour == $v['start_time'] || ($min >= 50 && $new_hour + 1 == $v['start_time'])) {
                                        $daylist = explode(',', $v['week_num']);
                                        if (in_array($week_num, $daylist)) {
                                            $time_ids[] = $v['id'];
                                        }
                                    }
                                }

                                $schedule_list = D('Deliver_schedule')->where(array('uid' => $this->deliver_session['uid'], 'time_id' => array('in', $time_ids), 'week_num' => $week_num, 'whether' => 1, 'status' => 1))->select();
                                if (!$schedule_list) {
                                    D('Deliver_user')->where(['uid' => $this->deliver_session['uid']])->save(['work_status' => 1, 'inaction_num' => 0]);
                                    $this->deliver_session['work_status'] = $_GET['type'];
                                    session('deliver_session', serialize($this->deliver_session));
                                }
                            }
                        }
                        ///***////

                        $store = D('Merchant_store')->where(array('store_id'=>$order['store_id']))->find();
                        $store['name'] = lang_substr($store['name'], 'en-us');

                        $sms_data['uid'] = 0;
                        $sms_data['mobile'] = $order['userphone'];
                        $sms_data['sendto'] = 'user';
                        $sms_data['tplid'] = 774533;
                        $sms_data['params'] = [
                            $store['name']
                        ];
                        //Sms::sendSms2($sms_data);

                        $sms_txt = "Your order from ".$store['name']." has arrived. Please feel free to contact the Tutti Support Team if there are any issues with your order. Thank you for choosing Tutti!";
                        //Sms::telesign_send_sms($order['userphone'],$sms_txt,0);
                        Sms::sendTwilioSms($order['userphone'],$sms_txt);
					} else {
						$this->rollback($supply_id, 4);
						$this->error("更新订单信息错误");
						exit;
					}
				} else {
					$this->rollback($supply_id, 4);
					$this->error("订单信息错误");
					exit;
				}
			}
			
			D('Deliver_user')->where(array('uid' => $this->deliver_session['uid']))->setInc('num');
			//统计每日配送订单量
			$date = date('Ymd');
			if ($deliver_count = D('Deliver_count')->field(true)->where(array('uid' => $this->deliver_session['uid'], 'today' => $date))->find()) {
				D('Deliver_count')->where(array('uid' => $this->deliver_session['uid'], 'today' => $date))->setInc('num');
			} else {
				D('Deliver_count')->add(array('uid' => $this->deliver_session['uid'], 'today' => $date, 'num' => 1));
			}
			//添加送餐员路线记录
            $where = array('uid'=>$uid,'status' => array(array('gt', 1), array('lt', 5)));
            //获取该送餐员所有未完成订单
            $user_order = D('Deliver_supply')->field(true)->where($where)->select();
            if(count($user_order) == 0){//如果该送餐员已经没有问完成订单 删除他的路线记录
                D('Deliver_route')->where(array('deliver_id'=>$uid))->delete();
            }else if(count($user_order) == 1){//如果只有一张未完成订单
                $curr_supply = $user_order[0];
                if($curr_supply['status'] == 4){//只差未送到客户手中
                    $data['deliver_id'] = $uid;
                    $data['order_id'] = $curr_supply['order_id'];
                    $data['destination_lat'] = $curr_supply['aim_lat'];
                    $data['destination_lng'] = $curr_supply['aim_lnt'];
                    $data['type'] = 1;
                }else{//尚未取餐
                    $data['deliver_id'] = $uid;
                    $data['order_id'] = $curr_supply['order_id'];
                    $data['destination_lat'] = $curr_supply['from_lat'];
                    $data['destination_lng'] = $curr_supply['from_lnt'];
                    $data['type'] = 0;
                }
                //记录去往下一个节点的信息
                $route = D('Deliver_route')->where(array('deliver_id'=>$uid))->find();
                if($route){
                    D('Deliver_route')->where(array('deliver_id'=>$uid))->save($data);
                }else{
                    D('Deliver_route')->add($data);
                }
            }else{//如果有多张订单 需要进行逻辑判断
                $data = $this->routeAssign($uid);
                //记录去往下一个节点的信息
                $route = D('Deliver_route')->where(array('deliver_id'=>$uid))->find();
                if($route){
                    D('Deliver_route')->where(array('deliver_id'=>$uid))->save($data);
                }else{
                    D('Deliver_route')->add($data);
                }
            }

			$this->success("Successful");
			exit;
		}
//		$where = array();
//		$where['status'] = 4;
//		// $where['item'] = 1;
//		$where['uid'] = $uid;
//		$where['is_hide'] = 0;
//		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
//			$where['store_id'] = $this->deliver_session['store_id'];
//		}
//		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->select();
//		if (false === $list) {
//			$this->error("系统错误");exit;
//		}
//
//		foreach ($list as &$val) {
//			switch ($val['pay_type']) {
//				case 'offline':
//                case 'Cash':
//					$val['pay_method'] = 0;
//					break;
//				default:
//					if ($val['paid']) {
//						$val['pay_method'] = 1;
//					} else {
//						$val['pay_method'] = 0;
//					}
//					break;
//			}
//			$val['deliver_cash'] = floatval($val['deliver_cash']);
//			$val['distance'] = floatval($val['distance']);
//			$val['freight_charge'] = floatval($val['freight_charge']);
//			$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
//			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
//			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
//			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
//// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
//			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
//			if ($val['change_log']) {
//				$changes = explode(',', $val['change_log']);
//				$uid = array_pop($changes);
//				$val['change_name'] = $this->getDeliverUser($uid);
//			}
//            $order = D('Shop_order')->get_order_by_orderid($val['order_id']);
//            $val['tip_charge'] = $order['tip_charge'];
//            $val['uid'] = $order['uid'];
//            $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
//            $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
//		}
//		$this->assign('list', $list);
//		$this->display();
	}


	private function shop_notice($order)
	{
		if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find()) {
			//增加商家余额
			$order['order_type']='shop';
			D('Merchant_money_list')->add_money($store['mer_id'],'用户购买快店订单记入收入',$order);

			//商家推广分佣
	        $now_user = M('User')->where(array('uid' => $order['uid']))->find();
	        D('Merchant_spread')->add_spread_list($order, $now_user, 'shop', $now_user['nickname'] . '用户购买快店商品获得佣金');

			//积分
			D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay'])* $this->config['score_get']), '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);

			D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.$store['name'] . ' 中消费获得'.$this->config['score_name']);

			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
		
			//短信
			$sms_data = array('mer_id' => $store['mer_id'], 'store_id' => $store['store_id'], 'type' => 'shop');
			if ($this->config['sms_shop_finish_order'] == 1 || $this->config['sms_shop_finish_order'] == 3) {
				if (empty($order['phone'])) {
					$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
					$order['phone'] = $user['phone'];
				}
				$sms_data['uid'] = $order['uid'];
				$sms_data['mobile'] = $order['userphone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您在 ' . $store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
				Sms::sendSms($sms_data);
			}
			if ($this->config['sms_shop_finish_order'] == 2 || $this->config['sms_shop_finish_order'] == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';
				$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
				Sms::sendSms($sms_data);
			}
		
			//小票打印 主打印
			$msg = ArrayToStr::array_to_str($order['order_id'], 'shop_order');
			$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
			$op->printit($store['mer_id'], $order['store_id'], $msg, 2);
		
			//分单打印
			$str_format = ArrayToStr::print_format($order['order_id'], 'shop_order');
			foreach ($str_format as $print_id => $print_msg) {
				$print_id && $op->printit($store['mer_id'], $order['store_id'], $print_msg, 2, $print_id);
			}
		}
	}

    private function meal_notice($order)
    {
    	if ($store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find()) {
			//增加商户余额
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
			D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);

			D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.$store['name'] . ' 中消费获得'.$this->config['score_name']);

			D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
			
			//短信
			$sms_data = array('mer_id' => $store['mer_id'], 'store_id' => $store['store_id'], 'type' => 'food');
			if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
				if (empty($order['phone'])) {
					$user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
					$order['phone'] = $user['phone'];
				}
				$sms_data['uid'] = $order['uid'];
				$sms_data['mobile'] = $order['phone'];
				$sms_data['sendto'] = 'user';
				$sms_data['content'] = '您在 ' . $store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
				Sms::sendSms($sms_data);
			}
			if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
				$sms_data['uid'] = 0;
				$sms_data['mobile'] = $store['phone'];
				$sms_data['sendto'] = 'merchant';
				$sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
				Sms::sendSms($sms_data);
			}
			
			//小票打印 主打印
			$msg = ArrayToStr::array_to_str($order['order_id']);
			$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
			$op->printit($store['mer_id'], $order['store_id'], $msg, 2);
			//分单打印
			$str_format = ArrayToStr::print_format($order['order_id']);
			foreach ($str_format as $print_id => $print_msg) {
				$print_id && $op->printit($store['mer_id'], $order['store_id'], $print_msg, 2, $print_id);
			}
    	}
    }
	
	public function detail()
	{
		$supply_id = intval(I("supply_id"));
		$where = array('supply_id' => $supply_id);
		$supply = D("Deliver_supply")->where($where)->find();
		if (empty($supply)) {
			$this->error_tips("配送源不存在");
			exit;
		}

		if ($supply['uid'] && $supply['uid'] != $this->deliver_session['uid']) {
			$this->error_tips("This order has been removed from you");
			exit;
		}

        $show_dining_time = time() - ($supply['create_time'] + $supply['dining_time']*60);

        if($show_dining_time < 0) {
            $supply['is_dinning'] = 0;
            $supply['show_dining_time'] = show_time($show_dining_time*-1);
        }else {
            $supply['is_dinning'] = 1;
            $supply['show_dining_time'] = show_time_ago($show_dining_time);
        }

        $supply['totalTime'] = show_time($supply['end_time'] - $supply['start_time']);

		$supply['deliver_cash'] = floatval($supply['deliver_cash']);
		$supply['distance'] = floatval($supply['distance']);
		$supply['freight_charge'] = floatval($supply['freight_charge']);
        $supply['meal_time'] = date('Y-m-d H:i',($supply['create_time'] + $supply['dining_time']*60));
		$supply['create_time'] = date('Y-m-d H:i', $supply['create_time']);
		$supply['appoint_time'] = date('Y-m-d H:i', $supply['appoint_time']);
		$supply['order_time'] = $supply['order_time'] ? date('Y-m-d H:i', $supply['order_time']) : '--';
		$supply['end_time'] = $supply['end_time'] ? date('Y-m-d H:i', $supply['end_time']) : 'N/A';
		$supply['real_orderid'] = $supply['real_orderid'] ? $supply['real_orderid'] : $supply['order_id'];
// 		$supply['store_distance'] = getRange(getDistance($supply['from_lat'], $supply['from_lnt'], $lat, $lng));
		$supply['map_url'] = U('Deliver/map', array('supply_id' => $supply['supply_id']));

        if($supply['status'] == 0){
            $supply['statusStr'] = L('QW_PLEASECONFIRM');
        }else if($supply['status'] == 1){
            $supply['statusStr'] = L('QW_WAITING');
        }else if($supply['status'] == 2){
            $supply['statusStr'] = L('QW_Accepted');
        }else if($supply['status'] == 3){
            $supply['statusStr'] = L('QW_PICKED');
        }else if($supply['status'] == 4){
            $supply['statusStr'] = L('QW_ARRIVING');
        }else if($supply['status'] == 5){
            $supply['statusStr'] = L('QW_COMPLETED');
        }

		if ($supply['change_log']) {
			$changes = explode(',', $supply['change_log']);
			$uid = array_pop($changes);
			$supply['change_name'] = $this->getDeliverUser($uid);
		}
		
		if ($supply['item'] == 1) {//外送系统的外送
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Waimai_order")->where($where)->find();
			if (empty($order)) {
				$this->error_tips("订单信息有误");
				exit;
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$this->assign('order', $order);
			//商品信息
			$goods = D()->field(true)->table(array(C('DB_PREFIX').'waimai_sell_log'=>'sl', C('DB_PREFIX').'waimai_goods'=>'wg'))->where("sl.order_id=".$supply['order_id']." AND sl.goods_id=wg.goods_id")->select();
			$this->assign('goods', $goods);
			
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ws.store_id")->find();
			if (! $store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}

			$this->assign('store', $store);
			
			//红包信息
			$where = array();
			$where['id'] = $order['coupon_id'];
			$couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();
			$this->assign('couponInfo', $couponInfo);
			//优惠信息
			$discountInfo = json_decode($order['discount_detail'], true);
			$this->assign('discountInfo', $discountInfo);
			
		} elseif ($supply['item'] == 0) {//老的餐饮外送
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Meal_order")->where($where)->find();
			if (empty($order)) {
				$this->error_tips("订单信息有误");
				exit;
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$order['discount_price'] = $order['price'];
			$this->assign('order', $order);
			$goods = unserialize($order['info']);
			foreach ($goods as &$g) {
				$g['tools_money'] = 0;
			}
			$this->assign('goods', $goods);
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_meal'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
		} elseif ($supply['item'] == 2) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Shop_order")->where($where)->find();
            //获取翻译文字
            if(C('DEFAULT_LANG') != 'zh-cn' && $order['desc_en'] != ''){
                $supply['note'] = $order['desc_en'];
            }
			if (empty($order)) {
				$this->error_tips("订单信息有误");
				exit;
			}
			$order['pay_type_name'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
			$order['discount_price'] = $order['price'];
			$order['cue_field'] = $order['cue_field'] ? unserialize($order['cue_field']) : '';

			//garfunkel add
            $order['subtotal_price'] = $order['price'] + $order['tip_charge'];
            $order['deliver_cash'] = round($order['price'] +$order['extra_price'] + $order['tip_charge'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'] + $order['delivery_discount'], 2), 2);

            $address = D('User_adress')->where(array('adress_id'=>$order['address_id']))->find();
            $order['user_address'] = $address['adress'];

            //获取翻译
            if(C('DEFAULT_LANG') != 'zh-cn'){
                if(!checkEnglish($address['detail']) && $address['detail'] != '' && $address['detail_en'] == ''){
                    $detail_en = translationCnToEn($address['detail']);
                    D('User_adress')->where(array('adress_id'=>$order['address_id']))->save(array('detail_en'=>$detail_en));
                    $address['detail_en'] = $detail_en;
                }
            }
            if(C('DEFAULT_LANG') != 'zh-cn' && $address['detail_en'] != ''){
                $address['detail'] = $address['detail_en'];
            }
            $order['user_address_detail'] = $address['detail'];

            switch ($order['pay_type']) {
                case 'offline':
                case 'Cash':
                $order['pay_method'] = 0;
                    break;
                default:
                    if ($order['paid']) {
                        $order['pay_method'] = 1;
                    } else {
                        $order['pay_method'] = 0;
                    }
                    break;
            }

			$this->assign('order', $order);

            //店铺信息
            $store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_shop'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();

			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $supply['order_id']))->select();
			foreach ($goods as $k=>&$g) {
                $g_id = $g['goods_id'];
                if($store['menu_version'] == 2){
                    $t_goods = D('StoreMenuV2')->getProduct($g_id,$order['store_id']);
                }else {
                    $t_goods = D('Shop_goods')->get_goods_by_id($g_id);
                }
                $g['name'] = $t_goods['name'];
//				if ($g['spec']) {
//					$g['name'] = $g['name'] . '(' . $g['spec'] . ')';
//				}
				$g['tools_money'] = 0;

                $spec_desc = '';
                $spec_ids = explode('_',$g['spec_id']);
                foreach ($spec_ids as $vv){
                    $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                    $spec_desc[] = lang_substr($spec['name'],C('DEFAULT_LANG'));
                }

                //$goods['spec_desc'] = $spec_desc;

                if($g['pro_id'] != '')
                    $pro_ids = explode('|',$g['pro_id']);
                else
                    $pro_ids = array();

                $spec_desc = "";
                foreach ($pro_ids as $vv){
                    $ids = explode(',',$vv);
                    $proId = $ids[0];
                    $sId = $ids[1];

                    $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                    $nameList = explode(',',$pro['val']);
                    $name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));

                    $spec_desc[] = $name;
                }
                $goods[$k]['spec_desc'] = $spec_desc;


                if($g['dish_id'] != "" && $g['dish_id'] != null){
                    $dish_desc = array();
                    $dish_list = explode("|",$g['dish_id']);
                    foreach($dish_list as $vv){
                        $one_dish = explode(",",$vv);
                        //0 dish_id 1 id 2 num 3 price
                        if($store['menu_version'] == 1) {
                            $dish = D('Side_dish')->where(array('id'=>$one_dish[0]))->find();
                            $dish_name = lang_substr($dish['name'],C('DEFAULT_LANG'));
                            $dish_vale = D('Side_dish_value')->where(array('id' => $one_dish[1]))->find();
                            $dish_vale['name'] = lang_substr($dish_vale['name'], C('DEFAULT_LANG'));
                        }elseif ($store['menu_version'] == 2){
                            //$dish = D('StoreMenuV2')->getProduct($one_dish[0],$order['store_id']);
                            //$dish_name = $dish['name'];
                            $product_dish = D('StoreMenuV2')->getProduct($one_dish[1],$order['store_id']);
                            $dish_vale['name'] = $product_dish['name'];
                        }
                        $add_str = $one_dish[2] > 1 ? $dish_vale['name']."*".$one_dish[2] : $dish_vale['name'];

                        $dish_desc[$dish['id']]['name'] = $dish_name;
                        $dish_desc[$dish['id']]['list'][] = $add_str;
                    }

                    $goods[$k]['dish'] = $dish_desc;
                }
			}
            $this->assign('supply', $supply);
			$this->assign('goods', $goods);

			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}

            //烟酒店铺提示
            $store_tag = explode(',',$store['store_tag']);
            if(in_array(1,$store_tag) || in_array(2,$store_tag)){
                $store['tag_tip'] = 1;
            }else{
                $store['tag_tip'] = 0;
            }

			$this->assign('store', $store);
		}
		$this->display();
	}
	
	public function detail_bak()
	{
		$uid = $this->deliver_session['uid'];
		$supply_id = intval(I("supply_id"));
		if (! $supply_id) {
			$this->error_tips("参数错误");
		}
		$where = array();
		$where['uid'] = $uid;
		$where['supply_id'] = $supply_id;
		// $where['item'] = 1;
		$supply = D("Deliver_supply")->where($where)->find();
		if (! $supply) {
			$this->error_tips("配送源不存在");
		}
		$this->assign('supply', $supply);
		
		if ($supply['item'] == 1) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Waimai_order")->where($where)->find();
			if (! $order) {
				$this->error_tips("订单信息有误");
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$this->assign('order', $order);
			//商品信息
			$goods = D()->field(true)->table(array(C('DB_PREFIX').'waimai_sell_log'=>'sl', C('DB_PREFIX').'waimai_goods'=>'wg'))->where("sl.order_id=".$supply['order_id']." AND sl.goods_id=wg.goods_id")->select();
			$this->assign('goods', $goods);
			
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'waimai_store'=>'ws'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ws.store_id")->find();
			if (! $store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
			
			//红包信息
			$where = array();
			$where['id'] = $order['coupon_id'];
			$couponInfo = D("Waimai_user_coupon")->field(true)->where($where)->find();
			$this->assign('couponInfo', $couponInfo);
			//优惠信息
			$discountInfo = json_decode($order['discount_detail'], true);
			$this->assign('discountInfo', $discountInfo);
			
		} elseif ($supply['item'] == 0) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Meal_order")->where($where)->find();
			if (!$order) {
				$this->error_tips("订单信息有误");
			}
			$pay_method = D('Config')->get_pay_method();
			$order['pay_type'] = $pay_method[$order['pay_type']]['name'];
			$order['discount_price'] = $order['price'];
			$this->assign('order', $order);
			$goods = unserialize($order['info']);
			foreach ($goods as &$g) {
				$g['tools_money'] = 0;
			}
			$this->assign('goods', $goods);
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_meal'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
		} elseif ($supply['item'] == 2) {
			//订单信息
			$where = array();
			$where['order_id'] = $supply['order_id'];
			$order = D("Shop_order")->where($where)->find();
			if (! $order) {
				$this->error_tips("订单信息有误");
			}
			$order['pay_type'] = D('Pay')->get_pay_name($order['pay_type'], $order['is_mobile_pay'], $order['paid']);
			$order['discount_price'] = $order['price'];
			$order['cue_field'] = $order['cue_field'] ? unserialize($order['cue_field']) : '';
			$this->assign('order', $order);
			$goods = D('Shop_order_detail')->field(true)->where(array('order_id' => $supply['order_id']))->select();
			foreach ($goods as &$g) {
				if ($g['spec']) {
					$g['name'] = $g['name'] . '(' . $g['spec'] . ')';
				}
				$g['tools_money'] = 0;
			}
			$this->assign('goods', $goods);
			//店铺信息
			$store = D()->field(true)->table(array(C('DB_PREFIX').'merchant_store'=>'ms', C('DB_PREFIX').'merchant_store_shop'=>'ml'))->where("ms.store_id=".$order['store_id']." AND ms.store_id=ml.store_id")->find();
			if (!$store) {
				$this->error_tips("店铺信息有误");
			}
			if ($store['total_money'] <= 0) {
				$store['send_money'] = $store['send_money'];
			} else {
				$store['send_money'] = $order['price'] > $store['total_money']? 0: $store['send_money'];
			}
			$store['tools_money'] = 0;
			if ($store['tools_money_have'] == 1) {
				foreach ($goods as $v) {
					$store['tools_money'] += $v['tools_price'] * $v['num'];
				}
			}
			$this->assign('store', $store);
		}
		$this->display();
	}
	
	//上传位置
	public  function location() 
	{
		$lng = I("lng");
		if (!$lng) {
			$this->error("获取坐标失败");
		}
		$lat = I("lat");
		if (!$lat) {
			$this->error("获取坐标失败");
		}
		$uid = $this->deliver_session['uid'];
		
		$columns = array();
		$columns['uid'] = $uid;
		$columns['lng'] = $lng;
		$columns['lat'] = $lat;
		$columns['create_time'] = time();
		
		$result = D("Deliver_user_location_log")->add($columns);
		if (!$result) {
			$this->error("位置查找失败");
		}
		$this->success("位置上传成功");
	}
	
	//位置导航
	public function map()
	{
		$supply_id = I("supply_id", 0, 'intval');
		if (! $supply_id) {
			$this->error("SupplyId不能为空");
		}
		$supply = D("Deliver_supply")->where(array('supply_id' => $supply_id))->find();
		if (! $supply) {
			$this->error("配送源不存在");
		}
		$this->assign('supply', $supply);
		$this->display();
	}
	
	public function del()
	{
		$uid = $this->deliver_session['uid'];
		$supply_id = intval(I("supply_id"));
		if ($supply = $this->deliver_supply->field(true)->where(array('uid' => $uid, 'supply_id' => $supply_id, 'status' => 5))->find()) {
			$this->deliver_supply->where(array('uid' => $uid, 'supply_id' => $supply_id, 'status' => 5))->save(array('is_hide' => 1));
			$this->success('ok');
		} else {
			$this->error("This order has been removed from you");
		}
	}
	
	public function info()
	{
		if ($this->deliver_session['store_id']) {
			$store = D('Merchant_store')->field(true)->where(array('store_id' => $this->deliver_session['store_id']))->find();
			$store_image_class = new store_image();
			$images = $store_image_class->get_allImage_by_path($store['pic_info']);
			$store['image'] = $images ? array_shift($images) : '';
			$this->assign('store', $store);
		}
		$total_list = D('Deliver_supply')->field('count(1) as cnt, sum(distance) as distance')->where(array('uid' => $this->deliver_session['uid'], 'status' => 5))->find();
		$grap_count = D('Deliver_supply')->where(array('uid' => $this->deliver_session['uid'], 'get_type' => 0))->count();
		$where['status'] = 5;
		$this->assign(array('distance' => isset($total_list['distance']) ? floatval($total_list['distance']) : 0, 'finish_total' => isset($total_list['cnt']) ? intval($total_list['cnt']) : 0, 'total' => $grap_count));
		$this->display();
	}
	
	public function orders()
	{
		$begin_time = isset($_GET['begin_time']) && $_GET['begin_time'] ? $_GET['begin_time'] : '';
		$end_time = isset($_GET['end_time']) && $_GET['end_time'] ? $_GET['end_time'] : '';
		$where = array('uid' => $this->deliver_session['uid'], 'status' => 5);
		if ($begin_time && $end_time) {
			$where['end_time'] = array(array('gt', strtotime($begin_time)), array('lt', strtotime($end_time . '23:59:59')));
		}

		$count_list = D('Deliver_supply')->field('count(1) as cnt, get_type')->where($where)->group('get_type')->select();
		
		foreach ($count_list as $row) {
			if ($row['get_type'] == 0) {
				$result['self_count'] = $row['cnt'];
			} elseif ($row['get_type'] == 1) {
				$result['system_count'] = $row['cnt'];
			} elseif ($row['get_type'] == 2) {
				$result['change_count'] = $row['cnt'];
			}
		}
		$result['begin_time'] = $begin_time;
		$result['end_time'] = $end_time;

        $b_date = $_GET['begin_time'].' 00:00:00';
        $e_date = $_GET['end_time'].' 24:00:00';

        $b_time = strtotime($b_date);
        $e_time = strtotime($e_date);

        $sql = "SELECT s.*, u.name, u.phone,o.tip_charge,o.price,o.pay_type as payType,o.coupon_price FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON s.order_id=o.order_id";

        $sql .= ' where s.uid = '.$this->deliver_session['uid'].' and s.status = 5 and o.is_del = 0 and s.end_time > 0';
        if ($begin_time && $end_time)
            $sql .= ' and s.create_time >='.$b_time.' and s.create_time <='.$e_time;
        $sql .= " order by s.create_time DESC";
        $list = D()->query($sql);

        $result['bonus'] = 0;
        $month_list = array();
        foreach ($list as $k=>&$val){
            $result['tip'] = $result['tip'] ? $result['tip'] + $val['tip_charge'] : $val['tip_charge'];
            $result['bonus'] += $val['bonus'];
            if($val['coupon_price'] > 0) $val['price'] = $val['price'] - $val['coupon_price'];
            $val['pay_type'] = $val['payType'];
            if($val['pay_type'] == 'offline' || $val['pay_type'] == 'Cash'){//统计现金
                $result['offline_money'] = $result['offline_money'] ? $result['offline_money'] + $val['price'] : $val['price'];
            }else{
                $result['online_money'] = $result['online_money'] ? $result['online_money'] + $val['price'] : $val['price'];
            }

            $result['freight_charge'] = $result['freight_charge'] ? $result['freight_charge'] + $val['freight_charge'] : $val['freight_charge'];

            switch ($val['pay_type']) {
                case 'offline':
                case 'Cash':
                    $val['pay_method'] = 0;
                    break;
                default:
                    if ($val['paid']) {
                        $val['pay_method'] = 1;
                    } else {
                        $val['pay_method'] = 0;
                    }
                    break;
            }

            $val['show_time'] = show_time($val['end_time'] - $val['create_time']);

            $val['deliver_cash'] = floatval($val['deliver_cash']);
            $val['distance'] = floatval($val['distance']);
            $val['freight_charge'] = floatval($val['freight_charge']);
            $val['create_time'] = date('Y-m-d H:i', $val['create_time']);
            $val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
            $val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
            $val['month_key'] = date('F Y', $val['end_time']);
            $val['end_time'] = $val['end_time'] ? date('m-d', $val['end_time']) : '未送达';
            $val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
            $val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
            if ($val['change_log']) {
                $changes = explode(',', $val['change_log']);
                $uid = array_pop($changes);
                $val['change_name'] = $this->getDeliverUser($uid);
            }
            $order = D('Shop_order')->get_order_by_orderid($val['order_id']);
            $val['tip_charge'] = $order['tip_charge'];
            $val['uid'] = $order['uid'];
            $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
            $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
            $val['summary'] = floatval($val['freight_charge'])+floatval($order['tip_charge']+$val['bonus']);

            $month_list[$val['month_key']]['list'][] = $val;
            $month_list[$val['month_key']]['summary'] += floatval($val['freight_charge'])+floatval($order['tip_charge']+$val['bonus']);
        }

        $result['order_count'] = count($list);

		$this->assign($result);
		$this->assign('list',$month_list);
		$this->display();
	}

	public function statistics(){
        $begin_time = isset($_GET['begin_time']) && $_GET['begin_time'] ? $_GET['begin_time'] : '';
        $end_time = isset($_GET['end_time']) && $_GET['end_time'] ? $_GET['end_time'] : '';
        $where = array('uid' => $this->deliver_session['uid'], 'status' => 5);
        if ($begin_time && $end_time) {
            $where['end_time'] = array(array('gt', strtotime($begin_time)), array('lt', strtotime($end_time . '23:59:59')));
        }

//		$result = D('Deliver_supply')->field('sum(deliver_cash) as offline_money, sum(money-deliver_cash) as online_money, sum(freight_charge) as freight_charge')->where($where)->find();
//      $result = D('Deliver_supply')->field('sum(freight_charge) as freight_charge')->where($where)->find();
        $count_list = D('Deliver_supply')->field('count(1) as cnt, get_type')->where($where)->group('get_type')->select();

        foreach ($count_list as $row) {
            if ($row['get_type'] == 0) {
                $result['self_count'] = $row['cnt'];
            } elseif ($row['get_type'] == 1) {
                $result['system_count'] = $row['cnt'];
            } elseif ($row['get_type'] == 2) {
                $result['change_count'] = $row['cnt'];
            }
        }
        $result['begin_time'] = $begin_time;
        $result['end_time'] = $end_time;

        $result['bonus'] = 0.00;

        $b_date = $_GET['begin_time'].' 00:00:00';
        $e_date = $_GET['end_time'].' 24:00:00';

        $b_time = strtotime($b_date);
        $e_time = strtotime($e_date);

        $sql = "SELECT s.*, u.name, u.phone,o.tip_charge,o.price,o.pay_type as payType,o.coupon_price,o.delivery_discount,o.merchant_reduce FROM " . C('DB_PREFIX') . "deliver_supply AS s INNER JOIN " . C('DB_PREFIX') . "deliver_user AS u ON s.uid=u.uid LEFT JOIN " . C('DB_PREFIX') . "shop_order AS o ON s.order_id=o.order_id";

        $sql .= ' where s.uid = '.$this->deliver_session['uid'].' and s.status = 5 and o.is_del = 0';
        if ($begin_time && $end_time)
            $sql .= ' and s.create_time >='.$b_time.' and s.create_time <='.$e_time;
        $sql .= " order by s.create_time DESC";
        $list = D()->query($sql);
        foreach ($list as $k=>&$val){
            $result['tip'] = $result['tip'] ? $result['tip'] + $val['tip_charge'] : $val['tip_charge'];
            $result['bonus'] += $val['bonus'];

            if($val['coupon_price'] > 0) $val['price'] = $val['price'] - $val['coupon_price'];
            if($val['delivery_discount'] > 0) $val['price'] = $val['price'] - $val['delivery_discount'];
            if($val['merchant_reduce'] > 0) $val['price'] = $val['price'] - $val['merchant_reduce'];
            $val['pay_type'] = $val['payType'];
            if($val['pay_type'] == 'offline' || $val['pay_type'] == 'Cash'){//统计现金
                $result['offline_money'] = $result['offline_money'] ? $result['offline_money'] + $val['price'] : $val['price'];
            }else{
                $result['online_money'] = $result['online_money'] ? $result['online_money'] + $val['price'] : $val['price'];
            }

            $result['freight_charge'] = $result['freight_charge'] ? $result['freight_charge'] + $val['freight_charge'] : $val['freight_charge'];

            switch ($val['pay_type']) {
                case 'offline':
                case 'Cash':
                    $val['pay_method'] = 0;
                    break;
                default:
                    if ($val['paid']) {
                        $val['pay_method'] = 1;
                    } else {
                        $val['pay_method'] = 0;
                    }
                    break;
            }
            $val['deliver_cash'] = floatval($val['deliver_cash']);
            $val['distance'] = floatval($val['distance']);
            $val['freight_charge'] = floatval($val['freight_charge']);
            $val['create_time'] = date('Y-m-d H:i', $val['create_time']);
            $val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
            $val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
            $val['end_time'] = $val['end_time'] ? date('Y-m-d H:i', $val['end_time']) : '未送达';
            $val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
            $val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
            if ($val['change_log']) {
                $changes = explode(',', $val['change_log']);
                $uid = array_pop($changes);
                $val['change_name'] = $this->getDeliverUser($uid);
            }
            $order = D('Shop_order')->get_order_by_orderid($val['order_id']);
            $val['tip_charge'] = $order['tip_charge'];
            $val['uid'] = $order['uid'];
            $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
            $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
        }

        $result['order_count'] = count($list);
        $base_one_money = 10;
        $guara_money = $base_one_money*$result['order_count'];
        $result['one_money'] = $base_one_money;
        $result['guara_money'] = $guara_money;

        $this->assign($result);
        $this->assign('list',$list);

        $today = date('Y-m-d',time());
        $today_begin = strtotime($today.'00:00');

        $month = date('Y-m',time());
        $month_begin = strtotime($month."-01 00:00");

        $where = array('uid' => $this->deliver_session['uid'], 'status' => 5);
        $where['end_time'] = array(array('gt', $today_begin), array('lt', time()));

        $today_data = D('Deliver_supply')->where($where)->find();
        $t_data['num'] = count($today_data);
        $all_t_money = 0;
        $all_t_distance = 0;
        foreach ($today_data as $v){
            $order = D('Shop_order')->get_order_by_orderid($v['order_id']);
            $all_t_money += $v['freight_charge'];
            $all_t_money += $order['tip_charge'];
            $all_t_distance += $v['distance'];
        }
        $t_data['money'] = $all_t_money;
        $t_data['distance'] = $all_t_distance;
        $this->assign('today_data',$t_data);

        $where['end_time'] = array(array('gt', $month_begin), array('lt', time()));
        $month_data = D('Deliver_supply')->where($where)->find();
        $m_data['num'] = count($month_data);
        $all_m_money = 0;
        $all_m_distance = 0;
        foreach ($month_data as $v){
            $order = D('Shop_order')->get_order_by_orderid($v['order_id']);
            $all_m_money += $v['freight_charge'];
            $all_m_money += $order['tip_charge'];
            $all_m_distance += $v['distance'];
        }
        $m_data['money'] = $all_m_money;
        $m_data['distance'] = $all_m_distance;
        $this->assign('month_data',$m_data);

        $this->display();
    }
	
	public function finish()
	{
		$this->display();
	}
	
	public function ajaxFinish()
	{
		$where = array();
		$page = isset($_GET['page']) && $_GET['page'] ? intval($_GET['page']) : 1;
		$page = max(1, $page);
		$where['status'] = 5;
		$where['is_hide'] = 0;
		$where['uid'] = $this->deliver_session['uid'];
		if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
			$where['store_id'] = $this->deliver_session['store_id'];
		}
		$count = $this->deliver_supply->where($where)->count();
		
		$page_size = 10;
		$start = $page_size * ($page - 1);
		
		$list = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->limit($start . ',' . $page_size)->select();
		foreach ($list as &$val) {
			switch ($val['pay_type']) {
				case 'offline':
                case 'Cash':
					$val['pay_method'] = 0;
					break;
				default:
					if ($val['paid']) {
						$val['pay_method'] = 1;
					} else {
						$val['pay_method'] = 0;
					}
					break;
			}
			$val['deliver_cash'] = floatval($val['deliver_cash']);
			$val['distance'] = floatval($val['distance']);
			$val['freight_charge'] = floatval($val['freight_charge']);
			$val['create_time'] = date('Y-m-d H:i', $val['create_time']);
			$val['appoint_time'] = date('Y-m-d H:i', $val['appoint_time']);
			$val['order_time'] = $val['order_time'] ? date('Y-m-d H:i', $val['order_time']) : '--';
			$val['end_time'] = $val['end_time'] ? date('Y-m-d H:i', $val['end_time']) : '未送达';
			$val['real_orderid'] = $val['real_orderid'] ? $val['real_orderid'] : $val['order_id'];
// 			$val['store_distance'] = getRange(getDistance($val['from_lat'], $val['from_lnt'], $lat, $lng));
			$val['map_url'] = U('Deliver/map', array('supply_id' => $val['supply_id']));
			if ($val['change_log']) {
				$changes = explode(',', $val['change_log']);
				$uid = array_pop($changes);
				$val['change_name'] = $this->getDeliverUser($uid);
			}
            $order = D('Shop_order')->get_order_by_orderid($val['order_id']);
            $val['tip_charge'] = $order['tip_charge'];
            $val['uid'] = $order['uid'];
            $store = D('Merchant_store')->field(true)->where(array('store_id'=>$val['store_id']))->find();
            $val['store_name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
		}
		exit(json_encode(array('total' => ceil($count/$page_size), 'list' => $list, 'count' => $count, 'err_code' => false)));
	}

	public function online(){
        $uid = $this->deliver_session['uid'];
	    if($_POST){
            $supply_id = intval(I("supply_id"));
            if (! $supply_id) {
                $this->error("参数错误");exit;
            }

            $supply = $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->find();
            if (empty($supply)) {
                $this->error("This order has been removed from you");
                exit;
            }
            if ($supply['status'] != 4) {
                $this->error("此单暂时不能进行配送完成操作");
                exit;
            }

            $order_id = $supply['order_id'];
            $post_data['order_id'] = 'vicisland_'.$order_id;
            $post_data['cust_id'] = 'Deliver'.$uid;
            $post_data['name'] = $_POST["name"];
            $post_data['card_num'] = $_POST["card_num"];
            $post_data['expiry'] = $_POST["expiry"];
            $post_data['charge_total'] = $_POST["charge_total"];
//            $post_data['charge_total'] = sprintf("%.2f", $post_data['charge_total']);
            $post_data['tip'] = $_POST["tip"];
            $post_data['rvarwap'] = $_POST["rvarwap"];

//            var_dump($post_data);die();
            import('@.ORG.pay.MonerisPay');
            $moneris_pay = new MonerisPay();
            $resp = $moneris_pay->payment($post_data,0);
            if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                $order = explode("_",$post_data['order_id']);
                $order_id = $order[1];
                $url =U("Wap/Deliver/detail")."&supply_id=".$supply_id."&from=index";

                //修改supply 和 order的支付相关数据
                $data['pay_type'] = 'moneris';
                $data['money'] = 0.00;
                $data['paid'] = 1;
                $data['deliver_cash'] = 0;
                $this->deliver_supply->field(true)->where(array('supply_id' => $supply_id, 'uid' => $this->deliver_session['uid']))->save($data);

                $order_data['tip_charge'] = $post_data['tip'];
                $order_data['paid'] = 1;
                $order_data['pay_type'] = 'moneris';
                $order_data['pay_time'] = time();
                $order_data['payment_money'] = $resp['transAmount'];

                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_data);

                $this->success(L('_PAYMENT_SUCCESS_'),$url,true);
            }else{
                //$this->error($resp['message'],'',true);
                $this->error("Declined. Please verify payment information and try again.",'',true);
            }
        }else{
            $supply_id = intval(I("supply_id"));
            $where = array();
            $where['status'] = 4;
            // $where['item'] = 1;
            $where['uid'] = $uid;
            $where['is_hide'] = 0;
            $where['supply_id'] = $supply_id;
            if ($this->deliver_session['group'] == 2 && $this->deliver_session['store_id']) {
                $where['store_id'] = $this->deliver_session['store_id'];
            }
            $supply = $this->deliver_supply->field(true)->where($where)->order("`create_time` DESC")->find();
            if (false === $supply) {
                $this->error("系统错误");exit;
            }


            switch ($supply['pay_type']) {
                case 'offline':
                case 'Cash':
                    $val['pay_method'] = 0;
                    break;
                default:
                    if ($supply['paid']) {
                        $supply['pay_method'] = 1;
                    } else {
                        $supply['pay_method'] = 0;
                    }
                    break;
            }
            $supply['deliver_cash'] = floatval($supply['deliver_cash']);
            $supply['distance'] = floatval($supply['distance']);
            $supply['freight_charge'] = floatval($supply['freight_charge']);
            $supply['create_time'] = date('Y-m-d H:i', $supply['create_time']);
            $supply['appoint_time'] = date('Y-m-d H:i', $supply['appoint_time']);
            $supply['order_time'] = $supply['order_time'] ? date('Y-m-d H:i', $supply['order_time']) : '--';
            $supply['real_orderid'] = $supply['real_orderid'] ? $supply['real_orderid'] : $supply['order_id'];
            $supply['map_url'] = U('Deliver/map', array('supply_id' => $supply['supply_id']));
            if ($supply['change_log']) {
                $changes = explode(',', $supply['change_log']);
                $uid = array_pop($changes);
                $supply['change_name'] = $this->getDeliverUser($uid);
            }
            $order = D('Shop_order')->get_order_by_orderid($supply['order_id']);
            $supply['tip_charge'] = $order['tip_charge'];
            $supply['discount'] = $order['coupon_price'] + $order['delivery_discount'] + $order['merchant_reduce'];
            $supply['uid'] = $order['uid'];
            $supply['username'] = $order['username'];
            $supply['goods_price'] = $order['goods_price'];

            $deposit_price = 0;

            $order_goods = D('Shop_order_detail')->where(array('order_id'=>$order['order_id']))->select();
            foreach ($order_goods as $good){
                $deposit_price += $good['deposit_price']*$good['num'];
            }
            $supply['deposit_price'] = $deposit_price;
            $supply['tax_price'] = $order['total_price'] - $supply['goods_price'] - $supply['freight_charge'] - $supply['deposit_price'];

            $this->assign('supply', $supply);
            $this->display();
        }
    }

    public function App_update(){
        $lat = $_POST['lat'] ? $_POST['lat'] : 0;
        $lng = $_POST['lng'] ? $_POST['lng'] : 0;
        $deliver_id = $this->deliver_session['uid'];

        if($lat != 0 && $lng != 0){
            $data['lng'] = $lng;
            $data['lat'] = $lat;
            D('Deliver_user')->field(true)->where(array('uid'=>$deliver_id))->save($data);
            $this->deliver_session['lat'] = $lat;
            $this->deliver_session['lng'] = $lng;
            exit(json_encode(array('error' => 0, 'msg' => 'Success！', 'dom_id' => 'account')));
        }else{
            exit(json_encode(array('error' => 1, 'msg' => 'Fail！', 'dom_id' => 'account')));
        }

    }

    public function update_device(){
        $device_id = $_POST['token'] ? $_POST['token'] : '';

        $deliver_id = $this->deliver_session['uid'];

        if($device_id != '' ){
            $data['device_id'] = $device_id;
            D('Deliver_user')->field(true)->where(array('uid'=>$deliver_id))->save($data);
            exit(json_encode(array('error' => 0, 'msg' => 'Success！', 'dom_id' => 'account')));
        }else{
            exit(json_encode(array('error' => 1, 'msg' => 'Fail！', 'dom_id' => 'account')));
        }

    }
    //送餐员路线逻辑运算 多张订单时
    public function routeAssign($deliver_id){
        $where = array('uid'=>$deliver_id,'status' => array(array('gt', 1), array('lt', 5)));
        //获取该送餐员所有未完成订单
        $user_order = D('Deliver_supply')->field(true)->where($where)->select();
        //送餐员的当前位置
        $deliver_lat = $this->deliver_session['lat'];
        $delvier_lng = $this->deliver_session['lng'];

        //$distance = getDistance($from_lat,$from_lng,$aim_lat,$aim_lng);
        /*
         * 遍历所有节点的距离 选择最短的 记录下第一个节点 添加到送餐员路线记录表中
         * 如果订单尚未取单 必须先到达餐厅 后才可到客户
         * 在考虑距离的基础上 如果要去餐厅取餐 添加出餐时间的判断
         */
        //先取出所有节点
        //$c_point['type'] 0 店铺 1客户
        $points = array();
        foreach ($user_order as $order){
            $c_point['order'] = $order;
            $c_point['status'] = $order['status'];
            if($order['status'] != 4){//取店铺节点
                $c_point['lat'] = $order['from_lat'];
                $c_point['lng'] = $order['from_lnt'];
                $c_point['type'] = 0;
                $points[] = $c_point;
            }
            //取用户节点
            $c_point['lat'] = $order['aim_lat'];
            $c_point['lng'] = $order['aim_lnt'];
            $c_point['type'] = 1;
            $points[] = $c_point;
        }

//        $routes = $this->getRouteList(count($points),count($points),array());
//        var_dump($routes);

        $min_dis = null;
        $i = 0;
        foreach ($points as $point){
            if($point['status'] != 4 && $point['type'] != 0){
                continue;
            }else{
                $distance = getDistance($deliver_lat,$delvier_lng,$point['lat'],$point['lng']);
                $data_p['distance'] = $distance;
                $data_p['point'] = $point;

                $routes[$i] = $data_p;
                $sort_a[$distance] = $i;
                $i++;
            }
        }
        //排序
        sort($sort_a);
        $record_point = $routes[$sort_a[0]];
        if($record_point['type'] == 1){//此为一个用户节点 直接记录

        }else{//如果是个餐厅 比较一下到达时间 与出餐时间
            $chu_time = $record_point['order']['create_time'] + $record_point['order']['dining_time'] * 60;
            $c_time = $this->getDistanceTime($deliver_lat,$delvier_lng,$record_point['lat'],$record_point['lng']);
            $dao_time = time() + $c_time*60;
            //如果到达时间与出餐时间差 的绝对值 小于5分钟 直接记录 并且有下一个节点的话
            if(abs($chu_time - $dao_time)/60 > 5 && $sort_a[1]){
                //先记录之前的差值
                $r_cha = abs($chu_time - $dao_time);
                //先计算到达下一个节点的时间
                $test_points = $routes[$sort_a[1]];
                $time_1 = $this->getDistanceTime($deliver_lat,$delvier_lng,$test_points['lat'],$test_points['lng']);
                //再计算 此节点到预计节点的时间
                $time_2 = $this->getDistanceTime($test_points['lat'],$test_points['lng'],$record_point['lat'],$record_point['lng']);

                $all_time = $time_1 + $time_2;

                $new_time = time() + $all_time*60;
                //计算新的时间差值
                $n_cha = abs($chu_time - $new_time);
                if ($n_cha < $r_cha){
                    $record_point = $routes[$sort_a[1]];
                }
            }
        }

        $data['deliver_id'] = $deliver_id;
        $data['order_id'] = $record_point['point']['order']['order_id'];
        $data['destination_lat'] = $record_point['point']['lat'];
        $data['destination_lng'] = $record_point['point']['lng'];
        $data['type'] = $record_point['point']['type'];

        return $data;
    }

    public function getDistanceTime($from_lat,$from_lng,$aim_lat,$aim_lng){
        //获取两点之间的距离
        $distance = getDistance($from_lat,$from_lng,$aim_lat,$aim_lng);
        //获取预计到达时间
        $use_time = $distance / 100;
        //返回值为分钟
        return $use_time;
    }

    public function getRouteList($num,$curr,$array){
	    $all_array = array();
        for ($i = 1;$i <= $num;$i++){
            if($num == $curr){
                if(!in_array($i,$all_array)) {
                    $all_array[] = $i;
                    $array[] = $i;
                    $next_num = $curr - 1;
                    if($next_num > 0){
                        $array = $this->getRouteList($num,$next_num,$array);
                        $all_list[] = implode(',',$array);
                        $array = array();
                    }
                }
            }else{
                if(in_array($i,$array)){
                    continue;
                }else{
                    $array[] = $i;
                    $next_num = $curr - 1;
                    if($next_num > 0){
                        $array = $this->getRouteList($num,$next_num,$array);
                        return $array;
                    }else{
                        echo "henhao<br>";
                        $all_list[] = implode(',',$array);
                    }
                }
            }
        }
        return $all_list;
//        $this->getRouteList($num,$num,array());
    }

    public function reg(){
	    if($_POST){
	        $data['phone'] = $_POST['phone'];
	        $data['vfcode'] = $_POST['sms_code'];
	        if(!D('User_modifypwd')->field('id')->where($data)->find()){
                $result = array('error_code' => true, 'msg' => L('_SMS_CODE_ERROR_'));
	            $this->ajaxReturn($result);
            }

            $deliver = D('Deliver_user')->field(true)->where(array('phone'=>$data['phone']))->find();
	        if($deliver){
                $result = array('error_code' => true, 'msg' => 'This number is already registered. Please sign in directly or try a different number.');
                $this->ajaxReturn($result);
            }

            $deliver_data['phone'] = $_POST['phone'];
	        $deliver_data['email'] = $_POST['email'];
	        $deliver_data['name'] = $_POST['first_name'];
	        $deliver_data['family_name'] = $_POST['last_name'];
//	        $deliver_data['birthday'] = $_POST['birthday'];
	        $deliver_data['pwd'] = md5($_POST['password']);
	        //$deliver_data['site'] = $_POST['address'];
	        //$deliver_data['lng'] = sprintf('%.7f',$_POST['lng']);
	        //$deliver_data['lat'] = sprintf('%.7f',$_POST['lat']);
	        $deliver_data['range'] = 50;

	        if(C('DEFAULT_LANG') == 'zh-cn')
	            $deliver_data['language'] = 0;
	        else
	            $deliver_data['language'] = 1;

	        $deliver_data['create_time'] = time();

	        $deliver_data['city_id'] = 0;
	        $deliver_data['province_id'] = 0;
	        $deliver_data['circle_id'] = 3446;
	        $deliver_data['area_id'] = 0;

	        //注册状态
            $deliver_data['reg_status'] = 1;
            $deliver_data['last_time'] = time();
	        $deliver_id = D('Deliver_user')->add($deliver_data);

            $database_deliver_user = D('Deliver_user');
            $now_user = $database_deliver_user->field(true)->where(array('uid'=>$deliver_id))->find();
            session('deliver_session', serialize($now_user));

            $result = array('error_code'=>false,'msg'=>L('SUCCESS_BKADMIN'));
            $this->ajaxReturn($result);
        }else
	        $this->display();
    }

    public function step_1(){
        $database_deliver_user = D('Deliver_user');
        if($_POST){
            $data['uid'] = $this->deliver_session['uid'];
            //$data['driver_license'] = $_POST['img_0'];
            //$data['insurance'] = $_POST['img_1'];
            //$data['certificate'] = $_POST['img_2'];

            //$data['sin_num'] = $_POST['sin_num'] ? $_POST['sin_num'] : "";

//            $deliver_img = D('Deliver_img')->where(array('uid'=>$this->deliver_session['uid']))->find();
//            if($deliver_img)
//                D('Deliver_img')->save($data);
//            else
//                D('Deliver_img')->add($data);

//            $card_data['ahname'] = $_POST['ahname'];
//            $card_data['transit'] = $_POST['transit'];
//            $card_data['institution'] = $_POST['institution'];
//            $card_data['account'] = $_POST['account'];

//            $deliver_card = D('Deliver_card')->field(true)->where(array('deliver_id'=>$this->deliver_session['uid']))->find();
//            if($deliver_card)
//                D('Deliver_card')->where(array('deliver_id'=>$this->deliver_session['uid']))->save($card_data);
//            else{
//                $card_data['deliver_id'] = $this->deliver_session['uid'];
//                D('Deliver_card')->add($card_data);
//            }

            //新修改的注册流程
            $userdata['birthday'] = $_POST['birthday'];
            $userdata['city_id'] = $_POST['city_id'] ? $_POST['city_id'] : '0';
            $userdata['site'] = $_POST['address'] ? $_POST['address'] : '';
            $userdata['vehicle_type'] = $_POST['vehicle_type'] ? $_POST['vehicle_type'] : '0';
            $userdata['apartment'] = $_POST['apartment'] ? $_POST['apartment'] : '';
            $userdata['city_str'] = $_POST['city'] ? $_POST['city'] : '';
            $userdata['province_str'] = $_POST['province'] ? $_POST['province'] : '';
            $userdata['postal_code'] = $_POST['postal_code'] ? $_POST['postal_code'] : '';
            $userdata['lat'] = $_POST['lat'] ? $_POST['lat'] : '0';
            $userdata['lng'] = $_POST['lng'] ? $_POST['lng'] : '0';
            $userdata['reg_status'] = 2;
            $userdata['last_time'] = time();

            $database_deliver_user->where(array('uid' => $this->deliver_session['uid']))->save($userdata);

            $result = array('error_code'=>false,'msg'=>L('_B_LOGIN_REGISTSUCESS_'));
            $this->ajaxReturn($result);
        }else {
            $now_user = $database_deliver_user->field(true)->where(array('uid' => $this->deliver_session['uid']))->find();
            $this->assign('user',$now_user);
            if($now_user['reg_status'] != 1)
                header('Location:'.U('Deliver/step_'.$now_user['reg_status']));

//            $deliver_img = D('Deliver_img')->field(true)->where(array('uid'=>$this->deliver_session['uid']))->find();
//            if ($deliver_img)
//                $this->assign('deliver_img',$deliver_img);

            $city_list = D('Area')->where(array('is_open'=>1,'area_type'=>2))->select();
            $this->assign('city_list',$city_list);
            $this->display();
        }
    }

    public function step_2(){
        $database_deliver_user = D('Deliver_user');
        if($_POST){

            $data['uid'] = $this->deliver_session['uid'];
            $data['sin_num'] = $_POST['sin_num'] ? $_POST['sin_num'] : "";
            $data['driver_license'] = trim($_POST['img_1']);
            $data['insurance'] = trim($_POST['img_2']);
            $data['certificate'] = trim($_POST['img_0']);
            $data['review_desc'] = "";

            $deliver_img = D('Deliver_img')->where(array('uid'=>$this->deliver_session['uid']))->find();
            if($deliver_img && ($deliver_img['sin_num'] != $data['sin_num'] || $deliver_img['driver_license'] != $data['driver_license'] || $deliver_img['insurance'] != $data['insurance'] || $deliver_img['certificate'] != $data['certificate'])){
                /**
                $file_name = "";
                if($deliver_img['driver_license'] != $data['driver_license']) $file_name .= "Driver’s License ";
                if($deliver_img['certificate'] != $data['certificate']) $file_name .= "Work Eligibility ";
                if($deliver_img['insurance'] != $data['insurance']) $file_name .= "Vehicle Insurance ";

                if($file_name != "") $this->sendUpdateMail($this->deliver_session['uid'],$file_name);
                */
                D('Deliver_img')->save($data);
                $database_deliver_user->where(array('uid' => $this->deliver_session['uid']))->save(array('group'=>0));
            }

            if(!$deliver_img){
                D('Deliver_img')->add($data);
                $database_deliver_user->where(array('uid' => $this->deliver_session['uid']))->save(array('group'=>0));
            }

            if($_GET['from'] != 4)
                $database_deliver_user->where(array('uid' => $this->deliver_session['uid']))->save(array('reg_status'=>3,'last_time'=>time()));

            $result = array('error_code'=>false,'msg'=>L('_B_LOGIN_REGISTSUCESS_'));
            $this->ajaxReturn($result);

        }else {
            $now_user = $database_deliver_user->field(true)->where(array('uid' => $this->deliver_session['uid']))->find();
            if ($now_user['reg_status'] != 2 && $_GET['from'] != 4)
                header('Location:' . U('Deliver/step_' . $now_user['reg_status']));
            else{
                $deliver_img = D('Deliver_img')->where(array('uid'=>$this->deliver_session['uid']))->find();
                $this->assign("userImg",$deliver_img);
            }
            $this->display();
        }
    }
    public function pre_save_step3(){
        if($_POST['buy_mode']==2){   //shipping
            $user_data["bag_get_type"]=2;
            $user_data["ship_adress"]=$_POST['address'];
            $user_data["ship_apartment"]=$_POST['apartment'];
            $user_data["ship_city_str"]=$_POST['city'];
            $user_data["ship_province_str"]=$_POST['province'];
            $user_data["ship_postal_code"]=$_POST['postalcode'];
            $user_data["bag_amount"]=$_POST['bag_amount'];
            $user_data["bag_get_id"]=$_POST['bag_id'];
        }else{      //pickup
            $user_data["bag_get_type"]=1;
            $user_data["bag_get_id"]=$_POST['bag_id'];
        }
        D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save($user_data);
        D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->save(array('bag_review_desc'=>""));
    }

    public function step_3(){
        $database_deliver_user = D('Deliver_user');
        $now_user = $database_deliver_user->field(true)->where(array('uid' => $this->deliver_session['uid']))->find();
        if($_POST){
            if($_POST['own_bag'] == 1){
                $saveData['bag_get_type'] = -1;
                $saveData['bag_get_id'] = $_POST['bag_img'];
                $saveData["reg_status"] = 4;
                $saveData["last_time"] = time();
                D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->save(array('bag_review_desc'=>""));
                $database_deliver_user->where(array('uid' => $this->deliver_session['uid']))->save($saveData);
                $result = array('error_code' => false, 'msg' => L('SUCCESS_BKADMIN'));
            }else {
                $this->pre_save_step3();
                if ($_POST['just_save'] == "1") {
                    //$result = array('error_code' => true, 'msg' => "Saved");
                    $result = array('error_code' => false, 'msg' => L('SUCCESS_BKADMIN'));
                } else {
                    import('@.ORG.pay.MonerisPay.mpgClasses');
                    $where = array('tab_id' => 'moneris', 'gid' => 7);
                    $result = D('Config')->field(true)->where($where)->select();
                    foreach ($result as $v) {
                        if ($v['info'] == 'store_id')
                            $store_id = $v['value'];
                        elseif ($v['info'] == 'token')
                            $api_token = $v['value'];
                    }

                    $txnArray['type'] = 'purchase';
                    $txnArray['crypt_type'] = '7';
                    $txnArray['pan'] = $_POST['c_number'];
                    $txnArray['expdate'] = transYM($_POST['e_date']);
                    $txnArray['order_id'] = 'TuttiDeliver_' . $this->deliver_session['uid'] . '_' . time();
                    $txnArray['cust_id'] = $this->deliver_session['uid'];
                    $txnArray['amount'] = $_POST["total_price"];

                    /**************************** Transaction Object *****************************/

                    $mpgTxn = new mpgTransaction($txnArray);

                    /****************************** Request Object *******************************/

                    $mpgRequest = new mpgRequest($mpgTxn);
                    $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
                    $mpgRequest->setTestMode(false);

                    $mpgHttpPost = new mpgHttpsPost($store_id, $api_token, $mpgRequest);

                    $mpgResponse = $mpgHttpPost->getMpgResponse();

                    if ($mpgResponse->getResponseCode() != "null" && $mpgResponse->getResponseCode() < 50) {//支付成功

                        $data['card_name'] = $_POST['c_name'];
                        $data['card_num'] = $_POST['c_number'];
                        $data['expdate'] = $txnArray['expdate'];
                        $data['order_id'] = $txnArray['order_id'];
                        $data['txnNumber'] = $mpgResponse->getTxnNumber();

                        D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->save($data);

                        //D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('reg_status'=>4,'last_time'=>time()));
                        $user_data["reg_status"] = 4;
                        $user_data["last_time"] = time();

                        D('Deliver_user')->where(array('uid' => $this->deliver_session['uid']))->save($user_data);

                        //$this->sendMail($now_user);
                        $result = array('error_code' => false, 'msg' => L('_PAYMENT_SUCCESS_'));
                    } else {
                        $result = array('error_code' => true, 'msg' => "Payment Declined");
                    }
                }
            }
            $this->ajaxReturn($result);

        }else {
            $this->assign('user',$now_user);

            $database_bag = D('Bag');
            $bag = $database_bag->field(true)->where(array('bag_switch' => "1"))->select();
            foreach ($bag as &$b){
                $new_img = array();
                $bag_img = explode(';',$b['bag_photos']);
                foreach ($bag_img as $img){
                    $image_tmp = explode(',', $img);
                    $new_img[] = C('config.site_url') . '/upload/deliver/' . $image_tmp[0] . '/' . $image_tmp['1'];
                }
                $b['bag_photos'] = $new_img;
            }

            $this->assign('bag',$bag);

            $city_id=$now_user["city_id"];
            $areas=D('Area')->where(array("area_id"=>$city_id))->find();

            $this->assign('city',$areas);
            //bag_type 0:未设置 1:自取 2：邮寄 3：全选

            if($now_user['bag_get_type'] != -1) {
                $bag_list = explode("|", $now_user['bag_get_id']);
                foreach ($bag_list as &$b) {
                    $b = explode(",", $b);
                }
            }else{
                $bag_list = array();
            }
            $this->assign('bag_list',$bag_list);

            if ($now_user['reg_status'] != 3 && $_GET['from'] != 4)
                header('Location:' . U('Deliver/step_' . $now_user['reg_status']));

            $this->display();
        }
    }

    public function step_4(){
        $database_deliver_user = D('Deliver_user');
        $now_user = $database_deliver_user->field(true)->where(array('uid' => $this->deliver_session['uid']))->find();
        if($now_user['reg_status'] != 4 && $now_user['reg_status'] != 5) {
            if($_GET['type'] == 'jump' && $now_user['reg_status'] != 0){
                D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('reg_status'=>4));
                //$this->sendMail($now_user);
            }else {
                header('Location:' . U('Deliver/step_' . $now_user['reg_status']));
            }
        }

        $user_img = D("Deliver_img")->where(array('uid'=>$this->deliver_session['uid']))->find();

        $uploadStatus = 0;
        if($user_img['driver_license'] != '' && $user_img['insurance'] != '' && $user_img['certificate'] != '' && $user_img['sin_num'] != ''){//是否全部上传
            if($now_user['group'] == -1){//未通过审核
                $uploadStatus = 3;
            }else{//等待审核
                $uploadStatus = 1;
            }
        }else{//未全部上传资料
            $uploadStatus = 0;
        }

        if($now_user['group'] == 1){//通过审核
            $uploadStatus = 2;
        }

        if($user_img['card_num'] != '' && $user_img['txnNumber'] != ''){
            $is_online = 1;
        }else{
            $is_online = 0;
        }

        if($now_user['bag_get_type'] == -1){//使用自己的背包
            if($now_user['bag_get_id'] == ''){//未上传图片
                $is_online = 0;
            }else{//已上传图片
                $is_online = 1;
            }
        }else if($now_user['bag_get_type'] == 1){//自提背包

        }else if($now_user['bag_get_type'] == 2){//邮寄背包

        }else{//未选择

        }

        $city = D('Area')->where(array('area_id'=>$now_user['city_id']))->find();
        $this->assign('city',$city);

        if($now_user['reg_status'] == 5 || $now_user['reg_status'] == 0){//背包是否已发出
            $user_img['bag_received'] = 1;
            $is_online = 1;
        }else{
            $user_img['bag_received'] = 0;
        }

        $user_img['upload_status'] = $uploadStatus;
        $user_img['online_paid'] = $is_online;

        $this->assign('user',$now_user);
        $this->assign('userImg',$user_img);
        $this->display();
    }

    public function activate_deliver(){
        $database_deliver_user = D('Deliver_user');
        $now_user = $database_deliver_user->field(true)->where(array('uid' => $this->deliver_session['uid']))->find();
        if($now_user['group'] == 1 && $now_user['reg_status'] == 5){
            $database_deliver_user->where(array('uid' => $this->deliver_session['uid']))->save(array('status'=>1,'reg_status'=>0));

            $email = array(array("address" => $this->deliver_session['email'], "userName" => $this->deliver_session['name']));
            $title = "Tutti Courier Instructions";
            $body = $this->getMailBodyToDeliver($this->deliver_session['name']);
            $mail = getMail($title, $body, $email);
            $mail->send();

            $result = array('error_code' => false, 'msg' => 'Success');
        }else {
            $result = array('error_code' => true, 'msg' => 'Fail');
        }

        $this->ajaxReturn($result);
    }

    public function sendMail($now_user){
        if($now_user['email'] != "") {
            $email = array(array("address"=>$now_user['email'],"userName"=>$now_user['name']));
            if($now_user['city_id'] == 105) {
                $title = "Picking Up Your Tutti Delivery Bags";
                $body = $this->getVicMailBody($now_user['name']);
                $mail = getMail($title, $body, $email);
                $mail->send();
            }
        }
    }

    public function schedule(){
        $week_num = date("w");
        $today = time();

        $work_list = $this->getDeliverWorkList();
        $this->assign('work_list',json_encode($work_list));

        $this->assign('week_num',$week_num);
        $this->assign('today',$today);
	    $this->display();
    }

    public function set_schedule(){
	    if($_POST){
	        $uid = $this->deliver_session['uid'];
	        $default_list = $_POST['default_list'];
	        $data = $_POST['data'];
            //每次更新删除之前所有的记录
            D('Deliver_schedule')->where(array('uid'=>$uid))->delete();
            $save_list = array();
            foreach ($data as $k=>$v){
                $week_num = $k;
                foreach ($v as $kk=>$vv){
                    $save_data = array();
                    $time_id = $vv['id'];
                    if($vv['is_check'] && $vv['is_check'] == 1){
                        //$save_list[$week_num][] = $time_id;
                        $save_data['uid'] = $uid;
                        $save_data['week_num'] = $week_num;
                        $save_data['time_id'] = $time_id;
                        $save_data['whether'] = 1;
                        $save_data['set_time'] = date('Y-m-d H:i:s');
                        $save_data['status'] = 1;
                        //是否repeat
                        if(isset($default_list[$week_num])){
                            if($default_list[$week_num] == 1)
                                $save_data['is_repeat'] = 1;
                            else
                                $save_data['is_repeat'] = 0;
                        }else{
                            $save_data['is_repeat'] = 1;
                        }

                        $save_list[] = $save_data;
                    }
                }
            }

            D('Deliver_schedule')->addAll($save_list);

            exit(json_encode(array('error'=>0,'msg'=>'Success')));
        }else {
            $week_num = date("w");
            $link_num = isset($_GET['num']) ? $_GET['num'] : $week_num;
            //$today = date('Y-m-d');
            $today = time();
            $this->assign('week_num', $week_num);
            $this->assign('link_num',$link_num);
            $this->assign('today', $today);

            $default_list = array();

            $work_list = $this->getDeliverWorkList();

            $city_id = $this->deliver_session['city_id'];
            $time_list = D('Deliver_schedule_time')->where(array('city_id' => $city_id))->order('start_time asc')->select();
            //根据星期排序
            $work_time_list = array();
            for ($i = 0; $i < 7; $i++) {
                foreach ($time_list as $v) {
                    $daylist = explode(',', $v['week_num']);
                    if (in_array($i, $daylist)) {
                        if(in_array($v['id'],$work_list[$i]['id_list']))
                            $v['is_check'] = 1;

                        $d_total = D('Deliver_schedule')->where(array('time_id'=>$v['id'],'week_num'=>$i,'status'=>1))->select();
                        $min_num_list = explode(',',$v['min_num']);
                        $min_num = 0;
                        foreach ($min_num_list as $w){
                            $t_num = explode('|',$w);
                            if ($i == $t_num[0]){
                                $min_num = $t_num[1];
                            }
                        }
                        if($min_num > count($d_total)){
                            $v['is_recomm'] = 1;
                        }else{
                            $v['is_recomm'] = 0;
                        }

                        $work_time_list[$i][] = $v;
                    }
                }

                if(isset($work_list[$i]['is_repeat'])) {
                    if ($work_list[$i]['is_repeat'] == 1)
                        $default_list[$i] = 1;
                    else
                        $default_list[$i] = 0;
                }
            }

            $this->assign('work_list',json_encode($work_list));
            $this->assign('work_time_list', json_encode($work_time_list));
            $this->assign('default_list',json_encode($default_list));
            $this->display();
        }
    }

    public function getDeliverWorkList(){
	    $uid = $this->deliver_session['uid'];

	    $list = D('Deliver_schedule')->where(array('uid'=>$uid))->select();

	    $re_list = array();
	    foreach ($list as $v){
            $time_data = D('Deliver_schedule_time')->where(array('id' => $v['time_id'],'city_id'=>$this->deliver_session['city_id']))->find();
            if($time_data) {
                $re_list[$v['week_num']]['is_repeat'] = $v['is_repeat'];
                $re_list[$v['week_num']]['id_list'][] = $v['time_id'];
                $re_list[$v['week_num']]['ids'][] = $time_data;
            }
        }

        foreach ($re_list as &$v){
            array_multisort(array_column($v['ids'],'start_time'),SORT_ASC,$v['ids']);
        }
        //处理连续的时间段
        foreach ($re_list as &$v){
            $before_time = -1;
            $is_continue = false;
            $end_list = array();
            $start_time = 0;
            $end_time = 0;
            foreach ($v['ids'] as $vv){
                if($before_time == -1) {
                    $start_time = $vv['start_time'];
                    $end_time = $vv['end_time'];
                }else{
                    if($before_time == $vv['start_time']){
                        $end_time = $vv['end_time'];
                    }else{
                        $new['start_time'] = $start_time;
                        $new['end_time'] = $end_time;

                        $end_list[] = $new;

                        $start_time = $vv['start_time'];
                        $end_time = $vv['end_time'];
                    }
                }
                $before_time = $vv['end_time'];
            }
            $new['start_time'] = $start_time;
            $new['end_time'] = $end_time;

            $end_list[] = $new;

            $v['ids'] = $end_list;
        }

        return $re_list;
    }

    public function ajax_schedule_time_list(){
	    $week_num = $_GET['week'];
	    $time_list = D('Deliver_schedule_time')->select();

	    $show_list = array();
	    foreach ($time_list as $v){
            $daylist = explode(',',$v['week_num']);
            if(in_array($week_num,$daylist)){
                $show_list[] = $v['start_time'].':00 ~ '.$v['end_time'].':00';
            }
        }

        exit(json_encode($show_list));
    }

    public function ajax_upload()
    {
        if ($_FILES['file']['error'] != 4) {
            //$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
            //$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
            //$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
            //if ($store_theme) {
                //$width = '900,450';
                //$height = '900,450';
            //} else {
                $width = '900,450';
                $height = '500,250';
            //}
            $param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
            $image = D('Image')->handle($this->deliver_session['uid'], 'deliver', 1, $param,false);
            if ($image['error']) {
                exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
            } else {
                $title = $image['title']['file'];
                $goods_image_class = new goods_image();
                $url = $goods_image_class->get_delver_image_by_path($title, 's');
                $file = $image['url']['file'];
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title,'file'=>$file)));
            }
        } else {
            exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
        }
    }

    public function ajax_upload_photo()
    {
        if ($_FILES['file']['error'] != 4) {
            //$store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
            //$shop = D('Merchant_store_shop')->field('store_theme')->where(array('store_id' => $store_id))->find();
            //$store_theme = isset($shop['store_theme']) ? intval($shop['store_theme']) : 0;
            //if ($store_theme) {
            //$width = '900,450';
            //$height = '900,450';
            //} else {
            $width = '900,450';
            $height = '500,250';
            //}
            $param = array('size' => $this->config['group_pic_size']);
            $param['thumb'] = true;
            $param['imageClassPath'] = 'ORG.Util.Image';
            $param['thumbPrefix'] = 'm_,s_';
            $param['thumbMaxWidth'] = $width;
            $param['thumbMaxHeight'] = $height;
            $param['thumbRemoveOrigin'] = false;
            $image = D('Image')->handle($this->deliver_session['uid'], 'delivery_photo', 1, $param,false);
            if ($image['error']) {
                exit(json_encode(array('error' => 1,'message' =>$image['msg'])));
            } else {
                $title = $image['title']['file'];
                $goods_image_class = new goods_image();
                $url = $goods_image_class->get_delivery_photo_by_path($title, 's');
                $file = $image['url']['file'];
                exit(json_encode(array('error' => 0, 'url' => $url, 'title' => $title,'file'=>$file)));
            }
        } else {
            exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
        }
    }

    public function ajax_save_city_id_for_deliver_user(){

        $uid = $this->deliver_session['uid'];
        $city_id = $_POST['city_id'];
        $where = array('area_id'=>$city_id,"bag_is_recruit"=>1);
        $area = D('Area')->where($where)->find();
        $where2 = array('uid'=>$uid);
        $du=D('Deliver_user')->where($where2)->save(array('city_id' => $city_id));
        if ($area){
            $return['error'] = 1;
        }else{
            $return['error'] = 0;
        }
        exit(json_encode($return));
        //exit("1111");
    }
    public function ajax_city_name(){
        $city_name = $_POST['city_name'];
        $where = array('area_name'=>$city_name,'area_type'=>2);
        $area = D('Area')->where($where)->find();
        $data = array();
        if($area){
            $data['area_id'] = 0;
            $data['city_id'] = $area['area_id'];
            $data['province_id'] = $area['area_pid'];

            $return['error'] = 0;
        }else{
            $return['error'] = 1;
        }
        $return['info'] = $data;
        exit(json_encode($return));
    }

    public function forgetpwd(){
	    if($_POST){
            if(isset($_POST['phone']) && !empty($_POST['phone'])) {
                $condition_user['phone'] = $_POST['phone'];

                if (!D('Deliver_user')->field('`uid`')->where($condition_user)->find()) {
                    $result = array('error_code' => true, 'msg' => 'Phone Number Error!');
                }
                if (!empty($result)) {
                    $this->ajaxReturn($result);
                }

                $data['vfcode'] = $_POST['sms_code'];
                if(!D('User_modifypwd')->field('id')->where($data)->find()){
                    $result = array('error_code' => true, 'msg' => L('_SMS_CODE_ERROR_'));
                    $this->ajaxReturn($result);
                }

                $deliver_data['pwd'] = md5($_POST['password']);

                D('Deliver_user')->where($condition_user)->save($deliver_data);

                D('User_modifypwd')->where(array('vfcode' => $data['vfcode'], 'telphone' => $_POST['phone']))->delete();

                $this->ajaxReturn(array('error_code' => false, 'msg' => 'Success!'));
            }else{
                $this->ajaxReturn(array('error_code' => true, 'msg' => L('_PLEASE_INPUT_ALL_')));
            }
        }else{
	        $this->display();
        }
    }

    public function inst(){
	    $type = array(2,3);
	    if(isset($_GET['did'])){
            $news = D('System_news')->where(array('id'=>$_GET['did']))->find();

            $this->assign('doc',$news);
            $this->display('inst_doc');
        }else {
            $wap_index_top_adver = D('Adver')->get_adver_by_key('app_courier_promotion',5);
            $this->assign('wap_index_top_adver',$wap_index_top_adver);

            $category = D('System_news_category')->field('id')->where(array('type' => array('in', $type)))->select();

            $cates = array();
            foreach ($category as $v) {
                $cates[] = intval($v['id']);
            }

            $news = D('System_news')->where(array('category_id' => array('in', $cates),'status'=>1))->order('sort desc')->select();

            $this->assign('list', $news);
            $this->display();
        }
    }

    public function account(){
	    $city = D('Area')->where(array('area_id'=>$this->deliver_session['city_id']))->find();
	    $this->assign('city',$city);

        $deliver_img = D('Deliver_img')->where(array('uid'=>$this->deliver_session['uid']))->find();
        $deliver_img['insurace_expiry_type'] = 1;//0过期 1未过期 2即将过期
        $deliver_img['certificate_expiry_type'] = 1;//0过期 1未过期 2即将过期

        if($deliver_img['insurace_expiry'] == ''){
            $deliver_img['insurace_expiry_type'] = 1;
        }else if(strtotime($deliver_img['insurace_expiry']." 23:59:59") < time()){
            $deliver_img['insurace_expiry_type'] = 0;
        }else{
            if((strtotime($deliver_img['insurace_expiry']." 23:59:59") - time())/86400 <= 31){
                $deliver_img['insurace_expiry_type'] = 2;
            }
        }

        if($deliver_img['certificate_expiry'] == '') {
            $deliver_img['certificate_expiry_type'] = 1;
        }else if($deliver_img['certificate_expiry'] != '-1' && strtotime($deliver_img['certificate_expiry']." 23:59:59") < time()) {
            $deliver_img['certificate_expiry_type'] = 0;
        }else{
            if((strtotime($deliver_img['certificate_expiry']." 23:59:59") - time())/86400 <= 31){
                $deliver_img['certificate_expiry_type'] = 2;
            }
        }
        $this->assign('deliver_img',$deliver_img);
	    $this->display();
    }

    public function change_pwd(){
	    if($_POST){
            $old_pwd = md5($_POST['old_pwd']);
            if($old_pwd != $this->deliver_session['pwd']){
                exit(json_encode(array('error' => 1,'message' =>L('_B_MY_WRONGKEY_'))));
            }else{
                $new_pwd = md5($_POST['new_pwd']);
                D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('pwd'=>$new_pwd));
                exit(json_encode(array('error' => 0,'message' =>'Success')));
            }
        }else{
	        $this->display();
        }
    }

    public function bank_info(){
	    $is_pwd = 0;
        if($_POST){
            if($_POST['pwd'] && $_POST['pwd'] != '') {
                $pwd = md5($_POST['pwd']);
                if ($pwd != $this->deliver_session['pwd']) {
                    $is_pwd = 2;
                } else {
                    $is_pwd = 1;
                    $deliver_card = D('Deliver_card')->field(true)->where(array('deliver_id'=>$this->deliver_session['uid']))->find();
                    $this->assign('deliver_card',$deliver_card);
                }
            }else{
                $old_data = D('Deliver_card')->where(array('deliver_id'=>$this->deliver_session['uid']))->find();

                $card_data['ahname'] = $_POST['ahname'];
                $card_data['transit'] = $_POST['transit'];
                $card_data['institution'] = $_POST['institution'];
                $card_data['account'] = $_POST['account'];

                if($old_data['ahname'] != $card_data['ahname'] || $old_data['transit'] != $card_data['transit'] || $old_data['institution'] != $card_data['institution'] || $old_data['account'] != $card_data['account']) {
                    D('Deliver_card')->where(array('deliver_id' => $this->deliver_session['uid']))->save($card_data);

                    $userdata['last_time'] = time();
                    D('Deliver_user')->where(array('uid' => $this->deliver_session['uid']))->save($userdata);

                    $title = "Courier banking info change";
                    $body = "Driver (".$this->deliver_session['uid'].") has changed his/her banking information. Please take a look.";
                    $email = array(array("address"=>"hr@tutti.app","userName"=>"HR"));

                    $mail = getMail($title,$body,$email);
                    $mail->send();
                }
                exit(json_encode(array('error' => 0,'message' =>'Success')));
            }
        }

        $this->assign('is_pwd',$is_pwd);
        $this->display();

    }

    public function support(){
	    $this->display();
    }

    public function ver_info(){
	    if($_POST){
            $database_deliver_user = D('Deliver_user');
            $userdata['site'] = $_POST['address'] ? $_POST['address'] : '';
            $userdata['lng'] = $_POST['lng'] ? $_POST['lng'] : '0';
            $userdata['lat'] = $_POST['lat'] ? $_POST['lat'] : '0';
            $userdata['city_id'] = $_POST['city_id'] ? $_POST['city_id'] : '0';
            $userdata['last_time'] = time();
            $userdata['group'] = 0;

            $database_deliver_user->where(array('uid' => $this->deliver_session['uid']))->save($userdata);

            $data['uid'] = $this->deliver_session['uid'];
            $data['driver_license'] = $_POST['img_0'];
            $data['insurance'] = $_POST['img_1'];
            $data['certificate'] = $_POST['img_2'];
            $data['sin_num'] = $_POST['sin_num'] ? $_POST['sin_num'] : "";

            D('Deliver_img')->save($data);

            $result = array('error_code'=>false,'msg'=>L('_B_LOGIN_REGISTSUCESS_'));
            $this->ajaxReturn($result);
        }else {
            $city_list = D('Area')->where(array('is_open' => 1, 'area_type' => 2))->select();
            $this->assign('city_list', $city_list);

            $city = D('Area')->where(array('area_id'=>$this->deliver_session['city_id']))->find();
            $this->assign('city_name',$city['area_name']);

            $deliver_img = D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->find();
            $this->assign('deliver_img', $deliver_img);

            $this->display();
        }
    }

    public function getVicMailBody($name){
        $body = "<p>Hi ".$name.",</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Thank you for signing up as a Tutti courier!</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Our agent will contact you soon about getting your delivery bag.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>If you haven’t uploaded the required documents, we recommend you finish uploading them in order to get started quickly. You can do so by logging into your courier account and clicking on the“Completing My Application” button. If you have any difficulties uploading documents, you can also bring in the original copy when picking up your delivery bag.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>After being approved and getting your delivery bag, we will activate your account. You will then receive another email with a link to instructions on how to use our courier app.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>For any questions or concerns, please contact us at 1-888-399-6668 or email <a href='mailto:hr@tutti.app'>hr@tutti.app</a>. We look forward to working with you!</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Best regards,</p>";
        $body .= "<p>Tutti Courier Team</p>";

        return $body;
    }

    public function update_work(){
        $img = D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->find();
	    if($_POST){
	        $data['certificate'] = $_POST['certificate'];
	        if($_POST['sin_num'] != "") $data['sin_num'] = $_POST['sin_num'];

	        if($img['update_review'] == 0){
	            $data['update_review'] = 1;
            }else if($img['update_review'] == 2){
	            $data['update_review'] = 10;
            }

            D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->save($data);
            $result = array('error_code'=>false,'msg'=>'Success');
            $this->sendUpdateMail($this->deliver_session['uid'],"Work Eligibility");
            $this->ajaxReturn($result);
        }else {
            $this->assign('deliver_img',$img);
            $this->display();
        }
    }

    public function update_insurance(){
        $img = D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->find();
        if($_POST){
            if($img['update_review'] == 0){
                $_POST['update_review'] = 2;
            }else if($img['update_review'] == 1){
                $_POST['update_review'] = 10;
            }

            D('Deliver_img')->where(array('uid' => $this->deliver_session['uid']))->save($_POST);
            $result = array('error_code'=>false,'msg'=>'Success');
            $this->sendUpdateMail($this->deliver_session['uid'],"Vehicle Insurance");
            $this->ajaxReturn($result);
        }else {
            $this->assign('deliver_img',$img);
            $this->display();
        }
    }

    public function policy(){
        $this->display();
    }

    public function sendUpdateMail($uid,$file_name){
        $email = array(array("address"=>"driver@tutti.app","userName"=>"Tutti"));
        $title = "Driver Doc Update";
        $body = $this->getMailBody($uid,$file_name);
        $mail = getMail($title, $body, $email);
        $mail->send();
    }

    public function getMailBody($uid,$file_name)
    {
        $body = "<p>Driver ID: " . $uid . "</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Updated Doc: ".$file_name."</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Please review on the backend at \"New Courier Verification\". If the updated document is approved, please set a new expiry date and activate the driver's account. If the doc isn't approved, please contact the driver to submit it again.</p>";

        return $body;
    }

    public function getMailBodyToDeliver($name)
    {
        $body = "<p>Hi " . $name . ",</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Congratulations! Your Tutti courier account is now active!</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Here is a link to our delivery instructions on how to use our courier app and complete delivery orders: <a href='https://qrco.de/bbyGle' target='_blank'>https://qrco.de/bbyGle</a>. Please go through this file before starting your first delivery.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Please go through this file before starting your first delivery.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Please also remember to fill in your direct deposit information by pressing Menu on the top right > Account > Banking Info.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>For any questions, please contact us at 1-888-399-6668 or email <a href='mailto:hr@tutti.app'>hr@tutti.app</a>.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Best regards,</p>";
        $body .= "<p>Tutti Courier Team</p>";

        return $body;
    }
}