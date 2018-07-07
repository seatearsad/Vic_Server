<?php

class My_cardAction extends BaseAction
{
    public $now_user;
    public $mer_id;

    public function __construct()
    {
        parent::__construct();
        if (empty($this->user_session)) {
            if ($this->is_app_browser) {
                $location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
                $this->error_tips('请先进行登录！', U('Login/index', $location_param));
            } else {
                $location_param['referer'] = urlencode($_SERVER['REQUEST_URI']);
                redirect(U('Login/index', $location_param));
            }
        }
        $now_user = D('User')->get_user($this->user_session['uid']);
        $mer_id = $_GET['mer_id'];
        if (empty($now_user)) {
            session('user', null);
            $this->error_tips('未获取到您的帐号信息，请重新登录！', U('Login/index'));
        }
        $now_user['now_money'] = floatval($now_user['now_money']);
        $now_user['now_money_two'] = number_format(floatval($now_user['now_money']), 2);
        $this->now_user = $now_user;
        $this->assign('now_user', $now_user);
    }

    //商家信息
    private function get_merchant_info($mer_id)
    {
        $now_merchant = D('Merchant')->get_info($mer_id);
        if (empty($now_merchant)) {
            $this->error_tips('此商家不存在');
        }
        $this->assign('now_merchant', $now_merchant);
        return $now_merchant;
    }

