<?php
class plan_msg extends plan_base{
	public function runTask(){
		$now_time = time();	
		$msg_list = M('Process_plan_msg')->field(true)->where(array('send_time'=>array('elt',$now_time),'status'=>'0'))->order('`send_time` ASC')->limit(5)->select();
		
		//尽量避免重复执行，先去除任务
		$msg_id = array();
		foreach($msg_list as $value){
			$msg_id[] = $value['id'];
		}
		M('Process_plan_msg')->where(array('id'=>array('in',$msg_id)))->data(array('status'=>'1'))->save();
		// M('Process_plan_msg')->where(array('id'=>array('in',$msg_id)))->delete();
		
		foreach($msg_list as $value){
			$this->keepThread();
			
			if($value['type'] == 1){
				$sms_data = unserialize($value['content']);
				foreach($sms_data as $v){
					$this->keepThread();
					Sms::sendSmsData($v);
				}
			}else if($value['type'] == 2){
				$wechat_data = unserialize($value['content']);
				// fdump($wechat_data,'wechat_data');
				$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
				foreach($wechat_data as $v){
					$this->keepThread();
					$model->sendWeixinTempMsg($v);
				}
			} elseif ($value['type'] == 4) {
				$app_data = array_shift(unserialize($value['content']));
				if (isset($app_data['from']) && $app_data['from'] == 'delivery') {
				    if (!($jpushDeliver instanceof Jpush)) {
				        $jpushDeliver = new Jpush(C('config.deliver_jpush_appkey'), C('config.deliver_jpush_secret'));
				    }
				    $jpushDeliver->send($app_data['platform'], $app_data['audience'], $app_data['notification'], $app_data['message'], false);
				} else {
    				if (!($jpush instanceof Jpush)) {
    					$jpush = new Jpush(C('config.weixin_push_jpush_appkey'), C('config.weixin_push_jpush_secret'));
    				}
    				$jpush->send($app_data['platform'], $app_data['audience'], $app_data['notification'], $app_data['message']);
				}
			}
		}
		return true;
	}
	
	/*
	 *  添加任务
	 *
	 *  param array 消息参数
	 *
	 *  param.type   1为短信通知，2为模板消息，3为图文消息，4为APP通知
	 *
	 *
	 *
	 *  param.content 不同的业务，不同的值，传数组
	 *
	 *  param.send_time  发送时间
	 *
	 *
	 *
	 *
	 */
	public function addTask($param){
		$data['type'] = $param['type'];
		$data['label'] = $param['label'] ? (is_array($param['label']) ? implode(',',$param['label']) : $param['label']) : '';
		if(!is_array($param['content'])){
			$param['content'] = array($param['content']);
		}else if(empty($param['content'][0])){
			$param['content'] = array($param['content']);
		}
		$data['content'] = serialize($param['content']);
		$data['send_time'] = $param['send_time'] ? $param['send_time'] : time();
		$data['add_time'] = time();
		$task_id = M('Process_plan_msg')->data($data)->add();
		import('@.ORG.plan');
		$plan_class = new plan();
		$param = array(
			'file'=>'msg',
			'plan_time'=>$data['send_time'],
			'param'=>array(
				'id'=>$task_id,
			),
		);
		$plan_class->addTask($param);
	}
}
?>