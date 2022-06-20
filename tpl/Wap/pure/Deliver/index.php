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
    <script>
        var location_url = "{pigcms{:U('Deliver/grab')}",lat = "{pigcms{$deliver_session['lat']}", lng = "{pigcms{$deliver_session['lng']}", reject_url = "{pigcms{:U('Deliver/reject')}",update_url = "{pigcms{:U('Deliver/index_count')}";
        var static_path = "{pigcms{$static_path}";
        var deliver_sound_url = "{pigcms{$static_public}sound/driver_new_order.mp3";
        $(function(){
            $(".startOrder,.stopOrder").click(function(){
                $.get("/wap.php?g=Wap&c=Deliver&a=index&action=changeWorkstatus&type="+$(this).attr('ref'), function(data){
                    if(data.error == 1){
                        //alert(data.msg);
                        layer.open({
                            title: "",
                            content: '' + data.msg + '',
                            btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],
                        });
                    }else {
                        if(typeof (window.linkJs) != 'undefined'){
                            window.linkJs.reloadWebView();
                        }else {
                            window.location.reload();
                        }
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
                    self_position = new google.maps.LatLng(lat, lng);
                    marker_deliver.setPosition(self_position);
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
            bottom: 65px;
            top:46%;
            background-color: #FFF5E8;
            border-radius: 15px;
        }
        #deliver_middle_div{
            background-color: #f8f8f8;
            display: none;
            overflow: auto;
            top:45%;
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
        #distance_div{
            margin-top: -55px;
            float: left;
            background: #5C96E9;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
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
        .order_memo{
            height: 100%;
            overflow: auto;
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
            flex: 1 1 35%;
            word-break: normal;
        }
        .order_detail .amount{
            flex: 1 1 70%;
            font-size: 32px;
            font-weight: bold;
        }
        .order_detail .payment label{
            padding: 6px 10px;
            border-radius: 10px;
            border: 2px solid #294068;
            font-size: 15px;
        }
        .store_name{
            padding: 10px 5%;
            font-size: 18px;
            font-weight: bold;
            color: #294068;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }
        .order_time{
            color: #555555;
            padding: 5px 2% 15px 2%;
            border-bottom: 1px solid #CCCCCC;
            font-size: 15px;
        }
        #position_div{
            padding: 0px 10%;
            font-size: 16px;
            line-height: 35px;
            color: #333333;
        }
        #position_div div{
            white-space: nowrap;
            text-overflow: ellipsis;
            height: 35px;
            overflow: hidden;
        }
        .title_icon{
            color: #ffa52d;
            vertical-align: middle;
            font-size: 26px !important;
        }
        .color_0 .title_icon{
            color: #5D9CBA;
        }
        .color_1 .title_icon{
            color: #864648;
        }
        .color_2 .title_icon{
            color: #90839F;
        }
        .color_3 .title_icon{
            color: #506343;
        }
        .color_4 .title_icon{
            color: #ffa52d;
        }
        .color_5 .title_icon{
            color: #344267;
        }
        .color_6 .title_icon{
            color: #776553;
        }
        .color_7 .title_icon{
            color: #57589A;
        }
        .send_btn{
            width: 92%;
            margin-top: 10px;
            margin-left: 4%;
            background-color: #ffa52d;
            color: white;
            font-size: 18px;
            line-height: 45px;
            border-radius: 10px;
            text-align: center;
        }
        .deliver_order{
            background-color: #EEEEEE;
            border-radius: 15px;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .deliver_top{
            font-size: 18px;
            font-weight: bold;
            color: white;
            background-color: #294068;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            padding: 10px 10px 10px 20px;
        }
        .wait_div{
            text-align: center;
            font-size: 17px;
            color: #294068;
        }
        .diff_time{
            border-radius: 100%;
            border: 2px solid #294068;
            width: 35px;
            height: 35px;
            flex: none !important;
            line-height: 35px;
            margin-top: 5px;
        }
    </style>
</head>

<body>
<include file="Public:google"/>
<include file="index_header" />
<div id="all_map"></div>
<div id="gray_middle_div">

</div>
<div id="deliver_middle_div">

</div>
<div id="bottom_nav">
    <span id="gray_count" class="active" data-show="gray_middle_div" data-hide="deliver_middle_div"></span>
    <span id="deliver_count" data-show="deliver_middle_div" data-hide="gray_middle_div"></span>
</div>

<script src="{pigcms{$static_public}js/laytpl.js"></script>
<if condition="$deliver_session['reg_status'] eq 0 and $deliver_session['group'] eq 1">
    <script type="text/javascript" src="{pigcms{$static_path}js/grab.js?202" charset="utf-8"></script>
    <script>
        window.onpageshow = function(event) {
            if (event.persisted) {
                if(typeof (window.linkJs) != 'undefined'){
                    window.linkJs.reloadWebView();
                }else {
                    window.location.reload();
                }
            }
        }

        var grab_timer,order_timer;
        $('#grab_list').html('');

        $('#gray_count').html("0 Pending");
        $('#deliver_count').html("0 in Progress");
        //var test_sound = 0;
        function getOrderNum() {
            $.get("{pigcms{:U('Deliver/index_count')}", function (response) {
                if (response.err_code == false) {
                    $('#gray_count').html(response.gray_count + " Pending");
                    $('#deliver_count').html(response.deliver_count + " in Progress");
                    $('#finish_count').html(response.finish_count);
                    if (response.work_status == 1) {
                        if(typeof (window.linkJs) != 'undefined'){
                            window.linkJs.reloadWebView();
                        }else {
                            window.location.reload();
                        }
                    }
                    /**
                    test_sound += 2;
                    console.log("test_sound",test_sound);
                    if(test_sound > 20){
                        response.just_new = 1;
                        test_sound = 0;
                    }
                     */

                    if(response.just_new == 1){
                        if(navigator.userAgent.match(/TuttiDeliver/i))
                            window.webkit.messageHandlers.newOrderSound.postMessage([0]);
                        else if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
                            if (typeof (window.linkJs.newOrderSound) != 'undefined') {
                                window.linkJs.newOrderSound();
                            }
                        }else {
                            var audio = new Audio();
                            audio.src = deliver_sound_url;
                            audio.play();
                        }
                    }

                    if(response.deliver_count == 0) loadPosition();
                }
            }, 'json');
        }

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

                if(show_id == 'deliver_middle_div') {
                    loadProcess();
                    order_timer = setInterval(getOrderNum, 2000);
                    clearInterval(grab_timer);
                    record_id = 0;
                    directionsDisplay.setMap(null);
                    marker_store.setMap(null);
                    marker_user.setMap(null);
                    location.hash = 1;
                }
                else {
                    getList();
                    grab_timer = setInterval(getList, 2000);
                    clearInterval(order_timer);
                    clearMarker();
                    location.hash = 0;
                }
            });
        });

        function clearMarker() {
            for(var i=0;i<marker_store_list.length;i++){
                marker_store_list[i].setMap(null);
            }

            for(var i=0;i<marker_user_list.length;i++){
                marker_user_list[i].setMap(null);
            }
        }

        function gotoPending() {
            $('#bottom_nav').children('span').first().trigger('click');
        }

        function loadProcess(){
            $.post("{pigcms{:U('Deliver/get_process')}",{"status":0,'lat':lat, 'lng':lng},function(result){
                if(!result.error_code) {
                    laytpl($('#processListBoxTpl').html()).render(result, function (html) {
                        $('#deliver_middle_div').html(html);
                    });
                    if(result.list.length > 0) {
                        setProcessOrder(result.list);
                    }else{
                        loadPosition();
                    }

                    $('.deliver_order').bind('click',function () {
                        var order_id = $(this).data('id');
                        location.href = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'"+order_id+"','from'=>'index'))}";
                    });
                }else{
                    var insertHtml = '<div class="wait_div" style="margin-top: 50px;font-weight: bold;">No order accepted yet</div>';
                    insertHtml += '<div class="wait_div" onclick="gotoPending();" style="margin: 20px 10%;background-color: #294068;color:white;border-radius: 8px;line-height: 45px;">Go To Pending Orders</div>';

                    $('#deliver_middle_div').html(insertHtml);

                    loadPosition();
                }
            },'json');
        }
    </script>
