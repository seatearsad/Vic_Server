<include file="Public:header"/>
<div style="margin: auto">
    <div>Start Date：<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/></div>
    <div style="margin-top: 10px;">End Date：<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/></div>

    <div style="margin-top: 20px;"><input type="button" value="Download" id="export"></div>
</div>

<script>
    $(function () {
        $('#export').click(function () {
            var b_time = $('input[name="begin_time"]').val();
            var e_time = $('input[name="end_time"]').val();
            if(b_time == '' || e_time == '')
                alert('Please Select Dates！');
            else
                window.location.href = "{pigcms{:U('Deliver/new_export',array('begin'=>'"+b_time+"','end'=>'"+e_time+"'))}";
        });
    });
</script>
<include file="Public:footer"/>