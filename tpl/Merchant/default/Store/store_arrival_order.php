<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		<tr>
			<th>订单编号</th>
			<th colspan="2">{pigcms{$now_order['orderid']}</th>
		</tr>
		<tr>
			<th>订单总价</th>
			<th>${pigcms{$now_order['total_price']|floatval}</th>
			
			<th rowspan="4" width="50%">
				<if condition="$offline_pay_list">
					<div>
						<volist name="offline_pay_list" id="vo">
							<button class="orderofflinepay" data-id="{pigcms{$vo.id}" style="margin-top:20px;">{pigcms{$vo.name}</button>
						</volist>
						<br>
						
					</div>
				</if>
				<if condition="$orderprinter">
					<div style="text-align:center;margin-top:30px;">
						<button style="background:#4f99c6!important;text-shadow:none;color:white;padding:10px 13px;" id="orderprinter">打印订单</button>
					</div>
				</if>
				
			</th>
		</tr>
		<if condition="$config.open_extra_price eq 1 AND $now_order.extra_price gt 0">
		<tr>
		<th>元宝价格（只支持线上支付）</th>
		<th>${pigcms{$now_order['total_price']-$now_order['discount_price']|floatval}+{pigcms{$now_order.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</th>
		</tr>
		</if>
		<tr>
			<th>优惠价格</th>
			<th>${pigcms{$now_order['discount_price']|floatval}</th>
		</tr>
		<tr>
			<th>实付金额</th>
			<th>${pigcms{$now_order['price']|floatval}</th>
		</tr>
		<tr>
			<th width="15%">订单备注</th>
			<th width="35%">{pigcms{$now_order['desc']}</th>
		</tr>
		<tr>
			<th colspan="2" style="text-align:center;">
				<div id="qrcodeUserWeixinCodeBox">
					<h3 style="margin:20px 0;">扫用户微信付款码支付<if condition="$config['arrival_alipay_open']"><span id="changeToAlipay" style="margin-left:20px;font-size:12px;color:blue;cursor:pointer;font-weight:normal;">切换到支付宝</span></if></h3>
					<div style="margin-top:60px;">
						<input type="text" style="height:30px;line-height:30px;padding-left:5px;" id="userQrcode"/>
						<button style="background:#4f99c6!important;text-shadow:none;color:white;" id="userQrcodeBtn">确认支付</button>
					</div>
					<div style="margin-top:60px;text-align:left;margin-left:30px;">
						建议使用扫码枪直接扫描得到值，<br/>或者先刷新用户微信中的码，再写入。<br/>如果扫码提示微信错误，可以关闭本页面重新创建订单。
						<if condition="$config['arrival_alipay_open']"><br/><font style="color:red;">按 空格 键可快速切换到支付宝模式</font></if>
					</div>
				</div>
				<div id="qrcodeUserAlipayCodeBox" style="display:none;">
					<h3 style="margin:20px 0;">扫用户支付宝付款码支付<span id="changeToWeixin" style="margin-left:20px;font-size:12px;color:blue;cursor:pointer;font-weight:normal;">切换到微信</span></h3>
					<div style="margin-top:60px;">
						<input type="text" style="height:30px;line-height:30px;padding-left:5px;" id="userAlipayQrcode"/>
						<button style="background:#4f99c6!important;text-shadow:none;color:white;" id="userAlipayQrcodeBtn">确认支付</button>
					</div>
					<div style="margin-top:60px;text-align:left;margin-left:30px;">
						建议使用扫码枪直接扫描得到值，<br/>或者先刷新用户支付宝中的码，再写入。<br/>如果扫码提示支付宝错误，可以关闭本页面重新创建订单。<br/>用户不能在支付宝中扫右侧微信二维码<br/><font style="color:red;">按 空格 键可快速切换到微信模式</font>
					</div>
				</div>
			</th>
			<th style="text-align:center;">
				<h3 style="margin:20px 0;">用户微信扫码支付</h3>
				<div>
					<img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$now_order['order_id']+3600000000}" style="width:250px;height:250px;"/>
				</div>
			</th>
		</tr>
	</table>
	<script type="text/javascript">
		var nowPayMethod = 'weixin';
		var checkPayStatus = null;
		$(function(){
			$('#changeToAlipay').click(function(){
				$('#qrcodeUserAlipayCodeBox').show();
				$('#qrcodeUserWeixinCodeBox').hide();
				nowPayMethod = 'alipay';
				$('#userAlipayQrcode').focus();
			});
			$('#changeToWeixin').click(function(){
				$('#qrcodeUserWeixinCodeBox').show();
				$('#qrcodeUserAlipayCodeBox').hide();
				nowPayMethod = 'weixin';
				$('#userQrcode').focus();
			});
			$('#userQrcode').focus();
			setInterval(function(){
				if(nowPayMethod == 'weixin'){
					if(document.activeElement.id != 'userQrcode'){
						$('#userQrcode').focus();
					}
				}else{
					if(document.activeElement.id != 'userAlipayQrcode'){
						$('#userAlipayQrcode').focus();
					}
				}
			},1000);
			var postPrintNow = false; 
			$('#orderprinter').click(function(){
				if(postPrintNow == true){
					alert('正在请求中，请稍等');
					return false;
				}
				postPrintNow = true;
				$.post("{pigcms{:U('store_arrival_print')}",{order_id:{pigcms{$now_order['order_id']}},function(){
					alert('已发送打印');
					postPrintNow = false;
				});
			});
			$('.orderofflinepay').click(function(){
				var tip_index = parent.layer.load(0, {shade: [0.5,'#fff']});
				$.post("{pigcms{:U('store_arrival_pay')}",{order_id:{pigcms{$now_order.order_id},'offline_pay':$(this).data('id')},function(result){
					parent.layer.close(tip_index);
					if(result.status == 1){
						clearInterval(checkPayStatus);
						alert('支付成功');
						window.top.location.reload();
					}else{
						alert(result.info);
					}
				});
			});
			
			$('#userQrcodeBtn').click(function(){
				postUserQrcode();
			});
			$('#userQrcode').keyup(function(e){
				if(e.keyCode == 32){
					$('#changeToAlipay').trigger('click');
					$('#userQrcode').val('');
				}else if(e.keyCode == 13){
					postUserQrcode();
				}
			});
			$('#userAlipayQrcode').keyup(function(e){
				if(e.keyCode == 32){
					$('#changeToWeixin').trigger('click');
					$('#userAlipayQrcode').val('');
				}else if(e.keyCode == 13){
					postUserQrcode();
				}
			});
			$('#userAlipayQrcodeBtn').click(function(){
				postUserQrcode();
			});
			checkPayStatus = setInterval(function(){
				$.post("{pigcms{:U('store_arrival_check')}",{order_id:{pigcms{$now_order['order_id']}},function(result){
					if(result.status == 1){
						alert('支付成功！');
						window.top.location.reload();
						// window.parent.location.href = "{pigcms{:U('store_arrival')}";
					}
				});
			},3000);
		});
		var postNow = false;
		function postUserQrcode(){
			if(nowPayMethod == 'weixin'){
				$('#userQrcode').val($.trim($('#userQrcode').val()));
				if($('#userQrcode').val() == ''){
					$('#userQrcode').focus();
					return false;
				}
			}else{
				$('#userAlipayQrcode').val($.trim($('#userAlipayQrcode').val()));
				if($('#userAlipayQrcode').val() == ''){
					$('#userAlipayQrcode').focus();
					return false;
				}
			}
			if(postNow == true){
				alert('正在请求中，请稍等');
				return false;
			}
			$('#userQrcodeBtn,#userAlipayQrcodeBtn').html('请求中...');
			postNow = true;
			var auth_code = nowPayMethod == 'weixin' ? $('#userQrcode').val() : $('#userAlipayQrcode').val();
			$.post("{pigcms{:U('store_arrival_pay')}",{order_id:{pigcms{$now_order.order_id},'auth_code':auth_code,'auth_type':nowPayMethod},function(result){
				if(result.status == 1){
					clearInterval(checkPayStatus);
					alert('支付成功');
					window.top.location.reload();
					// window.parent.location.href = "{pigcms{:U('store_arrival')}";
				}else{
					alert(result.info);
				}
				$('#userQrcodeBtn,#userAlipayQrcodeBtn').html('确认支付');
				postNow = false;
			});
		}
	</script>
	</body>
</html>