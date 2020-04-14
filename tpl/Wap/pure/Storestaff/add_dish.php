<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
        <if condition="$dish['id']">
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
                Choose At Least *
            </div>
            <input type="text" name="min" placeholder="0" value="{pigcms{$dish.min}" />
        </div>
        <div class="input_one" style="margin-left: 10%">
            <div class="input_title">
                Choose At Most *
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
            <option value="1" <if condition="$dish['type'] eq 1">selected="selected"</if>>
                Yes, allow quantity modification
            </option>
            <option value="0" <if condition="$dish['type'] eq 0">selected="selected"</if>>
                No, allow quantity modification
            </option>
        </select>
    </div>
    <div class="order_input" id="add_choice_div">
        <div class="input_title">
            Choices & Price
        </div>
        <volist name="dish['value']" id='vo'>
            <div data-id="dish_{pigcms{$vo['id']}">
                <input type="text" name="dish_val_name"  value="{pigcms{$vo.name}" style="width: 60%;border-right: none;" />
                <input type="text" name="dish_val_price" placeholder="+$" value="{pigcms{$vo.price}" style="width: 20%;margin-left: -6px;" />
                <span class="dish_val_del"></span>
            </div>
        </volist>
        <div data-id="new_0">
            <input type="text" name="dish_val_name"  value="" style="width: 60%;border-right: none;" />
            <input type="text" name="dish_val_price" placeholder="+$" value="" style="width: 20%;margin-left: -6px;" />
            <span class="dish_val_del"></span>
        </div>
    </div>
    <div class="confirm_btn_order" id="add_choice" style="width: 100%;">
        Add A Choice
    </div>
    <input type="hidden" name="dish_id" value="{pigcms{$dish['id']}" />
    <if condition="$dish['id']">
        <div style="display: flex;margin-top: 40px;">
            <div class="del_dish"></div>
            <div class="confirm_btn_order" id="confirm_order" style="width: 80%;margin-left: 5%">
                Save
            </div>
        </div>
        <else />
        <div class="confirm_btn_order" id="confirm_order" style="margin-top: 40px;">
            Save
        </div>
    </if>

</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    $('#confirm_order').click(function () {
        if($('input[name="name"]').val() == '' || $('input[name="min"]').val() == '' || $('input[name="max"]').val() == ''){
            alert('Please input required optional.');
            return false;
        }
        var data = {};
        data['name'] = $('input[name="name"]').val();
        data['min'] = $('input[name="min"]').val();
        data['max'] = $('input[name="max"]').val();
        data['type'] = $('select[name="type"] option:selected').val();
        data['dish_id'] = $('input[name="dish_id"]').val();
        data['goods_id'] = "{pigcms{$goods['goods_id']}"

        $('#add_choice_div').find('input[name="dish_val_name"]').each(function () {
            if($(this).val() != ''){
                var id = $(this).parent().data('id');
                data['val_name_'+id] = $(this).val();
                data['val_price_'+id] = $(this).parent().children('input[name="dish_val_price"]').val();
                if(data['val_price_'+id] == '') data['val_price'+id] = 0;
            }
        });

        layer.open({
            type:2,
            content:'Loading...'
        });
        $.post("{pigcms{:U('Storestaff/add_dish')}",data,function(result) {
            if(result.error == 0){
                layer.closeAll();
                layer.open({
                    content: "Success",
                    type:2,
                    time:1,
                    end:function () {
                        window.location.href = "{pigcms{:U('Storestaff/show_item')}&goods_id={pigcms{$goods['goods_id']}&sort_id={pigcms{$goods['sort_id']}&type=1";
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

    var new_num = 0;

    $('.dish_val_del').click(function () {
        del_choice($(this).parent());
    });

    $('#add_choice').click(function () {
        new_num++;
        var add_html = '<div data-id="new_'+new_num+'">' +
            '            <input type="text" name="dish_val_name"  value="" style="width: 60%;border-right: none;" />' +
            '            <input type="text" name="dish_val_price" placeholder="+$" value="" style="width: 20%;margin-left: -6px;" />' +
            '            <span class="dish_val_del"></span>' +
            '        </div>';

        $('#add_choice_div').append(add_html);
        $('.dish_val_del').unbind('click');
        $('.dish_val_del').click(function () {
            del_choice($(this).parent());
        });
    });

    function del_choice(dish_val_div) {
        var dish_val_id = dish_val_div.data('id');
        if(!confirm('确定要删除吗?不可恢复!')){
            return false;
        }else{
            if(dish_val_id.indexOf('new')!=-1)
                dish_val_div.remove();
            else{
                var str = dish_val_id.split('_');
                $.post("{pigcms{:U('Storestaff/del_dish_val')}",{val_id:str[1]},function(result) {
                    if(result.error == 0)
                        dish_val_div.remove();
                    else
                        layer.open({
                            content: "Fail",
                            type: 2,
                            time: 1
                        });
                },'JSON');
            }
        }
    }
    
    $('.del_dish').click(function () {
        if(!confirm('确定要删除吗?不可恢复!')){
            return false;
        }else{
            var data = {};
            data['dish_id'] = $('input[name="dish_id"]').val();
            layer.open({
                type:2,
                content:'Loading...'
            });
            $.post("{pigcms{:U('Storestaff/del_dish')}",data,function(result) {
                if(result.error == 0){
                    layer.closeAll();
                    layer.open({
                        content: "Success",
                        type:2,
                        time:1,
                        end:function () {
                            window.location.href = "{pigcms{:U('Storestaff/show_item')}&goods_id={pigcms{$goods['goods_id']}&sort_id={pigcms{$goods['sort_id']}&type=1";
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
    });
</script>
</body>
</html>
