<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Footer/modify')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('G_NAME')}</th>
				<td><input type="text" class="input fl" name="name" size="30" validate="maxlength:50,required:true" tips="网站底部显示的名称"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_TITLE')}</th>
				<td><input type="text" class="input fl" name="title" size="50" validate="maxlength:50" tips="介绍页的内容标题，不填写则以名称显示"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('I_CUSTOM_URL')}</th>
				<td><input type="text" class="input fl" id="url" name="url" size="60" validate="maxlength:200" tips=""/>
				<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url', 0, 1)" data-toggle="modal"> </a></td>
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
	<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
		function addLink(domid, iskeyword, type){
			art.dialog.data('domid', domid);
			if (type == 1) {
				art.dialog.open('?g=Admin&c=LinkPC&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
			} else {
				art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
			}
		}
		KindEditor.ready(function(K){
			kind_editor = K.create("#content",{
				width:'402px',
				height:'300px',
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news"
			});
		});
	</script>
<include file="Public:footer"/>