<include file="Public:header"/>
<style>
    .table-list thead th{
        line-height: 20px;
    }
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">{pigcms{:L('_BACK_COURIER_MANA_')}</a>|
					<a href="{pigcms{:U('Deliver/log_list', array('uid'=>$user['uid']))}" class="on">【{pigcms{$user['name']}】{pigcms{:L('_BACK_COURIER_OVER_')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Deliver/log_list')}" method="get">
							<input type="hidden" name="c" value="Deliver"/>
							<input type="hidden" name="a" value="log_list"/>
							<input type="hidden" name="uid" value="{pigcms{$user['uid']}"/>
							{pigcms{:L('_BACK_START_DATE_')}: <!--input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>用户ID</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>昵称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>手机号</option>
							</select-->
							<input type="text" class="input-text" name="begin_time" style="width:160px;" value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>&nbsp;&nbsp;&nbsp;to&nbsp;&nbsp;&nbsp;
							<input type="text" class="input-text" name="end_time" style="width:160px;" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>&nbsp;&nbsp;&nbsp;
							<input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>
							<a href="{pigcms{:U('Deliver/export_user', array('begin_time' => $begin_time, 'end_time' => $end_time, 'uid' => $user['uid']))}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('_BACK_DOWN_ORDER_')}</a>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
<!-- 								<th>订单ID</th> -->
								<th>{pigcms{:L('_C_ORDER_SOURCE_')}</th>
								<!--th>配送员类型</th-->
								<th>{pigcms{:L('_BACK_STORE_NAME_')}</th>
								<th>{pigcms{:L('_BACK_USER_NAME_')}</th>
								<th>{pigcms{:L('_BACK_USER_PHONE_')}</th>
								<th>{pigcms{:L('_BACK_CUSTOM_ADD_')}</th>
								<!--th>支付方式</th-->
								<th>{pigcms{:L('_BACK_PAYMENT_STATUS_')}</th>
								<th>{pigcms{:L('_BACK_ORDER_TOTAL_')}</th>
								<th>{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>
								<th>{pigcms{:L('_BACK_START_AT_')}</th>
								<th>{pigcms{:L('_BACK_FINISH_AT_')}</th>
								<th>{pigcms{:L('_BACK_CASH_RECE_')}</th>
								<!--th>创建时间</th-->
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($supply_info)">
								<volist name="supply_info"  id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
<!-- 										<td width="30">{pigcms{$vo.order_id}</td> -->
										<td><if condition="$vo['item'] eq 0">{pigcms{$config.meal_alias_name}<elseif condition="$vo['item'] eq 1" />外送系统<elseif condition="$vo['item'] eq 2" />{pigcms{:L('_BACK_DELIVERY_')}</if></td>
										<!--td width="50">{pigcms{$vo.group}</td-->
										<td>{pigcms{$vo.storename}</td>
										<td>{pigcms{$vo.username}</td>
										<td>{pigcms{$vo.userphone}</td>
										<td>{pigcms{$vo.aim_site}</td>
										<!--td width="50">{pigcms{$vo.pay_type}</td-->
										<td>{pigcms{$vo.paid}</td>
										<td>{pigcms{$vo.money|floatval}</td>
										<td>{pigcms{$vo.order_status}</td>
										<td>{pigcms{$vo.start_time}</td>
										<td>{pigcms{$vo.end_time}</td>
										<td style="color:red">{pigcms{$vo.deliver_cash|floatval}</td>
<!-- 										<td width="80">{pigcms{$vo.end_time}</td> -->
										<!--td width="50">{pigcms{$vo.create_time}</td-->
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="16">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="16">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script>
	var selectStoreId = {pigcms{:$selectStoreId? $selectStoreId: 0};
	var selectUserId = {pigcms{:$selectUserId? $selectUserId: 0};
	$(function(){
		$("#store").change(function(){
			selectStoreId = $("#store").val();
			selectUserId = 0;
			search();
		});
		$("#deliver").change(function(){
			selectStoreId = 0;
			selectUserId = $("#deliver").val();
			search();
		});
		$("#order_number").focus(function(){
			$("#phone").val("");
		});
		$("#phone").focus(function(){
			$("#order_number").val("");
		});
		$("#search").click(function(){
			var orderNum = $("#order_number").val();
			var phone = $("#phone").val();
			search(orderNum, phone)
		});
		function search(orderNum, phone) {
			var orderNum =  orderNum || 0;
			var phone = phone || 0;
			location.href = "{pigcms{:U('Merchant/Deliver/deliverList')}"+"&orderNum="+orderNum+"&phone="+phone+"&selectStoreId="+selectStoreId+"&selectUserId="+selectUserId;
		}
	});
</script>
<include file="Public:footer"/>