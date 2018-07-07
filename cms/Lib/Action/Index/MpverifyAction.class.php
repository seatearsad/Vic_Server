<?php
/*
 * 微信验证
 *
 */
class MpverifyAction extends Action{
    public function index(){
		echo $_GET['code'];
    }
}