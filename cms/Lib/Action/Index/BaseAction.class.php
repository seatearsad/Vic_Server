<?php
/*
 * 用户前台基础类
 *
 */
class BaseAction extends CommonAction{
    protected function _initialize(){
		parent::_initialize();
        echo"----BaseAction---";
		if(!function_exists('indexfdksajflkjsadmbvlknasdfa')){
			redirect('http://www.pigcms.com');
		}
		if($this->config['show_scroll_msg']){
			$scroll_msg = D('Scroll_msg')->get_msg();
			$this->assign('scroll_msg',$scroll_msg);
		}
	}
}
