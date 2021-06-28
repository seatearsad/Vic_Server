<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
            <h2>{pigcms{:L('F_TOP_UP_LIST')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Index/index')}">Home</a>
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('F_TOP_UP_LIST')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-3" style="height 90px;margin-top:40px;">
            <button class="btn btn-white float-right">
                <a href="{pigcms{:U('User/admin_recharge_list')}"
                   style="color: inherit">{pigcms{:L('F_CREDITS_ADDED')}</a>
            </button>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('F_TOP_UP_LIST')}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <div style="margin-bottom: 15px;min-height: 50px">
                                <div id="tool_bar" class="form-inline tutti_toolbar">
                                    <form action="{pigcms{:U('recharge_list')}" method="get">
                                        <input type="hidden" name="c" value="User"/>
                                        <input type="hidden" name="a" value="recharge_list"/>
                                        {pigcms{:L('F_FILTER')}:
                                        <input type="text" name="keyword" class="form-control"
                                               value="{pigcms{$_GET['keyword']}"/>
                                        <select name="searchtype" class="form-control">
                                            <option value="order_id"
                                            <if condition="$_GET['searchtype'] eq 'order_id'">selected="selected"</if>
                                            >{pigcms{:L('F_ORDER_ID')}</option>
                                            <option value="uid"
                                            <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>
                                            >{pigcms{:L('F_USER_ID')}</option>
                                            <option value="name"
                                            <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>
                                            >{pigcms{:L('F_USER_NAME')}</option>
                                            <option value="phone"
                                            <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>
                                            >{pigcms{:L('F_USER_PHONE')}</option>
                                        </select>
                                        <input type="submit" value="{pigcms{:L('F_SEARCH')}" class="form-control"/>　　
                                    </form>
                                </div>
                            </div>

                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                <tr>
                                    <th>{pigcms{:L('F_ORDER_ID')}</th>
                                    <th>{pigcms{:L('F_AMOUNT')}</th>
                                    <th>{pigcms{:L('F_USER')}</th>
                                    <th>{pigcms{:L('F_USER_ID')}</th>
                                    <th>{pigcms{:L('F_TIME')}</th>
                                    <th class="textcenter">{pigcms{:L('F_ACTION')}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <if condition="is_array($order_list)">
                                    <volist name="order_list" id="vo">
                                        <tr>
                                            <td>{pigcms{$vo.order_id}</td>
                                            <td>${pigcms{$vo.money}</td>

                                            <td>{pigcms{$vo.nickname}</td>
                                            <td>
                                                {pigcms{$vo.uid}
                                            </td>
                                            <td>
                                                {pigcms{:L('F_ORDERING_TIME')}：{pigcms{$vo['add_time']|date='Y-m-d
                                                H:i:s',###}<br/>
                                                <if condition="$vo['paid']">
                                                    {pigcms{:L('F_PAYMENT_TIME')}：{pigcms{$vo['pay_time']|date='Y-m-d
                                                    H:i:s',###}
                                                </if>
                                            </td>
                                            <td class="textcenter">
                                                <a href="javascript:void(0);"
                                                   onclick="window.top.artiframe('{pigcms{:U('User/order_detail',array('order_id'=>$vo['order_id']))}','{pigcms{:L(\'F_TOPUP_DETAILS\')}',800,560,true,false,false,false,'order_edit',true);">
                                                    <button class="btn btn-white text-grey" type="button">
                                                        {pigcms{:L('F_DETAILS')}
                                                    </button>
                                                </a>
                                                <a href="javascript:void(0);"
                                                   onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','{pigcms{:L(\'F_EDIT_INFO\')}',680,560,true,false,false,editbtn,'edit',true);">
                                                    <button class="btn btn-white text-grey" type="button">
                                                        {pigcms{:L('F_USER_INFO')}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    </volist>
                                    <tr>
                                        <td class="textcenter pagebar" colspan="6">{pigcms{$pagebar}</td>
                                    </tr>
                                    <else/>
                                    <tr>
                                        <td class="textcenter red" colspan="6">{pigcms{:L('_BACK_EMPTY_')}</td>
                                    </tr>
                                </if>
                                </tbody>
                                <tfoot>
                                <tr>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(function () {
                    $('#status').change(function () {
                        location.href = "{pigcms{:U('User/recharge_list')}&status=" + $(this).val();
                    });
                });
            </script>
            <include file="Public:footer"/>