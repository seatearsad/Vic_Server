<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<style>
    html {
        font-size: 625%;
    }
    @font-face {
        font-family: 'Montserrat';
        src: url('/static/font/Montserrat-Regular.ttf');
    }
    @font-face {
        font-family: 'Montserrat-bold';
        src: url('/static/font/Montserrat-Bold.otf');
    }
    @font-face {
        font-family: 'Montserrat-light';
        src: url('/static/font/Montserrat-Light.otf');
    }
    *{
        margin: 0px;
        box-sizing: border-box;
        font-family: Montserrat;
        -moz-osx-font-smoothing: grayscale;
    }
    body{
        color: #3f3f3f;
        font-size: 0.16rem;
    }
    div{
        letter-spacing: -0.5px;
    }
    a{
        display: contents;
        color:#3f3f3f;
        text-decoration: none;
    }
    .main{
        width: 93%;
        margin: 10px auto;
    }
    .only_1_lines{
         text-overflow: -o-ellipsis-lastline;
         overflow: hidden;
         text-overflow: ellipsis;
         display: -webkit-box;
         -webkit-line-clamp: 1;
         -webkit-box-orient: vertical;
     }
    .only_2_lines{
         text-overflow: -o-ellipsis-lastline;
         overflow: hidden;
         text-overflow: ellipsis;
         display: -webkit-box;
         -webkit-line-clamp: 2;
         -webkit-box-orient: vertical;
     }
    .only_3_lines{
        text-overflow: -o-ellipsis-lastline;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
    }
    #tutti_header{
        width: 100%;
        height: 0.6rem;
        display: flex;
        background-color: #ffffff;
        position: sticky;
        top:0px;
        z-index: 999;
    }
    #header_menu{
        display: flex;
        flex: 1 1 100%;
    }
    #header_sign{
        flex: 1 1 100%;
    }
    #header_logo{
        margin: 0.02rem auto;
        color: #ffa52d;
        font-size: 0.16rem;
        font-weight: bold;
        /*width: 56px;*/
        /*height: 56px;*/
        /*-moz-border-radius: 28px;*/
        /*-webkit-border-radius: 28px;*/
        /*border-radius: 28px;*/
        /*background-color: #ffa52d;*/
        /*background-image: url("./tpl/Static/blue/images/new/icon.png");*/
        /*background-size: 100% 100%;*/
        line-height: 0.56rem;
        flex: 0 0 auto;
        font-family: Montserrat-bold;
    }
    .hamburger{
        height: 0.50rem;
        width: 0.25rem;
        cursor: pointer;
        margin-left: 5%;
        margin-top: 0.15rem;
    }
    .hamburger .line {
        width: 0.25rem;
        height: 0.03rem;
        background-color: #3f3f3f;
        display: block;
        margin: 0.05rem auto;
        -moz-border-radius: 0.03rem;
        -webkit-border-radius: 0.03rem;
        border-radius: 0.03rem;
        -webkit-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
    .hamburger:hover .line{
        background-color: #ffa64d;
    }
    .menu_font{
        margin-top: 0.16rem;
        margin-left: 0.10rem;
        font-size: 1.1em;
        height: 0.30rem;
        line-height: 030rem;
    }
    #hamburger-1.is-active .line:nth-child(1) {
        -webkit-transform: translateY(8px) rotate(45deg);
        -ms-transform: translateY(8px) rotate(45deg);
        -o-transform: translateY(8px) rotate(45deg);
        transform: translateY(8px) rotate(45deg);
    }
    #hamburger-1.is-active .line:nth-child(3) {
        -webkit-transform: translateY(-8px) rotate(-45deg);
        -ms-transform: translateY(-8px) rotate(-45deg);
        -o-transform: translateY(-8px) rotate(-45deg);
        transform: translateY(-8px) rotate(-45deg);
    }
    #hamburger-1.is-active .line:nth-child(2) {
        opacity: 0;
    }
    #menu_memo{
        max-width: 100%;
        width: 100%;
        position: absolute;
        background-color: #f5f5f5;
        transition: transform .4s ease;
        transform: translate3d(-150%, 0, 0);
        display: flex;
        position: fixed;
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
        background-image: url("../tpl/Static/blue/images/new/black_arrow.png");
        background-size: auto 16px;
        background-repeat: no-repeat;
        background-position: right;
        display: flex;
        margin-top: 5px;
        color: #3f3f3f;
    }
    #menu_memo ul li:hover{
        color: #ffa52d;
        background-image: url("../tpl/Static/blue/images/new/or_arrow.png");
    }

    #menu_memo li:nth-child(1) .m_img{
        background-image: url("../tpl/Static/blue/images/new/home.png");
    }
    #menu_memo li:nth-child(2) .m_img{
        background-image: url("../tpl/Static/blue/images/new/food.png");
    }
    #menu_memo li:nth-child(3) .m_img{
        background-image: url("../tpl/Static/blue/images/new/car.png");
    }
    #menu_memo li:nth-child(4) .m_img{
        background-image: url("../tpl/Static/blue/images/new/store.png");
    }
    #menu_memo li:hover:nth-child(1) .m_img{
        background-image: url("../tpl/Static/blue/images/new/h_home.png");
    }
    #menu_memo li:hover:nth-child(2) .m_img{
        background-image: url("../tpl/Static/blue/images/new/h_food.png");
    }
    #menu_memo li:hover:nth-child(3) .m_img{
        background-image: url("../tpl/Static/blue/images/new/h_car.png");
    }
    #menu_memo li:hover:nth-child(4) .m_img{
        background-image: url("../tpl/Static/blue/images/new/h_store.png");
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
    .sign_btn{
        width: 30px;
        height: 30px;
        line-height: 30px;
        float: right;
        margin-right: 5%;
        margin-top: 11px;
        font-size: 1.125em;
        cursor: pointer;
        background-image: url("../tpl/Static/blue/images/wap/wap_login.png");
        background-size: 100% auto;
    }
    .user_div{
        width: 80px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        float: right;
        margin-right: 5%;
        margin-top: 15px;
        font-size: 1em;
    }
    .user_div a{
        text-decoration: none;
        color: #ffa52d;
    }
    .down_header{
        width: 100%;
        height: 60px;
        display: flex;
    }
    .down_close{
        width: 10%;
        height: 100%;
        line-height: 60px;
        text-align: center;
        font-size: 1.2em;
        color: #ffa52d;
        cursor: pointer;
        flex: 0 0 auto;
    }
    .down_app_name{
        flex: 1 1 100%;
        padding-left: 10px;
        padding-top: 10px;
        font-size: 0.9em;
    }
    .down_view{
        width: 25%;
        font-size: 1.2em;
        line-height: 60px;
        color: #ffa52d;
        text-align: center;
        flex: 0 0 auto;
        cursor: pointer;
        background-image: url("../tpl/Static/blue/images/wap/download.png");
        background-size: auto 24px;
        background-repeat: no-repeat;
        background-position: center;
    }
    .lang_div{
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        float: right;
        margin-right: 5%;
        margin-top: 11px;
        font-size: 1em;
        background-image: url("../tpl/Static/blue/images/wap/language.png");
        background-size: 100% auto;
        background-repeat: no-repeat;
        box-sizing: border-box;
    }
