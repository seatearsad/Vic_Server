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
    #all{
        width: 85%;
        margin: 60px auto 20px auto;
        font-size: 12px;
        color: #294068;
    }
    #title{
        text-align: center;
        font-size: 16px;
        line-height: 40px;
        margin-bottom: 10px;
        margin-top: 100px;
        font-weight: bold;
    }
    .tip{
        margin-top: 30px;
        font-size: 14px;
    }
    .input_div,.card_div{
        font-size: 0;
        margin-top: 30px;
    }
    .input_title{
        display: inline-block;
        width: 20%;
        font-size: 12px;
    }
    .card_div .input_title{
        display: inline-block;
        width: 100%;
        font-size: 14px;
        color: #666666;
        margin-left: 15px;
        line-height: 25px;
    }
    .card_div input{
        width: 100%;
        margin-bottom: 20px;
    }
    input[readonly]{
        background-color: white;
    }
    input{
        width: 100%;
        border-radius: 12px;
        border: 1px solid #EEEEEE;
        background-color: white;
        height: 40px;
        text-indent: 10px;

        font-size: 14px;
        color: #666666;
    }
    #save{
        width: 100%;
        height: 40px;
        line-height: 40px;
        color: white;
        text-align: center;
        margin: 40px auto;
        background-color: #ffa52d;
        border-radius: 12px;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
    }
    .red{
        color: red;
        margin-top: 10px;
    }
</style>
</head>
<body>
    <include file="header" />
    <div id="all">
        <div id="title">
            Courier Banking Information
        </div>
        <div class="tip">
            <if condition="$is_pwd neq 1">
                For security purpose, please enter your Tutti Courier account password below before viewing your banking information.
            <else />
                Your banking info is used to transfer your earnings electronically to your account. All information is kept securely.
            </if>
        </div>

        <if condition="$is_pwd neq 1">
        <form id="myform" method="post" action="{pigcms{:U('Deliver/bank_info')}" frame="true" refresh="true" autocomplete="off">
        <div class="input_div">
            <input type="password" placeholder="Enter your login password to continue" name="pwd">
        </div>
        </form>
        <else />
            <div class="card_div">
                <span class="input_title">Full Name</span>
                <input type="text" placeholder="Account holder's name" name="ahname" id="ahname" value="{pigcms{$deliver_card['ahname']}" readonly="readonly">

                <div style="width:48%;float: left;">
                <span class="input_title">Institution Number</span>
                <input type="text" maxlength="3" placeholder="3 digits" name="institution" id="institution" value="{pigcms{$deliver_card['institution']}" readonly="readonly">
                </div>
                <div style="width:48%;float: left;margin-left: 4%">
                <span class="input_title">Transit Number</span>
                <input type="text" maxlength="5" placeholder="5 digits" name="transit" id="transit" value="{pigcms{$deliver_card['transit']}" readonly="readonly">
                </div>
                <span class="input_title">Account Number</span>
                <input type="text" maxlength="12" minlength="7" placeholder="7-12 digits" name="account" id="account" value="{pigcms{$deliver_card['account']}" readonly="readonly">
            </div>
            <div class="tip" id="edit_div" style="display: none;margin-top: 10px">
                Please make sure the above information is correct before your submission. Incorrect information may result in failure of the deposit.
            </div>

            <div class="tip" style="font-weight: bold">
                Please make sure the above information is correct before your submission. Incorrect information may result in failure of the deposit.
            </div>
        </if>

        <if condition="$is_pwd eq 2">
        <div class="tip red">
            * Sorry, the password you provide is incorrect. Please try again.
        </div>
        </if>
        <div id="save">
            <if condition="$is_pwd neq 1">
                Continue
            <else />
                Confirm & Submit
            </if>
        </div>
    </div>
	<script type="text/javascript">
        var is_pwd = "{pigcms{$is_pwd}";

        if(is_pwd == 1){
            $('body').find('input').each(function () {
                $(this).removeAttr("readonly");
            });
            is_pwd = 3;
        }

        $('#save').click(function () {
            if(is_pwd != 1) {
                if(is_pwd != 3) {
                    if ($("input[name='pwd']").val() == '') {
                        //alert("{pigcms{:L('_B_D_LOGIN_CONFIRMKEY_')}");
                        layer.open({
                            title: "",
                            content: "{pigcms{:L('_B_D_LOGIN_CONFIRMKEY_')}",
                            btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],
                        });
                    } else {
                        $('#myform').submit();
                    }
                }else{
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
                        var form_data = {
                            'ahname':$('#ahname').val(),
                            'transit':$('#transit').val(),
                            'institution':$('#institution').val(),
                            'account':$('#account').val()
                        };
                        $.ajax({
                            url: "{pigcms{:U('Deliver/bank_info')}",
                            type: 'POST',
                            dataType: 'json',
                            data: form_data,
                            success:function(date){
                                if(date.error != 0){
                                    alert(date.message);
                                }else{
                                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.message,skin: 'msg', time:1,end:function () {
                                            //$('#save').html('Edit');
                                            //$('#edit_div').hide();
                                            //$('body').find('input').each(function () {
                                            //    $(this).attr("readonly","readonly");
                                            //});
                                            //is_pwd = 1;
                                    }});
                                }
                            }

                        });
                    }
                }
            }else{
                $('#save').html('Confirm & Submit');
                $('#edit_div').show();
                $('body').find('input').each(function () {
                    $(this).removeAttr("readonly");
                });
                is_pwd = 3;
            }
        });
    </script>
</body>
</html>