<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('User/recharge_list')}" class="on">{pigcms{:L('F_TOP_UP_LIST')}</a>
					<a href="{pigcms{:U('User/admin_recharge_list')}" >{pigcms{:L('F_CREDITS_ADDED')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('recharge_list')}" method="get">
							<input type="hidden" name="c" value="User"/>
							<input type="hidden" name="a" value="recharge_list"/>
							{pigcms{:L('F_FILTER')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="order_id" <if condition="$_GET['searchtype'] eq 'order_id'">selected="selected"</if>>{pigcms{:L('F_ORDER_ID')}</option>
								<!--option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>支付流水号</option-->
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('F_USER_NAME')}</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('F_USER_PHONE')}</option>
							</select>
							<input type="submit" value="{pigcms{:L('F_SEARCH')}" class="button"/>　　

							<!--支付状态：
							<select name="status" id="status">
								<option value="-1" <if condition="$_GET['status'] eq -1">selected="selected"</if>>全部</option>
								<option value="1" <if condition="$_GET['status'] eq 1">selected="selected"</if>>已支付</option>
								<option value="0" <if condition="$_GET['status'] eq 0">selected="selected"</if>>未支付</option>
							</select>-->
						</form>
					</td>
				</tr>
			</table>

			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<style>
					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}
					</style>
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>{pigcms{:L('F_ORDER_ID')}</th>
								<th>{pigcms{:L('F_AMOUNT')}</th>
								<th>{pigcms{:L('F_USER')}</th>
								<th>{pigcms{:L('F_USER_INFO')}</th>
								<th>{pigcms{:L('F_TIME')}</th>
								<th class="textcenter">{pigcms{:L('F_ACTION')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($order_list)">
								<volist name="order_list" id="vo">
									<tr>
										<td>{pigcms{$vo.order_id}</td>
										<td>${pigcms{$vo.money}</td>

										<td>{pigcms{$vo.nickname}</td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','{pigcms{:L(\'F_EDIT_INFO\')}',680,560,true,false,false,editbtn,'edit',true);">{pigcms{:L('F_USER_INFO')}</a>
										</td>
										<td>
											{pigcms{:L('F_ORDERING_TIME')}：{pigcms{$vo['add_time']|date='Y-m-d H:i:s',###}<br/>
											<if condition="$vo['paid']">{pigcms{:L('F_PAYMENT_TIME')}：{pigcms{$vo['pay_time']|date='Y-m-d H:i:s',###}</if>
										</td>
										<td class="textcenter">
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/order_detail',array('order_id'=>$vo['order_id']))}','{pigcms{:L(\'F_TOPUP_DETAILS\')}',800,560,true,false,false,false,'order_edit',true);">{pigcms{:L('F_DETAILS')}</a>
                                            <!--a href="javascript:void(0);" onclick="recharge_refund('{pigcms{$vo.order_id}','{pigcms{$vo.uid}')">退款</a-->
                                        </td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="6">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="6">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script>
$(function(){
	$('#status').change(function(){
		location.href = "{pigcms{:U('User/recharge_list')}&status=" + $(this).val();
	});
});
</script>
<include file="Public:footer"/>