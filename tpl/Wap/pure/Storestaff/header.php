<style>
    body{
        max-width: 100%;
    }
    #tutti_header{
        width: 100%;
        height: 60px;
        display: flex;
        background-color: #ffa52d;
        position: fixed;
        z-index: 999;
        left: 0;
        top:0;
    }
    #header_menu{
        display: flex;
        flex: 1 1 100%;
    }
    #header_sign{
        flex: 1 1 100%;
    }

    #header_sign a{
        margin-right: 5%;
        margin-top: 11px;
        border-radius: 3px;
    }

    .hamburger{
        height: 50px;
        width: 25px;
        cursor: pointer;
        margin-left: 20px;
        margin-top: 15px;
    }
    .hamburger .line {
        width: 25px;
        height: 3px;
        background-color: #ffffff;
        display: block;
        margin: 5px auto;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        -webkit-transition: all 0.3s ease-in-out;
        -o-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }
    .menu_font{
        margin: 16px auto;
        font-size: 0.95em;
        height: 30px;
        line-height: 30px;
        overflow: hidden;
        white-space:nowrap;
        text-overflow: ellipsis;
        width: 240px;
        padding: 0 10px;
        text-align: center;
        color: white;
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
        top:60px;
        max-width: 100%;
        width: 500px;
        background-color: #ffcc88;
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
    #menu_memo ul li span{
        font-weight: bold;
    }

    .menu_font span{
        padding: 3px 5px;
        color: white;
        background-color: #ffa52d;
        border-radius: 2px;
    }
    .menu_font .off_shift{
        background-color: grey;
    }
    #menu_memo #user_hi{
        width: 90%;
        height: 30px;
        margin-left: 5%;
        padding-top: 5px;
        position: inherit;
        border-bottom: 2px solid #f5f5f5;
    }
    .startOrder{color: #fff;float: right;background: green;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
    .stopOrder{color: #000;float: right;background: #ccc;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}

    #menu_bottom{
        width: 90%;
        left: 5%;
        position: absolute;
        bottom: 20px;
    }
    #setting{
        color: #ffa52d;
        float: left;
        cursor: pointer;
        background-image: url("{pigcms{$static_path}img/deliver_set.png");
        background-size: auto 100%;
        background-repeat: no-repeat;
        background-position: left;
        padding-left: 20px;
    }
    #logout{
        float: right;
        cursor: pointer;
        color: #3f3f3f;
        font-weight: bold;
        line-height: 30px;
        padding-left: 30px;
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-9.png');
        background-repeat: no-repeat;
        background-position: center left;
        background-size:auto 80%;
    }
    #lang_div{
        color: #3f3f3f;
        line-height: 30px;
        padding-left: 30px;
        float: left;
        font-weight: bold;
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-7.png');
        background-repeat: no-repeat;
        background-position: center left;
        background-size:auto 80%;
    }
    #lang_div span{
        cursor: pointer;
    }
    #lang_div span.act{
        color: #ffa52d;
    }
    .m_img{
        width: 35px;
        background-repeat: no-repeat;
        background-position: center left;
        background-size:auto 70%;
    }
    #menu_home .m_img{
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-1.png');
    }
    #menu_shift .m_img{
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-2.png');
    }
    #menu_stat .m_img{
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-3.png');
    }
    #menu_order .m_img{
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-5.png');
    }
    #menu_inst .m_img{
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-4.png');
    }
    #menu_account .m_img{
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-6.png');
    }
    #menu_print .m_img{
        background-image:url('{pigcms{$static_path}img/staff_menu/menu-8.png');
    }

    .print_memo{
        margin-left: 35px;
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
            {pigcms{$store.name}
        </div>
    </div>
</div>
<div id="menu_memo">
    <ul>
        <li id="menu_home">
            <span class="m_img"> </span>
            <span>Delivery Dashboard</span>
        </li>
        <li id="menu_shift">
            <span class="m_img"> </span>
            <span>Add An Order</span>
        </li>
        <li id="menu_stat">
            <span class="m_img"> </span>
            <span>Menu/Product Management</span>
        </li>
        <li id="menu_inst">
            <span class="m_img"> </span>
            <span>Account Management</span>
        </li>
        <li id="menu_order">
            <span class="m_img"> </span>
            <span>Order History & Statistics</span>
        </li>
        <!--li id="menu_account">
            <span class="m_img"> </span>
            <span>Merchant Support</span>
        </li-->
        <li id="menu_print">
            <span class="m_img"> </span>
            <span>Printing Settings</span>
        </li>
        <div class="print_memo" id="use_status">123</div>
        <div class="print_memo" id="printer_name">456</div>
    </ul>
    <div id="menu_bottom">
        <!--div id="setting">Setting</div-->
        <div id="lang_div">
            <span data-type="en-us" class="act">EN</span>
            <label> / </label>
            <span data-type="zh-cn"">CH</span>
        </div>
        <div id="logout">Log Out</div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script>
    if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()) || /(tuttipartner)/.test(navigator.userAgent.toLowerCase())){
        $('#menu_print').show();
        $('.print_memo').show();
    }else{
        $('#menu_print').hide();
        $('.print_memo').hide();
    }

    function pushPrinterNameAndUse(name,use) {
        var is_use = 'NO';
        if(use == '1') is_use = 'YES';

        $('#use_status').html(is_use);
        $('#printer_name').html(name);
    }

    if(typeof (window.linkJs) != 'undefined') {
        var printer = window.linkJs.updatePrinterUseAndName();
        var allStr = printer.split("|");

        pushPrinterNameAndUse(allStr[1],allStr[0]);
    }

    $('#menu_print,.print_memo').click(function () {
        //alert('Print Setting');
        if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()))
            window.linkJs.gotoPrinter();
        else
            window.webkit.messageHandlers.operatePrinter.postMessage([0]);
    });

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

    $('#menu_shift').click(function () {
        location.href = "{pigcms{:U('Storestaff/add_shop_order')}";
    });
    $('#menu_stat').click(function () {
        location.href = "{pigcms{:U('Storestaff/manage_product')}";
    });
    $('#menu_order').click(function () {
        location.href = "{pigcms{:U('Storestaff/orders')}";
    });
    $('#menu_inst').click(function () {
        location.href = "{pigcms{:U('Storestaff/manage_info')}";
    });
    $('#menu_account').click(function () {
        //location.href = "{pigcms{:U('Storestaff/account')}";
    });
    // $('#menu_support').click(function () {
    //     location.href = "{pigcms{:U('Storestaff/support')}";
    // });

    var init_top = $('#tutti_header').offset().top;
    var init_margin = parseFloat($('#menu_memo').css('margin-top'));


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
    $('#logout').click(function () {
        layer.open({
            title:['Reminder','background-color:#ffa52d;color:#fff;'],
            content:'Are you sure about logging out?',
            btn: ['Yes', 'No'],
            shadeClose: false,
            yes: function(){
                window.parent.location = "{pigcms{:U('Storestaff/logout')}";
            }
        });
    });

    $('#menu_home').click(function () {
        location.href = "{pigcms{:U('Storestaff/index')}";
    });
    $("#setting").click(function () {

    });

    var language = "{pigcms{:C('DEFAULT_LANG')}";
    setLanguage(language);
    function setLanguage(language){
        this.language = language;
        setCookie('lang',language,30);
        $('#lang_div').find('span').each(function () {
            if($(this).data('type') == language)
                $(this).addClass('act');
            else
                $(this).removeClass('act');
        });
    }

    $('#lang_div').find('span').each(function () {
        $(this).click(function () {
            if($(this).data('type') != language){
                setLanguage($(this).data('type'));
                location.reload();
            }
        });
    });
</script>