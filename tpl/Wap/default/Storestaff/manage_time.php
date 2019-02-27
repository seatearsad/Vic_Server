<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_STORE_CENTER_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link href="{pigcms{$static_path}js/mobiscroll/mobiscroll.custom.min.css" rel="stylesheet" type="text/css">
<style>
	.startOrder{color: #fff;float: right;background: green;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: left}
	.stopOrder{color: #000;float: right;background: #ccc;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: left}
	.addorder{color: #000;float: right;color: #fff;background-color: #06c1ae;;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: right}
</style>
    <style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    #orders .dealcard-block-right {
			margin-left:1px;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 0;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }

	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
		#nav-dropdown{height: 1.7rem;}
		#filtercon select{height: 100%;line-height: normal;width:100%;}
		#filtercon{margin: 0 .15rem;}
.find_div {
margin: .15rem 0;
}
	#filtercon input{background-color: #fff;
		width: 100%;
		border: none;
		background: rgba(255, 255, 255, 0);
		outline-style: none;
		display: block;
		line-height: .28rem;
		height: 100%;
		font-size: .28rem;
		padding: 0
}
		#find_submit{
			position: absolute;
			right: 0rem;
			top: .15rem;
			width: 1.2rem;
			height: .7rem;;
			-webkit-box-sizing: border-box;
		}
 .dealcard-block-right li{
    font-size: .266rem;
font-weight: 400;
 }
.dealcard-block-right .dth{font-weight: bold;}
 .ulrightdiv{
	float: right;
	position: relative;
	top: -60px;
	margin-right: 15px;
	}
	dl.list .dd-padding{padding: .28rem 0.1rem;}
	.red{color:red;}
