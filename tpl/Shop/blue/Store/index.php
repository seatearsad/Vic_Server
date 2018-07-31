<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>{pigcms{:L('_OUT_TXT_')} {pigcms{:L('_VIC_NAME_')}</title>
	<if condition="$now_area">
		<meta name="keywords" content="{pigcms{$now_area.area_name},{pigcms{$now_circle.area_name},{pigcms{$config.seo_keywords}" />
	<else />
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
	</if>
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<meta charset="utf-8">
	<link href="{pigcms{$static_path}css/shop_pc.css" rel="stylesheet"/>
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script type="text/javascript">var  ajax_list = "{pigcms{:U('Store/ajax_list')}";</script>
	<script src="{pigcms{$static_path}js/shop_store_list.js"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
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
						<span><a href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{$user_session.nickname}</a> | <a href="{pigcms{:UU('Index/Login/logout')}">{pigcms{:L('_LOGOUT_TXT_')}</a></span>
					</div>
				</if>
			</div>
		</div>
		<div class="shopto_end">
			<div class="w1200 clr">
				<div class="fl img">
					<a href="/"><img src="{pigcms{$config.site_logo}" width=180 height=51></a>
				</div>
				<div class="link fl">
					<a href="/shop.html" class="on">{pigcms{:L('_HOME_TXT_')}</a><span>|</span><a href="{pigcms{:UU('User/Index/shop_list')}">{pigcms{:L('_MY_ORDER_')}</a>
				</div>
				<div class="fr">
					<input type="text" placeholder="" id="keyword" value="">
					<button id="search" style="cursor: pointer;">{pigcms{:L('_SEARCH_TXT_')}</button>
				</div>
			</div>
		</div>
	</section>
	<section class="fication" <if condition="$keyword">style="display:none"</if>>
		<div class="w1200 clr">
			<div class="fication_n clr">
				<div class="fication_top fl">{pigcms{:L('_SHOP_CLASSIFICATION_')}：</div>
				<div class="fication_end">
					<ul class="clr">
						<volist name="category_list" id="rowset">
						<li <if condition="$rowset['cat_id'] eq $cat_fid">class="on"</if> data-cat_url="{pigcms{$rowset['cat_url']}" data-cat_id="{pigcms{$rowset['cat_id']}">
							<a href="/shop/{pigcms{$rowset['cat_url']}/{pigcms{$sort_url}/{pigcms{$type_url}"><span>{pigcms{$rowset['cat_name']}</span></a>
						</li>
						</volist>
					</ul>
					<volist name="category_list" id="rowset">
					<if condition="$rowset['son_list']">
					<div class="fication_list fication_list_{pigcms{$rowset['cat_id']}" <if condition="$rowset['cat_id'] eq $cat_fid">style="display:block"</if>>
						<dl class="clr">
							<dd><a href="/shop/{pigcms{$rowset['cat_url']}/{pigcms{$sort_url}/{pigcms{$type_url}" <if condition="0 eq $cat_id AND $rowset['cat_id'] eq $cat_fid">class="on"</if> data-cat_url="{pigcms{$rowset['cat_url']}">全部</a></dd>
							<volist name="rowset['son_list']" id="row">
								<dd><a href="/shop/{pigcms{$row['cat_url']}/{pigcms{$sort_url}/{pigcms{$type_url}" <if condition="$row['cat_id'] eq $cat_id">class="on"</if> data-cat_url="{pigcms{$row['cat_url']}">{pigcms{$row['cat_name']}</a></dd>
							</volist>
						</dl>
					</div>
					</if>
					</volist>
				</div>
			</div>
		</div>
	</section>
	<section class="Shoplist">
		<div class="w1200">
			<div class="search-tip" <if condition="empty($keyword)">style="display:none"</if>>
				<p>找到<span class="keyword">“{pigcms{$keyword}”</span>相关{pigcms{$config.shop_alias_name}<span class="count"></span>个</p>
			</div>
			<div class="Shoplist_top clr" <if condition="$keyword">style="display:none"</if>>
				<div class="fl sort">
					<a href="/shop/{pigcms{$cat_url}/juli/{pigcms{$type_url}" <if condition="$sort_url eq 'juli'">class="on"</if> data-sort_url="juli">{pigcms{:L('_DEFAULT_SORT_')}</a>
					<a href="/shop/{pigcms{$cat_url}/sale_count/{pigcms{$type_url}" <if condition="$sort_url eq 'sale_count'">class="on"</if> data-sort_url="sale_count">{pigcms{:L('_SALE_VOL_')}<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/send_time/{pigcms{$type_url}" <if condition="$sort_url eq 'send_time'">class="on"</if> data-sort_url="send_time">{pigcms{:L('_DIST_TIME_')}<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/basic_price/{pigcms{$type_url}" <if condition="$sort_url eq 'basic_price'">class="on"</if> data-sort_url="basic_price">{pigcms{:L('_MIN_DELI_PRICE_')}<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/score_mean/{pigcms{$type_url}" <if condition="$sort_url eq 'score_mean'">class="on"</if> data-sort_url="score_mean">{pigcms{:L('_SCORE_HIGHEST_')}<i></i></a>
					<a href="/shop/{pigcms{$cat_url}/create_time/{pigcms{$type_url}" <if condition="$sort_url eq 'create_time'">class="on"</if> data-sort_url="create_time">{pigcms{:L('_NEW_RELEASE_')}<i></i></a>
				</div>
				<!--div class="fr deliver">
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/-1" style="padding:0px;background:white;"><span <if condition="$type_url eq -1">class="on"</if> data-type="-1">全部</span></a>
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/0" style="padding:0px;background:white;"><span <if condition="$type_url eq 0">class="on"</if> data-type="0">配送</span></a>
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/2" style="padding:0px;background:white;"><span <if condition="$type_url eq 2">class="on"</if> data-type="2">自提</span></a>
					<span <if condition="$type_url eq 3">class="on"</if> data-type="3">平台配送/自提</span>
					<span <if condition="$type_url eq 4">class="on"</if> data-type="4">商家配送/自提</span>
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/5" style="padding:0px;background:white;"><span <if condition="$type_url eq 5">class="on"</if> data-type="5">快递配送</span></a>
					<a href="/shop/{pigcms{$cat_url}/{pigcms{$sort_url}/1" style="padding:0px;background:white;"><span <if condition="$type_url eq 1">class="on"</if> data-type="1">平台配送</span></a>
				</div-->
			</div> 
			<div class="Shoplist_end">
				<ul class="clr navBox_list">
					<volist name="store_list" id="vo">
					<li>
						<a href="{pigcms{$vo['detail_url']}">
							<div class="fix">
							<div class="img">
								<img src="{pigcms{$vo['image']}" width=222 height=148>
								<!--div class="imgewm">
									<img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$vo['qrcode_url']}" width="78" height="78"/>
									<p>{pigcms{:L('_WECHAT_SCAN_TO_PHONE_')}</p>
								</div-->
							</div>
							<div class="text">
								<dl>
									<dd class="clr top">
										<h2 class="fl">{pigcms{$vo['name']}</h2>
										<span class="fr">{pigcms{$vo['range']}</span>
									</dd>
									<dd class="clr middle">
										<div class="fl">
											<div class="atar_Show">
												<p></p>
											</div>
											<span class="Fraction"><i>{pigcms{$vo['star']}</i></span>
				  						</div>
										<!--span class="fr">{pigcms{:replace_lang_str(L('_MONTH_SALE_NUM_'),$vo['merchant_store_month_sale_count'])}</span-->
				 					</dd>
                                    <dd class="clr middle">
                                        <span class="fr">{pigcms{:replace_lang_str(L('_MONTH_SALE_NUM_'),$vo['month_sale_count'])}</span>
                                    </dd>
									<if condition="$vo['delivery']">
									<dd class="clr end">
										<span class="r5" style="width: 200px;float:left;">{pigcms{:L('_MIN_DELI_PRICE_')}:$<i>{pigcms{$vo['delivery_price']}</i></span>
										<span class="r5" style="width: 200px;float:left;">{pigcms{:L('_DELI_PRICE_')}:$<i>{pigcms{$vo['delivery_money']}</i></span>
                                        <span class="r5" style="width: 200px;float:left;">{pigcms{:L('_PACK_PRICE_')}:$<i>{pigcms{$vo['pack_fee']}</i></span>
										<!--span class="fr">{pigcms{$vo['delivery_time']}分钟</span-->
									</dd>
									<else />
									<dd class="clr end">
										<span class="r5">人均消费:$<i>{pigcms{$vo['mean_money']}</i></span>
									</dd>
									</if>
								</dl>
							</div>
							<div class="list">
								<dl class="clr">
									<php>$tmp_num=0;</php>
									<if condition="$vo['isverify'] gt 0">
										<dd class="fl zheng">证</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['system_newuser'])">
									<dd class="fl platform">首</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['system_minus'])">
									<dd class="fl reduce">减</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['delivery'])">
									<dd class="fl red">惠</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['discount'])">
									<dd class="fl zhe">折</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['newuser'])">
									<dd class="fl business">首</dd>
										<php>$tmp_num++;</php>
									</if>
									<if condition="isset($vo['coupon_list']['minus']) AND $tmp_num lt 6">
									<dd class="fl ticket">减</dd>
									</if>
				
									<if condition="$vo['delivery'] eq 0">
										<dd class="fr express">{pigcms{:L('_SELF_DIST_')}</dd>
									<elseif condition="$vo['delivery_system']" />
										<dd class="fr platform">{pigcms{:L('_PLAT_DIST_')}</dd>
									<elseif condition="$vo['deliver_type'] eq 5" />
										<dd class="fr Since">{pigcms{:L('_EXPRESS_DELI_')}</dd>
									<else />
										<dd class="fr business">{pigcms{:L('_SHOP_DIST_')}</dd>
									</if>
								</dl>
							</div>
							</div>
							<div class="position">
								<h2 class="h2top">{pigcms{$vo['name']}</h2>
								<div class="activity">
									<dl>
				
										<if condition="isset($vo['system_newuser_text'])">
										<dd>
											<span class="fl platform">首</span>
											<div class="a_text">{pigcms{$vo['system_newuser_text']}</div>
										</dd>
										</if>
										<if condition="isset($vo['system_minus_text'])">
										<dd>
											<span class="fl reduce">减</span>
											<div class="a_text">{pigcms{$vo['system_minus_text']}</div>
										</dd>
										</if>
										<if condition="isset($vo['delivery_text'])">
										<dd>
											<span class="fl red">惠</span>
											<div class="a_text">{pigcms{$vo['delivery_text']}</div>
										</dd>
										</if>
										
										<if condition="isset($vo['coupon_list']['discount'])">
										<dd>
											<span class="fl zhe">折</span>
											<div class="a_text">店内全场{pigcms{$vo['coupon_list']['discount']}折</div>
										</dd>
										</if>
										<if condition="isset($vo['newuser_text'])">
										<dd>
											<span class="fl red">首</span>
											<div class="a_text">{pigcms{$vo['newuser_text']}</div>
										</dd>
										</if>
										<if condition="isset($vo['minus_text'])">
										<dd>
											<span class="fl ticket">减</span>
											<div class="a_text">{pigcms{$vo['minus_text']}</div>
										</dd>
										</if>
									</dl>
								</div>
				 				<div class="notice">
									<h2>{pigcms{:L('_SHOP_NOTICE_')}</h2>{pigcms{$vo['store_notice']}
								</div>
							</div> 
						</a>
					</li>
					</volist>
				</ul>
			</div>
			<if condition="$next_page">
			<a href="javascript:void(0)" class="Load" data-page="2">{pigcms{:L('_CLICK_ADD_MORE_')}</a>
			</if>
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
					<img src="{pigcms{$config.wechat_qrcode}" width=122 height=122>
				</div>
			</li>
			<li class="Return"></li>
		</ul>
	</section>
<script id="storeListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
	<li>
		<a href="{{ d.store_list[i].detail_url }}">
			<div class="fix">
			<div-- class="img">
				<img src="{{ d.store_list[i].image }}" width=222 height=148>
				<!--div class="imgewm">
					<img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{{ d.store_list[i].qrcode_url }}" width="78" height="78"/>
					<!-- img class="lazy_img" src="{{ d.store_list[i].qrcode_url }}" data-original="{{ d.store_list[i].qrcode_url }}" width="78" height="78"/ >
					<p>{pigcms{:L('_WECHAT_SCAN_TO_PHONE_')}</p>
				</div-->
			</div>
			<div class="text">
				<dl>
					<dd class="clr top">
						<h2 class="fl">{{ d.store_list[i].name }}</h2>
						<span class="fr">{{ d.store_list[i].range }}</span>
					</dd>
                    <dd class="clr middle">
                        <div class="fl">
                            <div class="atar_Show">
                                <p></p>
                            </div>
                            <span class="Fraction"><i>{{ d.store_list[i].star }}</i>分</span>
                        </div>
                        <!--span class="fr">月售{{ d.store_list[i].merchant_store_month_sale_count }}单</span-->
                    </dd>
                    <dd class="clr middle">
                        <span class="fr">{{getLangStr('_MONTH_SALE_NUM_',d.store_list[i].month_sale_count)}}</span>
                    </dd>
					{{# if(d.store_list[i].delivery){ }}
					<dd class="clr end">
						<span class="r5" style="width: 200px;float:left;">{pigcms{:L('_MIN_DELI_PRICE_')}:$<i>{{ d.store_list[i].delivery_price }}</i></span>
						<span class="r5" style="width: 200px;float:left;">{pigcms{:L('_DELI_PRICE_')}:$<i>{{ d.store_list[i].delivery_money }}</i></span>
                        <span class="r5" style="width: 200px;float:left;">{pigcms{:L('_PACK_PRICE_')}:$<i>{{ d.store_list[i].pack_fee }}</i></span>
						<!--span class="fr">{{ d.store_list[i].delivery_time }}分钟</span-->
					</dd>
					{{# }else{ }}
					<dd class="clr end">
						<span class="r5">人均消费:$<i>{{ d.store_list[i].mean_money }}</i></span>
					</dd>
					{{# } }}
				</dl>
			</div>
			<div class="list">
				<dl class="clr">
				{{# var tmp_num=0 }}
					{{# if(d.store_list[i].isverify > 0){ }}
						<dd class="fl zheng">证</dd>
						{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.system_newuser != undefined){ }}
					<dd class="fl platform">首</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.system_minus != undefined){ }}
					<dd class="fl reduce">减</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.delivery != undefined){ }}
					<dd class="fl red">惠</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.discount != undefined){ }}
					<dd class="fl zhe">折</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.newuser != undefined){ }}
					<dd class="fl business">首</dd>
					{{# tmp_num++ }}
					{{# } }}
					{{# if(d.store_list[i].coupon_list.minus != undefined&&tmp_num<6){ }}
					<dd class="fl ticket">减</dd>
					{{# } }}
						
					{{# if(!d.store_list[i].delivery){ }}
					<dd class="fr express">{pigcms{:L('_SELF_DIST_')}</dd>
					{{# } }}


					{{# if(d.store_list[i].delivery){ }}
						{{# if(d.store_list[i].delivery_system){ }}
							<dd class="fr platform">{pigcms{:L('_PLAT_DIST_')}</dd>
						{{# }else{ }}
							{{# if(d.store_list[i].deliver_type == 5){ }}
								<dd class="fr Since">{pigcms{：L('_EXPRESS_DELI_')}</dd>
							{{# }else{ }}
								<dd class="fr business">{pigcms{:L('_SHOP_DIST_')}</dd>
							{{# } }}
						{{# } }}
					{{# } }}
				</dl>
			</div>
			</div>
			<div class="position">
				<h2 class="h2top">{{ d.store_list[i].name }}</h2>
				<div class="activity">
					<dl>
						{{# var tmpCouponList = parseCoupon(d.store_list[i].coupon_list,'array');  }}

						{{# if(tmpCouponList['system_newuser']){ }}
						<dd>
							<span class="fl platform">首</span>
							<div class="a_text">{{ tmpCouponList['system_newuser'] }}</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['system_minus']){ }}
						<dd>
							<span class="fl reduce">减</span>
							<div class="a_text">{{ tmpCouponList['system_minus'] }}</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['delivery']){ }}
						<dd>
							<span class="fl red">惠</span>
							<div class="a_text">{{ tmpCouponList['delivery'] }}</div>
						</dd>
						{{# } }}
						{{# if(d.store_list[i].coupon_list.discount != undefined){ }}
						<dd>
							<span class="fl zhe">折</span>
							<div class="a_text">店内全场{{ d.store_list[i].coupon_list.discount }}折</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['newuser']){ }}
						<dd>
							<span class="fl red">首</span>
							<div class="a_text">{{ tmpCouponList['newuser'] }}</div>
						</dd>
						{{# } }}
						{{# if(tmpCouponList['minus']){ }}
						<dd>
							<span class="fl ticket">减</span>
							<div class="a_text">{{ tmpCouponList['minus'] }}</div>
						</dd>
						{{# } }}
					</dl>
				</div>
 				<div class="notice">
					<h2>{pigcms{:L('_SHOP_NOTICE_')}</h2>{{ d.store_list[i].store_notice }}
				</div>  
			</div> 
		</a>
	</li>
{{# } }}
</script>
</body>
</html>
