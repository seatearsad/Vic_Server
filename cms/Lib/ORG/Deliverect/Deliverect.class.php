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

        $result = $this->curlPost($url,$data,false);
        var_dump($result);die();
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

    public function createDelOrder($order){
        if($order['send_platform'] != 0) {
            $url = $this->url . $this->channelName . "/order/" . $order['link_id'];

            $data['channelOrderId'] = $order['order_id'];
            $data['channelOrderDisplayId'] = "TuttiCancel-" . $order['order_id'];
            $data['channelLinkId'] = $order['link_id'];
            $data['status'] = 100;
            $data['cancellationReason'] = "Customer requests order cancellation";

            $result = $this->curlPost($url, $data);
        }
    }

    public function createOrder($order){
        $url = $this->url.$this->channelName."/order/".$order['link_id'];
        //var_dump($url.'---'.time());

        $data['channelOrderId'] = $order['order_id'];
        $data['channelOrderDisplayId'] = "Tutti-".$order['order_id'];
        $data['channelLinkId'] = $order['link_id'];
        $data['by'] = "Tutti";
        $data['orderType'] = $this->orderType['delivery'];
        $data['channel'] = 0;
        $data['pickupTime'] = gmdate("Y-m-d\TH:i:s\Z");//date("Y-m-d H:i:s");//date("Y-m-d")."T".date("H:i:s")."Z";
        $data['estimatedPickupTime'] = date("Y-m-d");//date("Y-m-d")."T".date("H:i:s")."Z";
        $data['deliveryTime'] = date("Y-m-d");
        $data['deliveryIsAsap'] = true;
        $data['courier'] = "Tutti";

        $customer = D("User_adress")->where(array('adress_id'=>$order['address_id']))->find();
        $data['customer']['name'] = $customer['name'];
        $data['customer']['companyName'] = "";
        $data['customer']['phoneNumber'] = $customer['phone'];
        $data['customer']['email'] = "";
        $data['customer']['note'] = $order['desc'];
        $data['customer']['tin'] = "";

        $area = D('Area')->where(array('area_id'=>$customer['city']))->find();
        $data['deliveryAddress']['street'] = $customer['adress'];
        $data['deliveryAddress']['streetNumber'] = "";
        $data['deliveryAddress']['postalCode'] = "";
        $data['deliveryAddress']['city'] = $area['area_name'];
        $data['deliveryAddress']['extraAddressInfo'] = $customer['detail'];

        $data['orderIsAlreadyPaid'] = ($order['pay_type'] == "offline" || $order['pay_type'] == "Cash") ? false : true;
        $data['payment']['amount'] = intval(($order['price'] - $order['merchant_reduce'])*100);
        $data['payment']['type'] = ($order['pay_type'] == "offline" || $order['pay_type'] == "Cash") ? 1 : 0;

        $data['note'] = $order['desc'];

        $order_detail = D("Shop_order_detail")->where(array('order_id'=>$order['order_id']))->select();
        $productAllPrice = 0;
        $items = array();

        $tax_price = 0;
        foreach ($order_detail as $detail){
            $item = array();
            $product = D("StoreMenuV2")->getProduct($detail['goods_id'],$order['store_id']);
            $item['plu'] = $product['plu'];
            $item['name'] = $product['name'];
            $item['price'] = intval($product['price']);
            $item['quantity'] = intval($detail['num']);
            $item['remark'] = "";

            $productAllPrice += $item['price'];

            $subItems = array();
            if($detail['dish_id'] != "") {
                $dish_list = explode("|", $detail['dish_id']);
                foreach ($dish_list as $dish) {
                    $subItem = array();
                    $sub_dish = explode(',', $dish);
                    $sub_product = D("StoreMenuV2")->getProduct($sub_dish[1], $order['store_id']);
                    $subItem['plu'] = $sub_product['plu'];
                    $subItem['name'] = $sub_product['name'];
                    $subItem['price'] = intval($sub_product['price']);
                    $subItem['quantity'] = intval($sub_dish[2]);

                    $productAllPrice += $subItem['price'];

                    $subItems[] = $subItem;
                }

                $item['subItems'] = $subItems;
            }

            $orderDetail = array('goods_id'=>$detail['goods_id'],'num'=>$detail['num'],'store_id'=>$order['store_id'],'dish_id'=>$detail['dish_id']);
            $tax_price += D('StoreMenuV2')->calculationTaxFromOrder($orderDetail);

            $items[] = $item;
        }
        $data['items'] = $items;

        $data['decimalDigits'] = 2;
        $data['numberOfCustomers'] = 1;
        $data['deliveryCost'] = intval($order['freight_charge']*100);
        $data['deliveryCostTax'] = intval(round($order['freight_charge']*$order['store_tax'],0));
        $data['serviceCharge'] = intval($order['service_fee']*100);
        $data['serviceChargeTax'] = 0;
        $data['discountTotal'] = intval($order['merchant_reduce']*100)*-1;

        /**
        $data['taxes'][0]['taxes'] = $order['store_tax'];
        $data['taxes'][0]['name'] = "deliveryCostTax";
        $data['taxes'][0]['total'] = intval($order['freight_charge']*$order['store_tax']);
         * */

        $data['taxes'][0]['taxes'] = $order['store_tax'];
        $data['taxes'][0]['name'] = "productTax";
        $data['taxes'][0]['total'] = intval(round($tax_price,2)*100);
        //var_dump($data);die();
        $result = $this->curlPost($url,$data);

        var_dump($result);
        var_dump($order['order_id']);
        if($result == NULL || $result == "NULL"){
            D("Shop_order")->where(array('order_id'=>$order['order_id']))->save(array('send_platform'=>1));
        }
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

    function curlPost($url, $data,$is_token = true, $timeout=30)
    {
        $ch = curl_init();

        $data = json_encode($data);

        $headers[] = "Accept: application/json";
        $headers[] = "Content-Type: application/json";
        if($is_token) $headers[] = "Authorization: ".$this->token_type." ".$this->token;

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