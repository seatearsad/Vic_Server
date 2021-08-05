<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>My Statistics</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
    <script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>

<style>
    body{
        background-color: white;
    }
    .summary{
        width: 80%;
        margin: 10px auto;
        border: 1px solid #ffa52d;
        border-radius: 2px;
        font-size: 12px;
        color: #777777;
        padding: 10px 5%;
        text-align: center;
    }
    .summary_txt{
        width: 80%;
        margin: 10px auto;
        font-size: 12px;
        color: #777777;
        padding: 5px;
        line-height: 1.5;
    }
    .su_left{
        display: inline-block;
        width: 70%;
        text-align: left;
        font-size: 12px;
        line-height: 25px;
    }
    .su_right{
        display: inline-block;
        width: 30%;
        text-align: right;
        font-size: 12px;
        line-height: 25px;
    }
    .y_c{
        color: #ffa52d;
    }
    .order_history{
        color: white;
        background-color: #ffa52d;
        line-height: 25px;
        margin: 20px auto;
        border-radius: 1px;
        cursor: pointer;
        width: 50%;
        font-size: 10px;
    }
    .my_earning{
        text-align: center;
        margin: 20px auto;
        font-size: 12px;
        color: #777777;
    }
    .my_earning span{
        display: inline-block;
    }
    .my_earning span.btn{
        padding: 2px 5px;
        background-color: #AAAAAA;
        border-radius: 1px;
        border: 1px solid #ffa52d;
        color: white;
        margin-left: 10px;
        cursor: pointer;
    }
    .my_earning span.act{
        background-color: #ffa52d;
    }
    .earn_show{
        font-size: 12px;
        color: #777777;
        width: 80%;
        margin-left: 5%;
        margin-top: 20px;
        border-radius: 2px;
        padding: 8px 5%;
        box-sizing: padding-box;
        border: 1px solid #ffa52d;
    }
    .earn_show div{
        display: inline-block;
    }
    .earn_show .earn_num{
        color: #ffa52d;
    }
    .earn_title{
        margin-left: 5px;
    }
    .border_red{
        border: 1px solid orangered;
    }
    .border_red .earn_num{
        color: orangered;
    }
    .border_green{
        border: 1px solid forestgreen;
    }
    .border_green .earn_num{
        color: forestgreen;
    }
    .border_blue{
        border: 1px solid dodgerblue;
    }
    .border_blue .earn_num{
        color: dodgerblue;
    }
