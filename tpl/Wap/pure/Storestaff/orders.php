<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Order History & Statistics</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
    <script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>
<style>
    #main{
        padding: 20px 5%;
        color: #999999;
        line-height: 16px;
        display: inline-block;
        font-size: 12px;
        width: 100%;
        box-sizing: border-box;
    }
    .Statistics_top{ background: #fff; display: flex;}
    .Statistics_top a{
        display: block;
        text-align: center;
        margin: 20px 5%;
        width: 40%;
        line-height: 20px;
        border: 1px solid #ffa52d;
        border-radius: 5px;
        height: 60px;
    }
    .Statistics_top a h2{
        background: url('{pigcms{$static_path}images/tjt_03.png') no-repeat;
        background-position: center right 10%;
        background-size: 8px;
        font-size: 14px;
        color: #22303c;
        width: auto;
    }
    .Statistics_top a input{
        font-size: 12px;
        text-align: center;
        width: 90%;
        line-height: 20px;
        border: none;
        color: #999999;
    }
</style>
<body>
<include file="header" />
<div id="main">
    <div style="text-align: center;font-size: 16px;">{pigcms{$store.name}</div>
    <div class="Statistics_top clr">
        <a href="javascript:void(0);" id="begin">
            <h2>Start Date</h2>
            <input type="text" readonly="readonly" placeholder="{pigcms{:L('_ND_STARTDATE_')}"  name="appDate" id="appDate" value="{pigcms{$_GET['begin_time']}">
        </a>
        <a href="javascript:void(0)" id="end">
            <h2>End Date</h2>
            <input type="text" readonly="readonly" placeholder="{pigcms{:L('_ND_ENDDATE_')}"  name="appDate1" id="appDate1" value="{pigcms{$_GET['end_time']}">
        </a>
    </div>

    <!--div class="to_btn" id="to_report" style="width: 100%">
        View Report
    </div-->
    <volist name="order_list" id="vo">
        <div class="order_h_list" data-id="{pigcms{$vo.order_id}">
            <span style="width: 20%;">#{pigcms{$vo.order_id}</span>
            <span>{pigcms{$vo.create_time|date="Y-m-d H:i",###}</span>
            <span style="width: 15%;">${pigcms{$vo.goods_price}</span>
            <span style="float: right">{pigcms{$vo.status}</span>
        </div>
    </volist>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
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
                    location.href="{pigcms{:U('Storestaff/orders')}&begin_time="+valueText+'&end_time='+$("#appDate1").val();
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
                    location.href="{pigcms{:U('Storestaff/orders')}&end_time="+valueText+'&begin_time='+$("#appDate").val();
                }
            }
        };
        $("#begin").mobiscroll($.extend(begin['date'], begin['default']));
        $("#end").mobiscroll($.extend(enddate['date'], enddate['default']));
    });

    $('#to_report').click(function () {
        alert('Report');
    });

    $('.order_h_list').click(function () {
        var order_id = $(this).data('id');
        window.location.href = "{pigcms{:U('Storestaff/show_order')}&order_id="+order_id;
    });
</script>

