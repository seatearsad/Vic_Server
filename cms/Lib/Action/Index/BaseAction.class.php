<?php
/*
 * 用户前台基础类
 *
 */
class BaseAction extends CommonAction{
    protected function _initialize(){
		parent::_initialize();

		if($this->config['show_scroll_msg']){
			$scroll_msg = D('Scroll_msg')->get_msg();
			$this->assign('scroll_msg',$scroll_msg);
		}
	}
}
