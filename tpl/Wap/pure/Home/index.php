<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>{pigcms{:L('_VIC_NAME_')} - {pigcms{:L('_HOME_TXT_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width,viewport-fit=cover"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name='apple-touch-fullscreen' content='yes'/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="format-detection" content="address=no"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index.css"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    <script type="text/javascript">
        var group_index_sort_url="{pigcms{:U('Home/group_index_sort')}";
        <if condition="$user_long_lat">var user_long = "{pigcms{$user_long_lat.long}",user_lat = "{pigcms{$user_long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>var app_version="{pigcms{$_REQUEST['app_version']}"
        var address_url = "{pigcms{:U('Home/address')}";
    </script>
    <script type="text/javascript" src="{pigcms{$static_path}js/index.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js" charset="utf-8"></script>
    <if condition="$config.guess_content_type eq 'shop'">
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/home_shop.css"/>
        <elseif condition="$config.guess_content_type eq 'meal'" />
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/home_meal.css?216"/>
    </if>
    <script>
        $(function(){
            $(document).on('click','.hasMore',function(){
                $(this).toggleClass('showMore');
                myScroll.refresh();
                return false;
            });
            //var banner_height	=	screen.width/320;
            var banner_height	=	$(window).width()/320;
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

            -webkit-animation-duration: 0.5s;
            -moz-animation-duration: 0.5s;
            -o-animation-duration: 0.5s;
            animation-duration: 0.5s;
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
                -webkit-transform: rotate(360deg);
            }

            to {
                -webkit-transform: rotate(00deg);
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
    </style>
</head>
<body>
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
                <volist name="category" id="vo">
                    <a href="{pigcms{:U('Shop/index')}&cat={pigcms{$vo['id']}">
                    <li>
                        <div>
                            <img src="{pigcms{$vo['image']}" />
                        </div>
                        <div>{pigcms{$vo['title']}</div>
                    </li>
                    </a>
                </volist>
            </ul>
            <div class="cate_right"></div>
        </div>
        <div class="gray_line"></div>
        <div class="recom">Recommendation</div>

        <section class="youlike hide">
            <dl class="likeBox dealcard"></dl>
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
                            <strong>{{ d[i].price }}</strong><span class="strong-color">元{{# if(d[i].extra_pay_price!=''){ }}{{ d[i].extra_pay_price }}{{# } }}</span>{{# if(d[i].wx_cheap){ }}<span class="tag">微信再减{{ d[i].wx_cheap }}元</span>{{# }else{ }}<del>{{ d[i].old_price }}</del>{{# } }} <span class="line-right"> {{ d[i].sale_txt }}</span>
                        </div>
                    </div>
                </dd>
                {{# } }}
                <elseif condition="$config.guess_content_type eq 'shop'"/>

                {{# for(var i = 0, len = d.length; i < len; i++){ }}
                <dd class="recommend-link-url" data-url="./wap.php?c=Shop&a=classic_shop&shop_id={{ d[i].id }}" data-url-type="openRightFloatWindow">

                    <div class="dealcard-img imgbox">
                        <img style="margin-left: 0px;position: absolute;"  src="{{ d[i].image }}" alt="{{ d[i].name }}">
                    </div>
                    <div class="dealcard-block-right">
                        <div class="brand">{{ d[i].name }}</div>
                        {{# if(d[i].delivery){ }}
                        <div class="price">
                            <!--span>{pigcms{:L('_MIN_DELI_PRICE_')} ${{ d[i].delivery_price }}</span-->
                            <span class="delivery">{pigcms{:L('_DELI_PRICE_')} ${{ d[i].delivery_money }}</span>
                            <span class="delivery">{pigcms{:L('_PACK_PRICE_')} ${{ d[i].pack_fee }}</span>
                            {{# if(d[i].delivery_system){ }}
                            <!--em class="location-right">{pigcms{:L('_PLAT_DIST_')}</em-->
                            {{# }else{ }}
                            <!--em class="location-right">{pigcms{:L('_SHOP_DIST_')}</em-->
                            {{# } }}
                        </div>
                        {{# } }}
                        <div class="price"><span>{{ d[i].keywords }}</span></div>
                    </div>
                    {{# if(d[i].is_close){ }}
                    <div class="is_close close_s">CLOSE</div>
                    {{# }else{ }}
                    <div class="is_close">OPEN</div>
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
                        <div class="more">{{ getLangStr('_EVENT_NUM_',d[i].coupon_count) }}</div>
                        {{# } }}
                    </div>
                    {{# } }}
                </dd>
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
    </div>
</div>
<div id="pageLoadTipShade" class="pageLoadTipBg">
    <div id="pageLoadTipBox" class="pageLoadTipBox">
        <div class="pageLoadTipLoader">
            <div style="background-image:url({pigcms{$config.shop_load_bg});"><!--img src="{pigcms{$static_path}shop/images/pageTipImg.png"/--></div>
        </div>
    </div>
</div>
<include file="Public:footer"/>
<script type="text/javascript">
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
</script>
{pigcms{$shareScript}
</body>
</html>
