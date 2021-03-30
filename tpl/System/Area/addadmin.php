<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Area/saveAdmin')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}"/>
		<input type="hidden" name="area_id" value="{pigcms{$_GET['area_id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('I_ACCOUNT_NAME')}</th>
				<td><input type="text" class="input fl" name="account" id="account" size="20" placeholder="" validate="maxlength:30,required:true" value="{pigcms{$admin['account']}"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_PASSWORD')}</th>
				<td><input type="password" class="input fl" name="pwd" id="pwd" size="20" placeholder=""  tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('B_FULLNAME')}</th>
				<td><input type="text" class="input fl" name="realname" id="realname" size="20" placeholder="" tips="" value="{pigcms{$admin['realname']}"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('B_USERPHONE')}</th>
				<td><input type="text" class="input fl" name="phone" size="20" placeholder=""  value="{pigcms{$admin['phone']}"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('B_USERPEMAIL')}</th>
				<td><input type="text" class="input fl" name="email" size="20" value="{pigcms{$admin['email']}"/></td>
			</tr>
			<!--tr>
				<th width="80">QQ</th>
				<td><input type="text" class="input fl" name="qq" size="20" value="{pigcms{$admin['qq']}"/></td>
			</tr-->
			<tr>
				<th width="80">{pigcms{:L('B_USERSTAT')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$admin['status'] eq 1">selected</if>"><span>{pigcms{:L('I_ACTIVE')}</span><input type="radio" name="status" value="1" <if condition="$admin['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$admin['status'] eq 0">selected</if>"><span>{pigcms{:L('I_HIDE')}</span><input type="radio" name="status" value="0" <if condition="$admin['status'] eq 0">checked="checked"</if> /></label></span>
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