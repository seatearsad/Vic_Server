<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Merchant_money/index')}">购买系统服务</a></li>
			
		</ul>
		<a href="{pigcms{:U('Merchant_money/mer_recharge')}">充值</a>
	</div>
	<style>
	.mainnav_title{
		margin-top:20px;
	}
	.mainnav_title ul a {
		padding: 15px 20px;
	}
	ul, ol {
		margin-bottom: 15px;
	}
	.mainnav_title span{
		color:#7EBAEF;
	}
	.mainnav_title a.on div{
		color:#C1BEBE;
	}
	button{
		padding: 6px;
		background-color: rgba(255, 255, 255, 0);
		box-sizing: border-box;
		border-width: 1px;
		border-style: solid;
		border-color: rgba(121, 121, 121, 1);
		border-radius: 2px;
		-moz-box-shadow: none;
		-webkit-box-shadow: none;
		box-shadow: none;
		font-size: 14px;
		color: #666666;
		cursor: pointer;

	}
	.mainnav_title {line-height:40px;/* height:40px; */border-bottom:1px solid #eee;color:#31708f;}
	.mainnav_title a {color:#004499;margin:0 5px;padding:4px 7px;background:#d9edf7;}
	.mainnav_title a:hover ,.mainnav_title a.on{background:#498CD0;color:#fff;text-decoration: none;}
	</style>
      

	
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<div class="tabbable" >
								<div class="row">					
									<div class="col-xs-12">		
										<div class="grid-view">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													
													<tr>
														<th>编号</th>
														<th >权限分类</th>
														<th>权限子分类</th>
														<th>权限子子分类</th>
														<th>价格</th>
														<th >操作</th>
													</tr>
												</thead>
												<tbody>
													<if condition="is_array($menus)">
														<volist name="menus" id="vo">
															<tr >
																<td>{pigcms{$vo.id}</td>
														
																<td >{pigcms{$vo.name}</td>
																<td></td>
															
																<td></td>
																<td></td>
																
																<td></td>
																
															</tr>
															<if condition="$vo.menu_list">
															
																<volist name="vo.menu_list" id="vv">
																	<tr <php>if(in_array($vv['id'],$mer_menus)){</php>style="display:none"<php>}</php>> 
																		<td>{pigcms{$vv.id}</td>
																
																		<td ></td>
																	
																		<td>{pigcms{$vv.name}</td>
																		<td></td>
																		<td><if condition="empty($vv['menu_list'])">{pigcms{$vv.price|floatval}元</if></td>
																		
																		<td><if condition="empty($vv['menu_list'])"><a class="btn btn-success" onclick="artconfirms({pigcms{$vv.id});">购买</a></if></td>
																		
																	</tr>
																	<if condition="$vv.menu_list">
															
																		<volist name="vv.menu_list" id="vd">
																			<tr <php>if(in_array($vd['id'],$mer_menus)){</php>style="display:none"<php>}</php>>
																				<td>{pigcms{$vd.id}</td>
																		
																			
																				<td > </td>
																				<td></td>
																				<td>{pigcms{$vd.name}</td>
																				<td>{pigcms{$vd.price|floatval}元</td>
																				
																				<td><a class="btn btn-success" onclick="artconfirms({pigcms{$vd.id});">购买</a></td>
																				
																			</tr>
																			
																		</volist>
																	</if>
																</volist>
															</if>
														</volist>
														
													<else/>
														<tr><td class="textcenter red" colspan="6">列表为空！</td></tr>
													</if>
												
														
														
												
												</tbody>
											</table>
										</div>						
									</div>
									<!--div class="col-xs-2" style="margin-top: 15px;">
										<a class="btn btn-success" href="#">导出成excel</a>
									</div-->
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	function artconfirms(auth_id) {
		artDialog(
			{	
				content:'您确定购买吗？如果您的商家余额充足将直接使用商家余额购买，不足请充值后购买！',
				lock:true,
				style:'succeed noClose'
			},
			function(){
				window.location.href="{pigcms{:U('pay_merchant_service')}&auth_id="+auth_id
			},
			function(){
				window.location.reload()
			}
		);
	}



</script>
<include file="Public:footer"/>
