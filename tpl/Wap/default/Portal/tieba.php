<!DOCTYPE html>
<!-- saved from url=(0035)http://www.mh163k.com/tieba/?page=1 -->
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>贴吧-163k地方门户网站系统</title>
	<!-- UC默认竖屏 ，UC强制全屏 -->
	<meta name="full-screen" content="yes">
	<meta name="browsermode" content="application">
	<!-- QQ强制竖屏 QQ强制全屏 -->
	<meta name="x5-orientation" content="portrait">
	<meta name="x5-fullscreen" content="true">
	<meta name="x5-page-mode" content="app">
	<meta name="keywords" content="贴吧">
	<meta name="description" content="贴吧">
	<link href="./css/tieba-mb.css" rel="stylesheet">
	<link rel="stylesheet" rev="stylesheet" href="./css/news-scroll5.css">
	<style type="text/css">
.foot_link { margin-top:0!important;}
#pageNavigation { display:none;}
#noMore { padding:10px 0 20px;}
#hideHead,#hideHead2 { background-color:#eee; padding-bottom:10px;}
#listEmpty { display:none!important;}
.headerblack { background-color:#000!important;}
</style>
	<script>
window['Bigcategory'] = '0';
window['Default_tplPath'] = 'http://www.mh163k.com/template/wap/main/default/';
</script>
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
			<span id="ipageTitle" style="">贴吧</span>
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
							<s class="s" style="background-color:#d81e06; background-image:url(../UploadFile/index/2017/1-9/201701091601176021129.png);"></s>
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
							<s class="s" style="background-color:#7778b5; background-image:url(../UploadFile/index/2017/1-10/201701101210535444969.png);"></s>
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

		<div class="p_main">
			<div class="posts">
				<div id="hideHead" style="display: block;">
					<div id="slide" class="clearfix" style="width: 360px;">
						<div id="content2" style="width: 1080px; transform: translate3d(0px, 0px, 0px) scale(1);">
							<div class="cell" style="width: 360px;">
								<a href="http://www.mh163k.com/tieba/?page=1#3">
									<img src="./images/201608082202239233052.jpg" alt=""></a>
							</div>
							<div class="cell" style="width: 360px;">
								<a href="http://www.mh163k.com/tieba/?page=1#2">
									<img src="./images/201608082201501421702.jpg" alt=""></a>
							</div>
							<div class="cell" style="width: 360px;">
								<a href="http://www.mh163k.com/tieba/?page=1#1">
									<img src="./images/201608082200534661578.jpg" alt=""></a>
							</div>
						</div>
						<ul id="indicator2">
							<li class="active">1</li>
							<li class="">2</li>
							<li class="">3</li>
						</ul>
					</div>
					<span class="prev" id="slide_prev" style="display:none">上一张</span>
					<span class="next" id="slide_next" style="display:none">下一张</span>
				</div>
				<div id="hideHead2">
					<div class="p_tabs clearfix">
						<ul>
							<li id="s_e_0" class="cur">
								<span data-catid="0" onclick="return showCatB({&#39;e&#39;:&#39;0&#39;,&#39;h&#39;:&#39;0&#39;},&#39;#smallHuifuTxt&#39;,&#39;s_e_&#39;,this);">最新回复</span>
							</li>
							<li id="s_e_1">
								<span data-catid="1" onclick="return showCatB({&#39;e&#39;:&#39;1&#39;,&#39;h&#39;:&#39;0&#39;},&#39;#smallHuifuTxt&#39;,&#39;s_e_&#39;,this);">最新发布</span>
							</li>
							<li id="s_e_3">
								<span data-catid="3" onclick="return showCatB({&#39;e&#39;:&#39;3&#39;,&#39;h&#39;:&#39;0&#39;},&#39;#smallHuifuTxt&#39;,&#39;s_e_&#39;,this);">热帖排行</span>
							</li>
							<li id="s_h_1">
								<span data-catid="1" onclick="return showCatB({&#39;h&#39;:&#39;1&#39;},&#39;#smallCatTxt&#39;,&#39;s_h_&#39;,this);">精华热帖</span>
							</li>
						</ul>
					</div>
				</div>
				<input id="pagenum" type="hidden" value="1">
				<div id="wrapper" style="top: 271px; transform: translate3d(0px, 0px, 0px); transition: transform 0.6s; height: auto;">
					<div id="scroller" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
						<div id="pullDown" style="display:none;">
							<span class="loader" style="display:none;">loadding</span>
						</div>

						<div class="post_list">
							<ul id="pagingList">
								<div class="item iszhiding5" id="item114">
									<h2>
										<span class="d display5">顶</span>
										<span class="j display1">精</span>
										<a href="" style="color:" class="link bold1" onclick="return setCookieID(&#39;114&#39;);">----＝＝＝＝贴吧使用指南＝＝＝＝＝-----</a>
									</h2>
									<dl>
										<dt>
											<span class="chrname">x4demo1</span>
											<span class="revertnum">844</span>
											阅读
										</dt>
										<dd>
											<span class="stime">07月18日 10:35</span>
										</dd>
									</dl>
									<div class="manage">
										<a href="http://www.mh163k.com/addedit.aspx?action=edit&amp;id=114" class="blue">编辑</a>
										<a href="javascript:void(0);" onclick="superManage(&#39;114&#39;);" class="blue">超级管理</a>
										<a href="javascript:void(0);" onclick="$.managerTBisdel(&#39;114&#39;);" class="blue">删除</a>
									</div>
								</div>

								<div class="item iszhiding0" id="item39">
									<h2>
										<span class="d display0">顶</span>
										<span class="j display0">精</span>
										<a href="#" style="color:" class="link bold0" onclick="return setCookieID(&#39;39&#39;);">两支廉价长焦间纠结，求大神指点</a>
									</h2>
									<div class="con">
										<div class="n_img" id="n_img_39" data-ischeck="1">
											<a href="./images/636075837713982884649873001.png" target="_blank" class="itemAlbum" original="./images/636075837713982884649873001.png">
												<img src="./images/636075837713982884649873001.png" data-src="./images/636075837713982884649873001.png" original="./images/636075837713982884649873001.png" data-ifshow="1" alt="" style="width: 106px; height: 79px;">
											</a>
											<a href="http://www.mh163k.com/UploadFile/tieba/image/20160823/63607583770320142780691280.png" target="_blank" class="itemAlbum" original="/UploadFile/tieba/image/20160823/63607583770320142780691280.png">
												<img src="./images/636075837703201427806912801.png" data-src="/UploadFile/tieba/image/20160823/636075837703201427806912801.png" original="/UploadFile/tieba/image/20160823/63607583770320142780691280.png" data-ifshow="1" alt="" style="width: 106px; height: 79px;">
												<div class="feed_highlight"></div>
											</a>
											<a href="http://www.mh163k.com/UploadFile/tieba/image/20160823/63607583766695073161950030.png" target="_blank" class="itemAlbum" original="/UploadFile/tieba/image/20160823/63607583766695073161950030.png">
												<img src="./images/636075837666950731619500301.png" data-src="/UploadFile/tieba/image/20160823/636075837666950731619500301.png" original="/UploadFile/tieba/image/20160823/63607583766695073161950030.png" data-ifshow="1" alt="" style="width: 106px; height: 79px;">
												<div class="feed_highlight"></div>
											</a>
											<a href="http://www.mh163k.com/UploadFile/tieba/image/20160823/63607583764523156435650450.png" target="_blank" class="itemAlbum" original="/UploadFile/tieba/image/20160823/63607583764523156435650450.png">
												<img src="./images/636075837645231564356504501.png" data-src="/UploadFile/tieba/image/20160823/636075837645231564356504501.png" original="/UploadFile/tieba/image/20160823/63607583764523156435650450.png" data-ifshow="1" alt="" style="width: 106px; height: 79px;">
												<div class="feed_highlight"></div>
											</a>
										</div>
									</div>
									<dl>
										<dt>
											<span class="chrname">x4demo2</span>
											<span class="revertnum">264</span>
											阅读
										</dt>
										<dd>
											<span class="stime">07月13日 14:32</span>
										</dd>
									</dl>
									<div class="manage">
										<a href="http://www.mh163k.com/addedit.aspx?action=edit&amp;id=39" class="blue">编辑</a>
										<a href="javascript:void(0);" onclick="superManage(&#39;39&#39;);" class="blue">超级管理</a>
										<a href="javascript:void(0);" onclick="$.managerTBisdel(&#39;39&#39;);" class="blue">删除</a>
									</div>
								</div>
							</ul>
							<div id="pullUp" style="display:none;">
								<span class="loader">loadding</span>
							</div>
							<div class="pageNavigation" id="pageNavigation">
								<div class="FirstPage">
									<span class="kill">&lt;</span>
								</div>
								<div class="EndPage">
									<a href="http://www.mh163k.com/tieba/?page=1#" onclick="return getPagingGlobal({&#39;p&#39;:&#39;2&#39;});" title="下一页">&gt;</a>
								</div>
								1/6
							</div>
						</div>
						<div style="height:50px; background-color:#eee;"></div>
					</div>

					<div id="reload" style="">
						<s class="s"></s>
						<span class="txt">下拉可以刷新</span>
						<br>
						最后更新：
						<span class="time">今天 17:47</span>
					</div>
					<span class="loader" id="pageLoader" style="display: none;">loadding</span>
				</div>

			</div>

			<div class="foot_link" id="foot_link">
				<ul class="link">
					<li>
						<a href="http://www.mh163k.com/">首页</a>
					</li>

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
	IDC2.isLogin('http://www.mh163k.com/','163k地方门户网站系统','25001');
	if(!window['ipageTitle']){
		$('#ipageTitle').show();
	}else{
		$('#ipageTitle').html(window['ipageTitleTxt']).show();
	}
},false);
</script>
		<div class="nav_index_bottom nav_tb_bottom">
			<ul>
				<li>
					<a href="http://www.mh163k.com/">
						<span class="home"></span>
						首页
					</a>
				</li>
				<li id="nav_bankuai">
					<a href="javascript:void(0);" onclick="return showCatState(false);">
						<span class="bankuai"></span>
						版块
					</a>
				</li>
				<li>
					<a href="javascript:void(0);" id="seniorSend" class="seniorSend">
						<span class="fatie"></span>
						发帖
					</a>
				</li>
				<li>
					<a href="javascript:void(0);" id="refresh">
						<span class="refresh"></span>
						刷新
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
		<div class="fixed_cat" id="fixed_cat" data-isshow="0" style="display:none; left:-160px;">
			<ul style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
				<li id="s_a_0" class="cur">
					<span data-bazhu="0" data-catid="0" onclick="return showCatA({&#39;a&#39;:&#39;0&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this)">全部版块</span>
				</li>
				<li id="s_a_2258">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2258&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2258" data-bazhu=" x4demo1 wenxin">
						京城茶馆
						<s>22</s>
					</span>
				</li>
				<li id="s_a_2263">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2263&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2263" data-bazhu=" x4demo1">
						风味美食
						<s>21</s>
					</span>
				</li>
				<li id="s_a_2276">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2276&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2276" data-bazhu=" aufly wenxin">
						通讯数码
						<s>21</s>
					</span>
				</li>
				<li id="s_a_2265">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2265&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2265" data-bazhu=" future">
						驴行天下
						<s>21</s>
					</span>
				</li>
				<li id="s_a_2272">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2272&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2272" data-bazhu=" energy">
						家装交流
						<s>21</s>
					</span>
				</li>
				<li id="s_a_2261">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2261&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2261" data-bazhu="暂时没有版主!">
						灌水拍砖
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2260">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2260&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2260" data-bazhu="暂时没有版主!">
						上班一族
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2259">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2259&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2259" data-bazhu="暂时没有版主!">
						情感驿站
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2262">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2262&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2262" data-bazhu="暂时没有版主!">
						文化沙龙
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2264">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2264&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2264" data-bazhu="暂时没有版主!">
						时尚购物
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2266">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2266&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2266" data-bazhu="暂时没有版主!">
						休闲娱乐
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2267">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2267&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2267" data-bazhu="暂时没有版主!">
						亲子乐园
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2268">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2268&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2268" data-bazhu="暂时没有版主!">
						健康生活
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2269">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2269&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2269" data-bazhu="暂时没有版主!">
						教育培训
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2270">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2270&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2270" data-bazhu="暂时没有版主!">
						家有宠物
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2271">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2271&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2271" data-bazhu="暂时没有版主!">
						京城房产
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2273">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2273&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2273" data-bazhu="暂时没有版主!">
						喜庆婚嫁
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2274">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2274&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2274" data-bazhu="暂时没有版主!">
						车友汇聚
						<s>0</s>
					</span>
				</li>
				<li id="s_a_2275">
					<span onclick="return showCatA({&#39;a&#39;:&#39;2275&#39;,&#39;b&#39;:&#39;0&#39;},&#39;s_a_&#39;,this);" data-catid="2275" data-bazhu="暂时没有版主!">
						理财股市
						<s>0</s>
					</span>
				</li>
			</ul>
		</div>
		<div class="showBigScroll5" id="showBigScroll5">
			<div class="header headerblack">
				<a href="http://www.mh163k.com/" class="back" style="width:100px; text-align:left; padding-left:15px;" onclick="">收起相册</a>
			</div>
			<img src="http://www.mh163k.com/tieba/?page=1" id="bigPic" class="bigPic" alt="">
			<div id="container" class="wrap_scroll5" data-isloaded="0" style="display:none;">
				<div id="content" class="scroller"></div>
				<div id="indicator" class="indicator">
					<div id="dotty" class="dotty"></div>
				</div>
			</div>
		</div>
		<script src="./js/jquery.cookie.js"></script>
		<script src="./js/purl.js"></script>
		<script src="./js/iscroll-probe.js"></script>
		<script src="./js/commonPaging.js"></script>
		<script src="./js/wap_tieba.js"></script>
		<script src="./js/getTiebaPage.js"></script>
		<script src="./js/wap_Dscroll.js"></script>
		<script>
