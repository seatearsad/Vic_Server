<?php
class GroupAction extends BaseAction{
    public function index(){
        //判断分类信息
        $cat_url = !empty($_GET['cat_url']) ? $_GET['cat_url'] : '';
        //判断地区信息
        $area_url = !empty($_GET['area_url']) ? $_GET['area_url'] : '';
        $circle_id = 0;
        if(!empty($area_url)){
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
        }else{
            $area_id = 0;
        }
        //判断排序信息
        $sort_id = !empty($_GET['sort_id']) ? $_GET['sort_id'] : 'juli';
        $long_lat   =   $this->user_long_lat;
        if(empty($long_lat)){
            $sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
            $sort_array = array(
                array('sort_id'=>'defaults','sort_value'=>'默认排序'),
                array('sort_id'=>'rating','sort_value'=>'评价最高'),
                array('sort_id'=>'start','sort_value'=>'最新发布'),
                array('sort_id'=>'solds','sort_value'=>'人气最高'),
                array('sort_id'=>'price','sort_value'=>'价格最低'),
                array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
            );
        } else {
            $sort_array = array(
                array('sort_id'=>'juli','sort_value'=>'离我最近'),
                array('sort_id'=>'rating','sort_value'=>'评价最高'),
                array('sort_id'=>'start','sort_value'=>'最新发布'),
                array('sort_id'=>'solds','sort_value'=>'人气最高'),
                array('sort_id'=>'price','sort_value'=>'价格最低'),
                array('sort_id'=>'priceDesc','sort_value'=>'价格最高'),
            );
        }
        foreach($sort_array as $key=>$value){
            if($sort_id == $value['sort_id']){
                $now_sort_array = $value;
                break;
            }
        }
        $arr['sort_array'] =   isset($sort_array)?$sort_array:null;

        //所有分类 包含2级分类
        $all_category = D('Group_category')->get_all_category();
        foreach($all_category as $k => $v){
            foreach($v['category_list'] as $kk => $vv){
                $v['category_list_tmp'][] =   $vv;
            }
            unset($v['category_list']);
            $all_category_list[]   =   $v;
        }
        unset($all_category);
        $arr['all_category_list'] =   isset($all_category_list)?$all_category_list:null;

        //根据分类信息获取分类
        if(!empty($cat_url)){
            $now_category = D('Group_category')->get_category_by_catUrl($cat_url);
            if(empty($now_category)){
                $this->returnCode('20045009');
            }

            if(!empty($now_category['cat_fid'])){
                $f_category = D('Group_category')->get_category_by_id($now_category['cat_fid']);
                $all_category_url = $f_category['cat_url'];
                $category_cat_field = $f_category['cat_field'];

                $top_category = $f_category;

                $get_grouplist_catfid = 0;
                $get_grouplist_catid = $now_category['cat_id'];
            }else{
                $all_category_url = $now_category['cat_url'];
                $category_cat_field = $now_category['cat_field'];
                $top_category = $now_category;

                $get_grouplist_catfid = $now_category['cat_id'];
                $get_grouplist_catid = 0;
            }
        }
        $all_area_list_tmp = D('Area')->get_all_area_list();
        foreach($all_area_list_tmp as $v){
            $all_area_list[]  =   $v;
        }
        $arr['all_area_list'] =   isset($all_area_list)?$all_area_list:array();
        $this->returnCode(0,$arr);
    }

