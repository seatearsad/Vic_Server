<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>{pigcms{:L('_B_PURE_MY_57_')}</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
        <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<link href="{pigcms{$static_path}css/eve.7c92a906.peter.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>

		<style>
			/*#login{margin: 0.5rem 0.2rem;}*/
			.btn-wrapper{margin:.28rem 0;}

			.nav{text-align: center;}
			.subline{margin:.28rem .2rem;}
			.subline li{display:inline-block;}
			.captcha img{margin-left:.2rem;}
			.captcha .btn{margin-top:-.15rem;margin-bottom:-.15rem;margin-left:.2rem;}
            .main{
                width: 100%;
                padding-top: 60px;
                max-width: 640px;
                min-width: 320px;
                margin: 0 auto;
            }


            .btn-larger{
                background-color: #ffa52d;
            }
            .btn-weak{
                border: .02rem solid #ffa52d;
                color: #ffa52d;
            }
            .div_inner{
                display: -webkit-flex;
                display: flex;
                width: 98%;
                margin-left: 0;
            }
            .div_outer{
                display: -webkit-flex;
                display: flex;
                width: 90%;
                margin-left: 5%;
            }
		</style>
        <include file="Public:facebook"/>
	</head>
	<body>
    <include file="Public:header"/>
    <div class="main">

        	<div id="tips"></div>
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('My/bind_user')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" login_url="{pigcms{:U('Login/index')}" class="detail_block">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input id="reg_phone" class="input-weak" type="text" placeholder="{pigcms{:L('_B_LOGIN_ENTERPHONENO_')}" name="phone" value="" />
			            		</dd>
								<!--if condition="C('config.bind_phone_verify_sms') AND C('config.sms_key')"-->
                                <div class="div-space"></div>
                                <div class="div_inner">
			            			<input id="sms_code" class="input-radius" name = "sms_code" type="text" placeholder="Code" />
			            			<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-inline btn-larger2">{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}</button>
			            		</div>
								<!--/if>
								<if condition="$now_user['pwd'] eq '' OR $now_user['phone'] eq '' OR C('config.bind_phone_verify_sms') eq 0">
									<dd class="kv-line-r dd-padding">
										<input id="reg_pwd_password" class="input-weak kv-k" type="password" placeholder="<if condition="$now_user['phone'] eq ''">{pigcms{:L('_B_D_LOGIN_6KEYWORD_')}<else />{pigcms{:L('_VERCIF_OLD_PASS_')}</if>"/>
										<input id="reg_txt_password" class="input-weak kv-k" type="text" placeholder="<if condition="$now_user['phone'] eq ''">{pigcms{:L('_B_D_LOGIN_6KEYWORD_')}<else />{pigcms{:L('_VERCIF_OLD_PASS_')}</if>" style="display:none;"/>
										<input type="hidden" id="reg_password_type" value="0"/>
										<button id="reg_changeWord" type="button" class="btn btn-weak kv-v">{pigcms{:L('_B_D_LOGIN_DISPLAY_')}</button>
									</dd>
								</if-->
			        		</dl>
			        	</dd>
			        </dl>
                    <div class="div-space"></div>
                    <div class="div-space"></div>
                    <div class="div-space"></div>
			        <div class="div_outer">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn-whole-h btn-larger btn-block">{pigcms{:L('_B_D_LOGIN_CONIERM_')}</button>
			        </div>
			    </form>
			</div>
        <script src="{pigcms{$static_public}layer/layer.m.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
		<script type="text/javascript">
            function show_msg(msg) {
                layer.open({
                    title: "{pigcms{:L('_STORE_REMIND_')}",
                    time: 2,
                    content: msg
                });
            }
            function checkPhone(phone) {
                if(!/^\d{10,}$/.test(phone)){
                    return false;
                }
                return true;
            }

			var countdown = 60;
			function sendsms(val){
				if($("input[name='phone']").val()==''){
					show_msg("{pigcms{:L('_B_LOGIN_ENTERPHONENO_')}");
				}else if(!checkPhone($("input[name='phone']").val())){
                    show_msg("{pigcms{:L('_B_LOGIN_ENTERGOODNO_')}");
                }else{
					if(countdown==60){
						$.ajax({
							url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send',
							type: 'POST',
							dataType: 'json',
							data: {phone: $("input[name='phone']").val(),reg:1},
                            success:function(date){
                                if(date.error_code){
                                    countdown = 0;
                                    show_msg(date.msg);
                                }else{
                                    show_msg('Success');
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
            $('#back_span').click(function () {
                window.history.go(-1);
            });

			$('#reg-form').submit(function (e) {
                e.preventDefault();
                sendPhone();
            });
			
			function sendPhone() {
                if($("input[name='phone']").val()==''){
                    show_msg("{pigcms{:L('_B_LOGIN_ENTERPHONENO_')}");
                }else if($("input[name='sms_code']").val()==''){
                    show_msg("{pigcms{:L('_B_MY_ENTERCODE_')}");
                }else if(!checkPhone($("input[name='phone']").val())){
                    show_msg("{pigcms{:L('_B_LOGIN_ENTERGOODNO_')}");
                }else{
                    $.post("{pigcms{:U('My/bind_user')}",{phone:$("input[name='phone']").val(),sms_code:$("input[name='sms_code']").val()},function(result){
                        if(result.status == '1'){
                            //window.location.href = $('#reg-form').attr('location_url');
                            artDialog.open.origin.location.reload();
                            window.location.href = "{pigcms{:U('My/index')}";
                        }else{
                            reg_flag = true;
                            //$('#tips').html(result.info).show();
                            show_msg(result.info);
                        }
                    });
                }
            }
		</script>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/bind_user.js"></script>
    </div>

	</body>
</html>