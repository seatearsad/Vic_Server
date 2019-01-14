<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{pigcms{:L('_CONFIRM_ORDER_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name='apple-touch-fullscreen' content='yes'>
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/weixin_pay.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
</head>
<body>


<?php if($is_app_browser && in_array($app_browser_type,array('android','ios')) && ($_REQUEST['app_version'] ? $_SESSION['app_version'] : $_SESSION['app_version']  || $app_version >= 60) ){ ?>
    <script type="text/javascript">
        <if condition="$app_browser_type eq 'android'">
            window.lifepasslogin.payCheck("{pigcms{$order_info['order_type']}","{pigcms{$order_info['order_id']}");
        layer.open({type: 2});
        function ReturnLastPay(){
            history.back();
        }
        function ReturnLastPage(){
            history.back();
        }
        <else/>
        <if condition="$app_version egt 60">
            $('body').append('<iframe src="pigcmso2o://gopay/{pigcms{$order_info.order_type}/{pigcms{$order_info.order_id}" style="display:none"></iframe>');
        <else/>
        $('body').append('<iframe src="pigcmso2o://gopay/<?php $arr=array('type'=>$order_info['order_type'],'order_id'=>$order_info['order_id']); echo base64_encode(json_encode($arr)); ?>" style="display:none"></iframe>');
        </if>
        function payCheck(){
            window.location.reload();
        }
        </if>
    </script>
<?php }else{ ?>
    <script type="text/javascript">
        var  now_money = Number("{pigcms{$now_user.now_money}");
        var  order_type = '{pigcms{$_GET['type']}';
        var  merchant_money = Number("{pigcms{$merchant_balance}");
        var  wx_cheap =Number("<?php if($cheap_info['can_cheap']){ ?>{pigcms{$cheap_info.wx_cheap}<?php }else{?>0<?php }?>");
        var  extra_price =Number("{pigcms{$order_info.extra_price}");
        var  open_extra_price =Number("{pigcms{$config.open_extra_price}");
        var  score_get_percent =Number("{pigcms{$config.user_score_get}");
        var  extra_price_name ="{pigcms{$config.extra_price_alias_name}";
        var  order_extra_price =Number("{pigcms{$order_info.order_extra_price}");
        var  score_count = Number("{pigcms{$score_count}");
        var  score_deducte = Number("{pigcms{$score_deducte}");
        var  score_percent = Number("{pigcms{$config.user_score_use_percent}");
        var  score_can_use_count = Number("{pigcms{$score_can_use_count}");
        var  merc_price = Number("<?php if($card_coupon['discount']>0){ ?>{pigcms{$card_coupon.discount}<?php }else{?>0<?php }?>");
        var  sysc_price = Number("<?php if($system_coupon['discount']>0){ ?>{pigcms{$system_coupon.discount}<?php }else{?>0<?php }?>");
        var  total_money = Number("{pigcms{$order_info.order_total_money}");
        var  card_discount = Number("<?php if($card_info['discount']){ ?>{pigcms{$card_info.discount}<?php }else{?>10<?php }?>");
        if(order_type=='shop'||order_type=='mall'){
            var freigh_money = Number("{pigcms{$order_info.freight_charge}");
            total_money = (total_money-freigh_money)*card_discount/10+freigh_money-wx_cheap-merc_price;
        }else{
            total_money = total_money*card_discount/10-wx_cheap-merc_price;
        }
        total_money+=order_extra_price;
        var  need_pay;
        var mer_coupon_html;
        var system_coupon_html;
        var sysc_price_tmp = sysc_price;
        var merc_price_tmp = merc_price;
        $(document).ready(function() {
            mer_coupon_html = $('#mer_coupon').html();
            system_coupon_html = $('#system_coupon').html();
            check_money(total_money,sysc_price,merchant_money);
            //点击事件
            $("#use_score,#use_balance,#use_merchant_money").bind("click", function () {

                check_money(total_money,sysc_price,merchant_money);
                if($("#use_score").is(':checked')==true){
                    $('#score_money_title').css('display','block');
                    $('#score_money_title .pay-wrapper').html('使用平台{pigcms{$config['score_name']}抵扣'+score_deducte+'元');
                }else{
                    $('#score_money_title').css('display','none');
                }
                isShowCredit();
            });

            $("form").submit(function() {
                $("button.mj-submit").attr("disabled", "disabled");
                $("button.mj-submit").html("正在处理...");
            });
            $('#pay-methods-panel').find('.list .dd-padding').click(function(){
                //garfunkel 线下支付不能使用优惠券 暂时屏蔽掉
                // if($(this).find('input').val()=='offline'){
                //     //window.location.href=window.location.href+'&pay_type=offline';
                //     var mer_coupon_none = "<a class='react' ><div class='more more-weak'><h6>{pigcms{:L('_SHOP_COUP_')}</h6><span class='more-after'>{pigcms{:L('_UNAVAILABLE_COUP_')}</span></div></a>";
                //     var system_coupon_none = "<a class='react' ><div class='more more-weak'><h6>{pigcms{:L('_PLATFORM_COUP_')}</h6><span class='more-after'>{pigcms{:L('_UNAVAILABLE_COUP_')}</span></div></a>";
                //     $('#system_coupon').html(system_coupon_none);
                //     $('#mer_coupon').html(mer_coupon_none);
                //
                //     check_money(total_money,0,0);
                //     sysc_price = 0;
                //     merc_price = 0;
                //     $('#pay_in_fact').html("{pigcms{:L('_ACTUAL_PAYMENT_')}：<b style='color:red'>$"+(total_money+merc_price).toFixed(2)+"</b>");
                //     $('input[name="card_id"]').attr('disabled','disabled');
                //     $('input[name="coupon_id"]').attr('disabled','disabled');
                // }else{
                //     $('#system_coupon').html(system_coupon_html);
                //     $('#mer_coupon').html(mer_coupon_html);
                //     $('#pay_in_fact').html("{pigcms{:L('_ACTUAL_PAYMENT_')}：<b style='color:red'>$"+(total_money-sysc_price).toFixed(2)+"</b>");
                //     $('input[name="card_id"]').removeAttr('disabled');
                //     $('input[name="coupon_id"]').removeAttr('disabled');
                //     sysc_price = sysc_price_tmp ;
                //     merc_price = merc_price_tmp;
                // }
            })

            // if($('#pay-methods-panel').find('.list .dd-padding input').val()=='offline'){
            //
            //     //window.location.href=window.location.href+'&pay_type=offline';
            //     var mer_coupon_none = "<a class='react' ><div class='more more-weak'><h6>{pigcms{:L('_SHOP_COUP_')}</h6><span class='more-after'>{pigcms{:L('_UNAVAILABLE_COUP_')}</span></div></a>";
            //     var system_coupon_none = "<a class='react' ><div class='more more-weak'><h6>{pigcms{:L('_PLATFORM_COUP_')}</h6><span class='more-after'>{pigcms{:L('_UNAVAILABLE_COUP_')}</span></div></a>";
            //     $('#system_coupon').html(system_coupon_none);
            //     $('#mer_coupon').html(mer_coupon_none);
            //
            //     check_money(total_money,0,0);
            //     sysc_price = 0;
            //     merc_price = 0;
            //     $('#pay_in_fact').html("{pigcms{:L('_ACTUAL_PAYMENT_')}：<b style='color:red'>$"+(total_money+merc_price).toFixed(2)+"</b>");
            //     $('input[name="card_id"]').attr('disabled','disabled');
            //     $('input[name="coupon_id"]').attr('disabled','disabled');
            // }else{
            //     $('#system_coupon').html(system_coupon_html);
            //     $('#mer_coupon').html(mer_coupon_html);
            //     $('#pay_in_fact').html("{pigcms{:L('_ACTUAL_PAYMENT_')}：<b style='color:red'>$"+(total_money-sysc_price).toFixed(2)+"</b>");
            //     $('input[name="card_id"]').removeAttr('disabled');
            //     $('input[name="coupon_id"]').removeAttr('disabled');
            //     sysc_price = sysc_price_tmp ;
            //     merc_price = merc_price_tmp;
            // }


            $('#alter_score').click(function(){

                $('#change_score').show();
                $('#pwd_bg').show();

            });
            $('.cancle').click(function(){
                $('#pwd_bg').hide();
                $('.pwd_verify').hide();
            })

            var score_money_sure = 0;
            $('.jia').click(function(){
                var score_change = $('input[name="score_change"]').val();
                if(score_can_use_count>=(Number(score_change)+1)){
                    var score_counts = Number(score_change)+1;
                }else{
                    var score_counts = score_can_use_count;
                }
                $('input[name="score_change"]').val(score_counts.toFixed(2));
                score_money_sure = (score_counts/score_percent).toFixed(2);
                $('.verify_lspan').html('$'+score_money_sure);
            });
            $('.jian').click(function(){
                var score_change = $('input[name="score_change"]').val();
                if(Number(score_change)-1>=0){
                    var score_counts = Number(score_change)-1;
                }else{
                    var score_counts = 0;
                }
                $('input[name="score_change"]').val(score_counts.toFixed(2));
                score_money_sure = (score_counts/score_percent).toFixed(2);
                $('.verify_lspan').html('$'+score_money_sure);
            });

            $('#score_sure').click(function(){
                $('#pwd_bg').hide();
                $('.pwd_verify').hide();
                var score_count_sure = $('input[name="score_change"]').val();
                score_deducte = score_money_sure;
                $('input[name="score_used_count"]').val(score_count_sure);
                $('input[name="score_deducte"]').val(score_deducte);
                $('#score_label').html('使用<font color="red">'+score_count_sure+'</font>个'+extra_price_name+'，抵扣：<font color="red">'+score_money_sure+'</font>元 ');
                $('#use_score').removeAttr('checked');
                $('#use_balance').removeAttr('checked');
                show_money(1,1,1,1,total_money);

            });

            $('#score_value').blur(function(){
                if(isNaN($(this).val())|| Number($(this).val())<0){
                    alert('非法输入');
                    window.location.reload();
                }
                if(Number($(this).val())>score_can_use_count){
                    $('#change_score .tips').html('最多使用'+score_can_use_count+'个'+extra_price_name);
                    $('input[name="score_change"]').val(score_can_use_count);
                }else{
                    var changed_score = $(this).val();
                    score_money_sure = (changed_score/score_percent).toFixed(2);
                    $('.verify_lspan').html('$'+score_money_sure);
                    $('input[name="score_change"]').val(Number(changed_score).toFixed(2));
                    $('#change_score .tips').html('');
                }
            });

        });

        //显示
        function show_money(use_merchant_money,use_score,use_balance,pay_title,money){
            if($('#use_merchant_money').is(':checked')==true){
                $("input[name='use_merchant_balance']").attr('value',1);
            }else{
                $("input[name='use_merchant_balance']").attr('value',0);
            }

            if($("#use_balance").is(':checked')==true&&use_balance){
                $("input[name='use_balance']").attr('value',1);
            }else{
                $("input[name='use_balance']").attr('value',0);
            }

            if($("#use_score").is(':checked')==true){
                var score_money = score_deducte;
                $("input[name='use_score']").attr('value',1);
            }else{
                var score_money = 0;
                $("input[name='use_score']").attr('value',0);
            }

            if(money==0){
                $('#need_pay_title').css('display','none');
            }else{
                $('#need_pay_title').css('display','block');
            }
            if(!pay_title){ //弹出来的支付方式
                $('#system_coupon').html(system_coupon_html);
                $('#mer_coupon').html(mer_coupon_html);
                //$('#balanceBox').css('margin-bottom','+60px');
                // $('#normal-fieldset').css('display','none');
                // $('#normal-fieldset input[name="pay_type"]').removeAttr('checked');
            }else{
                $('#balanceBox').css('margin-bottom','0px');
                if($('#normal-fieldset').css('display')=='none'){
                    //$('#normal-fieldset input[name="pay_type"]:first').attr('checked','checked');
                }
                $('#normal-fieldset').css('display','block');
            }
            if(!use_merchant_money){
                $('#merchant_money').css('color','#C1B9B9');
                $('#use_merchant_money').removeAttr('checked');
                $('#use_merchant_money').attr('disabled','disabled');
                $("input[name='use_merchant_balance']").attr('value',0);
            }else{
                if(merchant_money>0){
                    $('#merchant_money').css('color','#666666');
                    $('#use_merchant_money').removeAttr('disabled');
                }else{
                    $("input[name='use_merchant_balance']").attr('value',0);
                }
            }
            if(!use_score){
                $("input[name='use_score']").attr('value',0);
                $('#score_money').css('color','#C1B9B9');
                $('#use_score').removeAttr('checked');
                $('#use_score').attr('disabled','disabled');

            }else{
                if(score_deducte>0){
                    $('#score_money').css('color','#666666');
                    $('#use_score').removeAttr('disabled');
                }else{
                    $('.mt .score_money').css('display','none');
                    $("input[name='use_score']").attr('value',0);
                }
            }
            if(!use_balance){
                $("input[name='use_balance']").attr('value',0);
                $('#balance_money').css('color','#C1B9B9');
                $('#use_balance').removeAttr('checked');
                $('#use_balance').attr('disabled','disabled');
            }else{
                if(now_money>0){
                    $('#balance_money').css('color','#666666');
                    $('#use_balance').removeAttr('disabled');
                }else{
                    $("input[name='use_balance']").attr('value',0);
                }
            }
            $('.need-pay').html(money.toFixed(2))
            var extra_price_str = '';

            if(total_money-score_money-sysc_price<0){
                $('#pay_in_fact').html('实付款 0 元'+extra_price_str);
            }else{
                if(open_extra_price==1&&score_money>0){
                    extra_price_str = $('input[name="score_change"]').val()+'元宝';
                    $('#pay_in_fact').html('{pigcms{:L("_ACTUAL_PAYMENT_")}：<b style="color:red">$'+(total_money-sysc_price-score_money).toFixed(2)+'+'+extra_price_str+'</b>');
                    $('input[name="charge_total"]').val((total_money-sysc_price-score_money).toFixed(2));
                }else{
                    $('#pay_in_fact').html('{pigcms{:L("_ACTUAL_PAYMENT_")}：<b style="color:red">$'+(total_money-sysc_price).toFixed(2)+'</b>');
                    $('input[name="charge_total"]').val((total_money-sysc_price).toFixed(2));
                }
            }
            var pay_score = 0
            if($("#use_score").is(':checked')==true){

                var pay_score = $('input[name="score_change"]').val();
            }
            get_score(pay_score);
        }

        function get_score(pay_score){
            var give_score = 0;
            if(pay_score==extra_price){
                give_score =  0;
            }else if(order_type=='cash'||order_type=='store'){
                give_score =(total_money*score_get_percent.toFixed(2));

            }else if(pay_score==0&&extra_price>0){
                give_score =(total_money*score_get_percent.toFixed(2));

            }else if(pay_score<extra_price){
                give_score = extra_price-pay_score;
            }

            $('#give_score').html('可获得'+give_score+extra_price_name)

        }

        function check_money(total_money,sysc_price,merchant_money){
            if(total_money>0){
                if($('#use_merchant_money').is(':checked')==true){   								//使用商家余额

                    $("input[name='use_merchant_balance']").attr('value',1);
                    if(sysc_price>0){
                        var total_money_tmp = total_money-sysc_price;


                        if($("#use_balance").is(':checked')==true){
                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }
                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{
                                if(merchant_money>=total_money_tmp){
                                    show_money(1,1,0,0,0);                // 显示 {pigcms{$config['score_name']}，余额，支付方式
                                }else if(now_money>=total_money_tmp-merchant_money){
                                    show_money(1,1,1,0,0);                //
                                }else if(now_money==0){
                                    show_money(1,1,0,1,total_money_tmp-merchant_money);
                                }else{
                                    show_money(1,1,1,1,total_money_tmp-merchant_money-now_money);
                                }
                            }
                        }else{

                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }
                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{
                                if(merchant_money>=total_money_tmp){
                                    show_money(1,1,0,0,0);
                                }else{
                                    if(now_money==0){
                                        show_money(1,1,0,1,total_money_tmp-merchant_money);
                                    }else{
                                        show_money(1,1,1,1,total_money_tmp-merchant_money);
                                    }
                                }
                            }
                        }
                        if(total_money_tmp<=0){
                            show_money(0,0,0,0,0);
                        }

                    }else{  																			//不使用系统优惠券

                        var total_money_tmp = total_money;

                        if($("#use_balance").is(':checked')==true){
                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }

                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{
                                if(merchant_money>=total_money_tmp){
                                    show_money(1,1,0,0,0);
                                }else if(now_money>=total_money_tmp-merchant_money){

                                    show_money(1,1,1,0,0);
                                }else if(now_money==0){
                                    show_money(1,1,0,1,total_money_tmp-merchant_money);
                                }else{
                                    show_money(1,1,1,1,total_money_tmp-merchant_money-now_money);
                                }
                            }
                        }else{
                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }
                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{

                                if(merchant_money>=total_money_tmp){
                                    show_money(1,1,0,0,0);
                                }else{
                                    if(now_money==0){
                                        show_money(1,1,0,1,total_money_tmp-merchant_money);
                                    }else{
                                        show_money(1,1,1,1,total_money_tmp-merchant_money);
                                    }
                                }
                            }
                        }
                    }
                }else{  			//不使用商家余额
                    $("input[name='use_merchant_balance']").attr('value',0);
                    if(sysc_price>0){  				//使用系统优惠券
                        var total_money_tmp = total_money-sysc_price;

                        if($("#use_balance").is(':checked')==true){

                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }
                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{
                                if(now_money>=total_money_tmp){
                                    show_money(1,1,1,0,0);
                                }else if(now_money==0){
                                    show_money(1,1,0,1,total_money_tmp);
                                }else{
                                    show_money(1,1,1,1,total_money_tmp-now_money);
                                }
                            }
                        }else{
                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }
                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{

                                if(now_money==0){
                                    show_money(1,1,0,1,total_money_tmp);
                                }else{
                                    show_money(1,1,1,1,total_money_tmp);
                                }
                            }
                        }
                        if(total_money_tmp<=0){
                            show_money(0,0,0,0,0);
                        }
                    }else{   																		//不使用系统优惠券
                        var total_money_tmp = total_money;

                        if($("#use_balance").is(':checked')==true){
                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }
                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{
                                if(now_money>=total_money_tmp){
                                    show_money(0,1,1,0,0);
                                }else if(now_money==0){
                                    show_money(1,1,0,1,total_money_tmp);
                                }else{
                                    show_money(1,1,1,1,total_money_tmp-now_money);
                                }
                            }
                        }else{

                            if($("#use_score").is(':checked')==true){
                                total_money_tmp -= score_deducte;
                            }
                            if(total_money_tmp==0){
                                show_money(0,1,0,0,0);
                            }else{
                                if(now_money==0){

                                    show_money(1,1,0,1,total_money_tmp);
                                }else{
                                    show_money(1,1,1,1,total_money_tmp);
                                }
                            }
                        }
                    }
                }
            }else{
                show_money(0,0,0,0,0); //积分，商家余额，余额都隐藏
            }
        }

        <if condition="$config['twice_verify']">var twice_verify = true;<else />var twice_verify = false;</if>
        <if condition="$_SESSION['user']['verify_end_time']">var verify_end_time = {pigcms{$_SESSION['user']['verify_end_time']};</if>
    </script>
    <script language="javascript">
        function bio_verify(){
            layer.open({type:2,content:"{pigcms{:L('_LOADING_TXT_')}",shadeClose:false});
            var pay_type = $('input[name="pay_type"]:checked').val();
            $("button.mj-submit").attr("disabled", "disabled");
            $("button.mj-submit").html("正在处理...");
            var use_score= $("input[name='use_score']").val();
            var use_balance= $("input[name='use_balance']").val();
            //var  merchant_money = Number("{pigcms{$merchant_balance}");
            if(twice_verify&&(merchant_money!=0||use_balance==1||use_score==1)){
                if(typeof(wxSdkLoad) != "undefined"){
                    wx.invoke('getSupportSoter', {}, function (res) {
                        if(res.support_mode=='0x01'){
                            wx.invoke('requireSoterBiometricAuthentication', {
                                auth_mode: '0x01',
                                challenge: 'test',
                                auth_content: '请将指纹验证'  //指纹弹窗提示
                            }, function (res) {
                                if(res.err_code==0&&pay_type=='weixin'){
                                    callpay();
                                }else if(res.err_code==0){
                                    layer.closeAll();
                                    $('#pay-form').submit();
                                }else if (res.err_code==90009){
                                    layer.closeAll();
                                    $('#pwd_bg').css('display','block');
                                    $('#pwd_verify').css('display','block');
                                }else{
                                    alert(res.err_code);
                                    $("button.mj-submit").removeAttr("disabled");
                                    $("button.mj-submit").html("确认支付");
                                }
                            })
                        }else{
                            // 密码验证
                            layer.closeAll();
                            $('#pwd_bg').css('display','block')
                            $('#pwd_verify').css('display','block')
                        }
                    })
                }else{
                    layer.closeAll();
                    $('#pwd_bg').css('display','block');
                    $('#pwd_verify').css('display','block');
                }

            }else{//garfunkel add
                if(pay_type == 'moneris'){
                    var card_type = $('input[name="pay_card_type"]:checked').val();
                    if(card_type == 1){
                        if(check_card()){
                            // alert($('input[name="save"]:checked').val());
                            var re_data = {
                                'name':$('#card_name').val(),
                                'card_num':$('#card_num').val(),
                                'expiry':$('#expiry').val(),
                                'save':$('input[name="save"]:checked').val(),
                                // 'charge_total':$('input[name="charge_total"]').val(),
                                'charge_total':$('#add_tip').text().replace('$', ""),
                                'order_id':"Tutti{pigcms{$order_info.order_type}_{pigcms{$order_info.order_id}",
                                'cust_id':'{pigcms{:md5($order_info.uid)}',
                                'rvarwap':$('input[name="rvarwap"]').val(),
                                'coupon_id':$('input[name="coupon_id"]').val(),
                                'tip':$('#tip_num').text().replace('$', ""),
                                'order_type':"{pigcms{$order_info.order_type}"
                            };

                            //alert(re_data['order_type']);
                            $.post($('#moneris_form').attr('action'),re_data,function(data){
                                layer.closeAll();
                                layer.open({title:['Message'],content:data.info});
                                if(data.status == 1){
                                    setTimeout("window.location.href = '"+data.url+"'",200);
                                }
                            });
                        }else{
                            alert("{pigcms{:L('_PLEASE_RIGHT_CARD_')}");
                            layer.closeAll();
                            $("html,body").animate({"scrollTop":$('#credit').offset().top},900);
                        }
                    }else{
                        if($('input[name="credit_id"]').val()){
                            var re_data = {
                                'credit_id':$('input[name="credit_id"]').val(),
                                // 'charge_total':$('input[name="charge_total"]').val(),
                                'charge_total':$('#add_tip').text().replace('$', ""),
                                'order_id':"vicisland{pigcms{$order_info.order_type}_{pigcms{$order_info.order_id}",
                                'cust_id':'{pigcms{:md5($order_info.uid)}',
                                'rvarwap':$('input[name="rvarwap"]').val(),
                                'coupon_id':$('input[name="coupon_id"]').val(),
                                'tip':$('#tip_num').text().replace('$', ""),
                                'order_type':"{pigcms{$order_info.order_type}"
                            };
                            //alert(re_data['order_type']);
                            $.post($('#moneris_form').attr('action'),re_data,function(data){
                                layer.closeAll();
                                layer.open({title:['Message'],content:data.info});
                                if(data.status == 1){
                                    setTimeout("window.location.href = '"+data.url+"'",200);
                                }else{

                                }
                            });
                        }else{
                            alert("{pigcms{:L('_PLEASE_ADD_CARD_')}");
                            layer.closeAll();
                            $("html,body").animate({"scrollTop":$('#credit').offset().top},900);
                        }
                    }

                    // $.ajax({
                    //     url:"{pigcms{:U('Pay/getPayMessage')}",
                    //     type:'post',
                    //     data:{pay_type:pay_type,key_list:"ps_store_id|hpp_key"},
                    //     dataType:"json",
                    //     success:function(data){
                    //         $('input[name="ps_store_id"]').val(data['ps_store_id']);
                    //         $('input[name="hpp_key"]').val(data['hpp_key']);
                    //
                    //         $('#moneris_form').submit();
                    //     }
                    // });
                }else if(pay_type == 'weixin' || pay_type == 'alipay'){
                    var re_data = {
                        'charge_total':$('#add_tip').text().replace('$', ""),
                        'order_id':"vicisland{pigcms{$order_info.order_type}_{pigcms{$order_info.order_id}",
                        'cust_id':'{pigcms{:md5($order_info.uid)}',
                        'rvarwap':$('input[name="rvarwap"]').val(),
                        'coupon_id':$('input[name="coupon_id"]').val(),
                        'tip':$('#tip_num').text().replace('$', ""),
                        'order_type':"{pigcms{$order_info.order_type}",
                        'pay_type':pay_type
                    };
                    $.post('{pigcms{:U("Pay/WeixinAndAli")}',re_data,function(data){
                        layer.closeAll();
                        //success
                        if(data.status == 1){
                            if(pay_type == 'alipay')
                                $('body').html(data.url);
                            else
                                window.location.href = data.url;
                        }else{
                            layer.open({title:['Message'],content:data.info});
                        }

                    },'json');
                }else{
                    layer.closeAll();
                    var res = callpay();
                    if(res){
                        $('#pay-form').submit();
                    }
                }
            }
        }

        //微信弹程支付
        function callpay(){
            var pay_type = $('input[name="pay_type"]:checked').val();
            if(typeof(pay_type)!='undefined'){
                if(pay_type!='weixin'){
                    return true;
                }else if(pay_type=='weixin'){
                    var orderid_info = {pigcms{:json_encode($orderid_info)};
                    var pay_money = <?php if($order_info['order_type']=='recharge'){ ?> total_money<?php }else{ ?>need_pay<?php }?>;
                    var short_orderid = {pigcms{$order_info.order_id};
                    $("button.mj-submit").attr("disabled", "disabled");
                    $("button.mj-submit").html("正在处理...");
                    var param;
                    $.ajax({
                        url: "{pigcms{:U('Pay/go_pay')}",
                        type: 'POST',
                        dataType: 'json',
                        data: $('#pay-form').serialize(),
                        beforeSend: function(){
                            layer.open({type:2,content:'支付加载中',shadeClose:false});
                        },
                        success: function(date){
                            layer.closeAll();
                            if(date.error == 0){
                                param =  date.weixin_param;
                                WeixinJSBridge.invoke("getBrandWCPayRequest",param,function(res){
                                    WeixinJSBridge.log(res.err_msg);
                                    if(res.err_msg=="get_brand_wcpay_request:cancel"){
                                        window.location.reload();
                                    }else if(res.err_msg=="get_brand_wcpay_request:ok"){
                                        setTimeout("window.location.href = '"+date.redirctUrl+"'",200);
                                    }else{
                                        $("button.mj-submit").removeAttr("disabled");
                                        $("button.mj-submit").html("确认支付");
                                    }
                                });
                            }else{
                                layer.open({title:['调用微信支付发生错误：'],content:date.info});
                                $("button.mj-submit").removeAttr("disabled");
                                $("button.mj-submit").html("确认支付");
                            }
                        }
                    });
                    return false;
                }
                }else{
                        return true;
                    }
                }



    </script>
    <script>layer.open({type:2,content:'页面加载中',shadeClose:false});</script>
    <div id="tips" class="tips"></div>
<div class="wrapper-list">
    <dl class="list">
        <dd>
            <dl>
                <dd class="kv-line-r dd-padding">
                    <if condition="$order_info.order_type neq 'weidian'AND  $order_info.order_type neq 'store' AND  $order_info.order_type neq 'wxapp' AND $order_info['order_type'] neq 'recharge' AND $order_info['img']"><img src="{pigcms{$order_info.img}" style="width:80px;height:80px;"></if>
                    <div>
                        <p style="margin-left: 20px;">{pigcms{$order_info.order_name}</p>
                        <if condition="$order_info.order_price gt 0 && $order_info['order_num'] gt 0">
                            <p style="margin-left: 20px;margin-top: 10px;">$ {pigcms{$order_info.order_price}
                                <if condition="$order_info.is_head gt 0">(团长优惠)</if>
                                <if condition="$order_info.extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$order_info.extra_pay_price}{pigcms{$config.extra_price_alias_name}</if>
                                X {pigcms{$order_info.order_num}</p></if>
                        <if condition="$order_info.order_txt_type"><p style="margin-left: 20px;margin-top: 5px;">{pigcms{$order_info.order_txt_type}</p></if>

                    </div>
                </dd>
                <dd class="kv-line-r dd-padding">
                    <h6>{pigcms{:L('_ORDER_TOTAL_')}</h6><p><b style="color:red">${pigcms{$order_info.order_total_money}<if condition="$order_info.extra_price gt 0 AND $config.open_extra_price eq 1 ">+{pigcms{$order_info.extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if> </b></p>
                </dd>
            </dl>
        </dd>
    </dl>
    <if condition="$order_info['order_type'] != 'recharge' OR $order_info['order_type'] != 'weidian' ">
        <php>if($order_info['order_type']!='recharge' ){</php>
        <div id="balanceBox">
            <h4>{pigcms{:L('_CLEARING_INFO_')}</h4>
            <dl class="list">
                <dd>
                    <dl>
                        <if condition="$card_info AND $card_info.discount lt 10 AND $card_info.discount gt 0">
                            <dd class="kv-line-r dd-padding">
                                <h6>{pigcms{:L('_MEMBERSHIP_CARD_DIS_')}</h6><p  style="color:red;">{pigcms{:replace_lang_str('_NUM_DISCOUNT_',$card_info['discount'])}</p>
                            </dd>
                        </if>
                        <if condition="$cheap_info['can_cheap']">
                            <dd class="kv-line-r dd-padding">
                                <h6>{pigcms{:L('_WECHAT_DIS_')}</h6><p  style="color:red;">${pigcms{$cheap_info.wx_cheap}</p>
                            </dd>
                        </if>

                        <if condition="($_GET['type'] neq 'weidian' && $_GET['type'] neq 'plat')|| ($_GET['type'] == 'plat' && $order_info['pay_merchant_coupon'])">
                            <?php if(empty($notCard)){ ?>

                                <php>if($_GET['unmer_coupon'] && !$ban_mer_coupon){</php>


                                <dd id="mer_coupon">
                                    <a class="react" href="{pigcms{:U('My/select_card',($coupon_url?$coupon_url :$_GET))}&coupon_type=mer" >
                                        <div class="more more-weak">
                                            <h6>{pigcms{:L('_SHOP_COUP_')}</h6>
                                            <span class="more-after">{pigcms{:L('_DONT_USE_')}</span>
                                        </div>
                                    </a>
                                </dd>
                                <php>}else if(empty($card_coupon) || $ban_mer_coupon){</php>
                                <dd id="mer_coupon">
                                    <a class="react" >
                                        <div class="more more-weak">
                                            <h6>{pigcms{:L('_SHOP_COUP_')}</h6>
                                            <span class="more-after">{pigcms{:L('_UNAVAILABLE_COUP_')}</span>
                                        </div>
                                    </a>
                                </dd>
                                <php>}else{</php>
                                <dd id="mer_coupon">
                                    <a class="react" href="{pigcms{:U('My/select_card',($coupon_url ? $coupon_url :$_GET))}&coupon_type=mer">
                                        <div class="more more-weak">
                                            <h6>{pigcms{:L('_SHOP_COUP_')}</h6>
                                            <span class="more-after" style="color:red;"><?php if($card_coupon){ ?>满{pigcms{$card_coupon.order_money}减{pigcms{$card_coupon.discount}<?php }else{ ?>{pigcms{:L('_USE_COUP_')}<?php } ?></span>
                                        </div>
                                    </a>
                                </dd>
                                <php>}</php>
                            <?php } ?>
                        </if>
                        <if condition="($_GET['type'] neq 'weidian' && $_GET['type'] neq 'plat') || ($_GET['type'] == 'plat' && $order_info['pay_system_coupon'])">
                            <?php if(empty($notCard)){ ?>
                                <dd id="system_coupon">
                                    <php>if($_GET['unsys_coupon']){</php>
                                <dd id="mer_coupon">
                                    <a class="react" href="{pigcms{:U('My/select_card',($coupon_url?$coupon_url :$_GET))}&coupon_type=system" >
                                        <div class="more more-weak">
                                            <h6>{pigcms{:L('_PLATFORM_COUP_')}</h6>
                                            <span class="more-after">{pigcms{:L('_DONT_USE_')}</span>
                                        </div>
                                    </a>
                                </dd>
                                <php>}else if(empty($system_coupon)){</php>

                                <a class="react" >
                                    <div class="more more-weak">
                                        <h6>{pigcms{:L('_PLATFORM_COUP_')}</h6>
                                        <span class="more-after">{pigcms{:L('_UNAVAILABLE_COUP_')}</span>
                                    </div>
                                </a>
                            <?php }else{ ?>

                                <a class="react" href="{pigcms{:U('My/select_card',($coupon_url ? $coupon_url :$_GET))}&coupon_type=system">
                                    <div class="more more-weak">
                                        <h6>{pigcms{:L('_PLATFORM_COUP_')}</h6>
                                        <span class="more-after"  style="color:red;">
                                            <?php if($system_coupon){ ?>
                                                <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                                                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$system_coupon['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$system_coupon['discount'])}
                                                <php>}else{</php>
                                                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$system_coupon['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$system_coupon['order_money'])}
                                                <php>}</php>
                                            <?php }else{ ?>
                                                {pigcms{:L('_USE_COUP_')}
                                            <?php } ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                            </dd>
                            <?php } ?>
                        </if>
                        <if condition="$score_deducte gt 0">
                            <dd class="dd-padding" id="score_money">
                                <label class="mt" ><span id="score_label">{pigcms{:L('_TORDER_MEAL_TICKET_')} <php>if($config['open_extra_price']==1){</php>{pigcms{$config.extra_price_alias_name}<php>}else{</php> <php>}</php><font color="red">{pigcms{$score_can_use_count}</font>,{pigcms{:L('_MEAL_TICKET_DED_CASH_')} <font color="red">${pigcms{$score_deducte|floatval=###}</font></span> <span class="pay-wrapper"><input type="checkbox" class="mt" value="1" id="use_score" name="use_score"  <php>if($score_can_use_count==0){</php> disabled="disabled" value="1"<php>}else{</php>  value="0" checked="checked" <php>}</php>><p style="display:block;float:right;"></p></span></label><php> if($config['open_extra_price']==1){</php><a id="alter_score">修改</a><php>}</php>
                            </dd>
                        </if>
                        <?php if($merchant_balance){ ?>

                            <dd class="dd-padding" id="merchant_money" <if condition="$merchant_balance eq 0 OR $card_coupon.discount gt $order_info.order_total_money ">style="color: #C1B9B9;"</if>>
                            <label class="mt"><span class="pay-wrapper">使用商家会员卡余额支付<br>剩余<font color="red">${pigcms{$merchant_balance}</font><input type="checkbox" class="mt"  id="use_merchant_money" name="use_merchant_money" <if condition="$merchant_balance eq 0 OR $card_coupon.discount gt $order_info.order_total_money "> disabled="disabled" value="1"<else /> value="0" checked="checked" </if> ></span></label>
                            </dd>
                        <?php } ?>
                        <?php if($order_info['order_type'] != 'plat' || $order_info['pay_system_balance']){ ?>
                            <dd class="dd-padding" id="balance_money" <if condition="$now_user.now_money eq 0 OR $merchant_balance gt $order_info.order_total_money ">style="color: #C1B9B9;"</if>>
                            <label class="mt"><span class="pay-wrapper">{pigcms{:L('_USE_BALANCE_PAY_')}<br><font color="red">{pigcms{:L('_AVAILABLE_BALANCE_')} ${pigcms{$now_user.now_money}</font>
                                    <input type="checkbox" class="mt"  id="use_balance" name="use_balance"<if condition="$now_user['now_money'] eq 0 OR $merchant_balance gt $order_info['order_total_money'] ">disabled="disabled" value="1"<else /> value="0" checked="checked" </if>></span></label>
                            </dd>
                        <?php } ?>
                        <dd class="dd-padding">
                            <label class="mt">
                                <span style="float: right;" class="pay-wrapper">{pigcms{:L('_TAXATION_TXT_')}：<b style="color:red">${pigcms{$order_info['tax_price']}</b></span>
                            </label>
                        </dd>
                        <dd class="dd-padding" id="balance_money" >
                            <!--<label class="mt" id="score_money_title" style="display:none"><span class="pay-wrapper"></span></label>-->
                            <label class="mt">
                                <if condition="$config.open_extra_price eq 1"><span style="float: left;" class="pay-wrapper" id="give_score"></span></if>
                                <span style="float: right;" class="pay-wrapper" id="pay_in_fact"></span>
                                <if condition="$_GET['type'] eq 'shop'"><br><span  class="pay-wrapper" style="float: right;font-size:10px;color:#ccc9c9;margin-top:2px">({pigcms{:L('_NOTE_NOT_TAKE_DIS_')})</span></if></label>
                        </dd>
                    </dl>
                </dd>
            </dl>
        </div>
        <php>}</php>
    </if>

    <!-- garfunkel add moneris >
    <form action="https://www3.moneris.com/HPPDP/index.php" method="post" id="moneris_form" -->
    <form action="{pigcms{:U('Index/Pay/MonerisPay')}" method="post" id="moneris_form">
        <INPUT TYPE="HIDDEN" NAME="ps_store_id" VALUE="">
        <INPUT TYPE="HIDDEN" NAME="hpp_key" VALUE="">
        <INPUT TYPE="HIDDEN" NAME="charge_total" VALUE="">
        <input type="hidden" name="cust_id" value="{pigcms{:md5($order_info.uid)}">
        <input type="hidden" name="order_id" value="vicisland_{pigcms{$order_info.order_id}">
        <input type="hidden" name="rvarwap" value="1">
        <input type="hidden" name="credit_id" value="{pigcms{$card['id']}">
    </form>
    <form action="/source{pigcms{:U('Pay/go_pay',array('showwxpaytitle1'=>1))}" method="POST" id="pay-form" class="pay-form" >
        <input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
        <input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
        <input type="hidden" name="card_id" value="{pigcms{$card_coupon.id}"/>
        <input type="hidden" name="coupon_id" value="{pigcms{$system_coupon.id}"/>
        <input type="hidden" name="use_score" value="0"/>
        <input type="hidden" name="use_balance" <if condition="$order_info['order_type'] eq 'recharge' OR $now_user['now_money'] eq 0 OR  ($order_info['order_type'] eq 'plat' && !$order_info['pay_system_balance'])">value="1"<else /> value="0" </if>/>
        <input type="hidden" name="score_used_count" value="{pigcms{$score_can_use_count}">
        <input type="hidden" name="score_deducte" value="{pigcms{$score_deducte}">
        <input type="hidden" name="score_count" value="{pigcms{$score_count}">
        <input type="hidden" name="use_merchant_balance" <if condition="$order_info['order_type'] eq 'recharge' OR $merchant_blance eq 0 OR ($order_info['order_type'] eq 'plat' && !$order_info['pay_merchant_balance'])">value="1"<else /> value="0" </if>>
        <input type="hidden" name="merchant_balance" value="{pigcms{$merchant_balance}">
        <input type="hidden" name="balance_money" value="{pigcms{$now_user.now_money}">
        <input type="hidden" name="tip" value="">


        <div id="pay-methods-panel" class="pay-methods-panel">
            <div id="normal-fieldset" class="normal-fieldset" style="height: 100%;margin-bottom: 60px;" >
                <h4 style="margin: .3rem .2rem .2rem;">{pigcms{:L('_SELECT_PAY_MODE_')}</h4>
                <dl class="list">
                    <volist name="pay_method" id="vo">
                        <php>if($pay_offline || $key != 'offline'){</php>
                        <php>if(($key == 'weixin' && $is_wexin_browser) || ($key == 'alipay' && !$is_wexin_browser) || ($key != 'weixin' && $key!= 'alipay')){</php>
                        <dd class="dd-padding">
                            <label class="mt">
                                <!--i class="bank-icon icon-{pigcms{$key}"></i-->
                                <span class="pay-wrapper">
                                    <img src="{pigcms{$static_public}images/pay/{pigcms{$key}.png" style="height: 20px"/> {pigcms{:L($key)}
                                    <input type="radio" class="mt" value="{pigcms{$key}"  <php>if($key == 'moneris'){</php>checked="checked"<php>}</php> name="pay_type">
                                </span>
                            </label>
                        </dd>
                        <php>}</php>
                        <php>}</php>
                    </volist>
                </dl>
            </div>
            <if condition="$order_info['order_type'] != 'recharge'">
            <div id="tip_label" class="normal-fieldset" style="height: 100%;margin-top: -50px;margin-bottom: 60px;">
                <h4 style="margin: .3rem .2rem .2rem;">{pigcms{:L('_TIP_TXT_')}</h4>
                <dl class="list">
                    <dd class="dd-padding">
                        <div id="tip_list" style="margin: auto;width: 98%">
                            <span class="tip_s tip_on">
                                15%
                            </span>
                            <span class="tip_s">
                                20%
                            </span>
                                <span class="tip_s">
                                25%
                            </span>
                        </div>
                        <div style="margin: 20px auto 5px;width: 98%">
                            {pigcms{:L('_SELF_ENTER_TIP_')}: $ <input type="text" id="tip_fee" name="tip_fee" size="20" style="height: 25px;">
                        </div>
                        <div style="margin: 20px auto 5px;width: 98%;font-size: 16px;">
                            <span>{pigcms{:L('_TIP_TXT_')}:</span><span id="tip_num">$0</span>
                            <span style="color: #ff0000;">{pigcms{:L('_B_PURE_MY_70_')}:</span><span id="add_tip">$0</span>
                        </div>
                    </dd>
                </dl>
            </div>
            <else />
                <span id="tip_num" style="display: none;">$0</span>
                <span id="add_tip" style="display: none;">$0</span>
            </if>
            <div id="credit" class="normal-fieldset" style="height: 100%;display:none;margin-top: -50px; margin-bottom:60px;" >
                <h4 style="margin: .3rem .2rem .2rem;">{pigcms{:L('_CREDIT_CARD_')}</h4>
                <dl class="list">
                    <if condition="$card">
                    <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 15px;margin-left: .2rem">
                        <input type="radio" name="pay_card_type" value="0" class="mt" checked=checked> {pigcms{:L('_USE_OLD_CARD_')}
                    </div>
                    <a href="{pigcms{:U('My/credit',array('order_id'=>$order_info['order_id']))}">
                    <dd class="more dd-padding" style="border-bottom: 1px #cccccc solid;">
                        <label class="mt">
                            <span class="pay-wrapper">
                                   {pigcms{$card['name']} -- {pigcms{$card['card_num']}
                            </span>
                        </label>
                    </dd>
                    </a>
                    </if>
                    <dd class="dd-padding">
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 15px;">
                            <input type="radio" name="pay_card_type" value="1" class="mt" <if condition="!$card">checked=checked</if>> {pigcms{:L('_USE_NEW_CARD_')}
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_CREDITHOLDER_NAME_')}：</span>
                            <input type="text" maxlength="20" size="20" name="name" class="form-field" id="card_name" value="" style="float: left"/>
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_CREDIT_CARD_NUM_')}：</span>
                            <input type="text" maxlength="20" size="20" name="card_num" class="form-field" id="card_num" value="" style="float: left"/>
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_EXPRIRY_DATE_')}：</span>
                            <input type="text" maxlength="4" size="20" name="expiry" class="form-field" id="expiry" value="" style="float: left"/>
                        </div>
                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                            <span style="float: left;width:150px;">{pigcms{:L('_IS_SAVE_')}：</span>
                            <input type="checkbox" name="save" class="form-field" id="save" value="1" style="float: left;width:20px;height: 20px;"/>
                        </div>
                    </dd>
                </dl>
            </div>
            <div style="background-color: #FFFFFF; height: 53px;position: fixed;bottom: 0;left: 0;right: 0;z-index: 900;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);height: 49px;width: 100%;">
                <div id="need_pay_title" style="    position: absolute;margin-top: 18px;margin-left: 0.3rem;">
                    {pigcms{:L('_ALSO_NEED_PAY_')} <div style="font-weight:bold;color:red;display: inline;">$<div class="need-pay" style="display:inline;">
                        </div>
                    </div>
                </div>
                <button type="button" onclick="bio_verify()" style="float: right;height: 100%;width: 50%;background-color: #06c1ae;color: #fff;border: none;">{pigcms{:L('_CONFIRM_PAY_')}</button>
            </div>
        </div>
    </form>
</div>

<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
<div id="pwd_bg" style="height: 921px;" style="display:block">

</div>
<div id="pwd_verify" class="pwd_verify" style="display:none" >
    <div class="pwd_menu" >
        <span class="cancle"><img src="{pigcms{$static_path}images/twice_cancel.png"></span><p>密码验证</p>
    </div>
    <input type="hidden" id="pwd_type" name="type" value="1">
    <div class="verify_pwd">
        <p class="tips"></p>
        <input type="password"  autocomplete="off"  id="pwd" placeholder="输入登录密码" name="pwd" value="">
        <a id="forget_pwd" href="{pigcms{:U('Login/forgetpwd')}"><p class="forget_pwd">忘记密码?</p></a>
    </div>
    <div class="verify_sms" style="display:none;">
        <span style="color:#5E5E5E;font-size: 12px;">验证码将发送您手机：</span><span id="verify_phone" style="color:#006600;font-size: 12px;"></span>
        <input type="text" name="sms_code" autocomplete="off"  placeholder="输入验证码" value="">
        <button onclick="sendsms(this)">发送短信</button>
        <p></p>
    </div>
    <div class="verify_button" id="verify">
        <p>验证</p>
    </div>
</div>
<!-- 加 -->
<style type="text/css">
    .verify_jg{ display: inline-block; height: 27px; padding:50px 0; }
    .verify_l{ float: left; line-height: 27px; color: red }
    .verify_lspan{ font-size: 20px; float: left; }
    .verify_lspan1{margin: 0 5px; float: left;}
    .plus{ float: left; }
    .plus a{width: 30px;float: left;height: 25px;border: #e5e5e5 1px solid;text-align: center;color: #232326;font-size: 20px;line-height: 25px;font-family: "Arial";}
    .plus input{width: 80px;text-align: center;float: left;border: #e5e5e5 1px solid;border-left: none;border-right: none;height: 25px;font-size: 14px;border-radius: 0px;font-size: 16px;color: red;}
    .tip_s{width: 32%; height: 40px; border: 1px #999999 solid;line-height: 40px;text-align: center;font-size: 16px;display:-moz-inline-box;display:inline-block;cursor: pointer}
    .tip_on{background-color: #06c1ae;color: #ffffff;border-color:#06c1ae }
</style>

<div class="pwd_verify" style="display: none;" id="change_score">
    <div class="pwd_menu" >
        <span class="cancle"><img src="{pigcms{$static_path}images/twice_cancel.png"></span><p><php>if($config['open_extra_price']==1){</php>{pigcms{$config.extra_price_alias_name}<php>}else{</php>{pigcms{$config['score_name']}<php>}</php>修改</p>
    </div>
    <div class="verify_pwd">
        <p class="tips"></p>
        <div class="verify_jg">
            <div class="verify_l">
                <span class="verify_lspan">${pigcms{$score_deducte}</span>
                <span class="verify_lspan1">+</span>
            </div>
            <div class="plus">
                <a href="javascript:void(0)" class="jian">-</a>
                <input type="text" id="score_value" name="score_change" value="{pigcms{$score_can_use_count}" >
                <a href="javascript:void(0)" class="jia">+</a>
            </div>
        </div>
    </div>
    <div class="verify_button" id="score_sure" style="background-color: #06c1ae;color: #fff;">
        <p style="color: #fff;">确定</p>
    </div>
</div>
<!-- 加 -->
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script src="{pigcms{$static_path}js/bioauth_.js"></script>

<script>
    if($('#balanceBox dd dl dd').size() == 0){
        $('#balanceBox').hide();
    }
    layer.closeAll();
    var showBuyBtn = true;

    //garfunkel add
    $('input[name="pay_type"]').click(changePay);

    var isb = false;

    $(function(){
        if(parseFloat($('input[name="charge_total"]').val()) <= 20){
            isb = true;
        }
        var tipxn = new Array(3,4,5);
        var i = 0;
        $('#tip_list').children('span').each(function(){
            $(this).click(tip_select);
            if(isb){
                $(this).text('$' + tipxn[i]);
            }
            i++;
        });
        CalTip();
        isShowCredit();
    });
    //计算小费
    function CalTip(){
        var tipNum = 0;

        var num = $('#tip_fee').val();
        if(/^\d+(\.\d{1,2})?$/.test(num) && num != ""){
            tipNum = parseFloat(num);
        }else{
            $('#tip_list').children('span').each(function(){
                if($(this).hasClass('tip_on')){
                    if(isb)
                        tipNum = parseFloat($(this).text().replace('$', ""));
                    else
                        tipNum = $('input[name="charge_total"]').val() *  ($(this).text().replace(/%/, "")/100);
                }
            });
        }
        var totalNum = parseFloat($('input[name="charge_total"]').val()) + parseFloat(tipNum);

        $('input[name="tip"]').val(tipNum.toFixed(2));

        $('#tip_num').text('$' + tipNum.toFixed(2));
        $('#add_tip').text('$' + totalNum.toFixed(2));

        var user_money = {pigcms{$now_user.now_money};
        if(totalNum > user_money){
            $('#balance_money').css('color','#C1B9B9');
            $('#use_balance').removeAttr('checked');
            $('#use_balance').attr('disabled','disabled');

            $('#normal-fieldset').css('display','block');
        }else{
            $('#balance_money').css('color','#666666');
            $('#use_balance').removeAttr('disabled');
        }
        // alert($('#add_tip').text().replace('$', ""));
    }

    function tip_select(){
        $('#tip_list').children('span').each(function(){
            $(this).removeClass('tip_on');
        });
        $(this).addClass('tip_on');
        $('#tip_fee').val("");
        $('#tip_fee').after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").removeClass('form-field--error');
        CalTip();
    }

    function changePay() {
        $("input[name='use_balance']").attr('value',0);
        $("#use_balance").removeAttr('checked');

        isShowCredit();
    }

    function isShowCredit(){
        if($("#use_balance").is(':checked')==true){
            $('input[name="pay_type"]').removeAttr('checked');
            $('#tip_label').show();
            $('#credit').hide();
        }else{
            var pay_type = $('input[name="pay_type"]:checked').val();
            if(pay_type == 'moneris'){
                $('#credit').show();
                $('#tip_label').show();
            }else if(pay_type == 'weixin' || pay_type == 'alipay'){
                $('#tip_label').show();
                $('#credit').hide();
            }else{
                $('#credit').hide();
                $('#tip_label').hide();
            }
            check_money(total_money,sysc_price,merchant_money);
            CalTip();
        }
    }

    $('#tip_fee').live('focusin focusout',function(event){
        if(event.type == 'focusin'){
            $(this).siblings('.inline-tip').remove();$(this).removeClass('form-field--error');
        }else{
            $(this).val($.trim($(this).val()));
            var num = $(this).val();
            if(num != ''){
                if(!/^\d+(\.\d{1,2})?$/.test(num)){
                    alert("{pigcms{:L('_PLEASE_RIGHT_PRICE_')}");
                    $(this).focus();
                    $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").addClass('form-field--error');
                }else{
                    $('#tip_list').children('span').each(function(){
                        $(this).removeClass('tip_on');
                    });
                }
            }else{
                var isC = false;
                $('#tip_list').children('span').each(function(){
                    if($(this).hasClass('tip_on')){
                        isC = true;
                    }
                });
                if(!isC){
                    var i=0;
                    $('#tip_list').children('span').each(function(){
                        if(i == 1){
                            $(this).addClass('tip_on');
                        }
                        i++;
                    });
                }
            }
            CalTip();
        }
    });

    $('#card_name').live('focusin focusout',function(event){
        if(event.type == 'focusin'){
            $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
        }else{
            $(this).val($.trim($(this).val()));
            var name = $(this).val();
            if(name.length < 2){
                $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
            }
        }
    });
    $('#card_num').live('focusin focusout',function(event){
        if(event.type == 'focusin'){
            $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
        }else{
            $(this).val($.trim($(this).val()));
            var num = $(this).val();
            if(!/^\d{13,}$/.test(num)){
                $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
            }
        }
    });

    $('#expiry').live('focusin focusout',function(event){
        if(event.type == 'focusin'){
            $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
        }else{
            $(this).val($.trim($(this).val()));
            var expiry = $(this).val();
            if(expiry.length < 4 || expiry.length > 4){
                $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
            }
        }
    });
    function check_card(){
        var isT = true;
        if($('#card_name').val().length < 2 || !/^\d{13,}$/.test($('#card_num').val()) || $('#expiry').val().length != 4 ){
            isT = false;
        }
        return isT;
    }

</script>
<style>
    .form-field--error{
        border:1px #FF0000 solid;
    }
</style>
<if condition="$cheap_info['can_buy'] heq false">
    <script>layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'您必须关注公众号后才能购买本单！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$order_info['order_id']+2000000000}" style="width:230px;height:230px;"/>',shadeClose:false});$('button.mj-submit').remove();var showBuyBtn = false;</script>
</if>
<script>if(showBuyBtn){$('button.mj-submit').show();}</script>
<php>$no_footer = true;</php>
<include file="Public:footer"/>
{pigcms{$hideScript}

<?php } ?>
</body>
</html>