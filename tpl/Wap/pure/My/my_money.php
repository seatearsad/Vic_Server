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
        <link href="{pigcms{$static_path}css/eve.7c92a906.peter.css" rel="stylesheet"/>
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
            background-image: url("./tpl/Static/blue/images/new/icon_right_arrow.png");
            background-size: auto 16px;
            background-repeat: no-repeat;
            background-position:right 10px center;
            border-radius: 10px;

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
            color: #ffa52d;
            line-height: 40px;
        }
        .div_outer{
            display: -webkit-flex;
            display: flex;
            width: 90%;
            margin-left: 5%;
        }
        blance_title {
            line-height: 22px;
            font-weight: bold;
        }
        .blance_line{
            line-height: 20px;
        }
        a {
            color: #ffa52d;
        }
        input.input-radius {
            flex: 1 1 100%;
            border: 0;
            word-wrap: break-word;
            height: .9rem;
            margin: 0rem 0;
            text-indent: 0.2rem;
            line-height: 1;
            font-size: 16px;
            border-radius: 10px 0px 0px 10px;
        }
	</style>
        <include file="Public:facebook"/>
</head>
<body>
    <include file="Public:header"/>
    <div class="main">

        <if condition="$error">
            <div id="tips" class="tips tips-err" style="display:block;">{pigcms{$error}</div>
            <else/>
            <div id="tips" class="tips"></div>
        </if>
        <div class="my_money">
            ${pigcms{$now_user.now_money_two}
        </div>

        <form id="form" method="post" action="{pigcms{:U('My/recharge')}">
            <input type="hidden" name="label" value="{pigcms{$_GET.label}"/>
            <div class="div_outer">
                <input id="money" placeholder="{pigcms{:L('_P_INPUT_RECHARGE_')}" class="input-radius" type="text" name="money" value="{pigcms{$_GET.money}" <if condition="$_GET['label'] && $_GET['money']">readonly="readonly" onclick="$('#tips').html('订单充值时无法修改金额！').show();"</if>/>
                <button type="submit" class="btn btn-inline btn-larger2">{pigcms{:L('V2_PAGETITLE_ADDUP')}</button>
            </div>
        </form>
        <div class="div_outer">
<!--            <p class="btn-wrapper">{pigcms{:L('_AMOUNT_TWO_DEC_')}</p>-->
            <div class="btn-wrapper">
                <div class="blance_title">{pigcms{:L('Balance_pro')}：</div>
                <php>
                    foreach($recharge_list as $k=>$v){
                </php>
                <div class="blance_line">
                    <php>
                        echo L('Deposit_txt')." $".$k.' '.L('Earn_txt')." $".$v;
                    </php>
                </div>
                <php>
                    }
                </php>
            </div>
        </div>

        <ul>
            <a href="{pigcms{:U('transaction')}">
            <li>
                <div>History</div>
            </li>
            </a>
        </ul>
    </div>
    <include file="Public:footer"/>
<script>
    $(function(){
        $('#form').on('submit', function(e){
            $('#tips').removeClass('tips-err').hide();
            var money = parseFloat($('#money').val());
            if(isNaN(money)){
                $('#tips').html("{pigcms{:L('_ENTER_LEGAL_AMOUNT_')}").addClass('tips-err').show();
                e.preventDefault();
                return false;
            }else{
                var dian=money.toString().split(".")[1].length;
                if(dian>2){
                    $('#tips').html("{pigcms{:L('_ENTER_LEGAL_AMOUNT_')}").addClass('tips-err').show();
                    e.preventDefault();
                    return false;
                }else{
                    if(money > 10000){
                        $('#tips').html("{pigcms{:L('_RECHARGE_TEN_TH_')}").addClass('tips-err').show();
                        e.preventDefault();
                        return false;
                    }else if(money < 0.1){
                        $('#tips').html("{pigcms{:L('_RECHARGE_POINTONE_')}").addClass('tips-err').show();
                        e.preventDefault();
                        return false;
                    }
                }

            }
        });
    <if condition="$_GET['label'] && $_GET['money']">
            /* layer.open({type: 2,content: "{pigcms{:L('_AUTO_SUBMIT_WAIT_')}",shadeClose:false}); */
            $('#form').trigger('submit');
    </if>
    });
    $('#logout').on('click',function(){
        location.href =	"{pigcms{:U('Login/logout')}";
    });
</script>
</body>
</html>