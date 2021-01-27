<script src="{pigcms{$static_public}layer/layer.m.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<style>
    #tutti_header{
        height: 80px;
        display: flex;
        background-color: #ffffff;
    }
    #header_menu{
        display: flex;
        flex: 1 1 100%;
    }
    #header_sign{
        flex: 1 1 100%;
    }
    #header_logo{
        margin: 2px auto;
        height: 76px;
        color: #ffa52d;
        line-height: 76px;
        font-size: 32px;
        font-weight: bolder;
        flex: 0 0 auto;
        font-family: Montserrat-bold;
    }
    .hamburger{
        height: 44px;
        width: 50px;
        cursor: pointer;
        margin-left: 30px;
        margin-top: 18px;
    }
    .hamburger .line {
        width: 35px;
        height: 4px;
        background-color: #3f3f3f;
        display: block;
        margin: 7px auto;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        -webkit-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
    .hamburger:hover .line{
        background-color: #ffa64d;
    }
    .menu_font{
        margin-top: 25px;
        font-size: 20px;
        height: 30px;
        line-height: 30px;
    }
    .menu_font span{
        font-size: 16px;
        color: dimgrey;
        display: inline-block;
        padding-left: 25px;
    }
    #hamburger-1.is-active .line:nth-child(1) {
        -webkit-transform: translateY(11px) rotate(45deg);
        -ms-transform: translateY(11px) rotate(45deg);
        -o-transform: translateY(11px) rotate(45deg);
        transform: translateY(11px) rotate(45deg);
    }
    #hamburger-1.is-active .line:nth-child(3) {
        -webkit-transform: translateY(-11px) rotate(-45deg);
        -ms-transform: translateY(-11px) rotate(-45deg);
        -o-transform: translateY(-11px) rotate(-45deg);
        transform: translateY(-11px) rotate(-45deg);
    }
    #hamburger-1.is-active .line:nth-child(2) {
        opacity: 0;
    }
    #menu_memo{
        max-width: 100%;
        width: 380px;
        position: absolute;
        background-color: #f5f5f5;
        transition: transform .4s ease;
        transform: translate3d(-100%, 0, 0);
        display: flex;
        z-index: 999999;
    }
    #menu_memo.is_open{
        transform: translate3d(0, 0, 0);
    }
    #menu_memo ul{
        width: 92%;
        margin-left: 5%;
        margin-top: 20px;
        padding: 0;
    }
    #menu_memo ul li{
        list-style-type: none;
        height: 30px;
        line-height: 30px;
        cursor: pointer;
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/black_arrow.png");
        background-size: auto 16px;
        background-repeat: no-repeat;
        background-position: right;
        display: flex;
        margin-top: 5px;
        color: #3f3f3f;
    }
    #menu_memo ul li:hover{
        color: #ffa52d;
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/or_arrow.png");
    }

    #menu_memo li:nth-child(1) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/home.png");
    }
    #menu_memo li:nth-child(2) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/food.png");
    }
    #menu_memo li:nth-child(3) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/car.png");
    }
    #menu_memo li:nth-child(4) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/store.png");
    }
    #menu_memo li:hover:nth-child(1) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/h_home.png");
    }
    #menu_memo li:hover:nth-child(2) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/h_food.png");
    }
    #menu_memo li:hover:nth-child(3) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/h_car.png");
    }
    #menu_memo li:hover:nth-child(4) .m_img{
        background-image: url("{pigcms{$config.site_url}/tpl/Static/blue/images/new/h_store.png");
    }
    #menu_memo li .m_img{
        background-size: 22px 22px;
        background-repeat: no-repeat;
        background-position:left 2px;
        height: 30px;
        width: 30px;
    }
    .w_line{
        background-color: #ffffff;
        width: 100%;
        height: 5px;
        margin-top: 30px;
        margin-bottom: 30px;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
    }

    .sign_btn_up{
        width: 80px;
        height: 28px;
        border: 1px solid #ffa52d;
        color: #ffa52d;
        -moz-border-radius: 14px;
        -webkit-border-radius: 14px;
        border-radius: 14px;
        line-height: 30px;
        text-align: center;
        box-sizing: content-box;
        float: right;
        margin-right: 30px;
        margin-top: 26px;
        font-size: 16px;
        cursor: pointer;
    }

    .sign_btn{
        width: 80px;
        height: 28px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        line-height: 30px;
        text-align: center;
        box-sizing: content-box;
        float: right;
        margin-right: 30px;
        margin-top: 26px;
        font-size: 16px;
        cursor: pointer;
    }
    .user_div,.lang_div{
        width: 80px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        float: right;
        margin-right: 5%;
        margin-top: 26px;
        font-size: 1em;
    }
    .lang_div{
        padding-left: 33px;
        background-image: url("./tpl/Static/blue/images/wap/language.png");
        background-size: 28px auto;
        background-repeat: no-repeat;
        box-sizing: border-box;
    }
    .user_div a{
        text-decoration: none;
        color: #ffa52d;
    }
