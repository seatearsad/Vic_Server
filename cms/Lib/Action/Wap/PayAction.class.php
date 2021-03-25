<?php
class PayAction extends BaseAction{
    protected function _initialize() {
        parent::_initialize();
        if(defined('IS_INDEP_HOUSE')){
            $this->indep_house = C('INDEP_HOUSE_URL');
        }else{
            $this->indep_house = 'wap.php';
        }
        //获取倒计时时间 web app 时间不同
        $config = D('Config')->get_config();
        $web_count_down = $config['pay_count_down_web'];

        $this->assign('count_down',$web_count_down*60);
        //var_dump($_POST);die();
    }
    public function check(){
        if(count($_POST) > 0) {
            var_dump($_POST);
            die();
        }
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
            $now_order = D('User_recharge_order')->get_pay_order($this->user_session['uid'],intval($_GET['order_id']),true);
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
            //var_dump($now_order);die();
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
//var_dump($order_info);die();

        //ADD garfunkel
        $order_info['order_name'] = lang_substr($order_info['order_name'],C('DEFAULT_LANG'));
        if($this->config['open_extra_price']==1&&($order_info['order_type']!='appoint'||$order_info['discount_status'])){
            $user_score_use_percent=(float)$this->config['user_score_use_percent'];
            $order_info['order_extra_price'] = bcdiv($order_info['extra_price'],$user_score_use_percent,2);
        }else{
            $order_info['order_extra_price'] = 0;
            $order_info['extra_price'] = 0;
        }
        //$this->assign('order_info',$order_info);

        //garfunkel 如果修改信用卡的选择
        if($_GET['card_id']){
            D('User_card')->clearIsDefaultByUid($this->user_session['uid']);
            D('User_card')->field(true)->where(array('id'=>$_GET['card_id']))->save(array('is_default'=>1));
        }

        $card_list = D('User_card')->getCardListByUidForCheck($this->user_session['uid']);
        //var_dump($card_list);die();
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
        //$now_user['now_money']="55.5";
        $this->assign('now_user',$now_user);

        if($_GET['type'] != 'recharge' && $_GET['type'] != 'weidian' && ($order_info['business_type']=='' || $order_info['business_type']!='card_new_recharge') ) {

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
//            if($card_info['status']) {
//
//                $merchant_balance = $card_info['card_money'] + $card_info['card_money_give'];
//                if (isset($order_info['discount_status']) && !$order_info['discount_status'] || empty($card_info)||empty($card_info['discount'])) {
//                    $card_info['discount'] = 10;
//                }
//                $_SESSION['discount'] = $card_info['discount'];
//                $tmp_order['uid'] = $this->user_session['uid'];
//                $tmp_order['total_money'] = $order_info['total_money'] * $card_info['discount'] / 10 - $cheap_info['wx_cheap'];
//
//                if ((!isset($order_info['discount_status']) || $order_info['discount_status'])&&$_GET['unmer_coupon']!=1) {
//                    if (!empty($_GET['card_id'])) {
//                        $now_coupon = D('Member_card_coupon')->get_coupon_by_recordid($_GET['card_id'], $this->user_session['uid']);
//                        $now_coupon['type'] = 'mer';
//                        $this->assign('now_coupon', $now_coupon);
//                    } else {
//                        if (empty($_GET['merc_id'])) {
//                            if (!empty($order_info['business_type'])) {
//                                $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform, $order_info['business_type']);
//                            } else {
//                                $card_coupon_list = D('Card_new_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $platform);
//                            }
//                            $mer_coupon = reset($card_coupon_list);
//
//                        } else {
//                            $mer_coupon = D('Card_new_coupon')->get_coupon_info($_GET['merc_id']);
//                        }
//
//                    }
//                }
//            }else{
                if ($cheap_info['can_cheap']) {
                    $tmp_order['total_money']-=$cheap_info['wx_cheap'];
                }
//            }

            //是否与其他优惠互斥
            $is_chi = false;
            if($order_info['delivery_discount'] > 0 && $order_info['delivery_discount_type'] == 0){
                $is_chi = true;
            }
            if($order_info['merchant_reduce'] > 0 && $order_info['merchant_reduce_type'] == 0){
                $is_chi = true;
            }
            $order_info['is_c'] = $is_chi ? 0 : 1;

            //平台优惠券
            if (($tmp_order['total_money'] > $mer_coupon['discount'] || empty($mer_coupon)) && $_GET['unsys_coupon']!=1 && ($order_info['discount_status'] || !isset($order_info['discount_status']))) {
                $tmp_order['total_money'] -= empty($mer_coupon['discount']) ? 0 : $mer_coupon['discount'];
                if (empty($_GET['sysc_id'])) {  //没有选择优惠券

                    //如果是二次支付

                    if ($order_info['coupon_id']>0){
                        $sysc_id=$order_info['coupon_id'];
                        //如果选择的为活动优惠券
                        if(strpos($sysc_id,'event')!== false){
                            $event = explode('_',$sysc_id);
                            $event_coupon_id = $event[2];
                            $list = D('New_event')->getUserCoupon($this->user_session['uid'],0,$tmp_order['total_money'],$event_coupon_id);
                            $system_coupon = reset($list);
                            if($system_coupon)
                                $system_coupon['id'] = $system_coupon['coupon_id'].'_'.$system_coupon['id'];
                        }else {
                            $system_coupon = D('System_coupon')->get_coupon_info($sysc_id);
                        }
                        if($order_info['delivery_discount_type'] == 0)
                            $order_info['delivery_discount'] = 0;
                        if($order_info['merchant_reduce_type'] == 0)
                            $order_info['merchant_reduce'] = 0;
                    }
//                    if (!empty($order_info['business_type'])) {
//                        $now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform, $order_info['business_type']);
//                    } else {
//                        $now_coupon = D('System_coupon')->get_noworder_coupon_list($tmp_order, $_GET['type'], $this->user_session['phone'], $this->user_session['uid'], $platform);
//                    }
//                    var_dump($system_coupon);die();
//                    $system_coupon = reset($now_coupon);
//
//                    if(empty($system_coupon)){
//
//                        $event_coupon = D('New_event')->getUserCoupon($this->user_session['uid'],0,$tmp_order['total_money']);
//                        if($event_coupon) {
//                            $system_coupon = reset($event_coupon);
//                            $system_coupon['id'] = $system_coupon['coupon_id'] . '_' . $system_coupon['id'];
//                            //var_dump($system_coupon);die();
//                        }
//                    }

                } else {

                    $sysc_id = $_GET['sysc_id'];
                    //如果选择的为活动优惠券
                    if(strpos($sysc_id,'event')!== false){
                        $event = explode('_',$sysc_id);
                        $event_coupon_id = $event[2];
                        $list = D('New_event')->getUserCoupon($this->user_session['uid'],0,$tmp_order['total_money'],$event_coupon_id);
                        $system_coupon = reset($list);
                        if($system_coupon)
                            $system_coupon['id'] = $system_coupon['coupon_id'].'_'.$system_coupon['id'];
                    }else {
                        $system_coupon = D('System_coupon')->get_coupon_info($_GET['sysc_id']);
                    }
                    if($order_info['delivery_discount_type'] == 0)
                        $order_info['delivery_discount'] = 0;
                    if($order_info['merchant_reduce_type'] == 0)
                        $order_info['merchant_reduce'] = 0;
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

            if(!($order_info['delivery_discount_type'] == 0 && $order_info['delivery_discount'] != 0) && !($order_info['merchant_reduce_type'] == 0 && $order_info['merchant_reduce'] != 0) && !empty($system_coupon)){

                //if (($order_info['merchant_reduce_type'] == 1 && $order_info['delivery_discount_type'] == 1 && !empty($system_coupon)) || ($order_info['merchant_reduce'] == 0 && $order_info['delivery_discount'] == 0 && !empty($system_coupon))) {
                //$system_coupon['coupon_url_param'] = array('sysc_id' => $system_coupon['id'], 'order_id' => $order_info['order_id'], 'type' => $_GET['type']);
                $system_coupon['coupon_url_param'] = array('sysc_id' => $system_coupon['id'], 'order_id' => $order_info['order_id'], 'type' => $_GET['type']);
                if($system_coupon['discount'] >= $tmp_order['total_money']){
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
        //var_dump($order_info);die();
        $this->assign('order_info',$order_info);

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
        if($_GET['type'] != 'recharge') {
            $store = D('Merchant_store')->field(true)->where(array('store_id' => $now_order['order_info']['store_id']))->find();
            $store_pay = explode('|', $store['pay_method']);
            $pay_list = array();
            foreach ($pay_method as $k => $v) {
                if (in_array($k, $store_pay)) {
                    $pay_method[$k]['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                    $pay_list[$k] = $pay_method[$k];
                }
            }
            $city = D('Area')->where(array('area_id'=>$store['city_id']))->find();
            $this->assign('jetlag',$city['jetlag']);
        }else{
            foreach ($pay_method as $k => $v) {
                $pay_method[$k]['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                $pay_list[$k] = $pay_method[$k];
            }
        }

        $pay_list = array_reverse($pay_list,true);
        $this->assign('is_wexin_browser',$this->is_wexin_browser);
        $this->assign('pay_method',$pay_list);

        if($_GET['type'] == 'group'){
            $this->behavior(array('model'=>'Pay_group','mer_id'=>$order_info['mer_id'],'biz_id'=>$order_info['order_id']),true);
        }else if($_GET['type'] == 'meal'){
            $this->behavior(array('model'=>'Play_meal','mer_id'=>$order_info['mer_id'],'biz_id'=>$order_info['order_id']),true);
        }

        $config = D('Config')->get_gid_config(43);
        $not_touch = array();
        foreach ($config as $v){
            if($v['name'] == 'not_touch'){
                $txt = explode('|',$v['value']);
                $not_touch['title'] = $txt[0];
                $not_touch['content'] = $txt[1];
            }

            if($v['name'] == 'not_touch_enable'){
                if($v['value'] == '1'){
                    $not_touch['status'] = 1;
                }else{
                    $not_touch['status'] = 0;
                }
            }
        }
        $this->assign('not_touch',$not_touch);
        $this->assign('back_url',U("My/shop_order_list"));
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

    //余额 和 现金支付
    public function go_pay(){
        //换参数
        $result_url=$this->get_result_url($_POST['order_id']);//1 成功  0 失败

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

        $result_url=$this->get_result_url($_POST['order_id']);

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
                    //如果选择的为活动优惠券
                    if(strpos($_POST['coupon_id'],'event')!== false) {
                        $event = explode('_',$_POST['coupon_id']);
                        $coupon_id = $event[1];
                        if($coupon_id){
                            $coupon = D('New_event_coupon')->where(array('id'=>$coupon_id))->find();
                            $now_coupon['coupon_price'] = $coupon['discount'];
                            $now_coupon['sysc_id'] = $_POST['coupon_id'];
                        }
                    }else{
                        $system_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                        $now_coupon['coupon_price'] = $system_coupon['price'];
                        $now_coupon['sysc_id'] = $system_coupon['id'];
                    }
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
            //判断是否有减免配送费活动
            if($_POST['delivery_discount'] != null){
                $order_info['delivery_discount'] = $_POST['delivery_discount'];
                if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
                    D('Shop_order')->field(true)->where(array('order_id'=>$order_info['order_id']))->save(array('delivery_discount'=>$order_info['delivery_discount']));
                    if($order_info['delivery_discount'] > 0) {
                        D('New_event')->addEventCouponByType(3, $this->user_session['uid']);
                        $order_info['order_total_money'] = $order_info['order_total_money'] - $order_info['delivery_discount'];
                    }
                }
            }
            //店铺满减
            if($_POST['merchant_reduce'] != null){
                $order_info['merchant_reduce'] = $_POST['merchant_reduce'];
                if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
                    D('Shop_order')->field(true)->where(array('order_id'=>$order_info['order_id']))->save(array('merchant_reduce'=>$order_info['merchant_reduce']));
                    $order_info['order_total_money'] = $order_info['order_total_money'] - $order_info['merchant_reduce'];
                }
            }
        }

        //用户信息
        $now_user = D('User')->get_user($this->user_session['uid']);

        //----------------------------------------------------------------------------
        $this->Save_data_pre_pay($_POST);
//        if($_POST['not_touch'] != null && $_POST['not_touch'] == 1){
////            D('Shop_order')->field(true)->where(array('order_id'=>$order_info['order_id']))->save(array('not_touch'=>1));
////        }
////        $this->Save_coupon_info($_POST['order_id'],$_POST['coupon_id']);
////        $this->Save_order_desc($_POST['note'],$order_info['order_id']);
////        //保存地址备注
////        $this->Save_user_address_detail($_POST['address_detail'],$_POST['address_id']);
        //------------------------------------------------------------------------------

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

        if($_POST['pay_type'] == 'Cash' || $_POST['pay_type'] == 'offline') //现金支付没有小费
            $order_info['tip'] = 0;

        if($order_info['tip'] > 0){
            $order_info['order_total_money'] = $order_info['order_total_money'] + $order_info['tip'];
            $data['tip_charge'] = $order_info['tip'];
            if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){
                D('Shop_order')->field(true)->where(array('order_id'=>$order_info['order_id']))->save($data);
            }
        }

        if($_POST['note'] && $_POST['note'] != '')
            $order_info['desc'] = $_POST['note'];
        if($_POST['est_time'] && $_POST['est_time'] != ''){
            $order_info['expect_use_time'] = strtotime($_POST['est_time']);
        }

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
        }else if($order_info['order_type'] == 'shop' || $order_info['order_type'] == 'mall'){   //----------------------------->shop
            $save_result = D('Shop_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'plat'){
            $save_result = D('Plat_order')->wap_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }else if($order_info['order_type'] == 'balance-appoint'){
            $save_result = D('Appoint_order')->wap_balace_befor_pay($order_info,$now_coupon,$merchant_balance,$now_user);
        }

        if(cookie('is_house')){
            $save_result['url'] = str_replace('wap.php', C('INDEP_HOUSE_URL'), $save_result['url']);
        }

        if ($save_result['error_code']) {                       // 现金支付的错误 TRUE  or FALSE

            $this->error_tips($save_result['msg'],$result_url."0");             // -------------------------------------------------- 错误>>>>>>>>>>>>>>>>>>

        } else if ($save_result['url']) { //如果返回对象中有url（AND error_code=false)，就代表

            //$this->success_tips($save_result['msg'], $save_result['url']);
            $this->success_tips($save_result['msg'], $result_url."1"); // -------------------------------------------------- 余额支付 正确结果>>>>>>>

        } else {       //现金支付结果

            //需要支付的钱
            $pay_money = round($save_result['pay_money'] * 100) / 100;
            if (in_array($order_info['order_type'], array('group', 'meal', 'weidian', 'takeout', 'food', 'foodPad', 'recharge', 'appoint', 'waimai', 'wxapp', 'store', 'shop', 'plat', 'balance-appoint')) && $order_info['mer_id']) {
                $mer_id = $order_info['mer_id'];
            } else {
                $this->config['merchant_ownpay'] = 0;
            }

            $this->config['merchant_ownpay'] = intval($this->config['merchant_ownpay']);

            switch ($this->config['merchant_ownpay']) {
                case '0':
                    $pay_method = D('Config')->get_pay_method($notOnline, $notOffline, true);
                    break;
                case '1':
                    $pay_method = D('Config')->get_pay_method($notOnline, $notOffline, true);
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $mer_id))->find();
                    foreach ($merchant_ownpay as $ownKey => $ownValue) {
                        $ownValueArr = unserialize($ownValue);
                        if ($ownValueArr['open']) {
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
                        }
                    }

                    break;
                case '2':
                    $pay_method = array();
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $mer_id))->find();
                    foreach ($merchant_ownpay as $ownKey => $ownValue) {
                        $ownValueArr = unserialize($ownValue);
                        if ($ownValueArr['open']) {
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name' => $this->getPayName($ownKey), 'config' => $ownValueArr);
                        }
                    }

                    if (empty($pay_method)) {
                        $pay_method = D('Config')->get_pay_method($notOnline, $notOffline, true);
                    }
                    break;
            }

            //配置服务商子商户支付
            if ($this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_client_cert'];
                $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
                $pay_method['weixin']['config']['is_own'] = 1;
            }

            if (empty($pay_method)) {

                $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'),$result_url."0");                     //-------------------------->>>>>>>>>>>>>>>>>>>>

            }else{

                if (empty($pay_method[$_POST['pay_type']])) {
                    $this->error_tips('您选择的支付方式不存在或余额不足，请更换支付方式！',$result_url."0");  //-------------------------->>>>>>>>>>>>>>>>>>>>余额不足错误
                }

                $pay_class_name = ucfirst($_POST['pay_type']);

                //echo($pay_class_name); offline
                $import_result = import('@.ORG.pay.' . $pay_class_name);

                if (empty($import_result)) {
                    $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式',$result_url."0");//----------------->>>>>>>>>>>>>>>>>>>>
                }

                $order_id = $order_info['order_id'];
                if ($_POST['order_type'] == 'takeout' || $_POST['order_type'] == 'food' || $_POST['order_type'] == 'foodPad') {
                    $order_table = 'Meal_order';
                } else if ($_POST['order_type'] == 'recharge') {
                    $order_table = 'User_recharge_order';
                } else if ($_POST['order_type'] == 'balance-appoint') {
                    $order_table = 'Appoint_order';
                    //$order_info['order_type']='appoint';
                } else {
                    $order_table = ucfirst($_POST['order_type']) . '_order';
                }

                //更新长id
                $nowtime = date("ymdHis");
                if ($_POST['order_type'] == 'balance-appoint') {
                    $nowtime = date("mdHis");
                    $orderid = $nowtime . sprintf("%06d", $this->user_session['uid']);;
                } else {
                    $orderid = $nowtime . rand(10, 99) . sprintf("%08d", $this->user_session['uid']);
                }

                $data_tmp['pay_type'] = $_POST['pay_type'];
                $data_tmp['order_type'] = $_POST['order_type'];
                $data_tmp['order_id'] = $order_id;
                $data_tmp['orderid'] = $orderid;
                $data_tmp['addtime'] = $nowtime;
                if (!D('Tmp_orderid')->add($data_tmp)) {
                    $this->error_tips(L('_UPDATE_FAIL_ADMIN_'),$result_url."0");//-------------------------->>>>>>>>>>>>>>>>>>>>
                }else {

                    $save_pay_id = D($order_table)->where(array("order_id" => $order_id))->setField('orderid', $orderid);
                    if (!$save_pay_id) {
                        $this->error_tips(L('_UPDATE_FAIL_ADMIN_'),$result_url."0");
                    } else {
                        $order_info['old_order_id'] = $order_info['order_id'];
                        $order_info['order_id'] = $orderid;
                    }

                    $pay_class = new $pay_class_name($order_info, $pay_money, $_POST['pay_type'], $pay_method[$_POST['pay_type']]['config'], $this->user_session, 1);

                    $go_pay_param = $pay_class->pay();                         //--------------------------------------->>>>>>>>>>

                    //---------------------------------------------------------现金支付后，在Pay里就结束了，不会再往下运行了。

                    if (empty($go_pay_param['error'])) {

                        if (!empty($go_pay_param['url'])) {
                            $this->assign('url', $go_pay_param['url']);
                            $this->display();
                        } else if (!empty($go_pay_param['form'])) {
                            $this->assign('form', $go_pay_param['form']);
                            $this->display();
                        } else if (!empty($go_pay_param['weixin_param'])) {
                            if ($pay_method['weixin']['config']['is_own']) {
                                C('open_authorize_wxpay', true);
                                $share = new WechatShare($this->config, $_SESSION['openid']);
                                $this->hideScript = $share->gethideOptionMenu($mer_id);
                                $arr['hidScript'] = $this->hideScript;
                                $redirctUrl = C('config.site_url') . '/' . $this->indep_house . '?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'] . '&own_mer_id=' . $order_info['mer_id'];
                            } else {
                                $redirctUrl = C('config.site_url') . '/' . $this->indep_house . '?g=Wap&c=Pay&a=weixin_back&order_type=' . $order_info['order_type'] . '&order_id=' . $order_info['order_id'];
                            }
                            $arr['redirctUrl'] = $redirctUrl;
                            $arr['pay_money'] = $pay_money;
                            $arr['weixin_param'] = json_decode($go_pay_param['weixin_param']);
                            $arr['error'] = 0;
                            echo json_encode($arr);
                            die;
                        } else {
                            $this->error_tips(L('_ERROR_PAYMENT_'));
                        }
                    } else {
                        $this->error_tips($go_pay_param['msg']);
                    }
                }
            }
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
        $result_url=$this->get_result_url($_GET['order_id']);

        if(empty($pay_method)){
            $this->error_tips(L('_SYSTEM_NOT_PAY_MODE_'),$result_url.'0');
        }
        if(empty($pay_method[$pay_type])){
            $this->error_tips('您选择的支付方式不存在，请更新支付方式！',$result_url.'0');
        }

        $pay_class_name = ucfirst($pay_type);
        $import_result = import('@.ORG.pay.'.$pay_class_name);
        if(empty($import_result)){
            $this->error_tips('系统管理员暂未开启该支付方式，请更换其他的支付方式',$result_url.'0');
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
                $pay_info['msg'] = L('_PAY_SUCCESS_JUMP_').'---------------';
            }

            if(empty($pay_info['url'])){
                $this->error_tips($pay_info['msg'],$result_url.'0');
            }else{
                if(cookie('is_house')){
                    $pay_info['url'] = str_replace('wap.php',C('INDEP_HOUSE_URL'),$pay_info['url']);
                }else{
                    $pay_info['url'] = preg_replace('#/source/(\w+).php#','/wap.php',$pay_info['url']);
                    $pay_info['url'] =$pay_info['url']."&status=1";
                }
                if ($pay_info['error'] && $urltype == 'front') {
                    $this->redirect($pay_info['url']);
                    exit;
                }

                //现金支付成功的返回处理 peter

                $this->assign('pay_info', $pay_info);
                $this->display('after_pay');
            }
        }else{
            $this->error_tips($get_pay_param['msg']);
        }
    }

    //获得返回地址
    public function get_result_url($order_id){

        $now2_order = D('Shop_order')->get_pay_order($this->user_session['uid'], $order_id);
        $order_info =$now2_order['order_info'];
        $result_url=C('config.site_url') . "/wap.php?g=Wap&c=Shop&a=pay_result&order_id=" . $order_info['order_id'] . '&mer_id=' . $order_info['mer_id'] . '&store_id=' . $order_info['store_id']."&status=";//1 成功  0 失败

        return $result_url;
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

    //信用卡支付使用的是 Wap 下的PayAction.class.php！！！！！！！！！！！！！！！！！！！！！！！就是这里
    public function MonerisPay(){

        import('@.ORG.pay.MonerisPay');
        //-------
        $order = explode("_",$_POST['order_id']);
        $order_id = $order[1];
        $address_id=$_POST['address_id'];

        //---------------------------------------------------------------------------
        $this->Save_data_pre_pay($_POST);
//        if($_POST['not_touch'] != null && $_POST['not_touch'] == 1){
//            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save(array('not_touch'=>1));
//        }
//        if($_POST['tip'] != null) {
//            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save(array('tip_charge'=>$_POST['tip']));
//        }
//        $this->Save_coupon_info($order_id,$_POST['coupon_id']);
//        $this->Save_order_desc($_POST['note'],$order_id);
//        $this->Save_user_address_detail($_POST['address_detail'],$address_id);
        //----------------------------------------------------------------------------
        if ($_POST['order_type']=='recharge'){
            $result_url=U("Wap/My/my_money");
        }else{
            $result_url=$this->get_result_url($order_id);
        }

        //-----------------------------------------------------------------------------

        $moneris_pay = new MonerisPay();
        $resp = $moneris_pay->payment($_POST,$this->user_session['uid'],2);

        //var_dump($resp);echo($result_url);

        if($resp['requestMode'] && $resp['requestMode'] == "mpi"){
            if($resp['mpiSuccess'] == "true"){
                $result = array('error_code' => false,'mode'=>$resp['requestMode'],'html'=>$resp['mpiInLineForm'], 'msg' => $resp['message']);
                $this->ajaxReturn($result);
            }else{
                $this->error($resp['message'],$resp['url'],true);
            }
        }

        if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
            $order = explode("_",$_POST['order_id']);
            $order_id = $order[1];
            //判断是否有减免配送费活动
            if($_POST['delivery_discount'] != null){
                $order_info['delivery_discount'] = $_POST['delivery_discount'];
                if($_POST['order_type'] == 'shop' || $_POST['order_type'] == 'mall'){
                    D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save(array('delivery_discount'=>$order_info['delivery_discount']));
                    if($order_info['delivery_discount'] > 0)
                        D('New_event')->addEventCouponByType(3,$this->user_session['uid']);
                }
            }

            //店铺满减
            if($_POST['merchant_reduce'] != null){
                $order_info['merchant_reduce'] = $_POST['merchant_reduce'];
                if($_POST['order_type'] == 'shop' || $_POST['order_type'] == 'mall'){
                    D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save(array('merchant_reduce'=>$order_info['merchant_reduce']));
                }
            }

            if(!$_POST['order_type']) $_POST['order_type'] = "shop";
            if($_POST['order_type'] == "recharge"){
                $url = U("Wap/My/my_money");
            }else{
                $order = explode("_",$_POST['order_id']);
                $order_id = $order[1];
                $url = U("Wap/Shop/pay_result",array('order_id'=>$order_id));
                $url = $result_url.'1';
            }
            $this->success(L('_PAYMENT_SUCCESS_'),$url,true);

        }else{

            //var_dump($result_url);die();
            if ($_POST['order_type']=='recharge'){
                $this->error($resp['message'],$result_url,true);
            }else{
                $this->error($resp['message'],$result_url.'0',true);
            }
        }
    }
