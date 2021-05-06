<include file="Public:header"/>
	<form id="myform" method="post" enctype="multipart/form-data">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('E_REVIEWID')}</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" value="{pigcms{$reply.pigcms_id}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_MERCHANTNAME')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.m_name}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_STORENAME')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.s_name}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_USERNAME')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.nickname}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_USERPHONE')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.phone}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_REVIEWTYPE')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.type_name}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_REVIEWTIME')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.add_time|date='Y-m-d H:i:s',###}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_REST_SCORE')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.score}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_REST_REVIEW')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.comment}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
			</tr>
            <tr>
                <th width="80">{pigcms{:L('E_REVIEW_TRANSLATE')}</th>
                <td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$reply.comment_en}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/></td>
            </tr>
			<if condition="!empty($reply['pics'])">
				<tr>
					<th width="80">分类现图</th>
					<td>
					<volist name="reply['pics']" id="pic">
					<img src="{pigcms{$pic['image']}" style="width:50px;height:50px;" class="view_msg"/>　
					</volist>
					</td>
				</tr>
			</if>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>