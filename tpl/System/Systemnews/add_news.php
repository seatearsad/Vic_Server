<include file="Public:header"/>
<style>
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
    #J_selectImage_0,#J_selectImage_1{
        background-color: #EEEEEE;
        color: #666666;
        text-indent: 0px;
        border-radius: 5px;
        box-sizing: border-box;
        display: inline-block;
        width: 100%;
    }
    .img_0,.img_1,.img_2{
        width: 100%;
        text-align: center;
    }
    .img_0 img,.img_1 img,.img_2 img{
        height: 100px;
    }
</style>
	<form id="myform" method="post" action="{pigcms{:U('Systemnews/add_news')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">标题</th>
				<td><input type="text" class="input fl" name="title" size="75" placeholder="标题" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">分类</th>
				<if condition="$category">
				<td>
					<select name="category_id">
						<volist name="category" id="vo">
							<option value="{pigcms{$vo.id}" <if condition="$vo['id'] eq $_GET['category_id']">selected="selected"</if>>{pigcms{$vo.name}</option>
						</volist>
					</select>
				</td>
				</if>
			</tr>
			<tr>
				<th width="80">排序</th>
			
				<td><input type="text" class="input fl" name="sort" value="0"  placeholder="快报标题" validate="maxlength:50,required:true,digits:true"/></td>
			</tr>
            <if condition="$curr_cate['type'] eq 0">
                <tr>
                    <th width="80">城市</th>
                    <td>
                        <select id="city_id" name="city_id">
                            <option value="0">通用</option>
                            <volist name="city" id="vo">
                            <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                            </volist>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th width="80">封面</th>
                    <td>
                        <div style="display:inline-block;" id="J_selectImage_0">
                            <div class="btn btn-sm btn-success" style="position:relative;text-align: left;border:1px solid #ffa52d;">
                                Upload
                            </div>
                        </div>
                        <div class="img_0"></div>
                    </td>
                </tr>
                <tr>
                    <th width="80">顶部图片</th>
                    <td>
                        <div style="display:inline-block;" id="J_selectImage_1">
                            <div class="btn btn-sm btn-success" style="position:relative;text-align: left;border:1px solid #ffa52d;">
                                Upload
                            </div>
                        </div>
                        <div class="img_1"></div>
                    </td>
                </tr>
            </if>
			<tr>
				<th width="80">内容</th>
				<td>
					<textarea name="content" id="content"></textarea>
				</td>
			</tr>
			<tr>
				<th width="80">状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked"/></label></span>
					<span class="cb-disable"><label class="cb-disable "><span>禁用</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
		</table>
        <input id="filename_0" type="hidden" name="cover">
        <input id="filename_1" type="hidden" name="top_img">
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
	<script type="text/javascript">
        var uploader = WebUploader.create({
            auto: true,
            swf: '{pigcms{$static_public}js/Uploader.swf',
            server: "{pigcms{:U('System/systemnews/ajax_upload')}&cate_id={pigcms{$curr_cate.id}",
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
        uploader.addButton({
            id:'#J_selectImage_1',
            name:'image_1',
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

		KindEditor.ready(function(K){
			kind_editor = K.create("#content",{
				width:'402px',
				height:'320px',
				resizeType : 1,
				allowPreviewEmoticons:false,
				allowImageUpload : true,
				filterMode: true,
				items : [
					'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
					'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
					'insertunorderedlist', '|', 'emoticons', 'image', 'link'
				],
				emoticonsPath : './static/emoticons/',
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/news"
			});
		});
	</script>
<include file="Public:footer"/>