<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <if condition="$config['site_favicon']">
        <link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
    </if>
    <title>{pigcms{:L('_VIC_NAME_')} - {pigcms{:L('_HOME_TXT_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width,viewport-fit=cover"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name='apple-touch-fullscreen' content='yes'/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8;application/json" />
    <if condition="$is_ios eq 0">
        <link rel="manifest" href="/manifest.json">
    </if>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index.css?v=1.2"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobileSelect/mobileSelect.css"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    <script type="text/javascript">
        var group_index_sort_url="{pigcms{:U('Home/group_index_sort')}";
        <if condition="$user_long_lat">var user_long = "{pigcms{$user_long_lat.long}",user_lat = "{pigcms{$user_long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>var app_version="{pigcms{$_REQUEST['app_version']}"
        var address_url = "{pigcms{:U('Home/address')}";
        var static_url = "{pigcms{$static_public}";
    </script>
    <script type="text/javascript" src="{pigcms{$static_path}js/index.js?v=1.8" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobileSelect/mobileSelect.js"></script>
    <if condition="$config.guess_content_type eq 'shop'">
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/home_shop.css"/>
        <elseif condition="$config.guess_content_type eq 'meal'" />
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/home_meal.css?216"/>
    </if>
    <script>
        <if condition="$is_wexin_browser">

        <else />
        if(navigator.serviceWorker != null){
            navigator.serviceWorker.register('/sw.js')
                .then(function(registartion){
                    console.log('支持sw:',registartion.scope)
                }).catch(function (err) {
                console.log('不支持sw:',err);
            })
        }else{
            console.log('SW run fail');
        }

        window.addEventListener('beforeinstallprompt', function (e) {
            e.userChoice.then(function (choiceResult) {
                if (choiceResult.outcome === 'dismissed') {
                    console.log('用户取消安装应用');
                    //showmessage('用户取消安装应用');
                }else{
                    console.log('用户安装了应用');
                    //showmessage('用户安装了应用');
                }
            });
        });
        </if>

        $(function(){
            $(document).on('click','.hasMore',function(){
                $(this).toggleClass('showMore');
                myScroll.refresh();
                return false;
            });
            //var banner_height	=	screen.width/320;
            var banner_height	=	$('#container').width()*0.96/320;
            banner_height	=	 Math.ceil(banner_height*119);
            $("#banner_hei").css('height',banner_height);
        });
        var guess_num	=	"{pigcms{$guess_num}";
        var guess_content_type	=	"{pigcms{$guess_content_type}";
    </script>
    <style>
        .index_house{
            position:relative;
        }
        .index_house:after {
            display: block;
            content: "";
            border-top: 1px solid #BFBFBF;
            border-left: 1px solid #BFBFBF;
            width: 8px;
            height: 8px;
            -webkit-transform: rotate(135deg);
            background-color: transparent;
            position: absolute;
            top: 50%;
            right: 15px;
            margin-top: -5px;
        }
        #container{
            width: 100%;
            padding-top: 60px;
            max-width: 640px;
            min-width: 320px;
            margin: 0 auto;
        }
        .cate_left,.cate_right{
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 16px;
            background-repeat: no-repeat;
            background-position: center;
        }
        .gray_line{
            width: 100%;
            height: 2px;
            margin-top: 25px;
            margin-bottom: 25px;
            background-color: #cccccc;
        }
        .pageLoadTipBg{
            background:#D8D8D8;
            position:fixed;
            left:0;
            top:0;
            width:100%;
            height:100%;
            z-index: 9998;
            display: none;
        }
        .pageLoadTipBg.nobg{
            background:none;
        }
        .pageLoadTipBox{
            width: 120px;
            height: 120px;
            background: rgba(0,0,0,0.59);
            border-radius: 5px;
            box-shadow: 0 0 12px #333;
            -webkit-box-shadow: 0 0 12px #333;
            position: fixed;
            z-index: 9999;
        }
        .pageLoadTipLoader{
            display: inline-block;
            width: 70px;
            height: 70px;
            position: relative;
            margin: 20px;
        }
        .pageLoadTipLoader:before {
            content: "";
            display: block;
            position: absolute;
            width: 100%;
            height: 100%;
            line-height: 100%;
            overflow: hidden;
            background:rgba(0,0,0,0.0);
            border-radius: 100%;
            border: none;
            border-bottom: #ffffff solid 5px;
            border-top: rgba(255,255,255,0.35) solid 5px;
            border-right: rgba(255,255,255,0.35) solid 5px;
            border-left: rgba(255,255,255,0.35) solid 5px;
            -webkit-animation-name: pageLoader;
            -moz-animation-name: pageLoader;
            -o-animation-name: pageLoader;
            animation-name: pageLoader;
            -webkit-animation-iteration-count: infinite;
            -moz-animation-iteration-count: infinite;
            -o-animation-iteration-count: infinite;
            animation-iteration-count: infinite;
            -webkit-animation-timing-function: linear;
            -moz-animation-timing-function: linear;
            -o-animation-timing-function: linear;
            animation-timing-function: linear;
            -webkit-animation-fill-mode: forwards;
            -o-animation-fill-mode: forwards;
            animation-fill-mode: forwards;
            z-index: 1;

            -webkit-animation-duration: 1s;
            -moz-animation-duration: 1s;
            -o-animation-duration: 1s;
            animation-duration: 1s;
        }
        .pageLoadTipLoader div {
            position: absolute;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            color: dodgerblue;
            z-index: 2;
            width: 100%;
            height: 100%;
            line-height: 70px;
            font-size: 20%;
            border-radius: 100%;
            margin-left:5px;
            margin-top:5px;

            background-repeat: no-repeat;
            background-size: 40px;
            background-position: 50%;
        }
        .pageLoadTipLoader img{
            margin-top:15px;
            width:40px;
            height:40px;
        }
        @-webkit-keyframes pageLoader {
            from {
                -webkit-transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
            }

        }

        @-o-keyframes pageLoader {
            from {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }

        }

        @-moz-keyframes pageLoader {
            from {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }

        }

        @keyframes pageLoader {
            from {
                -webkit-transform: rotate(0deg);
                -moz-transform: rotate(0deg);
                -ms-transform: rotate(0deg);
                -o-transform: rotate(0deg);
                transform: rotate(0deg);
            }

            to {
                -webkit-transform: rotate(360deg);
                -moz-transform: rotate(360deg);
                -ms-transform: rotate(360deg);
                -o-transform: rotate(360deg);
                transform: rotate(360deg);
            }

        }
        .dealcard .brand{
            width: 100%;
            overflow:hidden;
            white-space:nowrap;
            text-overflow:ellipsis;
            font-size: 1.05em;
        }
        .free_delivery{
            position: absolute;
            color: #ffa52d;
            top: 30px;
            right: 5px;
            width: 50px;
            height: 30px;
            background-image: url("./tpl/Static/blue/images/new/badge.png");
            background-size: auto 100%;
            background-repeat: no-repeat;
            background-position: center;
        }
        .off_over{
            font-size: 11px;
            color: #ffa52d;
            background-image: url("./tpl/Static/blue/images/new/off_over_icon.png");
            background-size: auto 120%;
            background-repeat: no-repeat;
            background-position: center left;
            padding-left: 20px;
        }
        .recommend-link-url{
            line-height: 1.2;
        }
        .banner{
            width: 96%;
            margin: 10px auto;
        }
        .swiper-container{
            border-radius: 15px;
        }

        .store_img{
            position: relative;
        }
        .store_img ul{
            width: 99%;
            padding: 10px;
            overflow: auto;
            white-space: nowrap;
            box-sizing: border-box;
        }
        .store_img li{
            display: inline-block;
            width: 40%;
            margin-left: 10px;
        }
        .store_img li img,#recommendList li img{
            border-radius: 5px;
        }
        .closeLayer{
            width: 100%;
            height: 100%;
            position: absolute;
            opacity: 0.6;
            top: 0;
            background: #333333;
        }
        .close_txt{
            margin-bottom: 5px;
            color: white;
            text-align: center;
            width: 100%;
            position: absolute;
            bottom: 5px;
            font-size: 20px;
            font-weight: bold;
        }
        .show_span{
            display: inline-block;
            padding: 0 2px;
            font-size: 12px;
            color: #ffa52d;
            border: 1px solid #ffa52d;
            border-radius: 2px;
            line-height: 1.5;
        }
        #recommendList{
            background: white;
        }
        #recommendList dd{
            padding:20px 2% 10px 2%;
        }
        #recommendList ul{
            width: 99%;
            padding: 10px 0;
            overflow: auto;
            white-space: nowrap;
            box-sizing: border-box;
        }
        #recommendList li{
            display: inline-block;
            width: 55%;
            margin-left: 10px;
        }
        .view_all_span{
            float: right;
            font-weight: bold;
        }
        .view_all_span a{
            color: #ffa52d;
        }
        .all_title{
            margin: 10px 2% 0 3%;
            font-size: 16px;
            font-weight: bold;
        }
        #sort_select{
            float: right;
            padding-left: 25px;
            color: #545454;
            background-image: url("{pigcms{$static_public}images/arrow-bottom-new.png");
            background-repeat: no-repeat;
            background-size:auto 90%;
        }
        #moress{
            width: 100%;
            text-align: center;
            line-height: 1.8;
            margin-top: 5px;
        }
        #recommendList ul::-webkit-scrollbar,#category ul::-webkit-scrollbar,.store_img ul::-webkit-scrollbar{
            display: none;
        }
        .model_select{
            text-align: center;
            padding: 10px auto;
        }
        .model_select span{
            margin-left: 10px;
            cursor: pointer;
        }
        .model_select .active{
            color: #ffa52d;
        }
    </style>
    <include file="Public:facebook"/>
