<?php
/*
 * 社区首页
 *
 */
class IndexAction extends BaseAction{
	protected $village_id;
	protected $village;
	
	public function _initialize(){
		parent::_initialize();

		$this->village_id = $this->house_session['village_id'];
		$this->village = D('House_village')->field(true)->where(array('village_id'=>$this->village_id))->find();
		if(empty($this->village)){
			$this->error('该小区不存在！');
		}
		if($this->village['status'] == 0 && MODULE_NAME == 'Index' && (ACTION_NAME != 'index' && ACTION_NAME != 'village_edit')){
			$this->assign('jumpUrl',U('Index/index'));
			$this->error('您需要先完善信息才能继续操作');
		}
	}
	
    public function index(){
		$village_id = $this->village_id;
		if($village_id){
			$village_info = D('House_village')->field(true)->where(array('village_id'=>$village_id))->find();
			$village_info['long'] = floatval($village_info['long']);
			$village_info['lat'] = floatval($village_info['lat']);
			$this->assign('village_info',$village_info);
		}
		$this->display();
    }
	
    public function village_edit(){
    	$village_id = $this->village_id;
    	$condition_village['village_id'] = $village_id;
    	
    	if(IS_POST){
    		if(empty($_POST['long_lat'])){
    			$this->error('小区经纬度必填！');
    		}
    		if(empty($_POST['village_address'])){
    			$this->error('小区地址必填！');
    		}

			if(!is_int($_POST['property_warn_day'] + 0)){
				$this->error('物业提醒时间必须为整数！');
			}
    			
    		$long_lat = explode(',',$_POST['long_lat']);
    		$_POST['long'] = floatval($long_lat[0]);
    		$_POST['lat'] = floatval($long_lat[1]);
    		$_POST['status'] = 1;
			if(empty($_POST['long']) || empty($_POST['lat'])){
    			$this->error('请选取小区经纬度！');
    		}
			$_SESSION['house']['has_service_slide'] = $_POST['has_service_slide'];
    		$result = D('House_village')->where($condition_village)->data($_POST)->save();
    		if($result !== false){
    			$this->success('Success');
    		}else{
    			$this->error('修改失败！请重试。');
    		}
    	}
    }
    
    public function active_group_list(){
    	$village_id = $this->village_id;
    	$group = D('House_village_group')->get_limit_list_page($village_id);
    	
    	$this->assign('group',$group);
    	
    	$this->display();
    }
    
    public function active_group(){
    	if(IS_AJAX){
    		$group_id = intval($_POST['group_id']);
    		$sort = $_POST['sort']?intval($_POST['sort']):0;

    		$result = $this->checkVillageGroup($group_id);
    		if($result['error'] == 0){
    				
    			$data['village_id'] = $this->village_id;
    			$data['group_id'] = $group_id;
    			$data['sort'] = $sort;
				$data['url'] = $_POST['url'];
    			if(D('House_village_group')->add($data)){
    				$this->ajaxReturn(array('error'=>0));
    			}
			
    				
    			$this->ajaxReturn(array('msg'=>'保存失败请重试','error'=>1));
    		}elseif($result['error'] == 1){
    			$this->ajaxReturn(array('msg'=>$result['msg'],'error'=>1));
    		}else{
    			$this->ajaxReturn(array('msg'=>'保存失败请重试','error'=>1));
    		}
    		exit;
    	}else{
    		$this->display();
    	}
    }
    
    public function check_group(){
    	if(IS_AJAX){
    		$village_id = $this->village_id;
    		$group_id = $_POST['group_id'];
    		
    		$result = $this->checkVillageGroup($group_id);
    		if($result['error'] == 0){
    			$this->ajaxReturn($result);
    		}elseif($result['error'] == 1){
    			$this->ajaxReturn(array('msg'=>$result['msg'],'error'=>1));
    		}else{
    			$this->ajaxReturn(array('msg'=>'暂无匹配的团购信息','error'=>1));;
    		}
    		exit;
    	}
    }
    
