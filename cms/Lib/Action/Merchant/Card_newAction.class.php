<?php

/**
 * 新商家会原卡
 * User: 李俊
 * Date: 2016/8/17
 * Time: 11:21
 */
class Card_newAction extends BaseAction
{

    //实体卡编辑
    public function card_edit()
    {
        $mer_id = $this->merchant_session['mer_id'];

        if (IS_POST) {
            if (empty($_POST['support_score_select'])) {
                $_POST['support_score']=0;
            } elseif (!is_numeric($_POST['support_score']) || empty($_POST['support_score'])) {
                $this->error('消费一元获得积分数必须是大于1的数字');
            }

            if(!is_numeric($_POST['discount'])){
                $this->error('请填写0到10的数字,0相当于不打折,比如95折 填写9.5即可');
            }

            if ($_POST['recharge_back_rule'] == 1 && !$_POST['support_score_select']) {
                $this->error('积分支持已关闭，充值返现规则不能再选积分');
            }
            if (!is_numeric($_POST['recharge_back_money'])&&!is_numeric($_POST['recharge_back_score'])) {
                $this->error('请填写正确的数据');
            }
            if (!is_numeric($_POST['begin_money']) && $this->config['merchant_card_recharge_offline'] || $_POST['begin_money'] < 0) {
                $this->error('开卡初始金额设置错误');
            }

            if($_POST['discount']<0||$_POST['discount']>10){
                $this->error('会员卡折扣设置错误,不给折扣请填10');
            }
            //逗号替换
            $_POST['recharge_suggest'] = preg_replace("/(，)/",',',$_POST['recharge_suggest']);
            $_POST['mer_id'] = $mer_id;
            $_POST['last_time'] = $_SERVER['REQUEST_TIME'];
            if(M('Card_new')->where(array('mer_id'=>$_POST['mer_id']))->find()){
                M('Card_new')->where(array('mer_id'=>$mer_id))->data($_POST)->save();
                $this->success('保存成功');
            }else{
                $_POST['add_time'] = $_SERVER['REQUEST_TIME'];
                M('Card_new')->add($_POST);
                $this->success('添加会员卡成功');
            }

        } else {
            $data = M('Card_new')->where(array('mer_id'=>$mer_id))->find();
            $this->assign('card', $data);
            $this->display();
        }
    }

