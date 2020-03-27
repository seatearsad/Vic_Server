<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8;application/json" />
    <link rel="manifest" href="/manifest_courier.json">
<title>Donate a Meal</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<style>
    #lang_div{
        color: #666666;
        line-height: 30px;
        padding-left: 30px;
        margin-top: 10px;
        margin-left: 10px;
        background-image:url('{pigcms{$static_path}img/language.png');
        background-repeat: no-repeat;
        background-position: center left;
        background-size:auto 80%;
    }
    #lang_div span{
        cursor: pointer;
    }
    #lang_div span.act{
        color: #ffa52d;
    }
    .Land_top{
        margin-top: 5%;
    }
    .Land_end{
        width: 90%;
        margin: 0 5%;
    }
    .event_title{
        font-size: 16px;
        font-weight: bold;
        text-align: center;
        margin-top: 20px;
    }
    .event_title a{
        color: #ffa52d;
        text-decoration: underline;
    }
    .event_content{
        color: #666666;
        margin-top: 20px;
        font-size: 12px;
    }
    .choose_div{
        display: flex;
    }
    .choose_div div{
        width: 40%;
        height: 40px;
        line-height: 40px;
        text-align: center;
        border-radius: 3px;
        border: 1px solid #ffa52d;
        background-color: white;
        font-weight: bold;
        cursor: pointer;
    }
    .choose_div .act{
        background-color: #ffa52d;
        color: white;
    }
    .x_img {
        background-image: url("{pigcms{$static_path}img/login_img.png");
        width: 90%;
        margin: 10px auto;
        height: 100px;
        background-size: 100% auto;
        background-position: center;
        background-repeat: no-repeat;
    }
    .input_div{
        margin-top: 10px;
        font-size: 12px;
    }
    .input_div input{
        border-radius: 3px;
        background-color: white;
        line-height: 30px;
        padding-left: 5px;
    }
    .input_div span{
        width: 85px;
        display: inline-block;
    }
    .confirm_btn{
        width: 80%;
        margin: 30px auto 20px auto;
        text-align: center;
        background-color: #ffa52d;
        color: white;
        line-height: 35px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        cursor: pointer;
    }
    .confirm_btn:disabled{
        background-color: #666666;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
	<section class="Land">
	<div class="Land_top">
        <a href="https://tutti.app/wap.php?c=Shop&a=classic_shop&shop_id=292">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/f_icon.png') center no-repeat; background-size: 100% auto;border-radius: 0;width: 50px;height: 50px"></span>
        </a>
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/x_icon.png') center no-repeat; background-size: 50% auto;border-radius: 0;width: 50px;height: 50px"></span>
        <a href="https://www.tutti.app">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;width: 50px;height: 50px"></span>
        </a>
	</div>
	<div class="Land_end">
		<div class="event_title">
            <a href="https://tutti.app/wap.php?c=Shop&a=classic_shop&shop_id=292">Ferris' Grill & Garden Patio</a>: Donate a Meal
        </div>
        <div class="event_content">
            We are providing meals to our temporarily laid off staff free of charge, but there are many others in the service industry that will rapidly need assistance. By donating a meal, we hope to help keep Victoria service industry workers fed. The meals will be distributed by us the following day. Thank you!
        </div>
        <div style="margin:20px 0 10px 0;">
            Please choose one of the followings:
        </div>
        <div class="choose_div">
            <div data-num="8" class="choose_btn act">$8 Single Meal</div>
            <div data-num="24" class="choose_btn" style="margin-left: 10%;">$24 Family Meal</div>
        </div>
        <div style="margin-top: 20px">
            <img src="{pigcms{$static_public}images/pay/moneris.png" height="25">
        </div>
        <div class="input_div">
            <span>Name:</span>
            <input type="text" name="c_name" id="c_name" placeholder="Cardholder's Name" style="width:70%;" />
        </div>
        <div class="input_div">
            <span>Card Number:</span>
            <input type="text" name="c_number" id="c_number" placeholder="Card Number Here" style="width:70%;" />
        </div>
        <div class="input_div">
            <span>Expiry Date:</span>
            <input type="text" name="e_date" id="e_date" placeholder="MM/YY" />
            <span style="width: auto;margin-left: 5px">CVV:</span>
            <input type="text" name="cvv" id="cvv" placeholder="3-digit number" />
        </div>

        <div class="confirm_btn">
            Confirm & Donate
        </div>
	</div>
    <div class="x_img"></div>
	</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript">
    var choose_num = 8;
    $('.choose_btn').click(function () {
        $('.choose_btn').removeClass('act');
        $(this).addClass('act');

        choose_num = $(this).data('num');
    });
    
    $('.confirm_btn').click(function () {
        send_donate();
    });

    function send_donate() {
        var is_next = true;
        if($('#c_name').val() == '' || $('#c_number').val() == '' || $('#e_date').val() == '' || $('#cvv').val() == ''){
            is_next = false;
        }
        if(!is_next)
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        else{
            $('.confirm_btn').unbind('click');
            $('.confirm_btn').html('Loading...');
            var post_data = {
                c_name:$('#c_name').val(),
                c_number:$('#c_number').val(),
                e_date:$('#e_date').val(),
                cvv:$('#cvv').val(),
                choose_num:choose_num
            };
            layer.open({
                type:2,
                content:'Loading'
            });
            $.post("{pigcms{:U('Event/index')}", post_data, function (result) {
                layer.closeAll();
                if(result.error_code){
                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:result.msg, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
                    $(".confirm_btn").click(function () {
                        send_donate();
                    });
                    $('.confirm_btn').html('Confirm & Donate');
                }else{
                    layer.open({
                        title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: result.msg,skin: 'msg', time:1
                    });
                }
            },'JSON');
        }
    }
</script>   
</html>