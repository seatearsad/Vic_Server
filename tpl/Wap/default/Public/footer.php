<div class="footer">
    <div class="about_div">
        <p style="font-size: 18px;font-weight: bold">{pigcms{:L('7ABOUTUS')}</p>
        <p style="font-family: Montserrat-light;margin-top: 10px">
            {pigcms{:getAboutDesc()}...
        </p>
        <div class="learn_btn">{pigcms{:L('LEARNMORE')}</div>
    </div>
    <div class="footer_memo">
        <div style="width: 40%">
            <ul>
                <li class="list_head">{pigcms{:L('7NAV')}</li>
                <li><a href="#download">{pigcms{:L('7DOWNLOADAPP')}</a></li>
                <li><a href="{pigcms{$config.site_url}/wap.php">{pigcms{:L('7ORDERNOW')}</a></li>
                <li><a href="{pigcms{$config.site_url}/news">{pigcms{:L('7BLOG')}</a></li>
                <li><a href="{pigcms{$config.site_url}/intro/5.html" target="_blank">{pigcms{:L('7TERMSOFUSE')}</a></li>
                <li><a href="{pigcms{$config.site_url}/intro/2.html" target="_blank">{pigcms{:L('7PRIVACY')}</a></li>
            </ul>
        </div>
        <div class="footer_info" style="width: 60%">
            <ul>
                <li class="list_head">{pigcms{:L('7PARTNERSHIP')}</li>
                <li><a href="{pigcms{$config.site_url}/wap.php?g=Wap&c=Index&a=partner">{pigcms{:L('7MERCHANT')}</a></li>
                <li><a href="{pigcms{$config.site_url}/wap.php?g=Wap&c=Index&a=courier">{pigcms{:L('7COURIER')}</a></li>
                <li class="app_icon"></li>
                <li class="apk_icon"></li>
            </ul>
        </div>
        <div class="link_icon" style="width: 100%">
            <ul>
                <li><a href="https://www.facebook.com/tuttidelivery" target="_blank"></a></li>
                <li><a href="https://www.instagram.com/tuttidelivery/" target="_blank"></a></li>
                <li><a href="https://twitter.com/tuttidelivery" target="_blank"></a></li>
                <li><a href="https://www.youtube.com/channel/UCdXYWCKbNRPysK9dZ9rtC2A" target="_blank"></a></li>
                <li><a href="https://www.linkedin.com/company/tuttilifestyle" target="_blank"></a></li>
            </ul>
        </div>
    </div>
    <div class="grab_line"></div>
    <div class="copy_div">
        &copy; 2021 Kavl Technology Ltd.All rights reserved
    </div>
    <script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-123655278-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-123655278-1');
    </script>
    <!--script src="{pigcms{$static_public}js/lang.js"></script-->
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

        $('.learn_btn').click(function () {
            window.location.href = "{pigcms{$config.site_url}/about";
        });
    </script>
</div>
<style>
    .footer{
        width: 100%;
        margin-top: 10px;
        background-color: #232323;
        position: relative;
    }
    .about_div{
        width: 90%;
        margin: 0px auto;
        color: #ffffff;
        padding: 20px 0px;
        line-height: 22px;
        font-size: 16px;
        cursor: pointer;
    }
    .about_div span{
        padding: 3px 3px;
        border: 1px solid white;
        border-radius: 2px;
        font-size: 10px;
    }
    .learn_btn{
        width: 130px;
        background-color: #ffa52d;
        border-radius: 15px;
        height: 40px;
        line-height: 40px;
        margin: 10px 0;
        text-align: center;
        color: white;
        font-size: 18px;
        display: block;
    }
    .grab_line{
        width: 90%;
        height: 1px;
        margin:10px auto;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        background-color: #949494;
    }
    .footer_memo{
        width: 100%;
        margin:0px auto;
        color: #ffffff;
        overflow: hidden;
    }

    .footer_memo div{
        float: left;
        width: 50%;
    }
    .footer_memo a{
        color: #ffffff;
        text-decoration: none;
    }
    .footer_memo a:hover{
        color: #ffa52d;
    }
    .footer_memo ul{
        margin: 20px 0px 30px 20px;
        padding: 10px 0px;
        width: 100%;
    }
    .footer_memo ul li{
        list-style: none;
        line-height: 30px;
        font-size: 14px;
        font-family: Montserrat-light;
    }
    .footer_memo .list_head{
        line-height: 50px;
        font-size: 16px;
        font-weight: bold;
        font-family: Montserrat;
    }
    .open_time{
        text-align: center;
    }
    .open_time ul{
        width: 100%;
        margin: 10px auto;
    }
    .open_time ul .open_img{
        height: 70px;
        background-image: url("./tpl/Static/blue/images/new/open.png");
        background-size: 70px auto;
        background-repeat: no-repeat;
        background-position:30px center;
    }
    .footer_app ul{
        margin: 0 auto;
        overflow: hidden;
    }

    .footer_app ul li{
        width: 50%;
        height: 70px;
        background-size: auto 25px;
        background-repeat: no-repeat;
        background-position: center;
        cursor: pointer;
        float: left;
    }
    .footer_memo .app_icon, .footer_memo .apk_icon {
        height: 40px;
        background-size: auto 90%;
        background-repeat: no-repeat;
        background-position: center left;
        cursor: pointer;
        margin-top: 8px;
    }
    .app_icon{
        background-image: url("./tpl/Static/blue/images/new/Apple_app_store_icon_new.png");
    }
    .apk_icon{
        margin-top: 5px;
        background-image: url("./tpl/Static/blue/images/new/AndroidButton_new.png");
    }
    .copy_div{
        font-size: 13px;
        text-align: center;
        color: #707070;
        width: 100%;
        height: 100px;
        margin-top: 20px;
    }
    .link_icon ul{
        margin-bottom: 0;
        margin-left: 0;
        padding: 0;
        text-align: center;
    }
    .link_icon ul li{
        width: 24px;
        height: 24px;
        margin-left: 4px;
        list-style: none;
        display: inline-block;
        background-size: 100% 100%;
    }
    .link_icon ul li:nth-child(1){
        margin-left: 0px;
    }
    .link_icon a{
        width: 24px;
        height: 24px;
        display: block;
    }
    .link_icon li:nth-child(1){
        background-image: url("./tpl/Static/blue/images/new/icons/facebook_new.png");
    }
    .link_icon li:nth-child(2){
        background-image: url("./tpl/Static/blue/images/new/icons/instagram_new.png");
    }
    .link_icon li:nth-child(3){
        background-image: url("./tpl/Static/blue/images/new/icons/twitter_new.png");
    }
    .link_icon li:nth-child(4){
        background-image: url("./tpl/Static/blue/images/new/icons/youtube_new.png");
    }
    .link_icon li:nth-child(5){
        background-image: url("./tpl/Static/blue/images/new/icons/linkedin_new.png");
    }
    .lang_curr_wap div{
        float: none;
    }
    .lang_select{
        display: none;
        z-index: 99;
    }
</style>