   	public function active_group_del(){
   		if(IS_AJAX){
   			$group_id = $_POST['group_id'];
   			if(!$group_id){
   				$this->ajaxReturn( array('msg'=>'信息错误','error'=>1));
   			}
   			$condition['village_id'] = $this->village_id;
   			$condition['group_id'] = $group_id;
   			$village_group = D('House_village_group')->where($condition)->find();
   			if(!$village_group){
   				$this->ajaxReturn( array('msg'=>'暂无此信息','error'=>1));
   			}
   			if(D('House_village_group')->where($condition)->delete()){
   				$this->ajaxReturn( array('msg'=>'删除成功','error'=>0));
   			}
   			$this->ajaxReturn( array('msg'=>'删除失败','error'=>1));
   			exit;
   		}
   	}
   	
    public function checkVillageGroup($group_id){
    	$village_id = $this->village_id;
    	
    	if(!$group_id){
    		return array('msg'=>'检测通过之后才能保存哦','error'=>1);
    	}
    	
    	$village_group = D('House_village_group')->field(true)->where(array('village_id'=>$village_id,'group_id'=>$group_id))->find();
    	if($village_group){
    		return array('msg'=>'您已经保存该团购了，请勿重复操作','error'=>1);
    	}
    	
    	$now_time = $_SERVER['REQUEST_TIME'];
    	$group_info = D('')->field('`g`.`name` AS `group_name`,`m`.`name` AS `merchant_name`')->table(array(C('DB_PREFIX').'group'=>'g',C('DB_PREFIX').'merchant'=>'m'))->where("`g`.`mer_id`=`m`.`mer_id` AND `g`.`status`='1' AND `m`.`status`='1' AND `g`.`type`='1' AND `g`.`begin_time`<'$now_time' AND `g`.`end_time`>'$now_time' AND `g`.`group_id` ='$group_id'")->find();
    	if(!empty($group_info)){
    		$group_info['error'] = 0;
    		return $group_info;
    	}
    	return array('msg'=>'暂无匹配的团购信息','error'=>1);
    }
    
    // 预约相关
    public function active_appoint_list(){
    	$village_id = $this->village_id;
    	$appoint = D('House_village_appoint')->get_limit_list_page($village_id);
    	$this->assign('appoint',$appoint);
    	$this->display();
    }
    
    public function active_appoint(){
    	if(IS_POST){
    		$appoint_id = intval($_POST['appoint_id']);
    		$sort = $_POST['sort']?intval($_POST['sort']):0;
    
    		$result = $this->checkVillageAppoint($appoint_id);
    		if($result['error'] == 0){
				if($_FILES['img']['error'] != 4){
					$image = D('Image')->handle($this->village['village_id'],'house/appoint',3,array('size'=>5),false);
					if (!$image['error']) {
						$data['pic'] = str_replace('/upload/house/appoint/','',$image['url']['img']);
					}else{
						$this->error($image['msg']);
					}
					$data['label'] = $_POST['label'];
					$data['index_show'] = '1';
				}
    			$data['village_id'] = $this->village_id;
    			$data['appoint_id'] = $appoint_id;
    			$data['sort'] = $sort;
    			$data['url'] = $_POST['url'];

    			if(D('House_village_appoint')->add($data)){
					$this->success('添加成功');
    			}else{
					$this->error('保存失败请重试');
				}
    		}elseif($result['error'] == 1){
				$this->error($result['msg']);
    		}else{
				$this->error('保存失败请重试');
    		}
    	}else{
    		$this->display();
    	}
    }
	public function active_appoint_edit(){
		$now_acitive = D('House_village_appoint')->field(true)->where(array('pigcms_id'=>$_GET['id'],'village_id'=>$this->village['village_id']))->find();
		if(empty($now_acitive)){
			$this->error('当前推荐的预约不存在');
		}
    	if(IS_POST){
			$data['pigcms_id'] = $now_acitive['pigcms_id'];
    		$appoint_id = intval($_POST['appoint_id']);
    		$sort = $_POST['sort']?intval($_POST['sort']):0;
    
    		$result = $this->checkVillageAppoint($appoint_id);
    		if($result['error'] == 0){
				if($_FILES['img']['error'] != 4){
					$image = D('Image')->handle($this->village['village_id'],'house/appoint',3,array('size'=>5),false);
					if (!$image['error']) {
						$data['pic'] = str_replace('/upload/house/appoint/','',$image['url']['img']);
					}else{
						$this->error($image['msg']);
					}
				}
				$data['label'] = $_POST['label'];
				if(!empty($data['label'])){
					$data['index_show'] = '1';
				}else{
					$data['index_show'] = '0';
				}
				
    			$data['village_id'] = $this->village_id;
    			$data['appoint_id'] = $appoint_id;
    			$data['sort'] = $sort;
    			
    			if(D('House_village_appoint')->data($data)->save()){
					$this->success('编辑成功');
    			}else{
					$this->error('保存失败请重试');
				}
    		}elseif($result['error'] == 1){
				$this->error($result['msg']);
    		}else{
				$this->error('保存失败请重试');
    		}
    	}else{
			$this->assign('now_acitive',$now_acitive);
    		$this->display();
    	}
    }
    
