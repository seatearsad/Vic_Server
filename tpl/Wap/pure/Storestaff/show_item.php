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
    .item_show{
        color: #ffa52d;
        font-weight: bold;
    }
    .top_btn{
        flex: 1 1 100%;
        line-height: 35px;
        border: 1px solid #CCCCCC;
        color: #ffa52d;
    }
    .act_btn{
        color: white;
        background: #ffa52d;
        border: 1px solid #ffa52d;
    }
</style>
<body>
<include file="header" />
<div id="main">
    <div style="text-align: center;font-size: 16px;display: flex">
        <div class="top_btn act_btn">Basic Information</div>
        <div class="top_btn">Option/Add-On</div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Item Name (English)*
        </div>
        <div class="item_show">{pigcms{$goods.en_name}</div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Item Name (Mandarin)
        </div>
        <div class="item_show">{pigcms{$goods.cn_name}</div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Price*
        </div>
        <div class="item_show">{pigcms{$goods.price}</div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Description (Recommended)
        </div>
        <div class="item_show">{pigcms{$goods.des}</div>
    </div>
    <div class="order_input">
        <div id="product_img">
            <img src="{pigcms{$goods.image_url}" width="200" />
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Category
        </div>
        <div class="item_show">{pigcms{$sort.sort_name}</div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Listing Order
        </div>
        <div class="item_show">{pigcms{$goods.sort}</div>
    </div>
    <div class="order_input">
        <div class="input_title">
            Bottle Deposit
        </div>
        <div class="item_show">{pigcms{$goods.deposit_price}</div>
    </div>
    <div class="confirm_btn_order" id="confirm_order">
        Edit
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    $('#confirm_order').click(function () {
        window.location.href = "{pigcms{:U('Storestaff/add_item')}&goods_id={pigcms{$goods.goods_id}&sort_id={pigcms{$sort.sort_id}"
    });
</script>
</body>
</html>
