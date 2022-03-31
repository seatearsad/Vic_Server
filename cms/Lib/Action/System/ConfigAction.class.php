<?php
/*
 * 修改站点配置
 *
 */
class ConfigAction extends BaseAction {
    public function index(){
		$database_config_group = D('Config_group');
		if(empty($_GET['galias'])){
			$condition_config_group['status'] = '1';
		}
        //禁止改动项
		$condition_config_group['gid'] = array('not in',array(45,46,47));

		$group_list = $database_config_group->field(true)->where($condition_config_group)->order('`gsort` DESC,`gid` ASC')->select();
		foreach($group_list as &$gListValue){
			$gListValue['gname'] = str_replace('订餐',$this->config['meal_alias_name'],$gListValue['gname']);
			$gListValue['gname'] = str_replace('团购',$this->config['group_alias_name'],$gListValue['gname']);
			$gListValue['gname'] = str_replace('预约',$this->config['appoint_alias_name'],$gListValue['gname']);
			$gListValue['gname'] = str_replace('礼品',$this->config['gift_alias_name'],$gListValue['gname']);
		}
		$this->assign('group_list',$group_list);
		if(empty($_GET['galias'])){	
			$gid = $this->_get('gid');
		}else{
			foreach($group_list as $value){
				if($value['galias'] == $_GET['galias']){
					$gid = $value['gid'];
					break;
				}
			}
			$header_file = $_GET['header'];
			$this->assign('header_file',$header_file);
		}
		if(empty($gid)) $gid = $group_list[0]['gid'];
		$this->assign('gid',$gid);
		$this->assign('galias', $_GET['galias']);
		
		$database_config = D('Config');
		$condition_config['gid'] = $gid;
		$condition_config['status'] = '1';
		$tmp_config_list = $database_config->where($condition_config)->order('`sort` DESC')->select();

		foreach($tmp_config_list as $key=>$value){
			$value['info'] = str_replace('订餐',$this->config['meal_alias_name'],$value['info']);
			$config_list[$value['tab_id']]['name'] = $value['tab_name'];
			$config_list[$value['tab_id']]['list'][] = $value;
		}
		$this->assign($this->build_html($config_list));

		if(!empty($_GET['galias'])){
			import('ORG.Util.Dir');
			Dir::delDirnotself('./runtime/Cache/System/');
		}

        $this->assign('module_name','System');
		$this->display();
	}
	public function amend(){
		if(IS_POST){
			$database_config = D('Config');
			foreach($_POST as $key=>$value){
				$data['name'] = $key;
				if(is_array($value)){
					if($key == 'delivery_time' || $key == 'delivery_time2'){
						$data['value'] = implode('-',$value);
					}
				}else{
					$data['value'] = trim(stripslashes(htmlspecialchars_decode($value)));
				}
				$database_config->data($data)->save();
				if ($key == 'wechat_sourceid') {
					$data['name'] = 'wechat_token';
					$data['value'] = md5('pigcms_wechat_token' . $data['value']);
					$database_config->data($data)->save();
				}
				if($key == 'weidian_url' && !file_put_contents('./api/weidian.urls',$data['value'])){
					$this->error('配置保存失败，请检查网站根目录下的api文件夹是否拥有可写权限。');
				}
				if($key == 'appoint_site_url' && !file_put_contents('./api/appoint.urls',$data['value'])){
					$this->error('配置保存失败，请检查网站根目录下的api文件夹是否拥有可写权限。');
				}
				if($key == 'portal_switch' && !file_put_contents('./api/default.urls',$data['value'])){
					$this->error('配置保存失败，请检查网站根目录下的api文件夹是否拥有可写权限。');
				}
			}
			S(C('now_city').'config',null);
				
			if(str_replace(array('.gov.cn','.com.cn','.weihubao.com','.dazhongbanben.com'),'',$_SERVER['HTTP_HOST']) == $_SERVER['HTTP_HOST']){
				$top_domain = $domainArr[$count-2].'.'.$domainArr[$count-1];
			}else{
				$top_domain = $domainArr[$count-3].'.'.$domainArr[$count-2].'.'.$domainArr[$count-1];
			}
			$top_domain = strtolower($top_domain);
			unlink('./source/plan/'.$top_domain.'md5.php');
			unlink('./source/plan/time/'.$top_domain.'process.time');
			
			$this->success('Success');
		}else{
			$this->error('非法提交,请重新提交~');
		}
	}
	
