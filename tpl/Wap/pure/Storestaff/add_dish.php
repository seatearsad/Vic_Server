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
        width: 80%;
        height: 30px;
        margin-top: 5px;
        border: 1px solid #ffa52d;
        border-radius: 3px;
        vertical-align: top;
    }
    input[type="file"] {
        position: absolute;
        display: block;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
    .input_title{
        font-size: 14px;
    }
    .order_input input{
        line-height: 30px;
        font-size: 14px;
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
            {pigcms{:L('QW_OPTIONTITLE')} *
        </div>
        <input type="text" name="name" placeholder="" value="{pigcms{$dish.name}" />
    </div>
    <div class="double_input">
        <div class="input_one">
            <div class="input_title">
                {pigcms{:L('QW_ATLEAST')} *
            </div>
            <select name="min" autocomplete="off">
                <option value="-10">{pigcms{:L('QW_MIN')}</option>
                <php>
                    for($i=0;$i<=100;++$i){
                </php>
                <option value="{pigcms{$i}" <if condition="isset($dish['min']) and $i eq $dish['min']">selected=selected</if>>{pigcms{$i}</option>
                <php>
                    }
                </php>
            </select>
        </div>
        <div class="input_one" style="margin-left: 10%">
            <div class="input_title">
                {pigcms{:L('QW_ATMOST')} *
            </div>
            <select name="max" autocomplete="off">
                <option value="-10">{pigcms{:L('QW_MAX')}</option>
                <php>
                    for($i=-1;$i<=100;++$i){
                </php>
                <option value="{pigcms{$i}" <if condition="$dish['max'] and $i eq $dish['max']">selected=selected</if>>{pigcms{$i}</option>
                <php>
                    }
                </php>
            </select>
        </div>
    </div>
    <div style="margin: 5px 0 15px 0;display: none;">
        Input "0" to make it optional; input "-1" to indicate "unlimited"; input the exact same number for both min. and max. to required customers choose an exact number of options.
    </div>
    <div class="order_input">
        <div class="input_title">
            {pigcms{:L('QW_SINGLEMULTI')}
        </div>
        <select name="type" autocomplete="off">
            <option value="1" <if condition="$dish['type'] eq 1">selected="selected"</if>>
                {pigcms{:L('QW_YES')}
            </option>
            <option value="0" <if condition="$dish['type'] eq 0">selected="selected"</if>>
                {pigcms{:L('QW_NO')}
            </option>
        </select>
        <span class="dish_type_img"></span>
    </div>
    <div class="order_input" id="add_choice_div">
        <div class="input_title">
            {pigcms{:L('QW_CHOICEPRICE')}
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
        {pigcms{:L('QW_ADDCHOICE')}
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
        if($('input[name="name"]').val() == '' || $('select[name="min"] option:selected').val() == -10 || $('select[name="max"] option:selected').val() == -10){
            layer.open({
                content: "Please input required optional.",
                btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]
            });
            return false;
        }
        var data = {};
        data['name'] = $('input[name="name"]').val();
        data['min'] = $('select[name="min"] option:selected').val();
        data['max'] = $('select[name="max"] option:selected').val();
        data['type'] = $('select[name="type"] option:selected').val();
        data['dish_id'] = $('input[name="dish_id"]').val();
        data['goods_id'] = "{pigcms{$goods['goods_id']}"

        var is_continue = true;
        $('#add_choice_div').find('input[name="dish_val_name"]').each(function () {
            if($(this).val() != ''){
                var id = $(this).parent().data('id');
                data['val_name_'+id] = $(this).val();
                data['val_price_'+id] = $(this).parent().children('input[name="dish_val_price"]').val();
                if(data['val_price_'+id] == ''){
                    is_continue = false;
                }
                //if(data['val_price_'+id] == '') data['val_price'+id] = 0;
            }
        });

        if(!is_continue){
            layer.open({
                content: "Please input price.",
                btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]
            });
            return false;
        }

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
        layer.open({
            title:"{pigcms{:L('_STORE_REMIND_')}",
            content:'确定要删除吗?不可恢复!',
            btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
            yes: function(index){
                layer.close(index);
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
        });
    }
    
    $('.del_dish').click(function () {
        layer.open({
            title:"{pigcms{:L('_STORE_REMIND_')}",
            content:'确定要删除吗?不可恢复!',
            btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
            yes: function(index){
                layer.close(index);
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
    });

    $('.dish_type_img').click(function () {
        var img_1 = "{pigcms{$static_path}img/staff_menu/dish_ex_1.jpg";
        var img_2 = "{pigcms{$static_path}img/staff_menu/dish_ex_2.jpg";
        var tip_message = "<div>" +
            "                <div style='text-align: center;font-weight: bold;margin-top: -20px;'>" +
            "                    {pigcms{:L('QW_SINGLEMULTI')}" +
            "                </div>" +
            "                <div style='margin-top: 20px'>"+
            '                    {pigcms{:L('QW_SINGLEMULTIDESCRA')}' +
            "                </div>" +
            "                <div style='text-align: center;'>"+
            "                <img src=\""+img_1+"\" width=\"230\">" +
            "                </div>" +
            "                <div style='margin-top: 20px'>"+
            '                    {pigcms{:L('QW_SINGLEMULTIDESCRB')}' +
            "                </div>" +
            "                <div style='text-align: center;'>"+
            "                <img src=\""+img_2+"\" width=\"230\">" +
            "                </div>" +
            "            </div>";
        layer.open({
            title:[' ','border:none'],
            content:tip_message,
            style: 'border:2px solid #ffa52d; background-color:#fff; color:#666;'
        });
    });
</script>
</body>
</html>
