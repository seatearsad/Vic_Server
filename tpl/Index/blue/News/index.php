<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<if condition="$config['site_favicon']">
			<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
		</if>
		<!--title>{pigcms{$config.seo_title}</title-->
        <title>{pigcms{:L('_VIC_NAME_')} - Blog</title>
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
        *{
            margin: 0px;
            box-sizing: border-box;
            font-family: Helvetica;
            -moz-osx-font-smoothing: grayscale;
        }
        body{
            min-width: 1024px;
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
        .reg_show{
            width: 90%;
            height: 200px;
            margin: 10px auto 10px auto;
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
            background-color: #F5F5F5;
            display: flex;
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
            width: 50%;
            flex: 1 1 100%;
        }
        .reg_title img{
            width: 100%;
        }
        .reg_txt{
            font-size: 14px;
            width: 50%;
            flex: 1 1 100%;
            padding: 3%;
            text-align: left;
            box-sizing: border-box;
        }
        .desc_point{
            position: absolute;
            bottom: 0px;
            width: 100%;
            height: 20px;
            text-align: center;
        }
        .desc_point ul{
            margin: 0 auto;
            padding: 0;
        }
        .desc_point ul li{
            width: 30px;
            height: 5px;
            background-color: #CCCCCC;
            display: inline-block;
            margin-left: 5px;
            list-style: none;
        }
        .desc_point ul li.curr_li{
            background-color: #ffa52d;
        }
        .doc_sub{
            font-size: 16px;
            font-weight: bold;
            color: #666666;
        }
        .doc_title{
            font-weight: bold;
            font-size: 40px;
            color: #ffa52d;
            margin: 10px 0;
        }
        .doc_time{
            font-size: 12px;
            color: #999999;
        }
        .doc_content{
            margin: 20px 0;
        }
    </style>
	<body>
        <include file="Public:header"/>
        <div class="reg_desc">
            <div class="reg_show">
                <div class="desc_left"></div>
                <div class="desc_center">
                    <div class="desc_all">
                        <div class="reg_title">
                            <img src="{pigcms{$static_path}images/new/doc_img.png" />
                        </div>
                        <div class="reg_txt">
                            <div class="doc_sub">
                                [VICTORAI] SUB TITLE SUB TITLE SUB
                            </div>
                            <div class="doc_title">
                                1.WHY DO PEOPLE ORDER DELIVERIES?
                            </div>
                            <div class="doc_time">
                                Posted on Jan 20
                            </div>
                            <div class="doc_content">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit officia neque beatae at inventore excepturi numquam sint commodi alias, quam consequuntur corporis ex, distinctio
                            </div>
                        </div>
                    </div>
                    <div class="desc_all">
                        <div class="reg_title">
                            <img src="{pigcms{$static_path}images/new/doc_img.png" />
                        </div>
                        <div class="reg_txt">
                            <div class="doc_sub">
                                [VICTORAI] SUB TITLE SUB TITLE SUB
                            </div>
                            <div class="doc_title">
                                2.WHY DO PEOPLE ORDER DELIVERIES?
                            </div>
                            <div class="doc_time">
                                Posted on Jan 20
                            </div>
                            <div class="doc_content">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit officia neque beatae at inventore excepturi numquam sint commodi alias, quam consequuntur corporis ex, distinctio
                            </div>
                        </div>
                    </div>
                    <div class="desc_all">
                        <div class="reg_title">
                            <img src="{pigcms{$static_path}images/new/doc_img.png" />
                        </div>
                        <div class="reg_txt">
                            <div class="doc_sub">
                                [VICTORAI] SUB TITLE SUB TITLE SUB
                            </div>
                            <div class="doc_title">
                                3.WHY DO PEOPLE ORDER DELIVERIES?
                            </div>
                            <div class="doc_time">
                                Posted on Jan 20
                            </div>
                            <div class="doc_content">
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Suscipit officia neque beatae at inventore excepturi numquam sint commodi alias, quam consequuntur corporis ex, distinctio
                            </div>
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
        </div>
        <include file="Public:footer"/>
	</body>
<script>
    var desc_num = 3;
    var curr_num = 1;

    var timeoutId;
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

        if (typeof(timeoutId) != 'undefined') {
            clearTimeout(timeoutId);
        }

        timeoutId = setTimeout(function(){
            curr_num += 1;
            changeDesc();
        },5000);
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

    var courier_link = '{pigcms{$config.site_url}/wap.php?g=Wap&c=Deliver&a=login';
    $('.app_now').click(function () {
        window.location.href = courier_link;
    });
    $('.become_btn').click(function () {
        window.location.href = courier_link;
    });

    var width = $('.desc_center').width();
    var height = width / 3;
    var t_height = height + 20;
    $('.desc_all').height(height);
    $('.reg_desc').height(t_height);
    $('.reg_show').height(t_height);
    $('.desc_left').height(t_height);
    $('.desc_right').height(t_height);
</script>
</html>
