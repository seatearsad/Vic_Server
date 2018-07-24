<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_B_D_LOGIN_KEYBACK_')}  - {pigcms{$config.site_name}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">

    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
	<style>
		#login{margin: 0.5rem 0.2rem;}
		.btn-wrapper{margin:.28rem 0;}
		dl.list{border-bottom:0;border:1px solid #ddd8ce;}
		dl.list:first-child{border-top:1px solid #ddd8ce;}
		dl.list dd dl{padding-right:0.2rem;}
		dl.list dd dl>.dd-padding, dl.list dd dl dd>.react, dl.list dd dl>dt{padding-right:0;}
	    .nav{text-align: center;}
	    .subline{margin:.28rem .2rem;}
	    .subline li{display:inline-block;}
	    .captcha img{margin-left:.2rem;}
	    .captcha .btn{margin-top:-.15rem;margin-bottom:-.15rem;margin-left:.2rem;}
	</style>
</head>
<body id="index" data-com="pagecommon">
        <!--header  class="navbar">
            <div class="nav-wrap-left">
                <a class="react back" href="javascript:history.back()"><i class="text-icon icon-back"></i></a>
            </div>
            <h1 class="nav-header">{pigcms{$config.site_name}</h1>
            <div class="nav-wrap-right">
                <a class="react" href="{pigcms{:U('Home/index')}">
                    <span class="nav-btn"><i class="text-icon">⟰</i>{pigcms{:L('_B_D_LOGIN_HPME1_')}</span>
                </a>
            </div>
        </header-->
        <div id="container">
        	<div id="tips" style="-webkit-transform-origin:0px 0px;opacity:1;-webkit-transform:scale(1, 1);"></div>
			<div id="login">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			        
			            		<dd class="dd-padding">
			            			<input id="phone" class="input-weak" type="password" placeholder="{pigcms{:L('_B_D_LOGIN_ENTERKEY2_')}" name="newpwd" value="" required="">
			            		</dd>
								<dd class="dd-padding">
			            			<input id="phone" class="input-weak" type="password" placeholder="{pigcms{:L('_B_D_LOGIN_CONFIRMKEY_')}" name="new_pwd" value="" required="">
			            		</dd>
								
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<button type="submit" onclick="pwdmodify(this)"  class="btn btn-larger btn-block">{pigcms{:L('_B_D_LOGIN_CONIERM_')}</button>
			        </div>
			</div>
			<ul class="subline">
			    <li><a href="{pigcms{:U('Login/index')}">{pigcms{:L('_B_D_LOGIN_LOGINNOW_')}</a></li>
			</ul>
		</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript">
			
			
			function pwdmodify(val){
				
	
				$('#commit').val("{pigcms{:L('_B_D_LOGIN_SENDING_')}").prop('disabled',true);
				var newpwd = $('input[name="newpwd"]').val();
				var new_pwd = $('input[name="new_pwd"]').val();
				if (!newpwd) {
					$('#tips').html("{pigcms{:L('_B_D_LOGIN_BLANKNEWKEY_')}").show();

				}
				if (!new_pwd) {
					$('#tips').html("{pigcms{:L('_B_D_LOGIN_CONFIRMBLANKNEWKEY_')}").show();
				}
				if (newpwd!=new_pwd) {
					$('#tips').html("{pigcms{:L('_B_D_LOGIN_DIFFERENTKEY2_')}").show();
				}
				
				$.post("{pigcms{:U('Login/pwdModifying')}&pm={pigcms{$pm}", {newpwd:newpwd,new_pwd:new_pwd}, function(data){
					data.error_code=parseInt(data.error_code);
					if (!data.error_code) {
						$('#tips').html(data.msg).show();
						setTimeout(function(){
						    window.location.href='{pigcms{:U("Login/index")}&referer=http://'+window.location.host;
						  }, 1000);
						return false;
					} else {
						if(data.error_code == 1){
						  $('#tips').html(data.msg).show();
						}else if(data.error_code == 2){
						  $('#tips').html(data.msg).show();
						  setTimeout(function(){
						    window.location.href="{pigcms{:U('Login/index')}";
						  }, 1000);
						}
						
					}
				}, 'json');
				return false;
			}
			


	
		</script>
		
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/reg.js"></script>
		<include file="Public:footer"/>

{pigcms{$hideScript}
	</body>
</html>