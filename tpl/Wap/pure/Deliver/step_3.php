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
        top: 2%;
        width: 100%;
        font-size: 10px;
        color: #666666;
    }

    #step_now div{
        font-size: 10px;
        text-align: left;
        padding-left: 50%;
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
    .memo{
        width:80%;
        margin: 5px auto 5px auto;
        text-align: left;
    }
    .memo-sm{
        width:75%;
        margin: 5px auto 5px auto;
        text-align: left;
    }
    .step_title{
        width:80%;
        margin: 20px auto 5px auto;
        font-size: 14px;
        color: #333333;
    }
    #step_now{
        width:80%;
        margin: 20px auto;
        font-size: 0;
    }
    li{
        text-align: center;
        margin-top: 15px;
    }
    li input {
        width: 80%;
        height: 15px;
        padding: 8px 0;
        text-indent: 10px;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 12px;
    }
    li input.sm {
        width: 39%;
        height: 15px;
        padding: 8px 0;
        text-indent: 10px;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 12px;
    }
    li.Landd input {
        background: #ffa52d;
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
    .btn_circle{
        display: inline-block;
        background-color:#ffd117;
        width:20px;
        height:20px;
        padding: 4px;
        text-align:center;
        border-radius: 14px;
        margin: 5px;
        font-size: 24px;
        line-height: 16px;
        color: white;
        font-weight: bold;
        cursor: pointer;
        -moz-user-select: none;-khtml-user-select: none;user-select: none;
    }
    .btn_number{
        display: inline-block;
        font-size: 16px;
        color: black;
    }
    .price_div{
        line-height: 20px;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px">{pigcms{:L('_ND_BECOMEACOURIER_')}</div>
    </div>
    <div class="step_title">Delivery Bag</div>
    <div class="memo">
        Last step! You’re required to carry a Tutti delivery bag to pick up and drop off orders. Please select at least one of the following:
    </div>

    <if condition="is_array($bag)">
        <div class="memo">
            <volist name="bag" id="vo">
                <table style="width: 100%;text-align: center;padding: 8px">
                    <tr>
                        <td style="background-color: #c0c0c0;;width: 120px;height: 120px;">
                            <img src="{pigcms{$static_public}images/deliver_box.png" style="width: 100px;height:100px"></td>
                        <td style="position:relative;background-color: white;vertical-align: top;padding: 10px;text-align: left">
                            <div style="font-size: 16px;font-weight: bold">{pigcms{$vo.bag_name}</div>
                            <div style="font-size: 13px;font-weight: bold;margin-top: 4px;">${pigcms{$vo.bag_price}</div>
                            <div style="font-size: 12px;;margin-top: 4px;">{pigcms{$vo.bag_description}</div>
                            <div style="position: absolute;bottom: 5px;right: 5px">
                                <input type="hidden" id="photos_{pigcms{$vo.bag_id}" value="photos_{pigcms{$vo.bag_id}"/>
                                <div class="btn_circle btn_minus" data-bagid="{pigcms{$vo.bag_id}" data-num = "-1">-</div>
                                <div class="btn_number bagid_{pigcms{$vo.bag_id}" data-bagid="{pigcms{$vo.bag_id}" data-bagprice="{pigcms{$vo.bag_price}" data-bagtaxrate="{pigcms{$vo['bag_tax_rate']/100}">
                                    <volist name="bag_list" id="bag">
                                        <if condition="$bag[0] eq $vo['bag_id']">
                                            {pigcms{$bag[1]}
                                        </if>
                                    </volist>
                                </div>
                                <div class="btn_circle btn_plus" data-bagid="{pigcms{$vo.bag_id}" data-num = "1">+</div>
                            </div>
                        </td>
                    </tr>
                </table>
            </volist>
        </div>
    </if>
    </DIV>

    <div class="memo-sm" style="text-align: right;text-align: center;font-size: 12px;height: 20px;margin-top: 20px;">
        <if condition="$city['bag_type'] eq 1">
            <input type="hidden"  name="bag_type" value="1">
            <else />
            <if condition="$city['bag_type'] eq 2">
                <input type="hidden"  name="bag_type" value="2">
                <else />
                <div style="float: left">
                    <input type="radio" id="radiotype1" name="bag_type" value="1" style="vertical-align: top"> Shipping(${pigcms{$city.bag_shipping_fee})
                </div>
                <div style="float: right">
                    <input type="radio" id="radiotype2" name="bag_type" value="2" style="vertical-align: top"> Pick up
                </div>
            </if>
        </if>
    </div>
    <div class="memo-sm" style="text-align: right;font-size: 12px;font-weight: bold">
        <div class="price_div">Subtotal : $<span class="subtotal_box">0</span></div>
        <div class="price_div">Shipping : $<span class="shipping_fee_box">0</span></div>
    </div>


    <div id="pickup_div" style="display: none">
        <div class="step_title">You’ll pick up your bag in {pigcms{$city.area_name}!</div>
        <div class="memo">
            You will receive an email with the exact address and a link to book a pick-up time slot.
        </div>
    </div>
    <div id="shipping_div" style="display: none">
        <div class="step_title">We’ll ship the bag to you!</div>
        <div class="memo">
            Enter your shipping information below:
        </div>
        <div id="reg_list">
            <ul>
                <li>
                    <input type="text" class="" placeholder="{pigcms{:L('_ND_ADDRESS_')}" id="address" name="address" value="{pigcms{$user.ship_adress}">
                </li>
                <li>
                    <input type="text" class="" placeholder="Apartment,suite,unit,etc." id="apartment" name="apartment" value="{pigcms{$user.ship_apartment}">
                </li>
                <li>
                    <input type="text" class="sm " placeholder="City*" id="city"  name="city" value="{pigcms{$user.ship_city_str}">
                    <input type="text" class="sm" placeholder="Province*" id="province"  name="province" value="{pigcms{$user.ship_province_str}">
                </li>
                <li>
                    <input type="text" class="" placeholder="Postal Code*" id="postalcode" name="postalcode" value="{pigcms{$user.ship_postal_code}">
                </li>
            </ul>
        </div>
    </div>
    <div class="step_title">Payment Information</div>
    <div id="reg_list">
        <ul>
            <li>
                <input type="text" placeholder="{pigcms{:L('_CREDITHOLDER_NAME_')}" id="c_name">
            </li>
            <li>

                <input type="text" placeholder="{pigcms{:L('_CREDIT_CARD_NUM_')}" id="c_number">
            </li>
            <li>

                <input type="text" placeholder="{pigcms{:L('_EXPRIRY_DATE_')}" id="e_date">
            </li>
            <li>
                <input type="text" placeholder="3-digit number" id="cvv">
            </li>
            <div class="memo-sm" style="text-align: right;font-size: 12px;font-weight: bold">
                <div class="price_div">Subtotal : $<span class="subtotal_box">0</span></div>
                <div class="price_div">Shipping : $<span class="shipping_fee_box">0</span></div>
                <div class="price_div">Tax : $<span class="tax_box">0</span></div>
                <div class="price_div">Total : $<span class="total_box">0</span></div>
            </div>
            <li class="Landd">
                <input type="button" value="Pay Online" id="reg_form" style="background-color: #ffa52d;width: 80%;color: white;">
            </li>
            <li>
                <div style="border-bottom: 1px dashed silver;width: 55%;margin: auto">
                    OR
                </div>
            </li>
            <li class="Landd">
                <input type="button" value="Save & Pay Later" id="jump_btn" style="background-color: darkgrey;color:white;font-size:10px;width: 80%;margin-top: 10px;margin-bottom: 30px">
            </li>
        </ul>
    </div>

</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
<script type="text/javascript">
    <!--    //bag_type 0:未设置 1:自取 2：邮寄 3：全选-->
    <if condition="$city['bag_type'] eq 1">
        var init_bag_select=1;
    <else />
        <if condition="$city['bag_type'] eq 2">
            var init_bag_select=2;
        <else />
            var init_bag_select=2;
        </if>
    </if>

    var select_buy_mode = 0;
    var shipping_fee = parseFloat("{pigcms{$city.bag_shipping_fee}");
    var show_shipping_fee = 0;

    var bag_get_id = "";

    set_bag_id_number();

    function set_bag_id_number(){
        var sub_total = 0;
        var show_shipping_fee = 0;
        var total = 0;
        var tax_price = 0;

        bag_get_id = "";
        $('body').find('.btn_number').each(function () {
            if($(this).html().replace(/\s*/g,"") == "") $(this).html("0");

            var bag_price = parseFloat($(this).data("bagprice"));
            var bag_tax_rate = parseFloat($(this).data("bagtaxrate"));
            var num = parseInt($(this).html());
            tax_price += bag_price * bag_tax_rate.toFixed(2) * num;
            sub_total += parseFloat((bag_price * num).toFixed(2));
            //console.log("bag_price",bag_price);
            if(num > 0) {
                if(bag_get_id != "") bag_get_id += "|";
                bag_get_id += $(this).data("bagid") + "," + num;
            }
        });

        if (select_buy_mode==2){
            show_shipping_fee = shipping_fee;
        }else{
            show_shipping_fee = 0;
        }

        total = parseFloat((sub_total + show_shipping_fee + tax_price).toFixed(2));

        $(".subtotal_box").html(sub_total);
        $(".shipping_fee_box").html(show_shipping_fee);
        $(".tax_box").html(tax_price);
        $(".total_box").html(total);
    }

    function init_save_user_data(){
        if (init_bag_select==1){
            $("#shipping_div").hide();
            $("#pickup_div").show();
            select_buy_mode=1;
        }else{
            $("#shipping_div").show();
            $("#pickup_div").hide();
            select_buy_mode=2;
        }

        if (select_buy_mode==2){
            $("#radiotype2").click();
            $(".shipping_fee_box").html(shipping_fee);
        }else{
            $("#radiotype1").click();
        }
    }

    $(function(){
        init_save_user_data();

        $('.btn_minus,.btn_plus').click(function(){
            var bagid = $(this).data('bagid');
            var num = parseInt($(".bagid_"+bagid).html());
            var carr_num = $(this).data('num');

            num = num + carr_num;
            if(num < 0) num = 0;

            $(".bagid_" + bagid).html(num);

            set_bag_id_number();
        });

        $(":radio").click(function(){
            if($(this).val()==2){
                $("#shipping_div").hide();
                $("#pickup_div").show();
                select_buy_mode=1;
            }else{
                $("#shipping_div").show();
                $("#pickup_div").hide();
                select_buy_mode=2;
            }
        });
    });

    $("body").css({"height":$(window).height()});

    $('#reg_form').click(function () {

        var is_next = true;

        if($('#c_name').val() == '' || $('#c_number').val() == '' || $('#e_date').val() == '' || $('#cvv').val() == ''){
            is_next = false;
        }
        if(select_buy_mode==2){
            if(select_buy_mode==0 || bag_get_id == "" || $('#address').val() == '' || $('#apartment').val() == '' || $('#city').val() == '' || $('#postalcode').val() == ''|| $('#province').val() == ''){
                is_next = false;
            }else{
                is_next = true;
            }
        }
        if(!is_next)
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        else{
            $(this).attr("disabled","disabled");
            var post_data = {
                c_name:$('#c_name').val(),
                c_number:$('#c_number').val(),
                e_date:$('#e_date').val(),
                cvv:$('#cvv').val(),
                address:$('#address').val(),
                apartment:$('#apartment').val(),
                city:$('#city').val(),
                postalcode:$('#postalcode').val(),
                province: $('#province').val(),
                buy_mode:select_buy_mode,
                bag_id:bag_get_id,
                total_price:$(".total_box").html()
            };
            layer.open({content:"{pigcms{:L('_DEALING_TXT_')}"});
            $.ajax({
                url: "{pigcms{:U('Deliver/step_3')}",
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success:function(date){
                    layer.closeAll();
                    if(date.error_code){
                        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:date.msg, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
                        $("#reg_form").removeAttr("disabled");
                    }else{
                        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg,skin: 'msg', time:1,end:function () {
                                window.parent.location = "{pigcms{:U('Deliver/step_4')}";
                            }});
                    }
                }

            });
        }
    });

    $('#jump_btn').click(function () {

        var is_next = true;

        // if($('#c_name').val() == '' || $('#c_number').val() == '' || $('#e_date').val() == '' || $('#cvv').val() == ''){
        //     is_next = false;
        // }
        // if(select_buy_mode==2){
        //     if(select_buy_mode==0||curr_bagid==0 || $('#address').val() == '' || $('#apartment').val() == '' || $('#city').val() == '' || $('#postalcode').val() == ''|| $('#province').val() == ''){
        //         is_next = false;
        //     }else{
        //         is_next = true;
        //     }
        // }
        if(!is_next)
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        else{
            $(this).attr("disabled","disabled");
            var post_data = {
                c_name:$('#c_name').val(),
                c_number:$('#c_number').val(),
                e_date:$('#e_date').val(),
                cvv:$('#cvv').val(),
                address:$('#address').val(),
                apartment:$('#apartment').val(),
                city:$('#city').val(),
                postalcode:$('#postalcode').val(),
                province: $('#province').val(),
                buy_mode:select_buy_mode,
                bag_id:bag_get_id,
                total_price:$(".total_box").html(),
                just_save:1
            };
            layer.open({content:"{pigcms{:L('_DEALING_TXT_')}"});
            $.ajax({
                url: "{pigcms{:U('Deliver/step_3')}",
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success:function(date){
                    layer.closeAll();
                    console.log(date);
                    if(date.error_code){
                        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:date.msg, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],});
                        $("#jump_btn").removeAttr("disabled");
                    }else{
                        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg,skin: 'msg', time:1,end:function () {
                                //window.parent.location = "{pigcms{:U('Deliver/step_4')}";
                                //layer.open({content:"Saved"});
                                //window.parent.location = "{pigcms{:U('Deliver/step_4')}&type=jump";
                            }});
                    }
                }

            });
        }
    });



</script>
</html>