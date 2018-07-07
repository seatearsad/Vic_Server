<?php

class Percent_rateModel extends Model
{
    //获取抽成比例
    public function get_percent($mer_id,$type,$money)
    {
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();

        if ($now_mer_pr) {
            if ($now_mer_pr[$type . '_percent'] >= 0 && $now_mer_pr[$type .'_percent']!='') {
                $percent = $this->percent_detail($mer_id,$type,'mer_type',$money);
                if(empty($percent)){
                    return $now_mer_pr[$type . '_percent'];
                }else{
                    return $percent;
                }
            } elseif ($now_mer_pr['merchant_percent'] >= 0 &&$now_mer_pr['merchant_percent']!='') {
                $percent = $this->percent_detail($mer_id,$type,'merchant',$money);

                if(empty($percent)){
                    return $now_mer_pr['merchant_percent'];
                }else{
                    return $percent;
                }
            } elseif ( C('config.' . $type . '_percent') >= 0  ) {
                $percent = $this->percent_detail($mer_id,$type,'sys_type',$money);
                if(empty($percent)){
                    return C('config.' . $type . '_percent');

                }else{

                    return $percent;
                }
            } elseif ( C('config.platform_get_merchant_percent') >= 0) {
                $percent = $this->percent_detail($mer_id,$type,'system',$money);
                if(empty($percent)){
                    return C('config.platform_get_merchant_percent');
                }else{
                    return $percent;
                }
            } else {
                return 0;
            }
        } else {
            if (C('config.' . $type . '_percent') >= 0) {
                return C('config.' . $type . '_percent');
            } elseif (C('config.platform_get_merchant_percent') >= 0) {
                return C('config.platform_get_merchant_percent');
            } else {
                return 0;
            }
        }
    }

    //抽成细则的筛选
    public function percent_detail($mer_id,$type,$level,$money){
        $percent_detail = M('Percent_detail')->select();
        if(!empty($percent_detail)){
            $model = M('Percent_detail_by_type');
            $system_percent = $model->where(array('fid'=>0))->find();
            $mer_percent = $model->where(array('fid'=>$mer_id))->find();
            $percent  = 0;
            $i = 0;
            $in_detail = false;
            switch($level){
                case 'mer_type':
                    $percent_arr = explode(',',$mer_percent[$type.'_percent_detail']);
                    break;
                case 'merchant';
                    $percent_arr = explode(',',$mer_percent['merchant_percent_detail']);
                    break;
                case 'sys_type':
                    $percent_arr = explode(',',$system_percent[$type.'_percent_detail']);
                    break;
            }

            foreach ($percent_detail as $pv) {
                if($pv['money_start'] <= $money && $money <= $pv['money_end']){
                    if($percent_arr[$i]>0&&$percent_arr[$i]!=''){
                        $percent = $percent_arr[$i];
                    }else{
                        $percent = $pv['percent'];
                    }
                    $in_detail = true;
                }
                $i++;
            }
            if($in_detail){
                return $percent;
            }else{
                return;
            }
        }else{
            return ;
        }
    }
    //获取分佣比例
    public function get_rate($mer_id, $type)
    {
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        if ($now_mer_pr) {
            if ($now_mer_pr[$type . '_rate'] >= 0 &&$now_mer_pr[$type . '_rate']!='') {
                return $now_mer_pr[$type . '_rate'];
            } elseif ( $now_mer_pr['merchant_rate'] >= 0 &&$now_mer_pr['merchant_rate']!='') {
                return $now_mer_pr['merchant_rate'];
            } elseif (C('config.' . $type . '_rate') >= 0) {
                return C('config.' . $type . '_rate');
            } elseif ( C('config.platform_get_merchant_rate') >= 0) {
                return C('config.platform_get_merchant_rate');
            } else {
                return 0;
            }
        } else {
            if (C('config.' . $type . '_rate') >= 0 ) {
                return C('config.' . $type . '_rate');
            } elseif (C('config.platform_get_merchant_rate') >= 0) {
                return C('config.platform_get_merchant_rate');
            } else {
                return 0;
            }
        }
    }

