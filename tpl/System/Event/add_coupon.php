<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Event/coupon_modify')}" enctype="multipart/form-data">
        <input name="event_id" value="{pigcms{$event_id}" type="hidden">
        <input name="coupon_id" value="{pigcms{$coupon.id|default='0'}" type="hidden">
        <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">优惠券名称</th>
				<td>
                    <input type="text" class="input fl" name="name" size="20" validate="maxlength:200,required:true" value="{pigcms{$coupon.name|default=''}" />
                </td>
			</tr>
            <tr>
                <th width="80">优惠券描述</th>
                <td>
                    <textarea name="desc" validate="required:true">{pigcms{$coupon.desc|default=''}</textarea>
                </td>
            </tr>
			<tr>
				<th width="80">消费金额</th>
				<td>
                    <input type="text" class="input fl" name="use_price" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.use_price|default='0.00'}" />
                </td>
			</tr>
            <tr>
                <th width="80">优惠金额</th>
                <td>
                    <input type="text" class="input fl" name="discount" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.discount|default='0.00'}" />
                </td>
            </tr>
            <tr>
                <th width="80">限制天数</th>
                <td>
                    <input type="text" class="input fl" name="limit_day" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.limit_day|default=''}" />
                </td>
            </tr>
            <tr>
                <th width="80">优惠券类型</th>
                <td>
                    <select name="type">
                        <option value="0" <if condition="$coupon.type eq 0">selected</if>>本人</option>
                        <option value="1" <if condition="$coupon.type eq 1">selected</if>>邀请者</option>
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