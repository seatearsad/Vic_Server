<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('Merchant/reply')}" class="on">{pigcms{:L('E_ORDER_REVIEWS')}</a>|				</ul>			</div>			<table class="search_table" width="100%">				<tr>					<td style="width:50%;">						<form action="{pigcms{:U('Merchant/reply')}" method="get">							<input type="hidden" name="c" value="Merchant"/>							<input type="hidden" name="a" value="reply"/>							{pigcms{:L('E_FILTER')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>							<select name="searchtype">								<option value="m_name" <if condition="$_GET['searchtype'] eq 'm_name'">selected="selected"</if>>{pigcms{:L('E_MERCHANTNAME')}</option>								<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{:L('E_STORENAME')}</option>								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('E_USERNAME')}</option>								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('E_USERPHONE')}</option>							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;							<input type="submit" value="{pigcms{:L('E_SEARCH')}" class="button"/>						</form>					</td>				</tr>			</table>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<thead>							<tr>								<th>{pigcms{:L('E_REVIEWID')}</th>								<!--th>商户名称</th-->								<th>{pigcms{:L('E_STORENAME')}</th>								<th>{pigcms{:L('E_USERNAME')}</th>								<th>{pigcms{:L('E_USERPHONE')}</th>								<!--th>评论类型</th-->								<th>{pigcms{:L('E_REVIEWTIME')}</th>								<th>{pigcms{:L('E_REST_SCORE')}</th>								<th>{pigcms{:L('E_REST_REVIEW')}</th>                                <th>{pigcms{:L('E_COURIER_SCORE')}</th>                                <th>{pigcms{:L('E_COURIERREVIEW')}</th>								<th class="textcenter">{pigcms{:L('E_ACTION')}</th>							</tr>						</thead>						<tbody>							<if condition="is_array($reply_list)">								<volist name="reply_list" id="vo">									<tr>										<td>{pigcms{$vo.pigcms_id}</td>										<!--td>{pigcms{$vo.m_name}</td-->										<td>{pigcms{$vo.s_name}</td>										<td>{pigcms{$vo.nickname}</td>										<td>{pigcms{$vo.phone}</td>										<!--td><if condition="$vo['order_type'] eq 0">团购<elseif condition="$vo['order_type'] eq 1" />餐饮<elseif condition="$vo['order_type'] eq 2" />预约<elseif condition="$vo['order_type'] eq 3" />快店</if></td-->										<td><if condition="$vo['add_time']">{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}<else/>None</if></td>										<td class="textcenter">{pigcms{$vo.score}</td>										<td style="width:100px">{pigcms{$vo.comment}</td>                                        <td class="textcenter"><if condition="$vo['score_deliver'] neq -1">{pigcms{$vo.score_deliver}</if></td>                                        <td style="width:100px">{pigcms{$vo.comment_deliver}</td>										<td class="textcenter">										    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/replyinfo',array('reply_id'=>$vo['pigcms_id'],'frame_show'=>true))}','{pigcms{:L(\'E_VIEW\')}',520,470,true,false,false,false,'detail',true);">{pigcms{:L('E_VIEW')}</a> |										    <a href="javascript:void(0);" class="delete_row" parameter="reply_id={pigcms{$vo.pigcms_id}" url="{pigcms{:U('Merchant/replydel')}">{pigcms{:L('E_DELETE')}</a> |                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_ORDER_DETAIL_\')}',920,520,true,false,false,false,'detail',true);">{pigcms{:L('E_VIEWORDER')}</a>                                        </td>									</tr>								</volist>								<tr><td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="10">{pigcms{:L('_BACK_EMPTY_')}</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><include file="Public:footer"/>