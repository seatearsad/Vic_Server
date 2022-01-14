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
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
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
        top: 7%;
        width: 100%;
        font-size: 12px;
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
        margin-top: 15px;
    }
    li input {
        width: 80%;
        height: 25px;
        padding: 8px 0;
        text-indent: 10px;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 14px;
    }
    li input:disabled{
        background: #EEEEEE;
        opacity: 100%;
    }
    li input.sm {
        width: 39%;
        height: 25px;
        padding: 8px 0;
        text-indent: 10px;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 14px;
    }
    li select{
        width: 80%;
        height: 40px;
        text-indent: 5px;
        border-radius: 5px;
        font-size: 14px;
        color: black;
    }
    li.Landd input {
        background: #ffa52d;
        text-indent: 0px;
        font-size: 14px;
        margin-top: 30px;
        margin-left: 0;
        padding: 0px;
        color: white;
        height: 40px;
    }
    #send_code{
        background: #ffa52d;
        text-indent: 0px;
        border-radius: 2px;
        font-size: 10px;
        padding: 0px;
        height: 30px;
    }
    li div{
        display: inline-block;
        width: 80%;
        font-size: 12px;
    }
    input#sms_code{
        width: 25%;
    }
    #send_code{
        width: 30%;
    }
    .white_bg{
        background: #ffffff;
    }
    .gray_bg{
        background: #EEEEEE;
    }
    #vehicle_type{
        opacity: 100%;
    }
    #online_div{
        text-align: left;
        font-weight: bold;
        margin-top: 5px;
        width: 78%;
    }
    #city_id{
        background-color: white;
    }
    #reg_form:disabled{
        background-color: #999999;
    }
    .refresh{
        float: right;
        margin-top: 30px;
        margin-right: 20px;
        position: relative;
        z-index: 99;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<div class="refresh" id="refresh_btn">
    <span class="material-icons" style="color: #294068;font-size: 36px;">restart_alt</span>
</div>
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px;font-weight: bold;">{pigcms{:L('_ND_BECOMEACOURIER_')}</div>
        <div style="color: red;font-size: 10px;margin: 20px auto;width: 90%;">
            <!--            {pigcms{:L('_ND_ACCTSUCCESS_')}-->
            <!-- Registration is temporarily unavailable at this point due to shortage in delivery bags. We'll inform you by email when registration is back to active. We're sorry for the inconvenience and thank you for your understanding!-->
        </div>
    </div>
    <!--    <div id="step_now">-->
    <!--        <div>1.{pigcms{:L('_ND_INFORMATIONNEEDED_')}</div>-->
    <!--        <ul>-->
    <!--            <li class="act"></li><li></li><li></li><li></li>-->
    <!--        </ul>-->
    <!--    </div>-->
    <!--    <div id="memo">-->
    <!--        {pigcms{:L('_ND_INFORMATIONSECURE_')}-->
    <!--    </div>-->
    <div id="reg_list">
        <ul>
            <li>
                <select name="city_id" id="city_id" class="gray-bg" data-not-online="{pigcms{$not_online}">
                    <option value="0">------{pigcms{:L('_ND_DELIVERYCITY_')}------</option>
                    <volist name="city_list" id="city">
                        <option value="{pigcms{$city['area_id']}" <if condition="$user['city_id'] eq $city['area_id']">selected="selected"</if>>{pigcms{$city['area_name']}</option>
                    </volist>
                </select>
                <div id="online_div" style="display: none">Sorry, we’re temporarily not accepting new couriers in this city. We will notify you by email when we start accepting more applicants.</div>
            </li>
            <li>
                <select name="vehicle_type" class="gray-bg" id="vehicle_type" disabled="disabled">
                    <option value="0">------Vehicle Type------</option>
                    <option value="1" <if condition="$user['vehicle_type'] eq 1">selected="selected"</if>>Car</option>
                    <option value="2" <if condition="$user['vehicle_type'] eq 2">selected="selected"</if>>Bike</option>
                    <option value="3" <if condition="$user['vehicle_type'] eq 3">selected="selected"</if>>Motorcycle/Scooter</option>
                </select>
            </li>
            <li>
                <input type="text" class="gray-bg" placeholder="{pigcms{:L('_BIRTHDAY_TXT_')}*" id="birthday" name="birthday" disabled="disabled">
            </li>
            <li>
                <input type="text" class="gray-bg pac-target-input" placeholder="{pigcms{:L('_ND_ADDRESS_')}*" id="address" name="address" disabled="disabled">
            </li>
            <li>
                <input type="text" class="gray-bg" placeholder="Apartment, suite, unit, etc" id="apartment" name="apartment" disabled="disabled">
            </li>
            <li>
                <input type="text" class="sm gray-bg" placeholder="City*" id="city"  name="city" disabled="disabled">
                <input type="text" class="sm" placeholder="Province*" id="province"  name="province"  disabled="disabled">
            </li>
            <li>
                <input type="text" class="gray-bg" placeholder="Postal Code*" id="postalcode" name="postalcode" disabled="disabled">
            </li>

            <li class="Landd">
                <input type="button" value="Continue" id="reg_form" style="width: 80%;"  disabled="disabled">
            </li>
        </ul>
        <input type="hidden" name="lng" id="lng">
        <input type="hidden" name="lat" id="lat">
    </div>
    <div style="margin-bottom: 20px"></div>
</section>

</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
<script type="text/javascript">

    $("body").css({"height":$(window).height()});

    $("#city_id").change(function () {
        set_city_id($(this).val());
    });

    if($("#city_id").val() != 0) {
        set_city_id($("#city_id").val());
    }

    $('#refresh_btn').click(function () {
        if(typeof (window.linkJs) != 'undefined'){
            window.linkJs.reloadWebView();
        }else {
            window.location.reload();
        }
    });

    function close_input(){

        $("#vehicle_type").attr("disabled","disabled");
        $("#vehicle_type").removeClass("white_bg").addClass("gray_bg");
        $("#birthday").attr("disabled","disabled");
        $("#birthday").removeClass("white_bg").addClass("gray_bg");
        $("#address").attr("disabled","disabled");
        $("#address").removeClass("white_bg").addClass("gray_bg");
        $("#apartment").attr("disabled","disabled");
        $("#apartment").removeClass("white_bg").addClass("gray_bg");
        $("#city").attr("disabled","disabled");
        $("#city").removeClass("white_bg").addClass("gray_bg");
        $("#province").attr("disabled","disabled");
        $("#province").removeClass("white_bg").addClass("gray_bg");
        $("#postalcode").attr("disabled","disabled");
        $("#postalcode").removeClass("white_bg").addClass("gray_bg");
        $("#reg_form").attr("disabled","disabled");

    }
    function open_input(){

        $("#vehicle_type").removeAttr("disabled");
        $("#vehicle_type").removeClass("gray_bg").addClass("white_bg");
        $("#birthday").removeAttr("disabled");
        $("#birthday").removeClass("gray_bg").addClass("white_bg");
        $("#address").removeAttr("disabled");
        $("#address").removeClass("gray_bg").addClass("white_bg");
        $("#apartment").removeAttr("disabled");
        $("#apartment").removeClass("gray_bg").addClass("white_bg");
        $("#city").removeAttr("disabled");
        $("#city").removeClass("gray_bg").addClass("white_bg");
        $("#province").removeAttr("disabled");
        $("#province").removeClass("gray_bg").addClass("white_bg");
        $("#postalcode").removeAttr("disabled");
        $("#postalcode").removeClass("gray_bg").addClass("white_bg");
        $("#reg_form").removeAttr("disabled");
    }

    $('#login_btn').click(function () {
        window.location.href = "{pigcms{:U('Deliver/login')}";
    });

    function set_city_id(city_id){
        if (city_id>0){
            $.ajax({
                url: "{pigcms{:U('Deliver/ajax_save_city_id_for_deliver_user')}",
                type: 'POST',
                dataType: 'json',
                data:{"city_id":city_id},
                success:function(data){
                    if (data.error==1){
                        $("#online_div").hide();
                        open_input();
                    }else{
                        $("#online_div").show();
                        close_input();
                    }

                    //window.parent.location = "{pigcms{:U('Deliver/step_3')}";
                }
            });
        }else{
            $("#online_div").hide();
            close_input();
        }
    }

    $("#reg_form").click(function () {
        console.log("reg_form");
        $(this).attr("disabled","disabled");
        if(check_form()){
            var form_data = {
                'address':$('#address').val(),
                'lng':$('#lng').val(),
                'lat':$('#lat').val(),
                'city_id':$('#city_id').val(),
                'birthday':$('#birthday').val(),
                'vehicle_type':$('#vehicle_type').val(),
                'apartment':$('#apartment').val(),
                'city':$('#city').val(),
                'province':$('#province').val(),
                'postal_code':$('#postalcode').val()
                //'sin_num':$('#sin_num').val(),
                //'ahname':$('#ahname').val(),
                //'transit':$('#transit').val(),
                //'institution':$('#institution').val(),
                //'account':$('#account').val()
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
        if($('#vehicle_type').val() == 0){
            is_check = false;
            show_tip("Please complete all required fields",$(this));
        }
        $("#reg_list").find('input').each(function () {
            if($(this).attr("name") != "apartment" && $(this).val() == ''){
                show_tip("Please complete all required fields",$(this));
                is_check = false;
                return false;
            }
        });
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
        if(typeof(autocomplete) == 'undefined') {
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), {
                types: ['geocode'],
                componentRestrictions: {country: ['ca']}
            });
            autocomplete.addListener('place_changed', fillInAddress);
        }
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