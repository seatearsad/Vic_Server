<include file="Public:header"/><div id="wrapper">    <include file="Public:left_menu"/>    <!----------------------------------------    以上不要写代码     ------------------------------------------------>    <div class="row wrapper border-bottom white-bg page-heading">        <div class="col-lg-9">            <h2>{pigcms{:L('_BACK_MER_LIST_')}</h2>            <ol class="breadcrumb">                <li class="breadcrumb-item">                    <a href="{pigcms{:U('Index/index')}">Home</a>                </li>                <!--                <li class="breadcrumb-item">-->                <!--                    <a>UI Elements</a>-->                <!--                </li>-->                <li class="breadcrumb-item active">                    <strong>{pigcms{:L('_BACK_MER_LIST_')}</strong>                </li>            </ol>        </div>        <div class="col-lg-3" style="height 90px;margin-top:40px;"><!--            <div class="btn-group">--><!--                <button class="btn btn-white active">Today</button>--><!--                <button class="btn btn-white  ">Monthly</button>--><!--                <button class="btn btn-white">Annual</button>--><!--            </div>-->            <if condition="$system_session['level'] neq 3">                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/add')}','{pigcms{:L(\'E_CREATE_MERCHANT\')}',800,560,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('E_CREATE_MERCHANT')}</button></a>            </if>        </div>    </div>    <div class="wrapper wrapper-content animated fadeInRight">        <div class="row">            <div class="col-lg-12">                <div class="ibox ">                    <div class="ibox-title tutti_hidden_obj">                        <h5>{pigcms{:L('_BACK_MER_LIST_')}</h5>                        <div class="ibox-tools">                            <if condition="$system_session['level'] neq 3">                                <span style="margin-left:40px">                                    <b>{pigcms{:L('_BACK_TOTAL_MER_BA_')}：{pigcms{$all_money}</b>                                </span>                            </if>                        </div>                    </div>                    <div class="ibox-content">                        <!-------------------------------- 工具条 -------------------------------------->                        <div style="height: 50px;">                            <form action="{pigcms{:U('Merchant/index')}" class="form-inline" role="form"                                  method="get">                                <div id="tool_bar" style="form-group tutti_toolbar" style="height: 80px;">                                    <if condition="$system_session['level'] neq 3">                                        City:                                        <select name="city_id" id="city_id" class="form-control">                                            <option value="0"                                            <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>                                            >All</option>                                            <volist name="city" id="vo">                                                <option value="{pigcms{$vo.area_id}"                                                <if condition="$city_id eq $vo['area_id']">selected="selected"</if>                                                >{pigcms{$vo.area_name}</option>                                            </volist>                                        </select>                                    </if>                                    <input type="hidden" name="c" value="Merchant"/>                                    <input type="hidden" name="a" value="index"/>                                    &nbsp;{pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword"                                                                               value="{pigcms{$_GET['keyword']}"                                                                               class="form-control"/>                                    <select name="searchtype" class="form-control">                                        <option value="name"                                        <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>                                        >{pigcms{:L('_BACK_MER_NAME_')}</option>                                        <option value="phone"                                        <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>                                        >{pigcms{:L('_BACK_MER_PHONE_')}</option>                                        <option value="mer_id"                                        <if condition="$_GET['searchtype'] eq 'mer_id'">selected="selected"</if>                                        >{pigcms{:L('_BACK_MER_ID_')}</option>                                    </select>                                    &nbsp;{pigcms{:L('_BACK_MER_STATUS_')}: <select name="searchstatus"                                                                                    class="form-control">                                        <option value="0"                                        <if condition="$_GET['searchstatus'] eq 0">selected="selected"</if>                                        >{pigcms{:L('_BACK_NORMAL_')}</option>                                        <option value="1"                                        <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>                                        >{pigcms{:L('_BACK_PENDING_')}</option>                                        <option value="2"                                        <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>                                        >{pigcms{:L('_BACK_CLOSED_')}</option>                                        <option value="3"                                        <if condition="$_GET['searchstatus'] eq '3'">selected="selected"</if>                                        >{pigcms{:L('_BACK_ALL_')}</option>                                    </select><!--                                    &nbsp;{pigcms{:L('_BACK_SORT_ORDER_')}: <select name="searchorder"--><!--                                                                                    class="form-control">--><!--                                        <option value="0"--><!--                                        <if condition="$_GET['searchorder'] eq 0">selected="selected"</if>--><!--                                        >{pigcms{:L('_BACK_CODE_')}</option>--><!--                                        <option value="1"--><!--                                        <if condition="$_GET['searchorder'] eq '1'">selected="selected"</if>--><!--                                        >{pigcms{:L('_BACK_MER_BALANCE_')}</option>--><!----><!--                                    </select>-->                                    &nbsp;<input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}"                                                 class="form-control"/>                                </div>                            </form>                        </div>                        <!------------------------------------------------------------------------------>                        <!-- <form name="myform" id="myform" action="" method="post">-->                        <table class="footable table table-stripped toggle-arrow-tiny" data-page-size="20" data-sorting="false">                            <thead>                            <tr>                                <th data-sort-ignore="true">{pigcms{:L('_BACK_CODE_')}</th>                                <th data-sort-ignore="true">{pigcms{:L('_BACK_MER_NAME_')}</th>                                <th data-sort-ignore="true">{pigcms{:L('_BACK_MER_PHONE_')}</th>                                <th data-sort-ignore="true">{pigcms{:L('G_CITY')}</th>                                <th data-sort-ignore="true">{pigcms{:L('_BACK_LAST_TIME_')}</th>                                <th data-sort-ignore="true">{pigcms{:L('_BACK_VISIT_')}</th>                                <th data-sort-ignore="true">{pigcms{:L('C_CATEGORYNUM')}</th>                                <th data-sort-ignore="true">{pigcms{:L('_BACK_STATUS_')}</th>                                <if condition="C('config.open_extra_price') eq 1">                                    <th data-sort-ignore="true">{pigcms{:C('config.extra_price_alias_name')}</th>                                </if>                                <th data-hide="all">包含店铺</th>                                <th data-sort-ignore="true">{pigcms{:L('_BACK_CZ_')}</th>                            </tr>                            </thead>                            <tbody>                            <if condition="is_array($merchant_list)">                                <volist name="merchant_list" id="vo">                                    <tr>                                        <td>{pigcms{$vo.mer_id}</td>                                        <td>{pigcms{$vo.name}</td>                                        <td>{pigcms{$vo.phone}</td>                                        <td>{pigcms{$vo.area_name}</td>                                        <td>                                            <if condition="$vo['last_time']">{pigcms{$vo.last_time|date='Y-m-d                                                H:i:s',###}                                                <else/>                                                N/A                                            </if>                                        </td>                                        <td class="textcenter">                                            <if condition="$vo['status'] eq 1 OR $vo['status'] eq 3"><a                                                        href="{pigcms{:U('Merchant/merchant_login',array('mer_id'=>$vo['mer_id']))}"                                                        class="__full_screen_link" target="_blank">{pigcms{:L('_BACK_VISIT_')}</a>                                                <else/>                                                <a href="javascript:alert('{pigcms{:L(\'K_SUSPENDED\')}');"                                                   class="__full_screen_link">访问</a></if>                                        </td>                                        <!--td class="textcenter">{pigcms{$vo.hits}</td>                                        <td class="textcenter">{pigcms{$vo.fans_count}</td-->                                        <td>{pigcms{$vo.store_count}</td>                                        <td class="td_v_middle">                                            <if condition="$vo['status'] eq 1">                                                <span class="label label-primary">{pigcms{:L('_BACK_ACTIVE_')}</span>                                             <elseif condition="$vo['status'] eq 2"/>                                                <span class="label label-warning"> {pigcms{:L('_BACK_PENDING_')}</span>                                             <elseif condition="$vo['status'] eq 3"/>                                                <span class="label label-danger">欠款</span>                                             <else/>                                                <span class="label label-default">Closed</span>                                            </if>                                        </td>                                        <if condition="C('config.open_extra_price') eq 1">                                            <td>                                                商家欠平台{pigcms{$vo.extra_price_pay_for_system}个{pigcms{:C('config.extra_price_alias_name')},即{pigcms{:sprintf("%.2f",$vo['extra_price_pay_for_system']*$vo['extra_price_percent']/100)}元                                            </td>                                        </if>                                        <!--td class="textcenter"><a href="{pigcms{:U('Merchant/weidian_order',array('mer_id'=>$vo['mer_id']))}">微店账单</a></td-->                                        <td  >                                            <if condition="is_array($vo['store_list'])">                                                <volist name="vo['store_list']" id="ao">                                                    <div>                                                        <span style="line-height: 2">{pigcms{$ao.store_id} - </span>                                                        <span style="line-height: 2">{pigcms{$ao.name}</span>                                                        <span style="line-height: 2">&nbsp;&nbsp;&nbsp;&nbsp;                                                            <div class="btn-group">                                                                <div class="float-right">                                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$ao['store_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_VIEW_\')}',620,480,true,false,false,false,'detail',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_VIEW_')}</button></a>                                                                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$ao['store_id']))}','{pigcms{:L(\'_BACK_EDIT_STORE_INFO_\')}',620,480,true,false,false,editbtn,'store_add',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_EDIT_')}</button></a>                                                                </div>                                                             </div>                                                        </span>                                                    </div>                                                </volist>                                             <else/>                                                <div>None</div>                                            </if>                                        </td>                                        <td >                                            <div class="btn-group"><!--                                                <a target="_blank" href="{pigcms{:U('Merchant/store',array('mer_id'=>$vo['mer_id']))}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_STORE_LIST_')}</button></a>-->                                                <div class="float-right">                                                    <a href="javascript:void(0);"onclick="window.artiframe('{pigcms{:U('Merchant/edit',array('mer_id'=>$vo['mer_id']))}','{pigcms{:L(\'_BACK_EDIT_MER_INFO_\')}',800,560,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_EDIT_')}</button></a>                                                    <a href="javascript:void(0);" class="delete_row" parameter="mer_id={pigcms{$vo.mer_id}" url="{pigcms{:U('Merchant/del')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_DEL_')}</button></a>                                                </div>                                            </div>                                        </td>                                    </tr>                                </volist>                             <else/>                                <tr>                                    <td <if condition="$system_session['level'] neq 3">colspan="9" <else/> colspan="22"</if>>                                    {pigcms{:L('_BACK_EMPTY_')}                                    </td>                                </tr>                             </if>                            </tbody>                            <tfoot>                            <tr>                            </tr>                            </tfoot>                        </table>                        <div id="table_pagebar" style="height: 30px;">                        </div>                        <!--                            </form>-->                    </div>                </div>            </div>        </div>    </div>    <!-- Page-Level Scripts -->    <script>        var pagestr='{pigcms{$pagebar}';        let pagediv= $('#table_pagebar');        $(document).ready(function() {            $('.footable').footable({                "columns": {                    "sortable": false                },"sorting": {                    "enabled": false                }            });            // $('.footable').footable({            //     "columns": {            //         "sortable": false            //     },{            //         ...            //     }            // });            pagediv.html(pagestr);            $("#ulpage").bind('DOMNodeInserted', function(e) {                pagediv.html(pagestr);                // alert('element now contains: ' + $(e.target).html());            });        });    </script>    <!----------------------------------------    以下不要写代码     ------------------------------------------------><include file="Public:footer"/>