<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Shop/index')}">{pigcms{$now_store.name}</a></li>
			<if condition="$sortList">
                <volist name="sortList" id="sl">
                    <li class="active"><a href="{pigcms{:U('Shop/goods_sort', array('fid' => $sl['fid'], 'store_id' => $now_store['store_id']))}">{pigcms{$sl['sort_name']}</a></li>
                </volist>
			</if>
			<li class="active">分类列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
				    <a class="btn btn-success" href="{pigcms{:U('Shop/sort_add',array('store_id' => $now_store['store_id'], 'fid' => $fid))}">新建分类</a>
                    | <input type="text" id="tax_num" name="tax_num" value="">%
                    <button class="btn btn-success" onclick="Modify_tax({pigcms{$now_store['store_id']},0)">修改全部税率</button>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>编号</th>
									<th>排序</th>
									<th>分类名称</th>
									<th>星期几显示</th>
									<th>显示时间段</th>
									<th>商品管理</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$sort_list">
									<volist name="sort_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.sort_id}</td>
											<td>{pigcms{$vo.sort}</td>
											<td>{pigcms{$vo.sort_name}</td>
											<td>
												<label class="statusSwitch" style="display:inline-block;">
													<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-type="show_week" data-id="{pigcms{$vo.sort_id}" <if condition="$vo['is_weekshow'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
													<span class="lbl"></span>
												</label>
                                                <div>{pigcms{$vo.week_str}</div>
											</td>
											<td>
                                                <label class="statusSwitch" style="display:inline-block;">
                                                    <input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-type="show_time" data-id="{pigcms{$vo.sort_id}" <if condition="$vo['is_time'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
                                                    <span class="lbl"></span>
                                                </label>
                                                <if condition="$vo['begin_time']">
                                                <div>{pigcms{$vo.begin_time} - {pigcms{$vo.end_time}</div>
                                                </if>
                                            </td>
											<td>
											     <if condition="$vo['operation_type'] eq 2">
											         <a style="width: 60px;" class="label label-sm label-purple" href="{pigcms{:U('Shop/goods_sort',array('fid' => $vo['sort_id'], 'store_id' => $vo['store_id']))}">子分类</a>
											         <a style="width: 60px;" class="label label-sm label-info handle_btn" href="{pigcms{:U('Shop/goods_list',array('sort_id'=>$vo['sort_id']))}">商品管理</a>
											     <elseif condition="$vo['operation_type'] eq 1" />
											         <a style="width: 60px;" class="label label-sm label-purple" href="{pigcms{:U('Shop/goods_sort',array('fid' => $vo['sort_id'], 'store_id' => $vo['store_id']))}">子分类</a>
											     <else />
											         <a style="width: 60px;" class="label label-sm label-info handle_btn" href="{pigcms{:U('Shop/goods_list',array('sort_id'=>$vo['sort_id']))}">商品管理</a>
											     </if>
											</td>
											<td>
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Shop/sort_edit',array('sort_id'=>$vo['sort_id'], 'fid' => $vo['fid']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>　　
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Shop/sort_del',array('sort_id'=>$vo['sort_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>　　
												<if condition="empty($fid)">
												<a style="width: 60px;" class="label label-sm label-info handle_btn" href="{pigcms{:U('Shop/sort_order',array('sort_id' => $vo['sort_id'], 'store_id' => $vo['store_id']))}">销量详情</a>
											    </if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="8" >无内容</td></tr>
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
	/*店铺状态*/
	updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});
function Modify_tax(store_id,sort_id) {
    if($('#tax_num').val()){
        $.ajax({
            url:"{pigcms{:U('Shop/goods_tax')}",
            type:"post",
            data:{"store_id":store_id,"sort_id":sort_id,"tax_num":$('#tax_num').val()},
            dataType:"json",
            success:function(d){
                alert(d.info);
                window.location.reload();
            }
        });
    }else {
        alert('请输入税率!');
    }
}
function updateStatus(dom1, dom2, status1, status2, attribute){
	$(dom1).each(function(){
		if($(this).attr("data-status")==status1){
			$(this).attr("checked",true);
		}else{
			$(this).attr("checked",false);
		}
		$(dom2).show();
	}).click(function(){
		var _this = $(this),
		 	type = 'open',
		 	id = $(this).attr("data-id"),
            attribute = $(this).data('type');
		_this.attr("disabled",true);
		if(_this.attr("checked")){	//开启
			type = 'open';
		}else{		//关闭
			type = 'close';
		}
		$.ajax({
			url:"{pigcms{:U('Shop/sort_status')}",
			type:"post",
			data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
			dataType:"text",
			success:function(d){
				if(d != '1'){		//失败
					if(type=='open'){
						_this.attr("checked",false);
					}else{
						_this.attr("checked",true);
					}
					bootbox.alert("操作失败");
				}
				_this.attr("disabled",false);
			}
		});
	});
}
</script>
<include file="Public:footer"/>