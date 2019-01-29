<include file="Public:header"/>
<style>

</style>
	<form id="myform" method="post" action="{pigcms{:U('Deliver/user_view')}" frame="true" refresh="true">
		<input type="hidden" name="uid" value="{pigcms{$now_user.uid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
                <th width="15%">姓</th>
                <td width="35%"><input type="text" class="input fl" name="family_name" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.family_name}"/></td>
                <th width="15%">名</th>
                <td width="35%"><input type="text" class="input fl" name="name" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.name}"/></td>
			</tr>
            <tr>
                <th width="15%">邮箱</th>
                <td width="35%"><input type="text" class="input fl" name="email" size="20" validate="maxlength:50,required:true" value="{pigcms{$now_user.email}" /></td>
                <th width="15%">语言</th>
                <td width="35%">
                    <span class="cb-enable"><label class="cb-enable <if condition="$now_user['language'] eq 1">selected</if>"><span>English</span><input type="radio" name="language" value="1" <if condition="$now_user['language'] eq 1">checked="checked"</if> /></label></span>
                    <span class="cb-disable"><label class="cb-disable <if condition="$now_user['language'] eq 0">selected</if>"><span>中文</span><input type="radio" name="language" value="0" <if condition="$now_user['language'] eq 0">checked="checked"</if> /></label></span>
                </td>
            </tr>
			<tr>
				<th width="15%">密码</th>
				<td width="35%"><input type="text" class="input fl" name="pwd" size="20" value="" tips="不修改则不填写" /></td>
				<th width="15%">状态</th>
				<td width="35%" class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_user['status'] eq 1">selected</if>"><span>正常</span><input type="radio" name="status" value="1"  <if condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_user['status'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="status" value="0"  <if condition="$now_user['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
			 	<th width="15%">所在地</th>
				<td id="choose_cityarea" colspan=3  province_id="{pigcms{$now_user.province_id}" city_id="{pigcms{$now_user.city_id}" area_id="{pigcms{$now_user.area_id}" circle_id="{pigcms{$now_user.circle_id}"></td>
			<tr>
			<tr>
				<th width="15%">配送范围</th>
				<td width="35%"><input type="text" class="input fl" name="range" size="20" validate="required:true" value="{pigcms{$now_user.range}"/>公里</td>
                <th width="15%">手机号</th>
                <td width="35%"><input type="text" class="input fl" name="phone" size="20" validate="number:true,required:true" value="{pigcms{$now_user.phone}"/></td>
			<tr>
			</tr>
			<!--tr>
				<th width="15%">常驻地区</th>
				<td width="35%"><input type="text" class="input fl" readonly="readonly" name="adress" id="adress" validate="required:true" value="{pigcms{$now_user.site}"/></td>
				<th width="15%">配送员经纬度</th>
				<td width="35%" class="radio_box"><input class="input fl" size="20" name="long_lat" id="long_lat" type="text" readonly="readonly" validate="required:true" value="{pigcms{$now_user.lng},{pigcms{$now_user.lat}"/></td>
			</tr>
            <tr>
                <th width="15%">银行卡</th>
                <td colspan=3>
                    <input type="text" placeholder="Account Holder Name" class="input fl" name="ahname" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.ahname}" />
                </td>
            <tr>
            <tr>
                <th width="15%"></th>
                <td colspan=3>
                    <input type="text" placeholder="Transit(Branch)" class="input fl" name="transit" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.transit}" />
                </td>
            <tr>
            <tr>
                <th width="15%"></th>
                <td colspan=3>
                    <input type="text" placeholder="Institution" class="input fl" name="institution" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.institution}" />
                </td>
            <tr>
            <tr>
                <th width="15%"></th>
                <td colspan=3>
                    <input type="text" placeholder="Account" class="input fl" name="account" size="30" validate="maxlength:50,required:true" value="{pigcms{$card.account}" />
                </td>
            <tr-->
            <tr>
                <th width="15%">驾照</th>
                <td colspan=3>
                    <img src="{pigcms{:C('config.site_url')}{pigcms{$img['driver_license']}" height="100"/>
                </td>
            <tr>
            <tr>
                <th width="15%">车辆保险</th>
                <td colspan=3>
                    <img src="{pigcms{:C('config.site_url')}{pigcms{$img['insurance']}" height="100"/>
                </td>
            <tr>
            <tr>
                <th width="15%">车辆登记证</th>
                <td colspan=3>
                    <img src="{pigcms{:C('config.site_url')}{pigcms{$img['certificate']}" height="100"/>
                </td>
            <tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<!--div id="modal-table" class="modal fade" tabindex="-1" style="display:block;">
		<div class="modal-dialog" style="width:80%;">
			<div class="modal-content" style="width:100%;">
				<div class="modal-header no-padding" style="width:100%;">
					<div class="table-header">
						   拖动红色图标，经纬度框内将自动填充经纬度。
					</div>
				</div>
				<div class="modal-body no-padding" style="width:100%;">
					<form id="map-search" style="margin:10px;">
						<input id="map-keyword" type="textbox" style="width:300px;" placeholder="尽量填写城市、区域、街道名" value="{pigcms{$now_user.site}"/>
						<input type="submit" value="搜索"/>
					</form>
					<div style="width: 650px; height: 250px; min-height: 250px;" id="cmmap"></div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
	var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",merchant_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}";
	</script>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCLuaiOlNCVdYl9ZKZzJIeJVkitLksZcYA&libraries=places&language=en"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script-->
<script>
    $('img').click(function () {
        //alert($(this).attr('src'));
        window.top.artiframe($(this).attr('src'),'查看',600,500,true,false,false);
    });
</script>
<include file="Public:footer"/>