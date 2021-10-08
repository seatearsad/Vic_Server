<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8;application/json" />
    <if condition="$config['site_favicon']">
        <link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
    </if>
    <if condition="$is_ios eq 0">
        <link rel="manifest" href="/manifest.json">
    </if>
    <!--title>{pigcms{$config.seo_title}</title-->
    <title>{pigcms{:L('_VIC_NAME_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
    <if condition="$config['wap_redirect']">
        <script>
            if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){

            }else{
                //window.location.href = './';
            }

            // 检测浏览器是否支持SW
            if(navigator.serviceWorker != null){
                navigator.serviceWorker.register('/sw.js')
                    .then(function(registartion){
                        console.log('支持sw:',registartion.scope)
                    }).catch(function (err) {
                    console.log('不支持sw:',err);
                })
            }else{
                console.log('SW run fail');
            }

            window.addEventListener('beforeinstallprompt', function (e) {
                e.userChoice.then(function (choiceResult) {
                    if (choiceResult.outcome === 'dismissed') {
                        //console.log('用户取消安装应用');
                        showmessage('用户取消安装应用');
                    }else{
                        //console.log('用户安装了应用');
                        showmessage('用户安装了应用');
                    }
                });
            });
        </script>
    </if>
    <include file="Public:facebook"/>
</head>
<style>
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
        /*font-family: Helvetica;*/
        font-family: Montserrat;
        -moz-osx-font-smoothing: grayscale;
        font-size: 100%;
    }
    body{
        /*background-color: #F5F5F5;*/
        color: #3f3f3f;
    }
    .main{
        width: 100%;
        height: 680px;
        background-image: url("./tpl/Static/blue/images/wap/main.png");
        background-size: cover;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
    }
    .slogan{
        color: white;
        font-size: 2.2em;
        font-weight: bold;
        text-align: center;
        position: absolute;
        width: 100%;
        padding-left: 5%;
        padding-right: 5%;
        margin-top: 200px;
        font-family: Montserrat-bold;
    }
    .search_box{
        width: 100%;
        position: absolute;
        margin-top: 300px;
        text-align: center;
    }
    .search_back{
        background-color: #ffffff;
        height: 50px;
        width: 88%;
        margin: 0px auto;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        display: flex;
    }
    .search_input{
        border: 0px;
        padding-left: 50px;
        margin-left: 5px;
        width: 85%;
        font-size: 1.0em;
        background-image: url("./tpl/Static/blue/images/new/locating.png");
        background-repeat: no-repeat;
        background-size: auto 35px;
        background-position:5px center;
    }
    .link_btn{
        width: 15%;
        background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
        background-repeat: no-repeat;
        background-size: auto 35px;
        background-position:center;
        cursor: pointer;
    }
    .down_div{
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        margin-top: 50px;
    }
    .down_app{
        text-align: center;
        margin-top: 20px;
        height: 70px;
        width: 75%;
        margin: 20px auto;
    }
    .down_app span{
        position: relative;
        float: left;
        height: 70px;
        cursor: pointer;
        width: 50%;
        background-size: 90% auto;
        background-repeat: no-repeat;
        background-position: center;
        margin: 0;
    }
    .down_app .app_icon{
        background-image: url("./tpl/Static/blue/images/new/Apple_app_store_icon_new.png");
    }
    .down_app .apk_icon{
        background-image: url("./tpl/Static/blue/images/new/AndroidButton_new.png");
    }
    .app_desc{
        width: 100%;
        background-color: #FBF0DE;
        padding-top: 10px;
    }
    .desc_all{
        /*margin-top: 30px;*/
        position: relative;
        padding-bottom: 10px;
    }
    .desc_txt{
        width: 100%;
        margin-top: 20px;
        padding: 10px 5%;
        text-align: center;
        font-weight: lighter;
    }
    .desc_img{
        width: 100%;
        height: 215px;
        margin-top: 5px;
        background-size:auto 100%;
        background-repeat: no-repeat;
        background-position: center;
        position: relative;
    }
    .app_desc .desc_all:nth-child(1) .desc_img{
        background-image: url("./tpl/Static/blue/images/new/app_new_1.png");
    }
    .app_desc .desc_all:nth-child(2) .desc_img{
        background-image: url("./tpl/Static/blue/images/new/app_new_2.png");
    }
    .app_desc .desc_all:nth-child(3) .desc_img{
        background-image: url("./tpl/Static/blue/images/new/app_new_3.png");
    }
    .desc_title{
        font-size: 22px;
        font-weight: bold;
    }
    .desc_memo{
        font-size: 16px;
        margin-top: 15px;
        font-family: Montserrat-light;
    }
    .white_line{
        padding: 40px 5%;
    }
    .all_info{
        width: 100%;
        margin-top: 200px;
        height: 230px;
        display: flex;
        background-color: #FBF0DE;
    }
    .all_info .info_list{
        width: 90%;
        margin: 0 5% 50px 5% ;
        height: 400px;
        background-color: #ffffff;
        position: relative;
    }
    .info_txt{
        width: 100%;
        position: absolute;
        bottom: 70px;
        padding: 10px 10px;
        text-align: center;
    }
    .info_btn{
        position: absolute;
        left: 30px;
        right: 30px;
        bottom: 20px;
        height:40px;
        text-align: center;
        background-color: #ffa52d;
        line-height: 40px;
        -moz-border-radius: 5px;
        -webkit-border-radius: 5px;
        border-radius: 5px;
        color: #ffffff;
        font-weight: bold;
        font-size: 22px;
        cursor: pointer;
    }
    .food_comm{
        width: 100%;
        height: 260px;
        background-image: url("./tpl/Static/blue/images/new/food_community.jpg");
        background-size: 100% auto;
        background-repeat: no-repeat;
    }
    .info_courier{
        width: 100%;
        height: 260px;
        background-image: url("./tpl/Static/blue/images/new/courier.jpg");
        background-size: 100% auto;
        background-repeat: no-repeat;
    }
    .info_partner{
        width: 100%;
        height: 260px;
        background-image: url("./tpl/Static/blue/images/new/partner.jpg");
        background-size: 100% auto;
        background-repeat: no-repeat;
    }
    .ready_order{
        text-align: center;
        font-size: 30px;
        font-weight: bold;
    }
    .down_icon{
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
    .ten{
        width: 100%;
        height: 30px;
    }
    .three_div .three_memo_div{
        padding: 30px 5%;
        flex: 1 1 100%;
    }
    .three_memo_div div{
        text-align: center;
    }
    .memo_img{
        width: 200px;
        height: 200px;
        margin: 0px auto 20px auto;
        background-size: 100% auto;
    }
    .three_div .three_memo_div:nth-child(1) .memo_img{
        background-image: url("./tpl/Static/blue/images/new/memo_1.png");
    }
    .three_div .three_memo_div:nth-child(2) .memo_img{
        background-image: url("./tpl/Static/blue/images/new/memo_2.png");
    }
    .three_div .three_memo_div:nth-child(3) .memo_img{
        background-image: url("./tpl/Static/blue/images/new/memo_3.png");
    }
    .memo_title{
        color: black;
        font-size: 22px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .memo_sub{
        margin-top: 10px;
        font-size: 16px;
        font-family: Montserrat-light;
    }
    .desc_white{
        position: absolute;
        width: 100%;
        height: 25px;
        background-color: white;
        display: inline-block;
        bottom: 0px;
    }
    .down_img{
        background-image: url("./tpl/Static/blue/images/new/downloadapp.png");
        background-size:110% auto;
        background-repeat: no-repeat;
        width: 50%;
        margin-top: -150px;
    }
    .down_demo{
        margin-top: -120px;
        width: 50%;
        padding: 0px 3%;
    }
    .down_title{
        font-weight: bold;
        font-size: 22px;
        color: black;
        display: inline-block;
    }
    .icon_app{
        display: block;
        width: 80px;
        height: 80px;
        border-radius: 15px;
        background-image: url("./tpl/Static/blue/images/new/icon.png");
        background-size:auto 100%;
        background-repeat: no-repeat;
        margin: 40px auto;
    }
    .download_input{
        width: 60%;
        height: 60px;
        border-radius: 10px;
        border: 0px;
        padding: 5px 10px;
        font-size: 16px;
        display: inline-block;
    }
    .send_btn{
        width: 37%;
        margin-left: 1%;
        text-align: center;
        background: #ffa52d;
        color: white;
        border-radius: 10px;
        height: 60px;
        line-height: 60px;
        display: inline-block;
        vertical-align:top;
        font-weight: bold;
    }
    .serving_title{
        font-size: 24px;
        font-weight: bold;
        color: black;
    }
    .city_list{
        margin: 15px auto;
        padding: 0px;
    }
    .city_list li{
        list-style: none;
        display: inline-block;
        width: 48%;
        height: 60px;
        line-height: 60px;
        background-position: center left 10px;
        background-size: auto 80%;
        background-repeat: no-repeat;
        padding-left: 63px;
        margin: 10px auto;
        font-size: 16px;
    }
    .city_list li span{
        height: 60px;
        display: inline-block;
    }
    .city_list li:nth-child(1){
        background-image: url("./tpl/Static/blue/images/new/city/DT_Vancouver.png");
    }
    .city_list li:nth-child(2){
        background-image: url("./tpl/Static/blue/images/new/city/Victoria.png");
    }
    .city_list li:nth-child(3){
        background-image: url("./tpl/Static/blue/images/new/city/Kelowna.png");
    }
    .city_list li:nth-child(4){
        background-image: url("./tpl/Static/blue/images/new/city/Kamloops.png");
    }
    .city_list li:nth-child(5){
        background-image: url("./tpl/Static/blue/images/new/city/Nanaimo.png");
    }
    .city_list li:nth-child(6){
        background-image: url("./tpl/Static/blue/images/new/city/Chilliwack.png");
    }
    .city_list li:nth-child(7){
        background-image: url("./tpl/Static/blue/images/new/city/Maple_Ridge.png");
    }
    .city_list li:nth-child(8){
        background-image: url("./tpl/Static/blue/images/new/city/Squamish.png");
    }
    .city_list li:nth-child(9){
        background-image: url("./tpl/Static/blue/images/new/city/More.png");
    }
    .ready_div{
        width: 100%;
        padding: 30px 0;
        background-image: url("./tpl/Static/blue/images/new/ready_background.png");
        background-size: cover;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
    }
    .ready_img{
        background-image: url('./tpl/Wap/pure/static/img/staff_menu/login_img.png');
        height: 90px;
        background-size:contain;
        background-repeat: no-repeat;
        background-position: center center;
        width: 90%;
        margin: -10px auto;
    }
    .become_div{
        width: 90%;
        margin: 0 auto;
    }
    .become_div div{
        width: 100%;
        display: inline-block;
        height: 500px;
        position: relative;
    }
    .become_div .become_img{
        width: 100%;
        height: 300px;
        background-position:center center;
        background-size: auto 130%;
        background-repeat: no-repeat;
    }
    .become_div div:nth-child(1) .become_img{
        background-image: url("./tpl/Static/blue/images/new/partner.png");
    }
    .become_div div:nth-child(2) .become_img{
        background-image: url("./tpl/Static/blue/images/new/courier.png");
    }

    .become_div .become_title{
        width: 100%;
        margin: 10px auto;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        color: black;
        height: auto;
    }

    .become_div .become_desc{
        width: 100%;
        margin: 10px auto;
        text-align: center;
        font-size: 18px;
        color: black;
        height: auto;
        line-height: 1.5;
        font-weight: lighter;
    }
    .become_div .become_btn{
        width: 130px;
        background-color: #ffa52d;
        border-radius: 15px;
        height: 40px;
        line-height: 40px;
        margin: 10px auto;
        text-align: center;
        color: white;
        font-size: 18px;
        display: block;
    }
    .become_btn a{
        display: block;
        color: white;
        text-decoration: none;
    }
    .code_div{
        border: 1px solid grey;
        border-radius: 3px;
        height: 30px;
        padding: 0 3px;
    }
</style>
<body>
<script>
    var app_name = 'TUTTI - Online Food Community';
    var app_url = 'https://itunes.apple.com/us/app/tutti/id1439900347?ls=1&mt=8';
</script>
<include file="Public:header"/>
<div class="ten"></div>
<div class="main">
    <div class="slogan">BC’S FAVOURITE DELIVERY APP</div>
    <div class="search_box">
        <div class="search_back">
            <input type="text" placeholder="{pigcms{:L('ENTERADDRESS')}" id="address" class="search_input" name="search_word">
            <div class="link_btn"></div>
        </div>
    </div>
</div>
<!--div class="down_div">
    DOWNLOAD THE TUTTI APP
</div>
<div class="down_app">
    <span class="app_icon"></span>
    <span class="apk_icon"></span>
</div-->
<div class="three_div">
    <div class="three_memo_div">
        <div class="memo_img"></div>
        <div class="memo_title">{pigcms{:L('2LOACALREST')}</div>
        <div class="memo_sub">{pigcms{:L('2LOACALRESTDES')}</div>
    </div>
    <div class="three_memo_div">
        <div class="memo_img"></div>
        <div class="memo_title">{pigcms{:L('2LIQUOR')}</div>
        <div class="memo_sub">{pigcms{:L('2LIQUORDES')}</div>
    </div>
    <div class="three_memo_div">
        <div class="memo_img"></div>
        <div class="memo_title">{pigcms{:L('2GROCERY')}</div>
        <div class="memo_sub">{pigcms{:L('2GROCERYDES')}</div>
    </div>
</div>
<div class="app_desc">
    <div class="desc_all">
        <div class="desc_white"></div>
        <div class="desc_txt">
            <div class="desc_title">
                {pigcms{:L('3FAVREST')}
            </div>
            <div class="desc_memo">
                {pigcms{:L('3FAVRESTDES')}
            </div>
        </div>
        <div class="desc_img"></div>
    </div>
    <div class="desc_all">
        <div class="desc_white"></div>
        <div class="desc_txt">
            <div class="desc_title">
                {pigcms{:L('3VIEWMENU')}
            </div>
            <div class="desc_memo">
                {pigcms{:L('3VIEWMENUDES')}
            </div>
        </div>
        <div class="desc_img"></div>
    </div>
    <div class="desc_all">
        <div class="desc_white"></div>
        <div class="desc_txt">
            <div class="desc_title">
                {pigcms{:L('3TRACK')}
            </div>
            <div class="desc_memo">
                {pigcms{:L('3TRACKDES')}
            </div>
        </div>
        <div class="desc_img"></div>
    </div>
</div>
<div class="all_info" id="download_div">
    <div class="down_img"></div>
    <div class="down_demo">
        <div class="down_title">{pigcms{:L('3DOWNLOADAPP')}</div>
        <div class="icon_app"></div>
        <div style="margin-top: 20px;font-size: 14px;line-height: 20px;font-family: Montserrat-light;">
            {pigcms{:L('3DOWNLOADTEXT')}
        </div>
    </div>
</div>
<div style="background-color: #FBF0DE;padding: 10px 5%">
    <div style="font-size: 15px;margin-bottom: 15px">
        Enter your cellphone number to download our app
    </div>
    <input type="text" placeholder="{pigcms{:L('3DOWNLOADAPPDES')}" name="download_input" class="download_input">
    <span class="send_btn">{pigcms{:L('3GETMESSAGE')}</span>
    <div class="down_app">
        <span class="app_icon"></span>
        <span class="apk_icon"></span>
    </div>
</div>
<div class="white_line">
    <div class="serving_title">{pigcms{:L('4CITIES')}</div>
    <ul class="city_list">
        <li style="font-size: 14px">
            Vancouver&nbsp;DT
        </li>
        <li>
            <span>Victoria</span>
        </li>
        <li>Kelowna</li>
        <li>Kamloops</li>
        <li>Nanaimo</li>
        <li>Chilliwack</li>
        <li style="font-size: 14px">Maple&nbsp;Ridge</li>
        <li>Squamish</li>
        <li>{pigcms{:L('4MORE')}</li>
    </ul>
</div>
<div class="ready_div">
    <div class="ready_order">
        {pigcms{:L('5READYTOORDER')}
    </div>
    <div class="search_box" style="margin-top: 30px;margin-bottom: 30px;position: relative">
        <div class="search_back" style="width: 90%">
            <input type="text" placeholder="{pigcms{:L('ENTERADDRESS')}" id="address_bottom" class="search_input" name="search_word">
            <span class="link_btn"></span>
        </div>
    </div>
    <div class="ready_img"></div>
</div>
<div class="become_div">
    <div>
        <div class="become_img"></div>
        <div class="become_title">{pigcms{:L('6PARTNER')}</div>
        <div class="become_desc">
            {pigcms{:L('6PARTNERDES')}
        </div>
        <div class="become_btn"><a href="{pigcms{$config.site_url}/wap.php?g=Wap&c=Index&a=partner">Learn More</a></div>
    </div>
    <div>
        <div class="become_img"></div>
        <div class="become_title">{pigcms{:L('6COURIER')}</div>
        <div class="become_desc">
            {pigcms{:L('6COURIERDES')}
        </div>
        <div class="become_btn"><a href="{pigcms{$config.site_url}/wap.php?g=Wap&c=Index&a=courier">Learn More</a></div>
    </div>
</div>
<include file="Public:footer"/>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_public}layer/layer.m.js"></script>
<script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<script>
    var desc_num = 3;
    var curr_num = 1;

    changeDesc();

    function changeDesc() {
        var i = 1;
        curr_num = curr_num == 0 ? desc_num : curr_num;
        curr_num = curr_num > desc_num ? curr_num - desc_num : curr_num;

        var next_num = curr_num + 1;
        next_num = next_num > desc_num ? 1 : next_num;

        var pro_num = curr_num - 1;
        pro_num = pro_num == 0 ? desc_num : pro_num;
        $('.desc_center').find('.desc_all').each(function () {
            if(i == curr_num){
                $(this).css('opacity',1);
                $(this).attr('class','desc_all desc_curr');
            }
            else if(i == next_num){
                $(this).css('opacity',0);
                $(this).attr('class','desc_all desc_next');
            }
            else if(i == pro_num){
                $(this).css('opacity',0);
                $(this).attr('class','desc_all desc_pro');
            }else{
                $(this).css('opacity',0);
                $(this).attr('class','desc_all');
            }

            i++;
        });
    }

    $('.desc_right').click(function () {
        //if(desc_num > curr_num){
        curr_num += 1;
        changeDesc();
        //}
    });
    $('.desc_left').click(function () {
        //if(curr_num > 1){
        curr_num -= 1;
        changeDesc();
        //}
    });
    var i = 1;
    $('.all_info').find('.info_btn').each(function () {
        var link = '';
        switch (i){
            case 1:
                link = './wap.php';
                break;
            case 2:
                link = "{pigcms{:U('Index/courier')}";
                break;
            case 3:
                link = "{pigcms{:U('Index/partner')}";
                break;
            default:
                break;
        }
        $(this).click(function () {
            window.location.href = link;
        });
        i++;
    });

    $('.link_btn').click(function () {
        window.location.href = './wap.php';
    });

    $('#address').focus(function () {
        initAutocomplete();
    });

    $('#address_bottom').focus(function () {
        initAutocompleteBottom();
    });

    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), {types: ['geocode'],componentRestrictions: {country: ['ca']}});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function initAutocompleteBottom() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_bottom'), {types: ['geocode'],componentRestrictions: {country: ['ca']}});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();

        $.cookie('shop_select_address', place.formatted_address,{expires:700,path:"/"});
        $.cookie('shop_select_lng', place.geometry.location.lng(),{expires:700,path:"/"});
        $.cookie('shop_select_lat', place.geometry.location.lat(),{expires:700,path:"/"});
        //wap
        $.cookie('userLocationName', place.formatted_address,{expires:700,path:"/"});
        $.cookie('userLocationLong',place.geometry.location.lng(),{expires:700,path:'/'});
        $.cookie('userLocationLat',place.geometry.location.lat(),{expires:700,path:'/'});

        var add_com = place.address_components;
        var is_get_city = false;
        for(var i=0;i<add_com.length;i++){
            if(add_com[i]['types'][0] == 'locality'){
                is_get_city = true;
                var city_name = add_com[i]['long_name'];
                $.post("{pigcms{:U('Index/ajax_city_name')}",{city_name:city_name},function(result){
                    if (result.error == 1){
                        //$("input[name='city_id']").val(0);
                    }else{
                        //$("input[name='city_id']").val(result['info']['city_id']);
                        $.cookie('userLocationCity', result['info']['city_id'],{expires:700,path:"/"});
                    }
                    window.location.href = './wap.php';
                },'JSON');
            }
        }
    }
    function checkPhone(phone) {
        if(!/^\d{10,}$/.test(phone)){
            return false;
        }
        return true;
    }

    $(".send_btn").click(function () {
        var phone = $(".download_input").val();
        if(!checkPhone(phone)){
            layer.open({
                type:3,
                title: [' ', 'border:0px;height:30px'],
                content: "{pigcms{:L('_B_LOGIN_ENTERGOODNO_')}",
                time: 2
            });
        }else {
            layer.open({
                type: 3,
                title: [' ', 'border:0px;height:30px'],
                content: '<div style="width: 320px;">Please verify you\'re not a robot.</div>' +
                '<div style="margin-top: 10px"><input type="text" name="code" placeholder="Enter Code" class="code_div">' +
                '<img style="vertical-align: top;margin-left: 10px;margin-top: 3px;" src="/admin.php?g=System&c=Login&a=verify" id="verifyImg" onclick="fleshVerify(\'/admin.php?g=System&c=Login&a=verify\')" title="Refresh" alt="Refresh">' +
                '<img style="vertical-align: top;margin-left: 5px;margin-top: 3px;" src="/tpl/Static/blue/images/new/icon_refresh.png" onclick="fleshVerify(\'/admin.php?g=System&c=Login&a=verify\')" width=25>' +
                '</div>',
                btn: ["Submit & Send Message"],
                yes: function (index) {
                    var code = $(".code_div").val();
                    if (code == "") alert("Please Input Code");
                    else {
                        $.post("/index.php?g=Index&c=Index&a=send_message", {'code': code,'phone':phone}, function (result) {
                            if(result.error == 0){
                                layer.close(index);
                                layer.open({
                                    type:2,
                                    title: [' ', 'border:0px;height:30px'],
                                    content: result.msg,
                                    time: 2
                                });
                            }else{
                                fleshVerify('/admin.php?g=System&c=Login&a=verify');
                                alert(result.msg);
                            }
                        },"json");
                    }
                }
            });
        }
    });

    function fleshVerify(url){
        var time = new Date().getTime();
        $('#verifyImg').attr('src',url+"&time="+time);
    }
</script>
</html>
