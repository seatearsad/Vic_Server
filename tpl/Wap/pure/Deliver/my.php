<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>Delivery List</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
</head>
<body>
<section class="Navigation">
	<ul class="clr">
		<a href="{pigcms{:U('Deliver/pick')}"><li class="on">{pigcms{:L('_CC_PICK_UP_')}</li></a>
		<a href="{pigcms{:U('Deliver/send')}"><li class="on1">{pigcms{:L('_CC_UNDELIVERED_')}</li></a>
		<a href="{pigcms{:U('Deliver/my')}"><li class="on2" style="border-bottom:#19caad 2px solid;color:#19caad">{pigcms{:L('_CC_IN_TRANSIT_')}</li></a>
	</ul>
</section>
<section class="nav_end clr">
	<div class="Dgrab" id="Dgrab1">
		<if condition="$list">
		<volist name="list" id="row">
		<section class="robbed supply_{pigcms{$row['supply_id']} go_detail" data-id="{pigcms{$row.supply_id}">
			<div class="Online c9 p10 f14" data-id="{pigcms{$row.supply_id}">
				<span>
                    {pigcms{:L('_B_PURE_MY_68_')}: {pigcms{$row['real_orderid']}
                    <if condition="$row['uid'] eq 0">
                        ({pigcms{:L('_PAY_FROM_MER_')})
                    </if>
                </span>
				<if condition="$row['pay_method'] eq 1">
				<a href="javascript:;" class="fr cd p10">{pigcms{:L('_ONLINE_PAY_')}</a>
				<else />
				<a href="javascript:;" class="fr cd p10 on">{pigcms{:L('_CASH_ON_DELI_')}</a>
				</if>
				
			</div>
			<div class="Title m10 go_detail" data-id="{pigcms{$row.supply_id}">
				<h2 class="f16 c3">{pigcms{$row['store_name']}</h2>
				<p class="f14 c9">{pigcms{:L('_ORDER_TIME_')}：{pigcms{$row['order_time']}</p>
				<if condition="$row['get_type'] eq 1">
				<div class="leaflets">{pigcms{:L('_C_SYS_ASS_ORDER_')}</div>
				</if>
			</div>
			<div class="delivery m10">
				<p class="f14 c6 on">
					<a href="javascript:;" class="clr">
						<span class="fl">{pigcms{:L('_C_PICK_UP_')}</span>
						<em class="fl">{pigcms{$row['from_site']}</em>   
					</a>
				</p>
				<p class="f14 c6 on1">
					<a href="{pigcms{$row['map_url']}" class="clr">
						<span class="fl">{pigcms{:L('_C_DELIVER_')}</span>
						<em class="fl">{pigcms{$row['aim_site']}</em>
						<i class="cd f14 fl">{pigcms{:L('_LOOK_ROUTE_')}</i>
					</a>    
				</p>
			</div>
			<div class="Namelist p10 f14">
				<h2 class="f15 c3">{pigcms{$row['name']} <span class="c6"><a href="tel:{pigcms{$row['phone']}">{pigcms{$row['phone']}</a></span></h2> 
				<p class="c9">{pigcms{:L('_EXPECTED_TIME_')}：{pigcms{$row['appoint_time']}</p>
				<if condition="$row['note']">
				<p class="c9">{pigcms{:L('_NOTE_INFO_')}：{pigcms{$row['note']}</p>
				</if>
				<p class="red" style="height: 30px;">
                    {pigcms{:L('_TOTAL_RECE_')}：<i>${pigcms{$row['deliver_cash']}</i>
                    <!--if condition="$row['deliver_cash'] neq 0"-->
                    <if condition="$row['uid'] eq 0">
                        <if condition="$row['deliver_cash'] neq 0">
                            <input type="button" value="{pigcms{:L('moneris')}" style="width: 120px;height: 30px; background-color: #04B7A5;color: #ffffff;" class="t_online" data-id="{pigcms{$row['supply_id']}">
                        </if>
                    </if>
                </p>
                <p class="red">{pigcms{:L('_C_DISTANCE_')}{pigcms{$row['distance']}(KM)</p>
                    <!--，{pigcms{:L('_DELI_PRICE_')}:${pigcms{$row['freight_charge']},{pigcms{:L('_TIP_TXT_')}:${pigcms{$row['tip_charge']}</p-->
				<if condition="$row['get_type'] eq 2">
				<div class="Order">From Courier - {pigcms{$row['change_name']}</div>
				</if>
			</div>
			<div class="sign_bottom">
				<a href="javascript:;" class="service" data-id="{pigcms{$row['supply_id']}">{pigcms{:L('_ARRIVAL_TXT_')}</a>
			</div>
		</section>
		</volist>
		<else />
		<!-- 空白图 -->
			<div class="psnone">
				<img src="{pigcms{$static_path}images/qdz_02.png">
			</div>
		<!-- 空白图 -->
		</if>
	</div>
</section>
<!--script src="https://webapi.amap.com/maps?v=1.4.15&key=05c7ac0deb8eea9377a0ae555efc6b92"></script-->
<script type="text/javascript">
$(function(){
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	$(".Dgrab").css({"margin-top":"40px"});
	$(".nav_end .Dgrab").width($(window).width());

	var DeliverListUrl = "{pigcms{:U('Deliver/my')}";
	var mark = 0;

	function grab(e) {
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		$.post(DeliverListUrl, "supply_id="+supply_id, function(json){
			mark = 0;
			if (json.status) {
				layer.open({title:['Reminder','background-color:#ffa52d;color:#fff;'],content:'Order Completed',btn: ['Confirm'],end:function(){}});
			} else {
				layer.open({title:['Reminder','background-color:#ffa52d;color:#fff;'],content:'Error',btn: ['Confirm'],end:function(){}});
			}
			$(".supply_"+supply_id).remove();
		});
	}

	function detail(e) {
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		var DetailUrl = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'d%'))}";
		location.href = DetailUrl.replace(/d%/, supply_id);
	}

    //garfunkel add
    function onlinePay(e){
        if (mark) {
            return false;
        }
        mark = 1;
        e.stopPropagation();

        var supply_id = $(this).attr("data-id");
        var DetailUrl = "{pigcms{:U('Wap/Deliver/online', array('supply_id'=>'d%','lang'=>'en'))}";
        location.href = DetailUrl.replace(/d%/, supply_id);
    }

    $(".t_online").bind("click",onlinePay);
	$(".service").bind("click", grab);
	$(".go_detail").bind("click", detail);
});

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
<include file="menu"/>
</body>
</html>