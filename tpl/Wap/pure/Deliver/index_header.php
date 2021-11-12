<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
<style>
    body{
        max-width: 100%;
        background-color: #F8F8F8;
    }
    #tutti_header{
        width: 100%;
        height: 60px;
        display: flex;
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
        flex: 1 1 50%;
    }
    #header_sign div{
        display: inline-block;
        float: left;
        margin-right: 5px;
        text-align: center;
    }

    #header_sign a{
        margin-right: 5%;
        margin-top: 11px;
        border-radius: 3px;
    }

    .hamburger,.refresh{
        height: 48px;
        width: 48px;
        cursor: pointer;
        margin-left: 5%;
        margin-top: 10px;
        padding-top: 10px;
        box-sizing: border-box;
        background-color: white;
        border-radius: 24px;
    }
    .hamburger .line {
        width: 25px;
        height: 3px;
        background-color: #294068;
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
        background-color: #294068;
    }
    .menu_font{
        font-size: 0.95em;
        line-height: 48px;
        background-color: #294068;
        color: white;
        display: inline;
        margin: 10px 15px 5px 15px;
        padding: 0 20px;
        border-radius: 24px;
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
        background-color: #294068;
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
        margin-top: 60px;
        padding: 0;
    }
    #menu_memo ul li{
        list-style-type: none;
        height: 40px;
        line-height: 25px;
        cursor: pointer;
        background-size: auto 16px;
        background-repeat: no-repeat;
        background-position: right;
        display: flex;
        margin-top: 5px;
        color: white;
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
        padding-top: 15px;
        position: inherit;
        color: white;
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
        color: white;
        float: right;
        cursor: pointer;
        line-height: 30px;
    }
    #lang_div{
        color: white;
        line-height: 30px;
        padding-left: 30px;
        float: left;
        background-image:url('{pigcms{$static_path}img/language.png');
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
    .material-icons{
        width: 35px;
    }
</style>

<div id="tutti_header">
    <div id="header_menu">
        <div id="hamburger-1" class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
        <if condition="ACTION_NAME eq 'index' OR ACTION_NAME eq 'process'">
        <div class="menu_font">Status:
            <if condition="$deliver_session['work_status'] eq '1'">
                <span class="off_shift">{pigcms{:L('_ND_OFFSHIFT_')}</span>
            <else />
                <span class="on_shift">{pigcms{:L('_ND_ONSHIFT_')}</span>
            </if>
        </div>
        </if>
    </div>

    <div id="header_sign">
        <div class="refresh" id="refresh_btn">
            <span class="material-icons title_icon" style="color: #294068">restart_alt</span>
        </div>
        <div class="refresh">
            <span class="material-icons title_icon" style="color: #294068">more_vert</span>
        </div>
    </div>
</div>
<div id="menu_memo">
    <div id="user_hi">
        {pigcms{:replace_lang_str(L('_ND_HI_'),$deliver_session['name'])}
    </div>
    <ul>
        <li id="menu_home">
            <span class="material-icons">home</span>
            <span>{pigcms{:L('_ND_HOME_')}</span>
        </li>
        <li id="menu_shift">
            <span class="material-icons">event</span>
            <span>{pigcms{:L('_ND_MYSHIFTS_')}</span>
        </li>
        <li id="menu_stat">
            <span class="material-icons">account_balance_wallet</span>
            <span>{pigcms{:L('_ND_MYSTATISTICS_')}</span>
        </li>
        <li id="menu_order">
            <span class="material-icons">watch_later</span>
            <span>{pigcms{:L('_ND_ORDERHISTORY_')}</span>
        </li>
        <li id="menu_inst">
            <span class="material-icons">error</span>
            <span>{pigcms{:L('_ND_INSANN_')}</span>
        </li>
        <li id="menu_account">
            <span class="material-icons">account_circle</span>
            <span>{pigcms{:L('_ND_MYACCOUNT_')}</span>
        </li>
        <li id="menu_support">
            <span class="material-icons">help</span>
            <span>{pigcms{:L('_ND_COURIERSUPPORT_')}</span>
        </li>
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
        location.href = "{pigcms{:U('Deliver/schedule')}";
    });
    $('#menu_stat').click(function () {
        location.href = "{pigcms{:U('Deliver/statistics')}";
    });
    $('#menu_order').click(function () {
        location.href = "{pigcms{:U('Deliver/orders')}";
    });
    $('#menu_inst').click(function () {
        location.href = "{pigcms{:U('Deliver/inst')}";
    });
    $('#menu_account').click(function () {
        location.href = "{pigcms{:U('Deliver/account')}";
    });
    $('#menu_support').click(function () {
        location.href = "{pigcms{:U('Deliver/support')}";
    });

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
                window.parent.location = "{pigcms{:U('Deliver/logout')}";
            }
        });
    });

    $('#menu_home').click(function () {
        location.href = "{pigcms{:U('Deliver/index')}";
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

    $('#refresh_btn').click(function () {
        window.location.reload();
    });
</script>