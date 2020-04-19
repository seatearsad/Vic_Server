<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
        Account Management
    </title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    #main{
        padding: 20px 5%;
        color: #999999;
        line-height: 16px;
        display: inline-block;
        font-size: 12px;
        width: 100%;
        box-sizing: border-box;
    }
    .input_title{
        font-size: 14px;
    }
    .order_input input{
        line-height: 30px;
        font-size: 14px;
    }
</style>
<body>
<include file="header" />
<div id="main">
    <div style="text-align: center;font-size: 16px;">
        Change My Password
    </div>
    <div class="order_input">
        <input type="text" name="curr_pwd" placeholder="{pigcms{:L('QW_CURRENTPASS')}" value="" />
    </div>
    <div class="order_input">
        <input type="text" name="new_pwd" placeholder="{pigcms{:L('QW_NEWPASSWORD')}" value="" />
    </div>
    <div class="order_input">
        <input type="text" name="re_pwd" placeholder="{pigcms{:L('QW_RENEWPASS')}" value="" />
    </div>
    <div class="top_10 r_color" id="error_tip" style="display: none">
        *Sorry, the current password you provide is incorrect. Please try again.
    </div>
    <div class="top_10">
        {pigcms{:L('QW_IFFORGETPASS')}
    </div>
    <div class="confirm_btn_order" id="confirm_order">
        {pigcms{:L('QW_CONTINUE')}
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    $('#confirm_order').click(function () {
        if($('input[name="curr_pwd"]').val() == '' || $('input[name="new_pwd"]').val() == '' || $('input[name="re_pwd"]').val() == ''){
            alert('Please input required optional.');
            return false;
        }else if($('input[name="new_pwd"]').val() != $('input[name="re_pwd"]').val()){
            alert('Please input required optional.');
            return false;
        }
        var data = {};
        data['curr_pwd'] = $('input[name="curr_pwd"]').val();
        data['new_pwd'] = $('input[name="new_pwd"]').val();
        data['re_pwd'] = $('input[name="re_pwd"]').val();

        layer.open({
            type:2,
            content:'Loading...'
        });
        $.post("{pigcms{:U('Storestaff/change_pwd')}",data,function(result) {
            if(result.error == 0){
                layer.closeAll();
                layer.open({
                    content: "Success",
                    type:2,
                    time:1,
                    end:function () {
                        window.location.href = "{pigcms{:U('Storestaff/manage_info')}";
                    }
                });
            }else{
                layer.open({
                    content: "Fail",
                    type: 2,
                    time: 1,
                    end:function () {
                        $('#error_tip').show();
                    }
                });
            }
        },'JSON');
    });
</script>
</body>
</html>
