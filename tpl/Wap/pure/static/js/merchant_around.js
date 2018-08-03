// var myScroll;
$(function(){
	$('#around-map').height($(window).height());
	// $('#scroller').css({'min-height':($(window).height()+1)+'px'});
	// myScroll = new IScroll('#listList', { probeType: 1,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:false,scrollbars:true,shrinkScrollbars: 'scale',resizeScrollbars:false,fadeScrollbars:true});
	myScroll = new IScroll('#listList',{probeType:1,disableMouse:true,disablePointer:true,mouseWheel:false,scrollX:false,scrollY:true,click:iScrollClick()});
	
	motify.log(getLangStr('_LOADING_TXT_'),0,{show:true});
	if(user_long == '0' || !user_long){
		if(motify.checkLifeApp() && motify.getLifeAppVersion() >= 50 && (motify.checkIos() || motify.checkAndroid())){
			if(motify.checkAndroid()){
				var locations = window.lifepasslogin.getLocation(false);
				var locationArr = locations.split(',');
				var user_long = $.trim(locationArr[0]);
				var user_lat = $.trim(locationArr[1]);
				getStoreListBefore({result:[{x:user_long,y:user_lat}]});
			}else{
				$('body').append('<iframe src="pigcmso2o://getLocation/false" style="display:none;"></iframe>');
			}
		}else{
			getUserLocation({okFunction:'geoconvPlace',useHistory:false});
		}
		// getUserLocation({okFunction:'geoconvPlace',useHistory:false});
	}else{
		getMap(user_long,user_lat);
	}
});
function callbackLocation(locations){
	var locationArr = locations.split(',');
	var user_long = $.trim(locationArr[0]);
	var user_lat = $.trim(locationArr[1]);
	getStoreListBefore({result:[{x:user_long,y:user_lat}]});
}
function geoconvPlace(userLongLat,lng,lat){
	getStoreListBefore({result:[{x:lng,y:lat}]});
}
function getStoreListBefore(result){
	console.log(result);
	getMap(result.result[0].x,result.result[0].y);
}
var tmpLng,tmpLat,map,storeBox=[],storePoint=[];
/*
function getMap(lng,lat){
	map = new BMap.Map("around-map",{enableMapClick:false});            // 创建Map实例
	map.centerAndZoom(new BMap.Point(lng,lat),15);                 // 初始化地图,设置中心点坐标和地图级别。
	// map.addControl(new BMap.ZoomControl());      //添加地图缩放控件	
	// var marker = new BMap.Marker(new BMap.Point(lng,lat));
	// map.addOverlay(marker);
	tmpLng = lng;
	tmpLat = lat;
	getStoreList(tmpLng,tmpLat);
	map.addEventListener("dragend", function showInfo(){
		if(map.getZoom() >= 15){
			motify.clearLog();
			var cp = map.getCenter();
			var range = GetDistance(tmpLng,tmpLat,cp.lng,cp.lat);
			if(range > 300){
				tmpLng = cp.lng;
				tmpLat = cp.lat;
				getStoreList(tmpLng,tmpLat);
			}
		}else{
			motify.log('地图范围过大，请扩大后查看');
		}
	});
	map.addEventListener("zoomend", function(){
		motify.clearLog();
		if(this.getZoom() < 15){
			map.clearOverlays();
			motify.log('地图范围过大，请扩大后查看');
		}
	});   
}*/
function getMap(lng, lat) {
	var pyrmont = {lat: parseFloat(lat), lng: parseFloat(lng)};
	
	map = new google.maps.Map(document.getElementById('around-map'), {
		center: pyrmont,
		zoom: 15
	});
	tmpLng = lng;
	tmpLat = lat;
	getStoreList(tmpLng,tmpLat);
}

//附近店铺列表
function getStoreList(lng,lat){
	motify.log(getLangStr('_LOADING_TXT_'),0,{show:true});
	$.each(storePoint,function(i,item){
		//storePoint[i].closeInfoWindow();
	});
	//map.clearOverlays();
	storePoint = [];
	// lng = 117.238061;
	// lat = 31.814095;
	$.post(window.location.pathname+'?c=Merchant&a=ajaxAround',{lng:lng,lat:lat},function(result){
		if(result.length > 0){
			var listHtml = '';
			$.each(result,function(i,item){
				var listUrl = window.location.pathname+'?c=Group&a=shop&store_id='+item.store_id;
				listHtml+= '<dd class="link-url" data-url="'+listUrl+'"><div class="title">'+item.sname+'</div><div class="phone">Phone：'+item.sphone+'</div><div class="desc">Address：'+item.adress+'</div></dd>';
				// if(i == 0){
					// var marker = new BMap.Marker(new BMap.Point(item['long'],item['lat']),{icon:new BMap.Icon(static_path+"images/blue_marker.png", new BMap.Size(24,25))});
				// }else{
					//var marker = new BMap.Marker(new BMap.Point(item['long'],item['lat']),{icon:new BMap.Icon(static_path+"images/red_marker.png", new BMap.Size(24,25))});
				// }
				var marker = new google.maps.Marker({
					position:item['lat']+","+item['long'],
					icon: static_path+"images/red_marker.png",
					map: map
				});
				//map.addOverlay(marker);
				storePoint[i] = marker;
				// console.log(item);
				var message = '<div class="windowBox link-url" data-url="'+listUrl+'"><!--img id="imgDemo" src="'+item.img+'"/><br/--><a href="'+listUrl+'" style="color:white;">'+item.sname+'</a><br/>Phone：<a href="tel:'+item.sphone+'" style="color:#06c1ae;">'+item.sphone+'</a><br/>Address：'+item.adress+'</div>';
			
				var infowindow = new google.maps.InfoWindow({
						content: message,
						size: new google.maps.Size(50, 50)
					});
				infowindow.open(map, marker);


				//点击
				/*google.maps.event.addListener(marker, 'click', function (event) {
					var message = "您的坐标设置成功！";
					var infowindow = new google.maps.InfoWindow({
							content: message,
							size: new google.maps.Size(50, 50)
						});
					infowindow.open(map, marker);
					setPoint(event);
				});*/

				/*
				marker.addEventListener("click", function(){
					$.each(result,function(k,ktem){
						if(i == k){
							storePoint[k].setIcon(new BMap.Icon(static_path+"images/blue_marker.png", new BMap.Size(24,25)));
						}else{
							storePoint[k].setIcon(new BMap.Icon(static_path+"images/red_marker.png", new BMap.Size(24,25)));
						}
					});
					this.openInfoWindow(infoWindow);
					// document.getElementById('imgDemo').onload = function (){
						// infoWindow.redraw();
					// }
				});*/
			});
			$('#listList dl').html(listHtml);
		}else{
			$('#listList dl').empty();
		}
		motify.clearLog();
	});
	$(document).on('click','#listBtn',function(){
		$('#listList').height('auto');
		if($('#listList dl').html() != ''){
			$('#listBg,#listList').show();
			if($('#listList dl').height() < $('#listList').height()){
				$('#listList').css({height:$('#listList dl').height()-1+'px',top:(($(window).height()-$('#listList dl').height())/2)});
			}
			myScroll.refresh();
		}else{
			motify.log('屏幕地图中没有店铺');
		}
	});
	$(document).on('click','#listBg',function(){
		$('#listBg,#listList').hide();
	});
}