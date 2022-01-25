<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6">
            <h2>{pigcms{:L('_BACK_COURIER_MANA_')}</h2>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    {pigcms{:L('_BACK_DLVMNG_')}
                </li>
                <!--                <li class="breadcrumb-item">-->
                <!--                    <a>UI Elements</a>-->
                <!--                </li>-->
                <li class="breadcrumb-item active">
                    <strong>{pigcms{:L('_BACK_COURIER_MANA_')}</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-6 " style="height 90px;margin-top:40px;">
            <div class="btn-group float-right">
                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_add')}','{pigcms{:L(\'_BACK_ADD_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);" style="float:right;margin-left: 10px;"><button class="btn btn-primary">{pigcms{:L('_BACK_ADD_COURIER_')}</button></a>
            </div>
        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title tutti_hidden_obj">
                        <h5>{pigcms{:L('_BACK_ORDER_LIST_')}</h5>
                        <div class="ibox-tools">
                            <if condition="$system_session['level'] neq 3">
                                <div style="margin-left:40px;">
                                    <a href="{pigcms{:U('Deliver/export_deliver')}" style="float:right;margin-right: 10px;color:blue">
                                       {pigcms{:L('D_EXPORT_COURIERLIST')}
                                    </a>
                                </div>
                            </if>
                        </div>
                    </div>
                    <div class="ibox-content">
                        <div class="table-responsive">
                        <!-------------------------------- 工具条 -------------------------------------->
                        <div style="margin-bottom: 10px;min-height: 50px">
                                <div id="tool_bar" style="form-group " style="height: 80px;">
                                    <form action="{pigcms{:U('Deliver/user')}" class="form-inline" method="get">
                                        <input type="hidden" name="c" value="Deliver"/>
                                        <input type="hidden" name="a" value="user"/>
                                        {pigcms{:L('_BACK_SEARCH_')}:&nbsp;&nbsp; <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>&nbsp;&nbsp;
                                        <select name="searchtype" class="form-control">
                                            <option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>{pigcms{:L('_BACK_USER_ID_')}</option>
                                            <option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>{pigcms{:L('NAME_BKADMIN')}</option>
                                            <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_PHONE_NUM_')}</option>
                                            <option value="mail" <if condition="$_GET['searchtype'] eq 'mail'">selected="selected"</if>>{pigcms{:L('_EMAIL_TXT_')}</option>
                                        </select>
                                        <if condition="$system_session['level'] neq 3">
                                            &nbsp;&nbsp;City:&nbsp;&nbsp;
                                            <select name="city_id" id="city_id" class="form-control" >
                                                <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>
                                                <volist name="city" id="vo">
                                                    <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                                                </volist>
                                            </select>
                                        </if>&nbsp;&nbsp;
                                        <input type="submit" class="form-control" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>
                                    </form>


                                </div>
                        </div>
                        <!------------------------------------------------------------------------------>
                        <!-- <form name="myform" id="myform" action="" method="post">-->
                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="20" data-sorting="false">
                            <thead>
                            <tr>
                                <!--                                ID，姓名（名+姓），电话，邮箱，配送城市，配送范围，配送总量，状态，历史记录统计，配送记录，编辑-->
                                <!--                                展开信息：地址，备注，最后修改时间-->
                                <th data-sort-ignore="true">ID</th>
                                <th data-sort-ignore="true">{pigcms{:L('NAME_BKADMIN')}</th>
                                <th data-sort-ignore="true">{pigcms{:L('_BACK_PHONE_NUM_')}</th>
                                <th data-sort-ignore="true">Email</th>
                                <th data-sort-ignore="true">City</th>
                                <th data-sort-ignore="true">Vehicle Type</th>
                                <th data-sort-ignore="true">{pigcms{:L('_BACK_DELIVERY_AREA_')}</th>
                                <th data-sort-ignore="true">{pigcms{:L('_BACK_TOTAL_DELIVERY_')}</th>
                                <th data-sort-ignore="true">{pigcms{:L('_BACK_STATUS_')}</th>
                                <th data-hide="all">{pigcms{:L('_BACK_DEFAULT_ADD_')}</th>
                                <th data-hide="all">{pigcms{:L('_NOTE_TXT_')}</th>
                                <th data-hide="all">{pigcms{:L('_BACK_LAST_EDIT_TIME_')}</th>
                                <th data-sort-ignore="true">{pigcms{:L('_BACK_OVERVIEW_')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="is_array($user_list)">
                                <volist name="user_list" id="vo">
                                    <tr>
                                        <td>{pigcms{$vo.uid}</td>
                                        <td>{pigcms{$vo.name}&nbsp;{pigcms{$vo.family_name}</td>
                                        <td>{pigcms{$vo.phone}</td>
                                        <td>{pigcms{$vo.email}</td>
                                        <td>{pigcms{$vo.area_name}</td>
                                        <td>{pigcms{$vo.vehicle_name}</td>
                                        <td>{pigcms{$vo.range}</td>
                                        <td class="textcenter">{pigcms{$vo.num}</td>
                                        <td class="textcenter td_v_middle">
                                            <if condition="$vo['expiry'] eq 1">
                                                <font color="red">Expired</font>
                                            </if>
                                            <if condition="$vo['expiry'] eq 0 and $vo['status'] eq 1">
                                                <font color="green">{pigcms{:L('_BACK_NORMAL_')}</font>
                                            </if>
                                            <if condition="$vo['expiry'] eq 0 and $vo['status'] eq 0">
                                                <font color="red">{pigcms{:L('_BACK_BANNED_')}</font>
                                            </if>
                                        </td>
                                        <td>{pigcms{$vo.site}</td>
                                        <td >{pigcms{$vo.remark}</td>
                                        <td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
                                        <td class="textcenter">
                                            <div class="btn-group">
                                                <div class="float-right">
                                                    <a style="width: 60px;" class="" href="{pigcms{:U('Deliver/count_log',array('uid'=>$vo['uid']))}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_HISTORY_RECORD_')}</button></a> 　
                                                    <a style="width: 60px;" class="" href="{pigcms{:U('Deliver/log_list',array('uid'=>$vo['uid']))}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_HISTORY_DELI_')}</button></a>
                                                    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_edit',array('uid'=>$vo['uid']))}','{pigcms{:L(\'_BACK_EDIT_COURIER_\')}',680,560,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_EDIT_')}</button></a>
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
    </div>

<script>
    var city_id = $('#city_select').val();
    $('#city_select').change(function () {
        city_id = $(this).val();
        window.location.href = "{pigcms{:U('Deliver/user')}" + "&city_id="+city_id;
    });
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
        $("#ulpage").bind('DOMNodeInserted', function(e) {
            pagediv.html(pagestr);
            // alert('element now contains: ' + $(e.target).html());
        });
    });
</script>
<include file="Public:footer"/>