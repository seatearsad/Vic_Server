<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>My Account</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script>
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
    </script>
    <style>
        body{
            background-color: white;
        }
        #all{
            width: 90%;
            margin: 60px auto 20px auto;
            font-size: 12px;
            color: #333333;
        }
        #title{
            font-size: 16px;
            line-height: 40px;
            margin: 30px auto 20px auto;
            background-color: lightgray;
            border-radius: 10px;
            padding: 10px;
            display: inline-block;
            width: 100%;
            box-sizing: border-box;
            background-image: url("{pigcms{$static_path}img/deliver_menu/calendar_blue.png");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size:auto 60% ;
        }
        #title div{
            line-height: 22px;
        }
        #title.active{
            background-image: url("{pigcms{$static_path}img/deliver_menu/calendar.png");
            background-color: #f2dede;
        }
        #title.blue{
            background-color: lightsteelblue;
        }
        .f_t{
            font-size: 14px;
            height:220px;
            background-image:url('{pigcms{$static_path}img/deliver_menu/city_background.png');
            background-repeat: no-repeat;
            background-position: center top;
            background-size: 100% auto;
            position: relative;
        }
        .f_t span{
            position: absolute;
            width: 60%;
            height: 80%;
            bottom: 10px;
            left: 20%;
            background-image:url("{pigcms{$static_path}img/deliver_menu/car.png");
            background-repeat: no-repeat;
            background-position: center bottom;
            background-size: 100% auto;
        }
        .tip{
            font-size: 14px;
            margin-top: 10px;
            padding: 5px;
        }
        .clock_btn{
            font-size: 18px;
            display: inline-block;
            width: 50%;
            text-align: center;
            color: white;
            background-color: dimgrey;
            line-height: 50px;
            border-radius: 15px;
            cursor: pointer;
        }
        .clock_btn.active{
            background-color: #0C3E72;
        }
    </style>
</head>
<body>
<include file="header" />
<div id="all">
    <div id="title" <if condition='$is_change eq 1'>class="active"<else /><if condition='$is_scheduled eq 1'>class="blue"</if></if>>
        <div>
            <if condition='$is_change eq 1'>
                Current shift
                <else />
                Next shift
            </if>
        </div>
        <div style="font-weight: bold">{pigcms{$show_time}</div>
    </div>
    <div class="f_t">
        <span>

        </span>
    </div>
    <div class="tip">
        <if condition='$is_change eq 1'>
            Please ensure to carry your <b>delivery bag</b> when picking up and handing over orders.
            <else />
            <if condition="$is_scheduled eq 1 AND $city['urgent_time'] eq 0">
                <b>You can work during your shifts or when we’re busy.</b><br/>
                We’ll send a notification to let you know!
            </if>
            <if condition="$is_scheduled eq 0 AND $city['urgent_time'] eq 0">
                <b>You didn’t shedule any shift.</b><br/>
                We’ll send you a notification when we’re busy. Remember to schedule your shift so you can work in your preferred time!
            </if>
        </if>
        <if condition="$city['urgent_time'] neq 0">
            <b>We’re busy now!</b><br/>
            Press "Clock In" to accept orders.
        </if>
    </div>
    <div style="text-align: center;margin-top: 20px">
        <if condition="($city['urgent_time'] neq 0 OR $is_change eq 1)">
            <span class="clock_btn active" ref="0">
                Clock In
            </span>
            <else />
            <span class="clock_btn">
                Clock In
            </span>
        </if>

    </div>
</div>
<script type="text/javascript">
    $(".clock_btn.active").click(function(){
        $.get("/wap.php?g=Wap&c=Deliver&a=index&action=changeWorkstatus&type="+$(this).attr('ref'), function(){
            window.location.reload();
        });
    });
    $('#change_pwd').click(function () {
        location.href = "{pigcms{:U('Deliver/change_pwd')}";
    });
    $('#info').click(function () {
        location.href = "{pigcms{:U('Deliver/ver_info')}";
    });
    $('#bank').click(function () {
        location.href = "{pigcms{:U('Deliver/bank_info')}";
    });

    changeHeight();

    function changeHeight(){
        if(window.innerHeight >= window.innerWidth){
            $('.f_t').css({'height':'220px',"background-size": "100% auto"});
            $('.f_t span').css({'width':'60%','left':'20%'});
        }else{
            $('.f_t').css({'height':'350px',"background-size": "auto 100%"});
            $('.f_t span').css({'width':'30%','left':'35%'});
        }
    }
    window.onresize = function(){
        //console.log("width:"+window.innerWidth+";height:"+window.innerHeight);
        changeHeight();
    }
</script>
</body>
</html>