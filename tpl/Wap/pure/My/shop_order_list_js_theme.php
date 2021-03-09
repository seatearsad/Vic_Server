<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<script id="ShopOrderListItemTpl" type="text/html">
    {{# for(var i = 0, len = d.length; i < len; i++){ }}
    <dd id="my_order_{{d[i].order_id }}">
        <dl>
            <dd class="order-num" onclick="window.location.href='/wap.php?c=Shop&a=classic_shop&shop_id={{d[i].store_id}}'"><span id="ord_num">{{d[i].name}}</span>
                <!---->
            </dd>
            <dd class="dealcard dd-padding" onclick="window.location.href = '{{d[i].order_url}}';">
                <div class="dealcard-img imgbox" >
                    <img src="{{d[i].image}}" style="width:100%;height:100%;">
                </div>
                <div class="dealcard-block-right">
                    <div class="">{{d[i].num}} Items(s)</div>
                    <div>{{d[i].create_time_show}}</div>
                    <div class="total_price">${{d[i].real_total_price}}</div>
                </div>
                <div class="go_btn">
                </div>
            </dd>
            <dd class="dealbutton">
                {{# if ((d[i].status>3) &&(d[i].status<7)) { }}
                    <div class="button_block">
    <!--                    <div class="round_button">Reorder</div>-->
                        <div class="status_str">Cancelled</div>
                    </div>
                {{# }else{ }}
                    {{# if (d[i].statusLog>=6) { }}
                        {{# if (d[i].statusLog>6) { }}
                            <div class="button_block">
            <!--                    <div class="round_button">Reorder</div>-->
                                <div class="img_ratings" style="width:{{ d[i].rate_score*16 }}px;"></div>
                                <div class="status_str">{{ d[i].statusLogName }}</div>
                            </div>
                        {{# }else{ }}
                            <div class="button_block">
            <!--                    <div class="round_button">Reorder</div>-->
                                {{# var url = "wap.php?g=Wap&c=My&a=shop_feedback";
                                    url += '&type=shop&order_id='+d[i].order_id;
                                }}
                                <div class="round_button" onclick="location.href='{{ url }}'">Rate Order</div>
                                <div class="status_str">{{ d[i].statusLogName }}</div>
                            </div>
                        {{# } }}
                    {{# }else{ }}
                        {{# if (d[i].paid=="0") { }}
                            {{# var url = "wap.php?g=Wap&c=Pay&a=check";
                                 url += '&type=shop&order_id='+d[i].order_id;
                            }}
                            <div class="button_block">
                                <div class="round_button count_down" style="right: 20px;position: absolute;" onclick="location.href='{{ url }}'" data-time="{{d[i].create_time }}" data-id="{{d[i].order_id }}" data-jet="{{d[i].jetlag }}">Continue Payment</div>
                            </div>
                        {{# }else{ }}
                            <div class="button_block">
                                <div class="status_str use_main_color">{{ d[i].statusLogName }}</div>
                            </div>
                        {{# } }}
                    {{# } }}
                {{# } }}
            </dd>
        </dl>
    </dd>
    {{# } }}
</script>
<script id="ShopOrderListItemOldTpl" type="text/html">
    {{# for(var i = 0, len = d.length; i < len; i++){ }}
    <dd id="my_order_{{d[i].order_id }}">
        <dl>
            <dd class="order-num"><span id="ord_num">{{d[i].name}}{{d[i].real_orderid }}</span>
                <!---->
            </dd>
            <dd class="dealcard dd-padding">
                <div class="dealcard-img imgbox" onclick="window.location.href = '{{d[i].order_url}}';">
                    <img src="{{d[i].image}}" style="width:100%;height:100%;">
                </div>
                <div class="dealcard-block-right" onclick="window.location.href = '{{d[i].order_url}}';">
                    <div class="">{{d[i].name}}</div>
                    <div class="total_price">Total:{{d[i].price}}</div>
                    <small>Total item:{{d[i].num}}&nbsp;&nbsp;{{d[i].create_time_show}} </small>
                </div>
                <div class="order_btn">
                </div>
            </dd>
        </dl>
    </dd>
    {{# } }}
</script>
<script id="ShopOrderListItemTpl2" type="text/html">

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
		<dd class="page-link" data-url="shop&shop_id={{ d[i].id }}" data-url-type="openRightFloatWindow" {{# if(d[i].is_close){ }}style="opacity:0.6;"{{# } }}>
			<div class="dealcard-img imgbox">
				{{# if(d[i].isverify == 1){ }}
                <php>
                    if(C('DEFAULT_LANG') == 'zh-cn')
                    $img_name = '<img src="./static/images/kd_rec.png" style="width: 41px;height: 15px;position: absolute;z-index: 99;margin: 2px 0 0 0;">';
                    else
                    $img_name = '<img src="./static/images/en_rec.png" style="width: 15px;height: 14px;position: absolute;z-index: 99;margin: 2px 0 0 0;">';

                    echo $img_name;
                </php>
					<!--img src="./static/images/kd_rec.png" style="width: 41px;height: 15px;position: absolute;z-index: 15;margin: 2px 0 0 0;"-->
				{{# } }}
				<img src="{{ d[i].image }}" alt="{{ d[i].name }}">
				{{# if(d[i].is_close){ }}<div class="closeTip">{pigcms{:L('_AT_REST_')}</div>{{# } }}
			</div>
			<div class="dealcard-block-right">
				<div class="brand">{{ d[i].name }}<em class="location-right">{{# if(user_long != '0'){ }}{{ d[i].range }}{{# } }}</em></div>
				<div class="title {{# if(!d[i].delivery){ }}pick{{# } }}">
					<span class="star"><i class="full"></i><i class="full"></i><i class="full"></i><i class="half"></i><i></i></span>
                    <!--span>{{getLangStr('_MONTH_SALE_NUM_',d[i].month_sale_count)}}</span-->
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