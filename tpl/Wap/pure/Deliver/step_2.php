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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    body {
        padding: 0px;
        margin: 0px auto;
        font-size: 14px;
        min-width: 320px
        max-width: 640px;
        background-color: #f4f4f4;
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    section{
        position: absolute;
        top: 7%;
        width: 100%;
        font-size: 14px;
        color: #666666;
    }
    #step_now{
        width:80%;
        margin: 20px auto;
        font-size: 0;
    }
    #step_now div{
        font-size: 10px;
        text-align: left;
        padding-left: 25%;
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
        margin: 5px auto 5px auto;
        text-align: left;
    }
    #step_title{
        width:80%;
        margin: 20px auto 5px auto;
        font-size: 18px;
        font-weight: bold;
        color: #333333;
    }
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
    #J_selectImage_0,#J_selectImage_1,#J_selectImage_2{
        background-color: white;
        background-image: url("{pigcms{$static_path}img/step2.png");
        background-size: 40px 40px;
        background-repeat: no-repeat;
        background-position:left 10px center;
        color: #666666;
        text-indent: 0px;
        font-size: 12px;
        border-radius: 5px;
        padding: 0px;
        height: 50px;
        line-height: 50px;
        padding-left: 55px;
        box-sizing: border-box;
        display: inline-block;
        width: 100%;
    }
    input{
        width: 100%;
        padding: 12px 0px;
        border-radius: 3px;
    }
    .img_0,.img_1,.img_2{
        width: 100%;
        text-align: center;
    }
    .img_0 img,.img_1 img,.img_2 img{
        height: 100px;
    }
    #reg_form{
        background-color: #ffa52d;
        color: white;
    }
    .refresh{
        float: right;
        margin-top: 30px;
        margin-right: 20px;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<div class="refresh" id="refresh_btn">
    <span class="material-icons" style="color: #294068;font-size: 36px;">restart_alt</span>
</div>
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px">{pigcms{:L('_ND_BECOMEACOURIER_')}</div>
        <div style="color: #999999;font-size: 10px;margin: 10px auto;width: 90%;">
        </div>
    </div>

    <div id="memo" class="android_tip">
        Please use your browser to upload images if the uploading function does not work here. To do so, visit www.tutti.app and click on Menu > Become a Courier > Get Started.
    </div>
    <div id="step_title">
        Required Documents
    </div>
    <div id="memo" >
        The following documents are required to verify that you’re eligible to become a courier. All information are kept securely.<br/><br/>
        Make sure your photos are clear, especially the name and expiration date. Photos that are unclear or invalid may result in verification failure and delay your application.
    </div>
    <div id="step_title">
        1. Proof of Work Eligibility
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <input type="text" style="padding-left: 10px;" placeholder="Enter Social Insurance Number*" id="sin_num" name="sin_num" value="{pigcms{$userImg['sin_num']}">
    </div>
    <div id="memo">
        This is for taxation purpose only
    </div>
    <div id="memo" style="margin-top: 15px">
        Upload one of the following documents to prove you can legally work in Canada as a contract courier:<br/>
        - Canadian passport<br/>
        - Citizenship card<br/>
        - Residency card<br/>
        - Work permit<br/>
        - Study permit that allows off-campus work<br/>
        - Social insurance card<br/>
    </div>
    <!--    <div id="step_title">-->
    <!--        c.{pigcms{:L('_ND_WORKELIGIBILITY_')}-->
    <!--    </div>-->
    <!--    <div id="memo">-->
    <!--        {pigcms{:L('_ND_ELIGIBILITYNOTICE_')}-->
    <!--    </div>-->
    <div style="margin: 10px auto;width: 85%;">
        <div style="display:inline-block;" id="J_selectImage_0">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                {pigcms{:L('_ND_UPLOAD3_')}
            </div>
        </div>
        <div class="img_0">
            <if condition="$userImg['certificate'] neq ''">
                <img src=".{pigcms{$userImg['certificate']}" width="100" />
            </if>
        </div>
    </div>
    <div id="step_title">
        2. Driver’s License
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <div style="display:inline-block;" id="J_selectImage_1">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                {pigcms{:L('_ND_UPLOAD1_')}
            </div>
        </div>
        <div class="img_1">
            <if condition="$userImg['driver_license'] neq ''">
                <img src=".{pigcms{$userImg['driver_license']}" width="100" />
            </if>
        </div>
    </div>

    <div id="step_title">
        3. Vehicle Insurance
    </div>

    <div style="margin: 10px auto;width: 85%;">
        <div style="display:inline-block;" id="J_selectImage_2">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                {pigcms{:L('_ND_UPLOAD2_')}
            </div>
        </div>
        <div class="img_2">
            <if condition="$userImg['insurance'] neq ''">
                <img src=".{pigcms{$userImg['insurance']}" width="100" />
            </if>
        </div>
    </div>
    <div id="memo" style="color: #353535;margin-bottom: 30px;">
        {pigcms{:L('_ND_SKIPUPLOAD_')}
    </div>
    <div id="memo" style="text-align: center;margin-top: 20px;margin-bottom: 20px;">
        <span id="filename_0" style="display: none;">
            <if condition="$userImg['certificate'] neq ''">
                {pigcms{$userImg['certificate']}
            </if>
        </span>
        <span id="filename_1" style="display: none;">
            <if condition="$userImg['driver_license'] neq ''">
                {pigcms{$userImg['driver_license']}
            </if>
        </span>
        <span id="filename_2" style="display: none;">
            <if condition="$userImg['insurance'] neq ''">
                {pigcms{$userImg['insurance']}
            </if>
        </span>
        <input type="button" value="{pigcms{:L('_ND_SAVENCONTINUE_')}" id="reg_form">
    </div>

</section>

<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script type="text/javascript">
    if(!/(tutti_android)/.test(navigator.userAgent.toLowerCase())){
        $('.android_tip').hide();
    }
    $("body").css({"height":$(window).height()});

    var  uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Deliver/ajax_upload')}",
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploader.addButton({
        id:'#J_selectImage_0',
        name:'image_0',
        multiple:false
    });
    uploader.addButton({
        id:'#J_selectImage_1',
        name:'image_1',
        multiple:false
    });
    uploader.addButton({
        id:'#J_selectImage_2',
        name:'image_2',
        multiple:false
    });
    uploader.on('fileQueued',function(file){
        if($('.upload_pic_li').size() >= 5){
            uploader.cancelFile(file);
            alert('最多上传5个图片！');
            return false;
        }
    });
    uploader.on('uploadSuccess',function(file,response){
        if(response.error == 0){
            var fid = file.source.ruid;
            var ruid = fid.split('_');
            var img = findImg(ruid[1],response.file);
            img.html('<img src="'+response.url+'"/>');
            img.css("height","100px");
        }else{
            layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:"Oops! Something went wrong. Photos no larger than 5MB is recommended! Please try again.", btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });

    function findImg(fid,file) {
        var img = '';
        var all = 3;
        var curr = 0;
        var is_addcss = false;
        for(var i=0;i<all;i++) {
            $('#J_selectImage_' + i).children('div').each(function () {
                if (typeof($(this).attr('id')) != 'undefined') {
                    if(is_addcss && i > curr){
                        var top = parseInt($(this).css("top"));
                        $(this).css("top",top+100+"px");
                    }
                    var arr = $(this).attr('id').split('_');
                    if (arr[2] == fid) {
                        curr = i;
                        img = $('.img_' + i);
                        if($.trim(img.html()) == ''){
                            is_addcss = true;
                        }else{
                            is_addcss = false;
                        }
                        $('#filename_'+i).html(file);
                    }
                }
            });
        }
        return img;
    }

    $('#refresh_btn').click(function () {
        if(typeof (window.linkJs) != 'undefined'){
            window.linkJs.reloadWebView();
        }else {
            window.location.reload();
        }
    });

    $('#reg_form').click(function () {
        var is_next = true;
        // $('body').find('span').each(function () {
        //     if (typeof($(this).attr('id')) != 'undefined'){
        //         var span_id = $(this).attr('id').split('_');
        //         if(span_id[0] == 'filename'){
        //             if ($(this).html() == ''){
        //                 is_next = false;
        //             }
        //         }
        //     }
        // });

        // if($('#ahname').val() == '' || $('#transit').val() == '' || $('#institution').val() == '' || $('#account').val() == '' || $('#sin_num').val() == ''){
        //     is_next = false;
        // }
        var from = "{pigcms{$_GET['from']}";
        if(!is_next)
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        else{
            var post_data = {
                img_0:$('#filename_0').html(),
                img_1:$('#filename_1').html(),
                img_2:$('#filename_2').html(),
                sin_num:$('#sin_num').val()
            };
            $.ajax({
                url: "{pigcms{:U('Deliver/step_2')}&from="+from,
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success:function(dat){
                    //console.log(dat);
                    if(from != "4")
                        window.parent.location = "{pigcms{:U('Deliver/step_3')}";
                    else
                        window.parent.location = "{pigcms{:U('Deliver/step_4')}";
                }

            });
        }
    });
</script>
</body>
</html>