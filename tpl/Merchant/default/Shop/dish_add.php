<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-cubes"></i>
                <a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
            </li>
            <li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">分类列表</a></li>
            <li class="active"><a href="{pigcms{:U('Shop/goods_list',array('sort_id'=>$now_sort['sort_id']))}">{pigcms{$now_sort.sort_name}</a></li>
            <li class="active"><a href="{pigcms{:U('Shop/side_dish',array('goods_id'=>$now_goods['goods_id']))}">{pigcms{$now_goods.name} 配菜列表</a></li>
            <if condition="dish_id">
                <li class="active">修改配菜</li>
            <else />
                <li class="active">添加配菜</li>
            </if>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div class="tabbable">
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active">
                                <if condition="dish_id">
                                    <a href="javascript:void()">修改配菜</a>
                                <else />
                                    <a href="javascript:void()">添加配菜</a>
                                </if>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="grid-view">
                            <form enctype="multipart/form-data" class="form-horizontal" method="post" onsubmit="return checkForm();">
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="sort_name">商品名称</label></label>
                                    <div style="padding-top: 4px;">{pigcms{$now_goods.name}</div>
                                    <input name="goods_id" type="hidden" value="{pigcms{$now_goods.goods_id}"/>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="sort_name">名称</label></label>
                                    <input name="dish_id" type="hidden" value="{pigcms{$dish_id|default='0'}"/>
                                    <input class="col-sm-2" size="20" name="dish_name" id="dish_name" type="text" value="{pigcms{$side_dish.name}"/>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="sort">下限</label></label>
                                    <input class="col-sm-1" size="10" name="min" id="min" type="text" value="{pigcms{$side_dish.min|default='0'}"/>
                                    <span class="form_tips"></span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1"><label for="sort">上限</label></label>
                                    <input class="col-sm-1" size="10" name="max" id="max" type="text" value="{pigcms{$side_dish.max|default='-1'}"/>
                                    <span class="form_tips">填写-1 为不限</span>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-1" for="dish_type">可否多选</label>
                                    <select name="dish_type" id="dish_type">
                                        <option value="0" <if condition="$side_dish['type'] eq 0">selected="selected"</if>>单选</option>
                                        <option value="1" <if condition="$side_dish['type'] eq 1">selected="selected"</if>>多选</option>
                                    </select>
                                </div>
                                <div class="clearfix form-actions" id="single_list">
                                    <button class="btn btn-success" type="button" onclick="addValue()">添加单品</button>
                                    <div style="border-bottom: 1px solid #CCCCCC;height: 10px;margin-bottom: 10px"> </div>
                                    <volist name="dish_value" id="vo">
                                        <div class="form-group" id="old_{pigcms{$vo.id}">
                                            <label class="col-sm-1"><label for="sort">单品名称</label></label>
                                            <input class="col-sm-2" size="20" name="value_name-{pigcms{$vo.id}" id="value_name-{pigcms{$vo.id}" data-id="old_{pigcms{$vo.id}" type="text" value="{pigcms{$vo.name}"/>
                                            <label class="col-sm-1"><label for="sort">单品价格</label></label>
                                            <input class="col-sm-1" size="10" name="value_price-{pigcms{$vo.id}" id="value_price-{pigcms{$vo.id}" placeholder="0.00" data-id="old_{pigcms{$vo.id}" type="text" value="{pigcms{$vo.price}"/>
                                            <label class="col-sm-1" style="float: right;"><label class="single_del" data-id="old_{pigcms{$vo.id}" for="sort">删除</label></label>
                                        </div>
                                    </volist>
                                </div>
                                <div class="clearfix form-actions">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button class="btn btn-info" type="submit">
                                            <i class="ace-icon fa fa-check bigger-110"></i>
                                            保存
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .single_del{
        color: #FF0000;
        cursor: pointer;
    }
</style>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script>
    var new_num = 0;
    function addValue(){
        new_num++;
        $('#single_list').append(z_html());

        $('.single_del').unbind("click");
        $('.single_del').bind('click',function () {
            var tid = $(this).data('id');
            if(confirm('确定要删除此单品吗？')){
                $('#'+tid).remove();
            }else{
                return false;
            }
        });
    }

    $('.single_del').bind('click',function () {
        var tid = $(this).data('id');
        if(confirm('确定要删除此单品吗？')){
            var f_tid = tid.split("_");
            if(f_tid[0] == 'new'){
                $('#'+tid).remove();
            }else{
                $.post("{pigcms{:U('Shop/del_dish_value')}", {'dish_value_id':f_tid[1],'dish_id':'{pigcms{$dish_id}'}, function (result) {
                    if(result['error'] == 0){
                        $('#'+tid).remove();
                    }else{
                        alert(result['message']);
                    }
                },'json');
            }
        }else{
            return false;
        }
    });

    function z_html(){
        var str = '<div class="form-group" id="new_'+new_num+'">';

        str += '<label class="col-sm-1"><label for="sort">单品名称</label></label>';
        str += '<input class="col-sm-2" size="20" name="value_name_new-'+new_num+'" id="value_name_new-'+new_num+'" data-id="new_'+new_num+'" type="text" value=""/>';
        str += '<label class="col-sm-1"><label for="sort">单品价格</label></label>';
        str += '<input class="col-sm-1" size="10" name="value_price_new-'+new_num+'" id="value_price_new-'+new_num+'" placeholder="0.00" data-id="new_'+new_num+'" type="text" value=""/>';
        str += '<label class="col-sm-1" style="float: right;"><label class="single_del" data-id="new_'+new_num+'" for="sort">删除</label></label>';

        return str;
    }

    $(function(){
        /*调整保存按钮的位置*/
        $(".nav-tabs li a").click(function(){
            if($(this).attr("href")=="#imgcontent"){		//店铺图片
                $(".form-submit-btn").css('position','absolute');
                $(".form-submit-btn").css('top','670px');
            }else{
                $(".form-submit-btn").css('position','static');
            }
        });

        //$('form.form-horizontal').submit(function(){
            //$(this).find('button[type="submit"]').html('保存中...').prop('disabled',true);
        //});
        /*分享图片*/
        $('#image-file').ace_file_input({
            no_file:'gif|png|jpg|jpeg格式',
            btn_choose:'选择',
            btn_change:'重新选择',
            no_icon:'fa fa-upload',
            icon_remove:'',
            droppable:false,
            onchange:null,
            remove:false,
            thumbnail:false
        });
    });
    
    function checkForm() {
        var is_ok = true;
        var value_list = {};
        $('form').find('input').each(function () {
            //alert($(this).attr('name') + $(this).val());
            if($(this).val() == ''){
                is_ok = false;
            }

            if($(this).attr('name').indexOf("value_name") != -1){
                var index = $(this).attr('name').split("-");
                value_list[index[1]] = {};
                value_list[index[1]]['value'] = $(this).val();
            }
            if($(this).attr('name').indexOf("value_price") != -1){
                var index = $(this).attr('name').split("-");
                //console.log(index[1]);
                value_list[index[1]]['price'] = $(this).val();
            }
        });

        var is_value = false;
        for (var key in value_list) {
            var value = value_list[key];
            if(value['value'] != "" && value['price'] != ""){
                is_value = true;
            }
        }
        if(!is_value){
            alert("请添加配菜单品！");
            return false;
        }

        if(!is_ok){
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
            return false;
        }else{
            $('form.form-horizontal').find('button[type="submit"]').html('保存中...').prop('disabled',true);
            return true;
        }
    }

    function previewimage(input){
        if (input.files && input.files[0]){
            var reader = new FileReader();
            reader.onload = function (e) {$('#image_preview_box').html('<img style="width:120px;height:120px" src="'+e.target.result+'" alt="图片预览" title="图片预览"/>');}
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<include file="Public:footer"/>
