<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>Manage Your Schedule</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style.css" />
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script>
        $(function(){
            $(".startOrder,.stopOrder").click(function(){
                $.get("/wap.php?g=Wap&c=Deliver&a=index&action=changeWorkstatus&type="+$(this).attr('ref'), function(){
                    window.location.reload();
                });
            });
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
            },'json');

            return message;
        }
        //更新app 设备token
        var device_token = '';
        function pushDeviceToken(token) {
            device_token = token;
            var message = '';
            $.post("{pigcms{:U('Deliver/update_device')}", {'token':token}, function(result) {
                if(result){
                    message = result.message;
                }else {
                    message = 'Error';
                }
            });
            return message;
        }

        var week_all = [
            'SUN',
            'MON',
            'TUE',
            'WED',
            'THU',
            'FRI',
            'SAT'
        ];
    </script>
    <style>

    </style>
</head>
<body>
<include file="header" />
<section class="content_div">
    <div class="content_title">
        Sign In
    </div>
    <div class="content_desc">
        Use the phone number linked to your Tutti account to sign in
    </div>

    <div class="input_div" style="margin-top: 20px;">
        <input type="text" placeholder="10-digit phone number" name="phone">
    </div>
    <div class="input_div" style="margin-top: 10px;">
        <input type="password" placeholder="Password" name="pwd">
    </div>
</section>
<div class="bottom_btn">
    Next
</div>
<script src="{pigcms{$static_public}layer/layer.m.js"></script>

<script type="text/javascript">
    $('.bottom_btn').click(function () {
        window.location.href = "{pigcms{:U('Wap/Logoff/step_2')}";
    });
</script>
</body>
</html>