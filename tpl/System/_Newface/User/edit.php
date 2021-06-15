<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <div>
                            <form id="myform" method="post" action="{pigcms{:U('User/amend')}" frame="true"
                                  refresh="true" autocomplete="off">

                                <input type="hidden" name="uid" value="{pigcms{$now_user.uid}"/>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">ID</label>
                                    <div class="col-sm-9">{pigcms{$now_user.uid}</div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_NICKNAME_')}</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="nickname"
                                                                 size="20"
                                                                 validate="maxlength:50,required:true"
                                                                 value="{pigcms{$now_user.nickname}"/></div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_PHONE_NUM_')}</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="phone" size="20"
                                                                 validate="number:true" value="{pigcms{$now_user.phone}"
                                                                 autocomplete="off"/></div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_B_D_LOGIN_KEY1_')}</label>
                                    <div class="col-sm-9"><input type="password" class="form-control" name="pwd"
                                                                 size="20"
                                                                 value="" tips="{pigcms{:L('K_DNFIINM')}"
                                                                 autocomplete="off"/></div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_GENDER_')}</label>
                                    <div class="col-sm-9"><span class="cb-enable"><label
                                                    class="cb-enable <if condition="
                                                    $now_user['sex'] eq 1">selected</if>
                                            "><span>{pigcms{:L('_BACK_MALE_')}</span><input type="radio" name="sex"
                                                                                            value="1"  <if
                                                    condition="$now_user['sex'] eq 1">checked="checked"</if>/></label></span>
                                        <span class="cb-disable"><label class="cb-disable <if condition=" $now_user['sex'] eq 2">selected</if>
                                            "><span>{pigcms{:L('_BACK_FEMALE_')}</span><input type="radio"
                                                                                              name="sex" value="2"  <if
                                                    condition="$now_user['sex'] eq 2">checked="checked"</if>/></label></span>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STATUS_')}</label>
                                    <div class="col-sm-9"><span class="cb-enable"><label
                                                    class="cb-enable <if condition="
                                                    $now_user['status'] eq 1">selected</if>
                                            "><span>{pigcms{:L('_BACK_NORMAL_')}</span><input type="radio"
                                                                                              name="status"
                                                                                              value="1"  <if
                                                    condition="$now_user['status'] eq 1">checked="checked"</if>/></label></span>
                                        <if condition="$now_user['status'] eq 2">
                                                <span class="cb-disable"><label class="cb-disable selected"><span>{pigcms{:L('_BACK_PENDING_')}</span><input
                                                                type="radio" name="status" value="2" checked="checked"/></label></span>
                                            <elseif condition="$now_user['status'] eq 0"/>
                                            <span class="cb-disable"><label class="cb-disable selected"><span>{pigcms{:L('_BACK_BANNED_')}</span><input
                                                            type="radio" name="status" value="0"
                                                            checked="checked"/></label></span>
                                            <else/>
                                            <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('_BACK_BANNED_')}</span><input
                                                            type="radio" name="status" value="0"/></label></span>
                                        </if>
                                    </div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('NAME_BKADMIN')}</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="truename"
                                                                 value="{pigcms{$now_user.truename}"/></div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_EMAIL_')}</label>
                                    <div class="col-sm-9"><input type="text" class="form-control" name="email"
                                                                 value="{pigcms{$now_user.email}"/></div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_ND_ADDRESS_')}</label>
                                    <div class="col-sm-9"><input type="text" class="form-control"
                                                                 name="youaddress"
                                                                 value="{pigcms{$now_user.youaddress}"
                                        /></div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_REG_TIME_')}</label>
                                    <div class="col-sm-9">{pigcms{$now_user.add_time|date='Y-m-d H:i:s',###}</div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_REG_IP_')}</label>
                                    <div class="col-sm-9">{pigcms{$now_user.add_ip|long2ip=###}</div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_LAST_VTIME_')}</label>
                                    <div class="col-sm-9"> {pigcms{$now_user.last_time|date='Y-m-d H:i:s',###}</div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_LAST_VIP_')}</label>
                                    <div class="col-sm-9"> {pigcms{$now_user.last_ip|long2ip=###}</div>
                                </div>

                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('F_ADMIN_CODE')}</label>
                                    <div class="col-sm-6"><input type="text" class="form-control"
                                                                 name="user_rechange_code"
                                                                 id="user_rechange_code" value="" style="width: 30%"/>
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="send_code" type="button"
                                                style="margin-left: 10px;height: 30px;">Send
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group  row"
                                <php>if($can_recharge==0 || $config['open_frozen_money']==0){echo
                                    'style="display:none"';}
                                </php>
                                >
                                <label class="col-sm-3 col-form-label">冻结时间</label>
                                <div class="col-sm-9"> 冻结时间：
                                    <input type="text" class="form-control" name="frozen_time"
                                           id="d4311" value="<if condition="
                                           $now_user.frozen_time gt 0">{pigcms{$now_user.frozen_time}</if>"
                                    onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
                                    解冻时间：
                                    <input type="text" class="form-control" name="free_time"
                                           id="d4311" value="<if condition=" $now_user.free_time gt 0">{pigcms{$now_user.free_time}</if>
                                    " onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})" tips="冻结时间"/></div>

                                </div>

                                <th width="15%">冻结时间</th>
                                <td width="85%" colspan="3">
                                    <div style="height:30px;line-height:24px;">
                                        冻结时间：
                                        <input type="text" class="input-text" name="frozen_time"
                                               style="width:120px;" id="d4311" value="<if condition="
                                               $now_user.frozen_time gt 0">{pigcms{$now_user.frozen_time}</if>"
                                        onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
                                        解冻时间：
                                        <input type="text" class="input-text" name="free_time" style="width:120px;"
                                               id="d4311" value="<if condition=" $now_user.free_time gt 0">{pigcms{$now_user.free_time}</if>
                                        " onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})" tips="冻结时间"/>
                                    </div>
                                </td>
                                </tr>
                                <tr
                                <php>if($can_recharge==0 || $config['open_frozen_money']==0){echo
                                    'style="display:none"';}
                                </php>
                                >
                                <th width="15%">冻结理由</th>
                                <td width="85%" colspan="3">
                                    <div style="height:30px;line-height:24px;">
                                        <input type="text" class="input" name="frozen_reason" size="40"
                                               value="{pigcms{$now_user.frozen_reason}" tips="冻结理由"/>
                                    </div>
                                </td>
                                </tr>

                                <!--tr>
                                    <th width="15%">实体卡</th>
                                    <td width="85%" colspan="3"><div style="height:30px;line-height:24px;">实体卡卡号<input type="text" class="input" name="cardid" size="16"  value="{pigcms{$now_user.cardid}" validate="number:true" tips="此处填写实体卡卡号"/>实体卡余额<input type="text" class="input" name="balance_money" size="10" value="{pigcms{$balance_money}" validate="number:true" tips="此处填写实体卡余额"/></div></td>
                                </tr>
                                <tr>
                                    <th width="15%">等级</th>
                                    <td width="85%" colspan="3">
                                    <div style="height:30px;line-height:24px;">现在等级：<php>if(isset($levelarr[$now_user['level']])){ echo $levelarr[$now_user['level']]['lname'];}else{echo '暂无等级';}</php> &nbsp;&nbsp;&nbsp;&nbsp;
                                    <if condition="!empty($levelarr)">
                                    请设定等级：&nbsp;&nbsp;
                                    <select name="level" style="width:100px;">
                                    <option value="0">无</option>
                                    <volist name="levelarr" id="vo">
                                    <option value="{pigcms{$vo['level']}" <if condition="$now_user['level'] eq $vo['level']"> selected="selected"</if>>{pigcms{$vo['lname']}</option>
                                    </volist>
                                    </select>
                                    </if>
                                    &nbsp;&nbsp;</div>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="15%">记录表</th>
                                    <td width="85%" colspan="3">
                                        <div style="height:30px;line-height:24px;">
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/money_list',array('uid'=>$now_user['uid']))}','余额记录列表',680,560,true,false,false,null,'money_list',true);">余额记录</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/score_list',array('uid'=>$now_user['uid']))}','{pigcms{$config.score_name}记录列表',680,560,true,false,false,null,'score_list',true);">{pigcms{$config.score_name}记录</a>
                                        </div>
                                    </td>
                                </tr-->
                                <script>
                                    var balance_show = "{pigcms{:L('_BACK_BALANCE_SHOW_')}";
                                    var now_money = '{pigcms{$now_user.now_money|floatval=###}';
                                    $('#send_code').click(function () {
                                        if ($('#user_rechange_code').val() != '') {
                                            $.post("{pigcms{:U('User/send_user_code')}", {'code': $('#user_rechange_code').val()}, function (data) {
                                                if (data.status == 1) {
                                                    var html = '<tr>' +
                                                        '<th width="15%">' + balance_show + '</th>' +
                                                        '<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">{pigcms{:L('
                                                    F_CURRENT_BALANCE
                                                    ')}：$' + now_money + ' &nbsp;&nbsp;&nbsp;&nbsp;<select name="set_money_type"><option value="1">{pigcms{:L(\'F_ADD\')}</option><option value="2">{pigcms{:L(\'F_LESS\')}</option></select>&nbsp;&nbsp;<input type="text" class="input" name="set_money" size="10" validate="number:true" tips="此处填写增加或减少的额度，不是将余额变为此处填写的值"/></div></td>' +
                                                    '<input type="hidden" name="user_code_curr" value="' + $('#user_rechange_code').val() + '">'
                                                    '</tr>'

                                                    $('.frame_form').append(html);
                                                    $('#code_tr').hide();
                                                } else {
                                                    alert(data.msg);
                                                }
                                            }, 'json');
                                        } else {
                                            alert('Please Input Code');
                                        }
                                    });
                                </script>
                                <div class="btn tutti_hidden_obj">
                                    <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
                                    <input type="reset" value="取消" class="button"/>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <include file="Public:footer_inc"/>