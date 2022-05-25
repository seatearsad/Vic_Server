var tip_message = "<div id=\"new_msg\">" +
    "                <img src=\""+new_img+"\" width=\"120\">" +
    "                <div style=\"margin-top: 10px\">" +
    "                    You have one or more orders need to be confirmed. Please check the delivery page." +
    "                </div>" +
    "                <a href=\""+link_url+"\">" +
    "                    View" +
    "                </a>" +
    "            </div>";

var last_time = 0;
var click_id = 0;
var this_order;
var order_info;
var time_out;

var audio = new Audio();
audio.src = sound_url;
audio.loop = "loop";

getNewOrder();

$('body').click(function () {
    if(navigator.userAgent.match(/TuttiPartner/i)) {
        if(/(tuttipartner version)/.test(navigator.userAgent.toLowerCase())){
            window.webkit.messageHandlers.stopSound.postMessage([0]);
        }
    }else if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
        if (typeof (window.linkJs.stopSound) != 'undefined') {
            window.linkJs.stopSound();
        }
    }else {
        if(!audio.paused) {
            audio.pause();
        }
    }
});

function getNewOrder(){
    $.post(new_url,{last_time:last_time},function(result){
        if(result.error == 0){
            last_time = result.new_time;
            if(result.is_new == 1){
                layer.open({
                    title:[' ','border:none'],
                    content:tip_message,
                    style: 'border:none; background-color:#ffa52d; color:#fff;'
                });
                if(navigator.userAgent.match(/TuttiPartner/i))
                    window.webkit.messageHandlers.newOrderSound.postMessage([0]);
                else if(/(tutti_android)/.test(navigator.userAgent.toLowerCase())) {
                    if (typeof (window.linkJs.newOrderSound) != 'undefined') {
                        window.linkJs.newOrderSound();
                    }
                }else {
                    //var audio = new Audio();
                    //audio.src = sound_url;
                    //audio.loop = "loop";
                    audio.play();
                }
            }
            if(result.list != null && result.list.length > 0 && document.getElementById('OrderListTpl')){
                laytpl($('#OrderListTpl').html()).render(result.list, function(html){
                    $('.list_ul').html(html);
                    if(click_id != 0){
                        $('.list_ul').children('li').each(function () {
                            if($(this).data('id') == click_id){
                                $(this).addClass('act_li');
                            }
                        });
                    }
                    $('.list_ul').children('li').unbind('click');
                    $('.list_ul').children('li').click(function () {
                        click_order_list($(this));
                    });
                });
                if(document.getElementById('detail_div') && click_id == 0){
                    if(result.new_num > 0)
                        laytpl($('#HasOrderShow').html()).render({len:result.new_num,con_len:result.con_num}, function(html){
                            $('#detail_div').html(html);
                        });
                    else
                        laytpl($('#NotOrderShow').html()).render({con_len:result.con_num}, function(html){
                            $('#detail_div').html(html);
                        });
                }
            }else {
                if(document.getElementById('detail_div') && document.getElementById('NotOrderShow')){
                    laytpl($('#NotOrderShow').html()).render({con_len:0}, function(html){
                        $('#detail_div').html(html);
                    });
                }
            }
        }
        setTimeout(getNewOrder,3000);
    },'JSON');
}

function click_order_list(order) {
    if(click_id != order.data('id')) {
        $('.list_ul').children('li').each(function () {
            $(this).removeClass('act_li');
        });
        order.addClass('act_li');
        click_id = order.data('id');

        getOrderDetail(click_id);
    }
}

