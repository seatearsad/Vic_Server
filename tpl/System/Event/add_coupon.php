<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Event/coupon_modify')}" enctype="multipart/form-data">
        <input name="event_id" value="{pigcms{$event_id}" type="hidden">
        <input name="coupon_id" value="{pigcms{$coupon.id|default='0'}" type="hidden">
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('G_COUPON')}</th>
				<td>
                    <input type="text" class="input fl" name="name" size="20" validate="maxlength:200,required:true" value="{pigcms{$coupon.name|default=''}" />
                </td>
			</tr>
            <tr>
                <th width="80">{pigcms{:L('G_COUP_DESCRIPTION')}</th>
                <td>
                    <textarea name="desc" validate="required:true">{pigcms{$coupon.desc|default=''}</textarea>
                </td>
            </tr>
			<tr>
				<th width="80">{pigcms{:L('G_MIN_ORDER')}</th>
				<td>
                    <input type="text" class="input fl" name="use_price" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.use_price|default='0.00'}" />
                </td>
			</tr>
            <tr>
                <th width="80">{pigcms{:L('G_DISCOUNT_AMOUNT')}</th>
                <td>
                    <input type="text" class="input fl" name="discount" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.discount|default='0.00'}" />
                </td>
            </tr>
            <tr>
                <th width="80">
                    {pigcms{$type_name}
                </th>
                <td>
                    <input type="text" class="input fl" name="limit_day" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.limit_day|default=''}" />
                </td>
            </tr>
            <tr>
                <th width="80">
                    <if condition="$event_type eq 3 or $event_type eq 4 or $event_type eq 5">
                        {pigcms{:L('G_COMBINED')}
                        <else />
                        {pigcms{:L('G_COUP_TYPE')}
                    </if>
                </th>
                <td>
                    <select name="type">
                        <option value="0" <if condition="$coupon.type eq 0">selected</if>>
                        <if condition="$event_type eq 3 or $event_type eq 4 or $event_type eq 5">
                            {pigcms{:L('G_NO')}
                            <else />
                            {pigcms{:L('G_INVITEE')}
                        </if>
                        </option>
                        <option value="1" <if condition="$coupon.type eq 1">selected</if>>
                        <if condition="$event_type eq 3 or $event_type eq 4 or $event_type eq 5">
                            {pigcms{:L('G_YES')}
                            <else />
                            {pigcms{:L('G_INVITER')}
                        </if>
                        </option>
                    </select>
                </td>
            </tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>