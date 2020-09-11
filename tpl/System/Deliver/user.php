<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}" class="on">{pigcms{:L('_BACK_COURIER_MANA_')}</a>

                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','{pigcms{:L(\'_BACK_ADD_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_ADD_COURIER_')}</a>
					<!--a href="{pigcms{:U('Config/index',array('galias'=>'deliver','header'=>'Deliver/header'))}">配送配置</a-->
                    <if condition="$system_session['level'] neq 3">
                    <a href="{pigcms{:U('Deliver/rule')}">配送配置</a>
                    </if>
                    <a href="{pigcms{:U('Deliver/map')}">{pigcms{:L('_BACK_COURIER_MONI_')}</a>
                    <a href="{pigcms{:U('Deliver/schedule')}">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Deliver/user')}" method="get">
							<input type="hidden" name="c" value="Deliver"/>
							<input type="hidden" name="a" value="user"/>
							{pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>{pigcms{:L('_BACK_USER_ID_')}</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('_BACK_NICKNAME_')}</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_PHONE_NUM_')}</option>
							</select>
							<input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>
						</form>
                        <if condition="$system_session['level'] neq 3">
                        City:
                        <select name="searchtype" id="city_select">
                            <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>
                            <volist name="city" id="vo">
                                <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                            </volist>
                        </select>
                        </if>
                        <a href="{pigcms{:U('Deliver/export_deliver')}" class="button" style="float:right;margin-right: 10px;">
                            导出送餐员
                        </a>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>ID</th>
								<th>{pigcms{:L('_BACK_NICKNAME_')}</th>
								<th>{pigcms{:L('_BACK_PHONE_NUM_')}</th>
								<th>{pigcms{:L('_BACK_DEFAULT_ADD_')}</th>
								<th>{pigcms{:L('_BACK_LAST_EDIT_TIME_')}</th>
								<th>{pigcms{:L('_BACK_DELIVERY_AREA_')}</th>
								<th class="textcenter">{pigcms{:L('_BACK_STATUS_')}</th>
								<th class="textcenter">{pigcms{:L('_BACK_TOTAL_DELIVERY_')}</th>
								<th class="textcenter">{pigcms{:L('_BACK_OVERVIEW_')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($user_list)">
								<volist name="user_list" id="vo">
									<tr>
										<td>{pigcms{$vo.uid}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.phone}</td>
										<td>{pigcms{$vo.site}</td>
										<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
										<td>{pigcms{$vo.range}</td>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">{pigcms{:L('_BACK_NORMAL_')}</font><else/><font color="red">{pigcms{:L('_BACK_BANNED_')}</font></if></td>
										<td class="textcenter">{pigcms{$vo.num}</td>
										<td class="textcenter">
										<a style="width: 60px;" class="" href="{pigcms{:U('Deliver/count_log',array('uid'=>$vo['uid']))}">{pigcms{:L('_BACK_HISTORY_RECORD_')}</a>　 | 　
										<a style="width: 60px;" class="" href="{pigcms{:U('Deliver/log_list',array('uid'=>$vo['uid']))}">{pigcms{:L('_BACK_HISTORY_DELI_')}</a>　 | 　
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_edit',array('uid'=>$vo['uid']))}','{pigcms{:L(\'_BACK_EDIT_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_EDIT_')}</a>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="10">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script>
    var city_id = $('#city_select').val();
    $('#city_select').change(function () {
        city_id = $(this).val();
        window.location.href = "{pigcms{:U('Deliver/user')}" + "&city_id="+city_id;
    });
</script>
<include file="Public:footer"/>