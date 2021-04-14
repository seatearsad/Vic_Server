<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Area/amend')}" frame="true" refresh="true">
		<input type="hidden" name="area_id" value="{pigcms{$now_area['area_id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('G_NAME')}</th>
				<td><input type="text" class="input fl" name="area_name" value="{pigcms{$now_area.area_name}" size="20" placeholder="" validate="maxlength:30,required:true"/></td>
			</tr>
			<if condition="$now_area['area_type'] eq 2 || $now_area['area_type'] eq 4">
				<tr>
					<th width="80">{pigcms{:L('I_INITIAL_LETTER')}</th>
					<td><input type="text" class="input fl" name="first_pinyin" value="{pigcms{$now_area.first_pinyin}" size="20" placeholder="" validate="maxlength:20,required:true" tips="名称第一个字符的首字母！输入名称后，若此字段为空，会自动填写（仅作为示例）"/></td>
				</tr>
			</if>
			<if condition="$now_area['area_type'] gt 1">
				<tr>
					<th width="80">{pigcms{:L('I_NETWOEK_SYMBOL')}</th>
					<td><input type="text" class="input fl" name="area_url" value="{pigcms{$now_area.area_url}" size="20" placeholder="" validate="maxlength:20,required:true" tips="一般为地区名称的首字母！输入名称后，若此字段为空，会自动填写（仅作为示例）"/></td>
				</tr>
			</if>
			<if condition="$now_area['area_type'] gt 1 && $now_area['area_type'] lt 4">
				<tr>
					<th width="80">{pigcms{:L('I_IP_SYMPOL')}</th>
					<td><input type="text" class="input fl" name="area_ip_desc" value="{pigcms{$now_area.area_ip_desc}" size="20" placeholder="" validate="maxlength:30,required:true" tips="一般格式为 XX省XX市XX区(县)"/></td>
				</tr>
			</if>
			<tr>
				<th width="80">{pigcms{:L('I_LISTING_ORDER')}</th>
				<td><input type="text" class="input fl" name="area_sort" value="{pigcms{$now_area.area_sort}" size="10" value="0" validate="required:true,number:true,maxlength:6" tips="数值越大，排序越前"/></td>
			</tr>
            <!--tr>
                <th width="80">Place ID</th>
                <td><input type="text" class="input fl" name="place_id" value="{pigcms{$now_area.place_id}" size="35" value="0" validate="required:false" /></td>
            </tr-->
			<if condition="$now_area['area_type'] gt 1">
				<tr>
					<th width="100">{pigcms{:L('I_POPULARITY')}</th>
					<td>
						<span class="cb-enable"><label class="cb-enable <if condition="$now_area['is_hot'] eq 1">selected</if>"><span>Yes</span><input type="radio" name="is_hot" value="1" <if condition="$now_area['is_hot'] eq 1">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$now_area['is_hot'] eq 0">selected</if>"><span>No</span><input type="radio" name="is_hot" value="0" <if condition="$now_area['is_hot'] eq 0">checked="checked"</if>/></label></span>
					</td>
				</tr>
			</if>
			<tr>
				<th width="80">{pigcms{:L('G_STATUS')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_area['is_open'] eq 1">selected</if>"><span>{pigcms{:L('I_ACTIVE')}</span><input type="radio" name="is_open" value="1" <if condition="$now_area['is_open'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_area['is_open'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="is_open" value="0" <if condition="$now_area['is_open'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script type="text/javascript">
		get_first_word('area_name','area_url','first_pinyin');
	</script>
<include file="Public:footer"/>