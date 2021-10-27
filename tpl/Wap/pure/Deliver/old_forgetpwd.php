<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{:L('_COURIER_CENTER_')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    body {
        padding: 0px;
        margin: 0px auto;
        font-size: 14px;
        min-width: 320px;
        max-width: 100%;
        background-color: #f4f4f4;
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    section{
        position: absolute;
        top: 5%;
        width: 100%;
    }
    li input {
        width: 94%;
        height: 15px;
        padding: 10px 0;
        text-indent: 10px;
        color: #1b9dff;
        font-size: 14px;
        background-color: transparent;
        border-bottom: 1px solid;
        margin-left: 3%;
        margin-top: 10px;
    }
    li input:focus{
        border-bottom: 1px solid #FF0000;
    }
    li.Landd input {
        background: #1b9dff;
        background-color: rgb(27, 157, 255);
        color: #fff;
        text-indent: 0px;
        font-size: 16px;
        margin-top: 50px;
        padding: 0px;
        height: 40px;
    }
    #send_code{
        background: #1b9dff;
        background-color: rgb(27, 157, 255);
        color: #fff;
        text-indent: 0px;
        font-size: 14px;
        padding: 0px;
        height: 40px;
    }
    input#f_name,input#l_name,input#sms_code,#send_code{
        width: 45%;
    }
</style>
<body style="background:url({pigcms{$static_path}images/login_02.jpg) left bottom no-repeat #ebf3f8; background-size: 100% 137px;">
	<section>
	<div class="Land_top">
		<h2>{pigcms{:L('_COURIER_CENTER_')}</h2>
        <h2>{pigcms{:L('_B_D_LOGIN_KEYBACK_')}</h2>
	</div>
	<div id="reg_list">
		<ul>
			<li>
			  	<input type="text" placeholder="{pigcms{:L('_B_D_LOGIN_TEL_')}*" id="mobile">
			</li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_B_D_LOGIN_FILLMESSAGE_')}*" id="sms_code">
                <button class="btn" id="send_code">{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}</button>
            </li>
			<li>
				<input type="password" placeholder="{pigcms{:L('_B_D_LOGIN_KEY1_')}*" id="pwd">
			</li>
            <li>
                <input type="password" placeholder="{pigcms{:L('_B_D_LOGIN_CONFIRMKEY_')}*" id="c_pwd">
            </li>
            <li class="Landd">
                <input type="button" value="{pigcms{:L('_BACK_SUBMIT_')}" id="reg_form" style="background-color: #FF0000;width: 50%;margin-left: 25%;">
            </li>
            <li class="Landd">
                <input type="button" value="{pigcms{:L('_COURIER_LOGIN_')}" id="login_btn" style="background-color: #1b9dff;width: 50%;margin-left: 25%;">
            </li>
		</ul>
	</div>
	</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript">
$("body").css({"height":$(window).height()});

$('#login_btn').click(function () {
    window.location.href = "{pigcms{:U('Deliver/login')}";
});

$("#reg_form").click(function () {
    $(this).attr("disabled","disabled");
    if(check_form()){
        var form_data = {
            'phone':$('#mobile').val(),
            'sms_code':$('#sms_code').val(),
            'password':$('#pwd').val()
        };
        $.ajax({
            url: "{pigcms{:U('Deliver/forgetpwd')}",
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success:function(date){
                if(date.error_code){
                    show_tip(date.msg,$("#sms_code"));
                    $("#reg_form").removeAttr("disabled");
                }else{
                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg,skin: 'msg', time:1,end:function () {
                        window.parent.location = "{pigcms{:U('Deliver/login')}";
                    }});
                }
            }

        });
    }else{
        $(this).removeAttr("disabled");
    }

});

$("#send_code").click(function () {
    if($("#mobile").val() == ''){
       show_tip("{pigcms{:L('_B_LOGIN_ENTERPHONENO_')}",$("mobile"));
       return false;
    }else if(!checkPhone($("#mobile").val())){
        show_tip("{pigcms{:L('_B_LOGIN_ENTERGOODNO_')}",$("mobile"));
        return false;
    }
    sendsms(this);
});
var countdown = 60;
function sendsms(val) {
    if (countdown == 60) {
        $.ajax({
            url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send_forget_deliver',
            type: 'POST',
            dataType: 'json',
            data: {phone: $("#mobile").val(), reg: 1},
            success: function (date) {
                if (date.error_code) {
                    layer.open({
                        title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                        content: date.msg,
                        btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]
                    });
                }
            }

        });
    }
    if (countdown == 0) {
        val.removeAttribute("disabled");
        val.innerText = "{pigcms{:L('_B_D_LOGIN_RECEIVEMESSAGE_')}";
        countdown = 60;
        //clearTimeout(t);
    } else {
        val.setAttribute("disabled", true);
        val.innerText = "{pigcms{:L('_B_D_LOGIN_SENDAGAIN_')}(" + countdown + ")";
        countdown--;
        setTimeout(function () {
            sendsms(val);
        }, 1000)
    }
}

function checkPhone(phone) {
    if(!/^\d{10,}$/.test(phone)){
        return false;
    }
    return true;
}

function check_form() {
    var is_check = true;
    $("#reg_list").find('input').each(function () {
        if($(this).val() == ''){
            show_tip("{pigcms{:L('_PLEASE_INPUT_ALL_')}",$(this));
            is_check = false;
            return false;
        }
    });

    if(is_check && !checkPhone($('#mobile').val())){
        is_check = false;
        show_tip("{pigcms{:L('_B_LOGIN_ENTERGOODNO_')}",$('#mobile'));
    }

    if(is_check && $('#pwd').val() != $('#c_pwd').val()){
        is_check = false;
        show_tip("{pigcms{:L('_B_LOGIN_DIFFERENTKEY_')}",$('#pwd'));
    }

    if(is_check && $('#pwd').val().length < 6){
        is_check = false;
        show_tip("{pigcms{:L('_B_D_LOGIN_6KEYWORD_')}",$('#pwd'));
    }

    return is_check;
}

function show_tip(msg,input) {
    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: msg, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],end:function () {
         input.focus();
    }});
}
</script>
</html>