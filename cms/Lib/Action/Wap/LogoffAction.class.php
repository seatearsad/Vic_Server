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
        if($_POST){
            $data['phone'] = $_POST['phone'];
            $data['pwd'] = md5($_POST['password']);

            if($data['phone'] != ''){
                $data['status'] = 1;
                $data['login_type'] = 0;//平台用户
                $data['is_logoff'] = 0;//未注销
                $user = D("User")->where($data)->find();
                if($user){
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
        $logoff_user_id = session("logoff_user_id");
        $user = D("User")->where(array('uid' => $logoff_user_id))->find();
        if($_POST){
            $code = $_POST['code'];
            if($code != ""){
                $check = M('User_modifypwd')->where(array('telphone'=>$user['phone'],'vfcode'=>$code))->find();
                if($check){
                    M('User_modifypwd')->where(array('telphone'=>$user['phone'],'vfcode'=>$code))->delete();
                    session("logoff_check",1);
                    exit(json_encode(array('error' => 0)));
                }else{
                    exit(json_encode(array('error' => 1)));
                }
            }else{
                exit(json_encode(array('error' => 1)));
            }
        }else {
            $page_title = "Account Deletion";
            $this->assign("page_title", $page_title);

            if ($user) {
                $phone = $user['phone'];
                $last_two = substr($phone, -2, 2);
                $this->assign('last_two', $last_two);
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
                    $title = "";
                    $body = $this->getMailBodySuccess($user['nickname']);
                    //$mail = getMail($title, $body, $email);
                    //$mail->send();
                }

                session("logoff_user_id",null);
                session("logoff_check",null);
                M('User_modifypwd')->where(array('telphone'=>$user['phone']))->delete();
                exit(json_encode(array('error' => 0)));
            }else {
                $page_title = "Account Deletion";
                $this->assign("page_title", $page_title);
                $this->display();
            }
        }else{
            redirect(U('Logoff/index'));
        }
    }

    public function getMailBodySuccess($name){
        $body = "<p>Hi " . $name . ",</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>We've received your account deletion request. Your Tutti account is scheduled to be deleted after 30 days. If you change your mind, you can restore your account by signing in within the 30-day waiting period. Please be aware that we may retain certain information after account deletion for legal and regulatory purposes.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p><a href='https://www.tutti.app/wap.php?g=Wap&c=Login&a=index' target='_blank'>Sign In to Restore Account</a></p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p><a href='https://forms.gle/9zRjKqc3UG2Kugea6' target='_blank'>Leave a Feedback</a></p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>We hope to see you again soon!</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>The Tutti Team</p>";

        return $body;
    }

    public function getMailBodyBeforeDelete(){
        $body = "<p>This is a reminder that your Tutti account will be deleted after 1 day. If you change your mind, you can restore your account by signing in before we delete it.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p><a href='https://www.tutti.app/wap.php?g=Wap&c=Login&a=index' target='_blank'>Sign In to Restore Account</a></p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>We hope to see you again soon!</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>The Tutti Team</p>";

        return $body;
    }
}