<?php

//快店列表
class FoodshopAction extends BaseAction{
	public function index(){
		$this->header_json();
		$page	=	$_POST['page']?$_POST['page']:0;
		$page_count	=	10;
		$user_long_lat['long'] =$_POST['long'];
		$user_long_lat['lat']  =$_POST['lat'];
		if(!$user_long_lat['long'] && !$user_long_lat['lat']){
			$user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
		}

		$circle_id = 0;
		$area_url = I('area_url','');
		$sort_id =   I('sort_id','juli');
		$cat_url =   I('cat_url','all');
		if (!empty($area_url)) {
			$tmp_area = D('Area')->get_area_by_areaUrl($area_url);
			if(empty($tmp_area)){
				$this->returnCode('20045008');
			}

			if ($tmp_area['area_type'] == 3) {
				$now_area = $tmp_area;
			} else {
				$now_circle = $tmp_area;
				$now_area = D('Area')->get_area_by_areaId($tmp_area['area_pid'], true, $cat_url);
				if (empty($tmp_area)) {
					$this->returnCode('20045008');
				}
				$circle_url = $now_circle['area_url'];
				$circle_id = $now_circle['area_id'];
				$area_url = $now_area['area_url'];
			}
			$area_id = $now_area['area_id'];
		}
		//判断排序信息

		$cat_id = 0;
		if($cat_url != 'all'){
			$now_category = D('Meal_store_category')->get_category_by_catUrl($cat_url);
			if (empty($now_category)) {
				$this->returnCode('20045009');
			}
			if (!empty($now_category['cat_fid'])) {
				$f_category = D('Meal_store_category')->get_category_by_id($now_category['cat_fid']);
				$all_category_url = $f_category['cat_url'];
				$cat_fid = $now_category['cat_fid'];
				$cat_id = $now_category['cat_id'];
			} else {
				$cat_id = 0;
				$cat_fid = $now_category['cat_id'];
			}
		}
		$params['area_id'] = $area_id;
		$params['circle_id'] = $circle_id;
		$params['sort'] = $sort_id;
		$params['lat'] = $user_long_lat['lat'];
		$params['long'] = $user_long_lat['long'];
		$params['cat_fid'] = $cat_fid;
		$params['cat_id'] = $cat_id;
		$params['queue'] = -1;
		$params['page'] = $page==0?1:$page;
		$return = D('Merchant_store_foodshop')->wap_get_storeList_by_catid($params,1);
		$page_all = $return['totalPage'];
		$n=1;
		if(!empty($return)) {
			foreach ($return['store_list'] as $v) {
				if ($n > $page_count)
					break;
				$n++;
				if($v['discount_txt']['discount_type']==1){
					$v['discount_txt']  = $v['discount_txt']['discount_percent'].'折';
				}else if($v['discount_txt']['discount_type']==2) {
					$v['discount_txt']  = '每满'.$v['discount_txt']['condition_price'].'减'.$v['discount_txt']['minus_price'].'元';
				}else{
					$v['discount_txt']  = '';
				}
				$tmp_store_list[] = $v;
			}
			$store_lsit = $tmp_store_list;
		}

		$new_group_list =$store_lsit;


		if(!empty($new_group_list)){
			$this->returnCode(0,array('content'=>$new_group_list,'page_all'=>$page_all));exit;
		}else{
			$this->returnCode(0,array('content'=>array(),'page_all'=>$page_all));exit;
		}
	}



	public function header_json(){
		header('Content-type: application/json');
	}
}

?>