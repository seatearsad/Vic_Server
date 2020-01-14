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
            background:#fff;
            font-size: 14px;
            color:#333333;
            width: 100%;
            border: 0;
        }
        .Namelist {
            padding: 2px 10px;
            border-bottom: #e7e7e7 1px dashed;
            position: relative;
        }
        .sign_bottom a.service{
            background-color: #ffa52d;
            box-shadow: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<section class="nav_end clr">
		<if condition="$list">
		<volist name="list" id="row">
        <section class="details p10">
            <div class="details_top">
                <h2 class="f16 c3">
                    Order #{pigcms{$row['order_id']}
                </h2>
                <div style="color: #ffa52d">
                    {pigcms{$row['note']}
                </div>
            </div>
        </section>
		<section class="robbed supply_{pigcms{$row['supply_id']}" data-id="{pigcms{$row.supply_id}">
			<div class="Title m10" data-id="{pigcms{$row.supply_id}" style="border: 0;">
				<h2 class="f16 c3">{pigcms{:lang_substr($row['store_name'],C('DEFAULT_LANG'))}</h2>
				<if condition="$row['get_type'] eq 1">
				<div class="leaflets">{pigcms{:L('_C_SYS_ASS_ORDER_')}</div>
				</if>
			</div>
			<div class="Namelist p10 f14">
				<p style="height: 20px;font-size: 12px;color: #666666">
                    {pigcms{:L('_TOTAL_RECE_')}：<i>${pigcms{$row['deliver_cash']}</i>
                    <INPUT TYPE="HIDDEN" NAME="charge_total" VALUE="{pigcms{$row['deliver_cash']}">
                </p>
			</div>
            <div id="tip_label" class="normal-fieldset" style="height: 100%;margin-bottom: 20px;">
                <h4 style="margin: .8rem .2rem;">1. {pigcms{:L('_ND_TIP_')}</h4>
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
                            Input: $ <input type="text" id="tip_fee" name="tip_fee" size="20" style="height: 25px;">
                        </div>
                        <div style="margin: 20px auto 5px;width: 98%;">
                            <div style="margin-bottom: 5px;"><span>{pigcms{:L('_ND_TIP_')}:</span><span id="tip_num">$0</span></div>
                            <span>{pigcms{:L('_B_PURE_MY_70_')}:</span><span id="add_tip">$0</span>
                        </div>
                    </dd>
                </dl>
            </div>
            <div id="credit" class="normal-fieldset" style="margin-bottom:120px;margin-left: 10px;border-top: 1px #333333 dashed;" >
                <h4 style="margin: .8rem 0;">
                    2. Payment
                </h4>
                <div style="margin-bottom: 10px">
                    Thank you for using Tutti! Your payment information are processed securely.
                </div>
                <dl class="list">
                    <dd class="dd-padding">
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_CREDITHOLDER_NAME_')}：</span>
                            <input type="text" maxlength="20" size="20" name="name" class="form-field" id="card_name" value="" style="height: 25px;float: left"/>
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_CREDIT_CARD_NUM_')}：</span>
                            <input type="text" maxlength="20" size="20" name="card_num" class="form-field" id="card_num" value="" style="height: 25px;float: left"/>
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_EXPRIRY_DATE_')}：</span>
                            <input type="text" maxlength="4" size="20" name="expiry" class="form-field" id="expiry" value="" style="height: 25px;float: left"/>
                        </div>
                    </dd>
                </dl>
            </div>
			<div class="sign_bottom">
				<a href="javascript:;" class="service" data-id="{pigcms{$row['supply_id']}">{pigcms{:L('_PAYMENT_ORDER_')}</a>
			</div>
		</section>
		</volist>
		<else />
		<!-- 空白图 -->
			<div class="psnone">
				<img src="{pigcms{$static_path}images/qdz_02.jpg">
			</div>
		<!-- 空白图 -->
		</if>
</section>
<script type="text/javascript">
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