<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>{pigcms{:L('_B_D_LOGIN_REG2_')} | {pigcms{:L('_VIC_NAME_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
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
		        <h2>{pigcms{:L('_B_D_LOGIN_REG1_')}</h2>
		        <form id="J-login-form" method="post" class="form form--stack J-wwwtracker-form">
			        <div class="form-field form-field--icon">
			            <i class="icon icon-user"></i>
			            <input type="text" id="login-phone" class="f-text" name="phone" placeholder="{pigcms{:L('_B_D_LOGIN_TEL_')}"/>
			        </div>
					<if condition="C('config.reg_verify_sms') AND C('config.sms_pwd')">
					<div class="form-field form-field--icon">
			            <i class="icon icon-user"></i>
			            <input type="text" id="sms_code" style ="width:98px;" class="f-text" name="sms_code" placeholder="{pigcms{:L('_B_D_LOGIN_FILLMESSAGE_')}"/>
						<button class="btn" style="width: 125px;font-weight: normal;padding: 7px 5px 6px;" onclick="sendsms(this)" >{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}</button>
			        </div>
					</if>
			        <div class="form-field form-field--icon" >
			            <i class="icon icon-password"></i>
			            <input type="password" id="login-password" class="f-text" name="pwd" placeholder="{pigcms{:L('_B_D_LOGIN_KEY1_')}"/>
			        </div>
					<div class="form-field form-field--icon" >
			            <i class="icon icon-password"></i>
			            <input type="password" id="login-repassword" class="f-text" name="repwd" placeholder="{pigcms{:L('_AGAIN_INPUT_PASS_')}"/>
			        </div>
			        <if condition="$config['register_agreement'] neq ''">
					<p class="signup-guide"><input type="checkbox" id="register_agreement" checked>同意《注册协议》<a href="javascript:;" id="read_register_agreement">查看协议</a></p>
					</if>
			        <div class="form-field form-field--ops">
			            <input type="hidden" name="fingerprint" class="J-fingerprint"/>
			            <input type="hidden" name="origin" value="account-login"/>
			            <input type="submit" class="btn" id="commit" value="{pigcms{:L('_B_D_LOGIN_REG2_')}"/>
			        </div>
			    </form>
				<p class="signup-guide">{pigcms{:L('_ALREADY_ACC_')} <a href="{pigcms{:U('Login/index',array('referer'=>urlencode($referer)))}">{pigcms{:L('_B_D_LOGIN_LOGINNOW_')}</a></p>
		    </div>
		</div>
	</div>
	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			if($('body').height() < $(window).height()){
				$('.site-info-w').css({'position':'absolute','width':'100%','bottom':'0'});
			}
			$("#J-login-form").submit(function(){
				$('.validate-info').css('visibility','hidden');
				$('#commit').val('{pigcms{:L("_B_D_LOGIN_REG2_")}...').prop('disabled',true);
				
				$("#login-phone").val($.trim($("#login-phone").val()));
				$("#login-password").val($.trim($("#login-password").val()));
				$("#login-repassword").val($.trim($("#login-repassword").val()));
				
				if ($('#register_agreement').size() > 0 && !($('#register_agreement').is(':checked'))) {
					error_tips('同意注册协议后才能注册','register_agreement');
					return false;
				}
				var phone = $("#login-phone").val();
				var pwd = $("#login-password").val();
				<if condition="C('config.reg_verify_sms')">
					var sms_code = $("#sms_code").val();
				</if>
				var repwd = $("#login-repassword").val();
				if(phone == '' || phone == null){
					error_tips('{pigcms{:L("_B_D_LOGIN_BLANKNUM_")}','login-phone');
					return false;
				}
				//if(!/^[0-9]{10}$/.test(phone)){
					//error_tips('请输入10位数字的手机号码','login-phone');
					//return false;
				//}
				if(pwd == '' || pwd == null){
					error_tips('{pigcms{:L("_B_D_LOGIN_BLANKKEY_")}','login-password');
					return false;
				}
				if(pwd != repwd){
					error_tips('{pigcms{:L("_B_LOGIN_DIFFERENTKEY_")}','login-repassword');
					return false;
				}
				$.post("{pigcms{:U('Index/Login/reg')}", {'phone':phone,'pwd':pwd <if condition="C('config.reg_verify_sms')">,sms_code:sms_code</if>}, function(data){
					if(data.error_code){
						$("#commit").val('{pigcms{:L("_B_D_LOGIN_REG2_")}').prop('disabled',false);
						$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
						return false;
					}else if(data.user){
						$('.validate-info').html('<i class="tip-status tip-status--success"></i>{pigcms{:L("_B_LOGIN_REGISTSUCESS_")}').css('visibility','visible');
						setTimeout("location.href='{pigcms{$referer}'", 1000);
					}else{
						$('.validate-info').html('<i class="tip-status tip-status--success"></i>{pigcms{:L("_B_LOGIN_REGISTSUCESS_")}').css('visibility','visible');
						setTimeout("location.href='{pigcms{:U('Login/index',array('referer'=>urlencode($referer)))}'", 1000);
					}
				}, 'json');
				return false;
			});
			$('#read_register_agreement').click(function(){
				art.dialog.open("{pigcms{:U('Login/register_agreement')}&"+Math.random(),{
					init: function(){
						var iframe = this.iframe.contentWindow;
						window.top.art.dialog.data('login_iframe_handle',iframe);
					},
					id: 'login_handle',
					title:'注册协议',
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
			});
			
			// art.dialog.open("{pigcms{:U('Index/Recognition/see_login_qrcode',array('referer'=>urlencode($referer)))}&"+Math.random(),{
			// 	init: function(){
			// 		var iframe = this.iframe.contentWindow;
			// 		window.top.art.dialog.data('login_iframe_handle',iframe);
			// 	},
			// 	id: 'login_handle',
			// 	title:'{pigcms{:L("_FAST_REG_WECHAT_")}',
			// 	padding: 0,
			// 	width: 430,
			// 	height: 433,
			// 	lock: true,
			// 	resize: false,
			// 	background:'black',
			// 	button: null,
			// 	fixed: false,
			// 	close: null,
			// 	left: '50%',
			// 	top: '38.2%',
			// 	opacity:'0.4'
			// });
		});
		function error_tips(msg,id){
			$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+msg).css('visibility','visible');
			$("#commit").val('{pigcms{:L("_B_D_LOGIN_REG2_")}').prop('disabled',false);
			$('#'+id).focus();
		}
		
		var countdown = 60;function sendsms(val){
		if($("input[name='phone']").val()==''){
			alert('{pigcms{:L("_B_D_LOGIN_BLANKNUM_")}');
		}else{
			
			if(countdown==60){
				$.ajax({
					url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send',
					type: 'POST',
					dataType: 'json',
					data: {phone: $("input[name='phone']").val(),reg:1},
					success:function(date){
						if(date.error_code){
							$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+date.msg).css('visibility','visible');
						}
					}

				});
			}
			if (countdown == 0) {
				val.removeAttribute("disabled");
				val.innerText="{pigcms{:L('_B_D_LOGIN_FILLMESSAGE_')}";
				countdown = 60;
				//clearTimeout(t);
			} else {
				val.setAttribute("disabled", true);
				val.innerText="{pigcms{:L('_B_D_LOGIN_SENDAGAIN_')}(" + countdown + ")";
				countdown--;
				setTimeout(function() {
					sendsms(val);
				},1000)
			}
		}
	}
	</script>
	<include file="Public:footer"/>
</body>
</html>