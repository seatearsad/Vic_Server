<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/deliverList')}">{pigcms{:L('_BACK_DELIVERY_LIST_')}</a>|
                    <a href="{pigcms{:U('Deliver/prep_mode')}"  class="on">{pigcms{:L('D_F_PREP_MODE')}</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>

                    </td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>{pigcms{:L('BASE_CITY')}</th>
								<th>{pigcms{:L('D_F_SWITH')}</th>
								<th>{pigcms{:L('D_F_TIME_ALLOWED')}</th>
								<th> </th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($city)">
								<volist name="city"  id="vo">
									<tr class="<if condition='$i%2 eq 0'>odd<else/>even</if> order_line city_tr" data-id="{pigcms{$vo.area_id}">
										<td width="50">{pigcms{$vo.area_name}</td>
										<td width="50">
                                            <span class="cb-enable"><label class="cb-enable <if condition="$vo['busy_mode'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="have_meal" data-id="{pigcms{$vo.area_id}" value="1" <if condition="$vo['busy_mode'] eq 1">checked="checked"</if> /></label></span>
                                            <span class="cb-disable"><label class="cb-disable <if condition="$vo['busy_mode'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" data-id="{pigcms{$vo.area_id}" name="have_meal" value="0" <if condition="$vo['busy_mode'] eq 0">checked="checked"</if> /></label></span>
                                        </td>
										<td width="50">
                                            <select disabled="disabled" class="confirm_time" name="dining_time" autocomplete="off" data-time="{pigcms{$vo.min_time}" style="margin-top:5px;height: 30px;width: 200px;">
                                                <option value="0">---</option>
                                                <option value="10">10 min</option>
                                                <option value="20">20 min</option>
                                                <option value="30">30 min</option>
                                                <option value="40">40 min</option>
                                                <option value="50">50 min</option>
                                                <option value="60">60 min</option>
                                                <option value="70">70 min</option>
                                                <option value="80">80 min</option>
                                                <option value="90">90 min</option>
                                                <option value="100">100 min</option>
                                            </select>
                                        </td>
										<td width="50" class="count_down" data-time="{pigcms{$vo.open_busy_time}" data-jet="{pigcms{$vo.jetlag}">

                                        </td>
									</tr>
								</volist>
                                <tr>
                                    <td class="" colspan="4">*{pigcms{:L('D_F_TIP_1')}</td>
                                </tr>
								<tr>
                                    <td class="textcenter pagebar" colspan="3">{pigcms{$pagebar}</td>
                                    <td class="textcenter pagebar">
                                        <input type="button" id="save_setting" value="Submit" style="height: 30px;">
                                    </td>
                                </tr>
							<else/>
								<tr><td class="textcenter red" colspan="16">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<div class="order_status_show">

</div>
<script>
    $('input[name=have_meal]:checked').each(function () {
        setlayer(this);
    });
    $('input[name=have_meal]').click(function(){
        setlayer(this);
    });

    function setlayer(layer) {
        var val = $(layer).val();
        var curr_id = $(layer).data('id');
        if(val == 1){
            //$(".confirm_time").removeAttr("disabled");
            set_select(curr_id,false);
        }else{
            //$(".confirm_time").attr("disabled",true);
            set_select(curr_id,true);
        }
    }

    function set_select(id,open) {
        $('.city_tr').each(function () {
            if($(this).data('id') == id){
                if(open){
                    $(this).find(".confirm_time").attr("disabled",true);
                    $(this).find(".confirm_time").val(0);
                }else{
                    $(this).find(".confirm_time").removeAttr("disabled");
                    var min_time = $(this).find(".confirm_time").data('time');

                    $(this).find(".confirm_time").find('option').each(function () {
                        if($(this).val() == min_time){
                            $(this).attr("selected",true);
                        }
                    });
                }
            }
        });
    }
    
    $('#save_setting').click(function () {
        var data = [];
        $('.city_tr').each(function () {
            var curr_data = {};
            curr_data['id'] = $(this).data('id');
            curr_data['mode'] = $(this).find('input[name=have_meal]:checked').val();
            curr_data['min_time'] = $(this).find('.confirm_time').val();

            data.push(curr_data);
        });

        $.post("{pigcms{:U('prep_mode')}",{"data":data},function(result) {
            if (result.error == 0){
                window.location.reload();
            }
        },'JSON');
    });

    var num = 0;
    var curr_time = parseInt("{pigcms{:time()}");

    update_pay_time();

    function update_pay_time() {
        var count_down = 120*60;

        $('.city_tr').find('.count_down').each(function () {
            var create_time = parseInt($(this).data('time'));
            if(create_time > 0) {
                var jetlag = 0;//parseInt($(this).data('jet')) * 3600;
                var cha_time = count_down - (curr_time + jetlag - create_time + num);
                console.log(cha_time + "--" + curr_time + "-" + jetlag + "-" + create_time + "-" + num);

                var h = parseInt(cha_time / 3600);
                var i = parseInt((cha_time - 3600 * h) / 60);
                var s = (cha_time - 3600 * h) % 60;
                if (i < 10) i = '0' + i;
                if (s < 10) s = '0' + s;

                var time_str = h + ':' + i + ':' + s;

                $(this).html(time_str);

                if (cha_time <= 0) {
                    window.location.reload();
                } else {
                    window.setTimeout(function () {
                        num++;
                        update_pay_time();
                    }, 1000);
                }
            }
        });
    }
</script>
<style>
    select:disabled{
        background-color: lightgray;
    }
</style>
<include file="Public:footer"/>