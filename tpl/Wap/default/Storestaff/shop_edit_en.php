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
<div style="padding: 0.8rem;background-color: #ffa52d">
    <a href="javascript:void(0);" onclick="return window.history.go(-1);" style="color: white"> < Back</a>
    <div id="print_order" style="position: absolute;right: 20px;top:12px;color: white;cursor: pointer;">
        <if condition="$order['paid'] eq 1 and $order['status'] neq 0">
            Print Order
        </if>
    </div>
</div>
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
				<!--tr>
					<td>Order Time： {pigcms{$order.create_time|date="Y-m-d H:i:s",###}</td>
				</tr-->
				<tr>
					<td>Payment Time：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </td>
				</tr>
                <tr>
                    <td>Food Preparation Time：{pigcms{$supply['dining_time']} mins </td>
                </tr>
                <if condition="$order['expect_use_time']">
				<tr>
					<!--td>Scheduled Delivery：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td-->
                    <td>
                        Scheduled Delivery：
                        <?php if (($order['expect_use_time'] - $order['pay_time'])>=3600){?>
                            {pigcms{$order['expect_use_time']|date="Y-m-d H:i",###}
                        <?php }else{ ?>
                            ASAP
                        <?php } ?>
                    </td>
				</tr>
				</if>
				<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<td>Pick up address：{pigcms{$order['address']}</td>
				</tr>
				<else />
				<!--tr>
					<td>Customer Address：{pigcms{$order['address']}</td>
				</tr-->
				</if>
				<!--tr>
					<td>Delivery Method：{pigcms{$order['deliver_str']}</td>
				</tr>
				<tr>
					<td>Delivery Method：{pigcms{$order['deliver_status_str']}</td>
				</tr-->
				<tr>
					<td>Customer Comment： <if condition="$order['desc'] eq ''">N/A<else /><b style="color:#ffa52d">{pigcms{$order.desc}</b></if></td>
				</tr>
				
				<if condition="$order['invoice_head']">
				<!--tr>
					<td>invoice:{pigcms{$order['invoice_head']}</td>
				</tr-->
				</if>
				<!--tr>
				  <td>Payment status：{pigcms{$order['pay_status']}</td>
				</tr>
				<tr>
				  <td>Payment Types： {pigcms{$order.pay_type_str}</td>
				</tr-->
				<tr>
					<td>Order Status：{pigcms{$order['status_str']}</td>
				</tr>
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
	<!--a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="background-color:#ffa64d;float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">back</a-->
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
					<th>Name</th>
					<th class="cc">Rate</th>
					<th class="cc">Qty</th>
					<th class="rr">Options</th>
				</tr>
				<volist name="order['info']" id="info">
				<tr>
					<td>{pigcms{$info['name']} </td>
					<td class="cc">{pigcms{$info['price']|floatval}</td>
                    <td class="cc" <if condition="$info['num'] gt 1">style="color: #ffa52d"</if>>{pigcms{$info['num']} <span style="color: gray; font-size:10px">({pigcms{$info['unit']})</span></td>
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
                    <td class="rr">${pigcms{:round($order['tax_price'],2)}</td>
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
                <if condition="$order['tip_charge'] gt 0">
                    <!--tr>
                        <td>Tip</td>
                        <td class="cc"></td>
                        <td class="cc"></td>
                        <td class="rr">${pigcms{$order['tip_charge']|floatval}</td>
                    </tr-->
                </if>
				<!--tr>
					<td>Merchant Discount</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['merchant_reduce']|floatval}</span></td>
				</!--tr>
				<tr>
					<td>Tutti Coupon</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['balance_reduce']|floatval}</span></td>
				</tr-->
				<tr>
					<td>Total</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price" style="color: #ffa52d">${pigcms{$order['price']+$order['tip_charge']|floatval}</span></td>
				</tr>
                <if condition="$order['paid'] eq 1 and $order['status'] eq 0">
                    <tr>
                        <td>Food Preparation</td>
                        <td class="cc">
                            <input type="text" name="dining_time" pattern="^[0-9]*$" data-err="Error" style="height: 2rem" value="20">
                        </td>
                        <td class="cc" style="text-align: left;">Minute</td>
                        <td class="rr"></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: center">
                            <div class="submit_btn" style="background-color: #ffa64d" id="submit_div">
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
                alert(result.info);
                window.location.reload();
            }
        },'json');
        return false;
    });

    if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())){
        $('#print_order').show();
    }else{
        $('#print_order').hide();
    }

    $('#print_order').click(function () {
        if(typeof (window.linkJs) != 'undefined'){
            window.linkJs.printer_order('{pigcms{:json_encode($order)}');
        }
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

<!--include file="Storestaff:footer" -->