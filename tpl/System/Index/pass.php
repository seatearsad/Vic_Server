<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<a href="{pigcms{:U('Index/pass')}" class="on">{pigcms{:L('_BACK_CHANGE_PASS_')}</a>
			</div>
			<form id="myform" method="post" action="{pigcms{:U('Index/amend_pass')}" refresh="true">
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
					<tr>
						<th width="100">{pigcms{:L('_BACK_OLD_PASS_')}：</th>
						<td><input type="password" class="input-text" name="old_pass"/></td>
					</tr>
					<tr>
						<th>{pigcms{:L('_BACK_NEW_PASS_')}：</th>
						<td><input type="password" class="input-text"  name="new_pass" id="password" validate="required:true,minlength:5,maxlength:20"/></td>
					</tr>
					<tr>
						<th>{pigcms{:L('_BACK_CONFIRM_PASS_')}：</th>
						<td><input type="password" class="input-text"  name="re_pass" validate="required:true,equalTo:'#password'"/></td>
					</tr>
				</table>
				<div class="btn">
					<input type="submit"  name="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}" class="button" />
					<input type="reset"  value="{pigcms{:L('_BACK_CANCEL_')}" class="button" />
				</div>
			</form>
		</div>
<include file="Public:footer"/>