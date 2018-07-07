<include file="Public:header"/>
<div class="main-content">
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active">编辑店铺信息</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#category">选择分类</a>
							</li>
							<!--li>
								<a data-toggle="tab" href="#promotion">店铺折扣</a>
							</li>
							<li>
								<a data-toggle="tab" href="#stock">库存类型选择</a>
							</li-->
						  	<if condition="!empty($levelarr) AND false">
							<li>
								<a data-toggle="tab" href="#levelcoupon">会员优惠</a>
							</li>
							</if>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">				
							<div id="basicinfo" class="tab-pane active">
								<if condition="empty($store_shop)">
								<div class="alert alert-info" style="margin:10px;">
								<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>同步数据只能在完善店铺信息的时候同步，以后修改店铺时不允许同步
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>同步原餐饮的商品</label></label>
									<label><input name="sysnc" value="0" type="radio">&nbsp;&nbsp;不同步</label>&nbsp;&nbsp;&nbsp;
									<label><input name="sysnc" checked="checked" value="1" type="radio" >&nbsp;&nbsp;同步</label>&nbsp;&nbsp;&nbsp;
								</div>
								</if>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="Config_notice">店铺公告</label></label>
									<textarea class="col-sm-3" rows="4" name="store_notice" id="Config_notice">{pigcms{$store_shop.store_notice}</textarea>
								</div-->
								
								<!--div class="form-group">
									<label class="col-sm-1"><label>开发票</label></label>
									<label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_invoice" <if condition="$store_shop['is_invoice'] eq 1 ">checked="checked"</if> value="1" type="radio">&nbsp;&nbsp;支持</label>&nbsp;&nbsp;&nbsp;
								</div>
								
								<div class="form-group invoice" <if condition="$store_shop['is_invoice'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1">满足</label>
									<input class="col-sm-1" size="10" maxlength="10" name="invoice_price" id="Config_invoice_price" type="text" value="{pigcms{$store_shop.invoice_price|floatval}" />
									<label class="col-sm-1">元，可开发票</label>
								</div-->
								
								<div class="form-group">
									<label class="col-sm-1"><label>预订</label></label>
									<label><input name="is_book" <if condition="$store_shop['is_book'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_book" <if condition="$store_shop['is_book'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1"><label>预订时长</label></label>
									<div>
										<input id="book_day" type="text" value="{pigcms{$store_shop.book_day|default=1}" name="book_day"/>
										<span class="form_tips red">可提前预订多少天后的桌台</span>
									</div>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1"><label>预订时间</label></label>
									<div>
										<input id="book_start" type="text" value="{pigcms{$store_shop.book_start|default='00:00'}" name="book_start" readonly/>	至
										<input id="book_stop" type="text" value="{pigcms{$store_shop.book_stop|default='23:59'}" name="book_stop" readonly/>
										<span class="form_tips red">如果两个都不填写的话，表示从零点开始，按预订间隔时长进行全天预订</span>
									</div>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1">预订间隔时长</label>
									<input class="col-sm-1" name="book_time" type="text" value="{pigcms{$store_shop.book_time|default=60}" />
									<span class="form_tips red">两个可预订时间之间相隔的时长，单位（分钟）</span>
								</div>
								<div class="form-group book" <if condition="$store_shop['is_book'] eq 0 ">style="display:none"</if>>
									<label class="col-sm-1">提前取消时长</label>
									<input class="col-sm-1" name="cancel_time" type="text" value="{pigcms{$store_shop.cancel_time|default=60}" />
									<span class="form_tips red">至少要提前多久才能取消，否则不能取消，单位（分钟）</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>排号</label></label>
									<label><input name="is_queue" <if condition="$store_shop['is_queue'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_queue" <if condition="$store_shop['is_queue'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>外送</label></label>
									<label><input name="is_takeout" <if condition="$store_shop['is_takeout'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;不支持</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_takeout" <if condition="$store_shop['is_takeout'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;支持</label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label>停车位</label></label>
									<label><input name="is_park" <if condition="$store_shop['is_park'] eq 0 ">checked="checked"</if> value="0" type="radio">&nbsp;&nbsp;没有</label>&nbsp;&nbsp;&nbsp;
									<label><input name="is_park" <if condition="$store_shop['is_park'] eq 1 ">checked="checked"</if> value="1" type="radio" >&nbsp;&nbsp;有</label>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1">人均消费</label>
									<input class="col-sm-1" size="10" maxlength="10" name="mean_money" id="Config_mean_money" type="text" value="{pigcms{$store_shop.mean_money|floatval}" />
									<span class="form_tips">元</span>
								</div-->
							</div>
							<div id="category" class="tab-pane">
								<volist name="category_list" id="vo">
									<div class="form-group">
										<div class="radio">
											<label>
												<span class="lbl"><label style="color: red">{pigcms{$vo.cat_name}：</label></span>
											</label>
											<volist name="vo['son_list']" id="child">
												<label>
													<input class="cat_class" type="checkbox" name="store_category[]" value="{pigcms{$vo.cat_id}-{pigcms{$child.cat_id}" id="Config_store_category_{pigcms{$child.cat_id}" <if condition="in_array($child['cat_id'],$relation_array)">checked="checked"</if>/>
													<span class="lbl"><label for="Config_store_category_{pigcms{$child.cat_id}">{pigcms{$child.cat_name}</label></span>
												</label>
											</volist>
										</div>
									</div>
								</volist>
							</div>
							<div id="label" class="tab-pane">
								<volist name="label_list" id="vo">
									<div class="form-group">
										<div class="radio">
											<label>
												<input class="cat_class" type="checkbox" name="store_labels[]" value="{pigcms{$vo.id}" id="Config_store_label_{pigcms{$vo.id}" <if condition="in_array($vo['id'], $store_shop['store_labels'])">checked="checked"</if>/>
												<span class="lbl"><label for="Config_store_label_{pigcms{$vo.id}">{pigcms{$vo.name}</label></span>
											</label>
										</div>
									</div>
								</volist>
							</div>
							<div id="promotion" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">店铺折扣</label>
									<input class="col-sm-1" size="10" maxlength="10" name="store_discount" id="Config_mean_full_money" type="text" value="{pigcms{$store_shop.store_discount}" /><strong style="color:red">请填写0~100之间的整数，0和100都是表示无折扣，98表示9.8折</strong>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>优惠方式</label></label>
									<span><label><input id='discount_type0' name="discount_type" <if condition="$store_shop['discount_type'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>折上折</span>&nbsp;</span>
									<span><label><input id='discount_type1' name="discount_type" <if condition="$store_shop['discount_type'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>折扣最优</span></span>
									<strong style="color:red">折上折的意思是如果这个用户是有平台VIP等级，平台VIP等级有折扣优惠。那么这个用户的优惠计算方式是先用店铺的优惠进行打折后，再用VIP折扣进去打折；<br/>
									折扣最优是指：购买产品的总价用店铺优惠打折后的价格与总价跟VIP优惠打折后的价格进行比较，取最小值的优惠方式。
									</strong>
								</div>
								<div style="clear:both;"></div>
							</div>
							
							<div id="stock" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">库存类型：</label>
									<label><input type="radio" name="stock_type" value="0" <if condition="$store_shop['stock_type'] eq 0">checked="checked"</if>>&nbsp;&nbsp;每天自动更新固定量的库存</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="stock_type" value="1" <if condition="$store_shop['stock_type'] eq 1">checked="checked"</if>>&nbsp;&nbsp;固定的库存，不会每天自动更新</label>&nbsp;&nbsp;&nbsp;
								</div>
								<div style="clear:both;"></div>
							</div>

							<if condition="!empty($levelarr)">
							<div id="levelcoupon" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1" style="color:red;width:95%;">说明：必须设置一个会员等级优惠类型和优惠类型对应的数值，我们将结合优惠类型和所填的数值来计算该商品会员等级的优惠的幅度！</label>
								</div>
							    <volist name="levelarr" id="vv">
								  <div class="form-group">
								    <input  name="leveloff[{pigcms{$vv['level']}][lid]" type="hidden" value="{pigcms{$vv['id']}"/>
								    <input  name="leveloff[{pigcms{$vv['level']}][lname]" type="hidden" value="{pigcms{$vv['lname']}"/>
									<label class="col-sm-1">{pigcms{$vv['lname']}：</label>
									优惠类型：&nbsp;
									<select name="leveloff[{pigcms{$vv['level']}][type]">
										<option value="0">无优惠</option>
										<option value="1" <if condition="$vv['type'] eq 1">selected="selected"</if>>百分比（%）</option>
										<!--<option value="2">立减</option>-->
									</select>
									<input name="leveloff[{pigcms{$vv['level']}][vv]" type="text" value="{pigcms{$vv['vv']}" placeholder="请填写一个优惠值数字" onkeyup="value=value.replace(/[^1234567890]+/g,'')"/>
								</div>
								</volist>
							</div>
							</if>
							
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
function check(obj){
	var length = $('.paycheck:checked').length;
	if(length == 0){
		$(obj).attr('checked','checked');
		bootbox.alert('最少要选择一种支付方式');
	}			
}
$(function($){
	$('input[name=is_book]').click(function(){
		if ($(this).val() == 1) {
			$('.book').css('display', 'block');
		} else {
			$('.book').css('display', 'none');
		}
	});
	$('input[name=is_invoice]').click(function(){
		if ($(this).val() == 1) {
			$('.invoice').css('display', 'block');
		} else {
			$('.invoice').css('display', 'none');
		}
	});
	
	$('#book_start').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'00','minute':'00'}));
	$('#book_stop').timepicker($.extend($.datepicker.regional['zh-cn'], {'timeFormat':'hh:mm:ss','hour':'23','minute':'59'}));

	var is_submit = false;
	$('#edit_form').submit(function(){
		if (is_submit) return false;
		is_submit = true;
		$.post("{pigcms{:U('Foodshop/shop_edit',array('store_id'=>$_GET['store_id']))}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Foodshop/index')}";
			}else{
				is_submit = false;
				alert(result.info);
			}
		})
		return false;
	});
});
</script>
<include file="Public:footer"/>
