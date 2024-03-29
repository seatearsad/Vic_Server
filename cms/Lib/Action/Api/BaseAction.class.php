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
    protected $user;
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

        $this->config = D('Config')->get_config();

        if($_POST['scenic_now_city']){
            $this->config['scenic_now_city']	=	$_POST['scenic_now_city'];
        }
        $this->config['site_url'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'];
        C('config',$this->config);

        $this->checkSign();
        //监测被禁用账号
        if($_POST['uid']){
            $this->user = D('User')->where(array('uid'=>$_POST['uid']))->find();
            if($this->user['status'] == 0){
                $this->returnCode(1,'info',array(),'Account has been disabled!');
            }
        }else{
            $this->user = null;
        }
    }

    public function checkSign(){
        $secret_key = $this->config['api_secret_key'];
        $version = (int)str_replace(".","",$_POST['version']);
        $this->app_version = $version;
        if($version >= 260){
            $data = $_POST;
            $sign = strtolower($data['sign']);
            unset($data['sign']);
            $data['a'] = ACTION_NAME;
            ksort($data);
            foreach ($data as &$v){
                $v = html_entity_decode($v);
                $v = str_replace("\"","",$v);
                $v = str_replace("\n","",$v);
            }

            if(abs(time()-$_POST['time']) > 86400){
                $this->returnCode(1,'info',array(),'Time Error');
            }

            $data_str = "a:".ACTION_NAME.",time:".$_POST['time'].",version:".$_POST['version'];

            $self_sign = MD5($data_str.$secret_key);

            if($self_sign != $sign){
                $this->returnCode(1,'info',array(),'Sign Error');
            }
        }
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

        if($this->user != null){
            $array['user_is_logoff'] = $this->user['is_logoff'];
        }else{
            $array['user_is_logoff'] = 0;
        }

        echo json_encode($array);
        exit();
    }
}