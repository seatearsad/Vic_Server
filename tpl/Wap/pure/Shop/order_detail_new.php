<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{:L('_OUT_TXT_')} {pigcms{:L('_ORDER_DETAIL_')}</title>
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
    <body>
        <section class="public">
            <a class="return link-url" href="javascript:window.history.go(-1);"></a>
            <div class="content">{pigcms{:L('_ORDER_DETAIL_')}</div>
            <div class="ipho phone" data-phone="{pigcms{$store['phone']}"></div>
        </section>
        <if condition="$order_details['deliver_log_list']">
        <section class="defrayal">
            <div class="defrayal_n">
                <a href="{pigcms{:U('Shop/order_detail', array('order_id' => $order_details['order_id']))}">
                    <if condition="$order_details['deliver_log_list']['status'] eq 0"> <h2>{pigcms{:L('_ORDER_GENERATE_S_')}</h2> <p>{pigcms{:L('_B_PURE_MY_68_')}：{pigcms{$order_details.real_orderid}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 1"/> <h2>{pigcms{:L('_ORDER_DEFRAY_S_')}</h2> <p>{pigcms{:L('_B_PURE_MY_68_')}：{pigcms{$order_details.real_orderid}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 2"/> <h2>{pigcms{:L('_CLERK_ORDER_')}</h2> <p>{pigcms{:L('_BEING_FOOD_FOR_Y_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 3"/> <h2>{pigcms{:L('_DISTER_ORDER_')}</h2> <p>{pigcms{:L('_DISTER_TO_SHOP_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 4"/> <h2>{pigcms{:L('_DISTER_GET_FOOD_')}</h2> <p>{pigcms{:L('_DISTER_TO_YOU_WAIT_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 5"/> <h2>{pigcms{:L('_DISTER_DISTING_')}</h2> <p>{pigcms{:L('_DISTER_QUICK_TO_Y_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 6"/> <h2>{pigcms{:L('_DIST_END_')}</h2> <p>{pigcms{:L('_DIST_END_WEL_AGAIN_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 7"/>
                        <if condition="$order['is_pick_in_store'] eq 3">
                            <h2>{pigcms{:L('_CLERK_SHIP_GOODS_')}</h2>
                            <p>{pigcms{:L('_DELIV_TO_EXPREESS_')}
                                <strong style="color:red">【{pigcms{$order_details['express_name']}】</strong>
                                ，{pigcms{:L('_COURIER_NUMBER_')}:<strong style="color:green">{pigcms{$order_details['express_number']}</strong></p>
                        <else />
                            <h2>{pigcms{:L('_VERIFYING_CONS_')}</h2> <p>{pigcms{:L('_ORDER_CHANGE_CONS_')}</p>
                        </if>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 8"/> <h2>{pigcms{:L('_COMPLETE_REVIEW_')}</h2> <p>{pigcms{:L('_THANK_YOUR_VALU_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 9"/> <h2>{pigcms{:L('_REFUNDS_COMPLETE_')}</h2> <p>{pigcms{:L('_COMPLETE_REFUND_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 10"/> <h2>{pigcms{:L('_CANCELLATION_ORDER_')}</h2> <p>{pigcms{:L('_YOU_CANCEL_ORDER_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 11"/> <h2>{pigcms{:L('_BUSINESS_ALLOCATION_')}</h2> <p>{pigcms{:L('_GIVEN_YOU_PRO_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 12"/> <h2>{pigcms{:L('_SHIP_TO_PRO_')}</h2> <p>{pigcms{:L('_SHIP_YOU_DIST_P_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 13"/> <h2>{pigcms{:L('_PICKUP_GOODS_')}</h2> <p>{pigcms{:L('_RECE_YOUR_GOODS_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 14"/> <h2>{pigcms{:L('_SELF_BEEN_DELI_')}</h2> <p>{pigcms{:L('_SHIPPED_TO_YOU_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 15"/> <h2>{pigcms{:L('_YOU_PICKUP_GOODS_')}</h2> <p>{pigcms{:L('_YOU_PUTAWAY_GOODS_')}</p>
                    <elseif condition="$order_details['deliver_log_list']['status'] eq 30"/> <h2>{pigcms{:L('_CHANGE_PRICE_FOR_Y_')}</h2> <p>{pigcms{:L('_MODIFIED_TOTAL_PRICE_')} {pigcms{$order_details['deliver_log_list']['note']}</p>
                    </if>
                    <div class="time">{pigcms{$order_details['deliver_log_list']['dateline']|date="Y-m-d H:i",###}</div>
                    <em>{pigcms{:L('_MORE_STATUS_')}</em>
                </a>
            </div>
        </section>
        </if>
        <div class="h105"></div>
        <section class="g_details p40">
            
            <div class="infor">
                <ul>
                    <li class="first storext">
                        <a href="{pigcms{:U('Shop/index')}#shop-{pigcms{$store['store_id']}">
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
                            <if condition="!empty($goods['spec'])">
                            <p>{pigcms{$goods['spec']}</p>
                            </if>
                        </div>
                        <div class="clr fr right">
                            <div class="fl ride">x{pigcms{$goods['num']}</div>
                            <div class="fl del">${pigcms{$goods['total']}</div>
                            <div class="fl price">${pigcms{$goods['discount_total']}</div> 
                        </div>  
                    </dd>
                    </volist>
                </dl>
                <div class="mealfee">
                    <dl>
                        <dd class="clr">
                            <div class="fl">{pigcms{:L('_PACK_PRICE_')}</div>
                            <div class="fr">${pigcms{$order_details['packing_charge']}</div>
                        </dd>
                        <dd class="clr">
                            <div class="fl">{pigcms{:L('_DELI_PRICE_')}</div>
                            <div class="fr">${pigcms{$order_details['freight_charge']}</div>
                        </dd>
                        <dd class="clr">
                            <div class="fl">{pigcms{:L('_TAXATION_TXT_')}</div>
                            <div class="fr">5%</div>
                        </dd>
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
              
                    <div class="fl">{pigcms{:L('_ORDER_TXT_')} ${pigcms{$order_details['discount_price']|floatval} {pigcms{:L('_DISCOUNT_TXT_')}-${pigcms{$order_details['minus_price']|floatval}</div>
                    <div class="fr">{pigcms{:L('_TOTAL_RECE_')}: ${pigcms{$order_details['price']|floatval}</div>
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
                    <li class="clr">
                        <div class="fl">{pigcms{:L('_DIST_MODE_')}</div>
                        <div class="fr">{pigcms{:L('_PLAT_DIST_')}</div>
                    </li>
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
                        <dl class="kd_dl kd_dls">
                            <dd>
                                <h2 class="endt">{pigcms{:L('_DISTOR_TXT_')} ：{pigcms{$order_details['deliver_info']['name']} <a href="tel:{pigcms{$order_details['deliver_info']['phone']}">{pigcms{$order_details['deliver_info']['phone']}</a></h2>
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
        	                 <p class="kdsize">（{pigcms{:L('_BEFORE_MODIFY_')}：${pigcms{$order_details['change_price']|floatval}，{pigcms{:L('_NOTE_TXT_')}：{pigcms{$order_details['change_price_reason']}）</p>
        	                 <else />
        	                 <p class="e2c">${pigcms{$order_details['price']}</p>
        	                 </if>
        	             </div>
        	         </li>
        	         <if condition="$order_details['card_discount'] eq 0 OR $order_details['card_discount'] eq 10">
        	         <li class="clr">
        	             <div class="fl">{pigcms{:L('_BUSINESS_CARD_DIS_')}</div>
        	             <div class="p90">
                            <p class="e2c">-${pigcms{$order_details['minus_card_discount']}（{pigcms{:replace_lang_str(L('_NUM_DISCOUNT_'),$order_details['card_discount'])}）</p>
                            <p class="kdsize">（{pigcms{:L('_NOTE_NOT_TAKE_DIS_')}）</p>
                         </div>
        	         </li>
        	         </if>
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
        	         <if condition="$order_details['score_deducte'] gt 0">
                     <li class="clr">
                         <div class="fl">{pigcms{:L('_INTEGRAL_DED_')}</div>
                         <div class="fr e2c">-${pigcms{$order_details['score_deducte']|floatval}（使用{pigcms{$order_details['score_used_count']|floatval}积分）</div>
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
                            <li class="fr zlyd" data-url="{pigcms{:U('Shop/confirm_order', array('order_id' => $order_details['order_id'], 'store_id' => $store['store_id']))}">{pigcms{:L('_ONE_MORE_LIST_')}</li>
                        </if>
                    <else/>
                    <li class="fr zlyd" data-url="{pigcms{:U('Shop/confirm_order', array('order_id' => $order_details['order_id'], 'store_id' => $store['store_id']))}">{pigcms{:L('_ONE_MORE_LIST_')}</li>
                    </if>
                </ul>
            </div>
        </section>
    </body>
</html>

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
</script>