<!DOCTYPE html>
<html>

	<head>
		<title>选择技师</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />
	</head>
	<link rel="stylesheet" href="{pigcms{$static_path}css/common.css" />
	<link rel="stylesheet" href="{pigcms{$static_path}css/worker_list.css" />
	<body>
		<section class="listBox">

		<if condition='$merchant_worker_list'>
			<volist name='merchant_worker_list' id='worker'>
			
				<dl class="dealcard">
					<dd class="link-url" data-url="#">
						<div class="dealcard-img imgbox" onclick="location.href='{pigcms{:U('worker_detail',array('merchant_worker_id'=>$worker['merchant_worker_id'],'appoint_id'=>$_GET['appoint_id']))}'"> <img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$worker['avatar_path']}" alt="1213"> </div>
						<div class="dealcard-block-right" onclick="location.href='{pigcms{:U('worker_detail',array('merchant_worker_id'=>$worker['merchant_worker_id'],'appoint_id'=>$_GET['appoint_id']))}'">
							<div class="brand"> {pigcms{$worker['name']} </div>
							<div class="title">{pigcms{$worker['desc']|html_entity_decode}</div>
							<div class="price">共服务{pigcms{$worker['order_num']}次 </div>
						</div>
						<div class="dealcard-block-end-right">
							<a href="{pigcms{:U('order',array('merchantWorkerId'=>$worker['merchant_worker_id'],'appoint_id'=>$_GET['appoint_id']))}">选我</a>
						</div>
					</dd>
				</dl>
			</volist>
		</if>
		</section>
		
	</body>
</html>