	public function show(){
		$this->assign('bg_color','#F3F3F3');
		$config = D('Config')->get_config();
		$this->display();
	}
	protected function build_html($config_list){
		if(is_array($config_list)){
			$config_html = '';
			if(count($config_list) > 1) $has_tab = true;
			if($has_tab) $config_tab_html = '<ul class="tab_ul">';
			$pigcms_auto_key = 1;
			foreach($config_list as $pigcms_key=>$pigcms_value){
				if($has_tab) $config_tab_html .= '<li '.($pigcms_auto_key==1 ? 'class="active"' : '').'><a data-toggle="tab" href="#tab_'.$pigcms_key.'">'.$pigcms_value['name'].'</a></li>';
				
				$config_html .= '<table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_'.$pigcms_key.'">';
				foreach($pigcms_value['list'] as $key=>$value){
					$tmp_type_arr = explode('&',$value['type']);
					$type_arr = array();
					foreach($tmp_type_arr as $k=>$v){
						$tmp_value = explode('=',$v);
						$type_arr[$tmp_value[0]] = $tmp_value[1];
					}
					
					$config_html .= '<tr>';
					$config_html .= '<th width="160">'.$value['info'].'：</th>';
					$config_html .= '<td>';
					if($type_arr['type'] == 'text'){
						$size = !empty($type_arr['size']) ? $type_arr['size'] : '60';
						$config_html .= '<input type="text" class="input-text" name="'.$value['name'].'" id="config_'.$value['name'].'" value="'.$value['value'].'" size="'.$size.'" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'"/>';
					}else if($type_arr['type'] == 'textarea'){
						$rows = !empty($type_arr['rows']) ? $type_arr['rows'] : '4';
						$cols = !empty($type_arr['cols']) ? $type_arr['cols'] : '80';
						$config_html .= '<textarea rows="'.$rows.'" cols="'.$cols.'" name="'.$value['name'].'" id="config_'.$value['name'].'" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'">'.$value['value'].'</textarea>';
					}else if($type_arr['type'] == 'radio'){
						$radio_option = explode('|',$type_arr['value']);
						foreach($radio_option as $radio_k=>$radio_v){
							$radio_one = explode(':',$radio_v);
							if($radio_k == 0){
								$config_html .= '<span class="cb-enable"><label class="cb-enable '.($value['value']==$radio_one[0] ? 'selected' : '').'"><span>'.$radio_one[1].'</span><input type="radio" name="'.$value['name'].'" value="'.$radio_one[0].'" '.($value['value']==$radio_one[0] ? 'checked="checked"' : '').'/></label></span>';
							}else if($radio_k == 1){
								$config_html .= '<span class="cb-disable"><label class="cb-disable '.($value['value']==$radio_one[0] ? 'selected' : '').'"><span>'.$radio_one[1].'</span><input type="radio" name="'.$value['name'].'" value="'.$radio_one[0].'" '.($value['value']==$radio_one[0] ? 'checked="checked"' : '').'/></label></span>';
							}
						}
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}else if($type_arr['type'] == 'image'){
						$config_html .= '<span class="config_upload_image_btn"><input type="button" value="上传图片" class="button" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-image" name="'.$value['name'].'" id="config_'.$value['name'].'" value="'.$value['value'].'" size="48" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'"/> ';
					}else if($type_arr['type'] == 'file'){
						$config_html .= '<span class="config_upload_file_btn" file_validate="'.$type_arr['file'].'"><input type="button" value="上传文件" class="button" style="margin-left:0px;margin-right:10px;"/></span><input type="text" class="input-text input-file" name="'.$value['name'].'" id="config_'.$value['name'].'" value="'.$value['value'].'" size="48" readonly="readonly" validate="'.$type_arr['validate'].'" tips="'.$value['desc'].'"/> ';
					}else if($type_arr['type'] == 'select'){
						$radio_option = explode('|',$type_arr['value']);
						$config_html .= '<select name="'.$value['name'].'">';
						foreach($radio_option as $radio_k=>$radio_v){
							$radio_one = explode(':',$radio_v);							
							$config_html .= '<option value="'.$radio_one[0].'" '.($value['value']==$radio_one[0] ? 'selected="selected"' : '').'>'.$radio_one[1].'</option>';						
						}
						$config_html .= '</select>';
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}else if($type_arr['type'] == 'twoTime'){
						$tmpTime = explode('-',$value['value']);
						$config_html .= '<input type="text" class="input-text" name="'.$value['name'].'[]" value="'.$tmpTime[0].'" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:\''.$type_arr['format'].'\'})"/>&nbsp;-&nbsp;&nbsp;';
						$config_html .= '<input type="text" class="input-text" name="'.$value['name'].'[]" value="'.$tmpTime[1].'" size="5" onfocus="WdatePicker({isShowClear:false,dateFmt:\''.$type_arr['format'].'\'})"/>';
					}else if($type_arr['type'] == 'webTpl'){
						$hostArr = parse_url($this->config['site_url']);
						import('ORG.Util.Dir');
						$dirObj = new Dir(TMPL_PATH.C('DEFAULT_GROUP'));
						$radio_option = array();
						foreach($dirObj->_values as $dirValue){
							if($dirValue['isDir']){
								$tpl_theme_ini = parse_ini_file($dirValue['pathname'].'/theme.ini');
								if($tpl_theme_ini){
									$tmpArr = array(
											'name'=>$tpl_theme_ini['name'],
											'path'=>$tpl_theme_ini['path'],
											'isUse'=> ($tpl_theme_ini['path'] == $value['value'] ? true : false)
										);
									if(empty($tpl_theme_ini['domain']) || $tpl_theme_ini['domain'] == $hostArr['host']){
										array_push($radio_option,$tmpArr);
									}
								}
							}
						}
						$config_html .= '<select name="'.$value['name'].'">';
						foreach($radio_option as $radio_k=>$radio_v){					
							$config_html .= '<option value="'.$radio_v['path'].'" '.($radio_v['isUse'] ? 'selected="selected"' : '').'>'.$radio_v['name'].'</option>';						
						}
						$config_html .= '</select>';
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}else if($type_arr['type'] == 'wapTpl'){
						$hostArr = parse_url($this->config['site_url']);
						import('ORG.Util.Dir');
						$dirObj = new Dir(TMPL_PATH.'Wap/');
						$radio_option = array();
						foreach($dirObj->_values as $dirValue){
							if($dirValue['isDir']){
								$tpl_theme_ini = parse_ini_file($dirValue['pathname'].'/theme.ini');
								if($tpl_theme_ini){
									$tmpArr = array(
											'name'=>$tpl_theme_ini['name'],
											'path'=>$tpl_theme_ini['path'],
											'isUse'=> ($tpl_theme_ini['path'] == $value['value'] ? true : false)
										);
									if(empty($tpl_theme_ini['domain']) || $tpl_theme_ini['domain'] == $hostArr['host']){
										array_push($radio_option,$tmpArr);
									}
								}
							}
						}
						$config_html .= '<select name="'.$value['name'].'">';
						foreach($radio_option as $radio_k=>$radio_v){					
							$config_html .= '<option value="'.$radio_v['path'].'" '.($radio_v['isUse'] ? 'selected="selected"' : '').'>'.$radio_v['name'].'</option>';						
						}
						$config_html .= '</select>';
						if($value['desc']){
							$config_html .= '<em tips="'.$value['desc'].'" class="notice_tips"></em>';
						}
					}
					$config_html .= '</td>';
					$config_html .= '</tr>';
				}
				$config_html .= '</table>';
				$pigcms_auto_key++;
			}
			if($has_tab) $config_tab_html .= '</ul>';
			
			$return_config['config_html'] = $config_html;
			if($has_tab) $return_config['config_tab_html'] = $config_tab_html;
			return $return_config;
		}
	}
	
