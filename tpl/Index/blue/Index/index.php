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
	</head>
    <style>
        *{
            margin: 0px;
            box-sizing: border-box;
            font-family: Helvetica;
            -moz-osx-font-smoothing: grayscale;
        }
        body{
            min-width: 1024px;
            background-color: #F5F5F5;
            color: #3f3f3f;
        }
        .main{
            width: 100%;
            height: 500px;
            background-image: url("./tpl/Static/blue/images/new/main.jpg");
            background-size: cover;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
        }
        .slogan{
            color: #ffa52d;
            font-size: 48px;
            font-weight: bold;
            text-align: center;
            position: absolute;
            width: 100%;
            min-width: 1024px;
            margin-top: 200px;
        }
        .search_box{
            width: 100%;
            min-width: 1024px;
            position: absolute;
            margin-top: 300px;
            text-align: center;
        }
        .search_back{
            background-color: #ffffff;
            height: 60px;
            width: 45%;
            margin: 0px auto;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            display: flex;
        }
        .search_input{
            border: 0px;
            padding-left: 80px;
            margin-left: 5px;
            width: 85%;
            font-size: 24px;
            background-image: url("./tpl/Static/blue/images/new/locating.png");
            background-repeat: no-repeat;
            background-size: auto 45px;
            background-position:5px center;
        }
        .link_btn{
            width: 15%;
            background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
            background-repeat: no-repeat;
            background-size: auto 45px;
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
        }
        .down_app span{
            position: absolute;
            width: 205px;
            height: 70px;
            cursor: pointer;
        }
        .down_app .app_icon{
            margin-left: -240px;
            background-image: url("./tpl/Static/blue/images/new/Apple_app_store_icon.png");
            background-size: auto 70px;
            background-repeat: no-repeat;
        }
        .down_app .apk_icon{
            margin-left: 35px;
            background-image: url("./tpl/Static/blue/images/new/AndroidButton.png");
            background-size: auto 70px;
            background-repeat: no-repeat;
        }
        .app_desc{
            margin-top: 50px;
            margin-left: 20px;
            margin-right: 20px;
            height: 440px;
            display: flex;
        }
        .desc_left{
            -moz-transform:scaleX(-1);
            -webkit-transform:scaleX(-1);
            -o-transform:scaleX(-1);
            transform:scaleX(-1);
        }
        .desc_left,.desc_right{
            height: 440px;
            width: 100px;
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 70px;
            background-repeat: no-repeat;
            background-position: center right;
            cursor: pointer;
            z-index: 99;
        }
        .desc_left:hover,.desc_right:hover{
            background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
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
            width: 100%;
            height: 350px;
            margin-top: 55px;
            background-color: #ffffff;
            padding-left: 300px;
            padding-top: 100px;
            padding-right: 20px;
        }
        .desc_img{
            width: 220px;
            height: 440px;
            background-size:auto 440px ;
            background-repeat: no-repeat;
            position: absolute;
            margin-left: 30px;
        }
        .desc_center .desc_all:nth-child(1) .desc_img{
            background-image: url("./tpl/Static/blue/images/new/app_1.png");
        }
        .desc_center .desc_all:nth-child(2) .desc_img{
            background-image: url("./tpl/Static/blue/images/new/app_2.png");
        }
        .desc_center .desc_all:nth-child(3) .desc_img{
            background-image: url("./tpl/Static/blue/images/new/app_3.png");
        }
        .desc_title{
            font-size: 32px;
            font-weight: bold;
            line-height: 40px;
        }
        .desc_memo{
            font-size: 22px;
            margin-top: 10px;
            line-height: 28px;
        }
        .white_line{
            width: 90%;
            height: 3px;
            margin:70px auto;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            background-color: #ffffff;
        }
        .all_info{
            width: 90%;
            margin: 0px auto;
            height: 440px;
            display: flex;
        }
        .all_info .info_list{
            width: 30%;
            margin: 20px 0px 20px 3%;
            flex: 1 1 100%;
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
            font-size: 48px;
            font-weight: bold;
        }
    </style>
	<body>
        <include file="Public:header"/>
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
            <span class="app_icon">
            </span>
            <span class="apk_icon">
            </span>
        </div>
        <div class="app_desc">
            <div class="desc_left"></div>
            <div class="desc_center">
                <div class="desc_all">
                    <div class="desc_img"></div>
                    <div class="desc_txt">
                        <div class="desc_title">
                            {pigcms{:L('_NEW_INDEX_APP_T_1_')}
                        </div>
                        <div class="desc_memo">
                            {pigcms{:L('_NEW_INDEX_APP_D_1_')}
                        </div>
                    </div>
                </div>
                <div class="desc_all">
                    <div class="desc_img"></div>
                    <div class="desc_txt">
                        <div class="desc_title">
                            {pigcms{:L('_NEW_INDEX_APP_T_2_')}
                        </div>
                        <div class="desc_memo">
                            {pigcms{:L('_NEW_INDEX_APP_D_2_')}
                        </div>
                    </div>
                </div>
                <div class="desc_all">
                    <div class="desc_img"></div>
                    <div class="desc_txt">
                        <div class="desc_title">
                            {pigcms{:L('_NEW_INDEX_APP_T_3_')}
                        </div>
                        <div class="desc_memo">
                            {pigcms{:L('_NEW_INDEX_APP_D_3_')}
                        </div>
                    </div>
                </div>
            </div>
            <div class="desc_right"></div>
        </div>
        <div class="white_line"></div>
        <div class="all_info">
            <div class="info_list" style="margin-left: 0px">
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
            <div class="search_box" style="margin-top: 30px;margin-bottom: 30px;position: relative">
                <div class="search_back" style="width: 60%">
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
                link = './shop';
                break;
            case 2:
                link = './courier';
                break;
            case 3:
                link = './partner';
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
        window.location.href = './shop';
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
                    window.location.href = './shop';
                },'JSON');
            }
        }
    }
</script>
</html>
