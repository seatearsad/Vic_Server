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
            position: relative;
        }
        .f_t span{
            position: absolute;
            width: 50%;
            height: 80%;
            bottom: 10px;
            left: 25%;
            background-image:url("{pigcms{$static_path}img/deliver_menu/expired.png");
            background-repeat: no-repeat;
            background-position: center bottom;
            background-size: 100% auto;
        }
        .tip{
            font-size: 14px;
            margin-top: 10px;
            padding: 5px;
            width: 90%;
            margin-left: 5%;
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
    <div class="f_t">
        <span>

        </span>
    </div>
    <div class="tip" style="text-align: center;font-size: 18px;">
        <b>ACTION REQUIRED</b>
    </div>
    <div class="tip">
        The following doc(s) are expired. Please upload a photo or you cannot continue working.
    </div>
    <ul style="width: 60%;margin-left: 20%;font-size: 14px;margin-top: 10px;line-height: 25px;">
        <volist name="expiry" id="e">
            <li style="list-style-type: disc;">{pigcms{$e}</li>
        </volist>
    </ul>
    <div style="text-align: center;margin-top: 20px">
            <span class="clock_btn" ref="0">
                Update Now
            </span>
    </div>
</div>
<script type="text/javascript">
    $(".clock_btn").click(function(){
        location.href = "{pigcms{:U('Deliver/account')}";
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
            $('.f_t span').css({'width':'50%','left':'25%'});
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