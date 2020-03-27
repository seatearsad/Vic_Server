<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2020/3/27
 * Time: 10:45
 */

class EventAction extends BaseAction
{
    public function index(){
        if($_POST){
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
            $txnArray['pan'] = $_POST['c_number'];
            $txnArray['expdate'] = transYM($_POST['e_date']);
            $txnArray['order_id'] = 'Tutti_Ferris_'.time();
            $txnArray['cust_id'] = $this->deliver_session['uid'];
            $txnArray['amount'] = $_POST['choose_num'].".00";

            /**************************** Transaction Object *****************************/

            $mpgTxn = new mpgTransaction($txnArray);

            /****************************** Request Object *******************************/

            $mpgRequest = new mpgRequest($mpgTxn);
            $mpgRequest->setProcCountryCode("CA"); //"US" for sending transaction to US environment
            $mpgRequest->setTestMode(false);

            $mpgHttpPost  =new mpgHttpsPost($store_id,$api_token,$mpgRequest);

            $mpgResponse=$mpgHttpPost->getMpgResponse();

            if($mpgResponse->getResponseCode() != "null" && $mpgResponse->getResponseCode() < 50){//支付成功
                $data['card_name'] = $_POST['c_name'];
                $data['card_num'] = $_POST['c_number'];
                $data['expdate'] = $txnArray['expdate'];
                $data['order_id'] = $txnArray['order_id'];
                $data['txnNumber'] = $mpgResponse->getTxnNumber();

                //D('Deliver_img')->where(array('uid'=>$this->deliver_session['uid']))->save($data);

                //D('Deliver_user')->where(array('uid'=>$this->deliver_session['uid']))->save(array('reg_status'=>4,'last_time'=>time()));

                $result = array('error_code' => false, 'msg' => L('_PAYMENT_SUCCESS_'));
            }else{
                $result = array('error_code' => true, 'msg' => $mpgResponse->getMessage());
            }
            $this->ajaxReturn($result);
        }else {
            $this->display();
        }
    }
}