<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{:L('_MY_OUT_ORDER_')}  {pigcms{:L('_VIC_NAME_')}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  shop_alias_name = "{pigcms{$config.shop_alias_name}";
	</script>
<script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_path}js/category.js"></script>
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
    <include file="Public:facebook"/>
</head>
<body id="orders" class="has-order-nav" style="position:static;">
<include file="Public:header_top"/>
 <div class="body pg-buy-process"> 
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top">{pigcms{:L('_ALL_CLASSIF_')}</div>
					<div class="list">
						<!--ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul-->
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{:lang_substr($vo['name'],C('DEFAULT_LANG'))}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<include file="Public:scroll_msg"/>	
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<include file="Public:sidebar"/>
				<div id="content" class="coupons-box">
					<div class="mainbox mine">
						<select class="J-orders-filter orders-filter dropdown--small" id="select_status">
							<option value="-1" <if condition="$status eq -1">selected="selected"</if>>{pigcms{:L('_ALL_STATUS_')}</option>
							<option value="1" <if condition="$status eq 1">selected="selected"</if>>{pigcms{:L('_NOT_SALE_PAY_')}</option>
							<option value="0" <if condition="$status eq 0">selected="selected"</if>>{pigcms{:L('_NOT_PAY_')}</option>
							<option value="2" <if condition="$status eq 2">selected="selected"</if>>{pigcms{:L('_AL_USED_')}</option>
						</select>
						<div class="orders-wrapper" id="order-list">
							<div class="orders-head">
								<div class="order-cell order-info">{pigcms{:L('_SHOP_INFO_')}</div>
								<div class="order-cell order-quantity">{pigcms{:L('_B_PURE_MY_69_')}</div>
								<div class="order-cell order-money">{pigcms{:L('_B_PURE_MY_70_')}</div>
								<div class="order-cell order-status">{pigcms{:L('_ORDER_STATUS_')}</div>
								<div class="order-cell order-op">{pigcms{:L('_ACTION_')}</div>
							</div>
							<volist name="order_list" id="vo">
								<div class="J-order-w">
									<div class="order-title">{pigcms{:L('_B_PURE_MY_68_')}：<a href="{pigcms{:U('Index/shop_order_view',array('order_id'=>$vo['order_id']))}" target="_blank">{pigcms{$vo.order_id}</a></div>
									<div class="order-row">
										<div class="order-cell order-op order-cell--right">
											<if condition="$vo['status'] eq 3">	
											<elseif condition="($vo['paid'] eq 0) AND ($vo['status'] eq 0)" />											
												<a class="btn-hot btn-mini" href="{pigcms{:U('Index/Pay/check',array('type'=>'shop','order_id'=>$vo['order_id']))}">{pigcms{:L('_B_PURE_MY_81_')}</a>
											<elseif condition="$vo['status'] eq 0" />											
												<a class="btn-hot btn-mini" href="javascript:void(0)" onclick="shop_order_cancel({pigcms{$vo['order_id']})">{pigcms{:L('_B_D_LOGIN_CANCEL_')}</a>
											<elseif condition="$vo['status'] == 2"/>
												<a href="{pigcms{:U('Rates/shop')}">{pigcms{:L('_EVALUATE_TXT_')}</a>
											</if>
										</div>
										<div class="order-row--sub order-row--last">
											<div class="order-cell order-info">
												<div class="deal-info cf">
													<a class="img-w" href="{pigcms{$vo.url}" target="_blank" title="{pigcms{$vo.s_name}">
														<img src="{pigcms{$vo.image}" width="81" height="50"/>
													</a>
													<div class="info-detail">
														<a class="deal-title" href="{pigcms{$vo.url}" title="{pigcms{$vo.s_name}" target="_blank">{pigcms{$vo.s_name}</a>
														<p>{pigcms{:L('_ORDER_TIME_')}：{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</p>
														<a target="_blank" class="biz-info" href="{pigcms{$vo.url}">{pigcms{:L('_SHOP_INFO_')}</a>
													</div>
												</div>
											</div>
											<div class="order-cell order-quantity">{pigcms{$vo.num}</div>
											<div class="order-cell order-money"><span class="money">$</span>{pigcms{$vo['price']+$vo['tip_charge']-$vo['coupon_price']}</div>
											<div class="order-cell order-status">
											<if condition="$vo['status'] eq 4">
												{pigcms{:L('_CANCEL_A_REFUND_')}
											<elseif condition="$vo['status'] eq 5" />
												{pigcms{:L('_B_PURE_MY_76_')}
											<elseif condition="empty($vo['paid'])"/>
												{pigcms{:L('_NOT_PAY_')}
											<elseif condition="$vo['third_id'] eq '0' AND $vo['pay_type'] eq 'offline'"/>
												{pigcms{:L('_NOT_SALE_PAY_')} (<font color="red">{pigcms{:L('_UNPAID_OFFLINE_')}</font>)
											<elseif condition="$vo['status'] lt 2"/>
                                                    <if condition="$vo['now_status'] lt 2">
												    {pigcms{:L('_ORDER_STATUS_1_')}
                                                    <elseif condition="$vo['now_status'] eq 2"/>
                                                    {pigcms{:L('_CLERK_ORDER_')}
                                                    <elseif condition="$vo['now_status'] eq 3"/>
                                                    {pigcms{:L('_DISTER_ORDER_')}
                                                    <elseif condition="$vo['now_status'] eq 4"/>
                                                    {pigcms{:L('_DISTER_GET_FOOD_')}
                                                    <elseif condition="$vo['now_status'] eq 5"/>
                                                    {pigcms{:L('_DISTER_DISTING_')}
                                                    <elseif condition="$vo['now_status'] eq 6"/>
                                                    {pigcms{:L('_DIST_END_')}
                                                    <elseif condition="$vo['now_status'] eq 7"/>
                                                    <if condition="$vo['is_pick_in_store'] eq 3">
                                                        {pigcms{:L('_CLERK_SHIP_GOODS_')}
                                                        <else />
                                                        {pigcms{:L('_VERIFYING_CONS_')}
                                                    </if>
                                                    <elseif condition="$vo['now_status'] eq 8"/>
                                                    {pigcms{:L('_COMPLETE_REVIEW_')}
                                                    <elseif condition="$vo['now_status'] eq 9"/>
                                                    {pigcms{:L('_REFUNDS_COMPLETE_')}
                                                    <elseif condition="$vo['now_status'] eq 10"/>
                                                    {pigcms{:L('_CANCELLATION_ORDER_')}
                                                    <elseif condition="$vo['now_status'] eq 11"/>
                                                    {pigcms{:L('_BUSINESS_ALLOCATION_')}
                                                    <elseif condition="$vo['now_status'] eq 12"/>
                                                    {pigcms{:L('_SHIP_TO_PRO_')}
                                                    <elseif condition="$vo['now_status'] eq 13"/>
                                                    {pigcms{:L('_PICKUP_GOODS_')}
                                                    <elseif condition="$vo['now_status'] eq 14"/>
                                                    {pigcms{:L('_SELF_BEEN_DELI_')}
                                                    <elseif condition="$vo['now_status'] eq 15"/>
                                                    {pigcms{:L('_YOU_PICKUP_GOODS_')}
                                                    <elseif condition="$vo['now_status'] eq 30"/>
                                                    {pigcms{:L('_CHANGE_PRICE_FOR_Y_')}
                                                    </if>
											<elseif condition="$vo['status'] eq 2"/>
												{pigcms{:L('_AL_USED_')}
											<elseif condition="$vo['status'] eq 3"/>
												{pigcms{:L('_B_PURE_MY_74_')}
											</if>
											<div><a target="_blank" href="{pigcms{:U('Index/shop_order_view',array('order_id'=>$vo['order_id']))}">{pigcms{:L('_ORDER_DETAIL_')}</a></div></div>
										</div>
									</div>
								</div>
							</volist>
                        </div>
						{pigcms{$pagebar}
					</div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>	
</div>
<include file="Public:footer"/>
<script type="text/javascript">
	$(document).ready(function(){
		$("#select_status").change(function(data){
			location.href='{pigcms{:U(User/Index/shop_list)}&status=' + $(this).val();
		});
	});
	
	
	function shop_order_cancel(order_id){
			if(confirm("{pigcms{:L('_CONFIRM_CANCEL_ORDER_')}")){
				var cancelUrl = "{pigcms{:U('shop_order_check_refund')}";
				cancelUrl += "&order_id="+order_id;
				location.href = cancelUrl;
			}
			
		}
</script>
</body>
</html>
