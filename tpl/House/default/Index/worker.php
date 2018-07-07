<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('Index/worker')}">工作人员管理</a>
            </li>
            <li class="active">工作人员列表</li>
        </ul>
    </div>
    <!-- 内容头部 -->
    <div class="page-content">
        <div class="page-content-area">
        	<a class="btn btn-success" href="{pigcms{:U('Index/worker_add')}">添加工作人员</a>
            <style>
                .ace-file-input a {display:none;}
            </style>
            <div class="row">
                <div class="col-xs-12">
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>姓名</th>
                                    <th>电话</th>
                                    <th>微信昵称</th>
                                    <th>入职时间</th>
                                    <th>职务类型</th>
                                    <th>状态</th>
                                    <th>处理次数</th>
                                    <th>被评论数</th>
                                    <th>评分</th>
                                    <th>处理任务详情</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$workers">
                                    <volist name="workers" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.name}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.phone}</div></td>
                                            <td class="nickname">
                                            <if condition="empty($vo['openid'])">
                                            <a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_tmp_qrcode&qrcode_id={pigcms{$vo['wid'] + 3900000000}&img=1" data-wid="{pigcms{$vo['wid']}" class="see_qrcode" id="qr_{pigcms{$vo['wid']}" >绑定微信账号</a>
                                            <else/>
                                            {pigcms{$vo.nickname}
                                            </if>
                                            </td>
                                            <td><div class="tagDiv"><if condition="empty($vo['create_time'])">--<else />{pigcms{$vo.create_time|date='Y-m-d H:i:s',###}</if></div></td>
                                            <td><div class="tagDiv"><if condition='$vo["type"] eq 1'><span class="green">维修技工</span><else /><span class="red">客服专员</span></if></div></td>
                                            <td><div class="tagDiv"><if condition='$vo["status"] eq 1'><span class="green">正常</span><else /><span class="red">关闭</span></if></div></td>
                                            <td>{pigcms{$vo.num}</td>
                                            <td>{pigcms{$vo.reply_count}</td>
                                            <td>{pigcms{$vo.score_mean}</td>
                                            <td><a href="{pigcms{:U('Index/worker_order', array('wid'=>$vo['wid']))}">查看任务列表</a></td>
                                            <td>
                                                <a style="width: 60px;" class="label label-sm label-info" title="编辑" href="{pigcms{:U('Index/worker_edit', array('wid'=>$vo['wid']))}">编辑</a>　
                                                <a class="label label-warning cancel" title="取消微信绑定" data-wid="{pigcms{$vo['wid']}" <if condition="empty($vo['openid'])">style="display:none"</if>>取消绑定</a>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="10" >还没有工作人员入职</td></tr>
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
var test;
$(document).ready(function(){
	$('.see_qrcode').live('click', function(){
		test = art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'扫描二维码绑定微信号',
			padding: 0,
			width: 430,
			height: 433,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: function(){clearInterval(t);},
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		var wid = $(this).attr('data-wid'), obj = $(this);
	 	var t = window.setInterval(function(){
			$.get("{pigcms{:U('Index/check_worker')}", {wid:wid},  function(result){
				if (result.error_code == 0) {
					test.close();
					clearInterval(t);
					obj.parent('td').html(result.nickname).siblings('.button-column').children('.cancel').show();
				}
			}, 'json');
		},3000);
		return false;
	});
	
	$('.cancel').click(function(){
		var wid = $(this).attr('data-wid'), obj = $(this);
		obj.attr('disabled', true);
		$.get("{pigcms{:U('Index/cancel_worker')}", {wid:wid}, function(result){
			obj.attr('disabled', false);
			if (result.error_code == 1) {
				alert(result.msg);
			} else {
				var qrcode_id = 3900000000 + wid;
				obj.hide().parent('td').siblings('.nickname').html('<a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_tmp_qrcode&qrcode_id=' + qrcode_id + '&img=1" data-wid="' + wid + '" class="see_qrcode">绑定公众号</a>');
			}
		}, 'json');
	});
});
</script>
<include file="Public:footer"/>
