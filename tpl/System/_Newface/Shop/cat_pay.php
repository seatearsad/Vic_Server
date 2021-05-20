<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Shop/cat_pay')}" frame="true" refresh="true">
                            <input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
                            <input type="hidden" name="cat_fid" value="{pigcms{$parentid}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEGORYNAME')}</label>
                                <div class="col-sm-9 col-form-label">
                                    {pigcms{$now_category.cat_name}
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('BASE_ENCRYPTION')}</label>
                                <div class="col-sm-9 col-form-label">
                                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_ENCRYPTION1')}</span><input type="radio" name="pay_secret" value="1" checked="checked" /></label></span>
                                    <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_ENCRYPTION2')}</span><input type="radio" name="pay_secret" value="0" /></label></span>
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