    public function merchant_card()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        $now_card = M('Card_new')->where(array('mer_id' => $_GET['mer_id']))->find();
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->now_user['uid'], $this->mer_id);
        if (empty($card_info['id'])) {
            if ($now_card['self_get']) {
                $result = D('Card_new')->auto_get($this->now_user['uid'], $_GET['mer_id']);
                if (!$result['error_code']) {
                    $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->now_user['uid'], $_GET['mer_id']);
                } else {
                    $this->error_tips($result['msg']);
                }
            } else {
                $this->error_tips('您没有会员卡，请去领卡！');
            }

        } elseif ($card_info['status'] == 0) {
            $this->error_tips('您的会员卡不能使用！');
        }
        if(!empty($card_info['diybg'])){
            $card_info['style'] = 'background:url('.$card_info['diybg'].')center; background-size: 100%';
        }elseif(!empty($card_info['bg'])){
            $card_info['style'] ='background:url('.$card_info['bg'].')center; background-size: 100%';
        }
        $this->assign('card', $card_info);
        $this->display();
    }

    public function cardqrcode()
    {
        import('@.ORG.phpqrcode');
        QRcode::png($_SESSION['tmp_card_new'], false, 2, 8, 2);
    }


    public function merchant_store()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        //店铺列表
        $store_list = D('Merchant_store')->get_store_list_by_merId($now_merchant['mer_id']);
		if($store_list){
	        foreach($store_list as &$v){
				$v['url']	=	U('Merchant/shop',array('store_id'=>$v['store_id']));
	        }
        }
        $this->assign('store_list', $store_list);
        // dump($store_list);
        $this->display();
    }

    public function merchant_right()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->now_user['uid'], $_GET['mer_id']);
        $card_info['info'] = str_replace(PHP_EOL, '</br>', $card_info['info']);
        $this->assign('card_info', $card_info);
        $this->display();
    }

    //积分说明
    public function merchant_point()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->now_user['uid'], $_GET['mer_id']);
        $card_info['score_des'] = str_replace(PHP_EOL, '</br>', $card_info['score_des']);
        $this->assign('card_info', $card_info);
        $this->display();
    }

    public function merchant_prepay()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);

        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->now_user['uid'], $_GET['mer_id']);
        $card_info['recharge_suggest_array'] = explode(',', $card_info['recharge_suggest']);
        $this->assign('card_info', $card_info);
        $card_info['recharge_des'] = str_replace(PHP_EOL, '</br>', $card_info['recharge_des']);
        $now_merchant_card = M('Card_new')->where(array('mer_id' => $now_merchant['mer_id']))->find();
        $this->assign('now_merchant_card', $now_merchant_card);

        $this->display();
    }

    public function merchant_recharge()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);

        $money = floatval($_GET['money']);
        if (empty($money)) {
            $this->error_tips('请输入正确的充值金额');
        }

        $now_merchant_card = M('Card_new')->where(array('mer_id' => $now_merchant['mer_id']))->find();

        $data_card_new_recharge_order['mer_id'] = $now_merchant['mer_id'];
        $data_card_new_recharge_order['uid'] = $this->user_session['uid'];
        $data_card_new_recharge_order['money'] = $money;

        if ($now_merchant_card['recharge_count'] <= $money && $now_merchant_card['recharge_back_money'] > 0) {
            $data_card_new_recharge_order['give_money'] = intval($money / $now_merchant_card['recharge_count']) * $now_merchant_card['recharge_back_money'];
        }
        if ($now_merchant_card['recharge_count'] <= $money && $now_merchant_card['recharge_back_score'] > 0) {
            $data_card_new_recharge_order['give_score'] = intval($money / $now_merchant_card['recharge_count']) * $now_merchant_card['recharge_back_score'];
        }
        $data_card_new_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
        if ($order_id = M('Card_new_recharge_order')->data($data_card_new_recharge_order)->add()) {
            $pay_order_param = array(
                'business_type' => 'card_new_recharge',
                'business_id' => $order_id,
                'order_name' => '充值商家[' . $now_merchant['name'] . ']的会员卡余额',
                'uid' => $this->user_session['uid'],
                'total_money' => $money,
                'wx_cheap' => 0,
            );
            $plat_order_result = D('Plat_order')->add_order($pay_order_param);
            if ($plat_order_result['error_code']) {
                $this->error_tips($plat_order_result['error_msg']);
            } else {
                redirect(U('Pay/check', array('order_id' => $plat_order_result['order_id'], 'type' => 'plat')));
            }
        } else {
            $this->error_tips('订单创建失败，请重试。');
        }
    }

    public function merchant_coupon()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        $coupon_list = D('Card_new_coupon')->get_user_coupon_list($this->now_user['uid'], $_GET['mer_id']);
        $tmp = array();
        foreach ($coupon_list as $key => $v) {
            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
            } else {
                $tmp[$v['is_use']][$v['coupon_id']] = $v;
                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
//                switch($v['type']){
//                    case 'all':
//                        $url = $this->config['site_url'].'/wap.php';
//                        break;
//                    case 'group':
//                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Group&a=index';
//                        break;
//                    case 'meal':
//                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Meal_list&a=index';
//                        break;
//                    case 'appoint':
//                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Appoint&a=index';
//                        break;
//                    case 'shop':
//                        $url = $this->config['site_url'].'/wap.php?g=Wap&c=Shop&a=index';
//                        break;
//                }
//                 $coupon['url'] = $url;
            }
        }
        $this->assign('coupon_list', $tmp);
        $this->display();
    }

    public function merchant_personal()
    {
        //商家信息
        if (IS_POST) {
            if (M('User')->where(array('uid' => $this->now_user['uid']))->save($_POST)) {
                $this->success_tips('保存成功');
            } else {
                $this->error_tips('保存失败');
            }
        } else {
            $now_merchant = $this->get_merchant_info($_GET['mer_id']);
            $this->display();
        }
    }

    public function merchant_boundcard()
    {
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->now_user['uid'], $_GET['mer_id']);
        $this->assign('card_info', $card_info);
        if (IS_POST) {
            if (!empty($card_info['physical_id'])) {
                $this->error_tips('您已经绑定过实体卡了!');
            }
            if( D('Card_new')->add_pythsical_id($_POST['cardid'], $this->now_user['uid'], $card_info['id'])){
                $this->success_tips('保存成功');
            } else {
                $this->error_tips('保存失败');
            }

        }
        $this->display();
    }

    public function merchant_transrecord()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        $card_info = D('Card_new')->get_card_by_uid_and_mer_id($this->now_user['uid'], $_GET['mer_id']);
        $record = D('Card_new')->card_use_record($card_info['id'], 'all');

        $this->assign('record', $record['record']);
        $this->display();
    }

    public function merchant_coupon_list()
    {
        //商家信息
        $now_merchant = $this->get_merchant_info($_GET['mer_id']);
        $coupon_list = D('Card_new_coupon')->get_coupon_list_by_merid($_GET['mer_id']);
        if (!empty($this->now_user)) {
            $now_user_coupon = D('Card_new_coupon')->get_coupon_category_by_uid($this->now_user['uid']);
            foreach ($now_user_coupon as $v) {
                if (!empty($coupon_list[$v['coupon_id']])) {
                    $coupon_list[$v['coupon_id']]['selected'] = 1;
                }
            }
        }
        $category = array(
            'all' => '通用券',
            'group' => C('config.group_alias_name'),
            'meal' => C('config.meal_alias_name'),
            'appoint' => C('config.appoint_alias_name'),
            'shop' => C('config.shop_alias_name'),
            'store' => '优惠买单',
        );
        if(empty($this->config['appoint_page_row'])){
            unset($category['appoint']);
        }
        $platform = array('wap' => '移动网页', 'app' => 'App', 'weixin' => '微信');
        foreach ($coupon_list as $vv) {
            if($vv['had_pull']==$vv['num'] && $vv['last_time']<$_SERVER['REQUEST_TIME']-86400){
                continue;
            }
            $vv['platform'] = unserialize($vv['platform']);
            $tmp_platform = '';
            foreach ($vv['platform'] as $vt) {
                $tmp_platform .= $platform[$vt] . '/';
            }
            $vv['platform'] = substr($tmp_platform, 0, -1);
            $tmp[$vv['cate_name']][] = $vv;
        }
        foreach ($category as $k => $c) {
            if (empty($tmp[$k])) {
                $tmp[$k] = array();
                $category_tmp[$k]['count'] = 0;
            }else {
                $category_tmp[$k]['count'] = count($tmp[$k]);
            }
        }
        arsort($category_tmp);
        $this->assign('coupon_list', $tmp);
        $this->assign('category', $category);
        $max_category = array_keys($category_tmp);
        $this->assign('max_category', $max_category[0]);
        $this->assign('category_tmp', $category_tmp);
        $this->assign('isnew', D('User')->check_new($this->user_session['uid'],'all'));
        $this->display();
    }


    public function had_pull()
    {
        $coupon_id = $_POST['coupon_id'];
        $uid = $this->now_user['uid'];
        $model = D('Card_new_coupon');
        $has_get = $model->get_coupon_count_by_uid($coupon_id, $uid);
        $return['has_get'] = $has_get;
        if (empty($this->user_session)) {
            echo json_encode(array('error_code' => 6, 'msg' => '未登录'));
            die;
        }

        $result = $model->had_pull($coupon_id, $uid);
        $model->decrease_sku(0,1,$coupon_id);//网页领取完，微信卡券库存需要同步减少
        if ($result['error_code'] != 0) {
            switch ($result['error_code']) {
                case '1':
                    $error_msg = '领取失败';
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
            echo json_encode(array('error_code' => $result['error_code'], 'msg' => $error_msg));
            die;
        }

        echo json_encode(array('error_code' => 0, 'msg' => '领取成功', 'coupon' => $result['coupon']));
        die;

    }


}

?>