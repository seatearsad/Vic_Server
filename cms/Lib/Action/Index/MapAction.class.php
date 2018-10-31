<?php
/*
 * 地图处理
 *
 */
class MapAction extends BaseAction{
	public function suggestion(){
		header("Content-type: application/json");
		$city_id = isset($_GET['city_id']) ? intval($_GET['city_id']) : $this->config['now_city'];
		$now_city = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
		$this->assign('city_name',$now_city['area_name']);
		//$url = 'http://api.map.baidu.com/place/v2/suggestion?query='.urlencode($_GET['query']).'&region='.urlencode($now_city['area_name']).'&ak=4c1bb2055e24296bbaef36574877b4e2&output=json';
//		$url = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=48.43016873926502,-123.34303379055086&rankby=distance&keyword='.urlencode($_GET['query']).'&key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&language=zh-CN';
        $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input='.urlencode($_GET['query']).'&types=address&key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&location=48.43016873926502,-123.34303379055086&radius=50000&language=en';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if($result['status'] == 'OK' && count($result['predictions']) > 0){
				$return = [];
				foreach($result['predictions'] as $v) {
				    $place_url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid='.$v['place_id'].'&key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&fields=geometry&language=en';
                    $place = $http->curlGet($place_url);
                    $place = json_decode($place,true);
				    $return[] = [
						'name' => $v['description'],
						'lat' => $place['result']['geometry']['location']['lat'],
						'long' => $place['result']['geometry']['location']['lng'],
						'address' => $v['description']
					];
				}
				exit(json_encode(array('status'=>1,'result'=>$return)));	
			}else{
				exit(json_encode(array('status'=>2,'result'=>'没有查找到内容')));
			}
			/*
			if($result['status'] == 0 && $result['result']){
				$return = array();
				foreach($result['result'] as $value){
					if (!isset($value['location'])) continue; 
					$return[] = array(
						'name'=>$value['name'],
						'lat'=>$value['location']['lat'],
						'long'=>$value['location']['lng'],
						'address'=>$value['city'].$value['district'].$value['name']
					);
				}
				exit(json_encode(array('status'=>1,'result'=>$return)));
			}else{
				exit(json_encode(array('status'=>2,'result'=>'没有查找到内容')));
			}*/
		}else{
			exit(json_encode(array('status'=>0,'result'=>'获取失败')));
		}
	}

	public function geocoderGoogle($lng, $lat){
		$url = 'http://maps.google.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&language=zh-CN&sensor=false';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if($result['status'] == 'OK' && count($result['results']) > 0){
				$return = [];
				foreach($result['results'] as $v) {
					$return[] = [
						'name' => $v['address_components'][0]['long_name'],
						'lat' => $v['geometry']['location']['lat'],
						'long' => $v['geometry']['location']['lng'],
						'address' => $v['formatted_address']
					];
				}
				exit(json_encode(array('status'=>1,'result'=>$return)));	
			}
		}
		exit(json_encode(array('status'=>2,'result'=>'没有查找到内容')));

	}

	public function gpsToBaidu(){
		if(empty($_POST)){
			$input_post = file_get_contents('php://input');
			$_POST = json_decode($input_post,true);
		}
		header("Content-type: application/json");
		import('@.ORG.longlat');
		$longlat_class = new longlat();
		$location2 = $longlat_class->gpsToBaidu($_POST['lat'],$_POST['lng']);//转换腾讯坐标到百度坐标
		if($_POST['geocoder']){
			$location2['name'] = $this->geocoder($location2['lng'],$location2['lat']);
		}
		exit(json_encode($location2));
	}
	public function geocoder($lng,$lat){
		$url = 'http://api.map.baidu.com/geocoder/v2/?location='.$lat.','.$lng.'&output=json&pois=1&ak=4c1bb2055e24296bbaef36574877b4e2';
		import('ORG.Net.Http');
		$http = new Http();
		$result = $http->curlGet($url);
		if($result){
			$result = json_decode($result,true);
			if(!empty($result['result']['pois'])){
				return $result['result']['pois'][0]['name'];
			}else{
				return $result['result']['addressComponent']['street'].$result['result']['addressComponent']['street_number'];
			}
		}else{
			return '';
		}
	}
}