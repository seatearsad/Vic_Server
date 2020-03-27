<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Item</title>
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
        <div class="top_btn <if condition='$show_type eq 0'>act_btn</if>" data-type="0">Basic Information</div>
        <div class="top_btn <if condition='$show_type eq 1'>act_btn</if>" data-type="1">Option/Add-On</div>
    </div>
    <div id="base" style="display: none;">
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
        <div class="edit_div">
            <div class="del_btn" data-id="{pigcms{$goods['goods_id']}">Delete</div>
            <if condition="$goods['status'] eq 1">
                <div class="status_btn" data-status="{pigcms{$goods['status']}" data-id="{pigcms{$goods['goods_id']}">Deactivate this Item</div>
            <else />
                <div class="status_btn status_0" data-status="{pigcms{$goods['status']}" data-id="{pigcms{$goods['goods_id']}">Reactivate this Item</div>
            </if>
        </div>
        <div class="confirm_btn_order" id="confirm_order" style="width: 100%;">
            Edit
        </div>
    </div>
    <div id="option" style="display: none;">
        <div style="text-align: center;font-size: 16px; margin: 10px 0;">
            {pigcms{$goods['name']}
        </div>
        <if condition="$goods['spec_value'] neq '' or $goods['is_properties'] eq 1">
        <div class="option_div">
            <div class="act_op" data-option="1">Options 1</div>
            <div data-option="2">Options 2</div>
        </div>
        </if>
        <div class="op_1" style="margin-top: 20px">
            <div class="add_btn" style="width: 100%">
                <span class="cate_btn">Add Options / Add-On</span>
            </div>
            <div class="option_list">
                <volist name="goods['dish']" id='vo'>
                <div class="option_one" data-dish_id="{pigcms{$vo.id}">
                    <div class="dish_edit">
                        <div class="op_name">{pigcms{$vo['name']}</div>
                        <div class="op_desc">{pigcms{$vo['desc']}</div>
                    </div>
                    <div class="op_num">
                        {pigcms{$vo['val_num']} Options
                        <span class="s_f"></span>
                    </div>
                    <div class="op_val_list">
                        <volist name="vo['value']" id='d_vo'>
                        <div>
                            {pigcms{:lang_substr($d_vo['name'],C('DEFAULT_LANG'))}
                            <if condition="$d_vo['price'] neq 0">
                                (${pigcms{$d_vo['price']})
                            </if>
                        </div>
                        </volist>
                    </div>
                </div>
                </volist>
            </div>
        </div>
        <if condition="$goods['spec_value'] neq '' or $goods['is_properties'] eq 1">
        <div class="op_2" style="display: none">
            <div style="margin-top: 10px;text-align: center">
                Options under this section will be gradually changed in a format of Options 1 by our customer support team. To deleted or make changes, please contact 1-888-399-6668.
            </div>
            <volist name="goods['properties']" id='vo'>
                <div class="option_one">
                    <div class="op_name">{pigcms{$vo['name']}</div>
                    <div class="op_desc"> &nbsp;{pigcms{$vo['desc']}</div>
                    <div class="op_num">
                        {pigcms{$vo['val_num']} Options
                        <span class="s_f"></span>
                    </div>
                    <div class="op_val_list">
                        <volist name="vo['value']" id='d_vo'>
                            <div>
                                {pigcms{:lang_substr($d_vo,C('DEFAULT_LANG'))}
                            </div>
                        </volist>
                    </div>
                </div>
            </volist>
            <volist name="goods['spec']" id='vo'>
                <div class="option_one">
                    <div class="op_name">{pigcms{$vo['name']}</div>
                    <div class="op_desc">&nbsp;{pigcms{$vo['desc']}</div>
                    <div class="op_num">
                        {pigcms{$vo['val_num']} Options
                        <span class="s_f"></span>
                    </div>
                    <div class="op_val_list">
                        <volist name="vo['value']" id='d_vo'>
                            <div>
                                {pigcms{:lang_substr($d_vo['name'],C('DEFAULT_LANG'))}
                            </div>
                        </volist>
                    </div>
                </div>
            </volist>
        </div>
        </if>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    $('#confirm_order').click(function () {
        window.location.href = "{pigcms{:U('Storestaff/add_item')}&goods_id={pigcms{$goods.goods_id}&sort_id={pigcms{$sort.sort_id}"
    });
    
    $('.del_btn').click(function () {
        if(!confirm('确定要删除吗?不可恢复!'))
            return false;
        else{
            var goods_id = $(this).data('id');
            change_status(goods_id,2);
        }
    });
    
    $('.status_btn').click(function () {
        var goods_id = $(this).data('id');
        var status = $(this).data('status') == 1 ? 0 : 1;

        change_status(goods_id,status);
    });

    function change_status(goods_id,status) {
        layer.open({
            type:2,
            content:'Loading...'
        });
        $.post("{pigcms{:U('Storestaff/change_status_item')}",{'goods_id':goods_id,'status':status},function(result) {
            if(result.error == 0){
                layer.closeAll();
                layer.open({
                    content: "Success",
                    type:2,
                    time:1,
                    end:function () {
                        if(status == 2)
                            window.location.href = "{pigcms{:U('Storestaff/goods_list')}&sort_id={pigcms{$goods['sort_id']}";
                        else
                            window.location.reload();
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
    }
    var show_type = "{pigcms{$show_type}";
    show_div();
    $('.top_btn').click(function () {
        $('.top_btn').removeClass('act_btn');
        $(this).addClass('act_btn');

        show_type = $(this).data('type');
        show_div();
    });

    function show_div() {
        if(show_type == 0){
            $('#base').show();
            $('#option').hide();
        }else{
            $('#base').hide();
            $('#option').show();
        }
    }

    $('.option_div').children('div').each(function () {
        $(this).click(function () {
            var op = $(this).data('option');
            $('.option_div').children('div').each(function () {
                $(this).removeClass('act_op');
                if($(this).data('option') != op){
                    $('.op_'+$(this).data('option')).hide();
                }
            });
            $(this).addClass('act_op');

            $('.op_'+op).show();
        });
    });

    $('.dish_edit').click(function () {
        var dish_id = $(this).parent('div').data('dish_id');
        window.location.href = "{pigcms{:U('Storestaff/add_dish')}&goods_id={pigcms{$goods.goods_id}&dish_id="+dish_id;
    });

    $('.op_num').click(function () {
        var list = $(this).next('.op_val_list');

        if(list.is(":hidden")){
            $(this).children('.s_f').addClass('op_open');
            $(list).show();
        }else{
            $(this).children('.s_f').removeClass('op_open');
            $(list).hide();
        }
    });
    
    $('.add_btn').click(function () {
        window.location.href = "{pigcms{:U('Storestaff/add_dish')}&goods_id={pigcms{$goods.goods_id}";
    });
</script>
</body>
</html>
