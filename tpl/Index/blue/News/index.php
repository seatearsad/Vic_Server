<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <META NAME ="keywords" CONTENT="Tutti Delivery Food ">
        <META NAME="description" CONTENT="Founded in 2017 in Victoria, British Columbia, TUTTI started as a small food and beverage delivery company with the goal of building a delivery service that brings communities togethe">

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
						window.location.href = '../wapnews';
					<else/>
						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){
							window.location.href = './wapnews';
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
            font-family: Montserrat;
            -moz-osx-font-smoothing: grayscale;
        }
        body{
            min-width: 1024px;
            color: #3f3f3f;
        }
        a{
            display: contents;
            color:#3f3f3f;
            text-decoration: none;
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
            overflow-y: hidden;
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
            vertical-align: middle;
            display: inline;
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

        .main{
            width: 90%;
            margin: 10px auto;
        }
        .main_left{
            display: inline-block;
            width: 59%;
        }
        .main_right{
            display: inline-block;
            width: 36%;
            margin-left: 4%;
            vertical-align: top;
        }
        .cate_list{
            width: 100%;
            border-bottom: 3px solid #ffa52d;
        }
        .cate_list span{
            margin-left: 20px;
            font-weight: bold;
            cursor: pointer;
        }
        .cate_list span.curr_cate{
            color: #ffa52d;
        }
        .cate_list span.cate_more{
            float: right;
            color: #666666;
            font-weight: normal;
            margin-right: 5px;
        }
        .right_title{
            border-bottom: 3px solid #ffa52d;
            font-weight: bold;
            padding-right: 10px;
            display: inherit;
        }
        .left_list,.right_list{
            width: 100%;
            padding: 0;
            margin-top: 30px;
        }
        .left_list li{
            background-color: #F5F5F5;
            height: 90px;
            list-style: none;
            display: flex;
            margin-bottom: 20px;
        }
        .right_list li{
            list-style: none;
            margin-bottom: 20px;
            display: flex;
        }
        .right_list li label{
            width: 10px;
            height: 10px;
            background-color: #ffa52d;
            display: inline-block;
            flex: 0 0 auto;
            transform:rotate(45deg);
            -ms-transform:rotate(45deg); /* IE 9 */
            -webkit-transform:rotate(45deg);
            vertical-align: center;
            margin: auto 5px;
        }
        .right_list span{
            display: inline-block;
            flex: 1 1 100%;
            padding: 0px 50px 0px 10px;
        }
        .left_list span{
            display: inline-block;
            vertical-align: top;
        }
        .left_img{
            width: 135px;
            flex: 0 0 auto;
        }
        .left_title{
            height: 90px;
            padding: 10px 15px;
            box-sizing: border-box;
            flex: 1 1 100%;
        }
        .list_title{
            font-weight: bold;
        }
        .list_sub{
            font-size: 14px;
            margin:8px 0 2px 0;
            color: #666666;
        }
    </style>
	<body>
        <include file="Public:header"/>
        <div style="">
        <div class="reg_desc">
            <div class="reg_show">
                <div class="desc_left"></div>
                <div class="desc_center">
                    <volist name="commend" id="vo">
                    <a href="/news/{pigcms{$vo.id}">
                    <div class="desc_all">
                        <div class="reg_title">
                            <img src="{pigcms{$vo.cover}" />
                        </div>
                        <div class="reg_txt">
                            <div class="doc_sub">
                                {pigcms{$vo.sub_title}
                            </div>
                            <div class="doc_title">
                                {pigcms{$vo.title}
                            </div>
                            <div class="doc_time">
                                Posted on {pigcms{$vo.last_time|date='M d Y',###}
                            </div>
                            <div class="doc_content">
                                {pigcms{$vo.desc}
                            </div>
                        </div>
                    </div>
                    </a>
                    </volist>
                    <div class="desc_point">
                        <ul>
                            <volist name="commend" id="vo">
                            <li></li>
                            </volist>
                        </ul>
                    </div>
                </div>
                <div class="desc_right"></div>
            </div>
        </div>
        <div class="main" style="margin-top: 50px">
            <div class="main_left">
                <div class="cate_list">
                    <span class="curr_cate" style="margin-left: 0;" data-id="0">ALL POSTS</span>
                    <volist name="cate_list['cate']" id="vo">
                        <span data-id="{pigcms{$vo.id}">{pigcms{$vo.name}</span>
                    </volist>
                    <span class="cate_more">MORE >></span>
                </div>
                <ul class="left_list" id="list_0">
                    <volist name="news_all" id="vo">
                    <li>
                        <a href="/news/{pigcms{$vo.id}">
                        <div class="left_img">
                            <img src="{pigcms{$vo.cover}" alt="" style="width: 100%"/>
                        </div>
                        <div class="left_title">
                            <div class="list_title">
                                {pigcms{$vo.title}
                            </div>
                            <div class="list_sub">
                                {pigcms{$vo.sub_title}
                            </div>
                            <div class="doc_time">
                                Posted on {pigcms{$vo.last_time|date='M d Y',###}
                            </div>
                        </div>
                        </a>
                    </li>
                    </volist>
                </ul>
                <volist name="cate_list['news']" id="vo_cate">
                <ul class="left_list" id="list_{pigcms{$key}" style="display: none">
                    <volist name="vo_cate" id="vo">
                        <li>
                            <a href="/news/{pigcms{$vo.id}">
                                <div class="left_img">
                                    <img src="{pigcms{$vo.cover}" alt="" style="width: 100%"/>
                                </div>
                                <div class="left_title">
                                    <div class="list_title">
                                        {pigcms{$vo.title}
                                    </div>
                                    <div class="list_sub">
                                        {pigcms{$vo.sub_title}
                                    </div>
                                    <div class="doc_time">
                                        Posted on {pigcms{$vo.last_time|date='M d Y',###}
                                    </div>
                                </div>
                            </a>
                        </li>
                    </volist>
                </ul>
                </volist>
                <div class="view_more">
                    <a href="/news/cat-0">
                        VIEW ALL POSTS<label></label> >>
                    </a>
                </div>
            </div>
            <div class="main_right">
                <div class="right_title">POPULAR POSTS</div>
                <ul class="right_list">
                    <volist name="news_all" id="vo">
                    <li>
                        <a href="/news/{pigcms{$vo.id}">
                        <label></label>
                        <span>
                            {pigcms{$vo.title}
                        </span>
                        </a>
                    </li>
                    </volist>
                </ul>
            </div>
        </div>
        </div>
        <include file="Public:footer"/>
	</body>
<script>
    var desc_num = '{pigcms{$commend_num}';
    var curr_num = 1;

    var timeoutId;
    changeDesc();

    $(window).resize(function(){

        rsz();
    });
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

    function rsz(){
        var width = $('.desc_center').width();
        var height = width / 3.2;
        var t_height = height;
        $('.desc_all').height(height);
        $('.reg_desc').height(t_height);
        $('.reg_show').height(t_height);
        $('.desc_left').height(t_height);
        $('.desc_right').height(t_height);
    }
    rsz();
    $('.cate_list').find('span').each(function () {
        $(this).click(function () {
            var class_name = $(this).attr('class');
            if(class_name != 'curr_cate' && class_name != 'cate_more'){
                $('.cate_list').find('span').each(function () {
                    if($(this).attr('class') == 'curr_cate')
                        $(this).removeClass();
                });

                var cate_id = $(this).data('id');
                var id_name = '#list_'+cate_id;

                $('body').find('.left_list').each(function () {
                    $(this).hide();
                });
                $(id_name).show();
                $(this).addClass('curr_cate');

                var cate_name = '';
                if(cate_id != 0)
                    cate_name = ' ABOUT ' + $(this).html();

                $('.view_more label').html(cate_name);
                $('.view_more a').attr('href','/news/cat-'+cate_id);
            }else if(class_name == 'cate_more'){
                window.location.href = '/news/cat-0';
            }
        });
    });
</script>
</html>
