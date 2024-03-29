<style>
    * {
        margin: 0px;
        box-sizing: border-box;
        font-family: Helvetica;
        -moz-osx-font-smoothing: grayscale;
        font-size: 100%;
    }

    #tutti_header {
        width: 100%;
        /*height: 60px;*/
        background-color: #ffffff;
        position: fixed;
        z-index: 999;
        left: 0;
        right: 0;
        max-width: 640px;
        min-width: 320px;
        margin: 0 auto;
    }

    #search_label {
        width: 100%;
        height: 40px;
        background-color: #ffa52d;
        position: fixed;
        display: none;
        z-index: 999;
        left: 0;
        right: 0;
        max-width: 640px;
        min-width: 320px;
        margin: 0 auto;
    }

    #header_menu {
        <if condition = "MODULE_NAME == 'Home'">
        display: flex;
        </if>
        <if condition = "MODULE_NAME == 'Shop'">
            <if condition="ACTION_NAME == 'index'">
            display: flex;
            </if>
        </if>
        flex: 1 1 100%;
    }

    #header_sign {
        /*flex: 1 1 100%;
        flex: 0 0 auto;*/
        position: absolute;
        right: 0;
    }

    .header_search {
        width: 40px;
        height: 50px;
        margin-top: 10px;
        margin-right: 10px;
        float: right;
        background-image: url("./tpl/Static/blue/images/new/search.png");
        background-repeat: no-repeat;
        background-size: auto 55%;
        cursor: pointer;
        flex: 0 0 auto;
    }
    .home_style {
        position: absolute;
        right: 1px;
        top: 10px;
    }
    #header_logo {
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

    .hamburger {
        height: 50px;
        width: 25px;
        cursor: pointer;
        margin-left: 5%;
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

    .hamburger:hover .line {
        background-color: #ffa64d;
    }

    .menu_font {
        margin-top: 16px;
        margin-left: 10px;
        font-size: 1.1em;
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

    #menu_memo {
        margin-top: 60px;
        max-width: 100%;
        width: 500px;
        position: absolute;
        background-color: #f5f5f5;
        transition: transform .4s ease;
        transform: translate3d(-150%, 0, 0);
        display: flex;
        position: fixed;
        z-index: 999999;
    }

    #menu_memo.is_open {
        transform: translate3d(0, 0, 0);
    }

    #menu_memo ul {
        width: 92%;
        margin-left: 5%;
        margin-top: 20px;
        padding: 0;
    }

    #menu_memo ul li {
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

    #menu_memo ul li:hover {
        color: #ffa52d;
        background-image: url("../tpl/Static/blue/images/new/or_arrow.png");
    }

    #menu_memo li:nth-child(1) .m_img {
        background-image: url("../tpl/Static/blue/images/new/home.png");
    }

    #menu_memo li:nth-child(2) .m_img {
        background-image: url("../tpl/Static/blue/images/new/food.png");
    }

    #menu_memo li:nth-child(3) .m_img {
        background-image: url("../tpl/Static/blue/images/new/car.png");
    }

    #menu_memo li:nth-child(4) .m_img {
        background-image: url("../tpl/Static/blue/images/new/store.png");
    }

    #menu_memo li:hover:nth-child(1) .m_img {
        background-image: url("../tpl/Static/blue/images/new/h_home.png");
    }

    #menu_memo li:hover:nth-child(2) .m_img {
        background-image: url("../tpl/Static/blue/images/new/h_food.png");
    }

    #menu_memo li:hover:nth-child(3) .m_img {
        background-image: url("../tpl/Static/blue/images/new/h_car.png");
    }

    #menu_memo li:hover:nth-child(4) .m_img {
        background-image: url("../tpl/Static/blue/images/new/h_store.png");
    }

    #menu_memo li .m_img {
        background-size: 22px 22px;
        background-repeat: no-repeat;
        background-position: left 2px;
        height: 30px;
        width: 30px;
    }

    .w_line {
        background-color: #ffffff;
        width: 100%;
        height: 5px;
        margin-top: 30px;
        margin-bottom: 30px;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
    }

    .sign_btn {
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
        margin-right: 5%;
        margin-top: 11px;
        font-size: 1.125em;
        cursor: pointer;
    }

    .user_div {
        width: 80px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        float: right;
        margin-right: 5%;
        margin-top: 15px;
        font-size: 1em;
    }

    .user_div a {
        text-decoration: none;
        color: #ffa52d;
    }

    .down_header {
        width: 100%;
        height: 60px;
        display: flex;
        max-width: 640px;
        min-width: 320px;
        margin: 0 auto;
    }

    .down_close {
        width: 10%;
        height: 100%;
        line-height: 60px;
        text-align: center;
        font-size: 1.2em;
        color: #ffa52d;
        cursor: pointer;
        flex: 0 0 auto;
    }

    .down_app_name {
        flex: 1 1 100%;
        padding-left: 10px;
        padding-top: 10px;
        font-size: 0.9em;
    }

    .down_view {
        width: 25%;
        font-size: 1.2em;
        line-height: 60px;
        color: #ffa52d;
        text-align: center;
        flex: 0 0 auto;
        cursor: pointer;
        background-image: url("./tpl/Static/blue/images/wap/download.png");
        background-size: auto 24px;
        background-repeat: no-repeat;
        background-position: center;
    }

    .down_icon {
        width: 46px;
        height: 46px;
        margin-top: 7px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        background-image: url("./tpl/Static/blue/images/new/icon.png");
        background-size: 100% 100%;
        flex: 0 0 auto;
    }

    .local_div {
        width: 50px;
        height: 50px;
        margin-top: 5px;
        margin-left: 10px;
        background-image: url("./tpl/Static/blue/images/wap/address.png");
        background-size: 70% 70%;
        background-repeat: no-repeat;
        background-position: center;
        cursor: pointer;
        flex: 0 0 auto;
    }

    #search_key {
        width: 70%;
        height: 30px;
        margin-left: 10px;
        margin-top: 5px;
        background-color: white;
        border: 0px;
        border-radius: 5px;
    }

    #search_btn {
        color: #ffffff;
        background: none;
        width: auto;
        height: 30px;
        margin-top: 5px;
        margin-right: 5px;
        float: right;
        border: 1px solid #ffffff;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
    }

    #header_address_div {
        line-height: 60px;
        font-size: 16px;
        color: gray;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        word-break: break-all;
        width: 65%;
        flex:1 1 100%;
    }

    .this_header {
        width: 100%;
        text-align: center;
        font-size: 18px;
        height: 30px;
        line-height: 30px;
        margin-top: 15px;
        position: relative;
    }

    .this_header span {
        width: 50px;
        height: 30px;
        display: -moz-inline-box;
        display: inline-block;
        -moz-transform: scaleX(-1);
        -webkit-transform: scaleX(-1);
        -o-transform: scaleX(-1);
        transform: scaleX(-1);
        background-image: url("./tpl/Static/blue/images/new/new_black_arrow.png");
        background-size: auto 20px;
        background-repeat: no-repeat;
        background-position: right center;
        position: absolute;
        left: 8%;
        cursor: pointer;
    }
    #close_button_span{
        width: 50px;
        height: 30px;
        display: -moz-inline-box;
        display: inline-block;
        -moz-transform: scaleX(-1);
        -webkit-transform: scaleX(-1);
        -o-transform: scaleX(-1);
        transform: scaleX(-1);
        background-image: url("./tpl/Static/blue/images/icon_close.png");
        background-size: auto 20px;
        background-repeat: no-repeat;
        background-position: right center;
        position: absolute;
        left: 8%;
        cursor: pointer;
    }
    #select_div{
        width: 100%;
    }
    #select_div ul{
        width: 220px;
        margin: 10px auto;
        height: 36px;
        background-color: #E5E5E5;
        border-radius: 18px;
        font-size: 0;
    }
    #select_div li{
        width: 50%;
        line-height: 36px;
        display: inline-block;
        text-align: center;
        font-size: 14px;
        border-radius: 18px;
        font-weight: bold;
        cursor: pointer;
        color: #191919;
    }
    #select_div li.active{
        background-color: #191919;
        color: white;
    }
