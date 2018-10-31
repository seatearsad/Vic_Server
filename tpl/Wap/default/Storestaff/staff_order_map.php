<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="keywords" content="{pigcms{$config.seo_keywords}">
    <meta name="description" content="{pigcms{$config.seo_description}">
    <title>查看地址</title>
</head>
<body class=" hIphone" style="padding-bottom: initial;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_5e96991.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}images/hm.gif" width="0" height="0" style="display:block">
<div id="wrapper" class="">
    <div id="fis_elm__2">
        <div id="address-widget-map" class="address-widget-map">
            <div class="address-map-nav">
                <div class="left-slogan" style="margin-top: 12px"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="javascript:history.go(-1);"></a></div>
                <div class="center-title" style="margin-top: 4px"> <i class="icon-location" data-node="icon"></i>
                    <div class="ui-suggestion-mask">
                        <input type="text" placeholder="请输入小区、大厦或学校" id="se-input-wd" autocomplete="off">
                        <div class="ui-suggestion-quickdel"></div>
                    </div>
                </div>
                <div class="his-postion" data-node="historypos" style="">
                    <div class="ui-suggestion" id="ui-suggestion-0" style="top: 0px; left: 0px; position: relative;">
                        <div class="ui-suggestion-content" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></div>
                        <div class="ui-suggestion-button"><span class="ui-suggestion-clear" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);">清除历史记录</span><span class="ui-suggestion-close" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></span></div>
                    </div>
                </div>
            </div>
            <div id="fis_elm__3">
                <!--div class="map" style="display: none">
                	<div class="MapHolder" id="cmmap"></div>
					<div class="dot" style="display:block;"></div>
                </div-->
                <div class="mapaddress" data-node="mapaddress">
                    <ul id="addressShow"> </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="fis_elm__4"></div>
<div class="global-mask layout"></div>
<div id="fis_elm__6"></div>
<div id="fis_elm__7"></div>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=zh-CN"></script>
<script type="text/javascript">
    var address = '{pigcms{$address}';
    var timeout = 0;
    var map = null;
    var pyrmont = {lat: 48.430168, lng: -123.343033};
    function initGoogleMap(lat, lng) {
        map = new google.maps.Map("", {
            center: {lat: lat, lng: lng},
            zoom: 15
        });
        var request = {
            location: {lat: lat, lng: lng},
            name:'*',
            rankBy: google.maps.places.RankBy.DISTANCE,
        };
        service = new google.maps.places.PlacesService(map);
        service.nearbySearch(request, function(results, status){
            if (status == google.maps.places.PlacesServiceStatus.OK) {
                //console.log(results);
                getPositionAdress(results);
                for (var i = 0; i < results.length; i++) {

                }
            }
        });
    }

    $(document).ready(function(){
        $("#se-input-wd").bind('input', function(e){
            var address = $.trim($('#se-input-wd').val());
            if(address.length>0 && address !== '请输入小区、大厦或学校'){
                $('#addressShow').empty();
                clearTimeout(timeout);
                timeout = setTimeout("search('"+address+"')", 500);
            }
        });

        $('#addressShow').delegate("li","click",function(){
            info = JSON.parse($.cookie('staff_address'));
            info.adress = $(this).attr("sname");
            info.longitude = $(this).attr("lng");
            info.latitude = $(this).attr("lat");
            $.cookie('staff_address', JSON.stringify(info));
//            location.href = "{pigcms{:U('My/edit_adress', $params)}&adress_id="+info.id;
            location.href = "{pigcms{:U('Storestaff/add_shop_order')}&adress_id="+info.id;
        });

        // if (navigator.geolocation){
        //     navigator.geolocation.getCurrentPosition(function(position){
        //         initGoogleMap(position.coords.latitude, position.coords.longitude);
        //     });
        // }else{
        //     alert("Geolocation is not supported by this browser.");
        // }
    });

    function search(address)
    {
        $.get('index.php?g=Index&c=Map&a=suggestion', {query:address}, function(data){
            if(data.status == 1){
                getAdress(data.result);
            }
        });
    }

    function getPositionAdress(result){

        var re = [];
        //re.push({'name':result.sematic_description,'address':result.formatted_address,'long':result.location.lng,'lat':result.location.lat});
        for(var i in result){
            re.push({'name':result[i].name,'address':result[i].vicinity,'long':result[i].geometry.location.lng(),'lat':result[i].geometry.location.lat()});
        }
        getAdress(re);

    }
    function getAdress(re){
        $('#addressShow').html('');
        var addressHtml = '';
        for(var i=0;i<re.length;i++){
            if (re[i]['long'] == null || re[i]['lat'] == null) continue;
            addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'" sname="'+re[i]['name']+'" class="addresslist">';
            addressHtml += '<div class="mapaddress-title"> <span class="icon-location" data-node="icon"></span> <span class="recommend"> '+(i == 0 ? '[推荐位置]' : '')+'   '+re[i]['name']+' </span> </div>';
            addressHtml += '<div class="mapaddress-body"> '+re[i]['address']+' </div>';
            addressHtml += '</li>';
        }
        $('#addressShow').append(addressHtml);
    }
    //测试使用
    // $(function () {
    //     info = JSON.parse($.cookie('staff_address'));
    //     info.adress = "123";
    //     info.longitude = "-123.343033";
    //     info.latitude = "48.430168";
    //     $.cookie('staff_address', JSON.stringify(info));
    //     info.id = '1';
    //     location.href = "{pigcms{:U('Storestaff/add_shop_order')}&adress_id="+info.id;
    // })
</script>
</html>