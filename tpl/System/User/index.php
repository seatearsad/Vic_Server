<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('User/index')}" class="on">{pigcms{:L('_BACK_USER_LIST_')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('User/index')}" method="get">
							<input type="hidden" name="c" value="User"/>
							<input type="hidden" name="a" value="index"/>
							{pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>{pigcms{:L('_BACK_USER_ID_')}</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('_BACK_NICKNAME_')}</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_PHONE_NUM_')}</option>
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<font color="#000">{pigcms{:L('_BACK_REG_DATE_')}：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
							{pigcms{:L('_BACK_STATUS_')}:
							<select name="status">
								<option value="" <if condition="$_GET['status'] eq ' '">selected="selected"</if>>{pigcms{:L('_BACK_ALL_')}</option>
								<option value="1" <if condition="$_GET['status'] eq '1'">selected="selected"</if>>{pigcms{:L('_BACK_NORMAL_')}</option>
								<option value="0" <if condition="$_GET['status'] eq '0'">selected="selected"</if>>{pigcms{:L('_BACK_BANNED_')}</option>
							</select>
							<input type="submit" style="margin-right:20px;" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>
							{pigcms{:L('_BACK_USER_BALANCE_')}：$<if condition="$user_balance['count']">{pigcms{$user_balance['count']}<else/>0</if>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{pigcms{:L('_BACK_ACT_U_BAL_')}：$<if condition="$user_balance['open']">{pigcms{$user_balance['open']}<else/>0</if>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							{pigcms{:L('_BACK_BAN_U_BAL_')}：$<if condition="$user_balance['close']">{pigcms{$user_balance['close']}<else/>0</if>
							<a href="{pigcms{:U('User/export',array('begin_time'=>$_GET['begin_time'],'end_time'=>$_GET['end_time']))}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('_BACK_EXPORT_U_')}</a>
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
					</td>
				</tr>
			</table>
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
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
                                <th> </th>
								<th><a href="{pigcms{:U('User/index',array('sort'=>'uid'))}" style="color:blue;">ID</a></th>
								<th>{pigcms{:L('_BACK_NICKNAME_')}</th>
								<th>{pigcms{:L('_BACK_PHONE_NUM_')}</th>
                                <th>Email</th>
								<th><a href="{pigcms{:U('User/index',array('sort'=>'lastTime'))}" style="color:blue;">{pigcms{:L('_BACK_LAST_TIME_')}</a></th>
								<th>{pigcms{:L('_BACK_LAST_LOC_')}</th>
								<th class="textcenter"><a href="{pigcms{:U('User/index',array('sort'=>'money'))}" style="color:blue;">{pigcms{:L('_BACK_BALANCE_SHOW_')}</a></th>
								<!--th class="textcenter"><a href="{pigcms{:U('User/index',array('sort'=>'score'))}" style="color:blue;">{pigcms{:L('_BACK_POINTS_')}</a></th-->
								<th class="textcenter">{pigcms{:L('_BACK_FROM_')}</th>
                                <th class="textcenter"><a href="{pigcms{:U('User/index',array('sort'=>'invi_reg'))}" style="color:blue;">{pigcms{:L('F_REGISTRATION_INVITED')}</a></th>
                                <th class="textcenter"><a href="{pigcms{:U('User/index',array('sort'=>'invi_order'))}" style="color:blue;">{pigcms{:L('F_ORDERS_INVITED')}</a></th>
								<th class="textcenter">{pigcms{:L('_BACK_STATUS_')}</th>
								<th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($user_list)">
								<volist name="user_list" id="vo">
									<tr>
                                        <td>
                                            <input type="checkbox" name="check" value="{pigcms{$vo.uid}" />
                                        </td>
										<td>{pigcms{$vo.uid}</td>
										<td>{pigcms{$vo.nickname}</td>
										<td>{pigcms{$vo.phone}</td>
                                        <td>{pigcms{$vo.email}</td>
										<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
										<td>{pigcms{$vo.last_ip_txt}</td>
										<td class="textcenter">${pigcms{$vo.now_money|floatval=###}</td>
										<!--td class="textcenter">{pigcms{$vo.score_count}</td-->
										<td class="textcenter"><php>if(strpos($vo['source'],'weixin')===1){</php>
										{pigcms{$client.5}
										<php>}elseif(strpos($vo['source'],'wxapp')===1){</php>
										{pigcms{$client.4}
										<php>}else{</php>
										{pigcms{$client[$vo['client']]}
										<php>}</php></td>
                                        <td class="textcenter">{pigcms{$vo.invitation_reg_num}</td>
                                        <td class="textcenter">{pigcms{$vo.invitation_order_num}</td>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">{pigcms{:L('_BACK_NORMAL_')}</font><elseif condition="$vo['status'] eq 2" /><font color="red">{pigcms{:L('_BACK_PENDING_')}</font><else /><font color="red">{pigcms{:L('_BACK_BANNED_')}</font></if></td>
										<td class="textcenter">
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','{pigcms{:L(\'_BACK_EDIT_CUSTOMER_\')}',680,560,true,false,false,editbtn,'edit',true);"><if condition="$vo['status'] eq 2">{pigcms{:L('_BACK_PENDING_')}<else />{pigcms{:L('_BACK_EDIT_')}</if></a>
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/send_coupon',array('uid'=>$vo['uid']))}','{pigcms{:L(\'_BACK_ASS_COUPON_\')}',700,400,true,false,false,'','edit',true)">{pigcms{:L('_BACK_ASS_COUPON_')}</a>
                                        </td>
									</tr>
								</volist>
								<tr>
                                    <td class="textcenter pagebar">
                                        <span style="cursor: pointer" id="select_all">{pigcms{:L('_BACK_SELECT_ALL_')}</span>
                                    </td>
                                    <td class="textcenter pagebar">
                                        <span style="cursor: pointer" id="send_all">{pigcms{:L('_BACK_SEND_GROUP_CON_')}</span>
                                    </td>
                                    <td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td>
                                </tr>
							<else/>
								<tr><td class="textcenter red" colspan="11">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript">
    var city_id = $('#city_select').val();
    $('#city_select').change(function () {
        city_id = $(this).val();
        window.location.href = "{pigcms{:U('User/index')}" + "&city_id="+city_id;
    });

    $("#select_all").click(function(){
        var is_all = true;
        var groupCheckbox=$("input[name='check']");
        for(i=0;i<groupCheckbox.length;i++){
            if(groupCheckbox[i].checked){

            }else{
                is_all = false;
            }
        }
        $("input[name='check']").prop("checked",!is_all);
    });
    $("#send_all").click(function () {
        var ua = "";
        var groupCheckbox=$("input[name='check']");
        for(i=0;i<groupCheckbox.length;i++){
            if(groupCheckbox[i].checked){
                var val =groupCheckbox[i].value;
                if(ua == "")
                    ua = val;
                else
                    ua = ua + "," + val;
            }
        }

        if(ua == "")
            alert("Please select customers!");
        else
            window.top.artiframe("{pigcms{:U('User/send_coupon',array('uid'=>'"+ua+"'))}",'{pigcms{:L(\'_BACK_ASS_COUPON_\')}',700,400,true,false,false,'','edit',true)
    })
</script>
<include file="Public:footer"/>