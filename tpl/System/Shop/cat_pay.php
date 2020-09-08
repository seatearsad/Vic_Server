<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/cat_pay')}" frame="true" refresh="true">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<input type="hidden" name="cat_fid" value="{pigcms{$parentid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">分类名称</th>
				<td>{pigcms{$now_category.cat_name}</td>
			</tr>
			<tr>
				<th width="90">支付加密</th>
				<td style="line-height: 28px;">
                    <span class="cb-enable"><label class="cb-enable selected"><span>加密</span><input type="radio" name="pay_secret" value="1" checked="checked" /></label></span>
                    <span class="cb-disable"><label class="cb-disable"><span>不加密</span><input type="radio" name="pay_secret" value="0" /></label></span>
                </td>
			</tr>
            <input type="hidden" name="cat_id" value="{pigcms{$now_category['cat_id']}" />
            <input type="hidden" name="cat_fid" value="{pigcms{$parentid}" />
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>