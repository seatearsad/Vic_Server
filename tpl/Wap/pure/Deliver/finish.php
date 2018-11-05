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
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	<script type="text/javascript">
		var location_url = "{pigcms{:U('Deliver/ajaxFinish')}", del_url = "{pigcms{:U('Deliver/del')}", DetailUrl = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'d%'))}";
	</script>
	<script type="text/javascript" src="{pigcms{$static_path}js/deliver_finish.js?v=210" charset="utf-8"></script>
</head>
<body>
<div class="Dgrab" id="container">
	<div class="scroller" id="scroller">
		<div class="pullDown" id="pullDown">
			<span class="pullDownIcon"></span><span class="pullDownLabel">{pigcms{:L('_DROP_REFRESH_')}</span>
		</div>
		<div id="finish_list"></div>
		<div class="pullUp" id="pullUp">
			<span class="pullUpIcon"></span><span class="pullUpLabel">{pigcms{:L('_PULL_UP_MORE_')}</span>
		</div>
		<!-- 空白图 -->
			<div class="psnone" style="display: none;">
				<img src="{pigcms{$static_path}images/qdht_02.jpg">
			</div>
		<!-- 空白图 -->
	</div>

</div>
<script id="replyListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
<section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
	<div class="Online c9 p10 f14 go_detail" data-id="{{ d.list[i].supply_id }}">
		<span>{pigcms{:L('_B_PURE_MY_68_')}: {{ d.list[i].real_orderid }}</span>
		{{# if(d.list[i].pay_method == 1){ }}
		<a href="javascript:;" class="fr cd p10">{pigcms{:L('_ONLINE_PAY_')}</a>
		{{# } else { }}
		<a href="javascript:;" class="fr cd p10 on">{pigcms{:L('_CASH_ON_DELI_')}</a>
		{{# } }}
	</div>
        <div class="Title m10 go_detail" data-id="{{ d.list[i].supply_id }}">
            <h2 class="f16 c3">{{ d.list[i].store_name }}</h2>
            <p class="f14 c9">{pigcms{:L('_ORDER_TIME_')}：{{ d.list[i].order_time }}</p>
            <p class="f14 c9">{pigcms{:L('_DELI_TIME_')}：{{ d.list[i].end_time }}</p>
			{{# if(d.list[i].get_type == 1){ }}
			<div class="leaflets">{pigcms{:L('_C_SYS_ASS_ORDER_')}</div>
			{{# } }}
        </div>
        <div class="delivery m10">
            <p class="f14 c6 on">
                <a href="#" class="clr">
                    <span class="fl">{pigcms{:L('_C_PICK_UP_')}</span>
                    <em class="fl">{{ d.list[i].from_site }}</em>   
                </a>
            </p>
            <p class="f14 c6 on1">
                <a href="{{ d.list[i].map_url }}" class="clr">
                    <span class="fl">{pigcms{:L('_C_DELIVER_')}</span>
                    <em class="fl">{{ d.list[i].aim_site }}</em>
                    <i class="cd f14 fl">{pigcms{:L('_LOOK_ROUTE_')}</i>
                </a>    
            </p>
        </div>
        <div class="Namelist p10 f14">
            <h2 class="f15 c3">{{ d.list[i].name }} <span class="c6"><a href="tel:{{ d.list[i].phone }}">{{ d.list[i].phone }}</a></span></h2>
            <p class="c9">{pigcms{:L('_EXPECTED_TIME_')}：{{ d.list[i].appoint_time }}</p>
            <p class="red">{pigcms{:L('_TOTAL_RECE_')}：<i>${{ d.list[i].deliver_cash }}</i></p>
            <p class="red">{pigcms{:L('_C_DISTANCE_')}{pigcms{$row['distance']}(KM)，{pigcms{:L('_DELI_PRICE_')}:${pigcms{$row['freight_charge']},{pigcms{:L('_TIP_TXT_')}:${pigcms{$row['tip_charge']}</p>
			{{# if(d.list[i].get_type == 2){ }}
			<div class="Order">From Courier - {pigcms{$row['change_name']}</div>
			{{# } }}
        </div>
        <div class="sign_bottom">
            <a href="javascript:;" class="del" data-id="{{ d.list[i].supply_id }}">{pigcms{:L('_B_PURE_MY_27_')}</a>
        </div>
</section>
{{# } }}
</script>
</section>

<include file="menu"/>
</body>
</html>