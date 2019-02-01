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
</head>
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
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
    ul{
        padding-left: 0px;
    }
    li {
        list-style-type: none;
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
        margin-left: -40px;
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
    #reg_list{
        float: left;
        width: 80%;
        margin-left: 10%;
    }
</style>
<body style="background:#ebf3f8; background-size: 100% 137px;max-width: 100%">
<div class="top_back">
    <img src="{pigcms{$config.site_logo}" height=60>
    <span class="tt_title">{pigcms{:L('_COURIER_CENTER_')}</span>
</div>
<div class="top_two_back"></div>
<form enctype="multipart/form-data" class="form-horizontal" method="post">
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
                <span class="back">3</span>
                <span class="step_title">{pigcms{:L('_DELIVER_STEP_3_')}</span>
            </span>
            <span class="step">
                <span class="back curr">4</span>
                <span class="step_title" style="color:#f39824">{pigcms{:L('_DELIVER_STEP_4_')}</span>
            </span>
        </div>
        <div class="Land_top">
            <h2>{pigcms{:L('_DELIVER_STEP_4_')}</h2>
        </div>
        <div id="reg_list">
            <div style="float: left;width: 100%;text-align: center;margin-top: 20px;">
                <img src="{pigcms{$static_public}images/deliver/step4.png" width="100">
            </div>
            <div style="float: left;width:100%;text-align:center;margin-top:10px;font-size:16px;line-height:20px;">
                {pigcms{:L('_DELIVER_REG_COM_DESC_')}
            </div>
        </div>
        <div id="reg_list" style="margin-top: 50px; margin-bottom: 20px">
            <div style="float: left;width: 100%;text-align:center;font-size:14px;line-height:20px;margin-top: 20px">
                801-747 Fort Street， Victoria BC<br>
                Monday - Friday 10:00 am - 4:30 pm
            </div>
            <div style="float: left;width:100%;text-align:center;">
                <img src="{pigcms{$static_public}images/deliver/step4_address.png" width="100">
            </div>
        </div>

        <!--ul>
                <li>

                </li>
                <li>

                </li>

                <li>
                    801-747 Fort Street， Victoria BC
                </li>
                <li>
                    Monday -Friday 10:00 am - 7:00 pm
                </li>
            </ul-->
    </section>
</form>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript">
    $("body").css({"height":$(window).height()});

</script>
</html>