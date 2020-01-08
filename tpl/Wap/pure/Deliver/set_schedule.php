<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>Manage Your Schedule</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style.css" />
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script>
    $(function(){
        $(".startOrder,.stopOrder").click(function(){
            $.get("/wap.php?g=Wap&c=Deliver&a=index&action=changeWorkstatus&type="+$(this).attr('ref'), function(){
                window.location.reload();
            });
        });
    });
    //ios app 更新位置
    function updatePosition(lat,lng){
        var message = '';
	    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
            if(result){
                message = result.message;
            }else {
                message = 'Error';
            }
        });

	    return message;
    }
    //更新app 设备token
    var device_token = '';
    function pushDeviceToken(token) {
        device_token = token;
        var message = '';
        $.post("{pigcms{:U('Deliver/update_device')}", {'token':token}, function(result) {
            if(result){
                message = result.message;
            }else {
                message = 'Error';
            }
        });
        return message;
    }

    var week_all = [
        'SUN',
        'MON',
        'TUE',
        'WED',
        'THU',
        'FRI',
        'SAT'
    ];
</script>
<style>
	.startOrder{color: #fff;float: right;background: green;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
	.stopOrder{color: #000;float: right;background: #ccc;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}

    #week_list{
        margin-top: 10px;
        margin-right: 10px;
        display: flex;
    }
    #week_list div{
        width: 20px;
        height: 50px;
        line-height: 50px;
        background-color: white;
        border-radius: 2px;
        text-align: center;
        margin-left: 1.5%;
        flex: 1 1 auto;
        font-size: 12px;
        cursor: pointer;
    }
    #week_list div.active{
        color: white;
        background-color: #ffa52d;
    }
    #work_time,#recom{
        width: 90%;
        margin-left: 5%;
        margin-top: 10px;
        font-size: 12px;
        clear: both;
    }
    #recom{
        height: 25px;
        line-height: 25px;
        color: #ffa52d;
    }
    #work_time div{
        width: 100%;
        height: 25px;
        line-height: 25px;
        font-size: 12px;
    }
    #work_time span,#recom span{
        float: left;
    }

    .w_t{
        width: 73%;
        margin-left: 2%;
    }
    .w_i{
        width: 20%;
    }
    .w_r,.w_nr{
        width: 5%;
        height: 22px;
        background-image: url("{pigcms{$static_path}img/recomm.png");
        background-size: auto 100%;
        background-repeat: no-repeat;
        background-position: center;
    }
    .w_nr{
        background:none;
    }
    #work_time input{
        float: right;
    }
    input.mt[type="radio"], input.mt[type="checkbox"] {
        -webkit-appearance: none;
        width: 1.2rem;
        height: 1.2rem;
        border: .02rem solid #ddd8ce;
        text-align: center;
        vertical-align: middle;
        line-height: 1.2rem;
        outline: 0;
        background-color: white;
    }
    input.mt[type="checkbox"]:disabled{
        background-color: #cccccc;
    }
    input.mt[type="checkbox"]:checked {
        background-color: #ffa52d;
        border: 0;
        color: #fff;
    }
    @font-face {
        font-family:base_icon;
        src:url("./tpl/Wap/default/static/css/fonts/base.woff") format("woff"),url("./tpl/Wap/default/static/css/fonts/base.otf")
    }
    input.mt[type="checkbox"]:checked::after {
        content: "✓";
        font-size: 1.2rem;
        font-family: base_icon;
    }
    .clerk{
        margin-bottom: 120px;
    }
