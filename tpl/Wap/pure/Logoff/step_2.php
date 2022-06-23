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
        2-Step Verification
    </div>
    <div class="content_desc">
        Enter the code sent to (***) *** **{pigcms{$last_two}
    </div>

    <div class="input_div" style="margin-top: 20px;">
        <input type="text" placeholder="Verification code" name="code">
    </div>
</section>
<div class="bottom_btn">
    Next
</div>
<script src="{pigcms{$static_public}layer/layer.m.js"></script>

<script type="text/javascript">
    $('.bottom_btn').click(function () {
        var code = $("input[name='code']").val();

        if(code == ''){
            showMessage("Please enter valid verification code");
        }else{
            loading();
            var data = {"code":code};
            $.post("/wap.php?g=Wap&c=Logoff&a=step_2",data,function(result){
                closeMessage();
                if(result.error){
                    showMessage("Please enter valid verification code");
                }else{
                    window.location.href = "{pigcms{:U('Wap/Logoff/step_3')}";
                }
            },'JSON');
        }
    });
</script>
</body>
</html>