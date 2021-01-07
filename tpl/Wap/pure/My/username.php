<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_MODIFY_NICK_NAME_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.peter.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <include file="Public:facebook"/>
</head>
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

    .div_outer{
        display: -webkit-flex;
        display: flex;
        width: 90%;
        margin-left: 5%;
    }
    .div_inner{
        display: -webkit-flex;
        display: flex;
        width: 90%;
        margin-left: 5%;
    }
</style>
<body id="index">
    <include file="Public:header"/>
    <div class="main">
        <if condition="$error">
        	<div id="tips" class="tips tips-err" style="display:block;">{pigcms{$error}</div>
        <else/>
        	<div id="tips" class="tips"></div>
        </if>
        <div class="div-space"></div>
        <div class="div-space"></div>
        <form id="form" method="post" action="{pigcms{:U('My/username')}"  class="list_form">
		    <dl class="list" style="width: 90%">
		        <dd class="dd-padding">
		            <input id="username" placeholder="{pigcms{:L('_B_MY_ENTERNEWNAME_')}" class="input-weak" type="text" name="nickname" value="{pigcms{$now_user.nickname}">
		        </dd>
		    </dl>
		    <!--p class="btn-wrapper">{pigcms{:L('_NICKNAME_GZ_1_')} , {pigcms{:L('_NICKNAME_GZ_2_')}</p-->
            <div class="div-space"></div>
            <div class="div-space"></div>

            <div class="div_inner">
                <button type="submit" class="btn-whole-h btn-block btn-larger">{pigcms{:L('_B_D_LOGIN_CONIERM_')}</button>
            </div>
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
            $('#back_span').click(function () {
                window.history.go(-1);
            });
		</script>
    </div>
    <include file="Public:footer"/>
</body>
</html>