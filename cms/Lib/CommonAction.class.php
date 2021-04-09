<?php
class CommonAction extends Action 
{
	protected $user_session;
	protected $config;
	protected $common_url;
	protected $static_path;
	protected $static_public;
	protected $user_level;
	protected function _initialize() 
	{

		$serverHost = '';
		if (function_exists('getallheaders')) 
		{
			$allheaders = getallheaders();
			$serverHost = $allheaders['Host'];
		}
		if (empty($serverHost)) 
		{
			$serverHost = $_SERVER['HTTP_HOST'];
		}

		$this->config = D('Config')->get_config();
		if ((GROUP_NAME != 'Wap') && (($this->config['site_close'] == 1) || ($this->config['site_close'] == 3))) 
		{
			$this->assign('title', '网站关闭');
			$this->assign('jumpUrl', '-1');
			$this->error(($this->config['site_close_reason'] ? $this->config['site_close_reason'] : '网站临时关闭'));
		}
		if ($this->config['many_city'] && ((GROUP_NAME != 'Wap') || (MODULE_NAME != 'Chat'))) 
		{
			ini_set('session.cookie_domain', $this->config['many_city_top_domain']);
			$database_area = D('Area');
			if (($this->config['many_city_main_domain'] . '.' . $this->config['many_city_top_domain']) == $_SERVER['HTTP_HOST']) 
			{
				if ((GROUP_NAME == 'Index') && (MODULE_NAME == 'Index') && (ACTION_NAME == 'index')) 
				{
					if ($_COOKIE['now_city']) 
					{
						$now_city = S('area_url_' . $_COOKIE['now_city']);
						if (empty($now_city)) 
						{
							$now_city = $database_area->field('`area_id`,`area_name`,`area_type`,`area_url`')->where(array('area_url' => $_COOKIE['now_city'], 'is_open' => '1'))->find();
							if (!empty($now_city) && ($now_city['area_type'] == 2)) 
							{
								S('area_url_' . $_COOKIE['now_city'], $now_city);
							}
						}
						if (empty($now_city) || ($now_city['area_type'] != 2)) 
						{
							redirect('http://' . $this->config['many_city_main_domain'] . '.' . $this->config['many_city_top_domain'] . '/index.php?c=Changecity');
						}
						else 
						{
							redirect('http://' . $now_city['area_url'] . '.' . $this->config['many_city_top_domain']);
						}
					}
					else 
					{
						import('ORG.Net.IpLocation');
						$Ip = new IpLocation('UTFWry.dat');
						$area = $Ip->getlocation();
						$city = iconv('gbk', 'utf-8', $area['country']);
						$now_city = S('area_ip_desc_' . $city);
						if (empty($now_city) && !empty($city)) 
						{
							$condition_now_city['area_type'] = '2';
							$condition_now_city['area_ip_desc'] = $city;
							$condition_now_city['is_open'] = '1';
							$now_city = $database_area->field('`area_id`,`area_name`,`area_url`')->where($condition_now_city)->find();
							if (!empty($now_city)) 
							{
								S('area_ip_desc_' . $city, $now_city);
							}
						}
						if (empty($now_city)) 
						{
							redirect('http://' . $this->config['many_city_main_domain'] . '.' . $this->config['many_city_top_domain'] . '/index.php?c=Changecity');
						}
						else 
						{
							setcookie('now_city', $now_city['area_url'], $_SERVER['REQUEST_TIME'] + 72000000, '/', '.' . $this->config['many_city_top_domain']);
							redirect('http://' . $now_city['area_url'] . '.' . $this->config['many_city_top_domain']);
						}
					}
				}
				else 
				{
					if ($_COOKIE['now_city']) 
					{
						$now_city = S('area_url_' . $_COOKIE['now_city']);
						if (empty($now_city)) 
						{
							$now_city = $database_area->field('`area_id`,`area_name`,`area_type`,`area_url`')->where(array('area_url' => $_COOKIE['now_city'], 'is_open' => '1'))->find();
							if (!empty($now_city) && ($now_city['area_type'] == 2)) 
							{
								S('area_url_' . $_COOKIE['now_city'], $now_city);
							}
						}
					}
					else 
					{
						import('ORG.Net.IpLocation');
						$Ip = new IpLocation('UTFWry.dat');
						$area = $Ip->getlocation();
						$city = iconv('gbk', 'utf-8', $area['country']);
						$now_city = S('area_ip_desc_' . $city);
						if (empty($now_city) && !empty($city)) 
						{
							$condition_now_city['area_type'] = '2';
							$condition_now_city['area_ip_desc'] = $city;
							$condition_now_city['is_open'] = '1';
							$now_city = $database_area->field('`area_id`,`area_name`,`area_url`')->where($condition_now_city)->find();
							if (!empty($now_city)) 
							{
								S('area_ip_desc_' . $city, $now_city);
							}
						}
					}
					if (empty($now_city)) 
					{
						$now_city = $database_area->field('`area_id`,`area_name`,`area_url`')->where(array('area_id' => $this->config['now_city']))->find();
					}
					$this->config['now_select_city'] = $now_city;
					$this->assign('now_select_city', $now_city);
					$this->config['now_site_url'] = 'http://' . $now_city['area_url'] . '.' . $this->config['many_city_top_domain'];
					$this->config['now_city'] = $now_city['area_id'];
					setcookie('now_city', $now_city['area_url'], $_SERVER['REQUEST_TIME'] + 72000000, '/', '.' . $this->config['many_city_top_domain']);
				}
			}
			else if ((GROUP_NAME == 'Wap') && IS_GET) 
			{
				header('Location: http://' . $this->config['many_city_main_domain'] . '.' . $this->config['many_city_top_domain'] . $_SERVER['REQUEST_URI']);
				exit();
			}
			else 
			{
				if (defined('appoint_domain')) 
				{
					if ($_COOKIE['now_city']) 
					{
						$area_url = $_COOKIE['now_city'];
					}
					else 
					{
						$now_city = $database_area->field('`area_url`')->where(array('area_url' => $this->config['now_city']))->find();
						$area_url = $now_city['area_url'];
					}
				}
				else 
				{
					$area_url = rtrim(str_replace($this->config['many_city_top_domain'], '', $_SERVER['HTTP_HOST']), '.');
				}
				$now_select_city = S('area_url_' . $area_url);
				if (empty($now_select_city)) 
				{
					$now_select_city = $database_area->field('`area_id`,`area_name`,`area_type`,`area_url`')->where(array('area_url' => $area_url, 'is_open' => '1'))->find();
					if (!empty($now_select_city) && ($now_select_city['area_type'] == 2)) 
					{
						S('area_url_' . $area_url, $now_select_city);
					}
				}
				if (empty($now_select_city) || ($now_select_city['area_type'] != 2)) 
				{
					redirect('http://' . $this->config['many_city_main_domain'] . '.' . $this->config['many_city_top_domain'] . '/index.php?c=Changecity');
				}
				else 
				{
					$this->config['now_select_city'] = $now_select_city['area_id'];
					setcookie('now_city', $now_select_city['area_url'], $_SERVER['REQUEST_TIME'] + 72000000, '/', '.' . $this->config['many_city_top_domain']);
					C('now_city', $now_select_city['area_id']);
				}
				$this->assign('now_select_city', $now_select_city);
				$this->config['now_site_url'] = 'http://' . $now_select_city['area_url'] . '.' . $this->config['many_city_top_domain'];
				$this->config['now_city'] = $now_select_city['area_id'];
			}
			$this->config['seo_title'] = str_replace('{city}', $now_select_city['area_name'], $this->config['seo_title']);
			$this->config['seo_keywords'] = str_replace('{city}', $now_select_city['area_name'], $this->config['seo_keywords']);
			$this->config['seo_description'] = str_replace('{city}', $now_select_city['area_name'], $this->config['seo_description']);
			$this->config['flseo_title'] = str_replace('{city}', $now_select_city['area_name'], $this->config['flseo_title']);
			$this->config['flseo_keywords'] = str_replace('{city}', $now_select_city['area_name'], $this->config['flseo_keywords']);
			$this->config['flseo_description'] = str_replace('{city}', $now_select_city['area_name'], $this->config['flseo_description']);
		}
		$this->config['config_site_url'] = $this->config['site_url'];
		$this->config['site_url'] = 'http://' . $_SERVER['HTTP_HOST'];
		$this->assign('config', $this->config);
		if (strtolower(GROUP_NAME) == 'scenic') 
		{
			C('DEFAULT_THEME', 'default');
		}
		else if ((strtolower(GROUP_NAME) != 'wap') && !C('THEME_LOCK') && $this->config['site_web_tpl']) 
		{
			C('DEFAULT_THEME', $this->config['site_web_tpl']);
		}
		else if ((strtolower(GROUP_NAME) == 'wap') && !C('THEME_LOCK') && $this->config['site_wap_tpl']) 
		{
			if ($this->config['site_wap_tpl'] == 'pure') 
			{
				if ($_COOKIE['lowPhoneVersion'] != '1') 
				{
					if (!file_exists(TMPL_PATH . 'Wap/pure/' . MODULE_NAME . '/' . ACTION_NAME . C('TMPL_TEMPLATE_SUFFIX'))) 
					{
						$this->assign('no_footer', true);
						C('DEFAULT_THEME', 'default');
					}
					else 
					{
						C('DEFAULT_THEME', $this->config['site_wap_tpl']);
					}
				}
				else 
				{
					C('DEFAULT_THEME', 'default');
				}
			}
			else 
			{
				C('DEFAULT_THEME', $this->config['site_wap_tpl']);
			}
		}
		C('config', $this->config);
		session_start();
		$this->user_session = session('user');
		$this->assign('user_session', $this->user_session);
		$levelDb = M('User_level');
		$tmparr = $levelDb->field(true)->order('`id` ASC')->select();
		$levelarr = array();
		if ($tmparr) 
		{
			foreach ($tmparr as $vv ) 
			{
				$levelarr[$vv['level']] = $vv;
			}
		}
		$this->user_level = $levelarr;
		unset($tmparr);
		unset($levelarr);
		$this->assign('levelarr', $this->user_level);
		$this->common_url['group_category_all'] = $this->config['site_url'] . '/category/all/all';
		$this->static_path = $this->config['site_url'] . '/tpl/Static/' . C('DEFAULT_THEME') . '/';
		$this->static_public = $this->config['site_url'] . '/static/';
		$this->assign('static_path', $this->static_path);
		$this->assign('static_public', $this->static_public);
		$this->assign($this->common_url);
		if ($_GET['no_house']) 
		{
			setcookie('is_house', 0, time() - (3600 * 24));
			setcookie('house_village_id', 0, time() - (3600 * 24));
			unset($_COOKIE['is_house']);
			unset($_COOKIE['house_village_id']);
		}
		if (defined('IS_HOUSE')) 
		{
			if (!cookie('is_house')) 
			{
				setcookie('is_house', 1, time() + (3600 * 24));
				$house_village_list = D('House_village')->where(array('status' => 1))->select();
				if (count($house_village_list) == 1) 
				{
					setcookie('house_village_id', $house_village_list[0]['village_id'], time() + (3600 * 24));
					redirect(str_replace('wap.php', 'wap_house.php', U('House/village', array('village_id' => $house_village_list[0]['village_id']))));
				}
				redirect(str_replace('wap.php', 'wap_house.php', U('House/village_list')));
			}
			if (cookie('house_village_id')) 
			{
				redirect(str_replace('wap.php', 'wap_house.php', U('House/village', array('village_id' => cookie('house_village_id')))));
			}
		}
		else if ($_GET['no_house']) 
		{
			setcookie('is_house', 0, time() - (3600 * 24));
		}
		else if (cookie('is_house')) 
		{
			if (stripos('wap.php', __SELF__) !== false) 
			{
				redirect(str_replace('wap.php', 'wap_house.php', __SELF__));
			}
		}
		include './source/plan/index.php';
	}
	public function _empty() 
	{
		$this->error_tips('此页面不存在', $this->config['site_url']);
	}
	protected function get_uri_param() 
	{
		$uri_arr = explode('?', $_SERVER['REQUEST_URI']);
		if (!empty($uri_arr[1])) 
		{
			$uri_tmp = explode('&', $uri_arr[1]);
			foreach ($uri_tmp as $key => $value ) 
			{
				$tmp_arr = explode('=', $value);
				$return[$tmp_arr[0]] = $tmp_arr[1];
			}
			return $return;
		}
		return false;
	}
	protected function header_json() 
	{
		header('Content-type: application/json');
	}
	protected function error_tips($msg, $url) 
	{
		$this->assign('jumpUrl', $url);
		$this->error($msg);
	}
	protected function editor_alert($msg) 
	{
		exit(json_encode(array('error' => 1, 'message' => $msg)));
	}
	protected function ok_jsonp_return($json_arr) 
	{
		$json_arr['err_code'] = 0;
		exit($_GET['callback'] . '(' . json_encode($json_arr) . ')');
	}
	public function get_encrypt_key($array, $app_key) 
	{
		$new_arr = array();
		ksort($array);
		foreach ($array as $key => $value ) 
		{
			$new_arr[] = $key . '=' . $value;
		}
		$new_arr[] = 'app_key=' . $app_key;
		$string = implode('&', $new_arr);
		return md5($string);
	}
	protected function get_im_encrypt_key($array, $app_key) 
	{
		$new_arr = array();
		ksort($array);
		foreach ($array as $key => $value ) 
		{
			$new_arr[] = $key . '=' . $value;
		}
		$new_arr[] = 'app_key=' . $app_key;
		$string = implode('&', $new_arr);
		return md5($string);
	}
	protected function wapFriendRange($meter) 
	{
		if ($meter < 100) 
		{
			return '<100m';
		}
		if ($meter < 1000) 
		{
			return $meter . 'm';
		}
		return round($meter / 1000, 1) . 'km';
	}
}
function wapmerchantfjdslajlsaf() 
{
}
function wapfjdslakfHDFfjlsaf() 
{
}
function usererkfdnlasDSAskfaf() 
{
}
function uitreuitrewhjkfgdkjnlsfgdjklnfadsSYStemfsdajlgfd() 
{
}
function releasefljdsknfglasdkjnflskad() 
{
}
function rlbklfdsakljdfsakjldfsMerchantkjlfjklfdasjklfads() 
{
}
function meallkfjasdlkfjaslknklbklnqqio() 
{
}
function lotteryfdsafasfsadfsadfsadvca() 
{
}
function appointGrvqaoupqwlkrIndexqwr() 
{
}
function groupfdksajflkjsadlkjblkfnrewqrmbvlknasdfa() 
{
}
function indexfdksajflkjsadmbvlknasdfa() 
{
}
?>