<include file="Public:header"/>
<div class="main-content">
<!-- 内容头部 -->
<div class="breadcrumbs" id="breadcrumbs">
	<ul class="breadcrumb">
		<li>
			<i class="ace-icon fa fa-tablet"></i>
			<a href="{pigcms{:U('owner_arrival')}">功能库列表</a>
		</li>
		<li class="active">在线付款</li>
	</ul>
</div>
<!-- 内容头部 -->
<style type="text/css">
.form_list{width:45%; float:left}
.form_list select{margin-right:10px;height:42px;}
</style>

	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
						<form method="post" id="find-form" onSubmit="return check_user_submit()" class="form_list">
							<select name="find_type" id="find_type" class="col-sm-2">
								<option value="1">房主姓名</option>
								<option value="2">手机号码</option>
								<option value="3">物业编号</option>
							</select>
							<input class="col-sm-4" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;"/>

							<input class="btn btn-success" type="submit" id="find_submit" value="查找业主" />
							<a class="btn btn-success" onclick="location.href='{pigcms{:U('owner_arrival_add')}'">重置</a>
						</form>
						
						
						<form method="post" class="form_list">
							<div id="choose_cityarea">
							</div>
							
							<input class="btn btn-success" type="button" id="search_find" value="查找业主" />
						</form>
						<div class="clearfix"></div>
					</div>
					<div class="form-group user_list" style="border:1px solid #c5d0dc;padding:10px; display:none">
						<span>物业信息:</span>
						<p class="user_list_content">
						</p>
					</div>
				
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="property_id">物业缴费周期</label></label>
									<label class="col-sm-1">
										<select name="property_id" id="property">
											<option value="0">请选择</option>
											<volist name='list["list"]' id='row'>
												<option value='{pigcms{$row["id"]}' data-id="{pigcms{$row['id']}">{pigcms{$row.property_month_num}个月</option>
											</volist>
										</select>
									</label>
									<label class="col-sm-2 property_desc red"></label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="usernum">业主物业编号</label></label>
									<label class="col-sm-1">
										<input type="text" placeholder="请输入业主物业编号" name="usernum" id="usernum" />
									</label>
								</div>
							</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa bigger-110"></i>
										生成订单
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$('#search_find').live('click',function(){
	var owner_id = $('select[name="owner_id"]').val()
	var search_url = "{pigcms{:U('search_owner_info')}&owner_id="+owner_id;
	art.dialog.open(search_url,{
		init: function(){
			var iframe = this.iframe.contentWindow;
			window.top.art.dialog.data('iframe_handle',iframe);
		},
		id: 'handle',
		title:'业主信息',
		padding: 0,
		width: 720,
		height: 400,
		lock: true,
		resize: false,
		background:'black',
		button: null,
		fixed: false,
		close: null,
		left: '50%',
		top: '38.2%',
		opacity:'0.4'
	});
	return false;
});
	function check_submit(){
		
		var property = $("#property").val();
		if(property<=0){
			alert('请选择物业缴费周期');
			return false;
		}
		
		var usernum = $("#usernum").val();
		if(usernum == ''){
			alert('请填写业主物业编号');
			return false;
		}
		
		if(confirm('确认生成订单？')){
			return true;
		}else{
			return false;
		}
	}
	
	function check_user_submit(){
		var ajax_user_list_url = "{pigcms{:U('ajax_user_list')}";
		$.post(ajax_user_list_url,$('#find-form').serialize(),function(data){
			var shtml = '';
			if(data.status){
				$('.user_list').show();
				for(var i in data['user_list']){
					shtml += '<span class="red">编号：' + data['user_list'][i]['usernum'] + '&nbsp;&nbsp;|&nbsp;&nbsp;业主姓名：'+data['user_list'][i]['name']+'&nbsp;&nbsp;|&nbsp;&nbsp;地址：' + data['user_list'][i]['address'] + '</span><br />';
				}
				$('.user_list_content').html(shtml);
			}else{
				shtml +='暂无';
				$('.user_list_content').html(shtml);
			}
		},'json')
		return false;
	}
	
	


var choose_province="{pigcms{:U('ajax_unit')}",choose_floor="{pigcms{:U('ajax_floor')}",choose_layer="{pigcms{:U('ajax_layer')}",choose_owner="{pigcms{:U('ajax_owner')}";

if(document.getElementById('choose_cityarea')){
	show_unit();
}	

function show_unit(){
	$.post(choose_province,function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select class="col-sm-2" id="choose_province" name="unit_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.floor_id+'" '+(item.floor_id==$('#choose_cityarea').attr('province_id') ? 'selected="selected"' : '')+'>'+item.floor_name+'</option>';
			});
			area_dom+= '</select>';
			$('#choose_cityarea').prepend(area_dom);
			show_city($('#choose_province').find('option:selected').attr('value'),$('#choose_province').find('option:selected').html(),1);
			$('#choose_province').change(function(){
				show_city($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
		}else if(result.error == 2){
			var area_dom = '<select class="col-sm-2" id="choose_province_hide" name="unit_id" style="display:none;">';
			area_dom += '<option value="'+result.floor_id+'">'+result.floor_name+'</option>';
			area_dom += '</select>';
			$('#choose_cityarea').prepend(area_dom);
			show_city(result.id,result.name,0);
		}else{
			$('input[name="usernum"]').val('');
			//alert(result.info);
			location.href="{pigcms{:U('owner_arrival')}";
		}
	});
}

