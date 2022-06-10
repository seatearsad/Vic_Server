<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$deliver_session['name']}-Order</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
<script src="{pigcms{$static_path}js/jquery.min.js"></script>
<!-- <script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
</head>
<style>
    body{background-color: #F8F8F8;position: unset}
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
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
    .goods_detail{
        margin-top: -10px;
    }
    .goods_detail li{
        width: 90%;
        margin: 10px auto 0 auto;
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
    #sub_btn{
        position: absolute;
        bottom: 30px;
        width: 90%;
        height: 40px;
        line-height: 40px;
        left: 5%;
        background-color: #ffa52d;
        color: white;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
        border-radius: 10px;
        cursor: pointer;
    }
    #sub_btn a{
        color: white;
        display: block;
    }
    #pay_online,#photo_btn{
        float: right;
        padding: 5px 10px;
        background-color: white;
        border: 1px solid #294068;
        border-radius: 10px;
        font-size: 14px;
        line-height: 25px;
        font-weight: bold;
    }
    .order_all{
        position: absolute;
        bottom: 100px;
        overflow: auto;
        top: 0;
        width: 100%;
        padding-bottom: 20px;
    }
    .touch_detail{
        color: #294068;
        font-size: 16px;
        padding-bottom: 0px;
    }
    .photo_tip{
        position: absolute;
        bottom: 75px;
        width: 100%;
        text-align: center;
        color: #909090;
        font-size: 14px;
    }

    #system_message{
        position: absolute;
        top:0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: black;
        opacity: 50%;
        z-index: 1000;
        display: none;
    }
    #message_content{
        position: absolute;
        width: 90%;
        top: 0;
        max-width: 480px;
        min-width: 240px;
        margin-left: auto;
        margin-right: auto;
        z-index: 1001;
    }
