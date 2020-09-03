<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Balance</title>
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
        .my_money{
            width: 100%;
            text-align: center;
            margin: 40px auto;
            font-size: 4em;
            line-height: 40px;
        }
	</style>
        <include file="Public:facebook"/>
</head>
<body>
    <include file="Public:header"/>
    <div class="main">
        <div class="this_nav">
            <span id="back_span"></span>
            Balance
        </div>
        <div class="gray_line"></div>
        <div class="my_money">
            <img src="./tpl/Static/blue/images/wap/dollar.png" width="40">
            {pigcms{$now_user.now_money_two}
        </div>
        <ul>
            <a href="{pigcms{:U('recharge')}">
                <li>
                    <div>Add Money</div>
                </li>
            </a>
            <a href="{pigcms{:U('transaction')}">
            <li>
                <div>History</div>
            </li>
            </a>
        </ul>
    </div>
    <include file="Public:footer"/>
<script>
    $('#back_span').click(function () {
        window.history.go(-1);
    });
    $('#logout').on('click',function(){
        location.href =	"{pigcms{:U('Login/logout')}";
    });
</script>
</body>
</html>