<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Index/saveAdmin')}" frame="true" refresh="true">
		<input type="hidden" name="id" value="{pigcms{$_GET['id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('B_USERNAME')}</th>
				<td><input type="text" class="input fl" name="account" id="account" size="20" placeholder="" validate="maxlength:30,required:true" value="{pigcms{$admin['account']}"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('B_INPUTPASS')}</th>
				<td><input type="password" class="input fl" name="pwd" id="pwd" size="20" placeholder=""  tips="{pigcms{:L('B_PASSDES')}"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('B_FULLNAME')}</th>
				<td><input type="text" class="input fl" name="realname" id="realname" size="20" placeholder="" tips="{pigcms{:L('B_NAMEDESC')}" value="{pigcms{$admin['realname']}"/></td>
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
			<if condition="$config.open_extra_price  eq 1">
			<tr>
				<th width="80">区域管理员{pigcms{$config.score_name}结算比例</th>
				<td><input type="text" class="input fl" name="score_percent" size="20"  validate="required:true,min:0,max:100" value="{pigcms{$admin['score_percent']|floatval}"/></td>
			</tr>
			</if>
            <if condition="$admin['level'] eq 3">
            <tr>
                <th width="80">{pigcms{:L('BASE_CITY')}</th>
                <td>
                    <select name="area_id">
                        <option value="0" <if condition="$admin['area_id'] eq 0">selected="selected"</if>>None</option>
                    <volist name="city" id="vo">
                        <option value="{pigcms{$vo.area_id}" <if condition="$admin['area_id'] eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                    </volist>
                    </select>
                    <select name="level">
                        <option value="0">{pigcms{:L('B_NADMIN')}</option>
                        <option value="3" selected="selected">{pigcms{:L('B_CADMIN')}</option>
                    </select>
                </td>
            </tr>
                <else />
                <tr>
                    <th width="80">{pigcms{:L('BASE_TYPE')}</th>
                    <td>
                        <if condition="$admin['level'] eq 2">
                            Super Admin
                            <else />
                            <select name="level">
                                <option value="0" selected="selected">{pigcms{:L('B_NADMIN')}</option>
                                <option value="3">{pigcms{:L('B_CADMIN')}</option>
                            </select>
                        </if>
                    </td>
                </tr>
            </if>
			<tr>
				<th width="80">{pigcms{:L('B_USERSTAT')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$admin['status'] eq 1">selected</if>"><span>{pigcms{:L('B_USERSTATAC')}</span><input type="radio" name="status" value="1" <if condition="$admin['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$admin['status'] eq 0">selected</if>"><span>{pigcms{:L('B_USERSTATC')}</span><input type="radio" name="status" value="0" <if condition="$admin['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('BASE_SUBMIT')}" class="button" />
			<input type="reset" value="{pigcms{:L('BASE_CANCEL')}" class="button" />
		</div>
	</form>
	<script type="text/javascript">
		get_first_word('area_name','area_url','first_pinyin');
	</script>
<include file="Public:footer"/>