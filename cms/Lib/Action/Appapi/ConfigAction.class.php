<?php
class ConfigAction  extends BaseAction{
    //返回小猪cms O2O系统所有配置
    public  function    index(){
        $config    =   $this->config;
        $city_id   =   I('area_name');
        $app_type   =   I('app_type');
        $app_version    =   I('app_version');
        if($city_id){
            $city    =   $this->cityMatching($city_id);
        }else{
            $city    =   $this->nowCity($config['now_city']);
        }
        if($app_version){
                $arr['city']    =   $city;
        }else{
            if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']),'android')){
                $arr['city']    =   $city;
            }else{
                $arr['city'][]    =   $city;
            }
        }
        if($config['many_city']){
			$arr['many_city']	=	$config['many_city'];
        }else{
			$arr['many_city']	=	0;
        }
        $arr['config']['group_alias_name']      =   $this->config['group_alias_name'];
        $arr['config']['meal_alias_name']      =   $this->config['meal_alias_name'];
		$arr['config']['shop_alias_name']      =   $this->config['shop_alias_name'];
        $arr['config']['site_logo']      =   $this->config['site_logo'];
        $arr['pay_share']['pay_weixinapp_key']    =    isset($this->config['pay_weixinapp_key']) ? $this->config['pay_weixinapp_key']: null;
        $arr['pay_share']['pay_weixinapp_appsecret']    =   isset($this->config['pay_weixinapp_appsecret']) ? $this->config['pay_weixinapp_appsecret'] : null;
        $arr['pay_share']['pay_weixinapp_appid']    =   isset($this->config['pay_weixinapp_appid'])?$this->config['pay_weixinapp_appid']:null;
        $arr['pay_share']['pay_weixinapp_mchid']    =   isset($this->config['pay_weixinapp_mchid'])?$this->config['pay_weixinapp_mchid']:null;
        $appConfig  =   D('Appapi_app_config')->field(true)->select();
        foreach($appConfig as $k=>$v){
            if($v['var'] == 'ios_version_desc'){
                $arr['appConfig'][$v['var']]   =   $v['value'];
            }else{
                $arr['appConfig'][$v['var']]   =   nl2br($v['value']);
            }
        }
        if(empty($arr)){
            $this->returnCode('20000002');
        }else if(empty($arr['appConfig'])){
            $arr['appConfig']   =   array();
        }else if(empty($arr['config'])){
            $arr['config']      =   array();
        }else if(empty($arr['city'])){
            $arr['city']    =   null;
        }
        if(empty($arr['appConfig'])){
            $arr['appConfig'] = (Object)array();
        }
        $arr['house']	=	array(
			'house_door'	=>	$this->config['house_door'],
			'house_open'	=>	$this->config['house_open'],
        );
        $arr['config']['pay_alipay_app_open'] = $this->config['pay_alipay_app_open'];
        $arr['config']['pay_alipay_app_pid'] =  isset($this->config['pay_alipay_app_pid']) ? $this->config['pay_alipay_app_pid']: null;
        $arr['config']['pay_alipay_app_count'] =  isset($this->config['pay_alipay_app_count']) ? $this->config['pay_alipay_app_count']: null;
        if($app_type==1){
            $arr['config']['pay_alipay_app_private_key'] =  isset($this->config['pay_alipay_app_private_key_ios']) ? $this->config['pay_alipay_app_private_key_ios']: null;
        }elseif($app_type==2){
            $arr['config']['pay_alipay_app_private_key'] =  isset($this->config['pay_alipay_app_private_key_android']) ? $this->config['pay_alipay_app_private_key_android']: null;
        }
        $arr['config']['pay_alipay_app_public_key'] =  isset($this->config['pay_alipay_app_public_key']) ? $this->config['pay_alipay_app_public_key']: null;

		$menu_category = M('Home_menu_category')->field('cat_id')->where(array('cat_key'=>'app_footer'))->find();
        if($menu_category){
            $footer_menu_list = M('Home_menu')->where(array('status'=>'1','cat_id'=>$menu_category['cat_id']))->order('`sort` DESC,`id` ASC')->limit(4)->select();
        }
        $arr['url_check_arr'] = array(array('key'=>'c=My'),array('key'=>'c=Shop&a=order_detail'),array('key'=>'c=Shop&a=status'),array('key'=>'c=Takeout&a=order_detail'),array('key'=>'c=Food&a=order_detail'));

        if(count($footer_menu_list)<4){
            $arr['footer_menu_list']=array();
        }else{
            $footer_menu=array();

            foreach ($footer_menu_list as $key=>$v) {
				$url = parse_url($v['url']);

				if(($key!=3&&$key!=0)&&(empty($url['query'])||strpos($v['url'],'Home')||strpos($v['url'],'My'))){
					$arr['footer_menu_list']=array();
					//break;
				}
                $tmp['name'] = $v['name'];
				if($key==3||$key==0){

					$tmp['url'] = '';
				}else{

					$tmp['url'] = $v['url'];
				}
                $tmp['pic_path'] = C('config.site_url').'/upload/slider/'.$v['pic_path'];
                $tmp['hover_pic_path'] = C('config.site_url').'/upload/slider/'.$v['hover_pic_path'];
                $footer_menu[] = $tmp;
            }

            $arr['footer_menu_list']=$footer_menu;
        }
		$head_adver = D('Adver')->get_adver_by_key('app_index_top',1);
        $content_type = $this->config['guess_content_type'];
		$arr['is_app_adver'] = $head_adver ? true : false;
		$arr['home_like_type'] = $content_type;

        $this->returnCode(0,$arr);
    }
    # 景区配置
    public function scenic_index(){
		$config    =   $this->config;
        if($config['many_city']){
			$arr['many_city']	=	$config['many_city'];
        }else{
			$arr['many_city']	=	0;
        }
        $arr['scenic_now_city'] = $config['scenic_now_city'];
    }

	/**
	 * 获取微信js配置信息
	 */
	public function wx_config(){
		$share = new WechatShare($this->config, '');
		$arr = $share->get_wx_config();
		
		if($_POST['work'] == 'storestaff'){
			switch($_POST['page']){
				case 'index':
					$arr['share'] = array(
						'title'=>'店员中心',
						'content'=>'微信版店员中心-'.$this->config['site_name'],
						'url'=>$_POST['location_url']
					);
					break;
			}
		}
		if($_POST['work'] == 'deliver'){
			switch($_POST['page']){
				case 'index':
				case 'tongji':
				case 'info':
					$arr['share'] = array(
						'title'=>'配送员中心',
						'content'=>'微信版配送员中心-'.$this->config['site_name'],
						'url'=>$_POST['location_url']
					);
					break;
			}
		}
		
		if($arr['share']){
			$arr['share']['image'] = $this->config['site_url'].'/packapp/'.$_POST['work'].'/logo.png';
		}
		
		$this->returnCode(0, $arr);
	}
}
?>