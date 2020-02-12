<include file="Public:header"/>
<style>
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
    #J_selectImage_0{
        background-color: #EEEEEE;
        color: #666666;
        text-indent: 0px;
        border-radius: 5px;
        box-sizing: border-box;
        display: inline-block;
        width: 100%;
    }
    .img_0{
        width: 100%;
        text-align: center;
    }
    .img_0 img{
        height: 100px;
    }
</style>
	<form id="myform" method="post" action="{pigcms{:U('Systemnews/edit_category')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
            <tr>
                <th width="80">总分类</th>
                <input type="hidden" name="type" value="{pigcms{$category.type}" />
                <td>
                    <select name="all_type" id="select_type">
                        <volist name="all_type" id="type">
                            <option value="{pigcms{$key}" <if condition="$key eq $category['type']">selected</if>>{pigcms{$type}</option>
                        </volist>
                    </select>
                </td>
            </tr>
			<tr>
				<th width="80">分类</th>
				<input type="hidden" name="id" value="{pigcms{$category.id}" />
				<td><input type="text" class="input fl" name="name" size="75" value="{pigcms{$category.name}"placeholder="快报标题"  validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">排序</th>
				<td><input type="text" class="input fl" name="sort" value="{pigcms{$category.sort}" size="10" placeholder="排序" validate="maxlength:20,required:true,digits:true" /></td>
			</tr>
            <tr>
                <th width="80">链接图片</th>
                <td>
                    <div style="display:inline-block;" id="J_selectImage_0">
                        <div class="btn btn-sm btn-success" style="position:relative;text-align: left;border:1px solid #ffa52d;">
                            Upload
                        </div>
                    </div>
                    <div class="img_0">
                        <img src="{pigcms{$category.link_img}" >
                    </div>
                </td>
            </tr>
            <tr>
                <th width="80">链接地址</th>
                <td><input type="text" class="input fl" name="link_url" size="75" value="{pigcms{$category.link_url}" placeholder="链接地址" validate="maxlength:50"/></td>
            </tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$category['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$category['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$category['status'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="status" value="0" <if condition="$category['status'] eq 0">checked="checked"</if> /></label></span>
				</td>
			</tr>
		</table>
        <input id="filename_0" type="hidden" name="link_img" value="{pigcms{$category.link_img}">
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
    <script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
	<script>
        var uploader = WebUploader.create({
            auto: true,
            swf: '{pigcms{$static_public}js/Uploader.swf',
            server: "{pigcms{:U('System/Systemnews/ajax_upload')}&cate_id=0",
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,png',
                mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
            }
        });
        uploader.addButton({
            id:'#J_selectImage_0',
            name:'image_0',
            multiple:false
        });
        uploader.on('fileQueued',function(file){
            if($('.upload_pic_li').size() >= 5){
                uploader.cancelFile(file);
                alert('最多上传5个图片！');
                return false;
            }
        });
        uploader.on('uploadSuccess',function(file,response){
            if(response.error == 0){
                var fid = file.source.ruid;
                var ruid = fid.split('_');
                var img = findImg(ruid[1],response.file);
                img.html('<img src="'+response.url+'"/>');
                img.css("height","100px");
            }else{
                alert(response.info);
            }
        });

        uploader.on('uploadError', function(file,reason){
            $('.loading'+file.id).remove();
            alert('上传失败！请重试。');
        });

        function findImg(fid,file) {
            var img = '';
            var all = 3;
            var curr = 0;
            var is_addcss = false;
            for(var i=0;i<all;i++) {
                $('#J_selectImage_' + i).children('div').each(function () {
                    if (typeof($(this).attr('id')) != 'undefined') {
                        if(is_addcss && i > curr){
                            var top = parseInt($(this).css("top"));
                            $(this).css("top",top+100+"px");
                        }
                        var arr = $(this).attr('id').split('_');
                        if (arr[2] == fid) {
                            curr = i;
                            img = $('.img_' + i);
                            if($.trim(img.html()) == ''){
                                is_addcss = true;
                            }else{
                                is_addcss = false;
                            }

                            $('#filename_'+i).val(file);
                        }
                    }
                });
            }

            return img;
        }

        $('#select_type').change(function () {
            $("input[name='type']").val($(this).val());
        });
    </script>
<include file="Public:footer"/>