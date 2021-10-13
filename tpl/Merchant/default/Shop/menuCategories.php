<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{:L('DELIVERY_MANAGEMENT_BKADMIN')}</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Shop/index')}">{pigcms{$now_store.name}</a></li>
			<if condition="$sortList">
                <volist name="sortList" id="sl">
                    <li class="active"><a href="{pigcms{:U('Shop/goods_sort', array('fid' => $sl['fid'], 'store_id' => $now_store['store_id']))}">{pigcms{$sl['sort_name']}</a></li>
                </volist>
			</if>
			<li class="active">{pigcms{:L('CATEGORY_LIST_BKADMIN')}</li>
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
				    <a class="btn btn-success" href="{pigcms{:U('Shop/sort_add',array('store_id' => $now_store['store_id'], 'fid' => $fid))}">{pigcms{:L('ADD_CATEGORY_BKADMIN')}</a>
                    | <input type="text" id="tax_num" name="tax_num" value="">%
                    <button class="btn btn-success" onclick="Modify_tax({pigcms{$now_store['store_id']},0)">{pigcms{:L('EDIT_TAX_RATE_BKADMIN')}</button>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>{pigcms{:L('ID_BKADMIN')}</th>
									<th>Product Number</th>
									<th>{pigcms{:L('CATEGORY_NAME_BKADMIN')}</th>
									<th>{pigcms{:L('CATEGORY_AVAILABILITY(DAY)_BKADMIN')}</th>
									<th>{pigcms{:L('CATEGORY_AVAILABILITY(TIME)_BKADMIN')}</th>
									<th>Product Management</th>
									<th>{pigcms{:L('ACTION_BKADMIN')}</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$categories">
									<volist name="categories" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.id}</td>
											<td>{pigcms{$vo.productNum}</td>
											<td>{pigcms{$vo.name}</td>
											<td>
                                                {pigcms{$vo.week}
											</td>
											<td>

                                            </td>
											<td>
                                                <a  class="label label-sm label-info handle_btn" href="{pigcms{:U('Shop/menuProduct',array('categoryId'=>$vo['id'],'menuId'=>$_GET['menuId'],'store_id'=>$vo['storeId']))}">
                                                    Product Management
                                                </a>
											</td>
											<td>

											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="8" >{pigcms{:L('NO_CONTENT_BKADMIN')}</td></tr>
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
        if(!confirm("{pigcms{:L('SURE_RECOVERABLE_BKADMIN')}")) return false;
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
        alert("{pigcms{:L('ENTER_TAX_BKADMIN')}");
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