    // 列表
    public function ajaxList(){
        //判断分类信息
        $cat_url = I('cat_url','');
        $_GET['page']   =   I('page');
        //判断地区信息
        $area_url = I('area_url','');

        $circle_id = 0;
        if(!empty($area_url)){
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
        }else{
            $area_id = 0;
        }

        //判断排序信息
        $sort_id = I('sort_id','juli');
        $long_lat   =   $this->user_long_lat;
        if(empty($long_lat)){
            $sort_id = $sort_id == 'juli' ? 'defaults' : $sort_id;
        }

        //所有分类 包含2级分类
        $all_category_list = D('Group_category')->get_all_category();

        //根据分类信息获取分类
        if(!empty($cat_url)){
            $now_category = D('Group_category')->get_category_by_catUrl($cat_url);
            if(empty($now_category)){
                $this->returnCode('20045009');
            }

            if(!empty($now_category['cat_fid'])){
                $f_category = D('Group_category')->get_category_by_id($now_category['cat_fid']);
                $all_category_url = $f_category['cat_url'];
                $category_cat_field = $f_category['cat_field'];

                $top_category = $f_category;

                $get_grouplist_catfid = 0;
                $get_grouplist_catid = $now_category['cat_id'];
            }else{
                $all_category_url = $now_category['cat_url'];
                $category_cat_field = $now_category['cat_field'];
                $top_category = $now_category;

                $get_grouplist_catfid = $now_category['cat_id'];
                $get_grouplist_catid = 0;
            }
        }
        C('config.group_page_row',10);
        if($sort_id == 'juli'){
            $return = D('Group')->wap_get_storeList_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
            foreach($return['store_list'] as &$storeValue){
                $storeValue['url'] = $this->config['site_url'].str_replace('appapi.php','wap.php',$storeValue['url']);
                $storeValue['range_txt'] = $this->wapFriendRange($storeValue['juli']);
                $group_list = S('wap_store_group_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$storeValue['store_id']);
                if(empty($group_list)){
                    $group_list = D('Group')->get_single_store_group_list($storeValue['store_id'],0,true);
					foreach($group_list as $key=>$value){
						if(($get_grouplist_catid && $value['cat_id'] != $get_grouplist_catid) || ($get_grouplist_catfid && $value['cat_fid'] != $get_grouplist_catfid)){
							unset($group_list[$key]);
						}
					}
					$group_list = array_values($group_list);
                    S('wap_store_group_'.($get_grouplist_catfid ? $get_grouplist_catfid : $get_grouplist_catid).'_'.$storeValue['store_id'],$group_list,360);
                }else{
                    foreach($group_list as &$groupValue){
                        if($groupValue['end_time'] < $_SERVER['REQUEST_TIME']){
                            unset($groupValue);
                        }
                    }
                }
                $storeValue['group_list'] = $group_list;
                $storeValue['group_count'] = count($group_list);
                if(empty($storeValue['group_count'])){
                    unset($storeValue);
                }
                foreach($storeValue as $k => &$v){
                    foreach($v as $kk => &$vv){
                        $vv['url']  =$this->config['site_url'].$vv['url'];
                        $vv['price']    =   rtrim(rtrim(number_format($vv['price'],2,'.',''),'0'),'.');
                        $vv['wx_cheap'] =   rtrim(rtrim(number_format($vv['wx_cheap'],2,'.',''),'0'),'.');
                    }
                }
            }
            if(!$return['store_list']){
                $return['store_list']   =   array();
            }
            $return['style'] = 'store';
        }else{
            $return = D('Group')->wap_get_group_list_by_catid($get_grouplist_catid,$get_grouplist_catfid,$area_id,$sort_id, $long_lat['lat'], $long_lat['long'], $circle_id);
            $return['style'] = 'group';
            foreach($return['group_list'] as $k => $v){
                $return['group_list'][$k]['url']   =   $this->config['site_url'].$v['url'];
                $return['group_list'][$k]['price'] =   rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.');
                $return['group_list'][$k]['wx_cheap'] =   rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.');
            }
        }
        if(!$return['group_list']){
            $return['group_list']   =   array();
        }
        if(!$return['store_list']){
            $return['store_list']   =   array();
        }

        $this->returnCode(0,$return);
    }
    //	团购详情
    public function detail(){
    	$group_id	=	I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');

		if(empty($now_group)){
			$this->returnCode('20046010');
		}
		if($now_group['cue']){
			$now_group['cue_arr'] = unserialize($now_group['cue']);
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//判断是否微信浏览器，
		$long	=	I('long');
		$lat	=	I('lat');
		if($long && $lat){
			$rangeSort = array();
			foreach($now_group['store_list'] as &$storeValue){
				$storeValue['Srange'] = getDistance($lat,$long,$storeValue['lat'],$storeValue['long']);
				$storeValue['range'] = getRange($storeValue['Srange'],false);
				$rangeSort[] = $storeValue['Srange'];
			}
			array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
		}
		if($now_group['packageid']>0){
			$packages=M('Group_packages')->where(array('id' => $now_group['packageid'], 'mer_id' => $now_group['mer_id']))->find();
			if(!empty($packages['groupidtext'])){
				$mpackages = unserialize($packages['groupidtext']);
				$packagesgroupid = $this->check_group_status(array_keys($mpackages));
				if(is_array($packagesgroupid)){
					foreach($packagesgroupid as $gvv){
						$tmp_mpackages[]	=	array(
							'key'	=>	$gvv['group_id'],
							'value'	=>	$mpackages[$gvv['group_id']],
						);
					}
					$mpackages=$tmp_mpackages;
					unset($tmp_mpackages);
				}
			}else{
				$mpackages = false;
			}
		}
		$arr['mpackages']	=	isset($mpackages)?$mpackages:array();
		//	积分是否存在
		if(!empty($this->_uid)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}
			//判断积分抵现
			$user_coupon_use = D('User')->check_score_can_use($this->_uid,$now_group['price'],'group',$now_group['group_id']);
		}
//		if($now_group['pin_num']>0){
//			$now_group['price'] = $now_group['old_price'];
//		}
		$arr['now_group']	=	array(
			'score'			=>	$user_coupon_use['score'],
			'score_money'	=>	$user_coupon_use['score_money'],
			'group_id'		=>	$now_group['group_id'],				//团购ID
			'image'			=>	$now_group['all_pic'][0]['m_image'],	//图片
			'group_name'	=>	$now_group['group_name'],	//团购名
			'price'			=>	rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),		//现价
			'old_price'		=>	rtrim(rtrim(number_format($now_group['old_price'],2,'.',''),'0'),'.'),	//老价格
			'wx_cheap'		=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),	//APP优惠多少钱
			'group_share_num'=>	$now_group['group_share_num'],	//您需要购买或者邀请好友购买多少份才能成团
			'open_now_num'	=>	$now_group['open_now_num'],	//还差多少份成团
			'open_num'		=>	$now_group['open_num'],			//还差多少份成团
			'sale_count'	=>	$now_group['sale_count']+$now_group['virtual_num'],	//已售
			'score_mean'	=>	$now_group['score_mean'],			//多少分
			'reply_count'	=>	$now_group['reply_count'],			//多少人评论
			'tuan_type'		=>	$now_group['tuan_type'],			//团购类型
			'pin_num'		=>	$now_group['pin_num'],			//团购类型
			'trade_type' => $now_group['trade_type'],
			'appoint_id' => $now_group['appoint_id'],
			'pic_list'=>$now_group['merchant_pic']
		);