</style>
<body>
    <include file="index_header" />
    <div class="order_all" <if condition="$supply['status'] eq 5">style="bottom: 0;"</if>>
    <div class="order_title" style="background: #294068;">
        <span>
            {pigcms{$supply['statusStr']}
        </span>
        <if condition="$supply['status'] eq 2">
        <span class="span_right">
            <if condition="$supply['is_dinning'] eq 1">
                <label style="color: #ffa52d">Ready
                <else />
                <label>Ready in
            </if>
                {pigcms{$supply['show_dining_time']}
            </label>
        </span>
        </if>
        <if condition="$supply['status'] eq 5">
            <span class="span_right">
                {pigcms{$supply['totalTime']}
            </span>
        </if>
    </div>
    <div class="order_num" style="border-bottom: 1px solid #999999;">
        <span>
            Order {pigcms{$supply['order_id']}
        </span>
        <if condition="$order['pay_method'] neq 1">
            <span class="span_right">
                <label style="border: 1px solid #294068; border-radius: 5px;font-size: 14px;padding: 5px 10px;">
                    <if condition="$order['uid'] eq 0 and $supply['status'] neq 5">
                        {pigcms{:L('_ND_UNPAID_')}
                    </if>
                    <if condition="$order['uid'] eq 0 and $supply['pay_type'] eq 'moneris' and $supply['status'] eq 5">
                        {pigcms{:L('_ND_PAID_')}
                    </if>
                    <if condition="$order['uid'] eq 0 and $supply['pay_type'] neq 'moneris' and $supply['status'] eq 5">
                        {pigcms{:L('_ND_CASH_')}
                    </if>
                    <if condition="$order['uid'] neq 0 and $order['pay_method'] neq 1">
                        {pigcms{:L('_ND_CASH_')}
                    </if>
                </label>
            </span>
        </if>
    </div>
    <div class="order_num">
        <span class="material-icons" style="vertical-align: text-top;">restaurant</span>
        <span style="margin-left: -10px;">{pigcms{:lang_substr($store['name'],C('DEFAULT_LANG'))}</span>
    </div>
    <if condition="$supply['status'] eq 2">
        <div class="order_time">
            <div style="color: #333333;font-weight: bold;margin-left: 35px;margin-top: -15px;">
                {pigcms{$supply['from_site']}
            </div>
            <if condition="$store['trafficroute'] neq ''">
            <div style="margin-left: 35px;margin-top: 10px;">
                {pigcms{$store['trafficroute']}
            </div>
            </if>
            <div style="margin-left: 35px;margin-top: 20px;font-size: 18px">
                <span style="line-height: 40px;background-color: #294068;padding:10px 20px;color: white;border-radius: 20px;" id="open_map">
                    <label class="material-icons" style="vertical-align: middle;width: 30px;">open_in_new</label>
                    Get Direction
                </span>

                <span style="width: 40px;height:40px;border-radius: 20px;background-color: #294068;float: right">
                    <a href="tel:{pigcms{$store['phone']}" style="display: inline-block">
                        <label class="material-icons" style="color: white;font-size: 26px;margin-top: 7px;margin-left: 7px;">phone</label>
                    </a>
                </span>
            </div>
        </div>
    </if>
    <if condition="$supply['status'] eq 3 or $supply['status'] eq 4">
        <div class="order_num" style="margin-top:-25px;">
            <span class="material-icons" style="vertical-align: text-top;">person</span>
            <span style="margin-left: -10px;">{pigcms{$order['username']}</span>
        </div>
        <div class="order_time">
            <div style="color: #333333;font-weight: bold;margin-left: 30px;margin-top: -15px;">
                {pigcms{$supply.aim_site}
            </div>
            <div style="margin-left: 30px;margin-top: 20px;font-size: 18px">
                <span style="line-height: 40px;background-color: #294068;padding:10px 20px;color: white;border-radius: 20px;" id="open_map">
                    <label class="material-icons" style="vertical-align: middle;width: 30px;">open_in_new</label>
                    Get Direction
                </span>
                <span style="width: 40px;height:40px;border-radius: 20px;background-color: #294068;float: right">
                    <a href="tel:{pigcms{$order['userphone']}" style="display: inline-block">
                        <label class="material-icons" style="color: white;font-size: 26px;margin-top: 7px;margin-left: 7px;">phone</label>
                    </a>
                </span>
            </div>
        </div>
        <if condition="$order['not_touch'] eq 1 and $supply['status'] eq 4 and $order['address_detail'] neq ''">
        <div class="amount_div">
            <div class="order_time touch_detail">
                <span class="material-icons" style="vertical-align: middle">assignment</span>
                <span style="margin-left: -10px;font-weight: bold;">
                        No Contact Delivery
                </span>
                <div style="margin-left: 30px;font-size: 14px;color: #333333">
                    {pigcms{$order['address_detail']}
                </div>
                <div style="min-height:20px;padding-top: 10px;position: relative; display: block;">
                    <input type="hidden" name="photo" id="upload_img" />
                    <span class="img_0">
                    </span>
                    <span id="photo_btn">
                        <span class="material-icons" style="vertical-align: middle;width: 30px;">camera_alt</span>Delivery Photo
                    </span>
                </div>
            </div>
        </div>
        </if>

        <if condition="$order['not_touch'] eq 1 and $supply['status'] eq 4 and $order['address_detail'] eq ''">
            <div class="amount_div">
                <div class="order_time touch_detail">
                    <span class="material-icons" style="vertical-align: middle">assignment</span>
                    <span style="margin-left: -10px;font-weight: bold;">
                        No Contact Delivery
                    </span>
                    <div style="margin-left: 30px;font-size: 14px;color: #333333">
                        The customer didn't leave any instruction.
                    </div>
                    <div style="min-height:20px;padding-top: 10px;position: relative; display: block;">
                        <input type="hidden" name="photo" id="upload_img" />
                        <span class="img_0">
                        </span>
                            <span id="photo_btn">
                            <span class="material-icons" style="vertical-align: middle;width: 30px;">camera_alt</span>Delivery Photo
                        </span>
                    </div>
                </div>
            </div>
        </if>

        <if condition="$order['not_touch'] neq 1 and $order['address_detail'] neq '' and $supply['status'] eq 4">
            <div class="amount_div">
                <div class="order_time touch_detail">
                    <span class="material-icons" style="vertical-align: middle">assignment</span>
                    <span style="margin-left: -10px;font-weight: bold;">
                        Delivery Instruction
                    </span>
                    <div style="margin-left: 30px;font-size: 14px;color: #333333">
                        {pigcms{$order['address_detail']}
                    </div>
                    <div style="min-height:20px;padding-top: 10px;position: relative; display: block;">
                        <input type="hidden" name="photo" id="upload_img" />
                        <span class="img_0">
                        </span>
                            <span id="photo_btn">
                            <span class="material-icons" style="vertical-align: middle;width: 30px;">camera_alt</span>Delivery Photo
                        </span>
                    </div>
                </div>
            </div>
        </if>

        <if condition="$order['not_touch'] neq 1 and $order['address_detail'] eq '' and $supply['status'] eq 4">
            <div class="amount_div">
                <div class="order_time touch_detail">
                    <span class="material-icons" style="vertical-align: middle">assignment</span>
                    <span style="margin-left: -10px;font-weight: bold;">
                        Delivery Instruction
                    </span>
                    <div style="margin-left: 30px;font-size: 14px;color: #333333">
                        N/A
                    </div>
                    <div style="min-height:20px;padding-top: 10px;position: relative; display: block;">
                        <input type="hidden" name="photo" id="upload_img" />
                        <span class="img_0">
                        </span>
                            <span id="photo_btn">
                            <span class="material-icons" style="vertical-align: middle;width: 30px;">camera_alt</span>Delivery Photo
                        </span>
                    </div>
                </div>
            </div>
        </if>
    </if>
    <if condition="$supply['status'] eq 4 and ($supply['uid'] eq 0 or $order['pay_method'] neq 1)">
        <div class="amount_div" style="margin-top: 10px;">
            <div class="order_time" style="color: #294068;font-size: 16px;padding-bottom: 0px;">
                <span style="padding-right:15px; padding-left: 10px; font-size: 18px;"> $ </span>
                <span style="margin-left: -10px;font-weight: bold;">
                    Collect From Customer: ${pigcms{$supply['deliver_cash']|floatval}
                </span>
                <div style="margin-left: 30px;font-size: 14px;color: #333333;margin-top: 10px;">
                    <if condition="$supply['uid'] eq 0">
                        Collect payment from the customer by cash or online
                    </if>
                    <if condition="$order['pay_method'] neq 1">
                        Please collect cash when you arrive. You can keep them and we’ll deduct the order price from your earnings
                    </if>
                </div>
                <div style="height: 20px;padding-top: 10px;">
                    <span id="pay_online">
                        Pay Online
                    </span>
                </div>
            </div>
        </div>
    </if>
    <if condition="$supply['status'] eq 4 and $store['tag_tip'] eq 1">
        <div class="amount_div" style="margin-top: 10px;background-color: #E3EAFD">
            <div class="touch_detail" style="margin: 0 auto;width: 90%;">
                <span class="material-icons" style="vertical-align: middle">account_box</span>
                <span style="margin-left: -10px;font-weight: bold;">
                        ID Check Required
                    </span>
                <span class="material-icons" id="id_check" style="vertical-align: middle;float: right;cursor: pointer">info_outlined</span>
            </div>
        </div>
    </if>
    <if condition="$supply['status'] eq 5">
        <div class="order_time">
            <div class="time_sub">
                <span>{pigcms{:L('_ND_ORDERTYPE_')}</span>
                <span class="span_right">
                    <if condition="$supply['get_type'] eq 1">
                        Assigned
                    </if>
                    <if condition="$supply['get_type'] eq 2">
                        Assigned
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
            <if condition="$supply['uid'] eq 0 or $order['pay_method'] neq 1">
            <div class="amount_sub" style="font-weight: bold;">
                <span>
                    Collect From Customer:
                </span>
                <span class="span_right">
                    ${pigcms{$supply['deliver_cash']|floatval}
                </span>
            </div>
            </if>
            <div class="amount_sub">
                <span>{pigcms{:L('_ND_DELIVERYFEE_')}</span>
                <span class="span_right">
                    ${pigcms{$order['freight_charge']+$order['tip_charge']|floatval}
                </span>
            </div>
            <div class="amount_sub">
                <span>Bonus</span>
                <span class="span_right">
                    ${pigcms{$supply['bonus']}
                </span>
            </div>
            <div class="amount_sub" style="font-weight: bold;">
                <span>Total</span>
                <span class="span_right">
                    ${pigcms{$order['freight_charge']+$order['tip_charge']+$supply['bonus']|floatval}
                </span>
            </div>
        </div>
        <if condition="$supply['photo'] neq ''">
        <div class="amount_div" style="margin-top: 10px;">
            <div class="order_time touch_detail">
                <span class="material-icons" style="vertical-align: middle">assignment</span>
                <span style="margin-left: -10px;font-weight: bold;">Delivery Photo</span>
                <div style="min-height:20px;padding-top: 10px;position: relative; display: block;">
                    <input type="hidden" name="photo" id="upload_img" value="{pigcms{$supply['photo']}" />
                    <span class="img_0">
                        <img src="{pigcms{$supply['photo']}" style="width: 150px" />
                    </span>
                </div>
            </div>
        </div>
        </if>
    </if>
    <div class="order_num">
        <if condition="$supply['status'] eq 5">
            <span class="material-icons" style="vertical-align: text-top;">subject</span>
            <span style="margin-left: -10px;">Order Detail</span>
        </if>
        <if condition="$supply['status'] eq 2">
            <span class="material-icons" style="vertical-align: text-top;">person</span>
            <span style="margin-left: -10px;">{pigcms{$order['username']}</span>
        </if>
        <if condition="$supply['status'] eq 3 and $order['deliver_cash'] gt 0">
            <if condition="$supply['uid'] eq 0 or $order['pay_method'] neq 1">
            <div style="margin-left: 30px;font-size: 15px;margin-bottom: 10px;">
                Collect From Customer: ${pigcms{$supply['deliver_cash']|floatval}
            </div>
            </if>
        </if>
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
    </div>
    <if condition="$supply['status'] neq 5">
        <if condition="$order['not_touch'] eq 1">
            <div class="photo_tip">
                <span class="material-icons" style="vertical-align: middle;width: 30px;">camera_alt</span>Take a photo before completion
            </div>
        </if>
    <div id="sub_btn">
        <if condition="$supply['status'] eq 2">
            <a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/pick')}">
                I got the order!
            </a>
            <elseif condition="$supply['status'] eq 3" />
            <a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/send')}">
                I'm heading to the customer!
            </a>
            <elseif condition="$supply['status'] eq 4" />
            <a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/my')}">
                Delivered!
            </a>
        </if>
    </div>
    </if>
    <div id="system_message"></div>
    <div id="message_content"></div>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
var  uploader = WebUploader.create({
    auto: true,
    swf: '{pigcms{$static_public}js/Uploader.swf',
    server: "{pigcms{:U('Deliver/ajax_upload_photo')}",
    accept: {
        title: 'Images',
        extensions: 'gif,jpg,jpeg,png',
        mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
    }
});
uploader.addButton({
    id:'#photo_btn',
    name:'image_0',
    multiple:false
});

uploader.on('fileQueued',function(file){
    if($('.upload_pic_li').size() >= 5){
        uploader.cancelFile(file);
        alert('最多上传5个图片！');
        return false;
    }
});
uploader.on('uploadSuccess',function(file,response){
    if(response.error == 0){
        $('.img_0').html('<img src="'+response.url+'"/>');
        $('.img_0 img').css("width","150px");
        $('#upload_img').val(response.file);
        $('.img_0 img').unbind('click');
        $('.img_0 img').click(openPhoto);
    }else{
        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:"Oops! Something went wrong. Photos no larger than 5MB is recommended! Please try again.", btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
    }
});

uploader.on('uploadError', function(file,reason){
    $('.loading'+file.id).remove();
    alert('上传失败！请重试。');
});

var ua = navigator.userAgent;
if(!ua.match(/TuttiDeliver/i)) {
    navigator.geolocation.getCurrentPosition(function (position) {
        updatePosition(position.coords.latitude,position.coords.longitude);
    });
}

if(ua.match(/IPhonex/i)) {
    $('.order_all').css("top","24px");
}
//ios app 更新位置
function updatePosition(lat,lng){
    var message = '';
    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
        if(result){
            message = result.msg;
        }else {
            message = 'Error';
        }
    },'json');

    return message;
}

function appToPosition(lat,long){
    updatePosition(lat,long);

    return "{pigcms{$deliver_session['uid']}";
}

$('#open_map').click(function () {
    var status = "{pigcms{$supply['status']}";
    if(typeof (window.linkJs) != 'undefined'){
        var address;
        if (status == 2)
            address = "{pigcms{$supply['from_site']}";
        else
            address = "{pigcms{$supply['aim_site']}";

        window.linkJs.openGoogleMap(address);
    }else {
        var url = '';
        if (status == 2)
            url = "https://maps.google.com/maps?q={pigcms{$supply['from_site']}&z=17&hl=en";
        else
            url = "https://maps.google.com/maps?q={pigcms{$supply['aim_site']}&z=17&hl=en";

        location.href = url;
    }
});

$('#pay_online').click(function () {
    location.href = "{pigcms{:U('Wap/Deliver/online', array('supply_id'=>$supply['supply_id'],'lang'=>'en'))}";
});

function openPhoto(){
    var url = $("#upload_img").val();
    $('#system_message').show();
    $('#system_message').bind("click",function () {
        $(this).hide();
        $('#message_content').html("");
    });

    $('#message_content').bind("click",function () {
        $("#system_message").hide();
        $(this).html("");
    });

    var img_width = $('#message_content').width();
    var img = "<img src='"+url+"' width='"+img_width+"' id='message_img'/>";
    $('#message_content').html(img);


    $('#message_content').css('left',($(window).width() - img_width)/2);
    $('#message_content').css('top',($(window).height() - img_width*1.25)/2);
}

$(document).ready(function(){
    var deliver_sound_url = "{pigcms{$static_public}sound/driver_new_order.mp3";
    //setInterval(getOrderNum, 2000);
    function getOrderNum() {
        $.get("{pigcms{:U('Deliver/index_count')}", function (response) {
            if (response.err_code == false) {
                if(response.just_new == 1){
                    if(navigator.userAgent.match(/TuttiDeliver/i))
                        window.webkit.messageHandlers.newOrderSound.postMessage([0]);
                    else if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
                        if (typeof (window.linkJs.newOrderSound) != 'undefined') {
                            window.linkJs.newOrderSound();
                        }
                    }else {
                        var audio = new Audio();
                        audio.src = deliver_sound_url;
                        audio.play();
                    }
                }
            }
        }, 'json');
    }

    var mark = 0;
    $('.img_0 img').click(openPhoto);
    $(document).on('click', '#sub_btn a', function(e){
        e.stopPropagation();
        if (mark == 1) return false;
        mark = 1;
        var supply_id = $(this).attr("data-id"), post_url = $(this).data('url'), status = $(this).data('status');

        var post_data = {supply_id:supply_id};
        if(status == 4) post_data['photo'] = $('#upload_img').val();

        if (status == 5) {
            layer.open({
                content: '删除后就不再显示了，但是不影响您的接单统计!',
                btn: ['确认', '取消'],
                shadeClose: false,
                yes: function(){
                    layer.closeAll();
                    $.post(post_url, {supply_id:supply_id}, function(json){
                        if (json.status) {
                            location.href = "{pigcms{:U('Deliver/finish')}";
                            $('.supply_' + supply_id).hide();
                        } else {
                            layer.open({title:['提示：','background-color:#FF658E;color:#fff;'], content:json.info, btn: ['确定'],end:function(){}});
                        }
                    }, 'json');
                }, no: function(){
                    layer.open({content: '你选择了取消', time: 1});
                }
            });
        } else {
            $.post(post_url, post_data, function(json){
                mark = 0;
                if (json.status) {
                    if(status == 4) {
                        var pay_method = "{pigcms{$order['pay_method']}";
                        var content = "{pigcms{:L('_ND_CASHORDERNOTICE_')}";
                        if(pay_method == 1){
                            //content = "Order Completed! Delivery Fee: ${pigcms{$order.freight_charge}. Tips: ${pigcms{$order.tip_charge}. Thank you for your delivery!"
                            content = "Order complete! Your earning is ${pigcms{$order['freight_charge']+$order['tip_charge']+$supply['bonus']}. Thank you for your delivery!";
                        }
                        layer.open({
                            title: ['{pigcms{:L("_ND_TISHI_")}', 'background-color:#ffa52d;color:#fff;'],
                            content: content,
                            btn: ['{pigcms{:L("_ND_CONFIRM1_")}'],
                            end: function () {
                                location.reload();
                            }
                        });
                    }else {
                        layer.open({
                            title: ['{pigcms{:L("_ND_TISHI_")}', 'background-color:#ffa52d;color:#fff;'],
                            time: 2,
                            content: json.info,
                            end: function () {
                                location.reload();
                            }
                        });
                    }
                } else {
                    layer.open({title:['{pigcms{:L("_ND_TISHI_")}','background-color:#FF658E;color:#fff;'], content:json.info, btn: ['{pigcms{:L("_ND_CONFIRM1_")}'], end:function(){}});
                }
                $(".supply_"+supply_id).remove();
            });
        }
    });

    $("#id_check").click(function () {
        layer.open({
            title: ['<span class="material-icons" style="vertical-align: middle">account_box</span>ID Check', 'background-color:#ffffff;color:#294068;font-weight:bold;'],
            content: "This order contains age sensitive products. By law, you are responsible for checking <b>government issued photo ID</b> from the customer and ensuring he/she is <b>at least 19 years</b> of age before handing over the order. <br/><br/>" +
            "If the customer fails to do so, please contact the Tutti support team for instructions about returning the order back to the merchant. You will be compensated with a return delivery fee. <br/>"
        });
    });
});
</script>
</body>
</html>
