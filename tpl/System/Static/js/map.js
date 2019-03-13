$(function(){
	var pyrmont = {lat: 48.430168, lng: -123.343033};
	var map = null;
	if ($('#long_lat').val() != '') {
		var long_lat = $('#long_lat').val().split(',');
		pyrmont = {lat:parseFloat(long_lat[1]), lng:parseFloat(long_lat[0])};
	}
	console.log(pyrmont);
	map = new google.maps.Map(document.getElementById('cmmap'), {
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
		$('#long_lat').val(event.latLng.lng()+','+event.latLng.lat());
		var geocoder = new google.maps.Geocoder();
		var request = {
			location:{lat:event.latLng.lat(), lng:event.latLng.lng()}
		}
		geocoder.geocode(request, function(results, status){
			if(status == 'OK') {
				$("#adress").val(results[0].formatted_address);
                var add_com = results[0].address_components;
                var is_get_city = false;
                for(var i=0;i<add_com.length;i++){
                    if(add_com[i]['types'][0] == 'locality'){
                        is_get_city = true;
                        var city_name = add_com[i]['long_name'];
                        $('#city_area').html(city_name);
                        $.post(choose_city_name,{city_name:city_name},function(result){
                            if (result.error == 1){
                                alert("该城市还未开放！");
                                $('#city_id').val(0);
                            }else{
                                $('#city_id').val(result['info']['city_id']);
                            }
                        },'JSON');
                    }
                }
                if(!is_get_city) {
                    alert('未获取到城市信息');
                    $('#city_area').html('');
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
			radius: '50000',
			query: val
		};

		service = new google.maps.places.PlacesService(map);
		service.textSearch(request, function(result, status){
			marker.setMap(null);
			map.setCenter({lat:result[0].geometry.location.lat(), lng:result[0].geometry.location.lng()});
			marker.setMap(map);
			marker.setPosition(map.getCenter());
		});
	};

	//搜索
	$('#map-search').submit(function(){
		$('#map-keyword').val($.trim($('#map-keyword').val()));
		if($('#map-keyword').val().length >0){
			search($('#map-keyword').val());
		}
		//alert(1);
		return false;
	});
});