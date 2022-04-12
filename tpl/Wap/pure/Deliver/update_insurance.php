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
        #save,.upload_btn{
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
        .upload_btn{
            width: auto;
            margin-left: 10px;
            padding: 0 10px;
            display: inline-block;
        }
        input[type="file"] {
            display: block;
            position: absolute;
            opacity: 0;
            -ms-filter: 'alpha(opacity=0)';
        }
        .img_0{
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }
        .img_0 img{
            height: 100px;
        }
    </style>
</head>
<body>
<include file="header" />
<div id="all">
    <div id="title">
        Update Vehicle Insurance
    </div>
    <div class="tip">
        Your vehicle insurance is expiring soon or has already expired. Please update or you may not be able to do deliveries.
    </div>
    <div class="tip">
        Make sure your photos are clear, especially the name and expiration date. Photos that are unclear or invalid may result in verification failure.
    </div>
    <div class="tip" style="color: black;">
        <span style="font-weight: bold;margin-bottom: 10px;">Vehicle Insurance</span>
        <span class="upload_btn" id="J_selectImage_0">Upload a photo</span>
        <input type="hidden" name="img_file" id="img_file" />
    </div>
    <div class="tip img_0">

    </div>
    <div id="save">
        Save & Update
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script type="text/javascript">
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

    function addUploadBtn() {
        uploader.addButton({
            id: '#J_selectImage_0',
            name: 'image_0',
            multiple: false
        });
    }

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
            //var img = findImg(ruid[1],response.file);
            $(".img_0").html('<img src="'+response.url+'"/>');
            $(".img_0").css("height","100px");
            $("#img_file").val(response.file);
        }else{
            //alert(response.info);
            layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:response.info, btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
        }
    });

    uploader.on('uploadError', function(file,reason){
        layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:"Fail", btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
    });

    addUploadBtn();

    $('#save').click(function () {
        if($("#img_file").val() == ''){
            layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content:"{pigcms{:L('_PLEASE_INPUT_ALL_')}", btn:["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]});
        }else{
            var data = {'insurance':$("#img_file").val()};
            $.post("", data, function (result) {
                if(!result.error_code){
                    layer.open({title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",content: result.msg,skin: 'msg', time:1,end:function () {
                            window.parent.location = "{pigcms{:U('Deliver/account')}";
                        }});
                }
            }, 'JSON');
        }
    });
</script>
</body>
</html>