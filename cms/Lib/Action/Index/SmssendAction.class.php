<?php
class SmssendAction extends BaseAction{
	public function sms_send() {
		$user_modifypwdDb = M('User_modifypwd');
		 if(isset($_POST['phone']) && !empty($_POST['phone'])){
			 $result = D('User')->check_phone($_POST['phone']);
			 if(!empty($result)&&$_POST['reg']){
				 $this->ajaxReturn($result);
			 }
			$chars = '0123456789';
			mt_srand((double)microtime() * 1000000 * getmypid());
			$vcode = "";

			while (strlen($vcode) < 6)
				$vcode .= substr($chars, (mt_rand() % strlen($chars)), 1);
			/*
			$content = '您的验证码是：'. $vcode . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
			Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $_POST['phone'], 'uid' => $this->now_user['uid'], 'type' => 'bindphone'));
			*/
			/* add garfunkel new send sms*/
             $sms_data['uid'] = 0;
             $sms_data['mobile'] = $_POST['phone'];
             $sms_data['sendto'] = 'user';
             $sms_data['tplid'] = 367023;
             $sms_data['params'] = [
                 $vcode
             ];
             Sms::sendSms2($sms_data);

            ///
			$addtime = time();
			$expiry = $addtime + 5 * 60; /*             * **五分钟有效期*** */
			$data = array('telphone' => $_POST['phone'], 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
			$insert_id = $user_modifypwdDb->add($data);
			$this->ajaxReturn(array('error' => false));
			exit();

		}
	}

    public function sms_send_deliver() {
        $user_modifypwdDb = M('User_modifypwd');
        if(isset($_POST['phone']) && !empty($_POST['phone'])){
            $condition_user['phone'] = $_POST['phone'];
            if(D('Deliver_user')->field('`uid`')->where($condition_user)->find()){
                $result = array('error_code' => true, 'msg' => L('_B_LOGIN_PHONENOHAVE_'));
            }
            if(!empty($result)&&$_POST['reg']){
                $this->ajaxReturn($result);
            }
            $chars = '0123456789';
            mt_srand((double)microtime() * 1000000 * getmypid());
            $vcode = "";

            while (strlen($vcode) < 6)
                $vcode .= substr($chars, (mt_rand() % strlen($chars)), 1);
            /*
            $content = '您的验证码是：'. $vcode . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
            Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $_POST['phone'], 'uid' => $this->now_user['uid'], 'type' => 'bindphone'));
            */
            /* add garfunkel new send sms*/
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $_POST['phone'];
            $sms_data['sendto'] = 'user';
            $sms_data['tplid'] = 169244;
            $sms_data['params'] = [
                $vcode
            ];
            Sms::sendSms2($sms_data);

            ///
            $addtime = time();
            $expiry = $addtime + 60; /*             * **五分钟有效期*** */
            $data = array('telphone' => $_POST['phone'], 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
            $insert_id = $user_modifypwdDb->add($data);
            $this->ajaxReturn(array('error' => false));
            exit();

        }
    }

    public function sms_send_forget_deliver() {
        $user_modifypwdDb = M('User_modifypwd');
        if(isset($_POST['phone']) && !empty($_POST['phone'])){
            $condition_user['phone'] = $_POST['phone'];

            if(!D('Deliver_user')->field('`uid`')->where($condition_user)->find()){
                $result = array('error_code' => true, 'msg' => 'Phone Number Error!');
            }
            if(!empty($result)){
                $this->ajaxReturn($result);
            }
            $chars = '0123456789';
            mt_srand((double)microtime() * 1000000 * getmypid());
            $vcode = "";

            while (strlen($vcode) < 6)
                $vcode .= substr($chars, (mt_rand() % strlen($chars)), 1);
            /*
            $content = '您的验证码是：'. $vcode . '。此验证码20分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！';
            Sms::sendSms(array('mer_id' => 0, 'store_id' => 0, 'content' => $content, 'mobile' => $_POST['phone'], 'uid' => $this->now_user['uid'], 'type' => 'bindphone'));
            */
            /* add garfunkel new send sms*/
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $_POST['phone'];
            $sms_data['sendto'] = 'user';
            $sms_data['tplid'] = 169244;
            $sms_data['params'] = [
                $vcode
            ];
            Sms::sendSms2($sms_data);

            ///
            $addtime = time();
            $expiry = $addtime + 60; /*             * **五分钟有效期*** */
            $data = array('telphone' => $_POST['phone'], 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
            $insert_id = $user_modifypwdDb->add($data);
            $this->ajaxReturn(array('error' => false));
            exit();
        }
    }
}