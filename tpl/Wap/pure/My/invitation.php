<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Invitation Code</title>
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
    <link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/card_new.css"/>
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
            background-image: url("./tpl/Static/blue/images/new/black_arrow.php");
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
        .Coupon .Coupon_top{
            background-color: #ffa52d;
        }
        .Coupon .Coupon_end em{
            border: 1px solid #ffa52d;
            color: #ffa52d;
        }
        .Muse{
            width: 89%;
            margin: 10px auto;
        }
        #event_name{
            width: 90%;
            margin: 0px auto;
            font-weight: bold;
            font-size: 20px;
            text-align: center;
        }
        #event_img{
            width: 90%;
            margin: 10px auto;
            height: 130px;
            background-image: url("./tpl/Static/blue/images/new/coupon_icon.png");
            background-repeat: no-repeat;
            background-size: auto 100%;
            background-position: center;
        }
        #event_desc {
            width: 85%;
            margin: 10px auto;
        }
        #share_txt{
            width: 90%;
            margin: 0px auto;
            text-align: center;
        }
        #invi_code{
            width: 90%;
            margin: 0px auto;
            font-weight: bold;
            font-size: 20px;
            text-align: center;
            color: #ffa52d;
        }
        #invi_code input{
            text-align: center;
            color: #ffa52d;
            height: 35px;
            border: 1px #ffa52d dashed;
            border-radius: 5px;
            background-color: #f4f4f4;
        }
        #send_code{
            width: 80%;
            height: 35px;
            font-size: 18px;
            font-weight: bold;
            line-height: 35px;
            margin: 10px auto 60px auto;
            background-color: #ffa52d;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
        }
        #send_load{
            width: 80%;
            height: 35px;
            font-size: 18px;
            font-weight: bold;
            line-height: 35px;
            margin: 10px auto 60px auto;
            background-color: #999999;
            color: white;
            border-radius: 5px;
            text-align: center;
            display: none;
        }
        #send_div{
            width: 80%;
            margin: 10px auto;
        }
        #send_type{
            width: 100%;
            height: 36px;
        }
        #send_type li{
            display: inline;
            padding: 10px 20px;
            margin: 0;
            background: white;
            text-align: center;
            cursor: pointer;
        }
        #send_type li.active{
            background: #ffa52d;
            color: white;
        }
        #send_div input{
            width: 100%;
            line-height: 40px;
            margin: 5px auto;
        }
    </style>
    <include file="Public:facebook"/>
</head>
<body>
<include file="Public:header"/>
<div class="main">
    <div class="this_nav">
        <span id="back_span"></span>
    </div>
    <div id="event_name">
        {pigcms{$event.name}
    </div>
    <div id="event_img"></div>
    <div id="event_desc">
        {pigcms{$event.desc}
    </div>
    <div id="share_txt">
        {pigcms{:L('_SHARE_INVI_CODE_')}
    </div>
    <div id="invi_code">
        <input type="text" name="invi_code" value="{pigcms{$code}" readonly="readonly">
    </div>
    <div id="share_txt">
        or
    </div>
    <div id="send_div">
        <ul id="send_type">
            <li class="active">Email</li>
            <li>SMS</li>
        </ul>
        <input type="text" name="send_msg" id="send_msg" placeholder="Email Address">
    </div>
    <div id="send_code">
        {pigcms{:L('_SEND_INVI_CODE_')}
    </div>
    <div id="send_load">
        {pigcms{:L('_DEALING_TXT_')}
    </div>
</div>
<include file="Public:footer"/>
<script>
    if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
        $('#send_div').hide();
    }

    $('#back_span').click(function () {
        window.history.go(-1);
    });
    $('#send_code').click(function () {
        if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
            if (typeof (window.linkJs.send_invitation) != 'undefined') {
                window.linkJs.send_invitation("{pigcms{$send_msg}");
            }
        }else{
            if(send_type == 0){
                if(!checkMail($("input[name='send_msg']").val())){
                    alert("{pigcms{:L('_BACK_RIGHT_EMAIL_')}");
                }else{
                    var url = "{pigcms{:U('My/send_email_invi')}";
                }
            }else{
                if(!checkPhone($("input[name='send_msg']").val())){
                    alert("{pigcms{:L('_B_LOGIN_ENTERGOODNO_')}");
                }else{
                    var url = "{pigcms{:U('My/send_sms_invi')}";
                }
            }
            if(typeof (url) != 'undefined') {
                $('#send_code').hide();
                $('#send_load').show();
                $.post(url, {
                    'address': $("input[name='send_msg']").val(),
                    'code': "{pigcms{$code}",
                    'link': "{pigcms{$link}",
                    'amount':"{pigcms{$event.coupon_amount}"
                }, function (result) {
                    if (result.status == 1) {
                        alert('Success');
                    } else {
                        alert(result.msg);
                    }
                    $('#send_code').show();
                    $('#send_load').hide();
                }, 'json').error(function () {
                    alert('Error');
                    $('#send_code').show();
                    $('#send_load').hide();
                });
            }
        }
    });

    var send_type = 0;
    $('#send_type li').click(function () {
        if(send_type != $(this).index()){
            $('#send_type li').removeClass();
            $(this).addClass('active');
            send_type = $(this).index();

            if(send_type == 0)
                $('#send_msg').attr('placeholder','Email Address');
            else
                $('#send_msg').attr('placeholder','Phone Number');

        }
    });

    function checkPhone(phone) {
        if(!/^\d{10,}$/.test(phone)){
            return false;
        }
        return true;
    }
    function checkMail(mail) {
        var reg = /\w+[@]{1}\w+[.]\w+/;
        if(!reg.test(mail)){
            return false;
        }
        return true;
    }
</script>
</body>
</html>