    //会员卡用户列表
    public function index()
    {
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'phone') {
                $condition_user['u.phone'] = array('like', '%' . $_GET['keyword'] . '%');
            }
            if ($_GET['searchtype'] == 'card_id') {
                $condition_user['c.id'] = array('like', '%' . $_GET['keyword'] . '%');
            }
            if ($_GET['searchtype'] == 'physical_id') {
                $condition_user['c.physical_id'] = array('like', '%' . $_GET['keyword'] . '%');
            }
            if ($_GET['searchtype'] == 'nickname') {
                $condition_user['u.nickname'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        $condition_user['c.mer_id'] = $this->merchant_session['mer_id'];
        $count_count = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count_count, 20);
        $card_user_list = M('Card_userlist')->field('c.*,u.*,c.add_time as card_add_time,c.status as card_status')->join('as c left join '.C('DB_PREFIX').'user as u ON u.uid = c.uid')->where($condition_user)->order('c.id DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('card_user_list', $card_user_list);
        $this->display();
    }

    public function add_user(){
        if(!D('Card_new')->get_card_by_mer_id($this->merchant_session['mer_id'])){
            exit( '<html><head><style>*{margin:0;padding:0;}</style></head><body>请先编辑完善会员卡信息再添加用户</body></html>');
        }
        if(IS_POST){
            //卡号规则
            $_POST['mer_id'] = $this->merchant_session['mer_id'];
            if( $card_id = M('Card_userlist')->add($_POST)){
                if (!empty($_POST['card_money'])) {
                    $data_money['card_id'] = $card_id ;
                    $data_money['type'] = 1;
                    $data_money['money_add'] = $_POST['card_money'];
                    $data_money['desc'] = '会员卡创建初始金额';
                    D('Card_new')->add_row($data_money);
                }
                if (!empty($_POST['card_score'])) {
                    $data_score['card_id'] = $card_id ;
                    $data_score['type'] = 1;
                    $data_score['score_add'] = $_POST['card_score'];
                    $data_score['desc'] = '会员卡创建初始积分';
                    D('Card_new')->add_row($data_score);
                }
               $this->success('添加成功');
            }else{
               $this->error('添加失败');
            }
        }else{
            $this->display();
        }
    }

    //用户扫描二维码绑定会员卡
    public function see_qrcode(){
       if($_GET['wx_qrcode']) {
           $coupon = M('Card_new_coupon')->where(array('coupon_id' => $_GET['id']))->find();
           if(empty($coupon['wx_cardid'])){
               echo '<html><head><style>*{margin:0;padding:0;}</style></head><body>微信同步失败</body></html>';die;
           }
           echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img src="' . $coupon['wx_qrcode'] . '"/></body></html>';die;
       }
        if(!D('Card_new')->get_card_by_mer_id($this->merchant_session['mer_id'])){
            exit( '<html><head><style>*{margin:0;padding:0;}</style></head><body>请先编辑完善会员卡信息再添加用户</body></html>');
        }
        $where['id']  = $_GET['id'];
        $qrcode_id =800000000+$_GET['id'];
        $res = D('Recognition')->get_tmp_qrcode($qrcode_id);
        $date['qrcode_id']=$qrcode_id;
        $date['ticket']=$res['ticket'];
        $where['id']=$_GET['id'];
        M('Card_userlist')->where($where)->save($date);
        echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img src="'.$res['ticket'].'"/></body></html>';
    }

    //ajax 获取创建的会员卡绑定状态
    public function ajax_get_bind_status(){
        $cardid = $_POST['cardid'];
        if($uid = M('Card_userlist')->where(array('id'=>$cardid))->getField('uid')){
            echo json_encode(array('error_code'=>0,'msg'=>'绑定成功'));die;
        }else{
            echo json_encode(array('error_code'=>1,'msg'=>'绑定失败'));die;
        }
    }
    //会员卡详情
    public function card_detail()
    {
        $card_group  = M('Card_group')->where(array('mer_id'=>$this->merchant_session['mer_id']))->select();
        $this->assign('card_group',$card_group);
        if (IS_POST) {
            $card_info = D('Card_new')->get_cardinfo_by_id($_POST['id']);
            if (empty($card_info)) {
                $this->error('会员卡不存在');
            } else {
                $data['card_money_give'] = pow(-1,($_POST['set_money_type']+1))*$_POST['set_money']+$card_info['card_money_give'];
                $data['card_score'] = pow(-1,($_POST['set_score_type']+1))*$_POST['set_score']+$card_info['card_score'];
                if($data['card_money_give']<0){
                    $data['card_money'] = $card_info['card_money']+$data['card_money_give'];
                    $data['card_money_give']=0;
                }

                if($data['card_score']<0){
                    $data['card_score']=0;
                }

                $data['physical_id'] = $_POST['physical_id'];
                $data['status'] = $_POST['status'];
                $data['gid'] = $_POST['gid'];

                if (M('Card_userlist')->where(array('id' => $_POST['id'], 'uid' => $_POST['uid']))->data($data)->save()) {
                    if (!empty($_POST['set_money'])) {
                        $des = $_POST['set_money_type']==1?'增加':'减少';
                        $add_use = $_POST['set_money_type']==1?'add':'use';
                        $data_money['card_id'] = $card_info['id'] ;
                        $data_money['type'] = $_POST['set_money_type'];
                        $data_money['money_'.$add_use] = $_POST['set_money'];
                        $data_money['desc'] = '商家后台操作'.$des.'赠送余额';
                        D('Card_new')->add_row($data_money);
                    }
                    if (!empty($_POST['set_score'])) {
                        $des = $_POST['set_score_type']==1?'增加':'减少';
                        $add_use = $_POST['set_score_type']==1?'add':'use';
                        $data_score['card_id'] = $card_info['id'] ;
                        $data_score['type'] = $_POST['set_money_type'];
                        $data_score['score_'.$add_use] = $_POST['set_money'];
                        $data_score['desc'] = '商家后台操作'.$des.'积分';
                        D('Card_new')->add_row($data_score);
                    }
                    $this->success('Success');
                } else {

                    $this->error('修改失败！请重试。');
                }
            }
        } else {
            $id = $_GET['id'];
            $card_info = D('Card_new')->get_cardinfo_by_id($id);
            if (empty($card_info)) {
                $this->error('会员卡不存在');
            } else {
                $this->assign('card', $card_info);
                $this->display();
            }
        }
    }

    //消费记录
    public function consume_record()
    {
        $money_record = D('Card_new')->card_use_record($_GET['id']);
        $this->assign('record', $money_record['record']);
        $this->assign('pagebar', $money_record['pagebar']);
        $this->display();
    }



//会员卡优惠券
    public function card_new_coupon()
    {
        if (!empty($_GET['keyword'])) {
            if ($_GET['searchtype'] == 'id') {
                $condition_coupon['id'] = $_GET['keyword'];
            } else if ($_GET['searchtype'] == 'name') {
                $condition_coupon['name'] = array('like', '%' . $_GET['keyword'] . '%');
            }
        }
        //排序 /*/
        $order_string = '`coupon_id` DESC';
        if ($_GET['sort']) {
            switch ($_GET['sort']) {
                case 'uid':
                    $order_string = '`uid` DESC';
                    break;
                case 'lastTime':
                    $order_string = '`last_time` DESC';
                    break;
                case 'money':
                    $order_string = '`now_money` DESC';
                    break;
                case 'score':
                    $order_string = '`score_count` DESC';
                    break;
            }
        }
        $condition_coupon['mer_id'] = $this->merchant_session['mer_id'];
        $coupon = M('Card_new_coupon');
        $count_count = $coupon->where($condition_coupon)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count_count, 15);
        $coupon_list = $coupon->field(true)->where($condition_coupon)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
        foreach ($coupon_list as $key => &$v) {
            $v['platform'] = unserialize($v['platform']);
            if ($v['cate_name'] != 'all' && !empty($v['cate_id'])) {
                $tmp = unserialize($v['cate_id']);
                $v['cate_id'] = $tmp['cat_name'];
            }
            if ($v['end_time'] < time()) {
                D('System_coupon')->where(array('coupon_id' => $v['coupon_id']))->setField('status', 2);
                $v['status'] = 2;
            }

        }
        $return = D('Card_new_coupon')->cate_platform();
        $this->assign("category", $return['category']);
        $this->assign("platform", $return['platform']);
        $this->assign('coupon_list', $coupon_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function add_coupon()
    {
        if (IS_POST) {
            $now_card = M('Card_new')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
            if (strtotime($_POST['end_time']) < strtotime($_POST['start_time']) || strtotime($_POST['end_time']) < time() || strtotime($_POST['start_time']) < strtotime(date('Y-m-d'))) {
                $this->error(L('STRT_WRONG'));
            }
            if ($_POST['limit'] > $_POST['num']) {
                $this->error('领取限制不能大于数量！');
            }
            if ($_POST['use_limit'] > $_POST['limit'] || $_POST['use_limit'] > $_POST['num']) {
                $this->error('使用限制设置错误，不能大于领取限制和数量！');
            }
            if ($_POST['cate_name'] != 'all') {
                if ($_POST['cate_id'] != 0) {
                    if ($_POST['cate_name'] == 'meal') {
                        $cate_id = D(ucfirst($_POST['cate_name']) . '_store_category')->field('cat_id,cat_name')->where(array('cat_id' => $_POST['cate_id']))->find();
                    } else {
                        $cate_id = D(ucfirst($_POST['cate_name']) . '_category')->field('cat_id,cat_name')->where(array('cat_id' => $_POST['cate_id']))->find();
                    }
                    $_POST['cate_id'] = serialize($cate_id);
                }
            } else {
                $_POST['cate_id'] = 0;
            }
            $data['platform'] = serialize($_POST['platform']);
            unset($_POST['dosubmit']);
            unset($_POST['platform']);
            $image = D('Image')->handle($this->merchant_session['mer_id'], 'card_new_coupon', 1);
            if (!$image['error']) {
                $data = array_merge($data, $image['url']);
            }
//            if(strlen($_POST['des'])>){
//
//            }
            $data = array_merge($data, $_POST);
            $data['start_time'] = strtotime($data['start_time']);
            $data['end_time'] = strtotime($data['end_time']) + 86399;//到 23:59:59
            $data['add_time'] = $data['last_time'] = time();
            $data['mer_id'] = $this->merchant_session['mer_id'];
            $data['card_id'] = $now_card['card_id'];
            $data['notice'] =$_POST['notice'];
            $data['auto_get'] =$_POST['auto_get'];
            //dump($data);die;
            if ($id = M('Card_new_coupon')->add($data)) {
                $errorms= '';
                if($this->config['coupon_wx_sync']) {
                    if($_POST['sync_wx']) {
                        import('@.ORG.weixincard');
                        import('ORG.Net.Http');
                        $http	=	new Http();
                        $mode = D('Access_token_expires');
                        $res = $mode->get_access_token();
                        $param['logo_url'] = $this->config['site_logo'];
                        $param['brand_name'] = $_POST['brand_name'];
                        $param['title'] = $_POST['name'];
                        $param['color'] = $_POST['color'];
                        $param['notice'] = $_POST['notice'];
                        $param['phone'] = $this->config['site_phone'];
                        $param['description'] = $_POST['des'];
                        $param['begin_time'] = $data['start_time'];
                        $param['end_time'] = $data['end_time'];
                        $param['num'] = $_POST['num'];
                        $param['limit'] = $_POST['limit'];
                        $param['center_title'] = '立即使用';
                        $param['center_sub_title'] = $_POST['center_sub_title'];
                        $param['center_url'] = html_entity_decode($_POST['center_url']);
                        $param['custom_url_name'] = $_POST['custom_url_name'];
                        $param['custom_url'] = html_entity_decode($_POST['custom_url']);
                        $param['custom_url_sub_title'] = $_POST['custom_url_sub_title'];
                        $param['promotion_url'] = html_entity_decode($_POST['promotion_url']);
                        $param['promotion_url_name'] = '更多优惠';
                        $param['icon_url_list'] = $_POST['icon_url_list']; //封面图片
                        $param['abstract'] = $_POST['abstract']; //封面图片
                        $param['share_friends'] = $_POST['share_friends'];
                        foreach ($_POST['image_url'] as $k => $v) {
                            $text_image_list[] = array(
                                'image_url' => $v,
                                'text' => $_POST['text'][$k],
                            );
                        }

                        $param['text_image_list'] = $text_image_list;
                        $param['business_service'] = $_POST['business_service'];
                        $param['least_cost'] = $_POST['order_money']*100;
                        $param['reduce_cost'] = $_POST['discount']*100;
                        $param['res'] = $res;
                        $card = new Create_card($param);
                        $cardinfo = $card->create();
                        $ticket = $cardinfo['ticket'];
                        $qrcode_url = $cardinfo['qrcode_url'];
                        $return = $cardinfo['return'];
                    }
                    $wx_data['sync_wx'] = $_POST['sync_wx'];
                    unset($param['res']);
                    $wx_data['wx_param'] = serialize($param);
                    $errormsg = '';
                    if ($return['errcode'] == 0) {
                        $wx_data['wx_cardid'] = $return['card_id'];
                        $wx_data['jsapi_ticket'] = $ticket['ticket'];
                        $wx_data['expires_in'] = $ticket['expires_in'];
                        $wx_data['wx_qrcode'] = $qrcode_url['show_qrcode_url'];
                        $wx_data['wx_ticket_addtime'] = $_SERVER['REQUEST_TIME'];
                        $wx_data['is_wx_card'] = 1;
//                        unset($param['res']);
//                        $wx_data['wx_param'] = serialize($param);
                        $wx_data['cardsign'] = sha1($wx_data['wx_ticket_addtime'] . $ticket['ticket'] . $return['card_id']);
                        M('Card_new_coupon')->where(array('coupon_id' => $id))->save($wx_data);
                        $errormsg = $return['errmsg'];

                    } else {
                        $wx_data['is_wx_card'] = 0;
                        $errormsg = $return['errmsg'];
                        $wx_data['weixin_err'] = serialize($errormsg);
                        M('Card_new_coupon')->where(array('coupon_id' => $id))->save($wx_data);

                    }
                }

                $this->success('添加优惠券成功！'.$errormsg);
            } else {
                $this->error('添加失败！');
            }
        } else {
            $return = D('Card_new_coupon')->cate_platform();
            $color_list =  D('System_coupon')->color_list();
            $this->assign("color_list",$color_list);
            $this->assign("category", $return['category']);
            $this->assign("platform", $return['platform']);
            $this->display();
        }
    }

    public function edit_coupon()
    {
        if (IS_POST) {
            $add = pow(-1, (int)$_POST['add']);
            $_POST['num'] += $add * (int)$_POST['num_add'];//数量增减
            if ((int)$_POST['num'] < (int)$_POST['had_pull']) {
                $this->error('更新优惠券数量有误，不能小于已领取的数量！');
            } else if (strtotime($_POST['end_time']) < strtotime($_POST['start_time'])) {
                $this->error(L('STRT_WRONG'));
            }

            if ($_POST['num'] > $_POST['had_pull'] && $_POST['status'] == 3) {
                if ($_POST['end_time'] > time()) {
                    $_POST['status'] = 1;
                }
            }
            if ($_POST['num'] <= $_POST['had_pull']) {
                $_POST['status'] = 3;
            }

            unset($_POST['dosubmit']);
            $data = $_POST;
            $image = D('Image')->handle($this->merchant_session['mer_id'], 'card_new_coupon', 1);
            if (!$image['error']) {
                $data = array_merge($data, $image['url']);
            }
            $data['last_time'] = time();
            $data['mer_id'] = $this->merchant_session['mer_id'];

            if (M('Card_new_coupon')->where(array('coupon_id' => $_POST['coupon_id']))->save($data)) {
                $errorms= '';
                if($this->config['coupon_wx_sync']) {
                    if ($_POST['is_wx_card']) {
                        import('ORG.Net.Http');
                        $res = D('Access_token_expires')->get_access_token();
                        //修改描述
                        $update_info['card_id'] = $_POST['wx_cardid'];
                        if ($image['url']['img']) {
                            $update_info['general_coupon']['base_info']['logo_url'] = $image['url']['img'];
                        }
                        $update_info['general_coupon']['base_info']['description'] = $_POST['des_detial'];
                        $update_wx_card = httpRequest('https://api.weixin.qq.com/card/update?access_token=' . $res['access_token'], 'post', json_encode($update_info, JSON_UNESCAPED_UNICODE));
                        $update_wx_card = json_decode($update_wx_card[1], true);

                        //修改库存
                        $wx_data['card_id'] = $_POST['wx_cardid'];
                        $wx_data['increase_stock_value'] = $add > 0 ? $_POST['num_add'] : 0;
                        $wx_data['reduce_stock_value'] = $add < 0 ? $_POST['num_add'] : 0;
                        $update_wx_card = httpRequest('https://api.weixin.qq.com/card/modifystock?access_token=' . $res['access_token'], 'post', json_encode($wx_data, JSON_UNESCAPED_UNICODE));
                        $update_wx_card = json_decode($update_wx_card[1], true);
                        $errorms = $update_wx_card['errmsg'];
                    }
                }

                $this->success('保存成功！'.$errorms);
            } else {
                $this->error('保存失败！');
            }
        } else {
            $return = D('Card_new_coupon')->cate_platform(); //模板中定义相关中文名字
            $this->assign("category", $return['category']);
            $coupon = D('Card_new_coupon')->where(array('coupon_id' => $_GET['coupon_id']))->find();
            $coupon['platform'] = unserialize($coupon['platform']);
            $coupon['now_num'] = $coupon['now_num'];
            foreach ($coupon['platform'] as &$vv) {
                $vv = $return['platform'][$vv];
            }
            $coupon['platform'] = implode(',', $coupon['platform']);
            $coupon['cate_name'] = $coupon['cate_name'] == 'all' ? '全部类别' : $return['category'][$coupon['cate_name']];
            if (empty($coupon['cate_id'])) {
                $coupon['cate_id'] = '全部分类';
            } else {
                $coupon['cate_id'] = unserialize($coupon['cate_id']);
                $coupon['cate_id'] = $coupon['cate_id']['cat_name'];
            }

            $this->assign("coupon", $coupon);
            $this->display();
        }
    }

    public function ajax_ordertype_cateid()
    {
        if ($_POST['order_type'] == 'meal') {
            $cate_id = D(ucfirst($_POST['order_type']) . '_store_category')->field('cat_id,cat_name')->where(array('cat_status' => 1, 'cat_fid' => 0))->select();
        } else {
            $cate_id = D(ucfirst($_POST['order_type']) . '_category')->field('cat_id,cat_name')->where(array('cat_status' => 1, 'cat_fid' => 0))->select();
        }
        echo json_encode($cate_id);
    }

    //优惠券领取记录
    public function  had_pull()
    {
        $now_card = M('Card_new')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
        $where['card_id'] = $now_card['card_id'];
        $order_string = 'h.receive_time DESC ,h.id DESC';
        $coupon = M('Card_new_coupon_hadpull');
        $count_count = $coupon->where($where)->count();
        import('@.ORG.merchant_page');
        $p = new Page($count_count, 15);
        $coupon_list = $coupon->join('as h left join ' . C('DB_PREFIX') . 'card_new_coupon c ON h.coupon_id = c.coupon_id')->join(C('DB_PREFIX') . 'user u ON h.phone = u.phone')->field('h.id,c.name,u.nickname,h.num,h.receive_time,h.is_use,h.phone')->where($where)->order($order_string)->limit($p->firstRow . ',' . $p->listRows)->select();
        $this->assign('coupon_list', $coupon_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    //编辑分组
    public function card_group(){
        $card_group_count = M('Card_group')->count();
        import('@.ORG.merchant_page');
        $p = new Page($card_group_count, 15);
        $card_group_list = M('Card_group')->where(array('mer_id'=>$this->merchant_session['mer_id']))->limit($p->firstRow . ',' . $p->listRows)->select();
        foreach ($card_group_list as &$v) {
            $v['user_count'] = M('Card_userlist')->where(array('mer_id'=>$this->merchant_session['mer_id'],'gid'=>$v['id']))->count();
        }
        $this->assign('card_group_list', $card_group_list);
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->display();
    }

    public function add_card_group(){
        if($_POST){
            $_POST['mer_id'] = $this->merchant_session['mer_id'];
            if(empty($_POST['gid'])){
                $res = M('Card_group')->add($_POST);
            }else{
                $date['mer_id'] = $_POST['mer_id'];
                $date['name'] = $_POST['name'];
                $date['des'] = $_POST['des'];
                $res = M('Card_group')->where(array('id'=>$_POST['gid']))->save($date);
            }
            if($res){
                $this->success('保存成功！');
            }else {
                $this->error('保存失败！');
            }
        }else{
            if(!empty($_GET['gid'])){
                $now_group = M('Card_group')->where(array('id'=>$_GET['gid']))->find();
                $this->assign('now_group',$now_group);
            }
            $this->display();

        }
    }

    //分组用户列表
    public function card_group_user_list(){
        $gid = $_GET['gid'];
        $mer_id  = $this->merchant_session['mer_id'];
        $user_list = D('Card_new')->get_user_by_gid_merid($gid,$mer_id);
        $this->assign('user_list',$user_list);
        $this->display();
    }

    //派发优惠券
    public function send_coupon(){
        $mer_id  =$this->merchant_session['mer_id'];
        $coupon_list = D('Card_new_coupon')->get_coupon_list_by_merid($mer_id);
        if(empty($coupon_list)){
            $this->error('没有可分配的优惠券!');
        }
        $this->assign('coupon_list',$coupon_list);
        $card_group_list = M('Card_group')->where(array('mer_id'=>$this->merchant_session['mer_id']))->select();
        foreach ($card_group_list as &$v) {
            $v['user_count'] = M('Card_userlist')->where(array('mer_id'=>$this->merchant_session['mer_id'],'gid'=>$v['id']))->count();
        }
        $this->assign('card_group',$card_group_list);
        $this->display();
    }

    //派发记录
    public function send_history(){
        $mer_id  =$this->merchant_session['mer_id'];
        import('@.ORG.merchant_page');
        $count = M('Card_coupon_send_history')->where(array('mer_id'=>$mer_id))->count();
        $p = new Page($count, 15);
        $res = M('Card_coupon_send_history')->join('as h left join '.C('DB_PREFIX').'user u ON h.uid = u.uid')
            ->join(C('DB_PREFIX').'card_new_coupon c ON h.coupon_id = c.coupon_id')->field('h.*,u.nickname,c.name as coupon_name')->where(array('h.mer_id'=>$mer_id))->order('add_time DESC')->limit($p->firstRow . ',' . $p->listRows)->select();
        $pagebar = $p->show();
        $this->assign('pagebar', $pagebar);
        $this->assign('history',$res);

        $this->display();
    }

    public function ajax_send(){
        $mer_id  =$this->merchant_session['mer_id'];
        $coupon_list = explode(',',$_POST['coupon_list']);
        $uid = $_POST['uid'];
        $model = D('Card_new_coupon');
        foreach ($coupon_list as $item) {
            $tmp = $model->had_pull($item,$uid);
            $model->decrease_sku(0,1,$item);//网页领取完，微信卡券库存需要同步减少
            $tmp['msg'] = $this->coupon_error($tmp['error_code']);
            $data['uid'] = $uid;
            $data['mer_id']  = $mer_id;
            $data['coupon_id'] = $item;
            $data['error_code']  =$tmp['error_code'];
            $data['msg']  =$tmp['msg'];
            $data['add_time']  =time();
            M('Card_coupon_send_history')->add($data);
            $return[$tmp['coupon']['coupon_id']]['error_msg'] = $tmp['msg'];
            $return[$tmp['coupon']['coupon_id']]['coupon_name'] = $tmp['coupon']['name'];
            $return[$tmp['coupon']['coupon_id']]['send_code'] = $tmp['error_code'];
        }
        $this->ajaxReturn(array('error_code'=>0,'return'=>$return));
    }

    public function coupon_error($error_code){
        switch($error_code) {
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
        return $error_msg;
    }

    public function send(){
        if($_GET['card_group_id']){
            $res = D('Card_new')->get_userlist_by_car_group($_GET['card_group_id']);
            $this->assign('user_list',$res);
        }else if($_GET['uid']){
            $user = M('User')->where(array('uid'=>$_GET['uid']))->field('uid,nickname')->find();
            $this->assign('user',$user);
        }
        $this->assign('coupon_list',$_GET['coupon_id']);
        $this->display();
    }

    //获取可以派发的优惠券列表
    public function ajax_get_send_coupon(){
        if(!empty($_POST['card_group_id'])){
            $count = M('Card_userlist')->where(array('gid'=>array('in',$_POST['card_group_id'])))->count();
            $coupon_list  = D('Card_new_coupon')->get_coupon_list_by_merid($this->merchant_session['mer_id']);
            foreach ($coupon_list as &$v) {
                if($count>$v['num']-$v['had_pull']){
                    $v['disable'] = true;
                }else{
                    $v['disable'] = false;
                }
            }
        }else{
            $coupon_list  = D('Card_new_coupon')->get_coupon_list_by_merid($this->merchant_session['mer_id']);

        }
        $this->ajaxReturn($coupon_list);
    }

    public function ajax_get_user_merid(){
        $where['c.mer_id'] = $this->merchant_session['mer_id'];
        $where['u.'.$_POST['keyword']] = array('like',"%".$_POST['search_val']."%");
        $res = M('Card_userlist')->join('as c left join '.C('DB_PREFIX').'user u ON c.uid = u.uid')->field('c.*,u.nickname,u.phone')->where($where)->select();
        $this->ajaxReturn($res);
    }

    #微信购买派发设置
    public function weixin_send(){
        $mer_id  =$this->merchant_session['mer_id'];
        $now_card = M('Card_new')->where(array('mer_id'=>$this->merchant_session['mer_id']))->find();
        if(!$now_card['weixin_send']){
            $this->error('没有开启微信购买派送优惠券功能!');
        }
        $coupon_list = D('Card_new_coupon')->get_coupon_list_by_merid($mer_id);
        if(empty($coupon_list)){
            $this->ajaxReturn(array('error_code'=>1,'msg'=>'没有可分配的优惠券!'));
        }
        $now_card['weixin_send_couponlist'] = explode(',',$now_card['weixin_send_couponlist']);
        $this->assign('coupon_list',$coupon_list);
        $this->assign('now_card',$now_card);
        if(IS_POST){
            if(!is_numeric($_POST['money'])||$_POST['money']<0){
                $this->ajaxReturn(array('error_code'=>1,'msg'=>'金额设置错误!'));
            }
            $data['weixin_send_money'] = $_POST['money'];
            $data['weixin_send_couponlist'] = $_POST['coupon_id'];

            if(M('Card_new')->where(array('mer_id'=>$mer_id))->save($data)){
                $this->ajaxReturn(array('error_code'=>0,'msg'=>'保存成功!'));
            }
        }else{
            $this->display();
        }
    }


    public function recharge_list(){
        $mer_id  =$this->merchant_session['mer_id'];
        import('@.ORG.merchant_page');
        $where['u.mer_id'] = $mer_id;
        $count = M('Card_new_record')->join('as c left join '.C('DB_PREFIX').'card_userlist as u ON c.card_id = u.id')->where($where)->count();
        $p = new Page($count,20);
        $result =  M('Card_new_record')->join('as c left join '.C('DB_PREFIX').'card_userlist as u ON c.card_id = u.id')->where($where)->order('c.time DESC')->limit($p->firstRow,$p->listRows)->select();
        $pagebar=$p->show();
        $this->assign('record',$result);
        $this->assign('pagebar',$pagebar);
        $this->display();
    }


    public function show(){
        echo '<html><head><style>*{margin:0;padding:0;}</style></head><body><img style="width:320px;height:600px;float:left" src="./static/images/wx_coupon.png" />
	<img style="width:320px;height:600px;float:left" src="./static/images/wx_coupon2.png" /></body></html>';die;

    }


}