function getOrderDetail(order_id) {
    if(time_out) clearTimeout(time_out);
    if(typeof (is_detail_hide) != 'undefined'){
        if(is_detail_hide)
            $('.list_top').trigger('click');
    }
    layer.open({
        type:2,
        content:'Loading...'
    });
    $.post(detail_url,{order_id:order_id},function(result) {
        if (result.error == 0) {
            layer.closeAll();
            if(result.order_data != null){
                this_order = result.order_data;
                order_info = result.info_str;

                var busy_mode = this_order.busy_mode;
                if(busy_mode == 1){
                    $("#tip_layer").html(this_order.tip_msg);
                    $("#tip_layer").show();
                }else{
                    $("#tip_layer").hide();
                }

                this_order.is_app = is_app;
                laytpl($('#OrderDetailTpl').html()).render(this_order, function(html){
                    $('#detail_div').html(html);

                    if(this_order.status == 0) {
                        $('.con_layer').show();
                        if(busy_mode == 1) {
                            $('.con_layer').find(".confirm_time").find('option').each(function () {
                                if($(this).val() < this_order.min_time){
                                    $(this).remove();
                                }
                            });
                        }
                        $('.con_layer_confirm').hide();
                        //$('#item_all_num').html(this_order.num);
                        $('.confirm_btn').html('Confirm <label id="item_all_num">'+this_order.num+'</label> Item(s)');

                        if(this_order.order_type == 0){
                            $('.con_layer').css("background","#ffa52d");
                            $('.confirm_btn').css("background","#ffa52d");
                        }else {
                            $('.con_layer').css("background","#294068");
                            $('.confirm_btn').css("background","#294068");
                        }
                    }else {
                        $('.con_layer').hide();
                        $('.con_layer_confirm').show();
                        $('.cha_time').html(this_order.time_cha);

                        if(this_order.order_type == 1 && this_order.order_status == 1) {
                            $('.confirm_btn').css({"background":"#294068","color":"white"});
                            $('.confirm_btn').html("READY FOR PICKUP");
                        }else {
                            $('.confirm_btn').attr("style", "");
                            $('.confirm_btn').html('<label id="item_all_con_num">'+this_order.num+'</label> Item(s) Confirmed');
                        }

                        if(this_order.order_status == 1 || this_order.order_status == 2) {
                            $('#add_dining_time').show();
                            $('.con_layer_confirm .confirm_txt').show();
                            $('.con_layer_confirm .cha_time').show();
                            cal_time_show(this_order,0,1);
                        }else {
                            $('#add_dining_time').hide();
                            $('.con_layer_confirm .confirm_txt').hide();
                            $('.con_layer_confirm .cha_time').hide();
                            if(this_order.order_type == 1) {
                                if(this_order.order_status == 5){
                                    $('.confirm_btn').attr("style", "");
                                    $('.confirm_btn').html('');
                                }else{
                                    $('.confirm_btn').css({"background": "#294068", "color": "white"});
                                    $('.confirm_btn').html("MARK AS DONE");
                                }
                            }else{
                                $('.confirm_btn').attr("style", "");
                                $('.confirm_btn').html('<label id="item_all_con_num">'+this_order.num+'</label> Item(s) Confirmed');
                            }
                        }

                        //$('#item_all_con_num').html(this_order.num);
                        $('#print_order').unbind('click');
                        $('#print_order').click(printOrderToAndroid);
                    }
                });
            }
        }
    },'JSON');
}

function cal_time_show(order,cha) {
    var rece_time = order.rece_time;
    var dining_time = order.dining_time;
    var now_time = order.now_time;

    if(now_time - rece_time > dining_time*60){
        //var show_text = 'Order should be ready';
        var show_time = show_time_by_order(now_time - rece_time - dining_time*60 + cha);

        $('.cha_time').html("<label class='t_color'>"+show_time+ " ago</label>");
        time_out = setTimeout(function(){
            cha++;
            cal_time_show(order,cha);
        },1000);
    }else{
        var g_time = parseInt(rece_time) + parseInt(dining_time)*60 - now_time - cha;

        if(g_time < 0){
            getOrderDetail(order.order_id);
            return false;
        }else {
            var show_time = show_time_by_order(g_time);

            $('.cha_time').html("<label style='color: darkgreen'> in " + show_time + "</label>");
            time_out = setTimeout(function () {
                cha++;
                cal_time_show(order, cha);
            }, 1000);
        }
    }
}

function show_time_by_order(time) {
    var h = parseInt(time/3600);
    var m = parseInt(time%3600/60);
    m = m<10 ? '0'+m : m;
    var s = parseInt(time%60);
    s = s<10 ? '0'+s : s;

    var str = h == 0 ? m+':'+s : h+':'+m+':'+s;

    return str;
}