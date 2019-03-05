<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>Merchant Center</title>
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
</style>
</head>
<body>
	<dl class="list"  style="border-top:none;margin-top:0rem;">
		<dd id="filtercon">
			<div class="find_div">
				<form name="find_form" method="get" action="{pigcms{:U('Storestaff/meal_list')}">
					<input type="hidden" name="g" value="Wap"/>
					<input type="hidden" name="c" value="Storestaff"/>
					<input type="hidden" name="a" value="meal_list"/>
					<div class="find_type_div">
						<select name="ft" id="find_type" onchange="toJmupURl(this.value);">							
							<option value="">Please select the type</option>
							<option value="st" <php>if($ftype=='st') echo "selected='selected'";</php>>Pending order</option>
							<option value="oid" <php>if($ftype=='oid') echo "selected='selected'";</php>>Order Number</option>
							<option value="xm" <php>if($ftype=='xm') echo "selected='selected'";</php>>Custmer Name</option>
							<option value="dh" <php>if($ftype=='dh') echo "selected='selected'";</php>>Custmer Phone Number</option>
							<option value="mps" <php>if($ftype=='mps') echo "selected='selected'";</php>>Consumption code</option>									
						</select>
					</div>
					<div class="find_txt_div"><input name="fv" id="find_value" type="text" value="{pigcms{$fvalue}"/></div>
					<button class="btn btn-success" type="submit" id="find_submit">Search</button>
				</form>
			</div>
		</dd>
	</dl>
	    <div style="margin-top:.2rem;">
		    <dl class="list" id="orders">
				<volist name="order_list" id="vo">
					<dd class="dealcard dd-padding" onclick="Jumpto({pigcms{$vo['order_id']})">
						<ul class="dealcard-block-right">
							<li class="btm_li"><span class="dth">Custmer Name：</span>
							<span class="ttd">{pigcms{$vo.name}</span></li>
							<li class="btm_li"><span class="dth">Custmer Phone Number：</span><span class="td"><a  href="tel:{pigcms{$vo.phone}" onclick="stopPropagation()">{pigcms{$vo.phone}</a></span></li>
							<li class="btm_li"><span class="dth">Order Total：</span><span class="td red">${pigcms{$vo.price}</span></li>
							<li><span class="dth">Order Status：</span>
							<if condition="$vo['status'] eq 3">
			                  <span style="color: red">Canceled and refunded</span>
							<elseif condition="empty($vo['third_id']) AND ($vo['pay_type'] eq 'offline')" />
							 <span style="color: red">Not Paid</span>
							 <else/>
							<if condition="$vo['paid'] eq 0"><span style="color: red">Not Paid</span>
							<else/>
							<span style="color: green">Paid</span></if> / <if condition="$vo['status'] eq 0"><span style="color: red">Not consumed</span><elseif condition="$vo['status'] eq 1" /><span style="color: green">Consumed</span><elseif condition="$vo['status'] eq 2" /><span style="color: green">Has been consumed and evaluated</span></if>
							</if>
							<if condition="$vo['status'] gt 2">
							<strong style="color: red; float:right">Order canceled</strong>
							<elseif condition="$vo['is_confirm'] eq 1"/>
							<strong style="color: green; float:right">Order Has Been Confrim</strong>
							<elseif condition="$vo['is_confirm'] eq 0" />
							<a title="Operate order" class="green edit_btn js-add-order js-add-order-{pigcms{$vo.order_id}" style="color: green; float:right" href="javascript:;" js-order="{pigcms{$vo.order_id}">Confirm</a>
							<else />
							<strong style="color: red; float:right">Not paid, can not confirm</strong>
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
	$('.js-add-order').click(function(){
		var order_id = $(this).attr('js-order');
		$('.js-add-order-'+order_id).html('Pending');
		$.post("{pigcms{:U('Storestaff/check_confirm')}",{order_id:order_id,status:1},function(result){
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
});
    function Jumpto(oid){
		window.location.href="{pigcms{:U('Storestaff/meal_edit')}&order_id="+oid;
	}
	function toJmupURl(vv){
	   vv=$.trim(vv);
	   if(vv=='st'){
	     window.location.href="{pigcms{:U('Storestaff/meal_list')}&st=1";
	   }
	}
	function stopPropagation(e){
	  e = e || window.event; 
	  if(e.stopPropagation) { //W3C prevents bubbling methods
        e.stopPropagation();  
       } else {  
        e.cancelBubble = true; //IE prevents bubbling methods  
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
			alert('Please enter the search!');
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
				 *    /wap.php?c=Storestaff&a=group_qrcode&id=(14位Consumption code)
				 *    /wap.php?c=Storestaff&a=meal_qrcode&id=(订单ID)
				 */
				var result = res.resultStr;
				if(result.indexOf('http://') !== 0){
					layer.open({title:['Error message：','background-color:#FF658E;color:#fff;'],content:'Please Scen “ <font color="red">'+result+'</font> ” Not valid validation of QR code',btn: ['Confirm'],end:function(){}});
				}else{
					var ctype = getParam(result,'a'),id = getParam(result,'id'),c = getParam(result,'c');
					var actMode='meal_qrcode';
					if(ctype == 'group_qrcode') actMode='group_qrcode';
					if((ctype != 'group_qrcode' && ctype != 'meal_qrcode') || id== '' || c != 'Storestaff'){
						layer.open({title:['Error message：','background-color:#FF658E;color:#fff;'],content:'Not valid validation of QR code',btn: ['Confirm'],end:function(){}});
					}else{
						layer.open({
							title:['prompt：','background-color:#FF658E;color:#fff;'],
							content:'The initial inspection order belongs <font color="red">'+(ctype == 'group_qrcode' ? 'Group buy' : 'Food Order')+'</font> Orders, do you want to verify consumption or view orders?',
							btn: ['Verify consumption', 'View Order'],
							shadeClose: false,
							yes: function(){
								layer.open({
									type: 2,
									content: 'Verifiering consumption, please later'
								});
								$.getJSON("/wap.php?g=Wap&c=Storestaff&a="+actMode,{type:ctype,id:id,ajax:1},function(ret){ 
									if(!ret.error){
										layer.open({
											title:['Sucessful：','background-color:#FF658E;color:#fff;'],
											content:'Validation successful! Do you want to refresh the page?',
											btn: ['Yes','No'],
											yes: function(index){
												window.location.href='/wap.php?g=Wap&c=Storestaff&a=meal_list';
												layer.close(index);
											}
										});
									}else{
										layer.open({
											title:['Error message：','background-color:#FF658E;color:#fff;'],
											content:ret.msg,
											btn: ['Confirm'],
											end:function(){
												window.location.href='/wap.php?g=Wap&c=Storestaff&a=meal_list';
											}
										});
									}
								});
							}, no: function(){
								if(ctype == 'group_qrcode'){
									window.location.href = "{pigcms{:U('Storestaff/group_edit')}&order_id="+getParam(result,'order_id');
								}else{
									window.location.href = "{pigcms{:U('Storestaff/meal_edit')}&order_id="+getParam(result,'id');
								}
							}
						});
					}
				}	
			}
		});
	}else{
		layer.open({title:['Error message：','background-color:#FF658E;color:#fff;'],content:'You are not using WeChat browser, this feature can not be used! You can use the browser's own or other scanning two-dimensional code tool to scan',btn: ['Confirm'],end:function(){}});
	}
	return false;
});
</script>
</html>