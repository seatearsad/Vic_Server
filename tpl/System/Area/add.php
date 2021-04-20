<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Area/modify')}" frame="true" refresh="true">
		<input type="hidden" name="area_type" value="{pigcms{$_GET['type']}"/>
		<input type="hidden" name="area_pid" value="{pigcms{$_GET['pid']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('G_NAME')}</th>
				<td><input type="text" class="input fl" name="area_name" id="area_name" size="20" placeholder="" validate="maxlength:30,required:true"/></td>
			</tr>
			<if condition="$_GET['type'] eq 2 || $_GET['type'] eq 4">
				<tr>
					<th width="80">{pigcms{:L('I_INITIAL_LETTER')}</th>
					<td><input type="text" class="input fl" name="first_pinyin" id="first_pinyin" size="20" placeholder="" validate="maxlength:20,required:true" tips="{pigcms{:L('FLLC')}"/></td>
				</tr>
			</if>
			<if condition="$_GET['type'] gt 1">
				<tr>
					<th width="80">{pigcms{:L('I_NETWOEK_SYMBOL')}</th>
					<td><input type="text" class="input fl" name="area_url" id="area_url" size="20" placeholder="" validate="maxlength:20,required:true" tips="{pigcms{:L('GITLIOT')}"/></td>
				</tr>
			</if>
			<if condition="$_GET['type'] gt 1 && $_GET['type'] lt 4">
				<tr>
					<th width="80">{pigcms{:L('I_IP_SYMPOL')}</th>
					<td><input type="text" class="input fl" name="area_ip_desc" size="20" placeholder="" validate="maxlength:30,required:true" tips="{pigcms{:L('TGFIXC')}"/></td>
				</tr>
			</if>
			<tr>
				<th width="80">{pigcms{:L('I_LISTING_ORDER')}</th>
				<td><input type="text" class="input fl" name="area_sort" size="10" value="0" validate="required:true,number:true,maxlength:6" tips="{pigcms{:L('HIGHVAL')}"/></td>
			</tr>
            <!--tr>
                <th width="80">Place ID</th>
                <td><input type="text" class="input fl" name="place_id" size="35" validate="required:false" /></td>
            </tr-->
			<if condition="$_GET['type'] gt 1">
				<tr>
					<th width="100">{pigcms{:L('I_POPULARITY')}</th>
					<td>
						<span class="cb-enable"><label class="cb-enable"><span>Yes</span><input type="radio" name="is_hot" value="1" /></label></span>
						<span class="cb-disable"><label class="cb-disable selected"><span>No</span><input type="radio" name="is_hot" value="0" checked="checked"/></label></span>
					</td>
				</tr>
			</if>
			<tr>
				<th width="80">{pigcms{:L('G_STATUS')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('I_ACTIVE')}</span><input type="radio" name="is_open" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="is_open" value="0" /></label></span>
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