	public function ajax_upload_pic(){
		if($_FILES['imgFile']['error'] != 4){
			
			$image = D('Image')->handle($this->system_session['id'], 'config', 0, array('size' => 3), false);
			if (!$image['error']) {
				exit(json_encode(array('error' => 0,'url' => $image['url']['imgFile'], 'title' => $image['title']['imgFile'])));
			}
			exit(json_encode($image));
			
// 			$img_admin_id = sprintf("%09d", $this->system_session['id']);
// 			$rand_num = substr($img_admin_id,0,3) . '/' . substr($img_admin_id,3,3) . '/' . substr($img_admin_id,6,3);
// 			$upload_dir = "./upload/images/{$rand_num}/";
// 			if(!is_dir($upload_dir)){
// 				mkdir($upload_dir,0777,true);
// 			}
// 			$upload->maxSize = 3*1024*1024;
// 			$upload->allowExts = array('jpg','jpeg','png','gif');
// 			$upload->savePath = $upload_dir;
// 			$upload->saveRule = 'uniqid';
// 			if($upload->upload()){
// 				$title = $rand_num.','.$uploadList[0]['savename'];
// 				exit(json_encode(array('error' => 0,'url' => '/upload/images/'.$rand_num.'/'.$uploadList[0]['savename'],'title'=>$title)));
// 			}else{
// 				exit(json_encode(array('error' => 1,'message' =>$upload->getErrorMsg())));
// 			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择图片')));
		}
	}
	public function ajax_upload_file(){
		if(empty($_GET['name'])){
			exit(json_encode(array('error' => 1,'message' =>'不知道您要上传到哪个配置项，请重试。')));
		}
		$now_config = D('Config')->field('`name`,`type`')->where(array('name'=>$_GET['name']))->find();
		if(empty($now_config)){
			exit(json_encode(array('error' => 1,'message' =>'您正在上传的配置项不存在，请重试。')));
		}
		$tmp_type_arr = explode('&',$now_config['type']);
		$type_arr = array();
		foreach($tmp_type_arr as $k=>$v){
			$tmp_value = explode('=',$v);
			$type_arr[$tmp_value[0]] = $tmp_value[1];
		}
		$allowExts = array_key_exists('file',$type_arr) ? explode(',',$type_arr['file']) : array();	
		if($_FILES['imgFile']['error'] != 4){
			
// 			$image = D('Image')->handle($this->system_session['id'], 'files');
// 			if (!$image['error']) {
// 				exit(json_encode(array('error' => 0,'url' => $image['url']['imgFile'],'title' => $image['title']['imgFile'])));
// 			} else {
// 				exit(json_encode(array('error' => 1,'message' => $image['msg'])));
// 			}
			
			$img_admin_id = sprintf("%09d", $this->system_session['id']);
			$rand_num = substr($img_admin_id,0,3) . '/' . substr($img_admin_id,3,3) . '/' . substr($img_admin_id,6,3);
			$upload_dir = "./upload/files/{$rand_num}/";
			if(!is_dir($upload_dir)){
				mkdir($upload_dir,0777,true);
			}
			import('ORG.Net.UploadFile');
			$upload = new UploadFile();
			$upload->maxSize = 10*1024*1024;
			$upload->allowExts = $allowExts;
			$upload->savePath = $upload_dir;
			$upload->saveRule = 'uniqid';
			if($upload->upload()){
				$uploadList = $upload->getUploadFileInfo();				
				$title = $rand_num.','.$uploadList[0]['savename'];
				exit(json_encode(array('error' => 0,'url' =>'./upload/files/'.$rand_num.'/'.$uploadList[0]['savename'],'title'=>$title)));
			}else{
				exit(json_encode(array('error' => 1,'message' =>$upload->getErrorMsg())));
			}
		}else{
			exit(json_encode(array('error' => 1,'message' =>'没有选择文件')));
		}
	}
	public function live_service(){
		if (empty($this->config['site_url'])) {
			exit(json_encode(array('error_code' => true, 'msg' => '先填写您网站的域名')));
		}
		$live_service = new live_service();
		$live_service->create();
	}
    public function im()
    {
        if (empty($this->config["wechat_appid"]) || empty($this->config["wechat_appsecret"])) {
            exit(json_encode(array("error_code" => true, "msg" => "先设置站点的微信公众号信息")));
        }
        import("ORG.Net.Http");
        $http = new Http();
        require 'jiaoyou/cms.php';
        $return = Http::curlPost($jiaoyou->access(),array("url"=>$_SERVER['SERVER_NAME']));
        if ($return["err_code"]) {
            exit(json_encode(array("error_code" => true, "msg" => $return["err_code"])));
        }
        else {
            if (D("Config")->where("`name`='im_appid'")->find()) {
                D("Config")->where("`name`='im_appid'")->save(array("value" => $return["appid"]));
            }
            else {
                D("Config")->add(array("name" => "im_appid", "value" => $return["appid"], "gid" => 0, "status" => 1));
            }

            if (D("Config")->where("`name`='im_key'")->find()) {
                D("Config")->where("`name`='im_key'")->save(array("value" => $return["key"]));
            }
            else {
                D("Config")->add(array("name" => "im_key", "value" => $return["key"], "gid" => 0, "status" => 1));
            }
            S(C("now_city") . "config", NULL);
            exit(json_encode(array("error_code" => false, "msg" => "获取成功")));
        }
    }

