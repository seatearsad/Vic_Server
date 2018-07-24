<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>{pigcms{:L('_B_D_LOGIN_LOGIN1_')} | {pigcms{$config.site_name}</title>
    <!--[if IE 6]>
		<script src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a-min.v86c6ab94.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
		<script src="{pigcms{$static_path}js/html5shiv.min-min.v01cbd8f0.js"></script>
    <![endif]-->
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/common.v113ea197.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/base.v492b572b.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login.v7e870f72.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/login-section.vfa22738e.css" />
	<script src="{pigcms{$static_public}js/jquery.min.js"></script>
	
	<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
</head>
<body class="pg-unitive-login" style="background-color:#fff;">
	<div class="content" style="margin:0px auto;border:none;padding:30px;overflow:hidden;width:270px;">
	    <div class="component-login-section component-login-section--page mt-component--booted" >
		    <div class="origin-part theme--www">
			    <div class="validate-info" style="visibility:hidden"></div>
		        <h2>{pigcms{:L('_B_D_LOGIN_LOGIN2_')}</h2>
		        <form id="J-login-form" method="post" class="form form--stack J-wwwtracker-form">
			        <div class="form-field form-field--icon">
			            <i class="icon icon-user"></i>
			            <input type="text" id="login-phone" class="f-text" name="phone" placeholder="{pigcms{:L('_B_D_LOGIN_TEL_')}" value="{pigcms{$_COOKIE.login_name}"/>
			        </div>
			        <div class="form-field form-field--icon" >
			            <i class="icon icon-password"></i>
			            <input type="password" id="login-password" class="f-text" name="pwd" placeholder="{pigcms{:L('_B_D_LOGIN_KEY1_')}"/>
			        </div>
			        <div class="form-field form-field--ops">
			            <input type="hidden" name="fingerprint" class="J-fingerprint"/>
			            <input type="hidden" name="origin" value="account-login"/>
			            <input type="submit" class="btn" id="commit" value="{pigcms{:L('_B_D_LOGIN_LOGIN1_')}"/>
			        </div>
			    </form>
			    <p class="signup-guide">{pigcms{:L('_B_D_LOGIN_NOACCOUNT_')}<a href="{pigcms{:U('Login/reg',array('referer'=>$url_referer))}" target="_top">{pigcms{:L('_B_D_LOGIN_FREEREG_')}</a></p>        
			    <div class="oauth-wrapper">
			        <h3 class="title-wrapper"><span class="title">{pigcms{:L('_B_D_LOGIN_TELWECHATLOGIN_')}</span></h3>
			        <div class="oauth cf">
			            <a class="oauth__link oauth__link--weixin" href="javascript:void(0);"></a>
			        </div>    
			    </div>
		    </div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).ready(function(){
			if($('body').height() < $(window).height()){
				$('.site-info-w').css({'position':'absolute','width':'100%','bottom':'0'});
			}
			$("#J-login-form").submit(function(){
				$('.validate-info').css('visibility','hidden');
				$('#commit').val("{pigcms{:L('_B_D_LOGIN_LOGINING_')}").prop('disabled',true);
				var phone = $("#login-phone").val();
				var pwd = $("#login-password").val();
				if (phone == '' || phone == null) {
					$('.validate-info').html("<i class="tip-status tip-status--opinfo"></i>{pigcms{:L('_B_D_LOGIN_BLANKNUM_')}").css('visibility','visible');
					$("#commit").val("{pigcms{:L('_B_D_LOGIN_LOGIN1_')}").prop('disabled',false);
					return false;
				}
				if (pwd == '' || pwd == null) {
					$('.validate-info').html("<i class="tip-status tip-status--opinfo"></i>{pigcms{:L('_B_D_LOGIN_BLANKKEY_')}").css('visibility','visible');
					$("#commit").val("{pigcms{:L('_B_D_LOGIN_LOGIN1_')}").prop('disabled',false);
					return false;
				}
				
				$.post("{pigcms{:U('Login/login')}", {'phone':phone, 'pwd':pwd}, function(data){
					if (data.error_code) {
						$("#commit").val("{pigcms{:L('_B_D_LOGIN_LOGIN1_')}").prop('disabled',false);
						$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
						return false;
					} else {
						$('.validate-info').html("<i class="tip-status tip-status--success"></i>{pigcms{:L('_B_D_LOGIN_LOGINACCESSJUMP_')}.").css('visibility','visible');
						<if condition="$_GET['scriptName']">
							window.top.{pigcms{$_GET.scriptName}();
						<else/>
							setTimeout("window.top.location.href=window.top.location.href", 1000);
						</if>
					}
				}, 'json');
				return false;
			});
			
			$('.oauth__link--weixin').click(function(){
				art.dialog.open("{pigcms{:U('Login/see_login_qrcode',array('scriptName'=>$_GET['scriptName']))}",{
					init: function(){
						var iframe = this.iframe.contentWindow;
						window.top.art.dialog.data('login_iframe_handle',iframe);
					},
					id: 'login_handle',
					title:'{pigcms{:L('_B_D_LOGIN_WECHATSCANLOGIN_')}',
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
		});
	</script>
</body>
</html>