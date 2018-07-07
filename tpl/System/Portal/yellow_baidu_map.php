<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
  <style type="text/css">
    body, html {width: 100%;height: 100%;margin:0;font-family:"微软雅黑";font-family:"微软雅黑";}
    #allmap{width:100%;height:500px;}
    p{margin-left:5px; font-size:14px;}
  </style>
  <link rel="stylesheet" type="text/css" href="//apps.bdimg.com/libs/todc-bootstrap/3.1.1-3.2.1/todc-bootstrap.min.css">
  <script src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
  <script src="//apps.bdimg.com/libs/layer/2.1/layer.js"></script>
  <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
  <title>地图定位</title>
</head>
<body>
    <input type="hidden" id="lng" />
    <input type="hidden" id="lat" />
    <div id="allmap"></div>
    <p style="float: right;margin-right: 10px;"><button class="btn btn-primary" onclick="save()">确定</button></p>
</body>
</html>
<script type="text/javascript">
    var map = new BMap.Map("allmap");
    map.enableScrollWheelZoom();
    map.enableContinuousZoom();

    // 解析地理位置
    var myGeo = new BMap.Geocoder();
    myGeo.getPoint("合肥市", function(point){
        if (point) {
            setlbs(point.lng,point.lat);
            map.centerAndZoom(point, 11);
            map.addOverlay(new BMap.Marker(point));
        }
    }, "合肥市");

    map.addEventListener("click", showInfo);

    function showInfo(e){
        var allOverlay = map.getOverlays();
        for (var i = 0; i < allOverlay.length -1; i++){
            map.removeOverlay(allOverlay[i+1]);
        }
        setlbs(e.point.lng,e.point.lat);
        var point = new BMap.Point(e.point.lng, e.point.lat);
        var marker = new BMap.Marker(point);  // 创建标注
        map.addOverlay(marker);
    }
    
    function setlbs(lng,lat){
        $('#lng').val(lng);
        $('#lat').val(lat);
    }

    function save(){
        var lng = $('#lng').val();
        var lat = $('#lat').val();
        if(lng == '' || lat == ''){
            layer.alert('请先在地图上标注位置');
            return;
        }
        window.parent.setlnglat(lng,lat);
        window.close();
    }
</script>
