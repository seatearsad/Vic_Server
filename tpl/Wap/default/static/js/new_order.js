var message = "<div id=\"new_msg\">" +
    "                <img src=\""+new_img+"\" width=\"120\">" +
    "                <div style=\"margin-top: 10px\">" +
    "                    You have one or more orders need to be confirmed. Please check the delivery page." +
    "                </div>" +
    "                <a href=\"{pigcms{:U('Storestaff/shop_list')}\">" +
    "                    View" +
    "                </a>" +
    "            </div>";

var last_time = 0;
getNewOrder();
function getNewOrder(){
    $.post(new_url,{last_time:last_time},function(result){
        if(result.error == 0){
            last_time = result.new_time;
            if(result.is_new == 1){
                layer.open({
                    title:[' ','border:none'],
                    content:message,
                    style: 'border:none; background-color:#ffa52d; color:#fff;'
                });
            }
        }
        setTimeout(getNewOrder,3000);
    },'JSON');
}