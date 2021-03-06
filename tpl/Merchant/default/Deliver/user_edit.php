<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-desktop"></i>
				<a href="{pigcms{:U('Deliver/user')}">配送管理</a>
			</li>
			<li class="active">添加配送员</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">基本设置</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<input type="hidden" name="uid" value="{pigcms{$now_user['uid']}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">姓名</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$now_user['name']}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">联系电话</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value="{pigcms{$now_user['phone']}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="qq">密码</label></label>
									<input class="col-sm-2" size="20" name="pwd" id="pwd" type="text" value=""/>
									<span class="form_tips">不填写就不做修改。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="qq">配送范围</label></label>
									<input class="col-sm-2" size="20" name="range" id="range" type="text" value="{pigcms{$now_user['range']}"/>
									<span class="form_tips">（公里）</span>
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="long_lat">店铺经纬度</label></label>
									<input class="col-sm-2" size="10" name="long_lat" id="long_lat" type="text" readonly="readonly" value="{pigcms{$now_user['lng']},{pigcms{$now_user['lat']}"/>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" id="show_map_frame" data-toggle="modal">点击选取经纬度</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>配送员常驻地</label></label>
									<fieldset id="choose_cityarea" province_id="{pigcms{$now_user.province_id}" city_id="{pigcms{$now_user.city_id}" area_id="{pigcms{$now_user.area_id}" circle_id="{pigcms{$now_user.circle_id}"></fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="adress">地址</label></label>
									<input class="col-sm-2" size="20" name="adress" id="adress" type="text" value="{pigcms{$now_user['site']}"/>
									<span class="form_tips">地址不能带有上面所在地选择的省/区/商圈信息。</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1" for="have_meal">状态</label>
									<select name="status" id="status">
										<option value="1" <if condition='$now_user["status"] eq 1 || !$now_user["status"]'>selected="selected"</if> >正常</option>
										<option value="0" <if condition='$now_user["status"] eq 0'>selected="selected"</if>>禁止</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="have_meal">选择店铺</label>
									<select name="store_id" id="store_id">
										<volist name="waimai_store" id='waimai'>
										<option value="{pigcms{$waimai.store_id}" <if condition='$now_user["store_id"] eq $waimai["store_id"] || !$now_user["store_id"]'>selected="selected" </if> >{pigcms{$waimai.name}</option>
										</volist>
									</select>
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
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#edit_form').submit(function(){
		$('#edit_form button[type="submit"]').prop('disabled',true).html('Save....');
		$.post("{pigcms{:U('Deliver/user_edit')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('Deliver/user')}";
			}else{
				$('#edit_form button[type="submit"]').prop('disabled',false).html('<i class="ace-icon fa fa-check bigger-110"></i>保存');
				alert(result.info);
			}
		})
		return false;
	}); 
</script>
<include file="Public:footer"/>
