<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>{pigcms{:L('_BACK_CHANGE_PASS_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
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
                        <table cellpadding="0" cellspacing="0" class="table_form" width="100%">
                            <tr>
                                <th width="100">{pigcms{:L('_BACK_OLD_PASS_')}：</th>
                                <td><input type="password" class="input-text" name="old_pass"/></td>
                            </tr>
                            <tr>
                                <th>{pigcms{:L('_BACK_NEW_PASS_')}：</th>
                                <td><input type="password" class="input-text"  name="new_pass" id="password" validate="required:true,minlength:8,maxlength:20"/></td>
                            </tr>
                            <tr>
                                <th>{pigcms{:L('_BACK_CONFIRM_PASS_')}：</th>
                                <td><input type="password" class="input-text"  name="re_pass" validate="required:true,equalTo:'#password'"/></td>
                            </tr>
                        </table>
                        <div class="btn">
                            <input type="button"  name="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}" class="button" onclick="checkSubmit()" />
                            <input type="reset"  value="{pigcms{:L('_BACK_CANCEL_')}" class="button" />
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
