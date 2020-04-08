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
getNewOrder();
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
                var audio = new Audio();
                audio.src = sound_url;
                audio.play();
            }
            if(result.list != null){
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
                order_info = this_order.info;

                this_order.is_app = is_app;
                laytpl($('#OrderDetailTpl').html()).render(this_order, function(html){
                    $('#detail_div').html(html);

                    if(this_order.status == 0) {
                        $('.con_layer').show();
                        $('#item_all_num').html(this_order.num);
                    }else {
                        $('.con_layer').hide();
                        $('#print_order').unbind('click');
                        $('#print_order').click(printOrderToAndroid);
                    }
                });
            }
        }
    },'JSON');
}