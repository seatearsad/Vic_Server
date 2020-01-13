<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>Instructions & Announcement</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
    <script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>
<style>
    body{background-color: white;}
    #all_list{
        width: 90%;
        margin: 10px auto;
        font-size: 12px;
        color: #666666;
    }
    #list_title{
        text-align: center;
        margin-top: 20px;
        margin-bottom: 5px;
    }
    #list_time{
        text-align: center;font-size: 10px;color: #999999;
        margin-bottom: 10px;
    }
</style>
<body>
<div id="all_list">
    <div id="list_title">
        {pigcms{$doc.title}
    </div>
    <div id="list_time">
        updated {pigcms{$doc.last_time|date="Y-M-d H:i:s",###}
    </div>
    <div>
        {pigcms{$doc.content}
    </div>
</div>
<script type="text/javascript">
    var ua = navigator.userAgent;
    if(!ua.match(/TuttiDeliver/i)) {
        navigator.geolocation.getCurrentPosition(function (position) {
            updatePosition(position.coords.latitude,position.coords.longitude);
        });
    }
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
</script>
</body>
</html>