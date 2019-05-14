<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/amend')}" frame="true" refresh="true">
		<input type="hidden" name="mer_id" value="{pigcms{$merchant.mer_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="160">{pigcms{:L('_BACK_MER_ACC_')}</th>
				<td><div class="show">{pigcms{$merchant.account}</div></td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_MER_PASS_')}</th>
				<td><input type="password" id="check_pwd" check_width="180" check_event="keyup" class="input fl" name="pwd" value="" size="25" validate="minlength:6" /></td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_MER_NAME_')}</th>
				<td><input type="text" class="input fl" name="name" value="{pigcms{$merchant.name}" size="25" validate="maxlength:20,required:true"/></td>
			</tr>
			<if condition="C('config.open_sub_mchid') eq 1">
			<tr>
				<th width="160">是否开启特约子商功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['open_sub_mchid'] eq 1">selected</if>"><span>是</span>
					<input type="radio" name="open_sub_mchid" value="1" <if condition="$merchant['open_sub_mchid'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable " ><label class="cb-disable  <if condition="$merchant['open_sub_mchid'] eq 0">selected</if>"><span>否</span>
					<input type="radio" name="open_sub_mchid" value="0" <if condition="$merchant['open_sub_mchid'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启后，该商户不能设置使用自有的微信支付，该商家的支付将按服务商的子商户号支付<br>(开启后请正确配置子商户号、子商户退款、是否允许使用平台余额、是否允许使用平台优惠)"></em>
				</td>
				</tr>
			
			<tr class="sub_mch">
				<th width="160">子商户号</th>
				<td><input type="text" class="input fl" name="sub_mch_id" size="25" value="{pigcms{$merchant['sub_mch_id']}" placeholder="子商户号" validate="" />
					<em class="notice_tips" tips="开启子商户支付后必须要填写正确的子商户号，子商户号可以在微信子商户平台查看"></em>
				</td>
			</tr>
			
			<tr class="sub_mch">
				<th width="160">是否开启子商户支付退款</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['sub_mch_refund'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_refund" value="1" <if condition="$merchant['sub_mch_refund'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['sub_mch_refund'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_refund" value="0" <if condition="$merchant['sub_mch_refund'] eq 0">checked="checked"</if>/></label></span>
					<font color="red">请确认子商户是否授权退款权限,如果没有请不要开启</font>
					<em class="notice_tips" tips="开启子商户支付退款，需要子商户申请退款权限给微信服务商，如果未得到授权，退款将失败;<br>关闭后用户将不能退款，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			
			
			<tr class="sub_mch">
				<th width="160">是否允许适用平台余额</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$merchant['sub_mch_sys_pay'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_sys_pay" value="1" <if condition="$merchant['sub_mch_sys_pay'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$merchant['sub_mch_sys_pay'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_sys_pay" value="0" <if condition="$merchant['sub_mch_sys_pay'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启后用户通过子商户支付的同时也可以使用平台余额，且平台余额部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后，用户不能使用平台余额，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否允许适用平台优惠(积分优惠券)</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$merchant['sub_mch_discount'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_discount" value="1" <if condition="$merchant['sub_mch_discount'] eq 1">checked="checked"</if>  /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$merchant['sub_mch_discount'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_discount" value="0" <if condition="$merchant['sub_mch_discount'] eq 0">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后,用户通过子商户支付的同时也可以使用平台优惠，且优惠部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后用户不能使用平台优惠券，平台积分抵扣，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			
			<if condition="$config.open_mer_owe_money">
			<tr class="sub_mch">
				<th width="160">是否允许商家余额欠费</th>
				<td>
				<input type="text" class="input fl" name="mch_owe_money" size="25" placeholder="子商户欠钱额度" value="{pigcms{$merchant.mch_owe_money}" validate="" />
				<em class="notice_tips" tips="0 表示不允许欠款，一旦商家余额不足够平台配送，用户则不能下单，1000 表示平台允许商家欠平台1000元，超过1000后用户不能使用平台配送下单"></em>
				</td>
				
			</tr>
			
			</if>
			
			
			</if>
			<if condition="C('config.open_extra_price') eq 1">
			<tr>
				<th width="160">商家欠平台{pigcms{:C('config.extra_price_alias_name')}数</th>
				<td><input type="text" class="input fl" name="extra_price_pay_for_system" value="{pigcms{$merchant.extra_price_pay_for_system|floatval}" size="25"  validate="required:true,min:0" tips=""/>,即{pigcms{:sprintf("%.2f", $merchant['extra_price_pay_for_system']*$merchant['extra_price_percent']/100)}元</td>
			</tr>
			<tr>
				<th width="160">{pigcms{:C('config.extra_price_alias_name')}结算比例</th>
				<td><input type="text" class="input fl" name="extra_price_percent" value="{pigcms{$merchant.extra_price_percent|floatval}" size="25"  validate="required:true,min:0,max:100" tips=""/></td>
			</tr>
			<tr>
				<th width="160">消费1元赠送{pigcms{:C('config.score_name')}数</th>
				<td><input type="text" class="input fl" name="score_get" value="{pigcms{$merchant.score_get|floatval}" size="25"  validate="required:true,min:0" tips=""/>0 相当于不得{pigcms{$config.score_name}</td>
			</tr>
			</if>
			<tr>
				<th width="160">{pigcms{:L('_BACK_MER_PHONE_')}</th>
				<td><input type="text" class="input fl" name="phone" value="{pigcms{$merchant.phone}" size="25" validate="required:true" /></td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_EMAIL_')}</th>
				<td><input type="text" class="input fl" name="email" value="{pigcms{$merchant.email}" size="25" validate="email:true" /></td>
			</tr>
			<tr style="display:none;">
				<th width="160">对账周期</th>
				<td><input type="text" class="input fl" name="bill_period" value="{pigcms{$merchant.bill_period}" size="25" placeholder="可不填写" validate="number:true,min:0" tips="对账周期，填0则按系统对账周期计算,最小为一天"/></td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_STORE_AREA_')}</th>
				<!--td id="choose_cityarea" province_id="{pigcms{$merchant.province_id}" city_id="{pigcms{$merchant.city_id}" area_id="{pigcms{$merchant.area_id}" circle_id="-1"></td-->
                <td>
                    <select name="city_id">
                        <volist name="city" id="vo">
                            <option value="{pigcms{$vo.area_id}" <if condition="$merchant['city_id'] eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                        </volist>
                    </select>
                </td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_EXPIRE_DATE_')}</th>
				<td><input type="text" class="input fl" name="merchant_end_time" value="{pigcms{$merchant.merchant_end_time}" size="25" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm',lang:'en'})" /></td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_MER_STATUS_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['status'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ACTIVE_')}</span><input type="radio" name="status" value="1" <if condition="$merchant['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['status'] neq 1">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0" <if condition="$merchant['status'] neq 1">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<!--tr style="display:none">
				<th width="160">{pigcms{:L('_OFFLINE_PAY_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_close_offline'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="is_close_offline" value="0" <if condition="$merchant['is_close_offline'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_close_offline'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" name="is_close_offline" value="1" <if condition="$merchant['is_close_offline'] eq 1">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_CONTRACT_MER_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['issign'] eq 1">selected</if>"><span>Yes</span><input type="radio" name="issign" value="1" <if condition="$merchant['issign'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$merchant['issign'] neq 1">selected</if>"><span>No</span><input type="radio" name="issign" value="0"  <if condition="$merchant['issign'] neq 1">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后商家中心会显示此商家已签约标签即商家是优质客户，所有新增的产品都无需审核"></em>
				</td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_CERTIFIED_MER_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['isverify'] eq 1">selected</if>"><span>Yes</span><input type="radio" name="isverify" value="1" <if condition="$merchant['isverify'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['isverify'] neq 1">selected</if>"><span>No</span><input type="radio" name="isverify" value="0"  <if condition="$merchant['isverify'] neq 1">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后商家中心会显示此商家已认证标签"></em>
				</td>
			</tr>
			<tr>
				<th width="160">{pigcms{:L('_BACK_USE_PUB_NUM_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_open_oauth'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ALLOW_')}</span><input type="radio" name="is_open_oauth" value="1" <if condition="$merchant['is_open_oauth'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_open_oauth'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="is_open_oauth" value="0" <if condition="$merchant['is_open_oauth'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<if condition="$config['is_open_weidian']">
				<tr>
					<th width="160">开微店</th>
					<td>
						<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_open_weidian'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ALLOW_')}</span><input type="radio" name="is_open_weidian" value="1" <if condition="$merchant['is_open_weidian'] eq 1">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_open_weidian'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="is_open_weidian" value="0" <if condition="$merchant['is_open_weidian'] eq 0">checked="checked"</if>/></label></span>
					</td>
				</tr>
			</if>
			<if condition="isset($config['group_page_row']) && isset($config['now_scenic']) && $config['now_scenic'] neq 2">
				<tr>
					<th width="160">开通景区</th>
					<td>
						<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_open_scenic'] eq 1">selected</if>"><span>允许</span><input type="radio" name="is_open_scenic" value="1" <if condition="$merchant['is_open_scenic'] eq 1">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_open_scenic'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="is_open_scenic" value="0" <if condition="$merchant['is_open_scenic'] eq 0">checked="checked"</if>/></label></span>
					</td>
				</tr>
			</if-->
			<!--tr>
				<th width="160">抽成设置</th>
				<td>
				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_percent',array('mer_id'=>$merchant['mer_id'],'type'=>'merchant'))}','编辑商家抽成比例',800,560,true,false,false,null,'edit_percent',true);" style="color:blue">设置商家抽成比例</a>&nbsp;&nbsp;&nbsp;&nbsp;
			</tr>
			<tr>
				<th width="160">分佣设置</th>
				<td>

				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_rate',array('mer_id'=>$merchant['mer_id']))}','编辑商家推广分佣比例',800,560,true,false,false,null,'edit_rate',true);" style="color:blue">设置商家推广分佣比例</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_user_rate',array('mer_id'=>$merchant['mer_id']))}','用户分佣设置',800,560,true,false,false,null,'edit_user_rate',true);" style="color:blue">设置用户分佣比例</a>
				</td>

			</tr>
			<tr>
				<th width="160">线下支付设置</th>
				<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_offline',array('mer_id'=>$merchant['mer_id']))}','设置线下支付',800,560,true,false,false,null,'edit_offline',true);" style="color:blue">设置线下支付</a></td>
			</tr>
			<tr>
				<th width="160">{pigcms{$config.score_name}使用设置</th>
				<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_score',array('mer_id'=>$merchant['mer_id']))}','设置不同业务{pigcms{$config.score_name}最大使用数量',800,560,true,false,false,null,'edit_score',true);" style="color:blue">设置不同业务{pigcms{$config.score_name}使用数量</a></td>
			</tr>
			<tr>
				<th width="160">商家权限设置</th>
				<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/menu',array('mer_id'=>$merchant['mer_id']))}','设置商家使用权限',700,500,true,false,false,null,'menu',true);" style="color:blue">设置商家使用权限</a><td>
			</tr>

			<tr>
				<th width="160">平台{pigcms{$config.score_name}</th>
				<td><input type="text" class="input fl" name="plat_score" value="{pigcms{$merchant.plat_score}" size="10" placeholder="0" tips="平台{pigcms{$config.score_name}"/></td>
			</tr>


			<tr><th colspan="2" style="color: red;text-align:center"> 超级广告设置 </th></tr>
			<tr>
				<th width="160">首页宣传状态</th>
				<td>
					<select name="share_open" class="valid">
					<option value="0" <if condition="$merchant['share_open'] eq 0">selected="selected"</if>>关闭</option>
					<option value="1" <if condition="$merchant['share_open'] eq 1">selected="selected"</if>>开启显示商家信息</option>
					<option value="2" <if condition="$merchant['share_open'] eq 2">selected="selected"</if>>开启跳转到商家微网站</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="160">广告语</th>
				<td><input type="text" class="input fl" name="a_title" value="{pigcms{$home_share.title}" size="25" placeholder="可不填写" tips="粉丝看到自己的第一次进入本站来自哪个商家的店铺"/></td>
			</tr>
			<tr>
				<th width="160">进入提示语</th>
				<td><input type="text" class="input fl" name="a_name" value="{pigcms{$home_share.a_name}" size="5" placeholder="可不填写" tips="提示粉丝进入的提示语言"/></td>
			</tr>
			<tr>
				<th width="160">进入网址</th>
				<td><input type="text" class="input fl" name="a_href" value="{pigcms{$home_share.a_href}" size="60" placeholder="可不填写" tips="跳转到指定地方的网址"  validate="url:true"/></td>
			</tr-->
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	
	<script>
		$(function(){
			if($('input[name="open_sub_mchid"]:checked').val()==1){
				$('.sub_mch').show();
			}else{
				$('.sub_mch').hide();
			}
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