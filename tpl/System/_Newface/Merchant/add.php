<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Merchant/modify')}" frame="true" refresh="true">

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('E_MERCHANT_ACCOUNT')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="account" size="25"
                                           placeholder="{pigcms{:L('E_MERCHANT_ACCOUNT1')}"
                                           validate="maxlength:20,required:true"
                                           tips="{pigcms{:L('E_MERCHANT_ACCOUNTDESC')}"/>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('E_MERCHANT_PASSWORD')}</label>
                                <div class="col-sm-9">
                                    <input type="password" id="check_pwd" check_width="180" class="form-control"
                                           name="pwd" size="25" placeholder="{pigcms{:L('E_MERCHANT_PASSWORD1')}"
                                           validate="required:true,minlength:6"
                                           tips="{pigcms{:L('PHONE_ASSOCIATE_BKADMIN')}"/>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('E_MERCHANT_NAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" check_width="180" class="form-control" name="name" size="25"
                                           placeholder="" validate="maxlength:20,required:true"/>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('E_CONTACT_NUMBER1')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="phone" size="25"
                                           placeholder="{pigcms{:L('E_CONTACT_NUMBER1')}" validate="required:true"
                                           tips="{pigcms{:L('E_MERCHANT_NUMBER1DESC')}"/>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('E_CONTACT_EMAIL')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="email" size="25" placeholder=""
                                           validate="email:true" tips="{pigcms{:L('E_CONTACT_EMAILDESC')}"/>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('E_CITY_OF_LOCATION')}</label>
                                <div class="col-sm-9"><select name="city_id" class="form-control m-b">
                                        <volist name="city" id="vo">
                                            <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                                        </volist>
                                    </select></div>
                            </div>
                            <div class="hr-line-dashed"></div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_MER_STATUS_')}</label>
                                <div class="col-sm-3">
                                    <div class="switch">
                                        <div class="onoffswitch">
                                            <input name="status" type="checkbox" class="onoffswitch-checkbox"
                                                   id="status_input">
                                            <label class="onoffswitch-label" for="status_input">
                                                <span class="onoffswitch-inner"></span>
                                                <span class="onoffswitch-switch"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>


                                <!--tr>
                                    <th width="160">到期时间</th>
                                    <td><input type="text" class="input fl" name="merchant_end_time" size="25" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})" tips="商户到期时间，到期之后不允许进入商户平台并关闭该商户！清空为永不过期"/></td>
                                </tr-->

                                <!--tr>
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
                                </tr-->


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
        <include file="Public:footer_inc"/>