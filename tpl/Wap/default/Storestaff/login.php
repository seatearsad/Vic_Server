<!DOCTYPE html>
<html>
<head>
	<title>{pigcms{:L('_STAFF_LOGIN_')}</title>
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8;application/json" />
    <link rel="manifest" href="/manifest_partner.json">
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
    <script>
        // 检测浏览器是否支持SW
        if(navigator.serviceWorker != null){
            navigator.serviceWorker.register('/sw.js')
                .then(function(registartion){
                    console.log('支持sw:',registartion.scope)
                }).catch(function (err) {
                console.log('不支持sw:',err);
            })
        }else{
            console.log('SW run fail');
        }

        window.addEventListener('beforeinstallprompt', function (e) {
            e.userChoice.then(function (choiceResult) {
                if (choiceResult.outcome === 'dismissed') {
                    //console.log('用户取消安装应用');
                    showmessage('用户取消安装应用');
                }else{
                    //console.log('用户安装了应用');
                    showmessage('用户安装了应用');
                }
            });
        });
    </script>
</head>
<body id="index" data-com="pagecommon">
        <!--<header  class="navbar">
            <h1 class="nav-header">店员登录 - {pigcms{$config.site_name}</h1>
        </header>-->
        <div id="container">
        	<div id="tips" style="-webkit-transform-origin:0px 0px;opacity:1;-webkit-transform:scale(1, 1);"></div>
			<div id="login">
			    <form id="login_form" autocomplete="off" method="post" action="{pigcms{:U('Storestaff/login')}">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            		<dd class="dd-padding">
			            			<input class="input-weak" type="text" name="account" id="login_account" placeholder="<if condition="$language eq 'en'">Account</if><if condition="$language eq 'cn'">请输入您的店员账号</if>"/>
			            		</dd>
			            		<dd class="dd-padding">
									<input class="input-weak" type="password" name="pwd" id="login_pwd" placeholder="<if condition="$language eq 'en'">Password</if><if condition="$language eq 'cn'">请输入您的店员密码</if>"/>
			            		</dd>
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<button type="submit" class="btn btn-larger btn-block" style="background-color: #ffa64d">
						<if condition="$language eq 'en'">Login</if>
						<if condition="$language eq 'cn'">登录</if>
						</button>
						<if condition="$language eq 'cn'">
						<button type="button" class="btn btn-larger btn-block" style="margin-top:10px;background-color:green" onclick="window.location.href='/wap.php?g=Wap&c=Storestaff&a=login&lang=en'">English</button>
						</if>
						<if condition="$language eq 'en'">
						<button type="button" class="btn btn-larger btn-block" style="margin-top:10px;background-color:green"onclick="window.location.href='/wap.php?g=Wap&c=Storestaff&a=login&lang=cn'">Chinese</button>
						</if>
			        </div>
			    </form>
			</div>
		</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
        <script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
		<script src="{pigcms{$static_public}js/laytpl.js"></script>
	    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script type="text/javascript">
            var ua = navigator.userAgent;

            var user_name = $.cookie('merchant_user');
            var user_pass = $.cookie('merchant_pass');

            if(typeof (user_name) != "undefined" && typeof (user_pass) != "undefined"){
                if(!ua.match(/TuttiPartner/i)){
                    $('#login_account').val(user_name);
                    $('#login_pwd').val(user_pass);
                }
            }


            var device_token = '';
            function pushDeviceToken(token) {
                device_token = token;
                alert(device_token);
            }

            function putUserNP(name,password) {
                $('#login_account').val(name);
                $('#login_pwd').val(password);
            }
			var static_public="{pigcms{$static_public}",static_path="{pigcms{$merchantstatic_path}",login_check="{pigcms{:U('Storestaff/login')}",store_index="{pigcms{:U('Storestaff/index')}";

			<if condition="!empty($refererUrl)">
			 store_index="{pigcms{$refererUrl}";
			</if>

	var openid=false;
	<if condition="isset($openid) AND !empty($openid)">
	   openid="{pigcms{$openid}";
	</if>

	$(function(){
	$('#login_account').focus();
	$('#login_form').submit(function(){
		if($('#login_account').val()==''){
			alert('Account is empty');
			$('#login_account').focus();
			return false;
		}else if($('#login_pwd').val()==''){
			alert('Please enter your password');
			$('#login_pwd').focus();
		}else{
			$.post(login_check,$("#login_form").serialize(),function(result){
				//result = $.parseJSON(result);
				if(result){
                    if(result.error == 0){
                        if(ua.match(/TuttiPartner/i))
                            window.webkit.messageHandlers.getUserMessage.postMessage([$('#login_account').val(),$('#login_pwd').val()]);
                    }
					if(result.error == 0 && openid){
					  layer.open({
						title:['提示：','background-color:#FF658E;color:#fff;'],
						content:'系统检测到您是在微信中访问的，是否需要绑定微信号，下次访问可以免登录！',
						btn: ['是', '否'],
						shadeClose: false,
						yes: function(){
							/*layer.open({
								type: 2,
								content: '绑定中，请稍后'
							});*/
							$.post("/wap.php?g=Wap&c=Storestaff&a=freeLogin",{},function(ret){
								//layer.closeAll();
								if(!ret.error){
									layer.open({title:['成功提示：','background-color:#FF658E;color:#fff;'],content:'恭喜您绑定成功！',btn: ['确定'],end:function(){window.parent.location = store_index;}});
								}else{
									layer.open({
										title:['错误提示：','background-color:#FF658E;color:#fff;'],
										content:'绑定失败，请下次登录再试',
										btn: ['确定'],
										end:function(){
											window.parent.location = store_index;
										}
									});
								}
								/* setTimeout(function(){
									 window.parent.location = store_index;
								 },1000);*/
							},'JSON');

						}, no: function(){
							setTimeout(function(){
                                window.parent.location = store_index;
							},1000);
						}
					});

					/*alert(result.msg);
					setTimeout(function(){
						window.parent.location = store_index;
					},1000);*/

					}else if(result.error == 0 && !openid){
						setTimeout(function(){
                            if(!ua.match(/TuttiPartner/i)){
                                $.cookie('merchant_user',$('#login_account').val());
                                $.cookie('merchant_pass',$('#login_pwd').val());
                            }
							window.parent.location = store_index;
						},1000);
					}else{
						$('#login_'+result.dom_id).focus();
						alert(result.msg);
					}
				}else{
					alert('登录出现异常，请重试！');
				}
			},'JSON');
		}
		return false;
	});
});
	</script>
	</body>
</html>