<?php
class Deliver_supplyModel extends Model
{
    
    public function saveOrder($order_id, $store, $item = 2)
    {
        $shopOrderDB = M('Shop_order');
        $order = $shopOrderDB->field(true)->where(array('order_id' => $order_id))->find();
        
        if ($order['is_pick_in_store'] == 0 && C('config.dada_is_open')) {
            $dada = new Dada();
            $longlat_class = new longlat();
            
            $areas = M('Area')->field(true)->where(array('area_id' => array('in', array($store['city_id'], $store['area_id']))))->select();
            foreach ($areas as $a) {
                if ($a['area_id'] == $store['city_id']) {
                    $city_name = $a['area_name'];
                } elseif ($a['area_id'] == $store['area_id']) {
                    $area_name = $a['area_name'];
                }
            }
            if (empty($store['dada_shop_id'])) {
                
                $city_list = S('dada_city_list');
                if (empty($city_list)) {
                    $result = $dada->cityList();
                    if ($result['status'] == 'success') {
                        foreach ($result['result'] as $c) {
                            $city_list[$c['cityName']] = $c['cityCode'];
                        }
                    } else {
                        return array('error_code' => 1, 'msg' => $result['msg']);
                    }
                    S('dada_city_list', $city_list, 86400);
                }
                if (isset($city_list[$city_name])) {
                    $city_code = $city_list[$city_name];
                } else {
                    return array('error_code' => 1, 'msg' => '达达暂没有开启【' . $city_name . '】城市的配送');
                }
                
                $shop['shop_name'] = $store['name'];//                必填            门店名称
                $shop['shop_type'] = 1;//                  必填            业务类型(餐饮-1,商超-9,水果生鲜-13,蛋糕-21,酒品-24,鲜花-3,其他-5)
                $shop['city_name'] = $city_name;//                  必填             城市名称(如,上海)
                $shop['area_name'] = $area_name;//                 必填             区域名称(如,浦东新区)
                $shop['shop_address'] = $store['adress'];//             必填             门店地址
                $location2 = $longlat_class->baiduToGcj02($store['lat'], $store['long']);
                $shop['shop_lng'] = $location2['lng'];//                    必填             门店经度
                $shop['shop_lat'] = $location2['lat'];//                     必填             门店纬度
                $shop['contact_name'] = $store['name'];//            必填              联系人姓名
                $shop['phone'] = $store['phone'];//                        必填              联系人电话
                
                $result = $dada->addShop($shop);
                if ($result['status'] == 'success') {
                    $dada_shop_id = $result['result']['successList'][0]['origin_shop_id'];
                    M('Merchant_store')->where(array('store_id' => $store['store_id']))->save(array('dada_shop_id' => $dada_shop_id, 'dada_city_code' => $city_code));
                } else {
                    return array('error_code' => 1, 'msg' => $result['msg']);
                }
            } else {
                $dada_shop_id = $store['dada_shop_id'];
                $city_code = $store['dada_city_code'];
            }
            
            $data = array();
            $data['shop_id'] = $dada_shop_id;//达达的门店号
            $data['order_id'] = $order['real_orderid'];//
            $data['city_code'] = $city_code;
            $data['order_price'] = $order['price'];
            $data['is_prepay'] = 0;
            $data['expected_fetch_time'] = time() + 1300;//期望取货时间
            $data['expected_finish_time'] = time() + 1600;//期望送达时间
            $data['user_name'] = $order['username'];//收货人姓名
            $data['user_address'] = $order['address'];//收货人地址
            $data['user_phone'] = $order['userphone'];//收货人手机号（手机号和座机号必填一项）
            $data['user_tel'] = $order['userphone'];//收货人座机号（手机号和座机号必填一项）

            $location2 = $longlat_class->baiduToGcj02($order['lat'], $order['lng']);
            $data['user_lat'] = $location2['lat'];//收货人地址维度（高德坐标系）
            $data['user_lng'] = $location2['lng'];//收货人地址经度（高德坐标系）
            
            $result = $dada->addOrder($data);
            if ($result['status'] == 'success') {
                return array('error_code' => 0, 'msg' => '接单成功！');
            } else {
                return array('error_code' => 1, 'msg' => $result['msg']);
            }
        } else {
            $old = $this->field(true)->where(array('order_id' => $order_id, 'item' => $item))->find();
            if (empty($old)) {
                $store = M('Merchant_store')->field(true)->where(array('store_id' => $order['store_id']))->find();
                if (empty($store)) return array('error_code' => 1, 'msg' => '订单所对应的店铺不存在');
                $supply = array();
                if (empty($order['third_id']) && $order['pay_type'] == 'offline') $order['paid'] = 0;
                $supply['order_id'] = $order_id;
                $supply['paid'] = $order['paid'];
                $supply['real_orderid'] = isset($order['real_orderid']) ? $order['real_orderid'] : '';
                $supply['pay_type'] = $order['pay_type'];
                $supply['money'] = $order['price'];
                $supply['deliver_cash'] = round($order['price']+$order['extra_price'] - round($order['card_price'] + $order['merchant_balance'] + $order['card_give_money'] +$order['balance_pay'] + $order['payment_money'] + $order['score_deducte'] + $order['coupon_price'], 2), 2);
                $supply['deliver_cash'] = max(0, $supply['deliver_cash']);
                $supply['store_id'] = $store['store_id'];
                $supply['store_name'] = $store['name'];
                $supply['mer_id'] = $store['mer_id'];
                $supply['from_site'] = $store['adress'];
                $supply['from_lnt'] = $store['long'];
                $supply['from_lat'] = $store['lat'];

                //目的地
                $supply['aim_site'] =  $order['address'];
                $supply['aim_lnt'] = $order['lng'];
                $supply['aim_lat'] = $order['lat'];
                $supply['name']  = $order['username'];
                $supply['phone'] = $order['userphone'];

                $supply['status'] =  1;
                $supply['type'] = $order['is_pick_in_store'];
                $supply['item'] = $item;//0:老快店的外卖，1：外送系统，2：新快店
                $supply['create_time'] = $_SERVER['REQUEST_TIME'];
                //$supply['start_time'] = $_SERVER['REQUEST_TIME'];
                $supply['appoint_time'] = $order['expect_use_time'];
                $supply['note'] = $order['desc'];
            
                $supply['order_time'] = $order['pay_time'];//订单支付时间
                $supply['freight_charge'] = $order['freight_charge'];//配送费
                $supply['distance'] = round(getDistance($order['lat'], $order['lng'], $store['lat'], $store['long'])/1000, 2);//配送距离
                $supply['is_hide'] = '0';//是否隐藏 默认为0

                if ($this->create($supply) != false) {
                    if ($addResult = $this->add($supply)) {
                        //推送消息提示
//                         $this->sendMsg($supply);
                        return array('error_code' => 0, 'msg' => '接单成功！');
                    } else {
                        return array('error_code' => 1, 'msg' => '保存订单失败');
                    }
                } else {
                    return array('error_code' => 1, 'msg' => '保存订单失败');
                }
            }
//             $shopOrderDB->where(array('order_id' => $order_id))->save(array('status' => 1, 'order_status' => 1, 'last_time' => time()));
            return array('error_code' => 0, 'msg' => '接单成功！');
        }
        
    }
    
