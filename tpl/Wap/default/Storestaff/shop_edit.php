<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>店员中心</title>
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
					<td>订单编号：{pigcms{$order.real_orderid}</td>
				</tr>
				<if condition="$order.orderid neq 0">
				<tr>
					<td>订单流水号：{pigcms{$order.orderid}</td>
				</tr>
				</if>
				<tr>
					<td>客户姓名：{pigcms{$order.username}</td>
				</tr>
				<tr>
					<td>客户电话：<a href="tel:{pigcms{$order.userphone}" class="totel">{pigcms{$order.userphone}</a></td>
				</tr>
				<tr>
					<td>下单时间： {pigcms{$order.create_time|date="Y-m-d H:i:s",###}</td>
				</tr>
				<tr>
					<td>支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </td>
				</tr>
                <tr>
                    <td>出餐时间：{pigcms{$supply['dining_time']}分钟 </td>
                </tr>
				<if condition="$order['expect_use_time']">
				<tr>
					<td style="font-weight: 800;color:red; ">到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</td>
				</tr>
				</if>
				<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<td>自提地址：{pigcms{$order['address']}</td>
				</tr>
				<else />
				<tr>
					<td>客户地址：{pigcms{$order['address']}</td>
				</tr>
				</if>
				<tr>
					<td>配送方式：{pigcms{$order['deliver_str']}</td>
				</tr>
				<tr>
					<td>配送状态：{pigcms{$order['deliver_status_str']}</td>
				</tr>
				<tr>
					<td>客户留言： <b style="color:red">{pigcms{$order.desc|default='无'}</b></td>
				</tr>
				
				<if condition="$order['invoice_head']">
				<tr>
					<td>发票抬头:{pigcms{$order['invoice_head']}</td>
				</tr>
				</if>
				<tr>
				  <td>支付状态：{pigcms{$order['pay_status']}</td>
				</tr>
				<tr>
				  <td>支付方式： {pigcms{$order.pay_type_str}</td>
				</tr>
				<tr>
					<td>订单状态：{pigcms{$order['status_str']}</td>
				</tr>
				<if condition="$order['score_used_count']">
				<tr>
					<td>使用{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </td>
				</tr>
				<tr>
					<td>{pigcms{$config.score_name}抵现：${pigcms{$order['score_deducte']|floatval}</td>
				</tr>
				</if>
				
				<if condition="$order['merchant_balance'] gt 0">
				<tr>
					<td>商家余额：${pigcms{$order['merchant_balance']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['balance_pay'] gt 0">
				<tr>
					<td>平台余额：${pigcms{$order['balance_pay']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['payment_money'] gt 0">
				<tr>
					<td>在线支付：${pigcms{$order['payment_money']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['card_id']">
				<tr>
					<td>店铺优惠券金额：${pigcms{$order['card_price']} </td>
				</tr>
				</if>
				<if condition="$order['coupon_id']">
				<tr>
					<td>平台优惠券金额：${pigcms{$order['coupon_price']} </td>
				</tr>
				</if>
				<if condition="$order['card_give_money'] gt 0">
				<tr>
					<td>会员卡余额：${pigcms{$order['card_give_money']|floatval} </td>
				</tr>
				</if>
				<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
				<tr>
					<td>会员卡：{pigcms{$order['card_discount']|floatval} 折优惠</td>
				</tr>
				</if>
				<tr>
					<td>应收现金：${pigcms{$order['offline_price']|floatval}</td>
				</tr>
				<if condition="!empty($order['use_time'])">		
					<tr>
						<td>操作店员：<span class="totel">{pigcms{$order.last_staff}</span> </td>
					</tr>
					<tr>
						<td>操作时间： {pigcms{$order.use_time|date='Y-m-d H:i:s',###}</td>
					</tr>
				</if>
				<if condition="$order['paid'] eq 0">
					<tr id="xfstatus">
						<form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/deliver_goods')}">
							<td>订单状态：<span class="red">未支付</span>	
								<div>
									<input name="status" value="5" type="hidden" />
									<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden" />
									<button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">取消订单</button>
								</div>
							</td>
						</form>
					</tr>
				<elseif condition="$order['is_pick_in_store'] eq 3" />
					<form enctype="multipart/form-data" method="post" action="">
					<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden">
					<input name="status" value="2" type="hidden" />
					<!--tr>
						<td>订单状态：
						<select id="status" class="dropdown_select" >
						 	<option value="0" <if condition="$order['status'] eq 0">selected</if>>未确认</option>
						 	<option value="1" <if condition="$order['status'] eq 1">selected</if>>已确认</option>
						 	<option value="2" <if condition="$order['status'] eq 2">selected</if>>已消费</option>
						 	<option value="3" <if condition="$order['status'] eq 3">selected</if> disabled>已评价</option>
						 	<option value="4" <if condition="$order['status'] eq 4">selected</if>>已退款</option>
						 	<option value="5" <if condition="$order['status'] eq 5">selected</if>>已取消</option>
						</select>
						</td>
					</tr-->
					<tr>
						<td>快递公司：<select name="express_id" class="dropdown_select" ><volist name="express_list" id="vo"><option value="{pigcms{$vo.id}" <if condition="$vo['id'] eq $order['express_id']">selected</if>>{pigcms{$vo.name}</option></volist></select></td>
					</tr>
					<tr>
						<td>快递单号：<input name="express_number" value="{pigcms{$order['express_number']}" type="text" class="px" placeholder="填写快递单号"></td>
					</tr>
					<tr>
						<td><button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">发送快递</button></td>
					</tr>
					</form>
				<elseif condition="$order['status'] eq 7" />
					<tr id="xfstatus">
						<form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/deliver_goods')}">
							<td>订单状态：<span class="red">待发货</span>	
								<div>
								<input name="status" value="8" type="hidden" />
								<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden" />
								<button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">发货</button>
								</div>
							</td>
						</form>
					</tr>
				<elseif condition="!in_array($order['status'], array(2,3,4,5)) AND $sure"/>
					<tr id="xfstatus">
						<form enctype="multipart/form-data" method="post" action="">
							<td>订单状态：<span class="red">未消费</span>	
<!--								<div>-->
<!--									<input name="status" value="2" type="hidden">-->
<!--									<input name="order_id" value="{pigcms{$order['order_id']}" type="hidden">-->
<!--									<button id="merchant_remark_btn" class="submit" style="padding: 5px;margin: 12px auto;margin-top: 25px;background-color:#FF658E;border:1px solid #FF658E">确认消费</button>-->
<!--									<span class="form_tips" style="color: red">-->
<!--									注：改成已消费状态后同时如果是未付款状态则修改成线下支付已支付，状态修改后就不能修改了	-->
<!--									</span>-->
<!--								</div>-->
							</td>
						</form>
					</tr>
				<elseif condition="$order['status'] eq 3 OR $order['status'] eq 2" />
					<tr>
						<td>订单状态：<span class="green"> 已消费</span></td>
					</tr>
				<elseif condition="empty($sure)" />
					<tr>
						<td>订单状态：<span class="green"> 已接单</span></td>
					</tr>
				</if>
			</tbody>
		</table>
		<if condition="$order['cue_field']">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
			<tr>
				<th colspan="2"><strong>分类填写字段</strong></th>
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
					<th>商品名称</th>
					<th class="cc">单价</th>
					<th class="cc">数量</th>
					<th class="rr">规格属性</th>
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
                    <th class="rr">价格</th>
                </tr>
				<tr>
					<td>商品总价</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['goods_price']|floatval}</td>
				</tr>
                <tr>
                    <td>税费</td>
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
					<td>{pigcms{$store['freight_alias']|default='配送费'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['freight_charge']|floatval}</td>
				</tr>
				</if>
				<if condition="$order['packing_charge'] gt 0">
				<tr>
					<td>{pigcms{$store['pack_alias']|default='打包费'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['packing_charge']|floatval}</td>
				</tr>
				</if>
                <if condition="$order['tip_charge'] gt 0">
                    <tr>
                        <td>小费</td>
                        <td class="cc"></td>
                        <td class="cc"></td>
                        <td class="rr">${pigcms{$order['tip_charge']|floatval}</td>
                    </tr>
                </if>
				<tr>
					<td>商家优惠</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['merchant_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>平台优惠</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">-${pigcms{$order['coupon_price']|floatval}</span></td>
				</tr>
				<tr>
					<td>总额</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['price']+$order['tip_charge']|floatval}</span></td>
				</tr>
                <if condition="$order['paid'] eq 1 and $order['status'] eq 0">
                <tr>
                    <td>出餐时间</td>
                    <td class="cc">
                        <input type="text" name="dining_time" pattern="^[0-9]*$" data-err="Error" style="height: 2rem" value="20">
                    </td>
                    <td class="cc" style="text-align: left;">分钟</td>
                    <td class="rr"></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">
                        <div class="submit_btn" id="submit_div">
                            接单
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
            alert('请填写出餐时间');
            return false;
        }
        is_send = true;
        $(this).html('处理中……');
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
		<div id="title" class="wtitle">操作成功<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>



<!---<include file="Storestaff:footer"/>--->