//    //保存地址备注信息的逻辑  by Peter 2021-2-26
//    public function Save_user_address_detail($detail,$address_id){
//        if($detail != null) {
//            if(!checkEnglish($detail) && trim($detail) != ''){
//                $detail_en= translationCnToEn($detail);
//            }else{
//                $detail_en= '';
//            }
//            D('User_adress')->field(true)->where(array('adress_id'=>$address_id))->save(array('detail'=>$detail,'detail_en'=>$detail_en));
//        }
//    }
//    //保存优惠券  by Peter 2021-3-22
//    public function Save_coupon_info($order_id,$coupon_id){
//        //echo ("desc=".$desc.",order_id=".$order_id);die();
//        if($coupon_id != null && trim($coupon_id) != ''){
//            $order = D('Shop_order')->where(array('order_id'=>$order_id))->find();
//            //如果选择的为活动优惠券
//            if(strpos($coupon_id,'event')!== false) {
//                $event = explode('_',$coupon_id);
//                $t_coupon_id = $event[1];
//                if($t_coupon_id){
//                    $coupon = D('New_event_coupon')->where(array('id'=>$t_coupon_id))->find();
//                    $in_coupon = array('coupon_id' => $coupon_id, 'coupon_price' => $coupon['discount']);
//                }
//            }else {
//                $now_coupon = D('System_coupon')->get_coupon_by_id($coupon_id);
//                if (!empty($now_coupon)) {
//                    $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $coupon_id))->find();
//                    $coupon_real_id = $coupon_data['coupon_id'];
//                    $coupon = D('System_coupon')->get_coupon($coupon_real_id);
//                    $in_coupon = array('coupon_id' => $coupon_id, 'coupon_price' => $coupon['discount']);
//                }
//            }
//            if($order['delivery_discount_type'] == 0){
//                $in_coupon['delivery_discount'] = 0;
//            }
//            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($in_coupon);
//        }
//    }
//    //保存地址备注信息的逻辑  by Peter 2021-2-26
//    public function Save_order_desc($desc,$order_id){
//        //echo ("desc=".$desc.",order_id=".$order_id);die();
//        if($desc != null || trim($desc) != '') {
//            if(!checkEnglish($desc) && trim($desc) != ''){
//                $desc_en= translationCnToEn($desc);
//            }else{
//                $desc_en = '';
//            }
//            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save(array('desc'=>$desc,'desc_en'=>$desc_en));
//        }
//    }
    //集中保存函数，支付前需要记录这些数据
    //not touch、地址信息、tip、优惠券
    public function Save_data_pre_pay(&$post){

        $order_id = explode('_',$post['order_id'])[1];
        $save_list=array();
        $not_touch=$post['not_touch'];
        $coupon_id=$post['coupon_id'];
        $tip=$post['tip'];
        $desc=$post['note'];
        $detail=$post['address_detail'];

        //----------------------------------------------------
        if($not_touch != null && $not_touch == 1){
            $save_list['not_touch']=1;
        }
        //----------------------------------------------------
        if($tip != null) {
            if($post['pay_type'] == 'Cash' || $post['pay_type'] == 'offline'){
                //现金支付没有小费
                $tip = 0;
            }
            $save_list['tip_charge']=$tip;
        }
        //----------------------------------------------------
        if($coupon_id != null && trim($coupon_id) != ''){
            $order = D('Shop_order')->where(array('order_id'=>$order_id))->find();
            //如果选择的为活动优惠券
            if(strpos($coupon_id,'event')!== false) {
                $event = explode('_',$coupon_id);
                $t_coupon_id = $event[1];
                if($t_coupon_id){
                    $coupon = D('New_event_coupon')->where(array('id'=>$t_coupon_id))->find();
                    $in_coupon = array('coupon_id' => $coupon_id, 'coupon_price' => $coupon['discount']);
                }
            }else {
                $now_coupon = D('System_coupon')->get_coupon_by_id($coupon_id);
                if (!empty($now_coupon)) {
                    $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $coupon_id))->find();
                    $coupon_real_id = $coupon_data['coupon_id'];
                    $coupon = D('System_coupon')->get_coupon($coupon_real_id);
                    $in_coupon = array('coupon_id' => $coupon_id, 'coupon_price' => $coupon['discount']);
                }
            }
            if($order['delivery_discount_type'] == 0){
                $in_coupon['delivery_discount'] = 0;
            }
            $save_list=array_merge($save_list,$in_coupon);
        }
        //----------------------------------------------------
        if($desc != null || trim($desc) != '') {
            if(!checkEnglish($desc) && trim($desc) != ''){
                $desc_en= translationCnToEn($desc);
            }else{
                $desc_en = '';
            }
            $desc_list=array('desc'=>$desc,'desc_en'=>$desc_en);
            $save_list=array_merge($save_list,$desc_list);
        }
        //----------------------------------------------------
        if(count($save_list)>0){
            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($save_list);
        }
        //----------------------------------------------------
        if($detail != null) {
            if(!checkEnglish($detail) && trim($detail) != ''){
                $detail_en= translationCnToEn($detail);
            }else{
                $detail_en= '';
            }
            $detail_list=array('detail'=>$detail,'detail_en'=>$detail_en);
            //var_dump($post["address_id"].'----'.$detail_list);die();
            D('User_adress')->field(true)->where(array("adress_id"=>$post["address_id"]))->save($detail_list);
        }
    }

    //微信、支付宝请求支付!!!
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
        $address_id=$_POST['address_id'];

        //微信公众号 选择微信支付------------------------------------------> 设置支付渠道 channelId
        if($this->is_wexin_browser && $pay_type == 'weixin') {
            $channelId = 'WX_JSAPI';
            //$pay_url = 'http://open.4jicao.com/goods/payForSubmit';
            $pay_url = 'https://api.iotpaycloud.com/v1/payForSubmit';
        }
        //H5 选择微信支付
        if(!$this->is_wexin_browser && $pay_type == 'weixin')
            $channelId = 'WX_MWEB';
        //H5 支付宝支付
        if($pay_type == 'alipay')
            $channelId = 'ALIPAY_WAP';
        //------------------------------------------------------------------------------------------
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
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlPost($pay_url,'params='.json_encode($data));
        file_put_contents("./test_log.txt",date("Y/m/d")."   ".date("h:i:sa")."   "."Request" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode($data).'----'.json_encode($result,JSON_UNESCAPED_UNICODE)."\r\n",FILE_APPEND);
        //var_dump($result);
        //-------------------------------------------------------------------------------
        $this->Save_data_pre_pay($_POST);
