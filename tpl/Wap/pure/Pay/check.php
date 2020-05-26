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
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <include file="Public:facebook"/>
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
        var  delivery_discount = Number("{pigcms{$order_info.delivery_discount}");
        var  service_fee = Number("{pigcms{$order_info.service_fee}");
        <?php if($order_info['delivery_discount_type'] == 0 && $system_coupon['discount']>0){ ?>
            delivery_discount = 0;
        <?php } ?>
        var  merchant_reduce = Number("{pigcms{$order_info.merchant_reduce}");
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
                    // $('input[name="charge_total"]').val((total_money-sysc_price-score_money).toFixed(2));
                    $('input[name="charge_total"]').val((total_money-sysc_price-score_money-delivery_discount-merchant_reduce).toFixed(2));
                }else{
                    $('#pay_in_fact').html('{pigcms{:L("_ACTUAL_PAYMENT_")}：<b style="color:red">$'+(total_money-sysc_price).toFixed(2)+'</b>');
                    // $('input[name="charge_total"]').val((total_money-sysc_price).toFixed(2));
                    $('input[name="charge_total"]').val((total_money-sysc_price-delivery_discount-merchant_reduce).toFixed(2));
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
                                'order_type':"{pigcms{$order_info.order_type}",
                                'note':$('input[name="note"]').val(),
                                'est_time':$('#est_time_input').val(),
                                'cvd':$('#cvd').val(),
                                'delivery_discount':delivery_discount,
                                'not_touch':$('input[name="not_touch"]:checked').val(),
                                'merchant_reduce':merchant_reduce,
                                'service_fee':service_fee
                            };

                            //alert(re_data['order_type']);
                            $.post($('#moneris_form').attr('action'),re_data,function(data){
                                if(typeof (data.mode) != 'undefined' && data.mode == 'mpi'){
                                    // layer.open({
                                    //     title:'',
                                    //     content:data.html
                                    // });
                                    $('body').append(data.html);
                                }else {
                                    layer.closeAll();
                                    layer.open({title: ['Message'], content: data.info});
                                    if (data.status == 1) {
                                        setTimeout("window.location.href = '" + data.url + "'", 200);
                                    }
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
                                'order_type':"{pigcms{$order_info.order_type}",
                                'note':$('input[name="note"]').val(),
                                'est_time':$('#est_time_input').val(),
                                'delivery_discount':delivery_discount,
                                'not_touch':$('input[name="not_touch"]:checked').val(),
                                'merchant_reduce':merchant_reduce,
                                'service_fee':service_fee
                            };
                            var card_stauts = "{pigcms{$card['status']}";
                            if(card_stauts == 0){
                                var old_cvd = $('input[name="old_cvd"]').val();
                                if(!/^\d{3}$/.test(old_cvd)){
                                    alert('Please input CVD');
                                    layer.closeAll();
                                    return false;
                                }else{
                                    re_data['cvd'] = old_cvd;
                                }
                            }
                            //alert(re_data['order_type']);
                            $.post($('#moneris_form').attr('action'),re_data,function(data){
                                if(typeof (data.mode) != 'undefined' && data.mode == 'mpi'){
                                    // layer.open({
                                    //     title:'',
                                    //     content:data.html
                                    // });
                                    $('body').append(data.html);
                                }else {
                                    layer.closeAll();
                                    layer.open({title: ['Message'], content: data.info});
                                    if (data.status == 1) {
                                        setTimeout("window.location.href = '" + data.url + "'", 200);
                                    } else {

                                    }
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
                        'pay_type':pay_type,
                        'note':$('input[name="note"]').val(),
                        'est_time':$('#est_time_input').val(),
                        'delivery_discount':delivery_discount,
                        'not_touch':$('input[name="not_touch"]:checked').val(),
                        'merchant_reduce':merchant_reduce,
                        'service_fee':service_fee
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
<style>
    input::-webkit-input-placeholder { /* WebKit browsers */
        color:    #999;
        font-size: 1em;
    }
    input:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
        color:    #999;
        font-size: 1em;
    }
    input::-moz-placeholder { /* Mozilla Firefox 19+ */
        color:    #999;
        font-size: 1em;
    }
    input:-ms-input-placeholder { /* Internet Explorer 10+ */
        color:    #999;
        font-size: 1em;
    }
    .wrapper-list{
        padding-top: 70px;
        margin-bottom: 70px;
    }
    .user_address{
        width: 100%;
        background-color: white;
        background-image: url("./tpl/Static/blue/images/wap/address.png");
        background-repeat: no-repeat;
        background-size: auto 40px;
        background-position: 10px center;
        padding: 10px 0;
    }
    .user_address div{
        line-height: 25px;
        margin-left: 70px;
        font-size: 1.1em;
    }
    .order_store{
        line-height: 30px;
        padding-left: 40px;
        color: #999;
        background-image: url("./tpl/Static/blue/images/wap/shop.png");
        background-repeat: no-repeat;
        background-size: auto 20px;
        background-position: 10px center;
    }
    .all_list{
        border: 0;
        background-color: white;
        margin-top: 10px;
    }
    dl.all_list dt, dl.all_list dd{
        border-bottom: 0;
        border-top: 1px solid #e5e5e5;
        display: flex;
        padding: 20px 0px 20px 40px;
    }
    .goods_name,.goods_price{
        flex: 1 1 100%;
    }
    .goods_price{
        text-align: right;
        padding-right: 20px;
        color: #ffa52d;
    }
    .goods_num{
        flex: 0 0 auto;
    }
    .goods_spec{
        font-size: .8em;
        color: #999;
        margin-top: -10px;
        margin-left: 40px;
        padding-bottom: 10px;
        margin-right: 10px;
        line-height: 1.2em;
    }
    .order_note{
        line-height: 30px;
        padding-left: 40px;
        color: #999;
        background-image: url("./tpl/Static/blue/images/wap/est_time.png");
        background-repeat: no-repeat;
        background-size: auto 20px;
        background-position: 10px center;
        border-bottom: 1px solid #e5e5e5;
        cursor: pointer;
    }
    .est_time{
        color: #ffa52d;
        float: right;
        margin-right: 20px;
    }
    .coupon_span{
        color: #ffa52d;
        float: right;
        margin-right: 20px;
    }
    .note_input{
        border: 0;
        width: 90%;
        margin-left: 5%;
        line-height: 25px;
        background-color: #eee;
        padding-left: 5px;
    }
    input[type=text]{
        border: 0;
        background-color: #eee;
    }
    #tip_fee{
        border: 0;
        width: 90%;
        line-height: 25px;
        background-color: #eee;
        padding-left: 5px;
    }
    .note_div{
        height: 45px;
        padding-top: 7px;
    }
    .touch_tip{
        width: 90%;
        margin: 10px auto 0 auto;
        border: 2px solid #ffa52d;
        padding: 10px;
        box-sizing: border-box;
        font-size: 12px;
    }
    .apply_div{
        height: 45px;
        padding-top: 7px;
        display: flex;
    }
    .coupon_code{
        border: 0;
        width: 70%;
        margin-left: 5%;
        height:25px;
        line-height: 25px;
        background-color: #eee;
        padding-left: 5px;
    }
    #ex_code{
        width: 18%;
        height: 25px;
        line-height: 25px;
        color: white;
        background-color: #ffa52d;
        text-align: center;
        margin-left: 2%;
        cursor: pointer;
        border-radius: 2px;
    }
    .av_coupon{
        line-height: 30px;
        padding-left: 40px;
        color: #999;
        background-image: url("./tpl/Static/blue/images/wap/av_coupon.png");
        background-repeat: no-repeat;
        background-size: auto 20px;
        background-position: 10px center;
        border-bottom: 1px solid #e5e5e5;
    }
    .coupon_desc{
        line-height: 45px;
        padding-left: 40px;
    }
    .coupon_more{
        float: right;
        margin-right: 20px;
        width:20px;
        height: 30px;
        background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
        background-repeat: no-repeat;
        background-size: auto 16px;
        background-position: center;
    }
    .payment{
        line-height: 30px;
        padding-left: 40px;
        color: #999;
        background-image: url("./tpl/Static/blue/images/wap/payment.png");
        background-repeat: no-repeat;
        background-size: auto 20px;
        background-position: 10px center;
        border-bottom: 1px solid #e5e5e5;
    }
    .tip_title{
        line-height: 30px;
        padding-left: 40px;
        color: #999;
        background-image: url("./tpl/Static/blue/images/wap/tip.png");
        background-repeat: no-repeat;
        background-size: auto 20px;
        background-position: 10px center;
        display: flex;
    }
    input.mt[type="radio"]:checked, input.mt[type="checkbox"]:checked{
        background-color: #ffa52d;
    }
    input.mt[type="radio"], input.mt[type="checkbox"]{
        margin-right: 10px;
    }
    #tip_list{
        width: 80%;
        margin-left: 10px;
        margin-top: 2px;
    }
    #tip_input{
        width: 80%;
        padding-left: 20px;
        display: none;
    }
    .tip_more{
        width:20px;
        height: 30px;
        background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
        background-repeat: no-repeat;
        background-size: auto 16px;
        background-position: center;
        position: absolute;
        right: 20px;
        cursor: pointer;
    }
    #tip_label{
        padding: 10px 0;
    }
    #credit{
        margin-top: 10px;
    }
    a{
        color: #333;
    }
    .more::after{
        border-left: .04rem solid #999999;
        border-bottom: .04rem solid #999999;
    }
    .price_list{
        width: 90%;
        margin: 0 auto;
        padding: 20px 0;
        line-height: 20px;
    }
    .price_list span{
        float: right;
    }
    .price_total{
        width: 100%;
        line-height: 40px;
        border-top: 1px solid #e5e5e5;
        text-align: right;
        padding-right: 5%;
        color: #ffa52d;
    }
    #agree_div{
        width: 90%;
        margin: 0 auto;
        padding: 20px 0;
        line-height: 20px;
        font-size: 1.1em;
    }
    #agree_div a{
        text-decoration: underline;
    }
    .confirm_btn{
        width: 50%;
        background-color: #ffa52d;
        color: #fff;
        border: none;
        margin: 20px 25%;
        font-size: 1.2em;
        line-height: 30px;
        border-radius: 2px;
    }
    #free_delivery{
        text-align: center;
        color: #ffa52d;
    }
