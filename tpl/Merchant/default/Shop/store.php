<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
	<title>{pigcms{$config.site_name}</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
</head>
<body>
<form id="myform" method="post" action="{pigcms{:U('Shop/clone_goods')}" enctype="multipart/form-data">
<input type="hidden" name="store_id" value="{pigcms{$store_id}"/>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
<tr>
	<th></th>
	<th>{pigcms{:L('STORE_NAME_BKADMIN')}</th>
</tr>
<volist name="stores" id="vo">
<tr>
	<lable><th><input type="checkbox" name="store_ids[]" value="{pigcms{$vo['store_id']}"/></th></lable>
	<th>{pigcms{$vo['name']}</th>
</tr>
</volist>
<tr><td></td><td style="float:right"><button type="submit">Confirm</button></td></tr>
</table>
</form>
</body>
</html>