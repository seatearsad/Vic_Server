<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-user"></i>
                <a href="{pigcms{:U('User/index')}">业主管理</a>
            </li>
            <li class="active">业主列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<div class="form-group" style="border:1px solid #c5d0dc;padding:10px;">
				<form method="post" id="find-form">
					<select name="find_type" id="find_type" class="col-sm-1" style="margin-right:10px;height:42px;">
						<option value="1" <if condition="$find_type eq 1">selected="selected"</if>>物业编号</option>
						<option value="2" <if condition="$find_type eq 2">selected="selected"</if>>姓名</option>
						<option value="3" <if condition="$find_type eq 3">selected="selected"</if>>手机号</option>
						<option value="4" <if condition="$find_type eq 4">selected="selected"</if>>住址</option>
					</select>
					<input value="{pigcms{$find_value}" class="col-sm-2" name="find_value" id="find_value" type="text" style="margin-right:10px;font-size:18px;height:42px;"/>

					
					<label style="padding-left:0px;padding-right:20px;">是否为平台用户<input type="radio" class="ace" value="1" <if condition="$_POST['is_platform'] eq 1">checked="checked"</if> name="is_platform" />&nbsp;&nbsp;&nbsp;&nbsp;<span style="z-index: 1" class="lbl">是</span></label>
					
					<label style="padding-left:0px;padding-right:20px;"><input type="radio" class="ace" value="2" name="is_platform" <if condition="$_POST['is_platform'] eq 2">checked="checked"</if>><span style="z-index: 1" class="lbl">否</span></label>
					
					<input class="btn btn-success" type="submit" id="find_submit" value="查找业主" />&nbsp;
					<a class="btn btn-success" onclick="location.href='{pigcms{:U('User/index')}'">重置</a>
					<a onclick="location.href='{pigcms{:U('user_export',$_POST)}'" class="btn btn-success fr">EXCEL导出</a>
				</form>
			</div>
        	<button class="btn btn-success" onclick="addUser()">添加业主</button>&nbsp;
        	<button class="btn btn-success" onclick="importUser()">导入业主</button>&nbsp;
        	<button class="btn btn-success" onclick="importUserDetail()">导入业主每月帐单明细</button>&nbsp;
			<button class="btn btn-success" onclick="send_property()">群发微信消息</button>&nbsp;
			<button class="btn btn-success" onclick="location.href='{pigcms{:U('user_data')}'">数据统计</button>
			<style type="text/css">
				.ace-file-input a {display:none;}
				.div-intro{ float:right; margin-top:20px}
				.div-intro-detail{width:10px; height:10px; background-color:red; float:left; margin-top:5px}
				.div-intro span{ float:left; margin-left:5px;}
			</style>
			<div class="div-intro">
				<div class="div-intro-detail"></div>
				<span><if condition="$village_info['property_warn_day'] gt 0">物业服务时间到期提前 {pigcms{$village_info['property_warn_day']} 天提醒<else />物业服务时间到期提醒</if></span><br />
				<div class="div-intro-detail" style="background:orange"></div>
				<span>尚不是平台微信用户（注：将无法使用社区服务）</span>
			</div>
            
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="10%">物业编号</th>
                                    <th width="10%">姓名</th>
                                    <th width="10%">手机号</th>
                                    <th width="15%">住址</th>
                                    <th width="15%">待缴费用</th>
                                    <th width="5%">停车位</th>
                                    <th width="10%">房子大小</th>
                                    <th class="button-column" width="20%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$user_list">
                                    <volist name="user_list['user_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.usernum}</div></td>
                                            <td><if condition='$vo["openid"]'><div class="tagDiv">{pigcms{$vo.name}</div><else /><div class="tagDiv" style="color:orange">{pigcms{$vo.name}</div></if>
											
											<if condition='$vo["bind_list"]'>
												<a class="bind_info red" href="{pigcms{:U('bind_list',array('pigcms_id'=>$vo['pigcms_id']))}">他的家属和租客&nbsp;>&nbsp;</a>
											</if>
											
											</td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.address}</div></td>
                                            <td>
												<div class="tagDiv">
													<!--物业费：${pigcms{:floatval($vo['property_price'])}<br/>-->
													水费：${pigcms{:floatval($vo['water_price'])}<br/>
													电费：${pigcms{:floatval($vo['electric_price'])}<br/>
													燃气费：${pigcms{:floatval($vo['gas_price'])}<br/>
													停车费：${pigcms{:floatval($vo['park_price'])}<br/>
												</div>
											</td>
                                            <td><div class="shopNameDiv"><if condition="$vo.park_flag eq '1' ">有<else />无</if></div></td>
                                            <td><div class="shopNameDiv">{pigcms{$vo.housesize} ㎡</div></td>
                                            <td class="button-column">
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('User/edit',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum']))}">编辑</a>&nbsp;
                                                <a style="width: 60px;" class="label label-sm label-info" title="欠费明细" href="{pigcms{:U('User/pay_detail',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum']))}">欠费明细</a>&nbsp;
                                           		<if condition="$vo['uid'] neq 0">
													<a style="width: 60px;" class="label label-sm label-info" title="缴费明细" href="{pigcms{:U('User/orders',array('bind_id'=>$vo['pigcms_id']))}">缴费明细</a>
                                           		</if>&nbsp;
												<a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onclick="if(confirm('确认删除该条信息？')){location.href=\'{pigcms{:U('User/user_delete',array('pigcms_id'=>$vo['pigcms_id'],'usernum'=>$vo['usernum']))}\'}">删除</a>
												
												<if condition="(((time() gt strtotime(date('Y-m' , ($vo['property_month_time'] - ($village_info['property_warn_day'] *24*3600))))) OR (!$village_info['property_warn_day'])) AND (!empty($vo['openid'])))"><br /><a style="width: 80px;background-color:#f00 !important" class="label label-sm label-info" title="发送微信通知" onclick="send_property_one('{pigcms{$vo['pigcms_id']}' , '{pigcms{$vo['usernum']}')" href="javascript:void(0)" >发送微信通知</a></if>
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
function addUser(){
	window.location.href = "{pigcms{:U('User/user_add')}";
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

$('.bind_info').click(function(){
	art.dialog.open($(this).attr('href'),{
		init: function(){
			var iframe = this.iframe.contentWindow;
			window.top.art.dialog.data('iframe_handle',iframe);
		},
		id: 'handle',
		title:'查看',
		padding: 0,
		width: 800,
		height: 603,
		lock: true,
		resize: false,
		background:'black',
		button: null,
		fixed: false,
		close: null,
		left: '50%',
		top: '38.2%',
		opacity:'0.4'
	});
	return false;
});
</script>
<include file="Public:footer"/>
