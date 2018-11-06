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
		
        $this->config['site_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        C('config',$this->config);
    }

    //出错代码 0 成功
    public function returnCode($code=0,$name='data',$result=array(),$error_msg){
        header("Content-type: application/json");
        if($code == 0){
            if($name == ""){
                $array = array(
                    'status'=>1,
                    'fail'=>''
                );
                foreach ($result as $k => $v){
                    $array[$k] = $v;
                }
            }else{
                $array = array(
                    'status'=>1,
                    'fail'=>'',
                    $name =>$result
                );
            }
        }elseif(!empty($result)){
            $array = array(
                'status'=>0,
                'fail'=>$error_msg,
                $name =>$result
            );
        }elseif(!empty($error_msg)){
            $array = array(
                'status'=>0,
                'fail'=>$error_msg
            );
        }else{
            import("@.ORG.app_api");
            $app_api = new app_api();
            $error = $app_api->errorTip($code);
            $array = array(
                'status'=>$code,
                'fail'=>$error
            );
        }

        echo json_encode($array);
        exit();
    }
}