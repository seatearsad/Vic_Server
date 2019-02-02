<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>快递代收列表</title>
        </if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
        <style type="text/css">
		p{ font-size:12px;}
        .village_my nav.order_list section p{ padding-left:0;}
		.village_my nav.order_list section p .red{ color:red}
		.village_my nav.order_list section p .green{ color:green}
        </style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>快递代收列表</header>
    </if>
		<div id="container">
			<div id="scroller" class="village_my">
				<if condition="$list">
					<nav class="order_list">
						<volist name="list" id="vo">
							<section>
                            	<p><span>快递单号：</span>{pigcms{$vo.express_no}&nbsp;&nbsp;<span>快递类型：</span>{pigcms{$vo.express_name}</p>
								<p><span>到件时间：</span>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</p>
								<if condition="$vo.order_info.send_time gt 0"><p><span>预约送件时间：</span>{pigcms{$vo.order_info.send_time|date='Y-m-d H:i',###}</p></if>
								<if condition="$vo.money gt 0"><p><span>送件费用：</span>{pigcms{$vo.money}元<php>if($vo['order_info']['paid']){</php><font color="green">(已支付)</font><php>}else{</php></php><font color="red">(未支付)</font><php>}</php></p></if>
                                <if condition='$vo["delivery_time"]'><p><span>取件时间：</span>{pigcms{$vo.delivery_time|date='Y-m-d H:i:s',###}</p></if>
                                <if condition='$vo["status"] eq 0'>
                                	<p><span>状&nbsp;&nbsp;态：</span><span class="red">未取件</span>&nbsp;&nbsp;
									
									</p>
                                <elseif condition='$vo["status"] eq 1' />
                                	<p><span>状&nbsp;&nbsp;态：</span><span class="green">已取件（业主）</span></p>
                                <else />
                                	<p><span>状&nbsp;&nbsp;态：</span><span class="green">已取件（社区）</span></p>
                                </if>
                                <if condition='$vo["memo"]'><p><span>备注：</span>{pigcms{$vo.memo}</p></if>
									
								<p>
								<if condition='($vo["status"] eq 0) && (empty($vo["order_info"]["paid"])) && ($express_config["status"] eq 1)'>
									<button onClick="location.href='{pigcms{:U('express_appoint',array('id'=>$vo['id'],'village_id'=>$_GET['village_id']))}'">预约上门送件</button>
									<button onClick="chk_express({pigcms{$vo.id})">确认取件</button>
								</p>
								
								<elseif condition='!empty($vo["order_info"]) && ($vo["order_info"]["paid"] eq 1) && ($vo["order_info"]["status"] eq 0)' />
									<p><span class="green">已在派送中</span>
									<button onClick="chk_express({pigcms{$vo.id})">确认取件</button>
								</p> 
								</if>
								
							</section>
						</volist>
					</nav>
				<else/>
					<div class="noMoreDiv" style="margin-top:20px;background:#ebebeb;">暂无快递代收数据</div>
				</if>
                <if condition="!$is_app_browser">
                    <div id="pullUp" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
			</div>
		</div>
        <script type="text/javascript">
        	function chk_express(id){
				  console.log('sss')
					
					layer.open({
						content: '确认取件？',
						btn: ['确定', '取消'],
						shadeClose: false,
						yes:function(){									
							var url= '{pigcms{:U("express_edit")}';
							var village_id= "{pigcms{$_GET['village_id']}";
							var status = 1;
							$.post(url,{'id':id,'status':status,village_id:village_id},function(data){
								layer.open({
									content: data.msg,
									btn: ['确定'],
								});
								if(data.status == 1){
									location.reload();
								}
							},'json')
						
						}
					});
					  
				 // layer.open({
					// content: '确认取件？'
					// ,btn: ['确定', '取消']
					// ,yes: function(index){
					  
						// var url= '{pigcms{:U("express_edit")}';
						// var village_id= "{pigcms{$_GET['village_id']}";
						// var status = 1;
						// $.post(url,{'id':id,'status':status,village_id:village_id},function(data){
							// if(data.status == 1){
								// alert(data.msg);
								// location.reload();
							// }else{
								// alert(data.msg);
							// }
						// },'json')
					// }
				  // });
			}
        </script>
		{pigcms{$shareScript}
	</body>
</html>