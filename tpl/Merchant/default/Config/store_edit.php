<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/store')}">{pigcms{:L('STORE_MANAGEMENT_BKADMIN')}</a>
			</li>
			<li class="active">{pigcms{:L('EDIT_STORE_BKADMIN')}</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
                            <li class="active">
                                <a data-toggle="tab" href="#txtstore">{pigcms{:L('STORE_DESCRIPTION_BKADMIN')}</a>
                            </li>
							<li >
								<a data-toggle="tab" href="#basicinfo">{pigcms{:L('BASIC_SETTING_BKADMIN')}</a>
							</li>

<!--							{pigcms{/***[if >=3]***/}-->
<!--							<li>-->
<!--								<a data-toggle="tab" href="#discount">{pigcms{$config.cash_alias_name}</a>-->
<!--							</li>-->
<!--							{pigcms{/***[/if]***/}-->
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane ">
								<input type="hidden" name="store_id" value="{pigcms{$now_store.store_id}"/>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">{pigcms{:L('STORE_NAME_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" value="{pigcms{$now_store.name}" type="text"/>
								</div>
								<div class="form-group hidden_obj"><label class="col-sm-1">是否设置成主店</label><label><input type="radio" name="ismain" value="1" <if condition="$now_store['ismain'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>
									&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="ismain" value="0" <if condition="$now_store['ismain'] neq 1">checked="checked"</if>>&nbsp;&nbsp;否</label>
								 &nbsp;&nbsp;&nbsp;<span class="form_tips">如果将此店铺设置成主店，系统将自动取消其他已设置的主店</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">{pigcms{:L('STORE_PHONE_NUMBER_BKADMIN')}</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" value="{pigcms{$now_store.phone}" type="text"/>
									<span class="form_tips">{pigcms{:L('PHONE_ASSOCIATE_BKADMIN')}</span>
								</div>
							   <div class="form-group hidden_obj">
									<label class="col-sm-1"><label for="weixin">联系微信</label></label>
									<input class="col-sm-2" size="20" name="weixin" id="weixin" type="text" value="{pigcms{$now_store.weixin}"/>
								</div>
								<div class="form-group hidden_obj">
									<label class="col-sm-1"><label for="qq">联系Q Q</label></label>
									<input class="col-sm-2" size="20" name="qq" id="qq" type="text" value="{pigcms{$now_store.qq}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1">{pigcms{:L('SHORT_DESCRIPTION_BKADMIN')}</label>
									<input class="col-sm-3" maxlength="100" name="keywords" type="text" value="{pigcms{$now_store.keywords}" id="keywords"/><span class="form_tips">{pigcms{:L('REQUIRED_INFO_BKADMIN')}</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="long_lat">{pigcms{:L('ADDRESS_COORDINATES_BKADMIN')}</label></label>
									<input class="col-sm-2" size="10" name="long_lat" id="long_lat" value="{pigcms{$now_store.long},{pigcms{$now_store.lat}" type="text" readonly="readonly"/>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" id="show_map_frame" data-toggle="modal">{pigcms{:L('SET_COORDINATES_BKADMIN')}</a>
								</div>
								<div class="form-group hidden_obj">
									<label class="col-sm-1"><label for="permoney">人均消费</label></label>
									<input class="col-sm-2" size="20" name="permoney" id="permoney" type="text" value="10" data_db="{pigcms{$now_store.permoney}" onkeyup="value=value.replace(/[^1234567890]+/g,'')"/>
									<span class="form_tips"> 元（必填）</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="feature">{pigcms{:L('SEARCH_KEYWORDS_BKADMIN')}</label></label>
									<input class="col-sm-2" style="width:600px" name="feature" id="feature" type="text" value="{pigcms{$now_store.feature}" />
									<span class="form_tips">{pigcms{:L('OPTIONAL_SEARCH_BKADMIN')}</span>
								</div>
								<div class="form-group hidden_obj" id="choose_category_s">
									<label class="col-sm-1"><label>店铺所属分类</label></label>
									<fieldset id="choose_category" cat_fid="{pigcms{$now_store.cat_fid}" cat_id="{pigcms{$now_store.cat_id}"></fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>{pigcms{:L('CITY_BKADMIN')}</label></label>
									<!--fieldset id="choose_cityarea" province_id="{pigcms{$now_store.province_id}" city_id="{pigcms{$now_store.city_id}" area_id="{pigcms{$now_store.area_id}" circle_id="{pigcms{$now_store.circle_id}" market_id="{pigcms{$now_store.market_id}"></fieldset-->
                                    <fieldse id="city_area" style="padding-top: 6px;float: left">{pigcms{$now_store.city_name}</fieldse>
                                    <input type="hidden" name="area_id" id="area_id" value="{pigcms{$now_store.area_id}">
                                    <input type="hidden" name="city_id" id="city_id" value="{pigcms{$now_store.city_id}">
                                    <input type="hidden" name="province_id" id="province_id" value="{pigcms{$now_store.province_id}">
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="adress">{pigcms{:L('STORE_ADDRESS_BKADMIN')}</label></label>
									<input class="col-sm-2" size="30" name="adress" id="adress" value="{pigcms{$now_store.adress}" type="text"/>
								</div>
								<div class="form-group hidden_obj">
									<label class="col-sm-1"><label for="trafficroute">交通路线</label></label>
									<input class="col-sm-2" name="trafficroute" id="trafficroute" type="text" value="{pigcms{$now_store.trafficroute}" style="width:600px"/>
									<span class="form_tips">简单描述本店交通路线80字以内</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">{pigcms{:L('STORE_LIST_ORDER_BKADMIN')}</label></label>
									<input class="col-sm-1" size="10" name="sort" id="sort" type="text" value="{pigcms{$now_store.sort}" />
									<span class="form_tips">{pigcms{:L('DEFAULT_SORT_BKADMIN')}</span>
								</div>
								<div class="form-group hidden_obj">
									<label class="col-sm-1">WiFi名称</label>
									<input class="col-sm-1" name="wifi_account" type="text" value="{pigcms{$now_store.wifi_account}" />
								</div>
								<div class="form-group hidden_obj">
									<label class="col-sm-1">WiFi密码</label>
									<input class="col-sm-1" name="wifi_password" type="text" value="{pigcms{$now_store.wifi_password}" />
								</div>
								<if condition="$config['store_open_meal']">
									<div class="form-group">
										<label class="col-sm-1" for="have_meal">{pigcms{:L('_BACK_DINE_')}</label>
										<select name="have_meal" id="have_meal">
											<option value="0" <if condition="$now_store['have_meal'] eq 0">selected="selected"</if>>{pigcms{:L('DISABLE_BKADMIN')}</option>
											<option value="1" <if condition="$now_store['have_meal'] eq 1">selected="selected"</if>>{pigcms{:L('ENABLE_BKADMIN')}</option>
										</select>
									</div>
								</if>
								<if condition="$config['store_open_meal'] && false">
									<div class="form-group hidden_obj">
										<label class="col-sm-1" for="store_type">{pigcms{$config.meal_alias_name}类型</label>
										<select name="store_type" id="store_type">
											<option value="0" <if condition="$now_store['store_type'] eq 0">selected="selected"</if>>订餐和外卖</option>
											<option value="1" <if condition="$now_store['store_type'] eq 1">selected="selected"</if>>订餐</option>
											<option value="2" <if condition="$now_store['store_type'] eq 2">selected="selected"</if>>其他</option>
										</select>
										<span class="form_tips">【其他】是指（外卖，超市，花店...）</span>
									</div>
								</if>
								<if condition="$config['store_open_meal'] && false">
									<div class="form-group hidden_obj">
										<label class="col-sm-1" for="store_type">{pigcms{$config.meal_alias_name}类型</label>
										<select name="store_type" id="store_type">
											<option value="0" <if condition="$now_store['store_type'] eq 0">selected="selected"</if> disabled>订餐和外卖</option>
											<option value="1" <if condition="$now_store['store_type'] eq 1">selected="selected"</if>>到店消费</option>
											<option value="2" <if condition="$now_store['store_type'] eq 2">selected="selected"</if> disabled>其他</option>
											<option value="3" <if condition="$now_store['store_type'] eq 3">selected="selected"</if>>到店消费和外送</option>
										</select>
										<span class="form_tips red">【注】订餐和外卖、其他，这两种类型不能再被选择了，但是不影响已经选择过这两项的类型</span>
									</div>
								</if>
								<if condition="$config['store_open_group']">
									<div class="form-group hidden_obj">
										<label class="col-sm-1" for="have_group">{pigcms{$config.group_alias_name}</label>
										<select name="have_group" id="have_group">
											<option value="0" <if condition="$now_store['have_group'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$now_store['have_group'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								</if>
								<if condition="$config['store_open_shop']">
									<div class="form-group hidden_obj">
										<label class="col-sm-1" for="have_group">{pigcms{$config.shop_alias_name}</label>
										<select name="have_shop" id="have_shop">
											<option value="0" <if condition="$now_store['have_shop'] eq 0">selected="selected"</if>>关闭</option>
											<option value="1" <if condition="$now_store['have_shop'] eq 1">selected="selected"</if>>开启</option>
										</select>
									</div>
								</if>
								<if condition="$config['store_open_waimai'] && false">
									<div class="form-group hidden_obj">
										<label class="col-sm-1" for="have_waimai">{pigcms{$config.waimai_alias_name}</label>
										<select name="have_waimai" id="have_waimai">
											<option value="1" selected="selected">{pigcms{:L('ENABLE_BKADMIN')}</option>
											<option value="0">{pigcms{:L('DISABLE_BKADMIN')}</option>
										</select>
									</div>
								</if>
								<div class="alert alert-info">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
                                    {pigcms{:L('SUPPOSE_HOURS_BKADMIN')}
								</div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                        <tr>
                                            <th class="text-center">{pigcms{:L('WEEKLY_BKADMIN')}</th>
                                            <th class="text-center">{pigcms{:L('TIME_FRAME_1_BKADMIN')} </th>
                                            <th class="text-center">{pigcms{:L('TIME_FRAME_2_BKADMIN')}</th>
                                            <th class="text-center">{pigcms{:L('TIME_FRAME_3_BKADMIN')}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <td class="text-center">{pigcms{:L('MON_BKADMIN')}</td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_1|default='00:00:00'}" name="open_1" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_1|default='00:00:00'}" name="close_1" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_em_" style="display:none"></div>
                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_2" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_2|default='00:00:00'}" name="open_2" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_2" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_2|default='00:00:00'}" name="close_2" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_2_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_2_em_" style="display:none"></div>

                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_3" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_3|default='00:00:00'}" name="open_3" readonly/>  {pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_3" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_3|default='00:00:00'}" name="close_3" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_3_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_3_em_" style="display:none"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{pigcms{:L('TUE_BKADMIN')}</td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_4" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_4|default='00:00:00'}" name="open_4" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_4" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_4|default='00:00:00'}" name="close_4" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_4_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_4_em_" style="display:none"></div>
                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_5" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_5|default='00:00:00'}" name="open_5" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_5" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_5|default='00:00:00'}" name="close_5" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_5_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_5_em_" style="display:none"></div>

                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_6" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_6|default='00:00:00'}" name="open_6" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_6" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_6|default='00:00:00'}" name="close_6" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_6_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_6_em_" style="display:none"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{pigcms{:L('WED_BKADMIN')}</td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_7" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_7|default='00:00:00'}" name="open_7" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_7" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_7|default='00:00:00'}" name="close_7" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_7_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_7_em_" style="display:none"></div>
                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_8" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_8|default='00:00:00'}" name="open_8" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_8" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_8|default='00:00:00'}" name="close_8" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_8_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_8_em_" style="display:none"></div>

                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_9" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_9|default='00:00:00'}" name="open_9" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_9" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_9|default='00:00:00'}" name="close_9" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_9_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_9_em_" style="display:none"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{pigcms{:L('THUR_BKADMIN')}</td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_10" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_10|default='00:00:00'}" name="open_10" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_10" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_10|default='00:00:00'}" name="close_10" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_10_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_10_em_" style="display:none"></div>
                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_11" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_11|default='00:00:00'}" name="open_11" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_11" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_11|default='00:00:00'}" name="close_11" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_11_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_11_em_" style="display:none"></div>

                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_12" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_12|default='00:00:00'}" name="open_12" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_12" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_12|default='00:00:00'}" name="close_12" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_12_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_12_em_" style="display:none"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{pigcms{:L('FRI_BKADMIN')}</td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_13" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_13|default='00:00:00'}" name="open_13" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_13" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_13|default='00:00:00'}" name="close_13" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_13_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_13_em_" style="display:none"></div>
                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_14" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_14|default='00:00:00'}" name="open_14" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_14" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_14|default='00:00:00'}" name="close_14" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_14_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_14_em_" style="display:none"></div>

                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_15" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_15|default='00:00:00'}" name="open_15" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_15" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_15|default='00:00:00'}" name="close_15" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_15_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_15_em_" style="display:none"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{pigcms{:L('SAT_BKADMIN')}</td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_16" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_16|default='00:00:00'}" name="open_16" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_16" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_16|default='00:00:00'}" name="close_16" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_16_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_16_em_" style="display:none"></div>
                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_17" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_17|default='00:00:00'}" name="open_17" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_17" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_17|default='00:00:00'}" name="close_17" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_17_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_17_em_" style="display:none"></div>

                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_18" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_18|default='00:00:00'}" name="open_18" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_18" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_18|default='00:00:00'}" name="close_18" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_18_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_18_em_" style="display:none"></div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">{pigcms{:L('SUN_BKADMIN')}</td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_19" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_19|default='00:00:00'}" name="open_19" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_19" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_19|default='00:00:00'}" name="close_19" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_19_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_19_em_" style="display:none"></div>
                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_20" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_20|default='00:00:00'}" name="open_20" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_20" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_20|default='00:00:00'}" name="close_20" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_20_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_20_em_" style="display:none"></div>

                                            </td>
                                            <td class="text-center">
                                                <input id="Config_shop_start_time_21" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.open_21|default='00:00:00'}" name="open_21" readonly/>	{pigcms{:L('TO_BKADMIN')}
                                                <input id="Config_shop_stop_time_21" class="Config_shop_open_stop_time" type="text" value="{pigcms{$now_store.close_21|default='00:00:00'}" name="close_21" readonly/>
                                                <div class="errorMessage" id="Config_shop_start_time_21_em_" style="display:none"></div>
                                                <div class="errorMessage" id="Config_shop_stop_time_21_em_" style="display:none"></div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>


<!--								<div class="tabbable">-->
<!--									<ul class="nav nav-tabs" id="myTab">-->
<!--										<li class="active">-->
<!--											<a data-toggle="tab" href="#shop_time_1">-->
<!--												营业时间段1-->
<!--											</a>-->
<!--										</li>-->
<!--										<li>-->
<!--											<a data-toggle="tab" href="#shop_time_2">-->
<!--												营业时间段2-->
<!--											</a>-->
<!--										</li>-->
<!--										<li>-->
<!--											<a data-toggle="tab" href="#shop_time_3">-->
<!--												营业时间段3-->
<!--											</a>-->
<!--										</li>-->
<!--									</ul>-->
<!--									<div class="tab-content">-->
<!--										<div id="shop_time_1" class="tab-pane in active">-->
<!--											<div>-->
<!--												<input id="Config_shop_start_time" type="text" value="{pigcms{$now_store.open_1|default='00:00:00'}" name="open_1" readonly/>	至-->
<!--												<input id="Config_shop_stop_time" type="text" value="{pigcms{$now_store.close_1|default='00:00:00'}" name="close_1" readonly/>-->
<!--												<div class="errorMessage" id="Config_shop_start_time_em_" style="display:none"></div>-->
<!--												<div class="errorMessage" id="Config_shop_stop_time_em_" style="display:none"></div>-->
<!--											</div>-->
<!--										</div>-->
<!--										<div id="shop_time_2" class="tab-pane">-->
<!--											<div>-->
<!--												<input id="Config_shop_start_time_2" type="text" value="{pigcms{$now_store.open_2|default='00:00'}" name="open_2" readonly/>	至-->
<!--												<input id="Config_shop_stop_time_2" type="text" value="{pigcms{$now_store.close_2|default='00:00'}" name="close_2" readonly/>-->
<!--												<div class="errorMessage" id="Config_shop_start_time_2_em_" style="display:none"></div>-->
<!--												<div class="errorMessage" id="Config_shop_stop_time_2_em_" style="display:none"></div>-->
<!--											</div>-->
<!--										</div>-->
<!--										<div id="shop_time_3" class="tab-pane">-->
<!--											<div>-->
<!--												<input id="Config_shop_start_time_3" type="text" value="{pigcms{$now_store.open_3|default='00:00'}" name="open_3" readonly/>	至-->
<!--												<input id="Config_shop_stop_time_3" type="text" value="{pigcms{$now_store.close_3|default='00:00'}" name="close_3" readonly/>-->
<!--												<div class="errorMessage" id="Config_shop_start_time_3_em_" style="display:none"></div>-->
<!--												<div class="errorMessage" id="Config_shop_stop_time_3_em_" style="display:none"></div>-->
<!--											</div>-->
<!--										</div>-->
<!--									</div>-->
<!--								</div>-->
							</div>
							<div id="txtstore" class="tab-pane active">

								<div class="form-group">
									<label class="col-sm-1">{pigcms{:L('STORE_DESCRIPTION_BKADMIN')}</label>
									<textarea class="col-sm-5" rows="5" name="txt_info">{pigcms{$now_store.txt_info}</textarea>
                                    <span class="form_tips">This will be shown to users as a pop-up window when they open a store on the app</span>
								</div>

                                <!--               图片上传             ----->

                                <div class="form-group">
                                    <label class="col-sm-1">{pigcms{:L('_BACK_UPLOADMODE_TITLE_')}</label>
                                    <div class="col-sm-10">
                                        <label><span><label><input id="is_open_pick" name="is_open_pick" <if condition="$store_shop['is_open_pick'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>{pigcms{:L('_BACK_UPLOADMODE1_')}</span>&nbsp;</span></label>
                                        &nbsp;<label><span><label><input id="is_open_pick" name="is_open_pick" <if condition="$store_shop['is_open_pick'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>{pigcms{:L('_BACK_UPLOADMODE2_')}</span></span></label>
                                        &nbsp;<label><span><label><input id="is_open_pick" name="is_open_pick" <if condition="$store_shop['is_open_pick'] eq 2 ">checked="checked"</if> value="2" type="radio" ></label>&nbsp;<span>{pigcms{:L('_BACK_UPLOADMODE3_')}</span></span></label>

                                        <div>{pigcms{:L('RATIO_BKADMIN')}
                                        </div></div>
                                </div>
                                <!--               图片上传                 /////-->
                                <div id="upload_image_box" class="row" style="margin-bottom: 10px;display: none;">
                                    <div class="col-lg-12">
                                        <div class="ibox ">
                                            <div class="ibox-title  back-change">
                                                <label style="margin-bottom: 10px">{pigcms{:L('ITEM_PHOTO_BKADMIN')}</label>
                                            </div>
                                            <div class="ibox-content">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="image-crop">
                                                            <img id="ori_image" src="{pigcms{$static_path}images/p3.jpg">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h4>{pigcms{:L('PREVIEW_BKADMIN')}</h4>
                                                        <div class="img-preview img-preview-sm"></div>
                                                        <p>
                                                            &nbsp;<div  id="upld" class="btn btn-primary">Upload</div>
                                                        </p>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--                                //---------------------------------->
                                <div class="form-group" >
                                    <label class="col-sm-1">{pigcms{:L('STOREFRONT_IMAGE_BKADMIN')}</label>
                                    <div style="display:inline-block;" id="J_selectImage">
                                        <div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">
                                            <label title="Upload image file" for="inputImage" >
                                                <input type="file" accept="image/*" name="pic" id="inputImage" style="display:none">
                                                {pigcms{:L('UPLOAD_BKADMIN')}
                                            </label>
                                        </div>
                                    </div>
                                    <span class="form_tips"></span>
                                </div>

                                <div class="form-group hidden_obj">
                                    <label class="col-sm-1">{pigcms{:L('IMAGE_SELECT_BKADMIN')}</label>
                                    <a href="#modal-table" class="btn btn-sm btn-success" onclick="selectImg('upload_pic_ul','goods')">{pigcms{:L('IMAGE_SELECT_BKADMIN')}</a>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-1">{pigcms{:L('PREVIEW_BKADMIN')}</label>
                                    <div id="upload_pic_box">
                                        <ul id="upload_pic_ul">
                                            <volist name="now_store['pic']" id="vo">
                                                <li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ {pigcms{:L('DELETE_BKADMIN')} ]</a></li>
                                            </volist>
                                        </ul>
                                    </div>
                                </div>


							</div>

                            <!--               图片上传结束              -->

							<div id="discount" class="tab-pane">
								<div class="alert alert-block alert-success">
									<button type="button" class="close" data-dismiss="alert">
										<i class="ace-icon fa fa-times"></i>
									</button>
									<p>这里的设置只能用于优惠快速买单的地方，其他方式不起作用。如果在快速买单中不想优惠，那么就把优惠的选项全部清空就行了</p>
								</div>
								<div class="form-group">
									<label class="col-sm-1">优惠类型</label>
									<label><input type="radio" name="discount_type" value="1" <if condition="$now_store['discount_txt']['discount_type'] eq 1">checked="checked"</if>>&nbsp;&nbsp;折扣</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="discount_type" value="2" <if condition="$now_store['discount_txt']['discount_type'] eq 2">checked="checked"</if>>&nbsp;&nbsp;满减</label>&nbsp;&nbsp;&nbsp;
								</div>
								<div class="form-group percent" <if condition="$now_store['discount_txt']['discount_type'] neq 1">style="display:none"</if> >
									<label class="col-sm-1">普通折扣率</label>
									<input class="col-sm-2" style="width:60px" name="discount_percent"  type="text" value="{pigcms{$now_store['discount_txt']['discount_percent']}" /><b style="color:red">折</b>
									<span class="form_tips">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折</span>
								</div>
								<if condition="$config.open_extra_price eq 1">
								<div class="form-group percent" <if condition="$now_store['discount_txt']['discount_type'] neq 1">style="display:none"</if> >
									<label class="col-sm-1">每天限单折扣单数</label>
									<input class="col-sm-2" style="width:60px" name="discount_limit"  type="text" value="{pigcms{$now_store['discount_txt']['discount_limit']}" /><b style="color:red">单</b>
									<span class="form_tips">请填大于0的数字，不带小数点，0代表无</span>
								</div>
								<div class="form-group percent" <if condition="$now_store['discount_txt']['discount_type'] neq 1">style="display:none"</if> >
									<label class="col-sm-1">每天限单折扣率</label>
									<input class="col-sm-2" style="width:60px" name="discount_limit_percent"  type="text" value="{pigcms{$now_store['discount_txt']['discount_limit_percent']}" /><b style="color:red">折</b>
									<span class="form_tips">0~10之间的数字，支持一位小数！8代表8折，8.5代表85折，每天限单数内的折扣，超过数量将使用普通折扣率</span>
								</div>
								</if>
								<div class="form-group" <if condition="$now_store['discount_txt']['discount_type'] neq 2">style="display:none"</if> id="condition">
									<label class="col-sm-1">每满</label>
									<input class="col-sm-2" style="width:60px" name="condition_price"  type="text" value="{pigcms{$now_store['discount_txt']['condition_price']}" />
									<label class="col-sm-1" style="color:red;width: 60px;">元，减</label>
									<input class="col-sm-2" style="width:60px" name="minus_price"  type="text" value="{pigcms{$now_store['discount_txt']['minus_price']}" />
									<label class="col-sm-1" style="color:red">元</label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										{pigcms{:L('SAVE_BKADMIN')}
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="modal-table" class="modal fade" tabindex="-1" style="display:block;">
	<div class="modal-dialog" style="width:80%;">
		<div class="modal-content" style="width:100%;">
			<div class="modal-header no-padding" style="width:100%;">
				<div class="table-header">
					<button id="close_button" type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
                    {pigcms{:L('SEARCH_ZOOM_BKADMIN')}
				</div>
			</div>
			<div class="modal-body no-padding" style="width:100%;">
				<form id="map-search" style="margin:10px;">
					<input id="map-keyword" type="textbox" style="width:500px;" placeholder="{pigcms{:L('SEARCH_INPUT_PLACEHOLDER_BKADMIN')}"/>
					<input type="submit" value="Search"/>
				</form>
				<div style="width:100%;height:600px;min-height:600px;" id="cmmap"></div>
			</div>

			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-success pull-right" data-dismiss="modal">
					<i class="ace-icon fa fa-times"></i>
					{pigcms{:L('CLOSE_BKADMIN')}
				</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- PAGE CONTENT ENDS -->

<script type="text/javascript">
var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",merchant_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}",choose_market="{pigcms{:U('Area/ajax_market')}",choose_cat_fid="{pigcms{:U('Merchant_category/ajax_cat_fid')}",choose_cat_id="{pigcms{:U('Merchant_category/ajax_cat_id')}",choose_place_id="{pigcms{:U('Merchant_category/ajax_place_id')}",choose_city_name="{pigcms{:U('Area/ajax_city_name')}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/merchant_category.js"></script>
<script type="text/javascript">
$(function($){
    $('.Config_shop_open_stop_time').timepicker($.extend($.datepicker.regional['zh-cn'], {timeFormat: 'hh:mm:ss',showSecond: true}));

    $('input:radio[name=discount_type]').click(function(){
		if (1 == $(this).val()) {
			$('.percent').show();
			$('#condition').hide();
		} else if (2 == $(this).val()) {
			$('.percent').hide();
			$('#condition').show();
		}
	});

    // var geocoder = new google.maps.Geocoder();
    // geocoder.geocode({'placeId':"{pigcms{$now_store['place_id']}"},function (results, status) {
    //     $('#city_area').html(results[0].formatted_address);
    // });
});
</script>

<style>
.BMap_cpyCtrl{display:none;}
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}

.webuploader-container{
    position:relative;
}
.webuploader-container div{
    width: 78px!important;
    height: 34px!important;
}
input.ke-input-text {
    background-color: #FFFFFF;
    background-color: #FFFFFF!important;
    font-family: "sans serif",tahoma,verdana,helvetica;
    font-size: 12px;
    line-height: 24px;
    height: 24px;
    padding: 2px 4px;
    border-color: #848484 #E0E0E0 #E0E0E0 #848484;
    border-style: solid;
    border-width: 1px;
    display: -moz-inline-stack;
    display: inline-block;
    vertical-align: middle;
    zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
.webuploader-element-invisible {
    position: absolute !important;
    clip: rect(1px 1px 1px 1px);
    clip: rect(1px,1px,1px,1px);
}
.webuploader-pick-hover .btn{
    background-color: #629b58!important;
    border-color: #87b87f;
}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script>
    var loaded = false;
    var $upload_image_box;
    var $inputImage;
    var $cropped;
    var $image;
    var options = {
        aspectRatio: 1,
        preview: ".img-preview",
        done: function (data) {
            // Output the result data for cropping image.
        }
    };

    if (loaded == false) {
        loaded = true;
        $upload_image_box = $("#upload_image_box"); //裁剪工具区域
        $inputImage = $("#inputImage");             //打开裁剪上传区域的按钮
        if (window.FileReader) {                    //检测浏览器是否支持FileReader

            $inputImage.change(function () {        //将按钮事件关联事件

                var fileReader = new FileReader(),
                    files = this.files,
                    file;
                if (!files.length) {
                    return;
                }
                $image = $(".image-crop > img");
                $upload_image_box.show();
                file = files[0];

                if (/^image\/\w+$/.test(file.type)) {
                    $upload_image_box.show();
                    fileReader.readAsDataURL(file);
                    fileReader.onload = function () {
                        $inputImage.val("");
                        $image.cropper('destroy').cropper(options);
                        $image.cropper("reset", true).cropper("replace", this.result);
                    };
                } else {
                    showMessage("Please choose an image file.");
                }
            });
        } else {
            $inputImage.addClass("hide");
        }

        $("#setDrag").click(function () {
            $image.cropper("setDragMode", "crop");
        });

        $("#upld").on("click", function () {

            //console.log("download");
            if ($("#ori_image").attr("src") == null) {
                return false;
            } else {

                var base64 =  $($image).cropper('getCroppedCanvas', {
                    width: 620,
                    height: 520
                }).toDataURL("image/png");

                //$("#finalImg").prop("src", base64);// 显示图片
                uploadFile(base64)//编码后上传服务器
                //closeTailor();// 关闭裁剪框
            }
        });
    }

    $(document).ready(function(){
        $("#inputImage").on("click", function () {
            load_cooper($("input[name='is_open_pick']:checked").val());
        });
    });

    //点击“上传图片”后运行
    function load_cooper(rat) {

        var ar=0;
        if (rat==0){
            ar=1.667;
        }else if (rat==1){
            ar=2.8
        }else if (rat==2){
            ar=1
        }else{
            ar=1
        }
        options["aspectRatio"] = ar;

        if (!$cropped) {

            $cropped = $($image).cropper(options);
        }
    }

    function dataURLtoFile(dataURL, fileName, fileType) {
        var arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new File([u8arr], fileName, {type:fileType || 'image/png'});
    }
    //ajax请求上传
    function uploadFile(file) {
        var oData = new FormData();
        var nameImg=new Date().getTime()+".png";
        var ff=dataURLtoFile(file,nameImg);
        oData.append("file", ff);
        $.ajax({
            url : "{pigcms{:U('Config/store_ajax_upload_pic', array('store_id' => $now_store['store_id']))}",
            type: "post",
            dataType:"json",
            data : oData,
            processData: false,
            contentType: false,
            async : true,
            success : function(data) {
                if(data.error == 0){$upload_image_box.hide();
                    $('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+data.url+'"/><input type="hidden" name="pic[]" value="'+data.title+'"/><br/><a href="#" onclick="deleteImage(\''+data.title+'\',this);return false;">[ 删除 ]</a></li>');
                }else{
                    alert(data.info);
                }
            },
            error:function(data){
                $('.loading'+file.id).remove();
                alert('Upload failed! Please try again.');
            }
        });
    }

</script>
<script type="text/javascript">
    function deleteImage(path,obj){
        $.post("{pigcms{:U('Config/store_ajax_del_pic')}",{path:path});
        $(obj).closest('.upload_pic_li').remove();
    }

</script>

<include file="Public:footer"/>
