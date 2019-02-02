<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$now_village.village_name}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var pay_type = "{pigcms{$pay_type}",pay_money = {pigcms{$pay_money};
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_pay.js?210" charset="utf-8"></script>
	</head>
	<body>
		<header class="pageSliderHide"><div id="backBtn"></div>{pigcms{$pay_name}</header>
		<div id="container">
			<div id="scroller">
				<div id="pullDown" style="background-color:#04BE02;color:white;">
					<span class="pullDownLabel" style="padding-left:0px;"><i class="yesLightIcon" style="margin-right:10px;vertical-align:middle;"></i>{pigcms{$now_village.village_name} 在线快捷缴费</span>
				</div>
				<section class="query-container">
					<div class="query_div {pigcms{$pay_type}_ico"></div>
					<div class="area_tips">{pigcms{$now_user_info.address}</div>
					<if condition="$pay_type eq 'custom'">
						<div class="area_input" style="margin-top:15px;">
							<input type="text" class="recharge_txt" id="recharge_txt" placeholder="请填写缴费的事项"/>
							<span class="nametip">缴费事项</span>
						</div>
						<div class="area_input" style="margin-top:15px;">
							<input type="tel" class="recharge_txt" id="recharge_money" placeholder="缴纳的费用(元)"/>
							<span class="nametip">缴费金额</span>
						</div>
					<else/>
						<div class="area_input" style="margin-top:15px;">
							<input type="tel" class="recharge_txt" id="recharge_money" placeholder="您需缴纳的费用" value="${pigcms{$pay_money}" readonly="readonly"/>
							<span class="nametip"></span>
						</div>
					</if>
					<div class="area_btn"><input type="button" id="recharge_btn" value="缴费"/></div>
				</section>
				<if condition="$order_list">
					<section class="villageBox newsBox query-list" style="width:90%;margin:30px auto 10px;">
						<div class="headBox">帐单列表<!--div class="right link-url" data-url="/wap.php?g=Wap&amp;c=House&amp;a=village_newslist&amp;village_id=1"></div--></div>
						<dl>
							<volist name="order_list" id="vo">
								<dd>
									<div>{pigcms{$vo.desc}</div>
									<span class="right">{pigcms{$vo.ydate}年{pigcms{$vo.mdate}月</span>
								</dd>
							</volist>
						</dl>
					</section>
				</if>
				<div id="pullUp" style="bottom:-60px;">
					<img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
				</div>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>