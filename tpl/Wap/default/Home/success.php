<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>{pigcms{:L('_VIC_NAME_')}</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	</head>
	<body>
        <script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
             var location_url = '{pigcms{$url}';
            <if condition="$msg eq ''">
                location.href= location_url;
            <else/>
                layer.open({title:["",'background-color:#ffa64d;color:#fff;'],content:'{pigcms{$msg}',btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],end:function(){location.href=location_url;}});
            </if>
        </script>
	</body>
</html>