<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{:L('_COURIER_TXT_')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script>
	$(function(){
		$(".startOrder,.stopOrder").click(function(){
			$.get("/wap.php?g=Wap&c=Deliver&a=index&action=changeWorkstatus&type="+$(this).attr('ref'), function(){
				window.location.reload();
			});
		});
	})
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
    //更新app 设备token
    var device_token = '';
    function pushDeviceToken(token) {
        device_token = token;
        var message = '';
        if(device_token != "{pigcms{$deliver_session['device_id']}") {
            $.post("{pigcms{:U('Deliver/update_device')}", {'token': token}, function (result) {
                if (result) {
                    message = result.message;
                } else {
                    message = 'Error';
                }
            });
        }
        return message;
    }
    //更新Android 设备token
    if(typeof (window.linkJs) != 'undefined'){
        var android_token = window.linkJs.getDeviceId();
        if(android_token != "{pigcms{$deliver_session['device_id']}"){
            var message = '';
            $.post("{pigcms{:U('Deliver/update_device')}", {'token':android_token}, function(result) {
                if(result){
                    message = result.message;
                }else {
                    message = 'Error';
                }
            });
        }
    }
</script>
<style>
	.startOrder{color: #fff;float: right;background: green;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
	.stopOrder{color: #000;float: right;background: #ccc;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
</style>
</head>
<body>
	<section class="clerk">
		<div class="clerk_top">
			<div class="fl clerk_img">
				<if condition="$deliver_session['store_id']">
                <span style="background: url({pigcms{$store['image']}) center no-repeat; background-size: contain;"></span>
                <else />
                <span style="background: url(<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>) center no-repeat; background-size: contain;"></span>
                </if>
			</div>
			<div class="clerk_r">
				<h2>{pigcms{$deliver_session['name']}<i> , {pigcms{:L('_HELLO_TXT_')}</i></h2>
				<p>
					<if condition="$deliver_session['store_id']">{pigcms{:L('_COURIER_TXT_')}-{pigcms{$store['name']}<else />{pigcms{:L('_COURIER_TXT_')}</if>
                    <if condition="$city['urgent_time'] neq 0">
                        <if condition="$deliver_session['work_status'] eq '1'">
                        <a href="javascript:void(0)" class="startOrder" ref="0">{pigcms{:L('_CLOCK_IN_')}</a>
                        <else />
                        <a href="javascript:void(0)" class="stopOrder" ref="1">{pigcms{:L('_CLOCK_OUT_')}</a>
                        </if>
                    <else />
                        - <if condition="$deliver_session['work_status'] eq '1'">
                            <span>Off Work</span>
                        <else />
                            <span>Working</span>
                        </if>
                    </if>
				</p>
			</div>
		</div>
		<div class="clerk_end">
			<ul class="clr">
				<li class="Grab fl">
					<a href="{pigcms{:U('Deliver/grab')}">
						<i></i>
						<h2 id="gray_count">{pigcms{$gray_count}</h2>
						<p>{pigcms{:L('_C_ORDER_PENDING_')}</p>
					</a>
				</li>
				<li class="Handle fl">
					<a href="{pigcms{:U('Deliver/pick')}">
						<i></i>
						<h2 id="deliver_count">{pigcms{$deliver_count}</h2>
						<p>{pigcms{:L('_C_PROCESSING_')}</p>
					</a>
				</li>
				<li class="complete fl">
					<a href="{pigcms{:U('Deliver/finish')}">
						<i></i>
						<h2 id="finish_count">{pigcms{$finish_count}</h2>
						<p>{pigcms{:L('_C_COMPLETED')}</p>
					</a>
				</li>
			</ul>
		</div>
	</section>
	<section class="Map" id="biz-map">
	</section>
	<section class="bottom">
		<div class="bottom_n">
			<ul>
				<li class="Statistics fl">
                    <a href="{pigcms{:U('Deliver/schedule')}">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
				</li>
				<li class="home homeon fl">
					<a href="javascript:void(0);"><i></i>{pigcms{:L('_HOME_TXT_')}</a>
				</li>
				<li class="My fl">
					<a href="{pigcms{:U('Deliver/info')}">{pigcms{:L('_PROFILE_TXT_')}</a>
				</li>
			</ul>
		</div>
	</section>
	<script type="text/javascript">$('#biz-map').height($(window).height()-267);</script>
<!-- 	<script src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0"></script> -->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language={pigcms{:C('DEFAULT_LANG')}"></script>
	<script type="text/javascript">
// 	$(function(){
// 		map = new google.maps.Map(document.getElementById('biz-map'), {
// 			center: {lat:{pigcms{$deliver_session['lat']}, lng:{pigcms{$deliver_session['lng']}},
// 			zoom: 16
// 		});
//         var ua = navigator.userAgent;
//         if(!ua.match(/TuttiDeliver/i)) {
//             navigator.geolocation.getCurrentPosition(function (position) {
//                 console.log(position);
//                 //list_detail(position.coords.latitude, position.coords.longitude);
//                 map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
//
//                 //我的图标
//                 var marker = new google.maps.Marker({
//                     position: {lng: position.coords.longitude, lat: position.coords.latitude},
//                     map: map,
//                     icon: "{pigcms{$static_path}images/map/my_pos.png"
//                 });
//
//             });
//         }
// /*
// 			var geolocation = new BMap.Geolocation();
// 			geolocation.getCurrentPosition(function(r){
// 				if(this.getStatus() == BMAP_STATUS_SUCCESS){
// 					console.log(r);
// 					map.setCenter({lat: r.point.lat, lng:r.point.lng});
// 					var marker = new google.maps.Marker({
// 						position: map.getCenter(),
// 						map: map
// 					});
// 				}
// 				else {
// 					alert('failed'+this.getStatus());
// 				}        
// 			},{enableHighAccuracy: true})*/
//
//
//	
//
// // 				var map = new BMap.Map("biz-map");
// // 				var point = new BMap.Point({pigcms{$deliver_session['lng']}, {pigcms{$deliver_session['lat']});
// // 				map.centerAndZoom(point, 16);
//
// // 				var geolocation = new BMap.Geolocation();
// // 				geolocation.getCurrentPosition(function(r){
// // 					if(this.getStatus() == BMAP_STATUS_SUCCESS){
// // 						map.panTo(r.point);
// // 						var mk = new BMap.Marker(r.point);
// // 						map.addOverlay(mk);
// // // 						mk.setAnimation(BMAP_ANIMATION_BOUNCE); 
// // // 						alert('您的位置：'+r.point.lng+','+r.point.lat);
// // 					}
// // 					else {
// // 						alert('failed'+this.getStatus());
// // 					}        
// // 				},{enableHighAccuracy: true})
//
// 				setInterval(function(){
// 					$.get("{pigcms{:U('Deliver/index_count')}", function(response){
// 						if (response.err_code == false) {
// 							$('#gray_count').html(response.gray_count);
// 							$('#deliver_count').html(response.deliver_count);
// 							$('#finish_count').html(response.finish_count);
// 						}
// 					}, 'json');
// 				}, 2000);
// 	});			
// // 		$(function(){
// // 			var map = new BMap.Map("biz-map");
// // 			map.centerAndZoom(new BMap.Point({pigcms{$deliver_session['lng']}, {pigcms{$deliver_session['lat']}), 16);
//			
// // 			$.getScript("http://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2",function(){
// // 				var geolocation = new BMap.Geolocation();
// // 				geolocation.getCurrentPosition(function(r){
// // 					if(this.getStatus() == BMAP_STATUS_SUCCESS){
// // 						var mk = new BMap.Marker(r.point);
// // 						map.addOverlay(mk);
// // 						map.panTo(r.point);
// // 					} else {
// // 						alert('failed'+this.getStatus());
// // 					}        
// // 				},{enableHighAccuracy: true})
// // 			});
// // 		});

        var marker;
        var ua = navigator.userAgent;
        if(!ua.match(/TuttiDeliver/i)) {
            navigator.geolocation.getCurrentPosition(function (position) {
                map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
                updatePosition(position.coords.latitude,position.coords.longitude);
            });
        }

        var is_route = {pigcms{$is_route};
        var self_position = new google.maps.LatLng({pigcms{$deliver_session['lat']}, {pigcms{$deliver_session['lng']});
        var mapOptions = {
            zoom: 16,
            center: self_position
        }

        var map = new google.maps.Map(document.getElementById('biz-map'), mapOptions);
        function appToPosition(lat,long){
            alert("From App");
            map.setCenter({lat:lat, lng:long});
            //我的图标
            marker = new google.maps.Marker({
                position: {lng: long, lat: lat},
                map: map,
                icon:"{pigcms{$static_path}images/map/my_pos.png"
            });

            return "{pigcms{$deliver_session['uid']}";
        }

        function loadPosition(){
            marker = new google.maps.Marker({
                position: self_position,
                map: map,
                icon:"{pigcms{$static_path}images/map/my_pos.png"
            });
        }


        $(function () {
            if(is_route == 1){//如果已有路线规划 显示路线图
                loadRoute();
            }else{
                loadPosition();
            }

            setInterval(function(){
                $.get("{pigcms{:U('Deliver/index_count')}", function(response){
                    if (response.err_code == false) {
                        $('#gray_count').html(response.gray_count);
                        $('#deliver_count').html(response.deliver_count);
                        $('#finish_count').html(response.finish_count);
                    }
                }, 'json');
            }, 2000);

            if (navigator.geolocation) {
                setInterval(function(){
                        navigator.geolocation.getCurrentPosition(function (position) {
                            lat = position.coords.latitude;
                            lng = position.coords.longitude;
                        });

                    if(typeof(lat) != "undefined"){
                        updatePosition(lat,lng);
                    }
                }, 10000);
            }
        })

        function loadRoute() {
            var directionsService = new google.maps.DirectionsService();
            var directionsDisplay = new google.maps.DirectionsRenderer();
            var haight = self_position;
            var oceanBeach = new google.maps.LatLng({pigcms{$route['destination_lat']?$route['destination_lat']:0}, {pigcms{$route['destination_lng']?$route['destination_lng']:0});

            directionsDisplay.setMap(map);


            //var selectedMode = document.getElementById('biz-map').value;
            var request = {
                origin: haight,
                destination: oceanBeach,
                travelMode: 'DRIVING'
            };
            directionsService.route(request, function (response, status) {
                if (status == 'OK') {
                    directionsDisplay.setDirections(response);
                }
            });
        }
    </script>
</body>
</html>