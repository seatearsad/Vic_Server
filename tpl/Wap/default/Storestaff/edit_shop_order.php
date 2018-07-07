<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>店员中心</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
            <form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/confirm_order')}">
                <tbody>
                <tr>
                    <td>修改订单</td>
                </tr>
                <tr>
                    <td>姓名：<input type="text" name="name" value="<?php echo $user_adress['name'] ?>"></td>
                </tr>
                <tr>
                    <td>电话：<input type="text" name="phone" value="<?php echo $user_adress['phone'] ?>"></td>
                </tr>
                <tr>
                    <td>省份：<select name="province">
                            <volist name="province_list" id="vo">
                                <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_city_area['area_pid']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                            </volist>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>城市：<select name="city">
                            <volist name="city_list" id="vo">
                                <option value="{pigcms{$vo.area_id}" <if condition="$vo['area_id'] eq $now_city_area['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                            </volist>
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
                    <td>坐标：
                        <input type="text" style="width: 50%"> &nbsp;<button type="button" class="btn" style="background-color: #06c1bb;">点击选择</button></td>
                </tr>
                <tr>
                    <td>坐标：<input name="longitude" type="text"> <input name="latitude" type="text"></td>
                </tr>
                <tr>
                    <td>位置：<input name="adress" type="text"  placeholder="请输入客户位置"></td>
                </tr>
                <tr>
                    <td>地址：<input name="detail" type="text" placeholder="请填写详细的地址和门牌号"></td>
                </tr>
                <tr>
                    <td>总价：<input type="text" name="goods_price"></td>
                </tr>
                <tr>
                    <td><button type="submit" class="btn" style="text-align: center;width: 100%;margin-top: 20px;font-size: 14px;">确认修改</button></td>
                </tr>
                </tbody>
            </form>
        </table>

    </ul>
    <a href="{pigcms{:U('Storestaff/shop_list')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;">返 回</a>
</div>


