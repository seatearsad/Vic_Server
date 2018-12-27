<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th colspan="1">订单编号</th>
		<th colspan="5">{pigcms{$order['real_orderid']}</th>
	</tr>
	<if condition="$order.orderid neq 0">
	<tr>
		<th colspan="1">流水号</th>
		<th colspan="5"><if condition="$order['pay_type'] neq 'baidu'">shop_</if>{pigcms{$order['orderid']}</th>
	</tr>
	</if>
	<if condition="$order['shop_pass']">
	<tr>
		<th colspan="1">消费码</th>
		<th colspan="5">{pigcms{$order['shop_pass']}</th>
	</tr>
	</if>
	<tr>
		<th width="320">商品名称</th>
		<th>单价</th>
		<th>数量</th>
        <th>税率</th>
        <th>押金</th>
		<th>规格属性详情</th>
	</tr>
	<volist name="order['info']" id="vo">
	<tr>
		<th width="180">{pigcms{$vo['name']}</th>
		<th>${pigcms{$vo['price']|floatval}</th>
		<th>{pigcms{$vo['num']} / {pigcms{$vo['unit']}</th>
        <th>{pigcms{$vo['tax_num']}%</th>
        <th>${pigcms{$vo['deposit_price']}</th>
		<th>{pigcms{$vo['spec']}</th>
	</tr>
	</volist>
	<if condition="($order.status eq 0 OR $order.status eq 5) AND $order.paid eq 1">
	<tr>
		<th colspan="6"><a href="javascript:void(0)" onclick="refund_confirm();"><font color="blue">手动退款</font></a></th>
	</tr>
	</if>
	<tr>
		<th colspan="6">客户姓名：{pigcms{$order['username']}</th>
	</tr>
	<tr>
		<th colspan="6">客户手机：{pigcms{$order['userphone']}</th>
	</tr>
	<if condition="$order['register_phone']">
	<tr>
		<th colspan="6" style="color:red">客户注册手机：{pigcms{$order['register_phone']}</th>
	</tr>
	</if>
	<if condition="$order['is_pick_in_store'] eq 2">
	<tr>
		<th colspan="6">自提地址：{pigcms{$order['address']}</th>
	</tr>
	<else />
	<tr>
		<th colspan="6">客户地址：{pigcms{$order['address']}</th>
	</tr>
	</if>
	<tr>
		<th colspan="6">配送方式：{pigcms{$order['deliver_str']}</th>
	</tr>
	<tr>
		<th colspan="6">配送状态：{pigcms{$order['deliver_status_str']}</th>
	</tr>
	<if condition="$order['is_pick_in_store'] eq 3 AND $order['express_id']">
	<tr>
		<th colspan="6">快递公司：{pigcms{$order['express_name']}</th>
	</tr>
	<tr>
		<th colspan="6">快递单号：{pigcms{$order['express_number']}</th>
	</tr>
	</if>
	<if condition="$order['deliver_user_info']">
	<tr>
		<th colspan="6">配送员姓名：{pigcms{$order['deliver_user_info']['name']}</th>
	</tr>
	<tr>
		<th colspan="6">配送员电话：{pigcms{$order['deliver_user_info']['phone']}</th>
	</tr>
	</if>
	<tr>
		<th colspan="6">下单时间：{pigcms{$order['create_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	<if condition="$order['pay_time']">
	<tr>
		<th colspan="6">支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </th>
	</tr>
	</if>
	<if condition="$order['expect_use_time']">
	<tr>
		<th colspan="6">期望到货时间：{pigcms{$order['expect_use_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
	<if condition="$order['use_time']">
	<tr>
		<th colspan="6">送达时间：{pigcms{$order['use_time']|date="Y-m-d H:i:s",###}</th>
	</tr>
	</if>
	<tr>
		<th colspan="6">商品总价：${pigcms{$order['goods_price']|floatval} <if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if></th>
	</tr>
	<if condition="$order['packing_charge'] gt 0">
	<tr>
		<th colspan="6">{pigcms{$store['pack_alias']|default='打包费'}：${pigcms{$order['packing_charge']|floatval} </th>
	</tr>
	</if>
	<if condition="$order['freight_charge'] gt 0">
	<tr>
		<th colspan="6">{pigcms{$store['freight_alias']|default='配送费用'}：${pigcms{$order['freight_charge']|floatval} </th>
	</tr>
	</if>
    <tr>
        <th colspan="6">税费：${pigcms{$order['tax_price']|floatval}</th>
    </tr>
    <tr>
        <th colspan="6">押金：${pigcms{$order['deposit_price']|floatval} </th>
    </tr>
	<tr>
		<th colspan="6">订单总价：${pigcms{$order['total_price']|floatval} <if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if></th>
	</tr>
	<if condition="$order['merchant_reduce'] gt 0">
	<tr>
		<th colspan="6">店铺优惠：${pigcms{$order['merchant_reduce']|floatval} </th>
	</tr>
	</if>
	<if condition="$order['balance_reduce'] gt 0">
	<tr>
		<th colspan="6">平台优惠：${pigcms{$order['balance_reduce']|floatval} </th>
	</tr>
	</if>
	<if condition="$order['card_discount'] neq 0 AND $order['card_discount'] neq 10">
	<tr>
		<th colspan="6">会员卡：{pigcms{$order['card_discount']|floatval} 折优惠</th>
	</tr>
	</if>
	<tr>
		<th colspan="6">实付金额：${pigcms{$order['price']|floatval} 元</th>
	</tr>
	<if condition="$order['score_used_count']">
	<tr>
		<th colspan="6">使用{pigcms{$config.score_name}：{pigcms{$order['score_used_count']} </th>
	</tr>
	<tr>
		<th colspan="6">{pigcms{$config.score_name}抵现：${pigcms{$order['score_deducte']|floatval} 元</th>
	</tr>
	</if>
			
	<if condition="$order['card_give_money'] gt 0">
	<tr>
		<th colspan="6">会员卡余额：${pigcms{$order['card_give_money']|floatval} 元</th>
	</tr>
	</if>
	
	<if condition="$order['merchant_balance'] gt 0">
	<tr>
		<th colspan="6">商家余额：${pigcms{$order['merchant_balance']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['balance_pay'] gt 0">
	<tr>
		<th colspan="6">平台余额：${pigcms{$order['balance_pay']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['payment_money'] gt 0">
	<tr>
		<th colspan="6">在线支付：${pigcms{$order['payment_money']|floatval} 元</th>
	</tr>
	</if>
	<if condition="$order['card_id']">
	<tr>
		<th colspan="6">店铺优惠券金额：${pigcms{$order['card_price']} 元</th>
	</tr>
	</if>
	<if condition="$order['coupon_id']">
	<tr>
		<th colspan="6">平台优惠券金额：${pigcms{$order['coupon_price']} 元</th>
	</tr>
	</if>
	<if condition="$order['pay_type'] eq 'offline' AND empty($order['third_id'])">
	<tr>
		<th colspan="6">线下需支付：${pigcms{$order['price']-$order['card_price']-$order['merchant_balance']-$order['balance_pay']-$order['payment_money']-$order['score_deducte']-$order['coupon_price']|floatval}元</th>
	</tr>
	</if>
	<tr>
		<th colspan="6">支付状态：{pigcms{$order['pay_status']}</th>
	</tr>
	<tr>
		<th colspan="6">支付方式：{pigcms{$order['pay_type_str']}</th>
	</tr>
	<tr>
		<th colspan="6">订单状态：{pigcms{$order['status_str']}<if condition="$order['status'] eq 4">&nbsp;&nbsp;&nbsp;&nbsp;退款时间:{pigcms{$order['last_time']|date="Y-m-d H:i:s",###}</if></th>
	</tr>
	
	<tr>
		<th colspan="6">备注:{pigcms{$order['desc']|default="无"}</th>
	</tr>
	<if condition="$order['invoice_head']">
		<tr>
			<th colspan="6">发票抬头:{pigcms{$order['invoice_head']}</th>
		</tr>
	</if>
	<if condition="$order['cue_field']">
		<tr>
			<th colspan="6">&nbsp;</th>
		</tr>
		<tr>
			<th colspan="6"><strong>分类填写字段</strong></th>
		</tr>
		<volist name="order['cue_field']" id="vo">
			<tr>
				<th colspan="1">{pigcms{$vo.title}</th>
				<th colspan="5">{pigcms{$vo.txt}</th>
			</tr>
		</volist>
	</if>
</table>

<script>
	function refund_confirm(){
		layer.confirm('确认后订单状态改为已退款，金额请通过其他渠道手动退款给客户！', {
			btn: ['确定','取消'] //按钮
		}, function(){
			window.location.href='{pigcms{:U('Shop/refund_update',array('order_id'=>$order['order_id']))}';
		});
		//
	}
</script>
<include file="Public:footer"/>