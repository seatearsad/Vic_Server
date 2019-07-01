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
            background-color: #ffa52d;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            margin: 20px auto 0 auto;
            background-image: url("./tpl/Static/blue/images/wap/logout.png");
            background-size: auto 30px;
            background-repeat: no-repeat;
            background-position: 10px center;
            text-align: center;
            color: white;
            font-size: 1.4em;
            font-weight: bold;
            line-height: 40px;
            cursor: pointer;
        }
	</style>
</head>
<body>
    <include file="Public:header"/>
    <div class="main">
        <div class="this_nav">
            <span id="back_span"></span>
            Profile
        </div>
        <div class="gray_line"></div>
        <ul>
            <a href="{pigcms{:U('username')}">
                <li>
                    <div>{pigcms{:L('_B_PURE_MY_54_')}</div>
                </li>
            </a>
            <a href="{pigcms{:U('password')}">
            <li>
                <div>Password</div>
            </li>
            </a>
            <a href="{pigcms{:U('bind_user')}">
            <li>
                <div>Phone Number</div>
            </li>
            </a>
            <a href="{pigcms{:U('email')}">
            <li>
                <div>Email</div>
            </li>
            </a>
            <a href="{pigcms{:U('adress')}">
            <li>
                <div>Address</div>
            </li>
            </a>
            <a href="{pigcms{:U('credit')}">
            <li>
                <div>Wallet</div>
            </li>
            </a>
        </ul>

        <div id="logout">Log out</div>
    </div>
    <include file="Public:footer"/>
<script>
    $('#back_span').click(function () {
        window.history.go(-1);
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