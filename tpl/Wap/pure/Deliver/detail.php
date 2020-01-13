<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_COURIER_CENTER_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
</head>
<style>
    body{
        color: #666666;
    }
    #order_menu{
        margin-bottom: 10px;
        font-size: 0px;
    }
    #order_menu span{
        list-style-type: none;
        display: inline-block;
        width: 50%;
        text-align: center;
        background: white;
        font-size: 14px;
        height: 40px;
        line-height: 40px;
        box-sizing: border-box;
        cursor: pointer;
    }
    #order_menu span.curr{
        border-bottom: 3px solid #ffa52d;
    }
    .lang_select{
        background-color: white;
        height: 40px;
        font-size: 0px;
        border-bottom: 1px solid #e7e7e7;
    }
    .lang_select span{
        display: inline-block;
        font-size: 13px;
        width: 30%;
        height: 24px;
        line-height: 24px;
        margin-top: 8px;
        margin-left: 10%;
        margin-right: 10%;
        text-align: center;
        border: 1px solid #ffa52d;
        box-sizing: border-box;
        color: #ffa52d;
        cursor: pointer;
    }
    .lang_select span.curr{
        background-color: #ffa52d;
        color: white;
    }
    #order_info{
        margin-bottom: 90px;
        font-size: 11px;
    }
    .info_title{
        font-size: 12px;
        margin-bottom: 5px;
    }