</style>
<div class="down_header">
    <div class="down_close">X</div>
    <div class="down_icon"></div>
    <div class="down_app_name">
        <div class="name"></div>
    </div>
    <div class="down_view"> </div>
</div>
<div id="tutti_header">
    <div id="header_menu">
        <div id="hamburger-1" class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
        <!--div class="menu_font">{pigcms{:L('_NEW_MENU_')}</div-->
    </div>
    <div id="header_logo">TUTTI</div>
    <div id="header_sign">
        <div class="lang_div">
            <div class="lang_select" style="z-index: 99999;position: relative;background-color: white;border-radius: 5px;margin-top: 35px;padding: 5px;margin-left: -33px">
                <div class="lang_en" <if condition="C('DEFAULT_LANG') != 'zh-cn'">style="color:#ffa52d"</if>>English</div>
                <div class="lang_cn" <if condition="C('DEFAULT_LANG') == 'zh-cn'">style="color:#ffa52d"</if>>中文</div>
            </div>
        </div>
        <if condition="empty($user_session)">
            <div class="sign_btn"></div>
        <else />
            <!--div class="user_div">
                <a href="{pigcms{:U('My/index')}">{pigcms{$user_session.nickname}</a>
            </div-->
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
    function setCookie(c_name,value,expiredays)
    {
        var exdate=new Date();
        exdate.setDate(exdate.getDate()+expiredays);
        document.cookie=c_name+ "=" +escape(value)+
            ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
    }

    $('.lang_div').click(function () {
        //alert($(this).children('.lang_select').css('display'));
        if($(this).children('.lang_select').css('display') != "none")
            $(this).children('.lang_select').hide();
        else
            $(this).children('.lang_select').css("display","inline-block");
    });
    $('.lang_cn').click(function(){
        setCookie('lang','zh-cn',30);
        window.location.reload();
    });

    $('.lang_en').click(function () {
        setCookie('lang','en-us',30);
        window.location.reload();
    });

    var is_open_menu = false;
    $('.hamburger').click(function () {
        if(is_open_menu) {
            $(this).removeClass('is-active');
            $('#menu_memo').removeClass('is_open');
        }else {
            $(this).addClass('is-active');
            $('#menu_memo').addClass('is_open');
            $('#menu_memo').height($(window).height() - 0);
        }

        is_open_menu = !is_open_menu;
    });
    $('#menu_home').click(function () {
        window.location.href = '../wap';
    });
    $('#menu_shop').click(function () {
        window.location.href = '../wap.php';
    });
    $('#menu_courier').click(function () {
        window.location.href = "{pigcms{:U('Index/courier')}";
    });
    $('#menu_partner').click(function () {
        window.location.href = "{pigcms{:U('Index/partner')}";
    });
    $('#menu_blog').click(function () {
        window.location.href = '../news';
    });
    $('#menu_about').click(function () {
        window.location.href = '{pigcms{$config.site_url}/about';
    });

    var init_top = $('#tutti_header').offset().top;
    var init_margin = parseFloat($('#menu_memo').css('margin-top'));

    $('.down_close').click(function () {
        $('.down_header').hide();
        init_top = 0;
    });

    // if(/(android|windows phone)/.test(navigator.userAgent.toLowerCase())){
    //     $('.down_header').hide();
    //     init_top = 0;
    // }

    $('.down_app_name').children('.name').html(app_name);

    $(window).scroll(function () {
        var top = $(document).scrollTop();
        if((top > 0 && top <= init_top) || top < 0){
            $('#tutti_header').css('margin-top',-top);
            $('#menu_memo').css('margin-top',init_margin-top);
        }
        if(top > init_top){
            $('#tutti_header').css('margin-top',-init_top);
            $('#menu_memo').css('margin-top',init_margin - init_top);
        }
        if(top == 0){
            $('#tutti_header').css('margin-top',0);
            $('#menu_memo').css('margin-top',init_margin);

        }
    });
    $('.sign_btn').click(function () {
        // var width = $(window).width();
        // var height = $(window).height();
        // art.dialog.open("{pigcms{:U('Login/index')}",
        //     {title: '', width: width, height: height,flexed:false,close:null,background:'black',opacity:'0.4'});
        window.location.href = "{pigcms{:U('Login/index')}&referer=" + encodeURIComponent(window.location.href);
    });
    $('.down_view').click(function () {
        if(/(android)/.test(navigator.userAgent.toLowerCase())){
            window.open('https://play.google.com/store/apps/details?id=com.kavl.tutti.user');
        }else{
            window.open(app_url);
        }
    });
    $('#header_logo').click(function () {
        window.location.href = "/wap";
    });
</script>