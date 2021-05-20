<?php
class PayModel extends Model{
	public function __construct() {}
	/*根据 支付平台的英文 和 是否移动端支付 得到中文名称*/
	public function get_pay_name($pay_type,$is_mobile_pay, $paid = 1){
		switch($pay_type){
			case 'alipay':
				$pay_type_txt = L('_ALIPAY_TXT_');
				break;
			case 'tenpay':
				$pay_type_txt = L('_FINANCE_PAYMENT_');
				break;
			case 'yeepay':
				$pay_type_txt = L('_YIBAO_PAYMENT_');
				break;
			case 'allinpay':
				$pay_type_txt = L('_TONGLIAN_PAYMENT_');
				break;
			case 'chinabank':
				$pay_type_txt = L('_CHINA_BANK_PAY_');
				break;
			case 'weixin':
				$pay_type_txt = L('_WEICHAT_PAY_');
				break;
			case 'baidu':
				$pay_type_txt = L('_BAIDU_PAY_');
				break;
			case 'unionpay':
				$pay_type_txt = L('_UNIONPAY_PAYMENT_');
				break;
			case 'weifutong':
				$pay_type_txt = C('config.pay_weifutong_alias_name');
				break;
            case 'Cash':
			case 'offline':
				$pay_type_txt = L('_CASH_ON_DELI_');
				break;
            case 'moneris':
                $pay_type_txt = "Credit/Debit";
                break;
			default:
				if ($paid) {
					$pay_type_txt = L('_BALANCE_PAYMENT_');
				} else {
					$pay_type_txt = L('_UNPAID_TXT_');
					return L('_UNPAID_TXT_');
				}
				
		}
		if($is_mobile_pay == 1){
			$pay_type_txt .= '('.L('_WEICHAT_END_').')';
		} elseif ($is_mobile_pay == 2 || $is_mobile_pay == 3) {
			$pay_type_txt .= '(iOS)';
		} elseif($is_mobile_pay == 4){
            $pay_type_txt .= '(Android)';
        }
		return $pay_type_txt;
	}
}

?>