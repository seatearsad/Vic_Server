<?php
/**
 * Created by PhpStorm.
 * User: garfunkel
 * Date: 2021/9/28
 * Time: 8:09 PM
 */

class DeliverectAction
{
    private $site_url;
    private $link_type = 1;

    private $menu_version = 2;

    private $menumFolder = "./DeliverMenu/";
    //获取传递数据
    private $data;
    public function __construct()
    {
        file_put_contents("./deliverect_log.log",date("Y/m/d")."   ".date("h:i:sa")."   "."Deliverect" ."   ". $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'--'.json_encode(file_get_contents("php://input"))."\r\n",FILE_APPEND);
        $this->data = json_decode(file_get_contents("php://input"),true);
    }

    public function channelStatus(){
        $config = D('Config')->where(array('name'=>'site_url'))->find();
        $this->site_url = $config['value'];
        //echo "channelStatus";
        $link_array = array(
            "statusUpdateURL"=> $this->site_url."/deliverect/statusUpdate",
            "menuUpdateURL"=> $this->site_url."/deliverect/menuUpdate",
            "snoozeUnsnoozeURL"=> $this->site_url."/deliverect/snoozeUnsnooze",
            "busyModeURL"=> $this->site_url."/deliverect/busyMode",
            "updatePrepTimeURL"=> $this->site_url."/deliverect/updatePrepTimeURL"
        );


        $store_id = $this->data['channelLocationId'];
        $link_id = $this->data['channelLinkId'];
        $status = $this->data['status'];

        $link_status = 0;
        $log_type = "";
        $log_action = "";
        switch ($status){
            case 'register':
                $link_status = 1;
                $status = 1;
                $log_type = "Registration";
                $log_action = "";
                break;
            case 'active':
                $link_status = 2;
                $status = 1;
                $log_type = "Activate";
                $log_action = "";
                break;
            case 'inactive':
                $link_status = 3;
                $status = 0;
                $log_type = "Disable";
                $log_action = "";
                break;

            default:
                break;
        }

        $updateData['link_type'] = $this->link_type;
        $updateData['link_id'] = $link_id;
        $updateData['link_status'] =$link_status;
        $updateData['menu_version'] =$this->menu_version;
        $updateData['status'] = $status;

        D('Merchant_store')->where(array('store_id'=>$store_id))->save($updateData);

        D("StoreMenuV2")->saveIntegration($store_id,$log_type,$log_action,1);

        $store = D('Merchant_store')->where(array('store_id'=>$store_id))->find();
        if($store['link_status'] == 2){
            $this->syncStoreTime($store);
        }

        echo json_encode($link_array);
    }

    public function snoozeUnsnooze(){
        $channelLinkId = $this->data['channelLinkId'];
        $operations = $this->data['operations'];
        $locationId = $this->data['locationId'];

        $action = $operations[0]['action'];//snooze|unsnooze

        $updateItems = $operations[0]['data']['items'];

        $store = D('Merchant_store')->where(array('link_id'=>$channelLinkId))->find();

        $response['result'] = array();
        $responseResult['action'] = $action;
        $responseResult['data']['locationId'] = $locationId;
        $responseResult['data']['allSnoozedItems'] = array();

        if($action == "snooze"){
            $updateData['status'] = 0;
            $log_type = "Snnoze";
        }
        else{
            $updateData['status'] = 1;
            $log_type = "Unsnnoze";
        }

        $where['storeId'] = $store['store_id'];

        $log_action = array();
        foreach ($updateItems as $item){
            $responseResult['data']['allSnoozedItems'][] = $item['plu'];
            $where['plu'] = $item['_id'];

            $log_action[] = $item['plu'];

            if($action == "snooze") $updateData['snoozeTime'] = $item['snoozeEnd'];
            else $updateData['snoozeTime'] = "";

            D("Store_product")->where($where)->save($updateData);
        }

        $response['result'][] = $responseResult;

        D("StoreMenuV2")->saveIntegration($store['store_id'],$log_type,implode(',',$log_action),1);

        echo json_encode($response);
    }

