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
		<script src="{pigcms{$static_path}js/index.js"></script>
		<script src="{pigcms{$static_path}js/index.activity.js"></script>
		<if condition="$config['wap_redirect']">
			<script>
				if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){
					<if condition="$config['wap_redirect'] eq 1">
						window.location.href = './wap.php';
					<else/>
						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){
							window.location.href = './wap.php';
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
        #tutti_header{
            height: 100px;
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
            margin: 5px auto;
            width: 90px;
            height: 90px;
            -moz-border-radius: 45px;
            -webkit-border-radius: 45px;
            border-radius: 45px;
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
            margin-top: 40px;
        }
        .hamburger .line {
            width: 30px;
            height: 3px;
            background-color: #3f3f3f;
            display: block;
            margin: 6px auto;
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
            margin-top: 48px;
            font-size: 22px;
            -webkit-transition: all 0.3s ease-in-out;
            -o-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
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
        #hamburger-1.is-active .line:nth-child(1) {
            -webkit-transform: translateY(9px) rotate(45deg);
            -ms-transform: translateY(9px) rotate(45deg);
            -o-transform: translateY(9px) rotate(45deg);
            transform: translateY(9px) rotate(45deg);
        }
        #hamburger-1.is-active .line:nth-child(3) {
            -webkit-transform: translateY(-9px) rotate(-45deg);
            -ms-transform: translateY(-9px) rotate(-45deg);
            -o-transform: translateY(-9px) rotate(-45deg);
            transform: translateY(-9px) rotate(-45deg);
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
            width: 100px;
            height: 35px;
            border: 3px solid #F5F5F5;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            line-height: 35px;
            text-align: center;
            box-sizing: content-box;
            float: right;
            margin-right: 30px;
            margin-top: 40px;
            font-size: 20px;
            cursor: pointer;
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
            transition: transform .5s ease;
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
            background-image: url("./tpl/Static/blue/images/new/iphone.png");
            background-size:auto 440px ;
            background-repeat: no-repeat;
            position: absolute;
            margin-left: 30px;
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
            margin:50px auto;
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
            margin: 20px 0px 20px 30px;
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
            background-image: url("./tpl/Static/blue/images/new/food_community.jpg");
            background-size: 100% auto;
            background-repeat: no-repeat;
        }
        .info_courier{
            background-image: url("./tpl/Static/blue/images/new/courier.jpg");
            background-size: 100% auto;
            background-repeat: no-repeat;
        }
        .info_partner{
            background-image: url("./tpl/Static/blue/images/new/partner.jpg");
            background-size: 100% auto;
            background-repeat: no-repeat;
        }
        .ready_order{
            text-align: center;
            font-size: 48px;
            font-weight: bold;
        }
        .footer{
            width: 100%;
            margin-top: 100px;
            background-color: #232323;
            height: 500px;
        }
    </style>
	<body>
		<div id="tutti_header">
            <div id="header_menu">
                <div id="hamburger-1" class="hamburger">
                    <span class="line"></span>
                    <span class="line"></span>
                    <span class="line"></span>
                </div>
                <div class="menu_font">MENU</div>
            </div>
            <div id="header_logo"></div>
            <div id="header_sign">
                <div class="sign_btn">Sign in</div>
            </div>
        </div>
        <div id="menu_memo">
            <ul>
                <li>
                    <span class="m_img"> </span>
                    <span>Home</span>
                </li>
                <li>
                    <span class="m_img"> </span>
                    <span>Food Community</span>
                </li>
                <li>
                    <span class="m_img"> </span>
                    <span>Become a Courier</span>
                </li>
                <li>
                    <span class="m_img"> </span>
                    <span>Become a Partner</span>
                </li>
                <li style="background-image: none;height: 65px">
                    <span class="w_line"></span>
                </li>
                <li style="background-image: none">
                    <span>About Us</span>
                </li>
                <li style="background-image: none">
                    <span>Blogs</span>
                </li>
                <li style="background-image: none">
                    <span>Help</span>
                </li>
            </ul>
        </div>
        <div class="main">
            <div class="slogan">Your Online Food Community</div>
            <div class="search_box">
                <div class="search_back">
                    <input type="text" placeholder="Enter your address" class="search_input" name="search_word">
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
                            1 Various categories to choose from and get you always connected
                        </div>
                        <div class="desc_memo">
                            food delivery, local services and activities make your life so fun and exciting, best user experience.
                        </div>
                    </div>
                </div>
                <div class="desc_all">
                    <div class="desc_img"></div>
                    <div class="desc_txt">
                        <div class="desc_title">
                            2 Various categories to choose from and get you always connected
                        </div>
                        <div class="desc_memo">
                            food delivery, local services and activities make your life so fun and exciting, best user experience.
                        </div>
                    </div>
                </div>
                <div class="desc_all">
                    <div class="desc_img"></div>
                    <div class="desc_txt">
                        <div class="desc_title">
                            3 Various categories to choose from and get you always connected
                        </div>
                        <div class="desc_memo">
                            food delivery, local services and activities make your life so fun and exciting, best user experience.
                        </div>
                    </div>
                </div>
            </div>
            <div class="desc_right"></div>
        </div>
        <div class="white_line"></div>
        <div class="all_info">
            <div class="info_list food_comm" style="margin-left: 0px">
                <div class="info_txt">
                    Your favorite food and drinks are just a click away, we deliver right to your door !
                </div>
                <div class="info_btn">
                    Food Community
                </div>
            </div>
            <div class="info_list info_courier">
                <div class="info_txt">
                    Your favorite food and drinks are just a click away, we deliver right to your door !
                </div>
                <div class="info_btn">
                    Become a Courier
                </div>
            </div>
            <div class="info_list info_partner">
                <div class="info_txt">
                    Your favorite food and drinks are just a click away, we deliver right to your door !
                </div>
                <div class="info_btn">
                    Become a Partner
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
                    <input type="text" placeholder="Enter your address" class="search_input" name="search_word">
                    <div class="link_btn"></div>
                </div>
            </div>
        </div>
        <div class="footer">

        </div>
	</body>
<script>
    var is_open_menu = false;
    $('.hamburger').click(function () {
        if(is_open_menu) {
            $(this).removeClass('is-active');
            $('#menu_memo').removeClass('is_open');
        }else {
            $(this).addClass('is-active');
            $('#menu_memo').addClass('is_open');
            $('#menu_memo').height($(window).height() - 100);
        }

        is_open_menu = !is_open_menu;
    });

    var desc_num = 3;
    var curr_num = 1;

    changeDesc();

    function changeDesc() {
        var i = 1;
        curr_num = curr_num == 0 ? desc_num : curr_num;
        curr_num = curr_num > desc_num ? curr_num - desc_num : curr_num;

        var next_num = curr_num + 1;
        next_num = next_num > desc_num ? 0 : next_num;

        var pro_num = curr_num - 1;
        pro_num = pro_num == 0 ? 0 : pro_num;
        $('.desc_center').find('.desc_all').each(function () {
            if(i == curr_num){
                $(this).attr('class','desc_all desc_curr');
            }
            else if(i == next_num){
                $(this).attr('class','desc_all desc_next');
            }
            else if(i == pro_num){
                $(this).attr('class','desc_all desc_pro');
            }

            i++;
        });
    }

    $('.desc_right').click(function () {
        if(desc_num > curr_num){
            curr_num += 1;
            changeDesc();
        }
    });
    $('.desc_left').click(function () {
        if(curr_num > 1){
            curr_num -= 1;
            changeDesc();
        }
    });
</script>
</html>
