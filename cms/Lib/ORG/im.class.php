<?php
/*
 * 即时聊天的类
 *
 */
class im{
    public function create(){
		$array = parse_url(C('config.site_url'));
		$data = array(
				'domain' => $array['host'],
				'label' => '',
				'from' => '2',
				'wx_app_id' => C('config.wechat_appid'),
				'wx_app_secret' => C('config.wechat_appsecret'),
				'activity_url' => C('config.site_url') . '/wap.php?g=Wap&c=Api&a=activity',		//活动链接
				'my_url' => C('config.site_url') . '/wap.php?g=Wap&c=Api&a=my',		//活动链接
				'msg_tip_url' => C('config.site_url') . '/wap.php?g=Wap&c=Api&a=index',		//消息提醒链接
		);

		import('ORG.Net.Http');
		$http = new Http();

		$return = Http::curlPost('http://im-link.weihubao.com/api/app_create.php', $data);

		if($return['err_code']){
			exit(json_encode(array('error_code' => true, 'msg' => $return['err_msg'])));
		} else {
			if (D('Config')->where("`name`='im_appid'")->find()) {
				D('Config')->where("`name`='im_appid'")->save(array('value' => $return['app_id']));
			} else {
				D('Config')->add(array('name' => 'im_appid', 'value' => $return['app_id'], 'gid' => 0, 'status' => 1));
			}
			if (D('Config')->where("`name`='im_appkey'")->find()) {
				D('Config')->where("`name`='im_appkey'")->save(array('value' => $return['app_key']));
			} else {
				D('Config')->add(array('name' => 'im_appkey', 'value' => $return['app_key'], 'gid' => 0, 'status' => 1));
			}
			S('config',null);
			exit(json_encode(array('error_code' => false, 'msg' => '获取成功')));
		}
    }
    # 天气获取接口
    public function weather($data){
		import('ORG.Net.Http');
		$http = new Http();
		$url = "http://op.juhe.cn/onebox/weather/query";
		$params = array(
		      'cityname' => $data,//要查询的城市，如：温州、上海、北京
		      'key' => 'fd3a288eaed0f47acd375a91bed35df0',//应用APPKEY(应用详细页查询)
		      'dtype' => 'json',//返回数据的格式,xml或json，默认json
		);
		$return = Http::curlPost('http://op.juhe.cn/onebox/weather/query',$params);
		if($return['error_code']){
			return '';
		} else {
			$result	=	$return['result']['data']['realtime'];
			$results	=	$return['result']['data']['weather'][0]['info'];
			$arr	=	array(
				'info'	=>	$result['weather']['info'],	//天气
				'img'	=>	$result['weather']['img'],	//天气
				'min'	=>	$results['night'][2],	//最小温度
				'max'	=>	$results['day'][2],	//最大温度
				'city_name'	=>	$result['city_name'],	//城市
				'direct'	=>	$result['wind']['direct'],	//风向
				'power'	=>	$result['wind']['power'],		//风级
			);
			return	$arr;
		}
    }
	public function saveLocation($openid,$long,$lat){
		$data = array(
			'im_appid'  => C('config.im_appid'),
			'openid'    => $openid,
			'long' 		=> $long,
			'lat' 	    => $lat,
			'key' 	    => $lat,
		);
		$data['key'] = $this->get_im_encrypt_key($data);
		import('ORG.Net.Http');
		$http = new Http();
		Http::curlPost('http://im-link.weihubao.com/api/app_location.php', $data);
	}
	protected function get_im_encrypt_key($array, $app_key){
		$new_arr = array();
		ksort($array);
		foreach($array as $key=>$value){
			$new_arr[] = $key.'='.$value;
		}
		$new_arr[] = 'app_key='.$app_key;

		$string = implode('&',$new_arr);
		return md5($string);
	}
}
?>