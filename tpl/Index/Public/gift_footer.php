<style>

</style>
<div class="footer">
    <div class="footer-wrap">
        <div class="footer-inner">
            <ul class="footer-about">
                <!--li>
                    <dl>
                        <dt>关于我们</dt>
                        <dd><a href="#" target="_blank" rel="nofollow" data-bn-ipg="index-last-us-0">企业简介</a></dd>
                        <dd><a href="#" target="_blank" rel="nofollow" data-bn-ipg="index-last-us-1">企业资质</a></dd>
                        <dd><a href="#" target="_blank" rel="nofollow" data-bn-ipg="index-last-us-1">联系我们</a></dd>
                        
                    </dl>
                </li>
                <li>
                    <dl>
                        <dt>加入我们</dt>
                        <dd><a href="#" target="_blank" rel="nofollow" data-bn-ipg="index-last-us-0">商户入驻</a></dd>
                       
                      
                    </dl>
                </li>

                <li>
                    <dl>
                        <dt>帮助中心</dt>
                        <dd><a href="#" target="_blank" rel="nofollow" data-bn-ipg="index-last-us-0">手机版</a></dd>
                        <dd><a href="#" target="_blank" rel="nofollow" data-bn-ipg="index-last-us-1">平台快报</a></dd>
                    </dl>
                </li-->
				
				<pigcms:footer_link var_name="footer_link_list">
					<li><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a><if condition="$i neq count($footer_link_list)"><span>&nbsp;&nbsp;|&nbsp;&nbsp;</span></if></li>
				</pigcms:footer_link>
            </ul>
        </div>
    </div>
	
    <div class="footer-wrap-black">
        <div class="footer-inner2">
            <div class="footer-copyright">
            <a href="/" rel="nofollow" data-bn-ipg="foot-logo"><img src="{pigcms{$config.site_logo}" alt="积分商城logo"></a>
                <p>{pigcms{:nl2br(strip_tags($config['site_show_footer'],'<a>'))}</p>
            </div>
        </div>
    </div>
</div>

<script src="{pigcms{$static_path}gift/js/jquery-1.7.2.min.js"></script>
<script src="{pigcms{$static_path}gift/js/common.js"></script>
<script type="text/javascript" language="javascript">
    $(function(){
        $(".JSjfBtn").on('click',function(){
            showWindow(".bonusLow");
        });
    });
</script>
</body>
</html>