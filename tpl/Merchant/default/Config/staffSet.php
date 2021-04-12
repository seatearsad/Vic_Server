<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/store')}">{pigcms{:L('STORE_MANAGEMENT_BKADMIN')}</a>
			</li>
			<li>
				<a href="{pigcms{:U('Config/staff', array('store_id' => $now_store['store_id']))}">【{pigcms{$now_store.name}】 {pigcms{:L('STAFF_LISTING_BKADMIN')}</a>
			</li>
			<li class="active"><if condition="$item">{pigcms{:L('EDIT_STAFF_BKADMIN')}<else/>{pigcms{:L('ADD_STAFF_BKADMIN')}</if></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post" action="">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">{pigcms{:L('NAME_BKADMIN')}</label></label>
									<input type="text" class="col-sm-2" name="name" id="name" value="{pigcms{$item.name}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">{pigcms{:L('STAFF_TYPE_BKADMIN')}</label></label>
									<select name="type">
										<volist name="staff_type" id="vo">
											<option value="{pigcms{$key}" <if condition="$key eq $item['type']">selected="selected"</if> >{pigcms{$vo}</option>
										</volist>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="username">{pigcms{:L('USERNAME_BKADMIN')}</label></label>
									<input type="text" class="col-sm-2" name="username" id="username" value="{pigcms{$item.username}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="password">{pigcms{:L('PASSWORD_BKADMIN')}</label></label>
									<input type="password" class="col-sm-2" name="password" id="password" />
									<if condition="$item['password']"><span class="form_tips">{pigcms{:L('BLANK_IF_NOCHANGE_BKADMIN')}</span></if>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_name">{pigcms{:L('PHONE_NUMBER_BKADMIN')}</label></label>
									<input type="text" class="col-sm-2" name="tel" id="tel" value="{pigcms{$item.tel}" />
                                    <span class="form_tips">{pigcms{:L('OPTIONAL_BKADMIN')}</span>
								</div>
								<div class="form-group hidden_obj">
									<label class="col-sm-1">能否修改订单价格</label>
									<label><span><label><input name="is_change" <if condition="$item['is_change'] eq 0 ">checked="checked"</if> value="0" type="radio"></label>&nbsp;<span>不能</span>&nbsp;</span></label>
									<label><span><label><input name="is_change" <if condition="$item['is_change'] eq 1 ">checked="checked"</if> value="1" type="radio" ></label>&nbsp;<span>能</span></span></label>
								</div>
								<div class="clearfix form-actions">
									<div class="col-md-9">
										<button class="btn btn-info" type="submit">
											<i class="ace-icon fa fa-check bigger-110"></i>
                                            {pigcms{:L('SAVE_BKADMIN')}
										</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<include file="Public:footer"/>