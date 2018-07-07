<!DOCTYPE html>
<!-- saved from url=(0037)http://www.mh163k.com/article/?page=2 -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>文章资讯栏目首页-163k地方门户网站系统</title>
	<!-- UC默认竖屏 ，UC强制全屏 -->
	<meta name="full-screen" content="yes">
	<meta name="browsermode" content="application">
	<!-- QQ强制竖屏 QQ强制全屏 -->
	<meta name="x5-orientation" content="portrait">
	<meta name="x5-fullscreen" content="true">
	<meta name="x5-page-mode" content="app">
	<meta name="keywords" content="本地资讯栏目关键词,关键词,关键词,关键词,关键词,关键词,关键词,关键词">
	<meta name="description" content="本地资讯栏目介绍">
	<link rel="stylesheet" rev="stylesheet" href="./css/news-mb.css">
	<link rel="stylesheet" rev="stylesheet" href="./css/news-scroll5.css">
	<style>
#wrapper2 { height:41px;}
.slide_tabs{position:relative; overflow:hidden; background-color:#fafafa;}
.slide_tabs ul{}
.slide_tabs li{max-width:4em; padding:0 10px; height:40px; line-height:40px;border-bottom:1px solid #eee; overflow:hidden; float:left;text-align:center;overflow:hidden;}
.slide_tabs li.current{border-bottom:1px solid #ff9933; color:#f93;}
.slide_tabs li.current a { color:#f93;}
.slide_tabs_wrap .more{right:0;background:url('http://www.mh163k.com/template/wap/main/default/images/nav2015BG.png') repeat-y 0 0;position:absolute; z-index:1;top:0;width:50px;height:40px;}
.slide_tabs_wrap .more span,.slide_tabs_wrap .more span:after { position:absolute; top:16px; left:26px; display:inline-block; border-color:#adadad transparent transparent transparent; border-width:8px; border-style:solid; transition:transform .3s ease; -webkit-transition:-webkit-transform .3s ease; transform-origin:50% 25% 0; -webkit-transform-origin:50% 25% 0;}
.slide_tabs_wrap .more span:after { position:absolute; top:-10px; left:-8px; content:' '; border-color:#fafafa transparent transparent transparent;}
.open .more span { transform:rotate(180deg); -webkit-transform:rotate(180deg);}
#scroller2 {-webkit-tap-highlight-color: rgba(0,0,0,0);	width: 100%;-webkit-transform: translateZ(0);-moz-transform: translateZ(0);	-ms-transform: translateZ(0);-o-transform: translateZ(0);transform: translateZ(0);-webkit-touch-callout: none;-webkit-user-select: none;-moz-user-select: none;-ms-user-select: none;user-select: none;-webkit-text-size-adjust: none;-moz-text-size-adjust: none;-ms-text-size-adjust: none;-o-text-size-adjust: none;text-size-adjust: none;}
.slide_tabs_wrap { position:relative; z-index:3;}
.slide_tabs_wrap .node2 { display:none; position:absolute; left:0; top:0; right:0; background-color:#fafafa; box-shadow:0 2px 5px rgba(0,0,0,.2);}
.open .node2 { display:block;}
.slide_tabs_wrap .node2 .hd { border-bottom:1px solid #fff;}
.slide_tabs_wrap .node2 .hd .tit { display:inline-block; padding:7px 3px; color:#fb9031; border-bottom:1px solid #fb9031;}
.slide_tabs_wrap .node2 ul { padding:10px 0;}
.slide_tabs_wrap .node2 li { float:left; width:25%; padding:0 5px; -webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box; margin:5px 0;}
.slide_tabs_wrap .node2 li a { display:inline-block; vertical-align:top; border:1px solid #ddd; border-radius:15px; font-size:12px; line-height:30px; height:30px; overflow:hidden; padding:0 10px; width:4em; text-align:center;}
.slide_tabs_wrap .node2 li.current a { color:#f93;border:1px solid #ff9933;}

</style>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="./css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="./css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="./css/mb-common.css">
	<script src="./js/jquery-2.1.1.min.js"></script>
	<script src="./js/wap_common_2015.js"></script>
	<!--必须在现有的script外-->
	<script>
var isapp ="0";//在现有的js内:是否app平台
var YDB;
if(isapp === '1'){
	YDB = new YDBOBJ();
}
</script>
</head>
<body style="min-height: 640px;">
	<div id="pageMain">
		<div class="top_gg po_re" id="top_gg" style="display:none;">
			{$Mh163k_wap_首页_顶部广告}
			<span class="close po_ab">关闭</span>
		</div>
		<div class="header">
			<a href="http://www.mh163k.com/" onclick="" class="back">返回</a>
			<div class="search" id="search_ico" onclick="showNewPage(&#39;搜索&#39;,searchHtml,newPageSearch);" style="">搜索</div>
			<a href="http://www.mh163k.com/member/" class="my ico_ok" id="login_ico" style="display: none;">我的</a>
			<div class="type" id="nav_ico">导航</div>
			<span id="ipageTitle" style="">本地资讯</span>
			<div class="nav_APP" id="nav_APP">
				<ul class="clearfix">
					<li>
						<a href="http://www.mh163k.com/">
							首页
							<s class="s" style="background-color:#ffc230; background-image:url(./images/201603031026514893905.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/k/">
							外卖
							<s class="s" style="background-color:#5adcc8; background-image:url(./images/201603031035348719045.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/mall/">
							省啦
							<s class="s" style="background-color:#34aef4; background-image:url(./images/201603031031173057056.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/job/">
							招聘
							<s class="s" style="background-color:#ff5f45; background-image:url(./images/201603031032450876840.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/house/">
							房产
							<s class="s" style="background-color:#3399ff; background-image:url(./images/201603031033241981478.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/jiaoyou/">
							征婚
							<s class="s" style="background-color:#d81e06; background-image:url(./images/201701091601176021129.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/live/">
							供求
							<s class="s" style="background-color:#ff9933; background-image:url(./images/201603031030143985071.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/huodong/">
							活动
							<s class="s" style="background-color:#7778b5; background-image:url(./images/1-10/201701101210535444969.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/114/">
							黄页
							<s class="s" style="background-color:#87d140; background-image:url(./images/201603031036361226034.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/article/">
							资讯
							<s class="s" style="background-color:#1bca4c; background-image:url(./images/201603031028335841178.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/tieba/">
							贴吧
							<s class="s" style="background-color:#34aef4; background-image:url(./images/201603031041224861590.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/company/">
							商家
							<s class="s" style="background-color:#fd934a; background-image:url(./images/201603031034313862707.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/video/">
							视频
							<s class="s" style="background-color:#30cfd9; background-image:url(./images/201603031040254852431.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/member/myorder.aspx?action=myshoppay">
							购物车
							<s class="s" style="background-color:#87d140; background-image:url(./images/201603031042017361588.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/member/myorder.aspx">
							订单
							<s class="s" style="background-color:#ffc230; background-image:url(./images/201603031042530651808.png);"></s>
						</a>
					</li>
					<li>
						<a href="http://www.mh163k.com/gift/">
							积分
							<s class="s" style="background-color:#1bca4c; background-image:url(./images/201603031039252658301.png);"></s>
						</a>
					</li>
				</ul>
				<span class="arrow-up"></span>
			</div>
		</div>
		<div class="login_inner" id="login_inner">
			<p>
				<span class="username">123</span>
				，您好！欢迎登录163k地方门户网站系统！
				<br>
				<a href="http://www.mh163k.com/member">[管理中心]</a>
				<a href="javascript:IDC2.loginout(&#39;http://www.mh163k.com/&#39;);">[退出]</a>
			</p>
			<input value="1" id="isLogin" type="hidden">
			<input value="0" id="user_jibie" type="hidden"></div>
		<div class="nav_index_bottom" style="overflow:visible;">
			<ul>
				<li class="current">
					<a href="http://www.mh163k.com/">
						<span class="home"></span>
						首页
					</a>
				</li>
				<li>
					<a href="http://www.mh163k.com/photo/">
						<span class="photo"></span>
						图片
					</a>
				</li>
				<li>
					<a href="http://www.mh163k.com/member/myarticle.aspx?action=add" class="seniorSend">
						<span class="fatie"></span>
						投稿
					</a>
				</li>
				<li>
					<a href="http://www.mh163k.com/video/">
						<span class="video"></span>
						视频
					</a>
				</li>
				<li>
					<a href="http://www.mh163k.com/member/">
						<span class="mine"></span>
						我的
					</a>
				</li>
			</ul>
		</div>
		<div class="content news_index">
			<div class="slide_tabs_wrap">
				<div class="slide_tabs" id="wrapper2">
					<ul id="scroller2" style="width: 990px; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
						<li class="current item" onclick="return showCatA(this,&#39;&#39;);">
							<a href="javascript:void(0);">头版</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;88&#39;);">
							<a href="javascript:void(0);">京城快讯</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;23&#39;);">
							<a href="javascript:void(0);">健康常识</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;24&#39;);">
							<a href="javascript:void(0);">社交礼仪</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;25&#39;);">
							<a href="javascript:void(0);">母婴健康</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;84&#39;);">
							<a href="javascript:void(0);">生活百科</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;85&#39;);">
							<a href="javascript:void(0);">医学常识</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;86&#39;);">
							<a href="javascript:void(0);">职场人生</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;87&#39;);">
							<a href="javascript:void(0);">投资理财</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;385&#39;);">
							<a href="javascript:void(0);">感动天地</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;386&#39;);">
							<a href="javascript:void(0);">社会万象</a>
						</li>
					</ul>
				</div>
				<div class="node2">
					<div class="hd">
						<span class="tit">全部分类</span>
					</div>
					<ul id="cloneNav" class="clearfix">
						<li class="current item" onclick="return showCatA(this,&#39;&#39;);">
							<a href="javascript:void(0);">头版</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;88&#39;);">
							<a href="javascript:void(0);">京城快讯</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;23&#39;);">
							<a href="javascript:void(0);">健康常识</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;24&#39;);">
							<a href="javascript:void(0);">社交礼仪</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;25&#39;);">
							<a href="javascript:void(0);">母婴健康</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;84&#39;);">
							<a href="javascript:void(0);">生活百科</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;85&#39;);">
							<a href="javascript:void(0);">医学常识</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;86&#39;);">
							<a href="javascript:void(0);">职场人生</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;87&#39;);">
							<a href="javascript:void(0);">投资理财</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;385&#39;);">
							<a href="javascript:void(0);">感动天地</a>
						</li>
						<li class="item" onclick="return showCatA(this,&#39;386&#39;);">
							<a href="javascript:void(0);">社会万象</a>
						</li>
					</ul>
				</div>
				<div class="more" id="iscrollto">
					<span></span>
				</div>
			</div>
			<div id="wrapper" style="top:86px;bottom:51px;">
				<div id="scroller" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
					<div id="pullDown" style="display:none;">
						<span class="loader" style="display:none;">loadding</span>
					</div>
					<div id="slide" class="clearfix" style="width: 360px;">
						<div id="content" style="width: 1440px; transform: translate3d(-360px, 0px, 0px) scale(1);">
							<div class="cell" style="width: 360px;">
								<a href="http://www.mh163k.com/article/article_2376.html">
									<img src="./images/2016011216164766961111.png" alt=""></a>
								<span class="title">中国历史文化名城古镇-贵州镇远古镇（东方威尼斯）</span>
							</div>
							<div class="cell" style="width: 360px;">
								<a href="http://www.mh163k.com/article/article_2248.html">
									<img src="./images/2016011216174296681111.png" alt=""></a>
								<span class="title">男子救起落水11人体力严重透支，最终没能上岸。 获见义勇…</span>
							</div>
							<div class="cell" style="width: 360px;">
								<a href="http://www.mh163k.com/article/article_2157.html">
									<img src="./images/2016011216181252998781.png" alt=""></a>
								<span class="title">公园圣地——各国国家公园渊源</span>
							</div>
							<div class="cell" style="width: 360px;">
								<a href="http://www.mh163k.com/article/article_2151.html">
									<img src="./images/2016011216184338892271.png" alt=""></a>
								<span class="title">中国首台千万亿次超级计算机研制成功</span>
							</div>
						</div>
						<ul id="indicator" class="text_right">
							<li class="">1</li>
							<li class="active">2</li>
							<li class="">3</li>
							<li class="">4</li>
						</ul>
					</div>
					<span class="prev" id="slide_prev" style="display:none">上一张</span>
					<span class="next" id="slide_next" style="display:none">下一张</span>
					<ul id="innerrow" class="list_normal list_news">
						<li class="haspic1" id="item2376">
							<a href="http://www.mh163k.com/article/article_2376.html" class="link" onclick="return setCookieID(&#39;2376&#39;);">
								<p class="img">
									<img data-src="./images/2016011216164766961111.png" src="./images/2016011216164766961111.png" data-ifshow="1"></p>
								<p class="tit">中国历史文化名城古镇-贵州镇远古镇（东方威尼斯）</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：2543</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2375">
							<a href="http://www.mh163k.com/article/article_2375.html" class="link" onclick="return setCookieID(&#39;2375&#39;);">
								<p class="img">
									<img data-src="./images/2016011021014077925691.png" src="./images/2016011021014077925691.png" data-ifshow="1"></p>
								<p class="tit">南京“最牛公交站台”一站台停靠38趟车 小伙伴们都惊呆了</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：1202</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2374">
							<a href="http://www.mh163k.com/article/article_2374.html" class="link" onclick="return setCookieID(&#39;2374&#39;);">
								<p class="img">
									<img data-src="./images/2016011021023313882321.png" src="./images/2016011021023313882321.png" data-ifshow="1"></p>
								<p class="tit">中国十佳宜居城市排行榜”青岛获首位,幸福感城市第三位</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：717</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2373">
							<a href="http://www.mh163k.com/article/article_2373.html" class="link" onclick="return setCookieID(&#39;2373&#39;);">
								<p class="img">
									<img data-src="./images/2016011016253632651851.png" src="./images/2016011016253632651851.png" data-ifshow="1"></p>
								<p class="tit">网络求职6大安全注意 给求职者敲响警钟 远离网络求职潜规则</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：699</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2372">
							<a href="http://www.mh163k.com/article/article_2372.html" class="link" onclick="return setCookieID(&#39;2372&#39;);">
								<p class="img">
									<img data-src="./images/2016011021022021620831.png" src="./images/2016011021022021620831.png" data-ifshow="1"></p>
								<p class="tit">劳动和社会保障局医保处给您支招:医保卡被偷要尽快补办 一周内可办好</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：614</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2371">
							<a href="http://www.mh163k.com/article/article_2371.html" class="link" onclick="return setCookieID(&#39;2371&#39;);">
								<p class="img">
									<img data-src="./images/2016011021020396667861.png" src="./images/2016011021020396667861.png" data-ifshow="1"></p>
								<p class="tit">多吃豆腐可排毒养颜 豆腐是我国素食菜肴的主要原料，被人们誉为“植物肉”</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：434</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2370">
							<a href="http://www.mh163k.com/article/article_2370.html" class="link" onclick="return setCookieID(&#39;2370&#39;);">
								<p class="img">
									<img data-src="./images/2016011022492459126071.png" src="./images/2016011022492459126071.png" data-ifshow="1"></p>
								<p class="tit">女子忘带钥匙为省80元开锁费7楼爬窗进屋坠亡</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：492</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2369">
							<a href="http://www.mh163k.com/article/article_2369.html" class="link" onclick="return setCookieID(&#39;2369&#39;);">
								<p class="img">
									<img data-src="./images/2016011021015324869001.png" src="./images/2016011021015324869001.png" data-ifshow="1"></p>
								<p class="tit">记网络时代的世界读书日--今天你“读书”了吗</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：418</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2368">
							<a href="http://www.mh163k.com/article/article_2368.html" class="link" onclick="return setCookieID(&#39;2368&#39;);">
								<p class="img">
									<img data-src="./images/2016011021393827918491.png" src="./images/2016011021393827918491.png" data-ifshow="1"></p>
								<p class="tit">错误让你显得很真实，而非完美。职场应注意的九个社交技巧</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：146</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2367">
							<a href="http://www.mh163k.com/article/article_2367.html" class="link" onclick="return setCookieID(&#39;2367&#39;);">
								<p class="img">
									<img data-src="./images/2016011021395402949041.png" src="./images/2016011021395402949041.png" data-ifshow="1"></p>
								<p class="tit">成功求职的三个关键,在求职路上找到“捷径”</p>
								<p class="txt clearfix">
									<span class="left">05-13 11:08</span>
									<span class="right">人气：114</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2366">
							<a href="http://www.mh163k.com/article/article_2366.html" class="link" onclick="return setCookieID(&#39;2366&#39;);">
								<p class="img">
									<img data-src="./images/2016011021400382645121.png" src="./images/2016011021400382645121.png" data-ifshow="1"></p>
								<p class="tit">真正的实战派营销人谈销售拜访的几点技巧</p>
								<p class="txt clearfix">
									<span class="left">05-29 17:12</span>
									<span class="right">人气：74</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2365">
							<a href="http://www.mh163k.com/article/article_2365.html" class="link" onclick="return setCookieID(&#39;2365&#39;);">
								<p class="img">
									<img data-src="./images/2016011021025281045811.png" src="./images/2016011021025281045811.png" data-ifshow="1"></p>
								<p class="tit">一对超萌可爱的母女猫,大学自习室2只贪睡母女猫走红</p>
								<p class="txt clearfix">
									<span class="left">05-29 16:43</span>
									<span class="right">人气：67</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2364">
							<a href="http://www.mh163k.com/article/article_2364.html" class="link" onclick="return setCookieID(&#39;2364&#39;);">
								<p class="img">
									<img data-src="./images/2016011022493716968391.png" src="./images/2016011022493716968391.png" data-ifshow="1"></p>
								<p class="tit">广州长隆水上乐园举办“万人比基尼”活动</p>
								<p class="txt clearfix">
									<span class="left">05-29 16:41</span>
									<span class="right">人气：87</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2363">
							<a href="http://www.mh163k.com/article/article_2363.html" class="link" onclick="return setCookieID(&#39;2363&#39;);">
								<p class="img">
									<img data-src="./images/2016011022252491999431.png" src="./images/2016011022252491999431.png" data-ifshow="1"></p>
								<p class="tit">小伙租喜来登国际金融中心2000平米广告屏求婚引围观</p>
								<p class="txt clearfix">
									<span class="left">05-29 16:28</span>
									<span class="right">人气：90</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2362">
							<a href="http://www.mh163k.com/article/article_2362.html" class="link" onclick="return setCookieID(&#39;2362&#39;);">
								<p class="img">
									<img data-src="./images/2016011021163541911821.png" src="./images/2016011021163541911821.png" data-ifshow="1"></p>
								<p class="tit">食疗养生攻略：八种果蔬最能帮助女性吃出易瘦体质</p>
								<p class="txt clearfix">
									<span class="left">05-29 16:15</span>
									<span class="right">人气：52</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2361">
							<a href="http://www.mh163k.com/article/article_2361.html" class="link" onclick="return setCookieID(&#39;2361&#39;);">
								<p class="img">
									<img data-src="./images/2016011022254484121691.png" src="./images/2016011022254484121691.png" data-ifshow="1"></p>
								<p class="tit">过年回家花费调查凉透网友心 八成网友恐归！</p>
								<p class="txt clearfix">
									<span class="left">05-29 16:11</span>
									<span class="right">人气：50</span>
								</p>
							</a>
						</li>
						<li class="haspic1" id="item2360">
							<a href="http://www.mh163k.com/article/article_2360.html" class="link" onclick="return setCookieID(&#39;2360&#39;);">
								<p class="img">
									<img data-src="./images/2016011022495045160831.png" src="./images/2016011022495045160831.png" data-ifshow="1"></p>
								<p class="tit">全国乘用车市场信息联席会指出5月车市偏好 自主品牌现复苏迹象</p>
								<p class="txt clearfix">
									<span class="left">05-29 16:03</span>
									<span class="right">人气：51</span>
								</p>
							</a>
						</li>
						
					</ul>
					<div id="pullUp" style="display: none;">
						<span class="loader">loadding</span>
					</div>
				</div>
				<div id="reload" style="">
					<s class="s"></s>
					<span class="txt">下拉可以刷新</span>
					<br>
					最后更新：
					<span class="time">今天 17:46</span>
				</div>
				<span class="loader" id="pageLoader" style="display: none;">loadding</span>
			</div>
		</div>
		<div class="foot_link" id="foot_link" style="display: none;">
			<ul class="link">
				<li>
					<a href="http://www.mh163k.com/">首页</a>
				</li>
				<!--<li>
				<a href="http://www.mh163k.com/request.ashx?action=iswap&iswap=0">电脑版</a>
			</li>
			-->
			<li>
				<a href="http://app.163k.com/download.aspx?id=12299" data-img="../UploadFile/index/2015/9-7/201509071154513472674.png">客户端</a>
			</li>
			<li>
				<a href="http://www.mh163k.com/service/">反馈留言</a>
			</li>
			<li class="po_re">
				<a href="javascript:void(0);" id="shangjiaSelect" data-isshow="0">工作平台</a>
				<div class="po" id="shangjiaSelectPo">
					<p>
						<a href="http://www.mh163k.com/member/userindex_s.aspx">商家平台</a>
					</p>
					<p>
						<a href="http://www.mh163k.com/member/index_qy.aspx">企业平台</a>
					</p>
					<p>
						<a href="http://www.mh163k.com/member/index_zj.aspx">中介平台</a>
					</p>
					<p>
						<a href="http://www.mh163k.com/member/peisong.aspx">配送员</a>
					</p>
				</div>
			</li>
		</ul>
		<!--163k地方门户网站系统：<a href="http://www.mh163k.com/">mh163k.com</a>
	京ICP备06006761号-->Copyright @ 2003-2016 mh163k.com
</div>
<p style="display:none;"></p>
</div>
<div class="windowIframe" id="windowIframe" data-loaded="0">
<div class="header">
	<a href="http://www.mh163k.com/" class="back close" onclick="">返回</a>
	<span id="windowIframeTitle"></span>
</div>
<div class="body" id="windowIframeBody"></div>
</div>
<div id="l-map" style="display:none;"></div>
<script src="./js/wap_common.js"></script>
<script>
if(isapp === '1'){
	YDB.SetDragRefresh(0);
}
window['siteUrl'] = 'http://www.mh163k.com/';
document.addEventListener('DOMContentLoaded',function(){
	$('#nav_ico').click(function(e){
		e.preventDefault();
		$('#nav_APP').fadeToggle('fast');
	});
	IDC2.footWorker();
	IDC2.isLogin('http://www.mh163k.com/','163k地方门户网站系统','10001');
	if(!window['ipageTitle']){
		$('#ipageTitle').show();
	}else{
		$('#ipageTitle').html(window['ipageTitleTxt']).show();
	}
},false);
</script>
<input id="pagenum" type="hidden" value="1">
<input id="bigcatid" type="hidden" value="">
<input id="smallcatid" type="hidden" value="">
<script type="text/template" id="tp">
<li class="haspic{{hasImg}}" id="item{{newsid}}">
	<a href="article_{{newsid}}.html" class="link" onclick="return setCookieID('{{newsid}}');">
	<p class="img"><img data-src="{{filepath}}" src="http://www.mh163k.com/template/wap/main/default/images/livelistnopic.gif" data-ifshow="0" /></p>
	<p class="tit">{{chrtitle}}</p>
	<p class="txt clearfix"><span class="left">{{dtappenddate}}</span><span class="right">人气：{{hits}}</span></p>
	</a>
</li>
</script>
<script type="text/javascript" src="./js/jquery.cookie.js"></script>
<script type="text/javascript" src="./js/purl.js"></script>
<script type="text/javascript" src="./js/mustache.js"></script>
<script type="text/javascript" src="./js/iscroll-probe.js"></script>
<script type="text/javascript" src="./js/getArticlePage.js"></script>
<script>
var siteUrl = 'http://www.mh163k.com/';
function setCookieID(sid){
	$.cookie('myZXsid',sid,{path:'/',expires:10});
	return true;
}
function showCatA(o,sid){
	if(sid===''){
		$('#slide').show();
	}else{
		$('#slide').hide();
	}
	$('#bigcatid').val(sid);
	//$('#pagenum').val('1');
	getPageData('2');
	$(o).siblings('.current').removeClass('current');
	$(o).addClass('current');
	window['myScroll2'].scrollToElement($(o)[0],500)
}
window['myScroll2'] = null;
(function($){
	$('.header .back').attr({'onclick':'',href:'http://www.mh163k.com/'});
	$('#search_ico').show();
	$('#login_ico').hide();
	var w_w = $(window).width();
	$('#foot_link').hide();
	var list = $('#content').find('.cell');
	if(list.length > 0){
		var txt = '';
		list.each(function(i){
			if(i === 0){
				txt += '<li class="active">1</li>';
			}else{
				txt += '<li>'+(i+1)+'</li>';
			}
		});
		$('#slide').show();
		$('#indicator').html(txt);
		window['myScroll1'] = new C_Scroll({container:'slide',content:'content',ct:'indicator',size:w_w,intervalTime:5000,lazyIMG:!!0});
	}
	var star_nav = $('#scroller2');
	star_nav.css('width',(90*star_nav.find('li').length)+'px'); 
	window['myScroll2'] = new IScroll('#wrapper2', {
		scrollX: true,
		scrollY: false,
		click:true,
		keyBindings: true
	});
	$('#iscrollto').click(function(e){
		e.preventDefault();
		if(!$(this).parent().hasClass('open')){
			$(this).parent().addClass('open');
		}else{
			$(this).parent().removeClass('open');
		}
	});
	$('#cloneNav').html(star_nav.html());
})(jQuery);

var searchHtml = '<div class="searchbar2">'+
	'<form id="myform" action="list.aspx" method="get">'+
		'<input type="hidden" name="action" value="s" />'+
		'<input type="hidden" name="key" value="0" />'+
		'<input type="text" name="a" id="meSleKey" class="s_ipt" value="" placeholder="输入关键字" />'+
		'<input type="submit" class="s_btn po_ab" value="搜索">'+
	'</form></div>';
function newPageSearch(){
	var myform = $('#myform');
	if($('#meSleKey').val() !== ''){$('#mySle').val('1');myform.attr({'action':'?'});}
	$('#mySle').change(function(){
		var val = $(this).val();
		if(val === '1'){
			myform.attr({'action':'?'});
		}else{
			myform.attr({'action':'../k_good_a0_b0_c0_d0_e0_f0_g0_p1.html'});
		}
	}); 
}
window.onload=function(){loaded_page();}
</script>

</body>
</html>