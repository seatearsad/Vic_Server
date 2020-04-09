<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Add Order</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<style>
    #main{
        padding: 20px 5%;
        color: #999999;
        line-height: 16px;
        display: inline-block;
        font-size: 12px;
    }
</style>
<body>
<include file="header" />
<form id="add-shop-order" enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/confirm_order')}">
<div id="main">
    <div>
        Many customers still prefer to call-in and requesting a delivery. You can place an delivery order here and input delivery information. Our driver can deliver the order to the customer for you. Commission and delivery fee will be applied as normal Tutti order.
    </div>
    <div class="order_input">
        <div class="input_title">
            Customer Name*
        </div>
        <input type="text" name="name" value="" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Customer Contact Number*
        </div>
        <input type="text" name="phone" value="" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Select Delivery Address*
        </div>
        <input type="text" name="address" id="address" value="" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Delivery Instructions
        </div>
        <input type="text" name="detail" value="" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Subtotal (Before Tax)*
        </div>
        <input type="text" name="goods_price" value="" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Tax Amount ($)*
        </div>
        <input type="text" name="goods_tax" value="" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Bottle Deposit (If applicable)
        </div>
        <input type="text" name="deposit" value="" />
    </div>

    <div style="margin-top: 25px;line-height: 20px;">
        Please note that <label class="bold_black">Tutti couriers do NOT carry any POS machine for delivery</label>,
        and <label class="bold_black">only accept cash or online payments.</label>
        If the customer chooses to pay online, he or she will input her payment information on courier's phone using Tutti Courier App.
        <label class="bold_black">Please confirm that you have informed the customer the above information before placing the order.</label>
        Tutti does not hold responsibilities if the customer refuses to pay due to payment method unawareness.
    </div>
    <input type="hidden" name="longitude" />
    <input type="hidden" name="latitude" />
    <input type="hidden" name="province" value="104" />
    <input type="hidden" name="city_id" />
    <input type="hidden" name="area" value="0">
    <div class="confirm_btn_order" id="confirm_order">
        Continue
    </div>
</div>
</form>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
<script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<script>
    $(function(){
        $("#confirm_order").click(function() {
            if (!$('input[name="name"]').val()){
                alert("Customer's name is empty");return;
            }
            if (!$('input[name="phone"]').val()){
                alert('Mobile number is empty');return;
            }
            if (!$('input[name="address"]').val()||!$('input[name="longitude"]').val()||!$('input[name="latitude"]').val()){
                alert("Customer's location is empty");return;
            }
            // if (!$('input[name="detail"]').val()){
            //     alert("Note for deliver is empty,ex.unit number or front door...");return;
            // }
            if (!$('input[name="goods_price"]').val()){
                alert('SubTotal price is empty');return;
            }
            if (!$('input[name="goods_tax"]').val()){
                alert('Tax is empty');return;
            }
            $("#add-shop-order").submit();
        });
    });
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
        $('input[name="longitude"]').val(place.geometry.location.lng());
        $('input[name="latitude"]').val(place.geometry.location.lat());

        var add_com = place.address_components;
        var is_get_city = false;
        for(var i=0;i<add_com.length;i++){
            if(add_com[i]['types'][0] == 'locality'){
                is_get_city = true;
                var city_name = add_com[i]['long_name'];
                $.post("{pigcms{:U('Storestaff/ajax_city_name')}",{city_name:city_name},function(result){
                    if (result.error == 1){
                        $("input[name='city_id']").val(0);
                    }else{
                        $('input[name="city_id"]').val(result['info']['city_id']);
                    }
                },'JSON');
            }
        }
    }
</script>

