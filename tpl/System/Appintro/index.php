<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="javascript:void(0);" class="on">{pigcms{:L('I_LIST_INFORMATION')}</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appintro/add')}','{pigcms{:L(\'I_ADD_INFORMATION\')}',800,460,true,false,false,addbtn,'add',true);">{pigcms{:L('I_ADD_INFORMATION')}</a>
				</ul>
			</div>
			<div class="table-list">
				<table width="100%" cellspacing="0">
					<colgroup>
						<col/>
						<col/>
					
						<col width="180" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>{pigcms{:L('G_ID')}</th>
							<th>{pigcms{:L('G_NAME')}</th>
						
							<th class="textcenter">{pigcms{:L('E_ACTION')}</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($intro)">
							<volist name="intro" id="vo">
								<tr>
									<td>{pigcms{$vo.id}</td>
									<td>{pigcms{$vo.title}</td>
								
									<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appintro/edit',array('id'=>$vo['id']))}','{pigcms{:L(\'BASE_EDIT\')}',800,460,true,false,false,editbtn,'edit',true);">{pigcms{:L('BASE_EDIT')}</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Appintro/del')}">{pigcms{:L('BASE_DELETE')}</a></td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="4">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="4">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
						</if>
					</tbody>
				</table>
			</div>
		</div>
<include file="Public:footer"/>