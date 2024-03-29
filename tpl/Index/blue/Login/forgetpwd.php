<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <title>{pigcms{:L('_FORGET_PASS_W_')} | {pigcms{:L('_VIC_NAME_')}</title>
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
	<style type="text/css">.noact{background-image:none !important;color: #969696 !important;background-color: #CACACA !important;}</style>
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
		        <h2>{pigcms{:L('_B_D_LOGIN_TEL_')}</h2>
		        <form id="J-login-form" method="post" class="form form--stack J-wwwtracker-form">
			        <div class="form-field form-field--icon">
			            <i class="icon icon-user"></i>
			            <input type="text" id="login-phone" class="f-text" name="phone" placeholder="{pigcms{:L('_B_D_LOGIN_TEL_')}" value="{pigcms{$accphone}"/>
			        </div>
			        <div class="form-field form-field--icon" style="visibility:hidden" id="vfycodediv">
			            <i class="icon icon-password"></i>
			            <input type="text" id="vfycode" class="f-text" name="vfycode" placeholder="{pigcms{:L('_B_D_LOGIN_FILLMESSAGE_')}" value=""/>
			        </div>
			        <div class="form-field form-field--ops">
			            <input type="submit" class="btn" id="commit" value="{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}" style="width:55%"/>
						&nbsp;&nbsp;&nbsp;<span class="btn noact" style="width:15%;"><span id="reciprocal">60</span>S</span>
						<a class="btn" id="submitcommit" style="margin-top:15px;width:85%" href="javascript:;" />{pigcms{:L('_B_D_LOGIN_SUB_')}</a>
			        </div>
			    </form>
		    </div>
		</div>
	</div>
	<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
	     var flage=false;var islock=false;
		$(document).ready(function(){
			if($('body').height() < $(window).height()){
				$('.site-info-w').css({'position':'absolute','width':'100%','bottom':'0'});
			}
			$("#J-login-form").submit(function(){
				$('.validate-info').css('visibility','hidden');
				$('#commit').val('...').prop('disabled',true);
				var phone = $.trim($("#login-phone").val());
				var vfycode = $.trim($("#vfycode").val());
				if (phone == '' || phone == null) {
					$('.validate-info').html("<i class='tip-status tip-status--opinfo'></i>{pigcms{:L('_B_D_LOGIN_BLANKNUM_')}").css('visibility','visible');
					$("#commit").val("{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}").prop('disabled',false);
					return false;
				}
				
				$.post("{pigcms{:U('Index/Login/Generate')}", {'phone':phone,vfycode:'',tmpid:0}, function(data){
					data.error_code=parseInt(data.error_code);
					if (!data.error_code) {
						$("#commit").val("{pigcms{:L('_B_D_LOGIN_SENDAGAIN_')}");
						$("#vfycodediv").css('visibility','visible');
						$('.validate-info').html("<i class='tip-status tip-status--success'></i>{pigcms{:L('_B_D_LOGIN_FILLMESSAGE_')}").css('visibility','visible');
						flage=data.id;
						Reciprocal();
						return false;
					} else {
						if(data.error_code == 1){
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
							  $("#commit").val("{pigcms{:L('_B_D_LOGIN_SENDAGAIN_')}").prop('disabled',false);
						}else if(data.error_code == 2){
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>').css('visibility','visible');
						  setTimeout(function(){
						    window.location.href="{pigcms{:U('Index/Login/pwdModify')}&pm="+data.urlpm;
						  }, 800);
						}else if(data.error_code == 3){
						   $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg+'<a href="{pigcms{:U(\'Index/Login/reg\')}">去注册</a>').css('visibility','visible');
							  $("#commit").val("{pigcms{:L('_B_D_LOGIN_SENDAGAIN_')}").prop('disabled',false);
						}
						
					}
				}, 'json');
				return false;
			});

			$('#submitcommit').click(function(){
				if(islock || !flage) return false;
				islock=true;
			    $('.validate-info').css('visibility','hidden');
				  $('#submitcommit').val('...').prop('disabled',true);
				var phone = $.trim($("#login-phone").val());
				var vfycode = $.trim($("#vfycode").val());
				if (phone == '' || phone == null) {
					$('.validate-info').html("<i class='tip-status tip-status--opinfo'></i>{pigcms{:L('_B_D_LOGIN_BLANKNUM_')}").css('visibility','visible');
					  $("#submitcommit").val("{pigcms{:L('_B_D_LOGIN_SUB_')}");
					  islock=false;
					  return false;
				}
				if (vfycode == '' || vfycode == null) {
					$('.validate-info').html("<i class='tip-status tip-status--opinfo'></i>{pigcms{:L('_B_D_LOGIN_FILLMESSAGE_')}").css('visibility','visible');
					 $("#submitcommit").val("{pigcms{:L('_B_D_LOGIN_SUB_')}");
					 islock=false;
					 return false;
				}

				$.post("{pigcms{:U('Index/Login/Generate')}", {'phone':phone,vfycode:vfycode,tmpid:flage}, function(data){
					data.error_code=parseInt(data.error_code);
					if (data.error_code == 2) {
						$('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>').css('visibility','visible');
						  setTimeout(function(){
						    window.location.href="{pigcms{:U('Index/Login/pwdModify')}&pm="+data.urlpm;
						 }, 800);
						 islock=false;
						return false;
					} else {
						  $('.validate-info').html('<i class="tip-status tip-status--opinfo"></i>'+data.msg).css('visibility','visible');
						  islock=false;
						  $("#submitcommit").val("{pigcms{:L('_B_D_LOGIN_SUB_')}");
					}
				}, 'json');
				return false;
			});
		});

    function Reciprocal(){
	   $("#reciprocal").parent('.btn').removeClass('noact');
	     var inttmp=window.setInterval(function(){
		  num = $("#reciprocal").text();
		  num = parseInt(num);
	     $("#reciprocal").text(num-1);
		 if(num==1){
		    $("#reciprocal").parent('.btn').addClass('noact');
			//flage=0;
			$("#commit").val("{pigcms{:L('_B_D_LOGIN_SENDAGAIN_')}").prop('disabled',false);
			window.clearInterval(inttmp);
			setTimeout(function(){
				$("#reciprocal").text(60);
			}, 1000);
		 }
	   },1000);
    }
	</script>
	<include file="Public:footer"/>
</body>
</html>