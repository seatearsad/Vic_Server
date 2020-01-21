<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>{pigcms{:L('_COURIER_CENTER_')}</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    body {
        padding: 0px;
        margin: 0px auto;
        font-size: 14px;
        min-width: 320px;
        max-width: 100%;
        background-color: #f4f4f4;
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    section{
        position: absolute;
        top: 2%;
        width: 100%;
        font-size: 10px;
        color: #666666;
    }
    #step_now{
        width:80%;
        margin: 20px auto;
        font-size: 0;
    }
    #step_now div{
        font-size: 10px;
        text-align: left;
        padding-left: 50%;
    }
    #step_now ul{
        margin-top: 2px;
    }
    #step_now li{
        display: inline-block;
        width: 25%;
        height: 5px;
        background-color: #F4F4F4;
        margin-top: 0;
    }
    #step_now li:nth-child(1).act{
        background-color: #ffde59;
    }
    #step_now li:nth-child(2).act{
        background-color: #ffbd59;
    }
    #step_now li:nth-child(3).act{
        background-color: #ffa52d;
    }
    #step_now li:nth-child(4).act{
        background-color: #ffa99a;
    }
    #memo{
        width:80%;
        margin: 20px auto 5px auto;
        text-align: center;
    }
    li{
        text-align: center;
        margin-top: 10px;
    }
    li input {
        width: 55%;
        height: 15px;
        padding: 8px 0;
        text-indent: 10px;
        color: #333333;
        background-color: white;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 12px;
    }
    li select{
        width: 55%;
        height: 31px;
        text-indent: 5px;
        border-radius: 5px;
    }
    li.Landd input {
        background: #ffa52d;
        color: #fff;
        text-indent: 0px;
        font-size: 12px;
        margin-top: 30px;
        margin-left: 0;
        padding: 0px;
        height: 30px;
    }
    #send_code{
        background: #ffa52d;
        color: #fff;
        text-indent: 0px;
        border-radius: 2px;
        font-size: 10px;
        padding: 0px;
        height: 30px;
    }
    li span{
        text-align: left;
        display: inline-block;
        width: 35%;
        font-size: 12px;
    }
    input#sms_code{
        width: 25%;
    }
    #send_code{
        width: 30%;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px">{pigcms{:L('_ND_BECOMEACOURIER_')}</div>
        <div style="color: #999999;font-size: 10px;margin: 10px auto;width: 90%;">
            Please complete the following steps to get started!<br/>
            All information are kept securely and used for delivery and taxation purpose.
        </div>
    </div>
    <div id="step_now">
        <div>3.{pigcms{:L('_ND_DELIVERYBAG_')}</div>
        <ul>
            <li class="act"></li><li class="act"></li><li class="act"></li><li></li>
        </ul>
    </div>
    <div id="memo">
        <img src="{pigcms{$static_public}images/deliver_box.png" width="120px">
        <br/>$50.00
    </div>
    <div id="reg_list">
        <ul>
            <li>
                <span>Name:</span>
                <input type="text" placeholder="{pigcms{:L('_CREDITHOLDER_NAME_')}" id="c_name">
            </li>
            <li>
                <span>{pigcms{:L('_CREDIT_CARD_NUM_')}:</span>
                <input type="text" placeholder="{pigcms{:L('_CREDIT_CARD_NUM_')}" id="c_number">
            </li>
            <li>
                <span>{pigcms{:L('_EXPRIRY_DATE_')}:</span>
                <input type="text" placeholder="{pigcms{:L('_EXPRIRY_DATE_')}" id="e_date">
            </li>
            <li>
                <span>CVV:</span>
                <input type="text" placeholder="3-digit number" id="cvv">
            </li>
            <li class="Landd">
                <input type="button" value="Pay Online" id="reg_form" style="background-color: #ffa52d;width: 55%;">
            </li>
            <li>
                <div style="border-bottom: 1px dashed silver;width: 55%;margin: auto">
                    OR
                </div>
            </li>
            <li class="Landd">
                <input type="button" value="{pigcms{:L('_ND_PAYINPERSON_')}" id="jump_btn" style="background-color: dodgerblue;font-size:10px;width: 55%;margin-top: 10px;margin-bottom: 30px">
            </li>
        </ul>
    </div>
</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
<script type="text/javascript">
    $("body").css({"height":$(window).height()});
    $('#reg_form').click(function () {
        var is_next = true;
        if($('#c_name').val() == '' || $('#c_number').val() == '' || $('#e_date').val() == '' || $('#cvv').val() == ''){
            is_next = false;
        }
        if(!is_next)
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        else{
            $(this).attr("disabled","disabled");
            var post_data = {
                c_name:$('#c_name').val(),
                c_number:$('#c_number').val(),
                e_date:$('#e_date').val(),
                cvv:$('#cvv').val()
            };
            layer.open({content:"{pigcms{:L('_DEALING_TXT_')}"});
            $.ajax({
                url: "{pigcms{:U('Deliver/step_3')}",
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success:function(date){
                    layer.closeAll();
                    if(date.error_code){
                        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:date.msg, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
                        $("#reg_form").removeAttr("disabled");
                    }else{
                        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg,skin: 'msg', time:1,end:function () {
                                window.parent.location = "{pigcms{:U('Deliver/step_4')}";
                            }});
                    }
                }

            });
        }
    });

    $('#jump_btn').click(function () {
        window.parent.location = "{pigcms{:U('Deliver/step_4')}&type=jump";
    });

</script>
</html>