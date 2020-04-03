<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Account Management</title>
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
        color: #666666;
        line-height: 16px;
        display: inline-block;
        font-size: 14px;
        width: 100%;
        box-sizing: border-box;
    }
    .input_title{
        font-weight: normal;
    }
    .input_title label,.item_show{
        font-weight: bold;
    }
    #product_img{
        margin-top: 5px;
    }
    .to_btn{
        text-align: left;
        padding-left: 15px;
        line-height: 50px;
        box-sizing: border-box;
    }
</style>
<body>
<include file="header" />
<div id="main">
    <div id="base">
        <div class="order_input" style="margin-bottom: 20px;line-height: 30px;">
            <div class="input_title">
                <label>
                    Store Status:
                    <if condition="$store['store_is_close'] eq 0">
                        Active
                        <else />
                        Inactive
                    </if>
                </label>
                <if condition="$store['store_is_close'] eq 0">
                    <span class="status_btn to_close">
                        Pause My Store
                    </span>
                    <else />
                    <span class="status_btn">
                        Resume My Store
                    </span>
                </if>
            </div>
        </div>
        <div class="edit_info">Edit</div>
        <div class="order_input">
            <div class="input_title">
                Store Name (English):<label>{pigcms{$store.en_name}</label>
            </div>
        </div>
        <div class="order_input">
            <div class="input_title">
                Store Name (Mandarin):<label>{pigcms{$store.cn_name}</label>
            </div>
        </div>
        <div class="order_input">
            <div class="input_title">
                Store Phone Number:<label>{pigcms{$store.phone}</label>
            </div>
        </div>
        <div class="order_input">
            <div class="input_title">
                Email Address:<label>{pigcms{$store.email}</label>
            </div>
        </div>
        <div class="order_input">
            <div class="input_title">
                Logo/Image:
            </div>
            <div id="product_img">
                <img src="{pigcms{$store.image}" width="200" />
            </div>
        </div>
        <div class="order_input">
            <div class="input_title">
                Description:
            </div>
            <div class="item_show">{pigcms{$store.txt_info}</div>
        </div>
        <div class="order_input">
            <div class="input_title">
                Keywords:
            </div>
            <div class="item_show">{pigcms{$store.feature}</div>
        </div>
    </div>

    <div class="to_btn" id="manage_time" style="margin-top: 30px;">
        Manage Delivery Hours
    </div>
    <div class="to_btn" id="manage_pwd" style="margin-top: 10px;">
        Change My Password
    </div>
    <div class="to_btn" id="manage_mode" style="margin-top: 10px;">
        Vacation Mode
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
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
    var is_close = '{pigcms{$store.is_close}';
    $('.status_btn').click(function () {
        if(is_close == 0){//操作 关闭店铺
            layer.open({
                title:"{pigcms{:L('_STORE_REMIND_')}",
                content:"{pigcms{:L('_STORE_CLOSE_TIP_')}",
                btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
                yes: function(index){
                    layer.close(index);
                    $.post("{pigcms{:U('Storestaff/manage_open_close')}",{open_close:0},function(result){
                        layer.open({
                            title:"{pigcms{:L('_STORE_REMIND_')}",
                            content:result.info,
                            time: 1,
                            end:function () {
                                window.location.reload();
                            }
                        });

                    });
                }
            });
        }else{//操作 打开店铺
            $.post("{pigcms{:U('Storestaff/manage_open_close')}",{open_close:1},function(result){
                if(result.status == 1) {
                    layer.open({
                        title: "{pigcms{:L('_STORE_REMIND_')}",
                        content: result.info,
                        time: 1,
                        end: function () {
                            window.location.reload();
                        }
                    });
                }else{
                    layer.open({
                        title: "{pigcms{:L('_STORE_REMIND_')}",
                        content: result.info,
                    });
                }

            });
        }
    });
    
    $('.edit_info').click(function () {
        window.location.href = "{pigcms{:U('Storestaff/manage_info')}&edit=1";
    });
</script>
</body>
</html>