window['jifenneme'] = '金币';
window['isIscroll5'] = true; //列表是否启用iscroll5
window['isIscroll5_hideHead']= true;
window['islazyImg'] = true; //是否开启了延迟加载图片
var nowdomain ="http://www.mh163k.com/";
var keyvalues = {"table_id":"25",
	"pagesize":"20",
	"tplpath":"tieba",
	"tplname":"paging_wap_tieba_index_list.html",
	"strlen":"60",	
	"titleLen":"80",
	"isjson":"0",
	"p":"1",
	"_key":"",
	"a":"0",//板块
	"b":"0",//小类
	"e":"0",//排序(0默认回复时间倒,1发帖时间倒,2:精华前,3回复数,4:浏览数)
	"g":"0",//只查看某用户贴(userid)
	"h":"0",//只看精华
	"i":"0"//有图
};
function setCookieID(sid){
	$.cookie('myTBsid',sid,{path:'/',expires:10});
	return true;
}
function showCatA(obj,ids,node){
	$('#pagingList').empty();
	$('#pageLoader').show();
	getPagingGlobal(obj,node,ids,'2');
	//$('#bigCatTxt').html($(node).html());
	//$('#fullbg').trigger('click');
	window['Bigcategory'] = obj.a;
	getUserState();
	myScroll&&myScroll.scrollTo(0,0,0);
	return false;
}
function showCatB(obj,sid,ids,node){
	$('#pagingList').empty();
	$('#pageLoader').show();
	getPagingGlobal(obj,node,ids,'2');
	myScroll&&myScroll.scrollTo(0,0,0);
	return false;
}
var searchHtml = '<div class="searchbar2">'+
	'<form id="mySearch" method="get">'+
		'<input type="text" id="keyword" class="s_ipt" placeholder="请输入关键字" />'+
		'<input type="submit" class="s_btn po_ab" value="搜索">'+
	'</form></div>';
