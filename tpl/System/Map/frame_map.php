<include file="Public:header"/>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en"></script>
<script type="text/javascript">
    var choose_city_name="{pigcms{:U('Area/ajax_city_name')}";
$(function(){
	var map;
	var marker;
	var infowindow;
	var geocoder;
	var markersArray = [];
	//设置中心点
    var long_lat = "{pigcms{$long_lat}".split(',');

	var pyrmont = {lat: parseFloat(long_lat[1]), lng: parseFloat(long_lat[0])};

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
		var message = "Success,location pinned!";
		var infowindow = new google.maps.InfoWindow({
				content: message,
				size: new google.maps.Size(50, 50)
			});
		infowindow.open(map, marker);
		setPoint(event);
	});

	var setPoint = function(event){
		$('#long_lat', store_add_frame).val(event.latLng.lng()+','+event.latLng.lat());
        var geocoder = new google.maps.Geocoder();
        var request = {
            location:{lat:event.latLng.lat(), lng:event.latLng.lng()}
        };
        geocoder.geocode(request, function(results, status){
            if(status == 'OK') {
                $("#adress",store_add_frame).val(results[0].formatted_address);
                var add_com = results[0].address_components;
                var is_get_city = false;
                for(var i=0;i<add_com.length;i++){
                    if(add_com[i]['types'][0] == 'locality'){
                        is_get_city = true;
                        var city_name = add_com[i]['long_name'];
                        $('#city_area',store_add_frame).html(city_name);
                        $.post(choose_city_name,{city_name:city_name},function(result){
                            if (result.error == 1){
                                alert("该城市还未开放！");
                                $('#city_id',store_add_frame).val(0);
                            }else{
                                $('#city_id',store_add_frame).val(result['info']['city_id']);
                            }
                        },'JSON');
                    }
                }
                if(!is_get_city) {
                    alert('未获取到城市信息');
                    $('#city_area',store_add_frame).html('');
                    $('#city_id').val(0);
                }
            }
            console.log(results);
            console.log(status);
        });
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
<div id="frame_map_tips" style="margin:0">Drag the red icon for correct location</div>
<div class="modal-body no-padding" style="width:100%;">
	
		<input id="map-keyword" type="textbox" style="width:300px;" placeholder="Enter your store address" value=""/>
		<input type="submit" id="searchBtn" value="Search"/>
	
	<div id="map" style="height:478px;"></div>
</div>
<include file="Public:footer"/>