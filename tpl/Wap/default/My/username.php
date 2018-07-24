<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_MODIFY_NICK_NAME_')}</title>
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
        <form id="form" method="post" action="{pigcms{:U('My/username')}">
		    <dl class="list">
		        <dd class="dd-padding">
		            <input id="username" placeholder="{pigcms{:L('_B_MY_ENTERNEWNAME_')}" class="input-weak" type="text" name="nickname" value="{pigcms{$now_user.nickname}">
		        </dd>
		    </dl>
		    <p class="btn-wrapper">{pigcms{:L('_NICKNAME_GZ_1_')} , {pigcms{:L('_NICKNAME_GZ_2_')}</p>
		    <div class="btn-wrapper"><button type="submit" class="btn btn-block btn-larger">{pigcms{:L('_B_D_LOGIN_SUB_')}</button></div>
		</form>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			$(function(){
				$('#form').on('submit', function(e){
					$('#tips').removeClass('tips-err').hide();
			        var v = $('#username').val();
			        if(!/^([\u4E00-\uFA29]|[\uE7C7-\uE7F3]|[a-z])+/i.test(v)){
			            $('#tips').html("{pigcms{:L('_NICKNAME_GZ_1_')}").addClass('tips-err').show();
			            e.preventDefault();
			        }else if(v.length < 2 || v.length > 16){
			        	$('#tips').html("{pigcms{:L('_NICKNAME_GZ_2_')}").addClass('tips-err').show();
			            e.preventDefault();
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