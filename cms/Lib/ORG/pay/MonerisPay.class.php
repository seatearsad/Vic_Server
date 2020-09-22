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

        $this->countryCode = 'CA';
//        $this->testMode = true;
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

        //判断金额还需在api user_card_default 方法中修改
        if($data['order_type'] == 'recharge' || $data['charge_total'] >= 251 || $store['pay_secret'] == 1){
            return $this->mpi_transaction($data,$uid,$from_type);
        }

        $txnArray['type'] = 'purchase';
        $txnArray['crypt_type'] = '7';

        if($data['credit_id']){//存储卡的
            $card_id = $data['credit_id'];
            $card = D('User_card')->field(true)->where(array('id'=>$card_id))->find();
            $txnArray['pan'] = $card['card_num'];
            $txnArray['expdate'] = $card['expiry'];
        }else{//直接输入卡号的
            $txnArray['pan'] = $data['card_num'];
            $txnArray['expdate'] = transYM($data['expiry']);
        }

        //或者这张订单之前的错误回复
        $error_list = D('Pay_moneris_record_error')->field(true)->where(array('order_id'=>$order_id))->select();
        $count = count($error_list);
        if($count > 0)
            $data['order_id'] = $data['order_id'].'_'.$count;

        $txnArray['order_id'] = $data['order_id'];
        $txnArray['cust_id'] = $data['cust_id'];
        $txnArray['amount'] = $data['charge_total'];

//        var_dump($txnArray.$store_id.$api_token); die();

        /**************************** Transaction Object *****************************/

        $mpgTxn = new mpgTransaction($txnArray);

        /****************************** Request Object *******************************/

        /****************************** CVD Object ********************************/
        $card_cvd = '';
        if($data['cvd'] && $data['cvd'] != ''){
            $card_cvd = $data['cvd'];
        }

        if($card_cvd != '') {
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

        $mpgHttpPost  =new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();
        $resp = $this->arrageResp($mpgResponse,$txnArray['pan'],$txnArray['expdate'],0,$order_id,$card_cvd);

        if($resp['responseCode'] != "null" && $resp['responseCode'] < 50 && $data['save'] == 1){//如果需要存储
            $isC = D('User_card')->getCardByUserAndNum($uid,$data['card_num']);
            if(!$isC) {
                D('User_card')->clearIsDefaultByUid($uid);
                $data['is_default'] = 1;
                $data['uid'] = $uid;
                $data['create_time'] = date("Y-m-d H:i:s");
                //存储的时候为YYMM
                $data['expiry'] = transYM($data['expiry']);
                $data['credit_id'] = D('User_card')->field(true)->add($data);
            }
        }

        if($resp['responseCode'] != "null" && $resp['responseCode'] < 50) {
            //如果需要验证CVD
            if ($card_cvd != '') {
                if (strpos($resp['cvdResultCode'], 'M') !== false || strpos($resp['cvdResultCode'], 'Y') !== false) {
                    if ($data['credit_id']) {
                        $data_card['cvd'] = $card_cvd;
                        $data_card['status'] = 1;
                        $data_card['verification_time'] = time();
                        D('User_card')->field(true)->where(array('id' => $data['credit_id']))->save($data_card);
                    }
                } else {
                    //验证CVD 为通过 将responseCode修改后存储一次error记录并退款
                    $resp['responseCode'] = 7513;
                    $resp['message'] = 'CVD Error';
                    D('Pay_moneris_record_error')->add($resp);
                    $this->refund($uid, $order_id);
                }
            }

            //处理优惠券
            if($data['coupon_id']){
                //如果选择的为活动优惠券
                if(strpos($data['coupon_id'],'event')!== false) {
                    $event = explode('_',$data['coupon_id']);
                    $event_coupon_id = $event[2];
                    $list = D('New_event')->getUserCoupon($uid,0,-1,$event_coupon_id);
                    $now_coupon = reset($list);
                    if(!empty($now_coupon)){
                        $coupon = D('New_event_coupon')->where(array('id'=>$now_coupon['event_coupon_id']))->find();
                        $in_coupon = array('coupon_id' => $data['coupon_id'], 'coupon_price' => $coupon['discount']);
                        D('Shop_order')->field(true)->where(array('order_id' => $order_id))->save($in_coupon);
                    }
                }else{
                    $now_coupon = D('System_coupon')->get_coupon_by_id($data['coupon_id']);
                    if (!empty($now_coupon)) {
                        $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $data['coupon_id']))->find();
                        $coupon_real_id = $coupon_data['coupon_id'];
                        $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                        $in_coupon = array('coupon_id' => $data['coupon_id'], 'coupon_price' => $coupon['discount']);

                        D('Shop_order')->field(true)->where(array('order_id' => $order_id))->save($in_coupon);
                    }
                }
            }
        }

        if($uid != 0)
            $this->savePayData($resp,$data['rvarwap'],$data['tip'],$data['order_type'],$data['note'],$data['est_time']);

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

