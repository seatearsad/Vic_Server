<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta name="viewport" content="width=device-width, initial-scale=1" />
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
<!--		<if condition="$config['wap_redirect']">-->
<!--			<script>-->
<!--				if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){-->
<!--					<if condition="$config['wap_redirect'] eq 1">-->
<!--						window.location.href = './wap';-->
<!--					<else/>-->
<!--						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){-->
<!--							window.location.href = './wap';-->
<!--						}-->
<!--					</if>-->
<!--				}-->
<!--			</script>-->
<!--		</if>-->
        <include file="Public:facebook"/>
	</head>
    <style>
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
            /*height: 330px;*/
            margin-bottom: 18px;
        }
        .reg_show{
            width: 100%;
            /*height: 200px;*/
            margin: 0px auto 1px auto;
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
            /*display: flex;*/
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
            width: 100%;
            /*flex: 1 1 100%;*/
        }
        .reg_title img{
            width: 100%;
            border-radius: 10px;
        }
        .reg_txt{
            font-size: 14px;
            width: 100%;
            flex: 1 1 100%;
            padding: 3%;
            text-align: left;
            box-sizing: border-box;
        }
        .desc_point{
            position: absolute;
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
            background-color: #fff;
            display: inline-block;
            margin-left: 5px;
            list-style: none;
        }
        .desc_point ul li.curr_li{
            background-color: #ffa52d;
        }

        .doc_sub{
            font-size: 15px;
            font-weight: bold;
            color: #666666;
            margin: 5px 0 5px 0;
        }
        .doc_title{
            font-weight: bold;
            font-size: 20px;
            color: #ffa52d;
            margin:  0px 0 5px 0;
        }

        .doc_time{
            font-size: 12px;
            color: #999999;
            margin-top: 5px;
        }
        .doc_content{
            margin: 20px 0;
        }

        .main_left{
            display: inline-block;
            width: 100%;
        }
        .main_right{
            /*display: inline-block;*/
            width: 100%;
            /*margin-left: 4%;*/
            vertical-align: top;
            margin-top: 25px;
        }
        .view_more{
            text-align: right;
            font-size: 14px;
        }
        .cate_list{
            width: 100%;
            border-bottom: 3px solid #ffa52d;
            display: flex;
            font-size: 15px;
        }
        .cate_list span{
            margin-left: 15px;
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
        .cate_list_item{
            white-space: nowrap;
            overflow-x: scroll;
            line-height: 1;
        }
        .right_title{
            border-bottom: 3px solid #ffa52d;
            font-weight: bold;
            font-size: 14px;
            padding-right: 10px;
            display: inherit;
            color: #ffa52d;
            padding-bottom: 3px;
        }
        .left_list,.right_list{
            width: 100%;
            padding: 0;
            margin-top: 20px;
        }
        .left_list li{
            background-color: #F5F5F5;
            /*height: 90px;*/
            list-style: none;
            display: flex;
            margin-bottom: 20px;
            border-radius: 10px;
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
            width: 115px;
            margin: 10px;
            flex: 0 0 auto;
        }
        .left_img img{
            border-radius: 5px;
        }
        .left_title{
            height: 90px;
            padding: 10px 15px 10px 5px;
            box-sizing: border-box;
            flex: 1 1 100%;
            position: relative;
        }
        .list_title{
            font-weight: bold;
        }
        .list_sub{
            font-size: 12px;
            margin: 7px 0;
            color: #666666;
        }

    </style>
	<body>
        <script>
            var app_name = 'TUTTI - Online Food Community';
            var app_url = 'https://itunes.apple.com/us/app/tutti/id1439900347?ls=1&mt=8';
        </script>

        <include file="Public:wapnews_header"/>

        <div class="reg_desc">
            <div class="reg_show">
<!--                <div class="desc_left"></div>-->
                <div class="desc_center">
                    <volist name="commend" id="vo">
                        <a href="/wapnews/{pigcms{$vo.id}">
                        <div class="desc_all">
                            <div class="reg_title">
                                <img src="{pigcms{$vo.cover}" />
                            </div>
                            <div class="reg_txt">
                                <div class="doc_title only_3_lines">
                                    {pigcms{$vo.title}
                                </div>
                                <div class="doc_sub only_1_lines">
                                    {pigcms{$vo.sub_title}
                                </div>
                                <div class="doc_time">
                                    Posted on {pigcms{$vo.last_time|date='M d Y',###}
                                </div>
<!--                                <div class="doc_content">-->
<!--                                    {pigcms{$vo.desc}-->
<!--                                </div>-->
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
<!--                <div class="desc_right"></div>-->
            </div>
        </div>

        <div class="main">
            <div class="main_left">
                <div class="cate_list">
                    <span class="curr_cate" style="margin-left: 0;width: 95px;white-space: nowrap;" data-id="0">ALL POST</span>
                    <div class="cate_list_item">
                    <volist name="cate_list['cate']" id="vo">
                        <span data-id="{pigcms{$vo.id}">{pigcms{$vo.name}</span>
                    </volist>
                     </div>
                    <span class="cate_more"> > </span>
                </div>
                <ul class="left_list" id="list_0">
                    <volist name="news_all" id="vo">
                    <li>
                        <a href="/wapnews/{pigcms{$vo.id}">
                        <div class="left_img">
                            <img src="{pigcms{$vo.cover}" alt="" style="width: 100%"/>
                        </div>
                        <div class="left_title">
                            <div style="position: absolute;transform: translateY(-50%);top: 50%;">
                                <div class="list_title only_3_lines">
                                    {pigcms{$vo.title}
                                </div>
    <!--                            <div class="list_sub">-->
    <!--                                {pigcms{$vo.sub_title}-->
    <!--                            </div>-->
                                <div class="doc_time">
                                    {pigcms{$vo.last_time|date='M d Y',###}
                                </div>
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
                            <a href="/wapnews/{pigcms{$vo.id}">
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
                    <a href="/wapnews/cat-0">
                        VIEW ALL POSTS<label></label> >>
                    </a>
                </div>
            </div>
            <div class="main_right">
                <div class="right_title">POPULAR POSTS</div>
                <ul class="right_list">
                    <volist name="news_all" id="vo">
                    <li>
                        <a href="/wapnews/{pigcms{$vo.id}">
                        <label></label>
                        <div class="only_3_lines" style="margin-left:10px">
                            {pigcms{$vo.title}
                        </div>
                        </a>
                    </li>
                    </volist>
                </ul>
            </div>
        </div>

        <include file="Public:wapnews_footer"/>
	</body>
<script>
    var desc_num = '{pigcms{$commend_num}';
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
            //changeDesc();
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

    var courier_link = "{pigcms{$config.site_url}/wap.php?g=Wap&c=Deliver&a=login";

    $('.app_now').click(function () {
        window.location.href = courier_link;
    });
    $('.become_btn').click(function () {
        window.location.href = courier_link;
    });

    var width = $('.desc_center').width();
    //var width = window.screen.availWidth;
    var img_height= width/1.59;
    $('.desc_point').css('top',img_height);
    var height = width *1.03;
    var t_height = height;
    $('.reg_desc').height(t_height);    //最外层高度
    $('.reg_show').height(t_height);

    $('.desc_center').width(width)      //内层高度

    $('.desc_all').height(height);      //循环内高度


    // $('.desc_left').height(t_height);
    // $('.desc_right').height(t_height);

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
                $('.view_more a').attr('href','/wapnews/cat-'+cate_id);
            }else if(class_name == 'cate_more'){
                window.location.href = '/wapnews/cat-0';
            }
        });
    });

</script>
</html>
