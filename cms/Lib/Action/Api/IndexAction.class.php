<?php
/**
 * Created by PhpStorm.
 * User: Garfunkel
 * Date: 2018/8/20
 * Time: 14:57
 */

class IndexAction extends BaseAction
{
    public $mail;

    public function index(){
        if($_POST['lat'] != 'null' && $_POST['long'] != 'null'){
            $lat = $_POST['lat'];
            $long = $_POST['long'];
        }
        $city_id = $_POST['city_id'] ? $_POST['city_id'] : -1;
        //v2.6.1添加
        $userId = $_POST['uid'] ? $_POST['uid'] : 0;

        //顶部广告
        if($city_id == -1) {
            if($userId != 0) {
                D('User_adress')->where(array('uid'=>$userId))->save(array('default'=>0));
            }
            $city_id = $this->loadModel()->geocoderGoogle($lat, $long);
        }

        $_COOKIE['userLocationCity'] = $city_id;

        $head_adver = D('Adver')->get_adver_by_key('app_index_top',5);
        if(empty($head_adver)){
            $head_adver = D('Adver')->get_adver_by_key('wap_index_top',5);
        }
        if(!empty($head_adver)){
            $banner = array();
            foreach($head_adver as &$head_adver_value){
                //unset($head_adver_value['id'],$head_adver_value['bg_color'],$head_adver_value['cat_id'],$head_adver_value['status'],$head_adver_value['last_time'],$head_adver_value['sub_name']);
                $b_one = array();
                $b_one['id'] = $head_adver_value['id'];
                $b_one['url'] = $head_adver_value['pic'];
                $b_one['zt_url'] = $head_adver_value['url'];
                $b_one['title'] = $head_adver_value['name'];

                $banner[] = $b_one;
            }
            $arr['banner']['status'] = 1;
            $arr['banner']['info'] = $banner;
        }else{
            $arr['banner']['status'] = 0;
            $arr['banner']['info'] = array();
        }

        //获取店铺列表
        $page	=	$_POST['page']?$_POST['page']:0;
        $limit = 60;

        $sort = $_POST['sort'] ? $_POST['sort'] : 0;

        if($sort == 0) $order = 'juli';
        else $order = 'create_time';

        $deliver_type =  'all';

        $cat_id = 0;
        $cat_fid = 0;

        $key = '';
        $where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page,'limit'=>$limit);
        $key && $where['key'] = $key;

        $getMenuVersion = -1;//-1 全部 1只有version=1的 2
        if($this->app_version < 266)
            $getMenuVersion = 1;

        $shop_list = D('Merchant_store_shop')->get_list_arrange($where,3,1,$limit,$page,$lat,$long,$city_id,$getMenuVersion);
//
//        foreach ($shop_list as $k => $v) {
//            $product_list = D('Shop_goods')->get_list_by_storeid($v['site_id']);
//            $shop_list[$k]['goods'] = $product_list;
//        }

        if(!$shop_list['list']){
            $shop_list['list'] = array();
            $shop_list['count'] = '0';
        }