    public function menuUpdate(){
        //Menu Type 0-Delivery and pickup 1-Delivery 2-Pickup 3-Eat-in 4-Curbside

        print_r($this->data);
        //菜单数量
        $menuNum = count($this->data);

        $storeThirdId = $this->data[0]['channelLinkId'];

        $store = D('Merchant_store')->where(array('link_id'=>$storeThirdId))->find();

        if($store) {
            $storeId = $store['store_id'];

            //存储文件备份
            if (!is_dir($this->menumFolder)) {
                mkdir($this->menumFolder);
            } elseif (!is_writeable($this->menumFolder)) {
                header('Content-Type:text/html; charset=utf-8');
                exit('目录 [ ' . $this->menumFolder . ' ] 不可写！');
            }

            mkdir($this->menumFolder . $storeId);

            $docName = date('Y-m-d H_i_s') . ".json";

            file_put_contents($this->menumFolder . $storeId . "/" . $docName, json_encode($this->data), FILE_APPEND);
            ////

            D('Store_menu')->where(array('storeId' => $storeId))->delete();
            D('Cart')->where(array('sid' => $storeId))->delete();

            foreach ($this->data as $k => $menu) {
                $menuId = $menu['menuId'];
                $menuName = $menu['menu'];
                $menuType = $menu['menuType'];
                //时间表
                $menuTime = $menu['availabilities'];

                $menuData['id'] = $menuId;
                $menuData['storeId'] = $storeId;
                $menuData['name'] = $menuName;
                $menuData['type'] = $menuType;
                $menuData['createType'] = $this->link_type;
                $menuData['createTime'] = time();

                D('Store_menu')->add($menuData);

                $categories = $menu['categories'];

                $this->intoCategories($categories, $menuData['id'], $storeId, $menuTime);

                //print_r($menuData);
                $products = array_merge($menu['products'], $menu['modifierGroups'], $menu['modifiers'], $menu['bundles']);
                $this->intoProducts($products, $storeId);
            }

            D("StoreMenuV2")->saveIntegration($storeId,"Menu Push",$this->data[0]['menu'],1);
        }

        if($store['link_status'] == 2){
            $this->syncStoreTime($store);
        }
        echo "menuUpdate";
    }

    public function busyMode(){
        $link_id = $this->data['channelLinkId'];
        $open_close = $this->data['status'] == "PAUSED" ? 0 : 1;

        $store = D('Merchant_store')->field(true)->where(array('link_id' => $link_id))->find();
        $shop_status = getClose($store);

        $log_action = "";
        //0 关闭店铺 1打开店铺
        if($open_close == 0){
            if(!$shop_status['is_close']){
                $data['store_is_close'] = $shop_status['open_num'];
                D('Merchant_store')->where(array('store_id' => $store['store_id']))->save($data);
            }
            $log_action = "Close store";
            //$this->success('Success');
        }else{//1打开店铺
            if($shop_status['is_close']){
                if($shop_status['open_num'] == 0){
                    //$this->error(L('_STORE_NOT_OPEN_TIP_'));
                }else{
                    $data['store_is_close'] = 0;
                    D('Merchant_store')->where(array('store_id' => $store['store_id']))->save($data);
                }
            }
            $log_action = "Open store";
            //$this->success('Success');
        }
        D("StoreMenuV2")->saveIntegration($store['store_id'],"Busy Mode",$log_action,1);

        echo "busyMode";
    }

    public function statusUpdate(){
        print_r($this->data);

        $orderId = $this->data['channelOrderId'];
        $status = $this->data['status'];
        D("Shop_order")->where(array('order_id'=>$orderId))->save(array('send_platform'=>$status));

        if($status == 100){//Cancel Order
            $this->orderRefund($orderId);
        }
        if($status == 20){
            $this->updatePrepTimeURL(true);
            $order = D("Shop_order")->where(array('order_id'=>$orderId))->find();
            D("StoreMenuV2")->saveIntegration($order['store_id'],"Order",$orderId." - Accepted",1);
        }

        if($status == 90 || $status == 95){
            $this->updatePrepTimeURL(true);
            $order = D("Shop_order")->where(array('order_id'=>$orderId))->find();
            D("StoreMenuV2")->saveIntegration($order['store_id'],"Order",$orderId." - Accepted ".$status,1);
        }

        echo "statusUpdate";
    }