    public function check_appoint(){
    	if(IS_AJAX){
    		$village_id = $this->village_id;
    		$appoint_id = $_POST['appoint_id'];
    
    		$result = $this->checkVillageAppoint($appoint_id);
    		if($result['error'] == 0){
    			$this->ajaxReturn($result);
    		}elseif($result['error'] == 1){
    			$this->ajaxReturn(array('msg'=>$result['msg'],'error'=>1));
    		}else{
    			$this->ajaxReturn(array('msg'=>'暂无匹配的预约信息','error'=>1));;
    		}
    		exit;
    	}
    }
    
    public function active_appoint_del(){
    	if(IS_AJAX){
    		$appoint_id = $_POST['appoint_id'];
    		if(!$appoint_id){
    			$this->ajaxReturn( array('msg'=>'信息错误','error'=>1));
    		}
    		$condition['village_id'] = $this->village_id;
    		$condition['appoint_id'] = $appoint_id;
    		$village_appoint = D('House_village_appoint')->field(true)->where($condition)->find();
    		if(!$village_appoint){
    			$this->ajaxReturn( array('msg'=>'暂无此信息','error'=>1));
    		}
    		if(D('House_village_appoint')->where($condition)->delete()){
    			$this->ajaxReturn( array('msg'=>'删除成功','error'=>0));
    		}
    		$this->ajaxReturn( array('msg'=>'删除失败','error'=>1));
    		exit;
    	}
    }
    
    public function checkVillageAppoint($appoint_id){
    	$village_id = $this->village_id;
    	 
    	if(!$appoint_id){
    		return array('msg'=>'检测通过之后才能保存哦','error'=>1);
    	}
    	$condition = array('village_id'=>$village_id,'appoint_id'=>$appoint_id);
		if($_GET['id']){
			$condition['pigcms_id'] = array('neq',$_GET['id']);
		}
    	$village_appoint = D('House_village_appoint')->field(true)->where($condition)->find();

    	if($village_appoint){
    		return array('msg'=>'您已经保存该预约了，请勿重复操作','error'=>1);
    	}
    	 
    	$now_time = $_SERVER['REQUEST_TIME'];
    	$appoint_info = D('')->field('`a`.`appoint_name` ,`m`.`name` AS `merchant_name`')->table(array(C('DB_PREFIX').'appoint'=>'a',C('DB_PREFIX').'merchant'=>'m'))->where("`a`.`mer_id`=`m`.`mer_id` AND `a`.`check_status`='1' AND `a`.`appoint_status`='0' AND `m`.`status`='1' AND `a`.`start_time`<'$now_time' AND `a`.`end_time`>'$now_time' AND `a`.`appoint_id`='$appoint_id'")->find();
    	if(!empty($appoint_info)){
    		$appoint_info['error'] = 0;
    		return $appoint_info;
    	}
    	return array('msg'=>'暂无匹配的预约信息','error'=>1);
    }
    
    // 快店相关
    public function active_meal_list(){
    	
    	$village_id = $this->village_id;
    	$meal = D('House_village_meal')->get_limit_list_page($village_id);

    	$this->assign('meal',$meal);
    	$this->display();
    }
    