</style>
<div id="tutti_header">
    <div id="header_menu">
        <div id="hamburger-1" class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
        <div class="menu_font">
            <span><a href="#download" style="color: dimgrey;text-decoration: none;">{pigcms{:L('1DOWNLOADAPP')}</a></span>
            <if condition="empty($user_session)">
                <span><a href="{pigcms{:U('Wap/Login/index')}" style="color: dimgrey;text-decoration: none;">{pigcms{:L('1INVITE')}</a></span>
            <else />
                <span><a href="{pigcms{:U('Wap/My/invitation')}" style="color: dimgrey;text-decoration: none;">{pigcms{:L('1INVITE')}</a></span>
            </if>
        </div>
    </div>
    <div id="header_logo">
        TUTTI
    </div>
    <div id="header_sign">
        <div class="lang_div">
            <if condition="C('DEFAULT_LANG') == 'zh-cn'">
                中文
                <else />
                English
            </if>
            <div class="lang_select" style="z-index: 99999;position: relative;background-color: white;border-radius: 5px;padding: 5px;margin-left: -5px;">
                <div class="lang_en">English</div>
                <div class="lang_cn">中文</div>
            </div>
        </div>
        <if condition="empty($user_session)">
            <div class="sign_btn_up">{pigcms{:L('_B_D_LOGIN_REG2_')}</div>
            <div class="sign_btn">{pigcms{:L('_NEW_SIGN_IN_')}</div>
            <else />
            <div class="user_div">
                <a href="{pigcms{:U('Wap/My/index')}">{pigcms{$user_session.nickname}</a>
            </div>
        </if>
    </div>
</div>
<div id="menu_memo">
    <ul>
        <li id="menu_home">
            <span class="m_img"> </span>
            <span>{pigcms{:L('_HOME_TXT_')}</span>
        </li>
        <li id="menu_shop">
            <span class="m_img"> </span>
            <span>{pigcms{:L('_NEW_FOOD_COMM_')}</span>
        </li>
        <li id="menu_courier">
            <span class="m_img"> </span>
            <span>{pigcms{:L('_NEW_BECOME_COURIER_')}</span>
        </li>
        <li id="menu_partner">
            <span class="m_img"> </span>
            <span>{pigcms{:L('_NEW_BECOME_PARTNER_')}</span>
        </li>
        <li style="background-image: none;height: 65px">
            <span class="w_line"></span>
        </li>
        <li id="menu_about" style="background-image: none">
            <span>{pigcms{:L('_B_PURE_MY_ABOUTUS_')}</span>
        </li>
        <li id="menu_blog" style="background-image: none">
            <span>{pigcms{:L('_NEW_BLOGS_')}</span>
        </li>
        <li style="background-image: none">
            <span>{pigcms{:L('_NEW_HELP_')}</span>
        </li>
    </ul>
</div>
<script>
    var is_open_menu = false;
    $('.hamburger').click(function () {
        if(is_open_menu) {
            $(this).removeClass('is-active');
            $('#menu_memo').removeClass('is_open');
        }else {
            $(this).addClass('is-active');
            $('#menu_memo').addClass('is_open');
            $('#menu_memo').height($(window).height() - 60);
        }

        is_open_menu = !is_open_menu;
    });
    $('#menu_home').click(function () {
        window.location.href = '{pigcms{$config.site_url}';
    });
    $('#menu_shop').click(function () {
        window.location.href = '{pigcms{$config.site_url}/app';
    });
    $('#menu_courier').click(function () {
        window.location.href = '{pigcms{$config.site_url}/courier';
    });
    $('#menu_partner').click(function () {
        window.location.href = '{pigcms{$config.site_url}/partner';
    });
    $('#menu_about').click(function () {
        window.location.href = '{pigcms{$config.site_url}/about';
    });
    $('#menu_blog').click(function () {
        //window.location.href = '{pigcms{$config.site_url}/news';
    });
    
    $('.sign_btn').click(function () {
        // var width = $(window).width()/3;
        // var height = $(window).height()*0.8;
        // art.dialog.open("{pigcms{:U('Wap/Login/index')}",
        //    {title: '', width: width, height: height,close:null,background:'black',opacity:'0.4'});
        window.location.href = "{pigcms{:U('Wap/Login/index')}";
    });
    $('.sign_btn_up').click(function () {
        window.location.href = "{pigcms{:U('Wap/Login/reg')}";
    });
</script>