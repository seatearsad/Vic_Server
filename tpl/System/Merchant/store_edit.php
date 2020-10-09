<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/store_amend')}" frame="true" refresh="true">
		<input type="hidden" name="store_id" value="{pigcms{$store.store_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('_BACK_STORE_NAME_')}</th>
				<td><input type="text" class="input fl" name="name" value="{pigcms{$store.name}" size="25" placeholder="店铺名称" validate="required:true"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_STORE_PHONE_')}</th>
				<td><input type="text" class="input fl" name="phone" size="25" value="{pigcms{$store.phone}" placeholder="店铺的电话" validate="required:true" tips="多个电话号码以空格分开"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_STORE_LAT_LONG_')}</th>
				<td id="choose_map" default_long_lat="{pigcms{$store.long},{pigcms{$store.lat}"></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_STORE_AREA_')}</th>
				<!--td id="choose_cityarea" province_id="{pigcms{$store.province_id}" city_id="{pigcms{$store.city_id}" area_id="{pigcms{$store.area_id}" circle_id="{pigcms{$store.circle_id}" market_id="{pigcms{$store.market_id}"></td-->
                <td id="city_area">
                    <select name="city_id" id="city_select">
                        <option value="0" <if condition="$store['city_id'] eq '' or $store['city_id'] eq 0">selected="selected"</if>>All</option>
                        <volist name="city" id="vo">
                            <option value="{pigcms{$vo.area_id}" <if condition="$store['city_id'] eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                        </volist>
                    </select>
                </td>
                <!--input type="hidden" id="city_id" name="city_id" value="{pigcms{$store.city_id}"-->
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_STORE_ADDRESS_')}</th>
				<td><input type="text" class="input fl" name="adress" id="adress" value="{pigcms{$store.adress}" size="25" placeholder="店铺的地址" validate="required:true"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_STORE_SORT_')}</th>
				<td><input type="text" class="input fl" name="sort" size="5" value="{pigcms{$store.sort}" validate="required:true,number:true,maxlength:6" tips="默认添加顺序排序！手动调值，数值越大，排序越前"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_DINE_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$store['have_meal'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="have_meal" value="1" <if condition="$store['have_meal'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$store['have_meal'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" name="have_meal" value="0" <if condition="$store['have_meal'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_LUNCH_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$store['have_group'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="have_group" value="1" <if condition="$store['have_group'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$store['have_group'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" name="have_group" value="0" <if condition="$store['have_group'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('_BACK_DELIVERY_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$store['have_shop'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="have_shop" value="1" <if condition="$store['have_shop'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$store['have_shop'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" name="have_shop" value="0" <if condition="$store['have_shop'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<if condition="$config['store_open_waimai']">
				<tr>
					<th width="80">{pigcms{$config.waimai_alias_name}功能</th>
					<td>
						<span class="cb-enable"><label class="cb-enable <if condition="$store['have_waimai'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="have_waimai" value="1" <if condition="$store['have_waimai'] eq 1">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$store['have_waimai'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" name="have_waimai" value="0" <if condition="$store['have_waimai'] eq 0">checked="checked"</if>/></label></span>
					</td>
				</tr>
			</if>
			<tr>
				<th width="80">{pigcms{:L('_STORE_STATUS_')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$store['status'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ACTIVE_')}</span><input type="radio" name="status" value="1" <if condition="$store['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$store['status'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0" <if condition="$store['status'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
            <tr>
                <th width="80">支付加密</th>
                <td>
                    <span class="cb-enable"><label class="cb-enable <if condition="$store['pay_secret'] eq 1">selected</if>"><span>加密</span><input type="radio" name="pay_secret" value="1" <if condition="$store['pay_secret'] eq 1">checked="checked"</if> /></label></span>
                    <span class="cb-disable"><label class="cb-disable <if condition="$store['pay_secret'] eq 0">selected</if>"><span>不加密</span><input type="radio" name="pay_secret" value="0" <if condition="$store['pay_secret'] eq 0">checked="checked"</if>/></label></span>
                </td>
            </tr>
            <tr>
                <th width="80">{pigcms{:L('_BACK_TAX_')}</th>
                <td><input type="text" class="input fl" name="tax_num" size="5" value="{pigcms{$store['tax_num']}" validate="required:true,number:true,maxlength:6" />%</td>
            </tr>
            <tr>
                <th width="80">{pigcms{:L('_BACK_PROPORTION_')}</th>
                <td><input type="text" class="input fl" name="proportion" size="5" value="{pigcms{$store['proportion']}" validate="required:true,number:true,maxlength:6" />%</td>
            </tr>
            <tr>
                <th width="80">商品默认税率</th>
                <td><input type="text" class="input fl" name="default_tax" size="5" value="{pigcms{$store['default_tax']}" validate="required:true,number:true,maxlength:6" />%</td>
            </tr>
            <tr>
                <th width="80">服务费比例</th>
                <td><input type="text" class="input fl" name="service_fee" size="5" value="{pigcms{$store['service_fee']}" validate="required:true,number:true,maxlength:6" />%</td>
            </tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>