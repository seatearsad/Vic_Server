<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>店员中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<style>
.green{color:green;}
.btn{
margin: 0;
text-align: center;
height: 2.2rem;
line-height: 2.2rem;
padding: 0 .32rem;
border-radius: .3rem;
color: #fff;
border: 0;
background-color: #FF658E;
font-size: .28rem;
vertical-align: middle;
box-sizing: border-box;
cursor: pointer;
-webkit-user-select: none;}
.totel{color: green;}
.cpbiaoge td{font-size:1rem;}
.dropdown_select {
    -webkit-appearance: button;
    -webkit-user-select: none;
    font-size: 13px;
    overflow: visible;
    text-overflow: ellipsis;
    white-space: nowrap;
    color: #999999;
    display: inline;
    position: relative;
    margin: 0px 1px 0px 1px;
    font-size: 16px;
    height: auto;
    padding: 10px;
    outline: none;
    border: 0;
    background-color: transparent;
}
.px {
    position: relative;
    background-color: transparent;
    color: #999999;
    padding: 10px;
    font-size: 16px;
    margin: 0 auto;
    font-family: Arial, Helvetica, sans-serif;
    border: 0;
    -webkit-appearance: none;
}

    .submit_btn{
        color: #fff;
        background-color: #FF658E;
        top: .15rem;
        width: 100%;
        height: 3rem;
        text-align: center;
        line-height: 3rem;
        cursor: pointer;
    }
    #new_msg{
        width: 230px;
        text-align: center;
    }
    #new_msg a{
        display: inline-block;
        width: 100%;
        height: 30px;
        line-height: 30px;
        text-align: center;
        color: white;
        border: 1px solid white;
        border-radius: 5px;
        margin-top: 20px;
        font-weight: bold;
        font-size: 20px;
    }
</style>
</head>
<table>
<div style="padding: 0.8rem;background-color: #ffa52d">
    <a href="javascript:void(0);" onclick="return window.history.go(-1);" style="color: white"> < 返回</a>
    <div id="print_order" style="position: absolute;right: 20px;top:12px;color: white;cursor: pointer;">
        <if condition="$order['paid'] eq 1 and $order['status'] neq 0">
            打印订单
        </if>
    </div>
