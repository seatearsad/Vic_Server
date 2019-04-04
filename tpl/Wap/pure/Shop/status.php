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
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
</head>
<style>
    .main{
        width: 100%;
        padding-top: 60px;
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
    .orders_list li{
        margin-top: 0px;
    }
    .orders_list li .p18::after{
        background: #ffa52d;
    }
    .orders_list li .p18::before{
        content: none;
    }
    .orders_list li .p18 {
        padding: 10px 0px 10px 22px;
    }
    .orders_list li .p18 .con h2{
        color: #232323;
        font-size: 1.2em;
        font-weight: normal;
    }
    .orders_list li .p18 .con p{
        display: none;
    }
    .order_time{
        position: absolute;
        right: 10px;
        top:45px;
        color: silver;
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
    <div class="header_active">{pigcms{:L('_ORDER_STATUS_')}</div>
    <div><a href="{pigcms{:U('order_detail',array('order_id'=>$order_id))}">{pigcms{:L('_ORDER_INFO_TXT_')}</a></div>
</div>
<section class="g_details">
    <div class="orders_list">
        <ul>
            <volist name="status" id="vo">
                <li>
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
                        <div class="order_time">{pigcms{$vo.dateline|date="Y-m-d H:i",###}</div>
                    </div>
                </li>
            </volist>
        </ul>
    </div>
</section>
</div>
</body>
<script>
    $('#back_span').click(function () {
        window.location.href = "{pigcms{:U('My/shop_order_list')}";
    });
</script>
</html>