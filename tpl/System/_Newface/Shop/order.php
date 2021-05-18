<include file="Public:header"/>
<div id="wrapper">
    <include file="Public:left_menu"/>
    <!----------------------------------------    以上不要写代码     ------------------------------------------------>
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-9">
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
        <div class="col-lg-3" style="height 90px;margin-top:40px;">
            <!--            <div class="btn-group">-->
            <!--                <button class="btn btn-white active">Today</button>-->
            <!--                <button class="btn btn-white  ">Monthly</button>-->
            <!--                <button class="btn btn-white">Annual</button>-->
            <!--            </div>-->
            <if condition="$system_session['level'] neq 3">
                <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/add')}','{pigcms{:L(\'E_CREATE_MERCHANT\')}',800,560,true,false,false,addbtn,'add',true);"><button type="button" class="btn btn-primary btn-sm float-right">{pigcms{:L('E_CREATE_MERCHANT')}</button></a>
            </if>

        </div>
    </div>

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox ">
                    <div class="ibox-title">
                        <h5>{pigcms{:L('_BACK_ORDER_LIST_')}</h5>
                        <div class="ibox-tools">
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
                        <div class="ibox-content">
                            <!-------------------------------- 工具条 -------------------------------------->
                            <div style="height: 50px;">
                                <form action="{pigcms{:U('Merchant/index')}" class="form-inline" role="form"
                                      method="get">
                                    <div id="tool_bar" style="form-group tutti_toolbar" style="height: 80px;">

                                        <form action="{pigcms{:U('Shop/order')}" method="get">
                                            <input type="hidden" name="c" value="Shop"/>
                                            <input type="hidden" name="a" value="order"/>
                                            {pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="form-control" value="{pigcms{$_GET['keyword']}"/>
                                            <select name="searchtype" class="form-control">
                                                <option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>{pigcms{:L('_BACK_ORDER_NUM_')}</option>
                                                <!--option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
                                                <option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option-->
                                                <option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{:L('_BACK_STORE_NAME_')}</option>
                                                <option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('_BACK_USER_NAME_')}</option>
                                                <option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_USER_PHONE_')}</option>
                                                <option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>User ID</option>
                                            </select>
                                            <font color="#000">{pigcms{:L('_BACK_DATE_SELECT_')}：</font>
                                            <input type="text" class="form-control" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
                                            <input type="text" class="form-control" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
                                            {pigcms{:L('_BACK_ORDER_STATUS_')}:
                                            <select id="status" name="status" class="form-control">
                                                <volist name="status_list" id="vo">
                                                    <option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
                                                </volist>
                                            </select>
                                            {pigcms{:L('_BACK_PAYMENT_METHOD_')}:
                                            <select id="pay_type" name="pay_type" class="form-control" >
                                                <option value="" <if condition="'' eq $pay_type">selected="selected"</if>>{pigcms{:L('_BACK_ALL_')}</option>
                                                <volist name="pay_method" id="vo">
                                                    <option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
                                                </volist>
                                                <option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>{pigcms{:L('_BACK_BALANCE_')}</option>
                                            </select>
                                            <input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="form-control"/>　
                                        </form>
                                    </div>
                                </form>
                            </div>
                            <!------------------------------------------------------------------------------>
                            <!-- <form name="myform" id="myform" action="" method="post">-->
                            <table class="footable table table-stripped toggle-arrow-tiny" data-sorting="false">
                                <thead>
                                <tr>
                                    <th >{pigcms{:L('_BACK_CODE_')}</th>
                                    <th data-sortable="false">{pigcms{:L('_BACK_MER_NAME_')}</th>
                                    <th data-sortable="false">{pigcms{:L('_BACK_MER_PHONE_')}</th>
                                    <th data-sortable="false">{pigcms{:L('_BACK_LAST_TIME_')}</th>
                                    <th data-sortable="false">{pigcms{:L('_BACK_VISIT_')}</th>
                                    <th data-sortable="false">{pigcms{:L('_BACK_ACC_BALANCE_')}</th>
                                    <th data-sortable="false">{pigcms{:L('_BACK_STATUS_')}</th>
                                    <if condition="C('config.open_extra_price') eq 1">
                                        <th data-sortable="false">{pigcms{:C('config.extra_price_alias_name')}</th>
                                    </if>
                                    <if condition="$system_session['level'] neq 3">
                                        <th data-sortable="false">{pigcms{:L('_BACK_MER_INVOICE_')}</th>
                                    </if>
                                    <th data-hide="all">包含店铺</th>
                                    <th data-sortable="false">{pigcms{:L('_BACK_CZ_')}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <if condition="is_array($merchant_list)">
                                    <volist name="merchant_list" id="vo">
                                        <tr>
                                            <td>{pigcms{$vo.mer_id}</td>
                                            <td>{pigcms{$vo.name}</td>
                                            <td>{pigcms{$vo.phone}</td>
                                            <td>
                                                <if condition="$vo['last_time']">{pigcms{$vo.last_time|date='Y-m-d
                                                    H:i:s',###}
                                                    <else/>
                                                    N/A
                                                </if>
                                            </td>
                                            <td class="textcenter">
                                                <if condition="$vo['status'] eq 1 OR $vo['status'] eq 3"><a
                                                            href="{pigcms{:U('Merchant/merchant_login',array('mer_id'=>$vo['mer_id']))}"
                                                            class="__full_screen_link" target="_blank">{pigcms{:L('_BACK_VISIT_')}</a>
                                                    <else/>
                                                    <a href="javascript:alert('{pigcms{:L(\'K_SUSPENDED\')}');"
                                                       class="__full_screen_link">访问</a></if>
                                            </td>
                                            <!--td class="textcenter">{pigcms{$vo.hits}</td>
                                            <td class="textcenter">{pigcms{$vo.fans_count}</td-->
                                            <td class="textcenter">{pigcms{$vo.money}</td>
                                            <td>
                                                <if condition="$vo['status'] eq 1">
                                                    <span class="label label-primary">{pigcms{:L('_BACK_ACTIVE_')}</span>
                                                    <elseif condition="$vo['status'] eq 2"/>
                                                    <span class="label label-warning"> {pigcms{:L('_BACK_PENDING_')}</span>
                                                    <elseif condition="$vo['status'] eq 3"/>
                                                    <span class="label label-danger">欠款</span>
                                                    <else/>
                                                    <span class="label label-default">Closed</span>
                                                </if>
                                            </td>
                                            <if condition="C('config.open_extra_price') eq 1">
                                                <td>
                                                    商家欠平台{pigcms{$vo.extra_price_pay_for_system}个{pigcms{:C('config.extra_price_alias_name')},即{pigcms{:sprintf("%.2f",$vo['extra_price_pay_for_system']*$vo['extra_price_percent']/100)}元
                                                </td>
                                            </if>
                                            <if condition="$system_session['level'] neq 3">
                                                <td class="textcenter"><a target="_blank"
                                                                          href="{pigcms{:U('Bill/merchant_money_list',array('mer_id'=>$vo['mer_id']))}">{pigcms{:L('_BACK_INVOICE_')}</a>
                                                </td>
                                            </if>
                                            <!--td class="textcenter"><a href="{pigcms{:U('Merchant/weidian_order',array('mer_id'=>$vo['mer_id']))}">微店账单</a></td-->
                                            <td>
                                                <volist name="vo['store_list']" id="ao">
                                                    <div><span>{pigcms{$ao.store_id} - </span><span>{pigcms{$ao.name}</span><span>{pigcms{$ao.name}</span><span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$ao['store_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_VIEW_\')}',620,480,true,false,false,false,'detail',true);">{pigcms{:L('_BACK_VIEW_')}</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/store_edit',array('store_id'=>$ao['store_id']))}','{pigcms{:L(\'_BACK_EDIT_STORE_INFO_\')}',620,480,true,false,false,editbtn,'store_add',true);">{pigcms{:L('_BACK_EDIT_')}</a> | <a href="javascript:void(0);" class="delete_row" parameter="store_id={pigcms{$ao.store_id}" url="{pigcms{:U('Merchant/store_del')}">{pigcms{:L('_BACK_DEL_')}</a></span></div>
                                                </volist>
                                            </td>
                                            <td >
                                                <div class="btn-group">
                                                    <!--                                                <a target="_blank" href="{pigcms{:U('Merchant/store',array('mer_id'=>$vo['mer_id']))}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_STORE_LIST_')}</button></a>-->
                                                    <div class="float-right">
                                                        <a href="javascript:void(0);"
                                                           onclick="window.artiframe('{pigcms{:U('Merchant/edit',array('mer_id'=>$vo['mer_id']))}','{pigcms{:L(\'_BACK_EDIT_MER_INFO_\')}',800,560,true,false,false,editbtn,'edit',true);"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_EDIT_')}</button></a>
                                                        <a href="javascript:void(0);" class="delete_row"
                                                           parameter="mer_id={pigcms{$vo.mer_id}"
                                                           url="{pigcms{:U('Merchant/del')}"><button class="btn btn-white text-grey" type="button">{pigcms{:L('_BACK_DEL_')}</button></a>
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
                                        >{pigcms{:L('_BACK_EMPTY_')}</td></tr>
                                </if>
                                </tbody>
                                <tfoot>
                                <tr>

                                </tr>
                                </tfoot>
                            </table>
                            <div style="height: 30px;">
                                {pigcms{$pagebar}
                            </div>
                            <!--                            </form>-->


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

	<div class="mainbox">

		<table class="search_table" width="100%">
			<tr>
				<td>
				<form action="{pigcms{:U('Shop/order')}" method="get">
						<input type="hidden" name="c" value="Shop"/>
						<input type="hidden" name="a" value="order"/>
						{pigcms{:L('_BACK_SEARCH_')}: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
						<select name="searchtype">
							<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>{pigcms{:L('_BACK_ORDER_NUM_')}</option>
							<!--option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
							<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option-->
							<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{:L('_BACK_STORE_NAME_')}</option>
							<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>{pigcms{:L('_BACK_USER_NAME_')}</option>
							<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>{pigcms{:L('_BACK_USER_PHONE_')}</option>
                            <option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>User ID</option>
						</select>
						<font color="#000">{pigcms{:L('_BACK_DATE_SELECT_')}：</font>
						<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
						<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd',lang:'en'})"/>
						{pigcms{:L('_BACK_ORDER_STATUS_')}:
						<select id="status" name="status">
							<volist name="status_list" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
							</volist>
						</select>
						{pigcms{:L('_BACK_PAYMENT_METHOD_')}:
						<select id="pay_type" name="pay_type">
								<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>{pigcms{:L('_BACK_ALL_')}</option>
							<volist name="pay_method" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
								<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>{pigcms{:L('_BACK_BALANCE_')}</option>
						</select>
						<input type="submit" value="{pigcms{:L('_BACK_SEARCH_')}" class="button"/>　
					</form>
                    <if condition="$system_session['level'] neq 3">
                        City:
                        <select name="searchtype" id="city_select">
                            <option value="0" <if condition="$city_id eq '' or $city_id eq 0">selected="selected"</if>>All</option>
                            <volist name="city" id="vo">
                                <option value="{pigcms{$vo.area_id}" <if condition="$city_id eq $vo['area_id']">selected="selected"</if>>{pigcms{$vo.area_name}</option>
                            </volist>
                        </select>
                    </if>
				</td>
                <if condition="$system_session['level'] neq 3">
				<td>
					<b>{pigcms{:L('_BACK_A_RECE_')}：{pigcms{$total_price|floatval}</b>　
					<b>{pigcms{:L('_BACK_A_PAID_ON_')}：{pigcms{$online_price|floatval}</b>　
					<b>{pigcms{:L('_BACK_A_PAID_CASH_')}：{pigcms{$offline_price|floatval}</b>
				</td>
                </if>
				<td>
				<a href="{pigcms{:U('Shop/export',$_GET)}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('_BACK_DOWN_ORDER_')}</a>
                    <if condition="$system_session['level'] eq 2">
                        <a href="{pigcms{:U('Shop/export_total',$_GET)}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('C_OMZB')}</a>
                        <a href="{pigcms{:U('Shop/export_store',$_GET)}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('C_OMRESTRANK')}</a>
                        <a href="{pigcms{:U('Shop/export_user',$_GET)}" class="button" style="float:right;margin-right: 10px;">{pigcms{:L('C_OMUSERRANK')}</a>
                    </if>
				</td>
			</tr>

		</table>
		<form name="myform" id="myform" action="" method="post">
			<div class="table-list">
				<table width="100%" cellspacing="0">
					<colgroup>
						<col/>
						<col/>
						<col/>
						<col/>
						<col/>
						<col/>
					</colgroup>
					<thead>
						<tr>
							<th>{pigcms{:L('_BACK_ORDER_NUM_')}</th>
							<!--th>商家名称</th-->
							<th>{pigcms{:L('_BACK_STORE_NAME_')}</th>
							<th>{pigcms{:L('_BACK_STORE_PHONE_')}</th>
							<th>{pigcms{:L('_BACK_USER_NAME_')}</th>
							<th>{pigcms{:L('_BACK_USER_PHONE_')}</th>
                            <th>{pigcms{:L('_BACK_INIT_TOTAL_')}</th>
							<th>{pigcms{:L('_BACK_TOTAL_')}<i class="menu-icon fa fa-sort"></i></th>
                            <th>{pigcms{:L('_BACK_TIPS_')}</th>
							<th>{pigcms{:L('_BACK_TUTTI_DIS_')}</th>
							<th>{pigcms{:L('_BACK_MER_DIS_')}</th>
							<th>
							<if condition="$type eq 'price'">
								<if condition="$sort eq 'ASC'">
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}↓ </a>
								<elseif condition="$sort eq 'DESC'" />
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'ASC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}↑</a>
								<else />
									<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}<i class="menu-icon fa fa-sort"></i></a>
								</if>
							<else />
								<a href="{pigcms{:U('Shop/order', array('type' => 'price', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_AM_RECE_')}<i class="menu-icon fa fa-sort"></i></a>
							</if>
							</th>
							<th>{pigcms{:L('_BACK_TAX_')}<i class="menu-icon fa fa-sort"></i></th>
							<th>
							<if condition="$type eq 'pay_time'">
								<if condition="$sort eq 'ASC'">
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}↓</a>
								<elseif condition="$sort eq 'DESC'" />
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'ASC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}↑</a>
								<else />
									<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}</a>
								</if>
							<else />
								<a href="{pigcms{:U('Shop/order', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}" style="color:blue;">{pigcms{:L('_BACK_PAY_TIME_')}</a>
							</if>
							</th>
                            <th>{pigcms{:L('_BACK_PREP_TIME_')}</th>
							<th>{pigcms{:L('_BACK_ARR_TIME_')}</th>
							<th>{pigcms{:L('_BACK_ORDER_STATUS_')}</th>
							<th>{pigcms{:L('_BACK_PAY_STATUS_')}</th>
							<th class="textcenter">{pigcms{:L('_BACK_CZ_')}</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($order_list)">
							<volist name="order_list" id="vo">
								<tr class="order_line" data-id="{pigcms{$vo.order_id}">
									<td>{pigcms{$vo.real_orderid}</td>
									<!--td>{pigcms{$vo.merchant_name}</td-->
									<td>{pigcms{$vo.store_name}</td>
									<td>{pigcms{$vo.store_phone}</td>
									<td>{pigcms{$vo.username}</td>
									<td>{pigcms{$vo.userphone}</td>
                                    <td style="color: red">
                                        <php>if($vo['is_refund'] == 1){</php>
                                        ${pigcms{$vo['change_price'] + $vo['tip_charge']|floatval}
                                        <php>}</php>
                                    </td>
									<td>${pigcms{$vo['price'] + $vo['tip_charge']- $vo['coupon_price'] - $vo['delivery_discount'] - $vo['merchant_reduce']|floatval}</td>
                                    <td>${pigcms{$vo['tip_charge']|floatval}</td>
									<td>${pigcms{$vo['coupon_price'] + $vo['delivery_discount']|floatval}</td>
									<td>${pigcms{$vo.merchant_reduce|floatval}</td>
									<td>${pigcms{$vo.offline_price|floatval}</td>
									<td>${pigcms{$vo['duty_price']|floatval}</td>
									<td><if condition="$vo['pay_time']"> {pigcms{$vo['pay_time']|date="Y-m-d H:i:s",###}</if></td>
									<td>{pigcms{$vo.dining_time}</td>
                                    <td><if condition="$vo['use_time']">{pigcms{$vo['use_time']|date="Y-m-d H:i:s",###}</if></td>
                                    <td class="status">{pigcms{$vo.status_str}</td>
									<td><!-- {pigcms{$vo.pay_status} --><span style="color: green">{pigcms{$vo.pay_type_str}<br>({pigcms{$vo.pay_type})</span></td>
									<td class="textcenter">
										<if condition="$vo.status eq 0 AND $vo.paid eq 1">
                                            <a data-href="{pigcms{:U('Shop/refund_update',array('order_id'=>$vo['order_id']))}" class="refund">{pigcms{:L('_BACK_MANUAL_REFUND_')}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </if>
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','{pigcms{:L(\'_BACK_ORDER_DETAIL_\')}',920,520,true,false,false,false,'detail',true);">{pigcms{:L('_BACK_VIEW_')}</a>
                                        <php>if($vo['is_refund'] == 0){</php>
                                        <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Shop/edit_order',array('order_id'=>$vo['order_id']))}','{pigcms{:L(\'_BACK_EDIT_\')}',920,520,true,false,false,editbtn,'edit',true);">{pigcms{:L('_BACK_EDIT_')}</a>
                                        <php>}</php>
                                        <a href="{pigcms{:U('Shop/del',array('id'=>$vo['order_id']))}" onclick="return confirm('{pigcms{:L(\'_B_PURE_MY_84_\')}')" style="color: red">{pigcms{:L('_BACK_DEL_')}</a>
									</td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="18">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="18">{pigcms{:L('_BACK_EMPTY_')}</td></tr>
						</if>
					</tbody>
				</table>
			</div>
		</form>
	</div>
<!-- Page-Level Scripts -->
<script>
    $(document).ready(function() {

        $('.footable').footable({
            "columns": {
                "sortable": false
            },"sorting": {
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
    });

</script>

<script>

    var city_id = $('#city_select').val();
    $('#city_select').change(function () {
        city_id = $(this).val();
        window.location.href = "{pigcms{:U('Shop/order', $_GET)}" + "&city_id=" + city_id;
    });

    $(function () {
        $('#status').change(function () {
            location.href = "{pigcms{:U('Shop/order', array('type' => $type, 'sort' => $sort,'pay_type'=>$pay_type,'city_id'=>$city_id))}&status=" + $(this).val();
        });

        $('#pay_type').change(function () {
            location.href = "{pigcms{:U('Shop/order', array('type' => $type, 'sort' => $sort,'status'=>$status,'city_id'=>$city_id))}&pay_type=" + $(this).val();
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
