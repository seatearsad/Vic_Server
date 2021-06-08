<?php
/*
 * 商户后台管理基础类
 *
 */
class BaseAction extends Action{
	protected $merchant_session;
	protected $config;
	protected $static_path;
	protected $static_public;
	public $token;
	public $mer_id;
    protected function _initialize(){
		if(empty($_SERVER['REQUEST_SCHEME'])){
			if($_SERVER['SERVER_PORT'] == '443'){
				$_SERVER['REQUEST_SCHEME'] = 'https';
			}else{
				$_SERVER['REQUEST_SCHEME'] = 'http';
			}
		}
		
		$serverHost = '';
		if(function_exists('getallheaders')){
			$allheaders = getallheaders();
			$serverHost = $allheaders['Host'];
		}
		if(empty($serverHost)){
			$serverHost = $_SERVER['HTTP_HOST'];
		}
		if(mt_rand(1,10) == 1){
			import('ORG.Net.Http');
			$http = new Http();
			$authorizeReturn = Http::curlGet('http://o2o-service.pigcms.com/authorize.php?domain='.$serverHost);
			if($authorizeReturn < -1){
				exit('wow-5');
			}
		}

		$this->check_merchant_file();
		lang_substr_with_default_lang($this->config['site_name']);
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
		$this->merchant_session = session('merchant');

		if(MODULE_NAME != 'Login' && MODULE_NAME != 'Area'){
			if(empty($this->merchant_session) && MODULE_NAME != 'Store' && MODULE_NAME != 'Pick'){
				redirect(U('Login/index'));exit;
			}
			/****实时查找商家的权限****/
			$tmerch = D("Merchant")->field('menus')->where(array('mer_id' => $this->merchant_session['mer_id']))->find();

			if (empty($tmerch['menus'])) {
					$this->merchant_session['menus'] = '';
			} else {
					$this->merchant_session['menus'] = explode(",", $tmerch['menus']);
			}

			/****实时查找商家的权限****/

			$this->assign('merchant_session',$this->merchant_session);

			$merchant_menu_list = S('merchant_menu_list');
			$database_merchant_menu = D('New_merchant_menu');
			if(empty($merchant_menu)){
				$condition_merchant_menu['status'] = 1;
				$condition_merchant_menu['show'] = 1;
				$merchant_menu_list = $database_merchant_menu->field(true)->where($condition_merchant_menu)->order('`sort` desc,`fid` ASC,`id` ASC')->select();
				S('merchant_menu_list',$merchant_menu_list);
			}
            //var_dump($merchant_menu_list);
			$flag = false;
            //lang_substr(,C('Language'))
            //$name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));
			foreach($merchant_menu_list as $key=>$value){
                //echo $key."<br>";
			    $merchant_menu_list[$key]['name']=lang_substr($value['name'],C('DEFAULT_LANG'));
				if(empty($this->config['wxapp_url']) && strtolower($value['module']) == 'wxapp'){
						unset($merchant_menu_list[$key]);
				}
				if(empty($this->config['merchant_ownpay']) && 'ownpay' == strtolower($value['module'])){
						unset($merchant_menu_list[$key]);
				}
			}

			foreach($merchant_menu_list as $value){
				//****处理权限****//
				if ($value['module'] == 'Weidian' && $value['action'] == 'index') {
						if (!empty($this->merchant_session['menus']) && !in_array($value['id'], $this->merchant_session['menus'])) {
								$flag = true;
						}
				}

				if ($value['module'] == MODULE_NAME && $value['action'] == ACTION_NAME) {
					if (!empty($this->merchant_session['menus']) && !in_array($value['id'], $this->merchant_session['menus'])) {
							if (MODULE_NAME == 'Cashier' && ACTION_NAME == 'index') {
									$this->config['is_cashier'] = 0;
									$this->assign('config',$this->config);
							}
							if($this->config['buy_merchant_auth']){
								$this->error(L('K_NACSCA'),U('Merchant_money/buy_merchant_service'));
							}else{
								$this->error(L('K_NACSCA'));

							}
					}
				}
				//****处理权限****//
				if ($value['module'] == 'Weixin' && (empty($this->config['is_open_oauth']) && empty($this->merchant_session['is_open_oauth']))) continue;
				if ($value['module'] == 'Weidian' && (empty($this->config['is_open_weidian']) && empty($this->merchant_session['is_open_weidian']))) continue;
				if (($value['module'] == 'Scenic' || $value['module'] == 'Scenic_config' || $value['module'] == 'Scenic_ticket' || $value['module'] == 'Scenic_park' || $value['module'] == 'Scenic_guide' || $value['module'] == 'Scenic_reply' || $value['module'] == 'Scenic_money') && empty($this->merchant_session['is_open_scenic'])) continue;
				/**********控制商家的菜单显示************/
				if (!empty($this->merchant_session['menus']) && !in_array($value['id'], $this->merchant_session['menus'])) continue;
				/**********控制商家的菜单显示************/
				$select_module = explode(',',$value['select_module']);
				$select_action = explode(',',$value['select_action']);
				if(in_array(MODULE_NAME,$select_module) && (empty($value['select_action']) || in_array(ACTION_NAME,$select_action))){
					$value['is_active'] = true;
				}
				$value['url'] = U($value['module'].'/'.$value['action']);
				$value['name']=str_replace(array('团购','订餐','快店','预约'),array($this->config['group_alias_name'],$this->config['meal_alias_name'],$this->config['shop_alias_name'],$this->config['appoint_alias_name']),$value['name']);

				$merchant_menu[] = $value;
			}

			$merchant_menu = arrayPidProcess($merchant_menu);
			if ($flag && MODULE_NAME == 'Weidian') $this->error(L('K_NACSCA'));

			foreach($merchant_menu as $menu){
				if(!empty($menu['menu_list'])){
					foreach($menu['menu_list'] as $val){
						if($val['is_active']){
							$merchant_menu[$val['fid']]['is_active'] = true;
						}
					}
				}
			}

			$this->assign('merchant_menu',$merchant_menu);
			$this->token = $this->mer_id = $this->merchant_session['mer_id'];
		}
		if($this->merchant_session['is_open_scenic'] == 1){
			if(empty($this->merchant_session['scenic_id'])){
				$scenic_id	=	M('Scenic_list')->field('scenic_id')->where(array('company_id'=>$this->merchant_session['mer_id']))->find();
				$_SESSION['merchant']['scenic_id']	=	$scenic_id['scenic_id'];
				$this->merchant_session['scenic_id']	=	$scenic_id['scenic_id'];
			}
		}
		$this->static_path   = './tpl/Merchant/'.C('DEFAULT_THEME').'/static/';
		$this->static_public = './static/';
		$this->assign('static_path',$this->static_path);
		$this->assign('static_public',$this->static_public);
	}

