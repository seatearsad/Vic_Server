<include file="Public:header"/>
<div id="wrapper">

    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('_DELIVER_SCHEDULE_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_DELIVER_SCHEDULE_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-6 " style="height 90px;margin-top:40px;">
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

                                </div>
                            </if>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!-------------------------------- 工具条 -------------------------------------->
                        <div style="height: 50px;">
                            <form action="{pigcms{:U('Merchant/index')}" class="form-inline" role="form"
                                  method="get">
                                <div id="tool_bar" style="form-group tutti_toolbar" style="height: 80px;">
                                    City:
                                    <select name="searchtype" id="city_select" class="form-control">
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select>&nbsp;&nbsp;&nbsp;
                                </div>
                            </form>
                        </div>
                        <div class="table-list">
                            <if condition="$system_session['level'] neq 3">
                                <div id="add_set">
                                    Add Time
                                </div>
                                <table width="100%" cellspacing="0">
                                    <tbody id="new_list">
                                    </tbody>
                                </table>
                                <div id="add_submit" style="display: none;">Add</div>
                            </if>
                            <div id="week_list">
                            </div>
                            <div>
                                <table width="100%" cellspacing="0">
                                    <thead>
                                    <tr>
                                        <th class="textcenter">Time</th>
                                        <th class="textcenter">Min</th>
                                        <th class="textcenter">Max</th>
                                        <th class="textcenter">Current</th>
                                        <if condition="$system_session['level'] neq 3">
                                            <th class="textcenter">Edit</th>
                                        </if>
                                    </tr>
                                    </thead>
                                    <tbody id="work_list">

                                    </tbody>
                                </table>
                                <div id="submit">Save</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!------------------------------------------------------------------------------>
                <!-- <form name="myform" id="myform" action="" method="post">-->
            </div>
        </div>
    </div>
        <style>
            .table-list {
                width: 98%;
                margin: 0 auto;
                padding: 10px;
                box-sizing: border-box;
                line-height: 25px;
            }

            #add_set {
                position: relative;
                float: right;
                width: 100px;
                height: 20px;
                line-height: 20px;
                background-color: #ffa52d;
                border-radius: 2px;
                color: white;
                text-align: center;
                cursor: pointer;
                margin: 10px 0;
            }

            #submit, #add_submit {
                margin-top: 20px;
                width: 100px;
                height: 30px;
                line-height: 30px;
                font-size: 16px;
                background-color: #ffa52d;
                border-radius: 2px;
                color: white;
                text-align: center;
                cursor: pointer;
            }

            #week_list {
                margin-top: 10px;
                display: flex;
                background-color: #eef3f7;
                border-bottom: 1px solid white;
            }

            #week_list div {
                width: 44px;
                height: 44px;
                line-height: 44px;
                text-align: center;
                color: #999999;
                margin-left: 1%;
                flex: 1 1 auto;
                cursor: pointer;
            }

            #week_list div.active {
                width: 48px;
                height: 48px;
                line-height: 48px;
                border-radius: 24px;
                color: #2999f1;
            }
        </style>
        <script>
            var week_all = [
                'SUN',
                'MON',
                'TUE',
                'WED',
                'THU',
                'FRI',
                'SAT'
            ];

            var city_id = $('#city_select').val();

            $('#city_select').change(function () {
                city_id = $(this).val();
                getWorkList();
                new_num = 0;
                $('#new_list').html('');
                $('#add_submit').hide();
            });

            getWorkList();

            var work_time_list = {};
            var time_list = {};

            function getWorkList() {
                $.post("{pigcms{:U('Deliver/ajax_get_city_schedule')}", {'city_id': city_id}, function (data) {
                    work_time_list = data['work_time_list'];
                    time_list = data['time_list'];
                    showWorkList();
                }, 'json');
            }

            var init_num = parseInt("{pigcms{$week_num}");

            var html = '';
            for (var i = 0; i < 7; i++) {
                var curr_num = init_num + i;
                if (curr_num > 6) curr_num = curr_num - 7;

                if (i == 0)
                    html += '<div class="active" data-id="' + curr_num + '" data-num="' + i + '">';
                else
                    html += '<div data-id="' + curr_num + '" data-num="' + i + '">';

                html += week_all[curr_num];
                html += '</div>';
            }

            $('#week_list').html(html);

            $('#week_list').children('div').click(function () {
                init_num = $(this).data('id');
                $('#week_list').find('div').each(function () {
                    if ($(this).data('id') == init_num) {
                        $(this).addClass('active');
                        showWorkList();
                    } else {
                        $(this).removeClass();
                    }
                });
            });

            function showWorkList() {
                var work_list = work_time_list[init_num];
                var html = '';
                var level = "{pigcms{$system_session['level']}";
                if (typeof(work_list) != 'undefined') {
                    for (var i = 0; i < work_list.length; i++) {
                        var html_td = '<td class="textcenter">';
                        html += '<tr>';
                        html += html_td + format_time(work_list[i]['start_time']) + ' -- ' + format_time(work_list[i]['end_time']) + '</td>';
                        html += html_td + '<input type="text" class="form-control" name="min" data-id="' + i + '" data-num="' + init_num + '" value="' + work_list[i]['min'] + '"></td>';
                        html += html_td + '<input type="text" class="form-control" name="max" data-id="' + i + '" data-num="' + init_num + '" value="' + work_list[i]['max'] + '"></td>';
                        html += html_td + work_list[i]['curr_num'] + '</td>';

                        if (level != '3')
                            html += html_td + '<a href="javascript:del_time(' + init_num + ',' + work_list[i]['id'] + ')">X</a>' + '</td>';

                        html += '</tr>';
                    }
                }

                $('#work_list').html(html);

                $('#work_list').on("blur", "input[name='min']", this, change_min_max);
                $('#work_list').on("blur", "input[name='max']", this, change_min_max);
            }

            function change_min_max() {
                var time_id = $(this).data('id');
                var week_num = $(this).data('num');

                var type = $(this).attr('name');

                if ($(this).val() == '') {
                    alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
                    $(this).val(work_time_list[week_num][time_id][type]);
                }
                var num = parseInt($(this).val());

                work_time_list[week_num][time_id][type] = num;
            }

            function del_time(week_num, time_id) {
                if (confirm("{pigcms{:L('_B_PURE_MY_84_')}")) {
                    var re_data = {
                        'week_num': week_num,
                        'time_id': time_id
                    }
                    $.post("{pigcms{:U('Deliver/schedule_del_time')}", re_data, function (data) {
                        if (data.error == 0) {
                            alert(data.msg);
                            window.location.reload();
                        } else {
                            alert('Fail');
                        }
                    }, 'json');
                } else {
                    //return false;
                }
            }

            function format_time(t_time) {
                if (t_time < 12)
                    t_time = t_time + ':00 AM';
                else if (t_time == 12)
                    t_time = t_time + ':00 PM';
                else if (t_time > 12 && t_time < 24)
                    t_time = t_time - 12 + ':00 PM';
                else if (t_time == 24)
                    t_time = t_time - 12 + ':00 AM';
                else if (t_time >= 24)
                    t_time = t_time - 24 + ':00 AM';

                return t_time;
            }

            /////
            var new_num = 0;
            $('#add_set').click(function () {
                new_num = new_num + 1;
                var html_td = '<td class="textcenter">';
                var html = '<tr data-id="' + new_num + '">';
                html += html_td + '<input type="text" name="start_time" data-id="new_' + new_num + '" data-num="' + init_num + '">' + ' -- ' + '<input type="text" name="end_time" data-id="new_' + new_num + '" data-num="' + init_num + '">' + '</td>';
                html += html_td + '<input type="text" name="min" data-id="new_' + new_num + '" data-num="' + init_num + '" value="0"></td>';
                html += html_td + '<input type="text" name="max" data-id="new_' + new_num + '" data-num="' + init_num + '" value="0"></td>';
                html += html_td + '<input type="text" name="week_num" data-id="new_' + new_num + '" value="0,1,2,3,4,5,6"> <a href="javascript:delAdd(' + new_num + ')">X</a></td>';

                html += '</tr>';

                $('#new_list').append(html);
                //$('#new_list').off("blur","input[name='start_time']");
                //$('#new_list').on("blur","input[name='start_time']",this,check_time);

                $('#add_submit').show();
            });

            // function check_time() {
            //
            // }

            function delAdd(num) {
                $('#new_list').find('tr').each(function () {
                    var new_num = $(this).data('id');
                    if (new_num == num) {
                        $(this).remove();
                    }
                });
            }

            $('#add_submit').click(function () {
                var is_send = true;
                $('#new_list').find('input').each(function () {
                    if ($(this).val() == '') {
                        is_send = false;
                    }
                });

                var re_data = {};
                re_data['city_id'] = city_id;
                re_data['data'] = {};
                if (is_send) {
                    $('#new_list').find('tr').each(function () {
                        var new_num = $(this).data('id');
                        var new_data = {};
                        $(this).find('input').each(function () {
                            var name = $(this).attr('name');
                            new_data[name] = $(this).val();
                        });
                        re_data['data'][new_num] = new_data;
                    });

                    $.post("{pigcms{:U('Deliver/schedule_add_time')}", re_data, function (data) {
                        if (data.error == 0) {
                            alert(data.msg);
                            //window.location.reload();
                            window.location.href = "{pigcms{:U('Deliver/schedule')}&city_id=" + city_id;
                        } else {
                            alert('Fail');
                        }
                    }, 'json');
                } else {
                    alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
                }
            });
            $('#submit').click(function () {
                var is_send = true;
                var re_data = {};

                for (var i = 0; i < work_time_list.length; i++) {//week 0-6 sun（周日）-（周六）
                    re_data[i] = {};
                    for (var j = 0; j < work_time_list[i].length; j++) { //time
                        var time_data = {};

                        if((work_time_list[i][j]['min']*1)>(work_time_list[i][j]['max']*1)){
                            alert(week_all[i]+" "+format_time(work_time_list[i][j]["start_time"]) +" - "+ format_time(work_time_list[i][j]["end_time"]) + "" +" error, Please enter the correct number of couriers ("+ work_time_list[i][j]['min'] +" - "+work_time_list[i][j]['max']+")");
                            is_send=false;
                            return;
                        }else{
                            time_data['id'] = work_time_list[i][j]['id'];
                            time_data['min'] = work_time_list[i][j]['min'];
                            time_data['max'] = work_time_list[i][j]['max'];
                            re_data[i][j] = time_data;
                        }
                    }
                }

                if (is_send) {
                    $.post("{pigcms{:U('Deliver/update_schedule_time')}", {'data': re_data}, function (data) {
                        if (data.error == 0) {
                            alert('Success');
                            window.location.reload();
                        } else {
                            alert('Fail');
                        }
                        //alert('Success');
                        //window.location.reload();
                    }, "json");
                } else {
                    alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
                }
            });
        </script>
        <include file="Public:footer"/>
