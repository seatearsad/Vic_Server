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
        padding-top: 60px;
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
        margin-top: 10px;
    }
    .infor .answer .fr,.infor li .e2c{
        color: #ffa52d;
    }
    .infor .kd_dd .right .del{
        text-decoration: none;
    }
</style>
<body>
<include file="Public:header"/>
<div class="main">
    <div class="this_nav">
        <span id="back_span"></span>
        {pigcms{:L('_ORDER_DETAIL_')}
    </div>
    <div class="gray_line"></div>
    <div class="detail_header">
        <div><a href="{pigcms{:U('status',array('order_id'=>$order_details['order_id']))}">{pigcms{:L('_ORDER_STATUS_')}</a></div>
        <div class="header_active">{pigcms{:L('_ORDER_INFO_TXT_')}</div>
    </div>
<section class="g_details">
    <div class="infor">
        <ul>
            <li class="first storext">
                <a href="{pigcms{:U('Shop/classic_shop')}&shop_id={pigcms{$store['store_id']}">
                    <div class="img">
                        <img src="{pigcms{$store['image']}">
                    </div>
                    <div class="tit">{pigcms{$store['name']}</div>
                </a>
            </li>
        </ul>
        <dl class="kd_dd">
            <volist name="info" id="goods">
                <dd class="clr">
                    <div class="fl left">
                        <h2>
                            <if condition="in_array($goods['discount_type'], array(1, 3, 4))">
                                <em class="dd1">折</em>
                                <elseif condition="in_array($goods['discount_type'], array(2, 5))" />
                                <em class="d40">折</em>
                            </if>
                            {pigcms{$goods['name']}</h2>
                    </div>
                    <div class="clr fr right">
                        <div class="fl ride">x{pigcms{$goods['num']}</div>
                        <div class="fl del"> &nbsp;</div>
                        <div class="fl price">${pigcms{$goods['discount_total']}</div>
                    </div>
                </dd>
                <if condition="!empty($goods['spec'])">
                    <p style="font-size: 12px;color: #808080;margin-right: 5px">{pigcms{$goods['spec']}</p>
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
            </dl>
        </div>

        <div class="reduce">
            <dl>
                <volist name="discount_detail" id="discount">
                    <if condition="$discount['discount_type'] eq 1">
                        <dd class="clr">
                            <div  class="fl clr">
                                <em class="fl e0c">首</em>
                                <div class="p20">平台首单满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                            </div>
                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>
                        </dd>
                        <elseif condition="$discount['discount_type'] eq 2" />
                        <dd class="clr">
                            <div class="fl clr">
                                <em class="fl d52">减</em>
                                <div class="p20">平台优惠满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                            </div>
                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>
                        </dd>
                        <elseif condition="$discount['discount_type'] eq 3" />
                        <dd class="clr">
                            <div class="fl clr">
                                <em class="fl ffa">首</em>
                                <div class="p20">店铺首单满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                            </div>
                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>
                        </dd>
                        <elseif condition="$discount['discount_type'] eq 4" />
                        <dd class="clr">
                            <div class="fl clr">
                                <em class="fl ff6">减</em>
                                <div class="p20">店铺优惠满{pigcms{$discount['money']|floatval}元减{pigcms{$discount['minus']|floatval}元</div>
                            </div>
                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>
                        </dd>
                        <elseif condition="$discount['discount_type'] eq 5" />
                        <dd class="clr">
                            <div class="fl clr">
                                <em class="fl ff0">惠</em>
                                <div class="p20">商品满{pigcms{$discount['money']|floatval}元配送费减{pigcms{$discount['minus']|floatval}元</div>
                            </div>
                            <div class="fr ff3">-${pigcms{$discount['minus']|floatval}</div>
                        </dd>
                    </if>
                </volist>
            </dl>
        </div>
        <div class="answer clr">
            <!--div class="fl">{pigcms{:L('_ORDER_TXT_')} ${pigcms{$order_details['discount_price']|floatval} {pigcms{:L('_DISCOUNT_TXT_')}-${pigcms{$order_details['minus_price']|floatval}</div-->
            <div class="fr">{pigcms{:L('_TOTAL_RECE_')}: ${pigcms{$order_details['price'] + $order_details['tip_charge']|floatval}</div>
        </div>

    </div>

    <div class="infor">
        <ul>
            <if condition="$order_details['is_pick_in_store'] eq 2">
                <li class="clr first">
                    <div class="fl match">{pigcms{:L('_SELF_LIFT_')}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_DIST_MODE_')}</div>
                    <div class="fr">{pigcms{:L('_SELF_DIST_')}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_SELF_LIFT_ADDRESS_')}</div>
                    <div class="p90">
                        <p>{pigcms{$order_details['address']}</p>
                    </div>
                </li>
                <else />
                <li class="clr first">
                    <div class="fl match">{pigcms{:L('_DIST_INFO_')}</div>
                </li>
                <!--li class="clr">
                    <div class="fl">{pigcms{:L('_DIST_MODE_')}</div>
                    <div class="fr">{pigcms{:L('_PLAT_DIST_')}</div>
                </li-->
                <li class="clr">
                    <div class="fl">{pigcms{:L('_EXPECTED_TIME_')}</div>
                    <div class="fr">{pigcms{$order_details['expect_use_time']}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_RECE_INFO_')}</div>
                    <div class="p90">
                        <p>{pigcms{$order_details['address']}</p>
                        <p>{pigcms{$order_details['username']} {pigcms{$order_details['userphone']}</p>
                    </div>
                </li>
                <if condition="$order_details['deliver_info']">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_DIST_DETAIL_')}</div>
                        <dl class="p90">
                            <dd>
                                <h2 class="endt">{pigcms{$order_details['deliver_info']['name']} <a href="tel:{pigcms{$order_details['deliver_info']['phone']}">{pigcms{$order_details['deliver_info']['phone']}</a></h2>
                            </dd>
                        </dl>
                    </li>
                </if>
            </if>
        </ul>
    </div>

    <div class="infor">
        <ul>
            <li class="clr first">
                <div class="fl book">{pigcms{:L('_ORDER_INFO_')}</div>
            </li>
            <li class="clr">
                <div class="fl">{pigcms{:L('_B_PURE_MY_68_')}</div>
                <div class="fr">{pigcms{$order_details['real_orderid']}</div>
            </li>
            <li class="clr">
                <div class="fl">{pigcms{:L('_ORDER_TIME_')}</div>
                <div class="fr">{pigcms{$order_details['create_time']}</div>
            </li>
            <li class="clr">
                <div class="fl">{pigcms{:L('_NOTE_INFO_')}</div>
                <div class="fr">{pigcms{$order_details['note']}</div>
            </li>
        </ul>
    </div>
    <if condition="$order_details['paid'] eq 1">
        <div class="infor">
            <ul>
                <li class="clr first">
                    <div class="fl branch">{pigcms{:L('_PAYMENT_INFO_')}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_PAYMENT_TIME_')}</div>
                    <div class="fr">{pigcms{$order_details['pay_time']}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_PAYMENT_MODE_')}</div>
                    <div class="fr">{pigcms{$order_details['pay_type_str']}</div>
                </li>
                <li class="clr">
                    <div class="fl">{pigcms{:L('_TOTAL_RECE_')}</div>
                    <div class="p90">
                        <if condition="$order_details['change_price'] gt 0">
                            <p class="e2c">${pigcms{$order_details['price']}</p>
                            <p class="kdsize">{pigcms{:L('_BEFORE_MODIFY_')}：${pigcms{$order_details['change_price']|floatval}</p>
                            <else />
                            <p class="e2c">${pigcms{$order_details['price']}</p>
                        </if>
                    </div>
                </li>
                <!--if condition="$order_details['card_discount'] eq 0 OR $order_details['card_discount'] eq 10">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_BUSINESS_CARD_DIS_')}</div>
                        <div class="p90">
                            <php>
                                if(C('DEFAULT_LANG') == 'en-us'){
                                $order_details['card_discount'] = 10*(10 - $order_details['card_discount']);
                                }
                            </php>
                            <p class="e2c">-${pigcms{$order_details['minus_card_discount']}（{pigcms{:replace_lang_str(L('_NUM_DISCOUNT_'),$order_details['card_discount'])}）</p>
                            <p class="kdsize">（{pigcms{:L('_NOTE_NOT_TAKE_DIS_')}）</p>
                        </div>
                    </li>
                </if-->
                <if condition="$order_details['coupon_price'] gt 0">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_PLATFORM_COUP_')}</div>
                        <div class="fr e2c">-${pigcms{$order_details['coupon_price']|floatval}</div>
                    </li>
                </if>
                <if condition="$order_details['card_price'] gt 0">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_SHOP_COUP_')}</div>
                        <div class="fr e2c">-${pigcms{$order_details['card_price']|floatval}</div>
                    </li>
                </if>
                <if condition="$order_details['delivery_discount'] neq 0">
                    <li class="clr" style="color: #ffa52d">
                        <div class="fl">Save</div>
                        <div class="fr e2c">-${pigcms{$order_details['delivery_discount']}</div>
                    </li>
                </if>
                <if condition="$order_details['score_deducte'] gt 0">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_INTEGRAL_DED_')}</div>
                        <div class="fr e2c">-${pigcms{$order_details['score_deducte']|floatval}（{pigcms{:replace_lang_str(L('_USED_POINTS_'),$order_details['score_used_count'])}）</div>
                    </li>
                </if>
                <if condition="$order_details['card_give_money'] gt 0">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_PAYMENT_OF_BALANCE_')}</div>
                        <div class="fr e2c">-${pigcms{$order_details['card_give_money']|floatval}</div>
                    </li>
                </if>
                <if condition="$order_details['merchant_balance'] gt 0">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_SHOP_BALANCE_PAY_')}</div>
                        <div class="fr e2c">-${pigcms{$order_details['merchant_balance']|floatval}</div>
                    </li>
                </if>
                <if condition="$order_details['balance_pay'] gt 0">
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_PLATFORM_BALANCE_PAY_')}</div>
                        <div class="fr e2c">-${pigcms{$order_details['balance_pay']|floatval}</div>
                    </li>
                </if>
                <if condition="$order_details['payment_money'] gt 0">
                    <li class="clr">
                        <div class="fl">{pigcms{$order_details['pay_type_str']}</div>
                        <div class="fr e2c">-${pigcms{$order_details['payment_money']|floatval}</div>
                    </li>
                </if>
            </ul>
        </div>
    </if>
    <div class="consume consumes">
        <ul class="clr">
            <if condition="$order_details['status'] lt 3 OR ($order_details['paid'] eq 1 AND $order_details['status'] eq 5) OR ($order_details['paid'] eq 0 AND $order_details['status'] eq 7)">
                <if condition="$order_details['paid'] eq 0">
                    <li class="fl firmly" data-url="{pigcms{:U('Pay/check',array('order_id' => $order_details['order_id'], 'type'=>'shop'))}">{pigcms{:L('_PAYMENT_ORDER_')}</li>
                </if>
                <php> if($config['open_sub_mchid'] && $now_merchant['open_sub_mchid'] && $now_merchant['sub_mch_id'] > 0 && $now_merchant['sub_mch_refund'] == 0 && $order['is_own'] == 2 && $order['pay_type'] == 'weixin'){</php>
                <li class="fr zlyd">{pigcms{:L('_CANNT_REFUND_C_S_')} 【{pigcms{$now_merchant.name}】</li>
                <php>}else{</php>

                <if condition="$order_details['paid'] eq 0">
                    <li class="fr replace" data-url="{pigcms{:U('Shop/orderdel', array('order_id' => $order_details['order_id']))}">{pigcms{:L('_CANCEL_ORDER_')}</li>
                    <elseif condition="$order_details['paid'] eq 1 AND $order_details['status'] lt 2" />
                    <li class="fr replace" data-url="{pigcms{:U('My/shop_order_refund', array('order_id' => $order_details['order_id']))}">{pigcms{:L('_CANCEL_ORDER_')}</li>
                    <elseif condition="$order_details['paid'] eq 1 AND $order_details['status'] eq 5" />
                    <li class="fr replace" data-url="{pigcms{:U('My/shop_order_refund', array('order_id' => $order_details['order_id']))}">{pigcms{:L('_REFUND_TXT_')}</li>
                </if>
                <php>}</php>
                <if condition="$order_details['status'] eq 2">
                    <li class="fl replace" data-url="{pigcms{:U('My/shop_feedback',array('order_id' => $order_details['order_id']))}">{pigcms{:L('_B_PURE_MY_73_')}</li>
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
<script>
    $(document).ready(function(){
        $('.consumes ul li').click(function(){
            location.href = $(this).data('url');
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
</script>