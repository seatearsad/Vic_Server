<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>{pigcms{$config.seo_title}</title>
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
		<meta name="description" content="{pigcms{$config.seo_description}" />
		<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
		<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/ydyfx.css"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/index-slider.css"/>
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script src="{pigcms{$static_path}js/jquery.nav.js"></script>
		<script src="{pigcms{$static_path}js/navfix.js"></script>	
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}js/index.js"></script>	
		<script src="{pigcms{$static_path}js/index.slider.js"></script>	
		<if condition="$config['wap_redirect']">
			<script>
				if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){
					<if condition="$config['wap_redirect'] eq 1">
						window.location.href = './wap.php';
					<else/>
						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){
							window.location.href = './wap.php';
						}
					</if>
				}
			</script>
		</if>
		<!--[if IE 6]>
		<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
		<script type="text/javascript">
		   /* EXAMPLE */
		   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

		   /* string argument can be any CSS selector */
		   /* .png_bg example is unnecessary */
		   /* change it to what suits you! */
		</script>
		<script type="text/javascript">DD_belatedPNG.fix('*');</script>
		<style type="text/css"> 
			body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
			.category_list li:hover .bmbox {filter:alpha(opacity=50);}
			.gd_box{display:none;}
		</style>
		<![endif]-->
	</head>
	<body>
		<include file="Public:header_top"/>
		<div class="body"> 
			<div class="gd_box" style="top:1540px;margin-left:-80px;">
				<div id="gd_box">
					<div id="gd_box1">
						<div id="nav">
							<ul>
								<php>$autoI = 0;</php>
								<volist name="index_group_list" id="vo">
									<if condition="!empty($vo['group_list'])">
										<li <if condition="$autoI eq 0">class="current"</if>>
											<a class="f{pigcms{$i}" onClick="scrollToId('#f{pigcms{$i}');"><img src="{pigcms{$vo.cat_pic}" />
												<div class="scroll_{pigcms{$autoI%7+1}">{pigcms{$vo.cat_name}</div>
											</a>
										</li>
										<php>$autoI++;</php>
									</if>
								</volist>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<article>
				<div class="menu cf">
					<div class="menu_left">
						<div class="menu_left_top"><img src="{pigcms{$static_path}images/o2o1_27.png" /></div>
						<div class="list">
							<ul>
								<volist name="all_category_list" id="vo" key="k">
									<li>
										<div class="li_top cf">
											<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
											<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
										</div>
										<if condition="$vo['cat_count'] gt 1">
											<div class="li_bottom">
												<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
													<span><a href="{pigcms{$voo.url}" target="_blank">{pigcms{$voo.cat_name}</a></span>
												</volist>
											</div>
											<div class="list_txt">
												<p><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></p>
												<volist name="vo['category_list']" id="voo" key="j">
													<a class="<if condition="$voo['is_hot']">bribe</if>" href="{pigcms{$voo.url}" target="_blank">{pigcms{$voo.cat_name}</a>
												</volist>
											</div>
										</if>
									</li>
								</volist>
							</ul>
						</div>
					</div>
					<div class="menu_right cf">
						<div class="menu_right_top">
							<ul>
								<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
									<li class="ctur">
										<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
									</li>
								</pigcms:slider>
							</ul>
						</div>
						<div class="menu_right_bottom cf">
							<div class="mainbav">
								<div class="main_list cf">
									<div class="mainbav_left">
										<div class="mainbav_icon"><img src="{pigcms{$static_path}images/o2o1_35.png" /></div>
										<div class="mainbav_txt">热门{pigcms{$config.group_alias_name}</div>
									</div>
									<div class="mainbav_list">
										<volist name="hot_group_category" id="vo">
											<span><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></span>
										</volist>
									</div>
								</div>
								<div class="main_list cf">
									<div class="mainbav_left">
										<div class="mainbav_icon"><img src="{pigcms{$static_path}images/o2o1_38.png" /></div>
										<div class="mainbav_txt">全部区域</div>
									</div>
									<div class="mainbav_list">
										<volist name="all_area_list" id="vo">
											<span><a href="{pigcms{$vo.url}">{pigcms{$vo.area_name}</a></span>
										</volist>
									</div>
								</div>
								<div class="main_list cf">
									<div class="mainbav_left">
										<div class="mainbav_icon"><img src="{pigcms{$static_path}images/o2o1_42.png" /></div>
										<div class="mainbav_txt">热门商圈</div>
									</div>
									<div class="mainbav_list">
										<volist name="hot_circle_list" id="vo">
											<span><a href="{pigcms{$vo.url}">{pigcms{$vo.area_name}</a></span>
										</volist>
									</div>
								</div>
							</div>
							<div class="scroll cf">
								<div class="scroll_left <if condition="$now_activity">activityDiv</if>">
									<div class="scroll_top">
										<div class="scroll_top_left">
											<div class="scroll_top_left_img"><img src="{pigcms{$static_path}images/o2o1_47.png" /></div>
											<div class="scroll_top_txt">本期活动</div>
										</div>
										<if condition="$now_activity">
											<div class="scroll_top_right"> 
												<div class="scroll_top_txt">距离结束：</div>
												<div id="divdown1">
													<div class="scroll_top_right_img_shi" id="time_j">{pigcms{$time_array['j']}</div>
													<div class="scroll_top_txt">天</div>
													<div class="scroll_top_right_img" id="time_h">{pigcms{$time_array['h']}</div>
													<div class="scroll_top_txt">时</div>
													<div class="scroll_top_right_img" id="time_m">{pigcms{$time_array['m']}</div>
													<div class="scroll_top_txt">分</div>
													<div class="scroll_top_right_img" id="time_s">{pigcms{$time_array['s']}</div>
													<div class="scroll_top_txt">秒</div>
													<div class="scroll_top_right_img" id="time_mm" style="color:red;">00</div>
												</div>
												<div class="more"><a href="{pigcms{$activity_url}">更多 ></a></div>
											</div>
											<script>
												function format_time(time){
													if(time < 10){
														time = '0'+time;
													}
													return time;
												}
												$(function(){				
													var timeJDom = $('#time_j');
													var timeHDom = $('#time_h');
													var timeMDom = $('#time_m');
													var timeSDom = $('#time_s');
													var timeMMDom = $('#time_mm');
													var timer = setInterval(function(){
														var timeJ = parseInt(timeJDom.html());
														var timeH = parseInt(timeHDom.html());
														var timeM = parseInt(timeMDom.html());
														var timeS = parseInt(timeSDom.html());
														var timeMM = parseInt(timeMMDom.html());
														
														if(timeMM == 0){
															if(timeS == 0){
																if(timeM == 0){
																	if(timeH == 0){
																		if(timeJ == 0){
																			clearInterval(timer);
																			window.location.reload();
																		}else{
																			timeJDom.html(format_time(timeJ-1));
																		}
																		timeHDom.html('23');
																	}else{
																		timeHDom.html(format_time(timeH-1));
																	}
																	timeMDom.html('59');
																}else{
																	timeMDom.html(format_time(timeM-1));
																}
																timeSDom.html('59');
															}else{
																timeSDom.html(format_time(timeS-1));
															}
															timeMMDom.html('90');
														}else{
															timeMMDom.html(format_time(timeMM-1));
														}
													},10);
												});
											</script>
										</if>
									</div>
									<div id="scroll_box">
										<div class="div">
											<ul class="scroll_list">
												<if condition="$now_activity">
													<volist name="activity_list" id="vo">
														<li class="activity_li" <if condition="$i%2 eq 0">style="margin-right:0px;"</if>>
															<div class="scroll_article_left_top">
																<div class="scroll_article_left_top_banner"><a href="{pigcms{$vo.url}" target="_blank"><img src="{pigcms{$vo.index_pic}" alt="{pigcms{$vo.name}"/></a></div>
																<div class="scroll_article_article">
																	<div class="left">
																		<div class="scroll_article_article_title"><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a></div>
																		<div class="scroll_article_article_txt">{pigcms{$vo.title}</div>
																	</div>
																	<div class="right">
																		<div class="scroll_article_article_top"><span>{pigcms{$vo.part_count}</span> {pigcms{$config['score_name']}</div>
																		<div class="scroll_article_article_bottom">已参与 <span>{pigcms{$vo.part_count}</span></div>
																	</div>
																</div>
															</div>
														</li>
														<if condition="$i%2 eq 0"></ul><ul class="scroll_list" style="display:none;"></if>
													</volist>
												<else/>	
													<pigcms:adver cat_key="index_today_fav" limit="6" var_name="index_today_fav">
														<li>
															<div class="scroll_article_left_top">
																<a href="{pigcms{$vo.url}" target="_blank">
																	<img src="{pigcms{$vo.pic}" style="width:100%;height:100%;"/>
																</a>
															</div>
														</li>
													</pigcms:adver>
												</if>
											</ul>
										</div>
										<if condition="$now_activity && count($activity_list) gt 2">
											<div class="prevBtn"></div>
											<div class="nextBtn"></div>
										</if>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</article>
			<pigcms:near_shop limit="8"/>
			<if condition="$near_shop_list">
				<div class="category_top cf kuaidian">
					<div class="category_top_left">
						<ul>
							<li>			
								<div class="category_main_icon" style="background-color:#57BCAC;"><img src="{pigcms{$static_path}images/kuaidianico.png" style="width:18px;"/></if></div>
								<div class="category_main_txt">{pigcms{$config.meal_alias_name}推荐</div>
							</li>
						</ul>
					</div>
				</div>
				<article class="nearby cf">
					<div class="nearby_left">
						<div class="nearby_left_bottom">
							<div class="nearby_left_img"> 
								<div class="content__cell content__cell--slider" style="width:492px;">
									<div class="component-index-slider">
										<div class="index-slider ui-slider log-mod-viewed">
											<div class="pre-next">
												<a style="opacity:0;display:none;" href="javascript:;" hidefocus="true" class="mt-slider-previous sp-slide--previous"></a>
												<a style="opacity:0;display:none;" href="javascript:;" hidefocus="true" class="mt-slider-next sp-slide--next"></a>
											</div>
											<div class="head ccf">
												<ul class="trigger-container ui-slider__triggers mt-slider-trigger-container cf">
													<pigcms:adver cat_key="index_near_shop" limit="3" var_name="near_shop_adver_list">
														<li class="mt-slider-trigger <if condition='$i eq 1'>mt-slider-current-trigger</if>"></li>
													</pigcms:adver>
												</ul>
											</div>
											<ul class="content">
												<volist name="near_shop_adver_list" id="vo">
													<li class="cf" style="opacity:1;<if condition='$i neq 1'>display:none;</if>">
														<a href="{pigcms{$vo.url}" title="{pigcms{$vo.name}" target="_blank"><img src="{pigcms{$vo.pic}" /></a>
													</li>
												</volist>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="nearby_list">
						<ul>
							<volist name="near_shop_list" id="vo" offset="0" length="6">
								<li <if condition="$i gt 4">style="border-top:0px;"</if>>
									<div class="box">
										<div class="nearby_list_img">
											<a href="{pigcms{$vo.url}" target="_blank">
												<img class="meal_img lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$vo.image}" title="【{pigcms{$vo.area_name}】{pigcms{$vo.name}"/>
												<div class="bmbox">
													<div class="bmbox_list">
														<div class="bmbox_list_img"><img class="qrcode_img lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'meal','id'=>$vo['store_id']))}" /></div>
														<div class="bmbox_list_li">
															<ul>
																<li class="open_windows" data-url="{pigcms{$config.site_url}/merindex/{pigcms{$vo.mer_id}.html">商家</li>
																<li class="open_windows" data-url="{pigcms{$config.site_url}/meractivity/{pigcms{$vo.mer_id}.html">{pigcms{$config.group_alias_name}</li>
																<li class="open_windows" data-url="{pigcms{$config.site_url}/mergoods/{pigcms{$vo.mer_id}.html">{pigcms{$config.meal_alias_name}</li>
																<li class="open_windows" data-url="{pigcms{$config.site_url}/mermap/{pigcms{$vo.mer_id}.html">地图</li>
															</ul>
														</div>
													</div>
													<div class="bmbox_title"> 该商家有<span> {pigcms{$vo.fans_count} </span>个粉丝</div>
												</div>
											</a>
											<div class="name">【{pigcms{$vo.area_name}】{pigcms{$vo.name}</div>
											<div class="info">
												<div class="join">已售 <span>{pigcms{$vo.sale_count}</span></div>
											</div>
											<a href="{pigcms{$vo.url}" target="_blank">
												<button class="info_but">立即进店</button>
											</a>
										</div>
									</div>
								</li>
							</volist>
						</ul>
					</div>
				</article>
			</if>
			<div class="socll" style="width:100%;z-index:99">
				<php>$autoI=0;</php>
				<volist name="index_group_list" id="vo">
					<if condition="!empty($vo['group_list'])">
						<div class="category cf sa" id="f{pigcms{$i}">
							<div class="category_top cf">
								<div class="category_top_left">
									<ul>
										<li id="category_main_{pigcms{$autoI%7+1}">			
											<div class="category_main_icon"><if condition="$vo['cat_pic']"><img src="{pigcms{$vo.cat_pic}" style="width:22px;"/></if></div>
											<div class="category_main_txt">{pigcms{$vo.cat_name}</div>
										</li>
									</ul>
								</div>
								<div class="category_top_right">
									<if condition="count($vo['category_list']) gt 1">
										<volist name="vo['category_list']" id="voo" offset="0" length="6" key="j">
											<li><a target="_blank" href="{pigcms{$voo.url}" class="link">{pigcms{$voo.cat_name}</a></li>
										</volist>
									</if>
									<li class="more"><a target="_blank" href="{pigcms{$vo.url}" class="link">全部 ></a></li>
								</div>
							</div>
							<div class="category_list cf">
								<ul class="cf">
									<volist name="vo['group_list']" id="voo" offset="0" length="8" key="k">
										<li <if condition='$k%4 eq 0'>class="last--even"</if>>
											<div class="category_list_img">
												<a href="{pigcms{$voo.url}" target="_blank">
													<img alt="{pigcms{$voo.s_name}" class="deal_img lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$voo.list_pic}"/>
													<div class="bmbox">
														<div class="bmbox_list">
															<div class="bmbox_list_img"><img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'group','id'=>$voo['group_id']))}" /></div>
															<div class="bmbox_list_li">
																<ul class="cf">
																	<li class="open_windows" data-url="{pigcms{$config.site_url}/merindex/{pigcms{$voo.mer_id}.html">商家</li>
																	<li class="open_windows" data-url="{pigcms{$config.site_url}/meractivity/{pigcms{$voo.mer_id}.html">{pigcms{$config.group_alias_name}</li>
																	<li class="open_windows" data-url="{pigcms{$config.site_url}/mergoods/{pigcms{$voo.mer_id}.html">{pigcms{$config.meal_alias_name}</li>
																	<li class="open_windows" data-url="{pigcms{$config.site_url}/mermap/{pigcms{$voo.mer_id}.html">地图</li>
																</ul>
															</div>
														</div>
														<div class="bmbox_title"> 该商家有<span> {pigcms{$voo.fans_count} </span>个粉丝</div>
													</div>
												</a>
												<div class="datal" style="padding:5px 10px 5px;">
													<a href="{pigcms{$voo.url}" target="_blank">
														<div class="category_list_title">【{pigcms{$voo.prefix_title}】{pigcms{$voo.merchant_name}</div>
														<div class="category_list_description">{pigcms{$voo.group_name}</div>
													</a>
													<div class="deal-tile__detail cf">
														<span class="price">$<strong>{pigcms{$voo.price}</strong> </span>
														<span>门店价 ${pigcms{$voo.old_price}</span>
														<if condition="$voo['wx_cheap']">
															<div class="cheap">微信购买立减${pigcms{$voo.wx_cheap}</div>
														</if>														
													</div>
													<div class="extra-inner">
														<div class="sales">已售<strong class="num">{pigcms{$voo['sale_count']+$voo['virtual_num']}</strong></div >
														<div class="noreviews">
															<if condition="$voo['reply_count']">
																<a href="{pigcms{$voo.url}#anchor-reviews" target="_blank">
																	<div class="icon"><span style="width:{pigcms{$voo['score_mean']/5*100}%;" class="rate-stars"></span></div>
																	<span>{pigcms{$voo.reply_count}次评价</span>
																</a>
															<else/>
																<span>暂无评价</span>
															</if>
														</div >
													</div>
												</div>
											</div>
										</li>
									</volist>
								</ul>
							</div>
						</div>
						<php>$autoI++;</php>
					</if>
				</volist>
			</div>
        </div>
	<!--友情链接-->
	<if condition="!empty($flink_list)">
	<style type="text/css">.component-holy-reco {clear: both; margin: 0 auto;width: 1210px; position: relative;bottom: -98px;}.holy-reco{width:100%;margin:0 auto;padding-bottom:20px;_display:none}.holy-reco .tab-item {
    color: #666;}.holy-reco__content{border:1px solid #E8E8E8;padding:10px;background:#FFF}.holy-reco__content a{display:inline-block;color:#666;font-size:12px;padding:0 5px;line-height:16px;white-space:nowrap;width:85px;overflow:hidden;text-overflow:ellipsis}.nav-tabs--small .current {background: #ededed none repeat scroll 0 0;width:80px;text-align:center;padding:0 6px;float:left;cursor:pointer;}</style>
	<div class="component-holy-reco">
		<div class="J-holy-reco holy-reco">
			<div>
				<ul class="ccf cf nav-tabs--small">
					<li class="J-holy-reco__label current"><a href="javascript:void(0)" class="tab-item">友情链接</a></li>
				</ul>
			</div>
			<div class="J-holy-reco__content holy-reco__content">
				<volist name="flink_list" id="vo">
					<a href="{pigcms{$vo.url}" title="{pigcms{$vo.info}" target="_blank">{pigcms{$vo.name}</a>
				</volist>
			</div>
		</div>
	</div>
	</if>
	<!--友情链接--end-->
		<include file="Public:footer"/>
		<div id="fixedHeaderNav">
			<div class="nav cf">
				<div class="logo">
					<a href="{pigcms{$config.site_url}" title="{pigcms{$config.site_name}">
						<img  src="{pigcms{$config.site_logo}" />
					</a>
				</div>
				<div class="search">
					<form action="{pigcms{:U('Group/Search/index')}" method="post" group_action="{pigcms{:U('Group/Search/index')}" meal_action="{pigcms{:U('Meal/Search/index')}">
						<div class="formBox">
							<div class="form_sec">
								<div class="form_sec_txt group">{pigcms{$config.group_alias_name}</div>
								<div class="form_sec_txt1 meal">{pigcms{$config.meal_alias_name}</div>
							</div>
						</div>
						<div class="form_input">
							<input name="w" class="input" type="text" placeholder="请输入商品名称、地址等"/>
							<button value="" class="btnclick">搜索</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
