<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-cloud"></i>
			<li class="active">微硬件</li>
			<li class="active"><a href="{pigcms{:U('Dizwifi/index')}">微信链接WIFI</a></li>
			<li class="active">店铺列表</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>编号</th>
									<th>店铺名称</th>
									<th>联系电话</th>
									<th>店铺地址</th>
									<th>店铺状态</th>
									<th class="button-column" style="width:100px;">同步到微信</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$store_list">
									<volist name="store_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.store_id}</td>
											<td>{pigcms{$vo.name}</td>
											<td>{pigcms{$vo.phone}</td>
											<td>{pigcms{$vo.area_name} - {pigcms{$vo.adress}</td>
											<td>
												<switch name="vo['status']">
													<case value="0">关闭</case>
													<case value="1">正常</case>
													<case value="2">审核中</case>
												</switch>
											</td>
											<td class="button-column" nowrap="nowrap">
												<if condition="$vo['status'] eq 1">
													<if condition="$vo['available_state'] eq 0">
														<a class="green syn" style="padding-right:8px;" data-store_id="{pigcms{$vo['store_id']}">
															<i class="ace-icon fa fa-repeat bigger-130"></i>
														</a>
													<elseif condition="$vo['available_state'] eq 2" />
														<a >审核中</a>
													<elseif condition="$vo['available_state'] eq 3" />
													<a class="green">审核通过</a>
													<else />
													<a class="red">审核驳回</a>
													</if>
													
												</if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >{pigcms{:L('NO_CONTENT_BKADMIN')}</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	
	var flag = false;
	$('.syn').click(function(){
		if (flag) return false;
		flag = true;
		var store_id = $(this).data('store_id'), obj = $(this);
		$.get("{pigcms{:U('Dizwifi/syn')}", {'store_id':store_id}, function(response){
			if (response.error_code) {
				bootbox.alert(response.msg);
			} else {
				obj.parents('.button-column').html('<a class="green">审核中</a>');
			}
			flag = false;
		},'json');
	});
});
</script>
<include file="Public:footer"/>