</style>
<body>
    <section class="details p10">
        <div class="details_top">
            <h2 class="f16 c3">
                Order #{pigcms{$supply['order_id']}
            </h2>
            <div style="color: #ffa52d">
                {pigcms{$supply['note']}
            </div>
        </div>
    </section>
    <div id="order_menu">
        <span class="curr" data-id="0">{pigcms{:L('_ND_ORDERDETAIL_')}</span>
        <span data-id="1">{pigcms{:L('_ND_ORDERINFO_')}</span>
    </div>

    <section class="PsorderX" id="order_detail">
        <div class="lang_select">
            <span data-type="zh-cn">Chinese</span>
            <span data-type="en-us">English</span>
        </div>
        <div class="Psorder">
            <div class="Psorder_top p10">
                <div style="color: #333333;line-height: 30px">{pigcms{:lang_substr($store['name'],C('DEFAULT_LANG'))}</div>
            </div>

            <div class="Psorder_end p10">
                <ul>
                	<volist name="goods" id="gdetail">
                    <li class="clr">
                        <dl>
                            <dd class="on">
                                <if condition="$gdetail['num'] gt 1">
                                    <label style="color: #ffa52d">{pigcms{$gdetail['num']}</label>
                                <else />
                                    {pigcms{$gdetail['num']}
                                </if>
                            </dd>
                            <dd>
                                {pigcms{:lang_substr($gdetail['name'],C('DEFAULT_LANG'))}
                            </dd>
                            <dd>
                                <volist name="gdetail['spec_desc']" id="spec">
                                    <div>
                                        {pigcms{$spec}
                                    </div>
                                </volist>
                                <volist name="gdetail['dish']" id="dish">
                                    <div>
                                        {pigcms{$dish['name']}
                                        <volist name="dish['list']" id="dish_one">
                                            <br><label style="color:#999;font-size: 12px">- {pigcms{$dish_one}</label>
                                        </volist>
                                    </div>
                                </volist>
                            </dd>

                            <!--if condition="$supply['status'] eq 5">
                            <dd class="rig"><span><i>$</i>{pigcms{$gdetail['price']|floatval}</span></dd>
                            </if-->
                        </dl>
                    </li>
                    </volist>
                </ul>
            </div>
        </div>

        <div class="details_list">
            <!--p class="c9 f14">{pigcms{:L('_B_PURE_MY_69_')}：{pigcms{$order['num']}</p-->
            <!--p class="c9 f14">{pigcms{:L('_B_PURE_MY_70_')}：{pigcms{$order['subtotal_price']|floatval}</p-->
            <p class="f16 red">{pigcms{:L('_ND_DUEONDELIVERY_')}：${pigcms{$order['deliver_cash']|floatval}</p>
            <!--p class="f14 bur">{pigcms{:L('_PAYMENT_MODE_')}： {pigcms{$order['pay_type_name']} ({pigcms{$order['pay_type']})</p-->
        </div>
    </section>
    <div id="order_info">
        <section class="information">
            <div class="info_title p10">{pigcms{:L('_ND_RESTAURANTINFO_')}</div>
            <div class="information_end">
                <ul>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_RESTAURANT_')}</div>
                        <div class="fr c80">{pigcms{:lang_substr($store['name'],C('DEFAULT_LANG'))}</div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_RESTADDRESS_')}</div>
                        <div class="fr c80">{pigcms{$store['adress']}</div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_RESTNUM_')}</div>
                        <div class="fr c80"><php>$phoneArr = explode(' ',$store['phone']);</php><volist name="phoneArr" id="vo"><div><a href="tel:{pigcms{$vo}" style="color:blue;">{pigcms{$vo}</a></div></volist></div>
                    </li>
                </ul>
            </div>
        </section>
        <section class="information">
            <div class="info_title p10">{pigcms{:L('_ND_RESTAURANTINFO_')}</div>
            <div class="information_end">
                <ul>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_ORDERNUMBERL_')}</div>
                        <div class="fr c80">{pigcms{$order['real_orderid']}</div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_PAYMENTTIME_')}</div>
                        <div class="fr c80">{pigcms{$order['create_time']|date="Y-m-d H:i",###}</div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_FOODPREPTIME_')}</div>
                        <div class="fr c80">{pigcms{$supply['meal_time']}</div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_COMPLETIONTIME_')}</div>
                        <div class="fr c80">
                            <if condition="$order['end_time'] eq ''">
                                N/A
                            <else />
                                {pigcms{$order['end_time']|date="Y-m-d H:i",###}
                            </if>
                        </div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_ORDERTYPE_')}</div>
                        <div class="fr c80">
                            <if condition="$supply['get_type'] eq 1">
                                Assigned
                            </if>
                            <if condition="$supply['get_type'] eq 2">
                                From Courier - {pigcms{$supply['change_name']}
                            </if>
                            <if condition="$supply['get_type'] eq 0">
                                Accepted
                            </if>
                        </div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_DELIVERYFEE_')}</div>
                        <div class="fr c80">${pigcms{$order['freight_charge']}</div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_TIP_')}</div>
                        <div class="fr c80">
                            <if condition="$supply['status'] eq 5">
                                <if condition="$order['pay_method'] eq 1">
                                    ${pigcms{$order.tip_charge}
                                <else />
                                    N/A
                                </if>
                            <else />
                                {pigcms{:L('_ND_TIP1_')}
                            </if>
                        </div>
                    </li>
                </ul>
            </div>
        </section>
        <if condition="$supply['status'] neq 5">
        <section class="information">
            <div class="info_title p10">{pigcms{:L('_ND_CUSTOMERINFO_')}</div>
            <div class="information_end">
                <ul>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_CUSTNUMBER_')}</div>
                        <div class="fr c80"><a href="tel:{pigcms{$supply['phone']}" style="color:blue;">{pigcms{$supply['phone']}</a></div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_CUSTADDRESS_')}</div>
                        <div class="fr c80">{pigcms{$order['user_address']}</div>
                    </li>
                    <li class="clr p10">
                        <div class="fl c80">{pigcms{:L('_ND_CUSTADDRESSINS_')}</div>
                        <div class="fr c80">{pigcms{$order['user_address_detail']}</div>
                    </li>
                </ul>
            </div>
        </section>
        </if>
    </div>
    <if condition="$supply['status'] neq 5">
    <div class="sign_bottom Ps_bottom">
    	<if condition="$supply['status'] eq 1">
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/grab')}">{pigcms{:L('_TICK_ORDER_')}</a>
    	<elseif condition="$supply['status'] eq 2" />
        <div>{pigcms{:L('_ND_PICKUPNOTICE_')}</div>
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/pick')}">{pigcms{:L('_ND_PICKEDUP_')}</a>
    	<elseif condition="$supply['status'] eq 3" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/send')}">{pigcms{:L('_DELI_TXT_')}</a>
    	<elseif condition="$supply['status'] eq 4" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/my')}">{pigcms{:L('_ARRIVAL_TXT_')}</a>
    	<elseif condition="$supply['status'] eq 5" />

    	</if>
    </div>
    </if>
</body>
<script>
    function setCookie(c_name,value,expiredays)
    {
        var exdate=new Date();
        exdate.setDate(exdate.getDate()+expiredays);
        document.cookie=c_name+ "=" +escape(value)+
            ((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
    }

    var language = "{pigcms{:C('DEFAULT_LANG')}";
    setLanguage(language);
    function setLanguage(language){
        this.language = language;
        setCookie('lang',language,30);
        $('.lang_select').find('span').each(function () {
            if($(this).data('type') == language)
                $(this).addClass('curr');
            else
                $(this).removeClass('curr');
        });
    }

    $('.lang_select').find('span').each(function () {
        $(this).click(function () {
            if($(this).data('type') != language){
                setLanguage($(this).data('type'));
                location.reload();
            }
        });
    });

    var order_menu = ["order_detail","order_info"];
    function show_order(num){
        var i=0;
        $('#order_menu').find('span').each(function () {
            if(i == num){
                $(this).addClass('curr');
                $('#'+order_menu[i]).show();
            }else{
                $(this).removeClass('curr');
                $('#'+order_menu[i]).hide();
            }
            i++;
        });
    }

    $('#order_menu').find('span').each(function () {
        $(this).click(function (){
            show_order($(this).data('id'));
        });
    });

    show_order(0);

$(document).ready(function(){
	var mark = 0;
	$(document).on('click', '.sign_bottom a', function(e){
		e.stopPropagation();
		if (mark == 1) return false;
		mark = 1;
		var supply_id = $(this).attr("data-id"), post_url = $(this).data('url'), status = $(this).data('status');

		if (status == 5) {
			layer.open({
			    content: '删除后就不再显示了，但是不影响您的接单统计!',
			    btn: ['确认', '取消'],
			    shadeClose: false,
			    yes: function(){
			    	layer.closeAll();
						$.post(post_url, {supply_id:supply_id}, function(json){
							if (json.status) {
								location.href = "{pigcms{:U('Deliver/finish')}";
								$('.supply_' + supply_id).hide();
							} else {
								layer.open({title:['提示：','background-color:#FF658E;color:#fff;'], content:json.info, btn: ['确定'],end:function(){}});
							}
						}, 'json');
			    }, no: function(){
			        layer.open({content: '你选择了取消', time: 1});
			    }
			});
		} else {
			$.post(post_url, {'supply_id':supply_id}, function(json){
				mark = 0;
				if (json.status) {
                    if(status == 4) {
                        var pay_method = "{pigcms{$order['pay_method']}";
                        var content = "{pigcms{:L('_ND_CASHORDERNOTICE_')}";
                        if(pay_method == 1){
                            content = "Order Completed! Delivery Fee: ${pigcms{$order.freight_charge}. Tips: ${pigcms{$order.tip_charge}. Thank you for your delivery!"
                        }
                        layer.open({
                            title: ['{pigcms{:L("_ND_TISHI_")}', 'background-color:#ffa52d;color:#fff;'],
                            content: content,
                            btn: ['{pigcms{:L("_ND_CONFIRM1_")}'],
                            end: function () {
                                location.reload();
                            }
                        });
                    }else
                        layer.open({title:['{pigcms{:L("_ND_TISHI_")}','background-color:#ffa52d;color:#fff;'], time: 2, content: json.info,end:function () {
                            location.reload();
                        }});
				} else {
					layer.open({title:['{pigcms{:L("_ND_TISHI_")}','background-color:#FF658E;color:#fff;'], content:json.info, btn: ['{pigcms{:L("_ND_CONFIRM1_")}'], end:function(){}});
				}
				$(".supply_"+supply_id).remove();
			});
		}
	});
});

var ua = navigator.userAgent;
if(!ua.match(/TuttiDeliver/i)) {
    navigator.geolocation.getCurrentPosition(function (position) {
        updatePosition(position.coords.latitude,position.coords.longitude);
    });
}

//ios app 更新位置
function updatePosition(lat,lng){
    var message = '';
    $.post("{pigcms{:U('Deliver/App_update')}", {'lat':lat, 'lng':lng}, function(result) {
        if(result){
            message = result.message;
        }else {
            message = 'Error';
        }
    });

    return message;
}
</script>
</html>