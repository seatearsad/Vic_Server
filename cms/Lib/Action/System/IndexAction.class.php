<?php

/*
 * 后台管理基础类
 *
 */

class IndexAction extends BaseAction {

    public function index() {

		$mysqlVersion = M()->query('select VERSION()');
		$server_info = array(
            'PHP运行环境' => PHP_OS,
            'PHP运行方式' => php_sapi_name(),
            'PHP版本' => PHP_VERSION,
            'MYSQL版本' => $mysqlVersion[0]['VERSION()'],
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '磁盘剩余空间 ' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        );
        if($this->system_session['area_id']!=0){
            $now_area = D('Area')->get_area_by_areaId($this->system_session['area_id']);
            $this->assign('now_area',$now_area);
        }
        $this->assign('server_info', $server_info);
		$version = './conf/version.php';
        $ver = include $version;
        $ver = $ver['ver'];
		$release=include $version;
		$release=$release['release'];
		$uptime=include $version;
		$uptime=$uptime['uptime'];
		$this->assign('release',$release);
		$this->assign('uptime',$uptime);
		if($ver===false||trim($ver)==''){
			$ver='[未知版本]';
		}
        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $updatehost = 'http://www.mx800.com/o2o/update.php';
        $updatehosturl = $updatehost . '?a=client_check_time&v=' . $ver . '&u=' . $hosturl;
		//$info = json_decode(file_get_contents($updatehosturl),true);
  
		$hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);	
		$param['u']=$hosturl;
		$param['a']='check';
		$param['v']=$ver;
//		$lastver = $this->http($updatehost,$param,'GET', array("Content-type: text/html; charset=utf-8"));
        if(isMobile()){
            $this->assign('height',' ');
        }else{
            $this->assign('height',' height="70" ');
        }
		$this->assign('updateinfo',$updateinfo);
        $this->assign('ver', $ver);
        $this->assign('domain_time', $domain_time);
        $city = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$city);

        $this->display("","","","","","public");
    }

    public function main() {
        if (!$this->system_session['area_id']&&$this->system_session['level']!=2) {
            $this->redirect(U('Index/profile'));
        }

        $area_id = $this->system_session['area_id'];//区域管理员区域

		$server_info = array(
            'PHP运行环境' => PHP_OS,
            'PHP运行方式' => php_sapi_name(),
            'PHP版本' => PHP_VERSION,
            'MYSQL版本' => mysql_get_server_info(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . '秒',
            '磁盘剩余空间 ' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        );
        $this->assign('server_info', $server_info);

        //网站统计
        if($area_id){
            $pigcms_assign['website_collect_count'] = floatval(M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX').'merchant m ON l.mer_id = m.mer_id')->where('m.city_id = '.$area_id.' OR m.area_id = '.$area_id)->sum('total_money'));
        }else{
            $pigcms_assign['website_collect_count'] = floatval(M('Merchant_money_list')->sum('total_money'));
        }
        if($area_id){
            $sql_count = "SELECT count(*) FROM ". C('DB_PREFIX') . "user as u LEFT JOIN ". C('DB_PREFIX') . "user_adress as a on a.uid = u.uid ";
            $sql_count .= "WHERE a.default=1 and a.city=".$area_id;
            $count = D()->query($sql_count);
            $pigcms_assign['website_user_count'] = $count[0]['count(*)'];
        }else {
            $pigcms_assign['website_user_count'] = M('User')->count();
        }
        $where['status'] = 1;
        if($area_id){
            $where['_string'] = 'city_id = '.$area_id.' OR area_id = '.$area_id;
            $pigcms_assign['website_merchant_count'] = M('Merchant')->where($where)->count();
        }else{
            $pigcms_assign['website_merchant_count'] = M('Merchant')->where($where)->count();
        }

        if($area_id){
            $where['_string'] = 'city_id = '.$area_id.' OR area_id = '.$area_id;
            $pigcms_assign['website_merchant_store_count'] = M('Merchant_store')->where($where)->count();
            $area_info = M('Area')->where(array('area_id'=>$area_id))->find();
            $this->assign('area_info', $area_info);
        }else{
            $pigcms_assign['website_merchant_store_count'] = M('Merchant_store')->where($where)->count();
        }
        //团购统计

        if($area_id){
            $where_group['l.status'] = 1;
            $where_group['_string'] = 'm.city_id = '.$area_id.' OR m.area_id = '.$area_id;
            $pigcms_assign['group_group_count'] = M('Group')->join('as l left join '.C('DB_PREFIX').'merchant m ON l.mer_id = m.mer_id')->where($where_group)->count();
        }else{
            $pigcms_assign['group_group_count'] = M('Group')->where(array('status'=>1))->count();
        }
        //订餐统计
        $condition['s.status'] = 1;
        if($area_id){
            $condition['_string'] = 's.city_id = '.$area_id.' OR s.area_id = '.$area_id;
        }

        //$sql = "SELECT count(s.store_id) as count FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN ". C('DB_PREFIX') . "merchant_store_foodshop AS m ON s.store_id=m.store_id WHERE s.status=1";
        $foodshop_count = M('Merchant_store')->join('as s LEFT JOIN '. C('DB_PREFIX').'merchant_store_foodshop AS m ON s.store_id = m.store_id ')->where($condition)->count('s.store_id');
        //$result = D('')->query($sql);
        $pigcms_assign['meal_store_count'] = $foodshop_count;
		//快店统计
		//$sql = "SELECT count(s.store_id) as count FROM ". C('DB_PREFIX') . "merchant_store AS s INNER JOIN ". C('DB_PREFIX') . "merchant_store_shop AS m ON s.store_id=m.store_id WHERE s.status=1";
        //$result = D('')->query($sql);
        $foodshop_count = M('Merchant_store')->join('as s LEFT JOIN '. C('DB_PREFIX').'merchant_store_shop AS m ON s.store_id = m.store_id ')->where($condition)->count('s.store_id');

        $pigcms_assign['shop_store_count'] = $foodshop_count;
        //预约统计

        $now_time = $_SERVER['REQUEST_TIME'];
        $appoint_where['appoint_status'] = 0;
        $appoint_where['check_status'] = 1;
        $appoint_where['start_time'] = array('lt' , $now_time);
        $appoint_where['end_time'] = array('gt' , $now_time);
        if($area_id){
            $appoint_where['_string'] = 'm.city_id = '.$area_id.' OR m.area_id = '.$area_id;
            $pigcms_assign['appoint_group_count'] = M('Appoint')->join('as l left join '.C('DB_PREFIX').'merchant m ON l.mer_id = m.mer_id')->where($appoint_where)->count();
        }else{
            $pigcms_assign['appoint_group_count'] = M('Appoint')->where($appoint_where)->count();
        }

        //商家待审核
        // $pigcms_assign['merchant_verify_list'] = D('Merchant')->where(array('status'=>'2','reg_time'=>array('gt',$this->system_session['last_time'])))->select();
        if ($this->system_session['area_id']) {
        	$area_index = $this->system_session['level'] == 1 ? 'area_id' : 'city_id';
            $pigcms_assign['merchant_verify_count'] = D('Merchant')->where(array('status' => '2', $area_index => $this->system_session['area_id']))->count();
            //店铺待审核
            // $pigcms_assign['merchant_verify_store_list'] = D('Merchant_store')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $pigcms_assign['merchant_verify_store_count'] = D('Merchant_store')->where(array('status' => 2, $area_index => $this->system_session['area_id']))->count();
            //团购待审核
            // $pigcms_assign['group_verify_list'] = D('Group')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $merchants = D('Merchant')->field('mer_id')->where(array('status' => '1', $area_index => $this->system_session['area_id']))->select();
            $mer_ids = array();
            foreach ($merchants as $m) {
                if (!in_array($m['mer_id'], $mer_ids))
                    $mer_ids[] = $m['mer_id'];
            }

            $pigcms_assign['group_verify_count'] = 0;
            if ($mer_ids) {
                $pigcms_assign['group_verify_count'] = D('Group')->where(array('status' => '2', 'mer_id' => array('in', $mer_ids)))->count();
            }
        } else {
            $pigcms_assign['merchant_verify_count'] = D('Merchant')->where(array('status' => '2'))->count();
            //店铺待审核
            // $pigcms_assign['merchant_verify_store_list'] = D('Merchant_store')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $pigcms_assign['merchant_verify_store_count'] = D('Merchant_store')->where(array('status' => 2))->count();
            //团购待审核
            // $pigcms_assign['group_verify_list'] = D('Group')->where(array('status'=>'2','last_time'=>array('gt',$this->system_session['last_time'])))->select();
            $pigcms_assign['group_verify_count'] = D('Group')->where(array('status' => '2'))->count();
        }

        $this->assign('user',$this->ajax_user());
        $this->assign('mer_money',$this->ajax_merchant_money());
        $this->assign($pigcms_assign);
        $this->display();
    }

    public  function get_alias_name(){
        $alias_name = array('group','meal','shop','appoint','store','weidian','wxapp','waimai');
        if(!isset($this->config['appoint_alias_name'])){
            $key = array_search('appoint', $alias_name);
            unset($alias_name[$key]);
        }
        if(!isset($this->config['waimai_alias_name'])){
            $key = array_search('waimai', $alias_name);
            unset($alias_name[$key]);
        }
        return  $alias_name ;
    }

    public  function get_alias_c_name(){
        $alias_name = array(
            'all'=>'全部',
            'group'=>$this->config['group_alias_name'],
            'shop'=>$this->config['shop_alias_name'],
            'meal'=>$this->config['meal_alias_name'],
            'appoint'=>$this->config['appoint_alias_name'],
            'waimai'=>$this->config['waimai_alias_name'],
            'store'=>'到店',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现'
        );
        if(!isset($this->config['appoint_alias_name'])){
            unset($alias_name['appoint']);
        }
        if(!isset($this->config['waimai_alias_name'])){
            unset($alias_name['waimai']);
        }
        return $alias_name;
    }

    public function ajax_all_date(){
        $_POST['day']=I('day');
        $_POST['period']=I('period');
        $type=I('type');
        if(empty($_POST['day'])&&empty($_POST['period'])){
            $_POST['day'] =1;
        }
        $area_id = $this->system_session['area_id'];//区域管理员区域

        $alias_name = $this->get_alias_name();
        $today_zero_time = mktime(0,0,0,date('m',$_SERVER['REQUEST_TIME']),date('d',$_SERVER['REQUEST_TIME']), date('Y',$_SERVER['REQUEST_TIME']));
        $period = false;
        if(isset($_POST['period'])&&!empty($_POST['period'])){
            $period = explode(' - ',$_POST['period']);
            $_POST['begin_time'] = $period[0];
            $_POST['end_time'] = $period[1];
            if ($_POST['begin_time']>$_POST['end_time']) {
                $this->error("结束时间应大于开始时间");
            }
            $period = array(strtotime($_POST['begin_time']." 00:00:00"),strtotime($_POST['end_time']." 23:59:59"));
            $time_condition = " (pay_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $time_condition_mer = " (use_time BETWEEN ".$period[0].' AND '.$period[1].")";
            $condition_merchant_request['_string']=$time_condition;
            $condition_mer_request['_string']=$time_condition_mer;
            $period = true;
        }
        if($period && $_POST['begin_time']==$_POST['end_time']){
            $_POST['day']=1;
        }


        if($_POST['day']==1&&!$period){
            $condition_merchant_request['pay_time'] = array(array('egt',$today_zero_time),array('elt',time()));
            $condition_mer_request['use_time'] = array(array('egt',$today_zero_time),array('elt',time()));
        }else{
            if(!$period) {
                if ($_POST['day'] == 2) {
                    //本月
                    $today_zero_time = mktime(0, 0, 0, date('m'), 1, date('Y'));
                    $condition_merchant_request['pay_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                    $condition_mer_request['use_time'] = array(array('egt', $today_zero_time), array('elt', $_SERVER['REQUEST_TIME']));
                } else {
                    $condition_merchant_request['pay_time'] = array(array('egt', $today_zero_time - (($_POST['day']) * 86400)), array('elt', time()));
                    $condition_mer_request['use_time'] = array(array('egt', $today_zero_time - (($_POST['day']) * 86400)), array('elt', time()));
                }
            }
        }
        if($area_id){
            $condition_mer_request['_string'] .= 'm.city_id = '.$area_id.' OR m.area_id = '.$area_id;
        }

        $tmp_array=array();
        $condition_merchant_request['o.paid'] = 1;
        $condition_merchant_request['o.status']=array('lt',3);
        //garfunkel 判断城市管理员
        if($this->system_session['area_id'] != 0){
            $condition_merchant_request['m.city_id']=$this->system_session['area_id'];
        }
        $res_group   = M('Group_order')->field('total_money as money,payment_money,pay_type ,pay_time')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();

        $res_meal    = M('Meal_order')->field('total_price as money,payment_money,pay_type ,pay_time')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();

        $condition_merchant_request['o.status']=array('lt',4);
        $condition_merchant_request['o.is_del']=0;
        //$res_shop    = M('Shop_order')->field('total_price as money,payment_money ,pay_type,pay_time')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();
        $res_shop    = M('Shop_order')->field('total_price+tip_charge-coupon_price-delivery_discount-merchant_reduce as money,payment_money ,pay_type,pay_time')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();

        unset($condition_merchant_request['o.status']);
        unset($condition_merchant_request['o.is_del']);
        $res_appoint = M('Appoint_order')->field('payment_money as money ,pay_money as payment_money,pay_type,pay_time,product_id,product_payment_price')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->select();
        $res_wxapp   = M('Wxapp_order')->field('o.money,payment_money,pay_type ,pay_time')->where($condition_merchant_request)->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->select();
        $res_weidian = M('Weidian_order')->field('o.money as money,payment_money,pay_type ,pay_time')->where($condition_merchant_request)->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->select();
        $res_store   = M('Store_order')->field('total_price as money,payment_money,pay_type ,pay_time')->where($condition_merchant_request)->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->select();

        $count['group']   = M('Group_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();

        $count['meal']    = M('Meal_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['shop']    = M('Shop_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['appoint'] = M('Appoint_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['wxapp']   = M('Wxapp_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['weidian'] = M('Weidian_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['store']   = M('Store_order')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_merchant_request)->count();
        $count['all'] = $count['group']+$count['meal']+$count['shop']+$count['appoint']+$count['wxapp']+$count['weidian']+$count['store'];

        $condition_mer_request['type']=array('neq','withdraw');
        //garfunkel 判断城市管理员
        if($this->system_session['area_id'] != 0){
            $condition_mer_request['m.city_id']=$this->system_session['area_id'];
        }
        $mer_money = M('Merchant_money_list')->field('`o`.*')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_mer_request)->select();
        $mer_count= M('Merchant_money_list')->join(' as o left join '.C('DB_PREFIX').'merchant m ON m.mer_id = o.mer_id')->where($condition_mer_request)->group('o.type')->getField('type,count(o.id) as count');

        $mer_count['all']=0;
        foreach($alias_name as $mc){
            if(!isset($mer_count[$mc])){
                $mer_count[$mc]=0;
            }
            $mer_count['all'] +=$mer_count[$mc];
        }
        $weixin_pay = 0;
        $alipay_pay = 0;
        foreach($alias_name as $rv){
            $tmp = 'res_'.$rv;
            foreach($$tmp as $value){
                if($rv=='appoint'&&$value['product_id']>0){
                    $value['money'] = $value['product_payment_price'];
                }

                if($rv=='appoint'&&$value['product_id']>0){
                    $value['money'] = $value['product_payment_price'];
                }
                if($value['pay_type']=='weixin'){
                    $weixin_pay+=$value['payment_money'];
                }elseif($value['pay_type']=='alipay'){
                    $alipay_pay +=$value['payment_money'];
                }
                if($_POST['day']==2){
                    $tmp_time = date('d',$value['pay_time']);
                }else if($_POST['day']==1||($period&&($_POST['end_time']==$_POST['begin_time']))){
                    $tmp_time = date('G',$value['pay_time']);
                }else{
                    $tmp_time = date('ymd',$value['pay_time']);
                }
                if(!isset($tmp_array['all_count'][$tmp_time])){
                    $tmp_array['all_count'][$tmp_time]=0;
                    $tmp_array[$rv][$tmp_time]['count']=0;
                }
                $tmp_array['all_income'][$tmp_time] += $value['money'];
                $tmp_array['all_count'][$tmp_time] += 1;
                $tmp_array[$rv][$tmp_time]['income'] += $value['money'];
                $tmp_array[$rv][$tmp_time]['count'] += 1;
            }
        }


        $tmp_mer=array();
        foreach($mer_money as $value){
            if($_POST['day']==2){
                $tmp_time = date('d',$value['use_time']);
            }else if($_POST['day']==1){
                $tmp_time = date('G',$value['use_time']);
            }else{
                $tmp_time = date('ymd',$value['use_time']);
            }

            if(!isset( $tmp_mer['mer_count'][$tmp_time])){
                $tmp_mer['mer_count'][$tmp_time]=0;
                $tmp_mer['mer_count_by_type'][$value['type']][$tmp_time]=0;
            }
            $tmp_mer['mer_income'][$tmp_time] += $value['total_money'];
            $tmp_mer['mer_count'][$tmp_time] += 1;
            $tmp_mer['mer_income_by_type'][$value['type']][$tmp_time] += $value['total_money'];
            $tmp_mer['mer_count_by_type'][$value['type']][$tmp_time] +=1;
        }

        if(($_POST['day']==1&&!$period)||($period&&($_POST['end_time']==$_POST['begin_time']))){
            $day = date('H',time());
            for($i=0;$i<=date('H',time())+1;$i++){
                $pigcms_list['xAxis_arr'][]  = $i.'时';
                $pigcms_list['xAxis_arr_e'][]  = $i.'时';
                $time_arr[]=$i;
            }
        }else{
            if($_POST['day']==2){
                $day = date('d',time());
                for($i=1;$i<=$day;$i++){
                    $pigcms_list['xAxis_arr'][]  = $i.'日';
                    $pigcms_list['xAxis_arr_e'][]  =  date('Y/m/d',time()-$i*86400);
                    $time_arr[]=$i;
                }
            }else{
                //$now_day =date('d',$today_zero_time);
                $day = $_POST['day'];
                for($i=$day-1;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = date('m/d',$today_zero_time-$i*86400);
                    $pigcms_list['xAxis_arr_e'][]  = date('Y/m/d',$today_zero_time-$i*86400);
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
                    $pigcms_list['xAxis_arr_e'][]  =  $i.'时';
                    $time_arr[]=$i;
                }
            }else{
                for($i=$day;$i>=0;$i--){
                    $pigcms_list['xAxis_arr'][]  = date('m/d',$start_day-$i*86400).'';
                    $pigcms_list['xAxis_arr_e'][]  = date('Y/m/d',$start_day-$i*86400).'';
                    $time_arr[]=date('ymd',$start_day-$i*86400);
                }
            }
        }


        foreach($time_arr as $v){
            $pigcms_list['all_income'][] = floatval($tmp_array['all_income'][$v]);
            $pigcms_list['all_count'][] = floatval($tmp_array['all_count'][$v]);
            $pigcms_list['all_income_all'] += floatval($tmp_array['all_income'][$v]);
            $pigcms_list['all_mer_income'][] = floatval($tmp_mer['mer_income'][$v]);
            $pigcms_list['all_mer_income_all'] += floatval($tmp_mer['mer_income'][$v]);
            $pigcms_list['all_mer_count'] []= floatval($tmp_mer['mer_count'][$v]);

            foreach($alias_name as $a){
                $pigcms_list[$a.'_income'][] = floatval($tmp_array[$a][$v]['income']);
                $pigcms_list[$a.'_count'][] = floatval($tmp_array[$a][$v]['count']);
                $pigcms_list[$a.'_mer_income'][] = floatval($tmp_mer['mer_income_by_type'][$a][$v]);
                $pigcms_list[$a.'_mer_count'][] = floatval($tmp_mer['mer_count_by_type'][$a][$v]);
            }
        }



        //数据组装
        $pigcms_list['xAxis_txt'] = $pigcms_list['xAxis_arr'];

        foreach($alias_name as $n){
            $pigcms_list[$n]['income_txt'] = $pigcms_list[$n.'_income'];
            $pigcms_list[$n]['count_txt'] = $pigcms_list[$n.'_count'];
            $pigcms_list[$n]['mer_income_txt'] = $pigcms_list[$n.'_mer_income'];
            $pigcms_list[$n]['mer_count_txt'] = $pigcms_list[$n.'_mer_count'];
            unset($pigcms_list[$n.'_mer_income']);
            unset($pigcms_list[$n.'_income']);
            unset($pigcms_list[$n.'_count']);
            unset($pigcms_list[$n.'_mer_count']);
        }
        $pigcms_list['all']['income_txt'] = $pigcms_list['all_income'];
        $pigcms_list['all']['mer_income_txt'] = $pigcms_list['all_mer_income'];
        $pigcms_list['all']['count_txt'] = $pigcms_list['all_count'];
        $pigcms_list['all']['mer_count_txt'] = $pigcms_list['all_mer_count'];
        $pigcms_list['count'] = array('sales_count'=>$count,'consume'=>$mer_count);
        $pigcms_list['alias_name'] = $this->get_alias_c_name();

        if(IS_GET){
            $this->export($pigcms_list,$type,$_POST['day'],$_POST['period']);
        }
        $pigcms_list['pay_type'] =array('weixin'=>$weixin_pay,'alipay'=>$alipay_pay);
        unset($pigcms_list['all_income']);
        unset($pigcms_list['all_income']);
        unset($pigcms_list['all_mer_income']);
        unset($pigcms_list['xAxis_arr']);
        $this->ajaxReturn($pigcms_list);
    }

    public function ajax_new_data(){

        if($this->overView == 1) {
            $day = $_POST['day'];
            switch ($day) {
                case 'day':
                    $days = 1;
                    break;
                case 'week':
                    $days = 7;
                    break;
                case 'month':
                    $days = 30;
                    break;
            }

            $city_id = $_POST['city_id'] == 0 ? 105 : $_POST['city_id'];

            //$today_zero_time = mktime(0,0,0,date('m',$_SERVER['REQUEST_TIME']),date('d',$_SERVER['REQUEST_TIME']), date('Y',$_SERVER['REQUEST_TIME']));
            $today_zero_time = mktime(0, 0, 0, 1, 5, 2018);
            $begin_time = $today_zero_time - ($days - 1) * 3600 * 24;
            //$end_time = time();
            $end_time = $today_zero_time + 3600 * 24;
            $condition_merchant_request['pay_time'] = array(array('egt', $begin_time), array('elt', $end_time));

            $condition_merchant_request['status'] = array('lt', 4);
            $condition_merchant_request['is_del'] = 0;
            $condition_merchant_request['paid'] = 1;

            $condition_today_request['pay_time'] = array(array('egt', $today_zero_time), array('elt', $end_time));
            $condition_today_request['status'] = array('lt', 4);
            $condition_today_request['is_del'] = 0;
            $condition_today_request['paid'] = 1;

            $today = M('Shop_order')->field('sum(total_price+tip_charge-coupon_price-delivery_discount-merchant_reduce) as total_cash')->where($condition_today_request)->select();
            $today_cash = $today[0]['total_cash'];

            $res_shop = M('Shop_order')->field('total_price+tip_charge-coupon_price-delivery_discount-merchant_reduce as cash_flow,total_price+tip_charge as sales,payment_money ,pay_type,pay_time')->where($condition_merchant_request)->order('pay_time asc')->select();

            $condition_city_request['o.pay_time'] = array(array('egt', $today_zero_time), array('elt', $end_time));
            $condition_city_request['o.status'] = array('lt', 4);
            $condition_city_request['o.is_del'] = 0;
            $condition_city_request['o.paid'] = 1;
            $condition_city_request['m.city_id'] = $city_id;
            $res_city = M('Shop_order')->field('total_price+tip_charge-coupon_price-delivery_discount-merchant_reduce as cash_flow,total_price+tip_charge as sales,payment_money ,pay_type,pay_time')->join(' as o left join ' . C('DB_PREFIX') . 'merchant_store m ON m.store_id=o.store_id')->where($condition_city_request)->order('o.pay_time asc')->select();

            $data_array = array();
            $total = 0;
            foreach ($res_shop as $v) {
                if ($days == 1)
                    $show_time = date('H', $v['pay_time']).":00";
                else
                    $show_time = date('m-d', $v['pay_time']);

                $data_array[$show_time]['cash_flow'] += $v['cash_flow'];
                $data_array[$show_time]['sales'] += $v['sales'];
                $total += $v['cash_flow'];
            }
            //ksort($data_array);
            //user
            $condition_user['add_time'] = array(array('egt', $begin_time), array('elt', $end_time));
            $condition_user['status'] = 1;
            $all_user = D('User')->field('count(uid) as total')->where($condition_user)->select();
            $all_user = $all_user[0]['total'];

            $condition_city_user['u.add_time'] = array(array('egt', $today_zero_time), array('elt', $end_time));
            $condition_city_user['u.status'] = 1;
            $condition_city_user['a.city'] = $city_id;
            $condition_city_user['a.default'] = 1;
            $city_user = D('User')->field('count(u.uid) as total')->join(' as u left join ' . C('DB_PREFIX') . 'user_adress a ON a.uid=u.uid')->where($condition_city_user)->select();
            $city_user = $city_user[0]['total'];

            ///city////
            $city_array = array();
            $city_total = 0;
            foreach ($res_city as $v) {
                $show_time = date('H', $v['pay_time']).":00";

                $city_array[$show_time]['cash_flow'] += $v['cash_flow'];
                $city_array[$show_time]['sales'] += $v['sales'];
                $city_total += $v['cash_flow'];
            }

            $r_data = array('total' => $total, 'data_array' => $data_array, 'today_cash' => $today_cash,
                'city_total' => $city_total, 'city_array' => $city_array, 'city_id' => $city_id,
                'all_user' => $all_user, 'city_user' => $city_user
            );

            $this->ajaxReturn($r_data);
        }else{
            $this->ajaxReturn(array());
        }
    }

    public function ajax_sales_data(){

        if($this->overView == 1) {

            //筛选时间
            if (!empty($_POST['begin_time']) && !empty($_POST['end_time'])) {

                if ($_POST['begin_time'] > $_POST['end_time']) {
                    $this->error("Please enter the date ranges correctly");
                } else {
                    $begin_time=strtotime($_POST['begin_time'] . " 00:00:00");
                    $end_time=strtotime($_POST['end_time'] . " 23:59:59");
//                    $period = array(strtotime($_GET['begin_time'] . " 00:00:00"), strtotime($_GET['end_time'] . " 23:59:59"));
//                    $where['_string'] .= ($where['_string'] ? ' AND ' : '') . " (create_time BETWEEN " . $period[0] . ' AND ' . $period[1] . ")";
                    //$condition_where['_string']=$time_condition;
                }
            }

            $city_id = $_POST['city_id'] == 0 ? 105 : $_POST['city_id'];

            //-----------------------------------------------------------

            //$today_zero_time = mktime(0,0,0,date('m',$_SERVER['REQUEST_TIME']),date('d',$_SERVER['REQUEST_TIME']), date('Y',$_SERVER['REQUEST_TIME']));
            //$today_zero_time = mktime(0, 0, 0, 1, 5, 2018);
            //$begin_time = $today_zero_time - ($days - 1) * 3600 * 24;
            //$end_time = time();
            //$end_time = $today_zero_time + 3600 * 24;

            $condition_merchant_request['o.pay_time'] = array(array('egt', $begin_time), array('elt', $end_time));
            $condition_merchant_request['o.status'] = array('lt', 4);
            $condition_merchant_request['o.is_del'] = 0;
            $condition_merchant_request['o.paid'] = 1;
            $condition_today_request['m.city_id'] = $city_id;
            $today = M('Shop_order')->field('sum(o.total_price+o.tip_charge-o.coupon_price-o.delivery_discount-o.merchant_reduce) as total_cash')->join(' as o left join ' . C('DB_PREFIX') . 'merchant_store m ON m.store_id=o.store_id')->where($condition_today_request)->select();
            $today_cash = $today[0]['total_cash'];

            $condition_today_request['o.pay_time'] = array(array('egt', $begin_time), array('elt', $end_time));
            $condition_today_request['o.status'] = array('lt', 4);
            $condition_today_request['o.is_del'] = 0;
            $condition_today_request['o.paid'] = 1;
            $condition_today_request['m.city_id'] = $city_id;
            $res_shop = M('Shop_order')->field('o.total_price+o.tip_charge-o.coupon_price-o.delivery_discount-o.merchant_reduce as cash_flow,o.total_price+o.tip_charge as sales,o.payment_money ,o.pay_type,o.pay_time')->join(' as o left join ' . C('DB_PREFIX') . 'merchant_store m ON m.store_id=o.store_id')->where($condition_merchant_request)->order('pay_time asc')->select();

//            $condition_city_request['o.pay_time'] = array(array('egt', $today_zero_time), array('elt', $end_time));
//            $condition_city_request['o.status'] = array('lt', 4);
//            $condition_city_request['o.is_del'] = 0;
//            $condition_city_request['o.paid'] = 1;
//            $condition_city_request['m.city_id'] = $city_id;
//            $res_city = M('Shop_order')->field('total_price+tip_charge-coupon_price-delivery_discount-merchant_reduce as cash_flow,total_price+tip_charge as sales,payment_money ,pay_type,pay_time')->join(' as o left join ' . C('DB_PREFIX') . 'merchant_store m ON m.store_id=o.store_id')->where($condition_city_request)->order('o.pay_time asc')->select();

            $data_array = array();
            $total = 0;
            foreach ($res_shop as $v) {
//                if ($days == 1)
//                    $show_time = date('H', $v['pay_time']).":00";
//                else
                $show_time = date('m-d', $v['pay_time']);

                $data_array[$show_time]['cash_flow'] += $v['cash_flow'];
                $data_array[$show_time]['sales'] += $v['sales'];
                $total += $v['cash_flow'];
            }
            //ksort($data_array);
            //user
            $condition_user['add_time'] = array(array('egt', $begin_time), array('elt', $end_time));
            $condition_user['status'] = 1;
            $all_user = D('User')->field('count(uid) as total')->where($condition_user)->select();
            $all_user = $all_user[0]['total'];

            $condition_city_user['u.add_time'] = array(array('egt', $today_zero_time), array('elt', $end_time));
            $condition_city_user['u.status'] = 1;
            $condition_city_user['a.city'] = $city_id;
            $condition_city_user['a.default'] = 1;
            $city_user = D('User')->field('count(u.uid) as total')->join(' as u left join ' . C('DB_PREFIX') . 'user_adress a ON a.uid=u.uid')->where($condition_city_user)->select();
            $city_user = $city_user[0]['total'];

            ///city////
//            $city_array = array();
//            $city_total = 0;
//            foreach ($res_city as $v) {
//                $show_time = date('H', $v['pay_time']).":00";
//
//                $city_array[$show_time]['cash_flow'] += $v['cash_flow'];
//                $city_array[$show_time]['sales'] += $v['sales'];
//                $city_total += $v['cash_flow'];
//            }

            $r_data = array('total' => $total, 'data_array' => $data_array, 'today_cash' => $today_cash,
                'city_total' => $city_total, 'city_array' => $city_array, 'city_id' => $city_id,
                'all_user' => $all_user, 'city_user' => $city_user
            );

            $this->ajaxReturn($r_data);
        }else{
            $this->ajaxReturn(array());
        }
    }

    public function export($result,$type_name,$day,$peroid){
        $type = 'analysis';
        $title = '';
        $alias_name = $this->get_alias_name();
        array_unshift($alias_name,'all');
        foreach($alias_name as $kn =>$na){
            if($na==$type_name){
                unset($alias_name[$kn]);
                array_unshift($alias_name,$type_name);
                break;
            }
        }

        $alias_c_name = $this->get_alias_c_name();
        if($day){
            $p_title = $day.' '.L('_BACK_DAYS_');
        }elseif($peroid){
            $p_title = $peroid;
        }
        $title  = 'Sales Summary('.$p_title.' '.date("Y-m-d H'i's").')';
        require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';
        $objExcel = new PHPExcel();
        $objProps = $objExcel->getProperties();
        // 设置文档基本属性
        $objProps->setCreator($title);
        $objProps->setTitle($title);
        $objProps->setSubject($title);
        $objProps->setDescription($title);
        $cell_analysis  = array('date'=>'日期','income_txt'=>'订单总额', 'income_mer_txt'=>'消费总额', 'sales_count'=>'订单量总数','consume'=>'消费量总数');

        //打印条件

        // 设置当前的sheet
        $sheet = 0;

        foreach ($alias_name as $ca) {
            if ($sheet > 0) {
                $objExcel->createSheet();
            }
            $objExcel->setActiveSheetIndex($sheet);
            $objExcel->getActiveSheet()->setTitle($result['alias_name'][$ca]);
            $objActSheet = $objExcel->getActiveSheet();
            $objActSheet->getDefaultRowDimension()->setRowHeight(30);
            $sheet++;
            // 开始填充头部
            $cell_name = 'cell_' . $type;
            $cell_count = count($$cell_name);
            $cell_start = 1;
            $col_char = array();
            for ($f = 'A'; $f <= 'Z'; $f++, $cell_start++) {
                if ($cell_start > $cell_count) {
                    break;
                }
                $col_char[] = $f;
            }
            $col_k = 0;
            foreach ($$cell_name as $key => $v) {
                $objActSheet->getColumnDimension($col_char[$col_k])->setWidth(20);
                $objActSheet->setCellValue($col_char[$col_k] . '1', $v);
                $col_k++;
            }
            $i = 2;

            foreach ($result['xAxis_arr_e'] as $t => $row) {
                $col_k = 0;
                foreach ($$cell_name as $k => $vv) {
                    switch ($k) {
                        case 'date':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $row . ' ');
                            break;
                        case 'income_txt':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $result[$ca]['income_txt'][$t] . ' ');
                            break;
                        case 'income_mer_txt':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $result[$ca]['mer_income_txt'][$t] . ' ');
                            break;
                        case 'sales_count':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $result[$ca]['count_txt'][$t] . ' ');
                            break;
                        case 'consume':
                            $objActSheet->setCellValue($col_char[$col_k] . $i, $result[$ca]['mer_count_txt'][$t] . ' ');
                            break;
                    }
                    $col_k++;
                }
                $i++;
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
        header('Content-Disposition:attachment;filename="'.$title. '.xls"');
        header("Content-Transfer-Encoding:binary");
        $objWriter->save('php://output');
        exit();

    }


    public function get_sales_for_rader_chart(){
        $alias_name = $this->get_alias_name();
        $alias_c_name = $this->get_alias_c_name();
        $money_list = D('Merchant_money_list');
        $res = $money_list->field('type,count(id) as sales,SUM(money) as money')->where(array('income'=>1))->group('type')->select();
        $money=array();
        $sales = array();
        foreach($res as $r){
//            $tmp[$r['type']]['money']=$r['money'];
            $tmp[$r['type']]['sales']=$r['sales'];
        }
        foreach($alias_name as $v){
//            $money[$v] = $tmp[$v]['money']?$tmp[$v]['money']:0;
            $sales[$v] = $tmp[$v]['sales']?$tmp[$v]['sales']:0;
        }
        //$max = trim(max($money),'"');
        $max = trim(max($sales),'"');
        //$max = $max_money>$max_sales?$max_money:$max_sales ;
        $this->ajaxReturn(array('c_key'=>$alias_c_name,'sales'=>$sales,'max'=>$max));
    }

    public function ajax_user(){
        $user_count = M('User')->count();
        $weixin_user = M('User')->where(array('openid'=>array('neq','')))->count();
        $app_user = M('User')->where(array('app_openid'=>array('neq','')))->count();
        $phone_user = M('User')->where(array('phone'=>array('neq','')))->count();
        $men_user = M('User')->where(array('sex'=>1))->count();
        $women_user = M('User')->where(array('sex'=>2))->count();
        $unkonw_user = M('User')->where(array('sex'=>0))->count();
        return array(
            'weixin'=>$weixin_user,
            'app'=>$app_user,
            'phone'=>$phone_user,
            'men'=>$men_user,
            'women'=>$women_user,
            'unknow_user'=>$unkonw_user,
            'user_count'=>$user_count
        );
    }

    public function ajax_merchant_money(){
        if($this->system_session['area_id'] != 0){
            $area_id = $this->system_session['area_id'];
            $all_money = M('')->query('SELECT SUM(power(-1,1+mm.income)*mm.money) AS all_money FROM '.C('DB_PREFIX').'merchant_money_list as mm left join '.C('DB_PREFIX').'merchant as m on mm.mer_id=m.mer_id where m.city_id='.$area_id);
            $all_money = floatval($all_money[0]['all_money']);
            $all_count= M('Merchant_money_list')->where(array('type'=>array('neq','withdraw')))->count();
            $all_mer_money =M('Merchant')->where(array('city_id'=>$area_id))->sum('money');
            $all_need_pay = M('Merchant_withdraw')->where(array('status'=>0,'city_id'=>$area_id))->sum('money');
        }else {
            $all_money = M('')->query('SELECT SUM(power(-1,1+income)*money) AS all_money FROM ' . C('DB_PREFIX') . 'merchant_money_list ');
            $all_money = floatval($all_money[0]['all_money']);
            $all_count = M('Merchant_money_list')->where(array('type' => array('neq', 'withdraw')))->count();
            $all_mer_money = M('Merchant')->sum('money');
            $all_need_pay = M('Merchant_withdraw')->where(array('status' => 0))->sum('money');
        }
        $all_mer_money = $all_mer_money+$all_need_pay;
        return array(
            'all_money'=>$all_money>0?$all_money:0,
            'all_mer_money'=>$all_mer_money>0?$all_mer_money:0,
            'all_need_pay'=>$all_need_pay>0?$all_need_pay:0,
            'all_count'=>$all_count>0?$all_count:0,
        );
    }

    public function pass() {
        $this->display();
    }

    public function amend_pass() {
        $old_pass = $this->_post('old_pass');
        $new_pass = $this->_post('new_pass');
        $re_pass = $this->_post('re_pass');
        if ($old_pass == '') {
            $this->error('请填写旧密码！');
        } else if ($new_pass != $re_pass) {
            $this->error('两次新密码填写不一致！');
        } else if ($old_pass == $new_pass) {
            $this->error('新旧密码不能一样！');
        }

        $database_admin = D('Admin');
        $condition_admin['id'] = $this->system_session['id'];
        $admin = $database_admin->field('`id`,`pwd`')->where($condition_admin)->find();
        if ($admin['pwd'] != md5($old_pass)) {
            $this->error(L('B_OPWRONG'));
        } else {
            $data_admin['id'] = $admin['id'];
            $data_admin['pwd'] = md5($new_pass);
            if ($database_admin->data($data_admin)->save()) {
                $this->success(L('_B_LOGIN_CHANGEKEYSUCESS_'));
            } else {
                $this->error(L('_B_LOGIN_CHANGEKEYLOSE_'));
            }
        }
    }

    public function profile() {

        $database_admin = D('Admin');
        $condition_admin['id'] = $this->system_session['id'];
        $admin = $database_admin->where($condition_admin)->find();
        $sort_menus	=	explode(';',$admin['sort_menus']);
        $sort_menus_son	=	array();
        foreach($sort_menus as &$v){
        	$exp	=	explode(',',$v);
			$sort_menus_son[$exp[0]]	=	$exp[1];
        }
        //var_dump($sort_menus_son);
        $this->assign('sort_menus_son', $sort_menus_son);
        $this->assign('admin', $admin);
        $this->display();
    }

    public function amend_profile() {
        $database_admin = D('Admin');
        $data_admin['id'] = $this->system_session['id'];
        $data_admin['realname'] = $this->_post('realname');
        $data_admin['email'] = $this->_post('email');
        $data_admin['qq'] = $this->_post('qq');
        $data_admin['phone'] = $this->_post('phone');
        $data_admin['sort_menus'] = $this->_post('system_menu');

        if ($database_admin->data($data_admin)->save()) {
            $this->success(L('K_PROFILE_SUCC'));
        } else {
            $this->error(L('K_PROFILE_FAIL'));
        }
    }

    public function cache() {
        import('ORG.Util.Dir');
        Dir::delDirnotself('./runtime');

		if(str_replace(array('.gov.cn','.com.cn','.weihubao.com','.dazhongbanben.com'),'',$_SERVER['HTTP_HOST']) == $_SERVER['HTTP_HOST']){
			$top_domain = $domainArr[$count-2].'.'.$domainArr[$count-1];
		}else{
			$top_domain = $domainArr[$count-3].'.'.$domainArr[$count-2].'.'.$domainArr[$count-1];
		}
		$top_domain = strtolower($top_domain);
		unlink('./source/plan/'.$top_domain.'md5.php');
		unlink('./source/plan/time/'.$top_domain.'process.time');

        $this->frame_main_ok_tips('清除缓存成功！');
    }

    public function menu() {
        $this->assign('bg_color', '#F3F3F3');

        $database = D('Admin');
        $condition['id'] = intval($_GET['admin_id']);
        $admin = $database->field(true)->where($condition)->find();
        if (empty($admin)) {
            $this->frame_error_tips('数据库中没有查询到该管理员的信息！');
        }
        $admin['menus'] = explode(',', $admin['menus']);
        $this->assign('admin', $admin);

        $menus = D('System_menu')->where(array( 'status' => 1,'show'=>1))->select();
        $list = array();
        foreach ($menus as $menu) {
            $menu['name'] = lang_substr($menu['name'],C('DEFAULT_LANG'));
            if (empty($menu['fid'])) {
                if (isset($list[$menu['id']])) {
                    $list[$menu['id']] = array_merge($list[$menu['id']], $menu);
                } else {
                    $list[$menu['id']] = $menu;
                }
            } else {
                if (isset($list[$menu['fid']])) {
                    $list[$menu['fid']]['lists'][] = $menu;
                } else {
                    $list[$menu['fid']]['lists'] = array($menu);
                }
            }
        }
        $this->assign('menus', $list);

        $this->display();
    }

    public function savemenu() {
        if (IS_POST) {
            $admin_id = isset($_POST['admin_id']) ? intval($_POST['admin_id']) : 0;
            $menus = isset($_POST['menus']) ? $_POST['menus'] : '';
            $menus = implode(',', $menus);
            $database = D('Admin');
            $database->where(array('id' => $admin_id))->save(array('menus' => $menus));
            $this->success(L('K_ALL_SUCC'));
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    public function account() {
// 		import('ORG.Net.IpLocation');
// 		$IpLocation = new IpLocation();
        $admins = D('Admin')->field(true)->select();
        foreach ($admins as &$vo){
            if($vo['area_id'] != 0){
                $city = D('Area')->where(array('area_id'=>$vo['area_id']))->find();
                $vo['city_name'] = $city['area_name'];
            }
        }
// 		foreach($admins as &$value){
// 			$last_location = $IpLocation->getlocation(long2ip($value['last_ip']));
// 			$value['last_ip_txt'] = iconv('GBK','UTF-8',$last_location['country']);
// 		}
        $this->assign('admins', $admins);
        $this->display();
    }
	public function account_del(){
		$where['id']	=	$_POST['id'];
		$delete	=	D('Admin')->where($where)->delete();
		if($delete){
            $this->success(L('J_DELETION_SUCCESS'));
        }else{
            $this->error(L('K_FAILED_DELETE'));
        }
    }
    public function admin() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        $admin = D('Admin')->field(true)->where(array('id' => $id))->find();
        //garfunkel 获取城市
        $city = D('Area')->where(array('area_type'=>2))->order('area_name asc')->select();
        $this->assign('city',$city);

        $this->assign('admin', $admin);
        $this->assign('bg_color', '#F3F3F3');
        $this->display();
    }

    public function saveAdmin() {
        if (IS_POST) {
            $database_area = D('Admin');
            $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
            $account = htmlspecialchars($_POST['account']);
            if ($database_area->where("`id`<>'{$id}' AND `account`='{$account}'")->find()) {
                $this->error(L('J_ACCOUNT_EXISTS'));
            }
            if($_POST['level'] != 3)$_POST['area_id'] = 0;
            if($_POST['status'] == 'on') $_POST['status'] = 1;
            else $_POST['status'] = 0;
            unset($_POST['id']);
            if ($id) {
                if ($_POST['pwd']) {
                    $_POST['pwd'] = md5($_POST['pwd']);
                } else {
                    unset($_POST['pwd']);
                }
                $database_area->where(array('id' => $id))->data($_POST)->save();
                $this->success('Success');
                //$this->success($id);
                //echo "<script>window.top.artiframe('/admin.php?g=System&c=Index&a=menu&admin_id=14','{pigcms{:L(\'B_PERMISSIONS\')}',800,500,true,false,false,editbtn,'edit',true);</script>";
            } else {
            	//$_POST['level'] = 0;
                if (empty($_POST['pwd'])) {
                    $this->error(L('K_PASS_EMPTY'));
                }
                $_POST['pwd'] = md5($_POST['pwd']);
                $_POST['menus'] = '1,8';
                if ($new_id = $database_area->data($_POST)->add()) {
                    $this->success($new_id);
                    //redirect(U('Index/menu',array('admin_id'=>$new_id)));
                } else {
                    $this->error('添加失败！请重试~');
                }
            }
        } else {
            $this->error('非法提交,请重新提交~');
        }
    }

    /*     * **网站地图***** */

    public function sitemap() {
		$xmlfilepath = './'.str_replace('.','_',$_SERVER['HTTP_HOST']).'sitemap.xml';
		$this->assign('xmlfilepath', $xmlfilepath);
        $this->display();
    }

    /*     * **执行网站地图*****
     * *<loc>www.example1.com</loc>该页的网址。该值必须少于256个字节(必填项)。格式为<loc>您的url地址</loc>
     * *<lastmod>2010-01-01</lastmod>该文件上次修改的日期(选填项)。格式为<lastmod>年-月-日</lastmod>
     * *<changefreq> always </changefreq>页面可能发生更改的频率(选填项)
     * *有效值为：always、hourly、daily、weekly、monthly、yearly、never
     * *<priority>1.0</priority >此网页的优先级。有效值范围从 0.0 到 1.0 (选填项) 。0.0优先级最低、1.0最高。
     * *
     * */

    public function exeGenerate() {
        set_time_limit('100');
        /*         * **寻找网址*** */
        $UrlSetArr = array();
        $siteurl = $this->config['site_url'];
        $siteurl = rtrim($siteurl, '/') . '/';
        $UrlSetArr[] = array('loc' => $siteurl, 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '1.0');
        /*         * **团购***** */
        $UrlSetArr[] = array('loc' => $siteurl . 'category/all', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.9');
        $urldatatmp = M('Group_category')->field('cat_id,cat_fid,cat_name,cat_url')->where(array('cat_status' => '1'))->order('cat_id ASC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'category/' . $vv['cat_url'], 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.7');
            }
        }

        $jointable = C('DB_PREFIX') . 'merchant';
        $GroupDb = M('Group');
        $GroupDb->join('as grp LEFT JOIN ' . $jointable . ' as mer on grp.mer_id=mer.mer_id');
        $urldatatmp = $GroupDb->field('grp.group_id,grp.mer_id,grp.last_time')->where('grp.status="1" AND mer.status="1"')->order('grp.group_id  DESC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'group/' . $vv['group_id'] . '.html', 'lastmod' => !empty($vv['last_time']) ? date('Y-m-d', $vv['last_time']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.9');
            }
        }

        /*         * **订餐***** */
        $UrlSetArr[] = array('loc' => $siteurl . 'meal/all', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.9');

        $urldatatmp = M('Meal_store_category')->field('cat_id,cat_fid,cat_name,cat_url')->where(array('cat_status' => '1'))->order('cat_id ASC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'meal/' . $vv['cat_url'] . '/all', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.7');
            }
        }
        $urldatatmp = M('Merchant_store')->field('store_id,mer_id')->where(array('have_meal' => '1', 'status' => '1'))->order('store_id ASC')->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'meal/' . $vv['store_id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'always', 'priority' => '0.9');
            }
        }
        /*         * **分类信息***** */
        $UrlSetArr[] = array('loc' => $siteurl . 'classify/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.9');
        $UrlSetArr[] = array('loc' => $siteurl . 'classify/selectsub.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.5');
        $urldatatmp = M('Classify_category')->field('cid,fcid,subdir,updatetime')->where(array('cat_status' => '1'))->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                if (($vv['subdir'] == 1) && ($vv['fcid'] == 0)) {
                    $UrlSetArr[] = array('loc' => $siteurl . 'classify/subdirectory-' . $vv['cid'] . '.html', 'lastmod' => !empty($vv['updatetime']) ? date('Y-m-d', $vv['updatetime']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.5');
                } elseif (($vv['subdir'] == 2) && ($vv['fcid'] > 0)) {
                    $UrlSetArr[] = array('loc' => $siteurl . 'classify/list-' . $vv['cid'] . '.html', 'lastmod' => !empty($vv['updatetime']) ? date('Y-m-d', $vv['updatetime']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.8');
                } elseif (($vv['subdir'] == 3) && ($vv['fcid'] > 0)) {
                    $UrlSetArr[] = array('loc' => $siteurl . 'classify/list-' . $vv['fcid'] . '-' . $vv['cid'] . '.html', 'lastmod' => !empty($vv['updatetime']) ? date('Y-m-d', $vv['updatetime']) : date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.8');
                }
            }
        }

        $urldatatmp = M('Classify_userinput')->field('id,cid,addtime')->where(array('status' => '1'))->order('id DESC')->limit(5000)->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'classify/' . $vv['id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.9');
            }
        }

        /*         * *****商家中心********* */
        $urldatatmp = M('Merchant')->field('mer_id')->where(array('ismain' => 1, 'status' => 1))->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'merindex/' . $vv['mer_id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.3');
            }
        }
        /*         * ******活动********** */
        $UrlSetArr[] = array('loc' => $siteurl . 'activity/', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.6');
        $urldatatmp = M('Extension_activity_list')->field('pigcms_id')->where(array('status' => '1'))->select();
        if (!empty($urldatatmp)) {
            foreach ($urldatatmp as $vv) {
                $UrlSetArr[] = array('loc' => $siteurl . 'activity/' . $vv['pigcms_id'] . '.html', 'lastmod' => date('Y-m-d'), 'changefreq' => 'monthly', 'priority' => '0.5');
            }
        }
        $this->exeGenerateFile($UrlSetArr);
    }

    private function exeGenerateFile($UrlSetArr) {
        if (!empty($UrlSetArr)) {
            $xmlfilepath = './'.str_replace('.','_',$_SERVER['HTTP_HOST']).'sitemap.xml';
            $fp = fopen($xmlfilepath, "wb");
            if ($fp) {
                fwrite($fp, '<?xml version="1.0" encoding="utf-8"?>' . chr(10) . '<urlset>');
                foreach ($UrlSetArr as $uv) {
                    $linestr = chr(10) . '<url>' . chr(10) . '<loc>' . $uv ['loc'] . '</loc>' . chr(10) . '<lastmod>' . $uv['lastmod'] . '</lastmod>' . chr(10) . '<changefreq>' . $uv ['changefreq'] . '</changefreq>' . chr(10) . '<priority>' . $uv['priority'] . '</priority>' . chr(10) . '</url>';
                    fwrite($fp, $linestr);
                }
                fwrite($fp, chr(10) . '</urlset>');
                fclose($fp);
                $this->dexit(array('error' => 0, 'msg' => '生成完成！'));
            } else {
                $this->dexit(array
                    ('error' => 1, 'msg' => '网站根目录下'.$xmlfilepath.'文件不可写！'));
            }
        }
        $this->dexit(array('error' => 1, 'msg' => '没有可生成的数据'));
    }

    /*     * json 格式封装函数* */

    private function dexit($data = '') {
        if (is_array($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }
        exit();
    }
	public function ajax_help($group, $module, $action) {
		$url = strtolower($group . '_' . $module . '_' . $action);
		$url = 'http://o2o-service.pigcms.com/workorder/serviceAnswerApi.php?url=' . $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$content = curl_exec($ch);
		curl_close($ch);

		echo $content;
	}
	public function help(){
		$this->assign('answer_id', $_GET['answer_id']);
		$this->display();
	}

	public function check_account()
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$admin = D('Admin')->field(true)->where(array('id' => $id))->find();
		if (empty($admin)) {
			exit(json_encode(array('error_code' => 1, 'msg' => 'no')));
		}
		if ($admin['openid']) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok', 'nickname' => $admin['nickname'], 'avatar' => $admin['avatar'])));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => 'no')));
		}
	}

	public function cancel_account()
	{
		if ($this->system_session['level'] != 2) exit(json_encode(array('error_code' => 1, 'msg' => '没有权限取消')));
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if (D('Admin')->where(array('id' => $id))->save(array('openid' => '', 'avatar' => '', 'nickname' => ''))) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok')));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => '取消失败')));
		}
	}
		public function http($url, $params, $method = 'GET', $header = array(), $multi = false){
		$opts = array(
				CURLOPT_TIMEOUT        => 30,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_SSL_VERIFYPEER => false,
				CURLOPT_SSL_VERIFYHOST => false,
				CURLOPT_HTTPHEADER     => $header
		);
		/* 根据请求类型设置特定参数 */
		switch(strtoupper($method)){
			case 'GET':
				$opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
				break;
			case 'POST':
				//判断是否传输文件
				$params = $multi ? $params : http_build_query($params);
				$opts[CURLOPT_URL] = $url;
				$opts[CURLOPT_POST] = 1;
				$opts[CURLOPT_POSTFIELDS] = $params;
				break;
			default:
				throw new Exception('不支持的请求方式！');
		}
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt_array($ch, $opts);
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if($error) throw new Exception('请求发生错误：' . $error);
		return  $data;
	}	

}