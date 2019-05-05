<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Merchant Center -- Add Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/diancai.css" rel="stylesheet" type="text/css" />
    <style>
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
        .cpbiaoge td{font-size:1rem;}
        input{
            width: 75%;
            height: 30px;
            font-size: 14px;
            border-radius: 0!important;
            color: #858585;
            background-color: #fff;
            border: 1px solid #d5d5d5;
            padding: 5px 4px 6px;
            font-size: 14px;
            -webkit-transition-duration: .1s;
            transition-duration: .1s;
        }
       select {
            text-indent: .1rem;
            line-height: 1;
            -webkit-appearance: none;
            background-color: #fff;
            border: 1px solid #d5d5d5;
            font-size: .3rem;
            width: 75%;
            height: 30px;
        }
    </style>
</head>
<body>


<div style="padding: 0.2rem;">
    <ul class="round">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
            <form id="add-shop-order" enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/confirm_order')}">
                <tbody>
                    <tr>
                        <td>Add Order</td>
                    </tr>
                    <tr>
                        <td>Name：<input type="text" name="name" value="{pigcms{$now_adress.name}" placeholder="Customer's Name"></td>
                    </tr>
                    <tr>
                        <td>Mobile：<input type="text" name="phone" value="{pigcms{$now_adress.phone}" placeholder="Mobile Number"></td>
                    </tr>
                    <tr>
                        <td>Province：
                            <select name="province">
                                <if condition="$now_adress">
                                    <volist name="province_list" id="vo">
                                        <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_adress['province']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                    </volist>
                                    <else/>
                                    <volist name="province_list" id="vo">
                                        <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_city_area['area_pid']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                    </volist>
                                </if>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>City：<select name="city">
                                <if condition="$now_adress">
                                    <volist name="city_list" id="vo">
                                        <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_adress['city']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                    </volist>
                                    <else/>
                                    <volist name="city_list" id="vo">
                                        <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_city_area['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                    </volist>
                                </if>
                            </select></td>
                    </tr>
                    <tr>
                        <td>Area：<select name="area">
                                <volist name="area_list" id="vo">
                                    <option value="{pigcms{$vo.area_id}"  <if condition="$vo['area_id'] eq $now_adress['area']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                </volist>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>Address：
                            <?php if(!empty($now_adress['adress'])){echo $now_adress['adress'];} ?>
                            <button type="button" id="color-gray" class="btn" style="background-color: #ffa64d;width: 100px;font-size: 12px"> <?php if(!empty($now_adress['adress'])){echo 'Select';}else{ echo 'Select';} ?></button>
                        </td>
                    </tr>
                    <tr>
                        <td>Note for driver：<input name="detail" value="{pigcms{$now_adress.detail}" type="text" placeholder="Note for driver"></td>
                    </tr>
                    <tr>
                        <td>Price：<input type="text" value="{pigcms{$now_adress.goods_price}" name="goods_price" placeholder="Total price befroe tax">
                            <input type="hidden" name="adress" value="{pigcms{$now_adress.adress}" style="width: 50%">
                            <input type="hidden" name="longitude" value="{pigcms{$now_adress.longitude}" >
                            <input type="hidden" name="latitude" value="{pigcms{$now_adress.latitude}" >
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tax：$<input type="text" value="" name="goods_tax" placeholder="Tax">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Bottle Deposit：<input type="text" value="" name="goods_deposit" placeholder="Deposit">
                        </td>
                    </tr>
                    <tr>
                        <td><button type="button" id="confirm_order" class="btn" style="text-align: center;width: 100%;margin-top: 20px;font-size: 14px;background-color: #ffa64d">Confirm</button></td>
                    </tr>
                </tbody>
            </form>
        </table>
    </ul>
    <a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;background-color: #ffa64d">Back</a>
</div>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script src="{pigcms{$static_path}js/jquery.cookie.js"></script>
<script src="{pigcms{$static_path}js/common_wap.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<script>
    $(function(){
        $("#color-gray").click(function(){
            var detail = new Object();
            detail.name = $('input[name="name"]').val();
            detail.phone = $('input[name="phone"]').val();
            detail.province = $('select[name="province"]').val();
            detail.area = $('select[name="area"]').val();
            detail.city = $('select[name="city"]').val();
            detail.detail = $('input[name="detail"]').val();
            detail.goods_price = $('input[name="goods_price"]').val();
            $.cookie("staff_address", JSON.stringify(detail));
//            console.log($.cookie("staff_address"));
            location.href = "{pigcms{:U('Storestaff/staff_order_map',$params)}";
        });
        $("#confirm_order").click(function() {
            if (!$('input[name="name"]').val()){
                alert("Customer's name is empty");return;
            }
            if (!$('input[name="phone"]').val()){
                alert('Mobile number is empty');return;
            }
            if (!$('input[name="adress"]').val()||!$('input[name="longitude"]').val()||!$('input[name="latitude"]').val()){
                alert("Customer's location is empty");return;
            }
            if (!$('input[name="detail"]').val()){
                alert("Note for deliver is empty,ex.unit number or front door...");return;
            }
            if (!$('input[name="goods_price"]').val()){
                alert('Total price is empty');return;
            }
            $("#add-shop-order").submit();
        });
    });

</script>

