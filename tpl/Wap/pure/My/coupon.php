<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Coupon</title>
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
	</style>
</head>
<body>
    <include file="Public:header"/>
    <div class="main Coupon">
        <div class="this_nav">
            <span id="back_span"></span>
            Coupon
        </div>
        <div class="gray_line"></div>
        <ul class="end_ul">
            <a href="{pigcms{:U('card_list')}&coupon_type=system&use=0">
                <li>
                    <div>Available</div>
                </li>
            </a>
            <if condition="$coupon">
                <dl class="Muse">
                    <dd>
                        <div class="Coupon_top clr">
                            <div class="fl">
                                <div class="fltop">
                                    <i>$</i><em>{pigcms{$coupon.discount}</em>
                                </div>
                                <div class="flend">

                                </div>
                            </div>
                            <div class="fr">
                                <h2>{pigcms{$coupon.name} <php>if($_GET['coupon_type']=='mer'){</php>({pigcms{$coupon.merchant})<php>}</php></h2>
                                <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$coupon['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$coupon['discount'])}
                                <php>}else{</php>
                                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$coupon['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$coupon['order_money'])}
                                <php>}</php>
                            </div>
                        </div>

                        <div class="Coupon_end">
                            <div class="Coupon_x">
                                <i>{pigcms{$coupon.start_time|date='Y.m.d',###}--{pigcms{$coupon.end_time|date='Y.m.d',###}</i>
                                <a href="{pigcms{$coupon.url}"><em>{pigcms{:L('_IMMEDIATE_USE_')}</em></a>
                            </div>
                            <div class="Coupon_sm">
                                <span class="on">{pigcms{:L('_INSTRUCTIONS_TXT_')}</span>
                                <div class="Coupon_text overflow">{pigcms{$coupon.des}</div>
                            </div>
                        </div>
                        <span class="several">{pigcms{$coupon.get_num}</span>
                        <i class="bj"></i>
                    </dd>
                </dl>
            </if>
            <a href="{pigcms{:U('exchangeCode')}">
                <li>
                    <div>{pigcms{:L('_EXCHANGE_COUPON_')}</div>
                </li>
            </a>
            <a href="{pigcms{:U('card_list')}&coupon_type=system&use=1">
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
    $(".Coupon_sm").each(function(){
        $(this).find("span").click(function(){
            if($(this).hasClass("on")){
                $(this).removeClass("on")
                $(this).siblings(".Coupon_text").removeClass("overflow");
                $(this).parents("dd").siblings().find(".Coupon_sm span").addClass("on");
                $(this).parents("dd").siblings().find(".Coupon_sm .Coupon_text").addClass("overflow");
            }else{
                $(this).addClass("on")
                $(this).siblings(".Coupon_text").addClass("overflow");
            }

        })
    });
</script>
</body>
</html>