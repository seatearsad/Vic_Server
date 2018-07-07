<?php
/*
 * 社区首页
 *
 */
class House_indexAction extends BaseAction{
	//	获取社区基本信息管理
    public function index(){
    	$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$village_id	=	I('village_id');
		if($village_id){
			$info = ticket::get($ticket, $this->DEVICE_ID, true);
			if(empty($info)){
				$this->returnCode('20000009');
			}
			if($info['uid'] <= 10000000){
				$this->returnCode('20000010');
    		}
			$village_info = M('House_village')->field(array('qrcode_id','wx_image','wx_desc'),true)->where(array('village_id'=>$village_id))->find();
			$village_info	=	isset($village_info)?$village_info:array();
//			$village_info['long'] = floatval($village_info['long']);
//			$village_info['lat'] = floatval($village_info['lat']);
			unset($village_info['pwd']);
			if($village_info['province_id']){
				$province_id	=	$this->getCityId($village_info['province_id']);
				$village_info['default']['province_name']	=	$province_id['area_name'];
				$village_info['default']['province_id']	=	$village_info['province_id'];
			}
			if($village_info['city_id']){
				$city_id	=	$this->getCityId($village_info['city_id']);
				$village_info['default']['city_name']	=	$city_id['area_name'];
				$village_info['default']['city_id']	=	$village_info['city_id'];
			}
			if($village_info['area_id']){
				$area_id	=	$this->getCityId($village_info['area_id']);
				$village_info['default']['area_name']	=	$area_id['area_name'];
				$village_info['default']['area_id']	=	$village_info['area_id'];
			}
			if($village_info['circle_id']){
				$circle_id	=	$this->getCityId($village_info['circle_id']);
				$village_info['default']['circle_name']	=	$circle_id['area_name'];
				$village_info['default']['circle_id']	=	$village_info['circle_id'];
			}
			if(empty($village_info['default'])){
				$village_info['default']	=	(object)array();
			}
			$village_info['longs']	=	$village_info['long'];
			$village_info['lats']	=	$village_info['lat'];
			$village_info['now_city']	=	$this->config['now_city'];
			$village_info['many_city']	=	$this->config['many_city'];
			unset($village_info['long'],$village_info['lat']);
			$arr	=	array(
				'village'	=>	isset($village_info)?$village_info:(object)array(),
			);
		}else{
			$this->returnCode('30000001');
		}
		$this->returnCode(0,$arr);
    }
    //	获取省、市、区、商圈
    public	function	getProvince($stats=0){
    	$province_id	=	I('area_pid',0);
    	$aProvince_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$province_id,'is_open'=>1))->select();
    	if($stats){
			return $aProvince_id;
    	}else{
			$this->returnCode(0,$aProvince_id);
    	}
    }
    //	获取市
   // public	function	getCity(){
