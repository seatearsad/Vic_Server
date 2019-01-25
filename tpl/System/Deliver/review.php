<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}" class="on">配送员审核</a>|
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Deliver/user')}" method="get">
							<input type="hidden" name="c" value="Deliver"/>
							<input type="hidden" name="a" value="user"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>用户ID</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>昵称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>手机号</option>
							</select>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>ID</th>
								<th>First Name</th>
                                <th>Last Name</th>
								<th>手机号</th>
								<th>邮箱</th>
								<th>注册时间</th>
								<th class="textcenter">状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($user_list)">
								<volist name="user_list" id="vo">
									<tr>
										<td>{pigcms{$vo.uid}</td>
										<td>{pigcms{$vo.name}</td>
                                        <td>{pigcms{$vo.family_name}</td>
										<td>{pigcms{$vo.phone}</td>
										<td>{pigcms{$vo.email}</td>
										<td>{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</td>
										<td class="textcenter">
                                            <if condition="$vo['reg_status'] eq 1">
                                                <font color="red">完成注册</font>
                                            </if>
                                            <if condition="$vo['reg_status'] eq 2">
                                                <font color="red">完成第一步</font>
                                            </if>
                                            <if condition="$vo['reg_status'] eq 3">
                                                <font color="green">禁止</font>
                                            </if>
                                        </td>
										<td class="textcenter">　
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_view',array('uid'=>$vo['uid']))}','查看信息',680,560,true,false,false,editbtn,'edit',true);">编辑</a>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="10">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>