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
</head>

<body>
<section class="public">
    <a class="return link-url" href="javascript:window.history.go(-1);"></a>
    <div class="content">订单状态</div>
</section>
<div class="h44"></div>
<section class="g_details">
    <div class="orders_list">
        <ul>
            <volist name="status" id="vo">
            <li>
                <div class="time">{pigcms{$vo.dateline|date="Y-m-d H:i",###}</div>
                <div class="p18">
                    <div class="con">
                        <if condition="$vo['status'] eq 0"> <h2>订单生成成功</h2> <p>订单编号：{pigcms{$order.real_orderid}</p>
                        <elseif condition="$vo['status'] eq 1"/> <h2>订单支付成功</h2> <p>订单编号：{pigcms{$order.real_orderid}</p>
                        <elseif condition="$vo['status'] eq 2"/> <h2>店员接单</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正在为您准备商品</p>
                        <elseif condition="$vo['status'] eq 3"/> <h2>配送员接单</h2> <p>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正在赶往店铺取货</p>
                        <elseif condition="$vo['status'] eq 4"/> <h2>配送员取货</h2> <p>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已取货，准备配送，请耐心等待</p>
                        <elseif condition="$vo['status'] eq 5"/> <h2>配送员配送中</h2> <p>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正快速向您靠拢，请耐心等待！</p>
                        <elseif condition="$vo['status'] eq 6"/> <h2>配送结束</h2> <p>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已完成配送，欢迎下次光临！</p>
                        <elseif condition="$vo['status'] eq 7"/>
                            <if condition="$order['is_pick_in_store'] eq 3"><h2>店员已发货</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已发货给快递公司<strong style="color:red">【{pigcms{$order['express_name']}】</strong>，快递单号:<strong style="color:green">{pigcms{$order['express_number']}</strong></p>
                            <else /><h2>店员验证消费</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>将订单改成已消费</p>
                            </if>
                        <elseif condition="$vo['status'] eq 8"/> <h2>完成评论</h2> <p>您已完成评论，谢谢您提出宝贵意见！</p>
                        <elseif condition="$vo['status'] eq 9"/> <h2>已完成退款</h2> <p>您已完成退款</p>
                        <elseif condition="$vo['status'] eq 10"/> <h2>已取消订单</h2> <p>您已经取消订单</p>
                        <elseif condition="$vo['status'] eq 11"/> <h2>商家分配自提点</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>给您分配</p>
                        <elseif condition="$vo['status'] eq 12"/> <h2>商家发货到自提点</h2> <p>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已经给您发货到配送点</p>
                        <elseif condition="$vo['status'] eq 13"/> <h2>自提点已接货</h2> <p>自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经接到您的货物了</p>
                        <elseif condition="$vo['status'] eq 14"/> <h2>自提点已发货</h2> <p>自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经给您发货了</p>
                        <elseif condition="$vo['status'] eq 15"/> <h2>您在自提点取货</h2> <p>您在自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经把您的货提走了！</p>
                        <elseif condition="$vo['status'] eq 30"/> <h2>店员为您修改了价格</h2> <p>店员<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已将订单的总价修改成{pigcms{$vo.note}</p>
                        </if>
                    </div>
                </div>
            </li>
            </volist>
        </ul>
    </div>
</section>
</body>
</html>