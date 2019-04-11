<script type="text/javascript" src="{pigcms{$static_public}js/lang.js" charset="utf-8"></script>
<div class="footer">
    <div class="about_div">
        <p style="font-size: 20px;">TUTTI</p>
        <p>
            Aiming for best local food delivery and bring communities closer by offering virous business model. Founded in 2017,
            on-demand food delivery concierge service...
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
                <li><a href="#" target="_blank">About Us</a></li>
                <li><a href="#" target="_blank">Blogs</a></li>
                <li><a href="{pigcms{:U('Index/courier')}" target="_blank">Become a Courier</a></li>
                <li><a href="{pigcms{:U('Index/partner')}" target="_blank">Become a Partner</a></li>
            </ul>
        </div>
        <div class="open_time">
            <ul>
                <li class="open_img"></li>
            </ul>
        </div>
        <div>
            <ul>
                <li>11:00 am - 1:00 am</li>
                <li><a href="mailto:info@tutti.app">info@tutti.app</a></li>
                <li><a href="tel:12505906668">1-250-590-6668</a></li>
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
        line-height: 22px
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
        margin: 20px 0px 10px 30px;
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
</style>