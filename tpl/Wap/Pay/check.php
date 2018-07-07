<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>确认订单</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link href="{pigcms{$static_path}css/wap_pay_check.css" rel="stylesheet"/>
	<link href="{pigcms{$static_path}css/weixin_pay.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>

		<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>

</head>
<body>
<script type="text/javascript">

        var  score_count = Number("{pigcms{$score_count}");
        var  now_money = Number("{pigcms{$now_user.now_money}");
        var  score_deducte = Number("{pigcms{$score_deducte}");
        var  score_can_use_count = Number("{pigcms{$score_can_use_count}");
        var  coupon_price = Number("<?php if($now_coupon){ ?>{pigcms{$now_coupon.price}<?php }?>");
        var  wx_cheap =Number("<?php if($cheap_info['can_cheap']){ ?>{pigcms{$cheap_info.wx_cheap}<?php }else{?>0<?php }?>");
        var  total_money = Number("{pigcms{$order_info.order_total_money}");
        var  need_pay =total_money-wx_cheap;
        $(document).ready(function() {
            if($("#use_score").is(':checked')==true){
				$("input[name='use_score']").attr('value',1);
				 need_pay=total_money-score_deducte-coupon_price-now_money-wx_cheap;
				 need_pay=need_pay.toFixed(2);

				if(need_pay<=0){
					$('#normal-fieldset').css('display', 'none');
				}
				 if(need_pay>0){
					$('.need-pay').empty();
					$('.need-pay').append(need_pay);

				 }else{
					$('.need-pay').empty();
					$('.need-pay').append(0);
				 }
			}else{
				$("input[name='use_score']").attr('value',0);
				 need_pay=total_money-coupon_price-now_money-wx_cheap;
				 need_pay=need_pay.toFixed(2);
				 var type = "{pigcms{$_GET['type']}";
				 if(need_pay<=0&&type!='recharge'){
					$('#normal-fieldset').css('display', 'none');
				}
				 if(need_pay>0){
					$('.need-pay').empty();
					$('.need-pay').append(need_pay);

				 }else{
					$('.need-pay').empty();
					$('.need-pay').append(0);
				 }
			}
            if (score_count>0) {
            $("#use_score").bind("click", function () {

                if($("#use_score").is(':checked')==true){
                    $("input[name='use_score']").attr('value',1);
                     need_pay=total_money-score_deducte-coupon_price-now_money-wx_cheap;
                     need_pay=need_pay.toFixed(2);

                    if(need_pay<=0){
                        $('#normal-fieldset').css('display', 'none');
                    }
                     if(need_pay>0){
                        $('.need-pay').empty();
                        $('.need-pay').append(need_pay);

                     }else{
                        $('.need-pay').empty();
                        $('.need-pay').append(0);
                     }
                }else if($("#use_score").is(':checked')==false){
                    $("input[name='use_score']").attr('value',0);
                    need_pay=total_money-coupon_price-now_money-wx_cheap;
                    if(need_pay>0){
                        $('#normal-fieldset').css('display', 'block');
                        $('#need_pay').css('visibility', 'visible');
                    }

                    $('.need-pay').empty();
                    if(coupon_price>0){
                        $('.need-pay').append(need_pay.toFixed(2));
                    }else{
                         $('.need-pay').append(need_pay.toFixed(2));
                    }

					if(wx_cheap>0){
                        $('.need-pay').html(need_pay.toFixed(2));
                    }else{
                         $('.need-pay').html(need_pay.toFixed(2));
                    }

                }
            });
            }

			$("form").submit(function() {
				$("button.mj-submit").attr("disabled", "disabled");
				$("button.mj-submit").html("正在处理...");
			});
			
			
        });
	<if condition="$config['twice_verify']">var twice_verify = true;<else />var twice_verify = false;</if>
    </script>
	<script language="javascript">
	function bio_verify(){
		layer.open({type:2,content:'页面加载中',shadeClose:false});
		var pay_type = $('input:radio:checked').val();
		$("button.mj-submit").attr("disabled", "disabled");
		$("button.mj-submit").html("正在处理...");
		if(twice_verify&&(total_money-wx_cheap-need_pay>0)){
			if(typeof(wxSdkLoad) != "undefined"){
				wx.invoke('getSupportSoter', {}, function (res) {
				  if(res.support_mode=='0x01'){
					wx.invoke('requireSoterBiometricAuthentication', {
					  auth_mode: '0x01',
					  challenge: 'test',
					  auth_content: '请将指纹验证'  //指纹弹窗提示
					}, function (res) {
						if(res.err_code==0&&pay_type=='weixin'){
							callpay();
						}else if(res.err_code==0){
							layer.closeAll();
							$('#pay-form').submit();
						}else if (res.err_code==90009){
							layer.closeAll();
							$('#pwd_bg').css('display','block');
							$('#pwd_verify').css('display','block');
						}else{
							alert(res.err_code);
							$("button.mj-submit").removeAttr("disabled");
							$("button.mj-submit").html("确认支付");
						}
					})
				  }else{
					 // 密码验证
					 layer.closeAll();
					$('#pwd_bg').css('display','block')		
					$('#pwd_verify').css('display','block')		
				  }
				})
			}else{
						
				layer.closeAll();
				$('#pwd_bg').css('display','block');
				$('#pwd_verify').css('display','block');
			}
		 
		}else{
			layer.closeAll();
			var res = callpay();
			if(res){
				$('#pay-form').submit();
			}
		}
	}
	
	//微信弹程支付
	function callpay(){
		var pay_type = $('input:radio:checked').val();
		if(typeof(pay_type)!='undefined'){
			if(pay_type!='weixin'){
				return true;
			}else if(pay_type=='weixin'){
				var pay_method = {pigcms{:json_encode($pay_method)};
				var orderid_info = {pigcms{:json_encode($orderid_info)};
				var pay_money = need_pay;
				var short_orderid = {pigcms{$order_info.order_id};
				$("button.mj-submit").attr("disabled", "disabled");
				$("button.mj-submit").html("正在处理...");
				var param;
				$.ajax({
					url: '{pigcms{:U(\'Pay/get_weixin_param\')}',
					type: 'POST',
					dataType: 'json',
					data: {pay_method:pay_method,orderid_info:orderid_info,pay_money:pay_money,short_orderid:short_orderid},
					beforeSend: function(){
						layer.open({type:2,content:'支付加载中',shadeClose:false});
					},
					success: function(date){
					layer.closeAll();
						if(!date.error){
								 param =  date.weixin_param;
								 WeixinJSBridge.invoke("getBrandWCPayRequest",param,function(res){
									WeixinJSBridge.log(res.err_msg);
									if(res.err_msg=="get_brand_wcpay_request:ok"){
										setTimeout("window.location.href = '"+date.redirctUrl+"'",200);
									}else{
										$("button.mj-submit").removeAttr("disabled");
										$("button.mj-submit").html("确认支付");
									}
								});
						}else{
							alert(date.msg)
						}
					}
				});
				return false;
			}
		}else{
			return true;
		}
	}
	
	
</script>

<?php if($is_app_browser && in_array($app_browser_type,array('android','ios')) && ($_REQUEST['app_version'] ? $_SESSION['app_version'] : $_SESSION['app_version']) ){ ?>
 <script type="text/javascript">
    <if condition="$app_browser_type eq 'android'">
        window.lifepasslogin.payCheck("{pigcms{$_GET['type']}","{pigcms{$_GET['order_id']}");
        layer.open({type: 2});
        function ReturnLastPay(){
            history.back();
        };
    <else/>
        $('body').append('<iframe src="pigcmso2o://gopay/<?php $arr=array('type'=>$type,'order_id'=>$order_id); echo base64_encode(json_encode($arr)); ?>" style="display:none"></iframe>');
        function payCheck(){
           alert('1111');
           //alert(window.location.href);
           //alert(window.location.href);
           window.location.reload();
        }
    </if>
 </script>
    <?php }else{ ?>
		<script>layer.open({type:2,content:'页面加载中',shadeClose:false});</script>
        <div id="tips" class="tips"></div>
        <div class="wrapper-list">
			<h4 style="margin-top:.4rem">{pigcms{$order_info.order_name}</h4>
			<dl class="list">
			    <dd>
			        <dl>
			        	<if condition="$order_info['order_txt_type']">
				        	<dd class="kv-line-r dd-padding">
				                <h6>类型：</h6>
				                <p>{pigcms{$order_info.order_txt_type}</p>
				            </dd>
			            </if>
			            <if condition="$order_info['order_num']">
				            <dd class="kv-line-r dd-padding">
				                <h6>购买数量：</h6><p>{pigcms{$order_info.order_num}</p>
				            </dd>
			            </if>
			            <if condition="$order_info['order_price']">
				            <dd class="kv-line-r dd-padding">
				                <h6>项目单价：</h6><p>{pigcms{$order_info.order_price}元</p>
				            </dd>
			            </if>
			            <dd class="kv-line-r dd-padding">
			                <h6>总额：</h6><p><strong class="highlight-price">{pigcms{$order_info.order_total_money}元</strong></p>
			            </dd>
			        </dl>
			    </dd>
			</dl>
			<if condition="$order_info['order_type'] != 'recharge'">
				<h4>结算信息</h4>
				<dl class="list">
					<dd>
						<dl>
							<if condition="$cheap_info['can_cheap']">
								<dd class="kv-line-r dd-padding">
									<h6>微信优惠：</h6><p>{pigcms{$cheap_info.wx_cheap}元</p>
								</dd>
							</if>
							<if condition="$_GET['type'] neq 'weidian'">
								<?php if(empty($notCard)){ ?>
									<dd>
										<a class="react" href="{pigcms{:U('My/select_card',($order_info['coupon_url_param'] ? $order_info['coupon_url_param'] :$_GET))}&coupon_type=system">
											<div class="more more-weak">
												<h6>优惠券：</h6>
												<span class="more-after"><?php if($now_coupon){ ?>${pigcms{$now_coupon.price}<?php }else{ ?>使用优惠券<?php } ?></span>
											</div>
										</a>
									</dd>
								<?php } ?>
							</if>

							<if condition="$score_deducte gt 0">
								<dd class="kv-line-r dd-padding">
									<h6>本单可使用积分：{pigcms{$score_can_use_count}<br/>可抵扣金额：{pigcms{$score_deducte|floatval=###}元</h6><p>是否使用积分： <input type="checkbox" name="use_score" id="use_score" value="1" ></p>
								</dd>
							</if>
							<dd class="kv-line-r dd-padding">
								<h6>商家会员卡余额：</h6><p>{pigcms{$merchant_balance}元</p>
							</dd>
							<dd class="kv-line-r dd-padding">
								<h6>帐户余额：</h6><p>{pigcms{$now_user.now_money}元</p>
							</dd>

							<if condition="$pay_money gt 0">
								<dd class="kv-line-r dd-padding">
									<h6>还需支付：</h6>
									<p>
										<strong class="highlight-price">
											<php>if($cheap_info['can_cheap']){</php>
												<span class="need-pay"></span>元
											<php>}else{</php>
												<span class="need-pay">{pigcms{$pay_money}</span>元
											<php>}</php>
										</strong>
									</p>
								</dd>
							</if>
						</dl>
					</dd>
				</dl>
			</if>
			<form action="/source{pigcms{:U('Pay/go_pay',array('showwxpaytitle1'=>1))}" method="POST" id="pay-form" class="pay-form" >
				<input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
				<input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
				<input type="hidden" name="card_id" value="{pigcms{$now_coupon.record_id}"/>
				<if condition="$now_coupon.type eq 'system'">
				<input type="hidden" name="coupon_id" value="{pigcms{$now_coupon.id}"/>
				</if>
				<input type="hidden" name="use_score" value="0"/>
				<div id="pay-methods-panel" class="pay-methods-panel">
				<notempty name="score_count">
				<input type="hidden" name="score_used_count" value="{pigcms{$score_can_use_count}">
				<input type="hidden" name="score_deducte" value="{pigcms{$score_deducte}">
				<input type="hidden" name="score_count" value="{pigcms{$score_count}">

                </notempty>
				
					<if condition="$pay_money gt 0">
						<div id="normal-fieldset" class="normal-fieldset" style="height: 100%;">
							<h4>选择支付方式</h4>
							<dl class="list">
								<volist name="pay_method" id="vo">
									<if condition="$now_order['order_info']['group_share_num'] eq 0 OR $key neq 'offline'">
									<dd class="dd-padding">
										<label class="mt"><i class="bank-icon icon-{pigcms{$key}"></i><span class="pay-wrapper">{pigcms{$vo.name}<input type="radio" class="mt" value="{pigcms{$key}"  <if condition="$i eq 1">checked="checked"</if> name="pay_type"></span></label>
									</dd>
									</if>
								</volist>
								<if condition="$_SESSION['openid'] eq 'orw0XuCZvYok50HoRjSbxKpAMbpE'">
								<dd class="dd-padding">
									<label class="mt"><i class="bank-icon icon-alipay"></i><span class="pay-wrapper">支付宝<input type="radio" class="mt" value="alipay" name="pay_type"></span></label>
								</dd>
								</if>
							</dl>
						</div>
					</if>
					<div class="wrapper buy-wrapper">
						<button type="button" class="btn mj-submit btn-strong btn-larger btn-block" onclick="bio_verify()" style="display:none;">确认支付</button>
					</div>
				</div>
			</form>
		</div>
		
		<link href="{pigcms{$static_path}css/check.css" rel="stylesheet"/>
		<div id="pwd_bg" style="height: 921px;" style="display:block">

		</div>
		<div id="pwd_verify" class="pwd_verify" style="display:none" >
			<div class="pwd_menu">
				<div id="pwd_code"style="background-color:rgba(73, 180, 79, 1);color:#fff;">密码验证</div>
				<!--<div id="sms_code">短信验证</div>-->
			</div>
				<input type="hidden" id="pwd_type" name="type" value="1">
			<div class="verify_pwd">
				<p></p>
				<input type="password"  autocomplete="off"  id="pwd" placeholder="输入登录密码" name="pwd" value="">
				<a id="forget_pwd" href="{$forget_url}">忘记密码?</a>
			</div>
			<div class="verify_sms" style="display:none;">
				<span style="color:#5E5E5E;font-size: 12px;">验证码将发送您手机：</span><span id="verify_phone" style="color:#006600;font-size: 12px;"></span>
				<input type="text" name="sms_code" autocomplete="off"  placeholder="输入验证码" value="">
				<button onclick="sendsms(this)">发送短信</button>
				<p></p>
			</div>
			<div style="width:85%;width: 85%;height: 40px;margin: 0 auto;">
				<div class="pwd_button cancle">
					取消
				</div>
				<div class="pwd_button sure" id="verify">
					验证
				</div>
			</div>
		</div>
	
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}js/bioauth_.js"></script>

		<script>
			layer.closeAll();
			var showBuyBtn = true;
		</script>
		<if condition="$cheap_info['can_buy'] heq false">
			<script>layer.open({title:['提示：','background-color:#FF658E;color:#fff;'],content:'您必须关注公众号后才能购买本单！<br/>长按图片识别二维码关注：<br/><img src="{pigcms{$config.site_url}/index.php?c=Recognition&a=get_tmp_qrcode&qrcode_id={pigcms{$order_info['order_id']+2000000000}" style="width:230px;height:230px;"/>',shadeClose:false});$('button.mj-submit').remove();var showBuyBtn = false;</script>
		</if>
		<script>if(showBuyBtn){$('button.mj-submit').show();}</script>
		<php>$no_footer = true;</php>
		<include file="Public:footer"/>
{pigcms{$hideScript}

<?php } ?>
</body>
</html>