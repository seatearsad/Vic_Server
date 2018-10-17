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
            $txnArray['expdate'] = $data['expiry'];
        }


        $txnArray['order_id'] = $data['order_id'];
        $txnArray['cust_id'] = $data['cust_id'];
        $txnArray['amount'] = $data['charge_total'];

//        var_dump($txnArray.$store_id.$api_token); die();

        /**************************** Transaction Object *****************************/

        $mpgTxn = new mpgTransaction($txnArray);

        /****************************** Request Object *******************************/

        $mpgRequest = new mpgRequest($mpgTxn);
        $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
        $mpgRequest->setTestMode(true);

        $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

        $mpgResponse=$mpgHttpPost->getMpgResponse();
        $resp = $this->arrageResp($mpgResponse,$txnArray['pan'],$txnArray['expdate']);

        if($resp['complete'] == 'true' && $data['save'] == 1){//如果需要存储
            $isC = D('User_card')->getCardByUserAndNum($uid,$data['card_num']);
            if(!$isC) {
                D('User_card')->clearIsDefaultByUid($uid);
                $data['is_default'] = 1;
                $data['uid'] = $uid;
                $data['create_time'] = date("Y-m-d H:i:s");
                D('User_card')->field(true)->add($data);
            }
        }

        $this->savePayData($resp,$data['rvarwap'],$data['tip']);

        return $resp;
    }

    //处理返回数据
    public function arrageResp($mpgResponse,$pan,$expiry){
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

        $resp['pan'] = $pan;
        $resp['expiry'] = $expiry;
        //存储支付记录
        D('Pay_moneris_record')->field(true)->add($resp);

        return $resp;
    }

    //存储支付数据
    public function savePayData($resp,$is_wap,$tip){
        if($resp['complete'] == 'true'){//支付成功
            $order = explode("_",$resp['receiptId']);
            $order_id = $order[1];

            $order_param['order_id'] = $order_id;
            $order_param['order_from'] = 0;
            $order_param['order_type'] = 'shop';
            $order_param['pay_time'] = $resp['transDate'].' '.$resp['transTime'];
            $order_param['pay_money'] = $resp['transAmount'];
            $order_param['pay_type'] = 'moneris';
            $order_param['is_mobile'] = $is_wap;
            $order_param['is_own'] = 0;
            $order_param['third_id'] = 0;
            $order_param['invoice_head'] = $resp['txnNumber'];//借用发票头这个字段存储交易号
            $order_param['tip_charge'] = $tip;

            $result = D('Shop_order')->after_pay($order_param);

//            var_dump($result);die($order_id);
        }
    }
}