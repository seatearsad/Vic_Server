<?php

final class Sms {

    public $topdomain;
    public $key;
    public $smsapi_url;

    /**
     * 
     * 初始化接口类
     * @param int $userid 用户id
     * @param int $productid 产品id
     * @param string $sms_key 密钥
     */
    public function __construct() {
        
    }

    public function checkmobile($mobilephone) {
        $mobilephone = trim($mobilephone);
// 		if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[01236789]{1}[0-9]{8}$|18[01236789]{1}[0-9]{8}$/", $mobilephone)) {
        if (preg_match("/^1[0-9]{10}$/", $mobilephone)) {
            return $mobilephone;
        } else {
            return false;
        }
    }

    /**
     * 
     * 批量发送短信
     * @param array $mobile 手机号码
     * @param string $content 短信内容
     * @param datetime $send_time 发送时间
     * @param string $charset 短信字符类型 gbk / utf-8
     * @param string $id_code 唯一值 、可用于验证码
     * $data = array(mer_id, store_id, content, mobile, uid, type);
     */
    public function sendSms($data = array(), $send_time = '', $charset = 'utf-8', $id_code = '') {
        if ($data) {
            $type = isset($data['type']) ? $data['type'] : 'meal';
            $sendto = isset($data['sendto']) ? $data['sendto'] : 'user';
            $mer_id = isset($data['mer_id']) ? intval($data['mer_id']) : 0;
            $store_id = isset($data['store_id']) ? intval($data['store_id']) : 0;
            $uid = isset($data['uid']) ? intval($data['uid']) : 0;
            //if (empty($mer_id)) return 'mer_id is null';
            $content = isset($data['content']) ? Sms::_safe_replace($data['content']) : '';
            if (empty($content))
                return 'send content is null';
            $mobile = isset($data['mobile']) ? $data['mobile'] : '';
            if (empty($mobile))
                return 'phone is null';

            //O2O多个号码以空格分开，取最后一个号码
            $mobileArr = array();
            $phone_array = explode(' ', $mobile);
            foreach ($phone_array as $phone) {
                if (Sms::checkmobile($phone)) {
                    $mobileArr[] = $phone;
                }
            }
            if (count($mobileArr) > 1) {
                $mobile = array_pop($mobileArr);
            }

            $data = array(
                'o2o_type' => $type,
                'o2o_sendto' => $sendto,
                'o2o_mer_id' => $mer_id,
                'o2o_store_id' => $store_id,
                'o2o_uid' => $uid,
                'topdomain' => C('config.sms_server_topdomain'),
                'key' => trim(C('config.sms_key')),
                'token' => $mer_id . 'o2opigcms',
                'content' => $content,
                'mobile' => $mobile,
                'sign' => trim(C('config.sms_sign'))
            );

            $msg_class = new plan_msg();
            $param = array(
                'type' => '1',
                'content' => $data,
            );
            $msg_class->addTask($param);
        }
    }

    public function sendSmsData($data) {
        // fdump($data,'sendSmsData');
        $post = '';
        foreach ($data as $k => $v) {
            $post .= $k . '=' . $v . '&';
        }

        $smsapi_senturl = 'http://up.pigcms.cn/oa/admin.php?m=sms&c=sms&a=send&productid=3';
        $return = Sms::_post($smsapi_senturl, 0, $post);
        $arr = explode('#', $return);
        $send_time = time();

        //增加到本地数据库
        $row = array('mer_id' => $data['o2o_mer_id'], 'uid' => $data['o2o_uid'], 'store_id' => $data['o2o_store_id'], 'time' => $send_time, 'phone' => $data['mobile'], 'text' => $data['content'], 'status' => $arr[0], 'type' => $data['o2o_type'], 'sendto' => $data['o2o_sendto']);
        D('Sms_record')->add($row);
    }

