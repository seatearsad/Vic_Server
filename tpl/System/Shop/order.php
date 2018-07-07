<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('Shop/order')}" class="on">订单列表</a>
			</ul>
		</div>
		<table class="search_table" width="100%">
			<tr>
				<td>
				<form action="{pigcms{:U('Shop/order')}" method="get">
						<input type="hidden" name="c" value="Shop"/>
						<input type="hidden" name="a" value="order"/>
						
						搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
						<select name="searchtype">
							<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>
							<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
							<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
							<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>店铺名称</option>
							<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
							<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
						</select>
						<font color="#000">日期筛选：</font>
						<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
						<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
						订单状态筛选:
						<select id="status" name="status">
							<volist name="status_list" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
							</volist>
						</select>
						支付方式筛选: 
						<select id="pay_type" name="pay_type">
								<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
							<volist name="pay_method" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
								<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
						</select>
						<input type="submit" value="查询" class="button"/>　
					</form>
				</td>
				<td>
					<b>应收总金额：{pigcms{$total_price|floatval}</b>　
					<b>在线支付总额：{pigcms{$online_price|floatval}</b>　
					<b>线下支付总额：{pigcms{$offline_price|floatval}</b>
				</td>
				<td>
				<a href="{pigcms{:U('Shop/export',$_GET)}" class="button" style="float:right;margin-right: 10px;">导出订单</a>
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
						<col width="180" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>订单编号</th>
							<th>商家名称</th>
							<th>店铺名称</th>
							<th>店铺电话</th>
							<th>下单人</th>
							<th>电话</th>
							<th>总价<i class="menu-icon fa fa-sort"></i></th>
							<th>平台优惠</th>
							<th>商家优惠</th>
							<th>
							<if condition="$type eq 'price'">
								<if condition="$sort eq 'ASC'">
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">应付总价↓ </a>
								<elseif condition="$sort eq 'DESC'" />
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'ASC', 'status' => $status))}" style="color:blue;">应付总价↑</a>
								<else />
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">应付总价<i class="menu-icon fa fa-sort"></i></a>
								</if>
							<else />
								<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">应付总价<i class="menu-icon fa fa-sort"></i></a>
							</if>
							</th>
							<th>税费<i class="menu-icon fa fa-sort"></i></th>
							<th>
							<if condition="$type eq 'pay_time'">
								<if condition="$sort eq 'ASC'">
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">支付时间↓</a>
								<elseif condition="$sort eq 'DESC'" />
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'ASC', 'status' => $status))}" style="color:blue;">支付时间↑</a>
								<else />
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">支付时间</a>
								</if>
							<else />
								<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">支付时间</a>
							</if>
							</th>
							<th>送达时间</th>
							<th>订单状态</th>
							<th>支付情况</th>
							<th class="textcenter">操作</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($order_list)">
							<volist name="order_list" id="vo">
								<tr>
									<td>{pigcms{$vo.real_orderid}</td>
									<td>{pigcms{$vo.merchant_name}</td>
									<td>{pigcms{$vo.store_name}</td>
									<td>{pigcms{$vo.store_phone}</td>
									<td>{pigcms{$vo.username}</td>
									<td>{pigcms{$vo.userphone}</td>
									<td>${pigcms{$vo['total_price']|floatval}</td>
									<td>${pigcms{$vo.balance_reduce|floatval}</td>
									<td>${pigcms{$vo.merchant_reduce|floatval}</td>
									<td>${pigcms{$vo.price|floatval}</td>
									<td>${pigcms{$vo['duty_price']|floatval}</td>
									<td><if condition="$vo['pay_time']"> {pigcms{$vo['pay_time']|date="Y-m-d H:i:s",###}</if></td>
									<td><if condition="$vo['use_time']">{pigcms{$vo['use_time']|date="Y-m-d H:i:s",###}</if></td>
									<td class="status">{pigcms{$vo.status_str}</td>
									<td><!-- {pigcms{$vo.pay_status} --><span style="color: green">{pigcms{$vo.pay_type_str}</span></td>
									<td class="textcenter">
										<if condition="$vo.status eq 0 AND $vo.paid eq 1">
                                            <a data-href="{pigcms{:U('Shop/refund_update',array('order_id'=>$vo['order_id']))}" class="refund">手动退款</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</if>
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','查看{pigcms{$config.shop_alias_name}订单详情',720,520,true,false,false,false,'detail',true);">查看</a>
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/edit_order',array('order_id'=>$vo['order_id']))}','编辑订单',600,260,true,false,false,editbtn,'edit',true);">编辑</a>
                                        <a href="{pigcms{:U('Shop/del',array('id'=>$vo['order_id']))}" onclick="return confirm('确定要删除吗？')" style="color: red">删除</a>
									</td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="15">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="15">列表为空！</td></tr>
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
			content: '您确定要手动退款吗？只取修改订单状态！',
			lock: true,
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
			cancelVal: '取消',
			cancel: true
		});
	});
});


</script>
<include file="Public:footer"/>