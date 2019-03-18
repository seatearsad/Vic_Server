<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_OUT_TXT_')} {pigcms{:L('_B_PURE_MY_63_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<body>
    <div id="map"></div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en"></script>
    <script>
        var lat = parseFloat("{pigcms{$deliver.lat}");
        var lng = parseFloat("{pigcms{$deliver.lng}");
        var name = "{pigcms{$deliver.name}";
        var phone = "{pigcms{$deliver.phone}"
        var height = $(window).height();
        $('#map').css('height',height);

        var mapOptions = {
            zoom: 18,
            center: {lat:lat, lng:lng}
        }

        var map = new google.maps.Map(document.getElementById('map'), mapOptions);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(lat,lng),
            map: map,
            label:"{pigcms{$deliver.uid}",
            title: name + '('+ phone + ')',
            tag:"{pigcms{$deliver.uid}"
        });
        map.setCenter(marker.getPosition());

        setTimeout(
            getNewPosition
        ,3000);

        function getNewPosition() {
            $.post("{pigcms{:U('My/getDeliver')}",{deliver_id:"{pigcms{$deliver.uid}"},function(data){
                if(data.status == 1){
                    marker.setPosition({lat:parseFloat(data.url.lat), lng:parseFloat(data.url.lng)});
                    map.setCenter(marker.getPosition());
                    setTimeout(
                        getNewPosition
                        ,3000);
                }
            });

        }
    </script>
</body>
</html>