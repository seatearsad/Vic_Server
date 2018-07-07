<include file="Public:gift_header"/>
<section class="breadNav">
    <div class="w1200">
        <div class="crumbs">
            <a href="{pigcms{:U('index')}">全部</a>
			<a href="{pigcms{:U('gift_list',array('cat_id'=>$top_gift_category['cat_id']))}">
            {pigcms{$top_gift_category['cat_name']}</a>
			
			<a href="{pigcms{:U('gift_list',array('cat_id'=>$now_gift_category['cat_id']))}">
            {pigcms{$now_gift_category['cat_name']}</a>
        </div>
    </div>
</section>
<section class="mainSection">

    <div class="w1200">
        <div class="detaile clearfix">
            <div class="detailLeft">
                <div class="jf-pic-con">
                    <div class="jf-pic-con">
                        <div  class="jf-pic" ><span class="ks-imagezoom-wrap jqzoom"><img src="" width="400" height="400" class="fs" id="bigImg" alt="" jqimg=""></span></div>
                    </div>
                    <ul  class="jf-thumb clearfix">
						<volist name='gift_detail["pc_pic_list"]' id='gift'>
							<li <if condition='$i eq 1'>class="on"</if>> <img src="{pigcms{$gift.m_url}" data-big-src="{pigcms{$gift.url}"> </li>
						</volist>
                    </ul>
                </div>
            </div>
            <div class="detailRight">
                <div class="wrap">
                    <h1>{pigcms{$gift_detail.gift_name}</h1>
                    <p class="note">重要提示：所有兑换礼品颜色都随机发货</p>
                    <div class="row quantity">
                        <span class="fr changed">已兑换：<if condition='!empty($gift_detail["exchanged_num"])'>{pigcms{$gift_detail['exchanged_num']}<else />{pigcms{$gift_detail['sale_count']}</if>&nbsp;&nbsp;件</span>
						<if condition = 'in_array($gift_detail["exchange_type"],array(0,2))'>
							<p><span class="dib title"> 积&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;分:</span>
							<em class="bonus">{pigcms{$gift_detail['payment_pure_integral']}积分</em></p>
						</if>
						
						<if condition = 'in_array($gift_detail["exchange_type"],array(1,2))'>
							<p><span class="dib title"> 现&nbsp;&nbsp;&nbsp;金+:</span>
							<em class="bonus">{pigcms{$gift_detail['payment_integral']}积分+
								{pigcms{$gift_detail['payment_money']}
							元</em></p>
						</if>
                    </div>
                    <div class="wrap">
                        <div class="row JSsizes">
                            <span class="dib title">选择规格：</span>
                            <div class="dib option memo">
							<volist name='gift_detail["specification"]' id='specification'>
								<a href="javascript:;" <if condition='$i eq 1'>class="on"</if>>
									{pigcms{$specification}
								</a>
							</volist>
							<!--a href="javascript:;" class="disable">
									123
								</a-->
                            </div>
                        </div>
                        <div class="row" id="chosenTip">
                            <span class="dib title"></span>
                            <div class="dib option exchange_type">
                                <p class="chosenTip">已选择：“<em></em>”</p>
                            </div>
                        </div>
                        <div class="row JSpay">
                            <span class="dib title">兑换方式：</span>
                            <div class="dib option exchange_type">
							
								<if condition='in_array($gift_detail["exchange_type"],array(0,2))'>
									<a href="javascript:;" class="on" data-value="0">
										全积分
									</a>
								</if>
								
								<if condition='in_array($gift_detail["exchange_type"],array(1,2))'>
									<a href="javascript:;" data-value="1" class="on">
										积分+现金
									</a>
								</if>
                            </div>
                        </div>
                        <div class="row">
                            <span class="dib title">选择数量：</span>
                            <div class="dib option">
                                <div class="jf-bidNum clearfix">
                                    <span class="reduce">-</span><input type="text" id="now_sku" value="1"><span class="plus">+</span> <em class="stock dib">库存：<font class="new_total_sku">{pigcms{$gift_detail['sku'] - $gift_detail['sale_count']}</font>件</em>
                                </div>
								<if condition='$gift_detail["exchange_limit_num"] neq 0'>
									<p>每人限兑&nbsp;<em class='total_sku'>{pigcms{$gift_detail["exchange_limit_num"]}</em>&nbsp;件</p></td>
								</if>
                            </div>
							
                        </div>
						
                        <div class="row">
                            <span class="dib title"></span>
                            <div class="dib jfBtnOption">
							<if condition='$gift_detail["sku"] gt 0'>
                                <a href="javascript::void(0)" class="jfBtn JSjfBtn">立即兑换</a>
							<else />
								 <a href="javascript::void(0)" class="jfBtn JSjfBtn" style="background:gray">暂无库存</a>
							</if>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="detailMore mt20 clearfix">
            <div class="fl leftBar">
               <div class="recent">
                   <h2>最近浏览</h2>
                   <ul>
					   <volist name='gift_record_list["list"]' id='gift'>
						   <li>
							   <a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}">
								   <div class="i-pic">
									   <img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}"/>
								   </div>
								   <h3>
									   {pigcms{$gift.gift_name}
								   </h3>
								   <p class="bonus"><span>889 <em>积分</em></span><span><em>+</em>889 <em>元</em></span></p>
								   
							   </a>
						   </li>
						</volist>
                   </ul>
               </div>
<!--        暂时不要 先注释        <div class="hotRank mt20">
                    <h2><strong>HOT</strong>兑换排行</h2>
                    <ul class="hotList">
                        <volist name='hot_gift_list["list"]' id='gift'>
							<li>
								<i class="rankNum">{pigcms{$i}</i>
								<a href="{pigcms{:U('gift_detail',array('gift_id'=>$gift['gift_id']))}">
									<div class="i-pic fr">
										<img src="{pigcms{$config.site_url}/upload/system/gift/{pigcms{$gift['pc_pic_list'][0]}"/>
									</div>
									<div class="proText">
										<h3>{pigcms{$gift.gift_name}</h3>
										<if condition='in_array($gift["exchange_type"],array(0,2))'><p>积分：{pigcms{$gift.payment_pure_integral}<em>积分</em></p></if>
										<if condition='in_array($gift["exchange_type"],array(1,2))'>
										 <p>现金+：{pigcms{$gift.payment_integral}<em>积分</em><em>+</em>{pigcms{$gift.payment_money}<em>元</em></p>
										</if>
									</div>
								</a>
							</li>
						</volist>
                    </ul>
                </div> -->
            </div>
            <div class="rightInfo ofh">
                <section class="jf-addition pmTab">
                    <div class="hd">
                        <ul class="proTab clearfix">
							<li><a href="javascript:void(0)">发货清单</a> </li>
                            <li class="on"><a href="javascript:void(0)">礼品介绍</a> </li>
                        </ul>
                    </div>
                    <div class="bd">
						<div class="row">
                            {pigcms{$gift_detail['invoice_content']}
                        </div>
                        <div class="row">
                            <!--div class="detail-common-text tc mt20"></div-->
							{pigcms{$gift_detail['gift_content']|html_entity_decode}
							
                        </div>
                        
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<include file="Public:gift_footer"/>
<script type="text/javascript" language="javascript">
$('.exchange_type a').each(function(i){
	$(this).click(function(){
		$('.quantity p').each(function(){
			$(this).hide();
		});
		$('.quantity p:eq('+i+')').show();
	});
});

$('.JSjfBtn').click(function(){
	var memo = $('.memo > .on').html();
	var num = $('#now_sku').val();
	var gift_id = "{pigcms{$gift_detail['gift_id']}";
	var exchange_type = $('.exchange_type> .on').data('value');
	
	var gift_buy_url = "{pigcms{:U('gift_buy')}";
	gift_buy_url +='&memo=' + memo + '&num='+num + '&gift_id='+gift_id+'&exchange_type='+exchange_type;
	location.href = gift_buy_url;
	
});
</script>