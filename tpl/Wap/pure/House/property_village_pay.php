<!DOCTYPE html>
<html>
<head id="Head1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" />
<meta name="keywords" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link type="text/css" href="/images/icon.ico" rel="shortcut icon" />

<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
<link type="text/css" href="{pigcms{$static_path}css/property_base.css" rel="stylesheet" />
<link type="text/css" href="{pigcms{$static_path}css/property_page.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
<title>{pigcms{$now_village.village_name}</title></head>
<body id="body">
 <header class="pageSliderHide"><div id="backBtn"></div>{pigcms{$pay_name}</header>
<script type="text/javascript" language="javascript">
var ajax_get_presented_property_month_url = "{pigcms{:U('ajax_get_presented_property_month')}";
var ajax_diy_get_presented_property_month_url = "{pigcms{:U('ajax_diy_get_presented_property_month')}";
var village_id = "{pigcms{$_GET['village_id']}";
</script>
<style type="text/css">
.minus,.plus{ padding:0 .2rem;border: none; color:#ff6600}
</style>
<div class="czCon">
	<div class="tcTime clearfix">
		<p class="fl cp">
			业主信息：
		</p>
		<p class="dq">
			房屋面积：<span class="green">{pigcms{$now_user_info['housesize']}㎡</span>
			&nbsp;&nbsp;
			物业单价：
			<span class="green">
			<if condition = '$now_user_info["property_fee"] neq "0.00"'>
				{pigcms{$now_user_info["property_fee"]}
			<else />
				{pigcms{$now_village["property_price"]}
			</if>
			元/平方米/月
			</span>
			
			&nbsp;&nbsp;
			类型：
			<if condition = '$now_user_info["floor_type_name"]'>
				<span class="green">{pigcms{$now_user_info["floor_type_name"]}</span>
			<else />
				暂无
			</if>
			
		</p>
		<p class="dq">
			<if condition='$now_user_info["property_time_str"]'>物业费服务时间：{pigcms{$now_user_info["property_time_str"]}</if>
		</p>
	</div>


	<p class="xzp">请<span>选择</span>您要缴纳的物业周期</p>
	<div class="kdCon">
		<ul id="package" class="clearfix scUl">
			<volist name='property_list["list"]' id="property">
				<li id="p{pigcms{$property['id']}">
				{pigcms{$property['property_month_num']}个月
					<p class="dh"></p>
				</li>
			</volist>
			<if condition='$now_village["has_property_pay"]'>
				<li id="p0">
					<button type="button" class="btn btn-weak minus">-</button><input type="text" style="width:.8rem;height:28px; border:none;text-align:center; font-size:12px" id="diy_propertyt_month_num" readonly="readonly" /><button type="button" class="btn btn-weak plus">+</button>
				</li>
			</if>
		</ul>
		
		
		<div class="Give Clearfix">
			<p id="gift" class="zs1 fl clearfix">
				<span class="fl sp1">送</span>
				<span id="addmonth" class="fl sp2">0个月</span>
			</p>
			
		</div>

		<div class="tcData" id="tcSelect">
			<div class="gmShu">
				<p class="pp3">物业总价<b>$<span id="totalmoney">0.00</span></b></p>
			</div>
		</div>
		<p class="order" id="confirm"><img src="{pigcms{$static_path}images/ljOrder.png"/></p>
		
		<p class="ycp">计算公式：物业总价 = 房屋面积 * 物业单价 * 购买月份</p>
		<p class="red">注：单位：月。<if condition='$now_village["has_property_pay"]'>自定义最大36个月</if></p>
	</div>
</div>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/BuySet.js" type="text/javascript"></script>
</body>
</html>