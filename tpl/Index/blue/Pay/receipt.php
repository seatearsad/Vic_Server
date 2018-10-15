<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>{pigcms{:L('_PAYMENT_CONFIRM_')} - {pigcms{:L('_VIC_NAME_')}</title>
    <meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    <meta name="description" content="{pigcms{$config.seo_description}" />
</head>
<style>
    *{
        margin: 0 auto;
        font-size: 14px;
    }
    .line{
        width: 100%;
        border-bottom: 1px #cccccc solid;
        margin-top: 20px;
        margin-bottom: 20px;
    }
    .desc{
        font-size: 12px;
        color: #666666;
        margin-top: 5px;
        margin-bottom: 10px;
    }
    .div_desc{
        margin-bottom: 10px;
        color: #666666;
    }
</style>
<body>
    <div style="width: 90%;border:1px #333333 solid;margin-top: 10px;padding: 10px;margin-bottom: 10px;">
        <div style="text-align: center; font-size: 16px;">
            ORDER (#{pigcms{$order.real_orderid})
        </div>
        <div class="line"></div>
        <div>
            <volist name="order['info']" id="vo">
            <div>
                <span style="font-weight: bold">{pigcms{$vo.name} * {pigcms{$vo.num}</span>
                <span style="float: right">${pigcms{$vo.price}</span>
            </div>
            <div class="desc">
                {pigcms{$vo.spec}
            </div>
            </volist>
        </div>
        <div class="line"></div>
        <div>
            <div class="div_desc">
                <span>{pigcms{:L('_TOTAL_COMM_PRICE_')}</span>
                <span style="float: right">${pigcms{$order.goods_price}</span>
            </div>
            <div class="div_desc">
                <span>{pigcms{:L('_DELI_PRICE_')}</span>
                <span style="float: right">${pigcms{$order.freight_charge}</span>
            </div>
            <div class="div_desc">
                <span>{pigcms{:L('_PACK_PRICE_')}</span>
                <span style="float: right">${pigcms{$order.packing_charge}</span>
            </div>
            <div class="div_desc">
                <span>{pigcms{:L('_TAXATION_TXT_')}</span>
                <span style="float: right">5%</span>
            </div>
            <div class="div_desc">
                <span>{pigcms{:L('_TIP_TXT_')}</span>
                <span style="float: right">${pigcms{$order.tip_charge}</span>
            </div>
        </div>
        <div class="line"></div>
        <div>
            <if condition="$order['coupon_price'] neq 0">
                <div class="div_desc">
                    <span>{pigcms{:L('_PLATFORM_DIS_')}</span>
                    <span style="float: right"> - ${pigcms{$order.coupon_price}</span>
                </div>
            </if>
            <div class="div_desc" style="font-weight: bold;color: #000000">
                <span>{pigcms{:L('_B_PURE_MY_70_')}</span>
                <span style="float: right">${pigcms{$order['price'] - $order['coupon_price'] + $order['tip_charge']}</span>
            </div>
        </div>
        <div class="line"></div>
        <div>
            <div class="div_desc">
                <span>Paid with</span>
                <span style="float: right">Credit Card</span>
            </div>
            <div class="div_desc">
                <span>Date</span>
                <span style="float: right">{pigcms{$order.transDate} {pigcms{$order.transTime}</span>
            </div>
            <div class="div_desc">
                <span>Amount</span>
                <span style="float: right">${pigcms{$order.transAmount}</span>
            </div>
            <div class="div_desc">
                <span>Transaction Type</span>
                <span style="float: right">Purchase</span>
            </div>
            <div class="div_desc">
                <span>Reference Number</span>
                <span style="float: right">{pigcms{$order.referenceNum}</span>
            </div>
            <div class="div_desc">
                <span>Authorization Code</span>
                <span style="float: right">{pigcms{$order.authCode}</span>
            </div>
            <div class="div_desc">
                <span>Response/ISO Code</span>
                <span style="float: right">{pigcms{$order.responseCode}/{pigcms{$order.ISO}</span>
            </div>
            <div class="div_desc">
                <span>Message</span>
                <span style="float: right">{pigcms{$order.message}</span>
            </div>
        </div>
    </div>
</body>
</html>