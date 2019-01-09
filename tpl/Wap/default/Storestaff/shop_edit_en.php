<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Merchant Center</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
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
.submit_btn{
    color: #fff;
    background-color: #FF658E;
    top: .15rem;
    width: 100%;
    height: 3rem;
    text-align: center;
    line-height: 3rem;
    cursor: pointer;
}
</style>
</head>
<body>

<div style="padding: 0.2rem;"> 
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<td>Order Number：{pigcms{$order.real_orderid}</td>
				</tr>
				<if condition="$order.orderid neq 0">
				<tr>
					<td>Order serial number：{pigcms{$order.orderid}</td>
				</tr>
				</if>
				<tr>
					<td>Customer Name：{pigcms{$order.username}</td>
				</tr>
				<tr>
					<td>Customer Phone Number：<a href="tel:{pigcms{$order.userphone}" class="totel">{pigcms{$order.userphone}</a></td>
				</tr>
				<tr>
					<td>Order Time： {pigcms{$order.create_time|date="Y-m-d H:i:s",###}</td>
				</tr>
				<tr>
					<td>Payment Time：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </td>
				</tr>
                <tr>
                    <td>Order prepared by：{pigcms{$supply['dining_time']} mins </td>
                </tr>
                <if condition="$order['expect_use_time']">
				<tr>
					<td>Time of arrival：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				</if>
				<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<td>Pick up address：{pigcms{$order['address']}</td>
				</tr>
				<else />
				<tr>
					<td>Customer Address：{pigcms{$order['address']}</td>
				</tr>
				</if>
				<!--tr>
					<td>Delivery Method：{pigcms{$order['deliver_str']}</td>
				</tr-->
				<tr>
					<td>Delivery Method：{pigcms{$order['deliver_status_str']}</td>
				</tr>
				<tr>
					<td>Customer Commant： <b style="color:red">{pigcms{$order.desc|default='No'}</b</td>
				</tr>
				
				<if condition="$order['invoice_head']">
				<tr>
					<td>invoice:{pigcms{$order['invoice_head']}</td>
				</tr>
				</if>
				<tr>
				  <td>Payment status：{pigcms{$order['pay_status']}</td>
				</tr>
				<tr>
				  <td>Payment Types： {pigcms{$order.pay_type_str}</td>
				</tr>
				<tr>
					<td>Oder Status：{pigcms{$order['status_str']}</td>
				</tr>
				<if condition="$order['score_used_count']">
				<tr>
					<td>Use{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </td>
				</tr>
				<tr>
					<td>{pigcms{$config.score_name}Cash Out：${pigcms{$order['score_deducte']|floatval} </td>
				</tr>
				</if>
				
				<if condition="$order['merchant_balance'] gt 0">
				<tr>
					<td>Merchant Balance：${pigcms{$order['merchant_balance']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['balance_pay'] gt 0">
				<tr>
					<td>Contracter Balance：${pigcms{$order['balance_pay']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['payment_money'] gt 0">
				<tr>
					<td>Online Payment：${pigcms{$order['payment_money']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['card_id']">
				<tr>
					<td>Store coupon amount：${pigcms{$order['card_price']} </td>
				</tr>
				</if>
				<if condition="$order['coupon_id']">
				<tr>
					<td>Contracter coupon amount：${pigcms{$order['coupon_price']} </td>
				</tr>
				</if>
				<if condition="$order['card_give_money'] gt 0">
				<tr>
					<td>Membership card balance：${pigcms{$order['card_give_money']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
				<tr>
					<td>Membership card：{pigcms{$order['card_discount']|floatval} % of Discount</td>
				</tr>
				</if>
				<tr>
					<td>Sub total：${pigcms{$order['offline_price']|floatval}</td>
				</tr>
				<if condition="!empty($order['use_time'])">		
					<tr>
						<td>Operating clerk：<span class="totel">{pigcms{$order.last_staff}</span> </td>
					</tr>
					<tr>
						<td>Operating Time： {pigcms{$order.use_time|date='Y-m-d H:i:s',###}</td>
					</tr>
				</if>
				<if condition="$order['paid'] eq 0">
					<tr id="xfstatus">
						<form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/deliver_goods')}">
							<td>Order Status：<span class="red">Not Paid</span>	
								<div>
									<input name="status" value="5" type="hidden" />
									<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden" />
									<button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">Cancle order</button>
								</div>
							</td>
						</form>
					</tr>
				<elseif condition="$order['is_pick_in_store'] eq 3" />
					<form enctype="multipart/form-data" method="post" action="">
					<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden">
					<input name="status" value="2" type="hidden" />
					<!--tr>
						<td>Order Status：
						<select id="status" class="dropdown_select" >
						 	<option value="0" <if condition="$order['status'] eq 0">selected</if>>Not Confirmed</option>
						 	<option value="1" <if condition="$order['status'] eq 1">selected</if>>Confirm</option>
						 	<option value="2" <if condition="$order['status'] eq 2">selected</if>>Consumed</option>
						 	<option value="3" <if condition="$order['status'] eq 3">selected</if> disabled>Has been evaluated</option>
						 	<option value="4" <if condition="$order['status'] eq 4">selected</if>>Refuned</option>
						 	<option value="5" <if condition="$order['status'] eq 5">selected</if>>Cancled</option>
						</select>
						</td>
					</tr-->
					<tr>
						<td>Delivery Company：<select name="express_id" class="dropdown_select" ><volist name="express_list" id="vo"><option value="{pigcms{$vo.id}" <if condition="$vo['id'] eq $order['express_id']">selected</if>>{pigcms{$vo.name}</option></volist></select></td>
					</tr>
					<tr>
						<td>Tracking Number：<input name="express_number" value="{pigcms{$order['express_number']}" type="text" class="px" placeholder="Fill in Tracking Number"></td>
					</tr>
					<tr>
						<td><button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">Send delivery</button></td>
					</tr>
					</form>
				<elseif condition="$order['status'] eq 7" />
					<tr id="xfstatus">
						<form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/deliver_goods')}">
							<td>Order Status：<span class="red">To be delivered</span>	
								<div>
								<input name="status" value="8" type="hidden" />
								<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden" />
								<button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">Shipped</button>
								</div>
							</td>
						</form>
					</tr>
				<elseif condition="!in_array($order['status'], array(2,3,4,5)) AND $sure"/>
					<tr id="xfstatus">
						<form enctype="multipart/form-data" method="post" action="">
							<td>Order Status：<span class="red">Incomplete</span>
<!--								<div>-->
<!--									<input name="status" value="2" type="hidden">-->
<!--									<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden">-->
<!--									<button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">Confirm consumption</button>-->
<!--									<span class="form_tips" style="color: red">-->
<!--									Note：Into the state of consumption has been the same time if the state is not payment is modified to pay the line has been paid, the state can not be modified after the amendment	-->
<!--									</span>-->
<!--								</div>-->
							</td>
						</form>
					</tr>
				<elseif condition="$order['status'] eq 3 OR $order['status'] eq 2" />
					<tr>
						<td>Order Status：<span class="green"> Has been consumed</span></td>
					</tr>
				<elseif condition="empty($sure)" />
					<tr>
						<td>Order Status：<span class="green">Confirmed</span></td>
					</tr>
				</if>
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
	<a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">back</a>
    <if condition="$deliver">
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en"></script>
        <div style="margin-left: 10px">{pigcms{$deliver.name}({pigcms{$deliver.phone})</div>
        <div id="deliver_map" style="width: 98%;height: 300px;margin-left: 10px;border: 1px #f0efed solid"></div>

        <span class="d_id">{pigcms{$deliver.uid}</span>
        <span class="d_name">{pigcms{$deliver.name}</span>
        <span class="d_lat">{pigcms{$deliver.lat}</span>
        <span class="d_lng">{pigcms{$deliver.lng}</span>
        <span class="d_phone">{pigcms{$deliver.phone}</span>

        <style>
            .d_id,.d_name,.d_lat,.d_lng,.d_phone{
                display: none;
            }
        </style>
        <script type="text/javascript">
            var mapOptions = {
                zoom: 18,
                center: {lat:48.4245911, lng:-123.3667908}
            }

            var map = new google.maps.Map(document.getElementById('deliver_map'), mapOptions);

            var lat = $('.d_lat').text();
            var lng = $('.d_lng').text();

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat,lng),
                map: map,
                label:$('.d_id').text(),
                title: $('.d_name').text() + '('+ $('.d_phone').text()+ ')',
                tag:$('.d_id').text()
            });
            map.setCenter(marker.getPosition());

            var self_position = new google.maps.LatLng('{pigcms{$shop.lat}','{pigcms{$shop.long}');
            var shop_mark = new google.maps.Marker({
                position: self_position,
                map: map,
                icon:"{pigcms{$static_path}images/map/my_pos.png"
            });
        </script>
    </if>
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
            <if condition="$order['info']">
				<tr>
					<th>Name of the product</th>
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
            </if>
                <tr>
                    <th></th>
                    <th class="cc"></th>
                    <th class="cc"></th>
                    <th class="rr">Pricing</th>
                </tr>
				<tr>
					<td>Merchant Total</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['goods_price']|floatval}</td>
				</tr>
                <tr>
                    <td>Tax</td>
                    <td class="cc"></td>
                    <td class="cc"></td>
                    <td class="rr">${pigcms{$order['tax_price']|floatval}</td>
                </tr>
                <if condition="$order['deposit_price'] gt 0">
                    <tr>
                        <td>Bottle Deposit</td>
                        <td class="cc"></td>
                        <td class="cc"></td>
                        <td class="rr">${pigcms{$order['deposit_price']|floatval}</td>
                    </tr>
                </if>
				<if condition="$order['freight_charge'] gt 0">
				<tr>
					<td>Delivery Fee</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['freight_charge']|floatval}</td>
				</tr>
				</if>
				<if condition="$order['packing_charge'] gt 0">
				<tr>
					<td>Packageing Fee</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['packing_charge']|floatval}</td>
				</tr>
				</if>
				<tr>
					<td>Subtotal</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['total_price']|floatval}</span></td>
				</tr>
				<tr>
					<td>Merchant Discount</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['merchant_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>Tutti Coupon</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['balance_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>Total</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['price']|floatval}</span></td>
				</tr>
                <if condition="$order['paid'] eq 1 and $order['status'] eq 0">
                    <tr>
                        <td>Estimated Time</td>
                        <td class="cc">
                            <input type="text" name="dining_time" pattern="^[0-9]*$" data-err="Error" style="height: 2rem">
                        </td>
                        <td class="cc" style="text-align: left;">Minute</td>
                        <td class="rr"></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: center">
                            <div class="submit_btn" id="submit_div">
                                Confirm
                            </div>
                        </td>
                    </tr>
                </if>
			</tbody>
		</table>
	</ul>
</div>
<script>
    var is_send = false;
    $('#submit_div').click(function () {
        if(is_send) return false;
        var time_val = $.trim($('input[name="dining_time"]').val());
        if(time_val == '' || !/^[0-9]*$/.test(time_val)){
            alert('Please enter Meal Time！');
            return false;
        }
        is_send = true;
        $(this).html('Pending……');
        $.post("{pigcms{:U('Storestaff/shop_order_confirm')}",{order_id:"{pigcms{$order['order_id']}",status:1,dining_time:time_val},function(result){
            is_send = false;
            if(result.status == 1){
                window.location.href = "{pigcms{:U('Storestaff/shop_list')}";
            }else{
                window.location.reload();
            }
        });
        return false;
    });
</script>
<div class="footReturn">
	<div class="clr"></div>
	<div class="window" id="windowcenter">
		<div id="title" class="wtitle">sucessful<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>

</script>

<!---<include file="Storestaff:footer"/>--->