<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{:L('_PAYMENT_CONFIRM_')} - {pigcms{:L('_VIC_NAME_')}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<script type="text/javascript">
        var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
        var  score_count = Number("{pigcms{$score_count}");
        var  now_money = Number("{pigcms{$now_user.now_money}");
		var  extra_price =Number("{pigcms{$order_info.extra_price}");
        var  open_extra_price =Number("{pigcms{$config.open_extra_price}");
        var  extra_price_name ="{pigcms{$config.extra_price_alias_name}";
        var  order_extra_price =Number("{pigcms{$order_info.order_extra_price}");
        var  score_percent = Number("{pigcms{$user_score_use_percent}");
        var  score_deducte = Number("{pigcms{$score_deducte}");
        var  score_can_use_count = Number("{pigcms{$score_can_use_count}");
        var  total_money = Number("{pigcms{$order_info.order_total_money}");
		total_money+=order_extra_price;
        var  need_pay = total_money;
        $(document).ready(function(){
			//判断是否勾选了余额
			if($("#use_balance").is(':checked')==true){
				if($("#use_score").is(':checked')==true){
					need_pay_tmp = total_money-score_deducte;
				}
				if(now_money>need_pay){
					$('.imgradio').find('input').removeAttr('checked');
					need_pay_tmp=0;
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
					
				}else{					
					need_pay_tmp = total_money-now_money;
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}else{
				if($("#use_score").is(':checked')==true){
					need_pay_tmp = total_money-score_deducte;
				}else{
					need_pay_tmp = total_money;
				}
				if(need_pay_tmp>0){
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}
			
		//是否勾选了
			if($("#use_score").is(':checked')==true){
				if($("#use_balance").is(':checked')==true){
					need_pay_tmp=total_money-score_deducte-now_money;
				}else{
					need_pay_tmp=total_money-score_deducte;
				}
	
				if(need_pay_tmp<=0){
					$('.imgradio').find('input').removeAttr('checked');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html('0.00');
					$('#pay_bank_list').css('display', 'none');
				}else{
				
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}else{
				if($("#use_balance").is(':checked')==true){
					need_pay_tmp=total_money-now_money;
				}else{
					need_pay_tmp=total_money;
				}
				
				if(need_pay_tmp<=0){
					$('.need_pay').html('0.00');
					$('#pay_bank_list').css('display', 'none');
				}else{
					
					$('#pay_bank_list').css('display', 'block');
					$('#need-pay').css('display', 'block');
					$('.need_pay').html(need_pay_tmp.toFixed(2));
				}
			}
			
			//监听勾选
        
			$("#use_score").bind("click", function () {
				
				if($("#use_score").is(':checked')==true){
					console.log(score_deducte)
					if(total_money-score_deducte<=0){
						need_pay_tmp=0;
						$('.imgradio').find('input').removeAttr('checked');
						$('#use_balance').removeAttr('checked');
						$('#use_balance').attr('disabled','disabled');
						$('#pay_bank_list').css('display', 'none');
					}else{
						if($("#use_balance").is(':checked')==true){
							need_pay_tmp = total_money-now_money-score_deducte;
							
							if(need_pay_tmp<0){
								need_pay_tmp=0;
								$('.imgradio').find('input').removeAttr('checked');
								$('#pay_bank_list').css('display', 'none');
							}
						}else{
							
							need_pay_tmp=total_money-score_deducte;
						}
						
					}
					
					$('.need_pay').html(need_pay_tmp.toFixed(2));
					$("input[name='use_score']").attr('value',1);
					
				}else if($("#use_score").is(':checked')==false){  
					if($("#use_balance").is(':checked')==true){
						need_pay_tmp = total_money-now_money;
						if(need_pay_tmp<0){
							need_pay_tmp=0;
							$('.imgradio').find('input').removeAttr('checked');
							$('#pay_bank_list').css('display', 'none');
						}
					}else{
						need_pay_tmp = total_money;
					}             
					if(need_pay_tmp>0){
							$('.imgradio').find('input:first').attr('checked','checked');
						if(now_money!=0){							
							$('#use_balance').removeAttr('disabled');
						}
						$('#pay_bank_list').css('display', 'block');
					}
				
					$('.need_pay').html(need_pay_tmp.toFixed(2));
					$("input[name='use_score']").attr('value',0);
				}
			});
            
			//监听勾选余额
			$('#use_balance').bind("click", function () {
				if($("#use_score").is(':checked')==true){
					need_pay_tmp =total_money-score_deducte;
				}else{
					need_pay_tmp =total_money;
				}
				
				if($("#use_balance").is(':checked')==true){
						
					if(now_money>need_pay_tmp){
						$('.imgradio').find('input').removeAttr('checked');
						$('#pay_bank_list').css('display', 'none');
						$('.need_pay').html('0.00');
					}else{
						var need_pay_tmp = need_pay_tmp-now_money;
						$('#pay_bank_list').css('display', 'block');
                        $('#payment_select').show();
						$('.need_pay').html(need_pay_tmp.toFixed(2));
					}
					$("input[name='use_balance']").attr('value',1);
				}else if($("#use_balance").is(':checked')==false){
				
					if(need_pay_tmp>0){
						$('.imgradio').find('input:first').attr('checked','checked');
						$('#pay_bank_list').css('display', 'block');
                        $('#payment_select').show();
						$('#need-pay').css('display', 'block');
						$('.need_pay').html(need_pay_tmp.toFixed(2));
					}else{
						$('#need-pay').css('display', 'block');
						$('.need_pay').html('0.00');
					}
					$("input[name='use_balance']").attr('value',0);
				}
                isShowCredit();
			});
			
			
			var score_money_sure = 0;
			$('#plus').click(function(){
				var score_change = $('#score_change').val();
				if(score_can_use_count>=(Number(score_change)+1)){
					var score_counts = Number(score_change)+1;
				}else{
					var score_counts = score_can_use_count;
				}
				$('#score_change').val(score_counts.toFixed(2));
				score_deducte = score_money_sure;
				score_money_sure = (score_counts/score_percent).toFixed(2);
				$('#use_score').removeAttr('checked');
				$('.need_pay').text(total_money);
				$('#score_deducte_t').html('CAD$'+score_money_sure);
				$('#score_deducte').val(score_money_sure);
			});
			$('#minus').click(function(){
	
				var score_change = $('#score_change').val();
				if(Number(score_change)-1>=0){
					var score_counts = Number(score_change)-1;
				}else{
					var score_counts = 0;
				}
				$('#score_change').val(score_counts.toFixed(2));
				score_money_sure = (score_counts/score_percent).toFixed(2);
				score_deducte = score_money_sure;
				$('#use_score').removeAttr('checked');
				$('.need_pay').text(total_money);
				$('#score_deducte_t').html('CAD$'+score_money_sure);
				$('#score_deducte').val(score_money_sure);
			});
			
		
			
			$('#score_change').blur(function(){
				if(isNaN($(this).val())||Number($(this).val())<0){
					alert('非法输入');
					window.location.reload();
				}
				if(Number($(this).val())>score_can_use_count){
					alert('最多使用'+score_can_use_count+'个'+extra_price_name);
					$('#score_change').val(score_can_use_count);
				}else{
					var changed_score = $(this).val();
					score_money_sure = (changed_score/score_percent).toFixed(2);
					score_deducte = score_money_sure;
					$('.verify_lspan').html('CAD$'+score_money_sure);
					$('#score_change').val(Number(changed_score).toFixed(2));
					$('#score_deducte_t').html('CAD$'+score_money_sure);
					$('#score_deducte').val(score_money_sure);
					
					
				}
				$('#use_score').removeAttr('checked');
				$('.need_pay').text(total_money);
			});
			
        });
          
           
            
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/common.js"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/buy-process.css" />
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   /* EXAMPLE */
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

   /* string argument can be any CSS selector */
   /* .png_bg example is unnecessary */
   /* change it to what suits you! */
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
		body{behavior:url("{pigcms{$static_path}css/csshover.htc"); 
		}
		.category_list li:hover .bmbox {
filter:alpha(opacity=50);
	 
			}
  .gd_box{	display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<style>
		.payment-bank {
			margin-top: 10px;
			border: 1px solid #DFDFDF;
			padding: 5px 0 10px 20px;
			background-color: #F5F5F5;
		}
		.payment-banktit {
			height: 20px;
			line-height: 20px;
			margin-top: 5px;
			padding: 5px 0;
			font-family: \5b8b\4f53;
			cursor: pointer;
		}
		.payment-banktit b {
			display: inline-block;
			height: 20px;
			padding-left: 17px;
			color: #333;
			font-size: 14px;
		}
		.payment-bankcen {
			padding-top: 10px;
		}
		.bank {
			width: 786px;
			padding: 15px 0 0 20px;
		}
		.payment-bankcen .bank{
			padding-top: 0;
			width: 1210px;
		}
		.imgradio li {
			padding-left: 20px;
			width: 112px;
			height: 32px;
			float: left;
			position: relative;
			margin: 0 25px 15px 0;
			_display: inline;
			_zomm: 1;
		}
		.imgradio li input {
			position: absolute;
			left: 0;
			top: 10px;
		}
		.imgradio li label{
			cursor:pointer;
		}
		.payment-bankcen .bank .imgradio li {
			margin-right: 45px;
		}
		.clr {
			height: 0;
			font-size: 0;
			line-height: 0;
			clear: both;
			overflow: hidden;
		}
		.form-submit {
			margin: 30px 0 20px;
		}
		
		
#bd {
  width: 1210px;
  margin: 0 auto;
  padding: 10px 0 65px;
    border-top: 3px solid #fe5842;
  margin-top:20px;
}

#content {
  float: left;
  width: 1210px;
  _display: inline;
  padding: 0;
}
.cf {
  zoom: 1;
}		
		
.sysmsgw {
  width: 1150px;
  margin: 10px auto 0;
}
		
.common-tip {
  position: relative;
  margin-bottom: 10px;
  padding: 10px 30px;
  border: 1px #F5D8A7 solid;
  border-radius: 2px;
  background: #FFF6DB;
  font-size: 14px;
  text-align: center;
  color: #666;
  zoom: 1;
}
		
a.see_tmp_qrcode {
  color: #EE3968;
  text-decoration: none;
}
.mainbox {
  border: none;
  padding: 0;
  padding-bottom: 60px;
}
.form-field--error{
    border:1px #FF0000 solid;
}

.tip_s{width: 32%; height: 40px; border: 1px #999999 solid;line-height: 40px;text-align: center;font-size: 16px;display:-moz-inline-box;display:inline-block;cursor: pointer}
.tip_on{background-color: #06c1ae;color: #ffffff;border-color:#06c1ae }
		
	</style>
</head>
<body id="deal-buy" class="pg-buy pg-buy-process">
	<include file="Public:header_top"/>
	<div id="doc" class="bg-for-new-index">
		<div class="sysmsgw common-tip" id="sysmsg-error" style="display:none;"></div>
		<div id="bdw" class="bdw" style="min-height:700px;">
    		<div id="bd" class="cf">
			    <div id="content">
			    	<div>
			    		<div class="buy-process-bar-container">
						    <ol class="buy-process-desc steps-desc">
						        <li class="step step--current">
						            1. {pigcms{:L('_PLACE_ORDER_')}
						        </li>
						        <li class="step">
						            2. {pigcms{:L('_SELECT_PAY_MODE_')}
						        </li>
						        <li class="step">
						            3. {pigcms{:L('_SHOPPING_SUCCESS_')}
						        </li>
						    </ol>
						    <div class="progress">
						        <div class="progress-bar" style="width:66.66%"></div>
						    </div>
						</div>
			    	</div>
					<if condition="$order_info['order_type'] != 'recharge'">
						<div class="sysmsgw common-tip" style="margin-bottom:20px;" id="sysmsg-error">					
							<!--div class="sysmsg">
								<span class="J-msg-content"><span class="J-tip-status tip-status"></span>{pigcms{:L('_WECHAT_COUP_A_CARD_')}&nbsp;
								<if condition="$order_info['order_type'] eq 'group'">
								<a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>2000000000+$order_info['order_id']))}">{pigcms{:L('_CHECK_WECHAT_CODE_')}</a>
								<elseif condition="$order_info['order_type'] eq 'shop'" />
								<a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>3500000000+$order_info['order_id']))}" target="_blank">{pigcms{:L('_CHECK_WECHAT_CODE_')}</a>
								<else/>
								<a class="see_tmp_qrcode" href="{pigcms{:U('Index/Recognition/see_tmp_qrcode',array('qrcode_id'=>3000000000+$order_info['order_id']))}" target="_blank">{pigcms{:L('_CHECK_WECHAT_CODE_')}</a>
								</if>
								</span>
								<span class="close common-close">{pigcms{:L('_CLOSE_TXT_')}</span>
							</div-->
						</div>
					</if>
			        <form action="{pigcms{:U('Pay/go_pay')}" method="post" id="deal-buy-form" class="common-form J-wwwtracker-form">
			            <div class="mainbox cf" style="min-height:0px;">
			            	<div class="table-section summary-table">
			                    <table cellspacing="0" class="buy-table">
			                        <tr class="order-table-head-row">
			                        	<th class="desc">{pigcms{:L('_PRODUCT_NAME_')}</th>
			                        	<th class="unit-price">{pigcms{:L('_SINGLE_PRICE_')}</th>
                                                        <th class="amount">{pigcms{:L('_B_PURE_MY_69_')}</th>
                                                        <th class="col-total">{pigcms{:L('_B_PURE_MY_70_')}</th>
			                    	</tr>
				                    <volist name="order_info['order_content']" id="vo">
				                        <tr>
					                        <td class="desc">{pigcms{$vo.name}</td>
					                        <td class="money J-deal-buy-price">
                                                CAD$<span id="deal-buy-price">{pigcms{$vo.price}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo.extra_price}{pigcms{$config.extra_price_alias_name}</if></span>
					                        </td>
					                        <td class="deal-component-quantity ">{pigcms{$vo.num}</td>
					                        <td class="money total rightpadding col-total">
                                                CAD$<span id="J-deal-buy-total">{pigcms{$vo.money}<if condition="$config.open_extra_price eq 1 AND $vo.extra_price gt 0">+{pigcms{$vo['extra_price']*$vo['num']}{pigcms{$config.extra_price_alias_name}</if></span>
											</td>
					                    </tr>
				                    </volist>
			                        <tr>
										<td>
			                        	<if condition="!empty($leveloff) AND is_array($leveloff)">
											<span style="float: right;">{pigcms{:L('_B_PURE_MY_41_')}<strong style="color:#EA4F01;">{pigcms{$leveloff['lname']}</strong> &nbsp;{pigcms{$leveloff['offstr']}</span>
										</if>							
										</td>
				                        <td colspan="3" class="extra-fee total-fee rightpadding">
											<strong><if condition="!empty($leveloff) AND is_array($leveloff)">{pigcms{:L('_AFTER_DIS_')}</if>{pigcms{:L('_ORDER_TOTAL_')}(+ {pigcms{:L('_TAXATION_TXT_')})</strong>：
				                            <span class="inline-block money">
				                                CAD$<strong id="deal-buy-total-t">{pigcms{$order_info.order_total_money}<if condition="$config.open_extra_price eq 1 AND $order_info.extra_price gt 0">+{pigcms{$order_info.extra_price}{pigcms{$config.extra_price_alias_name}</if></strong>
				                            </span>
				                        </td>
			                    	</tr>
			                    	<if condition="$score_count gt 0">
										<tr>
											<td style="text-align:left;"  class="deal-component-quantity ">
												<strong>{pigcms{:L('_ACC_WITH_TICKET_')}</strong>：
												<span class="inline-block money" style="color:#EA4F01;">
													<strong class="deal-buy-total-t">{pigcms{$now_user.score_count}</strong>
													<input type="hidden" name="score_count" value="{pigcms{$now_user.score_count}">
												</span>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<strong>{pigcms{:L('_TORDER_MEAL_TICKET_')}</strong>：
												<button for="J-cart-minus" class="minus" id="minus" data-action="-" type="button">-</button><input type="text"  name="score_used_count"  autocomplete="off" class="f-text J-quantity J-cart-quantity" maxlength="9" name="q" data-max="{pigcms{$score_can_use_count}" data-min="0"  id="score_change" value="0"/><button for="J-cart-add" class="item plus" data-action="+" type="button" id="plus">+</button>
												&nbsp;&nbsp;&nbsp;&nbsp;
												<strong>{pigcms{:L('_MEAL_TICKET_DED_CASH_')}</strong>：
												<span class="inline-block money" style="color:#EA4F01;">
														<strong id="score_deducte_t">CAD${pigcms{$score_deducte|floatval=###}</strong>
														<input type="hidden" id="score_deducte" name="score_deducte" value="{pigcms{$score_deducte}">
												</span>
												
												
											</td>
										
										
											<td colspan="3" class="extra-fee total-fee rightpadding">
												{pigcms{:L('_USE_TICKET_DED_')}:<input type="checkbox" id ="use_score" name="use_score" value="1" <if condition="($score_checkbox eq 1) || (!empty($_GET['type']) && ($_GET['type'] == 'gift'))"> checked="checked" </if><if condition="($score_can_use_count eq 0) ||(($_GET['type'] == 'gift')) "> disabled="disabled" </if>>
											</td>
										</tr>
									</if>
								<if condition="$order_info['order_type'] != 'recharge'">
									<tr>						
										<td style="text-align:left;">
												<strong>{pigcms{:L('_AVAILABLE_BALANCE_')}</strong>：
												<span class="inline-block money" style="color:#EA4F01;">
														CAD$<strong id="deal-buy-total-t">{pigcms{$now_user.now_money}</strong>
												</span>
											
										</td>
										<td colspan="3" class="extra-fee total-fee rightpadding">
												{pigcms{:L('_USE_BALANCE_PAY_')}:<input type="checkbox" id ="use_balance" name="use_balance" value="1" <if condition="$now_user.now_money gt 0">checked="checked"<else />disabled="disabled"</if>>
										</td>
									</tr>
								</if>
									 
			                	</table>
			            	</div>
			            </div>
					
						<if condition="$order_info['order_type'] != 'recharge'">
							<div id="need-pay" >
								<strong>{pigcms{:L('_B_PURE_MY_70_')}</strong>：
								<span class="inline-block money" style="font-size:20px;color:#EA4F01;">
									CAD$<strong id="deal-buy-total-t" class="need_pay"><if condition="$pay_money lt 0">0.00<else />{pigcms{$pay_money}</if></strong>
								</span>
							</div>
						</if>
						
						<div id="pay_bank_list" style="display:none;">
							<div class="payment-bank">
                                <div id="payment_select">
                                    <div class="payment-banktit">
                                        <b class="open">{pigcms{:L('_SELECT_PAY_MODE_')}</b>
                                    </div>
                                    <div class="payment-bankcen">
                                        <div class="bank morebank">
                                            <ul class="imgradio">
                                                <volist name="pay_method" id="vo">
                                                    <php>if($pay_offline || $key != 'offline'){</php>
                                                    <li>
                                                        <label>
                                                            <input type="radio" name="pay_type" value="{pigcms{$key}" <php>if($key == 'offline'){</php>checked="checked"<php>}</php>><img src="{pigcms{$static_public}images/pay/{pigcms{$key}.png" style="height: 30px"/><!--br>{pigcms{$vo.name}-->
                                                            <!--img src="{pigcms{$static_public}images/pay/{pigcms{$key}.gif" width="112" height="32" alt="{pigcms{$vo.name}" title="{pigcms{$vo.name}"/-->
                                                        </label>
                                                    </li>
                                                    <php>}</php>
                                                </volist>
                                            </ul>
                                            <div class="clr"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="clr" style="border-bottom: 1px #cccccc solid"></div>
                                <div id="tip_label">
                                    <div class="payment-banktit">
                                        <b class="open">
                                            {pigcms{:L('_TIP_TXT_')}
                                        </b>
                                    </div>
                                    <div class="payment-bankcen">
                                        <div class="bank morebank">
                                            <dl class="list">
                                                <dd class="dd-padding">
                                                    <div id="tip_list" style="margin: auto;width: 98%">
                                                        <span class="tip_s tip_on">
                                                            15%
                                                        </span>
                                                        <span class="tip_s">
                                                            20%
                                                        </span>
                                                        <span class="tip_s">
                                                            25%
                                                        </span>
                                                    </div>
                                                    <div style="margin: 20px auto 5px;width: 98%">
                                                        {pigcms{:L('_SELF_ENTER_TIP_')}: $ <input type="text" id="tip_fee" name="tip_fee" size="20" style="height: 25px;border: 1px #333333 solid;">
                                                    </div>
                                                    <div style="margin: 20px auto 5px;width: 98%;font-size: 16px;">
                                                        <span>{pigcms{:L('_TIP_TXT_')}:</span><span id="tip_num">$0</span>
                                                        <span style="color: #ff0000;">{pigcms{:L('_B_PURE_MY_70_')}:</span><span id="add_tip">$0</span>
                                                    </div>
                                                </dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
								<div class="clr" style="border-bottom: 1px #cccccc solid"></div>
                                <div id="card">
                                    <div class="payment-banktit">
                                        <b class="open">
                                            {pigcms{:L('_CREDIT_CARD_')} --
                                            <a href="{pigcms{:U('User/Card/index')}" target="_blank">{pigcms{:L('_EDIT_TXT_')}</a>
                                        </b>
                                    </div>
                                    <div class="payment-bankcen">
                                        <div class="bank morebank">
                                            <ul class="imgradio">
                                                <if condition="count($card_list) eq 0">
                                                    <!--a href="{pigcms{:U('User/Card/index')}" target="_blank">
                                                        {pigcms{:L('_ADD_CREDIT_CARD_')}
                                                    </a>
                                                    <span id="refresh_card" style="font-size: 11px;color: #0c68cf;cursor:pointer"> -- {pigcms{:L('_REFRESH_TXT_')} </span-->
                                                <else />
                                                    <volist name="card_list" id="vo">
                                                        <div style="font-size: 12px;">
                                                            <input type="radio" name="card_id" value="{pigcms{$vo.id}">
                                                            {pigcms{$vo.name} -- {pigcms{$vo.card_num}
                                                        </div>
                                                        <if condition="$vo['status'] eq 0">
                                                            <div>
                                                                <span style="width:50px;display:-moz-inline-box;display:inline-block;">CVD：</span>
                                                                <input type="text" maxlength="3" size="20" name="cvd_{pigcms{$vo.id}" id="cvd_{pigcms{$vo.id}" value="" style="border: 1px #333333 solid;"/>
                                                            </div>
                                                        </if>
                                                    </volist>
                                                </if>
                                            </ul>
                                            <div class="clr"></div>
                                        </div>
                                        <div class="payment-bankcen" style="border-top: 1px #cccccc solid;">
                                            <div class="bank morebank">
                                                <input type="radio" name="card_id" value="0">
                                                {pigcms{:L('_USE_NEW_CARD_')}

                                                <ul class="imgradio">
                                                    <div>
                                                        <span style="width:150px;display:-moz-inline-box;display:inline-block;">{pigcms{:L('_CREDITHOLDER_NAME_')}：</span>
                                                        <input type="text" maxlength="20" size="20" name="name" id="card_name" value="" style="border: 1px #333333 solid;" />

                                                    </div>
                                                    <div>
                                                        <span style="width:150px;display:-moz-inline-box;display:inline-block;">{pigcms{:L('_CREDIT_CARD_NUM_')}：</span>
                                                        <input type="text" maxlength="20" size="20" name="card_num" id="card_num" value="" style="border: 1px #333333 solid;"/>
                                                    </div>
                                                    <div>
                                                        <span style="width:150px;display:-moz-inline-box;display:inline-block;">{pigcms{:L('_EXPRIRY_DATE_')}：</span>
                                                        <input type="text" maxlength="4" size="20" name="expiry" id="expiry" value="" style="border: 1px #333333 solid;"/>
                                                    </div>
                                                    <div>
                                                        <span style="width:150px;display:-moz-inline-box;display:inline-block;">{pigcms{:L('_IS_SAVE_')}：</span>
                                                        <input type="checkbox" name="save" id="save" value="1"/>
                                                    </div>
                                                    <div>
                                                        <span style="width:150px;display:-moz-inline-box;display:inline-block;">CVD：</span>
                                                        <input type="text" maxlength="3" size="20" name="cvd" id="cvd" value="" style="border: 1px #333333 solid;"/>
                                                    </div>
                                                </ul>
                                            </div>

                                        </div>
                                    </div>
                                </div>
							</div>
						</div>
						
						<div class="form-submit">
							<input type="hidden" name="order_id" value="{pigcms{$order_info.order_id}"/>
				    		<input type="hidden" name="order_type" value="{pigcms{$order_info.order_type}"/>
                            <input type="hidden" name="tip" value="">
			                <input id="J-order-pay-button" type="submit" class="btn btn-large btn-pay" name="commit" value="{pigcms{:L('_B_PURE_MY_81_')}"/><br/>
			            </div>
			    	</form>
				</div>
                <!--form action="https://esqa.moneris.com/HPPDP/index.php" method="post" id="moneris_form"-->
                <form action="{pigcms{:U('Index/Pay/MonerisPay')}" method="post" id="moneris_form">
                    <INPUT TYPE="HIDDEN" NAME="ps_store_id" VALUE="">
                    <INPUT TYPE="HIDDEN" NAME="hpp_key" VALUE="">
                    <INPUT TYPE="HIDDEN" NAME="charge_total" VALUE="{pigcms{$order_info.order_total_money}"><!--{pigcms{$pay_money}-->
                    <input type="hidden" name="cust_id" value="{pigcms{:md5($order_info.uid)}">
                    <input type="hidden" name="order_id" value="vicisland_{pigcms{$order_info.order_id}">
                    <input type="hidden" name="rvarwap" value="0">
                    <input type="hidden" name="credit_id" value="0">
                </form>
    		</div>
    		<!-- bd end -->
		</div>
	</div>
	<script src="http://hf.pigcms.com/static/js/artdialog/jquery.artDialog.js"></script>
	<script src="http://hf.pigcms.com/static/js/artdialog/iframeTools.js"></script>
	<script type="text/javascript">
		var orderid = 0;
		$(function(){
			$("#deal-buy-form").submit(function(event) {
			   $("#J-order-pay-button").val("{pigcms{:L('_DEALING_TXT_')}");
			   $("#J-order-pay-button").attr("disabled", "disabled");
			   var pay_type = $('input[name="pay_type"]:checked').val();
			   if(pay_type == 'moneris'){
                   event.preventDefault();
                   // alert('This function is currently unavailable.');
                   // $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                   // $("#J-order-pay-button").removeAttr("disabled");
                   if($('input[name="credit_id"]').val() == 0){//使用新的信用卡
                        if(check_card()){
                            var re_data = {
                                'name':$('#card_name').val(),
                                'card_num':$('#card_num').val(),
                                'expiry':$('#expiry').val(),
                                'cvd':$('#cvd').val(),
                                'save':$('input[name="save"]:checked').val(),
                                // 'charge_total':$('input[name="charge_total"]').val(),
                                'charge_total':$('#add_tip').text().replace('$', ""),
                                'order_id':"Tutti{pigcms{$order_info.order_type}_{pigcms{$order_info.order_id}",
                                'cust_id':'{pigcms{:md5($order_info.uid)}',
                                'rvarwap':$('input[name="rvarwap"]').val(),
                                'tip':$('#tip_num').text().replace('$', "")
                            };

                            $.post($('#moneris_form').attr('action'),re_data,function(data){
                                if(data.status == 1){
                                    art.dialog({
                                        title: 'Message',
                                        id: 'moneris_pay',
                                        opacity:'0.4',
                                        lock:true,
                                        fixed: true,
                                        resize: false,
                                        content: "{pigcms{:L('_PAYMENT_SUCCESS_')}"
                                    });
                                    setTimeout("window.location.href = '"+data.url+"'",200);
                                }
                            });
                        }else{
                            alert("{pigcms{:L('_PLEASE_RIGHT_CARD_')}");
                            $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                            $("#J-order-pay-button").removeAttr("disabled");
                            $("html,body").animate({"scrollTop":$('#card').offset().top},900);
                        }
                   }else{
                       var re_data = {
                           'credit_id':$('input[name="credit_id"]').val(),
                           // 'charge_total':$('input[name="charge_total"]').val(),
                           'charge_total':$('#add_tip').text().replace('$', ""),
                           'order_id':"Tutti{pigcms{$order_info.order_type}_{pigcms{$order_info.order_id}",
                           'cust_id':'{pigcms{:md5($order_info.uid)}',
                           'rvarwap':$('input[name="rvarwap"]').val(),
                           'tip':$('#tip_num').text().replace('$', "")
                       };

                       var cvd_id = 'cvd_'+$('input[name="credit_id"]').val();

                       var cvd = $('#'+cvd_id).val();
                       if(typeof (cvd) != "undefined"){
                           if(!/^\d{3}$/.test(cvd)){
                               alert('Please input CVD');
                               $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                               $("#J-order-pay-button").removeAttr("disabled");
                               return false;
                           }else{
                               re_data['cvd'] = cvd;
                           }
                       }

                       $.post($('#moneris_form').attr('action'),re_data,function(data){
                           if(data.status == 1){
                               setTimeout("window.location.href = '"+data.url+"'",200);
                           }else{
                               alert(data.info);
                               $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                               $("#J-order-pay-button").removeAttr("disabled");
                           }
                       });
                   }

                   // if($('input[name="credit_id"]').val()){
                   //
                   // }else{
                   //     alert("{pigcms{:L('_PLEASE_ADD_CARD_')}");
                   //     $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                   //     $("#J-order-pay-button").removeAttr("disabled");
                   //     $("html,body").animate({"scrollTop":$('#card').offset().top},900);
                   // }

                   // $.ajax({
                   //     url:"{pigcms{:U('Wap/Pay/getPayMessage')}",
                   //     type:'post',
                   //     data:{pay_type:pay_type,key_list:"ps_store_id|hpp_key"},
                   //     dataType:"json",
                   //     success:function(data){
                   //         $('input[name="ps_store_id"]').val(data['ps_store_id']);
                   //         $('input[name="hpp_key"]').val(data['hpp_key']);
                   //
                   //         $('#moneris_form').submit();
                   //     }
                   // });
               }else if(pay_type == 'weixin' || pay_type == 'alipay'){
                   event.preventDefault();
                   var re_data = {
                       'charge_total':$('#add_tip').text().replace('$', ""),
                       'order_id':"Tutti{pigcms{$order_info.order_type}_{pigcms{$order_info.order_id}",
                       'cust_id':'{pigcms{:md5($order_info.uid)}',
                       'rvarwap':$('input[name="rvarwap"]').val(),
                       'coupon_id':$('input[name="coupon_id"]').val(),
                       'tip':$('#tip_num').text().replace('$', ""),
                       'order_type':"{pigcms{$order_info.order_type}",
                       'pay_type':pay_type
                   };
                   $.post('{pigcms{:U("Pay/WeixinAndAli")}',re_data,function(data){
                       //success
                       if(data.status == 1){
                           if(pay_type == 'alipay'){
                               art.dialog({
                                   title: 'AliPay',
                                   id: 'ali_pay_pc',
                                   width:'350px',
                                   opacity:'0.4',
                                   lock:true,
                                   fixed: true,
                                   resize: false,
                                   content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">{pigcms{:L("_PAYMENT_JUMP_")}</p><p style="text-align:center;">'+data.url+'</p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"></p>',
                                   cancel: function(){
                                   $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                                   $("#J-order-pay-button").removeAttr("disabled");
                               },
                           });
                           }else{
                               art.dialog({
                                    title: 'QRCode',
                                    id: 'weixin_pay_qrcode',
                                    width:'350px',
                                    opacity:'0.4',
                                    lock:true,
                                    fixed: true,
                                    resize: false,
                                    content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">QRCode</p><p style="text-align:center;"><img src="{pigcms{:U('Recognition/get_own_qrcode')}&qrCon='+data.url+'" style="width:240px;height:240px;"></p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"><input id="J-order-weixin-button" type="button" class="btn btn-large btn-pay" value=\'{pigcms{:L("_IS_COMPLETE_")}\'/></p>',
                                    cancel: function(){
                                        $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                                        $("#J-order-pay-button").removeAttr("disabled");
                                    },
                                });
                           }
                       }else{
                           alert(data.info);
                           $("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
                           $("#J-order-pay-button").removeAttr("disabled");
                       }

                   },'json');
               }
			});
			$('#sysmsg-error .close').click(function(){
				$('#sysmsg-error').remove();
			});
			$('.see_tmp_qrcode').click(function(){
				var qrcode_href = $(this).attr('href');
				art.dialog.open(qrcode_href+"&"+Math.random(),{
					init: function(){
						var iframe = this.iframe.contentWindow;
						window.top.art.dialog.data('login_iframe_handle',iframe);
					},
					id: 'login_handle',
					title:'请使用微信扫描二维码',
					padding: 0,
					width: 430,
					height: 433,
					lock: true,
					resize: false,
					background:'black',
					button: null,
					fixed: false,
					close: null,
					left: '50%',
					top: '38.2%',
					opacity:'0.4'
				});
				return false;
			});
			// $('#deal-buy-form').submit(function(){
			// 	if($('input[name="pay_type"]:checked').val() == 'weixin' || $('input[name="pay_type"]:checked').val() == 'weifutong'){
			// 		art.dialog({
			// 			title: '提示信息',
			// 			id: 'weixin_pay_tip',
			// 			opacity:'0.4',
			// 			lock:true,
			// 			fixed: true,
			// 			resize: false,
			// 			content: '正在获取微信支付相关信息，请稍等...'
			// 		});
			// 		$.post($('#deal-buy-form').attr('action'),$('#deal-buy-form').serialize(),function(result){
			// 			art.dialog.list['weixin_pay_tip'].close();
			// 			if(result.status == 1){
			// 				orderid = result.orderid;
			// 				art.dialog({
			// 					title: '请使用微信扫码支付',
			// 					id: 'weixin_pay_qrcode',
			// 					width:'350px',
			// 					opacity:'0.4',
			// 					lock:true,
			// 					fixed: true,
			// 					resize: false,
			// 					content: '<p style="margin-top:20px;margin-bottom:20px;text-align:center;font-size:16px;color:black;">请使用微信扫描二维码进行支付</p><p style="text-align:center;"><img src="{pigcms{:U('Recognition/get_own_qrcode')}&qrCon='+result.info+'" style="width:240px;height:240px;"></p><p style="text-align:center;margin-top:20px;margin-bottom:20px;"><input id="J-order-weixin-button" type="button" class="btn btn-large btn-pay" value="已支付完成"/></p>',
			// 					cancel: function(){
			// 						$("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
			// 						$("#J-order-pay-button").removeAttr("disabled");
			// 					},
			// 				});
			// 			}else{
			// 				$("#J-order-pay-button").val("{pigcms{:L('_B_PURE_MY_81_')}");
			// 				$("#J-order-pay-button").removeAttr("disabled");
			// 				art.dialog({
			// 					title: '错误提示：',
			// 					id: 'weixin_pay_error',
			// 					opacity:'0.4',
			// 					lock:true,
			// 					fixed: true,
			// 					resize: false,
			// 					content: result.info
			// 				});
			//
			// 			}
			// 		});
			// 		return false;
			// 	}
			// });
			$('#J-order-weixin-button').live('click',function(){
				window.location.href="{pigcms{:U('User/Index/shop_order_view',array('order_id'=>$order_info['order_id']))}";
			});
		});

        //garfunkel add
        $("input[name='pay_type']").click(isShowCredit);
        var isb = false;
        $(function(){
            if(parseFloat($('input[name="charge_total"]').val()) <= 20){
                isb = true;
            }
            var tipxn = new Array(3,4,5);
            var i = 0;
            $('#tip_list').children('span').each(function(){
                $(this).click(tip_select);
                if(isb){
                    $(this).text('$' + tipxn[i]);
                }
                i++;
            });
            //默认第一个选中
            $('input[name="card_id"]:first').attr('checked','checked');
            $('input[name="credit_id"]').val($('input[name="card_id"]').val());

            CalTip();
            isShowCredit();
        });

        //计算小费
        function CalTip(){
            var tipNum = 0;

            var num = $('#tip_fee').val();
            if(/^\d+(\.\d{1,2})?$/.test(num) && num != ""){
                tipNum = parseFloat(num);
            }else{
                $('#tip_list').children('span').each(function(){
                    if($(this).hasClass('tip_on')){
                        if(isb)
                            tipNum = parseFloat($(this).text().replace('$', ""));
                        else
                            tipNum = $('input[name="charge_total"]').val() *  ($(this).text().replace(/%/, "")/100);
                    }
                });
            }
            var totalNum = parseFloat($('input[name="charge_total"]').val()) + parseFloat(tipNum);

            $('input[name="tip"]').val(tipNum.toFixed(2));

            $('#tip_num').text('$' + tipNum.toFixed(2));
            $('#add_tip').text('$' + totalNum.toFixed(2));

            var user_money = {pigcms{$now_user.now_money};
            if(totalNum > user_money){
                $('#balance_money').css('color','#C1B9B9');
                $('#use_balance').removeAttr('checked');
                $('#use_balance').attr('disabled','disabled');

                $('#normal-fieldset').css('display','block');
                $('#payment_select').show();
                $('.imgradio').find('input:last').attr('checked','checked');
                $('#pay_bank_list').css('display', 'block');
                $('#need-pay').css('display', 'block');
            }else{
                $('#balance_money').css('color','#666666');
                $('#use_balance').removeAttr('disabled');
            }
            $('.need_pay').html(totalNum.toFixed(2));
        }

        function tip_select(){
            $('#tip_list').children('span').each(function(){
                $(this).removeClass('tip_on');
            });
            $(this).addClass('tip_on');
            $('#tip_fee').val("");
            CalTip();
        }

        $('#tip_fee').live('focusin focusout',function(event){
            if(event.type == 'focusin'){
                $(this).siblings('.inline-tip').remove();$(this).removeClass('form-field--error');
            }else{
                $(this).val($.trim($(this).val()));
                var num = $(this).val();
                if(num != ''){
                    if(!/^\d+(\.\d{1,2})?$/.test(num)){
                        alert("{pigcms{:L('_PLEASE_RIGHT_PRICE_')}");
                        $(this).focus();
                        $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").addClass('form-field--error');
                    }else{
                        $('#tip_list').children('span').each(function(){
                            $(this).removeClass('tip_on');
                        });
                    }
                }else{
                    var isC = false;
                    $('#tip_list').children('span').each(function(){
                        if($(this).hasClass('tip_on')){
                            isC = true;
                        }
                    });
                    if(!isC){
                        var i=0;
                        $('#tip_list').children('span').each(function(){
                            if(i == 1){
                                $(this).addClass('tip_on');
                            }
                            i++;
                        });
                    }
                }
                CalTip();
                isShowCredit();
            }
        });

        $('input[name="card_id"]').click(function(){
            $('input[name="credit_id"]').val($(this).val());
        });

        function isShowCredit(){
            var tip = parseFloat($('#tip_num').text().replace('$', "")).toFixed(2);
            var totalNum = parseFloat($('input[name="charge_total"]').val()).toFixed(2);
            var show_money = (parseFloat(totalNum) + parseFloat(tip)).toFixed(2);
            if($("#use_balance").is(':checked')==true){
                $('input[name="pay_type"]').removeAttr('checked');
                $('#pay_bank_list').css('display', 'block');
                $('#payment_select').hide();
                $('#tip_label').show();
                $('#card').hide();
                $('.need_pay').html(show_money);
            }else{
                var pay_type = $('input[name="pay_type"]:checked').val();
                if(pay_type == 'moneris'){
                    $('#card').show();
                    $('#tip_label').show();
                    $('.need_pay').html(show_money);
                }else if(pay_type == 'weixin' || pay_type == 'alipay'){
                    $('#tip_label').show();
                    $('#card').hide();
                    $('.need_pay').html(show_money);
                }else{
                    $('#card').hide();
                    $('#tip_label').hide();
                    $('.need_pay').html(totalNum);
                }
            }
        }

        $('#refresh_card').click(function(){
            window.location.reload();
        });

        $('#card_name').live('focusin focusout',function(event){
            if(event.type == 'focusin'){
                $(this).siblings('.inline-tip').remove();$(this).removeClass('form-field--error');
            }else{
                $(this).val($.trim($(this).val()));
                var name = $(this).val();
                if(name.length < 2){
                    $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").addClass('form-field--error');
                }
            }
        });
        $('#card_num').live('focusin focusout',function(event){
            if(event.type == 'focusin'){
                $(this).siblings('.inline-tip').remove();$(this).removeClass('form-field--error');
            }else{
                $(this).val($.trim($(this).val()));
                var num = $(this).val();
                if(!/^\d{13,}$/.test(num)){
                    $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").addClass('form-field--error');
                }
            }
        });
        $('#expiry').live('focusin focusout',function(event){
            if(event.type == 'focusin'){
                $(this).siblings('.inline-tip').remove();$(this).removeClass('form-field--error');
            }else{
                $(this).val($.trim($(this).val()));
                var expiry = $(this).val();
                if(expiry.length < 4 || expiry.length > 4){
                    $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").addClass('form-field--error');
                }
            }
        });

        $('#cvd').live('focusin focusout',function(event){
            if(event.type == 'focusin'){
                $(this).siblings('.inline-tip').remove();$(this).closest('.form-field').removeClass('form-field--error');
            }else{
                $(this).val($.trim($(this).val()));
                var cvd = $(this).val();
                if(!/^\d{3}$/.test(cvd)){
                    $(this).after("<span class='inline-tip'><i class='tip-status tip-status--opinfo'></i></span>").closest('.form-field').addClass('form-field--error');
                }
            }
        });
        function check_card(){
            var isT = true;
            if($('#card_name').val().length < 2 || !/^\d{13,}$/.test($('#card_num').val()) || $('#expiry').val().length != 4 || !/^\d{3}$/.test($('#cvd').val())){
                isT = false;
            }
            return isT;
        }
	</script>
	<include file="Public:footer"/>
</body>
</html>
