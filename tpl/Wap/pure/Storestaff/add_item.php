<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Add An Item</title>
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
    <div style="text-align: center;font-size: 16px;">Add An Item</div>
    <div class="order_input">
        <div class="input_title">
            Item Name (English)*
        </div>
        <input type="text" name="name_en" placeholder="English (Required)" value="{pigcms{$goods.en_name}" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Item Name (Mandarin)
        </div>
        <input type="text" name="name_cn" placeholder="Mandarin (Optional)" value="{pigcms{$goods.cn_name}" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Price*
        </div>
        <input type="text" name="price" placeholder="0.00" value="{pigcms{$goods.price}" />
    </div>
    <div class="order_input">
        <div class="input_title">
            Description (Recommended)
        </div>
        <textarea name="desc">{pigcms{$goods.dsc}</textarea>
    </div>
    <div class="order_input">
        <div class="img_btn" id="upload_img">Upload An Image</div>
        <div id="product_img">
            <if condition="$goods['image'] neq ''">
            <img src="{pigcms{$goods['pic_arr'][0]['url']}" width="200" />
            <input type="hidden" name="product_pic" value="{pigcms{$goods.image}" />
            </if>
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Category
        </div>
        <select name="category" autocomplete="off">
            <volist name="sort_list" id="vo">
            <option value="{pigcms{$vo.sort_id}" <if condition="$sort['sort_id'] eq $vo['sort_id']">selected="selected"</if>>{pigcms{:lang_substr($vo['sort_name'],C('DEFAULT_LANG'))}</option>
            </volist>
        </select>
    </div>
    <div class="order_input">
        <div class="input_title">
            Listing Order
        </div>
        <input type="text" name="sort" value="{pigcms{$goods.sort}" />
        <div>
            Item with a large number will be listed on top in it's category.
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Bottle Deposit
        </div>
        <input type="text" name="deposit_price" placeholder="0.00" value="{pigcms{$goods.deposit_price}" />
    </div>
    <input type="hidden" name="goods_id" value="{pigcms{$goods.goods_id}" />
    <div class="confirm_btn_order" id="confirm_order">
        Continue
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    var uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Storestaff/ajax_upload')}",
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
            $('#product_img').html('<img src="'+response.url+'" width="200" /><input type="hidden" name="product_pic" value="'+response.title+'"/>');
        }else{
            alert(response.info);
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });

    $('#confirm_order').click(function () {
        if($('input[name="name_en"]').val() == '' || $('input[name="price"]').val() == ''){
            alert('Please input required optional.');
            return false;
        }
        var data = {};
        data['en_name'] = $('input[name="name_en"]').val();
        data['cn_name'] = $('input[name="name_cn"]').val();
        data['price'] = $('input[name="price"]').val();
        data['desc'] = $('textarea[name="desc"]').val();
        data['product_image'] = $('input[name="product_pic"]').val();
        data['sort_id'] = $('select[name="category"]').val();
        data['sort'] = $('input[name="sort"]').val();
        data['deposit'] = $('input[name="deposit_price"]').val();
        data['goods_id'] = $('input[name="goods_id"]').val();

        layer.open({
            type:2,
            content:'Loading...'
        });
        $.post("{pigcms{:U('Storestaff/add_item')}",data,function(result) {
            if(result.error == 0){
                layer.closeAll();
                layer.open({
                    content: "Success",
                    type:2,
                    time:1,
                    end:function () {
                        window.location.href = "{pigcms{:U('Storestaff/show_item')}&goods_id="+result.goods;
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
