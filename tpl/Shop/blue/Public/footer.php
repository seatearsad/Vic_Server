<footer>
	<div class="footer1">
        <div class="footer_list cf">
            <ul class="cf">
                <pigcms:footer_link var_name="footer_link_list">
                    <li><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a><if condition="$i neq count($footer_link_list)"></if></li>
                </pigcms:footer_link>
            </ul>
        </div>
		<div class="footer_txt cf">
			<div class="footer_txt" style="width: 100%;">{pigcms{:nl2br($config['site_show_footer'],'<a>')}</div>
		</div>
        <div class="footer_list cf">
            <ul class="ul_left">
                <li class="li_c">{pigcms{:L('_BUSINESS_TIME_')}</li>
                <li class="li_c">11：00 am - 1：00 am</li>
            </ul>
        </div>
	</div>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-123655278-1"></script>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '480621099087432');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=480621099087432&ev=PageView&noscript=1" />
    </noscript>
    <!-- End Facebook Pixel Code -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-123655278-1');
    </script>
</footer>
<div style="display:none;">{pigcms{$config.site_footer}</div>
<!--悬浮框-->
<!--if condition="MODULE_NAME neq 'Login'">
	<div class="rightsead">
		<ul>
			<li>
				<a href="javascript:void(0)" class="wechat">
					<img src="{pigcms{$static_path}images/l02.png" width="47" height="49" class="shows"/>
					<img src="{pigcms{$static_path}images/a.png" width="57" height="49" class="hides"/>
					<img src="{pigcms{$config.wechat_qrcode}" width="145" class="qrcode"/>
				</a>
			</li>
			<if condition="$config['site_qq']">
				<li>
					<a href="http://wpa.qq.com/msgrd?v=3&uin={pigcms{$config.site_qq}&site=qq&menu=yes" target="_blank" class="qq">
						<div class="hides qq_div">
							<div class="hides p1"><img src="{pigcms{$static_path}images/ll04.png"/></div>
							<div class="hides p2"><span style="color:#FFF;font-size:13px">{pigcms{$config.site_qq}</span></div>
						</div>
						<img src="{pigcms{$static_path}images/l04.png" width="47" height="49" class="shows"/>
					</a>
				</li>
			</if>
			<if condition="$config['site_phone']">
				<li>
					<a href="javascript:void(0)" class="tel">
						<div class="hides tel_div">
							<div class="hides p1"><img src="{pigcms{$static_path}images/ll05.png"/></div>
							<div class="hides p3"><span style="color:#FFF;font-size:12px">{pigcms{$config.site_phone}</span></div>
						</div>
						<img src="{pigcms{$static_path}images/l05.png" width="47" height="49" class="shows"/>
					</a>
				</li>
			</if>
			<li>
				<a class="top_btn">
					<div class="hides btn_div">
						<img src="{pigcms{$static_path}images/ll06.png" width="161" height="49"/>
					</div>
					<img src="{pigcms{$static_path}images/l06.png" width="47" height="49" class="shows"/>
				</a>
			</li>
		</ul>
	</div>
</if-->
<!--leftsead end-->