.top-btn-a a{color: #fff;margin-top: 10px;}
.top-btn-a .lb{margin-left: 20px;}
.top-btn-a .rb{float: right;margin-right: 20px;}
.dealcard-block-right{padding: 0 10px;}
#orders a{color: #333;}
#orders .td a{color: green;}
.find_type_div{
	position: absolute;
left: 0rem;
width: 1.7rem;
height: .7rem;
text-align: center;
background: white;
}
.find_txt_div{
vertical-align: middle;
position: relative;
margin-right: 1.3rem;
margin-left:1.8rem;
border-radius: .06rem;
border: 1px #CCC solid;
height: .7rem;
line-height: .7rem;
}
  .dealcard-block-right li.btm_li{
     margin-bottom: .18rem;
 }

.store_name{
    height: 20px;
    margin-left: 105px;
    margin-top: -100px;
}
.time_list{
    margin-top:.2rem;
    border: 1px solid #ccc;
    width: 98%;
    margin-left: 1%;
}
.time_list ul{
    width: 30%;
    background-color: #999;
}
.time_list ul li{
    width: 100%;
    text-align: center;
    height: 30px;
    line-height: 30px;
    border-bottom: 1px solid #ffffff;
    color: #ffffff;
    cursor: pointer;
}
.time_list ul li:hover{
    background-color: #0A8DE4;
}
.h_week{
    background-color: #0A8DE4;
}
.week_time{
    margin-left: 30%;
    margin-top: -217px;
    width: 70%;
    height: 217px;
}
.submit{
    width: 100px;
    height: 30px;
    background-color: #0A8DE4;
    text-align: center;
    line-height: 30px;
    margin: 20px auto;
    color: #ffffff;
    cursor: pointer;
}
.week_time dl{
    width: 100%;
    height: 100%;
    background-color: #ffffff;
    margin: 0px;
}
.week_time dl dd{
    width: 49%;
    text-align: center;
    height: 30px;
    line-height: 30px;
    display: inline-block;
    background-color: #ffffff;
    color: #333;
    border: 0px;
    margin-left: 0px;
}
.week_time dl #time_dd{
    height: 40px;
}
.week_time dl dd input{
    width: 100px;
    text-align: center;
    height: 25px;
}
#time_form input{
    display: none;
}
.week_time dl dd.time_desc{
    width: 100%;
    text-align: center;
}
</style>
</head>
<body>
	<dl class="list"  style="border-top:none;margin-top:0rem;">
		<dd id="filtercon">
			<div class="find_div">
                <div style="height: 110px;">
                    <img src="{pigcms{$store.image}" width="100" height="100">
                    <div class="store_name">
                        <div>{pigcms{$store.name}</div>
                        <div style="margin-top: 10px;">
                            {pigcms{:L('_STORE_STATUS_')}:
                            <if condition="$store['status']">
                                {pigcms{:L('_STORE_NORMAL_OPEN_')}
                            <else/>
                                {pigcms{:L('_STORE_ON_HOLIDAY_')}
                            </if>
                        </div>
                        <div style="margin-top: 10px;">
                            {pigcms{:L('_STORE_OPEN_CLOSE_')}:
                            <if condition="$store['is_close']">{pigcms{:L('_AT_REST_')}<else />{pigcms{:L('_AT_BUSINESS_')}</if>
                        </div>
                    </div>
                </div>
			</div>
		</dd>
	</dl>
	<dl class="list"></dl>
    <div class="time_list">
        <ul>
            <li id="week_1">{pigcms{:L('_STORE_MONDAY_')}</li>
            <li id="week_2">{pigcms{:L('_STORE_TUESDAY_')}</li>
            <li id="week_3">{pigcms{:L('_STORE_WEDNESDAY_')}</li>
            <li id="week_4">{pigcms{:L('_STORE_THURSDAY_')}</li>
            <li id="week_5">{pigcms{:L('_STORE_FRIDAY_')}</li>
            <li id="week_6">{pigcms{:L('_STORE_SATURDAY_')}</li>
            <li id="week_0">{pigcms{:L('_STORE_SUNDAY_')}</li>
        </ul>
        <div class="week_time">
            <dl>
                <dd>{pigcms{:L('_STORE_START_TIME_')}</dd>
                <dd>{pigcms{:L('_STORE_END_TIME_')}</dd>
                <dd id="time_dd">
                    <input type="text" name="open_time_1" id="open_time_1">
                </dd>
                <dd id="time_dd">
                    <input type="text" name="close_time_1" id="close_time_1">
                </dd>
                <dd id="time_dd">
                    <input type="text" name="open_time_2" id="open_time_2">
                </dd>
                <dd id="time_dd">
                    <input type="text" name="close_time_2" id="close_time_2">
                </dd>
                <dd id="time_dd">
                    <input type="text" name="open_time_3" id="open_time_3">
                </dd>
                <dd id="time_dd">
                    <input type="text" name="close_time_3" id="close_time_3">
                </dd>
                <dd class="time_desc">* {pigcms{:L('_STORE_TIME_TIP_1_')}.</dd>
                <dd class="time_desc">* {pigcms{:L('_STORE_START_END_TIP_')}.</dd>
            </dl>
        </div>
    </div>
    <div class="submit">
            Submit
    </div>
    <form id="time_form" autocomplete="off" method="post" action="{pigcms{:U('Storestaff/edit_time')}">
        <php>
            for($i=0;$i<21;$i++){
                $open_num = 'open_'.($i+1);
                $close_num = 'close_'.($i+1);
                echo "<input type='text' value=".$store[$open_num]." name=".$open_num." id=".$open_num." />";
                echo "<input type='text' value=".$store[$close_num]." name=".$close_num." id=".$close_num." />";
            }
        </php>
    </form>

    <include file="Storestaff:footer"/>
</body>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script>
    var week_num = '{pigcms{$week_num}';
    changeWeek();

    $('.time_list').find('li').each(function () {
        $(this).click(function () {
            week_num = $(this).attr('id').split('_')[1];
            changeWeek();
        });
    });

    function changeWeek(){
        $('.time_list').find('li').each(function () {
            if($(this).attr('id') == 'week_'+ week_num){
                $(this).attr('class','h_week');
                var js_num = week_num;
                if(week_num == 0) {
                    js_num = 7;
                }
                for(var i=1;i<4;i++){
                    var num = (js_num-1)*3 + i;
                    var open_input = 'open_time_' + i;
                    var close_input = 'close_time_' + i;
                    var open_time = $('#open_' + num).val();
                    var close_time = $('#close_' + num).val();

                    $('#'+open_input).val(open_time);
                    $('#'+close_input).val(close_time);
                }
            }else{
                $(this).removeClass();
            }
        });
    }

    $('.submit').click(function () {
        var is_tip = checkAllTime();
        if(!is_tip){
            var re_data = {};
            $('#time_form').find('input').each(function () {
                re_data[$(this).attr('name')] = $(this).val();
            });
            $.post($('#time_form').attr('action'),re_data,function(data){
                if(data.status == 1){
                    layer.open({
                        title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                        time: 1,
                        content: data.info,
                    });
                }else{
                    alert('Fail');
                }
            });
        }
    });

    function checkAllTime(){
        var is_tip = false;
        for(var i=0;i<21;i++) {
            var num = i + 1;
            var open_time = $('#open_' + num).val();
            var close_time = $('#close_' + num).val();
            var starArr = open_time.split(':');
            var endArr = close_time.split(':');

            if (starArr[0] > endArr[0]) {
                is_tip = true;
            }

            if (starArr[0] == endArr[0]) {
                if (starArr[1] > endArr[1]) {
                    is_tip = true;
                }

                if (starArr[1] == endArr[1]) {
                    if (starArr[2] > endArr[2]) {
                        is_tip = true;
                    }
                }
            }
            if(is_tip){
                var w_n = parseInt(i/3);
                var o_n = num%3;
                week_num = w_n + 1;
                if(week_num == 7) week_num = 0;
                changeWeek();
                if (o_n == 0) o_n = 3;
                $('#open_time_' + o_n).focus();

                alert("{pigcms{:L('_STORE_START_END_TIP_')}");
                break;
            }
        }

        return is_tip;
    }

    var theme = "ios";
    var mode = "scroller";
    var display = "bottom";
    var lang="en";

    $('.week_time').find('input').each(function () {
        // Time demo initialization
        $(this).mobiscroll().time({
            theme: theme,
            mode: mode,
            display: display,
            timeFormat: 'HH:ii:ss',
            timeWheels: 'HHii',
            lang: lang
        });
        $(this).change(function () {
            var js_num = week_num;
            if(week_num == 0) {
                js_num = 7;
            }
            var input_name = $(this).attr('name').split('_');
            var num = input_name[2];
            var input_num = (js_num-1)*3 + parseInt(num);
            $('#'+input_name[0]+'_'+input_num).val($(this).val());
            //alert($('#'+input_name[0]+'_'+input_num).val());
        });
    });
    // $(window).resize(function () {
    //     if($(window).height() > $(window).width()){
    //         $('.submit').css('margin-top','50px');
    //         $('.week_time').css('height','317px');
    //     }
    // });
    if($(window).height() > $(window).width()){
        $('.submit').css('margin-top','50px');
        $('.week_time').css('height','317px');
    }


</script>
</html>