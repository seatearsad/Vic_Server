<include file="Public:header"/>
<div id="wrapper">

    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('D_DELIVERYFEE_SETTING')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('D_DELIVERYFEE_SETTING')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-6 float-right" style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('_BACK_ORDER_LIST_')}</h5>
                        <div class="ibox-tools">
                            <if condition="$system_session['level'] neq 3">
                                <div style="margin-left:40px;">
                                    <a href="{pigcms{:U('Deliver/export_deliver')}"
                                       style="float:right;margin-right: 10px;color:blue">
                                        {pigcms{:L('D_EXPORT_COURIERLIST')}
                                    </a>
                                </div>
                            </if>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!-------------------------------- 工具条 -------------------------------------->

                        <!------------------------------------------------------------------------------>
                        <!-- <form name="myform" id="myform" action="" method="post">-->

                        <div class="form-group row">
                            <if condition="$system_session['level'] neq 3 and $parentid eq 0">
                                <label class="col-sm-3 col-form-label">City:</label>
                                <div class="col-sm-9">
                                    <select name="searchtype" id="city_select" class="form-control">
                                        <option value="0"
                                        <if condition="$city_id eq '' or $city_id eq 0">
                                            selected="selected"
                                        </if>
                                        >{pigcms{:L('G_UNIVERSAL')}</option>
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}"
                                            <if condition="$city_id eq $vo['area_id']">
                                                selected="selected"
                                            </if>
                                            >{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select>
                                </div>
                            </if>

                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button id="setFee" class="btn btn-primary text-white" type="button">Deliver Fee</button>
                                <button id="setBonus" class="btn btn-white text-grey" type="button">Deliver Bonus</button>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div id="fee_div">
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">
                                    {pigcms{:L('D_BASIC_SETUP')}：
                                </label>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">
                                    {pigcms{:L('D_STARTING_MILEAGE')}：</label>
                                <div class="col-sm-9"><input type="text" name="base_rule_mile"
                                                             value="{pigcms{$base_rule.end}"
                                                             class="form-control"></div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">
                                    {pigcms{:L('D_STARTING_AMOUNT')}：$</label>
                                <div class="col-sm-9"><input type="text" name="base_rule_fee"
                                                             value="{pigcms{$base_rule.fee}"
                                                             class="form-control"></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">
                                    {pigcms{:L('D_TERRITORIAL_LAYOUT')}：
                                </label>
                            </div>
                            <div class="form-group  row">
                                <div class="col-sm-4" >
                                    <button id="add_set" class="btn btn-white text-grey" type="button"> {pigcms{:L('D_AdDDINC')}</button>
                                </div>
                            </div>
                            <div style="margin-top: 20px;">
                                <table width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="textcenter">{pigcms{:L('D_INITIAL_MILEAGE')}</th>
                                        <th class="textcenter">{pigcms{:L('D_COMPLETION_MILEAGE')}</th>
                                        <th class="textcenter">{pigcms{:L('D_AMOUNT')}$</th>
                                        <th style="text-align: center">Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody id="fee_list">
                                    <volist name="fee_list" id="vo">
                                        <tr id="tr_{pigcms{$vo.id}">
                                            <td class="textcenter">
                                                <input type="text" name="start_mile_new-{pigcms{$vo.id}"
                                                       value="{pigcms{$vo.start}" class="form-control">
                                            </td>
                                            <td class="textcenter">
                                                <input type="text" name="end_mile_new-{pigcms{$vo.id}"
                                                       value="{pigcms{$vo.end}" class="form-control">
                                            </td>
                                            <td class="textcenter">
                                                <input type="text" name="fee_new-{pigcms{$vo.id}"
                                                       value="{pigcms{$vo.fee}" class="form-control">
                                            </td>
                                            <td style="text-align: center">
                                                <a class="del_set" data-rowid="{pigcms{$vo.id}">X</a>
                                            </td>
                                        </tr>
                                    </volist>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="bonus_div" style="display: none;">
                            <div class="form-group  row">
                                <div class="col-sm-12" >
                                    <button id="add_bonus" class="btn btn-primary text-white float-right" type="button"> + Add</button>
                                </div>
                            </div>
                            <div style="margin-top: 20px;">
                                <table width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="textcenter">Day</th>
                                        <th class="textcenter" colspan="2">Time Range</th>
                                        <th class="textcenter">Bonus per Order</th>
                                        <th class="textcenter">Expiry Date</th>
                                        <th class="textcenter">Status</th>
                                        <th style="text-align: center">Edit</th>
                                    </tr>
                                    </thead>
                                    <tbody id="bonus_list">
                                    <volist name="bonus_list" id="vo">
                                        <tr id="tr_bonus_{pigcms{$vo.id}">
                                            <td style="text-align: center">
                                                <select class="form-control" name="day_bonus_new-{pigcms{$vo.id}">
                                                    <option value="-1">Select</option>
                                                    <option value="0" <if condition="$vo['week'] eq 0">selected="selected"</if>>Sunday</option>
                                                    <option value="1" <if condition="$vo['week'] eq 1">selected="selected"</if>>Monday</option>
                                                    <option value="2" <if condition="$vo['week'] eq 2">selected="selected"</if>>Tuesday</option>
                                                    <option value="3" <if condition="$vo['week'] eq 3">selected="selected"</if>>Wednesday</option>
                                                    <option value="4" <if condition="$vo['week'] eq 4">selected="selected"</if>>Thursday</option>
                                                    <option value="5" <if condition="$vo['week'] eq 5">selected="selected"</if>>Friday</option>
                                                    <option value="6" <if condition="$vo['week'] eq 6">selected="selected"</if>>Saturday</option>
                                                </select>
                                            </td>
                                            <td style="text-align: center">
                                                <input class="form-control hasDatepicker" type="text" value="{pigcms{$vo.begin_time}" name="begin_bonus_new-{pigcms{$vo.id}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH:mm:ss',lang:'en'})">
                                            </td>
                                            <td style="text-align: center">
                                                <input class="form-control hasDatepicker" type="text" value="{pigcms{$vo.end_time}" name="end_bonus_new-{pigcms{$vo.id}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH:mm:ss',lang:'en'})">
                                            </td>
                                            <td style="text-align: center">
                                                <input class="form-control" type="text" value="{pigcms{$vo.amount}" name="amount_bonus_new-{pigcms{$vo.id}">
                                            </td>
                                            <td style="text-align: center">
                                                <input class="form-control" type="text" value="{pigcms{$vo.expiry}"  name="expiry_bonus_new-{pigcms{$vo.id}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})" readonly="">
                                            </td>
                                            <td style="text-align: center">
                                                <span class="label label-primary">Activate</span>
                                            </td>
                                            <td style="text-align: center">
                                                <a class="del_bonus" data-rowid="{pigcms{$vo.id}">X</a>
                                            </td>
                                        </tr>
                                    </volist>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row">
                            <div class="col-sm-12">
                                <button id="submit"  class="btn btn-primary text-white float-right" type="button">{pigcms{:L('D_SUBMIT')}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    var city_id = $('#city_select').val();

    var new_num = 90000;
    $('#add_set').click(function () {
        new_num = new_num + 1;
        var html_td = '<td  style="text-align: center">';
        var html = '<tr id="tr_'+new_num+'">';
        html += html_td + '<input class="form-control" type="text" name="start_mile_new-' + new_num + '"></td>';
        html += html_td + '<input class="form-control" type="text" name="end_mile_new-' + new_num + '"></td>';
        html += html_td + '<input class="form-control" type="text" name="fee_new-' + new_num + '"></td>';
        html += html_td + '<a class="del_set" data-rowid="'+new_num+'">X</a>';
        html += '</tr>';

        $('#fee_list').append(html);

        $('.del_set').unbind();
        $('.del_set').bind('click',function () {
            if (confirm("{pigcms{:L('_B_PURE_MY_84_')}")) {
                $("#tr_"+$(this).data('rowid')).remove();
            } else {
                //return false;
            }
        });
    });

    var new_bonus_num = 90000;

    var weekStr = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
    function getWeekSelect(id){
        var weekSelect = '<select class="form-control" name="day_bonus_new-'+id+'">';
        weekSelect += '<option value="-1">Select</option>';
        for(var i=0;i < 7;i++){
            weekSelect += '<option value="'+i+'">'+weekStr[i]+'</option>';
        }
        weekSelect += '</select>';

        return weekSelect;

    }
    $('#add_bonus').click(function () {
        new_bonus_num = new_bonus_num + 1;
        var html_td = '<td  style="text-align: center">';
        var html = '<tr id="tr_bonus_'+new_bonus_num+'">';
        html += html_td + getWeekSelect(new_bonus_num);
        html += html_td + '<input class="form-control hasDatepicker" type="text" name="begin_bonus_new-' + new_bonus_num + '" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:\'HH:mm:ss\',lang:\'en\'})"></td>';
        html += html_td + '<input class="form-control hasDatepicker" type="text" name="end_bonus_new-' + new_bonus_num + '" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:\'HH:mm:ss\',lang:\'en\'})"></td>';
        html += html_td + '<input class="form-control" type="text" name="amount_bonus_new-' + new_bonus_num + '"></td>';
        html += html_td + '<input class="form-control" type="text" name="expiry_bonus_new-' + new_bonus_num + '" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:\'yyyy-MM-dd\',lang:\'en\'})"></td>';
        html += html_td + '</td>';
        html += html_td + '<a class="del_bonus" data-rowid="'+new_bonus_num+'">X</a></td>';
        html += html_td + '';
        html += '</tr>';

        $('#bonus_list').append(html);

        $('.del_bonus').unbind();
        $('.del_bonus').bind('click',function () {
            if (confirm("{pigcms{:L('_B_PURE_MY_84_')}")) {
                $("#tr_bonus_"+$(this).data('rowid')).remove();
            } else {
                //return false;
            }
        });
    });


    $('.del_set').click(function () {
        if (confirm("{pigcms{:L('_B_PURE_MY_84_')}")) {
            $("#tr_"+$(this).data('rowid')).remove();
        } else {
            //return false;
        }

        //$('#fee_list').append(html);
    });

    $('.del_bonus').click(function () {
        if (confirm("{pigcms{:L('_B_PURE_MY_84_')}")) {
            $("#tr_bonus_"+$(this).data('rowid')).remove();
        } else {
            //return false;
        }
    });

    $('#submit').click(function () {
        var is_send = true;
        var re_data = {};

        if ($("input[name='base_rule_mile']").val() == '' || $("input[name='base_rule_fee']").val() == '') {
            is_send = false;
        } else {
            re_data['base_rule_mile'] = $("input[name='base_rule_mile']").val();
            re_data['base_rule_fee'] = $("input[name='base_rule_fee']").val();
        }

        $('#fee_list').find('input').each(function () {
            if ($(this).val() == '') {
                is_send = false;
            } else {
                re_data[$(this).attr('name')] = $(this).val();
            }
        });

        $('#bonus_list').find('input,select').each(function () {
            if ($(this).val() == '' || $(this).val() == '-1') {
                is_send = false;
            } else {
                re_data[$(this).attr('name')] = $(this).val();
            }
        });

        re_data['city_id'] = city_id;

        if (is_send) {
            $.post("{pigcms{:U('Deliver/update_rule')}", re_data, function (data) {

                if (data.error == 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert('Fail');
                }
            }, 'json');
        } else {
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        }
    });

    $('#city_select').change(function () {
        city_id = $(this).val();
        window.location.href = "{pigcms{:U('Deliver/rule', $_GET)}" + "&city_id=" + city_id + location.hash;
    });

    $("#setFee").click(function () {
        $(this).removeClass('btn-white text-grey');
        $(this).addClass('btn-primary text-white');

        $("#setBonus").addClass('btn-white text-grey');
        $("#setBonus").removeClass('btn-primary text-white');

        $("#fee_div").show();
        $("#bonus_div").hide();

        location.hash = "fee";
    });

    $("#setBonus").click(function () {
        $(this).removeClass('btn-white text-grey');
        $(this).addClass('btn-primary text-white');

        $("#setFee").addClass('btn-white text-grey');
        $("#setFee").removeClass('btn-primary text-white');

        $("#fee_div").hide();
        $("#bonus_div").show();

        location.hash = "bonus";
    });

    var curr_hash = location.hash.replace("#","");
    if(curr_hash == "" || curr_hash == "fee"){
        $("#setFee").trigger('click');
    }else{
        $("#setBonus").trigger('click');
    }
</script>
<include file="Public:footer"/>
