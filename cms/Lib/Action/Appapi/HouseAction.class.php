<?php
class HouseAction  extends BaseAction{
	//	小区列表
	public function index(){
		$long_lat	=	array(
				'lat'	=>	I('lat',0),
				'long'	=>	I('long',0),
		);
		$city	=	I('city',C('config.now_city'));
		C('config.now_city',$city);
		$keyword	=	I('keyword');
		$_GET['page']	=	I('page',1);
		$House_village	=	D('House_village');
		//	查询社区列表
		$aHouseVillageList	=	$House_village->wap_get_list($long_lat,$keyword);
		$arr	=	array();
		if($aHouseVillageList){
			foreach($aHouseVillageList['village_list'] as $k=>$v){
				$arr['village_list'][$k]['village_id']	=	$v['village_id'];
				$arr['village_list'][$k]['village_name']	=	$v['village_name'];
				$arr['village_list'][$k]['village_address']	=	$v['village_address'];
				$arr['village_list'][$k]['range']	=	$v['range'];
			}
			$arr['totalPage']	=	$aHouseVillageList['totalPage'];
			$arr['village_count']	=	$aHouseVillageList['village_count'];
		}else{
			$arr['village_list']	=	array();
			$arr['totalPage']		=	0;
			$arr['village_count']	=	0;
		}
		if($keyword){
			$arr['village_me']	=	array();
		}else{
			$ticket	=	I('ticket');
			$info = ticket::get($ticket,$this->DEVICE_ID,true);
			if($info){
				//	查询我居住的社区列表
				$bindList	=	$House_village->get_bind_list($info['uid'],'',true,$long_lat);
				if($bindList){
					foreach($bindList as $k=>$v){
						$arr['village_me'][$k]['village_id']	=	$v['village_id'];
						$arr['village_me'][$k]['village_name']	=	$v['village_name'];
						$arr['village_me'][$k]['village_address']	=	$v['village_address'];
						$arr['village_me'][$k]['range']			=	$v['range'];
					}
				}else{
					$arr['village_me']	=	array();
				}
			}else{
				$arr['village_me']	=	array();
			}
		}
		$this->returnCode(0,$arr);
	}
	//	我的小区列表
	public function bind_list(){
		$long_lat	=	array(
				'lat'	=>	I('lat',0),
				'long'	=>	I('long',0),
		);
		$House_village	=	D('House_village');
		$ticket	=	I('ticket');
		$info = ticket::get($ticket, $this->DEVICE_ID, true);
		$bindList	=	$House_village->get_bind_list($info['uid'],'',true,$long_lat);
		$arr	=	array();
		if($bindList){
			foreach($bindList as $k=>$v){
				$arr[$k]['village_id']	=	$v['village_id'];
				$arr[$k]['village_name']	=	$v['village_name'];
				$arr[$k]['village_address']	=	$v['village_address'];
				$arr[$k]['range']		=	$v['range'];
			}
		}
		$this->returnCode(0,$arr);
	}
	//	我的小区里的房子
	public function village_list(){
		$ticket	=	I('ticket');
		if(empty($ticket)){
			$this->returnCode('20044013');
		}
		$info = ticket::get($ticket,$this->DEVICE_ID,true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		$village_id	=	I('village_id');
		if(empty($village_id)){
			$this->returnCode('30000001');
		}
		$this->get_village($village_id);
		$House_village	=	D('House_village_user_bind');
		$village_list	=	$House_village->get_user_bind_list($info['uid'],$village_id);
		$arr	=	array();
		if($village_list){
			foreach($village_list as $k=>$v){
				$arr[$k]	=	array(
						'pigcms_id'	=>	$v['pigcms_id'],
						'address'	=>	$v['address'],
						'usernum'	=>	$v['usernum']
				);
			}
		}else{
			$village_list	=	$House_village->get_family_user_bind_list($info['uid'],$village_id);
			foreach($village_list as $k=>$v){
				$arr[$k]	=	array(
						'pigcms_id'	=>	$v['pigcms_id'],
						'address'	=>	$v['address'],
						'usernum'	=>	$v['usernum'],
						'parent_id' =>$v['parent_id']
				);
			}
		}
		$this->returnCode(0,$arr);
	}
	//	社区--首页
	public function village_index(){
		$village_id	=	I('village_id');
		$pigcms_type	=	I('pigcms_type',1);
		if(empty($village_id)){
			$this->returnCode('30000001');
		}

		$database_shequ_slider = D('House_village_slider');
		$now_village = $this->get_village($village_id);
		$has_slide = $this->getHasConfig($now_village['village_id'],'has_slide');
		if($has_slide){
			//幻灯片
			$where['village_id'] = $now_village['village_id'];
			$where['status'] = '1';
			$where['type'] = '0';
			$slider_list = $database_shequ_slider->where($where)->order('`sort` DESC,`id` ASC')->select();
			if($slider_list){
				foreach($slider_list as $k=>$v){
					$slider[$k]['id'] 	= $v['id'];
					$slider[$k]['name'] = $v['name'];
					$slider[$k]['pic'] = $this->config['site_url'].'/upload/slider/'.$v['pic'];
					$slider[$k]['url'] = $v['url'];
				}
			}else{
				$slider	=	array();
			}
		}else{
			$slider	=	array();
		}
		$arr['slider']	=	$slider;
		//找到模板排序
		$displayArr = explode(' ',$this->config['house_display']);
		$displayTplArr = array(
				1=>'village_index_news',
				2=>'village_index_pay',
				3=>'village_index_group',
				4=>'village_index_meal',
				5=>'village_index_appoint',
				6=>'village_index_bbs',
		);
		$displayIncludeTplArr = array();
		foreach($displayArr as $value){
			if($value>=1 && $value<=6){
				$displayIncludeTplArr[] = $displayTplArr[$value];
			}
		}
		$arr['sort']	=	$displayArr;
		$long_lat	=	array(
				'lat'	=>	I('lat'),
				'long'	=>	I('long'),
		);
		$user_long_lat = D('User_long_lat')->getLocation(1,0,$long_lat);
		foreach($displayIncludeTplArr as $v){
			if($v == 'village_index_news'){
				$news = D('House_village_news')->get_limit_list($now_village['village_id'],2);
				if($news){
					foreach($news as $kk=>$vv){
						$new_url	=	$this->config['site_url'].U('Wap/House/village_news',array('village_id'=>$village_id,'news_id'=>$vv['news_id']));
						if($pigcms_type == 1){
							$new_url	=	str_replace('/appapi.php?','/wap.php?',$new_url);
						}else if($pigcms_type == 2){
							$new_url	=	str_replace('/appapi.php?','/wap_house.php?',$new_url);
						}
						unset($news[$kk]['status'],$news[$kk]['is_hot'],$news[$kk]['cat_id'],$news[$kk]['is_notice'],$news[$kk]['content']);
						$news[$kk]['add_time_s']	=	date('Y-m-d H:i',$vv['add_time']);
						$news[$kk]['url']	=	$new_url;
					}
					$arr['news']['list']	=	$news;
					$news_url	=	$this->config['site_url'].U('Wap/House/village_newslist',array('village_id'=>$village_id));
					$news_url	=	str_replace('/appapi.php?','/wap_house.php?',$news_url);
					if($pigcms_type == 1){
						$news_url	=	str_replace('/appapi.php?','/wap.php?',$news_url);
					}else if($pigcms_type == 2){
						$news_url	=	str_replace('/appapi.php?','/wap_house.php?',$news_url);
					}
					$arr['news']['news_url']	=	$news_url;
				}else{
					$arr['news']['list']	=	array();
					$arr['news']['news_url']	=	'';
				}
				$arr['news']['sort']	=	'1';
			}
			if($v == 'village_index_pay'){
				if($this->config['house_bbsservice_limit']){
					$category	= D('House_service_category')->getIndexCatList($now_village['village_id'],$this->config['house_bbsservice_limit']);
				}else{
					$category	= D('House_service_category')->getIndexCatList($now_village['village_id'],16);
				}
				if($category){
					foreach($category as $kk=>$vv){
						$cat_url	=	substr($vv['cat_url'],0,3);
						if($cat_url != 'htt'){
							$category[$kk]['cat_url']	=	$this->config['site_url'].$vv['cat_url'];
							if($pigcms_type == 1){
								$category[$kk]['cat_url']	=	str_replace('/appapi.php?','/wap.php?',$category[$kk]['cat_url']);
								$category[$kk]['cat_url']	=	str_replace('Appapi','Wap',$category[$kk]['cat_url']);
							}else if($pigcms_type == 2){
								$category[$kk]['cat_url']	=	str_replace('/appapi.php?','/wap_house.php?',$category[$kk]['cat_url']);
								$category[$kk]['cat_url']	=	str_replace('Appapi','Wap',$category[$kk]['cat_url']);
							}

						}
					}
					$arr['category']['list']	=	$category;
				}else{
					$arr['category']['list']	=	array();
				}
				$arr['category']['sort']	=	'2';
			}
			if($v == 'village_index_group'){
				$group = D('House_village_group')->get_limit_list($now_village['village_id'],3,$user_long_lat);
				if($group){
					foreach($group as $kk=>$vv){
						$group_url	=	$this->config['site_url'].$vv['url'];
						if($pigcms_type == 1){
							$group_url	=	str_replace('/appapi.php?','/wap.php?',$group_url);
							$group_url	=	str_replace('Appapi','Wap',$group_url);
						}else if($pigcms_type == 2){
							$group_url	=	str_replace('/appapi.php?','/wap_house.php?',$group_url);
							$group_url	=	str_replace('Appapi','Wap',$group_url);
						}
						$group_url	=	str_replace('g=Appapi','g=Wap',$group_url);
						$group_list[$kk]	=	array(
								'group_id'	=>	$vv['group_id'],
								'group_name'	=>	$vv['group_name'],
								'prefix_title'	=>	$vv['prefix_title'],
								'price'			=>	$vv['price'],
								'wx_cheap'		=>	$vv['wx_cheap'],
								'sale_count'	=>	$vv['sale_count'],
								'list_pic'		=>	$vv['list_pic'],
								'range'			=>	isset($vv['range'])?$vv['range']:'',
								'url'			=>	$group_url,
								'pin_num'			=>	$vv['pin_num'],
						);
						if($vv['tuan_type'] == 2){
							$group_list[$kk]['name']	=	$vv['s_name'];
						}else{
							$group_list[$kk]['name']	=	$vv['name'];
						}
					}
					$arr['group']['list']		=	$group_list;
					$list_url	=	$this->config['site_url'].U('Wap/House/village_grouplist',array('village_id'=>$village_id));
					if($pigcms_type == 1){
						$list_url	=	str_replace('/appapi.php?','/wap.php?',$list_url);
						$list_url	=	str_replace('Appapi','Wap',$list_url);
					}else if($pigcms_type == 2){
						$list_url	=	str_replace('/appapi.php?','/wap_house.php?',$list_url);
						$list_url	=	str_replace('Appapi','Wap',$list_url);
					}
					$arr['group']['list_url']	=	$list_url;
				}else{
					$arr['group']['list']	=	array();
					$arr['group']['list_url']	=	'';
				}
				$arr['group']['sort']	=	'3';
			}
			if($v == 'village_index_meal'){
				$meal = D('House_village_meal')->get_limit_list($now_village['village_id'],3,$user_long_lat);
				if($meal){
					foreach($meal as $kk=>$vv){
						$meal_url	=	$this->config['site_url'].$vv['wap_url'];
						if($pigcms_type == 1){
							$meal_url	=	str_replace('/appapi.php?','/wap.php?',$meal_url);
							$meal_url	=	str_replace('Appapi','Wap',$meal_url);
						}else if($pigcms_type == 2){
							$meal_url	=	str_replace('/appapi.php?','/wap_house.php?',$meal_url);
							$meal_url	=	str_replace('Appapi','Wap',$meal_url);
						}
						$meal_url	=	str_replace('g=Appapi','g=Wap',$meal_url);
						$meal_list[$kk]	=	array(
								'name'			=>	$vv['name'],
								'adress'		=>	$vv['adress'],
								'mean_money'	=>	$vv['mean_money'],
								'sale_count'	=>	$vv['sale_count'],
								'store_type'	=>	$vv['store_type'],
								'list_pic'		=>	$vv['list_pic'],
								'range'			=>	isset($vv['range'])?$vv['range']:'',
								'state'			=>	isset($vv['state'])?$vv['state']:'',
								'wap_url'		=>	$meal_url,
						);
					}
					$arr['meal']['list']	=	$meal_list;
					$list_url	=	$this->config['site_url'].U('village_meallist',array('village_id'=>$village_id));
					if($pigcms_type == 1){
						$list_url	=	str_replace('/appapi.php?','/wap.php?',$list_url);
						$list_url	=	str_replace('Appapi','Wap',$list_url);
					}else if($pigcms_type == 2){
						$list_url	=	str_replace('/appapi.php?','/wap_house.php?',$list_url);
						$list_url	=	str_replace('Appapi','Wap',$list_url);
					}
					$arr['meal']['list_url']=	$list_url;
				}else{
					$arr['meal']['list']	=	array();
					$arr['meal']['list_url']	=	'';
				}
				$arr['meal']['sort']	=	'4';
			}
			if($v == 'village_index_appoint'){
				$appoint = D('House_village_appoint')->get_limit_list($now_village['village_id'],3,$user_long_lat);
				if($appoint){
					foreach($appoint as $kk=>$vv){
						$appoint_url	=	$this->config['site_url'].$vv['url'];
						if($pigcms_type == 1){
							$appoint_url	=	str_replace('appapi.php?','wap.php?',$appoint_url);
							$appoint_url	=	str_replace('Appapi','Wap',$appoint_url);
						}else if($pigcms_type == 2){
							$appoint_url	=	str_replace('appapi.php?','wap_house.php?',$appoint_url);
							$appoint_url	=	str_replace('Appapi','Wap',$appoint_url);
						}
						$appoint_url	=	str_replace('g=Appapi','g=Wap',$appoint_url);
						$appoint_list[$kk]	=	array(
								'appoint_name'	=>	$vv['appoint_name'],
								'payment_money'	=>	$vv['payment_money'],
								'appoint_content'	=>	$vv['appoint_content'],
								'appoint_sum'	=>	$vv['appoint_sum'],
								'list_pic'		=>	$vv['list_pic'],
								'range'			=>	isset($vv['range'])?$vv['range']:'',
								'appoint_status'=>	$vv['appoint_status'],
								'url'	=>	$appoint_url,
						);
					}
					$arr['appoint']['list']	=	$appoint_list;
					$list_url	=	$this->config['site_url'].U('village_appointlist',array('village_id'=>$village_id));
					if($pigcms_type == 1){
						$list_url	=	str_replace('/appapi.php?','/wap.php?',$list_url);
						$list_url	=	str_replace('Appapi','Wap',$list_url);
					}else if($pigcms_type == 2){
						$list_url	=	str_replace('/appapi.php?','/wap_house.php?',$list_url);
						$list_url	=	str_replace('Appapi','Wap',$list_url);
					}
					$arr['appoint']['list_url']	=	$list_url;
				}else{
					$arr['appoint']['list']	=	array();
					$arr['appoint']['list_url']	=	'';
				}
				$arr['appoint']['sort']	=	'5';
			}
			if($v == 'village_index_bbs'){
				$bbs = D('Bbs')->bbsHotAricle('house',$now_village['village_id'],$this->config['house_bbsarticle_limit']);
				if($bbs){
					foreach($bbs['aricle'] as $kk=>$vv){
						if($pigcms_type == 1){
							$bbs_url	=	str_replace('wap.php?','wap.php?',$vv['url']);
							$bbs_url	=	str_replace('Appapi','Wap',$vv['url']);
						}else if($pigcms_type == 2){
							$bbs_url	=	str_replace('wap.php?','wap_house.php?',$vv['url']);
							$bbs_url	=	str_replace('Appapi','Wap',$vv['url']);
						}
						$aricle[$kk]	=	array(
								'aricle_id'	=>	$vv['aricle_id'],
								'aricle_img'	=>	$vv['aricle_img'],
								'aricle_title'	=>	$vv['aricle_title'],
								'aricle_praise_num'		=>	$vv['aricle_praise_num'],
								'aricle_comment_num'		=>	$vv['aricle_comment_num'],
								'url'		=>	$bbs_url,
								'update_time'	=>	date('Y-m-d H:i',$vv['update_time']),
						);
					}
					$arr['bbs']['aricle']	=	$aricle;
					if($pigcms_type == 1){
						$arr['bbs']['bbs_url']	=	str_replace('wap.php?','wap.php?',$bbs['bbs_url']);
						$arr['bbs']['bbs_url']	=	str_replace('Appapi','Wap',$bbs['bbs_url']);
					}else if($pigcms_type == 2){
						$arr['bbs']['bbs_url']	=	str_replace('wap.php?','wap_house.php?',$bbs['bbs_url']);
						$arr['bbs']['bbs_url']	=	str_replace('Appapi','Wap',$bbs['bbs_url']);
					}
				}else{
					$arr['bbs']['aricle']	=	array();
					$arr['bbs']['bbs_url']	=	'';
				}
				$arr['bbs']['sort']	=	'6';
			}
		}
		if(empty($arr['news'])){
			$arr['news']	=	array(
					'list'	=>	array(),
					'news_url'	=>	'',
					'sort'	=>	'1',
			);
		}
		if(empty($arr['category'])){
			$arr['category']	=	array(
					'list'	=>	array(),
					'sort'	=>	'2',
			);
		}
		if(empty($arr['group'])){
			$arr['group']	=	array(
					'list'	=>	array(),
					'list_url'	=>	'',
					'sort'	=>	'3',
			);
		}
		if(empty($arr['meal'])){
			$arr['meal']	=	array(
					'list'	=>	array(),
					'list_url'	=>	'',
					'sort'	=>	'4',
			);
		}
		if(empty($arr['appoint'])){
			$arr['appoint']	=	array(
					'list'	=>	array(),
					'list_url'	=>	'',
					'sort'	=>	'5',
			);
		}
		if(empty($arr['bbs'])){
			$arr['bbs']	=	array(
					'aricle'	=>	array(),
					'bbs_url'	=>	'',
					'sort'	=>	'6',
			);
		}
		$this->returnCode(0,$arr);
	}
	private function get_village($village_id){
		$now_village = D('House_village')->get_one($village_id);
		if(empty($now_village)){
			$this->returnCode('20120001');
		}
		return $now_village;
	}
	private function getHasConfig($village_id,$field){
		$database_house_village = D('House_village');
		$house_village_info = $database_house_village->get_one($village_id,$field);
		$config_info = $house_village_info[$field];
		return $config_info;
	}
	//	社区--便民服务
	public function house_service(){
		$village_id	=	I('village_id');
		$pigcms_type	=	I('pigcms_type',1);
		if(empty($village_id)){
			$this->returnCode('30000001');
		}
		$now_village = $this->get_village($village_id);
		$hot_cat_list = D('House_service_category')->getHotCatList($now_village['village_id'],6);
		//print_r($hot_cat_list);exit;



		if($hot_cat_list){
			// foreach($hot_cat_list as $k=>$v){
			// $url	=	htmlspecialchars_decode($v['cat_url']);
			// if($pigcms_type == 2){
			// $hot_cat_list[$k]['cat_url']	=	str_replace('wap.php','wap_house.php',$url);
			// }
			// }


			foreach($hot_cat_list as $k=>$v){
				$url	=	htmlspecialchars_decode($v['cat_url']);
				$url	=	substr($url,0,3);
				if($url != 'htt'){
					$cat_url	=	$this->config['site_url'].$v['cat_url'];
					$hot_cat_list[$k]['cat_url']	=	str_replace('appapi.php','wap.php',$cat_url);
				}
				if($pigcms_type == 2){
					$hot_cat_list[$k]['cat_url']	=	str_replace('wap.php','wap_house.php',$url);
				}
			}
		}
		//幻灯片
		$has_service_slide = $this->getHasConfig($now_village['village_id'],'has_service_slide');
		if($has_service_slide){
			$where['village_id'] = $now_village['village_id'];
			$where['status'] = '1';
			$where['type'] = '1';
			$slider_list = M('House_village_slider')->where($where)->order('sort DESC,id ASC')->select();
			if($slider_list){
				foreach($slider_list as $k=>$v){
					$url	=	htmlspecialchars_decode($v['url']);
					if(stripos($url , 'LBS://')!==FALSE){
						$url	=	wapLbsTranform($url);
					}
					if($pigcms_type == 2){
						$url	=	str_replace('wap.php','wap_house.php',$url);
					}
					$slider[$k]['id']	=	$v['id'];
					$slider[$k]['url']	=	$url;
					$slider[$k]['name']	=	$v['name'];
					$slider[$k]['pic']	=	$this->config['site_url'].'/upload/service/'.$v['pic'];
				}
			}
		}
		$cat_list = D('House_service_category')->getAllCatList($now_village['village_id']);
		if($cat_list){
			foreach($cat_list as $k=>$v){
				if(is_array($v)){
					foreach($v['son_list'] as $kk=>$vv){
						if(empty($vv['cat_url'])){
							$cat_url = $this->config['site_url'] . U('Wap/Houseservice/cat_list',array('village_id'=>$now_village['village_id'],'id'=>$vv['id']));
							$vv['cat_url'] = str_replace('appapi.php','wap.php',$cat_url);
						}
						$vvUrl	=	htmlspecialchars_decode($vv['cat_url']);
						if(stripos($vvUrl , 'LBS://')!==FALSE){
							$vvUrl	=	wapLbsTranform($vvUrl);
						}
						if($pigcms_type == 2){
							$vvUrl	=	str_replace('wap.php','wap_house.php',$vvUrl);
						}
						$cat[$k][]	=	array(
								'cat_fname'	=>	$v['cat_name'],
								'id'		=>	$vv['id'],
								'cat_name'	=>	$vv['cat_name'],
								'cat_url'	=>	$vvUrl,
								'cat_img'	=>	$vv['cat_img'],
						);
					}
				}
			}
			foreach($cat as $v){
				$cat_lists[]	=	$v;
			}
			if(empty($cat_lists)){
				$cat_lists[]	=	array();
			}
		}
		$arr['slider_list']	=	isset($slider)?$slider:array();
		$arr['hot_cat_list']=	isset($hot_cat_list)?$hot_cat_list:array();
		$arr['cat_list']	=	isset($cat_lists)?$cat_lists:array();
		$this->returnCode(0,$arr);
	}
	//	社区--常用电话
	public function house_phone(){
		$village_id	=	I('village_id');
		if(empty($village_id)){
			$this->returnCode('30000001');
		}
		$now_village = $this->get_village($village_id);
		$phone_list = D('House_village_phone_category')->getAllCatPhoneList($now_village['village_id']);
		if(empty($phone_list)){
			$phone_list	=	array();
		}else{
			foreach($phone_list as $k=>$v){
				foreach($v['phone_list'] as $kk=>$vv){
					$arr['phone_list'][$k][$kk]	=	$vv;
					$arr['phone_list'][$k][$kk]['cat_id']	=	$v['cat_id'];
					$arr['phone_list'][$k][$kk]['cat_name']	=	$v['cat_name'];
				}
			}
		}
		$arr['phone']	=	array(
				'name'	=>	'物业服务中心',
				'title'	=>	'拨打物业服务中心电话',
				'phone'	=>	isset($now_village['property_phone'])?$now_village['property_phone']:'',
		);
		$this->returnCode(0,$arr);
	}
	//	社区--我的
	public function village_my(){
		//判断用户是否属于本小区
		$village_id	=	I('village_id');
		if(empty($village_id)){
			$this->returnCode('30000001');
		}
		$ticket	=	I('ticket');
		$info = ticket::get($ticket, $this->DEVICE_ID, true);
		if(empty($info)){
			$this->returnCode('20000009');
		}
		$pigcms_id	=	I('pigcms_id');
		if(empty($pigcms_id)){
			$this->returnCode('20120004');
		}
		$pigcms_type	=	I('pigcms_type',1);
		//$bindList	=	M('House_village_user_bind')->where(array('pigcms_id'=>$pigcms_id))->find();
//		if($info['uid']	!=	$bindList['uid']){
//			$this->returnCode('20120002');
//		}
		$now_village = $this->get_village($village_id);
		$now_user_info = $this->get_user_village_info($pigcms_id,$village_id,$info['uid']);
		$now_user = D('User')->get_user($info['uid']);
		$arr['user']	=	array(
				'name'		=>	$now_user_info['name'],
				'usernum'	=>	$now_user_info['usernum'],
				'address'	=>	$now_user_info['address'],
				'avatar'	=>	$now_user['avatar'],
				'url'		=>	$this->config['site_url'].U('Wap/My/myinfo'),
		);
		if(empty($now_user['avatar'])){
			$arr['user']['avatar']	=	$this->config['site_url'].'/tpl/Wap/pure/static/images/pic-default.png';
		}
		if(empty($arr['user'])){
			$arr['user']	=	array();
		}else{
			if($pigcms_type == 1){
				$arr['user']['url']	=	str_replace('appapi.php','wap.php',$arr['user']['url']);
			}else if($pigcms_type == 2){
				$arr['user']['url']	=	str_replace('appapi.php','wap_house.php',$arr['user']['url']);
			}
		}
		if($arr['now_user_info']['parent_id'] == 0){
			$arr['village'][]	=	array(
					'url'	=>	$this->config['site_url'].U('Wap/House/village_my_bind_family_add',array('village_id'=>$now_village['village_id'])),
					'title'	=>	'绑定家属',
					'img'	=>	$this->config['site_url'].'/static/images/wap_house/family.png',
			);
		}
		$arr['village'][]		=	array(
				'url'	=>	$this->config['site_url'].U('Wap/House/village_my_pay',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'小区缴费',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/fee.png',
		);
		$arr['village'][]	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/House/village_my_repair',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'在线报修',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/repair.png',
		);
		$arr['village'][]=	array(
				'url'	=>	$this->config['site_url'].U('Wap/House/village_my_utilities',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'水电煤上报',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/newspaper.png',
		);
		$arr['service'][]	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/My/group_order_list'),
				'title'	=>	'团购订单',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/group.png',
		);
		$arr['service'][]		=	array(
				'url'	=>	$this->config['site_url'].U('Wap/My/appoint_order_list'),
				'title'	=>	'预约订单',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/meal.png',
		);
		$arr['service'][]	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/My/meal_order_list'),
				'title'	=>	'外卖订单',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/appoint.png',
		);

		$arr['life'][]		=	array(
				'url'	=>	$this->config['site_url'].U('Wap/House/village_my_paylists',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'缴费订单列表',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/book.png',
		);
		$arr['life'][]		=	array(
				'url'	=>	$this->config['site_url'].U('Wap/House/village_my_repairlists',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'在线报修列表',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/life_repair.png',
		);
		$arr['life'][]	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/House/village_my_utilitieslists',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'水电煤上报列表',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/life_newspaper.png',
		);
		if($arr['now_user_info']['parent_id'] == 0){
			$arr['life'][]	=	array(
					'url'	=>	$this->config['site_url'].U('Wap/House/village_my_bind_family_add',array('village_id'=>$now_village['village_id'])),
					'title'	=>	'绑定家属列表',
					'img'	=>	$this->config['site_url'].'/static/images/wap_house/parent_id.png',
			);
		}
		$arr['life'][]	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/Library/express_service_list',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'快递代收',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/pass.png',
		);
		$arr['life'][]	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/Library/visitor_list',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'访客登记',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/interview.png',
		);
		$arr['interaction'][]	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/House/village_my_suggest',array('village_id'=>$now_village['village_id'])),
				'title'	=>	'投诉建议',
				'img'	=>	$this->config['site_url'].'/static/images/wap_house/suggest.png',
		);
		foreach($arr['village'] as &$v){
			if($pigcms_type == 1){
				$v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
			}else if($pigcms_type == 2){
				$v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
			}
		}
		foreach($arr['service'] as &$v){
			if($pigcms_type == 1){
				$v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
			}else if($pigcms_type == 2){
				$v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
			}
		}
		foreach($arr['life'] as &$v){
			if($pigcms_type == 1){
				$v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
			}else if($pigcms_type == 2){
				$v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
			}
		}
		foreach($arr['interaction'] as &$v){
			if($pigcms_type == 1){
				$v['url']	=	str_replace('appapi.php','wap.php',$v['url']);
			}else if($pigcms_type == 2){
				$v['url']	=	str_replace('appapi.php','wap_house.php',$v['url']);
			}
		}
		if($pigcms_type == 1){
			$array	=	array('list'=>array($arr['village'],$arr['life'],$arr['service'],$arr['interaction']),'user'=>$arr['user']);
		}else{
			$array	=	array('list'=>array($arr['village'],$arr['service'],$arr['life'],$arr['interaction']),'user'=>$arr['user']);
		}
		$this->returnCode(0,$array);
	}


	//搜索小区附近
	public function lbs_search(){
		$keyword = I('keyword');
		$village_id = I('village_id');
		$city_id = I('city_id');
		if(empty($keyword)){
			$this->returnCode('20090012');
		}
		if(empty($village_id)){
			$this->returnCode('30000001');
		}
		if(empty($city_id)){
			$this->returnCode('20090002');
		}
		$now_city = D('Area')->field(true)->where(array('area_id' => $city_id))->find();
		$database_house_village = D('House_village');
		$now_village = $database_house_village->get_one($village_id);
		if(empty($now_village)){
			$this->returnCode('20090005');
		}
		$this->assign('now_village',$now_village);
		$url = 'http://api.map.baidu.com/place/v2/search?query='.urlencode($keyword).'&page_size=9999&page_num=0&scope=1&location=&location='.$now_village['lat'].','.$now_village['long'].'&radius=2000&output=json&ak=4c1bb2055e24296bbaef36574877b4e2';
		import('ORG.Net.Http');
		$http = new Http();
		$results = $http->curlGet($url);
		$return = array();
		if($results){
			$results = json_decode($results,true);
			if($results['status'] == 0 && $results['results']){
				$return = array();
				foreach($results['results'] as $value){
					if(!isset($value['location']['lat']) && !isset($value['location']['lng'])){
						continue;
					}
					$url = wapLbsTranform("LBS://".$value['location']['lng'].",".$value['location']['lat'],array('title'=>$value['name'],'village_id'=>$village_id),true);
					$return['list'][] = array(
							'name'=>$value['name'],
							'lat'=>$value['location']['lat'],
							'long'=>$value['location']['lng'],
							'address'=>$value['address'],
							'url'=>$url['url']
					);
				}
			}
			$return['count'] = count($return['list']);
		}

		$this->returnCode(0,$return);
	}


	//	查询是否绑定了当前小区
	protected function get_user_village_info($bind_id,$village_id,$uid){
		$now_user_info = D('House_village_user_bind')->get_one_by_bindId($bind_id);
		if(empty($now_user_info)){
			$this->returnCode('20120003');
		}
		$database_house_village_user_bind = D('House_village_user_bind');
		$where['parent_id|pigcms_id'] = $bind_id;
		$where['uid'] = $uid;
		$where['village_id'] = $village_id;
		$house_village_user_bind_count = $database_house_village_user_bind->where($where)->count();
		if(!$house_village_user_bind_count){
			$this->returnCode('20120002');
		}
		return $now_user_info;
	}
}
?>