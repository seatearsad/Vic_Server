<?php

class UpdateAction extends BaseAction{
    public function index(){
		$sql = file_get_contents('./sql.sql');
		$sqls = explode(';',$sql);
		foreach($sqls as $value){
			$value = trim($value);
			echo $value.'<br/><br/>';
			if(!empty($value)){
				dump(D('')->query($value));
				echo '<br/><br/>';
			}
		}
    }
	public function get_union_id(){
		if(empty($_GET['now_uid'])){
			$_GET['now_uid'] = 0;
		}
		$user_list = D('User')->where(array('openid'=>array(array('neq',''),array('NOTLIKE','%~no_use')),'union_id'=>'','uid'=>array('gt',$_GET['now_uid'])))->order('`uid` ASC')->limit(50)->select();
		// echo D('User')->getLastSql();
		if(empty($user_list)){
			exit('ok');
		}
		$access_token_array = D('Access_token_expires')->get_access_token();
		if (!$access_token_array['errcode']) {
			import('ORG.Net.Http');
			$http = new Http();
			foreach($user_list as $key=>$value){
				$return = $http->curlGet('https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$access_token_array['access_token'].'&openid='.$value['openid'].'&lang=zh_CN');
				$userinfo = json_decode($return,true);
				// if($key == 0){
					// dump($value);
					// dump($userinfo);
				// }
				// if($userinfo['errcode']){
					// dump($value);
					// dump($userinfo);
					// die;
				// }
				if($userinfo['unionid']){
					D('User')->where(array('uid'=>$value['uid']))->data(array('union_id'=>$userinfo['unionid']))->save();
				}
			}
		}
		echo  '处理完一批，正在跳转';
		// if(count($user_list) < 50){
			// exit('ok');
		// }
		echo '<script>location.href = "'.U('get_union_id',array('now_uid'=>$value['uid'])).'";</script>';exit;
	}
}