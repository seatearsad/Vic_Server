<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<a href="{pigcms{:U('Index/profile')}" class="on">{pigcms{:L('J_CUSTOMIZED_MENU')}</a>
		</div>
		<form method="post" id="myform" action="{pigcms{:U('amend_profile')}" refresh="true" onclick='javascript:return submitcheck();'>
			<input type="hidden" class="input-text" name="system_menu" value=""/>
			<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
				<tr>
					<th  width="120">{pigcms{:L('B_USERNAME')}：</td>
					<td>{pigcms{$admin.account}</th>
				</tr>
				<tr>
					<th  width="120">{pigcms{:L('B_FULLNAME')}：</th>
					<td><input type="text" class="input-text"  name="realname" value="{pigcms{$admin.realname}" validate="required:true" /></td>
				</tr>
				<tr>
					<th>{pigcms{:L('B_EMAIL')}：</th>
					<td><input type="text" class="input-text"  name="email" value="{pigcms{$admin.email}" validate="required:true,email:true,minlength:1,maxlength:40" /></td>
				</tr>
				<!--tr>
					<th>Q Q：</th>
					<td><input type="text" class="input-text"  name="qq" value="{pigcms{$admin.qq}" validate="required:true,qq:true" /></td>
				</tr-->
                <input type="hidden" class="input-text"  name="qq" value="{pigcms{$admin.qq|default='123'}" />
				<tr>
					<th>{pigcms{:L('B_PHONE')}：</th>
					<td><input type="text" class="input-text"  name="phone" value="{pigcms{$admin.phone}"  validate="required:true,mobile:true" /></td>
				</tr>
				<tr>
					<th>{pigcms{:L('B_MENUORDER')}：</th>
					<td>
						{pigcms{:L('B_MODES')}<br/>
						<volist name="system_menu" id="vv" key="k">
							<div style="margin-top:10px;width:30%;float:left;">{pigcms{$vv['name']} <input type="number" min="0" class="input-text input1" name="{pigcms{$vv.id}" value="{pigcms{$sort_menus_son[$vv['id']]}" /></div>
							<if condition="$k%3 eq 0"><br /></if>
						</volist>
					</td>
				</tr>
			</table>
			<div class="btn">
				<input TYPE="submit" id="submit" name="dosubmit" value="{pigcms{:L('BASE_SUBMIT')}" class="button" />
				<input type="reset" value="{pigcms{:L('BASE_CANCEL')}" class="button" />
			</div>
		</form>
	</div>
	<script type="text/javascript">
		function submitcheck(){
			var system_menu	=	'';
			$(".input1").each(function(){
				if(this.value){
					system_menu	+=	this.name+','+this.value+';';
				}
		    });
		    system_menu=system_menu.substring(0,system_menu.length-1);
			$("input:[name$='system_menu']").val(system_menu);
		}
	</script>
<include file="Public:footer"/>