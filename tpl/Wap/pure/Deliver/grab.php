<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_C_PENDING_LIST_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<!-- 	<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script> -->
<!-- 	<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script> -->
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
<!-- 	<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script> -->
    <!--script src="https://webapi.amap.com/maps?v=1.4.15&key=05c7ac0deb8eea9377a0ae555efc6b92"></script-->
    <script type="text/javascript">
		var location_url = "{pigcms{:U('Deliver/grab')}", detail_url = "{pigcms{:U('Deliver/detail')}", lat = "{pigcms{$deliver_session['lat']}", lng = "{pigcms{$deliver_session['lng']}", static_path = "{pigcms{$static_path}";
        var reject_url = "{pigcms{:U('Deliver/reject')}";
        //ios app 更新位置
        function updatePosition(lat,lng){
            var message = '';
            $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
                if(result){
                    message = result.message;
                }else {
                    message = 'Error';
                }
            },'json');

            return message;
        }

        //定位是否有问题
        var location_error = false;

        if(navigator.geolocation) {
            //alert('geolocation:start');
            navigator.geolocation.getCurrentPosition(function (position) {
                //alert("geolocation_lat:" + position.coords.latitude);
                updatePosition(position.coords.latitude, position.coords.longitude);
                run_update_location();
            }, function (error) {
                console.log("geolocation:" + error.code);
                // location_error = true;
                // run_Amap();
                // run_update_location();
            },{enableHighAccuracy:true,timeout:50000});
        }else{
            //alert('geolocation:error');
        }

        function run_Amap() {
            var mapObj = new AMap.Map('iCenter');
            mapObj.plugin('AMap.Geolocation', function () {
                geolocation = new AMap.Geolocation({
                    enableHighAccuracy: true,//是否使用高精度定位，默认:true
                    timeout: 10000,          //超过10秒后停止定位，默认：无穷大
                    maximumAge: 0,           //定位结果缓存0毫秒，默认：0
                    convert: true,           //自动偏移坐标，偏移后的坐标为高德坐标，默认：true
                    showButton: true,        //显示定位按钮，默认：true
                    buttonPosition: 'LB',    //定位按钮停靠位置，默认：'LB'，左下角
                    buttonOffset: new AMap.Pixel(10, 20),//定位按钮与设置的停靠位置的偏移量，默认：Pixel(10, 20)
                    showMarker: true,        //定位成功后在定位到的位置显示点标记，默认：true
                    showCircle: true,        //定位成功后用圆圈表示定位精度范围，默认：true
                    panToLocation: true,     //定位成功后将定位到的位置作为地图中心点，默认：true
                    zoomToAccuracy:true      //定位成功后调整地图视野范围使定位位置及精度范围视野内可见，默认：false
                });
                mapObj.addControl(geolocation);
                geolocation.getCurrentPosition();
                AMap.event.addListener(geolocation, 'complete', onComplete);//返回定位信息
                AMap.event.addListener(geolocation, 'error', onError);      //返回定位出错信息
            });
        }

        function onComplete(result) {
            console.log(result.position + ' | ' + result.location_type);
            var lat = result.position.getLat();
            var lng = result.position.getLng();
            updatePosition(lat,lng);
        }

        function onError(error) {
            console.log(error.info + '||' + error.message);
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
	</script>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=zh-CN"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/grab.js?211" charset="utf-8"></script>
</head>
<style>
    .delivery p em {
        overflow: hidden;
        white-space: normal;
        text-overflow: ellipsis;
        display: inline-block;
        font-size: 14px;
        height: auto;
        word-break: normal | break-word;
    }
</style>
<body>
	<div class="Dgrab" id="container">
		<div class="scroller" id="scroller">
			<div id="grab_list"></div>
		</div>
		<!-- 空白图 -->
		<div class="psnone" ><img src="{pigcms{$static_path}images/qdz_02.png"></div>
		<!-- 空白图 -->
	</div>
	<include file="menu"/>
</body>
<script id="replyListBoxTpl" type="text/html">

{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
<section class="robbed supply_{{ d.list[i].supply_id }}" data-id="{{ d.list[i].supply_id }}">
	<div class="Online c9 p10 f14 go_detail" data-id="{{ d.list[i].supply_id }}" style="cursor: pointer;">
		<span>
            {pigcms{:L('_B_PURE_MY_68_')}: {{ d.list[i].real_orderid }}
            {{# if(d.list[i].uid == 0){ }}
                ({pigcms{:L('_PAY_FROM_MER_')})
            {{# } }}
        </span>
		{{# if(d.list[i].pay_method == 1){ }}
		<a href="javascript:;" class="fr cd p10">{pigcms{:L('_ONLINE_PAY_')}</a>
		{{# } else { }}
		<a href="javascript:;" class="fr cd p10 on">{pigcms{:L('_CASH_ON_DELI_')}</a>
		{{# } }}
	</div>
	<div class="Title m10 go_detail" data-id="{{ d.list[i].supply_id }}">
		<h2 class="f16 c3">{{ d.list[i].store_name }}</h2>
		<p class="f14 c9">{pigcms{:L('_ORDER_TIME_')}：{{ d.list[i].order_time }}</p>
	</div>
	<div class="delivery m10">
		<p class="f14 c6 on">
			<a href="javascript:;" class="clr">
				<span class="fl">{pigcms{:L('_C_PICK_UP_')}</span>
				<em class="fl">{{ d.list[i].from_site }}</em>
				<i class="cd f12 fl">{pigcms{:L('_C_DISTANCE_')}:{{ d.list[i].store_distance }}</i>
			</a>
		</p>
		<p class="f14 c6 on1">
			<a href="{{ d.list[i].map_url }}" class="clr">
				<span class="fl">{pigcms{:L('_C_DELIVER_')}</span>
				<em class="fl">{{ d.list[i].aim_site }}</em>
				<i class="cd f14 fl">{pigcms{:L('_LOOK_ROUTE_')}</i>
			</a>
		</p>
	</div>
	<div class="Namelist p10 f14">
		<h2 class="f15 c3">{{ d.list[i].name }} <span class="c6"><a href="tel:{{ d.list[i].phone }}">{{ d.list[i].phone }}</a></span></h2>
        <p class="c9">{pigcms{:L('_MEAL_TIME_')}：{{ d.list[i].meal_time }}</p>
        <p class="c9">{pigcms{:L('_EXPECTED_TIME_')}：{{ d.list[i].appoint_time }}</p>
		<!--p class="red">{pigcms{:L('_TOTAL_RECE_')}：<i>${{ d.list[i].deliver_cash }}</i></p-->
		<p class="red">{pigcms{:L('_C_DISTANCE_')}:{{ d.list[i].distance }}(KM)
            <!--，{pigcms{:L('_DELI_PRICE_')}:${{ d.list[i].freight_charge }},{pigcms{:L('_TIP_TXT_')}:${{d.list[i].tip_charge}} --></p>
	</div>
	<div class="sign_bottom">
		<a href="javascript:void(0);" class="rob" data-spid="{{ d.list[i].supply_id }}">{pigcms{:L('_D_ACCEPT_ORDER_')}</a>
        <a href="javascript:void(0);" class="rej" data-spid="{{ d.list[i].supply_id }}">{pigcms{:L('_D_REJECT_ORDER_')}</a>
	</div>
</section>
{{# } }}
</script>
</html>