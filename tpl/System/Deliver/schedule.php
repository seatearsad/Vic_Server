<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
                <ul>
                    <a href="{pigcms{:U('Deliver/user')}">{pigcms{:L('_BACK_COURIER_MANA_')}</a>

                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','{pigcms{:L(\'_BACK_ADD_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_ADD_COURIER_')}</a>
                    <!--a href="{pigcms{:U('Config/index',array('galias'=>'deliver','header'=>'Deliver/header'))}">配送配置</a-->
                    <if condition="$system_session['level'] neq 3">
                        <a href="{pigcms{:U('Deliver/rule')}">配送配置</a>
                    </if>
                    <a href="{pigcms{:U('Deliver/map')}">{pigcms{:L('_BACK_COURIER_MONI_')}</a>
                    <a href="{pigcms{:U('Deliver/schedule')}" class="on">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
                </ul>
			</div>
		</div>
        <div class="table-list">
            <div>
                City:
                <select name="searchtype" id="city_select">
                    <volist name="city" id="vo">
                        <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                    </volist>
                </select>
            </div>
            <div id="add_set">
                Add Time
            </div>
            <table width="100%" cellspacing="0">
                <tbody id="new_list">

                </tbody>
            </table>
            <div id="add_submit" style="display: none;">Add</div>

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
                    </tr>
                    </thead>
                    <tbody id="work_list">

                    </tbody>
                </table>

                <div id="submit">Edit</div>
            </div>
<include file="Public:footer"/>
<style>
    .table-list{
        width: 98%;
        margin: 0 auto;
        padding:10px;
        box-sizing: border-box;
        line-height: 25px;
    }
    #add_set{
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
    #submit,#add_submit{
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
    #week_list{
        margin-top: 10px;
        display: flex;
        background-color: #eef3f7;
        border-bottom: 1px solid white;
    }
    #week_list div{
        width: 44px;
        height: 44px;
        line-height: 44px;
        text-align: center;
        color: #999999;
        margin-left: 1%;
        flex: 1 1 auto;
        cursor: pointer;
    }
    #week_list div.active{
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

    var city_id = "{pigcms{$city[0]['area_id']}";

    $('#city_select').change(function () {
        city_id = $(this).val();
        getWorkList();
    });

    getWorkList();

    var work_time_list = {};
    var time_list = {};
    function getWorkList() {
        $.post("{pigcms{:U('Deliver/ajax_get_city_schedule')}",{'city_id':city_id},function(data){
            work_time_list = data['work_time_list'];
            time_list = data['time_list'];
            showWorkList();
        },'json');
    }

    var init_num = parseInt("{pigcms{$week_num}");

    var html = '';
    for(var i=0;i<7;i++){
        var curr_num = init_num + i;
        if(curr_num > 6) curr_num = curr_num - 7;

        if(i == 0)
            html += '<div class="active" data-id="'+curr_num+'" data-num="'+i+'">';
        else
            html += '<div data-id="'+curr_num+'" data-num="'+i+'">';

        html += week_all[curr_num];
        html += '</div>';
    }

    $('#week_list').html(html);

    $('#week_list').children('div').click(function () {
        init_num = $(this).data('id');
        $('#week_list').find('div').each(function () {
            if($(this).data('id') == init_num){
                $(this).addClass('active');
                showWorkList();
            }else{
                $(this).removeClass();
            }
        });
    });
    
    function showWorkList() {
        var work_list = work_time_list[init_num];
        var html = '';
        if(typeof(work_list) != 'undefined') {
            for (var i = 0; i < work_list.length; i++) {
                var html_td = '<td class="textcenter">';
                html += '<tr>';
                html += html_td + format_time(work_list[i]['start_time']) + ' -- ' + format_time(work_list[i]['end_time']) + '</td>';
                html += html_td + '<input type="text" name="min" data-id="'+i+'" data-num="'+init_num+'" value="' + work_list[i]['min'] +'"></td>';
                html += html_td + '<input type="text" name="max" data-id="'+i+'" data-num="'+init_num+'" value="' + work_list[i]['max'] +'"></td>';
                html += html_td + work_list[i]['curr_num'] +'</td>';

                html += '</tr>';
            }
        }

        $('#work_list').html(html);

        $('#work_list').on("blur","input[name='min']",this,change_min_max);
        $('#work_list').on("blur","input[name='max']",this,change_min_max);
    }
    
    function change_min_max() {
        var time_id = $(this).data('id');
        var week_num = $(this).data('num');

        var type = $(this).attr('name');

        var num = parseInt($(this).val());

        work_time_list[week_num][time_id][type] = num;
    }

    function format_time(t_time){
        if(t_time <= 12)
            t_time = t_time + ':00 AM';
        // else if(t_time == 12)
        //     t_time = t_time + ':00 PM';
        else if(t_time > 12 && t_time <= 24)
            t_time = t_time-12 + ':00 PM';
        else if(t_time > 24)
            t_time = t_time-24 + ':00 AM';

        return t_time;
    }
    /////
    var new_num = 0;
    $('#add_set').click(function () {
        new_num = new_num + 1;
        var html_td = '<td class="textcenter">';
        var html = '<tr>';
        html += html_td + '<input type="text" name="start_time" data-id="new_'+new_num+'" data-num="'+init_num+'">' + ' -- ' + '<input type="text" name="end_time" data-id="new_'+new_num+'" data-num="'+init_num+'">' + '</td>';
        html += html_td + '<input type="text" name="min" data-id="new_'+new_num+'" data-num="'+init_num+'" value="0"></td>';
        html += html_td + '<input type="text" name="max" data-id="new_'+new_num+'" data-num="'+init_num+'" value="0"></td>';
        html += html_td +'<input type="text" name="new_week_num" data-id="new_'+new_num+'" value="0,1,2,3,4,5,6"></td>';

        html += '</tr>';

        $('#new_list').append(html);
        $('#add_submit').show();
    });
    
    $('#add_submit').click(function () {
        var is_send = true;
        $('#new_list').find('input').each(function () {
            if($(this).val() == ''){
                is_send = false;
            }
        });
        if(is_send){

        }else{
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        }
    });
    $('#submit').click(function () {
        var is_send = true;
        var re_data = {};

        if(is_send) {
            // $.post("{pigcms{:U('Deliver/update_rule')}", re_data, function (data) {
            //     if (data.error == 0) {
            //         alert(data.msg);
            //         window.location.reload();
            //     } else {
            //         alert('Fail');
            //     }
            // },'json');
        }else{
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        }
    });
</script>