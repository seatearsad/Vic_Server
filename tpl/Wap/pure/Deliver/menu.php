<!-- 悬浮 -->
<div class="suspension">
    <div class="suspension_n">
        <ul>
            <li><a href="{pigcms{:U('Deliver/index')}"><img src="{pigcms{$static_path}images/ht_03.png">{pigcms{:L('_HOME_TXT_')}</a></li>
            <li><a href="{pigcms{:U('Deliver/tongji')}"><img src="{pigcms{$static_path}images/ht_06.png">{pigcms{:L('_STATISTICS_TXT_')}</a></li>
            <li><a href="{pigcms{:U('Deliver/info')}"><img src="{pigcms{$static_path}images/ht_09.png">{pigcms{:L('_PROFILE_TXT_')}</a></li>
            <li><a href="{pigcms{:U('Deliver/grab')}"><img src="{pigcms{$static_path}images/ht_13.png">{pigcms{:L('_C_ORDER_PENDING_')}</a></li>
            <li><a href="{pigcms{:U('Deliver/pick')}"><img src="{pigcms{$static_path}images/ht_17.png">{pigcms{:L('_C_PROCESSING_')}</a></li>
            <li><a href="{pigcms{:U('Deliver/finish')}"><img src="{pigcms{$static_path}images/ht_20.png">{pigcms{:L('_C_COMPLETED')}</a></li>
        </ul>
    </div>
    <div class="susp-img"></div>
</div>
<script type="text/javascript">
    $(".susp-img").click(function(){
        $(".suspension_n").toggle(100);
    })
    $(".suspension_n li").last().css("border","none");
</script>
<!-- 悬浮 -->