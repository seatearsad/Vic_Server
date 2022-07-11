<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2022/6/21
 * Time: 16:55
 */

class LogoffAction extends BaseAction
{
    public function index(){
        redirect(U('Logoff/step_2'));
        if($_POST){
            $data['phone'] = $_POST['phone'];
            $data['pwd'] = md5($_POST['password']);

            if($data['phone'] != ''){
                $data['status'] = 1;
                $data['login_type'] = 0;//平台用户
                //$data['is_logoff'] = 0;//未注销
                $user = D("User")->where($data)->find();

                if($user && $user['is_logoff'] == 1){
                    exit(json_encode(array('error' => 2)));
                }

                if($user && $user['is_logoff'] == 0){
                    session("logoff_user_id",$user['uid']);
                    $vcode = createRandomStr(6,true,true);

                    $sms_txt = "This is your verification code for log off. Your code is: ".$vcode." .";
                    Sms::sendTwilioSms($data['phone'],$sms_txt);

                    $user_modifypwdDb = M('User_modifypwd');
                    $addtime = time();
                    $expiry = $addtime + 5 * 60; /*             * **五分钟有效期*** */
                    $data = array('telphone' => $data['phone'], 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
                    $insert_id = $user_modifypwdDb->add($data);
                    exit(json_encode(array('error' => 0)));
                }else{
                    exit(json_encode(array('error' => 1)));
                }
            }else {
                exit(json_encode(array('error' => 1)));
            }
        }else {
            $page_title = "Account Deletion";
            $this->assign("page_title", $page_title);
            $this->display();
        }
    }

    public function step_2(){
        if($_POST){
            $logoff_user_id = session("logoff_user_id");
            $user = D("User")->where(array('uid' => $logoff_user_id))->find();
            if($user) {
                $code = $_POST['code'];
                if ($code != "") {
                    $check = M('User_modifypwd')->where(array('telphone' => $user['phone'], 'vfcode' => $code))->find();
                    if ($check) {
                        M('User_modifypwd')->where(array('telphone' => $user['phone'], 'vfcode' => $code))->delete();
                        session("logoff_check", 1);
                        exit(json_encode(array('error' => 0)));
                    } else {
                        exit(json_encode(array('error' => 1)));
                    }
                } else {
                    exit(json_encode(array('error' => 1)));
                }
            }else{
                exit(json_encode(array('error' => 1)));
            }
        }else {
            $page_title = "Account Deletion";
            $this->assign("page_title", $page_title);

            $user = session('user');

            if(!$user){
                if($_GET['u'] && $_GET['sign'] && $_GET['t']){//从app来的
                    $config = D('Config')->get_config();
                    $secret_key = $config['api_secret_key'];

                    $uid = $_GET['u'];
                    $time = $_GET['t'];
                    $sign = $_GET['sign'];
                    $data_str = "c:Logoff,a:step_2,uid:".$uid.",t:".$time;
                    $check_sign = MD5($data_str.$secret_key);
                    if($check_sign == $sign){
                        $user = D('User')->where(array('uid'=>$uid))->find();
                        session("logoff_user_id",$user['uid']);
                    }else{
                        redirect(U('Login/index'));
                    }
                }else{
                    redirect(U('Login/index'));
                }
            }else{
                session("logoff_user_id",$user['uid']);
            }

            if($user['is_logoff'] == 1){
                $this->error_tips("Deletion request already exists for this account. Please do not repeat. To cancel your request, please sign in through the Tutti homepage.",U('Login/index'));
            }

            if ($user) {
                $phone = $user['phone'];
                if($phone == ""){
                    session("logoff_check", 1);
                    redirect(U('Logoff/step_3'));
                }else {
                    $last_two = substr($phone, -2, 2);
                    $this->assign('last_two', $last_two);

                    $code = M('User_modifypwd')->where(array('telphone' => $phone))->order('id desc')->find();
                    if ($code) {
                        $cha_time = 60 - (time() - $code['addtime']);
                    } else {
                        $cha_time = 0;
                    }
                    $this->assign('cha_time', $cha_time);
                }
            } else {
                redirect(U('Logoff/index'));
            }
            $this->display();
        }
    }

    public function step_3(){
        $logoff_user_id = session("logoff_user_id");
        $user = D("User")->where(array('uid' => $logoff_user_id))->find();
        if($user && session("logoff_check") == 1) {
            $page_title = "Account Deletion";
            $this->assign("page_title", $page_title);
            $this->display();
        }else{
            redirect(U('Logoff/index'));
        }
    }

    public function step_4(){
        $logoff_user_id = session("logoff_user_id");
        $user = D("User")->where(array('uid' => $logoff_user_id))->find();
        if($user && session("logoff_check") == 1) {
            if($_POST){
                $is_logoff = $_POST['is_logoff'];
                if($is_logoff == 1) {
                    $time = strtotime(date('Y-m-d', time()));
                    D("User")->where(array('uid' => $logoff_user_id))->save(array('is_logoff' => 1, 'logoff_time' => $time));

                    $email = array(array("address" => $user['email'], "userName" => $user['nickname']));
                    $title = "We Received Your Account Deletion Request";
                    $body = $this->getMailBodySuccess($user['nickname']);
                    $mail = getMail($title, $body, $email);
                    $mail->send();
                }

                session("logoff_user_id",null);
                session("logoff_check",null);
                session("user",null);
                M('User_modifypwd')->where(array('telphone'=>$user['phone']))->delete();
                exit(json_encode(array('error' => 0)));
            }else {
                $page_title = "Account Deletion";
                $this->assign("page_title", $page_title);
                $this->display();
            }
        }else{
            redirect(U('Login/index'));
        }
    }

    public function send_code(){
        $logoff_user_id = session("logoff_user_id");
        $user = D("User")->where(array('uid' => $logoff_user_id))->find();
        if($user) {
            $vcode = createRandomStr(6, true, true);

            $sms_txt = "This is your verification code for log off. Your code is: " . $vcode . " .";
            Sms::sendTwilioSms($user['phone'], $sms_txt);

            $user_modifypwdDb = M('User_modifypwd');
            $user_modifypwdDb->where(array('telphone' => $user['phone']))->delete();
            $addtime = time();
            $expiry = $addtime + 5 * 60; /*             * **五分钟有效期*** */
            $data = array('telphone' => $user['phone'], 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
            $insert_id = $user_modifypwdDb->add($data);

            exit(json_encode(array('error' => 0)));
        }else{
            exit(json_encode(array('error' => 1)));
        }
    }

    public function getMailBodySuccess($name){
        $body = "<p>Hi " . $name . ",</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>We've received your account deletion request. Your Tutti account is scheduled to be deleted after 30 days. If you change your mind, you can restore your account by signing in within the 30-day waiting period. Please be aware that we may retain certain information after account deletion for legal and regulatory purposes.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>If this wasn't you...</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p><a href='https://www.tutti.app/wap.php?g=Wap&c=Login&a=index' target='_blank'>Sign In to Restore Account</a></p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p><a href='https://forms.gle/9zRjKqc3UG2Kugea6' target='_blank'>Leave a Feedback</a></p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>We hope to see you again soon!</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>The Tutti Team</p>";

        return $body;
    }
}