    /**
     *  post数据
     *  @param string $url		post的url
     *  @param int $limit		返回的数据的长度
     *  @param string $post		post数据，字符串形式username='dalarge'&password='123456'
     *  @param string $cookie	模拟 cookie，字符串形式username='dalarge'&password='123456'
     *  @param string $ip		ip地址
     *  @param int $timeout		连接超时时间
     *  @param bool $block		是否为阻塞模式
     *  @return string			返回字符串
     */
    private function _post($url, $limit = 0, $post = '', $cookie = '', $ip = '', $timeout = 15, $block = true) {
        $return = '';
        $url = str_replace('&amp;', '&', $url);
        $matches = parse_url($url);
        $host = $matches['host'];
        $path = $matches['path'] ? $matches['path'] . ($matches['query'] ? '?' . $matches['query'] : '') : '/';
        $port = !empty($matches['port']) ? $matches['port'] : 80;
        $siteurl = Sms::_get_url();
        if ($post) {
            $out = "POST $path HTTP/1.1\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Referer: " . $siteurl . "\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "Content-Type: application/x-www-form-urlencoded\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= 'Content-Length: ' . strlen($post) . "\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cache-Control: no-cache\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
            $out .= $post;
        } else {
            $out = "GET $path HTTP/1.1\r\n";
            $out .= "Accept: */*\r\n";
            $out .= "Referer: " . $siteurl . "\r\n";
            $out .= "Accept-Language: zh-cn\r\n";
            $out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
            $out .= "Host: $host\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Cookie: $cookie\r\n\r\n";
        }
        $fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
        if (!$fp)
            return '';

        stream_set_blocking($fp, $block);
        stream_set_timeout($fp, $timeout);
        @fwrite($fp, $out);
        $status = stream_get_meta_data($fp);

        if ($status['timed_out'])
            return '';
        while (!feof($fp)) {
            if (($header = @fgets($fp)) && ($header == "\r\n" || $header == "\n"))
                break;
        }

        $stop = false;
        while (!feof($fp) && !$stop) {
            $data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
            $return .= $data;
            if ($limit) {
                $limit -= strlen($data);
                $stop = $limit <= 0;
            }
        }
        @fclose($fp);

        //部分虚拟主机返回数值有误，暂不确定原因，过滤返回数据格式
        $return_arr = explode("\n", $return);
        if (isset($return_arr[1])) {
            $return = trim($return_arr[1]);
        }
        unset($return_arr);

        return $return;
    }

    /**
     * 获取当前页面完整URL地址
     */
    private function _get_url() {
        $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
        $php_self = $_SERVER['PHP_SELF'] ? Sms::_safe_replace($_SERVER['PHP_SELF']) : Sms::_safe_replace($_SERVER['SCRIPT_NAME']);
        $path_info = isset($_SERVER['PATH_INFO']) ? Sms::_safe_replace($_SERVER['PATH_INFO']) : '';
        $relate_url = isset($_SERVER['REQUEST_URI']) ? Sms::_safe_replace($_SERVER['REQUEST_URI']) : $php_self . (isset($_SERVER['QUERY_STRING']) ? '?' . Sms::_safe_replace($_SERVER['QUERY_STRING']) : $path_info);
        return $sys_protocal . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') . $relate_url;
    }

