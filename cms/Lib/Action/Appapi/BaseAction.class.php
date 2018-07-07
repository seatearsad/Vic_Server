<?php
/**
 *
 */
class BaseAction extends Action
{
    protected $mobileCode, $_uid, $DEVICE_ID;
    protected $config;
    protected $user_long_lat;
    protected $user_session;
    protected $app_version;
	protected $voic_baidu = array();
    protected function _initialize()
    {
		if(empty($_POST)){
			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
			if(!empty($_POST)){
				$_REQUEST = array_merge($_REQUEST,$_POST);
			}
		}

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

        $this->config = D('Config')->get_config();
        if($this->config['many_city']){
            $now_city   =   I('now_city', false);
            if($now_city){
                $this->config['now_city'] = $now_city;
            }
        }
        if($_POST['scenic_now_city']){
			$this->config['scenic_now_city']	=	$_POST['scenic_now_city'];
        }
        $this->config['site_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        C('config',$this->config);
        $lat    =   I('lat',false);
        $long   =   I('long',false);
        $longlat    =   I('longlat','gps');
        if($lat &&  $long){
            if($longlat == 'baidu'){
                $this->user_long_lat = array('long'=>$long,'lat'=>$lat);
            }else{
                $this->user_long_lat = $this->gpsToBaidu($lat,$long);
            }
        }

        $this->DEVICE_ID = trim($_POST['Device-Id']);
        if(empty($this->DEVICE_ID)){
			$this->DEVICE_ID	=	trim($_POST['Device']);
        }
        file_put_contents('./runtime/test2.php',var_export($this->DEVICE_ID,true));
        C('user_session',$this->user_session);
        $uid    =   I('uid',false);
        $phone  =   I('phone',false);
        if($uid && $phone){
            $this->user_session = array(
                'uid'   =>  $uid,
                'phone' =>  $phone,
            );
        }
        //支付回调
        if ($_GET['is_mobile'] == 2 && $_GET['pay_type']) {
            if (! $this->DEVICE_ID) {
                $this->DEVICE_ID = 200;
            }
        }

        if (! $this->DEVICE_ID) {
//          $this->returnCode('20044004');
        }
        $ticket = I('ticket', false);
        if ($ticket) {
            $info = ticket::get($ticket, $this->DEVICE_ID, true);
            $this->_uid = $info['uid'];
        }

		if($this->DEVICE_ID == 'wxapp' && $this->_uid > 10000){
			$tmpArr = $_POST;
			unset($tmpArr['ticket'],$tmpArr['Device-Id'],$tmpArr['app_version']);
			if(!empty($tmpArr)){
				$tmpArr['module'] = MODULE_NAME;
				$tmpArr['action'] = ACTION_NAME;
				$tmpArr['post_time'] = date('Y-m-d H:i:s',$_SERVER['REQUEST_TIME']);
				fdump($tmpArr,'wxapp_post',true);
			}
		}

        //	支付
        $levelDb=M('User_level');
		$tmparr=$levelDb->field(true)->order('`id` ASC')->select();
		$levelarr=array();
		if($tmparr){
		   foreach($tmparr as $vv){
		      $levelarr[$vv['level']]=$vv;
		   }
		}
		# 语音播报
		if(empty($this->voic_baidu)){
			$voic_baidu	=	$this->voic_baidu();
			$voic_baidu	=	json_decode($voic_baidu);
			$this->voic_baidu	=	get_object_vars($voic_baidu);
		}
		$this->user_level = $levelarr;
        $this->app_version  = I('app_version');
		unset($tmparr,$levelarr);
    }

    //出错代码 0 成功
    public function returnCode($code=0,$result=array(),$error_msg){
		header("Content-type: application/json");
        if($code == 0){
            $array = array(
                'errorCode'=>0,
                'errorMsg'=>'success',
                'result'=>$result
            );
        }elseif(!empty($result)){
            $array = array(
                'errorCode'=>$code,
                'errorMsg'=>'success',
                'result'=>$result
            );
        }elseif(!empty($error_msg)){
            $array = array(
                'errorCode'=>$code,
                'errorMsg'=>$error_msg
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
    //百度经纬度，转换gps经纬度
    public  function    gpsToBaidu($lat,$long){
        import('@.ORG.longlat');
        $longlat_class = new longlat();
        $location2 = $longlat_class->gpsToBaidu($lat, $long);
        return array('long'=>$location2['lng'],'lat'=>$location2['lat']);
    }
//    public function returnPayInfo($state, $msg) {
//        redirect($this->config['site_url']."/return.php?state=$state");exit;
//    }
//    public function error_tips($msg,$url='javascript:history.back(-1);'){
//        $error_msg  =   array(
//            'msg'   =>  $msg,
//            'url'   =>  $url,
//        );
//        return $error_msg;
//        exit;
//    }
    public  function    cityMatching($city_id){
        $long   =   strlen($city_id);
        if($long >= 7){
            $city_id    =   str_replace('市',NULL,$city_id);
        }
        $database_area = D('Area');
        $database_field = '`area_id`,`area_name`';
        $condition_all_city['area_name'] = $city_id;
        $condition_all_city['is_open'] = 1;
        $oCity = $database_area->field($database_field)->where($condition_all_city)->find();
        if($oCity){
            return  $oCity;
        }else{
            return $this->nowCity();
        }
    }
    public  function    nowCity($config=0){
        if($config == 0){
            $config =   $this->config['now_city'];
        }
        $database_area = D('Area');
        $database_field = '`area_id`,`area_name`';
        $condition_all_city['area_id'] = $config;
		$all_city_old = $database_area->field($database_field)->where($condition_all_city)->find();
        return  $all_city_old;
    }
    //得到友好的距离
    protected function wapFriendRange($meter){
        if($meter < 100){
            return '<100m';
        }else if($meter <1000){
            return $meter.'m';
        }else{
            return round($meter/1000,1).'km';
        }
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
	//	社区检测
	public	function	is_existence(){
		$village_id	=	I('village_id');
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$info = ticket::get($ticket, $this->DEVICE_ID, true);
    	if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
    	}
		if(empty($info)){
			$this->returnCode('20000009');
		}
		if(empty($village_id)){
			$this->returnCode('30000001');
		}
		$village = D('House_village')->where(array('village_id'=>$village_id))->find();
		if(empty($village)){
			$this->returnCode('20090005');
		}else{
			if($_SESSION['house']['village_id'] != $village_id){
				$_SESSION['house']	=	$village;
			}
		}
		if($village['status'] == 0){
			$this->returnCode('20090008');
		}
		if($village['status'] == 2){
			$this->returnCode('20000007');
		}
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
    # 获取百度语音token
    public function voic_baidu(){
		$voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
		import('ORG.Net.Http');
		$http	=	new Http();
		$url	=	Http::curlGet($voic_baidu);
		return $url;
    }
	public function getObj(){
		$arr = array('test1'=>'test2');
		$arr = json_encode($arr);
		$arr = json_decode($arr);
		unset($arr->test1);
		return $arr;
	}
}