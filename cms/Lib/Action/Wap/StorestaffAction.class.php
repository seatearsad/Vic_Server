<?php

/*
 * 店员中心 Wap版
 *
 */

class StorestaffAction extends BaseAction
{

    protected $staff_session;
    protected $store;
    protected $database_store_staff;
    protected $language;
    public function __construct()
    {

        parent::__construct();
        $this->staff_session = session('staff_session');
        $this->staff_session = !empty($this->staff_session) ? unserialize($this->staff_session) : false;
        if (ACTION_NAME != 'login') {
            if (empty($this->staff_session) && $this->is_wexin_browser && !empty($_SESSION['openid'])) {
                $tmpstaff = D('Merchant_store_staff')->field(true)->where(array('openid' => trim($_SESSION['openid'])))->find();
                if (!empty($tmpstaff)) {
                    session('staff_session', serialize($tmpstaff));
                    $this->staff_session = $tmpstaff;
                }
            }

            if (empty($this->staff_session)) {
                redirect(U('Storestaff/login', array('referer' => urlencode('http://' . $_SERVER['HTTP_HOST'] . (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'])))));
                exit();
            } else {
                $this->assign('staff_session', $this->staff_session);
                $database_merchant_store = D('Merchant_store');
                $condition_merchant_store['store_id'] = $this->staff_session['store_id'];
                $this->store = $database_merchant_store->field(true)->where($condition_merchant_store)->find();
                if (empty($this->store)) {
                    $this->error_tips('店铺不存在！');
                }
            }
        }
        $this->language = isset($_COOKIE['language']) ? $_COOKIE['language'] : 'cn';
        $this->assign('language', $this->language);
        $this->assign('merchantstatic_path', $this->config['site_url'] . '/tpl/Merchant/static/');
    }

    public function login()
    {

        //设置cookie
        if(isset($_GET['lang'])) {
            setcookie('language', $_GET['lang']);
            $lang = $_GET['lang'] == 'cn' ? 'zh-cn' : 'en-us';
            setcookie('lang', $lang, $_SERVER['REQUEST_TIME'] + 72000000);
            Header("Location:/wap.php?g=Wap&c=Storestaff&a=login");
        }

        //if(isset($_COOKIE['language'])) {
        $this->assign('language', isset($_COOKIE['language']) ? $_COOKIE['language'] : 'cn');
        //}

        if (IS_POST) {
            /* if(md5($_POST['verify']) != $_SESSION['merchant_store_login_verify']){
             exit(json_encode(array('error'=>'1','msg'=>'验证码不正确！','dom_id'=>'verify')));
             } */

            $condition_store_staff['username'] = trim($_POST['account']);
            $database_store_staff = D('Merchant_store_staff');
            $now_staff = $database_store_staff->field(true)->where($condition_store_staff)->find();

            if (empty($now_staff)) {
                exit(json_encode(array('error' => 2, 'msg' => '帐号不存在！', 'dom_id' => 'account')));
            }
            $pwd = md5(trim($_POST['pwd']));
            if ($pwd != $now_staff['password']) {
                exit(json_encode(array('error' => 3, 'msg' => '密码错误！', 'dom_id' => 'pwd')));
            }
            $data_store_staff['id'] = $now_staff['id'];
            $data_store_staff['last_time'] = $_SERVER['REQUEST_TIME'];
            if ($database_store_staff->data($data_store_staff)->save()) {
                session('staff_session', serialize($now_staff));
                exit(json_encode(array('error' => 0, 'msg' => '登录成功,现在跳转~', 'dom_id' => 'account')));
            } else {
                exit(json_encode(array('error' => 6, 'msg' => '登录信息保存失败,请重试！', 'dom_id' => 'account')));
            }
        } else {
            if ($this->is_wexin_browser && !empty($_SESSION['openid'])) {
                $this->assign('openid', $_SESSION['openid']);
            }
            $referer = isset($_GET['referer']) ? htmlspecialchars_decode(urldecode($_GET['referer']), ENT_QUOTES) : '';
            $this->assign('refererUrl', $referer);
            $this->display();
        }
    }

    /*****绑定微信下次免登录********/
    public function freeLogin()
    {
        if (IS_POST && $this->is_wexin_browser && !empty($_SESSION['openid']) && is_array($this->staff_session)) {
            $openid = trim($_SESSION['openid']);
            $store_staffDb = D('Merchant_store_staff');
            $store_staffDb->where(array('openid' => $openid))->save(array('openid' => ''));
            $bindwx = $store_staffDb->where(array('id' => $this->staff_session['id'], 'store_id' => $this->staff_session['store_id']))->save(array('openid' => $openid));
            if ($bindwx) {
                exit(json_encode(array('error' => 0)));
            } else {
                exit(json_encode(array('error' => 1)));
            }
        }
        exit(json_encode(array('error' => 1)));
    }

    public function index()
    {
        if ($this->store['have_shop']) {
            redirect(U('Storestaff/shop_list'));
        } elseif ($this->store['have_meal']) {
            redirect(U('Storestaff/meal_list'));
        } elseif ($this->store['have_group']) {
            redirect(U('Storestaff/group_list'));
        } else {
            echo "该店铺没有开启{$this->config['group_alias_name']}，{$this->config['meal_alias_name']}，{$this->config['shop_alias_name']}中的任何一个";
            exit();
        }
        exit();
    }

    /* 团购相关 */

    protected function check_group()
    {
        if (empty($this->store['have_group'])) {
            $this->error_tips('您访问的店铺没有开通' . $this->config['group_alias_name'] . '功能！');
        }
    }

    public function appoint_list()
    {
        $store_id = $this->store['store_id'];

        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');
        $where['store_id'] = $store_id;

        $order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();

        $uidArr = array();
        foreach ($order_info as $v) {
            array_push($uidArr, $v['uid']);
        }

        $uidArr = array_unique($uidArr);

        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid' => array('in', $uidArr)))->select();


        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
        $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
        // dump($order_list);
        $this->assign('order_list', $order_list);
        if($this->language == 'en') {
            $this->display('appoint_list_en');
            return;
        }
        $this->display();
    }

    /*预约订单查找*/
    public function appoint_find()
    {
        if (IS_POST) {
            $database_order = D('Appoint_order');
            $database_user = D('User');
            $database_appoint = D('Appoint');
            $database_store = D('Merchant_store');

            $appoint_where['mer_id'] = $this->store['mer_id'];
            if ($_POST['find_type'] == 1 && strlen($_POST['find_value']) == 16) {
                $appoint_where['appoint_pass'] = $_POST['find_value'];
            } else {
                if ($_POST['find_type'] == 1) {
                    $appoint_where['appoint_pass'] = array('LIKE', '%' . $_POST['find_value'] . '%');
                } else if ($_POST['find_type'] == 2) {
                    $appoint_where['order_id'] = $_POST['find_value'];
                } else if ($_POST['find_type'] == 3) {
                    $appoint_where['appoint_id'] = $_POST['find_value'];
                } else if ($_POST['find_type'] == 4) {
                    $user_where['uid'] = $_POST['find_value'];
                } else if ($_POST['find_type'] == 5) {
                    $user_where['nickname'] = array('LIKE', '%' . $_POST['find_value'] . '%');
                } else if ($_POST['find_type'] == 6) {
                    $user_where['phone'] = array('LIKE', '%' . $_POST['find_value'] . '%');
                }
            }

            $order_info = $database_order->field(true)->where($appoint_where)->order('`order_id` DESC')->select();
            $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where($user_where)->select();
            $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
            $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
            $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
            if ($order_list) {
                foreach ($order_list as $key => $val) {
                    if ($_POST['find_type'] == 5) {
                        if (!isset($val['nickname'])) {
                            unset($order_list[$key]);
                            continue;
                        }
                    } else if ($_POST['find_type'] == 6) {
                        if (!isset($val['phone'])) {
                            unset($order_list[$key]);
                            continue;
                        }
                    }

                    $order_list[$key]['pay_time'] = date('Y-m-d H:i:s', $order_list[$key]['pay_time']);
                    $order_list[$key]['order_time'] = date('Y-m-d H:i:s', $order_list[$key]['order_time']);
                }
            }

            $return['list'] = array_values($order_list);
            $return['row_count'] = count($order_list);
            echo json_encode($return);
        } else {
            $this->display();
        }
    }

    /*预约订单详情*/
    public function appoint_edit(){
        $where['order_id'] = $_GET['order_id'];

        $database_order = D('Appoint_order');
        $database_user = D('User');
        $database_appoint = D('Appoint');
        $database_store = D('Merchant_store');

        $order_info = $database_order->field(true)->where($where)->order('`order_id` DESC')->select();
        $uidArr = array();
        foreach ($order_info as $v) {
            array_push($uidArr, $v['uid']);
        }

        $uidArr = array_unique($uidArr);
        $user_info = $database_user->field('`uid`, `phone`, `nickname`')->where(array('uid' => array('in', $uidArr)))->select();
        $appoint_info = $database_appoint->field('`appoint_id`, `appoint_name`, `appoint_type`, `appoint_price`')->select();
        $store_info = $database_store->field('`store_id`, `name`, `adress`')->select();
        $order_list = $this->formatOrderArray($order_info, $user_info, $appoint_info, $store_info);
        $now_order = $order_list[0];
        $now_order['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);

        $cue_info = unserialize($now_order['cue_field']);
        $cue_list = array();
        foreach ($cue_info as $key => $val) {
            if (!empty($cue_info[$key]['value'])) {
                $cue_list[$key]['name'] = $val['name'];
                $cue_list[$key]['value'] = $val['value'];
                $cue_list[$key]['type'] = $val['type'];
                if ($cue_info[$key]['type'] == 2) {
                    $cue_list[$key]['long'] = $val['long'];
                    $cue_list[$key]['lat'] = $val['lat'];
                    $cue_list[$key]['address'] = $val['address'];
                }
            }
        }
        $this->assign('cue_list', $cue_list);
        $this->assign('now_order', $now_order);

        if($this->language == 'en') {
            $this->display('appoint_edit_en');
            return;
        }

        $this->display();
    }

    /*验证预约服务*/
    public function appoint_verify()
    {
        $database_order = D('Appoint_order');
        $where['store_id'] = $this->store['store_id'];
        $where['order_id'] = $_GET['order_id'];
        $now_order = $database_order->field(true)->where($where)->find();

        if (empty($now_order)) {
            $this->error('当前订单不存在！');
        } else {
            $fields['store_id'] = $this->staff_session['store_id'];
            $fields['last_staff'] = $this->staff_session['name'];
            $fields['last_time'] = time();
            $fields['service_status'] = 1;
            if ($database_order->where($where)->data($fields)->save()) {
                //验证增加商家余额
                $order_info['order_id'] = $now_order['order_id'];
                $order_info['store_id'] = $now_order['store_id'];
                $order_info['order_type'] = 'appoint';
                $order_info['balance_pay'] = $now_order['balance_pay'];
                $order_info['score_deducte'] = $now_order['score_deducte'];
                $order_info['payment_money'] = $now_order['pay_money'];
                $order_info['is_own'] = $now_order['is_own'];
                $order_info['merchant_balance'] = $now_order['merchant_balance'];
                $order_info['score_used_count'] = $now_order['score_used_count'];
                $order_info['money'] = $order_info['balance_pay'] + $order_info['score_deducte'] + $order_info['payment_money'] + $order_info['merchant_balance'];

                if($now_order['product_id'] > 0){
                    $order_info['total_money'] = $now_order['product_price'];
                }else{
                    $order_info['total_money'] = $now_order['appoint_price'];
                }
                $order_info['payment_money'] = $now_order['pay_money'] + $now_order['pay_money'];
                $order_info['balance_pay'] = $now_order['balance_pay'] + $now_order['product_balance_pay'];
                $order_info['merchant_balance'] = $now_order['merchant_balance'] + $now_order['product_merchant_balance'];
                $order_info['card_give_money'] = $now_order['card_give_money'] + $now_order['product_card_give_money'];
                $order_info['uid'] = $now_order['uid'];

                $appoint_name = M('Appoint')->field('appoint_name')->where(array('appoint' => $now_order['appoint_id']))->find();
                D('Merchant_money_list')->add_money($this->store['mer_id'], '用户预约' . $appoint_name['appoint_name'] . '记入收入', $order_info);
                if($this->config['open_score_get_percent']==1){
                    $score_get = $this->config['score_get_percent']/100;
                }else{
                    $score_get = $this->config['user_score_get'];
                }

                D('User')->add_score($now_order['uid'], round(($order_info['balance_pay']+$order_info['payment_money']) * $score_get), '购买预约商品获得'.$this->config['score_name']);

                $this->success('验证成功！');
            } else {
                $this->error('验证失败！请重试。');
            }
        }
    }

    /* 格式化订单数据  */
    protected function formatOrderArray($order_info, $user_info, $appoint_info, $store_info)
    {
        if (!empty($user_info)) {
            $user_array = array();
            foreach ($user_info as $val) {
                $user_array[$val['uid']]['phone'] = $val['phone'];
                $user_array[$val['uid']]['nickname'] = $val['nickname'];
            }
        }
        if (!empty($appoint_info)) {
            $appoint_array = array();
            foreach ($appoint_info as $val) {
                $appoint_array[$val['appoint_id']]['appoint_name'] = $val['appoint_name'];
                $appoint_array[$val['appoint_id']]['appoint_type'] = $val['appoint_type'];
                $appoint_array[$val['appoint_id']]['appoint_price'] = $val['appoint_price'];
            }
        }
        if (!empty($store_info)) {
            $store_array = array();
            foreach ($store_info as $val) {
                $store_array[$val['store_id']]['store_name'] = $val['name'];
                $store_array[$val['store_id']]['store_adress'] = $val['adress'];
            }
        }
        if (!empty($order_info)) {
            foreach ($order_info as &$val) {
                $val['phone'] = $user_array[$val['uid']]['phone'];
                $val['nickname'] = $user_array[$val['uid']]['nickname'];
                $val['appoint_name'] = $appoint_array[$val['appoint_id']]['appoint_name'];
                $val['appoint_type'] = $appoint_array[$val['appoint_id']]['appoint_type'];
                $val['appoint_price'] = $appoint_array[$val['appoint_id']]['appoint_price'];
                $val['store_name'] = $store_array[$val['store_id']]['store_name'];
                $val['store_adress'] = $store_array[$val['store_id']]['store_adress'];
            }
        }
        return $order_info;
    }

    public function group_list()
    {
        $this->check_group();
        $store_id = $this->store['store_id'];
        $condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`store_id`='$store_id'";

        $condition_table = array(C('DB_PREFIX') . 'group' => 'g', C('DB_PREFIX') . 'group_order' => 'o', C('DB_PREFIX') . 'user' => 'u');
        $order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->select();
        $this->assign('order_list', $order_list);
        if($this->language == 'en') {
            $this->display('group_list_en');
            return;
        }
        $this->display();
    }

    public function group_find()
    {
        if (IS_POST) {
            $mer_id = $this->store['mer_id'];
            $condition_where = "`o`.`uid`=`u`.`uid` AND `o`.`group_id`=`g`.`group_id` AND `o`.`mer_id`='$mer_id'";
            $find_value = $_POST['find_value'];
            $store_id = $this->store['store_id'];
            if ($_POST['find_type'] == 1 && strlen($find_value) == 14) {
                $res = D('Group_pass_relation')->get_orderid_by_pass($find_value);
                if (!empty($res)) {
                    $condition_where .= " AND `o`.`order_id`=" . $res['order_id'];
                } else {
                    $condition_where .= " AND `o`.`group_pass`='$find_value'";
                }
                //$condition_where .= " AND `o`.`group_pass`='$find_value'";
            } else {
                $condition_where .= " AND `o`.`store_id`='$store_id'";
                if ($_POST['find_type'] == 1) {
                    $condition_where .= " AND `o`.`group_pass` like '$find_value%'";
                } else if ($_POST['find_type'] == 2) {
                    $condition_where .= " AND `o`.`express_id` like '$find_value%'";
                } else if ($_POST['find_type'] == 3) {
                    $condition_where .= " AND `o`.`real_orderid`='$find_value'";
                } else if ($_POST['find_type'] == 4) {
                    $condition_where .= " AND `o`.`group_id`='$find_value'";
                } else if ($_POST['find_type'] == 5) {
                    $condition_where .= " AND `o`.`uid`='$find_value'";
                } else if ($_POST['find_type'] == 6) {
                    $condition_where .= " AND `u`.`nickname` like '$find_value%'";
                } else if ($_POST['find_type'] == 7) {
                    $condition_where .= " AND `o`.`phone` like '$find_value%'";
                }
            }
            $condition_table = array(C('DB_PREFIX') . 'group' => 'g', C('DB_PREFIX') . 'group_order' => 'o', C('DB_PREFIX') . 'user' => 'u');
            $order_list = D('')->field('`o`.`phone` AS `group_phone`,`o`.*,`g`.`s_name`,`u`.`uid`,`u`.`nickname`,`u`.`phone`')->where($condition_where)->table($condition_table)->order('`o`.`add_time` DESC')->select();
            if ($order_list) {
                foreach ($order_list as $key => $value) {
                    $order_list[$key]['add_time'] = date('Y-m-d H:i:s', $value['add_time']);
                    $order_list[$key]['pay_time'] = date('Y-m-d H:i:s', $value['pay_time']);
                }
            }
            $return['list'] = $order_list;
            $return['row_count'] = count($order_list);
            echo json_encode($return);
        } else {
            $this->check_group();
            $this->display();
        }
    }

    public function group_verify()
    {
        $this->check_group();
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'], $_GET['order_id'], false);

        if(empty($now_order['paid'])){
            $this->error('此订单尚未支付！');
        }
        if($now_order['status']!=0){
            $this->error('此订单尚不是未消费！');
        }

        if (empty($now_order)) {
            $this->error('当前订单不存在！');
        } else if ($now_order['paid'] && $now_order['status'] == 0) {
            $condition_group_order['order_id'] = $now_order['order_id'];
            if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
                $data_group_order['third_id'] = $now_order['order_id'];
            }
            $data_group_order['status'] = '1';
            $data_group_order['store_id'] = $this->store['store_id'];
            $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
            $data_group_order['last_staff'] = $this->staff_session['name'];
            if ($database_group_order->where($condition_group_order)->data($data_group_order)->save()) {
                $this->group_notice($now_order, 1);
                // 				$sms_data = array('mer_id' => $now_order['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'group');
                // 				if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
                // 					$sms_data['uid'] = $now_order['uid'];
                // 					$sms_data['mobile'] = $now_order['phone'];
                // 					$sms_data['sendto'] = 'user';
                // 					$sms_data['content'] = '您购买 '.$now_order['order_name'].'的订单(订单号：' . $now_order['order_id'] . ')已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
                // 					Sms::sendSms($sms_data);
                // 				}
                // 				if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
                // 					$merchant = D('Merchant')->where(array('mer_id' => $now_order['mer_id']))->find();
                // 					$sms_data['uid'] = 0;
                // 					$sms_data['mobile'] = $merchant['phone'];
                // 					$sms_data['sendto'] = 'merchant';
                // 					$sms_data['content'] = '顾客购买的' . $now_order['order_name'] . '的订单(订单号：' . $now_order['order_id'] . '),已经完成了消费！';
                // 					Sms::sendSms($sms_data);
                // 				}

                //             	D('User')->add_score($now_order['uid'],floor($now_order['total_money']*C('config.user_score_get')),'购买 '.$now_order['order_name'].' 消费'.floatval($now_order['total_money']).'元 获得积分');
                // 				D('Userinfo')->add_score($now_order['uid'], $now_order['mer_id'], $now_order['total_money'], '购买 '.$now_order['order_name'].' 消费'.floatval($now_order['total_money']).'元 获得积分');
                D('Group_pass_relation')->change_refund_status($now_order['order_id'], 1);

                //验证增加商家余额
                $now_order['order_type'] = 'group';
                $now_order['store_id'] = $this->store['store_id'];
                $now_order['verify_all'] = 1;
                D('Merchant_money_list')->add_money($this->store['mer_id'], '用户购买' . $now_order['name'] . '记入收入', $now_order);
                $this->success_tips('验证成功！');
            } else {
                $this->error('验证失败！请重试。');
            }
        } else {
            $this->error('当前订单的状态并不是未消费。');
        }
    }

    protected function erroroutTips($msg, $ajax = false, $url = '')
    {
        if ($ajax) {
            echo json_encode(array('error' => 1, 'msg' => $msg));
        } else {
            $this->error($msg, $url);
        }
        exit();
    }

    public function group_pass_array()
    {
        $this->check_group();
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'], $_GET['order_id'], false);
        $now_order['coupon_price'] = D('Group_order')->get_coupon_info($now_order['order_id']);
        $un_pay = $now_order['total_money'] - $now_order['wx_cheap'] - $now_order['merchant_balance'] - $now_order['balance_pay'] - $now_order['score_deducte'] - $now_order['coupon_price'];
        $has_pay = $now_order['total_money'] - $un_pay;
        $pass_array = D('Group_pass_relation')->get_pass_array($now_order['order_id']);
        $un_use_num = D('Group_pass_relation')->get_pass_num($now_order['order_id'], 0);
        foreach ($pass_array as &$v) {
            $v['need_pay'] = $has_pay > $now_order['price'] ? 0 : $now_order['price'] - $has_pay;
            $has_pay = ($has_pay - $now_order['price']) > 0 ? $has_pay - $now_order['price'] : 0;
        }
        $this->assign('un_use_num', $un_use_num);
        $this->assign('pass_array', $pass_array);
        $this->assign('now_order', $now_order);
        if($this->language == 'en') {
            $this->display('group_pass_array_en');
            return;
        }
        $this->display();
    }

    public function group_array_verify()
    {
        $this->check_group();
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->get_order_detail_by_id_and_merId($this->store['mer_id'], $_POST['order_id'], false);
        $verify_all = false;
        if(empty($now_order['paid'])){
            $this->error('此订单尚未支付！');
        }
        if($now_order['status']!=0){
            $this->error('此订单尚不是未消费！');
        }
        if (empty($now_order)) {
            $this->error('当前订单不存在！');
        } else {
            $where['order_id'] = $_POST['order_id'];
            $where['group_pass'] = $_POST['group_pass'];
            $group_pass_rela = D('Group_pass_relation');
            $res = $group_pass_rela->where($where)->find();
            if (!empty($res)) {
                $date['status'] = 1;
                if ($group_pass_rela->where($where)->data($date)->save()) {
                    $count = $group_pass_rela->get_pass_num($where['order_id']);
                    $count += $group_pass_rela->get_pass_num($where['order_id'], 3);

                    if ($count == 0) {
                        if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
                            $data_group_order['third_id'] = $now_order['order_id'];
                        }
                        $data_group_order['status'] = '1';
                        $verify_all = true;
                    } else {
                        //$now_order['total_money'] = $now_order['price'];
                        $now_order['res'] = $res;
                    }

                    $data_group_order['store_id'] = $this->store['store_id'];
                    $condition_group_order['order_id'] = $where['order_id'];
                    $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
                    $data_group_order['last_staff'] = $this->staff_session['name'];
                    if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {

                        //验证增加商家余额
                        $now_order['order_type'] = 'group';
                        $now_order['store_id'] = $this->store['store_id'];
                        $now_order['verify_all'] = 0;
                        D('Merchant_money_list')->add_money($this->store['mer_id'], '验证团购订单' . $now_order['real_orderid'] . '的消费码</br>' . $_POST['group_pass'] . '记入收入', $now_order);
                        $this->group_notice($now_order, $verify_all);

                        $this->success('验证消费成功！');
                    } else {
                        $this->error('验证失败！请重试。');
                    }
                    //$this->success("验证消费成功！");
                } else {
                    $this->error("验证消费成功！");
                }
            } else {
                exit('此消费码不存在！');
            }
        }
    }

    /*     * *扫二维码验证*** */

    public function group_qrcode()
    {
        $group_pass = trim($_GET['id']);
        $ajax = isset($_GET['ajax']) ? intval($_GET['ajax']) : false;
        if (empty($this->store['have_group'])) {
            $this->erroroutTips('您访问的店铺没有开通' . $this->config['group_alias_name'] . '功能！', $ajax, U('Storestaff/group_list'));
        }
        $database_group_order = D('Group_order');
        $now_order = $database_group_order->where(array('mer_id' => $this->store['mer_id'], 'group_pass' => $group_pass))->find();
        if(empty($now_order['paid'])){
            $this->erroroutTips('此订单尚未支付！', $ajax, U('Storestaff/group_list'));
        }
        if($now_order['status']!=0){
            $this->erroroutTips('此订单尚不是未消费！', $ajax, U('Storestaff/group_list'));
        }
        if (empty($now_order)) {
            $this->erroroutTips('当前订单不存在！', $ajax, U('Storestaff/group_list'));
        } else if ($now_order['paid'] && $now_order['status'] == 0) {
            $condition_group_order['order_id'] = $now_order['order_id'];
            if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
                $data_group_order['third_id'] = $now_order['order_id'];
            }
            $data_group_order['status'] = '1';
            $data_group_order['store_id'] = $this->store['store_id'];
            $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
            $data_group_order['last_staff'] = $this->staff_session['name'];
            if ($database_group_order->where($condition_group_order)->data($data_group_order)->save()) {
                $this->group_notice($now_order, 1);
                //验证增加商家余额
                $now_order['order_type'] = 'group';
                $now_order['verify_all'] = 1;
                $now_order['store_id'] = $this->store['store_id'];
                D('Merchant_money_list')->add_money($this->store['mer_id'], '用户购买' . $now_order['name'] . '记入收入', $now_order);
                D('Group_pass_relation')->change_refund_status($now_order['order_id'], 1);
                if ($ajax) {
                    echo json_encode(array('error' => 0, 'msg' => 'OK'));
                } else {
                    $this->success('验证成功！', U('Storestaff/group_list'));
                }
                exit();
            } else {
                $this->erroroutTips('验证失败！请重试。', $ajax, U('Storestaff/group_list'));
            }
        } else {
            $this->erroroutTips('当前订单的状态并不是未消费。', $ajax, U('Storestaff/group_list'));
        }
    }

    /*     * *扫二维码验证*** */

    public function meal_qrcode()
    {
        $order_id = trim($_GET['id']);
        $ajax = isset($_GET['ajax']) ? intval($_GET['ajax']) : false;
        if (empty($this->store['have_meal'])) {
            $this->erroroutTips('您访问的店铺没有开通' . $this->config['meal_alias_name'] . '功能！', $ajax, U('Storestaff/meal_list'));
        }
        $store_id = intval($this->store['store_id']);
        if (!empty($order_id)) {
            if ($order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
                if ($order['status'] > 2) {
                    $this->erroroutTips('该订单已取消，您不能验证成已消费。', $ajax, U('Storestaff/meal_list'));
                    exit;
                }
                $data = array('store_uid' => $this->staff_session['id'], 'status' => 1, 'use_time' => time(), 'last_staff' => $this->staff_session['name']);
                if ($order['paid'] == 0) $this->erroroutTips('该订单是未支付状态，您不能验证成已消费。', $ajax, U('Storestaff/meal_list'));
                if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
                    $order['paid'] = 0;
                }
                if ($order['paid'] == 0) {
                    $notOffline = 1;
                    if ($this->config['pay_offline_open'] == 1) {
                        $now_merchant = D('Merchant')->get_info($order['mer_id']);
                        if ($now_merchant) {
                            $notOffline = ($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
                        }
                    }
                    if ($notOffline) {
                        $this->erroroutTips('该订单是未支付状态，您不能验证成已消费。', $ajax, U('Storestaff/meal_list'));
                        exit;
                    }
                }


                if ($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {
                    $data['third_id'] = $order['order_id'];
                    $data['pay_type'] = 'offline';
                    $data['paid'] = 1;
                    $data['pay_time'] = $_SERVER['REQUEST_TIME'];
                }
                $data['use_time'] = $_SERVER['REQUEST_TIME'];
                if (D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
                    if ($order['status'] == 0) {
                        if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find()) {
                            if ($supply['status'] < 2) {
                                D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->delete();
                            } else {
                                D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->save(array('status' => 5));
                            }
                        }
                        $this->meal_notice($order);
                    }
                    if ($ajax) {
                        echo json_encode(array('error' => 0, 'msg' => 'OK'));
                    } else {
                        $this->success("更新成功", U('Storestaff/meal_list'));
                    }
                } else {
                    $this->erroroutTips('验证失败！请重试。', $ajax, U('Storestaff/meal_list'));
                }
            } else {
                $this->erroroutTips('验证失败！请重试。', $ajax, U('Storestaff/meal_list'));
            }
        } else {
            if ($ajax) {
                echo json_encode(array('error' => 1, 'msg' => '订单ID不存在！'));
            } else {
                $this->redirect(U('Storestaff/meal_list'));
            }
        }
    }

    public function group_edit()
    {
        $this->check_group();
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'], $_GET['order_id'], false);
        if (empty($now_order)) {
            exit('此订单不存在！');
        }
        if (!empty($now_order['paid'])) {
            if ($now_order['is_pick_in_store']) {
                $now_order['paytypestr'] = "到店自提";
            } else {
                $now_order['paytypestr'] = D('Pay')->get_pay_name($now_order['pay_type']);
            }

            if (($now_order['pay_type'] == 'offline') && !empty($now_order['third_id']) && ($now_order['paid'] == 1)) {
                $now_order['paytypestr'] .= '<span style="color:green">&nbsp; 已支付</span>';
            } else if (($now_order['pay_type'] != 'offline') && ($now_order['paid'] == 1)) {
                $now_order['paytypestr'] .= '<span style="color:green">&nbsp; 已支付</span>';
            } else {
                $now_order['paytypestr'] .= '<span style="color:red">&nbsp; 未支付</span>';
            }
        } else {
            $now_order['paytypestr'] = '未支付';
        }
        $this->assign('now_order', $now_order);
        //if($now_order['tuan_type'] == 2 && $now_order['paid'] == 1){
        $express_list = D('Express')->get_express_list();
        $this->assign('express_list', $express_list);
        //}
        if($this->language == 'en') {
            $this->display('group_edit_en');
            return;
        }
        $this->display();
    }

    public function group_express()
    {
        $this->check_group();
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'], $_GET['order_id'], false);
        if (empty($now_order)) {
            $this->error('此订单不存在！');
        }
        if(empty($now_order['paid'])){
            $this->error('此订单尚未支付！');
        }
        if($now_order['status']!=0){
            $this->error('此订单尚不是未消费！');
        }

        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['express_type'] = $_POST['express_type'];
        $data_group_order['express_id'] = $_POST['express_id'];
        $data_group_order['last_staff'] = $this->staff_session['name'];
        if ($now_order['paid'] == 1 && $now_order['status'] == 0) {
            $data_group_order['status'] = 1;
            $data_group_order['use_time'] = $_SERVER['REQUEST_TIME'];
            $data_group_order['store_id'] = $this->store['store_id'];
        }
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->group_notice($now_order, 1);
            //验证增加商家余额
            $now_order['order_type'] = 'group';
            $now_order['verify_all'] = 1;
            $now_order['store_id'] = $this->store['store_id'];
            D('Merchant_money_list')->add_money($this->store['mer_id'], '用户购买' . $now_order['name'] . '记入收入', $now_order);
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！请重试。');
        }
    }

    public function group_remark()
    {
        $this->check_group();
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'], $_GET['order_id'], true, false);
        if (empty($now_order)) {
            $this->error('此订单不存在！');
        }
        if (empty($now_order['paid'])) {
            $this->error('此订单尚未支付！');
        }
        $condition_group_order['order_id'] = $now_order['order_id'];
        $data_group_order['merchant_remark'] = $_POST['merchant_remark'];
        if (D('Group_order')->where($condition_group_order)->data($data_group_order)->save()) {
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！请重试。');
        }
    }

    /* 检查是否开启订餐 */

    protected function check_meal()
    {
        if (empty($this->store['have_meal'])) {
            $this->error_tips('您访问的店铺没有开通' . $this->config['meal_alias_name'] . '功能！');
        }
    }

    public function meal_list()
    {
        $this->check_meal();

        $store_id = intval($this->store['store_id']);
        $where = array('mer_id' => $this->store['mer_id'], 'store_id' => $store_id);
        $stauts = isset($_GET['st']) ? intval(trim($_GET['st'])) : false;
        $ftype = isset($_GET['ft']) ? trim($_GET['ft']) : '';
        $fvalue = isset($_GET['fv']) ? trim(htmlspecialchars($_GET['fv'])) : '';
        if (empty($ftype) && ($stauts == 1)) {
            $where['paid'] = 1;
            $where['status'] = 0;
            $ftype = 'st';
        }
        switch ($ftype) {
            case 'oid': //订单id
                $fvalue && $where['order_id'] = array('like', "%$fvalue%");
                break;
            case 'xm':  //下单人姓名
                $fvalue && $where['name'] = array('like', "%$fvalue%");
                break;
            case 'dh':  //下单人电话
                $fvalue && $where['phone'] = array('like', "%$fvalue%");
                break;
            case 'mps': //消费码
                $fvalue && $where['meal_pass'] = array('like', "%$fvalue%");
                break;
            default:
                break;
        }
        $this->assign('ftype', $ftype);
        $this->assign('fvalue', $fvalue);

        $Meal_orderDb = D("Meal_order");
        $count = $Meal_orderDb->where($where)->count();
        import('@.ORG.wap_group_page');
        $p = new Page($count, 20, 'p');

        $notOffline = 1;
        $pay_offline_open = $this->config['pay_offline_open'];
        if ($pay_offline_open == 1) {
            $now_merchant = D('Merchant')->get_info($mer_id);
            if ($now_merchant) {
                $notOffline = ($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
            }
        }

        $list = $Meal_orderDb->where($where)->order("order_id DESC")->limit($p->firstRow . ',' . $p->listRows)->select();
        foreach ($list as &$l) {
            $l['info'] = unserialize($l['info']);
            if ($notOffline && $l['paid'] == 0) $l['is_confirm'] = 2;
        }


        $this->assign('order_list', $list);
        $this->assign('now_store', $this->store);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        if($this->language == 'en') {
            $this->display('meal_list_en');
            return;
        }
        $this->display();
    }

    public function meal_edit()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $store_id = intval($this->store['store_id']);
        if (IS_POST) {

            if (isset($_POST['status'])) {
                $status = intval($_POST['status']);
                if ($order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
                    if ($order['status'] > 2) {
                        $this->error_tips('该订单已取消，您不能验证成已消费。', U('Storestaff/meal_list'));
                        exit;
                    }
                    if ($order['paid'] == 0) $this->error_tips('该订单是未支付状态，您不能验证成已消费。', U('Storestaff/meal_list'));
                    $data = array('store_uid' => $this->staff_session['id'], 'status' => $status, 'use_time' => time(), 'last_staff' => $this->staff_session['name']);
                    if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
                        $order['paid'] = 0;
                    }
                    if ($order['paid'] == 0) {
                        $notOffline = 1;
                        if ($this->config['pay_offline_open'] == 1) {
                            $now_merchant = D('Merchant')->get_info($order['mer_id']);
                            if ($now_merchant) {
                                $notOffline = ($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
                            }
                        }
                        if ($notOffline) {
                            $this->error_tips('该订单是未支付状态，您不能验证成已消费。', U('Storestaff/meal_list'));
                            exit;
                        }
                    }
                    if ($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
                        $data['third_id'] = $order['order_id'];
                        $data['pay_type'] = 'offline';
                        $data['paid'] = 1;
                        $data['pay_time'] = $_SERVER['REQUEST_TIME'];
                    }
                    $data['use_time'] = $_SERVER['REQUEST_TIME'];
                    if (D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
                        if ($status && $order['status'] == 0) {
                            if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find()) {
                                if ($supply['status'] < 2) {
                                    D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->delete();
                                } else {
                                    D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 0))->save(array('status' => 5));
                                }
                            }
                            $this->meal_notice($order);
                        }

                        $this->success_tips('更新成功', U('Storestaff/meal_edit', array('order_id' => $order['order_id'])));
                    } else {
                        $this->error_tips('更新失败，稍后再试');
                    }
                } else {
                    $this->error_tips('不合法的请求');
                }
            } else {
                $this->redirect(U('Storestaff/meal_list'));
            }
        } else {
            $order = D("Meal_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find();
            $order['info'] = unserialize($order['info']);
            if ($order['store_uid']) {
                $staff = D("Merchant_store_staff")->where(array('id' => $order['store_uid']))->find();
                $order['store_uname'] = $staff['name'];
            }
            if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
                $order['paid'] = 0;
            }
            if (!empty($order['paid'])) {
                $order['paytypestr'] = D('Pay')->get_pay_name($order['pay_type']);
                if (($order['pay_type'] == 'offline') && !empty($order['third_id']) && ($order['paid'] == 1)) {
                    $order['paytypestr'] .= '<span style="color:green">&nbsp; 已支付</span>';
                } else if (($order['pay_type'] != 'offline') && ($order['paid'] == 1)) {
                    $order['paytypestr'] .= '<span style="color:green">&nbsp; 已支付</span>';
                } else {
                    $order['paytypestr'] .= '<span style="color:red">&nbsp; 未支付</span>';
                }
            } else {
                $order['paytypestr'] = '未支付';
            }
            $this->assign('order', $order);
            if($this->language == 'en') {
                $this->display('mall_order_detail_en');
                return;
            }
            $this->display();
        }
    }

    public function logout()
    {
        session('staff_session', null);
        redirect(U('Storestaff/login'));
    }

    private function group_notice($order, $verify_all)
    {
        if ($verify_all) {
            //积分
            $now_user = M('User')->where(array('uid' => $order['uid']))->find();
            if(C('config.open_extra_price')==1){
                $order['order_type'] = 'group';
                $score = D('Percent_rate')->get_extra_money($order);
                if($score>0){
                    D('User')->add_score($order['uid'], floor($score),'购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.C('config.extra_price_alias_name'));
                }
            }else {
                D('User')->add_score($order['uid'], round($order['total_money'] * $this->config['score_get']), '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得'.$this->config['score_name']);
                D('Scroll_msg')->add_msg('group',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).'购买 ' . $order['order_name'] . '成功并消费获得'.$this->config['score_name']);
                D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['total_money'], '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得积分');
            }
            //商家推广分佣

            D('Merchant_spread')->add_spread_list($order, $now_user, 'group', $now_user['nickname'] . '购买'.C('config.group_alias_name').'获得佣金');
        }

        //短信
        $sms_data = array('mer_id' => $order['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'group');
        if ($this->config['sms_group_finish_order'] == 1 || $this->config['sms_group_finish_order'] == 3) {
            $sms_data['uid'] = $order['uid'];
            $sms_data['mobile'] = $order['phone'];
            $sms_data['sendto'] = 'user';
            if (empty($order['res'])) {
                $sms_data['content'] = '您购买 ' . $order['order_name'] . '的订单(订单号：' . $order['real_orderid'] . ')已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
            } else {
                $sms_data['content'] = '您购买 ' . $order['order_name'] . '的订单(消费码：' . $order['res']['group_pass'] . ')已经完成了消费，如有任何疑意，请您及时联系我们！';
            }
            Sms::sendSms($sms_data);
        }

        if ($this->config['sms_group_finish_order'] == 2 || $this->config['sms_group_finish_order'] == 3) {
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $this->store['phone'];
            $sms_data['sendto'] = 'merchant';
            $sms_data['content'] = '顾客购买的' . $order['order_name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
            Sms::sendSms($sms_data);
        }

        //小票打印
        $msg = ArrayToStr::array_to_str($order['order_id'], 'group_order');
        $op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
        $op->printit($this->store['mer_id'], $this->store['store_id'], $msg, 2);
    }

    private function meal_notice($order)
    {
        //验证增加商家余额
        $order['order_type'] = 'meal';
        $info = unserialize($order['info']);
        $info_str = '';
        foreach ($info as $v) {
            $info_str .= $v['name'] . ':' . $v['price'] . '*' . $v['num'] . '</br>';
        }

        D('Merchant_money_list')->add_money($this->store['mer_id'], '用户购买' . $info_str . '记入收入', $order);

        //商家推广分佣
        $now_user = M('User')->where(array('uid' => $order['uid']))->find();
        D('Merchant_spread')->add_spread_list($order, $now_user, 'meal', $now_user['nickname'] . '用户购买餐饮商品获得佣金');

        //积分
        if(C('config.open_extra_price')==1){
            $score = D('Percent_rate')->get_extra_money($order);
            if($score>0){
                D('User')->add_score($order['uid'], floor($score),'在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.extra_price_alias_name'));
            }
        }else {
            //积分
            D('User')->add_score($order['uid'], floor($order['price'] * $this->config['score_get']), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
            D('Scroll_msg')->add_msg('meal',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.$this->store['name'] . ' 中消费获得'.$this->config['score_name']);

            D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
        }
        //短信
        $sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'food');
        if ($this->config['sms_finish_order'] == 1 || $this->config['sms_finish_order'] == 3) {
            if (empty($order['phone'])) {
                $user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
                $order['phone'] = $user['phone'];
            }
            $sms_data['uid'] = $order['uid'];
            $sms_data['mobile'] = $order['phone'];
            $sms_data['sendto'] = 'user';
            $sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $order['order_id'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
            Sms::sendSms($sms_data);
        }
        if ($this->config['sms_finish_order'] == 2 || $this->config['sms_finish_order'] == 3) {
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $this->store['phone'];
            $sms_data['sendto'] = 'merchant';
            $sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['order_id'] . '),已经完成了消费！';
            Sms::sendSms($sms_data);
        }

        //小票打印
        $msg = ArrayToStr::array_to_str($order['order_id']);
        $op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
        $op->printit($this->store['mer_id'], $order['store_id'], $msg, 2);


        $str_format = ArrayToStr::print_format($order['order_id']);
        foreach ($str_format as $print_id => $print_msg) {
            $print_id && $op->printit($this->store['mer_id'], $order['store_id'], $print_msg, 2, $print_id);
        }

    }

    public function check_confirm()
    {
        $database = D('Meal_order');
        $order_id = $condition['order_id'] = intval($_POST['order_id']);
        $condition['store_id'] = $this->store['store_id'];
        $order = $database->field(true)->where($condition)->find();
        if (empty($order)) {
            $this->error('订单不存在！');
        }
        if ($order['status'] > 2) $this->error('订单已取消');
        if ($order['paid'] == 0) {
            $this->error('此单未支付，不能接单！');
        }
        $notOffline = 1;
        $pay_offline_open = $this->config['pay_offline_open'];
        if ($pay_offline_open == 1) {
            $now_merchant = D('Merchant')->get_info($mer_id);
            if ($now_merchant) {
                $notOffline = ($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
            }
        }
        if ($notOffline && $order['paid'] == 0) {
            $this->error('此单未支付，不能接单！');
        }

        if ($order['meal_type'] == 1) {
            $deliverCondition['store_id'] = $this->store['store_id'];
            $deliverCondition['mer_id'] = $this->store['mer_id'];
            //             if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
            $old = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 0))->find();
            if (empty($old)) {
                $deliverType = $deliver['type'];
                $address_id = $order['address_id'];
                $address_info = D('User_adress')->where(array('adress_id' => $address_id))->find();

                $supply['order_id'] = $order_id;
                $supply['paid'] = $order['paid'];
                $supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
                $supply['pay_type'] = $order['pay_type'];
                $supply['money'] = $order['price'];
                $supply['deliver_cash'] = floatval($order['price']+$order['extra_price'] - $order['card_price'] - $order['merchant_balance'] - $order['balance_pay'] - $order['payment_money'] - $order['score_deducte'] - $order['coupon_price']);
                $supply['deliver_cash'] = max(0, $supply['deliver_cash']);
                $supply['store_id'] = $this->store['store_id'];
                $supply['store_name'] = $this->store['name'];
                $supply['mer_id'] = $this->store['mer_id'];
                $supply['from_site'] = $this->store['adress'];
                $supply['from_lnt'] = $this->store['long'];
                $supply['from_lat'] = $this->store['lat'];
                if ($address_info) {
                    $supply['aim_site'] = $address_info['adress'] . ' ' . $address_info['detail'];
                    $supply['aim_lnt'] = $address_info['longitude'];
                    $supply['aim_lat'] = $address_info['latitude'];
                    $supply['name'] = $address_info['name'];
                    $supply['phone'] = $address_info['phone'];
                }
                $supply['status'] = 1;
                $supply['type'] = $deliverType;
                $supply['item'] = 0;
                $supply['create_time'] = $_SERVER['REQUEST_TIME'];
                $supply['start_time'] = $_SERVER['REQUEST_TIME'];
                $supply['appoint_time'] = $_SERVER['REQUEST_TIME'];
                if ($addResult = D('Deliver_supply')->add($supply)) {
                } else {
                    $this->error('接单失败');
                }
            }
            //             } else {
            //                 $this->error('您还没有接入配送机制');
            //             }
        }

        $data['is_confirm'] = 1;
        $data['order_status'] = 3;
        $data['store_uid'] = $this->staff_session['id'];
        $data['last_staff'] = $this->staff_session['name'];
        if ($database->where($condition)->save($data)) {
            $this->success('已接单');
        } else {
            $this->error('接单失败');
        }
    }

    public function shop_list()
    {
        //修改上下班状态
        if($_GET['action'] == 'changeWorkstatus') {
            D('Merchant_store_staff')->where(['id' => $this->staff_session['id']])->save(['work_status' => $_GET['type']]);
            $this->staff_session['work_status'] = $_GET['type'];
            session('staff_session', serialize($this->staff_session));
            exit;
        }

        $store_id = intval($this->store['store_id']);
        $where = array('mer_id' => $this->store['mer_id'], 'store_id' => $store_id);
        $stauts = isset($_GET['st']) ? intval(trim($_GET['st'])) : false;
        $ftype = isset($_GET['ft']) ? trim($_GET['ft']) : '';
        $fvalue = isset($_GET['fv']) ? trim(htmlspecialchars($_GET['fv'])) : '';
        $where['paid'] = 1;
        if (empty($ftype) && ($stauts == 1)) {
            $where['status'] = 0;
            $ftype = 'st';
        }
        switch ($ftype) {
            case 'oid': //订单id
                $fvalue && $where['real_orderid'] = array('like', "%$fvalue%");
                break;
            case 'xm':  //下单人姓名
                $fvalue && $where['name'] = array('like', "%$fvalue%");
                break;
            case 'dh':  //下单人电话
                $fvalue && $where['phone'] = array('like', "%$fvalue%");
                break;
            case 'mps': //消费码
                $fvalue && $where['orderid'] = $fvalue;
                break;
            default:
                break;
        }
        $this->assign('ftype', $ftype);
        $this->assign('fvalue', $fvalue);

        $where['mer_id'] = $this->store['mer_id'];
        $where['store_id'] = $store_id;
        $this->assign(D("Shop_order")->get_order_list($where, 'paid DESC, order_id DESC', 4));

        $shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
        $shop = array_merge($this->store, $shop);
        $this->assign('now_store', $shop);
        if($this->language == 'en') {
            $this->display('shop_list_en');
            return;
        }
        $this->display();
    }

    public function shop_qrcode()
    {
        $order_id = trim($_GET['id']);
        $ajax = isset($_GET['ajax']) ? intval($_GET['ajax']) : false;
        if (empty($this->store['have_shop'])) {
            $this->erroroutTips('您访问的店铺没有开通' . $this->config['shop_alias_name'] . '功能！', $ajax, U('Storestaff/shop_list'));
        }
        $store_id = intval($this->store['store_id']);

        $status = 2;
        if ($order = D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
            if ($order['status'] == 4 || $order['status'] == 5) {
                $this->erroroutTips('该订单已取消，您不能验证成已消费。', $ajax, U('Storestaff/shop_list'));
                exit;
            }

            if ($order['is_refund']) {
                $this->erroroutTips('用户正在退款中~！', $ajax, U('Storestaff/shop_list'));
                exit;
            }

            if ($order['status'] > 0 && $order['status'] != 7) {
                $this->erroroutTips('该订单已经验证过了。', $ajax, U('Storestaff/shop_list'));
                exit;
            }
            if ($order['paid'] == 0) $this->erroroutTips('该订单是未支付状态，您不能验证成已消费。', $ajax, U('Storestaff/shop_list'));
            $data = array('status' => $status, 'order_status' => 6, 'use_time' => time(), 'last_staff' => $this->staff_session['name']);
            if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
                $order['paid'] = 0;
            }
            if ($order['paid'] == 0) {
                $notOffline = 1;
                if ($this->config['pay_offline_open'] == 1) {
                    $now_merchant = D('Merchant')->get_info($order['mer_id']);
                    if ($now_merchant) {
                        $notOffline = ($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
                    }
                }
                if ($notOffline) {
                    $this->erroroutTips('该订单是未支付状态，您不能验证成已消费。', $ajax, U('Storestaff/shop_list'));
                    exit;
                }
            }
            if ($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
                $data['third_id'] = $order['order_id'];
                $data['pay_type'] = 'offline';
                $data['paid'] = 1;
            }

            if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
                if ($status == 2) {
                    D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
                    if ($order['status'] < 2) {
                        $phones = explode(' ', $this->store['phone']);
                        D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
                        if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find()) {
                            if ($supply['status'] < 2) {
                                D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->delete();
                            } else {
                                D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 5));
                            }
                        }
                    }
                    if ($order['status'] != 2 && $order['status'] != 3) {
                        $this->shop_notice($order);
                    }
                }
                $this->success_tips('更新成功', $ajax, U('Storestaff/shop_list', array('order_id' => $order['order_id'])));
            } else {
                $this->erroroutTips('更新失败，稍后再试', $ajax, U('Storestaff/shop_list'));
            }
        } else {
            $this->erroroutTips('不合法的请求', $ajax, U('Storestaff/shop_list'));
        }
    }

    public function shop_edit()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $store_id = intval($this->store['store_id']);
        if (IS_POST) {
            if (isset($_POST['status'])) {
                $status = intval($_POST['status']);
                if ($order = D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->find()) {
                    if ($order['status'] == 4 || $order['status'] == 5) {
                        $this->error_tips('订单已取消，不能再做其他操作！');
                        exit;
                    }
                    if ($order['is_refund']) {
                        $this->error_tips('用户正在退款中~！');
                        exit;
                    }
                    $data = array('status' => $status, 'order_status' => 6, 'cancel_type' => 1, 'use_time' => time(), 'last_staff' => $this->staff_session['name']);//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
                    if ($order['is_pick_in_store'] == 3) {

                        $express_id = isset($_POST['express_id']) ? intval($_POST['express_id']) : 0;
                        $express_number = isset($_POST['express_number']) ? htmlspecialchars($_POST['express_number']) : 0;
                        if ($status == 2 && (empty($express_id) || empty($express_number))) $this->error_tips('快递公司和快递单号都不能为空。');
                        if ($order['paid'] == 0) {
                            $this->error_tips('未付款的订单只能进行取消操作。');
                            exit;
                        }
                        if (empty($order['third_id']) && $order['pay_type'] == 'offline') {
                            $order['paid'] = 0;
                        }
                        if ($order['paid'] == 0) {
                            $notOffline = 1;
                            if ($this->config['pay_offline_open'] == 1) {
                                $now_merchant = D('Merchant')->get_info($order['mer_id']);
                                if ($now_merchant) {
                                    $notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
                                }
                            }
                            if ($notOffline) {
                                $this->error_tips('不支持线下支付。');
                                exit;
                            }
                        }
                        $data['express_id'] = $express_id;
                        $data['express_number'] = $express_number;
                        $data['last_staff'] = $this->staff_session['name'];
                        $data['use_time'] = $_SERVER['REQUEST_TIME'];
                        $data['last_time'] = $_SERVER['REQUEST_TIME'];
                        if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
                            $phones = explode(' ', $this->store['phone']);
                            if ($status == 1) {
                                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
                            } elseif ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {
                                $this->shop_notice($order);
                                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
                            } elseif ($status == 4) {
                                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
                            } elseif ($status == 5) {
                                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
                            }
                            $this->success_tips('更新成功', U('Storestaff/shop_edit', array('order_id' => $order['order_id'])));
                        } else {
                            $this->error_tips('更新失败，稍后再试');
                        }

                    } else {
                        if ($order['status'] == 4 || $order['status'] == 5) {
                            $this->error_tips('该订单已取消，您不能验证成已消费。', $ajax,U('Storestaff/shop_list'));
                            exit;
                        }

                        $supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();

                        if ($order['is_pick_in_store'] == 0 && $status == 2 && $supply && $supply['uid']) {//平台配送，当配送员接单后店员就不能把订单修改成已消费状态
                            $this->error_tips('您不能将该订单改成已消费状态。', U('Storestaff/shop_list'));
                        }

                        if ($order['paid'] == 0) $this->error_tips('该订单是未支付状态，您不能验证成已消费。', U('Storestaff/shop_list'));

                        if(empty($order['third_id']) && $order['pay_type'] == 'offline'){
                            $order['paid'] = 0;
                        }
                        if ($order['paid'] == 0) {
                            $notOffline = 1;
                            if ($this->config['pay_offline_open'] == 1) {
                                $now_merchant = D('Merchant')->get_info($order['mer_id']);
                                if ($now_merchant) {
                                    $notOffline =($now_merchant['is_close_offline'] == 0 && $now_merchant['is_offline'] == 1) ? 0 : 1;
                                }
                            }
                            if ($notOffline) {
                                $this->error_tips('该订单是未支付状态，您不能验证成已消费。', $ajax,U('Storestaff/shop_list'));
                                exit;
                            }
                        }
                        if ($order['paid'] != 1 || ($order['pay_type'] == 'offline' && empty($order['third_id']))) {//将未支付的订单，由店员改成已消费，其订单状态则修改成线下已支付！
                            $data['third_id'] = $order['order_id'];
                            $data['pay_type'] = 'offline';
                            $data['paid'] = 1;
                        }

                        if (D("Shop_order")->where(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id))->save($data)) {
                            if ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {
                                D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 4));
                                $phones = explode(' ', $this->store['phone']);
                                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
                                if ($supply) {
                                    if ($supply['status'] < 2) {
                                        D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->delete();
                                    } else {
                                        D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 5));
                                    }
                                }
                                $this->shop_notice($order);
                            }
                            $this->success_tips('更新成功', U('Storestaff/shop_edit',array('order_id' => $order['order_id'])));
                        } else {
                            $this->error_tips('更新失败，稍后再试');
                        }
                    }
                } else {
                    $this->error_tips('不合法的请求');
                }
            } else {
                $this->redirect(U('Storestaff/shop_list'));
            }
        } else {
            $sure = true;
            $order = D("Shop_order")->get_order_detail(array('mer_id' => $this->store['mer_id'], 'order_id' => $order_id, 'store_id' => $store_id));
            if ($order['is_pick_in_store'] == 3) {
                $express_list = D('Express')->get_express_list();
                $this->assign('express_list',$express_list);
            } elseif ($order['is_pick_in_store'] == 0) {
                $supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order['order_id'], 'item' => 2))->find();
                if (isset($supply['uid']) && $supply['uid']) {
                    $sure = false;
                    if($supply['status'] == 2 || $supply['status'] == 3){
                        $t_deliver = D('Deliver_user')->field(true)->where(array('uid'=>$supply['uid']))->find();
                        $this->assign('deliver',$t_deliver);
                    }
                }
                $this->assign('supply',$supply);
            }
            //add garfunkel
            $tax_price = 0;
            $deposit_price = 0;
            $lang = $this->language == 'cn' ? 'zh-cn' : 'en-us';
            foreach ($order['info'] as $k => $v){
                $g_id = $v['goods_id'];
                $goods = D('Shop_goods')->get_goods_by_id($g_id);
                $order['info'][$k]['unit'] = lang_substr($goods['unit'],$lang);
                $order['info'][$k]['name'] = lang_substr($goods['name'],$lang);

                $tax_price += $v['price'] * $goods['tax_num']/100 * $v['num'];
                $deposit_price += $goods['deposit_price'] * $v['num'];
                //garfunkel 显示规格和分类
                $spec_desc = '';
                $spec_ids = explode('_',$v['spec_id']);
                foreach ($spec_ids as $vv){
                    $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                    $spec_desc = $spec_desc == '' ? lang_substr($spec['name'],$lang) : $spec_desc.','.lang_substr($spec['name'],$lang);
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
                    $name = lang_substr($nameList[$sId],$lang);

                    $spec_desc = $spec_desc == '' ? $name : $spec_desc.','.$name;
                }
                if ($spec_desc != '')
                    $order['info'][$k]['spec'] = $spec_desc;
            }
            $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
            $tax_price = $tax_price + ($order['freight_charge'] + $order['packing_charge'])*$store['tax_num']/100;
            $order['tax_price'] = $tax_price;
            $order['deposit_price'] = $deposit_price;
            //
            $this->assign('store', D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find());
            $this->assign('shop',$store);
            $this->assign('sure', $sure);
            $this->assign('order', $order);
            $this->assign('staff',$this->staff_session);
            if($this->language == 'en') {
                $this->display('shop_edit_en');
                return;
            }
            $this->display();
        }
    }

    private function shop_notice($order)
    {
        //验证增加商家余额
        $order['order_type'] = 'shop';
        D('Merchant_money_list')->add_money($this->store['mer_id'], '用户在' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元记入收入', $order);

        //商家推广分佣
        $now_user = M('User')->where(array('uid' => $order['uid']))->find();
        D('Merchant_spread')->add_spread_list($order, $now_user, 'shop', $now_user['nickname'] . '用户购买快店商品获得佣金');

        if(C('config.open_extra_price')==1){
            $score = D('Percent_rate')->get_extra_money($order);
            if($score>0){
                D('User')->add_score($order['uid'], floor($score),'在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.C('config.extra_price_alias_name'));
            }
        }else {
            //积分
            D('User')->add_score($order['uid'], round(($order['payment_money'] + $order['balance_pay']) * $this->config['score_get']), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得'.$this->config['score_name']);
            D('Scroll_msg')->add_msg('shop',$now_user['uid'],'用户'.$now_user['nickname'].'于'.date('Y-m-d H:i',$_SERVER['REQUEST_TIME']). '在'.$this->store['name'] . ' 中消费获得'.$this->config['score_name']);
            D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
        }
        //短信
        $sms_data = array('mer_id' => $this->store['mer_id'], 'store_id' => $this->store['store_id'], 'type' => 'shop');
        if ($this->config['sms_shop_finish_order'] == 1 || $this->config['sms_shop_finish_order'] == 3) {
            if (empty($order['phone'])) {
                $user = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
                $order['phone'] = $user['phone'];
            }
            $sms_data['uid'] = $order['uid'];
            $sms_data['mobile'] = $order['userphone'];
            $sms_data['sendto'] = 'user';
            $sms_data['content'] = '您在 ' . $this->store['name'] . '店中下的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费，如有任何疑意，请您及时联系本店，欢迎再次光临！';
            Sms::sendSms($sms_data);
        }
        if ($this->config['sms_shop_finish_order'] == 2 || $this->config['sms_shop_finish_order'] == 3) {
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $this->store['phone'];
            $sms_data['sendto'] = 'merchant';
            $sms_data['content'] = '顾客购买的' . $order['name'] . '的订单(订单号：' . $order['real_orderid'] . '),已经完成了消费！';
            Sms::sendSms($sms_data);
        }

        //小票打印
        $msg = ArrayToStr::array_to_str($order['order_id'], 'shop_order');
        $op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
        $op->printit($this->store['mer_id'], $order['store_id'], $msg, 2);


        $str_format = ArrayToStr::print_format($order['order_id'], 'shop_order');
        foreach ($str_format as $print_id => $print_msg) {
            $print_id && $op->printit($this->store['mer_id'], $order['store_id'], $print_msg, 2, $print_id);
        }

    }

    public function shop_order_confirm()
    {
        $database = D('Shop_order');
        $order_id = $condition['order_id'] = intval($_POST['order_id']);
        $condition['store_id'] = $this->store['store_id'];
        $order = $database->field(true)->where($condition)->find();
        if (empty($order)) {
            $this->error('订单不存在！');
            exit;
        }
        if ($order['status'] > 0) {
            $this->error('该单已接，不要重复接单');
            exit;
        }
        if ($order['status'] == 4 || $order['status'] == 5) {
            $this->error('订单已取消，不能接单！');
            exit;
        }
        if ($order['is_refund']) {
            $this->error('用户正在退款中~！');
            exit;
        }
        if ($order['paid'] == 0) {
            $this->error('订单未支付，不能接单！');
            exit;
        }

        $data['status'] = 1;
        $data['order_status'] = 1;
        $data['last_staff'] = $this->staff_session['name'];
        $data['last_time'] = time();
        $condition['status'] = 0;
        if ($database->where($condition)->save($data)) {
            if ($order['is_pick_in_store'] != 2 && $order['is_pick_in_store'] != 3) {
                $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
                if ($result['error_code']) {
                    D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
                    $this->error_tips($result['msg']);
                    exit;
                }
                //             	$supply_db_table = D('Deliver_supply');
                //                 $old = $supply_db_table->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
                //                 if (empty($old)) {
                //                 	$supply = array();
                //     				if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
                //                     $supply['order_id'] = $order_id;
                //                     $supply['paid'] = $order['paid'];
                //                     $supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
                //                     $supply['pay_type'] = $order['pay_type'];
                //                     $supply['money'] = $order['price'];
                //     				$supply['deliver_cash'] = round($order['price']+$order['extra_price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
                //     				$supply['deliver_cash'] = max(0, $supply['deliver_cash']);
                //     				$supply['store_id'] = $this->store['store_id'];
                //                     $supply['store_name'] = $this->store['name'];
                //                     $supply['mer_id'] = $this->store['mer_id'];
                //                     $supply['from_site'] = $this->store['adress'];
                //                     $supply['from_lnt'] = $this->store['long'];
                //                     $supply['from_lat'] = $this->store['lat'];
                //     				//目的地
                //     				$supply['aim_site'] =  $order['address'];
                //     				$supply['aim_lnt'] = $order['lng'];
                //     				$supply['aim_lat'] = $order['lat'];
                //     				$supply['name']  = $order['username'];
                //     				$supply['phone'] = $order['userphone'];
                //                     $supply['status'] = 1;
                //                     $supply['type'] = $order['is_pick_in_store'];
                //                     $supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店

                //                     $supply['create_time'] = $_SERVER['REQUEST_TIME'];
                //                     $supply['appoint_time'] = $order['expect_use_time'];
                //                     $supply['note'] = $order['desc'];


                //                     $supply['order_time'] = $order['create_time'];
                //                     $supply['freight_charge'] = $order['freight_charge'];
                //                     $supply['distance'] = round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2);

                //                     if ($supply_db_table->create($supply) != false) {
                //                     	if (!($addResult = $supply_db_table->add($supply))) {
                //                     		$this->error('接单失败');
                //                     	}
                //                     } else {
                //                     	$this->error('已接单');
                //                     }
                //                 }
            }
            $phones = explode(' ', $this->store['phone']);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));

            //add garfunkel
            $userInfo = D('User')->field(true)->where(array('uid'=>$order['uid']))->find();
            if($userInfo['device_id'] != ""){
                $message = 'Your order has been accepted by the store, they are preparing your order now. Our Courier is on the way, thank you for your patient.';
                Sms::sendMessageToGoogle($userInfo['device_id'],$message);
            }else{
                $sms_data['uid'] = $order['uid'];
                $sms_data['mobile'] = $order['userphone'];
                $sms_data['sendto'] = 'user';
                $sms_data['tplid'] = 172700;
                $sms_data['params'] = [];
                Sms::sendSms2($sms_data);
            }

            //发送信息
            //获取所有的配送员
//            $rs = D('Deliver_user')->field(true)->where(array('status' => 1, 'work_status' => 0))->select();
//            foreach($rs as $r){
//                $sms_data = [
//                    'mobile' => $r['phone'],
////		            'tplid' => 86914,
//                    'tplid' =>247173,
//                    'params' => [],
//                    'content' => '有一个新的订单可以配送，请前往个人中心抢单。'
//                ];
//                Sms::sendSms2($sms_data);
//            }

            $this->success('已接单');
        } else {
            $this->error('接单失败');
        }
    }

    public function group_pick()
    {
        $this->check_group();
        $now_order = D('Group_order')->get_order_detail_by_id_and_merId($this->store['mer_id'], $_GET['order_id'], false);
        if (empty($now_order)) {
            $this->error('此订单不存在！');
        }
        if(empty($now_order['paid'])){
            $this->error('此订单尚未支付！');
        }
        if($now_order['status']!=0){
            $this->error('此订单尚不是未消费！');
        }

        $condition_group_order['order_id'] = $now_order['order_id'];
        $date['status'] = 1;
        $date['paid'] = 1;
        $date['last_staff'] = $this->staff_session['name'];
        $date['use_time'] = $_SERVER['REQUEST_TIME'];
        if (empty($now_order['third_id']) && $now_order['pay_type'] == 'offline') {
            $date['third_id'] = $now_order['order_id'];
        }
        if (D('Group_order')->where($condition_group_order)->data($date)->save()) {
            $this->group_notice($now_order, 1);

            //验证增加商家余额
            $now_order['order_type'] = 'group';
            $now_order['verify_all'] = 1;
            $now_order['store_id'] = $this->store['store_id'];
            D('Merchant_money_list')->add_money($this->store['mer_id'], '用户购买' . $now_order['name'] . '记入收入', $now_order);
            $this->success('修改成功！');
        } else {
            $this->error('修改失败！请重试。');
        }
    }


    public function pick()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));
        if (empty($order)) {
            $this->error('订单不存在！');
        }
        $pick_order = D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->find();
        if (IS_POST) {
            if ($order['status'] == 4 || $order['status'] == 5) $this->error_tips('订单已取消，不能接单！');
            if ($order['is_refund']) {
                $this->error_tips('用户正在退款中~！');
                exit;
            }
            if ($order['paid'] == 0) $this->error_tips('订单未支付，不能接单！');
            $pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : '';
            $pick_address = null;
            if ($pick_id) {
                $type = substr($pick_id, 0, 1);
                $type = $type == 's' ? 1 : 0;
                $pick_id = substr($pick_id, 1);
                $pick_address = D('Pick_address')->field(true)->where(array('id' => $pick_id, 'mer_id' => $this->store['mer_id']))->find();

            }
            if (empty($pick_address)) {
                $this->error_tips('没有分配自提点！', U('Storestaff/pick', array('order_id' => $order_id)));
                exit;
            }
            if (empty($pick_order)) {
                D('Pick_order')->add(array('store_id' => $order['store_id'], 'order_id' => $order['order_id'], 'type' => $type, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
                D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 7, 'order_status' => 1, 'pick_id' => $pick_id));
                $phones = explode(' ', $this->store['phone']);
                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));//分配到自提点
                $this->success_tips('分配成功！', U('Storestaff/pick', array('order_id' => $order_id)));
                exit;
            } else {
                $this->error_tips('不要重复分配！', U('Storestaff/pick', array('order_id' => $order_id)));
                exit;
            }
        }

        $user = D('User_adress')->where(array('adress_id' => $order['address_id'], 'uid' => $order['uid']))->find();
        $pick_addr = D('Pick_address')->get_pick_addr_by_merid($this->store['mer_id'], true);
        if ($order['pick_id']) {
            $t_pick = array();
            foreach ($pick_addr as $v) {
                if ('p' . $order['pick_id'] == $v['pick_addr_id']) {
                    $t_pick = $v;
                }
            }
            $pick_addr = array($t_pick);
        } else {
            foreach ($pick_addr as &$v) {
                $v['range'] = getRange(getDistance($v['lat'], $v['long'], $user['latitude'], $user['longitude']));
            }
        }
        $this->assign('order', $order);
        $pick_order['pick_id'] = isset($pick_order['pick_id']) && $pick_order['pick_id'] ? ($pick_order['type'] == 1 ? 's' . $pick_order['pick_id'] : 'p' . $pick_order['pick_id']) : '';

        $this->assign('pick_order', $pick_order);
        $this->assign('pick_addr', $pick_addr);
        $this->display();
    }

    public function deliver_goods()
    {
        $database = D('Shop_order');
        $order_id = $condition['order_id'] = intval($_POST['order_id']);
        $order_id = $condition['order_id'] = intval($_POST['order_id']);
        $condition['store_id'] = $this->store['store_id'];
        $order = $database->field(true)->where($condition)->find();
        if (empty($order)) {
            if (IS_AJAX) {
                $this->error('订单不存在！');
            } else {
                $this->error_tips('订单不存在！');
            }
        }
        if ($order['status'] == 4 || $order['status'] == 5) {
            if (IS_AJAX) {
                $this->error('订单已取消，不能发货！');
            } else {
                $this->error_tips('订单已取消，不能发货！');
            }
        }
        if ($order['is_refund']) {
            if (IS_AJAX) {
                $this->error('用户正在退款中~！');
            } else {
                $this->error_tips('用户正在退款中~！');
            }
            exit;
        }
        if ($order['paid'] == 0) {
            if (IS_AJAX) {
                $this->error('订单未支付，不能发货！');
            } else {
                $this->error_tips('订单未支付，不能发货！');
            }
        }

        $data = array('status' => 8, 'order_status' => 1);
        $data['last_staff'] = $this->staff_session['name'];
        if (D('Shop_order')->where(array('order_id' => $order_id))->save($data)) {//发货
            D('Pick_order')->where(array('store_id' => $order['store_id'], 'order_id' => $order['order_id']))->save(array('status' => 1));
            $phones = explode(' ', $this->store['phone']);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 12, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));//发货
            if (IS_AJAX) {
                $this->success('已发货');
            } else {
                $this->success_tips('已发货', U('Storestaff/shop_edit', array('order_id' => $order['order_id'])));
            }
        } else {
            if (IS_AJAX) {
                $this->error('发货失败，稍后重试！');
            } else {
                $this->error_tips('发货失败，稍后重试！', U('Storestaff/shop_edit', array('order_id' => $order['order_id'])));
            }
        }
    }


    private function get_deliver_fee($store_id)
    {
        $store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
        //起步运费
        $delivery_fee = 0;
        //超出距离部分的单价
        $per_km_price = 0;
        //起步距离
        $basic_distance = 0;
        //减免配送费的金额
        $delivery_fee_reduce = 0;

        //起步运费
        $delivery_fee2 = 0;
        //超出距离部分的单价
        $per_km_price2 = 0;
        //起步距离
        $basic_distance2 = 0;

        if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {//平台配送|平台或自提
            if ($store_shop['s_is_open_own']) {//开启了店铺的独立配送费的设置
                //配送时段一的配置
                if ($store_shop['s_free_type'] == 0) {//免配送费

                } elseif ($store_shop['s_free_type'] == 1) {//不免
                    $delivery_fee = $store_shop['s_delivery_fee'];
                    $per_km_price = $store_shop['s_per_km_price'];
                    $basic_distance = $store_shop['s_basic_distance'];
                } elseif ($store_shop['s_free_type'] == 2) {//满免
                    if ($price < $store_shop['s_full_money']) {
                        $delivery_fee = $store_shop['s_delivery_fee'];
                        $per_km_price = $store_shop['s_per_km_price'];
                        $basic_distance = $store_shop['s_basic_distance'];
                    }
                }
                //配送时段二的配送
                if ($store_shop['s_free_type2'] == 0) {//免配送费

                } elseif ($store_shop['s_free_type2'] == 1) {//不免
                    $delivery_fee2 = $store_shop['s_delivery_fee2'];
                    $per_km_price2 = $store_shop['s_per_km_price2'];
                    $basic_distance2 = $store_shop['s_basic_distance2'];
                } elseif ($store_shop['s_free_type2'] == 2) {//满免
                    if ($price < $store_shop['s_full_money2']) {
                        $delivery_fee2 = $store_shop['s_delivery_fee2'];
                        $per_km_price2 = $store_shop['s_per_km_price2'];
                        $basic_distance2 = $store_shop['s_basic_distance2'];
                    }
                }
            } else {
                $delivery_fee = $this->config['delivery_fee'];
                $per_km_price = $this->config['per_km_price'];
                $basic_distance = $this->config['basic_distance'];

                $delivery_fee2 = $this->config['delivery_fee2'];
                $per_km_price2 = $this->config['per_km_price2'];
                $basic_distance2 = $this->config['basic_distance2'];
            }
            //使用平台的优惠（配送费的减免）
            // 			if ($d_tmp = $this->get_reduce($discounts, 2, $price)) {
            // 				$delivery_fee_reduce = $d_tmp['reduce_money'];
            // 			}
        } else {//商家配送|商家或自提|快递配送
            if ($store_shop['reach_delivery_fee_type'] == 0) {

            } elseif ($store_shop['reach_delivery_fee_type'] == 1) {
                $delivery_fee = $store_shop['delivery_fee'];
                $per_km_price = $store_shop['per_km_price'];
                $basic_distance = $store_shop['basic_distance'];

                $delivery_fee2 = $store_shop['delivery_fee2'];
                $per_km_price2 = $store_shop['per_km_price2'];
                $basic_distance2 = $store_shop['basic_distance2'];
            } elseif ($store_shop['reach_delivery_fee_type'] == 2)  {
                if ($price < $store_shop['no_delivery_fee_value']) {
                    $delivery_fee = $store_shop['delivery_fee'];
                    $per_km_price = $store_shop['per_km_price'];
                    $basic_distance = $store_shop['basic_distance'];

                    $delivery_fee2 = $store_shop['delivery_fee2'];
                    $per_km_price2 = $store_shop['per_km_price2'];
                    $basic_distance2 = $store_shop['basic_distance2'];
                }
            }
            if ($store_shop['reach_delivery_fee_type2'] == 0) {

            } elseif ($store_shop['reach_delivery_fee_type2'] == 1) {
                $delivery_fee2 = $store_shop['delivery_fee2'];
                $per_km_price2 = $store_shop['per_km_price2'];
                $basic_distance2 = $store_shop['basic_distance2'];
            } elseif ($store_shop['reach_delivery_fee_type2'] == 2)  {
                if ($price < $store_shop['no_delivery_fee_value2']) {
                    $delivery_fee2 = $store_shop['delivery_fee2'];
                    $per_km_price2 = $store_shop['per_km_price2'];
                    $basic_distance2 = $store_shop['basic_distance2'];
                }
            }
        }

        return array('delivery_fee_reduce' => $delivery_fee_reduce, 'basic_distance' => $basic_distance, 'per_km_price' => $per_km_price, 'delivery_fee' => $delivery_fee, 'basic_distance2' => $basic_distance2, 'per_km_price2' => $per_km_price2, 'delivery_fee2' => $delivery_fee2);
    }
    public function mall_order_detail()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'store_id' => $this->store['store_id']));

        $store_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
        if ($store_shop['deliver_type'] == 0 || $store_shop['deliver_type'] == 3) {
            $delivery_times = explode('-', $this->config['delivery_time']);
            $start_time = $delivery_times[0] . ':00';
            $stop_time = $delivery_times[1] . ':00';

            $delivery_times2 = explode('-', $this->config['delivery_time2']);
            $start_time2 = $delivery_times2[0] . ':00';
            $stop_time2 = $delivery_times2[1] . ':00';
        } else {
            $start_time = $store_shop['delivertime_start'];
            $stop_time = $store_shop['delivertime_stop'];

            $start_time2 = $store_shop['delivertime_start2'];
            $stop_time2 = $store_shop['delivertime_stop2'];
        }

        $have_two_time = 1;//是否两个时段 0：没有，1有

        $is_cross_day_1 = 0;//第一时间段是否跨天 0：不跨天，1：跨天
        $is_cross_day_2 = 0;//第二时间段是否跨天 0：不跨天，1：跨天

        $time = time() + $store_shop['send_time'] * 60;//默认的期望送达时间

        $format_second_time = 1;//是否要格式化时间段二

        $now_time_value = 1;//当前所处的时间段
        if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
            $start_time = strtotime(date('Y-m-d ') . '00:00');
            $stop_time = strtotime(date('Y-m-d ') . '23:59');
            $have_two_time = 0;
        } else {
            $start_time = strtotime(date('Y-m-d ') . $start_time);
            $stop_time = strtotime(date('Y-m-d ') . $stop_time);
            if ($stop_time < $start_time) {
                $stop_time = $stop_time + 86400;
                $is_cross_day_1 = 1;
            }

            if ($time < $start_time) {
                $time = $start_time;
            } elseif ($start_time <= $time && $time <= $stop_time) {

            } else {
                $format_second_time = 0;
                if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
                    $have_two_time = 0;
                    $time = $start_time + 86400;
                    $start_time2 = strtotime(date('Y-m-d ') . '00:00');
                    $stop_time2 = strtotime(date('Y-m-d ') . '23:59');
                } else {
                    $start_time2 = strtotime(date('Y-m-d ') . $start_time2);
                    $stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
                    if ($stop_time2 < $start_time2) {
                        $stop_time2 = $stop_time2 + 86400;
                        $is_cross_day_2 = 1;
                    }

                    if ($time < $start_time2) {
                        $time = $start_time2;
                        $now_time_value = 2;
                    } elseif ($start_time2 <= $time && $time <= $stop_time2) {
                        $now_time_value = 2;
                    } else {
                        $time = $start_time + 86400;
                    }
                }
            }
        }
        if ($format_second_time) {//是否要格式化时间段二
            if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {
                $have_two_time = 0;
                $start_time2 = strtotime(date('Y-m-d ') . '00:00');
                $stop_time2 = strtotime(date('Y-m-d ') . '23:59');
            } else {
                $start_time2 = strtotime(date('Y-m-d ') . $start_time2);
                $stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
                if ($stop_time2 < $start_time2) {
                    $stop_time2 = $stop_time2 + 86400;
                    $is_cross_day_2 = 1;
                }
            }
        }

        if ($have_two_time) {
            $this->assign(array('time_select_1' => date('H:i', $start_time) . '-' . date('H:i', $stop_time), 'time_select_2' => date('H:i', $start_time2) . '-' . date('H:i', $stop_time2)));
        } else {
            $this->assign(array('time_select_1' => date('H:i', $start_time) . '-' . date('H:i', $stop_time)));
        }
        $this->assign('have_two_time', $have_two_time);

        $distance = getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);

        $distance = $distance / 1000;

        $return = $this->get_deliver_fee($order['store_id']);

        $pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
        $delivery_fee = $return['delivery_fee'] + round($pass_distance * $return['per_km_price'], 2);
        $delivery_fee = $delivery_fee - $return['delivery_fee_reduce'];
        $delivery_fee = $delivery_fee > 0 ? $delivery_fee : 0;

        $pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
        $delivery_fee2 = $return['delivery_fee2'] + round($pass_distance * $return['per_km_price2'], 2);
        $delivery_fee2 = $delivery_fee2 - $return['delivery_fee_reduce'];
        $delivery_fee2 = $delivery_fee2 > 0 ? $delivery_fee2 : 0;


        $this->assign(array('delivery_fee' => $delivery_fee, 'delivery_fee2' => $delivery_fee2));
        $this->assign('arrive_datetime', date('Y-m-d H:i', $time));
        $this->assign('distance', round($distance, 2));
        $this->assign('store', $store_shop);
        $this->assign('order', $order);

        if($this->language == 'en') {
            $this->display('mall_order_detail_en');
            return;
        }

        $this->display();

    }

    /**
     * 商城订单更改配送方式  将快递 更改成 其他配送方式
     */
    public function check_deliver()
    {
        $database = D('Shop_order');
        $order_id = $condition['order_id'] = intval($_POST['order_id']);
        $expect_use_time = isset($_POST['expect_use_time']) ? strtotime(htmlspecialchars($_POST['expect_use_time'])) : 0;
        $condition['store_id'] = $this->store['store_id'];
        $order = $database->field(true)->where($condition)->find();
        if(empty($order)){
            $this->error_tips('订单不存在！');
            exit;
        }

        if ($order['is_refund']) {
            $this->error_tips('用户正在退款中~！');
            exit;
        }
        if ($order['paid'] == 0) {
            $this->error_tips('订单未支付，不能接单！');
            exit;
        }
        if ($order['status'] > 0) {
            $this->error_tips('该订单已处理，不能更改！');
            exit;
        }

        if ($order['is_pick_in_store'] != 3) {
            $this->error_tips('不是快递配送，不能修改配送方式！');
            exit;
        }
        $d_shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();

        if (in_array($d_shop['deliver_type'], array(2, 5))) {
            $this->error_tips('店铺不支持快递以外的配送，不能修改配送方式！');
            exit;
        }
        $is_pick_in_store = $d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3 ? 0 : 1;



        if ($d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3) {
            $delivery_times = explode('-', $this->config['delivery_time']);
            $start_time = $delivery_times[0] . ':00';
            $stop_time = $delivery_times[1] . ':00';

            $delivery_times2 = explode('-', $this->config['delivery_time2']);
            $start_time2 = $delivery_times2[0] . ':00';
            $stop_time2 = $delivery_times2[1] . ':00';
        } else {
            $start_time = $d_shop['delivertime_start'];
            $stop_time = $d_shop['delivertime_stop'];

            $start_time2 = $d_shop['delivertime_start2'];
            $stop_time2 = $d_shop['delivertime_stop2'];
        }



        $time = $expect_use_time ? $expect_use_time : (time() + $d_shop['send_time'] * 60);//默认的期望送达时间


        $now_time_value = 1;//当前所处的时间段
        if ($start_time == $stop_time && $start_time == '00:00:00') {//时间段一，24小时
        } else {
            $start_time = strtotime(date('Y-m-d ') . $start_time);
            $stop_time = strtotime(date('Y-m-d ') . $stop_time);
            if ($stop_time < $start_time) {
                $stop_time = $stop_time + 86400;
            }

            if ($time < $start_time) {
                $time = $start_time;
            } elseif ($start_time <= $time && $time <= $stop_time) {

            } else {
                if ($start_time2 == $stop_time2 && $start_time2 == '00:00:00') {//没有时间段二
                    $time = $start_time + 86400;
                    $start_time2 = strtotime(date('Y-m-d ') . '00:00');
                    $stop_time2 = strtotime(date('Y-m-d ') . '23:59');
                } else {
                    $start_time2 = strtotime(date('Y-m-d ') . $start_time2);
                    $stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
                    if ($stop_time2 < $start_time2) {
                        $stop_time2 = $stop_time2 + 86400;
                    }

                    if ($time < $start_time2) {
                        $time = $start_time2;
                        $now_time_value = 2;
                    } elseif ($start_time2 <= $time && $time <= $stop_time2) {
                        $now_time_value = 2;
                    } else {
                        $time = $start_time + 86400;
                    }
                }
            }
        }
        $distance = getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long']);
        $distance = $distance / 1000;
        $return = $this->get_deliver_fee($order['store_id']);
        if ($now_time_value == 1) {
            $pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
            $delivery_fee = $return['delivery_fee'] + round($pass_distance * $return['per_km_price'], 2);
            $delivery_fee = $delivery_fee - $return['delivery_fee_reduce'];
            $delivery_fee = $delivery_fee > 0 ? $delivery_fee : 0;
        } else {
            $pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
            $delivery_fee = $return['delivery_fee2'] + round($pass_distance * $return['per_km_price2'], 2);
            $delivery_fee = $delivery_fee - $return['delivery_fee_reduce'];
            $delivery_fee = $delivery_fee > 0 ? $delivery_fee : 0;
        }


        $data['status'] = 1;
        $condition['status'] = 0;
        $data['order_status'] = 1;
        $data['is_pick_in_store'] = $is_pick_in_store;
        $data['expect_use_time'] = $time;
        $data['last_staff'] = $this->staff_session['name'];
        if ($d_shop['deliver_type'] == 0 || $d_shop['deliver_type'] == 3) {
            $data['no_bill_money'] = $delivery_fee;
        }

        if ($database->where($condition)->save($data)) {
            $result = D('Deliver_supply')->saveOrder($order_id, $this->store);
            if ($result['error_code']) {
                D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
                $this->error_tips($result['msg']);
                exit;
            }
            $phones = explode(' ', $this->store['phone']);
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $phones[0]));
            $this->success_tips('已接单');
        } else {
            $this->error_tips('接单失败');
        }
    }
    //@ydhl-wangchuanyaun 店员添加订单
    public function add_shop_order(){
        //省城区
        $database_area = D('Area');
        $now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
        $this->assign('now_city_area',$now_city_area);
        $province_list = $database_area->get_arealist_by_areaPid(0);
        $this->assign('province_list',$province_list);
        $city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
        $this->assign('city_list',$city_list);
        $area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
        $this->assign('area_list',$area_list);
        $now_adress = json_decode($_COOKIE['staff_address'], true);
        $this->assign('now_adress', $now_adress);
        $this->display();
    }
    //@ydhl-wangchuanyaun 店员修改订单  暂时不考虑店员修改订单
    public function edit_shop_order(){
        //	    $order_id = $_GET['order_id'];
        //	    $shop_order = M('Shop_order')->field(true)->find($order_id);//订单
        //        if ($shop_order['staff_id'] !=$this->staff_session['id']){
        //            $this->error_tips('你不能修改此订单');
        //        }
        //        if ($shop_order['order_status'] > 4){
        //            $this->error_tips('此订单已确认收货，不能在修改！');
        //        }
        //	    $user_adress = M('User_adress')->field(true)->find($shop_order['address_id']);//联系地址
        //        //省城区
        //        $database_area = D('Area');
        //        $now_city_area = $database_area->where(array('area_id'=>$this->config['now_city']))->find();
        //        $this->assign('now_city_area',$now_city_area);
        //        $province_list = $database_area->get_arealist_by_areaPid(0);
        //        $this->assign('province_list',$province_list);
        //        $city_list = $database_area->get_arealist_by_areaPid($now_city_area['area_pid']);
        //        $this->assign('city_list',$city_list);
        //        $area_list = $database_area->get_arealist_by_areaPid($now_city_area['area_id']);
        //        $this->assign('shop_order',$shop_order);
        //        $this->assign('user_adress',$user_adress);
        //        $this->assign('area_list',$area_list);
        //
        //        $this->display();
    }
    //@ydhl-wangchuanyaun 店员  订单确认
    public function confirm_order(){
        if (IS_POST){
            if (!trim($_POST['name'])){
                $this->error_tips('请输入客户姓名');
            }
            if (!trim($_POST['phone'])){
                $this->error_tips('请输入客户电话');
            }
            if (!trim($_POST['adress'])){
                $this->error_tips('请输入客户位置');
            }
            if (!trim($_POST['detail'])){
                $this->error_tips('请输入客户详细地址');
            }
            if (!trim($_POST['goods_price'])){
                $this->error_tips('请输入商品总价');
            }
            if (!$_POST['longitude'] ||!$_POST['latitude'] ||!$_POST['adress']){
                $this->error_tips('请选择位置');
            }

            $staff=$this->staff_session; //店员
            $merchant_store = $this->store; //店铺
            $goods_price = $_POST['goods_price'];

            $_POST['longitude']=sprintf("%10.6f", $_POST['longitude']);
            $_POST['latitude']=sprintf("%10.6f", $_POST['latitude']);
            //先保存用户地址，在使用用户的地址来计算配送费
            unset($_POST['goods_price']);
            $user_add = M('UserAdress')->data($_POST)->add();
            if ($user_add){
                $user_adress = M('UserAdress')->field(true)->find($user_add);
            }else{
                $this->error('地址保存失败！请重试');
            }
            //计算配送费
            if ($user_adress) {
                //获取两点之间的距离
                $distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $merchant_store['lat'], $merchant_store['long']);
                $distance = $distance / 1000;
                //获取配送费用
                $deliveryCfg = [];
                $deliverys = D("Config")->get_gid_config(20);
                foreach($deliverys as $r){
                    $deliveryCfg[$r['name']] = $r['value'];
                }
                if($distance < 5) {
                    $return['delivery_fee'] = round($deliveryCfg['delivery_distance_1'], 2);
                }elseif($distance > 5 && $distance <= 8) {
                    $return['delivery_fee'] = round($deliveryCfg['delivery_distance_2'], 2);
                }elseif($distance > 8 && $distance <= 10) {
                    $return['delivery_fee'] = round($deliveryCfg['delivery_distance_3'], 2);
                }elseif($distance > 10 && $distance <= 15) {
                    $return['delivery_fee'] = round($deliveryCfg['delivery_distance_4'], 2);
                }elseif($distance > 15 && $distance <= 20) {
                    $return['delivery_fee'] = round($deliveryCfg['delivery_distance_5'], 2);
                }elseif($distance > 20) {
                    $return['delivery_fee'] = round($deliveryCfg['delivery_distance_more'], 2);
                }
                $return['delivery_fee2'] = $return['delivery_fee'];
            }
            $real_orderid  = date('ymdhis').substr(microtime(),2,8-strlen($staff['id'])).$staff['id'];//订单编号
            //商品税费
            $return_data=[];
            $store = D('Merchant_store')->field(true)->where(array('store_id'=>$merchant_store['store_id']))->find();
            $freight_charge_tax = $return['delivery_fee'] * $store['tax_num']/100;//配送费税
            //$goods_price_tax = $goods_price * 0.05;//商品税
            $return_data['staff_id']=$staff['id'];//店员id
            $return_data['store_id']=$merchant_store['store_id'];//店铺id
            $return_data['mer_id']=$merchant_store['mer_id'];//商家id
            $return_data['goods_price']=floatval(sprintf("%.2f", $goods_price));//商品总价
            //$return_data['goods_price_tax']=$goods_price_tax;//商品税
            $return_data['freight_charge']=floatval(sprintf("%.2f", $return['delivery_fee']));//配送费
            $return_data['freight_charge_tax']=$freight_charge_tax;//配送费税
            $return_data['address_id']=$user_add;//客户地址id
            $return_data['real_orderid']=$real_orderid;//订单编号
            $return_data['desc']="Merchant--{$staff['name']}--order from restaurants";//备注
            $return_data['goods_price_tax'] = $_POST['goods_tax'] ? $_POST['goods_tax'] : 0;
            $return_data['deposit'] = $_POST['goods_deposit'] ? $_POST['goods_deposit'] : 0;
            $return_data['all_tax'] = floatval(sprintf("%.2f", $return_data['goods_price_tax'])) + floatval(sprintf("%.2f", $freight_charge_tax));
            $price = floatval(sprintf("%.2f", $goods_price))+floatval(sprintf("%.2f", $return_data['goods_price_tax']))+floatval(sprintf("%.2f", $return['delivery_fee']))+floatval(sprintf("%.2f", $freight_charge_tax))+floatval(sprintf("%.2f", $return_data['deposit']));
            $return_data['total_price']=$price;//总价=实际支付
            $return_data['price']=$price;//实际需要支付的金额，商品*配送
            $this->assign('post_data',$_POST);
            $this->assign('return_data',$return_data);
        }
        $this->display();
    }
    //@ydhl-wangchuanyaun 保存店员下单
    public function save_shop_oder(){
        if (IS_POST){
            $_POST['create_time']=strtotime(date("Y-m-d H:i:s"));//下单时间
            $_POST['paid']=1;//是否支付
            $_POST['pay_time']=strtotime(date("Y-m-d H:i:s"));//支付时间，为了排序  靠前显示
            $_POST['pay_type']='offline';//支付类型

            //**代客下单 用discount_price记录税费**
            //**代客下单 用packing_charge记录押金**

            $order = M('Shop_order')->data($_POST)->add();
            if ($order){
                //清除cookie
                cookie('staff_address',null,'');
                $this->success_tips('Success',U('Storestaff/shop_list'));

            }else{
                $this->error_tips('Fail');
            }
        }
    }
    //店员下单地图
    public function staff_order_map(){
        //        $cookie = json_decode($_COOKIE['staff_address'], true);
        //        dump($cookie);
        $this->display();
    }





}