    public function message(){
        $where = array();
        if(isset($_GET['type_select']) && $_GET['type_select'] != -1){
            $where['type'] = $_GET['type_select'];
            $this->assign('type',$_GET['type_select']);
        }else{
            $this->assign('type',-1);
        }
        if($_GET['city_select'] && $_GET['city_select'] != 0){
            $where['city_id'] = $_GET['city_select'];
            $this->assign('city_id',$_GET['city_select']);
        }else{
            $this->assign('city_id',0);
        }

        $area_list = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$area_list);

        $list = D('System_message')->where($where)->order('id desc')->select();

        $nomalArr = array();
        $expireArr = array();
        $inactiveArr = array();
        foreach ($list as &$value) {
            if($value['city_id'] == 0){
                $value['city_name'] = L('G_UNIVERSAL');
            }else{
                $value['city_name'] = D("Area")->where(array('area_id'=>$value['city_id']))->find()['area_name'];
            }

            $value['platform'] = "";
            if($value['is_wap'] == 1) $value['platform'] = "Wap";
            if($value['is_ios'] == 1) $value['platform'] .= $value['platform'] == "" ? "iOS" : "<br>iOS";
            if($value['is_android'] == 1) $value['platform'] .= $value['platform'] == "" ? "Android" : "<br>Android";

            switch ($value['status']){
                case 0:
                    $inactiveArr[] = $value;
                    break;
                case 1:
                    $nomalArr[] = $value;
                    break;
                case 2:
                    $expireArr[] = $value;
                    break;
                default:
                    break;
            }
        }