</if>
<script id="replyListBoxTpl" type="text/html">
    {{# for(var i = 0, len = d.list.length; i < len; i++){ }}
    <div id="distance_div">{{ d.list[i].user_distance }}</div>
    <a href="javascript:void(0);" class="rej" data-spid="{{ d.list[i].supply_id }}">
        <div id="reject_div">Reject</div>
    </a>
    {{# if(d.list[i].just == 1){ }}
    <div id="top_label">Just for you</div>
    {{# } else { }}
    <div id="top_label" style="background-color: #666666">Also open to others</div>
    {{# } }}
    <div class="order_memo">
        <div id="order_div">
            <div class="order_detail">
                <span class="payment">
                    <label>
                        {{# if(d.list[i].uid == 0){ }}
                            {pigcms{:L('_ND_UNPAID_')}
                            {{# } else { }}
                            {{# if(d.list[i].pay_method == 1){ }}
                                Paid
                            {{# } else { }}
                                {pigcms{:L('_ND_CASH_')}
                            {{# } }}
                        {{# } }}
                    </label>
                </span>
                <span class="amount">${{ d.list[i].deliver_income }}</span>
                {{# if(d.list[i].just == 1){ }}
                <span class="diff_time">{{ d.list[i].diff_time }}</span>
                {{# } else { }}
                <span></span>
                {{# } }}
            </div>
            <div class="store_name">
                {{ d.list[i].store_name }}
            </div>
            <div class="order_time">
                {{ d.list[i].store_distance }} from you
                ·
                {{# if(d.list[i].is_dinning == 1){ }}
                Ready {{ d.list[i].show_dining_time }}
                {{# } else { }}
                Ready in {{ d.list[i].show_dining_time }}
                {{# } }}
            </div>
        </div>
        <div id="position_div">
            <div>
                <span class="material-icons title_icon">restaurant</span>
                {{ d.list[i].from_site }}
            </div>
            <div>
                <span class="material-icons title_icon">pin_drop</span>
                {{ d.list[i].aim_site }}
            </div>
        </div>
        <a href="javascript:void(0);" class="rob" data-spid="{{ d.list[i].supply_id }}" style="display: block;">
            <div class="send_btn">
                {{# if(d.list[i].bonus > 0){ }}
                Accept to Earn ${{ d.list[i].bonus }} Bonus
                {{# } else { }}
                Accept
                {{# } }}
            </div>
        </a>
    </div>
    {{# } }}
</script>
<script id="processListBoxTpl" type="text/html">
    {{# for(var i = 0, len = d.list.length; i < len; i++){ }}
    <div class="deliver_order" data-id="{{ d.list[i].supply_id }}">
        <div class="deliver_top">
            #{{ d.list[i].order_id }} -
            {{# if(d.list[i].status == 2){ }}
            {pigcms{:L('_ND_WAITING_')}
            {{# } else if(d.list[i].status == 3) { }}
            {pigcms{:L('_ND_INHAND_')}
            {{# } else { }}
            {pigcms{:L('_ND_ARRIVING_')}
            {{# } }}
            <span class="material-icons" style="float: right;">arrow_forward</span>
        </div>
        <div class="store_name" style="text-align: center">
            {{ d.list[i].store_name }}
        </div>
        <div class="order_time" style="text-align: center;border-bottom: 0px;">
            {{# if(d.list[i].status < 4){ }}
            {{ d.list[i].store_distance }} from you
            {{# } else { }}
            {{ d.list[i].user_distance }} from you
            {{# } }}
            {{# if(d.list[i].status < 3){ }}
            ·
            {{# if(d.list[i].is_dinning == 1){ }}
            Ready
            {{# } else { }}
            Ready in
            {{# } }}
            {{ d.list[i].show_dining_time }}
            {{# } }}
        </div>
        <div id="position_div" class="color_{{ i%8 }}">
            <div>
                <span class="material-icons title_icon">restaurant</span>
                {{ d.list[i].from_site }}
            </div>
            <div>
                <span class="material-icons title_icon">pin_drop</span>
                {{ d.list[i].aim_site }}
            </div>
        </div>
    </div>
    {{# } }}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&callback=initMap&language=en" async defer></script>
<script type="text/javascript">
    var self_position,is_route,map,directionsService,directionsDisplay,record_id,marker_deliver,marker_store,marker_user,marker_store_list,marker_user_list;

    function initMap() {
        is_route = {pigcms{$is_route};
        self_position = new google.maps.LatLng({pigcms{$deliver_session['lat']}, {pigcms{$deliver_session['lng']});
        var mapOptions = {
            zoom: 16,
            center: self_position,
            mapTypeControl:false,
            zoomControl:true,
            fullscreenControl:false,
            zoomControlOptions:{
                style:google.maps.ZoomControlStyle.SMALL,
                position:google.maps.ControlPosition.LEFT_BOTTOM
            }
        };

        map = new google.maps.Map(document.getElementById('all_map'),mapOptions);

        marker_deliver = new google.maps.Marker({
            position: self_position,
            map: map,
            //icon:"{pigcms{$static_path}img/deliver_menu/customer_pin_3.png"
        });

        /**
         if(is_route == 1){//如果已有路线规划 显示路线图
                loadRoute();
            }else{
                loadPosition();
            }
         */

        directionsService = new google.maps.DirectionsService();
        directionsDisplay = new google.maps.DirectionsRenderer();
        marker_store = new google.maps.Marker();
        marker_user = new google.maps.Marker();
        marker_store_list = [];
        marker_user_list = [];

        var curr_hash = location.hash.replace("#","");
        if(curr_hash == "1"){
            $('#deliver_count').trigger('click');
        }else{
            grab_timer = setInterval(getList, 2000);
            //getList();
        }
    }

        //定位是否有问题
        var location_error = false;

        var ua = navigator.userAgent;
        if(!ua.match(/TuttiDeliver/i)) {
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
        }


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
            marker_deliver.setPosition(self_position);
            record_id = 0;
            directionsDisplay.setMap(null);
            marker_store.setMap(null);
            marker_user.setMap(null);
            clearMarker();
            map.setCenter(self_position);
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

        function setProcessOrder(orderList) {
            bounds = new google.maps.LatLngBounds();
            clearMarker();
            marker_store_list = [];
            marker_user_list = [];
            for (var i = 0; i < orderList.length; i++) {
                var order = orderList[i];

                var user = {
                    url:"{pigcms{$static_path}img/deliver_menu/customer_pin_"+(i%8+1)+".png",
                    scaledSize: new google.maps.Size(35,35),
                    size: new google.maps.Size(35,35)
                };

                var store =  {
                    url:"{pigcms{$static_path}img/deliver_menu/restaurant_pin_"+(i%8+1)+".png",
                    scaledSize: new google.maps.Size(35,35),
                    size: new google.maps.Size(35,35)
                };

                var store_pos = {lat: parseFloat(order.from_lat), lng: parseFloat(order.from_lnt)};
                var user_pos = {lat: parseFloat(order.aim_lat), lng: parseFloat(order.aim_lnt)};

                // The marker, positioned at Uluru
                marker_store = new google.maps.Marker({position: store_pos, map: map,icon:store});
                marker_user = new google.maps.Marker({position: user_pos, map: map,icon:user});

                marker_store_list[i] = marker_store;
                marker_user_list[i] = marker_user;

                bounds.extend(new   google.maps.LatLng(marker_store.getPosition().lat()
                    ,marker_store.getPosition().lng()));
                bounds.extend(new   google.maps.LatLng(marker_user.getPosition().lat()
                    ,marker_user.getPosition().lng()));
            }

            map.fitBounds(bounds);
        }

        function loadRoute(orderDetail) {
            if(record_id != orderDetail.supply_id) {
                record_id = orderDetail.supply_id;
                directionsDisplay.setMap(null);
                marker_store.setMap(null);
                marker_user.setMap(null);
                //var haight = self_position;
                //var oceanBeach = new google.maps.LatLng({pigcms{$route['destination_lat']?$route['destination_lat']:0}, {pigcms{$route['destination_lng']?$route['destination_lng']:0});
                var user_pos = {lat: parseFloat(orderDetail.aim_lat), lng: parseFloat(orderDetail.aim_lnt)};
                var store_pos = {lat: parseFloat(orderDetail.from_lat), lng: parseFloat(orderDetail.from_lnt)};

                user_pos = new google.maps.LatLng(user_pos);
                store_pos = new google.maps.LatLng(store_pos);

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
                    strokeOpacity: 0,
                    strokeColor: "#294068",
                    icons: [{
                        icon: lineSymbol,
                        offset: "0",
                        repeat: "15px",
                    }]
                });

                marker_user = new google.maps.Marker({
                    position: user_pos,
                    map: map,
                    scaledSize: new google.maps.Size(10, 10),
                    icon: {
                        url: "{pigcms{$static_path}img/deliver_menu/customer_pin_6.png",
                        scaledSize: new google.maps.Size(35, 35)
                    }
                });

                marker_store = new google.maps.Marker({
                    position: store_pos,
                    map: map,
                    icon: {
                        url: "{pigcms{$static_path}img/deliver_menu/restaurant_pin_6.png",
                        scaledSize: new google.maps.Size(35, 35)
                    },
                });

                directionsDisplay.setOptions({polylineOptions: line, suppressMarkers: [marker_store, marker_user]});

                //var selectedMode = document.getElementById('biz-map').value;
                var request = {
                    origin: store_pos,
                    destination: user_pos,
                    travelMode: 'DRIVING',
                };

                directionsService.route(request, function (response, status) {
                    if (status == 'OK') {
                        directionsDisplay.setDirections(response);
                        // var mapOptions = {
                        //     zoom: map.getZoom()+20,
                        // };
                        // map.update(mapOptions);
                    }
                });
            }
        }
</script>
</body>
</html>