<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">{pigcms{:L('_BACK_COURIER_MANA_')}</a>|
					<a href="{pigcms{:U('Deliver/count_log',array('uid'=>$user['uid']))}" class="on">【{pigcms{$user['name']}】{pigcms{:L('_BACK_COURIER_OVER_')}</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>{pigcms{:L('_BACK_DATE_')}</th>
								<th>{pigcms{:L('_BACK_ORDERS_DELIVER_')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($count_list)">
								<volist name="count_list" id="vo">
									<tr>
										<td>{pigcms{$vo.today}</td>
										<td>{pigcms{$vo.num}</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="2">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="2">No Data</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>