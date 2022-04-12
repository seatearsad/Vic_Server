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

<body>
    <section class="Statistics">
        <div class="Statistics_top clr">
            <a href="javascript:void(0);" id="begin">
                <h2><i>{pigcms{:L('_START_TIME_')}</i></h2>
                <input type="text" readonly="readonly" placeholder="{pigcms{:L('_SELECT_TIME_')}"  name="appDate" id="appDate" value="{pigcms{$begin_time}">
            </a>
            <a href="javascript:void(0)" id="end">
                <h2><i>{pigcms{:L('_END_TIME_')}</i></h2>
                <input type="text" readonly="readonly" placeholder="{pigcms{:L('_SELECT_TIME_')}"  name="appDate1" id="appDate1" value="{pigcms{$end_time}">
            </a>
        </div>
        <section class="robbed">
            <div class="Online c9 p10 f14">
                <span>{pigcms{:L('_SUMMARY_TXT_')} </span>
            </div>
            <div class="Title m10">
                <p class="f14 c9">{pigcms{:L('_ONLINE_PAY_')}：<i>$</i>{pigcms{$online_money|floatval}</p>
                <p class="f14 c9">{pigcms{:L('_CASH_ON_DELI_')}：<i>$</i>{pigcms{$offline_money|floatval}</p>
                <p class="f14 c9">{pigcms{:L('_DELI_PRICE_')}：<i>$</i>{pigcms{$freight_charge|floatval}</p>
                <p class="f14 c9">{pigcms{:L('_TIP_TXT_')}：<i>$</i>{pigcms{$tip|default=0}</p>
            </div>
            <div class="Namelist p10 f14">
                <p class="red">{pigcms{:L('_ORDER_NUM_TOTAL_')}：{pigcms{$order_count|default=0}<i></i></p>
            </div>
        </section>
        <volist name="list" id="order">
        <section class="robbed supply_{pigcms{$order.supply_id}" data-id="{pigcms{$order.supply_id}">
            <div class="Online c9 p10 f14 go_detail" data-id="{pigcms{$order.supply_id}">
                <span>{pigcms{:L('_B_PURE_MY_68_')}: {pigcms{$order.real_orderid}</span>
                <if condition="$order['pay_method'] eq 1">
                <a href="javascript:;" class="fr cd p10">{pigcms{:L('_ONLINE_PAY_')}</a>
                <else />
                <a href="javascript:;" class="fr cd p10 on">{pigcms{:L('_CASH_ON_DELI_')}</a>
                </if>
            </div>
            <div class="Title m10 go_detail" data-id="{pigcms{$order.supply_id}">
                <h2 class="f16 c3">{pigcms{$order.store_name}</h2>
                <p class="f14 c9">{pigcms{:L('_ORDER_TIME_')}：{pigcms{$order.order_time}</p>
                <p class="f14 c9">{pigcms{:L('_DELI_TIME_')}：{pigcms{$order.end_time}</p>
                <if condition="$order['get_type'] eq 1">
                <div class="leaflets">{pigcms{:L('_C_SYS_ASS_ORDER_')}</div>
                </if>
            </div>

            <div class="Namelist p10 f14">
                <p class="red">{pigcms{:L('_TOTAL_RECE_')}：<i>${pigcms{$order.deliver_cash}</i></p>
                <p class="red">{pigcms{:L('_C_DISTANCE_')}:{pigcms{$order.distance}(KM)，{pigcms{:L('_DELI_PRICE_')}:${pigcms{$order.freight_charge},{pigcms{:L('_TIP_TXT_')}:${pigcms{$order.tip_charge}</p>
                <if condition="$order['get_type'] eq 2">
                <div class="Order">From Courier - {pigcms{$order['change_name']}</div>
                </if>
            </div>
        </section>
        </volist>
    </section>
    <section class="bottom">
        <div class="bottom_n">
            <ul>
                <li class="Statistics fl">
                    <a href="{pigcms{:U('Deliver/schedule')}">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
                </li>
                <li class="home fl">
                      <a href="{pigcms{:U('Deliver/index')}">
                        <i></i>{pigcms{:L('_HOME_TXT_')}
                      </a>
                </li>
                 <li class="My Myon fl">
                    <a href="{pigcms{:U('Deliver/info')}">{pigcms{:L('_PROFILE_TXT_')}</a>
                </li>
            </ul>
        </div>
    </section>
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

var is_flag = false;
$(document).on('click', '.go_detail', function(e){
    e.stopPropagation();
    if (is_flag) {
        return false;
    }
    is_flag = true;
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