    public function sendMsg($supply)
    {
        $group = $supply['type'] + 1;
        $where = $group == 1 ? '`group`=1' : '`group`=2 AND `store_id`=' . $supply['store_id'];
        $where .= " AND `status`=1 AND `is_notice`=0 AND ROUND(6378.138 * 2 * ASIN(SQRT(POW(SIN(({$supply['from_lat']}*PI()/180-`lat`*PI()/180)/2),2)+COS({$supply['from_lat']}*PI()/180)*COS(`lat`*PI()/180)*POW(SIN(({$supply['from_lnt']}*PI()/180-`lng`*PI()/180)/2),2))))<`range`";
        $users = D('Deliver_user')->field(true)->where($where)->select();
        $model = new templateNews(C('config.wechat_appid'), C('config.wechat_appsecret'));
        $href = C('config.site_url') . '/packapp/deliver/index.html?gopage=grab';
        foreach ($users as $user) {
            if ($user['client'] == 0) {
                if ($user['openid']) {
                    $model->sendTempMsg('OPENTM406638907', array('href' => $href, 'wecha_id' => $user['openid'], 'first' => $user['name'] . '您好！', 'keyword1' => '您有新的待处理订单', 'keyword2' => date('Y年m月d日 H:s'), 'remark' => '请您及时处理！'));
                }
            } else {
                $this->deliver($user);
            }
        }
    }
    
    
    private function deliver($user)
    {
        $client = intval($user['client']);	//1 IOS 2安卓
        $device_id = str_replace('-', '', $user['device_id']);
        $audience = array('tag' => array($device_id));
    
        $title = '订单提醒';
//         $msg = '小猪生活通：收款成功'.mt_rand(0,100).'.0'.mt_rand(1,9).'元';
        $msg = '您有新的待处理订单';
    
        $voice_return = json_decode($this->voic_baidu(), true);
        $voice_access_token = $voice_return['access_token'];
        $voice_mp3 = 'http://tsn.baidu.com/text2audio?tex=' . $msg . '&lan=zh&tok=' . $voice_access_token . '&ctp=1&cuid=9B9A62EE-3EE8-45E0-9C06-2E3245AE3FF5';
    
        $url = C('config.site_url') . '/packapp/deliver/index.html?gopage=grab';
        $js_url = C('config.site_url') . '/packapp/deliver/grab.html';
    
        $voice_second = 1;
    
        $extra = array('voice_mp3' => $voice_mp3, 'voice_second' => $voice_second, 'url' => $url,'js_url' => $js_url);
        
        import('@.ORG.Jpush');
        $jpush = new Jpush(C('config.deliver_jpush_appkey'), C('config.deliver_jpush_secret'));
        $notification = $jpush->createBody($client, $title, $msg, $extra);
        $message = $jpush->createMsg($title, $msg, $extra);
        
        $columns = array();
        $columns['platform'] = $client == 1 ? array('ios') : array('android');
        $columns['audience'] = $audience;
        $columns['notification'] = $notification;
        $columns['message'] = $message;
        $columns['from'] = 'delivery';
        $plan_msg = new plan_msg();
        $plan_msg->addTask(array('type' => '4', 'content' => array($columns)));
//         $msg = $jpush->send("all", $audience, $notification, $message);
    }
    private function voic_baidu()
    {
        static $return;

        if (empty($return)) {
            $voic_baidu = "https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=XU7nTWQzS9vn32bckmrhHbTu&client_secret=1375720f23eed9b7b787e728de5dd1c2";
            import('ORG.Net.Http');
            $return = Http::curlGet($voic_baidu);
        }
        return $return;
    }

}
