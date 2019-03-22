<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <if condition="$config['site_favicon']">
        <link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
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

        </script>
    </if>
</head>
<style>
    *{
        margin: 0px;
        box-sizing: border-box;
        font-family: Helvetica;
        -moz-osx-font-smoothing: grayscale;
        font-size: 100%;
    }
    body{
        background-color: #F5F5F5;
        color: #3f3f3f;
    }
    .main{
        width: 100%;
        height: 600px;
        background-image: url("./tpl/Static/blue/images/wap/main.jpg");
        background-size: cover;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
    }
    .slogan{
        color: #ffa52d;
        font-size: 2.4em;
        font-weight: bold;
        text-align: center;
        position: absolute;
        width: 100%;
        padding-left: 5%;
        padding-right: 5%;
        margin-top: 200px;
    }
    .search_box{
        width: 100%;
        position: absolute;
        margin-top: 330px;
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
        font-size: 1.2em;
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
        width: 100%;
    }
    .down_app span{
        position: relative;
        float: left;
        height: 70px;
        cursor: pointer;
        width: 50%;
        background-size: auto 80%;
        background-repeat: no-repeat;
        background-position: center;
    }
    .down_app .app_icon{
        background-image: url("./tpl/Static/blue/images/new/Apple_app_store_icon.png");
    }
    .down_app .apk_icon{
        background-image: url("./tpl/Static/blue/images/new/AndroidButton.png");
    }
    .app_desc{
        width: 100%;
        background-color: #ffffff;
    }
    .desc_all{
        margin-top: 30px;
    }
    .desc_txt{
        width: 100%;
        height: 220px;
        margin-top: 20px;
        background-color: #ffffff;
        padding-left: 32%;
        padding-top: 60px;
        padding-right: 2%;
    }
    .desc_img{
        width: 27%;
        height: 215px;
        margin-top: 5px;
        background-image: url("./tpl/Static/blue/images/new/iphone.png");
        background-size:100% auto ;
        background-repeat: no-repeat;
        position: absolute;
        margin-left: 2%;
    }
    .desc_title{
        font-size: 1em;
        font-weight: bold;
    }
    .desc_memo{
        font-size: 0.8em;
        margin-top: 10px;
    }
    .white_line{
        width: 90%;
        height: 3px;
        margin:50px auto;
        -moz-border-radius: 3px;
        -webkit-border-radius: 3px;
        border-radius: 3px;
        background-color: #ffffff;
    }
    .all_info{
        width: 100%;
        margin: 0px auto;
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
        font-size: 2em;
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
</style>
<body>
<script>
    var app_name = 'TUTTI - Online Food Community';
</script>
<include file="Public:header"/>
<div class="ten"></div>
<div class="main">
    <div class="slogan">Your Online Food Community</div>
    <div class="search_box">
        <div class="search_back">
            <input type="text" placeholder="Enter your address" id="address" class="search_input" name="search_word">
            <div class="link_btn"></div>
        </div>
    </div>
</div>
<div class="down_div">
    DOWNLOAD THE TUTTI APP
</div>
<div class="down_app">
    <span class="app_icon"></span>
    <span class="apk_icon"></span>
</div>
<div class="app_desc">
    <div class="desc_all">
        <div class="desc_img"></div>
        <div class="desc_txt">
            <div class="desc_title">
                Various categories to choose from and get you always connected
            </div>
            <div class="desc_memo">
                food delivery, local services and activities make your life so fun and exciting, best user experience.
            </div>
        </div>
    </div>
    <div class="desc_all">
        <div class="desc_txt" style="width:70%;padding-left:3%;text-align: right">
            <div class="desc_title">
                Various categories to choose from and get you always connected
            </div>
            <div class="desc_memo">
                food delivery, local services and activities make your life so fun and exciting, best user experience.
            </div>
        </div>
        <div class="desc_img" style="margin-left:1%;right: 2%;margin-top: -220px"></div>
    </div>
    <div class="desc_all">
        <div class="desc_img"></div>
        <div class="desc_txt">
            <div class="desc_title">
                Various categories to choose from and get you always connected
            </div>
            <div class="desc_memo">
                food delivery, local services and activities make your life so fun and exciting, best user experience.
            </div>
        </div>
    </div>
</div>
<div class="white_line"></div>
<div class="all_info">
    <div class="info_list">
        <div class="food_comm"></div>
        <div class="info_txt">
            {pigcms{:L('_NEW_INDEX_FOOD_')}
        </div>
        <div class="info_btn">
            {pigcms{:L('_NEW_FOOD_COMM_')}
        </div>
    </div>
    <div class="info_list">
        <div class="info_courier"></div>
        <div class="info_txt">
            {pigcms{:L('_NEW_INDEX_COURIER_')}
        </div>
        <div class="info_btn">
            {pigcms{:L('_NEW_BECOME_COURIER_')}
        </div>
    </div>
    <div class="info_list">
        <div class="info_partner"></div>
        <div class="info_txt">
            {pigcms{:L('_NEW_INDEX_PARTNER_')}
        </div>
        <div class="info_btn">
            {pigcms{:L('_NEW_BECOME_PARTNER_')}
        </div>
    </div>
</div>
<div class="white_line"></div>
<div>
    <div class="ready_order">
        Ready To Order?
    </div>
    <div class="search_box" style="margin-top: 30px;margin-bottom: 10px;position: relative">
        <div class="search_back">
            <input type="text" placeholder="Enter your address" id="address_bottom" class="search_input" name="search_word">
            <div class="link_btn"></div>
        </div>
    </div>
</div>
<include file="Public:footer"/>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en" async defer></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_public}layer/layer.m.js"></script>
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
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function initAutocompleteBottom() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address_bottom'), {types: ['geocode']});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        $.cookie('shop_select_address', place.formatted_address,{expires:700,path:"/"});
        $.cookie('shop_select_lng', place.geometry.location.lng(),{expires:700,path:"/"});
        $.cookie('shop_select_lat', place.geometry.location.lat(),{expires:700,path:"/"});
    }
</script>
</html>
