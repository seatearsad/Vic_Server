<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$deliver_session['name']}-{pigcms{:L('_STATISTICS_TXT_')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>
<style>
    body{background-color: white;}
    .order_title .pay_status{
        float: right;
        border: 1px solid cornflowerblue;
        border-radius: 2px;
        padding: 1px 4px;
        color: cornflowerblue;
        font-size: 10px;
    }
    .order_title .pay_status_red{
        float: right;
        border: 1px solid indianred;
        border-radius: 2px;
        padding: 1px 4px;
        color: indianred;
        font-size: 10px;
    }
    .store_name{
        color: #333333;
        padding-top: 5px;
        padding-bottom: 5px;
        border-bottom: 1px dashed #999999;
    }
    .robbed{
        background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
        background-repeat: no-repeat;
        background-position:top 70% right 5px;
        background-size: auto 20%;
        box-sizing: border-box;
    }
</style>
<body>
    <include file="header" />
    <div style="height: 70px"></div>
    <div style="text-align: center">
        <if condition="$begin_time neq '' and $end_time neq ''">
            {pigcms{$begin_time} -- {pigcms{$end_time}
        <else />
            {pigcms{:L('_ND_ALLORDERS_')}
        </if>
    </div>
    <volist name="list" id="order">
        <section class="robbed go_detail supply_{pigcms{$order.supply_id}" data-id="{pigcms{$order.supply_id}">
            <div class="order_title">
                <span>Order # {pigcms{$order.order_id}</span>
                <span style="margin-left: 10px;">{pigcms{$order['end_time']}</span>
                <span style="margin-left: 10px;">{pigcms{$order['show_time']}</span>
                <if condition="$order['pay_method'] eq 1">
                <span class="pay_status">
                    {pigcms{:L('_ND_PAID_')}
                </span>
                <else />
                <span class="pay_status_red">
                    {pigcms{:L('_ND_CASH_')}
                </span>
                </if>
            </div>
            <div class="store_name">
                {pigcms{$order.store_name}
            </div>
            <div style="margin-top: 5px;">
                {pigcms{:L('_ACTUAL_PAYMENT_')} :
                <if condition="$order['pay_method'] eq 1">
                    $0.00
                <else />
                    ${pigcms{$order['deliver_cash']}
                </if>
            </div>
            <div style="margin-top: 5px;">
                {pigcms{:L('_DELI_PRICE_')} : ${pigcms{$order.freight_charge}
            </div>
            <div style="margin-top: 5px;">
                {pigcms{:L('_ND_TIPS_')} :
                <if condition="$order['pay_method'] eq 1">
                    ${pigcms{$order.tip_charge}
                <else />
                    N/A
                </if>
            </div>
        </section>
    </volist>
<script type="text/javascript">
var DetailUrl = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'d%'))}";
$(function () {
	var begin = {};
	begin.date = {preset : 'date'};
	begin.default = {
	        theme: 'android-ics light', //皮肤样式
	        mode: 'scroller', //日期选择模式
			display: 'bottom', //显示方式
			dateFormat: 'yyyy-mm-dd',
            lang:'en',
			onSelect: function (valueText, inst) {
				$("#appDate").val(valueText);
				if ($("#appDate1").val() == '') {
				} else {
					location.href="{pigcms{:U('Deliver/tongji')}&begin_time="+valueText+'&end_time='+$("#appDate1").val();
				}
	        }
	};
	var enddate = {};
	enddate.date = {preset : 'date'};
	enddate.default = {
	        theme: 'android-ics light', //皮肤样式
	        mode: 'scroller', //日期选择模式
			display: 'bottom', //显示方式
			dateFormat: 'yyyy-mm-dd',
            lang:'en',
			onSelect: function (valueText, inst) {
				$("#appDate1").val(valueText);
				if ($("#appDate").val() == '') {
				} else {
					location.href="{pigcms{:U('Deliver/tongji')}&end_time="+valueText+'&begin_time='+$("#appDate").val();
				}
	        }
	};
	$("#begin").mobiscroll($.extend(begin['date'], begin['default']));
	$("#end").mobiscroll($.extend(enddate['date'], enddate['default']));
});

//var is_flag = false;
$(document).on('click', '.go_detail', function(e){
    //e.stopPropagation();
    // if (is_flag) {
    //     return false;
    // }
    // is_flag = true;
    var supply_id = $(this).attr("data-id");
    location.href = DetailUrl.replace(/d%/, supply_id);
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