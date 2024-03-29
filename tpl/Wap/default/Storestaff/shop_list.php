<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>店员中心</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script>
	$(function(){
		$(".startOrder,.stopOrder").click(function(){
			$.get("/wap.php?g=Wap&c=Storestaff&a=shop_list&action=changeWorkstatus&type="+$(this).attr('ref'), function(){
				window.location.reload();
			});
		});
	})
</script>
<style>
	.startOrder{color: #fff;float: right;background: green;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: left}
	.stopOrder{color: #000;float: right;background: #ccc;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: left}
	.addorder{color: #000;float: right;color: #fff;background-color: #ffa64d;padding: 10px 0px 10px 0px;width:100%;text-align:center;float: right}
</style>
    <style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    #orders .dealcard-block-right {
			margin-left:1px;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 0;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }

	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
		#nav-dropdown{height: 1.7rem;}
		#filtercon select{height: 100%;line-height: normal;width:100%;}
		#filtercon{margin: 0 .15rem;}
.find_div {
margin: .15rem 0;
}
	#filtercon input{background-color: #fff;
		width: 100%;
		border: none;
		background: rgba(255, 255, 255, 0);
		outline-style: none;
		display: block;
		line-height: .28rem;
		height: 100%;
		font-size: .28rem;
		padding: 0
}
		#find_submit{
			position: absolute;
			right: 0rem;
			top: .15rem;
			width: 1.2rem;
			height: .7rem;;
			-webkit-box-sizing: border-box;
            padding: 0px;
            background-color: #ffa64d;
		}
 .dealcard-block-right li{
    font-size: .266rem;
font-weight: 400;
 }
.dealcard-block-right .dth{font-weight: bold;}
 .ulrightdiv{
	float: right;
	position: relative;
	top: -60px;
	margin-right: 15px;
	}
	dl.list .dd-padding{padding: .28rem 0.1rem;}
	.red{color:red;}
.top-btn-a a{color: #fff;margin-top: 10px;}
.top-btn-a .lb{margin-left: 20px;}
.top-btn-a .rb{float: right;margin-right: 20px;}
.dealcard-block-right{padding: 0 10px;}
#orders a{color: #333;}
#orders .td a{color: green;}
.find_type_div{
	position: absolute;
left: 0rem;
width: 1.7rem;
height: .7rem;
text-align: center;
background: white;
}
.find_txt_div{
vertical-align: middle;
position: relative;
margin-right: 1.3rem;
margin-left:1.8rem;
border-radius: .06rem;
border: 1px #CCC solid;
height: .7rem;
line-height: .7rem;
}
  .dealcard-block-right li.btm_li{
     margin-bottom: .18rem;
 }

        #new_msg{
            width: 230px;
            text-align: center;
        }
        #new_msg a{
            display: inline-block;
            width: 100%;
            height: 30px;
            line-height: 30px;
            text-align: center;
            color: white;
            border: 1px solid white;
            border-radius: 5px;
            margin-top: 20px;
            font-weight: bold;
            font-size: 20px;
        }
</style>
</head>
<body>
	<dl class="list"  style="border-top:none;margin-top:0rem;">
		<dd id="filtercon">
			<div class="find_div">
				<form name="find_form" method="get" action="{pigcms{:U('Storestaff/shop_list')}">
					<input type="hidden" name="g" value="Wap"/>
					<input type="hidden" name="c" value="Storestaff"/>
					<input type="hidden" name="a" value="shop_list"/>
					<div class="find_type_div">
						<select name="ft" id="find_type" onchange="toJmupURl(this.value);">							
							<option value="">请选择类型</option>
							<option value="st" <php>if($ftype=='st') echo "selected='selected'";</php>>待验证订单</option>
							<option value="oid" <php>if($ftype=='oid') echo "selected='selected'";</php>>订单编号</option>
							<option value="xm" <php>if($ftype=='xm') echo "selected='selected'";</php>>客户姓名</option>
							<option value="dh" <php>if($ftype=='dh') echo "selected='selected'";</php>>客户电话</option>
							<option value="mps" <php>if($ftype=='mps') echo "selected='selected'";</php>>订单流水号</option>									
						</select>
					</div>
					<div class="find_txt_div"><input name="fv" id="find_value" type="text" value="{pigcms{$fvalue}"/></div>
					<button class="btn btn-success" type="submit" id="find_submit">搜索</button>
				</form>
			</div>
		</dd>
	</dl>
	<dl class="list">
	<dd>
        <a href="{pigcms{:U('Storestaff/add_shop_order')}" class="addorder">添加订单</a>
	</dd>
