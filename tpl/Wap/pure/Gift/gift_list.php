<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.gift_alias_name}列表</title>
<include file="Public:gift_header" />
</head>

<body>
<div class="lodingCover">
    <div class="spinner">
        <div class="rect1"></div>
        <div class="rect2"></div>
        <div class="rect3"></div>
        <div class="rect4"></div>
        <div class="rect5"></div>
    </div>
</div>
<nav class="topNav filterNav">
    <ul class="box">
        <!--li class="b-flex">
            <a <if condition='!empty($_GET["type"]) && ($_GET["type"] == "hot")'>class="active"</if> href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'type'=>'hot'))}">
                <span>热门</span>
            </a>
        </li-->
		
		<li class="b-flex">
            <p>
				<if condition='$_GET["type"] eq "new"'>
					<span>今日新品</span>
				<elseif condition='$_GET["type"] eq "integral"'/>
					<span>高端生活</span>
				<else />
					<span>{pigcms{$gift_category_detail['cat_name']}</span>
				</if>
                
            </p>
        </li>
        <li class="b-flex">
            <a id="gift_sort" <if condition='!empty($_GET["order"]) && (in_array($_GET["order"],array("integral_desc","integral_asc")))'>class="active"</if> href="javascript:void(0)">
                <span>{pigcms{$config['score_name']}值 <if condition='$_GET["order"] eq "integral_desc"'><i class="down fa fa-long-arrow-down"></i><elseif condition='$_GET["order"] eq "integral_asc"' /><i class="down fa fa-long-arrow-up"></i></if></span>
            </a>
        </li>
        <!--li class="b-flex">
            <a <if condition='!empty($_GET["type"]) && ($_GET["type"] == "new")'>class="active"</if> href="{pigcms{:U('gift_list',array('cat_id'=>$_GET['cat_id'],'type'=>'new'))}">
                <span>新品</span>
            </a>
        </li-->
    </ul>
</nav>

<section class="list">
    <ul>
		<if condition='$gift_list["list"]'>
			<volist name='gift_list["list"]' id='gift'>
				<li class="item item2"  onclick="location.href='{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}'">
					<div class="wrap">
						<div class="fl i-pic">
							<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['wap_pic_list'][0]}"/>
						</div>
						<div class="ofh desc">
							<div class="wrap pr">
								<h2>{pigcms{$gift.gift_name}</h2>
								<if condition='in_array($gift["exchange_type"],array(0,2))'>
									<p>{pigcms{$gift.payment_pure_integral} <em>{pigcms{$config['score_name']}</em>
								<else />
									<p>{pigcms{$gift.payment_integral} <em>{pigcms{$config['score_name']}</em> + {pigcms{$gift.payment_money} <em>元</em></p>
								</if>
								<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}" class="aButton pa">马上兑换</a>
								<small class="tip">已兑换
								<if condition='!empty($gift["exchanged_num"])'>
									<em>{pigcms{$gift["exchanged_num"]}</em>
								<else />
									<em>{pigcms{$gift["sale_count"]}</em>
								</if>
								件</small>
							</div>
						</div>
					</div>
				</li>
			</volist>
		<else />
			<p style=" text-align:center">暂无礼品</p>
		</if>
    </ul>
</section>

<include file="Public:gift_footer" />
<script type="text/javascript" language="javascript">
$(function(){
	$('#gift_sort').click(function(){
		var order = "{pigcms{$_GET['order']}";
		var type = "{pigcms{$_GET['type']}";
		var url = "{pigcms{:U('gift_list')}";
		var cat_id = "{pigcms{$_GET['cat_id']}";
		
		if(type){
			url += "&type=" + type;
		}else{
			url += "&cat_id" + cat_id;
		}
		
		if(order == 'integral_desc'){
			url += '&order=integral_asc';
			
		}else if(order == 'integral_asc'){
			url += '&order=integral_desc';
		}else{
			url += '&order=integral_desc';
		}
		location.href=url;
	});
	
})
</script>

<script type="text/javascript">
window.shareData = {  
			"moduleName":"Gift",
			"moduleID":"0",
			"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
			"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Gift/gift_list',array('cat_id'=>$_GET['cat_id']))}",
			"tTitle": "{pigcms{$config.gift_alias_name}列表",
			"tContent": "{pigcms{$config.site_name}"
};
</script>
{pigcms{$shareScript}
</body>
</html>