<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Appintro/add')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('I_TITLE')}</th>
				<td><input type="text" class="input fl" name="title" size="75" placeholder="{pigcms{:L('I_ANNOUNCEMENT_TITLE')}" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_CONTENT')}</th>
				<td>
					<textarea name="content" id="content"></textarea>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script type="text/javascript">
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/intro"
			});
		});
	</script>
<include file="Public:footer"/>