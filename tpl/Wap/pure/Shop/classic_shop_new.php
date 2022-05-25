<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <if condition="$config['site_favicon']">
            <link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
        </if>
		<title>{pigcms{:L('_VIC_NAME_')} - {pigcms{:L('_OUT_TXT_')}</title>
        <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
        <meta name="description" content="{pigcms{$config.seo_description}" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width,viewport-fit=cover"/>

<!--        <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, width=device-width, user-scalable=no">-->

        <meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/shopBase.css?v=1.93"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<!--        <script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>-->
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?220" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<!--		<script type="text/javascript" src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0" charset="utf-8"></script>		-->
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?2221" charset="utf-8"></script>
		<script type="text/javascript">
			var locationClassicHash = 'shop-{pigcms{$_GET.shop_id}';
			var storeUrl = "{pigcms{:U('Shop/classic_shop')}&shop_id={pigcms{$_GET.shop_id}";
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
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js?v=20" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}shop/js/shopClassicBaseNew.js?t={pigcms{$_SERVER.REQUEST_TIME}" charset="utf-8"></script>
        <include file="Public:facebook"/>
	</head>
        <style>
            *{
                margin: 0px;
                box-sizing: border-box;
                font-family: Helvetica;
                -moz-osx-font-smoothing: grayscale;
                font-size: 100%;
            }
            #container{
                width: 100%;
                max-width:640px;
                min-width:320px;
                margin:0 auto;

                /*height: 900px;*/
            }
            #shopHeader{
                display: flex;
                top: 0px;
                height: 60px;
                padding-top: 6px;
                width: 100%;
                max-width: 640px;
            }

            #shopBanner{
                width: 90%;
                height: 190px;
                left:5%;
                border-radius: 10px;
                border:2px solid #eeeeee;
                right:auto;
                background: #fff;
                margin-top: 0;
                margin-bottom: 10px;
                padding-top:0px;
            }
            #shopTitle{
                line-height: 102%;
                margin-left: 0px;
                margin-top: 10px;
                margin-bottom: 4px;
                margin-right: 50px;
                font-size: 21px;
                font-weight: bold;
                color: #000000;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                flex: 1 1 100%;
            }
            #shopTitle_Header{
                opacity: 1;
                font-size: 20px;
                padding: 6px 10px;
                text-overflow: ellipsis;
                overflow: hidden;
                white-space: nowrap;
                position: relative;
                flex: 1 1 100%;
            }
            #deliveryText{
                margin-bottom: 5px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            #deliveryText a{
                color: black;
                text-decoration: underline;
            }
            #shopNoticeText{
                margin-bottom: 3px;
                overflow: hidden;
                margin-right: 5px;
                line-height: 1.3;
                display: -webkit-box;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 2;
            }
            .backBtn {
                position: relative;
                width: 40px;
                height: 40px;
                top: 6px;
                left: 15px;
                background: #fff;
                border-radius: 20px;
                flex: 0 0 auto;
            }
            .backBtn::after{
                border-top: 2px solid #999;
                border-left: 2px solid #999;
                left:16px;
            }
            .searchBtn{
                background-image: url(/tpl/Static/blue/images/new/icon_shop_search.png);
                position: relative;
                width: 50px;
                height: 50px;
                line-height: 30px;
                top: 0px;
                right: 10px;
                text-align: center;
                color: white;
                padding: 0px;
                background-repeat: no-repeat;
                background-size: auto 80%;
                background-position: center;
                cursor: pointer;
                flex: 0 0 auto;
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
                padding-left: 10px;
                padding-top: 0px;
            }
            #shopBanner .text div{
                /*line-height: 1.5;*/
            }
            #shopBanner .text .star{
                height: 25px;
                margin-top: 5px;
            }
            .is_close{
                width: 5rem;
                height: 20px;
                line-height: 20px;
                background-color: #ffa52d;
                text-align: center;
                border-radius: 2px;
                font-size: 1em;
                color: white;
                position: absolute;
                top: 50px;
                left: 32px;
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
                height: 51px;
                width: 100%;
                display: flex;
                background: #f4f4f4;
                border-bottom: 1px solid silver;
                position: sticky;
                position: -webkit-sticky;
                top: 101px;
                z-index: 98;
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
            #shopContentBar{
                background: #f4f4f4;
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
            #shopProductRightBar2,#shopSearchResult{
                width: 100%;
                float: none;
                background: #f4f4f4;
                margin: 20px auto;
                padding: 5px 8px 5px 5px;
            }
            #shopMenuBar{
                height: 42px;
                margin-bottom: -1px;
                width: 100%;
                position: sticky;
                top:60px;
                z-index: 98;
            }
            #shopMenuBar li.active{
                color: #ffa52d;
                background-color: #f4f4f4;
            }
            #shopMenuBar li.active::after{
                background-color: #ffa52d;
            }
            #shopProductRightBar2 .cat_name,#shopSearchResult .cat_name{
                padding-left: 10px;
                text-align: left;
                color: black;
                font-size: 1.2em;
                border-radius: 10px 10px 0px 0px;
                background: white;
                margin-top: 10px;
                font-weight: bold;
            }
            #shopProductRightBar2 dl,#shopSearchResult dl{
                background: none;
                padding-bottom: 30px;
            }
            #shopProductRightBar2 ul,#shopSearchResult ul{
                background-color: white;
                border-radius: 0px 0px 10px 10px;
                margin-top: -1px;
            }
            #shopProductRightBar2 li .position_img,#shopSearchResult li .position_img{
                width: 80px;
                border-radius: 5px;
                background-position: center;
                background-size: cover;
                background-repeat: no-repeat;
            }
            #shopProductRightBar2 li .product_text,#shopSearchResult li .product_text{
                margin-left: 90px;
                height: 80px;
            }
            #shopProductRightBar2 li .product_btn,#shopProductCartBox .product_btn.plus,#shopProductCartBox .product_btn.min,#shopSearchResult li .product_btn{
                border: 1px solid #ffa52d;
            }
            #shopProductRightBar2 li .product_btn.plus::after,#shopProductCartBox .product_btn.plus::after,#shopSearchResult li .product_btn.plus::after{
                background-color: #ffa52d;
            }
            #shopProductRightBar2 li .product_btn.min::before,#shopProductRightBar2 li .product_btn.plus::before,
            #shopProductCartBox .product_btn.min::before,#shopProductCartBox .product_btn.plus::before,
            #shopSearchResult li .product_btn.min::before,#shopSearchResult li .product_btn.plus::before{
                background-color: #ffa52d;
            }
            #shopProductCartBox dd .cartRight .price,
            #shopProductRightBar2 li .product_text .price,
            #shopSearchResult li .product_text .price,

            #shopDetailPageBar .fl,
            .msg-option .btn{
                color: #ffa52d;
            }
            #shopSearchResult li .product_btn.number,
            #shopProductRightBar2 li .product_text .price,
            #shopProductRightBar2 li .product_btn,
            #shopSearchResult li .product_text .price,
            .msg-option .btn{
                color: #000;
            }

            #shopProductCart #cartNumber,
            #shopProductCart #cartInfo .cart,
            #shopProductCart #checkCart,
            .shopCartFly,
            #shopDetailPageBar #shopDetailPageBuy,
            #shopDetailMapBar .btn{
                background-color: #ffa52d;
            }
            #shopProductCart{
                background-color: #443e3e;
                padding-bottom: env(safe-area-inset-bottom);
                max-width: 640px;
            }
            #shopProductCartBox dt{
                border-left: 0;
                font-weight: bold;
            }
            #shopReplyBox ul li.active,#shopDetailPageFormat .row .right li.active,
            #shopDetailPageDish .row .right li.active,
            #shopDetailPageLabel .row .right li.active{
                border-color: none;
                color: #ffa52d;
            }

            #shopReplyBox .star{
                float: right;
            }
            #shopReplyBox .right .time{
                float: none;
            }
            .pageLoadTipLoader::before{
                border-bottom: #ffffff solid 5px;
                border-top: rgba(255,255,255,0.35) solid 5px;
                border-right: rgba(255,255,255,0.35) solid 5px;
                border-left: rgba(255,255,255,0.35) solid 5px;
            }
            .gray_line{
                border: 1px solid #e6e6e6;
                height: 1px;
                margin-bottom: 10px;
            }
            .msg-option{
                bottom: 0;
            }
            #shopDetailPageBar{
                width: 100%;
                max-width: 650px;
                background: #f4f4f4;
            }
            .closeBtn div:before,.closeBtn div:after{
                top: 17px;
                left: 6px;
            }
            #free_delivery{
                line-height: 20px;
                color: white;
                position: absolute;
                bottom: 0px;
                font-size: 12px;
            }
            .donate_btn{
                position: absolute;
                top: 0px;
                right: 20px;
                border: 1px solid #ffa52d;
                color: #ffa52d;
                padding: 3px;
                border-radius: 3px;
            }
            .star span{
                border: 1px solid #ffa52d;
                border-radius: 2px;
                padding: 1px 2px;
                font-size: 12px;
                color: white;
                background-color: #ffa52d;
                margin-right: 5px;
            }
            #shopMerchantBox .merchantReduce{
                color: #ffa52d;
            }
            #background_area{
                position:fixed;
                margin-top: 0px;
                background-color: #f4f4f4;
                width: 100%;
                max-width: 640px;
                height:260px;
                background-image: url("");
                background-repeat:no-repeat;
                background-size:100% ;
                -moz-background-size:100%
                z-index: 0;
            }
            #shopBanner .text {
                padding-top: 0px;
                padding-left: 10px;
                padding-right: 5px;
                margin-bottom: 5px;
            }
            #stars{
                position: absolute;
                background-image:url("/tpl/Static/blue/images/new/ic_rating_one.png");
                background-size:20px 20px;
                background-repeat: no-repeat;
                background-position: 25px 0px ;
                right: 10px;
                top: 10px;
                width: 45px;
            }
            #stars span{
                font-size: 16px;
            }
            #stars div{
                display: inline;
                background-image:url("/tpl/Static/blue/images/new/ic_rating_one.png");
                background-size:20px 20px;
                height: 20px;
                width:20px;
            }
            #shopReplyDiv {
                margin-top: 0px;
            }
            #shopMerchantBox dl {
                margin-top: 0px;
                background: unset;
            }
            /*#div_space::before {  content: ' ';*/
                /*position: fixed;*/
                /*z-index: -1;*/
                /*top: 0;*/
                /*right: 0;*/
                /*bottom: 0;*/
                /*left: 0;*/
                /*background: url(http://www.vicisland.ca:8087/upload/goods/000/000/056/s_5cd0f9372c58d367.png) no-repeat 0 top #f2f8fa;*/
                /*background-size: 100% auto;*/
            /*}*/
            #pageShop {
                overflow:initial;
            }
            #shopCatBar {
                position: sticky;
                z-index: 99;
                top: 101px;
            }
            .swiper-container{
                /*margin-top: 50px;*/
            }
            .add_bold{
                font-weight: bold;
                line-height: 15px;
                margin-top: 8px;
                font-size: 16px;
            }
            .add_grey{
                color: #8A8A8A;
                font-size: 14px;
            }
            .caret{
                font-weight: bold;
                font-size: 16px;
            }
            .div_block{
                margin: 20px 5px;
                padding: 5px 10px;
                background: white;
                border-radius: 10px;
            }
            .closeBtn div {
                left: 10px;
                top: 10px;
            }
            .box_title{
                font-weight: bold;
            }
            .box_left_title{
                width: 95px;
                font-size: 14px;
                display: inline-block;
                text-align: right;
            }
            .box_right_content{
                display: inline;
                font-size: 14px;
                max-width: 190px;
            }
            #shopMerchantBox dd {
                padding: 12px 20px 12px 5px;
                border-bottom: 1px solid #f1f1f1;
                color: #333;
                position: relative;
                display: flex;
            }
            #shopProductLeftBar2 dd span {
                padding: 8px 10px;
                line-height: 17px;
                background: none;
                display: block;
                border-bottom: none;
            }

            #selectDiv{
                margin: 10px auto 10px -5px;
                display: none;
            }

            #selectDiv ul{
                width: 300px;
                height: 40px;
                background-color: #E5E5E5;
                border-radius: 20px;
                font-size: 0px;
            }
            #selectDiv li{
                display: inline-block;
                font-size: 13px;
                width: 146px;
                height: 36px;
                margin: 2px;
                border-radius: 18px;
                text-align: center;
                color: #4E4D4D;
                font-weight: bold;
                padding-top: 2px;
            }

            #selectDiv li.active{
                background-color: #4E4D4D;
                color: white;
            }
            #selectDiv li .select_desc{
                font-size: 12px;
                font-weight: normal;
                line-height: 100%;
            }
        </style>
	<body onscroll="scrollProductEvent(1)">
    <include file="Public:google"/>
