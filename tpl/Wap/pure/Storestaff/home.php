<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{pigcms{:L('_STORE_CENTER_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<style>
    #main{
        padding: 20px 5%;
        color: #666666;
        line-height: 16px;
        display: inline-block;
        font-size: 14px;
        width: 100%;
        box-sizing: border-box;
    }
</style>
<body>
<include file="header" />
<div id="main">
    <div style="text-align: center;font-size: 18px;">
        Hi,<label style="font-weight: bold">{pigcms{$store.name}</label>
    </div>
    <div style="text-align: center;font-size: 18px;margin-top: 30px">
        Your current store status:
        <if condition="$store['status'] eq 1">
            <label style="color: forestgreen">Active</label>
            <else />
            <label style="color: darkred">Inactive</label>
        </if>
    </div>
    <div class="home_list">
        <div class="dash_div">
            Delivery<br>Dashboard
        </div>
        <div class="menu_div">
            Menu/Product<br>Management
        </div>
        <div class="account_div">
            Account<br>Management
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    var new_img = "{pigcms{$static_path}images/new_order.png";
    var new_url = "{pigcms{:U('Storestaff/getNewOrder')}";
    var link_url = "{pigcms{:U('Storestaff/index')}";
    var sound_url = "{pigcms{$static_public}sound/soft-bells.mp3";
    var detail_url = "{pigcms{:U('Storestaff/getOrderDetail')}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/new_order.js?v=1.9"></script>
<script>
    var all_height = $(window).height();
    var all_width = $(window).width();
    if(all_height < all_width){
        $('.home_list').css('display','flex');
    }
    $('.dash_div').click(function () {
        window.location.href = "{pigcms{:U('Storestaff/index')}";
    });
    $('.menu_div').click(function () {
        window.location.href = "{pigcms{:U('Storestaff/manage_product')}";
    });
    $('.account_div').click(function () {
        window.location.href = "{pigcms{:U('Storestaff/manage_info')}";
    });
</script>

