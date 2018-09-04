<include file="Public:header"/>
<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
    <tr>
        <td width="15%">ID</td>
        <td width="15%">名称</td>
        <td width="15%">总数</td>
        <td width="15%">已领</td>
        <td width="15%">时间</td>
        <td width="15%">优惠</td>
        <td width="15%">发放</td>
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
            <th width="15%">{pigcms{$vo.start_time|date='Y-m-d',###} 到 {pigcms{$vo.end_time|date='Y-m-d',###}</th>
            <th width="15%">满 {pigcms{$vo.order_money} 减 {pigcms{$vo.discount} 元</th>
            <th width="15%"><if condition="$vo.is_l eq 1"> <span style="color:red">已发</span> <else /> <a href="javascript:send_user({pigcms{$vo.coupon_id});" style="color: #0b6041">发放</a> </if></th>
        <tr/>
    </volist>
</table>
<include file="Public:footer"/>