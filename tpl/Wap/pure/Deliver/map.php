<!DOCTYPE html>
 <html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>配送员导航</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
</head>

<body class=" hIphone" style="padding-bottom: initial;background: #ecedf1;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}images/hm.gif" width="0" height="0" style="display:block">
<div id="wrapper" class="">
    <!--div id="fis_elm__2">
        <div id="common-widget-nav" class="common-widget-nav ">
            <div class="left-slogan"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="javaScript:history.back(-1);"></a> </div>
            <div class="center-title"> <a href="javascript:void(0)">配送员导航</a> </div>
            <div class="right-slogan "> <a class="tel-btn icon-refresh-image" href="javascript:" id="refresh"></a> </div>
        </div>
    </div-->
    <div id="fis_elm__4">
        <div id="map" class="order-widget-orderhistory" style="min-height:100px;">
        </div>
    </div>
</div>
<div class="global-mask layout"></div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language={pigcms{:C('DEFAULT_LANG')}"></script>
<script>
var wHeight = $(window).height();
$("#map").css('height', wHeight);
var start = 0;
var end = 0;
$(function(){

    var pyrmont = {lat: {pigcms{$supply['aim_lat']}, lng: {pigcms{$supply['aim_lnt']}};
	var map = null;
	console.log(pyrmont);
	map = new google.maps.Map(document.getElementById('map'), {
		center: pyrmont,
        zoom: 16,
        mapTypeId: 'terrain'
	});
	
	//我的图标
	var marker = new google.maps.Marker({
		position: {lng: {pigcms{$supply['aim_lnt']}, lat: {pigcms{$supply['aim_lat']}},
        map: map,
        title:"Destination",
        icon:"{pigcms{$static_path}images/map/use_icon.png"
    });

    //店铺图标
    var marker2 = new google.maps.Marker({
		position: {lng: {pigcms{$supply['from_lnt']}, lat: {pigcms{$supply['from_lat']}},
        map: map,
        title:"Pick Up Point",
        icon:"{pigcms{$static_path}images/map/store_icon.png"
    });

    //配送员位置
    navigator.geolocation.getCurrentPosition(function (position) {  
        console.log(position);
        var marker = new google.maps.Marker({
                position: {lat: position.coords.latitude, lng:position.coords.longitude},
                map: map,
                icon: "{pigcms{$static_path}/images/map/deliver_pos.png"
            });

        start = {lat: position.coords.latitude, lng:position.coords.longitude};
        <if condition="$supply['status'] eq 2">
        end = {lng:{pigcms{$supply['from_lnt']}, lat:{pigcms{$supply['from_lat']}};
        <else />
        end = {lng:{pigcms{$supply['aim_lnt']}, lat:{pigcms{$supply['aim_lat']}};
        </if>
        seachLine([start,end]);
    });  

        /*
    var geolocation = new BMap.Geolocation();
        geolocation.getCurrentPosition(function(r){
            if(this.getStatus() == BMAP_STATUS_SUCCESS){
                console.log(r);
                var marker = new google.maps.Marker({
                    position: {lat: r.point.lat, lng:r.point.lng},
                    map: map,
                    icon: "{pigcms{$static_path}/images/map/deliver_pos.png"
                });

                start = {lat: r.point.lat, lng:r.point.lng};
                <if condition="$supply['status'] eq 2">
                end = {lng:{pigcms{$supply['from_lnt']}, lat:{pigcms{$supply['from_lat']}};
                <else />
                end = {lng:{pigcms{$supply['aim_lnt']}, lat:{pigcms{$supply['aim_lat']}};
                </if>
                seachLine([start,end]);

            }
            else {
                alert('failed'+this.getStatus());
            }        
        },{enableHighAccuracy: true})*/

        
    var seachLine = function(flightPlanCoordinates){
        var flightPath = new google.maps.Polyline({
            path: flightPlanCoordinates,
            geodesic: true,
            strokeColor: '#FF0000',
            strokeOpacity: 1.0,
            strokeWeight: 2
        });
        flightPath.setMap(map);
    }
    /*
    // 百度地图API功能
    var map = new BMap.Map("map");
    map.centerAndZoom(new BMap.Point({pigcms{$supply['from_lnt']}, {pigcms{$supply['from_lat']}), 15);
	
	var points = [];
	
    //我的图标
    var pt1 = new BMap.Point({pigcms{$supply['aim_lnt']}, {pigcms{$supply['aim_lat']});
    var myIcon = new BMap.Icon("{pigcms{$static_path}images/map/my_pos.png", new BMap.Size(60,60));
    var marker1 = new BMap.Marker(pt1,{icon:myIcon});  // 创建标注
    map.addOverlay(marker1);
	
	points.push(pt1);
    //店铺图标
    var pt2 = new BMap.Point({pigcms{$supply['from_lnt']}, {pigcms{$supply['from_lat']});
    var storeIcon = new BMap.Icon("{pigcms{$static_path}images/map/store_pos.png", new BMap.Size(22,60));
    var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
    map.addOverlay(marker2);
	
	points.push(pt2);
	
	map.setViewport(points);
    //配送员位置
    var my_Blng = 0;
    var my_Blat = 0;
    var deliverMk;
    navigator.geolocation.getCurrentPosition(function(position){
        var lng = position.coords.longitude;
        var lat = position.coords.latitude;
        var point = {};
        point.lng = lng;
        point.lat = lat;
		var points = [];
		
		points.push(new BMap.Point(lng,lat));
		
        BMap.Convertor.translate(point, 0, function(Bpoint){
            var my_Blng = Bpoint.lng;
            var my_Blat = Bpoint.lat;
            var pt3 = new BMap.Point(my_Blng, my_Blat);
            var deliverIcon = new BMap.Icon("{pigcms{$static_path}images/map/deliver_pos.png", new BMap.Size(22,60));
            deliverMk = new BMap.Marker(pt3,{icon:deliverIcon});
            map.addOverlay(deliverMk);
			points.push(pt3);
			map.setViewport(points);
			
            start = new BMap.Point(my_Blng, my_Blat);
            <if condition="$supply['status'] eq 2">
            end = new BMap.Point({pigcms{$supply['from_lnt']}, {pigcms{$supply['from_lat']});
            <else />
            end = new BMap.Point({pigcms{$supply['aim_lnt']}, {pigcms{$supply['aim_lat']});
            </if>
            seachLine(start, end);
            map.setViewport([start, end]);
        });
    });

    //规划路线
    function seachLine(start, end) {
        var driving = new BMap.WalkingRoute(map, {renderOptions:{map: map, autoViewport: true}});    //驾车实例
        driving.search(start, end);
        driving.setSearchCompleteCallback(function(){
            function move() {
                navigator.geolocation.getCurrentPosition(function(position){
                    var lng = position.coords.longitude;
                    var lat = position.coords.latitude;
                    var point = {};
                    point.lng = lng;
                    point.lat = lat;
                    BMap.Convertor.translate(point, 0, function(Bpoint){
                        var my_Blng = Bpoint.lng;
                        var my_Blat = Bpoint.lat;
                        var movePos = new BMap.Point(my_Blng, my_Blat);
                        deliverMk.setPosition(movePos);
                    });
                });
            }

            setInterval(move, 500);
        });
    }

    map.enableScrollWheelZoom();
    map.enableContinuousZoom();*/
});
var ua = navigator.userAgent;
if(!ua.match(/TuttiDeliver/i)) {
    navigator.geolocation.getCurrentPosition(function (position) {
        updatePosition(position.coords.latitude,position.coords.longitude);
    });
}
//ios app 更新位置
function updatePosition(lat,lng){
    var message = '';
    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
        if(result){
            message = result.message;
        }else {
            message = 'Error';
        }
    });

    return message;
}
</script>
</body>
</html>