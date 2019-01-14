<?php
class PayAction extends BaseAction{
    protected function _initialize() {
        parent::_initialize();
        if(defined('IS_INDEP_HOUSE')){
            $this->indep_house = C('INDEP_HOUSE_URL');
        }else{
            $this->indep_house = 'wap.php';
        }
    }
    public function check(){
        if(empty($this->user_session)){
            $this->error_tips(L('_B_MY_LOGINFIRST_'),U('Login/index'));
        }
        if($this->config['open_extra_price']==0 && empty($this->user_session['phone'])){
            $this->error_tips(L('_BIND_PHONE_BEFORECON_'),U('My/bind_user',array('referer'=>urlencode(U('Pay/check',$_GET)))));
        }

        if(!in_array($_GET['type'],array('group','meal','weidian','takeout', 'food', 'foodPad','recharge','appoint','wxapp', 'store', 'shop', 'mall', 'plat','balance-appoint'))){
            $this->error_tips(L('_ORDER_CANNT_IDEN_'));
        }
        $group_pay_offline = true;
        if($_GET['type'] == 'group'){
            $now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
            if($now_order['order_info']['group_share_num']>0||$now_order['order_info']['pin_num']>0)$group_pay_offline=false;
        }else if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
            $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']), false, $_GET['type']);
            if ($now_order['order_info']['paid'] == 2) $this->assign('notCard',true);
            $_GET['type']  = 'meal';
        }else if($_GET['type'] == 'weidian'){
            $now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
            $this->assign('notCard',true);
        }else if($_GET['type'] == 'recharge'){
            $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
            $this->assign('notCard',true);
        }else if($_GET['type'] == 'appoint'){
            $now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
        }else if($_GET['type'] == 'wxapp'){
            $_GET['notOffline'] = true;
            $now_order = D('Wxapp_order')->get_pay_order($_GET['uid'],intval($_GET['order_id']));
            $this->assign('notCard',true);
        }else if($_GET['type'] == 'store'){
            $_GET['notOffline'] = true;
            $now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
            //$this->assign('notCard',true);
        }else if($_GET['type'] == 'shop' || $_GET['type'] == 'mall'){
            $now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));

        }else if($_GET['type'] == 'plat'){
            $now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
            $now_order['order_info']['extra_price'] =  $now_order['order_info']['order_info']['extra_price'];
            if($now_order['order_info']['status']==1){
                $this->error_tips(L('_B_MY_PAIDTHISORDER_'));
            }
        }else if($_GET['type'] == 'balance-appoint'){
            $now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_GET['order_id']));
        }else{
            $this->error_tips(L('_ILLEGAL_ORDER_'));
        }
        if($now_order['error'] == 1){
            if($now_order['url']){
                $this->success_tips($now_order['msg'],$now_order['url']);
            }else{
                $this->error_tips($now_order['msg']);
            }
        }
        $order_info = $now_order['order_info'];
        //ADD garfunkel
        $order_info['order_name'] = lang_substr($order_info['order_name'],C('DEFAULT_LANG'));
        if($this->config['open_extra_price']==1&&($order_info['order_type']!='appoint'||$order_info['discount_status'])){
            $user_score_use_percent=(float)$this->config['user_score_use_percent'];
            $order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
        }else{
            $order_info['order_extra_price'] = 0;
            $order_info['extra_price'] = 0;
        }
        $this->assign('order_info',$order_info);

        //garfunkel 如果修改信用卡的选择
        if($_GET['card_id']){
            D('User_card')->clearIsDefaultByUid($this->user_session['uid']);
            D('User_card')->field(true)->where(array('id'=>$_GET['card_id']))->save(array('is_default'=>1));
        }

        $card_list = D('User_card')->getCardListByUid($this->user_session['uid']);
        if(count($card_list) > 0)
            $this->assign('card',$card_list[0]);

        if($this->is_app_browser){
            $this->display();die;
        }

        if($order_info['mer_id']){
            $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
            if(empty($notOffline)){
                if ($now_merchant) {
                    $notOffline =($pay_offline && $now_merchant['is_offline'] == 1) ? 0 : 1;
                }
            }
        }

        //判断线下支付
        if($order_info['order_type'] == 'plat'){
            $this->assign('pay_offline',$order_info['pay_offline']);
        }else{
            $pay_offline = D('Percent_rate')->pay_offline($order_info['mer_id'],$_GET['type']);
            $this->assign('pay_offline',$pay_offline&&$group_pay_offline);
        }

        //判断对接
        if(C('butt_open')){
            import('ORG.Net.Http');
            $http = new Http();
            $postArr = array(
                'butt_id' => $order_info['mer_id'],
                'order_id' => $order_info['order_id'],
                'order_type' => $order_info['order_type'],
                'order_name' => $order_info['order_name'],
                'order_num' => $order_info['order_num'],
                'order_price' => $order_info['order_price']*100,
                'order_total_money' => $order_info['order_total_money'],
                'redirct_url' => $this->config['site_url'].'/'.$this->indep_house.'?c=Pay&a=butt_pay',
            );
            $return = Http::curlPost(C('butt_pay_post_url'),get_butt_encrypt_key($postArr,C('butt_key')));
            if($return['err_code']){
                $this->error($return['err_msg']);
            }else if($return['result']){
                redirect($return['result']);
            }else{
                $this->error(L('_ERROR_PAYMENT_'));
            }
        }

        //得到微信优惠金额,判断用户能否购买此团购
        $cheap_info = array('can_buy'=>true,'can_cheap'=>false,'wx_cheap'=>0);
        if($_GET['type'] == 'group'){
            $now_user = D('User')->get_user($this->user_session['uid']);
            if($this->config['weixin_buy_follow_wechat'] == 2 && !empty($_SESSION['openid']) && empty($now_user['is_follow'])){
                $cheap_info['can_buy'] = false;
            }
            $cheap_info['wx_cheap'] = D('Group')->get_group_cheap($order_info['group_id']);
            $cheap_info['wx_cheap'] = $cheap_info['wx_cheap']*$order_info['order_num'];
            if($cheap_info['wx_cheap']){
                $cheap_info['can_cheap'] = true;
                if($_SESSION['openid']){
                    if($this->config['weixin_buy_follow_wechat'] == 1 && empty($now_user['is_follow'])){
                        $cheap_info['can_cheap'] = false;
                    }
                }else{
                    $cheap_info['can_cheap'] = false;
                }
                $cheap_info['wx_cheap'] = round($cheap_info['wx_cheap'] * 100) /100;
                $_SESSION['wx_cheap'] = $cheap_info['wx_cheap'];
            }
        }
        $this->assign('cheap_info',$cheap_info);

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);

        if(empty($now_user)){
            $this->error_tips(L('_NO_GET_INFO_'));
        }
        $now_user['now_money'] = floatval($now_user['now_money']);

        //子商户设置不允许使用平台余额支付
        if($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_sys_pay']!=1){
            $now_user['now_money'] = 0;
        }
        $this->assign('now_user',$now_user);

        if($_GET['type'] != 'recharge' && $_GET['type'] != 'weidian' && ($order_info['business_type']==''||$order_info['business_type']!='card_new_recharge')) {
            //商家优惠券
            if ($this->is_app_browser) {
                $platform = 'app';
            } else if ($this->is_wexin_browser) {
                $platform = 'weixin';
            } else {
                $platform = 'wap';
            }
            $order_info['total_money'] = $order_info['order_total_money'];
            $tmp_order = $order_info;

            $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'], $order_info['mer_id']);
            if($card_info['status']) {
                $merchant_balance = $card_info['card_money'] + $card_info['card_money_give'];
                if (isset($order_info['discount_status']) && !$order_info['discount_status'] || empty($card_info)||empty($card_info['discount'])) {
                    $card_info['discount'] = 10;
                }
                $_SESSION['discount'] = $card_info['discount'];
                $tmp_order['uid'] = $this->user_session['uid'];
                $tmp_order['total_money'] = $order_info['total_money'] * $card_info['discount'] / 10 - $cheap_info['wx_cheap'];

                if ((!isset($order_info['discount_status']) || $order_info['discount_status'])&&$_GET['unmer_coupon']!=1) {
                    if (!empty($_GET['card_id'])) {
                        $now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($_GET['card_id'], $this->user_session['uid']);
                        $now_coupon['type'] = 'mer';
                        $this->assign('now_coupon', $now_coupon);
                    } else {
                        if (empty($_GET['merc_id'])) {
                            if (!empty($order_info['business_type'])) {
                                $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform, $order_info['business_type']);
                            } else {
                                $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform);
                            }
                            $mer_coupon = reset($card_coupon_list);

                        } else {
                            $mer_coupon = D('Card_new_coupon')->get_coupon_info($_GET['merc_id']);
                        }

                    }
                }
            }else{
                if ($cheap_info['can_cheap']) {
                    $tmp_order['total_money']-=$cheap_info['wx_cheap'];
                }
            }

            //平台优惠券
            if (($tmp_order['total_money'] > $mer_coupon['discount'] || empty($mer_coupon))&&$_GET['unsys_coupon']!=1&&($order_info['discount_status']||!isset($order_info['discount_status']))) {
                $tmp_order['total_money'] -= empty($mer_coupon['discount']) ? 0 : $mer_coupon['discount'];
                if (empty($_GET['sysc_id'])) {
                    if (!empty($order_info['business_type'])) {
                        $now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform, $order_info['business_type']);
                    } else {
                        $now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform);
                    }
                    $system_coupon = reset($now_coupon);

                } else {
                    $system_coupon = D('System_coupon')->get_coupon_info($_GET['sysc_id']);
                }
            }

            //子商户设置不允许使用平台优惠抵扣
            if($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_discount']!=1){
                $system_coupon = array();
            }

            if (!empty($mer_coupon)) {
                $mer_coupon['coupon_url_param'] = array('merc_id' => $mer_coupon['id'], 'order_id' => $order_info['order_id'], 'type' => $_GET['type']);
                if($system_coupon['discount']<$tmp_order['total_money']){
                    $this->assign('card_coupon', $mer_coupon);
                    $_SESSION['merc_id'] = $mer_coupon['id'];
                    $_SESSION['card_discount'] = $mer_coupon['discount'];
                }
            } else {
                $mer_coupon['coupon_url_param'] = array();
            }


            if (!empty($system_coupon)) {
                $system_coupon['coupon_url_param'] = array('sysc_id' => $system_coupon['id'], 'order_id' => $order_info['order_id'], 'type' => $_GET['type']);
                if($system_coupon['discount']>=$tmp_order['total_money']){
                    $ban_mer_coupon=1;
                }else{
                    $ban_mer_coupon=0;
                }
                $this->assign('ban_mer_coupon',$ban_mer_coupon);
                //   if($system_coupon['discount']<$tmp_order['total_money']) {
                $_SESSION['sysc_id'] = $system_coupon['id'];
                $this->assign('system_coupon', $system_coupon);
                // }
            } else {
                $system_coupon['coupon_url_param'] = array();
            }



            $coupon_url = array_merge($mer_coupon['coupon_url_param'], $system_coupon['coupon_url_param']);
            if($_GET['unsys_coupon']&&!empty($coupon_url)){
                unset($_SESSION['sysc_id']);
                $coupon_url['unsys_coupon']=1;
            }
            if($_GET['unmer_coupon']&&!empty($coupon_url)){
                unset($_SESSION['merc_id']);
                $coupon_url['unmer_coupon']=1;
            }
            $this->assign('coupon_url', $coupon_url);

            // $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'],$order_info['mer_id']);


            if (isset($order_info['discount_status']) && !$order_info['discount_status']) {
                $card_info['discount'] = 10;
            }
            $this->assign('card_info', $card_info);

            $this->assign('merchant_balance', $merchant_balance);

        }

        //使用积分
        $score_can_use_count=0;
        $score_deducte=0;
        $user_score_use_percent = (float)$this->config['user_score_use_percent'];
        //定制元宝判断条件
		 if($this->config['open_extra_price']==1&&$order_info['extra_price']>0) {
            if ($_GET['type'] == 'group' || $_GET['type'] == 'store' || $_GET['type'] == 'meal' ||$_GET['type'] == 'mall' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'appoint' || $_GET['type'] == 'mall' || $_GET['type'] == 'shop' || $_GET['type'] == 'balance-appoint' || ($order_info['order_type'] == 'plat' && $order_info['pay_system_score'])) {           //business_type
                $type_ = $_GET['type'];
                if ($order_info['business_type'] == 'foodshop') {
                    $type_ = 'meal';
                }

                if ($_GET['type'] == 'balance-appoint') {
                    $type_ = 'appoint';
                }
                 if ($_GET['type'] == 'mall') {
                    $type_ = 'shop';
                }



                $user_score_use_condition = $this->config['user_score_use_condition'];
                $user_score_max_use = D('Percent_rate')->get_max_core_use($order_info['mer_id'], $type_);//不同业务不同积分
                // $user_score_max_use=$score_config['user_score_max_use'];
              
                if ($_GET['type'] == 'group') {
                    $group_info = D('Group')->where(array('group_id' => $order_info['group_id']))->find();
                    if ($group_info['score_use']) {
                        if ($group_info['group_max_score_use'] != 0) {
                            $user_score_max_use = $group_info['group_max_score_use'];
                        }
                    } else {
                        $user_score_max_use = 0;
                    }
                }
                $user_score_use_percent = (float)$this->config['user_score_use_percent'];
                $score_max_deducte = bcdiv($user_score_max_use, $user_score_use_percent, 2);
                if ($user_score_use_percent > 0 && $score_max_deducte > 0 && $user_score_use_condition > 0 && $now_user['score_count'] > 0) {   //如果设置没有错误
                    $total_ = $order_info['extra_price'];
                    if ($total_ >= $user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
                        if ($total_ > $score_max_deducte) {                    //判断积分最大抵扣金额是否比这个订单的总额大
                            $score_can_use_count = (int)($now_user['score_count'] > $user_score_max_use ? $user_score_max_use : $now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        } else {
                            $score_can_use_count = $total_ * $user_score_use_percent > (int)$now_user['score_count'] ? (int)$now_user['score_count'] : $total_ * $user_score_use_percent;
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        }
                    }
                }

            }
        }else{
            if ($_GET['type'] == 'group' || $_GET['type'] == 'store' || $_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad' || $_GET['type'] == 'appoint' || $_GET['type'] == 'mall' || $_GET['type'] == 'shop' || $_GET['type'] == 'balance-appoint' || ($order_info['order_type'] == 'plat' && $order_info['pay_system_score'])) {           //business_type
                $type_ = $_GET['type'];
                if ($order_info['business_type'] == 'foodshop') {
                    $type_ = 'meal';
                }

                if ($_GET['type'] == 'balance-appoint') {
                    $type_ = 'appoint';
                }


                $user_score_use_condition = $this->config['user_score_use_condition'];
                $user_score_max_use = D('Percent_rate')->get_max_core_use($order_info['mer_id'], $type_);//不同业务不同积分
                // $user_score_max_use=$score_config['user_score_max_use'];

                if ($_GET['type'] == 'group') {
                    $group_info = D('Group')->where(array('group_id' => $order_info['group_id']))->find();
                    if ($group_info['score_use']) {
                        if ($group_info['group_max_score_use'] != 0) {
                            $user_score_max_use = $group_info['group_max_score_use'];
                        }
                    } else {
                        $user_score_max_use = 0;
                    }
                }
                $user_score_use_percent = (float)$this->config['user_score_use_percent'];
                $score_max_deducte = bcdiv($user_score_max_use, $user_score_use_percent, 2);
                if ($user_score_use_percent > 0 && $score_max_deducte > 0 && $user_score_use_condition > 0 && $now_user['score_count'] > 0) {   //如果设置没有错误
                    $total_ = $tmp_order['total_money'];
                    if ($total_ >= $user_score_use_condition) {             //判断是否够使用积分的条件，即这个订单的总额是否大于规定的金额
                        if ($total_ > $score_max_deducte) {                    //判断积分最大抵扣金额是否比这个订单的总额大
                            $score_can_use_count = (int)($now_user['score_count'] > $user_score_max_use ? $user_score_max_use : $now_user['score_count']);//如果用户积分大于每个订单的积分可使用数 则按可使用数算
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        } else {
                            $score_can_use_count = ceil($total_ * $user_score_use_percent) > (int)$now_user['score_count'] ? (int)$now_user['score_count'] : ceil($total_ * $user_score_use_percent);
                            $score_deducte = bcdiv($score_can_use_count, $user_score_use_percent, 2);
                            $score_deducte = $score_deducte > $total_ ? $total_ : $score_deducte;
                        }
                    }
                }

            }
        }
        //子商户设置不允许使用平台优惠抵扣
        if($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_discount']!=1){
            $score_can_use_count = 0;
            $score_deducte = 0;
        }

        $this->assign('score_can_use_count', $score_can_use_count);
        $this->assign('score_deducte', $score_deducte);
        $this->assign('score_count', $now_user['score_count']);


        //需要支付的钱
        // $this->assign('pay_money',number_format($pay_money,2));

        //调出支付方式
        $notOnline = intval($_GET['notOnline']);
        if($_GET['type'] != 'recharge' && $_GET['type'] != 'appoint'){
            $notOffline = intval($_GET['notOffline']);
        }else{
            $notOffline = 1;
        }

        //********************预定金不允许线下支付*************************//
        if (intval($_GET['isdeposit'])) $notOffline = 1;
        if($_GET['type'] == 'meal' || $_GET['type'] == 'takeout' || $_GET['type'] == 'food' || $_GET['type'] == 'foodPad'){
            $t_order = D('Meal_order')->get_order_by_id($this->user_session['uid'], intval($_GET['order_id']));
            $true_price = $t_order['total_price'] - $t_order['minus_price'];
            if ($t_order['price'] < $true_price) $notOffline = 1;
        }
        //********************预定金不允许线下支付*************************//


		if($this->config['open_extra_price']==1){
			$now_mer = M('Merchant')->where(array('mer_id'=>$order_info['mer_id']))->find();
       
        	$score_percent = $now_mer['score_get']>=0?$now_mer['score_get']:$this->config['user_score_get'];
        
        	$this->assign('score_get',$score_percent);
		}
		
        if (isset($_GET['online']) && $_GET['type'] == 'foodPad') {
            $online = isset($_GET['online']) ? intval($_GET['online']) : 1;
            $notOnline = $online ? 0 : 1;
            $notOffline = $online ? 1 : $notOffline;
        }

        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        if(in_array($_GET['type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $order_info['mer_id']){

        }else if($_GET['type'] == 'plat' && $order_info['pay_merchant_ownpay'] && $order_info['mer_id']){
            $this->config['merchant_ownpay'] = 1;
        }else{
            $this->config['merchant_ownpay'] = 0;
        }

        switch($this->config['merchant_ownpay']){
            case 0:
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                break;
            case 1:
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_merchant['mer_id']))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }

                break;
            case 2:
                $pay_method = array();
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$now_merchant['mer_id']))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                }

                break;
        }

		
        if ($this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
            $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
            $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
            $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
            $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
            $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
			//$pay_method['weixin']['config']['is_own'] = 1 ;
        }

        if(empty($pay_method) && $this->is_app_browser==false){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'));
        }

        if(empty($_SESSION['openid']) || $_GET['type'] == 'foodPad'){
            unset($pay_method['weixin'],$pay_method['weifutong']);
        }
        //dump($_SESSION);die;
        if($pay_method['weixin']['config']['is_own']){
            $merchant_bind = D('Weixin_bind')->field('authorizer_appid')->where(array('mer_id' => $now_merchant['mer_id']))->find();
            if(empty($merchant_bind)){
                unset($pay_method['weixin']);
            }else{
                if(empty($_SESSION['open_authorize_openid']) || empty($_SESSION['open_authorize_mer_id']) || ($_SESSION['open_authorize_mer_id'] && $_SESSION['open_authorize_mer_id'] != $order_info['mer_id'])){
                    $this->open_authorize_openid(array('appid'=>$merchant_bind['authorizer_appid']));
                }else{
					$_SESSION['open_authorize_mer_id'] = $order_info['mer_id'];
				}
                if($_SESSION['open_authorize_openid'] == 'error'){
                    unset($pay_method['weixin']);
                }
            }
        }
        //add garfunkel
        foreach ($pay_method as $k=>$v){
            $pay_method[$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
        }
        $this->assign('pay_method',$pay_method);

        if($_GET['type'] == 'group'){
            $this->behavior(array('model'=>'Pay_group','mer_id'=>$order_info['mer_id'],'biz_id'=>$order_info['order_id']),true);
        }else if($_GET['type'] == 'meal'){
            $this->behavior(array('model'=>'Play_meal','mer_id'=>$order_info['mer_id'],'biz_id'=>$order_info['order_id']),true);
        }



        $this->assign('type',$_GET['type']);
        $this->assign('order_id',$_GET['order_id']);
        $this->display();
    }
    protected function getPayName($label){
        $payName = array(
            'weixin' => '微信支付',
            'tenpay' => '财付通支付',
            'yeepay' => '银行卡支付(易宝支付)',
            'allinpay' => '银行卡支付(通联支付)',
            'chinabank' => '银行卡支付(网银在线)',
			'weifutong' => $this->config['pay_weifutong_alias_name'],
        );
        return $payName[$label];
    }
    public function go_pay(){
        //换参数
        if($_POST['ticket']!=''){
            $_POST['use_merchant_balance'] = $_POST['use_merchant_money'];
            $_POST['use_balance'] = $_POST['use_balance_money'];
        }
        if(empty($this->user_session)){
            $this->error_tips(L('_B_MY_LOGINFIRST_'),U('Login/index'));
        }
        if(!in_array($_POST['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','mall','plat','balance-appoint','plat'))){
            $this->error_tips(L('_ORDER_CANNT_IDEN_'));
        }
        if (strtolower($_POST['pay_type']) == 'alipay') {
            $url = U('Pay/alipay', $_POST);
            $this->assign('url', $url);
            $this->display('alipay_pay');
            die;
        }

        switch($_POST['order_type']){
            case 'group':
                $now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'meal':
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']), false,  $_POST['order_type']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->get_pay_order($this->user_session['uid'],intval($_POST['order_id']),false,$_POST['order_type']);
                if ($now_order['order_info']['pay_type'] !== $_POST['pay_type']) {
                    $this->error_tips('非法的订单');
                }
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->get_pay_order(0,intval($_POST['order_id']));
                break;
            case 'store':
                $now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'shop':
            case 'mall':
                $now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                if($_POST['pay_type'] == 'offline' && $now_order['order_info']['tip_charge'] != 0)
                    D('Shop_order')->where(array('order_id'=>intval($_POST['order_id'])))->save(array('tip_charge'=>0));
                break;
            case 'plat':
                $now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_POST['order_id']));
                break;
            case 'balance-appoint':
                $now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_POST['order_id']));
                break;
            default:
                $this->error_tips(L('_ILLEGAL_ORDER_'));
        }

        if($now_order['error'] == 1){
            $this->error_tips($now_order['msg'],$now_order['url']);
        }

        $order_info = $now_order['order_info'];
        $now_merchant = D('Merchant')->get_info($order_info['mer_id']);
        if($now_merchant['status']==3){
            $this->error_tips('该商家状态异常，无法支付');
        }
        //商家会员卡余额
        if($_POST['use_merchant_balance']){
            $order_info['use_merchant_balance'] = 1;
        }else{
            $order_info['use_merchant_blance'] = 0;
        }
        if($_POST['order_type'] != 'recharge'  && $_POST['order_type'] != 'weidian'  && (empty($order_info['business_type'])||$order_info['business_type']!='card_new_recharge')) {
            //优惠券
            if (!isset($order_info['discount_status']) || $order_info['discount_status']) {
                if ((!empty($_POST['card_id'])&&$_POST['card_id']==$_SESSION['merc_id'])||($_POST['ticket']&&$_POST['card_id']&&$_POST['use_mer_coupon'])) {
                    $card_coupon = D('Card_new_coupon')->get_coupon_by_id($_POST['card_id']);
                    $now_coupon['card_price'] = $card_coupon['price'];
                    $now_coupon['merc_id'] = $card_coupon['id'];
                    unset($_SESSION['merc_id']);
                }
                if ((!empty($_POST['coupon_id'])&&$_POST['coupon_id']==$_SESSION['sysc_id'])||($_POST['ticket']&&$_POST['coupon_id']&&$_POST['use_sys_coupon'])) {
                    $system_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                    $now_coupon['coupon_price'] = $system_coupon['price'];
                    $now_coupon['sysc_id'] = $system_coupon['id'];
                    unset($_SESSION['sysc_id']);
                }
            }
            //子商户设置不允许使用平台优惠抵扣
            if($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_discount']!=1){
                $now_coupon['coupon_price'] = 0;
                $now_coupon['sysc_id'] = 0;
            }

            if($order_info['mer_id'] ){
                $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'],$order_info['mer_id']);
                if(empty($card_info)){
                    $merchant_balance['card_money'] = 0;
                    $merchant_balance['card_give_money'] = 0;
                    $merchant_balance['card_discount'] = 10;
                }else{
                    $merchant_balance['card_money'] = $card_info['card_money'];
                    $merchant_balance['card_give_money'] = $card_info['card_money_give'];
                    $merchant_balance['card_discount'] = empty($card_info['discount'])||(isset($order_info['discount_status'])&&!$order_info['discount_status'])?10:$card_info['discount'];
                }
            }
        }

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);


        if(empty($now_user)){
            $this->error_tips(L('_NO_GET_INFO_'));
        }
        //判断积分是否够用 防止支付同时积分被改动

        if ($_POST['use_score']) {
            if($now_user['score_count']<$_POST['score_used_count']){
                $this->error_tips('账户'.$this->config['score_name'].'不够，请重试！');
            }
            if($this->config['open_extra_price']){

                if(isset($order_info['extra_price']) && $order_info['extra_price']>0 &&$order_info['extra_price']<$_POST['score_deducte']){
                    $this->error_tips($this->config['score_name'].'抵扣金额错误！');
                }
            }else{
                if($order_info['order_total_money']<$_POST['score_deducte']){
                    $this->error_tips($this->config['score_name'].'抵扣金额错误！');
                }
            }

            $order_info['score_used_count']=$_POST['score_used_count'];
            $order_info['score_deducte']=$_POST['score_deducte'];
        }else{
            $order_info['score_used_count']=0;
            $order_info['score_deducte']=0;
        }
        $order_info['use_score'] = $_POST['use_score'];
        //这里为了解决app支付
        if($_POST['use_balance']==1){
            $order_info['use_balance'] = 1;
        }else{
            $order_info['use_balance'] = 0;
        }

        //子商户设置不允许使用平台余额支付
        if($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_sys_pay']!=1){
            $order_info['use_balance'] = 0;
        }
        //子商户设置不允许使用平台优惠抵扣
        if($now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0 && $now_merchant['sub_mch_discount']!=1){
            $order_info['score_used_count']=0;
            $order_info['score_deducte']=0;
             $order_info['use_score'] = $_POST['use_score'];
        }

        if($_POST['order_type']=='plat'&&$this->config['open_extra_price']==1&&$order_info['business_type']=='foodshop'){
            $now_business_order = M('Foodshop_order')->where(array('order_id'=>$order_info['business_id']))->find();
            $order_info['order_total_money']+=$now_business_order['extra_price'];
        }else if($_POST['order_type']=='balance-appoint'&&$this->config['open_extra_price']==1){
            $order_info['order_total_money']+=$order_info['extra_price'];
        }

        //如果用户存在余额或使用了优惠券，则保存至订单信息。如果金额满足订单总价，则实时扣除并返回支付成功！若不够则不实时扣除，防止用户在付款过程中取消支付
        $wx_cheap = 0;

        //garfunkel add 添加小费记录
        $order_info['tip'] = $_POST['tip'];
        if($_POST['pay_type'] == 'Cash' || $_POST['pay_type'] == 'offline')
            $order_info['tip'] = 0;
        if($order_info['tip'] > 0){
            $order_info['order_total_money'] = $order_info['order_total_money'] + $order_info['tip'];
            $data['tip_charge'] = $order_info['tip'];
            if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
                D('Shop_order')->field(true)->where(array('order_id'=>$order_info['order_id']))->save($data);
            }
        }
        //
        if($order_info['order_type'] == 'group'){
            //判断有没有使用微信，如果是微信，则检测此团购有没有微信优惠！
            if($this->is_app_browser){
                $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id'=>$order_info['group_id']))->find();
                $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
            }elseif($this->config['weixin_buy_follow_wechat'] == 2 && empty($now_user['is_follow'])){
                $this->error_tips('您未关注公众号，不能购买！请先关注公众号。');
            }elseif($this->config['weixin_buy_follow_wechat'] == 1 && empty($now_user['is_follow'])){
                $wx_cheap = 0;
            }elseif($_SESSION['openid']){
                $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id'=>$order_info['group_id']))->find();
                $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
            }
            $save_result = D('Group_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user,$wx_cheap);
        }else if($order_info['order_type'] == 'meal' || $order_info['order_type'] == 'takeout' || $order_info['order_type'] == 'food' || $order_info['order_type'] == 'foodPad'){
            $save_result = D('Meal_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user, $order_info['order_type']);
        }else if($order_info['order_type'] == 'weidian'){
            $save_result = D('Weidian_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'recharge'){
            $save_result = D('User_recharge_order')->web_befor_pay($order_info,$now_user);
        }else if($order_info['order_type'] == 'appoint'){
            $save_result = D('Appoint_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'waimai'){
            $save_result = D('Waimai_order')->wap_befor_pay($order_info,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'wxapp'){
            $save_result = D('Wxapp_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'store'){
            $save_result = D('Store_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
            $save_result = D('Shop_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'plat'){
            $save_result = D('Plat_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'balance-appoint'){
            $save_result = D('Appoint_order')->wap_balace_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }

        if(cookie('is_house')){
            $save_result['url'] = str_replace('wap.php', C('INDEP_HOUSE_URL'), $save_result['url']);
        }
        if($save_result['error_code']){
            $this->error_tips($save_result['msg']);
        }else if($save_result['url']){
            $this->success_tips($save_result['msg'],$save_result['url']);
        }

        //需要支付的钱
        $pay_money = round($save_result['pay_money']*100)/100;
        if(in_array($order_info['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $order_info['mer_id']){
            $mer_id = $order_info['mer_id'];
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }

                break;
            case '2':
                $pay_method = array();
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }

                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                }
                break;
        }

        //配置服务商子商户支付
        if($this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id']>0){
            $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
            $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
            $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
            $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
            $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
            $pay_method['weixin']['config']['is_own'] = 1 ;
        }

        if(empty($pay_method)){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'));
        }
        if(empty($pay_method[$_POST['pay_type']])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($_POST['pay_type']);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $order_id = $order_info['order_id'];
        if ($_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food' || $_POST['order_type'] == 'foodPad') {
            $order_table = 'Meal_order';
        }else if($_POST['order_type']=='recharge'){
            $order_table = 'User_recharge_order';
        }else if($_POST['order_type']=='balance-appoint'){
            $order_table = 'Appoint_order';
            //$order_info['order_type']='appoint';
        }else{
            $order_table = ucfirst($_POST['order_type']).'_order';
        }
        //更新长id
        $nowtime = date("ymdHis");
        if($_POST['order_type']=='balance-appoint'){
            $nowtime = date("mdHis");
            $orderid = $nowtime.sprintf("%06d",$this->user_session['uid']);;
        }else{
            $orderid = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
        }
        $data_tmp['pay_type'] = $_POST['pay_type'];
        $data_tmp['order_type'] = $_POST['order_type'];
        $data_tmp['order_id'] = $order_id;
        $data_tmp['orderid'] = $orderid;
        $data_tmp['addtime'] = $nowtime;
        if(!D('Tmp_orderid')->add($data_tmp)){
            $this->error_tips(L('_UPDATE_FAIL_ADMIN_'));
        }
        $save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
        if(!$save_pay_id){
            $this->error_tips(L('_UPDATE_FAIL_ADMIN_'));
        }else{
            $order_info['order_id']=$orderid;
        }

        $pay_class = new $pay_class_name($order_info,$pay_money,$_POST['pay_type'],$pay_method[$_POST['pay_type']]['config'],$this->user_session,1);
        $go_pay_param = $pay_class->pay();

        if(empty($go_pay_param['error'])){
            if(!empty($go_pay_param['url'])){
                $this->assign('url',$go_pay_param['url']);
                $this->display();
            }else if(!empty($go_pay_param['form'])){
                $this->assign('form',$go_pay_param['form']);
                $this->display();
            }else if(!empty($go_pay_param['weixin_param'])){
                if ($pay_method['weixin']['config']['is_own']) {
                    C('open_authorize_wxpay', true);
                    $share = new WechatShare($this->config, $_SESSION['openid']);
                    $this->hideScript = $share->gethideOptionMenu($mer_id);
                    $arr['hidScript'] = $this->hideScript;
                    $redirctUrl = C('config.site_url') . '/'.$this->indep_house.'?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'] . '&own_mer_id=' . $order_info['mer_id'];
                } else {
                    $redirctUrl = C('config.site_url') . '/'.$this->indep_house.'?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'];
                }
                $arr['redirctUrl'] = $redirctUrl;
                $arr['pay_money'] = $pay_money;
                $arr['weixin_param'] = json_decode($go_pay_param['weixin_param']);
                $arr['error'] = 0;
                echo json_encode($arr);die;
            }else{
                $this->error_tips(L('_ERROR_PAYMENT_'));
            }
        }else{
            $this->error_tips($go_pay_param['msg']);
        }
    }

    public function alipay()
    {

        if($_GET['ticket']!=''){
            $_GET['use_merchant_balance'] = $_GET['use_merchant_money'];
            $_GET['use_balance'] = $_GET['use_balance_money'];
        }
        if(empty($this->user_session)){
            $this->error_tips('请先进行登录！',U('Login/index'));
        }
        if(!in_array($_GET['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint'))){
            $this->error_tips('订单来源无法识别，请重试。');
        }
        switch($_GET['order_type']){
            case 'group':
                $now_order = D('Group_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'meal':
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']), false,  $_GET['order_type']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),false,$_GET['order_type']);
                if ($now_order['order_info']['pay_type'] !== $_GET['pay_type']) {
                    $this->error_tips('非法的订单');
                }
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->get_pay_order(0,intval($_GET['order_id']));
                break;
            case 'store':
                $now_order = D('Store_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
                break;
            case 'shop':
            case 'mall':
                $now_order = D('Shop_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
                break;
            case 'plat':
                $now_order = D('Plat_order')->get_pay_order($this->user_session['uid'], intval($_GET['order_id']));
                break;
            case 'balance-appoint':
                $now_order = D('Appoint_order')->get_pay_balace_order($this->user_session['uid'],intval($_GET['order_id']));
                break;
            default:
                $this->error_tips('非法的订单');
        }
        if($now_order['error'] == 1){
            $this->error_tips($now_order['msg'],$now_order['url']);
        }
        $order_info = $now_order['order_info'];

        if($_GET['order_type'] != 'recharge'  && $_GET['order_type'] != 'weidian'  && (empty($order_info['business_type'])||$order_info['business_type']!='card_new_recharge')) {
            //优惠券
            if (!isset($order_info['discount_status']) || $order_info['discount_status']) {
                if ((!empty($_GET['card_id'])&&$_GET['card_id']==$_SESSION['merc_id'])||($_GET['ticket']&&$_GET['card_id']&&$_GET['use_mer_coupon'])) {
                    $card_coupon = D('Card_new_coupon')->get_coupon_by_id($_GET['card_id']);
                    $now_coupon['card_price'] = $card_coupon['price'];
                    $now_coupon['merc_id'] = $card_coupon['id'];
                    unset($_SESSION['merc_id']);
                }
                if ((!empty($_GET['coupon_id'])&&$_GET['coupon_id']==$_SESSION['sysc_id'])||($_GET['ticket']&&$_GET['coupon_id']&&$_GET['use_sys_coupon'])) {
                    $system_coupon = D('System_coupon')->get_coupon_by_id($_GET['coupon_id']);
                    $now_coupon['coupon_price'] = $system_coupon['price'];
                    $now_coupon['sysc_id'] = $system_coupon['id'];
                    unset($_SESSION['sysc_id']);
                }
            }
            if($order_info['mer_id'] ){
                $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->user_session['uid'],$order_info['mer_id']);
                if(empty($card_info)){
                    $merchant_balance['card_money'] = 0;
                    $merchant_balance['card_give_money'] = 0;
                    $merchant_balance['card_discount'] = 10;
                }else{
                    $merchant_balance['card_money'] = $card_info['card_money'];
                    $merchant_balance['card_give_money'] = $card_info['card_money_give'];
                    $merchant_balance['card_discount'] = empty($card_info['discount'])||(isset($order_info['discount_status'])&&!$order_info['discount_status'])?10:$card_info['discount'];
                }
            }
        }

//        if(!empty($_GET['ticket'])){
//            $merchant_balance = array();
//            $merchant_balance['card_give_money'] = 0;
//            $merchant_balance['card_discount'] = 10;
//        }

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);


        if ($_GET['use_score']) {
            if($now_user['score_count']<$_GET['score_used_count']){
                $this->error_tips('账户'.$this->config['score_name'].'不够，请重试！');
            }
            $order_info['score_used_count']=$_GET['score_used_count'];
            $order_info['score_deducte']=$_GET['score_deducte'];
        }else{
            $order_info['score_used_count']=0;
            $order_info['score_deducte']=0;
        }
        $order_info['use_score'] = $_GET['use_score'];
        if($_GET['use_balance']==0){
            $order_info['use_balance'] = 0;
        }else{
            $order_info['use_balance'] = 1;
        }
        if(empty($now_user)){
            $this->error_tips('未获取到您的帐号信息，请重试！');
        }

        //如果用户存在余额或使用了优惠券，则保存至订单信息。如果金额满足订单总价，则实时扣除并返回支付成功！若不够则不实时扣除，防止用户在付款过程中取消支付
        $wx_cheap = 0;
        if($order_info['order_type'] == 'group'){
            //判断有没有使用微信，如果是微信，则检测此团购有没有微信优惠！
            if($this->config['weixin_buy_follow_wechat'] == 2 && empty($now_user['is_follow'])){
                $this->error_tips('您未关注公众号，不能购买！请先关注公众号。');
            }elseif($this->config['weixin_buy_follow_wechat'] == 1 && empty($now_user['is_follow'])){
                $wx_cheap = 0;
            }elseif($_SESSION['openid']){
                $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id'=>$order_info['group_id']))->find();
                $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
            }elseif($this->is_app_browser){
                $now_group = M('Group')->field('`group_id`,`wx_cheap`')->where(array('group_id'=>$order_info['group_id']))->find();
                $wx_cheap = $order_info['order_num'] * $now_group['wx_cheap'];
            }
            $save_result = D('Group_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user,$wx_cheap);
        }else if($order_info['order_type'] == 'meal' || $order_info['order_type'] == 'takeout' || $order_info['order_type'] == 'food' || $order_info['order_type'] == 'foodPad'){
            $save_result = D('Meal_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user, $order_info['order_type']);
        }else if($order_info['order_type'] == 'weidian'){
            $save_result = D('Weidian_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'recharge'){
            $save_result = D('User_recharge_order')->web_befor_pay($order_info,$now_user);
        }else if($order_info['order_type'] == 'appoint'){
            $save_result = D('Appoint_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'waimai'){
            $save_result = D('Waimai_order')->wap_befor_pay($order_info,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'wxapp'){
            $save_result = D('Wxapp_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'store'){
            $save_result = D('Store_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
            $save_result = D('Shop_order')->wap_befor_pay($order_info, $now_coupon, $merchant_balance, $now_user);
        }else if($order_info['order_type'] == 'plat'){
            $save_result = D('Plat_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'balance-appoint'){
            $save_result = D('Appoint_order')->wap_balace_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }

        if($save_result['error_code']){
            $this->error_tips($save_result['msg']);
        }else if($save_result['url']){
            $this->success_tips($save_result['msg'],$save_result['url']);
        }

        //需要支付的钱
        $pay_money = round($save_result['pay_money']*100)/100;

        if(in_array($order_info['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $order_info['mer_id']){
            $mer_id = $order_info['mer_id'];
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }
                break;
            case '2':
                $pay_method = array();
                $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                foreach($merchant_ownpay as $ownKey=>$ownValue){
                    $ownValueArr = unserialize($ownValue);
                    if($ownValueArr['open']){
                        $ownValueArr['is_own'] = true;
                        $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method($notOnline,$notOffline,true);
                }
                break;
        }

        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$_GET['pay_type']])){
            //$this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($_GET['pay_type']);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $order_id = $order_info['order_id'];
        if ($_GET['order_type'] == 'takeout' || $_GET['order_type'] == 'food' || $_GET['order_type'] == 'foodPad') {
            $order_table = 'Meal_order';
        }else if($_GET['order_type']=='recharge'){
            $order_table = 'User_recharge_order';
        }else if($_GET['order_type']=='balance-appoint'){
            $order_table = 'Appoint_order';
        }else{
            $order_table = ucfirst($_GET['order_type']).'_order';
        }

        $nowtime = date("ymdHis");
        $orderid = $nowtime.rand(10,99).sprintf("%08d",$this->user_session['uid']);
        $data_tmp['pay_type'] = 'alipay';
        $data_tmp['order_type'] = $_GET['order_type'];
        $data_tmp['order_id'] = $order_id;
        $data_tmp['orderid'] = $orderid;
        $data_tmp['addtime'] = $nowtime;
        if(!D('Tmp_orderid')->add($data_tmp)){
            $this->error_tips('更新失败，请联系管理员');
        }
        $save_pay_id = D($order_table)->where(array("order_id"=>$order_id))->setField('orderid',$orderid);
        if(!$save_pay_id){
            $this->error_tips('更新失败，请联系管理员');
        }else{
            $order_info['order_id']=$orderid;
        }

        $pay_class = new $pay_class_name($order_info,$pay_money,$_GET['pay_type'],$pay_method[$_GET['pay_type']]['config'],$this->user_session,1);
        $go_pay_param = $pay_class->pay();
        if(empty($go_pay_param['error'])){
            header("Content-type: text/html; charset=utf-8");
            echo $go_pay_param['form'];
            die;
            if(!empty($go_pay_param['url'])){
                $this->assign('url',$go_pay_param['url']);
                $this->display();
            }else if(!empty($go_pay_param['form'])){
                $this->assign('form',$go_pay_param['form']);
                $this->display();
            }else if(!empty($go_pay_param['weixin_param'])){
                if($pay_method['weixin']['config']['is_own']){
                    C('open_authorize_wxpay',true);
                    $share = new WechatShare($this->config,$_SESSION['openid']);
                    $this->hideScript = $share->gethideOptionMenu($mer_id);
                    $this->assign('hideScript', $this->hideScript);
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'].'&own_mer_id='.$order_info['mer_id'];
                }else{
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Pay&a=weixin_back&order_type='.$order_info['order_type'].'&order_id='.$order_info['order_id'];
                }
                $this->assign('redirctUrl',$redirctUrl);
                $this->assign('pay_money',$pay_money);
                $this->assign('weixin_param',json_decode($go_pay_param['weixin_param']));
                $this->display('weixin_pay');
            }else{
                $this->error_tips('调用支付发生错误，请重试。');
            }
        }else{
            $this->error_tips($go_pay_param['msg']);
        }
    }

    public function get_orderid($table,$orderid,$offline=0){
        $order =  D($table);
        $tmp_orderid = D('Tmp_orderid');
        if($offline){
            $now_order = $order->where(array('orderid'=>$orderid))->find();
        }else{
            $now_order = $order->where(array('orderid'=>$orderid))->find();
            if(empty($now_order)){
                $res = $tmp_orderid->where(array('orderid'=>$orderid))->find();
                $now_order = $order->where(array('order_id'=>$res['order_id']))->find();
                $order->where(array('order_id'=>$res['order_id']))->setField('orderid',$orderid);
                $now_order['orderid']=$orderid;
            }
        }
        if(empty($now_order)){
            $this->error_tips('该订单不存在');
        }else{
//            $tmp_orderid->where(array('order_id'=>$now_order['order_id']))->delete();
        }

        return $now_order;
    }

    //微信同步回调页面
    public function weixin_back(){
        switch($_GET['order_type']){
            case 'group':
                $now_order =$this->get_orderid('Group_order',$_GET['order_id']);
                break;
            case 'meal':
                $now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
                break;
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order =$this->get_orderid('Meal_order',$_GET['order_id']);
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->where(array('orderid'=>$_GET['order_id']))->find();
                $now_order =$this->get_orderid('Weidian_order',$_GET['order_id']);
                break;
            case 'recharge':
                $now_order =$this->get_orderid('User_recharge_order',$_GET['order_id']);
                break;
            case 'appoint':
                $now_order =$this->get_orderid('Appoint_order',$_GET['order_id']);
                break;
            case 'waimai':
                $now_order =$this->get_orderid('Waimai_order',$_GET['order_id']);
                break;
            case 'wxapp':
                $now_order =$this->get_orderid('Wxapp_order',$_GET['order_id']);
                break;
            case 'store':
                $now_order =$this->get_orderid('Store_order',$_GET['order_id']);
                break;
            case 'shop':
            case 'mall':
                $now_order = $this->get_orderid('Shop_order', $_GET['order_id']);
                break;
            case 'plat':
                $now_order = $this->get_orderid('Plat_order', $_GET['order_id']);
                break;
            case 'balance-appoint':
                $now_order = $this->get_orderid('Appoint_order', $_GET['order_id']);
                break;
            default:
                $this->error_tips(L('_ILLEGAL_ORDER_'));
        }


        if(empty($now_order)){
            $this->error_tips(L('_B_MY_NOORDER_'));
        }
        $now_order['order_type'] = $_GET['order_type'];
        if($now_order['paid']==3&&$now_order['is_initiative']!=2){
            switch($_GET['order_type']){
                case 'group':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=group_order&order_id='.$now_order['order_id'];
                    break;
                case 'meal':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'takeout':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'food':
                case 'foodPad':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'weidian':
                    $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
                    $this->NoticeWDAsyn($now_order['orderid']);
                    break;
                case 'appoint':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
                case 'waimai':
                    $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                    break;
                case 'recharge':
                    $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                    break;
                case 'wxapp':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                    break;
                case 'store':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_list';
                    break;
                case 'shop':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'mall':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'plat':
                    $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                    break;
                case 'balance-appoint':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
            }
            redirect($redirctUrl);exit;
        }
		

        if(in_array($_GET['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $now_order['mer_id']){
            $mer_id = $now_order['mer_id'];
        }else if($_GET['order_type'] == 'plat'){
            $get_order_info = D('Plat_order')->get_pay_order(0,$now_order['order_id']);
            if($get_order_info['order_info']['pay_merchant_ownpay'] && $get_order_info['order_info']['mer_id']){
                $mer_id = $get_order_info['order_info']['mer_id'];
            }else{
                $this->config['merchant_ownpay'] = 0;
            }
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method();
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){

                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }


                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){

                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }

                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method();
                }

                break;
        }
        if($mer_id) {
            $now_merchant = D('Merchant')->get_info($mer_id);
            if ($this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
                $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
				$pay_method['weixin']['config']['is_own'] = 1 ;
            }
        }
        $_GET['order_id'] = $now_order['order_id'];
        $now_order['order_id']  =   $now_order['orderid'];
        $import_result = import('@.ORG.pay.Weixin');
        if(empty($pay_method)){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'));
        }
        $pay_class = new Weixin($now_order,0,'weixin',$pay_method['weixin']['config'],$this->user_session,1);
        $go_query_param = $pay_class->query_order();
		
		
        if($go_query_param['error'] === 0){
            $go_query_param['order_param']['return']=1;
            switch($_GET['order_type']){
                case 'group':
                    D('Group_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'meal':
                case 'takeout':
                case 'food':
                case 'foodPad':
                    D('Meal_order')->after_pay($go_query_param['order_param'], $_GET['order_type']);
                    break;
                case 'weidian':
                    $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                    if(($pay_info['error']==0) && isset($pay_info['url'])){  /***异步通知***/
                        $this->NoticeWDAsyn($now_order['orderid']);
                    }
                    break;
                case 'recharge':
                    D('User_recharge_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'waimai':
                    D('Waimai_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'appoint':

                        D('Appoint_order')->after_pay($go_query_param['order_param']);

                    break;
                case 'balance-appoint':

                        D('Appoint_order')->balance_after_pay($go_query_param['order_param']);

                    break;
                case 'wxapp':
                    D('Wxapp_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'store':
                    D('Store_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'shop':
                case 'mall':
                    D('Shop_order')->after_pay($go_query_param['order_param']);
                    break;
                case 'plat':
                    D('Plat_order')->after_pay($go_query_param['order_param']);
                    break;
            }
        }
        switch($_GET['order_type']){
            case 'group':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$_GET['order_id'];
                break;
            case 'meal':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'takeout':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'food':
            case 'foodPad':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$_GET['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'weidian':
                $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                $this->NoticeWDAsyn($now_order['orderid']);
                break;
            case 'appoint':
            case 'balance-appoint':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$_GET['order_id'];
                break;
            case 'waimai':
                $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$_GET['order_id'];
                break;
            case 'recharge':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=index';
                break;
            case 'wxapp':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$_GET['order_id'];
                break;
            case 'store':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_list';
                break;
            case 'shop':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $_GET['order_id'];
                break;
            case 'mall':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $_GET['order_id'];
                break;
            case 'plat':
                $redirctUrl = D('Plat_order')->get_order_url($_GET['order_id'],true);
                break;
        }
        if($go_query_param['error'] == 1){
            $this->error_tips('校验时发生错误！'.$go_query_param['msg'],$redirctUrl);
        }else{
            redirect($redirctUrl);
        }
    }

    /***异步通知*微店**/
    public function NoticeWDAsyn($order_id){
        $now_order = M('Weidian_order')->field(true)->where(array('orderid'=>trim($order_id)))->find();
        if(!empty($now_order) && ($now_order['pay_type']=='weixin' || $now_order['pay_type']=='alipay')){
            $wdAsynarr=array('order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']);
            $wdAsynarr['salt'] = 'pigcms';
            ksort($wdAsynarr);
            $wdAsynarr['sign_key'] = sha1(http_build_query($wdAsynarr));
            $wdAsynarr['request_time'] = time();
            $returnarr=httpRequest($this->config['weidian_url'].'/api/pay_notify.php','POST',$wdAsynarr);
            if(empty($returnarr['1'])){
                $returnarr=httpRequest($this->config['weidian_url'].'/api/pay_notify.php','POST',$wdAsynarr);
            }
        }
    }

    //异步通知
    public function notify_url(){
        $pay_method = D('Config')->get_pay_method();
        if(empty($pay_method)){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'));
        }
        if(empty($pay_method[$_GET['pay_type']])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];
        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }

        $pay_class = new $pay_class_name('', '', $pay_type, $pay_method[$pay_type]['config'], $this->user_session, 1);
        $notify_return = $pay_class->notice_url();

        if(empty($notify_return['error'])){

        }else{
            $this->error_tips($notify_return['msg']);
        }
    }

    //跳转通知
    public function return_url(){
        $pay_type = isset($_GET['paytype']) ? $_GET['paytype'] : $_GET['pay_type'];
        if($pay_type == 'weixin'){
            $array_data = json_decode(json_encode(simplexml_load_string($GLOBALS['HTTP_RAW_POST_DATA'], 'SimpleXMLElement', LIBXML_NOCDATA)), true);
            if($array_data && $array_data['attach'] != 'weixin'){
                $_GET['own_mer_id'] = $array_data['attach'];
            }
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method();
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }

                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method();
                }

                break;
        }
        if($_GET['own_mer_id']) {
            $now_merchant = D('Merchant')->get_info($_GET['own_mer_id']);
            if ($this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
				$pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
				$pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
                $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
                $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
				$pay_method['weixin']['config']['is_own'] = 1 ;
            }
        }
        if(empty($pay_method)){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'));
        }
        if(empty($pay_method[$pay_type])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }
		$fileContent = file_get_contents("php://input");
		
        $pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,1);
        $get_pay_param = $pay_class->return_url();

		
		
        $get_pay_param['order_param']['return']=1;
        $offline = $pay_type!='offline'?false:true;

        if(empty($get_pay_param['error'])){
            if($get_pay_param['order_param']['order_type'] == 'group'){
                $now_order = $this->get_orderid('Group_order',$get_pay_param['order_param']['order_id'],$offline);
//                $get_pay_param['order_param']['order_id']=$offline?$get_pay_param['order_param']['order_id']:$now_order['orderid'];
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);

            }else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food' || $get_pay_param['order_param']['order_type'] == 'foodPad'){
                $now_order = $this->get_orderid('Meal_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Meal_order')->after_pay($get_pay_param['order_param'], $get_pay_param['order_param']['order_type']);
            }else if($get_pay_param['order_param']['order_type'] == 'weidian'){
                $now_order = $this->get_orderid('Weidian_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Weidian_order')->after_pay($get_pay_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($get_pay_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($get_pay_param['order_param']['order_id']);
                }
            }else if($get_pay_param['order_param']['order_type'] == 'recharge'){
                $now_order = $this->get_orderid('User_recharge_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'appoint'){
                $now_order = $this->get_orderid('Appoint_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];

                    $pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);

            }else if($get_pay_param['order_param']['order_type'] == 'waimai'){
                $now_order = $this->get_orderid('Waimai_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'wxapp'){
                $now_order = $this->get_orderid('Wxapp_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Wxapp_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'store'){
                $now_order = $this->get_orderid('Store_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Store_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'shop' || $get_pay_param['order_param']['order_type'] == 'mall'){
                $now_order = $this->get_orderid('Shop_order', $get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'plat'){
                $now_order = $this->get_orderid('Plat_order', $get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Plat_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'balance-appoint'){
                $now_order = $this->get_orderid('Appoint_order',$get_pay_param['order_param']['order_id'],$offline);
                $get_pay_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Appoint_order')->balance_after_pay($get_pay_param['order_param']);
            }else{
                $this->error_tips(L('_ILLEGAL_ORDER_'));
            }
	
            $urltype = isset($_GET['urltype']) ? $_GET['urltype'] : '';
            if(empty($pay_info['error'])){
                if($get_pay_param['order_param']['pay_type'] == 'weixin'){
                    exit('<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>');
                } elseif ($get_pay_param['order_param']['pay_type'] == 'baidu') {//百度的异步通知返回
                    exit("<html><head><meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\"></head><body><h1>这是一个return_url页面</h1></body></html>");
                } elseif ('unionpay' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
                    exit("验签成功");
                } elseif ('weifutong' == $get_pay_param['order_param']['pay_type'] && $urltype == 'back') {
                    exit("success");
                }
                $pay_info['msg'] = L('_PAY_SUCCESS_JUMP_');
            }
            if(empty($pay_info['url'])){
                $this->error_tips($pay_info['msg']);
            }else{
                if(cookie('is_house')){
                    $pay_info['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'),$pay_info['url']);
                }else{
                    $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                }
                if ($pay_info['error'] && $urltype == 'front') {
                    $this->redirect($pay_info['url']);
                    exit;
                }
                $this->assign('pay_info', $pay_info);
                $this->display('after_pay');
            }
        }else{
            $this->error_tips($get_pay_param['msg']);
        }
    }

    //支付宝支付同步回调
    public function alipay_return(){
        $order_id_arr = explode('_',$_GET['out_trade_no']);
        $order_type = $order_id_arr[0];
        $order_id = $order_id_arr[1];
        $total_fee = $order_id_arr[2];
        switch($order_type){
            case 'group':
                $now_order = D('Group_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'meal':
                $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'takeout':
            case 'food':
            case 'foodPad':
                $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'weidian':
                $now_order = D('Weidian_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'recharge':
                $now_order = D('User_recharge_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'appoint':
                $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'balance-appoint':
                $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'waimai':
                $now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'wxapp':
                $now_order = D('Wxapp_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'store':
                $now_order = D('Store_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'shop':
            case 'mall':
                $now_order = D('Shop_order')->where(array('orderid'=>$order_id))->find();
                break;
            case 'plat':
                $now_order = D('Plat_order')->where(array('orderid'=>$order_id))->find();
                break;
            default:
                $this->error_tips('非法的订单');
        }
        if($now_order['paid'] && $order_type!='balance-appoint'){
            switch($order_type){
                case 'group':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                    break;
                case 'meal':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'takeout':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'food':
                case 'foodPad':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                    break;
                case 'weidian':
                    $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                    break;
                case 'appoint':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                    break;
                case 'waimai':
                    $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                    break;
                case 'recharge':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=index';
                    break;
                case 'wxapp':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                    break;
                case 'store':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_list';
                    break;
                case 'shop':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'mall':
                    $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                    break;
                case 'plat':
                    $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                    break;
            }
            redirect($redirctUrl);exit;
        }

        if(in_array($_GET['order_type'],array('group','meal','weidian','takeout','food','foodPad','recharge','appoint','waimai','wxapp','store','shop','plat','balance-appoint')) && $now_order['mer_id']){
            $mer_id = $now_order['mer_id'];
        }else if($_GET['order_type'] == 'plat'){
            $get_order_info = D('Plat_order')->get_pay_order(0,$now_order['order_id']);
            if($get_order_info['order_info']['pay_merchant_ownpay'] && $get_order_info['order_info']['mer_id']){
                $mer_id = $get_order_info['order_info']['mer_id'];
            }else{
                $this->config['merchant_ownpay'] = 0;
            }
        }else{
            $this->config['merchant_ownpay'] = 0;
        }
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method(0, 0, true);
                }
                break;
        }
        if(empty($pay_method)){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'));
        }
        $import_result = import('@.ORG.pay.Alipay');
        $pay_class = new Alipay('','','alipay',$pay_method['alipay']['config'],$this->user_session,1);
        $go_query_param = $pay_class->query_order();
        $offline = false;
        if($go_query_param['error'] === 0){
            $go_query_param['order_param']['pay_money'] = $total_fee;
            if($order_type == 'group'){
                $now_order = $this->get_orderid('Group_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Group_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'meal' || $order_type == 'takeout' || $order_type == 'food' || $order_type == 'foodPad'){
                $now_order = $this->get_orderid('Meal_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Meal_order')->after_pay($go_query_param['order_param'], $order_type);
            }else if($order_type == 'weidian'){
                $now_order = $this->get_orderid('Weidian_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($go_query_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($go_query_param['order_param']['order_id']);
                }
            }else if($order_type == 'recharge'){
                $now_order = $this->get_orderid('User_recharge_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('User_recharge_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];


                D('Appoint_order')->after_pay($go_query_param['order_param']);

                // $pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'balance-appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                D('Appoint_order')->balance_after_pay($go_query_param['order_param']);
            }else if($order_type == 'waimai'){
                $now_order = $this->get_orderid('Waimai_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Waimai_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'wxapp'){
                $now_order = $this->get_orderid('Wxapp_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Wxapp_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'store'){
                $now_order = $this->get_orderid('Store_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Store_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'shop' || $order_type == 'mall'){
                $now_order = $this->get_orderid('Shop_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Shop_order')->after_pay($go_query_param['order_param']);
            }else if($order_type == 'plat'){
                $now_order = $this->get_orderid('Plat_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Plat_order')->after_pay($go_query_param['order_param']);
            }else{
                $this->error_tips(L('_ILLEGAL_ORDER_'));
            }
        }
        switch($order_type){
            case 'group':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=group_order&order_id='.$now_order['order_id'];
                break;
            case 'meal':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'takeout':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'food':
            case 'foodPad':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                break;
            case 'weidian':
                $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$go_query_param['order_param']['third_id'],'payment_method'=>$go_query_param['order_param']['pay_type']));
                break;
            case 'appoint':
            case 'balance-appoint':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?g=Wap&c=My&a=appoint_order&order_id='.$now_order['order_id'];
                break;
            case 'waimai':
                $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                break;
            case 'recharge':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=index';
                break;
            case 'wxapp':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                break;
            case 'store':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_list';
                break;
            case 'shop':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                break;
            case 'mall':
                $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                break;
            case 'plat':
                $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                break;
        }
        if ($pay_info['error']==0) echo 'success';
        redirect($redirctUrl);
    }
    //支付宝异步通知
    public function alipay_notice()
    {
        $pay_method = D('Config')->get_pay_method(0, 0, true);
        if(empty($pay_method)){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'));
        }
        $import_result = import('@.ORG.pay.Alipay');
        $pay_class = new Alipay('','','alipay',$pay_method['alipay']['config'],$this->user_session,1);
        $go_query_param = $pay_class->notice_url();
        $offline = false;
        if($go_query_param['error'] == 0){
            if($go_query_param['order_param']['order_type'] == 'group'){
                $now_order = $this->get_orderid('Group_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Group_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'meal' || $go_query_param['order_param']['order_type'] == 'takeout' || $go_query_param['order_param']['order_type'] == 'food' || $go_query_param['order_param']['order_type'] == 'foodPad'){
                $now_order = $this->get_orderid('Meal_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Meal_order')->after_pay($go_query_param['order_param'], $go_query_param['order_param']['order_type']);
            }else if($go_query_param['order_param']['order_type'] == 'weidian'){
                $now_order = $this->get_orderid('Weidian_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Weidian_order')->after_pay($go_query_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($go_query_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($go_query_param['order_param']['order_id']);
                }
            }else if($go_query_param['order_param']['order_type'] == 'recharge'){
                $now_order = $this->get_orderid('User_recharge_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('User_recharge_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);

                //$pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'balance-appoint'){
                $now_order = $this->get_orderid('Appoint_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info =D('Appoint_order')->balance_after_pay($go_query_param['order_param']);
                //$pay_info = D('Appoint_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'waimai'){
                $now_order = $this->get_orderid('Waimai_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Waimai_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'wxapp'){
                $now_order = $this->get_orderid('Wxapp_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Wxapp_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'store'){
                $now_order = $this->get_orderid('Store_order',$go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id']=$now_order['orderid'];
                $pay_info = D('Store_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'shop' || $go_query_param['order_param']['order_type'] == 'mall'){
                $now_order = $this->get_orderid('Shop_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Shop_order')->after_pay($go_query_param['order_param']);
            }else if($go_query_param['order_param']['order_type'] == 'plat'){
                $now_order = $this->get_orderid('Plat_order', $go_query_param['order_param']['order_id'],$offline);
                $go_query_param['order_param']['order_id'] = $now_order['orderid'];
                $pay_info = D('Plat_order')->after_pay($go_query_param['order_param']);
            }else{
                $this->error_tips(L('_ILLEGAL_ORDER_'));
            }


            $order_id = $go_query_param['order_param']['order_id'];
            switch($go_query_param['order_param']['order_type']){
                case 'group':
                    $now_order = D('Group_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'meal':
                case 'takeout':
                case 'food':
                case 'foodPad':
                    $now_order = D('Meal_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'weidian':
                    $now_order = D('Weidian_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'recharge':
                    $now_order = D('User_recharge_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'appoint':
                    $now_order = D('Appoint_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'waimai':
                    $now_order = D('Waimai_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'wxapp':
                    $now_order = D('Wxapp_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'store':
                    $now_order = D('Store_order')->where(array('orderid'=>$order_id))->find();
                    break;
                case 'shop':
                case 'mall':
                    $now_order = D('Shop_order')->where(array('orderid' => $order_id))->find();
                    break;
                case 'plat':
                    $now_order = D('Plat_order')->where(array('orderid' => $order_id))->find();
                    break;
                default:
                    echo "fail";
                    exit;
            }
            if ($now_order['paid']) {
                echo "success";
                exit;
            }
        }
        echo "fail";
        exit;
    }

    //对接支付跳转
    public function butt_pay(){
        if(!empty($_GET['order_id']) && !empty($_GET['order_type'])){
            if(empty($_GET['is_paid'])){
                switch($_GET['order_type']){
                    case 'appoint':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$_GET['order_id'];
                        break;
                }
                if(empty($redirctUrl)){
                    $this->error('该类订单不允许访问');
                }else{
                    redirect($redirctUrl);
                }
            }else{
                $butt_array = array(
                    'order_id' => $_GET['order_id'],
                    'order_type' => $_GET['order_type'],
                    'pay_type' => $_GET['pay_type'],
                    'pay_money' => $_GET['pay_money'],
                    'pay_third_id' => $_GET['pay_third_id'],
                    'encrypt_time' => $_GET['encrypt_time'],
                );
                $key = get_butt_encrypt_key($butt_array,C('butt_key'),true);
                if($key == $_GET['encrypt_key']){
                    $_GET['pay_money'] = $_GET['pay_money']/100;
                    switch($_GET['order_type']){
                        case 'appoint':
                            $order_param = array(
                                'pay_type' => 'weixin',
                                'is_mobile' => '1',
                                'order_type' => $_GET['order_type'],
                                'order_id' => $_GET['order_id'],
                                'is_own' => '0',
                                'third_id' => $_GET['pay_third_id'],
                                'pay_money' => $_GET['pay_money'],
                            );
                            $pay_info = D('Appoint_order')->after_pay($order_param);
                            break;
                    }
                    if(!empty($pay_info)){
                        if(empty($pay_info['url'])){
                            $this->error_tips($pay_info['msg']);
                        }else{
                            if(cookie('is_house')){
                                $pay_info['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'),$pay_info['url']);
                            }else{
                                $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                            }

                            redirect($pay_info['url']);
                        }
                    }else{
                        $this->error('该类订单不允许访问');
                    }
                }else{
                    $this->error('订单校验失败，请重试');
                }
            }
        }else{
            $this->error('访问出错，请重试');
        }
    }

    //百度的同步通知
    public function baidu_back()
    {
        $pay_type = 'baidu';
        $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);
        switch($this->config['merchant_ownpay']){
            case '0':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                break;
            case '1':
                $pay_method = D('Config')->get_pay_method(0, 0, true);
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                break;
            case '2':
                $pay_method = array();
                if($_GET['own_mer_id']){
                    $mer_id = $_GET['own_mer_id'];
                }else if($_SESSION['own_mer_id']){
                    $mer_id = $_SESSION['own_mer_id'];
                    unset($_SESSION['own_mer_id']);
                }
                if($mer_id){
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id'=>$mer_id))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                }
                if(empty($pay_method)){
                    $pay_method = D('Config')->get_pay_method(0, 0, true);
                }
                break;
        }
        if(empty($pay_method)){
            $this->error_tips('系统管理员没开启任一一种支付方式！');
        }
        if(empty($pay_method[$pay_type])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！');
        }

        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式');
        }
        $pay_class = new $pay_class_name('','',$pay_type,$pay_method[$pay_type]['config'],$this->user_session,1);
        $get_pay_param = $pay_class->return_url();
        if($pay_type!='offline'){
            $get_pay_param['order_param']['return']=1;
        }
        if(empty($get_pay_param['error'])){

            switch($get_pay_param['order_param']['order_type']){
                case 'group':
                    $now_order = D('Group_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'meal':
                    $now_order = D('Meal_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'takeout':
                case 'food':
                case 'foodPad':
                    $now_order = D('Meal_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'weidian':
                    $now_order = D('Weidian_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'recharge':
                    $now_order = D('User_recharge_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'appoint':
                    $now_order = D('Appoint_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'waimai':
                    $now_order = D('Waimai_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'wxapp':
                    $now_order = D('Wxapp_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'store':
                    $now_order = D('Store_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'shop':
                case 'mall':
                    $now_order = D('Shop_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                case 'plat':
                    $now_order = D('Plat_order')->where(array('orderid'=>$get_pay_param['order_param']['order_id']))->find();
                    break;
                default:
                    $this->error_tips('非法的订单');
            }


            if(empty($now_order)){
                $this->error_tips('该订单不存在');
            }
            $now_order['order_type'] = $get_pay_param['order_param']['order_type'];
            if($now_order['paid']&&$now_order['order_type']!='balance-appoint'){
                switch($get_pay_param['order_param']['order_type']){
                    case 'group':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=group_order&order_id='.$now_order['order_id'];
                        break;
                    case 'meal':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Meal&a=detail&orderid='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'takeout':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Takeout&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'food':
                    case 'foodPad':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Food&a=order_detail&order_id='.$now_order['order_id'].'&mer_id='.$now_order['mer_id'].'&store_id='.$now_order['store_id'];
                        break;
                    case 'weidian':
                        $redirctUrl = D('Weidian_order')->get_weidian_url(array('wecha_id'=>$now_order['uid'],'order_no'=>$now_order['weidian_order_id'],'pay_money'=>$now_order['money'],'third_id'=>$now_order['third_id'],'payment_method'=>$now_order['pay_type']));
                        $this->NoticeWDAsyn($now_order['orderid']);
                        break;
                    case 'appoint':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=appoint_order&order_id='.$now_order['order_id'];
                        break;
                    case 'waimai':
                        $redirctUrl = C('config.site_url').'/index.php?g=WaimaiWap&c=Order&a=detail&order_id='.$now_order['order_id'];
                        break;
                    case 'recharge':
                        $redirctUrl = C('config.site_url').D('User_recharge_order')->get_pay_after_url($now_order['label'],$now_order['is_mobile_pay']);
                        // $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=index';
                        break;
                    case 'wxapp':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Wxapp&a=pay_back&order_id='.$now_order['order_id'];
                        break;
                    case 'store':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=My&a=store_order_list';
                        break;
                    case 'shop':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Shop&a=status&order_id=' . $now_order['order_id'];
                        break;
                    case 'mall':
                        $redirctUrl = C('config.site_url').'/'.$this->indep_house.'?c=Mall&a=status&order_id=' . $now_order['order_id'];
                        break;
                    case 'plat':
                        $redirctUrl = D('Plat_order')->get_order_url($now_order['order_id'],true);
                        break;
                }
                redirect($redirctUrl);exit;
            }

            if($get_pay_param['order_param']['order_type'] == 'group'){
                $pay_info = D('Group_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'meal' || $get_pay_param['order_param']['order_type'] == 'takeout' || $get_pay_param['order_param']['order_type'] == 'food' || $get_pay_param['order_param']['order_type'] == 'foodPad'){
//                 $get_pay_param['order_param']['orderid'] = $get_pay_param['order_param']['order_id'];
//                 unset($get_pay_param['order_param']['order_id']);
                $pay_info = D('Meal_order')->after_pay($get_pay_param['order_param'], $get_pay_param['order_param']['order_type']);
            }else if($get_pay_param['order_param']['order_type'] == 'weidian'){
                $pay_info = D('Weidian_order')->after_pay($get_pay_param['order_param']);
                if(($pay_info['error']==0) && isset($pay_info['url']) && ($get_pay_param['order_param']['pay_type'] == 'weixin')){  /***异步通知***/
                    $this->NoticeWDAsyn($get_pay_param['order_param']['order_id']);
                }
            }else if($get_pay_param['order_param']['order_type'] == 'recharge'){
                $pay_info = D('User_recharge_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'appoint'){

                    $pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);

                //$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'balance-appoint'){

                    $pay_info =D('Appoint_order')->balance_after_pay($get_pay_param['order_param']);

                //$pay_info = D('Appoint_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'waimai'){
                $pay_info = D('Waimai_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'wxapp'){
                $pay_info = D('Wxapp_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'store'){
                $pay_info = D('Store_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'shop' || $get_pay_param['order_param']['order_type'] == 'mall'){
                $pay_info = D('Shop_order')->after_pay($get_pay_param['order_param']);
            }else if($get_pay_param['order_param']['order_type'] == 'plat'){
                $pay_info = D('Plat_order')->after_pay($get_pay_param['order_param']);
            }else{
                $this->error_tips(L('_ILLEGAL_ORDER_'));
            }
            if(empty($pay_info['error'])){
                $pay_info['msg'] = L('_PAY_SUCCESS_JUMP_');
            }
            if(empty($pay_info['url'])){
                $this->error_tips($pay_info['msg']);
            }else{
                if(cookie('is_house')){
                    $pay_info['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'),$pay_info['url']);
                }else{
                    $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                }
                $this->assign('pay_info',$pay_info);
                $this->display('after_pay');
            }
        }else{
            $this->error_tips($get_pay_param['msg']);
        }
    }

    public function getPayMessage(){
        $key = $_POST['pay_type'];
        $key_list = $_POST['key_list'];
        $where = array('tab_id'=>$key,'gid'=>7);
        $list = explode("|",$key_list);

        $data = array();
        $result = D('Config')->field(true)->where($where)->select();

        foreach($result as $v){
            if(in_array($v['info'],$list)){
                $data[$v['info']] = $v['value'];
            }
        }

        echo json_encode($data);
    }

    public function MonerisPay(){
        import('@.ORG.pay.MonerisPay');
        $moneris_pay = new MonerisPay();
        $resp = $moneris_pay->payment($_POST,$this->user_session['uid']);
//        var_dump($resp);die();
        if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
            if(!$_POST['order_type']) $_POST['order_type'] = "shop";
            if($_POST['order_type'] == "recharge"){
                $url = U("Wap/My/my_money");
            }else{
                $order = explode("_",$_POST['order_id']);
                $order_id = $order[1];
                $url =U("Wap/Shop/status",array('order_id'=>$order_id));
            }

            $this->success(L('_PAYMENT_SUCCESS_'),$url,true);
        }else{
            $this->error($resp['message'],'',true);
        }
    }

    public function WeixinAndAli(){
        //获取支付的相关配置数据
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
        //var_dump($_POST);
        //var_dump($this->user_session);
        //支付类型 weixin alipay
        $channelId = '';
        $pay_type = $_POST['pay_type'];
        //微信公众号 选择微信支付
        if($this->is_wexin_browser && $pay_type == 'weixin') {
            $channelId = 'WX_JSAPI';
            $pay_url = 'http://open.4jicao.com/goods/payForSubmit';
        }
        //H5 选择微信支付
        if(!$this->is_wexin_browser && $pay_type == 'weixin')
            $channelId = 'WX_MWEB';
        //H5 支付宝支付
        if($pay_type == 'alipay')
            $channelId = 'ALIPAY_WAP';

        $order_id = explode('_',$_POST['order_id'])[1];
        $data['mchId'] = $pay_id;
        $data['mchOrderNo'] = $_POST['order_id'].'_'.time();
        $data['channelId'] = $channelId;
        $data['currency'] = 'CAD';
        //单位分
        $data['amount'] = $_POST['charge_total'] * 100;
        $data['clientIp'] = real_ip();
        $data['device'] = 'WEB';
        //支付结果回调URL
        $data['notifyUrl'] = 'https://www.tutti.app/notify';
        $data['subject'] = $order_id;
        $data['body'] = $_POST['order_id'];
//        $data['param1'] = '';
//        $data['param2'] = '';
        if($this->is_wexin_browser && $pay_type == 'weixin') {
            $data['returnUrl'] = 'https://www.tutti.app/notify';
            $data['extra'] = json_encode(array('openId' => $this->user_session['openid']));
        }
        if(!$this->is_wexin_browser && $pay_type == 'weixin') {
            $h5_info['type'] = "WAP";
            $h5_info['wap_url'] = 'https://tutti.app/wap.php';
            $h5_info['wap_name'] = 'Tutti';
            $sceneInfo['h5_info'] = $h5_info;
            $data['extra'] = json_encode(array('sceneInfo' => $sceneInfo));
        }
        $data['sign'] = $this->getSign($data,$pay_key);
        //echo $pay_url;
        //ksort($data);
        //var_dump($data);
        //var_dump(json_encode($data));
        file_put_contents("./test_log.txt",date("Y/m/d")."   ".date("h:i:sa")."   "."Request" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($data)."\r\n",FILE_APPEND);
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlPost($pay_url,'params='.json_encode($data));
        //var_dump($result);
        if($channelId == 'WX_JSAPI' || $channelId == 'WX_MWEB'){
            if($result['success']){
                //处理小费
                $order_data = array('tip_charge'=>$_POST['tip']);
                //处理优惠券
                if($_POST['coupon_id']){
                    $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                    if(!empty($now_coupon)){
                        $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id'=>$_POST['coupon_id']))->find();
                        $coupon_real_id = $coupon_data['coupon_id'];
                        $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                        $in_coupon = array('coupon_id'=>$data['coupon_id'],'coupon_price'=>$coupon['discount']);
                        $order_data = array_merge($order_data,$in_coupon);
                    }
                }
                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_data);
                $this->success('', $result['url']);
            }
            else
                $this->error('Fail'.' - errCode:'.$result['errcode']);
        }else {
            //通信标识 及 创建支付订单是否成功
            if ($result['retCode'] == 'SUCCESS') {
                //交易结果
                if ($result['resCode'] == 'SUCCESS') {
                    //先处理一下订单信息
                    //处理小费
                    $order_data = array('tip_charge'=>$_POST['tip']);
                    //处理优惠券
                    if($_POST['coupon_id']){
                        $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                        if(!empty($now_coupon)){
                            $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id'=>$_POST['coupon_id']))->find();
                            $coupon_real_id = $coupon_data['coupon_id'];
                            $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                            $in_coupon = array('coupon_id'=>$data['coupon_id'],'coupon_price'=>$coupon['discount']);
                            $order_data = array_merge($order_data,$in_coupon);
                        }
                    }
                    D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_data);
                    //=========
                    if($pay_type == 'weixin')
                        $this->success('', $result['codeUrl']);
                    if($pay_type == 'alipay')
                        $this->success('', $result['payUrl']);
                } else {
                    $this->error($result['errCodeDes'].' - errCode:'.$result['errcode']);
                }
            } else {
                $this->error($result['retMsg'].' - errCode:'.$result['errcode']);
            }
        }

//        echo json_encode($result);
    }

    public function receipt(){
        $now_order = D('Shop_order')->get_order_detail(array('uid' => $this->user_session['uid'], 'order_id' => $_GET['order_id']));
        $pay_record = D('Pay_moneris_record')->field(true)->where(array('order_id'=>$_GET['order_id']))->find();
        if($pay_record)
            $now_order = array_merge($now_order,$pay_record);
//        var_dump($now_order);
        foreach($now_order['info'] as $k=>$v){
            $goods = D('Shop_goods')->field(true)->where(array('goods_id'=>$v['goods_id']))->find();
            $now_order['info'][$k]['name'] = lang_substr($goods['name'],C('DEFAULT_LANG'));

            //garfunkel 显示规格和分类
            $spec_desc = '';
            $spec_ids = explode('_',$v['spec_id']);
            foreach ($spec_ids as $vv){
                $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                $spec_desc = $spec_desc == '' ? lang_substr($spec['name'],C('DEFAULT_LANG')) : $spec_desc.','.lang_substr($spec['name'],C('DEFAULT_LANG'));
            }

            if($v['pro_id'] != '')
                $pro_ids = explode('|',$v['pro_id']);
            else
                $pro_ids = array();

            foreach ($pro_ids as $vv){
                $ids = explode(',',$vv);
                $proId = $ids[0];
                $sId = $ids[1];

                $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                $nameList = explode(',',$pro['val']);
                $name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));

                $spec_desc = $spec_desc == '' ? $name : $spec_desc.','.$name;
            }

            if ($spec_desc != '')
                $now_order['info'][$k]['spec'] = $spec_desc;
        }


        $this->assign('order',$now_order);
        $this->display();
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
}
?>
