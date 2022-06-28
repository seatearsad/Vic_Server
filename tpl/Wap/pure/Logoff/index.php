<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>Account Deletion</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style.css" />
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <style>
        .third_div{
            margin-top: 20px;
        }
        .third_div li{
            display: inline-block;
            list-style: none;
            margin-right: 10px;
            width: 60px;
            height: 60px;
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% auto;
            cursor: pointer;
        }
        .third_div li:nth-child(1){
            background-image: url("{pigcms{$static_path}images/logoff/google.png");
        }
        .third_div li:nth-child(2){
            background-image: url("{pigcms{$static_path}images/logoff/fb.png");
        }
        .third_div li:nth-child(3){
            background-image: url("{pigcms{$static_path}images/logoff/weixin.png");
        }
        .third_div li:nth-child(4){
            background-image: url("{pigcms{$static_path}images/logoff/apple.png");
        }
    </style>
</head>
<body>
<include file="header" />
<section class="content_div">
    <div class="content_title">
        Sign In
    </div>
    <div class="content_desc">
        Use the phone number linked to your Tutti account to sign in
    </div>

    <div class="input_div" style="margin-top: 20px;">
        <input type="text" placeholder="10-digit phone number" name="phone">
    </div>
    <div class="input_div" style="margin-top: 10px;">
        <input type="password" placeholder="Password" name="pwd">
    </div>

    <div class="content_desc" style="margin-top: 30px;">
        If your account is signed up through one of the following platforms, please sign in here:
    </div>
    <div class="third_div">
        <ul>
            <li></li>
            <li></li>
            <li></li>
            <li></li>
        </ul>
    </div>
</section>
<div class="bottom_btn">
    Next
</div>

<script type="text/javascript">
    $('.bottom_btn').click(function () {
        var phone = $("input[name='phone']").val();
        var pwd = $("input[name='pwd']").val();

        if(phone == '' || pwd == ''){
            showMessage("The information you entered is invalid");
        }else{
            loading();
            var data = {"phone":phone,"password":pwd};
            $.post("/wap.php?g=Wap&c=Logoff&a=index",data,function(result){
                closeMessage();
                if(result.error == 2){
                    showMessage("Deletion request already exists for this account. Please do not repeat. To cancel your request, please sign in through the Tutti homepage.");
                }else if(result.error){
                    showMessage("The information you entered is invalid");
                }else{
                    window.location.href = "{pigcms{:U('Wap/Logoff/step_2')}";
                }
            },'JSON');
        }
    });
</script>
</body>
</html>