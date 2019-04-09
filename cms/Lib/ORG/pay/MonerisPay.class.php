<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/10/10
 * Time: 10:51
 */

class MonerisPay
{
    public function payment($data,$uid){
        import('@.ORG.pay.MonerisPay.mpgClasses');

        $where = array('tab_id'=>'moneris','gid'=>7);
        $result = D('Config')->field(true)->where($where)->select();
        foreach($result as $v){
            if($v['info'] == 'store_id')
                $store_id = $v['value'];
            elseif ($v['info'] == 'token')
                $api_token = $v['value'];
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
        $order = explode("_",$data['order_id']);
        $order_id = $order[1];
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

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode(false);

        $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();
        $resp = $this->arrageResp($mpgResponse,$txnArray['pan'],$txnArray['expdate'],0,$order_id);

        if($resp['responseCode'] < 50 && $data['save'] == 1){//如果需要存储
            $isC = D('User_card')->getCardByUserAndNum($uid,$data['card_num']);
            if(!$isC) {
                D('User_card')->clearIsDefaultByUid($uid);
                $data['is_default'] = 1;
                $data['uid'] = $uid;
                $data['create_time'] = date("Y-m-d H:i:s");
                //存储的时候为YYMM
                $data['expiry'] = transYM($data['expiry']);
                D('User_card')->field(true)->add($data);
            }
        }

        if($resp['responseCode'] < 50){
            //处理优惠券
            if($data['coupon_id']){
                $now_coupon = D('System_coupon')->get_coupon_by_id($data['coupon_id']);
                if(!empty($now_coupon)){
                    $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id'=>$data['coupon_id']))->find();
                    $coupon_real_id = $coupon_data['coupon_id'];
                    $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                    $in_coupon = array('coupon_id'=>$data['coupon_id'],'coupon_price'=>$coupon['discount']);

                    D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($in_coupon);
                }
            }
        }
        if($uid != 0)
            $this->savePayData($resp,$data['rvarwap'],$data['tip'],$data['order_type']);

        return $resp;
    }

    //处理返回数据 $record_type 存储记录的类型 0初次支付记录 1删单退款记录（用户删单、系统删单）2 二次付款记录 3退还部分款项记录
    public function arrageResp($mpgResponse,$pan,$expiry,$record_type = 0,$order_id){
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
    public function savePayData($resp,$is_wap,$tip,$order_type){
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
                $order_param['is_mobile'] = $is_wap;
                $order_param['is_own'] = 0;
                $order_param['third_id'] = 0;
                $order_param['invoice_head'] = $resp['txnNumber'];//借用发票头这个字段存储交易号
                $order_param['tip_charge'] = $tip;
                //garfunkel add 19.4.9
                if($_POST['note'] && $_POST['note'] != '')
                    $order_param['desc'] = $_POST['note'];

                $result = D('Shop_order')->after_pay($order_param);
            }

//            var_dump($result);die($order_id);
        }
    }

    /*
     * $order_id 为系统原始订单ID
     * $change_amount 为要退还的金额 当不输入是为-1，即订单金额全部退回
    */
    public function refund($uid,$order_id,$change_amount = -1,$record_type = 1){
        $order = D('Shop_order')->field(true)->where(array('order_id'=>$order_id,'paid'=>1,'pay_type'=>'moneris'))->find();
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
                $where = array('tab_id'=>'moneris','gid'=>7);
                $result = D('Config')->field(true)->where($where)->select();
                foreach($result as $v){
                    if($v['info'] == 'store_id')
                        $store_id = $v['value'];
                    elseif ($v['info'] == 'token')
                        $api_token = $v['value'];
                }
//                var_dump($txnArray);
                import('@.ORG.pay.MonerisPay.mpgClasses');
                $mpgTxn = new mpgTransaction($txnArray);

                $mpgRequest = new mpgRequest($mpgTxn);
                $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
                $mpgRequest->setTestMode(false);

                $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);
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
                    $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
                    $mpgRequest->setTestMode(false);
                    $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);
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
            import('@.ORG.pay.MonerisPay.mpgClasses');
            $mpgTxn = new mpgTransaction($txnArray);

            $mpgRequest = new mpgRequest($mpgTxn);
            $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
            $mpgRequest->setTestMode(false);

            $where = array('tab_id'=>'moneris','gid'=>7);
            $result = D('Config')->field(true)->where($where)->select();
            foreach($result as $v){
                if($v['info'] == 'store_id')
                    $store_id = $v['value'];
                elseif ($v['info'] == 'token')
                    $api_token = $v['value'];
            }

            $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);
            $mpgResponse=$mpgHttpPost->getMpgResponse();
            $resp = $this->arrageResp($mpgResponse,'','',3,$order_id);

            return $resp;
        }
    }

    public function addPay($uid,$order_id,$change_amount){
        import('@.ORG.pay.MonerisPay.mpgClasses');
        $where = array('tab_id'=>'moneris','gid'=>7);
        $result = D('Config')->field(true)->where($where)->select();
        foreach($result as $v){
            if($v['info'] == 'store_id')
                $store_id = $v['value'];
            elseif ($v['info'] == 'token')
                $api_token = $v['value'];
        }

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
        $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode(false);

        $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();
        $resp = $this->arrageResp($mpgResponse,$txnArray['pan'],$txnArray['expdate'],2,$order_id);

        return $resp;
    }
}