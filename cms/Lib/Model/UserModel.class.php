<?php
class UserModel extends Model
{
	/*得到所有用户*/
	public function get_user($uid,$field='uid')
	{
		$condition_user[$field] = $uid;
		$now_user = $this->field(true)->where($condition_user)->find();
		if(!empty($now_user)){
			if(C('config.open_frozen_money')==1) {
				if ($now_user['frozen_time'] < $_SERVER['REQUEST_TIME'] && $_SERVER['REQUEST_TIME'] < $now_user['free_time']) {
					$now_user['now_money'] -= $now_user['frozen_money'];
					$now_user['now_money'] = $now_user['now_money']<0?0:$now_user['now_money'];
				}
//				if ($now_user['score_extra_count'] > 0) {
//					$now_user['score_count'] += $now_user['score_extra_count'];
//				}
			}
			//dump($now_user['frozen_money']);
			$now_user['now_money'] = floatval($now_user['now_money']);
		}
		return $now_user;
	}
	/*帐号密码检入*/
	public function checkin($phone,$pwd,$type){
		if (empty($phone)){
			if($type){
				return array('error_code' => true, 'msg' => '20042001');
			}else{
				return array('error_code' => true, 'msg' => L('_B_LOGIN_ENTERPHONENO_'));
			}
		}
		if (empty($pwd)){
			if($type){
				return array('error_code' => true, 'msg' => '20042002');
			}else{
				return array('error_code' => true, 'msg' => L('_B_LOGIN_ENTERKEY_'));
			}
		}
		$now_user = $this->field(true)->where(array('phone' => $phone,'is_logoff'=>array('lt',2)))->find();
		if ($now_user){
			if($now_user['pwd'] != md5($pwd)){
				if($type){
					return array('error_code' => true, 'msg' => '20120007');
				}else{
					return array('error_code' => true, 'msg' => L('_PHONENUM_OR_PASSWORD_ERROR_'));
				}
			}
			if(empty($now_user['status'])){
				if($type){
					return array('error_code' => true, 'msg' => '20120008');
				}else{
					return array('error_code' => true, 'msg' => L('_API_FROZEN_USER_'));
				}
			}
			if($now_user['status'] == 2){
				if($type){
					return array('error_code' => true, 'msg' => '20120008');
				}else{
					return array('error_code' => true, 'msg' => '该帐号未审核，无法登录');
				}
			}

			$condition_save_user['uid'] = $now_user['uid'];
			$data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_save_user['last_ip'] = get_client_ip(1);
						/****判断此用户是否在user_import表中***/
			$user_importDb=D('User_import');
			$user_import=$user_importDb->where(array('telphone'=>$phone,'isuse'=>'0'))->find();
			if(!empty($user_import)){
			   !empty($user_import['ppname']) && $data_save_user['truename']=$user_import['ppname'];
			   $data_save_user['qq']=$user_import['qq'];
			   $data_save_user['email']=$user_import['email'];
			   $data_save_user['level']=max($now_user['level'],$user_import['level']);
			   $data_save_user['score_count']=max($now_user['score_count'],$user_import['integral']);
			   $data_save_user['now_money']=max($now_user['now_money'],$user_import['money']);
			   $data_save_user['importid']=$user_import['id'];
			   	if(C('config.reg_verify_sms')){
					$data_user['status'] = 1; //开启注册验证短信就不需要审核
				}else{
					$data_user['status'] = 2; /*             * *未审核*** */

				}
			   $mer_id=$user_import['mer_id'];
			   $data_save_user['openid']=isset($_SESSION['weixin']) && isset($_SESSION['weixin']['user']) ? $_SESSION['weixin']['user']['openid'] :'';
			   if(($mer_id>0) && !empty($data_save_user['openid'])){
			      $merchant_user_relationDb=M('Merchant_user_relation');
				  $mwhere=array('openid'=>$data_save_user['openid'],'mer_id'=>$mer_id);
				  $mtmp=$merchant_user_relationDb->where($mwhere)->find();
				  if(empty($mtmp)){
					 $mwhere['dateline']=time();
					 $mwhere['from_merchant']=3;
				     $merchant_user_relationDb->add($mwhere);
				  }
			   }
			}

			if($now_user['is_logoff'] == 1){
                $data_save_user['is_logoff'] = 0;
                $data_save_user['logoff_time'] = 0;
                $data_save_user['device_id'] = "";
            }

			if($this->where($condition_save_user)->data($data_save_user)->save()){
			    if(!empty($user_import)){
				   $user_importDb->where(array('id'=>$user_import['id']))->save(array('isuse'=>1));
				}

                $now_user['is_logoff'] = 0;
                $now_user['logoff_time'] = 0;
                $now_user['device_id'] = "";

				$database_house_village_user_bind = D('House_village_user_bind');
				$bind_where['uid'] = $now_user['uid'];
				$database_house_village_user_bind->where($bind_where)->data(array('phone'=>$_POST['phone']))->save();
			}
			return array('error_code' => false, 'msg' => 'OK' ,'user'=>$now_user);
		} else {
			if($type){
				return array('error_code' => true, 'msg' => '20120009');
			}else{
				return array('error_code' => true, 'msg' => L('_PHONENUM_OR_PASSWORD_ERROR_'));
			}
		}
	}
	/*手机号、union_id、open_id 直接登录入口*/
	public function autologin($field,$value,$type=0){
		$condition_user[$field] = $value;
		$now_user = $this->field(true)->where($condition_user)->find();
		if($now_user){
			if(empty($now_user['status'])){
				return array('error_code' => true, 'msg' => '该帐号被禁止登录!');
			}
			if($now_user['status'] == 2){
				return array('error_code' => true, 'msg' => '该帐号未审核，无法登录!');
			}
			$condition_save_user['uid'] = $now_user['uid'];
			$data_save_user['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_save_user['last_ip'] = get_client_ip(1);
			$data_save_user['login_type'] = $type;
			$this->where($condition_save_user)->data($data_save_user)->save();

			return array('error_code' => false, 'msg' => 'OK' ,'user'=>$now_user);
		}else{
			return array('error_code'=>1001,'msg'=>'没有此用户！');
		}
	}
	/*
	 *	提供用户信息注册用户，密码需要自行md5处理
	 *
	 *	**** 请自行处理逻辑，此处直接插入用户表 ****
	 */
	public function autoreg($data_user){
		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		$data_user['status'] = 1;
		$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];

		if($data_user['openid']){
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];
		}
		if($uid = $this->data($data_user)->add()){
			$register_give_money_condition = C('config.register_give_money_condition');
			if ($register_give_money_condition == 2 || $register_give_money_condition == 3) {
				//$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
//				$this->where(array('uid'=>$uid))->setInc('score_recharge_money',C('config.register_give_money'));
				//if(C('config.register_give_score')>0){
				//	$this->add_score($uid,1,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
				//}
				$register_give_money_type = C('config.register_give_money_type');
				if($register_give_money_type==1 ||$register_give_money_type==2 ){
					$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
				}
				if($register_give_money_type==0 ||$register_give_money_type==2 ){
					$this->add_score($uid,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
				}
			}

			$spread_user_give_type = C('config.spread_user_give_type');
			if($spread_user_give_type!=3&&!empty($_SESSION['openid'])){
 				$now_user_spread = M('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$_SESSION['openid']))->find();
  				if($now_user_spread) {
  					$spread_user = $this->get_user($now_user_spread['spread_openid'],'openid');
  					$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
  					if ($spread_user_give_type == 0 ||$spread_user_give_type == 2) {
						$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level: C('config.spread_give_money');
						if(C('config.open_score_fenrun')){
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
						}else{
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');
						}
  						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
  					}
  
 					if ($spread_user_give_type == 1 || $spread_user_give_type == 2) {
						$spread_give_score = $now_level['spread_user_give_score']>0?$now_level: C('config.spread_give_score');
						$this->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . C('config.score_name'));
						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.$this->config['score_name'].$spread_give_score.'个');
  					}
  
				}
			}
			return array('error_code' =>false,'msg' =>'OK','uid'=>$uid);
		}else{
			return array('error_code' => true, 'msg' => L('_B_LOGIN_REGISTLOSERE_'));
		}
	}

	public function autoreg_by_scan_merchant_qrcode($data_user){
		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		$data_user['status'] = 1;
		$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];

		if($data_user['openid']){
			$data_user['last_weixin_time'] = $_SERVER['REQUEST_TIME'];
		}
		if($uid = $this->data($data_user)->add()){
			$register_give_money_condition = C('config.register_give_money_condition');
			if ($register_give_money_condition == 2 || $register_give_money_condition == 3) {
				//$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
				//if(C('config.register_give_score')>0){
				//	$this->add_score($uid,1,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
				//}
				$register_give_money_type = C('config.register_give_money_type');
				if($register_give_money_type==1 ||$register_give_money_type==2 ){
					$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
				}
				if($register_give_money_type==0 ||$register_give_money_type==2 ){
					$this->add_score($uid,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
				}
			}

			$spread_user_give_type = C('config.spread_user_give_type');
			if($spread_user_give_type!=3&&!empty($_SESSION['openid'])){
 				$now_user_spread = M('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$_SESSION['openid']))->find();
  				if($now_user_spread) {
  					$spread_user = $this->get_user($now_user_spread['spread_openid'],'openid');
  					$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
  					if ($spread_user_give_type == 0 ||$spread_user_give_type == 2) {
						$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level: C('config.spread_give_money');
						if(C('config.open_score_fenrun')){
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
						}else{
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');
						}
  						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
  					}
  
 					if ($spread_user_give_type == 1 || $spread_user_give_type == 2) {
						$spread_give_score = $now_level['spread_user_give_score']>0?$now_level: C('config.spread_give_score');
						$this->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . C('config.score_name'));
						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.$this->config['score_name'].$spread_give_score.'个');
  					}
  
				}
			}
			return array('error_code' =>false,'msg' =>array('uid'=>$uid));
		}else{
			return array('error_code' => true, 'msg' => L('_B_LOGIN_REGISTLOSERE_'));
		}
	}

	/*帐号密码注册*/
	public function checkreg($phone,$pwd,$userName = '',$email = ''){
		if (empty($phone)) {
			return array('error_code' => true, 'msg' => L('_B_LOGIN_ENTERPHONENO_'));
		}
		if (empty($pwd)) {
			return array('error_code' => true, 'msg' => L('_B_LOGIN_ENTERKEY_'));
		}

		if(is_numeric($phone) == false){
			return array('error_code' => true, 'msg' => '请输入有效的手机号');
		}

		$condition_user['phone'] = $phone;
		if($this->field('`uid`')->where($condition_user)->find()){
			return array('error_code' => true, 'msg' => L('_B_LOGIN_PHONENOHAVE_'));
		}

		$data_user['phone'] = $phone;
		$data_user['pwd'] = md5($pwd);
		$data_user['status'] = 1;
		if($userName == '')
		    $data_user['nickname'] = substr($phone,0,3).'****'.substr($phone,7);
		else
		    $data_user['nickname'] = $userName;

        $data_user['email'] = $email;
		$data_user['add_time'] = $data_user['last_time'] = $_SERVER['REQUEST_TIME'];
		$data_user['add_ip'] = $data_user['last_ip'] = get_client_ip(1);
		$data_user['score_clean_time'] = $_SERVER['REQUEST_TIME'];

		if($uid = $this->data($data_user)->add()){
			$register_give_money_condition = C('config.register_give_money_condition');
			if ($register_give_money_condition == 1 || $register_give_money_condition == 3) {
				//$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
				//if(C('config.register_give_score')>0){
				//	$this->add_score($uid,1,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
				//}
				$register_give_money_type = C('config.register_give_money_type');
				if($register_give_money_type==1 ||$register_give_money_type==2 ){
					$this->add_money($uid, C('config.register_give_money'), '新用户注册平台赠送余额');
				}
				if($register_give_money_type==0 ||$register_give_money_type==2 ){
					$this->add_score($uid,C('config.register_give_score'), '新用户注册平台赠送'.C('config.score_name'));
				}
			}

			$spread_user_give_type = C('config.spread_user_give_type');

			if($spread_user_give_type!=3&&!empty($_SESSION['openid'])){
 				$now_user_spread = M('User_spread')->field('`spread_openid`, `openid`')->where(array('openid'=>$_SESSION['openid']))->find();
  				if($now_user_spread) {
  					$spread_user = $this->get_user($now_user_spread['spread_openid'],'openid');
  					$now_level = M('User_level')->where(array('id' => $spread_user['level']))->find();
  					if ($spread_user_give_type == 0 ||$spread_user_give_type == 2) {
						$spread_give_money = $now_level['spread_user_give_moeny']>0?$now_level: C('config.spread_give_money');
						if(C('config.open_score_fenrun')){
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额','','',$uid);
						}else{
							$this->add_money($spread_user['uid'],  $spread_give_money, '推荐新用户注册平台赠送余额');
						}
  						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠平台余额'.$spread_give_money.'元');
  					}
  
 					if ($spread_user_give_type == 1 || $spread_user_give_type == 2) {
						$spread_give_score = $now_level['spread_user_give_score']>0?$now_level: C('config.spread_give_score');
						$this->add_score($spread_user['uid'], $spread_give_score, '推荐新用户注册平台赠送' . C('config.score_name'));
						D('Scroll_msg')->add_msg('spread_reg',$spread_user['uid'],'用户'.$spread_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'推荐新用户注册获赠'.$this->config['score_name'].$spread_give_score.'个');
  					}
  
				}
			}
			$return = $this->checkin($phone,$pwd);

			if(empty($result['error_code'])){
    			return $return;
    		}else{
				return array('error_code' =>false,'msg' =>'OK');
			}
		}else{
			return array('error_code' => true, 'msg' => L('_B_LOGIN_REGISTLOSERE_'));
		}
	}

	public function check_phone($phone){
		$condition_user['phone'] = $phone;
        $condition_user['is_logoff'] = array('lt',2);
		if($this->field('`uid`')->where($condition_user)->find()){
			return array('error_code' => true, 'msg' => L('_B_LOGIN_PHONENOHAVE_'));
		}
	}
	/*修改用户信息*/
	public function save_user($uid,$field,$value){
		$condition_user['uid'] = $uid;
		$data_user[$field] = $value;
		if($this->where($condition_user)->data($data_user)->save()){
			return array('error'=>0,$field=>$value);
		}else{
			return array('error'=>1,'msg'=>'修改失败！请重试。');
		}
	}
	/*修改用户信息*/
	public function scenic_save_user($where,$data){
		if(empty($where)){
			return 0;
		}
		$save	=	$this->where($where)->data($data)->save();
		if($save){
			return 1;
		}else{
			return 0;
		}
	}

	/*增加用户的钱*/
	public function add_money($uid,$money,$desc,$ask=0,$ask_id=0,$type_id=0,$desc_en){
		$condition_user['uid'] = $uid;
		if($type_id>0){
			D('Fenrun')->add_recommend_award($uid,$type_id,1,$money,$desc);
		}else {
			if ($this->where($condition_user)->setInc('now_money', $money)) {
				D('User_money_list')->add_row($uid, 1, $money, $desc, true, $ask, $ask_id,false,$desc_en);
				return array('error_code' => false, 'msg' => 'OK');
			} else {
				return array('error_code' => true, 'msg' => '用户余额充值失败！请联系管理员协助解决。');
			}
		}
	}

	public function add_score_recharge_money($uid,$money,$desc){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setInc('score_recharge_money',$money)){
			D('User_money_list')->add_row($uid,1,$money,$desc);
			return array('error_code' =>false,'msg' =>'OK');
		}else{
			return array('error_code' => true, 'msg' => '用户'.C('config.score_name').'兑换余额保存记录失败！请联系管理员协助解决。');
		}
	}

	/*使用用户的钱*/
	public function user_money($uid,$money,$desc,$ask=0,$ask_id=0,$withdraw=0,$desc_en=''){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setDec('now_money',$money)){
			$score_recharge_money = $this->where($condition_user)->getField('score_recharge_money');
			if($score_recharge_money>0 && $withdraw==0){
				$now_score_recharge_money = $score_recharge_money>$money?$money:$score_recharge_money;
				$this->where($condition_user)->setDec('score_recharge_money',$now_score_recharge_money);
				D('User_money_list')->add_row($uid,2,$now_score_recharge_money,C('config.score_name')."兑换余额记录减扣 ".$now_score_recharge_money." 元",true,$ask,$ask_id);
			}
			D('User_money_list')->add_row($uid,2,$money,$desc,true,$ask,$ask_id,false,$desc_en);
			return array('error_code' =>false,'msg' =>'OK');
		}else{
			return array('error_code' => true, 'msg' => '用户余额扣除失败！请联系管理员协助解决。');
		}
	}


	/*增加用户的积分*/
	public function add_score($uid,$score,$desc){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setInc('score_count',$score)){
			D('User_score_list')->add_row($uid,1,$score,$desc);

//			$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
//			$now_user = $this->get_user($uid,'uid');
//			$href = C('config.site_url').'/wap.php?c=My&a=integral';
//			$res = $model->sendTempMsg('OPENTM207509450', array('href' => $href, 'wecha_id' => $now_user['openid'], 'first' => C('config.score_name').'变动提醒',  'keyword1'=>date('Y.m.d H:i'),'keyword2' => $score, 'keyword3' => $desc, 'keyword4' => floatval($now_user['score_count']), 'remark' => '积分等于元宝，感谢您的使用'));


			return array('error_code' =>false,'msg' =>C('config.score_name').'回退成功！');
		}else{

			return array('error_code' => true, 'msg' => '添加'.C('config.score_name').'失败！请联系管理员协助解决。');
		}
	}

	//这个方法增加的积分到一定时间会清零
	public function add_extra_score($uid,$score,$desc){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setInc('score_extra_count',$score) && $this->where($condition_user)->setInc('score_count',$score)){ //积分 跟 奖励积分同时增加，清理的时候同时减少

			D('User_score_list')->add_row($uid,1,$score,$desc);
			return array('error_code' =>false,'msg' =>C('config.score_name').'回退成功！');
		}else{
			return array('error_code' => true, 'msg' => '添加'.C('config.score_name').'失败！请联系管理员协助解决。');
		}
	}

	/*使用用户的积分*/
	public function user_score($uid,$score,$desc,$type=2){
		$condition_user['uid'] = $uid;
		if($this->where($condition_user)->setDec('score_count',$score)){
			$now_user = $this->get_user($uid);
			$dec_extra_score = $now_user['score_extra_count']<$score?$now_user['score_extra_count']:$score;
			$this->where($condition_user)->setDec('score_extra_count',$dec_extra_score); //同时减少
			D('User_score_list')->add_row($uid,$type,$score,$desc);
			return array('error_code' =>false,'msg' =>C('config.score_name').'回退成功！');
		}else{
			return array('error_code' => true, 'msg' => '减少'.C('config.score_name').'失败！请联系管理员协助解决。');
		}
	}

	public  function  check_new($phone,$cate_name){

		$user = $this->field('uid')->where(array('phone'=>$phone))->find();
		if(empty($user)){
			$user = $this->field('uid')->where(array('uid'=>$phone))->find();
		}
		$m = new Model();
		$table = array(C('DB_PREFIX').'group_order',C('DB_PREFIX').'meal_order',C('DB_PREFIX').'appoint_order',C('DB_PREFIX').'shop_order',C('DB_PREFIX').'foodshop_order');
		$count = 0;
		$where['uid']=$user['uid'];
		$where['paid'] = 1;
		switch($cate_name){
			case 'all':
				foreach($table as  $v){
					$count += $m->table($v)->where($where)->count('order_id');
				}
				break;
			case 'group':
				$count  = $m->table($table[0])->where($where)->count('order_id');
				break;
			case 'meal':
				$count  = $m->table($table[1])->where($where)->count('order_id');
				break;
			case 'appoint':
				$count  = $m->table($table[2])->where($where)->count('order_id');
				break;
			case 'shop':
				$count  = $m->table($table[3])->where($where)->count('order_id');
				break;
			case 'foodshop':
				$count  = $m->table($table[4])->where($where)->count('order_id');
				break;
		}

		if($count>0){
			return 0;
		}else{
			return 1;
		}
	}

    public function getUserOrderNum($phone,$cate_name){
        $user = $this->field('uid')->where(array('phone'=>$phone))->find();
        if(empty($user)){
            $user = $this->field('uid')->where(array('uid'=>$phone))->find();
        }
        $m = new Model();
        $table = array(C('DB_PREFIX').'group_order',C('DB_PREFIX').'meal_order',C('DB_PREFIX').'appoint_order',C('DB_PREFIX').'shop_order',C('DB_PREFIX').'foodshop_order');
        $count = 0;
        $where['uid']=$user['uid'];
        $where['paid'] = 1;
        switch($cate_name){
            case 'all':
                foreach($table as  $v){
                    $count += $m->table($v)->where($where)->count('order_id');
                }
                break;
            case 'group':
                $count  = $m->table($table[0])->where($where)->count('order_id');
                break;
            case 'meal':
                $count  = $m->table($table[1])->where($where)->count('order_id');
                break;
            case 'appoint':
                $count  = $m->table($table[2])->where($where)->count('order_id');
                break;
            case 'shop':
                $count  = $m->table($table[3])->where($where)->count('order_id');
                break;
            case 'foodshop':
                $count  = $m->table($table[4])->where($where)->count('order_id');
                break;
        }

        return $count;
    }

	public function check_score_can_use($uid,$money,$order_type,$group_id,$mer_id){
		$now_user = $this->get_user($uid);
		$score_count = $now_user['score_count'];
		$score_can_use_count=0;
		$score_deducte=0;
		if ($order_type == 'group'||$order_type == 'meal'||$order_type == 'takeout'||$order_type == 'food'||$order_type == 'foodPad') {
			$user_score_use_condition = C('config.user_score_use_condition');
			$user_score_max_use = D('Percent_rate')->get_max_core_use($mer_id, $order_type);//不同业务不同积分
			if($order_type=='group'){
				$group_info = D('Group')->where(array('group_id'=>$group_id))->find();

				if($group_info['score_use']){
					if($group_info['group_max_score_use']!=0){
						$user_score_max_use = $group_info['group_max_score_use'];
					}
				}else{
					$user_score_max_use = 0;
				}
			}
			$user_score_use_percent=(float)C('config.user_score_use_percent');
			$score_max_deducte=bcdiv($user_score_max_use,$user_score_use_percent,2);


			if($user_score_use_percent>0&&$score_max_deducte>0&&$user_score_use_condition>0){   //如果设置没有错误
				if ($money>=$user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
					if($money>$score_max_deducte){                    //判断积分最大抵扣金额是否比这个订单的总额大
						$score_can_use_count = (int)($score_count>$user_score_max_use?$user_score_max_use:$score_count);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
						$score_deducte = bcdiv($score_can_use_count,$user_score_use_percent,2);
						$score_deducte = $score_deducte>$money?$money:$score_deducte;
					}else{
						//最大可抵扣的金额比总单金额大 只扣掉总单范围内的积分 扣除积分=总单*积分抵扣比例
						$score_can_use_count = ceil($money*$user_score_use_percent);
						$score_deducte = bcdiv($score_can_use_count,$user_score_use_percent,2);
						$score_deducte = $score_deducte>$money?$money:$score_deducte;
					}
				}
			}
		}
		return array('score'=>$score_can_use_count,'score_money'=>floatval($score_deducte));
	}

	public function get_user_by_phone($phone){
		$now_user = $this->where(array('phone'=>$phone))->find();
		return $now_user['uid'];
	}
	# 按出生年月算年龄	韩露
	public function age($birthday){
		if(empty($birthday)){
			return '';
		}
		$age = date('Y', time()) - date('Y', strtotime($birthday)) - 1;
		if (date('m', time()) == date('m', strtotime($birthday))){
			if (date('d', time()) > date('d', strtotime($birthday))){
			$age++;
			}
		}elseif (date('m', time()) > date('m', strtotime($birthday))){
			$age++;
		}
		return $age;
	}

	/*
	 * 用户签到功能
	 * */

	public function check_sign_today($uid){
		$recently_sign = M('User_sign')->where(array('uid'=>$uid))->order('id DESC')->find();
		if($recently_sign && strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))==strtotime(date('Ymd',$recently_sign['sign_time']))){
			return array('error_code'=>0,'msg'=>'已经签到了');
		}else{
			return array('error_code'=>1,'msg'=>'今天没签到');
		}
	}

	public function sign_in($uid){
		$recently_sign = M('User_sign')->where(array('uid'=>$uid))->order('id DESC')->find();
		$now_user = $this->get_user($uid);
		if($recently_sign && strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))==strtotime(date('Ymd',$recently_sign['sign_time']))){
			return array('error_code'=>1,'msg'=>L('_ONEDAY_SIGN_ONCE_'));
		}

		if($recently_sign && (strtotime(date('Ymd',$_SERVER['REQUEST_TIME']))-strtotime(date('Ymd',$recently_sign['sign_time'])))>86400){
			$score_get = C('config.sign_get_score');
			$sign_day = 1;
		}else{

			$score_get = (($recently_sign['day']+1)%30===0?30:($recently_sign['day']+1)%30)+C('config.sign_get_score')-1;
			$sign_day = $recently_sign['day']+1;
		}
		$data['uid'] = $uid;
		$data['day'] = $sign_day;
		$data['score_count'] = $score_get;
		$data['sign_time'] = $_SERVER['REQUEST_TIME'];
		M('User_sign')->add($data);
		$this->add_extra_score($uid,$score_get,replace_lang_str(L('_SIGN_NUM_DAY_'),$sign_day).' '.replace_lang_str(L('_GET_NUM_TICKET_'),$score_get));

		D('Scroll_msg')->add_msg('sign',$uid,$now_user['nickname'].'--'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).replace_lang_str(L('_GET_NUM_TICKET_'),$score_get));
		if(C('DEFAULT_LANG') == 'zh-cn')
		    $return_msg = L('_SIGN_SUCCESS_').' '.replace_lang_str(L('_GET_NUM_TICKET_'),$score_get);
		else
            $return_msg = replace_lang_str(L('_GET_NUM_TICKET_'),$score_get);

		if($sign_day>1){
			$return_msg = replace_lang_str(L('_CON_DAYS_SIGN_'),$sign_day).' '.replace_lang_str(L('_GET_NUM_TICKET_'),$score_get);
		}
		return array('error_code'=>0,'msg'=>$return_msg);
	}

	/*
	 * 今天签到人数
	 * */
	public function sign_num_today(){
		$today = date('Y-m-d',$_SERVER['REQUEST_TIME']);
		$today_start = strtotime($today.' 00:00:00');
		$today_end = strtotime($today.' 23:59:59');
		$where['_string'] = 'sign_time > '.$today_start.' OR sign_time < '.$today_end;
		$today_sign_num = M('User_sign')->where($where)->count();
		return $today_sign_num;
	}

    public function getUserInvitationCode($uid){
        $user = $this->where(array('uid'=>$uid))->find();

        if($user['invitation_code'] == '') {
            $code = $this->getInvitationCode(6);
            $data['invitation_code'] = $code;
            $this->where(array('uid'=>$uid))->save($data);
        }else {
            $code = $user['invitation_code'];
        }

        return $code;
    }
    function getInvitationCode($len){
        $code = $this->getRandomStr($len);
        if($this->where(array('invitation_code'=>$code))->find()){
            $code = $this->getInvitationCode($len);
        }

        return $code;
    }

    function getRandomStr($len, $special=false){
        $chars = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9"
        );

        if($special){
            $chars = array_merge($chars, array("!", "@", "#", "$", "?", "|", "{", "/", ":", ";",
                "%", "^", "&", "*", "(", ")", "-", "_", "[", "]",
                "}", "<", ">", "~", "+", "=", ",", "."
            ));
        }

        $charsLen = count($chars) - 1;
        shuffle($chars);//打乱数组顺序
        $str = '';
        for($i=0; $i<$len; $i++){
            $str .= $chars[mt_rand(0, $charsLen)];//随机取出一位
        }
        return $str;
    }

    function handleLogOffUser(){
	    $offList = $this->where(array('is_logoff'=>1))->select();
	    $time = time();
	    $check_time = 1*3600*24;

	    $sendMailTime = 1*3600*20;

	    $handleList = array();
	    $sendList = array();
	    foreach ($offList as $user){
            $cha_time = $time - $user['logoff_time'];
            if($cha_time >= $check_time){
                $handleList[] = $user['uid'];
            }else if($cha_time >= $sendMailTime){
                if($user['email'] != '') {
                    $sendList[] = array("address" => $user['email'], "userName" => $user['nickname']);
                }
            }
        }

        if(count($handleList) > 0) {
	        $this->where(array('uid'=>array('in',$handleList)))->save(array('is_logoff'=>2,'logoff_time'=>$time,'device_id'=>'','openid'=>''));
	        D('User_card')->where(array('uid'=>array('in',$handleList)))->delete();
            D('User_adress')->where(array('uid'=>array('in',$handleList)))->delete();
            D('Reply')->where(array('uid'=>array('in',$handleList)))->delete();
        }

        if(count($sendList) > 0){
            $title = "Reminder: Your Tutti Account Will Be Deleted Soon";
            $body = $this->getMailBodyBeforeDelete();
            $mail = getMail($title, $body, $sendList);
            $mail->send();
        }
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
?>