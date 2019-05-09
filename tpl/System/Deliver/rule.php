<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">配送员管理</a>
                    <if condition="$system_session['level'] neq 3">
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','添加配送员',680,560,true,false,false,editbtn,'edit',true);">添加配送员</a>
                    <a href="{pigcms{:U('Deliver/rule')}" class="on">配送配置</a>
                    </if>
                    <a href="{pigcms{:U('Deliver/map')}">配送员监控</a>
				</ul>
			</div>
		</div>
        <div class="table-list">
            <div>
                基本配置：
                <div>
                    起步公里：<input type="text" name="base_rule_mile" value="{pigcms{$base_rule.end}">
                </div>
                <div>
                    起步金额：$<input type="text" name="base_rule_fee" value="{pigcms{$base_rule.fee}">
                </div>
            </div>
            <div style="margin-top: 20px">
                梯度配置：
                <div id="add_set">
                    添加梯度
                </div>
                <table width="100%" cellspacing="0">
                    <thead>
                    <tr>
                        <th class="textcenter">起始公里</th>
                        <th class="textcenter">结束公里</th>
                        <th class="textcenter">金额</th>
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
            <div id="submit">提交</div>
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
</script>