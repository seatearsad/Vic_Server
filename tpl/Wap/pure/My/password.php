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
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
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
        margin-bottom: 15px;
        background-color: #cccccc;
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
    .btn{
        background-color: #ffa52d;
        width: 50%;
        margin: 0 auto;
    }
</style>
<body id="index">
<include file="Public:header"/>
    <div class="main">
        <div class="this_nav">
            <span id="back_span"></span>
            {pigcms{:L('_B_D_LOGIN_KEY1_')}
        </div>
        <div class="gray_line"></div>
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
				<button type="submit" class="btn btn-block btn-larger">{pigcms{:L('_B_D_LOGIN_CONIERM_')}</button>
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
            $('#back_span').click(function () {
                window.history.go(-1);
            });
		</script>
</div>
		<include file="Public:footer"/>
</body>
</html>