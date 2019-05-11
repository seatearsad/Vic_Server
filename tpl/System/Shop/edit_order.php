<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<form id="myform" method="post" action="{pigcms{:U('Shop/save_edit_order')}" frame="true" refresh="true">
<input type="hidden" name="order_id" value="{pigcms{$order.order_id}"/>
<input type="hidden" name="store_tax" id="store_tax" value="{pigcms{$store.tax_num}">
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
	<tr>
		<th colspan="1">订单编号</th>
		<th colspan="5">{pigcms{$order['real_orderid']}</th>
	</tr>
	<tr>
		<th width="320">商品名称</th>
		<th>单价</th>
		<th>数量</th>
        <th>税率</th>
        <th>押金</th>
		<th>规格属性详情</th>
	</tr>
	<volist name="order['info']" id="vo">
	<tr id="good_list">
		<th width="180">{pigcms{$vo['name']}</th>
		<th>${pigcms{$vo['price']|floatval}</th>
		<th>
            <input type="text" name="good_{pigcms{$vo['goods_id']}" data-id="{pigcms{$vo['tax_num']}" data-name="{pigcms{$vo['deposit_price']}" data-for="{pigcms{$vo['price']|floatval}" pattern="[0-9]*" size="3" value="{pigcms{$vo['num']}">/{pigcms{$vo['unit']}
        </th>
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
	<tr>
        <th colspan="6">商品总价：$<span id="good_price">{pigcms{$order['goods_price']|floatval}</span>
            <if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">
                +{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}
            </if>
        </th>
	</tr>
	<if condition="$order['packing_charge'] gt 0">
	<tr>
		<th colspan="6">
            {pigcms{$store['pack_alias']|default='打包费'}：
            $<span id="packing_charge">{pigcms{$order['packing_charge']|floatval}</span>
        </th>
	</tr>
    <else />
        <span id="packing_charge" style="display: none">0</span>
	</if>
	<if condition="$order['freight_charge'] gt 0">
	<tr>
		<th colspan="6">{pigcms{$store['freight_alias']|default='配送费用'}：
            $<input type="text" name="freight_charge" id="freight_charge" size="5" value="{pigcms{$order['freight_charge']|floatval}">
        </th>
	</tr>
	</if>
    <tr>
        <th colspan="6">税费：$<span id="tax_price">{pigcms{$order['tax_price']|floatval}</span></th>
    </tr>
    <tr>
        <th colspan="6">押金：$<span id="deposit_price">{pigcms{$order['deposit_price']|floatval}</span></th>
    </tr>
	<tr>
		<th colspan="6">订单总价：
            $<span id="total_price">{pigcms{$order['price']|floatval}</span>
            <if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">+{pigcms{$now_order.extra_price}{pigcms{$config.extra_price_alias_name}</if>
        </th>
	</tr>
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
</table>
    <input type="hidden" name="goods_price" value="{pigcms{$order['goods_price']|floatval}">
    <input type="hidden" name="price" value="{pigcms{$order['price']|floatval}">
    <input type="hidden" name="total_price" value="{pigcms{$order['price']|floatval}">
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
        <input type="reset" value="取消" class="button" />
    </div>
</form>
<script>
    var good_num = parseFloat('{pigcms{$order.num}');
    $(function () {
        $('table').find('#good_list').each(function () {
            //alert($(this).find('input').data('for'));
            //var input = $(this).find('input');
            $(this).find('input').bind('input porpertychange',changeGoodNum);
            $(this).find('input').live('focusout',checkNum);
        });

        $('#freight_charge').bind('input porpertychange',changeGoodNum);
        $('#freight_charge').live('focusout',checkNum);
    });

    function checkNum() {
        if($(this).val() == ''){
            $(this).val(0);
            updatePrice();
        }
    }

    function changeGoodNum() {
        if($(this).val() != ''){
            updatePrice();
        }
    }

    function getOtherTax() {
        var tax = (parseFloat($('#packing_charge').html()) + parseFloat($('#freight_charge').val())) * $('#store_tax').val()/100;
        return tax;
    }
    
    function getTotalPrice(good_price,tax_price,deposit_price) {
        var total = good_price+tax_price+deposit_price;
        total += parseFloat($('#packing_charge').html()) + parseFloat($('#freight_charge').val());
        return total;
    }

    function updatePrice() {
        var good_price = 0;
        var tax_price = 0;
        var deposit_price = 0;
        $('table').find('#good_list').each(function () {
            good_price += $(this).find('input').val() * $(this).find('input').data('for');
            tax_price += $(this).find('input').val() * $(this).find('input').data('for')*$(this).find('input').data('id')/100;
            deposit_price += $(this).find('input').val()*$(this).find('input').data('name');
        });

        if(good_num == 0){
            deposit_price = parseFloat('{pigcms{$order.deposit_price}');
            tax_price = parseFloat('{pigcms{$order.good_tax_price}');
            good_price = parseFloat('{pigcms{$order.goods_price}');
        }

        tax_price = tax_price + getOtherTax();
        if(good_price == 0){
            alert('请输入商品数量');
        }

        var total_prcie = getTotalPrice(good_price,tax_price,deposit_price);
        $('#good_price').html(good_price.toFixed(2));
        $('#tax_price').html(tax_price.toFixed(2));
        $('#deposit_price').html(deposit_price.toFixed(2));
        $('#total_price').html(total_prcie.toFixed(2));

        $('input[name="goods_price"]').val(good_price.toFixed(2));
        $('input[name="price"]').val(total_prcie.toFixed(2));
        $('input[name="total_price"]').val(total_prcie.toFixed(2));
    }

</script>

<include file="Public:footer"/>