<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{:L('_VIC_NAME_')} - {pigcms{:L('_OUT_TXT_')}</title>
        <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
        <meta name="description" content="{pigcms{$config.seo_description}" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/shopBase.css"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?220" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0" charset="utf-8"></script>		
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?220" charset="utf-8"></script>
		<script type="text/javascript">
			var locationClassicHash = 'shop-{pigcms{$_GET.shop_id}';
			
			var user_long = '0',user_lat  = '0';
			var user_address='';
			var ajax_url_root = "{pigcms{$config.site_url}/wap.php?c=Shop&a=";
			var check_cart_url = "{pigcms{$config.site_url}/wap.php?c=Shop&a=confirm_order";
			var ajax_map_url = "{pigcms{$config.site_url}/index.php?g=Index&c=Map&a=suggestion&city_id={pigcms{$config.now_city}";
			var get_route_url = "{pigcms{:U('Group/get_route')}";
			var baiduToGcj02Url = "{pigcms{:U('Userlonglat/baiduToGcj02')}";
			var city_id="{pigcms{$config.now_city}";
			var cat_url="",sort_url="",type_url="";
			var noAnimate= true;
			var userOpenid="{pigcms{$_SESSION.openid}";
			var shopShareUrl = "{pigcms{$config.site_url}{pigcms{:U('Shop/index',array('openid'=>$_SESSION['openid']))}&shop-id=";
			var shopReplyUrl = "{pigcms{$config.site_url}/index.php?g=Index&c=Reply&a=ajax_get_list&order_type=3&parent_id=";
		</script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}shop/js/shopClassicBaseNew.js?t={pigcms{$_SERVER.REQUEST_TIME}" charset="utf-8"></script>
	</head>
        <style>
            #container{
                width: 100%;
                padding-top: 60px;
            }
            #shopHeader{
                position: relative;
                background: none;
            }
            #shopBanner{
                background: none;
                margin-top: 0;
            }
            #shopTitle{
                color: #333;
            }
            .backBtn::after{
                border-top: 2px solid #999;
                border-left: 2px solid #999;
            }
            #shopBanner .leftIco{
                left:30px;
            }
            #shopBanner .leftIco div{
                width: 60px;
                height: 60px;
            }
            #shopBanner .text{
                height: 70px;
                padding-left: 110px;
            }
            #shopBanner .text div{
                color:#999;
                height: 20px;
                line-height: 20px;
            }
            .is_close{
                margin:10px 20px;
                width: 50px;
                height: 20px;
                line-height: 20px;
                background-color: #ffa52d;
                text-align: center;
                border-radius: 2px;
                font-size: .8em;
                color: white;
            }
            .close_s{
                background-color: silver;
            }
            .shop_info{
                position: absolute;
                top: 10px;
                right: 10px;
                width: 30px;
                height: 30px;
                background-image: url("{pigcms{$static_path}shop/images/store_info.png");
                background-size: 100%;
                cursor: pointer;
            }
            #shopBanner::after{
                content: none;
            }
            #shopProductLeftBar2{
                width: 100%;
                display: flex;
                background: none;
                border-top: 1px solid silver;
                border-bottom: 1px solid silver;
            }
            .sub_left,.sub_right{
                width: 6%;
                height: 50px;
                cursor: pointer;
                opacity: 0;
                background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
                background-size: auto 16px;
                background-repeat: no-repeat;
                background-position: center;
            }
            .sub_left{
                -moz-transform: scaleX(-1);
                -webkit-transform: scaleX(-1);
                -o-transform: scaleX(-1);
                transform: scaleX(-1);
            }
            #shopProductLeftBar2 dd span{
                background: none;
            }
            #shopProductLeftBar2 dl{
                margin: 0;
                padding: 0;
                width: 88%;
                height: 50px;
                white-space: nowrap;
                float:left;
                overflow-y:hidden;
                overflow-x: visible;
                display: inline;
            }
            #shopProductLeftBar2 dd{
                text-align: center;
                color: silver;
                display: inline-block;
                height: 100%;
                line-height: 50px;
                padding: 0 10px;
                font-size: 1.2em;
                box-sizing: initial;
                background: none;
            }
            #shopProductLeftBar2 dd.active{
                background: none;
            }
            #shopProductLeftBar2 dd.active span{
                color: #ffa52d;
            }
            #shopProductRightBar2{
                width: 96%;
                float: none;
                margin: 0 auto;
                padding-top: 20px;
            }
            #shopMenuBar li.active{
                color: #ffa52d;
            }
            #shopMenuBar li.active::after{
                background-color: #ffa52d;
            }
            #shopProductRightBar2 .cat_name{
                padding-left: 0;
                text-align: center;
                color: silver;
                font-size: 1.2em;
            }
            #shopProductRightBar2 dl{
                background: none;
            }
            #shopProductRightBar2 ul{
                background-color: white;
            }
            #shopProductRightBar2 li .position_img{
                width: 100px;
            }
            #shopProductRightBar2 li .product_text{
                margin-left: 110px;
                height: 60px;
            }
            #shopProductRightBar2 li .product_btn,#shopProductCartBox .product_btn.plus,#shopProductCartBox .product_btn.min{
                border: 1px solid #ffa52d;
            }
            #shopProductRightBar2 li .product_btn.plus::after,#shopProductCartBox .product_btn.plus::after{
                left: 12px;
                background-color: #ffa52d;
            }
            #shopProductRightBar2 li .product_btn.min::before,#shopProductRightBar2 li .product_btn.plus::before,#shopProductCartBox .product_btn.min::before,#shopProductCartBox .product_btn.plus::before{
                left: 8px;
                background-color: #ffa52d;
            }
            #shopProductCartBox dd .cartRight .price,#shopProductRightBar2 li .product_btn,#shopProductRightBar2 li .product_text .price{
                color: #ffa52d;
            }
            #shopProductCart #cartNumber,#shopProductCart #cartInfo .cart,#shopProductCart #checkCart,.shopCartFly{
                background-color: #ffa52d;
            }
            #shopProductCart{
                background-color: #949494;
            }
            #shopProductCartBox dt{
                border-left: 0;
                font-weight: bold;
            }
            #shopReplyBox ul li.active{
                border-color: #ffa52d;
                color: #ffa52d;
            }
            #shopReplyBox .star{
                float: right;
            }
            #shopReplyBox .right .time{
                float: none;
            }
        </style>
	<body>
    <include file="Public:header"/>
    <div id="container">
		<div id="pageList" class="pageDiv" <if condition="$config['shop_show_footer']">style="padding-bottom:56px;"</if>>
			<section id="listHeader" class="roundBg">
				<div id="listBackBtn" class="listBackBtn hide"><div></div></div>
				<div id="locationBtn" class="page-link" data-url="address" data-url-type="openRightFloatWindow">
					<span class="location"></span>
					<span id="locationText">{pigcms{:L('_BEING_POSITION_')}</span>
					<span class="go"></span>
				</div>
				<div id="searchBtn" class="listSearchBtn page-link" data-url="shopsearch"><div></div></div>
			</section>
			<section id="listBanner" class="banner">
				<div class="swiper-container swiper-container1">
					<div class="swiper-wrapper"></div>
					<div class="swiper-pagination swiper-pagination1"></div>
				</div>
			</section>
			<section id="listSlider" class="slider">
				<div class="swiper-container swiper-container2" style="height:178px;">
					<div class="swiper-wrapper"></div>
					<div class="swiper-pagination swiper-pagination2"></div>
				</div>
			</section>
			<section id="listRecommend" class="recommend"></section>
			<section id="listNavBox" class="navBox">
				<ul>
					<li class="dropdown-toggle caret category" data-nav="category">
						<span class="nav-head-name">{pigcms{:L('_SHOP_CLASSIFICATION_')}</span>
					</li>
					<li class="dropdown-toggle caret sort" data-nav="sort">
						<span class="nav-head-name">{pigcms{:L('_INTELLIGENT_SORTING_')}</span>
					</li>
					<li class="dropdown-toggle caret type subway" data-nav="type">
						<span class="nav-head-name">{pigcms{:L('_TYPE_TXT_')}</span>
					</li>
				</ul>
				<div class="dropdown-wrapper category">
					<div class="dropdown-module">
						<div class="scroller-wrapper">
							<div id="dropdown_scroller" class="dropdown-scroller">
								<div>
									<ul>
										<li class="category-wrapper" style="min-height:200px;">
											<ul class="dropdown-list"></ul>
										</li>
										<li class="sort-wrapper">
											<ul class="dropdown-list"></ul>
										</li>
										<li class="type-wrapper">
											<ul class="dropdown-list"></ul>
										</li>
									</ul>
								</div>
							</div>
							<div id="dropdown_sub_scroller" class="dropdown-sub-scroller"><div></div></div>
						</div>
					</div>
				</div>
			</section>
			<section id="listNavPlaceHolderBox">
			</section>
			<section id="storeList">
				<dl class="dealcard"></dl>
				<div id="storeListLoadTip">{pigcms{:L('_LOADING_TXT_')}</div>
			</section>
			<section class="shade"></section>
			<php>if(!$config['shop_show_footer']){$no_footer = true;$no_small_footer = true;}</php>
			<include file="Public:footer"/>
		</div>
		<div id="pageShop" class="pageDiv">
			<section id="shopHeader">
				<div id="backBtn" class="backBtn"></div>
				<div id="shopTitle"></div>
                <div class="is_close"></div>
                <div class="shop_info" data-nav="merchant"></div>
				<!--div id="searchBtn" class="searchBtn"><div></div></div-->
			</section>
			<section id="shopBanner">
				<div class="leftIco">
					<div id="shopIcon"></div>
				</div>
				<div class="text">
                    <div class="star">
                        <i class="full"></i><i></i>
                    </div>
					<div id="deliveryText"></div>
					<div id="shopNoticeText"></div>
				</div>
				<!--div class="discount">
					<div class="noticeBox"><div class="notice"><div></div></div></div>
					<span id="shopCouponText"></span>
				</div-->
			</section>
			<section id="shopMenuBar">
				<ul>
					<li class="caret product active" data-nav="product">{pigcms{:L('_PRODUCT_TXT_')}</li>
					<li class="caret reply" data-nav="reply">{pigcms{:L('_EVALUATE_TXT_')}</li>
					<li class="caret merchant" data-nav="merchant">{pigcms{:L('_SHOP_TXT_')}</li>
				</ul>
			</section>
			<section id="shopCatBar" style="display:none;">	
				<div class="title">
                    {pigcms{:L('_ALL_CLASSIF_')}
				</div>
				<div class="content">
					<ul></ul>
				</div>
			</section>
			<section id="shopContentBar">
				<div id="shopProductBox">
					<div id="shopProductBottomBar"><ul class="clearfix"></ul><div id="shopProductBottomLine"></div></div>
					<div id="shopProductLeftBar2">
                        <div class="sub_left"></div>
                        <dl></dl>
                        <div class="sub_right"></div>
                    </div>
					<div id="shopProductRightBar2"><dl></dl></div>
					<div id="shopProductCartShade"></div>
					<div id="shopProductCartBox"></div>
					<div id="shopProductCart">
						<div id="cartInfo" class="cartLeft" style="display:none;">
							<div class="cart">
								<div id="cartNumber">0</div>
							</div>
							<div class="price">{pigcms{:L('_TOTAL_TXT_')}$<span id="cartMoney">0</span></div>
						</div>
						<div id="emptyCart">
							<div class="cart"></div>{pigcms{:L('_CART_EMPTY_')}
						</div>
						<div id="checkCart" style="display:none;">{pigcms{:L('_GOOD_CHOICE_')}</div>
						<div id="checkCartEmpty">{pigcms{:L('_MIN_DELI_PRICE_')}</div>
					</div>
				</div>
				<div id="shopReplyBox" style="display:none">
					<div id="shopReplyDiv">
						<ul class="clearfix">
							<li class="active" data-tab="">{pigcms{:L('_ALL_TXT_')}(<em>0</em>)</li>
							<li data-tab="good">{pigcms{:L('_SATISFIED_TXT_')}(<em>0</em>)</li>
							<li data-tab="wrong">{pigcms{:L('_SATISFIED_NOT_')}(<em>0</em>)</li>
						</ul>
						<dl></dl>
						<div id="noReply">{pigcms{:L('_NOT_EVALUATION_')}</div>
						<div id="showMoreReply">{pigcms{:L('_LOAD_MORE_')}</div>
					</div>
				</div>
				<div id="shopMerchantBox">
					<dl id="shopMerchantDescBox">
						<dd class="phone more">{pigcms{:L('_SHOP_PHONE_')}</dd>
						<dd class="address more page-link"><span></span>{pigcms{:L('_SHOP_ADDRESS_')}</dd>
						<dd class="openTime">{pigcms{:L('_BUSINESS_TIME_')}</dd>
						<dd class="deliveryType">{pigcms{:L('_DIST_SERVICE_')}</dd>
						<dd class="merchantNotice">{pigcms{:L('_SHOP_NOTICE_')}</dd>
					</dl>
					<if condition="!$merchant_link_showOther">
						<dl id="shopMerchantLinkBox">
							<dd class="more link-url" data-url="{pigcms{:U('My/shop_order_list')}"><span></span>{pigcms{:L('_MY_OUT_ORDER_')}</dd>
						</dl>
					</if>
					<dl id="shopMerchantCouponBox">
						<dd>{pigcms{:L('_DIST_SERVICE_')}</dd>
						<dd>{pigcms{:L('_DIST_TIME_')}</dd>
					</dl>
				</div>
				<div id="shopPageShade" style="display:none;"></div>
				<div id="shopPageCatShade"></div>
				<div id="shopDetailPage" style="display:none;">
					<div class-s="scrollerBox">
						<div id="shopDetailpageClose" class="closeBtn"><div></div></div>
						<div id="shopDetailPageImgbox" class="swiper-container swiper-container-productImg">
							<div class="swiper-wrapper"></div>
							<div class="swiper-pagination swiper-pagination-productImg"></div>
						</div>
						<div id="shopDetailPageTitle">
							<div class="title">{pigcms{:L('_PRODUCT_NAME_')}</div>
							<div class="desc">{pigcms{:L('_PRODUCT_DESC_')}</div>
						</div>
						<div id="shopDetailPageFormat">{pigcms{:L('_PRODUCT_STOCK_')}</div>
						<div id="shopDetailPageBar" class="clearfix">
							<div class="fl" id="shopDetailPagePrice">{pigcms{:L('_PRICE_TXT_')}</div>
							<div class="fr">
								<div id="shopDetailPageBuy">{pigcms{:L('_ADD_TO_CART_')}</div>
								<div id="shopDetailPageNumber" style="display:none;">
									<div class="product_btn plus"></div>
									<div class="product_btn number">0</div>
									<div class="product_btn min"></div>
								</div>
							</div>
						</div>
						<div id="shopDetailPageLabel">
							<div class="tip">{pigcms{:L('_WANT_TO_NOT_')}<div class="question"></div></div>
							<div id="shopDetailPageLabelBox"></div>
						</div>
						<div id="shopDetailPageContent">
							<div class="title">{pigcms{:L('_PRODUCT_DESC_')}</div>
							<div class="content">{pigcms{:L('_CONTENT_TXT_')}</div>
						</div>
					</div>
				</div>
			</section>
		</div>
		<div id="pageMap" class="pageDiv">
			<div id="shopDetailMapClose" class="closeBtn"><div></div></div>
			<div id="shopDetailMapBiz"></div>
			<div id="shopDetailMapBar">
				<span id="shopDetailMapAddress">{pigcms{:L('_ADDRESS_TXT_')}</span>
				<a class="btn right" id="shopDetailMapAddressGo">{pigcms{:L('_LOOK_ROUTE_')}</a>
			</div>
		</div>
		<div id="pageCat" class="pageDiv">
			<section id="catHeader">
				<div id="catBackBtn" class="backBtn"></div>
				<span id="catTitle">{pigcms{:L('_CLASSIFICATION_TXT_')}</span>
				<div id="catSearchBtn" class="listSearchBtn page-link" data-url="shopSearch"><div></div></div>
			</section>
			<div id="pageCatNav"></div>
			<section class="shade"></section>
			<section id="storeList">
				<dl class="dealcard"></dl>
				<div id="storeListLoadTip">{pigcms{:L('_LOADING_TXT_')}</div>
			</section>
		</div>
		<div id="pageLoadTipShade" class="pageLoadTipBg">
			<div id="pageLoadTipBox" class="pageLoadTipBox">
				<div class="pageLoadTipLoader">
					<div style="background-image:url({pigcms{$config.shop_load_bg});"><!--img src="{pigcms{$static_path}shop/images/pageTipImg.png"/--></div>
				</div>
			</div>
		</div>
		<div id="pageAddress" class="pageDiv">
			<div id="pageAddressHeader" class="searchHeader">
				<div id="pageAddressBackBtn" class="searhBackBtn"></div>
				<div id="pageAddressSearch" class="searchBox">
					<div class="searchIco"></div>
					<input type="text" id="pageAddressSearchTxt" class="searchTxt" placeholder="{pigcms{:L('_PLEASE_INPUT_ADDRESS_')}" autocomplete="off"/>
					<div class="delIco" id="pageAddressSearchDel"><div></div></div>
				</div>
				<div id="pageAddressSearchBtn" class="searchBtn">{pigcms{:L('_SEARCH_TXT_')}</div>
			</div>
			<div id="pageAddressContent" class="searchAddressList">
				<div id="pageAddressLocationList">
					<div class="title">{pigcms{:L('_CURR_ADDRESS_')}</div>
					<dl class="content">
						<dd data-long="" data-lat="" data-name="">
							<div class="name"></div>
						</dd>
					</dl>
				</div>
				<div id="pageAddressUserList">
					<div class="title">{pigcms{:L('_MY_ADDRESS_')}</div>
					<dl class="content"></dl>
				</div>
			</div>
			<div id="pageAddressSearchContent" class="searchAddressList" style="display:none;">
				<dl class="content"></dl>
			</div>
		</div>
		<div id="pageShopSearch" class="pageDiv">
			<div id="pageShopSearchHeader" class="searchHeader">
				<div id="pageShopSearchBackBtn" class="searhBackBtn"></div>
				<div id="pageShopSearchBox" class="searchBox">
					<div class="searchIco"></div>
					<input type="text" id="pageShopSearchTxt" class="searchTxt" placeholder="{pigcms{:L('_INPUT_SHOP_NAME_')}" autocomplete="off"/>
					<div class="delIco" id="pageShopSearchDel"><div></div></div>
				</div>
				<div id="pageShopSearchBtn" class="searchBtn">{pigcms{:L('_SEARCH_TXT_')}</div>
			</div>
			<div id="storeList" style="display:none;">
				<dl class="dealcard"></dl>
				<div id="storeListLoadTip">{pigcms{:L('_LOADING_TXT_')}</div>
			</div>
		</div>
		<include file="Shop:classic_js_theme"/>
    </div>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Shop",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Shop/index')}",
				"tTitle": "{pigcms{$config.shop_alias_name|default="快店"} - {pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};

            var base_width = $('#shopProductLeftBar2').find('dl').width();
            var act_width = 0;
            var move_size = 0;
            $('#shopProductLeftBar2').find('dl').scroll(function () {
                move_size = $(this).scrollLeft();
                setSubMove();
            });

            function setSubMove() {
                if(move_size > 0){
                    if($('.sub_left').css('opacity') == 0){
                        $('.sub_left').css('opacity',1);
                    }
                }else{
                    $('.sub_left').css('opacity',0);
                }

                if(base_width < act_width-move_size-10){
                    if($('.sub_right').css('opacity') == 0){
                        $('.sub_right').css('opacity',1);
                    }
                }else{
                    $('.sub_right').css('opacity',0);
                }
                // console.log(act_width-move_size+'--'+base_width);
            }
		</script>
		{pigcms{$shareScript}
	</body>
</html>