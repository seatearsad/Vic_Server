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
                if(result.error){
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