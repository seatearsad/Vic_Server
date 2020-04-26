<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Order</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<body>
    <include file="header" />
    <div id="main">
        <div class="order_detail">
            <div id="detail_div">

            </div>
            <div class="con_layer">
                <span class="confirm_txt">
                    Food Preparation
                </span>
                <select class="confirm_time" name="dining_time" autocomplete="off">
                    <option value="5">5 min</option>
                    <option value="10">10 min</option>
                    <option value="20" selected="selected">20 min</option>
                    <option value="30">30 min</option>
                    <option value="40">40 min</option>
                </select>
                <span class="confirm_btn w_color">
                    Confirm <label id="item_all_num">7</label> Item(s)
                </span>
            </div>
        </div>
    </div>
    <script>
        var new_img = "{pigcms{$static_path}images/new_order.png";
        var new_url = "{pigcms{:U('Storestaff/getNewOrder')}";
        var link_url = "javascript:layer.closeAll();"//"{pigcms{:U('Storestaff/shop_list')}";
        var sound_url = "{pigcms{$static_public}sound/soft-bells.mp3";
        var detail_url = "{pigcms{:U('Storestaff/getOrderDetail')}";
    </script>
    <script type="text/javascript" src="{pigcms{$static_path}js/new_order.js?v=2.3"></script>
    <script>
        var order_id = "{pigcms{$_GET['order_id']}";

        getOrderDetail(order_id);

        var header_height = 60;
        var all_height = $(window).height();

        $(function () {
            $(".order_detail").height(all_height - header_height);
            $('#detail_div').height(all_height - header_height - 50);
        });

        function printOrderToAndroid(time_val){
            if(typeof (time_val) == "undefined" || !/^[0-9]*$/.test(time_val)){
                time_val = "0";
            }

            if(typeof (window.linkJs) != 'undefined'){
                if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()))
                    window.linkJs.printer_order(JSON.stringify(this_order),JSON.stringify(this_order.info),time_val);
            }

            if(/(tuttipartner)/.test(navigator.userAgent.toLowerCase())) {
                var orderDetail = this_order.real_orderid;
                orderDetail  += "|" + this_order.store_name;
                orderDetail  += "|" + this_order.store_phone;
                orderDetail  += "|" + this_order.pay_time_str;
                orderDetail  += "|" + this_order.desc;
                orderDetail  += "|" + this_order.expect_use_time;
                orderDetail  += "|" + this_order.username;
                orderDetail  += "|" + this_order.userphone;
                orderDetail  += "|$" + this_order.goods_price;

                if(time_val == "0")
                    time_val = this_order.dining_time;

                var orderInfo = order_info ;

                window.webkit.messageHandlers.printer_order.postMessage([orderDetail, orderInfo, time_val]);
            }
        }
        //是否为App
        var is_app = false;
        if(typeof (window.linkJs) != 'undefined') {
            var is_use = window.linkJs.getUsePrinter();
            if(is_use == 1){
                if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()) || /(tuttipartner)/.test(navigator.userAgent.toLowerCase())){
                    is_app = true;
                }
            }
        }else{
            if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()) || /(tuttipartner)/.test(navigator.userAgent.toLowerCase())){
                is_app = true;
            }
        }
    </script>
    <script id="OrderDetailTpl" type="text/html">
        <div class="detail_title">
            Order #{{ d.order_id }}
            (
            {{# if(d.status == 0){ }}
            <span class="t_color">{pigcms{:L('QW_PLEASECONFIRM')}</span>
            {{# }else if(d.order_status == 1){ }}
            <span class="t_color">{pigcms{:L('QW_WAITING')}</span>
            {{# }else if(d.order_status == 2){ }}
            <span class="t_color">{pigcms{:L('QW_Accepted')}</span>
            {{# }else if(d.order_status == 3){ }}
            <span class="t_color">{pigcms{:L('QW_PICKED')}</span>
            {{# }else if(d.order_status == 4){ }}
            <span class="t_color">{pigcms{:L('QW_ARRIVING')}</span>
            {{# }else if(d.order_status == 5){ }}
            <span class="t_color">{pigcms{:L('QW_COMPLETED')}</span>
            {{# } }}
            )
            {{# if(d.status > 0 && d.status < 4 && d.is_app){ }}
            <span class="order_print" id="print_order"></span>
            {{# } }}
        </div>
        <div class="detail_user">
            Placed by {{ d.username }} ({{ d.userphone }}) at {{ d.date }}
        </div>
        <div class="detail_note">
            {pigcms{:L('QW_NOTE')}:
            <span class="t_color">
            {{ d.desc }}
        </span>
        </div>
        {{# if(d.info != null){ }}
        {{# for(var i = 0, len = d.info.length; i < len; i++){ }}
        {{# if(i > 0){ }}
        <div class="g_line"></div>
        {{# } }}
        <div class="order_item">
            {{# if(d.info[i].num > 1){ }}
            <span class="item_num num_more">x{{ d.info[i].num }}</span>
            {{# }else{ }}
            <span class="item_num num_one">x{{ d.info[i].num }}</span>
            {{# } }}
            <span class="item_name">
            {{ d.info[i].name }}
            </span>
            <span class="item_price">${{ d.info[i].price }}</span>
        </div>
        {{# for(var j = 0, lens = d.info[i].spec_arr.length; j < lens; j++){ }}
        <div class="order_item dish_line">
            <span class="item_num"></span>
            <span class="item_name">
            - {{ d.info[i].spec_arr[j] }}
            </span>
            <span class="item_price"></span>
        </div>
        {{# } }}
        {{# for(var j = 0, lena = d.info[i].pro_arr.length; j < lena; j++){ }}
        <div class="order_item dish_line">
            <span class="item_num"></span>
            <span class="item_name">
            - {{ d.info[i].pro_arr[j] }}
            </span>
            <span class="item_price"></span>
        </div>
        {{# } }}
        {{# if(d.info[i].dish){ }}
        {{# for(var j in d.info[i].dish){ }}
        <div class="order_item dish_line">
            <span class="item_num"></span>
            <span class="item_name">
            - {{ d.info[i].dish[j].name }}
            </span>
            <span class="item_price"></span>
        </div>
        {{# for(var k in d.info[i].dish[j].list){ }}
        <div class="order_item dish_line">
            <span class="item_num"></span>
            <span class="item_name">
                    {{# var name=d.info[i].dish[j].list[k].split("*") }}
                 &nbsp;&nbsp;&nbsp;&nbsp;{{ name[0] }}
                    {{# if(typeof(name[1]) != 'undefined'){ }}
                    <label class="r_color">*{{ name[1] }}</label>
                    {{# } }}
                </span>
            <span class="item_price"></span>
        </div>
        {{# } }}
        {{# } }}
        {{# } }}
        {{# } }}
        <div class="b_line"></div>
        {{# } }}

        <div class="order_total">
            <div>{pigcms{:L('QW_SUBTOTAL')}: ${{ d.goods_price }}</div>
            <div>{pigcms{:L('QW_TAX')}: ${{ d.tax_price }}</div>
            <div>{pigcms{:L('QW_COMMISSION')}: -${{ d.tutti_comm }}</div>
            {{# if(d.merchant_reduce > 0){ }}
            <div>{pigcms{:L('QW_MERCHANTDISCOUNT')}: -${{ d.merchant_reduce }}</div>
            {{# } }}
            {{# if(d.packing_charge > 0){ }}
            <div>{pigcms{:L('QW_PACKAGEFEE')}: ${{ d.packing_charge }}</div>
            {{# } }}
            {{# if(d.deposit_price > 0){ }}
            <div>{pigcms{:L('QW_BOTTLEDEPOSIT')}: ${{ d.deposit_price }}</div>
            {{# } }}
            <div>{pigcms{:L('QW_MERCHANTREFUND')}: ${{ d.merchant_refund }}</div>
        </div>
    </script>
</body>
</html>