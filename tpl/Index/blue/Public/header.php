<style>
    #tutti_header{
        height: 60px;
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
        width: 56px;
        height: 56px;
        -moz-border-radius: 28px;
        -webkit-border-radius: 28px;
        border-radius: 28px;
        background-color: #ffa52d;
        background-image: url("./tpl/Static/blue/images/new/icon.png");
        background-size: 100% 100%;
        flex: 0 0 auto;
    }
    .hamburger{
        height: 50px;
        width: 50px;
        cursor: pointer;
        margin-left: 30px;
        margin-top: 15px;
    }
    .hamburger .line {
        width: 25px;
        height: 3px;
        background-color: #3f3f3f;
        display: block;
        margin: 5px auto;
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
        margin-top: 16px;
        font-size: 20px;
        height: 30px;
        line-height: 30px;
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
        background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
        background-size: auto 16px;
        background-repeat: no-repeat;
        background-position: right;
        display: flex;
        margin-top: 5px;
        color: #3f3f3f;
    }
    #menu_memo ul li:hover{
        color: #ffa52d;
        background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
    }

    #menu_memo li:nth-child(1) .m_img{
        background-image: url("./tpl/Static/blue/images/new/home.png");
    }
    #menu_memo li:nth-child(2) .m_img{
        background-image: url("./tpl/Static/blue/images/new/food.png");
    }
    #menu_memo li:nth-child(3) .m_img{
        background-image: url("./tpl/Static/blue/images/new/car.png");
    }
    #menu_memo li:nth-child(4) .m_img{
        background-image: url("./tpl/Static/blue/images/new/store.png");
    }
    #menu_memo li:hover:nth-child(1) .m_img{
        background-image: url("./tpl/Static/blue/images/new/h_home.png");
    }
    #menu_memo li:hover:nth-child(2) .m_img{
        background-image: url("./tpl/Static/blue/images/new/h_food.png");
    }
    #menu_memo li:hover:nth-child(3) .m_img{
        background-image: url("./tpl/Static/blue/images/new/h_car.png");
    }
    #menu_memo li:hover:nth-child(4) .m_img{
        background-image: url("./tpl/Static/blue/images/new/h_store.png");
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
        width: 80px;
        height: 28px;
        border: 3px solid #F5F5F5;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        line-height: 30px;
        text-align: center;
        box-sizing: content-box;
        float: right;
        margin-right: 30px;
        margin-top: 11px;
        font-size: 18px;
        cursor: pointer;
    }
</style>
<div id="tutti_header">
    <div id="header_menu">
        <div id="hamburger-1" class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
        <div class="menu_font">{pigcms{:L('_NEW_MENU_')}</div>
    </div>
    <div id="header_logo"></div>
    <div id="header_sign">
        <div class="sign_btn">{pigcms{:L('_NEW_SIGN_IN_')}</div>
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
        <li style="background-image: none">
            <span>{pigcms{:L('_B_PURE_MY_ABOUTUS_')}</span>
        </li>
        <li style="background-image: none">
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
        window.location.href = './';
    });
    $('#menu_shop').click(function () {
        window.location.href = './shop';
    });
    $('#menu_courier').click(function () {
        window.location.href = './courier';
    });
    $('#menu_partner').click(function () {
        window.location.href = './partner';
    });
</script>