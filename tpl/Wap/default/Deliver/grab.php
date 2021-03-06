<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>配送员系统</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
	<script src="{pigcms{$static_path}js/convertor.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    #orders .dealcard-block-right {
			margin-left:1px;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 0;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }

	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
		#nav-dropdown{height: 1.7rem;}
		#filtercon select{height: 100%;line-height: normal;width:100%;}
		#filtercon{margin: 0 .15rem;}
.find_div {
margin: .15rem 0;
}
	#filtercon input{background-color: #fff;
		width: 100%;
		border: none;
		background: rgba(255, 255, 255, 0);
		outline-style: none;
		display: block;
		line-height: .28rem;
		height: 100%;
		font-size: .28rem;
		padding: 0
}
		#find_submit{
			position: absolute;
			right: 0rem;
			top: .15rem;
			width: 1.2rem;
			height: .7rem;;
			-webkit-box-sizing: border-box;
		}
 .dealcard-block-right li{
    font-size: .266rem;
font-weight: 400;
 }
.dealcard-block-right .dth{font-weight: bold;}
 .ulrightdiv{
	float: right;
	position: relative;
	top: -60px;
	margin-right: 15px;
	}
	dl.list .dd-padding{padding: .28rem 0.1rem;}
	.red{color:red;}
.top-btn-a a{color: #fff;margin-top: 10px;}
.top-btn-a .lb{margin-left: 20px;}
.top-btn-a .rb{float: right;margin-right: 20px;}
.dealcard-block-right{padding: 0 10px;}
#orders a{color: #333;}
#orders .td a{color: green;}
.find_type_div{
	position: absolute;
left: 0rem;
width: 1.7rem;
height: .7rem;
text-align: center;
background: white;
}
.find_txt_div{
vertical-align: middle;
position: relative;
margin-right: 1.3rem;
margin-left:1.8rem;
border-radius: .06rem;
border: 1px #CCC solid;
height: .7rem;
line-height: .7rem;
}
  .dealcard-block-right li.btm_li{
     margin-bottom: .18rem;
 }
 
 .deliver_list {
 	border-buttom: 0px solid #fff;
 	border-top:1px solid #e5e5e5;
 }
 .dealcard-block-right {
 	margin-buttom:10px;
 }
 .diver_list_get {
 	width: 1.5rem;
 	float: right;
 	height: 1.5rem;
 	background: #EE3968;
 	font-size: 0.8rem;
 	text-align: center;
 	border-radius: 50%;
 	margin-right:0.3rem;
 	cursor: pointer;
 }
 .diver_list_get em {
 	color: lightgoldenrodyellow;
 	font-style: normal;
 	line-height: 1.5rem;
 }
</style>
</head>
<body>
<!-- 	<header  class="navbar"> -->
<!-- 		<h1 class="nav-header">配送系统 - {pigcms{$config.site_name}</h1> -->
<!--     </header> -->
    <div id="content">
    	<dl class="list empty">
			<dd class="dealcard dd-padding" style="border-bottom:none;border-top:1px solid #ccc;text-align:center;">
			页面正在加载...
		</dd>
		<div style="clear:both"></div>
	</dl>
	</div>
	<include file="Deliver:footer"/>
</body>
<script>
$(function(){
	var DeliverListUrl = "{pigcms{:U('Deliver/grab')}";
	var ListBox = $("#content");
	var mark = 0;
	var Blng = 0;
	var Blat = 0;
	
	var template = '<dl class="list supply_{supply_id}" data-id="{supply_id}">\
						<dd class="dealcard dd-padding" style="border-bottom:none;border-top:1px solid #ccc;">\
								<div>\
								<ul class="dealcard-block-right" style="width:3.8rem; float:left; margin-bottom:0.3rem; margin-left:0;">\
									<li class="btm_li">\
										<span class="dth">订单编号：</span>\
										<span class="ttd">{real_orderid}</span>\
									</li>\
									<li class="btm_li">\
										<span class="dth">店铺名称：</span>\
										<span class="ttd">{store_name}</span>\
									</li>\
									<li class="btm_li">\
										<span class="dth">日期：</span>\
										<span class="ttd">{create_time}</span>\
									</li>\
									<li class="btm_li">\
										<span class="dth">顾客姓名：</span>\
										<span class="ttd">{name}</span>\
									</li>\
									<li class="btm_li"><span class="dth">顾客电话：</span><span class="td"><a  href="tel:{phone}" onclick="stopPropagation()">{phone}</a></span></li>\
									<li class="btm_li"><span class="dth">取货地址：</span>\
									<span style="color: red">{from_site}</span></li>\
									<li><span class="dth">收货地址：</span>\
									<span style="color: red">{aim_site}</span></li>\
									<li><span class="dth">支付方式：</span>\
									<span style="color: green">{pay_method}</span></li>\
									<li><span class="dth">应收取现金：</span>\
									<span style="color: red">{deliver_cash}</span></li>\
									<li><span class="dth">预定时间：</span>\
									<span class="ttd">{appoint_time}</span></li>\
									<li><span class="dth">客户备注信息：</span>\
									<span class="ttd">{note}</span></li>\
								</ul>\
								<span class="diver_list_get grab" data-id="{supply_id}">\
									<em>抢</em>\
								</span>\
							</div>\
						</dd>\
					<dl>\
					<div style="clear:both"></div>';
	var none_template = '<dl class="list empty">\
								<dd class="dealcard dd-padding" style="border-bottom:none;border-top:1px solid #ccc;text-align:center;">\
								暂无订单需要配送，休息休息吧^_^\
							</dd>\
							<div style="clear:both"></div>\
						</dl>';

	var timer = setInterval(getPoint, 10000);

	function operate(data) {
		ListBox.find(".empty").remove();
		if (null === data) {
			ListBox.html(none_template);
		}
		var supplyIds = [];
		var add = [];
		$.each(data, function(n, d){
			//判断页面中是否存在
			if (! ListBox.children("dl").hasClass("supply_"+d.supply_id)) {
				add.push(d);
			}
			supplyIds.push(d.supply_id);
		});
		//移除页面中已经不存在的订单
		$.each(ListBox.children("dl"), function(n, v) {
			var tagId = $(v).attr("data-id");

			if ($.inArray(tagId, supplyIds) < 0) {
				$(".supply_"+tagId).remove();
			}
		});

		for (var i=0,item; item=add[i++];) {
			var html = template;
			for (var key in item) {
				html = html.replace(new RegExp('{'+key+'}',"gm"), item[key]);
			}
			ListBox.prepend(html);
		}
		$(".grab").unbind();
		$(".grab").bind("click", grab);
	}
	getList(116.372612, 39.8185234);
	function getList(lng, lat) {
		$.get(DeliverListUrl+"&lng="+lng+"&lat="+lat, function(json){
			if (!json.status) {
				alert(json.msg);
			}
			operate(json.list);
		}, 'json');
	}

	function grab(e) {
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		$.post(DeliverListUrl, "supply_id="+supply_id, function(json){
			mark = 0;
			if (json.status) {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'抢单成功，快去看看吧~',btn: ['确定'],end:function(){}});
			} else {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:json.info,btn: ['确定'],end:function(){}});
			}
			$(".supply_"+supply_id).remove();
		});
	}

	$(".grab").bind("click", grab);

	function getPoint() {
		try {
			navigator.geolocation.getCurrentPosition(function(position){
				var lng = position.coords.longitude;
				var lat = position.coords.latitude;
				var point = {};
				point.lng = lng;
				point.lat = lat;
				BMap.Convertor.translate(point, 0, function(Bpoint){
					Blng = Bpoint.lng;
					Blat = Bpoint.lat;
					getList(Blng, Blat);
				});
			});
		} catch(e) {
			alert("get data error");
		}
	}
	
	//检查浏览器是否支持地理位置获取 
	if (navigator.geolocation){
		getPoint();
	}else{
		clearInterval(timer);
		layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'定位失败,用户浏览器不支持或已禁用位置获取权限',btn: ['确定'],end:function(){ window.close(); }});
	}
});
</script>
</html>