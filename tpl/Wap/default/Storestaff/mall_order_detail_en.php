<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Clerk center</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/datePicker.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll_min.css" media="all">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min1.8.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll_min.js"></script>
<style>
.green{color:green;}
.btn{
margin: 0;
text-align: center;
height: 2.2rem;
line-height: 2.2rem;
padding: 0 .32rem;
border-radius: .3rem;
color: #fff;
border: 0;
background-color: #FF658E;
font-size: .28rem;
vertical-align: middle;
box-sizing: border-box;
cursor: pointer;
-webkit-user-select: none;}
.totel{color: green;}
.cpbiaoge td{font-size:1rem;}
.dropdown_select {
    -webkit-appearance: button;
    -webkit-user-select: none;
    font-size: 13px;
    overflow: visible;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #999999;
    display: inline;
    position: relative;
    margin: 0px 1px 0px 1px;
    font-size: 16px;
    height: auto;
    padding: 10px;
    outline: none;
    border: 0;
    background-color: transparent;
}
.px {
    position: relative;
    background-color: transparent;
    color: #999999;
    padding: 10px;
    font-size: 16px;
    margin: 0 auto;
    font-family: Arial, Helvetica, sans-serif;
    border: 0;
    -webkit-appearance: none;
}
</style>
</head>
<body>