    //判断是否可以使用线下支付
    public function pay_offline($mer_id,$type){
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        if ($now_mer_pr) {
            if($type=='group'||$type=='shop'||$type=='meal'){
                return $now_mer_pr[$type.'_offline']&&$now_mer_pr['merchant_offline']&&C('config.'.$type.'_offline')&&C('config.pay_offline_open');
            }else{
                return $now_mer_pr['merchant_offline']&&C('config.'.$type.'_offline')&&C('config.pay_offline_open');
            }
        } else {
            return C('config.'.$type.'_offline')&&C('config.pay_offline_open');
        }
    }

    //获取用户分佣比例
    public function get_user_spread_rate($mer_id,$type,$group_id){
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        $first_rate='';
        $second_rate='';
        $third_rate='';
        $rate_type='';
        switch($type){
            case 'group':
                $now_group = M('Group')->where(array('group_id'=>$group_id))->find();
                //if($now_group['spread_rate']&&$now_mer_pr['group_first_rate']&&C('config.group_first_rate')&&C('config.user_spread_rate')){
                if($now_group['spread_rate']>=0){
                    $first_rate=$now_group['spread_rate'];
                    $second_rate=$now_group['sub_spread_rate']>0?$now_group['sub_spread_rate']:0;
                    $third_rate=$now_group['third_spread_rate']>0?$now_group['third_spread_rate']:0;
                    $rate_type='group';
                }elseif($now_mer_pr['group_first_rate']>=0&&$now_mer_pr['group_first_rate']!=''){
                    $first_rate=$now_mer_pr['group_first_rate'];
                    $second_rate=$now_mer_pr['group_second_rate']>0?$now_mer_pr['group_second_rate']:0;
                    $third_rate=$now_mer_pr['group_third_rate']>0?$now_mer_pr['group_third_rate']:0;
                    $rate_type='merchant';
                }elseif(C('config.group_first_rate')>=0){
                    $first_rate=C('config.group_first_rate');
                    $second_rate=C('config.group_second_rate')>0?C('config.group_second_rate'):0;
                    $third_rate=C('config.group_third_rate')>0?C('config.group_third_rate'):0;
                    $rate_type='system_group';
                }elseif(C('config.user_spread_rate')>=0){
                    $first_rate=C('config.user_spread_rate');
                    $second_rate=C('config.user_first_spread_rate')>0?C('config.user_first_spread_rate'):0;
                    $third_rate=C('config.user_second_spread_rate')>0?C('config.user_second_spread_rate'):0;
                    $rate_type='system';
                }else{
                    $first_rate=0;
                    $second_rate=0;
                    $third_rate=0;
                    $rate_type='';
                }
                //  }
                break;
            default:
                if($now_mer_pr){
                    if($now_mer_pr[$type.'_first_rate']>=0){
                        $first_rate=$now_mer_pr[$type.'_first_rate'];
                        $second_rate=$now_mer_pr[$type.'_second_rate']>0?$now_mer_pr[$type.'_second_rate']:0;
                        $third_rate=$now_mer_pr[$type.'_third_rate']>0?$now_mer_pr[$type.'_third_rate']:0;
                        $rate_type='merchant';
                    }elseif(C('config.'.$type.'_first_rate')>=0){
                        $first_rate=C('config.'.$type.'_first_rate');
                        $second_rate=C('config.'.$type.'_second_rate')>0?C('config.'.$type.'_second_rate'):0;
                        $third_rate=C('config.'.$type.'_third_rate')>0?C('config.'.$type.'_third_rate'):0;
                        $rate_type='system_'.$type;
                    }elseif(C('config.user_spread_rate')>=0){
                        $first_rate=C('config.user_spread_rate');
                        $second_rate=C('config.user_first_spread_rate')>0?C('config.user_first_spread_rate'):0;
                        $third_rate=C('config.user_second_spread_rate')>0?C('config.user_second_spread_rate'):0;
                        $rate_type = 'system';
                    }else{
                        $first_rate=0;
                        $second_rate=0;
                        $third_rate=0;
                        $rate_type = '';
                    }
                }else{
                    $first_rate=C('config.user_spread_rate');
                    $second_rate=C('config.user_first_spread_rate');
                    $third_rate=C('config.user_second_spread_rate');
                    $rate_type = 'system';
                }
                break;

        }

        return array(
            'first_rate'=>$first_rate,
            'second_rate'=>$second_rate,
            'third_rate'=>$third_rate,
            'type'=>$rate_type,
        );
    }

