<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="//apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.css">
	<script type="text/javascript" src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>

	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<style type="text/css">
		.form-inline{margin-top:10px;}
	</style>
</head>
<body>
<div class="container">
	<input type="hidden" id="article_id" value="{pigcms{$article.aid}"/>
	<div class="form-inline">
		<div class="form-group">
			<div class="form-group">
		      <label>资讯分类：</label>
		      <select class="form-control" onchange="select_article_cate(this)" id="parent_cates" pid="{pigcms{$article['fcid']}">
			  	<option value="">资讯分类</option>
			  	<if condition="$fcid_list">
			  	<volist name="fcid_list" id="vo">
			  		<option value="{pigcms{$vo.cid}" >{pigcms{$vo.cat_name}</option>
			  	</volist>
			  	</if>
			  </select>
		  </div>
		  <div class="form-group">
		    <select class="form-control" id="child_cates" child_id="{pigcms{$article['cid']}">
			  	<option value="">资讯分类</option>
			</select>
		  </div>
		</div>
	</div>

	<div class="form-inline">
		<div class="form-group">
			<label>资讯标题：</label>
			<input type="text" id="title" class="form-control" value="{pigcms{$article.title}" placeholder="请输入标题">
		</div>
	</div>

	<div class="form-inline">
		<div class="form-group" id="article_labels">
			<label>资讯标签：</label>
			<if condition="$label_list neq ''">
			<volist name="label_list" id="vo">
			<?php 
				$flag = 0;
				if($article_label_list){
					foreach($article_label_list as $myitem){
						if($myitem['label_id'] == $vo['id']){
							$flag = 1;
							break;
						}
					}
				}
			?>
			<label><input type="checkbox" label_id="{pigcms{$vo.id}" <if condition="$flag eq 1">checked</if>>{pigcms{$vo.title}</label>
			</volist>
			</if>
		</div>
	</div>

	<div class="form-inline">
		<div class="form-group">
			<label>资讯简介：</label>
			<textarea class="form-control" style="width: 570px;height: 80px;" id="article_desc">{pigcms{$article.desc}</textarea>
		</div>
	</div>
	
	<div class="form-inline">
		<div class="form-group">
			<label>缩&nbsp;&nbsp;略&nbsp;&nbsp;图：</label>
			<div class="input-group">
				<form target="frame_img" enctype="multipart/form-data" action="{pigcms{:U('Portal/uplad_img')}" method="post" style="display: none;">
					<input type="file" name="file_img" onchange="upload_img(this)">
				</form>
				<iframe name="frame_img" id="frame_img" style="display: none;"></iframe>
				<a href="javascript:;" onclick="img_click(this)" >
					<if condition="$article['thumb']">
					<img id="img" flag="1" src="{pigcms{$article.thumb}" imgurl="{pigcms{$article.thumb}" style="width: 120px;height: 80px;" />
					<else/>
					<img id="img" flag="0" src="{pigcms{$static_path}images/addimg.jpg" imgurl="{pigcms{$static_path}images/addimg.jpg" style="width: 120px;height: 80px;"/>
					</if>
				</a>
				<span style="color:gray;margin-top: 64px;padding-left: 120px;">建议上传不大于1M的jpg、png图片</span>
			</div>
		</div>
	</div>
	
	<div class="form-inline">
		<label>资讯内容：</label>
		<textarea id="msg" placeholder="请输入内容" class="layui-textarea">{pigcms{$article.msg|htmlspecialchars_decode}</textarea>
	</div>

	<div class="form-inline">
		<label>是否发布：</label>
		<label><input type="checkbox" id="status" <if condition="$article['status'] eq 1">checked</if>>发布</label>
	</div>

	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" onclick="save()" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>

</div>

<script type="text/javascript">

	init_cate();

	// 初始化分类
	function init_cate(){
		var parent_cat_id = $('#parent_cates').attr('pid');
		if(parent_cat_id == ''){
			return;
		}

		$('#parent_cates').val(parent_cat_id);
		select_article_cate($('#parent_cates'));
	}

	// 资讯分类选择
	function select_article_cate(obj){
		var pid = $(obj).val();
		$('#child_cates').html('');
		$.get("{pigcms{:U('Portal/ajax_article_child_cates')}",{'pid':pid},function(response){
			if(response.code>0){
				return;
			}
			var child_cat_id = $('#child_cates').attr('child_id');
			var html = '<option value="">资讯分类</option>';
			$.each(response.msg,function(i,v){
				html += '<option value="'+v.cid+'" ' + (child_cat_id == v.cid ? 'selected' : '' ) + '>'+v.cat_name+'</option>';
			});
			$('#child_cates').html(html);
		},'json');
	}

	// 缩略图上传
	function img_click(obj){
		$(obj).siblings('form').children('input').click();
	}

	function upload_img(obj){
		var index = layer.load(1, {
		  shade: [0.6,'#000'], //0.1透明度的白色背景
		  offset: '50px'
		});
		$(obj).parent('form').submit();
	}

	function upload_success(msg){
		$('#img').attr('src',msg).attr('imgurl',msg).attr('flag','1');
		layer.closeAll();
	}

	function upload_error(msg){
		layer.closeAll();
		layer.alert(msg);
	}

	// 保存
	function save(){
		var data = new Object();
		data.aid = $('#article_id').val();
		data.fcid = $('#parent_cates').val();
		data.cid = $('#child_cates').val();
		data.title = $.trim($('#title').val());
		data.desc = $.trim($('#article_desc').val());
		data.img_flag = $('#img').attr('flag');
		data.thumb = $('#img').attr('imgurl');
		kind_editor_msg.sync();
		data.msg = $.trim($('#msg').val());
		data.status = $('#status').is(':checked') ? 1 : 0;

		// 文章标签
		var labels = $('#article_labels input[type="checkbox"]:checked');
		var label_ids = [];
		$.each(labels,function(i,v){
			label_ids[i] = $(v).attr('label_id');
		});
		data.labels = label_ids;

		if(data.fcid =='' || data.fcid <= 0 || data.cid == '' || data.cid <= 0){
			layer.alert('请选择分类');
			return;
		}

		if(data.title == ''){
			layer.alert('请输入资讯标题');
			return;
		}

		if(data.msg == ''){
			layer.alert('请输入资讯内容');
			return;
		}

		$.post("{pigcms{:U('Portal/save_article')}",data,function(response){
			if(response.code>0){
				layer.alert(response.msg);
				return;
			}
			layer.msg(response.msg,{time:1000},function(){
				window.top.main_refresh();
				parent.window.top.closeiframe();
			});
			
		},'json');
	}
</script>
 
<script type="text/javascript">

KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});

	// 初始化信息编辑器
	kind_editor_msg = K.create("#msg",{
		uploadJson: "{pigcms{:U('Portal/ajax_upload_pic')}",
		width:'605px',
		height:'450px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link'
		]
	});
});
</script>
</body>
</html>