<include file="Public:header"/>
	<form id="myform" method="post" action="" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="90">Name</th>
				<th width="90">Phone</th>
<!-- 				<th width="90">常驻地址距离</th> -->
				<th width="90">Courier Assignment Distance</th>
				<th width="90">Click</th>
			</tr>
			<volist name="users" id="row">
			<tr>
				<th width="90">{pigcms{$row['name']}</th>
				<th width="90">{pigcms{$row['phone']}</th>
<!-- 				<th width="90">{pigcms{$row['range']}</th> -->
				<th width="90">{pigcms{$row['now_range']}</th>
				<td><input type="radio" name="uid" value="{pigcms{$row['uid']}" /></td>
			</tr>
			</volist>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>