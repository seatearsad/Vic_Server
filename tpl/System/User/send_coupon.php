<include file="Public:header"/>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
    <tr>
        <td width="15%">ID</td>
        <td width="15%">Title</td>
        <td width="15%">Total</td>
        <td width="15%">Claimed</td>
        <td width="15%">Valid Date</td>
        <td width="15%">Promotion</td>
        <td width="15%">Action</td>
    <tr/>
    <script type="text/javascript">
        function send_user(cid){
            $.ajax({url:"{pigcms{:U('User/sendCouponToUser')}",type:"post",data:"uid={pigcms{$uid}&cid="+cid,dataType:"json",success:function(data){
                    if(data.error_code == 0){
                        alert('success');
                        window.location.reload();
                    }
                }
            });
        }
    </script>
    <volist name="list" id="vo">
        <tr>
            <th width="15%">{pigcms{$vo.coupon_id}</th>
            <th width="15%">{pigcms{$vo.name}</th>
            <th width="15%">{pigcms{$vo.num}</th>
            <th width="15%">{pigcms{$vo.had_pull}</th>
            <th width="15%">{pigcms{$vo.start_time|date='Y-m-d',###} - {pigcms{$vo.end_time|date='Y-m-d',###}</th>
            <th width="15%">
                <php>if(C('DEFAULT_LANG') == 'zh-cn'){</php>
                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['order_money'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['discount'])}
                <php>}else{</php>
                {pigcms{:replace_lang_str(L('_MAN_NUM_REDUCE_'),$vo['discount'])}{pigcms{:replace_lang_str(L('_MAN_REDUCE_NUM_'),$vo['order_money'])}
                <php>}</php>
            </th>
            <th width="15%"><if condition="$vo.is_l eq 1"> <span style="color:red">Assigned</span> <else /> <a href="javascript:send_user({pigcms{$vo.coupon_id});" style="color: #0b6041">Assign</a> </if></th>
        <tr/>
    </volist>
</table>
<include file="Public:footer"/>