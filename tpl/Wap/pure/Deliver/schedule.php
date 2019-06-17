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

    #week_list{
        margin-top: 10px;
        display: flex;
    }
    #week_list div{
        width: 44px;
        height: 44px;
        line-height: 44px;
        background-color: white;
        border-radius: 22px;
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
    #date_div{
        width: 100%;
        text-align: center;
        margin-top: 10px;
        font-size: 16px;
        color: grey;
    }
    #set_btn{
        box-sizing: border-box;
        width: 150px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        color: white;
        background-color: #33A1FF;
        margin: 10px auto;
        background-image: url("{pigcms{$static_path}images/settings.png");
        background-repeat: no-repeat;
        background-size: auto 80%;
        background-position: 10px 4px;
        cursor: pointer;
        padding-left: 20px;
    }
    #work_list{
        width: 80%;
        margin: 10px auto;
        text-align: center;
    }
    #work_list div{
        height: 30px;
        margin-top: 10px;
        line-height: 30px;
        background-color: white;
    }
</style>
</head>
<body>
	<section class="clerk">
		<!--div class="clerk_top">
			<div class="fl clerk_img">
				<if condition="$deliver_session['store_id']">
                <span style="background: url({pigcms{$store['image']}) center no-repeat; background-size: contain;"></span>
                <else />
                <span style="background: url(<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>) center no-repeat; background-size: contain;"></span>
                </if>
			</div>
			<div class="clerk_r">
				<h2>{pigcms{$deliver_session['name']}<i> , {pigcms{:L('_HELLO_TXT_')}</i></h2>
				<p>
					<if condition="$deliver_session['store_id']">{pigcms{:L('_COURIER_TXT_')}-{pigcms{$store['name']}<else />{pigcms{:L('_COURIER_TXT_')}</if>
					<if condition="$deliver_session['work_status'] eq '1'">
					<a href="javascript:void(0)" class="startOrder" ref="0">{pigcms{:L('_CLOCK_IN_')}</a>
					<else />
					<a href="javascript:void(0)" class="stopOrder" ref="1">{pigcms{:L('_CLOCK_OUT_')}</a>
					</if>
				</p>
                <div id="set_btn">设置</div>
			</div>
		</div-->
		<div id="week_list">

		</div>
        <div id="date_div">
            {pigcms{:date('Y-m-d',$today)}
        </div>
        <div id="work_list">

        </div>
        <div id="set_btn">
            Setting
        </div>
	</section>
	<section class="bottom">
		<div class="bottom_n">
			<ul>
				<li class="Statistics Statisticson fl">
                    <a href="{pigcms{:U('Deliver/schedule')}">{pigcms{:L('_DELIVER_SCHEDULE_')}</a>
				</li>
				<li class="home fl">
					<a href="{pigcms{:U('Deliver/index')}"><i></i>{pigcms{:L('_HOME_TXT_')}</a>
				</li>
				<li class="My fl">
					<a href="{pigcms{:U('Deliver/info')}">{pigcms{:L('_PROFILE_TXT_')}</a>
				</li>
			</ul>
		</div>
	</section>
<!-- 	<script src="http://api.map.baidu.com/api?type=quick&ak=4c1bb2055e24296bbaef36574877b4e2&v=1.0"></script> -->
	<script type="text/javascript">
        $('#set_btn').click(function () {
            window.location.href = "{pigcms{:U('Deliver/set_schedule')}";
        });

        var work_list = JSON.parse('{pigcms{$work_list}');

        var time_str = parseInt("{pigcms{$today}");
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
                    $(this).removeClass();
                }
            });

            set_work_list();
        });

        function set_work_list() {
            var html = '';
            if(typeof(work_list[init_num]) != 'undefined'){
                var curr_work = work_list[init_num]['ids'];
                for(var i=0;i<curr_work.length;i++) {
                    html += '<div>';
                    html += format_time(curr_work[i]['start_time']) + ' -- ' + format_time(curr_work[i]['end_time']);
                    html += '</div>';
                }
            }

            $('#work_list').html(html);
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
    </script>
</body>
</html>