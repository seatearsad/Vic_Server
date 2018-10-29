<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>配送列表</title>
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
		<a href="{pigcms{:U('Deliver/pick')}"><li class="on">待取货</li></a>
		<a href="{pigcms{:U('Deliver/send')}"><li class="on1">待配送</li></a>
		<a href="{pigcms{:U('Deliver/my')}"><li class="on2" style="border-bottom:#19caad 2px solid;color:#19caad">配送中</li></a>
	</ul>
</section>
<section class="nav_end clr">
	<div class="Dgrab" id="Dgrab1">
		<if condition="$list">
		<volist name="list" id="row">
		<section class="robbed supply_{pigcms{$row['supply_id']} go_detail" data-id="{pigcms{$row.supply_id}">
			<div class="Online c9 p10 f14" data-id="{pigcms{$row.supply_id}">
				<span>
                    订单编号: {pigcms{$row['real_orderid']}
                    <if condition="$row['uid'] eq 0">
                        (代客下单)
                    </if>
                </span>
				<if condition="$row['pay_method'] eq 1">
				<a href="javascript:;" class="fr cd p10">在线支付</a>
				<else />
				<a href="javascript:;" class="fr cd p10 on">货到付款</a>
				</if>
				
			</div>
			<div class="Title m10 go_detail" data-id="{pigcms{$row.supply_id}">
				<h2 class="f16 c3">{pigcms{$row['store_name']}</h2>
				<p class="f14 c9">下单时间：{pigcms{$row['order_time']}</p>
				<if condition="$row['get_type'] eq 1">
				<div class="leaflets">系统派单</div>
				</if>
			</div>
			<div class="delivery m10">
				<p class="f14 c6 on">
					<a href="javascript:;" class="clr">
						<span class="fl">取</span>
						<em class="fl">{pigcms{$row['from_site']}</em>   
					</a>
				</p>
				<p class="f14 c6 on1">
					<a href="{pigcms{$row['map_url']}" class="clr">
						<span class="fl">送</span>
						<em class="fl">{pigcms{$row['aim_site']}</em>
						<i class="cd f14 fl">查看路线</i>
					</a>    
				</p>
			</div>
			<div class="Namelist p10 f14">
				<h2 class="f15 c3">{pigcms{$row['name']} <span class="c6"><a href="tel:{pigcms{$row['phone']}">{pigcms{$row['phone']}</a></span></h2> 
				<p class="c9">期望送达：{pigcms{$row['appoint_time']}</p>
				<if condition="$row['note']">
				<p class="c9">客户备注：{pigcms{$row['note']}</p>
				</if>
				<p class="red" style="height: 30px;">
                    应收现金：<i>{pigcms{$row['deliver_cash']}</i>元
                    <!--if condition="$row['deliver_cash'] neq 0"-->
                    <if condition="$row['uid'] eq 0">
                    <input type="button" value="线上支付" style="width: 120px;height: 30px; background-color: #04B7A5;color: #ffffff;" class="t_online" data-id="{pigcms{$row['supply_id']}">
                    </if>
                </p>
				<p class="red">配送距离{pigcms{$row['distance']}公里，配送费:${pigcms{$row['freight_charge']},小费:${pigcms{$row['tip_charge']}</p>
				<if condition="$row['get_type'] eq 2">
				<div class="Order">订单来源于{pigcms{$row['change_name']}配送员</div>
				</if>
			</div>
			<div class="sign_bottom">
				<a href="javascript:;" class="service" data-id="{pigcms{$row['supply_id']}">送达</a>
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
	</div>
</section>
<script type="text/javascript">
$(function(){
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	$(".Dgrab").css({"margin-top":"40px"});
	$(".nav_end .Dgrab").width($(window).width());

	var DeliverListUrl = "{pigcms{:U('Deliver/my')}";
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
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'配送成功~',btn: ['确定'],end:function(){}});
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

    //garfunkel add
    function onlinePay(e){
        if (mark) {
            return false;
        }
        mark = 1;
        e.stopPropagation();

        var supply_id = $(this).attr("data-id");
        var DetailUrl = "{pigcms{:U('Wap/Deliver/online', array('supply_id'=>'d%'))}";
        location.href = DetailUrl.replace(/d%/, supply_id);
    }

    $(".t_online").bind("click",onlinePay);
	$(".service").bind("click", grab);
	$(".go_detail").bind("click", detail);
});


</script>
<include file="menu"/>
</body>
</html>