    public function active_meal(){
    	if(IS_AJAX){
    		$store_id = intval($_POST['store_id']);
    		$sort = $_POST['sort']?intval($_POST['sort']):0;
    
    		$result = $this->checkVillageMeal($store_id);
    		if($result['error'] == 0){
    
    			$data['village_id'] = $this->village_id;
    			$data['store_id'] = $store_id;
    			$data['sort'] = $sort;
				$data['url'] = $_POST['url'];
    			if(D('House_village_meal')->add($data)){
    				$this->ajaxReturn(array('error'=>0));
    			}
    
    			$this->ajaxReturn(array('msg'=>'保存失败请重试','error'=>1));
    		}elseif($result['error'] == 1){
    			$this->ajaxReturn(array('msg'=>$result['msg'],'error'=>1));
    		}else{
    			$this->ajaxReturn(array('msg'=>'保存失败请重试','error'=>1));
    		}
    		exit;
    	}else{
    		$this->display();
    	}
    }
    
    public function check_meal(){
    	if(IS_AJAX){
    		$village_id = $this->village_id;
    		$store_id = $_POST['store_id'];
    
    		$result = $this->checkVillageMeal($store_id);
    		if($result['error'] == 0){
    			$this->ajaxReturn($result);
    		}elseif($result['error'] == 1){
    			$this->ajaxReturn(array('msg'=>$result['msg'],'error'=>1));
    		}else{
    			$this->ajaxReturn(array('msg'=>'暂无匹配的快店信息','error'=>1));;
    		}
    		exit;
    	}
    }
    
    public function active_meal_del(){
    	if(IS_AJAX){
    		$store_id = $_POST['store_id'];
    		if(!$store_id){
    			$this->ajaxReturn( array('msg'=>'信息错误','error'=>1));
    		}
    		$condition['village_id'] = $this->village_id;
    		$condition['store_id'] = $store_id;
    		$village_appoint = D('House_village_meal')->where($condition)->find();
    		if(!$village_appoint){
    			$this->ajaxReturn( array('msg'=>'暂无此信息','error'=>1));
    		}
    		if(D('House_village_meal')->where($condition)->delete()){
    			$this->ajaxReturn( array('msg'=>'删除成功','error'=>0));
    		}
    		$this->ajaxReturn( array('msg'=>'删除失败','error'=>1));
    		exit;
    	}
    }
    
    public function checkVillageMeal($store_id){
    	$village_id = $this->village_id;
    
    	if(!$store_id){
    		return array('msg'=>'检测通过之后才能保存哦','error'=>1);
    	}
    
    	$village_meal= D('House_village_meal')->where(array('village_id'=>$village_id,'store_id'=>$store_id))->find();
    	if($village_meal){
    		return array('msg'=>'您已经保存该快店了，请勿重复操作','error'=>1);
    	}
    
    	$now_time = $_SERVER['REQUEST_TIME'];
    	$meal_info = D('')->field('`m`.`name` AS `merchant_name`,`ms`.`name` AS `store_name`')->table(array(C('DB_PREFIX').'merchant_store'=>'ms',C('DB_PREFIX').'merchant_store_shop'=>'msm',C('DB_PREFIX').'merchant'=>'m'))->where("`ms`.`have_meal`='1' AND `ms`.`status`='1' AND `m`.`mer_id`=`ms`.`mer_id` AND `ms`.`store_id`=`msm`.`store_id` AND `ms`.`store_id`='$store_id'")->find();
    	if(!empty($meal_info)){
    		$meal_info['error'] = 0;
    		return $meal_info;
    	}
    	return array('msg'=>'暂无匹配的快店信息','error'=>1);
    }
	


	public function ajax_help($group, $module, $action) 
	{
		$url = strtolower($group . '_' . $module . '_' . $action);
		$url = 'http://o2o-service.pigcms.com/workorder/serviceAnswerApi.php?url=' . $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_TIMEOUT, 4);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$content = curl_exec($ch);
		curl_close($ch);

