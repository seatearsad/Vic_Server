<include file="Public:header"/>

<body class="gray-bg" id="wrapper">
<!------------------------------------  START  -------------------------------------->

<div id="login" class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        <h1 class="logo-name">{pigcms{$config.site_name}</h1>
    </div>
    <h3>Welcome to TUTTI</h3>
    <p>Login in. </p>
    <form method="post" class="m-t" role="form"  id="form">
        <div class="form-group">
            <input id="account" name="account" type="text" class="form-control" placeholder="{pigcms{:L('_BACK_LOGIN_NAME_')}" required="" value="{pigcms{$_GET.account}">
        </div>
        <div class="form-group">
            <input id="pwd" name="pwd" type="password" class="form-control" placeholder="{pigcms{:L('_B_D_LOGIN_KEY1_')}" required="" value="{pigcms{$_GET.pwd}">
        </div>
        <div class="form-group" style="display: flex;">
            <input id="verify" name="verify" type="text" maxlength="4" class="form-control line-seperate" placeholder="{pigcms{:L('_BACK_VER_CODE_')}" required="" value="">
            <span id="verify_box" style="padding: 5px 0 0 10px;">
                <img src="{pigcms{:U('Login/verify')}" id="verifyImg" onclick="fleshVerify('{pigcms{:U('Login/verify')}')" title="{pigcms{:L('_BACK_RE_CODE_')}" alt="{pigcms{:L('_BACK_RE_CODE_')}"/>
                <a href="javascript:fleshVerify('{pigcms{:U('Login/verify')}')" id="fleshVerify">{pigcms{:L('_BACK_RE_CODE_')}</a>
            </span>
        </div>

<!--        <p>-->
<!--            <label>：</label>-->
<!--            <input class="text-input" type="text" name="account" id="account" value="{pigcms{$_GET.account}"/>-->
<!--        </p>-->
<!--        <p>-->
<!--            <label>{pigcms{:L('_B_D_LOGIN_KEY1_')}：</label>-->
<!--            <input class="text-input" type="password" name="pwd" id="pwd" value="{pigcms{$_GET.pwd}"/>-->
<!--        </p>-->
        <button type="submit" class="btn btn-primary block full-width m-b">{pigcms{:L('_B_D_LOGIN_LOGIN1_')}</button>
    </form>

    <p class="m-t"> <small>Copyright © 2021 Kavl Technology Ltd. All rights reserved</small> </p>

    <div class="lang_div">
        Language
        <div class="lang_select">
            <div class="lang_en">English</div>
            <div class="lang_cn">Chinese</div>
        </div>
    </div>
</div>
<style>
    .lang_div{
        float:right;
        width: 100px;
        text-align: center;
        line-height: 30px;
        background-color: white;
    }
    .lang_select{
        display: none;
        margin: 5px 0;
        cursor: pointer;
    }
</style>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">

    function fleshVerify(url){
        if (url=="") url="{pigcms{:U('Login/verify')}";
        var time = new Date().getTime();
        $('#verifyImg').attr('src',url+"&time="+time);
    }

    $(document).ready(function(){

        $('.scan_login').click(function(){
            art.dialog.open("{pigcms{:U('Login/see_admin_qrcode')}&t="+Math.random(),{
                init: function(){
                    var iframe = this.iframe.contentWindow;
                    window.top.art.dialog.data('login_iframe_handle',iframe);
                },
                id: 'login_handle',
                title:'请使用微信扫描二维码登录',
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
            return false;
        });

        $('#send_code').click(function(){
            $.post('{pigcms{:U("Login/send_code")}', {account:$('#account').val()}, function(response){
                if (response.errcode) {
                } else {
                }
            }, 'json');
        });

        //-----------------------------------------提交验证--------------------------------------

        if($('#account').val() == ''){
            $('#account').focus();
        }else if($('#pwd').val() == ''){
            $('#pwd').focus();
        }else{
            $('#verify').focus();
        }

        $('#form').submit(function(){
            var message_title="";
            var message_content="";
            if($('#account').val()==''){
                tutti_notifiction("error",'Invalid Username!')
                $('#account').focus();
            }else if($('#pwd').val()==''){
                tutti_notifiction("error",'Invalid Password!')
                $('#pwd').focus();
            }else if($('#verify').val().length!=4){
                tutti_notifiction("error",'Invalid Verification Code!')
                $('#verify').focus();
            }else{
                $.post(login_check,$("#form").serialize(),function(data){
                    if(data==1){
                        message_title = 'Success!';
                        message_content="Welcome to Tutti";
                        tutti_notification(
                            message_title,
                            message_content,
                            3000,
                            function () {
                            window.parent.location = system_index;
                            });
                        // //pic = 'ok';
                        // setTimeout(function(){
                        //
                        // },1000);
                    }else{
                        message_title = 'Error!';
                        if(data==-1){
                            $('#verify').focus();
                            message_content='Invalid Verification Code!';
                            fleshVerify("");
                        }else if(data==-2){
                            $('#account').focus();
                            message_content='Invalid Username!';
                        }else if(data==-3){
                            $('#pwd').focus();
                            message_content='Invalid Password!';
                        }
                        else if(data==-4){$('#account').focus();message_content='用户被禁止登录！';}
                        else if(data==-5){$('#account').focus();message_content='登录信息保存失败,请重新登录！';}
                        else{message_content='登录出现异常，请重试！';}

                        tutti_notification(message_title,message_content);
                    }

                });
            }
            return false;
        });
    });

    if(self!=top){window.top.location.href="{pigcms{:U('Index/index')}";}
    var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",login_check="{pigcms{:U('Login/check')}",system_index="{pigcms{:U('Index/index')}";

</script>

<script type="text/javascript" src="{pigcms{$static_path}jsuser/login.js"></script>

<!------------------------------------  END  -------------------------------------->

<include file="Public:footer"/>