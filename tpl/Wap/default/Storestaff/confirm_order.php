<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Merchant Center</title>
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
    </style>
</head>
<body>

<div style="padding: 0.2rem;">
    <ul class="round">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="cpbiaoge">
            <form enctype="multipart/form-data" method="post" action="{pigcms{:U('Storestaff/save_shop_oder')}">
                <tbody>
                <tr>
                    <td>Confirm Order</td>
                </tr>
                <tr>
                    <td>Name：<?php echo $post_data['name'] ?></td>
                </tr>
                <tr>
                    <td>Mobile：<?php echo $post_data['phone'] ?></td>
                </tr>
                <tr>
                    <td>Address：<?php echo $post_data['adress'] ?></td>
                </tr>
                <tr>
                    <td>Note for driver：<?php echo $post_data['detail'] ?></td>
                </tr>

                <tr>
                    <td>Merchant total：$<?php echo $return_data['goods_price'] ?></td>
                </tr>
                <tr>
                    <td>Tax：$<?php echo $return_data['goods_price_tax'] ?></td>
                </tr>
                <tr>
                    <td>Delivery fee：$<?php echo $return_data['freight_charge'] ?></td>
                </tr>
                <tr>
                    <td>Tax：$<?php echo $return_data['freight_charge_tax'] ?></td>
                </tr>
                <tr>
                    <td>Bottle Deposit：$<?php echo $return_data['deposit'] ?></td>
                </tr>
                <tr>
                    <td>Total price：$<?php echo $return_data['price'] ?></td>

                </tr>
                <tr>
                    <td><span style="color: red"></span></td>
                </tr>
                <input type="hidden" name="staff_id" value="<?php echo $return_data['staff_id'] ?>">
                <input type="hidden" name="store_id" value="<?php echo  $return_data['store_id'] ?>">
                <input type="hidden" name="mer_id" value="<?php echo $return_data['mer_id'] ?>">
                <input type="hidden" name="price" value="<?php echo $return_data['price'] ?>">
                <input type="hidden" name="goods_price" value="<?php echo $return_data['goods_price'] ?>">
                <input type="hidden" name="total_price" value="<?php echo $return_data['total_price'] ?>">
                <input type="hidden" name="freight_charge" value="<?php echo $return_data['freight_charge']?>">
                <input type="hidden" name="address_id" value="<?php echo $return_data['address_id'] ?>">
                <input type="hidden" name="desc" value="<?php echo  $return_data['desc'] ?>">
                <input type="hidden" name="username" value="<?php echo $post_data['name'] ?>">
                <input type="hidden" name="userphone" value="<?php echo $post_data['phone'] ?>">
                <input type="hidden" name="address" value="<?php echo $post_data['adress'] ?>">
                <input type="hidden" name="real_orderid" value="<?php echo $return_data['real_orderid']?>">
                <input type="hidden" name="discount_price" value="<?php echo $return_data['all_tax'] ?>">
                <input type="hidden" name="packing_charge" value="<?php echo $return_data['deposit'] ?>">
                <tr>
                    <td><button type="submit" class="btn" style="text-align: center;width: 100%;margin-top: 20px;font-size: 14px;background-color: #06c1ae;">Confirm</button></td>
                </tr>
                </tbody>
            </form>
        </table>

    </ul>
    <a href="{pigcms{:U('Storestaff/add_shop_order')}" class="btn" style="float:right;right:1rem;top:0.2rem;position:absolute;width:5rem;font-size:1rem;background-color: #06c1ae;">Back</a>
</div>