<div style="padding: 0.2rem;"> 
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<td>Ctstmer Name：{pigcms{$order.username}</td>
				</tr>
				<tr>
					<td>Custmer Phone Number：<a href="tel:{pigcms{$order.userphone}" class="totel">{pigcms{$order.userphone}</a></td>
				</tr>
				<tr>
					<td>Order number：{pigcms{$order.real_orderid}</td>
				</tr>
				<if condition="$order.orderid neq 0">
				<tr>
					<td>Order serial number：{pigcms{$order.orderid}</td>
				</tr>
				</if>
				<tr>
					<td>Order Time： {pigcms{$order.create_time|date="Y-m-d H:i:s",###}</td>
				</tr>
				<tr>
					<td>Time Paid：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </td>
				</tr>
				<if condition="$order['expect_use_time']">
				<tr>
					<td>Arrival time：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				</if>
				<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<td>Pick up time：{pigcms{$order['address']}</td>
				</tr>
				<else />
				<tr>
					<td>Custmer Address：{pigcms{$order['address']}</td>
				</tr>
				</if>
				<tr>
					<td>Delivery method：{pigcms{$order['deliver_str']}</td>
				</tr>
				<tr>
					<td>Delivery status：{pigcms{$order['deliver_status_str']}</td>
				</tr>
				<tr>
					<td>Custmer Commant： {pigcms{$order.desc|default='无'}</td>
				</tr>
				
				<if condition="$order['invoice_head']">
				<tr>
					<td>Invoice:{pigcms{$order['invoice_head']}</td>
				</tr>
				</if>
				<tr>
				  <td>Payment status：{pigcms{$order['pay_status']}</td>
				</tr>
				<tr>
				  <td>payment method： {pigcms{$order.pay_type_str}</td>
				</tr>
				<tr>
					<td>Order Status：{pigcms{$order['status_str']}</td>
				</tr>
				<if condition="$order['score_used_count']">
				<tr>
					<td>Use{pigcms{$config['score_name']}：{pigcms{$order['score_used_count']} </td>
				</tr>
				<tr>
					<td>{pigcms{$config['score_name']}Cash out：${pigcms{$order['score_deducte']|floatval} Dollars</td>
				</tr>
				</if>
				
				<if condition="$order['merchant_balance'] gt 0">
				<tr>
					<td>Merchant balance：${pigcms{$order['merchant_balance']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['balance_pay'] gt 0">
				<tr>
					<td>Contracter Balance：${pigcms{$order['balance_pay']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['payment_money'] gt 0">
				<tr>
					<td>Online Payment：${pigcms{$order['payment_money']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['card_id']">
				<tr>
					<td>Store coupon amount：${pigcms{$order['card_price']} 元</td>
				</tr>
				</if>
				<if condition="$order['coupon_id']">
				<tr>
					<td>Contracter coupon amount：${pigcms{$order['coupon_price']} 元</td>
				</tr>
				</if>
				<if condition="$order['card_give_money'] gt 0">
				<tr>
					<td>Membership card balance：${pigcms{$order['card_give_money']|floatval} 元</td>
				</tr>
				</if>
				<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
				<tr>
					<td>Membership card：{pigcms{$order['card_discount']|floatval} 折优惠</td>
				</tr>
				</if>
				<tr>
					<td>Receivable Amount：${pigcms{$order['offline_price']|floatval}元</td>
				</tr>
				<if condition="!empty($order['use_time'])">		
					<tr>
						<td>Operating clerk：<span class="totel">{pigcms{$order.last_staff}</span> </td>
					</tr>
					<tr>
						<td>Operat time： {pigcms{$order.use_time|date='Y-m-d H:i:s',###}</td>
					</tr>
				</if>
				<form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/check_deliver')}">
				<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden">
				<tr>
					<th ><strong>Change the Delivery Method</strong> <b style="color:red">（The part of delivery charge that changed is store's responsbilty）</b></th>
				</tr>
				<tr>
					<td>Delivery fee of the Quick delivery:<b style="color: red">${pigcms{$order['freight_charge']|floatval}</b></td>
				</tr>
				<tr>
					<td>Delivery method changed to:
					<if condition="$store['deliver_type'] eq 0 OR $store['deliver_type'] eq 3">
					<b style="color: red">Contrater delivery</b>
					<else />
					<b style="color: red">Store Delivery</b>
					</if>
					</td>
				</tr>
				<tr>
					<td>Delivery Distant:<b style="color: red">{pigcms{$distance|floatval}km</b></td>
				</tr>
				<tr>
					<td>【{pigcms{$time_select_1}】's delivery charge:<b style="color: red">${pigcms{$delivery_fee|floatval}</b></td>
				</tr>
				<if condition="$have_two_time">
				<tr>
					<td>【{pigcms{$time_select_2}】's delivery charge:<b style="color: red">${pigcms{$delivery_fee2|floatval}</b></td>
				</tr>
				</if>
				<if condition="$order['status'] eq 0">
				<tr>
					<td>Estimated delivery time:<input type="text" name="expect_use_time" value="{pigcms{$arrive_datetime}" id="expect_use_time" style="height: 24px;" readonly/></td>
				</tr>
				<tr>
					<td >
						 <button type="submit" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">Change Delivery method</button>
					</td>
				</tr>
				<else />
				<tr>
					<td>Estimated delivery time:{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				</if>
				</form>
			</tbody>
		</table>
		<if condition="$order['cue_field']">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
			<tr>
				<th colspan="2"><strong>Fill in the fields</strong></th>
			</tr>
			<volist name="order['cue_field']" id="vo">
				<tr>
					<td>{pigcms{$vo.title}</td>
					<td>{pigcms{$vo.txt}</td>
				</tr>
			</volist>
			</tbody>
		</table>
		</if>
	</ul>
	<a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">返 回</a>
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<th>Product's name</th>
					<th class="cc">unit price</th>
					<th class="cc">Quantity</th>
					<th class="rr">Specification attribute</th>
				</tr>
				<volist name="order['info']" id="info">
				<tr>
					<td style="color: blue">{pigcms{$info['name']} </td>
					<td class="cc">{pigcms{$info['price']|floatval}</td>
					<td class="cc" style="color: blue">{pigcms{$info['num']} <span style="color: gray; font-size:10px">({pigcms{$info['unit']})</span></td>
					<td class="rr">{pigcms{$info['spec']}</td>
				</tr>
				</volist>
				<tr>
					<td>Product Total</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['goods_price']|floatval}</td>
				</tr>
				<if condition="$order['freight_charge'] gt 0">
				<tr>
					<td>{pigcms{$store['freight_alias']|default='Delivery Charge'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['freight_charge']|floatval}</td>
				</tr>
				</if>
				<if condition="$order['packing_charge'] gt 0">
				<tr>
					<td>{pigcms{$store['pack_alias']|default='Packageing Fee'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['packing_charge']|floatval}</td>
				</tr>
				</if>
				<tr>
					<td>Total</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['total_price']|floatval}</span></td>
				</tr>
				<tr>
					<td>Store's discount</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['merchant_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>Contracter's discount</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['balance_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>Total After discount</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['price']|floatval}</span></td>
				</tr>
			</tbody>
		</table>
	</ul>
</div>
<div class="footReturn">
	<div class="clr"></div>
	<div class="window" id="windowcenter">
		<div id="title" class="wtitle">Sucessful<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function () {
	var opt = {};
	opt.date = {preset:'datetime'};
	opt.default = {
		theme: 'android-ics light', //UI type
		display: 'bottom', //Display method
		mode: 'scroller', //Date selection mode
		lang:'zh',
		minWidth: 64,
		setText: 'Confirm', //Confirm button's name
		cancelText: 'Cancel',//Cancel Button
		dateFormat: 'yy-mm-dd'
	};
	$("#expect_use_time").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
});
</script>