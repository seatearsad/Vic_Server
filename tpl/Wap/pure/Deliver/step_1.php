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
        top: 2%;
        width: 100%;
        font-size: 10px;
        color: #666666;
    }
    #step_now{
        width:80%;
        margin: 20px auto;
        font-size: 0;
    }
    #step_now div{
        font-size: 10px;
        text-align: left;
    }
    #step_now ul{
        margin-top: 2px;
    }
    #step_now li{
        display: inline-block;
        width: 25%;
        height: 5px;
        background-color: #F4F4F4;
        margin-top: 0;
    }
    #step_now li:nth-child(1).act{
        background-color: #ffde59;
    }
    #step_now li:nth-child(2).act{
        background-color: #ffbd59;
    }
    #step_now li:nth-child(3).act{
        background-color: #ffa52d;
    }
    #step_now li:nth-child(4).act{
        background-color: #ffa99a;
    }
    #memo{
        width:80%;
        margin: 20px auto 5px auto;
        text-align: center;
    }
    li{
        text-align: center;
        margin-top: 10px;
    }
    li input {
        width: 55%;
        height: 15px;
        padding: 8px 0;
        text-indent: 10px;
        color: #333333;
        background-color: white;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 12px;
    }
    li select{
        width: 55%;
        height: 31px;
        text-indent: 5px;
        border-radius: 5px;
    }
    li.Landd input {
        background: #ffa52d;
        color: #fff;
        text-indent: 0px;
        font-size: 12px;
        margin-top: 30px;
        margin-left: 0;
        padding: 0px;
        height: 30px;
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
    li span{
        text-align: left;
        display: inline-block;
        width: 35%;
        font-size: 12px;
    }
    input#sms_code{
        width: 25%;
    }
    #send_code{
        width: 30%;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px">Become a Tutti Courier</div>
        <div style="color: #999999;font-size: 10px;margin: 10px auto;width: 90%;">
            Thank you for signing up with Tutti Courier! Your account has been created successfully. Please complete the following steps to get started!
        </div>
    </div>
    <div id="step_now">
        <div>1.Information Needed</div>
        <ul>
            <li class="act"></li><li></li><li></li><li></li>
        </ul>
    </div>
    <div id="memo">
        All information are kept securely and used for delivery and taxation purpose.
    </div>
    <div id="reg_list">
        <ul>
            <li>
                <span>Delivery City:</span>
                <select name="city_id" id="city_id">
                    <volist name="city_list" id="city">
                    <option value="{pigcms{$city['area_id']}">{pigcms{$city['area_name']}</option>
                    </volist>
                </select>
            </li>
            <li>
                <span>{pigcms{:L('_ADDRESS_TXT_')}:</span>
                <input type="text" placeholder="{pigcms{:L('_ADDRESS_TXT_')}" id="address">
            </li>
            <li>
                <span>SIN Number:</span>
                <input type="text" placeholder="SIN Number" id="sin_num">
            </li>
            <li class="Landd">
                <input type="button" value="Continue" id="reg_form" style="background-color: #ffa52d;width: 50%;">
            </li>
        </ul>
        <input type="hidden" name="lng" id="lng">
        <input type="hidden" name="lat" id="lat">
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
                'address':$('#address').val(),
                'lng':$('#lng').val(),
                'lat':$('#lat').val(),
                'city_id':$('#city_id').val(),
                'sin_num':$('#sin_num').val()
            };
            $.ajax({
                url: "{pigcms{:U('Deliver/step_1')}",
                type: 'POST',
                dataType: 'json',
                data: form_data,
                success:function(date){
                    if(date.error_code){
                        show_tip(date.msg,$("#sin_num"));
                        $("#reg_form").removeAttr("disabled");
                    }else{
                        // layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg,skin: 'msg', time:1,end:function () {
                        //         window.parent.location = "{pigcms{:U('Deliver/step_2')}";
                        // }});
                        window.parent.location = "{pigcms{:U('Deliver/step_2')}";
                    }
                }

            });
        }else{
            $(this).removeAttr("disabled");
        }

    });

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
        // $("#reg_list").find('input').each(function () {
        //     if($(this).val() == ''){
        //         show_tip("{pigcms{:L('_PLEASE_INPUT_ALL_')}",$(this));
        //         is_check = false;
        //         return false;
        //     }
        // });
        if($("input[name='lng']").val() == '')
            $("input[name='lng']").val(0);
        if($("input[name='lat']").val() == '')
            $("input[name='lat']").val(0);

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