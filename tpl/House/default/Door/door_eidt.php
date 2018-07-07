<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('door_list')}">社区论坛</a>
			</li>
			<li class="active">修改设备</li>
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
					<form  class="form-horizontal" method="post" id="edit_form" action="{pigcms{:U('door_eidt',array('door_id'=>$aDoor['door_id']))}" enctype="multipart/form-data" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="door_device_id">设备ID</label></label>
									<input class="col-xs-4 col-sm-4 col-md-2" size="20" name="door_device_id" id="door_device_id" type="text" value="{pigcms{$aDoor.door_device_id}"/>
								</div>
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="door_name">设备名</label></label>
									<input class="col-xs-4 col-sm-4 col-md-2" size="20" name="door_name" id="door_name" type="text" value="{pigcms{$aDoor.door_name}"/>
								</div>
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="aFloor">楼名</label></label>
									<select class="col-xs-4 col-sm-4 col-md-2" name="floor_id" id="floor_id">
										<option value="-1" <if condition="$aDoor['floor_id'] eq -1">selected="selected"</if>>小区大门</option>
										<if condition="$aFloor">
											<volist name="aFloor" id="vo">
												<option value="{pigcms{$vo.floor_id}">{pigcms{$vo['floor_name']}{pigcms{$vo['floor_layer']}</option>
											</volist>
										</if>
									</select>
								</div>
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="door_status">状态</label></label>
									<label><input name="door_status" type="radio" value="1" <if condition="$aDoor.door_status eq 1">checked</if> />&nbsp;&nbsp;开启</label>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<label><input name="door_status" type="radio" value="0" <if condition="$aDoor.door_status eq 0">checked</if> />&nbsp;&nbsp;关闭</label>
								</div>
								<div class="form-group">
									<label class="col-xs-2 col-sm-2 col-md-1 col-lg-1"><label for="all_status">权限</label></label>
									<label><input name="all_status" type="radio" value="1" <if condition="$aDoor.all_status eq 1">checked</if> />&nbsp;&nbsp;全部权限</label>
									&nbsp;&nbsp;&nbsp;
									<label><input name="all_status" type="radio" value="2" <if condition="$aDoor.all_status eq 2">checked</if> />&nbsp;&nbsp;单个权限</label>
								</div>
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
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
.form-group>label{font-size:12px;line-height:24px;}
</style>
<script>
function check_submit(){
	if($('#door_device_id').val() == ''){
		alert('设备ID不能为空');
		return false;
	}else if($('#door_name').val() == ''){
		alert('设备名不能为空');
		return false;
	}else if($('#floor_id').val() == ''){
		alert('楼名不能为空');
		return false;
	}
	window.location.href = "{pigcms{:U('door_eidt')}";
}
</script>
<include file="Public:footer"/>