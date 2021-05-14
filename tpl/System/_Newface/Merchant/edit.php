<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

                    <div class="col-lg-12">
                        <div class="ibox">
                            <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Merchant/amend')}" frame="true"
                              refresh="true">

                            <input type="hidden" name="mer_id" value="{pigcms{$merchant.mer_id}"/>
                            <input type="hidden" class="input fl" name="bill_period"
                                   value="{pigcms{$merchant.bill_period}"/>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MER_ACC_')}</label>
                                <label class="col-sm-9 col-form-label">{pigcms{$merchant.account}</label>
                            </div>

                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MER_PASS_')}</label>
                                <div class="col-sm-9"><input type="password" id="check_pwd" check_width="180"
                                                             check_event="keyup" class="form-control"
                                                             name="pwd"
                                                             value="" size="25" validate="minlength:6"/></div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MER_NAME_')}</label>
                                <div class="col-sm-9"><input class="form-control" type="text" class="form-control"
                                                             name="name" value="{pigcms{$merchant.name}" size="25"
                                                             validate="maxlength:20,required:true"/></div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MER_PHONE_')}</label>
                                <div class="col-sm-9"><input class="form-control" type="text" class="form-control"
                                                             name="phone" value="{pigcms{$merchant.phone}" size="25"
                                                             validate="required:true"/></div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_EMAIL_')}</label>
                                <div class="col-sm-9"><input class="form-control" type="text" class="form-control"
                                                             name="email" value="{pigcms{$merchant.email}" size="25"
                                                             validate="email:true"/></div>
                            </div>
                            <div class="hr-line-dashed"></div>


                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STORE_AREA_')}</label>
                                <div class="col-sm-9"><input class="form-control" type="text" class="form-control"
                                                             name="email" value="{pigcms{$merchant.email}" size="25"
                                                             validate="email:true"/></div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STORE_AREA_')}</label>
                                <div class="col-sm-9"> <select name="city_id" class="form-control m-b">
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}"
                                            <if condition="$merchant['city_id'] eq $vo['area_id']">
                                                selected="selected"
                                            </if>
                                            >{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select></div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STORE_AREA_')}</label>
                                <div class="col-sm-9"><input class="form-control" type="text" class="form-control"
                                                             name="email" value="{pigcms{$merchant.email}" size="25"
                                                             validate="email:true"/></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_EXPIRE_DATE_')}</label>
                                <div class="col-sm-3"><input class="form-control" type="text" class="form-control"
                                                             name="merchant_end_time" value="{pigcms{$merchant.merchant_end_time}" size="25"
                                                             onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm',lang:'en'})"/></div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MER_STATUS_')}</label>
                                <div class="col-sm-3">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input name="status" type="checkbox" <if condition="$merchant['status'] eq 1">checked="checked"</if> class="onoffswitch-checkbox" id="status_input">
                                            <label class="onoffswitch-label" for="status_input">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                            </div>

                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
                                <input type="reset" value="取消" class="button"/>
                            </div>
                        </form>
                            </div>
                    </div>
                </div>


            </div>

            <script>
                $(function () {
                    if ($('input[name="open_sub_mchid"]:checked').val() == 1) {
                        $('.sub_mch').show();
                    } else {
                        $('.sub_mch').hide();
                    }
                    $('input[name="open_sub_mchid"]').click(function () {
                        var sub = $(this);
                        if (sub.val() == 1) {
                            $('.sub_mch').show();
                        } else {
                            $('.sub_mch').hide();
                        }
                    });
                });
            </script>

            <!----------------------------------------    以下不要写代码     ------------------------------------------------>
            <include file="Public:footer_inc"/>