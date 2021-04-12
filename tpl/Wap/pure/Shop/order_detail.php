<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{pigcms{:L('_OUT_TXT_')} {pigcms{:L('_ORDER_DETAIL_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
    <meta http-equiv="pragma" content="no-cache"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name='apple-touch-fullscreen' content='yes'/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <link href="{pigcms{$static_path}shop/css/order_detail.css" rel="stylesheet"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
</head>
<style>
    .main{
        width: 100%;
        padding-top: 48px;
        max-width: 640px;
        min-width: 320px;
        margin: 0 auto;
    }
    .gray_line{
        width: 100%;
        height: 2px;
        margin-top: 15px;
        background-color: #cccccc;
    }
    .this_nav{
        width: 100%;
        text-align: center;
        font-size: 1.8em;
        height: 30px;
        line-height: 30px;
        margin-top: 15px;
        position: relative;
    }
    .this_nav span{
        width: 50px;
        height: 30px;
        display:-moz-inline-box;
        display:inline-block;
        -moz-transform:scaleX(-1);
        -webkit-transform:scaleX(-1);
        -o-transform:scaleX(-1);
        transform:scaleX(-1);
        background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
        background-size: auto 20px;
        background-repeat: no-repeat;
        background-position: right center;
        position: absolute;
        left: 8%;
        cursor: pointer;
    }
    .detail_header{
        display: flex;
        background-color: white;
        height: 40px;
        line-height: 40px;
        font-size: 1.2em;
    }
    .detail_header div{
        box-sizing: border-box;
        flex: 1 1 100%;
        text-align: center;
        color: #ffa52d;
    }
    .header_active{
        border-bottom: 1px solid #ffa52d;
    }
    .g_details{
        margin-top: 0px;
        padding-top: 5px;
    }
    .infor .answer {
        padding: 0px 10px 10px 0;
    }
    .infor .answer .fr,.infor li .e2c{
        color: #ffa52d;
    }
    .infor .kd_dd .right .del{
        text-decoration: none;
    }

    .map_infor{
        width: 100%;
        height: 400px;
    }
    .msg_infor{
        width: 94%;
        background: #fff;
        border-radius: 5px;
        border 1px solid #e3e3e3;
        opacity: 0.95;
        position: absolute;
        top: 120px;
        left: 3%;
        padding: 10px 10px 10px 10px;
    }
    .info_common{

    }
    .msg_title{
        font-size: 16px;
        margin: 0px 5px 5px;
        font-weight: bold
    }
    .msg_desc{
        font-size: 14px;
        margin: 5px 5px;
        color: #686868;
    }
    .bg_infor{
        width: 100%;
    }
    .bg_infor img{
        width: 100%;
    }
    .info_norad{
        border-radius: 0px;
    }
    .infor_head {
        background: #fff;
        margin: 0px;
        border-radius: 10px 10px 0 0;
        position: relative;
        top: -8px;
        height: 8px;
    }
    .infor {
        background: #fff;
        margin-bottom: 10px;
    }
    .infor li {
        border-bottom: #f1f1f1 0px solid;
    }
    .infor li.storext .tit {
        padding-left: 0px;
    }
    .infor li.storext a {
        display: block;
        padding: 10px 0px;
        font-weight: bold;
    }
    .infor li.storext {
        margin-left: 10px;
        position: relative;
        padding: 0px;
    }
    .order_num{
        display: inline-block;
        width:20px;
    }
    .infor .kd_dd .left {
        width: 80%;
    }
    .infor .kd_dd .right {
        width: 20%;
    }
    .infor .kd_dd .right div {
        width: 100%;
        float: right;
    }
    .infor .kd_dd {
        border-bottom: #f1f1f1 0px solid;
    }
    .infor .mealfee dd {
        padding: 5px 10px 10px 0;

    }
    .infor .mealfee{
        border-bottom: #f1f1f1 0px solid;
    }
    .infor .kd_dd dd h2{
        color: black;
    }
    .infor li.first {
        font-weight: bold;
    }
    .infor li.storext {
        border-bottom: #f1f1f1 0px solid;
    }
    .gray_line{
        border-bottom: 1px solid #e3e3e3;
        height: 1px;
        width: 100%;
    }
    .div_button{
        color: white;
        background: #ffa52d;
        font-size: 18px;
        width: 90%;
        border-radius: 10px;
        margin: 0px 20px 10px 20px;
        padding: 10px 10px 10px 10px;
        text-align: center;
    }
    .div_deli{
        margin: 0px 20px 0px 10px;
        background-image: url("./tpl/Static/blue/images/new/icon_call.png");
        background-size: auto 40px;
        background-repeat: no-repeat;
        background-position: right center;
    }
</style>
<body style="max-width: 640px;background-color: white;">
<include file="Public:header"/>
<div class="main">
<!--    <div class="this_nav">-->
<!--        <span id="back_span"></span>-->
<!--        {pigcms{:L('_ORDER_DETAIL_')}-->
<!--    </div>-->
<!--    <div class="gray_line"></div>-->
<!--    <div class="detail_header">-->
<!--        <div><a href="{pigcms{:U('status',array('order_id'=>$order_details['order_id']))}">{pigcms{:L('_ORDER_STATUS_')}</a></div>-->
<!--        <div class="header_active">{pigcms{:L('_ORDER_INFO_TXT_')}</div>-->
<!--    </div>-->
<section class="g_details">
    <if condition="$order.statusLog gt 3 AND 6 gt $order.statusLog AND $order.deliver_lng neq null AND $order.deliver_lat neq null">
        <div class="map_infor" id="web_map"></div>
    <else />
        <div class="bg_infor"><img src="{pigcms{$store['image']}"> </div>
    </if>
    <div class="msg_infor">
        <div class="msg_title info_common">{pigcms{$order.statusLogName}<span style="display:none; ">{pigcms{$order['statusLog']}-{pigcms{$order_details['status']}</span> </div>
        <div class="msg_desc info_common">{pigcms{$order.statusDesc}</div>
        <div class=""></div>
    </div>
    <!--    <iframe class="mapframe" src="http://54.190.29.18/index.php?g=Index&c=Index&a=map&type=2&order_id=12649&lang=--><?php //$_COOKIE['lang']?><!--"></iframe>-->
<!--    STORE-->

    <div class="infor_head"></div>

    <if condition="$order_details['paid'] eq 0 AND $order.statusLog eq 0">
        <div id="payment_box" class="infor">
<!--            data-time="'+order_list[i]['create_time']+'" data-id="'+order_list[i]['order_id']+'"data-jet="'+order_list[i]['jetlag']+'"-->
            <a href="{pigcms{:U('Pay/check',array('order_id' => $order_details['order_id'], 'type'=>'shop','times'=>'2'))}"><div class="div_button count_down" data-time="{pigcms{$order['create_time']}" data-id="{pigcms{$order_details['order_id']}" data-jet="{pigcms{$order['jetlag']}">Finish Payment</div></a>
        </div>
        <div class="gray_line"></div>
    </if>

    <if condition="$order.statusLog gt 2 AND 6 gt $order.statusLog AND $order.deliver_lng neq null AND $order.deliver_lat neq null">
        <div class="infor" style="margin-bottom: 0px;">
            <a href="tel:{pigcms{$order_details['deliver_info']['phone']}">
                <div class="div_deli">
                    <div style="font-size: 18px;font-weight: bold">{pigcms{$order_details['deliver_info']['name']}</div>
                    <div style="margin-top: 5px;">will deliver your order to you</div>
                </div>
            </a>
        </div>
        <div class="gray_line"></div>
    </if>

    <div class="infor">
        <ul>
            <li class="first storext">
                <a href="{pigcms{:U('Shop/classic_shop')}&shop_id={pigcms{$store['store_id']}">

                    <div class="tit">{pigcms{$store['name']}</div>
                </a>
            </li>
        </ul>
        <dl class="kd_dd">
            <volist name="info" id="goods">
                <dd class="clr">
                    <div class="fl left">
                        <h2>
                            <span class="order_num">{pigcms{$goods['num']}</span>
                            <if condition="in_array($goods['discount_type'], array(1, 3, 4))">
                                <em class="dd1">折</em>
                                <elseif condition="in_array($goods['discount_type'], array(2, 5))" />
                                <em class="d40">折</em>
                            </if>
                            {pigcms{$goods['name']}</h2>
                    </div>
                    <div class="clr fr right">
<!--                        <div class="fl del"> &nbsp;</div>-->
                        <div class="fl price">${pigcms{$goods['discount_total']}</div>
                    </div>
                </dd>
                <if condition="!empty($goods['spec'])">
                    <p style="font-size: 12px;color: #808080;margin-right: 5px;padding-left: 33px">{pigcms{$goods['spec']}</p>
                </if>
            </volist>
        </dl>
        <div class="mealfee">
            <dl>
                <dd class="clr">
                    <div class="fl">{pigcms{:L('_DELI_PRICE_')}</div>
                    <div class="fr">${pigcms{$order_details['freight_charge']}</div>
                </dd>
                <dd class="clr">
                    <div class="fl"><span style="line-height: 20px;display: inline-block;">{pigcms{:L('V2_SERVICEFEE')}</span> <img src="{pigcms{$static_path}img/index/tax_fee.png" id="tax_fee_img" width="20" style="vertical-align: middle;margin-left: 5px;" /></div>
                    <div class="fr">${pigcms{:number_format($order_details['packing_charge'] + $order_details['tax_price'] + $order_details['deposit_price'] + $order_details['service_fee'],2)}</div>
                </dd>
                <if condition="$order_details['tip_charge'] neq 0">
                    <dd class="clr">
                        <div class="fl">{pigcms{:L('_TIP_TXT_')}</div>
                        <div class="fr">${pigcms{$order_details['tip_charge']}</div>
                    </dd>
                </if>
                <dd class="clr">
                    <div class="fl"><span style="line-height: 20px;display: inline-block;color: #ffa52d">{pigcms{:L('V3_ORDER_DETAIL_DISCOUNT')}</span> </div>
                    <div class="fr" style="color: #ffa52d">-${pigcms{$order.discount_price}</div>
                </dd>
                <dd class="clr">
                    <div class="fl" style="font-weight: bold">{pigcms{:L('_TOTAL_RECE_')}</div>
                    <div class="fr" style="font-weight: bold">${pigcms{$order_details['price'] + $order_details['tip_charge']-$order_details['merchant_reduce']-$order_details['delivery_discount']-$order_details['coupon_price']|floatval}</div>

                </dd>
            </dl>
        </div>

<!--        <div class="reduce">-->
<!--            <dl>-->
<!--                <volist name="discount_detail" id="discount">-->
<!--                    <if condition="$discount['discount_type'] eq 1">-->
<!--                        <dd class="clr">-->
<!--                            <div  class="fl clr">-->
<!--                                <em class="fl e0c">首</em>-->
<!--                                <div class="p20">平台首单满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>-->
<!--                            </div>-->
<!--                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>-->
<!--                        </dd>-->
<!--                        <elseif condition="$discount['discount_type'] eq 2" />-->
<!--                        <dd class="clr">-->
<!--                            <div class="fl clr">-->
<!--                                <em class="fl d52">减</em>-->
<!--                                <div class="p20">平台优惠满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>-->
<!--                            </div>-->
<!--                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>-->
<!--                        </dd>-->
<!--                        <elseif condition="$discount['discount_type'] eq 3" />-->
<!--                        <dd class="clr">-->
<!--                            <div class="fl clr">-->
<!--                                <em class="fl ffa">首</em>-->
<!--                                <div class="p20">店铺首单满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>-->
<!--                            </div>-->
<!--                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>-->
<!--                        </dd>-->
<!--                        <elseif condition="$discount['discount_type'] eq 4" />-->
<!--                        <dd class="clr">-->
<!--                            <div class="fl clr">-->
<!--                                <em class="fl ff6">减</em>-->
<!--                                <div class="p20">店铺优惠满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>-->
<!--                            </div>-->
<!--                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>-->
<!--                        </dd>-->
<!--                        <elseif condition="$discount['discount_type'] eq 5" />-->
<!--                        <dd class="clr">-->
<!--                            <div class="fl clr">-->
<!--                                <em class="fl ff0">惠</em>-->
<!--                                <div class="p20">商品满{pigcms{$discount['money']|floatval}元配送费减{pigcms{$discount['minus']|floatval}元</div>-->
<!--                            </div>-->
<!--                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>-->
<!--                        </dd>-->
<!--                    </if>-->
<!--                </volist>-->
<!--            </dl>-->
<!--        </div>-->


    </div>
    <div class="gray_line"></div>
<!--    ORDER-->
    <div class="infor">
        <ul>
                <li class="clr first">
                    <div class="fl match">{pigcms{:L('_ORDER_INFO_')}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('V3_ORDER_DETAIL_ORDERNUMBER')}</div>
                    <div class="fr">{pigcms{$order_details['real_orderid']}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_PAYMENT_MODE_')}</div>
                    <div class="fr">{pigcms{$order_details['pay_type']}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_RECE_INFO_')}</div>
                    <div class="p90">
                        <p>{pigcms{$order_details['address']}</p>
                        <p>{pigcms{$order_details['username']} {pigcms{$order_details['userphone']}</p>
                    </div>
                </li>
                <!--li class="clr">
                    <div class="fl">{pigcms{:L('_DIST_MODE_')}</div>
                    <div class="fr">{pigcms{:L('_PLAT_DIST_')}</div>
                </li-->
<!--                <li class="clr">-->
<!--                    <div class="fl">{pigcms{:L('_EXPECTED_TIME_')}</div>-->
<!--                    <div class="fr">{pigcms{$order_details['expect_use_time']}</div>-->
<!--                </li>-->



        </ul>
    </div>
    <div class="gray_line"></div>
<!--    STORE NOTES-->
    <div class="infor">
        <ul>
            <li class="clr first">
                <div class="fl book">{pigcms{:L('V3_ORDER_DETAIL_NOTES')}</div>
            </li>
<!--            <li class="clr">-->
<!--                <div class="fl">{pigcms{:L('_B_PURE_MY_68_')}</div>-->
<!--                <div class="fr">{pigcms{$order_details['real_orderid']}</div>-->
<!--            </li>-->
<!--            <li class="clr">-->
<!--                <div class="fl">{pigcms{:L('_ORDER_TIME_')}</div>-->
<!--                <div class="fr">{pigcms{$order_details['create_time']}</div>-->
<!--            </li>-->
            <li class="clr">
                <if condition="$order_details['note'] eq ''">
                    N/A
                    <else/>
                    {pigcms{$order_details['note']}
                </if>

            </li>
        </ul>
    </div>

<!--    BOTTOM BUTTON-->
    <div class="consume consumes">
        <ul class="clr">
            <if condition="$order_details['status'] lt 3 OR ($order_details['paid'] eq 1 AND $order_details['status'] eq 5) OR ($order_details['paid'] eq 0 AND $order_details['status'] eq 7)">
<!--                <if condition="$order_details['paid'] eq 0">-->
<!--                    <li class="fl firmly" data-url="{pigcms{:U('Pay/check',array('order_id' => $order_details['order_id'], 'type'=>'shop'))}">{pigcms{:L('_PAYMENT_ORDER_')}</li>-->
<!--                </if>-->
                <php> if($config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0 && $now_merchant['sub_mch_refund'] == 0 && $order['is_own'] == 2 && $order['pay_type'] == 'weixin'){</php>
                <li class="fr zlyd refund_button">{pigcms{:L('_CANNT_REFUND_C_S_')} 【{pigcms{$now_merchant.name}】</li>
                <php>}else{</php>

                <if condition="$order_details['paid'] eq 0">
                    <li class="fr replace refund_button" data-url="{pigcms{:U('Shop/orderdel', array('order_id' => $order_details['order_id']))}">{pigcms{:L('_CANCEL_ORDER_')}</li>
                    <elseif condition="$order_details['paid'] eq 1 AND $order_details['status'] lt 1" />
                    <li class="fr replace refund_button" data-url="{pigcms{:U('My/shop_order_refund', array('order_id' => $order_details['order_id']))}">{pigcms{:L('_CANCEL_ORDER_')}</li>
                    <elseif condition="$order_details['paid'] eq 1 AND $order_details['status'] eq 5" />
                    <li class="fr replace refund_button" data-url="{pigcms{:U('My/shop_order_refund', array('order_id' => $order_details['order_id']))}">{pigcms{:L('_REFUND_TXT_')}</li>
                </if>
                <php>}</php>
                <if condition="$order_details['status'] eq 2">
                    <li class="fl replace reorder" data-url="{pigcms{:U('My/shop_feedback',array('order_id' => $order_details['order_id']))}">{pigcms{:L('_B_PURE_MY_73_')}</li>
                    <!--li class="fr zlyd" data-url="{pigcms{:U('Shop/confirm_order', array('order_id' => $order_details['order_id'], 'store_id' => $store['store_id']))}">{pigcms{:L('_ONE_MORE_LIST_')}</li-->
                </if>
                <else/>
                <!--li class="fr zlyd" data-url="{pigcms{:U('Shop/confirm_order', array('order_id' => $order_details['order_id'], 'store_id' => $store['store_id']))}">{pigcms{:L('_ONE_MORE_LIST_')}</li-->
            </if>
        </ul>
    </div>

</section>
</div>
</body>
</html>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<style>
    .b_font{
        color: #555;
        font-weight: bold;
        font-size: 16px;
    }
</style>

<if condition="$order.statusLog gt 3 AND 6 gt $order.statusLog AND $order.deliver_lng neq null AND $order.deliver_lat neq null">
 <script>
    var store_lat = "{pigcms{$order.store_lat}";
    var store_lng = "{pigcms{$order.store_lng}";
    var user_lat = "{pigcms{$order.user_lat}";
    var user_lng = "{pigcms{$order.user_lng}";
    var deliver_lat ="{pigcms{$order.deliver_lat}";
    var deliver_lng = "{pigcms{$order.deliver_lng}";

    var deliver_icon = "{pigcms{$static_public}images/deliver/icon_deliver_map.png";
    var store_icon = "{pigcms{$static_public}images/deliver/icon_store_map.png";

    //获取get传值的方法
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]);
        return null;
    }
    var map;
    function initMap() {
        // The location of Uluru
        var lng= Number(getQueryString("lng"));
        var lat=Number(getQueryString("lat"));
        var label=getQueryString("label");
        var uluru = {lat: parseFloat(deliver_lat), lng: parseFloat(deliver_lng)};
        var store_pos = {lat:parseFloat(store_lat), lng:parseFloat(store_lng)};
        var user_pos = {lat:parseFloat(user_lat), lng:parseFloat(user_lng)};
        // The map, centered at Uluru
        var map = new google.maps.Map(
            document.getElementById('web_map'), {zoom: 18, center: uluru});

        var deliver = {
            url:deliver_icon,
            scaledSize: new google.maps.Size(35,35),
            size: new google.maps.Size(35,35)
        };

        var store =  {
            url:store_icon,
            scaledSize: new google.maps.Size(35,35),
            size: new google.maps.Size(35,35)
        };

        // The marker, positioned at Uluru
        var marker_deliver = new google.maps.Marker({position: uluru, map: map,icon:deliver});
        var marker_store = new google.maps.Marker({position: store_pos, map: map,icon:store});
        var marker_user = new google.maps.Marker({position: user_pos, map: map});

        var bounds = new google.maps.LatLngBounds();
        bounds.extend(new   google.maps.LatLng(marker_deliver.getPosition().lat()
            ,marker_deliver.getPosition().lng()));
        bounds.extend(new   google.maps.LatLng(marker_store.getPosition().lat()
            ,marker_store.getPosition().lng()));
        bounds.extend(new   google.maps.LatLng(marker_user.getPosition().lat()
            ,marker_user.getPosition().lng()));

        map.fitBounds(bounds);
        //地图缩放时触发，当地图的缩放比例大于默认比例时，恢复为默认比例
        // google.maps.event.addListener(map, 'zoom_changed', function () {
        //     if (map.getZoom() > defaultZoom){
        //         map.setZoom(defaultZoom);
        //     }
        // });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&callback=initMap"
        async defer></script>
</if>

<script>

    var num = 0;
    var curr_time = parseInt("{pigcms{:time()}");

    $(document).ready(function(){
        update_pay_time();

        $('.consumes ul li.refund_button').click(function(){
            var link_url=$(this).data('url');
            layer.open({
                content: "{pigcms{:L(\'REFUND_ALERT_\')}",
                btn: ['Yes', 'No'],
                shadeClose: false,
                yes: function(){
                    window.location.href =link_url;
                }, no: function(){}
            });
        });

        $('.consumes ul li.reorder').click(function(){
            var link_url=$(this).data('url');
            window.location.href =link_url;
        });

        $(document).on('click','.phone',function(event){
            if($(this).attr('data-phone')){
                var tmpPhone = $(this).attr('data-phone').split(' ');
                var msg_dom = '<div class="msg-bg"></div>';
                msg_dom+= '<div id="msg" class="msg-doc msg-option">';
                msg_dom+= '<div class="msg-bd">'+($(this).data('phonetitle') ? $(this).data('phonetitle') : "{pigcms{:L('_CALL_PHONE_')}")+'</div>';
                for(var i in tmpPhone){
                    msg_dom+= '<div class="msg-option-btns"><a class="btn msg-btn" href="tel:'+tmpPhone[i]+'">'+(tmpPhone.length == 1 && $(this).data('phonetip') ? $(this).data('phonetip') : tmpPhone[i])+'</a></div>';
                }
                msg_dom+= '     <button class="btn msg-btn-cancel" type="button">{pigcms{:L('_B_D_LOGIN_CANCEL_')}</button>';
                msg_dom+= '</div>';
                $('body').append(msg_dom);
            }
            event.stopPropagation();
        });
        $(document).on('click','.msg-btn-cancel,.msg-bg',function(){
            $('.msg-doc,.msg-bg').remove();
        });
    });
    $('#back_span').click(function () {
        window.location.href = "{pigcms{:U('My/shop_order_list')}";
    });

    var width = $(window).width()*2/3;

    var msg = "<div class='b_font' style='width: "+width+"px;text-align: center;'>{pigcms{:L('V2_SERVICEFEE')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_TAX')}:${pigcms{:number_format($order_details['tax_price'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:L('V2_TAXDES')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_PACKINGFEE')}:${pigcms{:number_format($order_details['packing_charge'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:L('V2_PACKINGFEEDES')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_BOTTLEDEPOSIT')}:${pigcms{:number_format($order_details['deposit_price'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:L('V2_BOTTLEDEPOSITDES')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_SERVICEFEEDES')}:${pigcms{:number_format($order_details['service_fee'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:replace_lang_str(L('V2_TOTALTAXNFEES'),$store['service_fee'])}</div>" +
        "<div class='b_font' style='width: "+width+"px;text-align: right;margin-top: 15px;margin-bottom: 10px;'>{pigcms{:L('V2_SERVICEFEE')}:{pigcms{:number_format($order_details['packing_charge'] + $order_details['tax_price'] + $order_details['deposit_price'] + $order_details['service_fee'],2)}</div>";

    $('#tax_fee_img').click(function () {
        layer.open({
            title:["",'border:none'],
            content:msg,
            style: 'border:none; background-color:#fff; color:#999;'
        });
    });

    function update_pay_time() {
        var count_down = parseInt("{pigcms{$count_down}");

        $('#payment_box').find('.count_down').each(function () {

            var create_time = $(this).data('time');
            var jetlag = parseInt($(this).data('jet'))*3600;
            var cha_time = count_down - (curr_time + jetlag - create_time + num);
            console.log(cha_time+"--"+curr_time+"-"+jetlag+"-"+create_time+"-"+num);

            var h = parseInt(cha_time / 3600);
            var i = parseInt((cha_time - 3600 * h) / 60);
            var s = (cha_time - 3600 * h) % 60;
            if (i < 10) i = '0' + i;
            if (s < 10) s = '0' + s;

            //var time_str = h + ':' + i + ':' + s;
            var time_str = "{pigcms{:L('_B_PURE_MY_81_')} " + i + ':' + s;

            $(this).html(time_str);

            var cid = $(this).data('id');
            var allStr = "my_order_"+cid;
            if(cha_time < 0){
                layer.open({content:'Payment over-time. You will be directed back to the menu.',shadeClose:false,btn:['OK'],yes:function(){
                        window.location.href = "{pigcms{:U('My/shop_order_list')}&shop_id={pigcms{$order_info.store_id}";
                 }});
            }else{
                window.setTimeout(function () {
                    num++;
                    update_pay_time()
                }, 1000);
            }
        });


    }

</script>