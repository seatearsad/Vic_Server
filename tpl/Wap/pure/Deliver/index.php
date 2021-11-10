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
<link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&callback=initMap&language=en" async defer></script>
<script>
    var location_url = "{pigcms{:U('Deliver/grab')}",lat = "{pigcms{$deliver_session['lat']}", lng = "{pigcms{$deliver_session['lng']}", reject_url = "{pigcms{:U('Deliver/reject')}",update_url = "{pigcms{:U('Deliver/index_count')}";
	$(function(){
		$(".startOrder,.stopOrder").click(function(){
			$.get("/wap.php?g=Wap&c=Deliver&a=index&action=changeWorkstatus&type="+$(this).attr('ref'), function(data){
			    if(data.error == 1){
                    alert(data.msg);
			    }else {
                    window.location.reload();
                }
			},'json');
		});
	})
    //ios app 更新位置
    function updatePosition(lat,lng){
        var message = '';
	    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
            if(result){
                message = result.msg;
            }else {
                message = 'Error';
            }
            return message;
        },'json');
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

    function appToPosition(lat,long){
        updatePosition(lat,long);

        return "{pigcms{$deliver_session['uid']}";
    }
</script>
    <include file="Public:facebook"/>
    <style>
        body{
            position: unset;
        }
        #all_map{
            position: absolute;
            width: 100%;
            height: 55%;
            background-color: #EEEEEE;
        }
        #bottom_nav{
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 50px;
            background-color: #EEEEEE;
            border-top: 1px solid #999999;
            display: flex;
            padding-top: 5px;
        }
        #bottom_nav span{
            flex: 1 1 50%;
            height: 40px;
            line-height: 40px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            color: #294068;
            box-sizing: border-box;
        }
        #bottom_nav span.active{
            color: #ffa52d;
        }
        #deliver_count{
            border-left: 1px solid #999999;;
        }
        #gray_middle_div,#deliver_middle_div{
            position: absolute;
            width: 94%;
            margin-left: 3%;
            bottom: 80px;
            top:50%;
            background-color: #FFF5E8;
            border-radius: 15px;
        }
        #deliver_middle_div{
            background-color: #294068;
            display: none;
        }
        #reject_div{
            height: 30px;
            line-height: 30px;
            border-radius: 18px;
            border: 2px solid #294068;
            background-color: white;
            color: #294068;
            font-size: 18px;
            font-weight: bold;
            padding: 5px 22px;
            float: right;
            margin-top: -50px;
        }
        #top_label{
            width: auto;
            background-color: #ffa52d;
            color: white;
            padding: 5px 20px;
            font-size: 16px;
            border-radius: 10px;
            border-bottom-left-radius: 0px;
            position: absolute;
            top: -10px;
        }
        #order_div{
            padding: 20px 5% 10px 5%;
            text-align: center;
            font-size: 16px;
        }
        .order_detail{
            display: flex;
            padding: 0px 5%;
            color: #294068;
            line-height: 50px;
        }
        .order_detail span{
            flex: 1 1 25%;
        }
        .order_detail .amount{
            flex: 1 1 50%;
            font-size: 32px;
            font-weight: bold;
        }
        .order_detail .payment label{
            padding: 5px 10px;
            border-radius: 10px;
            border: 2px solid #294068;
            font-size: 16px;
        }
        .store_name{
            padding: 10px 5%;
            font-size: 18px;
            font-weight: bold;
            color: #294068;
        }
        .order_time{
            color: #555555;
            padding: 5px 5% 15px 5%;
            border-bottom: 1px solid #CCCCCC;
        }
        #position_div{
            padding: 0px 10%;
            font-size: 18px;
            line-height: 35px;
            color: #555555;
        }
        .title_icon{
            color: #ffa52d;
            vertical-align: text-top;
            font-size: 26px !important;
        }
        .send_btn{
            width: 92%;
            margin-top: 20px;
            margin-left: 4%;
            background-color: #ffa52d;
            color: white;
            font-size: 18px;
            line-height: 45px;
            border-radius: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <include file="header" />
	<div id="all_map"></div>
    <div id="gray_middle_div">
        <div id="reject_div">Reject</div>
        <div id="top_label">Just for you</div>
        <div id="order_div">
            <div class="order_detail">
                <span class="payment">
                    <label>Cash</label>
                </span>
                <span class="amount">$117.89</span>
                <span></span>
            </div>
            <div class="store_name">
                Rook and Rose Flower Shop
            </div>
            <div class="order_time">
                0.5 km from you · Ready in 9 min
            </div>
        </div>
        <div id="position_div">
            <div>
                <span class="material-icons title_icon">restaurant</span>
                852 Fort St, Victoria, BC
            </div>
            <div>
                <span class="material-icons title_icon">pin_drop</span>
                852 Fort St, Victoria, BC
            </div>
        </div>
        <div class="send_btn">
            Accept
        </div>
    </div>
    <div id="deliver_middle_div">

    </div>
    <div id="bottom_nav">
        <span id="gray_count" class="active" data-show="gray_middle_div" data-hide="deliver_middle_div"></span>
        <span id="deliver_count" data-show="deliver_middle_div" data-hide="gray_middle_div"></span>
    </div>

    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <if condition="$deliver_session['reg_status'] eq 0 and $deliver_session['group'] eq 1">
        <script type="text/javascript" src="{pigcms{$static_path}js/grab.js?211" charset="utf-8"></script>
        <script>
            $('#grab_list').html('');

            $('#gray_count').html("0 Pending");
            $('#deliver_count').html("0 in Progress");

            // setInterval(function(){
            //     $.get("{pigcms{:U('Deliver/index_count')}", function(response){
            //         if (response.err_code == false) {
            //             $('#gray_count').html(response.gray_count + " Pending");
            //             $('#deliver_count').html(response.deliver_count + " in Progress");
            //             $('#finish_count').html(response.finish_count);
            //             if(response.work_status == 1){
            //                 window.location.reload();
            //             }
            //         }
            //     }, 'json');
            // }, 2000);

            $('#bottom_nav').find('span').each(function () {
                $(this).click(function () {
                    var show_id = $(this).data('show');
                    $('#'+show_id).show();
                    var hide_id = $(this).data('hide');
                    $('#'+hide_id).hide();
                    $(this).addClass('active').siblings().removeClass('active');
                });
            });
        </script>
    </if>
    <script id="replyListBoxTpl" type="text/html">
        {{# for(var i = 0, len = d.list.length; i < len; i++){ }}
        <section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
            {{# if(d.list[i].just == 1){ }}
            <div class="just_div just">
                Just for you!
                <span style="float: right">{{ d.list[i].diff_time }}</span>
            </div>
            {{# } else { }}
            <div class="just_div">Also open to others</div>
            {{# } }}
            <div class="order_title">
                <span class="store_name">{{ d.list[i].store_name }}</span>
                {{# if(d.list[i].uid == 0){ }}
                    <span class="pay_status_red_d">
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
                <a href="javascript:void(0);" class="rej" data-spid="{{ d.list[i].supply_id }}">
                <span class="reject_btn">
                    {pigcms{:L('_D_REJECT_ORDER_')}
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
        //     if(navigator.geolocation) {
        //         //alert('geolocation:start');
        //         navigator.geolocation.getCurrentPosition(function (position) {
        //             //alert("geolocation_lat:" + position.coords.latitude);
        //             map.setCenter({lat: position.coords.latitude, lng: position.coords.longitude});
        //             updatePosition(position.coords.latitude, position.coords.longitude);
        //             run_update_location();
        //         }, function (error) {
        //             console.log("geolocation:" + error.code);
        //             //location_error = true;
        //             //run_Amap();
        //             //run_update_location();
        //         },{enableHighAccuracy:true,timeout:50000});
        //     }else{
        //         //alert('geolocation:error');
        //     }
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

        function loadPosition(){
            marker = new google.maps.Marker({
                position: self_position,
                map: map,
                icon:"{pigcms{$static_path}images/map/my_pos.png"
            });
        }

        var self_position,is_route,map;

        function initMap() {
            is_route = {pigcms{$is_route};
            self_position = new google.maps.LatLng({pigcms{$deliver_session['lat']}, {pigcms{$deliver_session['lng']});
            var mapOptions = {
                zoom: 16,
                center: self_position
            }

            map = new google.maps.Map(document.getElementById('all_map'),mapOptions);

            if(is_route == 1){//如果已有路线规划 显示路线图
                loadRoute();
            }else{
                loadPosition();
            }
        }

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

            // var polyline = new google.maps.Polyline({
            //     strokeColor: '#C00',
            //     strokeOpacity: 0.7,
            //     strokeWeight: 5
            // });

            var lineSymbol = {
                path: "M 0,-1 0,1",
                strokeOpacity: 1,
                scale: 3,
            };

            var line = new google.maps.Polyline({
                strokeOpacity:0,
                strokeColor:"#FFAC1C",
                icons:[{
                    icon: lineSymbol,
                    offset:"0",
                    repeat:"25px",
                }]
            });

            directionsDisplay.setOptions({polylineOptions: line});

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