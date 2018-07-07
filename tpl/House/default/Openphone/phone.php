<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-shopping-cart gear-icon"></i>
				功能库
			</li>
			<li><a href="{pigcms{:U('Openphone/index')}">常用电话</a></li>
			<li class="active"><a href="{pigcms{:U('Openphone/phone',array('cat_id'=>$now_cat['cat_id']))}">【{pigcms{$now_cat.cat_name}】电话列表</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
        	
            <div class="row">
                <div class="col-xs-12">
					<button class="btn btn-success" onclick="phone_add()">添加电话</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="25%">名称</th>
                                    <th width="20%">电话</th>
                                    <th width="20%">排序</th>
                                    <th width="15%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$phone_list">
                                    <volist name="phone_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.pigcms_id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                           	<td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                           	<td><div class="tagDiv">{pigcms{$vo.sort}</div></td>
                                            <td>
												<div class="tagDiv">
													<if condition="$vo['status'] eq 0">
														<div class="tagDiv red">关闭</div>
													<else />
														<div class="tagDiv green">开启</div>
													</if>
												</div>
											</td>
                                            <td class="button-column">
												<a style="width: 60px;" class="label label-sm label-info" title="修改" href="{pigcms{:U('phone_edit',array('id'=>$vo['pigcms_id']))}">修改</a>
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该电话？')){location.href='{pigcms{:U('phone_del',array('id'=>$vo['pigcms_id']))}'}">删除</a>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="6" >没有任何分类。</td></tr>
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
<script type="text/javascript">
function phone_add(){
	var url = "{pigcms{:U('phone_add',array('cat_id'=>$now_cat['cat_id']))}";
	window.location.href = url;
}
</script>

<include file="Public:footer"/>