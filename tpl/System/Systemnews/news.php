<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Systemnews/index')}">{pigcms{:L('I_ARTICLES')}</a>
					<a href="{pigcms{:U('Systemnews/news',array('category_id'=>$_GET['category_id']))}" class="on">{pigcms{$category_name}</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/add_news',array('category_id'=>$_GET['category_id']))}','Add {pigcms{$category_name}',800,500,true,false,false,addbtn,'add',true);">Add {pigcms{$category_name}</a>
				</ul>
			</div>
			
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Systemnews/news')}" method="get">
							<input type="hidden" name="c" value="Systemnews"/>
							<input type="hidden" name="a" value="news"/>
							{pigcms{:L('F_FILTER')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="title" <if condition="$_GET['searchtype'] eq 'title'">selected="selected"</if>>{pigcms{:L('I_TITLE')}</option>
								<option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>ID</option>
							</select>
							<input type="submit" value="{pigcms{:L('F_SEARCH')}" class="button"/>
						</form>
					</td>
				</tr>
			</table>
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
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>{pigcms{:L('G_ID')}</th>
								<th>{pigcms{:L('I_TITLE')}</th>
								<th>{pigcms{:L('TIME_ADDED_BKADMIN')}</th>
								<th>{pigcms{:L('_BACK_LAST_EDIT_TIME_')}</th>
								<th>{pigcms{:L('I_LISTING_ORDER')}</th>
								<th>{pigcms{:L('G_STATUS')}</th>
								<th class="textcenter">{pigcms{:L('E_ACTION')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($news_list)">
								<volist name="news_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.title}</td>
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
										<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
										<td>{pigcms{$vo.sort}</td>
										<td><if condition="$vo['status'] eq 1"><font color="green">{pigcms{:L('I_ENABLE1')}</font><else/><font color="red">{pigcms{:L('I_DISABLE3')}</font></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_news',array('id'=>$vo['id'],'frame_show'=>true))}','{pigcms{:L(\'BASE_VIEW\')}',1000,640,true,false,false,false,'add',true);">{pigcms{:L('BASE_VIEW')}</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_news',array('id'=>$vo['id']))}','{pigcms{:L(\'BASE_EDIT\')}',800,500,true,false,false,editbtn,'edit',true);">{pigcms{:L('BASE_EDIT')}</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Systemnews/del',array('id'=>$vo['id']))}">{pigcms{:L('BASE_DELETE')}</a></td>
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
<include file="Public:footer"/>