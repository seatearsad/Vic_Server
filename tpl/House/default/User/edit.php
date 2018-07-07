<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-user"></i>
				<a href="{pigcms{:U('User/index')}">业主管理</a>
			</li>
			<li class="active">业主信息设置</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" id="edit_form" action="{pigcms{:U('User/edit')}" onsubmit="return chk_submit()">
						<input  name="pigcms_id" type="hidden"  value="{pigcms{$info.pigcms_id}"/>
						<input  name="usernum" type="hidden"  value="{pigcms{$info.usernum}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								
								<div class="form-group">
									<label class="col-sm-1"><label for="usernum">用户编号</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$info.usernum}" type="text" style="border:none;background:white!important;" readonly="readonly">
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="name">业主名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$info.name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">业主联系方式</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value="{pigcms{$info.phone}" />
									<span class="form_tips">多个电话号码以空格分开</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="floor_name">单元房间：</label></label>
                                    <p style="line-height:30px; color:#666">
                                    <volist name='floor_list["list"]' id='floor_info'>
                                    <if condition='$floor_info["floor_id"] eq $info["floor_id"]'>
									{pigcms{$floor_info.floor_layer}&nbsp;&nbsp;-&nbsp;&nbsp;{pigcms{$floor_info.floor_name}
                                    </if>
                                    </volist>
                                    &nbsp;&nbsp;-&nbsp;&nbsp;
                                    
                                    <volist name='vacancy_list' id='vacancy_info'>
                                    <if condition='$vacancy_info["pigcms_id"] eq $info["vacancy_id"]'>
                                    {pigcms{$vacancy_info.layer}&nbsp;&nbsp;-&nbsp;&nbsp;{pigcms{$vacancy_info.room}
                                    </if>
                                    </volist>
                                    </p>
								</div>
								
								<div class="form-group" style="display:none">
									<label class="col-sm-1"><label for="floor_name">单元名称：</label></label>
									<select class="col-sm-2"  name="floor_id" id="floor_id">
										<volist name='floor_list["list"]' id='floor_info'>
											<option value="{pigcms{$floor_info.floor_id}" <if condition='$floor_info["floor_id"] eq $info["floor_id"]'>selected="selected"</if>>{pigcms{$floor_info.floor_name}&nbsp;&nbsp;--&nbsp;&nbsp;{pigcms{$floor_info.floor_layer}</option>
										</volist>
									</select>
								</div>
								
								
								<div class="form-group" style="display:none">
									<label class="col-sm-1"><label for="layer_room">层号房间号：</label></label>
									<select class="col-sm-2" name="layer_room" id="layer_room" >
										<option value="0">请选择</option>
										<volist name='vacancy_list' id='vacancy_info'>
											<option data-layer="{pigcms{$vacancy_info['layer']}" value="{pigcms{$vacancy_info['pigcms_id']}" data-room="{pigcms{$vacancy_info['room']}" <if condition='$vacancy_info["pigcms_id"] eq $info["vacancy_id"]'>selected="selected"</if>>{pigcms{$vacancy_info.layer}&nbsp;&nbsp;--&nbsp;&nbsp;{pigcms{$vacancy_info.room}</option>
										</volist>
									</select>
									<input name="layer_num" id="layer_num" type="hidden" value="{pigcms{$info['layer']}"/>
									<input name="room_num" id="room_num" type="hidden" value="{pigcms{$info['room']}"/>
									<input name="vacancy_id" id="vacancy_id" type="hidden" value="{pigcms{$info['vacancy_id']}" />	
								</div>
								
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="address">住址</label></label>
									<input class="col-sm-2" size="20" name="address" id="address" type="text" value="{pigcms{$info.address}" />
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="water_price">水费总欠费</label></label>
									<input class="col-sm-2" size="10" name="water_price" id="water_price" type="text"  value="{pigcms{$info.water_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="electric_price">电费总欠费</label></label>
									<input class="col-sm-2" size="10" name="electric_price" id="electric_price" type="text"  value="{pigcms{$info.electric_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="gas_price">燃气费总欠费</label></label>
									<input class="col-sm-2" size="10" name="gas_price" id="gas_price" type="text"  value="{pigcms{$info.gas_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="park_price">停车费总欠费</label></label>
									<input class="col-sm-2" size="10" name="park_price" id="park_price" type="text"  value="{pigcms{$info.park_price|floatval=###}"/>
									<span class="form_tips">元 （支持两位小数）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="housesize">房子平方</label></label>
									<input class="col-sm-2" size="10" name="housesize" id="housesize" type="text"  value="{pigcms{$info.housesize}"/>
									<span class="form_tips">米 （支持两位小数）</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1" for="park_flag">停车位</label>
									
									<label style="padding-left:0px;padding-right:20px;"><input type="radio" name="park_flag" value="1" class="ace" <if condition="$info['park_flag'] eq 1">checked="checked"</if>><span class="lbl" style="z-index: 1">开启</span></label>
									<label style="padding-left:0px;"><input type="radio" name="park_flag" value="0" class="ace" <if condition="$info['park_flag'] eq 0">checked="checked"</if>><span class="lbl" style="z-index: 1" >关闭</span></label>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="memo">备注</label></label>
									<textarea class="col-sm-2" size="10" name="memo" id="memo" />{pigcms{$info.memo}</textarea>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="unittype">住宅类型</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$info.floor_type_name}" type="text" style="border:none;background:white!important;" readonly="readonly">
								</div>
								
								<if condition='$info.type gt 0'>
									<div class="form-group">
										<label class="col-sm-1"><label for="unittype">关系</label></label>
										<input class="col-sm-2" size="20" <if condition='$info["type"] eq 1'>value="家人"<elseif condition='$info["type"] eq 2' />value="租客"</if> type="text" style="border:none;background:white!important;" readonly="readonly">
									</div>
								</if>
								
								<if condition='$info["property_month"]'>
									<div class="form-group">
										<label class="col-sm-1"><label for="property_month_num">物业服务时间</label></label>
										<input class="col-sm-2" size="20" value="{pigcms{$info.property_month}" type="text" style="border:none;background:white!important;" readonly="readonly">
									</div>
								</if>
							</div>
						</div>
						<div class="space"></div>
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
<script type="text/javascript" language="javascript">
function chk_submit(){
	/* var ex = /^\d+$/;
	if(!ex.test($('input[name="property_month_num"]').val())){
		alert('物业服务周期必须为整数！');
		return false;
	}
	
	if(!ex.test($('input[name="presented_property_month_num"]').val())){
		alert('赠送物业时间必须为整数！');
		return false;
	} 
	
	if(!confirm('确认进行修改，修改后会微信通知业主。')){
		return false;
	}*/
	
	if(!confirm('确认进行修改?')){
		return false;
	}
}


var url = "{pigcms{:U('ajax_get_layer')}";
$('#floor_id').change(function(){
	var floor_id = $(this).val();
	$.post(url,{'floor_id':floor_id},function(data){
		if(data['status'] == 0){
			alert(data.msg);
		}else{
			var list = data['list'];
			var shtml = '<option>请选择</option>';
			
			if(list){
				for(var i in list){
					shtml += '<option data-layer="'+list[i]['layer']+'" data-room="'+list[i]['room']+'" value="'+list[i]['pigcms_id']+'">'+list[i]['layer']+'&nbsp;&nbsp;--&nbsp;&nbsp;'+list[i]['room']+'</option>'
				}
			}
			$('#layer_room').html(shtml)
		}
	},'json')
});

$('#layer_room').change(function(){
	$('#layer_num').val($(this).find(':selected').data('layer'));
	$('#room_num').val($(this).find(':selected').data('room'));
	$('#vacancy_id').val($(this).find(':selected').val());
})
</script>
<include file="Public:footer"/>