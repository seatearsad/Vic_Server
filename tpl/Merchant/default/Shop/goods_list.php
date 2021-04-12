<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{:L('DELIVERY_MANAGEMENT_BKADMIN')}<</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">{pigcms{:L('C_CATEGORYLIST')}</a></li>
			<li class="active">{pigcms{$now_sort.sort_name}</li>
			<li class="active">{pigcms{:L('ITEM_LIST_BKADMIN')}</li>
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
					<button class="btn btn-success" onclick="CreateShop()">{pigcms{:L('ADD_ITEM_BKADMIN')}</button>
                    | <input type="text" id="tax_num" name="tax_num" value="">%
                    <button class="btn btn-success" onclick="Modify_tax({pigcms{$now_sort.store_id},{pigcms{$now_sort.sort_id})">{pigcms{:L('EDIT_TAX_RATE_BKADMIN')}</button>
                    <button style="float: right" class="btn btn-success" onclick="ImportExcel()">{pigcms{:L('IMPORT_DATA_BKADMIN')}</button>
                    <input type="file" id="inputExcel" style="display:none;">
                    <div style="float: right;margin-top: 20px;margin-right: 20px">
                        <label style="float: left">
                            <if condition="$is_hide eq 1">
                                {pigcms{:L('HIDE_DELETED_BKADMIN')}
                            <else/>
                                {pigcms{:L('SHOW_DELETED_BKADMIN')}
                            </if>
                        </label>
                        <input name="switch-field-1" id="hide_btn" class="ace ace-switch ace-switch-6" type="checkbox" <if condition="$is_hide eq 0">checked="checked"</if>/>
                        <span class="lbl"></span>
                    </div>
                    <div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">{pigcms{:L('ID_BKADMIN')}</th>
									<th width="50">{pigcms{:L('LISTING_ORDER_BKADMIN')}</th>
									<th class="button-column">{pigcms{:L('ITEM_NAME_BKADMIN')}</th>
									<th width="80">{pigcms{:L('LISTING_PRICE_BKADMIN')}</th>
									<th class="button-column" style="width:60px;">{pigcms{:L('UNIT_BKADMIN')}</th>
									<th width="80">{pigcms{:L('ORIGINAL_INSTOCK_BKADMIN')}</th>
									<th width="80">{pigcms{:L('CURRENTLY_INSTOCK_BKADMIN')}</th>
                                    <th width="50">{pigcms{:L('TAX_RATE_BKADMIN')}</th>
                                    <th width="50">{pigcms{:L('BOTTLE_DEPOSIT_BKADMIN')}</th>
									<th width="80">{pigcms{:L('DAILY_SALES_BKADMIN')}</th>
									<th width="80">{pigcms{:L('TOTAL_SALES_BKADMIN')}</th>
									<th class="button-column" style="width:180px;">{pigcms{:L('LAST_MODIFIED_BKADMIN')}</th>
									<th class="button-column" style="width:100px;">{pigcms{:L('PRINTER_BKADMIN')}</th>
									<th width="100" class="button-column">{pigcms{:L('STATUS_BKADMIN')}</th>
									<th width="100" class="button-column">{pigcms{:L('ACTION_BKADMIN')}</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$goods_list">
									<volist name="goods_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.goods_id}</td>
											<td>{pigcms{$vo.sort}</td>
											<td>{pigcms{$vo.name}</td>
											<td>{pigcms{$vo.price|floatval}</td>
											<td class="button-column">{pigcms{$vo.unit}</td>
											<if condition="$vo['stock_num'] eq -1">
											<td>{pigcms{:L('ULN_BKADMIN')}</td>
											<else />
											<td>{pigcms{$vo.stock_num}</td>
											</if>
											<td>{pigcms{$vo.stock_num_t}</td>
                                            <td>{pigcms{$vo.tax_num}%</td>
                                            <td>{pigcms{$vo.deposit_price}</td>
											<td>{pigcms{$vo.today_sell_count}</td>
											<td>{pigcms{$vo.sell_count}</td>
											<td class="button-column">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
											<td class="button-column">{pigcms{$vo.print_name}</td>
											<td class="button-column">
                                                <if condition="$vo['status'] eq 2">
                                                    <img src="{pigcms{$static_path}images/noteye.png" width="30" />
                                                    <else/>
												<label class="statusSwitch" style="display:inline-block;">
													<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo.goods_id}" <if condition="$vo['status'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
													<span class="lbl"></span>
												</label>
                                                </if>
											</td>
											<td class="button-column">
												<a title="{pigcms{:L('EDIT_BKADMIN')}" class="green" style="padding-right:8px;" href="{pigcms{:U('Shop/goods_edit',array('goods_id'=>$vo['goods_id'],'page'=>$_GET['page']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>
                                                <if condition="$vo['status'] eq 2">
                                                    <a title="{pigcms{:L('RESTORE_BKADMIN')}" class="orange" style="padding-right:8px;" href="javascript:hiddenGoods({pigcms{$vo['goods_id']},0);">
                                                        <i class="ace-icon fa fa-refresh bigger-130"></i>
                                                    </a>
                                                <else/>
                                                    <a title="{pigcms{:L('DELETE_BKADMIN')}" class="red" style="padding-right:8px;" href="javascript:hiddenGoods({pigcms{$vo['goods_id']},1);">
                                                        <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                                    </a>
                                                </if>
                                                <a title="{pigcms{:L('COPY_BKADMIN')}" class="blue" style="padding-right:8px;" href="{pigcms{:U('Shop/goods_copy',array('goods_id'=>$vo['goods_id']))}">
                                                    <i class="ace-icon fa fa-file-o bigger-130"></i>
                                                </a>
                                                <a title="{pigcms{:L('OPTIONS_BKADMIN')}" class="pink" style="padding-right:8px;" href="{pigcms{:U('Shop/side_dish',array('goods_id'=>$vo['goods_id']))}">
                                                    <i class="ace-icon fa fa-inbox bigger-130"></i>
                                                </a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="13" >无内容</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/xlsx.full.min.js"></script>
<script type="text/javascript">
$(function(){
	/*店铺状态*/
	updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");

	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm("{pigcms{:L('YOU_HIDE_BKADMIN')}}")) return false;
	});

    jQuery(document).on('click','#shopList a.orange',function(){
        if(!confirm("{pigcms{:L('SURE_RESTOR_BKADMIN')}}")) return false;
    });

    jQuery(document).on('click','#shopList a.blue',function(){
        if(!confirm('确定要复制此产品吗？')) return false;
    });
});

function hiddenGoods(goods_id,attribute) {
    $.ajax({
        url:"{pigcms{:U('Shop/goods_status')}",
        type:"post",
        data:{"type":"hidden","id":goods_id,"status1":0,"status2":2,"attribute":attribute},
        dataType:"text",
        success:function(d){
            window.location.reload();
        }
    });
}
function CreateShop(){
	window.location.href = "{pigcms{:U('Shop/goods_add',array('sort_id' => $now_sort['sort_id']))}";
}
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
		 	id = $(this).attr("data-id");
		_this.attr("disabled",true);
		if(_this.attr("checked")){	//开启
			type = 'open';
		}else{		//关闭
			type = 'close';
		}
		$.ajax({
			url:"{pigcms{:U('Shop/goods_status')}",
			type:"post",
			data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
			dataType:"text",
			success:function(d){
				if(d != '1'){		//失败
					if (type=='open') {
						_this.attr("checked",false);
					} else {
						_this.attr("checked",true);
					}
					bootbox.alert("操作失败");
				}
				_this.attr("disabled",false);
			}
		});
	});
}

function ImportExcel() {
    $('#inputExcel').click();
}

$('#inputExcel').change(function (e) {
    var files = e.target.files;
    var fileReader = new FileReader();
    fileReader.onload = function(ev) {
        try {
            var data = ev.target.result,
                workbook = XLSX.read(data, {
                    type: 'binary'
                }), // 以二进制流方式读取得到整份excel表格对象
                persons = []; // 存储获取到的数据
        } catch (e) {
            console.log('文件类型不正确');
            return;
        }

        // 表格的表格范围，可用于判断表头是否数量是否正确
        var fromTo = '';
        // 遍历每张表读取
        for (var sheet in workbook.Sheets) {
            if (workbook.Sheets.hasOwnProperty(sheet)) {
                fromTo = workbook.Sheets[sheet]['!ref'];
                //console.log(fromTo);
                persons = persons.concat(XLSX.utils.sheet_to_json(workbook.Sheets[sheet]));
                // break; // 如果只取第一张表，就取消注释这行
            }
        }

        $.post("{pigcms{:U('Shop/import_excel')}",{"store_id":"{pigcms{$now_sort.store_id}","sort_id":"{pigcms{$now_sort.sort_id}","data":persons},function(result){
            bootbox.alert(result.msg);
            setTimeout(function() {
                window.location.reload();
            },2000);
        },"json");
    };

    // 以二进制方式打开文件
    fileReader.readAsBinaryString(files[0]);
});

$('#hide_btn').click(function () {
    var is_hide = "{pigcms{$is_hide}";
    is_hide = is_hide == "1" ? 0 : 1;

    window.location.href = "{pigcms{:U('Shop/goods_list',array('sort_id' => $now_sort['sort_id']))}"+"&hidden="+is_hide;
});
</script>
<include file="Public:footer"/>
