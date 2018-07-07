<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Foodshop/package', array('store_id' => $now_store['store_id']))}">{pigcms{$now_store['name']}</a></li>
			<li class="active">修改套餐</li>
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
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtintro">商品详情</a>
							</li>
							
							<!--li>
								<a data-toggle="tab" href="#seckill">限时优惠</a>
							</li-->
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane  active">
								<if condition="$error_tips">
									<div class="alert alert-danger">
										<p>请更正下列输入错误:</p>
										<p>{pigcms{$error_tips}</p>
									</div>
								</if>
								<if condition="$ok_tips">
									<div class="alert alert-info">
										<p>{pigcms{$ok_tips}</p>				
									</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1"><label for="name">套餐名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$package.name}"/>
									<span class="form_tips">必填。</span>
								</div>
								
								<!--div class="form-group">
									<label class="col-sm-1"><label for="price">商品原价</label></label>
									<input class="col-sm-1" size="20" name="old_price" id="old_price" type="text" value="{pigcms{$package.old_price|floatval}"/>
									<span class="form_tips">原价可不填，不填和现价一样</span>
								</div-->
								<div class="form-group">
									<label class="col-sm-1"><label for="price">套餐价格</label></label>
									<input class="col-sm-1" size="20" name="price" id="price" type="text" value="{pigcms{$package.price|floatval}"/>
									<span class="form_tips">元</span>
								</div>
							
								<div class="form-group">
									<label class="col-sm-1">是否可用：</label>
									<label><input type="radio" name="status" value="0" <if condition="$package['status'] eq 0">checked="checked"</if>>&nbsp;&nbsp;否</label>&nbsp;&nbsp;&nbsp;
									<label><input type="radio" name="status" value="1" <if condition="$package['status'] eq 1">checked="checked"</if>>&nbsp;&nbsp;是</label>&nbsp;&nbsp;&nbsp;
								</div>
								<!--div class="form-group">
									<label class="col-sm-1"><label for="price">{pigcms{$now_store['pack_alias']|default='打包费'}</label></label>
									<input class="col-sm-1" size="20" name="packing_charge" id="packing_charge" type="text" value="{pigcms{$package.packing_charge|floatval}"/>
								</div-->
										
								<div class="form-group">
									<label class="col-sm-1"><label for="price">使用说明</label></label>
									<textarea class="col-sm-2" rows="4" cols="10" name="note">{pigcms{$package.note}</textarea>
									<span class="form_tips">最多200个字</span>
								</div>
								
							</div>
							<div id="txtintro" class="tab-pane">
								<div class="alert alert-info" style="margin:10px 0;">
									<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
									添加一项表示添加一个菜品系列，可以添加多个菜品供选择;可选数：表示该系列下最多能选择几个菜品
									<br/><br/>
									默认可选择一个菜品;可选数后面的删除表示删除该系列，菜品后面的删除表示删除该菜品！
								</div>
								<div class="topic_box">
									<volist name="package['goods_detail']" id="goods_detail" key="out">
									<div class="question_box spec">
										<p class="question_info"><span>可选数：</span>
											<input type="text" class="txt" value="{pigcms{$goods_detail['num']}" name="nums[]"/>
											<input type="hidden" class="txt" value="{pigcms{$goods_detail['id']}" name="dids[]"/>
											<a href="javascript:;" class="box_del">删除</a>
										</p>
										<div class="optionul_r">
											<if condition="!empty($goods_detail['goods_list'])">
											<table class="table table-striped table-bordered table-hover">
												<tr>
													<td>菜品名称</td>
													<td>菜品价格</td>
													<!--td>规格</td-->
													<td>操作</td>
												</tr>
												<volist name="goods_detail['goods_list']" id="detail">
												<tr>
													<td>{pigcms{$detail['name']}<input type="hidden" name="goods_ids[{pigcms{$out-1}][]" value="{pigcms{$detail['goods_id']}" /></td>
													<td>{pigcms{$detail['price']|floatval}</td>
													<!--td></td-->
													<td class="button-column">
														<a title="删除" class="red" style="padding-right:8px;" href="javascript:;">
															<i class="ace-icon fa fa-trash-o bigger-130"></i>
														</a>
													</td>
												</tr>
												</volist>
											</table>
											</if>
											<p class="bot_add"><a href="javascript:;" class="btn btn-sm btn-success">添加菜品</a></p>
										</div>
									</div>
									</volist>
									<p class="add_spec" style="margin-top:10px"><a href="javascript:;" title="添加" class="btn btn-sm btn-success">添加一项</a></p>
							</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>var menu_url = "{pigcms{:U('Foodshop/menu',array('store_id'=>$now_store['store_id']))}";</script>
<link rel="stylesheet" href="{pigcms{$static_path}css/package.css">
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/package.js"></script>
<include file="Public:footer"/>