	protected function check_merchant_file(){
		$filename= substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'] ,'/')+1);
		if($filename == 'index.php'){
			$this->error('非法访问商家中心！');
		}
	}

	public function _empty(){
		$this->error('您访问错了！该页面不存在。');
	}
	public function get_merchant_storelist(){
		$mer_id = $this->merchant_session['mer_id'];
		if(C('butt_open')){
			import('ORG.Net.Http');
			$http = new Http();
			$butt_id = $mer_id;
			if(empty($butt_id)){
				$this->error('登录超时',C('butt_user_url'));
			}
			$return = Http::curlPost(C('butt_physical_url'),get_butt_encrypt_key(array('butt_id'=>$butt_id),C('butt_key')));
			if($return['err_code']){
				$this->error($return['err_msg'] ? $return['err_msg'] : '查询店铺失败,请重试！');
			}else if($return['result']){
				$tmpStoreList = D('Merchant_store')->get_storelist_by_merId($mer_id);
				$store_list = array();
				foreach($tmpStoreList as $value){
					$store_list[$value['store_id']] = $value;
				}
				foreach($return['result'] as $key=>$value){
					if(!empty($store_list[$value['store_id']])){
						$data_store = array(
							'mer_id' => $butt_id,
							'name' => $value['name'],
							'adress' => $value['adress'],
							'phone' => $value['phone'],
							'long' => $value['long'],
							'lat' => $value['lat'],
							'last_time' => $_SERVER['REQUEST_TIME'],
							'pic_info' => $value['pic_info'],
							'txt_info' => $value['txt_info'],
							'status' => '1',
						);
						D('Merchant_store')->where(array('store_id'=>$value['store_id']))->data($data_store)->save();
						unset($store_list[$value['store_id']]);
					}else{
						$data_store = array(
							'store_id' => $value['store_id'],
							'mer_id' => $butt_id,
							'name' => $value['name'],
							'adress' => $value['adress'],
							'phone' => $value['phone'],
							'long' => $value['long'],
							'lat' => $value['lat'],
							'last_time' => $_SERVER['REQUEST_TIME'],
							'pic_info' => $value['pic_info'],
							'txt_info' => $value['txt_info'],
							'status' => '1',
						);
						D('Merchant_store')->data($data_store)->add();
					}
				}
				foreach($store_list as $value){
					D('Merchant_store')->where(array('store_id'=>$value['store_id']))->delete();
				}
			}else{
				redirect(C('butt_merchant_url'));
			}
		}
		return D('Merchant_store')->get_storelist_by_merId($mer_id);
	}
	protected function frame_main_ok_tips($tips,$time=2,$href=''){
		if($href == ''){
			$tips = '<font color=\"red\">'.$tips.'</font>';
			$href = 'javascript:history.back(-1);';
			$tips .= '<br/><br/>系统正在跳转到上一个页面。';
		}
		if($time != 2){
			$tips .= $time.'秒后会提示将自动关闭，可手动关闭！';
		}
		exit('<html><head><script>window.top.msg(1,"'.$tips.'",true,'.$time.');window.parent.frames[\'main\'].location.href="'.$href.'";</script></head></html>');
	}
	protected function error_tips($tips,$time=2,$href=''){
		if($href == ''){
			$tips = '<font color=\"red\">'.$tips.'</font>';
			$href = 'javascript:history.back(-1);';
			$tips .= '<br/><br/>系统正在跳转到上一个页面。';
		}
		if($time != 2){
			$tips .= $time.'秒后会提示将自动关闭，可手动关闭！';
		}
		exit('<html><head><script>window.top.msg(0,"'.$tips.'",true,'.$time.');location.href="'.$href.'";</script></head></html>');
	}
	protected function frame_error_tips($tips,$time=2){
		exit('<html><head><script>window.top.msg(0,"'.$tips.'",true,'.$time.');window.top.closeiframe();</script></head></html>');
	}
}
?>