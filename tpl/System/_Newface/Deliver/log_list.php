<include file="Public:header"/>
<div id="wrapper">

    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-8">
            <h2>{pigcms{:L('_BACK_HISTORY_DELI_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->

                <li class="breadcrumb-item">
                    <a href="{pigcms{:U('Deliver/user')}" style="text-decoration:underline;">{pigcms{:L('_BACK_COURIER_MANA_')}</a>
                </li>
                <li class="breadcrumb-item">
                    <strong>【{pigcms{$user['name']}】{pigcms{:L('_BACK_HISTORY_DELI_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-4 float-right" style="height 90px;margin-top:40px;">
            <div class="btn-group">
            </div>
        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
<!--                    <div class="ibox-title">-->
<!--                        <h5>{pigcms{:L('_BACK_COURIER_OVER_')}</h5>-->
<!--                        <div class="ibox-tools">-->
<!--                            <if condition="$system_session['level'] neq 3">-->
<!--                                <div style="margin-left:40px;">-->
<!--                                </div>-->
<!--                            </if>-->
<!--                        </div>-->
<!--                    </div>-->
                    <div class="ibox-content">
                        <!-------------------------------- 工具条 -------------------------------------->

                        <!------------------------------------------------------------------------------>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                                    <tr>
                                        <td>
                                            <form action="{pigcms{:U('Deliver/log_list')}" method="get">
                                                <input type="hidden" name="c" value="Deliver"/>
                                                <input type="hidden" name="a" value="log_list"/>
                                                <input type="hidden" name="uid" value="{pigcms{$user['uid']}"/>
                                                <!--input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>用户ID</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>昵称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>手机号</option>
							</select-->
                                                <div class="row">
                                                    <div class="form-group col-lg-6" id="data_5">
                                                        <div class="input-daterange input-group" id="datepicker">
                                                            <input type="text" class="form-control-sm form-control" name="begin_time" autocomplete="off" value="{pigcms{$begin_time}">
                                                            <!--span class="input-group-addon">to value="{pigcms{:date('m/d/Y')}" </span-->
                                                            &nbsp;to &nbsp;
                                                            <input type="text" class="form-control-sm form-control" name="end_time" autocomplete="off" value="{pigcms{$end_time}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group col-lg-2">
                                                        <div class="input-daterange input-group" id="datepicker">
                                                            <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="form-control form-control-sm" />
                                                        </div>
                                                    </div>
                                                </div>
<!--                                                <a href="{pigcms{:U('Deliver/export_user', array('begin_time' => $begin_time, 'end_time' => $end_time, 'uid' => $user['uid']))}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('_BACK_DOWN_ORDER_')}</a>-->
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                                <form name="myform" id="myform" action="" method="post">
                                    <div class="table-list">
                                        <table class="table table-striped table-bordered table-hover dataTables-example">
                                            <thead>
                                            <tr>
                                                <!-- 								<th>订单ID</th> -->
                                                <th>{pigcms{:L('_C_ORDER_SOURCE_')}</th>
                                                <!--th>配送员类型</th-->
                                                <th>{pigcms{:L('_BACK_STORE_NAME_')}</th>
                                                <th>{pigcms{:L('_BACK_USER_NAME_')}</th>
                                                <th>{pigcms{:L('_BACK_USER_PHONE_')}</th>
                                                <th>{pigcms{:L('_BACK_CUSTOM_ADD_')}</th>
                                                <!--th>支付方式</th-->
                                                <th>{pigcms{:L('_BACK_PAYMENT_STATUS_')}</th>
                                                <th>{pigcms{:L('_BACK_ORDER_TOTAL_')}</th>
                                                <th>{pigcms{:L('_BACK_DELIVERY_STATUS_')}</th>
                                                <th>{pigcms{:L('_BACK_START_AT_')}</th>
                                                <th>{pigcms{:L('_BACK_FINISH_AT_')}</th>
                                                <th>{pigcms{:L('_BACK_CASH_RECE_')}</th>
                                                <!--th>创建时间</th-->
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <if condition="is_array($supply_info)">
                                                <volist name="supply_info"  id="vo">
                                                    <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <!-- 										<td width="30">{pigcms{$vo.order_id}</td> -->
                                            <td><if condition="$vo['item'] eq 0">{pigcms{$config.meal_alias_name}<elseif condition="$vo['item'] eq 1" />外送系统<elseif condition="$vo['item'] eq 2" />{pigcms{:L('_BACK_DELIVERY_')}</if></td>
                                            <!--td width="50">{pigcms{$vo.group}</td-->
                                            <td>{pigcms{$vo.storename}</td>
                                            <td>{pigcms{$vo.username}</td>
                                            <td>{pigcms{$vo.userphone}</td>
                                            <td>{pigcms{$vo.aim_site}</td>
                                            <!--td width="50">{pigcms{$vo.pay_type}</td-->
                                            <td>{pigcms{$vo.paid}</td>
                                            <td>{pigcms{$vo.money|floatval}</td>
                                            <td>{pigcms{$vo.order_status}</td>
                                            <td>{pigcms{$vo.start_time}</td>
                                            <td>{pigcms{$vo.end_time}</td>
                                            <td style="color:red">{pigcms{$vo.deliver_cash|floatval}</td>
                                            <!-- 										<td width="80">{pigcms{$vo.end_time}</td> -->
                                            <!--td width="50">{pigcms{$vo.create_time}</td-->
                                            </tr>
                                            </volist>
                                            <else/>
                                            <tr><td class="textcenter red" colspan="16">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
                                            </if>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                                <div style="height: 30px;">
                                    {pigcms{$pagebar}
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link href="{pigcms{$static_path}css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <script src="{pigcms{$static_path}js/plugins/datapicker/bootstrap-datepicker.js"></script>
    <script>
        $('#data_5 .input-daterange,#data_6 .input-daterange').datepicker({
            keyboardNavigation: false,
            forceParse: false,
            autoclose: true
        });
        var selectStoreId = {pigcms{:$selectStoreId? $selectStoreId: 0};
        var selectUserId = {pigcms{:$selectUserId? $selectUserId: 0};
        $(function(){
            $("#store").change(function(){
                selectStoreId = $("#store").val();
                selectUserId = 0;
                search();
            });
            $("#deliver").change(function(){
                selectStoreId = 0;
                selectUserId = $("#deliver").val();
                search();
            });
            $("#order_number").focus(function(){
                $("#phone").val("");
            });
            $("#phone").focus(function(){
                $("#order_number").val("");
            });
            $("#search").click(function(){
                var orderNum = $("#order_number").val();
                var phone = $("#phone").val();
                search(orderNum, phone)
            });
            function search(orderNum, phone) {
                var orderNum =  orderNum || 0;
                var phone = phone || 0;
                location.href = "{pigcms{:U('Merchant/Deliver/deliverList')}"+"&orderNum="+orderNum+"&phone="+phone+"&selectStoreId="+selectStoreId+"&selectUserId="+selectUserId;
            }
        });
    </script>
<include file="Public:footer"/>