<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/audit_index')}">审核业主</a>
            </li>
            <li class="active">审核业主列表</li>
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
                                    <th width="10%">物业编号</th>
                                    <th width="10%">姓名</th>
                                    <th width="10%">手机号</th>
									 <th width="5%">住宅类型</th>
                                    <th width="15%">住址</th>
                                    <th width="15%">绑定关系</th>
                                    <th width="5%">状态</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$user_list['list']">
                                    <volist name="user_list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td>{pigcms{$vo.name}</td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
											<td><if condition='$vo["floor_type_name"]'><div class="tagDiv">{pigcms{$vo.floor_type_name}</div><else /><div class="tagDiv red">暂无</div></if></td>
                                            <td><div class="tagDiv">{pigcms{$vo.floor_layer} {pigcms{$vo.floor_name} {pigcms{$vo.layer} {pigcms{$vo.room}</div></td>
                                            <td>
												<div class="tagDiv">
													<if condition='$vo["type"] eq 0'>
														房主
													<elseif condition='$vo["type"] eq 1' />
														家人
													<elseif condition='$vo["type"] eq 2' />
														租客
													</if>
												</div>
											</td>
                                            <td>
												<if condition='$vo["status"] eq 0'>
													<div class="shopNameDiv red">禁止</div>
												<elseif condition='$vo["status"] eq 1' />
													<div class="shopNameDiv green">正常</div>
												<elseif condition='$vo["status"] eq 2' />
													<div class="shopNameDiv red">审核中</div>
												<elseif condition='$vo["status"] eq 3' />
													<div class="shopNameDiv green">审核通过</div>
												<else />
													<div class="shopNameDiv red">已解绑</div>
												</if>
											</td>
                                            <td class="button-column">
												<if condition='$vo["status"] neq 1'>
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('User/audit_edit',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum']))}">编辑</a>
												</if>
                                                
												<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该条信息？')){location.href=\'{pigcms{:U('audit_del',array('pigcms_id'=>$vo['pigcms_id']))}\'}">删除</a>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="12" >没有任何业主。</td></tr>
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
function importUser(){
	window.location.href = "{pigcms{:U('User/user_import')}";
}
function importUserDetail(){
	window.location.href = "{pigcms{:U('User/detail_import')}";
}

function send_property(){
	var property_warn_day = "{pigcms{$village_info['property_warn_day']}";
	if(parseInt(property_warn_day) > 0){
		var confirm_txt = "确认群发微信消息（物业费到期提前" + property_warn_day + "天提醒）";
	}else{
		var confirm_txt = "确认群发微信消息（物业费到期提醒）";
	}
	
	if(confirm(confirm_txt)){
		var url = "{pigcms{:U('User/send_property')}";
		$.post(url , {'is_collective':1},function(data){
			if(data['status']){
				alert(data['msg']);
			}
		},'json')
	}
}

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
