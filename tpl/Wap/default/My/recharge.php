<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_RECHARGE_TXT_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
</head>
<body id="index">
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
		</script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>