    public function updatePrepTimeURL($acceepted = false){
        $orderId = $this->data['channelOrderId'];
        /**
        $pickupTime = str_replace("T"," ",$this->data['pickupTime']);
        $pickupTime = str_replace("Z","",$pickupTime);

        $pickupTime = strtotime($pickupTime);
         * */

        $database = D('Shop_order');
        $order_id = $condition['order_id'] = $orderId;
        $condition['is_del'] = 0;
        $order = $database->field(true)->where($condition)->find();

        /**
        $shop = D('Merchant_store')->field(true)->where(array('store_id' => $this->store['store_id']))->find();

        $area = D('Area')->where(array('area_id'=>$shop['city_id']))->find();
        $shop['busy_mode'] = $area['busy_mode'];
        $shop['min_time'] = $area['min_time'];

        if($shop['busy_mode'] == 1 && $_POST['dining_time'] < $shop['min_time']){
            $this->error_tips(replace_lang_str(L('D_F_TIP_3'),$shop['min_time']));
        }
        */

        if($acceepted) $dining_time = 20;
        /**
        if($order['send_platform'] == 20){
            $acceepted = true;
        }
         */

        if($this->data['pickupTime'] != '') {
            $preparationTime = $this->data['pickupTime'];

            $preparationTime = strtotime($preparationTime);

            $dining_time = intval(($preparationTime - strtotime(gmdate('Y-m-d\TH:i:s\Z'))) / 60);

            $database->where(array('order_id' => $order['order_id']))->save(array('dining_time' => $dining_time));
            D('Deliver_supply')->where(array('order_id' => $order['order_id']))->save(array('dining_time' => $dining_time));
            D("StoreMenuV2")->saveIntegration($order['store_id'],"Order",$orderId." - Prep time=".$dining_time,1);
        }

        if (empty($order)) {
            $this->error("Sorry, this order has been cancelled or removed.");
            exit;
        }
        if ($order['status'] == 4 || $order['status'] == 5) {
            $this->error('Failed! Order canceled by the customer');
            exit;
        }
        if ($order['status'] > 0) {
            $this->error('该单已接，不要重复接单');
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

        if($acceepted) {
            $_POST['dining_time'] = $dining_time ? $dining_time : 0;

            $store = D('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();

            $data['status'] = 1;
            $data['order_status'] = 1;
            $data['last_staff'] = "Deliverect";
            $data['last_time'] = time();
            $condition['status'] = 0;
            if ($database->where($condition)->save($data)) {
                if ($order['is_pick_in_store'] != 2 && $order['is_pick_in_store'] != 3) {
                    $result = D('Deliver_supply')->saveOrder($order_id, $store);
                    if ($result['error_code']) {
                        D('Shop_order')->where(array('order_id' => $order_id))->save(array('status' => 0, 'order_status' => 0, 'last_time' => time()));
                        //$this->error_tips($result['msg']);
                        exit;
                    }
                }

                D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 2, 'name' => "Deliverect", 'phone' => ""));

                //add garfunkel
                $userInfo = D('User')->field(true)->where(array('uid' => $order['uid']))->find();
                if ($userInfo['device_id'] != "") {
                    $message = 'Your order has been accepted by the store, they are preparing it now. Our Courier is on the way, thank you for your patience!';
                    Sms::sendMessageToGoogle($userInfo['device_id'], $message);
                } else {
                    $sms_data['uid'] = $order['uid'];
                    $sms_data['mobile'] = $order['userphone'];
                    $sms_data['sendto'] = 'user';
                    $sms_data['tplid'] = 172700;
                    $sms_data['params'] = [];
                    //Sms::sendSms2($sms_data);
                    $sms_txt = "Your order has been accepted by the store, they are preparing your order now. Our Courier is on the way, thank you for your patience.";
                    //Sms::telesign_send_sms($order['userphone'],$sms_txt,0);
                    Sms::sendTwilioSms($order['userphone'], $sms_txt);
                }

                if (isset($_POST['dining_time']) && $_POST['dining_time'] >= 40) {
                    $store['name'] = lang_substr($store['name'], 'en-us');
                    $sms_data['uid'] = $order['uid'];
                    $sms_data['mobile'] = $order['userphone'];
                    $sms_data['sendto'] = 'user';
                    $sms_data['tplid'] = 585843;
                    $sms_data['params'] = [
                        $store['name'],
                        $_POST['dining_time']
                    ];
                    //Sms::sendSms2($sms_data);
                    $sms_txt = "We’d like to inform you that " . $store['name'] . " needs " . $_POST['dining_time'] . " minutes to finish preparing your order. Estimated delivery time may be longer than expected. Thank you for your patience!";
                    //Sms::telesign_send_sms($order['userphone'],$sms_txt,0);
                    Sms::sendTwilioSms($order['userphone'], $sms_txt);
                }

                //$this->success('已接单');
            } else {
                //$this->error('接单失败');
            }
        }
        echo "updatePrepTimeURL";
    }


