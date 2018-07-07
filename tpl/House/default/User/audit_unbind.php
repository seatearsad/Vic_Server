<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/audit_unbind')}">申请解绑</a>
            </li>
            <li class="active">申请解绑列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">申请编号</th>
                                    <th width="10%">姓名</th>
                                    <th width="10%">手机号码</th>
									<th width="20%">单元/房间</th>
                                    <th width="10%">所属角色</th>
                                    <th width="10%">状态</th>
                                    <th width="15%">操作时间</th>
                                    <th class="button-column" >操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$lists">
                                    <volist name="lists" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.itemid}</div></td>
                                            <td>{pigcms{$vo.name}</td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
											<td>{pigcms{$vo.address}</td>
                                            <td>
												<div class="tagDiv">
													<if condition='$vo["type"] eq 0'>
														房主
													<elseif condition='$vo["type"] eq 1' />
														家人
													<elseif condition='$vo["type"] eq 2' />
														租客
                                                    <elseif condition='$vo["type"] eq 3' />
														替换房主    
													</if>
												</div>
											</td>
                                            <td>
												<div class="tagDiv">
													<if condition='$vo["status"] eq 1'>
														<span class="red">审核中</span>
													<elseif condition='$vo["status"] eq 2' />
														<span class="red">拒绝解绑</span>
													<elseif condition='$vo["status"] eq 3' />
														<span class="green">已解绑</span>
													</if>
												</div>
											</td>
                                            <td>{pigcms{$vo.edittime|date='Y-m-d H:i:s',###}</td>
                                            
                                            <td class="button-column">
												<if condition='$vo["status"] neq 3'>
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('User/audit_unbind_edit',array('itemid'=>$vo['itemid']))}">编辑</a>&nbsp;&nbsp;
												</if>
                                                
												<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该条信息？')){location.href=\'{pigcms{:U('audit_unbind_del',array('itemid'=>$vo['itemid']))}\'}">删除</a>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >没有任何申请信息</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$user_list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" language="javascript">



function send_property_one(pigcms_id , usernum){
	if(confirm('确认发送微信消息？')){
		var url = "{pigcms{:U('User/send_property')}";
		$.post(url,{'pigcms_id':pigcms_id,'usernum':usernum},function(data){
			if(data['status']){
				alert(data['msg']);
			}
		},'json')
	}
}
</script>
<include file="Public:footer"/>
