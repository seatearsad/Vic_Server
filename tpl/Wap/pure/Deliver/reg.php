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
        <h2>{pigcms{:L('_B_D_LOGIN_REG2_')}</h2>
	</div>
	<div id="reg_list">
		<ul>
            <li>
                <input type="text" placeholder="{pigcms{:L('_FIRST_NAME_')}*" id="f_name">
                <input type="text" placeholder="{pigcms{:L('_LAST_NAME_')}*" id="l_name">
            </li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_EMAIL_TXT_')}*" id="email">
            </li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_ADDRESS_TXT_')}*" id="address">
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
            <li class="Landd">
                <input type="button" value="{pigcms{:L('_B_D_LOGIN_REG2_')}" id="reg_form" style="background-color: #FF0000;width: 50%;margin-left: 25%;">
            </li>
            <li class="Landd">
                <input type="button" value="{pigcms{:L('_COURIER_LOGIN_')}" id="login_btn" style="background-color: #1b9dff;width: 50%;margin-left: 25%;">
            </li>
		</ul>
	</div>
        <input type="hidden" name="lng" id="lng">
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="city_id" id="city_id">
        <input type="hidden" name="province_id" id="province_id">
	</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en" async defer></script>
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
            'last_name':$('#l_name').val(),
            'address':$('#address').val(),
            'email':$('#email').val(),
            'phone':$('#mobile').val(),
            'sms_code':$('#sms_code').val(),
            'password':$('#pwd').val(),
            'lng':$('#lng').val(),
            'lat':$('#lat').val(),
            'city_id':$('#city_id').val(),
            'province_id':$('#province_id').val()
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
    }
    sendsms(this);
});
var countdown = 60;
function sendsms(val) {
    if(countdown==60){
        $.ajax({
            url: '{pigcms{$config.site_url}/index.php?g=Index&c=Smssend&a=sms_send_deliver',
            type: 'POST',
            dataType: 'json',
            data: {phone: $("#mobile").val(),reg:1},
            success:function(date){
                if(date.error_code){
                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
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
            show_tip("{pigcms{:L('_PLEASE_INPUT_ALL_')}",$(this));
            is_check = false;
            return false;
        }
    });

    if(is_check && !checkMail($('#email').val())){
        is_check = false;
        show_tip('Please enter the correct email address',$('#email'));
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

$('#address').focus(function () {
    initAutocomplete();
});

var autocomplete;
function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), {types: ['geocode'],componentRestrictions: {country: ['ca']}});
    autocomplete.addListener('place_changed', fillInAddress);
}
function fillInAddress() {
    var place = autocomplete.getPlace();
    $("input[name='lng']").val(place.geometry.location.lng());
    $("input[name='lat']").val(place.geometry.location.lat());

    var geocoder = new google.maps.Geocoder();
    var request = {
        location:{lat:place.geometry.location.lat(), lng:place.geometry.location.lng()}
    }
    geocoder.geocode(request, function(results, status){
        if(status == 'OK') {
            console.log(results[0].address_components);
            var add_com = results[0].address_components;
            var is_get_city = false;
            for(var i=0;i<add_com.length;i++){
                if(add_com[i]['types'][0] == 'locality'){
                    is_get_city = true;
                    var city_name = add_com[i]['long_name'];
                    $.post("{pigcms{:U('Deliver/ajax_city_name')}",{city_name:city_name},function(result){
                        if (result.error == 1){
                            $('#city_id').val(0);
                            $('#province_id').val(0);
                        }else{
                            $('#city_id').val(result['info']['city_id']);
                            $('#province_id').val(result['info']['province_id']);
                        }
                    },'JSON');
                }
            }
            if(!is_get_city) {
                $('#city_id').val(0);
                $('#province_id').val(0);
            }
        }
        console.log(results);
    });
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
</script>
</html>