    /**
     * @param $categories
     * @param $menuId
     * @param $storeId
     * @param $menuTime 菜单的时间列表 如果分类中没有时间 便使用这个时间列表
     */
    public function intoCategories($categories,$menuId,$storeId,$menuTime){
        $category_list = array();
        $allCategoryId = array();
        $all_time = array();
        $all_subproduct = array();

        D('Store_categories')->where(array('storeId'=>$storeId))->delete();
        $sort = 0;
        foreach ($categories as $c => $category){
            $cateData = array();
            $cateData['id'] = $category['_id'];
            $cateData['name'] = $category['name'];
            $cateData['desc'] = $category['description'];
            $cateData['storeId'] = $storeId;
            $cateData['menuId'] = $menuId;
            $cateData['createType'] = 1;
            $cateData['createTime'] = time();
            $cateData['sort'] = $sort;

            $category_list[] = $cateData;

            $allCategoryId[] = $category['_id'];

            //如果分类中没有时间 便使用这个时间列表
            $cateTime = count($category['availabilities']) > 0 ? $category['availabilities'] : $menuTime;


            foreach ($cateTime as $ct => $cTime){
                $timeData = array();
                $timeData['categoryId'] = $cateData['id'];
                $timeData['weekNum'] = $cTime['dayOfWeek'];
                $timeData['startTime'] = $cTime['startTime'];
                $timeData['endTime'] = $cTime['endTime'];
                $timeData['storeId'] = $storeId;

                $all_time[] = $timeData;
            }

            $cateProduct = $category['subProducts'];
            $productSort = 0;
            foreach ($cateProduct as $p=>$productId){
                $subProduct = array();
                $subProduct['categoryId'] = $cateData['id'];
                $subProduct['productId'] = $productId;
                $subProduct['storeId'] = $storeId;
                $subProduct['sort'] = $productSort;

                $all_subproduct[] = $subProduct;
                $productSort++;
            }

            $sort++;
        }

        D('Store_categories')->addAll($category_list);

        //先删除之前所有的记录
        //D('Store_categories_time')->where(array('categoryId'=>array('in',$allCategoryId)))->delete();
        D('Store_categories_time')->where(array('storeId'=>$storeId))->delete();
        D('Store_categories_time')->addAll($all_time);

        //D('Store_categories_product')->where(array('categoryId'=>array('in',$allCategoryId)))->delete();
        D('Store_categories_product')->where(array('storeId'=>$storeId))->delete();
        D('Store_categories_product')->addAll($all_subproduct);
    }

