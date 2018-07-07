<?php
class ShowtableAction extends BaseAction{
	public function index(){
		$result = D('')->query("SELECT * FROM `information_schema`.`COLUMNS` WHERE `TABLE_SCHEMA` = 'o2o_demo'");
		$tableArr = array();
		foreach($result as $value){
			$tmpValue = $value;
			unset($tmpValue['TABLE_CATALOG']);
			unset($tmpValue['TABLE_SCHEMA']);
			unset($tmpValue['TABLE_NAME']);
			unset($tmpValue['COLUMN_NAME']);
			unset($tmpValue['PRIVILEGES']);
			$tableArr[$value['TABLE_NAME']][$value['COLUMN_NAME']] = $tmpValue;
		}
		
		$dataUpdateArr['table'] = $tableArr;
		
		//config表
		$config_group_arr = D('Config_group')->select();
		foreach($config_group_arr as $value){
			$dataUpdateArr['config_group'][$value['gid']] = $value;
		}
		
		$config_arr = D('Config')->select();
		foreach($config_arr as $value){
			$dataUpdateArr['config'][$value['name']] = $value;
		}
		
		//new_merchant_menu表
		$new_merchant_menu_arr = D('New_merchant_menu')->select();
		foreach($new_merchant_menu_arr as $value){
			$dataUpdateArr['new_merchant_menu'][$value['id']] = $value;
		}
		
		//system_menu表
		$system_menu_arr = D('System_menu')->select();
		foreach($system_menu_arr as $value){
			$dataUpdateArr['system_menu'][$value['id']] = $value;
		}
		
		
		file_put_contents('./runtime/table1.php','<?php return '.var_export($dataUpdateArr,true).' ;?>');
	}
	public function bidui(){
		$table1 = include('./runtime/table1.php');
		$table2 = include('./runtime/table2.php');
		
		foreach($table1['table'] as $key=>$value){
			if(strpos($key,'pigcms_cashier_') === 0) continue;
			if(strpos($key,'pigcms_app_') === 0) continue;
			if(strpos($key,'pigcms_scenic_') === 0) continue;
			if(strpos($key,'pigcms_fc_') === 0) continue;
			if(strpos($key,'pigcms_waimai_') === 0) continue;
			if(strpos($key,'pigcms_weidian_') === 0) continue;
			if(strpos($key,'pigcms_circle') === 0) continue;
			if(strpos($key,'pigcms_portal_') === 0) continue;
			if(strpos($key,'pigcms_index_group_hits_') === 0) continue;
			if(empty($table2['table'][$key])){
				$sql = D()->query("show create table " .$key);
				$table_array[$key] = $sql[0]['Create Table'].';';
				continue;
			}
			
			foreach($value as $k=>$v){
				if(empty($table2['table'][$key][$k])){
					$sql = D()->query("show create table " .$key);
					$table_sql = $sql[0]['Create Table'];
					$table_sql_arr = explode("\n",$table_sql);
					foreach($table_sql_arr as $tk => $tv){
						$tv = trim($tv);
						if(strpos($tv,'`'.$k.'`') === 0){
							$tv = rtrim($tv,',');
							$table_data_array[$key][$k] = 'ALTER TABLE `'.$key.'` ADD '.$tv.';';
							break;
						}
					}
				}
			}
		}
		
		echo '------------------------------ 表判断 ------------------------------'.'<br/><br/>';
		
		foreach($table_array as $value){
			echo $value.'<br/><br/>';
		}
		
		echo '<br/><br/>';		
		echo '------------------------------ 字段判断 ------------------------------'.'<br/><br/>';
		
		foreach($table_data_array as $value){
			foreach($value as $v){
				echo $v.'<br/><br/>';
			}
		}
		
		echo '<br/><br/>';
		echo '------------------------------ 配置分组判断 ------------------------------'.'<br/><br/>';
		foreach($table1['config_group'] as $key=>$value){
			$value2 = $table2['config_group'][$key];
			if(empty($value2)){
				if($value['gname'] == '外卖配置' || $value['gname'] == '多城市配置' || $value['gname'] == '推送配置' || $value['gname'] == 'APP支付分享配置' || $value['gname'] == '微店对接' || $value['gname'] == '景区配置' || $value['gname'] == 'APP包名'){
					continue;
				}
				echo "INSERT INTO `pigcms_config_group` (`gid`, `gname`, `galias`, `gsort`, `status`) VALUES(".$value['gid'].", '".$value['gname']."', '".$value['galias']."', ".$value['gsort'].", ".$value['status'].");".'<br/><br/>';
			}else if($value['gname'] != $value2['gname'] || $value['galias'] != $value2['galias'] || $value['gsort'] != $value2['gsort'] || $value['status'] != $value2['status']){
				echo "UPDATE `pigcms_config_group` SET `gname`='".$value['gname']."',`galias`='".$value['galias']."',`gsort`='".$value['gsort']."',`status`='".$value['status']."' WHERE `gid`='".$value['gid']."';".'<br/><br/>';
			}
		}
		
		
		echo '<br/><br/>';
		echo '------------------------------ 配置判断 ------------------------------'.'<br/><br/>';
		foreach($table1['config'] as $key=>$value){
			if(strpos($key,'pay_chinabank_') === 0) continue;
			if(strpos($key,'is_open_weidian') === 0) continue;
			if(strpos($key,'waimai_') === 0) continue;
			if(strpos($key,'live_service_') === 0) continue;
			if(strpos($key,'many_city') === 0) continue;
			if(strpos($key,'push_jpush_') === 0) continue;
			if(strpos($key,'pay_weixinapp_') === 0) continue;
			if(strpos($key,'weixin_push_jpush_') === 0) continue;
			if(strpos($key,'weidian_url') === 0) continue;
			if(strpos($key,'store_open_waimai') === 0) continue;
			if(strpos($key,'is_cashier') === 0) continue;
			if(strpos($key,'is_open_cashier') === 0) continue;
			if(strpos($key,'twice_verify') === 0) continue;
			if(strpos($key,'scenic_') === 0) continue;
			if(strpos($key,'pay_unionpay_') === 0) continue;
			if(strpos($key,'app_android_') === 0) continue;
			if(strpos($key,'app_ios_') === 0) continue;
			if(strpos($key,'wxapp_') === 0) continue;
			if(strpos($key,'pay_wxapp_') === 0) continue;
			if(strpos($key,'pay_jq_wxapp') === 0) continue;
			$value2 = $table2['config'][$key];
			if(empty($value2)){
				$insert_config_sql[] = "INSERT INTO `pigcms_config` (`name`, `type`, `value`, `info`, `desc`, `tab_id`, `tab_name`, `gid`, `sort`, `status`) VALUES ('".$value['name']."', '".$value['type']."', '".$value['value']."', '".$value['info']."', '".$value['desc']."', '".$value['tab_id']."', '".$value['tab_name']."', '".$value['gid']."', '".$value['sort']."', '".$value['status']."');";
			}else if($value['type'] != $value2['type'] || $value['info'] != $value2['info'] || $value['desc'] != $value2['desc'] || $value['tab_id'] != $value2['tab_id'] || $value['tab_name'] != $value2['tab_name'] || $value['gid'] != $value2['gid'] || $value['sort'] != $value2['sort'] || $value['status'] != $value2['status']){
				$update_config_sql[] = "UPDATE `pigcms_config` SET `type`='".$value['type']."',`info`='".$value['info']."',`desc`='".$value['desc']."',`tab_id`='".$value['tab_id']."',`tab_name`='".$value['tab_name']."',`gid`='".$value['gid']."',`sort`='".$value['sort']."',`status`='".$value['status']."' WHERE `name`='".$value['name']."';";
			}
		}
		foreach($insert_config_sql as $value){
			echo str_replace(array('<','>'),array('&lt;','&gt;'),$value).'<br/><br/>';
		}
		foreach($update_config_sql as $value){
			echo str_replace(array('<','>'),array('&lt;','&gt;'),$value).'<br/><br/>';
		}
		
		
		echo '<br/><br/>';
		echo '------------------------------ 商家左侧菜单 ------------------------------'.'<br/><br/>';
		foreach($table1['new_merchant_menu'] as $key=>$value){
			$value2 = $table2['new_merchant_menu'][$key];
			if(empty($value2)){
				if($value['fid'] == '70') continue;
				if($value['id'] == '62') continue;
				if($value['fid'] == '62') continue;
				if(strpos($value['name'],'微店') === 0) continue;
				if(strpos($value['name'],'商家微店对账') === 0) continue;
				if(strpos($value['module'],'Scenic') === 0) continue;
				echo "INSERT INTO `pigcms_new_merchant_menu` (`id` ,`fid` ,`name` ,`module` ,`action` ,`select_module` ,`select_action` ,`icon` ,`sort` ,`show` ,`status`)VALUES ('".$value['id']."', '".$value['fid']."', '".$value['name']."', '".$value['module']."', '".$value['action']."', '".$value['select_module']."', '".$value['select_action']."', '".$value['icon']."', '".$value['sort']."', '".$value['show']."', '".$value['status']."');".'<br/><br/>';
			}else if($value['gname'] != $value2['gname'] || $value['galias'] != $value2['galias'] || $value['gsort'] != $value2['gsort'] || $value['status'] != $value2['status']){
				// echo "UPDATE `pigcms_config_group` SET `gname`='".$value['gname']."',`galias`='".$value['galias']."',`gsort`='".$value['gsort']."',`status`='".$value['status']."' WHERE `gid`='".$value['gid']."';".'<br/><br/>';
			}
		}
		
		
		echo '<br/><br/>';
		echo '------------------------------ 系统左侧菜单 ------------------------------'.'<br/><br/>';
		foreach($table1['system_menu'] as $key=>$value){
			$value2 = $table2['system_menu'][$key];
			if(empty($value2)){
				if($value['module'] == 'Waimai') continue;
				if($value['name'] == 'APP版本管理') continue;
				if($value['name'] == '极光推送群发') continue;
				if($value['name'] == '数据分析2') continue;
				if($value['id'] == '96') continue;
				if($value['fid'] == '96') continue;
				if($value['id'] == '146') continue;
				if($value['fid'] == '146') continue;
				if($value['id'] == '159') continue;
				if($value['fid'] == '159') continue;
				if($value['fid'] == '70') continue;
				if($value['id'] == '199') continue;
				if($value['fid'] == '199') continue;
				if($value['id'] == '204') continue;
				if($value['fid'] == '204') continue;
				$insert_system_menu_sql[] = "INSERT INTO `pigcms_system_menu` (`id`, `fid`, `name`, `module`, `action`, `sort`, `show`, `status`, `area_access`) VALUES(".$value['id'].", ".$value['fid'].", '".$value['name']."', '".$value['module']."', '".$value['action']."', ".$value['sort'].", ".$value['show'].", ".$value['status'].", ".$value['area_access'].");";
			}else if($value['fid'] != $value2['fid'] || $value['name'] != $value2['name'] || $value['module'] != $value2['module'] || $value['action'] != $value2['action'] || $value['sort'] != $value2['sort'] || $value['show'] != $value2['show'] || $value['status'] != $value2['status'] || $value['area_access'] != $value2['area_access']){
				$update_system_menu_sql[] = "UPDATE `pigcms_system_menu` SET `fid`='".$value['fid']."',`name`='".$value['name']."',`module`='".$value['module']."',`action`='".$value['action']."',`sort`='".$value['sort']."',`show`='".$value['show']."',`status`='".$value['status']."',`area_access`='".$value['area_access']."' WHERE `id`='".$value['id']."';";
			}
		}
		
		foreach($insert_system_menu_sql as $value){
			echo str_replace(array('<','>'),array('&lt;','&gt;'),$value).'<br/><br/>';
		}
		foreach($update_system_menu_sql as $value){
			echo str_replace(array('<','>'),array('&lt;','&gt;'),$value).'<br/><br/>';
		}
	}
}