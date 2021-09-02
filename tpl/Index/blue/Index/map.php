<!DOCTYPE html>
<html>
<head>
    <title>Maps</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }
        /* Optional: Makes the sample page fill the window. */
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<body>
<div id="map"></div>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&callback=initMap"
        async defer></script>
<script>
    var store_lat = "{pigcms{$data['store_lat']}";
    var store_lng = "{pigcms{$data['store_lng']}";
    var user_lat = "{pigcms{$data['user_lat']}";
    var user_lng = "{pigcms{$data['user_lng']}";
    var deliver_lat = "{pigcms{$data['deliver_lat']}";
    var deliver_lng = "{pigcms{$data['deliver_lng']}";

    var order_id = "{pigcms{$data['order_id']}";

    var deliver_icon = "{pigcms{$static_public}images/deliver/icon_deliver_map.png";
    var store_icon = "{pigcms{$static_public}images/deliver/icon_store_map.png";

    //获取get传值的方法
    function getQueryString(name) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r != null) return decodeURI(r[2]);
        return null;
    }

    var uluru = {lat: parseFloat(deliver_lat), lng: parseFloat(deliver_lng)};
    var store_pos = {lat:parseFloat(store_lat), lng:parseFloat(store_lng)};
    var user_pos = {lat:parseFloat(user_lat), lng:parseFloat(user_lng)};
    // The map, centered at Uluru
    var map;

    var deliver,store,marker_deliver,marker_store,marker_user,bounds;

    setInterval(function (){
        updatePosition();
        }, 30000);

    function initMap() {
        // The location of Uluru
        var lng= Number(getQueryString("lng"));
        var lat=Number(getQueryString("lat"));
        var label=getQueryString("label");
        console.log('initMap');

        map = new google.maps.Map(
            document.getElementById('map'), {zoom: 18, center: uluru});

        deliver = {
            url:deliver_icon,
            scaledSize: new google.maps.Size(35,35),
            size: new google.maps.Size(35,35)
        };

        store =  {
            url:store_icon,
            scaledSize: new google.maps.Size(35,35),
            size: new google.maps.Size(35,35)
        };

        // The marker, positioned at Uluru
        marker_deliver = new google.maps.Marker({position: uluru, map: map,icon:deliver});
        marker_store = new google.maps.Marker({position: store_pos, map: map,icon:store});
        marker_user = new google.maps.Marker({position: user_pos, map: map});

        bounds = new google.maps.LatLngBounds();

        bounds.extend(new   google.maps.LatLng(marker_deliver.getPosition().lat()
            ,marker_deliver.getPosition().lng()));
        bounds.extend(new   google.maps.LatLng(marker_store.getPosition().lat()
            ,marker_store.getPosition().lng()));
        bounds.extend(new   google.maps.LatLng(marker_user.getPosition().lat()
            ,marker_user.getPosition().lng()));

        map.fitBounds(bounds);
        //地图缩放时触发，当地图的缩放比例大于默认比例时，恢复为默认比例
        // google.maps.event.addListener(map, 'zoom_changed', function () {
        //     if (map.getZoom() > defaultZoom){
        //         map.setZoom(defaultZoom);
        //     }
        // });
    }

    function updatePosition(){
        $.post("{pigcms{:U('Index/map')}", {order_id:order_id},function(response){
            if(response.error == 0){
                uluru = {lat: parseFloat(response.deliver_lat), lng: parseFloat(response.deliver_lng)};

                //marker_deliver = new google.maps.Marker({position: uluru, map: map,icon:deliver});
                marker_deliver.setPosition(uluru);

                //bounds = new google.maps.LatLngBounds();

                // bounds.extend(new   google.maps.LatLng(marker_deliver.getPosition().lat()
                //     ,marker_deliver.getPosition().lng()));
                // bounds.extend(new   google.maps.LatLng(marker_store.getPosition().lat()
                //     ,marker_store.getPosition().lng()));
                // bounds.extend(new   google.maps.LatLng(marker_user.getPosition().lat()
                //     ,marker_user.getPosition().lng()));

                //map.update();
            }
        }, 'json');
    }
</script>
</body>
</html>