    public function intoProducts($products,$storeId){
        $addAllList = array();

        $allProductIds = array();

        $allRelation = array();

        D('Store_product')->where(array('storeId'=>$storeId))->delete();

        foreach ($products as $thirdId=>$product){
            $productData['id'] = $thirdId;
            $productData['name'] = $product['name'];
            $productData['desc'] = $product['description'];
            if(count($product['productTags']) > 0)
                $productData['productTags'] = implode(',',$product['productTags']);
            else
                $productData['productTags'] = '';
            $productData['plu'] = $product['plu'];
            $productData['storeId'] = $storeId;
            if($product['imageUrl'])
                $productData['image'] = $product['imageUrl'];
            else
                $productData['image'] = '';
            $productData['sort'] = $product['sortOrder'];
            $productData['price'] = $product['price'] ? $product['price'] : 0;
            $productData['tax'] = $product['deliveryTax'] ? $product['deliveryTax'] : 0;
            $productData['productType'] = $product['productType'];

            if($product['isCombo'])
                $productData['isCombo'] = 1;
            else
                $productData['isCombo'] = 0;

            $productData['max'] = $product['max'] == 0 ? -1 : $product['max'];
            $productData['min'] = $product['min'];
            if($product['multiMax'])
                $productData['multiMax'] = $product['multiMax'];
            else
                $productData['multiMax'] = 0;

            $productData['createType'] = 1;
            $productData['createTime'] = time();

            if($productData['productType'] == 4)
                $productData['isBundles'] = 1;
            else
                $productData['isBundles'] = 0;

            if($productData['productType'] == 3)
                $productData['isModifiers'] = 1;
            else
                $productData['isModifiers'] = 0;

            $productData['subNum'] = count($product['subProducts']);

            $allProductIds[] = $thirdId;

            $addAllList[] = $productData;

            $subProducts = $product['subProducts'];

            $sort = 0;
            foreach ($subProducts as $s=>$subId){
                $relationData = array();
                $relationData['productId'] = $thirdId;
                $relationData['relationType'] = 0;
                if($productData['isBundles'] == 1) $relationData['relationType'] = 1;
                if($productData['isModifiers'] == 1) $relationData['relationType'] = 2;
                $relationData['subProductId'] = $subId;
                $relationData['storeId'] = $storeId;
                $relationData['sort'] = $sort;

                $allRelation[] = $relationData;

                $sort++;
            }
        }

        D('Store_product')->addAll($addAllList);
        //D('Store_product_relation')->where(array('productId'=>array('in',$allProductIds)))->delete();
        D('Store_product_relation')->where(array('storeId'=>$storeId))->delete();
        D('Store_product_relation')->addAll($allRelation);
    }

    public function orderRefund($orderId){
        $order_id = $orderId;

        $now_order = D("Shop_order")->get_order_detail(array('order_id' => $order_id));

        if(empty($now_order)){
            exit();
        }

        $uid = $now_order['uid'];

        $store_id = $now_order['store_id'];
        /**
        if (!($now_order['paid'] == 1 && ($now_order['status'] == 0 || $now_order['status'] == 5))) {
            $this->returnCode(1,'info',array(),L('_B_MY_ORDERDEALING_'));
        }
         * */

        $data_shop_order['cancel_type'] = 8;//取消类型（0:pc店员，1:wap店员，2:andriod店员,3:ios店员，4：打包app店员，5：用户，6：配送员, 7:超时取消,8:Deliverect）
        if ($now_order['pay_type'] == 'offline' || $now_order['pay_type'] == 'Cash') {
            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize(array('refund_time' => time()));
            $data_shop_order['status'] = 4;
            if (D('Shop_order')->data($data_shop_order)->save()) {
                $return = $this->shop_refund_detail($now_order, $store_id);
                if ($return['error_code']) {
                    exit();
                }
            } else {
                exit();
            }
        }else{
            if($now_order['pay_type'] == 'moneris'){
                import('@.ORG.pay.MonerisPay');
                $moneris_pay = new MonerisPay();
                $resp = $moneris_pay->refund($uid,$now_order['order_id']);
                //var_dump($resp);die();
                if($resp['responseCode'] != 'null' && $resp['responseCode'] < 50){
                    $data_shop_order['order_id'] = $now_order['order_id'];
                    $data_shop_order['status'] = 4;
                    $data_shop_order['last_time'] = time();
                    D('Shop_order')->data($data_shop_order)->save();
                }else{
                    exit();
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
                    exit();
                }
            }

            $return = $this->shop_refund_detail($now_order, $store_id);
            if ($return['error_code']) {
                exit();
            }

            D('Shop_order_log')->add_log(array('order_id' => $order_id, 'status' => 9));
        }

        import('@.ORG.Deliverect.Deliverect');
        $deliverect = new Deliverect();
        $result = $deliverect->createDelOrder($now_order);
    }


