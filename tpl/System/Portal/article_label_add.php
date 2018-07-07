<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="//apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.css">
	<script type="text/javascript" src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>
	<style type="text/css">
		.form-inline{margin-top:10px;}
	</style>
</head>
<body>
<div class="container">
	<input type="hidden" id="label_id" value="{pigcms{$label.id}"/>
	<div class="form-inline">
		<div class="form-group">
			<label>标签名称：</label>
			<input type="text" id="title" class="form-control" value="{pigcms{$label.title}" placeholder="请输入标签名称">
		</div>
	</div>

	<div class="btn hidden">
		<input type="submit" name="dosubmit" id="dosubmit" onclick="save()" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>

</div>

<script type="text/javascript">

	// 保存
	function save(){
		var label_id = $('#label_id').val();
		var name = $.trim($('#title').val());

		if(name == ''){
			layer.alert('请输入标签名称');
			return;
		}

		$.post("{pigcms{:U('Portal/save_label')}",{'label_id':label_id,'name':name},function(response){
			if(response.code>0){
				layer.alert(response.msg);
				return;
			}
			layer.msg(response.msg);
		},'json');
	}
</script>
</body>
</html>