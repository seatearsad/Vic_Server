<?php
class BaseAction extends CommonAction{
	public $token = '';
	public $mer_id = 0;
	public $wecha_id = '';
 	public $is_wexin_browser = false;
	public $merchant_info;
	public $tpl_path;
	public $voic_baidu = array();
    public $is_app_browser =   false;
	protected $user_level;
	public function __construct(){
		parent::__construct();

		if(!function_exists('wapfjdslakfHDFfjlsaf')){
			exit('wow-5');
		}

		$this->token = $this->mer_id = isset($_REQUEST['mer_id']) ? htmlspecialchars($_REQUEST['mer_id']) : (isset($_REQUEST['token']) ? htmlspecialchars($_REQUEST['token']) : 0);
        if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false){
			$this->is_wexin_browser = true;
		}

        if(strpos($_SERVER['HTTP_USER_AGENT'],'pigcmso2oreallifeapp') !== false){
			// fdump($_SERVER['HTTP_USER_AGENT'],'HTTP_USER_AGENT');

			preg_match('/versioncode=(\d+),/',$_SERVER['HTTP_USER_AGENT'],$versionArr);
			// fdump($versionArr,'versionArr');
			$app_version = $versionArr[1];
			$this->assign('app_version',$app_version);


			// fdump($_COOKIE);

			if($_POST['ticket']){
				setcookie('app_ticket',$_POST['ticket']);
			}
			if($_POST['ticket']){
				$ticket = $_POST['ticket'];
			}else if($_COOKIE['app_ticket']){
				$ticket = $_COOKIE['app_ticket'];
			}else{
				preg_match('/ticket=(.*?),/',$_SERVER['HTTP_USER_AGENT'],$ticketArr);
				// fdump($ticketArr,'ticketArr');
				$ticket = $ticketArr[1];
			}

			preg_match('/device-id=(.*?),/',$_SERVER['HTTP_USER_AGENT'],$deviceIdArr);
			// fdump($deviceIdArr,'deviceIdArr');
			$device_id = $deviceIdArr[1];



			if($device_id){
                if(empty($ticket) || $ticket == 'exit'){
                    session('user','');
                    $this->user_session = array();
                }else{
                    $info = ticket::get($ticket, $device_id, true);
					// fdump($info,'info');
                    if($info){
                        $uid = $info['uid'];
                        $user = D('User')->field(true)->where(array('uid'=>$uid))->find();
						// fdump($user,'app_user');
                        session('user',$user);
                        $this->user_session = session('user');
						$this->assign('user_session',$this->user_session);
                        setcookie('login_name',$user['phone'],$_SERVER['REQUEST_TIME']+10000000,'/');
                    }else{
                        session('user','');
                        $this->user_session = array();
                    }
                }
            }
            $this->assign('no_footer',true);
            $this->is_app_browser =   true;
            $this->assign('is_app_browser',$this->is_app_browser);

            if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'android') !== false){
               $this->assign('app_browser_type','android');
            }else{
               $this->assign('app_browser_type','ios');
            }
			$this->assign('user_session',$this->user_session);
        }else if(strpos($_SERVER['HTTP_USER_AGENT'],'pigcmso2olifeapp') !== false){
            //        判断APP跳转到H5，自动登录
            $ticket       =   I('ticket',false);
            $device_id    =   I('Device-Id',false);
            $app_version	=	I('app_version');
            if($app_version){
				$_SESSION['app_version']	=	$app_version;
            }
            $this->assign('app_version',I('app_version'));
            if($device_id){
                if($ticket == 'exit'){
                    session('user','');
                    session_destroy();
                    $this->user_session = array();
                }else if($ticket){
                    $info = ticket::get($ticket, $device_id, true);
                    if ($info) {
                        $uid = $info['uid'];
                        $user = D('User')->field(true)->where(array('uid'=>$uid))->find();
                        session('user',$user);
                        $this->user_session = session('user');
                        setcookie('login_name',$user['phone'],$_SERVER['REQUEST_TIME']+10000000,'/');
                    }else{
                        session('user','');
                        session_destroy();
                        $this->user_session = array();
                    }
                }else{
                    session('user','');
                    session_destroy();
                    $this->user_session = array();
                }
            }
            $this->assign('no_footer',true);
            $this->is_app_browser =   true;
            $this->assign('is_app_browser',$this->is_app_browser);
            if(strpos($_SERVER['HTTP_USER_AGENT'],'android') !== false){
               $this->assign('app_browser_type','android');
            }else{
               $this->assign('app_browser_type','ios');
            }
        }
        $this->assign('is_wexin_browser',$this->is_wexin_browser);
		/* 判断如果没有openId，则自动授权获取openId */
