<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Deliver/assign_setting')}" frame="true" refresh="true">
                            <volist name="setting" id="vo">
                            <div class="form-group  row">
                                <label class="col-sm-4 col-form-label">{pigcms{$vo['info']}</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" name="{pigcms{$vo['name']}" size="20" placeholder="" validate="required:true" value="{pigcms{$vo['value']}"/>
                                </div>
                            </div>
                            </volist>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('BASE_SUBMIT')}" class="button" />
                                <input type="reset" value="{pigcms{:L('BASE_CANCEL')}" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<include file="Public:footer_inc"/>