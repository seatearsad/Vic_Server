<include file="Public:header"/>
<form id="myform" method="post" action="{pigcms{:U('Shop/save_edit_order')}" frame="true" refresh="true">
    <input type="hidden" name="order_id" value="{pigcms{$order.order_id}"/>
    <table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
        <tr>
            <th width="160">订单编号</th>
            <td><div class="show">{pigcms{$order.real_orderid}</div></td>
        </tr>
        <tr>
            <th width="160">总价</th>
            <td><input type="text" class="input fl" name="total_price" value="{pigcms{$order.total_price}" size="25" placeholder="总价" validate="maxlength:20,required:true"/></td>
        </tr>
        <tr>
            <th width="160">实际支付</th>
            <td><input type="text" class="input fl" name="price" value="{pigcms{$order.price}" size="25" placeholder="实际支付" validate="maxlength:20,required:true"/></td>
        </tr>
        <tr>
            <th width="160">商品总价</th>
            <td><input type="text" class="input fl" name="goods_price" value="{pigcms{$order.goods_price}" size="25" placeholder="商品总价" validate="maxlength:20,required:true"/></td>
        </tr>
        <tr>
            <th width="160">配送费</th>
            <td><input type="text" class="input fl" name="freight_charge" value="{pigcms{$order.freight_charge}" size="25" placeholder="配送费" validate="maxlength:20,required:true"/></td>
        </tr>
    </table>
    <div class="btn hidden">
        <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
        <input type="reset" value="取消" class="button" />
    </div>
</form>

<script>
    $(function(){
        if($('input[name="open_sub_mchid"]:checked').val()==1){
            $('.sub_mch').show();
        }else{
            $('.sub_mch').hide();
        }
        $('input[name="open_sub_mchid"]').click(function(){
            var sub = $(this);
            if(sub.val()==1){
                $('.sub_mch').show();
            }else{
                $('.sub_mch').hide();
            }
        });
    });
</script>

<include file="Public:footer"/>