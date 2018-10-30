<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>{pigcms{$now_link.name}</title>
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
				body{behavior:url("{pigcms{$static_path}css/csshover.htc"); 
				}
				.category_list li:hover .bmbox {
		filter:alpha(opacity=50);
			 
					}
		  .gd_box{	display: none;}
		</style>
		<![endif]-->
	</head>
	<body>
		<div style="100%">
			<div class="w main">
				<div id="Position" class="margin_b6" style="font-size: 30px;line-height: 30px;">
					<!--a href="{pigcms{$config.site_url}">首页</a><span>&gt;</span>&nbsp;关于我们<span>&gt;</span>&nbsp;{pigcms{$now_link.name}</div-->
					<div class="right" style="line-height: 40px;">
						<div class="corner_t">
							<div class="corner_tl"></div>
							<div class="corner_tr"></div>
						</div>
						<div class="corner_c"></div>
						<div class="content" style="width: 90%;padding: 20px 30px;">
							<h1 class="tit" style="font-size: 50px;">{pigcms{$now_link.title}</h1>
							{pigcms{$now_link.content}

                            <h1 class="tit" style="font-size: 50px;">
                                <if condition="$now_link['id'] eq 5">
                                    <a href="./2.html?app=1" style="font-size: 50px;color: #0c68cf">Privacy Policy</a>
                                <else />
                                    <a href="./5.html?app=1" style="font-size: 50px;color: #0c68cf">Terms of Use</a>
                                </if>
                            </h1>
						</div>
						<!--[if !ie]>内容 结束<![endif]-->
						<div class="corner_b"><div class="corner_bl"></div><div class="corner_br"></div></div>
						<!--[if !ie]>help_tips 开始<![endif]-->
						<!--[if !ie]>help_tips 结束<![endif]-->
					</div>
					<!--[if !ie]>right 结束<![endif]-->
				</div>
        </div>
	</body>
</html>
