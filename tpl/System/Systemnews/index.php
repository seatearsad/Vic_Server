<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Systemnews/index')}" class="on">{pigcms{:L('I_ARTICLES')}</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/add_category')}','{pigcms{:L(\'I_ADD_ARTCAT\')}',800,460,true,false,false,addbtn,'add',true);">{pigcms{:L('I_ADD_ARTCAT')}</a>
				</ul>
			</div>
			{pigcms{:L('I_GENERAL_CATEGORY')}：
			<select name="all_type" id="select_type" style="margin-bottom: 10px">
                <option value="-1">All</option>
                <volist name="all_type" id="type">
                    <option value="{pigcms{$key}" <if condition="$key eq $select_type">selected</if>>{pigcms{$type}</option>
                </volist>
            </select>

			<!--<p>网站首页会显示最前面10条快报。置顶的快报会优先显示，并将悬浮在页面顶部。</p>-->
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>{pigcms{:L('G_ID')}</th>
                                <th>{pigcms{:L('I_GENERAL_CATEGORY')}</th>
								<th>{pigcms{:L('C_CATEGORYNAME')}</th>
								<th>{pigcms{:L('I_CONTENT_LIST')}</th>
								<th>{pigcms{:L('I_LISTING_ORDER')}</th>
								<th>{pigcms{:L('G_STATUS')}</th>
								<th class="textcenter">{pigcms{:L('E_ACTION')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($category)">
								<volist name="category" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
                                        <td>{pigcms{$all_type[$vo['type']]}</td>
										<td>{pigcms{$vo.name}</td>
										<td><a href="{pigcms{:U('Systemnews/news',array('category_id'=>$vo['id']))}">{pigcms{:L('I_VIEW_CONTENT')}({pigcms{$vo.count})</a></td>
										<td>{pigcms{$vo.sort}</td>
										<td><if condition="$vo['status'] eq 1"><font color="green">{pigcms{:L('I_ENABLE1')}</font><else/><font color="red">{pigcms{:L('I_DISABLE3')}</font></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_category',array('id'=>$vo['id']))}','{pigcms{:L(\'BASE_EDIT\')}',800,460,true,false,false,editbtn,'edit',true);">{pigcms{:L('BASE_EDIT')}</a> | <a href="javascript:void(0);" class="delete_row" parameter="category_id={pigcms{$vo.id}" url="{pigcms{:U('Systemnews/del',array('category_id'=>$vo['id']))}">{pigcms{:L('BASE_DELETE')}</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="9">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script>
    $('#select_type').change(function () {
        var type = $(this).val();
        if(type != -1)
            location.href = "{pigcms{:U('Systemnews/index')}&type="+type;
        else
            location.href = "{pigcms{:U('Systemnews/index')}";
    });
</script>
<include file="Public:footer"/>