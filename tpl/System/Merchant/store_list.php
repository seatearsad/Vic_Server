<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('Merchant/index')}" class="on">{pigcms{:L('E_STORE_STAT_LIST')}</a>				</ul>			</div>			<table class="search_table" width="100%">				<tr>					<td style="width:50%;">						<form action="{pigcms{:U('Merchant/store_list')}" method="get">							<input type="hidden" name="c" value="Merchant"/>							<input type="hidden" name="a" value="store_list"/>							{pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>							<select name="searchtype">								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('E_STORE_NAME')}</option>								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('E_STORE_NUMB')}</option>								<option value="mer_id" <if condition="$_GET['searchtype'] eq 'mer_id'">selected="selected"</if>>{pigcms{:L('G_STORE_ID')}</option>							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							{pigcms{:L('E_STORE_STATUS')}: <select name="searchstatus">								<option value="0" <if condition="$_GET['searchstatus'] eq 0">selected="selected"</if>>{pigcms{:L('_BACK_ALL_')}</option>								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>{pigcms{:L('_BACK_NORMAL_')}</option>								<option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>{pigcms{:L('_BACK_CLOSED_')}</option>							</select>                            <if condition="$system_session['level'] neq 3">                                City:                                <select name="city_id" id="city_select">                                    <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>                                    <volist name="city" id="vo">                                        <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>                                    </volist>                                </select>                            </if>							<input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>						</form>					</td>                    <if condition="$system_session['level'] neq 3">					<td>						<b></b>					</td>                    </if>				</tr>			</table>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup><col> <col> <col> <col><col><col>                            <col width="240" align="center"> </colgroup>						<thead>							<tr>								<th>{pigcms{:L('_BACK_CODE_')}</th>								<th>{pigcms{:L('E_STORE_NAME')}</th>								<th>{pigcms{:L('E_STORE_NUMB')}</th>								<th>{pigcms{:L('E_LASTUP')}</th>								<th class="textcenter">{pigcms{:L('_BACK_VISIT_')}</th>								<!--th class="textcenter">{pigcms{:L('_BACK_CLICK_RATE_')}</th>								<th class="textcenter">{pigcms{:L('_BACK_FOLLOWER_NUM_')}</th-->								<th width="10%">{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>								<!--th class="textcenter">微店账单</th-->								<th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>							</tr>						</thead>						<tbody>							<if condition="is_array($store_list)">								<volist name="store_list" id="vo">									<tr>										<td>{pigcms{$vo.store_id}</td>										<td>{pigcms{$vo.name}</td>										<td>{pigcms{$vo.phone}</td>										<td><if condition="$vo['last_time']">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}<else/>N/A</if></td>										<td class="textcenter"><if condition="$vo['status'] eq 1 OR $vo['status'] eq 3"><a href="{pigcms{:U('Merchant/merchant_login',array('mer_id'=>$vo['mer_id']))}" class="__full_screen_link" target="_blank">{pigcms{:L('_BACK_VISIT_')}</a><else/><a href="javascript:alert('商户状态不正常，无法访问！请先修改商户状态。');" class="__full_screen_link">{pigcms{:L('J_ACESS')}</a></if></td>										<td>                                            <if condition="$vo['status'] eq 1">                                                <font color="green">{pigcms{:L('_BACK_ACTIVE_')}</font><elseif condition="$vo['status'] eq 2"/><font color="red">{pigcms{:L('_BACK_PENDING_')}</font><elseif condition="$vo['status'] eq 3"/><font color="red">欠款</font><else/><font color="red">{pigcms{:L('_BACK_CLOSED_')}</font>                                            </if>                                            <if condition="$vo['store_is_close'] neq 0">                                                <font color="red">({pigcms{:L('_STORE_ON_HOLIDAY_')})</font>                                            </if>                                            <if condition="$vo['all_zero']">                                                <font color="red">{pigcms{:L('E_STORETIME0')}</font>                                            </if>                                        </td>										<!--td class="textcenter"><a href="{pigcms{:U('Merchant/weidian_order',array('mer_id'=>$vo['mer_id']))}">微店账单</a></td-->										<td class="textcenter">                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$vo['store_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_VIEW_\')}',620,480,true,false,false,false,'detail',true);">{pigcms{:L('_BACK_VIEW_')}</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$vo['store_id']))}','{pigcms{:L(\'_BACK_EDIT_STORE_INFO_\')}',620,480,true,false,false,editbtn,'store_add',true);">{pigcms{:L('_BACK_EDIT_')}</a> | <a href="javascript:void(0);" class="delete_row" parameter="store_id={pigcms{$vo.store_id}" url="{pigcms{:U('Merchant/store_del')}">{pigcms{:L('_BACK_DEL_')}</a>										</td>									</tr>								</volist>                                <tr><td class="textcenter pagebar" <if condition="$system_session['level'] neq 3">colspan="7"<else />colspan="7"</if>>{pigcms{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" <if condition="$system_session['level'] neq 3">colspan="7"<else />colspan="7"</if>>{pigcms{:L('_BACK_EMPTY_')}</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><script>    var city_id = $('#city_select').val();    $('#city_select').change(function () {        city_id = $(this).val();        window.location.href = "{pigcms{:U('Merchant/store_list')}" + "&city_id="+city_id;    });</script><include file="Public:footer"/>