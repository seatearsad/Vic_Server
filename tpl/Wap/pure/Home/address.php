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
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/shopBase.css?t={pigcms{$_SERVER.REQUEST_TIME}"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/jquery.lazyload.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?220" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/common.js?t={pigcms{$_SERVER.REQUEST_TIME}" charset="utf-8"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en"></script>
    <script type="text/javascript">
        <if condition="$user_long_lat">var last_user_long = "{pigcms{$user_long_lat.long}",last_user_lat = "{pigcms{$user_long_lat.lat}";<else/>var last_user_long = '0',last_user_lat  = '0';</if>
        var open_extra_price =Number("{pigcms{$config.open_extra_price}");
        var user_long = '0',user_lat  = '0';var user_address='';var ajax_url_root = "{pigcms{$config.site_url}/wap.php?c=Shop&a=";var check_cart_url = "{pigcms{$config.site_url}/wap.php?c=Shop&a=confirm_order";var ajax_map_url = "{pigcms{$config.site_url}/index.php?g=Index&c=Map&a=suggestion&city_id={pigcms{$config.now_city}";var get_route_url = "{pigcms{:U('Group/get_route')}";var baiduToGcj02Url = "{pigcms{:U('Userlonglat/baiduToGcj02')}";var city_id="{pigcms{$config.now_city}";var cat_url="",sort_url="",type_url="";var noAnimate= true;var userOpenid="{pigcms{$_SESSION.openid}";var shopShareUrl = "{pigcms{$config.site_url}{pigcms{:U('Shop/index',array('openid'=>$_SESSION['openid']))}&shop-id=",shopReplyUrl = "{pigcms{$config.site_url}/index.php??g=Index&c=Reply&a=ajax_get_list&order_type=3&parent_id=";</script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
</head>
<style>
    .pageLoadTipLoader::before{
        border-bottom: #ffffff solid 5px;
        border-top: rgba(255,255,255,0.35) solid 5px;
        border-right: rgba(255,255,255,0.35) solid 5px;
        border-left: rgba(255,255,255,0.35) solid 5px;
    }
    .searchBtn.so{
        background-color: #ffa52d;
    }
</style>
<body>
<div id="pageLoadTipShade" class="pageLoadTipBg">
    <div id="pageLoadTipBox" class="pageLoadTipBox">
        <div class="pageLoadTipLoader">
            <div style="background-image:url({pigcms{$config.shop_load_bg});"><!--img src="{pigcms{$static_path}shop/images/pageTipImg.png"/--></div>
        </div>
    </div>
</div>
<div id="pageAddress">
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

<script>
    var window_width = $(window).width();

    $("#pageAddressSearchTxt").focus(function () {
        initAutocomplete();
    });

    $(function () {
        showAddress();
    });

    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('pageAddressSearchTxt'), {types: ['geocode'],componentRestrictions: {country: ['ca']}});
        autocomplete.addListener('place_changed', fillInAddress);
    }

    function fillInAddress() {
        var place = autocomplete.getPlace();

        $.cookie('shop_select_address', place.formatted_address,{expires:700,path:"/"});
        $.cookie('shop_select_lng', place.geometry.location.lng(),{expires:700,path:"/"});
        $.cookie('shop_select_lat', place.geometry.location.lat(),{expires:700,path:"/"});
        //wap
        $.cookie('userLocationName', place.formatted_address,{expires:700,path:"/"});
        $.cookie('userLocationLong',place.geometry.location.lng(),{expires:700,path:'/'});
        $.cookie('userLocationLat',place.geometry.location.lat(),{expires:700,path:'/'});

        var add_com = place.address_components;
        var is_get_city = false;
        for(var i=0;i<add_com.length;i++){
            if(add_com[i]['types'][0] == 'locality'){
                is_get_city = true;
                var city_name = add_com[i]['long_name'];
                $.post("{pigcms{:U('Index/ajax_city_name')}",{city_name:city_name},function(result){
                    if (result.error == 1){
                        //$("input[name='city_id']").val(0);
                    }else{
                        //$("input[name='city_id']").val(result['info']['city_id']);
                        $.cookie('userLocationCity', result['info']['city_id'],{expires:700,path:"/"});
                    }
                    window.location.href = './wap.php';
                },'JSON');
            }
        }
    }

    var hasLoadAddress=false,loadAddressTimer=null,addressGeocoder = false;
    function showAddress(){
        nowPage = 'address';
        $('#pageAddress').addClass('nowPage').show().siblings('.pageDiv').removeClass('nowPage').hide();

        if(user_long == "0" || $('#pageAddressHeader').hasClass('mustHideBack')){
            $('#pageAddressHeader').addClass('hideBack');
            $('#pageAddressSearchTxt').width(window_width-74-32-6);
            $('#pageAddressHeader').removeClass('mustHideBack');
        }else{
            $('#pageAddressHeader').removeClass('hideBack');
            $('#pageAddressSearchTxt').width(window_width-124-32);
        }
        addressGeocoder = true;

        $('#pageAddressLocationList dl').html('<div style="height:40px;line-height:40px;background:white;padding-left:12px;">'+ getLangStr('_BEING_POSITION_') +'</div>');
        getUserLocation({'useHistory':false,okFunction:'getListGeocoderbefore',okFunctionParam:[true],errorFunction:'getAddressGeocoderError',errorFunctionParam:[false]});
        if(hasLoadAddress == false){
            $('#pageAddressBackBtn').click(function(){
                $('#pageAddressSearchDel').trigger('click');
                goBackPage();
            });

            // $("#pageAddressSearchTxt").bind('input', function(e){
            //     var address = $.trim($(this).val());
            //     if(address.length > 0){
            //         $('#pageAddressSearchDel,#pageAddressSearchContent').show();
            //         $('#pageAddressContent').hide();
            //
            //         clearTimeout(loadAddressTimer);
            //         loadAddressTimer = setTimeout("searchAddress('"+address+"')", 500);
            //         $('#pageAddressSearchBtn').addClass('so');
            //     }else{
            //         $('#pageAddressSearchDel').hide();
            //         $('#pageAddressSearchBtn').removeClass('so');
            //
            //         $('#pageAddressContent').show();
            //         $('#pageAddressSearchContent').hide();
            //     }
            // });
            // $('#pageAddressSearchBtn').click(function(){
            //     var address = $.trim($("#pageAddressSearchTxt").val());
            //     searchAddress(address);
            // });

            $('#pageAddressSearchDel').click(function(){
                //$('#pageAddressSearchTxt').val('').trigger('input');
                /* $('#pageAddressSearchDel').hide(); */
            });

            $(document).on('click','.searchAddressList dd',function(){
                $('#pageAddressSearchDel').trigger('click');
                user_long = $(this).data('long');
                user_lat = $(this).data('lat');

                city_id = $(this).data('city');
                if(typeof(city_id) != 'undefined')
                    $.cookie('userLocationCity', city_id,{expires:700,path:"/"});

                $('#locationText').html($(this).data('name'));

                $.cookie('userLocation',user_long+','+user_lat,{expires:700,path:'/'});
                $.cookie('userLocationLong',user_long,{expires:700,path:'/'});
                $.cookie('userLocationLat',user_lat,{expires:700,path:'/'});
                $.cookie('userLocationName',$(this).data('name'),{expires:700,path:'/'});
                if($(this).data('id')){
                    $.cookie('userLocationId',$(this).data('id'),{expires:700,path:'/'});
                }

                window.location.href = "wap.php";
                mustShowShopList = true;
                location.hash = 'list';
                return false;
            });

            $.getJSON(ajax_url_root+'ajax_address',function(result){
                if(result.length > 0){
                    laytpl($('#listAddressListTpl').html()).render(result, function(html){
                        $('#pageAddressUserList .content').html(html);
                    });
                }else{
                    $('#pageAddressUserList').hide();
                }
                pageLoadHides();
            });

            hasLoadAddress = true;
        }else{
            pageLoadHides();
        }
    }

    function getAddressGeocoderError(){
        $('#pageAddressLocationList dl').html('<div style="height:40px;line-height:40px;background:white;padding-left:12px;">Address not found</div>');
        $('#pageAddressLocationList').hide();
    }

    function searchAddress(address){
        $.get(ajax_map_url, {query:address}, function(data){
            if(data.status == 1){
                $('#pageAddressSearchContent dl').empty();
                var result = data.result;
                var addressHtml = '';
                for(var i=0;i<result.length;i++){
                    if(result[i]['long']){
                        addressHtml += '<dd data-long="'+result[i]['long']+'" data-lat="'+result[i]['lat']+'" data-name="'+result[i]['name']+'">';
                        addressHtml += '<div class="name">'+result[i]['name']+'</div>';
                        addressHtml += '<div class="desc">'+result[i]['address']+'</div>';
                        addressHtml += '</dd>';
                    }
                }
                $('#pageAddressSearchContent dl').html(addressHtml);
            }
        });
    }
    function pageLoadHides(){
        $('#pageLoadTipShade').hide();
    }
</script>

<script id="listAddressListTpl" type="text/html">
    {{# for(var i = 0, len = d.length; i < len; i++){ }}
    <dd data-long="{{ d[i].long }}" data-lat="{{ d[i].lat }}" data-name="{{ d[i].street }}" data-id="{{ d[i].id }}" data-city="{{ d[i].city_id}}">
        <div class="name">{{ d[i].street }} {{ d[i].house }}</div>
        <div class="desc">{{ d[i].name }} {{ d[i].phone }}</div>
    </dd>
    {{# } }}
</script>
</body>
</html>
