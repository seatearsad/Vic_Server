<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<if condition="$config['site_favicon']">
			<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
		</if>
		<!--title>{pigcms{$config.seo_title}</title-->
        <title>{pigcms{:L('_VIC_NAME_')} - Blog - {pigcms{$news.title}</title>
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
            font-size: 12px;
            margin: 7px 0;
            color: #666666;
        }
        .from{
            margin: 10px auto;
            color:#999999;
        }
        .detail_title{
            font-weight: bold;
            font-size: 20px;
        }
        .detail_time{
            margin: 2px auto;
            color:#999999;
            font-size: 14px;
        }
        .sub_title{
            font-size: 16px;
            margin-top:10px;
        }
        .content{
            margin-top:10px;
        }
    </style>
	<body>
        <include file="Public:header"/>
        <div class="main" style="margin-top: 20px">
            <div class="main_left">
                <img src="{pigcms{$news.top_img}" style="width: 100%">
                <div class="from">
                    <a href="/news/cat-0">All Posts</a> > <a href="/news/cat-{pigcms{$now_cat.id}">{pigcms{$now_cat.name}</a> > {pigcms{$news.title}
                </div>
                <div class="detail_title">
                    {pigcms{$news.title}
                </div>
                <div class="sub_title">
                    {pigcms{$news.sub_title}
                </div>
                <div class="detail_time">
                    Update on {pigcms{$news.last_time|date='M d Y',###}
                </div>
                <div class="content">
                    {pigcms{$news.content}
                </div>
            </div>
            <div class="main_right">
                <div class="right_title">RELATED POSTS</div>
                <ul class="right_list">
                    <volist name="cate_list" id="vo">
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

                <if condition="$now_cat.link_img">
                    <div>
                        <a href="{pigcms{$now_cat.link_url}">
                            <img src="{pigcms{$now_cat.link_img}" style="width: 100%">
                        </a>
                    </div>
                </if>
            </div>
        </div>
        <include file="Public:footer"/>
	</body>
</html>
