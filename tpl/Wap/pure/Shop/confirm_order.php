<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/datePicker.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/mobiscroll_min.css" media="all">
<script type="text/javascript" src="{pigcms{$static_path}shop/js/jquery1.8.3.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/dialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/mobiscroll_min.js"></script>

<title>{pigcms{$store['name']|default="快店"}</title>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta content="telephone=no, address=no" name="format-detection">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/main.css" media="all">
<style>
#dingcai_adress_info{
border-top: 1px solid #ddd8ce;
border-bottom: 1px solid #ddd8ce;
position: relative;
}
#dingcai_adress_info:after{
position: absolute;
right: 8px;
top: 50%;
display: block;
content: '';
width: 13px;
height: 13px;
border-left: 3px solid #999;
border-bottom: 3px solid #999;
-webkit-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
-moz-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
-ms-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
}


#enter_im_div {
  bottom: 121px;
  z-index: 11;
  display: none;
  position: fixed;
  width: 100%;
  max-width: 640px;
  height: 1px;
}
#enter_im {
  width: 94px;
  margin-left: 110px;
  position: relative;
  left: -100px;
  display: block;
}
a {
  color: #323232;
  outline-style: none;
  text-decoration: none;
}
#to_user_list {
  height: 30px;
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
.post_package {
    background-color: #4A96D4;
    border-color: #A5DE37;
}
.btn_express {
    color: #fff;
    font-weight: 300;
    font-size: 16px;
    text-decoration: none;
    text-align: center;
    line-height: 34px;
    padding: 0px 15px;
    margin: 0;
    display: inline-block;
    cursor: pointer;
    border: none;
    box-sizing: border-box;
    transition-property: all;
    transition-duration: 0.3s;
    border-radius: 4px;
}

