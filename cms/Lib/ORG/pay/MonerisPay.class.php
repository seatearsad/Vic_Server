<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/10/10
 * Time: 10:51
 */

class MonerisPay
{
    protected $store_id;
    protected $api_token;
    protected $countryCode;
    protected $testMode;
    public function __construct(){
        import('@.ORG.pay.MonerisPay.mpgClasses');

        $where = array('tab_id'=>'moneris','gid'=>7);
        $result = D('Config')->field(true)->where($where)->select();
        foreach($result as $v){
            if($v['info'] == 'store_id')
                $this->store_id = $v['value'];
            elseif ($v['info'] == 'token')
                $this->api_token = $v['value'];
        }
        //die($this->store_id."----------".$this->api_token);
        $this->countryCode = 'CA';
        //$this->testMode = true;
        $this->testMode = false;
    }

    /**
     * @param $data
     * @param $uid
     * @param $from_type 1Web(PC) 2Wap 3App
     * @return mixed
     */
    public function payment($data,$uid,$from_type){

        $order = explode("_",$data['order_id']);
        $order_id = $order[1];
        $order = D('Shop_order')->where(array('order_id'=>$order_id))->find();
        $store = D('Merchant_store')->where(array('store_id'=>$order['store_id']))->find();

        if($data['credit_id']){//存储卡的】
            $card_id = $data['credit_id'];
            $card = D('User_card')->field(true)->where(array('id' => $card_id))->find();
            $pan = $card['card_num'];
        }else {//直接输入卡号的
            $pan = $data['card_num'];
        }

        if($uid == 0){
            if($data['order_type'] != 'recharge')
                return $this->purchase($data,$uid,$from_type,$order);
            else
                return array();
        }

        //是否为可验证的卡 Visa 首数字4； Master 首数字5；AmEx 34或37
        $is_check = false;
        if(substr($pan,0,1) == 4 || substr($pan,0,1) == 5)
            $is_check = true;
        else if(substr($pan,0,2) == 34 || substr($pan,0,2) == 37)
            $is_check = true;

        //判断金额还需在api user_card_default 方法中修改
        if($is_check && ($data['order_type'] == 'recharge' || $data['charge_total'] >= 251 || $store['pay_secret'] == 1)){
            //跳转到第三方支付 3D 1.1
            //return $this->mpi_transaction($data,$uid,$from_type);

            //3D 2.0
            $resp = $this->threeDSAuthentication($data,$uid,$from_type);
            //var_dump($resp);die();

            if($resp['transStatus'] == "Y" || $resp['transStatus'] == "A"){
                //return $this->purchase($data,$uid,$from_type,$order);
                $order_md = D('Pay_moneris_md')->where(array('moneris_order_id'=>$resp['receiptId']))->find();
                $MD = $order_md['order_md'];

                //file_put_contents("./test_log.txt",date("Y/m/d")."   ".date("h:i:sa")."   "."Moneris 3D" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($resp).'---'.json_encode($order_md)."\r\n",FILE_APPEND);
                return $this->MPI_Cavv($MD,$resp['cavv'],$resp['eci'],$resp['threeDSServerTransId']);
            }elseif ($resp['transStatus'] == "N" || $resp['transStatus'] == "U"){
                if($data['order_type'] != 'recharge')
                    return $this->purchase($data,$uid,$from_type,$order);
                else
                    return $resp;
            } else{
                return $resp;
            }
        }else {
            if($data['order_type'] != 'recharge')
                return $this->purchase($data,$uid,$from_type,$order);
            else
                return array();
        }
    }

    //直接支付
    public function purchase($data,$uid,$from_type,$order){
        $order_id = $order['order_id'];

        $txnArray['type'] = 'purchase';
        $txnArray['crypt_type'] = '7';

        if ($data['credit_id']) {//使用老卡存储卡的
            $card_id = $data['credit_id'];
            $card = D('User_card')->field(true)->where(array('id' => $card_id))->find();
            $txnArray['pan'] = $card['card_num'];
            $txnArray['expdate'] = $card['expiry'];
        } else {//直接输入卡号的
            $txnArray['pan'] = $data['card_num'];
            $txnArray['expdate'] = transYM($data['expiry']);
        }

        //或者这张订单之前的错误回复
        $error_list = D('Pay_moneris_record_error')->field(true)->where(array('order_id' => $order_id))->select();
        $count = count($error_list);
        if ($count > 0)
            $data['order_id'] = $data['order_id'] . '_' . $count;

        $txnArray['order_id'] = $data['order_id'];
        $txnArray['cust_id'] = $data['cust_id'];
        $txnArray['amount'] = $data['charge_total'];

//        var_dump($txnArray.$store_id.$api_token); die();

        /**************************** Transaction Object *****************************/

        $mpgTxn = new mpgTransaction($txnArray);

        /****************************** Request Object *******************************/

        /****************************** CVD Object ********************************/
        $card_cvd = '';
        if ($data['cvd'] && $data['cvd'] != '') {
            $card_cvd = $data['cvd'];
        }

        if ($card_cvd != '') {
            $cvdTemplate = array(
                'cvd_indicator' => '1',
                'cvd_value' => $card_cvd
            );

            $mpgCvdInfo = new mpgCvdInfo ($cvdTemplate);
            $mpgTxn->setCvdInfo($mpgCvdInfo);
        }

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode);
        //$mpgRequest->setTestMode(true);

        $mpgHttpPost = new mpgHttpsPost($this->store_id, $this->api_token, $mpgRequest);

        $mpgResponse = $mpgHttpPost->getMpgResponse();
        //echo("----3-----");
        $resp = $this->arrageResp($mpgResponse, $txnArray['pan'], $txnArray['expdate'], 0, $order_id, $card_cvd);

