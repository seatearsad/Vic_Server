<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{pigcms{:L('QW_CATEGORYLIST')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/staff.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
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
    <div style="text-align: center;font-size: 16px;">{pigcms{:L('QW_CATEGORYLIST')}</div>
    <div class="confirm_btn_order" id="add_category" style="width: 100%">
        <span class="cate_btn">{pigcms{:L('QW_ADDCATEGORY')}</span>
    </div>
    <volist name="sort_list" id="vo">
        <div class="cate_list <if condition='$i%2 eq 1'>list_right</if>" data-id="{pigcms{$vo.sort_id}">
            <div style="font-weight: bold;line-height: 25px;font-size: 14px;">{pigcms{$vo.sort_name}</div>
            <div>
                <if condition="$vo['stop_count'] gt 0 ">
                    <label class="r_color">
                        {pigcms{:replace_lang_str(L('QW_INACTIVEITEM'),$vo['stop_count'])}
                    </label>
                </if>
                    {pigcms{:replace_lang_str(L('QW_ACTIVEITEM'),$vo['normal_count'])}
                    &nbsp;&nbsp;{pigcms{:L('QW_MENUORDER')}:{pigcms{$vo.sort}
            </div>
            <span class="cate_set" data-id="{pigcms{$vo.sort_id}" data-fid=""{pigcms{$vo.fid}"></span>
        </div>
    </volist>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    $('#add_category').click(function () {
        art.dialog.data('domid', 0);
        art.dialog.open("{pigcms{:U('Storestaff/manage_add_cate')}",{lock:true,title:"{pigcms{:L('_STORE_ADD_PRO_CATE_')}",background: '#000',opacity: 0.45});
    });
    $('.cate_set').click(function () {
        var sort_id = $(this).data('id');
        var fid = $(this).data('fid');
        edit_cate(sort_id,fid);
        stopDefault();
    });
    $('.cate_list').click(function () {
        var sort_id = $(this).data('id');
        window.location.href = "{pigcms{:U('Storestaff/goods_list')}&sort_id="+sort_id;
    });
    function edit_cate(sort_id,fid){
        window.location.href = "{pigcms{:U('Storestaff/category_setting')}&sort_id="+sort_id;
    }
    function stopDefault(e) {
        e = e || window.event;
        e.stopPropagation();
    }
    var all_height = $(window).height();
    var all_width = $(window).width();
    if(all_height > all_width){
        $('.cate_list').css('width','100%');
    }else{
        $('.cate_list').css('width','49%');
    }
</script>