        $arr['best']['status'] = 1;
        $arr['best']['info'] = $shop_list['list'];
        $arr['best']['count'] = $shop_list['count'];
        //获取顶级分类
        //$city_id = $_COOKIE['userLocationCity'] ? $_COOKIE['userLocationCity'] : 0;
        //$city_id = 105;
        $category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>0,'city_id'=>$city_id))->order('cat_sort desc')->select();
        if(count($category) == 0){
            $category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>0,'city_id'=>0))->order('cat_sort desc')->select();
        }
        $nav_list = array();
        $categoryList = array();
        foreach ($category as $v){
            $nav['title'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
            $nav['image'] = 'https://www.tutti.app/static/images/category/'.$v['cat_url'].'.png?v=1.2.0';
            $nav['id'] = $v['cat_id'];
            $categoryList[] = $v['cat_id'];
            $nav_list[] = $nav;
        }
        $arr['nav'] = $nav_list;

        $sub_where['cat_fid'] = array('in',$categoryList);
        $sub_where['cat_status'] = 1;
        $subCategory = D('Shop_category')->where($sub_where)->order('cat_sort desc')->select();
        $sub_nav_list = array();
        foreach ($subCategory as $v){
            $sub_nav['title'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
            $cate_image_class = new category_image();
            if($v['cat_img'] != '') {
                $sub_nav['image'] = $cate_image_class->get_image_by_path($v['cat_img']);
            }else{
                $sub_nav['image'] = '';
            }
            $sub_nav['id'] = $v['cat_id'];
            $sub_nav['fid'] = $v['cat_fid'];

            $sub_nav_list[] = $sub_nav;
        }
        $arr['sub_nav'] = $sub_nav_list;


        //如果城市为自定义的话 修改type值 返回数组
        import('@.ORG.RegionalCalu.RegionalCalu');
        $region = new RegionalCalu();
        if(!$region->index($city_id,$long,$lat)){
            $recommend_list = array();
        }else {
            //获取推荐列表
            $re_category = D('Shop_category')->field(true)->where(array('cat_fid' => 0, 'cat_type' => 1, 'city_id' => $city_id))->order('cat_sort desc')->select();
            $categoryList = array();
            foreach ($re_category as $v) {
                $categoryList[] = $v['cat_id'];
            }
            $sub_where['cat_fid'] = array('in', $categoryList);
            $sub_where['cat_status'] = 1;
            $subCategory = D('Shop_category')->where($sub_where)->order('cat_sort desc')->select();
            $recommend_list = array();

            foreach ($subCategory as $v) {
                $sub_recommend['title'] = lang_substr($v['cat_name'], C('DEFAULT_LANG'));
                $sub_recommend['id'] = $v['cat_id'];
                $sub_recommend['fid'] = $v['cat_fid'];
                $sub_recommend['info'] = array();
                $closeArr = array();
                $openArr = array();
                $storeList = D('Shop_category_relation')->where(array('cat_id' => $v['cat_id']))->order('store_sort desc')->select();
                $allClose = true;
                foreach ($storeList as $store) {
                    $store_where = array('st.store_id' => $store['store_id']);
                    if ($this->app_version < 266) $store_where['st.menu_version'] = 1;

                    $storeRow = D('Merchant_store')->field('st.*,sh.background,sh.delivery_radius')->join('as st left join ' . C('DB_PREFIX') . 'merchant_store_shop sh on st.store_id = sh.store_id ')->where($store_where)->find();
                    if ($storeRow) {
                        $storeMemo['store_id'] = $storeRow['store_id'];
                        $storeMemo['name'] = lang_substr($storeRow['name'], C('DEFAULT_LANG'));
                        if ($storeRow['background'] && $storeRow['background'] != '') {
                            $image_tmp = explode(',', $storeRow['background']);
                            $storeMemo['background'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
                        } else {
                            $storeMemo['background'] = '';
                        }
                        $storeMemo['txt_info'] = $storeRow['txt_info'];
                        $storeMemo['is_close'] = 1;

                        //@wangchuanyuan 周一到周天
                        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
                        $now_time = date('H:i:s');
                        switch ($date) {
                            case 1 :
                                if ($storeRow['open_1'] != '00:00:00' || $storeRow['close_1'] != '00:00:00') {
                                    if ($storeRow['open_1'] < $now_time && $now_time < $storeRow['close_1']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_2'] != '00:00:00' || $storeRow['close_2'] != '00:00:00') {
                                    if ($storeRow['open_2'] < $now_time && $now_time < $storeRow['close_2']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_3'] != '00:00:00' || $storeRow['close_3'] != '00:00:00') {
                                    if ($storeRow['open_3'] < $now_time && $now_time < $storeRow['close_3']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                break;
                            case 2 ://周二
                                if ($storeRow['open_4'] != '00:00:00' || $storeRow['close_4'] != '00:00:00') {
                                    if ($storeRow['open_4'] < $now_time && $now_time < $storeRow['close_4']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_5'] != '00:00:00' || $storeRow['close_5'] != '00:00:00') {
                                    if ($storeRow['open_5'] < $now_time && $now_time < $storeRow['close_5']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_6'] != '00:00:00' || $storeRow['close_6'] != '00:00:00') {
                                    if ($storeRow['open_6'] < $now_time && $now_time < $storeRow['close_6']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                break;
                            case 3 ://周三
                                if ($storeRow['open_7'] != '00:00:00' || $storeRow['close_7'] != '00:00:00') {
                                    if ($storeRow['open_7'] < $now_time && $now_time < $storeRow['close_7']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_8'] != '00:00:00' || $storeRow['close_8'] != '00:00:00') {
                                    if ($storeRow['open_8'] < $now_time && $now_time < $storeRow['close_8']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_9'] != '00:00:00' || $storeRow['close_9'] != '00:00:00') {
                                    if ($storeRow['open_9'] < $now_time && $now_time < $storeRow['close_9']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                break;
                            case 4 :
                                if ($storeRow['open_10'] != '00:00:00' || $storeRow['close_10'] != '00:00:00') {
                                    if ($storeRow['open_10'] < $now_time && $now_time < $storeRow['close_10']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_11'] != '00:00:00' || $storeRow['close_11'] != '00:00:00') {
                                    if ($storeRow['open_11'] < $now_time && $now_time < $storeRow['close_11']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_12'] != '00:00:00' || $storeRow['close_12'] != '00:00:00') {
                                    if ($storeRow['open_12'] < $now_time && $now_time < $storeRow['close_12']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                break;
                            case 5 :
                                if ($storeRow['open_13'] != '00:00:00' || $storeRow['close_13'] != '00:00:00') {
                                    if ($storeRow['open_13'] < $now_time && $now_time < $storeRow['close_13']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_14'] != '00:00:00' || $storeRow['close_14'] != '00:00:00') {
                                    if ($storeRow['open_14'] < $now_time && $now_time < $storeRow['close_14']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_15'] != '00:00:00' || $storeRow['close_15'] != '00:00:00') {
                                    if ($storeRow['open_15'] < $now_time && $now_time < $storeRow['close_15']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                break;
                            case 6 :
                                if ($storeRow['open_16'] != '00:00:00' || $storeRow['close_16'] != '00:00:00') {
                                    if ($storeRow['open_16'] < $now_time && $now_time < $storeRow['close_16']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_17'] != '00:00:00' || $storeRow['close_17'] != '00:00:00') {
                                    if ($storeRow['open_17'] < $now_time && $now_time < $storeRow['close_17']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_18'] != '00:00:00' || $storeRow['close_18'] != '00:00:00') {
                                    if ($storeRow['open_18'] < $now_time && $now_time < $storeRow['close_18']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                break;
                            case 0 :
                                if ($storeRow['open_19'] != '00:00:00' || $storeRow['close_19'] != '00:00:00') {
                                    if ($storeRow['open_19'] < $now_time && $now_time < $storeRow['close_19']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_20'] != '00:00:00' || $storeRow['close_20'] != '00:00:00') {
                                    if ($storeRow['open_20'] < $now_time && $now_time < $storeRow['close_20']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                if ($storeRow['open_21'] != '00:00:00' || $storeRow['close_21'] != '00:00:00') {
                                    if ($storeRow['open_21'] < $now_time && $now_time < $storeRow['close_21']) {
                                        $storeMemo['is_close'] = 0;
                                    }
                                }
                                break;
                            default :
                                $storeMemo['is_close'] = 1;
                        }
                        //garfunkel add
                        if ($storeRow['store_is_close'] != 0) {
                            $storeMemo['is_close'] = 1;
                        }

                        if ($storeRow['status'] == 1) {
                            $distance = getDistance($lat, $long, $storeRow['lat'], $storeRow['long']);
                            if ($distance < $storeRow['delivery_radius'] * 1000) {
                                if ($storeMemo['is_close'] == 0) {
                                    $allClose = false;
                                    $openArr[] = $storeMemo;
                                } else {
                                    $closeArr[] = $storeMemo;
                                }
                            }
                        }
                    }
                }
                $sub_recommend['info'] = array_slice(array_merge($openArr, $closeArr), 0, 5);

                if (count($sub_recommend['info']) > 0 && !$allClose) {
                    $recommend_list[] = $sub_recommend;
                }
            }
        }
        $arr['recommend'] = $recommend_list;
        $arr['city_id'] = $city_id;

        //获取系统消息 $from 0Wap 1iOS 2Android

        $arr['system_message'] = D("System_message")->getSystemMessage($_POST['from'],$_POST['version'],$city_id,$lat,$long);

        $this->returnCode(0,'data',$arr);
    }

    public function getShopByCategory(){
        //获取店铺列表
        $page	=	$_POST['page']?$_POST['page']:0;
        $limit = 10;

        $order = 'juli';
        $deliver_type =  'all';

        $lat = $_POST['lat'];
        $long = $_POST['lng'];

        $cat_id = intval($_POST['cate_id']);
        $cat_fid = intval($_POST['category']);

        $city_id = $_POST['city_id'] ? $_POST['city_id'] : -1;

        if($cat_id == 0)
            $cat_where['cat_id'] = $cat_fid;
        else
            $cat_where['cat_id'] = $cat_id;

        $category = D('Shop_category')->field(true)->where(array('cat_id'=>$cat_fid))->find();
        $result['cat_name'] = lang_substr($category['cat_name'], C('DEFAULT_LANG'));

        $getMenuVersion = -1;//-1 全部 1只有version=1的 2
        if($this->app_version < 266)
            $getMenuVersion = 1;

        $where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page, 'limit' => $limit);
        if($category['cat_type'] == 1){
            if($page == 1)
                $shop_list = D('Merchant_store_shop')->get_list_arrange($where,1,2,$limit,$page,$lat,$long,$city_id,$getMenuVersion);
        }else {
            $key = '';
            if ($_POST['keyword']) {
                $key = $_POST['keyword'];
                $key && $where['key'] = $key;
                $shop_list = D('Merchant_store_shop')->get_list_arrange($where, 1, 1, $limit, $page, $lat, $long,$city_id,$getMenuVersion);
            } else {
                $shop_list = D('Merchant_store_shop')->get_list_arrange($where, 3, 1, $limit, $page, $lat, $long,$city_id,$getMenuVersion);
            }

        }

        if(!$shop_list['list']){
            $shop_list['list'] = array();
            $shop_list['count'] = '0';
        }

        $result['info'] = $shop_list['list'];
        $result['count'] = $shop_list['count'];

        $this->returnCode(0,'',$result,'success');
    }

    public function getCategorySubList(){
        $cate_fid = $_POST['category'];
        $category = D('Shop_category')->field(true)->where(array('cat_fid'=>$cate_fid,'cat_status'=>1))->order('cat_sort desc')->select();
        $cat_type = 0;
        foreach ($category as &$v) {
            $v['cat_name'] = lang_substr($v['cat_name'], C('DEFAULT_LANG'));
            $v['cat_id'] = $v['cat_id'];
            $cat_type = $v['cat_type'];
        }
        $all['cat_name'] = 'All';
        $all['cat_id'] = 0;
        $all['cat_type'] = $cat_type;
        if($category)
            array_unshift($category,$all);
        else
            $category[] = $all;

        $this->returnCode(0,'info',$category);
    }

    public function getStore(){
        $sid = $_POST['sid'];
        $lat = $_POST['lat'] ? $_POST['lat'] : 0;
        $lng = $_POST['lng'] ? $_POST['lng'] : 0;
        $store = $this->loadModel()->get_store_by_id($sid,$lat,$lng);

        $this->returnCode(0,'info',$store);

    }
    public function loadModel(){
        $this_model = D('Store');

        return $this_model;
    }
    public function getStoreGoods(){
        $sid = $_POST['sid'];

        $t_store = $this->loadModel()->get_store_by_id($sid);
        //店铺状态
        $store['shopstatus'] = $t_store['is_close'] == 0 ? "1" : "0";
        $store['shopname'] = $t_store['site_name'];

        if($t_store['menu_version'] == 1) {
            $group = $this->loadModel()->get_goods_group_by_storeId($sid);

            $goods = $this->loadModel()->get_goods_by_storeId($sid);

            $store['group'] = $this->loadModel()->arrange_group_for_goods($group);
            $store['foods'] = $this->loadModel()->arrange_goods_for_goods($goods);
        }else if ($t_store['menu_version'] == 2){
            $categories = D('StoreMenuV2')->getStoreCategories($sid,true);
            $store['group'] = D('StoreMenuV2')->arrangeApp($categories);

            if($_POST['keyword'] && trim($_POST['keyword']) != "")
                $keyword = $_POST['keyword'];
            else
                $keyword = '';

            $goods = D('StoreMenuV2')->getStoreProductApp($categories,$_POST['uid'],$sid,$keyword);
            $store['foods'] = $goods;
        }
        $store['count'] = count($store['foods']);

        $this->returnCode(0,'info',$store);
    }

    public function user_third_Login(){
//        $userInfo = array("uid"=>"1","uname"=>"garfunkel","password"=>"123456","login_type"=>"1",
//            "outsrc"=>"http://thirdqq.qlogo.cn/qqapp/1106028245/ED09815DE876D237105B7BF6F40DEFCA/100",
//            "openid"=>"ED09815DE876D237105B7BF6F40DEFCA"
//        );
        $nickname = $_POST['nickname'];
        $openid = $_POST['openid'];
        $face_pic = htmlspecialchars_decode($_POST['face_pic']);
        $sex = $_POST['sex'];
        $province = $_POST['province'];
        $city = $_POST['city'];
        $type = $_POST['type'];

        $token = $_POST['token'];
        $email = $_POST['email'] ? $_POST['email'] : '';
        //type 01Tutti 2微信 3Facebook 4Google 5Apple
        $result = D('User')->autologin('openid', $openid,$type);

        if(!$result['user']){
            $data_user = array(
                'openid' 	=> $openid,
                'union_id' 	=> '',
                'nickname' 	=> $nickname,
                'sex' 		=> $sex,
                'province' 	=> $province,
                'city' 		=> $city,
                'avatar' 	=> $face_pic,
                'is_follow' => 1,
                'login_type'=> $type,
                'email'     => $email
            );
            $reg_result = D('User')->autoreg($data_user);
            if($reg_result['error_code']){
                $user['uid'] = '0';
            }else{
                $user = D('User')->get_user($openid,'openid');
            }
        }else{
            $user = $result['user'];
        }


        $userInfo['uid'] = $user['uid'];
        $userInfo['uname'] = $user['nickname'];
        $userInfo['password'] = $user['pwd'];
        $userInfo['outsrc'] = $user['avatar'];
        $userInfo['openid'] = $user['openid'];
        $userInfo['login_type'] = $type;
        //记录设备号
        if($token != '') {
            $from = $_POST['from'] ? $_POST['from'] : 0;

            switch ($from){
                case 1:
                    $source = 'Wap';
                    break;
                case 2:
                    $source = 'App';
                    break;
                case 3:
                    $source = 'App';
                    break;
                case 4:
                    $source = 'Android';
                    break;
                default:
                    $source = 'Web';
                    break;
            }
            D('User')->where(array('uid' => $userInfo['uid']))->save(array('device_id' => $token,'source'=>$source));
        }

        $this->returnCode(0,'info',$userInfo);
    }

    public function userLogin(){
        $userName = $_POST['userName'];
        $password = $_POST['password'];
        $token = $_POST['token'];

        $userInfo = $this->loadModel()->getUserInfo($userName,$password);

        if($userInfo['msg'] != "")
            $code = 1;
        else{
            $code = 0;
            D('User')->where(array('uid'=>$userInfo['uid']))->save(array('device_id'=>$token));
        }

        $this->returnCode($code,'info',$userInfo,$userInfo['msg']);
    }

    public function getVerificationCode(){
        $phone = $_POST['phone'];
        $result = $this->loadModel()->sendVerificationCode($phone);

        if ($result['error_code'])
            $code = 1;
        else
            $code = 0;

        $this->returnCode($code,'info',array(),$result['msg']);
    }

    public function sendForgetCode(){
        $phone = $_POST['phone'];

        if(empty($phone)){
            $this->returnCode(1,'info',array(),'No phone number');
        }

        $user = D('User')->where(array('phone'=>$phone))->find();
        if(!$user)
            $this->returnCode(1,'info',array(),'Phone Number Error');

        $vcode = createRandomStr(6,true,true);

        $sms_data['uid'] = 0;
        $sms_data['mobile'] = $phone;
        $sms_data['sendto'] = 'user';
        $sms_data['tplid'] = 169244;
        $sms_data['params'] = [
            $vcode
        ];
        //Sms::sendSms2($sms_data);

        $sms_txt = "This is your verification code for password recovery. Your code is: ".$vcode;
        //Sms::telesign_send_sms($phone,$sms_txt,2);
        Sms::sendTwilioSms($phone,$sms_txt);

        $user_modifypwdDb = M('User_modifypwd');
        $addtime = time();
        $expiry = $addtime + 5 * 60; /*             * **五分钟有效期*** */
        $data = array('telphone' => $phone, 'vfcode' => $vcode, 'expiry' => $expiry, 'addtime' => $addtime);
        $insert_id = $user_modifypwdDb->add($data);

        $this->returnCode(0,'info',array(),'Success');
    }

    public function userForget(){
        $phone = $_POST['phone'];
        $vcode = $_POST['vcode'];

        if(D('User_modifypwd')->where(array('vfcode'=>$vcode,'telphone'=>$phone))->find()){
            if($_POST['type'] == 1){
                $this->forgetToPassword();
            }else{
                $this->returnCode(0,'info',array(),'Success');
            }
        }else{
            $this->returnCode(1,'info',array(),L('_SMS_CODE_ERROR_'));
        }
    }

    public function forgetToPassword(){
        $phone = $_POST['phone'];
        $pwd = $_POST['password'];

        $data['pwd'] = md5($pwd);
        D('User')->where(array('phone'=>$phone))->save($data);

        $this->returnCode(0,'info',array(),'Success');
    }

    public function userForgotPwd(){
        $uid = $_POST['uid'];
        $vcode = $_POST['vcode'];
        $pwd = $_POST['new_pwd'];

        $user = D('User')->where(array('uid'=>$uid))->find();

        if(D('User_modifypwd')->where(array('vfcode'=>$vcode,'telphone'=>$user['phone']))->find()){
            $data['pwd'] = md5($pwd);
            D('User')->where(array('uid'=>$uid))->save($data);

            $this->returnCode(0,'info',array(),'Success');
        }else{
            $this->returnCode(1,'info',array(),L('_SMS_CODE_ERROR_'));
        }
    }

    public function oldToPassword(){
        $uid = $_POST['uid'];
        $old_pwd = $_POST['old_pwd'];
        $new_pwd = $_POST['new_pwd'];

        $user = D('User')->where(array('uid'=>$uid))->find();
        if(md5($old_pwd) != $user['pwd']){
            //$this->returnCode(1,'info',array(),L('_B_MY_WRONGKEY_'));
            $this->returnCode(1,'info',array(),L('_B_MY_WRONGKEY_'));
        }else{
            $data['pwd'] = md5($new_pwd);
            D('User')->where(array('uid'=>$uid))->save($data);
            $this->returnCode(0,'info',array(),'Success');
        }
    }

    public function userReg(){
        $phone = $_POST['phone'];
        $vcode = $_POST['vcode'];
        $pwd = $_POST['password'];
        $token = $_POST['token'];
        $invi_code = $_POST['invi_code'];
        $source = '';

        $from = $_POST['from'] ? $_POST['from'] : 0;

        switch ($from){
            case 1:
                $source = 'Wap';
                break;
            case 2:
                $source = 'App';
                break;
            case 3:
                $source = 'App';
                break;
            case 4:
                $source = 'Android';
                break;
            default:
                break;
        }

        $uname = $_POST['uname'] ? $_POST['uname'] : "";
        $email = $_POST['email'] ? $_POST['email'] : "";

        $result = $this->loadModel()->reg_phone_pwd_vcode($phone,$vcode,$pwd,$invi_code,$uname,$email);

        if ($result['error_code'])
            $code = 1;
        else{
            $code = 0;
            D('User')->where(array('uid'=>$result['uid']))->save(array('device_id'=>$token,'source'=>$source));
            //garfunkel add 查找是否有新用户送券活动 并添加优惠券
            D('New_event')->addEventCouponByType(1,$result['uid']);
        }

        $this->returnCode($code,'info',$result,$result['msg']);
    }

    public function getCommentByStore(){
        $sid = $_POST['sid'];
        $type = $_POST['type'];

        $result = $this->loadModel()->get_comment_sid($sid,$type);

        $this->returnCode(0,'',$result,$result['msg']);
    }

    public function addCart(){
        $uid = $_POST['uid'];
        $fid = $_POST['fid'];
        $sid = $_POST['storeId'] ? $_POST['storeId'] : "";
        $categoryId = $_POST['categoryId'] ? $_POST['categoryId'] : 0;


        $num = !empty($_POST['num']) ? $_POST['num']:1;
        $spec = empty($_POST['spec']) ? "" : $_POST['spec'];
        $proper = empty($_POST['proper']) ? "" : $_POST['proper'];
        $dish_id = empty($_POST['dish_id']) ? "" : $_POST['dish_id'];

        D('Cart')->add_cart($uid,$fid,$num,$spec,$proper,$dish_id,$sid,$categoryId);

        $this->returnCode(0,'info',array(),'success');
    }

    public function addCartAndSpec(){
        $uid = $_POST['uid'];
        $fid = $_POST['fid'];
        $sid = $_POST['storeId'] ? $_POST['storeId'] : "";
        $categoryId = $_POST['categoryId'] ? $_POST['categoryId'] : 0;

        $num = !empty($_POST['num']) ? $_POST['num']:1;
        $spec = $_POST['spec'];
        $proper = $_POST['proper'];
        if($_POST['dish']){
            $dish_id = $_POST['dish'];
        }else{
            $dish_id = "";
        }

        D('Cart')->add_cart($uid,$fid,$num,$spec,$proper,$dish_id,$sid,$categoryId);

        $this->returnCode(0,'info',array(),'success');
    }

    public function getCart(){
        $uid = $_POST['uid'];
        $storeId = $_POST['storeId'] ? $_POST['storeId'] : 0;

        $result = D('Cart')->get_cart($uid,$storeId);

        $this->returnCode(0,'',$result,'success');
    }

    public function delCart(){
        $uid = $_POST['uid'];
        $storeId = $_POST['storeId'] ? $_POST['storeId'] : 0;

        $result = D('Cart')->del_cart($uid,$storeId);

        $this->returnCode(0,'info',$result,'success');
    }

    public function getUserDefaultAddress(){
        $uid = $_POST['uid'];

        $adr = $this->loadModel()->getDefaultAdr($uid);

        if ($adr == null)
            $this->returnCode(1,'info',array(),'success');
        else{
            $result[] = $adr;
            $this->returnCode(0,'info',$result,'success');
        }
    }

    public function getUserAddress(){
        $uid = $_POST['uid'];
        $sid = $_POST['store_id'];

        $result = $this->loadModel()->getUserAdr($uid,$sid);

        $this->returnCode(0,'info',$result,'success');
    }

    public function addUserAddress(){
        $data['uid'] = $_POST['uid'];
        $data['adress_id'] = $_POST['itemid'];
        $data['name'] = html_entity_decode($_POST['uname']);
        $data['phone'] = $_POST['phone'];
        $data['adress'] = $_POST['map_addr'];
        $data['zipcode'] = $_POST['map_number'];
        $data['longitude'] = $_POST['lng'];
        $data['latitude'] = $_POST['lat'];
        $data['detail'] = html_entity_decode($_POST['map_location']);
        $data['default'] = $_POST['default'];
        if($_POST['city_name']){
            $city_name = $_POST['city_name'];
            //$where = array('area_name'=>$city_name,'area_type'=>2);
            //$area = D('Area')->where($where)->find();
            $city_id = 0;
            $area_pid = 0;
            $area_list = D('Area')->where(array('area_type'=>2))->select();
            foreach ($area_list as $city){
                $city_arr = explode("|",$city['area_ip_desc']);
                if(in_array($city_name,$city_arr)){
                    $city_id = $city['area_id'];
                    $area_pid = $city['area_pid'];
                }
            }
            if($city_id) {
                $data['area'] = 0;
                $data['city'] = $city_id;
                $data['province'] = $area_pid;
            }else{
                $data['area'] = 0;
                $data['city'] = 0;
                $data['province'] = 0;
            }
        }else{
            $_POST['city_name'] = "";
            $city_id = $this->loadModel()->geocoderGoogle($_POST['lat'], $_POST['lng']);
            $data['city'] = $city_id ? $city_id : 0;
        }

        $data['city_name'] = $_POST['city_name'];
        $result = $this->loadModel()->addUserAddress($data);

        $data['areaID'] = $data['city'];
        $data['address'] = $data['adress'];
        $data['city_name'] = $_POST['city_name'];

        if($this->app_version > 260)//当版本号大于260时 添加数据
            $this->returnCode(0,'info',$data,'success');
        else
            $this->returnCode(0,'info',array(),'success');
    }

    public function delUserAddress(){
        $uid = $_POST['uid'];
        $aid = $_POST['itemid'];

        $this->loadModel()->delUserAddress($uid,$aid);

        $this->returnCode(0,'info',array(),'success');
    }

    public function setDefaultAdr(){
        $uid = $_POST['uid'];
        $aid = $_POST['itemid'];

        $this->loadModel()->setDefaultAdr($uid,$aid);

        $this->returnCode(0,'info',array(),'success');
    }

    public function checkCart()
    {
        $uid = $_POST['uid'];
        $cartList = $_POST['cart_list'];

        $cart_array = json_decode(html_entity_decode($cartList), true);

        $getMenuVersion = -1;//-1 全部 1只有version=1的 2
        if($this->app_version < 266)
            $getMenuVersion = 1;

        if($getMenuVersion == 1)
            $sid = D('Cart')->field(true)->where(array('uid'=>$uid,'fid'=>$cart_array[0]['fid']))->find()['sid'];
        else
            $sid = $cart_array[0]['storeId'];

        $store = D('Store')->get_store_by_id($sid);
        
        if($getMenuVersion != 1) {
            if ($store['is_close'] == 1) $this->returnCode(1, 'info', array(), "storeClose");
        }
        if(!D('Cart')->where(array('sid'=>$sid,'uid'=>$uid))->find()) $this->returnCode(1,'info',array(),'checkcarterror');

        if($store['menu_version'] == 2){
            $categories = D('StoreMenuV2')->getStoreCategories($sid,true);
            $sortList = D('StoreMenuV2')->arrangeApp($categories);
        }else {
            $sortList = D('Shop_goods_sort')->lists($sid, true);
        }
        $sortIdList = array();

        $del_list = array();

        foreach ($sortList as $k => $sl){
            $sortIdList[] = $sl['sort_id'];
        }

        foreach ($cart_array as $product) {
            $goodsId = $product['fid'];
            if($store['menu_version'] == 2){
                $product_good = D('StoreMenuV2')->getProduct($goodsId,$sid);
                $goods = D('StoreMenuV2')->arrangeProductAppOne($product_good);
                if($product['categoryId'] == 0) {
                    $curr_category = D('StoreMenuV2')->getCategoryByProductId($goodsId,$sid);
                    $goods['sort_id'] = $curr_category['categoryId'];
                }else {
                    $goods['sort_id'] = $product['categoryId'];
                }

                if($product['dish_id'] != '') {
                    $dish_list = explode('|', $product['dish_id']);
                    $all_dish_id = array();
                    foreach ($dish_list as $dish) {
                        $dish_arr = explode(',', $dish);
                        if (!in_array($dish_arr[0], $all_dish_id)) $all_dish_id[] = $dish_arr[0];
                        if (!in_array($dish_arr[1], $all_dish_id)) $all_dish_id[] = $dish_arr[1];
                    }

                    $all_list = D('Store_product')->where(array('id' => array('in', $all_dish_id), 'storeId' => $sid, 'status' => 1))->select();

                    if (count($all_dish_id) != count($all_list)) {
                        //$goods['status'] = 0;
                        D('Cart')->where(array('uid'=>$uid,'fid'=>$goodsId,'dish_id'=>$product['dish_id']))->delete();
                        $del_list[] = lang_substr($goods['name'],C('DEFAULT_LANG'));
                    }
                }
            }else {
                $goods = D('Shop_goods')->where(array('goods_id' => $goodsId))->find();
                if($product['dish_id'] != '') {
                    $dish_list = explode('|', $product['dish_id']);
                    $all_dish_id = array();
                    $all_dish_value_id = array();
                    foreach ($dish_list as $dish) {
                        $dish_arr = explode(',', $dish);
                        if (!in_array($dish_arr[0], $all_dish_id)) $all_dish_id[] = $dish_arr[0];
                        if (!in_array($dish_arr[1], $all_dish_value_id)) $all_dish_value_id[] = $dish_arr[1];
                    }
                    $is_del_dish = false;
                    $all_list = D('Side_dish')->where(array('id' => array('in', $all_dish_id), 'status' => 1))->select();

                    if (count($all_dish_id) != count($all_list)) {
                        //$goods['status'] = 0;
                        $is_del_dish = true;
                    }

                    $all_value_list = D('Side_dish_value')->where(array('id' => array('in', $all_dish_value_id), 'status' => 1))->select();
                    if (count($all_dish_value_id) != count($all_value_list)) {
                        //$goods['status'] = 0;
                        $is_del_dish = true;
                    }

                    if($is_del_dish){
                        D('Cart')->where(array('uid'=>$uid,'fid'=>$goodsId,'dish_id'=>$product['dish_id']))->delete();
                        $del_list[] = lang_substr($goods['name'],C('DEFAULT_LANG'));
                    }
                }
            }

            if(!in_array($goods['sort_id'],$sortIdList)){
                $del_list[] = lang_substr($goods['name'],C('DEFAULT_LANG'));
                D('Cart')->where(array('uid'=>$uid,'fid'=>$goodsId))->delete();
            }

            if($goods['status'] != 1){
                $del_list[] = lang_substr($goods['name'],C('DEFAULT_LANG'));
                D('Cart')->where(array('uid'=>$uid,'fid'=>$goodsId))->delete();
            }
        }
        /**
        $week = date('w');
        $currTime = date('H:i:s');

        $del_list = array();
        foreach ($cart_array as $c_good){
            $goods = D('Shop_goods')->where(array('goods_id'=>$c_good['fid']))->find();
            $goods_sort = D('Shop_goods_sort')->where(array('sort_id'=>$goods['sort_id']))->find();

            $is_continue = true;
            if($goods_sort['is_weekshow'] == 1){
                $weekList = explode(',',$goods_sort['week']);
                if(!in_array($week,$weekList)){
                    $del_list[] = lang_substr($goods['name'],C('DEFAULT_LANG'));
                    $is_continue = false;
                    D('Cart')->where(array('uid'=>$uid,'fid'=>$c_good['fid']))->delete();
                }
            }

            if($is_continue && $goods_sort['is_time'] == 1){
                $showTime = explode(',',$goods_sort['show_time']);
                if(!($currTime >= $showTime[0] && $currTime < $showTime[1])){
                    $del_list[] = lang_substr($goods['name'],C('DEFAULT_LANG'));
                    D('Cart')->where(array('uid'=>$uid,'fid'=>$c_good['fid']))->delete();
                }
            }
        }
         * */

        if(count($del_list) == 0){
            $this->returnCode(0, 'info', array(), 'success');
        }else{
            $this->returnCode(1,'info',$del_list,'checkcarterror');
        }
    }

    public function confirmCart(){
        $uid = $_POST['uid'];
        $cartList = $_POST['cart_list'];

        $cart_array = json_decode(html_entity_decode($cartList),true);

        $getMenuVersion = -1;//-1 全部 1只有version=1的 2
        if($this->app_version < 266)
            $getMenuVersion = 1;

        $result = D('Cart')->getCartList($uid,$cart_array,$getMenuVersion);

        if($result['store_name']) {
            //平台优惠劵
            $_POST['amount'] = $result['food_total_price'];

            $coupon = $this->getCanCoupon();
            $result['coupon'] = $coupon;

            //账户余额
            $userInfo = D('User')->get_user($uid);
            $result['is_bind_phone'] = $userInfo['phone'] == '' ? 0 : 1;
            $result['now_money'] = round($userInfo['now_money'], 2);

            $this->returnCode(0, '', $result, 'success');
        }else{
            $this->returnCode(1,'info',array(),'Cart is Empty');
        }
    }

    public function saveOrder(){
        $uid = $_POST['uid'];
        $cartList = $_POST['cart_list'];
        $note = $_POST['order_mark'];
        //New UI
        $address_mark = html_entity_decode($_POST['address_mark']);

        $adr_id = $_POST['addr_item_id'];

        $address = D('User_adress')->where(array('adress_id'=>$adr_id))->find();
        if($address_mark != $address['detail']){
            $addressData['detail'] = $address_mark;
            if(!checkEnglish($address_mark) && trim($address_mark) != ''){
                $addressData['detail_en'] = translationCnToEn($address_mark);
            }else{
                $addressData['detail_en'] = '';
            }
            D('User_adress')->where(array('adress_id'=>$adr_id))->save($addressData);
        }

        $cart_array = json_decode(html_entity_decode($cartList),true);
        $tax_price = 0;
        $deposit_price = 0;
        $orderData = array();

        //判断商品是否还在可销售的时间段
        $getMenuVersion = -1;//-1 全部 1只有version=1的 2
        if($this->app_version < 266)
            $getMenuVersion = 1;

        if($getMenuVersion == 1)
            $sid = D('Cart')->field(true)->where(array('uid'=>$uid,'fid'=>$cart_array[0]['fid']))->find()['sid'];
        else
            $sid = $cart_array[0]['storeId'];

        $store = D('Merchant_store')->where(array('store_id'=>$sid))->find();

        if($store['menu_version'] == 2){
            $categories = D('StoreMenuV2')->getStoreCategories($sid,true);
            $sortList = D('StoreMenuV2')->arrangeApp($categories);
        }else {
            $sortList = D('Shop_goods_sort')->lists($sid, true);
        }
        $sortIdList = array();

        $is_cut = false;
        $is_error = false;
        foreach ($sortList as $k => $sl){
            $sortIdList[] = $sl['sort_id'];
        }

        foreach ($cart_array as $product) {
            $goodsId = $product['fid'];
            if($store['menu_version'] == 2){
                $product_good = D('StoreMenuV2')->getProduct($goodsId,$sid);
                $goods = D('StoreMenuV2')->arrangeProductAppOne($product_good);
                if($product['categoryId'] == 0) {
                    $curr_category = D('StoreMenuV2')->getCategoryByProductId($goodsId,$sid);
                    $goods['sort_id'] = $curr_category['categoryId'];
                }else {
                    $goods['sort_id'] = $product['categoryId'];
                }
            }else {
                $goods = D('Shop_goods')->where(array('goods_id' => $goodsId))->find();
            }

            if(!D('Cart')->where(array('uid'=>$uid,'fid'=>$goodsId))->find()){
                $is_error = true;
            }

            if(!in_array($goods['sort_id'],$sortIdList)){
                $is_cut = true;
                D('Cart')->where(array('uid'=>$uid,'fid'=>$goodsId))->delete();
            }

            if($goods['status'] != 1){
                $is_error = true;
                D('Cart')->where(array('uid'=>$uid,'fid'=>$goodsId))->delete();
            }
        }

        if($is_cut){
            $this->returnCode(1,'',array(),"Please note that you have one or more item become unavailable at this time and will be removed from your cart. Do you confirm to continue checkout?");
        }

        if($is_error){
            $this->returnCode(1,'',array(),"Sorry, you have one (or more) item that is temporarily unavailable at this moment, and will be removed from your order. All other items will stay in your shopping cart. Please modify your items if needed.");
        }

        ///////////
        //获取商品折扣活动
        $store_discount = D('New_event')->getStoreNewDiscount($sid);
        $goodsDiscount = $store_discount['goodsDiscount'];
        $goodsDishDiscount = $store_discount['goodsDishDiscount'];

        foreach ($cart_array as $v){
            if($store['menu_version'] == 2){
                $good = D('StoreMenuV2')->getProduct($v['fid'],$sid);
                $t_good['productId'] = $v['fid'];
                $t_good['productName'] = $good['name'];
                $good['price'] = round($good['price']*$goodsDiscount/100,2);
                $good['tax_num'] = $good['tax']/1000;
                $good['deposit_price'] = 0;
                $good['stock_num'] = -1;
            }else{
                $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['fid']))->find();
                $good['price'] = round($good['price']*$goodsDiscount,2);
                $t_good['productId'] = $v['fid'];
                $t_good['productName'] = lang_substr($good['name'],C('DEFAULT_LANG'));
            }

            $specData = D('Shop_goods')->format_spec_value($good['spec_value'], $good['goods_id'], $good['is_properties']);
            if($specData['list'] != "" && $v['spec'] != ""){
                foreach ($specData['list'] as $kk=>$vv){
                    if($v['spec'] == $kk){
                        $good['price'] = $vv['price'];
                    }
                }
            }

            $t_good['productStock'] = $good['stock_num'];
            $t_good['productParam'] = array();
            if($v['spec'] != ''){
                $spec_list = explode('_',$v['spec']);
                foreach ($spec_list as $vv){
                    $t_spec['id'] = $vv;
                    $t_spec['type'] = 'spec';
                    $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                    $t_spec['name'] = lang_substr($spec['name'],C('DEFAULT_LANG'));

                    $t_good['productParam'][] = $t_spec;
                }
            }
            if($v['proper'] != ''){
                $pro_list = explode('_',$v['proper']);
                foreach ($pro_list as $vv){
                    $t_pro['data'] = array();
                    $t_pro['type'] = 'pro';

                    $ids = explode(',',$vv);
                    $proId = $ids[0];
                    $sId = $ids[1];

                    $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                    $nameList = explode(',',$pro['val']);
                    $name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));

                    $t_pro['data'][] = array('list_id' => $proId,'id' => $sId,'name'=>$name);

                    $t_good['productParam'][] = $t_pro;
                }
            }

            if($v['dish_id'] != "" && $v['dish_id'] != null){
                $t_dish['type'] = 'side_dish';
                $t_dish['dish_id'] = $v['dish_id'];
                $t_good['productParam'][] = $t_dish;

                $dish_list = explode('|',$v['dish_id']);
                foreach($dish_list as $dish_value){
                    $one_dish = explode(",",$dish_value);
                    //0 dish_id 1 id 2 num 3 price
                    $good['price'] = $good['price'] + $one_dish[3]*$one_dish[2];
                }
            }
            $t_good['productPrice'] = $good['price'];

            $t_good['count'] = $v['stock'];
            $t_good['tax_num'] = $good['tax_num'];
            $t_good['deposit_price'] = $good['deposit_price'];

            if($store['menu_version'] == 1) {//这个计算税已无效
                $tax_price += $good['price'] * $good['tax_num']/100 * $v['stock'];
            }else{
                $orderDetail = array('goods_id'=>$t_good['productId'],'num'=>$v['stock'],'store_id'=>$sid,'dish_id'=>$v['dish_id']);
                $tax_price += D('StoreMenuV2')->calculationTaxFromOrder($orderDetail);
            }

            $deposit_price += $good['deposit_price']*$v['stock'];

            $orderData[] = $t_good;
        }


        $return = D('Shop_goods')->checkCart($sid, $uid, $orderData,1,0,$goodsDiscount,$goodsDishDiscount);

        //garfunkel add
        $area = D('Area')->where(array('area_id'=>$store['city_id']))->find();

        $now_time = time()+ $area['jetlag']*3600;
        $order_data = array();
        $order_data['mer_id'] = $return['mer_id'];
        $order_data['store_id'] = $return['store_id'];
        $order_data['uid'] = $uid;

        $order_data['desc'] = $note;
        $order_data['create_time'] = $now_time;
        $order_data['last_time'] = $now_time;
        $order_data['invoice_head'] = "";
        $order_data['village_id'] = 0;

        $order_data['num'] = $return['total'];
        $order_data['packing_charge'] = $return['store']['pack_fee'];//打包费

        $order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_redu[ce'];//店铺优惠
        $order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠
        $orderid  = date('ymdhis').substr(microtime(),2,8-strlen($uid)).$uid;
        $order_data['real_orderid'] = $orderid;
        $order_data['no_bill_money'] = 0;//无需跟平台对账的金额

        $address = D('User_adress')->field(true)->where(array('adress_id' => $adr_id, 'uid' => $uid))->find();

        $order_data['username'] = $address['name'];
        $order_data['userphone'] = $address['phone'];
        $order_data['address'] = $address['adress'];
        if($address['detail_en'] != ''){
            $order_data['address_detail'] = $address['detail_en'] ." (".$address['detail'].")";
        }else {
            $order_data['address_detail'] = $address['detail'];
        }
        $order_data['address_id'] = $adr_id;
        $order_data['lat'] = $address['latitude'];
        $order_data['lng'] = $address['longitude'];

        $order_data['expect_use_time'] = D('Store')->get_store_delivery_time($sid);
        $order_data['freight_charge'] = $delivery_fee = D('Store')->CalculationDeliveryFee($uid,$sid,$adr_id);

        $order_data['is_pick_in_store'] = 0;

        $order_data['goods_price'] = $return['price'];//商品的价格
        //garfunkel 计算服务费
        $order_data['service_fee'] = number_format($order_data['goods_price'] * $return['store']['service_fee']/100,2);
        $order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
        $order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
        //modify garfunkel
        //$order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['store']['pack_fee'];//订单总价  商品价格+打包费+配送费
        //$order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
        //$order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['store']['pack_fee'];//实际要支付的价格
        //$order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['packing_charge'];//实际要支付的价格
        //$order_data['price'] = $order_data['price'] * 1.05; //税费

        $tax_price = $return['tax_price'] + ($delivery_fee + $return['store']['pack_fee'])*$return['store']['tax_num']/100;
        $order_data['total_price'] = $return['price'] + $tax_price + $deposit_price + $delivery_fee + $return['store']['pack_fee'] + $order_data['service_fee'];
        $order_data['price'] = $order_data['total_price'];

        $order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情

        $order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'

        //订单来源
        $order_data['order_from'] = $_POST['cer_type'];
        //记录支付类型
        if($_POST['pay_type'] == 3){
            $order_data['pay_type'] = "moneris";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }elseif ($_POST['pay_type'] == 4){//余额支付
            $order_data['pay_type'] = "";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }elseif ($_POST['pay_type'] == 2){//微信支付
            $order_data['pay_type'] = "weixin";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }elseif ($_POST['pay_type'] == 1){//支付宝
            $order_data['pay_type'] = "alipay";
            $order_data['tip_charge'] = $_POST['tip'] ? $_POST['tip'] : 0;
        }else{
            $order_data['tip_charge'] = 0;
        }

        //处理优惠券
        if($_POST['coupon_id'] && $_POST['coupon_id'] != -1){
            //如果选择的为活动优惠券
            if(strpos($_POST['coupon_id'],'event')!== false) {
                $event = explode('_',$_POST['coupon_id']);
                $coupon_id = $event[1];
                if($coupon_id){
                    $coupon = D('New_event_coupon')->where(array('id'=>$coupon_id))->find();
                    $order_data['coupon_id'] = $_POST['coupon_id'];
                    $order_data['coupon_price'] = $coupon['discount'];
                }
            }else {
                $now_coupon = D('System_coupon')->get_coupon_by_id($_POST['coupon_id']);
                if (!empty($now_coupon)) {
                    $coupon_data = D('System_coupon_hadpull')->field(true)->where(array('id' => $_POST['coupon_id']))->find();
                    $coupon_real_id = $coupon_data['coupon_id'];
                    $coupon = D('System_coupon')->get_coupon($coupon_real_id);
                    $order_data['coupon_id'] = $_POST['coupon_id'];
                    $order_data['coupon_price'] = $coupon['discount'];
                }
            }
        }

        if($_POST['delivery_discount']) {
            $order_data['delivery_discount'] = $_POST['delivery_discount'];
            $delivery_coupon = D('New_event')->getFreeDeliverCoupon($return['store_id'], $store['city_id']);
            $distance = getDistance($store['lat'], $store['long'], $address['latitude'], $address['longitude']);
            if ($delivery_coupon != "" && $delivery_coupon['limit_day'] * 1000 >= $distance) {
                $order_data['delivery_discount_event'] = $delivery_coupon['event_type'];
            }
        }else{
            $order_data['delivery_discount'] = 0;
        }

        //garfunkel 判断来源 2or3 Apple app | 4 Android
        $order_data['is_mobile_pay'] = $_POST['cer_type'];

        //$order_data['delivery_discount'] = $_POST['delivery_discount'] ? $_POST['delivery_discount'] : 0;
        $order_data['merchant_reduce'] = $_POST['merchant_reduce'] ? $_POST['merchant_reduce'] : 0;
        $order_data['not_touch'] = $_POST['not_touch'] ? $_POST['not_touch'] : 0;

        if(!checkEnglish($order_data['desc']) && trim($order_data['desc']) != ''){
            $order_data['desc_en'] = translationCnToEn($order_data['desc']);
        }else{
            $order_data['desc_en'] = '';
        }

        $order_id = D('Shop_order')->saveOrder($order_data, $return);
        $order = D('Shop_order')->where(array('order_id'=>$order_id))->find();
        //清除购物车中的内容
        D('Cart')->delCart($uid,$cart_array);
        //die($order_id);
        if($_POST['pay_type'] == 0){//线下支付 直接进入支付流程
            $order_param['order_id'] = $order_id;
            $order_param['order_from'] = 0;
            $order_param['order_type'] = 'shop';
            $order_param['pay_time'] = date();
            $order_param['pay_type'] = 'Cash';
            $order_param['is_mobile'] = $order_data['is_mobile_pay'];
            $order_param['is_own'] = 0;
            $order_param['third_id'] = 0;

            D('Shop_order')->after_pay($order_param);
        }elseif($_POST['pay_type'] == 4){//余额支付
            //账户余额
            $userInfo = D('User')->get_user($uid);
            $now_money = round($userInfo['now_money'],2);

            $data['balance_pay'] = $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'] - $order_data['delivery_discount'] - $order_data['merchant_reduce'];
            $data['balance_pay'] = round($data['balance_pay'],2);
            if($now_money >= $data['balance_pay']){
                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($data);

                $order_param = array(
                    'order_id' => $order_id,
                    'pay_type' => '',
                    'order_type'=> 'shop',
                    'third_id' => '',
                    'is_mobile' => $order_data['is_mobile_pay'],
                    'pay_money' => 0,
                    'order_total_money' => $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'],
                    'balance_pay' => $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'],
                    'merchant_balance' => 0,
                    'is_own'	=> 0
                );

                D('Shop_order')->after_pay($order_param);
            }else {
                $this->returnCode(1, 'info', array(), L('_B_MY_NOMONEY_'));
            }
        }else if($_POST['pay_type'] == 3){//信用卡支付

        }else if($_POST['pay_type'] == 1){//支付宝
            $price = $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'] - $order_data['delivery_discount'] - $order_data['merchant_reduce'];
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price,$_POST['ip'],$_POST['cer_type']);
            if($result['resCode'] == 'SUCCESS'){
                $result['main_id'] = $order_id;
                $result['orderNo'] = $order['real_orderid'];
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',$result,$result['retMsg']);
            }
        }else if($_POST['pay_type'] == 2){//微信支付
            $price = $order_data['price'] + $order_data['tip_charge'] - $order_data['coupon_price'] - $order_data['delivery_discount'] - $order_data['merchant_reduce'];
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price,$_POST['ip'],$_POST['cer_type']);
            if($result['resCode'] == 'SUCCESS'){
                $result['main_id'] = $order_id;
                $result['orderNo'] = $order['real_orderid'];
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),'fail');
            }
        }

        if($order_id != 0) {
            $returnData['main_id'] = $order_id;
            $returnData['orderNo'] = $order['real_orderid'];
            $this->returnCode(0, '', $returnData, 'success');
        }else
            $this->returnCode(1,'info',array(),'fail');
    }

    public function getOrderList(){
        $uid = $_POST['uid'];
        $status = $_POST['status'];
        $_GET['page'] = $_POST['page'];

        $where = "is_del=0 AND uid={$uid}";
        //orderStatus -1 全部；0 未付款；1 已付款；2 退款；3 进行中；4 待评价；5 已完成

//        if ($status == 0) {
//            $where .= " AND paid=0 AND status<4";
//        } elseif ($status == 1) {
//            $where .= " AND paid=1 AND status=2";
//        } elseif ($status == 2) {
//            $where .= " AND paid=1 AND (status=4 OR status=5)";
//        }else{//付款超时，待删除
//            $where .= " AND status<>6";
//        }

        switch ($status){
            case 0:
                $where .= " AND paid=0 AND status<4";
                break;
            case 1:
                $where .= " AND paid=1 AND status=2";
                break;
            case 2:
                $where .= " AND paid=1 AND (status=4 OR status=5)";
                break;
            case 3:
                $where .= " AND status<2";
                break;
            case 4:
                $where .= " AND status=2";
                break;
            case 5:
                $where .= " AND status=3";
                break;
            case 6:
                $where .= " AND paid=1 AND (status=2 OR status=3 OR status=4 OR status=5)";
                break;
            default://付款超时，待删除
                $where .= " AND status<>6";
                break;
        }

        $where .= " AND is_del = 0";

        $count = D("Shop_order")->where($where)->count();

        $order_list = D("Shop_order")->get_order_list($where, 'order_id DESC', false);//field(true)->where($where)->order('order_id DESC')->select();
        $order_list = $order_list['order_list'];

        foreach ($order_list as $st) {
            $store_ids[] = $st['store_id'];
        }
        $m = array();
        if ($store_ids) {
            $store_image_class = new store_image();
            $merchant_list = D("Merchant_store")->field('s.*,sh.background')->join('as s left join '.C('DB_PREFIX').'merchant_store_shop sh ON sh.store_id = s.store_id ')->where(array('s.store_id' => array('in', $store_ids)))->select();
            foreach ($merchant_list as $li) {
                $images = $store_image_class->get_allImage_by_path($li['pic_info']);
                $li['image'] = $images ? array_shift($images) : array();
                unset($li['status']);
                $li['pay_method'] = explode('|',$li['pay_method']);
                $m[$li['store_id']] = $li;
            }
        }
        $list = array();
        foreach ($order_list as $ol) {
            if (isset($m[$ol['store_id']]) && $m[$ol['store_id']]) {
                $list[] = array_merge($ol, $m[$ol['store_id']]);
            } else {
                $list[] = $ol;
            }
        }

        foreach($list as $key=>$val){
            //modify garfunkel
            $t['rowID'] = $val['real_orderid'];
            $t['userID'] = $val['uid'];
            $t['payType'] = $val['pay_type'];
            $t['payTypeName'] = D('Store')->getPayTypeName($val['pay_type']);
            $t['storeID'] = $val['store_id'];
            $t['storeName'] = lang_substr($val['name'],C('DEFAULT_LANG'));
            $t['createDate'] = date('Y-m-d H:i',$val['create_time']);
            $t['create_time'] = $val['create_time'];
            $t['goodsCount'] = $val['num'];
            $t['goodsPrice'] = $val['goods_price'];
            $t['status'] = $val['status'];
            $t['statusName'] = D('Store')->getOrderStatusName($val['status']);
            $t['isComment'] = "1";
            //$t['statusName'] = D('Store')->getOrderStatusName($val['status']);
            $status = D('Shop_order_log')->field(true)->where(array('order_id' => $val['order_id']))->order('id DESC')->find();
            $status['status'] = $status['status'] == 33 ? 2 : $status['status'];
            $t['statusLog'] = $status['status'];
            $t['statusLogName'] = D('Store')->getOrderStatusLogName($status['status']);
            $t['goodsImage'] = $val['image'];
            $t['orderType'] = "0";
            $t['tip_fee'] = $val['tip_charge'];
            $t['total_price'] = $val['price'];
            $t['paid'] = $val['paid'];
            $t['order_id'] = $val['order_id'];
            $t['discount'] = $val['coupon_price'];
            $t['delivery_discount'] = $val['delivery_discount'];
            $t['merchant_reduce'] = $val['merchant_reduce'];
            $t['pay_method'] = $val['pay_method'];
            if($val['background'] && $val['background'] != '') {
                $image_tmp = explode(',', $val['background']);
                $t['background'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];
            }else{
                $t['background'] = '';
            }

            if($val['status'] == 3){
                $reply = D('Reply')->where(array('order_id'=>$val['order_id']))->find();
                $t['score'] = $reply['score'];
            }else{
                $t['score'] = 0;
            }

            $delivery = D('Deliver_supply')->field(true)->where(array('order_id'=>$val['order_id']))->find();
            if($delivery) {
                if($delivery['status'] > 1 && $delivery['status'] <= 5){
                    $deliver = D('Deliver_user')->field(true)->where(array('uid'=>$delivery['uid']))->find();
                    $t['deliver_name'] = $deliver['name'];
                    $t['deliver_phone'] = $deliver['phone'];
                    $t['deliver_lng'] = $deliver['lng'];
                    $t['deliver_lat'] = $deliver['lat'];
                }
            }

            $t['jetlag'] = 0;
            if($val['paid'] == 0) {
                $store = D('Merchant_store')->where(array('store_id' => $val['store_id']))->find();
                $area = D('Area')->where(array('area_id'=>$store['city_id']))->find();
                $t['jetlag'] = $area['jetlag'];
            }
            $t['now_time'] = time();

            $result['info'][] = $t;
        }

        $result['count'] = 10;
        $result['number'] = count($result['info']);
        $result['total_page'] = ceil($count / 10);


        $this->returnCode(0,'',$result,'success');
    }

    public function getDeliverPosition(){
        $order_id = $_POST['order_id'];
        $delivery = D('Deliver_supply')->field(true)->where(array('order_id'=>$order_id))->find();
        if($delivery) {
            if($delivery['status'] > 1 && $delivery['status'] < 5){
                $deliver = D('Deliver_user')->field(true)->where(array('uid'=>$delivery['uid']))->find();
                $t['deliver_name'] = $deliver['name'].'('.$deliver['phone'].')';
                $t['deliver_lng'] = $deliver['lng'];
                $t['deliver_lat'] = $deliver['lat'];

                $this->returnCode(0,'info',$t,'success');
            }
        }
    }

    public function getOrderStatus(){
        $order_id = $_POST['order_id'];

        $order = D('Shop_order')->field(true)->where(array('real_orderid'=>$order_id))->find();
        $status = D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id']))->order('id DESC')->select();
        foreach ($status as $v){
            $data['status'] = $v['status'];
            $data['name'] = D('Store')->getOrderStatusStr($v['status']);
            $data['mark'] = D('Store')->getOrderStatusMark($v['status'],$order['order_id'],$v);
            $data['createDate'] = date('Y-m-d H:i:s',$v['dateline']);

            $result[] = $data;
        }

        $this->returnCode(0,'info',$result,'success');
    }

    public function getOrderDetail(){
        $order_id = $_POST['order_id'];//长id
        $orderId = $_POST['orderId'];//短id

        if($orderId && $orderId != '') {
            $order = D('Shop_order')->field(true)->where(array('order_id' => $orderId))->find();
            $order_id = $order['real_orderid'];
        }else
            $order = D('Shop_order')->field(true)->where(array('real_orderid'=>$order_id))->find();

        if($order['paid'] == 0){
            $order_detail['statusname'] = L('_UNPAID_TXT_');
            $order_detail['payname'] = L('_UNPAID_TXT_');
            $order_detail['score'] = 0;
        }else{
            $order_detail['statusname'] = D('Store')->getOrderStatusName($order['status']);
            $order_detail['status'] = $order['status'];

            //$order_detail['payname'] = $order['pay_type'] == 'moneris' ? 'Paid Online' : 'Cash';
            if($order['pay_type'] == 'moneris'){
                $order_detail['payname'] = 'Credit Card';
            }elseif ($order['pay_type'] == ''){
                $order_detail['payname'] = 'Tutti Credits';//'Balance';
            }elseif ($order['pay_type'] == 'weixin'){
                $order_detail['payname'] = 'WeiXin';
            }elseif ($order['pay_type'] == 'alipay'){
                $order_detail['payname'] = 'AliPay';
            }else{
                $order_detail['payname'] = 'Cash';
            }

            if($order['status'] == 3){
                $reply = D('Reply')->where(array('order_id'=>$order['order_id']))->find();
                $order_detail['score'] = $reply['score'];
            }else{
                $order_detail['score'] = 0;
            }
        }

        $order_detail['pay_type'] = $order['pay_type'];
        $order_detail['orderId'] = $order['order_id'];
        $order_detail['paid'] = $order['paid'];
        $order_detail['add_time'] = date('Y-m-d H:i:s',$order['create_time']);
        //$order_detail['payname'] = $order_detail['paymodel'] = D('Store')->getPayTypeName($order['pay_type']);
        $order_detail['packing_fee'] = $order['packing_charge'];
        $order_detail['ship_fee'] = $order['freight_charge'];
        $order_detail['tip_fee'] = $order['tip_charge'];
        $order_detail['food_amount'] = $order['goods_price'];
        $order_detail['goods_count'] = $order['num'];
        $order_detail['order_id'] = $order_id;
        $order_detail['expect_time'] = date('Y-m-d H:i:s',$order['expect_use_time']);
        $order_detail['site_id'] = $order['store_id'];
        $order_detail['uname'] = $order['username'];
        $order_detail['phone'] = $order['userphone'];
        $order_detail['address2'] = $order['address'];
        $order_detail['address1'] = $order['desc'];
        $order_detail['service_fee'] = $order['service_fee'];
        $order_detail['promotion_discount'] = "0";
        $order_detail['discount'] = $order['coupon_price'] + $order['delivery_discount'] + $order['merchant_reduce'];
        $order_detail['create_time'] = $order['create_time'];
        $order_detail['delivery_discount'] = $order['delivery_discount'];
        $order_detail['merchant_reduce'] = $order['merchant_reduce'];
        $order_detail['coupon_discount'] = $order['coupon_price'];

        $order_detail['user_lat'] = $order['lat'];
        $order_detail['user_lng'] = $order['lng'];
        $order_detail['address2'] = $order['address_detail'] == "" ? $order['address'] : $order['address']." - ".$order['address_detail'];

        /**
        $address = D('User_adress')->where(array('adress_id'=>$order['address_id']))->find();
        $order_detail['user_lat'] = $address['latitude'];
        $order_detail['user_lng'] = $address['longitude'];
        if($address && $address['detail'] != '')
            $order_detail['address2'] = $address['adress'].' ('.$address['detail'].')';
        */

        $store = D('Store')->get_store_by_id($order['store_id']);
        $order_detail['jetlag'] = 0;
        if($order['paid'] == 0) {
            //$store = D('Merchant_store')->where(array('store_id' => $order['store_id']))->find();
            $area = D('Area')->where(array('area_id'=>$store['city_id']))->find();
            $order_detail['jetlag'] = $area['jetlag'];
        }
        $order_detail['now_time'] = time();

        $order_detail['site_name'] = $store['site_name'];
        $order_detail['tel'] = $store['phone'];
        $order_detail['store_service_fee'] = $store['service_fee'];
        $order_detail['background'] = $store['background'];
        $order_detail['pay_method'] = $store['pay_method'];
        $order_detail['store_lat'] = $store['lat'];
        $order_detail['store_lng'] = $store['lng'];

        $status = D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id']))->order('id DESC')->find();
        $add_time = 0;
        if($status['status'] == 33){
            if(D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id'], 'status' => 3))->order('id DESC')->find())
                $status['status'] = 3;
            else
                $status['status'] = 2;
            $add_time = $status['note'];
        }else {
            if ($add_time_log = D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id'], 'status' => 33))->order('id DESC')->find()) {
                $add_time = $add_time_log['note'];
            }
        }
        $order_detail['status_log'] = $status['status'];
        $order_detail['statusName'] = D('Store')->getOrderStatusLogName($status['status']);
        $order_detail['statusDesc'] = D('Store')->getOrderStatusDesc($status['status'],$order,$status,$store['site_name'],$add_time);

        if($order['paid'] == 0) {
            $order_detail['statusName'] = "Unpaid";
            $order_detail['statusDesc'] = "This order will be expired and removed in 5 minutes. Please make a payment to get it delivered to you.";
        }

        $result['order'] = $order_detail;

        $tax_price = 0;
        $deposit_price = 0;

        $order_good = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
        foreach($order_good as $v){
            $goods['fname'] = $v['name'];
            $goods['quantity'] = $v['num'];
            $goods['price'] = $v['price'];

            $spec_desc = '';
            $spec_ids = explode('_',$v['spec_id']);
            foreach ($spec_ids as $vv){
                $spec = D('Shop_goods_spec_value')->field(true)->where(array('id'=>$vv))->find();
                $spec_desc = $spec_desc == '' ? lang_substr($spec['name'],C('DEFAULT_LANG')) : $spec_desc.';'.lang_substr($spec['name'],C('DEFAULT_LANG'));
            }

            $goods['spec_desc'] = $spec_desc;

            if($v['pro_id'] != '')
                $pro_ids = explode('|',$v['pro_id']);
            else
                $pro_ids = array();

            $spec_desc = "";
            foreach ($pro_ids as $vv){
                $ids = explode(',',$vv);
                $proId = $ids[0];
                $sId = $ids[1];

                $pro = D('Shop_goods_properties')->field(true)->where(array('id'=>$proId))->find();
                $nameList = explode(',',$pro['val']);
                $name = lang_substr($nameList[$sId],C('DEFAULT_LANG'));

                $spec_desc = $spec_desc == '' ? $name : $spec_desc.';'.$name;
            }
            $goods['spec_desc'] = $goods['spec_desc'] == '' ? $spec_desc : $goods['spec_desc'].";".$spec_desc;

            if($v['dish_id'] != "" && $v['dish_id'] != null){
                $dish_desc = "";
                $dish_list = explode("|",$v['dish_id']);
                foreach($dish_list as $vv){
                    $one_dish = explode(",",$vv);
                    //0 dish_id 1 id 2 num 3 price
                    if($store['menu_version'] == 1) {
                        $dish_vale = D('Side_dish_value')->where(array('id' => $one_dish[1]))->find();
                        $dish_vale['name'] = lang_substr($dish_vale['name'], C('DEFAULT_LANG'));
                    }elseif ($store['menu_version'] == 2){
                        $product_dish = D('StoreMenuV2')->getProduct($one_dish[1],$order['store_id']);
                        $dish_vale['name'] = $product_dish['name'];
                    }
                    //$dish_vale = D('Side_dish_value')->where(array('id'=>$one_dish[1]))->find();
                    //$dish_vale['name'] = lang_substr($dish_vale['name'],C('DEFAULT_LANG'));

                    $add_str = $one_dish[2] > 1 ? $dish_vale['name']."*".$one_dish[2] : $dish_vale['name'];

                    $dish_desc = $dish_desc == "" ? $add_str : $dish_desc.";".$add_str;
                }
                //$goods['spec_desc'] = $goods['spec_desc'] == '' ? $dish_desc : $goods['spec_desc'].";".$dish_desc;
                $goods['spec_desc'] = str_replace('<br/>','',$v['spec']);
            }

            if($store['menu_version'] == 2){
                //$goods = D('StoreMenuV2')->getProduct($v['fid']);
                //$goods['price'] = $goods['price']/100;
                //$goods['tax_num'] = $goods['tax']/1000;
                $tax_price += D('StoreMenuV2')->calculationTaxFromOrder($v);
                $good['deposit_price'] = 0;
            }else {
                $good = D('Shop_goods')->field(true)->where(array('goods_id' => $v['goods_id']))->find();
                $tax_price += $v['price'] * $v['tax_num']/100 * $v['num'];
            }
            $deposit_price += $good['deposit_price']*$v['num'];

            $food[] = $goods;
        }

        $result['food'] = $food;
        $tax_price = $tax_price + ($order['packing_charge'] + $order['freight_charge'])*$store['tax_num']/100;
        $result['order']['tax_price'] = number_format($tax_price,2);
        $result['order']['deposit_price'] = $deposit_price;
        $result['order']['subtotal'] = $order['price'];

        $delivery = D('Deliver_supply')->field(true)->where(array('order_id'=>$order['order_id']))->find();
        if($delivery) {
            $deliver = D('Deliver_user')->field(true)->where(array('uid'=>$delivery['uid']))->find();
            if($deliver['name'])
                $result['order']['empname'] = $deliver['name'].'('.$deliver['phone'].')';
            else
                $result['order']['empname'] = '';

            $result['order']['deliver_name'] = $deliver['name'];
            $result['order']['deliver_phone'] = $deliver['phone'];

            if($delivery['status'] > 1 && $delivery['status'] <= 5){
                $result['order']['deliver_lng'] = $deliver['lng'];
                $result['order']['deliver_lat'] = $deliver['lat'];
            }
        }

        $this->returnCode(0,'',$result,'success');
    }

    public function getGoodsSpec(){
        $uid = $_POST['uid'];
        $fid = $_POST['fid'];

        $storeId = $_POST['storeId'] ? $_POST['storeId'] : 0;

        $store = D('Merchant_store')->where(array('store_id'=>$storeId))->find();

        $is_version = 1;
        if($store['menu_version'] == 2){
            $is_version = 2;
        }else{
            $database_shop_goods = D('Shop_goods');
            $now_goods = $database_shop_goods->get_goods_by_id($fid);
            if(!$now_goods){//为兼容老版本使用
                $is_version = 2;
            }else {
                //modify garfunkel 判断语言
                $now_goods['name'] = lang_substr($now_goods['name'], C('DEFAULT_LANG'));
                $now_goods['unit'] = lang_substr($now_goods['unit'], C('DEFAULT_LANG'));
                foreach ($now_goods['properties_list'] as $k => $v) {
                    $now_goods['properties_list'][$k]['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                    foreach ($v['val'] as $kk => $vv) {
                        $now_goods['properties_list'][$k]['val'][$kk] = lang_substr($vv, C('DEFAULT_LANG'));
                    }
                    $result['properties_list'][] = $now_goods['properties_list'][$k];
                }

                foreach ($now_goods['spec_list'] as $k => $v) {
                    $now_goods['spec_list'][$k]['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                    foreach ($v['list'] as $kk => $vv) {
                        $now_goods['spec_list'][$k]['list'][$kk]['name'] = lang_substr($vv['name'], C('DEFAULT_LANG'));
                    }
                    //ksort($v['list']);
                    //$now_goods['spec_list'][$k]['list'] = $v['list'];
                }
                $result['spec_list'] = $now_goods['spec_list'];

                //garfunkel add side_dish
                $dish_list = D('Side_dish')->where(array('goods_id' => $fid, 'status' => 1))->select();
                $send_list = array();
                foreach ($dish_list as &$v) {
                    $v['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                    $values = D('Side_dish_value')->where(array('dish_id' => $v['id'], 'status' => 1))->select();
                    foreach ($values as &$vv) {
                        $vv['name'] = lang_substr($vv['name'], C('DEFAULT_LANG'));
                        $vv['price'] = round($vv['price']*$now_goods['goodsDishDiscount'],2);
                        $vv['list'] = array();
                    }
                    if ($values) {
                        $v['list'] = $values;
                        $send_list[] = $v;
                    }
                }

                if ($send_list)
                    $result['side_dish'] = $send_list;
                else
                    $result['side_dish'] = "";

                $result['list'] = $now_goods['list'];
            }
        }

        if($is_version == 2){
            $result['spec_list'] = "";
            $result['properties_list'] = "";

            //$result['list'] = array();

            $dish_list = D('StoreMenuV2')->getProductRelation($fid,$storeId,1);
            $dish_list_new = D('StoreMenuV2')->arrangeDishWap($dish_list,$fid,$storeId);

            $result['side_dish'] = $dish_list_new;
        }

        $result['cart'] = D('Cart')->field(true)->where(array("uid"=>$uid,"fid"=>$fid,'sid'=>$storeId))->order('time desc')->select();

        $this->returnCode(0,'',$result,'success');
    }

    public function reorderById(){
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];

        $order = D('Shop_order')->where(array('order_id'=>$order_id))->find();
        if($order) {
            $store = $this->loadModel()->get_store_by_id($order['store_id'],0,0);
            if($store['is_close'] == 1){
                $this->returnCode(1, 'info', array(), 'Sorry, this store is currently unavailable.');
            }else {
                if ($uid && $order_id) {
                    $order_list = D('Shop_order_detail')->where(array('order_id' => $order_id))->select();
                    if ($order_list) {
                        $add_list = array();
                        foreach ($order_list as $detail) {
                            $data = array();
                            $data['uid'] = $uid;
                            $data['fid'] = $detail['goods_id'];
                            $data['num'] = $detail['num'];
                            $data['sid'] = $detail['store_id'];
                            $data['status'] = 0;
                            $data['spec'] = $detail['spec_id'];
                            $data['proper'] = $detail['pro_id'];
                            $data['dish_id'] = $detail['dish_id'];
                            $data['time'] = date("Y-m-d H:i:s");

                            $add_list[] = $data;
                        }

                        D('Cart')->addAll($add_list);
                    }

                    $this->returnCode(0, '', array(), 'success');
                } else {
                    $this->returnCode(1, 'info', array(), 'Fail');
                }
            }
        }else{
            $this->returnCode(1, 'info', array(), 'Fail');
        }
    }

    public function credit_pay(){
        import('@.ORG.pay.MonerisPay');
        $moneris_pay = new MonerisPay();
        //app 支付标识
        $_POST['rvarwap'] = 2;
        $resp = $moneris_pay->payment($_POST,$_POST['uid'],3);
        if($resp['requestMode'] && $resp['requestMode'] == "mpi"){
            if($resp['mpiSuccess'] == "true"){
                if($resp['version'] == 1)
                    $result = array('error_code' => false,'mode'=>$resp['requestMode'],'PaReq'=>urlencode($resp['MpiPaReq']),'TermUrl' => urlencode($resp['MpiTermUrl']),'MD' => urlencode($resp['MpiMD']),'ACSUrl' => urlencode($resp['MpiACSUrl']),'site_url'=>$resp['MpiTermUrl']);
                if($resp['version'] == 2)
                    $result = array('error_code' => false,'mode'=>$resp['requestMode'],'PaReq'=>'','TermUrl'=>urlencode($resp['challengeURL']),'MD' => '','ACSUrl'=>urlencode($resp['challengeData']),'site_url'=>$resp['site_url']);
                //$this->ajaxReturn($result);
                $this->returnCode(0,'info',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),$resp['message']);
            }
        }

        if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
            //$order = explode("_",$_POST['order_id']);
            //$order_id = $order[1];
            //$url =U("Wap/Shop/status",array('order_id'=>$order_id));

            $this->returnCode(0,'info',array(),'success');
            //$this->success(L('_PAYMENT_SUCCESS_'),$url,true);
        }else{
            $this->returnCode(1,'info',array(),$resp['message']);
//            $this->error($resp['message'],'',true);
        }
    }

    public function credit_pay_android(){
        import('@.ORG.pay.MonerisPay');
        $moneris_pay = new MonerisPay();
        //app 支付标识
        $_POST['rvarwap'] = 2;
        $resp = $moneris_pay->payment($_POST,$_POST['uid'],3);
        if($resp['requestMode'] && $resp['requestMode'] == "mpi"){
            if($resp['mpiSuccess'] == "true"){
                if($resp['version'] == 1)
                    $result = array('error_code' => false,'mode'=>$resp['requestMode'],'PaReq'=>urlencode($resp['MpiPaReq']),'TermUrl' => urlencode($resp['MpiTermUrl']),'MD' => urlencode($resp['MpiMD']),'ACSUrl' => urlencode($resp['MpiACSUrl']),'site_url'=>$resp['MpiTermUrl']);
                if($resp['version'] == 2)
                    $result = array('error_code' => false,'mode'=>$resp['requestMode'],'PaReq'=>'','TermUrl'=>urlencode($resp['challengeURL']),'MD' => '','ACSUrl'=>urlencode($resp['challengeData']),'site_url'=>$resp['site_url']);
                //$this->ajaxReturn($result);
                $this->returnCode(0,'info',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),$resp['message']);
            }
        }

        if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
            //$order = explode("_",$_POST['order_id']);
            //$order_id = $order[1];
            //$url =U("Wap/Shop/status",array('order_id'=>$order_id));
            $result = array('error_code' => false,'mode'=>'','PaReq'=>'','TermUrl' => '','MD' => '','ACSUrl' => '','site_url'=>'');
            $this->returnCode(0,'info',$result,'success');
            //$this->success(L('_PAYMENT_SUCCESS_'),$url,true);
        }else{
            $this->returnCode(1,'info',array(),$resp['message']);
//            $this->error($resp['message'],'',true);
        }
    }

    public function ToPay(){
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];
        $price = $_POST['price'];
        $tip = $_POST['tip'];
        $delivery_discount = $_POST['delivery_discount'] ? $_POST['delivery_discount'] : 0;
        $merchant_reduce = $_POST['merchant_reduce'] ? $_POST['merchant_reduce'] : 0;

        $_POST['cer_type'] = $_POST['cer_type'] ? $_POST['cer_type'] : 3;
        $order = D('Shop_order')->where(array('order_id'=>$order_id))->find();
        if($_POST['pay_type'] == 0){//线下支付 直接进入支付流程
            D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save(array('tip_charge'=>$tip));
            $order_param['order_id'] = $order_id;
            $order_param['order_from'] = 0;
            $order_param['order_type'] = 'shop';
            $order_param['pay_time'] = date();
            $order_param['pay_type'] = 'Cash';
            //$order_param['is_mobile'] = 2;
            $order_param['is_own'] = 0;
            $order_param['third_id'] = 0;

            D('Shop_order')->after_pay($order_param);
        }elseif($_POST['pay_type'] == 4){//余额支付
            //账户余额
            $userInfo = D('User')->get_user($uid);
            $now_money = round($userInfo['now_money'],2);

            $data['balance_pay'] = $price + $tip - $delivery_discount - $merchant_reduce;
            if($now_money >= $data['balance_pay']){
                D('Shop_order')->field(true)->where(array('order_id'=>$order_id))->save($data);

                $order_param = array(
                    'order_id' => $order_id,
                    'pay_type' => '',
                    'order_type'=> 'shop',
                    'third_id' => '',
                    //'is_mobile' => 2,
                    'pay_money' => 0,
                    'order_total_money' => $price + $tip,
                    'balance_pay' => $price + $tip,
                    'merchant_balance' => 0,
                    'is_own'	=> 0
                );

                D('Shop_order')->after_pay($order_param);
            }else {
                $this->returnCode(1, 'info', array(), L('_B_MY_NOMONEY_'));
            }
        }else if($_POST['pay_type'] == 1){//支付宝
            $price = $price + $tip - $delivery_discount - $merchant_reduce;
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price,$_POST['ip'],$_POST['cer_type']);
            if($result['resCode'] == 'SUCCESS'){
                $result['main_id'] = $order_id;
                $result['orderNo'] = $order['real_orderid'];
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),'fail');
            }
        }else if($_POST['pay_type'] == 2){//微信支付
            $price = $price + $tip - $delivery_discount - $merchant_reduce;
            $result = $this->loadModel()->WeixinAndAli($_POST['pay_type'],$order_id,$price,$_POST['ip'],$_POST['cer_type']);
            if($result['resCode'] == 'SUCCESS'){
                $result['main_id'] = $order_id;
                $result['orderNo'] = $order['real_orderid'];
                $this->returnCode(0,'result',$result,'success');
            }else{
                $this->returnCode(1,'info',array(),'fail');
            }
        }

        $this->returnCode(0,'info',array(),'success');
    }

    public function user_card_default(){
        $uid = $_POST['uid'];
        $card = D('User_card')->getCardListByUid($uid);

        $save_price = 251;
        $send_card = $card[0];
        foreach ($card as $v){
            if($v['is_default'] == 1){
                $send_card = $v;
            }
        }
        if($card) {
            //$send_card = $card[0];
            $send_card['save_price'] = $save_price;
            $this->returnCode(0, 'info', $send_card, 'success');
        }else {
            $this->returnCode(0, 'info', array('id' => '0','save_price'=>251), 'success');
        }
    }

    public function getUserCard(){
        $uid = $_POST['uid'];
        $card_list = D('User_card')->getCardListByUid($uid);
        if(!$card_list)
            $card_list = array();
        else{
            foreach ($card_list as $k=>$v){
                $card_list[$k]['expiry'] = transYM($v['expiry']);
            }
        }

        $this->returnCode(0,'info',$card_list,'success');
    }

    public function setDefaultCard(){
        $uid = $_POST['uid'];
        $card_id = $_POST['card_id'];

        D('User_card')->clearIsDefaultByUid($uid);

        D('User_card')->field(true)->where(array('id'=>$card_id))->save(array('is_default'=>1));

        $card_list = D('User_card')->getCardListByUid($uid);
        foreach ($card_list as $k=>$v){
            $card_list[$k]['expiry'] = transYM($v['expiry']);
        }

        $this->returnCode(0,'info',$card_list,'success');
    }

    public function edit_card(){
        $uid = $_POST['uid'];
        $data['name'] = $_POST['name'];
        $data['card_num'] = $_POST['card_num'];
        $data['expiry'] = transYM($_POST['expiry']);

        //如果 is_default 存在，清空之前的default
        if($_POST['is_default']){
            D('User_card')->clearIsDefaultByUid($uid);
        }
        $data['is_default'] = $_POST['is_default'] ? $_POST['is_default'] : 0;

        $data['uid'] = $uid;

        if($_POST['card_id'] && $_POST['card_id'] != ''){
            $data['id'] = $_POST['card_id'];
            D('User_card')->field(true)->where(array('id'=>$data['id']))->save($data);
            $this->returnCode(0,'info',array(),'success');
        }else {
            $isC = D('User_card')->getCardByUserAndNum($data['uid'], $data['card_num']);
            if ($isC) {
//                $this->error(L('_CARD_EXIST_'));
                $this->returnCode(1,'info',array(),'fail');
            } else {
                $data['create_time'] = date("Y-m-d H:i:s");
                D('User_card')->field(true)->add($data);
                $this->returnCode(0,'info',array(),'success');
            }
        }
    }

    public function delCard(){
        $uid = $_POST['uid'];

        if($_POST['card_id']){
            D('User_card')->field(true)->where(array('id'=>$_POST['card_id']))->delete();
            //$this->success(L('_OPERATION_SUCCESS_'));
        }

        $card_list = D('User_card')->getCardListByUid($uid);
        if(!$card_list) $card_list = array();

        $this->returnCode(0,'info',$card_list,'success');
    }

    public function getCouponByUser(){
        $uid = $_POST['uid'];
        $amount = $_POST['amount'] ? $_POST['amount'] : -1;

        $coupon_list = D('System_coupon')->get_user_coupon_list($uid);

        //获取活动优惠券
        $event_coupon_list = D('New_event')->getUserCoupon($uid);
        if(!$coupon_list) $coupon_list = array();
        if(count($event_coupon_list) > 0){
            foreach ($event_coupon_list as &$system_coupon) {
                $system_coupon['id'] = $system_coupon['coupon_id'] . '_' . $system_coupon['id'];
            }
            $coupon_list = array_merge($coupon_list,$event_coupon_list);
        }

        $tmp = array();
        $canTmp = array();
        $notCanTmp = array();
        foreach ($coupon_list as $key => $v) {
            if(!$v['is_use']){
                $coupon = $this->arrange_coupon($v);
                if($amount > 0){
                    if($v['order_money'] <= $amount){
                        $coupon['canUse'] = 1;
                        $canTmp[] = $coupon;
                    }else{
                        $coupon['canUse'] = 0;
                        $notCanTmp[] = $coupon;
                    }
                }else {
                    $coupon['canUse'] = 1;
                    $tmp[] = $coupon;
                }
            }
//            if (!empty($tmp[$v['is_use']][$v['coupon_id']])) {
//                $tmp[$v['is_use']][$v['coupon_id']]['get_num']++;
//            } else {
//                $tmp[$v['is_use']][$v['coupon_id']] = $v;
//                $mer = M('Merchant')->where(array('mer_id'=>$v['mer_id']))->find();
//                $tmp[$v['is_use']][$v['coupon_id']]['merchant']=$mer['name'];
//                $tmp[$v['is_use']][$v['coupon_id']]['get_num'] = 1;
//            }

        }

//        if($amount > 0) {
//            $last_names = array_column($tmp, 'canUse');
//            array_multisort($last_names, SORT_DESC, $tmp);
//        }
        if($amount > 0)
            $tmp = array_merge($canTmp,$notCanTmp);

        $this->returnCode(0,'info',$tmp,'success');
    }

    public function arrange_coupon($coupon){
        $coupon['name'] = lang_substr($coupon['name'],C('DEFAULT_LANG'));
        $coupon['des'] = lang_substr($coupon['des'],C('DEFAULT_LANG'));

        if($coupon['discount_desc'])
            $data['desc'] = $coupon['discount_desc'];

        $data['name'] = $coupon['name'];
        //$data['desc'] = $coupon['des'];
        $data['rowiID'] = $coupon['id'];
        $data['limitMoney'] = $coupon['order_money'];
        $data['money'] = $coupon['discount'];
        $data['beginDate'] = date('Y.m.d',$coupon['start_time']);
        $data['endDate'] = date('Y.m.d',$coupon['end_time']);
        $data['type'] = "2";
        $data['is_new'] = $coupon['allow_new'];

        if($coupon['is_use'] == 0)
            $data['status'] = $coupon['is_use'];
        elseif ($coupon['is_use'] == 1)
            $data['status'] = "2";
        else
            $data['status'] = "3";


        return $data;
    }
    //获取订单可使用的优惠券
    public function getCanCoupon(){
        $uid = $_POST['uid'];
        //订单金额
        $amount = $_POST['amount'] ? $_POST['amount'] : -1;
//        $today = time();

//        $sql = 'select c.coupon_id,h.id,c.discount,c.order_money from '.C('DB_PREFIX').'system_coupon_hadpull as h left join '.C('DB_PREFIX').'system_coupon as c on h.coupon_id = c.coupon_id';
//        $sql .= ' where h.uid = '.$uid.' and h.is_use = 0 and c.start_time <='.$today.' and c.end_time >='.$today.' and c.order_money <='.$amount;
//        $sql .= ' order by c.discount desc,c.end_time asc';
//
//        $model = new Model();
//        $coupon_list = $model->query($sql);
        $coupon_list = D('System_coupon')->get_user_coupon_list($uid);

        //if(empty($coupon_list)){
            $event_coupon = D('New_event')->getUserCoupon($uid,0,$amount);
            if($event_coupon) {
                foreach ($event_coupon as &$system_coupon) {
                    $system_coupon['id'] = $system_coupon['coupon_id'] . '_' . $system_coupon['id'];
                    //$coupon_list[] = $system_coupon;
                }
            }
        //}

        //$event_coupon = D('New_event')->getUserCoupon($uid,0,$amount);
        if(!$coupon_list) $coupon_list = array();
        if(count($event_coupon) > 0){
            $coupon_list = array_merge($coupon_list,$event_coupon);
        }

        $tmp = array();
        foreach ($coupon_list as $key => $v) {
            if($amount < 0){
                if (!$v['is_use']) {
                    $coupon = $this->arrange_coupon($v);
                    $tmp[] = $coupon;
                }
            }else{
                if (!$v['is_use'] && $v['order_money']<=$amount) {
                    $coupon = $this->arrange_coupon($v);
                    $tmp[] = $coupon;
                }
            }
        }
        return $tmp;
//        $this->returnCode(0,'info',$tmp,'success');
    }

    public function orderRefund(){
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];

        $now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $uid));
        if(empty($now_order)){
            $this->returnCode(1,'info',array(),L('_B_MY_NOORDER_'));
        }
        $store_id = $now_order['store_id'];
        //$mer_id = $now_order['mer_id'];
        if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERDEALING_'));
        }

        $data_shop_order['cancel_type'] = 5;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
        if ($now_order['pay_type'] == 'offline' || $now_order['pay_type'] == 'Cash') {
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
            $data_shop_order['status'] = 4;
            if (D('Shop_order')->data($data_shop_order)->save()) {
                $return = $this->shop_refund_detail($now_order, $store_id);
                if ($return['error_code']) {
                    $this->returnCode(1,'info',array(),$return['msg']);
                }
            } else {
                $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE_'));
            }
        }else{
            if($now_order['pay_type'] == 'moneris'){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
                $resp = $moneris_pay->refund($uid,$now_order['order_id']);
//                var_dump($resp);die();
                if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->returnCode(1,'info',array(),$resp['message']);
                }
            }else if($now_order['pay_type'] == 'weixin' || $now_order['pay_type'] == 'alipay'){
                import('@.ORG.pay.IotPay');
                $IotPay = new IotPay();
                $result = $IotPay->refund($uid,$now_order['order_id'],'APP');
                if ($result['retCode'] == 'SUCCESS' && $result['resCode'] == 'SUCCESS'){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->returnCode(1,'info',array(),$result['retMsg']);
                }
            }

            $return = $this->shop_refund_detail($now_order, $store_id);
            if ($return['error_code']) {
                $this->returnCode(1,'info',array(),$return['msg']);
            }

//            if (empty($now_order['pay_type'])) {
//                $data_shop_order['order_id'] = $now_order['order_id'];
//                $data_shop_order['status'] = 4;
//                $data_shop_order['last_time'] = time();
//                D('Shop_order')->data($data_shop_order)->save();
//            }
//            if(empty($go_refund_param['msg'])){
//                $go_refund_param['msg'] .= L('_B_MY_ORDERCANCELLEDACCESS_');
//            }
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
        }

        $store = D('Merchant_store')->where(array('store_id'=>$store_id))->find();
        if($store['link_type'] == 1) {
            $now_order['link_id'] = $store['link_id'];

            import('@.ORG.Deliverect.Deliverect');
            $deliverect = new Deliverect();
            $result = $deliverect->createDelOrder($now_order);
        }

        $this->returnCode(0,'info',array(),'success');
    }

    private function shop_refund_detail($now_order, $store_id)
    {
        $order_id  = $now_order['order_id'];

        $mer_store = D('Merchant_store')->where(array('mer_id' => $this->mer_id, 'store_id' => $store_id))->find();

        //如果使用了积分 2016-1-15
        if ($now_order['score_used_count'] != 0) {
            $result = D('User')->add_score($now_order['uid'],$now_order['score_used_count'],L('_B_MY_REFUND_') . $mer_store['name'] . '(' . $order_id . ') '.$this->config['score_name'].L('_B_MY_ROLLBACK_'));
            $param = array('refund_time' => time());
            if ($result['error_code']) {
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $result['error_code'] || $data_shop_order['status'] = 4;
            D('Shop_order')->data($data_shop_order)->save();
            if ($result['error_code']) {
                return $result;
            }
            $go_refund_param['msg'] .= ' '.$result['msg'];
        }

        //平台余额退款
        if ($now_order['balance_pay'] != '0.00') {
            $add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$order_id.' 增加余额',0,0,0,"Order Cancellation (Order # ".$order_id.")");

            $param = array('refund_time' => time());
            if($result['error_code']){
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }

            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $result['error_code'] || $data_shop_order['status'] = 4;
            D('Shop_order')->data($data_shop_order)->save();
            if ($result['error_code']) {
                return $result;
            }
            $go_refund_param['msg'] .= ' 平台余额退款成功';
        }
        //商家会员卡余额退款
        if ($now_order['merchant_balance'] != '0.00'||$now_order['card_give_money']!='0.00') {
            //$result = D('Member_card')->add_card($now_order['uid'],$now_order['mer_id'],$now_order['merchant_balance'],L('_B_MY_REFUND_') . $mer_store['name'] . '(' . $order_id . ')  增加余额');
            $result = D('Card_new')->add_user_money($now_order['mer_id'],$now_order['uid'],$now_order['merchant_balance'],$now_order['card_give_money'],0,L('_B_MY_REFUND_').$now_order['order_name'].' 增加余额',L('_B_MY_REFUND_').$now_order['order_name'].' 增加赠送余额');

            $param = array('refund_time' => time());
            if ($result['error_code']) {
                $param['err_msg'] = $result['msg'];
            } else {
                $param['refund_id'] = $now_order['order_id'];
            }

            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $result['error_code'] || $data_shop_order['status'] = 4;
            D('Shop_order')->data($data_shop_order)->save();
            if ($result['error_code']) {
                return $result;
            }
            $go_refund_param['msg'] .= $result['msg'];
        }

        //退款打印
        $msg = ArrayToStr::array_to_str($now_order['order_id'], 'shop_order');
        $op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
        $op->printit($this->mer_id, $store_id, $msg, 3);

        $str_format = ArrayToStr::print_format($now_order['order_id'], 'shop_order');
        foreach ($str_format as $print_id => $print_msg) {
            $print_id && $op->printit($this->mer_id, $store_id, $print_msg, 3, $print_id);
        }

        //退款时销量回滚
        if (($now_order['paid'] == 1 || $now_order['reduce_stock_type'] == 1) && $now_order['is_rollback'] == 0) {
            $goods_obj = D("Shop_goods");
            foreach ($now_order['info'] as $menu) {
                $goods_obj->update_stock($menu, 1);//修改库存
            }
            D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_rollback' => 1));
        }
        D("Merchant_store_shop")->where(array('store_id' => $now_order['store_id'], 'sale_count' => array('gt', 0)))->setDec('sale_count', 1);
        //退款时销量回滚

        $go_refund_param['error_code'] = false;
        return $go_refund_param;
    }

    public function coupon_code(){
        $code = $_POST['code'];
        $uid = $_POST['uid'];

        $coupon = D('System_coupon')->field(true)->where(array('notice'=>$code))->find();
        $cid = $coupon['coupon_id'];

        if($cid){
            $l_id = D('System_coupon_hadpull')->field(true)->where(array('uid'=>$uid,'coupon_id'=>$cid))->find();

            if($l_id == null) {
                $result = D('System_coupon')->had_pull($cid, $uid);
                if(isset($result) && $result['error_code'] == 0) {
                    $coupon = $this->arrange_coupon($result['coupon']);
                    $this->returnCode(0, 'info', $coupon, 'success');
                }else{
                    $this->returnCode(1,'info',array(),$result['msg']);
                }
            }else
                $this->returnCode(1,'info',array(),L('_AL_EXCHANGE_CODE_'));
        }else{
            $this->returnCode(1,'info',array(),L('_NOT_EXCHANGE_CODE_'));
        }
    }
    //未支付 取消订单
    public function cancelOrder(){
        $uid = $_POST['uid'];
        $id = $_POST['order_id'];

        if ($order = M('Shop_order')->where(array('order_id' => $id, 'uid' => $uid))->find()) {
// 			if ($order['status'] != 0 ) $this->error_tips('商家已经处理了此订单，现在不能取消了！');
            if ($order['paid'] == 1 )
                $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE1_'));

// 			D("Merchant_store_meal")->where(array('store_id' => $order['store_id']))->setDec('sale_count', 1);
            /* 粉丝行为分析 */
//            $this->behavior(array('mer_id' => $order['mer_id'], 'biz_id' => $order['store_id']));

            M('Shop_order')->where(array('order_id' => $id, 'uid' => $uid))->save(array('status' => 5, 'is_rollback' => 1));//取消未支付的订单
            D('Shop_order_log')->add_log(array('order_id' => $id, 'status' => 10));

            if (($order['paid'] == 1 || $order['reduce_stock_type'] == 1) && $order['is_rollback'] == 0) {
                $details = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
                $goods_db = D("Shop_goods");
                foreach ($details as $menu) {
                    $goods_db->update_stock($menu, 1);//修改库存
                }
            }

            $this->returnCode(0,'info',array(),L('_B_MY_CANCELLACCESS1_'));
        } else {
            $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE1_'));
        }
    }

    //已支付 取消订单
    public function delOrder()
    {
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];

        $now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $uid));
        if(empty($now_order)){
            $this->returnCode(1,'info',array(),L('_B_MY_NOORDER_'));
        }
        $store_id = $now_order['store_id'];
        $this->mer_id = $now_order['mer_id'];
        if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERDEALING_'));
        }
        if (empty($now_order['paid'])) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERNOPAY_'));
        }
        if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERMUSTNOPAID_'));
        } elseif ($now_order['status'] > 3 && !($now_order['paid'] == 1 && $now_order['status'] == 5)) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERMUSTNOPAID_'));
        }
        $mer_store = D('Merchant_store')->where(array('mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id']))->find();
        $my_user = D('User')->field(true)->where(array('uid' => $now_order['uid']))->find();

        //线下支付退款
        $data_shop_order['cancel_type'] = 5;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消）
        if ($now_order['pay_type'] == 'offline' || $now_order['pay_type'] == 'Cash') {
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
            $data_shop_order['status'] = 4;
            if (D('Shop_order')->data($data_shop_order)->save()) {
                $return = $this->shop_refund_detail($now_order, $store_id);
                if ($return['error_code']) {
                    $this->returnCode(1,'info',array(),$result['msg']);
                } else {
                    //add garfunkel 取消订单成功 发送消息
                    if (C('config.sms_shop_cancel_order') == 1 || C('config.sms_shop_cancel_order') == 3) {
                        $sms_data['uid'] = $now_order['uid'];
                        $sms_data['mobile'] = $now_order['userphone'] ? $now_order['userphone'] : $my_user['phone'];
                        $sms_data['sendto'] = 'user';
                        $sms_data['content'] = '您在 ' . $mer_store['name'] . '店中下的订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被您取消并退款，欢迎再次光临！';
                        $sms_data['params'] = [
                            $order_id,
                            date('Y-m-d H:i:s'),
                            lang_substr($mer_store['name'],'en-us')
                        ];
                        $sms_data['tplid'] = 171187;
                        //Sms::sendSms2($sms_data);
                        $sms_txt = "Your order (".$order_id.") has been successfully canceled at ".date('Y-m-d H:i:s')." at ".lang_substr($mer_store['name'],'en-us')." store, we are looking forward to seeing you again.";
                        //Sms::telesign_send_sms($sms_data['mobile'],$sms_txt,0);
                        Sms::sendTwilioSms($sms_data['mobile'],$sms_txt);
                    }
                    if (C('config.sms_shop_cancel_order') == 2 || C('config.sms_shop_cancel_order') == 3) {
                        $sms_data['uid'] = 0;
                        $sms_data['mobile'] = $mer_store['phone'];
                        $sms_data['sendto'] = 'merchant';
                        $sms_data['content'] = '顾客' . $now_order['username'] . '的预定订单(订单号：' . $order_id . '),在' . date('Y-m-d H:i:s') . '时已被客户取消并退款！';
                        $sms_data['params'] = [
                            $now_order['username'],
                            $order_id,
                            date('Y-m-d H:i:s')
                        ];
                        $sms_data['tplid'] = 169151;
                        //Sms::sendSms2($sms_data);

                        //add garfunkel 添加语音
                        $txt = "This is a important message from island life , the customer has canceled the last order.";
                        Sms::send_voice_message($sms_data['mobile'],$txt);
                    }
                    $this->returnCode(0,'info',array(),L('_B_MY_USEOFFLINECHANGEREFUND_'));
                }
            } else {
                $this->returnCode(1,'info',array(),L('_B_MY_CANCELLLOSE_'));
            }
        }else{
            if($now_order['pay_type'] == 'moneris'){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
                $resp = $moneris_pay->refund($uid,$now_order['order_id']);
//                var_dump($resp);die();
                if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    $this->returnCode(1,'info',array(),$resp['message']);
                }
            }else if ($now_order['payment_money'] != '0.00') {
                if ($now_order['is_own']) {
                    $pay_method = array();
                    $merchant_ownpay = D('Merchant_ownpay')->field('mer_id', true)->where(array('mer_id' => $now_order['mer_id']))->find();
                    foreach($merchant_ownpay as $ownKey=>$ownValue){
                        $ownValueArr = unserialize($ownValue);
                        if($ownValueArr['open']){
                            $ownValueArr['is_own'] = true;
                            $pay_method[$ownKey] = array('name'=>$this->getPayName($ownKey),'config'=>$ownValueArr);
                        }
                    }
                    $now_merchant = D('Merchant')->get_info($now_order['mer_id']);
                    if ($now_merchant['sub_mch_refund'] && $this->config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0) {
                        $pay_method['weixin']['config']['pay_weixin_appid'] = $this->config['pay_weixin_appid'];
                        $pay_method['weixin']['config']['pay_weixin_appsecret'] = $this->config['pay_weixin_appsecret'];
                        $pay_method['weixin']['config']['pay_weixin_mchid'] = $this->config['pay_weixin_sp_mchid'];
                        $pay_method['weixin']['config']['pay_weixin_key'] = $this->config['pay_weixin_sp_key'];
                        $pay_method['weixin']['config']['sub_mch_id'] = $now_merchant['sub_mch_id'];
                        $pay_method['weixin']['config']['pay_weixin_client_cert'] = $this->config['pay_weixin_sp_client_cert'];
                        $pay_method['weixin']['config']['pay_weixin_client_key'] = $this->config['pay_weixin_sp_client_key'];
                        $pay_method['weixin']['config']['is_own'] = 1 ;
                    }
                } else {
                    $pay_method = D('Config')->get_pay_method(0,0,1);
                }

                if (empty($pay_method)) {
                    $this->returnCode(1,'info',array(),L('_B_MY_NOPAIMENTMETHOD_'));
                }
                if (empty($pay_method[$now_order['pay_type']])) {
                    $this->returnCode(1,'info',array(),L('_B_MY_CHANGEPAIMENT_'));
                }

                $pay_class_name = ucfirst($now_order['pay_type']);
                $import_result = import('@.ORG.pay.'.$pay_class_name);
                if(empty($import_result)){
                    $this->returnCode(1,'info',array(),L('_B_MY_THISPAIMENTNOTOPEN_'));
                }
                D('Shop_order')->where(array('order_id' => $now_order['order_id']))->save(array('is_refund' => 1));
                $now_order['order_type'] = 'shop';
                $now_order['order_id'] = $now_order['orderid'];
                if($now_order['is_mobile_pay']==3){
                    $pay_method[$now_order['pay_type']]['config'] =array(
                        'pay_weixin_appid'=>$this->config['pay_wxapp_appid'],
                        'pay_weixin_key'=>$this->config['pay_wxapp_key'],
                        'pay_weixin_mchid'=>$this->config['pay_wxapp_mchid'],
                        'pay_weixin_appsecret'=>$this->config['pay_wxapp_appsecret'],
                    );
                }
                $pay_class = new $pay_class_name($now_order, $now_order['payment_money'], $now_order['pay_type'], $pay_method[$now_order['pay_type']]['config'], $this->user_session, 1);
                $go_refund_param = $pay_class->refund();

                $now_order['order_id'] = $order_id;
                $data_shop_order['order_id'] = $order_id;
                $data_shop_order['refund_detail'] = serialize($go_refund_param['refund_param']);
                if (empty($go_refund_param['error']) && $go_refund_param['type'] == 'ok') {
                    $data_shop_order['status'] = 4;
                }
                $data_shop_order['last_time'] = time();
                D('Shop_order')->data($data_shop_order)->save();
                if($data_shop_order['status'] != 4){
                    $this->returnCode(1,'info',array(),$go_refund_param['msg']);
                }else{
                    $go_refund_param['msg'] ="在线支付退款成功 ";
                }
            }


            $return = $this->shop_refund_detail($now_order, $store_id);
            if ($return['error_code']) {
                $this->returnCode(1,'info',array(),$return['msg']);
            } else {
                $go_refund_param['msg'] .= $return['msg'];
            }

            if (empty($now_order['pay_type'])) {
                $data_shop_order['order_id'] = $now_order['order_id'];
                $data_shop_order['status'] = 4;
                $data_shop_order['last_time'] = time();
                D('Shop_order')->data($data_shop_order)->save();
                $go_refund_param['msg'] .= L('_B_MY_ORDERCANCELLEDACCESS_');
            }
            if(empty($go_refund_param['msg'])){
                $go_refund_param['msg'] .= L('_B_MY_ORDERCANCELLEDACCESS_');
            }
            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
            $this->returnCode(0,'info',array(),$go_refund_param['msg']);
        }
    }

    public function add_comment(){
        $uid = $_POST['uid'];
        $order_id = $_POST['order_id'];
        $comment = $_POST['comment'];
        $score = $_POST['score'];
        $score_store = $_POST['score1'];
        $score_deliver = $_POST['score2'];
        $is_late = $_POST['righttime'] == 1 ? 0 : 1;

        $comment_deliver = $_POST['comment_deliver'] ? $_POST['comment_deliver'] : "";

        $now_order = D('Shop_order')->get_order_detail(array('uid' => $uid, 'order_id' => $order_id));

        if (empty($now_order)) {
            $this->returnCode(1,'info',array(),L('_B_MY_NOORDER_'));
        }
        if (empty($now_order['paid'])) {
            $this->returnCode(1,'info',array(),L('_B_MY_NOTCONSUMENOCOMMENT_'));
        }
        if ($now_order['status'] == 3) {
            $this->returnCode(1,'info',array(),L('_B_MY_HAVECOMMENTD_'));
        }

        $goodsids = array();
        $goods_ids = array();
        $goods = '';
        $pre = '';
        if (isset($now_order['info'])) {
            foreach ($now_order['info'] as $row) {
                if (!in_array($row['goods_id'], $goodsids)) {
                    $goodsids[] = $row['goods_id'];
                    if (in_array($row['goods_id'], $goods_ids)) {
                        $goods .= $pre . $row['name'];
                        $pre = '#@#';
                    }
                }
            }
        }
        $database_reply = D('Reply');

        $data_reply['parent_id'] = $now_order['store_id'];
        $data_reply['store_id'] = $now_order['store_id'];
        $data_reply['mer_id'] = $now_order['mer_id'];
        $data_reply['score'] = $score;
        $data_reply['order_type'] = 3;
        $data_reply['order_id'] = intval($now_order['order_id']);
        $data_reply['anonymous'] = 1;
        $data_reply['comment'] = $comment;
        $data_reply['uid'] = $uid;
        $data_reply['pic'] = '';
        $data_reply['add_time'] = $_SERVER['REQUEST_TIME'];
        $data_reply['add_ip'] = get_client_ip(1);
        $data_reply['goods'] = $goods ? $goods : "";
        $data_reply['score_store'] = $score_store;
        $data_reply['score_deliver'] = $score_deliver;
        $data_reply['is_late'] = $is_late;
        $data_reply['comment_deliver'] = $comment_deliver;

        $data_reply['merchant_reply_content'] = "";
        $data_reply['merchant_reply_time'] = 0;

        if(!checkEnglish($comment) && trim($comment) != ''){
            $data_reply['comment_en'] = translationCnToEn($comment);
        }else{
            $data_reply['comment_en'] = '';
        }

        if(!checkEnglish($comment_deliver) && trim($comment_deliver) != ''){
            $data_reply['comment_deliver_en'] = translationCnToEn($comment_deliver);
        }else{
            $data_reply['comment_deliver_en'] = '';
        }

// 		echo "<pre/>";
// 		print_r($data_reply);die;
        if ($database_reply->data($data_reply)->add()) {
            D('Merchant_store')->setInc_shop_reply($now_order['store_id'], $score);
            D('Shop_order')->change_status($now_order['order_id'], 3);
            D('Shop_order_log')->add_log(array('order_id' => $now_order['order_id'], 'status' => 8));
            foreach ($goods_ids as $goods_id) {
                if (in_array($goods_id, $goodsids)) {
                    D('Shop_goods')->where(array('goods_id' => $goods_id))->setInc('reply_count', 1);
                    D('Shop_order_detail')->where(array('goods_id' => $goods_id, 'order_id' => $order_id))->save(array('is_goods' => 1));
                }
            }

            if($this->config['feedback_score_add']>0){
                $user = D('User')->field(true)->where(array('uid'=>$uid))->find();

                D('User')->add_extra_score($this->$uid,$this->config['feedback_score_add'],$this->config['shop_alias_name'].L('_B_MY_COMMENTGET_').$this->config['feedback_score_add'].$this->config['score_name']);
                D('Scroll_msg')->add_msg('feedback',$uid,L('_B_MY_USER_').$user['nickname'].date('Y-m-d H:i',$_SERVER['REQUEST_TIME']).L('_B_MY_COMMENT_').$this->config['shop_alias_name'].L('_B_MY_GET_').$this->config['feedback_score_add'].$this->config['score_name']);
            }

            $this->returnCode(0,'info',array(),L('_B_MY_COMMENTACCESS_'));
//            exit(json_encode(array('status' => 1, 'msg' => L('_B_MY_COMMENTACCESS_'),  'url' => U('Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])))));
//            $this->success_tips(L('_B_MY_COMMENTACCESS_'), U('Shop/status', array('order_id' => $now_order['order_id'], 'mer_id' => $now_order['mer_id'], 'store_id' => $now_order['store_id'])));
        }
        $this->returnCode(1,'info',array(),L('_B_MY_COMMENTLOSE_'));
    }

    public function get_user_info(){
        $uid = $_POST['uid'];
        $userInfo = D('User')->get_user($uid);
        $info['now_money'] = round($userInfo['now_money'],2);
        $info['phone'] = $userInfo['phone'];
        $info['email'] = $userInfo['email'];
        $coupon = $this->getCanCoupon();
        $info['coupon_num'] = count($coupon);
        $user_code = D('User')->getUserInvitationCode($uid);
        $info['invi_code'] = strtoupper($user_code);

        if(isset($_POST['order_id'])){
            $order_id = $_POST['order_id'];
            $sid = D('Shop_order')->where(array('order_id'=>$order_id))->find()['store_id'];
            $store = D('Store')->get_store_by_id($sid);
            $info['pay_method'] = explode('|',$store['pay_method']);
        }else{
            $event_list = D('New_event')->getEventList(1,2);
            if($event_list){
                $event = reset($event_list);
                $event['name'] = lang_substr($event['name'],C('DEFAULT_LANG'));
                $event['desc'] = lang_substr($event['desc'],C('DEFAULT_LANG'));
                $info['is_event'] = 1;
                $info['event'] = $event;
            }else{
                $info['is_event'] = 0;
            }
        }

        $this->returnCode(0,'info',$info,'success');
    }

    public function set_nickname(){
        $uid = $_POST['uid'];
        $nickname = $_POST['nickname'];

        if($nickname && $nickname!= ''){
            D('User')->where(array('uid'=>$uid))->save(array('nickname'=>$nickname));
        }

        $this->returnCode(0,'info',array(),'success');
    }

    public function changePassword(){
        $uid = $_POST['uid'];
        $oldPwd = $_POST['oldPwd'];
        $newPwd = $_POST['newPwd'];

        $user = D('User')->where(array('uid'=>$uid))->find();
        if($user['pwd'] != md5($oldPwd)){
            $this->returnCode(1,'info',array(),L('_API_PASSWORD_ERROR'));
        }else{
            D('User')->where(array('uid'=>$uid))->save(array('pwd'=>md5($newPwd)));
            $this->returnCode(0,'info',array(),'success');
        }
    }

    public function setPhone(){
        $uid = $_POST['uid'];
        $phone = $_POST['phone'];
        $vcode = $_POST['vcode'];
        $database_user = D('User');
        if(empty($_POST['phone'])){
            $this->returnCode(1,'info',array(),L('_B_LOGIN_ENTERPHONENO_'));
        }

        $condition_user['phone'] = $_POST['phone'];
        if($database_user->field(true)->where($condition_user)->find()){
            $this->returnCode(1,'info',array(),L('_API_PHONE_ERROR'));
        }

        if($vcode){
            $sms_verify_result = D('Smscodeverify')->verify($vcode, $_POST['phone']);
            if ($sms_verify_result['error_code']) {
                $this->returnCode(1,'info',array(),L('_API_VERCODE_ERROR'));
            } else {
                $modifypwd = $sms_verify_result['modifypwd'];
            }
        }

        $database_user->where(array('uid'=>$uid))->save(array('phone'=>$phone));
        $this->returnCode(0,'info',array(),'success');
    }

    public function setEmail(){
        $uid = $_POST['uid'];
        if($_POST['email']){
            $result = D('User')->save_user($uid,'email',$_POST['email']);
            if($result['error']){
                $this->returnCode(1,'info',array(),'Error');
            }else{
                $this->returnCode(0,'info',array(),'success');
            }
        }else{
            $this->returnCode(1,'info',array(),'Error');
        }
    }

    public function get_invitation(){
        $uid = $_POST['uid'];

        $user_code = D('User')->getUserInvitationCode($uid);
        $info['invi_code'] = strtoupper($user_code);

        $event_list = D('New_event')->getEventList(1,2);
        if($event_list){
            $event = reset($event_list);
            $event['name'] = lang_substr($event['name'],C('DEFAULT_LANG'));
            $event['desc'] = lang_substr($event['desc'],C('DEFAULT_LANG'));
            $info['is_event'] = 1;
            $info['event'] = $event;
        }else{
            $info['is_event'] = 0;
        }

        $link = C('config.site_url')."/invite/".base64_encode($user_code);
        $info['link'] = $link;

        $userInfo = D('User')->get_user($uid);

        $msg = $userInfo['nickname']." invites you to order delivery from Tutti! Sign up using your code ".strtoupper($user_code)." or the link below to get $".$event['coupon_amount']." in coupons when you place your first order! ";
        $info['msg'] = $msg;

        $this->returnCode(0,'info',$info,'success');
    }

    public function getRechargeDis(){
        $config = D('Config')->get_config();
        $recharge_txt = $config['recharge_discount'];
        $recharge = explode(",",$recharge_txt);
        $recharge_list = array();
        foreach ($recharge as $v){
            $v_a = explode("|",$v);
            $recharge_list[$v_a[0]] = $v_a[1];
        }

        krsort($recharge_list);
        $this->returnCode(0,'info',$recharge_list,'success');
    }

    public function getRechargeDisForAndroid(){
        $config = D('Config')->get_config();
        $recharge_txt = $config['recharge_discount'];
        if($recharge_txt == ''){
            $this->returnCode(0, 'info', array(), 'success');
        }else {
            $recharge = explode(",", $recharge_txt);
            $recharge_list = array();
            foreach ($recharge as $v) {
                $v_a = explode("|", $v);
                $t_value['amount'] = $v_a[0];
                $t_value['discount'] = $v_a[1];

                $recharge_list[] = $t_value;
            }

            $score = [];
            foreach ($recharge_list as $key => $value) {
                $score[$key] = $value['amount'];
            }
            array_multisort($score, SORT_ASC, $recharge_list);
            $this->returnCode(0, 'info', $recharge_list, 'success');
        }
    }

    public function createRechargeOrder(){
        $uid = $_POST['uid'];
        $data_user_recharge_order['uid'] = $uid;
        $money = floatval($_POST['money']);
        if(empty($money) || $money > 10000){
            //$this->error('请输入有效的金额！最高不能超过1万元。');
            $this->returnCode(1,'info',array(),'请输入有效的金额！最高不能超过1万元。');
        }
        if($_POST['label']){
            $data_user_recharge_order['label'] = $_POST['label'];
        }
        $data_user_recharge_order['money'] = $money;
        // $data_user_recharge_order['order_name'] = '帐户余额在线充值';
        $data_user_recharge_order['add_time'] = $_SERVER['REQUEST_TIME'];
        $data_user_recharge_order['is_mobile_pay'] = $_POST['cer_type'] ? $_POST['cer_type'] :2;

        if($order_id = D('User_recharge_order')->data($data_user_recharge_order)->add()){
            $this->returnCode(0,'info',$order_id,'success');
        }
    }

    public function getRechargeList(){
        $page = $_POST['page'];
        $uid = $_POST['uid'];

        $transaction = D('User_money_list')->get_list($uid,$page,20);
        $transaction['count'] = 20;
        foreach($transaction['money_list'] as $k=>$v){
            $transaction['money_list'][$k]['time_s'] = date('Y-m-d H:i',$v['time']);
            if(C('DEFAULT_LANG') != 'zh-cn' && $v['desc_en'] != ''){
                $transaction['money_list'][$k]['desc'] = $v['desc_en'];
            }
        }
        if(!$transaction['money_list'])
            $transaction['money_list'] = array();

        unset($transaction['pagebar']);
        $this->returnCode(0,'info',$transaction,'success');
    }

    public function testDistance(){
//        die('henhao');
        $from = $_GET['from'];
        $aim = $_GET['aim'];
        $url = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$from.'&destination='.$aim.'&key=AIzaSyAxHAPoWlRu2Mz8APLwM8Ae6B3x1MJUlvU&language=en';
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlGet($url);
        $result = json_decode($result);

        $this->returnCode(0,'info',$result,'success');
    }

    public function updateAssign(){
        $id = D('Deliver_assign')->check_assign();

        //garfunkel add 暂时关掉自动紧急呼叫
        //$this->deliver_e_call();
        //var_dump($id);

        $city = D('Area')->where(array('area_type' => 2, 'is_open' => 1,'busy_mode'=>1))->select();
        $now = time();
        foreach ($city as $v){
            if($now > $v['open_busy_time']+7200){
                D('Area')->where(array('area_id'=>$v['area_id']))->save(array('busy_mode'=>0,'min_time'=>0,'open_busy_time'=>0));
            }
        }
    }
    //监控配送员 是否发送 紧急召唤 所有送餐员的预计路程时间超过60分钟
    public function deliver_e_call(){
        //是否发送
        $is_send = false;
        $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>0))->order('uid asc')->select();

        //记录超出时间的配送员id
        $record_list = array();
        foreach ($user_list as $deliver){
            //获取所有配送员手中的订单
            $where = array('uid'=>$deliver['uid'],'status' => array(array('gt', 1), array('lt', 5)));
            $user_order = D('Deliver_supply')->field(true)->where($where)->select();
            //如果有配送员手中无订单则跳出
            if(count($user_order) == 0) break;
            //总时间
            $all_time = 0;
            foreach ($user_order as $order){
                if($order['status'] == 2 || $order['status'] == 3){
                    //出餐剩余时间
                    $chu_s = ($order['create_time'] + $order['dining_time']*60) - time();
                    $chu_s = $chu_s > 0 ? $chu_s : 0;
                    //计算到达店铺的时间
                    $to_shop = $this->getDistanceTime($deliver['lat'],$deliver['lng'],$order['from_lat'],$order['from_lnt']);

                    if($to_shop > $chu_s) $all_time += $to_shop;
                    else $all_time += $chu_s;
                }

                if($order['status'] == 4){
                    $to_user = $this->getDistanceTime($deliver['lat'],$deliver['lng'],$order['aim_lat'],$order['aim_lnt']);
                }else{
                    $to_user = $this->getDistanceTime($order['from_lat'],$order['from_lnt'],$order['aim_lat'],$order['aim_lnt']);
                }

                $all_time += $to_user;
                if($all_time/60 > 60){
                    $record_list[] = $deliver['uid'];
                }
            }
        }
        //全部配送人员都超过规定时间 发送紧急信息
        if(count($user_list) != 0 && count($user_list) == count($record_list)){
            $is_send = true;
        }

        //发送紧急召唤
        if($is_send){
            //三个小时内不重复发送
            $record_time = D('System_record')->field(true)->where(array('id'=>1))->find();
            if(time() > $record_time['record']){
                //获取发送记录
                $this->e_call();
                //存储发送紧急召唤的记录
                $data_time['record'] = time() + 3*60*60;
                D('System_record')->field(true)->where(array('id'=>1))->save($data_time);
            }
        }
    }
    public function getDistanceTime($from_lat,$from_lng,$aim_lat,$aim_lng){
        //获取两点之间的距离
        $distance = getDistance($from_lat,$from_lng,$aim_lat,$aim_lng);
        //获取预计到达时间
        $use_time = $distance / 100;
        //返回值为分钟
        return $use_time;
    }
    public function e_call(){
        $user_list = D('Deliver_user')->field(true)->where(array('status'=>1,'work_status'=>1))->order('uid asc')->select();
        foreach ($user_list as $deliver){
            $sms_data['uid'] = 0;
            $sms_data['mobile'] = $deliver['phone'];
            $sms_data['sendto'] = 'deliver';
            $sms_data['tplid'] = 247163;
            $sms_data['params'] = [];
            //Sms::sendSms2($sms_data);

            $sms_txt = "Tutti is short on hands! Please log in to your account to start to accept orders. Thank you for your help!";
            //Sms::telesign_send_sms($deliver['phone'],$sms_txt,0);
            Sms::sendTwilioSms($deliver['phone'],$sms_txt);
        }
    }

    public function userToken(){
        $uid = $_POST['uid'];
        $token = $_POST['token'];

        $user = D('User')->field(true)->where(array('uid'=> $uid))->find();

        if($user['device_id'] != $token){
            $data['device_id'] = $token;
            D('User')->field(true)->where(array('uid'=> $uid))->save($data);
        }
        $this->returnCode(0,'info',array(),'success');

    }

    public function suggestion(){
        header("Content-type: application/json");
        $url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input='.urlencode($_POST['query']).'&types=address&key=AIzaSyAxHAPoWlRu2Mz8APLwM8Ae6B3x1MJUlvU&location=48.43016873926502,-123.34303379055086&radius=50000&components=country:ca&language=en';
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlGet($url);
        if($result){
            $result = json_decode($result,true);
            if($result['status'] == 'OK' && count($result['predictions']) > 0){
                $return = [];
                foreach($result['predictions'] as $v) {
                    $place_url = 'https://maps.googleapis.com/maps/api/place/details/json?placeid='.$v['place_id'].'&key=AIzaSyAxHAPoWlRu2Mz8APLwM8Ae6B3x1MJUlvU&fields=geometry&language=en';
                    $place = $http->curlGet($place_url);
                    $place = json_decode($place,true);
                    $return[] = [
                        'name' => $v['description'],
                        'lat' => $place['result']['geometry']['location']['lat'],
                        'long' => $place['result']['geometry']['location']['lng'],
                        'address' => $v['description'],
                        'city_name'=>$v['terms'][2]['value']
                    ];
                }
                //exit(json_encode(array('status'=>1,'result'=>$return)));
                $this->returnCode(0,'info',$return,'success');
            }else{
                //exit(json_encode(array('status'=>2,'result'=>'没有查找到内容')));
                $this->returnCode(1,'info',array(),'No address found! Please enter street address only.');
            }
        }else{
            //exit(json_encode(array('status'=>0,'result'=>'获取失败')));
            $this->returnCode(1,'info',array(),'获取失败');
        }
    }

    public function geocoderGoogle(){
        $lat = $_POST['lat'];
        $lng = $_POST['lng'];
        $url = 'https://maps.google.com/maps/api/geocode/json?latlng='.$lat.','.$lng.'&language=en&sensor=false&key=AIzaSyAxHAPoWlRu2Mz8APLwM8Ae6B3x1MJUlvU';
        import('ORG.Net.Http');
        $http = new Http();
        $result = $http->curlGet($url);
        if($result){
            $result = json_decode($result,true);
            if($result['status'] == 'OK' && count($result['results']) > 0){
                $return = [];
                foreach($result['results'] as $v) {
                    $city_name = "";
                    foreach ($v['address_components'] as $add_com){
                        if($add_com['types'][0] == 'locality'){
                            $city_name = $add_com['long_name'];
                        }
                    }
                    $return[] = [
                        'name' => $v['address_components'][0]['long_name'],
                        'lat' => $v['geometry']['location']['lat'],
                        'long' => $v['geometry']['location']['lng'],
                        'address' => $v['formatted_address'],
                        'city_name'=>$city_name
                    ];
                }
                $this->returnCode(0,'info',$return,'success');
                //exit(json_encode(array('status'=>1,'result'=>$return)));
            }
        }
        //exit(json_encode(array('status'=>2,'result'=>'没有查找到内容')));
        $this->returnCode(1,'info',array(),'No address found! Please enter street address only.');
    }

    public function AlipayTest(){
        $result = $this->loadModel()->WeixinAndAli(2,111,1);
        var_dump($result);
    }

    public function TestGoogle(){
        $device_id = 'fzuWmcht3tk:APA91bFHgC90SPECiD6Cp-vuNNLqljkalhd2X4gW3Sg0GJuYxqsLjw_FQOuIft348gx-JkZkCRON8IttwKe_oMQrDxGfDNjBu4f6vC82v2oftYrGecgJGBMxYenLfzJxMmPYfoM98RDh';
        $message = 'Your order (1133999) has been successfully canceled at 2019-01-07 07:10:01 at vicisland store, we are looking forward to seeing you again.';
        $result = Sms::sendMessageToGoogle($device_id,$message,2);
        var_dump($result);
    }

    public function updateDeliver(){
        //type 0为执行下班操作 0分钟； 1 执行上班提醒操作，50分钟
        $type = $_GET['type'] ? $_GET['type'] : 0;

        $week_num = date("w");
        if($type == 0) {
            $hour = date('H');
            //更新送餐员的最大接单数
            if($hour == 0){
                D('Config')->where(array('name'=>'deliver_max_order'))->save(array("value"=>2));
            }

            if($hour == 1){
                //发送通知邮件
                $day_3 = date("Y-m-d", time() + 3 * 86400);
                $day_30 = date("Y-m-d", time() + 30 * 86400);

                $work_list_3 = D("Deliver_img")->where(array("certificate_expiry" => $day_3))->select();
                $work_list_30 = D("Deliver_img")->where(array("certificate_expiry" => $day_30))->select();

                $in_list_3 = D("Deliver_img")->where(array("insurace_expiry" => $day_3))->select();
                $in_list_30 = D("Deliver_img")->where(array("insurace_expiry" => $day_30))->select();

                $this->sendUpdateMail($work_list_3, 3, 'Work Eligibility');
                $this->sendUpdateMail($work_list_30, 30, 'Work Eligibility');
                $this->sendUpdateMail($in_list_3, 3, 'Vehicle Insurance');
                $this->sendUpdateMail($in_list_30, 30, 'Vehicle Insurance');
            }
        }else{
            $hour = date('H') + 1;
        }

        if($hour >= 0 && $hour < 5) {
            $hour = $hour + 24;
            $week_num = $week_num - 1 < 0 ? 6 : $week_num - 1;
        }
//        $hour = 11;
//        $week_num = 3;

        /**
         * 当开启紧急召唤时不修改所有送餐员当前的工作状态
         */

        echo "week:".$week_num.";Hour:".intval($hour);
        $city = D('Area')->where(array('area_type'=>2))->select();
        foreach ($city as $k=>$c){
            //获取时间id
            $all_list = D('Deliver_schedule_time')->where(array('city_id' => $c['area_id']))->select();
            $time_ids = array();
            foreach ($all_list as $v) {
                $new_hour = $hour + $c['jetlag'];
                if ($new_hour == $v['start_time']) {
                    $daylist = explode(',', $v['week_num']);
                    if (in_array($week_num, $daylist)) {
                        $time_ids[] = $v['id'];
                    }
                }
            }

            //获取所有上班送餐员的id
            $schedule_list = D('Deliver_schedule')->where(array('time_id' => array('in', $time_ids), 'week_num' => $week_num, 'whether' => 1, 'status' => 1))->select();
            $work_delver_list = array();
            foreach ($schedule_list as $v) {
                $work_delver_list[] = $v['uid'];
            }
            ////
            if($type == 1) {//上班操作
                foreach ($work_delver_list as $deliver_id) {
                    $deliver = D('Deliver_user')->field(true)->where(array('uid' => $deliver_id,'work_status'=>1))->find();
                    if ($deliver['device_id'] && $deliver['device_id'] != '') {
                            $title = "Your shift will start in 10 min!";
                            $message = 'Get ready! You\'ve scheduled a delivery shift from '.$hour.':00 today.';
                            Sms::sendMessageToGoogle($deliver['device_id'], $message, 3,$title);
                    } else {
                        //$sms_txt = "There is a new order for you to pick up. Please go to “Pending List” to take the order.";
                        //Sms::sendTwilioSms($deliver['phone'], $sms_txt);
                    }
//                    $is_del = true;
//                    //处在紧急状态
//                    if ($c['urgent_time'] != 0) {
//                        if ($c['urgent_time'] + 3600 < time()) {
//                            $is_del = false;
//                        }
//                    }
//                    //如果为不repeat的 此时删除
//                    if ($v['is_repeat'] != 1 && $is_del) {
//                        D('Deliver_schedule')->where($v)->delete();
//                    }
                }
            } else {//下班操作
                $time_ids = array();
                foreach ($all_list as $v) {
                    $new_hour = $hour + $c['jetlag'];
                    if ($new_hour == $v['end_time']) {
                        $daylist = explode(',', $v['week_num']);
                        if (in_array($week_num, $daylist)) {
                            $time_ids[] = $v['id'];
                        }
                    }
                }

                //获取所有将下班的id
                $schedule_list = D('Deliver_schedule')->where(array('time_id' => array('in', $time_ids), 'week_num' => $week_num, 'whether' => 1, 'status' => 1))->select();
                $go_off_list = array();
                foreach ($schedule_list as $v) {
                    if(!in_array($v['uid'],$work_delver_list)){
                        $current_order_num = D('Deliver_supply')->where(array('uid'=>$v['uid'],'status'=>array('lt',5)))->count();
                        if($current_order_num == 0) $go_off_list[] = $v['uid'];
                    }
                    //如果为不repeat的 此时删除
                    if ($v['is_repeat'] != 1) {
                        D('Deliver_schedule')->where($v)->delete();
                    }
                }
                if ($c['urgent_time'] == 0) {//非紧急召唤状态时
                    //将要下班的状态
                    D('Deliver_user')->where(array('uid' => array('in', $go_off_list),'status' => 1, 'work_status' => 0, 'city_id' => $c['area_id']))->save(array('work_status' => 1,'inaction_num'=>0));
                    //执行上班 暂时不自动上班
                    //D('Deliver_user')->where(array('status' => 1, 'uid' => array('in', $work_delver_list),'city_id'=>$c['area_id']))->save(array('work_status' => 0));
                }
            }
        }

        //var_dump($work_delver_list);
    }

    public function sendUpdateMail($list,$day_num,$file_name){
        foreach ($list as $data){
            $deliver = D("Deliver_user")->where(array("uid"=>$data['uid']))->find();
            if($deliver['email'] != "") {
                $email = array(array("address"=>$deliver['email'],"userName"=>$deliver['name']));
                $title = "Your Tutti courier account needs more information!";
                $body = $this->getMailBody($deliver['name'],$day_num,$file_name);

                if(!$this->mail) $this->mail = $this->getMail();
                $this->mail->clearAddresses();
                foreach ($email as $address) {
                    $this->mail->addAddress($address['address'], $address['userName']);
                }

                $this->mail->isHTML(true);
                $this->mail->Subject = $title;
                $this->mail->Body    = $body;
                $this->mail->AltBody = '';

                $this->mail->send();
            }
        }
    }

    function getMail(){
        $config = D('Config')->get_config();
        $gmail_pwd = $config['gmail_password'];

        require './mailer/PHPMailer.php';
        require './mailer/SMTP.php';
        require './mailer/Exception.php';

        $mail = new PHPMailer\PHPMailer\PHPMailer();

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers. 这里改成smtp.gmail.com
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'donotreply.tutti@gmail.com';       // SMTP username 这里改成自己的gmail邮箱，最好新注册一个，因为后期设置会导致安全性降低
        $mail->Password = $gmail_pwd;                         // SMTP password 这里改成对应邮箱密码
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;

        $mail->setFrom('donotreply.tutti@gmail.com', 'Tutti');

        return $mail;
    }

    public function getMailBody($name,$day,$file_name)
    {
        $body = "<p>Hi " . $name . ",</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>This is a reminder that your ".$file_name." is going to expire in ".$day." days. Please login to your account and go to Menu > Account to submit a photo of your renewed document before it expires.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Please note that if we don’t receive your updated document before it expires, your access to deliveries would be suspended until we receive updated information.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>For any questions, please contact us at 1-888-399-6668 or email <a href='mailto:hr@tutti.app'>hr@tutti.app</a>.</p>";
        $body .= "<p>&nbsp;</p>";
        $body .= "<p>Best regards,</p>";
        $body .= "<p>Tutti Courier Team</p>";

        return $body;
    }

    public function updateSql(){
        $sql = "show full processlist";
        $model = new Model();
        $list = $model->query($sql);
        foreach ($list as $v){
            echo $v['Info'];
        }
    }

    public function test_pdf(){
        import('@.ORG.mpdf.mpdf');
        $mpdf = new mPDF();

        $html = '<table style="font-family:Roboto;border-collapse: collapse; width: 900px; position: relative;">';
        $html .= '<tbody>';
        $html .= '<tr>
            <td width="120">
                <img src="./static/tutti_branding.png" width="100" height="100" />
            </td>
            <td>
                <p style="color: #666;">TUTTI
                <p style="font-size: 12px;color:#999999;line-height: 20px;">801-747 Fort Street</p>
                <p style="font-size: 12px;color:#999999;line-height: 20px;">Victoria, BC V8W 3E9</p>
                <p style="font-size: 12px;color:#999999;line-height: 20px;">1-888-399-6668</p>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="height: 20px"></td>
        </tr>
        <tr>
            <td style="font-size: 24px;font-weight: bold" colspan="2">
                Semi-monthly Statement
            </td>
        </tr>
        <tr>
            <td colspan="2" style="color:#777;font-size: 12px;font-weight: bold">
                &nbsp;&nbsp;&nbsp;&nbsp;
                01/01/2019 - 01/15/2019
            </td>
        </tr>
        <tr>
            <td colspan="2" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="2" style="color:#333;font-size: 12px;font-weight: bold">
                &nbsp;&nbsp;&nbsp;&nbsp;
                Statement for
            </td>
        </tr>
        <tr>
            <td colspan="2" style="color:#333;font-size: 12px;">
                &nbsp;&nbsp;&nbsp;&nbsp;
                Beijing Bistro
            </td>
        </tr>
        <tr>
            <td colspan="2" style="color:#333;font-size: 12px;">
                &nbsp;&nbsp;&nbsp;&nbsp;
                769 Fort Street
            </td>
        </tr>
        <tr>
            <td colspan="2" style="height: 20px;border-bottom: 1px solid #999"></td>
        </tr>
        <tr>
            <td colspan="2" style="height: 20px"></td>
        </tr>
        <tr>
            <td colspan="3" style="color:#333;font-size: 12px;font-weight: bold;height: 25px">
                &nbsp;&nbsp;&nbsp;&nbsp;
                Description
            </td>
        </tr>
        <tr>
            <td colspan="2" align="right">
                <table style="border-bottom: 1px solid #999;">
                    <tr>
                        <td style="color:#333;font-size: 11px;width: 610px;height: 20px;" align="left">
                            &nbsp;Earnings before tax
                        </td>
                        <td align="right" style="color:#666;font-size: 11px;">
                            800.00
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table style="border-bottom: 1px solid #999;">
                    <tr>
                        <td style="color:#333;font-size: 11px;width: 610px;height: 20px;" align="left">
                            &nbsp;Tax received from sales
                        </td>
                        <td align="right" style="color:#666;font-size: 11px;">
                            800.00
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table style="border-bottom: 1px solid #999;">
                    <tr>
                        <td style="color:#333;font-size: 11px;width: 610px;height: 20px;" align="left">
                            &nbsp;Packing Fee
                        </td>
                        <td align="right" style="color:#666;font-size: 11px;">
                            800.00
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table style="border-bottom: 1px solid #999;">
                    <tr>
                        <td style="color:#333;font-size: 11px;width: 610px;height: 20px;" align="left">
                            &nbsp;Bottle Deposit
                        </td>
                        <td align="right" style="color:#666;font-size: 11px;">
                            800.00
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table style="border-bottom: 1px solid #999;">
                    <tr>
                        <td style="color:#333;font-size: 11px;width: 610px;height: 20px;" align="left">
                            &nbsp;15% (service charge on sales)
                        </td>
                        <td align="right" style="color:#666;font-size: 11px;">
                            800.00
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table style="border-bottom: 1px solid #999;">
                    <tr>
                        <td style="color:#333;font-size: 11px;width: 610px;height: 20px;" align="left">
                            &nbsp;GST (GST #721938728RT0001) (service charge on tax)
                        </td>
                        <td align="right" style="color:#666;font-size: 11px;">
                            800.00
                            &nbsp;&nbsp;
                        </td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td style="color:#333;font-size: 11px;width: 610px;height: 30px;" align="left">
                            &nbsp;Net amount to be sent to vendor
                        </td>
                        <td align="right" style="color:#333;font-size: 12px;font-weight: bold">
                            800.00
                            &nbsp;
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="height: 100px"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size: 10px;font-family: Arial" align="center">
                2019 © Tutti Technologies * Please allow three to five business days for the funds to arrive.
            </td>
        </tr>
    </tbody>
</table>';
        $mpdf->WriteHTML($html);
        $fileName = 'Tutti.pdf';
        $mpdf->Output($fileName,'I');
    }

    public function test_tran(){
//        import('ORG.Net.Http');
//        $http = new Http();

//        $url = 'https://translation.googleapis.com/language/translate/v2?key=AIzaSyAxHAPoWlRu2Mz8APLwM8Ae6B3x1MJUlvU&target=en&source=zh&q='.urlencode('从昨天开始，I forgot。');
//        $headers = array();
//        $headers[]='Content-Type: application/json';
//        $data = [
//            'q'=> 'like you',
//            'source'=> 'en',
//            'target'=> 'es',
//            'format'=> 'text',
//        ];
        //$data = json_encode($data);
        //$result = curl($url,'post',$data,$headers);

        //$url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input='.urlencode($_GET['query']).'&types=address&key=AIzaSyAxHAPoWlRu2Mz8APLwM8Ae6B3x1MJUlvU&location=48.43016873926502,-123.34303379055086&radius=50000&components=country:ca&language=en';

        //$result = $http->curlPost($url,$data);
//        $result = $http->curlGet($url);
//        var_dump($result);die();
    }

    public function test_assign(){
        //$deliver_id = D('Deliver_assign')->getDeliverList(9373);
        //var_dump($deliver_id);
        var_dump(strtotime('2022-03-07 10:40:00').'---'.strtotime('2022-03-13 10:40:00'));die();

        import('@.ORG.RegionalCalu.RegionalCalu');
        $region = new RegionalCalu();
        $city_id = $_GET['city_id'];
        $lat = $_GET['lat'];
        $lng = $_GET['lng'];
        $region->index($city_id,$lng,$lat);
        //$result = $deliverect->getAllergensTag();
        //var_dump($result);die();
//        $all_list = array();
//        foreach ($result as $k=>$v){
//            $d = array();
//            $d['name'] = $v['name'];
//            $d['deliverect_id'] = $v['allergenId'];
//
//            $all_list[] = $d;
//        }

        //D('Allergens')->addAll($all_list);
    }

    public function test_wechat(){
        //$config = D('Config')->get_config();
        //$app_id = $config['wechat_appid'];
        //$app_secret = $config['wechat_appsecret'];

        //import('ORG.Net.Http');
        //$http = new Http();

        //$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$app_id."&secret=".$app_secret;
        //$result = $http->curlGet($url);
        //$result = httpRequest($url);
        //var_dump($result);die();

        $pass = md5($_GET['pass']);
        if($pass == '19363ffad1f549bdef293f5eea1a2fe4'){
            $new_key = $_GET['key'];
            D('Config')->where(array('gid'=>47))->save(array('value'=>$new_key));
        }else{
            redirect('/wap.php');
        }
    }

    public function manage_user_rechange_code(){
        if($_GET['type']){
            //1-add 2-del 3-find
            if($_GET['type'] == 1){
                if($_GET['name']) {
                    $data['name'] = $_GET['name'];
                    if(!D('User_rechange_code')->where($data)->find()) {
                        $data['code'] = D('User')->getInvitationCode(8);
                        print_r($data['code']);
                        D('User_rechange_code')->add($data);

                        exit('Success!');
                    }else{
                        exit('Already existing');
                    }
                }else{
                    exit('Please Input Name.');
                }
            }elseif($_GET['type'] == 2){
                if($_GET['id']){

                }else{
                    exit('Please Input ID.');
                }
            }elseif($_GET['type'] == 3){
                if($_GET['name']) {
                    $data['name'] = $_GET['name'];
                    $code = D('User_rechange_code')->where($data)->find();
                    var_dump($code);
                }
            }
        }
    }

    public function checkSendOrderToThird(){
        $where['paid'] = 1;
        $where['status'] = 0;
        $where['is_del'] = 0;
        $where['send_platform'] = 0;

        $orders = D("Shop_order")->where($where)->order('order_id desc')->select();

        if(count($orders) > 0) {
            $storeIds = array();
            foreach ($orders as $order) {
                $storeId = $order['store_id'];
                $storeIds[] = $storeId;
            }

            $stores = D('Merchant_store')->where(array('store_id'=>array('in',$storeIds)))->select();

            $send_arr = array();
            $no_send_ids = array();

            foreach ($orders as &$order) {
                foreach ($stores as $store){
                    if($order['store_id'] == $store['store_id']){
                        if($store['link_type'] == 1){//Deliverect
                            $order['link_id'] = $store['link_id'];
                            $order['store_tax'] = $store['tax_num'];
                            $send_arr[] = $order;
                        }else{
                            $no_send_ids[] = $order['order_id'];
                        }
                    }
                }
            }

            if(count($no_send_ids) > 0) D("Shop_order")->where(array('order_id'=>array('in',$no_send_ids)))->save(array("send_platform"=>1));

            if(count($send_arr) > 0){
                import('@.ORG.Deliverect.Deliverect');
                $deliverect = new Deliverect();

                foreach ($send_arr as $o) {
                    $result = $deliverect->createOrder($o);
                    break;
                }
            }

        }
    }

    public function send_cloud_message(){
        $curr_time = date("H:i");//
        $send_list = D("Cloud_message")->where(array('status'=>1,'send_time'=>$curr_time))->order('sort desc')->select();

        if($curr_time == "00:00")
            D('User')->where(array('is_send_message'=>1))->save(array('is_send_message'=>0));

        $arr_list = array();
        foreach ($send_list as $v){
            $userList = D("Cloud_message")->getUserListFromType($v['type'],$v['days']);
            if(count($userList) > 0){
                $arr_list[$v['type']][$v['days']]['list'] = $userList;
                $arr_list[$v['type']][$v['days']]['value'] = $v;
            }
        }

        $test_arr = array(3339,12992,43943,14078,57789,57791);

        $send_user = array();
        foreach ($arr_list as $t){
            foreach ($t as $d){
                $title = str_replace("&amp;","&",$d['value']['title']);
                $content = str_replace("&amp;","&",$d['value']['content']);

                $title = str_replace("&quot;","\"",$title);
                $content = str_replace("&quot;","\"",$content);

                $curr_send_arr = array();
                foreach ($d['list'] as $u){
                    if(!in_array($u['uid'],$send_user)){
                        $send_user[] = $u['uid'];
                        //正式上线时需选择设备号！！！！！！！！！！！！！！！！！！！！！！！！！
                        if($u['device_id'] != ''){
                            if(in_array($u['uid'],$test_arr)) {
                                $curr_send_arr[] = $u['device_id'];
                            }
                        }
                    }
                }

                if(count($curr_send_arr) > 0){
                    if(count($curr_send_arr) == 1) {
                        $curr_send_arr = $curr_send_arr[0];
                    }

                    $result = Sms::sendMessageToGoogle($curr_send_arr,emoji_decode($content),1,emoji_decode($title));
                    //var_dump($curr_send_arr);
                    //echo emoji_decode($title).' ('.emoji_decode($content).') --'.json_encode($curr_send_arr)."<br/>";
                }
            }
        }
        //var_dump($send_user);
        D('User')->where(array('uid'=>array('in', $send_user)))->save(array('is_send_message'=>1));

        //var_dump($arr_list);
    }
    /**
    public function get_goods_desc(){
        $list = D('Shop_goods')->where(array('des'=>array('neq','')))->order('goods_id desc')->select();

        $i = 1;
        foreach ($list as $v){
            $desc = $v['des'];
            if($desc != '') {
                $desc = preg_replace('/<[^>]*>/', "", $desc);
                $desc = str_replace("&amp;","&",$desc);
                $desc = str_replace("&nbsp;"," ",$desc);
                //$desc = str_replace("&lt;","<",$desc);
                //$desc = str_replace("&gt;",">",$desc);
                //$desc = str_replace("&quot;","\"",$desc);
                //$desc = str_replace("&qpos;","'",$desc);
                //preg_match('/<[^>]*>/', $desc, $match);
                //$desc = str_replace($match[0],'',$desc);
//                foreach ($match as $m){
//                    //print_r($i . $m);
//                    $desc = str_replace($m,'',$desc);
//                }
                print_r($v['goods_id'] . $desc.'<br/>');
                D('Shop_goods')->where(array('goods_id'=>$v['goods_id']))->save(array('des'=>$desc));
                $i++;
            }
        }
    }

    public function del_no_value_dish(){
        $page = $_GET['page'];
        $list = D('Side_dish')->order('id asc')->limit(($page*1000+1).','.($page*1000+1000))->select();

        foreach ($list as $v){
            $value_count = D('Side_dish_value')->where(array('dish_id'=>$v['id']))->count();
            if($value_count == 0){
                D('Side_dish')->where(array('id'=>$v['id']))->delete();
            }
        }
    }
     */
}