//		if($this->is_wexin_browser){
//			 unset($_SESSION);
//			 session_destroy();
//			$_SESSION['openid'] = 'oG77Rjo8EBPzuQbJrGFa83YsIrQQ';
//			$result = D('User')->autologin('openid',$_SESSION['openid']);
//			$now_user = $result['user'];
//			session('user',$now_user);
//			$this->user_session = session('user');
//		}

		$this->static_path  = $this->config['site_url'].'/tpl/Wap/'.C('DEFAULT_THEME').'/static/';
		$this->assign('static_path',$this->static_path);
		$this->tpl_path  = $this->config['site_url'].'/tpl/Wap/'.C('DEFAULT_THEME').'/';
		$this->assign('tpl_path',$this->tpl_path);

		if($this->is_wexin_browser && empty($_SESSION['openid'])){
			$this->authorize_openid();
		}

		//判断分享获得佣金
		if (!empty($_GET['openid']) && $_SESSION['openid'] && $_GET['openid'] != $_SESSION['openid'] && !M('Merchant_spread')->where(array('openid' => $_SESSION['openid']))->find()) {
			$spread_user = D('User')->get_user($_GET['openid'], 'openid');
			if (!empty($spread_user)) {
				$now_user = D('User')->get_user($_SESSION['openid'], 'openid');
				if (empty($now_user) || C('config.user_spread_replace')) {
					D('User_spread')->where(array('openid' => $_SESSION['openid']))->delete();
					D('User_spread')->data(array('spread_openid' => $_GET['openid'], 'spread_uid' => $spread_user['uid'], 'openid' => $_SESSION['openid']))->add();
				}
			}
		}

		//判断微信浏览器，如果是则获取用户是否已经关注平台公众号
		if($this->config['wechat_follow_txt_url'] && $this->config['wechat_follow_txt_txt'] && !empty($_GET['openid'])){
			if($this->config['wechat_follow_show_open']){
				$invote_follow = true;
			}else{
				if($this->user_session['uid']){
					$now_user = D('User')->get_user($this->user_session['uid']);
					if(empty($now_user['is_follow'])){
						$invote_follow = true;
					}
				}else{
					$invote_follow = true;
				}
			}
			if($invote_follow){
				$invote_user = D('User')->get_user($_GET['openid'],'openid');
				if($invote_user){
					$invote_nickname = !empty($invote_user['truename']) ? $invote_user['truename'] : $invote_user['nickname'];
					$invote_array = array(
						'url'=>$this->config['wechat_follow_txt_url'],
						'txt'=> str_replace('{nickname}',$invote_nickname,$this->config['wechat_follow_txt_txt']),
						'avatar'=>$invote_user['avatar'],
					);
					$this->assign('invote_array',$invote_array);
				}
			}
		}

		$otherwc = isset($_GET['otherwc']) ? intval($_GET['otherwc']) : 0;
		if ($otherwc) {
			$_SESSION['otherwc'] = $otherwc;
		}
		$this->assign('merchant_link_showOther',false);
		if($_SESSION['otherwc']){
			if(!$this->config['merchant_link_showOther']){
				$this->assign('merchant_link_showOther',true);
			}
		}else{
			$this->assign('merchant_link_showOther',true);
		}

		$this->assign('mer_id', $this->mer_id);
		$this->assign('token', $this->token);

		if($this->user_session){
			$times = D('Verify_limit')->field('end_time')->where(array('uid'=>$this->user_session['uid'],'times'=>array('lt',2)))->find();
			if(!empty($this->user_session)){
				$_SESSION['user']['verify_end_time']=$times['end_time'];
			}

		}


		if($this->token){
			$this->merchant_info = D('Merchant_info')->field(true)->where(array('token' => $this->token))->find();
			$merchant = D('Merchant')->field('name')->where(array('mer_id' => $this->token))->find();
			if (empty($this->merchant_info) && $this->token) {
				$info = array('wxname' => $merchant['name'], 'createtime' => time(), 'updatetime' => time(), 'tpltypeid' => 1, 'tpllistid' => 1, 'tplcontentid' => 1, 'tpltypename' => '193_index_b4bt', 'tpllistname' => 'yl_list', 'tplcontentname' => 'ktv_content');
				$info['token'] = $info['uid'] = $this->token;
				$info['id'] = D('Merchant_info')->add($info);
				$this->merchant_info = $info;
			} elseif ($this->merchant_info['wxname'] != $merchant['name']) {
				D('Merchant_info')->where(array('id' => $this->merchant_info['id']))->save(array('wxname' => $merchant['name']));
			}
			$this->merchant_info['wxname'] = $merchant['name'];
		}

		$this->assign('wxuser', $this->merchant_info);
		$this->common_url['group_category_all'] = U('Wap/Group/index');
		$this->assign($this->common_url);

		//判断开关网站
		if($this->config['site_close'] == 2 || $this->config['site_close'] == 3){
			$this->assign('title','网站关闭');
			$this->assign('jumpUrl','-1');
			$this->error_tips($this->config['site_close_reason'] ? $this->config['site_close_reason'] : '网站临时关闭');
		}

		//分享
		if($this->is_wexin_browser || $_SESSION['openid']){
			$share = new WechatShare($this->config,$_SESSION['openid']);
			$this->shareScript = $share->getSgin();
			$this->assign('shareScript', $this->shareScript);
			$this->hideScript = $share->gethideOptionMenu();
			$this->assign('hideScript', $this->hideScript);
		}

		if (empty($this->user_session) && MODULE_NAME != 'Login' && $_GET['wxscan'] == 1 && $this->config['scan_login'] == 1) {
			$this->error_tips('请先进行登录！', U('Login/index', array('referer' => urlencode($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']))));
		}

		$this->check_level();  //检查用户等级
		//$this->check_score();  //清理积分

        if(cookie('house_village_id')){
            if(stripos(__SELF__, 'wap.php')!==false){
                redirect(str_replace('wap.php', C('INDEP_HOUSE_URL'), __SELF__));
            }
        }

        /*城市定位*/
		if(strpos(MODULE_NAME,'Scenic') === 0){
			$database_area = D('Area');
			if($_GET['scenic_city']){
				$now_scenic_city	=	$database_area->field('`area_id`,`area_name`,`area_type`,`area_url`,`area_pid`')->where(array('area_id'=>$_GET['scenic_city']))->find();
				$_SESSION['scenic_travel_city'] = $now_scenic_city;
			}else{
				if(empty($_SESSION['scenic_travel_city'])){
					if($_SERVER['HTTP_HOST'] == 'jq.pigcms.com'){
						$city = '安徽省合肥市';
					}else{
						//通过IP得到当前IP的地理位置
						import('ORG.Net.IpLocation');
						$Ip = new IpLocation('UTFWry.dat');
						$area = $Ip->getlocation();
						$city = iconv('gbk','utf-8',$area['country']);
					}
					if(empty($now_scenic_city) && !empty($city)){
						$condition_now_city['area_type'] = '2';
						$condition_now_city['area_ip_desc'] = $city;
						$condition_now_city['is_open'] = '1';
						$now_scenic_city = $database_area->field('`area_id`,`area_name`,`area_type`,`area_url`,`area_pid`')->where($condition_now_city)->find();
						if(!empty($now_scenic_city)){
							$_SESSION['scenic_travel_city']	=	$now_scenic_city;
						}
					}
				}
				if(empty($_SESSION['scenic_travel_city'])){
					$now_scenic_city	=	$database_area->field('`area_id`,`area_name`,`area_type`,`area_url`,`area_pid`')->where(array('area_type'=>2,'is_hot'=>1))->order('area_sort DESC')->find();
					$_SESSION['scenic_travel_city']	=	$now_scenic_city;
				}
				if(empty($_SESSION['scenic_travel_city'])){
					$now_scenic_city	=	$database_area->field('`area_id`,`area_name`,`area_type`,`area_url`,`area_pid`')->where(array('area_id'=>$this->config['scenic_now_city']))->find();
					$_SESSION['scenic_travel_city']	=	$now_scenic_city;
				}
				if(empty($now_scenic_city)){
					$now_scenic_city	=	$_SESSION['scenic_travel_city'];
				}
			}
			if(empty($this->voic_baidu)){
				$voic_baidu	=	$this->voic_baidu();
				$voic_baidu	=	json_decode($voic_baidu);
				$this->voic_baidu	=	get_object_vars($voic_baidu);
			}
			$this->config['scenic_select_city'] = $now_scenic_city;
			$this->assign('scenic_select_city',$now_scenic_city);
			$this->config['scenic_city'] = $now_scenic_city['area_id'];
		}
	}
	//错误信息提示
	public function error_tips($msg,$url='javascript:history.back(-1);'){
		if(IS_AJAX){
			$this->error($msg,$url);die;
		}else{
			$this->assign('msg',$msg);
			$this->assign('url',$url);
			$this->display('Home/error');
			exit;
		}
	}
	public function success_tips($msg,$url='javascript:history.back(-1);'){
		$this->assign('msg',$msg);
		$this->assign('url',$url);
		$this->display('Home/success');
		exit;
	}

	public function authorize_openid(){
		if(empty($_GET['code']) || empty($_SESSION['weixin']['state'])){
			$_SESSION['weixin']['state']   = md5(uniqid());
			$customeUrl = preg_replace('#&code=(\w+)#','',$this->config['site_url'].$_SERVER['REQUEST_URI']);
			$oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$this->config['wechat_appid'].'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_base&state='.$_SESSION['weixin']['state'].'#wechat_redirect';
			redirect($oauthUrl);exit;
		}else if(isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['weixin']['state'])){
			unset($_SESSION['weixin']);
			import('ORG.Net.Http');
			$http = new Http();
			$return = $http->curlGet('https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->config['wechat_appid'].'&secret='.$this->config['wechat_appsecret'].'&code='.$_GET['code'].'&grant_type=authorization_code');
			$jsonrt = json_decode($return,true);
			if($jsonrt['errcode']){
				$error_msg_class = new GetErrorMsg();
				$this->error_tips('微信网页授权发生错误：'.$error_msg_class->wx_error_msg($jsonrt['errcode']),U('Home/index'));
			}
			if($jsonrt['openid']){
				$_SESSION['openid'] = $jsonrt['openid'];

				//如果存在即 自动登录
				$result = D('User')->autologin('openid',$jsonrt['openid']);
				if(empty($result['error_code'])){
					$now_user = $result['user'];
					session('user',$now_user);
					$this->user_session = session('user');
					if($_SERVER['REQUEST_TIME'] - $now_user['last_weixin_time'] > 259200){	//缓存3天
						$customeUrl = preg_replace('#&code=(\w+)#','',$this->config['site_url'].$_SERVER['REQUEST_URI']);
						redirect(U('Login/weixin',array('referer'=>urlencode($customeUrl))));
					}
				}
			}else{
				redirect(U('Home/index'));
			}
		}else{
			redirect(U('Home/index'));
		}
	}
	public function open_authorize_openid($param){
		if(empty($_GET['code']) || empty($_SESSION['open_weixin']['state'])){
			$_SESSION['open_weixin']['state']   = md5(uniqid());
			$customeUrl = preg_replace('#&code=(\w+)#','',$this->config['site_url'].$_SERVER['REQUEST_URI']);
			$oauthUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$param['appid'].'&redirect_uri='.urlencode($customeUrl).'&response_type=code&scope=snsapi_base&state='.$_SESSION['open_weixin']['state'].'&component_appid='.$this->config['wx_appid'].'#wechat_redirect';
			redirect($oauthUrl);exit;
		}else if(isset($_GET['code']) && isset($_GET['state']) && ($_GET['state'] == $_SESSION['open_weixin']['state'])){
			$get_component_access_token = D('Weixin_bind')->get_component_access_token();
			import('ORG.Net.Http');
			$http = new Http();
			$return = $http->curlGet('https://api.weixin.qq.com/sns/oauth2/component/access_token?appid='.$param['appid'].'&code='.$_GET['code'].'&grant_type=authorization_code&component_appid='.$this->config['wx_appid'].'&component_access_token='.$get_component_access_token);
			$jsonrt = json_decode($return,true);
			if($jsonrt['openid']){
				$_SESSION['open_authorize_openid'] = $jsonrt['openid'];
			}else{
				$_SESSION['open_authorize_openid'] = 'error';
			}
			unset($_GET['code']);
			unset($_SESSION['open_weixin']);
		}else{
			unset($_GET['code']);
			unset($_SESSION['open_weixin']);
			$_SESSION['open_authorize_openid'] = 'error';
		}
	}
	/*粉丝行为分析、统计*/
	public function behavior($param=array(),$extra_param=false){
		$openid = $_SESSION['openid'];

		if(empty($param) || empty($openid)){
			return false;
		}

		if(empty($param['model'])){
			$param['model'] = MODULE_NAME.'_'.ACTION_NAME;
		}

		$database_behavior = M('Behavior');

		$data_behavior = $param;
		$data_behavior['openid'] = $openid;
		$data_behavior['date'] = $data_behavior['last_date'] = $_SERVER['REQUEST_TIME'];
		$database_behavior->data($data_behavior)->add();
	}
	public function _modules(){
		return array(
			'Home_index' => '首页',
			'Search_group' => $this->config['group_alias_name'].'搜索',
			'Search_meal' => $this->config['meal_alias_name'].'搜索',
			'Group_index' => $this->config['group_alias_name'].'列表',
			'Group_detail' => $this->config['group_alias_name'].'内页',
			'Group_feedback' => $this->config['group_alias_name'].'评论列表',
			'Group_branch' => $this->config['group_alias_name'].'页店铺列表',
			'Group_buy' => '提交'.$this->config['group_alias_name'].'订单',
			'Group_shop' => '店铺'.$this->config['group_alias_name'].'页面',
			'Group_addressinfo' => '店家地图',
			'Group_get_route' => '店家路线',
			'Pay_group' => $this->config['group_alias_name'].'确认订单',
			'Pay_meal' => $this->config['meal_alias_name'].'确认订单',
			'Meal_index' => '店铺介绍',
			'Meal_menu' => '店铺菜单',
			'Meal_thissort' => '菜品分类',
			'Meal_cart' => '确认我的菜单',
			'Meal_saveorder' => '提交我的菜单',
			'Meal_detail' => '订单详情',
			'Meal_my' => '我的'.$this->config['meal_alias_name'].'记录',
			'Meal_order' => $this->config['meal_alias_name'].'订单列表',
			'Meal_selectmeal' => $this->config['meal_alias_name'].'点菜',
			'Index_index' => '微网站',
		);
	}

	public function get_encrypt_key($array,$app_key){
		$new_arr = array();
		ksort($array);
		foreach($array as $key=>$value){
			$new_arr[] = $key.'='.$value;
		}
		$new_arr[] = 'app_key='.$app_key;

		$string = implode('&',$new_arr);
		return md5($string);
	}
	protected function get_im_encrypt_key($array, $app_key){
		$new_arr = array();
		ksort($array);
		foreach($array as $key=>$value){
			$new_arr[] = $key.'='.$value;
		}
		$new_arr[] = 'app_key='.$app_key;

		$string = implode('&',$new_arr);
		return md5($string);
	}
	/* WAP/Card模块调用*/
	public function _wapthisCard(){
    	$thisCard = M('Member_card_set')->where(array('token'=>$this->token,'id'=>intval($_GET['cardid'])))->find();
    	return $thisCard;
    }
	/* WAP/Card模块调用*/
	protected function _waptodaySigned(){
    	$signined = 0;
    	$now = time();
    	$where = array('token' => $this->token, 'wecha_id' => $this->user_session['uid'], 'score_type' => 1);
    	$sign = M('Member_card_sign')->where($where)->order('sign_time desc')->find();
    	$today = date('Y-m-d', $now);
    	$itoday = date('Y-m-d', intval($sign['sign_time']));
    	if($sign && $itoday == $today){
    		$signined = 1;
    	}
    	return $signined;
    }
	/* 是否已登录*/
	protected function wapIsLogin(){
		if(empty($this->user_session)){
			$this->error_tips('请先进行登录！',U('Login/index'));
		}
	}
	//出错代码 0 成功
    public function returnCode($code=0,$result=array()){
        if($code == 0){
            $array = array(
                'errorCode'=>0,
                'errorMsg'=>'success',
                'result'=>$result
            );
        }else{
            import("@.ORG.app_api");
            $app_api = new app_api();
            $error = $app_api->errorTip($code);
            $array = array(
                    'errorCode'=>$code,
                    'errorMsg'=>$error
            );
        }

        echo json_encode($array);
        exit();
    }
    # 获取百度语音token
    public function voic_baidu(){
		$voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
		import('ORG.Net.Http');
		$http	=	new Http();
		$url	=	Http::curlGet($voic_baidu);
		return $url;
    }
	# 随机数
	protected function rand_color($len=8,$format='ALL'){
		$is_abc = $is_numer = 0;
		$password = $tmp ='';
		switch($format){
			case 'ALL':
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
			case 'COLOR':
				$chars='abcdef0123456789';
				break;
			case 'CHAR':
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
				break;
			case 'NUMBER':
				$chars='0123456789';
				break;
			default :
				$chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				break;
		}
		mt_srand((double)microtime()*1000000*getmypid());
		while(strlen($password)<$len){
			$tmp =substr($chars,(mt_rand()%strlen($chars)),1);
			if(($is_numer <> 1 && is_numeric($tmp) && $tmp > 0 )|| $format == 'CHAR'){
				$is_numer = 1;
			}
			if(($is_abc <> 1 && preg_match('/[a-zA-Z]/',$tmp)) || $format == 'NUMBER'){
				$is_abc = 1;
			}
			$password.= $tmp;
		}
		if($is_numer <> 1 || $is_abc <> 1 || empty($password) ){
			$password = $this->rand_color($len,$format);
		}
		return $password;
	}

	/*
	 *检查用户等级
	 */
	protected function check_level(){
		if($this->config['level_balance']) {
			if (!empty($this->user_session)) {
				$now_user = D('User')->get_user($this->user_session['uid']);
				$now_level = M('User_level')->where(array('id' => $now_user['level']))->find();
				$time = time();
				if ($now_user['level']!=0&&$now_level['validity']!=0&&$now_user['level_time']!=0&&($time - $now_user['level_time']) / 86400 > $now_level['validity']) {            //超时去除level
					$date['level']= 0;
					$date['level_time']= $time;
					M('User')->where(array('uid'=>$this->user_session['uid']))->setField('level',0);
					$_SESSION['user']['level'] = 0;
					$this->user_session['level'] = 0;
					$log['uid'] = $this->user_session['uid'];
					$log['des'] = '等级到期，系统自动取消';
					$log['add_time'] = $time;
					M('User_level_log')->add($log);
				}
			}
		}
	}
}
?>