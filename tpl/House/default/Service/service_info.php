<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('service_info')}">便民服务</a>
			</li>
			<li class="active">便民分类</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
        	
            <div class="row">
                <div class="col-xs-12">
                
                <button class="btn btn-success" onclick="location.href='{pigcms{:U('service_info_add')}'">添加信息</button>
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th width="20%">标题</th>
                                    <th width="20%">分类</th>
                                    <th width="20%">链接</th>
                                    <th width="15%">状态</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list['list']">
                                    <volist name="list['list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                            <td><div class="tagDiv">{pigcms{$vo.id}</div></td>
                                            <td><div class="tagDiv">{pigcms{$vo.title}</div></td>
                                           	<td><div class="tagDiv">{pigcms{$vo.cat_name}</div></td>
                                            <td><div class="tagDiv"><a href="{pigcms{$vo.url}" target="_blank">查看链接</a></div></td>
                                            <td><div class="tagDiv">
                                           		<if condition='$vo["status"] eq 0'>
                                                	<div class="tagDiv red">关闭</div>
                                                <else />
                                                	<div class="tagDiv green">开启</div>
                                                </if>
                                            </div></td>
                                            
                                            <td class="button-column">
                                            <!--<a style="width: 60px;" class="label label-sm label-info handle_btn" title="详情" href="{pigcms{:U('service_info_detail',array('id'=>$vo['id']))}">详情</a>-->
                                           <a style="width: 60px;" class="label label-sm label-info" title="修改" href="{pigcms{:U('service_info_edit',array('id'=>$vo['id']))}">修改</a>
                                                <a style="width: 60px;" class="label label-sm label-info" title="删除" href="javascript:void(0)" onClick="if(confirm('确认删除该条信息？')){location.href='{pigcms{:U('service_info_del',array('id'=>$vo['id']))}'}">删除</a>
                                           </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="8" >没有任何信息。</td></tr>
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

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'信息详情',
				padding: 0,
				width: 720,
				height: 520,
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