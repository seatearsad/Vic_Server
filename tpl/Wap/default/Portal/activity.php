<!DOCTYPE html>
<!-- saved from url=(0030)http://www.mh163k.com/huodong/ -->
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <title>同城活动栏目首页-163k地方门户网站系统</title>
    <meta name="keywords" content="同城活动栏目关键词,关键词,关键词,关键词,关键词,关键词,关键词,关键词">
    <meta name="description" content="同城活动栏目介绍">
    <link rel="stylesheet" rev="stylesheet" href="./css/member-mb.css">
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
<body>
    <div id="pageMain">
        <div class="top_gg po_re" id="top_gg" style="display:none;">
            {$Mh163k_wap_首页_顶部广告}
            <span class="close po_ab">关闭</span>
        </div>
        <div class="header">
            <a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
            <div class="search" id="search_ico" onclick="showNewPage(&#39;搜索&#39;,searchHtml,newPageSearch);" style="display:none;">搜索</div>
            <a href="http://www.mh163k.com/member/" class="my ico_ok" id="login_ico">我的</a>
            <div class="type" id="nav_ico">导航</div>
            <span id="ipageTitle" style="">同城活动</span>
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
                            <s class="s" style="background-color:#7778b5; background-image:url(./images/201701101210535444969.png);"></s>
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
        <div class="o_main">
            <div class="nav_APP showNavApp clearfix">
                <ul>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=3">
                            吃喝玩乐
                            <s class="s" style="background-color:#ff9933; background-image:url(./images/201508111711485766795.png);"></s>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=4">
                            亲子活动
                            <s class="s" style="background-color:#ff645a; background-image:url(./images/201508111713370767555.png);"></s>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=5">
                            户外运动
                            <s class="s" style="background-color:#5adcc8; background-image:url(./images/201508111714192795535.png);"></s>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=6">
                            购物活动
                            <s class="s" style="background-color:#73c257; background-image:url(./images/201508111715451075903.png);"></s>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=7">
                            出游活动
                            <s class="s" style="background-color:#73c257; background-image:url(./images/201508111716204678039.png);"></s>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=8">
                            公益活动
                            <s class="s" style="background-color:#3399ff; background-image:url(./images/201508111717124368704.png);"></s>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=9">
                            集体观影
                            <s class="s" style="background-color:#7778b5; background-image:url(./images/201508111717397645221.png);"></s>
                        </a>
                    </li>
                    <li>
                        <a href="http://www.mh163k.com/huodong/?colname=10">
                            聚会交友
                            <s class="s" style="background-color:#ff9933; background-image:url(./images/201508111718140295843.png);"></s>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="filter2" id="filter2">
                <ul class="tab clearfix">
                    <li class="item">
                        <a href="javascript:void(0);">
                            <span>全部分类</span> <em></em>
                        </a>
                    </li>
                    <li class="item">
                        <a href="javascript:void(0);">
                            <span>全部区域</span> <em></em>
                        </a>
                    </li>
                </ul>
                <div class="inner" style="display:none;">
                    <ul>
                        <li class="current">
                            <a href="http://www.mh163k.com/huodong/?">全部活动</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=3&amp;keyword=&amp;a=">吃喝玩乐</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=4&amp;keyword=&amp;a=">亲子活动</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=5&amp;keyword=&amp;a=">户外运动</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=6&amp;keyword=&amp;a=">购物活动</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=7&amp;keyword=&amp;a=">出游活动</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=8&amp;keyword=&amp;a=">公益活动</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=9&amp;keyword=&amp;a=">集体观影</a>
                        </li>
                        <li>
                            <a href="http://www.mh163k.com/huodong/?colname=10&amp;keyword=&amp;a=">聚会交友</a>
                        </li>
                    </ul>
                </div>
                <div class="inner" style="display:none;" <ul="">
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=0" data-id="0" id="s_areaid_0" class="selected">全部区域</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=1" data-id="1" id="s_areaid_1">朝阳区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=2" data-id="2" id="s_areaid_2">海淀区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=11" data-id="11" id="s_areaid_11">丰台区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=12" data-id="12" id="s_areaid_12">西城区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=13" data-id="13" id="s_areaid_13">通州区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=77" data-id="77" id="s_areaid_77">东城区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=78" data-id="78" id="s_areaid_78">昌平区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=79" data-id="79" id="s_areaid_79">宣武区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=80" data-id="80" id="s_areaid_80">崇文区</a>
                    <a href="http://www.mh163k.com/huodong/?a=&amp;colname=&amp;keyword=82" data-id="82" id="s_areaid_82">其他区县</a>

                </div>

                <div class="inner_parent" id="parent_container" style="display:none;">
                    <div class="innercontent"></div>
                </div>
                <div class="inner_child" id="inner_container" style="display:none;">
                    <div class="innercontent"></div>
                </div>
            </div>
            <div class="fullbg" id="fullbg" style="display:none;"> <i class="pull2"></i>
            </div>
            <!--列表-->
            <div class="pic_3_list">
                <ul>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_33.html">
                            <div class="pic"> <sup class="bm2"></sup>
                                <img src="./images/2015051009415954813752.jpg" alt=""></div>
                            <h3>
                                环美家居首届装修家具狂欢节
                                <span class="bao">20人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">九月三十号全天</p>
                                <p class="address">黄河路126号环美家具世界一层大厅</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_32.html">
                            <div class="pic"> <sup class="bm1"></sup>
                                <img src="./images/2015051009413742398222.jpg" alt=""></div>
                            <h3>
                                前生注定 喜结良缘 520集体婚礼活动
                                <span class="bao">33人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">2016年5月20日</p>
                                <p class="address">北京市朝阳区百汇广场</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_31.html">
                            <div class="pic">
                                <sup class="bm3"></sup>
                                <img src="./images/2015051009584157913062.jpg" alt=""></div>
                            <h3>
                                北京周末相亲会,硕博海归单身白领相亲活动!
                                <span class="bao">179人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">2月14号下午</p>
                                <p class="address">舟运区文化体育馆</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_30.html">
                            <div class="pic">
                                <sup class="bm2"></sup>
                                <img src="./images/2015051010035748575962.jpg" alt=""></div>
                            <h3>
                                北漂户外 5月16-17日房山十渡漂流 彩弹CS&amp;竹筏
                                <span class="bao">28人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">7月6号周日上午</p>
                                <p class="address">鹋名城</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_29.html">
                            <div class="pic">
                                <sup class="bm2"></sup>
                                <img src="./images/2015051010052034496262.jpg" alt=""></div>
                            <h3>
                                2015年青少年暑假“少年商学院训练营”6天5夜-理财
                                <span class="bao">48人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">4月9号-15号</p>
                                <p class="address">世纪广场南门口</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_28.html">
                            <div class="pic">
                                <sup class="bm1"></sup>
                                <img src="./images/2015051010061053215402.jpg" alt=""></div>
                            <h3>
                                招募通告:招募真心热爱流行音乐,时尚活动的朋友
                                <span class="bao">25人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">十月二号全天</p>
                                <p class="address">朝阳区工人文化宫</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_27.html">
                            <div class="pic">
                                <sup class="bm3"></sup>
                                <img src="./images/2015051010135175161352.jpg" alt=""></div>
                            <h3>
                                北京通州宋庄奶油草莓采摘、草莓采摘、红颜草莓采摘开始啦
                                <span class="bao">197人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">七月七全天</p>
                                <p class="address">北京市东城区东四环南路9号</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_26.html">
                            <div class="pic">
                                <sup class="bm1"></sup>
                                <img src="./images/2015051010161768883892.jpg" alt=""></div>
                            <h3>
                                维视力青少年近视康复中心爱心公益免费体验活动
                                <span class="bao">270人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">10月10日下午</p>
                                <p class="address">北京市东城区东四环南路9号</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_25.html">
                            <div class="pic">
                                <sup class="bm1"></sup>
                                <img src="./images/2015051010170279866222.jpg" alt=""></div>
                            <h3>
                                招募天通苑附近羽毛球爱好者打球
                                <span class="bao">169人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">8月24日周六</p>
                                <p class="address">北京市东城区东四环南路9号</p>
                            </div>
                        </a>
                    </li>

                    <li>
                        <a href="http://www.mh163k.com/huodong/huodong_24.html">
                            <div class="pic">
                                <sup class="bm2"></sup>
                                <img src="./images/2015051010383906311462.jpg" alt=""></div>
                            <h3>
                                70后相约怀柔踏青烧烤露营自驾游
                                <span class="bao">167人已报</span>
                            </h3>
                            <div class="clearfix">
                                <p class="time">6月29日</p>
                                <p class="address">北京市东城区东四环南路9号</p>
                            </div>
                        </a>
                    </li>
                </ul>
                <div class="pageNav">
                    <div class="FirstPage">
                        <a class="kill" href="javascript:;">&lt;</a>
                    </div>
                    <div class="EndPage">
                        <a href="http://www.mh163k.com/huodong/?Colname=&amp;PageNo=2&amp;KeyWord=&amp;a=" title="下一页">&gt;</a>
                    </div>
                    1/2
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
                <a href="javascript:;" class="back close">返回</a>
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
  IDC2.isLogin('http://www.mh163k.com/','163k地方门户网站系统','4001');
  if(!window['ipageTitle']){
    $('#ipageTitle').show();
  }else{
    $('#ipageTitle').html(window['ipageTitleTxt']).show();
  }
},false);
</script>
        <script type="text/javascript" src="./js/iscroll.js"></script>
        <script>
function getnum(){
  var list = $('.suc_active'),txt_id_arr = [];
  list.each(function(){
    txt_id_arr.push($(this).attr('data-id'));
  });
  if(txt_id_arr.length<1){
    return false;
  }
  var url = 'http://www.mh163k.com/request.ashx?action=chrnum&key=active&id='+txt_id_arr.join(',')+'&jsoncallback=?';
  var arr = [];
  $.getJSON(url,function(data){
    arr = data[0]['MSG'];
    for(var i=0;i<arr.length;i++){
      for(var k in arr[i]){
        $('#suc_'+k).html(arr[i][k][0]['intnum']);
      }
    }
  });
}
(function($){
  window['myScroll_parent'] = null;
  window['myScroll_inner'] = null;
  showFilter({ibox:'filter2',content1:'parent_container',content2:'inner_container',fullbg:'fullbg'});
  getnum();
})(jQuery);
</script></div>
</body>
</html>