</div>
<div style="padding: 0.2rem;"> 
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
				<tr>
					<td>订单编号：{pigcms{$order.real_orderid}</td>
				</tr>
				<if condition="$order.orderid neq 0">
				<tr>
					<td>订单流水号：{pigcms{$order.orderid}</td>
				</tr>
				</if>
				<tr>
					<td>客户姓名：{pigcms{$order.username}</td>
				</tr>
				<tr>
					<td>客户电话：<a href="tel:{pigcms{$order.userphone}" class="totel">{pigcms{$order.userphone}</a></td>
				</tr>
				<!--tr>
					<td>下单时间： {pigcms{$order.create_time|date="Y-m-d H:i:s",###}</td>
				</tr-->
				<tr>
					<td>支付时间：{pigcms{$order['pay_time']|date="Y-m-d H:i:s",###} </td>
				</tr>
                <tr>
                    <td>出餐时间：{pigcms{$supply['dining_time']}分钟 </td>
                </tr>
				<if condition="$order['expect_use_time']">
				<tr>
					<td style="font-weight: 800;color:red; ">
                        到货时间：
                        <?php if (($order['expect_use_time'] - $order['pay_time'])>=3600){?>
                        {pigcms{$order['expect_use_time']|date="Y-m-d H:i",###}
                        <?php }else{ ?>
                        ASAP
                        <?php } ?>
                    </td>
				</tr>
				</if>
				<if condition="$order['is_pick_in_store'] eq 2">
				<tr>
					<td>自提地址：{pigcms{$order['address']}</td>
				</tr>
				<else />
				<!--tr>
					<td>客户地址：{pigcms{$order['address']}</td>
				</tr-->
				</if>
				<!--tr>
					<td>配送方式：{pigcms{$order['deliver_str']}</td>
				</tr>
				<tr>
					<td>配送状态：{pigcms{$order['deliver_status_str']}</td>
				</tr-->
				<tr>
                    <td>客户留言： <if condition="$order['desc'] eq ''">无<else /><b style="color:#ffa52d">{pigcms{$order.desc}</b></if></td>
				</tr>
				
				<if condition="$order['invoice_head']">
				<!--tr>
					<td>发票抬头:{pigcms{$order['invoice_head']}</td>
				</tr-->
				</if>
				<!--tr>
				  <td>支付状态：{pigcms{$order['pay_status']}</td>
				</tr>
				<tr>
				  <td>支付方式： {pigcms{$order.pay_type_str}</td>
				</tr-->
				<tr>
					<td>订单状态：{pigcms{$order['status_str']}</td>
				</tr>
			</tbody>
		</table>
		<if condition="$order['cue_field']">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
			<tr>
				<th colspan="2"><strong>分类填写字段</strong></th>
			</tr>
			<volist name="order['cue_field']" id="vo">
				<tr>
					<td>{pigcms{$vo.title}</td>
					<td>{pigcms{$vo.txt}</td>
				</tr>
			</volist>
			</tbody>
		</table>
		</if>
	</ul>
	<!--a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;background-color: #ffa64d">返 回</a-->
    <if condition="$deliver">
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en"></script>
        <div style="margin-left: 10px">{pigcms{$deliver.name}({pigcms{$deliver.phone})</div>
        <div id="deliver_map" style="width: 98%;height: 300px;margin-left: 10px;border: 1px #f0efed solid"></div>

        <span class="d_id">{pigcms{$deliver.uid}</span>
        <span class="d_name">{pigcms{$deliver.name}</span>
        <span class="d_lat">{pigcms{$deliver.lat}</span>
        <span class="d_lng">{pigcms{$deliver.lng}</span>
        <span class="d_phone">{pigcms{$deliver.phone}</span>

        <style>
            .d_id,.d_name,.d_lat,.d_lng,.d_phone{
                display: none;
            }
        </style>
        <script type="text/javascript">
            var mapOptions = {
                zoom: 18,
                center: {lat:48.4245911, lng:-123.3667908}
            }

            var map = new google.maps.Map(document.getElementById('deliver_map'), mapOptions);

            var lat = $('.d_lat').text();
            var lng = $('.d_lng').text();

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat,lng),
                map: map,
                label:$('.d_id').text(),
                title: $('.d_name').text() + '('+ $('.d_phone').text()+ ')',
                tag:$('.d_id').text()
            });
            map.setCenter(marker.getPosition());

            var self_position = new google.maps.LatLng('{pigcms{$shop.lat}','{pigcms{$shop.long}');
            var shop_mark = new google.maps.Marker({
                position: self_position,
                map: map,
                icon:"{pigcms{$static_path}images/map/my_pos.png"
            });
        </script>
    </if>
	<ul class="round">
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
			<tbody>
            <if condition="$order['info']">
				<tr>
                    <th>数量</th>
                    <th>商品名称</th>
                    <th class="cc">单价</th>
				</tr>
				<volist name="order['info']" id="info">
				<tr>
                    <td valign="top" <if condition="$info['num'] gt 1">style="color: #ffa52d"</if>>{pigcms{$info['num']} <!--span style="color: gray; font-size:10px">({pigcms{$info['unit']})</span--></td>
                    <td>
                        {pigcms{$info['name']}
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
                            <volist name="info['spec_arr']" id="spec">
                                <tr style="border: 0px;">
                                    <td style="font-size:12px;color: #666666;border-bottom:0px;padding:2px 0px 2px 5px">
                                        {pigcms{$spec}
                                    </td>
                                </tr>
                            </volist>
                            <volist name="info['pro_arr']" id="pro">
                                <tr style="border: 0px;">
                                    <td style="font-size:12px;color: #666666;border-bottom:0px;padding:2px 0px 2px 5px">
                                        {pigcms{$pro}
                                    </td>
                                </tr>
                            </volist>
                            <volist name="info['dish']" id="dish">
                                <volist name="dish['list']" id="dish_one">
                                    <tr style="border: 0px;">
                                        <td style="font-size:12px;color: #666666;border-bottom:0px;padding:2px 0px 2px 5px">
                                            <php>
                                                if(strpos($dish_one,'*') !== false){
                                                    $dish_one = str_replace('*','<label style="color: red">*',$dish_one);
                                                    $dish_one .= '</label>';
                                                }
                                            </php>
                                            {pigcms{$dish_one}
                                        </td>
                                    </tr>
                                </volist>
                            </volist>
                        </table>
                    </td>
                    <td valign="top" class="cc">{pigcms{$info['price']|floatval}</td>
				</tr>
                </volist>
            </if>
            </tbody>
        </table>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
            <tbody>
                <tr>
                    <th></th>
                    <th class="cc"></th>
                    <th class="cc"></th>
                    <th class="rr">价格</th>
                </tr>
				<tr>
					<td>商品总价</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['goods_price']|floatval}</td>
				</tr>
                <tr>
                    <td>税费</td>
                    <td class="cc"></td>
                    <td class="cc"></td>
                    <td class="rr">${pigcms{:round($order['tax_price'],2)}</td>
                </tr>
                <if condition="$order['deposit_price'] gt 0">
                    <tr>
                        <td>Bottle Deposit</td>
                        <td class="cc"></td>
                        <td class="cc"></td>
                        <td class="rr">${pigcms{$order['deposit_price']|floatval}</td>
                    </tr>
                </if>
				<if condition="$order['freight_charge'] gt 0">
				<tr>
					<td>{pigcms{$store['freight_alias']|default='配送费'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['freight_charge']|floatval}</td>
				</tr>
				</if>
				<if condition="$order['packing_charge'] gt 0">
				<tr>
					<td>{pigcms{$store['pack_alias']|default='打包费'}</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr">${pigcms{$order['packing_charge']|floatval}</td>
				</tr>
				</if>
                <if condition="$order['tip_charge'] gt 0">
                    <!--tr>
                        <td>小费</td>
                        <td class="cc"></td>
                        <td class="cc"></td>
                        <td class="rr">${pigcms{$order['tip_charge']|floatval}</td>
                    </tr-->
                </if>
				<!--tr>
					<td>商家优惠</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">${pigcms{$order['merchant_reduce']|floatval}</span></td>
				</tr>
				<tr>
					<td>平台优惠</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price">-${pigcms{$order['coupon_price']|floatval}</span></td>
				</tr-->
				<tr>
					<td>总额</td>
					<td class="cc"></td>
					<td class="cc"></td>
					<td class="rr"><span class="price" style="color: #ffa52d">${pigcms{$order['price']+$order['tip_charge']|floatval}</span></td>
				</tr>
                <if condition="$order['paid'] eq 1 and $order['status'] eq 0">
                <tr>
                    <td>出餐时间</td>
                    <td class="cc">
                        <input type="text" name="dining_time" pattern="^[0-9]*$" data-err="Error" style="height: 2rem" value="20">
                    </td>
                    <td class="cc" style="text-align: left;">分钟</td>
                    <td class="rr"></td>
                </tr>
                <tr>
                    <td colspan="4" style="text-align: center">
                        <div class="submit_btn" style="background-color: #ffa64d" id="submit_div">
                            接单
                        </div>
                    </td>
                </tr>
                </if>
			</tbody>
		</table>
	</ul>
</div>
<script>
    var is_send = false;
    $('#submit_div').click(function () {
        if(is_send) return false;
        var time_val = $.trim($('input[name="dining_time"]').val());
        if(time_val == '' || !/^[0-9]*$/.test(time_val)){
            alert('请填写出餐时间');
            return false;
        }
        is_send = true;
        $(this).html('处理中……');
        $.post("{pigcms{:U('Storestaff/shop_order_confirm')}",{order_id:"{pigcms{$order['order_id']}",status:1,dining_time:time_val},function(result){
            is_send = false;
            if(result.status == 1){
                printOrderToAndroid(time_val);
                setTimeout(function () {
                    window.location.href = "{pigcms{:U('Storestaff/shop_list')}";
                },1000);
            }else{
                alert(result.info);
                window.location.reload();
            }
        },'json');
        return false;
    });

    if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()) || /(tuttipartner)/.test(navigator.userAgent.toLowerCase())){
        $('#print_order').show();
    }else{
        $('#print_order').hide();
    }

    function pushUserPrinter(status){
        if(status == '1'){
            if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()) || /(tuttipartner)/.test(navigator.userAgent.toLowerCase())){
                $('#print_order').show();
            }else{
                $('#print_order').hide();
            }
        }else{
            $('#print_order').hide();
        }
    }
    //Android
    if(typeof (window.linkJs) != 'undefined') {
        var is_use = window.linkJs.getUsePrinter();
        pushUserPrinter(is_use);
    }

    function isIntNum(val){
        var regPos = / ^\d+$/; // 非负整数
        var regNeg = /^\-[1-9][0-9]*$/; // 负整数
        if(regPos.test(val) || regNeg.test(val)){
            return true;
        }else{
            return false;
        }
    }

    function printOrderToAndroid(time_val){
        if(typeof (time_val) == "undefined" || !/^[0-9]*$/.test(time_val)){
            time_val = "0";
        }

        <?php
        $order_info = $order['info'];
        $i = 0;
        $info_str = "";
        foreach ($order_info as &$v){
            if($i > 0) $info_str .= "|";
            if(strpos($v['name'], "'") !== false) {
                $v['name'] = str_replace("'",'’',$v['name']);
            }
            if(strpos($v['spec'], "'") !== false) {
                $v['spec'] = str_replace("'",'’',$v['spec']);
            }
            $info_str .= $v['name']."#".$v['num']."#".$v['spec'];

            foreach ($v['spec_arr'] as& $ss){
                if(strpos($ss, "'") !== false) {
                    $ss = str_replace("'",'’',$ss);
                }
            }

            foreach ($v['pro_arr'] as& $pp){
                if(strpos($pp, "'") !== false) {
                    $pp = str_replace("'",'’',$pp);
                }
            }
            
            $dish_arr = "";
            if($v['dish']){
                $d_num = 0;
                foreach ($v['dish'] as &$dish_one) {
                    if (strpos($dish_one['name'], "'") !== false) {
                        $dish_one['name'] = str_replace("'", '’', $v['dish']['name']);
                    }

                    $dish_arr .= $d_num == 0 ? $dish_one['name'] . "@@" : "@%".$dish_one['name'] . "@@";
                    $c_num = 0;
                    foreach ($dish_one['list'] as &$d_one) {
                        if (strpos($d_one, "'") !== false) {
                            $d_one = str_replace("'", '’', $d_one);
                        }

                        $dish_arr .= $c_num == 0 ? $d_one : "%%" . $d_one;
                        $c_num++;
                    }

                    $d_num++;
                }

            }
            $info_str .= "#".$dish_arr;

            $v['dish_desc'] = $dish_arr;

            $i++;
        }

        $order_data = $order;

        if(strpos($order['username'], "'") !== false) {
            $order['username'] = str_replace("'",'’',$order['username']);
        }
        $order_data['username'] = $order['username'];

        if(strpos($order['address'], "'") !== false) {
            $order['address'] = str_replace("'",'’',$order['address']);
        }
        $order_data['address'] = $order['address'];

        if(strpos($order['last_staff'], "'") !== false) {
            $order['last_staff'] = str_replace("'",'’',$order['last_staff']);
        }
        $order_data['last_staff'] = $order['last_staff'];
        
        $order_data['info'] = "";
        $order_data['pay_status'] = "";
        $order_data['deliver_log_list'] = "";
        $order_data['deliver_info'] = "";
        $order_data['deliver_user_info'] = "";

        $shop['name'] = lang_substr($shop['name'],C('DEFAULT_LANG'));
        if(strpos($shop['name'], "'") !== false) {
            $shop['name'] = str_replace("'",'’',$shop['name']);
        }
        $order_data['store_name'] = $shop['name'];

        $order_data['store_phone'] = $shop['phone'];
        $order_data['pay_time_str'] = date("Y-m-d H:i:s",$order['pay_time']);
        if(strpos($order['desc'], "'") !== false) {
            $order['desc'] = str_replace("'",'’',$order['desc']);
        }
        $order_data['desc'] = $order['desc'] == "" ? "N/A" : $order['desc'];

        if (($order_data['expect_use_time'] - $order_data['pay_time'])>=3600){
            $order_data['expect_use_time'] = date("Y-m-d H:i:s",$order_data['expect_use_time']);
        }else{
            $order_data['expect_use_time'] = "ASAP";
        }

        $order_data['dining_time'] = $supply['dining_time'] ? $supply['dining_time'] : '0';
        ?>
        if(typeof (window.linkJs) != 'undefined'){
            if(/(tutti_android)/.test(navigator.userAgent.toLowerCase()))
                window.linkJs.printer_order('{pigcms{:json_encode($order_data)}','{pigcms{:json_encode($order_info)}',time_val);
        }
        if(/(tuttipartner)/.test(navigator.userAgent.toLowerCase())) {
            var orderDetail = "{pigcms{$order_data['real_orderid']}";
            orderDetail  += "|" + "{pigcms{$order_data['store_name']}";
            orderDetail  += "|" + "{pigcms{$order_data['store_phone']}";
            orderDetail  += "|" + "{pigcms{$order_data['pay_time_str']}";
            orderDetail  += "|" + "{pigcms{$order_data['desc']}";
            orderDetail  += "|" + "{pigcms{$order_data['expect_use_time']}";
            orderDetail  += "|" + "{pigcms{$order_data['username']}";
            orderDetail  += "|" + "{pigcms{$order_data['userphone']}";
            orderDetail  += "|$" + "{pigcms{$order['goods_price']|floatval}";

            if(time_val == "0")
                time_val = "{pigcms{$order_data['dining_time']}";

            var orderInfo = "{pigcms{$info_str}" ;

            window.webkit.messageHandlers.printer_order.postMessage([orderDetail, orderInfo, time_val]);
        }
    }

    $('#print_order').click(printOrderToAndroid);
    var new_img = "{pigcms{$static_path}images/new_order.png";
    var new_url = "{pigcms{:U('Storestaff/getNewOrder')}";
    var link_url = "{pigcms{:U('Storestaff/shop_list')}";
    var sound_url = "{pigcms{$static_public}sound/soft-bells.mp3";
</script>
    <script type="text/javascript" src="{pigcms{$static_path}js/new_order.js?v=2.1.0"></script>
<div class="footReturn">
	<div class="clr"></div>
	<div class="window" id="windowcenter">
		<div id="title" class="wtitle">操作成功<span class="close" id="alertclose"></span></div>
		<div class="content">
			<div id="txt"></div>
		</div>
	</div>
</div>



<!--include file="Storestaff:footer"/ -->