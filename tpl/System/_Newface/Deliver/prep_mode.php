<include file="Public:header"/>
<div id="wrapper">

    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('D_F_PREP_MODE')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('D_F_PREP_MODE')}</strong>
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

                                </div>
                            </if>
                        </div>
                    </div>
                    <div class="ibox-content">
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
                                                        <span class="cb-enable"><label class="cb-enable <if condition="$vo['busy_mode'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="have_meal_{pigcms{$vo.area_id}" data-id="{pigcms{$vo.area_id}" value="1" <if condition="$vo['busy_mode'] eq 1">checked="checked"</if> /></label></span>
                                                        <span class="cb-disable"><label class="cb-disable <if condition="$vo['busy_mode'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" data-id="{pigcms{$vo.area_id}" name="have_meal_{pigcms{$vo.area_id}" value="0" <if condition="$vo['busy_mode'] eq 0">checked="checked"</if> /></label></span>
                                                    </td>
                                                    <td width="50">
                                                        <select disabled="disabled" class="confirm_time form-control" name="dining_time" autocomplete="off" data-time="{pigcms{$vo.min_time}" style="margin-top:5px;height: 30px;width: 200px;">
                                                            <option value="0">---</option>
                                                            <option value="20">20 min</option>
                                                            <option value="30">30 min</option>
                                                            <option value="40">40 min</option>
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
                                                <td class="" colspan="4">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td class="textcenter pagebar">
                                                    <input type="button" id="save_setting" value="Submit" class="btn btn-w-m btn-primary">
                                                </td>
                                                <td class="textcenter pagebar" colspan="3">{pigcms{$pagebar}</td>
                                            </tr>
                                        <else/>
                                            <tr><td class="textcenter red" colspan="16">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
                                        </if>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
		</div>
<script>
    $('body').find('input[type="radio"]:checked').each(function () {
        setlayer(this);
    });
    $('.city_tr').find('input[type="radio"]').click(function(){
        setlayer(this);
    });

    function setlayer(layer) {
        var val = $(layer).val();console.log("+++++"+val);
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
        var is_send = true;
        $('.city_tr').each(function () {
            var curr_data = {};
            curr_data['id'] = $(this).data('id');
            curr_data['mode'] = $(this).find('input[type=radio]:checked').val();
            curr_data['min_time'] = $(this).find('.confirm_time').val();
            if (curr_data['mode'] == 1 && curr_data['min_time'] <= 0) is_send = false;
            data.push(curr_data);
        });

        if (!is_send) {
            alert("When enabled, the minimum food prep time allowed cannot be empty (---).");
        }else{
            $.post("{pigcms{:U('prep_mode')}", {"data": data}, function (result) {
                if (result.error == 0) {
                    window.location.reload();
                }
            }, 'JSON');
        }
    });

    var num = 0;
    var curr_time = parseInt("{pigcms{:time()}");

    update_pay_time();

    function update_pay_time() {
        var count_down = 120*60;

        var is_update = false;

        $('.city_tr').find('.count_down').each(function () {
            var create_time = parseInt($(this).data('time'));
            if(create_time > 0) {
                var jetlag = 0;//parseInt($(this).data('jet')) * 3600;
                var cha_time = count_down - (curr_time + jetlag - create_time + num);
                //console.log(cha_time + "--" + curr_time + "-" + jetlag + "-" + create_time + "-" + num);

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
                    is_update = true;
                }
            }
        });

        if(is_update){
            window.setTimeout(function () {
                num++;
                update_pay_time();
            }, 1000);
        }
    }
</script>
<style>
    select:disabled{
        background-color: lightgray;
    }
</style>
<include file="Public:footer"/>