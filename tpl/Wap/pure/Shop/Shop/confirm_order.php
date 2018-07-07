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

</style>
</head>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/scroller.js"></script>
<body onselectstart="return true;" ondragstart="return false;">
<div class="container">
	<form name="cart_confirm_form" action="{pigcms{:U('Shop/save_order',array('store_id'=> $store['store_id'], 'mer_id' => $store['mer_id'], 'village_id'=>$village_id))}" method="post">
	<section class="menu_wrap pay_wrap">
		<ul class="box">
			<li>
				<a class="">配送方式：</a>&nbsp;&nbsp;
				<if condition="in_array($delivery_type, array(0, 3))">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">平台配送</a>&nbsp;&nbsp;
				</if>
				<if condition="in_array($delivery_type, array(1, 4))">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">商家配送</a>&nbsp;&nbsp;
				</if>
				<if condition="$delivery_type eq 5">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">快递配送</a>&nbsp;&nbsp;
				</if>
				<if condition="in_array($delivery_type, array(2, 3, 4))">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store<elseif condition="$delivery_type neq 2" />pick_in_store_click<else />pick_in_store</if>" id="pick_in_store">到店自提</a>
				</if>
			</li>
			<if condition="$delivery_type neq 2">
			<li id="li_delivery" <if condition="$pick_addr_id">style="display:none"</if>>
				<a href="{pigcms{:U('My/adress',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'mer_id' => $store['mer_id'], 'current_id'=>$user_adress['adress_id']))}">
					<strong>
						<span id="showAddres"><if condition="$user_adress['adress_id']">{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}<else/>请点击添加送货地址</if></span><br>
						<span id="showName">{pigcms{$user_adress['name']}</span>
						<span id="showTel">{pigcms{$user_adress['phone']}</span>
					</strong>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			</if>
			<if condition="in_array($delivery_type, array(2, 3, 4))">
			<li id="li_pick" <if condition="$delivery_type neq 2 AND empty($pick_addr_id)">style="display:none"</if>>
				<a href="{pigcms{:U('My/pick_address',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'mer_id' => $store['mer_id'],'pick_addr_id' => $pick_address['pick_addr_id']))}">
					<strong>
						<span id="showAddres">地址：{pigcms{$pick_address['name']}</span><br>
						<span id="showName">电话：{pigcms{$pick_address['phone']}</span><br>
						<span id="showTel">省市区：{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']}</span>
					</strong>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			</if>
		</ul>
		<ul class="box pay_box">
			<li id="show_arrive_date" <if condition="$delivery_type eq 2 OR $pick_addr_id">style="display:none"</if>>
				<a href="javascript:void(0);" id="dateBtn" class="date">
					<strong>送达日期</strong>
					<span id="arriveDate">{pigcms{$arrive_date}</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			<li id="show_arrive_time" <if condition="$delivery_type eq 2 OR $pick_addr_id">style="display:none"</if>>
				<a href="javascript:void(0);" id="timeBtn" class="time">
					<strong>送达时间</strong>
					<span id="arriveTime">尽快送出</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			<li>
				<a href="javascript:void(0);" id="remarkBtn">
					<strong>订单备注</strong>
					<span id="remarkTxt">点击添加订单备注</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul>
		<ul class="box" <if condition="$store['is_invoice'] AND $store['invoice_price'] elt $price">style="display:block"<else/>style="display:none"</if>>
			<li>
				<a href="javascript:void(0);" id="invoiceBtn">
					<strong id="invoiceTxt">点击添加发票抬头</strong>
					<span ></span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul>
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
					<strong>$<span class="unit_price">{pigcms{$ditem['price']}</span> <span style="color: gray; font-size:10px">({pigcms{$ditem['num']}{pigcms{$ditem['unit']})</span></strong>
				</div>
			</div>
		</li>
		</volist>
		</ul>
		<ul class="menu_list box" style="margin-bottom:20px;">
			<li>
				<div>
					<h3>折扣后商品总价：<strong style="display: inline;font-size:14px;">${pigcms{$vip_discount_money|floatval}</strong>元</h3>
				</div>
			</li>
			<if condition="$packing_charge">
			<li>
				<div>
					<h3>{pigcms{$store['pack_alias']|default='打包费'}：<strong style="display: inline;font-size:14px;">${pigcms{$packing_charge|floatval}</strong>元</h3>
				</div>
			</li>
			</if>
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
	  <input class="hidden" id="ouserName" name="ouserName" value="{pigcms{$user_adress['name']}">
	  <input class="hidden" id="ouserTel" name="ouserTel" value="{pigcms{$user_adress['phone']}">
	  <input class="hidden" id="ouserAddres" name="ouserAddres" value="{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']}  {pigcms{$user_adress['detail']}">
	  <input class="hidden" id="address_id" name="address_id" value="{pigcms{$user_adress['adress_id']}">
	  <input type="hidden" name="pick_address" value="{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']} {pigcms{$pick_address['name']} 电话：{pigcms{$pick_address['phone']}"/>
	  <input type="hidden" name="pick_id" value="{pigcms{$pick_address['pick_addr_id']}"/>
	  <input class="hidden" id="oarrivalDate" name="oarrivalDate" value="">
	  <input class="hidden" id="oarrivalTime" name="oarrivalTime" value="">
	  <input class="hidden" id="omark" name="omark" value="">
	  <input class="hidden" id="invoice_head" name="invoice_head" value="">
	  <input class="hidden" id="deliver_type" name="deliver_type" value="<if condition="$delivery_type eq 2 OR $pick_addr_id">1<else />0</if>">
	</div>
	</form>
	<div class="addres_box" id="remarkBox">
		<ul>
			<li><textarea class="txt max" placeholder="请填写备注" id="userMark"></textarea></li>
			<li class="btns_wrap">
			<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancleRemark">取消</a></span>
			<span><a href="javascript:void(0);" class="comm_btn higher" id="saveRemark">确认</a></span>
			</li>
		</ul>
	</div>
	<div class="addres_box" id="invoice_head_box">
		<ul>
			<li><textarea class="txt max" placeholder="请填发票抬头" id="invoice_head_txt"></textarea></li>
			<li class="btns_wrap">
			<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancleInvoice">取消</a></span>
			<span><a href="javascript:void(0);" class="comm_btn higher" id="saveInvoice">确认</a></span>
			</li>
		</ul>
	</div>
</div>
<div class="fixed" style="min-height:90px;padding:14px;">
	<p>
		<span class="fr">商品总计：<strong>$<span id="totalPrice_">{pigcms{$price}</span></strong> / <span id="cartNum_">{pigcms{$total}</span>份</span>
		<p id="show_delivery_fee" <if condition="$delivery_type eq 2 OR $pick_addr_id">style="display:none"</if>>{pigcms{$store['freight_alias']|default='配送费'}：${pigcms{$delivery_fee}</p>			
	</p>
	<span class="fr" style="position: absolute; bottom: 8px; right: 20px;">
	<a href="javascript:;" class="comm_btn" id="submit_order" >确认订单</a>
	</span>
</div>
<script type="text/javascript">
if(/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase())){
	if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
		var reg = /versioncode=(\d+),/;
		var arr = reg.exec(navigator.userAgent.toLowerCase());
		if(arr == null){
		}else{
			if(parseInt(arr[1]) >= 50){
				$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
			}
		}
	}
}
$(document).ready(function () {
	$(window).scrollTop(1);
	$('div.fixed').css('bottom','0');
    var maxYear = '{pigcms{$maxYear}';
    var maxMouth = '{pigcms{$maxMouth}';
    var maxDay = '{pigcms{$maxDay}';
    var maxYear2 = '{pigcms{$maxYear2}';
    var maxMouth2 = '{pigcms{$maxMouth2}';
    var maxDay2 = '{pigcms{$maxDay2}';
    var maxHour = '{pigcms{$maxHour}';
    var maxMinute = '{pigcms{$maxMinute}';
    var minYear = '{pigcms{$minYear}';
    var minMouth = '{pigcms{$minMouth}';
    var minDay = '{pigcms{$minDay}';
    var minHour1 = '{pigcms{$minHour1}';
    var minMinute1 = '{pigcms{$minMinute1}';
    var minHour2 = '{pigcms{$minHour2}';
    var minMinute2 = '{pigcms{$minMinute2}';
    var today = '{pigcms{$today}';
    var is_cross_day = {pigcms{$is_cross_day};
    var opt = {};

    opt.date = {preset:'date', minDate: new Date(minYear, minMouth, minDay, minHour2, minMinute2), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
    if (is_cross_day == 1) {
        opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour1, minMinute1), maxDate:new Date(maxYear2,maxMouth2, maxDay2, maxHour, maxMinute)};
    } else {
        opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour1, minMinute1), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
    }
    opt.time_default = {
            theme: 'android-ics light', //皮肤样式
            display: 'bottom', //显示方式
            mode: 'scroller', //日期选择模式
            lang:'zh',
            minWidth: 64,
            setText: '确定', //确认按钮名称
            cancelText: '取消',//取消按钮
            dateFormat: 'yy-mm-dd',
    		onSelect: function (valueText, inst) {
        		if (is_cross_day == 1) {
        			$('#arriveDate').html(valueText.substr(0, 10));
        			$('#oarrivalDate').val(valueText.substr(0, 10));
	    			$('#arriveTime').html(valueText.substr(11));
	    			$('#oarrivalTime').val(valueText.substr(11));
        		} else {
	    			$('#arriveTime').html(valueText);
	    			$('#oarrivalTime').val(valueText);
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
        setText: '确定', //确认按钮名称
        cancelText: '取消',//取消按钮
        dateFormat: 'yy-mm-dd',
		onSelect: function (valueText, inst) {
			if (is_cross_day == 1) {
				if (valueText == today) {
					opt.time = {preset:'datetime', minDate: new Date(minYear, minMouth, minDay, minHour1, minMinute1), maxDate:new Date(maxYear2,maxMouth2, maxDay2, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				} else {
					var d = new Date(valueText);
					opt.time = {preset:'datetime', minDate: new Date(d.getFullYear(), d.getMonth(), d.getDate(), minHour2, minMinute2), maxDate:new Date(d.getFullYear(), d.getMonth(), d.getDate()+1, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				}
			} else {
				if (valueText == today) {
					opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour1, minMinute1), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				} else {
					opt.time = {preset:'time', minDate: new Date(minYear, minMouth, minDay, minHour2, minMinute2), maxDate:new Date(maxYear,maxMouth, maxDay, maxHour, maxMinute)};
					$(".time").scroller('destroy').scroller($.extend(opt['time'], opt['time_default']));
				}
			}
			$('#arriveDate').html(valueText);
			$('#oarrivalDate').val(valueText);
        }
    };
    $(".date").scroller('destroy').scroller($.extend(opt['date'], opt['default']));
    

	

    
	$('#post_package').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'none');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee').css('display', 'block');
		$('#deliver_type').val(0);
	});
	$('#pick_in_store').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'block');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee').css('display', 'none');
		$('#deliver_type').val(1);
	});

	// 添加备注
	$('#remarkBtn').bind('click', function(){
		var remark = $('#remarkTxt').text();
		if(remark == '点击添加订单备注') remark = '';
		$('#userMark').val(remark);
		$('#remarkBox').dialog({title: '添加备注'});
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
// 		if($('#address_id').val() == ''){
// 			$(window).scrollTop(0);
// 			$('#showAddres').css('color','red').closest('li').css('border','1px solid red');
// 			alert('请您先添加送货地址');
// 			return false;
// 		}
		if(!$(this).hasClass('disabled')){
			$(this).addClass('disabled');
			
// 			var wo_delivery_time = $.trim($("#arriveTime").html());
// 			if(wo_delivery_time == '尽快送出'){
// 				wo_delivery_time = '';
// 			}
// 			$('#oarrivalTime').val(wo_delivery_time);
			
			var wo_memo = $.trim($("#remarkTxt").html());
			if(wo_memo == '点击添加订单备注') {
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
	
	if(/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase())){
		var reg = /versioncode=(\d+),/;
		var arr = reg.exec(navigator.userAgent.toLowerCase());
		if(arr == null){
			
		}else{
			var version = parseInt(arr[1]);
			if(version >= 50){
				if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
					// $('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
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
}
</script>
</body>
{pigcms{$hideScript}
</html>