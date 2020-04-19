<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Category Setting</title>
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
    #begin_time,#end_time{
        border: 1px solid #666666;
        width: 20%;
    }
    select{
        height: 40px;
        width: 20%;
    }
    #week_check{
        width: 20px;
        height: 20px;
        margin-right: 5px;
    }
    .week_en{
        line-height: 20px;
        vertical-align: bottom;
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
    <div style="text-align: center;font-size: 16px;">Category Setting</div>
    <div class="order_input">
        <div class="input_title">
            {pigcms{:L('QW_CATENAMEENG')}*
        </div>
        <input type="text" name="cate_name_en" value="{pigcms{$sort.en_name}" />
    </div>
    <div class="order_input">
        <div class="input_title">
            {pigcms{:L('QW_CATENAMECHIN')}
        </div>
        <input type="text" name="cate_name_cn" value="{pigcms{$sort.cn_name}" />
    </div>
    <div style="border-bottom: 1px dashed #666666;margin-top: 20px"></div>
    <div class="order_input">
        <div class="input_title">
            {pigcms{:L('QW_CATEORDER')}
        </div>
        <input type="text" name="sort" value="{pigcms{$sort.sort}" />
        <div>
            {pigcms{:L('QW_CATEORDERDES')}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            {pigcms{:L('QW_CATESHOWDAY')}
        </div>
        <select name="is_weekshow" autocomplete="off">
            <option value="0" <if condition="$sort['is_weekshow'] eq 0">selected="selected"</if>>{pigcms{:L('QW_OFF')}</option>
            <option value="1" <if condition="$sort['is_weekshow'] eq 1">selected="selected"</if>>{pigcms{:L('QW_ON')}</option>
        </select>
        <div>
            <input id="week_check" type="checkbox" value="1" name="week[]" <if condition="in_array('1',$sort['week'])">checked="checked"</if>><label class="week_en">MON</label>
            <input id="week_check" type="checkbox" value="2" name="week[]" <if condition="in_array('2',$sort['week'])">checked="checked"</if>><label class="week_en">TUE</label>
            <input id="week_check" type="checkbox" value="3" name="week[]" <if condition="in_array('3',$sort['week'])">checked="checked"</if>><label class="week_en">WED</label>
            <input id="week_check" type="checkbox" value="4" name="week[]" <if condition="in_array('4',$sort['week'])">checked="checked"</if>><label class="week_en">THUR</label>
            <input id="week_check" type="checkbox" value="5" name="week[]" <if condition="in_array('5',$sort['week'])">checked="checked"</if>><label class="week_en">FRI</label>
            <input id="week_check" type="checkbox" value="6" name="week[]" <if condition="in_array('6',$sort['week'])">checked="checked"</if>><label class="week_en">SAT</label>
            <input id="week_check" type="checkbox" value="0" name="week[]" <if condition="in_array('0',$sort['week'])">checked="checked"</if>><label class="week_en">SUN</label>
        </div>
        <div style="margin-top: 5px">
            {pigcms{:L('QW_CATESHOWDAYDESCR')}
        </div>
    </div>
    <div class="order_input">
        <div class="input_title">
            {pigcms{:L('QW_CATESHOWTIME')}
        </div>
        <select name="is_time" autocomplete="off">
            <option value="0" <if condition="$sort['is_time'] eq 0">selected="selected"</if>>{pigcms{:L('QW_OFF')}</option>
            <option value="1" <if condition="$sort['is_time'] eq 1">selected="selected"</if>>{pigcms{:L('QW_ON')}</option>
        </select>
        <input class="col-sm-1" size="10" name="begin_time" id="begin_time" type="text" placeholder="00:00" value="{pigcms{$sort.begin_time}" />
        <label style="">&nbsp; - &nbsp;</label>
        <input class="col-sm-1" size="10" name="end_time" id="end_time" type="text" placeholder="00:00" value="{pigcms{$sort.end_time}" />
        <div style="margin-top: 5px">
            {pigcms{:L('QW_CATESHOWTIMEDESCR')}
        </div>
    </div>
    <div class="confirm_btn_order" id="confirm_order">
        {pigcms{:L('QW_SAVE')}
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    var theme = "ios";
    var mode = "scroller";
    var display = "bottom";
    var lang="en";

    $('#begin_time').mobiscroll().time({
        theme: theme,
        mode: mode,
        display: display,
        timeFormat: 'HH:ii',
        timeOrder: 'HHii',
        timeWheels: 'HHii',
        lang: lang
    });

    $('#end_time').mobiscroll().time({
        theme: theme,
        mode: mode,
        display: display,
        timeFormat: 'HH:ii',
        timeOrder: 'HHii',
        timeWheels: 'HHii',
        lang: lang
    });

    $('#confirm_order').click(function () {
        var data = {};
        data['en_name'] = $('input[name="cate_name_en"]').val();
        data['cn_name'] = $('input[name="cate_name_cn"]').val();
        data['sort'] = $('input[name="sort"]').val();
        data['is_weekshow'] = $('select[name="is_weekshow"] option:selected').val();
        var week_num = '';
        var i=0;
        $('#main').find('input[name="week[]"]').each(function () {
           if($(this).prop("checked")){
               if(week_num == '')
                   week_num += $(this).val();
               else
                   week_num += ','+$(this).val();
           }
           i++;
        });
        data['week'] = week_num;
        data['is_time'] = $('select[name="is_time"] option:selected').val();
        data['begin_time'] = $('input[name="begin_time"]').val();
        data['end_time'] = $('input[name="end_time"]').val();
        data['sort_id'] = "{pigcms{$sort.sort_id}";

        layer.open({
            type:2,
            content:'Loading...'
        });
        $.post("{pigcms{:U('Storestaff/category_setting')}",data,function(result) {
            if(result.error == 0){
                layer.closeAll();
                layer.open({
                    content: "Success",
                    type:2,
                    time:1,
                    end:function () {
                        window.location.href = "{pigcms{:U('Storestaff/manage_product')}";
                    }
                });
            }
        },'JSON');
    });
</script>
</body>
</html>