.pick_in_store_click {
    background-color: #fff;
    color: #000;
    border: 1px solid #4A96D4;
}
.post_package_click {
    background-color: #fff;
    color: #000;
    border: 1px solid #4A96D4;
}
.pick_in_store {
    background-color: #4A96D4;
    border-color: #A5DE37;
}
/*加载层*/
.motifyShade{
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	bottom:0;
	padding: 0;
	z-index: 998;
	width: 100%;
}
.motify {
	display: none;
	position: fixed;
	top: 35%;
	left: 50%;
	width: 260px;
	padding: 0;
	margin: 0 0 0 -130px;
	z-index: 999;
	background: rgba(0, 0, 0, 0.8);
	color: #fff;
	font-size: 14px;
	line-height: 1.5em;
	border-radius: 6px;
	-webkit-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
	box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
}
.motify .motify-inner {
	padding: 10px 10px;
	text-align: center;
	word-wrap: break-word;
}
</style>
</head>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/scroller.js"></script>
<body onselectstart="return true;" ondragstart="return false;">
<div class="container">
	<form name="cart_confirm_form" action="{pigcms{:U('Shop/save_order',array('store_id'=> $store['store_id'], 'mer_id' => $store['mer_id'], 'frm' => $_GET['frm'], 'village_id'=>$village_id))}" method="post">
	<section class="menu_wrap pay_wrap">
		<ul class="box">
			<li>
				<!--a class="">{pigcms{:L('_DIST_MODE_')}：</a>&nbsp;&nbsp;-->
				<!--if condition="in_array($delivery_type, array(0, 3))">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">{pigcms{:L('_PLAT_DIST_')}</a>&nbsp;&nbsp;
				</if-->
				<if condition="in_array($delivery_type, array(1, 4))">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">{pigcms{:L('_SHOP_DIST_')}</a>&nbsp;&nbsp;
				</if>
				<if condition="$delivery_type eq 5">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package_express">{pigcms{:L('_EXPRESS_DELI_')}</a>&nbsp;&nbsp;
				</if>
				<if condition="in_array($delivery_type, array(2, 3, 4))">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store<elseif condition="$delivery_type neq 2" />pick_in_store_click<else />pick_in_store</if>" id="pick_in_store">{pigcms{:L('_SELF_DIST_')}</a>
				</if>
			</li>
			<if condition="$delivery_type neq 2">
			<li id="li_delivery" <if condition="$pick_addr_id">style="display:none"</if>>
				<a href="{pigcms{:U('My/adress',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'mer_id' => $store['mer_id'], 'frm' => $_GET['frm'], 'current_id'=>$user_adress['adress_id'], 'order_id' => $order_id))}">
					<strong>
						<span id="showAddres"><if condition="$user_adress['adress_id']">{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}<else/>{pigcms{:L('_CLICK_ADD_NEW_A_')}</if></span><br>
						<span id="showName">{pigcms{$user_adress['name']}</span>
						<span id="showTel">{pigcms{$user_adress['phone']}</span>
					</strong>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			</if>
			<if condition="in_array($delivery_type, array(2, 3, 4))">
			<li id="li_pick" <if condition="$delivery_type neq 2 AND empty($pick_addr_id)">style="display:none"</if>>
				<a href="{pigcms{:U('My/pick_address',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'frm' => $_GET['frm'], 'mer_id' => $store['mer_id'],'pick_addr_id' => $pick_address['pick_addr_id'], 'order_id' => $order_id))}">
					<strong>
						<span id="showTel">省市区：{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']}</span><br>
						<span id="showName">电话：{pigcms{$pick_address['phone']}</span><br>
						<span id="showAddres">地址：{pigcms{$pick_address['name']}</span>
						<br><span id="showName" style="color:green">距离：{pigcms{$pick_address['distance']}</span>
					</strong>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			</if>
		</ul>
		<ul class="box pay_box">
			<li id="show_arrive_date" <if condition="$delivery_type eq 2 OR $delivery_type eq 5 OR $pick_addr_id">style="display:none"</if>>
				<a href="javascript:void(0);" id="dateBtn" class="date">
					<strong>{pigcms{:L('_DELI_DATE_')}</strong>
					<span id="arriveDate">{pigcms{$arrive_date}</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			<if condition="$have_two_time">
            <li id="two_time_select" class="line name" <if condition="$delivery_type eq 2 OR $delivery_type eq 5 OR $pick_addr_id">style="display:none"</if>>
				<strong>{pigcms{:L('_DELI_TIME_SLOT_')}</strong>
				<label><input type="radio" value="0" name="time_select" <if condition="$now_time_value eq 1">checked</if>>{pigcms{$time_select_1}</label>	
				<label><input type="radio" value="1" name="time_select" <if condition="$now_time_value eq 2">checked</if>>{pigcms{$time_select_2}</label>
            </li>
            </if>
			<li id="show_arrive_time" <if condition="$delivery_type eq 2 OR $delivery_type eq 5 OR $pick_addr_id">style="display:none"</if>>
				<a href="javascript:void(0);" id="timeBtn" class="time">
					<strong>{pigcms{:L('_DELI_TIME_')}</strong>
					<span id="arriveTime">{pigcms{$arrive_time}</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" id="remarkBtn">
					<strong>{pigcms{:L('_NOTE_INFO_')}</strong>
					<span id="remarkTxt">{pigcms{:L('_CLICK_ADD_NOTE_INFO_')}</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul>
		<ul class="box " <if condition="$store['is_invoice'] AND $store['invoice_price'] elt $price">style="display:block"<else/>style="display:none"</if>>
			<li>
				<a href="javascript:void(0);" id="invoiceBtn">
					<strong id="invoiceTxt">点击添加发票抬头</strong>
					<span ></span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul>
		<if condition="$cue_field">
			<ul class="box pay_box">
				<volist name="cue_field" id="vo">
					<li>
						<a href="javascript:void(0);" id="cue_field_btn_{pigcms{$key}">
							<strong>{pigcms{$vo.name}</strong>
							<span id="cue_field_{pigcms{$key}_txt">点击填写{pigcms{$vo.name}</span>
							<div><i class="ico_arrow"></i></div>
						</a>
					</li>
				</volist>
			</ul>
		</if>
		<if condition="!empty($goods)">
		<ul class="menu_list order_list" id="orderList">
		<volist name="goods" id="ditem">
		<li>
			<div>
			<if condition="!empty($ditem['image'])">
				<img src="{pigcms{$ditem['image']}" alt="">
			</if>
			</div>
			<div>
				<h3>{pigcms{$ditem['name']}</h3>
				<div>
					<div>
						<span style="color:#999">{pigcms{$ditem['str']}</span>
					</div>
					<span class="count">{pigcms{$ditem['num']}</span>
					<strong>$<span class="unit_price">{pigcms{$ditem['price']}<if condition="$ditem.extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$ditem['extra_price']|floatval}{pigcms{$config.extra_price_alias_name}</if></span> <span style="color: gray; font-size:10px">({pigcms{$ditem['num']}{pigcms{$ditem['unit']})</span></strong>
				</div>
			</div>
		</li>
		</volist>
		</ul>
		<ul class="menu_list box" style="margin-bottom:20px;">
			<li>
				<div>
					<h3>{pigcms{:L('_TOTAL_PRICE_A_DIS_')}：<strong style="display: inline;font-size:14px;">${pigcms{$vip_discount_money|floatval}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></strong></h3>
				</div>
			</li>
			<!--if condition="$packing_charge"-->
			<li>
				<div>
					<h3>{pigcms{:L('_PACK_PRICE_')}：<strong style="display: inline;font-size:14px;">${pigcms{$store_shop['pack_fee']}</strong></h3>
				</div>
			</li>
			<!--/if-->
			<if condition="$discount_list">
			<volist name="discount_list" id="row">
			<li>
				<if condition="$row['store_id']">
					<if condition="$row['type'] eq 0">
						<div>
							<h3>商家首单优惠：满${pigcms{$row['full_money']|floatval}元,减<strong style="display: inline;font-size:14px;">${pigcms{$row['reduce_money']|floatval}</strong>元</h3>
						</div>
					<elseif condition="$row['type'] eq 1" />
						<div>
							<h3>商家满减优惠：满${pigcms{$row['full_money']|floatval}元,减<strong style="display: inline;font-size:14px;">${pigcms{$row['reduce_money']|floatval}</strong>元</h3>
						</div>
					<else />
					</if>
				<else />
					<if condition="$row['type'] eq 0">
						<div>
							<h3>平台首单优惠：满${pigcms{$row['full_money']|floatval}元,减<strong style="display: inline;font-size:14px;">${pigcms{$row['reduce_money']|floatval}</strong>元</h3>
						</div>
					<elseif condition="$row['type'] eq 1" />
						<div>
							<h3>平台满减优惠：满${pigcms{$row['full_money']|floatval}元,减<strong style="display: inline;font-size:14px;">${pigcms{$row['reduce_money']|floatval}</strong>元</h3>
						</div>
					<else />
					</if>
				</if>
			</li>
			</volist>
			</if>
		</ul>
		</if>
	</section>
	<div style="display:none;">
	  <input class="hidden" id="order_id" name="order_id" value="{pigcms{$order_id}">
	  <input class="hidden" id="ouserName" name="ouserName" value="{pigcms{$user_adress['name']}">
	  <input class="hidden" id="ouserTel" name="ouserTel" value="{pigcms{$user_adress['phone']}">
	  <input class="hidden" id="ouserAddres" name="ouserAddres" value="{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']}  {pigcms{$user_adress['detail']}">
	  <input class="hidden" id="address_id" name="address_id" value="{pigcms{$user_adress['adress_id']}">
	  <input type="hidden" name="pick_address" value="{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']} {pigcms{$pick_address['name']} 电话：{pigcms{$pick_address['phone']}"/>
	  <input type="hidden" name="pick_id" value="{pigcms{$pick_address['pick_addr_id']}"/>
	  <input class="hidden" id="oarrivalDate" name="oarrivalDate" value="{pigcms{$arrive_date}">
	  <input class="hidden" id="oarrivalTime" name="oarrivalTime" value="{pigcms{$arrive_time}">
	  <input class="hidden" id="omark" name="omark" value="">
	  <input class="hidden" id="invoice_head" name="invoice_head" value="">
	  <input class="hidden" id="deliver_type" name="deliver_type" value="<if condition="$delivery_type eq 2 OR $pick_addr_id">1<else />0</if>"/>
		<if condition="$cue_field">
			<volist name="cue_field" id="vo">
				<input class="hidden" id="cue_field_{pigcms{$key}_head" name="cue_field[{pigcms{$key}][txt]" value=""/>
				<input class="hidden" name="cue_field[{pigcms{$key}][title]" value="{pigcms{$vo.name}"/>
			</volist>
		</if>
	</div>
	</form>
	<div class="addres_box" id="remarkBox">
		<ul>
			<li><textarea class="txt max" placeholder="{pigcms{:L('_PLEASE_INPUT_NOTE_')}" id="userMark"></textarea></li>
			<li class="btns_wrap">
			<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancleRemark">{pigcms{:L('_B_PURE_MY_86_')}</a></span>
			<span><a href="javascript:void(0);" class="comm_btn higher" id="saveRemark">{pigcms{:L('_B_PURE_MY_85_')}</a></span>
			</li>
		</ul>
	</div>
	<div class="addres_box" id="invoice_head_box">
		<ul>
			<li><textarea class="txt max" placeholder="请填发票抬头" id="invoice_head_txt"></textarea></li>
			<li class="btns_wrap">
			<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancleInvoice">{pigcms{:L('_B_PURE_MY_86_')}</a></span>
			<span><a href="javascript:void(0);" class="comm_btn higher" id="saveInvoice">{pigcms{:L('_B_PURE_MY_85_')}</a></span>
			</li>
		</ul>
	</div>
	<if condition="$cue_field">
		<volist name="cue_field" id="vo">
			<div class="addres_box" id="cue_field_{pigcms{$key}_head_box">
				<ul>
					<li><textarea class="txt max" placeholder="请填{pigcms{$vo.name}" id="cue_field_{pigcms{$key}_head_txt"></textarea></li>
					<li class="btns_wrap">
						<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancle_cue_field_{pigcms{$key}">{pigcms{:L('_B_PURE_MY_86_')}</a></span>
						<span><a href="javascript:void(0);" class="comm_btn higher" id="save_cue_field_{pigcms{$key}">{pigcms{:L('_B_PURE_MY_85_')}</a></span>
					</li>
				</ul>
			</div>
		</volist>
	</if>
</div>
<div class="fixed" style="min-height:90px;padding:14px;">
	<p>
		<span class="fr">{pigcms{:L('_TOTAL_RECE_')}：<strong>$<span id="totalPrice_">{pigcms{$price|floatval}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></span></strong> / <span id="cartNum_">{pigcms{$total}</span></span>
		<p id="show_delivery_fee" <if condition="$delivery_type eq 2 OR $pick_addr_id OR $now_time_value eq 2">style="display:none"</if>>{pigcms{:L('_DELI_PRICE_')}：${pigcms{$delivery_fee}，{pigcms{:L('_TAXATION_TXT_')}: {pigcms{$store['tax_num']}%</p>
		<if condition="$have_two_time">	
		<p id="show_delivery_fee2" <if condition="$now_time_value eq 1">style="display:none"</if>>{pigcms{:L('_DELI_PRICE_')}：${pigcms{$delivery_fee2}</p>
		</if>	
	</p>
	<span class="fr" style="position: absolute; bottom: 8px; right: 20px;">
	<a href="javascript:;" class="comm_btn" id="submit_order" >{pigcms{:L('_B_PURE_MY_85_')}</a>
	</span>
</div>
<if condition="$cue_field">
	<volist name="cue_field" id="vo">
		<script type="text/javascript">
			// 添加自定义
			$('#cue_field_btn_{pigcms{$key}').bind('click', function(){
				var cue_field = $('#cue_field_{pigcms{$key}_txt').text();
				if(cue_field == '点击填写{pigcms{$vo.name}') cue_field = '';
				$('#cue_field_{pigcms{$key}_txt,#cue_field_{pigcms{$key}_head_txt').val(cue_field);
				$('#cue_field_{pigcms{$key}_head_box').dialog({title: '点击填写{pigcms{$vo.name}'});
			});

			$('#cancle_cue_field_{pigcms{$key}').bind('click', function(){
				$('#cue_field_{pigcms{$key}_head_box').dialog('close');
			});

			$('#save_cue_field_{pigcms{$key}').bind('click', function(){
				var cue_field = $('#cue_field_{pigcms{$key}_head_txt').val();
				if(cue_field == '') cue_field = '点击填写{pigcms{$vo.name}';
				$('#cue_field_{pigcms{$key}_txt').text(cue_field);
				$('#cue_field_{pigcms{$key}_head').val(cue_field);
				
				$('#cue_field_{pigcms{$key}_head_txt').val('');
				$('#cue_field_{pigcms{$key}_head_box').dialog('close');
			});
		</script>
	</volist>
</if>
<script type="text/javascript">
var motify = {
	timer:null,
	log:function(msg,time,shade){
		$('.motifyShade,.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		if(shade && shade.show){
			if($('.motifyShade').size() > 0){
				$('.motifyShade').css({'background-color':'rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+')'}).show();
			}else{
				$('body').append('<div class="motifyShade" style="display:block;background-color:rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+');"></div>');
			}
		}
		if(typeof(time) == 'undefined'){
			time = 3000;
		}
		if(time != 0){
			motify.timer = setTimeout(function(){
				$('.motify').hide();
			},time);
		}
	}
};
$(document).ready(function () {
	$(window).scrollTop(1);
	setTimeout(function(){
		$('div.fixed').css({'bottom':'0px','left':'0px'});
	},1000);
    var maxYear = '{pigcms{$maxYear}';
    var maxMouth = '{pigcms{$maxMouth}';
    var maxDay = '{pigcms{$maxDay}';
    var maxYear_today = '{pigcms{$maxYear_today}';
    var maxMouth_today = '{pigcms{$maxMouth_today}';
    var maxDay_today = '{pigcms{$maxDay_today}';
    var maxYear_today2 = '{pigcms{$maxYear_today2}';
    var maxMouth_today2 = '{pigcms{$maxMouth_today2}';
    var maxDay_today2 = '{pigcms{$maxDay_today2}';
    var maxHour = '{pigcms{$maxHour}';
    var maxMinute = '{pigcms{$maxMinute}';
    var maxHour2 = '{pigcms{$maxHour2}';
    var maxMinute2 = '{pigcms{$maxMinute2}';
    var minYear = '{pigcms{$minYear}';
    var minMouth = '{pigcms{$minMouth}';
    var minDay = '{pigcms{$minDay}';
    var minHour_today = '{pigcms{$minHour_today}';
    var minMinute_today = '{pigcms{$minMinute_today}';
    var minHour_tomorrow = '{pigcms{$minHour_tomorrow}';
    var minMinute_tomorrow = '{pigcms{$minMinute_tomorrow}';
    var minHour_today2 = '{pigcms{$minHour_today2}';
    var minMinute_today2 = '{pigcms{$minMinute_today2}';
    var minHour_tomorrow2 = '{pigcms{$minHour_tomorrow2}';
    var minMinute_tomorrow2 = '{pigcms{$minMinute_tomorrow2}';
    var today = '{pigcms{$today}';
    var is_cross_day_1 = {pigcms{$is_cross_day_1}, is_cross_day_2 = {pigcms{$is_cross_day_2};
    var opt = {};

    opt.date = {preset:'date', minDate: new Date(minYear, minMouth, minDay, minHour_tomorrow, minMinute_tomorrow), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};

	var time_select = $('input[name="time_select"]:checked').val();
	if (time_select == 'undefined' || time_select == 0) {
	    if (is_cross_day_1 == 1) {
	        opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour_today, minMinute_today), maxDate:new Date(maxYear_today, maxMouth_today, maxDay_today, maxHour, maxMinute)};
	    } else {
	        opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_today, minMinute_today), maxDate:new Date(maxYear, maxMouth, maxDay, maxHour, maxMinute)};
	    }
    }  else {
	    if (is_cross_day_2 == 1) {
	        opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour_today2, minMinute_today2), maxDate:new Date(maxYear_today2, maxMouth_today2, maxDay_today2, maxHour2, maxMinute2)};
	    } else {
	        opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_today2, minMinute_today2), maxDate:new Date(maxYear, maxMouth, maxDay, maxHour2, maxMinute2)};
	    }
    }
    opt.time_default = {
            theme: 'android-ics light', //皮肤样式
            display: 'bottom', //显示方式
            mode: 'scroller', //日期选择模式
            lang:'zh',
            minWidth: 64,
            setText: "{pigcms{:L('_B_PURE_MY_85_')}", //确认按钮名称
            cancelText: "{pigcms{:L('_B_PURE_MY_86_')}",//取消按钮
            dateFormat: 'yy-mm-dd',
    		onSelect: function (valueText, inst) {
    			var time_select = $('input[name="time_select"]:checked').val();
    			if (time_select == 'undefined' || time_select == 0) {
	        		if (is_cross_day_1 == 1) {
	        			$('#arriveDate').html(valueText.substr(0, 10));
	        			$('#oarrivalDate').val(valueText.substr(0, 10));
		    			$('#arriveTime').html(valueText.substr(11));
		    			$('#oarrivalTime').val(valueText.substr(11));
	        		} else {
		    			$('#arriveTime').html(valueText);
		    			$('#oarrivalTime').val(valueText);
	        		}
    			} else {
    				if (is_cross_day_2 == 1) {
	        			$('#arriveDate').html(valueText.substr(0, 10));
	        			$('#oarrivalDate').val(valueText.substr(0, 10));
		    			$('#arriveTime').html(valueText.substr(11));
		    			$('#oarrivalTime').val(valueText.substr(11));
	        		} else {
		    			$('#arriveTime').html(valueText);
		    			$('#oarrivalTime').val(valueText);
	        		}
    			}
            }
	};
	
    $(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
    
    opt.default = {
        theme: 'android-ics light', //皮肤样式
        display: 'bottom', //显示方式
        mode: 'scroller', //日期选择模式
        lang:'zh',
        minWidth: 64,
        setText: "{pigcms{:L('_B_PURE_MY_85_')}", //确认按钮名称
        cancelText: "{pigcms{:L('_B_PURE_MY_86_')}",//取消按钮
        dateFormat: 'yy-mm-dd',
		onSelect: function (valueText, inst) {
			var time_select = $('input[name="time_select"]:checked').val();
			if (time_select == 'undefined' || time_select == 0) {
				if (is_cross_day_1 == 1) {
					if (valueText == today) {
						opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour_today, minMinute_today), maxDate:new Date(maxYear_today, maxMouth_today, maxDay_today, maxHour, maxMinute)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					} else {
						var d = new Date(valueText);
						opt.time = {preset:'datetime', minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), minHour_tomorrow, minMinute_tomorrow), maxDate:new Date(d.getFullYear(), d.getMonth(), d.getDate()+1, maxHour, maxMinute)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					}
				} else {
					if (valueText == today) {
						opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_today, minMinute_today), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					} else {
						opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_tomorrow, minMinute_tomorrow), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					}
				}
			} else {
				if (is_cross_day_2 == 1) {
					if (valueText == today) {
						opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour_today2, minMinute_today2), maxDate:new Date(maxYear_today2, maxMouth_today2, maxDay_today2, maxHour2, maxMinute2)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					} else {
						var d = new Date(valueText);
						opt.time = {preset:'datetime', minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), minHour_tomorrow2, minMinute_tomorrow2), maxDate:new Date(d.getFullYear(), d.getMonth(), d.getDate()+1, maxHour2, maxMinute2)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					}
				} else {
					if (valueText == today) {
						opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_today2, minMinute_today2), maxDate:new Date(maxYear, maxMouth, maxDay, maxHour2, maxMinute2)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					} else {
						opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_tomorrow2, minMinute_tomorrow2), maxDate:new Date(maxYear, maxMouth, maxDay, maxHour2, maxMinute2)};
						$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
					}
				}
			}
			$('#arriveDate').html(valueText);
			$('#oarrivalDate').val(valueText);
        }
    };

    
    $(".date").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
    

    $('input[name="time_select"]').click(function(){
    	var valueText = $('#oarrivalDate').val();
    	if ($(this).val() == 0) {
        	$('#show_delivery_fee').show();
        	$('#show_delivery_fee2').hide();
			if (is_cross_day_1 == 1) {
				if (valueText == today) {
					opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour_today, minMinute_today), maxDate:new Date(maxYear_today, maxMouth_today, maxDay_today, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				} else {
					var d = new Date(valueText);
					opt.time = {preset:'datetime', minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), minHour_tomorrow, minMinute_tomorrow), maxDate:new Date(d.getFullYear(), d.getMonth(), d.getDate()+1, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				}
			} else {
				if (valueText == today) {
					opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_today, minMinute_today), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				} else {
					opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_tomorrow, minMinute_tomorrow), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				}
			}
		} else {
        	$('#show_delivery_fee').hide();
        	$('#show_delivery_fee2').show();
			if (is_cross_day_2 == 1) {
				if (valueText == today) {
					opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour_today2, minMinute_today2), maxDate:new Date(maxYear_today2, maxMouth_today2, maxDay_today2, maxHour2, maxMinute2)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				} else {
					var d = new Date(valueText);
					opt.time = {preset:'datetime', minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), minHour_tomorrow2, minMinute_tomorrow2), maxDate:new Date(d.getFullYear(), d.getMonth(), d.getDate()+1, maxHour2, maxMinute2)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				}
			} else {
				if (valueText == today) {
					opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_today2, minHour_today2), maxDate:new Date(maxYear, maxMouth, maxDay, maxHour2, maxMinute2)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				} else {
					opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour_tomorrow2, minMinute_tomorrow2), maxDate:new Date(maxYear, maxMouth, maxDay, maxHour2, maxMinute2)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				}
			}
		}
    });

    
	$('#post_package').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'none');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select').css('display', 'block');
		$('#deliver_type').val(0);
	});
	$('#post_package_express').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_delivery').css('display', 'block');
		$('#li_pick, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select').css('display', 'none');
		$('#deliver_type').val(0);
	});
	$('#pick_in_store').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'block');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select').css('display', 'none');
		$('#deliver_type').val(1);
	});

	// 添加备注
	$('#remarkBtn').bind('click', function(){
		var remark = $('#remarkTxt').text();
		if(remark == "{pigcms{:L('_CLICK_ADD_NOTE_INFO_')}") remark = '';
		$('#userMark').val(remark);
		$('#remarkBox').dialog({title: "{pigcms{:L('_ADD_NOTE_')}"});
	});

	$('#cancleRemark').bind('click', function(){
		$('#remarkBox').dialog('close');
	});

	$('#saveRemark').bind('click', function(){
		$('#remarkTxt').text($('#userMark').val());
		$('#userMark').val('');
		$('#remarkBox').dialog('close');
	});
	
	// 添加发票
	$('#invoiceBtn').bind('click', function(){
		var invoice = $('#invoiceTxt').text();
		if(invoice == '点击添加发票抬头') invoice = '';
		$('#invoiceTxt').val(invoice);
		$('#invoice_head_box').dialog({title: '添加发票抬头'});
	});

	$('#cancleInvoice').bind('click', function(){
		$('#invoice_head_box').dialog('close');
	});

	$('#saveInvoice').bind('click', function(){
		$('#invoiceTxt').text($('#invoice_head_txt').val());
		$('#invoice_head_txt').val('');
		$('#invoice_head_box').dialog('close');
	});

	$("#submit_order").click(function(){
		
		if($('#deliver_type').val() == 0 && $('#address_id').val() == ''){
			motify.log('Please Enter Address');
			return false;
		}
		if(!$(this).hasClass('disabled')){
			<?php
				if($cue_field){ 
					foreach($cue_field as $key=>$value){
						if($value['iswrite']){
			?>
							if($('#cue_field_<?php echo $key; ?>_head').val() == ''){
								motify.log("请填写<?php echo $value['name'];?>");
								return false;
							}
			<?php
						}
					}
				}
			?>
			$(this).addClass('disabled');
			
// 			var wo_delivery_time = $.trim($("#arriveTime").html());
// 			if(wo_delivery_time == '尽快送出'){
// 				wo_delivery_time = '';
// 			}
// 			$('#oarrivalTime').val(wo_delivery_time);
			
			var wo_memo = $.trim($("#remarkTxt").html());
			if(wo_memo == "{pigcms{:L('_CLICK_ADD_NOTE_INFO_')}") {
				wo_memo = '';
			}
			$('#omark').val(wo_memo);
			var invoice_head = $.trim($("#invoiceTxt").html());
			if(invoice_head == '点击添加发票抬头') {
				invoice_head = '';
			}
			$('#invoice_head').val(invoice_head);
			/*$.post($('#cart_confirm_form').attr('action'), $('#cart_confirm_form').serialize(), function(response){
				if (response.status) {
					window.location.href = response.url;
				} else {
					alert(response.info);
				}
			});
			return false;*/
			document.cart_confirm_form.submit();
		}
		return false;
	});
	
	if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
		var reg = /versioncode=(\d+),/;
		var arr = reg.exec(navigator.userAgent.toLowerCase());
		if(arr == null){
			
		}else{
			var version = parseInt(arr[1]);
			if(version >= 50){
				if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
					$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
					$('#li_delivery a').click(function(){
						var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
						$('body').append('<iframe src="pigcmso2o://getUserAddress/'+address_id+'" style="display:none;"></iframe>');
						return false;
					});
				}else{
					$('#li_delivery a').click(function(){
						var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
						window.lifepasslogin.getUserAddress(address_id);
						return false;
					});
				}
			}
		}
	}
});

function callbackUserAddress(address){
	var addressArr = address.split('<>');
	// $('#remarkTxt').html(addressArr[0]);
	$('#address_id').val(addressArr[0]);
	$('#showName').html(addressArr[1]);
	$('#showTel').html(addressArr[2]);
	$('#showAddres').html(addressArr[3]);
	<php>
		$tmpGet = $_GET;
		unset($tmpGet['adress_id']);
	</php>
	window.location.href = "{pigcms{:U('confirm_order',$tmpGet)}&adress_id="+addressArr[0];
}
</script>
</body>
{pigcms{$hideScript}
</html>