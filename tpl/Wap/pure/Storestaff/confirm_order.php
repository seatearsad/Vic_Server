<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Confirm Order</title>
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
    input.mt[type="radio"]:checked, input.mt[type="checkbox"]:checked {
        background-color: #ffa52d;
    }
    .show_order_memo{
        font-size: 16px;
    }
</style>
<body>
<include file="header" />
<form id="add-shop-order" enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/save_shop_oder')}">
<div id="main">
    <div id="edit_btn">{pigcms{:L('QW_EDIT')}</div>
    <div class="input_title" style="margin-top: 30px">{pigcms{:L('QW_CONFIRMINFO')}:</div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">{pigcms{:L('QW_CUSTOMERNAME')}*: </label><label class="show_order_memo"> {pigcms{$post_data['name']}</label>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">{pigcms{:L('QW_CUSTOMERNUMBER')}*: </label><label class="show_order_memo"> {pigcms{$post_data['phone']}</label>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">{pigcms{:L('QW_ADDRESS')}*: </label><label class="show_order_memo"> {pigcms{$post_data['address']}</label>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">{pigcms{:L('QW_DELIVERYINSTRUCTION')}: </label><label class="show_order_memo"> {pigcms{$post_data['detail']}</label>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">{pigcms{:L('QW_AOSUBTOTAL')}*: </label><label class="show_order_memo"> ${pigcms{$post_data['goods_price']}</label>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">{pigcms{:L('QW_AOTAX')}*: </label><label class="show_order_memo"> ${pigcms{$post_data['goods_tax']}</label>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">{pigcms{:L('QW_AOBOTTLEDEPOSIT')}: </label><label class="show_order_memo"> ${pigcms{$post_data['deposit']}</label>
        </div>
    </div>
    <div class="order_input" style="margin-top: 20px">
        <div class="input_title">Food Preparation Time*</div>
        <select class="confirm_time" name="dining_time" autocomplete="off" style="margin-top:5px;height: 30px;width: 200px">
            <option value="10">10 min</option>
            <option value="20" selected="selected">20 min</option>
            <option value="30">30 min</option>
            <option value="40">40 min</option>
            <option value="50">50 min</option>
            <option value="60">60 min</option>
            <option value="70">70 min</option>
            <option value="80">80 min</option>
            <option value="90">90 min</option>
            <option value="100">100 min</option>
        </select>
    </div>
    <div style="margin-top: 20px;font-size: 14px;color: #666666;font-weight: bold;">
        {pigcms{:L('QW_ADDORDERB')}
    </div>
    <div style="margin-top: 20px">
        <input type="checkbox" class="mt" value="1" name="is_read" style="border-radius: 0;width: .40rem;height: .40rem;line-height: .40rem;">
        <label style="line-height: 20px">
            {pigcms{:L('QW_ADDORDERC')}
        </label>
    </div>
    <input type="hidden" name="staff_id" value="{pigcms{$return_data['staff_id']}">
    <input type="hidden" name="store_id" value="{pigcms{$return_data['store_id']}">
    <input type="hidden" name="mer_id" value="{pigcms{$return_data['mer_id']}">
    <input type="hidden" name="price" value="{pigcms{$return_data['price']}">
    <input type="hidden" name="goods_price" value="{pigcms{$return_data['goods_price']}">
    <input type="hidden" name="total_price" value="{pigcms{$return_data['total_price']}">
    <input type="hidden" name="freight_charge" value="{pigcms{$return_data['freight_charge']}">
    <input type="hidden" name="desc" value="{pigcms{$return_data['desc']}">
    <input type="hidden" name="username" value="{pigcms{$post_data['name']}">
    <input type="hidden" name="userphone" value="{pigcms{$post_data['phone']}">
    <input type="hidden" name="real_orderid" value="{pigcms{$return_data['real_orderid']}">
    <input type="hidden" name="discount_price" value="{pigcms{$return_data['goods_price_tax']}">
    <input type="hidden" name="packing_charge" value="{pigcms{$return_data['deposit']}">
    <input type="hidden" name="adress" value="{pigcms{$post_data['address']}" />
    <input type="hidden" name="address" value="{pigcms{$post_data['address']} {pigcms{$post_data['detail']}" />
    <input type="hidden" name="detail" value="{pigcms{$post_data['detail']}" />
    <input type="hidden" name="longitude" value="{pigcms{$post_data['longitude']}" />
    <input type="hidden" name="latitude" value="{pigcms{$post_data['latitude']}" />
    <input type="hidden" name="province" value="{pigcms{$post_data['province']}" />
    <input type="hidden" name="city" value="{pigcms{$post_data['city_id']}" />
    <input type="hidden" name="area" value="{pigcms{$post_data['area']}" />
    <div class="confirm_btn_order" id="confirm_order">
        {pigcms{:L('QW_PLACENCONFIRM')}
    </div>
</div>
</form>

<script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<script>
    var is_read = $('input[name="is_read"]').prop('checked');
    $('#edit_btn').click(function () {
        history.go(-1);
    });

    $('input[name="is_read"]').change(function () {
       is_read = $('input[name="is_read"]').prop('checked');
    });

    $('#confirm_order').click(function () {
        if(is_read){
            $("#add-shop-order").submit();
        }else{
            alert('Check required!');
        }
    });
</script>

