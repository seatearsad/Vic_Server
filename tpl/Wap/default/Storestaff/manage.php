<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_STORE_CENTER_')}</title>
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
	.addorder{color: #000;float: right;color: #fff;background-color: #06c1ae;;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: right}
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

 .store_name{
     height: 20px;
     margin-left: 105px;
     margin-top: -90px;
 }
 .store_name div{

 }
 .store_open{
     float: right;
     margin-top: -30px;
     margin-right: 10px;
     width: 60px;
     height: 25px;
     text-align: center;
     line-height: 25px;
     border: 1px solid #e6e6e6;
     background-color: #0CCC6C;
     color: #ffffff;
     cursor: pointer;
 }
.store_close{
    float: right;
    margin-top: -30px;
    margin-right: 10px;
    width: 60px;
    height: 25px;
    text-align: center;
    line-height: 25px;
    border: 1px solid #e6e6e6;
    background-color: #ff2c4c;
    color: #ffffff;
    cursor: pointer;
}
#features{
    list-style: none;
}
#features li{
    width: 30%;
    height: 100px;
    display: inline-block;
    border: 1px solid #ccc;
    text-align: center;
    margin-left: 1.5%;
    margin-top: 10px;
    cursor: pointer;
}
#features li div{
    margin-top:40px;
    word-wrap:break-word;
}
</style>
</head>
<body>
	<dl class="list"  style="border-top:none;margin-top:0rem;">
        <dd id="filtercon">
			<div class="find_div">
                <div style="height: 110px;">
                    <img src="{pigcms{$store.image}" width="100" height="100">
                    <div class="store_name">
                        <div style="font-size: 20px">{pigcms{$store.name}</div>

                        <div style="margin-top: 10px;">
                            {pigcms{:L('_STORE_OPEN_CLOSE_')}:
                            <if condition="$store['status']">
                                <if condition="$store['is_close']">{pigcms{:L('_AT_REST_')}<else />{pigcms{:L('_AT_BUSINESS_')}</if>
                            <else />
                                {pigcms{:L('_AT_REST_')}
                            </if>
                        </div>
                    </div>
                </div>
                <if condition="$store['status']">
                <div id="store_open" <if condition="$store['is_close']">class="store_open"<else />class="store_close"</if>>
                    <if condition="$store['is_close']">
                        {pigcms{:L('_STORE_OPEN_')}
                    <else />
                        {pigcms{:L('_STORE_CLOSE_')}
                    </if>
                </div>
                </if>
			</div>
		</dd>
	</dl>
	<dl class="list"></dl>
    <div style="margin-top:.2rem;">
        <ul id="features">
            <li id="info">
                <div>{pigcms{:L('_STORE_INFO_')}</div>
            </li>
            <li id="manage_product">
                <div>{pigcms{:L('_STORE_PRODUCT_MANAGE_')}</div>
            </li>
            <li id="manage_time">
                <div>{pigcms{:L('_STORE_TIME_MANAGE_')}</div>
            </li>
        </ul>
    </div>
    <include file="Storestaff:footer"/>
</body>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript">
//更新app 设备token
function pushDeviceToken(token) {
    var message = '';
    if(token != "{pigcms{$staff_session['device_id']}") {
        $.post("{pigcms{:U('Storestaff/update_device')}", {'token': token}, function (result) {
            if (result) {
                message = result.message;
            } else {
                message = 'Error';
            }
        });
    }
    return message;
}
//更新Android 设备token
if(typeof (window.linkJs) != 'undefined'){
    var android_token = window.linkJs.getDeviceId();
    if(android_token != "{pigcms{$staff_session['device_id']}"){
        var message = '';
        $.post("{pigcms{:U('Storestaff/update_device')}", {'token':android_token}, function(result) {
            if(result){
                message = result.message;
            }else {
                message = 'Error';
            }
        });
    }
}

if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()) || /(tuttipartner)/.test(navigator.userAgent.toLowerCase())){
    var html = '<li id="set_printer"><div>{pigcms{:L(\'_STORE_SET_PRINTER_\')}</div></li>';
    $('#features').append(html);
}

$('#set_printer').click(function () {
    if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()))
        window.linkJs.gotoPrinter();
    else
        alert("Set Printer Click!");
});

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
</script>
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
var is_close = '{pigcms{$store.is_close}';
$('#store_open').click(function () {
    if(is_close == 0){//操作 关闭店铺
        layer.open({
            title:"{pigcms{:L('_STORE_REMIND_')}",
            content:"{pigcms{:L('_STORE_CLOSE_TIP_')}",
            btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
            yes: function(index){
                layer.close(index);
                $.post("{pigcms{:U('Storestaff/manage_open_close')}",{open_close:0},function(result){
                    layer.open({
                        title:"{pigcms{:L('_STORE_REMIND_')}",
                        content:result.info,
                        time: 1,
                        end:function () {
                            window.location.reload();
                        }
                    });

                });
            }
        });
    }else{//操作 打开店铺
        $.post("{pigcms{:U('Storestaff/manage_open_close')}",{open_close:1},function(result){
            if(result.status == 1) {
                layer.open({
                    title: "{pigcms{:L('_STORE_REMIND_')}",
                    content: result.info,
                    time: 1,
                    end: function () {
                        window.location.reload();
                    }
                });
            }else{
                layer.open({
                    title: "{pigcms{:L('_STORE_REMIND_')}",
                    content: result.info,
                });
            }

        });
    }
});

$('#manage_time').click(function () {
    window.location.href = '{pigcms{:U("Storestaff/manage_time")}';
});
$('#manage_product').click(function () {
    window.location.href = '{pigcms{:U("Storestaff/manage_product")}';
});
$('#info').click(function () {
    window.location.href = '{pigcms{:U("Storestaff/manage_info")}';
});
</script>
</html>