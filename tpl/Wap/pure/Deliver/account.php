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
        background-color: white;
    }
    #all{
        width: 90%;
        margin: 60px auto 20px auto;
        font-size: 12px;
        color: #333333;
    }
    #title{
        text-align: center;
        font-size: 16px;
        line-height: 40px;
        margin-bottom: 10px;
    }
    .f_t{
        font-size: 14px;
    }
    .tip{
        color: silver;
        font-size: 10px;
    }
    .list{
        font-size: 0;
        border-bottom: 1px solid silver;
    }
    .list span{
        display: inline-block;
        font-size: 11px;
        color: #666666;
        line-height: 30px;
    }
    .l_span{
        width: 40%;
        text-align: left;
    }
    .r_span{
        width: 60%;
        text-align: right;
    }
    #change_pwd,#info,#bank{
        font-size: 0;
        line-height: 35px;
        cursor: pointer;
        border-bottom: 1px solid silver;
    }
    #change_pwd span,#info span,#bank span{
        display: inline-block;
        font-size: 12px;
    }
    #change_pwd .r_span{
        background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
        background-size: auto 16px;
        background-repeat: no-repeat;
        background-position: right;
        padding-right: 20px;
        box-sizing: border-box;
    }
    #info,#bank{
        border: 0;
    }
    #info .l_span,#bank .l_span{
        width: 50%;
    }
    #info .r_span,#bank .r_span{
        width: 50%;
        background-image: url("./tpl/Static/blue/images/new/or_arrow.png");
        background-size: auto 16px;
        background-repeat: no-repeat;
        background-position: right;
        padding-right: 20px;
        box-sizing: border-box;
        color: #ffa52d;
    }
    @font-face {
        font-family:base_icon;
        src:url("./tpl/Wap/default/static/css/fonts/base.woff") format("woff"),url("./tpl/Wap/default/static/css/fonts/base.otf")
    }
    .ver::before,.nver::before{
        display: inline-block;
        -webkit-appearance: none;
        width: 1.1rem;
        height: 1.1rem;
        text-align: center;
        border-radius: 100%;
        vertical-align: middle;
        line-height: 1.1rem;
        outline: 0;
        content: "✓";
        font-size: 1.1rem;
        font-family: base_icon;
        color: white;
    }
    .nver::before{
        background-color: #999999;
    }
    .ver::before{
        background-color: #ffa52d;
    }
</style>
</head>
<body>
    <include file="header" />
    <div id="all">
        <div id="title">
            Courier Information
        </div>
        <div class="f_t">
            Basic Information
        </div>
        <div class="tip">
            If you wish to change the info below, please contact our courier support.
        </div>
        <div class="list">
            <span class="l_span">First Name:</span>
            <span class="r_span">{pigcms{$deliver_session.name}</span>

            <span class="l_span">Last Name:</span>
            <span class="r_span">{pigcms{$deliver_session.family_name}</span>

            <span class="l_span">Phone Number:</span>
            <span class="r_span">{pigcms{$deliver_session.phone}</span>

            <span class="l_span">Email:</span>
            <span class="r_span">{pigcms{$deliver_session.email}</span>

            <span class="l_span">Date of Birth:</span>
            <span class="r_span">{pigcms{$deliver_session.birthday}</span>
        </div>
        <div id="change_pwd">
            <span class="l_span">Password</span>
            <span class="r_span">Change Password</span>
        </div>
        <div id="info">
            <span class="l_span">Courier Verification Info</span>
            <span class="r_span">Update</span>
        </div>
        <div class="list">
            <span class="l_span">City:</span>
            <span class="r_span">{pigcms{$city.area_name}</span>

            <span class="l_span">Address:</span>
            <span class="r_span">{pigcms{$deliver_session.site}</span>

            <span class="l_span">SIN Number:</span>
            <span class="r_span">
                <if condition="$deliver_img['sin_num'] eq ''">
                    Please Update <lable class="nver"></lable>
                <else />
                    Verified <lable class="ver"></lable>
                </if>
            </span>

            <span class="l_span">Driver's License:</span>
            <span class="r_span">
                <if condition="$deliver_img['driver_license'] eq ''">
                    Please Update <lable class="nver"></lable>
                <else />
                    Verified <lable class="ver"></lable>
                </if>
            </span>

            <span class="l_span">Vehicle Insurance:</span>
            <span class="r_span">
                <if condition="$deliver_img['insurance'] eq ''">
                    Please Update <lable class="nver"></lable>
                <else />
                    Verified <lable class="ver"></lable>
                </if>
            </span>

            <span class="l_span">Work Eligibility:</span>
            <span class="r_span">
                <if condition="$deliver_img['certificate'] eq ''">
                    Please Update <lable class="nver"></lable>
                <else />
                    Verified <lable class="ver"></lable>
                </if>
            </span>
        </div>
        <div id="bank">
            <span class="l_span">Banking Info</span>
            <span class="r_span">Update</span>
        </div>
        <div class="tip">
            This information is used to deposit your earnings to your account directly.
        </div>
    </div>
	<script type="text/javascript">
        $('#change_pwd').click(function () {
            location.href = "{pigcms{:U('Deliver/change_pwd')}";
        });
        $('#bank').click(function () {
            location.href = "{pigcms{:U('Deliver/bank_info')}";
        });
    </script>
</body>
</html>