        $list = array_merge($nomalArr,$inactiveArr,$expireArr);

        $this->assign('message_list',$list);
        $this->assign('module_name','System');
        $this->display();
    }

    public function add_message(){
        $area_list = D('Area')->where(array('area_type'=>2,'is_open'=>1))->select();
        $this->assign('city',$area_list);

        if($_GET['id']){
            $message = D('System_message')->where(array('id'=>$_GET['id']))->find();

            $this->assign('message',$message);
        }
        $this->display();
    }

    public function modify_message(){
        if($_POST['begin_time'] != "") $_POST['begin_time'] = strtotime($_POST['begin_time']. " 00:00:00");
        if($_POST['end_time'] != "") $_POST['end_time'] = strtotime($_POST['end_time']. " 23:59:59");

        $_POST['name'] = htmlspecialchars_decode($_POST['name']);
        $_POST['link'] = htmlspecialchars_decode($_POST['link']);

        if($_POST['type'] == 1){
            $_POST['content'] = $_POST['content_img'];
        }else{
            $_POST['content'] = htmlspecialchars_decode($_POST['content']);
        }

        if($_POST['id'] == 0){
            D('System_message')->add($_POST);
        }else{
            D('System_message')->where(array('id'=>$_POST['id']))->save($_POST);
        }

        $this->frame_submit_tips(1, 'Success!');
    }
}