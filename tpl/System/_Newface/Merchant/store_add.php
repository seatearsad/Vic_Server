<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/store_modify')}" frame="true" refresh="true">
		<input type="hidden" name="mer_id" value="{pigcms{$merchant.mer_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">{pigcms{:L('E_STORE_NAME')}</th>
				<td><input type="text" class="input fl" name="name" size="25" placeholder="{pigcms{:L('E_STORE_NAME')}" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_CONTACT_NUMBER1')}</th>
				<td><input type="text" class="input fl" name="phone" size="25" placeholder="{pigcms{:L('E_CONTACT_NUMBER1')}" validate="required:true" tips="{pigcmss{:L('E_MERCHANT_NUMBER1DESC')}"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_STORE_COORDINATE')}</th>
				<td id="choose_map"></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_STORE_LOCATION')}</th>
				<!--td id="choose_cityarea"></td-->
                <td id="city_area">

                </td>
                <input type="hidden" id="city_id" name="city_id">
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_STORE_ADDRESS')}</th>
				<td><input type="text" class="input fl" name="adress" id="adress" size="25" placeholder="{pigcms{:L('E_STORE_ADDRESS')}" validate="required:true"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_STORE_ORDER')}</th>
				<td><input type="text" class="input fl" name="sort" size="5" value="0" validate="required:true,number:true,maxlength:6" tips="默认添加顺序排序！手动调值，数值越大，排序越前"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{:L('E_DINE_IN')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="have_meal" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="have_meal" value="0" /></label></span>
				</td>
			</tr>
			<!--tr>
				<th width="80">{pigcms{$config.group_alias_name}功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="have_group" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="have_group" value="0" /></label></span>
				</td>
			</tr-->
			<tr>
				<th width="80">{pigcms{:L('E_DELIVERY_FUNCTION')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="have_shop" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="have_shop" value="0" /></label></span>
				</td>
			</tr>
			<if condition="$config['store_open_waimai']">
				<tr>
					<th width="80">{pigcms{$config.waimai_alias_name}功能</th>
					<td>
						<span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="have_waimai" value="1" checked="checked" /></label></span>
						<span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="have_waimai" value="0" /></label></span>
					</td>
				</tr>
			</if>
			<tr>
				<th width="80">{pigcms{:L('E_STORE_STATUS')}</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
            <tr>
                <th width="80">{pigcms{:L('BASE_ENCRYPTION')}</th>
                <td>
                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_ENCRYPTION1')}</span><input type="radio" name="pay_secret" value="1" checked="checked" /></label></span>
                    <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_ENCRYPTION2')}</span><input type="radio" name="pay_secret" value="0" /></label></span>
                </td>
            </tr>
            <tr>
                <th width="80">{pigcms{:L('E_TAX_PERCENTAGE')}</th>
                <td><input type="text" class="input fl" name="tax_num" size="5" value="5" validate="required:true,number:true,maxlength:6" />%</td>
            </tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>