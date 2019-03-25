<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_B_D_LOGIN_LOGIN1_')} - {pigcms{:L('_VIC_NAME_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name='apple-touch-fullscreen' content='yes'/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="format-detection" content="address=no"/>
    <script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
</head>
<style>
    *{
        margin: 0px;
        box-sizing: border-box;
        font-family: Helvetica;
        -moz-osx-font-smoothing: grayscale;
    }
    .sign_title{
        margin-top: 50px;
        text-align: center;
        font-size: 2.2em;
        font-weight: bold;
    }
    .sign_input{
        width: 75%;
        margin: 50px auto 0 auto;
    }
    .sign_input input{
        width: 100%;
        height: 50px;
        border-left: 0;
        border-right: 0;
        border-top: 0;
        border-bottom: 2px solid #666666;
        font-size: 1.2em;
        padding-left: 5px;
        margin-top: 10px;
        -moz-border-radius: 0px;
        -webkit-border-radius: 0px;
        border-radius: 0px;
    }
    .sign_input input:focus{
        border-bottom: 2px solid #ffa52d;
    }
    .forget_div{
        margin-top: 10px;
        text-align: center;
        font-size: 0.95em;
        color: #666666;
    }
    .forget_div a{
        color: #666666;
    }
    .sign_btn{
        margin: 60px auto 0 auto;
        text-align: center;
        width: 80%;
        height: 45px;
        line-height: 45px;
        font-size: 1.8em;
        font-weight: bold;
        background-color: #ffa52d;
        color: #ffffff;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        cursor: pointer;
    }
    .black_line{
        width: 100%;
        height: 2px;
        background-color:#333333;
        margin-top: 80px;
    }
    .or_div{
        width: 12%;
        margin: -10px auto 0 auto;
        text-align: center;
        height: 20px;
        line-height: 20px;
        background-color: white;
        font-size: 1.2em;
        color: #666666;
    }
</style>
<body>
    <div class="sign_title">
        Sign in
    </div>
    <div class="sign_input">
        <input type="tel" name="phone" placeholder="Phone Number" value="{pigcms{$_COOKIE.login_name}">
        <input type="password" placeholder="Password" name="password" />
    </div>
    <div class="forget_div">
        Forget password? <a href="#">Find my password</a>
    </div>
    <div class="sign_btn">
        Sign in
    </div>
    <div class="forget_div">
        New to TUTTI? <a href="{pigcms{:U('Login/reg')}">Sign up</a>
    </div>
    <div class="black_line"></div>
    <div class="or_div">or</div>
    <script src="{pigcms{$static_public}layer/layer.m.js"></script>
    <script>
        $('.sign_btn').click(function () {
            var phone = $('input[name=phone]').val();
            var password = $('input[name=password]').val();
            if(phone == '' || password == ''){
                layer.open({
                    title: "{pigcms{:L('_STORE_REMIND_')}",
                    time: 1,
                    content: "{pigcms{:L('_PLEASE_INPUT_ALL_')}"
                });
            }else{
                $.post("{pigcms{:U('Login/index')}",{phone:phone,password:password},function(result){
                    if(result.status == '1'){
                        window.location.href = "{pigcms{$referer}";
                    }else{
                        layer.open({
                            title: "{pigcms{:L('_STORE_REMIND_')}",
                            time: 1,
                            content: result.info
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>