<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">优惠买单</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink cur" data-url="{pigcms{:U('store_order')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>订单号</th>
								<th>用户昵称</th>
								<th>用户电话</th>
								<th>订单金额</th>
								<th>优惠金额</th>
								<th>获得{pigcms{$config.score_name}数</th>
								<th>使用{pigcms{$config.score_name}数</th>
								<th>实付金额</th>
								<th>支付时间</th>
								<th>支付类型</th>
							</tr>
						</thead>
						<tbody>
							<volist name="order_list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
								
									<td><div class="tagDiv">{pigcms{$vo.order_id}</div></td>
									<td>{pigcms{$vo['nickname']}</td>
									<td>{pigcms{$vo['phone']}</td>
									<td>{pigcms{$vo['total_price']|floatval}</td>
									<td>{pigcms{$vo['discount_price']|floatval}</td>
									<td>{pigcms{$vo.score_give|floatval}</td>
									<td>{pigcms{$vo.score_used_count|floatval}</td>
									<td>{pigcms{$vo['price']|floatval}</td>
									<td>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</td>
									<td>{pigcms{$vo.pay_type_show}</td>
								</tr>
							</volist>
						</tbody>
					</table>
					{pigcms{$pagebar}
				</div>
			</div>
		</div>
	</body>
</html>