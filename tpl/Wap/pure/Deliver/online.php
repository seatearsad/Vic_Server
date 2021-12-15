<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_COURIER_CENTER_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>

    <style>
        .tip_s{width: 30%; height: 40px; border: 1px #999999 solid;line-height: 40px;text-align: center;font-size: 16px;display:-moz-inline-box;display:inline-block;cursor: pointer}
        .tip_on{background-color: #ffa52d;color: #ffffff;border-color:#ffa52d }
        input{border: 1px #999999 solid;width: 50%;}
        .robbed{
            padding: 5px 10px;
            background:none;
            font-size: 15px;
            color:#333333;
            width: 100%;
            border: 0;
        }
        .Namelist {
            padding: 2px 10px;
            border-bottom: #e7e7e7 1px dashed;
            position: relative;
        }
        .sign_bottom{
            background: none;
        }
        .sign_bottom a{
            padding: 10px 0;
            width: 90%;
        }
        .sign_bottom a.service{
            background-color: #ffa52d;
            box-shadow: none;
            border-radius: 5px;
        }
        .order_title{
            padding: 70px 5% 10px 5%;
            font-size: 16px;
            color: white;
        }
        .span_right{
            float: right;
        }
        .order_num{
            width: 90%;
            margin: 5px auto;
            padding: 10px 0 20px 0;
            font-weight: bold;
            font-size: 18px;
            color: #294068;
        }
    </style>
</head>
<body>
<include file="index_header" />
    <div class="order_title" style="background: #294068;">
            <span>
                Arriving
            </span>
            <span class="span_right"></span>
    </div>
    <div class="order_num" style="border-bottom: 1px solid #999999;">
            <span>
                Order {pigcms{$supply['order_id']}
            </span>
        <if condition="$supply['pay_method'] neq 1">
                <span class="span_right">
                    <label style="border: 1px solid #294068; border-radius: 5px;font-size: 14px;padding: 5px 10px;">
                        {pigcms{:L('_ND_UNPAID_')}
                    </label>
                </span>
        </if>
    </div>
    <section class="nav_end clr">
    <section class="robbed supply_{pigcms{$supply['supply_id']}" data-id="{pigcms{$supply.supply_id}">
        <div class="Title m10" data-id="{pigcms{$supply.supply_id}" style="border: 0;">
            <h2 class="f18 c3" style="color: #294068;font-weight: bold;">{pigcms{:lang_substr($supply['store_name'],C('DEFAULT_LANG'))}</h2>
            <if condition="$supply['get_type'] eq 1">
            <div class="leaflets">{pigcms{:L('_C_SYS_ASS_ORDER_')}</div>
            </if>
        </div>
        <div style="margin-left: 20px;">
            <div class="p10" style="line-height: 20px;">
                <div style="font-weight: bold;">{pigcms{$supply['username']}</div>
                <div style="margin-top: 5px;">
                    <span>Subtotal:</span><span style="float: right;">${pigcms{$supply['goods_price']}</span>
                </div>
                <div>
                    <span>Delivery Fee:</span><span style="float: right;">${pigcms{$supply['freight_charge']}</span>
                </div>
                <div>
                    <span>Tax:</span><span style="float: right;">${pigcms{$supply['tax_price']}</span>
                </div>
                <div>
                    <span>Bottle Deposit:</span><span style="float: right;">${pigcms{$supply['deposit_price']}</span>
                </div>
                <div>
                    <span>Driver Tip:</span><span id="tip_num" style="float: right;">$0</span>
                </div>
            </div>
            <div class="Namelist p10">
                <p style="height: 20px;color: #666666">
                    <INPUT TYPE="HIDDEN" NAME="charge_total" VALUE="{pigcms{$supply['deliver_cash']}">
                </p>
            </div>
            <div id="tip_label" class="normal-fieldset" style="height: 100%;margin-bottom: 20px;">
                <dl class="list">
                    <dd class="dd-padding">
                        <div id="tip_list" style="margin: auto;width: 98%">
                            <span class="tip_s tip_on">
                                15%
                            </span>
                            <span class="tip_s">
                                20%
                            </span>
                            <span class="tip_s">
                                25%
                            </span>
                        </div>
                        <div style="margin: 20px auto 5px;width: 98%">
                            Or input tip amount: $ <input type="text" id="tip_fee" name="tip_fee" size="20" style="height: 25px;">
                        </div>
                        <div style="margin: 20px auto 5px;width: 98%;font-weight: bold;">
                            <span>Total:</span><span id="add_tip" style="float: right;">$0</span>
                        </div>
                    </dd>
                </dl>
            </div>
        </div>
        <div id="credit" class="normal-fieldset" style="margin-bottom:50px;margin-left: 10px;border-top: 1px #999999 dashed;" >
            <div style="margin-left: 15px;">
                <h4 style="margin: .8rem 0;">
                    Payment
                </h4>
                <dl class="list" style="display: inline-block">
                    <dd class="dd-padding">
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_CREDITHOLDER_NAME_')}：</span>
                            <input type="text" maxlength="20" size="20" name="name" class="form-field" id="card_name" value="" style="height: 25px;float: left"/>
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_CREDIT_CARD_NUM_')}：</span>
                            <input type="tel" maxlength="20" size="20" name="card_num" class="form-field" id="card_num" value="" style="height: 25px;float: left"/>
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_EXPRIRY_DATE_')}：</span>
                            <input type="tel" maxlength="4" size="20" name="expiry" class="form-field" id="expiry" value="" style="height: 25px;float: left"/>
                        </div>
                    </dd>
                </dl>
                <div style="margin-top: 20px;font-size: 14px;">
                    Thank you for using Tutti! Your payment information is processed securely.
                </div>
            </div>
        </div>

        <div class="sign_bottom">
            <a href="javascript:;" class="service" data-id="{pigcms{$supply['supply_id']}">Pay</a>
        </div>
    </section>
</section>
<script type="text/javascript">
    if(ua.match(/IPhonex/i)) {
        $('.order_title').css("padding-top","94px");
    }

$(function(){
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	$(".Dgrab").css({"margin-top":"40px"});
	$(".nav_end .Dgrab").width($(window).width());
});

//garfunkel add
var isb = false;

$(function(){
    if(parseFloat($('input[name="charge_total"]').val()) <= 20){
        isb = true;
    }
    var tipxn = new Array(3,4,5);
    var i = 0;
    $('#tip_list').children('span').each(function(){
        $(this).click(tip_select);
        if(isb){
            $(this).text('$' + tipxn[i]);
        }
        i++;
    });
    CalTip();

    function payment(e) {
        if($('#card_name').val() == "" || $('#card_num').val() == "" || $('#expiry').val() == ""){
            layer.open({
                title:['Message'],
                content:"Please complete your payment information."
            });
            return false;
        }
        e.stopPropagation();
        layer.open({type:2,content:"{pigcms{:L('_LOADING_TXT_')}",shadeClose:false});
        var re_data = {
            'name':$('#card_name').val(),
            'card_num':$('#card_num').val(),
            'expiry':$('#expiry').val(),
            'charge_total':$('#add_tip').text().replace('$', ""),
            'tip':$('#tip_num').text().replace('$', ""),
            'rvarwap':1,
            'supply_id':$(this).attr("data-id")
        };
        $.post("{pigcms{:U('Deliver/online')}",re_data,function(data){
            layer.closeAll();
            layer.open({title:['Message'],content:data.info});
            if(data.status == 1){
                setTimeout("window.location.href = '"+data.url+"'",200);
            }
        });
    }

    $(".service").bind("click",payment);
});
//计算小费
function CalTip(){
    var tipNum = 0;

    var num = $('#tip_fee').val();
    if(/^\d+(\.\d{1,2})?$/.test(num) && num != ""){
        tipNum = parseFloat(num);
    }else{
        $('#tip_list').children('span').each(function(){
            if($(this).hasClass('tip_on')){
                if(isb)
                    tipNum = parseFloat($(this).text().replace('$', ""));
                else
                    tipNum = $('input[name="charge_total"]').val() *  ($(this).text().replace(/%/, "")/100);
            }
        });
    }
    var totalNum = parseFloat($('input[name="charge_total"]').val()) + parseFloat(tipNum);

    $('#tip_num').text('$' + tipNum.toFixed(2));
    $('#add_tip').text('$' + totalNum.toFixed(2));
    // alert($('#add_tip').text().replace('$', ""));
}

function tip_select(){
    $('#tip_list').children('span').each(function(){
        $(this).removeClass('tip_on');
    });
    $(this).addClass('tip_on');
    $('#tip_fee').val("");
    $('#tip_fee').after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").removeClass('form-field--error');
    CalTip();
}

$('#tip_fee').live('focusin focusout',function(event){
    if(event.type == 'focusin'){
        $(this).siblings('.inline-tip').remove();$(this).removeClass('form-field--error');
    }else{
        $(this).val($.trim($(this).val()));
        var num = $(this).val();
        if(num != ''){
            if(!/^\d+(\.\d{1,2})?$/.test(num)){
                alert("{pigcms{:L('_PLEASE_RIGHT_PRICE_')}");
                $(this).focus();
                $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").addClass('form-field--error');
            }else{
                $('#tip_list').children('span').each(function(){
                    $(this).removeClass('tip_on');
                });
            }
        }else{
            var isC = false;
            $('#tip_list').children('span').each(function(){
                if($(this).hasClass('tip_on')){
                    isC = true;
                }
            });
            if(!isC){
                var i=0;
                $('#tip_list').children('span').each(function(){
                    if(i == 1){
                        $(this).addClass('tip_on');
                    }
                    i++;
                });
            }
        }
        CalTip();
    }
});

$('#card_name').live('focusin focusout',function(event){
    if(event.type == 'focusin'){
        $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
    }else{
        $(this).val($.trim($(this).val()));
        var name = $(this).val();
        if(name.length < 2){
            $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
        }
    }
});
$('#card_num').live('focusin focusout',function(event){
    if(event.type == 'focusin'){
        $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
    }else{
        $(this).val($.trim($(this).val()));
        var num = $(this).val();
        if(!/^\d{13,}$/.test(num)){
            $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
        }
    }
});

$('#expiry').live('focusin focusout',function(event){
    if(event.type == 'focusin'){
        $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
    }else{
        $(this).val($.trim($(this).val()));
        var expiry = $(this).val();
        if(expiry.length < 4 || expiry.length > 4){
            $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
        }
    }
});

var ua = navigator.userAgent;
if(!ua.match(/TuttiDeliver/i)) {
    navigator.geolocation.getCurrentPosition(function (position) {
        updatePosition(position.coords.latitude,position.coords.longitude);
    });
}
//ios app 更新位置
function updatePosition(lat,lng){
    var message = '';
    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
        if(result){
            message = result.message;
        }else {
            message = 'Error';
        }
    });

    return message;
}
</script>
</body>
</html>