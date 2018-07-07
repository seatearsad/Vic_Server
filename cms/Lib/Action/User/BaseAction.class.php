<?php
/*
 * 用户中心管理基础类
 *
 */
class BaseAction extends CommonAction{
	public $now_user;
	
    protected function _initialize(){
		parent::_initialize();
		if(!function_exists('usererkfdnlasDSAskfaf')){
			redirect('http://www.pigcms.com');
		}
		if(empty($this->user_session)){
			redirect(U('Index/Login/index',array('referer'=>urlencode('http://'.$_SERVER['HTTP_HOST'].(!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'])))));
			exit;
		}
		
		$now_user = D('User')->get_user($this->user_session['uid']);

		if(empty($now_user)){
			$this->error_tips('未获取到您的帐号信息，请重试！');
		}
		$now_user['now_money'] = floatval($now_user['now_money']);
		$this->now_user = $now_user;
		if($this->config['show_scroll_msg']){
			$scroll_msg = D('Scroll_msg')->get_msg();
			$this->assign('scroll_msg',$scroll_msg);
		}
		$this->assign('now_user',$now_user);
	}
	
	public function _empty(){
		$this->error('对不起，您访问的页面不存在！');
	}
}

?>