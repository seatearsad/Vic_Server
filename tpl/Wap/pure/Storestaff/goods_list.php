<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Category List</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
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
</style>
<body>
<include file="header" />
<div id="main">
    <div style="text-align: center;font-size: 16px;color: #666666">{pigcms{$sort.sort_name}</div>
    <div style="text-align: center;margin-top: 5px;">
        <if condition="$sort[is_time] eq 1">
            {pigcms{$sort.show_time}
        </if>
        <if condition="$sort[is_weekshow] eq 1">
            {pigcms{$sort.week_show}
        </if>
    </div>
    <div class="add_product">
        <div class="add_btn">
            <span class="cate_btn">Add An Item</span>
        </div>
        <div class="pro_btn act" data-type="0">All<br>Item(s)</div>
        <div class="pro_btn" data-type="1">Active<br>Item(s)</div>
        <div class="pro_btn" data-type="2">Inactive<br>Item(s)</div>
    </div>
    <div class="goods_list">
        <div class="goods_name">Name</div>
        <div class="goods_price">Price</div>
        <div class="goods_option">Options</div>
        <div style="width: 10%;"></div>
    </div>
    <div id="goodsList"></div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script id="GoodsListTpl" type="text/html">
    {{# for(var i = 0, len = d.length; i < len; i++){ }}
    {{# if(d[i].status == 1){ }}
        <div class="goodsList normal" data-id="{{ d[i].goods_id }}">
    {{# }else{ }}
        <div class="goodsList stop" data-id="{{ d[i].goods_id }}">
    {{# } }}
        <div class="goods_name">{{ d[i].name }}</div>
        <div class="goods_price">${{ d[i].price }}</div>
        <div class="goods_option" style="padding-left: 20px;box-sizing: border-box;">3</div>
        <div class="goods_btn"></div>
    </div>
    {{# } }}
</script>
<script>
    var select_type = 0;//0 all 1 active 2 inactive
    getGoodsList();
    function getGoodsList() {
        layer.open({
            type:2,
            content:'Loading...'
        });
        $.post("{pigcms{:U('Storestaff/getGoodsList')}",{select_type:select_type,sort_id:"{pigcms{$sort.sort_id}"},function(result) {
            layer.closeAll();
            if (result.error == 0) {
                laytpl($('#GoodsListTpl').html()).render(result.list, function(html){
                    $('#goodsList').html(html);
                    $('.goodsList').unbind('click');
                    $('.goodsList').click(function () {
                        loadGoods($(this).data('id'));
                    });
                });
            }
        },'JSON');
    }

    $('.pro_btn').click(function () {
        select_type = $(this).data('type');
        $('.add_product').children('.pro_btn').each(function () {
            $(this).removeClass('act');
        });
        $(this).addClass('act');
        getGoodsList();
    });

    function loadGoods(goods_id) {

    }
</script>

