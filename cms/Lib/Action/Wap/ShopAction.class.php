<?php
class ShopAction extends BaseAction{
    protected $leveloff = '';
    protected function _initialize() {
        parent::_initialize();
        //获取倒计时时间 web app 时间不同
        $config = D('Config')->get_config();
        $web_count_down = $config['pay_count_down_web'];

        $this->assign('count_down',$web_count_down*60);
    }
    public function index(){
        if($_GET['shop-id']){
            redirect(U('index').'#shop-'.$_GET['shop-id']);
        }
        if($_GET['cat']){
            redirect(U('index').'#cat-'.$_GET['cat']);
        }
        $user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
        $this->assign('user_long_lat',$user_long_lat);

        $lat = isset($user_long_lat['lat']) ? $user_long_lat['lat'] : 0;
        $long = isset($user_long_lat['long']) ? $user_long_lat['long'] : 0;

        if($_COOKIE['userLocationCity']){
            $city_id = $_COOKIE['userLocationCity'];
        }else{
            $city_id = D('Store')->geocoderGoogle($lat,$long);
            $city_id = $city_id ? $city_id : 0;
            $_COOKIE['userLocationCity'] = $city_id;
        }

        //$category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>0))->select();
        $category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>0,'city_id'=>$city_id))->order('cat_sort desc')->select();
        if(count($category) == 0){
            $category = D('Shop_category')->field(true)->where(array('cat_fid'=>0,'cat_type'=>0,'city_id'=>0))->order('cat_sort desc')->select();
        }
        $nav_list = array();
        foreach ($category as $v){
            $nav['title'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
            $nav['image'] = $this->config['site_url'].'/static/images/category/web/'.$v['cat_url'].'.png';
            $nav['id'] = $v['cat_id'];

            $nav_list[] = $nav;
        }
        $this->assign('category',$nav_list);

        if($_GET['key']){
            $this->assign('keyword',$_GET['key']);
        }

        $this->getFooterMenu();
        $this->display();
    }

    public function classic_index(){
        $this->display();
    }
    public function classic_address(){
        $this->display();
    }
    public function classic_shopsearch(){
        $this->display();
    }
    public function classic_cat(){
        $this->display();
    }
    public function classic_shop(){
        $this->display('classic_shop_new');
    }
    public function classic_good(){
        $this->display();
    }
    public function classic_map(){
        $this->display();
    }
    public function getFooterMenu(){
        $home_menu_list = D('Home_menu')->getMenuList('shop_footer');
        $this->assign('home_menu_list',$home_menu_list);
        return array();
    }

    public function ajax_index()
    {
        /*最多5个*/
        $return = array();
        $return['banner_list'] = D('Adver')->get_adver_by_key('wap_shop_index_top', 5);
        $return['slider_list'] = D('Slider')->get_slider_by_key('wap_shop_slider', 0);
        $return['adver_list'] = D('Adver')->get_adver_by_key('wap_shop_index_cente', 3);
        $return['category_list'] = D('Shop_category')->lists(true);
        //add garfunkel 判断语言
        foreach ($return['category_list'] as $k => $v){
            $return['category_list'][$k]['cat_name'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
            foreach ($v['son_list'] as $kk => $vv) {
                $return['category_list'][$k]['son_list'][$kk]['cat_name'] = lang_substr($vv['cat_name'],C('DEFAULT_LANG'));
            }
        }
        $return['sort_list'] = array(
            array(
                'name' => L('_INTELLIGENT_SORTING_'),
                'sort_url' => 'juli'
            ),
            array(
                'name' => L('_HIGHEST_SALES_'),
                'sort_url'=>'sale_count'
            ),
            array(
                'name' => L('_DELI_TIME_MIN_'),
                'sort_url'=>'send_time'
            ),
            array(
                'name' => L('_DIST_PRICE_MIN_'),
                'sort_url' => 'basic_price'
            ),
// 				array(
// 						'name' => '配送费最低',
// 						'sort_url' => 'delivery_fee'
// 				),
            array(
                'name' => L('_SCORE_HIGHEST_'),
                'sort_url' => 'score_mean'
            ),
            array(
                'name' => L('_NEW_RELEASE_'),
                'sort_url' => 'create_time'
            )
        );
        $return['type_list'] = array(
            array(
                'name' => L('_ALL_TXT_'),
                'type_url' => 'all'
            ),
            array(
                'name' => L('_DELI_TXT_'),
                'type_url' => 'delivery'
            ),
            array(
                'name' => L('_SELF_LIFT_'),
                'type_url' => 'pick'
            )
        );
        echo json_encode($return);
    }

    public function ajax_cat(){
        $cat_id = $_POST['id'];
        $list = D('Shop_category')->field(true)->where(array('cat_fid'=>$cat_id,'cat_status'=>1))->order('cat_sort desc')->select();
        $nav_list = array();

        $is_recommend = false;
        foreach ($list as $v){
            $nav['title'] = lang_substr($v['cat_name'],C('DEFAULT_LANG'));
            $nav['id'] = $v['cat_id'];
            $nav['url'] = '#cat-'.$cat_id.'-'.$v['cat_id'];
            $nav_list[] = $nav;
            if($v['cat_type'] == 1){
                $is_recommend = true;
            }
        }

        //if(!$is_recommend) {
        $all['title'] = 'All';
        $all['id'] = 0;
        $all['url'] = '#cat-' . $cat_id;
        array_unshift($nav_list, $all);
        //}

        $return['status'] = 1;
        $return['list'] = $nav_list;
        $return['is_recommend'] = $is_recommend ? 1 : 0;

        exit(json_encode($return));

    }

    public function ajax_category(){
        $return = array();
        $return['category_list'] = D('Shop_category')->lists(true);
        $return['sort_list'] = array(
            array(
                'name' => L('_INTELLIGENT_SORTING_'),
                'sort_url' => 'juli'
            ),
            array(
                'name' => L('_HIGHEST_SALES_'),
                'sort_url'=>'sale_count'
            ),
            array(
                'name' => L('_DELI_TIME_MIN_'),
                'sort_url'=>'send_time'
            ),
            array(
                'name' => L('_DIST_PRICE_MIN_'),
                'sort_url' => 'basic_price'
            ),
// 				array(
// 						'name' => '配送费最低',
// 						'sort_url' => 'delivery_fee'
// 				),
            array(
                'name' => L('_SCORE_HIGHEST_'),
                'sort_url' => 'score_mean'
            ),
            array(
                'name' => L('_NEW_RELEASE_'),
                'sort_url' => 'create_time'
            )
        );
        $return['type_list'] = array(
            array(
                'name' => L('_ALL_TXT_'),
                'type_url' => 'all'
            ),
            array(
                'name' => L('_DELI_TXT_'),
                'type_url' => 'delivery'
            ),
            array(
                'name' => L('_SELF_LIFT_'),
                'type_url' => 'pick'
            )
        );
        echo json_encode($return);
    }
    /*请求参数 cat_url sort_url type_url user_long user_lat page*/
    public function ajax_list()
    {
        $key = isset($_GET['key']) ? htmlspecialchars($_GET['key']) : '';
        $cat_url = isset($_GET['cat_url']) ? htmlspecialchars($_GET['cat_url']) : 'all';
        $order = isset($_GET['sort_url']) ? htmlspecialchars($_GET['sort_url']) : 'juli';
        $deliver_type = isset($_GET['type_url']) ? htmlspecialchars($_GET['type_url']) : 'all';
        $lat = isset($_GET['user_lat']) ? htmlspecialchars($_GET['user_lat']) : 0;
        $long = isset($_GET['user_long']) ? htmlspecialchars($_GET['user_long']) : 0;
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $is_wap = $_GET['is_wap'] + 0;
        $page = max(1, $page);
        $cat_id = 0;
        $cat_fid = 0;
        //garfunkel add
        $cat_id = $_GET['cat_id'];
        $cat_fid = $_GET['cat_fid'];
        //
        if($_SESSION['openid'] && $lat == 0 && $long == 0){
            $user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid']);
            $lat = isset($user_long_lat['lat']) ? $user_long_lat['lat'] : 0;
            $long = isset($user_long_lat['long']) ? $user_long_lat['long'] : 0;
        }

        if ($cat_url != 'all') {
            $now_category = D('Shop_category')->get_category_by_catUrl($cat_url);
            if ($now_category) {
                if ($now_category['cat_fid']) {
                    $cat_id = $now_category['cat_id'];
                    $cat_fid = $now_category['cat_fid'];
                } else {
                    $cat_id = 0;
                    $cat_fid = $now_category['cat_id'];
                }
            }
        }

        if($_COOKIE['userLocationCity']){
            $city_id = $_COOKIE['userLocationCity'];
        }else{
            $city_id = D('Store')->geocoderGoogle($lat,$long);
            $city_id = $city_id ? $city_id : 0;
            $_COOKIE['userLocationCity'] = $city_id;
        }

        if($cat_id == 0)
            $now_category = D('Shop_category')->where(array('cat_id'=>$cat_fid))->find();
        else
            $now_category = D('Shop_category')->where(array('cat_id'=>$cat_id))->find();

        if($now_category['cat_type'] == 1){
            $lists = D('Merchant_store_shop')->getRecommendList($city_id,$lat,$long,$cat_id,$cat_fid);
        }else {
            $where = array('deliver_type' => $deliver_type, 'order' => $order, 'lat' => $lat, 'long' => $long, 'cat_id' => $cat_id, 'cat_fid' => $cat_fid, 'page' => $page);
            $key && $where['key'] = $key;

            if ($is_wap > 0) {
                $lists = D('Merchant_store_shop')->get_list_by_option($where, $is_wap);
            } else {
                $lists = D('Merchant_store_shop')->get_list_by_option($where);
            }
        }
        $return = array();
        $now_time = date('H:i:s');

        //garfunkel获取减免配送费的活动

        foreach ($lists['shop_list'] as $row) {
            $temp = array();
            $temp['id'] = $row['store_id'];
            //modify garfunkel 判断语言
            $temp['name'] = lang_substr($row['name'],C('DEFAULT_LANG'));
            $temp['juli'] = $row['juli'];
            $temp['range'] = $row['range'];
            $temp['image'] = $row['image'];
            $temp['image_list'] = $row['image_list'];
            $temp['image_count'] = $row['image_count'];
            $temp['star'] = $row['score_mean'];
            $temp['month_sale_count'] = $row['sale_count'];
            $temp['merchant_store_month_sale_count'] = $row['merchant_store_month_sale_count'];//月售量
            $temp['delivery'] = $deliver_type == 'pick' ? 0 : $row['deliver'];//是否支持配送
            $temp['delivery_time'] = $row['send_time'];//配送时长
            $temp['delivery_price'] = floatval($row['basic_price']);//起送价
            if($lat != 0 && $long != 0){
                $temp['delivery_money'] = getDeliveryFee($row['lat'],$row['long'],$lat,$long,$row['city_id']);
            }else{
                $temp['delivery_money'] = floatval($row['delivery_fee']);//配送费
            }
            $temp['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
            $temp['is_close'] = 1;
            $temp['isverify'] = $row['isverify'];
            //add garfunkel
            $temp['pack_alias'] = $row['pack_alias'];
            $temp['pack_fee'] = $row['pack_fee'];
            $temp['tax_num'] = $row['tax_num'];
            $temp['deposit_price'] = $row['deposit_price'];

//			if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
//				$temp['time'] = '24小时营业';
//				$temp['is_close'] = 0;
//			} else {
//				$temp['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
//				if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
//					$temp['is_close'] = 0;
//				}
//				if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
//					$temp['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
//					if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
//						$temp['is_close'] = 0;
//					}
//				}
//				if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
//					$temp['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
//					if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
//						$temp['is_close'] = 0;
//					}
//				}
//			}
            if($row['store_is_close'] != 0){
                $row = checkAutoOpen($row);
            }
            //@wangchuanyuan 周一到周天
            $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
            switch ($date){
                case 1 :
                    if ($row['open_1'] != '00:00:00' || $row['close_1'] != '00:00:00'){
                        if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
                            $temp['is_close'] = 0;
                        }
                    }
                    if($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00'){
                        if($row['open_2'] < $now_time && $now_time < $row['close_2']) {
                            $temp['is_close'] = 0;
                        }
                    }
                    if($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00'){
                        if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
                    break;
                case 2 ://周二
                    if ($row['open_4'] != '00:00:00' || $row['close_4'] != '00:00:00') {
                        if ($row['open_4'] < $now_time && $now_time < $row['close_4']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_5'] != '00:00:00' || $row['close_5'] != '00:00:00') {
                        if ($row['open_5'] < $now_time && $now_time < $row['close_5']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_6'] != '00:00:00' || $row['close_6'] != '00:00:00') {
                        if ($row['open_6'] < $now_time && $now_time < $row['close_6']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = substr($row['open_4'], 0, -3) . '~' . substr($row['close_4'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_5'], 0, -3) . '~' . substr($row['close_5'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_6'], 0, -3) . '~' . substr($row['close_6'], 0, -3);
                    break;
                case 3 ://周三
                    if ($row['open_7'] != '00:00:00' || $row['close_7'] != '00:00:00') {
                        if ($row['open_7'] < $now_time && $now_time < $row['close_7']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_8'] != '00:00:00' || $row['close_8'] != '00:00:00') {
                        if ($row['open_8'] < $now_time && $now_time < $row['close_8']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_9'] != '00:00:00' || $row['close_9'] != '00:00:00') {
                        if ($row['open_9'] < $now_time && $now_time < $row['close_9']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = substr($row['open_7'], 0, -3) . '~' . substr($row['close_7'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_8'], 0, -3) . '~' . substr($row['close_8'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_9'], 0, -3) . '~' . substr($row['close_9'], 0, -3);

                    break;
                case 4 :
                    if ($row['open_10'] != '00:00:00' || $row['close_10'] != '00:00:00') {
                        if ($row['open_10'] < $now_time && $now_time < $row['close_10']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_11'] != '00:00:00' || $row['close_11'] != '00:00:00') {
                        if ($row['open_11'] < $now_time && $now_time < $row['close_11']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_12'] != '00:00:00' || $row['close_12'] != '00:00:00') {
                        if ($row['open_12'] < $now_time && $now_time < $row['close_12']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = substr($row['open_10'], 0, -3) . '~' . substr($row['close_10'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_11'], 0, -3) . '~' . substr($row['close_11'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_12'], 0, -3) . '~' . substr($row['close_12'], 0, -3);
                    break;
                case 5 :
                    if ($row['open_13'] != '00:00:00' || $row['close_13'] != '00:00:00') {
                        if ($row['open_13'] < $now_time && $now_time < $row['close_13']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_14'] != '00:00:00' || $row['close_14'] != '00:00:00') {
                        if ($row['open_14'] < $now_time && $now_time < $row['close_14']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_15'] != '00:00:00' || $row['close_15'] != '00:00:00') {
                        if ($row['open_15'] < $now_time && $now_time < $row['close_15']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = substr($row['open_13'], 0, -3) . '~' . substr($row['close_13'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_14'], 0, -3) . '~' . substr($row['close_14'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_15'], 0, -3) . '~' . substr($row['close_15'], 0, -3);
                    break;
                case 6 :
                    if ($row['open_16'] != '00:00:00' || $row['close_16'] != '00:00:00') {
                        if ($row['open_16'] < $now_time && $now_time < $row['close_16']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_17'] != '00:00:00' || $row['close_17'] != '00:00:00') {
                        if ($row['open_17'] < $now_time && $now_time < $row['close_17']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_18'] != '00:00:00' || $row['close_18'] != '00:00:00') {
                        if ($row['open_18'] < $now_time && $now_time < $row['close_18']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = substr($row['open_16'], 0, -3) . '~' . substr($row['close_16'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_17'], 0, -3) . '~' . substr($row['close_17'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_18'], 0, -3) . '~' . substr($row['close_18'], 0, -3);
                    break;
                case 0 :
                    if ($row['open_19'] != '00:00:00' || $row['close_19'] != '00:00:00') {
                        if ($row['open_19'] < $now_time && $now_time < $row['close_19']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_20'] != '00:00:00' || $row['close_20'] != '00:00:00') {
                        if ($row['open_20'] < $now_time && $now_time < $row['close_20']){
                            $temp['is_close'] = 0;
                        }
                    }
                    if ($row['open_21'] != '00:00:00' || $row['close_21'] != '00:00:00') {
                        if ($row['open_21'] < $now_time && $now_time < $row['close_21']){
                            $temp['is_close'] = 0;
                        }
                    }
                    $temp['time'] = substr($row['open_19'], 0, -3) . '~' . substr($row['close_19'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_20'], 0, -3) . '~' . substr($row['close_20'], 0, -3);
                    $temp['time'] .= ';' . substr($row['open_21'], 0, -3) . '~' . substr($row['close_21'], 0, -3);
                    break;
                default :
                    $temp['is_close'] = 1;
                    $temp['time']= '营业时间未知';
            }
            //garfunkel add
            if($row['store_is_close'] != 0){
                $temp['is_close'] = 1;
            }

            $keywords = D('Keywords')->where(array('third_type' => 'Merchant_store', 'third_id' => $row['store_id']))->select();
            $str = "";
            foreach ($keywords as $key) {
                $str .= $key['keyword'] . " ";
            }
            $temp['keywords'] = $str;
            //end  @wangchuanyuan
            $temp['coupon_list'] = array();
            if ($row['is_invoice']) {
                $temp['coupon_list']['invoice'] = floatval($row['invoice_price']);
            }
            if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
                $temp['coupon_list']['discount'] = $row['store_discount']/10;
            }
            $system_delivery = array();
            foreach ($row['system_discount'] as $row_d) {
                if ($row_d['type'] == 0) {//新单
                    $temp['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 1) {//满减
                    $temp['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 2) {//配送
                    if ($row_d['full_money'] > 0 && $row_d['reduce_money'] > 0) {
                        $system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                    }
                }
            }
            foreach ($row['merchant_discount'] as $row_m) {
                if ($row_m['type'] == 0) {
                    $temp['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                } elseif ($row_m['type'] == 1) {
                    $temp['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                }
            }
            if ($row['deliver']) {
                if ($temp['delivery_system']) {
                    $system_delivery && $temp['coupon_list']['delivery'] = $system_delivery;
                } else {
                    if ($row['is_have_two_time']) {
                        if ($row['reach_delivery_fee_type2'] == 0) {
                            if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
                                $temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
                            }
                        } elseif ($row['reach_delivery_fee_type2'] == 1) {
                            //$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                        } elseif ($row['reach_delivery_fee_type2'] == 2) {
                            $row['delivery_fee2'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
                        }
                    } else {
                        if ($row['reach_delivery_fee_type'] == 0) {
                            if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
                                $temp['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
                            }
                        } elseif ($row['reach_delivery_fee_type'] == 1) {
                            //$temp['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                        } elseif ($row['reach_delivery_fee_type'] == 2) {
                            $row['delivery_fee'] && $temp['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
                        }
                    }
                }
            }
            $temp['coupon_count'] = count($temp['coupon_list']);

            $temp['free_delivery'] = 0;
            $temp['event'] = "";
            $delivery_coupon = D('New_event')->getFreeDeliverCoupon($row['store_id'],$city_id);
            if($delivery_coupon != "" && $delivery_coupon['limit_day']*1000 >= $row['juli']){
                $temp['free_delivery'] = 1;
                $t_event['use_price'] = $delivery_coupon['use_price'];
                $t_event['discount'] = $delivery_coupon['discount'];
                $t_event['miles'] = $delivery_coupon['limit_day']*1000;
                $t_event['desc'] = $delivery_coupon['desc'];
                $t_event['event_type'] = $delivery_coupon['event_type'];

                $temp['event'] = $t_event;

                //$temp['delivery_money'] =  $temp['delivery_money'] - $delivery_coupon['discount'];
                //$temp['delivery_money'] = $temp['delivery_money'] < 0 ? 0 : $temp['delivery_money'];
            }

            //garfunkel店铺满减活动
            $eventList = D('New_event')->getEventList(1,4);
            $store_coupon = "";
            if(count($eventList) > 0) {
                $store_coupon = D('New_event_coupon')->where(array('event_id' => $eventList[0]['id'],'limit_day'=>$row['store_id']))->order('use_price asc')->select();
                if(count($store_coupon) > 0){
                    if(C('DEFAULT_LANG') == 'zh-cn'){
                        $temp['merchant_reduce_list'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),'$'.$store_coupon[0]['use_price']).replace_lang_str(L('_MAN_REDUCE_NUM_'),'$'.$store_coupon[0]['discount']);
                    }else{
                        $temp['merchant_reduce_list'] = replace_lang_str(L('_MAN_NUM_REDUCE_'),'$'.$store_coupon[0]['discount']).'$'.$store_coupon[0]['use_price'];
                    }
                }
            }

            //获取特殊城市属性
            $is_add = true;
            $city = D('Area')->where(array('area_id'=>$row['city_id']))->find();
            if($city['range_type'] != 0){
                switch ($city['range_type']){
                    case 1://按照纬度限制的城市 小于某个纬度
                        if($lat >= $city['range_para']) $is_add = false;
                        break;
                    default:
                        break;
                }
            }

            if($is_add) $return[] = $temp;
        }
        echo json_encode(array('store_list' => $return, 'has_more' => $lists['has_more'] ? true : false));
    }

    /*店铺详情页面*/
    public function ajax_shop() {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();

        $user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
        if($_COOKIE['userLocationLat']){
            $user_long_lat['lat'] = $_COOKIE['userLocationLat'];
            $user_long_lat['long'] = $_COOKIE['userLocationLong'];
        }
        //资质认证
        if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
            echo json_encode(array());
            exit;
        }
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        $now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
        if (empty($now_shop) || empty($now_store)) {
            echo json_encode(array());
            exit;
        }
        $auth_files = array();
        if (!empty($now_store['auth_files'])) {
            $auth_file_class = new auth_file();
            $tmp_pic_arr = explode(';', $now_store['auth_files']);
            foreach($tmp_pic_arr as $key => $value){
                $auth_files[] = $auth_file_class->get_image_by_path($value, 'm');//array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
            }
        }
        $now_store['auth_files'] = $auth_files;
        $discounts = D('Shop_discount')->get_discount_byids(array($store_id));
        $row = array_merge($now_store, $now_shop);

        $store = array();
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($row['pic_info']);

        $store['id'] = $row['store_id'];

        $store['phone'] = $row['phone'];
        $store['long'] = $row['long'];
        $store['lat'] = $row['lat'];
        $store['store_theme'] = $row['store_theme'];
        $store['is_mult_class'] = $row['is_mult_class'];
        $store['adress'] = $row['adress'];
        $store['is_close'] = 1;
        $store['isverify'] = $now_mer['isverify'];
        $store['shop_remind'] = $row['shop_remind'];
        $now_time = date('H:i:s');


//		if ($row['open_1'] == '00:00:00' && $row['close_1'] == '00:00:00') {
//			$store['time'] = '24小时营业';
//			$store['is_close'] = 0;
//		} else {
//			$store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
//			if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
//				$store['is_close'] = 0;
//			}
//			if ($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00') {
//				$store['time'] .= ',' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
//				if ($row['open_2'] < $now_time && $now_time < $row['close_2']) {
//					$store['is_close'] = 0;
//				}
//			}
//			if ($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00') {
//				$store['time'] .= ',' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
//				if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
//					$store['is_close'] = 0;
//				}
//			}
//		}
        if($row['store_is_close'] != 0){
            $row = checkAutoOpen($row);
        }
        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
        switch ($date){
            case 1 :
                if ($row['open_1'] != '00:00:00' || $row['close_1'] != '00:00:00'){
                    if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00'){
                    if($row['open_2'] < $now_time && $now_time < $row['close_2']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00'){
                    if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
                $store['time'] .= ';' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
                $store['time'] .= ';' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
                break;
            case 2 ://周二
                if ($row['open_4'] != '00:00:00' || $row['close_4'] != '00:00:00') {
                    if ($row['open_4'] < $now_time && $now_time < $row['close_4']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_5'] != '00:00:00' || $row['close_5'] != '00:00:00') {
                    if ($row['open_5'] < $now_time && $now_time < $row['close_5']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_6'] != '00:00:00' || $row['close_6'] != '00:00:00') {
                    if ($row['open_6'] < $now_time && $now_time < $row['close_6']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_4'], 0, -3) . '~' . substr($row['close_4'], 0, -3);
                $store['time'] .= ';' . substr($row['open_5'], 0, -3) . '~' . substr($row['close_5'], 0, -3);
                $store['time'] .= ';' . substr($row['open_6'], 0, -3) . '~' . substr($row['close_6'], 0, -3);
                break;
            case 3 ://周三
                if ($row['open_7'] != '00:00:00' || $row['close_7'] != '00:00:00') {
                    if ($row['open_7'] < $now_time && $now_time < $row['close_7']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_8'] != '00:00:00' || $row['close_8'] != '00:00:00') {
                    if ($row['open_8'] < $now_time && $now_time < $row['close_8']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_9'] != '00:00:00' || $row['close_9'] != '00:00:00') {
                    if ($row['open_9'] < $now_time && $now_time < $row['close_9']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_7'], 0, -3) . '~' . substr($row['close_7'], 0, -3);
                $store['time'] .= ';' . substr($row['open_8'], 0, -3) . '~' . substr($row['close_8'], 0, -3);
                $store['time'] .= ';' . substr($row['open_9'], 0, -3) . '~' . substr($row['close_9'], 0, -3);

                break;
            case 4 :
                if ($row['open_10'] != '00:00:00' || $row['close_10'] != '00:00:00') {
                    if ($row['open_10'] < $now_time && $now_time < $row['close_10']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_11'] != '00:00:00' || $row['close_11'] != '00:00:00') {
                    if ($row['open_11'] < $now_time && $now_time < $row['close_11']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_12'] != '00:00:00' || $row['close_12'] != '00:00:00') {
                    if ($row['open_12'] < $now_time && $now_time < $row['close_12']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_10'], 0, -3) . '~' . substr($row['close_10'], 0, -3);
                $store['time'] .= ';' . substr($row['open_11'], 0, -3) . '~' . substr($row['close_11'], 0, -3);
                $store['time'] .= ';' . substr($row['open_12'], 0, -3) . '~' . substr($row['close_12'], 0, -3);
                break;
            case 5 :
                if ($row['open_13'] != '00:00:00' || $row['close_13'] != '00:00:00') {
                    if ($row['open_13'] < $now_time && $now_time < $row['close_13']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_14'] != '00:00:00' || $row['close_14'] != '00:00:00') {
                    if ($row['open_14'] < $now_time && $now_time < $row['close_14']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_15'] != '00:00:00' || $row['close_15'] != '00:00:00') {
                    if ($row['open_15'] < $now_time && $now_time < $row['close_15']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_13'], 0, -3) . '~' . substr($row['close_13'], 0, -3);
                $store['time'] .= ';' . substr($row['open_14'], 0, -3) . '~' . substr($row['close_14'], 0, -3);
                $store['time'] .= ';' . substr($row['open_15'], 0, -3) . '~' . substr($row['close_15'], 0, -3);
                break;
            case 6 :
                if ($row['open_16'] != '00:00:00' || $row['close_16'] != '00:00:00') {
                    if ($row['open_16'] < $now_time && $now_time < $row['close_16']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_17'] != '00:00:00' || $row['close_17'] != '00:00:00') {
                    if ($row['open_17'] < $now_time && $now_time < $row['close_17']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_18'] != '00:00:00' || $row['close_18'] != '00:00:00') {
                    if ($row['open_18'] < $now_time && $now_time < $row['close_18']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_16'], 0, -3) . '~' . substr($row['close_16'], 0, -3);
                $store['time'] .= ';' . substr($row['open_17'], 0, -3) . '~' . substr($row['close_17'], 0, -3);
                $store['time'] .= ';' . substr($row['open_18'], 0, -3) . '~' . substr($row['close_18'], 0, -3);
                break;
            case 0 :
                if ($row['open_19'] != '00:00:00' || $row['close_19'] != '00:00:00') {
                    if ($row['open_19'] < $now_time && $now_time < $row['close_19']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_20'] != '00:00:00' || $row['close_20'] != '00:00:00') {
                    if ($row['open_20'] < $now_time && $now_time < $row['close_20']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_21'] != '00:00:00' || $row['close_21'] != '00:00:00') {
                    if ($row['open_21'] < $now_time && $now_time < $row['close_21']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_19'], 0, -3) . '~' . substr($row['close_19'], 0, -3);
                $store['time'] .= ';' . substr($row['open_20'], 0, -3) . '~' . substr($row['close_20'], 0, -3);
                $store['time'] .= ';' . substr($row['open_21'], 0, -3) . '~' . substr($row['close_21'], 0, -3);
                break;
            default :
                $store['is_close'] = 1;
                $store['time']= '营业时间未知';
        }
        //garfunkel add
        if($row['store_is_close'] != 0){
            $store['is_close'] = 1;
        }
        //end  @wangchuanyuan
        $store['home_url'] = U('Index/index', array('token' => $row['mer_id']));
        //modify garfunkel 判断语言
        $store['name'] = lang_substr($row['name'],C('DEFAULT_LANG'));
        //modify garfunkel 判断语言
        $store['store_notice'] = lang_substr($row['store_notice'],C('DEFAULT_LANG'));
        $store['txt_info'] = $row['txt_info'];
        $store['image'] = isset($images[0]) ? $images[0] : '';
        $store['auth_files_str'] = implode(',', $auth_files);
        $store['auth_files'] = $auth_files;
        $store['images'] = $images;
        $store['images_str'] = implode(',', $images);
        $store['star'] = $row['score_mean'];
        $store['month_sale_count'] = $row['sale_count'];
        $store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
        $store['delivery_time'] = $row['send_time'];//配送时长
        $store['delivery_price'] = floatval($row['basic_price']);//起送价

        $is_have_two_time = 0;//是否是第二时段的配送显示

        if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
            if ($this->config['delivery_time']) {
                $delivery_times = explode('-', $this->config['delivery_time']);
                $start_time = $delivery_times[0] . ':00';
                $stop_time = $delivery_times[1] . ':00';
                if (!($start_time == $stop_time && $start_time == '00:00:00')) {
                    if ($this->config['delivery_time2']) {
                        $delivery_times2 = explode('-', $this->config['delivery_time2']);
                        $start_time2 = $delivery_times2[0] . ':00';
                        $stop_time2 = $delivery_times2[1] . ':00';
                        if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
                            $is_have_two_time = 1;
                        }
                    }
                }
            }

            if ($is_have_two_time) {
                if ($now_time <= $stop_time || $now_time > $stop_time2) {
                    $is_have_two_time = 0;
                }
            }

            if ($row['s_is_open_own']) {
                if ($is_have_two_time) {
                    //$store['delivery_money'] = $row['s_free_type2'] == 0 ? 0 : $row['s_delivery_fee2'];
                } else {
                    //$store['delivery_money'] = $row['s_free_type'] == 0 ? 0 : $row['s_delivery_fee'];
                }
            } else {
                //$store['delivery_money'] = $is_have_two_time ? C('config.delivery_fee2') : C('config.delivery_fee');
            }
        } else {
            if (!($row['delivertime_start'] == $row['delivertime_stop'] && $row['delivertime_start'] == '00:00:00')) {
                if (!($row['delivertime_start2'] == $row['delivertime_stop2'] && $row['delivertime_start2'] == '00:00:00')) {
                    $is_have_two_time = 1;
                }
            }
            if ($is_have_two_time) {
                if ($now_time <= $row['delivertime_stop'] || $now_time > $row['delivertime_stop2']) {
                    $is_have_two_time = 0;
                }
            }
            //$store['delivery_money'] = $is_have_two_time ? $row['delivery_fee2'] : $row['delivery_fee'];
        }

        //modify garfunkel
        if($user_long_lat && $user_long_lat['lat'] != 0){
            $store['delivery_money'] = getDeliveryFee($store['lat'],$store['long'],$user_long_lat['lat'],$user_long_lat['long'],$row['city_id']);
        }else{
            $store['delivery_money'] = C('config.delivery_distance_1');
        }
        //$store['delivery_money'] = floatval($store['delivery_money']);
// 		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
// 		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
        $store['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
        if (in_array($row['deliver_type'], array(2, 3, 4))) {
            $store['pick'] = 1;//是否支持自提
        } else {
            $store['pick'] = 0;//是否支持自提
        }
        $store['pack_alias'] = $row['pack_alias'];//打包费别名
        //modify garfunkel
        $store['pack_fee'] = $row['pack_fee'];
        $store['freight_alias'] = $row['freight_alias'];//运费别名
        $store['coupon_list'] = array();
        if ($row['is_invoice']) {
            $store['coupon_list']['invoice'] = floatval($row['invoice_price']);
        }
        if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
            $store['coupon_list']['discount'] = $row['store_discount']/10;
        }
        $system_delivery = array();
        if (isset($discounts[0]) && $discounts[0]) {
            foreach ($discounts[0] as $row_d) {
                if ($row_d['type'] == 0) {//新单
                    $store['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 1) {//满减
                    $store['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 2) {//配送
                    $system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                }
            }
        }
        if (isset($discounts[$store_id]) && $discounts[$store_id]) {
            foreach ($discounts[$store_id] as $row_m) {
                if ($row_m['type'] == 0) {
                    $store['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                } elseif ($row_m['type'] == 1) {
                    $store['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                }
            }
        }
        if ($store['delivery']) {
            if ($store['delivery_system']) {
                $system_delivery && $store['coupon_list']['delivery'] = $system_delivery;
            } else {
                if ($is_have_two_time) {
                    if ($row['reach_delivery_fee_type2'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
                            $store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
                        }
                    } elseif ($row['reach_delivery_fee_type'] == 1) {
                        //$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } else {
                        $row['delivery_fee2'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
                    }
                } else {
                    if ($row['reach_delivery_fee_type'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
                            $store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
                        }
                    } elseif ($row['reach_delivery_fee_type'] == 1) {
                        //$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } else {
                        $row['delivery_fee'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
                    }
                }
            }
        }
        $today = date('Ymd');

        $product_list = D('Shop_goods')->get_list_by_storeid($store_id);
        foreach ($product_list as $row) {
            $temp = array();
            $temp['cat_id'] = $row['sort_id'];
            //modify garfunkel 判断语言
            $temp['cat_name'] = lang_substr($row['sort_name'],C('DEFAULT_LANG'));
            $temp['sort_discount'] = $row['sort_discount']/10;
            foreach ($row['goods_list'] as $r) {
                $glist = array();
                $glist['product_id'] = $r['goods_id'];
                //modify garfunkel 判断语言
                $glist['product_name'] = lang_substr($r['name'],C('DEFAULT_LANG'));
                $glist['product_price'] = $r['price'];
                $glist['is_seckill_price'] = $r['is_seckill_price'];
                $glist['o_price'] = $r['o_price'];
                $glist['number'] = $r['number'];
                $glist['packing_charge'] = floatval($r['packing_charge']);
                $glist['unit'] = $r['unit'];
                $glist['tax_num'] = $r['tax_num'];
                $glist['deposit_price'] = $r['deposit_price'];
                if (isset($r['pic_arr'][0])) {
                    $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                }
                $glist['product_sale'] = $r['sell_count'];
                $glist['product_reply'] = $r['reply_count'];
                $glist['has_format'] = false;
                if ($r['spec_value'] || $r['is_properties']) {
                    $glist['has_format'] = true;
                }
                if($r['extra_pay_price']>0){
                    $glist['extra_pay_price']=$r['extra_pay_price'];
                    $glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
                }

                $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
                if ($today == $r['sell_day']) {
                    $glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                } else {
                    $glist['stock'] = $r['stock_num'];
                }
                $temp['product_list'][] = $glist;
            }
            $list[] = $temp;
        }
        echo json_encode(array('store' => $store, 'product_list' => $list));
    }


    public function ajaxShop()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();

        $user_long_lat = D('User_long_lat')->getLocation($_SESSION['openid'],0);
        if($_COOKIE['userLocationLat']){
            $user_long_lat['lat'] = $_COOKIE['userLocationLat'];
            $user_long_lat['long'] = $_COOKIE['userLocationLong'];
        }
        //资质认证
        if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
            echo json_encode(array());
            exit;
        }
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        //var_dump($now_shop);die();
        $now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
        if (empty($now_shop) || empty($now_store)) {
            echo json_encode(array());
            exit;
        }

        $auth_files = array();
        if (!empty($now_store['auth_files'])) {
            $auth_file_class = new auth_file();
            $tmp_pic_arr = explode(';', $now_store['auth_files']);
            foreach($tmp_pic_arr as $key => $value){
                $auth_files[] = $auth_file_class->get_image_by_path($value, 'm');//array('title' => $value, 'url' => $auth_file_class->get_image_by_path($value, 's'));
            }
        }
        $now_store['auth_files'] = $auth_files;
        $discounts = D('Shop_discount')->get_discount_byids(array($store_id));
        $row = array_merge($now_store, $now_shop);

        $store = array();
        $store_image_class = new store_image();
        $images = $store_image_class->get_allImage_by_path($row['pic_info']);

        $store['id'] = $row['store_id'];

        $image_tmp = explode(',', $row['background']);
        $store['background'] = C('config.site_url') . '/upload/background/' . $image_tmp[0] . '/' . $image_tmp['1'];

        $store['phone'] = $row['phone'];
        $store['long'] = $row['long'];
        $store['lat'] = $row['lat'];
        $store['tmpl'] = $row['store_theme'] ? ($row['is_mult_class'] ? 0 : 1) : 0;
        $store['store_theme'] = $row['store_theme'];
        $store['adress'] = $row['adress'];
        $store['is_close'] = 1;
        $store['store_notice'] = lang_substr($row['adress'],C('DEFAULT_LANG'));
        $store['isverify'] = $now_mer['isverify'];
        $store['store_status'] = $now_store['status'];
        $store['shop_remind'] = $row['shop_remind'];
        $now_time = date('H:i:s');
        $keywords = D('Keywords')->where(array('third_type' => 'Merchant_store', 'third_id' => $row['store_id']))->select();
        $str = "";
        foreach ($keywords as $key) {
            $str .= $key['keyword'] . " ";
        }
        $store['keywords'] = $str;

        if($row['store_is_close'] != 0){
            $row = checkAutoOpen($row);
        }
        //获得配送时间列表
        $time_list = array();
        for ($i = 0;$i < 21;++$i){
            $this_num = $i + 1;
            if ($row['open_'.$this_num] != '00:00:00' || $row['close_'.$this_num] != '00:00:00'){
                $open_time[$i] = substr($row['open_'.$this_num], 0, -3) . '-' . substr($row['close_'.$this_num], 0, -3);
            }else{
                $open_time[$i] = "";
            }

            $day_num = $i/3;
            if($time_list[$day_num] == ""){
                $time_list[$day_num] = $open_time[$i];
            }else{
                if($open_time[$i] != "")
                    $time_list[$day_num] .= ", ".$open_time[$i];
            }
        }

        $store['open_list'] = $time_list;
        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
        switch ($date){
            case 1 :
                if ($row['open_1'] != '00:00:00' || $row['close_1'] != '00:00:00'){
                    if ($row['open_1'] < $now_time && $now_time < $row['close_1']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_2'] != '00:00:00' || $row['close_2'] != '00:00:00'){
                    if($row['open_2'] < $now_time && $now_time < $row['close_2']) {
                        $store['is_close'] = 0;
                    }
                }
                if($row['open_3'] != '00:00:00' || $row['close_3'] != '00:00:00'){
                    if ($row['open_3'] < $now_time && $now_time < $row['close_3']) {
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_1'], 0, -3) . '~' . substr($row['close_1'], 0, -3);
                $store['time'] .= ';' . substr($row['open_2'], 0, -3) . '~' . substr($row['close_2'], 0, -3);
                $store['time'] .= ';' . substr($row['open_3'], 0, -3) . '~' . substr($row['close_3'], 0, -3);
                break;
            case 2 ://周二
                if ($row['open_4'] != '00:00:00' || $row['close_4'] != '00:00:00') {
                    if ($row['open_4'] < $now_time && $now_time < $row['close_4']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_5'] != '00:00:00' || $row['close_5'] != '00:00:00') {
                    if ($row['open_5'] < $now_time && $now_time < $row['close_5']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_6'] != '00:00:00' || $row['close_6'] != '00:00:00') {
                    if ($row['open_6'] < $now_time && $now_time < $row['close_6']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_4'], 0, -3) . '~' . substr($row['close_4'], 0, -3);
                $store['time'] .= ';' . substr($row['open_5'], 0, -3) . '~' . substr($row['close_5'], 0, -3);
                $store['time'] .= ';' . substr($row['open_6'], 0, -3) . '~' . substr($row['close_6'], 0, -3);
                break;
            case 3 ://周三
                if ($row['open_7'] != '00:00:00' || $row['close_7'] != '00:00:00') {
                    if ($row['open_7'] < $now_time && $now_time < $row['close_7']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_8'] != '00:00:00' || $row['close_8'] != '00:00:00') {
                    if ($row['open_8'] < $now_time && $now_time < $row['close_8']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_9'] != '00:00:00' || $row['close_9'] != '00:00:00') {
                    if ($row['open_9'] < $now_time && $now_time < $row['close_9']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_7'], 0, -3) . '~' . substr($row['close_7'], 0, -3);
                $store['time'] .= ';' . substr($row['open_8'], 0, -3) . '~' . substr($row['close_8'], 0, -3);
                $store['time'] .= ';' . substr($row['open_9'], 0, -3) . '~' . substr($row['close_9'], 0, -3);

                break;
            case 4 :
                if ($row['open_10'] != '00:00:00' || $row['close_10'] != '00:00:00') {
                    if ($row['open_10'] < $now_time && $now_time < $row['close_10']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_11'] != '00:00:00' || $row['close_11'] != '00:00:00') {
                    if ($row['open_11'] < $now_time && $now_time < $row['close_11']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_12'] != '00:00:00' || $row['close_12'] != '00:00:00') {
                    if ($row['open_12'] < $now_time && $now_time < $row['close_12']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_10'], 0, -3) . '~' . substr($row['close_10'], 0, -3);
                $store['time'] .= ';' . substr($row['open_11'], 0, -3) . '~' . substr($row['close_11'], 0, -3);
                $store['time'] .= ';' . substr($row['open_12'], 0, -3) . '~' . substr($row['close_12'], 0, -3);
                break;
            case 5 :
                if ($row['open_13'] != '00:00:00' || $row['close_13'] != '00:00:00') {
                    if ($row['open_13'] < $now_time && $now_time < $row['close_13']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_14'] != '00:00:00' || $row['close_14'] != '00:00:00') {
                    if ($row['open_14'] < $now_time && $now_time < $row['close_14']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_15'] != '00:00:00' || $row['close_15'] != '00:00:00') {
                    if ($row['open_15'] < $now_time && $now_time < $row['close_15']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_13'], 0, -3) . '~' . substr($row['close_13'], 0, -3);
                $store['time'] .= ';' . substr($row['open_14'], 0, -3) . '~' . substr($row['close_14'], 0, -3);
                $store['time'] .= ';' . substr($row['open_15'], 0, -3) . '~' . substr($row['close_15'], 0, -3);
                break;
            case 6 :
                if ($row['open_16'] != '00:00:00' || $row['close_16'] != '00:00:00') {
                    if ($row['open_16'] < $now_time && $now_time < $row['close_16']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_17'] != '00:00:00' || $row['close_17'] != '00:00:00') {
                    if ($row['open_17'] < $now_time && $now_time < $row['close_17']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_18'] != '00:00:00' || $row['close_18'] != '00:00:00') {
                    if ($row['open_18'] < $now_time && $now_time < $row['close_18']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_16'], 0, -3) . '~' . substr($row['close_16'], 0, -3);
                $store['time'] .= ';' . substr($row['open_17'], 0, -3) . '~' . substr($row['close_17'], 0, -3);
                $store['time'] .= ';' . substr($row['open_18'], 0, -3) . '~' . substr($row['close_18'], 0, -3);
                break;
            case 0 :
                if ($row['open_19'] != '00:00:00' || $row['close_19'] != '00:00:00') {
                    if ($row['open_19'] < $now_time && $now_time < $row['close_19']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_20'] != '00:00:00' || $row['close_20'] != '00:00:00') {
                    if ($row['open_20'] < $now_time && $now_time < $row['close_20']){
                        $store['is_close'] = 0;
                    }
                }
                if ($row['open_21'] != '00:00:00' || $row['close_21'] != '00:00:00') {
                    if ($row['open_21'] < $now_time && $now_time < $row['close_21']){
                        $store['is_close'] = 0;
                    }
                }
                $store['time'] = substr($row['open_19'], 0, -3) . '~' . substr($row['close_19'], 0, -3);
                $store['time'] .= ';' . substr($row['open_20'], 0, -3) . '~' . substr($row['close_20'], 0, -3);
                $store['time'] .= ';' . substr($row['open_21'], 0, -3) . '~' . substr($row['close_21'], 0, -3);
                break;
            default :
                $store['is_close'] = 1;
                $store['time']= '营业时间未知';
        }

        //garfunkel add
        if($row['store_is_close'] != 0){
            $store['is_close'] = 1;
        }
        //end  @wangchuanyuan

        $store['home_url'] = U('Index/index', array('token' => $row['mer_id']));
        //modify garfunkel 判断语言
        $store['name'] = lang_substr($row['name'],C('DEFAULT_LANG'));
        $store['store_notice'] = lang_substr($row['store_notice'],C('DEFAULT_LANG'));
        $store['txt_info'] = $row['txt_info'];
        $store['image'] = isset($images[0]) ? $images[0] : '';
        $store['auth_files_str'] = implode(',', $auth_files);
        $store['auth_files'] = $auth_files;
        $store['images'] = $images;
        $store['images_str'] = implode(',', $images);
        $store['star'] = $row['score_mean'];
        $store['month_sale_count'] = $row['sale_count'];
        $store['delivery'] = $row['deliver_type'] == 2 ? false : true;//是否支持配送
        $store['delivery_time'] = $row['send_time'];//配送时长
        $store['delivery_price'] = floatval($row['basic_price']);//起送价
        $store['city_id'] = $row['city_id'];

        $is_have_two_time = 0;//是否是第二时段的配送显示

        if ($row['deliver_type'] == 0 || $row['deliver_type'] == 3) {
            if ($this->config['delivery_time']) {
                $delivery_times = explode('-', $this->config['delivery_time']);
                $start_time = $delivery_times[0] . ':00';
                $stop_time = $delivery_times[1] . ':00';
                if (!($start_time == $stop_time && $start_time == '00:00:00')) {
                    if ($this->config['delivery_time2']) {
                        $delivery_times2 = explode('-', $this->config['delivery_time2']);
                        $start_time2 = $delivery_times2[0] . ':00';
                        $stop_time2 = $delivery_times2[1] . ':00';
                        if (!($start_time2 == $stop_time2 && $start_time2 == '00:00:00')) {
                            $is_have_two_time = 1;
                        }
                    }
                }
            }

            if ($is_have_two_time) {
                if ($now_time <= $stop_time || $now_time > $stop_time2) {
                    $is_have_two_time = 0;
                }
            }

            if ($row['s_is_open_own']) {
                if ($is_have_two_time) {
                    //$store['delivery_money'] = $row['s_free_type2'] == 0 ? 0 : $row['s_delivery_fee2'];
                } else {
                    //$store['delivery_money'] = $row['s_free_type'] == 0 ? 0 : $row['s_delivery_fee'];
                }
            } else {
                //$store['delivery_money'] = $is_have_two_time ? C('config.delivery_fee2') : C('config.delivery_fee');
            }
        } else {
            if (!($row['delivertime_start'] == $row['delivertime_stop'] && $row['delivertime_start'] == '00:00:00')) {
                if (!($row['delivertime_start2'] == $row['delivertime_stop2'] && $row['delivertime_start2'] == '00:00:00')) {
                    $is_have_two_time = 1;
                }
            }
            if ($is_have_two_time) {
                if ($now_time <= $row['delivertime_stop'] || $now_time > $row['delivertime_stop2']) {
                    $is_have_two_time = 0;
                }
            }
            //$store['delivery_money'] = $is_have_two_time ? $row['delivery_fee2'] : $row['delivery_fee'];
        }

        //garfunkel获取减免配送费的活动
        $delivery_coupon = D('New_event')->getFreeDeliverCoupon($store_id,$store['city_id']);

        //garfunkel店铺满减活动
        $eventList = D('New_event')->getEventList(1,4);
        $store_coupon = "";
        if(count($eventList) > 0) {
            $store_coupon = D('New_event_coupon')->where(array('event_id' => $eventList[0]['id'],'limit_day'=>$store_id))->order('use_price asc')->select();
            if(count($store_coupon) > 0){
                foreach ($store_coupon as $c) {
                    if (C('DEFAULT_LANG') == 'zh-cn') {
                        $reduce[] = replace_lang_str(L('_MAN_NUM_REDUCE_'), '$' . $c['use_price']) . replace_lang_str(L('_MAN_REDUCE_NUM_'), '$' . $c['discount']);
                    } else {
                        $reduce[] = replace_lang_str(L('_MAN_NUM_REDUCE_'), '$' . $c['discount']) . '$' . $c['use_price'];
                    }
                }
                $store['reduce'] = $reduce;
            }
        }

        //modify garfunkel
        if($user_long_lat && $user_long_lat['lat'] != 0){
            //$store['distance'] = getDistance($store['lat'],$store['long'],$user_long_lat['lat'],$user_long_lat['long']);
            $from = $store['lat'].','.$store['long'];
            $aim = $user_long_lat['lat'].','.$user_long_lat['long'];
            $store['distance'] = getDistanceByGoogle($from,$aim);
            $store['delivery_money'] = calculateDeliveryFee($store['distance'],$store['city_id']);
            //$store['delivery_money'] = getDeliveryFee($store['lat'],$store['long'],$user_long_lat['lat'],$user_long_lat['long'],$row['city_id']);

            $store['free_delivery'] = 0;
            $store['event'] = "";

            if($delivery_coupon != "" && $delivery_coupon['limit_day']*1000 >= $store['distance']*1000){
                $store['free_delivery'] = 1;
                $t_event['use_price'] = $delivery_coupon['use_price'];
                $t_event['discount'] = $delivery_coupon['discount'];
                $t_event['miles'] = $delivery_coupon['limit_day']*1000;
                $t_event['desc'] = $delivery_coupon['desc'];
                $t_event['event_type'] = $delivery_coupon['event_type'];

                $store['event'] = $t_event;

                //$temp['delivery_money'] =  $temp['delivery_money'] - $delivery_coupon['discount'];
            }
        }else{
            $store['delivery_money'] = C('config.delivery_distance_1');
        }
        //$store['delivery_money'] = floatval($store['delivery_money']);
        // 		$store['delivery_money'] = $row['deliver_type'] == 0 ? C('config.delivery_fee') : $row['delivery_fee'];//配送费
        // 		$store['delivery_money'] = floatval($store['delivery_money']);//配送费
        $store['delivery_system'] = $row['deliver_type'] == 0 || $row['deliver_type'] == 3 ? true : false;//是否是平台配送
        if (in_array($row['deliver_type'], array(2, 3, 4))) {
            $store['pick'] = 1;//是否支持自提
        } else {
            $store['pick'] = 0;//是否支持自提
        }
        $store['pack_alias'] = $row['pack_alias'];//打包费别名
        //add garfunkel
        $store['pack_fee'] = $row['pack_fee'];//打包费用
        $store['freight_alias'] = $row['freight_alias'];//运费别名
        $store['coupon_list'] = array();
        if ($row['is_invoice']) {
            $store['coupon_list']['invoice'] = floatval($row['invoice_price']);
        }
        if ($row['store_discount'] != 0 && $row['store_discount'] != 100) {
            $store['coupon_list']['discount'] = $row['store_discount']/10;
        }
        $system_delivery = array();
        if (isset($discounts[0]) && $discounts[0]) {
            foreach ($discounts[0] as $row_d) {
                if ($row_d['type'] == 0) {//新单
                    $store['coupon_list']['system_newuser'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 1) {//满减
                    $store['coupon_list']['system_minus'][] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                } elseif ($row_d['type'] == 2) {//配送
                    $system_delivery[] = array('money' => floatval($row_d['full_money']), 'minus' => floatval($row_d['reduce_money']));
                }
            }
        }
        if (isset($discounts[$store_id]) && $discounts[$store_id]) {
            foreach ($discounts[$store_id] as $row_m) {
                if ($row_m['type'] == 0) {
                    $store['coupon_list']['newuser'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                } elseif ($row_m['type'] == 1) {
                    $store['coupon_list']['minus'][] = array('money' => floatval($row_m['full_money']), 'minus' => floatval($row_m['reduce_money']));
                }
            }
        }
        if ($store['delivery']) {
            if ($store['delivery_system']) {
                $system_delivery && $store['coupon_list']['delivery'] = $system_delivery;
            } else {
                if ($is_have_two_time) {
                    if ($row['reach_delivery_fee_type2'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee2'] > 0) {
                            $store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee2']));
                        }
                    } elseif ($row['reach_delivery_fee_type'] == 1) {
                        //$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } else {
                        $row['delivery_fee2'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value2']), 'minus' => floatval($row['delivery_fee2']));
                    }
                } else {
                    if ($row['reach_delivery_fee_type'] == 0) {
                        if ($row['basic_price'] > 0 && $row['delivery_fee'] > 0) {
                            $store['coupon_list']['delivery'][] = array('money' => floatval($row['basic_price']), 'minus' => floatval($row['delivery_fee']));
                        }
                    } elseif ($row['reach_delivery_fee_type'] == 1) {
                        //$store['coupon_list']['delivery'] = array('money' => false, 'minus' => $row['delivery_fee']);
                    } else {
                        $row['delivery_fee'] && $store['coupon_list']['delivery'][] = array('money' => floatval($row['no_delivery_fee_value']), 'minus' => floatval($row['delivery_fee']));
                    }
                }
            }
        }

        if ($store['tmpl']) {
            $today = date('Ymd');
            $product_list = D('Shop_goods')->get_list_by_storeid($store_id);

            foreach ($product_list as $row) {
                $temp = array();
                $temp['cat_id'] = $row['sort_id'];
                //modify garfunkel 判断语言
                $temp['cat_name'] = lang_substr($row['sort_name'],C('DEFAULT_LANG'));
                $temp['sort_discount'] = $row['sort_discount']/10;
                foreach ($row['goods_list'] as $r) {
                    $glist = array();
                    $glist['product_id'] = $r['goods_id'];
                    $glist['product_desc'] = $r['des'];
                    //modify garfunkel 判断语言
                    $glist['product_name'] = lang_substr($r['name'],C('DEFAULT_LANG'));
                    $glist['product_price'] = $r['price'];
                    $glist['is_seckill_price'] = $r['is_seckill_price'];
                    $glist['o_price'] = $r['o_price'];
                    $glist['number'] = $r['number'];
                    $glist['packing_charge'] = floatval($r['packing_charge']);
                    $glist['unit'] = $r['unit'];
                    if (isset($r['pic_arr'][0])) {
                        $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                    }
                    $glist['product_sale'] = $r['sell_count'];
                    $glist['product_reply'] = $r['reply_count'];
                    $glist['has_format'] = false;
                    if ($r['spec_value'] || $r['is_properties']) {
                        $glist['has_format'] = true;
                    }
                    if($r['extra_pay_price']>0){
                        $glist['extra_pay_price']=$r['extra_pay_price'];
                        $glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
                    }
                    $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
                    if ($today == $r['sell_day']) {
                        $glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                    } else {
                        $glist['stock'] = $r['stock_num'];
                    }
                    $temp['product_list'][] = $glist;
                }
                $list[] = $temp;
            }
            /*
            if(count($list) > 0){
                foreach($list as $k => $c) {
                    foreach($c['product_list'] as $i => $v){
                        if($v['product_image'] == '') {
                            $list[$k]['product_list'][$i]['product_image'] = "/static/images/noimage.png";
                        }
                    }
                }
            }*/

            echo json_encode(array('store' => $store, 'product_list' => $list));

        } else {
            if($now_store['menu_version'] == 2){
                $categories = D('StoreMenuV2')->getStoreCategories($store_id,true);
                $sortList = D('StoreMenuV2')->arrangeWap($categories);
                $list = D('StoreMenuV2')->getStoreProduct($categories,$store_id);
                //$list = D('StoreMenuV2')->arrangeProductWap($products);
            }else {
                $sortList = D('Shop_goods_sort')->lists($store_id, true);
                $sortIdList = array();
                $sortListById = array();
                //Add Garfunkel 判断语言
                foreach ($sortList as $k => $sl) {
                    $sortList[$k]['cat_name'] = lang_substr($sl['sort_name'], C('DEFAULT_LANG'));
                    $sortIdList[] = $sl['sort_id'];
                    $sortListById[$sl['sort_id']] = $sl;
                    $show_time_str = explode(',', $sortList[$k]['show_time']);
                    $sortList[$k]['show_time_str'] = $show_time_str[0] . " - " . $show_time_str[1];
                }
                $firstSort = reset($sortList);
                //$sortId = isset($firstSort['sort_id']) ? $firstSort['sort_id'] : 0;
                $product_list = D('Shop_goods')->get_list_by_storeid($store_id);

                foreach ($product_list as $row) {
                    if (in_array($row['sort_id'], $sortIdList)) {
                        $temp = array();
                        $temp['cat_id'] = $row['sort_id'];
                        //modify garfunkel 判断语言
                        $temp['cat_name'] = lang_substr($row['sort_name'], C('DEFAULT_LANG'));
                        $temp['sort_discount'] = $row['sort_discount'] / 10;

                        //是否是限时供应
                        if (($sortListById[$row['sort_id']]['is_weekshow'] == "0") && ($sortListById[$row['sort_id']]['is_time'] == "0")) {
                            $temp['limited_offers'] = "0";
                        } else {
                            $temp['limited_offers'] = "1";
                        }

                        $temp['is_time'] = $sortListById[$row['sort_id']]['is_time'];

                        if ($sortListById[$row['sort_id']]['is_time'] == 1) {
                            $show_time = explode(',', $sortListById[$row['sort_id']]['show_time']);
                            //$sortListById[$row['sort_id']]['show_time'] = $show_time[0]."-".$show_time[1];
                            $temp['begin_time'] = $show_time[0];
                            $temp['end_time'] = $show_time[1];
                        }
                        foreach ($row['goods_list'] as $r) {
                            $glist = array();
                            $glist['product_id'] = $r['goods_id'];
                            $glist['product_desc'] = $r['des'];
                            //modify garfunkel 判断语言
                            $glist['product_name'] = lang_substr($r['name'], C('DEFAULT_LANG'));
                            $glist['product_price'] = $r['price'];
                            $glist['is_seckill_price'] = $r['is_seckill_price'];
                            $glist['o_price'] = $r['o_price'];
                            $glist['number'] = $r['number'];
                            $glist['packing_charge'] = floatval($r['packing_charge']);
                            $glist['unit'] = $r['unit'];
                            if (isset($r['pic_arr'][0])) {
                                $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                            }
                            $glist['product_sale'] = $r['sell_count'];
                            $glist['product_reply'] = $r['reply_count'];
                            $glist['has_format'] = false;
                            if ($r['spec_value'] || $r['is_properties']) {
                                $glist['has_format'] = true;
                            }
                            //garfunkel add side_dish
                            $glist['has_dish'] = false;
                            if (D('Side_dish')->where(array('goods_id' => $r['goods_id'], 'status' => 1))->find()) {
                                $glist['has_dish'] = true;
                            }
                            //
                            if ($r['extra_pay_price'] > 0) {
                                $glist['extra_pay_price'] = $r['extra_pay_price'];
                                $glist['extra_pay_price_name'] = $this->config['extra_price_alias_name'];
                            }

                            $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
                            if ($today == $r['sell_day']) {
                                $glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                            } else {
                                $glist['stock'] = $r['stock_num'];
                            }
                            $temp['product_list'][] = $glist;
                        }
                        $list[] = $temp;
                    }
                }
            }
            //echo json_encode(array('store' => $store, 'product_list' => $this->getGoodsBySortId($sortId, $store_id), 'sort_list' => array_values($sortList)));
            echo json_encode(array('store' => $store, 'product_list' => $list, 'sort_list' => array_values($sortList)));
        }
    }

    private function getGoodsBySortId($sortId, $store_id)
    {
        $sortIds = D('Shop_goods_sort')->getAllSonIds($sortId, $store_id);
        $product_list = D('Shop_goods')->getGoodsBySortIds($sortIds, $store_id);
        $list = array();
        foreach ($product_list as $row) {
            $temp = array();
            $temp['cat_id'] = $row['sort_id'];
            //modify garfunkel 判断语言
            $temp['cat_name'] = lang_substr($row['sort_name'],C('DEFAULT_LANG'));
            $temp['sort_discount'] = $row['sort_discount']/10;
            foreach ($row['goods_list'] as $r) {
                $glist = array();
                $glist['product_id'] = $r['goods_id'];
                //modify garfunkel 判断语言
                $glist['product_name'] = lang_substr($r['name'],C('DEFAULT_LANG'));
                $glist['product_price'] = $r['price'];
                $glist['is_seckill_price'] = $r['is_seckill_price'];
                $glist['o_price'] = $r['o_price'];
                $glist['number'] = $r['number'];
                $glist['packing_charge'] = floatval($r['packing_charge']);
                $glist['unit'] = $r['unit'];
                $glist['tax_num'] = $r['tax_num'];
                $glist['deposit_price'] = $r['deposit_price'];
                if (isset($r['pic_arr'][0])) {
                    $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                }else{exit;
                    $glist['product_image'] = "/static/images/noimage.png";
                }
                $glist['product_sale'] = $r['sell_count'];
                $glist['product_reply'] = $r['reply_count'];
                $glist['has_format'] = false;
                if ($r['spec_value'] || $r['is_properties']) {
                    $glist['has_format'] = true;
                }
                if($r['extra_pay_price']>0){
                    $glist['extra_pay_price']=$r['extra_pay_price'];
                    $glist['extra_pay_price_name']=$this->config['extra_price_alias_name'];
                }

                $r['sell_day'] = $now_shop['stock_type'] ? $today : $r['sell_day'];
                if ($today == $r['sell_day']) {
                    $glist['stock'] = $r['stock_num'] == -1 ? $r['stock_num'] : (intval($r['stock_num'] - $r['today_sell_count']) > 0 ? intval($r['stock_num'] - $r['today_sell_count']) : 0);
                } else {
                    $glist['stock'] = $r['stock_num'];
                }
                $temp['product_list'][] = $glist;
            }
            $list[] = $temp;
        }
        return $list;
    }
    public function showGoodsBySortId()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();

        //资质认证
        if ($this->config['store_shop_auth'] == 1 && $now_store['auth'] < 3) {
            echo json_encode(array());
            exit;
        }
        $now_shop = D('Merchant_store_shop')->field(true)->where($where)->find();
        $now_mer = M('Merchant')->field('isverify')->where(array('mer_id'=>$now_store['mer_id']))->find();
        if (empty($now_shop) || empty($now_store)) {
            echo json_encode(array());
            exit;
        }
        $sortId = isset($_GET['sort_id']) ? $_GET['sort_id'] : 0;
        echo json_encode(array('product_list' => $this->getGoodsBySortId($sortId, $store_id)));

    }

    public function ajaxSearchGoods(){
        $store_id = $_GET['store_id'];
        $keyword = $_GET['keyword'];

        $is_result = 1;

        $store = D('Merchant_store')->where(array('store_id'=>$store_id))->find();

        if($store['menu_version'] == 2){
            $categories = D('StoreMenuV2')->getStoreCategories($store_id,true);
            $list = D('StoreMenuV2')->getStoreProduct($categories,$store_id,1,$keyword);
        }else {
            $product_list = D('Shop_goods')->get_list_by_storeid($store_id, 0, $keyword);
            foreach ($product_list as $row) {
                $temp = array();
                $temp['cat_id'] = 0;
                $temp['cat_name'] = "";
                $temp['sort_discount'] = 0;

                foreach ($row['goods_list'] as $r) {
                    $glist = array();
                    $glist['product_id'] = $r['goods_id'];
                    //modify garfunkel 判断语言
                    $glist['product_name'] = lang_substr($r['name'], C('DEFAULT_LANG'));
                    $glist['product_price'] = $r['price'];
                    $glist['product_desc'] = $r['des'];
                    $glist['is_seckill_price'] = $r['is_seckill_price'];
                    $glist['o_price'] = $r['o_price'];
                    $glist['number'] = $r['number'];
                    $glist['packing_charge'] = floatval($r['packing_charge']);
                    $glist['unit'] = $r['unit'];
                    if (isset($r['pic_arr'][0])) {
                        $glist['product_image'] = $r['pic_arr'][0]['url']['s_image'];
                    }
                    $glist['product_sale'] = $r['sell_count'];
                    $glist['product_reply'] = $r['reply_count'];
                    $glist['has_format'] = false;
                    if ($r['spec_value'] || $r['is_properties']) {
                        $glist['has_format'] = true;
                    }
                    //garfunkel add side_dish
                    $glist['has_dish'] = false;
                    if (D('Side_dish')->where(array('goods_id' => $r['goods_id']))->find()) {
                        $glist['has_dish'] = true;
                    }
                    //
                    if ($r['extra_pay_price'] > 0) {
                        $glist['extra_pay_price'] = $r['extra_pay_price'];
                        $glist['extra_pay_price_name'] = $this->config['extra_price_alias_name'];
                    }

                    $temp['product_list'][] = $glist;
                }
                if (!$temp['product_list']) {
                    $temp['product_list'] = array();
                    $is_result = 0;
                }
                $list[] = $temp;
            }
        }
        //echo json_encode(array('store' => $store, 'product_list' => $this->getGoodsBySortId($sortId, $store_id), 'sort_list' => array_values($sortList)));
        echo json_encode(array('product_list' => $list,'is_result'=>$is_result));
    }

    public function ajax_goods()
    {
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 2;
        $where = array('store_id' => $store_id);
        $now_store = D('Merchant_store')->field(true)->where($where)->find();

        $goods_id = isset($_GET['goods_id']) ? $_GET['goods_id'] : 1;

        if($now_store['menu_version'] == 2){
            $now_goods = D('StoreMenuV2')->getProduct($goods_id,$store_id);
            $now_goods = D('StoreMenuV2')->arrangeProductWapShow($now_goods);

            $dish_list = D('StoreMenuV2')->getProductRelation($goods_id,$store_id,1);

            $dish_list_new = D('StoreMenuV2')->arrangeDishWap($dish_list,$goods_id,$store_id,1);

            $now_goods['side_dish'] = $dish_list_new;
        }else {
            $database_shop_goods = D('Shop_goods');
            $now_goods = $database_shop_goods->get_goods_by_id($goods_id);
            //modify garfunkel 判断语言
            $now_goods['name'] = lang_substr($now_goods['name'], C('DEFAULT_LANG'));
            $now_goods['unit'] = lang_substr($now_goods['unit'], C('DEFAULT_LANG'));
            foreach ($now_goods['properties_list'] as $k => $v) {
                $now_goods['properties_list'][$k]['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                foreach ($v['val'] as $kk => $vv) {
                    $now_goods['properties_list'][$k]['val'][$kk] = lang_substr($vv, C('DEFAULT_LANG'));
                }
            }

            foreach ($now_goods['spec_list'] as $k => $v) {
                $now_goods['spec_list'][$k]['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                foreach ($v['list'] as $kk => $vv) {
                    $now_goods['spec_list'][$k]['list'][$kk]['name'] = lang_substr($vv['name'], C('DEFAULT_LANG'));
                }
            }

            //garfunkel add side_dish
            $dish_list = D('Side_dish')->where(array('goods_id' => $goods_id, 'status' => 1))->select();
            foreach ($dish_list as &$v) {
                $v['name'] = lang_substr($v['name'], C('DEFAULT_LANG'));
                $values = D('Side_dish_value')->where(array('dish_id' => $v['id'], 'status' => 1))->select();
                foreach ($values as &$vv) {
                    $vv['name'] = lang_substr($vv['name'], C('DEFAULT_LANG'));
                    $vv['list'] = array();
                }
                $v['list'] = $values;
            }
            $now_goods['side_dish'] = $dish_list;

            ///
            if (empty($now_goods)) {
                $this->error_tips('商品不存在！');
            }
        }
        echo json_encode($now_goods);
    }
    /*我的收货地址*/
    public function ajax_set_address_default()
    {
        $return = array();
        if ($this->user_session['uid']) {
            $aid=$_GET['aid'];
            $adress_rt = D('User_adress')->set_default($this->user_session['uid'],$aid);
            $return[]=array("succ"=>1);
            //foreach ($adress_list as $row) {
            //    $return[] = array('id'=>$row['adress_id'],'checked'=>$row['checked'],'street' => $row['adress'], 'house' => $row['detail'], 'name' => $row['name'], 'phone' => $row['phone'], 'long' => $row['longitude'], 'lat' => $row['latitude'],'city_id' => $row['city']);
            //}
        }
        echo json_encode($return);
    }
    /*我的收货地址*/
    public function ajax_address()
    {
        $return = array();
        if ($this->user_session['uid']) {
            $lastid=$_GET['lastid'];
            if ($lastid=="null"){}
            $adress_list = D('User_adress')->get_adress_list($this->user_session['uid'],$lastid);

            foreach ($adress_list as $row) {
                $return[] = array('id'=>$row['adress_id'],'checked'=>$row['checked'],'default'=>$row['default'],'street' => $row['adress'], 'house' => $row['detail'], 'name' => $row['name'], 'phone' => $row['phone'], 'long' => $row['longitude'], 'lat' => $row['latitude'],'city_id' => $row['city']);
            }
        }
        echo json_encode($return);
    }

    private function check_cart($order_data = null)
    {
        $this->isLogin();
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

        $store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
        if ($store['have_shop'] == 0 || $store['status'] != 1) {
            return array('error_code' => true, 'msg' => L('_STORE_IS_CLOSE_'));
        }
        if ($this->config['store_shop_auth'] == 1 && $store['auth'] < 3) {
            return array('error_code' => true, 'msg' => '您查看的'.$this->config['shop_alias_name'].'没有通过资质审核！');
            exit;
        }
        $now_time = date('H:i:s');
        $is_open = 0;


//		if ($store['open_1'] == '00:00:00' && $store['close_1'] == '00:00:00') {
//			$is_open = 1;
//		} else {
//			if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
//				$is_open = 1;
//			}
//			if ($store['open_2'] != '00:00:00' || $store['close_2'] != '00:00:00') {
//				if ($store['open_2'] < $now_time && $now_time < $store['close_2']) {
//					$is_open = 1;
//				}
//			}
//			if ($store['open_3'] != '00:00:00' || $store['close_3'] != '00:00:00') {
//				if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
//					$is_open = 1;
//				}
//			}
//		}
        if($store['store_is_close'] != 0){
            $store = checkAutoOpen($store);
        }
        //@wangchuanyuan 周一到周天
        $date = date("w");//今天是星期几 @ydhl-wangchuanyuan 20171106
        switch ($date){
            case 1 :
                if ($store['open_1'] < $now_time && $now_time < $store['close_1']) {
                    $is_open = 1;
                }
                if($store['open_2'] < $now_time && $now_time < $store['close_2']) {
                    $is_open = 1;
                }
                if ($store['open_3'] < $now_time && $now_time < $store['close_3']) {
                    $is_open = 1;
                }
                break;
            case 2 ://周二
                if ($store['open_4'] < $now_time && $now_time < $store['close_4']){
                    $is_open = 1;
                }
                if ($store['open_5'] < $now_time && $now_time < $store['close_5']){
                    $is_open = 1;
                }
                if ($store['open_6'] < $now_time && $now_time < $store['close_6']){
                    $is_open = 1;
                }
                break;
            case 3 ://周三
                if ($store['open_7'] < $now_time && $now_time < $store['close_7']){
                    $is_open = 1;
                }
                if ($store['open_8'] < $now_time && $now_time < $store['close_8']){
                    $is_open = 1;
                }
                if ($store['open_9'] < $now_time && $now_time < $store['close_9']){
                    $is_open = 1;
                }
                break;
            case 4 :
                if ($store['open_10'] < $now_time && $now_time < $store['close_10']){
                    $is_open = 1;
                }
                if ($store['open_11'] < $now_time && $now_time < $store['close_11']){
                    $is_open = 1;
                }
                if ($store['open_12'] < $now_time && $now_time < $store['close_12']){
                    $is_open = 1;
                }
                break;
            case 5 :
                if ($store['open_13'] < $now_time && $now_time < $store['close_13']){
                    $is_open = 1;
                }
                if ($store['open_14'] < $now_time && $now_time < $store['close_14']){
                    $is_open = 1;
                }
                if ($store['open_15'] < $now_time && $now_time < $store['close_15']){
                    $is_open = 1;
                }
                break;
            case 6 :
                if ($store['open_16'] < $now_time && $now_time < $store['close_16']){
                    $is_open = 1;
                }
                if ($store['open_17'] < $now_time && $now_time < $store['close_17']){
                    $is_open = 1;
                }
                if ($store['open_18'] < $now_time && $now_time < $store['close_18']){
                    $is_open = 1;
                }
                break;
            case 0 :
                if ($store['open_19'] < $now_time && $now_time < $store['close_19']){
                    $is_open = 1;
                }
                if ($store['open_20'] < $now_time && $now_time < $store['close_20']){
                    $is_open = 1;
                }
                if ($store['open_21'] < $now_time && $now_time < $store['close_21']){
                    $is_open = 1;
                }
                break;
            default :
                $is_open = 0;
        }
        //garfunkel add
        if($store['store_is_close'] != 0){
            $is_open = 0;
        }
        //end  @wangchuanyuan

        if ($is_open == 0) {
            return array('error_code' => true, 'msg' => 'Store closed');
        }

        $store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
        if (empty($store) || empty($store_shop)) return array('error_code' => true, 'msg' => '');
        $store = array_merge($store, $store_shop);
        $mer_id = $store['mer_id'];
        $this->assign('store', $store);
        if ($order_data === null) {
            $productCart = json_decode(cookie('shop_cart_' . $store_id),true);
            if (empty($productCart)){
                $productCart = array();
                for($i=0;$i<20;$i++){
                    $tmpCookie = cookie('shop_cart_' . $store_id.'_'.$i);
                    if(!empty($tmpCookie)){
                        $tmpArr = json_decode($tmpCookie,true);
                        if(empty($tmpArr)){
                            $tmpArr = array();
                        }
                        $productCart = array_merge($productCart,$tmpArr);
                    }else{
                        break;
                    }
                }
                if(empty($productCart)){
                    redirect(U('Shop/index') . '#shop-' . $store_id);
                }
            }
        } else {
            $productCart = $order_data;
        }
        if (empty($productCart)) redirect(U('Shop/index') . '#shop-' . $store_id);


        $store_shop_level = !empty($store_shop['leveloff']) ? unserialize($store_shop['leveloff']) : '';//店铺设置的vip等级折扣率
        //用户的VIP折扣率
        $vip_discount = 100;
        if (!empty($this->user_level) && !empty($store_shop_level) && !empty($this->user_session) && isset($this->user_session['level'])) {
            if (isset($store_shop_level[$this->user_session['level']]) && isset($this->user_level[$this->user_session['level']])) {
                $level_off = $store_shop_level[$this->user_session['level']];
                if ($level_off['type'] == 1) {
                    $vip_discount = $level_off['vv'];
                }
            }
        }


        $goods = array();
        $price = 0;//原始总价
        $total = 0;//商品总数
        $extra_price = 0;//额外价格的总价
        $packing_charge = 0;//打包费
        //店铺优惠条件
        $sorts_discout = D('Shop_goods_sort')->get_sorts_discount($store_id);
        $store_discount_money = 0;//店铺折扣后的总价
        if ($order_data && is_array($order_data)) {
            foreach ($order_data as $row) {
                $goods_id = $row['goods_id'];
                $num = $row['num'];
                $t_return = D('Shop_goods')->check_stock($goods_id, $num, $row['spec_id'], $store_shop['stock_type'], $store_id);
                if ($t_return['status'] == 0) {
                    continue;
                } elseif ($t_return['status'] == 2) {
                    continue;
                }
                $total += $num;
                $price += $t_return['price'] * $num;
                $extra_price += $row['extra_price'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;


                //折扣($sorts_discout[$t_return['sort_id']]['discount_type'] == 1 ? '分类折扣' : '店铺折扣')
                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;

                //该商品的折扣类型 0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                $discount_type = 0;
                //折扣率 0：无折扣
                $discount_rate = 0;
                if ($t_discount < 100) {
                    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
                        $discount_type = 2;
                    } else {
                        $discount_type = 1;
                    }
                    $discount_rate = $t_discount;
                }

                $this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣后的总价
                $only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);//本商品的店铺折扣的单价
                if ($sorts_discout['discount_type'] == 0) {//折上折
                    if ($vip_discount < 100) {
                        $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                        $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                    }
                    $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);//本商品的VIP折扣后的总价
                    $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);//本商品的VIP折扣的单价
                } else {//折扣最优
                    $t_vip_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2);
                    if ($t_vip_price < $this_goods_total_price) {
                        $this_goods_total_price = $t_vip_price;

                        if ($vip_discount < 100) {
                            $discount_type = 3;
                            $discount_rate = $vip_discount;
                        }
                        $only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2);
                    }
                }

                $store_discount_money += $this_goods_total_price;//折扣后的商品总价（店铺，分类，VIP折扣都计算在内）

                $goods[] = array(
                    //modify garfunkel 判断语言
                    'name' => lang_substr($row['name'],C('DEFAULT_LANG')),
                    'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                    'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                    'discount_rate' => $discount_rate,//折扣率
                    'num' => $num,
                    'goods_id' => $goods_id,
                    'old_price' => floatval($t_return['old_price']),//商品原始价
                    'price' => floatval($t_return['price']),//
                    'discount_price' => floatval($only_discount_price),//折扣价
                    'cost_price' => floatval($t_return['cost_price']),
                    'number' => $t_return['number'],
                    'image' => $t_return['image'],
                    'sort_id' => $t_return['sort_id'],
                    'packing_charge' => $t_return['packing_charge'],
                    'unit' => $t_return['unit'],
                    'str' => $row['spec'],
                    'spec_id' => $row['spec_id'],
                    'extra_price' => $row['extra_price']
                );
            }
        } else {
            foreach ($productCart as $row) {
                $goods_id = $row['productId'];
                $num = $row['count'];
                $spec_ids = array();
                $str_s = array(); $str_p = array();
                foreach ($row['productParam'] as $r) {
                    if ($r['type'] == 'spec') {
                        $spec_ids[] = $r['id'];
                        $str_s[] = $r['name'];
                    } else {
                        foreach ($r['data'] as $d) {
                            $str_p[] = $d['name'];
                        }
                    }
                }
                $spec_str = $spec_ids ? implode('_', $spec_ids) : '';
                $t_return = D('Shop_goods')->check_stock($goods_id, $num, $spec_str, $store_shop['stock_type'], $store_id);
                if ($t_return['status'] == 0) {
                    $this->error_tips($t_return['msg']);
                    exit();
                } elseif ($t_return['status'] == 2) {
                    $this->error_tips($t_return['msg']);
                    exit();
                }
                $total += $num;
                $price += $t_return['price'] * $num;
                $extra_price += $row['productExtraPrice'] * $num;
                $packing_charge += $t_return['packing_charge'] * $num;

                $t_discount = isset($sorts_discout[$t_return['sort_id']]['discount']) && $sorts_discout[$t_return['sort_id']]['discount'] ? $sorts_discout[$t_return['sort_id']]['discount'] : 100;
// 				$store_discount_money += $num * round($t_return['price'] * $t_discount / 100, 2);
                $discount_type = 0;
                $discount_rate = 0;
                if ($t_discount < 100) {
                    if ($sorts_discout[$t_return['sort_id']]['discount_type']) {//分类折扣
                        $discount_type = 2;
                    } else {
                        $discount_type = 1;
                    }
                    $discount_rate = $t_discount;
                }
                // 				$store_discount_money += $num * round($t_return['price'] * $t_discount / 100, 2);


                $this_goods_total_price = $num * round($t_return['price'] * $t_discount * 0.01, 2);//本商品的折扣总价
                $only_discount_price = round($t_return['price'] * $t_discount * 0.01, 2);
                if ($sorts_discout['discount_type'] == 0) {//折上折
                    if ($vip_discount < 100) {
                        $discount_type = $discount_type == 2 ? 5 : ($discount_type == 1 ? 4 : 3);
                        $discount_rate = $discount_rate ? $discount_rate . ',' . $vip_discount : $vip_discount;
                    }
                    $this_goods_total_price = round($this_goods_total_price * $vip_discount * 0.01, 2);
                    $only_discount_price = round($only_discount_price * $vip_discount * 0.01, 2);
                } else {//折扣最优
                    $t_vip_price = $num * round($t_return['price'] * $vip_discount * 0.01, 2);
                    if ($t_vip_price < $this_goods_total_price) {
                        $this_goods_total_price = $t_vip_price;

                        if ($vip_discount < 100) {
                            $discount_type = 3;
                            $discount_rate = $vip_discount;
                        }
                        $only_discount_price = round($t_return['price'] * $vip_discount * 0.01, 2);
                    }
                }

                $store_discount_money += $this_goods_total_price;

                $str = '';
                $str_s && $str = implode(',', $str_s);
                $str_p && $str = $str ? $str . ';' . implode(',', $str_p) : implode(',', $str_p);
                $goods[] = array(
                    //modify garfunkel 判断语言
                    'name' => lang_substr($row['productName'],C('DEFAULT_LANG')),
                    'is_seckill_price' => $t_return['is_seckill_price'],//是否是秒杀价(0:否，1：是)
                    'discount_type' => $discount_type,//0:无折扣1：店铺折扣，2：分类折扣，3：VIP折扣，4:店铺+VIP折扣，5:分类+VIP折扣
                    'discount_rate' => $discount_rate,//折扣率
                    'num' => $num,
                    'goods_id' => $goods_id,
                    'old_price' => floatval($t_return['old_price']),//商品原始价
                    'price' => floatval($t_return['price']),//是秒杀的时候是秒杀价，不是的时候是原始价
                    'discount_price' => floatval($only_discount_price),//折扣价
                    'cost_price' => floatval($t_return['cost_price']),
                    'number' => $t_return['number'],
                    'image' => $t_return['image'],
                    'sort_id' => $t_return['sort_id'],
                    'packing_charge' => $t_return['packing_charge'],
                    'unit' => $t_return['unit'],
                    'str' => $str,
                    'spec_id' => $spec_str,
                    'extra_price'=>$row['productExtraPrice']
                );
            }
        }

        $minus_price = 0;
        //会员等级优惠  外卖费不参加优惠
        $vip_discount_money = round($store_discount_money, 2);

        $discounts = D('Shop_discount')->get_discount_byids(array($store_id));
        $discount_list = null;

        //优惠
        $sys_first_reduce = 0;//平台首单优惠
        $sto_first_reduce = 0;//店铺首单优惠
        $sys_full_reduce = 0;//平台满减
        $sto_full_reduce = 0;//店铺满减
        $shop_order_obj = D("Shop_order");

        $sys_count = $shop_order_obj->where(array('uid' => $this->user_session['uid']))->count();
        if (empty($sys_count)) {//平台首单优惠
            if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money)) {
                $d_tmp['discount_type'] = 1;//平台首单
                $d_tmp['money'] = $d_tmp['full_money'];
                $d_tmp['minus'] = $d_tmp['reduce_money'];
                $discount_list['system_newuser'] = $d_tmp;
// 				$discount_list[] = $d_tmp;
                $sys_first_reduce = $d_tmp['reduce_money'];
            }
        }


        if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money)) {
            $d_tmp['discount_type'] = 2;//平台满减
            $d_tmp['money'] = $d_tmp['full_money'];
            $d_tmp['minus'] = $d_tmp['reduce_money'];
            $discount_list['system_minus'] = $d_tmp;
// 			$discount_list[] = $d_tmp;
            $sys_full_reduce = $d_tmp['reduce_money'];
        }

        $sto_count = $shop_order_obj->where(array('uid' => $this->user_session['uid'], 'store_id' => $store_id))->count();
        $sto_first_reduce = 0;
        if (empty($sto_count)) {
            if ($d_tmp = $this->get_reduce($discounts, 0, $vip_discount_money, $store_id)) {
                $d_tmp['discount_type'] = 3;//店铺首单
                $d_tmp['money'] = $d_tmp['full_money'];
                $d_tmp['minus'] = $d_tmp['reduce_money'];
                $discount_list['newuser'] = $d_tmp;
// 				$discount_list[] = $d_tmp;
                $sto_first_reduce = $d_tmp['reduce_money'];
            }
        }
        $sto_full_reduce = 0;
        if ($d_tmp = $this->get_reduce($discounts, 1, $vip_discount_money, $store_id)) {
            $d_tmp['discount_type'] = 4;//店铺满减
            $d_tmp['money'] = $d_tmp['full_money'];
            $d_tmp['minus'] = $d_tmp['reduce_money'];
            $discount_list['minus'] = $d_tmp;
// 			$discount_list[] = $d_tmp;
            $sto_full_reduce = $d_tmp['reduce_money'];
        }

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
        //减免配送费的金额
// 		$delivery_fee_reduce2 = 0;

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
// 			$delivery_fee = $this->config['delivery_fee'];
            //使用平台的优惠（配送费的减免）
            if ($d_tmp = $this->get_reduce($discounts, 2, $price)) {
                $d_tmp['discount_type'] = 5;//平台配送费满减
                $d_tmp['money'] = $d_tmp['full_money'];
                $d_tmp['minus'] = $d_tmp['reduce_money'];
                $discount_list['delivery'] = $d_tmp;
                $delivery_fee_reduce = $d_tmp['reduce_money'];
            }
// 			$delivery_fee = $delivery_fee - $delivery_fee_reduce;
// 			$delivery_fee = $delivery_fee >= 0 ? $delivery_fee : 0;
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
// 			if ($store_shop['delivery_fee'] > 0) {//外卖费
// 				if ($store_shop['reach_delivery_fee_type'] == 1) {
// 					$delivery_fee = $store_shop['delivery_fee'];

// 				} else {
// 					if ($price < $store_shop['basic_price']) {//不足起送价
// 						if ($store_shop['delivery_fee_valid']) {
// 							$delivery_fee = $store_shop['delivery_fee'];
// 						}
// 					} else {
// 						if ($store_shop['reach_delivery_fee_type'] == 2 && $price < $store_shop['no_delivery_fee_value']) {
// 							$delivery_fee = $store_shop['delivery_fee'];
// 						}
// 					}
// 				}
// 			}
        }

        if (empty($goods)) {
            redirect(U('Shop/index') . '#shop-' . $store_id);
            return array('error_code' => true, 'msg' => '购物车是空的');
        } else {
            $data = array('error_code' => false);
            $data['total'] = $total;
            $data['price'] = $price;//商品实际总价
            $data['extra_price'] = $extra_price;//商品实际总价
            $data['discount_price'] = $vip_discount_money;//折扣后的总价
            $data['goods'] = $goods;
            $data['store_id'] = $store_id;
            $data['mer_id'] = $mer_id;
            $data['store'] = $store;
            $data['discount_list'] = $discount_list;

            $data['delivery_type'] = $store_shop['deliver_type'];

            $data['sys_first_reduce'] = $sys_first_reduce;//平台新单优惠的金额
            $data['sys_full_reduce'] = $sys_full_reduce;//平台满减优惠的金额
            $data['sto_first_reduce'] = $sto_first_reduce;//店铺新单优惠的金额
            $data['sto_full_reduce'] = $sto_full_reduce;//店铺满减优惠的金额

            $data['store_discount_money'] = $store_discount_money;//店铺折扣后的总价
            $data['vip_discount_money'] = $vip_discount_money;//VIP折扣后的总价
            $data['packing_charge'] = $packing_charge;//总的打包费

            $data['delivery_fee'] = $delivery_fee;//起步配送费
            $data['basic_distance'] = $basic_distance;//起步距离
            $data['per_km_price'] = $per_km_price;//超出起步距离部分的距离每公里的单价
            $data['delivery_fee_reduce'] = $delivery_fee_reduce;//配送费减免的金额

            $data['delivery_fee2'] = $delivery_fee2;//起步配送费
            $data['basic_distance2'] = $basic_distance2;//起步距离
            $data['per_km_price2'] = $per_km_price2;//超出起步距离部分的距离每公里的单价

            return $data;
        }
    }

    private function get_reduce($discounts, $type, $price, $store_id = 0)
    {
        $reduce_money = 0;
        $return = null;
        if (isset($discounts[$store_id])) {
            foreach ($discounts[$store_id] as $row) {
                if ($row['type'] == $type) {
                    if ($price >= $row['full_money']) {
                        if ($reduce_money < $row['reduce_money']) {
                            $reduce_money = $row['reduce_money'];
                            $return = $row;
                        }
                    }
                }
            }
        }
        return $return;
    }


    private function getCookieData($store_id)
    {
        $productCart = json_decode(cookie('shop_cart_' . $store_id), true);
        if (empty($productCart)) {
            $productCart = array();
            for ($i = 0; $i < 20; $i++) {
                $tmpCookie = cookie('shop_cart_' . $store_id . '_' . $i);
                if (!empty($tmpCookie)) {
                    $tmpArr = json_decode($tmpCookie, true);
                    if (empty($tmpArr)) {
                        $tmpArr = array();
                    }
                    $productCart = array_merge($productCart, $tmpArr);
                } else {
                    break;
                }
            }
        }

        return $productCart;
    }

    public function checkGoodsTime(){
        $store_id = isset($_POST['store_id']) ? intval($_POST['store_id']) : 0;
        $newCart = array();
        if($store_id != 0) {
            $productCart = $this->getCookieData($store_id);
            if(!empty($productCart)) {
                $store = D("Merchant_store")->field(true)->where(array('store_id' => $store_id))->find();
                if($store['menu_version'] == 1) {
                    $sortList = D('Shop_goods_sort')->lists($store_id, true);
                    $sortIdList = array();
                    foreach ($sortList as $k => $sl) {
                        $sortIdList[] = $sl['sort_id'];
                    }

                    foreach ($productCart as $product) {
                        $goodsId = $product['productId'];
                        $goods = D('Shop_goods')->where(array('goods_id' => $goodsId))->find();

                        if (in_array($goods['sort_id'], $sortIdList)) {
                            $newCart[] = $product;
                        }
                    }
                }else if($store['menu_version'] == 2){
                    $categories = D('StoreMenuV2')->getStoreCategories($store_id,true);
                    $products = D('StoreMenuV2')->getStoreProductAll($categories,$store_id);

                    $allProduct = array();
                    foreach ($products as $p){
                        $allProduct[] = $p['id'];
                    }

                    foreach ($productCart as $product) {
                        $goodsId = $product['productId'];

                        if (in_array($goodsId, $allProduct)) {
                            $newCart[] = $product;
                        }
                    }
                }
            }
        }

        $is_error = false;
        if(count($productCart) > count($newCart)){
            $is_error = true;
            $msg = "Please note that you have one or more item become unavailable at this time and will be removed from your cart. Do you confirm to continue checkout?";
        }else{
            $msg = "";
        }

        echo json_encode(array('error'=>$is_error,'msg'=>$msg,'cartList'=>$newCart));
    }

    public function confirm_order()
    {
// 		$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
// 		if ($order_id && ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid'])))) {
// 			$return = $this->check_cart($order['info']);
// 			$this->assign('order_id', $order_id);
// 		} else {
// 			$return = $this->check_cart();
// 		}
// 		if ($return['error_code']) $this->error_tips($return['msg']);

        //delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

        if ($order_id && ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid'])))) {
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $order['info'], 0);
            $this->assign('order_id', $order_id);
            //die("++++");
        } else {
            $cookieData = $this->getCookieData($store_id);

            if(empty($cookieData)) {
                redirect(U('Shop/index') . '#shop-' . $store_id);
                exit;
            }else{
                $store = D('Merchant_store')->where(array('store_id'=>$store_id))->find();
                //查看所有的cookie商品是否存在 如果不存在删除cookie
                if($store['menu_version'] == 2){
                    $all_id = array();
                    foreach ($cookieData as $pp){
                        if(!in_array($pp['productId'],$all_id)){
                            $all_id[] = $pp['productId'];
                        }

                        if(count($pp['productParam']) > 0){
                            foreach ($pp['productParam'] as $ppp){
                                $dish_id_list = explode(',',$ppp['dish_id']);
                                if($dish_id_list[0] != '' && !in_array($dish_id_list[0],$all_id)){
                                    $all_id[] = $dish_id_list[0];
                                }
                                if($dish_id_list[1] != '' && !in_array($dish_id_list[1],$all_id)){
                                    $all_id[] = $dish_id_list[1];
                                }
                            }
                        }
                    }

                    $all_product = D('Store_product')->where(array('id'=>array('in',$all_id),'storeId'=>$store_id))->group('id')->select();
                    //var_dump($all_id);
                    //var_dump(count($all_id).' ---- '.count($all_product));die();
                    if(count($all_id) != count($all_product)){
                        cookie('shop_cart_' . $store['store_id'], null);
                    }
                }
            }
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
            //var_dump($return);die("----");
        }

        if ($return['error_code']) $this->error_tips($return['msg'],U("Shop/classic_shop",array("shop_id"=>$store_id)));
        //add garfunkel
        if(isset($return['store']['name'])){
            $return['store']['name'] = lang_substr($return['store']['name'],C('DEFAULT_LANG'));
        }
        foreach ($return['goods'] as $k=>$v){
            $return['goods'][$k]['name'] = lang_substr($v['name'],C('DEFAULT_LANG'));
            $return['goods'][$k]['unit'] = lang_substr($v['unit'],C('DEFAULT_LANG'));

            $now_sort = D('Shop_goods_sort')->where(array('sort_id'=>$v['sort_id']))->find();
            $return['goods'][$k]['is_time'] = $now_sort['is_time'];
            if($now_sort['is_time'] == 1){
                $show_time = explode(',',$now_sort['show_time']);
                $return['goods'][$k]['begin_time'] = $show_time[0];
                $return['goods'][$k]['end_time'] = $show_time[1];
            }
        }
        //
        $village_id = isset($_GET['village_id']) ? intval($_GET['village_id']) : 0;
        $this->assign('village_id', $village_id);
        $is_own = 0;
        $merchant_ownpay = D('Merchant_ownpay')->field('mer_id',true)->where(array('mer_id' => $return['mer_id']))->find();
        foreach ($merchant_ownpay as $ownKey => $ownValue) {
            $ownValueArr = unserialize($ownValue);
            if($ownValueArr['open']){
// 				$is_own = 1;
            }
        }
        if ($is_own) {
            if ($return['delivery_type'] == 0) {
                $this->error_tips('商家配置的配送信息不正确');
            } elseif ($return['delivery_type'] == 3) {
                $return['delivery_type'] = 2;
            }
        }

        $basic_price = $return['price'];
        if($this->config['open_extra_price']>0){
            $basic_price += $return['extra_price'];
        }
        $return['price'] = round($return['vip_discount_money'] - round($return['sto_first_reduce'] + $return['sto_full_reduce'] + $return['sys_first_reduce'] + $return['sys_full_reduce'] + $return['packing_charge'], 2), 2);//实际要支付的价格

        $advance_day = $return['store']['advance_day'];
        $advance_day = empty($advance_day) ? 1 : $advance_day;

        if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
            $delivery_times = explode('-', $this->config['delivery_time']);
            $start_time = $delivery_times[0] . ':00';
            $stop_time = $delivery_times[1] . ':00';

            $delivery_times2 = explode('-', $this->config['delivery_time2']);
            $start_time2 = $delivery_times2[0] . ':00';
            $stop_time2 = $delivery_times2[1] . ':00';
        } else {
            $start_time = $return['store']['delivertime_start'];
            $stop_time = $return['store']['delivertime_stop'];

            $start_time2 = $return['store']['delivertime_start2'];
            $stop_time2 = $return['store']['delivertime_stop2'];
        }

        $have_two_time = 1;//是否两个时段 0：没有，1有

        $is_cross_day_1 = 0;//第一时间段是否跨天 0：不跨天，1：跨天
        $is_cross_day_2 = 0;//第二时间段是否跨天 0：不跨天，1：跨天

        $time = time() + $return['store']['send_time'] * 60;//默认的期望送达时间

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
        }
        $this->assign('have_two_time', $have_two_time);
        $this->assign('arrive_date', date('Y-m-d', $time));
        $this->assign('arrive_time', date('H:i', $time));
        $this->assign('now_time_value', $now_time_value);


        $date['minYear'] = date('Y', $time);
        $date['minMouth'] = date('n', $time) - 1;
        $date['minDay'] = date('j', $time);


        $date['minHour_today'] = date('G', $time);
        $date['minMinute_today'] = date('i', $time);

        $date['minHour_tomorrow'] = date('G', $start_time);
        $date['minMinute_tomorrow'] = date('i', $start_time);

        if ($time < $start_time2) {
            $date['minHour_today2'] = date('G', $start_time2);
            $date['minMinute_today2'] = date('i', $start_time2);
        } else {
            $date['minHour_today2'] = date('G', $time);
            $date['minMinute_today2'] = date('i', $time);
        }
        $date['minHour_tomorrow2'] = date('G', $start_time2);
        $date['minMinute_tomorrow2'] = date('i', $start_time2);

        $date['maxYear_today'] = date('Y', $stop_time);
        $date['maxMouth_today'] = date('n', $stop_time) - 1;
        $date['maxDay_today'] = date('j', $stop_time);

        $date['maxYear_today2'] = date('Y', $stop_time2);
        $date['maxMouth_today2'] = date('n', $stop_time2) - 1;
        $date['maxDay_today2'] = date('j', $stop_time2);


        $time = strtotime("+{$advance_day} day") + $return['store']['send_time'] * 60;
        $date['maxYear'] = date('Y', $time);
        $date['maxMouth'] = date('n', $time) - 1;
        $date['maxDay'] = date('j', $time);

        $date['maxHour'] = date('G', $stop_time);
        $date['maxMinute'] = date('i', $stop_time);

        $date['maxHour2'] = date('G', $stop_time2);
        $date['maxMinute2'] = date('i', $stop_time2);

        $date['today'] = date('Y-m-d');

        $date['is_cross_day_1'] = $is_cross_day_1;
        $date['is_cross_day_2'] = $is_cross_day_2;
        $this->assign($date);

        //是否达到起送价
        if ($return['store']['basic_price'] <= $basic_price) {
            $address_id = isset($_GET['adress_id']) ? intval($_GET['adress_id']) : cookie('userLocationId');
            if($address_id){
                D('User_adress')->where(array('adress_id'=>$address_id))->save(array('default'=>1));
                D('User_adress')->where(array('uid'=>$this->user_session['uid'],'adress_id'=>array('neq',$address_id)))->save(array('default'=>0));
//                if($_GET['current_id']){
//                    D('User_adress')->where(array('adress_id'=>$_GET['current_id']))->save(array('default'=>0));
//                }
            }
            //var_dump($this->user_session['uid']);die("------");
            $user_adress = D('User_adress')->get_one_adress($this->user_session['uid'], intval($address_id));
            //var_dump($user_adress);die("------");
        } else {
            if (in_array($return['delivery_type'], array(2, 3, 4))) {
                $return['delivery_type'] = 2;
            } else {
                $this->error_tips('没有达到起送价，不予以配送');
            }
        }

        //garfunkel获取减免配送费的活动
        $delivery_coupon = D('New_event')->getFreeDeliverCoupon($store_id,$return['store']['city_id']);

//            $store['free_delivery'] = 0;
//            $store['event'] = "";
//
//            if($delivery_coupon != "" && $delivery_coupon['limit_day']*1000 >= $store['distance']){
//                $store['free_delivery'] = 1;
//                $t_event['use_price'] = $delivery_coupon['use_price'];
//                $t_event['discount'] = $delivery_coupon['discount'];
//                $t_event['miles'] = $delivery_coupon['limit_day']*1000;
//
//                $store['event'] = $t_event;
//
//                //$temp['delivery_money'] =  $temp['delivery_money'] - $delivery_coupon['discount'];
//            }

        //计算配送费
        $is_jump_address = 0;
        if ($user_adress) {

            //$distance = getDistance($user_adress['latitude'], $user_adress['longitude'], $return['store']['lat'], $return['store']['long']);
            $from = $return['store']['lat'].','.$return['store']['long'];
            $aim = $user_adress['latitude'].','.$user_adress['longitude'];

            $distance = getDistance($return['store']['lat'],$return['store']['long'],$user_adress['latitude'],$user_adress['longitude']);
            //var_dump($distance);die("++++++++++++++==".$return['store']['delivery_radius'] * 1000);
            if($distance <= $return['store']['delivery_radius'] * 1000) {
                $return['store']['free_delivery'] = 0;
                $return['store']['event'] = "";
                if ($delivery_coupon != "" && $delivery_coupon['limit_day'] * 1000 >= $distance) {
                    $return['store']['free_delivery'] = 1;
                    $t_event['use_price'] = $delivery_coupon['use_price'];
                    $t_event['discount'] = $delivery_coupon['discount'];
                    $t_event['miles'] = $delivery_coupon['limit_day'] * 1000;
                    $t_event['desc'] = $delivery_coupon['desc'];
                    $t_event['event_type'] = $delivery_coupon['event_type'];

                    $return['store']['event'] = $t_event;
                } else {
                    $distance = getDistanceByGoogle($from, $aim);
                }
                //$distance = $distance / 1000;
                //var_dump($distance);die();

                //获取配送费用
//			$deliveryCfg = [];
//			$deliverys = D("Config")->get_gid_config(20);
//			foreach($deliverys as $r){
//				$deliveryCfg[$r['name']] = $r['value'];
//			}
//
//			if($distance < 5) {
//				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_1'], 2);
//			}elseif($distance > 5 && $distance <= 8) {
//				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_2'], 2);
//			}elseif($distance > 8 && $distance <= 10) {
//				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_3'], 2);
//			}elseif($distance > 10 && $distance <= 15) {
//				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_4'], 2);
//			}elseif($distance > 15 && $distance <= 20) {
//				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_5'], 2);
//			}elseif($distance > 20) {
//				$return['delivery_fee'] = round($deliveryCfg['delivery_distance_more'], 2);
//			}
                $return['delivery_fee'] = calculateDeliveryFee($distance, $return['store']['city_id']);
                $return['delivery_fee2'] = $return['delivery_fee'];

                /*$pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
                $return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
                $return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
                $return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;

                $pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
                $return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
                $return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
                $return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;*/
            }else{
                //超出了配送范围
                $is_jump_address = 1;
                $user_adress=null;
            }
        }else{
            //没有获得默认地址
            $is_jump_address = 1;
        }
        $this->assign('user_adress', $user_adress);
        //如果没有找到合适的配送地址
        if($is_jump_address == 1 && $_GET['from']=='shop'){
            $store = $return['store'];
            //redirect(U('My/adress',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'mer_id' => $store['mer_id'], 'frm' => $_GET['frm'], 'adress_id'=>$user_adress['adress_id'], 'order_id' => $order_id)));
            //die("redirect");
            redirect(U('My/adress',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'mer_id' => $store['mer_id'], 'frm' => $_GET['frm'], 'adress_id'=>0, 'order_id' => $order_id, 'from' => "shop")));
        }
        //计算打包费 add garfunkel
        $store_shop = D("Merchant_store_shop")->field(true)->where(array('store_id' => $store_id))->find();
        $this->assign('store_shop',$store_shop);
        //var_dump($return);
        //
        $pick_addr_id = isset($_GET['pick_addr_id']) ? $_GET['pick_addr_id'] : '';
        $pick_list = D('Pick_address')->get_pick_addr_by_merid($return['mer_id'], true);
        if ($pick_addr_id) {
            foreach ($pick_list as $k => $v) {
                if ($v['pick_addr_id'] == $pick_addr_id) {
                    $pick_address = $v;
                    break;
                }
            }
        } else {
            $pick_address = $pick_list[0];
        }
        //garfunkel店铺满减活动
        $eventList = D('New_event')->getEventList(1,4);
        $store_coupon = "";
        if(count($eventList) > 0) {
            $store_coupon = D('New_event_coupon')->where(array('event_id' => $eventList[0]['id'],'limit_day'=>$store_id))->order('use_price asc')->select();
            if($store_coupon && $store_coupon != ''){
                foreach ($store_coupon as $c){
                    if($return['vip_discount_money'] >= $c['use_price']){
                        $return['merchant_reduce'] = $c['discount'];
                    }
                }
            }
        }
        //garfunkel add
        //$tax = $return['store']['tax_num'] / 100 + 1;
        $return['tax_price'] = $return['tax_price'] + ($return['delivery_fee'] + $store_shop['pack_fee'])*$return['store']['tax_num'] / 100;
        //$return['price'] = ($return['price'] + $return['delivery_fee'] + $store_shop['pack_fee'])  * 1.05;//税费
        $return['price'] = $return['price'] + $return['delivery_fee'] + $store_shop['pack_fee'] + $return['tax_price'] + $return['deposit_price'];
        $return['price'] = sprintf("%.2f",$return['price']);
        $pick_address['distance'] = $this->wapFriendRange($pick_address['distance']);
        $this->assign('store_name',$return['store']['name']);

        $this->assign($return);
        //var_dump($return);
        $this->assign('pick_addr_id', $pick_addr_id);
        $this->assign('pick_address', $pick_address);

        $now_store_category_relation = M('Shop_category_relation')->where(array('store_id'=>$return['store_id']))->find();
        $now_store_category = M('Shop_category')->where(array('cat_id'=>$now_store_category_relation['cat_id']))->find();
        if($now_store_category['cue_field']){
            $this->assign('cue_field',unserialize($now_store_category['cue_field']));
        }
        if ($_GET['buy_type']=='shop'){
            $this->assign('back_url',U("Shop/classic_shop",array("shop_id"=>$_GET['store_id'])));
        }else{
            $this->assign('back_url',U("Shop/classic_shop",array("shop_id"=>$_GET['store_id'])));
        }
        $this->display();
    }


    public function save_order()
    {
        //delivery_type 0:平台配送，1：商家配送，2：自提，3:平台配送或自提，4：商家配送或自提
        $store_id = isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        //-------------------------------
        if ($order_id && ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid'])))) {
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $order['info'], 0);
        } else {
            $cookieData = $this->getCookieData($store_id);
            if(empty($cookieData)) {
                redirect(U('Shop/index') . '#shop-' . $store_id);
                exit;
            }
            $return = D('Shop_goods')->checkCart($store_id, $this->user_session['uid'], $cookieData);
        }

// 		$order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
// 		if ($order_id && ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid'])))) {
// 			$return = $this->check_cart($order['info']);
// 		} else {
// 			$return = $this->check_cart();
// 		}
        //---------------------------------
        if ($return['error_code']) $this->error_tips($return['msg']);
        if (IS_POST) {
            $village_id = isset($_REQUEST['village_id']) ? intval($_REQUEST['village_id']) : 0;
            $phone = isset($_POST['ouserTel']) ? htmlspecialchars($_POST['ouserTel']) : '';
            $name = isset($_POST['ouserName']) ? htmlspecialchars($_POST['ouserName']) : '';
            $address = isset($_POST['ouserAddres']) ? htmlspecialchars($_POST['ouserAddres']) : '';
            $pick_address = isset($_POST['pick_address']) ? htmlspecialchars($_POST['pick_address']) : '';
            $invoice_head = isset($_POST['invoice_head']) ? htmlspecialchars($_POST['invoice_head']) : '';
            $address_id = isset($_POST['address_id']) ? intval($_POST['address_id']) : 0;
            $pick_id = isset($_POST['pick_id']) ? htmlspecialchars($_POST['pick_id']) : 0;
            $pick_id = substr($pick_id, 1);
            $deliver_type = isset($_POST['deliver_type']) ? intval($_POST['deliver_type']) : 0;
            $arrive_time = isset($_POST['oarrivalTime']) ? htmlspecialchars($_POST['oarrivalTime']) : 0;
            $arrive_date = isset($_POST['oarrivalDate']) ? htmlspecialchars($_POST['oarrivalDate']) : 0;
            $note = isset($_POST['omark']) ? htmlspecialchars($_POST['omark']) : '';
            if ($return['price'] < $return['store']['basic_price']) {
                if (in_array($return['store']['deliver_type'], array(2, 3, 4))) {
                    $deliver_type = 1;
                } else {
                    $this->error_tips('订单没有达到起送价，不予配送');
                }
            }

            //garfunkel获取减 平台 和 店铺 免配送费的活动(只能选一个）
            $delivery_coupon = D('New_event')->getFreeDeliverCoupon($store_id, $return['store']['city_id']);

            //garfunkel 店铺满减 活动
            $eventList = D('New_event')->getEventList(1, 4);

            $store_coupon = "";
            if (count($eventList) > 0) {                                                                      //limit_day 就是 store_id
                $store_coupon = D('New_event_coupon')->where(array('event_id' => $eventList[0]['id'], 'limit_day' => $store_id))->order('use_price asc')->select();
            }
            /////
            if ($deliver_type != 1) {//配送方式是：非自提和非快递配送
                if (empty($name)) $this->error_tips('联系人不能为空');
                if (empty($phone)) $this->error_tips('联系电话不能为空');
// 				if ($return['delivery_type'] == 1 || $return['delivery_type'] == 4) {
                if ($user_address = D('User_adress')->field(true)->where(array('adress_id' => $address_id, 'uid' => $this->user_session['uid']))->find()) {
                    if ($user_address['longitude'] != 0 && $user_address['latitude'] != 0) {
                        if ($return['store']['delivery_range_type'] == 0) {
                            $from = $return['store']['lat'] . ',' . $return['store']['long'];
                            $aim = $user_address['latitude'] . ',' . $user_address['longitude'];
                            $distance = getDistance($return['store']['lat'], $return['store']['long'], $user_address['latitude'], $user_address['longitude']);
                            $return['store']['free_delivery'] = 0;
                            $return['store']['event'] = "";
                            if ($delivery_coupon != "" && $delivery_coupon['limit_day'] * 1000 >= $distance) {
                                $return['store']['free_delivery'] = 1;
                                $t_event['use_price'] = $delivery_coupon['use_price'];
                                $t_event['discount'] = $delivery_coupon['discount'];
                                $t_event['miles'] = $delivery_coupon['limit_day'] * 1000;
                                $t_event['type'] = $delivery_coupon['type'];
                                $t_event['desc'] = $delivery_coupon['desc'];
                                $t_event['event_type'] = $delivery_coupon['event_type'];

                                $return['store']['event'] = $t_event;
                                //$distance = $distance/1000;
                            }
                            //}else {
                            $distance = getDistanceByGoogle($from, $aim);
                            //}

                            $delivery_radius = $return['store']['delivery_radius'] * 1000;
                            if ($distance > $delivery_radius && $return['delivery_type'] != 5) {
                                //$this->error_tips('您到本店的距离是' . $distance . '米,超过了' . $delivery_radius . '米的配送范围');
                            }
                        } else {
                            if ($return['store']['delivery_range_polygon']) {
                                if (!isPtInPoly($user_address['longitude'], $user_address['latitude'], $return['store']['delivery_range_polygon'])) {
                                    $this->error_tips('您的地址不在本店指定的配送区域');
                                }
                            } else {
                                $this->error_tips('您的地址不在本店指定的配送区域');
                            }
                        }
                    } else {
                        $this->error_tips('您选择的地址没有完善，请先编辑地址，点击“点击选择位置”进行完善', U('My/adress', array('buy_type' => 'shop', 'store_id' => $return['store_id'], 'village_id' => $village_id, 'mer_id' => $return['mer_id'], 'current_id' => $user_adress['adress_id'])));
                    }
                } else {
                    $this->error_tips('地址信息不存在');
                }
// 				}
            }
            //var_dump($return['store']);die();

            $area = D('Area')->where(array('area_id' => $return['store']['city_id']))->find();
            $now_time = time() + $area['jetlag'] * 3600;
            $order_data = array();
            $order_data['mer_id'] = $return['mer_id'];
            $order_data['store_id'] = $return['store_id'];
            $order_data['uid'] = $this->user_session['uid'];

            $order_data['desc'] = $note;
            $order_data['create_time'] = $now_time;
            $order_data['last_time'] = $now_time;
            $order_data['invoice_head'] = $invoice_head;
            $order_data['village_id'] = $village_id;

            $order_data['num'] = $return['total'];
            //modify garfunkel
            $order_data['packing_charge'] = $return['store']['pack_fee'];//打包费
            //$order_data['packing_charge'] = $return['packing_charge'];//打包费

            $order_data['merchant_reduce'] = $return['sto_first_reduce'] + $return['sto_full_reduce'];//店铺优惠
            $order_data['balance_reduce'] = $return['sys_first_reduce'] + $return['sys_full_reduce'];//平台优惠

            $orderid = date('ymdhis') . substr(microtime(), 2, 8 - strlen($this->user_session['uid'])) . $this->user_session['uid'];
            $order_data['real_orderid'] = $orderid;
            $order_data['no_bill_money'] = 0;//无需跟平台对账的金额

            if ($deliver_type == 1) {//自提
                if (empty($pick_id)) $this->error_tips('请选择自提点');
                $order_data['is_pick_in_store'] = 2;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                $delivery_fee = $order_data['freight_charge'] = 0;//运费
                $order_data['username'] = isset($this->user_session['nickname']) && $this->user_session['nickname'] ? $this->user_session['nickname'] : '';
                $order_data['userphone'] = isset($this->user_session['phone']) && $this->user_session['phone'] ? $this->user_session['phone'] : '';
                $order_data['address'] = $pick_address;
                $order_data['address_id'] = 0;
                $order_data['pick_id'] = $pick_id;
                $order_data['status'] = 7;
                $order_data['expect_use_time'] = time() + $return['store']['send_time'] * 60;//客户期望使用时间
            } else {//配送
                $order_data['username'] = $name;
                $order_data['userphone'] = $phone;
                $order_data['address'] = $address;
                $order_data['address_id'] = $address_id;
                $order_data['lat'] = $user_address['latitude'];
                $order_data['lng'] = $user_address['longitude'];
                if ($arrive_date == 0) {
                    $arrive_date = date('Y-m-d');
                }
                if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {
                    $delivery_times = explode('-', $this->config['delivery_time']);
                    $start_time = $delivery_times[0] . ':00';
                    $stop_time = $delivery_times[1] . ':00';

                    $delivery_times2 = explode('-', $this->config['delivery_time2']);
                    $start_time2 = $delivery_times2[0] . ':00';
                    $stop_time2 = $delivery_times2[1] . ':00';
                } else {
                    $start_time = $return['store']['delivertime_start'];
                    $stop_time = $return['store']['delivertime_stop'];

                    $start_time2 = $return['store']['delivertime_start2'];
                    $stop_time2 = $return['store']['delivertime_stop2'];
                }

                if ($start_time == $stop_time && $start_time == '00:00:00') {
                    $stop_time = '23:59:59';
                }
                $if_start_time = strtotime(date('Y-m-d ') . $start_time);
                $if_stop_time = strtotime(date('Y-m-d ') . $stop_time);

                if ($if_start_time > $if_stop_time) {
                    $if_stop_time = $if_stop_time + 86400;
                }

                $if_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
                $if_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
                if ($if_start_time2 > $if_stop_time2) {
                    $if_stop_time2 = $if_stop_time2 + 86400;
                }

                if ($arrive_time == 0) {
                    if ($arrive_date != date('Y-m-d')) {
                        $arrive_time = strtotime($arrive_date . $start_time);
                    } else {
                        $arrive_time = $now_time + $return['store']['send_time'] * 60;
                        if ($start_time == $stop_time && $start_time == '00:00:00') {

                        } else {
                            $_start_time = strtotime(date('Y-m-d ') . $start_time);
                            $_stop_time = strtotime(date('Y-m-d ') . $stop_time);
                            if ($_start_time > $_stop_time) {
                                $_stop_time = $_stop_time + 86400;
                            }
                            if ($arrive_time < $_start_time) {
                                $arrive_time = $_start_time;
                            } elseif ($_start_time <= $arrive_time && $arrive_time <= $_stop_time) {

                            } else {
                                $_start_time2 = strtotime(date('Y-m-d ') . $start_time2);
                                $_stop_time2 = strtotime(date('Y-m-d ') . $stop_time2);
                                if ($_start_time2 > $_stop_time2) {
                                    $_stop_time2 = $_stop_time2 + 86400;
                                }
                                if ($arrive_time < $_start_time2) {
                                    $arrive_time = $_start_time2;
                                } elseif ($_start_time2 <= $arrive_time && $arrive_time <= $_stop_time2) {

                                } else {
                                    $arrive_time = $_start_time + 86400;
                                }
                            }
                        }
                    }
                } else {
                    $arrive_time = strtotime($arrive_date . $arrive_time);
                }
                if ($arrive_time)
                    $arrive_time = $arrive_time + $area['jetlag'] * 3600;

                $order_data['expect_use_time'] = $arrive_time ? $arrive_time : $now_time + $return['store']['send_time'] * 60;//客户期望使用时间

                //计算配送费
                //$distance = $distance / 1000;
                //获取配送费用
//				$deliveryCfg = [];
//				$deliverys = D("Config")->get_gid_config(20);
//				foreach($deliverys as $r){
//					$deliveryCfg[$r['name']] = $r['value'];
//				}
//
//				if($distance < 5) {
//					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_1'], 2);
//				}elseif($distance > 5 && $distance <= 8) {
//					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_2'], 2);
//				}elseif($distance > 8 && $distance <= 10) {
//					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_3'], 2);
//				}elseif($distance > 10 && $distance <= 15) {
//					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_4'], 2);
//				}elseif($distance > 15 && $distance <= 20) {
//					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_5'], 2);
//				}elseif($distance > 20) {
//					$return['delivery_fee'] = round($deliveryCfg['delivery_distance_more'], 2);
//				}

                $return['delivery_fee'] = calculateDeliveryFee($distance, $return['store']['city_id']);
                $return['delivery_fee2'] = $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];
                $order_data['merchant_reduce']=0;
                $order_data['delivery_discount']=0;
                //garfunkel 如果存在减免配送费的活动
                if ($return['store']['event']) {
                    if ($return['price'] >= $return['store']['event']['use_price']) {
                        //如果优惠金额大于配送费
                        if ($return['delivery_fee'] < $return['store']['event']['discount'])
                            $order_data['delivery_discount'] = $return['delivery_fee'];
                        else
                            $order_data['delivery_discount'] = $return['store']['event']['discount'];

                        //是否可与优惠券公用 0不可 1可以
                        $order_data['delivery_discount_type'] = $return['store']['event']['type'];
                        //平台活动还是店铺活动 0平台 1店铺活动的id
                        $order_data['delivery_discount_event'] = $return['store']['event']['event_type'];
                    }
                }
                //garfunke 店铺满减活动
                if ($store_coupon && $store_coupon != '') {
                    foreach ($store_coupon as $c) {
                        if ($return['price'] >= $c['use_price']) {
                            $order_data['merchant_reduce'] = $c['discount'];
                            $order_data['merchant_reduce_type'] = $c['type'];
                        }
                    }
                }
                // 缓存
                $order_data['merchant_reduce_save'] = $order_data['merchant_reduce'];
                $order_data['delivery_discount_save'] = $order_data['delivery_discount'];
                /*
                if ($return['delivery_type'] == 5) {//快递配送
                    $pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
                    $return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
                    $return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
                    $return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
                    $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
                } else {
                    $expect_use_time_temp = strtotime(date('Y-m-d ') . date('H:i:s', $order_data['expect_use_time']));
                    if ($if_start_time <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time) {//时间段一
                        $pass_distance = $distance > $return['basic_distance'] ? floatval($distance - $return['basic_distance']) : 0;
                        $return['delivery_fee'] += round($pass_distance * $return['per_km_price'], 2);
                        $return['delivery_fee'] = $return['delivery_fee'] - $return['delivery_fee_reduce'];
                        $return['delivery_fee'] = $return['delivery_fee'] > 0 ? $return['delivery_fee'] : 0;
                        $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee'];//运费
                    } elseif ($if_start_time2 <= $expect_use_time_temp && $expect_use_time_temp <= $if_stop_time2) {//时间段二
                        $pass_distance = $distance > $return['basic_distance2'] ? floatval($distance - $return['basic_distance2']) : 0;
                        $return['delivery_fee2'] += round($pass_distance * $return['per_km_price2'], 2);
                        $return['delivery_fee2'] = $return['delivery_fee2'] - $return['delivery_fee_reduce'];
                        $return['delivery_fee2'] = $return['delivery_fee2'] > 0 ? $return['delivery_fee2'] : 0;
                        $delivery_fee = $order_data['freight_charge'] = $return['delivery_fee2'];//运费
                    } else {
                        $this->error_tips('您选择的时间不在配送时间段内！');
                    }
                }*/
                if ($return['delivery_type'] == 0 || $return['delivery_type'] == 3) {//平台配送
                    $order_data['is_pick_in_store'] = 0;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                    $order_data['no_bill_money'] = $delivery_fee;
                } elseif ($return['delivery_type'] == 1 || $return['delivery_type'] == 4) {
                    $order_data['is_pick_in_store'] = 1;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                } else {
                    $order_data['is_pick_in_store'] = 3;//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                }
            }

            //判断期望时间是否在营业时间内
// 			if ($order_data['expect_use_time']) {
// 				$now_time = date('H:i:s', $order_data['expect_use_time']);
// 				$is_close = 1;
// 				if ($return['store']['open_1'] == '00:00:00' && $return['store']['close_1'] == '00:00:00') {
// 				} else {
// 					if ($return['store']['open_1'] < $now_time && $now_time < $return['store']['close_1']) {
// 						$is_close = 0;
// 					}
// 					if ($return['store']['open_2'] != '00:00:00' && $return['store']['close_2'] != '00:00:00') {
// 						if ($return['store']['open_2'] < $now_time && $now_time < $return['store']['close_2']) {
// 							$is_close = 0;
// 						}
// 					}
// 					if ($return['store']['open_3'] != '00:00:00' && $return['store']['close_3'] != '00:00:00') {
// 						if ($return['store']['open_3'] < $now_time && $now_time < $return['store']['close_3']) {
// 							$is_close = 0;
// 						}
// 					}
// 				}
// 				if ($is_close) {
// 					$this->error_tips('期望到货时间不在服务时间内，不能下单！');
// 					exit;
// 				}
// 			}

            $order_data['goods_price'] = $return['price'];//商品的价格
            //garfunkel 计算服务费
            $order_data['service_fee'] = number_format($order_data['goods_price'] * $return['store']['service_fee'] / 100, 2);
            $order_data['extra_price'] = $return['extra_price'];//另外要支付的金额
            $order_data['discount_price'] = $return['vip_discount_money'];//商品折扣后的总价
            //modify garfunkel
            //$order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['store']['pack_fee'];//订单总价  商品价格+打包费+配送费
            //$order_data['total_price'] = ($return['price'] * 1.05) + $delivery_fee + $return['packing_charge'];//订单总价  商品价格+打包费+配送费
            //$order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['store']['pack_fee'];//实际要支付的价格
            //$order_data['price'] = $order_data['discount_price'] - $order_data['merchant_reduce'] - $order_data['balance_reduce'] + $delivery_fee + $return['packing_charge'];//实际要支付的价格
            //$tax = $return['store']['tax_num']/100 + 1;
            //$order_data['price'] = $order_data['price'] * $tax;
            //$order_data['price'] = $order_data['price'] * 1.05; //税费

            $return['tax_price'] = $return['tax_price'] + ($delivery_fee + $return['store']['pack_fee']) * $return['store']['tax_num'] / 100;
            $order_data['price'] = $return['price'] + $return['delivery_fee'] + $return['store']['pack_fee'] + $return['tax_price'] + $return['deposit_price'] + $order_data['service_fee'];
            $order_data['total_price'] = $order_data['price'];

            $order_data['discount_detail'] = $return['discount_list'] ? serialize($return['discount_list']) : '';//优惠详情
            //var_dump($order_data);die();
// 			if ($return['price'] - $return['store_discount_money'] > 0) {
// 				$order_data['discount_detail'] = '店铺折扣优惠：' . floatval($return['price'] - $return['store_discount_money']);
// 			}
// 			if ($return['store_discount_money'] - $return['vip_discount_money'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']) : 'VIP优惠：' . floatval($return['store_discount_money'] - $return['vip_discount_money']);
// 			}
// 			if ($return['sys_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台首单减：' . $return['sys_first_reduce'] : '平台首单减：' . $return['sys_first_reduce'];
// 			}
// 			if ($return['sys_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';平台满减：' . $return['sys_full_reduce'] : '平台满减：' . $return['sys_full_reduce'];
// 			}
// 			if ($return['sto_first_reduce']> 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺首单减：' . $return['sto_first_reduce'] : '店铺首单减：' . $return['sto_first_reduce'];
// 			}
// 			if ($return['sto_full_reduce'] > 0) {
// 				$order_data['discount_detail'] = $order_data['discount_detail'] ? $order_data['discount_detail'] . ';店铺满减：' . $return['sto_full_reduce'] : '店铺满减：' . $return['sto_full_reduce'];
// 			}


            $order_data['reduce_stock_type'] = $return['store']['reduce_stock_type'];//'减库存类型（0：支付后，1：下单后）'

            //自定义字段
            if ($_POST['cue_field']) {
                $order_data['cue_field'] = serialize($_POST['cue_field']);
            }
            $order_data['is_mobile_pay'] = 1;
            //var_dump($order_data);die();
            if ($order_id = D('Shop_order')->saveOrder($order_data, $return, $this->user_session)) {
                /* 粉丝行为分析 */
                $this->behavior(array('mer_id' => $return['mer_id'], 'biz_id' => $order_id));
                redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'shop')));
// 			} elseif (false && ($order_id = D('Shop_order')->add($order_data))) {
// 				D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 0));
// 				if ($order_data['is_pick_in_store'] == 2 && $order_data['status'] == 7) {
// 					D('Pick_order')->add(array('store_id' => $order_data['store_id'], 'order_id' => $order_id, 'pick_id' => $pick_id, 'status' => 0, 'dateline' => time()));
// 					//D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 11, 'name' => $this->staff_session['name'], 'phone' => $this->store['phone']));//分配到自提点
// 				}
// 				$detail_obj = D('Shop_order_detail');
// 				$goods_obj = D("Shop_goods");
// 				foreach ($return['goods'] as $grow) {
// 					$detail_data = array('store_id' => $return['store_id'], 'order_id' => $order_id, 'number' => isset($grow['number']) && $grow['number'] ? $grow['number'] : '', 'cost_price' => $grow['cost_price'], 'unit' => $grow['unit'], 'goods_id' => $grow['goods_id'], 'name' => $grow['name'], 'price' => $grow['price'], 'num' => $grow['num'], 'spec' => $grow['str'], 'spec_id' => $grow['spec_id'], 'create_time' => time(),'extra_price'=>$grow['extra_price']);
// 					$detail_data['is_seckill'] = intval($grow['is_seckill_price']);
// 					$detail_data['discount_type'] = intval($grow['discount_type']);
// 					$detail_data['discount_rate'] = $grow['discount_rate'];
// 					$detail_data['sort_id'] = $grow['sort_id'];
// 					$detail_data['old_price'] = floatval($grow['old_price']);
// 					$detail_data['discount_price'] = floatval($grow['discount_price']);
// 					D('Shop_order_detail')->add($detail_data);
// 					$order_data['reduce_stock_type'] && $goods_obj->update_stock($grow);//修改库存
// 				}

// 				if ($this->user_session['openid']) {
// 					$keyword2 = '';
// 					$pre = '';
// 					foreach ($return['goods'] as $menu) {
// 						$keyword2 .= $pre . $menu['name'] . ':' . $menu['price'] . '*' . $menu['num'];
// 						$pre = '\n\t\t\t';
// 					}
// 					$href = C('config.site_url').'/wap.php?c=Shop&a=status&order_id='. $order_id . '&mer_id=' . $order_data['mer_id'] . '&store_id=' . $order_data['store_id'];
// 					$model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
// 					$model->sendTempMsg('OPENTM201682460', array('href' => $href, 'wecha_id' => $this->user_session['openid'], 'first' => '您好，您的订单已生成', 'keyword3' => $orderid, 'keyword1' => date('Y-m-d H:i:s'), 'keyword2' => $keyword2, 'remark' => '您的该次'.$this->config['shop_alias_name'].'下单成功，感谢您的使用！'));
// 				}

// 				$msg = ArrayToStr::array_to_str($order_id, 'shop_order');
// 				$op = new orderPrint($this->config['print_server_key'], $this->config['print_server_topdomain']);
// 				$op->printit($return['mer_id'], $return['store_id'], $msg, 0);

// 				$str_format = ArrayToStr::print_format($order_id, 'shop_order');
// 				foreach ($str_format as $print_id => $print_msg) {
// 					$print_id && $op->printit($return['mer_id'], $return['store_id'], $print_msg, 0, $print_id);
// 				}


// 				$sms_data = array('mer_id' => $return['mer_id'], 'store_id' => $return['store_id'], 'type' => 'shop');
// 				if ($this->config['sms_shop_place_order'] == 1 || $this->config['sms_shop_place_order'] == 3) {
// 					$sms_data['uid'] = $this->user_session['uid'];
// 					$sms_data['mobile'] = $order_data['userphone'];
// 					$sms_data['sendto'] = 'user';
// 					$sms_data['content'] = '您' . date("H时i分") . '在【' . $return['store']['name'] . '】中下了一个订单，订单号：' . $orderid;
// 					Sms::sendSms($sms_data);
// 				}
// 				if ($this->config['sms_shop_place_order'] == 2 || $this->config['sms_shop_place_order'] == 3) {
// 					$sms_data['uid'] = 0;
// 					$sms_data['mobile'] = $return['store']['phone'];
// 					$sms_data['sendto'] = 'merchant';
// 					$sms_data['content'] = '顾客【' . $order_data['username'] . '】在' . date("Y-m-d H:i:s") . '时下了一个订单，订单号：' . $orderid . '请您注意查看并处理!';
// 					Sms::sendSms($sms_data);
// 				}

// 				/* 粉丝行为分析 */
// 				$this->behavior(array('mer_id' => $return['mer_id'], 'biz_id' => $order_id));

// 				redirect(U('Pay/check', array('order_id' => $order_id, 'type' => 'shop')));
            } else {
                $this->error_tips('订单保存失败....');
            }
        } else {
            $this->error_tips('不合法的提交');
        }

    }

    public function order_detail_old()
    {
        $this->isLogin();
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
        $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
        $shop = D('Merchant_store_shop')->field(true)->where(array('store_id' => $order['store_id']))->find();
        cookie('shop_cart_' . $store['store_id'], null);
        for($i=0;$i<20;$i++){
            if(cookie('shop_cart_' . $store['store_id'].'_'.$i)){
                cookie('shop_cart_' . $store['store_id'].'_'.$i,null);
            }else{
                break;
            }
        }
        $pick_address = M('Pick_address')->where(array('id'=>$order['pick_id']))->find();
        $order['pick_lat'] = $pick_address['lat'];
        $order['pick_lng'] = $pick_address['long'];
        $lng_lat = D('User_long_lat')->getLocation($_SESSION['openid'], 0);
        $now_merchant = D('Merchant')->get_info($order['mer_id']);

        $this->assign('now_merchant',$now_merchant );

        $this->assign('lat',$lng_lat['lat']);
        $this->assign('lng',$lng_lat['long']);
        $this->assign('store', array_merge($store, $shop));
        $this->assign('order', $order);
        $this->display();
    }


    public function orderdel()
    {
        $this->isLogin();
        $id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        if ($order = M('Shop_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid']))->find()) {
// 			if ($order['status'] != 0 ) $this->error_tips('商家已经处理了此订单，现在不能取消了！');
            if ($order['paid'] == 1 ) $this->error_tips('该订单已支付，您不能取消！');
// 			if ($order['paid'] == 1 && date('m', $order['dateline']) == date('m')) {
// 				foreach (unserialize($order['info']) as $menu) {
// 					D('Meal')->where(array('meal_id' => $menu['id'], 'sell_count' => array('gt', $menu['num'])))->setDec('sell_count', $menu['num']);
// 				}
// 			}
// 			D("Merchant_store_meal")->where(array('store_id' => $order['store_id']))->setDec('sale_count', 1);
            /* 粉丝行为分析 */
            $this->behavior(array('mer_id' => $order['mer_id'], 'biz_id' => $order['store_id']));

            M('Shop_order')->where(array('order_id' => $id, 'uid' => $this->user_session['uid']))->save(array('status' => 5, 'is_rollback' => 1));//取消未支付的订单
            D('Shop_order_log')->add_log(array('order_id' => $id, 'status' => 10));

            if (($order['paid'] == 1 || $order['reduce_stock_type'] == 1) && $order['is_rollback'] == 0) {
                $details = D('Shop_order_detail')->field(true)->where(array('order_id' => $order['order_id']))->select();
                $goods_db = D("Shop_goods");
                foreach ($details as $menu) {
                    $goods_db->update_stock($menu, 1);//修改库存
                }
            }


            $this->success_tips(L('_B_MY_ORDERCANCELLEDACCESS_'), U('Shop/status', array('mer_id' => $order['mer_id'], 'store_id' => $order['store_id'], 'order_id' => $order['order_id'])));
        } else {
            $this->error_tips(L('_B_MY_ORDERCANCELLEDACCESS_FAIL'));
        }

    }

    /**
     * 订单状态列表
     */
    public function status()
    {
        redirect(U("Shop/order_detail",array("order_id"=>$_GET['order_id'])));

        //header('Location: http://' . $this->config['many_city_main_domain'] . '.' .);
        die();
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

        if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))) {
            //if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id))) {
            $storeName = D("Merchant_store")->field('`name`, `phone`')->where(array('store_id' => $order['store_id']))->find();
            //modify garfunkel
            $storeName['name'] = lang_substr($storeName['name'],C('DEFAULT_LANG'));
            $this->assign('storeName', $storeName);
            cookie('shop_cart_' . $order['store_id'], null);
            for($i=0;$i<20;$i++){
                if(cookie('shop_cart_' . $order['store_id'].'_'.$i)){
                    cookie('shop_cart_' . $order['store_id'].'_'.$i,null);
                }else{
                    break;
                }
            }
            $status = D('Shop_order_log')->field(true)->where(array('order_id' => $order_id))->order('id DESC')->select();
            $statusCount = D("Shop_order_log")->where(array('order_id' => $order_id))->count();
            $this->assign('statusCount', $statusCount);
            //配送员轨迹
            if (in_array($order['order_status'], array(1, 5))) {
                $supply = D("Deliver_supply")->where(array('order_id'=>$_GET['order_id']))->find();
                $start_time = $supply['start_time'];
                $end_time = $supply['end_time']? $supply['end_time']: time();
                $where = array();
                $where['uid'] = $supply['uid'];
                $where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
                $lines = D("Deliver_user_location_log")->where($where)->order("`create_time` ASC")->select();
                $points = array();
                $points['from_site'] = array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']);
                $points['aim_site'] = array('lng'=>$supply['aim_lnt'], 'lat'=>$supply['aim_lat']);
                $this->assign('supply', $supply);
                $this->assign('lines', $lines);
                if ($lines) {
                    $this->assign('center', array_pop($lines));
                } else {
                    $this->assign('center', array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']));
                }
                $this->assign('point', $points);
            }
            $this->assign('status', $status);
            $this->assign('order_id', $order_id);
            $this->assign('order', $order);
            $this->assign('redirect_url',"./wap.php?g=Wap&c=Shop&a=order_detail&order_id="+$order_id);
            $this->display();
        } else {
            //$this->error_tips('错误的订单信息！');
            echo "错误的订单信息！";
        }
    }

    /**
     * 支付结果页
     */
    public function pay_result()
    {
        $status=isset($_GET['status']) ? intval($_GET['status']) : -1;  //未获得支付状态信息     1 0  支付成功，失败   10 充值成功  9 充值失败
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $mer_id =isset($_GET['mer_id']) ? intval($_GET['mer_id']) : 0;
        $store_id=isset($_GET['store_id']) ? intval($_GET['store_id']) : 0;

        if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))) {
            //if ($order = D('Shop_order')->get_order_detail(array('order_id' => $order_id))) {
            //$storeName = D("Merchant_store")->field('`name`, `phone`')->where(array('store_id' => $order['store_id']))->find();
            $store = D("Merchant_store")->where(array('store_id' => $order['store_id']))->find();
            //modify garfunkel
            $store['name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
            $this->assign('store', $store);

            //清空购物车
            cookie('shop_cart_' . $order['store_id'], null);
            for($i=0;$i<20;$i++){
                if(cookie('shop_cart_' . $order['store_id'].'_'.$i)){
                    cookie('shop_cart_' . $order['store_id'].'_'.$i,null);
                }else{
                    break;
                }
            }
//            $status = D('Shop_order_log')->field(true)->where(array('order_id' => $order_id))->order('id DESC')->select();
//            $statusCount = D("Shop_order_log")->where(array('order_id' => $order_id))->count();
//            $this->assign('statusCount', $statusCount);
//            //配送员轨迹
//            if (in_array($order['order_status'], array(1, 5))) {
//                $supply = D("Deliver_supply")->where(array('order_id'=>$_GET['order_id']))->find();
//                $start_time = $supply['start_time'];
//                $end_time = $supply['end_time']? $supply['end_time']: time();
//                $where = array();
//                $where['uid'] = $supply['uid'];
//                $where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
//                $lines = D("Deliver_user_location_log")->where($where)->order("`create_time` ASC")->select();
//                $points = array();
//                $points['from_site'] = array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']);
//                $points['aim_site'] = array('lng'=>$supply['aim_lnt'], 'lat'=>$supply['aim_lat']);
//                $this->assign('supply', $supply);
//                $this->assign('lines', $lines);
//                if ($lines) {
//                    $this->assign('center', array_pop($lines));
//                } else {
//                    $this->assign('center', array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']));
//                }
//                $this->assign('point', $points);
//            }
            $city = D('Area')->where(array('area_id'=>$store['city_id']))->find();
            $order['jetlag'] = $city['jetlag'];
            //var_dump($store);
            //echo "<hr>";
            //var_dump($order);
            $this->assign('back_url','./wap.php?g=Wap&c=My&a=shop_order_list');
            $this->assign('order_id', $order_id);
            $this->assign('order', $order);
            $this->assign('status', $status);
            $this->assign('error', 0);
            $this->display();
        } else {
            //$this->error_tips('错误的订单信息！');
            //echo "错误的订单信息！";
            $this->assign('error', 1);
            $this->display();
        }
    }

    public function map()
    {
        $order_id = I("order_id", 0, 'intval');
        if (! $order_id) {
            $this->error_tips("OrderId不能为空");
        }
        $supply = D("Deliver_supply")->where(array('order_id'=>$order_id))->find();
        if (! $supply) {
            $this->error_tips("配送源不存在");
        }
        if (! $supply['uid']) {
            $this->error_tips("订单还没有分配配送员");
        }
        $start_time = $supply['start_time'];
        if (!$start_time) {
            $this->error_tips("配送员还没有开始配送");
        }
        $end_time = $supply['end_time']? $supply['end_time']: time();
        $where = array();
        $where['uid'] = $supply['uid'];
        $where['create_time'] = array(array('gt', $start_time), array('lt', $end_time));
        $lines = D("Deliver_user_location_log")->where($where)->order("`create_time` ASC")->select();

        $points = array();
        $points['from_site'] = array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']);
        $points['aim_site'] = array('lng'=>$supply['aim_lnt'], 'lat'=>$supply['aim_lat']);

        $this->assign('supply', $supply);
        $this->assign('lines', $lines);
        if ($lines) {
            $this->assign('center', array_pop($lines));
        } else {
            $this->assign('center', array('lng'=>$supply['from_lnt'], 'lat'=>$supply['from_lat']));
        }
        $this->assign('point', $points);
        $this->assign('order_id', $order_id);

        $this->display('map');
    }

    public function orderstatus()
    {
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        if ($order = M('Shop_order')->where(array('order_id' => $order_id, 'uid' => $this->user_session['uid']))->find()) {
            exit(json_encode(array('error_code' => false, 'data' => $order)));
        } else {
            exit(json_encode(array('error_code' => true, 'msg' => '错误的订单信息！')));
        }
    }
    private function isLogin()
    {
        if (empty($this->user_session)) {
            if($this->is_app_browser){
                $this->error_tips('请先进行登录！',U('Login/index'));
            }else{
                redirect(U('Login/index',array('referer'=>urlencode($_SERVER["REQUEST_URI"]))));
            }
        }
    }

    //extra_price 定制的页面
    public function merchant_shop(){

        if($this->config['open_extra_price']!=1){
            $this->error_tips('非法访问！');
        }
        $store_id= $_GET['store_id'];
        $from= !isset($_GET['from'])?0:$_GET['from'];//  '0代理团购，1代表订餐 2,预约',
        $now_store = D('Merchant_store')->get_store_by_storeId($_GET['store_id']);
        if(empty($now_store)){
            $this->error_tips('该店铺不存在！');
        }
        //得到当前店铺的评分
        $store_score = D('Merchant_score')->field('`score_all`,`reply_count`')->where(array('parent_id'=>$now_store['store_id'],'type'=>'2'))->find();
        $appoint_list = D('Appoint')->get_appointlist_by_StoreId($store_id);
        $activity_list = M('Wxapp_list')->where(array('mer_id'=>$now_store['mer_id']))->select();
        $reply_list = M('Reply')->field('r.*,u.nickname')->join('AS r left join '.C('DB_PREFIX').'user AS u ON r.uid = u.uid')->where(array('r.store_id'=>$store_id,'r.order_type'=>$from))->order('score DESC')->limit(2)->select();
        foreach ($reply_list as &$v) {
            $v['pic']=M('Reply_pic')->where(array('pigcms_id'=>array('in',$v['pic'])))->select();
        }
        $reply_count = M('Reply')->where(array('store_id'=>$store_id))->count();
        $this->assign('reply_count',$reply_count);
        $this->assign('reply_list',$reply_list);
        $this->assign('activity_list',$activity_list);
        $this->assign('appoint_list',$appoint_list);
        $this->assign('store_score',$store_score);

        if(!empty($this->user_session)){
            $database_user_collect = D('User_collect');
            $condition_user_collect['type'] = 'group_shop';
            $condition_user_collect['id'] = $now_store['store_id'];
            $condition_user_collect['uid'] = $this->user_session['uid'];
            if($database_user_collect->where($condition_user_collect)->find()){
                $now_store['is_collect'] = true;
            }
        }

        $now_store['reply_url']=U('Wap/Group/feedback',array('order_type'=>$from));
        $this->assign('now_store',$now_store);

        $store_group_list = D('Group')->get_store_group_list($now_store['store_id'],0,true);
        $this->assign('store_group_list',$store_group_list);

        //为粉丝推荐
        $index_sort_group_list = D('Group')->get_group_list('index_sort',10,true);
        //判断是否微信浏览器，
        if($_SESSION['openid'] && $index_sort_group_list){
            $long_lat = D('User_long_lat')->field('long,lat')->where(array('open_id' => $_SESSION['openid']))->find();
            if($long_lat){
                import('@.ORG.longlat');
                $longlat_class = new longlat();
                $location2 = $longlat_class->gpsToBaidu($long_lat['lat'], $long_lat['long']);//转换腾讯坐标到百度坐标
                $group_store_database = D('Group_store');
                foreach($index_sort_group_list as &$storeGroupValue){
                    $tmpStoreList = $group_store_database->get_storelist_by_groupId($storeGroupValue['group_id']);
                    if($tmpStoreList){
                        foreach($tmpStoreList as &$tmpStore){
                            $tmpStore['Srange'] = getDistance($location2['lat'],$location2['lng'],$tmpStore['lat'],$tmpStore['long']);
                            $tmpStore['range'] = getRange($tmpStore['Srange'],false);
                            $rangeSort[] = $tmpStore['Srange'];
                        }
                        array_multisort($rangeSort, SORT_ASC, $tmpStoreList);
                        $storeGroupValue['store_list'] = $tmpStoreList;
                        $storeGroupValue['range'] = $tmpStoreList[0]['range'];
                    }
                }
            }
        }
        $this->assign('index_sort_group_list',$index_sort_group_list);
        $this->display();
    }


    public function again_order(){
        $this->check_cart();
        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D('Shop_order')->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
        echo '<pre/>';
        print_r($order);
    }



    /**
     * 订单详情
     */
    public function order_detail()
    {

        $order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;
        $order = D("Shop_order")->get_order_detail(array('order_id' => $order_id, 'uid' => $this->user_session['uid']));
        //
        //var_dump($order);die();
        if ($order) {

            //-------------------------------  获取地图位置 --------------------------------------peter

            $supply = D('Deliver_supply')->where(array('order_id'=>$order_id))->find();
            if($supply){
                $deliver_id = $supply['uid'];
                $deliver = D('Deliver_user')->where(array('uid'=>$deliver_id))->find();
                $order['store_lat'] = $supply['from_lat'];
                $order['store_lng'] = $supply['from_lnt'];
                $order['user_lat'] = $supply['aim_lat'];
                $order['user_lng'] = $supply['aim_lnt'];
                $order['deliver_lat'] = $deliver['lat'];
                $order['deliver_lng'] = $deliver['lng'];
            }
            //------------------------------------------------------------------------------------

            $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();

            $store_image_class = new store_image();
            //modify garfunkel
            $store['name'] = lang_substr($store['name'],C('DEFAULT_LANG'));
            //var_dump($store);die();
            $images = $store_image_class->get_allImage_by_path($store['pic_info']);
            $store['image'] = isset($images[0]) ? $images[0] : '';
            cookie('shop_cart_' . $store['store_id'], null);
            for($i=0;$i<20;$i++){
                if(cookie('shop_cart_' . $store['store_id'].'_'.$i)){
                    cookie('shop_cart_' . $store['store_id'].'_'.$i,null);
                }else{
                    break;
                }
            }
            $city = D('Area')->where(array('area_id'=>$store['city_id']))->find();
            $order['jetlag'] = $city['jetlag'];

            //------------------------------ 更新status等信息 ------------------------------------peter
            if ($order['status']==4||$order['status']==5){  //取消订单
                $n_status=$order['status'];
                $order['statusLog'] = $n_status['status'];
                $order['statusLogName'] =L('_B_MY_ORDERCANCELLEDACCESS_');
                $order['statusDesc'] =L('_CANCELLATION_ORDER_');
            }else{
                $n_status = D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id']))->order('id DESC')->find();
                //var_dump($n_status);die();
                $add_time = 0;
                if($n_status['status'] == 33){
                    if(D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id'], 'status' => 3))->order('id DESC')->find())
                        $n_status['status'] = 3;
                    else
                        $n_status['status'] = 2;
                    $add_time = $n_status['note'];
                }else {
                    if ($add_time_log = D('Shop_order_log')->field(true)->where(array('order_id' => $order['order_id'], 'status' => 33))->order('id DESC')->find()) {
                        $add_time = $add_time_log['note'];
                    }
                }
                $order['statusLog'] = $n_status['status'];

                if( ($order['paid'] == 0) && ($n_status['status']=="1" || $n_status['status']=="0")){
                    $order['statusLogName']=L('V3_UNPAID');
                    $order['statusDesc'] = L('V3_UNPAID_DESC');
                }else{
                    $order['statusLogName'] = D('Store')->getOrderStatusLogName($n_status['status']);
                    $order['statusDesc'] = D('Store')->getOrderStatusDesc($n_status['status'],$order,0,$store['name'],$add_time);
                }

            }

            //var_dump($order['statusDesc']);die();
            //-------------------------------------------------------------------------------------
            //var_dump($order);die();
            if($order['paid'] == 0){
                $order['pay_type'] = 'Not Paid';
            }else{
                if($order['pay_type'] == ''){
                    $order['pay_type'] = 'Tutti Credits';
                }else{
                    if($order['pay_type'] == 'offline' || $order['pay_type'] == 'Cash'){
                        $order['pay_type'] = 'Cash';
                    }
                }
            }
            //-----------------------------------------------------------------------------
            if($order['pay_type'] == 'Cash' && empty($order['third_id'])){
                $payment = rtrim(rtrim(number_format($order['price']-$order['card_price']-$order['merchant_balance']-$order['card_give_money']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-floatval($order['coupon_price']),2,'.',''),'0'),'.');
            }
            $discount_price = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'], 2));
            $order['card_discount'] = $order['card_discount'] == 0 ? 10 : $order['card_discount'];
            $arr['order_details'] = array(
                'orderid' => $order['orderid'],
                'order_id' => $order['order_id'],
                'real_orderid' => $order['real_orderid'],
                'username' => $order['username'],
                'userphone' => $order['userphone'],
                'create_time' => date('Y-m-d H:i:s',$order['create_time']),
                'pay_time' => date('Y-m-d H:i:s',$order['pay_time']),
                'expect_use_time' => $order['expect_use_time'] != 0 ? date('Y-m-d H:i',$order['expect_use_time']) : '尽快',
                'is_pick_in_store' => $order['is_pick_in_store'],//配送方式 0：平台配送，1：商家配送，2：自提，3:快递配送
                'address' => $order['address'],
                'deliver_str' => $order['deliver_str'],
                'deliver_status_str' => $order['deliver_status_str'],
                'note' => isset($order['desc']) ? $order['desc'] : '',
                'invoice_head' => $order['invoice_head'],//发票抬头
                'pay_status' => $order['pay_status_print'],
                'pay_type_str' => $order['pay_type_str'],
                'status_str' => $order['status_str'],
                'score_used_count' => $order['score_used_count'],//抵用的积分
                'score_deducte' => strval(floatval($order['score_deducte'])),//积分兑现的金额
                'card_give_money' => strval(floatval($order['card_give_money'])),//会员卡赠送余额
                'merchant_balance' => strval(floatval($order['merchant_balance'])),//商家余额
                'balance_pay' => strval(floatval($order['balance_pay'])),//平台余额
                'payment_money' => strval(floatval($order['payment_money'])),//在线支付的金额
                'change_price' => strval(floatval($order['change_price'])),//店员修改前的原始价格（如果是0表示没有修改过，可不显示）
                'change_price_reason' => $order['change_price_reason'],//店员修改价格的理由
                'card_id' => $order['card_id'],
                'card_price' => strval(floatval($order['card_price'])),//商家优惠券的金额
                'coupon_price' => strval(floatval($order['coupon_price'])),//平台优惠券的金额
                'payment' => isset($payment) ? $payment : 0,
                'use_time' => $order['use_time'] != 0 ? date('Y-m-d H:i:s',$order['use_time']) : '0',
                'last_staff' => $order['last_staff'],
                'status' => $order['status'],
                'paid' => $order['paid'],
                'register_phone' => $order['register_phone'],//注册时的用户手机号
                'lat' => $order['lat'],
                'lng' => $order['lng'],
                'cue_field' => $order['cue_field'],//商家自定义字段值（如果没有的话是空 即：''）
                'card_discount' => $order['card_discount'],//会员卡折扣
                'goods_price' => strval(floatval($order['goods_price'])),//商品的总价
                'freight_charge' => strval(floatval($order['freight_charge'])),//配送费
                'packing_charge' => strval(floatval($order['packing_charge'])),//打包费
                'total_price' => strval(floatval($order['total_price'])),//订单总价
                'merchant_reduce' => strval(floatval($order['merchant_reduce'])),//商家优惠的金额
                'balance_reduce' => strval(floatval($order['balance_reduce'])),//平台优惠的金额
                'price' => strval(floatval($order['price'])),//实际支付金额
                'distance' => round(getDistance($order['lat'], $order['lng'], $this->store['lat'], $this->store['long'])/1000, 2),//距离
                'discount_price' => strval($discount_price),//折扣后的总价  = floatval(round($order['discount_price'] + $order['freight_charge'] + $order['packing_charge'], 2));
                'minus_price' => strval(floatval(round($order['merchant_reduce'] + $order['balance_reduce'], 2))),//平台和商家的优惠金额
                'go_pay_price' => strval(floatval(round($discount_price * 1.05 - $order['merchant_reduce'] - $order['balance_reduce'], 2))),//应付的金额
                'minus_card_discount' => strval(floatval(round(($discount_price - $order['merchant_reduce'] - $order['balance_reduce'] - $order['freight_charge']) * (1 - $order['card_discount'] * 0.1), 2))),//折扣与优惠的优惠金额
                'order_from_txt' => $this->order_froms[$order['order_from']],
                'deliver_log_list' => D('Shop_order_log')->where(array('order_id' => $order['order_id']))->order('id DESC')->find(),
                'deliver_info' => unserialize($order['deliver_info']),
                'pay_type' => $order['pay_type'],
                'tip_charge' => $order['tip_charge'],
                'delivery_discount'=>$order['delivery_discount'],
                'service_fee'=>$order['service_fee']
            );
            $tax_price = 0;
            $deposit_price = 0;
            foreach($order['info'] as $v) {
                $discount_price = floatval($v['discount_price']) > 0 ? floatval($v['discount_price']) : floatval($v['price']);
                $arr['info'][] = array(
                    //modify garfunkel
                    'name' => lang_substr($v['name'],C('DEFAULT_LANG')),
                    'discount_type' => $v['discount_type'],
                    'price' => strval(floatval($v['price'])),
                    'discount_price' => strval($discount_price),
                    'spec' => empty($v['spec']) ? '' : $v['spec'],
                    'num' => $v['num'],
                    'total' => strval(floatval($v['price'] * $v['num'])),
                    'discount_total' => strval(floatval($discount_price * $v['num'])),
                );
                if($store['menu_version'] == 1) {
                    $goods = D('Shop_goods')->field(true)->where(array('goods_id' => $v['goods_id']))->find();
                    $tax_price += $v['price'] * $goods['tax_num']/100 * $v['num'];
                    $deposit_price += $goods['deposit_price'] * $v['num'];
                }elseif ($store['menu_version'] == 2){
                    $goods = D('StoreMenuV2')->getProduct($v['goods_id'],$order['store_id']);
                    //$tax_price += $v['price'] * $goods['tax']/100000 * $v['num'];
                    $tax_price += D('StoreMenuV2')->calculationTaxFromOrder($v);
                    $deposit_price += 0;
                }
                /**
                $goods = D('Shop_goods')->field(true)->where(array('goods_id'=>$v['goods_id']))->find();
                $tax_price += $v['price'] * $goods['tax_num']/100 * $v['num'];
                $deposit_price += $goods['deposit_price'] * $v['num'];
                 * */
            }
            $tax_price = $tax_price + ($order['freight_charge'] + $order['packing_charge'])*$store['tax_num']/100;
            $arr['order_details']['tax_price'] = $tax_price;
            $arr['order_details']['deposit_price'] = $deposit_price;
            $arr['discount_detail'] = $order['discount_detail'] ?: '';

            $order['discount_price'] = $order['coupon_price'] + $order['merchant_reduce'] + $order['delivery_discount'];
            $this->assign("order",$order);
//             echo '<pre/>';
//             print_r($arr);die;
            $this->assign($arr);
            $this->assign('store', $store);
            $this->assign('back_url', "../wap.php?g=Wap&c=My&a=shop_order_list");

            //var_dump($order);

            $this->display();
        } else {
            $this->error_tips('订单信息错误！');
        }
    }

}
?>