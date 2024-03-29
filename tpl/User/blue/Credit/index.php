<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{:L('_MY_BALANCE_')} | {pigcms{:L('_VIC_NAME_')}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<style>
a.btn {
  display: inline-block;
  vertical-align: middle;
  padding: 7px 20px 6px;
  font-size: 14px;
  font-weight: 700;
  -webkit-font-smoothing: antialiased;
  line-height: 1.5;
  letter-spacing: .1em;
  text-align: center;
  text-decoration: none;
  border-width: 0 0 1px;
  border-style: solid;
  background-repeat: repeat-x;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
  -moz-user-select: -moz-none;
  -ms-user-select: none;
  -webkit-user-select: none;
  user-select: none;
  cursor: pointer;
  color: #fff;
  background-color: #2eb7aa;
  border-color: #008177;
  filter: progid:DXImageTransform.Microsoft.gradient(gradientType=0, startColorstr='#FF2BB8AA', endColorstr='#FF2EB7AA');
  background-size: 100%;
  background-image: -moz-linear-gradient(top,#2bb8aa,#2eb7aa);
  background-image: -webkit-linear-gradient(top,#2bb8aa,#2eb7aa);
  background-image: linear-gradient(to bottom,#2bb8aa,#2eb7aa);
}
.pay_form {
	width:350px;
}
.pay_form .pay_tip{
	font-size:12px;margin-bottom:30px;
}
.pay_form #recharge_money,#truename,#withdraw_money{
	/* margin-left: 80px; */
	width: 88px;
	height: 16px;
	padding: 5px;
	border: 1px solid #aaa;
	line-height: 16px;
	vertical-align: top;
}
.pay_form .form-field label{
	line-height:16px;
}
.pay_form .form-field .inline-link {
  margin: 0 0 0 8px;
  font-size: 12px;
  line-height: 26px;
  vertical-align: top;
  zoom: 1;
}
</style>
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/common.js"></script>
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css">
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
    <include file="Public:facebook"/>
</head>
<body id="credit" class="has-order-nav" style="position:static;">
<include file="Public:google"/>
<include file="Public:header_top"/>
 <div class="body pg-buy-process">
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
		
				<div class="menu_left hide">
					<div class="menu_left_top">{pigcms{:L('_ALL_CLASSIF_')}</div>
					<div class="list">
						<!--ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul-->
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{:lang_substr($vo['name'],C('DEFAULT_LANG'))}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<include file="Public:scroll_msg"/>
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/order-nav.v0efd44e8.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/account.v1a41925d.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/table-section.v538886b7.css" />
				<include file="Public:sidebar"/>
				<div id="content" class="coupons-box">
					<div class="mainbox mine">
						<div class="balance">{pigcms{:L('_ACCOUNT_BALANCE_')}： <strong>${pigcms{$now_user.now_money}</strong><if condition="$now_user.frozen_money gt 0 AND $now_user.free_time gt $_SERVER['REQUEST_TIME'] AND $config.open_frozen_money eq 1"><span class="frozen_money" style="font-size:12px;">(冻结{pigcms{$now_user.frozen_money|floatval}元)<i class="reason"></i></span></if> <a class="btn" id="recharge_amount">{pigcms{:L('_RECHARGE_TXT_')}</a><if condition="$config.company_pay_open eq 1"><a class="btn" id="withdraw">{pigcms{:L('_PUT_FORWARD_')}</a></if></div>
						<ul class="filter cf">
							<li class="current"><a href="{pigcms{:U('Credit/index')}">{pigcms{:L('_BALANCE_RECORD_')}</a></li>
						</ul>
						<div class="table-section">
							<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<th width="130">{pigcms{:L('_TIME_TXT_')}</th>
									<th width="auto">{pigcms{:L('_DETAIL_TXT_')}</th>
									<th width="110">{pigcms{:L('_AMOUNT_TXT_')}</th>
								</tr>
								<volist name="money_list" id="vo">
									<tr>
										<td>{pigcms{$vo.time|date='Y-m-d H:i:s',###}</td>
										<td class="detail">{pigcms{$vo.desc}</td>
										<if condition="$vo['type'] eq 1">
											<td class="income">+{pigcms{$vo.money}</td>
										<else/>
											<td class="expense">-{pigcms{$vo.money}</td>
										</if>
									</tr>
								</volist>
							</table>
						</div>
						{pigcms{$pagebar}
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
	<include file="Public:footer"/>
	<style>
		.webuploader-container{
			position:relative;
		}
		.webuploader-element-invisible{
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px,1px,1px,1px);
		}
		.webuploader-pick{
			position: relative;
			display: inline-block;
			cursor: pointer;
			color: #fff;
			text-align: center;
			border-radius: 3px;
			overflow: hidden;
			width:100%;
			height:100%;
		}
		.webuploader-pick-disable{
			opacity: 0.6;
			pointer-events:none;
		}
		.p-node-wordcounter {
			position: absolute;
			padding: 1px 5px;
			line-height: 18px;
			font-size: 12px;
			color: #FFF;
			background: #0B0;
			border-radius: 0 0 3px 3px;
		}
	</style>
	<script src="{pigcms{$static_public}js/webuploader.min.js"></script>
	<script>
		$(function(){
			$('#recharge_amount').click(function(){
				art.dialog({
					id: 'pay_handle',
					title:"{pigcms{:L('_RECHARGE_TXT_')}",
					padding: 0,
					width: 380,
					height: 240,
                    okVal:"{pigcms{:L('_B_PURE_MY_85_')}",
                    cancelVal:"{pigcms{:L('_B_PURE_MY_86_')}",
					lock: true,
					resize: false,
					background:'black',
					init:function(){
						$('#recharge_money').focus();
					},
					fixed: false,
					left: '50%',
					top: '38.2%',
					opacity:'0.4',
					content:'<div class="pay_form"><div class="pay_tip">{pigcms{:L(\'Balance_pro\')}:<br/>{pigcms{$recharge_str}</div><div class="form-field"><label for="recharge_money">Amount：</label><span class="inline-link">$</span><input type="text" name="money" autocomplete="off" id="recharge_money"/></div><div id="money_tips" style="color:red;"></div></div>',
					ok:function(){
						var money = parseFloat($('#recharge_money').val());
						if(isNaN(money)){
							$('#money_tips').html("{pigcms{:L('_ENTER_LEGAL_AMOUNT_')}");
							$('#recharge_money').focus();
							return false;
						}else if(money > 10000){
							$('#money_tips').html("{pigcms{:L('_RECHARGE_TEN_TH_')}");
							$('#recharge_money').focus();
							return false;
						}else if(money < 0.1){
							$('#money_tips').html("{pigcms{:L('_RECHARGE_POINTONE_')}");
							$('#recharge_money').focus();
							return false;
						}else{
							$('#money_tips').empty();
							window.location.href = '{pigcms{:U('Credit/recharge')}&money='+money;
						}
					},
					cancel:true
				});
			});
			
			$('.frozen_money').on('click',function(){
				art.dialog({   
					content: '冻结理由：{pigcms{$now_user.frozen_reason}',   
					id: 'frozen'  
				}); 
			
				
			});
			
			<if condition="$config.company_pay_open eq 1">
			$('#withdraw').click(function(){
				 $.ajax({
					url: '{pigcms{:U('Credit/withdraw')}',
					type: 'POST',
					dataType: 'json',
					success:function(data){
						if(!data){

							alert("您没有绑定微信或余额不足，不能提款");
							window.location.href = '{pigcms{:U('Credit/index')}';
						}else{
						art.dialog({
							id: 'withdraw_handle',
							title:'账户提款',
							padding: 0,
							width: 380,
							height: 200,
							lock: true,
							resize: false,
							background:'black',
							init:function(){
								$('#withdraw_money').focus();
							},
							fixed: false,
							left: '50%',
							top: '38.2%',
							opacity:'0.4',
							content:'<div class="pay_form"><div class="pay_tip">提款金额不能超过余额：{pigcms{$can_withdraw_money} 元<if condition="$score_recharge_money gt 0">，您有 {pigcms{$score_recharge_money} 元是{pigcms{$config['score_name']}兑换所得，不能提现！</if></div><div class="form-field"><label for="truename">真实姓名：</label><input type="text" name="true" autocomplete="off" id="truename"/></div><div class="form-field"><label for="withdraw_money">提款金额：</label><input type="text" name="money" autocomplete="off" id="withdraw_money"/><span class="inline-link">元</span></div><div id="money_tips" style="color:red;"></div></div>',
							ok:function(){
								var money = parseFloat($('#withdraw_money').val());
								var name = $('#truename').val();

								if(isNaN(money)){
									$('#money_tips').html('请输入合法的金额！');
									$('#withdraw_money').focus();
									return false;
								}else if(money > {pigcms{$can_withdraw_money}){
									$('#money_tips').html('提款金额最高不能超过{pigcms{$can_withdraw_money}元');
									$('#withdraw_money').focus();
									return false;
								}else if(money < <if condition="C(\'config.company_least_money\')">{pigcms{:C(\'config.company_least_money\')}<else />0.1</if>){
									$('#money_tips').html('单次提款金额最低不能低于 <if condition="C(\'config.company_least_money\')">{pigcms{:C(\'config.company_least_money\')}<else />0.1</if> 元');
									$('#withdraw_money').focus();
									return false;
								}else{
									$('#money_tips').empty();
									window.location.href = '{pigcms{:U('Credit/withdraw')}&money='+money+'&n='+encodeURI(name);
								}
							},
							cancel:true
						});
						}

					},
				});

			});
			</if>
		});
	</script>
</body>
</html>
