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
    var location_url = "{pigcms{:U('Deliver/grab')}",lat = "{pigcms{$deliver_session['lat']}", lng = "{pigcms{$deliver_session['lng']}"
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
    .deliver i{
        background-color: #32620e;
    }
    #container{
        width: 98%;
        margin: 190px auto 20px auto;
    }
    #grab_list{
        color: #333333;
        font-size: 12px;
    }
    .order_title{
        margin-top: 3px;
        padding: 5px 2%;
        border-bottom: 1px dashed silver;
        background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
        background-repeat: no-repeat;
        background-position: center right 5px;
        background-size: auto 20%;
        cursor: pointer;
    }
    .order_title .pay_status{
        float: right;
        border: 1px solid cornflowerblue;
        border-radius: 2px;
        padding: 1px 4px;
        color: cornflowerblue;
        font-size: 10px;
        margin-top: -35px;
    }
    .order_title .pay_status_red{
        float: right;
        border: 1px solid indianred;
        border-radius: 2px;
        padding: 1px 4px;
        color: indianred;
        font-size: 10px;
        margin-top: -35px;
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
    .order_cash{
        padding: 5px 2%;
        font-size: 10px;
        color: #999999;
    }
    .order_cash label{
        color: orangered;
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
    .note_label{
        float: left;
        width: 25px;
        height: 25px;
        margin-top: -5px;
        background-image: url("{pigcms{$static_path}img/note_icon.png");
        background-size: auto 100%;
        background-repeat: no-repeat;
        background-position: left;
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
        background-image: url("{pigcms{$static_path}img/google_map_icon.png");
        background-size: auto 90%;
        background-repeat: no-repeat;
        background-position: left;
        cursor: pointer;
    }
    .phone_btn{
        float: left;
        width: 50px;
        border: 1px solid #59a422;
        border-radius: 2px;
        color: #59a422;
        font-size: 8px;
        padding-top: 2px;
        padding-bottom: 2px;
        padding-left: 24px;
        padding-right: 0px;
        margin-bottom: 10px;
        box-sizing: padding-box;
        background-image: url("{pigcms{$static_path}img/phone_icon.png");
        background-size: auto 80%;
        background-repeat: no-repeat;
        background-position: left 2px center;
        cursor: pointer;
        height: 21px;
        margin-left: 10px;
    }
    .accept_btn_2,.accept_btn_3,.accept_btn_4{
        float: right;
        width: 45%;
        text-align: center;
        border-radius: 3px;
        height: 28px;
        line-height: 28px;
        color: white;
        cursor: pointer;
    }
    .accept_btn_2{
        background-color: #7ed957;
        font-size: 11px;
    }
    .accept_btn_3{
        background-color: #f48383;
    }
    .accept_btn_4{
        background-color: #38b6ff;
    }

    #gray_count{
        color: #fd6254;
    }
    #deliver_count{
        color: #32620e;
    }
    #top_menu{
        width: 98%;
        margin: 0px auto;
        text-align: center;
    }
    #top_menu li{
        list-style-type: none;
        display: inline-block;
        padding-left: 8px;
        padding-right: 8px;
        border-radius: 2px;
        font-size: 9px;
        margin-right: 3%;
        color: #333333;
        height: 33px;
        cursor: pointer;
    }
    #top_menu label{
        float: left;
        cursor: pointer;
        width: 100%;
    }
    #top_menu span{
        position: relative;
        float: left;
        margin-top: 2px;
        font-size: 10px;
        width: 100%;
    }
    #top_menu .all{
        font-size: 11px;
        color: #ffa52d;
        border: 1px solid #ffa52d;
    }
    #top_menu .all label{
        margin-top: 8px;
        text-align: center;
    }
    #top_menu .all.curr{
        background-color: #ffa52d;
        color: white;
    }
    #top_menu .accept{
        border: 1px solid limegreen;
    }
    #top_menu .accept.curr{
        background-color: limegreen;
        color: white;
    }
    #top_menu .accept label{
        margin-top: 2px;
    }
    #top_menu .accept span{
        color: limegreen;
    }
    #top_menu .accept.curr span{
        color: white;
    }
    #top_menu .pickup{
        border: 1px solid orangered;
    }
    #top_menu .pickup.curr{
        background-color: orangered;
        color: white;
    }
    #top_menu .pickup.curr span{
        color: white;
    }
    #top_menu .pickup label{
        margin-top: 2px;
    }
    #top_menu .pickup span{
        color: orangered;
    }
    #top_menu .route{
        border: 1px solid cornflowerblue;
    }
    #top_menu .route.curr{
        background-color: cornflowerblue;
        color: white;
    }
    #top_menu .route.curr span{
        color: white;
    }
    #top_menu .route label{
        margin-top: 2px;
    }
    #top_menu .route span{
        color: cornflowerblue;
    }

    .status_2,.status_3,.status_4{
        font-size: 10px;
        padding: 3px 5px;
        color: white;
        border-radius: 2px;
    }
    .status_2{
        background-color: limegreen;
    }
    .status_3{
        background-color: orangered;
    }
    .status_4{
        background-color: cornflowerblue;
    }
