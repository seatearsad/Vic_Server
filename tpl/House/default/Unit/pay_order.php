<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('Unit/index')}">物业管理</a>
            </li>
            <li class="active">物业对帐</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('pay_order')}" method="get">
							<input type="hidden" name="c" value="Unit"/>
							<input type="hidden" name="a" value="pay_order"/>
							
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"  style="height:42px"/>
							<select name="searchtype"  style="height:42px">
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>联系电话</option>	
								<option value="order_name" <if condition="$_GET['searchtype'] eq 'order_name'">selected="selected"</if>>缴费项名称</option>								
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							状态: <select name="searchstatus" style="height:42px">
								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>已对账</option>
								<option value="0" <if condition="$_GET['searchstatus'] eq 0">selected="selected"</if>>未对账</option>
							</select>
							时间筛选：
							<input type="text" name="begin_time" class="input-text" value="{pigcms{$_GET['begin_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="开始时间"/>
							<input type="text" name="end_time" class="input-text" value="{pigcms{$_GET['end_time']}"  style="height:42px" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd'})" placeholder="结束时间"//>
							<button class="btn btn-success" type="submit">查询</button>
							<button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('pay_order')}'">重置</button>
							<button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('Library/owner_arrival')}'">现场缴费</button>
							<button class="btn btn-success" type="button" onclick="location.href='{pigcms{:U('export',$_GET)}'">EXCEL导出</button>
						</form>
					</td>
				</tr>
			</table>
			
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">缴费项</th>
                                    <th width="5%">已缴金额</th>
                                    <th width="5%">支付方式</th>
                                    <th width="8%">支付时间</th>
                                    <th width="5%">业主名</th>
                                    <th width="5%">联系方式</th>
                                    <th width="10%">住址</th>
                                    <th width="8%">编号</th>
									<th width="5%">物业服务周期</th>
									<th width="8%">赠送物业服务时间</th>
									<th width="5%">服务时间</th>
                                    <th width="5%">对账状态</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$order_list['order_list']">
                                    <volist name="order_list['order_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td>{pigcms{$vo.order_name}</td>
                                            <td>{pigcms{$vo.money}</td>
                                            <td>现金支付</td>
                                            <td>{pigcms{$vo.time|date='Y-m-d H:i:s',###}</td>
                                            <td>{pigcms{$vo.username}</td>
                                            <td>{pigcms{$vo.phone}</td>
                                            <td>{pigcms{$vo.address}</td>
                                            <td>{pigcms{$vo.usernum}</td>
											<td>{pigcms{$vo.property_month_num}个月</td>
											<if condition='!empty($vo["presented_property_month_num"]) AND ($vo["diy_type"] eq 0)'><td>{pigcms{$vo.presented_property_month_num}个月</td><elseif condition='$vo["diy_type"] eq 1' /><td>{pigcms{$vo.diy_content}</td><else /><td class="red">无</td></if>
											<td>{pigcms{$vo.property_time_str}</td>
                                            <td><if condition="$vo['is_pay_bill'] eq 0"><if condition="$vo.pay_type eq 1"><strong style="color: green">现金支付</strong><else /><strong style="color: red">未对账</strong></if><else /><strong style="color: green"><strong type="color:green">已对账</strong></if></td>
                                        </tr>
                                    </volist>
									<tr class="even">
										<td colspan="16">
											本页总金额：<strong style="color: green">{pigcms{$total}</strong>　本页已出账金额：<strong style="color: red">{pigcms{$finshtotal}</strong><br/> 
											总金额：<strong style="color: green">{pigcms{$order_list.totalMoney.totalMoney}</strong>　<br/>
										</td>
									</tr>
									<tr class="odd">
										<td colspan="16" id="show_count"></td>
									</tr>
									<tr><td class="textcenter pagebar" colspan="12">{pigcms{$order_list.pagebar}</td></tr>
                                <else />
                                    <tr class="odd"><td class="button-column" colspan="12" >没有任何数据。</td></tr>
								</if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<include file="Public:footer"/>
