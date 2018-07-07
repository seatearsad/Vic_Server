<?php
class DoorAction extends BaseAction{
	//	门禁设备列表
    public function door_list(){
    	$where['village_id'] = $this->house_session['village_id'];
    	$page		=	I('page',1);
    	$page_coun	=	I('page_coun',20);
    	$aDoor	=	D('House_village_door')->get_door($where,$page,$page_coun,'html');
    	$this->assign('door_list',$aDoor['door_list']);
    	$this->assign('pagebar',$aDoor['pagebar']);
		$this->display();
	}
	//	新增设备
	//public	function	door_add(){
//		if($_POST){
//			$_POST['village_id']	= $this->house_session['village_id'];
//			$add	=	D('House_village_door')->add_door($_POST);
//			if($add){
//				$this->success('新增设备成功！',U('Door/door_list'));exit;
//			}else{
//				$this->error('新增设备失败！');exit;
//			}
//		}else{
//			//	获取小区的楼层列表
//			$arr['village_id']	=	$this->house_session['village_id'];
//    		$arr['status']		=	1;
//    		$aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->where($arr)->select();
//    		$this->assign('aFloor',$aFloor);
//		}
//		$this->display();
//	}
	//	修改设备
	public	function	door_eidt(){
		$where['door_id']		=	I('door_id');
		if(empty($where['door_id'])){
			$where['door_id']	=	$_GET['door_id'];
		}
		if(empty($where)){
			$this->error('设备未找到');exit;
		}
		if($_POST){
			$data['floor_id']	=	$_POST['floor_id'];
			$data['door_status']=	$_POST['door_status'];
			$data['all_status']	=	$_POST['all_status'];
			$aSave	=	D('House_village_door')->save_door($where,$data);
			if($aSave == 99){
				$this->success('修改设备成功！',U('door_list'));exit;
			}else{
				$this->error('修改设备失败！');exit;
			}
		}else{
			$aDoor	=	D('House_village_door')->get_one_door($where);
			$this->assign('aDoor',$aDoor);
			//	获取小区的楼层列表
			$arr['village_id']	=	$this->house_session['village_id'];
    		$arr['status']		=	1;
    		$aFloor	=	M('House_village_floor')->field(array('floor_id','floor_name','floor_layer'))->where($arr)->select();
    		$this->assign('aFloor',$aFloor);
    		$this->display();
		}
	}
	//	查看设备的用户
	public	function	door_user(){
		$where['door_fid']		=	I('door_id');
		$page		=	I('page',1);
		$page_coun		=	I('page_coun',10);
		if(empty($where)){
			$this->error('设备未找到');exit;
		}
		$aDoor	=	D('House_village_door')->get_door_user($where,$page,$page_coun,'html');
		$this->assign('aDoor',$aDoor['list']);
		$this->assign('pagebar',$aDoor['pagebar']);
		$this->assign('door_id',$where['door_fid']);
		$this->display();
	}
	//	查看设备的用户
	public	function	door_user_add(){
		//	查询楼的门禁ID
		$where['door_id']		=	I('door_id');
		$floor_id	=	M('House_village_door')->field(array('floor_id'))->where($where)->find();
		//	查询属于这个门禁的用户
		$where_user	=	array(
			'village_id'	=>	$this->house_session['village_id'],
			'floor_id'	=>	$floor_id['floor_id'],
		);
		$aUser	=	M('House_village_user_bind')->field(array('pigcms_id','name','address','uid'))->where($where_user)->select();
		//	循环这些用户进行匹配
		foreach($aUser as $k=>$v){
			$aDoorUser	=	M('House_village_door_user')->field(array('pigcms_id'))->where(array('user_id'=>$v['pigcms_id'],'door_fid'=>$where['door_id']))->find();
			if($aDoorUser){
				unset($aUser[$k]);
				continue;
			}else{
				$avatar	=	M('User')->field(array('avatar'))->where(array('uid'=>$v['uid']))->find();
				if($avatar){
					$aUser[$k]['avatar']	=	$avatar['avatar'];
				}else{
					$aUser[$k]['avatar']	=	$avatar['avatar'];
				}
			}
		}
		$this->assign('aUser',$aUser);
		$this->assign('door_id',$where['door_id']);
		$this->display();
	}
	//	门禁设备新增用户
	public	function	door_add_user(){
		$pigId	=	$_POST['pigcms'];
		$arr['door_fid']	=	$_POST['door_id'];
		if(empty($pigId)){
			$this->error('请选择用户');exit;
		}
		if(empty($arr)){
			$this->error('门禁ID为空');exit;
		}
		$aId	=	explode(',',$pigId);
		foreach($aId as $k=>$v){
			$arr['user_id']	=	$v;
			$aFind	=	M('House_village_door_user')->field(array('pigcms_id'))->where($arr)->find();
			if($aFind){
				continue;
			}
			$arr['status']	=	1;
			$arr['start_time']	=	time();
			$add	=	M('House_village_door_user')->data($arr)->add();
			if(empty($add)){
				$this->error('添加用户失败');exit;
			}
		}
		$this->success('新增设备成功！',U('door_user',array('door_id'=>$arr['door_fid'])));exit;
	}
	//	门禁设备新增用户
	public	function	door_del_user(){
		$where['pigcms_id']	=	$_GET['pigcms_id'];
		if(empty($where)){
			$this->error('请选择要删除的用户');exit;
		}
		$aDelete	=	M('House_village_door_user')->where($where)->delete();
		if(empty($aDelete)){
			$this->error('删除用户失败');exit;
		}else{
			$this->success('删除用户成功');exit;
		}
	}
	//	门禁设备修改用户
	public	function	door_eidt_user(){
		$door_id		=	$_GET['door_id'];
		$where['pigcms_id']	=	$_GET['pigcms_id'];
		if($_POST){
			$data['start_time']	=	strtotime($_POST['start_time']);
			$data['end_time']	=	strtotime($_POST['end_time'])+86399;
			$data['status']	=	$_POST['status'];
			$aSave	=	M('House_village_door_user')->where($where)->data($data)->save();
			if($aSave){
				$this->success('修改用户成功',U('door_user',array('door_id'=>$door_id)));exit;
			}else{
				$this->error('修改用户失败');exit;
			}
		}else{
			if(empty($where)){
				$this->error('请选择要修改的用户');exit;
			}
			$aFind	=	M('House_village_door_user')->field(true)->where($where)->find();
			if(empty($aFind)){
				$this->error('未找到这个用户');exit;
			}
			$this->assign('aFind',$aFind);
			$this->assign('door_id',$door_id);
			$this->display();
		}
	}
}