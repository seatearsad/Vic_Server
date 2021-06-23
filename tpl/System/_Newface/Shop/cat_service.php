<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Shop/cat_service')}" frame="true" refresh="true">
                            <input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
                            <input type="hidden" name="cat_fid" value="{pigcms{$parentid}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEGORYNAME')}</label>
                                <div class="col-sm-9 col-form-label">
                                    {pigcms{$now_category.cat_name}
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_SFRATE')}</label>
                                <div class="col-sm-8 input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                    <input type="text" class="touchspin2 form-control" name="service_fee" id="service_fee" validate="maxlength:2,required:true,number:true" size="25" />
                                    <span class="input-group-addon bootstrap-touchspin-postfix input-group-append"><span class="input-group-text">%</span></span>
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