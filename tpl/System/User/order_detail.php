<include file="Public:header"/>
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
	<style>
		.frame_form td{line-height:24px;}
	</style>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th width="15%">{pigcms{:L('F_ORDER_ID')}</th>
			<td colspan="3" width="85%">{pigcms{$now_order.order_id}</td>
		</tr>
		<if condition="$now_order.orderid neq 0">
		<tr>
			<th width="15%">订单流水号</th>
			<td colspan="3" width="85%">{pigcms{$now_order.orderid}</td>
		</tr>
		</if>
		
		
		<tr>
			<td colspan="4" style="padding-left:5px;color:black;"><b>{pigcms{:L('_ORDER_INFO_')}</b></td>
		</tr>
		<tr>
			<th width="15%">{pigcms{:L('F_ORDER_DETAILS')}</th>
			<td width="35%">{pigcms{:L('F_TOP_UP')}</td>
			<th width="15%">{pigcms{:L('F_ORDER_STATUS')}</th>
			<td width="35%">
				<if condition="$now_order['paid']">
					<font color="green">{pigcms{:L('F_PAID')}</font>
				<else/>
					<font color="red">未付款</font>
				</if>
				
			</td>
		</tr>
		
		<tr>
			<th width="15%">{pigcms{:L('F_ORDERING_TIME')}</th>
			<td width="35%">{pigcms{$now_order.add_time|date='Y-m-d H:i:s',###}</td>
			<if condition="$now_order['paid']">
				<th width="15%">{pigcms{:L('F_PAYMENT_TIME')}</th>
				<td width="35%">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</td>
			<else/>
				<th width="15%"></th>
				<td width="35%"></td>
			</if>
		</tr>
		
		
		<tr>
			<td colspan="4" style="padding-left:5px;color:black;"><b>{pigcms{:L('F_USER_INFO1')}：</b></td>
		</tr>
		<tr>
			<th width="15%">{pigcms{:L('F_USER_ID')}</th>
			<td width="35%">{pigcms{$now_order.uid}</td>
			<th width="15%">{pigcms{:L('F_USER_NICKNAME')}</th>
			<td width="35%">{pigcms{$now_order.nickname}</td>
		</tr>
		<tr>
			<th width="15%">{pigcms{:L('F_USER_NUMBER1')}</th>
			<td width="35%">{pigcms{$now_order.phone}</td>
	
		</tr>
		
		
		
	</table>
	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
	<script>
		function refund_confirm(){
			layer.confirm('确认后订单状态改为已退款，金额请手动退款给客户！', {
				btn: ['确定','取消'] //按钮
			}, function(){
				window.location.href='{pigcms{:U('Group/refund_update',array('order_id'=>$now_order['order_id']))}';
			});
			//
		}
	</script>
<include file="Public:footer"/