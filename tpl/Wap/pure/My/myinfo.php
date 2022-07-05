<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{:L('_B_PURE_MY_53_')}</title>
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
        .gray_space_v{
            width: 100%;
            margin-top: 25px;
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
            position: relative;
            width: 90%;
            height: 50px;
            margin-left: 5%;
            margin-bottom: 15px;
            background-color: white;
            list-style: none;
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
            border-radius: 10px;
            background-image: url("./tpl/Static/blue/images/new/icon_right_arrow.png");
            background-size: auto 16px;
            background-repeat: no-repeat;
            background-position:right 10px center;
        }
        .main ul li div{
            line-height: 50px;
            font-size: 1.1em;
            display:inline;
            padding-left: 20px;
            background-size: auto 70%;
            background-repeat: no-repeat;
            background-position: 10px center;
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
        #logout{
            width: 50%;
            height: 40px;
            background-color: rgba(156, 148, 153, 0.37);
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            margin: 20px auto 0 auto;
            background-size: auto 30px;
            text-align: center;
            color: #4e4d4d;
            font-size: 1.4em;
            line-height: 40px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .main ul li .right_align{
            position: absolute;
            font-size: 12px;
            color: #a3a3a3;
            right:30px;
        }
	</style>
        <include file="Public:facebook"/>
</head>
<body>
    <include file="Public:google"/>
    <include file="Public:header"/>
    <div class="main">
        <ul>
            <a href="{pigcms{:U('adress')}">
                <li>
                    <div>{pigcms{:L('_B_PURE_MY_87_')}</div>
                </li>
            </a>
            <a href="{pigcms{:U('credit')}">
                <li>
                    <div>{pigcms{:L('_B_PURE_MY_88_')}</div>
                </li>
            </a>
            <div class="gray_space_v"></div>
            <a href="{pigcms{:U('username')}">
                <li>
                    <div>{pigcms{:L('_B_PURE_MY_89_')}</div>
                    <div class="right_align">{pigcms{$now_user.nickname}</div>
                </li>
            </a>

            <li id="bind_phone">
                <div>{pigcms{:L('_B_PURE_MY_90_')}</div>
                <div class="right_align">{pigcms{$now_user.phone}</div>
            </li>
            <a href="{pigcms{:U('email')}">
            <li>
                <div>{pigcms{:L('_B_PURE_MY_91_')}</div>
                <div class="right_align">{pigcms{$now_user.email}</div>
            </li>
            </a>
            <a href="{pigcms{:U('password')}">
                <li>
                    <div>{pigcms{:L('_B_PURE_MY_92_')}</div>
                </li>
            </a>

            <a href="{pigcms{:U('invitation')}">
                <li>
                    <div>{pigcms{:L('_B_PURE_MY_93_')}</div>
                    <div class="right_align">{pigcms{$invitationcode}</div>

                </li>
            </a>
            <a href="{pigcms{:U('privacy')}">
                <li>
                    <div>{pigcms{:L('PRIVACY_TXT')}</div>
                </li>
            </a>
        </ul>

        <div id="logout">{pigcms{:L('_B_PURE_MY_94_')}</div>
    </div>
    <include file="Public:footer"/>
<script>
    $('#back_span').click(function () {
        window.history.go(-1);
    });

    var user_phone = "{pigcms{$now_user['phone']}";
    $("#bind_phone").click(function () {
        if(user_phone == ""){
            window.location.href = "{pigcms{:U(bind_user)}"
        }else{
            layer.open({
                content:"You cannot change the phone number linked to your account here. Please contact our support team to do so if necessary.",
                btn: ['Confirm']
            });
        }
    });

    $('#logout').on('click',function(){
        if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
            if (typeof (window.linkJs.delUser) != 'undefined') {
                window.linkJs.delUser();
            }
        }
        location.href =	"{pigcms{:U('Login/logout')}";
    });
</script>
</body>
</html>