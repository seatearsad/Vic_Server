<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no,height=device-height" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>My Schedule</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
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
    body{
        position: unset;
        background: #f8f8f8;
    }
	.startOrder{color: #fff;float: right;background: green;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
	.stopOrder{color: #000;float: right;background: #ccc;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
    .clerk{
        color: #666666;
        font-size: 12px;
    }

    #week_list{
        margin: 20px 10px 50px 5%;
        white-space: nowrap;
        overflow: auto;
    }
    #week_list div{
        position: relative;
        width: 70px;
        height: 70px;
        line-height: 30px;
        background-color: #E3EAFD;
        border-radius: 12px;
        text-align: center;
        margin-right: 2%;
        display: inline-block;
        font-size: 12px;
        color: #7297E6;
        cursor: pointer;
    }
    #week_list div.active{
        color: white;
        background-color: #294068;
    }
    #date_div{
        width: 100%;
        text-align: center;
        margin: 30px auto;
        font-size: 16px;
        font-weight: bold;
        color: #294068;
        text-decoration: underline;
    }
    #set_btn{
        box-sizing: border-box;
        width: 90%;
        height: 40px;
        line-height: 40px;
        text-align: center;
        color: #614213;
        font-size: 16px;
        border-radius: 12px;
        background-color: #F8E9D0;
        cursor: pointer;
        position: absolute;
        bottom: 50px;
        left: 5%;
    }
    #work_list{
        width: 90%;
        margin: 10px auto;
        text-align: center;
    }
    #work_list div{
        height: 50px;
        margin-top: 10px;
        line-height: 50px;
        font-size: 16px;
        border-radius: 12px;
        font-weight: bold;
        color: #294068;
        background-color: white;

    }
    #list_txt{
        font-size: 14px;margin: 20px auto;color: #555555;width: 80%;
    }
    .title_icon{
        font-size: 6px !important;
        position: absolute;
        width: 6px !important;
        bottom: 10px;
        left: 32px;
    }
    .date_span{
        position: absolute;
        width: 100%;
        left: 0;
        top: 22px;
        font-size: 24px;
        font-weight: bold;
        color: #294068;
    }
    #week_list div.active .date_span{
        color: white;
    }
</style>
</head>
<body>
    <include file="header" />
    <div class="page_title" style="padding-bottom: 10px;">
        Schedule
    </div>
	<section class="clerk" style="text-align: center">
		<div id="week_list">

		</div>
        <div id="date_div">
            {pigcms{:date('Y-m-d',$today)}
        </div>
        <div id="work_list">

        </div>
        <div id="list_txt">

        </div>
	</section>

    <div id="set_btn">
        <span class="material-icons" style="vertical-align: text-top;margin-top: -3px;">add_circle_outline</span>
        Manage Shifts
    </div>
<!-- 	<script src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0"></script> -->
	<script type="text/javascript">
        var work_list = JSON.parse('{pigcms{$work_list}');

        var time_str = parseInt("{pigcms{$today}");
        var init_num = parseInt("{pigcms{$week_num}");
        var html = '';
        for(var i=0;i<7;i++){
            var curr_num = init_num + i;
            if(curr_num > 6) curr_num = curr_num - 7;

            var className = '';
            if(typeof(work_list[curr_num]) != 'undefined'){
                className = 'is_set';
            }

            if(i == 0) {
                className += " active";
                html += '<div class="'+className+'" data-id="' + curr_num + '" data-num="' + i + '">';
            }else {
                html += '<div class="'+className+'" data-id="' + curr_num + '" data-num="' + i + '">';
            }

            var show_date = new Date(time_str*1000 + 86400000*i);
            var d = show_date.getDate();

            d = (Array(2).join('0')+d).slice(-2);

            html += week_all[curr_num];
            html += '<span class="date_span">'+d+'</span>';
            if(typeof(work_list[curr_num]) != 'undefined') {
                html += '<span class="material-icons title_icon">lens</span>';
            }
            html += '</div>';
        }
        $('#week_list').html(html);

        set_work_list();

        $('#week_list').children('div').click(function () {
            init_num = $(this).data('id');

            var date_num = parseInt($(this).data('num'));
            var show_date = new Date(time_str*1000 + 86400000*date_num);
            var y = show_date.getFullYear();
            var m = show_date.getMonth()+1;
            var d = show_date.getDate();
            $('#date_div').html(y+'-'+m+'-'+d);

            $('#week_list').find('div').each(function () {
                if($(this).data('id') == init_num){
                    $(this).addClass('active');
                }else{
                    $(this).removeClass('active');
                }
            });

            set_work_list();
        });

        function set_work_list() {
            var html = '';
            var list_txt = '';

            if(typeof(work_list[init_num]) != 'undefined'){
                var curr_work = work_list[init_num]['ids'];

                if(curr_work.length > 0) {
                    for (var i = 0; i < curr_work.length; i++) {
                        html += '<div>';
                        html += format_time(curr_work[i]['start_time']) + ' -- ' + format_time(curr_work[i]['end_time']);
                        html += '</div>';
                    }
                    list_txt = '';
                }else{
                    list_txt = 'No shift scheduled for the day you choose';
                }
            }else{
                list_txt = 'No shift scheduled for the day you choose';
            }

            $('#list_txt').html(list_txt);
            $('#work_list').html(html);
        }

        $('#set_btn').click(function () {
            window.location.href = "{pigcms{:U('Deliver/set_schedule')}&num="+init_num;
        });

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
    </script>
</body>
</html>