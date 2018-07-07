<?php
class House_village_doorModel extends Model{
	//	获取门禁设备的列表
	public	function	get_door($where,$page,$page_coun,$device='app'){
		if(!$where){
			return	3;
		}
		$count_arr	=	$this->where($where)->count();
		if(!$count_arr){
			return	2;
		}
		$aDoor	=	$this->field(true)->page($page,$page_coun)->where($where)->select();
		foreach($aDoor as $k=>$v){
			$aFloor	=	M('House_village_floor')->field(array('floor_name','floor_layer'))->where(array('floor_id'=>$v['floor_id']))->find();
			$aDoor[$k]['floor_name']	=	$aFloor['floor_name'];
			$aDoor[$k]['floor_layer']	=	$aFloor['floor_layer'];
		}
		if($aDoor){
			if($device == 'app'){
				$arr	=	array(
					'totalPage'	=>	ceil($count_arr/$page_coun),
					'page'		=>	intval($page),		//当前页面
					'count'		=>	count($aDoor),	//当前条数
					'door_list'	=>	isset($aDoor)?$aDoor:array(),	//顺风车列表
				);
			}else{
				import('@.ORG.merchant_page');
				$p = new Page($count_arr,$page_coun,'page');
				$arr	=	array(
					'door_list'	=>	isset($aDoor)?$aDoor:array(),
					'pagebar'	=>	$p->show(),
				);
			}
			return	$arr;
		}else{
			return	2;
		}
	}
	//	获取单个设备
	public	function	get_one_door($where){
		if(!$where){
			return	1;
		}
		$aDoor	=	$this->field(true)->where($where)->find();
		if($aDoor){
			return	$aDoor;
		}else{
			return	2;
		}
	}
	//	添加门禁设备
	public	function	add_door($data){
		if(!$data){
			return	3;
		}
		$addDoor	=	$this->data($data)->add();
		if($addDoor){
			return	$addDoor;
		}else{
			return	0;
		}
	}
	//	修改门禁设备
	public	function	save_door($where,$data){
		if(!$where || !$data){
			return	4;
		}
		$addDoor	=	$this->where($where)->data($data)->save();
		if($addDoor == 0){
			return	2;
		}else if($addDoor){
			return	99;
		}else{
			return	3;
		}
	}
	//	删除门禁设备
	public	function	del_door($where){
		if(!$where){
			return	3;
		}
		$delDoor	=	$this->where($where)->delete();
		if($delDoor){
			return	99;
		}else{
			return	2;
		}
	}
	//	查询栋的列表
	public	function	floor_list($where,$page,$page_coun){
		if(!$where){
			return	3;
		}
		$count_arr	=	M('House_village_floor')->where($where)->count();
		$aFloorList	=	M('House_village_floor')->field(true)->page($page,$page_coun)->where($where)->select();
		if($aFloorList){
			foreach($aFloorList as $k=>$v){
				$aFloorList[$k]['add_time_s']	=	date('Y-m-d H:i',$v['add_time']);
			}
			$arr	=	array(
				'totalPage'	=>	ceil($count_arr/$page_coun),
				'page'		=>	intval($page),		//当前页面
				'count'		=>	count($aFloorList),	//当前条数
				'door_list'	=>	isset($aFloorList)?$aFloorList:array(),	//顺风车列表
			);
			return	$arr;
		}else{
			return	2;
		}
	}
	//	获取门禁设备的用户
	public	function	get_door_user($where,$page,$page_coun,$device='app'){
		if(!$where){
			return	3;
		}
		$count_arr	=	M('House_village_door_user')->where($where)->count();
		if(!$count_arr){
			return	2;
		}
		$aDoor	=	M('House_village_door_user')->field(true)->page($page,$page_coun)->where($where)->select();
		foreach($aDoor as $k=>$v){
			$aFloor	=	M('House_village_user_bind')->field(array('name','address','room_addrss'))->where(array('pigcms_id'=>$v['user_id']))->find();
			$aDoor[$k]['name']	=	$aFloor['name'];
			$aDoor[$k]['address']	=	$aFloor['address'];
			$aDoor[$k]['room_addrss']	=	$aFloor['room_addrss'];
		}
		if($aDoor){
			if($device == 'app'){
				$arr	=	array(
					'totalPage'	=>	ceil($count_arr/$page_coun),
					'page'		=>	intval($page),		//当前页面
					'count'		=>	count($aDoor),	//当前条数
					'door_list'	=>	isset($aDoor)?$aDoor:array(),	//顺风车列表
				);
			}else{
				import('@.ORG.merchant_page');
				$p = new Page($count_arr,$page_coun,'page');
				$arr	=	array(
					'list'	=>	isset($aDoor)?$aDoor:array(),
					'pagebar'	=>	$p->show(),
				);
			}
			return	$arr;
		}else{
			return	2;
		}
	}
}
?>
