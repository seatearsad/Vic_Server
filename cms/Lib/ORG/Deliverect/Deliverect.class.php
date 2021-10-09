<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/9/26
 * Time: 5:50 PM
 */

class Deliverect
{
    protected $client_id;
    protected $client_secret;
    protected $token;
    protected $expiry;
    protected $token_type;
    protected $channelName = "tuttidelivery";

    protected $orderType = array(
        'pickup'=>1,
        'delivery'=>2,
        'eatin'=>3,
        'curbside'=>4
    );

    protected $paymentType = array(
        'online' => 0,
        'cash' => 1
    );

    private $url = "https://api.staging.deliverect.com/";

    //获取token
    private $getTokenUrl = "oauth/token";
    //获取过敏原标签
    private $allAllergens = "allAllergens";

    public function __construct()
    {
        $where = array('tab_id'=>'deliverect','gid'=>50);
        $result = D('Config')->field(true)->where($where)->select();
        foreach($result as $v){
            if($v['name'] == 'deliverect_cliend_id')
                $this->client_id = $v['value'];
            elseif ($v['name'] == 'deliverect_cliend_secret')
                $this->client_secret = $v['value'];
            elseif ($v['name'] == 'deliverect_token')
                $this->token = $v['value'];
            elseif ($v['name'] == 'deliverect_expiry')
                $this->expiry = $v['value'];
            elseif ($v['name'] == 'deliverect_token_type')
                $this->token_type = $v['value'];
        }

        if($this->token == "" || $this->expiry < time()){
            $this->getToken();
        }
    }

    public function getToken(){
        $url = $this->url . $this->getTokenUrl;
        $data['client_id'] = $this->client_id;
        $data['client_secret'] = $this->client_secret;
        $data['audience'] = "https://api.deliverect.com";
        $data['grant_type'] = "client_credentials";

        $result = $this->curlPost($url,$data);

        if(!$result['_error']){
            $this->token = $result['access_token'];
            $this->expiry = $result['expires_at'];
            $this->token_type = $result['token_type'];
        }

        D('Config')->where(array('name'=>'deliverect_token'))->save(array("value"=>$this->token));
        D('Config')->where(array('name'=>'deliverect_expiry'))->save(array("value"=>$this->expiry));
        D('Config')->where(array('name'=>'deliverect_token_type'))->save(array("value"=>$this->token_type));
    }

    public function getAllergensTag(){
        $url = $this->url . $this->allAllergens;
        $result = $this->curlGet($url);

        return $result;
    }

    public function createOrder($storeId,$order){
        $url = $this->url.$this->channelName."/order/".$storeId;

        $data['orderType'] = $this->orderType['delivery'];
    }

    public function curlGet($url,$timeout=30){
        $ch = curl_init();

        $headers[] = "Accept: application/json";
        $headers[] = "Authorization: ".$this->token_type." ".$this->token;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch ,CURLOPT_TIMEOUT ,$timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS,10);
        curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);

        //获得内容
        $result = curl_exec($ch);

        //关闭curl
        curl_close($ch);

        $result = json_decode($result,true);

        return $result;
    }

    function curlPost($url, $data, $timeout=30)
    {
        $ch = curl_init();

        $data = json_encode($data);

        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/json";

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch ,CURLOPT_TIMEOUT ,$timeout);
        curl_setopt($ch, CURLOPT_MAXREDIRS,10);
        curl_setopt($ch, CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);

        $result = curl_exec($ch);
        //关闭curl
        curl_close($ch);
        $result = json_decode($result, true);
        return $result;
    }
}