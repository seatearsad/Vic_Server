<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>{pigcms{$now_special.name}</title>     
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}/layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>	
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/special.css?213"/>
		<script type="text/javascript" charset="utf-8">
			var getCouponUrl = "{pigcms{:U('ajax_get_coupon_by_ids')}";
			var getShopUrl = "{pigcms{:U('ajax_get_shop_by_ids')}";
			var userPhone = "{pigcms{$user_session.phone}";
			var receiveCouponUrl = "{pigcms{:U('Systemcoupon/had_pull')}";
			var LoginUrl = "{pigcms{:U('Login/index')}";
			var BindPhoneUrl = "{pigcms{:U('My/bind_user')}";
			var hasLogin = <if condition="$user_session">true<else/>false</if>;
			<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/special.js?210" charset="utf-8"></script>
	</head>
	<!--body style="max-width:640px;margin:0 auto;"-->
	<body>
		<div class="container pageSliderHide" style="background:{pigcms{$now_special.bgcolor};max-width:640px;margin:0 auto;padding-bottom:8px;">
			<if condition="$now_special['image']">
				<section class="custom-image-swiper">
					<img src="{pigcms{$now_special.image}"/>
				</section>
			</if>
			<if condition="$now_special['coupon']">
				<section class="coupon_list clearfix" data-coupon="{pigcms{$now_special.coupon_id}"></section>
			</if>		
			<div class="addCategoryBtn col-{pigcms{:count($now_special['product_list'])}" <if condition="count($now_special['product_list']) eq 1">style="display:none;"</if>>
				<ul>
					<volist name="now_special['product_list']" id="vo">
						<li data-product="{pigcms{$now_special['product_id_arr'][$key]}">{pigcms{$vo.name}</li>
					</volist>
				</ul>
			</div>
			<div class="productBox"></div>
		</div>
		<script id="productTpl" type="text/html">
			{{# for(var i in d){ }}
				<div class="app-fields ui-sortable productRow cat-{{ nowIndex }} link-url" data-url="{pigcms{:U('Shop/index')}#shop-{{ d[i].id }}" {{# if(d[i].is_close){ }}style="opacity:0.6;"{{# } }}>
					<div class="control-group product_dealcard">
						<div class="dealcard-img imgbox">
							<img src="{{ d[i].image }}" alt="{{ d[i].name }}">
							{{# if(d[i].is_close){ }}<div class="closeTip">休息中</div>{{# } }}
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
								<span>月售{{ d[i].month_sale_count }}单</span>
								{{# if(d[i].delivery){ }}
									<em class="location-right">{{ d[i].delivery_time }}分钟</em>
								{{# }else{ }}
									<em class="location-right">门店自提</em>
								{{# } }}
							</div>
							{{# if(d[i].delivery){ }}
								<div class="price">
									<span>起送价 ${{ d[i].delivery_price }}</span><span class="delivery">配送费 ${{ d[i].delivery_money }}</span>
									{{# if(d[i].delivery_system){ }}
										<em class="location-right">平台配送</em>
									{{# }else{ }}
										<em class="location-right">商家配送</em>
									{{# } }}
								</div>
							{{# } }}
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
										<div class="more">{{ d[i].coupon_count }}个活动</div>
									{{# } }}
								</div>
							{{# } }}
						</div>
					</div>
				</div>
			{{# } }}
		</script>
		<script id="couponTpl" type="text/html">
			{{# for(var i in d){ }}
				<a href="javascript:;" class="couponRow {{# if(d[i].can == false){ }}coupon_on1{{# } }}" data-id="{{ d[i].id }}" data-name="{{ d[i].name }}" data-order_money="{{ d[i].order_money }}" data-discount="{{ d[i].discount }}">
					<p class="coupon_name pull-left"><span>{{ d[i].name }}</span></p>
					<p class="coupon_monery pull-left">{{ d[i].discount }}</p>
					<p class="coupon_use pull-left oneline">满{{ d[i].order_money }}元可用</p>
					<span class="icon2"></span>
				</a>
			{{# } }}
		</script>
		<script type="text/javascript">
			window.shareData = {  
				"moduleName":"Special",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Special/index',array('id'=>$now_special['pigcms_id']))}",
				"tTitle": "{pigcms{$now_special.name}",
				"tContent": "{pigcms{$now_special.desc}"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>