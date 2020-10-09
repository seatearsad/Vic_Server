<div class="footer">
    <div class="about_div">
        <p style="font-size: 20px;">TUTTI</p>
        <p>
            {pigcms{:getAboutDesc()}...
            <span>Read More</span>
        </p>
    </div>
    <div class="grab_line"></div>
    <div class="footer_memo">
        <div>
            <ul>
                <li class="list_head">LEGAL</li>
                <pigcms:footer_link var_name="footer_link_list">
                    <li><a href="{pigcms{$vo.url}" target="_blank">{pigcms{$vo.name}</a></li>
                </pigcms:footer_link>
            </ul>
        </div>
        <div class="footer_info">
            <ul>
                <li class="list_head">INFORMATION</li>
                <li><a href="{pigcms{$config.site_url}/about" target="_blank">About Us</a></li>
                <!--li><a href="{pigcms{$config.site_url}/news" target="_blank">Blogs</a></li-->
                <li><a href="javascript:void(0);">Blogs</a></li>
                <li><a href="{pigcms{$config.site_url}/courier" target="_blank">Become a Courier</a></li>
                <li><a href="{pigcms{$config.site_url}/partner" target="_blank">Become a Partner</a></li>
                <li class="lang_div">
                    <if condition="C('DEFAULT_LANG') == 'zh-cn'">
                        Chinese
                        <else />
                        English
                    </if>
                    <span style="font-weight: bold"> &or;</span>
                    <div class="lang_select">
                        <div class="lang_en">English</div>
                        <div class="lang_cn">Chinese</div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="open_time">
            <ul>
                <li class="open_img"></li>
                <li>10:00 am - 1:00 am</li>
                <li><a href="mailto:info@tutti.app">info@tutti.app</a></li>
                <li><a href="tel:18883996668">1-888-399-6668</a></li>
            </ul>
        </div>
        <div class="footer_app">
            <ul>
                <li class="app_icon"></li>
                <li class="apk_icon"></li>
            </ul>
        </div>
    </div>
    <div class="copy_div">
        &copy; 2019 Kavl Technology Ltd.All rights reserved
    </div>
    <div class="link_icon">
        <ul>
            <li><a href="https://www.facebook.com/tuttidelivery" target="_blank"></a></li>
            <li><a href="https://www.instagram.com/tuttidelivery/" target="_blank"></a></li>
            <li><a href="https://twitter.com/tuttidelivery" target="_blank"></a></li>
            <li><a href="https://www.youtube.com/channel/UCdXYWCKbNRPysK9dZ9rtC2A" target="_blank"></a></li>
            <li><a href="https://www.linkedin.com/company/tuttilifestyle" target="_blank"></a></li>
        </ul>
    </div>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-123655278-1"></script>

    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-123655278-1');
    </script>
    <script src="{pigcms{$static_public}js/lang.js"></script>
    <script>
        $('.apk_icon').click(function () {
            // layer.open({
            //     title:"{pigcms{:L('_STORE_REMIND_')}",
            //     content:'Coming Soon'
            // });
            window.open('https://play.google.com/store/apps/details?id=com.kavl.tutti.user');
        });
        var app_url = 'https://itunes.apple.com/us/app/tutti/id1439900347?ls=1&mt=8';
        $('.app_icon').click(function () {
            window.open(app_url);
        });
        
        $('.about_div').click(function () {
            window.location.href = "{pigcms{$config.site_url}/about";
        });
    </script>
</div>
<style>
    .footer{
        width: 100%;
        margin-top: 100px;
        background-color: #232323;
        height: 500px;
        position: relative;
    }
    .about_div{
        width: 60%;
        margin: 0px auto;
        color: #ffffff;
        padding: 20px 0px;
        line-height: 22px;
        cursor: pointer;
    }
    .about_div span{
        padding: 3px 3px;
        border: 1px solid white;
        border-radius: 2px;
        font-size: 10px;
    }
    .grab_line{
        width: 90%;
        height: 3px;
        margin:10px auto;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        background-color: #949494;
    }
    .footer_memo{
        width: 90%;
        margin:50px auto;
        color: #ffffff;
        display: flex;
    }
    .footer_memo a{
        color: #ffffff;
        text-decoration: none;
    }
    .footer_memo a:hover{
        color: #ffa52d;
    }
    .footer_memo ul{
        margin: 0px 0px 0px 30px;
        padding: 10px 0px;
        width: 150px;
    }
    .footer_memo ul li{
        list-style: none;
        height: 30px;
        line-height: 30px;
        font-size: 12px;
    }
    .footer_memo .list_head{
        height: 40px;
        line-height: 40px;
        font-size: 16px;
        font-weight: bold;
    }
    .open_time{
        flex: 1 1 100%;
        text-align: center;
    }
    .open_time ul{
        width: 100%;
        margin: 0 auto;
        margin-top: -15px;
    }
    .open_time ul .open_img{
        height: 70px;
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/open.png");
        background-size: 70px auto;
        background-repeat: no-repeat;
        background-position: center;
    }
    .footer_app{
        flex: 1 1 100%;
    }
    .footer_app ul{
        margin: 0 auto;
        width: 250px;
    }
    .footer_app .app_icon{
        height: 70px;
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/Apple_app_store_icon.png");
        background-size: auto 50px;
        background-repeat: no-repeat;
        background-position: right;
        cursor: pointer;
    }
    .footer_app .apk_icon{
        height: 70px;
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/AndroidButton.png");
        background-size: auto 50px;
        background-repeat: no-repeat;
        background-position: right;
        cursor: pointer;
    }
    .copy_div{
        font-size: 13px;
        text-align: center;
        color: #707070;
    }
    .link_icon ul{
        margin: -35px 8% 0 0;
        padding: 0;
        float: right;
    }
    .link_icon ul li{
        width: 35px;
        height: 35px;
        margin-left: 10px;
        list-style: none;
        float: left;
        background-size: 100% 100%;
    }
    .link_icon a{
        width: 35px;
        height: 35px;
        display: block;
    }
    .link_icon li:nth-child(1){
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/icons/facebook.png");
    }
    .link_icon li:nth-child(2){
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/icons/instagram.png");
    }
    .link_icon li:nth-child(3){
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/icons/twitter.png");
    }
    .link_icon li:nth-child(4){
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/icons/youtube.png");
    }
    .link_icon li:nth-child(5){
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/icons/linkedin.png");
    }
    .lang_div{
        cursor: pointer;
    }
    .lang_select{
        display: none;
    }
    .lang_select div:hover{
        color: #ffa52d;
    }
</style>
<script type="text/javascript">
    window.zESettings = {
        webWidget: {
            color: {
                launcher: '#ffa52d', // This will also update the badge
                launcherText: '#ffffff',
            }
        }
    };
</script>
<!-- Start of tuttidelivery Zendesk Widget script -->
<script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=fe2c146c-36c1-4a86-807d-0ebeaa3d0a58"> </script>
<!-- End of tuttidelivery Zendesk Widget script -->