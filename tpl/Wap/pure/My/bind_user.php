<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">
	<head>
		<meta charset="utf-8"/>
		<title>{pigcms{:L('_B_PURE_MY_57_')}</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
        <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/index_wap.css" rel="stylesheet"/>
		<link href="{pigcms{$static_path}css/idangerous.swiper.css" rel="stylesheet"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<style>
			/*#login{margin: 0.5rem 0.2rem;}*/
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
            .main{
                width: 100%;
                padding-top: 60px;
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
            .btn-larger{
                background-color: #ffa52d;
            }
            .btn-weak{
                border: .02rem solid #ffa52d;
                color: #ffa52d;
            }
		</style>
	</head>
	<body>
    <include file="Public:header"/>
    <div class="main">
        <div class="this_nav">
            <span id="back_span"></span>
            Phone Number
        </div>
        <div class="gray_line"></div>
        	<div id="tips"></div>
			<div id="login">
				<form id="reg-form" action="{pigcms{:U('My/bind_user')}" autocomplete="off" method="post" location_url="{pigcms{$referer}" login_url="{pigcms{:U('Login/index')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input id="reg_phone" class="input-weak" type="text" placeholder="{pigcms{:L('_B_LOGIN_ENTERPHONENO_')}" name="phone" value="" />
			            		</dd>
								<if condition="C('config.bind_phone_verify_sms') AND C('config.sms_key')">
			            		<dd class="kv-line-r dd-padding">
			            			<input id="sms_code" class="input-weak kv-k" name = "vcode" type="text" placeholder="填写短信验证码" />
			            			<button id="reg_send_sms" type="button" onclick="sendsms(this)" class="btn btn-weak kv-v">获取短信验证码</button>
			            		</dd>
								</if>
								<if condition="$now_user['pwd'] eq '' OR $now_user['phone'] eq '' OR C('config.bind_phone_verify_sms') eq 0">
									<dd class="kv-line-r dd-padding">
										<input id="reg_pwd_password" class="input-weak kv-k" type="password" placeholder="<if condition="$now_user['phone'] eq ''">{pigcms{:L('_B_D_LOGIN_6KEYWORD_')}<else />{pigcms{:L('_VERCIF_OLD_PASS_')}</if>"/>
										<input id="reg_txt_password" class="input-weak kv-k" type="text" placeholder="<if condition="$now_user['phone'] eq ''">{pigcms{:L('_B_D_LOGIN_6KEYWORD_')}<else />{pigcms{:L('_VERCIF_OLD_PASS_')}</if>" style="display:none;"/>
										<input type="hidden" id="reg_password_type" value="0"/>
										<button id="reg_changeWord" type="button" class="btn btn-weak kv-v">{pigcms{:L('_B_D_LOGIN_DISPLAY_')}</button>
									</dd>
								</if>
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
<!--						<button type="submit" class="btn btn-larger btn-block">注册并绑定</button>-->
						<button type="submit" class="btn btn-larger btn-block">{pigcms{:L('_B_D_LOGIN_CONIERM_')}</button>
			        </div>
			    </form>
			</div>
		<script type="text/javascript">
			var countdown = 60;
			function sendsms(val){
				if($("input[name='phone']").val()==''){
					alert("{pigcms{:L('_B_LOGIN_ENTERPHONENO_')}");
				}else{
					
					if(countdown==60){
						$.ajax({
							url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send',
							type: 'POST',
							dataType: 'json',
							data: {phone: $("input[name='phone']").val()},

						});
					}
					if (countdown == 0) {
						val.removeAttribute("disabled");
						val.innerText="获取短信验证码";
						countdown = 60;
						//clearTimeout(t);
					} else {
						val.setAttribute("disabled", true);
						val.innerText="重新发送(" + countdown + ")";
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
		</script>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/bind_user.js"></script>
    </div>
		<include file="Public:footer"/>
	</body>
</html>