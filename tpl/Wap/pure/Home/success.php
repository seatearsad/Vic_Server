<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>{pigcms{:L('_B_D_LOGIN_TIP2_')}</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
	</head>
	<body>
        <script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
            var location_url = '{pigcms{$url}';
            layer.open({title:["{pigcms{:L('_B_D_LOGIN_TIP2_')}",'background-color:#06c1ae;color:#fff;'],content:"{pigcms{$msg}",btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],end:function(){location.href=location_url;}});</script>
	</body>
</html>