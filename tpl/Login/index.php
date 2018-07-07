<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>后台系统管理中心 - {pigcms{$config.site_name} </title>
<script type="text/javascript" src="{pigcms{$static_path}newlogin/jquery.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}newlogin/common.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}newlogin/jquery.tscookie.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}newlogin/jquery.validation.min.js"></script>
<link href="{pigcms{$static_path}newlogin/login.css" rel="stylesheet" type="text/css">
		<!-- 为使用方便，直接使用jquery.js库 -->
		<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
		<!-- 引入封装了failback的接口--initGeetest -->
		<script src="http://static.geetest.com/static/tools/gt.js"></script>
<style type="text/css">
.notice{position:absolute;z-index:1000;letter-spacing:2px;top:5px;left:50%;margin-left:-90px;padding:5px 20px 5px 10px;font-weight:bold;border:1px solid gray;color:blue;background:#FFF;background-position:3px 40%;font-family:微软雅黑,Tahoma,Helvetica,sans;-moz-border-radius:8px;-webkit-border-radius:8px;border-radius:8px;}
</style>
<style type="text/css">
body {
	background-color: #666666;
	background-image: url("");
	background-repeat: no-repeat;
	background-position: center top;
	background-attachment: fixed;
	background-clip: border-box;
	background-size: cover;
	background-origin: padding-box;
	width: 100%;
	padding: 0;
}
</style>
<style>
        .inp {
            border: 1px solid gray;
            padding: 0 10px;
            width: 200px;
            height: 30px;
            font-size: 18px;
        }
        .btn {
            border: 1px solid gray;
            width: 100px;
            height: 30px;
            font-size: 18px;
            cursor: pointer;
        }
        #embed-captcha {
            width: 300px;
            margin: 8px auto;
        }
        .show {
            display: block;
        }
        .hide {
            display: none;
        }
        #notice {
            color: red;
        }
    </style>

</head>
<body style="background-image: url({pigcms{$static_path}newlogin/houtaiimage/bg_3.jpg);">
<div style="position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
    width: 100%;
    height: 100%;
    background: url({pigcms{$static_path}newlogin/index-mask.png) repeat;
    z-index: 0;"></div>
<video id="index-video" style="position: fixed;
    right: 0;
    bottom: 0;
    min-width: 100%;
    min-height: 100%;
    width: auto;
    height: auto;
    z-index: -100;
    background: url(../../image/index/bg.jpg) no-repeat;
    background-size: cover;" src="{pigcms{$static_path}newlogin/index.mp4" autoplay="true" loop="-1" ></video>
<div style="margin-top: 100px;
    width: 100%;
    text-align: center;">
				<img src="{pigcms{$static_path}newlogin/index-title.png" style="width: 450px;vertical-align: middle;position: relative;
    z-index: 99;
    color: #fff;
    margin-top: 100px;">
			</div>	
<div class="login-layout">
	<div class="top">
		
	
	</div>
	<div class="box">
		<form method="post" id="form">
			<span><label>帐号</label><input type="text" name="account" id="account" autocomplete="off" class="input-text" value="{pigcms{$_GET.account}"></span>
			<span><label>密码</label><input type="password" name="pwd" id="pwd" class="input-password" autocomplete="off" value="{pigcms{$_GET.pwd}" pattern="[\S]{5}[\S]*" title="密码不少于5个字符"></span>
			<span><label>验证</label><input type="text" name="verify" id="verify" autocomplete="off" class="input-text" ></span>
<span id="verify_box">
						<img src="{pigcms{:U('Login/verify')}" id="verifyImg" onclick="fleshVerify('{pigcms{:U('Login/verify')}')" title="刷新验证码" alt="刷新验证码"style="margin-top:10px;"/ >
						<a href="javascript:fleshVerify('{pigcms{:U('Login/verify')}')" id="fleshVerify">刷新验证码</a>
					</span>
			<span style="position: absolute; left: 613px;">
			<input name="nchash" type="hidden" value="c59a47f0">
			<input name="" class="input-button" type="submit" value="登录"></span>
			<span style="position: absolute; left: 702px;">
			<input href="javascript:void(0)" name="" class="input-button"  id="scan_login" type="submit" value="扫码"></span>
		</form>
	</div>
</div>
<div class="bottom">
	<h5>Powered by PigCms.com</h5>
	<h6 title="小猪CMS生活通">© 2007-2016 <a href="{pigcms{$config.config_site_url}" target="_blank">Hi!Alex Networking Inc.</a></h6>
</div>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
		<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
		<script type="text/javascript">
			$(document).ready(function(){
				$('.scan_login').click(function(){
					art.dialog.open("{pigcms{:U('Login/see_admin_qrcode')}&t="+Math.random(),{
						init: function(){
							var iframe = this.iframe.contentWindow;
							window.top.art.dialog.data('login_iframe_handle',iframe);
						},
						id: 'login_handle',
						title:'请使用微信扫描二维码登录',
						padding: 0,
						width: 430,
						height: 433,
						lock: true,
						resize: false,
						background:'black',
						button: null,
						fixed: false,
						close: null,
						left: '50%',
						top: '38.2%',
						opacity:'0.4'
					});
					return false;
				});
				$('#send_code').click(function(){
					$.post('{pigcms{:U("Login/send_code")}', {account:$('#account').val()}, function(response){
						if (response.errcode) {
						} else {
						}
					}, 'json');
				});
			});
		</script>
		<script type="text/javascript">
			if(self!=top){window.top.location.href="{pigcms{:U('Index/index')}";}
			var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",login_check="{pigcms{:U('Login/check')}",system_index="{pigcms{:U('Index/index')}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}login/login.js"></script>

<iframe id="TSLOGINI" style="display:none" src="">
</iframe>
</body>
</html>