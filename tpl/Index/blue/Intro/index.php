<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>{pigcms{$now_link.name} - {pigcms{:L('_VIC_NAME_')}</title>
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
		<meta name="description" content="{pigcms{$config.seo_description}" />
		<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
		<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
		<link href="{pigcms{$static_path}css/intro.css"  rel="stylesheet"  type="text/css" />
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script type="text/javascript">
	      var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	    </script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<style type="text/css">
                *{
                    margin: 0px;
                    box-sizing: border-box;
                    font-family: Helvetica;
                    -moz-osx-font-smoothing: grayscale;
                }
                body{
                    min-width: 1024px;
                    background-color: #F5F5F5;
                    color: #3f3f3f;
                }
                .main .content{
                    width: 100%;
                }
                .main .content .tit{
                    border: 0;
                }
		</style>
	</head>
	<body>
    <include file="Public:header"/>
		<div>
			<!--article>
				<div class="menu cf">
					<div class="menu_left hide">
						<div class="menu_left_top">{pigcms{:L('_ALL_CLASSIF_')}</div>
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
													<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
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
										<a href="{pigcms{$vo.url}">{pigcms{:lang_substr($vo['name'],C('DEFAULT_LANG'))}</a>
									</li>
								</pigcms:slider>
							</ul>
						</div>
					</div>
				</div>
			</article-->
			<div class="w main">
				<div class="margin_b6">
					<!--a href="{pigcms{$config.site_url}">首页</a><span>&gt;</span>&nbsp;关于我们<span>&gt;</span>&nbsp;{pigcms{$now_link.name}</div-->
					<!--div class="left">
						<h2></h2>
						<ul class="conact_side">
							<pigcms:footer_link var_name="footer_link_list">
								<li><a href="{pigcms{$vo.url}" <if condition="$vo['out_link']">target="_blank"</if>>{pigcms{$vo.name}</a></li>
							</pigcms:footer_link>
						</ul>
						<div class="borderlr"></div>
						<div class="corner_b">
							<div class="corner_bl"></div>
							<div class="corner_br"></div>
						</div>
					</div-->
					<div class="right">
						<div class="corner_t">
							<div class="corner_tl"></div>
							<div class="corner_tr"></div>
						</div>
						<div class="corner_c"></div>
						<div class="content">
							<h1 class="tit">{pigcms{$now_link.title}</h1>
							{pigcms{$now_link.content}
						</div>
						<!--[if !ie]>内容 结束<![endif]-->
						<div class="corner_b"><div class="corner_bl"></div><div class="corner_br"></div></div>
						<!--[if !ie]>help_tips 开始<![endif]-->
						<!--[if !ie]>help_tips 结束<![endif]-->
					</div>
					<!--[if !ie]>right 结束<![endif]-->
				</div>
        </div>
		<include file="Public:footer"/>
	</body>
</html>
