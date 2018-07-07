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
        $this->assign('merchantstatic_path', $this->config['site_url'] . '/tpl/Merchant/static/');
    }

    public function login()
    {
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

    /*****绑定微信下次免登陆********/
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
        if ($this->store['have_group']) {
            redirect(U('Storestaff/group_list'));
        } elseif ($this->store['have_meal']) {
            redirect(U('Storestaff/meal_list'));
        } elseif ($this->store['have_shop']) {
            redirect(U('Storestaff/shop_list'));
        } else {
			session('staff_session', null);
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
    public function appoint_edit()
    {
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
        // dump($now_order);
        $this->assign('now_order', $now_order);
        $this->display();
    }

    /*验证预约服务*/
    public function appoint_verify()
    {
        $database_order = D('Appoint_order');
        $where['store_id'] = $this->store['store_id'];
        $where['order_id'] = $_GET['order_id'];
        $order_info = $database_order->field(true)->where($where)->find();

        if (empty($order_info)) {
            $this->error('当前订单不存在！');
        } else {
            $fields['store_id'] = $this->staff_session['store_id'];
            $fields['last_staff'] = $this->staff_session['name'];
            $fields['last_time'] = time();
            $fields['service_status'] = 1;
            if ($database_order->where($where)->data($fields)->save()) {
                //验证增加商家余额
                $order_info['order_type'] = 'appoint';
                $appoint_name = M('Appoint')->field('appoint_name')->where(array('appoint' => $order_info['appoint_id']))->find();
                D('Merchant_money_list')->add_money($this->store['mer_id'], '用户预约' . $appoint_name['appoint_name'] . '记入收入', $order_info);

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
exit;
        if(empty($now_order['paid'])){
            $this->error('此订单尚未支付！');
        }
        if($now_order['status']!=0){
            //$this->error('此订单尚不是未消费！');
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
exit;
                //验证增加商家余额
                $now_order['order_type'] = 'group';
                $now_order['store_id'] = $this->store['store_id'];
                $now_order['verify_all'] = 1;
                D('Merchant_money_list')->add_money($this->store['mer_id'], '用户购买' . $now_order['name'] . '记入收入', $now_order);
                $this->success('验证成功！');
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
            D('User')->add_score($order['uid'], floor($order['total_money'] * C('config.user_score_get')), '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得积分');
            D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['total_money'], '购买 ' . $order['order_name'] . ' 消费' . floatval($order['total_money']) . '元 获得积分');
            //商家推广分佣
            $now_user = M('User')->where(array('uid' => $order['uid']))->find();
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

        //积分
        D('User')->add_score($order['uid'], floor($order['price'] * C('config.user_score_get')), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
        D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');

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
            if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
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
                    $supply['deliver_cash'] = floatval($order['price'] - $order['card_price'] - $order['merchant_balance'] - $order['balance_pay'] - $order['payment_money'] - $order['score_deducte'] - $order['coupon_price']);
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
            } else {
                $this->error('您还没有接入配送机制');
            }
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
        $this->assign(D("Shop_order")->get_order_list($where, 'paid DESC, status ASC, order_id DESC', 4));

        $shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $this->store['store_id']))->find();
        $shop = array_merge($this->store, $shop);
        $this->assign('now_store', $shop);
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
            if ($order['status'] > 0) {
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
                if ($status == 2 && $order['status'] < 2) {
                    D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
                    if ($supply = D('Deliver_supply')->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find()) {
                        if ($supply['status'] < 2) {
                            D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->delete();
                        } else {
                            D('Deliver_supply')->where(array('order_id' => $order_id, 'item' => 2))->save(array('status' => 5));
                        }
                    }
                    $this->shop_notice($order);
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
    				$data = array('status' => $status, 'order_status' => 6, 'use_time' => time(), 'last_staff' => $this->staff_session['name']);
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
	    					if ($status == 1) {
	    						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
	    					} elseif ($status == 2 && $order['status'] != 2 && $order['status'] != 3) {
	    						$this->shop_notice($order);
	    						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
	    					} elseif ($status == 4) {
	    						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
	    					} elseif ($status == 5) {
	    						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 10, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
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
	    					if ($status == 2 && $order['status'] < 2) {
	    						D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 7, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
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
				$supply = D('Deliver_supply')->field('uid')->where(array('order_id' => $order['order_id'], 'item' => 2))->find();
				if (isset($supply['uid']) && $supply['uid']) {
					$sure = false;
				}
			}
			$this->assign('store', D('Merchant_store_shop')->field(true)->where(array('store_id' => $store_id))->find());
			$this->assign('sure', $sure);
    		$this->assign('order', $order);
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


        //积分
        D('User')->add_score($order['uid'], floor($order['price'] * C('config.user_score_get')), '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');
        D('Userinfo')->add_score($order['uid'], $order['mer_id'], $order['price'], '在 ' . $this->store['name'] . ' 中消费' . floatval($order['price']) . '元 获得积分');

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
        if ($order['paid'] == 0) {
        	$this->error('订单未支付，不能接单！');
        	exit;
        }

        $data['status'] = 1;
        $data['order_status'] = 1;
        $data['last_staff'] = $this->staff_session['name'];
        $condition['status'] = 0;
        if ($database->where($condition)->save($data)) {
	    	if ($order['is_pick_in_store'] != 2 && $order['is_pick_in_store'] != 3) {
	            $deliverCondition['store_id'] = $this->store['store_id'];
	            $deliverCondition['mer_id'] = $this->store['mer_id'];
	            // 商家是否接入配送
	            if ($deliver = D('Deliver_store')->where($deliverCondition)->find()) {
	            	$supply_db_table = D('Deliver_supply');
	                $old = $supply_db_table->field(true)->where(array('order_id' => $order_id, 'item' => 2))->find();
	                if (empty($old)) {
	                	$supply = array();
	    				if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
	                    $supply['order_id'] = $order_id;
	                    $supply['paid'] = $order['paid'];
	                    $supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
	                    $supply['pay_type'] = $order['pay_type'];
	                    $supply['money'] = $order['price'];
	    				$supply['deliver_cash'] = round($order['price'] - round($order['card_price'] + $order['merchant_balance'] + $order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
	                    $supply['store_id'] = $this->store['store_id'];
	                    $supply['store_name'] = $this->store['name'];
	                    $supply['mer_id'] = $this->store['mer_id'];
	                    $supply['from_site'] = $this->store['adress'];
	                    $supply['from_lnt'] = $this->store['long'];
	                    $supply['from_lat'] = $this->store['lat'];
	    				//目的地
	    				$supply['aim_site'] =  $order['address'];
	    				$supply['aim_lnt'] = $order['lng'];
	    				$supply['aim_lat'] = $order['lat'];
	    				$supply['name']  = $order['username'];
	    				$supply['phone'] = $order['userphone'];
	                    $supply['status'] = 1;
	                    $supply['type'] = $order['is_pick_in_store'];
	                    $supply['item'] = 2;//0:老快店的外卖，1：外送系统，2：新快店

	                    $supply['create_time'] = $_SERVER['REQUEST_TIME'];
	                    $supply['start_time'] = $_SERVER['REQUEST_TIME'];
	                    $supply['appoint_time'] = $order['expect_use_time'];
	                    $supply['note'] = $order['desc'];
	                    if ($supply_db_table->create($supply) != false) {
	                    	if (!($addResult = $supply_db_table->add($supply))) {
	                    		$this->error('接单失败');
	                    	}
	                    } else {
	                    	$this->error('已接单');
	                    }
	                }
	            } else {
	                $this->error('您还没有接入配送机制');
	            }
	        }
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));
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
                D('Pick_order')->add(array('store_id' => $order['store_id'], 'order_id' => $order['order_id'], 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
				D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 7, 'order_status' => 1));
                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
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
                if ($order['pick_id'] == $v['pick_addr_id']) {
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
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 12, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//发货
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

}