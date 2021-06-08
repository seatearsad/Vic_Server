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
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/pageloader.css?217"/>
    <script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>

    <style>
		dl.list dd dl{
            padding-left:0;
            background-color: white;
            width: 100%;
            border-radius: 10px;
            margin: 5px 0 5px;
        }
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
	        width: 49%;
	        text-align:center;
	        position: relative;
            color: #999999;
            font-size: 1.2em;
	    }

		.orderindex li.active {
            font-weight: bold;
            color:#ffa52d;
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

        dl.list .order-num {
            height: .9rem;
            line-height: .6rem;
            color: black;
            padding-left: .2rem;
            border-bottom: 1px solid #e3e3e3;
            padding-top: .12rem;
            background: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 12px;
            background-repeat: no-repeat;
            background-position: right 10px center;
            margin-right: 1px;
        }

        dl.list .order-num a {
            display: block;
            float: right;
            margin-right: .1rem
        }

        .order-num-foot span:first-child {
            display: block;
            float: left;
            color: #06c1bb;
            line-height: 1rem
        }

        .order-num-foot span:last-child, .order-pay, .order-cancel {
            border: 1px solid #e5e5e5;
            color: #666;
            padding: .2rem;
            border-radius: 4px;
            display: block;
            float: right;
            line-height: .3rem;
            margin: .1rem .1rem 0 0;
        }

        .order-num-foot span.order-pay {
            color: #06c1bb;
            padding: .2rem .5rem
        }

        .main {
            width: 100%;
            padding-top: 60px;
            max-width: 640px;
            min-width: 320px;
            margin: 0 auto;
        }
        dl.list{
            background-color: transparent;
            border: 0px;
        }
        .gray_line{
            width: 100%;
            height: 0px;
        }
        .gray_k{
            width: 10%;
            height: 2px;
            background-color: #f4f4f4;
            margin: -2px auto 0 auto;
        }
        #ord_num{
            padding-left: 0px;
            /*background-image: url("./tpl/Static/blue/images/wap/order_num.png");*/
            background-repeat: no-repeat;
            background-size: auto 100%;
            font-size: 16px;
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
            color: #000;
            font-weight: bold;
        }
        .go_btn{
            position: absolute;
            right: .2rem;
            top: .3rem;
            width: 1.3rem;
            height: 1.3rem;
            line-height: .5rem;
            text-align: center;
            border-radius: .05rem;
            color: white;
            background-color: #ffa52d;
            background: url("./tpl/Static/blue/images/new/new_black_arrow.png");
            background-size: auto 16px;
            background-repeat: no-repeat;
            background-position: right center;
        }
        .order_btn span{
            display: block;
        }
        .button_block{
            padding: 5px 5px 5px 10px;
            display: flex;
            height: 40px;
        }
        .round_button{
            border-radius: 5px;
            background: #ffa52d;
            padding: 4px 8px;
            color: #fff;
            margin-right: 5px;
            margin-left: 5px;
        }
        .img_ratings{
            width: 80px;
            height: 20px;
            background: url("./tpl/Static/blue/images/new/ic_rating_one.png ");
            background-size: auto 16px;
            background-repeat: repeat-x;
            background-position: left center;
            margin-top: 2px;
        }
        .status_str{
            right: 20px;
            position: absolute;
            line-height: 1.9;
        }
        .use_main_color{
            color:#ffa52d;
        }
        dl.list .dealbutton{
            padding: 0 0 10px 0;
        }
        dl.list .dd-padding, dl.list dt, dl.list dd > .react {
            padding: .18rem .2rem;
            padding-left: 0.2rem;
        }

	</style>
    <include file="Public:facebook"/>
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
                    <li data-status='6'>
                        <a id="secondclick" href="javascript:void(0)" tab-id="6" class="react">
                            <span>{pigcms{:L('_B_PURE_MY_96_')}</span>
                        </a>
                    </li>
                    <li class="active" data-status='3'>
                        <a id="firstclick" href="javascript:void(0)" tab-id="3" class="react ">
                            <span>{pigcms{:L('_B_PURE_MY_95_')}</span>
                        </a>
                    </li>
                </ul>
				</div></div>
			</dd>
		</dl>
        <div class="gray_line"></div>
        <div class="gray_k"></div>
		<div style="padding: 0px 8px 8px 8px;">
		    <dl class="list tabs-content" id="orders">
				<div tab-id="6" class="tab">
				</div>
                <div tab-id="3" class="tab active">
                </div>
		    </dl>
		</div>
        <div id="pageLoadTipShade" class="pageLoadTipBg">
            <div id="pageLoadTipBox" class="pageLoadTipBox">
                <div class="pageLoadTipLoader">
                    <div style="background-image:url({pigcms{$config.shop_load_bg});"><!--img src="{pigcms{$static_path}shop/images/pageTipImg.png"/--></div>
                </div>
            </div>
        </div>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
        <include file="My:shop_order_list_js_theme"/>
		<script type="text/javascript" language="javascript">

            $(document).ready(function() {
                $('#firstclick').trigger('click');
                <if condition="$param['select'] eq 'history'">
                    $('#secondclick').trigger('click');
                        <else/>
                    $('#firstclick').trigger('click');
                </if>
            });

			// var activePos = $('.tabs-header .active').position();
			// function changePos() {
			// 	activePos = $('.tabs-header .active').position();
			// 	$('.border').stop().css({
			// 		left: activePos.left,
			// 		width: $('.tabs-header .active').width()
			// 	});
			// }
			// changePos();
			// var tabHeight = $('.tab.active').height();
			// function animateTabHeight() {alert(111);
			// 	tabHeight = $('.tab.active').height();
			// 	$('.tabs-content').stop().css({ height: tabHeight + 'px' });
			// }

            var url = "{pigcms{:U('ajax_shop_order_list')}"
			var tabItems = $('.tabs-header ul li');
			var tabCurrentItem = tabItems.filter('.active');//get currentactive的

			$('.tabs-header a').on('click', function (e) {
				e.preventDefault();

				pageLoadTips({showBg:false});

				var tabId = $(this).attr('tab-id');

				$('.tabs-header a').stop().parent().removeClass('active');
				$(this).stop().parent().addClass('active');
				tabCurrentItem = tabItems.filter('.active');
				$('.tab').stop().fadeOut(300, function () {
					$(this).removeClass('active');
				}).hide();
				$('.tab[tab-id="' + tabId + '"]').stop().fadeIn(300, function () {
					var status = $('.tabs-header ul li.active').data('status');
					$.get(url,{'status':status,'store_id':{pigcms{$_GET.store_id|intval}},function(data){
						if(data.status){
							var order_list = data['order_list'];
							console.log(order_list);
                            laytpl($('#ShopOrderListItemTpl').html()).render(order_list, function(html){
                                $('.tab[tab-id="' + tabId + '"]').html(html);
                            });
						}else{
							var shtml ='<dd><dd class="dealcard dd-padding" style=" text-align:center; background:#fff; width:100%">{pigcms{:L('_B_PURE_MY_83_')}</dd></dd>';
                            $('.tab[tab-id="' + tabId + '"]').html(shtml);
						}
                        pageLoadHides();
					},'json')
				});
			});

            $('.tabs-header_stop a').on('click', function (e) {
                e.preventDefault();
                var tabId = $(this).attr('tab-id');

                $('.tabs-header a').stop().parent().removeClass('active');
                $(this).stop().parent().addClass('active');
                //changePos();
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
                                shtml += '<dd id="my_order_'+order_list[i]["order_id"]+'">';
                                shtml += '<dl><dd class="order-num"><span id="ord_num">'+order_list[i]["real_orderid"]+'</span>';
                                if((order_list[i]['paid'] == 0)){
                                    shtml +='<a href="javascript:void(0)" onclick="del_order('+order_list[i]["order_id"]+')"><img src="{pigcms{$static_path}images/u282.png"></a>';
                                }
                                // if((order_list[i]['status']==2) || (order_list[i]['status']==3)){
                                // 	shtml +='<a href="javascript:void(0)" onclick="del_order('+order_list[i]["order_id"]+')"><img src="{pigcms{$static_path}images/u282.png"></a>';
                                // }
                                shtml += '</dd>';
                                shtml += '<dd class="dealcard dd-padding">';
                                shtml += '<div class="dealcard-img imgbox" onclick="window.location.href = \''+order_list[i]['order_url']+'\';">';
                                shtml += '<img src="'+order_list[i]['image']+'" style="width:100%;height:100%;"/>';
                                shtml += '</div>';
                                shtml += '<div class="dealcard-block-right"  onclick="window.location.href = \''+order_list[i]['order_url']+'\';">';
                                shtml += '<div class="dealcard-brand single-line">'+order_list[i]['name']+'</div>';
                                shtml += '<div class="total_price">Total:'+order_list[i]['price']+'</div>'
                                shtml += '<small>Total item:'+order_list[i]['num']+'&nbsp;&nbsp;'+order_list[i]['create_time_show']+'</small>';
                                shtml += '</div>';

                                shtml += '<div class="order_btn">';
                                if(order_list[i]['paid'] == 0 && (order_list[i]['status'] < 2 || order_list[i]['status'] == 7)){
                                    var url = "{pigcms{:U('Pay/check')}";
                                    url += '&type=shop&order_id='+order_list[i]['order_id'];
                                    shtml +='<span onclick="location.href=\''+url+'\'">{pigcms{:L('_B_PURE_MY_81_')}</span>';
                                    shtml += '<label class="count_down" style="color: grey" data-time="'+order_list[i]['create_time']+'" data-id="'+order_list[i]['order_id']+'"data-jet="'+order_list[i]['jetlag']+'"></label>'
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
                                shtml +='</dl></dd><div style=" height:10px; background:#f0efed"></div>';
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

			var num = 0;
            var curr_time = parseInt("{pigcms{:time()}");
            var count_down = parseInt("{pigcms{$count_down}");
			function update_pay_time() {
                $('#orders').find('.count_down').each(function () {
                    var create_time = $(this).data('time');
                    var jetlag = parseInt($(this).data('jet'))*3600;
                    var cha_time = count_down - (curr_time + jetlag - create_time + num);

                    var h = parseInt(cha_time / 3600);
                    var i = parseInt((cha_time - 3600 * h) / 60);
                    var s = (cha_time - 3600 * h) % 60;
                    if (i < 10) i = '0' + i;
                    if (s < 10) s = '0' + s;

                    //var time_str = h + ':' + i + ':' + s;
                    var time_str = "{pigcms{:L('_B_PURE_MY_81_')} " + i + ':' + s;

                    $(this).html(time_str);

                    var cid = $(this).data('id');
                    var allStr = "my_order_"+cid;
                    if(cha_time < 0)
                        $("#"+allStr).remove();
                });
                window.setTimeout(function () {
                    num++;
                    update_pay_time()
                }, 1000);
            }

            update_pay_time();
            //转转-------------------------------

		</script>
    <script src="{pigcms{$static_path}js/pageloader.js?215"></script>
</div>
<include file="Public:footer"/>
</body>
</html>