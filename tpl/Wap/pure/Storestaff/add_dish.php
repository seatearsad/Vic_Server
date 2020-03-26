<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
        <if condition="$dish_id">
            Edit Options
            <else />
            Add Options
        </if>
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
        {pigcms{:lang_substr($goods['name'], C('DEFAULT_LANG'));}
    </div>
    <div class="order_input">
        <div class="input_title">
            Option Title *
        </div>
        <input type="text" name="name" placeholder="" value="{pigcms{$dish.name}" />
    </div>
    <div class="double_input">
        <div class="input_one">
            <div class="input_title">
                Choose At Least
            </div>
            <input type="text" name="min" placeholder="0" value="{pigcms{$dish.min}" />
        </div>
        <div class="input_one" style="margin-left: 10%">
            <div class="input_title">
                Choose At Most
            </div>
            <input type="text" name="max" placeholder="-1" value="{pigcms{$dish.max}" />
        </div>
    </div>
    <div style="margin: 5px 0 15px 0;">
        Choose"0" to make it optional; choose "Unlimited" to allow customers add as many as they'd like.
    </div>
    <div class="order_input">
        <div class="input_title">
            Allow customers to choose the same option mutiple times?
        </div>
        <select name="type" autocomplete="off">
            <option value="0">
                Yes, allow quantity modification
            </option>
            <option value="0">
                No, allow quantity modification
            </option>
        </select>
    </div>
    <div class="order_input">
        <div class="input_title">
            Choices & Price
        </div>
        <input type="text" name="sort" value="" style="width: 60%;border-right: none;" />
        <input type="text" name="sort" placeholder="+$" value="" style="width: 20%;margin-left: -6px;" />
        <span class="dish_val_del"></span>
    </div>
    <div class="confirm_btn_order" id="add_choice" style="width: 100%;">
        Add A Choice
    </div>
    <input type="hidden" name="dish_id" value="" />
    <div class="confirm_btn_order" id="confirm_order" style="margin-top: 40px;">
        Save
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    $('#confirm_order').click(function () {
        // if($('input[name="name_en"]').val() == '' || $('input[name="price"]').val() == ''){
        //     alert('Please input required optional.');
        //     return false;
        // }
        // var data = {};
        // data['en_name'] = $('input[name="name_en"]').val();
        // data['cn_name'] = $('input[name="name_cn"]').val();
        // data['price'] = $('input[name="price"]').val();
        // data['desc'] = $('textarea[name="desc"]').val();
        // data['product_image'] = $('input[name="product_pic"]').val();
        // data['sort_id'] = $('select[name="category"]').val();
        // data['sort'] = $('input[name="sort"]').val();
        // data['deposit'] = $('input[name="deposit_price"]').val();
        // data['goods_id'] = $('input[name="goods_id"]').val();
        //
        // layer.open({
        //     type:2,
        //     content:'Loading...'
        // });
        // $.post("{pigcms{:U('Storestaff/add_item')}",data,function(result) {
        //     if(result.error == 0){
        //         layer.closeAll();
        //         layer.open({
        //             content: "Success",
        //             type:2,
        //             time:1,
        //             end:function () {
        //                 window.location.href = "{pigcms{:U('Storestaff/show_item')}&goods_id="+result.goods;
        //             }
        //         });
        //     }else{
        //         layer.open({
        //             content: "Fail",
        //             type: 2,
        //             time: 1
        //         });
        //     }
        // },'JSON');
    });
</script>
</body>
</html>
