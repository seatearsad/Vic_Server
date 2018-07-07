<div class="head clearfix">
	<div class="fl">
		<a class="logo" alt="{pigcms{$config.appoint_site_name}" href="{pigcms{$config.appoint_site_url}" style="background-image:url(<if condition="$config['appoint_site_logo']">{pigcms{$config.appoint_site_logo}<else/>{pigcms{$config.site_logo}</if>);background-size:100%;"></a>
	</div>
	<div class="site fl">
		<p class="site-t">
			<strong>{pigcms{$now_select_city.area_name}</strong> 
		</p>
		<if condition='$config["many_city"]'>
		<p class="site-c">
			[ <span><a href="javascript:void(0)" onclick="changeCity()">切换城市</a></span> ]
		</p>
		</if>
	</div>
	<script>
		function changeCity(){
			window.location.href = "{pigcms{:U('Changecity/index')}&referer="+encodeURIComponent(location.href);
		}
	</script>
	<div class="head-r fr">
		<div class="tbar-login sty000 fr clearfix"></div>
		<div class="nav sty000 fr clearfix">
			<pigcms:slider cat_key="web_yue_slider" limit="10" var_name="web_yue_slider" reverse="true">
				<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
			</pigcms:slider>
		</div>
	</div>
	<form id="form1" method="post" action="#">
		<input type="hidden" id="backCityUrl" name="backCityUrl" value="#">
	</form>
</div>