</dl>
	    <div style="margin-top:.2rem;">
		    <dl class="list" id="orders">
				<volist name="order_list" id="vo">
					<dd class="dealcard dd-padding" onclick="Jumpto({pigcms{$vo['order_id']})">
						<ul class="dealcard-block-right">
							<li class="btm_li"><span class="dth">客户姓名：</span>
							<span class="ttd">{pigcms{$vo.username}</span></li>
							<li class="btm_li"><span class="dth">客户电话：</span><span class="td"><a  href="tel:{pigcms{$vo.userphone}" onclick="stopPropagation()">{pigcms{$vo.userphone}</a></span></li>
							<li class="btm_li"><span class="dth">订单总价：</span><span class="td red">${pigcms{$vo.price|floatval}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo.extra_price}{pigcms{$config.extra_price_alias_name}</if></span></li>
							<li>
							<span class="dth">支付状态：</span>
							<if condition="empty($vo['third_id']) AND ($vo['pay_type'] eq 'offline' OR $vo['pay_type'] eq 'Cash')" >
								<span style="color: red">线下未支付</span>
							<elseif condition="$vo['paid'] eq 0" />
								<span style="color: red">未支付</span>
							<else />
								<span style="color: green">已支付</span> / <span style="color: #aaa">{pigcms{$vo['pay_type_str']}</span>
							</if>
                                <if condition="$vo['paid'] eq 0">
								<a class="red edit_btn" style="color: red; float:right" href="javascript:;" >未支付</a>
							<elseif condition="$vo['status'] eq 0 AND $vo['paid'] eq 1" />
								<if condition="($vo['is_pick_in_store'] eq 2 OR $vo['is_pick_in_store'] eq 1) AND $now_store['is_open_pick'] eq 1">
								<a title="操作订单" class="green handle_btn" style="color: green; float:right" href="{pigcms{:U('Storestaff/pick',array('order_id'=>$vo['order_id']))}">分配自提点</a>
								<elseif condition="$vo['is_pick_in_store'] eq 3 AND $now_store['deliver_type'] neq 5" />
								<a data-title="接单" class="green edit_btn " style="color: green; float:right" href="{pigcms{:U('Storestaff/mall_order_detail',array('order_id'=>$vo['order_id']))}" js-order="{pigcms{$vo.order_id}">更换配送</a>
								<else />
<!--								<a title="操作订单" class="green edit_btn js-add-order js-add-order-{pigcms{$vo.order_id}" style="color: green; float:right" href="javascript:;" js-order="{pigcms{$vo.order_id}">接单</a>
								<a title="操作订单" class="green edit_btn js-add-order js-add-order-{pigcms{$vo.order_id}" style="color: #fff;background-color: #FF658E;position: absolute;right: 0rem; top: .15rem;width: 1.2rem;height: .7rem;text-align: center;line-height: .7rem;float:right" href="javascript:;" js-order="{pigcms{$vo.order_id}">接单</a-->
                                    <a title="操作订单" class="green edit_btn" style="color: #fff;background-color: #ffa64d;position: absolute;right: 0rem; top: .15rem;width: 1.2rem;height: .7rem;text-align: center;line-height: .7rem;float:right" href="{pigcms{:U('Storestaff/shop_edit',array('order_id'=>$vo['order_id']))}">查看订单</a>
								</if>
							<elseif condition="$vo['status'] eq 1" />
							<a title="已接单" class="green edit_btn" style="color: green; float:right" href="javascript:;" >已接单</a>
							<elseif condition="$vo['status'] eq 2" />
							<a title="已消费" class="green edit_btn" style="color: blue; float:right" href="javascript:;" >已消费</a>
							<elseif condition="$vo['status'] eq 3" />
							<a title="已评价" class="green edit_btn" style="color: gray; float:right" href="javascript:;" >已评价</a>
							<elseif condition="$vo['status'] eq 4" />
							<a title="已退款" class="red edit_btn" style="color: red; float:right" href="javascript:;" >已退款</a>
							<elseif condition="$vo['status'] eq 5" />
							<a title="已取消" class="red edit_btn" style="color: red; float:right" href="javascript:;" >已取消</a>
							<elseif condition="$vo['status'] eq 7" />
							<a title="操作订单" class="green edit_btn js-send-order js-add-order-{pigcms{$vo.order_id}" style="color: green; float:right" href="javascript:;" js-order="{pigcms{$vo.order_id}">发货</a>
							<elseif condition="$vo['status'] eq 8" />
							<a title="已发货" class="red edit_btn" style="color: green; float:right" href="javascript:;" >已发货</a>
							<elseif condition="$vo['status'] eq 9" />
							<a title="自提点接货" class="red edit_btn" style="color: green; float:right" href="javascript:;" >自提点接货</a>
							<elseif condition="$vo['status'] eq 10" />
							<a title="自提点发货" class="red edit_btn" style="color: green; float:right" href="javascript:;" >自提点发货</a>
							<else />
							<a title="未支付" class="red edit_btn" style="color: red; float:right" href="javascript:;" >未支付，不接单</a>
							</if>
							
							</li>
						</ul>
					</dd>
				</volist>
			</dl>
			<div style="margin-top:.2rem;margin-bottom:.2rem;margin-left:1.5rem;">{pigcms{$pagebar}</div>
		</div>
		<include file="Storestaff:footer"/>
</body>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	var is_click = false;
	$('.js-add-order').click(function(){
		if (is_click) return false;
		is_click = true;
		var order_id = $(this).attr('js-order');
		$('.js-add-order-'+order_id).html('处理中');
		$(this).unbind('click');
		$.post("{pigcms{:U('Storestaff/shop_order_confirm')}",{order_id:order_id,status:1},function(result){
			is_click = false;
			if(result.status == 1){
				$('.js-add-order-'+order_id).html(result.info);
				$('.js-add-order').removeClass('js-add-order');
			}else{
				layer.open({
				    style: 'border:none; background-color:#78BA32; color:#fff;',
				    content:result.info,
				    time: 2
				})
				//window.location.reload();
			}
		});
		return false;
	});
	
	var is_send_click = false;
	$('.js-send-order').click(function(){
		if (is_send_click) return false;
		is_send_click = true;
		var order_id = $(this).attr('js-order');
		$('.js-add-order-'+order_id).html('处理中');
		$(this).unbind('click');
		$.post("{pigcms{:U('Storestaff/deliver_goods')}",{order_id:order_id,status:1},function(result){
			is_send_click = false;
			if(result.status == 1){
				$('.js-add-order-'+order_id).html(result.info);
				$('.js-send-order').removeClass('js-send-order');
			}else{
				layer.open({
				    style: 'border:none; background-color:#78BA32; color:#fff;',
				    content:result.info,
				    time: 2
				})
				//window.location.reload();
			}
		});
		return false;
	});
});
    function Jumpto(oid){
		window.location.href="{pigcms{:U('Storestaff/shop_edit')}&order_id="+oid;
	}
	function toJmupURl(vv){
	   vv=$.trim(vv);
	   if(vv=='st'){
	     window.location.href="{pigcms{:U('Storestaff/shop_list')}&st=1";
	   }
	}
	function stopPropagation(e){
	  e = e || window.event; 
	  if(e.stopPropagation) { //W3C阻止冒泡方法  
        e.stopPropagation();  
       } else {  
        e.cancelBubble = true; //IE阻止冒泡方法  
       } 
	}
	$('#find_type').change(function(){
		if($(this).val() != '1'){
			$('#find_value').val($('#find_value').val().replace(/\s+/g,""));
		}else{
			$('#find_value').val($('#find_value').val().replace(/\s+/g,"").replace(/(\d{4})/g,'$1 '));
		}
	});
	$('#find_value').keyup(function(){
		if($('#find_type').val() == '1'){
			if($(this).val().substr(-1) == ' '){
				$(this).val($(this).val().substr(0,($(this).val().length-1)));
			}else{
				$(this).val($(this).val().replace(/\s+/g,"").replace(/(\d{4})/g,'$1 '));
			}
		}
	});
	$('#find_submit').click(function(){
		var find_value = $('#find_value');
		find_value.val($.trim(find_value.val()));
		var find_type = $.trim($('#find_type').val());
		if(find_type && find_value.val().length < 1){
			alert('请输入查找内容！');
			find_value.focus();
			return false;
		}
		
		window.document.find_form.submit();
		return false;
	});
	var new_img = "{pigcms{$static_path}images/new_order.png";
	var new_url = "{pigcms{:U('Storestaff/getNewOrder')}";
    var link_url = "{pigcms{:U('Storestaff/shop_list')}";
    var sound_url = "{pigcms{$static_public}sound/soft-bells.mp3";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/new_order.js?v=2.1.0"></script>
{pigcms{$shareScript}
<script type="text/javascript">
function is_mobile(){
	var ua = navigator.userAgent.toLowerCase();
	if ((ua.match(/(iphone|ipod|android|ios|ipad)/i))){
		if(navigator.platform.indexOf("Win") == 0 || navigator.platform.indexOf("Mac") == 0){
			return false;
		}else{
			return true;
		}
	}else{
		return false;
	}
}
function is_weixin(){
    var ua = navigator.userAgent.toLowerCase();
    if(is_mobile() && ua.indexOf('micromessenger') != -1){  
        return true;  
    } else {  
        return false;  
    }  
}
function getParam(url,name){ 
	var reg = new RegExp("[&|?]"+name+"=([^&$]*)", "gi"); 
	var a = reg.test(url); 
	return a ? RegExp.$1 : ""; 
}
$('#qrcode_btn').click(function(){
	if(is_weixin()){
		wx.scanQRCode({
			needResult:1,
			scanType:["qrCode"],
			success:function (res){
				/*
				 * URL提示：
				 *    /wap.php?c=Storestaff&a=group_qrcode&id=(14位消费码)
				 *    /wap.php?c=Storestaff&a=meal_qrcode&id=(订单ID)
				 */
				var result = res.resultStr;
				if(result.indexOf('http://') !== 0 && result.indexOf('https://') !== 0){
					layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'您扫描的内容 “ <font color="red">'+result+'</font> ” 不是有效的验证二维码',btn: ['确定'],end:function(){}});
				}else{
					var ctype = getParam(result,'a'),id = getParam(result,'id'),c = getParam(result,'c');
					var actMode='shop_qrcode';
					if(ctype == 'group_qrcode') actMode='group_qrcode';
					if((ctype != 'group_qrcode' && ctype != 'meal_qrcode'&& ctype != 'shop_qrcode') || id== '' || c != 'Storestaff'){
						layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'您扫描的内容不是有效的验证二维码',btn: ['确定'],end:function(){}});
					}else{
						layer.open({
							title:['提示：','background-color:#FF658E;color:#fff;'],
							content:'初次检测订单属于 <font color="red">'+(ctype == 'group_qrcode' ? '团购' : (ctype == 'meal_qrcode' ? '订餐' : '快店'))+'</font> 订单，您是要验证消费或查看订单？',
							btn: ['验证消费', '查看订单'],
							shadeClose: false,
							yes: function(){
								layer.open({
									type: 2,
									content: '验证消费中，请稍后'
								});
								$.getJSON("/wap.php?g=Wap&c=Storestaff&a="+actMode,{type:ctype,id:id,ajax:1},function(ret){ 
									if(!ret.error){
										layer.open({
											title:['成功提示：','background-color:#FF658E;color:#fff;'],
											content:'验证成功！是否要刷新页面？',
											btn: ['确定','取消'],
											yes: function(index){
												window.location.href='/wap.php?g=Wap&c=Storestaff&a=shop_list';
												layer.close(index);
											}
										});
									}else{
										layer.open({
											title:['错误提示：','background-color:#FF658E;color:#fff;'],
											content:ret.msg,
											btn: ['确定'],
											end:function(){
												window.location.href='/wap.php?g=Wap&c=Storestaff&a=shop_list';
											}
										});
									}
								});
							}, no: function(){
								if(ctype == 'group_qrcode'){
									window.location.href = "{pigcms{:U('Storestaff/group_edit')}&order_id="+getParam(result,'order_id');
								}else if(ctype == 'shop_qrcode'){
									window.location.href = "{pigcms{:U('Storestaff/shop_edit')}&order_id="+getParam(result,'id');
								}else if(ctype == 'meal_qrcode'){
									window.location.href = "{pigcms{:U('Storestaff/meal_edit')}&order_id="+getParam(result,'id');
								}
							}
						});
					}
				}	
			}
		});
	}else{
		layer.open({title:['错误提示：','background-color:#FF658E;color:#fff;'],content:'您使用的不是微信浏览器，此功能无法使用！您可以使用浏览器自带的或其他扫描二维码工具进行扫描',btn: ['确定'],end:function(){}});
	}
	return false;
});
</script>
</html>