		$content = json_decode($content,true);
		foreach ($content as $value) {
			$class = $value['is_video'] == 1 ? 'class="video"' : 'class="writing"';
			$html .= '<p class="lianjie zuoce_clear "><a ' . $class . ' href="javascript:openwin(' . "'" . U('Index/help', array('answer_id' => $value['answer_id'])) . "'" .',768,960)">'.$value['title'].'</a></p>';
		}
		if (empty($html)) {
			$html = '<p class="lianjie zuoce_clear">没有帮助教程！</a>';
		}
		echo $html;
	}
	
	public function help()
	{
		$this->assign('answer_id', $_GET['answer_id']);
		$this->display();
	}
   
	public function cancel()
	{
		if (D('House_village')->where(array('village_id' => $this->village_id))->save(array('openid' => '', 'avatar' => '', 'nickname' => ''))) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok')));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => '取消失败')));
		}
	}
	
	public function check_bind()
	{
		if ($this->village['openid']) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok', 'nickname' => $this->village['nickname'], 'avatar' => $this->village['avatar'])));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => 'no')));
		}
	}
	
	public function worker()
	{
		$workers = D('House_worker')->field(true)->where(array('village_id' => $this->village_id))->select();
		$this->assign('workers', $workers);
		$this->display();
	}
    
	public function worker_add()
	{
		if (IS_POST) {
			$data = array('village_id' => $this->village_id);
			$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$data['phone'] = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
			if (empty($data['name']) || empty($data['phone'])) {
				$this->error('姓名和电话不能为空');
			}
			if (D('House_worker')->field(true)->where(array('village_id' => $this->village_id, 'phone' => $data['phone']))->find()) {
				$this->error('电话已存在，不能重复增加');
			}
			D('House_worker')->add($data);
			$this->success('增加成功');
		} else {
			$this->display();
		}
	}
    
	public function worker_edit()
	{
		$wid = isset($_GET['wid']) ? intval($_GET['wid']) : 0;
		$worker = D('House_worker')->field(true)->where(array('village_id' => $this->village_id, 'wid' => $wid))->find();
		if (empty($worker)) {
			$this->error('没有该条工作人员记录');
		}
		if (IS_POST) {
			$data = array('village_id' => $this->village_id);
			$data['name'] = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$data['phone'] = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
			$data['type'] = isset($_POST['type']) ? intval($_POST['type']) : 0;
			if (isset($_POST['status']) && $worker['openid']) {
				$data['status'] = $_POST['status'];
			}
			
			if (empty($data['name']) || empty($data['phone'])) {
				$this->error('姓名和电话不能为空');
			}
			$this_worker = D('House_worker')->field(true)->where(array('village_id' => $this->village_id, 'phone' => $data['phone']))->find();
			if (!empty($this_worker) && $this_worker['wid'] != $worker['wid']) {
				$this->error('电话已存在，不能重复增加');
			}
			D('House_worker')->where(array('village_id' => $this->village_id, 'wid' => $wid))->save($data);
			$this->success('修改成功');
		} else {
			$this->assign('worker', $worker);
			$this->display();
		}
	}
	
	public function check_worker()
	{
		$wid = isset($_GET['wid']) ? intval($_GET['wid']) : 0;
		$worker = D('House_worker')->field(true)->where(array('village_id' => $this->village_id, 'wid' => $wid))->find();
		if (empty($worker)) {
			exit(json_encode(array('error_code' => 1, 'msg' => 'no')));
		}
		if ($worker['openid']) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok', 'nickname' => $worker['nickname'], 'avatar' => $worker['avatar'])));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => 'no')));
		}
	}
	
	public function cancel_worker()
	{
		$wid = isset($_GET['wid']) ? intval($_GET['wid']) : 0;
		if (D('House_worker')->where(array('village_id' => $this->village_id, 'wid' => $wid))->save(array('status' => 0, 'openid' => '', 'avatar' => '', 'nickname' => ''))) {
			exit(json_encode(array('error_code' => 0, 'msg' => 'ok')));
		} else {
			exit(json_encode(array('error_code' => 1, 'msg' => '取消失败')));
		}
	}
	
	public function worker_order()
	{
		$wid = isset($_GET['wid']) ? intval($_GET['wid']) : 0;
		$begin_time = isset($_POST['begin_time']) ? htmlspecialchars($_POST['begin_time']) : '';
		$end_time = isset($_POST['end_time']) ? htmlspecialchars($_POST['end_time']) : '';
		
		$worker = D('House_worker')->field(true)->where(array('village_id' => $this->village_id, 'wid' => $wid))->find();
		if (empty($worker)) {
			$this->error('不存在的工作人员，或已辞职');
		}
		$where = array('village_id' => $this->village_id, 'wid' => $wid);
		if ($begin_time && $end_time) {
			$where['begin_time'] = strtotime($begin_time . '00:00:00');
			$where['end_time'] = strtotime($end_time . '23:59:59');
		}elseif($begin_time){
			$where['begin_time'] = strtotime($begin_time . '00:00:00');
		}elseif($end_time){
			$where['end_time'] = strtotime($end_time . '23:59:59');
		}

		$status = $_POST['status'] + 0;
		if($status > 0){
			$where['status'] = $status - 1;
		}

		$order ='';
		if($_GET['time']){
			$order['time'] = $_GET['time'];
		}


		$repair_list = D('House_village_repair_list')->getlist($where , 20 , $order);
		$this->assign('repair_list', $repair_list);
		$this->assign('wid', $wid);
		$this->assign(array('begin_time' => $begin_time, 'end_time' => $end_time));
		$this->display();
	}
    
    public function info()
    {
    	$bind_id = isset($_GET['bindid']) ? intval($_GET['bindid']) : 0;
    	$cms_id = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
    	if ($bind_id && $cms_id) {
    		$condition['bind_id'] = $bind_id;
    		$condition['pigcms_id'] = $cms_id;
    		$condition['village_id'] = $this->village_id;
    		$repair = D('House_village_repair_list')->getlist($condition, 1);
			$repair = $repair['repair_list'][0];
			
    		$this->assign('repair', $repair);
    		if ($repair['status']) {
    			$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
    			$this->assign('worker', $worker);
    		} else {
// 	    		$type = $repair['type'] == 1 ? 1 : 0;
// 		    	$workers = D('House_worker')->field(true)->where(array('type' => $type, 'status' => 1, 'village_id' => $this->village_id))->select();
// 		    	$this->assign('workers', $workers);
    		}
    	}
    	$this->display();
    }


	public function worker_export(){
			$begin_time = 0;
			$end_time = 0;
			if(isset($_GET['begin_time']) && !empty($_GET['begin_time'])){
				$begin_time = strtotime($_GET['begin_time'] . '00:00:00');
			}

			if(isset($_GET['end_time']) && !empty($_GET['end_time'])){
				$end_time = strtotime($_GET['end_time'] . '23:59:59');
			}

			if(($begin_time > 0) && ($end_time > 0)){
				if($begin_time > $end_time){
					$this->error("结束时间应大于开始时间");
				}

				$where['begin_time'] = $begin_time;
				$where['end_time'] = $end_time;
			}elseif(isset($begin_time)){
				$where['begin_time'] = $begin_time;
			}elseif(isset($end_time)){
				$where['end_time'] = $end_time;
			}

			$where['village_id'] = $this->village_id;

			$wid = $_GET['wid'] + 0;
			$where['wid'] = $wid;

			$repair_list = D('House_village_repair_list')->getlist($where,1000);
			$repair_list = $repair_list['repair_list'];

			if(count($repair_list) <= 0 ){
				$this->error('无数据导出！');
			}

			require_once APP_PATH . 'Lib/ORG/phpexcel/PHPExcel.php';

			$title = $this->village['village_name'] . '社区-工作人员任务列表';

			$objExcel = new PHPExcel();
			$objProps = $objExcel->getProperties();
			// 设置文档基本属性
			$objProps->setCreator($title);
			$objProps->setTitle($title);
			$objProps->setSubject($title);
			$objProps->setDescription($title);

			$length = ceil(count($repair_list)/1000);

			for ($i = 0; $i < $length; $i++) {
				$i && $objExcel->createSheet();
				$objExcel->setActiveSheetIndex($i);

				$objExcel->getActiveSheet()->setTitle('第' . ($i+1) . '个一千个用户');
				$objActSheet = $objExcel->getActiveSheet();

				$objActSheet->setCellValue('A1', '业主编号');
				$objActSheet->setCellValue('B1', '报修人');
				$objActSheet->setCellValue('C1', '状态');
				$objActSheet->setCellValue('D1', '报修内容');
				$objActSheet->setCellValue('E1', '报修时间');
				$objActSheet->setCellValue('F1', '报修地址');
				$objActSheet->setCellValue('G1', '评分');
				$objActSheet->setCellValue('H1', '处理人员');
				$objActSheet->setCellValue('I1', '处理人员手机号码');
				$objActSheet->setCellValue('J1', '回复内容');
				$objActSheet->setCellValue('K1', '回复时间');
				$objActSheet->setCellValue('L1', '评论内容');
				$objActSheet->setCellValue('M1', '评论时间');

				if (!empty($repair_list)) {
					$index = 2;

					$cell_list = range('A','M');
					foreach ($cell_list as $cell) {
						$objActSheet->getColumnDimension($cell)->setWidth(40);
					}

					foreach ($repair_list as $value) {
						$objActSheet->setCellValueExplicit('A' . $index, $value['usernum']);
						$objActSheet->setCellValueExplicit('B' . $index, $value['name']);

						if($value['status'] == 0){
							$status_val = '未指派';
						}elseif($value['status'] == 1){
							$status_val = '已指派';
						}elseif($value['status'] == 2){
							$status_val = '已受理';
						}elseif($value['status'] == 3){
							$status_val = '已处理';
						}elseif($value['status'] == 4){
							$status_val = '业主已评价';
						}
						$objActSheet->setCellValueExplicit('C' . $index, $status_val);
						$objActSheet->setCellValueExplicit('D' . $index, $value['content']);
						$objActSheet->setCellValueExplicit('E' . $index, date('Y-m-d H:i:s',$value['time']));
						$objActSheet->setCellValueExplicit('F' . $index, $value['address']);
						$objActSheet->setCellValueExplicit('G' . $index, $value['score']);




						$bind_id = $value['bind_id'];
						$cms_id = $value['pid'];
						if ($bind_id && $cms_id) {
							$condition['bind_id'] = $bind_id;
							$condition['pigcms_id'] = $cms_id;
							$condition['village_id'] = $this->village_id;
							$repair = D('House_village_repair_list')->getlist($condition, 1);
							$repair = $repair['repair_list'][0];

							if ($repair['status']) {
								$worker = D('House_worker')->field(true)->where(array('wid' => $repair['wid'], 'village_id' => $this->village_id))->find();
							}

							$objActSheet->setCellValueExplicit('H' . $index, $worker['name']);
							$objActSheet->setCellValueExplicit('I' . $index, $worker['phone']);
							$objActSheet->setCellValueExplicit('J' . $index, $repair['reply_content']);
							$objActSheet->setCellValueExplicit('K' . $index, $repair['reply_time']>0 ? date('Y-m-d H:i:s',$repair['reply_time']) : "");
							$objActSheet->setCellValueExplicit('L' . $index, $repair['comment']);
							$objActSheet->setCellValueExplicit('M' . $index, $repair['comment_time']>0 ? date('Y-m-d H:i:s',$repair['comment_time']) : "");
						}
						$index++;
					}
				}
				sleep(2);
				//输出
				$objWriter = new PHPExcel_Writer_Excel5($objExcel);
				ob_end_clean();
				header("Pragma: public");
				header("Expires: 0");
				header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
				header("Content-Type:application/force-download");
				header("Content-Type:application/vnd.ms-execl");
				header("Content-Type:application/octet-stream");
				header("Content-Type:application/download");
				header('Content-Disposition:attachment;filename="'.$title.'_' . date("Y-m-d h:i:sa", time()) . '.xls"');
				header("Content-Transfer-Encoding:binary");
				$objWriter->save('php://output');
				exit();
		}
	}
}