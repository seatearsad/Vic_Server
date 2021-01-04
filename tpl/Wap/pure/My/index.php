<?php if (!defined('PigCms_VERSION')) {
    exit('deny access!');
} ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{pigcms{:L('_B_PURE_MY_33_')}</title>
    <meta name="viewport"
          content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width,viewport-fit=cover"/>
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
        .main {
            width: 100%;
            padding-top: 60px;
            max-width: 640px;
            min-width: 320px;
            margin: 0 auto;
        }

        .user_avatar {
            width: 90px;
            height: 90px;
            margin: 20px auto 0 auto;
            -moz-border-radius: 45px;
            -webkit-border-radius: 45px;
            border-radius: 45px;
            background-size: cover;
        }

        .user_avatar_default {
            width: 100px;
            height: 100px;
            margin: 20px auto 0 auto;
            background-size: cover;
            background-image: url("./tpl/Static/blue/images/wap/user.png");
        }

        .user_name {
            text-align: center;
            font-size: 1.2em;
            margin-top: 10px;
        }

        .gray_line {
            width: 100%;
            height: 2px;
            margin-top: 15px;
            background-color: #cccccc;
        }

        .obutton {
            width: 70px;
            background-color: #ffa52d;
            border-radius: 10px;
            color:#ffffff;
            padding: 5px 10px 5px 10px;
            border-color: #ffa52d;
            border-width: 0px;
        }

        .gray_k {
            width: 10%;
            height: 2px;
            background-color: #f4f4f4;
            margin: -2px auto 0 auto;
        }

        .main ul {
            margin: 20px 0 0;
            width: 100%;
        }

        .main ul li {
            width: 90%;
            height: 50px;
            margin-left: 5%;
            background-color: white;
            border-radius: 10px;
            list-style: none;
            margin-bottom: 10px;
            background-image: url("./tpl/Static/blue/images/new/icon_right_arrow.png");
            background-size: auto 20px;
            background-repeat: no-repeat;
            background-position: right 10px center;
        }

        .main ul li div {
            line-height: 50px;
            font-size: 1.4em;
            padding-left: 60px;
            background-size: auto 70%;
            background-repeat: no-repeat;
            background-position: 10px center;
        }

        .main ul a:nth-child(1) li div {
            background-image: url("./tpl/Static/blue/images/wap/profile.png");
        }

        /*.main ul a:nth-child(2) li div {*/
        /*background-image: url("./tpl/Static/blue/images/wap/balance.png");*/
        /*}*/

        .main ul a:nth-child(2) li div {
            background-image: url("./tpl/Static/blue/images/wap/coupon.png");
        }

        /*.main ul a:nth-child(4) li div {*/
        /*background-image: url("./tpl/Static/blue/images/wap/orders.png");*/
        /*}*/

        .main ul a:nth-child(3) li div {
            background-image: url("./tpl/Static/blue/images/wap/language.png");
        }

        #event_div {
            clear:both;
            width: 90%;
            margin-left: 5%;
            background-color: rgba(255, 165, 44, 0.21);
            margin-top: 5px;
            border-radius: 10px;
            border-style: solid;
            border-width: 2px;
            border-color: #ffa52d;
            padding: 5px 2% 5px 70px;
            background-image: url("./tpl/Static/blue/images/new/gift_icon.png");
            background-repeat: no-repeat;
            background-size: 55px auto;
            background-position: 10px 10px;
        }

        #event_name {
            font-weight: bold;
            font-size: 16px;
            color: #ffa52d;
        }

        #event_desc {
            font-size: 13px;
        }

        #courier_div {
            width: 90%;
            margin-left: 5%;
            background-color: rgba(1, 98, 255, 0.2);
            margin-top: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            border-style: solid;
            border-width: 2px;
            border-color: #013cff;
            padding: 5px 2% 5px 70px;
            background-image: url("./tpl/Static/blue/images/new/icon_deliver.png");
            background-repeat: no-repeat;
            background-size: 55px auto;
            background-position: 10px 10px;
        }

        #courier_name {
            font-weight: bold;
            font-size: 16px;
            color: #013cff;
        }

        #courier_desc {
            font-size: 13px;
        }

        .GreyText {
            color: #4e4d4d;
        }
        .LightGreyText {
            color: #919191;
        }
        .MidSizeText {
            font-size: 12px;
        }
        .NormalSizeText {
            font-size: 18px;
        }
        .BigSizeText {
            font-size: 26px;
            font-weight:bold;
        }
        .MainColorText{
            color: #ffa52d;
        }
        .div_h{
            float:left;
        }

        .right_align{
            position: absolute;
            right:5px;
        }
        .space_left{
            margin-left: 20px;
        }
        .space_left_2{
            margin-left: 50px;
        }
    </style>
    <include file="Public:facebook"/>
