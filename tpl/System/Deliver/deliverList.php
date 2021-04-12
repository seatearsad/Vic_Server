<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/deliverList')}" class="on">{pigcms{:L('_BACK_DELIVERY_LIST_')}</a>|
                    <a href="{pigcms{:U('Deliver/prep_mode')}">{pigcms{:L('D_F_PREP_MODE')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<div class="deliver_search">
							<!-- <span>
								店铺名称：
									<select id="store" name="store">
										<option value="0">全部</option>
										<volist name="stores" id="store">
											<option <if condition="$selectStoreId eq $store['store_id']">selected</if> value="{pigcms{$store['store_id']}">{pigcms{$store['name']}</option>
										</volist>
									</select>
							</span> -->
							<span class="mar_l_10">
								{pigcms{:L('_BACK_DELIVERY_STATUS_')}：<select id="status" name="deliver">
										<option value="0" <if condition="$status eq 0">selected</if> >{pigcms{:L('_BACK_ALL_')}</option>
										<option value="1" <if condition="$status eq 1">selected</if> >{pigcms{:L('_BACK_AWAIT_')}</option>
										<option value="2" <if condition="$status eq 2">selected</if> >{pigcms{:L('_BACK_CONFIRMED_')}</option>
										<option value="3" <if condition="$status eq 3">selected</if> >{pigcms{:L('_BACK_PICKED_')}</option>
										<option value="4" <if condition="$status eq 4">selected</if> >{pigcms{:L('_BACK_IN_TRANSIT_')}</option>
										<option value="5" <if condition="$status eq 5">selected</if> >{pigcms{:L('_BACK_COMPLETED_')}</option>
									</select>
							</span>
							<span class="mar_l_10">{pigcms{:L('_BACK_USER_PHONE_')}：<input type="text" id="phone" name="phone" <if condition="$phone">value="{pigcms{$phone}"></if></span>
							<span>{pigcms{:L('_START_TIME_')}：</span>
							<div style="display:inline-block;">
								<select class='custom-date' id="time_value" name='select'>
									<option value='1' <if condition="$day eq 1">selected</if>>{pigcms{:L('_BACK_TODAY_')}</option>
									<option value='7' <if condition="$day eq 7">selected</if>>7 {pigcms{:L('_BACK_DAYS_')}</option>
									<option value='30' <if condition="$day eq 30">selected</if>>30 {pigcms{:L('_BACK_DAYS_')}</option>
									<option value='180' <if condition="$day eq 180">selected</if>>180 {pigcms{:L('_BACK_DAYS_')}</option>
									<option value='365' <if condition="$day eq 365">selected</if>>365 {pigcms{:L('_BACK_DAYS_')}</option>
									<option value='custom' <if condition="$period">selected</if>>{pigcms{:L('_BACK_CUSTOMIZE_')}</option>
								</select>
							</div>
							<span class="mar_l_10"><button id="search" class="btn btn-success">{pigcms{:L('_BACK_SEARCH_')}</button></span>
							<!--a href="{pigcms{:U('Deliver/export', array('status' => $status, 'day' => $day, 'phone'=> $phone, 'period' => $period))}" class="button" style="float:right;margin-right: 10px;">导出订单</a-->
                            <a href="javascript:void(0);" class="button" style="float:right;margin-right: 10px;" onclick="window.top.artiframe('{pigcms{:U('Deliver/export')}','Download Courier Statistics',920,520,true,false,false,false,'detail',true);">
                                Download Payment Summary
                            </a>
						</div>
                        <if condition="$system_session['level'] neq 3">
                        City:
                        <select name="searchtype" id="city_select">
                            <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>
                            <volist name="city" id="vo">
                                <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                            </volist>
                        </select>
                        </if>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
			
						<thead>
							<tr>
								<th>OrderID</th>
								<th>{pigcms{:L('_BACK_FROM_')}</th>
								<!--th>配送员类型</th-->
								<th>{pigcms{:L('_BACK_STORE_NAME_')}</th>
								<th>{pigcms{:L('_BACK_USER_NAME_')}</th>
								<th>{pigcms{:L('_BACK_USER_PHONE_')}</th>
								<th>{pigcms{:L('_BACK_CUSTOM_ADD_')}</th>
								<!--th>支付方式</th-->
								<th>{pigcms{:L('_BACK_PAYMENT_STATUS_')}</th>
								<th>{pigcms{:L('_BACK_ORDER_TOTAL_')}</th>
								<th>{pigcms{:L('_BACK_CASH_RECE_')}</th>
								<th>{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>
								<th>{pigcms{:L('_BACK_COURIER_NICK_')}</th>
								<th>{pigcms{:L('_BACK_COURIER_PHONE_')}</th>
								<th>{pigcms{:L('_BACK_START_AT_')}</th>
								<th>{pigcms{:L('_BACK_FINISH_AT_')}</th>
								<th>{pigcms{:L('_BACK_ASS_COURIER_')}</th>
								<th>{pigcms{:L('B_ACTION')}</th>
								<!--th>创建时间</th-->
								
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($supply_info)">
								<volist name="supply_info"  id="vo">
									<tr class="<if condition='$i%2 eq 0'>odd<else/>even</if> order_line" data-id="{pigcms{$vo.order_id}">
										<td width="30">{pigcms{$vo.order_id}</td>
										<td width="40"><if condition="$vo['item'] eq 0">{pigcms{:L('_BACK_DINE_')}<elseif condition="$vo['item'] eq 1" />外送系统<elseif condition="$vo['item'] eq 2" />{pigcms{:L('_BACK_DELIVERY_')}</if></td>
										<!--td width="50">{pigcms{$vo.group}</td-->
										<td width="80">{pigcms{$vo.storename}</td>
										<td width="30">{pigcms{$vo.username}</td>
										<td width="50">{pigcms{$vo.userphone}</td>
										<td width="150">{pigcms{$vo.aim_site}</td>
										<!--td width="50">{pigcms{$vo.pay_type}</td-->
										<td width="50">{pigcms{$vo.paid}</td>
										<td width="30">{pigcms{$vo.money|floatval}</td>
										<td width="30">{pigcms{$vo.deliver_cash|floatval}</td>
										<td width="50">{pigcms{$vo.order_status}</td>
										<td width="50">{pigcms{$vo.name}</td>
										<td width="80">{pigcms{$vo.phone}</td>
										<td width="80">{pigcms{$vo.start_time}</td>
										<td width="80">{pigcms{$vo.end_time}</td>
										
										<td width="80">
										<if condition="$vo['status'] eq 0">
										<font color="red">{pigcms{:L('_BACK_ORDER_FILED_')}</font>
										<elseif condition="$vo['status'] eq 1" />
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/appoint_deliver',array('supply_id' => $vo['supply_id']))}','Courier Assignment({pigcms{$vo['distance']})',480,380,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_ASS_DIST_')}</a>
										<elseif condition="$vo['status'] lt 5" />
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/appoint_deliver',array('supply_id' => $vo['supply_id']))}','{pigcms{:L(\'_BACK_CHANGE_COURIER_\')}({pigcms{$vo['distance']})',480,380,true,false,false,editbtn,'edit',true);" style="color:red">{pigcms{:L('_BACK_CHANGE_COURIER_')}</a>
										<else />
										<font color="green">{pigcms{:L('_BACK_DELIVERED_')}</font>
										</if>
										</td>
										<td width="80">
										<if condition="$vo['status'] eq 0 OR $vo['status'] eq 5 OR $vo['status'] eq 1">
										---
										<else />
										<a href="javascript:void(0);" style="color:green" data-supply="{pigcms{$vo['supply_id']}" class="change">{pigcms{:L('_BACK_SWITCH_COM_')}</a>
										</if>
										</td>
										<!--td width="50">{pigcms{$vo.create_time}</td-->
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="16">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="16">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<div class="order_status_show">
    fff
</div>
<script>
    var city_id = $('#city_select').val();
    $('#city_select').change(function () {
        city_id = $(this).val();
        window.location.href = "{pigcms{:U('Deliver/deliverList')}" + "&city_id="+city_id;
    });

	var selectStoreId = {pigcms{:$selectStoreId? $selectStoreId: 0};
	var selectUserId = {pigcms{:$selectUserId? $selectUserId: 0};
	$(function(){
		$("#store").change(function(){
			selectStoreId = $("#store").val();
			selectUserId = 0;
			search();
		});
		$("#deliver").change(function(){
			selectStoreId = 0;
			selectUserId = $("#deliver").val();
			search();
		});
		$("#order_number").focus(function(){
			$("#phone").val("");
		});
		$("#phone").focus(function(){
			$("#order_number").val("");
		});
		$("#search").click(function(){
			search();
		});
		function search(orderNum, phone) {
			var phone = $("#phone").val(), status = $('#status').val();
			var day = '', period = '';
			if($('#time_value option:selected').attr('value')=='custom'){
				period = $('#time_value option:selected').html();
			}else{
				day = $('#time_value option:selected').attr('value');
			}
			location.href = "{pigcms{:U('Merchant/Deliver/deliverList')}"+"&period="+period+"&phone="+phone+"&day="+day+"&status="+status;
		}
		$('.change').click(function(){
			var supply_id = $(this).attr('data-supply'), obj = $(this);
			window.top.art.dialog({
				lock: true,
                title:'Reminder',
				content: "{pigcms{:L('_BACK_SURE_CHANGE_')}",
                okVal:'Yes',
				ok: function(){
					$.get("{pigcms{:U('Deliver/change')}", {supply_id:supply_id}, function(response){
						if (response.error_code) {
							window.top.msg(0, response.msg);
						} else {
							window.top.msg(1, response.msg,true);
							obj.remove();
						}
					}, 'json');
				},
                cancelVal:'Cancel',
				cancel: true
			});
		});
	});

    var hover_id = 0;
    var time_out;
    $('.order_line').mouseover(function () {
        if(typeof (time_out) != "undefined") clearTimeout(time_out);
        
        var curr_id = $(this).data('id');
        if(hover_id != curr_id){
            hover_id = curr_id;
            var this_y = $(this).position().top+40;
            time_out = setTimeout(function () {
                $.post("{pigcms{:U('Shop/get_order_status')}",{"order_id":hover_id},function(result){
                    if(result.error == 0){
                        var html = '';
                        for(var i=0;i<result['list'].length;++i){
                            html += result['list'][i];
                        }
                        if(html != '') {
                            $('.order_status_show').html(html);
                            $('.order_status_show').css('top', this_y);
                            $('.order_status_show').show();
                        }
                    }
                },'JSON');
            },1000);
        }
    });

    $('.order_line').mouseout(function () {
        hover_id = 0;
        $('.order_status_show').hide();
    });

    function getMouse(e) {
        e = e  || window.event;
    }
</script>
<style>
.drp-popup{top:90px !important}
.deliver_search input{height: 20px;}
.deliver_search select{height: 20px;}
.deliver_search .mar_l_10{margin-left: 10px;}
.deliver_search .btn{height: 23px;line-height: 16px; padding: 0px 12px;}
.order_status_show{
    position: absolute;
    width: 300px;
    height: 180px;
    background-color: #EEEEEE;
    left: 20px;
    display: none;
    padding: 10px;
    line-height: 1.8;
}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/date-picker/index.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/date-picker/index.css" />
<include file="Public:footer"/>