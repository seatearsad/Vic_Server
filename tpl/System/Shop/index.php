<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>										<a href="{pigcms{:U('Shop/index')}" <if condition="!$category">class="on"</if>>分类列表</a>|					<if condition="$category">					<a href="{pigcms{:U('Shop/index',array('parentid'=>$category['cat_id']))}" class="on">{pigcms{$category.cat_name} - 子分类列表</a>|					</if>					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_add', array('parentid' => $parentid))}','添加分类',480,360,true,false,false,addbtn,'add',true);">添加<if condition="$category">子<else />主</if>分类</a>				</ul>			</div>            <table class="search_table" width="100%">                <tr>                    <td>                        <if condition="$system_session['level'] neq 3 and $parentid eq 0">                            City:                            <select name="searchtype" id="city_select">                                <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>                                <volist name="city" id="vo">                                    <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>                                </volist>                            </select>                        </if>                    </td>                </tr>            </table>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col/>							<col/>							<col/>							<col width="180" align="center"/>						</colgroup>						<thead>							<tr>								<th>编号</th>								<th>排序</th>								<th>名称</th>								<th>短标记(url)</th>								<if condition="empty($parentid)">									<th>查看子分类</th>								<else/>									<th>表单填写项</th>								</if>								<th>状态</th>                                <th>类型</th>                                <th>城市</th>                                <th>店铺数量</th>								<th>店铺不营业时显示状态</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="is_array($category_list)">								<volist name="category_list" id="vo">									<tr>										<td>{pigcms{$vo.cat_id}</td>										<td>{pigcms{$vo.cat_sort}</td>										<td>{pigcms{$vo.cat_name}</td>										<td>{pigcms{$vo.cat_url}</td>										<if condition="empty($parentid)">											<td><a href="{pigcms{:U('Shop/index',array('parentid'=>$vo['cat_id']))}">查看子分类</a></td>										<else/>											<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('cue_field',array('cat_id'=>$vo['cat_id']))}','表单填写项',580,420,true,false,false,false,'detail',true);">表单填写项</a></td>										</if>										<td><if condition="$vo['cat_status'] eq 1"><font color="green">启用</font><elseif condition="$vo['cat_status'] eq 2"/><font color="red">待审核</font><else/><font color="red">关闭</font></if></td>                                        <td>                                            <if condition="$vo['cat_type'] eq 0">                                                <font color="green">普通分类</font>                                            <else/>                                                <font color="red">推广分类</font>                                            </if>                                        </td>                                        <td>                                            <if condition="$vo['city_id'] eq 0">                                                <font color="gray">{pigcms{$vo['city_name']}</font>                                                <else/>                                                <font color="green">{pigcms{$vo['city_name']}</font>                                            </if>                                        </td>                                        <td>                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_store',array('cat_id'=>$vo['cat_id'], 'parentid'=>$vo['cat_fid']))}','分类店铺',480,360,true,false,false,editbtn,'edit',true);">                                                {pigcms{$vo.store_num}                                            </a>                                        </td>                                        <td><if condition="$vo['show_method'] eq 0"><font color="green">不显示</font><elseif condition="$vo['show_method'] eq 1"/><font color="red">正常显示</font><else/><font color="red">靠后显示</font></if></td>										<td class="textcenter">                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_edit',array('cat_id'=>$vo['cat_id'],'frame_show'=>true))}','查看分类信息',480,360,true,false,false,false,'detail',true);">查看</a> |                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_edit',array('cat_id'=>$vo['cat_id'], 'parentid'=>$vo['cat_fid']))}','编辑分类信息',480,360,true,false,false,editbtn,'edit',true);">编辑</a> |                                            <a href="javascript:void(0);" class="delete_row" parameter="cat_id={pigcms{$vo.cat_id}" url="{pigcms{:U('Shop/cat_del')}">删除</a> |                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/cat_service',array('cat_id'=>$vo['cat_id'], 'parentid'=>$vo['cat_fid']))}','分类服务费',480,260,true,false,false,editbtn,'edit',true);">服务费</a>                                        </td>									</tr>								</volist>							<else/>								<tr><td class="textcenter red" colspan="10">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><script>    var city_id = $('#city_select').val();    $('#city_select').change(function () {        city_id = $(this).val();        window.location.href = "{pigcms{:U('Shop/index', $_GET)}" + "&city_id="+city_id;    });</script><include file="Public:footer"/>