<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Adver/adver_amend')}" enctype="multipart/form-data">
		<input type="hidden" name="id" value="{pigcms{$now_adver.id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('I_AD_NAME')}</th>
				<td><input type="text" class="input fl" name="name" value="{pigcms{$now_adver.name}" size="20" placeholder="" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_AD_SUBTITLE')}</th>
				<td><input type="text" class="input fl" name="sub_name" size="20" value="{pigcms{$now_adver.sub_name}" placeholder="" validate="required:true"/></td>
			</tr>
			<!--if condition="$many_city eq 1"-->
				<tr>
					<th width="15%">{pigcms{:L('I_UNIVERSAL_AD')}</th>
					<td width="35%" class="radio_box">
						<span class="cb-enable"><label class="cb-enable <if condition="$now_adver['city_id'] eq 0">selected</if>"><span>{pigcms{:L('G_UNIVERSAL')}</span><input id="yes" type="radio" name="currency" value="1" <if condition="$now_adver['city_id'] eq 0">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$now_adver['city_id'] neq 0">selected</if>"><span>{pigcms{:L('G_CITY_SPECIFIC')}</span><input id="no" type="radio" name="currency" value="2" <if condition="$now_adver['city_id'] neq 0">checked="checked"</if> /></label></span>
					</td>
				</tr>
				<tr id="adver_region" <if condition="$now_adver['city_id'] eq 0">style="display:none;"</if>>
					<th width="15%">{pigcms{:L('E_CITY_OF_LOCATION')}</th>
					<td width="85%" colspan="3" id="choose_cityareass" province_idss="{pigcms{$now_adver['province_id']}" city_idss="{pigcms{$now_adver['city_id']}"></td>
				</tr>
			<!--/if-->
			<tr>
				<th width="80">{pigcms{:L('I_AD_IMAGE')}</th>
				<td><img src="{pigcms{$config.site_url}/upload/adver/{pigcms{$now_adver.pic}" style="width:260px;height:80px;" class="view_msg"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_UPLOAD_IMAGE')}</th>
				<td><input type="file" class="input fl" name="pic" style="width:200px;" placeholder="" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_BACKGROUND_COLOR')}</th>
				<td><input type="text" class="input fl" name="bg_color" value="{pigcms{$now_adver.bg_color}" id="choose_color" style="width:120px;" placeholder="" tips=""/>&nbsp;&nbsp;<a href="javascript:void(0);" id="choose_color_box" style="line-height:28px;">{pigcms{:L('I_CHOOSE_COLOR')}</a></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_URL')}</th>
				<td>
				<input type="text" class="input fl" name="url" id="url" value="{pigcms{$now_adver.url}" style="width:200px;" placeholder="" validate="maxlength:200,required:true,url:true"/>
				<if condition="!C('butt_open')">
					<if condition="$now_category['cat_type'] neq 1">
						<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 0)" data-toggle="modal"> </a>
					<else />
						<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 1)" data-toggle="modal"> </a>
					</if>
				</if>
				</td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_LISTING_ORDER2')}</th>
				<td><input type="text" class="input fl" name="sort" style="width:80px;" value="{pigcms{$now_adver.sort}" validate="maxlength:10,required:true,number:true"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_STATUS')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_adver['status'] eq 1">selected</if>"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="status" value="1" <if condition="$now_adver['status'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_adver['status'] eq 0">selected</if>"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="status" value="0" <if condition="$now_adver['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_FILL_UNIVERSAL')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_adver['complete'] eq 1">selected</if>"><span>Yes</span><input type="radio" name="complete" value="1" <if condition="$now_adver['complete'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_adver['complete'] eq 0">selected</if>"><span>No</span><input type="radio" name="complete" value="0" <if condition="$now_adver['complete'] eq 0">checked="checked"</if>/></label></span>
					<em tips="当设置过城市广告时，城市广告数量不够总数量，是否使用通用广告来补全城市广告位的数量。" class="notice_tips"></em>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
function addLink(domid, iskeyword, type){
	art.dialog.data('domid', domid);
	if (type == 1) {
		art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	} else {
		art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
	}
}
$("#yes").click(function(){
	$("#adver_region").hide();
})
$("#no").click(function(){
	$("#adver_region").show();
})
</script>
<include file="Public:footer"/>