<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Clerk center</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />
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
</style>
</head>
<body>

<div style="padding: 0.2rem;"> 
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<td>Custmer Name：{pigcms{$order.name}</td>
				</tr>
				<tr>
					<td>Custmer Phone Number：<a href="tel:{pigcms{$order.phone}" class="totel">{pigcms{$order.phone}</a></td>
				</tr>
				<tr>
					<td>Order Total： <span class="price">${pigcms{$order['price']}</span></td>
				</tr>
				<tr>
					<td>Order Status：
					  <if condition="$order['status'] eq 3">
						<span class="red">Canceled and refunded</span>
						<elseif condition="empty($order['third_id']) AND ($order['pay_type'] eq 'offline')" />
							<span class="red">Not Paid online</span>
						<elseif condition="$order['paid'] eq 0"/>
							<span class="red">Not Paid</span>
						<else />
							<span class="green">Paid</span>
						</if>
					</td>
				</tr>
				<tr>
					<td>Oder Time： {pigcms{$order.dateline|date="Y-m-d H:i:s",###}</td>
				</tr>
				<tr>
					<td>Custmer Address： {pigcms{$order.address}</td>
				</tr>
				<tr>
					<td>Custmer Commant： {pigcms{$order.note}</td>
				</tr>
				<tr>
				  <td>Payment method： {pigcms{$order.paytypestr}</td>
				</tr>
				<tr>
					<td> 
						<if condition="$order['third_id'] eq '0' AND $order['pay_type'] eq 'offline'">
							Total： ${pigcms{$order['total_price']}<br>
					 		Use Contracter Balance pay ：{pigcms{$order.balance_pay} <br>
					 		Use Store membership card balance pay：{pigcms{$order.merchant_balance}<br>
							Amount should Pay to Merchant：<font color="red">${pigcms{$order['total_price']-$order['merchant_balance']-$order['balance_pay']}元</font>
						<else/>
							Use Contracter Balance pay：{pigcms{$order.balance_pay} <br>
					 		Use Store membership card balance pay：{pigcms{$order.merchant_balance}<br>
					 		Online Payment Amount：{pigcms{$order.payment_money}<br> 
						</if>
					 </td>
				</tr>
			<if condition="!empty($now_order['use_time'])">		
				<tr>
					<td><if condition="$order['tuan_type'] neq 2">consumption<else/>Ship</if>Time： {pigcms{$order.use_time|date='Y-m-d H:i:s',###}</td>
				</tr>
				<tr>
					<td>Operating clerk： {pigcms{$order.last_staff}</td>
				</tr>
			</if>
				<if condition="$order['status'] eq 0">
					<tr id="xfstatus">
						<form enctype="multipart/form-data" method="post" action="">
							<td>Payment Method：
								<span class="red">Not Paid</span>	
								<div><input name="status" value="1" type="hidden">
								<button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">Confirm Payment</button>
								<span class="form_tips" style="color: red">
								注：Into the state of consumption has been the same time if the state is not payment is modified to pay the line has been paid, the state can not be modified after the amendment	
								</span>
								</div>
							</td>
						</form>
					</tr>
				<elseif condition="$order['status'] eq 1 OR $order['status'] eq 2"/>
					<tr>
						<td>Whether it has been paid：<span class="green"> Paid</span></td>
					</tr>
				</if>
			</tbody>
		</table>
	</ul>
	<a href="{pigcms{:U('Storestaff/meal_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">Return</a>
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<th>Dish name</th>
					<th class="cc">unit price</th>
					<th class="cc">Number of item purchased</th>
					<th class="rr">Prise</th>
				</tr>
				<volist name="order['info']" id="info">
				<tr>
					<td>{pigcms{$info['name']}</td>
					<td class="cc">{pigcms{$info['price']}</td>
					<td class="cc">{pigcms{$info['num']}</td>
					<td class="rr">${pigcms{$info['num'] * $info['price']}</td>
				</tr>
				</volist>
				<tr>
					<td>Product Total</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['price']}</td>
				</tr>
				<!-- <tr>
					<td>Delivery Charge</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">$1.00</td>
				</tr> -->
				<tr>
					<td>Total</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['price']}</span></td>
				</tr>
			</tbody>
		</table>
	</ul>
</div>
<div class="footReturn">
	<div class="clr"></div>
	<div class="window" id="windowcenter">
		<div id="title" class="wtitle">Successful<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>

</script>

<!---<include file="Storestaff:footer"/>--->