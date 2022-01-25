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
        background-color: #F8F8F8;
    }
    #phone,#mail{
        width: 85%;
        margin: 20px auto;
        border-radius: 10px;
        padding: 20px 20px 20px 50px;
        box-sizing: border-box;
        font-size: 14px;
        background: #E3EAFD;
        color: #294068;
    }
    .div_title{
        font-weight: bolder;
        font-size: 16px;
        margin-bottom: 10px;
    }

    .title_icon{
        margin-left: -35px;
        position: absolute;
        display: table-cell;
    }
</style>
</head>
<body>
    <include file="header" />
    <div class="page_title" style="padding-bottom: 10px;">{pigcms{:L('_ND_COURIERSUPPORT_')}</div>
    <a href="tel:1-888-399-6668">
    <div id="phone">
        <div class="div_title">
            <span class="material-icons title_icon">phone</span>
            Order & Delivery Questions
        </div>
        <div style="margin-top: 2px;">
            Please contact our customer support if you have any questions about your order or delivery at 1-888-399-6668.
        </div>
    </div>
    </a>
    <div id="mail">
        <div class="div_title">
            <span class="material-icons title_icon">markunread</span>
            Account & Payment Questions
        </div>
        <div style="margin-top: 2px;">
            For inquiries about your account info, payment status, and other questions, please email our human resource department at henry@tutti.app.
        </div>
    </div>
<script>
    var ua = navigator.userAgent;
    $('#mail').click(function () {
        if(!ua.match(/TuttiDeliver/i)) {
            location.href = "mailto:henry@tutti.app";
        }else{
            layer.open({
                title: "",
                content: "Please send email to henry@tutti.app",
                btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],
            });
            //alert("Please send email to henry@tutti.app");
        }
    });

</script>
</body>
</html>