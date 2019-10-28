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
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?t={pigcms{$_SERVER.REQUEST_TIME}" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index.css"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/home_shop.css"/>
        <include file="Public:facebook"/>
	</head>
    <style>
        #container{
            width: 100%;
            padding-top: 60px;
        }
        .cate_left,.cate_right,.sub_left,.sub_right{
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 16px;
            background-repeat: no-repeat;
            background-position: center;
        }
        #category{
            margin-top: 2px;
        }
        .gray_line{
            width: 100%;
            height: 2px;
            margin-top: 25px;
            margin-bottom: 25px;
            background-color: #cccccc;
        }
        .gray_k{
            width: 10%;
            height: 2px;
            background-color: #f4f4f4;
            margin: -27px auto 0 auto;
        }
        #sub_cate{
            width: 100%;
            height: 30px;
        }
        #storeList{
            width: 96%;
            margin: 15px auto;
        }
        #storeListLoadTip{
            width: 100%;
            text-align: center;
            display: none;
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
    </style>
	<body>
    <include file="Public:header"/>
    <div id="container">
        <div id="category">
            <div class="cate_left"></div>
            <ul>
                <volist name="category" id="vo">
                    <a href="#cat-{pigcms{$vo['id']}">
                        <li data-id="{pigcms{$vo['id']}">
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
        <div id="sub_cate">
            <div class="sub_left"></div>
            <ul>

            </ul>
            <div class="sub_right"></div>
        </div>
        <div class="gray_line"></div>
        <div class="gray_k"></div>
        <div id="storeList">
            <dl class="dealcard"></dl>
            <div id="storeListLoadTip">{pigcms{:L('_LOADING_TXT_')}</div>
        </div>
        <script id="listShopTpl" type="text/html">
            {{# for(var i = 0, len = d.length; i < len; i++){ }}
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
                        <span class="delivery">{pigcms{:L('_DELI_PRICE_')} ${{ d[i].delivery_money }}</span>
                        <!--span class="delivery">{pigcms{:L('_PACK_PRICE_')} ${{ d[i].pack_fee }}</span-->
                        {{# if(d[i].delivery_system){ }}
                        <!--em class="location-right">{pigcms{:L('_PLAT_DIST_')}</em-->
                        {{# }else{ }}
                        <!--em class="location-right">{pigcms{:L('_SHOP_DIST_')}</em-->
                        {{# } }}
                    </div>
                    {{# } }}
                    <div class="price"><span>{{ d[i].keywords }}</span></div>
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
        </script>
    </div>
    <include file="Public:footer"/>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Shop",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Shop/index')}",
				"tTitle": "{pigcms{$config.shop_alias_name|default="快店"} - {pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};

			var page = 1;
            var user_long = $.cookie('userLocationLong');
            var user_lat = $.cookie('userLocationLat');

            var is_more = false;

            function showShopList(newPage) {
                if(newPage){
                    page = 1;
                }
                
                var cat_id = 0;
                var cat_fid = 0;
                if(curr_sub_id != 0){
                    cat_id = curr_sub_id;
                    cat_fid = curr_cat_id;
                }else{
                    cat_id = 0;
                    cat_fid = curr_cat_id;
                }
                $.getJSON("{pigcms{:U('ajax_list')}",{cat_id:cat_id,cat_fid:cat_fid,user_lat:user_lat,user_long:user_long,page:page,key:keyword},function(result){
                    if(result.store_list && result.store_list.length > 0){
                        laytpl($('#listShopTpl').html()).render(result.store_list, function(html){
                            if(newPage){
                                $('#storeList .dealcard').html(html);
                                $('#storeList').show();
                            }else{
                                $('#storeList .dealcard').append(html);
                            }
                        });

                        is_more = result.has_more;
                        if(result.has_more == false){
                            $('#storeListLoadTip').hide();
                        }else{
                            $('#storeListLoadTip').show();
                        }
                    }else{
                        $('#storeList .dealcard').html('');
                        is_more = false;
                        $('#storeListLoadTip').hide();
                        if(keyword != ''){
                            $('#storeList .dealcard').html('<div style="text-align: center">Sorry, no results found.</div>');
                        }
                    }
                    //isSearchListShow = false;
                    //pageLoadHides();
                });
            }

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

            $(window).bind('hashchange', function() {
                show_cate(window.location.hash);
            });
            //当前分类id
            var curr_cat_id = 0;
            var curr_sub_id = 0;
            show_cate(window.location.hash);
            function show_cate(hash) {
                if(hash != ''){
                    var cat_str = hash.split('#')[1].split('-');
                    if(cat_str[0] == 'cat'){
                        layer.open({
                            type:2
                        });
                        $('#sub_cate').show();

                        var cat_id = cat_str[1];
                        if(cat_id != curr_cat_id){
                            curr_cat_id = cat_id;
                            $('#category').find('li').each(function () {
                                if($(this).attr('data-id') == cat_id) {
                                    $(this).css('background', '#f5f5f5f5');
                                    getCateSubList(cat_id);
                                }else{
                                    $(this).css('background', 'none');
                                }
                            });
                            if (cat_str[2]) {
                                curr_sub_id = cat_str[2];
                            } else {
                                curr_sub_id = 0;
                            }
                        }else {
                            layer.closeAll();
                            //子集
                            if (cat_str[2]) {
                                curr_sub_id = cat_str[2];
                            } else {
                                curr_sub_id = 0;
                            }
                            $('#sub_cate').find('li').each(function () {
                                if ($(this).attr('data-id') == curr_sub_id) {
                                    $(this).attr('class', 'cur');
                                } else {
                                    $(this).removeClass();
                                }
                            });
                            showShopList(true);
                        }
                    }else{
                        $('#sub_cate').hide();
                    }
                }else{
                    $('#sub_cate').hide();
                }
            }
            
            function getCateSubList(cat_id) {
                $.post("{pigcms{:U('ajax_cat')}",{'id':cat_id},function(data){
                    layer.closeAll();
                    if(data.status == 1){
                        addSubList(data.list);
                        showShopList(true);
                    }
                },'json');
            }

            var base_width = $('#sub_cate').find('ul').width();
            var act_width = 0;
            var move_size = 0;
            function addSubList(list) {
                var html = '';
                for(var i=0;i<list.length;i++){
                    html += '<a href='+list[i]['url']+'>';
                    html += '<li data-id='+list[i]['id']+'>'+list[i]['title']+'</li>';
                    html += '</a>';
                }
                $('#sub_cate').find('ul').html(html);

                base_width = $('#sub_cate').find('ul').width();
                act_width = 0;
                $('#sub_cate').find('li').each(function () {
                    if($(this).attr('data-id') == curr_sub_id){
                        $(this).attr('class','cur');
                    }else{
                        $(this).removeClass();
                    }
                    act_width += $(this).width()+parseInt($(this).css('padding-left'))*2;
                });

                setSubMove();
            }

            $('#sub_cate').find('ul').scroll(function () {
                move_size = $(this).scrollLeft();
                setSubMove();
            });
            
            function setSubMove() {
                if(move_size > 0){
                    if($('.sub_left').css('opacity') == 0){
                        $('.sub_left').css('opacity',1);
                        //$('.sub_left').bind('click',subClickLeft);
                    }
                }else{
                    $('.sub_left').css('opacity',0);
                    //$('.sub_left').unbind();
                }

                if(base_width < act_width-move_size-10){
                    if($('.sub_right').css('opacity') == 0){
                        $('.sub_right').css('opacity',1);
                        //$('.sub_right').bind('click',subClickRight);
                    }
                }else{
                    $('.sub_right').css('opacity',0);
                    //$('.sub_right').unbind();
                }

                //$('#sub_cate').find('ul').animate({scrollLeft:move_size},300);
            }
            
            function subClickRight() {
                move_size += base_width/5;
                if(move_size > act_width-base_width){
                    move_size = act_width-base_width;
                }
                setSubMove();
            }
            
            function subClickLeft() {
                move_size -= base_width/5;
                if(move_size < 0){
                    move_size = 0;
                }
                setSubMove();
            }

            showShopList(true);

            $(window).scroll(function(){
                var doc_height = $(document).height();
                var scroll_top = $(document).scrollTop();
                var window_height = $(window).height();

                if(scroll_top + window_height >= doc_height-60){
                    if(is_more){
                        is_more = false;
                        page++;
                        showShopList(false);
                    }
                }
            });

            $(document).on('click','.recommend-link-url',function(){
                var url = $(this).data('url');
                window.location.href = url;
            });
		</script>
		{pigcms{$shareScript}
	</body>
</html>