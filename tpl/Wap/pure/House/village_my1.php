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
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>个人中心</header>
    </if>
		<div id="container">
			<div id="scroller" class="village_my">
				<nav>
					<section class="myInfoSection <if condition='defined("IS_INDEP_HOUSE")'>link-url</if>" id="myInfoSection"  <if condition='defined("IS_INDEP_HOUSE")'>data-url="{pigcms{:U('My/myinfo')}"</if> >
						<img class="lazy_img" src="<if condition="$now_user['avatar']">{pigcms{$now_user.avatar}<else/>{pigcms{$static_path}images/pic-default.png</if>"/>
						<h2 style="font-size:14px;line-height:20px;height:20px;padding-top:0px;">业主名称：{pigcms{$now_user_info.name} </h2>
						<p style="line-height:20px;height:20px;"><span style="color:#999;">物业编号：{pigcms{$now_user_info.usernum}</span></p>
						<p style="line-height:20px;height:20px;"><span style="color:#666;">地址：{pigcms{$now_user_info.address}</span></p>
					</section>
				</nav>
				<nav>
					<if condition="!$_SESSION['now_village_bind']['flag']">
						<section class="link-url" data-url="{pigcms{:U('House/village_my_bind_family_add',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#FF4364;">绑</span><p>绑定家属</p></section>
					</if>
					<section class="link-url" data-url="{pigcms{:U('House/village_my_pay',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#0092DE;">费</span><p>小区缴费</p></section>
					<section class="link-url" data-url="{pigcms{:U('House/village_my_repair',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#EAAD0D;">修</span><p>在线报修</p></section>
					<section class="link-url" data-url="{pigcms{:U('House/village_my_utilities',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#EA0DDF;">报</span><p>水电煤上报</p></section>
				</nav>
				
				
				
				<if condition='defined("IS_INDEP_HOUSE")'>
				<nav>
					<section class="link-url" data-url="{pigcms{:U('My/group_order_list')}"><span style="background-color:#FF4364;">团</span><p>团购订单</p></section>
					<section class="link-url" data-url="{pigcms{:U('My/appoint_order_list')}"><span style="background-color:#EAAD0D;">预</span><p>预约订单</p></section>
					<section class="link-url" data-url="{pigcms{:U('My/meal_order_list')}"><span style="background-color:#EA0DDF;">外</span><p>外卖订单</p></section>
				</nav>
				</if>
				
				
				
				
				
				<nav>
					<section class="link-url" data-url="{pigcms{:U('House/village_my_paylists',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#F5716E;">订</span><p>缴费订单列表</p></section>
					<section class="link-url" data-url="{pigcms{:U('House/village_my_repairlists',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#EAAD0D;">修</span><p>在线报修列表</p></section>
					<section class="link-url" data-url="{pigcms{:U('House/village_my_utilitieslists',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#EA0DDF;">报</span><p>水电煤上报列表</p></section>
                    <if condition="!$_SESSION['now_village_bind']['flag']"><section class="link-url" data-url="{pigcms{:U('House/village_my_bind_family_list',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#FF4364;">家</span><p>绑定家属列表</p></section></if>
                    <section class="link-url" data-url="{pigcms{:U('Library/express_service_list',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#84AF9B;">递</span><p>快递代收</p></section>
                    <section class="link-url" data-url="{pigcms{:U('Library/visitor_list',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#AEDD81;">访</span><p>访客登记</p></section>
				</nav>
                
				<nav>
					<section class="link-url" data-url="{pigcms{:U('House/village_my_suggest',array('village_id'=>$now_village['village_id']))}"><span style="background-color:#0092DE;">议</span><p>投诉建议</p></section>
				</nav>
                
                <nav>
		<section class="link-url" data-url="{pigcms{:U('Login/logout')}">
			<p style="color:#FF658E; padding-left:0">退出登录</p>
		</section>
	</nav>
                
                
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
				
			</div>
		</div>
		<include file="House:footer"/>
		{pigcms{$shareScript}
	</body>
</html>