        if ($resp['responseCode'] != "null" && $resp['responseCode'] < 50 && $data['save'] == 1) {//如果需要存储

            $isC = D('User_card')->getCardByUserAndNum($uid, $data['card_num']);
            if (!$isC) {
                D('User_card')->clearIsDefaultByUid($uid);
                $card_data['name'] = $data['name'];
                $card_data['is_default'] = 1;
                $card_data['card_num'] = $data['card_num'];
                $card_data['uid'] = $uid;
                $data['uid'] = $uid;
                $card_data['create_time'] = date("Y-m-d H:i:s");
                //存储的时候为YYMM
                $card_data['expiry'] = transYM($data['expiry']);
                $data['credit_id'] = D('User_card')->field(true)->add($card_data);
            }
        }

        if ($resp['responseCode'] != "null" && $resp['responseCode'] < 50) {
            //如果需要验证CVD
            if ($card_cvd != '') {
                if (strpos($resp['cvdResultCode'], 'M') !== false || strpos($resp['cvdResultCode'], 'Y') !== false) {
                    if ($data['credit_id']) {
                        $data_card['cvd'] = $card_cvd;
                        $data_card['status'] = 1;
                        $data_card['verification_time'] = time();
                        D('User_card')->field(true)->where(array('id' => $data['credit_id']))->save($data_card);
                    }
                }
//                } else {
//                    //验证CVD 为通过 将responseCode修改后存储一次error记录并退款
//                    $resp['responseCode'] = 7513;
//                    $resp['message'] = 'CVD Error';
//                    D('Pay_moneris_record_error')->add($resp);
//                    $this->refund($uid, $order_id);
//                }
            }

            //处理优惠券
            if ($data['coupon_id']) {
                //如果选择的为活动优惠券
                if (strpos($data['coupon_id'], 'event') !== false) {
                    $event = explode('_', $data['coupon_id']);
                    $event_coupon_id = $event[2];
                    $list = D('New_event')->getUserCoupon($uid, 0, -1, $event_coupon_id);
                    $now_coupon = reset($list);
                    if (!empty($now_coupon)) {
                        $coupon = D('New_event_coupon')->where(array('id' => $now_coupon['event_coupon_id']))->find();
                        $in_coupon = array('coupon_id' => $data['coupon_id'], 'coupon_price' => $coupon['discount']);
                        if ($order['delivery_discount_type'] == 0) {
                            $in_coupon['delivery_discount'] = 0;
                        }
                        D('Shop_order')->field(true)->where(array('order_id' => $order_id))->save($in_coupon);
                    }
                } else {
                    $now_coupon = D('System_coupon')->get_coupon_by_id($data['coupon_id']);
                    if (!empty($now_coupon)) {
                        $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $data['coupon_id']))->find();
                        $coupon_real_id = $coupon_data['coupon_id'];
                        $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                        $in_coupon = array('coupon_id' => $data['coupon_id'], 'coupon_price' => $coupon['discount']);
                        if ($order['delivery_discount_type'] == 0) {
                            $in_coupon['delivery_discount'] = 0;
                        }

                        D('Shop_order')->field(true)->where(array('order_id' => $order_id))->save($in_coupon);
                    }
                }
            }
        }

        if ($uid != 0) {
            $this->savePayData($resp, $data['rvarwap'], $data['tip'], $data['order_type'], $data['note'], $data['est_time']);
        } else {

        }
        return $resp;
    }

    //处理返回数据 $record_type 存储记录的类型 0初次支付记录 1删单退款记录（用户删单、系统删单）2 二次付款记录 3退还部分款项记录
    public function arrageResp($mpgResponse,$pan,$expiry,$record_type = 0,$order_id,$card_cvd=''){
        /*卡类型 M = MasterCard V = Visa AX = American Express  NO = Discover（仅限加拿大）
        C1 = JCB（仅限加拿大）SE =Sears（仅限加拿大）D =借方（仅限加拿大）*/
        $resp['card_type'] = $mpgResponse->getCardType();
        //交易订单号
        $resp['receiptId'] = $mpgResponse->getReceiptId();
        //参考编号
        $resp['referenceNum'] = $mpgResponse->getReferenceNum();
        //响应代码
        $resp['responseCode'] = $mpgResponse->getResponseCode();
        //ISO代码
        $resp['ISO'] = $mpgResponse->getISO();
        //授权码
        $resp['authCode'] = $mpgResponse->getAuthCode();
        //交易时间
        $resp['transTime'] = $mpgResponse->getTransTime();
        //交易日期
        $resp['transDate'] = $mpgResponse->getTransDate();
        //交易类型
        $resp['transType'] = $mpgResponse->getTransType();
        //是否完成 true 成功 false 失败
        $resp['complete'] = $mpgResponse->getComplete();
        //返回消息
        $resp['message'] = $mpgResponse->getMessage();
        //交易金额
        $resp['transAmount'] = $mpgResponse->getTransAmount();
        //交易号 ** 退款需要
        $resp['txnNumber'] = $mpgResponse->getTxnNumber();
        //是否超时
        $resp['timeOut'] = $mpgResponse->getTimedOut();

        $order = explode("_",$resp['receiptId']);
        $resp['order_id'] = $order[1];
        //test use
        //if($resp['order_id'] == null) $resp['order_id'] = 1;

        //garfunkel add
        if($card_cvd != '') {
            $resp['cvdResultCode'] = $mpgResponse->getCvdResultCode();
        }else {
            $resp['cvdResultCode'] = '';
        }

        $resp['cvd'] = $card_cvd;

        $resp['pan'] = $pan;
        $resp['expiry'] = $expiry;
        //存储支付记录
        if($record_type == 0)
            $save_table = D('Pay_moneris_record');
        elseif($record_type == 1)
            $save_table = D('Pay_moneris_record_del');
        elseif($record_type == 2)
            $save_table = D('Pay_moneris_record_add');
        elseif($record_type == 3)
            $save_table = D('Pay_moneris_record_refund');

        if($resp['responseCode'] != "null" && $resp['responseCode'] < 50)//只记录正确的
            $save_table->field(true)->add($resp);
        else{//记录所有错误
            //有可能错误返回值里面没有正确的订单号
            $resp['order_id'] = $order_id;
            D('Pay_moneris_record_error')->add($resp);
        }

        return $resp;
    }

    //存储支付数据
    public function savePayData($resp,$is_wap,$tip,$order_type,$desc = '',$expect_use_time = ''){
//        if($resp['complete'] == 'true'){//支付成功
        if(!$order_type) $order_type = "shop";
        if($resp['responseCode'] != "null" && $resp['responseCode'] < 50){

            $order = explode("_",$resp['receiptId']);
            $order_id = $order[1];
            if($order_type == "recharge"){
                $order_param['order_id'] = $order_id;
                $order_param['pay_type'] = 'moneris';
                $order_param['pay_time'] = $resp['transDate'] . ' ' . $resp['transTime'];
                $order_param['pay_money'] = $resp['transAmount'];
                $order_param['order_type'] = $order_type;
                $order_param['is_mobile_pay'] = 1;
                $order_param['third_id'] = 0;

                $result = D('User_recharge_order')->after_pay($order_param);
            }else {
                $order_param['order_id'] = $order_id;
                $order_param['order_from'] = 0;
                $order_param['order_type'] = 'shop';
                $order_param['pay_time'] = $resp['transDate'] . ' ' . $resp['transTime'];
                $order_param['pay_money'] = $resp['transAmount'];
                $order_param['pay_type'] = 'moneris';
                //$order_param['is_mobile'] = $is_wap;
                $order_param['is_own'] = 0;
                $order_param['third_id'] = 0;
                $order_param['invoice_head'] = $resp['txnNumber'];//借用发票头这个字段存储交易号
                $order_param['tip_charge'] = $tip;
                //garfunkel add 19.4.9
                if($desc && $desc != '')
                    $order_param['desc'] = $desc;
                if($expect_use_time && $expect_use_time != ''){
                    $order_param['expect_use_time'] = strtotime($expect_use_time);
                }

                $result = D('Shop_order')->after_pay($order_param);

            }

        }
    }

    /*
     * $order_id 为系统原始订单ID
     * $change_amount 为要退还的金额 当不输入是为-1，即订单金额全部退回
     * $type 0 shop_order | 1 User_recharge_order
    */
    public function refund($uid,$order_id,$change_amount = -1,$record_type = 1,$type=0){
        //if($type == 0)
            $order = D('Shop_order')->field(true)->where(array('order_id'=>$order_id,'paid'=>1,'pay_type'=>'moneris'))->find();
        //else
            //$order = D('User_recharge_order')->field(true)->where(array('order_id'=>$order_id,'paid'=>1,'pay_type'=>'moneris'))->find();

        if($order){
            if ($record_type == 1){
                $record = D('Pay_moneris_record')->field(true)->where(array('order_id'=>$order_id))->find();
                //是否有过全额退款记录
                $del_record = D('Pay_moneris_record_del')->field(true)->where(array('order_id'=>$order_id))->find();
            }else{
                $record = D('Pay_moneris_record_add')->field(true)->where(array('order_id'=>$order_id))->find();
                //是否有过全额退款记录
                $del_record = D('Pay_moneris_record_refund')->field(true)->where(array('order_id'=>$order_id))->find();
            }


//            if($record){
            $record_type = 3;
            if($record && !$del_record){//正式的使用此判断
                //初始化退款差额
                $cha = 0;
                $txnArray['type'] = 'purchasecorrection';
                $txnArray['txn_number'] = $record['txnNumber'];
                $txnArray['order_id'] = $record['receiptId'];
                $txnArray['crypt_type'] = '7';

                //之前是否有退款
//                $refund_record = D('Pay_moneris_record_refund')->field(true)->where(array('order_id'=>$order_id))->find();
//                if($refund_record){//如果之前有退款 减去退款金额
//                    $record['transAmount'] = $record['transAmount'] - $refund_record['transAmount'];
//                }
//                //金额是否超出
//                if($change_amount > $record['transAmount']){
//                    //如果有超出记录金额 之后再追加付款中退还
//                    $cha = $change_amount - $record['transAmount'];
//                    $change_amount = $record['transAmount'];
//                }

//                $txnArray['amount'] = $change_amount == -1 ? $record['transAmount'] : $change_amount;

                //$txnArray['amount'] = $record['transAmount'];
                //$txnArray['cust_id'] = $uid;

                $mpgTxn = new mpgTransaction($txnArray);

                $mpgRequest = new mpgRequest($mpgTxn);
                $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
                $mpgRequest->setTestMode($this->testMode);

                $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);
                $mpgResponse=$mpgHttpPost->getMpgResponse();

//                $record_type = 3;//退还部分款项
//                if($change_amount == -1)//全额退款即删单
//                    $record_type = 1;

                $resp = $this->arrageResp($mpgResponse,'','',$record_type,$order_id);
//                var_dump($resp);
                if(!($resp['responseCode'] != "null" && $resp['responseCode'] < 50)){//如果购买更正不成功，尝试退款
                    $txnArray['type'] = 'refund';
                    $txnArray['txn_number'] = $record['txnNumber'];
                    $txnArray['order_id'] = $record['receiptId'];
                    $txnArray['amount'] = $record['transAmount'];
                    $txnArray['crypt_type'] = '7';

                    $mpgTxn = new mpgTransaction($txnArray);

                    $mpgRequest = new mpgRequest($mpgTxn);
                    $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
                    $mpgRequest->setTestMode($this->testMode);
                    $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);
                    $mpgResponse=$mpgHttpPost->getMpgResponse();

                    $resp = $this->arrageResp($mpgResponse,'','',$record_type,$order_id);
                }


//                if ($cha > 0 || $change_amount == -1){//如果有多出的金额或者删除订单 再后添加付款处退还
//                    $this->refund_addPay($uid,$order_id,$cha);
//                }

                return $resp;
            }
        }
    }

    public function refund_addPay($uid,$order_id,$change_amount){
        $record = D('Pay_moneris_record_add')->field(true)->where(array('order_id'=>$order_id))->find();
        if($record){
            $txnArray['order_id'] = $record['receiptId'];
            $txnArray['type'] = 'refund';
            $txnArray['crypt_type'] = '7';
            $txnArray['txn_number'] = $record['txnNumber'];
            //金额是否超出
            if($change_amount > $record['transAmount']){
                $change_amount = $record['transAmount'];
            }
            $txnArray['amount'] = $change_amount == -1 ? $record['transAmount'] : $change_amount;
            $txnArray['cust_id'] = $uid;
            $mpgTxn = new mpgTransaction($txnArray);

            $mpgRequest = new mpgRequest($mpgTxn);
            $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
            $mpgRequest->setTestMode($this->testMode);

            $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);
            $mpgResponse=$mpgHttpPost->getMpgResponse();
            $resp = $this->arrageResp($mpgResponse,'','',3,$order_id);

            return $resp;
        }
    }

    public function addPay($uid,$order_id,$change_amount){
        $txnArray['type'] = 'purchase';
        $txnArray['crypt_type'] = '7';

        $record = D('Pay_moneris_record')->field(true)->where(array('order_id'=>$order_id))->find();
        if($record){
            $txnArray['pan'] = $record['pan'];
            $txnArray['expdate'] = $record['expiry'];
        }


        $txnArray['order_id'] = 'vicislandAdd_'.$order_id;
        $txnArray['cust_id'] = $uid;
        $txnArray['amount'] = $change_amount;

//        var_dump($txnArray.$store_id.$api_token); die();

        /**************************** Transaction Object *****************************/

        $mpgTxn = new mpgTransaction($txnArray);

        /****************************** Request Object *******************************/

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode);

        $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();
        $resp = $this->arrageResp($mpgResponse,$txnArray['pan'],$txnArray['expdate'],2,$order_id);

        return $resp;
    }

    /**
     * @param $data
     * @param $uid
     * @param $from_type 1Web(PC) 2Wap 3App
     * @return mixed
     */
    public function mpi_transaction($data,$uid,$from_type){

        if($data['credit_id']){//存储卡的

            $vault_card = D('Vault_card')->where(array('user_card_id'=>$data['credit_id']))->find();
            if($vault_card){
                $data_key = $vault_card['data_key'];
            }else{
                $result = $this->addVaultCard($data['credit_id'],$uid);
                if($result['resSuccess'] == "true")
                    $data_key = $result['data_key'];
                else
                    return $result;
            }
        }else{//直接输入卡号的
            $vault_card = D('Vault_card')->where(array('card_num'=>$data['card_num'],'expiry'=>transYM($data['expiry'])))->find();
            if($vault_card){
                $data_key = $vault_card['data_key'];
            }else {
                $card['card_num'] = $data['card_num'];
                $card['expiry'] = transYM($data['expiry']);
                $card['name'] = $data['name'];
                $card['cvd'] = $data['cvd'];
                $result = $this->addVaultCard(0, $uid, $card);
                if ($result['resSuccess'] == "true")
                    $data_key = $result['data_key'];
                else
                    return $result;
            }
        }

        $order = explode("_",$data['order_id']);
        $order_id = $order[1];
        $order_param = array();
        if($data['note'] && $data['note'] != '')
            $order_param['desc'] = $data['note'];
        if($data['est_time'] && $data['est_time'] != ''){
            $order_param['expect_use_time'] = strtotime($data['est_time']);
        }

        if(count($order_param) > 0)
            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_param);

        if(!$data['order_type']) $data['order_type'] = "shop";

        $save = $data['save'] ? $data['save'] : 0;
        $tip = $data['tip'] ? $data['tip'] : 0;
        $coupon_id = $data['coupon_id'] ? $data['coupon_id'] : '';
        $card_user_name = $data['name'] ? $data['name'] : '';

        $orderInfo = '-'.$data['order_type'].'-'.$data['order_id'].'-'.$from_type.'-'.$save.'-'.$tip.'-'.$coupon_id.'-'.$card_user_name.'-'.$data_key.'-';
        //$data_key='gvOULripl7gcabGJmM1vOlnj2';
        $amount=$data['charge_total'];
        $xid = sprintf("%'920d", rand());
        $MD = $xid.$orderInfo.$amount;

        $site_url = $this->getNotificationUrl();

        $merchantUrl = $site_url.'/secure3d';//.$_SERVER["HTTP_REFERER"];
        $accept = $_SERVER['HTTP_ACCEPT'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];

        $txnArray =array('type'=>'res_mpitxn',
            'data_key'=>$data_key,
            'amount'=>$amount,
            'xid'=>$xid,
            'MD'=>$MD,
            'merchantUrl'=>$merchantUrl,
            'accept'=>$accept,
            'userAgent'=>$userAgent
        );

        $mpgTxn = new mpgTransaction($txnArray);
        //var_dump($mpgTxn);die();

        /************************ Request Object **********************************/
        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode); //false or comment out this line for production transactions
        /************************ mpgHttpsPost Object ******************************/
        $mpgHttpPost = new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);
        /************************ Response Object **********************************/
        $mpgResponse=$mpgHttpPost->getMpgResponse();
        //print("\nMpiSuccess = " . $mpgResponse->getMpiSuccess());
        //var_dump($mpgResponse);die();
        $resp['requestMode'] = "mpi";
        $resp['version'] = 1;
        $resp['mpiSuccess'] = $mpgResponse->getMpiSuccess();
        $resp['message'] = $mpgResponse->getMpiMessage();

        if($mpgResponse->getMpiSuccess() == "true")
        {
            //print($mpgResponse->getMpiInLineForm());
            $resp['mpiInLineForm'] = $mpgResponse->getMpiInLineForm();
            //$resp['mpiInLineForm'] = str_replace('<noscript>','',$resp['mpiInLineForm']);
            //$resp['mpiInLineForm'] = str_replace('</noscript>','',$resp['mpiInLineForm']);
            $resp['mpiInLineForm'] .= '<SCRIPT LANGUAGE="Javascript">OnLoadEvent();</SCRIPT>';

            $resp['MpiPaReq'] = $mpgResponse->getMpiPaReq();
            $resp['MpiTermUrl'] = $mpgResponse->getMpiTermUrl();
            $resp['MpiMD'] = $mpgResponse->getMpiMD();
            $resp['MpiACSUrl'] = $mpgResponse->getMpiACSUrl();
        }
        else
        {
            //print("\nMpiMessage = " . $mpgResponse->getMpiMessage());
            if(!$resp['message'] || $resp['message'] == '')
                $resp['message'] = $mpgResponse->getMpiMessage();
        }
        //var_dump($mpgResponse);die();
        return $resp;
    }

    public function addVaultCard($card_id,$uid,$card = array()){
        if($card_id != 0){
            $card = D('User_card')->field(true)->where(array('id'=>$card_id))->find();
        }

        $user = D('User')->where(array('uid'=>$uid))->find();

        /************************* Transactional Variables ****************************/

        $type='res_add_cc';
        $cust_id=$uid;
        $phone = $user['phone'];
        $email = $user['email'] == '' ? $phone."@tutti.app" : $user['email'];
        $note = $user['nickname'];
        $pan = $card['card_num'];
        $expiry_date = $card['expiry'];
        $crypt_type='1';

        /*********************** Transactional Associative Array **********************/

        $txnArray=array('type'=>$type,
            'cust_id'=>$cust_id,
            'phone'=>$phone,
            'email'=>$email,
            'note'=>$note,
            'pan'=>$pan,
            'expdate'=>$expiry_date,
            'crypt_type'=>$crypt_type
        );

        $mpgTxn = new mpgTransaction($txnArray);

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode);
        $mpgRequest->setTestMode($this->testMode);

        $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();

        //响应代码
        $resp['responseCode'] = $mpgResponse->getResponseCode();
        //返回消息
        $resp['message'] = $mpgResponse->getMessage();
        $resp['resSuccess'] = $mpgResponse->getResSuccess();
        if($resp['resSuccess'] == "true") {
            $resp['data_key'] = $mpgResponse->getDataKey();
            if($card_id != 0)
                $vault_card['user_card_id'] = $card_id;
            else{
                $vault_card['name'] = $card['name'];
                $vault_card['card_num'] = $card['card_num'];
                $vault_card['expiry'] = $card['expiry'];
            }

            $vault_card['uid'] = $uid;
            $vault_card['data_key'] = $resp['data_key'];

            D('Vault_card')->add($vault_card);
        }else {
            $resp['data_key'] = "";
        }

        return $resp;
    }

    public function MPI_Acs($PaRes,$MD){
        $type='acs';

        $txnArray=array(
            'type'=>$type,
            'PaRes'=>$PaRes,
            'MD'=>$MD,
        );

        $mpgTxn = new mpgTransaction($txnArray);

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode);

        $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();
        //var_dump($mpgResponse);die();
        //print("\nMpiMessage = " . $mpgResponse->getMpiMessage());
        //print("\nMpiSuccess = " . $mpgResponse->getMpiSuccess());
        $message = $mpgResponse->getMpiSuccess();

        if (strcmp($mpgResponse->getMpiSuccess(),"true") == 0)
        {
            $cavv = $mpgResponse->getMpiCavv();
            $eci = $mpgResponse->getMpiEci();

            return $this->MPI_Cavv($MD,$cavv,$eci);
        }else{
            $result['message'] = $message;
            $orderInfo = $this->getOrderInfoFromMD($MD);
            $result['url'] = $orderInfo['url'];
            $result['uid'] = $orderInfo['uid'];

            return $result;
        }
    }

    public function MPI_Cavv($MD,$cavv,$eci,$threeTransId=''){
        if($threeTransId == ''){
            $threeVersion = 1;
        }else{
            $threeVersion = 2;
        }

        $orderInfo = $this->getOrderInfoFromMD($MD);

        $type = 'cavv_purchase';
        $order_id = $orderInfo['orderId'];
        $cust_id = $orderInfo['uid'];
        $amount = $orderInfo['amount'];
        $pan = $orderInfo['card_num'];
        $expiry_date = $orderInfo['expiry'];
        //$crypt_type = '7';
        $crypt_type = $eci;

        $txnArray=array(
            'type'=>$type,
            'order_id'=>$order_id,
            'cust_id'=>$cust_id,
            'amount'=>$amount,
            'pan'=>$pan,
            'expdate'=>$expiry_date,
            'cavv'=>$cavv,
            'crypt_type'=>$crypt_type,
            'dynamic_descriptor'=>'3dsecure'
        );
        //3D V2
        if($threeVersion == 2){
            $txnArray['threeds_version'] = 2;
            $txnArray['threeds_server_trans_id'] = $threeTransId;
        }

        $mpgTxn = new mpgTransaction($txnArray);

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode);
        //file_put_contents("./test_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Moneris3DCavvRequest" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($mpgTxn)."\r\n",FILE_APPEND);
        $mpgHttpPost = new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        $mpgResponse = $mpgHttpPost->getMpgResponse();

        $resp = $this->arrageResp($mpgResponse,$txnArray['pan'],$txnArray['expdate'],0,$order_id);
        //file_put_contents("./test_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Moneris3DCavvResponse" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($resp)."\r\n",FILE_APPEND);
        if($resp['responseCode'] != "null" && $resp['responseCode'] < 50){
            if($orderInfo['save'] == 1) {
                $isC = D('User_card')->getCardByUserAndNum($orderInfo['uid'], $pan);
                if (!$isC) {
                    D('User_card')->clearIsDefaultByUid($orderInfo['uid']);
                    $data['is_default'] = 1;
                    $data['uid'] = $orderInfo['uid'];
                    $data['card_num'] = $orderInfo['card_num'];
                    $data['name'] = $orderInfo['card_name'];
                    $data['create_time'] = date("Y-m-d H:i:s");
                    $data['expiry'] = $expiry_date;
                    $data['credit_id'] = D('User_card')->field(true)->add($data);
                }
            }

            //处理优惠券
            if(isset($orderInfo['coupon_id']) && $orderInfo['coupon_id'] != ''){
                //如果选择的为活动优惠券
                if(strpos($orderInfo['coupon_id'],'event')!== false) {
                    $event = explode('_',$orderInfo['coupon_id']);
                    $event_coupon_id = $event[2];
                    $list = D('New_event')->getUserCoupon($orderInfo['uid'],0,-1,$event_coupon_id);
                    $now_coupon = reset($list);
                    if(!empty($now_coupon)){
                        $coupon = D('New_event_coupon')->where(array('id'=>$now_coupon['event_coupon_id']))->find();
                        $in_coupon = array('coupon_id' => $orderInfo['coupon_id'], 'coupon_price' => $coupon['discount']);
                        D('Shop_order')->field(true)->where(array('order_id' => $orderInfo['order_id']))->save($in_coupon);
                    }
                }else{
                    $now_coupon = D('System_coupon')->get_coupon_by_id($orderInfo['coupon_id']);
                    if (!empty($now_coupon)) {
                        $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $orderInfo['coupon_id']))->find();
                        $coupon_real_id = $coupon_data['coupon_id'];
                        $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                        $in_coupon = array('coupon_id' => $orderInfo['coupon_id'], 'coupon_price' => $coupon['discount']);

                        D('Shop_order')->field(true)->where(array('order_id' => $orderInfo['order_id']))->save($in_coupon);
                    }
                }
            }
            //删除存储的订单信息
            D('Pay_moneris_md')->where(array('moneris_order_id'=>$order_id))->delete();
        }

        //1Web(PC) 2Wap 3App
        $is_wap = $orderInfo['order_from']-1;

        $this->savePayData($resp,$is_wap,$orderInfo['tip'],$orderInfo['order_type']);

        $resp['url'] = $orderInfo['url'];
        $resp['uid'] = $orderInfo['uid'];

        return $resp;
    }

    public function getOrderInfoFromMD($MD){
        //$orderInfo = '-'.$data['order_type'].'-'.$data['order_id'].'-'.$from_type.'-'.$save.'-'.$tip.'-'.$coupon_id.'-'.$card_user_name.'-'.$data_key.'-';
        //var_dump($MD);die();
        $arr = explode('-',$MD);
        $orderInfo['order_type'] = $arr[1];
        $orderInfo['orderId'] = $arr[2];
        $orderInfo['order_from'] = $arr[3];
        $orderInfo['save'] = $arr[4];
        $orderInfo['tip'] = $arr[5];
        $orderInfo['coupon_id'] = $arr[6];
        $orderInfo['card_name'] = $arr[7];
        $data_key = $arr[8];
        $orderInfo['amount'] = $arr[9];

        $order_id_str = explode("_",$orderInfo['orderId']);
        $orderInfo['order_id'] = $order_id_str[1];

        if($orderInfo['order_type'] == 'recharge')
            $order = D('User_recharge_order')->where(array('order_id'=>$orderInfo['order_id']))->find();
        else
            $order = D('Shop_order')->where(array('order_id'=>$orderInfo['order_id']))->find();

        $orderInfo['uid'] = $order['uid'];

        $vault_card = D('Vault_card')->where(array('data_key'=>$data_key))->find();
        if($vault_card['user_card_id'] == 0){
            $orderInfo['card_num'] = $vault_card['card_num'];
            $orderInfo['expiry'] = $vault_card['expiry'];
        }else{
            $card = D('User_card')->where(array('id'=>$vault_card['user_card_id']))->find();
            $orderInfo['card_num'] = $card['card_num'];
            $orderInfo['expiry'] = $card['expiry'];
        }

        //1vWeb(PC) 2 Wap 3 App

        if($orderInfo['order_from'] == 1){
            if($orderInfo['order_type'] == 'recharge')
                $url = C('config.config_site_url').'/index.php?g=User&c=Credit&a=index';
            else {
                if(strpos($_SERVER['HTTP_HOST'],'tutti.app') !== false)
                    $url = 'https://'.$_SERVER['HTTP_HOST'].'/index.php?g=User&c=Index&a=shop_order_view&order_id=' . $orderInfo['order_id'];
                else
                    $url = C('config.config_site_url') . '/index.php?g=User&c=Index&a=shop_order_view&order_id=' . $orderInfo['order_id'];
            }

            $orderInfo['url'] = $url;

        }elseif($orderInfo['order_from'] == 2){ /// WAP 应该用的是这里，设置支付成功或

            if($orderInfo['order_type'] == 'recharge')
                $url = U("Wap/My/my_money");
            else {
                if(strpos($_SERVER['HTTP_HOST'],'tutti.app') !== false)
                    $url = 'https://'.$_SERVER['HTTP_HOST'].'/wap.php?g=Wap&c=Shop&a=pay_result&order_id='.$orderInfo['order_id']."&mer_id=".$orderInfo['mer_id']."&store_id=".$orderInfo['store_id'];
                    //$url = 'https://'.$_SERVER['HTTP_HOST'].'/wap.php?g=Wap&c=Shop&a=pay_result&order_id='.$orderInfo['order_id']."&mer_id=".$orderInfo['mer_id']."&store_id=".$orderInfo['store_id']."&status=1";
                else
                    $url = U("Wap/Shop/pay_result", array('order_id' => $orderInfo['order_id'],"mer_id"=>$orderInfo['mer_id'],"store_id"=>$orderInfo['store_id']));
                    //$url = U("Wap/Shop/pay_result", array('order_id' => $orderInfo['order_id'],"mer_id"=>$orderInfo['mer_id'],"store_id"=>$orderInfo['store_id'],"status"=>"1"));
            }

            $orderInfo['url'] = $url;
        }else{
            if($orderInfo['order_type'] == 'recharge')
                $orderInfo['url'] = '#recharge_'.$orderInfo['order_id'];
            else
                $orderInfo['url'] = '#'.$orderInfo['order_id'];
        }

        return $orderInfo;
    }

    public function getNotificationUrl(){
        if($this->testMode){
            //$site_url = C('config.config_site_url') == '' ? 'http://www.vicisland.ca' : C('config.config_site_url');
            $site_url = C('config.config_site_url') == '' ? 'http://34.212.27.103' : C('config.config_site_url');
        }else{
            if(strpos($_SERVER['HTTP_HOST'],'tutti.app') !== false)
                $site_url = 'https://'.$_SERVER['HTTP_HOST'];
            else
                $site_url = C('config.config_site_url') == '' ? 'https://www.tutti.app' : C('config.config_site_url');
        }


        return $site_url;
    }

    ////***** 3D Secure 2.0 *******/////

    /**
     * @param $order_id 编辑好的订单号
     * @param $card_pan 卡号
     */
    public function cardLookUp($order_id,$card_pan){
        $mpiCardLookup = new MpiCardLookup();
        $mpiCardLookup->setOrderId($order_id);
        $mpiCardLookup->setPan($card_pan);
        $mpiCardLookup->setNotificationUrl($this->getNotificationUrl());


        /****************************** Transaction Object *******************************/

        $mpgTxn = new mpgTransaction($mpiCardLookup);
        //var_dump($mpgTxn);die();

        /******************************* Request Object **********************************/

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode);

        /****************************** HTTPS Post Object *******************************/

        $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();

        //var_dump($mpgResponse);die();
    }

    public function threeDSAuthentication($data,$uid,$from_type){
        if($data['credit_id']){//存储卡的】
            $card_id = $data['credit_id'];
            $card = D('User_card')->field(true)->where(array('id' => $card_id))->find();
            $txnArray['name'] = $card['name'];
            $txnArray['pan'] = $card['card_num'];
            $txnArray['expdate'] = $card['expiry'];

            $vault_card = D('Vault_card')->where(array('user_card_id'=>$data['credit_id']))->find();
            if($vault_card){
                $data_key = $vault_card['data_key'];
            }else{
                $result = $this->addVaultCard($data['credit_id'],$uid);
                if($result['resSuccess'] == "true")
                    $data_key = $result['data_key'];
                else
                    return $result;
            }
        }else{//直接输入卡号的
            $txnArray['name'] = $data['name'];
            $txnArray['pan'] = $data['card_num'];
            $txnArray['expdate'] = transYM($data['expiry']);

            $vault_card = D('Vault_card')->where(array('card_num'=>$data['card_num'],'expiry'=>transYM($data['expiry'])))->find();
            if($vault_card){
                $data_key = $vault_card['data_key'];
            }else {
                $card['card_num'] = $data['card_num'];
                $card['expiry'] = transYM($data['expiry']);
                $card['name'] = $data['name'];
                $card['cvd'] = $data['cvd'];
                $result = $this->addVaultCard(0, $uid, $card);
                if ($result['resSuccess'] == "true")
                    $data_key = $result['data_key'];
                else
                    return $result;
            }
        }

        $order = explode("_",$data['order_id']);
        $order_id = $order[1];
        $order_param = array();
        if($data['note'] && $data['note'] != '')
            $order_param['desc'] = $data['note'];
        if($data['est_time'] && $data['est_time'] != ''){
            $order_param['expect_use_time'] = strtotime($data['est_time']);
        }

        if(count($order_param) > 0)
            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_param);

        if(!$data['order_type']) $data['order_type'] = "shop";


        $error_list = D('Pay_moneris_record_error')->field(true)->where(array('order_id' => $order_id))->select();
        $count = count($error_list);
        if ($count > 0)
            $data['order_id'] = $data['order_id'] . '_' . $count;

        //$this->cardLookUp($data['order_id'],$txnArray['pan']);

        $save = $data['save'] ? $data['save'] : 0;
        $tip = $data['tip'] ? $data['tip'] : 0;
        $coupon_id = $data['coupon_id'] ? $data['coupon_id'] : '';
        $card_user_name = $data['name'] ? $data['name'] : '';

        $orderInfo = '-'.$data['order_type'].'-'.$data['order_id'].'-'.$from_type.'-'.$save.'-'.$tip.'-'.$coupon_id.'-'.$card_user_name.'-'.$data_key.'-';
        //$data_key='gvOULripl7gcabGJmM1vOlnj2';
        $amount=$data['charge_total'];
        $xid = sprintf("%'920d", rand());
        $MD = $xid.$orderInfo.$amount;

        $site_url = $this->getNotificationUrl();

        $merchantUrl = $site_url.'/secure3d';//.$_SERVER["HTTP_REFERER"];
        $accept = $_SERVER['HTTP_ACCEPT'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];


        $mpiThreeDSAuthentication = new MpiThreeDSAuthentication();
        $mpiThreeDSAuthentication->setOrderId($data['order_id']);	//must be the same one that was used in MpiCardLookup call
        $mpiThreeDSAuthentication->setCardholderName($txnArray['name']);
        $mpiThreeDSAuthentication->setPan($txnArray['pan']);
        //$mpiThreeDSAuthentication->setDataKey("8OOXGiwxgvfbZngigVFeld9d2"); //Optional - For Moneris Vault and Hosted Tokenization tokens in place of setPan
        $mpiThreeDSAuthentication->setExpdate($txnArray['expdate']);
        $mpiThreeDSAuthentication->setAmount($amount);
        $mpiThreeDSAuthentication->setThreeDSCompletionInd("Y"); //(Y|N|U) indicates whether 3ds method MpiCardLookup was successfully completed
        $mpiThreeDSAuthentication->setRequestType("01"); //(01=payment|02=recur)
        $mpiThreeDSAuthentication->setPurchaseDate(date("YYYYMMDDHHMMSS")); //(YYYYMMDDHHMMSS)
        $mpiThreeDSAuthentication->setNotificationURL($merchantUrl); //(Website where response from RRes or CRes after challenge will go)
        $mpiThreeDSAuthentication->setChallengeWindowSize("03"); //(01 = 250 x 400, 02 = 390 x 400, 03 = 500 x 600, 04 = 600 x 400, 05 = Full screen)

        $mpiThreeDSAuthentication->setBrowserUserAgent($userAgent);
        $mpiThreeDSAuthentication->setBrowserJavaEnabled("true"); //(true|false)
        $mpiThreeDSAuthentication->setBrowserScreenHeight("1000"); //(pixel height of cardholder screen)
        $mpiThreeDSAuthentication->setBrowserScreenWidth("1920"); //(pixel width of cardholder screen)
        $mpiThreeDSAuthentication->setBrowserLanguage("en-GB"); //(defined by IETF BCP47)

        $mpiRequest['orderId'] = $data['order_id'];
        $mpiRequest['cardholderName'] = $txnArray['name'];
        $mpiRequest['pan'] = $txnArray['pan'];
        $mpiRequest['expdate'] = $txnArray['expdate'];
        $mpiRequest['amount'] = $amount;
        $mpiRequest['ThreeDSCompletionInd'] = "Y";
        $mpiRequest['RequestType'] = "01";
        $mpiRequest['PurchaseDate'] = date("YYYYMMDDHHMMSS");
        $mpiRequest['NotificationURL'] = $merchantUrl;
        $mpiRequest['ChallengeWindowSize'] = "03";
        $mpiRequest['BrowserUserAgent'] = $userAgent;
        $mpiRequest['BrowserJavaEnabled'] = "true";
        $mpiRequest['BrowserScreenHeight'] = "1000";
        $mpiRequest['BrowserScreenWidth'] = "1920";
        $mpiRequest['BrowserLanguage'] = "en-GB";
        $mpiRequest['ProcCountryCode'] = $this->countryCode;

        /****************************** Transaction Object *******************************/

        $mpgTxn = new mpgTransaction($mpiThreeDSAuthentication);

        /******************************* Request Object **********************************/

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode); //false or comment out this line for production transactions

        /****************************** HTTPS Post Object *******************************/
        //file_put_contents("./test_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Moneris3DRequest" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($mpiRequest)."\r\n",FILE_APPEND);
        $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        /************************************* Response *********************************/

        $mpgResponse=$mpgHttpPost->getMpgResponse();

        $resp['requestMode'] = "mpi";

        $resp['responseCode'] = $mpgResponse->getResponseCode();
        $resp['receiptId'] = $mpgResponse->getReceiptId();
        $resp['message'] = $mpgResponse->getMessage();

        $resp['messageType'] = $mpgResponse->getMpiMessageType();
        $resp['transStatus'] = $mpgResponse->getMpiTransStatus();
        $resp['challengeURL'] = $mpgResponse->getMpiChallengeURL();
        $resp['challengeData'] = $mpgResponse->getMpiChallengeData();
        $resp['threeDSServerTransId'] = $mpgResponse->getMpiThreeDSServerTransId();
        $resp['eci'] = $mpgResponse->getMpiEci();
        $resp['cavv'] = $mpgResponse->getMpiCavv();
        $resp['site_url'] = $merchantUrl;

        //file_put_contents("./test_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Moneris3DResponse" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($resp)."\r\n",FILE_APPEND);
        //var_dump($resp);die();
        if($resp['transStatus'] == "C" || $resp['transStatus'] == "Y" || $resp['transStatus'] == "A"){
            $order_md = D('Pay_moneris_md')->where(array('moneris_order_id'=>$data['order_id']))->find();
            $md['order_md'] = $MD;
            $md['create_time'] = time();

            if($order_md){
                D('Pay_moneris_md')->where(array('moneris_order_id'=>$data['order_id']))->save($md);
            }else{
                $md['moneris_order_id'] = $data['order_id'];
                D('Pay_moneris_md')->add($md);
            }
        }

        if($resp['transStatus'] == "C")
        {
            $resp['mpiSuccess'] = "true";
            $resp['version'] = 2;
            $resp['mpiInLineForm'] = '<form name="downloadForm" method="POST" action="'.$resp['challengeURL'].'">
                                            <input type="hidden" name="creq" value="'.$resp['challengeData'].'">
                                      </form>';
            $resp['mpiInLineForm'] .= '<SCRIPT LANGUAGE="Javascript">
                                            function OnLoadEvent()
                                                    {
                                                        document.downloadForm.submit();
                                                    }
                                              OnLoadEvent();
                                        </SCRIPT>';
        }
        else
        {
            //print("\nMpiMessage = " . $mpgResponse->getMpiMessage());
            $resp['mpiSuccess'] = "false";
            $resp['message'] = L("V3_ORDER_RESULT_PAYMENT_FAIL");
            //if(!$resp['message'] || $resp['message'] == '')
            //    $resp['message'] = $mpgResponse->getMessage();
        }

        return $resp;
    }


    public function cavvLookup($cres){
        $mpiCavvLookup = new MpiCavvLookup();
        $mpiCavvLookup->setCRes($cres);

        /****************************** Transaction Object *******************************/

        $mpgTxn = new mpgTransaction($mpiCavvLookup);

        /******************************* Request Object **********************************/

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode); //false or comment out this line for production transactions

        /****************************** HTTPS Post Object *******************************/

        $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        /************************************* Response *********************************/

        $mpgResponse=$mpgHttpPost->getMpgResponse();
        //此为订单号
        $receiptId = $mpgResponse->getReceiptId();

        $receiptIdList = explode("_",$receiptId);
        $order_id = $receiptIdList[1];

        $order = D('Shop_order')->where(array('order_id'=>$order_id))->find();

        //需要从订单信息中重新组装MD中的信息
        $order_md = D('Pay_moneris_md')->where(array('moneris_order_id'=>$receiptId))->find();
        $MD = $order_md['order_md'];

        if($order){
            if ($mpgResponse->getMessage() == "SUCCESS")// && $mpgResponse->getMpiEci() == 5
            {
                $cavv = $mpgResponse->getMpiCavv();
                $eci = $mpgResponse->getMpiEci();
                $threeTransId = $mpgResponse->getMpiThreeDSServerTransId();

                return $this->MPI_Cavv($MD,$cavv,$eci,$threeTransId);
            }else{
                $result['message'] = L("V3_ORDER_RESULT_PAYMENT_FAIL");//$mpgResponse->getMessage();
                $orderInfo = $this->getOrderInfoFromMD($MD);
                $result['url'] = $orderInfo['url'];
                $result['uid'] = $orderInfo['uid'];

                return $result;
            }
        }else{
            return null;
        }
    }
}