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

<body>
    <section class="details p10">
        <div class="details_top">
            <h2 class="f16 c3"><i>{pigcms{$supply['name']}</i><span class="f14 c6"><a href="tel:{pigcms{$supply['phone']}">{pigcms{$supply['phone']}</a></span></h2>
            <a href="{pigcms{:U('Deliver/map', array('supply_id' => $supply['supply_id']))}">
            <p class="c6 f14"><i>{pigcms{:L('_C_DELIVERY_ADDRESS_')}：</i><span>{pigcms{$supply['aim_site']}</span></p>
            <em><img src="{pigcms{$static_path}images/dindxqt_11.png" width=23 height=27></em>
            </a>
        </div>
    </section>

    <section class="PsorderX">
        <div class="Psorder">
            <div class="Psorder_top p10">
                <h2 class="f16 c3">{pigcms{:L('_ORDER_DETAIL_')}</h2>
            </div>
            <div class="Psorder_end p10">
                <ul>
                	<volist name="goods" id="gdetail">
                    <li class="clr">
                        <dl>
                            <dd>{pigcms{$gdetail['name']}</dd>
                            <dd class="on"><i>x</i> {pigcms{$gdetail['num']}</dd>
                            <dd class="rig"><span><i>$</i>{pigcms{$gdetail['price']|floatval}</span></dd>
                        </dl>
                    </li>
                    </volist>
                </ul>
            </div>
        </div>

        <div class="details_list">
            <p class="c9 f14">{pigcms{:L('_B_PURE_MY_69_')}：{pigcms{$order['num']}</p>
            <p class="c9 f14">{pigcms{:L('_B_PURE_MY_70_')}：{pigcms{$order['subtotal_price']|floatval}</p>
            <p class="f16 red">{pigcms{:L('_ACTUAL_PAYMENT_')}：${pigcms{$order['deliver_cash']|floatval}</p>
            <p class="f14 bur">{pigcms{:L('_PAYMENT_MODE_')}： {pigcms{$order['pay_type_name']} ({pigcms{$order['pay_type']})</p>
            <p class="f12 red">{pigcms{:L('_C_DISTANCE_')}{pigcms{$supply['distance']}(KM)，{pigcms{:L('_DELI_PRICE_')}:${pigcms{$supply['freight_charge']},{pigcms{:L('_TIP_TXT_')}:${pigcms{$order['tip_charge']}</p>
        </div>

        <div class="Remarks clr p10">
            <span class="fl c3 f16">{pigcms{:L('_NOTE_INFO_')}</span>
            <div class="Remarks_rig">{pigcms{$supply['note']}</div>
        </div>
    </section>


    <section class="Psorder information">
        <div class="Psorder_top p10">
            <h2 class="f16 c3">{pigcms{:L('_ORDER_INFO_')}</h2>
        </div>
        <div class="information_end">
            <ul>
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{:L('_B_PURE_MY_68_')}</div>
                    <div class="fr f14 c80">{pigcms{$order['real_orderid']}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{:L('_ORDER_TIME_')}</div>
                    <div class="fr f14 c25">{pigcms{$order['create_time']|date="Y-m-d H:i",###}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{:L('_EXPECTED_TIME_')}</div>
                    <div class="fr f14 c25">{pigcms{$supply['appoint_time']}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{:L('_DELI_TIME_')}</div>
                    <div class="fr f14 c25">{pigcms{$supply['end_time']}</div>
                </li>
                
                <if condition="$supply['get_type'] neq 2">
	                <li class="clr p10">
	                    <div class="fl f16 c80">{pigcms{:L('_C_ORDER_TYPE_')}</div>
	                    <div class="fr f14 c80">{pigcms{:L('_C_SYS_ASS_ORDER_')}</div>
	                </li>
	            <else />
	                <li class="clr p10">
	                    <div class="fl f16 c80">{pigcms{:L('_C_ORDER_SOURCE_')}</div>
	                    <div class="fr f14 c80">{pigcms{:L('_COURIER_TXT_')}:{pigcms{$supply['change_name']}</div>
	                </li>
				</if>
            </ul>
        </div>
    </section>

	<if condition="$order['cue_field']">
    <section class="Psorder information">
        <div class="Psorder_top p10">
            <h2 class="f16 c3"></h2>
        </div>
        <div class="information_end">
            <ul>
				<volist name="order['cue_field']" id="cue">
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{$cue['title']}</div>
                    <div class="fr f14 c80">{pigcms{$cue['txt']}</div>
                </li>
				</volist>
            </ul>
        </div>
    </section>
	</if>
           
    <section class="Psorder information Merchant">
        <div class="Psorder_top p10">
            <h2 class="f16 c3">{pigcms{:L('_SHOP_INFO_')}</h2>
        </div>
        <div class="information_end">
            <ul>
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{:L('_C_MERCHANT_NAME_')}</div>
                    <div class="fr f14 c80">{pigcms{:lang_substr($store['name'],C('DEFAULT_LANG'))}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{:L('_C_MERCHANT_PHONE_')}</div>
                    <div class="fr f14 c80"><php>$phoneArr = explode(' ',$store['phone']);</php><volist name="phoneArr" id="vo"><div><a href="tel:{pigcms{$vo}" style="color:blue;">{pigcms{$vo}</a></div></volist></div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">{pigcms{:L('_C_MERCHANT_ADDR_')}</div>
                    <div class="fr f14 c80">{pigcms{$store['adress']}</div>
                </li>
            </ul>
        </div>
    </section> 

    <div class="sign_bottom Ps_bottom">
    	<if condition="$supply['status'] eq 1">
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/grab')}">{pigcms{:L('_TICK_ORDER_')}</a>
    	<elseif condition="$supply['status'] eq 2" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/pick')}">{pigcms{:L('_C_PICK_UP_')}</a>
    	<elseif condition="$supply['status'] eq 3" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/send')}">{pigcms{:L('_DELI_TXT_')}</a>
    	<elseif condition="$supply['status'] eq 4" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/my')}">{pigcms{:L('_ARRIVAL_TXT_')}</a>
    	<elseif condition="$supply['status'] eq 5" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/del')}">{pigcms{:L('_B_PURE_MY_27_')}</a>
    	</if>
    </div>     
</body>
<script>
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
					layer.open({title:['操作提示：','background-color:#FF658E;color:#fff;'], content:json.info, btn: ['确定'], end:function(){
						location.reload();
					}});
				} else {
					layer.open({title:['操作提示：','background-color:#FF658E;color:#fff;'], content:json.info, btn: ['确定'], end:function(){}});
				}
				$(".supply_"+supply_id).remove();
			});
		}
	});
});
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