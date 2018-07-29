<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_B_PURE_MY_55_')}</title>
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
        <form method="post" action="{pigcms{:U('My/password')}" id="form">
		    <dl class="list">
		    	<dd>
		    		<dl>
		    			<if condition="$now_user['pwd']">
			            	<dd class="dd-padding"><input class="input-weak" placeholder="{pigcms{:L('_INPUT_CURR_PASS_')}" type="password" id="currentpassword" name="currentpassword" autocomplete="off"></dd>
				        </if>
				        <dd class="dd-padding"><input class="input-weak" placeholder="{pigcms{:L('_INPUT_NEW_PASS_')}" type="password" id="password" name="password" autocomplete="off"></dd>
				        <dd class="dd-padding"><input class="input-weak" placeholder="{pigcms{:L('_INPUT_AGAIN_NEW_PASS_')}" type="password" id="password2" name="password2" autocomplete="off"></dd>
				    </dl>
		    	</dd>
		    </dl>
		    <div class="btn-wrapper">
				<button type="submit" class="btn btn-block btn-larger">{pigcms{:L('_B_D_LOGIN_SUB_')}</button>
		    </div>
		</form>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			$(function(){
				$('#form').submit(function(){
					$('#tips').removeClass('tips-err').hide();
			        var old_v = $("#currentpassword");
			        var new_v = $("#password");
			        var new_v2 = $("#password2");
			        if(old_v.size() > 0 && old_v.val().length < 6){
			        	$('#tips').html("pigcms{:L('_B_MY_WRONGKEY_')").addClass('tips-err').show();
			            return false;
				    }
			      	if(new_v.val().length < 6){
			      		$('#tips').html("{pigcms{:L('_NEW_PASS_ERROR_')}").addClass('tips-err').show();
			      		return false;
				    }
			      	if(new_v2.val() != new_v.val()){
			      		$('#tips').html("{pigcms{:L('_B_LOGIN_DIFFERENTKEY_')}").addClass('tips-err').show();
			      		return false;
				    }
			    });
			});
			function toast(msg){
				
			}
		</script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>