<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Shop/cat_amend')}" frame="true" refresh="true">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<input type="hidden" name="cat_fid" value="{pigcms{$parentid}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">{pigcms{:L('C_CATEGORYNAME')}</th>
				<td><input type="text" class="input fl" name="cat_name" id="cat_name" value="{pigcms{$now_category.cat_name}" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/></td>
			</tr>
			<tr>
				<th width="90">{pigcms{:L('C_CATEGORYURL')}</th>
				<td><input type="text" class="input fl" name="cat_url" id="cat_url" value="{pigcms{$now_category.cat_url}" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips="只能使用英文或数字，用于网址（url）中的标记！建议使用分类的拼音"/></td>
			</tr>
			<tr>
				<th width="90">{pigcms{:L('C_LISTORDER')}</th>
				<td><input type="text" class="input fl" name="cat_sort" value="{pigcms{$now_category.cat_sort}" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips="默认添加时间排序！手动排序数值越大，排序越前。"/></td>
			</tr>
			<tr>
				<th width="90">{pigcms{:L('C_CATESTAT')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_category['cat_status'] eq 1">selected</if>"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="cat_status" value="1"  <if condition="$now_category['cat_status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_category['cat_status'] eq 0">selected</if>"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="cat_status" value="0"  <if condition="$now_category['cat_status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
			<tr>
				<th width="90">{pigcms{:L('C_CATEGORYSCS')}</th>
				<td>
					<select name="show_method" class="valid">
					<option value="0" <if condition="$now_category['show_method'] eq 0">selected</if>>{pigcms{:L('C_CATEGORYSCS1')}</option>
					<option value="1" <if condition="$now_category['show_method'] eq 1">selected</if>>{pigcms{:L('C_CATEGORYSCS2')}</option>
					<option value="2" <if condition="$now_category['show_method'] eq 2">selected</if>>{pigcms{:L('C_CATEGORYSCS3')}</option>
					</select>
				</td>
			</tr>
            <tr>
                <th width="90">{pigcms{:L('C_CATEICON')}</th>
                <td>
                    <div style="display:inline-block;position:relative;width:78px;height:30px;" id="J_selectImage">
                        <div class="btn btn-sm btn-success">{pigcms{:L('BASE_UPLOADIMAGE')}</div>
                    </div>
                    <div id="upload_pic_ul">
                        <if condition="$now_category['cat_img'] neq ''">
                        <img src="{pigcms{$now_category.img_url}" width="80" />
                        <input type="hidden" name="cat_img" value="{pigcms{$now_category.cat_img}" />
                        </if>
                    </div>
                </td>
            </tr>
            <if condition="$parentid eq 0">
                <tr>
                    <th width="90">{pigcms{:L('C_CATETYPE')}</th>
                    <td>
                        <span class="cb-enable"><label class="cb-enable <if condition="$now_category['cat_type'] eq 0">selected</if>"><span>{pigcms{:L('C_CATEGORYNOR')}</span><input type="radio" name="cat_type" value="0" <if condition="$now_category['cat_type'] eq 0">checked="checked"</if> /></label></span>
                        <span class="cb-disable"><label class="cb-disable <if condition="$now_category['cat_type'] eq 1">selected</if>"><span>{pigcms{:L('C_CATEGORYFT')}</span><input type="radio" name="cat_type" value="1" <if condition="$now_category['cat_type'] eq 1">checked="checked"</if> /></label></span>
                    </td>
                </tr>
                <tr>
                    <th width="90">{pigcms{:L('BASE_CITY')}</th>
                    <td>
                        <select name="city_id">
                            <option value="0" <if condition="$now_category['city_id'] eq 0">selected</if>>{pigcms{:L('G_UNIVERSAL')}</option>
                            <volist name="city" id="vo">
                                <option value="{pigcms{$vo.area_id}" <if condition="$now_category['city_id'] eq $vo['area_id']">selected</if>>{pigcms{$vo.area_name}</option>
                            </volist>
                        </select>
                    </td>
                </tr>
            <else />
                <tr>
                    <th width="90">{pigcms{:L('C_CATETYPE')}</th>
                    <td style="vertical-align: middle">
                        <if condition="$category['cat_type'] eq 0">
                            {pigcms{:L('C_CATEGORYNOR')}
                            <else />
                            {pigcms{:L('C_CATEGORYFT')}
                        </if>
                        <input type="hidden" name="cat_type" value="{pigcms{$category.cat_type}" >
                    </td>
                </tr>
                <tr>
                    <th width="90">{pigcms{:L('BASE_CITY')}</th>
                    <td style="vertical-align: middle">
                        {pigcms{$category.city_name}
                        <input type="hidden" name="city_id" value="{pigcms{$category.city_id}" >
                    </td>
                </tr>
            </if>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('BASE_SUBMIT')}" class="button" />
			<input type="reset" value="{pigcms{:L('BASE_CANCEL')}" class="button" />
		</div>
	</form>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    var uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Shop/ajax_upload_pic', array('cat_id' => $now_category['cat_id'],'cat_fid'=>$parentid))}",
        pick: '#J_selectImage',
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploader.on('fileQueued',function(file){
        if($('.upload_pic_li').size() >= 1){
            uploader.cancelFile(file);
            alert('最多上传1张图片！');
            return false;
        }
    });
    uploader.on('uploadSuccess',function(file,response){
        if(response.error == 0){
            $('#upload_pic_ul').html('<img src="'+response.url+'" width="80"/><input type="hidden" name="cat_img" value="'+response.title+'"/>');
        }else{
            alert(response.info);
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });
</script>
<style>
    .webuploader-container div {
        width: 78px !important;
        height: 30px !important;
        box-sizing: border-box;
    }
    .btn-success, .btn-success:focus {
        background-color: #87b87f !important;
        border-color: #87b87f;
        color: white;
    }
    .btn-sm {
        border-width: 4px;
        font-size: 13px;
        line-height: 1.39;
    }
    .webuploader-element-invisible {
        position: absolute !important;
        clip: rect(1px 1px 1px 1px);
        clip: rect(1px,1px,1px,1px);
    }
    input[type="file"] {
        display: block;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }
</style>
<include file="Public:footer"/>