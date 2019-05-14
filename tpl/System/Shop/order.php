<include file="Public:header"/>
<style>
    .table-list thead th{
        line-height: 20px;
    }
</style>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('Shop/order')}" class="on">{pigcms{:L('_BACK_ORDER_LIST_')}</a>
			</ul>
		</div>
		<table class="search_table" width="100%">
			<tr>
				<td>
				<form action="{pigcms{:U('Shop/order')}" method="get">
						<input type="hidden" name="c" value="Shop"/>
						<input type="hidden" name="a" value="order"/>
						
						{pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
						<select name="searchtype">
							<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>{pigcms{:L('_BACK_ORDER_NUM_')}</option>
							<!--option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
							<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option-->
							<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{:L('_BACK_STORE_NAME_')}</option>
							<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('_BACK_USER_NAME_')}</option>
							<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_USER_PHONE_')}</option>
						</select>
						<font color="#000">{pigcms{:L('_BACK_DATE_SELECT_')}：</font>
						<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
						<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
						{pigcms{:L('_BACK_ORDER_STATUS_')}:
						<select id="status" name="status">
							<volist name="status_list" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
							</volist>
						</select>
						{pigcms{:L('_BACK_PAYMENT_METHOD_')}:
						<select id="pay_type" name="pay_type">
								<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>{pigcms{:L('_BACK_ALL_')}</option>
							<volist name="pay_method" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
								<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>{pigcms{:L('_BACK_BALANCE_')}</option>
						</select>
						<input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>　
					</form>
				</td>
				<td>
					<b>{pigcms{:L('_BACK_A_RECE_')}：{pigcms{$total_price|floatval}</b>　
					<b>{pigcms{:L('_BACK_A_PAID_ON_')}：{pigcms{$online_price|floatval}</b>　
					<b>{pigcms{:L('_BACK_A_PAID_CASH_')}：{pigcms{$offline_price|floatval}</b>
				</td>
				<td>
				<a href="{pigcms{:U('Shop/export',$_GET)}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('_BACK_DOWN_ORDER_')}</a>
				</td>
			</tr>

		</table>
		<form name="myform" id="myform" action="" method="post">
			<div class="table-list">
				<table width="100%" cellspacing="0">
					<colgroup>
						<col/>
						<col/>
						<col/>
						<col/>
						<col/>
						<col/>
					</colgroup>
					<thead>
						<tr>
							<th>{pigcms{:L('_BACK_ORDER_NUM_')}</th>
							<!--th>商家名称</th-->
							<th>{pigcms{:L('_BACK_STORE_NAME_')}</th>
							<th>{pigcms{:L('_BACK_STORE_PHONE_')}</th>
							<th>{pigcms{:L('_BACK_USER_NAME_')}</th>
							<th>{pigcms{:L('_BACK_USER_PHONE_')}</th>
                            <th>{pigcms{:L('_BACK_INIT_TOTAL_')}</th>
							<th>{pigcms{:L('_BACK_TOTAL_')}<i class="menu-icon fa fa-sort"></i></th>
                            <th>{pigcms{:L('_BACK_TIPS_')}</th>
							<th>{pigcms{:L('_BACK_TUTTI_DIS_')}</th>
							<th>{pigcms{:L('_BACK_MER_DIS_')}</th>
							<th>
							<if condition="$type eq 'price'">
								<if condition="$sort eq 'ASC'">
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}↓ </a>
								<elseif condition="$sort eq 'DESC'" />
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'ASC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}↑</a>
								<else />
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}<i class="menu-icon fa fa-sort"></i></a>
								</if>
							<else />
								<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}<i class="menu-icon fa fa-sort"></i></a>
							</if>
							</th>
							<th>{pigcms{:L('_BACK_TAX_')}<i class="menu-icon fa fa-sort"></i></th>
							<th>
							<if condition="$type eq 'pay_time'">
								<if condition="$sort eq 'ASC'">
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}↓</a>
								<elseif condition="$sort eq 'DESC'" />
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'ASC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}↑</a>
								<else />
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}</a>
								</if>
							<else />
								<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}</a>
							</if>
							</th>
                            <th>{pigcms{:L('_BACK_PREP_TIME_')}</th>
							<th>{pigcms{:L('_BACK_ARR_TIME_')}</th>
							<th>{pigcms{:L('_BACK_ORDER_STATUS_')}</th>
							<th>{pigcms{:L('_BACK_PAY_STATUS_')}</th>
							<th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($order_list)">
							<volist name="order_list" id="vo">
								<tr>
									<td>{pigcms{$vo.real_orderid}</td>
									<!--td>{pigcms{$vo.merchant_name}</td-->
									<td>{pigcms{$vo.store_name}</td>
									<td>{pigcms{$vo.store_phone}</td>
									<td>{pigcms{$vo.username}</td>
									<td>{pigcms{$vo.userphone}</td>
                                    <td style="color: red">
                                        <php>if($vo['is_refund'] == 1){</php>
                                        ${pigcms{$vo['change_price'] + $vo['tip_charge']|floatval}
                                        <php>}</php>
                                    </td>
									<td>${pigcms{$vo['price'] + $vo['tip_charge']|floatval}</td>
                                    <td>${pigcms{$vo['tip_charge']|floatval}</td>
									<td>${pigcms{$vo.coupon_price|floatval}</td>
									<td>${pigcms{$vo.merchant_reduce|floatval}</td>
									<td>${pigcms{$vo.offline_price|floatval}</td>
									<td>${pigcms{$vo['duty_price']|floatval}</td>
									<td><if condition="$vo['pay_time']"> {pigcms{$vo['pay_time']|date="Y-m-d H:i:s",###}</if></td>
									<td>{pigcms{$vo.dining_time}</td>
                                    <td><if condition="$vo['use_time']">{pigcms{$vo['use_time']|date="Y-m-d H:i:s",###}</if></td>
                                    <td class="status">{pigcms{$vo.status_str}</td>
									<td><!-- {pigcms{$vo.pay_status} --><span style="color: green">{pigcms{$vo.pay_type_str}<br>({pigcms{$vo.pay_type})</span></td>
									<td class="textcenter">
										<if condition="$vo.status eq 0 AND $vo.paid eq 1">
                                            <a data-href="{pigcms{:U('Shop/refund_update',array('order_id'=>$vo['order_id']))}" class="refund">{pigcms{:L('_BACK_MANUAL_REFUND_')}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </if>
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_ORDER_DETAIL_\')}',920,520,true,false,false,false,'detail',true);">{pigcms{:L('_BACK_VIEW_')}</a>
                                        <php>if($vo['is_refund'] == 0){</php>
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/edit_order',array('order_id'=>$vo['order_id']))}','{pigcms{:L(\'_BACK_EDIT_\')}',920,520,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_EDIT_')}</a>
                                        <php>}</php>
                                        <a href="{pigcms{:U('Shop/del',array('id'=>$vo['order_id']))}" onclick="return confirm('{pigcms{:L(\'_B_PURE_MY_84_\')}')" style="color: red">{pigcms{:L('_BACK_DEL_')}</a>
									</td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="18">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="18">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
						</if>
					</tbody>
				</table>
			</div>
		</form>
	</div>
<script>
$(function(){
	$('#status').change(function(){
		location.href = "{pigcms{:U('Shop/order', array('type' => $type, 'sort' => $sort))}&status=" + $(this).val();
	});
	
	$('#pay_type').change(function(){
		location.href = "{pigcms{:U('Shop/order', array('type' => $type, 'sort' => $sort))}&pay_type=" + $(this).val();
	});	

	$('.refund').click(function(){
		var get_url = $(this).data('href'), obj = $(this);
		window.top.art.dialog({
            title:'Reminder',
			content: 'Are you sure about refund?',
			lock: true,
            okVal:'Yes',
			ok: function () {
				this.close();
				$.get(get_url,function(response){
					if (response.status == 1) {
						obj.parents('tr').find('.status').html('<del style="color:gray">已退款</del>');
						obj.remove();
					} else {
						window.top.art.dialog({
							title: response.info
						});
					}
				},'json');
				return false;
			},
			cancelVal: "{pigcms{:L('_BACK_CANCEL_')}",
			cancel: true
		});
	});
});


</script>
<include file="Public:footer"/>