<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$deliver_session['name']}-{pigcms{:L('_STATISTICS_TXT_')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
<script src="{pigcms{$static_path}js/jquery.min.js"></script>
<!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>
<style>
    body{background-color: #F8F8F8;}
    .list_head{
        width: 90%;
        margin: 5px auto;
        font-weight: bold;
        color: #ffa52d;
    }
    .list_order{
        display: flex;
        width: 85%;
        margin: 5px auto;
        line-height: 50px;
        font-size: 14px;
        color: #555555;
        border-bottom: 1px solid #EEEEEE;
        cursor: pointer;
    }
    .list_order span{
        flex: 1 1 21%;
    }
    .order_title{
        padding: 70px 5% 10px 5%;
        font-size: 16px;
        color: white;
    }
    .span_right{
        float: right;
    }
    .order_num{
        width: 90%;
        margin: 5px auto;
        padding: 10px 0 20px 0;
        font-weight: bold;
        font-size: 18px;
        color: #294068;
    }
    .order_time{
        width: 90%;
        margin: -10px auto 5px auto;
        padding: 0px 0 20px 0;
        font-size: 14px;
        color: #555555;
    }
    .time_sub{
        margin-left: 32px;
        line-height: 25px;
    }
    .amount_div{
        width: 100%;
        background: #FAEED7;
        color: #555555;
        padding: 20px 0;
    }
    .amount_sub{
        margin-left: 5%;
        margin-right: 5%;
        padding-left: 32px;
        line-height: 25px;
    }
    .goods_detail li{
        width: 90%;
        margin: -10px auto 0 auto;
    }
    .goods_detail .goods_item{
        margin-left: 32px;
    }
    .goods_item_sub{
        margin-left: 60px;
    }
    .goods_item span.on{
        width: 20px;
        height: 20px;
        border: 1px solid #294068;
        border-radius: 5px;
        display: inline-block;
        text-align: center;
        line-height: 20px;
        color: #294068;
    }
</style>
<body>
    <include file="header" />
    <div class="order_title" style="background: #294068;">
        <span>
            {pigcms{$supply['statusStr']}
        </span>
        <span class="span_right">10 hr 59 min</span>
    </div>
    <div class="order_num" style="border-bottom: 1px solid #999999;">
        <span>
            Order {pigcms{$supply['order_id']}
        </span>
        <if condition="$supply['pay_method'] neq 1">
            <span class="span_right">
                <label style="border: 1px solid #294068; border-radius: 5px;font-size: 14px;padding: 5px 10px;">
                    {pigcms{:L('_ND_CASH_')}
                </label>
            </span>
        </if>
    </div>
    <div class="order_num">
        <span class="material-icons" style="vertical-align: text-top;">restaurant</span>
        <span style="margin-left: -10px;">{pigcms{:lang_substr($store['name'],C('DEFAULT_LANG'))}</span>
    </div>
    <div class="order_time">
        <div class="time_sub">
            <span>{pigcms{:L('_ND_ORDERTYPE_')}</span>
            <span class="span_right">
                <if condition="$supply['get_type'] eq 1">
                    Assigned
                </if>
                <if condition="$supply['get_type'] eq 2">
                    From Courier - {pigcms{$supply['change_name']}
                </if>
                <if condition="$supply['get_type'] eq 0">
                    Accepted
                </if>
            </span>
        </div>
        <div class="time_sub">
            <span>Order Placed</span>
            <span class="span_right">
                {pigcms{$order['create_time']|date="Y-m-d H:i",###}
            </span>
        </div>
        <div class="time_sub">
            <span>{pigcms{:L('_ND_FOODPREPTIME_')}</span>
            <span class="span_right">
                {pigcms{$supply['meal_time']}
            </span>
        </div>
        <div class="time_sub">
            <span>{pigcms{:L('_ND_COMPLETIONTIME_')}</span>
            <span class="span_right">
                {pigcms{$supply['end_time']}
            </span>
        </div>
    </div>
    <div class="amount_div">
        <if condition="$order['deliver_cash'] gt 0">
        <div class="amount_sub" style="font-weight: bold;margin-bottom: 5px;">
            <span>{pigcms{:L('_ND_DUEONDELIVERY_')}</span>
            <span class="span_right">
                ${pigcms{$order['deliver_cash']|floatval}
            </span>
        </div>
        </if>
        <div class="amount_sub">
            <span>{pigcms{:L('_ND_DELIVERYFEE_')}</span>
            <span class="span_right">
                ${pigcms{$order['freight_charge']}
            </span>
        </div>
        <div class="amount_sub">
            <span>{pigcms{:L('_ND_TIP_')}</span>
            <span class="span_right">
                ${pigcms{$order['tip_charge']}
            </span>
        </div>
        <div class="amount_sub" style="font-weight: bold;">
            <span>Total</span>
            <span class="span_right">
                ${pigcms{$order['freight_charge']+$order['tip_charge']|floatval}
            </span>
        </div>
    </div>
    <div class="order_num">
        <span class="material-icons" style="vertical-align: text-top;">subject</span>
        <span style="margin-left: -10px;">Order Detail</span>
    </div>
    <div class="goods_detail">
        <ul>
            <volist name="goods" id="gdetail">
                <li class="clr">
                    <div class="goods_item">
                        <if condition="$gdetail['num'] gt 1">
                        <span class="on" style="background: #294068;color: white">
                        <else />
                        <span class="on">
                        </if>
                            {pigcms{$gdetail['num']}
                        </span>
                        <span style="margin-left: 3px;line-height: 20px;">
                            {pigcms{:lang_substr($gdetail['name'],C('DEFAULT_LANG'))}
                        </span>
                    </div>
                    <volist name="gdetail['spec_desc']" id="spec">
                        <div class="goods_item_sub">
                            {pigcms{$spec}
                        </div>
                    </volist>
                    <volist name="gdetail['dish']" id="dish">
                        <div class="goods_item_sub">
                            {pigcms{$dish['name']}
                            <volist name="dish['list']" id="dish_one">
                                <br><label style="color:#999;font-size: 12px">- {pigcms{$dish_one}</label>
                            </volist>
                        </div>
                    </volist>
                </li>
            </volist>
        </ul>
    </div>
<script>
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
</script>
</body>
</html>