</head>
<body>
<include file="Public:google"/>
<include file="Public:header"/>
<div id="container">
    <div id="scroller">
        <if condition="$wap_index_top_adver">
            <section id="banner_hei" class="banner">
                <div class="swiper-container swiper-container1">
                    <div class="swiper-wrapper">
                        <volist name="wap_index_top_adver" id="vo">
                            <div class="swiper-slide">
                                <a href="{pigcms{$vo.url}">
                                    <img src="{pigcms{$vo.pic}"/>
                                </a>
                            </div>
                        </volist>
                    </div>
                    <div class="swiper-pagination swiper-pagination1"></div>
                </div>
            </section>
        </if>
        <div id="category">
            <div class="cate_left"></div>
            <ul>
                <!--volist name="category" id="vo">
                    <a href="{pigcms{:U('Shop/index')}&cat={pigcms{$vo['id']}">
                    <li>
                        <div>
                            <img src="{pigcms{$vo['image']}" />
                        </div>
                        <div>{pigcms{$vo['title']}</div>
                    </li>
                    </a>
                </volist-->
            </ul>
            <div class="cate_right"></div>
        </div>
        <div class="model_select">
            <span class="active" data-type="delivery">Delivery</span>
            <span data-type="pickup">Pick up</span>
        </div>
        <div id="recommendList"></div>
        <!--div class="gray_line"></div-->
        <div class="all_title">
            <span>All Restaurants</span>
            <span id="sort_select">
                Closest to You
            </span>
        </div>

        <section class="youlike hide">
            <dl class="likeBox dealcard"></dl>
            <div id="moress">{pigcms{:L('_LOADING_TXT_')}</div>
        </section>
        <script id="indexRecommendBoxTpl" type="text/html">
            <if condition="$config.guess_content_type eq 'group'">
                {{# for(var i = 0, len = d.length; i < len; i++){ }}
                <dd class="recommend-link-url" data-group_id="{{ d[i].group_id }}" data-url="{{ d[i].url }}">
                    {{# if(d[i].pin_num > 0){ }}<div class="pin_style"></div>{{# } }}
                    <div class="dealcard-img imgbox">
                        <img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d[i].list_pic) }}" alt="{{ d[i].s_name }}"/>
                    </div>
                    <div class="dealcard-block-right">
                        <div class="brand">{{# if(d[i].tuan_type != 2){ }} {{ d[i].merchant_name }}</div>
                        <div class="title">{{ d[i].group_name }}</div>
                        <div class="price">
                            <strong>{{ d[i].price }}</strong>
                            <span class="strong-color">元{{# if(d[i].extra_pay_price!=''){ }}{{ d[i].extra_pay_price }}{{# } }}</span>{{# if(d[i].wx_cheap){ }}
                            <span class="tag">微信再减{{ d[i].wx_cheap }}元</span>{{# }else{ }}<del>{{ d[i].old_price }}</del>{{# } }}
                            <span class="line-right"> {{ d[i].sale_txt }}</span>
                        </div>
                    </div>
                </dd>
                {{# } }}
                <elseif condition="$config.guess_content_type eq 'shop'"/>

                {{# for(var i = 0, len = d.length; i < len; i++){ }}
                {{# if(d[i].image_count > 0){ }}
                <dd class="recommend-link-url" data-url="./wap.php?c=Shop&a=classic_shop&shop_id={{ d[i].id }}" data-url-type="openRightFloatWindow" style="padding: 0;border-radius: 5px;">
                    <div class="store_img">
                        {{# if(d[i].image_count == 1){ }}
                        <img src="{{ d[i].image_list[0] }}" style="width: 100%;display: block;" />
                        {{# }else{ }}
                        <ul>
                            {{# for(var j = 0; j < d[i].image_count; j++){ }}
                            <li {{# if(j==0){ }}style="margin-left:0;"{{# } }}><img src="{{ d[i].image_list[j] }}" style="width: 100%" /></li>
                            {{# } }}
                        </ul>
                        {{# } }}
                        {{# if(d[i].is_close){ }}
                        <div class="closeLayer"></div>
                        <div class="close_txt">Currently Closed</div>
                        {{# } }}
                    </div>
                    <div class="brand" style="margin-left: 10px">{{ d[i].name }}
                        {{# if(d[i].star > 0){ }}
                        <span style="color:grey;">{{ d[i].star }}</span>
                        <img src="./static/images/icon-star-enter.png" width="12">
                        {{# } }}
                    </div>
                    <div class="brand" style="margin-left: 10px;font-size: 12px;color: grey">${{ d[i].delivery_money }} + · {{ d[i].keywords }}</div>
                    <div class="brand" style="margin-left: 10px;margin-bottom: 5px;">
                        {{# if(d[i].free_delivery == 1){ }}
                        <span class="show_span" style="margin-right: 5px;">{{ d[i].event.desc }}</span>
                        {{# } }}
                        {{# if(d[i].merchant_reduce_list){ }}
                        <span class="show_span">{{ d[i].merchant_reduce_list }}</span>
                        {{# } }}
                        &nbsp;
                    </div>
                </dd>
                {{# }else{ }}
                <dd class="recommend-link-url" data-url="./wap.php?c=Shop&a=classic_shop&shop_id={{ d[i].id }}" data-url-type="openRightFloatWindow" {{# if(d[i].is_close){ }}style="opacity:0.6;"{{# } }}>

                    <div class="dealcard-img imgbox">
                        <img style="margin-left: 0px;position: absolute;"  src="{{ d[i].image }}" alt="{{ d[i].name }}">
                        {{# if(d[i].is_close){ }}
                        <div class="is_close close_s">CLOSED</div>
                        {{# } }}
                    </div>
                    <div class="dealcard-block-right">
                        <div class="brand">{{ d[i].name }}</div>
                        {{# if(d[i].delivery){ }}
                        <div class="price">
                            <!--span>{pigcms{:L('_MIN_DELI_PRICE_')} ${{ d[i].delivery_price }}</span-->
                            <span class="delivery">{pigcms{:L('_DELI_PRICE_')} ${{ d[i].delivery_money }}+</span>
                            <!--span class="delivery">{pigcms{:L('_PACK_PRICE_')} ${{ d[i].pack_fee }}</span-->
                            {{# if(d[i].delivery_system){ }}
                            <!--em class="location-right">{pigcms{:L('_PLAT_DIST_')}</em-->
                            {{# }else{ }}
                            <!--em class="location-right">{pigcms{:L('_SHOP_DIST_')}</em-->
                            {{# } }}
                        </div>
                        {{# } }}
                        <div class="price"><span>{{ d[i].keywords }}</span></div>
                        {{# if(d[i].merchant_reduce_list){ }}
                        <div class="off_over">{{ d[i].merchant_reduce_list }}</div>
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
                    {{# if(d[i].free_delivery == 1){ }}
                        <div class="free_delivery"></div>
                    {{# } }}
                </dd>
                {{# } }}
                {{# } }}
                <elseif condition="$config.guess_content_type eq 'meal'"/>
                {{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
                {{# if(d.store_list[i].state == 0){ }}
                <dl class="on">
                    <dt>
                        <div class="navLtop clr recommend-link-url" data-url="{{ d.store_list[i].url }}">
                            {{# if(d.store_list[i].isverify == 1){ }}
                            <img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:1px; margin-right:5px;float:left">
                            {{# } }}
                            <h2 class="fl1">{{ d.store_list[i].name }}</h2>
                            <div class="navLtop_right fr">
                                {{# if(d.store_list[i].is_book == 1){ }}
                                <span class="ln">订</span>
                                {{# } }}
                                {{# if(d.store_list[i].is_queue == 1){ }}
                                <span class="zi">排</span>
                                {{# } }}
                                {{# if(d.store_list[i].is_takeout == 1){ }}
                                <span class="lv">外</span>
                                {{# } }}
                            </div>
                        </div>
                        <div class="navLBt clr">
                            <ul class="navLBt_ul fl show_number clr">
                                <li>
                                    <div class="atar_Show">
                                        <p tip="{{ d.store_list[i].score_mean }}" ></p>
                                    </div>
                                </li>
                            </ul>
                            <div class="Notopen fl">{pigcms{:L('_NO_BUSINESS_')}</div>
                            <div class="distance fr">{{ d.store_list[i].range }}</div>
                        </div>
                    </dt>
                </dl>
                {{# } else { }}
                <dl>
                    <dt>
                        <div class="navLtop clr recommend-link-url" data-url="{{ d.store_list[i].url }}">
                            {{# if(d.store_list[i].isverify == 1){ }}
                            <img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:1px; margin-right:5px;float:left">
                            {{# } }}
                            <h2 class="fl1">{{ d.store_list[i].name }}</h2>
                            <div class="navLtop_right fr">
                                {{# if(d.store_list[i].is_book == 1){ }}
                                <span class="ln">订</span>
                                {{# } }}
                                {{# if(d.store_list[i].is_queue == 1){ }}
                                <span class="zi">排</span>
                                {{# } }}
                                {{# if(d.store_list[i].is_takeout == 1){ }}
                                <span class="lv">外</span>
                                {{# } }}
                            </div>
                        </div>
                        <div class="navLBt clr">
                            <ul class="navLBt_ul fl show_number clr">
                                <li>
                                    <div class="atar_Show fl">
                                        <p tip="{{ d.store_list[i].score_mean }}" ></p>
                                    </div>
                                </li>
                            </ul>
                            <div class="distance fr">{{ d.store_list[i].range }}</div>
                        </div>
                    </dt>
                    {{# if(d.store_list[i].pay_in_store == 1 && d.store_list[i].discount_txt != ''){ }}
                    <dd class="navlink clr">
                        <a href="{{ d.store_list[i].store_pay }}">
                            <span class="link_Pay">{pigcms{:L('_TO_SHOP_PAY_')}</span>
                            {{# if(d.store_list[i].discount_txt.discount_type == 1){ }}
                            <span>{{ getLangStr('_DISCOUNT_NUM_',d.store_list[i].discount_txt.discount_percent) }}</span>
                            {{# } else { }}
                            <span>
                                {{ getLangStr('_EVERY_FULL_',d.store_list[i].discount_txt.condition_price) }}
                                {{ getLangStr('_REDUCE_NUM_',d.store_list[i].discount_txt.minus_price) }}
                            </span>
                            {{# } }}
                            <span class="link_jt fr"></span>
                        </a>
                    </dd>
                    {{# } }}
                    {{# for(var j = 0, jlen = d.store_list[i].group_list.length; j < jlen; j++){ }}
                    <dd class="Menulink clr">
                        <a href="{{ d.store_list[i].group_list[j].url }}">
                            <div class="Menulink_img fl">
                                <img class="on" src="{{ d.store_list[i].group_list[j].list_pic }}">
                                <span class="MenuGroup"></span>
                            </div>
                            <div class="Menulink_right">
                                <h2>{{ d.store_list[i].group_list[j].name }}</h2>
                                <div class="MenuPrice">
                                    <span class="PriceF"><i>$</i><em>{{ d.store_list[i].group_list[j].price }}</em></span>
                                    <span class="PriceT">{pigcms{:L('_RACK_RATE_')}:${{ d.store_list[i].group_list[j].old_price }}</span>
                                    <span class="PriceS">{{ d.store_list[i].group_list[j].sale_txt }}</span>
                                </div>
                            </div>
                        </a>
                    </dd>
                    {{# } }}
                </dl>
                {{# } }}
                {{# } }}
            </if>
        </script>

        <script id="indexRecommendListTpl" type="text/html">
            {{# for(var i = 0, len = d.length; i < len; i++){ }}
                <dd>
                    <div>
                    <span style="font-size: 18px;font-weight: bold;">{{ d[i].title }}</span>
                    <span class="view_all_span" data-id="{{ d[i].fid }}-{{ d[i].id }}">
                        <a href="./wap.php?c=Shop&a=index&cat={{ d[i].fid }}-{{ d[i].id }}">
                        View All
                        </a>
                    </span>
                    </div>

                    <ul>
                        {{# for(var j = 0, jlen = d[i].info.length; j < jlen; j++){ }}
                        <a href="./wap.php?c=Shop&a=classic_shop&shop_id={{ d[i].info[j].store_id }}">
                        <li {{# if(j==0){ }}style="margin-left:0;"{{# } }} data-id="{{ d[i].info[j].store_id }}" class="recomm_store">
                            <div style="position: relative;margin-bottom: 2px">
                                <img src="{{ d[i].info[j].background }}" style="width: 100%;display: block;" />
                                {{# if(d[i].info[j].is_close){ }}
                                <div class="closeLayer" style="border-radius: 5px;"></div>
                                <div class="close_txt" style="font-size: 16px">Currently Closed</div>
                                {{# } }}
                            </div>
                            <div style="overflow: hidden;text-overflow: ellipsis;">{{ d[i].info[j].name }}</div>
                            <div style="color: grey;overflow: hidden;text-overflow: ellipsis;">{{ d[i].info[j].txt_info }}</div>
                        </li>
                        </a>
                        {{# } }}
                    </ul>
                </dd>
            {{# } }}
        </script>
    </div>
</div>
<div id="pageLoadTipShade" class="pageLoadTipBg">
    <div id="pageLoadTipBox" class="pageLoadTipBox">
        <div class="pageLoadTipLoader">
            <div style="background-image:url({pigcms{$config.shop_load_bg});"></div>
        </div>
    </div>
</div>
<include file="Public:footer"/>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en"></script>
<script type="text/javascript">
    var sortType = 0;
    window.shareData = {
        "moduleName":"Home",
        "moduleID":"0",
        "imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
        "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
        "tTitle": "{pigcms{$config.site_name}",
        "tContent": "{pigcms{$config.seo_description}"
    };

    var base_num = 4;
    var all_num = $('#category').find('li').length;
    var curr_num = 4;
    var width = $('#category').find('li').width();

    if(all_num > base_num){
        $('.cate_right').css('opacity',1);
        $('.cate_right').bind('click',cate_right_click);
    }
    
    function cate_right_click() {
        curr_num++;
        scroll_cate();
    }
    function cate_left_click() {
        curr_num--;
        scroll_cate();
    }
    function scroll_cate() {
        if(curr_num > all_num)
            curr_num = all_num;
        if(curr_num < base_num)
            curr_num = base_num;

        if(curr_num == all_num){
            $('.cate_right').css('opacity',0);
            $('.cate_right').unbind();
        }else if(curr_num == base_num){
            $('.cate_left').css('opacity',0);
            $('.cate_left').unbind();
        }else if(curr_num > base_num){
            if($('.cate_left').css('opacity') == 0){
                $('.cate_left').css('opacity',1);
                $('.cate_left').bind('click',cate_left_click);
            }
            if($('.cate_right').css('opacity') == 0){
                $('.cate_right').css('opacity',1);
                $('.cate_right').bind('click',cate_right_click);
            }
        }
        var cha_num = curr_num - base_num;
        $('#category').find('ul').animate({scrollLeft:cha_num * width},500);
    }
    //
    if($.cookie('userLocationLong') && $.cookie('userLocationLat') && !$.cookie('userLocationCity')){
        var geocoder = new google.maps.Geocoder();
        var request = {
            location:{lat:parseFloat($.cookie('userLocationLat')), lng:parseFloat($.cookie('userLocationLong'))}
        }
        geocoder.geocode(request, function(results, status){
            if(status == 'OK') {
                console.log(results[0].address_components);
                var add_com = results[0].address_components;
                var is_get_city = false;
                for(var i=0;i<add_com.length;i++){
                    if(add_com[i]['types'][0] == 'locality'){
                        is_get_city = true;
                        var city_name = add_com[i]['long_name'];
                        $.post("{pigcms{:U('Index/ajax_city_name')}",{city_name:city_name},function(result){
                            if (result.error == 1){
                                $.cookie('userLocationCity', 0,{expires:700,path:"/"});
                            }else{
                                $.cookie('userLocationCity', result['info']['city_id'],{expires:700,path:"/"});
                            }
                        },'JSON');
                    }
                }
            }
        });
    }

    $(window).scroll(function(){
        var doc_height = $(document).height();
        var scroll_top = $(document).scrollTop();
        var window_height = $(window).height();

        if(scroll_top + window_height >= doc_height-60){
            if(has_more) {
                has_more = false;
                getRecommendList();
            }
        }
    });

    if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())){
        var user_id = "{pigcms{$user_session['uid']}";
        if(user_id == ""){
            if(typeof (window.linkJs.getUserInfo) != 'undefined'){
                var str = window.linkJs.getUserInfo();

                var user_str = str.split(',');
                var phone = user_str[0];
                var password = user_str[1];
                $.post("{pigcms{:U('Login/index')}",{phone:phone,password:password},function(result){
                    if(result.status == '1'){
                        //window.location.reload();
                    }
                });
            }
        }
    }

    var sortArr=['Closest to You','Date Added'];

    var mobileSelect1 = new MobileSelect({
        trigger: '#sort_select',
        title: 'Sort',
        wheels: [
            {data: sortArr}
        ],
        position:[0], //初始化定位 打开时默认选中的哪个 如果不填默认为0
        transitionEnd:function(indexArr, data){
            //console.log(data);
        },
        callback:function(indexArr, data){
            console.log(indexArr);
            sortType = indexArr[0];
            like_page = 1;
            getRecommendList();
        }
    });

</script>
{pigcms{$shareScript}
</body>
</html>
