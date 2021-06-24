<?php
/*
 * 后台管理基础类
 *
 */
class BaseAction extends Action{
	protected $system_session;
	protected $static_path;
	protected $static_public;
	protected $overView;
    protected function _initialize(){
		if(empty($_SERVER['REQUEST_SCHEME'])){
			if($_SERVER['SERVER_PORT'] == '443'){
				$_SERVER['REQUEST_SCHEME'] = 'https';
			}else{
				$_SERVER['REQUEST_SCHEME'] = 'http';
			}
		}


        if (!file_exists(TMPL_PATH . GROUP_NAME.'/_Newface/' . MODULE_NAME . '/' . ACTION_NAME . C('TMPL_TEMPLATE_SUFFIX')))
        {
            C('DEFAULT_THEME', '');
            $this->static_path   = './tpl/System/Static/';
        }
        else
        {
            C('DEFAULT_THEME', '_Newface');
            $this->static_path   = './tpl/System/_Newface/Static/';
        }


        $this->static_public = './static/';
        $this->assign('static_path',$this->static_path);
        $this->assign('static_public',$this->static_public);
        $this->assign('module_name',MODULE_NAME);
        $this->assign('action_name',ACTION_NAME);
		$serverHost = '';
		if(function_exists('getallheaders')){
			$allheaders = getallheaders();
			$serverHost = $allheaders['Host'];
		}
		if(empty($serverHost)){
			$serverHost = $_SERVER['HTTP_HOST'];
		}

		$this->check_admin_file();

		$this->config = D('Config')->get_config();
		if($this->config['open_extra_price']==1){
			$this->config['score_name']=$this->config['extra_price_alias_name'];
		}

		if($this->config['open_score_get_percent']==1){
			$this->config['score_get'] = $this->config['score_get_percent']/100;
		}else{
			$this->config['score_get'] =  $this->config['user_score_get'];
		}

		$this->assign('config',$this->config);
		C('config',$this->config);

		if($this->config['many_city']){
			if($this->config['many_city_main_domain'].'.'.$this->config['many_city_top_domain'] != $_SERVER['HTTP_HOST'] && IS_GET){
				header('Location: '.$_SERVER['REQUEST_SCHEME'].'://'.$this->config['many_city_main_domain'].'.'.$this->config['many_city_top_domain'].$_SERVER['REQUEST_URI']);
				exit();
			}
			//设置域名为顶级域名
			ini_set("session.cookie_domain",$this->config['many_city_top_domain']);
		}
		
		if(!empty($this->config['session_save_type']) && $this->config['session_save_type'] != 'file'){
			$class      = 'Session'. ucwords(strtolower($this->config['session_save_type']));
			// 检查驱动类
			if(require_cache(EXTEND_PATH.'Driver/Session/'.$class.'.class.php')) {
				$hander = new $class();
				$hander->execute();
			}else {
				// 类没有定义
				throw_exception(L('_CLASS_NOT_EXIST_').': ' . $class);
			}
		}
		session_start();
		$this->system_session = session('system');
		if(empty($this->system_session)){
			$this->system_session = session('soft_system');
			if(empty($this->system_session)){
				if($_GET['session_id']){
					session_commit();
					session_id($_GET['session_id']);
					session_start();
					$this->system_session = session('soft_system');
				}
			}
		}

		$log['admin_id'] =$this->system_session['id'] ;
		$log['group'] = GROUP_NAME ;
		$log['module'] = MODULE_NAME ;
		$log['action'] = ACTION_NAME ;
		$log['add_time'] = $_SERVER['REQUEST_TIME'] ;
		M('Admin_log')->add($log);

		if(MODULE_NAME != 'Login'){
			if(empty($this->system_session)){
				header("Location: ".U('Login/index'));
				exit();
			}
			$this->assign('system_session',$this->system_session);
		}





		/****实时查找账号的权限****/
		$tmerch = D("Admin")->field('menus,sort_menus')->where(array('id' => $this->system_session['id']))->find();

		if (empty($tmerch['menus'])) {
			$this->system_session['menus'] = '';
		} else {
			$this->system_session['menus'] = explode(",", $tmerch['menus']);
		}
		$this->system_session['sort_menus'] = array();
		if ($tmerch['sort_menus']){
			$sort_menus	=	explode(";", $tmerch['sort_menus']);
			foreach($sort_menus as $v){
				$exp	=	explode(',',$v);
				$this->system_session['sort_menus'][$exp[0]] =	$exp[1];
			}
		}
		/****实时查找账号的权限****/

		$database_system_menu = D('System_menu');
		$condition_system_menu['status'] = 1;
		$condition_system_menu['show'] = 1;
		$menu_list = $database_system_menu->field(true)->where($condition_system_menu)->order('`sort` DESC,`fid` ASC,`id` ASC')->select();
		$flag = false;//echo "<pre>";var_dump($menu_list);exit;
		$module = $action = '';
		foreach($menu_list as $key=>$value){
			if(empty($this->config['wxapp_url']) && (strtolower($value['module']) == 'wxapp' || $value['name'] == '营销活动')) {
				continue;
			}

			//****处理权限****//
			if (strtolower($value['module']) == strtolower(MODULE_NAME) && strtolower($value['action']) == strtolower(ACTION_NAME)) {
				if (!empty($this->system_session['menus']) && !in_array($value['id'], $this->system_session['menus'])) {
					$flag = true;
					continue;
				}
			}
			//****处理权限****//

			if (empty($value['area_access']) && $this->system_session['area_id'] && !in_array($value['id'], $this->system_session['menus'])) continue;
			/**********控制账号的菜单显示************/
			if (!empty($this->system_session['menus']) && !in_array($value['id'], $this->system_session['menus'])) continue;
			/**********控制账号的菜单显示************/

			if (empty($module) && $value['fid']) {
				$module = ucfirst($value['module']);
				$action = $value['action'];
			}

			$value['name'] =  str_replace('订餐',$this->config['meal_alias_name'],$value['name']);
			$value['name'] =  str_replace('餐饮',$this->config['meal_alias_name'],$value['name']);
			$value['name'] = str_replace('团购',$this->config['group_alias_name'],$value['name']);
			$value['name'] = str_replace('预约',$this->config['appoint_alias_name'],$value['name']);

			//garfunkel add
            $value['name'] = lang_substr($value['name'],C('DEFAULT_LANG'));
			if($value['fid'] == 0){
				$system_menu[$value['id']] = $value;
			}else{
				$system_menu[$value['fid']]['menu_list'][] = $value;
			}
		}
		if ($flag) {
			if ('index' == strtolower(MODULE_NAME) && 'main' == strtolower(ACTION_NAME)) {
				$this->redirect(U("$module/$action"));
			} else {
				$this->error(L('K_NACSCA'), U("$module/$action"));
			}
		}
		$tmp	=	array();
		foreach($system_menu as $key=>$value){
			switch($key){
				case 1:
					$system_menu[$key]['icon'] = 'tasks';
					break;
				case 2:
					$system_menu[$key]['icon'] = 'gear';
					break;
				case 24:
					$system_menu[$key]['icon'] = 'wechat';
					break;
				case 3:
					$system_menu[$key]['icon'] = 'globe';
					break;
				case 4:
					$system_menu[$key]['icon'] = 'spoon';
					break;
				case 130:
					$system_menu[$key]['icon'] = 'shopping-basket';
					break;
				case 5:
					$system_menu[$key]['icon'] = 'user';
					break;
				case 12:
					$system_menu[$key]['icon'] = 'cloud';
					break;
				case 48:
					$system_menu[$key]['icon'] = 'magic';
					break;
				case 56:
					$system_menu[$key]['icon'] = 'clock-o';
					break;
				case 66:
					$system_menu[$key]['icon'] = 'truck';
					break;
				case 74:
					$system_menu[$key]['icon'] = 'home';
					break;
				case 96:
					$system_menu[$key]['icon'] = 'smile-o';
					break;
				case 36:
					$system_menu[$key]['icon'] = 'newspaper-o';
					break;
				case 146:
					$system_menu[$key]['icon'] = 'tree';
					break;
				case 59:
					$system_menu[$key]['icon'] = 'bicycle';
					break;
				case 135:
					$system_menu[$key]['icon'] = 'gift';
					break;
				case 159:
				    $system_menu[$key]['icon'] = 'home';
				    break;
				case 172:
				    $system_menu[$key]['icon'] = 'bank';
				    break;
				case 182:
					$system_menu[$key]['icon'] = 'car';
					break;
				case 204:
					$system_menu[$key]['icon'] = 'wechat';
					break;
                case 216:
                    $system_menu[$key]['icon'] = 'table';
                    break;
			}
			if($this->system_session['sort_menus']){
				if($this->system_session['sort_menus'][$key]){
					$system_menu[$key]['sort_menu']	=	$this->system_session['sort_menus'][$key];
				}else{
					$system_menu[$key]['sort_menu'] =	0;
				}
			}
		}
		if($this->system_session['sort_menus']){
			$system_menu	=	$this->menu_sort($system_menu,'sort_menu');
		}
		$this->assign('system_menu',$system_menu);
		if($this->system_session['level'] == 2 || in_array(0,$this->system_session['menus'])){
            $this->overView = 1;
        }else{
            $this->overView = 0;
        }

        $this->assign('over_view',$this->overView);
		if($_GET['frame']){
			$this->assign('bg_color', '#F3F3F3');
		}
	}

