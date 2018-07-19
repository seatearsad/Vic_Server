<!DOCTYPE html>
<html><head>
<if condition="$zd['status'] eq 1">
            {pigcms{$zd['code']}
        </if>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></script>
<title>{pigcms{$tpl.wxname}</title>

<meta name="format-detection" content="telephone=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}tpl/1334/css/reset.css">
<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}tpl/1350/css/base.css">
 <script type="text/javascript" src="{pigcms{$static_path}tpl/1334/js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}tpl/1334/js/touch.js"></script>
</head>


<body class="bg30 hAndroid hAndroid2x">
<!--music-->
    <if condition="$homeInfo['musicurl'] neq false">
      <include file="Index:music"/>
    </if>
<section id="container">
<if condition="$homeInfo['switch'] eq 0">
  <section class="ap oh carouselBox" id="carouselBox" ontouchstart="touchStart(event)" ontouchmove="touchMove(event);" ontouchend="touchEnd(event);" style="overflow: hidden;">
        <ul class="oh ab carouselPics" id="carouselPics" style="width: 500%; position: relative; left: -640px;">
          <volist name="flash" id="so">   
            <li style="width: 320px;"><a href="{pigcms{$so.url}"><img src="{pigcms{$so.img}"></a></li>
          </volist>
        </ul>
        <ul class="ab tc carouselBtns" id="carouselBtns"></ul>
        <div class="ab carouselBtnsBg"></div>
    </section><!--carouselBox end-->
<else />
    <include file="Index:bannerstyle"/>
</if>
    <ul class="oh menu301" id="menu301">
    	<volist name="info" id="vo"> 
          <li><a href="<if condition="$vo['url'] eq ''">{pigcms{:U('Wap/Index/lists',array('classid'=>$vo['id'],'token'=>$vo['token']))}<else/>{pigcms{$vo.url|htmlspecialchars_decode}</if>"><img src="{pigcms{$vo.img}"><span>{pigcms{$vo.name}</span></a></li>
		</volist>         
    </ul>
</section><!--container end-->
<script type="text/javascript">
var _initX = 0;
var _finishX = 0;
var _startX = 0;
var _startY = 0;
function touchStart(event) {
  _startX = event.touches[0].clientX;
  _startY = event.touches[0].clientY;
  _initX = _startX;
}
function touchMove(event) {
  var touches = event.touches;
  var _endX = event.touches[0].clientX;
  var _endY = event.touches[0].clientY;
  if(Math.abs(_endY-_startY)>Math.abs(_endX-_startX)){
    return;    
  }
  event.preventDefault();
  _finishX = _endX;
  var _absX = Math.abs(_endX-_startX);
  var lastX = $('#carouselPics').css('left').replace('px','');
  if(_startX>_endX){
    st.Stop();
    $('#carouselPics').css('left',(parseInt(lastX)-_absX)+'px');
  }else{
    st.Stop();
    $('#carouselPics').css('left',(parseInt(lastX)+_absX)+'px');
  } 
  _startX = _endX;
}
function touchEnd(event) {
  if(_finishX==0){
    return;
  }
  if(_initX>_finishX){
    bindEvent(_initX,_finishX);
  }else if(_initX<_finishX){
    bindEvent(_initX,_finishX);
  }
  _initX = 0;
  _finishX = 0;
}

var picCount = $("#carouselPics li").length;
  
$("#carouselPics").css('width',picCount+'00%');

var st = createPicMove("carouselBox", "carouselPics", picCount);

var forEach = function(array, callback){
  for (var i = 0, len = array.length; i < len; i++) { callback.call(this, array[i], i); }
}

var nums = [];

for(var i = 0, n = st._count - 1; i <= n;i++){
  var li = document.createElement("li");
  nums[i] = document.getElementById("carouselBtns").appendChild(li);
}

st.onStart = function(){
  forEach(nums, function(o, i){ o.className = st.Index == i ? "current" : ""; })
}  

function bindEvent(start,end){
  if (start >= end) {
    st.Next();
  } else {
    st.Previous();
  }
}

st.Run();

var resetScrollEle = function(){
  var slider2Li = $("#carouselPics li");
  slider2Li.css("width",$(".carouselBox").width()+"px");
  
  var oHeight1 = $(window).height();
  var oHeight2 = $('body').height();
  var oFooterHeight = $('#footer').outerHeight();
  if(oHeight1>oHeight2){
    $('#container').css('min-height',(oHeight1-oFooterHeight));  
  }
};

resetScrollEle();

window.addEventListener("orientationchange",function(){
  st.Change = st._slider.offsetWidth/st._count;
  st.Next();
  resetScrollEle();
});

window.addEventListener("resize",function(){
  st.Change = st._slider.offsetWidth/st._count;
  st.Next();
  resetScrollEle();
});
</script>



<div style="height: 34px;line-height: 34px;font-size: 12px;background: #e3e2dd;text-align: center;color: #999;">
  <p>
  <if condition="$iscopyright eq 1 && $homeInfo.copyright neq ''">
  {pigcms{$homeInfo.copyright}
  <elseif condition="$iscopyright eq 1 && $homeInfo.copyright eq ''"/>
  技术支持：{pigcms{$tpl['wxname']}
  <else/>
  {pigcms{$siteCopyright}
  </if>
  </p>
</div>
<include file="Index:styleInclude"/>
<include file="$cateMenuFileName"/>
<include file="Index:share" />
</body>
</html>