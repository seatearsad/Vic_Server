<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{:L('_B_PURE_MY_46_')} | {pigcms{:L('_VIC_NAME_')}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}coupon/css/card_new.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/common.js"></script>
<!--script src="{pigcms{$static_path}js/category.js"></script-->
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
    <include file="Public:facebook"/>
</head>
<body id="settings" class="has-order-nav" style="position:static;">
<include file="Public:header_top"/>
 <div class="body pg-buy-process" style="margin: 0 auto">
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top">{pigcms{:L('_ALL_CLASSIF_')}</div>
					<div class="list">
						<!--ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul-->
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{:lang_substr($vo['name'],C('DEFAULT_LANG'))}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<include file="Public:scroll_msg"/>
		<div id="bdw" class="Coupon">
			<div id="bd" class="cf">
				<include file="Public:sidebar"/>
				<div id="content" class="coupons-box">
					<div class="mainbox mine" style="background-color: #f4f4f4">
						<ul class="filter cf">
							<li class="current"><a href="{pigcms{:U('Coupon/index')}">{pigcms{:L('_B_PURE_MY_46_')}</a></li>
						</ul>
						<div class="address-div">
							<div class="table-section">
                                <div class="swiper-slide">
                                    <ul class="end_ul">
                                        <li>
                                            <if condition="$coupon_list[0]">
                                                <volist name="coupon_list[0]" id="vo">
                                                    <dl class="Muse">
                                                        <dd>
                                                            <div class="Coupon_top clr">
                                                                <div class="fl">
                                                                    <div class="fltop">
                                                                        <i>$</i><em>{pigcms{$vo.discount}</em>
                                                                    </div>
                                                                    <div class="flend" style="line-height: 14px;">
                                                                        <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                                                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                                                                        <php>}else{</php>
                                                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['order_money'])}
                                                                        <php>}</php>
                                                                    </div>
                                                                </div>
                                                                <div class="fr">
                                                                    <h2>{pigcms{$vo.name} <php>if($_GET['coupon_type']=='mer'){</php>({pigcms{$vo.merchant})<php>}</php></h2>
                                                                    <p>&nbsp;<!--使用平台：{pigcms{$vo.platform}--></p>
                                                                    <p>&nbsp;<!--使用类别：<php>if($vo['cate_name']=='all'){echo "所有";}else{</php>{pigcms{$vo.cate_name}<php>}</php>--></p>
                                                                </div>
                                                            </div>

                                                            <div class="Coupon_end">
                                                                <div class="Coupon_x">
                                                                    <i>{pigcms{$vo.start_time|date='Y.m.d',###}--{pigcms{$vo.end_time|date='Y.m.d',###}</i>
                                                                    <php>if($_GET['coupon_type']=='system'){</php><a href="{pigcms{$vo.url}"><em>{pigcms{:L('_IMMEDIATE_USE_')}</em></a><php>}</php>
                                                                </div>
                                                                <div class="Coupon_sm">
                                                                    <span class="on">{pigcms{:L('_INSTRUCTIONS_TXT_')}</span>
                                                                    <div class="Coupon_text overflow">{pigcms{$vo.des}</div>
                                                                </div>
                                                            </div>
                                                            <span class="several">{pigcms{$vo.get_num}</span>
                                                            <i class="bj"></i>
                                                        </dd>
                                                    </dl>
                                                </volist>
                                                <php>if($_GET['coupon_type']=='mer'){</php><div class="more"><a href="{pigcms{:C('config.site_url')}/wap.php">快去购买吧<span></span></a></div>	<php>}</php>
                                            </if>
                                            <php>if($_GET['coupon_type']=='system'){</php>
                                            <div class="more"><!--a href="{pigcms{:U('Wap/Systemcoupon/index')}">更多好券，去领券中心看看<span></span></a--></div>
                                            <php>}else{</php>

                                            <php>}</php>
                                        </li>
                                    </ul>
                                </div>
							</div>
						</div>
						{pigcms{$pagebar}
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
     <script>
         $(".Coupon_sm").each(function(){
             $(this).find("span").click(function(){
                 if($(this).hasClass("on")){
                     $(this).removeClass("on")
                     $(this).siblings(".Coupon_text").removeClass("overflow");
                     $(this).parents("dd").siblings().find(".Coupon_sm span").addClass("on");
                     $(this).parents("dd").siblings().find(".Coupon_sm .Coupon_text").addClass("overflow");
                 }else{
                     $(this).addClass("on")
                     $(this).siblings(".Coupon_text").addClass("overflow");
                 }

             })
         })
     </script>
	<include file="Public:footer"/>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places" async defer></script>
</body>
</html>
