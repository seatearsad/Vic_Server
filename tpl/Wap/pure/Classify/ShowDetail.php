<!DOCTYPE html>
<html lang="en">
<head>
   <title>{pigcms{$detail['title']} - 信息展示</title> 
      <include file="Public:classify_header" />
	  <link href="{pigcms{$static_path}classify/css/idangerous.swiper.css" rel="stylesheet"/>

<style>
.album {
    height: 9.9rem;
    position: relative;
}

.album img {
    width: 100%;
    height: 9.9rem;
    -webkit-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    top: 50%;
    position: relative;
}

.album .desc {
    position: absolute;
    bottom: 0;
    width: 100%;
    color: white;
    text-align: center;
    height: 1.56rem;
    line-height: 1.56rem;
    font-size: 12px;
    background: rgba(0, 0, 0, .5);
}

.albumContainer {
position: fixed;
width: 100%;
height: 100%;
left: 0;
top: 0;
background: #000;
z-index: 1000;
display: none;
overflow: hidden;
-webkit-transform-origin:0px 0px;
opacity:1;
-webkit-transform:scale(1,1);
}

.swiper-slide{
			display: -webkit-box;
			display: -ms-flexbox;
			overflow: hidden;
			-webkit-box-align: center;
			-webkit-box-pack: center;
			-ms-box-align: center;
			-ms-flex-pack: justify;
		}
		.swiper-slide img{
			width:auto;
			height:auto;
			max-width:100%;
			max-height:90%;
		}
		.swiper-pagination{
			left: 0;
			top: 10px;
			text-align: center;
			bottom:auto;
			right:auto;
			width:100%;
		}
		.swiper-close{
			right:10px;
			top:5px;
			text-align:right;
			position:absolute;
			z-index:21;
			color:white;
			font-size:.7rem;
		}
		span.tag{
			background: #fdb338;
			color: #fff;
			line-height: 1.5;
			display: inline-block;
			padding: 0 .06rem;
			font-size: .24rem;
			border-radius: .06rem;
		}
		
		
		#enter_im_div {
		  bottom: 60px;
		  left:10px;
		  z-index: 11;
		  position: fixed;
		  width: 94px;
		  height:31px;
		}
		#enter_im {
		  width: 94px;
		  position: relative;
		  display: block;
		}
		a {
		  color: #323232;
		  outline-style: none;
		  text-decoration: none;
		}
		#to_user_list {
		  height: 16px;
		  padding: 7px 6px 8px 8px;
		  background-color: #00bc06;
		  border-radius: 25px;
		  /* box-shadow: 0 0 2px 0 rgba(0,0,0,.4); */
		}
		#to_user_list_icon_div {
		  width: 20px;
		  height: 16px;
		  background-color: #fff;
		  border-radius: 10px;
		}
		
		.rel {
		  position: relative;
		}
		.left {
		  float: left;
		}
		.to_user_list_icon_em_a {
		  left: 4px;
		}
		#to_user_list_icon_em_num {
		  background-color: #f00;
		}
		#to_user_list_icon_em_num {
		  width: 14px;
		  height: 14px;
		  border-radius: 7px;
		  text-align: center;
		  font-size: 12px;
		  line-height: 14px;
		  color: #fff;
		  top: -14px;
		  left: 68px;
		}
		.hide {
		  display: none;
		}
		.abs {
		  position: absolute;
		}
		.to_user_list_icon_em_a, .to_user_list_icon_em_b, .to_user_list_icon_em_c {
		  width: 2px;
		  height: 2px;
		  border-radius: 1px;
		  top: 7px;
		  background-color: #00ba0a;
		}
		.to_user_list_icon_em_a {
		  left: 4px;
		}
		.to_user_list_icon_em_b {
		  left: 9px;
		}
		.to_user_list_icon_em_c {
		  right: 4px;
		}
		.to_user_list_icon_em_d {
		  width: 0;
		  height: 0;
		  border-style: solid;
		  border-width: 4px;
		  top: 14px;
		  left: 6px;
		  border-color: #fff transparent transparent transparent;
		}
		#to_user_list_txt {
		  color: #fff;
		  font-size: 13px;
		  line-height: 16px;
		  padding: 1px 3px 0 5px;
		}
				.w-goods-price{
			color: #9E9E9E;
			font-size: .26rem;
			  height: .46rem;
		}
		/*.w-progressBar {
  margin-right: 50px;
}*/
		.w-progressBar .wrap {
  position: relative;
  margin-bottom: 8px;
  height: 5px;
  border-radius: 5px;
  background-color: #efeeee;
  overflow: hidden;
}
.w-progressBar .bar, .w-progressBar .color {
  display: block;
  height: 100%;
  border-radius: 4px;
}
.w-progressBar .bar {
  overflow: hidden;
}
.w-progressBar .color {
  width: 100%;
  background: #FFA538;
  background: -webkit-gradient(linear,left top,right top,from(#FFCB3D),to(#FF8533));
  background: -moz-linear-gradient(left,#FFCB3D,#FF8533);
  background: -o-linear-gradient(left,#FFCB3D,#FF8533);
  background: -ms-linear-gradient(left,#FFCB3D,#FF8533);
}
.w-progressBar .txt {
  overflow: hidden;
}
.w-progressBar li {
  float: left;
  color: #9E9E9E;
			font-size: .26rem;
}
.w-progressBar .txt b {
  font-weight: normal;
}
.w-progressBar .txt-r {
  float: right;
  border: 0;
  text-align: right;
}
.finish_tip {
  display: inline-block;
  margin-left: 30px;
  color: red;
}
.txt-blue {
  color: #0079fe;
}
.m-detail-userCodes {
  background: #f4f4f4;
  color: #999999;
}
.m-detail-userCodes-blank {
  text-align: center;
  color: #8f8f8f;
}
.w-bar {
  display: block;
  overflow: hidden;
  position: relative;
  color: #525252;
  background: #fff;
}
.w-bar-hint {
    font-size: .26rem;
  color: #8f8f8f;
}
.m-detail-record-list li {
  margin-bottom: 14px;
}
.m-detail-record-time {
  display: inline-block;
  margin-bottom: 14px;
  padding: 0 5px;
  font-size: 10px;
  line-height: 15px;
  background: #f4f4f4;
  border-radius: 15px;
  border: 1px solid #d5d5d5;
}
.m-detail-record-list .avatar {
  float: left;
  margin-top: 2px;
  border-radius: 50%;
  overflow: hidden;
}
.m-detail-record-list .text {
  margin-left: 45px;
}
.m-detail-record-list .text p{
    font-size: .228rem;
	  line-height: 1.9;
}
.f-breakword {
  white-space: normal;
  word-wrap: break-word;
  word-break: break-all;
}
.m-detail-record-list .address {
  display: inline-block;
  word-wrap: no-wrap;
  word-break: no-wrap;
}
.m-detail-record-list .num {
  color: #525252;
}
.txt-red {
  color: #db3652;
}
dl.list .dd-padding.m-detail-record-wrap {
  margin-left: 28px;
  padding: 10px 10px 0 0;
  border-left: 1px solid #d5d5d5;
  overflow:visible;
}
.m-detail-record-list {
  margin-left: -18px;
}
 .m-simpleFooter {
  position: fixed;
  z-index: 2;
  left: 0;
  right: 0;
  border: 1px solid #D4D4D4;
  background: rgba(240,240,240,.8);
    padding: 8px 10px;
  bottom: 0;
  border-width: 1px 0;
  line-height: 32px;
  height: 32px;
}
.w-button {
  text-align: center;
  white-space: nowrap;
  font-size: 14px;
  display: inline-block;
  vertical-align: middle;
  color: #fff;
  background: #3399FE;
  border-width: 0;
  border-style: solid;
  border-color: #1B7DE0;
  padding: 0 15px;
  text-align: center;
  height: 30px;
  line-height: 30px;
  border-radius: 3px;
  cursor: pointer;
  text-decoration: none!important;
  outline: none;
}
.w-button-main {
  background: #db3652;
  border-color: #b6243d;
}
.w-button-main:disabled,.w-button-main.btn-disabled {
  background-color: #dcdcdc;
  color: #999;
  border: 0;
}
.m-detail-buy .w-button {
  width: 112px;
}
#deal{
	padding-bottom:49px;
}
dl.list .dd-padding.m-detail-userCodes{
	padding:.28rem .4rem;
}
dl.list .dd-padding.m-detail-userCodes p{
	  font-size: .26rem;
	  text-align:left;
}
dl.list .dd-padding.m-detail-userCodes p b{
	display: inline-block;
	margin-right: 4px;
	font-weight: normal;
}
dl.list .dd-padding.m-detail-userCodes p a{
	color:#FF658E;
}
.w-msgbox-codes {
  color: #999;
}
.w-msgbox-codes .name {
  margin-bottom: 8px;
  line-height: 30px;
  border-bottom: 1px dotted #d4d4d4;
  position: relative;
}
.f-txtabb {
  overflow: hidden;
  white-space: nowrap;
  text-overflow: ellipsis;
}
.w-msgbox-codes .name h4 {
  padding-right: 70px;
  color: #3399FE;
  line-height: 30px;
    font-size: .265rem;
}
.w-msgbox-codes .name span {
  position: absolute;
  top: 0;
  right: 0;
}
.w-msgbox-codes p{
	line-height: 24px;
}
.w-msgbox-codes li {
  float: left;
  width: 63px;
  line-height: 22px;
    font-size: .245rem;
}
.m-detail-goods-result {
  margin: 7px 0 10px;
}
.m-detail-goods-result .w-record {
  padding: 10px;
  border: 1px solid #d5d5d5;
  border-bottom: 0;
  position: relative;
  font-size: 12px;
    color: #999;
}
.m-detail-goods-result .w-record-avatar {
	  float: left;
  margin-top: 3px;
  width: 45px;
  height: 45px;
}
.m-detail-goods-result .w-record-avatar img {
  width: 100%;
  height: 100%;
  display: inline;
  vertical-align: middle;
    border-radius: 4px;
}
.m-detail-goods-result .w-record-detail {
  margin-left: 60px;
}
.m-detail-goods-result .w-record-detail p{
	line-height: 1.71;
}
.txt-green {
  color: #528d00;
}
.m-detail-goods-result-luckyCode {
  padding: 10px 0 10px 20px;
  background: #db3652;
  color: #ffffff;
  line-height: 20px;
}
.m-detail-goods-result-luckyCode b {
  font-size: 0.35rem;
}
.w-button-simple {
  padding: 0 8px;
  height: 20px;
  line-height: 20px;
  font-size: 12px;
  font-weight: normal;
  color: #fff;
  background: #db3652;
  border: 0;
  border-radius: 20px;
}
.w-button-simple-white {
  background: #fff;
  color: #db3652;
}
.m-detail-goods-result-luckyCode .resultBtn {
  margin: -5px 0 0 10px;
}
img{
	width:100%;
}

</style>
  
<php>$share_img='';</php>
<if condition="!empty($imglist)">
	<section class="scroll">
		<!-- Swiper -->
		<!--div class="swiper-container swiper-container-banner banner">
			<div class="swiper-wrapper">
				<volist name="imglist" id="imgv">
					<div class="swiper-slide">
						<img src="{pigcms{$imgv}" >
					</div>
				</volist>
			</div>
			<div class="swiper-pagination swiper-pagination-banner"></div>
		</div-->
		<div class="album view_album" data-pics="<volist name="imglist" id="imgv">{pigcms{$imgv}<if condition="count($imglist) gt $i">,</if></volist>">
			<img src="{pigcms{$imglist[0]}" />
			<div class="desc">点击图片查看相册</div>
		</div>
	</section>
</if>
	
<div class="itemDesc">
    <div class="itemAttr">
        <div class="attr1">
            <div class="wrap pr">
                <h1>
                    {pigcms{$detail['title']}
                </h1>
                <!--h2>299元</h2-->
                <p><span>更新：{pigcms{$detail['updatetime']}</span> <span>浏览：{pigcms{$detail['views']}人</span></p>
                <span class="star pa <if condition='!empty($classify_usercollect_info)'>on</if>"  onclick="FavoriteThis();">
                    <i class="fa fa-star-o"></i>
                    <p>收藏</p>
                </span>
            </div>
        </div>
        <div class="attr2">
            <div class="wrap">
                <div class="row no-gutter">
					<if condition="!empty($content)">
					 <volist name="content" id="vv">
						 
						 <div class="col-50"><span class="fl" style="font-weight:bold">{pigcms{$vv['tn']}&nbsp;&nbsp;</span><p><if condition="is_array($vv['vv'])">{pigcms{$vv['vv']|implode=','}			 <elseif condition="$vv['type'] eq 1 AND empty($vv['vv']) AND $vv['inarr'] eq 1"/>面议<elseif condition="$vv['type'] eq 1 AND isset($vv['unit']) AND !empty($vv['unit'])"/><strong class="price2">{pigcms{$vv['vv']}</strong> / {pigcms{$vv['unit']}<else/><if condition='$vv["vv"] eq "on"'>有<else />{pigcms{$vv['vv']}</if></if>&nbsp;&nbsp;</p></div>
					 </volist>
					 </if>
                </div>
            </div>
        </div>
    </div>

    <div class="list-block cards-list">
        <ul>
			
			<if condition='!empty($detail["is_assure"])'>
				<li class="card">
					<div class="card-header">担保金额</div>
					<div class="card-content">
						<div class="card-content-inner">
							<div class="addr">
								<p>
								{pigcms{$detail['assure_money']}元
								</p>
							</div>
						</div>
					</div>
				</li>
			</if>
			
            <li class="card">
                <div class="card-header">联系信息</div>
                <div class="card-content">
                    <div class="card-content-inner">
                        <div class="contact">
                            <a data-click-from="telephone_ico" href="tel:{pigcms{$detail['lxtel']}" class="fr">
                                拨打电话
                            </a>
                            <div class="ofh">
                                <p>联系人：{pigcms{$detail['lxname']}</p>
                                <p>电话：<em>{pigcms{$detail['lxtel']}</em> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
			
			<if condition="!empty($detail['description'])">
            <li class="card">
                <div class="card-header">说明描述</div>
                <div class="card-content">
                    <div class="card-content-inner">
                        <p class="desc">
                            {pigcms{$detail['description']|htmlspecialchars_decode=ENT_QUOTES}
                        </p>
                    </div>
                </div>
            </li>
			</if>
        </ul>
    </div>
</div>
<php>echo '<pre>'.$this->shareScript.'</pre>';</php>	
<if condition='$detail["is_assure"] == 1'>
	<section class="payAttention">
		<div class="row">
			<div class="col-50"><a href="javascript:void(0)" class="i1"><i></i>在线支付</a> </div>
			<div class="col-50"><a href="javascript:void(0)" class="i2"><i></i>担保交易</a> </div>
		</div>
	</section>
</if>
<section class="ftHeight"></section>
<section class="detailsFt">
	<if condition="$im_url">
		<a href="{pigcms{$im_url}" class="btn1"><i></i>交谈</a>
	</if>	
	<if condition='empty($classify_order_info) && ($detail["is_assure"] == 1)'>
		<a href="{pigcms{:U('order',array('classify_userinput_id'=>$detail['id']))}" class="btn2"><i></i>担保支付</a>
	<elseif condition='!empty($classify_order_info)' />
		<a href="javascript:void(0)" class="btn2" style="background:#ccc"><i></i>已被人购买</a>
	</if>
</section>

<include file="Public:classify_footer" />
<script type="text/javascript" src="/tpl/Wap/default/static/js/common_wap.js"></script>
<script src="{pigcms{$static_path}classify/js/idangerous.swiper.min.js"></script>
<script src="{pigcms{$static_path}classify/js/fastclick.js"></script>

<script>
    var swiper = new Swiper('.swiper-container-banner', {
        loop:true,
        autoplay: 5000,//可选选项，自动滑动
        // 如果需要分页器
        pagination: '.swiper-pagination-banner'
    });
	
	$('.view_album').click(function(){
		$('#buy_box').removeAttr('style');
		show_buy_box = false;
		var album_more = $(this).attr('data-pics');
		var album_array = album_more.split(',');
		
		if(is_weixin()){

			
			wx.previewImage({
				current:album_array[0],
				urls:album_array
			});
		}else{
			var album_html = '<div class="albumContainer h_gesture_ tap_gesture_" style="display:block;">';
				album_html += '<div class="swiper-container">';
				album_html += '		<div class="swiper-wrapper">';
			$.each(album_array,function(i,item){
				album_html += '			<div class="swiper-slide">';
				album_html += '				<img src="'+item+'"/>';
				album_html += '			</div>';
			});
				album_html += '		</div>';
				album_html += '  	<div class="swiper-pagination"></div><div class="swiper-close" onclick="close_swiper()">X</div>';
				album_html += '</div>';
			
			album_html += '</div>';
			$('body').append(album_html);
		
			mySwiper = $('.swiper-container').swiper({
				pagination:'.swiper-pagination',
				loop:true,
				grabCursor: true,
				paginationClickable: true
			});
		}
	});
	
	function close_swiper(){
		$('.albumContainer').remove();
		show_buy_box = true;
	}
	
	<if condition='empty($classify_usercollect_info)'>
    $(function(){
       $(".star").tap(function(){
          if($(this).hasClass("on")){
              $(this).removeClass("on");
          }else{
              $(this).addClass("on");
          }
       });
    });
	</if>
	
	
	var uid="{pigcms{$uid}";
	var vid= "{pigcms{$vid}";
  /*****收藏处理*******/
 function FavoriteThis(){
	 <if condition='!empty($classify_usercollect_info)'>
	 
	 alert('您已成功收藏！');
	 
	 <else />
	 
	 $.post("{pigcms{:U('Classify/collectOpt')}",{vid:vid},function(data){
		 if(!data['error']){
			 alert('收藏成功！');
			 location.reload();
		 }
	 },'JSON');
	 
	 </if>
	 
 }
</script>

<script type="text/javascript">
window.shareData = {  
	"moduleName":"Classify",
	"moduleID":"0",
	"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
	"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Classify/ShowDetail',array('vid'=>$_GET['vid']))}",
	"tTitle": "{pigcms{$detail['title']}",
	"tContent": "{pigcms{$detail['title']}"
};
</script>
 {pigcms{$shareScript}

</body>
</html>