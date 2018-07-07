<?php
// 商家余额
class Merchant_money_listModel extends Model{
    //增加余额
    public function add_money($mer_id,$desc,$order_info){
        //商家绑定用户
        $mer_user = D('Merchant')->get_merchant_user($mer_id);

        if(isset($order_info['store_id'])){
            $now_store = M('Merchant_store')->where(array('store_id'=>$order_info['store_id']))->find();
            $date['store_id'] = $order_info['store_id'];
        }
        //自有支付
        if(isset($order_info['is_own'])&&$order_info['is_own']>0){
            $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
            $alias_name = $this->get_alias_c_name($order_info['order_type']);
            $store_name = '无';
            if (!empty($now_store)) {
                $store_name = $now_store['name'];
            }
            if($order_info['is_own']==2){
                $remark = '请到子商户平台中查看';
            }else{
                $remark = '请到商家平台中查看';
            }
            $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $mer_user['openid'], 'first' => '收款成功,' , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$order_info['payment_money'],'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' =>$remark));

            $order_info['payment_money']=0;
        }

        switch($order_info['order_type']){
            case 'group':
                if(!empty($order_info['refund'])){
                    $num =1;
                    $money=$order_info['refund_money'];
                }elseif(!$order_info['verify_all']){
                    $num =1;
                    if($order_info['pay_type']=='offline'){
                        $count = D('Group_pass_relation')->get_pass_num($order_info['order_id'],1);
                        if($order_info['score_deducte']>$order_info['price']){
                            if($order_info['score_deducte']-$count*$order_info['price']>0){
                                $money =$order_info['price'];
                            }else{
                                $money = $order_info['score_deducte']-($count-1)*$order_info['price'];
                            }
                        }else{
                            if($count==1){
                                $money = $order_info['score_deducte'];
                            }else{
                                $money=0;
                                return array('error_code'=>false,'msg'=>'无收入记录');
                            }
                        }
                    }else{
                        $money = ($order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'])/$order_info['num']*100/100;
                    }
                }else{
                    $num =$order_info['num'];
                    $money = ($order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'])*100/100;
                }

                $order_info['total_money'] = $order_info['total_money'];
                break;
            case 'meal':
                $num =$order_info['total'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['merchant_balance'];
                $order_info['total_money'] = $order_info['total_price'];
                break;
            case 'shop':
                $num =$order_info['num'];
                if($order_info['is_pick_in_store']==0&&($order_info['card_give_money']>0||$order_info['card_price']>0)&&$order_info['order_from']==0){//平台配送 （使用了商家赠送余额或商家优惠券）
                    $pay_for_system = $order_info['freight_charge'];
                    //$money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];
                    /////平台佣金计算规则改为：应付总费用 * 比例
                    $money = $order_info['price'];

                }else if($order_info['is_pick_in_store']==0&&$order_info['order_from']==1){//平台配送 （使用了商家赠送余额或商家优惠券）
                    $pay_for_system = $order_info['no_bill_money'];
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']+$order_info['merchant_balance']-$order_info['freight_charge'];

                }else{
                    $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];
                }

                $order_info['total_money'] = $order_info['total_price'];
                break;
            case 'shop_offline':
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['score_deducte']+$order_info['coupon_price']+$order_info['balance_reduce']-$order_info['no_bill_money']+$order_info['merchant_balance'];
                $order_info['total_money'] = $order_info['total_price'];
                break;
            case 'appoint':
                $num = 1;
                $money = $order_info['money'];
                $order_info['total_money']  = $order_info['total_money'];
                break;
            case 'store':
                $num =1;
                $order_info['total_money'] = $order_info['total_price'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance']+$order_info['coupon_price']+$order_info['score_deducte'];
                break;
            case 'cash':
                $num =1;
                $order_info['total_money'] = $order_info['total_price'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
            case 'wxapp':
                $num =1;
                $order_info['total_money'] = $order_info['money'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
            case 'weidian':
                $num =$order_info['order_num'];
                $order_info['total_money'] = $order_info['money'];
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
            case 'withdraw':
                $num =1;
                $money = $order_info['money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            case 'yydb':
                $num =1;
                $money = $order_info['money'];
                $order_info['total_money']= $money;
                break;
            case 'coupon':
                $num =1;
                $money = $order_info['money'];
                $order_info['total_money']= $money;
                break;
            case 'spread':
                $num =1;
                $money = $order_info['money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            case 'merrecharge':
                $num =1;
                $money = $order_info['pay_money'];
                $date['money']= $money;
                $date['total_money']= $money;
                break;
            default:
                $money = $order_info['balance_pay']+$order_info['payment_money']+$order_info['merchant_balance'];
                break;
        }
        $percent = D('Percent_rate')->get_percent($mer_id,$order_info['order_type'],$money);

        if($money<=0){
            if($pay_for_system!=0){
                if($order_info['order_from']==1){
                    $desc_pay_for_system='商城订单快递配送转为平台配送，系统扣除平台配送费';
                }else{
                    $desc_pay_for_system= '快店平台配送，平台从商家余额中扣除订单的配送费';
                }
                $result_pay = $this->use_money($mer_id,$pay_for_system,$order_info['order_type'],$desc_pay_for_system,$order_info['real_orderid']);
            }
            $this->merchant_card($mer_id,$order_info);
            return array('error_code'=>false,'msg'=>'无收入记录');
        }

        $date['num'] = $num;
        $date['mer_id']=$mer_id;
        if($order_info['order_type']!='withdraw' && $order_info['order_type']!='merrecharge'){
            if(C('config.open_extra_price')==1){
                $date['score']=D('Percent_rate')->get_extra_money($order_info);
                $date['score_count']=$order_info['score_used_count'];
            }else{
                $date['score'] = $money*C('config.user_score_get');
                $date['score_count']=$order_info['score_used_count'];
            }
        }

        $date['type']=$order_info['order_type'];
        if($date['type']=='group'||$date['type']=='shop') {
            $date['order_id'] = $order_info['real_orderid'];
        }else{
            $date['order_id'] = $order_info['order_id'];
        }


        //除了提现，分佣，其他的收入要抽成
        if($order_info['order_type']!='withdraw' && $order_info['order_type']!='spread' && $order_info['order_type']!='merrecharge'){
            $date['total_money']= $order_info['total_money'];
            $date['system_take']= ($money*$percent/100);
            $date['money']= sprintf("%.2f",$money*(100-$percent)/100);
//            if($order_info['order_type']=='shop'&&$order_info['order_from']==1){
//                $date['money']+=$order_info['freight_charge'];
//            }
            $date['percent'] = $percent;
        }else{
            $date['percent'] = 0;
        }
        $date['income'] = 1;
        $date['use_time']= time();
        $date['desc']=  $desc;
    
        if( $percent != 100 && $date['money']!=0 && !M('Merchant')->where(array('mer_id'=>$mer_id))->setInc('money', $date['money'])  ){
            return array('error_code'=>true,'msg'=>'增加商家余额失败');
        }elseif($order_info['order_type']=='group'||$order_info['order_type']=='meal'||$order_info['order_type']=='shop'||$order_info['order_type']=='appoint'||$order_info['order_type']=='store'||$order_info['order_type']=='cash'||$order_info['order_type']=='wxapp'||$order_info['order_type']=='weidian'){
            if($order_info['order_type']=='cash'){
                $date['type'] = 'store';
            }
            M(ucfirst($date['type']) . '_order')->where(array('order_id' => $order_info['order_id']))->setField('is_pay_bill', 1);
        }
        $now_mer_money = M('Merchant')->field('money')->where(array('mer_id'=> $mer_id))->find();
        $date['now_mer_money'] = $now_mer_money['money'];

        if(!$this->add($date)){
            return array('error_code'=>true,'msg'=>$desc.' ，保存商家收入失败！');
        }else{
            if($order_info['order_type'] != 'withdraw'&& !empty($mer_user) && $mer_user['open_money_tempnews']) {
                $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
                $alias_name = $this->get_alias_c_name($order_info['order_type']);
                $store_name = '无';
                if (!empty($now_store)) {
                    $store_name = $now_store['name'];
                }
                $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $mer_user['openid'], 'first' => '收款成功，当前商家余额:'.$now_mer_money['money'] , 'keyword1' => $alias_name[$order_info['order_type']], 'keyword2' =>$money,'keyword3' =>$store_name, 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到商家中心商家余额中查看'));
            }
            if($order_info['order_type']!='merrecharge'){
                $this->merchant_card($mer_id,$order_info);
            }

            //解冻用户奖励金额 定制
//            if(C('config.free_recommend_awards_percent')>0){
//                // 在线支付金额 平台余额 商家余额 商家会员卡赠送余额
//                // 推广注册的用户 推广注册的商家
//                $award_money  = $order_info['payment_money'] + $order_info['balance_pay']+$order_info['merchant_balance']+$order_info['card_give_money'];
//                if($award_money > 0) {
//                    $spread_users[]  = $order_info['uid'];
//                    $User_model      = D('User');
//                    $Fenrun_model      = D('Fenrun');
//                    $now_user        = $User_model->get_user($order_info['uid']);
//                    $now_user_spread = D('User_spread')->field('`spread_openid`, `openid`')->where(array('openid' => $now_user['openid']))->find();
//                    if (!empty($now_user_spread)) {
//                        $spread_user = $User_model->get_user($now_user_spread['spread_openid'], 'openid');
//                        if ($spread_user && !in_array($spread_user['uid'], $spread_users)) {
//                            $free_money = round(($award_money) * C('config.free_recommend_awards_percent') / 100, 2);
//                            if ($free_money > 0 && $spread_user['frozen_award_money'] > 0) {
//                                $Fenrun_model->free_user_recommend_awards($spread_user['uid'], $free_money);
//                            }
//                        }
//                    }
//                    //如果该商家是被推荐注册的
//                    $now_merchant = D('Merchat')->get_info($mer_id);
//                    if ($now_merchant['uid']) {
//                        $now_merchant_user = $User_model->get_user($now_merchant['uid']);
//                        $now_user_spread   = D('User_spread')->field('`spread_openid`, `openid`')->where(array('openid' => $now_merchant_user['openid']))->find();
//                        if (!empty($now_user_spread)) {
//                            $spread_user = $User_model->get_user($now_user_spread['spread_openid'], 'openid');
//                            if ($spread_user && !in_array($spread_user['uid'], $spread_users)) {
//                                $free_money = round(($award_money) * C('config.free_recommend_awards_percent') / 100, 2);
//                                if ($free_money > 0 && $spread_user['frozen_award_money'] > 0) {
//                                    $Fenrun_model->free_user_recommend_awards($now_merchant['uid'], $award_money, 2);
//                                }
//                            }
//                        }
//                    }
//                }
//            }
            if($pay_for_system!=0){
                if($order_info['order_from']==1){
                    $desc_pay_for_system='商城订单快递配送转为平台配送，系统扣除平台配送费';
                }else{
                    $desc_pay_for_system= '快店平台配送，平台从商家余额中扣除订单的配送费';
                }
                $this->use_money($mer_id,$pay_for_system,$order_info['order_type'],$desc_pay_for_system,$order_info['real_orderid'],$mer_user);
            }
            return array('error_code'=>false,'msg'=>$desc.' ，保存商家收入成功！');
        }
    }

    public function merchant_card($mer_id,$order_info){
        if($card = D('Card_new')->get_card_by_mer_id($mer_id)){
            $uid = $order_info['uid'];
            $card_info = D('Card_new')->get_card_by_uid_and_mer_id($uid,$mer_id);
            if(empty($card_info)&&$card['auto_get_buy']){

                $res = D('Card_new')->auto_get($uid,$mer_id);
            }
            if($card_info['weixin_send'] && $order_info['pay_type']=='weixin' && $order_info['pay_money'] > $card_info['weixin_send_money'] ){
                //购买自动派券功能（派发的都是用户可以领取的功能）
                $coupon_list = explode(',',$card_info['weixin_send_couponlist']);

                $model = D('Card_new_coupon');
                foreach ($coupon_list as $item) {
                    $tmp = $model->had_pull($item,$uid);
                    switch($tmp['error_code']) {
                        case '0':
                            $error_msg = '领取成功';
                            break;
                        case '1':
                            $error_msg = '领取发生错误';
                            break;
                        case '2':
                            $error_msg = '优惠券已过期';
                            break;
                        case '3':
                            $error_msg = '优惠券已经领完了';
                            break;
                        case '4':
                            $error_msg = '只允许新用户领取';
                            break;
                        case '5':
                            $error_msg = '不能再领取了';
                            break;
                    }
                    $tmp['msg'] ='微信购买派发，'.$error_msg;
                    $data['uid'] = $uid;
                    $data['mer_id']  = $mer_id;
                    $data['coupon_id'] = $item;
                    $data['error_code']  =$tmp['error_code'];
                    $data['msg']  =$tmp['msg'];
                    $data['add_time']  =time();
                    M('Card_coupon_send_history')->add($data);
                }
            }

            if(!empty($card_info)){
                $data_score['card_id'] = $card_info['id'] ;
                $data_score['type'] = 1;
                $data_score['score_add'] = $card_info['support_score']*($order_info['payment_money']+$order_info['balance_pay']+$order_info['merchant_balance']+$order_info['card_give_money']);
                if( $data_score['score_add']>0){
                    $data_score['desc'] = '消费活动会员卡积分';
                    $res = D('Card_new')->add_user_money($mer_id,$uid,0,0,$data_score['score_add'],$data_score['desc'],$give_desc='');

                }
            }
        }

    }

    protected  function get_alias_c_name(){
        return array(
            'all'=>'选择分类',
            'group'=>C('config.group_alias_name'),
            'shop'=>C('config.shop_alias_name'),
            'meal'=>C('config.meal_alias_name'),
            'appoint'=>C('config.appoint_alias_name'),
            'waimai'=>'外卖',
            'store'=>'优惠买单',
            'cash'=>'到店支付',
            'weidian'=>'微店',
            'wxapp'=>'营销',
            'withdraw'=>'提现',
            'coupon'=>'优惠券',
            'withdraw'=>'提现',
            'activity'=>'平台活动',
            'spread'=>'商家推广'
        );
    }

    //减少余额
    public function use_money($mer_id,$money,$type,$desc,$order_id,$percent=0,$system_take = 0,$mer_user=array()){
        $date['mer_id']=$mer_id;
        $date['income'] = 2;
        $date['order_id'] = $order_id;
        if($percent){
            $date['percent'] = $percent;
            $date['system_take'] = $system_take;
        }
        $date['use_time']= time();
        $date['type']= $type;
        $date['desc']=  $desc;
        $date['money']=  $money;
        if(!M('Merchant')->where(array('mer_id'=>$mer_id))->setDec('money', $date['money'])){
            return array('error_code'=>true,'msg'=>$desc.'，使用失败！');
        }
        $now_mer_money = M('Merchant')->field('money')->where(array('mer_id'=> $mer_id))->find();
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        $mer_status = 1;
        if(C('config.open_mer_owe_money')){
            $now_mer_money['mch_owe_money'] = 0;
        }
        if($now_mer_money['money']<$now_mer_money['mch_owe_money']){
            M('Merchant')->where(array('mer_id'=>$mer_id))->setField('status',3);
            $mer_status = 3;
        }
        if(!empty($mer_user['openid'])){
            if($mer_status==3){
                $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_mer_money['openid'], 'first' => $desc.'，当前商家余额:'.$now_mer_money['money'].',您的商家状态为欠费，您的商家业务状态为禁止状态，请及时充值' , 'keyword1' => '商家余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到商家中心商家余额中查看'));
            }else{
                $model->sendTempMsg('OPENTM402026291', array('href' => '', 'wecha_id' => $now_mer_money['openid'], 'first' => $desc.'，当前商家余额:'.$now_mer_money['money'] , 'keyword1' => '商家余额使用', 'keyword2' =>$money,'keyword3' =>'', 'keyword4' => date("Y年m月d日 H:i"),'keyword5' =>$date['order_id'],'remark' => '请到商家中心商家余额中查看'));

            }
        }

        $date['now_mer_money'] = $now_mer_money['money'];
        if(!$this->add($date)){
            return array('error_code'=>true,'msg'=>$desc.'，保存失败！');
        }else{
            return array('error_code'=>false,'msg'=>$desc.'，保存成功！');
        }
    }

    //提现
    public function withdraw($mer_id,$name,$money,$remark){
        $date['mer_id']=$mer_id;
        if(C('config.company_pay_mer_percent')>0){
            $tmp_money  = $money;
            $money = floor ($tmp_money * (100-C('config.company_pay_mer_percent'))/100);
            //$date['percent'] = C('config.company_pay_mer_percent');
            $system_take = floor($tmp_money * (C('config.company_pay_mer_percent'))/100)/100;
        }
        $date['name']=$name;
        $date['money']=  $money;
        $date['remark']=  $remark;
        $date['withdraw_time'] = time();
        $res =M('Merchant_withdraw')->add($date);
        if(!$res){
            return array('error_code'=>true,'msg'=>'保存失败！');
        }else{
            //考虑兑现后减值
            $this->use_money($mer_id,$money/100,'withdraw','商户提现减少金额',$res,C('config.company_pay_mer_percent'),$system_take);
            return array('error_code'=>false,'msg'=>'保存成功！');
        }
    }

    //拒绝提现 增加余额
    public function reject($mer_id,$withdraw_id,$reason){
        $res = M('Merchant_withdraw')->where(array('id'=>$withdraw_id,'mer_id'=>$mer_id))->find();
        $date['status'] = 2;
        $date['remark'] = $reason;
        M('Merchant_withdraw')->where(array('id'=>$withdraw_id,'mer_id'=>$mer_id))->save($date);
        $desc = '驳回商户提现增加金额';
        $order_info['money'] = $res['money']/100;
        $order_info['order_type'] = 'withdraw';
        $order_info['mer_id'] = $mer_id;
        $order_info['order_id'] = $withdraw_id;
        return $this->add_money($mer_id,$desc,$order_info);
    }

    //同意提现改变状态
    public function agree($mer_id,$money,$withdraw_id,$remark,$is_online=false){
        $date['status'] = 1;
        $date['remark'] = $remark;
        $date['online'] = $is_online;
        $date['money'] = $money;

        $res = M('Merchant_withdraw')->where(array('id'=>$withdraw_id,'mer_id'=>$mer_id))->save($date);
        return $res;
    }

    //统计所有商家余额
    public function  get_all_mer_money(){
        return M('Merchant')->sum('money');
    }

    //获取提现列表
    public function get_withdraw_list($mer_id,$is_system = 0,$status=3,$time=''){
        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        $where['mer_id']= $mer_id;
        if($status!=3){
            $where['status']= $status;
        }
        if(!empty($time)){
            $where['_string'] = $time;
        }

        $count = M('Merchant_withdraw')->where($where)->count();
        $p = new Page($count, 20);
        $pagebar=$p->show();
        if($_GET['page']>$p->totalPage){
            return array('withdraw_list'=>array(),'page_num'=>$p->totalPage);
        }else{
            return array('withdraw_list'=>M('Merchant_withdraw')->where($where)->order('withdraw_time DESC')->limit($p->firstRow,$p->listRows)->select(),'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
        }

    }

    //获取商家收入列表
    public function get_income_list($mer_id,$is_system = 0,$where){
        if($_GET['page']){
            $where = $_SESSION['condition'];
        }

        if($is_system){
            import('@.ORG.system_page');
        }else{
            import('@.ORG.merchant_page');
        }
        $where['mer_id']=$mer_id;
        $count = M('Merchant_money_list')->where( $where)->count();
        unset($where['mer_id']);
        $where['l.mer_id']=$mer_id;
        if($where['store_id']){
            $where['l.store_id'] = $where['store_id'];
            unset($where['store_id']);
        }
        $p = new Page($count, 20);
        $pagebar=$p->show();
        if($_GET['page']>$p->totalPage){
            return array('income_list'=>array(),'total'=>0,'page_num'=>$p->totalPage);
        }else {
            $income_list = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id left join '.C('DB_PREFIX') . 'merchant_store ms ON l.store_id = ms.store_id ')->field('ms.name as store_name,l.order_id,l.desc,l.use_time,l.num,l.money,l.type,l.id,l.income,l.now_mer_money,l.system_take,l.percent,l.store_id,l.score,l.score_count')->where($where)->order('use_time DESC')->limit($p->firstRow, $p->listRows)->select();
            //print_r($income_list);exit;
            $total = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id ')->where(array('l.mer_id'=>$mer_id,'l.income'=>1))->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.money');
            $income_total = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id ')->where(array('l.mer_id'=>$mer_id,'l.income'=>1,'l.type'=>array('neq','merrecharge')))->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.money');
            $recharge_total = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id ')->where(array('l.mer_id'=>$mer_id,'l.income'=>1,'l.type'=>'merrecharge'))->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.money');
            $total_score = M('Merchant_money_list')->join('as l left join '.C('DB_PREFIX') . 'merchant m ON m.mer_id = l.mer_id ')->where($where)->order('use_time DESC')->limit($p->firstRow, $p->listRows)->sum('l.score');
            return array('income_list' => $income_list,'total'=>empty($total)?0:$total,'income_total'=>empty($income_total)?0:$income_total, 'total_score'=>empty($total_score)?0:$total_score,'recharge_total'=>empty($recharge_total)?0:$recharge_total,'pagebar' => $pagebar, 'page_num' => $p->totalPage);
        }
    }

    //获取所有商家提现列表 有提现的向前排列
    public function get_mer_withdraw_list($condition_merchant,$page_count=15){
        $database_merchant = M('Merchant');
        import('@.ORG.system_page');
        if(isset($condition_merchant)){
            $count_merchant = $database_merchant->join('as m left join '.C('DB_PREFIX').'merchant_withdraw AS w  ON m.mer_id = w.mer_id ')->where($condition_merchant)->count();

            foreach($condition_merchant as $k=>$v){
                if(strpos($k,'status')){
                    continue;
                }
                $condition_merchant['m.'.$k] = $v;
                unset($condition_merchant[$k]);
            }
            $p = new Page($count_merchant,$page_count);
            $mer_withdraw_list = $database_merchant->join('as m left join '.C('DB_PREFIX').'merchant_withdraw AS w  ON m.mer_id = w.mer_id ')
                ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.money as withdraw_money')
                ->where($condition_merchant)
                ->order('m.money DESC')
                ->limit($p->firstRow.','.$p->listRows)
                ->select();
        }else{

            foreach($condition_merchant as $k=>$v){
                $condition_merchant['m.'.$k] = $v;
                unset($condition_merchant[$k]);
            }
            $count_merchant = $database_merchant->where($condition_merchant)->count();
            $p = new Page($count_merchant,$page_count);
            $mer_withdraw_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(money) AS  withdraw_money,withdraw_time,status as withdraw_status FROM pigcms_merchant_withdraw WHERE status =0  GROUP BY mer_id) w ON m.mer_id = w.mer_id ')
                ->field('m.mer_id,m.phone,m.name,m.money,w.withdraw_time,w.withdraw_money')
                ->where($condition_merchant)
                ->order('m.money DESC')
                ->limit($p->firstRow.','.$p->listRows)
                ->select();
        }
        $pagebar = $p->show();
        return array('mer_withdraw_list'=>$mer_withdraw_list,'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
    }


    //抽成列表
    public function get_mer_percentage_list($condition_merchant,$page_count=15){
        $database_merchant = M('Merchant');
        $where['area_id'] = $condition_merchant['area_id'];
        $count_merchant = $database_merchant->where($where)->count();
        $time_condition = '';
        foreach($condition_merchant as $k=>$v){
            if($k!='_string'){
                $condition_merchant['m.'.$k] = $v;
            }else{
                $time_condition = 'WHERE '.$condition_merchant[$k];
            }
            unset($condition_merchant[$k]);
        }
        $extra_str ='';
        $extra_field ='';
        if(C('config.open_extra_price')==1){
            $tmp_time_condition  =  str_replace('use_time','add_time', $time_condition);
            $extra_str= 'LEFT JOIN (SELECT mer_id,SUM(score_count) as send_count FROM  '.C('DB_PREFIX').'merchant_score_send_log '.$tmp_time_condition.'  group by mer_id ) sl ON sl.mer_id = m.mer_id ';
            $extra_field = ' ,sl.send_count';
        }

        import('@.ORG.system_page');
        $p = new Page($count_merchant,$page_count);
        $mer_percentage_list = $database_merchant->join('as m left join '.'(SELECT  mer_id,SUM(system_take) AS  money ,SUM(score) as all_score FROM '.C('DB_PREFIX').'merchant_money_list '.$time_condition.' GROUP BY mer_id) w ON m.mer_id = w.mer_id  '.$extra_str)
            ->field('m.mer_id,m.name,m.phone,w.money,w.all_score'.$extra_field)
            ->where($condition_merchant)
            ->order('w.money DESC,w.all_score DESC')
            ->limit($p->firstRow.','.$p->listRows)
            ->select();

        $pagebar = $p->show();
        return array('mer_percentage_list'=>$mer_percentage_list,'pagebar'=>$pagebar,'page_num'=>$p->totalPage);
    }

    //统计所有商家余额
    public function  get_all_percent_money($condition_merchant){
        foreach($condition_merchant as $k=>$v){
            if($k!='_string'){
                $condition_merchant['m.'.$k] = $v;
                unset($condition_merchant[$k]);
            }
        }
        return M('Merchant_money_list')->join('AS l left join '.C('DB_PREFIX') .'merchant as m ON m.mer_id = l.mer_id')->where($condition_merchant)->sum('l.system_take');
    }

    //统计所有送出的积分
    public function  get_all_score($condition_merchant){
        $all_socre=  0;
        if(C('config.open_extra_price')==1){
            $tmp_condition = $condition_merchant;
            foreach($tmp_condition as $k=>$v){
                if($k!='_string'){
                    $tmp_condition['m.'.$k] = $v;
                    unset($tmp_condition[$k]);
                }else{
                    $tmp_condition[$k] = str_replace('use_time','add_time', $tmp_condition[$k]);
                }
            }
            $all_socre = M('Merchant')->join('as m LEFT JOIN '.C('DB_PREFIX').'merchant_score_send_log sl ON sl.mer_id = m.mer_id')->where($tmp_condition)->sum('score_count');
        }
        foreach($condition_merchant as $k=>$v){
            if($k!='_string'){
                $condition_merchant['m.'.$k] = $v;
                unset($condition_merchant[$k]);
            }
        }
        $all_socre += M('Merchant_money_list')->join('AS l left join '.C('DB_PREFIX') .'merchant as m ON m.mer_id = l.mer_id')->where($condition_merchant)->sum('l.score');
        return $all_socre;
    }
}