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
        .send_btn{
            width: 100%;
            margin-top: 20px;
            background-color: #ffa52d;
            color: white;
            font-size: 18px;
            font-weight: bold;
            line-height: 42px;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
        }
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

    <div class="send_btn">
        Resend Code (<label id="resend_time">{pigcms{$cha_time}</label>s)
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

    var cha_time = parseInt("{pigcms{$cha_time}");
    update_time(cha_time);

    function update_time() {
        if(cha_time <= 0){
            $('.send_btn').html("Get Code");
            $('.send_btn').click(function () {
                $.post("/wap.php?g=Wap&c=Logoff&a=send_code",{},function(result){
                    if(!result.error){
                        showMessage("Success");
                        setTimeout(function() {
                            window.location.reload();
                        },2000);
                    }else{
                        showMessage("Error");
                        $('.send_btn').unbind('click');
                    }
                },'JSON');
            });
        }else{
            $("#resend_time").html(cha_time);
            setTimeout(function() {
                update_time();
            },1000);

            cha_time--;
        }
    }
</script>
</body>
</html>