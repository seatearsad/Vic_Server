<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/cat_service')}" frame="true" refresh="true">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<input type="hidden" name="cat_fid" value="{pigcms{$parentid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">分类名称</th>
				<td>{pigcms{$now_category.cat_name}</td>
			</tr>
			<tr>
				<th width="90">服务费比例</th>
				<td style="line-height: 28px;">
                    <input type="text" class="input fl" name="service_fee" id="service_fee" validate="maxlength:2,required:true,number:true" size="25"/> %</td>
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