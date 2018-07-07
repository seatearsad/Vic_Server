<?php

class Merchant_moneyAction extends BaseAction{
    public function index(){
        $mer_id = intval($this->merchant_session['mer_id']);
        $store_list = D('Merchant_store')->where(array('mer_id'=>$mer_id))->select();
        $period = false;

        if(!empty($_GET['day'])){
            $this->assign('day',$_GET['day']);
        }
        if(isset($_GET['begin_time'])&&isset($_GET['end_time'])&&!empty($_GET['begin_time'])&&!empty($_GET['end_time'])){
            if ($_GET['begin_time']>$_GET['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_GET['begin_time']." 00:00:00"),strtotime($_GET['end_time']." 23:59:59"));
            if($_GET['store_id']){
                $time_condition = " (l.use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            }else{
                $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            }
            $condition_merchant_request['_string']=$time_condition;
            $this->assign('begin_time',$_GET['begin_time']);
            $this->assign('end_time',$_GET['end_time']);
            $period = true;
        }
        if(isset($_GET['type'])&&!empty($_GET['type'])){
            $type=$_GET['type'];
            if($type=='activity'){
                $condition_merchant_request['type'] =array('in', 'coupon,yydb');
            }elseif($type=='store'){
                $condition_merchant_request['type'] = array('in','store,cash');
            }else{
                $condition_merchant_request['type'] = $type;
            }
        }else{
            $type='group';
            $condition_merchant_request['type'] = $type;
        }

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
		}
		$this->assign('store_list',$store_list);

        if($_GET['store_id']!=0&&$type!='wxapp'&&$type!='activity'){
            $store_id = $_GET['store_id'];
            foreach($condition_merchant_request as $k=>$v){
                if($k != '_string'){
					$condition_merchant_request['l.'.$k] = $v;
					unset($condition_merchant_request[$k]);
				}
            }
            $this->assign('store_id',$_GET['store_id']);
            $condition_merchant_request['o.store_id'] = $_GET['store_id'];
        }

        $today_zero_time = mktime(0,0,0,date('m',$_SERVER['REQUEST_TIME']),date('d',$_SERVER['REQUEST_TIME']), date('Y',$_SERVER['REQUEST_TIME']));

        if(empty($_GET['day'])){
            $_GET['day'] =2;
        }
        if($_GET['day'] < 1){
            $this->error('日期非法！');
        }
		
        if($_GET['day']==1&&!$period){
            if($store_id){
                $condition_merchant_request['l.use_time'] = array(array('egt',$today_zero_time),array('elt',time()));
            }else{
                $condition_merchant_request['use_time'] = array(array('egt',$today_zero_time),array('elt',time()));
            }
            if($_GET['store_id']){
                $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join '.C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
            }else{
				$condition_merchant_request['mer_id'] = $this->merchant_session['mer_id'];
                $request_list = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
            }
        }else{
            if(!$period) {
                if ($_GET['day'] == 2) {
                    //本月
                    $today_zero_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                    if ($store_id) {
                        $condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                    } else {
                        $condition_merchant_request['use_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                    }
                } else {
                    if ($store_id) {
                        $condition_merchant_request['l.use_time'] = array(array('egt', $today_zero_time - (($_GET['day'] - 1) * 86400)), array('elt', $today_zero_time));
                    } else {
                        $condition_merchant_request['use_time'] = array(array('egt', $today_zero_time - (($_GET['day']) * 86400)), array('elt', time()));
                    }
                }
            }

            if($_GET['store_id']){
                $request_list = M('Merchant_money_list')->field('l.order_id,l.use_time,l.money,l.type,l.income,l.mer_id,o.store_id')->join('as l left join '.C('DB_PREFIX').$type.'_order o ON o.order_id = l.order_id ')->where($condition_merchant_request)->select();
            }else{
				$condition_merchant_request['mer_id'] = $this->merchant_session['mer_id'];
                $request_list = M('Merchant_money_list')->field(true)->where($condition_merchant_request)->select();
            }
        }

        $tmp_array=array();
        if(($_GET['day']==1&&!$period)||($period&&($_GET['end_time']==$_GET['begin_time']))){
            foreach($request_list as $value){
                if($value['type']=='cash'){
                    $value['type'] = 'store';
                }
                $tmp_time = date('G',$value['use_time']);
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
        }else{
            foreach($request_list as $value){
                if($value['type']=='cash'){
                    $value['type'] = 'store';
                }
				if($_GET['day']==2&&!$period){
					$tmp_time = date('j',$value['use_time']);
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
        if(($_GET['day']==1&&!$period)||($period&&($_GET['end_time']==$_GET['begin_time']))){
            $day = date('G',time());
            for($i=0;$i<=date('H',time());$i++){
                $pigcms_list['xAxis_arr'][]  = '"'.$i.'时"';
                $time_arr[]=$i;
            }
        }else{
            if($_GET['day']==2){
                $day = date('d',time());
                for($i=1;$i<=$day;$i++){
                    $pigcms_list['xAxis_arr'][]  = '"'.$i.'日"';
                    $time_arr[]=$i;
                }
            }else{
                $day = $_GET['day'];
                for($i=$day-1;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = '"'.date('j',$today_zero_time-$i*86400).'日"';
                    $time_arr[]=date('ymd',$today_zero_time-$i*86400);
                }
            }
        }
		
        if($period){
            unset($pigcms_list['xAxis_arr']);
			unset($time_arr);
            $start_day =strtotime($_GET['end_time']);

            $day = (strtotime($_GET['end_time'])-strtotime($_GET['begin_time']))/86400;
            if($day==0){
                for($i=0;$i<24;$i++){
                    $pigcms_list['xAxis_arr'][]  = '"'.$i.'时"';
                    $time_arr[]=$i;
                }
            }else{
                for($i=$day;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = '"'.date('d',$start_day-$i*86400).'日"';
                    $time_arr[]=date('ymd',$start_day-$i*86400);
                }
            }
        }
		$no_data_time= array();
        //根据时间组装数据
        foreach($time_arr as $v){
                if($tmp_array[$v]){
					foreach($alias_name as $name){
                        $pigcms_list[$name.'_income'][] = '"'.floatval($tmp_array[$v][$name]['income']).'"';
                        $pigcms_list[$name.'_income_all'] += floatval($tmp_array[$v][$name]['income']);
                        $pigcms_list[$name.'_order_count'][]   = '"'.intval($tmp_array[$v][$name]['count']).'"';
                        $pigcms_list[$name.'_order_count_all'] += intval($tmp_array[$v][$name]['count']);
                    }
                }else{
					if(!in_array($v,$no_data_time)){
						foreach($alias_name as $name){
							$pigcms_list[$name.'_income'][] = '"0"';
							$pigcms_list[$name.'_order_count'][]   = '"0"';
						}
					}
                }
        }

        //基础统计
        $pigcms_list['xAxis_txt'] = implode(',',$pigcms_list['xAxis_arr']);

        foreach($alias_name as $name){
            $pigcms_list[$name]['income_txt'] = implode(',',$pigcms_list[$name.'_income']);
            $pigcms_list[$name]['order_count_txt'] = implode(',',$pigcms_list[$name.'_order_count']);
        }
        if(!$period&&!$_GET['day']!=''){
            $this->assign('day',$_GET['day']);
        }
        $mer_money = M('Merchant')->field('money')->where(array('mer_id'=>$mer_id))->find();
        $this->assign('all_money',$mer_money['money']);
        $this->assign('pigcms_list',$pigcms_list);
        $this->assign('alias_name',$alias_name);
        $this->assign('mer_id',$mer_id);
        $this->assign('type',$type);
        krsort($tmp_array);
        $this->assign('request_list',$tmp_array);
        $this->display();
    }

    protected  function get_alias_name(){
        return array('group','shop','meal','appoint','waimai','store','weidian','wxapp');
    }

    protected  function get_alias_c_name(){
        $c_name = array(
            'all'=>'选择分类',
            'group'=>$this->config['group_alias_name'],
            'shop'=>$this->config['shop_alias_name'],
            'shop_offline'=>$this->config['shop_alias_name'].'线下零售',
            'meal'=>$this->config['meal_alias_name'],
            'appoint'=>$this->config['appoint_alias_name'],
            'waimai'=>'外卖',
            'store'=>'优惠买单',
            'cash'=>'到店支付',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现',
            'coupon'=>'优惠券',
            'withdraw'=>'提现',
            'activity'=>'平台活动',
            'spread'=>'商家推广'
        );
        if(!$this->config['store_open_waimai']) unset($c_name['waimai']);
        if(!$this->config['wxapp_url']) unset($c_name['wxapp']);
        if(!$this->config['appoint_page_row']) unset($c_name['appoint']);
        if(!$this->config['is_open_weidian']) unset($c_name['weidian']);
        if(!$this->config['is_cashier']) unset($c_name['store']);
        if(!$this->config['pay_in_store'] || !$this->config['is_cashier'] ) unset($c_name['cash'],$c_name['shop_offline']);
        return $c_name ;
    }


    public function withdraw(){
        if($this->config['company_pay_open']=='0') {
            $this->error('平台没有开启提现功能！');
        }
        $mer_id = intval($this->merchant_session['mer_id']);
        $now_merchant = M('Merchant')->where(array('mer_id'=>$mer_id))->find();
        $this->assign('now_merchant',$now_merchant);
        if(M('Merchant_withdraw')->where(array('mer_id'=>$mer_id,'status'=>0))->find()){
            $this->error('您有一笔提现在审核，请审核通过了再申请！');
        }
        if($_POST['money']){
            if($_POST['money']>$now_merchant['money']){
                $this->error('提现金额超过了您的余额');
            }
            $money = floatval(($_POST['money']))*100;
            if($_POST['money']<$this->config['min_withdraw_money']){
                $this->error('不能低于最低提款额 '.$this->config['min_withdraw_money'].' 元!');
            }
            $res = D('Merchant_money_list')->withdraw($mer_id,$_POST['name'],$money,$_POST['remark']);
            if($res['error_code']){
                $this->error($res['msg']);
            }else{
                D('Scroll_msg')->add_msg('mer_withdraw',$now_merchant['mer_id'],'商家'.$now_merchant['name'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '提现成功！');
                $this->success("申请成功，请等待审核！",U('Merchant_money/index'));
            }
        }else{
            $this->display();
        }
    }

    public function withdraw_list(){
        $mer_id = intval($this->merchant_session['mer_id']);
        $withdraw_list = D('Merchant_money_list')->get_withdraw_list($mer_id);
        $this->assign($withdraw_list);
        $this->display();
    }

    public function income_list(){
        if(!empty($_POST['order_id'])){
            if(empty($_POST['order_type'])){
                $this->error("没有选分类");
            }
            if($_POST['order_type']=='all'){
                $this->error("该分类下不能填写订单id");
            }else if($_POST['order_type']=='withdraw'){
                $condition['id'] = $_POST['order_id'];
            }else{
                $condition['order_id'] = $_POST['order_id'];
            }
        }
        $mer_id = intval($this->merchant_session['mer_id']);
        $store_list = D('Merchant_store')->where(array('mer_id'=>$mer_id))->select();
        $this->assign('store_list',$store_list);

        $merchant = M('Merchant')->field(true)->where(array('mer_id'=> $mer_id))->find();
        if ($merchant['percent']) {
            $percent = $merchant['percent'];
        } elseif ( C('config.platform_get_merchant_percent')) {
            $percent = C('config.platform_get_merchant_percent');
        }
        if(!empty($_POST['store_id'])){
            $condition['store_id'] = $_POST['store_id'];
            $this->assign('store_id',$_POST['store_id']);
        }

        $this->assign('percent',$percent);
        $this->assign('order_id',$_POST['order_id']);
        $this->assign('order_type',$_POST['order_type']);
		
		if($_POST['order_type']=='activity'){
                $condition['type'] = 'coupon or yydb';
		}elseif($_POST['order_type']!='all'&&!empty($_POST['order_type'])){
            $condition['type'] = $_POST['order_type'];
        }
        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition['_string']=$time_condition;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }

        if(!$_GET['page']){
            $_SESSION['condition'] = $condition;
        }
        $res = D('Merchant_money_list')->get_income_list($mer_id,0,$condition);
        $this->assign('mer_id',$mer_id);
        $this->assign('total',$res['total']);
        $this->assign('income_total',$res['income_total']);
        $this->assign('total_score',$res['total_score']);
        $this->assign('recharge_total',$res['recharge_total']);
        $this->assign('income_list',$res['income_list']);
        $this->assign('alias_name',$this->get_alias_c_name());
        $this->assign('pagebar',$res['pagebar']);
        $this->display();
    }

    public function withdraw_order_info(){
        $withdraw = M('Merchant_withdraw')->where(array('id'=>$_GET['id']))->find();
        $now_merchant = M('Merchant')->where(array('mer_id'=>$withdraw['mer_id']))->find();
        $this->assign('withdraw',$withdraw);
        $this->assign('now_merchant',$now_merchant);
        $this->display();
    }
	public function buy_system(){
		$this->error('该功能正在开发中');
	}

    //商家送出元宝记录 定制的
    public function score_log(){
        import('@.ORG.merchant_page');
        $where['mer_id'] = intval($this->merchant_session['mer_id']);
        if(isset($_POST['begin_time'])&&isset($_POST['end_time'])&&!empty($_POST['begin_time'])&&!empty($_POST['end_time'])){
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (add_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $where['_string']=$time_condition;
            $this->assign('begin_time',$_POST['begin_time']);
            $this->assign('end_time',$_POST['end_time']);
        }
        $count = M('Merchant_score_send_log')->where( $where)->count();

        $p = new Page($count, 20);
        $socre_list = M('Merchant_score_send_log')->where($where)->order('id DESC')->limit($p->firstRow,$p->listRows)->select();
        $pagebar=$p->show();
        $this->assign('score_list',$socre_list);
        $this->assign('pagebar',$pagebar);
        $this->display();
    }

    /*
     *能还未拥有的权限列表
     * */
    public function buy_merchant_service(){
        $mer_menus  = $this->merchant_session['menus'];
        if(empty($mer_menus)){
            $this->error('您已经拥有所有的权限了！',U('Merchant_money/index'));
        }
        $menus = D('New_merchant_menu')->where(array('status'=>1))->select();
        $list = array();
        $list = arrayPidProcess($menus);
        $this->assign('menus', $list);
        $this->assign('mer_menus', $mer_menus);
        $this->display();
    }


    /*
     * 购买权限
     * */
    public function pay_merchant_service(){
        $auth_id = $_GET['auth_id'];
        if(empty($auth_id)){
            $this->error('非法访问！');
        }
        $now_auth = D('New_merchant_menu')->where(array('id'=>$auth_id))->find();
        $now_merchant = D('Merchant')->get_info($this->merchant_session['mer_id']);
        if($now_auth['price']>$now_merchant['money']){
            $this->error('您的商家余额不足，不能购买该权限，请充值',U('mer_recharge',array('money'=>($now_auth['price']-$now_merchant['money']),'auth_id'=>$auth_id)));
        }else{
            $res = D('Merchant_auth')->add_auth($this->merchant_session['mer_id'],$auth_id);
            if($res['error_code']){
                $this->error($res['msg'],U('Merchant_money/buy_merchant_service'));
            }else{

                D('Merchant_money_list')->use_money($this->merchant_session['mer_id'],$now_auth['price'],'merauth','购买商家权限【'.$now_auth['name'].'】扣除商家余额',$auth_id);
                $this->success($res['msg'],U('Merchant_money/buy_merchant_service'));
            }
        }
    }

    /*
     * 商家余额充值
     * */
    public function mer_recharge(){
        //商家信息

        $now_merchant = D('Merchant')->get_info($this->merchant_session['mer_id']);

        if(empty($_GET['money'])){
            $this->assign('now_merchant',$now_merchant);
            $this->display();
        }else {
            $money = floatval($_GET['money']);
            if (empty($money)||$money <0 ||!is_numeric($money)) {
                $this->error('请输入正确的充值金额');
            }

            $data_mer_recharge_order['mer_id'] = $now_merchant['mer_id'];
            $data_mer_recharge_order['money'] = $money;
            $data_mer_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
            $data_mer_recharge_order['last_time'] = $_SERVER['REQUEST_TIME'];
            if($_GET['auth_id']){
                $data_mer_recharge_order['label'] = 'web_merauth_' . $_GET['auth_id'];
            }
            if ($order_id = M('Merchant_recharge_order')->data($data_mer_recharge_order)->add()) {
                redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'merrecharge')));
            } else {
                $this->error_tips('订单创建失败，请重试。');
            }
        }

    }

}