    //积分最大使用量
    public function get_max_core_use($mer_id,$type){
        $where['mer_id'] = $mer_id;
        $now_mer_pr = M('Merchant_percent_rate')->where($where)->find();
        if ($now_mer_pr) {
            if ($now_mer_pr['merchant_'.$type . '_score_max'] >= 0 &&$now_mer_pr['merchant_'.$type . '_score_max']!='') {
                return $now_mer_pr['merchant_'.$type . '_score_max'];
            } elseif ( $now_mer_pr['merchant_score_max'] >= 0 &&$now_mer_pr['merchant_score_max']!='') {
                return $now_mer_pr['merchant_score_max'];
            } elseif (C('config.' . $type . '_score_max') >= 0&&$type!='store') {
                return C('config.' . $type . '_score_max');
            } elseif ( C('config.user_score_max_use') >= 0) {
                return C('config.user_score_max_use');
            } else {
                return 0;
            }
        } else {
            if (C('config.' . $type . '_score_max') >= 0 &&$type!='store') {
                return C('config.' . $type . '_score_max');
            } elseif (C('config.user_score_max_use') >= 0) {
                return C('config.user_score_max_use');
            } else {
                return 0;
            }
        }
    }
    //元宝定制
    public function get_extra_money($order){
        switch($order['order_type']){
            case 'group':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'meal':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'shop':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'appoint':

                if($order['paid'] == 3){
                    $total_money = $order['product_price'];
                }else{
                    if($order['is_initiative']==1){
                        //剩余钱的逻辑
                        if($order['product_id']){
                            //剩余钱的逻辑
                            $total_money = $order['product_balance_pay']+$order['user_pay_money']+$order['product_score_deducte']+$order['product_coupon_price'] + $order['product_payment_price'];

                        }else{
                            $total_money =$order['balance_pay'] + $order['pay_money'] + $order['product_balance_pay']+$order['user_pay_money']+$order['product_score_deducte']+$order['product_coupon_price'] + $order['product_payment_price'];
                        }
                    }else{
                        if($order['product_id']){
                            $money = $order['product_payment_price'];
                        }else{
                            $money = $order['payment_money'];
                        }
                        $total_money  = $money;
                    }

                }



                break;
            case 'store':
                $total_money = $order['balance_pay']+$order['merchant_balance']+$order['payment_money']+$order['score_deducte'];
                break;
            case 'cash':
                $total_money = $order['total_price'];
                break;
        }

        $now_merchant = M('Merchant')->where(array('mer_id'=>$order['mer_id']))->find();
        if($now_merchant['score_get']>=0){
            $score_percent = $now_merchant['score_get'];
        }else{
            $score_percent = C('config.user_score_get');
        }

        if($order['extra_price']>0){
            if($order['score_used_count']>0){
                if($order['score_used_count']<$order['extra_price']){
                    $give_money =$order['extra_price']-$order['score_used_count'];
                }else{
                    $give_money =0 ;
                }
            }else{
                $give_money =bcmul($total_money,$score_percent,2) ;
            }
        }else{
            $give_money =bcmul($total_money,$score_percent,2) ;
        }

        return $give_money;
    }

}