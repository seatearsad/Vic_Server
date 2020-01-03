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
<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
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
    #phone,#mail{
        width: 85%;
        margin: 20px auto;
        border-radius: 5px;
        border: 2px solid #ffa52d;
        padding: 20px 20px 20px 50px;
        box-sizing: border-box;
        font-size: 12px;
        background-repeat: no-repeat;
        background-position: center left 9px;
        background-size:32px auto;
    }
    #phone{
        background-image:url('{pigcms{$static_path}img/phone_or.png');
    }
    #mail{
        background-image:url('{pigcms{$static_path}img/mail_or.png');
    }
</style>
</head>
<body>
    <include file="header" />
    <div style="height: 60px"></div>
    <a href="tel:1-888-3999-6668">
    <div id="phone">
        <div>
            Order and Delivery Questions
        </div>
        <div style="font-size: 10px;margin-top: 2px;">
            Please contact our customer support if you have any questions about your order or delivery at 1-888-3999-6668.
        </div>
    </div>
    </a>
    <a href="mailto:hr@tutti.app">
    <div id="mail">
        <div>
            Account and Payment Questions
        </div>
        <div style="font-size: 10px;margin-top: 2px;">
            For inquiries about your account info, payment status, and other questions, please email our human resource department at hr@tutti.app.
        </div>
    </div>
    </a>
</body>
</html>