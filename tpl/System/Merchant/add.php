<include file="Public:header"/>
	<style>
		.sub_mch{
			display:none
		}
	</style>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/modify')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="160">商户帐号</th>
				<td><input type="text" class="input fl" name="account" size="25" placeholder="商户平台的帐号" validate="maxlength:20,required:true" tips="设定之后，以后不能再修改！"/></td>
			</tr>
			<tr>
				<th width="160">商户密码</th>
				<td><input type="password" id="check_pwd" check_width="180" class="input fl" name="pwd" size="25" placeholder="商户平台的密码" validate="required:true,minlength:6" tips="商户的密码很重要，填写难度较强的密码有效保护商户的信息，也可以保护网站的数据安全。"/></td>
			</tr>
			<tr>
				<th width="160">商户名称</th>
				<td><input type="text" class="input fl" name="name" size="25" placeholder="商户的公司名或..." validate="maxlength:20,required:true"/></td>
			</tr>
			<if condition="C('config.open_sub_mchid') eq 1">
			<tr>
				<th width="160">是否开启特约子商支付功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="open_sub_mchid" value="1"/></label></span>
					<span class="cb-disable"><label class="cb-disable  selected"><span>否</span><input type="radio" name="open_sub_mchid" value="0" checked="checked" /></label></span>
					<em class="notice_tips" tips="开启后，该商户不能设置使用自有的微信支付，该商家的支付将按服务商的子商户号支付<br>(开启后请正确配置子商户号、子商户退款、是否允许使用平台余额、是否允许使用平台优惠)"></em>
				</td>
			</tr>
			
			<tr class="sub_mch">
				<th width="160">子商户号</th>
				<td>
				<input type="text" class="input fl" name="sub_mch_id" size="25" placeholder="子商户号" validate="" />
				<em class="notice_tips" tips="开启子商户支付后必须要填写正确的子商户号，子商户号可以在微信子商户平台查看"></em>
				</td>
				
			</tr>
			
			<tr class="sub_mch">
				<th width="160">是否开启子商户支付退款</th>
				<td>
					<span class="cb-enable"><label class="cb-enable "><span>开启</span><input type="radio" name="sub_mch_refund" value="1"  /></label></span>
					<span class="cb-disable"><label class="cb-disable selected"><span>关闭</span><input type="radio" name="sub_mch_refund" value="0" checked="checked"/></label></span>
					<font color="red">请确认子商户是否授权退款权限,如果没有请不要开启</font>
					<em class="notice_tips" tips="开启子商户支付退款，需要子商户申请退款权限给微信服务商，如果未得到授权，退款将失败;<br>关闭后用户将不能退款，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否允许使用平台余额</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="sub_mch_system_pay" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="sub_mch_system_pay" value="0"/></label></span>
					<em class="notice_tips" tips="开启后用户通过子商户支付的同时也可以使用平台余额，且平台余额部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后，用户不能使用平台余额，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否允许使用平台优惠(积分优惠券)</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="sub_mch_discount" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="sub_mch_discount" value="0"/></label></span>
					<em class="notice_tips" tips="开启后,用户通过子商户支付的同时也可以使用平台优惠，且优惠部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后用户不能使用平台优惠券，平台积分抵扣，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			
			
			<if condition="$config.open_mer_owe_money">
			<tr class="sub_mch">
				<th width="160">是否允许商家余额欠费</th>
				<td>
				<input type="text" class="input fl" name="mch_owe_money" size="25" placeholder="子商户欠钱额度" validate="" />
				<em class="notice_tips" tips="0 表示不允许欠款，一旦商家余额不足够平台配送，用户则不能下单，1000 表示平台允许商家欠平台1000元，超过1000后用户不能使用平台配送下单"></em>
				</td>
				
			</tr>
			
			</if>
			<if condition="C('config.open_extra_price') eq 1">
			<tr>
				<th width="160">{pigcms{:C('config.extra_price_alias_name')}结算比例</th>
				<td><input type="text" class="input fl" name="extra_price_percent" value="" size="25"  validate="required:true,min:0,max:100" tips=""/></td>
			</tr>
			<tr>
				<th width="160">消费1元赠送{pigcms{:C('config.score_name')}数</th>
				<td><input type="text" class="input fl" name="score_get" value="" size="25"  validate="required:true,min:0" tips=""/>0 相当于不得{pigcms{$config.score_name}</td>
			</tr>
			</if>
			<tr>
				<th width="160">联系电话</th>
				<td><input type="text" class="input fl" name="phone" size="25" placeholder="联系人的电话" validate="required:true" tips="多个电话号码以空格分开"/></td>
			</tr>
			<tr>
				<th width="160">联系邮箱</th>
				<td><input type="text" class="input fl" name="email" size="25" placeholder="可不填写" validate="email:true" tips="只供管理员后台记录，前台不显示"/></td>
			</tr>
			<tr style="display:none;">
				<th width="160">对账周期</th>
				<td><input type="text" class="input fl" name="bill_period" size="25" placeholder="可不填写" validate="number:true,min:1" tips="对账周期，不填则按系统对账周期计算,最小为一天"/></td>
			</tr>
			
			<tr>
				<th width="160">所在区域</th>
				<!--td id="choose_cityarea"></td-->
                <td>
                    <select name="city_id">
                        <volist name="city" id="vo">
                            <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                        </volist>
                    </select>
                </td>
			</tr>
			<tr>
				<th width="160">到期时间</th>
				<td><input type="text" class="input fl" name="merchant_end_time" size="25" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})" tips="商户到期时间，到期之后不允许进入商户平台并关闭该商户！清空为永不过期"/></td>
			</tr>
			<tr>
				<th width="160">商户状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="160">签约商家</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="issign" value="1"/></label></span>
					<span class="cb-disable"><label class="cb-disable  selected"><span>否</span><input type="radio" name="issign" value="0" checked="checked" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="160">认证商家</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="isverify" value="1" /></label></span>
					<span class="cb-disable"><label class="cb-disable  selected"><span>否</span><input type="radio" name="isverify" value="0"  checked="checked"/></label></span>
				</td>
			</tr>
			<if condition="$config['wx_token']">
			<tr>
				<th width="160">使用公众号</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>允许</span><input type="radio" name="is_open_oauth" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>禁止</span><input type="radio" name="is_open_oauth" value="0"/></label></span>
					<em class="notice_tips" tips="如果系统设置中允许所有商家都使用公众号，请禁止无效。"></em>
				</td>
			</tr>
			</if>
			<if condition="$config['is_open_weidian']">
				<tr>
					<th width="160">开微店</th>
					<td>
						<span class="cb-enable"><label class="cb-enable selected"><span>允许</span><input type="radio" name="is_open_oauth" value="1" checked="checked" /></label></span>
						<span class="cb-disable"><label class="cb-disable"><span>禁止</span><input type="radio" name="is_open_oauth" value="0"/></label></span>
						<em class="notice_tips" tips="如果系统设置中允许所有商家都能开微店，请禁止无效。"></em>
					</td>
				</tr>
			</if>
		
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script>
		$(function(){
			$('input[name="open_sub_mchid"]').click(function(){
				var sub = $(this);
				if(sub.val()==1){
					$('.sub_mch').show();
				}else{
					$('.sub_mch').hide();
				}
			});
		});
	</script>
	
<include file="Public:footer"/>