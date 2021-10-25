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
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en"></script>
    <script type="text/javascript">
        <if condition="$user_long_lat">var last_user_long = "{pigcms{$user_long_lat.long}",last_user_lat = "{pigcms{$user_long_lat.lat}";<else/>var last_user_long = '0',last_user_lat  = '0';</if>
        var open_extra_price =Number("{pigcms{$config.open_extra_price}");
        var user_long = '0',user_lat  = '0';var user_address='';var ajax_url_root = "{pigcms{$config.site_url}/wap.php?c=Shop&a=";var check_cart_url = "{pigcms{$config.site_url}/wap.php?c=Shop&a=confirm_order";var ajax_map_url = "{pigcms{$config.site_url}/index.php?g=Index&c=Map&a=suggestion&city_id={pigcms{$config.now_city}";var get_route_url = "{pigcms{:U('Group/get_route')}";var baiduToGcj02Url = "{pigcms{:U('Userlonglat/baiduToGcj02')}";var city_id="{pigcms{$config.now_city}";var cat_url="",sort_url="",type_url="";var noAnimate= true;var userOpenid="{pigcms{$_SESSION.openid}";var shopShareUrl = "{pigcms{$config.site_url}{pigcms{:U('Shop/index',array('openid'=>$_SESSION['openid']))}&shop-id=",shopReplyUrl = "{pigcms{$config.site_url}/index.php??g=Index&c=Reply&a=ajax_get_list&order_type=3&parent_id=";</script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
    <include file="Public:facebook"/>
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
    #pageLoadTipShade,#pageAddress,#pageLoadTipBox,.searchHeader{
        max-width: 640px;
        min-width: 320px;
        margin: 0 auto;
    }
    .searchTxt{
        width: 90%;
    }
    .list_outer{
        width: 95%;
        margin-bottom: 0;
        margin-left: auto;
        margin-right: auto;
    }
    .list_header{
        height: 10px;
        background-color: #ffffff;
        border-radius: 10px 10px 0px 0px;
    }
    .dd_line{
        display: flex;
    }
    .select_radio{
        padding: 5px;
    }
    .select_value{
        padding-left: 10px;
    }
    .list_footer{
        height: 10px;
        background-color: #ffffff;
        border-radius: 0px 0px 10px 10px;
    }

    .regular-radio {
        display: none;
    }

    .regular-radio + label {
        -webkit-appearance: none;
        background-color: #eeeeee;
        border: 1px solid #eeeeee;
        box-shadow: 0 1px 2px rgba(0,0,0,0.00), inset 0px -15px 10px -12px rgba(0,0,0,0.05);
        padding: 9px;
        border-radius: 50px;
        display: inline-block;
        position: relative;
        padding: 14px;
    }

    .regular-radio:checked + label:after {
        content: ' ';
        border-radius: 50px;
        position: absolute;
        background: #ffa52d;
        box-shadow: inset 0px 0px 10px rgba(0,0,0,0.3);
        text-shadow: 0px;
        font-size: 32px;
        width: 20px;
        height: 20px;
        left: 4px;
        top: 4px;
    }

    .regular-radio:checked + label {
        background-color: #ffffff;
        color: #ffa52d;
        border: 2px solid #ffa52d;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px -15px 10px -12px rgba(0,0,0,0.05), inset 15px 10px -12px rgba(255,255,255,0.1), inset 0px 0px 10px rgba(0,0,0,0.1);
    }

    .regular-radio + label:active, .regular-radio:checked + label:active {
        box-shadow: 0 1px 2px rgba(0,0,0,0.05), inset 0px 1px 3px rgba(0,0,0,0.1);
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
        <div id="pageAddressSearchBtn" class="searchBtn">&nbsp;</div>
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
        if(typeof(autocomplete) == 'undefined') {
            autocomplete = new google.maps.places.Autocomplete(document.getElementById('pageAddressSearchTxt'), {
                types: ['geocode'],
                componentRestrictions: {country: ['ca']}
            });
            autocomplete.addListener('place_changed', fillInAddress);
            // need to stop prop of the touchend event
            if (navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) {
                setTimeout(function () {
                    var container = document.getElementsByClassName('pac-container')[0];
                    container.addEventListener('touchend', function (e) {
                        e.stopImmediatePropagation();
                    });
                }, 500);
            }
        }
    }
    //google结果列表点击事件
    function fillInAddress() {

        var place = autocomplete.getPlace();

        //以下为给 添加地址提供的代码
        //info = JSON.parse($.cookie('user_address'));
        var info = new Object();
        info.adress = place.formatted_address;
        info.longitude = place.geometry.location.lng();
        info.latitude = place.geometry.location.lat();
        //
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
                        info.city = 0;
                        info.province = 0;
                        info.city_name = 'N/A';
                        $.cookie('userLocationCity', 0,{expires:700,path:"/"});
                    }else{
                        info.city = result['info']['city_id']
                        info.province = result['info']['province_id'];
                        info.city_name = city_name;
                        //$("input[name='city_id']").val(result['info']['city_id']);
                        $.cookie('userLocationCity', result['info']['city_id'],{expires:700,path:"/"});
                    }
                    $.cookie('user_address', JSON.stringify(info));
                    //window.location.href = './wap.php';
                    window.location.href = './wap.php?g=Wap&c=My&a=edit_adress&from=map';
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
            //$('#pageAddressSearchTxt').width(window_width-74-32-6);
            $('#pageAddressHeader').removeClass('mustHideBack');
        }else{
            $('#pageAddressHeader').removeClass('hideBack');
            //$('#pageAddressSearchTxt').width(window_width-124-32);
        }
        addressGeocoder = true;

        $('#pageAddressLocationList dl').html('<div style="height:40px;line-height:40px;background:white;padding-left:12px;">'+ getLangStr('_BEING_POSITION_') +'</div>');
        //getUserLocation({'useHistory':false,okFunction:'getListGeocoderbefore',okFunctionParam:[true],errorFunction:'getAddressGeocoderError',errorFunctionParam:[false]});
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

            var data_id=0;

            $(document).on('click','.searchAddressList dd',function(){
                //从地址列表里选择
                $('#pageAddressSearchDel').trigger('click');
                //alert($(this).index()+" "+$(this).data('name'));

                data_id=$(this).data('id');
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

                //alert($.cookie('userLocationId'));
                // alert($.cookie('userLocationName'));
                $.getJSON(ajax_url_root+"ajax_set_address_default&aid="+$.cookie('userLocationId') ,function(result){
                    if(result.length > 0){

                    }else{
                       alert("Network error, please retry later.");
                    }
                    //alert('123');
                    window.location.href = "wap.php";
                    mustShowShopList = true;
                    location.hash = 'list';
                });

                return false;
            });

            $.getJSON(ajax_url_root+"ajax_address&lastid="+$.cookie('userLocationId') ,function(result){
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
    <div class="list_outer">
    <div class="list_header"></div>
    {{# for(var i = 0, len = d.length; i < len; i++){ }}
        <dd class="dd_line"
            data-long="{{ d[i].long }}"
            data-lat="{{ d[i].lat }}"
            data-name="{{ d[i].street }}"
            data-id="{{ d[i].id }}"
            data-city="{{ d[i].city_id}}">
            <div class="select_radio">
                <input type="radio" id="radio-2-{{i}}" name="radio-2-set" class="regular-radio"
                {{# if(d[i].checked=="1") { }} checked {{# } }}/><label for="radio-2-{{i}}"></label><br/>
            </div>
            <div class="select_value">
                <div class="name">{{ d[i].street }} {{ d[i].house }}</div>
                <div class="desc">{{ d[i].name }} {{ d[i].phone }}</div>
            </div>

        </dd>
    {{# } }}
    <div class="list_footer"></div>
    </div>
</script>

</body>
</html>
