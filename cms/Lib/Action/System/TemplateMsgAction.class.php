<?php

class TemplateMsgAction extends BaseAction{

	public function __construct(){
		parent::__construct();
	}


	public function index(){
		if(IS_POST){
			$data = array();
			$data['tempkey'] = $_REQUEST['tempkey'];
			$data['name'] = $_REQUEST['name'];
			$data['content'] = $_REQUEST['content'];
			$data['topcolor'] = $_REQUEST['topcolor'];
			$data['textcolor'] = $_REQUEST['textcolor'];
			$data['status'] = $_REQUEST['status'];
			$data['tempid'] = $_REQUEST['tempid'];
			
			foreach ($data as $key => $val){
				foreach ($val as $k => $v){
					$info[$k][$key] = $v;
				}
			}
			foreach ($info as $kk => $vv){
				if($vv['tempid'] == ''){
					$info[$kk]['status'] = 0;
				}
// 				$info[$kk]['token'] = session('token');
				$where = array('tempkey'=>$info[$kk]['tempkey']);

				if(M('Tempmsg')->where($where)->getField('id')){
					M('Tempmsg')->where($where)->save($info[$kk]);
				}else{
					M('Tempmsg')->add($info[$kk]);
				}
			}
			$this->success('操作成功');
		} else {
			$model = new templateNews();
			$templs = $model->templates();
			$list = M('Tempmsg')->field(true)->select();
			$data = array();
			foreach ($list as $row) {
				$data[$row['tempkey']] = $row;
			}
			
			$result = array();
			foreach ($templs as $k => $v){
				$temp = $v;
				if (isset($data[$k])) {
					$temp = $data[$k];
					$temp['name'] = $v['name'];
					$temp['content'] = $v['content'];
				} else {
					$temp['tempkey'] = $k;
					$temp['name'] = $v['name'];
					$temp['content'] = $v['content'];
					$temp['topcolor'] = '#029700';
					$temp['textcolor'] = '#000000';
					$temp['status'] = 0;
				}
				$result[] = $temp;
			}
			$this->assign('list', $result);
			$this->display();
		}
	}
	
	public function getTemplateID()
	{
		$tempkey = isset($_POST['tempkey']) ? htmlspecialchars($_POST['tempkey']) : '';
		
		$access_token_array = D('Access_token_expires')->get_access_token();
		if ($access_token_array['errcode']) {
			$this->error('获取access_token发生错误：错误代码' . $access_token_array['errcode'] .',微信返回错误信息：' . $access_token_array['errmsg']);
		}
		$access_token = $access_token_array['access_token'];
		
		$send_to_url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.$access_token;
		
		import('ORG.Net.Http');
		
		$rt = $this->curlPost($send_to_url, '{"template_id_short":"' . $tempkey . '"}');
		exit($rt);
	}
	
	private function curlPost($url, $data, $timeout=15){
		$ch = curl_init();
		$headers[] = "Accept-Charset: utf-8";//"Content-Type: multipart/form-data; boundary=" .  uniqid('------------------');
		// 		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch ,CURLOPT_TIMEOUT ,$timeout);
		$result = curl_exec($ch);
	
		//关闭curl
		curl_close($ch);
		// echo $result;exit;
// 		$result = json_decode($result, true);
		return $result;
	}
}