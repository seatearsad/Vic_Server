<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-cubes"></i>
                <a href="{pigcms{:U('Shop/index')}">{pigcms{:L('DELIVERY_MANAGEMENT_BKADMIN')}</a>
            </li>
            <li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">{pigcms{:L('CATEGORY_LIST_BKADMIN')}</a></li>
            <li class="active"><a href="{pigcms{:U('Shop/goods_list',array('sort_id'=>$now_sort['sort_id']))}">{pigcms{$now_sort.sort_name}</a></li>
            <li class="active">{pigcms{$now_goods.name}</li>
            <li class="active">{pigcms{:L('OPTIONS_BKADMIN')}</li>
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
                    <button class="btn btn-success" onclick="CreateDish()">{pigcms{:L('ADD_OPTION_BKADMIN')}</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="button-column">{pigcms{:L('OPTION_BKADMIN')}</th>
                                <th width="80">{pigcms{:L('MIN_BKADMIN')}</th>
                                <th width="80">{pigcms{:L('MAX_BKADMIN')}</th>
                                <th width="80">{pigcms{:L('MULTI_CAP_BKADMIN')}</th>
                                <th width="50">{pigcms{:L('TOTAL_BKADMIN')}</th>
                                <th width="50">{pigcms{:L('STATUS_BKADMIN')}</th>
                                <th width="100" class="button-column">{pigcms{:L('ACTION_BKADMIN')}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <if condition="$dish_list">
                                <volist name="dish_list" id="vo">
                                    <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                            <td>{pigcms{$vo.name}</td>
                            <td>{pigcms{$vo.min}</td>
                            <td>{pigcms{$vo.max}</td>
                            <td>
                                <if condition="$vo['type'] eq 0">
                                    {pigcms{:L('SINGLE_SELECTION_BKADMIN')}
                                    <else />
                                    {pigcms{:L('MULTIPLE_SELECTION_BKADMIN')}
                                </if>
                            </td>
                            <td>{pigcms{$vo.count}</td>
                            <td>
                                <label class="statusSwitch" style="display:inline-block;">
                                    <input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo.id}" <if condition="$vo['status'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
                                    <span class="lbl"></span>
                                </label>
                            </td>
                            <td class="button-column">
                                <a title="{pigcms{:L('EDIT_BKADMIN')}" class="green" style="padding-right:8px;" href="{pigcms{:U('Shop/dish_add',array('goods_id'=>$vo['goods_id'],'page'=>$_GET['page'],'dish_id'=>$vo['id']))}">
                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                </a>
                                <a title="{pigcms{:L('DELETE_BKADMIN')}" class="red" style="padding-right:8px;" href="{pigcms{:U('Shop/dish_del',array('goods_id'=>$vo['goods_id'],'dish_id'=>$vo['id']))}">
                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                </a>
                            </td>
                            </tr>
                            </volist>
                            <else/>
                            <tr class="odd"><td class="button-column" colspan="6" >{pigcms{:L('NO_OPTIONS_BKADMIN')}</td></tr>
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
        jQuery(document).on('click','#shopList a.red',function(){
            if(!confirm("{pigcms{:L('SURE_RECOVERABLE_BKADMIN')}")) return false;
        });
    });
    function CreateDish(){
        window.location.href = "{pigcms{:U('Shop/dish_add',array('goods_id' => $now_goods['goods_id']))}";
    }

    updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");

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
                url:"{pigcms{:U('Shop/side_dish_status')}",
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
</script>
<include file="Public:footer"/>