</style>
</head>
<body>
<include file="header" />
	<section class="clerk" style="margin-top: 70px;">
        <div id="week_list">

        </div>
        <div id="recom">
            <span class="w_r"></span>
            <span class="w_t">{pigcms{:L('_ND_RECOM_')}</span>
        </div>
        <div id="work_time">

        </div>
        <div class="radio_box" style="float: right;margin-right: 5%;margin-top: 20px">
            <span style="font-size:12px;float: left;margin-right: 10px;line-height: 24px">{pigmcs{:L('_ND_REPEATWEEKLY_')}</span>
            <span class="cb-enable"><label class="cb-enable selected"><span>On</span><input type="radio" name="repeat" value="1" checked="checked"/></label></span>
            <span class="cb-disable"><label class="cb-disable"><span>Off</span><input type="radio" name="repeat" value="0" /></label></span>
        </div>
	</section>
	<section class="bottom" style="height: 40px;line-height: 40px;text-align: center;font-size: 12px;">
		<div class="btn_c" style="float: left;width: 60px;background-color: grey;color:white;cursor: pointer">
			Cancel
		</div>
        <div class="btn_s" style="float: right;width: 60px;background-color: #ffa52d;color: white;cursor: pointer">
            Save
        </div>
	</section>
<!-- 	<script src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0"></script> -->
    <script src="{pigcms{$static_public}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script type="text/javascript">
        //开关
        $('.cb-enable').click(repeat_enable);
        $('.cb-disable').click(repeat_disable);

        function repeat_enable(){
            $(this).find('label').addClass('selected');
            $(this).find('label').find('input').prop('checked',true);
            $(this).next('.cb-disable').find('label').find('input').prop('checked',false);
            $(this).next('.cb-disable').find('label').removeClass('selected');
            default_list[init_num]= 1;
        }

        function repeat_disable(){
            $(this).find('label').addClass('selected');
            $(this).find('label').find('input').prop('checked',true);
            $(this).prev('.cb-enable').find('label').find('input').prop('checked',false);
            $(this).prev('.cb-enable').find('label').removeClass('selected');
            default_list[init_num] = 0;
        }

        var work_time_list = JSON.parse('{pigcms{$work_time_list}');

        var init_num = parseInt("{pigcms{$week_num}");

        var today_week = parseInt("{pigcms{$week_num}");

        var default_list = JSON.parse('{pigcms{$default_list}');

        var link_num = parseInt("{pigcms{$link_num}");

        var html = '';
        for(var i=0;i<7;i++){
            var curr_num = init_num + i;
            if(curr_num > 6) curr_num = curr_num - 7;

            if(link_num == curr_num)
                html += '<div class="active" data-id="'+curr_num+'" data-num="'+i+'">';
            else
                html += '<div data-id="'+curr_num+'" data-num="'+i+'">';

            html += week_all[curr_num];
            html += '</div>';
        }

        $('#week_list').html(html);

        getWorkTime(link_num);

        $('#week_list').children('div').click(function () {
            init_num = $(this).data('id');
            $('#week_list').find('div').each(function () {
                if($(this).data('id') == init_num){
                    $(this).addClass('active');
                    getWorkTime(init_num);
                }else{
                    $(this).removeClass();
                }
            });
        });

        function getWorkTime(init_num) {
            var time_list = work_time_list[init_num];
            var html = '';

            var show_date = new Date();
            var h = show_date.getHours();

            var is_set = true;

            if(init_num == today_week && h >= time_list[0]['start_time']){
                is_set = false;
            }
            for(var i=0;i<time_list.length;i++){
                if(time_list[i]['is_recomm'] == 1)
                    html += '<div><span class="w_r"></span><span class="w_t">';
                else
                    html += '<div><span class="w_nr"></span><span class="w_t">';

                html += format_time(time_list[i]['start_time']) + ' -- ' +  format_time(time_list[i]['end_time']);
                html += '</span><span class="w_i">';
                if(time_list[i]['is_check'])
                    html += '<input type="checkbox" class="mt" data-id="'+init_num+'" data-num="'+i+'" name="work_time_'+init_num+'[]" value="'+time_list[i]['id']+'" checked="checked">';
                else
                    html += '<input type="checkbox" class="mt" data-id="'+init_num+'" data-num="'+i+'" name="work_time_'+init_num+'[]" value="'+time_list[i]['id']+'">';
                html += '</span></div>';
            }

            $('#work_time').html(html);
            $('.mt').bind('click',this,mt_click);

            if(!is_set){
                $('#work_time').find('input').each(function () {
                    $(this).attr('disabled','disabled');
                });

                if(typeof(default_list[init_num]) == 'undefined' || default_list[init_num] == 1){
                    $('.cb-enable').trigger('click');
                }else{
                    $('.cb-disable').trigger('click');
                }

                $('.cb-enable').unbind('click');
                $('.cb-disable').unbind('click');
            }else{
                $('.cb-enable').unbind('click');
                $('.cb-disable').unbind('click');
                $('.cb-enable').click(repeat_enable);
                $('.cb-disable').click(repeat_disable);

                if(typeof(default_list[init_num]) == 'undefined' || default_list[init_num] == 1){
                    $('.cb-enable').trigger('click');
                }else{
                    $('.cb-disable').trigger('click');
                }
            }
        }

        function mt_click(){
            var t_num = $(this).data('num');
            if($(this).prop('checked')){
                work_time_list[init_num][t_num]['is_check'] = 1;
            }else{
                work_time_list[init_num][t_num]['is_check'] = 0;
            }
        }

        function format_time(t_time){
            if(t_time < 12)
                t_time = t_time + ':00 AM';
            else if(t_time == 12)
                t_time = t_time + ':00 PM';
            else if(t_time > 12 && t_time < 24)
                t_time = t_time-12 + ':00 PM';
            else if(t_time == 24)
                t_time = t_time-12 + ':00 AM';
            else if(t_time >= 24)
                t_time = t_time-24 + ':00 AM';

            return t_time;
        }

        $('.btn_c').click(function () {
            window.location.href = "{pigcms{:U('Deliver/schedule')}";
        });

        $('.btn_s').click(function () {
            var re_data = work_time_list;
            $.post("{pigcms{:U('Deliver/set_schedule')}",{'data':re_data,'default_list':default_list},function(data){
                if(data['error'] == 0){
                    layer.open({
                        title: "{pigcms{:L('_STORE_REMIND_')}",
                        time: 1,
                        content: data.msg,
                        end:function () {
                            window.location.href = "{pigcms{:U('Deliver/schedule')}";
                        }
                    });
                }
            },'json');
        });
    </script>
</body>
</html>