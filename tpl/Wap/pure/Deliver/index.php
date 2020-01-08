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
    var location_url = "{pigcms{:U('Deliver/grab')}",lat = "{pigcms{$deliver_session['lat']}", lng = "{pigcms{$deliver_session['lng']}";
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
    <include file="Public:facebook"/>
</head>
<style>
    .clerk{
        position: fixed;
        top: 60px;
        left: 0;
        width: 100%;
        z-index: 9999;
    }
    .clerk .clerk_end .clr li{
        width: 50%;
        border-right: 2px solid #F4F4F4;
        border-left: 2px solid #F4F4F4;
        box-sizing: border-box;
    }
    .grab i{
        background-color: #fd6254;
    }
    #container{
        width: 98%;
        margin: 145px auto 0 auto;
    }
    #grab_list{
        color: #333333;
        font-size: 12px;
    }
    .order_title{
        margin-top: 3px;
        padding: 5px 2%;
        border-bottom: 1px dashed silver;
    }
    .order_title .pay_status{
        float: right;
        border: 1px solid cornflowerblue;
        border-radius: 2px;
        padding: 1px 4px;
        color: cornflowerblue;
        font-size: 10px;
    }
    .order_title .pay_status_red{
        float: right;
        border: 1px solid indianred;
        border-radius: 2px;
        padding: 1px 4px;
        color: indianred;
        font-size: 10px;
    }
    .order_time{
        font-size: 9px;
        height: 30px;
        line-height: 30px;
    }
    .order_time label{
        margin: 0 3px;
    }
    .order_time span{
        color: #999999;
    }
    .order_time .time_show{
        color: red;
    }
    .order_address{
        padding: 5px 2%;
    }
    .order_address div{
        width: 100%;
        padding: 5px 0;
    }
    .from_label{
        float: left;
        border-radius: 2px;
        padding: 1px 4px;
        border: 1px solid lightblue;
        color: lightblue;
        font-size: 10px;
    }
    .order_address .address{
        float: right;
        width: 86%;
        font-size: 11px;
    }
    .to_label{
        float: left;
        border-radius: 2px;
        padding: 1px 4px;
        border: 1px solid dimgrey;
        color: dimgrey;
        font-size: 10px;
    }
    .order_address div{
        width: 100%;
        height: 30px;
    }
    .order_address .address_bottom{
        font-size: 9px;
        float: right;
        color: #AAAAAA;
        margin-top: 2px;
    }
    .order_btn{
        padding: 2px 3%;
        height: 40px;
    }
    .location_btn{
        float: left;
        width: 40px;
        border: 1px solid #ffa52d;
        border-radius: 2px;
        color: #ffa52d;
        font-size: 8px;
        padding-top: 2px;
        padding-bottom: 2px;
        padding-left: 24px;
        padding-right: 0px;
        margin-bottom: 10px;
        box-sizing: padding-box;
        background-image: url("{pigcms{$static_path}img/location_icon.png");
        background-size: auto 90%;
        background-repeat: no-repeat;
        background-position: left;
        cursor: pointer;
    }
    .accept_btn{
        float: right;
        width: 75%;
        text-align: center;
        background-color: limegreen;
        border-radius: 3px;
        height: 28px;
        line-height: 28px;
        color: white;
        cursor: pointer;
    }
    #gray_count{
        color: #fd6254;
    }
    #deliver_count{
        color: #32620e;
    }