function show_city(id,name,type){
	$.post(choose_floor,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select class="col-sm-2" id="choose_city" name="floor_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.floor_id+'" '+(item.id==$('#choose_cityarea').attr('city_id') ? 'selected="selected"' : '')+'>'+item.floor_layer+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_city')){
				$('#choose_city').replaceWith(area_dom);
			}else if(document.getElementById('choose_province')){
				$('#choose_province').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			if($('#choose_cityarea').attr('area_id') != '-1'){
				show_area($('#choose_city').find('option:selected').attr('value'),$('#choose_city').find('option:selected').html(),1);
			
				$('#choose_city').change(function(){
					show_area($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
				});
			}
		}else if(result.error == 2){
			var area_dom = '<select class="col-sm-2" id="choose_city_hide" name="floor_id" style="display:none;">';
			area_dom += '<option value="'+result.floor_id+'">'+result.floor_name+'</option>';
			area_dom += '</select>';
			$('#choose_cityarea').prepend(area_dom);
			if($('#choose_cityarea').attr('area_id') != '-1'){
				show_area(result.id,result.name,0);
			}
		}else{
			$('input[name="usernum"]').val('');
			//alert(result.info);
			location.href="{pigcms{:U('owner_arrival')}";
		}
	});
}

function show_area(id,name,type){
	$.post(choose_layer,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select class="col-sm-3" id="choose_area" name="pigcms_id">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.pigcms_id+'" '+(item.id==$('#choose_cityarea').attr('area_id') ? 'selected="selected"' : '')+'>'+item.address+'</option>';
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_area')){
				$('#choose_area').replaceWith(area_dom);
			}else if(document.getElementById('choose_city')){
				$('#choose_city').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			if($('#choose_cityarea').attr('circle_id') != '-1'){
				show_circle($('#choose_area').find('option:selected').attr('value'),$('#choose_area').find('option:selected').html(),1);
				$('#choose_area').change(function(){
					show_circle($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
				});
			}
		}else{
			$('input[name="usernum"]').val('');
			//alert(result.info);
		}
	});
}

function show_circle(id,name,type){
	$.post(choose_owner,{id:id,name:name,type:type},function(result){
		result = $.parseJSON(result);
		if(result.error == 0){
			var area_dom = '<select id="choose_circle" name="owner_id" class="col-sm-2" style="margin-right:10px;">';
			$.each(result.list,function(i,item){
				area_dom+= '<option value="'+item.pigcms_id+'" '+(item.id==$('#choose_cityarea').attr('circle_id') ? 'selected="selected"' : '')+'>'+item.name+'</option>';
				$('input[name="usernum"]').val(item['usernum']);
			});
			area_dom+= '</select>';
			if(document.getElementById('choose_circle')){
				$('#choose_circle').replaceWith(area_dom);
			}else if(document.getElementById('choose_area')){
				$('#choose_area').after(area_dom);
			}else{
				$('#choose_cityarea').prepend(area_dom);
			}
			
			
			//show_market($('#choose_circle').find('option:selected').attr('value'),$('#choose_circle').find('option:selected').html(),1);
			$('#choose_circle').change(function(){
				//show_market($(this).find('option:selected').attr('value'),$(this).find('option:selected').html(),1);
			});
		}else{
			$('input[name="usernum"]').val('');
			alert(result.info);
		}
	});
}

var ajax_property_info_url = "{pigcms{:U('ajax_property_info')}"
$('select[name="property_id"]').change(function(){
	var property_id = $(this).val();
	$.post(ajax_property_info_url,{'property_id':property_id},function(data){
		if(data.status){
			if(data['detail']['diy_type'] > 0){
				$('.property_desc').html(data['detail']['diy_content']);
			}else{
				if(data['detail']['presented_property_month_num'] > 0){
					$('.property_desc').html('赠送'+data['detail']['presented_property_month_num']+'个月');
				}else{
					$('.property_desc').html('');
				}
			}
		}else{
			$('.property_desc').html('');
		}
	},'json')
	
});
</script>

<include file="Public:footer"/>