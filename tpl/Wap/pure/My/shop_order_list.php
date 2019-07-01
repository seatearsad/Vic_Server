<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_OUT_TXT_')} {pigcms{:L('_B_PURE_MY_63_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
    <style>
		dl.list dd dl{ padding-left:0;background-color: white;width: 98%;margin: 0px auto;}
		.dealcard-img{ margin-left:.2rem}
		dl.list dd{ border:none;}
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    .dealcard-block-right {
	        height: 1.5rem;
	    }
	    .dealcard .dealcard-brand {
			heigth:.5rem;
            font-size:1.3em;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: .15rem;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }
	    .orderindex li {
	        display: inline-block;
	        width: 25%;
	        text-align:center;
	        position: relative;
            color: #999999;
            font-size: 1.2em;
	    }

		.orderindex li.active {
            font-weight: bold;
	    }

	    .orderindex li .react {
	        padding: .28rem 0;
	    }
	    .orderindex .text-icon {
	        display: block;
	        font-size: .4rem;
	        margin-bottom: .18rem;
	    }
	    .orderindex .amount-icon {
	        position: absolute;
	        left: 50%;
	        top: .16rem;
	        color: white;
	        background: #EC5330;
	        border-radius: 50%;
	        padding: .08rem .06rem;
	        min-width: .28rem;
	        font-size: .24rem;
	        margin-left: .1rem;
	        display: none;
	    }
	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
	    .orderindex li .react.hover{
	    	color:#FF658E;
	    }
		.tabs {
		  z-index: 15px;
		  position: relative;
		  box-shadow: 0 0 30px rgba(0, 0, 0, 0.1);
		  box-sizing: border-box;
		  overflow: hidden;
		}
		.tabs-header {
		  position: relative;
		  overflow: hidden;
		}
		.tabs-header .border {
		  position: absolute;
		  bottom: 0;
		  left: 0;
		  background: #06c1bb;
		  width: auto;
		  height: 2px;
		  width:25%;
		  -webkit-transition: 0.3s ease;
				  transition: 0.3s ease;
		}

		.tabs-content .tab {
		  display: none;
		}
		.tabs-content .tab.active {
		  display: block;
		}

		.order-num{ height:.6rem; line-height:.6rem; color:#9E9E9E; padding-left:.2rem}
		.order-num a{ display:block; float:right; margin-right:.1rem}
		.order-num-foot span:first-child{display:block; float:left; color:#06c1bb; line-height:1rem}
		.order-num-foot span:last-child,.order-pay,.order-cancel{border:1px solid #e5e5e5;color:#666; padding:.2rem; border-radius:4px; display:block; float:right;line-height:.3rem; margin:.1rem .1rem 0 0; }
		.order-num-foot span.order-pay{ color:#06c1bb;padding:.2rem .5rem}
        .main{
            width: 100%;
            padding-top: 60px;
        }
        dl.list{
            background-color: transparent;
            border: 0px;
        }
        .gray_line{
            width: 100%;
            height: 2px;
            background-color: #cccccc;
        }
        .gray_k{
            width: 10%;
            height: 2px;
            background-color: #f4f4f4;
            margin: -2px auto 0 auto;
        }
        #ord_num{
            padding-left: 20px;
            background-image: url("./tpl/Static/blue/images/wap/order_num.png");
            background-repeat: no-repeat;
            background-size: auto 100%;
        }
        .dealcard .dealcard-img{
            width: 2.4rem;
            height: 1.5rem;
        }
        .dealcard .dealcard-img img{
            margin-left: 0px;
        }
        .dealcard .dealcard-block-right {
            margin-left: 2.8rem;
            margin-right: 1.2rem;
        }
        .total_price{
            color: #ffa52d;
        }
        .order_btn{
            position: absolute;
            right: .2rem;
            top: .3rem;
            width: 1.3rem;
            height: .5rem;
            line-height: .5rem;
            text-align: center;
            border-radius: .05rem;
            color: white;
            background-color: #ffa52d;
        }
        .order_btn span{
            display: block;
        }
	</style>
</head>
<body id="index">
<include file="Public:header"/>
<div class="main">
        <div id="tips" class="tips"></div>
		<dl class="list" style="margin-top:0px;">
		    <dd>
			<div class="tabs">
			<div class="tabs-header">
				<ul class="orderindex">
					<li class="active"><a href="javascript:void(0)" tab-id="1" class="react">
						<span>{pigcms{:L('_B_PURE_MY_64_')}</span>
					</a>
					</li><li data-status='-1'><a href="javascript:void(0)" tab-id="2" class="react ">
						<span>{pigcms{:L('_B_PURE_MY_65_')}</span>
					</a>
					</li><li data-status='1'><a href="javascript:void(0)" tab-id="3" class="react ">
						<span>{pigcms{:L('_B_PURE_MY_66_')}</span>
					</a>
					</li><li data-status='2'><a href="javascript:void(0)" tab-id="4" class="react " >
						<span>{pigcms{:L('_B_PURE_MY_67_')}</span>
					</a>
					</li>
				</ul>
				</div></div>
			</dd>
		</dl>
        <div class="gray_line"></div>
        <div class="gray_k"></div>
		<div style="margin-top:.2rem;">
		    <dl class="list tabs-content" id="orders">
				<div tab-id="1" class="tab active">

				<volist name="order_list" id="order">
					<dd>
						<dl>
							<dd class="order-num"><span id="ord_num">{pigcms{$order.real_orderid}</span>
							<if condition="$order['paid'] eq 0">
							<a href="javascript::void(0)" onclick="del_order({pigcms{$order['order_id']})"><img src="{pigcms{$static_path}images/u282.png"></a>
							</if>
                            <!--<if condition="in_array($order['status'],array(2,3))">
							<a href="javascript::void(0)" onclick="del_order({pigcms{$order['order_id']})"><img src="{pigcms{$static_path}images/u282.png"></a>
							</if>-->
							</dd>
							<dd class="dealcard dd-padding">
									<div class="dealcard-img imgbox" onclick="window.location.href = '{pigcms{$order.order_url}';">
										<img src="{pigcms{$order.image}" style="width:100%;height:100%;"/>
									</div>
									<div class="dealcard-block-right" onclick="window.location.href = '{pigcms{$order.order_url}';">
										<div class="dealcard-brand single-line">{pigcms{$order.name}</div>
                                        <div class="total_price">Total:${pigcms{$order['price']|floatval}</div>
										<small>Total item:{pigcms{$order.num}&nbsp;&nbsp;{pigcms{:date('Y-m-d',$order['create_time'])} </small>
									</div>
                                    <div class="order_btn">
                                        <if condition="empty($order['paid']) AND ($order['status'] lt 2 OR $order['status'] eq 7)" >
                                            <span onclick="location.href='{pigcms{:U('Pay/check',array('type'=>'shop','order_id'=>$order['order_id']))}'">{pigcms{:L('_B_PURE_MY_81_')}</span>
                                            <elseif condition="$order['status'] == 2"/>
                                            <span onclick="location.href='{pigcms{:U('My/shop_feedback',array('order_id'=>$order['order_id']))}'">{pigcms{:L('_B_PURE_MY_82_')}</span>
                                            <else />
                                            <if condition="$order['status'] eq 0">
                                                <span>{pigcms{:L('_B_PURE_MY_71_')}</span>
                                                <elseif condition="$order['status'] eq 1" />
                                                <if condition="$deliver">
                                                    <span onclick='location.href="{pigcms{:U('My/order_track',array('order_id'=>$order['order_id']))}"'>Track</span>
                                                    <else />
                                                    <span>{pigcms{:L('_B_PURE_MY_72_')}</span>
                                                </if>
                                                <elseif condition="$order['status'] eq 2" />
                                                <span href="{pigcms{:U('My/shop_feedback',array('order_id'=>$order['order_id']))}">{pigcms{:L('_B_PURE_MY_73_')}</span>
                                                <elseif condition="$order['status'] eq 3" />
                                                <span>{pigcms{:L('_B_PURE_MY_74_')}</span>
                                                <elseif condition="$order['status'] eq 4" />
                                                <span>{pigcms{:L('_B_PURE_MY_75_')}</span>
                                                <elseif condition="$order['status'] eq 5" />
                                                <span>{pigcms{:L('_B_PURE_MY_76_')}</span>
                                                <elseif condition="$order['status'] eq 6" />
                                                <elseif condition="$order['status'] eq 7" />
                                                <span>{pigcms{:L('_B_PURE_MY_77_')}</span>
                                                <elseif condition="$order['status'] eq 8" />
                                                <span>{pigcms{:L('_B_PURE_MY_78_')}</span>
                                                <elseif condition="$order['status'] eq 9" />
                                                <span>{pigcms{:L('_B_PURE_MY_79_')}</span>
                                                <elseif condition="$order['status'] eq 10" />
                                                <span>{pigcms{:L('_B_PURE_MY_80_')}</span>
                                            </if>
                                        </if>
                                    </div>
								</dd>
							<!--dd class="order-num order-num-foot">
								<if condition="$order['status'] eq 0">
									<span>{pigcms{:L('_B_PURE_MY_71_')}</span>
								<elseif condition="$order['status'] eq 1" />
									<span>{pigcms{:L('_B_PURE_MY_72_')}</span>
                                    <if condition="$deliver">
                                        <span onclick='location.href="{pigcms{:U('My/order_track',array('order_id'=>$order['order_id']))}"'>Track</span>
                                    </if>
								<elseif condition="$order['status'] eq 2" />
									<span href="{pigcms{:U('My/shop_feedback',array('order_id'=>$order['order_id']))}">{pigcms{:L('_B_PURE_MY_73_')}</span>
								<elseif condition="$order['status'] eq 3" />
									<span>{pigcms{:L('_B_PURE_MY_74_')}</span>
								<elseif condition="$order['status'] eq 4" />
									<span>{pigcms{:L('_B_PURE_MY_75_')}</span>
								<elseif condition="$order['status'] eq 5" />
									<span>{pigcms{:L('_B_PURE_MY_76_')}</span>
								<elseif condition="$order['status'] eq 6" />
								<elseif condition="$order['status'] eq 7" />
									<span>{pigcms{:L('_B_PURE_MY_77_')}</span>
								<elseif condition="$order['status'] eq 8" />
									<span>{pigcms{:L('_B_PURE_MY_78_')}</span>
								<elseif condition="$order['status'] eq 9" />
									<span>{pigcms{:L('_B_PURE_MY_79_')}</span>
								<elseif condition="$order['status'] eq 10" />
									<span>{pigcms{:L('_B_PURE_MY_80_')}</span>
								</if>

								<if condition="empty($order['paid']) AND ($order['status'] lt 2 OR $order['status'] eq 7)" >
									<span onclick="location.href='{pigcms{:U('Pay/check',array('type'=>'shop','order_id'=>$order['order_id']))}'" class="order-pay">{pigcms{:L('_B_PURE_MY_81_')}</span>
								<elseif condition="$order['status'] == 2"/>
									<span onclick="location.href='{pigcms{:U('My/shop_feedback',array('order_id'=>$order['order_id']))}'">{pigcms{:L('_B_PURE_MY_82_')}</span>
								</if>
							</dd-->
						</dl>
					</dd>
					<div style=" height:10px; background:#f0efed"></div>
				</volist>
				</div>
			<div tab-id="2" class="tab"></div>
			<div tab-id="3" class="tab"></div>
			<div tab-id="4" class="tab"></div>
		    </dl>
		</div>

		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" language="javascript">
			var activePos = $('.tabs-header .active').position();
			var url = "{pigcms{:U('ajax_shop_order_list')}"
			function changePos() {
				activePos = $('.tabs-header .active').position();
				$('.border').stop().css({
					left: activePos.left,
					width: $('.tabs-header .active').width()
				});
			}
			changePos();
			var tabHeight = $('.tab.active').height();
			function animateTabHeight() {
				tabHeight = $('.tab.active').height();
				$('.tabs-content').stop().css({ height: tabHeight + 'px' });
			}

			var tabItems = $('.tabs-header ul li');
			var tabCurrentItem = tabItems.filter('.active');
			$('.tabs-header a').on('click', function (e) {
				e.preventDefault();
				var tabId = $(this).attr('tab-id');

				$('.tabs-header a').stop().parent().removeClass('active');
				$(this).stop().parent().addClass('active');
				changePos();
				tabCurrentItem = tabItems.filter('.active');
				$('.tab').stop().fadeOut(300, function () {
					$(this).removeClass('active');
				}).hide();
				$('.tab[tab-id="' + tabId + '"]').stop().fadeIn(300, function () {
					var status = $('.tabs-header ul li.active').data('status');
					$.get(url,{'status':status,'store_id':{pigcms{$_GET.store_id|intval}},function(data){
						if(data.status){
							var shtml = '<dd>';
							var order_list = data['order_list'];
							for(var i in order_list){
								shtml += '<dl><dd class="order-num"><span id="ord_num">'+order_list[i]["real_orderid"]+'</span>';
								if((order_list[i]['paid'] == 0)){
									shtml +='<a href="javascript:void(0)" onclick="del_order('+order_list[i]["order_id"]+')"><img src="{pigcms{$static_path}images/u282.png"></a>';
								}
								if((order_list[i]['status']==2) || (order_list[i]['status']==3)){
									shtml +='<a href="javascript:void(0)" onclick="del_order('+order_list[i]["order_id"]+')"><img src="{pigcms{$static_path}images/u282.png"></a>';
								}
								shtml += '</dd>';
								shtml += '<dd class="dealcard dd-padding">';
								shtml += '<div class="dealcard-img imgbox" onclick="window.location.href = \''+order_list[i]['order_url']+'\';">';
								shtml += '<img src="'+order_list[i]['image']+'" style="width:100%;height:100%;"/>';
								shtml += '</div>';
								shtml += '<div class="dealcard-block-right"  onclick="window.location.href = \''+order_list[i]['order_url']+'\';">';
								shtml += '<div class="dealcard-brand single-line">'+order_list[i]['name']+'</div>';
								shtml += '<div class="total_price">Total:'+order_list[i]['price']+'</div>'
								shtml += '<small>Total item:'+order_list[i]['num']+'&nbsp;&nbsp;'+order_list[i]['create_time']+'</small>';
                                shtml += '</div>';

                                shtml += '<div class="order_btn">';
                                if(order_list[i]['paid'] == 0 && (order_list[i]['status'] < 2 || order_list[i]['status'] == 7)){
                                     var url = "{pigcms{:U('Pay/check')}";
                                     url += '&type=shop&order_id='+order_list[i]['order_id'];
                                     shtml +='<span onclick="location.href=\''+url+'\'">{pigcms{:L('_B_PURE_MY_81_')}</span>';
                                 }else if(order_list[i]['status'] == 2){
                                     var url = "{pigcms{:U('My/shop_feedback')}";
                                     url += '&order_id='+order_list[i]['order_id'];
                                     shtml +='<span onclick="location.href=\''+url+'\'">{pigcms{:L('_B_PURE_MY_82_')}</span>';
                                 }else{
                                    if(order_list[i]['status']==0){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_71_')}</span>';
                                    }else if(order_list[i]['status']==1){
                                        if(order_list[i]['deliver']){
                                            var url = "{pigcms{:U('My/order_track')}";
                                            url += '&order_id='+order_list[i]['order_id'];
                                            shtml +='<span onclick="location.href=\''+url+'\'">Track</span>';
                                        }else{
                                            shtml += '<span>{pigcms{:L('_B_PURE_MY_72_')}</span>';
                                        }
                                    }else if(order_list[i]['status']==2){
                                        var url = "{pigcms{:U('My/shop_feedback')}";
                                        url +='&order_id='+order_list[i]['order_id'];
                                        shtml +='<span onclick="location.href=\''+url+'\'">{pigcms{:L('_B_PURE_MY_73_')}</span>';
                                    }else if(order_list[i]['status']==3){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_74_')}</span>';
                                    }else if(order_list[i]['status']==4){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_75_')}</span>';
                                    }else if(order_list[i]['status']==5){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_76_')}</span>';
                                    }else if(order_list[i]['status']==6){
                                    }else if(order_list[i]['status']==7){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_77_')}</span>';
                                    }else if(order_list[i]['status']==8){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_78_')}</span>';
                                    }else if(order_list[i]['status']==9){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_79_')}</span>';
                                    }else if(order_list[i]['status']==10){
                                        shtml += '<span>{pigcms{:L('_B_PURE_MY_80_')}</span>';
                                    }
                                 }

								shtml += '</div></dd>';

// 								var url = "{pigcms{:U('My/shop_order_refund')}";
// 								url +='&order_id='+order_list[i]['order_id']+'&mer_id='+order_list[i]['mer_id']+'&store_id='+order_list[i]['store_id'];

                                // shtml += '<dd class="order-num order-num-foot">	';
								// if(order_list[i]['status']==0){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_71_')}</span>';
								// }else if(order_list[i]['status']==1){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_72_')}</span>';
								// }else if(order_list[i]['status']==2){
								// 	var url = "{pigcms{:U('My/shop_feedback')}";
								// 	url +='&order_id='+order_list[i]['order_id'];
								// 	shtml +='<span onclick="location.href=\''+url+'\'">{pigcms{:L('_B_PURE_MY_73_')}</span>';
								// }else if(order_list[i]['status']==3){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_74_')}</span>';
								// }else if(order_list[i]['status']==4){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_75_')}</span>';
								// }else if(order_list[i]['status']==5){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_76_')}</span>';
								// }else if(order_list[i]['status']==6){
								// }else if(order_list[i]['status']==7){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_77_')}</span>';
								// }else if(order_list[i]['status']==8){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_78_')}</span>';
								// }else if(order_list[i]['status']==9){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_79_')}</span>';
								// }else if(order_list[i]['status']==10){
								// 	shtml += '<span>{pigcms{:L('_B_PURE_MY_80_')}</span>';
								// }
                                //
                                //
								//  if(order_list[i]['paid'] == 0 && (order_list[i]['status'] < 2 || order_list[i]['status'] == 7)){
								// 	 var url = "{pigcms{:U('Pay/check')}";
								// 	 url += '&type=shop&order_id='+order_list[i]['order_id'];
								// 	 shtml +='<span onclick="location.href=\''+url+'\'" class="order-pay">{pigcms{:L('_B_PURE_MY_81_')}</span>';
								//  }else if(order_list[i]['status'] == 2){
								// 	 var url = "{pigcms{:U('My/shop_feedback')}";
								// 	 url += '&order_id='+order_list[i]['order_id'];
								// 	 shtml +='<span onclick="location.href=\''+url+'\'">{pigcms{:L('_B_PURE_MY_82_')}</span>';
								//  }else{
								// 	 shtml+='<a></a>';
								//  }
								// shtml +='</dd>';
                                shtml +='</dl><div style=" height:10px; background:#f0efed"></div>';
							}
						}else{
							var shtml ='<dd><dd class="dealcard dd-padding" style=" text-align:center; background:#fff; width:100%">{pigcms{:L('_B_PURE_MY_83_')}</dd></dd>';
						}
						$('.tab[tab-id="' + tabId + '"]').html(shtml);
					},'json')
				});
			});


			function del_order(order_id){
				if(!order_id){
					return false;
				}



				layer.open({
				content:"{pigcms{:L('_B_PURE_MY_84_')}",
				btn: ["{pigcms{:L('_B_PURE_MY_85_')}","{pigcms{:L('_B_PURE_MY_86_')}"],
				yes:function(){
                   var del_url = "{pigcms{:U('ajax_shop_order_del')}";
					$.get(del_url,{'order_id':order_id},function(data){
						if(data['status']){
							location.reload();
						}
					},'json');
				}
			});

			}
		</script>
</div>
<include file="Public:footer"/>
</body>
</html>