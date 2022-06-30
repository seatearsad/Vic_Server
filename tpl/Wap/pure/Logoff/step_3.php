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
        Account Deletion
    </div>
    <div class="content_desc">
        If you request to delete your Tutti account, the following data will be deleted after 30 days:
        <br/><br/>
        ● Your profile and personal information (e.g. email, phone number, address, payment method, etc.)<br/><br/>
        ● Your order history<br/><br/>
        ● Unused credits, promotion and rewards<br/><br/>
        You can restore your account by signing in to your account any time within the 30-day peirod.<br/><br/>
        We may retain certain information after account deletion for legal and regulatory purposes.
    </div>
</section>
<div class="bottom_btn">
    Continue
</div>
<script src="{pigcms{$static_public}layer/layer.m.js"></script>

<script type="text/javascript">
    $('.bottom_btn').click(function () {
        window.location.href = "{pigcms{:U('Wap/Logoff/step_4')}";
    });
    $(function () {
        var userAgent = navigator.userAgent;
        showMessage(userAgent);
    });
</script>
</body>
</html>