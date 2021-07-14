<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>

    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('_BACK_BAG_LIST_TITLE_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_BAG_LIST_TITLE_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-6 " style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">
                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/bag_add')}','{pigcms{:L(\'_BACK_BAG_LIST_ADD_\')}',680,560,true,false,false,editbtn,'edit',true);" style="float:right;margin-left: 10px;"><button class="btn btn-primary">{pigcms{:L('_BACK_BAG_LIST_ADD_')}</button></a>
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('_BACK_DELIVERY_LIST_')}</h5>
                        <div class="ibox-tools">

                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                <thead>
                                <tr>
                                    <th>Bag ID</th>
                                    <th>Bag Name</th>
                                    <th>Price</th>
                                    <th>Description</th>
                                    <th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>
                                    <th class="textcenter"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <if condition="is_array($bag_list)">
                                    <volist name="bag_list" id="vo">
                                        <tr>
                                            <td>{pigcms{$vo.bag_id}</td>
                                            <td>{pigcms{$vo.bag_name}</td>
                                            <td>{pigcms{$vo.bag_price}</td>
                                            <td>{pigcms{$vo.phone}</td>
                                            <td class="textcenter">
                                                <if condition="$vo['reg_status'] neq 4 and $vo['reg_status'] neq 5">
                                                    <font color="red">{pigcms{:L('_BACK_REGISTERED_')}</font>
                                                </if>
                                                <if condition="$vo['reg_status'] eq 4">
                                                    <font color="green">
                                                        {pigcms{:L('_BACK_DELIVER_BOX_')}
                                                        <if condition="$vo['is_online_pay'] eq 1">
                                                            (Paid)
                                                            <else/>
                                                            (Unpaid)
                                                        </if>
                                                    </font>
                                                    |
                                                </if>

                                                <if condition="$vo['reg_status'] eq 4 or $vo['reg_status'] eq 5">
                                                    <if condition="$vo['is_upload'] eq 0">
                                                        <font color="red">{pigcms{:L('D_INCOMPLETE_REGIST')}</font>
                                                        <else/>
                                                        <if condition="$vo['group'] eq 0">
                                                            <font color="red">{pigcms{:L('D_AWAITING_APPROVAL')}</font>
                                                        </if>
                                                        <if condition="$vo['group'] eq -1">
                                                            <font color="red">未通过审核</font>
                                                        </if>
                                                        <if condition="$vo['group'] eq 1">
                                                            <font color="green">{pigcms{:L('D_APPROVED')}</font>
                                                        </if>
                                                    </if>
                                                </if>
                                            </td>
                                            <td class="textcenter">　
                                                <a href="javascript:void(0);"
                                                   onclick="window.top.artiframe('{pigcms{:U('Deliver/user_view',array('uid'=>$vo['uid']))}','{pigcms{:L(\'_BACK_EDIT_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);">
                                                    <button class="btn btn-white text-grey" type="button">
                                                        {pigcms{:L('_BACK_EDIT_')}
                                                    </button>
                                                </a>
                                            </td>
                                        </tr>
                                    </volist>
                                    <tr>
                                        <td class="textcenter pagebar" colspan="10">{pigcms{$pagebar}</td>
                                    </tr>
                                    <else/>
                                    <tr>
                                        <td class="textcenter red" colspan="10">{pigcms{:L('_BACK_EMPTY_')}</td>
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
        </div>
        <script>
            var city_id = $('#city_select').val();
            $('#city_select').change(function () {
                // city_id = $(this).val();
                // window.location.href = "{pigcms{:U('Deliver/review')}" + "&city_id="+city_id;
            });
        </script>
        <include file="Public:footer"/>