</style>
<body>
    <include file="header" />
	<section class="clerk">
        <div style="background-color: #F4F4F4;height: 10px;width: 100%"></div>
		<div class="clerk_end">
			<ul class="clr">
				<li class="grab fl">
					<a href="{pigcms{:U('Deliver/index')}">
						<i></i>
						<h2 id="gray_count">{pigcms{$gray_count}</h2>
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
        <div style="background-color: #F4F4F4;width: 100%;height: 15px;"></div>
        <div style="background-color: #F4F4F4;width: 100%;height: 45px;">
            <ul id="top_menu">
                <li class="all curr" data-type="0">
                    <label>{pigcms{:L('_ND_ALL_')}</label>
                </li>
                <li class="accept" data-type="2">
                    <span>0</span>
                    <label>{pigcms{:L('_ND_JUSTACCEPTED_')}</label>
                </li>
                <li class="pickup" data-type="3">
                    <span>0</span>
                    <label>{pigcms{:L('_ND_PICKEDUP_')}</label>
                </li>
                <li class="route" data-type="4">
                    <span>0</span>
                    <label>{pigcms{:L('_ND_ENROUTE_')}</label>
                </li>
            </ul>
        </div>
	</section>
    <div id="container">
        <div class="scroller" id="scroller">
            <div id="grab_list"></div>
        </div>
    </div>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script id="replyListBoxTpl" type="text/html">
        {{# for(var i = 0, len = d.list.length; i < len; i++){ }}
        <section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
            <div class="order_title" data-id="{{ d.list[i].supply_id }}">
                <span class="status_{{ d.list[i].status }}">
                    Order # {{ d.list[i].order_id }} -
                    {{# if(d.list[i].status == 2){ }}
                        {pigcms{:L('_ND_WAITING_')}
                    {{# } else if(d.list[i].status == 3) { }}
                        {pigcms{:L('_ND_INHAND_')}
                    {{# } else { }}
                        {pigcms{:L('_ND_ARRIVING_')}
                    {{# } }}
                </span>
                <div style="margin-top: 5px">{{ d.list[i].store_name }}</div>
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
                    {{# if(d.list[i].status == 2){ }}
                    <label> | </label>
                    <span>
                        {{# if(d.list[i].is_dinning == 1){ }}
                            Order is ready
                        {{# } else { }}
                            Order will be ready in
                        {{# } }}
                    </span>
                    <span class="time_show">{{ d.list[i].show_dining_time }}</span>
                    {{# } }}
                </div>
            </div>
            <div class="order_address">
                {{# if(d.list[i].status == 2){ }}
                <div>
                    <span class="from_label">
                        {pigcms{:L('_ND_FROM_')}
                    </span>
                    <span class="address">
                        {{ d.list[i].from_site }}
                        <span class="address_bottom">

                        </span>
                    </span>
                </div>
                {{# } }}
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
                {{# if(d.list[i].desc != '' && d.list[i].status == 4){ }}
                <div>
                    <span class="note_label"></span>
                    <span class="address">
                        {{ d.list[i].user_address.detail }}
                    </span>
                </div>
                {{# } }}
            </div>
            {{# if(d.list[i].pay_method == 0 && d.list[i].status == 4){ }}
            <div class="order_cash">
                {pigcms{:L('_ACTUAL_PAYMENT_')} : <label>${{ d.list[i].deliver_cash }}</label>
            </div>
            {{# } }}
            <div class="order_btn">
                <span class="location_btn" data-status="{{ d.list[i].status }}" data-from="{{ d.list[i].from_site }}" data-aim="{{ d.list[i].aim_site }}">
                    {{# if(d.list[i].status == 2){ }}
                        {pigcms{:L('_ND_ROUTE_MERCHANT_')}
                    {{# } else { }}
                        {pigcms{:L('_ND_ROUTE_CUSTOMER_')}
                    {{# } }}
                </span>
                <a href="tel:{{ d.list[i].phone }}">
                <span class="phone_btn">
                    {pigcms{:L('_ND_CALL_CUSTOMER_')}
                </span>
                </a>
                <a href="javascript:sendRequest({{ d.list[i].status }},{{ d.list[i].supply_id }});">
                <span class="accept_btn_{{ d.list[i].status }}">
                    {{# if(d.list[i].status == 2){ }}
                        {pigcms{:L('_ND_IMATREST_')}
                    {{# } else if(d.list[i].status == 3) { }}
                        {pigcms{:L('_ND_STARTDELIVERY_')}
                    {{# } else { }}
                        {pigcms{:L('_ND_ORDERCOMPLETED_')}
                    {{# } }}
                </span>
                </a>
            </div>
        </section>
        {{# } }}
    </script>
	<script type="text/javascript">
        setInterval(function(){
            $.get("{pigcms{:U('Deliver/index_count')}", function(response){
                if (response.err_code == false) {
                    $('#gray_count').html(response.gray_count);
                    $('#deliver_count').html(response.deliver_count);
                    $('#finish_count').html(response.finish_count);
                }
            }, 'json');
        }, 2000);

        var curr_status = 0;
        $('#top_menu').find('li').each(function () {
            $(this).bind('click',function () {
                changeStatus(this);
            });

        });

        function changeStatus(obj) {
            $('#top_menu').find('li').each(function () {
                $(this).removeClass('curr');
            });
            $(obj).addClass('curr');
            getList($(obj).data('type'));
        }

        getList(curr_status);
        function getList(status) {
            curr_status = status;

            $.post("{pigcms{:U('Deliver/get_process')}",{"status":status,'lat':lat, 'lng':lng},function(result){
                if(!result.error_code){
                    laytpl($('#replyListBoxTpl').html()).render(result, function(html){
                        $('#container').html(html);
                    });

                    $('.accept span').html(result.anum);
                    $('.pickup span').html(result.pnum);
                    $('.route span').html(result.rnum);

                    $('.order_title').click(function () {
                        var supply_id = $(this).data('id');
                        var order_url = "{pigcms{:U('Deliver/detail', array('supply_id'=>'"+supply_id+"'))}";
                        location.href = order_url;
                    });
                    
                    $('.location_btn').click(function () {
                        var status = $(this).data('status');
                        var url = '';
                        if(status == 2)
                            url = "https://maps.google.com/maps?q="+$(this).data('from')+"&z=17&hl=en";
                        else
                            url = "https://maps.google.com/maps?q="+$(this).data('aim')+"&z=17&hl=en";

                        location.href = url;
                    });
                }else{
                    alert("Error");
                }
            },'json');
        }

        function sendRequest(status,supply_id) {
            var DeliverListUrl = "";
            var content_msg = "";

            switch (status){
                case 2:
                    var order_url = "{pigcms{:U('Deliver/detail', array('supply_id'=>'"+supply_id+"'))}";
                    location.href = order_url;
                    return false;
                    break;
                case 3:
                    DeliverListUrl = "{pigcms{:U('Deliver/send')}";
                    content_msg = 'Order status updated';
                    break;
                case 4:
                    DeliverListUrl = "{pigcms{:U('Deliver/my')}";
                    content_msg = 'Order Completed';
                    break;

                default:
                    break;
            }

            $.post(DeliverListUrl, "supply_id="+supply_id, function(json){
                if (json.status) {
                    layer.open({title:['Reminder','background-color:#ffa52d;color:#fff;'],content:content_msg,btn: ['Confirm'],end:getList(curr_status)});
                } else {
                    layer.open({title:['Reminder','background-color:#ffa52d;color:#fff;'],content:'Error',btn: ['Confirm'],end:function(){}});
                }
            });
        }
        

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

        //var is_route = {pigcms{$is_route};
        var self_position = new google.maps.LatLng({pigcms{$deliver_session['lat']}, {pigcms{$deliver_session['lng']});
        var mapOptions = {
            zoom: 16,
            center: self_position
        };

        $(function () {
            setInterval(function(){
                $.get("{pigcms{:U('Deliver/index_count')}", function(response){
                    if (response.err_code == false) {
                        $('#gray_count').html(response.gray_count);
                        $('#deliver_count').html(response.deliver_count);
                        $('#finish_count').html(response.finish_count);
                    }
                }, 'json');
            }, 2000);
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
    </script>
</body>
</html>