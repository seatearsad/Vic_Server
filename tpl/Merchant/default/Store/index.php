<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffIndex.js?111"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffIndex.css"/>
	</head>
	<body>
		<div class="infoBox">
			<div class="logo">
				<img src="{pigcms{$config.site_merchant_logo}"/>
			</div>
			<div class="text">
				{pigcms{$staff_session.name}<if condition="$staff_session['type_name']">({pigcms{$staff_session['type_name']})</if>
				<br/>
				<span>{pigcms{$store.name}</span>
			</div>
		</div>
		<div class="pageBg"></div>
		<div class="pageLink">
			<ul>
				<if condition="$store['have_group']">
					<li class="group" data-url="{pigcms{:U('group_list')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.group_alias_name}</div>
					</li>
				</if>
				<if condition="$store['have_meal']">
					<li class="meal" data-url="{pigcms{:U('foodshop')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.meal_alias_name}</div>
					</li>
				</if>
				<if condition="$store['have_shop']">
					<li class="shop" data-url="{pigcms{:U('shop_list')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.shop_alias_name}</div>
					</li>
				</if>
				<if condition="$config['appoint_page_row']">
					<li class="appoint" data-url="{pigcms{:U('appoint_list')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.appoint_alias_name}</div>
					</li>
				</if>
				<if condition="$config['is_cashier'] OR $config['pay_in_store']">
					<li class="store" data-url="{pigcms{:U('store_order')}">
						<div class="icon"></div>
						<div class="text">{pigcms{$config.cash_alias_name}</div>
					</li>
					<li class="arrival" data-url="{pigcms{:U('store_arrival')}">
						<div class="icon"></div>
						<div class="text">店内收银</div>
					</li>
				</if>
				<li class="coupon" data-url="{pigcms{:U('coupon_list')}">
					<div class="icon"></div>
					<div class="text">优惠券</div>
				</li>
				<if condition="$store['have_meal'] OR $config['is_cashier'] OR $config['pay_in_store']">
				<li class="report" data-url="{pigcms{:U('report')}">
					<div class="icon"></div>
					<div class="text">报表统计</div>
				</li>
				</if>
				<li class="physical_card" data-url="{pigcms{:U('physical_card')}">
					<div class="icon"></div>
					<div class="text">实体卡管理</div>
				</li>
				<!--li class="qrcode">
					<div class="icon"></div>
					<div class="text">扫一扫</div>
				</li-->
				<li class="logout" data-confirm="您确定要退出吗？" data-url="{pigcms{:U('logout')}">
					<div class="icon"></div>
					<div class="text">退出</div>
				</li>
			</ul>
		</div>
	</body>
	<script type="text/javascript">
		setInterval(function(){
			$.post("/store.php?g=Merchant&c=Store&a=ping");
		},60000);
	</script>
</html>