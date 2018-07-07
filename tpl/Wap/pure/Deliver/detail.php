<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>配送员系统</title>
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
            <p class="c6 f14"><i>配送地址：</i><span>{pigcms{$supply['aim_site']}</span></p>
            <em><img src="{pigcms{$static_path}images/dindxqt_11.png" width=23 height=27></em>
            </a>
        </div>
    </section>

    <section class="PsorderX">
        <div class="Psorder">
            <div class="Psorder_top p10">
                <h2 class="f16 c3">订单详情</h2>
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
            <p class="c9 f14">数量：{pigcms{$order['num']}</p>
            <p class="f16 red">实际支付：${pigcms{$order['price']|floatval}</p>
            <p class="f14 bur">支付方式： {pigcms{$order['pay_type']}</p>
            <p class="f12 red">配送距离{pigcms{$supply['distance']}公里，配送费{pigcms{$supply['freight_charge']}元</p>
        </div>

        <div class="Remarks clr p10">
            <span class="fl c3 f16">备注</span>
            <div class="Remarks_rig">{pigcms{$supply['note']}</div>
        </div>
    </section>


    <section class="Psorder information">
        <div class="Psorder_top p10">
            <h2 class="f16 c3">订单信息</h2>
        </div>
        <div class="information_end">
            <ul>
                <li class="clr p10">
                    <div class="fl f16 c80">订单编号</div>
                    <div class="fr f14 c80">{pigcms{$order['real_orderid']}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">下单时间</div>
                    <div class="fr f14 c25">{pigcms{$order['create_time']|date="Y-m-d H:i",###}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">期望送达</div>
                    <div class="fr f14 c25">{pigcms{$supply['appoint_time']}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">送达时间</div>
                    <div class="fr f14 c25">{pigcms{$supply['end_time']}</div>
                </li>
                
                <if condition="$supply['get_type'] neq 2">
	                <li class="clr p10">
	                    <div class="fl f16 c80">订单类型</div>
	                    <div class="fr f14 c80">系统派单</div>
	                </li>
	            <else />
	                <li class="clr p10">
	                    <div class="fl f16 c80">订单来源</div>
	                    <div class="fr f14 c80">{pigcms{$supply['change_name']}配送员</div>
	                </li>
				</if>
            </ul>
        </div>
    </section>

	<if condition="$order['cue_field']">
    <section class="Psorder information">
        <div class="Psorder_top p10">
            <h2 class="f16 c3">分类填写字段</h2>
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
            <h2 class="f16 c3">商家信息</h2>
        </div>
        <div class="information_end">
            <ul>
                <li class="clr p10">
                    <div class="fl f16 c80">店铺名称</div>
                    <div class="fr f14 c80">{pigcms{$store['name']}</div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">店铺电话</div>
                    <div class="fr f14 c80"><php>$phoneArr = explode(' ',$store['phone']);</php><volist name="phoneArr" id="vo"><div><a href="tel:{pigcms{$vo}" style="color:blue;">{pigcms{$vo}</a></div></volist></div>
                </li>
                <li class="clr p10">
                    <div class="fl f16 c80">店铺地址</div>
                    <div class="fr f14 c80">{pigcms{$store['adress']}</div>
                </li>
            </ul>
        </div>
    </section> 

    <div class="sign_bottom Ps_bottom">
    	<if condition="$supply['status'] eq 1">
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/grab')}">抢单</a>
    	<elseif condition="$supply['status'] eq 2" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/pick')}">取货</a>
    	<elseif condition="$supply['status'] eq 3" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/send')}">配送</a>
    	<elseif condition="$supply['status'] eq 4" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/my')}">送达</a>
    	<elseif condition="$supply['status'] eq 5" />
    	<a href="javascript:void(0);" data-id="{pigcms{$supply['supply_id']}" data-status="{pigcms{$supply['status']}" data-url="{pigcms{:U('Deliver/del')}">删除</a>
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
</script>
</html>