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
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
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
    .Land_top {
        text-align: center;
        color: #1b9dff;
    }
    #J_selectImage_0,#J_selectImage_1,#J_selectImage_2{
        background: #1b9dff;
        background-color: rgb(27, 157, 255);
        color: #fff;
        text-indent: 0px;
        font-size: 14px;
        padding: 0px;
        height: 40px;
        display: inline-block;
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
    .col-sm-1{
        margin-left: 20px;
        width: 100px;
        line-height: 40px;
    }
    .img_0,.img_1,.img_2{
        width: 100%;
        height: 100px;
        text-align: center;
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
        margin-left: -30px;
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
    }
</style>
<body style="background:#ebf3f8; background-size: 100% 137px;max-width: 100%">
    <div class="top_back">
        <img src="{pigcms{$config.site_logo}" width=180 height=51 style="margin-top: 10px">
        <span class="tt_title">{pigcms{:L('_COURIER_CENTER_')}</span>
    </div>
    <div class="top_two_back"></div>
    <form enctype="multipart/form-data" class="form-horizontal" method="post">
	<section>
	<div class="Land_top">
		<h2>{pigcms{:L('_COURIER_CENTER_')}</h2>
        <h2>{pigcms{:L('_B_D_LOGIN_REG2_')}</h2>
	</div>
	<div id="reg_list">
        Step 2
	</div>

	</section>
    </form>
</body>
<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript">
$("body").css({"height":$(window).height()});

</script>
</html>