    private function shop_refund_detail($now_order, $store_id)
    {
        $order_id  = $now_order['order_id'];

        //平台余额退款
        if ($now_order['balance_pay'] != '0.00') {
            $add_result = D('User')->add_money($now_order['uid'],$now_order['balance_pay'],'退款 '.$order_id.' 增加余额',0,0,0,"Order Cancellation (Order # ".$order_id.")");

            $param = array('refund_time' => time());
            $param['refund_id'] = $now_order['order_id'];

            $data_shop_order['order_id'] = $now_order['order_id'];
            $data_shop_order['refund_detail'] = serialize($param);
            $data_shop_order['status'] = 4;
            D('Shop_order')->data($data_shop_order)->save();
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

    public function syncStoreTime($store){
        $area = D('Area')->where(array('area_id'=>$store['city_id']))->find();

        $checkCity = false;
        if($area['begin_time'] != $area['end_time']){
            $checkCity = true;
            $area['begin_time'] = substr($area['begin_time'],0,-3);
            $area['end_time'] = substr($area['end_time'],0,-3);
        }

        $categories_time_list = D('Store_categories_time')->where(array('storeId'=>$store['store_id']))->select();

        $clearList = array();
        foreach ($categories_time_list as $k=>$c){
            $checkStr = $c['weekNum'].'-'.$c['startTime'].'-'.$c['endTime'];
            if(in_array($checkStr,$clearList)){
                unset($categories_time_list[$k]);
            }else{
                $clearList[] = $checkStr;
            }
        }

        //var_dump($categories_time_list);die();

        $check_list = array(1=>2,2=>2,3=>2,4=>2,5=>2,6=>2,7=>2);

        $all_list = array();
        foreach ($categories_time_list as $t){
            if($check_list[$t['weekNum']] > 0) {
                $openName = 'open_' . (3*$t['weekNum'] - $check_list[$t['weekNum']]);
                $closeName = 'close_' . (3*$t['weekNum'] - $check_list[$t['weekNum']]);
                $check_list[$t['weekNum']] = $check_list[$t['weekNum']]-1;
            }else{
                $openName = 'open_' . (3*$t['weekNum']);
                $closeName = 'close_' . (3*$t['weekNum']);

                if($all_list[$openName]){
                    if(str_replace(':','',$t['startTime']) > str_replace(':','',$all_list[$openName])){
                        $t['startTime'] = $all_list[$openName];
                    }
                }

                if($all_list[$closeName]){
                    if(str_replace(':','',$t['endTime']) < str_replace(':','',$all_list[$closeName])){
                        $t['endTime'] = $all_list[$closeName];
                    }
                }
            }

            if($checkCity){
                if(str_replace(':','',$area['begin_time']) > str_replace(':','',$t['startTime'])){
                    $all_list[$openName] = $area['begin_time'];
                }else{
                    $all_list[$openName] = $t['startTime'];
                }

                if(str_replace(':','',$area['end_time']) < str_replace(':','',$t['endTime'])){
                    $all_list[$closeName] = $area['end_time'];
                }else{
                    $all_list[$closeName] = $t['endTime'];
                }
            }else {
                $all_list[$openName] = $t['startTime'];
                $all_list[$closeName] = $t['endTime'];
            }
        }

        for($i=1;$i<=21;++$i){
            if(!$all_list['open_'.$i]) {
                $all_list['open_'.$i] = "00:00:00";
            }else{
                $all_list['open_'.$i] = $all_list['open_'.$i].":00";
            }
            if(!$all_list['close_'.$i]){
                $all_list['close_'.$i] = "00:00:00";
            }else{
                $all_list['close_'.$i] = $all_list['close_'.$i].":00";
            }
        }
        //var_dump($all_list);die();
        D('Merchant_store')->where(array('store_id'=>$store['store_id']))->save($all_list);
    }
}