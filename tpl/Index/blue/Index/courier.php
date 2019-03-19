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
        .white_line{
            width: 96%;
            height: 3px;
            margin:50px auto;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            background-color: #ffffff;
        }
        .main{
            width: 100%;
            height: 520px;
            background-image: url("./tpl/Static/blue/images/new/courier/courier.jpg");
            background-size: 100% auto;
            background-position: bottom;

        }
        .become_div{
            width: 320px;
            height: 180px;
            background-color: #ffffff;
            margin-top: -70px;
            margin-left: 70px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            text-align: center;
        }
        .become_txt{
            width: 100%;
            height: 40px;
            line-height: 40px;
            font-size: 30px;
            font-weight: bold;
            padding-top: 20px;
            color: #ffa52d;
        }
        .become_btn{
            width: 70%;
            height: 40px;
            margin-left: 15%;
            margin-top: 50px;
            line-height: 40px;
            background-color: #ffa52d;
            font-size: 30px;
            font-weight: bold;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            color: #ffffff;
            cursor: pointer;
        }
        .courier_desc{
            height: 180px;
            display: flex;
            margin-top: 80px;
        }
        .courier_desc .desc_box{
            flex: 1 1 100%;
            margin-left: 50px;
            padding-right: 50px;
        }
        .desc_title{
            width: 100%;
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .desc_txt{
            line-height: 20px;
        }
        .middle_img{
            width: 100%;
            height: 520px;
            background-image: url("./tpl/Static/blue/images/new/courier/middle.jpg");
            background-size: 100% auto;
            background-position: center;
        }
        .how_div{
            height: 300px;
            margin-top: 100px;
            text-align: center;
        }
        .how_title{
            font-size: 36px;
            font-weight: bold;
            height: 50px;
        }
        .how_memo{
            display: flex;
            width: 90%;
            margin: 50px auto;
        }
        .how_box{
            flex: 1 1 100%;
            margin-left: 20px;
            padding-right: 20px;
            font-size: 14px;
        }
        .how_img{
            width: 100%;
            height: 100px;
            margin-bottom: 10px;
            background-repeat: no-repeat;
            background-size: auto 100%;
            background-position: center;
        }
        .how_memo .how_box:nth-child(1) .how_img{
            background-image: url("./tpl/Static/blue/images/new/courier/mobile_app.png");
        }
        .how_memo .how_box:nth-child(2) .how_img{
            background-image: url("./tpl/Static/blue/images/new/courier/to_store.png");
        }
        .how_memo .how_box:nth-child(3) .how_img{
            background-image: url("./tpl/Static/blue/images/new/courier/car.png");
        }
        .how_memo .how_box:nth-child(4) .how_img{
            background-image: url("./tpl/Static/blue/images/new/courier/hand_over.png");
        }
        .reg_desc{
            height: 330px;
        }
        .app_now{
            width: 330px;
            height: 50px;
            margin: 0 auto;
            line-height: 50px;
            background-color: #ffa52d;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            font-size: 44px;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            cursor: pointer;
        }
        .reg_show{
            width: 600px;
            height: 200px;
            margin: 80px auto 50px auto;
            display: flex;
        }
        .desc_left{
            -moz-transform:scaleX(-1);
            -webkit-transform:scaleX(-1);
            -o-transform:scaleX(-1);
            transform:scaleX(-1);
        }
        .desc_left,.desc_right{
            height: 200px;
            width: 40px;
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 30px;
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
            height: 180px;
            position: absolute;
            transform: translate3d(120%, 0, 0);
            -webkit-transition: all 0.5s ease-in-out;
            -o-transition: all 0.5s ease-in-out;
            transition: all 0.5s ease-in-out;
            text-align: center;
        }
        .desc_pro{
            transform: translate3d(-100%, 0, 0);
        }
        .desc_next{
            transform: translate3d(100%, 0, 0);
        }
        .desc_curr{
            transform: translate3d(0%, 0, 0);
        }
        .reg_title{
            font-size: 28px;
            font-weight: bold;
            height: 40px;
            margin: 60px 0 10px 0;
        }
        .reg_txt{
            font-size: 14px;
        }
        .desc_point{
            position: absolute;
            bottom: 20px;
            width: 100%;
        }
        .desc_point ul{
            width: 42px;
            margin: 0 auto;
            padding: 0;
            position: relative;
        }
        .desc_point ul li{
            width: 8px;
            height: 8px;
            -moz-border-radius: 4px;
            -webkit-border-radius: 4px;
            border-radius: 4px;
            background-color: #ffa52d;
            float: left;
            margin-left: 5px;
            list-style: none;
        }
        .desc_point ul li.curr_li{
            width: 10px;
            height: 10px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            border-radius: 5px;
            margin-top: -1px;
        }
    </style>
	<body>
        <include file="Public:header"/>
        <div class="main"></div>
        <div class="become_div">
            <div class="become_txt">
                Become a Courier
            </div>
            <div class="become_btn">
                Get Started
            </div>
        </div>
        <div class="courier_desc">
            <div class="desc_box">
                <div class="desc_title">
                    Own Scheduling
                </div>
                <div class="desc_txt">
                    TUTTI is always devote in supporting
                    local business providers, while
                    offering delicated support. Passing on
                    the eCommerce idea to every partner,
                    sharing the most advanced new
                    features.
                </div>
            </div>
            <div class="desc_box">
                <div class="desc_title">
                    Keep 100% Earnings
                </div>
                <div class="desc_txt">
                    TUTTI is always devote in supporting
                    local business providers, while
                    offering delicated support. Passing on
                    the eCommerce idea to every partner,
                    sharing the most advanced new
                    features.
                </div>
            </div>
            <div class="desc_box">
                <div class="desc_title">
                    Weekly Payrolls
                </div>
                <div class="desc_txt">
                    TUTTI is always devote in supporting
                    local business providers, while
                    offering delicated support. Passing on
                    the eCommerce idea to every partner,
                    sharing the most advanced new
                    features.
                </div></div>
        </div>
        <div class="white_line"></div>
        <div class="middle_img"></div>
        <div class="how_div">
            <div class="how_title">
                How it works?
            </div>
            <div class="how_memo">
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        Accept an incoming order check detailed info
                    </div>
                </div>
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        Arriving at the store on time
                    </div>
                </div>
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        Drive to customer with the food in TUTTI delivery bag
                    </div>
                </div>
                <div class="how_box">
                    <div class="how_img"></div>
                    <div>
                        Hand over the items to customer with car
                    </div>
                </div>
            </div>
        </div>
        <div class="white_line"></div>
        <div class="reg_desc">
            <div class="reg_show">
                <div class="desc_left"></div>
                <div class="desc_center">
                    <div class="desc_all">
                        <div class="reg_title">1.Sign up online</div>
                        <div class="reg_txt">
                            Create your account and password,simple information is needed
                        </div>
                    </div>
                    <div class="desc_all">
                        <div class="reg_title">2.Sign up online</div>
                        <div class="reg_txt">
                            Create your account and password,simple information is needed
                        </div>
                    </div>
                    <div class="desc_all">
                        <div class="reg_title">3.Sign up online</div>
                        <div class="reg_txt">
                            Create your account and password,simple information is needed
                        </div>
                    </div>
                    <div class="desc_point">
                        <ul>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                </div>
                <div class="desc_right"></div>
            </div>
            <div class="app_now">Apply Now</div>
        </div>
        <include file="Public:footer"/>
	</body>
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
                var j = 1;
                $('.desc_point').find('li').each(function () {
                    if(j == i){
                        $(this).addClass('curr_li');
                    }else{
                        $(this).removeClass('curr_li');
                    }
                    j++;
                });
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
</script>
</html>
