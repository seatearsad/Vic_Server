<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{pigcms{:L('_BACK_CHANGE_PASS_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('B_INFO')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_CHANGE_PASS_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="row wrapper wrapper-content animated fadeInRight">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">
                    <form id="myform" method="post" action="{pigcms{:U('Index/amend_pass')}" refresh="true">
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('_BACK_OLD_PASS_')}</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="password" name="old_pass"/>
                            </div>
                        </div>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('_BACK_NEW_PASS_')}</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="password" name="new_pass" id="password" validate="required:true,minlength:8,maxlength:20"/>
                            </div>
                        </div>
                        <div class="form-group  row">
                            <label class="col-sm-2 col-form-label">{pigcms{:L('_BACK_CONFIRM_PASS_')}</label>
                            <div class="col-sm-10">
                                <input class="form-control" type="password" name="re_pass" validate="required:true,equalTo:'#password'"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="button"  name="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}" class="btn btn-w-m btn-primary" onclick="checkSubmit()" />
                            &nbsp;
                            <input type="reset"  value="{pigcms{:L('_BACK_CANCEL_')}" class="btn btn-w-m btn-primary" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
</div>
<script>
    function checkSubmit() {
        var pwd = $('#password').val();
        if (pwd.length >= 8) {
            if (/[a-z]+/.test(pwd) && /[0-9]+/.test(pwd) && /[A-Z]+/.test(pwd)){// && /\W+\D+/.test(pwd)) {
                //m = 3;
                $('#myform').submit();
            } else{ //if (/[a-zA-Z]+/.test(pwd) || /[0-9]+/.test(pwd) || /\W+\D+/.test(pwd)) {
                // if (/[a-zA-Z]+/.test(pwd) && /[0-9]+/.test(pwd)) {
                //     m = 2;
                // } else if (/[a-zA-Z]+/.test(pwd) && /\W+\D+/.test(pwd)) {
                //     m = 2;
                // } else if (/[0-9]+/.test(pwd) && /\W+\D+/.test(pwd)) {
                //     m = 2;
                // } else {
                //     m = 1;
                // }
                alert("{pigcms{:L('B_NEWPASSDESC')}");
            }
        } else {
            alert("{pigcms{:L('B_NEWPASSDESC')}");
        }
        return false;
    }
</script>
<include file="Public:footer"/>