</style>
<body>
<include file="header" />
<section class="Statistics">
    <div class="Statistics_top clr">
        <a href="javascript:void(0);" id="begin">
            <h2><i>{pigcms{:L('_START_TIME_')}</i></h2>
            <input type="text" readonly="readonly" placeholder="{pigcms{:L('_ND_STARTDATE_')}"  name="appDate" id="appDate" value="{pigcms{$begin_time}">
        </a>
        <a href="javascript:void(0)" id="end">
            <h2><i>{pigcms{:L('_END_TIME_')}</i></h2>
            <input type="text" readonly="readonly" placeholder="{pigcms{:L('_ND_ENDDATE_')}"  name="appDate1" id="appDate1" value="{pigcms{$end_time}">
        </a>
    </div>
    <section class="summary">
        <div style="margin: 5px auto 15px auto;font-weight: bold;">{pigcms{:L('_ND_SUMMARY_')}</div>
        <div style="font-size: 0px">
            <span class="su_left y_c">{pigcms{:L('_ND_TOTALORDER_')}</span>
            <span class="su_right y_c">{pigcms{$order_count|default=0}</span>
        </div>
        <div style="font-size: 0px">
            <span class="su_left">{pigcms{:L('_ND_TOTALDELIVERYFEE_')}</span>
            <span class="su_right">${pigcms{$freight_charge|floatval}</span>
        </div>
        <div style="font-size: 0px">
            <span class="su_left">{pigcms{:L('_ND_TIPS_')}</span>
            <span class="su_right">${pigcms{$tip|default=0}+</span>
        </div>
        <div style="font-size: 0px">
            <span class="su_left" style="font-weight: bold;">Earning by Delivery Fees + Tips, or</span>
            <span class="su_right" style="font-weight: bold;">${pigcms{$freight_charge+$tip}+</span>
        </div>
        <div style="font-size: 0px">
            <span class="su_left" style="font-weight: bold;">Guaranteed Earning ($10/order)*</span>
            <span class="su_right" style="font-weight: bold;">${pigcms{$guara_money|floatval}</span>
        </div>
        <div style="border-bottom: 1px solid lightgray;margin: 15px auto;"></div>
        <div style="font-size: 0px">
            <span class="su_left" style="font-weight: bold;">{pigcms{:L('_ND_ACTUALEARN_')}</span>
            <span class="su_right" style="font-weight: bold;">
                    <if condition="$guara_money gt $freight_charge+$tip">
                        ${pigcms{$guara_money|floatval}
                    <else />
                        ${pigcms{$freight_charge+$tip}+
                    </if>
                </span>
        </div>
        <div style="font-size: 0px">
            <span class="su_left">{pigcms{:L('_ND_CASHREC_')}</span>
            <span class="su_right">(${pigcms{$offline_money|floatval})</span>
        </div>
        <div style="font-size: 0px">
            <span class="su_left y_c">{pigcms{:L('_ND_AMOUNTPAYABLE_')}</span>
            <span class="su_right y_c">
                    <if condition="$guara_money gt $freight_charge+$tip">
                        ${pigcms{$guara_money-$offline_money|floatval}
                    <else />
                        ${pigcms{$freight_charge+$tip-$offline_money|floatval}
                    </if>
                </span>
        </div>
        <div class="order_history">
            {pigcms{:L('_ND_VIEWORDERHIS_')}
        </div>
    </section>
    <!--div class="summary_txt">
        * Effective on February 16th, 2021, you're guaranteed a ${pigcms{$one_money} average earning per order. If the total of delivery fees + tips is lower than the guaranteed earning for the pay period, you will be paid based on the guaranteed earning calculation. Please note that this only applies to your pay period, which is the 1st-15th and 16th-31st of every month. If you select a different date range, the number given will not be accurate.
    </div-->
    <!--section class="my_earning">
        <span>{pigcms{:L('_ND_MYEARNING_')}</span>
        <span class="btn act" data-type="0">{pigcms{:L('_ND_TODAY_')}</span>
        <span class="btn" data-type="1">{pigcms{:L('_ND_MONTH_')}</span>
        <span> = </span>
        <span id="all_money">${pigcms{$today_data['money']}</span>
    </section>
    <div class="earn_show">
        <div class="earn_num" id="all_count">{pigcms{$today_data['num']}</div>
        <div class="earn_title">Orders Delivered in Total</div>
    </div>
    <div class="earn_show">
        <div class="earn_num" id="all_distance">{pigcms{$today_data['distance']} Km</div>
        <div class="earn_title">Driven with Tutti</div>
    </div>
    <div class="earn_show">
        <div class="earn_num" id="all_ave">${pigcms{$today_data['money']/$today_data['num']|floatval}</div>
        <div class="earn_title">Average Earning per Order</div>
    </div-->
</section>
<script type="text/javascript">
    var DetailUrl = "{pigcms{:U('Wap/Deliver/detail', array('supply_id'=>'d%'))}";
    $(function () {
        var begin = {};
        begin.date = {preset : 'date'};
        begin.default = {
            theme: 'android-ics light', //皮肤样式
            mode: 'scroller', //日期选择模式
            display: 'bottom', //显示方式
            dateFormat: 'yyyy-mm-dd',
            lang:'en',
            onSelect: function (valueText, inst) {
                $("#appDate").val(valueText);
                if ($("#appDate1").val() == '') {
                } else {
                    location.href="{pigcms{:U('Deliver/statistics')}&begin_time="+valueText+'&end_time='+$("#appDate1").val();
                }
            }
        };
        var enddate = {};
        enddate.date = {preset : 'date'};
        enddate.default = {
            theme: 'android-ics light', //皮肤样式
            mode: 'scroller', //日期选择模式
            display: 'bottom', //显示方式
            dateFormat: 'yyyy-mm-dd',
            lang:'en',
            onSelect: function (valueText, inst) {
                $("#appDate1").val(valueText);
                if ($("#appDate").val() == '') {
                } else {
                    location.href="{pigcms{:U('Deliver/statistics')}&end_time="+valueText+'&begin_time='+$("#appDate").val();
                }
            }
        };
        $("#begin").mobiscroll($.extend(begin['date'], begin['default']));
        $("#end").mobiscroll($.extend(enddate['date'], enddate['default']));
    });

    var is_flag = false;
    $(document).on('click', '.go_detail', function(e){
        e.stopPropagation();
        if (is_flag) {
            return false;
        }
        is_flag = true;
        var supply_id = $(this).attr("data-id");
        location.href = DetailUrl.replace(/d%/, supply_id);
    });
    var ua = navigator.userAgent;
    if(!ua.match(/TuttiDeliver/i)) {
        navigator.geolocation.getCurrentPosition(function (position) {
            updatePosition(position.coords.latitude,position.coords.longitude);
        });
    }
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

    $('.order_history').click(function () {
        var begin_time = "{pigcms{$begin_time}";
        var end_time = "{pigcms{$end_time}";

        if(begin_time != '' && end_time != '')
            location.href = "{pigcms{:U('Deliver/orders')}&begin_time="+begin_time+"&end_time="+end_time;
        else
            location.href = "{pigcms{:U('Deliver/orders')}";
    });

    $('.my_earning').find('.btn').each(function () {
        $(this).click(function () {
            var type = $(this).data('type');
            if(type == 0){
                $('#all_money').html("${pigcms{$today_data['money']}");
                $('#all_count').html("{pigcms{$today_data['num']}");
                $('#all_distance').html("{pigcms{$today_data['distance']}KM");
                $('#all_ave').html("${pigcms{$today_data['money']/$today_data['num']|floatval}");
            }else{
                $('#all_money').html("${pigcms{$month_data['money']}");
                $('#all_count').html("{pigcms{$month_data['num']}");
                $('#all_distance').html("{pigcms{$month_data['distance']}KM");
                $('#all_ave').html("${pigcms{$month_data['money']/$month_data['num']|floatval}");
            }
            $('.my_earning').find('.btn').each(function () {
                $(this).removeClass('act');
            });
            $(this).addClass('act');
        });
    });
</script>
</body>
</html>