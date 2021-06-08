<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('User/recharge_list')}" >{pigcms{:L('F_TOP_UP_LIST')}</a>
					<a href="{pigcms{:U('User/admin_recharge_list')}" class="on">{pigcms{:L('F_CREDITS_ADDED')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('admin_recharge_list')}" method="get">
							<input type="hidden" name="c" value="User"/>
							<input type="hidden" name="a" value="admin_recharge_list"/>
							{pigcms{:L('F_SEARCH_ADMIN')}:
							<select name="admin_id">
								<option value="0">All</option>
								<volist name="admin_list" id="vo">
									<option value="{pigcms{$vo.id}">{pigcms{$vo.realname}</option>
								</volist>
							</select>
							<font color="#000">{pigcms{:L('F_SEARCH_DATE')}：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							<input type="submit" value="{pigcms{:L('F_SEARCH')}" class="button"/>　　

							<!--支付状态：
							<select name="status" id="status">
								<option value="-1" <if condition="$_GET['status'] eq -1">selected="selected"</if>>全部</option>
								<option value="1" <if condition="$_GET['status'] eq 1">selected="selected"</if>>已支付</option>
								<option value="0" <if condition="$_GET['status'] eq 0">selected="selected"</if>>未支付</option>
							</select>-->
						</form>
					</td>
				</tr>
			</table>

			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<style>
					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}
					</style>
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>{pigcms{:L('F_ORDER_ID')}</th>
								<th>{pigcms{:L('F_AMOUNT')}</th>
								<th>{pigcms{:L('F_USER')}</th>
								<th>Admin</th>
								<th>{pigcms{:L('F_USER_INFO')}</th>
								<th>{pigcms{:L('F_TIME')}</th>
								<th class="textcenter">{pigcms{:L('F_ACTION')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($recharge_list)">
								<volist name="recharge_list" id="vo">
									<tr>
										<td>{pigcms{$vo.pigcms_id}</td>
										<td><if condition="$vo.type eq 1">{pigcms{:L('F_ADD')}: <else />{pigcms{:L('F_LESS')}: </if>${pigcms{$vo.money}</td>

										<td><if condition="$vo.nickname">{pigcms{$vo.nickname}<else />{pigcms{$vo.phone}</if></td>
										<td>{pigcms{$vo.realname}</td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','{pigcms{:L(\'F_EDIT_INFO\')}',680,560,true,false,false,editbtn,'edit',true);">{pigcms{:L('F_USER_INFO')}</a>
										</td>
										<td>
											{pigcms{$vo['time']|date='Y-m-d H:i:s',###}<br/>
											
										</td>
										<td class="textcenter">{pigcms{:str_replace("管理员后台操作","Backend Operated by ",$vo['desc'])}</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="7">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script>
$(function(){
	$('#status').change(function(){
		location.href = "{pigcms{:U('User/recharge_list')}&status=" + $(this).val();
	});
});

</script>
<include file="Public:footer"/>