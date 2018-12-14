<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>Deliver List</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
</head>
<body>
<section class="Navigation">
	<ul class="clr">
		<a href="{pigcms{:U('Deliver/pick')}"><li class="on">{pigcms{:L('_CC_PICK_UP_')}</li></a>
		<a href="{pigcms{:U('Deliver/send')}"><li class="on1" style="border-bottom:#ef4c79 2px solid;color:#ef4c79">{pigcms{:L('_CC_UNDELIVERED_')}</li></a>
		<a href="{pigcms{:U('Deliver/my')}"><li class="on2">{pigcms{:L('_CC_IN_TRANSIT_')}</li></a>
	</ul>
</section>
<section class="nav_end clr">
	<div class="Dgrab" id="Dgrab1">
		<if condition="$list">
		<volist name="list" id="row">
		<section class="robbed supply_{pigcms{$row['supply_id']}" data-id="{pigcms{$row.supply_id}">
			<div class="Online c9 p10 f14 go_detail" data-id="{pigcms{$row.supply_id}">
				<span>
                    {pigcms{:L('_B_PURE_MY_68_')}: {pigcms{$row['real_orderid']}
                    <if condition="$row['uid'] eq 0">
                        ({pigcms{:L('_PAY_FROM_MER_')})
                    </if>
                </span>
				<if condition="$row['pay_method'] eq 1">
				<a href="javascript:;" class="fr cd p10">{pigcms{:L('_ONLINE_PAY_')}</a>
				<else />
				<a href="javascript:;" class="fr cd p10 on">{pigcms{:L('_CASH_ON_DELI_')}</a>
				</if>
				
			</div>
			<div class="Title m10 go_detail" data-id="{pigcms{$row.supply_id}">
				<h2 class="f16 c3">{pigcms{$row['store_name']}</h2>
				<p class="f14 c9">{pigcms{:L('_ORDER_TIME_')}：{pigcms{$row['order_time']}</p>
				<if condition="$row['get_type'] eq 1">
				<div class="leaflets">{pigcms{:L('_C_SYS_ASS_ORDER_')}</div>
				</if>
			</div>
			<div class="delivery m10">
				<p class="f14 c6 on">
					<a href="javascript:;" class="clr">
						<span class="fl">{pigcms{:L('_C_PICK_UP_')}</span>
						<em class="fl">{pigcms{$row['from_site']}</em>   
					</a>
				</p>
				<p class="f14 c6 on1">
					<a href="{pigcms{$row['map_url']}" class="clr">
						<span class="fl">{pigcms{:L('_C_DELIVER_')}</span>
						<em class="fl">{pigcms{$row['aim_site']}</em>
						<i class="cd f14 fl">{pigcms{:L('_LOOK_ROUTE_')}</i>
					</a>    
				</p>
			</div>
			<div class="Namelist p10 f14">
				<h2 class="f15 c3">{pigcms{$row['name']} <span class="c6"><a href="tel:{pigcms{$row['phone']}">{pigcms{$row['phone']}</a></span></h2> 
				<p class="c9">{pigcms{:L('_EXPECTED_TIME_')}：{pigcms{$row['appoint_time']}</p>
				<if condition="$row['note']">
				<p class="c9">{pigcms{:L('_NOTE_INFO_')}：{pigcms{$row['note']}</p>
				</if>
				<p class="red">{pigcms{:L('_TOTAL_RECE_')}：<i>${pigcms{$row['deliver_cash']}</i></p>
				<p class="red">{pigcms{:L('_C_DISTANCE_')}{pigcms{$row['distance']}(KM)，{pigcms{:L('_DELI_PRICE_')}:${pigcms{$row['freight_charge']},{pigcms{:L('_TIP_TXT_')}:${pigcms{$row['tip_charge']}</p>
				<if condition="$row['get_type'] eq 2">
				<div class="Order">From Courier - {pigcms{$row['change_name']}</div>
				</if>
			</div>
			<div class="sign_bottom">
				<a href="javascript:;" class="Dis" data-id="{pigcms{$row['supply_id']}">{pigcms{:L('_DELI_TXT_')}</a>
			</div>
		</section>
		</volist>
		<else />
		<!-- 空白图 -->
			<div class="psnone">
				<img src="{pigcms{$static_path}images/qdz_02.png">
			</div>
		<!-- 空白图 -->
		</if>
	</div>
</section>
<script type="text/javascript">
$(function(){
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	$(".Dgrab").css({"margin-top":"40px"});
	$(".nav_end .Dgrab").width($(window).width());

	var DeliverListUrl = "{pigcms{:U('Deliver/send')}";
	var mark = 0;

	function grab(e) {
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		$.post(DeliverListUrl, "supply_id="+supply_id, function(json){
			mark = 0;
			if (json.status) {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'更新配送状态成功！',btn: ['确定'],end:function(){}});
			} else {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'系统出错~',btn: ['确定'],end:function(){}});
			}
			$(".supply_"+supply_id).remove();
		});
	}

	function detail(e) {
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		var DetailUrl = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'d%'))}";
		location.href = DetailUrl.replace(/d%/, supply_id);
	}

	$(".Dis").bind("click", grab);
	$(".go_detail").bind("click", detail);
	
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
<include file="menu"/>
</body>
</html>