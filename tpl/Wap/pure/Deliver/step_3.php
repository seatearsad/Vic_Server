<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{:L('_COURIER_CENTER_')}</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <style>
        body {
            padding: 0px;
            margin: 0px auto;
            font-size: 14px;
            min-width: 320px;
            max-width: 640px;
            background-color: #dcdcdc;
            color: #626160;
            position: relative;
            -webkit-tap-highlight-color: rgba(0,0,0,0);
            font-family: Helvetica;
        }
        section{
            position: absolute;
            background-color: #ffffff;
            width: 80%;
            left: 10%;
            margin-top: -40px;
            box-shadow: 0 1px 4px 0 rgba(0,0,0,0.37)
        }
        ul{
            padding-left: 0px;
        }
        li {
           list-style-type: none;
        }
        li input {
            width: 94%;
            height: 15px;
            padding: 10px 0;
            text-indent: 10px;
            color: #1b9dff;
            font-size: 14px;
            background-color: transparent;
            margin-left: 3%;
            margin-top: 10px;
            border: none;
            border-bottom: 1px solid;
        }
        li input:focus{
            border-bottom: 1px solid #FF0000;
        }
        input[type="file"] {
            display: block;
            position: absolute;
            opacity: 0;
            -ms-filter: 'alpha(opacity=0)';
        }
        .Land_top {
            text-align: center;
            color: #1b9dff;
        }
        input#f_name,input#l_name,input#sms_code,#send_code{
            width: 45%;
        }
        .top_back{
            position: relative;
            width: 100%;
            top: 0%;
            height:100px;
            background-color: #4f9cf6;
        }
        .top_two_back{
            position: relative;
            width: 100%;
            top: 0%;
            height:10px;
            background-color: #535353;
        }
        .img_0 img,.img_1 img,.img_2 img{
            height: 100px;
        }
        .tt_title{
            font-size: 20px;
            color: white;
            height: 50px;
            line-height: 50px;
            position: absolute;
            margin-top: 10px;
            font-weight: bold;
        }
        #reg_form {
            color: #fff;
            text-indent: 0px;
            font-size: 16px;
            padding: 0px;
            height: 40px;
            background-color: transparent;
            border: 0px;
            margin-bottom: 20px;
            cursor: pointer;
            background-color: #407ec7;
            width: 50%;
            margin-left: 25%;
        }
        #reg_form:disabled{
            background-color: #999999;
        }
        .Land_top {
            text-align: center;
            color: #626160;
            width: 100%;
            float: left;
            margin-top: 10px;
        }
        .Land_top h2{
            color: #1b9dff;
            width: 100%;
        }
        .step{
            width: 25%;
            height: 40px;
            float: left;
            text-align: left;
            font-size: 12px;
            min-width: 140px;
        }
        .step .back{
            width: 20px;
            height: 20px;
            background-color:#a0a0a0;
            border-radius: 50%;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            text-align: center;
            margin-left: 20px;
            color: #ffffff;
        }
        .step span{
            position: absolute;
            line-height: 20px;
        }
        .step_title{
            margin-left: 42px;
            word-wrap: break-word;
        }
        .step .curr{
            background-color:#f39824;
        }
    </style>
</head>
<body style="background:#ebf3f8; background-size: 100% 137px;max-width: 100%">
    <div class="top_back">
        <img src="{pigcms{$config.site_logo}" width=80 height=80>
        <span class="tt_title">{pigcms{:L('_COURIER_CENTER_')}</span>
    </div>
    <div class="top_two_back"></div>
	<section>
        <div class="Land_top">
            <span class="step">
                <span class="back">1</span>
                <span class="step_title">{pigcms{:L('_DELIVER_STEP_1_')}</span>
            </span>
            <span class="step">
                <span class="back">2</span>
                <span class="step_title">{pigcms{:L('_DELIVER_STEP_2_')}</span>
            </span>
            <span class="step">
                <span class="back curr">3</span>
                <span class="step_title" style="color:#f39824">{pigcms{:L('_DELIVER_STEP_3_')}</span>
            </span>
            <span class="step">
                <span class="back">4</span>
                <span class="step_title">{pigcms{:L('_DELIVER_STEP_4_')}</span>
            </span>
        </div>
        <div class="Land_top">
            <h2>{pigcms{:L('_DELIVER_STEP_3_')}</h2>
        </div>
	<div id="reg_list">
        <div style="margin: 10px auto;width: 80%;text-align: center">
            {pigcms{:L('_COURIER_BAG_DESC_')}
        </div>
        <ul>
            <li style="text-align: center">
                <img src="{pigcms{$static_public}images/deliver_box.png" width="200px">
            </li>
            <li style="text-align: center">
                $50.00
            </li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_CREDITHOLDER_NAME_')}*" id="c_name">
            </li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_CREDIT_CARD_NUM_')}*" id="c_number">
            </li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_EXPRIRY_DATE_')}*" id="e_date">
            </li>
        </ul>
	</div>
    <div>
        <input type="button" value="{pigcms{:L('_NEXT_TXT_')}" id="reg_form">
    </div>
	</section>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript">
$("body").css({"height":$(window).height()});

$('#reg_form').click(function () {
    var is_next = true;
    if($('#c_name').val() == '' || $('#c_number').val() == '' || $('#e_date').val() == ''){
        is_next = false;
    }
    if(!is_next)
        alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
    else{
        $(this).attr("disabled","disabled");
        var post_data = {
            c_name:$('#c_name').val(),
            c_number:$('#c_number').val(),
            e_date:$('#e_date').val()
        };
        layer.open({content:"{pigcms{:L('_DEALING_TXT_')}"});
        $.ajax({
            url: "{pigcms{:U('Deliver/step_3')}",
            type: 'POST',
            dataType: 'json',
            data: post_data,
            success:function(date){
                layer.closeAll();
                if(date.error_code){
                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:date.msg, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
                    $("#reg_form").removeAttr("disabled");
                }else{
                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: date.msg,skin: 'msg', time:1,end:function () {
                            window.parent.location = "{pigcms{:U('Deliver/step_4')}";
                    }});
                }
            }

        });
    }
});
</script>
</html>