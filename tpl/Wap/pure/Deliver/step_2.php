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
    <link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
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
        font-size: 14px;
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
        font-size: 9px;
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
        background-color: #ffa52d;
        color: white;
        padding: 10px 15px;
        border-radius: 3px;
    }
</style>
<body style="background:url('{pigcms{$static_path}img/login_bg.png');">
<section>
    <div class="Land_top" style="color:#333333;">
        <span class="fillet" style="background: url('./tpl/Static/blue/images/new/icon.png') center no-repeat; background-size: contain;"></span>
        <div style="font-size: 14px">Become a Tutti Courier</div>
        <div style="color: #999999;font-size: 10px;margin: 10px auto;width: 90%;">
            Please complete the following steps to get started!<br/>
            All information are kept securely and used for delivery and taxation purpose.
        </div>
    </div>
    <div id="step_now">
        <div>2.Document Uploading</div>
        <ul>
            <li class="act"></li><li class="act"></li><li></li><li></li>
        </ul>
    </div>
    <div id="step_title">
        a.Driver's License
    </div>
    <div id="memo">
        Please make sure the photo is clear, especially the name and expiration date. Photos that are unclear or invalid may result in verification failure and delay your application.
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <div style="display:inline-block;" id="J_selectImage_0">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                Upload a photo of your Diver's License here
            </div>
        </div>
        <div class="img_0">

        </div>
    </div>
    <div id="step_title">
        b.Vehicle Insurance
    </div>
    <div id="memo">
        Please make sure the photo is clear, especially the name and expiration date of the insurance. Photos that are unclear or invalid may result in verification failure and delay your application.
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <div style="display:inline-block;" id="J_selectImage_1">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                Upload a photo of your Vehicle Insurance here
            </div>
        </div>
        <div class="img_1">

        </div>
    </div>
    <div id="step_title">
        c.Proof of Work Eligibility
    </div>
    <div id="memo">
        This may be a valid passport, residency card, birth certificate, citizenship card, work permit, or a study permit that allow off-campus work. Please make sure the photo is clear, especially the name and expiration date of the document.Photos that are unclear or invalid may result in verification failure and delay your application.
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <div style="display:inline-block;" id="J_selectImage_2">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: center;">
                Upload Proof of Work Eligibility here
            </div>
        </div>
        <div class="img_2">

        </div>
    </div>
    <div id="memo" style="text-align: center;margin-top: 20px">
        <input type="button" value="Save and Continue">
    </div>
</section>

<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script type="text/javascript">
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
        }else{
            alert(response.info);
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });

    function findImg(fid,file) {
        var img = '';
        var all = 3;
        for(var i=0;i<all;i++) {
            $('#J_selectImage_' + i).children('div').each(function () {
                if (typeof($(this).attr('id')) != 'undefined') {
                    var arr = $(this).attr('id').split('_');
                    if (arr[2] == fid) {
                        img = $('.img_' + i);
                        $('#filename_'+i).html(file);
                    }
                }
            });
        }

        return img;
    }

    $('#reg_form').click(function () {
        var is_next = true;
        $('body').find('span').each(function () {
            if (typeof($(this).attr('id')) != 'undefined'){
                var span_id = $(this).attr('id').split('_');
                if(span_id[0] == 'filename'){
                    if ($(this).html() == ''){
                        is_next = false;
                    }
                }
            }
        });

        if($('#ahname').val() == '' || $('#transit').val() == '' || $('#institution').val() == '' || $('#account').val() == '' || $('#sin_num').val() == ''){
            is_next = false;
        }

        if(!is_next)
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        else{
            var post_data = {
                img_0:$('#filename_0').html(),
                img_1:$('#filename_1').html(),
                img_2:$('#filename_2').html(),
                ahname:$('#ahname').val(),
                transit:$('#transit').val(),
                institution:$('#institution').val(),
                account:$('#account').val(),
                sin_num:$('#sin_num').val()
            };
            $.ajax({
                url: "{pigcms{:U('Deliver/step_1')}",
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success:function(date){
                    window.parent.location = "{pigcms{:U('Deliver/step_2')}";
                }

            });
        }
    });
</script>
</body>
</html>