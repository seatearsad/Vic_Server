<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<if condition="$config['site_favicon']">
			<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
		</if>
		<!--title>{pigcms{$config.seo_title}</title-->
        <title>{pigcms{:L('_VIC_NAME_')}</title>
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
		<meta name="description" content="{pigcms{$config.seo_description}" />
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script src="{pigcms{$static_path}js/jquery.nav.js"></script>
		<script src="{pigcms{$static_path}js/navfix.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}js/index.activity.js"></script>
		<if condition="$config['wap_redirect']">
			<script>
				if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){
					<if condition="$config['wap_redirect'] eq 1">
						window.location.href = './wap';
					<else/>
						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){
							window.location.href = './wap';
						}
					</if>
				}

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
        }
        body{
            min-width: 1024px;
            background-color: #FFFFFF;
            color: #3f3f3f;
        }
        .main{
            width: 100%;
            height: 700px;
            background-image: url("./tpl/Static/blue/images/new/main.png");
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            margin: 10px auto;
            position: relative;
            /*border-radius: 20px;*/
            overflow: hidden;
            align-content: center;
            display: grid;
        }
        .slogan{
            color: white;
            font-size: 48px;
            font-weight: bold;
            text-align: center;
            position: absolute;
            width: 100%;
            min-width: 1024px;
            top: 200px;
            font-family: Montserrat-bold;
        }
        .search_box{
            width: 100%;
            min-width: 1024px;
            position: absolute;
            top: 300px;
            text-align: center;
        }
        .search_back{
            background-color: #ffffff;
            height: 60px;
            width: 45%;
            margin: 0px auto;
            -moz-border-radius: 10px;
            -webkit-border-radius: 10px;
            border-radius: 10px;
            display: flex;
        }
        .search_input{
            border: 0px;
            padding-left: 50px;
            margin-left: 10px;
            width: 85%;
            font-size: 20px;
            background-image: url("./tpl/Static/blue/images/new/locating.png");
            background-repeat: no-repeat;
            background-size: auto 40px;
            background-position: 0px center;
        }
        .link_btn{
            width: 15%;
            background-image: url("./tpl/Static/blue/images/new/icon_right_arrow.png");
            background-repeat: no-repeat;
            background-size: auto 40px;
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
            margin-top: 50px;
            height: 70px;
        }
        .down_app span{
            width: 255px;
            height: 70px;
            cursor: pointer;
            display: inline-block;
        }
        .down_app .app_icon{
            background-image: url("./tpl/Static/blue/images/new/Apple_app_store_icon.png");
            background-size: auto 70px;
            background-repeat: no-repeat;
        }
        .down_app .apk_icon{
            margin-left: 15px;
            background-image: url("./tpl/Static/blue/images/new/AndroidButton.png");
            background-size: auto 70px;
            background-repeat: no-repeat;
        }
        .app_desc{
            margin-top: 50px;
            height: 560px;
            display: flex;
            width: 100%;
            background-color: #FBF0DE;
            position: relative;
        }
        .desc_left{
            -moz-transform:scaleX(-1);
            -webkit-transform:scaleX(-1);
            -o-transform:scaleX(-1);
            transform:scaleX(-1);
        }
        .desc_left,.desc_right{
            height: 560px;
            width: 6%;
            background-image: url("./tpl/Static/blue/images/new/icon_right_arrow.png");
            background-size: auto 40px;
            background-repeat: no-repeat;
            background-position: center center;
            cursor: pointer;
            z-index: 99;
        }
        .desc_left:hover,.desc_right:hover{
            background-image: url("./tpl/Static/blue/images/new/icon_right_arrow.png");
        }
        .desc_center{
            flex: 1 1 100%;
            position: relative;
            overflow: hidden;
        }
        .desc_all{
            width: 100%;
            position: absolute;
            transform: translate3d(120%, 0, 0);
            -webkit-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
        }
        .desc_pro{
            transform: translate3d(-120%, 0, 0);
        }
        .desc_next{
            transform: translate3d(120%, 0, 0);
        }
        .desc_curr{
            transform: translate3d(0%, 0, 0);
        }
        .desc_txt{
            width: 35%;
            height: 560px;
            padding-top: 100px;
            margin-left: 10%;
        }
        .desc_img{
            width: 50%;
            height: 540px;
            background-size:auto 100%;
            background-repeat: no-repeat;
            background-position: center bottom;
            position: absolute;
            margin-left: 50px;
            bottom: 0px;
        }
        .desc_center .desc_all:nth-child(1) .desc_img{
            background-image: url("./tpl/Static/blue/images/new/app_new_1.png");
        }
        .desc_center .desc_all:nth-child(2) .desc_img{
            background-image: url("./tpl/Static/blue/images/new/app_new_2.png");
        }
        .desc_center .desc_all:nth-child(3) .desc_img{
            background-image: url("./tpl/Static/blue/images/new/app_new_3.png");
        }
        .desc_title{
            font-size: 38px;
            font-weight: bold;
            line-height: 40px;
        }
        .desc_memo{
            font-size: 20px;
            margin-top: 20px;
            line-height: 24px;
            color: dimgrey;
            font-family: Montserrat-light;
        }
        .white_line,.become_div{
            width: 70%;
            /*height: 3px;*/
            margin:70px auto;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            background-color: #ffffff;
        }
        .all_info{
            width: 100%;
            margin-top: 200px;
            height: 500px;
            display: flex;
            background-color: #FBF0DE;
        }
        .ready_order{
            text-align: center;
            font-size: 48px;
            font-weight: bold;
        }
        .three_div{
            width: 90%;
            margin: 30px auto;
            display: flex;
        }
        .three_div .three_memo_div{
            padding: 30px 0;
            flex: 1 1 100%;
        }
        .three_memo_div div{
            text-align: center;
        }
        .memo_img{
            width: 200px;
            height: 200px;
            margin: 0px auto 40px auto;
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
            font-size: 30px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .memo_sub{
            margin-top: 20px;
            font-size: 18px;
            padding: 0px 10px;
            font-family: Montserrat-light;
        }
        .desc_white{
            position: absolute;
            width: 100%;
            height: 38px;
            background-color: white;
            display: inline-block;
            bottom: 0px;
        }
        .desc_item{
            position: absolute;
            width: 10%;
            height: 5px;
            top:15px;
            margin: 0 auto;
            display: flex;
            left: 50%;
            transform: translate(-50%,-50%);
        }
        .desc_item li{
            flex: 1 1 100%;
            border-radius: 2px;
            background-color: white;
            height: 5px;
            margin-right: 5px;
            list-style: none;
        }
        .ready_div{
            width: 100%;
            padding: 200px 0;
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
        }
        .down_img{
            background-image: url("./tpl/Static/blue/images/new/downloadapp.png");
            background-size:100% auto;
            background-repeat: no-repeat;
            width: 330px;
            height: 580px;
            margin-left: 15%;
            margin-top: -100px;
        }
        .down_demo{
            margin: 80px 6%;
            width: 40%;
        }
        .down_title{
            font-weight: bold;
            font-size: 30px;
            color: black;
            height: 60px;
            line-height: 60px;
            display: inline-block;
            vertical-align:top;
            margin-left: 10px;
        }
        .icon_app{
            display: inline-block;
            width: 60px;
            height: 60px;
            border-radius: 15px;
            background-image: url("./tpl/Static/blue/images/new/icon.png");
            background-size:auto 100%;
            background-repeat: no-repeat;
        }
        .download_input{
            width: 70%;
            height: 60px;
            border-radius: 10px;
            border: 0px;
            padding: 5px 10px;
            font-size: 16px;
            display: inline-block;
        }
        .send_btn{
            width: 25%;
            margin-left: 2%;
            text-align: center;
            background: #ffa52d;
            color: white;
            border-radius: 10px;
            height: 60px;
            line-height: 60px;
            display: inline-block;
            vertical-align:top;
        }
        .serving_title{
            font-size: 36px;
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
            width: 22%;
            height: 80px;
            line-height: 80px;
            background-position: center left;
            background-size: auto 80px;
            background-repeat: no-repeat;
            padding-left: 100px;
            margin: 10px auto;
            font-size: 22px;
        }
        .city_list li:nth-child(1){
            background-image: url("./tpl/Static/blue/images/new/city/Victoria.png");
        }
        .city_list li:nth-child(2){
            background-image: url("./tpl/Static/blue/images/new/city/Kamloops.png");
        }
        .city_list li:nth-child(3){
            background-image: url("./tpl/Static/blue/images/new/city/Nanaimo.png");
        }
        .city_list li:nth-child(4){
            background-image: url("./tpl/Static/blue/images/new/city/Kelowna.png");
        }
        .city_list li:nth-child(5){
            background-image: url("./tpl/Static/blue/images/new/city/More.png");
        }
        .become_div{
            width: 80%;
        }
        .become_div div{
            width: 49%;
            display: inline-block;
            height: 500px;
            position: relative;
        }
        .become_div .become_img{
            width: 100%;
            height: 300px;
            background-position:center center;
            background-size: auto 450px;
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
            font-size: 30px;
            font-weight: bold;
            color: black;
            height: auto;
        }

        .become_div .become_desc{
            width: 70%;
            margin: 10px auto;
            text-align: center;
            font-size: 20px;
            color: black;
            height: auto;
            margin-left: 15%;
        }
        .become_div .become_btn{
            width: 180px;
            background-color: #ffa52d;
            border-radius: 10px;
            height: 50px;
            line-height: 50px;
            margin: 10px auto;
            text-align: center;
            color: white;
            font-size: 20px;
            display: block;
        }
        .become_btn a{
            display: block;
            color: white;
            text-decoration: none;
        }
        .div_video{
            width: 100%;
        }
    </style>
	<body>
        <include file="Public:header"/>
        <div class="main">
            <video class="div_video" autoplay="" loop="" muted="" poster="" type="video/mp4" class="e_videoback e_videoback-000 p_videoback" webkit-playsinline="true" x-webkit-airplay="true" playsinline="true" x5-video-player-type="h5" x5-video-orientation="h5" x5-video-player-fullscreen="true">
                <source src="/tpl/Static/blue/images/vedio/main_vedio.mp4">
            </video>
            <div class="slogan">
                BC’s Favorite <label style="color: #ffa52d">Local</label> Delivery App
            </div>
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
            <span class="app_icon">
            </span>
            <span class="apk_icon">
            </span>
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
            <div class="desc_white"></div>
            <div class="desc_left"></div>
            <div class="desc_center">
                <div class="desc_all">
                    <div class="desc_img" style="right: 20px;"></div>
                    <div class="desc_txt">
                        <div class="desc_title">
                            {pigcms{:L('3FAVREST')}
                        </div>
                        <div class="desc_memo">
                            {pigcms{:L('3FAVRESTDES')}
                        </div>
                    </div>
                </div>
                <div class="desc_all">
                    <div class="desc_img"></div>
                    <div class="desc_txt" style="margin-left: 60%">
                        <div class="desc_title">
                            {pigcms{:L('3VIEWMENU')}
                        </div>
                        <div class="desc_memo">
                            {pigcms{:L('3VIEWMENUDES')}
                        </div>
                    </div>
                </div>
                <div class="desc_all">
                    <div class="desc_img" style="right: 20px;"></div>
                    <div class="desc_txt">
                        <div class="desc_title">
                            {pigcms{:L('3TRACK')}
                        </div>
                        <div class="desc_memo">
                            {pigcms{:L('3TRACKDES')}
                        </div>
                    </div>
                </div>
            </div>
            <div class="desc_right"></div>
            <ul class="desc_item">
                <li></li><li></li><li></li>
            </ul>
        </div>
        <div class="all_info" id="download">
            <div class="down_img"></div>
            <div class="down_demo">
                <span class="icon_app"></span><span class="down_title">{pigcms{:L('3DOWNLOADAPP')}</span>
                <div style="margin-top: 20px;font-size: 22px;">
                    {pigcms{:L('3DOWNLOADTEXT')}
                </div>
                <div style="margin-top: 20px">
                    <input type="text" placeholder="{pigcms{:L('3DOWNLOADAPPDES')}" name="download_input" class="download_input">
                    <span class="send_btn">{pigcms{:L('3GETMESSAGE')}</span>
                </div>
                <div class="down_app">
                    <span class="app_icon"></span>
                    <span class="apk_icon"></span>
                </div>
            </div>
        </div>
        <div class="white_line">
            <div class="serving_title">{pigcms{:L('4CITIES')}</div>
            <ul class="city_list">
                <li>Victoria</li>
                <li>Kamloops</li>
                <li>Nanaimo</li>
                <li>Kelowna</li>
                <li>{pigcms{:L('4MORE')}</li>
            </ul>
        </div>
        <div class="ready_div">
            <div class="ready_order">
                {pigcms{:L('5READYTOORDER')}
            </div>
            <div class="search_box" style="margin-top: 30px;margin-bottom: 30px;position: relative;top:0;">
                <div class="search_back" style="width: 50%">
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
                <div class="become_btn"><a href="{pigcms{$config.site_url}/partner">{pigcms{:L('LEARNMORE')}</a></div>
            </div>
            <div>
                <div class="become_img"></div>
                <div class="become_title">{pigcms{:L('6COURIER')}</div>
                <div class="become_desc">
                    {pigcms{:L('6COURIERDES')}
                </div>
                <div class="become_btn"><a href="{pigcms{$config.site_url}/courier">{pigcms{:L('LEARNMORE')}</a></div>
            </div>
        </div>
        <include file="Public:footer"/>
	</body>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_public}layer/layer.m.js"></script>
<script>
    var desc_num = 3;
    var curr_num = 1;

    changeDesc();

    var timer = null;

    function changeDesc() {
        if(timer != null){
            clearInterval(timer);
        }
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
        i = 1;
        $('.desc_item').find('li').each(function () {
            if(i == curr_num){
                $(this).css('background-color','#ffa52d');
            }else{
                $(this).css('background-color','white');
            }
            i++;
        });

        timer = setTimeout(function () {
            curr_num += 1;
            changeDesc();
        }, 3000);
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
                    window.location.href = './app';
                },'JSON');
            }
        }
    }
</script>
</html>
