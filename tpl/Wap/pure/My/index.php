<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{:L('_B_PURE_MY_33_')}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width,viewport-fit=cover"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<!--script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script-->

		<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
    <style>
        .main{
            width: 100%;
            padding-top: 60px;
        }
        .user_avatar{
            width: 90px;
            height: 90px;
            margin: 20px auto 0 auto;
            -moz-border-radius: 45px;
            -webkit-border-radius: 45px;
            border-radius: 45px;
            background-size: cover;
        }
        .user_avatar_default{
            width: 100px;
            height: 100px;
            margin: 20px auto 0 auto;
            background-size: cover;
            background-image: url("./tpl/Static/blue/images/wap/user.png");
        }
        .user_name{
            text-align: center;
            font-size: 1.2em;
            margin-top: 10px;
        }
        .gray_line{
            width: 100%;
            height: 2px;
            margin-top: 15px;
            background-color: #cccccc;
        }
        .gray_k{
            width: 10%;
            height: 2px;
            background-color: #f4f4f4;
            margin: -2px auto 0 auto;
        }
        .main ul{
            margin: 20px 0 0;
            width: 100%;
        }
        .main ul li{
            width: 90%;
            height: 50px;
            margin-left: 5%;
            background-color: white;
            list-style: none;
            margin-bottom: 10px;
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 16px;
            background-repeat: no-repeat;
            background-position:right 10px center;
        }
        .main ul li div{
            line-height: 50px;
            font-size: 1.4em;
            padding-left: 60px;
            background-size: auto 70%;
            background-repeat: no-repeat;
            background-position: 10px center;
        }
        .main ul a:nth-child(1) li div{
            background-image: url("./tpl/Static/blue/images/wap/profile.png");
        }
        .main ul a:nth-child(2) li div{
            background-image: url("./tpl/Static/blue/images/wap/balance.png");
        }
        .main ul a:nth-child(3) li div{
            background-image: url("./tpl/Static/blue/images/wap/coupon.png");
        }
        .main ul a:nth-child(4) li div{
            background-image: url("./tpl/Static/blue/images/wap/orders.png");
        }
        .main ul a:nth-child(5) li div{
            background-image: url("./tpl/Static/blue/images/wap/language.png");
        }

        #event_div{
            width: 90%;
            height: 80px;
            margin-left: 5%;
            background-color: white;
            margin-top: 10px;
            border-radius: 10px;
            padding: 10px 2% 10px 70px;
            background-image: url("./tpl/Static/blue/images/new/gift_icon.png");
            background-repeat: no-repeat;
            background-size: 55px auto;
            background-position: 10px 10px;
        }
        #event_name{
            font-weight: bold;
            font-size: 18px;
        }
	</style>
        <include file="Public:facebook"/>
</head>
<body>
    <include file="Public:header"/>
	<div class="main">
        <if condition="$now_user['avatar']">
            <div class="user_avatar" style='background-image: url("{pigcms{$now_user['avatar']}")'></div>
        <else />
            <div class="user_avatar_default"></div>
        </if>
        <div class="user_name">{pigcms{$now_user['nickname']}</div>
        <div class="gray_line"></div>
        <div class="gray_k"></div>
        <if condition="$event">
            <a href="{pigcms{:U('My/invitation')}">
                <div id="event_div">
                    <div id="event_name">{pigcms{$event.name}</div>
                    <div id="event_desc">{pigcms{$event.desc}</div>
                </div>
            </a>
        </if>
        <ul>
            <a href="{pigcms{:U('My/myinfo')}">
            <li>
                <div>Profile</div>
            </li>
            </a>
            <a href="{pigcms{:U('My/my_money')}">
            <li>
                <div>Balance</div>
            </li>
            </a>
            <a href="{pigcms{:U('My/coupon')}">
            <li>
                <div>Coupon</div>
            </li>
            </a>
            <a href="{pigcms{:U('My/shop_order_list')}">
            <li>
                <div>Orders</div>
            </li>
            </a>
            <a href="{pigcms{:U('My/language')}">
            <li>
                <div>Language</div>
            </li>
            </a>
        </ul>
    </div>
	<include file="Public:footer"/>
</body>
</html>