</head>
<body>
<include file="Public:header"/>
<div class="main">
    <div style="position:relative;width: 90%;margin-left: 5%;margin-top: 10px;margin-bottom: 10px;display: inline-block;">
        <div class="NormalSizeText">{pigcms{:replace_lang_str(L('_ND_HI_'),$now_user['nickname'])}
        </div>
        <div class="BigSizeText MainColorText div_h">{pigcms{$coupon_number}<div  class="LightGreyText MidSizeText">{pigcms{:L('QW_V2_COUPONS')}</div>
        </div>
        <div class="BigSizeText MainColorText div_h space_left"> ${pigcms{$now_user.now_money_two}<div  class="LightGreyText MidSizeText">{pigcms{:L('QW_V2_CREDITS')}</div>
        </div>
        <div class="right_align">
            <button class="obutton" type="button" onclick="window.location.href='./wap.php?g=Wap&c=My&a=recharge';">
                {pigcms{:L('_RECHARGE_TXT_')}
            </button>
        </div>
    </div>
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
        <!--        <a href="{pigcms{:U('My/my_money')}">-->
        <!--            <li>-->
        <!--                <div>Balance</div>-->
        <!--            </li>-->
        <!--        </a>-->
        <a href="{pigcms{:U('My/coupon')}">
            <li>
                <div>Coupon</div>
            </li>
        </a>
        <!--        <a href="{pigcms{:U('My/shop_order_list')}">-->
        <!--            <li>-->
        <!--                <div>Orders</div>-->
        <!--            </li>-->
        <!--        </a>-->
        <a href="{pigcms{:U('My/language')}">
            <li>
                <div>Language</div>
            </li>
        </a>
    </ul>
    <a href="https://www.tutti.app/wap.php?g=Wap&c=Index&a=courier">
        <div id="courier_div">
            <div id="courier_name">{pigcms{:L('QW_V2_COURIER_TITLE')}</div>
            <div id="courier_desc">{pigcms{:L('QW_V2_COURIER_DESC')}</div>
        </div>
    </a>
</div>
<include file="Public:footer"/>
<script type="text/javascript">
    window.zESettings = {
        webWidget: {
            color: {
                launcher: '#ffa52d', // This will also update the badge
                launcherText: '#ffffff',
            },
            mobile: {
                labelVisible: true
            },
            offset: {
                mobile: {
                    horizontal: '-10px',
                    vertical: '35px'
                }
            }
        }
    };
</script>
<!-- Start of tuttidelivery Zendesk Widget script -->
<script id="ze-snippet"
        src="https://static.zdassets.com/ekr/snippet.js?key=fe2c146c-36c1-4a86-807d-0ebeaa3d0a58"></script>
<!-- End of tuttidelivery Zendesk Widget script -->

<script type="text/javascript">
    zE('webWidget', 'identify', {
        name: "{pigcms{$now_user['nickname']}",
        email: "{pigcms{$now_user['email']}",
        phone: "{pigcms{$now_user['phone']}",
        organization: "User_{pigcms{$now_user['uid']}"
    });
    zE('webWidget', 'prefill', {
        name: {
            value: "{pigcms{$now_user['nickname']}"
        },
        email: {
            value: "{pigcms{$now_user['email']}"
        },
        phone: {
            value: "{pigcms{$now_user['phone']}"
        }
    });
</script>
</body>
</html>