    /**
     * 安全过滤函数
     *
     * @param $string
     * @return string
     */
    private function _safe_replace($string) {
        $string = str_replace('%20', '', $string);
        $string = str_replace('%27', '', $string);
        $string = str_replace('%2527', '', $string);
        $string = str_replace('*', '', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace("'", '', $string);
        $string = str_replace('"', '', $string);
        $string = str_replace(';', '', $string);
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace("{", '', $string);
        $string = str_replace('}', '', $string);
        $string = str_replace('\\', '', $string);
        return $string;
    }

    private function post2($curlPost, $url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
        $return_str = curl_exec($curl);
        curl_close($curl);
        return $return_str;
    }

    private function xml_to_array($xml) {
        $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
        if (preg_match_all($reg, $xml, $matches)) {
            $count = count($matches[0]);
            for ($i = 0; $i < $count; $i++) {
                $subxml = $matches[2][$i];
                $key = $matches[1][$i];
                if (preg_match($reg, $subxml)) {
                    $arr[$key] = Sms::xml_to_array($subxml);
                } else {
                    $arr[$key] = $subxml;
                }
            }
        }
        return $arr;
    }

    public function sendSms2($data) {


	$appkey = "fae6c7edbb8a76aa69035d871ba24cd0";
		$sdkappid = "1400047240";

		$nationcode = "86";
		if(strlen($data['mobile']) != 11) {
			$nationcode = "1";
		}

		$random = time();
		$url = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid={$sdkappid}&random={$random}";

		$newdata = [
			'params' => isset($data['params']) ? $data['params'] : [],
			'tel' => [
				'mobile' => $data['mobile'],
				'nationcode' => $nationcode
			],
			'time' => $random,
			'tpl_id' => $data['tplid']
            //'sign'  =>  'Island Life'//garfunkel 短信签名
		];
		$newdata['sig'] = hash('sha256', "appkey=$appkey&random=$random&time={$random}&mobile=".$data['mobile']);

		Sms::post2(json_encode($newdata), $url);

		return true;

	/*
        if (strlen($data['mobile']) == 11) {
            $data['mobile'] = "86 " . $data['mobile'];
        } else {
            $data['mobile'] = "1 " . $data['mobile'];
        }

        $message = $data['content'];
        $mobile = $data['mobile'];

        $account = "I58317265";
        $password = "480c4234f7cf927e7dd10e4d16ddafd9";
        if (strpos($mobile, "86 ") === 0) { //国内
            $account = "M72945838";
            $password = "b27652dfde06e63f3e2c978b9aedc0cd";
        }
        $target = "http://api.isms.ihuyi.com/webservice/isms.php?method=Submit";
        $post_data = "account=" . $account . "&password=" . $password . "&mobile=" . $mobile . "&content=" . $message;
        //用户名是登录用户中心->国际短信->产品总览->APIID
        //查看密码请登录用户中心->国际短信->产品总览->APIKEY
        $gets = Sms::xml_to_array(Sms::post2($post_data, $target));
        if ($gets['SubmitResult']['code'] == 2) {
            return true;
        } else {
            return false;
        }*/
    }
    public function send_voice_message($phone,$ttxt){
        $username = "vicislandlife@gmail.com";
        $pin = "kavl6668";

        $broadcast_type = "1";

        $phone_number_source = "3";

        $broadcast_name = "Send new order notifications";

        $PhoneNumbers = $phone;

        $TTSText = $ttxt;

        $proxy = "https://api.call-em-all.com/webservices/ceaapi_v3-2-13.asmx?WSDL";
        $client = new SoapClient($proxy, array("trace" => true));

        $request = array (
            "username" => $username,
            "pin" => $pin,
            "broadcastType" => $broadcast_type,
            "phoneNumberSource" => $phone_number_source,
            "broadcastName" => $broadcast_name,
            "phoneNumberCSV" => "",
            "launchDateTime" => "",
            "checkCallingWindow" => "0",
            "commaDelimitedPhoneNumbers" => $PhoneNumbers,
            "TTSText" => $TTSText,
        );

        $client->ExtCreateBroadcast(array("myRequest" => $request));
    }

    //type 向那个客户端发送消息 1用户端 2店员端 3配送员端
    public function sendMessageToGoogle($device_id,$message,$type=1){
        $server_key = '';
        switch ($type){
            case 1:
                $server_key = 'AIzaSyAxHAPoWlRu2Mz8APLwM8Ae6B3x1MJUlvU';
                break;
            case 2:
                $server_key = 'AIzaSyAIIwFEIadyWzc9T3M37hUb4ujbH0i5BAk';
                break;
            case 3:
                $server_key = 'AIzaSyA3DNstqG2aHPyjeyOpsuiWfoC5-qF9l_Q';
                break;
            default:
                break;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';
        $data['to'] = $device_id;
        $data['data'] = array('message'=>'Message From Tutti');
        $data['notification'] = array('title'=>'Tutti','body'=>$message,"sound"=>"default");


        $ch = curl_init();
        $headers[] = "Content-Type:application/json";//"Content-Type: multipart/form-data; boundary=" .  uniqid('------------------');
        $headers[] = "Authorization:key=".$server_key;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch ,CURLOPT_TIMEOUT ,15);
        $result = curl_exec($ch);

        //关闭curl
        curl_close($ch);
        // echo $result;exit;
        $result = json_decode($result, true);

        if (isset($result['errcode'])) {
            import('ORG.Net.GetErrorMsg');
            $errmsg = GetErrorMsg::wx_error_msg($result['errcode']);
            return array('errcode' => $result['errcode'], 'errmsg' => $errmsg);
        } else {
            $result['errcode'] = 0;
            return $result;
        }
    }
}

?>
