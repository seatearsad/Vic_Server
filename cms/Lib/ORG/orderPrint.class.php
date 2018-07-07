<?php
class orderPrint
{
	public $serverUrl;
	
	public $key;
	
	public $topdomain;
	
	public function __construct($server_key, $server_topdomain)
	{
		$this->serverUrl = 'http://up.pigcms.cn/';
		$this->key = C('config.print_server_key');//'59e60f384e7f91c66f5a1a4c5a5bced2,95856e2a0365ffc370c1ba332fbd18f8,5ac00722405fbe0607e8cbc5b09ee009,8433fd3b35e80b8ab20a9ad09d77f821';//trim(C('server_key'));
		$this->topdomain = C('config.print_server_topdomain');//'pigcms.cn';//trim(C('server_topdomain'));
		if (!$this->topdomain) {
			$this->topdomain = $this->getTopDomain();
		}
	}
	public function printit($mer_id, $store_id = 0, $content = '', $paid = 0, $print_id = 0)
	{
		if ($print_id) {
			$usePrinter = D('Orderprinter')->where(array('mer_id' => $mer_id, 'store_id' => $store_id, 'pigcms_id' => $print_id))->find();
			$usePrinters = $usePrinter ? array($usePrinter) : '';
		} else {
			$usePrinters = D('Orderprinter')->where(array('mer_id' => $mer_id, 'store_id' => $store_id, 'is_main' => 1))->select();
			if (empty($usePrinters)) {
				$usePrinters = D('Orderprinter')->where(array('mer_id' => $mer_id, 'store_id' => $store_id, 'is_main' => 0))->order('pigcms_id asc')->select();
				$usePrinter = count($usePrinters) > 0 ? $usePrinters[0] : '';
				$usePrinters = $usePrinter ? array($usePrinter) : '';
			}
		}
		if ($usePrinters) {
			foreach ($usePrinters as $rowset) {
				$rowset['paid'] = explode(',', $rowset['paid']);
				if ($paid == -1 || in_array($paid, $rowset['paid'])) {
					if ($rowset['mp']) {
						$data = array('content' => $content, 'machine_code' => $rowset['mcode'], 'machine_key' => $rowset['mkey']);
						$url = $this->serverUrl . 'server.php?m=server&c=orderPrint&a=printit&count=' . $rowset['count'] . '&key=' . $this->key . '&domain=' . $this->topdomain;
						$rt = $this->api_notice_increment($url, $data);
					} elseif ($rowset['username'])  {
						$data = array('content' => '|5' . $content);
						if ($qr == '') {
							$qrlink = $rowset['qrcode'];
						} else {
							$qrlink = $qr;
						}
						$url = $this->serverUrl.'server.php?m=server&c=orderPrint&a=fcprintit&productid=3&count=' . $rowset['count'] . '&mkey=' . $rowset['mkey'] . '&mcode=' . $rowset['mcode'] . '&name=' . $rowset['username'] . '&qr=' . urlencode($qrlink) . '&domain=' . $this->topdomain;
						$rt = $this->api_notice_increment($url, $data);
					}else{
						/***WIFI小票打印机****/
					   	$data = array('content' => $content, 'machine_code' => $rowset['mcode'], 'machine_key' => $rowset['mkey']);
						$url = $this->serverUrl . 'server.php?m=server&c=orderPrint&a=printit&count=' . $rowset['count'] . '&key=' . $this->key . '&domain=' . $this->topdomain;
						$rt = $this->api_notice_increment($url,$data);
					}
					// 				$config_file = CONF_PATH . 't.php';
					// 				$fp = fopen($config_file, 'a+');
					// 				fwrite($fp, stripslashes(var_export($rt, true)) . ";");
					// 				fclose($fp);
				}
			}
			
		}
	}
	
	function api_notice_increment($url, $data)
	{
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			return $errorno;
		} else {
			return $tmpInfo;
		}
	}
	
	function getTopDomain()
	{
		$host = $_SERVER['HTTP_HOST'];
		$host = strtolower($host);
		if (strpos($host,'/') !== false) {
			$parse = @parse_url($host);
			$host = $parse['host'];
		}
		$topleveldomaindb = array('com','edu','gov','int','mil','net','org','biz','info','pro','name','museum','coop','aero','xxx','idv','mobi','cc','me');
		$str = '';
		foreach ($topleveldomaindb as $v) {
			$str .= ($str ? '|' : '') . $v;
		}
		$matchstr = "[^\.]+\.(?:(".$str.")|\w{2}|((".$str.")\.\w{2}))$";
		if (preg_match("/".$matchstr."/ies", $host, $matchs)) {
			$domain = $matchs['0'];
		} else {
			$domain = $host;
		}
		return $domain;
	}
}
