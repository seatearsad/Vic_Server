<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Store/shop_edit')}" enctype="multipart/form-data">
		<input type="hidden" name="order_id" value="{pigcms{$order.order_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th colspan="1">订单编号</th>
				<th colspan="3">{pigcms{$order['real_orderid']}</th>
			</tr>
			<if condition="$order.orderid neq 0">
			<tr>
				<th colspan="1">订单流水号</th>
				<th colspan="3"><if condition="$order['pay_type'] neq 'baidu'">shop_</if>{pigcms{$order['orderid']}</th>
			</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th><strong>商品名称</strong></th>
				<th><strong>单价</strong></th>
				<th><strong>数量</strong></th>
				<th><strong>规格属性详情</strong></th>
			</tr>
			<volist name="order['info']" id="vo">
			<tr>
				<th style="color:#9F0050">{pigcms{$vo['name']}</th>
				<th style="color:#9F0050">{pigcms{$vo['price']|floatval}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo.extra_price}{pigcms{$config.extra_price_alias_name}</if></th>
				<th style="color:#9F0050"><strong>{pigcms{$vo['num']}</strong> / {pigcms{$vo['unit']}</th>
				<th style="color:#9F0050">{pigcms{$vo['spec']}</th>
			</tr>
			</volist>
			<tr>
				<th colspan="4">备注:<span style="color:red">{pigcms{$order['desc']|default="无"}</span></th>
			</tr>
			<tr >
				<th><strong>总价</strong></th>
				<th>{pigcms{$order['goods_price']|floatval}</th>
				<th colspan="2">{pigcms{$order['num']}</th>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th><strong>支付状态</strong></th>
				<th style="color: green">{pigcms{$order['pay_status']}</th>
				<th><strong>支付方式</strong></th>
				<th>{pigcms{$order['pay_type_str']}</th>
			</tr>
			<tr>
				<th>线下需支付</th>
				<th style="color: red">${pigcms{$order['offline_price']|floatval}元</th>
				<th>发票信息</th>
				<th>{pigcms{$order.invoice_head}</th>
			</tr>
			<tr>
				<th><strong>订单状态</strong></th>
				<if condition="$order['is_pick_in_store'] eq 3">
					<th>
						<select name="express_id"><volist name="express_list" id="vo"><option value="{pigcms{$vo.id}" <if condition="$order['express_id'] eq $vo['id']">selected</if>>{pigcms{$vo.name}</option></volist></select>
					</th>
					<th><input type="text" name="express_number" value="{pigcms{$order['express_number']}" style=" height: 24px;"/></th>
					<th>
<!-- 						<select name="status"> -->
<!-- 						 	<option value="0" <if condition="$order['status'] eq 0">selected</if>>未确认</option> -->
<!-- 						 	<option value="1" <if condition="$order['status'] eq 1">selected</if>>已确认</option> -->
<!-- 						 	<option value="2" <if condition="$order['status'] eq 2">selected</if>>已消费</option> -->
<!-- 						 	<option value="3" <if condition="$order['status'] eq 3">selected</if> disabled>已评价</option> -->
<!-- 						 	<option value="4" <if condition="$order['status'] eq 4">selected</if>>已退款</option> -->
<!-- 						 	<option value="5" <if condition="$order['status'] eq 5">selected</if>>已取消</option> -->
<!-- 						 </select> -->
						 <button type="submit">提交</button>
					</th>
				<elseif condition="$order['status'] gt 1 AND $order['status'] lt 6" />
					<th colspan="3">
					{pigcms{$order['status_str']}
					</th>
				<else />
					<th>
						<select name="status">
						 	<option value="0" <if condition="$order['status'] eq 0">selected</if>>未确认</option>
						 	<option value="1" <if condition="$order['status'] eq 1">selected</if>>已确认</option>
						 	<option value="2" <if condition="$order['status'] eq 2">selected</if> <if condition="$sure">disabled</if>>已消费</option>
						 	<option value="3" <if condition="$order['status'] eq 3">selected</if> disabled>已评价</option>
						 	<option value="4" <if condition="$order['status'] eq 4">selected</if> disabled>已退款</option>
						 	<option value="5" <if condition="$order['status'] eq 5">selected</if>>已取消</option>
						 </select>
					</th>
					<th style="color: red">注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，<br/>状态修改后就不能修改了</th>
					<th><button type="submit">提交</button></th>
				</if>
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>客户信息</strong></th>
			</tr>
			<tr>
				<th>客户姓名：{pigcms{$order['username']}</th>
				<th>客户手机：{pigcms{$order['userphone']}</th>
			</tr>
			<if condition="$order['register_phone']">
			<tr>
				<th colspan="2" style="color:red">客户注册手机：{pigcms{$order['register_phone']}</th>
			</tr>
			</if>
			<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<th colspan="2">自提地址：{pigcms{$order['address']}</th>
				</tr>
			<else />
				<tr>
					<th colspan="2">客户地址：{pigcms{$order['address']}</th>
				</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>配送信息</strong></th>
			</tr>
            <if condition="$deliver">
                <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en"></script>
                <tr>
                    <th colspan="2">
                        <div id="deliver_map" style="width: 100%;height: 300px;"></div>
                    </th>
                </tr>

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
			<tr>
				<th>配送方式：{pigcms{$order['deliver_str']}</th>
				<th>配送状态：{pigcms{$order['deliver_status_str']}</th>
			</tr>
			<if condition="$order['deliver_user_info']">
				<tr>
					<th>配送员姓名：{pigcms{$order['deliver_user_info']['name']}</th>
					<th>配送员电话：{pigcms{$order['deliver_user_info']['phone']}</th>
				</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>时间信息</strong></th>
			</tr>
			<tr>
				<th colspan="4">下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
			</tr>
			<if condition="$order['pay_time']">
				<tr>
					<th colspan="4">支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
				</tr>
			</if>
			<if condition="$order['expect_use_time']">
				<tr>
					<th colspan="4">到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
				</tr>
			</if>
		</table>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
			<tr>
				<th colspan="2"><strong>费用信息</strong></th>
			</tr>
			<tr>
				<th colspan="4">商品总价：${pigcms{$order['goods_price']|floatval} <if condition="$config.open_extra_price eq 1 AND $order.extra_price gt 0">+{pigcms{$order.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></th>
			</tr>
			<if condition="$order['packing_charge'] gt 0">
			<tr>
				<th colspan="4">{pigcms{$store['pack_alias']|default='打包费'}：${pigcms{$order['packing_charge']|floatval} </th>
			</tr>
			</if>
			<if condition="$order['freight_charge'] gt 0">
			<tr>
				<th colspan="4">配送费用：${pigcms{$order['freight_charge']|floatval} </th>
			</tr>
			</if>
			<if condition="$order['is_pick_in_store'] eq 0">
			<tr>
				<th colspan="4">支付平台配送费：{pigcms{$order['no_bill_money']|floatval}</th>
			</tr>
			</if>
            <tr>
                <th colspan="4">税费：${pigcms{$order['tax_price']|floatval} </th>
            </tr>
            <tr>
                <th colspan="4">Bottle Deposit：${pigcms{$order['deposit']|floatval} </th>
            </tr>
			<tr>
				<th colspan="4">订单总价：${pigcms{$order['total_price']|floatval} <if condition="$config.open_extra_price eq 1 AND $order.extra_price gt 0">+{pigcms{$order.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></th>
			</tr>
			<if condition="$order['merchant_reduce'] gt 0">
			<tr>
				<th colspan="4">店铺优惠：${pigcms{$order['merchant_reduce']|floatval} </th>
			</tr>
			</if>
			<if condition="$order['balance_reduce'] gt 0">
			<tr>
				<th colspan="4">平台优惠：${pigcms{$order['balance_reduce']|floatval} </th>
			</tr>
			</if>
			<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
			<tr>
				<th colspan="4">会员卡：{pigcms{$order['card_discount']|floatval} 折优惠</th>
			</tr>
			</if>
			<tr>
				<th colspan="4">实付金额：${pigcms{$order['price']|floatval} 元</th>
			</tr>
			<if condition="$order['score_used_count']">
			<tr>
				<th colspan="4">使用{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </th>
			</tr>
			<tr>
				<th colspan="4">{pigcms{$config.score_name}抵现：${pigcms{$order['score_deducte']|floatval} 元</th>
			</tr>
			</if>
			
			<if condition="$order['card_give_money'] gt 0">
			<tr>
				<th colspan="4">会员卡余额：${pigcms{$order['card_give_money']|floatval} 元</th>
			</tr>
			</if>
			
			<if condition="$order['merchant_balance'] gt 0">
			<tr>
				<th colspan="4">商家余额：${pigcms{$order['merchant_balance']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['balance_pay'] gt 0">
			<tr>
				<th colspan="4">平台余额：${pigcms{$order['balance_pay']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['payment_money'] gt 0">
			<tr>
				<th colspan="4">在线支付：${pigcms{$order['payment_money']|floatval} 元</th>
			</tr>
			</if>
			<if condition="$order['card_id']">
			<tr>
				<th colspan="4">店铺优惠券金额：${pigcms{$order['card_price']} 元</th>
			</tr>
			</if>
			<if condition="$order['coupon_id']">
			<tr>
				<th colspan="4">平台优惠券金额：${pigcms{$order['coupon_price']} 元</th>
			</tr>
			</if>
		</table>
		<if condition="$order['cue_field']">
			<table cellpadding="0" cellspacing="0" class="frame_form" width="100%" style="margin-top:38px;">
				<tr>
					<th colspan="2"><strong>分类填写字段</strong></th>
				</tr>
				<volist name="order['cue_field']" id="vo">
					<tr>
						<th>{pigcms{$vo.title}</th>
						<th>{pigcms{$vo.txt}</th>
					</tr>
				</volist>
			</table>
		</if>
	</form>
<script type="text/javascript">
			$(function(){
				$('#merchant_remark_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/group_remark',array('order_id'=>$now_order['order_id']))}",{merchant_remark:$('#merchant_remark').val()},function(result){
						$('#merchant_remark_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
				$('#store_id_btn').click(function(){
					$(this).html('提交中...').prop('disabled',true);
					$.post("{pigcms{:U('Group/order_store_id',array('order_id'=>$now_order['order_id']))}",{store_id:$('#order_store_id').val()},function(result){
						$('#store_id_btn').html('修改').prop('disabled',false);
						alert(result.info);
					});
				});
			});
		</script>
	</body>
</html>