<!doctype html>
<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<!--title>{pigcms{$config.shop_alias_name}_{pigcms{$now_city.area_name}_{pigcms{$config.seo_title}</title-->
    <title>{pigcms{$store['name']} {pigcms{:L('_OUT_TXT_')} | {pigcms{:L('_VIC_NAME_')}</title>
	<if condition="$now_area">
		<meta name="keywords" content="{pigcms{$store['name']} {pigcms{:L('_OUT_TXT_')},{pigcms{$now_area.area_name},{pigcms{$now_circle.area_name},{pigcms{$config.seo_keywords}" />
	<else />
		<meta name="keywords" content="{pigcms{$store['name']} {pigcms{:L('_OUT_TXT_')},{pigcms{$config.seo_keywords}" />
	</if>
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<meta charset="utf-8">
	<link href="{pigcms{$static_path}css/shop_pc.css" rel="stylesheet"/>
	<!--<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2&s=1"></script>-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language={pigcms{:C('DEFAULT_LANG')}"></script>
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script src="{pigcms{$static_public}js/layer/layer.js"></script>
	<script src="{pigcms{$static_path}js/requestAnimationFrame.js"></script>
	<script src="{pigcms{$static_path}js/fly.js"></script>
	<script type="text/javascript">var store_id = '{pigcms{$store['id']}',store_theme = '{pigcms{$store['store_theme']}', is_pick = '{pigcms{$store['pick']}', delivery_price = '{pigcms{$store['delivery_price']|floatval}', pack_alias = '{pigcms{$store["pack_alias"]}', store_long = '{pigcms{$store.long}',store_lat = '{pigcms{$store.lat}',static_path = "{pigcms{$static_path}", ajax_goods="{pigcms{:U('Store/ajax_goods')}", cookie_index = 'foodshop_cart_{pigcms{$store["id"]}', cart_url = "/shop/order/{pigcms{$store['id']}.html",ExtraPirceName = "{pigcms{$config.extra_price_alias_name}",open_extra_price = Number("{pigcms{$config.open_extra_price}");</script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
    <script src="{pigcms{$static_path}js/shop_menu.js"></script>
	<!--[if lte IE 9]>
	<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
	<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
	<![endif]-->
</head>
<body style="background: #f5f5f5;">
	<section class="shoptop">
		<div class="shopto_top">
			<div class="w1200 clr">
				<div class="fl clr">
					<span class="fl">{pigcms{$shop_select_address}</span>
					<a href="/shop/change.html" class="fl">[{pigcms{:L('_SWITCH_ADDRESS_')}]</a>
				</div>
				<if condition="empty($user_session)">
					<div class="fr">
						<span><a href="{pigcms{:UU('Index/Login/index')}">{pigcms{:L('_B_D_LOGIN_LOGIN1_')}</a> | <a href="{pigcms{:UU('Index/Login/reg')}">{pigcms{:L('_B_D_LOGIN_REG2_')}</a></span>
					</div>
				<else />
					<div class="fr">
						<span><a href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{$user_session.nickname}</a> | <a href="{pigcms{:UU('Index/Login/logout')}">{pigcms{:L(_LOGOUT_TXT_)}</a></span>
					</div>
				</if>
			</div>
		</div>
		<div class="shopto_end shopto_end2">
			<div class="w1200 clr">
				<div class="fl img">
					<a href="/"><img src="{pigcms{$config.site_logo}" width=180 height=51></a>
				</div>
				<div class="link fl">
					<a href="/shop.html" class="on">{pigcms{:L('_HOME_TXT_')}</a><span>|</span><a href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{:L('_MY_ORDER_')}</a>
				</div>
				<div class="fr">
					<input type="text" placeholder="{pigcms{:L('_SEARCH_FOOD_')}" id="keyword" value="{pigcms{$keyword}">
					<button id="search" style="cursor: pointer;">{pigcms{:L('_SEARCH_TXT_')}</button>
				</div>
			</div>
		</div>
	</section>
	<section class="details">
		<div class="w1200 clr">
			<div class="fl parent">
				<div class="img fl">
					<img src="{pigcms{$store['image']}">
				</div>
				<div class="pl15 clr">
					<div class="title clr">
						<h2>{pigcms{$store['name']}</h2>
						<if condition="$store['is_close']">
							<span class="no">{pigcms{:L('_NO_BUSINESS_')}</span>
						<else />
							<span class="yes">{pigcms{:L('_AT_BUSINESS_')}</span>
						</if>
						<if condition="$store['isverify']">
                            <php>
                                if(C('DEFAULT_LANG') == 'zh-cn')
                                    $img_name = 'sjxq_rec.png';
                                else
                                    $img_name = 'en_rec.png';
                            </php>
							<img src="../static/images/{pigcms{$img_name}" style="float:left;margin-left:15px;" >
						</if>
					</div>
					<div class="score clr">
						<div class="fl">
							<div class="atar_Show">
								<p></p>
							</div>
							<span class="Fraction"><i>{pigcms{$store['star']}</i></span>
						</div>
						<span class="fl">{pigcms{:replace_lang_str(L('_MONTH_SALE_NUM_'),$store['month_sale_count'])}</span>
					</div>
					<div class="time">{pigcms{:L('_RECE_TIME_')}：{pigcms{$store['time']}</div>
				</div>
				<div class="trans">
					<div class="trans_n">
						<ul>
							<li>
								<span class="fl">{pigcms{:L('_SHOP_ADDRESS_')}：</span>
								<div class="p62">{pigcms{$store['adress']}</div> 
							</li>
							<li>
								<span class="fl">{pigcms{:L('_SHOP_PHONE_')}：</span>
								<div class="p62">{pigcms{$store['phone']}</div> 
							</li>
							<li>
								<span class="fl">{pigcms{:L('_DIST_SERVICE_')}：</span>
								<div class="p62">{pigcms{$store['deliver_name']}</div> 
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="fr give">
				<ul class="clr">
					<!--li>
						<h2>${pigcms{$store['delivery_price']|floatval}</h2>
						<p>{pigcms{:L('_MIN_DELI_PRICE_')}</p>
					</li-->
					<li>
						<h2>${pigcms{$store['delivery_money']|floatval}</h2>
						<p>{pigcms{:L('_DELI_PRICE_')}</p>
					</li>
                    <li>
                        <h2>${pigcms{$store['pack_fee']}</h2>
                        <p>{pigcms{:L('_PACK_PRICE_')}</p>
                    </li>
					<li>
						<h2>{pigcms{$store['delivery_time']}{pigcms{:L('_MINUTES_TXT_')}</h2>
						<p>{pigcms{:L('_DELI_TIME_')}</p>
					</li>
				</ul>
			</div>
		</div>
	</section>
	
	<section class="variety">
		<div class="w1200 clr">
			<div class="fl vleft">
				<if condition="$keyword">
					<div class="search-tip">
						<p>{pigcms{:L('_FIND_TXT_')}<span class="keyword">“{pigcms{$keyword}”</span>{pigcms{:L('_RELATED_FOOD_')}<span class="count">{pigcms{$count}</span>个</p>
					</div>
					<else />
					<div class="vlefttop">
						<div class="clr change">
							<a href="/shop/{pigcms{$store['id']}.html" class="on">{pigcms{:L('_PRODUCT_TXT_')}</a>
							<a href="/shop/comment/{pigcms{$store['id']}.html" >{pigcms{:L('_EVALUATE_TXT_')}</a>
						</div>
						<div class="Selling Shoplist_top clr">
							<div class="fr">
								<a href="/shop/{pigcms{$store['id']}.html" <if condition="$sort eq 0">class="on"</if>>{pigcms{:L('_DEFAULT_SORT_')}</a>
								<a href="/shop/{pigcms{$store['id']}.html?sort=1" <if condition="$sort eq 1">class="on"</if>>{pigcms{:L('_SALE_VOL_')}<i></i></a>
								<a href="/shop/{pigcms{$store['id']}.html?sort=2" <if condition="$sort eq 2">class="on"</if>>{pigcms{:L('_PRICE_TXT_')}<i></i></a>
							</div>
						</div>
					</div>
					<if condition="$sort eq 0">
						<div class="vleftend clr">
							<volist name="product_list" id="row">
								<a href="javascript:void(0)" <if condition="$key eq 0">class="on"</if> data-cat_id="{pigcms{$row['cat_id']}">{pigcms{$row['cat_name']} </a>
							</volist>
						</div>
					</if>
				</if>
				<div class="varietylist">
					<volist name="product_list" id="rowset">
					<div class="slist varietylist-{pigcms{$rowset['cat_id']}" data-cat_id="{pigcms{$rowset['cat_id']}">
						<if condition="$rowset['cat_id']">
						<div class="Selling clr">
							<div class="fl">{pigcms{$rowset['cat_name']} <if condition="$rowset['sort_discount']"><span style="font-size:14px;color:red">{pigcms{$rowset['sort_discount']}折</span></if></div> 
						</div>
						</if>
						<div class="Sell_list">
							<ul class="clr">
								<volist name="rowset['product_list']" id="vo">
									<li>
										<a href="javascript:void(0)" style="cursor: default;">
											<if condition="$vo['product_image']">
											<div class="img" data-goods_id="{pigcms{$vo['product_id']}" data-index="{pigcms{$vo['product_id']}" data-has_format="{pigcms{$vo['has_format']}" style="cursor: pointer;">
												<if condition="$store['store_theme']">
													<img src="{pigcms{$vo['product_image']}" style="height:190px;width:190px;"/>
												<else />
													<img src="{pigcms{$vo['product_image']}" style="padding:18px 0;height:105px;width:190px;"/>
												</if>
											</div>
											</if>
											<div class="text">
												<dl>
													<dd class="clr top">
                                                        <php>
                                                            $font_s = '';
                                                            if(strlen($vo['product_name']) >= 30){
                                                                $font_s = 'style="font-size:14px"';
                                                            }

                                                        </php>
                                                        <h2 {pigcms{$font_s}>{pigcms{$vo['product_name']}</h2>
													</dd>
													<dd class="clr middle">
														<div class="fl">{pigcms{:replace_lang_str(L('_MONTH_SALE_NUM_'),$vo['product_sale'])}  <if condition="$vo['stock'] neq -1 AND $vo['stock'] lt 10">{pigcms{:L('_STOCK_TXT_')}{pigcms{$vo['stock']}{pigcms{$vo['unit']}</if></div>
														<div class="fr bs-up">(<i>{pigcms{$vo['product_reply']}</i>)</div>
													</dd>
													<dd class="clr end">
														<div class="fl" <if condition="$vo['is_seckill_price'] eq 1 ">style="line-height:18px"</if>>
															<if condition="$vo['is_seckill_price'] eq 1">
															<span><i class="imit_i">{pigcms{:L('_LIMIT_PRICE_')}:</i>${pigcms{$vo['product_price']|floatval}<if condition="$vo.extra_pay_price gt 0 AND $config.open_extra_price eq 1 AND $vo.has_format eq false">+{pigcms{$vo.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></span>
															<br>
															<del style="ne-height: 16px;float: left;margin-left: 0px;">{pigcms{:L('_ORIGIN_PRICE_')}:${pigcms{$vo['o_price']|floatval}</del>
															<else />
															<span>${pigcms{$vo['product_price']|floatval}<if condition="$vo.extra_pay_price gt 0 AND $config.open_extra_price eq 1 AND $vo.has_format eq false">+{pigcms{$vo.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if>
															</span>
															</if>
														</div>
														<div style="cursor: pointer;" class="fr click" data-has_format="{pigcms{$vo['has_format']}" data-index="{pigcms{$vo['product_id']}" data-goods_id="{pigcms{$vo['product_id']}" data-price="{pigcms{$vo['product_price']|floatval}" data-packing_charge="{pigcms{$vo['packing_charge']|floatval}" data-stock="{pigcms{$vo['stock']}" data-name="{pigcms{$vo['product_name']}" data-extra_pay_price="{pigcms{$vo.extra_pay_price}" ></div>
													</dd>
												</dl>
											</div>
										</a>
										<if condition="$vo['is_seckill_price'] eq 1">
										<div class="imit">{pigcms{:L('_LIMIT_TIME_DISCOUNT_')}</div>
										</if>
									</li>
								</volist>
							</ul>
						</div> 
					</div>
					</volist>
				</div>
			</div>
			
			<div class="fr vright">
				<div class="vright_top">
					<h2>{pigcms{:L('_SHOP_NOTICE_')}</h2>
					<div class="text">{pigcms{$store['store_notice']}</div> 
				</div>
				<div class="vright_middle">
					<div class="activity">
						<dl>
							<if condition="isset($store['coupon_list']['system_newuser']) AND $store['coupon_list']['system_newuser']">
								<dd>
									<span class="fl platform">首</span>
									<div class="a_text">平台首单
										<volist name="store['coupon_list']['system_newuser']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['system_minus']) AND $store['coupon_list']['system_minus']">
								<dd>
									<span class="fl reduce">减</span>
									<div class="a_text">平台
										<volist name="store['coupon_list']['system_minus']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['delivery']) AND $store['coupon_list']['delivery']">
								<dd>
									<span class="fl red">惠</span>
									<div class="a_text">配送费
									<volist name="store['coupon_list']['delivery']" id="vo">
										满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
									</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['discount']) AND $store['coupon_list']['discount']">
								<dd>
									<span class="fl zhe">折</span>
									<div class="a_text">店内全场{pigcms{$store['coupon_list']['discount']}折</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['newuser']) AND $store['coupon_list']['newuser']">
								<dd>
									<span class="fl business">首</span>
									<div class="a_text">店铺首单
										<volist name="store['coupon_list']['newuser']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
							<if condition="isset($store['coupon_list']['minus']) AND $store['coupon_list']['minus']">
								<dd>
									<span class="fl ticket">减</span>
									<div class="a_text">店铺
										<volist name="store['coupon_list']['minus']" id="vo">
											满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
										</volist>
									</div>
								</dd>
							</if>
						</dl>
					</div>
				</div>
				<div class="vright_end" id="biz-map">
				</div>
			</div>
		</div>
	</section>
	
	<include file="Public:footer"/>
	<!-- 导航 -->
	<section class="scan">
		<ul>
			<li class="code">
				<div class="display">
					<h2>{pigcms{:L('_SCAN_QR_CODE_')}</h2>
					<p>{pigcms{:L('_ATTENTION_TO_WECHAT_')}</p>
					<img src="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'shop','id'=>$store['id']))}" width=122 height=122>
				</div>
			</li>
			<li class="Return"></li>
		</ul>
	</section>
	
	<!-- 弹窗 -->
	<div class="Popup">
		<h2 class="title">{pigcms{:L('_PRODUNT_INTRO_')}</h2>
		<div class="Popup_n clr"></div>
		<a href="javascript:void(0)" class="gb"></a>
	</div>
	<div class="mask"></div>
	<!-- 弹窗 -->
	
	<!-- 购物车 -->
	<div class="car">
		<div class="cartop clr">
			<span class="fl">{pigcms{:L('_CART_TXT_')}</span>
			<a href="javascript:void(0)" class="fr empty">{pigcms{:L('_CLEAR_TXT_')}</a>
		</div>
		<div class="carmiddle clr"><ul></ul></div>
		<div class="carend">
			<div class="fl carleft">
				<span class="mark"></span>
				<div class="common clr" style="display: none;">
					<span class="fl">{pigcms{:L('_TOTAL_TXT_')}</span>
					<span class="fr">$<i id="total_price">35</i></span>
				</div>
				<i class="amount" style="display: none;"></i>
			</div>
			<if condition="$store['pick']">
				<div class="tencer">{pigcms{:L('_CART_EMPTY_')}</div>
			<else />
				<div class="tencer">{pigcms{:replace_lang_str(L('_NUM_DELI_PRICE_'),$store['delivery_price']|floatval)}</div>
			</if>
			<form action="/shop/order/{pigcms{$store['id']}.html" method="post" id="post_cart">
				<input type="hidden" name="foodshop_cart" id="foodshop_cart"/>
			</form>
		</div>
	</div>
	<!-- 购物车 -->
</body>
</html>