<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{:L('_TICKET_RECORD_')}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?211" charset="utf-8"></script>
    <style>
	    .titleImg{
			width:25px;
			height:25px;
			margin-right:10px;
	    }
	    .titleBorder{
			padding-bottom:10px;
			border-bottom:1px solid #e5e5e5;
			padding:10px 5px;
			font-size:14px;
	    }
	    .title{
			padding-top:12px;
			width:95%;
	    }
	    .imgRirht{
			float:right;
			margin-top:-19px;
			width:10px;
	    }
	</style>
        <include file="Public:facebook"/>
</head>
<body>
    <include file="Public:google"/>
	<div style="text-align:center;background-color:#fff;padding:10px 0;">
		<button id="transaction" style="font-size:16px;width:45%;background-color:#fff;border:1px solid #00c4ac;padding:8px;color:#00c4ac;margin-right:-4px;z-index:100;-moz-border-radius:6px 0 0 6px;-webkit-border-radius:6px 0 0 6px;border-radius:6px 0 0 6px;">{pigcms{:L('_B_PURE_MY_40_')}</button>
		<button id="integral" style="font-size:16px;width:45%;background-color:#00c4ac;border:1px solid #00c4ac;padding:8px;color:#fff;margin-left:-4px;-moz-border-radius:0 6px 6px 0;-webkit-border-radius:0 6px 6px 0;border-radius:0 6px 6px 0;">{pigcms{:L('_TICKET_TXT_')}</button>
	</div>
	<dl id="newList" style="padding:0 10px;background-color:#fff;margin-top:10px;margin-bottom:10px;"></dl>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			var page = 1;
			list();
			$('#transaction').on('click',function(){
				location.href =	"{pigcms{:U('transaction')}";
			});
			$('#integral').on('click',function(){
				location.href =	"{pigcms{:U('integral')}";
			});
			$(window).scroll(function(){
				if($(window).scrollTop() == $(document).height() - $(window).height()){
					$('#mais').remove();
					var jia	=	'';
    				jia	+=	'<div id="jia" class="text-center m-t m-b">{pigcms{:L("_LOADING_TXT_")}</div>';
    				$('#newList').append(jia);
					if($('#is_null').length < 1){
						destination	=	$('#destination').text();
						ride_price	=	$('#ride_price').text();
						remain_number	=	$('#remain_number').text();
						list();
					}else{
						$('#jia').remove();
					}
				}
			});
			function list(){
				$.ajax({
					type : "post",
					url : "{pigcms{:U('integral_json')}",
					dataType : "json",
					data:{
						page	:	page,
					},
					async:false,
					success : function(result){
						var rideList	=	'';
						if(result){
							var	ride_list	=	result.score_list;
							if(ride_list){
								var	ride_list_length	=	ride_list.length;
								page++;
								for(var x=0;x<ride_list_length;x++){
									rideList	+=	'<div class="titleBorder">';
									rideList	+=	'	<div style="float:left;width:70%;">'+ride_list[x].desc+'</div>';
									if(ride_list[x].type == 2){
										rideList	+=	'	<div style="float:right;width:30%;text-align:right;"> -'+ride_list[x].score+'</div>';
									}else{
										rideList	+=	'	<div style="float:right;width:30%;text-align:right;color:#e50000"> +'+ride_list[x].score+'</div>';
									}
									rideList	+=	'	<div style="clear:both;"></div>';
									rideList	+=	'	<div style="color:#bbb;font-size:12px;">'+ride_list[x].time_s+'</div>';
									rideList	+=	'</div>';
								}
								if(ride_list_length <= 9){
									rideList	+=	'<div id="is_null" style="text-align:center;padding:10px 0;">{pigcms{:L("_B_PURE_MY_49_")}</div>';
								}else{
									rideList	+=	'<div id="mais" style="text-align:center;padding:10px 0;">{pigcms{:L("_B_PURE_MY_50_")}</div>';
								}
							}else{
								rideList	+=	'<div id="is_null" style="text-align:center;padding:10px 0;">{pigcms{:L("_B_PURE_MY_49_")}</div>';
							}
						}else{
							rideList	+=	'<div id="is_null" style="text-align:center;padding:10px 0;">{pigcms{:L("_B_PURE_MY_49_")}</div>';
						}
						$('#jia').remove();
						$('#newList').append(rideList);
					},
					error:function(){
                        alert("{pigcms{:L('_B_PURE_MY_WRONGPAGE_')}");
					}
				})
			}
		</script>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
		{pigcms{$shareScript}
	</body>
</html>