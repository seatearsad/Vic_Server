<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('Event/index')}" class="on">活动列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Event/add')}','创建活动',480,300,true,false,false,addbtn,'add',true);">创建活动</a>|				</ul>			</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col/>							<col/>							<col/>							<col/>							<if condition="$system_session['level'] eq 2 || $system_session['level'] eq 0">								<col width="180" align="center"/>							</if>						</colgroup>						<thead>							<tr>								<th>编号</th>								<th>名称</th>								<th>活动类型</th>								<th>开始时间</th>								<th>结束时间</th>                                <th>状态</th>                                <th>城市</th>								<th>优惠券列表</th>								<if condition="$system_session['level'] eq 2 || $system_session['level'] eq 0">									<th class="textcenter">操作</th>								</if>							</tr>						</thead>						<tbody>							<if condition="is_array($event_list)">								<volist name="event_list" id="vo">									<tr>										<td>{pigcms{$vo.id}</td>										<td>{pigcms{$vo.name}</td>										<td>{pigcms{$vo.type_name}</td>										<td>                                            <if condition="$vo['begin_time'] eq 0">                                                不限                                            <else />                                                {pigcms{$vo.begin_time|date='Y-m-d',###}                                            </if>                                        </td>										<td>                                            <if condition="$vo['end_time'] eq 0">                                                不限                                                <else />                                                {pigcms{$vo.end_time|date='Y-m-d',###}                                            </if>                                        </td>                                        <td>                                            {pigcms{$vo.status_name}                                        </td>                                        <td>                                            <if condition="$vo['type'] neq 3">                                                -                                            <else />                                                {pigcms{$vo.city_name}                                            </if>                                        </td>										<td><a href="{pigcms{:U('Event/coupon_list',array('id'=>$vo['id']))}">优惠券列表</a></td>										<if condition="$system_session['level'] eq 2 || $system_session['level'] eq 0">											<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Event/edit',array('id'=>$vo['id']))}','编辑活动分类',480,370,true,false,false,editbtn,'add',true);">编辑</a> <!--| <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.activity_id}" url="{pigcms{:U('Activity/del')}">删除</a--></td>										</if>									</tr>								</volist>							<else/>								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><include file="Public:footer"/>