</style>
<include file="Public:header"/>
<div class="wrapper-list">
    <if condition="$order_info['order_type'] != 'recharge'">
    <div class="user_address">
        <div>{pigcms{$order_info['username']} {pigcms{$order_info['phone']}</div>
        <div>{pigcms{$order_info['address']}</div>
    </div>
    </if>
    <dl class="all_list">
        <div class="order_store">{pigcms{$order_info['order_name']}</div>
        <volist name="order_info['order_content']" id="vo">
            <dd>
                <div class="goods_name">
                    <div>{pigcms{$vo['name']}</div>
                </div>
                <div class="goods_num">{pigcms{$vo['num']}</div>
                <div class="goods_price">${pigcms{$vo['price']}</div>
            </dd>
            <div class="goods_spec">{pigcms{$vo['spec']}</div>
        </volist>
    </dl>

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
        <input type="hidden" name="delivery_discount" value="{pigcms{$order_info.delivery_discount}">
        <input type="hidden" name="merchant_reduce" value="{pigcms{$order_info.merchant_reduce}">
        <input type="hidden" name="service_fee" value="{pigcms{$order_info.service_fee}">
        <if condition="$order_info['order_type'] != 'recharge'">
        <div class="all_list">
            <div class="order_note">
                Scheduled Delivery
                <span class="coupon_more"></span>
                <span class="est_time">ASAP</span>
                <input type="hidden" name="est_time" id="est_time_input">
            </div>
            <if condition="$not_touch['status'] eq 1">
            <div class="touch_tip">
                <div style="font-weight: bold;">
                    <input type="checkbox" class="mt" value="1" name="not_touch" style="border-radius: 0;width: .40rem;height: .40rem;line-height: .40rem;">
                    {pigcms{$not_touch.title}
                </div>
                <div style="margin-top: 8px;color: #999999">{pigcms{$not_touch.content}</div>
            </div>
            </if>
            <div class="note_div">
                <input type="text" name="note" class="note_input" placeholder="Note">
            </div>
        </div>
        <div class="all_list">
            <a class="react" href="{pigcms{:U('My/select_card',($coupon_url?$coupon_url :$_GET))}&coupon_type=system&delivery_type={pigcms{$order_info['is_c']}" >
                <div class="av_coupon">
                    Available Coupons
                    <span class="coupon_more"></span>
                </div>
            </a>
            <?php if($system_coupon){ ?>
                <div class="coupon_desc">
                    <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                    {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$system_coupon['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$system_coupon['discount'])}
                    <php>}else{</php>
                    {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$system_coupon['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$system_coupon['order_money'])}
                    <php>}</php>
                    <span class="coupon_span">-${pigcms{$system_coupon['discount']}</span>
                </div>
            <?php }else{ ?>
                <div class="apply_div">
                    <input type="text" name="coupon_code" class="coupon_code" placeholder="{pigcms{:L('_EXCHANGE_COUPON_')}">
                    <div id="ex_code">{pigcms{:L('_EXCHANGE_TXT_')}</div>
                </div>
            <?php } ?>
        </div>
        </if>
        <div class="all_list">
        <div class="payment">
            Payment
        </div>
        <div class="pay-methods-panel">
            <div class="normal-fieldset">
                <dl class="list">
                    <?php if(($order_info['order_type'] != 'plat' && $order_info['order_type'] != 'recharge') || $order_info['pay_system_balance']){ ?>
                        <dd class="dd-padding" id="balance_money" <if condition="$now_user.now_money eq 0 OR $merchant_balance gt $order_info.order_total_money ">style="color: #C1B9B9;"</if>>
                        <label class="mt">
                            <span class="pay-wrapper">
                                <img src="./tpl/Static/blue/images/wap/dollar.png" style="height: 25px"/>
                                <font style="color: #ffa52d">${pigcms{$now_user.now_money}</font> &nbsp;&nbsp;Balance Pay
                                <input type="checkbox" class="mt"  id="use_balance" name="use_balance"<if condition="$now_user['now_money'] eq 0 OR $merchant_balance gt $order_info['order_total_money'] ">disabled="disabled" value="1"<else /> value="0" checked="checked" </if>>
                            </span>
                        </label>
                        </dd>
                    <?php } ?>
                    <volist name="pay_method" id="vo">
                        <php>if($pay_offline || $key != 'offline'){</php>
                        <php>if(($key == 'weixin' && $is_wexin_browser) || ($key == 'alipay' && !$is_wexin_browser) || ($key != 'weixin' && $key!= 'alipay')){</php>
                        <php>if(($order_info['order_type'] == recharge && $key != 'weixin' && $key!= 'alipay') || $order_info['order_type'] != recharge){</php>
                        <dd class="dd-padding">
                            <label class="mt">
                                <!--i class="bank-icon icon-{pigcms{$key}"></i-->
                                <span class="pay-wrapper">
                                    <img src="{pigcms{$static_public}images/pay/{pigcms{$key}.png" style="height: 25px"/>
                                    <if condition="$key eq 'offline'">Cash</if>
                                    <input type="radio" class="mt" value="{pigcms{$key}"  <php>if($key == 'moneris'){</php>checked="checked"<php>}</php> name="pay_type">
                                </span>
                            </label>
                            <php>if($key == 'moneris'){</php>
                            <div id="credit" class="normal-fieldset">
                                <dl class="list">
                                    <if condition="$card">
                                        <div style="line-height: 20px;width: 100%;margin-bottom: 15px;">
                                            <input type="radio" name="pay_card_type" value="0" class="mt" checked=checked> {pigcms{:L('_USE_OLD_CARD_')}
                                        </div>
                                        <a href="{pigcms{:U('My/credit',array('order_id'=>$order_info['order_id'],'type'=>$order_info['order_type']))}">
                                            <dd class="more dd-padding">
                                                <label class="mt">
                                                    <span class="pay-wrapper">
                                                           {pigcms{$card['name']} -- {pigcms{$card['card_num']}
                                                    </span>
                                                </label>
                                            </dd>
                                        </a>
                                        <if condition="$card['status'] eq 0">
                                            <div style="line-height: 20px;float:left;width: 100%;margin-left:.2rem;margin-top: 5px;margin-bottom: 5px;">
                                                <span style="float: left;width:50px;">CVD：</span>
                                                <input type="text" maxlength="3" size="20" name="old_cvd" class="form-field" id="old_cvd" placeholder="3 digites on the back of your card" value="" style="float: left"/>
                                            </div>
                                        </if>
                                    </if>
                                    <dd class="dd-padding" style="border-top: 1px #cccccc solid;">
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
                                        <div style="line-height: 20px;float:left;width: 100%;margin-bottom: 5px;">
                                            <span style="float: left;width:150px;">CVD：</span>
                                            <input type="text" maxlength="3" size="20" name="cvd" class="form-field" placeholder="3 digites on the back of your card" id="cvd" value="" style="float: left"/>
                                        </div>
                                    </dd>
                                </dl>
                            </div>
                            <php>}</php>
                        </dd>
                        <php>}</php>
                        <php>}</php>
                        <php>}</php>
                    </volist>
                </dl>
            </div>
        </div>
        <if condition="$order_info['order_type'] != 'recharge'">
            <div id="tip_label" class="normal-fieldset">
                <dl class="list" style="border-bottom: 0;">
                    <div class="tip_title">
                        <div>{pigcms{:L('_TIP_TXT_')}</div>
                        <div id="tip_list">
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
                        <div id="tip_input">
                            $ <input type="text" id="tip_fee" name="tip_fee" size="20">
                        </div>
                        <span class="tip_more"></span>
                    </div>
                </dl>
            </div>
        </if>
        <span id="tip_num" style="display: none;">$0</span>
        <span id="add_tip" style="display: none;">$0</span>
        </div>
        <div class="all_list">
            <if condition="$order_info['order_type'] != 'recharge'">
            <div class="price_list">
                <if condition="$order_info['delivery_discount'] neq 0">
                <div id="free_delivery">
                    You're eligible for free delivery!
                </div>
                </if>
                <div>
                    Subtotal <span>${pigcms{$order_info['goods_price']}</span>
                </div>
                <div>
                    {pigcms{:L('_DELI_PRICE_')} <span>${pigcms{$order_info['freight_charge']}</span>
                </div>
                <div>
                    {pigcms{:L('V2_SERVICEFEE')} <img src="{pigcms{$static_path}img/index/tax_fee.png" id="tax_fee_img" width="20" style="vertical-align: middle;margin-left: 5px;" />
                    <span>${pigcms{:number_format($order_info['packing_charge'] + $order_info['deposit_price'] + $order_info['tax_price'] + $order_info['service_fee'],2)}</span>
                </div>
                <!--if condition="$order_info['packing_charge'] != 0">
                <div>
                    {pigcms{:L('_PACK_PRICE_')} <span>${pigcms{$order_info['packing_charge']}</span>
                </div>
                </if>
                <if condition="$order_info['deposit_price'] != 0">
                <div>
                    {pigcms{:L('_DEPOSIT_TXT_')} <span>${pigcms{:sprintf("%.2f",$order_info['deposit_price'])}</span>
                </div>
                </if>
                <div>
                    {pigcms{:L('_TAXATION_TXT_')} <span>${pigcms{:sprintf("%.2f",$order_info['tax_price'])}</span>
                </div>
                <div>
                    {pigcms{:L('_SERVICE_FEE_')} <span>${pigcms{:sprintf("%.2f",$order_info['service_fee'])}</span>
                </div-->
                <div>
                    {pigcms{:L('_TIP_TXT_')} <span class="tip_show"></span>
                </div>
                <?php if($system_coupon){ ?>
                <div>
                    Coupon
                    <span>
                        -${pigcms{:sprintf("%.2f",$system_coupon['discount'])}
                    </span>
                </div>
                <?php } ?>
                <if condition="$order_info['delivery_discount']+$order_info['merchant_reduce'] neq 0">
                    <div style="color: #ffa52d">
                        Save <span>-${pigcms{:sprintf("%.2f",$order_info['delivery_discount']+$order_info['merchant_reduce'])}</span>
                    </div>
                </if>
            </div>
            </if>
            <div class="price_total">
                Total <span></span>
            </div>
        </div>
        <div id="agree_div">
            <input type="checkbox" name="is_agree" class="form-field" id="is_agree" value="1" checked="checked"/>
            By clicking the box,you are agree with
            <a href="./intro/5.html" target="_blank">Terms of Use</a> and <a href="./intro/2.html" target="_blank">Privacy Policy</a>
        </div>
        <div style="text-align: center; color: #ffa52d; margin-bottom: -10px" id="count_down"></div>
        <button type="button" class="confirm_btn">{pigcms{:L('_B_D_LOGIN_CONIERM_')}</button>
            <!--div style="background-color: #FFFFFF; height: 53px;position: fixed;bottom: 0;left: 0;right: 0;z-index: 900;-webkit-tap-highlight-color: rgba(0, 0, 0, 0);height: 49px;width: 100%;">
                <div id="need_pay_title" style="    position: absolute;margin-top: 18px;margin-left: 0.3rem;">
                    {pigcms{:L('_ALSO_NEED_PAY_')} <div style="font-weight:bold;color:red;display: inline;">$<div class="need-pay" style="display:inline;">
                        </div>
                    </div>
                </div>
                <button type="button" onclick="bio_verify()" style="float: right;height: 100%;width: 50%;background-color: #06c1ae;color: #fff;border: none;">{pigcms{:L('_CONFIRM_PAY_')}</button>
            </div-->
        </div>
    </form>
</div>

<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>

<!-- 加 -->
<style type="text/css">
    .verify_jg{ display: inline-block; height: 27px; padding:50px 0; }
    .verify_l{ float: left; line-height: 27px; color: red }
    .verify_lspan{ font-size: 20px; float: left; }
    .verify_lspan1{margin: 0 5px; float: left;}
    .plus{ float: left; }
    .plus a{width: 30px;float: left;height: 25px;border: #e5e5e5 1px solid;text-align: center;color: #232326;font-size: 20px;line-height: 25px;font-family: "Arial";}
    .plus input{width: 80px;text-align: center;float: left;border: #e5e5e5 1px solid;border-left: none;border-right: none;height: 25px;font-size: 14px;border-radius: 0px;font-size: 16px;color: red;}
    .tip_s{width: 32%; height: 25px;color: #ffa52d; border: 1px #ffa52d solid;line-height: 25px;text-align: center;font-size: 16px;display:-moz-inline-box;display:block;cursor: pointer;float: left}
    .tip_on{background-color: #ffa52d;color: #ffffff;border-color:#ffa52d }
</style>
<!-- 加 -->
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script src="{pigcms{$static_path}js/bioauth_.js"></script>

<script>
    $('.tip_more').click(function () {
        if($('#tip_list').is(':hidden')){
            $('#tip_list').show();
            $('#tip_input').hide();
        }else{
            $('#tip_list').hide();
            $('#tip_input').show();
        }
    });

    $(function () {
        if(!$('input[name="is_agree"]').is(':checked')){
            $('.confirm_btn').css('background-color', '#666666');
            $('.confirm_btn').unbind();
        }
    });

    $('input[name="is_agree"]').click(function () {
        if($('input[name="is_agree"]').is(':checked')){
            $('.confirm_btn').css('background-color', '#ffa52d');
            $('.confirm_btn').bind('click',bio_verify);
        }else{
            $('.confirm_btn').css('background-color', '#666666');
            $('.confirm_btn').unbind();
        }
    });

    $('.confirm_btn').click(function () {
        bio_verify();
    });

    $("#ex_code").click(function(){
        var code = $("input[name='coupon_code']").val();
        if(code == ""){
            layer.open({
                title:'Message',
                content:"{pigcms{:L('_INPUT_EXCHANGE_CODE_')}"
            });
        } else{
            exchange_code(code);
        }
    })

    function exchange_code(code){
        $.ajax({url:"{pigcms{:U('My/exchangeCode')}",type:"post",data:"code="+code,dataType:"json",success:function(data){
                if(data.error_code == 0){
                    layer.open({
                        title:'Message',
                        time:1,
                        content:"Success"
                    });
                    window.location.reload();
                }else{
                    layer.open({
                        title:'Message',
                        time:1,
                        content:data.msg
                    });
                }
            }
        });
    }
    
    $('.order_note').click(function () {
        $('#est_time_input').trigger('click');
    });

    var theme = "ios";
    var mode = "scroller";
    var display = "bottom";
    var lang="en";

    var myDate = new Date();
    //获取当前年
    var year=myDate.getFullYear();
    //获取当前月
    var month=myDate.getMonth();
    //获取当前日
    var date=myDate.getDate();
    var h=myDate.getHours() + 1;       //获取当前小时数(0-23)
    var m=myDate.getMinutes() + 30;

    $('#est_time_input').mobiscroll().datetime({
        theme: theme,
        mode: mode,
        display: display,
        dateFormat: 'yyyy-mm-dd',
        dateOrder:'yyMdd',
        timeFormat: 'HH:ii',
        timeWheels: 'HHii',
        minDate: new Date(year,month,date,h,m),
        maxDate: new Date(year,month+1,date+1),
        lang: lang,
        stepMinute: 1
    });
    $('#est_time_input').change(function () {
        if($(this).val() == '')
            $('.est_time').html('ASAP');
        else
            $('.est_time').html($(this).val());
    });

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

        var pay_type = $('input[name="pay_type"]:checked').val();

        if(pay_type != 'offline') {
            $('.tip_show').text('$' + tipNum.toFixed(2));
            $('.price_total').find('span').text('$' + totalNum.toFixed(2));
        }


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
            CalTip();
        }else{
            var pay_type = $('input[name="pay_type"]:checked').val();
            if(pay_type == 'moneris'){
                $('#credit').show();
                $('#tip_label').show();
                $('.tip_show').text($('#tip_num').text());
                $('.price_total').find('span').text($('#add_tip').text());
            }else if(pay_type == 'weixin' || pay_type == 'alipay'){
                $('#tip_label').show();
                $('#credit').hide();
                $('.tip_show').text($('#tip_num').text());
                $('.price_total').find('span').text($('#add_tip').text());
            }else{
                $('#credit').hide();
                $('#tip_label').hide();
                $('.tip_show').text('$0.00');
                $('.price_total').find('span').text('$' + $('input[name="charge_total"]').val());
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

    $('#cvd').live('focusin focusout',function(event){
        if(event.type == 'focusin'){
            $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
        }else{
            $(this).val($.trim($(this).val()));
            var cvd = $(this).val();
            if(!/^\d{3}$/.test(cvd)){
                $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
            }
        }
    });

    function check_card(){
        var isT = true;
        if($('#card_name').val().length < 2 || !/^\d{13,}$/.test($('#card_num').val()) || $('#expiry').val().length != 4 || !/^\d{3}$/.test($('#cvd').val())){
            isT = false;
        }
        return isT;
    }

    var create_time = "{pigcms{$order_info.create_time}";
    var jetlag = "{pigcms{$jetlag}";
    var curr_time = parseInt("{pigcms{:time()}") + parseInt(jetlag)*3600;

    var cha_time = 300 - (curr_time - create_time);
    function update_pay_time() {
        var h = parseInt(cha_time / 3600);
        var i = parseInt((cha_time - 3600 * h) / 60);
        var s = (cha_time - 3600 * h) % 60;
        if (i < 10) i = '0' + i;
        if (s < 10) s = '0' + s;

        //var time_str = h + ':' + i + ':' + s;
        var time_str = i + ':' + s;

        $('#count_down').html(time_str);

        if(cha_time < 0){
            layer.open({content:'Payment over-time. You will be directed back to the menu.',shadeClose:false,btn:['OK'],yes:function(){
                window.location.href = "{pigcms{:U('Shop/classic_shop')}&shop_id={pigcms{$order_info.store_id}";
            }});
        }else {
            window.setTimeout(function () {
                cha_time--;
                update_pay_time()
            }, 1000);
        }
    }

    update_pay_time();

    var width = $(window).width()*2/3;

    var msg = "<div class='b_font' style='width: "+width+"px;text-align: center;'>{pigcms{:L('V2_SERVICEFEE')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_TAX')}:${pigcms{:number_format($order_info['tax_price'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:L('V2_TAXDES')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_PACKINGFEE')}:${pigcms{:number_format($order_info['packing_charge'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:L('V2_PACKINGFEEDES')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_BOTTLEDEPOSIT')}:${pigcms{:number_format($order_info['deposit_price'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:L('V2_BOTTLEDEPOSITDES')}</div>" +
        "<div class='b_font' style='width: "+width+"px;margin-top: 10px'>{pigcms{:L('V2_SERVICEFEEDES')}:${pigcms{:number_format($order_info['service_fee'],2)}</div>" +
        "<div style='width: "+width+"px;'>{pigcms{:replace_lang_str(L('V2_TOTALTAXNFEES'),$order_info['store_service_fee'])}</div>" +
        "<div class='b_font' style='width: "+width+"px;text-align: right;margin-top: 15px;margin-bottom: 10px;'>{pigcms{:L('V2_SERVICEFEE')}:{pigcms{:number_format($order_info['packing_charge'] + $order_info['tax_price'] + $order_info['deposit_price'] + $order_info['service_fee'],2)}</div>";

    $('#tax_fee_img').click(function () {
        layer.open({
            title:["",'border:none'],
            content:msg,
            style: 'border:none; background-color:#fff; color:#999;'
        });
    });
</script>
<style>
    .form-field--error{
        border:1px #FF0000 solid;
    }
    .b_font{
        color: #555;
        font-weight: bold;
        font-size: 16px;
    }
</style>
<if condition="$cheap_info['can_buy'] heq false">
    <script>layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'您必须关注公众号后才能购买本单！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$order_info['order_id']+2000000000}" style="width:230px;height:230px;"/>',shadeClose:false});$('button.mj-submit').remove();var showBuyBtn = false;</script>
</if>
<script>if(showBuyBtn){$('button.mj-submit').show();}</script>
{pigcms{$hideScript}

<?php } ?>
</body>
</html>