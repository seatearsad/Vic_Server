<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>技师介绍</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />
		<link rel="stylesheet" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" href="{pigcms{$static_path}css/worker_intro.css" />

		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<style>
			img{
				width: 100%;
				height: 100%;
			}
		</style>
	</head>

	<body>
		<div class="container">
			<div class="intro-top">
				<div class="intro-head"><img src="{pigcms{$config.site_url}/upload/appoint/{pigcms{$worker_detail['avatar_path']}"></div>
				<p>{pigcms{$worker_detail['name']}</p>
				<p class="intro-detail">{pigcms{$worker_detail['desc']|html_entity_decode}<span>&nbsp;&nbsp;&nbsp;&nbsp;共服务{pigcms{$worker_detail['order_num']}次</span></p>
				<p>总体印象：
					<for start='0' end='$worker_detail["all_avg_score"]'>
						<img src="{pigcms{$static_path}images/star.png" class="intro-star" />
					</for><for start='0' end='5 - $worker_detail["all_avg_score"]'>
						<img src="{pigcms{$static_path}images/star_2.png" class="intro-star" />
					</for> 
				</p>
			</div>

			<div class="intro-comment">
				<ul>
					<if condition='$comment_list'>
						<li><img src="{pigcms{$static_path}images/intro-mark.png">
							<p>用户评价<span>（{pigcms{:count($comment_list)}人评价）</span></p>
						</li>
						
						<volist name='comment_list' id='comment'>
							<li>
								<p>
								<for start='0' end='$comment["avg_score"]'>
									<img src="{pigcms{$static_path}images/star.png" class="intro-star" />
								</for><for start='0' end='5 - $comment["avg_score"]'>
									<img src="{pigcms{$static_path}images/star_2.png" class="intro-star" />
								</for> 
								<span>{pigcms{$comment['nickname']}（{pigcms{$comment['add_time']|date='Y/m/d',###}）</span>
									<span class="intro-content">{pigcms{:msubstr($comment['content'],0,20)}</span>
								</p>
							</li>
						</volist>
					<else />
						<li><img src="{pigcms{$static_path}images/intro-mark.png">
							<p>用户评价<span></span></p>
						</li>
						<li>
							<p>
								<span class="intro-content">暂无评论</span>
							</p>
						</li>
					</if>
				</ul>
			</div>

			<div class="intro-comment">
				<ul>
					<li><img src="{pigcms{$static_path}images/intro-3.png">
						<p>技师排班表</p>
					</li>
				</ul>

				<section id="service-date">
		<div class="yxc-pay-main yxc-payment-bg pad-bot-comm">
            <div class="yxc-time-con number-{pigcms{:count($timeOrder)}">
				<volist name="timeOrder" id="timeOrderInfo">
					<dl <if condition="$i eq count($timeOrder)">class="last"</if>>
						<dt <if condition="$i eq 1">class="active"</if> data-role="date" data-text="<if condition="$key eq date('Y-m-d')" > 今天<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
	<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天<else />{pigcms{$key}
								</if>" data-date="{pigcms{$key}">
								<if condition="$key eq date('Y-m-d')" > 今天
								<elseif condition="$key eq date('Y-m-d',strtotime('+1 day'))" />明天
								<elseif condition="$key eq date('Y-m-d',strtotime('+2 day'))" />后天
								<else />
								</if>
							<span>{pigcms{$key}</span>
						</dt>
					</dl>
				</volist>
			</div>
            
			<div class="yxc-time-con number-{pigcms{:count($timeOrder)}">
			</div>
			<div class="yxc-time-con" data-role="timeline" id="worker_time">
				<volist name="timeOrder" id="timeOrderInfo">
					<div class="date-{pigcms{$key} timeline" <if condition="$i neq 1">style='display:none'</if> >
					   <volist name="timeOrderInfo" id="vo">
							<dl>
								<dd data-role="item" onclick="location.href='{pigcms{:U('order',array('appoint_id'=>$_GET['appoint_id'],'merchantWorkerId'=>$_GET['merchant_worker_id'],'now_date'=>$vo['now_date'],'now_time'=>$vo['start']))}'" data-peroid="{pigcms{$vo['start']}" <if condition="$vo['order'] eq 'no' || $vo['order'] eq 'all' ">class="disable"</if>>{pigcms{$vo['start']}<br>
								<if condition="$vo['order'] eq 'no' ">不可预约<elseif condition="$vo['order'] eq 'all' " />已约满<else />可预约</if></dd>
							</dl>
						</volist>
					</div>
				</volist>
            </div>
		</div>
	</section>
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}js/intro.js" ></script>
	</body>

</html>