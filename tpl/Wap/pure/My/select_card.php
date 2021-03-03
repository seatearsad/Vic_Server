<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>{pigcms{:L('_SELECT_COUPON_')}</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
        <link href="{pigcms{$static_path}css/eve.7c92a906.peter.css" rel="stylesheet"/>
        <link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/card_new.css"/>
        <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
        <script src="{pigcms{$static_path}layer/layer.m.js"></script>
        <style>
            dl.list{
                width: 90%;
            }
            .gray_line{
                width: 100%;
                height: 2px;
                margin-top: 15px;
                background-color: #cccccc;
            }
            .gray_k{
                width: 10%;
                height: 2px;
                background-color: #f4f4f4;
                margin: -2px auto 0 auto;
            }

            .this_nav{
                width: 100%;
                text-align: center;
                font-size: 1.8em;
                height: 30px;
                line-height: 30px;
                margin-top: 15px;
                position: relative;
            }
            .this_nav span{
                width: 50px;
                height: 30px;
                display:-moz-inline-box;
                display:inline-block;
                -moz-transform:scaleX(-1);
                -webkit-transform:scaleX(-1);
                -o-transform:scaleX(-1);
                transform:scaleX(-1);
                background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
                background-size: auto 20px;
                background-repeat: no-repeat;
                background-position: right center;
                position: absolute;
                left: 8%;
                cursor: pointer;
            }
            .my_money{
                width: 100%;
                text-align: center;
                margin: 40px auto;
                font-size: 4em;
                line-height: 40px;
            }
            .Coupon .Coupon_top{
                background-color: #ffa52d;
            }
            .Coupon .Coupon_top_not_user{
                background-color: #a3a3a3;
            }
           
            .Coupon .Coupon_end em{
                border: 1px solid #ffa52d;
                color: #ffa52d;
            }
            .Muse{
                width: 94%;
                margin: 0px auto;
            }
            .div_outer{
                display: -webkit-flex;
                display: flex;
                width: 90%;
                margin-left: 5%;
            }

        </style>
		<style>
			 dl.list dd.dealcard {
				overflow: visible;
				-webkit-transition: -webkit-transform .2s;
				position: relative;
			}
			.dealcard.orders-del {
				-webkit-transform: translateX(1.05rem);
			}
			.dealcard-block-right {
				height: 1.68rem;
				position: relative;
			}
			.dealcard .dealcard-brand {
				margin-bottom: .18rem;
			}
			.dealcard small {
				font-size: .24rem;
				color: #666;
			}
			.dealcard weak {
				font-size: .24rem;
				color: #999;
				position: absolute;
				bottom: 0;
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
				width:50%;
				text-align:center;
				position: relative;
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
		</style>
		<style>
			.address-container {
				font-size: .3rem;
				-webkit-box-flex: 1;
			}
			.kv-line h6 {
                width: 100%;
                text-align: center;
			}
			.btn-wrapper {
				margin: .2rem .2rem;
				padding: 0;
			}
		
			.address-wrapper a {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flex-box;
			}
		
			.address-select {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flex-box;
				padding-right: .2rem;
				-webkit-box-align: center;
				-webkit-box-pack: center;
				-moz-box-align: center;
				-moz-box-pack: center;
				-ms-box-align: center;
				-ms-flex-pack: justify;
			}
		
			.list.active dd {
				background-color: #fff5e3;
			}
		
			.confirmlist {
				display: -webkit-box;
				display: -moz-box;
				display: -ms-flex-box;
			}
		
			.confirmlist li {
				-ms-flex: 1;
				-moz-box-flex: 1;
				-webkit-box-flex: 1;
				height: .88rem;
				line-height: .88rem;
				border-right: 1px solid #C9C3B7;
				text-align: center;
			}
		
			.confirmlist li a {
				color: #2bb2a3;
			}
		
			.confirmlist li:last-child {
				border-right: none;
			}
            .main{
                width: 100%;
                padding-top: 60px;
                max-width: 640px;
                min-width: 320px;
                margin: 0 auto;
            }
            .main ul{
                margin: 0px 0 0;
                width: 100%;
            }
            .main ul li{
                width: 90%;
                height: 50px;
                margin-left: 5%;
                background-color: white;
                list-style: none;
                margin-bottom: 10px;
                background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
                background-size: auto 16px;
                background-repeat: no-repeat;
                background-position:right 10px center;
            }
            .main ul li div{
                line-height: 50px;
                font-size: 1.4em;
                padding-left: 20px;
                background-size: auto 70%;
                background-repeat: no-repeat;
                background-position: 10px center;
            }
            input.mt[type="radio"]:checked{
                background-color: #ffa52d;
            }
            #delivery_desc{
                background-color: white;
                line-height: 18px;
                color: #999999;
                margin-top: 5px;
                margin-bottom: 5px;
                padding-left: 5px;
                padding-right: 5px;
                font-size: 12px;
            }

            .apply_div {
                height: 45px;
                padding-top: 0px;
                display: flex;
            }
            dl.list .dd-padding, dl.list dt, dl.list dd > .react {
                padding: 5px 10px;
            }

            .coupon_code {
                border: 0;
                width: 70%;
                margin-left: 0%;
                height: 45px;
                line-height: 45px;
                background-color: #eee;
                border-radius: 10px 0px 0px 10px;
                border:0px;
                background: #fff;
                padding-left: 10px;
            }
            #ex_code {
                width: 30%;
                height: 45px;
                line-height: 45px;
                color: white;
                background-color: #ffa52d;
                text-align: center;
                margin-left: 2%;
                font-size: 20px;
                cursor: pointer;
                border-radius: 2px;
                border-radius: 0px 10px 10px 0px;
            }
        </style>
        <include file="Public:facebook"/>
	</head>
	<body id="index" data-com="pagecommon">
    <include file="Public:header"/>
    <dd class="main Coupon">
		<div id="tips" class="tips"></div>
        <if condition="$delivery_type eq 0">
        <div id="delivery_desc">
            Please note that you cannot use multiple discounts at the same time.
            To use your coupon,your other discount may be affected.
        </div>
        </if>
		<dl class="list" style="margin-top:0rem;display:none">
		    <dd>
				<ul class="orderindex">
					<li><a href="{pigcms{:U('My/select_card',array('coupon_type'=>'system','order_id'=>$_GET['order_id'],'type'=>$_GET['type']))}" class="react <if condition="(empty($_GET['coupon_type'])) OR ($_GET['coupon_type'] eq 'system')">hover</if>">
						<i class="text-icon">⌺</i>
						<span>{pigcms{:L('_PLATFORM_COUP_')}</span>
					</a>
					</li><li><a href="{pigcms{:U('My/select_card',array('coupon_type'=>'mer','order_id'=>$_GET['order_id'],'type'=>$_GET['type']))}" class="react <if condition="$_GET['coupon_type'] == 'mer'">hover</if>">
						<i class="text-icon">⌸</i>
						<span>{pigcms{:L('_SHOP_COUP_')}</span>
					</a>
					</li>
				</ul>
			</dd>
		</dl>
		<if condition="$coupon_list AND $_GET['coupon_type'] eq 'mer'">
        <div id="tips" class="tips"></div>
        <dl class="list ">
            <dd class="address-wrapper">
                <a class="react" href="{pigcms{$unselect}">
                    <div class="address-container">
                        <div class="kv-line">
                            <h6>{pigcms{:L('_NOT_USE_COUPONE_')}</h6>
                        </div>
                    </div>
                </a>
            </dd>
        </dl>

        <volist name="coupon_list" id="vo">
            <dl class="list <if condition="$vo['id'] eq $_GET['merc_id']">active</if>">
                <dd class="address-wrapper">
                    <a class="react" href="{pigcms{$vo.select_url}">
                        <div class="address-select"><input class="mt" type="radio" name="addr" <if condition="$vo['id'] eq $_GET['merc_id']">checked="checked"</if>/></div>
                        <div class="address-container">
                            <div class="kv-line">
                                <h6>{pigcms{:L('_PURCHASE_TXT_')}：</h6>
                                <p>
                                    {pigcms{:replace_lang_str(L('_MAN_CAN_USE_'),$vo['order_money'])}
                                </p>
                            </div>
                            <div class="kv-line">
                                <h6>{pigcms{:L('_MONEY_NUM_')}：</h6><p>${pigcms{$vo.discount}</p>
                            </div>
                            <div class="kv-line">
                                <h6>{pigcms{:L('_EXPRIRY_DATE_')}：</h6><p>{pigcms{$vo.end_time|date='Y/m/d',###}</p>
                            </div>
                        </div>
                    </a>
                </dd>
            </dl>
        </volist>

		<elseif condition="coupon_list AND $_GET['coupon_type'] eq 'system' " />

			<div id="tips" class="tips"></div>
			
			<dl class="list ">
                <dd class="address-wrapper">
                    <a class="react" href="{pigcms{$unselect}">
                        <div class="address-container">
                            <div class="kv-line">
                                <h6>{pigcms{:L('_NOT_USE_COUPONE_')}</h6>
                            </div>

                        </div>
                    </a>
                </dd>
            </dl>
            <dl class="list ">
                <dd class="apply_div">
                    <input type="text" name="coupon_code" class="coupon_code" placeholder="{pigcms{:L('_EXCHANGE_COUPON_')}">
                    <div id="ex_code">{pigcms{:L('_EXCHANGE_TXT_')}</div>
                </dd>
            </dl>

                <ul class="end_ul">
                    <volist name="coupon_list" id="coupon">
                        <dl class="Muse">
                            <dd>
                                <div <if condition="$coupon['is_use'] eq 1">class="Coupon_top clr"<else/>  class="Coupon_top Coupon_top_not_user clr" </if> >
                                    <div class="fl">
                                        <div class="fltop">
                                            <i>$</i><em>{pigcms{$coupon.discount}</em>
                                        </div>
                                        <div class="flend">

                                        </div>
                                    </div>
                                    <div class="fr">
                                        <h2>{pigcms{$coupon.name} <php>if($_GET['coupon_type']=='mer'){</php>({pigcms{$coupon.merchant})<php>}</php></h2>
                                        <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$coupon['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$coupon['discount'])}
                                        <php>}else{</php>
                                        {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$coupon['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$coupon['order_money'])}
                                        <php>}</php>
                                    </div>
                                </div>

                                <div class="Coupon_end">
                                    <div class="Coupon_x">
                                        <i>{pigcms{$coupon.start_time|date='Y.m.d',###}--{pigcms{$coupon.end_time|date='Y.m.d',###}</i>
                                        <if condition="$coupon['is_use'] eq 1">
                                            <a href="{pigcms{$coupon.select_url}"><em>{pigcms{:L('_IMMEDIATE_USE_')}</em></a>
                                        </if>
                                    </div>
                                    <div class="Coupon_sm">
                                        <span class="on">{pigcms{:L('_INSTRUCTIONS_TXT_')}</span>
                                        <div class="Coupon_text overflow">{pigcms{$coupon.des}</div>
                                    </div>
                                </div>

                            </dd>
                        </dl>
                    </volist>
                </ul>

		<else/>
			<div id="tips" class="tips" style="display:block;">{pigcms{:L('_UNAVAILABLE_COUP_')}</div>
		</if>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
            $(".Coupon_sm").each(function(){
                $(this).find("span").click(function(){
                    if($(this).hasClass("on")){
                        $(this).removeClass("on")
                        $(this).siblings(".Coupon_text").removeClass("overflow");
                        $(this).parents("dd").siblings().find(".Coupon_sm span").addClass("on");
                        $(this).parents("dd").siblings().find(".Coupon_sm .Coupon_text").addClass("overflow");
                    }else{
                        $(this).addClass("on")
                        $(this).siblings(".Coupon_text").addClass("overflow");
                    }

                })
            });

			$(function(){
				$('.mj-del').click(function(){
					var now_dom = $(this);
					if(confirm('您确定要删除此地址吗？')){
						$.post(now_dom.attr('href'),function(result){
							if(result.status == '1'){
								now_dom.closest('dl').remove();
							}else{
								alert(result.info);
							}
						});
					}
					return false;
				});
				$('.address-wrapper input.mt').click(function(){
					window.location.href = $(this).closest('a').attr('href');
				});
			});

            $("#ex_code").click(function(){
                var code = $("input[name='coupon_code']").val();
                var order_id = "{pigcms{$_GET['order_id']}";
                if(code == ""){
                    layer.open({
                        title:'Message',
                        content:"{pigcms{:L('_INPUT_EXCHANGE_CODE_')}"
                    });
                } else{
                    exchange_code(code,order_id);
                }
            })

            function exchange_code(code,order_id){
                $.ajax({
                    url:"{pigcms{:U('My/exchangeCode')}",
                    type:"post",
                    data:{"code":code,"order_id":order_id},
                    dataType:"json",
                    success:function(data){
                        if(data.error_code == 0){
                            layer.open({
                                title:'Message',
                                time:3,
                                content:"Success",
                                end:function () {
                                    window.location.reload();
                                }
                            });

                        }else if (data.error_code == 2) {
                            layer.open({
                                title:'Message',
                                time:3,
                                content:data.msg,
                                end:function () {
                                    window.location.reload();
                                }
                            });

                        }else{
                            layer.open({
                                title:'Message',
                                time:3,
                                content:data.msg
                            });
                        }
                    }
                });
            }
		</script>
    </div>
{pigcms{$hideScript}
	</body>
</html>