//            var_dump($result);die($order_id);
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

        if(strpos($_SERVER['HTTP_HOST'],'tutti.app') !== false)
            $site_url = 'https://'.$_SERVER['HTTP_HOST'];
        else
            $site_url = C('config.config_site_url') == '' ? 'https://www.tutti.app' : C('config.config_site_url');

        //$site_url = C('config.config_site_url') == '' ? 'http://www.vicisland.ca' : C('config.config_site_url');
//        $site_url = C('config.config_site_url') == '' ? 'http://54.190.29.18' : C('config.config_site_url');
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
        //var_dump($txnArray);//die();

        $mpgTxn = new mpgTransaction($txnArray);
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
                $resp['message'] = $mpgResponse->getMessage();
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

    public function MPI_Cavv($MD,$cavv,$eci){
        $orderInfo = $this->getOrderInfoFromMD($MD);

        $type = 'cavv_purchase';
        $order_id = $orderInfo['orderId'];
        $cust_id = $orderInfo['uid'];
        $amount = $orderInfo['amount'];
        $pan = $orderInfo['card_num'];
        $expiry_date = $orderInfo['expiry'];
        $crypt_type = '7';

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

        $mpgTxn = new mpgTransaction($txnArray);

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode($this->countryCode); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode($this->testMode);

        $mpgHttpPost = new mpgHttpsPost($this->store_id,$this->api_token,$mpgRequest);

        $mpgResponse = $mpgHttpPost->getMpgResponse();
        $resp = $this->arrageResp($mpgResponse,$txnArray['pan'],$txnArray['expdate'],0,$order_id);

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
            if($orderInfo['coupon_id']){
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

                        $in_coupon = array('coupon_id' => $data['coupon_id'], 'coupon_price' => $coupon['discount']);

                        D('Shop_order')->field(true)->where(array('order_id' => $orderInfo['order_id']))->save($in_coupon);
                    }
                }
            }
        }

        //1Web(PC) 2Wap 3App
        $is_wap = $orderInfo['order_from']-1;

        $this->savePayData($resp,$is_wap,$orderInfo['tip'],$orderInfo['order_type']);

        $resp['url'] = $orderInfo['url'];

        return $resp;
    }

    public function getOrderInfoFromMD($MD){
        //$orderInfo = '-'.$data['order_type'].'-'.$data['order_id'].'-'.$from_type.'-'.$save.'-'.$tip.'-'.$coupon_id.'-'.$card_user_name.'-'.$data_key.'-';
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

        //1Web(PC) 2Wap 3App
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
        }elseif($orderInfo['order_from'] == 2){
            if($orderInfo['order_type'] == 'recharge')
                $url = U("Wap/My/my_money");
            else {
                if(strpos($_SERVER['HTTP_HOST'],'tutti.app') !== false)
                    $url = 'https://'.$_SERVER['HTTP_HOST'].'/wap.php?g=Wap&c=Shop&a=status&order_id=' . $orderInfo['order_id'];
                else
                    $url = U("Wap/Shop/status", array('order_id' => $orderInfo['order_id']));
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
}