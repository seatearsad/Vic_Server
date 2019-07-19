<script type="text/javascript" src="{pigcms{$static_public}js/lang.js" charset="utf-8"></script>
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
                <li><a href="#" target="_blank">Blogs</a></li>
                <li><a href="{pigcms{:U('Index/courier')}" target="_blank">Become a Courier</a></li>
                <li><a href="{pigcms{:U('Index/partner')}" target="_blank">Become a Partner</a></li>
                <li class="lang_curr_wap">
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
            </ul>
        </div>
        <div>
            <ul>
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
        <div class="link_icon">
            <ul>
                <li><a href="https://www.facebook.com/tuttilifestyle/" target="_blank"></a></li>
                <li><a href="https://www.instagram.com/tuttilifestyle/?hl=en" target="_blank"></a></li>
                <li><a href="https://twitter.com/tuttilifestyle" target="_blank"></a></li>
                <li><a href="https://www.youtube.com/channel/UCdXYWCKbNRPysK9dZ9rtC2A?view_as=public" target="_blank"></a></li>
                <li><a href="https://www.linkedin.com/company/tuttilifestyle/about/" target="_blank"></a></li>
            </ul>
        </div>
    </div>
    <div class="copy_div">
        &copy; 2019 Kavl Technology Ltd.All rights reserved
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
        position: relative;
    }
    .about_div{
        width: 80%;
        margin: 0px auto;
        color: #ffffff;
        padding: 20px 0px;
        line-height: 22px;
        font-size: 12px;
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
        margin:10px auto;
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
        margin: 20px 0px 30px 30px;
        padding: 10px 0px;
        width: 100%;
    }
    .footer_memo ul li{
        list-style: none;
        height: 20px;
        line-height: 20px;
        font-size: 12px;
    }
    .footer_memo .list_head{
        height: 40px;
        line-height: 40px;
        font-size: 16px;
        font-weight: bold;
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
    .footer_app .app_icon{
        background-image: url("./tpl/Static/blue/images/new/Apple_app_store_icon.png");
    }
    .footer_app .apk_icon{
        background-image: url("./tpl/Static/blue/images/new/AndroidButton.png");
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
        margin-top: 32px;
        padding: 0;
        float: left;
    }
    .link_icon ul li{
        width: 24px;
        height: 24px;
        margin-left: 4px;
        list-style: none;
        float: left;
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
        background-image: url("./tpl/Static/blue/images/new/icons/facebook.png");
    }
    .link_icon li:nth-child(2){
        background-image: url("./tpl/Static/blue/images/new/icons/instagram.png");
    }
    .link_icon li:nth-child(3){
        background-image: url("./tpl/Static/blue/images/new/icons/twitter.png");
    }
    .link_icon li:nth-child(4){
        background-image: url("./tpl/Static/blue/images/new/icons/youtube.png");
    }
    .link_icon li:nth-child(5){
        background-image: url("./tpl/Static/blue/images/new/icons/linkedin.png");
    }
    .lang_curr_wap div{
        float: none;
    }
    .lang_select{
        display: none;
        z-index: 99;
    }
</style>