</style>
<div class="down_header">
    <div class="down_close">X</div>
    <div class="down_icon"></div>
    <div class="down_app_name">
        <div class="name">TUTTI - Online Food Community</div>
    </div>
    <div class="down_view"></div>
</div>
<div id="tutti_header">
    <if condition="MODULE_NAME eq 'Home' and ACTION_NAME eq 'index'">
        <div id="select_div">
            <ul>
                <li class="active" data-type="0">DELIVERY</li>
                <li data-type="1">PICKUP</li>
            </ul>
        </div>
    </if>
    <div id="header_menu">
        <if condition="MODULE_NAME == 'Home'">
            <php>setcookie("path_by_what",1);</php>
            <div class="local_div" data-url="{pigcms{:U('Home/address')}"></div>
            <div id="header_address_div"></div>
            <div class="header_search"></div>
        </if>
        <if condition="MODULE_NAME == 'Shop'">
            <if condition="ACTION_NAME == 'pay_result'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V3_ORDER_RESULT')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'order_detail'">
                <php>setcookie("path_by_what",4);</php>
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('_ORDER_DETAIL_')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'confirm_order'">
                <php>setcookie("path_by_what",5);</php>
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_CART')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'index'">
                <php>setcookie("path_by_what",2);</php>
                <div class="local_div" data-url="{pigcms{:U('Shop/classic_address')}"></div>
                <div id="header_address_div"></div>
                <div class="header_search"></div>
            </if>
        </if>

        <if condition="MODULE_NAME == 'Pay'">
            <if condition="ACTION_NAME == 'check'">
                <div class="this_header">
                    <div id="close_button_span"></div>
                    {pigcms{:L('V2_PAGETITLE_CHECKOUT')}
                </div>
            </if>
        </if>

        <if condition="MODULE_NAME == 'My'">

            <if condition="ACTION_NAME == 'language'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('_LANG_TXT_')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'shop_feedback'">
                <div class="this_header">
                    {pigcms{:L('V3_ORDER_REVIEW_TITLE')}
                    <div class="header_search"></div>
                </div>
            </if>
            <if condition="ACTION_NAME == 'shop_order_list'">
                <php>setcookie("path_by_what",3);</php>
                <div class="this_header">
                    {pigcms{:L('V3_ORDER_LIST_TITLE')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'select_card'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_ACCOUNT_MENU_COUPONS')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'index'">
                <div class="this_header">
                    {pigcms{:L('V2_PAGETITLE_ACCOUNT')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'myinfo'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_PROFILE')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'adress'">
                <php>setcookie("path_by_what",6);</php>
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{$page_title}
                </div>
            </if>
            <if condition="ACTION_NAME == 'edit_adress' && $address_id==''">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_ADDRESS_ADD')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'edit_adress' && $address_id!=''">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_ADDRESS_EDIT')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'credit'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_PAYMENTMETHODS')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'edit_card' && $card_id==''">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_PAYMENTMETHODS_ADD')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'edit_card' && $card_id!=''">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_PAYMENTMETHODS_EDIT')}
                </div>
            </if>

            <if condition="ACTION_NAME == 'my_money'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_CREDITES')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'transaction'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_CREDITES_HISTORY')}
                </div>
            </if>

            <if condition="ACTION_NAME == 'username'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_NICKNAME')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'bind_user'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_PHONENUMBER')}
                </div>
            </if>

            <if condition="ACTION_NAME == 'email'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_EMAIL')}
                </div>
            </if>

            <if condition="ACTION_NAME == 'password'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_PASSWORD')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'invitation'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_PAGETITLE_INVITATION')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'coupon'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('V2_ACCOUNT_MENU_COUPONS')}
                </div>
            </if>
            <if condition="ACTION_NAME == 'privacy'">
                <div class="this_header">
                    <span id="back_button_span"></span>
                    {pigcms{:L('PRIVACY_TXT')}
                </div>
            </if>
        </if>
    </div>
    <!--div id="header_logo"></div-->

</div>
<div id="search_label">
    <input type="text" id="search_key" name="search_key" placeholder="{pigcms{:L('_SEARCH_STORE_GOODS_')}"/>
    <input type="button" name="search_btn" id="search_btn" value="Search">
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/lang.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<script>
    var keyword = "{pigcms{$keyword}";
    if(keyword != "") {
        $('#search_label').show();
        $('#search_key').val(keyword);
    }

    $('#search_label').css('margin-top', $('#tutti_header').height());

    $('#header_logo').click(function () {
        window.location.href = "{pigcms{$config.site_url}" + "/wap.php";
    });
    var is_open_menu = false;
    $('.hamburger').click(function () {
        if (is_open_menu) {
            $(this).removeClass('is-active');
            $('#menu_memo').removeClass('is_open');
        } else {
            $(this).addClass('is-active');
            $('#menu_memo').addClass('is_open');
            $('#menu_memo').height($(window).height() - 60);
        }

        is_open_menu = !is_open_menu;
    });

    var module_name = "{pigcms{:MODULE_NAME}";
    var action_name = "{pigcms{:ACTION_NAME}";

    var init_top = $('#tutti_header').offset().top;
    var init_margin = parseFloat($('#menu_memo').css('margin-top'));

    var select_height = 0;
    var record_top = 0;
    var cha_top = 0;
    var select_hide = false;

    if(module_name == "Home" && action_name == "index"){
        select_height = 60;
    }

    $('.down_close').click(function () {
        $('.down_header').hide();
        init_top = 0;
        setCookie('close_app_tip', '1', 1);
    });

    if (/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
        $('.down_header').hide();
        init_top = 0;
    }

    //$.cookie('close_app_tip',null);
    if ($.cookie('close_app_tip') == 1) {
        $('.down_header').hide();
        init_top = 0;
    }

    $(window).scroll(function () {
        var top = $(document).scrollTop();
        if ((top > 0 && top <= init_top) || top < 0) {
            $('#tutti_header').css('margin-top', -top);
            $('#menu_memo').css('margin-top', init_margin - top);
            $('#search_label').css('margin-top', -top + $('#tutti_header').height());
        }
        if (top > init_top) {
            $('#tutti_header').css('margin-top', -init_top);
            $('#menu_memo').css('margin-top', init_margin - init_top);
            $('#search_label').css('margin-top', -init_top + $('#tutti_header').height());
            if(select_height > 0) {
                cha_top += top - record_top;

                if(cha_top > 0 && select_hide) cha_top = 0;
                if(cha_top < 0 && !select_hide) cha_top = 0;

                if (cha_top > 0 && cha_top <= select_height && !select_hide) {
                    $('#tutti_header').css('margin-top', -(init_top+cha_top));
                    $('#search_label').css('margin-top', -(init_top+cha_top) + $('#tutti_header').height());
                }
                if(cha_top >= select_height && !select_hide){
                    $('#tutti_header').css('margin-top', -(init_top+select_height));
                    select_hide = true;
                }
                if(select_hide && cha_top == 0){
                    $('#tutti_header').css('margin-top', -(init_top+select_height));
                    $('#search_label').css('margin-top', -(init_top+select_height) + $('#tutti_header').height());
                }

                if (cha_top < 0 && cha_top >= -select_height && select_hide) {
                    $('#tutti_header').css('margin-top', -(init_top+select_height+cha_top));
                    $('#search_label').css('margin-top', -(init_top+select_height+cha_top) + $('#tutti_header').height());
                }
                if(cha_top <= -select_height && select_hide){
                    $('#tutti_header').css('margin-top', -init_top);
                    select_hide = false;
                }

                if(!select_hide && cha_top == 0){
                    $('#tutti_header').css('margin-top', -init_top);
                    $('#search_label').css('margin-top', -init_top + $('#tutti_header').height());
                }
            }
        }
        record_top = top;

        if (top == 0) {
            $('#tutti_header').css('margin-top', 0);
            $('#menu_memo').css('margin-top', init_margin);
            $('#search_label').css('margin-top', $('#tutti_header').height());
        }
    });
    var app_url = 'https://itunes.apple.com/us/app/tutti/id1439900347?ls=1&mt=8';
    $('.down_view').click(function () {
        if (/(android)/.test(navigator.userAgent.toLowerCase())) {
            window.open('https://play.google.com/store/apps/details?id=com.kavl.tutti.user');
        } else {
            window.open(app_url);
        }
    });
    $('.local_div').click(function () {
        window.location.href = $(this).data('url');
    });

    $('.header_search').click(function () {
        if ($('#search_label').is(":hidden")) {
            $('#search_label').show();
        } else {
            $('#search_label').hide();
        }
    });

    $('#search_label').click(function () {
        $('#search_key').focus();
    });

    $('#search_btn').click(function () {
        var keyword = $('#search_key').val();
        if (keyword == '') {
            window.location.href = "{pigcms{:U('Shop/index')}";
        } else {
            window.location.href = "{pigcms{:U('Shop/index')}" + "&key=" + keyword;
        }
    });

    $('#back_button_span').click(function () {
        <if  condition="$back_url eq ''">
            window.history.go(-1);
        <else />
            window.location.href="{pigcms{$back_url}";
        </if>
    });
    $('#close_button_span').click(function () {
        window.location.href="{pigcms{$back_url}";
    });

    function goback(){
        if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)){
            var referLink = document.createElement('a');
            referLink.href = url;
            document.body.appendChild(referLink);
            referLink.click();
        }else {
            location.href = document.referrer;
        }
    }

    $('#header_address_div').html($.cookie('userLocationName'));
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js" charset="utf-8"></script>
