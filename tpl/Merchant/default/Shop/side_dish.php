<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-cubes"></i>
                <a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
            </li>
            <li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">分类列表</a></li>
            <li class="active"><a href="{pigcms{:U('Shop/goods_list',array('sort_id'=>$now_sort['sort_id']))}">{pigcms{$now_sort.sort_name}</a></li>
            <li class="active">{pigcms{$now_goods.name}</li>
            <li class="active">配菜</li>
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
                    <button class="btn btn-success" onclick="CreateDish()">添加配菜</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th class="button-column">名称</th>
                                <th width="80">下限</th>
                                <th width="80">上限</th>
                                <th width="80">可否多选</th>
                                <th width="50">总数量</th>
                                <th width="100" class="button-column">操作</th>
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
                                    单选
                                    <else />
                                    多选
                                </if>
                            </td>
                            <td>{pigcms{$vo.count}</td>
                            <td class="button-column">
                                <a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Shop/dish_add',array('goods_id'=>$vo['goods_id'],'page'=>$_GET['page'],'dish_id'=>$vo['id']))}">
                                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                                </a>
                                <a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Shop/dish_del',array('goods_id'=>$vo['goods_id'],'dish_id'=>$vo['id']))}">
                                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                                </a>
                            </td>
                            </tr>
                            </volist>
                            <else/>
                            <tr class="odd"><td class="button-column" colspan="6" >无配菜</td></tr>
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
            if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
        });
    });
    function CreateDish(){
        window.location.href = "{pigcms{:U('Shop/dish_add',array('goods_id' => $now_goods['goods_id']))}";
    }
</script>
<include file="Public:footer"/>
