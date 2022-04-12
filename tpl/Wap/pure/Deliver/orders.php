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
<link href="{pigcms{$static_path}css/deliver.css?v=2.0.0" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
<script src="{pigcms{$static_path}js/jquery.min.js"></script>
<!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>
<style>
    body{background-color: #F8F8F8;}
    .order_title .pay_status{
        float: right;
        border: 1px solid cornflowerblue;
        border-radius: 2px;
        padding: 1px 4px;
        color: cornflowerblue;
        font-size: 12px;
    }
    .order_title .pay_status_red{
        float: right;
        border: 1px solid indianred;
        border-radius: 2px;
        padding: 1px 4px;
        color: indianred;
        font-size: 12px;
    }
    .store_name{
        color: #333333;
        padding-top: 5px;
        padding-bottom: 5px;
        border-bottom: 1px dashed #999999;
        font-size: 14px;
    }
    .robbed{
        background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
        background-repeat: no-repeat;
        background-position:top 70% right 5px;
        background-size: auto 20%;
        box-sizing: border-box;
        color: #666666;
        font-size: 11px;
    }

    .list_head{
        width: 90%;
        margin: 5px auto;
        font-weight: bold;
        color: #ffa52d;
        font-size: 18px;
    }
    .list_order{
        display: flex;
        width: 85%;
        margin: 5px auto;
        line-height: 50px;
        font-size: 16px;
        font-weight: bolder;
        color: #555555;
        border-bottom: 1px solid #EEEEEE;
        cursor: pointer;
    }
    .list_order span{
        flex: 1 1 21%;
    }
</style>
<body>
    <include file="header" />
    <div class="page_title" style="padding-bottom: 5px;">{pigcms{:L('_ND_ORDERHISTORY_')}</div>
    <div style="text-align: center">
        <if condition="$begin_time neq '' and $end_time neq ''">
            {pigcms{$begin_time} -- {pigcms{$end_time}
        <else />
            &nbsp;
        </if>
    </div>
    <volist name="list" id="month_order">
        <div class="list_head">
            <span>{pigcms{$key}</span>
            <span style="float: right">${pigcms{:number_format($month_order['summary'],2,'.','')}</span>
        </div>
        <volist name="month_order.list" id="order">
            <div class="list_order go_detail supply_{pigcms{$order.supply_id}" data-id="{pigcms{$order.supply_id}">
                <span>{pigcms{$order.end_time}</span>
                <span style="flex: 1 1 35%;">#{pigcms{$order.order_id}</span>
                <span>
                    <if condition="$order['pay_method'] neq 1">
                        {pigcms{:L('_ND_CASH_')}
                    </if>
                </span>
                <span>${pigcms{:number_format($order['summary'],2,'.','')}</span>
                <label class="material-icons" style="margin-top: 15px; font-size: 20px; text-align: right;">arrow_forward</label>
            </div>
        </volist>
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
    },'json');

    return message;
}
</script>
</body>
</html>