</style>
<body>
    <include file="header" />
	<section class="clerk">
        <div style="background-color: #F4F4F4;height: 10px;width: 100%"></div>
		<div class="clerk_end">
			<ul class="clr">
				<li class="grab fl">
					<a href="{pigcms{:U('Deliver/grab')}">
						<i></i>
						<h2 id="gray_count">0</h2>
						<p>{pigcms{:L('_ND_PENDINGORDERS_')}</p>
					</a>
				</li>
				<li class="deliver fl">
					<a href="{pigcms{:U('Deliver/process')}">
						<i></i>
						<h2 id="deliver_count">{pigcms{$deliver_count}</h2>
						<p>{pigcms{:L('_ND_MYORDERS_')}</p>
					</a>
				</li>
			</ul>
		</div>
        <div style="background-color: #F4F4F4;height: 15px;width: 100%"></div>
	</section>
    <div id="container">
        <div class="scroller" id="scroller">
            <div id="grab_list">
                <div style="width: 80%;margin: 10px auto;font-size: 12px;color: #666666;text-align: center;">
                    Please go to "My Account" to complete all required information and documents in order to accept orders.
                    <a href="{pigcms{:U('Deliver/account')}">
                    <div style="background-color: #ffa52d;color: white;line-height: 30px;width: 80%;margin: 10px auto;border-radius: 5px;">
                        {pigcms{:L('_ND_MYACCOUNT_”')}
                    </div>
                    </a>
                </div>


            </div>
        </div>
    </div>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <if condition="$deliver_session['reg_status'] eq 0 and $deliver_session['group'] eq 1">
        <script type="text/javascript" src="{pigcms{$static_path}js/grab.js?211" charset="utf-8"></script>
        <script>
            $('#grab_list').html('');
            $('#gray_count').html('{pigcms{$gray_count}');
            setInterval(function(){
                $.get("{pigcms{:U('Deliver/index_count')}", function(response){
                    if (response.err_code == false) {
                        $('#gray_count').html(response.gray_count);
                        $('#deliver_count').html(response.deliver_count);
                        $('#finish_count').html(response.finish_count);
                    }
                }, 'json');
            }, 2000);
        </script>
    </if>
    <script id="replyListBoxTpl" type="text/html">
        {{# for(var i = 0, len = d.list.length; i < len; i++){ }}
        <section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
            <div class="order_title">
                <span>{{ d.list[i].store_name }}</span>
                {{# if(d.list[i].uid == 0){ }}
                    <span class="pay_status_red">
                        {pigcms{:L('_ND_UNPAID_')}
                    </span>
                {{# } else { }}
                    {{# if(d.list[i].pay_method == 1){ }}
                    <span class="pay_status">
                        {pigcms{:L('_ND_PAID_')}
                    </span>
                    {{# } else { }}
                    <span class="pay_status_red">
                        {pigcms{:L('_ND_CASH_')}
                    </span>
                    {{# } }}
                {{# } }}
                <div class="order_time">
                    <span>Order placed</span>
                    <span class="time_show">{{ d.list[i].show_create_time }}</span>
                    <label> | </label>
                    <span>
                        {{# if(d.list[i].is_dinning == 1){ }}
                            Order is ready
                        {{# } else { }}
                            Order will be ready in
                        {{# } }}
                    </span>
                    <span class="time_show">{{ d.list[i].show_dining_time }}</span>
                </div>
            </div>
            <div class="order_address">
                <div>
                    <span class="from_label">
                        {pigcms{:L('_ND_FROM_')}
                    </span>
                    <span class="address">
                        {{ d.list[i].from_site }}
                        <span class="address_bottom">
                            You are {{ d.list[i].store_distance }} away from this restaurant.
                        </span>
                    </span>
                </div>
                <div>
                    <span class="to_label">
                        {pigcms{:L('_ND_TO_')}
                    </span>
                    <span class="address">
                        {{ d.list[i].user_address.adress }}
                        <span class="address_bottom">
                            {pigcms{:L('_DELI_PRICE_')}:${{ d.list[i].freight_charge }}
                        </span>
                    </span>
                </div>
            </div>
            <div class="order_btn">
                <a href="{{ d.list[i].map_url }}">
                <span class="location_btn">
                    {pigcms{:L('_ND_CHECKLOCATIONS_')}
                </span>
                </a>
                <a href="javascript:void(0);" class="rob" data-spid="{{ d.list[i].supply_id }}">
                <span class="accept_btn">
                    {pigcms{:L('_ND_ACCEPT_')}
                </span>
                </a>
            </div>
        </section>
        {{# } }}
    </script>
	<script type="text/javascript">
        //定位是否有问题
        var location_error = false;

        var marker;
        var ua = navigator.userAgent;
        //if(!ua.match(/TuttiDeliver/i)) {
            if(navigator.geolocation) {
                //alert('geolocation:start');
                navigator.geolocation.getCurrentPosition(function (position) {
                    //alert("geolocation_lat:" + position.coords.latitude);
                    map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
                    updatePosition(position.coords.latitude, position.coords.longitude);
                    run_update_location();
                }, function (error) {
                    console.log("geolocation:" + error.code);
                    //location_error = true;
                    //run_Amap();
                    //run_update_location();
                },{enableHighAccuracy:true,timeout:50000});
            }else{
                //alert('geolocation:error');
            }
        //}


        function onComplete(result) {
            console.log(result.position + ' | ' + result.location_type);
            var lat = result.position.getLat();
            var lng = result.position.getLng();
            map.setCenter({lat: lat, lng: lng});
            updatePosition(lat,lng);
        }
        
        function onError(error) {
            console.log(error.info + '||' + error.message);
        }

        var is_route = {pigcms{$is_route};
        var self_position = new google.maps.LatLng({pigcms{$deliver_session['lat']}, {pigcms{$deliver_session['lng']});
        var mapOptions = {
            zoom: 16,
            center: self_position
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
        });

        function run_update_location() {
            if(location_error){
                setInterval(function(){
                    run_Amap();
                }, 10000);
            }else{
                setInterval(function(){
                    navigator.geolocation.getCurrentPosition(function (position) {
                        lat = position.coords.latitude;
                        lng = position.coords.longitude;
                    });
                    console.log('run getCurrentPosition');
                    if(typeof(lat) != "undefined"){
                        updatePosition(lat,lng);
                    }
                }, 10000);
            }
        }

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