	protected function menu_sort($array,$sort){
		$mune	=	array();	//菜单
		$arrsa	=	array();	//排序
		foreach($array as $k=>&$v){
			if($v[$sort] == 0){
				$arrsa[]	=	$v;
			}else{
				$mune[]	=	$v;
			}
		}
		$mune_sort = $this->my_sort($mune,$sort,SORT_DESC,SORT_NUMERIC);
		$arrsa_sort = $this->my_sort($arrsa,'sort',SORT_DESC,SORT_NUMERIC);
		$arr	=	array_merge_recursive($mune_sort,$arrsa_sort);
		return $arr;
	}
	# 多维数组排序
	protected function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC){
		if(is_array($arrays)){
			foreach ($arrays as $array){
				if(is_array($array)){
					$key_arrays[] = $array[$sort_key];
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
		array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
		return $arrays;
	}
	protected function check_admin_file(){
		$filename= substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'] ,'/')+1);
		if($filename == 'index.php'){
			$this->error('非法访问系统后台！');
		}
	}

	public function _empty(){
		//exit('Fuck ! 你搞错了。');
		exit('对不起，您访问的页面不存在！');
	}

	protected function frame_main_ok_tips($tips,$time=3,$href=''){
		if($href == ''){
			$tips = '<font color=\"red\">'.$tips.'</font>';
			$href = 'javascript:history.back(-1);';
			$tips .= '<br/><br/>系统正在跳转到上一个页面。';
		}
		if($time != 3){
			$tips .= $time.'秒后会提示将自动关闭，可手动关闭！';
		}
		exit('<html><head><script>window.top.msg(1,"'.$tips.'",true,'.$time.');window.parent.frames[\'main\'].location.href="'.$href.'";</script></head></html>');
	}
	protected function error_tips($tips,$time=3,$href=''){
		if($href == ''){
			$tips = '<font color=\"red\">'.$tips.'</font>';
			$href = 'javascript:history.back(-1);';
			$tips .= '<br/><br/>系统正在跳转到上一个页面。';
		}
		if($time != 3){
			$tips .= $time.'秒后会提示将自动关闭，可手动关闭！';
		}
		exit('<html><head><script>window.top.msg(0,"'.$tips.'",true,'.$time.');location.href="'.$href.'";</script></head></html>');
	}
	protected function frame_error_tips($tips,$time=3){
		exit('<html><head><script>window.top.msg(0,"'.$tips.'",true,'.$time.');window.top.closeiframe();</script></head></html>');
	}
	protected function frame_submit_tips($type,$tips,$time=3){
		if($type){
			exit('<html><head><script>window.top.msg(1,"'.$tips.'",true,'.$time.');window.top.main_refresh();window.top.closeiframe();</script></head></html>');
		}else{
			exit('<html><head><script>window.top.msg(0,"'.$tips.'",true,'.$time.');window.top.frames["Openadd"].history.back();window.top.closeiframebyid("form_submit_tips");</script></head></html>');
		}
	}
			/*     * *cURL封装*** */

    final public function httpRequest($url, $method='GET', $postfields = null, $headers = array(), $debug = false) {
        /* $Cookiestr = "";  * cUrl COOKIE处理*
          if (!empty($_COOKIE)) {
          foreach ($_COOKIE as $vk => $vv) {
          $tmp[] = $vk . "=" . $vv;
          }
          $Cookiestr = implode(";", $tmp);
          } */
        $method = strtoupper($method);
        $ci = curl_init();
        /* Curl settings */
        curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
        curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        switch ($method) {
            case "POST":
                curl_setopt($ci, CURLOPT_POST, true);
                if (!empty($postfields)) {
                    $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
                }
                break;
            default:
                curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
                break;
        }
        $ssl = preg_match('/^https:\/\//i', $url) ? TRUE : FALSE;
        curl_setopt($ci, CURLOPT_URL, $url);
        if ($ssl) {
            curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
            curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
        }
        //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ci, CURLOPT_MAXREDIRS, 2); /* 指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的 */
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ci, CURLINFO_HEADER_OUT, true);
        /* curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
        $response = curl_exec($ci);
        $requestinfo = curl_getinfo($ci);
        $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
        if ($debug) {
            echo "=====post data======\r\n";
            var_dump($postfields);
            echo "=====info===== \r\n";
            print_r($requestinfo);

            echo "=====response=====\r\n";
            print_r($response);
        }
        curl_close($ci);
        return array($http_code, $response, $requestinfo);
    }
}
?>