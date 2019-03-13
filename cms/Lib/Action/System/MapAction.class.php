<?php
/*
 * 地图处理
 *
 * @  Writers    Jaty
 * @  BuildTime  2014/11/06 15:07
 * 
 */
class MapAction extends BaseAction{
	public function frame_map(){
		$long_lat = $_GET['long_lat'];
		if(!$long_lat){
			$long_lat = '-123.343033,48.430168';
		}
		$this->assign('long_lat',$long_lat);
		$this->display();
	}
}