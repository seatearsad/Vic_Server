<include file="Public:header"/>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBt3Lu-FlQE5LgusybLzqGr8lIXmvTsLZU&libraries=places&language=zh-CN"></script>
<script type="text/javascript">
	
$(function(){
	var map;
	var marker;
	var infowindow;
	var geocoder;
	var markersArray = [];
	//设置中心点
	var pyrmont = {lat: 48.430168, lng: -123.343033};

	var store_add_frame = window.top.frames['Openstore_add'].document;

	map = new google.maps.Map(document.getElementById('map'), {
		center: pyrmont,
		zoom: 15
	});

	//添加图标
	var marker = new google.maps.Marker({
		position: map.getCenter(),
		draggable: true,
		map: map
	});

	//拖动图标结束
	google.maps.event.addListener(marker, 'dragend', function (event) {
		var message = "您的坐标设置成功！";
		var infowindow = new google.maps.InfoWindow({
				content: message,
				size: new google.maps.Size(50, 50)
			});
		infowindow.open(map, marker);
		setPoint(event);
	});

	var setPoint = function(event){
		$('#long_lat', store_add_frame).val(event.latLng.lng()+','+event.latLng.lat());
	}

	//搜索
	var search = function(val){
		var request = {
			location: pyrmont,
			radius: '5000',
			query: val
		};

		service = new google.maps.places.PlacesService(map);
		service.textSearch(request, function(result, status){
			console.log(result);
			console.log(status);
			marker.setMap(null);
			map.setCenter({lat:result[0].geometry.location.lat(), lng:result[0].geometry.location.lng()});
			marker.setMap(map);
			marker.setPosition(map.getCenter());
			
		});
	};


	$('#searchBtn').click(function(){
		$('#map-keyword').val($.trim($('#map-keyword').val()));
		if($('#map-keyword').val().length >0){
			search($('#map-keyword').val());
		}
		return false;
	});
});
</script>
<style>.BMap_cpyCtrl{display:none;}</style>
<div id="frame_map_tips" style="margin:0">(用鼠标滚轮可以缩放地图)&nbsp;&nbsp;&nbsp;&nbsp;拖动红色图标，左侧经纬度框内将自动填充经纬度。</div>
<div class="modal-body no-padding" style="width:100%;">
	
		<input id="map-keyword" type="textbox" style="width:300px;" placeholder="尽量填写城市、区域、街道名" value=""/>
		<input type="submit" id="searchBtn" value="搜索"/>
	
	<div id="map" style="height:478px;"></div>
</div>
<include file="Public:footer"/>