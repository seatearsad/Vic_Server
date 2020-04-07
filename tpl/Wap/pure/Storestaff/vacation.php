<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>
        Account Management
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
        font-size: 14px;
        width: 100%;
        box-sizing: border-box;
    }
</style>
<body>
<include file="header" />
<div id="main">
    <div class="v_mode_title">
        <div style="flex: 1 1 100%;line-height: 30px">Vacation Mode</div>
        <div class="turn_btn">
            <span class="turn_on">ON</span>
            <span class="turn_off">OFF</span>
        </div>
    </div>
    <div id="reason">
        <div style="margin-top: 30px">
            Please choose a reason *
        </div>
        <div class="reason_div">
            <span>
                <input type="radio" name="reason" value="1">
            </span>
            <span>
                My store will be temporarily closed for holidays/vacations.
            </span>
        </div>
        <div class="reason_div">
            <span>
                <input type="radio" name="reason" value="2">
            </span>
            <span>
                I don't get many orders.
            </span>
        </div>
        <div class="reason_div">
            <span>
                <input type="radio" name="reason" value="3">
            </span>
            <span>
                Other : <input type="text" id="reason_input" name="reason_other" />
            </span>
        </div>
    </div>
    <div style="margin-top: 30px">
        By turning on the vacation mode, your store will be closed on Tutti platform. If you set a re-open date, it will automatically open on the designated date. If you choose "Manually Re-open, you will turn the vacation mode "OFF" to have your store back to open.
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    var store_status = "{pigcms{$store.status}";
    show_status();
    function show_status(){
        if(store_status == 1){
            $('.turn_on').show();
            $('.turn_off').hide();
            $('.turn_btn').css('background','#ffa52d');
            $('#reason').show();
        }else{
            $('.turn_on').hide();
            $('.turn_off').show();
            $('.turn_btn').css('background','#cccccc');
            $('#reason').hide();
        }
    }
    
    $('.turn_btn').click(function () {
        if(store_status == 1){
            var data = {};
            if(typeof($('input:radio[name="reason"]:checked').val()) == 'undefined'){
                alert('Please choose a reason');
                return false;
            }else{
                data['reason_type'] = $('input:radio[name="reason"]:checked').val();
                if($('input:radio[name="reason"]:checked').val() == 3){
                    if(typeof ($('input[name="reason_other"]').val()) == 'undefined' || $('input[name="reason_other"]').val() == ''){
                        alert('Please input other reason');
                        return false;
                    }else {
                        //alert($('input[name="reason_other"]').val());
                        data['reason_other'] = $('input[name="reason_other"]').val();
                    }
                }
            }
            layer.open({
                title:"{pigcms{:L('_STORE_REMIND_')}",
                content:"{pigcms{:L('_STORE_HOLIDAY_TIP_')}",
                btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
                yes: function(index){
                    layer.close(index);
                    $.post("{pigcms{:U('Storestaff/manage_holiday')}",data,function(result){
                        layer.open({
                            title:"{pigcms{:L('_STORE_REMIND_')}",
                            content:result.info,
                            time: 1,
                            end:function () {
                                store_status = store_status == 1 ? 0 : 1;
                                show_status();
                            }
                        });

                    });
                }
            });
        }else{
            $.post("{pigcms{:U('Storestaff/manage_holiday')}",function(result){
                layer.open({
                    title:"{pigcms{:L('_STORE_REMIND_')}",
                    content:result.info,
                    time: 1,
                    end:function () {
                        store_status = store_status == 1 ? 0 : 1;
                        show_status();
                    }
                });

            });
        }
    });
</script>
</body>
</html>
