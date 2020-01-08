<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>My Schedule</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
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
	.startOrder{color: #fff;float: right;background: green;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
	.stopOrder{color: #000;float: right;background: #ccc;border: 1px solid #ccc;padding: 5px 10px 5px 10px;}
    .clerk{
        color: #666666;
        font-size: 12px;
    }

    #week_list{
        margin-top: 10px;
        margin-right: 10px;
        display: flex;
    }
    #week_list div{
        width: 20px;
        height: 60px;
        line-height: 30px;
        background-color: white;
        border-radius: 2px;
        text-align: center;
        margin-left: 1.5%;
        flex: 1 1 auto;
        font-size: 12px;
        cursor: pointer;
    }
    #week_list div.is_set{
        background-image: url("{pigcms{$static_path}img/set_y.png");
        background-repeat: no-repeat;
        background-position: center bottom 10px;
        background-size: auto 30%;
    }
    #week_list div.active.is_set{
        background-image: url("{pigcms{$static_path}img/set_w.png");
    }
    #week_list div.active{
        color: white;
        background-color: #ffa52d;
    }
    #date_div{
        width: 100%;
        text-align: center;
        margin-top: 10px;
        font-size: 12px;
        text-decoration: underline;
    }
    #set_btn{
        box-sizing: border-box;
        width: 100px;
        height: 25px;
        line-height: 25px;
        text-align: center;
        color: white;
        font-size: 12px;
        background-color: #ffa52d;
        margin: 10px 10px 20px auto;
        cursor: pointer;
    }
    #work_list{
        width: 70%;
        margin: 10px auto;
        text-align: center;
    }
    #work_list div{
        height: 30px;
        margin-top: 10px;
        line-height: 30px;
        font-size: 12px;
        border: 1px solid #ffa52d;
        background-color: white;
    }
    #list_txt{
        font-size: 9px;margin: 20px auto;color: #999999;width: 80%;
    }
</style>
</head>
<body>
    <include file="header" />
	<section class="clerk" style="margin-top: 70px;text-align: center">
        <div id="set_btn">
            {pigcms{:L('_ND_ADDSHIFT_')}
        </div>
		<div id="week_list">

		</div>
        <div id="date_div">
            {pigcms{:date('Y-m-d',$today)}
        </div>
        <div style="margin: 20px auto">
            {pigcms{:L('_ND_MYSHIFT_')}:
        </div>
        <div id="work_list">

        </div>
        <div id="list_txt">

        </div>
	</section>
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

            html += week_all[curr_num];
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
                    list_txt = '{pigcms{:L('_ND_MYSHIFT2_')}';
                }else{
                    list_txt = '{pigcms{:L('_ND_MYSHIFT1_')}';
                }
            }else{
                list_txt = '{pigcms{:L('_ND_MYSHIFT1_')}';
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