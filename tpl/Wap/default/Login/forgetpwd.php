<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_B_LOGIN_RECOMENDNEWGIFSCORE_')}  - {pigcms{$config.site_name}</title>
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
			            			<input id="phone" class="input-weak" type="tel" placeholder="{pigcms{:L('_B_D_LOGIN_TEL_')}" name="phone" value="" required="">
			            		</dd>
								<if condition=" C('config.reg_verify_sms')">
			            		<dd class="kv-line-r dd-padding">
			            			<input id="vfycode" class="input-weak kv-k" name = "vcode" type="text" placeholder="{pigcms{:L('_B_D_LOGIN_FILLMESSAGE_')}" required/>
			            			<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v">{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}</button>
			            		</dd>
								</if>
			            		
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<button type="submit" onclick="forgetpwd(this);" class="btn btn-larger btn-block">{pigcms{:L('_B_D_LOGIN_SUB_')}</button>
			        </div>
			   
			</div>
			<ul class="subline">
			    <li><a href="{pigcms{:U('Login/index')}">{pigcms{:L('_B_D_LOGIN_LOGINNOW_')}</a></li>
			</ul>
		</div>
		<script type="text/javascript">
			var flage='';
			function  forgetpwd(val){
				
					var vfycode  = $('#vfycode').val();
					$.ajax({
						url: '{pigcms{$config.site_url}/index.php?g=Index&c=Login&a=Generate',
						type: 'POST',
						dataType: 'json',
						data: {phone: $("input[name='phone']").val(),vfycode:vfycode,tmpid:1},
						success:function(date){
							if(date.error_code==2){
								window.location.href="{pigcms{:U('Login/pwdModify')}&pm="+date.urlpm;
							}else{
								$('#tips').html(date.msg).show();
							}
						}
					});
				
			}
			
			var countdown = 60;
			function sendsms(val){
				if($("input[name='phone']").val()==''){
					alert("{pigcms{:L('_B_D_LOGIN_BLANKNUM_')}");
				}else{
					
					if(countdown==60){
						$.ajax({
							url: '{pigcms{$config.site_url}/index.php?g=Index&c=Login&a=Generate',
							type: 'POST',
							dataType: 'json',
							data: {phone: $("input[name='phone']").val(),vfycode:'',tmpid:0},
							success:function(date){
								flage=date.id;
								if(date.error_code){
									$('#tips').html(date.msg).show();
								}
							}

						});
					}
					if (countdown == 0) {
						val.removeAttribute("disabled");
						val.innerText="{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}";
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
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/reg.js"></script>
		<include file="Public:footer"/>

{pigcms{$hideScript}
	</body>
</html>