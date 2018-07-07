<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="mainBox">
			<div class="rightMain">
				<div class="grid-view">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="form_add" autocomplete="off">
						<input type="hidden" name="business_type" value="{pigcms{$_GET.business_type}"/>
						<input type="hidden" name="business_id" value="{pigcms{$_GET.business_id}"/>
						<input type="hidden" name="pay_title" value="{pigcms{$pay_title}"/>
						<div class="form-group">
							<label class="col-sm-2"><label for="total_price">订单金额</label></label>
							<input class="col-sm-4" size="10" name="total_price" id="total_price" type="text" value="{pigcms{$pay_money}"/>
							<span class="form_tips">元</span>
						</div>
						<if condition="$config.open_extra_price">
							<div class="form-group">
								<label class="col-sm-2"><label for="user_phone">用户手机号</label></label>
								<input class="col-sm-4" size="10" name="user_phone" id="user_phone" type="text" value="{pigcms{$user_phone}"/>
								<span class="form_tips">该手机号码需要在平台注册,没有则自动注册</span>
							</div>
						</if>
						<div class="form-group">
							<label class="col-sm-2">订单描述</label>
							<textarea class="col-sm-4" rows="3" name="txt_info"></textarea>
							<span class="form_tips">可不填写，仅作用记录值</span>
						</div>
						<div class="form-group">
							<label class="col-sm-2" for="FoodType_week">买单优惠</label>
							<div class="col-sm-4" style="margin-top:5px;padding-left:0px;">
								<if condition="$has_discount">
									<label><input type="checkbox" value="1" name="buy_discount" id="buy_discount" />&nbsp;&nbsp;开启</label>&nbsp;&nbsp;
								<else/>
									店铺没有设置优惠
								</if>
							</div>
							<span class="form_tips">若开启，则会使用“优惠买单”的优惠</span>
						</div>
						<div id="discount_set" style="display:none;">
							<div class="form-group">
								<label class="col-sm-2"><label for="sort">不可优惠金额</label></label>
								<input class="col-sm-4" size="20" name="no_discount" id="no_discount" type="text" value=""/>
								<span class="form_tips">注意：是不给予优惠的部分</span>
							</div>
							<div class="form-group">
								<label class="col-sm-2"><label for="sort">优惠信息</label></label>
								<span class="form_tips" style="margin-left:0px;"><if condition="$discount_type eq 1">{pigcms{$discount_percent}折<elseif condition="$discount_type eq 2" />每满{pigcms{$condition_price}减{pigcms{$minus_price}元</if></span>
							</div>
							<div class="form-group">
								<label class="col-sm-2"><label for="sort">优惠金额</label></label>
								<span class="form_tips" id="discount_money_tip" style="font-size:20px;color:red;margin-left:0px;">---</span>
								<input class="col-sm-4" size="20" name="discount_money" id="discount_money" type="text" value="" style="display:none;"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2"><label for="sort">需付金额</label></label>
							<span class="form_tips" id="pay_money_tip" style="font-size:20px;color:red;margin-left:0px;">---</span>
							<input class="col-sm-4" size="20" name="pay_money" id="pay_money" type="text" value="" style="display:none;"/>
							<input class="col-sm-4" size="20" name="extra_price" id="extra_price" type="text" value="" style="display:none;"/>
						</div>
						<div class="clearfix form-actions">
							<div class="col-md-offset-3 col-md-9">
								<button class="btn btn-info" type="submit" id="submit_btn">
									生成订单
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
<script>
$(function(){
	$('#total_price').focus();

	$("#buy_discount").change(function(){
		if($(this).prop('checked')){
			$('#discount_set').show();
		}else{
			$('#discount_set').hide();
		}
		showPayMoney();
	});
	$('#total_price,#no_discount').keyup(function(){
		showPayMoney();
	});
	if($('#total_price').val() != ''){
		$('#total_price').trigger('keyup');
	}
	$('#form_add').submit(function(){
		var total_price = parseFloat($('#total_price').val());
		if(total_price < 0){
			alert('请输入正确的订单金额');
			$('#total_price').focus();
			return false;
		}
		$('#submit_btn').html('生成中...').prop('disabled',true);
		$.post("{pigcms{:U('store_arrival_add')}",$('#form_add').serialize(),function(result){
			$('#submit_btn').html('生成订单').prop('disabled',false);
			if(result.status == 1){
				if (result.info === 'SUCCESS') {
					window.top.location.reload();
				} else {
    				parent.layer.open({
    				  type: 2,
    				  title: '支付订单',
    				  shadeClose: false,
    				  shade: 0.6,
    				  area: ['820px', '610px'],
    				  content: "{pigcms{:U('store_arrival_order')}&order_id="+result.info
    				});
				}
			}else{
				alert(result.info);
			}
		});
		return false;
	});
});
function showPayMoney(){
	var open_extra_price  = Number('{pigcms{$config.open_extra_price}');
	var extra_price_name  = '{pigcms{$config.extra_price_alias_name}';
	var extra_percent  = Number('{pigcms{$config.user_score_use_percent}');
	var condition_price = '{pigcms{$condition_price}', minus_money = '{pigcms{$minus_price}', discount_percent = '{pigcms{$discount_percent}', discount_type = '{pigcms{$discount_type}';
	var total_money = parseFloat($('#total_price').val());
	if(isNaN(total_money)){
		total_money = 0;
	}
	var no_discount = $('#no_discount').val() != '' ? parseFloat($('#no_discount').val()) : 0;
	var no_discount_money = $("#buy_discount").prop('checked') == true ? no_discount : total_money;
	if (total_money >= no_discount_money) {
		if (discount_type == 1) {
			price = parseFloat(total_money - no_discount_money) * parseFloat(discount_percent) / 10 + no_discount_money;
			minus_price = parseFloat(total_money - no_discount_money) * (100 - discount_percent * 10) / 100;	
		} else if (discount_type == 2) {
			minus_price = Math.floor(parseFloat(total_money - no_discount_money) / parseFloat(condition_price)) * minus_money;
			price = total_money - minus_price;
		} else {
			minus_price = 0;
			price = total_money;
		}
		if(open_extra_price==1){							
			$('#extra_price').val((minus_price*extra_percent).toFixed(2)+extra_price_name);
			$('#pay_money_tip').html(price+'+'+(minus_price*extra_percent).toFixed(2)+extra_price_name);
			$('#discount_money_tip').html('-$' + minus_price);
			$('#discount_money').val(minus_price);
			$('#pay_money').val(price);
		}else{
			
			price = price.toFixed(2);
			minus_price = minus_price.toFixed(2);
			$('#discount_money_tip').html('-$' + minus_price);
			$('#discount_money').val(minus_price);
			$('#pay_money_tip').html('$'+price);
			$('#pay_money').val(price);
		}
	} else {
		$('#no_discount').val(total_money);
		showPayMoney();
	}
}
</script>
</html>