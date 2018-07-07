<!doctype html>
<html>
<head lang="en">
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="address=no">
<title>{pigcms{$now_merchant.name}的门店列表</title>
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
<style type="text/css">
.TravelTC{ position: fixed; left: 0px; top:0px; width: 100%;height: 100%;background: url({pigcms{$static_path}/images/xtmt_03.png); z-index: 99999; display: none;}
.Choosetravel{ position: fixed; z-index: 999999; bottom: 0px; left: 0px; background: #fff; width: 100%; display: none; }
</style>
<script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
</head>
<body>
	<div id="bigbox">
		<volist name="store_list" id="vo">
			<div class="jtxx_dz">
				<a style="color:#000" href="{pigcms{$vo.url}">
				<div class="address_box left">
					<div class="add_box_t">
						<span class="margin_r_md">{pigcms{$vo.name}</span>
						<span class="margin_r_md haoma">{pigcms{$vo.phone}</span>
					</div>
					<p class="add_color">{pigcms{$vo.area_name}{pigcms{$vo.adress}</p>
				</div>
				</a>
				<a class="mobile right" onclick="phone('{pigcms{$vo.phone}')"></a>
			</div>
		</volist>
	</div>
	<div class="TravelTC"></div>
	<section class="Choosetravel" style="margin-bottom:50px;">
		<div style="margin-left:20px;font-size:18px;padding:10px;">拨打电话</div>
		<div id="phones"></div>
		<div id="cancel" style="border-top:1px solid #ccc;padding:10px;text-align:center;color:#FF6634;">取消</div>
	</section>
	<script type="text/javascript">
		function phone(srt_phone){
			var list = '';
			var strs= new Array();
			strs=srt_phone.split(" ");
			for (i=0;i<strs.length ;i++){
				list	+=	'<a style="text-decoration:none" href="tel:'+strs[i]+'"><div style="border-top:1px solid #ccc;padding:10px;text-align:center;color:#06c1ae;">'+strs[i]+'</div></a>';
			}
			$("#phones").html(list);
			$(".TravelTC").show();
			$(".Choosetravel").show();
		}
		$("#cancel").click(function(){
			$(".TravelTC").hide();
			$(".Choosetravel").hide();
		})
	</script>
</body>
</html>