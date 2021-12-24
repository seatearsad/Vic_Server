<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <title>{pigcms{:L('_B_PURE_MY_55_')} | {pigcms{:L('_VIC_NAME_')}</title>
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
	<link rel="stylesheet" type="text/css" href="{pigcms{$config.site_url}/tpl/Static/default/css/qrcode.v74a11a81.css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/footer.css" />
	<script src="{pigcms{$static_public}js/jquery.min.js"></script>
    <include file="Public:facebook"/>
</head>
<body id="login" class="theme--www" style="position: static;">
    <include file="Public:google"/>
	<header id="site-mast" class="site-mast site-mast--mini">
	    <div class="site-mast__branding cf">
			<a href="{pigcms{$config.site_url}"><img src="{pigcms{$config.site_logo}" alt="{pigcms{$config.site_name}" title="{pigcms{$config.site_name}" style="width:190px;height:60px;"/></a>
	    </div>
	</header>
	<div class="site-body pg-login cf">
	    <div class="promotion-banner">
	        <img src="{pigcms{$config.site_url}/tpl/Static/default/css/img/web_login/{pigcms{:mt_rand(1,4)}.jpg" width="480" height="370">    
	    </div>
	    <div class="component-login-section component-login-section--page mt-component--booted" >
		    <div class="origin-part theme--www">
			    <div class="validate-info" style="visibility:hidden"></div>
		        <h2>{pigcms{:L('_INPUT_NEW_PASS_')}</h2>
		        <form id="J-login-form" method="post" class="form form--stack J-wwwtracker-form">
			        <div class="form-field form-field--icon">
			            <i class="icon icon-password"></i>
			            <input type="password" id="newpwd" class="f-text" name="newpwd" placeholder="{pigcms{:L('_B_D_LOGIN_ENTERKEY2_')}" value=""/>
			        </div>
					<div class="form-field form-field--icon">
			            <i class="icon icon-password"></i>
			            <input type="password" id="new_pwd" class="f-text" name="new_pwd" placeholder="{pigcms{:L('_B_D_LOGIN_CONFIRMKEY_')}" value=""/>
			        </div>
			        <div class="form-field form-field--ops">
			            <input type="submit" class="btn" id="commit" value="{pigcms{:L('_B_D_LOGIN_CONIERM_')}"/>
			        </div>
			    </form>
		    </div>
		</div>
	</div>
	<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			if($('body').height() < $(window).height()){
				$('.site-info-w').css({'position':'absolute','width':'100%','bottom':'0'});
			}
			$("#J-login-form").submit(function(){
				$('.validate-info').css('visibility','hidden');
				$('#commit').val("{pigcms{:L('_B_D_LOGIN_SENDING_')}").prop('disabled',true);
				var newpwd = $.trim($("#newpwd").val());
				var new_pwd = $.trim($("#new_pwd").val());
				if (!newpwd) {
					$('.validate-info').html("<i class='tip-status tip-status--opinfo'></i>{pigcms{:L('_B_D_LOGIN_BLANKNEWKEY_')}").css('visibility','visible');
					$("#commit").val("{pigcms{:L('_B_D_LOGIN_CONIERM_')}").prop('disabled',false);
					return false;
				}
				if (!new_pwd) {
					$('.validate-info').html("<i class='tip-status tip-status--opinfo'></i>{pigcms{:L('_B_D_LOGIN_CONFIRMBLANKNEWKEY_')}").css('visibility','visible');
					 $("#commit").val("{pigcms{:L('_B_D_LOGIN_CONIERM_')}").prop('disabled',false);
					 return false;
				}
				if (newpwd!=new_pwd) {
					$('.validate-info').html("<i class='tip-status tip-status--opinfo'></i>{pigcms{:L('_B_D_LOGIN_DIFFERENTKEY2_')}").css('visibility','visible');
					$("#commit").val("{pigcms{:L('_B_D_LOGIN_CONIERM_')}").prop('disabled',false);
					 return false;
				}
				
				$.post("{pigcms{:U('Index/Login/pwdModifying')}&pm={pigcms{$pm}", {newpwd:newpwd,new_pwd:new_pwd}, function(data){
					data.error_code=parseInt(data.error_code);
					if (!data.error_code) {
						$("#vfycodediv").css('visibility','visible');
						$('.validate-info').html('<i class="tip-status tip-status--success"></i>'+data.msg).css('visibility','visible');
						setTimeout(function(){
						    window.location.href='{pigcms{:U("Index/Login/index")}&referer=http://'+window.location.host;
						  }, 1000);
						return false;
					} else {
						if(data.error_code == 1){
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
						  $("#commit").val("{pigcms{:L('_B_D_LOGIN_CONIERM_')}").prop('disabled',false);
						}else if(data.error_code == 2){
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
						  setTimeout(function(){
						    window.location.href="{pigcms{:U('Index/Login/index')}";
						  }, 1000);
						}
						
					}
				}, 'json');
				return false;
			});
			

		});
	</script>
	<include file="Public:footer"/>
</body>
</html>