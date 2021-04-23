<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{:L('DELIVERY_MANAGEMENT_BKADMIN')}</a>
			</li>
			<li>{pigcms{$now_store['name']}</li>
			<li class="active">{pigcms{:L('_BACK_ORDER_LIST_')}</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						<label class="col-sm-1" style="display:none">订单状态筛选：</label>
						<select id="status" name="status" style="display:none">
							<volist name="status_list" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
							</volist>
						</select>
						<form action="{pigcms{:U('Shop/order')}" method="get">
							<input type="hidden" name="c" value="Shop"/>
							<input type="hidden" name="a" value="order"/>
							<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>

                            {pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>{pigcms{:L('_BACK_ORDER_NUM_')}</option>
								<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
								<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('F_USER_NAME')}</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('F_USER_PHONE')}</option>
							</select>
							<font color="#000">{pigcms{:L('DATE_FILTER')}：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
                            {pigcms{:L('ORDER_STATUS_FILTER')}:
							<select id="status" name="status">
								<volist name="status_list" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
                            {pigcms{:L('PAY_METHOD_FILTER')}:
							<select id="pay_type" name="pay_type">
									<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>{pigcms{:L('_B_PURE_MY_64_')}</option>
								<volist name="pay_method" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
									<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>{pigcms{:L('_BALANCE_PAYMENT_')}</option>
							</select>
							<input type="submit" value="{pigcms{:L('SEARCH_BKADMIN')}" class="button"/>　
                            <a href="{pigcms{:U('Shop/export_pdf', $_GET)}" class="btn btn-success" style="float:right;margin-right: 10px;">{pigcms{:L('_BACK_DOWN_PDF_')}</a>
                            <a href="{pigcms{:U('Shop/export', $_GET)}" class="btn btn-success" style="float:right;margin-right: 10px;">{pigcms{:L('_BACK_DOWN_ORDER_')}</a>
						</form>
					</div>
					
					<div class="alert alert-info" style="margin:10px 0;">
						<b>{pigcms{:L('_BACK_A_RECE_')}：{pigcms{$total_price|floatval}</b>　
						<b>{pigcms{:L('_BACK_A_PAID_ON_')}：{pigcms{$online_price|floatval}</b>　
						<b>{pigcms{:L('_BACK_A_PAID_CASH_')}：{pigcms{$offline_price|floatval}</b>
					</div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="50">{pigcms{:L('F_ORDER_ID')}</th>
									<!--th id="shopList_c1" width="50">订单号</th-->
									<th id="shopList_c1" width="50">{pigcms{:L('F_USER_NAME')}</th>
									<th id="shopList_c0" width="80">{pigcms{:L('F_USER_PHONE')}</th>
									<!--th id="shopList_c0" width="80">配送方式</th>
									<th id="shopList_c0" width="80">地址</th-->
									
									
									<th id="shopList_c5" width="50">{pigcms{:L('_TOTAL_COMM_PRICE_')}<br>($)</th>
									<!--th id="shopList_c5" width="50">包装费</th-->
									<th id="shopList_c3" width="30">{pigcms{:L('_DELI_PRICE_')}<br>($)</th>
									<th id="shopList_c3" width="100">
									<if condition="$type eq 'price'">
										<if condition="$sort eq 'ASC'">
											<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">{pigcms{:L('_ORDER_TOTAL_')}<br>($)<i class="menu-icon fa fa-sort-desc"></i></a>
										<elseif condition="$sort eq 'DESC'" />
											<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'ASC', 'status' => $status))}">{pigcms{:L('_ORDER_TOTAL_')}<br>($)<i class="menu-icon fa fa-sort-asc"></i></a>
										<else />
											<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">{pigcms{:L('_ORDER_TOTAL_')}<br>($)<i class="menu-icon fa fa-sort"></i></a>
										</if>
									<else />
										<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">{pigcms{:L('_ORDER_TOTAL_')}<br>($)<i class="menu-icon fa fa-sort"></i></a>
									</if>
									</th>
									<th id="shopList_c4" width="50">{pigcms{:L('_BACK_MER_DIS_')}<br>($)</th>
									<th id="shopList_c4" width="50">{pigcms{:L('_BACK_TUTTI_DIS_')}<br>($)</th>
									
									<th id="shopList_c4" width="50">{pigcms{:L('_BACK_ACT_AM_PAID_')}<br>($)</th>
									
									<th id="shopList_c4" width="50">{pigcms{:L('_BALANCE_PAYMENT_')}<br>($)</th>
									<th id="shopList_c4" width="50">{pigcms{:L('_ONLINE_PAY_')}<br>($)</th>
									<th id="shopList_c4" width="50">{pigcms{:L('_SHOP_CARD_PAY_')}<br>($)</th>
									<th id="shopList_c4" width="50">{pigcms{:L('_SHOP_COUP_')}<br>($)</th>
									<th id="shopList_c4" width="50">{pigcms{:L('_PLATFORM_COUP_')}<br>($)</th>
									<th id="shopList_c4" width="50">使用{pigcms{$config['score_name']}数</th>
									<th id="shopList_c4" width="50">使用{pigcms{$config['score_name']}金额</th>
									<th id="shopList_c4" width="50">店员应收现价</th>
									
									<th id="shopList_c3" width="80">{pigcms{:L('_ORDER_TIME_')}</th>
									<th id="shopList_c3" width="90">
									<if condition="$type eq 'pay_time'">
										<if condition="$sort eq 'ASC'">
											<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}">{pigcms{:L('_PAYMENT_TIME_')}<i class="menu-icon fa fa-sort-desc"></i></a>
										<elseif condition="$sort eq 'DESC'" />
											<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'pay_time', 'sort' => 'ASC', 'status' => $status))}">{pigcms{:L('_PAYMENT_TIME_')}<i class="menu-icon fa fa-sort-asc"></i></a>
										<else />
											<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}">{pigcms{:L('_PAYMENT_TIME_')}<i class="menu-icon fa fa-sort"></i></a>
										</if>
									<else />
										<a href="{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}">{pigcms{:L('_PAYMENT_TIME_')}<i class="menu-icon fa fa-sort"></i></a>
									</if>
									</th>
									<th id="shopList_c3" width="80">{pigcms{:L('_EXPECTED_TIME_')}</th>
									<th id="shopList_c3" width="80">{pigcms{:L('_BACK_FINISH_AT_')}</th>
									
									<th id="shopList_c4" width="70">{pigcms{:L('_BACK_PAYMENT_STATUS_')}</th>
									<th id="shopList_c4" width="70">{pigcms{:L('PAYMENT_OPTIONS_BKADMIN')}</th>
									<th id="shopList_c4" width="70">{pigcms{:L('_ORDER_STATUS_')}</th>
									<th id="shopList_c4" width="70">{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>
<!--									<th id="shopList_c5" width="120" >验证信息</th>-->
									<!--th id="shopList_c5" width="120" >顾客留言</th>
									<th id="shopList_c4" width="50">发票抬头</th-->
									<th id="shopList_c5" width="20" >{pigcms{:L('V3_ORDER_RESULT_PAYMENT_VIEW_ORDER')}</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$order_list">
									<volist name="order_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><div class="tagDiv">{pigcms{$vo.real_orderid}</div></td>
											<!--td><div class="tagDiv">{pigcms{$vo.orderid}</div></td-->
											<td><div class="tagDiv">{pigcms{$vo.username}</div></td>
											<td><div class="shopNameDiv">{pigcms{$vo.userphone}</div></td>
											<!--td>{pigcms{$vo.deliver_str}</td>
											<td>{pigcms{$vo.address}</td-->
											
											<td>{pigcms{$vo.goods_price|floatval}</td>
											<!--td>{pigcms{$vo.packing_charge}</td-->
											<td>{pigcms{$vo.freight_charge|floatval}</td>
											<td>{pigcms{$vo.total_price|floatval}</td>
											<td>{pigcms{$vo.merchant_reduce|floatval}</td>
											<td>{pigcms{$vo.balance_reduce|floatval}</td>
											<td>{pigcms{$vo.price|floatval}</td>
											
											<td>{pigcms{$vo.balance_pay|floatval}</td>
											<td>{pigcms{$vo.payment_money|floatval}</td>
											<td>{pigcms{$vo.merchant_balance|floatval}</td>
											<td>{pigcms{$vo.card_price|floatval}</td>
											<td>{pigcms{$vo.coupon_price|floatval}</td>
											<td>{pigcms{$vo.score_used_count}</td>
											<td>{pigcms{$vo.score_deducte|floatval}</td>
											<td style="color: green"><strong>{pigcms{$vo['offline_price']|floatval}</strong></td>
											
											<td>{pigcms{$vo.create_time|date="Y-m-d H:i:s",###}</td>
											<if condition="$vo['pay_time']">
											<td>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</td>
											<else />
											<td></td>
											</if>
											<if condition="$vo['expect_use_time']">
											<td>{pigcms{$vo.expect_use_time|date="Y-m-d H:i:s",###}</td>
											<else />
											<td>{pigcms{:L('ASAP_BKADMIN')}</td>
											</if>
											<if condition="$vo['use_time']">
											<td>{pigcms{$vo.use_time|date="Y-m-d H:i:s",###}</td>
											<else />
											<td></td>
											</if>
											<td>{pigcms{$vo.pay_status}</td>
											<td>{pigcms{$vo.pay_type_str}</td>
											<td>{pigcms{$vo.status_str}</td>
											<td>{pigcms{$vo.deliver_status_str}</td>
											
<!--											<td>-->
<!--											<if condition="!empty($vo['last_staff'])">-->
<!--                                                {pigcms{:L('OPERATOR_BKADMIN')}：<span class="red">{pigcms{$vo['last_staff']}</span><if condition="$vo['use_time']"><br/>{pigcms{:L('_SALE_TIME_')}：<br/>{pigcms{$vo.use_time|date="Y-m-d H:i",###}</if>-->
<!--											<else/>-->
<!--											    <span class="red">未验证消费</span>-->
<!--											</if>-->
<!--											</td>-->
											<!--td>{pigcms{$vo.note}</td>
											<td>{pigcms{$vo.invoice_head}</td-->
											
											<td>
											<a title="{pigcms{:L('Action')}" class="green handle_btn" style="float:right" href="{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id']))}">
												<i class="ace-icon fa fa-search bigger-130"></i>
											</a>
											</td>
											
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="30" >{pigcms{:L('_B_PURE_MY_83_')}</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
    body {
        background-color: #fff;
    }
</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
$(function(){
	$('.handle_btn').live('click',function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'{pigcms{:L('F_DETAILS')}',
			padding: 0,
			width: 720,
			height: 520,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
	});
	$('#status').change(function(){
		location.href = "{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => $type, 'sort' => $sort))}&status=" + $(this).val();
	});	
});
</script>
<include file="Public:footer"/>
