<?php
/*
 * 地图处理
 *
 */
class MapAction extends BaseAction{
	public function suggestion(){
		if($_POST['area_name']){
			$area_name = $_POST['area_name'];
		}else{
			$now_city = D('Area')->get_area_by_areaId($this->config['now_city']);
			$area_name = $now_city['area_name'];
		}
		$query = $_POST['query'] ? $_POST['query'] : $_GET['query'];
		$url = 'http://api.map.baidu.com/place/v2/suggestion?query='.urlencode($query).'&region='.urlencode($area_name).'&ak=4c1bb2055e24296bbaef36574877b4e2&output=json';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if($result['status'] == 0){
				$return = array();
				if($result['result']){
					foreach($result['result'] as $value){
						if(empty($value['location']['lng'])){
							continue;
						}
						$return[] = array(
							'name'=>$value['name'],
							'lat'=>$value['location']['lat'],
							'long'=>$value['location']['lng'],
							'lng'=>$value['location']['lng'],
							'adress'=>$value['city'].$value['district'].$value['name'],
							'address'=>$value['city'].$value['district'].$value['name']
						);
					}
				}
				$this->returnCode(0,$return);
			}else{
				$this->returnCode('20000002');
			}
		}else{
			$this->returnCode('20000002');
		}
	}
	/*百度经纬度转火星经纬度*/
	public function baiduToGcj02(){
		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$location2 = $longlat_class->baiduToGcj02($_POST['baidu_lat'], $_POST['baidu_lng']);
		if($location2){
			$this->returnCode(0,$location2);
		}else{
			$this->returnCode('20000002');
		}
	}
}