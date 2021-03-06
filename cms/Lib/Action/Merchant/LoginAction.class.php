<?php
/*
 * 商户登录
 *
 */

class LoginAction extends BaseAction{
    public function index(){
		$this->display();
    }
	public function check(){
		if($this->isAjax()){
			if(md5($_POST['verify']) != $_SESSION['merchant_login_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}
			
			$database_merchant = D('Merchant');
			$condition_merchant['account'] = trim($_POST['account']);
			$now_merchant = $database_merchant->field(true)->where($condition_merchant)->find();
			if(empty($now_merchant)){
				exit(json_encode(array('error'=>'2','msg'=>'用户名不存在！','dom_id'=>'account')));
			}
			$pwd = md5($_POST['pwd']);
			if($pwd != $now_merchant['pwd']){
				exit(json_encode(array('error'=>'3','msg'=>'密码错误！','dom_id'=>'pwd')));
			}
			if(!empty($now_merchant['merchant_end_time']) && $now_merchant['merchant_end_time'] < $_SERVER['REQUEST_TIME']){
				$data_merchant['mer_id'] = $now_merchant['mer_id'];
				$data_merchant['status'] = '0';
				$database_merchant->data($data_merchant)->save();
				exit(json_encode(array('error'=>'7','msg'=>'您的帐号已经过期！请联系工作人员获得详细帮助。','dom_id'=>'account')));
			}
			if($now_merchant['status'] == 0){
				exit(json_encode(array('error'=>'4','msg'=>'您被禁止登录！请联系工作人员获得详细帮助。','dom_id'=>'account')));
			}else if($now_merchant['status'] == 2){
				exit(json_encode(array('error'=>'5','msg'=>'您的帐号正在审核中，请耐心等待或联系工作人员审核。','dom_id'=>'account')));
			}
			
			$data_merchant['mer_id'] = $now_merchant['mer_id'];
			$data_merchant['last_ip'] = get_client_ip(1);
			$data_merchant['last_time'] = $_SERVER['REQUEST_TIME'];
			$data_merchant['login_count'] = $now_merchant['login_count']+1;
			if($database_merchant->data($data_merchant)->save()){
				$now_merchant['login_count'] += 1;
				
				if(!empty($now_merchant['last_ip'])){
					import('ORG.Net.IpLocation');
					$IpLocation = new IpLocation();
					$last_location = $IpLocation->getlocation(long2ip($now_merchant['last_ip']));
					$now_merchant['last']['country'] = iconv('GBK','UTF-8',$last_location['country']);
					$now_merchant['last']['area'] = iconv('GBK','UTF-8',$last_location['area']);
				}
				session('merchant',$now_merchant);

				if($now_merchant['status']==3){
					$remark = '您的账户处于欠费状态，您的商家业务已经被关闭，请及时充值，充值后将恢复业务';
				}else{
					$remark ='登录成功,现在跳转~';
				}
				exit(json_encode(array('error'=>'0','msg'=>$remark,'dom_id'=>'account')));
			}else{
				exit(json_encode(array('error'=>'6','msg'=>'登录信息保存失败,请重试！','dom_id'=>'account')));
			}
		}else{
			exit('deney Access !');
		}
	}
	public function reg_check(){
		if($this->isAjax()){
			if(md5($_POST['verify']) != $_SESSION['merchant_reg_verify']){
				exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
			}
			$_POST['account'] = trim($_POST['account']);
			//帐号
			$database_merchant = D('Merchant');
			$condition_merchant['account'] = trim($_POST['account']);
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'2','msg'=>'帐号已经存在！','dom_id'=>'account')));
			}
			
			//名称
			$condition_merchant['name'] = $_POST['name'];
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'3','msg'=>'商家名称已经存在！','dom_id'=>'email')));
			}
			
			//邮箱
			$condition_merchant['email'] = $_POST['email'];
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'4','msg'=>'邮箱已经存在！','dom_id'=>'email')));
			}
			
			//手机号
			$condition_merchant['phone'] = $_POST['phone'];
			$now_merchant = $database_merchant->field('`mer_id`')->field(true)->where($condition_merchant)->find();
			if(!empty($now_merchant)){
				exit(json_encode(array('error'=>'5','msg'=>'手机号已经存在！','dom_id'=>'phone')));
			}
			
			$config = D('Config')->get_config();
			$this->assign('config',$config);
			
			$_POST['mer_id'] = null;
			if($config['merchant_verify']){
				$_POST['status'] = 2;
			}else{
				$_POST['status'] = 1;
			}
			
			$_POST['pwd'] = md5($_POST['pwd']);
			$_POST['reg_ip'] = get_client_ip(1);
			$_POST['reg_time'] = $_SERVER['REQUEST_TIME'];
			$_POST['login_count'] = 0;
			$_POST['reg_from'] = 0;
			if($insert_id=$database_merchant->data($_POST)->add()){
				M('Merchant_score')->add(array('parent_id'=>$insert_id,'type'=>1));
				D('Scroll_msg')->add_msg('mer_reg',$insert_id,'商家'.$_POST['name'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '注册成功！');
				if($config['merchant_verify']){
					exit(json_encode(array('error'=>'0','msg'=>'注册成功,请耐心等待审核或联系工作人员审核。~','dom_id'=>'account')));
				}else{
					exit(json_encode(array('error'=>'0','msg'=>'注册成功,请登录~','dom_id'=>'account')));
				}
			}else{
				exit(json_encode(array('error'=>'6','msg'=>'注册失败,请重试！','dom_id'=>'account')));
			}
		}else{
			exit('deney Access !');
		}
	}
	public function logout(){
		session('merchant',null);
		header('Location: '.U('Login/index'));
	}
	public function verify(){
		$verify_type = $_GET['type'];
		if(empty($verify_type)){exit;}
		import('ORG.Util.Image');
		Image::buildImageVerify(4,1,'jpeg',53,26,'merchant_'.$verify_type.'_verify');
	}
}