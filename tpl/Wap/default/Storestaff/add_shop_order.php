<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>店员中心--下单</title>
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
                        <td>添加订单</td>
                    </tr>
                    <tr>
                        <td>姓名：<input type="text" name="name" value="{pigcms{$now_adress.name}" placeholder="请输入客户姓名"></td>
                    </tr>
                    <tr>
                        <td>电话：<input type="text" name="phone" value="{pigcms{$now_adress.phone}" placeholder="请输入客户电话"></td>
                    </tr>
                    <tr>
                        <td>省份：
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
                        <td>城市：<select name="city">
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
                        <td>区县：<select name="area">
                                <volist name="area_list" id="vo">
                                    <option value="{pigcms{$vo.area_id}"  <if condition="$vo['area_id'] eq $now_adress['area']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                </volist>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>位置：
                            <?php if(!empty($now_adress['adress'])){echo $now_adress['adress'];} ?>
                            <button type="button" id="color-gray" class="btn" style="background-color: #06c1bb;"> <?php if(!empty($now_adress['adress'])){echo '重新选择';}else{ echo '选择位置';} ?></button>
                        </td>
                    </tr>
                    <tr>
                        <td>地址：<input name="detail" value="{pigcms{$now_adress.detail}" type="text" placeholder="请填写详细的地址和门牌号"></td>
                    </tr>
                    <tr>
                        <td>总价：<input type="text" value="{pigcms{$now_adress.goods_price}" name="goods_price" placeholder="请输入商品总价">
                            <input type="hidden" name="adress" value="{pigcms{$now_adress.adress}" style="width: 50%">
                            <input type="hidden" name="longitude" value="{pigcms{$now_adress.longitude}" >
                            <input type="hidden" name="latitude" value="{pigcms{$now_adress.latitude}" >
                        </td>
                    </tr>
                    <tr>
                        <td><button type="button" id="confirm_order" class="btn" style="text-align: center;width: 100%;margin-top: 20px;font-size: 14px;">确认订单</button></td>
                    </tr>
                </tbody>
            </form>
        </table>
    </ul>
    <a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">返 回</a>
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
                alert('请输入客户姓名');return;
            }
            if (!$('input[name="phone"]').val()){
                alert('请输入客户电话');return;
            }
            if (!$('input[name="adress"]').val()||!$('input[name="longitude"]').val()||!$('input[name="latitude"]').val()){
                alert('请选择客户位置');return;
            }
            if (!$('input[name="detail"]').val()){
                alert('请输入客户详细地址');return;
            }
            if (!$('input[name="goods_price"]').val()){
                alert('请输入商品总价');return;
            }
            $("#add-shop-order").submit();
        });
    });

</script>

