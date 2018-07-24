<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{:L('_B_PURE_MY_53_')}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
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
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script>
    <style>
	    .titleImg{
			width:25px;
			height:25px;
			margin-right:10px;
	    }
	    .titleBorder{
			padding-bottom:10px;
			border-bottom:1px solid #e5e5e5;
	    }
	    .title{
			padding-top:12px;
			width:95%;
	    }
	    .imgRirht{
			float:right;
			margin-top:-19px;
			width:10px;
	    }
	</style>
</head>
<body>
	<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
		<div id="nickname" class="titleBorder">
			<div class="title">{pigcms{:L('_B_PURE_MY_54_')}<span style="float:right;">{pigcms{$now_user.nickname}</span></div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht" />
		</div>
		<div id="psword" class="titleBorder">
			<div class="title">{pigcms{:L('_B_PURE_MY_55_')}</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		<div id="bind_user" class="titleBorder">
			<div class="title"><if condition="$now_user['phone']">{pigcms{:L('_B_PURE_MY_56_')}<else />{pigcms{:L('_B_PURE_MY_57_')}</if><span style="float:right;">{pigcms{$now_user.phone_s}</span></div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		<if condition="!$_GET['type']">
			<div id="adress" class="titleBorder">
				<div class="title">{pigcms{:L('_B_PURE_MY_58_')}</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
			</div>
		</if>
		<!--div id="authentication" class="titleBorder">
			<div class="title">实名认证</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		<div id="car_owner" class="titleBorder">
			<div class="title">车主认证</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
		</div>
		<if condition="!$_GET['type']">
			<div id="cardcode" style="padding-bottom:10px;">
				<div class="title">我的实体卡</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht"></img>
			</div>
		</if-->
	</dl>
	<if condition="!$_GET['type']">
		<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
			<div id="about" style="padding-bottom:10px;">
				<div class="title">{pigcms{:L('_B_PURE_MY_ABOUTUS_')}</div>
				<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht" />
			</div>
		</dl>
	</if>
	
	
	<dl style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;">
		<div id="merchant" style="padding-bottom:10px;">
			<div class="title">{pigcms{:L('_B_PURE_MY_60_')}</div>
			<img src="{pigcms{$static_path}images/new_my/tubiao2_11.png" class="imgRirht" />
		</div>
	</dl>
		
	<button id="logout" style="padding:15px;width:90%;margin:10px 5%;background-color:#00c4ac;color:#fff;border:0px;">{pigcms{:L('_B_PURE_MY_61_')}</button>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			var find_car = "{pigcms{$find_car}";
			var find = "{pigcms{$find}";
			<if condition="$now_user['phone']">var change_phone=true;<else />var change_phone=false;</if>
			<if condition="C('config.bind_phone_verify_sms') AND C('config.sms_key')">var sms=true;<else />var sms=false;</if>
		</script>
		<script>
			$('#nickname').on('click',function(){
				location.href =	"{pigcms{:U('username')}";
			});
			$('#psword').on('click',function(){
				if(change_phone&&sms){
					location.href =	"{pigcms{:U('My/verify_original_phone')}&go=password";
				}else{
					location.href =	"{pigcms{:U('password')}";
				}
			});
			$('#bind_user').on('click',function(){
				if(change_phone&&sms){
					location.href =	"{pigcms{:U('My/verify_original_phone')}&go=bind_user";
				}else{
					location.href =	"{pigcms{:U('bind_user')}";
				}
			});
			$('#adress').on('click',function(){
				location.href =	"{pigcms{:U('adress')}";
			});
			$('#authentication').on('click',function(){
				if(find){
					location.href =	"{pigcms{:U('authentication_index')}";
				}else{
					location.href =	"{pigcms{:U('authentication')}";
				}
			});
			$('#car_owner').on('click',function(){
				if(find_car){
					location.href =	"{pigcms{:U('car_owner')}";
				}else{
					location.href =	"{pigcms{:U('car_apply')}";
				}
			});
			$('#cardcode').on('click',function(){
				location.href =	"{pigcms{:U('cardcode')}";
			});
			$('#phone').on('click',function(){
				location.href =	"{pigcms{:U('my_money')}";
			});
			$('#logout').on('click',function(){
				location.href =	"{pigcms{:U('Login/logout')}";
			});
			$('#about').on('click',function(){
				location.href =	"{pigcms{:U('My/about')}";
			});
			$('#merchant').on('click',function(){
				location.href =	"{pigcms{$merchant_url}";
			});
		</script>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>