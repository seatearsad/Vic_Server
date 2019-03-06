<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<script id="listBannerSwiperTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<div class="swiper-slide">
			<a href="{{ d[i].url }}">
				<img src="{{ d[i].pic }}" alt="{{ d[i].name }}"/>
			</a>
		</div>
	{{# } }}
</script>
<script id="listSliderSwiperTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		{{# if(i%8 == 0){ }}
			<div class="swiper-slide">
				<ul class="icon-list">
		{{# } }}
					<li class="icon">
						<a href="{{ d[i].url }}">
							<span class="icon-circle">
								<img src="{{ d[i].pic }}">
							</span>
							<span class="icon-desc">{{ d[i].name }}</span>
						</a>
					</li>
		{{# if(i != 0 && ((i+1)%8 == 0 || i+1 == len)){ }}		
				</ul>
			</div>
		{{# } }}
	{{# } }}
</script>
<script id="listRecommendTpl" type="text/html">
	<div class="recommendBox">
		{{# if(d[0]){ }}
			<div class="recommendLeft link-url" data-url="{{ d[0].url }}">
				<img src="{{ d[0].pic }}" alt="{{ d[0].name }}"/>
			</div>
		{{# } }}
		<div class="recommendRight">
			{{# if(d[1]){ }}
				<div class="recommendRightTop link-url" data-url="{{ d[1].url }}">
					<img src="{{ d[1].pic }}" alt="{{ d[1].name }}"/>
				</div>
			{{# } }}
			{{# if(d[2]){ }}
				<div class="recommendRightBottom link-url" data-url="{{ d[2].url }}">
					<img src="{{ d[2].pic }}" alt="{{ d[2].name }}">
				</div>
			{{# } }}
		</div>
	</div>
</script>
<script id="listShopTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd class="page-link" data-url="shop-{{ d[i].id }}" data-url-type="openRightFloatWindow" {{# if(d[i].is_close){ }}style="opacity:0.6;"{{# } }}>
			<div class="dealcard-img imgbox">
				{{# if(d[i].isverify == 1){ }}
                <php>
                    if(C('DEFAULT_LANG') == 'zh-cn')
                    $img_name = '<img src="./static/images/kd_rec.png" style="width: 41px;height: 15px;position: absolute;z-index: 99;margin: 2px 0 0 0;">';
                    else
                    $img_name = '<img src="./static/images/en_rec.png" style="width: 15px;height: 14px;position: absolute;z-index: 99;margin: 2px 0 0 0;">';

                    echo $img_name;
                </php>
					<!--img src="./static/images/kd_rec.png" style="    width: 41px;height: 15px;position: absolute;z-index: 15;margin: 2px 0 0 0;"-->
				{{# } }}
				<img src="{{ d[i].image }}" alt="{{ d[i].name }}">
				{{# if(d[i].is_close){ }}<div class="closeTip">{pigcms{:L('_AT_REST_')}</div>{{# } }}
			</div>
			<div class="dealcard-block-right">
				<div class="brand">{{ d[i].name }}<em class="location-right">{{# if(user_long != '0'){ }}{{ d[i].range }}{{# } }}</em></div>
				<div class="title {{# if(!d[i].delivery){ }}pick{{# } }}">
					<span class="star">
						{{#
							var tmpScore = parseFloat(d[i].star);
							if(tmpScore>0){
								for(var tmpI=0;tmpI<5;tmpI++){ if(tmpScore >= tmpI+1){ }}<i class="full"></i>{{# }else if(tmpScore > tmpI){ }}<i class="half"></i>{{# }else{ }}<i></i>{{# } }
							}else{
						}}
							<i class="full"></i><i class="full"></i><i class="full"></i><i class="half"></i><i></i>
						{{#
							}
						}}
					</span>
                    &nbsp;<!--span style="color:#9f9f9f;">
                        {{ getLangStr('_SALE_NUM_ORDER_',d[i].month_sale_count) }}
                    </span><br/>
                    &nbsp;<span style="color:#9f9f9f;">
                        {{getLangStr('_MONTH_SALE_NUM_',d[i].merchant_store_month_sale_count)}}
                    </span-->
					{{# if(d[i].delivery){ }}
						<!--em class="location-right">{{ d[i].delivery_time }}分钟</em-->
					{{# }else{ }}
						<em class="location-right">{pigcms{:L('_SELF_DIST_')}</em>
					{{# } }}
				</div>
				{{# if(d[i].delivery){ }}
					<div class="price">
						<!--span>{pigcms{:L('_MIN_DELI_PRICE_')} ${{ d[i].delivery_price }}</span-->
                        <span class="delivery">{pigcms{:L('_DELI_PRICE_')} ${{ d[i].delivery_money }}</span>
                        <span class="delivery">{pigcms{:L('_PACK_PRICE_')} ${{ d[i].pack_fee }}</span>
						{{# if(d[i].delivery_system){ }}
							<em class="location-right">{pigcms{:L('_PLAT_DIST_')}</em>
						{{# }else{ }}
							<em class="location-right">{pigcms{:L('_SHOP_DIST_')}</em>
						{{# } }}
					</div>
				{{# } }}
			</div>
				{{# if(d[i].coupon_count > 0){ }}
					<div class="coupon {{# if(d[i].coupon_count > 2){ }}hasMore{{# } }}">
						<ul>
							{{# var tmpCouponList = parseCoupon(d[i].coupon_list,'array');  }}
							{{# if(tmpCouponList['invoice']){ }}
								<li><em class="merchant_invoice"></em>{{ tmpCouponList['invoice'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['discount']){ }}
								<li><em class="merchant_discount"></em>{{ tmpCouponList['discount'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['minus']){ }}
								<li><em class="merchant_minus"></em>{{ tmpCouponList['minus'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['newuser']){ }}
								<li><em class="newuser"></em>{{ tmpCouponList['newuser'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['delivery']){ }}
								<li><em class="delivery"></em>{{ tmpCouponList['delivery'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['system_minus']){ }}
							<li><em class="system_minus"></em>{{ tmpCouponList['system_minus'] }}</li>
							{{# } }}
							{{# if(tmpCouponList['system_newuser']){ }}
								<li><em class="system_newuser"></em>{{ tmpCouponList['system_newuser'] }}</li>
							{{# } }}
						</ul>
						{{# if(d[i].coupon_count > 2){ }}
							<div class="more">{{ getLangStr('_EVENT_NUM_',d[i].coupon_count) }}</div>
						{{# } }}
					</div>
				{{# } }}
		</dd>
	{{# } }}
</script>
<script id="shopProductLeftBarTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd id="shopProductLeftBar-{{ d[i].cat_id }}" data-cat_id="{{ d[i].cat_id }}" {{# if(i==0){ }}class="active"{{# } }}>{{ d[i].cat_name }}</dd>
	{{# } }}
</script>
<script id="shopProductRightBarTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		{{# if(d[i].product_list.length > 0){ }}
			<dd id="shopProductRightBar-{{ d[i].cat_id }}" data-cat_id="{{ d[i].cat_id }}">
				<div class="cat_name">{{ d[i].cat_name }}　{{# if(d[i].sort_discount){ }}<div class="cat_discount">{{ d[i].sort_discount }}折</div><div class="cat_discount bred">折扣不同享</div>{{# } }}</div>
				<ul>
					{{# for(var j = 0, jlen = d[i].product_list.length; j < jlen; j++){ }}
						<li class="product_{{ d[i].product_list[j].product_id }}" data-product_id="{{ d[i].product_list[j].product_id }}" data-product_price="{{ d[i].product_list[j].product_price }}" data-product_name="{{ d[i].product_list[j].product_name }}" data-stock="{{ d[i].product_list[j].stock }}" data-packing_charge="{{ d[i].product_list[j].packing_charge }}"  data-extra_price="{{ d[i].product_list[j].extra_pay_price }}" data-extra_price_name="{{ d[i].product_list[j].extra_pay_price_name }}">
							<div class="position_img">
								{{# if(d.length >= 3){ }}
									<img src="{pigcms{$static_public}images/blank.gif" data-original="{{ d[i].product_list[j].product_image }}" class="lazy"/>
								{{# }else{ }}
									<img src="{{ d[i].product_list[j].product_image }}"/>
								{{# } }}
							</div>
							<div class="product_text">
								<div class="title">{{ d[i].product_list[j].product_name }}</div>
								<!--div class="sale">{{ getLangStr('_MONTH_SALE_NUM_',d[i].product_list[j].product_sale) }} {{ getLangStr('_PRAISE_TXT_') }}{{ d[i].product_list[j].product_reply }}</div-->
                                <div class="sale">{{ getLangStr('_PRAISE_TXT_') }}{{ d[i].product_list[j].product_reply }}</div>
								{{# if(d[i].product_list[j].has_format){ }}
									<div class="price">
                                        ${{ d[i].product_list[j].product_price }} {{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}
                                        {{# if(d[i].product_list[j].deposit_price > 0){ }}
                                        <span class="sale" style="text-decoration: none;">({pigcms{:L('_DEPOSIT_TXT_')}:${{ d[i].product_list[j].deposit_price }})</span>
                                        {{# } }}
                                    </div>
								{{# }else{ }}
									<div class="price">
                                        ${{ d[i].product_list[j].product_price }}{{# if(d[i].product_list[j].is_seckill_price){ }}<span>${{ d[i].product_list[j].o_price }}</span>{{# } }}{{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}
                                        {{# if(d[i].product_list[j].deposit_price > 0){ }}
                                        <span class="sale" style="text-decoration: none;">({pigcms{:L('_DEPOSIT_TXT_')}:${{ d[i].product_list[j].deposit_price }})</span>
                                        {{# } }}
                                    </div>
								{{# } }}
								{{# if(d[i].product_list[j].is_seckill_price){ }}
									<div class="skill_discount" style="margin-top: 5px;">{pigcms{:L('_LIMIT_TIME_DISCOUNT_')}</div>
								{{# } }}
							</div>
							{{# if(d[i].product_list[j].has_format){ }}
								<div class="product_btn" >
									{pigcms{:L('_OPTIONAL_SPEC_')}
								</div>
							{{# }else{ }}
								<div class="product_btn plus"></div>
								<div class="bgPlusBack"></div>
								<div class="bgMinBack"></div>
							{{# } }}
						</li>
					{{# } }}
				</ul>
			</dd>
		{{# } }}
	{{# } }}
</script>
<script id="shopProductTopBarTpl" type="text/html">
		<li data-cat_id="0" class="active">{pigcms{:L('_ALL_CLASSIF_')}</li>
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-cat_id="{{ d[i].cat_id }}" data-name="{{ d[i].cat_name }}" data-discount="{{ d[i].sort_discount }}">{{ d[i].cat_name }}{{# if(d[i].sort_discount){ }}<span>({{ d[i].sort_discount }}折优惠)</span>{{# } }}</li>
	{{# } }}
</script>
<script id="shopProductBottomBarTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		{{# if(d[i].product_list.length > 0){ }}
			{{# for(var j = 0, jlen = d[i].product_list.length; j < jlen; j++){ }}
				<li class="product_{{ d[i].product_list[j].product_id }} product_cat_{{ d[i].cat_id }}" data-product_id="{{ d[i].product_list[j].product_id }}" data-product_price="{{ d[i].product_list[j].product_price }}" data-product_name="{{ d[i].product_list[j].product_name }}" data-stock="{{ d[i].product_list[j].stock }}" data-packing_charge="{{ d[i].product_list[j].packing_charge }}" data-extra_price="{{ d[i].product_list[j].extra_pay_price }}" data-extra_price_name="{{ d[i].product_list[j].extra_pay_price_name }}">
					<div class="position_img">
						{{# if(d.length >= 3){ }}
							<img src="{pigcms{$static_public}images/blank.gif" data-original="{{ d[i].product_list[j].product_image }}" class="lazy"/>
						{{# }else{ }}
							<img src="{{ d[i].product_list[j].product_image }}"/>
						{{# } }}
					</div>
					<div class="product_text">
						<div class="title">{{ d[i].product_list[j].product_name }}</div>
						{{# if(d[i].product_list[j].is_seckill_price){ }}
						<div class="skill_discount">{pigcms{:L('_LIMIT_TIME_DISCOUNT_')}</div>
						{{# } }}
                        <!--div class="sale">{{ getLangStr('_MONTH_SALE_NUM_',d[i].product_list[j].product_sale) }} {{ getLangStr('_PRAISE_TXT_') }}{{ d[i].product_list[j].product_reply }}</div-->
                        <div class="sale">{{ getLangStr('_PRAISE_TXT_') }}{{ d[i].product_list[j].product_reply }}</div>
						{{# if(d[i].product_list[j].has_format){ }}
							<div class="price">${{ d[i].product_list[j].product_price }} {{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}</div>
						{{# }else{ }}
							<div class="price">${{ d[i].product_list[j].product_price }}{{# if(d[i].product_list[j].is_seckill_price){ }}<span>${{ d[i].product_list[j].o_price }}</span>{{# } }}{{# if(d[i].product_list[j].extra_pay_price>0&&open_extra_price==1){ }}+{{ d[i].product_list[j].extra_pay_price }}{{ d[i].product_list[j].extra_pay_price_name }}{{# } }}</div>
						{{# } }}
					</div>
					{{# if(d[i].product_list[j].has_format){ }}
						<div class="product_btn">
                            {pigcms{:L('_OPTIONAL_SPEC_')}
						</div>
					{{# }else{ }}
						<div class="product_btn plus"></div>
					{{# } }}
				</li>
			{{# } }}
		{{# } }}
	{{# } }}
</script>
<script id="listCategoryListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-cat_id="{{ d[i].cat_id }}" data-cat_url="{{ d[i].cat_url }}" {{# if(d[i].son_list && d[i].son_list.length > 0){ }}data-has-sub="true"{{# }else{ }} onclick="list_location($(this));return false;" {{# } }} class="listCat-{{ d[i].cat_url }} {{# if(d[i].son_list && d[i].son_list.length > 0){ }}right-arrow-point-right{{# } }} {{# if(i == 0){ }}active{{# } }}">
			<span data-name="{{ d[i].cat_name }}">{{ d[i].cat_name }}</span>
			{{# if(d[i].son_list && d[i].son_list.length > 0){ }}
				<span class="quantity"><b></b></span>		
				<div class="sub_cat hide">
					<ul class="dropdown-list sub-list">
						<li class="listCat-{{ d[i].cat_url }} isSon" data-cat_id="{{ d[i].cat_id }}" data-cat_url="{{ d[i].cat_url }}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{{ d[i].cat_name }}">{pigcms{:L('_ALL_TXT_')}</span></div></li>
						{{# for(var j = 0, jlen = d[i].son_list.length; j < jlen; j++){ }}
							<li class="listCat-{{ d[i].son_list[j].cat_url }} isSon" data-cat_id="{{ d[i].son_list[j].cat_id }}" data-cat_url="{{ d[i].son_list[j].cat_url }}" onclick="list_location($(this));return false;"><div><span class="sub-name" data-name="{{ d[i].son_list[j].cat_name }}">{{ d[i].son_list[j].cat_name }}</span></div></li>
						{{# } }}
					</ul>
				</div>
			{{# } }}
		</li>
	{{# } }}
</script>
<script id="listSortListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-sort_url="{{ d[i].sort_url }}" {{# if(i == 0){ }}class="active"{{# } }} onclick="list_location($(this));return false;"><span data-name="{{ d[i].name }}">{{ d[i].name }}</span><em></em></li>
	{{# } }}
</script>
<script id="listTypeListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<li data-type_url="{{ d[i].type_url }}" {{# if(i == 0){ }}class="active"{{# } }} onclick="list_location($(this));return false;"><span data-name="{{ d[i].name }}">{{ d[i].name }}</span><em></em></li>
	{{# } }}
</script>
<script id="listAddressListTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd data-long="{{ d[i].long }}" data-lat="{{ d[i].lat }}" data-name="{{ d[i].street }}" data-id="{{ d[i].id }}">
			<div class="name">{{ d[i].street }} {{ d[i].house }}</div>
			<div class="desc">{{ d[i].name }} {{ d[i].phone }}</div>
		</dd>
	{{# } }}
</script>
<script id="productFormatTpl" type="text/html">
	{{# for(var i in d){ }}
		<div class="row clearfix">
			<div class="left">{{ d[i].name }}</div>
			<div class="right fl">
				<ul>
					{{# var k = 0; for(var j in d[i].list){ }}
						<li class="fl {{# if(k == 0){ }}active{{# } }}" data-spec_list_id="{{ d[i].list[j].id }}"  data-spec_id="{{ d[i].list[j].sid}}">{{ d[i].list[j].name }}</li>
					{{#  k++; } }}
				</ul>
			</div>
		</div>
	{{# } }}
</script>
<script id="productPropertiesTpl" type="text/html">
	{{# for(var i in d){ }}
		<div class="row clearfix productProperties_{{ d[i].id }}" data-label_name="{{ d[i].name }}" data-num="{{ d[i].num }}">
			<div class="left">{{ d[i].name }}</div>
			<div class="right fl">
				<ul>
					{{# var k = 0; for(var j in d[i].val){ }}
						<li class="fl {{# if(k == 0 && d[i].num == 1){ }}active{{# } }}" data-label_list_id="{{ i }}" data-label_id="{{ j }}">{{ d[i].val[j] }}</li>
					{{#  k++; } }}
				</ul>
			</div>
		</div>
	{{# } }}
</script>
<script id="productSwiperTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<div class="swiper-slide">
			<img src="{{ d[i].url }}"/>
		</div>
	{{# } }}
</script>
<script id="productCartBoxTpl" type="text/html">
	<dl>
		<dt class="clearfix">{pigcms{:L('_CART_TXT_')}<div id="shopProductCartDel">{pigcms{:L('_CLEAR_TXT_')}</div></dt>
		{{#
			var packCharge = 0;
			for(var i in d){
				packCharge += d[i].productPackCharge*d[i].count;
		}}
			<dd class="clearfix cartDD" data-product_id="{{ d[i].productId }}" data-product_price="{{ d[i].productPrice }}" data-product_name="{{ d[i].productName }}" data-stock="{{ d[i].productStock }}" data-packing_charge="{{ d[i].productPackCharge }}" data-extra_price="{{ d[i].productExtraPrice }}" data-extra_price_name="{{ d[i].productExtraPriceName }}">
				<div class="cartLeft {{# if(d[i].productParam.length > 0){ }}hasSpec{{# } }}">
					<div class="name">{{ d[i].productName }}</div>
					{{# if(d[i].productParam.length > 0){ }}
						{{# 
							var tmpParam = [];
							for(var j in d[i].productParam){
								if(d[i].productParam[j].type == 'spec'){
									tmpParam.push(d[i].productParam[j].name);
								}else{
									for(var k in d[i].productParam[j].data){
										tmpParam.push(d[i].productParam[j].data[k].name);
									}
								}
							}
							var tmpParamStr = tmpParam.join(' ');
						}}
						<div class="spec" data-product_id="{{ i }}">{{ tmpParamStr }}</div>
					{{# } }}
				</div>
				<div class="cartRight">
					<div class="product_btn plus cart"></div>
					<div class="product_btn number cart productNum-{{ i }}">{{ d[i].count }}</div>
					<div class="product_btn min cart"></div>
					<div class="price">${{ d[i].productPrice }}{{#  if(d[i].productExtraPrice>0&&open_extra_price ){ }}+{{ d[i].productExtraPrice }}{{ d[i].productExtraPriceName }}{{# } }}</div>
				</div>
			</dd>
		{{# 
			}
			if(packCharge > 0){
		}}
			<dd>{{ nowShop.store.pack_alias }}&nbsp;<font color="red">$<span id="packChargeCount">{{ packCharge }}</span></font></dd>
		{{#		
			}
		}}
	</dl>
</script>
<script id="shopReplyTpl" type="text/html">
	{{# for(var i = 0, len = d.length; i < len; i++){ }}
		<dd>
			<div class="avatar">
				<img src="{{# if(d[i].avatar!= ''){}}{{ d[i].avatar }}{{# }else{ }}/static/images/portrait.jpg{{# } }}"/>
			</div>
			<div class="right">
				<div class="nickname">{{ d[i].nickname }}<div class="time">{{ d[i].add_time_hi }}</div></div>
				<div class="star">
					{{# for(var j=1;j<=5;j++){ }}{{# if(d[i].score >= j){ }}<i class="full"></i>{{# }else{ }}<i></i>{{# } }}{{# } }}
				</div>
				<div class="content">{{ d[i].comment }}</div>
				{{# if(d[i].goods){ }}
					{{# var tmpGoods = d[i].goods; }}
					<div class="recommend clearfix">
						{{# for(var k in tmpGoods){ }}
							<div>{{ tmpGoods[k] }}</div>
						{{# } }}
					</div>
				{{# } }}
				{{# if(d[i].merchant_reply_time != '0'){ }}
					<div class="reply">
						<div class="title">{pigcms{:L('_SHOP_RETURN_')}:<div class="time">{{ d[i].merchant_reply_time_hi }}</div></div>
						<div class="reply_content">{{ d[i].merchant_reply_content }}</div>
					</div>
				{{# } }}
			</div>
		</dd>
	{{# } }}
</script>