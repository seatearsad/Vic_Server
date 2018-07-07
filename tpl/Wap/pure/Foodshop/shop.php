<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name='apple-touch-fullscreen' content='yes'/>
<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
<meta name="format-detection" content="telephone=no"/>
<meta name="format-detection" content="address=no"/>
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$shop['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css?a=1123688"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>

<body>
  <header class="headshop" style="background: url({pigcms{$shop['image']}) center top no-repeat; background-size: 100% 100%;">
       <div class="shop_bot">
		  
		 
          <span> {pigcms{$shop['name']}</span>
		  
          <if condition="$shop['is_park']">
          <span class="cheb"><img src="{pigcms{$static_path}images/xqtc_06.png"></span>
          </if>
       </div>
  </header>

  <section class="Storenav">
     <ul class="clr">
		<if condition="$shop['is_book']">
		<li>
			<a href="{pigcms{:U('Foodshop/book_order', array('store_id' => $shop['store_id']))}">
				<span class="head_upper"><img src="{pigcms{$static_path}images/book.png"></span>
				<span class="head_lower">订桌点餐</span>
			</a>
		</li>
		</if>
		<if condition="$shop['is_queue']">
        <li>
          <a href="{pigcms{:U('Foodshop/queue', array('store_id' => $shop['store_id']))}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/xqym_05.png"></span>
             <span class="head_lower">排号</span>
          </a>
       </li>
		</if>
        <li>
          <a href="{pigcms{:U('My/pay', array('store_id' => $shop['store_id']))}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/xqym_07.png"></span>
             <span class="head_lower">{pigcms{$config.cash_alias_name}</span>
          </a>
       </li>
		<if condition="$shop['is_takeout']">
        <li>
          <a href="{pigcms{:U('Shop/index')}#shop-{pigcms{$shop['store_id']}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/xqym_09.png"></span>
             <span class="head_lower">{pigcms{$config.shop_alias_name}</span>
          </a>
       </li>
		</if>
	   <if condition="$card_info AND $card_info['self_get'] eq 1 ">
		<li>
          <a href="{pigcms{:U('My_card/merchant_card',array('mer_id'=>$shop['mer_id']))}">
             <span class="head_upper"><img src="{pigcms{$static_path}images/merchant_card.png"></span>
             <span class="head_lower">会员卡</span>
          </a>
       </li>
       </if>
     </ul>
  </section>

	<section class="BusinessHours clr">
		<if condition="$merchant['isverify']">
			<img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:8px; margin-right:10px;float:left">
			<span style="float:left">认证商家</span>
			</br>
		</if>
		<if condition="$shop['is_close']">
			<span class="on fl">营业时间：{pigcms{$shop['business_time']}</span>
			<span class="fr rig">未营业</span>
		<else />
			<span class="on on1 fl">营业时间：{pigcms{$shop['business_time']}</span>
		</if>
	</section>
<if condition="$shop['wifi_account']">
  <section  class="wifi clr">
     <div class="wifi_left fl">
       <span class="wifi_top"></span>
       <span class="wifi_font" style="display: none;">点击按钮连接无线网</span>
       <span class="wifi_font on" >{pigcms{$shop['wifi_account']}</span>
     </div>
     <div class="wifi_right fr">
       <a href="javascript:void(0)" style="display: none;">微信wifi</a>
       <span href="javascript:void(0)" class="on" >密码：{pigcms{$shop['wifi_password']}</span>
     </div>
  </section>
</if>
 <section class="purchase_list">
    <div class="navBox_list m10_list">
       <dl>
		<volist name="shop['group_list']" id="group">
         <dd class="Menulink clr">
            <a href="{pigcms{$group['url']}">
              <div class="Menulink_img fl">
                <img class="on" src="{pigcms{$group['list_pic']}">
               <if condition="$group['pin_num'] eq 0"> <span class="MenuGroup"></span><else /><span class="PinGroup"></span></if>
              </div>
              <div class="Menulink_right">
                <h2>{pigcms{$group['name']}</h2>
                <div class="MenuPrice">
					<span class="PriceF"><i>$</i><em>{pigcms{$group['price']}<if condition="$group.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$group.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if></em></span>
                  <span class="PriceT">门市价:${pigcms{$group['old_price']}</span>
                  <span class="PriceS">{pigcms{$group['sale_txt']}</span>
                </div>
              </div>
            </a>
         </dd>
         </volist>
       </dl>
       <div class="more">
         <span>查看其他<i></i>个团购</span>
       </div>
      </div>
 </section>

<if condition="$coupon_list">
<section class="Coupon">
   <div class="Coupon_top">
     优惠券
   </div>
   <div class="swiper-container">
        <div class="swiper-wrapper">
			<volist name="coupon_list" id="vo">
				<div class="swiper-slide" date-type ="{pigcms{$vo.coupon_id}"> 
					
					<div class="Coupon_ntop fl">
						<div class="Coupon_ntop_span">
						  <i>$</i><em>{pigcms{$vo.discount|floatval}</em>
						</div>
						<div class="Coupon_ntop_span1">
						  满{pigcms{$vo.order_money|floatval}元使用
						</div>
					</div>
					<div class="Coupon_nend fl">
						<div class="Coupon_ntop_span">
						{pigcms{$vo.name}
						</div>
						<div class="Coupon_ntop_span1">
						  <p>使用时间</p>
						  <p style="font-size:10px;">{pigcms{$vo.start_time|date='Y-m-d',###}至{pigcms{$vo.end_time|date='Y-m-d',###}</p>
						</div>
					</div>
					<div class="Coupon_Receive fr">立即领取</div>

				</div>
			</volist>
		</div>
	</div>
</section>
</if>
<if condition="$shop['is_book']">
<section class="dishes">
	<if condition="$goods_list">
	<div class="dishes_top">推荐菜</div>
	<a href="{pigcms{:U('Foodshop/show_menu', array('store_id' => $shop['store_id']))}">
	<div class="dishes_bot">
		<volist name="goods_list" id="goods">
		<span>{pigcms{$goods['name']}</span>　
		</volist>
	</div>
	</a>
	</if>
	<div class="dishes_All">
		<a href="{pigcms{:U('Foodshop/show_menu', array('store_id' => $shop['store_id']))}">本店所有菜品</a>
	</div>
</section>
</if>
<section class="Moreinfor">
	<div class="Moreinfor_top">更多信息</div>
	<div class="Moreinfor_bot">
	<ul>
		<php>$phones = explode(' ', $shop['phone']);</php>
		<li class="pho">
		<volist name="phones" id="phone">
		<a href="tel:{pigcms{$phone}">{pigcms{$phone}</a>
		</volist>
		</li>
		<!-- <li class="place">{pigcms{$shop['business_time']}</li> -->
		<li class="time"><a href="{pigcms{:U('Foodshop/addressinfo', array('store_id' => $shop['store_id']))}">{pigcms{$shop['adress']} <span class="fr more"></span></a></li>
	</ul>
	</div>
</section>

<!-- 新增html -->
<if condition="$reply_list">
<section class="details_evaluate">
	<div class="Moreinfor_top">评价</div>
	<ul>
	<volist name="reply_list" id="reply">
	<li>
		<div class="details_evaluate_top clr">
			<div class="evaluate_left">
				<h3>{pigcms{$reply['nickname']}</h3>
				<span>{pigcms{$reply['add_time']}</span>
			</div>
			<div class="evaluate_right">
				<div class="atar_Show">
					<p></p>
				</div>
				<span><i>{pigcms{$reply['score']|floatval}</i>分</span>
			</div>  
		</div>
		<div class="details_evaluate_end">{pigcms{$reply['comment']}</div>
	</li>
	</volist>
	</ul>
	<if condition="$reply_count gt 3">
	<div class="stillmore">
		<a href="{pigcms{:U('Foodshop/reply', array('store_id' => $shop['store_id']))}" class="clr">
			<span>查看全部评价（{pigcms{$reply_count}）</span><em></em>
		</a>
	</div>
	</if>
</section>
</if>
<!-- 新增html end -->

</body>
<script type="text/javascript">
// 查看其它团购
$(".m10_list").each(function(){
	var height = $(this).height();
	if (height > 221) {
		$(this).css({"height":"221px","overflow":"hidden"})
	} else {
		$(this).find(".more").hide();
	}
});
       
$(".m10_list .more").click(function(){
	$(this).hide();
	$(this).parents(".m10_list").css("height","auto");
});
      
var len = $(".m10_list dd").length
$(".m10_list .more i").text(len-2); 


// 清除边框
$(".navBox_list dl").each(function(){
	$(this).find("dd.Menulink").last().css("border-bottom","none");
});
//奖票滑动
var myswiper5 = new Swiper('.swiper-container', {direction : 'horizontal',  freeMode : true, freeModeMomentumRatio : 0.5, slidesPerView : 'auto'});

$(function(){
	$('.swiper-slide').click(function(event) {
		var tmp= $(this);
		$.ajax({
			url: "{pigcms{:U('My_card/had_pull')}",
			type: 'POST',
			dataType: 'json',
			data: {coupon_id:tmp.attr('date-type')},
			success:function(data){
				if(data.error_code){
					if(data.error_code == 1 || data.error_code==2){
						alert(data.msg);
						 window.location.reload();
					}else if(data.error_code==3){
						alert(data.msg);
						tmp.addClass('none');
					}else {
						alert(data.msg);
					}
				}else{
					tmp.find('.box .get .left-icon ').css('display','block');
					alert(data.msg);
				
				}
			}
			
		});
	});
});
</script>


<!-- 新增js -->
<script type="text/javascript">
  // 显示分数
      $(".evaluate_right").each(function() {
        var num=$(this).find("i").text();
        var www=num*18;//
        $(this).find("p").css("width",www);
    });
</script>
<!-- 新增js -->

</html>