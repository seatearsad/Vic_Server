<include file="Public:header"/>
<div class="mainbox">
    <div id="nav" class="mainnav_title">
        <a href="{pigcms{:U('Index/pass')}" class="on">{pigcms{:L('_BACK_CHANGE_PASS_')}</a>
    </div>
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
