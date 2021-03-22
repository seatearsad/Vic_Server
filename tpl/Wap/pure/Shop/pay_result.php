<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <title>{pigcms{$storeName.name}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
    <link href="{pigcms{$static_path}shop/css/order_detail.css" rel="stylesheet"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
</head>
<style>
    .main{
        width: 100%;
        padding-top: 60px;
        max-width: 640px;
        min-width: 320px;
        margin: 0 auto;
    }
    .gray_line{
        width: 100%;
        height: 2px;
        margin-top: 15px;
        background-color: #cccccc;
    }
    .g_details{
        padding:10px 10px;
    }
    .div_box{
        background: white;
        border-radius: 10px;
        padding: 15px 20px;
        margin-bottom: 20px;
    }
    .div_small_title{
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 20px;
        margin-top:20px;
    }
    .div_title{
        text-align: center;
        font-size: 20px;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .div_content{

    }
    .div_button{
        width: 100%;
        background: #ffa52d;
        margin: 20px 0px 10px 0;
        padding: 10px 20px;
        border-radius: 20px;
        text-align: center;
        color: white;
        font-size: 16px;
    }

</style>
<body>
<include file="Public:header"/>
<div class="main">
<section class="g_details">
    <div class="div_box">
        <if condition="$status eq '3'">
            <div class="div_small_title">loading...</div>
            <script>
                window.location.href="./wap.php?g=Wap&c=My&a=shop_order_list";
            </script>
        <else/>

                <div class="div_title">
                    <if condition="$status eq '1'">{pigcms{:L('V3_ORDER_RESULT_PAYMENT_SUCC')}<else/>{pigcms{:L('V3_ORDER_RESULT_PAYMENT_FAIL')}</if>
                </div>
                <div class="div_content">
                    <if condition="$status eq '1'">
                        Order #{pigcms{$order['real_orderid']} from {pigcms{$store['name']}
                    <else/>
                    {pigcms{:L('V3_ORDER_RESULT_PAYMENT_FAIL_DESC')}
                    </if>
                </div>

                <if condition="$status eq '1'">
                    <a href=".//wap.php?g=Wap&c=Shop&a=order_detail&order_id={pigcms{$order_id}"><div class="div_button">{pigcms{:L('V3_ORDER_RESULT_PAYMENT_VIEW_ORDER')}</div></a>
                    <else/>
                    <a href=".//wap.php?g=Wap&c=Pay&a=check&order_id={pigcms{$order_id}&type=shop"><div class="div_button">{pigcms{:L('V3_ORDER_RESULT_PAYMENT_CONTINUE')}</div></a>
                </if>

        </if>
    </div>
</section>
</div>
</body>
</html>