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
        .img_div{
            height: 200px;
            background-image: url("{pigcms{$static_path}images/logoff/logoff_img.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size:auto 100%;
            margin: 15px auto;
        }
        .cancel_btn{
            width: 86%;
            background-color: #DDDDDD;
            font-size: 20px;
            font-weight: bold;
            line-height: 50px;
            border-radius: 10px;
            position: absolute;
            bottom: 100px;
            left:7%;
            text-align: center;
            cursor: pointer;
        }
    </style>
</head>
<body>
<include file="header" />
<section class="content_div">
    <div class="content_title">
        Account Deletion
    </div>
    <div class="img_div">

    </div>
    <div class="content_title">
        We're sorry to see you go
    </div>
    <div class="content_desc">
        You can restore your account by signing in within the 30-day waiting period.
        <br/><br/>
        By requesting to delete your account, you'll be logged out.
    </div>
</section>
<div class="cancel_btn">
    Cancel
</div>
<div class="bottom_btn">
    Delete Account
</div>
<script src="{pigcms{$static_public}layer/layer.m.js"></script>

<script type="text/javascript">
    $('.bottom_btn').click(function () {
        loading();
        $.post("/wap.php?g=Wap&c=Logoff&a=step_4",{"is_logoff":1},function(result){
            closeMessage();
            if(result.error){
                showMessage("Fail!");
            }else{
                layer.open({
                    content:"Request Success!",
                    btn: ['Confirm'],
                    end:function(){
                        if(navigator.userAgent.match(/TuttiUser/i)){
                            var url = "tuttiapp:logout";
                            document.location = url;
                        }else {
                            window.location.href = "{pigcms{:U('Wap/Login/index')}";
                        }
                    }
                });
            }
        },'JSON');
    });
    $('.cancel_btn').click(function () {
        $.post("/wap.php?g=Wap&c=Logoff&a=step_4",{"is_logoff":0},function(result){
            closeMessage();
            if(result.error){
                showMessage("Fail!");
            }else{
                if(navigator.userAgent.match(/TuttiUser/i)){
                    var url = "tuttiapp:back";
                    document.location = url;
                }else {
                    window.location.href = "{pigcms{:U('Wap/Logoff/index')}";
                }
            }
        },'JSON');
    });
</script>
</body>
</html>