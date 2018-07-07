<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="keywords" content="{pigcms{$config.seo_keywords}">
	<meta name="description" content="{pigcms{$config.seo_description}">
	<title>查看地址</title>
</head>
<body class=" hIphone" style="padding-bottom: initial;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_5e96991.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/address_9d295cd.css">
<link href="{pigcms{$static_path}bbs/css/style.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}images/hm.gif" width="0" height="0" style="display:block">
<div id="wrapper" class="">
    <div id="fis_elm__2">
        <div id="address-widget-map" class="address-widget-map">
            <div class="address-map-nav" style="background-color:#06c1ae;">
                <div class="left-slogan" style="margin-top:18px;"><a class="left-arrow icon-arrow-left2" style="color:#fff;" data-node="navBack" href="javascript:history.go(-1);"></a></div>
                <div class="center-title" style="margin-top:8px"><i class="icon-location" data-node="icon" style="margin-top:6px;"></i>
                    <div class="ui-suggestion-mask">
                        <input type="text" placeholder="直接输入定位您的地址" id="se-input-wd" autocomplete="off">
                        <div class="ui-suggestion-quickdel"></div>
                    </div>
                </div>
                <div class="his-postion" data-node="historypos" style="">
                    <div class="ui-suggestion" id="ui-suggestion-0" style="top: 0px; left: 0px; position: relative;">
                        <div class="ui-suggestion-content" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></div>
                        <div class="ui-suggestion-button"><span class="ui-suggestion-clear" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);">清除历史记录</span><span class="ui-suggestion-close" style="-webkit-tap-highlight-color: rgba(255, 255, 255, 0);"></span></div>
                    </div>
                </div>
            </div>
            <div id="fis_elm__3">
                <!--<div class="map">
                	<div class="MapHolder" id="cmmap"></div>
					<div class="dot" style="display:block;"></div>
                </div>-->
                <div class="mapaddress" data-node="mapaddress">
                    <ul id="addressShow"> </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="fis_elm__4"></div>
<div class="global-mask layout"></div>
<div id="fis_elm__6"></div>
<div id="fis_elm__7"></div>

</body>
<script type="text/javascript">
var address = '{pigcms{$address}';
var type = '{pigcms{$type}';
var package_id = '{pigcms{$package_id}';
$(document).ready(function(){
	var inputWdTimer = 0;
	$("#se-input-wd").bind('input', function(e){
		var address = $.trim($('#se-input-wd').val());
		if(address.length>0 && address !== '请输入小区、大厦或学校'){
			$('#addressShow').empty();
			clearTimeout(inputWdTimer);
			inputWdTimer = setTimeout(function(){
				$.get('index.php?g=Index&c=Map&a=suggestion', {query:address}, function(data){
					if(data.status == 1){
						getAdress(data.result);
					} else {
						//alert(data.result);return false;
					}
				});
			},500);
		}
	});

	$('#addressShow').delegate("li","click",function(){
		var adress = $(this).attr("address");
		var lng = $(this).attr("lng");
		var lat = $(this).attr("lat");
		if(type == 1 || type == 3){
			sessionStorage.setItem("package_start", adress);
			sessionStorage.setItem("package_start_long", lng);
			sessionStorage.setItem("package_start_lat", lat);
		}else if(type == 2 || type == 4){
			sessionStorage.setItem("package_end", adress);
			sessionStorage.setItem("package_end_long", lng);
			sessionStorage.setItem("package_end_lat", lat);
		}
		if(type==1 || type==2){
			location.href = "{pigcms{:U('add')}";
		}else{
			location.href = "{pigcms{:U('eidt',array('package_id'=>$package_id))}";
		}
	});
});
function getAdress(re){
	$('#addressShow').html('');
	var addressHtml = '';
	for(var i=0;i<re.length;i++){
		addressHtml += '<li lng="'+re[i]['long']+'" lat="'+re[i]['lat']+'" sug_address="'+re[i]['name']+'" address="'+re[i]['address']+'" class="addresslist">';
		addressHtml += '<div class="mapaddress-title"> <span class="icon-location" data-node="icon"></span> <span class="recommend"> '+(i == 0 ? '[推荐位置]' : '')+'   '+re[i]['name']+' </span> </div>';
		addressHtml += '<div class="mapaddress-body"> '+re[i]['address']+' </div>';
		addressHtml += '</li>';
	}
	$('#addressShow').append(addressHtml);
}
</script>
</html>