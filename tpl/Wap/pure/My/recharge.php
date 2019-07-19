<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_RECHARGE_TXT_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <include file="Public:facebook"/>
</head>
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
    .btn-larger{
        background-color: #ffa52d;
        width: 50%;
        margin: auto;
    }
</style>
<body id="index">
<include file="Public:header"/>
<div class="main">
    <div class="this_nav">
        <span id="back_span"></span>
        Balance
    </div>
    <div class="gray_line"></div>
        <if condition="$error">
        	<div id="tips" class="tips tips-err" style="display:block;">{pigcms{$error}</div>
        <else/>
        	<div id="tips" class="tips"></div>
        </if>
        <form id="form" method="post" action="{pigcms{:U('My/recharge')}">
			<input type="hidden" name="label" value="{pigcms{$_GET.label}"/>
		    <dl class="list">
		        <dd class="dd-padding">
		            <input id="money" placeholder="{pigcms{:L('_P_INPUT_RECHARGE_')}" class="input-weak" type="text" name="money" value="{pigcms{$_GET.money}" <if condition="$_GET['label'] && $_GET['money']">readonly="readonly" onclick="$('#tips').html('订单充值时无法修改金额！').show();"</if>/>
		        </dd>
		    </dl>
		    <p class="btn-wrapper">{pigcms{:L('_AMOUNT_TWO_DEC_')}</p>
            <div class="btn-wrapper">
                <div>{pigcms{:L('Balance_pro')}：</div>
                <php>
                    foreach($recharge_list as $k=>$v){
                </php>
                <div>
                    <php>
                        echo L('Deposit_txt')." $".$k.' '.L('Earn_txt')." $".$v;
                    </php>
                </div>
                <php>
                    }
                </php>
            </div>
		    <div class="btn-wrapper"><button type="submit" class="btn btn-block btn-larger">{pigcms{:L('_RECHARGE_TXT_')}</button></div>
		</form>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			$(function(){
				$('#form').on('submit', function(e){
					$('#tips').removeClass('tips-err').hide();
					var money = parseFloat($('#money').val());
					if(isNaN(money)){
						$('#tips').html("{pigcms{:L('_ENTER_LEGAL_AMOUNT_')}").addClass('tips-err').show();
			            e.preventDefault();
						return false;
					}else if(money > 10000){
						$('#tips').html("{pigcms{:L('_RECHARGE_TEN_TH_')}").addClass('tips-err').show();
			            e.preventDefault();
						return false;
					}else if(money < 0.1){
						$('#tips').html("{pigcms{:L('_RECHARGE_POINTONE_')}").addClass('tips-err').show();
			            e.preventDefault();
						return false;
					}
			    });		
				<if condition="$_GET['label'] && $_GET['money']">
					/* layer.open({type: 2,content: "{pigcms{:L('_AUTO_SUBMIT_WAIT_')}",shadeClose:false}); */
					$('#form').trigger('submit');
				</if>
			});
            $('#back_span').click(function () {
                window.history.go(-1);
            });
		</script>
</div>
		<include file="Public:footer"/>
</body>
</html>