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
<link href="{pigcms{$static_path}css/deliver.css?v=2.0.0" rel="stylesheet"/>
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
        min-width: 320px
        max-width: 640px;
        background-color: #f4f4f4;
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    section{
        position: absolute;
        top: 5%;
        max-width: 640px;
        width: 100%;
    }
    li{
        text-align: center;
        margin-top: 10px;
    }
    li input {
        width: 80%;
        height: 25px;
        padding: 8px 0;
        text-indent: 10px;
        color: #000000;
        background-color: white;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 14px;
    }
    li span {
        width: 80%;
        height: 25px;
        padding: 8px 0;
        color: #787c81;
        font-size: 14px;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 12px;
        display: inline-block;
        text-align: left;
    }
    li.Landd input {
        background: #ffa52d;
        color: #fff;
        text-indent: 0px;
        font-size: 14px;
        margin-left: 0;
        padding: 0px;
        height: 38px;
        width: 80%;
    }
    #send_code{
        background: #ffa52d;
        color: #fff;
        text-indent: 0px;
        border-radius: 2px;
        font-size: 10px;
        padding: 0px;
        height: 30px;
    }
    input#sms_code{
        width: 50%;
    }
    #send_code{
        width: 30%;
        height: 38px;
        font-size: 14px;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
	<section>
	<div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
		<div style="margin-top:10px;margin-bottom: 20px;font-weight: bold;font-size: 14px;">{pigcms{:L('_ND_BECOMEACOURIER_')}</div>
	</div>
	<div id="reg_list">
		<ul>
            <li>
                <input type="text" placeholder="{pigcms{:L('_FIRST_NAME_')}*" id="f_name">
            </li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_LAST_NAME_')}*" id="l_name">
            </li>

            <li>
                <input type="text" placeholder="{pigcms{:L('_EMAIL_TXT_')}*" id="email">
            </li>
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
            <li>
                <span>By clicking “Sign Up”, I agree to Tutti’s Terms of Use and acknowledge that I have read the <a href="{pigcms{:U('Deliver/policy')}" style="text-decoration: underline">Privacy Policy.</a></span>
            </li>
            <li class="Landd">
                <input type="button" value="{pigcms{:L('_ND_REGISTER_')}" id="reg_form" style="background-color: #ffa52d;">
            </li>
            <li class="Landd">
                <span style="color: #000;text-align: left;font-size:13px;font-weight: bold">Already have an account? <a href="#" id="login_btn" style="text-decoration: underline">Sign in</a> here.</span>
            </li>
		</ul>
	</div>
	</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
<script type="text/javascript">
$("body").css({"height":$(window).height()});

$('#login_btn').click(function () {
    window.location.href = "{pigcms{:U('Deliver/login')}";
});

$("#reg_form").click(function () {
    $(this).attr("disabled","disabled");
    if(check_form()){
        var form_data = {
            'first_name':$('#f_name').val(),
            'birthday':$('#birthday').val(),
            'last_name':$('#l_name').val(),
            'email':$('#email').val(),
            'phone':$('#mobile').val(),
            'sms_code':$('#sms_code').val(),
            'password':$('#pwd').val()
        };
        $.ajax({
            url: "{pigcms{:U('Deliver/reg')}",
            type: 'POST',
            dataType: 'json',
            data: form_data,
            success:function(date){
                if(date.error_code){
                    show_tip(date.msg,$("#sms_code"));
                    $("#reg_form").removeAttr("disabled");
                }else{
                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg,skin: 'msg', time:1,end:function () {
                        window.parent.location = "{pigcms{:U('Deliver/step_1')}";
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
            url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send_deliver',
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
function checkMail(mail) {
    var reg = /\w+[@]{1}\w+[.]\w+/;
    if(!reg.test(mail)){
        return false;
    }
    return true;
}

function check_form() {
    var is_check = true;
    $("#reg_list").find('input').each(function () {
        if($(this).val() == ''){
            show_tip("Please complete all required fields.",$(this));
            is_check = false;
            return false;
        }
    });

    if(is_check && !checkMail($('#email').val())){
        is_check = false;
        show_tip("{pigcms{:L('_BACK_RIGHT_EMAIL_')}",$('#email'));
    }

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

function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

var theme = "ios";
var mode = "scroller";
var display = "bottom";
var lang="en";

$('#birthday').mobiscroll().date({
    theme: theme,
    mode: mode,
    display: display,
    dateFormat: 'yyyy-mm-dd',
    dateOrder:'yymmdd',
    lang: lang
});
</script>
</html>