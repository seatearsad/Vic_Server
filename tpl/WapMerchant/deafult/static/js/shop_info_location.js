//if(container == 'wechat'){
//			if(lat == '0.000000' || lng == '0.000000'){
//				wx.ready(function () {
//					wx.getLocation({
//					    success: function (res) {
//					        lat = parseFloat(res.latitude); // 纬度，浮点数，范围为90 ~ -90
//					        lng = parseFloat(res.longitude); // 经度，浮点数，范围为180 ~ -180。
//					        var gcjloc = transformFromWGSToGCJ(lng, lat);
//						    center = new qq.maps.LatLng(gcjloc.lat, gcjloc.lng);
//							shop_latlng = center;
//							init_map();
//					    }
//					});
//				});
//			}else{
//				init_map();
//			}
//		}else if(container == 'browser'){
//			if(lat == '0.000000' || lng == '0.000000'){
//				getLocation();
//			}else{
////				init_map();
//			}
//		}else if(container == 'web'){
////			init_map();
//		}
//		function getLocation(){
//			if(navigator.geolocation){
//				navigator.geolocation.getCurrentPosition(function(position){
//					lat=position.coords.latitude;
//					lng=position.coords.longitude;
//					center = new qq.maps.LatLng(lat, lng);
//					shop_latlng = center;
//					init_map();
//				});
//			}else{
//				alert("您的浏览器不支持地理定位");
//			}
//		}
		
		function open_map() {
		    $("#map-layer").show();
		    $(".pigcms-header,.container-fill").hide();

			$.getScript("http://api.map.baidu.com/getscript?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2",function(){
				var map = new BMap.Map("map", {"enableMapClick":false});
				map.enableScrollWheelZoom();
				map.enableContinuousZoom();
				
				
				var oPoint = new BMap.Point(116.331398,39.897445);
				var marker = new BMap.Marker(oPoint);
				var setPoint = function(mk,b){
					var pt = mk.getPosition();
					lng = pt.lng;
					lat = pt.lat;
//					$('#long').val(pt.lng);
//					$('#lat').val(pt.lat);
					(new BMap.Geocoder()).getLocation(pt,function(rs){
						addComp = rs.addressComponents;
						address_detail = addComp.street + addComp.streetNumber;
//						$("#location span").text(addComp.street + addComp.streetNumber);
					});
				};

				marker.enableDragging();

				map.centerAndZoom(oPoint, 16);
//				if($('#long').val() == '' || $('#lat').val() == ''){
				if(lng == '' || lat == ''){
						var geolocation = new BMap.Geolocation();
						geolocation.getCurrentPosition(function(r){
							if(this.getStatus() == BMAP_STATUS_SUCCESS){
								oPoint = new BMap.Point(r.point.lng, r.point.lat);
								map.centerAndZoom(oPoint,16);
								marker.setPosition(oPoint);
							} else {
								alert('failed'+this.getStatus());
							}        
						},{enableHighAccuracy: true})
//					function myFun(result){
//						oPoint = new BMap.Point(result.center['lng'],result.center['lat']);
//						map.centerAndZoom(oPoint,16);
//						marker.setPosition(oPoint);
//					}
//					var myCity = new BMap.LocalCity();
//					myCity.get(myFun);
				}else{
//					oPoint = new BMap.Point($('#long').val(), $('#lat').val());
					oPoint = new BMap.Point(lng, lat);
					map.centerAndZoom(oPoint, 16);
					marker.setPosition(oPoint);
				}

				map.addControl(new BMap.NavigationControl());
				map.enableScrollWheelZoom();  //启用滚轮放大缩小，默认禁用
				map.enableContinuousZoom();  

				map.addOverlay(marker);

				marker.addEventListener("dragend", function(){
					setPoint(marker,true);
				});
				marker.addEventListener("click", function(e){
					setPoint(marker,true);
				});
				local = new BMap.LocalSearch(map,{
					pageCapacity:1,
					onSearchComplete:function(results){
						map.centerAndZoom(results.getPoi(0).point, 16);
						marker.setPosition(results.getPoi(0).point);
					}
				});
			});
		}

//		function init_map() {
//		    if(lat == '0.000000' || lng == '0.000000'){
//			    var map = new qq.maps.Map(document.getElementById("map"), {
//			        disableDefaultUI: true,
//			        zoom: 13
//			    });
//		    	citylocation = new qq.maps.CityService({
//			        complete: function(result) {
//			            map.setCenter(result.detail.latLng);
//			        }
//			    });
//			    citylocation.searchLocalCity();
//			    var marker = new qq.maps.Marker({
//			        map: map
//			    });
//		    }else{
//		  		var map = new qq.maps.Map(document.getElementById("map"), {
//			    	center: center,
//			        disableDefaultUI: true,
//			        zoom: 17
//			    });
//			    var marker = new qq.maps.Marker({
//			    	position: center,
//			        map: map
//			    });
//		    }
//			geocoder = new qq.maps.Geocoder({
//			    complete : function(result){
//			        address_detail = result.detail.address;
//			        $("#location span").text(address_detail);
//			    }
//			});
//			geocoder.getAddress(center);
//		    qq.maps.event.addListener(map, 'click',function(e) {
//		        marker.setPosition(e.latLng);
//		        shop_latlng = e.latLng;
//				geocoder.getAddress(shop_latlng);
//		    });
//			$("[name='lat']").val(shop_latlng.lat);
//		   	$("[name='long']").val(shop_latlng.lng);
//		}
		$("#map-cancel").click(function() {
			$(".pigcms-header,.container-fill").show();
		    $("#map-layer").hide();
		}); 
		$("#map-confirm").click(function() {
//		    $("[name='lat']").val(shop_latlng.lat);
//		    $("[name='long']").val(shop_latlng.lng);
			$('#long').val(lng);
			$('#lat').val(lat);
			$("#location span").text(address_detail);
		    $("#map-layer").hide();
		    $(".pigcms-header,.container-fill").show();
		});