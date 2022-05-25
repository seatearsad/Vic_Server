<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{pigcms{:L('_STORE_CENTER_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css?v=1.2" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<body>
    <include file="header" />
    <div id="main">
        <div class="order_list">
            <div class="list_top">
                {pigcms{:L('QW_HIDELIST')}
            </div>
            <ul class="list_ul"></ul>
        </div>
        <div class="order_detail">
            <div class="show_list"></div>
            <div id="detail_div"></div>
            <div id="tip_layer">{pigcms{:replace_lang_str(L('D_F_TIP_2'),$store['min_time'])}</div>
            <div class="con_layer">
                <span class="confirm_txt">
                    Food Preparation
                </span>
                <select class="confirm_time" name="dining_time" autocomplete="off">
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
                <span class="confirm_btn w_color">
                    {pigcms{:replace_lang_str(L('QW_CONFIRMBUTTON'),'<label id="item_all_num">0</label>')}
                </span>
            </div>
            <div class="con_layer_confirm">
                <span class="confirm_txt">
                    Order should be ready
                </span>
                <span class="cha_time"></span>
                <div id="add_dining_time" style="position: absolute;top:32px;left: 20px">
                    <select class="confirm_time" name="add_dining_time" autocomplete="off" style="width:160px;height: 30px">
                        <option value="10">Another 10 min</option>
                        <option value="20" selected="selected">Another 20 min</option>
                        <option value="30">Another 30 min</option>
                        <option value="40">Another 40 min</option>
                        <option value="50">Another 50 min</option>
                        <option value="60">Another 60 min</option>
                        <option value="70">Another 70 min</option>
                        <option value="80">Another 80 min</option>
                        <option value="90">Another 90 min</option>
                        <option value="100">Another 100 min</option>
                    </select>
                    <span class="add_time_btn">Add</span>
                </div>
                <span class="confirm_btn w_color">
                    <label id="item_all_con_num">7</label> Item(s)
                    Confirmed
                </span>
            </div>
        </div>
    </div>
    <script>
        var new_img = "{pigcms{$static_path}images/new_order.png";
        var new_url = "{pigcms{:U('Storestaff/getNewOrder')}";
        var link_url = "javascript:hasNewOrder();"//"{pigcms{:U('Storestaff/shop_list')}";
        var sound_url = "{pigcms{$static_public}sound/soft-bells.mp3";
        var detail_url = "{pigcms{:U('Storestaff/getOrderDetail')}";
    </script>
    <script type="text/javascript" src="{pigcms{$static_path}js/new_order.js?v=2.1.0"></script>
    <script>
        //更新app 设备token
        function pushDeviceToken(token) {
            var message = '';
            if(token != "{pigcms{$staff_session['device_id']}") {
                $.post("{pigcms{:U('Storestaff/update_device')}", {'token': token}, function (result) {
                    if (result) {
                        message = result.message;
                    } else {
                        message = 'Error';
                    }
                });
            }
            return message;
        }
        //更新Android 设备token
        if(typeof (window.linkJs) != 'undefined'){
            var android_token = window.linkJs.getDeviceId();
            if(android_token != "{pigcms{$staff_session['device_id']}"){
                var message = '';
                $.post("{pigcms{:U('Storestaff/update_device')}", {'token':android_token}, function(result) {
                    if(result){
                        message = result.message;
                    }else {
                        message = 'Error';
                    }
                });
            }
        }

        var header_height = 60;
        var all_height = $(window).height();
        var all_width = $(window).width();
        var is_detail_hide = false;
        var tip_layer_show = false;
        var con_layer_confirm_show = false;

        $(function () {
            $(".order_list").height(all_height - header_height);
            $(".order_detail").height(all_height - header_height);
            $('#detail_div').height(all_height - header_height - 100);
            $('.list_ul').height(all_height - header_height - 50);
            if(all_width > all_height){
                $('.show_list').hide();
            }else{
                $('.order_list').children().hide();
                $('.order_list').width('0px');
                is_detail_hide = true;
            }
        });

        function hasNewOrder(){
            layer.closeAll();
            $('.show_list').trigger('click');
        }
        $('.list_top').click(function () {
            isHide();
            $('.order_list').children().hide();
            $('.order_list').width('0px');
            $('.show_list').show();
            if(is_detail_hide) {
                $('.order_detail').show();
                $('.order_detail').children().show();
                if(!tip_layer_show) $("#tip_layer").hide();
                if(!con_layer_confirm_show) $('.con_layer_confirm').hide();
                if(click_id == 0){
                    $('.con_layer').hide();
                    $('.con_layer_confirm').hide();
                }
            }
        });
        $('.show_list').click(function () {
            isHide();
            $('.order_list').width('350px');
            setTimeout(function (){
                $('.order_list').children().show()
            },200);
            //记录detail中的元素是否显示
            if ($("#tip_layer").is(':hidden')) {
                tip_layer_show = false;
            }else{
                tip_layer_show = true;
            }
            if ($('.con_layer_confirm').is(':hidden')) {
                con_layer_confirm_show = false;
            }else{
                con_layer_confirm_show = true;
            }

            $('.show_list').hide();
            if(is_detail_hide){
                $('.order_detail').hide();
                $('.order_detail').children().hide();
            }
        });
        function isHide(){
            var all_height = $(window).height();
            var all_width = $(window).width();
            if(all_width > all_height){
                is_detail_hide = false;
            }else{
                is_detail_hide = true;
            }
        }

        var is_send = false;
        $('.confirm_btn').click(function () {
            if(is_send) return false;
            if(this_order.order_status == 3){
                layer.open({
                    title: '',
                    content: 'Please confirm the customer has picked up this order',
                    btn: ['Confirm', 'Cancel'],
                    shadeClose: false,
                    yes: function() {
                        layer.closeAll();
                        updateStatus();
                    }
                });
            }else{
                updateStatus();
            }

            return false;
        });

        function updateStatus() {
            layer.open({
                type: 2,
                content: 'Loading...'
            });
            var time_val = $('select[name="dining_time"] option:selected').val();
            is_send = true;

            $.post("{pigcms{:U('Storestaff/shop_order_confirm')}",{order_id:click_id,status:1,dining_time:time_val},function(result){
                is_send = false;
                if(result.status == 1){
                    if(this_order.status == 0) printOrderToAndroid(time_val);
                    setTimeout(function () {
                        layer.closeAll();
                        getNewOrder();
                        getOrderDetail(click_id);
                    },1000);
                }else{
                    layer.open({
                        content:result.info,
                        btn: ['OK'],
                        end:function(){
                            window.location.reload();
                        }
                    });
                }
            },'json');
        }

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
        function moni_click() {
            $('.show_list').trigger('click');
        }
        $('.add_time_btn').click(function () {
            var add_time = $('select[name="add_dining_time"] option:selected').val();
            layer.open({
                type:2,
                content:'Loading...'
            });
            $.post("{pigcms{:U('Storestaff/add_dining_time')}",{order_id:click_id,dining_time:add_time},function(result){
                if(result.error == 0){
                    setTimeout(function () {
                        layer.closeAll();
                        getNewOrder();
                        getOrderDetail(click_id);
                    },1000);
                }else{
                    alert(result.info);
                    window.location.reload();
                }
            },'json');
        });
    </script>
    <script id="NotOrderShow" type="text/html">
        <div id="staff_show_div">
            <div class="logo">
                <img src="{pigcms{$static_path}img/staff_menu/tutti_branding.png" width="100" />
            </div>
            <div>
                {pigcms{:L('QW_NONEWORDERA')}
            </div>
            <div style="margin-top: 20px;font-weight: bold;font-size: 16px;color: #999999">
                {pigcms{:L('QW_NONEWORDERB')}
            </div>
            {{# if(d.con_len > 0){ }}
            <div style="color: #999999">
                You have {{ d.con_len }} order(s) in progress now.
            </div>
            {{# } }}
        </div>
    </script>
    <script id="HasOrderShow" type="text/html">
        <a href="javascript:moni_click()" style="color: #666666">
        <div id="staff_show_div">
            <div class="logo">
                <img src="{pigcms{$static_path}img/staff_menu/new_order.png" width="100" />
            </div>
            <div>
                {pigcms{:L('QW_NONEWORDERA')}
            </div>
            <div style="margin-top: 20px;font-weight: bold;font-size: 16px;color:white;background-color: #ffa52d;line-height: 35px;border-radius: 3px;">
                You have {{ d.len }} new order(s) now!
            </div>
            {{# if(d.con_len > 0){ }}
            <div style="margin-top: 10px;color: #ffa52d">
                {{ d.con_len }} order(s) are in process.
            </div>
            {{# } }}
        </div>
        </a>
    </script>
    <script id="OrderListTpl" type="text/html">
        {{# for(var i = 0, len = d.length; i < len; i++){ }}
        <li data-id="{{ d[i].order_id }}">
            <span>#{{ d[i].order_id }}</span>
            <span>{{ d[i].username }}</span>
            {{# if(d[i].status == 1){ }}
                {{# if(d[i].order_type == 0){ }}
                <span class="confirm_order">{pigcms{:L('QW_CONFIRMED')}</span>
                {{# }else{ }}
                <span class="confirm_order" style="color: #294068">
                    {{# if(d[i].order_status == 1){ }}
                        Preparing
                    {{# }else{ }}
                        Ready
                    {{# } }}
                </span>
                {{# } }}
            {{# }else{ }}
                {{# if(d[i].order_type == 0){ }}
                    <span class="new_order">{pigcms{:L('QW_NEW')}</span>
                {{# }else{ }}
                    <span class="new_order" style="background-color: #294068;">{pigcms{:L('QW_NEW')}</span>
                {{# } }}
            {{# } }}
        </li>
        {{# } }}
    </script>
    <script id="OrderDetailTpl" type="text/html">
        <div class="detail_title">
            {{# if(d.order_type == 1){ }}
                <span style="background-color: #294068;color: white;padding: 5px 10px;border-radius: 5px;">{pigcms{:L('_SELF_LIFT_')}</span>
            {{# } }}
            Order #{{ d.order_id }}
            (
            {{# if(d.order_type == 0){ }}
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
            {{# }else{ }}
                {{# if(d.status == 0){ }}
                    <span style="color: #294068">{pigcms{:L('QW_PLEASECONFIRM')}</span>
                {{# }else if(d.order_status == 1){ }}
                    <span style="color: #294068">Preparing</span>
                {{# }else if(d.order_status == 5){ }}
                    <span style="color: #294068">{pigcms{:L('QW_COMPLETED')}</span>
                {{# }else{ }}
                    <span style="color: #294068">Ready for Pickup</span>
                {{# } }}
            {{# } }}
            )
            {{# if(d.status > 0 && d.status < 4 && d.is_app){ }}
            <span class="order_print" id="print_order"></span>
            {{# } }}
        </div>
        <div class="detail_user">
            Placed by {{ d.username }} ({{ d.userphone }}) at {{ d.date }}
        </div>
        {{# if(d.order_type == 0){ }}
            <div class="detail_note">
                {pigcms{:L('QW_NOTE')}:
                <span class="t_color">
                    {{ d.desc }}
                </span>
            </div>
        {{# }else{ }}
            <div class="detail_note" style="border-color: #294068;">
                {pigcms{:L('QW_NOTE')}:
                <span class="t_color">
                        {{ d.desc }}
                    </span>
            </div>
        {{# } }}
        {{# if(d.link_type == 0){ }}
        {{# if(d.info != null){ }}
        {{# for(var i = 0, len = d.info.length; i < len; i++){ }}
        {{# if(i > 0){ }}
        <div class="g_line"></div>
        {{# } }}
        <div class="order_item">
            {{# if(d.info[i].num > 1){ }}
                {{# if(d.order_type == 1){ }}
                    <span class="item_num num_more" style="border: 2px solid #294068;background: #294068;">x{{ d.info[i].num }}</span>
                {{# }else{ }}
                    <span class="item_num num_more">x{{ d.info[i].num }}</span>
                {{# } }}
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
        {{# }else{ }}
            <div style="margin-top: 20px;">Please check order details with your integration partner.</div>
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