//		$province_id	=	I('area_pid');
//		if(empty($province_id)){
//			$this->returnCode('20090001');
//		}
//		$aCity_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$province_id,'is_open'=>1))->select();
//		$this->returnCode(0,$aCity_id);
//    }
//    //	获取区
//    public	function	getArea(){
//		$city_id	=	I('area_pid');
//		if(empty($city_id)){
//			$this->returnCode('20090002');
//		}
//		$aArea_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$city_id,'is_open'=>1))->select();
//		$this->returnCode(0,$aArea_id);
//    }
//    //	获取商圈
//    public	function	getCircle(){
//		$area_id	=	I('area_pid');
//		if(empty($area_id)){
//			$this->returnCode('20090003');
//		}
//		$aCircle_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_pid'=>$area_id,'is_open'=>1))->select();
//		$this->returnCode(0,$aCircle_id);
//    }
    //	用ID获取城市
    public	function	getCityId($area_id=0){
		if(empty($area_id)){
			return array();
		}
		$aArea_id	=	M('Area')->field(array('area_id','area_name','area_pid'))->where(array('area_id'=>$area_id,'is_open'=>1))->find();
		if(empty($aArea_id)){
			$aArea_id	=	(object)array();
		}
		return $aArea_id;
    }
    //	修改社区基本信息管理
    public	function	villageEdit(){
		$ticket = I('ticket');
    	if(empty($ticket)){
			$this->returnCode('20044013');
    	}
    	$info = ticket::get($ticket, $this->DEVICE_ID, true);
    	if(empty($info)){
			$this->returnCode('20000009');
		}
		if($info['uid'] <= 10000000){
			$this->returnCode('20000010');
    	}
    	$village_id	=	I('village_id');
    	if(empty($village_id)){
			$this->returnCode('30000001');
    	}else{
    		$arr	=	array(
    			'property_phone'	=>	I('property_phone'),		//物业联系电话
    			'property_address'	=>	I('property_address'),		//物业联系地址
    			'long'				=>	I('longs'),					//经度
    			'lat'				=>	I('lats'),					//纬度
    			'province_id'		=>	I('province_id'),			//省
    			'city_id'			=>	I('city_id'),				//市
    			'area_id'			=>	I('area_id'),				//区
    			'circle_id'			=>	I('circle_id'),				//商圈
    			'village_address'	=>	I('village_address'),		//社区地址
    			'property_price'	=>	I('property_price'),		//一平方米的物业费单价
    			'water_price'		=>	I('water_price'),			//水费单价
    			'electric_price'	=>	I('electric_price'),		//电费单价
    			'gas_price'			=>	I('gas_price'),				//燃气费单价
    			'park_price'		=>	I('park_price'),			//停车位每月价格
    			'has_custom_pay'	=>	I('has_custom_pay'),		//是否支持自定义缴费
    			'has_express_service'=>	I('has_express_service'),	//是否开启快递代收
    			'has_visitor'		=>	I('has_visitor'),			//是否开启访客登记
    			'has_slide'			=>	I('has_slide'),				//是否开启社区幻灯片
    			'has_service_slide'	=>	I('has_service_slide'),		//是否开启便民页面幻灯片 0，关闭 1，开启
    		);
    		foreach($arr as $k=>$v){
				if($v==null){
					unset($arr[$k]);
				}
    		}
    		$aVillage_id	=	M('House_village')->field(array('village_id','status'))->where(array('village_id'=>$village_id))->find();
    		if($aVillage_id){
    			if($aVillage_id['status'] == 0){
					if($arr['long'] && $arr['lat']){
						$arr['status']	=	1;
					}
    			}
				$aSave	=	M('House_village')->where(array('village_id'=>$village_id))->data($arr)->save();
    		}else{
				$this->returnCode('20090005');
    		}
    	}
    	if($aSave){
			$this->returnCode(0);
    	}else if($aSave === 0){
			$this->returnCode('20090007');
    	}else{
			$this->returnCode('20090006');
    	}
    }
    public function see_qrcode(){
    	$this->is_existence();
    	$type		=	'house';
    	$village_id	=	I('village_id');
		//判断ID是否正确，如果正确且以前生成过二维码则得到ID
		$pigcms_return = D('House_village')->get_qrcode($village_id);
		if(empty($pigcms_return)){
			$this->returnCode('20090053');
		}
		if(empty($pigcms_return['qrcode_id'])){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$village_id);
		}else{
			$qrcode_return = D('Recognition')->get_qrcode($pigcms_return['qrcode_id']);
			if($qrcode_return['error_code']){
				$this->returnCode('20090055');
			}
		}
		if($qrcode_return['error_code']){
			$this->returnCode('20090056');
		}else if($qrcode_return['qrcode'] == 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='){
			$qrcode_return = D('Recognition')->get_new_qrcode($type,$village_id);
		}

		//echo $_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house';
		if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house/'.$village_id.'.png')){
			if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house')){
				echo $_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house';
				mkdir($_SERVER['DOCUMENT_ROOT'].'/runtime/qrcode/house/',0777,true);
			}
			import('ORG.Net.Http');
			$http = new Http();
			file_put_contents('./runtime/qrcode/house/'.$village_id.'.png',Http::curlGet($qrcode_return['qrcode']));
		}
		$arr	=	array(
			'img'	=>	$this->config['site_url'].'/runtime/qrcode/house/'.$village_id.'.png',
		);
		$this->returnCode(0,$arr);
    }
}