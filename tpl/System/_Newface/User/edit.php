<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('User/amend')}" frame="true"
                              refresh="true" autocomplete="off">
                            <div class="frame_form">
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

                                <div class="form-group  row" id="code_tr">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('F_ADMIN_CODE')}</label>
                                    <div class="col-sm-6"><input type="text" class="form-control"
                                                                 name="user_rechange_code"
                                                                 id="user_rechange_code" value=""/>
                                    </div>
                                    <div class="col-sm-3">
                                        <button id="send_code" type="button" class="form-control"
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
                                        冻结理由：


                                </div>

                                <div class="form-group  row"
                                    <php>if($can_recharge==0 || $config['open_frozen_money']==0){echo
                                        'style="display:none"';}
                                    </php>
                                >
                                            <label class="col-sm-3 col-form-label">冻结理由</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" name="frozen_reason" size="40"
                                                       value="{pigcms{$now_user.frozen_reason}" tips="冻结理由"/>
                                            </div>
                                </div>
                                <script>
                                    var balance_show = "{pigcms{:L('_BACK_BALANCE_SHOW_')}";
                                    var now_money = '{pigcms{$now_user.now_money|floatval=###}';
                                    $('#send_code').click(function () {
                                        if($('#user_rechange_code').val() != '') {
                                            $.post("{pigcms{:U('User/send_user_code')}", {'code': $('#user_rechange_code').val()}, function (data) {
                                                if(data.status == 1){

                                                    var html = '<div class="form-group  row">' +
                                                        '<label class="col-sm-3 col-form-label">balance_show</label><div class="col-sm-9">'+
                                                        '{pigcms{:L("F_CURRENT_BALANCE")}：$'+now_money+' &nbsp;&nbsp;&nbsp;&nbsp;<select name="set_money_type" class="form-control"><option value="1">{pigcms{:L("F_ADD")}</option><option value="2">{pigcms{:L("F_LESS")}</option></select>&nbsp;&nbsp;<input type="text" class="form-control" name="set_money" size="10" validate="number:true" tips="此处填写增加或减少的额度，不是将余额变为此处填写的值"/>' +
                                                    '<input type="hidden" class="form-control" name="user_code_curr" value="'+$('#user_rechange_code').val()+'">'
                                                    '</div>';

                                                    $('.frame_form').append(html);
                                                    $('#code_tr').hide();
                                                }else{
                                                    alert(data.msg);
                                                }
                                            }, 'json');
                                        }else{
                                            alert('Please Input Code');
                                        }
                                    });
                                </script>
                                <div class="btn tutti_hidden_obj">
                                    <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
                                    <input type="reset" value="取消" class="button"/>
                                </div>

                            </div>
                    </form>
                    </div>
                </div>
            </div>
            <include file="Public:footer_inc"/>