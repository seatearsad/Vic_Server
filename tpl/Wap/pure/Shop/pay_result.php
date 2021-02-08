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
        <ul>{pigcms{$order['real_orderid']}---{pigcms{$store['name']}
            <a href="http://www.vicisland.ca:8087/wap.php?g=Wap&c=Shop&a=order_detail&order_id={pigcms{$order_id}">订单</a>
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