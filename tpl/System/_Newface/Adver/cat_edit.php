<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Adver/cat_amend')}" frame="true" refresh="true">
                            <input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('G_NAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cat_name" value="{pigcms{$now_category.cat_name}" size="10" placeholder="" validate="maxlength:20,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_SYMBOL')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cat_key" value="{pigcms{$now_category.cat_key}" size="10" placeholder="" validate="maxlength:50,required:true" tips=""/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('I_TYPE')}</label>
                                <div class="col-sm-9">
                                    <span class="cb-enable"><label class="cb-enable <if condition="$now_category['cat_type'] eq 0">selected</if>"><span>WAP</span><input type="radio" name="cat_type" value="0" <if condition="$now_category['cat_type'] eq 0">checked="checked"</if> /></label></span>
                                    <span class="cb-disable"><label class="cb-disable <if condition="$now_category['cat_type'] eq 1">selected</if>"><span>PC</span><input type="radio" name="cat_type" value="1" <if condition="$now_category['cat_type'] eq 1">checked="checked"</if> /></label></span>
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