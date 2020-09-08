<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/store_modify')}" frame="true" refresh="true">
		<input type="hidden" name="mer_id" value="{pigcms{$merchant.mer_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">店铺名称</th>
				<td><input type="text" class="input fl" name="name" size="25" placeholder="店铺名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="80">联系电话</th>
				<td><input type="text" class="input fl" name="phone" size="25" placeholder="店铺的电话" validate="required:true" tips="多个电话号码以空格分开"/></td>
			</tr>
			<tr>
				<th width="80">店铺经纬度</th>
				<td id="choose_map"></td>
			</tr>
			<tr>
				<th width="80">店铺所在地</th>
				<!--td id="choose_cityarea"></td-->
                <td id="city_area">

                </td>
                <input type="hidden" id="city_id" name="city_id">
			</tr>
			<tr>
				<th width="80">店铺地址</th>
				<td><input type="text" class="input fl" name="adress" id="adress" size="25" placeholder="店铺的地址" validate="required:true"/></td>
			</tr>
			<tr>
				<th width="80">店铺排序</th>
				<td><input type="text" class="input fl" name="sort" size="5" value="0" validate="required:true,number:true,maxlength:6" tips="默认添加顺序排序！手动调值，数值越大，排序越前"/></td>
			</tr>
			<tr>
				<th width="80">{pigcms{$config.meal_alias_name}功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="have_meal" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="have_meal" value="0" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">{pigcms{$config.group_alias_name}功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="have_group" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="have_group" value="0" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="80">{pigcms{$config.shop_alias_name}功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="have_shop" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="have_shop" value="0" /></label></span>
				</td>
			</tr>
			<if condition="$config['store_open_waimai']">
				<tr>
					<th width="80">{pigcms{$config.waimai_alias_name}功能</th>
					<td>
						<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="have_waimai" value="1" checked="checked" /></label></span>
						<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="have_waimai" value="0" /></label></span>
					</td>
				</tr>
			</if>
			<tr>
				<th width="80">店铺状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
            <tr>
                <th width="80">支付加密</th>
                <td>
                    <span class="cb-enable"><label class="cb-enable selected"><span>加密</span><input type="radio" name="pay_secret" value="1" checked="checked" /></label></span>
                    <span class="cb-disable"><label class="cb-disable"><span>不加密</span><input type="radio" name="pay_secret" value="0" /></label></span>
                </td>
            </tr>
            <tr>
                <th width="80">税费</th>
                <td><input type="text" class="input fl" name="tax_num" size="5" value="5" validate="required:true,number:true,maxlength:6" />%</td>
            </tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<include file="Public:footer"/>