function newPageSearch(){
	$('#mySearch').submit(function(e){
		e.preventDefault();
		$('#pagingList').empty();
		$('#pageLoader').show();
		getPagingGlobal({'_key':$('#keyword').val()});
		$('#windowIframe').find('.back').trigger('click');
		myScroll&&myScroll.scrollTo(0,0,0);
	});
}
function showCatState(onlyClose){
	var fixed_cat = $('#fixed_cat'),nav_bankuai = $('#nav_bankuai');
	if(!!onlyClose){
		if(fixed_cat.attr('data-isshow') === '1'){
			fixed_cat.attr('data-isshow','0').animate({'left':'-160px'},500,function(){
				fixed_cat.css('display','none');
			});
			nav_bankuai.removeClass('current');
		}
		return false;
	}
	if(fixed_cat.attr('data-isshow') === '0'){
		fixed_cat.css({'display':'block'}).animate({'left':'0'},500,function(){}).attr('data-isshow','1');
		window['myScroll_bankuai'].scrollTo(0,0,0);
		window['myScroll_bankuai'].refresh();
		nav_bankuai.addClass('current');
	}else{
		fixed_cat.attr('data-isshow','0').animate({'left':'-160px'},500,function(){
			fixed_cat.css('display','none');
		});
		nav_bankuai.removeClass('current');
	}
	return false;
}
window['myScroll_bankuai'] = null;
document.addEventListener('DOMContentLoaded',function(){
	window['myScroll_bankuai'] = new IScroll('#fixed_cat', {
		click: true,
		probeType:2,
		scrollX: true,
		scrollY: true,
		bounce:false,
		freeScroll:false
	});
	
	
	window['myScroll_bankuai'].on('scrollEnd',function(){
		if(this.x < -30){
			showCatState(true);
		}else{
			window['myScroll_bankuai'].scrollTo(0,this.y,300);
		}
	});
	
	$('#search_ico').show();
	$('#login_ico').hide();
	
	var list = $('#content2').find('.cell');
	if(list.length > 0){
		$('#slide').show();
		var txt = '';
		$('#content2').find('.cell').each(function(i){
			if(i === 0){
				txt += '<li class="active">1</li>';
			}else{
				txt += '<li>'+(i+1)+'</li>';
			}
		});
		$('#indicator2').html(txt);
		var w_w = $(window).width();
		setTimeout(function(){new C_Scroll({container:'slide',content:'content2',ct:'indicator2',size:w_w,intervalTime:5000,lazyIMG:!!0});},20);
	}
	
	$('#refresh').click(function(e){
		e.preventDefault();
		$('#pagingList').empty();
		$('#pageLoader').show();
		getPagingGlobal({},null,null,'2');
		myScroll&&myScroll.scrollTo(0,0,0);
	});
	$('.header .back').attr({'href':nowdomain,'onclick':''});
	if('0' !== '0'){
		$('#s_a_0').addClass('cur');
		$('#bigCatTxt').html($('#s_a_0 a').html())
	}else{
		$('#s_a_0').addClass('cur');
	}
	getPagingGlobal();
	//showFilter({ibox:'filter2',content1:'parent_container',content2:'inner_container',fullbg:'fullbg'});
	$('#seniorSend').click(function(e){
		e.preventDefault();
		isfabukill()&&(window.location.href = 'http://www.mh163k.com/tieba/addedit.aspx?bigcategoryid='+keyvalues['a']+'&categoryid='+keyvalues['b']);
		
	});
	function isfabukill(){
		if($('#isfabukill').val() === '0'){
			MSGwindowShow('tieba','1','请登录后再发表新帖！',nowdomain+'member/login.html?from='+encodeURIComponent('http://www.mh163k.com/tieba/addedit.aspx?bigcategoryid='+keyvalues['a']+'&categoryid='+keyvalues['b']),'');
			return false;
		}
		return true;
	}
	$('#pagingList').on('click','.n_img a',function(e){
		e.preventDefault();
		//window.location.href = $(this).parent().parent().parent().find('.link').attr('href');
	});
},false);
window.onload = function(){
	loaded_page();
	$('#wrapper').css({'top':parseInt($('#hideHead').outerHeight() + $('#hideHead2').outerHeight() + 45)+'px'});
	$(window).resize(function(){
		$('#wrapper').css({'top':parseInt($('#hideHead').outerHeight() + $('#hideHead2').outerHeight() + 45)+'px'});
	});
};
</script>

	</div>
</body>
</html>