//        $this->Save_coupon_info($order_id,$_POST['coupon_id']);
//        $this->Save_order_desc($_POST['note'],$order_id);
//        $this->Save_user_address_detail($_POST['address_detail'],$_POST['address_id']);
        //-------------------------------------------------------------------------------

        //微信支付
        if($channelId == 'WX_JSAPI' || $channelId == 'WX_MWEB'){
            if($result['success']){
                //处理小费
                $order_data = array('tip_charge'=>$_POST['tip']);
                if($_POST['note'] && $_POST['note'] != '')
                    $order_data['desc'] = $_POST['note'];
                if($_POST['est_time'] && $_POST['est_time'] != ''){
                    $order_data['expect_use_time'] = strtotime($_POST['est_time']);
                }
                //处理优惠券
                //$this->Save_coupon_info($_POST['coupon_id']);
                if($_POST['coupon_id']){
                    //如果选择的为活动优惠券
                    if(strpos($_POST['coupon_id'],'event')!== false) {
                        $event = explode('_',$_POST['coupon_id']);
                        $coupon_id = $event[1];
                        if($coupon_id){
                            $coupon = D('New_event_coupon')->where(array('id'=>$coupon_id))->find();
                            $in_coupon = array('coupon_id' => $_POST['coupon_id'], 'coupon_price' => $coupon['discount']);
                            $order_data = array_merge($order_data, $in_coupon);
                        }
                    }else {
                        $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                        if (!empty($now_coupon)) {
                            $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $_POST['coupon_id']))->find();
                            $coupon_real_id = $coupon_data['coupon_id'];
                            $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                            $in_coupon = array('coupon_id' => $_POST['coupon_id'], 'coupon_price' => $coupon['discount']);
                            $order_data = array_merge($order_data, $in_coupon);
                        }
                    }
                }

                //判断是否有减免配送费活动
                if($_POST['delivery_discount'] != null){
                    $order_data['delivery_discount'] = $_POST['delivery_discount'];
                    if($order_data['delivery_discount'] > 0)
                        D('New_event')->addEventCouponByType(3,$this->user_session['uid']);
                }
                //店铺满减
                if($_POST['merchant_reduce'] != null){
                    $order_data['merchant_reduce'] = $_POST['merchant_reduce'];
                }
                //无接触配送
                if($_POST['not_touch'] != null && $_POST['not_touch'] == 1){
                    $order_data['not_touch'] = 1;
                }

                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_data);
                $this->success('', $result['url']);
            }
            else
                $this->error('Fail'.' - errCode:'.$result['errcode']);
        }else {
            //Alipay支付
            //通信标识 及 创建支付订单是否成功
            if ($result['retCode'] == 'SUCCESS') {
                //交易结果
                if ($result['resCode'] == 'SUCCESS') {
                    //先处理一下订单信息
                    //处理小费
                    $order_data = array('tip_charge'=>$_POST['tip']);
                    if($_POST['note'] && $_POST['note'] != '')
                        $order_data['desc'] = $_POST['note'];
                    if($_POST['est_time'] && $_POST['est_time'] != ''){
                        $order_data['expect_use_time'] = strtotime($_POST['est_time']);
                    }
                    //处理优惠券
                    if($_POST['coupon_id']){
                        //如果选择的为活动优惠券
                        if(strpos($_POST['coupon_id'],'event')!== false) {
                            $event = explode('_',$_POST['coupon_id']);
                            $coupon_id = $event[1];
                            if($coupon_id){
                                $coupon = D('New_event_coupon')->where(array('id'=>$coupon_id))->find();
                                $in_coupon = array('coupon_id' => $_POST['coupon_id'], 'coupon_price' => $coupon['discount']);
                                $order_data = array_merge($order_data, $in_coupon);
                            }
                        }else {
                            $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                            if (!empty($now_coupon)) {
                                $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $_POST['coupon_id']))->find();
                                $coupon_real_id = $coupon_data['coupon_id'];
                                $coupon = D('System_coupon')->get_coupon($coupon_real_id);

                                $in_coupon = array('coupon_id' => $data['coupon_id'], 'coupon_price' => $coupon['discount']);
                                $order_data = array_merge($order_data, $in_coupon);
                            }
                        }
                    }

                    //判断是否有减免配送费活动
                    if($_POST['delivery_discount'] != null){
                        $order_data['delivery_discount'] = $_POST['delivery_discount'];
                        if($order_data['delivery_discount'] > 0)
                            D('New_event')->addEventCouponByType(3,$this->user_session['uid']);
                    }
                    //店铺满减
                    if($_POST['merchant_reduce'] != null){
                        $order_data['merchant_reduce'] = $_POST['merchant_reduce'];
                    }
                    //无接触配送
                    if($_POST['not_touch'] != null && $_POST['not_touch'] == 1){
                        $order_data['not_touch'] = 1;
                    }
                    D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($order_data);

                    //===============================最终的跳转页  peter

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

    public function secure3d(){
        if($_GET['PaReq'] && $_GET['TermUrl'] && $_GET['MD'] && $_GET['ACSUrl']){
            $inLineForm ='<html><head><title>Title for Page</title></head><SCRIPT LANGUAGE="Javascript" >' .
                "<!--
				function OnLoadEvent()
				{
					document.downloadForm.submit();
				}
				-->
				</SCRIPT>" .
                '<body onload="OnLoadEvent()">
					<form name="downloadForm" action="' . urldecode($_GET['ACSUrl']) .
                '" method="POST">
					<noscript>
					<br>
					<br>
					<center>
					<h1>Processing your 3-D Secure Transaction</h1>
					<h2>
					JavaScript is currently disabled or is not supported
					by your browser.<br>
					<h3>Please click on the Submit button to continue
					the processing of your 3-D secure
					transaction.</h3>
					<input type="submit" value="Submit">
					</center>
					</noscript>
					<input type="hidden" name="PaReq" value="' . str_replace(' ','+',urldecode($_GET['PaReq'])) . '">
					<input type="hidden" name="MD" value="' . urldecode($_GET['MD']) . '">
					<input type="hidden" name="TermUrl" value="' . urldecode($_GET['TermUrl']) .'">
				</form>
				</body>
				</html>';
            //$inLineForm = urldecode($_GET['ACSUrl']).'-'.urldecode($_GET['PaReq']).'-'.urldecode($_GET['MD']).'-'.urldecode($_GET['TermUrl']);
            //$inLineForm = str_replace(' ','',urldecode($_GET['PaReq']));
            echo $inLineForm;
            exit();
        }

        if($_POST['PaRes'] && $_POST['MD']) {
            $PaRes = $_POST['PaRes'];
            $MD = $_POST['MD'];
            import('@.ORG.pay.MonerisPay');
            $moneris_pay = new MonerisPay();

            $resp = $moneris_pay->MPI_Acs($PaRes, $MD);

            if ($resp['responseCode'] != 'null' && $resp['responseCode'] < 50) {
                if(strpos($resp['url'],'#')!== false) {
                    $script = '<SCRIPT LANGUAGE="Javascript" >var ua = navigator.userAgent;
                            if(ua.match(/TuttiiOS/i)){
                                  window.webkit.messageHandlers.payComplate.postMessage(["'.$resp['url'].'"]);
                            }
                            if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
                                if (typeof (window.activity.showToast) != "undefined") {
                                    window.activity.showToast("'.$resp['url'].'");
                                }
                            }
                            </SCRIPT>';
                    echo $script;
                    exit();
                }else{
                    if(empty($this->user_session)){
                        $user = D('User')->field(true)->where(array('uid'=>$resp['uid']))->find();
                        session('user',$user);
                        $this->user_session = session('user');
                    }
                    //$this->success($resp['message'], $resp['url']);
                    redirect(U('My/my_money'));
                }
            } else {
                if(strpos($resp['url'],'#')!== false) {
                    //echo $resp['message'];
                    $script = '<SCRIPT LANGUAGE="Javascript" >var ua = navigator.userAgent;
                            if(ua.match(/TuttiiOS/i)){
                                  window.webkit.messageHandlers.payFail.postMessage(["'.$resp['url'].'"]);
                            }
                            if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
                                if (typeof (window.activity.showFailToast) != "undefined") {
                                    window.activity.showFailToast("'.$resp['url'].'");
                                }
                            }
                            </SCRIPT>';
                    echo $script;
                    exit();
                }else{
                    $this->error($resp['message'], $resp['url']);
                }
            }
        }else{
            $this->error();
        }

        //$this->success($result['message'], $result['url']);
    }
}
?>
