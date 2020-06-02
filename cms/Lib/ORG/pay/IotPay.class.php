<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2019/1/11
 * Time: 12:10
 */

class IotPay
{
    public function refund($uid,$order_id,$device){
        $order = D('Shop_order')->field(true)->where(array('order_id'=>$order_id,'paid'=>1))->find();
        if($order){
            $where = array('tab_id'=>'alipay','gid'=>7);
            $result = D('Config')->field(true)->where($where)->select();
            foreach ($result as $payData){
                if($payData['name'] == 'pay_alipay_name')
                    $pay_id = $payData['value'];
                if($payData['name'] == 'pay_alipay_key')
                    $pay_key = $payData['value'];
                if($payData['name'] == 'pay_alipay_pid')
                    $pay_url = $payData['value'];
            }

            $channelId = "";
            if($order['pay_type'] == 'weixin'){
                $channelId = 'WX_MICROPAY';
            }else if($order['pay_type'] == 'alipay'){
                $channelId = 'ALIPAY_MICROPAY';
            }

            $data['mchId'] = $pay_id;
            $data['mchRefundNo'] = 'TuttiRefund_'.$order_id.'_'.time();
            //$data['channelId'] = $channelId;
            $data['currency'] = 'CAD';
            $data['refundAmount'] = $order['payment_money']*100;
            $data['clientIp'] = real_ip();
            //$data['device'] =$device;
            //$data['notifyUrl'] = 'https://www.tutti.app/notify';
            $data['loginName'] = 'jwsj218';
            //import('ORG.Crypt.Des');
            //$data['password'] = bin2hex(Des::encrypt('il1234','IotPay66'));
            $data['password'] = $this->encrypt('il1234','IotPay66','98765432');
            $data['payOrderId'] = $order['invoice_head'];
            $data['sign'] = $this->getSign($data,$pay_key);
            //var_dump($data);die();
            import('ORG.Net.Http');
            $http = new Http();
            $pay_url = 'https://api.iotpaycloud.com/v1/refund_order';
            $result = $http->curlPost($pay_url,'params='.json_encode($data));
            //var_dump($result);die();
            return $result;
        }
    }

    private function getSign($params,$key){
        if(!empty($params)){
            $p =  ksort($params);
            if($p){
                $str = '';
                foreach ($params as $k=>$val){
                    if ($val != '')
                        $str .= $k .'=' . $val . '&';
                }
                $strs = rtrim($str, '&');
                //var_dump($strs);
                $sign = md5($strs.'&key='.$key);
                return strtoupper($sign);
            }
        }
    }
    /**
     * @param string $crypt 需要加密的字符串
     * @param string $key 加密使用的密钥
     * @param string $vi 加密使用的向量
     * @return string $crypt 加密后的字符串
     * @des 3DES加密
     */
    private function encrypt($input, $key, $iv, $base64 = true){
//        $size = 8;
//        $input = self::pkcs5_pad($input, $size);
//        $encryption_descriptor = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
//        mcrypt_generic_init($encryption_descriptor, $key, $iv);
//        $data = mcrypt_generic($encryption_descriptor, $input);
//        mcrypt_generic_deinit($encryption_descriptor);
//        mcrypt_module_close($encryption_descriptor);
//        return base64_encode($data);

        $key = $key;
        $iv = $iv;
        $pass_enc = $input;
        $block = mcrypt_get_block_size('des', 'cbc');
        $pad = $block - (strlen($pass_enc) % $block);
        $pass_enc .= str_repeat(chr($pad), $pad);
        $pass_enc = mcrypt_encrypt(MCRYPT_DES, $key, $pass_enc, MCRYPT_MODE_CBC, $iv);
        return strtr(base64_encode($pass_enc), '+/', '-_');
    }

    /**
     * @param string $crypt 需要解密的字符串
     * @param string $key 加密使用的密钥
     * @param string $vi 加密使用的向量
     * @return string $input 解密后的字符串
     * @des 3DES解密
     */
    private function decrypt($crypt, $key, $iv, $base64 = true){
        $crypt = base64_decode($crypt);
        $encryption_descriptor = mcrypt_module_open(MCRYPT_3DES, '', 'cbc', '');
        mcrypt_generic_init($encryption_descriptor, $key, $iv);
        $decrypted_data = mdecrypt_generic($encryption_descriptor, $crypt);
        mcrypt_generic_deinit($encryption_descriptor);
        mcrypt_module_close($encryption_descriptor);
        $decrypted_data = self::pkcs5_unpad($decrypted_data);
        return rtrim($decrypted_data);
    }
    private function pkcs5_pad($text, $blocksize){
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
    private function pkcs5_unpad($text) {
        $pad = ord($text{strlen($text) - 1});
        if ($pad > strlen($text)){
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }
}