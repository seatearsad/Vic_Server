<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/foodshopmenu.js?210" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
<script>var store_id = '{pigcms{$store["store_id"]}', all_goods = '{pigcms{$all_goods}', submit_url = '{pigcms{:U("Foodshop/order_detail", array("store_id" => $order["store_id"], "order_id" => $order["order_id"]))}', cookie_index = 'foodshop_cart_{pigcms{$store["store_id"]}_order_{pigcms{$order["order_id"]}';
var  open_extra_price =Number('{pigcms{$config.open_extra_price}');
</script>
</head>
<body>
	<section class="foodleft">
		<!--div class="search">
			<input type="text" placeholder="搜索您想吃的" class="sr">
			<a href="#">搜索</a>
		</div-->
		<div class="foodnav">
			<ul>
				<volist name="goods_list" id="sort" key="i">
				<li><a href="javascript:void(0)" data-cat_id="{pigcms{$sort['sort_id']}" <if condition="$i eq 1">class="on"</if>>{pigcms{$sort['sort_name']}</a></li>
				</volist>
			</ul>
		</div>
	</section>
	<section class="foodright">
		<volist name="goods_list" id="rowset">
		<dl data-cat_id="{pigcms{$rowset['sort_id']}" class="foodright-{pigcms{$rowset['sort_id']}">
			<dt>{pigcms{$rowset['sort_name']}</dt>
			<volist name="rowset['goods_list']" id="goods">
			<dd class="goods_{pigcms{$goods['goods_id']}">
				<div class="foodr_img">
					<img src="{pigcms{$goods['pic_arr'][0]['url']['s_image']}">
				</div>
				<div class="food_right">
					<h2>{pigcms{$goods['name']}</h2>
					<div class="MenuPrice">
						<i>$</i>{pigcms{$goods['price']}<if condition="$goods.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+<em style="font-size:12px;color:#f03c3c">{pigcms{$goods.extra_pay_price}{pigcms{$config.extra_price_alias_name}</em></if><em>/{pigcms{$goods['unit']}</em>
					</div>
					<if condition="$goods['spec_list'] OR $goods['properties_list']">
					<div class="Addsub">
						<span class="Speci">选规格</span>
					</div>
					<else />
	                <div class="Addsub">
	                    <a href="javascript:void(0)" class="jian" data-price="{pigcms{$goods['price']|floatval}" data-id="{pigcms{$goods['goods_id']}" data-index="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}" data-extra_pay_price="{pigcms{$goods.extra_pay_price}" data-extra_price_name = "{pigcms{$config.extra_price_alias_name}"></a>
						<input type="text" value="0" readOnly="true" class="num">
	                    <a href="javascript:void(0)" class="jia" data-price="{pigcms{$goods['price']|floatval}" data-id="{pigcms{$goods['goods_id']}" data-index="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}" data-extra_pay_price="{pigcms{$goods.extra_pay_price}" data-extra_price_name = "{pigcms{$config.extra_price_alias_name}"></a>
					</div>
					</if>
				</div>
				<if condition="$goods['spec_list'] OR $goods['properties_list']">
					<section class="Tcancel TcancelT">
						<div class="TcancelT_top clr">
							<div class="TcancelT_topL">
								<h2>{pigcms{$goods['name']}</h2>
								<span><i>$</i>{pigcms{$goods['price']}</span>
							</div>
							<a href="javascript:void(0)" class="gb"></a>
						</div>
						<div class="TcancelT_zh">
							<div class="TcancelT_n">
								<volist name="goods['spec_list']" id="spec_r">
								<div class="TcancelT_list">
									<h2>{pigcms{$spec_r['name']}</h2>
									<div class="fications" data-id="{pigcms{$spec_r['id']}" data-num="1" data-name="{pigcms{$spec_r['name']}" data-type="spec">
										<ul class="clr" >
											<?php foreach ($spec_r['list'] as $srow) {?>
											<li data-id="{pigcms{$srow['id']}" data-name="{pigcms{$srow['name']}" data-type="spec" data-goods_id="{pigcms{$goods['goods_id']}">{pigcms{$srow['name']}</li>
											<?php }?>
										</ul>
									</div>
								</div>
								</volist>
								<volist name="goods['properties_list']" id="pro_r">
								<div class="TcancelT_list">
									<h2>{pigcms{$pro_r['name']}</h2>
									<div class="fications" data-id="{pigcms{$pro_r['id']}" data-name="{pigcms{$pro_r['name']}" data-num="{pigcms{$pro_r['num']}" data-type="properties">
										<ul class="clr" >
											<?php foreach ($pro_r['val'] as $k => $val) {?>
											<li data-id="{pigcms{$k}" data-name="{pigcms{$val}" data-type="properties" data-goods_id="{pigcms{$goods['goods_id']}">{pigcms{$val}</li>
											<?php }?>
										</ul>
									</div>
								</div>
								</volist>
							</div>
						</div>
						<div class="Selected">
							已选：<span></span>
						</div>
						<div class="join" data-goods_id="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}" data-price="{pigcms{$goods['price']}">
							<input type="button" value="加入菜单">
						</div>
					</section>
				</if>
			</dd>
			</volist>
		</dl>
		</volist>
	</section>
	<div class="Mask"></div>
	<section class="floor clr">
		<div class="trolley"></div>
		<div class="qty">0</div>
		<div class="prix">$<i id="total_price">0</i></div>
		<form name="cart_confirm_form" action="{pigcms{:U('Foodshop/order_detail', array('order_id' => $order['order_id']))}" method="post">
		<input type="hidden" name="store_id" value="{pigcms{$store['store_id']}" />
		<input type="hidden" name="order_id" value="{pigcms{$order['order_id']}" />
		<input type="button" class="next" value="下一步">
		</form>
		<!--a href="javascirpt:void(0);" class="next">下一步</a-->
	</section>
	<section class="Cart">
		<div class="Cart_top clr">
			<h2>购物车</h2>
			<span>清空</span>
		</div>
		<div class="Cart_list">
			<ul>
			</ul>
		</div>
	</section>
</body>
</html>