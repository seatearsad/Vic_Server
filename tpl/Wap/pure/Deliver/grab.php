<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>等待抢单列表</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<!-- 	<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script> -->
<!-- 	<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script> -->
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
<!-- 	<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script> -->
	<script type="text/javascript">
		var location_url = "{pigcms{:U('Deliver/grab')}", detail_url = "{pigcms{:U('Deliver/detail')}", lat = "{pigcms{$deliver_session['lat']}", lng = "{pigcms{$deliver_session['lng']}", static_path = "{pigcms{$static_path}";
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=zh-CN"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/grab.js?211" charset="utf-8"></script>
</head>
<body>
	<div class="Dgrab" id="container">
		<div class="scroller" id="scroller">
			<div id="grab_list"></div>
		</div>
		<!-- 空白图 -->
		<div class="psnone" ><img src="{pigcms{$static_path}images/qdz_02.jpg"></div>
		<!-- 空白图 -->
	</div>
	<include file="menu"/>
</body>
<script id="replyListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
<section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
	<div class="Online c9 p10 f14 go_detail" data-id="{{ d.list[i].supply_id }}" style="cursor: pointer;">
		<span>订单编号: {{ d.list[i].real_orderid }}</span>
		{{# if(d.list[i].pay_method == 1){ }}
		<a href="javascript:;" class="fr cd p10">在线支付</a>
		{{# } else { }}
		<a href="javascript:;" class="fr cd p10 on">货到付款</a>
		{{# } }}
	</div>
	<div class="Title m10 go_detail" data-id="{{ d.list[i].supply_id }}">
		<h2 class="f16 c3">{{ d.list[i].store_name }}</h2>
		<p class="f14 c9">下单时间：{{ d.list[i].order_time }}</p>
	</div>
	<div class="delivery m10">
		<p class="f14 c6 on">
			<a href="javascript:;" class="clr">
				<span class="fl">取</span>
				<em class="fl">{{ d.list[i].from_site }}</em>
				<i class="cd f12 fl">距离我{{ d.list[i].store_distance }}</i>
			</a>
		</p>
		<p class="f14 c6 on1">
			<a href="{{ d.list[i].map_url }}" class="clr">
				<span class="fl">送</span>
				<em class="fl">{{ d.list[i].aim_site }}</em>
				<i class="cd f14 fl">查看路线</i>
			</a>
		</p>
	</div>
	<div class="Namelist p10 f14">
		<h2 class="f15 c3">{{ d.list[i].name }} <span class="c6"><a href="tel:{{ d.list[i].phone }}">{{ d.list[i].phone }}</a></span></h2>
		<p class="c9">期望送达：{{ d.list[i].appoint_time }}</p>
		<p class="red">应收现金：<i>{{ d.list[i].deliver_cash }}</i>元</p>
		<p class="red">配送距离{{ d.list[i].distance }}公里，配送费:${{ d.list[i].freight_charge }},小费:${{d.list[i].tip_charge}}</p>
	</div>
	<div class="sign_bottom">
		<a href="javascript:void(0);" class="rob" data-spid="{{ d.list[i].supply_id }}">抢单</a>
	</div>
</section>
{{# } }}
</script>
</html>