<!--    <div id="debug" style="position: fixed;color:red;width:auto;height: 40px;left:30px;top:200px;z-index: 1000000;background: white">Debug</div>-->
    <div id="debug2" style="position: fixed;color:red;width:auto;height: 40px;left:30px;top:220px;z-index: 1000000;background: white"></div>
    <div id="container">
<!--        <div class="productNum-4630_2_FOFO_8_FOFO_1_FOFO_0.01_4_FOFO_33_FOFO_1_FOFO_1.05_XOXO_4_FOFO_34_FOFO_1_FOFO_0.65_XOXO_4_FOFO_52_FOFO_1_FOFO_0.32">abcde</div>-->
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
				</ul>·
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
                <div id="shopTitle_Header"></div>
                <!--div class="shop_info" data-nav="merchant"></div-->
				<div id="searchBtn" class="searchBtn"><div></div></div>
			</section>
            <div id="background_area">
            </div>
            <div id="div_space" style="width: 100%;height:120px">
            </div>
			<section id="shopBanner">
				<div class="text">
                    <div id="stars"><span id="stars_text">.</span><div id="rating"></div></div>
                    <div id="shopTitle"></div>
                    <div id="selectDiv">
                        <ul>
                            <li id="delivery_li" data-type="0" class="active">
                                <p>DELIVERY</p>
                                <p class="select_desc">Unavailable</p>
                            </li>
                            <li id="pickup_li" data-type="1">
                                <p>PICKUP</p>
                                <p class="select_desc">Unavailable</p>
                            </li>
                        </ul>
                    </div>
					<div id="deliveryText"></div>
					<div id="shopNoticeText"></div>
                    <div class="star">
                        <i class="full"></i><i></i>
                    </div>
				</div>
                <if condition="$_GET['shop_id'] eq 292">
                    <a href="./wap.php?c=Event&a=index">
                <div class="donate_btn">Donate a Meal</div>
                    </a>
                </if>
				<!--div class="discount">
					<div class="noticeBox"><div class="notice"><div></div></div></div>
					<span id="shopCouponText"></span>
				</div-->
			</section>
            <section id="shopMenuBar_Space" style="height: 0;"></section>
			<section id="shopMenuBar">
				<ul>
					<li class="caret product active" data-nav="product">{pigcms{:L('_PRODUCT_TXT_')}</li>
					<li class="caret reply" data-nav="reply">{pigcms{:L('_EVALUATE_TXT_')}</li>
					<li class="caret merchant" data-nav="merchant">{pigcms{:L('_SHOP_TXT_')}</li>
				</ul>
			</section>
<!--			<section id="shopCatBar" style="display:none;">	-->
<!--				<div class="title">-->
<!--                    {pigcms{:L('_ALL_CLASSIF_')}-->
<!--				</div>-->
<!--				<div class="content">-->
<!--					<ul></ul>-->
<!--				</div>-->
<!--			</section>-->
			<section id="shopContentBar">
				<div id="shopProductBox">
<!--					<div id="shopProductBottomBar"><ul class="clearfix"></ul><div id="shopProductBottomLine"></div></div>-->
                    <div id="shopCatBar" style="display:none;">
                        <div class="title">
                            {pigcms{:L('_ALL_CLASSIF_')}
                        </div>
                        <div class="content">
                            <ul></ul>
                        </div>
                    </div>
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
							<div id="cartBox" class="cart">
								<div id="cartNumber">0</div>
							</div>
							<div class="price">
                                {pigcms{:L('_TOTAL_TXT_')}$<span id="cartMoney">0</span>
                                <div id="free_delivery"></div>
                            </div>
						</div>
                        <div id="checkCart" style="display:none;">{pigcms{:L('_GOOD_CHOICE_')}</div>
						<div id="emptyCart">
							<div class="cart"></div>{pigcms{:L('_CART_EMPTY_')}
						</div>
						<div id="checkCartEmpty"></div>
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
				<div id="shopMerchantBox"  style="display:none;background: unset">
					<dl id="shopMerchantDescBox">
                        <div class="div_block">
                            <dd class="box_title">{pigcms{:L('_SHOP_BOX_TITLE_')}</dd>
                            <dd class=""><span class="box_left_title">{pigcms{:L('_SHOP_PHONE_')}:&nbsp;</span><span class="phone box_right_content"></span></dd>
						    <dd class=""><span class="box_left_title">{pigcms{:L('_SHOP_ADDRESS_')}:&nbsp; </span><span class="address box_right_content"></span></dd>
                        </div>
                        <div class="div_block">
                        <dd class="box_title">{pigcms{:L('_TIME_BOX_TITLE_')}</dd>
						    <dd class=""><span class="box_left_title">{pigcms{:L('_STORE_MONDAY_')}: &nbsp;</span><span class="w1 box_right_content"></span></dd>
                            <dd class=""><span class="box_left_title">{pigcms{:L('_STORE_TUESDAY_')}: &nbsp;</span><span class="w2 box_right_content"></span></dd>
                            <dd class=""><span class="box_left_title">{pigcms{:L('_STORE_WEDNESDAY_')}: &nbsp;</span><span class="w3 box_right_content"></span></dd>
                            <dd class=""><span class="box_left_title">{pigcms{:L('_STORE_THURSDAY_')}: &nbsp;</span><span class="w4 box_right_content"></span></dd>
                            <dd class=""><span class="box_left_title">{pigcms{:L('_STORE_FRIDAY_')}: &nbsp;</span><span class="w5 box_right_content"></span></dd>
                            <dd class=""><span class="box_left_title">{pigcms{:L('_STORE_SATURDAY_')}: &nbsp;</span><span class="w6 box_right_content"></span></dd>
                            <dd class=""><span class="box_left_title">{pigcms{:L('_STORE_SUNDAY_')}: &nbsp;</span><span class="w7 box_right_content"></span></dd>
						<!--dd class="deliveryType">{pigcms{:L('_DIST_SERVICE_')}</dd-->
<!--						<dd class="merchantNotice">{pigcms{:L('_SHOP_NOTICE_')}</dd>-->
<!--                        <dd class="merchantReduce"></dd>-->
                        </div>
					</dl>
<!--					<if condition="!$merchant_link_showOther">-->
<!--						<dl id="shopMerchantLinkBox">-->
<!--							<dd class="more link-url" data-url="{pigcms{:U('My/shop_order_list')}"><span></span>{pigcms{:L('_MY_OUT_ORDER_')}</dd>-->
<!--						</dl>-->
<!--					</if>-->
<!--					<dl id="shopMerchantCouponBox">-->
<!--						<dd>{pigcms{:L('_DIST_SERVICE_')}</dd>-->
<!--						<dd>{pigcms{:L('_DIST_TIME_')}</dd>-->
<!--					</dl>-->
				</div>
				<div id="shopPageShade" style="display:none;"></div>
				<div id="shopPageCatShade" style="z-index: 80"></div>
<!--                可选规格对话框-->
				<div id="shopDetailPage" style="display:none;">
                    <div id="shopDetailpageClose" class="closeBtn"><div></div></div>
					<div class="scrollerBoxShit" style="padding-bottom: 60px;padding-top: 35px;">

						<div id="shopDetailPageImgbox" class="swiper-container swiper-container-productImg">
							<div class="swiper-wrapper"></div>
							<div class="swiper-pagination swiper-pagination-productImg"></div>
						</div>
						<div id="shopDetailPageTitle">
							<div class="title">{pigcms{:L('_PRODUCT_NAME_')}</div>
                            <div class="content"></div>
							<div class="desc"></div>
						</div>
						<div id="shopDetailPageFormat">{pigcms{:L('_PRODUCT_STOCK_')}</div>
						<div id="shopDetailPageLabel">
							<!--div class="tip">{pigcms{:L('_WANT_TO_NOT_')}<div class="question"></div></div-->
							<div id="shopDetailPageLabelBox"></div>
						</div>
                        <div id="shopDetailPageDish">{pigcms{:L('_PRODUCT_STOCK_')}</div>
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
					</div>
				</div>

                <div id="shopSearchPage" style="display:none;">
                    <div class-s="scrollerBox">
                        <div id="shopSearchpageClose" class="closeBtn"><div></div></div>
                        <div id="shopSearchBoxDiv" style="padding: 20px 0 0 70px;">
                            <input type="text" name="searckey" id="search_input" placeholder="Search items within this store" />
                            <span id="search_btn">Search</span>
                        </div>
                        <div id="shopSearchResult">
                            <dl></dl>
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
            $(document).ready(function(){
                var s = "";
                s += " 屏幕高度："+ window.screen.availHeight+"\n";
                s += " 屏幕宽度："+ window.screen.availWidth+"\n";
                s += " 网页可见区域宽："+ document.body.clientWidth+"\n";
                s += " 网页可见区域高："+ document.body.clientHeight+"\n";
                s += " 网页可见区域宽："+ document.body.offsetWidth + " (包括边线和滚动条的宽)"+"\n";
                s += " 网页可见区域高："+ document.body.offsetHeight + " (包括边线的宽)"+"\n";
                s += " 正文全文宽："+ document.body.scrollWidth+"\n";
                s += " 正文全文高："+ document.body.scrollHeight+"\n";
                // s += " 网页被卷去的高(ff)："+ document.body.scrollTop+"\n";
                // s += " 网页被卷去的高(ie)："+ document.documentElement.scrollTop+"\n";
                // s += " 网页被卷去的左："+ document.body.scrollLeft+"\n";
                // s += " 网页正文部分上："+ window.screenTop+"\n";
                // s += " 网页正文部分左："+ window.screenLeft+"\n";
                // s += " 屏幕分辨率的高："+ window.screen.height+"\n";
                // s += " 屏幕分辨率的宽："+ window.screen.width+"\n";

                s += " 你的屏幕设置是 "+ window.screen.colorDepth +" 位彩色"+"\n";
                 // s += " 你的屏幕设置 "+ window.screen.deviceXDPI +" 像素/英寸"+"\n";

                //alert (s);
                //$('#container').css('height',document.body.clientHeight+200);

                var clh=document.body.clientHeight;
                //$(".productNum-4630_2_FOFO_8_FOFO_1_FOFO_0_DODO_01_4_FOFO_33_FOFO_1_FOFO_1_DODO_05_XOXO_4_FOFO_34_FOFO_1_FOFO_0_DODO_65_XOXO_4_FOFO_52_FOFO_1_FOFO_0_DODO_32").html("1234");
                //$('#pageShop').css('height',clh+900);
                //$('#shopProductRightBar2').css('height',clh);
                //alert (document.body.clientHeight);
                //$('#shopProductRightBar2').css('height',30);
            });

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
            var pickupImg = "<img src='./tpl/Static/blue/images/new/pickup_icon.png' height='20' style='vertical-align: sub' /> ";
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

            $('#shopProductRightBar2').scroll(function () {
                var base_top = $(this).offset().top;
                $(this).find('dd').each(function () {
                    var curr_top = $(this).offset().top - base_top;
                    if(curr_top > -10 && curr_top < 10){
                        var this_id = $(this).data('cat_id');
                        $('#shopProductLeftBar2-'+this_id).addClass("active").siblings("dd").removeClass("active");
                    }
                });
            });
		</script>
		{pigcms{$shareScript}
	</body>
</html>