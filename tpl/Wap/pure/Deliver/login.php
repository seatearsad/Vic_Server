<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;application/json" />
    <link rel="manifest" href="/manifest_courier.json">
<title>{pigcms{:L('_COURIER_LOGIN_')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
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
<body style="background:url({pigcms{$static_path}images/login_02.jpg) left bottom no-repeat #ebf3f8; background-size: 100% 137px;">
	<section class="Land">
	<div class="Land_top">
		<span class="fillet" style="background: url(<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>) center no-repeat; background-size: contain;"></span>
		<h2>{pigcms{:L('_COURIER_CENTER_')}</h2>
	</div>
	<div class="Land_end">
		<ul>
			<li class="number">
			  	<input type="text" placeholder="{pigcms{:L('_B_D_LOGIN_TEL_')}" id="login_phone">
				<a href="javascript:void(0)"></a>
			</li>
			<li class="Password">
				<input type="password" placeholder="{pigcms{:L('_B_D_LOGIN_KEY1_')}" id="login_pwd">
				<a href="javascript:void(0)"></a>
			</li>
            <li id="findpwd" style="text-align: center;color: #999999;margin-bottom: 5px;cursor: pointer;">
                Forget password? Find my password
            </li>
			<li class="Landd">
				<input type="button" value="{pigcms{:L('_B_D_LOGIN_LOGIN1_')}" id="login_form">
			</li>
            <li class="Landd" style="margin-bottom: 30px;">
                <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                <input type="button" value="English" class="lang_en" id="lang">
                <php>}else{</php>
                <input type="button" value="Chinese" class="lang_cn" id="lang">
                <php>}</php>
            </li>
            <li class="Landd">
                <input type="button" value="{pigcms{:L('_B_D_LOGIN_REG2_')}" id="reg_form" style="background-color: #FF0000;width: 50%;margin-left: 25%;">
            </li>
		</ul>
	</div>     
	</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript">
var store_index = "{pigcms{:U('Deliver/index')}";
<if condition="!empty($refererUrl)">
	store_index = "{pigcms{$refererUrl}";
</if>
var openid = false;
<if condition="isset($openid) AND !empty($openid)">
	openid = "{pigcms{$openid}";
</if>
$("#findpwd").click(function () {
    window.location.href = "{pigcms{:U('Deliver/forgetpwd')}";
});
$(function(){
    var ua = navigator.userAgent;
    // alert(ua);
	var is_click_login = false;
	$('#login_form').click(function(){
		if (is_click_login) return false;
		is_click_login = true;
		if ($('#login_phone').val()=='') {
			layer.open({title:["{pigcms{:L('_B_D_LOGIN_TIP2_')}：",'background-color:#FF658E;color:#fff;'],content:"{pigcms{:L('_B_LOGIN_ENTERPHONENO_')}",btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],end:function(){}});
			is_click_login = false;
			return false;
		} else if ($('#login_pwd').val()=='') {
			layer.open({title:["{pigcms{:L('_B_D_LOGIN_TIP2_')}：",'background-color:#FF658E;color:#fff;'],content:"{pigcms{:L('_B_LOGIN_ENTERKEY_')}",btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],end:function(){}});
			is_click_login = false;
			return false;
		} else {
			$.post("{pigcms{:U('Deliver/login')}", {'phone':$('#login_phone').val(), 'pwd':$('#login_pwd').val()}, function(result) {
				is_click_login = false;
				if (result) {
                    //webView调用
                    if(ua.match(/TuttiDeliver/i))
                        window.webkit.messageHandlers.getUserMessage.postMessage([$('#login_phone').val(),$('#login_pwd').val()]);

					if (result.error == 0 && result.is_bind == 0 && openid) {
						  layer.open({
							title:['提示：','background-color:#FF658E;color:#fff;'],
							content:'系统检测到您是在微信中访问的，是否需要绑定微信号，下次访问可以免登录！',
							btn: ['是', '否'],
							shadeClose: false,
							yes: function(){
								$.post("/wap.php?g=Wap&c=Deliver&a=freeLogin",function(ret){
									if(!ret.error){
										layer.open({title:['成功提示：','background-color:#FF658E;color:#fff;'],content:'恭喜您绑定成功！',btn: ['确定'],end:function(){window.parent.location = store_index;}});
									}else{
										layer.open({
											title:['错误提示：','background-color:#FF658E;color:#fff;'],
											content:ret.msg,
											btn: ['确定'],
											end:function(){
												window.parent.location = store_index;
											}
										});
									}
								},'JSON');
	
							}, no: function(){
								setTimeout(function(){
									window.parent.location = store_index;
								},1000);
							}
						});
					} else if(result.error == 0){
						setTimeout(function(){
							window.parent.location = store_index;
						},1000);
					} else {
						layer.open({content: result.msg, skin: 'msg', time: 2});
					}
				} else {
					layer.open({title:['登录提示：','background-color:#FF658E;color:#fff;'],content:'登录出现异常，请重试~',btn: ['确定'],end:function(){}});
				}
			},'JSON');
		}
		return false;
	});
});
$("body").css({"height":$(window).height()});
$(".Land_end input").focus(function(){
	$(this).siblings("a").show();
});
$(".Land_end a").click(function(){
	$(this).hide();
	$(this).siblings("input").val("");
});

function putUserNP(name,password) {
    $('#login_phone').val(name);
    $('#login_pwd').val(password);
}

$("#reg_form").click(function () {
    window.location.href = "{pigcms{:U('Deliver/reg')}";
});
</script>   
</html>