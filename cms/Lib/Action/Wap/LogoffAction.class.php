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
        $page_title = "Account Deletion";
        $this->assign("page_title",$page_title);
        $this->display();
    }

    public function step_2(){
        $page_title = "Account Deletion";
        $this->assign("page_title",$page_title);
        $this->display();
    }

    public function step_3(){
        $page_title = "Account Deletion";
        $this->assign("page_title",$page_title);
        $this->display();
    }
}