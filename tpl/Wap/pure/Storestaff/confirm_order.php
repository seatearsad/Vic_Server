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
</style>
<body>
<include file="header" />
<form id="add-shop-order" enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/save_shop_oder')}">
<div id="main">
    <div id="edit_btn">Edit</div>
    <div class="input_title" style="margin-top: 30px">Please confirm the following information:</div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">Customer Name*:</label>{pigcms{$post_data['name']}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">Customer Contact Number*:</label>{pigcms{$post_data['phone']}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">Select Delivery Address*:</label>{pigcms{$post_data['address']}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">Delivery Instructions:</label>{pigcms{$post_data['detail']}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">Subtotal (Before Tax)*:</label>${pigcms{$post_data['goods_price']}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">Tax Amount ($)*:</label>${pigcms{$post_data['goods_tax']}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            <label style="color: #999999;">Bottle Deposit (If applicable):</label>${pigcms{$post_data['deposit']}
        </div>
    </div>
    <div style="margin-top: 20px">
        Please note that <label class="bold_black">Tutti couriers do NOT carry any POS machine for delivery</label>,
        and <label class="bold_black">only accept cash or online payments.</label>
        If the customer chooses to pay online, he or she will input her payment information on courier's phone using Tutti Courier App.
        <label class="bold_black">Please confirm that you have informed the customer the above information before placing the order.</label>
        Tutti does not hold responsibilities if the customer refuses to pay due to payment method unawareness.
    </div>
    <div style="margin-top: 20px">
        <input type="checkbox" class="mt" value="1" name="is_read" style="border-radius: 0;width: .40rem;height: .40rem;line-height: .40rem;">
        <label style="line-height: 20px">
            I have read the above information and confirm that I've informed the customer the above payment methods.
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
        Place & Accept the Order
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
            alert('Please check ????！');
        }
    });
</script>