		if($now_group['tuan_type'] != 2){
			$arr['now_group']['merchant_name']	=	$now_group['merchant_name'];
		}else{
			$arr['now_group']['merchant_name']	=	$now_group['s_name'];
		}
		if($now_group['cue_arr']){
			foreach($now_group['cue_arr'] as $v){
				if(!empty($v['value'])){
					$cue_arr[]	=	array(
						'key'	=>	$v['key'],
						'value'	=>	$v['value'],
					);
				}
			}
			$arr['cue_arr']	=	isset($cue_arr)?$cue_arr:array();
		}else{
			$arr['cue_arr']	=	array();
		}
		//	商家的其他店铺
		if($now_group['store_list']){
			foreach($now_group['store_list'] as $k=>$v){
				$arr['store_list'][]	=	array(
					'store_id'	=>	$v['store_id'],
					'name'	=>	$v['name'],
					'area_name'	=>	$v['area_name'],
					'adress'	=>	$v['adress'],
					'range'	=>	isset($v['range'])?$v['range']:'',
					'phone'	=>	$v['phone'],
					'lat'	=>	$v['lat'],
					'lng'	=>	$v['long'],
				);
			}
		}else{
			$arr['store_list']	=	array();
		}
		//	还有多久开始时间
		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];
		if($time > 0){
			$arr['now_group']['is_time']	=	1;		//时间按钮
			$time_array = '还剩'.floor($time/86400).'天'.floor($time%86400/3600).'时'.floor($time%86400%3600/60).'分开团';
		}else if($now_group['end_time'] > $_SERVER['REQUEST_TIME'] && $now_group['begin_time'] < $_SERVER['REQUEST_TIME'] && $now_group['type'] == 1){
			if($now_group['is_appoint_bind']){
				$arr['now_group']['is_time']	=	2;	//立即预约
				$appUrl		=	$this->config['site_url'].U('Wap/Appoint/detail',array('appoint_id'=>$now_group['appoint_id']));
				$arr['now_group']['url']	=	str_replace('appapi.php','wap.php',$appUrl);
			}else{
				$arr['now_group']['is_time']	=	3;	//立即购买
			}
		}else{
			$arr['now_group']['is_time']	=	0;		//没有按钮
		}
		
		$arr['now_group']['time_array']	=	isset($time_array)?$time_array:'';
		$arr['now_group']['url']	=	isset($arr['now_group']['url'])?$arr['now_group']['url']:'';
		if($now_group['trade_type']!=''){
			$arr['now_group']['is_time']=4;
			$arr['now_group']['url']	=	$this->config['site_url'].'/wap.php?c=Group&a=buy&group_id='.$now_group['group_id'];
		}
		//	评论
		if($now_group['reply_count']){
			$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
		}
		if($reply_list){
			foreach($reply_list as $k=>$v){
				if($v['pics']){
					foreach($v['pics'] as $kk=>$vv){
						if($kk == 8){
							break;
						}else{
							$pics[]	=	$vv['m_image'];
						}
					}
				}
				$arr['reply_list'][]	=	array(
					'nickname'	=>	$v['nickname'],
					'add_time'	=>	$v['add_time'],
					'score'		=>	$v['score'],
					'comment'	=>	$v['comment'],
					'pics'		=>	isset($pics)?$pics:array(),
					'merchant_reply_content'	=>	$v['merchant_reply_content'],
				);
				unset($pics);
			}
		}else{
			$arr['reply_list']	=	array();
		}
		//	商家其他的团购
		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
		if($merchant_group_list){
			foreach($merchant_group_list as $k=>$v){
				$arr['merchant_group_list'][]	=	array(
					'list_pic'	=>	$v['list_pic'],
					'group_id'	=>	$v['group_id'],
					'name'	=>	$v['name'],
					'group_name'	=>	$v['group_name'],
					'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
					'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
					'sale_count'	=>	$v['sale_count']+$v['virtual_num'],
				);
			}
		}else{
			$arr['merchant_group_list']	=	array();
		}
		//	分类下其他团购，看了本团购的用户还看了
		$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
		if($category_group_list){
			foreach($category_group_list as $key=>$value){
				if($value['group_id'] == $now_group['group_id']){
					unset($category_group_list[$key]);
				}else{
					$arr['category_group_list'][]	=	array(
						'list_pic'	=>	$value['list_pic'],
						'group_id'	=>	$value['group_id'],
						'name'	=>	$value['name'],
						'tuan_type'	=>	$value['tuan_type'],
						'group_name'	=>	$value['group_name'],
						'price'	=>	rtrim(rtrim(number_format($value['price'],2,'.',''),'0'),'.'),
						'wx_cheap'	=>	rtrim(rtrim(number_format($value['wx_cheap'],2,'.',''),'0'),'.'),
						'sale_count'	=>	$value['sale_count']+$value['virtual_num'],
					);
				}
			}
		}else{
			$arr['category_group_list']	=	array();
		}
		$arr['share']	=	array(
			'url'	=>	$this->config['site_url'].U('Wap/Group/detail',array('group_id'=>$now_group['group_id'])),
			'pic'	=>	$arr['now_group']['image'],
			'title'	=>	$arr['now_group']['merchant_name'],
			'content'=>	$arr['now_group']['group_name'],
		);
		$arr['share']['url']	=	str_replace('appapi.php','wap.php',$arr['share']['url']);
		if($this->DEVICE_ID=='wxapp'){
			$arr['now_group']['content_xml'] = S('wxapp_groupcontent_'.$now_group['group_id']);
			if(empty($arr['now_group']['content_xml'])){
				$src	=	'<img src="'.C('config.site_url').'/';
				$now_group['content']	=	str_replace('<img src="/',$src,$now_group['content']);
				// $now_group['content'] = '<p></p>';
				$dom = new simple_html_dom();
				$dom->load($now_group['content']);
				$arr['now_group']['content_xml'] = $this->htmlToArray($dom->root->children, 1);
				$dom->clear();
				S('wxapp_groupcontent_'.$now_group['group_id'],$arr['now_group']['content_xml'],600);
			}
			
		}
		
		$this->returnCode(0,$arr);
	}
	
	private function htmlToArray($obj, $count)
	{
		// fdump($obj,'obj');
		$return = array();
		foreach ($obj as $p) {
			$data = array();
			if ($p->tag == 'table') continue;
			$data['tag'] = $p->tag;
			foreach ($p->attr as $k => $v) {
				$data['attr'][$k] = $v;
			}
			if ($p->tag == 'img') {
				$data['index'] = $count;
				$count ++;
			}
			if (!empty($p->nodes)) {
				$data['child'] =  $this->htmlToArray($p->nodes, $count);
			} else {
				$data['text'] = htmlspecialchars_decode(str_replace('&nbsp;',' ',$p->plaintext));
				$txt = trim($data['text']);
				if (empty($txt) && $p->tag == 'text') continue;
			}
			$return[] = $data;
		}
		return $return;
	}
	
	private function check_group_status($groupids=array()){
		if(!empty($groupids)){
			$tmpids=M('Group')->where('group_id in('.implode(',',$groupids).') and status="1"')->field('group_id')->select();
			return $tmpids;
		}
		return false;
	}
	//	图文详情
	public function detail_content(){
		$group_id	=	I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');
		if(empty($now_group)){
			$this->returnCode('20046010');
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//	组装详情字段
		$src	=	'<img src="'.C('config.site_url').'/';
		$now_group['content']	=	str_replace('<img src="/',$src,$now_group['content']);
		$content	=	'<!DOCTYPE html>
						<html>
						<meta charset="utf-8" />
								<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
								<meta name="apple-mobile-web-app-capable" content="yes"/>
								<meta name="apple-touch-fullscreen" content="yes"/>
								<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
								<meta name="format-detection" content="telephone=no"/>
								<meta name="format-detection" content="address=no"/>
						<head>
						<style type="text/css">
							body {font-size: 14px;line-height: 1.5;-webkit-user-select: none;-webkit-touch-callout: none;background-color:white;padding-bottom: 49px;padding-left:10px;padding-right:10px;}
							article, aside, audio, body, canvas, caption, details, div, p, figure, footer, header, hgroup, html, iframe, img, mark, menu, nav, object, section, span, summary, table, tbody, td, tfoot, thead, tr, video, dl, dd {margin: 0;padding: 0;border: 0;}
							table {border-collapse: collapse;border-spacing: 0;}
							.deal-menu-summary {padding: 0 10px 10px;text-align: right;border-bottom: 1px #e8e8e8 solid;}
							.detail .content p {margin: 10px 0;color: #a1a1a1;}
							.deal-menu-summary .worth {display: inline-block;min-width: 10px;_width: 10px;padding-right: 20px;text-align: left;word-break: normal;word-wrap: normal;font-weight: bold;}
							.deal-menu-summary .price {color: #ea4f01;padding-right: 0;}
							.detail .content{line-height:1.6em;}.detail .content table { width:100%!important; margin-top:0px; border:none; font-size:14px; color:#222; }
							.detail .content table .name { width:auto; text-align:left; border-left:none; }
							.detail .content table .price { width:20%; text-align:center; }
							.detail .content table .amount { width:20%; text-align:center; }
							.detail .content table .subtotal { width:20%; text-align:right; border-right:none; font-family: arial, sans-serif; }
							.detail .content table caption, .detail .content table th, .detail .content table td {padding:8px 10px; background:#FFF; border:1px solid #E8E8E8; border-top:none; word-break:break-all; word-wrap:break-word;}
							.detail .content table caption { background:#F0F0F0; }
							.detail .content table caption .title, .detail .content table .subline .title { font-weight:bold; }
							.detail .content table th { color:#333; background:#F0F0F0; font-weight:bold; border-left-style:none; border-right-style:none;}
							.detail .content table td { color:#a1a1a1; border-bottom-style:dotted; }
							.detail .content table .subline { background:#fff; text-align:center; border-left:none; border-right:none; }
							.detail .content table .subline-left { width:22%; text-align:left;border-right: 1px #e8e8e8 dotted; }
							.detail .content img{max-width:100%;_width:100%;display:inline-block;}
							.detail .content ul{list-style-type: initial;padding-left:16px;font-size:14px!important;}
							.detail .content ul li {font-size:14px!important;margin:4px 0;line-height: 1.5;color:#a1a1a1!important;}
						</style></head><body><div class="detail"><div class="content">'.$now_group['content'].'</div></div></body></html>';
						
						
		
		
		$arr['now_group']	=	array(
			'group_id'		=>	$now_group['group_id'],				//团购ID
			'image'		=>	$now_group['all_pic'][0]['m_image'],	//图片
			'group_name'	=>	$now_group['group_name'],	//团购名
			'price'	=>	rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),			//现价
			'old_price'	=>	rtrim(rtrim(number_format($now_group['old_price'],2,'.',''),'0'),'.'),	//老价格
			'wx_cheap'	=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),	//APP优惠多少钱
			'group_share_num'	=>	$now_group['group_share_num'],	//您需要购买或者邀请好友购买多少份才能成团
			'open_now_num'	=>	$now_group['open_now_num'],	//还差多少份成团
			'open_num'	=>	$now_group['open_num'],			//还差多少份成团
			'content'		=>	$content,						//本单详情
			'tuan_type'		=>	$now_group['tuan_type'],			//团购类型
		);
		
		if($now_group['tuan_type'] == 2){
			$arr['now_group']['merchant_name']	=	$now_group['merchant_name'];
		}else{
			$arr['now_group']['merchant_name']	=	$now_group['s_name'];
		}
		//	还有多久开始时间
		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];
		if($time > 0){
			$arr['now_group']['is_time']	=	1;		//时间按钮
			$time_array = '还剩'.floor($time/86400).'天'.floor($time%86400/3600).'时'.floor($time%86400%3600/60).'分开团';
		}else if($now_group['end_time'] > $_SERVER['REQUEST_TIME'] && $now_group['begin_time'] < $_SERVER['REQUEST_TIME'] && $now_group['type'] == 1){
			if($now_group['is_appoint_bind']){
				$arr['now_group']['is_time']	=	2;	//立即预约
			}else{
				$arr['now_group']['is_time']	=	3;	//立即购买
			}
		}else{
			$arr['now_group']['is_time']	=	0;		//没有按钮
		}
		$arr['now_group']['url'] = C('config.site_url').'/wap.php?g=Wap&c=Appoint&a=index';
		$arr['now_group']['time_array']	=	isset($time_array)?$time_array:'';
		$this->returnCode(0,$arr);
	}
	//	提交订单页面
	public function buy(){
		$now_user = D('User')->get_user($this->_uid);
		if(empty($now_user)){
			$this->returnCode('20044013');
		}
		$group_id	=	I('group_id');
		$type	=	I('type',1);
		$now_group = D('Group')->get_group_by_groupId($group_id);
		if($now_group['pin_num']>0){
			if($_POST['group_type']==1){
				$now_group['price'] = $now_group['old_price'];
			}elseif($_POST['group_type']==3){
				$now_group['price'] = $now_group['price']*$now_group['start_discount']/100; //团长按团长折扣计算
			}elseif($_POST['group_type']==2){
				$_POST['group_type'] = 2;
				$now_group['price'] = $now_group['price'];
			}
		}
		if(empty($now_group)){
			$this->returnCode('20046011');
		}
		if($now_group['begin_time'] > $_SERVER['REQUEST_TIME']){
			$this->returnCode('20046012');
		}
		if($now_group['type'] > 2){
			$this->returnCode('20046013');
		}
		//用户等级 优惠
		$level_off=false;
		$finalprice=0;
		if(!empty($this->user_level) && !empty($now_user) && isset($now_user['level'])){
			$leveloff=!empty($now_group['leveloff']) ? unserialize($now_group['leveloff']) :'';
			/****type:0无优惠 1百分比 2立减*******/
			if(!empty($leveloff) && isset($leveloff[$now_user['level']]) && isset($this->user_level[$now_user['level']])){
				$level_off=$leveloff[$now_user['level']];
				if($level_off['type']==1){
					$finalprice=$now_group['price']*($level_off['vv']/100);
					$finalprice=$finalprice>0 ? $finalprice : 0;
					$level_off['offstr']='单价按原价'.$level_off['vv'].'%来结算';
				}elseif($level_off['type']==2){
					$finalprice=$now_group['price']-$level_off['vv'];
					$finalprice=$finalprice>0 ? $finalprice : 0;
					$level_off['offstr']='单价立减'.$level_off['vv'].'元';
				}
			}
		}
		is_array($level_off) && $level_off['price']=round($finalprice,2);
		unset($leveloff);
		if($type == 2){
			$finalprice > 0 && $now_group['price']=round($finalprice,2);
			//$_POST['group_type']=1;
			$result = D('Group_order')->save_post_form($now_group,$now_user['uid'],0);

			if($result['error'] == 1){
				$this->returnCode('20046014');
			}
			$arr['order']	=	array(
				'order_id'	=>	$result['order_id'],
				'type'	=>	'group',
			);
			$this->returnCode(0,$arr);
		}else{
			if($now_group['tuan_type'] == 2){
				$now_group['user_adress'] = D('User_adress')->get_one_adress($now_user['uid'],intval($_GET['adress_id']));
			}
			$pick_list = D('Pick_address')->get_pick_addr_by_merid($now_group['mer_id']);
			$pick_addr_id	=	I('pick_addr_id');
			if(!empty($pick_addr_id)){
				foreach($pick_list as $k=>$v){
					if($v['pick_addr_id']==$pick_addr_id){
						$pick_address = $v;
						break;
					}
				}
			}else{
				$pick_address =$pick_list[0];
			}
			if($pick_address){
				$arr['pick_address'][]	=	array(
					'pick_addr_id'	=>	$pick_address['pick_addr_id'],
					'name'	=>	$pick_address['name'],
					'phone'	=>	$pick_address['phone'],
					'province'	=>	$pick_address['area_info']['province'],
					'city'	=>	$pick_address['area_info']['city'],
					'area'	=>	$pick_address['area_info']['area'],
				);
			}else{
				$arr['pick_address']	=	array();
			}
			if($now_group){
				$arr['now_group']	=	array(
					'group_id'	=>	$now_group['group_id'],
					'pin_num'	=>	$now_group['pin_num'],
					'mer_id'	=>	$now_group['mer_id'],
					's_name'	=>	$now_group['s_name'],
					'price'	=>		rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),
					'wx_cheap'	=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),
					'once_min'	=>	$now_group['once_min'],
					'once_max'	=>	$now_group['once_max'],
					'tuan_type'	=>	$now_group['tuan_type'],			//2选择地址，时间说明	0，1直接下单
					'pick_in_store'	=>	$now_group['pick_in_store'],	//0送货	1自提
					'finalprice'	=>	isset($finalprice)?$finalprice:0,
					'level'	=>	isset($level_off['level'])?$level_off['level']:0,
					'pd_price'	=>	isset($level_off['price'])?rtrim(rtrim(number_format($level_off['price'],2,'.',''),'0'),'.'):0,
				);
			}else{
				$arr['now_group']	=	array();
			}
			if($now_group['user_adress']){
				$arr['user_adress'][]	=	array(
					'adress_id'	=>	$now_group['user_adress']['adress_id'],
					'name'	=>	$now_group['user_adress']['name'],
					'phone'	=>	$now_group['user_adress']['phone'],
					'province_txt'	=>	$now_group['user_adress']['province_txt'],
					'city_txt'	=>	$now_group['user_adress']['city_txt'],
					'area_txt'	=>	$now_group['user_adress']['area_txt'],
					'adress'	=>	$now_group['user_adress']['adress'],
					'detail'	=>	$now_group['user_adress']['detail'],
					'zipcode'	=>	$now_group['user_adress']['zipcode'],
					'url'	=>	$this->config['site_url'].U('Wap/My/adress',array('group_id'=>$now_group['group_id'],'current_id'=>$now_group['current_id'])),
				);
				$arr['user_adress'][0]['url']	=	str_replace('appapi.php','wap.php',$arr['user_adress'][0]['url']);
			}else{
				$arr['user_adress']	=	array();
			}
			$arr['delivery_time']	=	array(
				array(
					'key'	=>	1,
					'value'	=>	'工作日、双休日与假日均可送货',
				),
				array(
					'key'	=>	2,
					'value'	=>	'只工作日送货',
				),
				array(
					'key'	=>	3,
					'value'	=>	'只双休日、假日送货',
				),
				array(
					'key'	=>	4,
					'value'	=>	'白天没人，其它时间送货',
				),
			);
			if($now_user['phone']){
				$arr['user']	=	array(
					'phone'		=>	substr($now_user['phone'],0,3).'****'.substr($now_user['phone'],7),
				);
			}else{
				$arr['user']	=	array(
					'phone'	=>	'',
				);
			}
			$this->returnCode(0,$arr);
		}
	}
	//	店铺详情
	public function shop(){
		$store_id	=	I('store_id');
		$now_store = D('Merchant_store')->get_store_by_storeId($store_id);
		if(empty($now_store)){
			$this->returnCode('20046001');
		}
		//得到当前店铺的评分
		$store_score = D('Merchant_score')->field('`score_all`,`reply_count`')->where(array('parent_id'=>$now_store['store_id'],'type'=>'2'))->find();
		if(!$store_score){
			$store_score	=	array(
				'score_all'	=>	'',
				'reply_count'	=>	'',
			);
		}else{
			$store_score['reply_count']	=	round($store_score['score_all']/$store_score['reply_count'],1);
		}
		$arr['store_score']	=	$store_score;
		if(!empty($this->_uid)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_shop';
			$condition_user_collect['id'] = $now_store['store_id'];
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_store['is_collect'] = true;
			}
		}
		if($now_store){
			$arr['now_store']	=	array(
				'store_id'	=>	$now_store['store_id'],
				'name'		=>	$now_store['name'],
				'adress'	=>	$now_store['adress'],
				'phone'		=>	$now_store['phone'],
				'all_pic'	=>	$now_store['all_pic'][0],
				'store_url'	=>	$this->config['site_url'].U('Wap/Index/index',array('token'=>$now_store['mer_id'])),
				'pay_url'	=>	$this->config['site_url'].U('Wap/My/pay',array('store_id'=>$now_store['store_id'])),
				'map_url'	=>	$this->config['site_url'].U('Wap/Group/addressinfo',array('store_id'=>$now_store['store_id'])),
			);
			$arr['now_store']['store_url']	=	str_replace('appapi.php','wap.php',$arr['now_store']['store_url']);
			$arr['now_store']['pay_url']	=	str_replace('appapi.php','wap.php',$arr['now_store']['pay_url']);
			$arr['now_store']['map_url']	=	str_replace('appapi.php','wap.php',$arr['now_store']['map_url']);
		}else{
			$arr['now_store']	=	array();
		}
		$store_group_list = D('Group')->get_store_group_list($now_store['store_id'],0,true);
		if($store_group_list){
			foreach($store_group_list as $k=>$v){
				$arr['store_group_list'][]	=	array(
					'group_name'	=>	$v['group_name'],
					'group_id'	=>	$v['group_id'],
					'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
					'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
					'sale_count'	=>	$v['sale_count'],
					'list_pic'	=>	$v['list_pic'],
					'pin_num'	=>	$v['pin_num'],
				);
			}
		}else{
			$arr['store_group_list']	=	array();
		}
		//为粉丝推荐
		$index_sort_group_list = D('Group')->get_group_list('index_sort',10,true);
		//判断是否微信浏览器，
		if($index_sort_group_list){
			$long	=	I('long');
			$lat	=	I('lat');
			if($long && $lat){
				import('@.ORG.longlat');
				$longlat_class = new longlat();
				$location2 = $longlat_class->gpsToBaidu($lat,$long);//转换腾讯坐标到百度坐标
				$group_store_database = D('Group_store');
				foreach($index_sort_group_list as &$storeGroupValue){
					$tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
					if($tmpStoreList){
						foreach($tmpStoreList as &$tmpStore){
							$tmpStore['Srange'] = getDistance($location2['lat'],$location2['lng'],$tmpStore['lat'],$tmpStore['long']);
							$tmpStore['range'] = getRange($tmpStore['Srange'],false);
							$rangeSort[] = $tmpStore['Srange'];
						}
						array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
						$storeGroupValue['store_list'] = $tmpStoreList;
						$storeGroupValue['range'] = $tmpStoreList[0]['range'];
					}
				}
			}
			foreach($index_sort_group_list as $v){
				$arr['index_sort_group_list'][]	=	array(
					'group_name'	=>	$v['group_name'],
					'group_id'	=>	$v['group_id'],
					'list_pic'	=>	$v['list_pic'],
					'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
					'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
					'sale_count'	=>	$v['sale_count'],
				);
			}
		}else{
			$arr['index_sort_group_list']	=	array();
		}
		$this->returnCode(0,$arr);
	}
	//	团购评论
	public function feedback(){
		$group_id	=	I('group_id');
		$tmp_now_group = D('Group')->get_group_by_groupId($group_id);
		if(empty($tmp_now_group)){
			$this->returnCode('20046011');
		}else{
			$now_group	=	array(
				'score_mean'	=>	$tmp_now_group['score_mean'],
				'reply_count'	=>	$tmp_now_group['reply_count'],
			);
		}
		$arr['now_group']	=	$now_group;
		$reply_return = D('Reply')->get_page_reply_list($tmp_now_group['group_id'],0,'','time',count($tmp_now_group['store_list']));
		foreach($reply_return['list'] as &$v){
			unset($v['store_name'],$v['avatar'],$v['pigcms_id'],$v['order_id'],$v['parent_id'],$v['store_id'],$v['mer_id'],$v['order_type'],$v['uid'],$v['anonymous'],$v['pic'],$v['status'],$v['add_ip']);
			foreach($v['pics'] as $vv){
				$pics[]	=	$vv['m_image'];
			}
			$v['pics']	=	isset($pics)?$pics:array();
			unset($pics);
		}
		unset($reply_return['page']);
		$arr['reply_return']	=	$reply_return;
		$this->returnCode(0,$arr);
	}

	//拼团详情
	public function group_pin_detail(){
		$group_id	=	I('group_id');
		$now_group = D('Group')->get_group_by_groupId($group_id,'hits-setInc');

		if(empty($now_group)){
			$this->returnCode('20046010');
		}
		if($now_group['cue']){
			$now_group['cue_arr'] = unserialize($now_group['cue']);
		}
		if(!empty($now_group['pic_info'])){
			$merchant_image_class = new merchant_image();
			$now_group['merchant_pic'] = $merchant_image_class->get_allImage_by_path($now_group['pic_info']);
		}
		//判断是否微信浏览器，
		$long	=	I('long');
		$lat	=	I('lat');
		if($long && $lat){
			$rangeSort = array();
			foreach($now_group['store_list'] as &$storeValue){
				$storeValue['Srange'] = getDistance($lat,$long,$storeValue['lat'],$storeValue['long']);
				$storeValue['range'] = getRange($storeValue['Srange'],false);
				$rangeSort[] = $storeValue['Srange'];
			}
			array_multisort($rangeSort, SORT_ASC, $now_group['store_list']);
		}

		//	积分是否存在
		if(!empty($this->_uid)){
			$database_user_collect = D('User_collect');
			$condition_user_collect['type'] = 'group_detail';
			$condition_user_collect['id'] = $now_group['group_id'];
			$condition_user_collect['uid'] = $this->_uid;
			if($database_user_collect->where($condition_user_collect)->find()){
				$now_group['is_collect'] = true;
			}

			//判断积分抵现
			$user_coupon_use = D('User')->check_score_can_use($this->_uid,$now_group['price'],'group',$now_group['group_id']);
		}

		$arr['now_group']	=	array(
			'score'			=>	$user_coupon_use['score']?$user_coupon_use['score']:0,
			'score_money'	=>	$user_coupon_use['score_money']?strval($user_coupon_use['score_money']):0,
			'group_id'		=>	$now_group['group_id'],				//团购ID
			'group_name'	=>	$now_group['group_name'],	//团购名
			'price'			=>	rtrim(rtrim(number_format($now_group['price'],2,'.',''),'0'),'.'),		//现价
			'old_price'		=>	rtrim(rtrim(number_format($now_group['old_price'],2,'.',''),'0'),'.'),	//老价格
			'wx_cheap'		=>	rtrim(rtrim(number_format($now_group['wx_cheap'],2,'.',''),'0'),'.'),	//APP优惠多少钱
			'group_share_num'=>	$now_group['group_share_num'],	//您需要购买或者邀请好友购买多少份才能成团
			'open_now_num'	=>	$now_group['open_now_num'],	//还差多少份成团
			'open_num'		=>	$now_group['open_num'],			//还差多少份成团
			'sale_count'	=>	$now_group['sale_count']+$now_group['virtual_num'],	//已售
			'score_mean'	=>	$now_group['score_mean'],			//多少分
			'reply_count'	=>	$now_group['reply_count'],			//多少人评论
			'tuan_type'		=>	$now_group['tuan_type'],			//团购类型
			'pin_num'      =>$now_group['pin_num'],                 //拼团人数
			'invite_num'  =>$now_group['pin_num']-1,
			'time_desc'  =>'该团拼团时限为'.$now_group['pin_effective_time'].'小时',
		    'group_desc' =>$now_group['intro'],
		);
		foreach( $now_group['all_pic'] as $v_img){
			$arr['now_group']['img_arr'][] = $v_img['m_image'];
		}
		//判断当前团购是否有团购组
		$can_join = D('Group_start')->check_join_pin($now_group['group_id'],$this->_uid,$now_group['pin_effective_time']);
		if($can_join){
			$arr['now_group']['can_join'] = 1;
			$arr['now_group']['pin_need_num'] = $can_join;
		}else{
			$arr['now_group']['can_join'] = 0;
			$arr['now_group']['pin_need_num'] = 0;
		}
		if($now_group['group_refund_fee']!=100){

			$arr['pin_sign'] = array('1. 拼团成功前，开团人（团长）在拼团时限内不允许取消订单，参团人允许取消订单。','2. 拼团成功后，开团人（团长）不得取消订单，参团人取消订单则收取一定的手续费。');
		}else{
			$arr['pin_sign'] = array('1. 拼团成功前，开团人（团长）在拼团时限内不允许取消订单，参团人不允许取消订单。','2. 拼团成功后，开团人（团长）不得取消订单，参团人不允许取消订单。');

		}
		$arr['pin_rule'] = array('1.开团：选择可开团商品，点击“发起X人团”按钮，付款后即为开团成功;',
				'2.参团：进入朋友分享的页面，点击“立即参团”按钮，付款后即为参团成功，多人同时支付时，支付成功时间较早的人获得参团资格;',
				'3.成团：在开团或参团成功后，点击“分享团购”将页面分享给好友，凑齐人数即为成团，此时商家会开始接单;',
				'4.组团失败：在有效时间内未凑齐人数，即为组团失败，此时将自动退款；'
									);

		if($now_group['tuan_type'] != 2){
			$arr['now_group']['merchant_name']	=	$now_group['merchant_name'];
		}else{
			$arr['now_group']['merchant_name']	=	$now_group['s_name'];
		}
		if($now_group['cue_arr']){
			foreach($now_group['cue_arr'] as $v){
				if(!empty($v['value'])){
					$cue_arr[]	=	array(
							'key'	=>	$v['key'],
							'value'	=>	$v['value'],
					);
				}
			}
			$arr['cue_arr']	=	isset($cue_arr)?$cue_arr:array();
		}else{
			$arr['cue_arr']	=	array();
		}
		//	商家的其他店铺
		if($now_group['store_list']){
			foreach($now_group['store_list'] as $k=>$v){
				$arr['store_list'][]	=	array(
						'store_id'	=>	$v['store_id'],
						'name'	=>	$v['name'],
						'area_name'	=>	$v['area_name'],
						'adress'	=>	$v['adress'],
						'range'	=>	isset($v['range'])?$v['range']:'',
						'phone'	=>	$v['phone'],
				);
			}
		}else{
			$arr['store_list']	=	array();
		}
		//	还有多久开始时间
		$time = $now_group['begin_time'] - $_SERVER['REQUEST_TIME'];
		if($time > 0){
			$arr['now_group']['is_time']	=	1;		//时间按钮
			$time_array = ''.floor($time/86400).'天'.floor($time%86400/3600).'时'.floor($time%86400%3600/60).'分开团';
		}else if($now_group['end_time'] > $_SERVER['REQUEST_TIME'] && $now_group['begin_time'] < $_SERVER['REQUEST_TIME'] && $now_group['type'] == 1){
			if($now_group['is_appoint_bind']){
				$arr['now_group']['is_time']	=	2;	//立即预约
				$appUrl		=	$this->config['site_url'].U('Wap/Appoint/detail',array('appoint_id'=>$now_group['appoint_id']));
				$arr['now_group']['url']	=	str_replace('appapi.php','wap.php',$appUrl);
			}else{
				$arr['now_group']['is_time']	=	3;	//立即购买
			}
		}else{
			$arr['now_group']['is_time']	=	0;		//没有按钮
		}
		$arr['now_group']['time_array']	=	isset($time_array)?$time_array:'';
		$arr['now_group']['url']	=	isset($arr['now_group']['url'])?$arr['now_group']['url']:'';
		//	评论
		if($now_group['reply_count']){
			$reply_list = D('Reply')->get_reply_list($now_group['group_id'],0,count($now_group['store_list']),3);
		}
		if($reply_list){
			foreach($reply_list as $k=>$v){
				if($v['pics']){
					foreach($v['pics'] as $kk=>$vv){
						if($kk == 8){
							break;
						}else{
							$pics[]	=	$vv['m_image'];
						}
					}
				}
				$arr['reply_list'][]	=	array(
						'nickname'	=>	$v['nickname'],
						'add_time'	=>	$v['add_time'],
						'score'		=>	$v['score'],
						'comment'	=>	$v['comment'],
						'pics'		=>	isset($pics)?$pics:array(),
						'merchant_reply_content'	=>	$v['merchant_reply_content'],
				);
				unset($pics);
			}
		}else{
			$arr['reply_list']	=	array();
		}
		//	商家其他的团购
		$merchant_group_list = D('Group')->get_grouplist_by_MerchantId($now_group['mer_id'],3,true,$now_group['group_id']);
		if($merchant_group_list){
			foreach($merchant_group_list as $k=>$v){
				$arr['merchant_group_list'][]	=	array(
						'list_pic'	=>	$v['list_pic'],
						'group_id'	=>	$v['group_id'],
						'name'	=>	$v['name'],
						'group_name'	=>	$v['group_name'],
						'price'	=>	rtrim(rtrim(number_format($v['price'],2,'.',''),'0'),'.'),
						'wx_cheap'	=>	rtrim(rtrim(number_format($v['wx_cheap'],2,'.',''),'0'),'.'),
						'sale_count'	=>	$v['sale_count']+$v['virtual_num'],
						'pin_num'	=>	$v['pin_num'],
				);
			}
		}else{
			$arr['merchant_group_list']	=	array();
		}
		//	分类下其他团购，看了本团购的用户还看了
		$category_group_list = D('Group')->get_grouplist_by_catId($now_group['cat_id'],$now_group['cat_fid'],3,true);
		if($category_group_list){
			foreach($category_group_list as $key=>$value){
				if($value['group_id'] == $now_group['group_id']){
					unset($category_group_list[$key]);
				}else{
					$arr['category_group_list'][]	=	array(
							'list_pic'	=>	$value['list_pic'],
							'group_id'	=>	$value['group_id'],
							'name'	=>	$value['name'],
							'tuan_type'	=>	$value['tuan_type'],
							'group_name'	=>	$value['group_name'],
							'price'	=>	rtrim(rtrim(number_format($value['price'],2,'.',''),'0'),'.'),
							'wx_cheap'	=>	rtrim(rtrim(number_format($value['wx_cheap'],2,'.',''),'0'),'.'),
							'sale_count'	=>	$value['sale_count']+$value['virtual_num'],
					);
				}
			}
		}else{
			$arr['category_group_list']	=	array();
		}
		$arr['share']	=	array(
				'url'	=>	$this->config['site_url'].U('Wap/Group/detail',array('group_id'=>$now_group['group_id'])),
				'pic'	=>	$arr['now_group']['image'],
				'title'	=>	$arr['now_group']['merchant_name'],
				'content'=>	$arr['now_group']['group_name'],
		);
		$arr['share']['url']	=	str_replace('appapi.php','wap.php',$arr['share']['url']);
		$this->returnCode(0,$arr);
	}
}
?>