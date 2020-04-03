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
    <link href="{pigcms{$static_path}css/deliver.css?v=1.0.4" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    body {
        padding: 0px;
        margin: 0px auto;
        font-size: 14px;
        min-width: 320px;
        max-width: 100%;
        background-color: #f4f4f4;
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    section{
        position: absolute;
        top: 2%;
        width: 100%;
        font-size: 10px;
        color: #666666;
    }
    #step_now{
        width:80%;
        margin: 20px auto;
        font-size: 0;
    }
    #step_now div{
        font-size: 10px;
        text-align: right;
    }
    #step_now ul{
        margin-top: 2px;
    }
    #step_now li{
        display: inline-block;
        width: 25%;
        height: 5px;
        background-color: #F4F4F4;
    }
    #step_now li:nth-child(1).act{
        background-color: #ffde59;
    }
    #step_now li:nth-child(2).act{
        background-color: #ffbd59;
    }
    #step_now li:nth-child(3).act{
        background-color: #ffa52d;
    }
    #step_now li:nth-child(4).act{
        background-color: #ffa99a;
    }
    #memo{
        width:80%;
        margin: 20px auto 5px auto;
        text-align: center;
    }
    #address{
        width: 70%;
        margin: 10px auto;
        border-radius: 5px;
        border: 2px solid #ffa52d;
        text-align: center;
        padding: 10px 10px;
        box-sizing: border-box;
    }
    input{
        background-color: #ffa52d;
        color: white;
        padding: 5px 5px;
        border-radius: 3px;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px">{pigcms{:L('_ND_BECOMEACOURIER_')}</div>
        <div style="color: #999999;font-size: 12px;margin-top: 10px;">Thank you! You are almost there!</div>
    </div>
    <div id="step_now">
        <div>4.{pigcms{:L('_ND_GETYOURBAG_')}</div>
        <ul>
        <li class="act"></li><li class="act"></li><li class="act"></li><li class="act"></li>
        </ul>
    </div>
    <div id="memo">
        {pigcms{:L('_ND_PICKUPNOTICE0_')}
    </div>
    <div id="address">
        <div>
            #218-852 Fort St
        </div>
        <div>
            Victoria BC
        </div>
        <div>&nbsp;</div>
        <div style="color: #999999">
            Hours:
        </div>
        <div>
            Monday 2 pm - 6 pm
        </div>
        <div>
            Tuesday to Friday 11 am - 6 pm
        </div>
    </div>
    <div id="memo">
        {pigcms{:L('_ND_PICKUPNOTICE1_')}
    </div>
    <div style="border-bottom: 1px dashed #666666;margin: 20px auto;width: 80%;">

    </div>
    <div id="memo">
        {pigcms{:L('_ND_PICKUPNOTICE2_')}
    </div>
    <div id="memo" style="text-decoration: underline">
        <a href="{pigcms{:U('Deliver/account')}">
        {pigcms{:L('_ND_COMPLETINGAPP_')}
        </a>
    </div>
    <div id="memo">
        {pigcms{:L('_ND_PICKUPNOTICE3_')}
    </div>
</section>
<script>
    $("body").css({"height":$(window).height()});
</script>
</body>
</html>