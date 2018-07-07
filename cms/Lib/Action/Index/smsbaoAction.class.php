<?php
/*
 *短信宝插件
 *
 */
 
class smsbaoAction extends BaseAction {
    public function index(){
		$row = array('name' => 'sms_name', 'type' =>'type=text&validate=required:true', 'value' => 'smsbao', 'info' =>'短信宝用户名', 'desc' =>'你在短信宝注册的用户名', 'tab_id' =>'0', 'tab_name'=>'','gid'=>'15','sort'=>'12','status'=>'1');
        $add=M('config')->add($row);
        echo "<h4>小猪o2o短信宝短信插件安装成功，请删除install_smsbao.php文件</h4>";
    }
}