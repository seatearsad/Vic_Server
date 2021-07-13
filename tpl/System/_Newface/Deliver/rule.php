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
        html += html_td + '<a id=';
        html += '</tr>';

        $('#fee_list').append(html);
    });

    $('.del_set').click(function () {
        if (confirm("{pigcms{:L('_B_PURE_MY_84_')}")) {
            $("#tr_"+$(this).data('rowid')).remove();
        } else {
            //return false;
        }

        //$('#fee_list').append(html);
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
