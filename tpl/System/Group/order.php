<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Group/order')}" class="on">订单列表</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Group/order')}" method="get">
							<input type="hidden" name="c" value="Group"/>
							<input type="hidden" name="a" value="order"/>
							
							搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>
								<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>支付流水号</option>
								<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
								<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{$config.group_alias_name}名称</option>
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
							</select>
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							
							订单状态筛选: 
							<select id="status" name="status" >
								
								<volist name="status_list" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
							支付方式筛选: 
							<select id="pay_type" name="pay_type">
									<option value="" <if condition="$key eq $pay_type">selected="selected"</if>>全部支付方式</option>
								<volist name="pay_method" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
									<option value="balance" <if condition="$key eq $pay_type">selected="selected"</if>>余额支付</option>
							</select>
							<input type="submit" value="查询" class="button"/>　　
						</form>
					</td>
					<td>
						<a href="{pigcms{:U('Group/export',$_GET)}" class="button" style="float:right;margin-right: 10px;">导出订单</a>
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
								<th>订单编号</th>
								<th>商家信息</th>
								<th>{pigcms{$config.group_alias_name}信息</th>
								<th>订单信息</th>
								<th>订单用户</th>
								<th>查看用户信息</th>
								<th>订单状态</th>
								<th>
								<if condition="$type eq 'pay_time'">
									<if condition="$sort eq 'ASC'">
										<a href="{pigcms{:U('Group/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">时间(支付时间↓) </a>
									<elseif condition="$sort eq 'DESC'" />
										<a href="{pigcms{:U('Group/order', array('type' => 'pay_time', 'sort' => 'ASC', 'status' => $status))}" style="color:blue;">时间(支付时间↑)</a>
									<else />
										<a href="{pigcms{:U('Group/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">时间</a>
									</if>
								<else />
									<a href="{pigcms{:U('Group/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">时间</a>
								</if>
								</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($order_list)">
								<volist name="order_list" id="vo">
									<tr>
										<td>{pigcms{$vo.real_orderid}</td>
										<td>商家ID：{pigcms{$vo.mer_id}　商家电话：{pigcms{$vo.m_phone}<br/>商家名称：{pigcms{$vo.m_name}</td>
										<td>{pigcms{$config.group_alias_name}ID：{pigcms{$vo.group_id}　{pigcms{$config.group_alias_name}价：${pigcms{$vo.g_price}<br/>{pigcms{$config.group_alias_name}名称：{pigcms{$vo.s_name}</td>
										<td>数量：{pigcms{$vo.num}<br/>总价：${pigcms{$vo.total_money|floatval=###}</td>
										<td>用户名：{pigcms{$vo.nickname}<br/>订单手机号：{pigcms{$vo.group_phone}</td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','编辑用户信息',680,560,true,false,false,editbtn,'edit',true);">查看用户信息</a>
										</td>
										<td>
											<if condition="$vo['paid'] eq 1">
												<if condition="$vo['pay_type'] eq 'offline' AND empty($vo['third_id']) AND $vo['status'] eq 0" >
													<font color="red">线下支付&nbsp;未付款</font>
												<elseif condition="$vo['status'] eq 0" />
													<font color="green">已付款</font>&nbsp;
													<php>if($vo['tuan_type'] != 2){</php>
														<font color="red">未消费</font>
													<php>}else{</php>
														<php>if($vo['is_pick_in_store']){</php>
															<font color="red">未取货</font>
														<php>}else{</php>
															<font color="red">未确认收货</font>
														<php>}</php>
													<php>}</php>
												<elseif condition="$vo['status'] eq 1"/>
													<php>if($vo['tuan_type'] != 2){</php>
														<font color="green">已消费</font>
													<php>}else{</php>
														<php>if($vo['is_pick_in_store']){</php>
															<font color="green">已取货</font>
														<php>}else{</php>
															<font color="green">已收货</font>
														<php>}</php>
													<php>}</php>&nbsp;
													<font color="red">待评价</font>
												<elseif condition="$vo['status'] eq 2"/>
													<font color="green">已完成</font>
												<elseif condition="$vo['status'] eq 3"/>
													<font color="red">已退款</font>
												<elseif condition="$vo['status'] eq 4"/>
													<font color="red">已取消</font>
												</if>
											<else/>
												<if condition="$vo['status'] eq 4">
													<font color="red">已取消</font>
												<else />
													<font color="red">未付款</font>
												</if>
											</if>
										</td>
										<td>
											下单时间：{pigcms{$vo['add_time']|date='Y-m-d H:i:s',###}<br/>
											<if condition="$vo['paid']">付款时间：{pigcms{$vo['pay_time']|date='Y-m-d H:i:s',###}</if>
										</td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Group/order_detail',array('order_id'=>$vo['order_id']))}','查看{pigcms{$config.group_alias_name}订单详情',800,560,true,false,false,false,'order_edit',true);">查看详情</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="11">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script>
$(function(){
	// $('#status').change(function(){
		// location.href = "{pigcms{:U('Group/order', array('type' => $type, 'sort' => $sort))}&status=" + $(this).val();
	// });	
	// $('#pay_type').change(function(){
		// location.href = "{pigcms{:U('Group/order', array('type' => $type, 'sort' => $sort))}&pay_type=" + $(this).val();
	// });	
});

</script>
<include file="Public:footer"/>