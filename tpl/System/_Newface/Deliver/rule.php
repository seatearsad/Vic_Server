<include file="Public:header"/>
<div id="wrapper">

    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>{pigcms{:L('D_DELIVERYFEE_SETTING')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('D_DELIVERYFEE_SETTING')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4 float-right" style="height 90px;margin-top:40px;">
            <div class="btn-group">

                <if condition="$system_session['level'] eq 2">
                    <a href="{pigcms{:U('Deliver/user')}" style="float:right;">
                        <button class="btn btn-white  text-grey">{pigcms{:L('_BACK_COURIER_MANA_')}</button>
                    </a>
                    <if condition="$system_session['level'] neq 3">
                        <a href="{pigcms{:U('Deliver/rule')}">
                            <button class="btn btn-white text-grey active">{pigcms{:L('D_DELIVERYFEE_SETTING')}</button>
                        </a>
                    </if>
                    <a href="{pigcms{:U('Deliver/map')}">
                        <button class="btn btn-white text-grey">{pigcms{:L('_BACK_COURIER_MONI_')}</button>
                    </a>
                    <a href="{pigcms{:U('Deliver/schedule')}">
                        <button class="btn btn-white text-grey">{pigcms{:L('_DELIVER_SCHEDULE_')}</button>
                    </a>
                </if>
                <a href="javascript:void(0);"
                   onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','{pigcms{:L(\'_BACK_ADD_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);"
                   style="float:right;margin-left: 10px;">
                    <button class="btn btn-primary">{pigcms{:L('_BACK_ADD_COURIER_')}</button>
                </a>

            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
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
                        <div class="form-group  row">
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
                        <div class="hr-line-dashed"></div>
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
                                </tr>
                                </thead>
                                <tbody id="fee_list">
                                <volist name="fee_list" id="vo">
                                    <tr>
                                        <td class="textcenter">
                                            <input type="text" name="start_mile-{pigcms{$vo.id}"
                                                   value="{pigcms{$vo.start}" class="form-control">
                                        </td>
                                        <td class="textcenter">
                                            <input type="text" name="end_mile-{pigcms{$vo.id}"
                                                   value="{pigcms{$vo.end}" class="form-control">
                                        </td>
                                        <td class="textcenter">
                                            <input type="text" name="fee-{pigcms{$vo.id}"
                                                   value="{pigcms{$vo.fee}" class="form-control">
                                        </td>
                                    </tr>
                                </volist>
                                </tbody>
                            </table>
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

    var new_num = 0;
    $('#add_set').click(function () {
        new_num = new_num + 1;
        var html_td = '<td class="textcenter">';
        var html = '<tr>';
        html += html_td + '<input class="form-control" type="text" name="start_mile_new-' + new_num + '"></td>';
        html += html_td + '<input class="form-control" type="text" name="end_mile_new-' + new_num + '"></td>';
        html += html_td + '<input class="form-control" type="text" name="fee_new-' + new_num + '"></td>';

        html += '</tr>';

        $('#fee_list').append(html);
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
        window.location.href = "{pigcms{:U('Deliver/rule', $_GET)}" + "&city_id=" + city_id;
    });
</script>
<include file="Public:footer"/>
