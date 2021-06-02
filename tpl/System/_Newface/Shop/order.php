<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('_BACK_ORDER_LIST_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_ORDER_LIST_')}</strong>
                </li>
            </ol>
        </div>

        <div class="col-lg-6 float-right" style="height 90px;margin-top:40px;">
<!--            <div class="btn-group">-->
<!--                <button class="btn btn-white" type="button">Left</button>-->
<!--                <button class="btn btn-primary" type="button">Middle</button>-->
<!--                <button class="btn btn-white" type="button">Right</button>-->
<!--            </div>-->
                <div class="btn-group float-right">
                    <a href="{pigcms{:U('Shop/export',$_GET)}" class="button" style="float:right;margin-right: 10px;"><button class="btn btn-white text-grey">{pigcms{:L('_BACK_DOWN_ORDER_')}</button></a>
                    <if condition="$system_session['level'] eq 2">
                    <a href="{pigcms{:U('Shop/export_total',$_GET)}" class="button" style="float:right;margin-right: 10px;"><button class="btn btn-white text-grey ">{pigcms{:L('C_OMZB')}</button></a>
                    <a href="{pigcms{:U('Shop/export_store',$_GET)}" class="button" style="float:right;margin-right: 10px;"><button class="btn btn-white text-grey">{pigcms{:L('C_OMRESTRANK')}</button></a>
                    <a href="{pigcms{:U('Shop/export_user',$_GET)}" class="button" style="float:right;margin-right: 10px;"><button class="btn btn-white text-grey">{pigcms{:L('C_OMUSERRANK')}</button></a>
                    </if>
                </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5><if condition="$system_session['level'] neq 3">
                                <b>{pigcms{:L('_BACK_A_RECE_')}：{pigcms{$total_price|floatval} &nbsp;&nbsp;
                                    {pigcms{:L('_BACK_A_PAID_ON_')}：{pigcms{$online_price|floatval} &nbsp;&nbsp;
                                    {pigcms{:L('_BACK_A_PAID_CASH_')}：{pigcms{$offline_price|floatval}
                                </b>
                            </if>
                        </h5>
                        <div class="ibox-tools tutti_hidden_obj">
                            <if condition="$system_session['level'] neq 3">
                                <span style="margin-left:40px">
                                    <if condition="$system_session['level'] neq 3">
                                       <b>{pigcms{:L('_BACK_A_RECE_')}：{pigcms{$total_price|floatval} &nbsp;&nbsp;
                                          {pigcms{:L('_BACK_A_PAID_ON_')}：{pigcms{$online_price|floatval} &nbsp;&nbsp;
                                          {pigcms{:L('_BACK_A_PAID_CASH_')}：{pigcms{$offline_price|floatval}
                                        </b>
                                    </if>
                                </span>
                            </if>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <!-------------------------------- 工具条 -------------------------------------->
                        <div style="height: 50px;">

                                <div id="tool_bar" style="form-group tutti_toolbar" style="height: 80px;">

                                    <form action="{pigcms{:U('Shop/order')}" method="get" class="form-inline ">
                                        <input type="hidden" name="c" value="Shop"/>
                                        <input type="hidden" name="a" value="order"/>
                                        {pigcms{:L('_BACK_SEARCH_')}:&nbsp; <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>&nbsp;
                                        <select name="searchtype" class="form-control">
                                            <option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected" </if> >{pigcms{:L('_BACK_ORDER_NUM_')}</option>
                                            <!--option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
                                            <option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option-->
                                            <option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{:L('_BACK_STORE_NAME_')}</option>
                                            <option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('_BACK_USER_NAME_')}</option>
                                            <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_USER_PHONE_')}</option>
                                            <option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>User ID</option>
                                            <option value="orderid"<if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>Order ID</option>
                                        </select>
                                        &nbsp;{pigcms{:L('_BACK_DATE_SELECT_')}：
                                        <input type="text" class="form-control" name="begin_time" style="width:120px;"
                                               id="d4311" value="{pigcms{$_GET.begin_time}"
                                               onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>&nbsp;
                                        <input type="text" class="form-control" name="end_time" style="width:120px;"
                                               id="d4311" value="{pigcms{$_GET.end_time}"
                                               onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>&nbsp;
                                        {pigcms{:L('_BACK_ORDER_STATUS_')}:&nbsp;
                                        <select id="status" name="status" class="form-control">
                                            <volist name="status_list" id="vo">
                                                <option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if> >
                                                {pigcms{$vo}
                                                </option>
                                            </volist>
                                        </select>&nbsp;
                                        {pigcms{:L('_BACK_PAYMENT_METHOD_')}:&nbsp;
                                        <select id="pay_type" name="pay_type" class="form-control">
                                            <option value=""
                                            <if condition="'' eq $pay_type">selected="selected"</if>
                                            >{pigcms{:L('_BACK_ALL_')}</option>
                                            <volist name="pay_method" id="vo">
                                                <option value="{pigcms{$key}"
                                                <if condition="$key eq $pay_type">selected="selected"</if>
                                                >{pigcms{$vo.name}</option>
                                            </volist>
                                            <option value="balance"
                                            <if condition="'balance' eq $pay_type">selected="selected"</if>
                                            >{pigcms{:L('_BACK_BALANCE_')}</option>
                                        </select>&nbsp;
                                        <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="form-control"/>　
                                    </form>
                                </div>
                        </div>
                        <!------------------------------------------------------------------------------>
                        <!-- <form name="myform" id="myform" action="" method="post">-->
                        <table class="footable table table-stripped toggle-arrow-tiny" data-sorting="false">
                            <thead>
                            <tr>
                                <th>Order ID</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_STORE_NAME_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_STORE_PHONE_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_USER_NAME_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_USER_PHONE_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_INIT_TOTAL_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_TOTAL_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_TIPS_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_TUTTI_DIS_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_MER_DIS_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_AM_RECE_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_PAY_TIME_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_PREP_TIME_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_ARR_TIME_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_ORDER_STATUS_')}</th>
                                <!--                                    <th data-hide="all">查看支票</th>-->
                                <th data-hide="all">{pigcms{:L('_BACK_ORDER_NUM_')}</th>
                                <th data-hide="all">{pigcms{:L('_BACK_REG_NUM_')}</th>
                                <th data-hide="all">{pigcms{:L('_BACK_CUSTOM_ADD_')}</th>
                                <th data-hide="all">{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>
                                <th data-hide="all">送餐员+电话</th>
                                <th data-hide="all">{pigcms{:L('_BACK_PAY_STATUS_')}</th>
                                <th data-sortable="false">{pigcms{:L('_BACK_CZ_')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="is_array($order_list)">
                                <volist name="order_list" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.order_id}</td>
                                        <td>{pigcms{$vo.store_name}</td>
                                        <td>{pigcms{$vo.store_phone}</td>
                                        <td>{pigcms{$vo.username}</td>
                                        <td>{pigcms{$vo.userphone}</td>
                                        <td>
                                            <php>if($vo['is_refund'] == 1){</php>
                                            ${pigcms{$vo['change_price'] + $vo['tip_charge']|floatval}
                                            <php>}</php>
                                        </td>
                                        <td>${pigcms{$vo['price'] + $vo['tip_charge']- $vo['coupon_price'] -
                                            $vo['delivery_discount'] - $vo['merchant_reduce']|floatval}
                                        </td>
                                        <td>${pigcms{$vo['tip_charge']|floatval}</td>
                                        <td>${pigcms{$vo['coupon_price'] + $vo['delivery_discount']|floatval}</td>
                                        <td>${pigcms{$vo.merchant_reduce|floatval}</td>
                                        <td>${pigcms{$vo['duty_price']|floatval}</td>
                                        <td>
                                            <if condition="$vo['pay_time']"> {pigcms{$vo['pay_time']|date="Y-m-d
                                                H:i:s",###}
                                            </if>
                                        </td>
                                        <td>{pigcms{$vo.dining_time}</td>
                                        <td>
                                            <if condition="$vo['use_time']">{pigcms{$vo['use_time']|date="Y-m-d
                                                H:i:s",###}
                                            </if>
                                        </td>
                                        <td>{pigcms{$vo.status_str}</td>

                                        <!--                                            <if condition="$system_session['level'] neq 3">-->
                                        <!--                                                <td><a target="_blank" href="{pigcms{:U('Bill/merchant_money_list',array('mer_id'=>$vo['mer_id']))}">{pigcms{:L('_BACK_INVOICE_')}</a>-->
                                        <!--                                                </td>-->
                                        <!--                                            </if>-->
                                        <!--td class="textcenter"><a href="{pigcms{:U('Merchant/weidian_order',array('mer_id'=>$vo['mer_id']))}">微店账单</a></td-->
                                        <td>
                                            {pigcms{$vo.real_orderid}
                                        </td>
                                        <td>
                                            {pigcms{$vo.reg_user_phone}
                                        </td>
                                        <td>
                                            {pigcms{$vo.address}
                                        </td>
                                        <td>
                                            {pigcms{$vo.deliver_status_str}
                                        </td>
                                        <td>
                                            {pigcms{$vo.deliverinfo}
                                        </td>
<!--                                        {pigcms{$vo.pay_status} -({pigcms{$vo.pay_type})--->
                                        <td><span style="color: green">{pigcms{$vo.pay_type_str}</span>
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                 <div class="float-right">
                                                     <if condition="$vo.status eq 0 AND $vo.paid eq 1">&nbsp;&nbsp;&nbsp;&nbsp;
                                                         <a data-href="{pigcms{:U('Shop/refund_update',array('order_id'=>$vo['order_id']))}" class="refund"><button class="btn btn-white text-grey btn-xs" type="button">{pigcms{:L('_BACK_MANUAL_REFUND_')}</button></a>
                                                     </if>
                                                     <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_ORDER_DETAIL_\')}',920,520,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey btn-xs" type="button">{pigcms{:L('_BACK_VIEW_')}</button></a>

                                                     <php>if($vo['status'] > 0){</php>
                                                     <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/edit_order',array('order_id'=>$vo['order_id']))}','{pigcms{:L(\'_BACK_EDIT_\')}',920,520,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey btn-xs" type="button">{pigcms{:L('_BACK_EDIT_')}</button></a>
                                                     <php>}</php>
                                                     <a href="{pigcms{:U('Shop/del',array('id'=>$vo['order_id']))}" onclick="return confirm('{pigcms{:L(\'_B_PURE_MY_84_\')}')" style="color: red"><button class="btn btn-white text-grey btn-xs" type="button">{pigcms{:L('_BACK_DEL_')}</button></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </volist>
                                <else/>
                                <tr>
                                    <td
                                    <if condition="$system_session['level'] neq 3">colspan="9"
                                        <else/>
                                        colspan="22"
                                    </if>
                                    >{pigcms{:L('_BACK_EMPTY_')}</td>
                                </tr>
                            </if>
                            </tbody>
                            <tfoot>
                            <tr>
                            </tr>
                            </tfoot>
                        </table>
                        <div id="table_pagebar" style="height: 30px;">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- Page-Level Scripts -->
<script>
    var pagestr='{pigcms{$pagebar}';
    let pagediv= $('#table_pagebar');
    $(document).ready(function () {

        $('.footable').footable({
            "columns": {
                "sortable": false
            }, "sorting": {
                "enabled": false
            }
        });
        // $('.footable').footable({
        //     "columns": {
        //         "sortable": false
        //     },{
        //         ...
        //     }
        // });
        pagediv.html(pagestr);
    });
</script>

<script>

    var city_id = $('#city_select').val();
    $('#city_select').change(function () {
        // city_id = $(this).val();
        // window.location.href = "{pigcms{:U('Shop/order', $_GET)}" + "&city_id=" + city_id;
    });

    $(function () {
        $('#status').change(function () {
            // location.href = "{pigcms{:U('Shop/order', array('type' => $type, 'sort' => $sort,'pay_type'=>$pay_type,'city_id'=>$city_id))}&status=" + $(this).val();
        });

        $('#pay_type').change(function () {
            // location.href = "{pigcms{:U('Shop/order', array('type' => $type, 'sort' => $sort,'status'=>$status,'city_id'=>$city_id))}&pay_type=" + $(this).val();
        });

        $('.refund').click(function () {
            var get_url = $(this).data('href'), obj = $(this);
            window.top.art.dialog({
                title: 'Reminder',
                content: 'Are you sure about refund?',
                lock: true,
                okVal: 'Yes',
                ok: function () {
                    this.close();
                    $.get(get_url, function (response) {
                        if (response.status == 1) {
                            obj.parents('tr').find('.status').html('<del style="color:gray">已退款</del>');
                            obj.remove();
                        } else {
                            window.top.art.dialog({
                                title: response.info
                            });
                        }
                    }, 'json');
                    return false;
                },
                cancelVal: "{pigcms{:L('_BACK_CANCEL_')}",
                cancel: true
            });
        });
    });

</script>

<!----------------------------------------    以下不要写代码     ------------------------------------------------>
<include file="Public:footer"/>
