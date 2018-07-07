<include file="Public:header"/>
<div class="main-content">
    <!-- 内容头部 -->
    <div class="breadcrumbs" id="breadcrumbs">
        <ul class="breadcrumb">
            <li>
                <i class="ace-icon fa fa-gear gear-icon"></i>
                <a href="{pigcms{:U('door_list')}">门禁设置</a>
            </li>
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
                                	<th width="5%">No.</th>
                                	<th width="12%">设备ID</th>
                                    <th width="12%">设备名</th>
                                    <th width="12%">设备密码</th>
                                   	<th width="5%">状态</th>
                                   	<th width="5%">权限</th>
                                   	<th width="10%">增加时间</th>
                                   	<th width="10%">楼名</th>
                                   	<th width="10%">楼号</th>
                                    <th class="button-column" width="15%">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$door_list">
                                    <volist name="door_list" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><div class="tagDiv">{pigcms{$vo.door_id}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.door_device_id}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.door_name}</div></td>
                                            <td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.door_psword}</div></td>
                                            <if condition="$vo.door_status eq 1">
                                            	<td><div class="tagDiv" style="color:green;">启用</div></td>
                                            <elseif condition="$vo.door_status eq 0" />
                                            	<td><div class="tagDiv" style="color:red;">未启用</div></td>
                                            </if>
                                            <if condition="$vo.all_status eq 1">
                                            	<td><div class="tagDiv" style="color:green;">全部</div></td>
                                            <elseif condition="$vo.all_status eq 2" />
												<td><div class="tagDiv" style="color:#5bc0de;">获取</div></td>
                                            </if>
                                            <td><div class="shopNameDiv">{pigcms{$vo.add_time|date='Y-m-d',###}</div></td>
                                            <if condition="$vo.floor_id neq -1">
												<td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.floor_name}</div></td>
												<td style="word-break:break-all"><div class="tagDiv">{pigcms{$vo.floor_layer}</div></td>
											<else/>
												<td style="word-break:break-all"><div class="tagDiv">小区</div></td>
												<td style="word-break:break-all"><div class="tagDiv">大门</div></td>
											</if>
                                            <td class="button-column">
                                            	<a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="修改设备" href="{pigcms{:U('door_eidt',array('door_id'=>$vo['door_id']))}">修改设备</a>
                                                <a style="width:80px;height:26px;line-height:20px;" class="label label-sm label-info" title="查看用户" href="{pigcms{:U('door_user',array('door_id'=>$vo['door_id']))}">查看用户</a>
                                            </td>
                                        </tr>
                                    </volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="11" >暂无数据。</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<include file="Public:footer"/>
