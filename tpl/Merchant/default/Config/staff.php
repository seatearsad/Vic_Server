<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/store')}">{pigcms{:L('STORE_MANAGEMENT_BKADMIN')}</a>
			</li>
			<li class="active">【{pigcms{$now_store.name}】 {pigcms{:L('STAFF_LISTING_BKADMIN')}</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">	
							<li class="active">
								<a>{pigcms{:L('USER_MANAGEMENT_BKADMIN')}</a>
							</li>
						</ul>
					
						<div class="tab-content">
							<div class="tab-pane active">
								<button class="btn btn-success" onclick="CreateShop()">{pigcms{:L('ADD_STAFF_BKADMIN')}</button>　
								<a href="../wap.php?g=Wap&c=Storestaff&a=login" class="btn btn-success" target="_blank">{pigcms{:L('MERCHANT_LOGIN_BKADMIN')}</a>
								<div id="shopList" class="grid-view">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th width="100">{pigcms{:L('USERNAME_BKADMIN')}</th>
												<th width="100">{pigcms{:L('NAME_BKADMIN')}</th>
												<th width="100">{pigcms{:L('STAFF_TYPE_BKADMIN')}</th>
												<th width="100">{pigcms{:L('PHONE_NUMBER_BKADMIN')}</th>
												<th width="100">{pigcms{:L('TIME_ADDED_BKADMIN')}</th>
<!--												<th width="100">能否修改订单价格</th>-->
												<th width="80" class="button-column">{pigcms{:L('ACTION_BKADMIN')}</th>
											</tr>
										</thead>
										<tbody>
											<if condition="$staff_list">
												<volist name="staff_list" id="staff">
													<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
														<td>{pigcms{$staff.username}</td>
														<td>{pigcms{$staff.name}</td>
														<td>{pigcms{$staff_type[$staff['type']]}</td>
														<td>{pigcms{$staff.tel}</td>
														<td>{pigcms{$staff.time|date='Y-m-d H:i:s',###}</td>
<!--														<td><if condition="$staff['is_change']"><span style="color:green">能</span><else /><span style="color:red">不能</span></if></td>-->
														<td class="button-column">
															<a class="green" style="padding-right:8px;" href="{pigcms{:U('Config/staffSet', array('itemid'=>$staff['id'],'store_id'=>$now_store['store_id']))}" >
																<i class="ace-icon fa fa-pencil bigger-130"></i>
															</a>
															<a title="Delete" class="red" style="padding-right:8px;" href="{pigcms{:U('Config/staffDelete',array('itemid'=>$staff['id'],'store_id'=>$now_store['store_id']))}">
																<i class="ace-icon fa fa-trash-o bigger-130"></i>
															</a>
														</td>
													</tr>
												</volist>
											<else/>
												<tr class="odd"><td class="button-column" colspan="5" >{pigcms{:L('NO_CONTENT_BKADMIN')}</td></tr>
											</if>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	jQuery(document).on('click','#shopList a.red',function(){
        if(!confirm("{pigcms{:L('SURE_RECOVERABLE_BKADMIN')}")) return false;
	});
});
function CreateShop(){
	window.location.href = "{pigcms{:U('Config/staffSet', array('store_id' => $now_store['store_id']))}";
}
function drop_confirm(msg, url)
{
	if (confirm(msg)) {
		window.location.href = url;
	}
}
</script>
<include file="Public:footer"/>
