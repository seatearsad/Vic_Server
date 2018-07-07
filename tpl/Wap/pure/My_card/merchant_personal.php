<!doctype html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<title>{pigcms{$now_merchant.name}的会员资料</title>
	<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
</head>
<body>
	<form action="" method="post">
	<div id="bigbox">
		<div class="jtxx">
			<p class="jtxx_l left">姓名：</p>
			<input class="jtxx_r xb left" type="text" value="{pigcms{$now_user.truename}" id="true_name" name="truename">
		</div>
		
		<div class="jtxx">
			<p class="jtxx_l left">生日：</p>
			<input class="jtxx_r xb left rili" type="date" value="{pigcms{$now_user.birthday}" id="birthday" name="birthday">
		</div>  
	</div>
	<button class="btn" id="commitBtn">完成</button>
	</form>
	<div class="cover hide"></div>
	<div class="sure hide">
		<p class="sure_text">该手机号对应多张卡，您确定去并卡账户吗？</p>
		<div class="btn_box">
		<input class="anniu btn_sure" type="button" value="确定">
		<input class="anniu margin_l btn_cancel" type="button" value="取消"></div>
	</div>
</body>
</html>