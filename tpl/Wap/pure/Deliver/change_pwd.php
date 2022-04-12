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
        },'json');

	    return message;
    }
</script>
<style>
    body{
        background-color: #F8F8F8;
    }
    #all{
        width: 85%;
        margin: 60px auto 20px auto;
        font-size: 12px;
        color: #294068;
    }
    #title{
        font-size: 18px;
        line-height: 40px;
        margin-bottom: 10px;
        margin-top: 100px;
        font-weight: bold;
        text-align: center;
    }
    input{
        width: 100%;
        border-radius: 12px;
        border: 1px solid #EEEEEE;
        background-color: white;
        height: 40px;
        text-indent: 10px;
        margin-top: 20px;
        color: #666666;
    }
    #save{
        width: 100%;
        height: 40px;
        line-height: 40px;
        color: white;
        text-align: center;
        font-size: 16px;
        margin: 40px auto;
        background-color: #ffa52d;
        border-radius: 12px;
        cursor: pointer;
    }
</style>
</head>
<body>
    <include file="header" />
    <div id="all">
        <div id="title">
            Change My Password
        </div>
        <div>
            <input type="password" placeholder="Current Password" name="old_pwd">
        </div>
        <div>
            <input type="password" placeholder="New Password" name="new_pwd">
        </div>
        <div>
            <input type="password" placeholder="Re-enter New Password" name="re_new_pwd">
        </div>
        <div id="save">
            Save
        </div>
    </div>
	<script type="text/javascript">
        $('#save').click(function () {
            var is_submit = true;
            $('#all').find('input').each(function () {
                if($(this).val() == ''){
                    is_submit = false;
                }
            });
            if(!is_submit){
                //alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
                layer.open({
                    title: "",
                    content: "{pigcms{:L('_PLEASE_INPUT_ALL_')}",
                    btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],
                });
            }else{
                if($('input[name="new_pwd"]').val() != $('input[name="re_new_pwd"]').val()){
                    //alert("{pigcms{:L('_B_LOGIN_DIFFERENTKEY_')}");
                    layer.open({
                        title: "",
                        content: "{pigcms{:L('_B_LOGIN_DIFFERENTKEY_')}",
                        btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],
                    });
                }else{
                    var form_data = {
                        'old_pwd':$('input[name="old_pwd"]').val(),
                        'new_pwd':$('input[name="new_pwd"]').val()
                    };
                    $.ajax({
                        url: "{pigcms{:U('Deliver/change_pwd')}",
                        type: 'POST',
                        dataType: 'json',
                        data: form_data,
                        success:function(date){
                            if(date.error != 0){
                                //alert(date.message);
                                layer.open({
                                    title: "",
                                    content: " " + date.message + " ",
                                    btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],
                                });
                            }else{
                                layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.message,skin: 'msg', time:1,end:function () {
                                    window.parent.location = "{pigcms{:U('Deliver/account')}";
                                }});
                            }
                        }

                    });
                }
            }
        });
    </script>
</body>
</html>