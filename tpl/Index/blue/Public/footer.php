<div class="footer">
    <!--div class="about_div">
        <p style="font-size: 20px;">TUTTI</p>
        <p>
            {pigcms{:getAboutDesc()}...
            <span>Read More</span>
        </p>
    </div-->
    <div class="footer_memo">
        <div style="flex: 1 1 80%;display: inherit;position: relative">
            <ul>
                <li class="list_head">{pigcms{:L('7ABOUTUS')}</li>
                <li style="font-family: Montserrat-light;">{pigcms{:getAboutDesc()}...</li>
            </ul>
            <div class="about_more_btn" id="about_div">{pigcms{:L('LEARNMORE')}</div>
        </div>
        <div class="footer_info">
            <ul>
                <li class="list_head">{pigcms{:L('7NAV')}</li>
                <li><a href="#download">{pigcms{:L('7DOWNLOADAPP')}</a></li>
                <li><a href="{pigcms{$config.site_url}/wap.php">{pigcms{:L('7ORDERNOW')}</a></li>
                <li><a href="javascript:void(0);">{pigcms{:L('7BLOG')}</a></li>
                <li><a href="{pigcms{$config.site_url}/intro/5.html" target="_blank">{pigcms{:L('7TERMSOFUSE')}</a></li>
                <li><a href="{pigcms{$config.site_url}/intro/2.html" target="_blank">{pigcms{:L('7PRIVACY')}</a></li>
            </ul>
        </div>
        <div class="footer_app">
            <ul>
                <li class="list_head">{pigcms{:L('7PARTNERSHIP')}</li>
                <li><a href="{pigcms{$config.site_url}/partner">{pigcms{:L('7MERCHANT')}</a></li>
                <li><a href="{pigcms{$config.site_url}/courier">{pigcms{:L('7COURIER')}</a></li>
                <li class="app_icon"></li>
                <li class="apk_icon"></li>
            </ul>
        </div>
    </div>
    <div class="grab_line"></div>
    <div class="copy_div">
        Copyright © 2021 Kavl Technology Ltd. All rights reserved
    </div>
    <div class="link_icon">
        <ul>
            <li><a href="https://www.facebook.com/tuttidelivery" target="_blank"></a></li>
            <li><a href="https://www.instagram.com/tuttidelivery/" target="_blank"></a></li>
            <li><a href="https://twitter.com/tuttidelivery" target="_blank"></a></li>
            <li><a href="https://www.linkedin.com/company/tuttilifestyle" target="_blank"></a></li>
            <li><a href="https://www.youtube.com/channel/UCdXYWCKbNRPysK9dZ9rtC2A" target="_blank"></a></li>
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
        
        $('#about_div').click(function () {
            window.location.href = "{pigcms{$config.site_url}/about";
        });
    </script>
</div>
<style>
    .footer{
        width: 100%;
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
        width: 80%;
        height: 1px;
        margin:10px auto;
        background-color: white;
    }
    .footer_memo{
        width: 80%;
        margin:50px auto;
        color: lightgray;
        display: flex;
        padding-top: 50px;
    }
    .footer_memo a{
        color: #ffffff;
        text-decoration: none;
        font-family: Montserrat-light;
    }
    .footer_memo a:hover{
        color: #ffa52d;
    }
    .footer_memo ul{
        margin: 0px 0px 0px 30px;
        padding: 10px 0px;
        width: 400px;
    }
    .footer_memo ul li{
        list-style: none;
        height: 30px;
        line-height: 30px;
        font-size: 16px;
        /*font-weight: lighter;*/
    }
    .footer_memo .list_head{
        height: 50px;
        line-height: 40px;
        font-size: 22px;
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
        /*flex: 1 1 100%;*/
    }
    .footer_app ul{
        margin: 0 auto;
        width: 280px;
    }
    .footer_app .app_icon{
        height: 70px;
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/Apple_app_store_icon.png");
        background-size: auto 50px;
        background-repeat: no-repeat;
        background-position: left;
        cursor: pointer;
    }
    .footer_app .apk_icon{
        height: 70px;
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/AndroidButton.png");
        background-size: auto 50px;
        background-repeat: no-repeat;
        background-position: left;
        cursor: pointer;
    }
    .copy_div{
        font-size: 18px;
        text-align: left;
        color: lightgray;
        margin-left: 10.5%;
        line-height: 50px;
    }
    .link_icon ul{
        margin: -45px 10.5% 0 0;
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
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/icons/linkedin.png");
    }
    .link_icon li:nth-child(5){
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/icons/youtube.png");
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
    .about_more_btn{
        width: 180px;
        background-color: #ffa52d;
        border-radius: 20px;
        height: 50px;
        line-height: 50px;
        margin: 10px auto 0 30px;
        text-align: center;
        color: white;
        font-size: 20px;
        display: block;
        position: absolute;
        bottom: 10px;
        cursor: pointer;
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
<!--script id="ze-snippet" src="https://static.zdassets.com/ekr/snippet.js?key=fe2c146c-36c1-4a86-807d-0ebeaa3d0a58"> </script-->
<!-- End of tuttidelivery Zendesk Widget script -->