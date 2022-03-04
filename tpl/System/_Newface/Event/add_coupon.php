<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Event/coupon_modify')}" enctype="multipart/form-data">
                            <input name="event_id" value="{pigcms{$event_id}" type="hidden">
                            <input name="coupon_id" value="{pigcms{$coupon.id|default='0'}" type="hidden">
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_COUPON')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="name" size="20" validate="maxlength:200,required:true" value="{pigcms{$coupon.name|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_COUP_DESCRIPTION')}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="desc" validate="required:true">{pigcms{$coupon.desc|default=''}</textarea>
                                </div>
                            </div>
                            <if condition="$event_type neq 6">
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_MIN_ORDER')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="use_price" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.use_price|default='0.00'}"/>
                                </div>
                            </div>
                            </if>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">
                                    <if condition="$event_type neq 6">
                                        {pigcms{:L('G_DISCOUNT_AMOUNT')}
                                        <else />
                                        {pigcms{:L('G_DISCOUNT_AMOUNT_GOODS')}
                                    </if>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="discount" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.discount|default=''}"/>
                                    <if condition="$event_type eq 6">
                                        eg. Key in 0.80 for a 20% off discount
                                    </if>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{$type_name}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="limit_day" size="20" validate="maxlength:20,required:true" value="{pigcms{$coupon.limit_day|default=''}"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">
                                    <if condition="$event_type eq 3 or $event_type eq 4 or $event_type eq 5">
                                        {pigcms{:L('G_COMBINED')}
                                        <elseif condition="$event_type eq 6" />
                                            Apply discount to options
                                        <else />
                                        {pigcms{:L('G_COUP_TYPE')}
                                    </if>
                                </label>
                                <div class="col-sm-9">
                                    <select name="type" class="form-control">
                                        <option value="0" <if condition="$coupon.type eq 0">selected</if>>
                                        <if condition="$event_type eq 3 or $event_type eq 4 or $event_type eq 5">
                                            {pigcms{:L('G_NO')}
                                            <elseif condition="$event_type eq 6" />
                                            NO
                                            <else />
                                            {pigcms{:L('G_INVITEE')}
                                        </if>
                                        </option>
                                        <option value="1" <if condition="$coupon.type eq 1">selected</if>>
                                        <if condition="$event_type eq 3 or $event_type eq 4 or $event_type eq 5">
                                            {pigcms{:L('G_YES')}
                                            <elseif condition="$event_type eq 6" />
                                            YES
                                            <else />
                                            {pigcms{:L('G_INVITER')}
                                        </if>
                                        </option>
                                    </select>
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
<include file="Public:footer_inc"/>