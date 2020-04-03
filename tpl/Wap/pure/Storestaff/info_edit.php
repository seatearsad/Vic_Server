<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
        Account Management
    </title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    #main{
        padding: 20px 5%;
        color: #999999;
        line-height: 16px;
        display: inline-block;
        font-size: 12px;
        width: 100%;
        box-sizing: border-box;
    }
    textarea{
        width: 100%;
        border: 1px solid #ffa52d;
        height: 80px;
    }
    select{
        width: 100%;
        height: 30px;
        margin-top: 5px;
        border: 1px solid #ffa52d;
        border-radius: 3px;
    }
    input[type="file"] {
        position: absolute;
        display: block;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
</style>
<body>
<include file="header" />
<div id="main">
    <div style="text-align: center;font-size: 16px;">
        Edit Store Information
    </div>
    <div class="order_input">
        <div class="input_title">
            Store Name (English)*
        </div>
        <input type="text" name="name_en" placeholder="English (Required)" value="{pigcms{$store.en_name}" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Store Name (Mandarin)
        </div>
        <input type="text" name="name_cn" placeholder="Mandarin (Optional)" value="{pigcms{$store.cn_name}" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Store Phone Number*
        </div>
        <input type="text" name="phone" placeholder="10 digit store number" value="{pigcms{$store.phone}" />
        <div>
            This number will be used to send you voice messages for new orders.
        </div>
    </div>
    <div class="order_input">
        <div class="img_btn" id="upload_img">Upload An Image</div>
        <div id="product_img" style="margin-top: 5px">
            <if condition="$store['image'] neq ''">
            <img src="{pigcms{$store.image}" width="200" />
            <input type="hidden" name="pic_info" value="{pigcms{$store[pic_info]}" />
            </if>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Store Description
        </div>
        <input type="text" name="txt_info" value="{pigcms{$store.txt_info}" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Keywords
        </div>
        <input type="text" name="feature" placeholder="" value="{pigcms{$store.feature}" />
        <div>
            A short introduction that will appear under your store name.
        </div>
    </div>
    <div class="confirm_btn_order" id="confirm_order">
        Save
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    var uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Storestaff/ajax_store_upload')}",
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploader.addButton({
        id:'#upload_img',
        name:'image_0',
        multiple:false
    });
    uploader.on('uploadSuccess',function(file,response){
        if(response.error == 0){
            // var fid = file.source.ruid;
            // var ruid = fid.split('_');
            // var img = findImg(ruid[1],response.file);
            $('#product_img').html('<img src="'+response.url+'" width="200" /><input type="hidden" name="pic_info" value="'+response.title+'"/>');
        }else{
            alert(response.info);
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });

    $('#confirm_order').click(function () {
        if($('input[name="name_en"]').val() == '' || $('input[name="phone"]').val() == ''){
            alert('Please input required optional.');
            return false;
        }
        var data = {};
        data['en_name'] = $('input[name="name_en"]').val();
        data['cn_name'] = $('input[name="name_cn"]').val();
        data['phone'] = $('input[name="phone"]').val();
        data['txt_info'] = $('input[name="txt_info"]').val();
        data['pic_info'] = $('input[name="pic_info"]').val();
        data['feature'] = $('input[name="feature"]').val();

        layer.open({
            type:2,
            content:'Loading...'
        });
        $.post("{pigcms{:U('Storestaff/manage_info')}",data,function(result) {
            if(result.status == 1){
                layer.closeAll();
                layer.open({
                    content: "Success",
                    type:2,
                    time:1,
                    end:function () {
                        window.location.href = "{pigcms{:U('Storestaff/manage_info')}";
                    }
                });
            }else{
                layer.open({
                    content: "Fail",
                    type: 2,
                    time: 1
                });
            }
        },'JSON');
    });
</script>
</body>
</html>
