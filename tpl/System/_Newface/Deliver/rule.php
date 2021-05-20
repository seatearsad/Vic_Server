<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">{pigcms{:L('_BACK_COURIER_MANA_')}</a>
                    <if condition="$system_session['level'] neq 3">
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','{pigcms{:L(\'_BACK_ADD_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_ADD_COURIER_')}</a>
                    <a href="{pigcms{:U('Deliver/rule')}" class="on">{pigcms{:L('D_DELIVERYFEE_SETTING')}</a>
                    </if>
                    <a href="{pigcms{:U('Deliver/map')}">{pigcms{:L('_BACK_COURIER_MONI_')}</a>
                    <a href="{pigcms{:U('Deliver/schedule')}">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
				</ul>
			</div>
		</div>
        <table class="search_table" width="100%">
            <tr>
                <td>
                    <if condition="$system_session['level'] neq 3 and $parentid eq 0">
                        City:
                        <select name="searchtype" id="city_select">
                            <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>{pigcms{:L('G_UNIVERSAL')}</option>
                            <volist name="city" id="vo">
                                <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                            </volist>
                        </select>
                    </if>
                </td>
            </tr>
        </table>
        <div class="table-list">
            <div>
                {pigcms{:L('D_BASIC_SETUP')}：
                <div>
                    {pigcms{:L('D_STARTING_MILEAGE')}：<input type="text" name="base_rule_mile" value="{pigcms{$base_rule.end}">
                </div>
                <div>
                    {pigcms{:L('D_STARTING_AMOUNT')}：$<input type="text" name="base_rule_fee" value="{pigcms{$base_rule.fee}">
                </div>
            </div>
            <div style="margin-top: 20px">
                {pigcms{:L('D_TERRITORIAL_LAYOUT')}：
                <div id="add_set">
                    {pigcms{:L('D_AdDDINC')}
                </div>
                <table width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="textcenter">{pigcms{:L('D_INITIAL_MILEAGE')}</th>
                        <th class="textcenter">{pigcms{:L('D_COMPLETION_MILEAGE')}</th>
                        <th class="textcenter">{pigcms{:L('D_AMOUNT')}</th>
                    </tr>
                    </thead>
                    <tbody id="fee_list">
                        <volist name="fee_list" id="vo">
                        <tr>
                            <td class="textcenter">
                                <input type="text" name="start_mile-{pigcms{$vo.id}" value="{pigcms{$vo.start}">
                            </td>
                            <td class="textcenter">
                                <input type="text" name="end_mile-{pigcms{$vo.id}" value="{pigcms{$vo.end}">
                            </td>
                            <td class="textcenter">
                                $ <input type="text" name="fee-{pigcms{$vo.id}" value="{pigcms{$vo.fee}">
                            </td>
                        </tr>
                        </volist>
                    </tbody>
                </table>
            </div>
            <div id="submit">{pigcms{:L('D_SUBMIT')}</div>
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
    }
    #submit{
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
</style>
<script>
    var city_id = $('#city_select').val();

    var new_num = 0;
    $('#add_set').click(function () {
        new_num = new_num + 1;
        var html_td = '<td class="textcenter">';
        var html = '<tr>';
        html += html_td + '<input type="text" name="start_mile_new-'+ new_num +'"></td>';
        html += html_td + '<input type="text" name="end_mile_new-'+ new_num +'"></td>';
        html += html_td + '$ <input type="text" name="fee_new-'+ new_num +'"></td>';

        html += '</tr>';

        $('#fee_list').append(html);
    });
    
    $('#submit').click(function () {
        var is_send = true;
        var re_data = {};

        if($("input[name='base_rule_mile']").val() == '' || $("input[name='base_rule_fee']").val() == ''){
            is_send = false;
        }else {
            re_data['base_rule_mile'] = $("input[name='base_rule_mile']").val();
            re_data['base_rule_fee'] = $("input[name='base_rule_fee']").val();
        }


        $('#fee_list').find('input').each(function () {
            if($(this).val() == ''){
                is_send = false;
            }else {
                re_data[$(this).attr('name')] = $(this).val();
            }
        });

        re_data['city_id'] = city_id;

        if(is_send) {
            $.post("{pigcms{:U('Deliver/update_rule')}", re_data, function (data) {
                if (data.error == 0) {
                    alert(data.msg);
                    window.location.reload();
                } else {
                    alert('Fail');
                }
            },'json');
        }else{
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        }
    });

    $('#city_select').change(function () {
        city_id = $(this).val();
        window.location.href = "{pigcms{:U('Deliver/rule', $_GET)}" + "&city_id="+city_id;
    });
</script>