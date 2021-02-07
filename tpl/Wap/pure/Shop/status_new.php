<!DOCTYPE html>
 <html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>{pigcms{$storeName.name}</title>
	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
	<meta name="description" content="{pigcms{$config.seo_description}" />
    <link href="{pigcms{$static_path}shop/css/order_detail.css" rel="stylesheet"/>
</head>

<body>
<section class="public">
    <a class="return link-url" href="javascript:window.history.go(-1);"></a>
    <div class="content">{pigcms{:L('_ORDER_STATUS_')}</div>
</section>
<div class="h44"></div>
<section class="g_details">
    <div class="orders_list">
        <ul>
            <volist name="status" id="vo">
            <li>11111
                <div class="time">{pigcms{$vo.dateline|date="Y-m-d H:i",###}</div>
                <div class="p18">
                    <div class="con">
                        <if condition="$vo['status'] eq 0"> <h2>{pigcms{:L('_ORDER_GENERATE_S_')}</h2> <p>{pigcms{:L('_B_PURE_MY_68_')}：{pigcms{$order.real_orderid}</p>
                        <elseif condition="$vo['status'] eq 1"/> <h2>{pigcms{:L('_ORDER_DEFRAY_S_')}</h2> <p>{pigcms{:L('_B_PURE_MY_68_')}：{pigcms{$order.real_orderid}</p>
                        <elseif condition="$vo['status'] eq 2"/> <h2>{pigcms{:L('_CLERK_ORDER_')}</h2> <p>{pigcms{:L('_CLERK_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a></p>
                        <elseif condition="$vo['status'] eq 3"/> <h2>{pigcms{:L('_DISTER_ORDER_')}</h2> <p>{pigcms{:L('_DISTOR_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>{pigcms{:L('_BEING_FOOD_FOR_Y_')}</p>
                        <elseif condition="$vo['status'] eq 4"/> <h2>{pigcms{:L('_DISTER_GET_FOOD_')}</h2> <p>{pigcms{:L('_DISTOR_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>{pigcms{:L('_DISTER_TO_SHOP_')}</p>
                        <elseif condition="$vo['status'] eq 5"/> <h2>{pigcms{:L('_DISTER_DISTING_')}</h2> <p>{pigcms{:L('_DISTOR_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>{pigcms{:L('_DISTER_QUICK_TO_Y_')}</p>
                        <elseif condition="$vo['status'] eq 6"/> <h2>{pigcms{:L('_DIST_END_')}</h2> <p>{pigcms{:L('_DISTOR_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>{pigcms{:L('_DIST_END_WEL_AGAIN_')}</p>
                        <elseif condition="$vo['status'] eq 7"/>
                            <if condition="$order['is_pick_in_store'] eq 3"><h2>{pigcms{:L('_CLERK_SHIP_GOODS_')}</h2> <p>{pigcms{:L('_CLERK_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> {pigcms{:L('_DELIV_TO_EXPREESS_')}<strong style="color:red">【{pigcms{$order['express_name']}】</strong>，快递单号:<strong style="color:green">{pigcms{$order['express_number']}</strong></p>
                            <else /><h2>{pigcms{:L('_VERIFYING_CONS_')}</h2> <p>{pigcms{:L('_CLERK_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>{pigcms{:L('_ORDER_CHANGE_CONS_')}</p>
                            </if>
                        <elseif condition="$vo['status'] eq 8"/> <h2>{pigcms{:L('_COMPLETE_REVIEW_')}</h2> <p>{pigcms{:L('_THANK_YOUR_VALU_')}</p>
                        <elseif condition="$vo['status'] eq 9"/> <h2>{pigcms{:L('_REFUNDS_COMPLETE_')}</h2> <p>{pigcms{:L('_COMPLETE_REFUND_')}</p>
                        <elseif condition="$vo['status'] eq 10"/> <h2>{pigcms{:L('_CANCELLATION_ORDER_')}</h2> <p>{pigcms{:L('_YOU_CANCEL_ORDER_')}</p>
                        <elseif condition="$vo['status'] eq 11"/> <h2>{pigcms{:L('_BUSINESS_ALLOCATION_')}</h2> <p>{pigcms{:L('_CLERK_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>{pigcms{:L('_GIVEN_YOU_PRO_')}</p>
                        <elseif condition="$vo['status'] eq 12"/> <h2>{pigcms{:L('_SHIP_TO_PRO_')}</h2> <p>{pigcms{:L('_CLERK_TXT_')}:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>{pigcms{:L('_SHIP_YOU_DIST_P_')}</p>
                        <elseif condition="$vo['status'] eq 13"/> <h2>{pigcms{:L('_PICKUP_GOODS_')}</h2> <p>{pigcms{:L('_SELF_LIFTING_')}:<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> {pigcms{:L('_RECE_YOUR_GOODS_')}</p>
                        <elseif condition="$vo['status'] eq 14"/> <h2>{pigcms{:L('_SELF_BEEN_DELI_')}</h2> <p>{pigcms{:L('_SELF_LIFTING_')}:<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> {pigcms{:L('_SHIPPED_TO_YOU_')}</p>
                        <elseif condition="$vo['status'] eq 15"/> <h2>{pigcms{:L('_YOU_PICKUP_GOODS_')}</h2> <p>{pigcms{:L('_SELF_LIFTING_')}:<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> {pigcms{:L('_YOU_PUTAWAY_GOODS_')}</p>
                        <elseif condition="$vo['status'] eq 30"/> <h2>{pigcms{:L('_CHANGE_PRICE_FOR_Y_')}</h2> <p>{pigcms{:L('_CLERK_TXT_')}:<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> {pigcms{:L('_MODIFIED_TOTAL_PRICE_')} {pigcms{$vo.note}</p>
                        </if>
                    </div>
                </div>
            </li>
            </volist>
        </ul>
    </div>
</section>
</body>
</html>