<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{pigcms{$thisCard.cardname}优惠券</title>
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="{pigcms{$static_path}card/style/style.css" rel="stylesheet" type="text/css">
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<script src="/static/js/alert.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}card/js/accordian.pack.js" type="text/javascript"></script>
<style>
header {
    margin: 0 10px;
    position: relative;
    z-index: 4;
}
header ul {
	margin:0 -1px;
	border: 1px solid #179f00;
	border-radius: 3px;
	width: 100%;
	overflow: hidden;
}
header ul li a.bl {
    border-left: 0.5px solid #0b8e00;
}
header ul li a.on {
    background-color:#179f00;
    color: #ffffff;
    background-image: -moz-linear-gradient(center bottom , #179f00 0%, #5dd300 100%);
}
header ul li a {
    color: #0b8e00;
    display: block;
    font-size: 15px;
    height: 28px;
    line-height: 28px;
    text-align: center;
    width:50%;
    float:left;
}
.pic{width:100%;margin-bottom:10px;}
.over{background:#aaa;border:1px solid #aaa;box-shadow: 0 1px 0 #cccccc inset, 0 1px 2px rgba(0, 0, 0, 0.5);}
.window .title{background-image: linear-gradient(#179f00, #179f00);}
</style>
</head>
<body id="cardnews" onLoad="new Accordian('basic-accordian',5,'header_highlight');" class="mode_webapp">
<div class="qiandaobanner">
	<a href="javascript:history.go(-1);"><img src="{pigcms{$thisCard.vip}" ></a>
</div>
<header>
	<nav id="nav_1" class="p_10">
		<ul class="box">
			<li><a href="wap.php?g=Wap&c=Card&a=my_coupon&token={pigcms{$token}&cardid={pigcms{$thisCard.id}&type=2" class="<if condition="$type eq 2">on</if>">优惠券</a></li>
			<li><a href="wap.php?g=Wap&c=Card&a=my_coupon&token={pigcms{$token}&cardid={pigcms{$thisCard.id}&type=3" class="<if condition="$type eq 3">on</if>">礼品券</a></li>
		</ul>
	</nav>
</header>
<div id="basic-accordian">
<volist name="list" id="item">
<div id="test{pigcms{$item.id}-header" class="accordion_headings  <?php if ($item['id']==$firstItemID){?>header_highlight<?php } ?>">
<div class="tab  <if condition="$type eq 3">gift<else/>coupon</if>">
<span class="title">
{pigcms{$item.title}(<if condition="$type eq 3">{pigcms{$item.integral}{pigcms{$config['score_name']}兑换<else/>可领取{pigcms{$item.count}张</if>)
<p>有效期至{pigcms{$item.enddate|date='Y年m月d日',###}</p>
</span>
</div>
<div id="test{pigcms{$item.id}-content">
<div class="accordion_child">
<div id="queren{pigcms{$item.id}">
	<img src="{pigcms{$item.pic}" class="pic">
	<a  class="submit <if condition="$item.count lt 1">over</if>" href="javascript:void(0)" onclick="payformsubmit({pigcms{$item.id})"><if condition="$item.count lt 1">已经领光了<else/>点击领取</if></a>	
</div>
<ul style="min-height:230px;">
<b>领取要求：</b>
<if condition="$type eq 3">
<li>领取礼品券要消耗<span class="max_count">{pigcms{$item.integral}</span>点{pigcms{$config['score_name']}。</li>
<else/>
<li>每人最多领取<span class="max_count">{pigcms{$item.people}</span>张，您已经领取了<span class="get_count">{pigcms{$item.get_count}</span>张</li>
</if>
<b>详情说明：</b>
<p>{pigcms{$item.info}</p></ul>
<div style="clear:both;height:20px;"></div>
</div> 
<div style="clear:both;height:20px;"></div>
</div>
</div>

</volist>
</div>
<script>
var jQ = jQuery.noConflict();

function payformsubmit(itemid){
	var submitData = {
		coupon_id:itemid,
		cardid: {pigcms{$thisCard.id},
		type: {pigcms{$type},
		cat:3,
	};

	jQ.post('/wap.php?g=Wap&c=Card&a=action_myCoupon&token={pigcms{$token}', submitData,function(data) {
		if(data.err == 0){
			jQ('.count').html(jQ('.count').html()-1);
		}
		alert(data.info);
	}, "json");


}




</script>
<include file="Card:cardFooter"/>
<include file="Card:share"/>
</body>
</html>