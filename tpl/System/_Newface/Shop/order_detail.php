<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                <tr>
                                    <th class="text-center">
                                        {pigcms{:L('_BACK_ITEM_')}<br>
                                        <small></small>
                                    </th>
                                    <th class="text-center">
                                        {pigcms{:L('_BACK_RATE_')}<br>
                                        <small></small>
                                    </th>
                                    <th class="text-center">
                                        {pigcms{:L('_BACK_QUANTITY_')}<br>
                                        <small></small>
                                    </th>
                                    <th class="text-center">
                                        {pigcms{:L('_BACK_TAX_')}<br>
                                        <small></small>
                                    </th>
                                    <th class="text-center">
                                        {pigcms{:L('_STORE_PRODUCT_DEPOSIT_')}<br>
                                        <small></small>
                                    </th>
                                    <th class="text-center">
                                        {pigcms{:L('_BACK_REQUEST_')}<br>
                                        <small></small>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <volist name="order['info']" id="vo">
                                <tr>
                                    <th class="text-center">{pigcms{$vo['name']}</th>
                                    <th class="text-center">${pigcms{$vo['price']|floatval}</th>
                                    <th class="text-center">{pigcms{$vo['num']} / {pigcms{$vo['unit']}</th>
                                    <th class="text-center">{pigcms{$vo['tax_num']}%</th>
                                    <th class="text-center">${pigcms{$vo['deposit_price']}</th>
                                    <th class="text-center">{pigcms{$vo['spec']}</th>
                                </tr>
                                </volist>
                                <if condition="($order.status eq 0 OR $order.status eq 5) AND $order.paid eq 1">
                                    <tr>
                                        <th colspan="6"><a href="javascript:void(0)" onclick="refund_confirm();"><font
                                                        color="blue">{pigcms{:L('_BACK_MANUAL_REFUND_')}</font></a></th>
                                    </tr>
                                    <else/>
                                    <tr>
                                        <th colspan="6">&nbsp;</th>
                                    </tr>
                                </if>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_ORDER_NUM_')}</th>
                                    <td colspan="5">{pigcms{$order['real_orderid']}</td>
                                </tr>

                                <if condition="$order['shop_pass']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">Order ID</th>
                                        <th colspan="5">{pigcms{$order['order_id']}</th>
                                    </tr>
                                </if>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_USER_NAME_')}</th>
                                    <td colspan="5">{pigcms{$order['username']}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_USER_PHONE_')}</th>
                                    <td colspan="5">{pigcms{$order['userphone']}</td>
                                </tr>
                                <if condition="$order['register_phone']">
                                    <tr style="color:red">
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_REG_NUM_')}</th>
                                        <td colspan="5">{pigcms{$order['register_phone']}</td>
                                    </tr>
                                </if>
                                <if condition="$order['is_pick_in_store'] eq 2 or $order['order_type'] eq 1">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_SELF_LIFT_ADDRESS_')}</th>
                                        <td colspan="5">
                                            {pigcms{$order['store_address']}
                                        </td>
                                    </tr>
                                    <else/>
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_CUSTOM_ADD_')}</th>
                                        <td colspan="5">
                                            {pigcms{$order['address']}<if condition="$order['address_detail'] neq ''">&nbsp;- {pigcms{$order['address_detail']}</if>
                                        </td>
                                    </tr>
                                </if>
                                <!--tr>
                                    <th colspan="6">配送方式：{pigcms{$order['deliver_str']}</th>
                                </tr-->
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>
                                    <td colspan="5">
                                        <if condition="$order['order_type'] eq 0">
                                            {pigcms{$order['deliver_status_str']}
                                            <else />
                                            -
                                        </if>
                                    </td>
                                </tr>
                                <if condition="$order['is_pick_in_store'] eq 3 AND $order['express_id']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">快递公司</th>
                                        <td colspan="5">{pigcms{$order['express_name']}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap" scope="row">快递单号</th>
                                        <td colspan="5">{pigcms{$order['express_number']}</td>
                                    </tr>
                                </if>
                                <if condition="$order['deliver_user_info']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_COURIER_NICK_')}</th>
                                        <td colspan="5">{pigcms{$order['deliver_user_info']['name']}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_COURIER_PHONE_')}</th>
                                        <td colspan="5">{pigcms{$order['deliver_user_info']['phone']}</td>
                                    </tr>
                                </if>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_ORDER_TIME_')}</th>
                                    <td colspan="5">{pigcms{$order['create_time']|date="Y-m-d H:i:s",###}</td>
                                </tr>
                                <if condition="$order['pay_time']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_PAY_TIME_')}</th>
                                        <td colspan="5">{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###}</td>
                                    </tr>
                                </if>
                                <if condition="$order['expect_use_time'] and $order['order_type'] eq 0">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_EXPECTED_TIME_')}</th>
                                        <td colspan="5">{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td>
                                    </tr>
                                </if>
                                <if condition="$order['order_type'] eq 1">
                                    <tr>
                                        <th class="text-nowrap" scope="row">Merchant Confirmation</th>
                                        <td colspan="5">
                                            <if condition="$order['confirm_time']">{pigcms{$order['confirm_time']|date="Y-m-d H:i:s",###}</if>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap" scope="row">Expected Ready Time</th>
                                        <td colspan="5">
                                            <if condition="$order['pickup_time']">{pigcms{$order['pickup_time']|date="Y-m-d H:i:s",###}</if>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap" scope="row">Pickup Confirmation</th>
                                        <td colspan="5">
                                            <if condition="$order['ready_time']">{pigcms{$order['ready_time']|date="Y-m-d H:i:s",###}</if>
                                        </td>
                                    </tr>
                                </if>
                                <if condition="$order['use_time']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_ARR_TIME_')}</th>
                                        <td colspan="5">{pigcms{$order['use_time']|date="Y-m-d H:i:s",###}</td>
                                    </tr>
                                </if>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_SUBTOTAL_')}</th>
                                    <td colspan="5">${pigcms{$order['goods_price']|floatval}
                                        <if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">
                                            +{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}
                                        </if>
                                    </td>
                                </tr>
                                <if condition="$order['packing_charge'] gt 0">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_PACK_PRICE_')}</th>
                                        <td colspan="5">${pigcms{$order['packing_charge']|floatval}</td>
                                    </tr>
                                </if>

                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_DELI_PRICE_')}</th>
                                    <td colspan="5">${pigcms{$order['freight_charge']|floatval}</td>
                                </tr>

                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_TAX_')}</th>
                                    <td colspan="5">${pigcms{$order['tax_price']|floatval}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_STORE_PRODUCT_DEPOSIT_')}</th>
                                    <td colspan="5">${pigcms{$order['deposit_price']|floatval}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap" scope="row">Service Fee</th>
                                    <td colspan="5">${pigcms{$order['service_fee']|floatval}</td>
                                </tr>

                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_ORDER_TOTAL_AMOUNT_')}</th>
                                    <td colspan="5">${pigcms{$order['total_price']|floatval}
                                        <if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">
                                            +{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}
                                        </if>
                                        (Order amount does NOT include any discounts or tips)
                                    </td>
                                </tr>
                                <if condition="$order['merchant_reduce'] gt 0">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_STORE_DIS_')}</th>
                                        <td colspan="5">${pigcms{:L('_STORE_DIS_')}：${pigcms{$order['merchant_reduce']|floatval}</td>
                                    </tr>
                                </if>
                                <if condition="$order['balance_reduce'] gt 0">
                                    <tr>
                                        <th class="text-nowrap" scope="row">平台优惠</th>
                                        <td colspan="5">${pigcms{$order['balance_reduce']|floatval}</td>
                                    </tr>
                                </if>
                                <if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
                                    <tr>
                                        <th class="text-nowrap" scope="row">会员卡</th>
                                        <td colspan="5">{pigcms{$order['card_discount']|floatval} 折优惠</td>
                                    </tr>
                                </if>

                                <if condition="$order['score_used_count']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">使用{pigcms{$config.score_name}</th>
                                        <td colspan="5">{pigcms{$order['score_used_count']}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap" scope="row">抵现</th>
                                        <td colspan="5">${pigcms{$order['score_deducte']|floatval}</td>
                                    </tr>
                                </if>

                                <if condition="$order['card_give_money'] gt 0">
                                    <tr>
                                        <th class="text-nowrap" scope="row">会员卡余额</th>
                                        <td colspan="5">${pigcms{$order['card_give_money']|floatval}</td>
                                    </tr>
                                </if>

                                <if condition="$order['merchant_balance'] gt 0">
                                    <tr>
                                        <th class="text-nowrap" scope="row">商家余额</th>
                                        <td colspan="5">${pigcms{$order['merchant_balance']|floatval}</td>
                                    </tr>
                                </if>
                                <if condition="$order['balance_pay'] gt 0">
                                    <!--tr>
                                        <th colspan="6">{pigcms{:L('_BACK_TUTTI_CREDIT_')}：${pigcms{$order['balance_pay']|floatval}</th>
                                    </tr-->
                                </if>
                                <if condition="$order['payment_money'] gt 0">
                                    <!--tr>
                                        <th colspan="6">{pigcms{:L('_ONLINE_PAY_')}：${pigcms{$order['payment_money']|floatval}</th>
                                    </tr-->
                                </if>
                                <if condition="$order['card_id']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">店铺优惠券金额</th>
                                        <td colspan="5">${pigcms{$order['card_price']}</td>
                                    </tr>
                                </if>
                                <if condition="$order['coupon_id']">
                                    <tr>
                                        <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_SYS_CON_PRICE_')}</th>
                                        <td colspan="5">${pigcms{$order['coupon_price']} (ID &nbsp;{pigcms{$order['coupon_id2']})</td>
                                    </tr>
                                </if>

                                <tr style="color: black">
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_ACT_AM_PAID_')}</th>
                                    <td colspan="5">${pigcms{$order['price']+$order['tip_charge']-$order['coupon_price']-$order['merchant_reduce']-$order['delivery_discount']|floatval}</td>
                                </tr>

                                <!--if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])">
                                <tr>
                                    <th colspan="6">{pigcms{:L('_BACK_PAY_OFFLINE_')}：${pigcms{$order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-$order['coupon_price']|floatval}元</th>
                                </tr>
                                </if-->
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_PAYMENT_STATUS_')}</th>
                                    <td colspan="5">{pigcms{$order['pay_status']}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_PAYMENT_METHOD_')}</th>
                                    <td colspan="5">{pigcms{$order['pay_type_str']}</td>
                                </tr>
                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_ORDER_STATUS_')}</th>
                                    <td colspan="5">{pigcms{$order['status_str']}
                                        <if condition="$order['status'] eq 4">&nbsp;&nbsp;&nbsp;&nbsp;{pigcms{:L('_BACK_REFUND_TIME_')}:{pigcms{$order['last_time']|date="Y-m-d
                                            H:i:s",###}
                                        </if>
                                    </td>
                                </tr>

                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_NOTE_MERCHANT_')}</th>
                                    <td colspan="5">{pigcms{$order['desc']|default="N/A"}</td>
                                </tr>

                                <tr>
                                    <th class="text-nowrap" scope="row">{pigcms{:L('_BACK_RECEIPT_')}</th>
                                    <td colspan="5"> <if condition="$order['invoice_head']">{pigcms{$order['invoice_head']}<else/>N/A</if></td>
                                </tr>
                                <if condition="$order['deliver_user_info']['photo'] neq ''">
                                    <tr>
                                        <th class="text-nowrap" scope="row">Delivery Photo</th>
                                        <td colspan="5">
                                            <a href="{pigcms{$order['deliver_user_info']['photo']}" target="_blank" style="color: black;text-decoration: underline;font-weight: bold;">VIEW</a>
                                        </td>
                                    </tr>
                                </if>
                                <if condition="$order['cue_field']">
                                    <tr>
                                        <th colspan="6">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th colspan="6"><strong>分类填写字段</strong></th>
                                    </tr>
                                    <volist name="order['cue_field']" id="vo">
                                        <tr>
                                            <th colspan="1">{pigcms{$vo.title}</th>
                                            <th colspan="5">{pigcms{$vo.txt}</th>
                                        </tr>
                                    </volist>
                                </if>

                                </tbody>
                            </table>
                        </div>
                        <script src="{pigcms{$static_public}js/layer/layer.js"></script>

                    </div>
                </div>
            </div>
        </div>
        <script>
            function refund_confirm() {
                layer.confirm('确认后订单状态改为已退款，金额请通过其他渠道手动退款给客户！', {
                    btn: ['确定', '取消'] //按钮
                }, function () {
                    window.location.href = '{pigcms{:U('
                    Shop / refund_update
                    ',array('
                    order_id
                    '=>$order['
                    order_id
                    ']))}';
                });
                //
            }
        </script>
        <include file="Public:footer_inc"/>