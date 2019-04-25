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
    #sms_code{
        width: 45%;
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
    #reg_send_sms{
        width: 50%;
        margin-left: 3%;
        height: 50px;
        background-color: #ffa52d;
        color: white;
        border: 0px;
        font-size: 1em;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
    }
</style>
<body>
    <div class="sign_title">
        Sign up
    </div>
    <div class="sign_input">
        <input type="text" name="nickname" placeholder="Full Name" >
        <input type="tel" name="phone" placeholder="Phone Number">
        <input id="sms_code" name="sms_code" type="text" placeholder="Code" />
        <button id="reg_send_sms" type="button" onclick="sendsms(this)">{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}</button>
        <input type="text" name="email" placeholder="Email Address" >
        <input type="password" placeholder="Password" name="password" />
        <input type="password" placeholder="Confirm Password" name="con_password" />
    </div>
    <div class="sign_btn">
        Sign up
    </div>
    <div class="forget_div">
        Already a customer? <a href="{pigcms{:U('Login/index')}">Sign in</a>
    </div>
    <div class="black_line"></div>
    <div class="or_div">or</div>
    <script src="{pigcms{$static_public}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
    <script>
        function show_msg(msg) {
            layer.open({
                title: "{pigcms{:L('_STORE_REMIND_')}",
                time: 1,
                content: msg
            });
        }
        var countdown = 60;
        function sendsms(val){
            if($("input[name='phone']").val()==''){
                show_msg("{pigcms{:L('_B_D_LOGIN_BLANKNUM_')}");
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
                            }else{
                                show_msg(date.msg);
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
        
        $('.sign_btn').click(function () {
            var is_tip = false;
            var re_data = {};
            $('.sign_input').find('input').each(function () {
                if($(this).val() == ''){
                    is_tip = true;
                }else{
                    re_data[$(this).attr('name')] = $(this).val();
                }
            });
            if($("input[name='password']").val() != $("input[name='con_password']").val()){
                show_msg("{pigcms{:L('_B_LOGIN_DIFFERENTKEY_')}");
            }else {
                if (is_tip) {
                    show_msg("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
                } else {
                    var phone = $("input[name='phone']").val();
                    var password = $("input[name='password']").val();
                    var sms_code = $("input[name='sms_code']").val();
                    $.post("{pigcms{:U('Login/reg')}",{phone:phone,password:password,sms_code:sms_code},function(result){
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
        });
    </script>
</body>
</html>