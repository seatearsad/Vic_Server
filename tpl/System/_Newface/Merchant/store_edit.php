<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Merchant/store_amend')}" frame="true" refresh="true">
                            <input type="hidden" name="store_id" value="{pigcms{$store.store_id}"/>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_STORE_NAME')}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" value="{pigcms{$store.name}" size="25" placeholder="" validate="required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_CONTACT_NUMBER1')}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="phone" size="25" value="{pigcms{$store.phone}" placeholder="" validate="required:true" tips=""/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_STORE_COORDINATE')}</label>
                                <div class="col-sm-8" id="choose_map" default_long_lat="{pigcms{$store.long},{pigcms{$store.lat}">

                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_STORE_LOCATION')}</label>
                                <div class="col-sm-8">
                                    <select name="city_id" id="city_select" class="form-control m-b">
                                        <option value="0" <if condition="$store['city_id'] eq '' or $store['city_id'] eq 0">selected="selected"</if>>{pigcms{:L('_B_MY_CHOOSECITY_')}</option>
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}" <if condition="$store['city_id'] eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_STORE_ADDRESS')}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="adress" id="adress" value="{pigcms{$store.adress}" size="25" placeholder="店铺的地址" validate="required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_STORE_ORDER')}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="sort" size="5" value="{pigcms{$store.sort}" validate="required:true,number:true,maxlength:6" tips=""/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('_SELF_LIFT_')}</label>
                                <div class="col-sm-8">
                                    <span class="cb-enable"><label class="cb-enable <if condition="$store['is_pickup'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="is_pickup" value="1" <if condition="$store['is_pickup'] eq 1">checked="checked"</if> /></label></span>
                                    <span class="cb-disable"><label class="cb-disable <if condition="$store['is_pickup'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" name="is_pickup" value="0" <if condition="$store['is_pickup'] eq 0">checked="checked"</if>/></label></span>
                                    <input type="text" class="col-sm-4" name="pickup_radius" size="7" value="{pigcms{$store.pickup_radius}" validate="required:true,number:true,maxlength:7" style="margin-left: 10px;"/> km
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_DELIVERY_FUNCTION')}</label>
                                <div class="col-sm-8">
                                    <span class="cb-enable"><label class="cb-enable <if condition="$store['have_shop'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ON_')}</span><input type="radio" name="have_shop" value="1" <if condition="$store['have_shop'] eq 1">checked="checked"</if> /></label></span>
                                    <span class="cb-disable"><label class="cb-disable <if condition="$store['have_shop'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_OFF_')}</span><input type="radio" name="have_shop" value="0" <if condition="$store['have_shop'] eq 0">checked="checked"</if>/></label></span>
                                    <input type="text" class="col-sm-4" name="delivery_radius" size="7" value="{pigcms{$store.delivery_radius}" validate="required:true,number:true,maxlength:7" style="margin-left: 10px;"/> km
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_STORE_STATUS')}</label>
                                <div class="col-sm-8">
                                    <span class="cb-enable"><label class="cb-enable <if condition="$store['status'] eq 1">selected</if>"><span>{pigcms{:L('_BACK_ACTIVE_')}</span><input type="radio" name="status" value="1" <if condition="$store['status'] eq 1">checked="checked"</if> /></label></span>
                                    <span class="cb-disable"><label class="cb-disable <if condition="$store['status'] eq 0">selected</if>"><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="status" value="0" <if condition="$store['status'] eq 0">checked="checked"</if>/></label></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('BASE_ENCRYPTION')}</label>
                                <div class="col-sm-8">
                                    <span class="cb-enable"><label class="cb-enable <if condition="$store['pay_secret'] eq 1">selected</if>"><span>{pigcms{:L('C_ENCRYPTION1')}</span><input type="radio" name="pay_secret" value="1" <if condition="$store['pay_secret'] eq 1">checked="checked"</if> /></label></span>
                                    <span class="cb-disable"><label class="cb-disable <if condition="$store['pay_secret'] eq 0">selected</if>"><span>{pigcms{:L('C_ENCRYPTION2')}</span><input type="radio" name="pay_secret" value="0" <if condition="$store['pay_secret'] eq 0">checked="checked"</if>/></label></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_TAX_PERCENTAGE')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="touchspin2 form-control" name="tax_num" size="5" value="{pigcms{$store['tax_num']}" validate="required:true,number:true,maxlength:6" />
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('_BACK_PROPORTION_')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="touchspin2 form-control" name="proportion" size="5" value="{pigcms{$store['proportion']}" validate="required:true,number:true,maxlength:6" />
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_Default_Tax_Rate')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="touchspin2 form-control" name="default_tax" size="5" value="{pigcms{$store['default_tax']}" validate="required:true,number:true,maxlength:6" />
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_SERVICE_FEE')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="touchspin2 form-control" name="service_fee" size="5" value="{pigcms{$store['service_fee']}" validate="required:true,number:true,maxlength:6" />
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('E_SERVICE_FEE')}({pigcms{:L('_SELF_LIFT_')})</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="touchspin2 form-control" name="pickup_service_fee" size="5" value="{pigcms{$store['pickup_service_fee']}" validate="required:true,number:true,maxlength:6" />
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{:L('_BACK_PROPORTION_')}({pigcms{:L('_SELF_LIFT_')})</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="touchspin2 form-control" name="pickup_proprotion" size="5" value="{pigcms{$store['pickup_proprotion']}" validate="required:true,number:true,maxlength:6" />
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">Store Tag</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected" id="tag_label">
                                    <input type="hidden" id="store_tag" name="store_tag" value="{pigcms{$store['store_tag']}" />
                                    <volist name="store_tag_list" id="vo">
                                        <if condition="$vo['check'] eq 1">
                                            <button type="button" class="btn btn-primary" data-key="{pigcms{$key}" style="margin: 5px 2px">{pigcms{$vo['name']}</button>
                                            <else />
                                            <button type="button" class="btn btn-default" data-key="{pigcms{$key}" style="margin: 5px 2px">{pigcms{$vo['name']}</button>
                                        </if>
                                    </volist>
                                </div>
                            </div>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
                                <input type="reset" value="取消" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $("#tag_label").children('button').each(function () {
                $(this).click(function () {
                    if($(this).hasClass("btn-primary")){
                        if($(this).data('key').toString() == $("#store_tag").val()){
                            alert("至少需要一个标签");
                        }else{
                            $(this).removeClass("btn-primary");
                            $(this).addClass("btn-default");
                        }
                    }else if($(this).hasClass("btn-default")){
                        $(this).removeClass("btn-default");
                        $(this).addClass("btn-primary");
                    }

                    handleTag();
                });
            });

            function handleTag() {
                var tag_val = "";
                $("#tag_label").children('button').each(function () {
                    if($(this).hasClass("btn-primary")){
                        tag_val = tag_val == "" ? $(this).data("key").toString() : tag_val+","+ $(this).data("key").toString();
                    }
                });

                $("#store_tag").val(tag_val);
            }
        </script>
<include file="Public:footer_inc"/>