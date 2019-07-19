<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>{pigcms{:L('_BALANCE_RECORD_')}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width,viewport-fit=cover"/>
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
        .main{
            width: 100%;
            padding-top: 60px;
        }

        .gray_line{
            width: 100%;
            height: 2px;
            margin-top: 15px;
            background-color: #cccccc;
        }
        .gray_k{
            width: 10%;
            height: 2px;
            background-color: #f4f4f4;
            margin: -2px auto 0 auto;
        }
        .main ul{
            margin: 20px 0 0;
            width: 100%;
        }
        .main ul li{
            width: 90%;
            height: 50px;
            margin-left: 5%;
            background-color: white;
            list-style: none;
            margin-bottom: 10px;
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 16px;
            background-repeat: no-repeat;
            background-position:right 10px center;
        }
        .main ul li div{
            line-height: 50px;
            font-size: 1.4em;
            padding-left: 20px;
            background-size: auto 70%;
            background-repeat: no-repeat;
            background-position: 10px center;
        }
        .this_nav{
            width: 100%;
            text-align: center;
            font-size: 1.8em;
            height: 30px;
            line-height: 30px;
            margin-top: 15px;
        }
        .this_nav span{
            width: 50px;
            height: 30px;
            display:-moz-inline-box;
            display:inline-block;
            -moz-transform:scaleX(-1);
            -webkit-transform:scaleX(-1);
            -o-transform:scaleX(-1);
            transform:scaleX(-1);
            background-image: url("./tpl/Static/blue/images/new/black_arrow.png");
            background-size: auto 20px;
            background-repeat: no-repeat;
            background-position: right center;
            position: absolute;
            left: 8%;
            cursor: pointer;
        }
	</style>
        <include file="Public:facebook"/>
</head>
<body>
		<if condition="$_SESSION['source'] neq 1">
	<!--div style="text-align:center;background-color:#fff;padding:10px 0;">
		<button id="transaction" style="font-size:16px;width:45%;background-color:#00c4ac;border:1px solid #00c4ac;padding:8px;color:#fff;margin-right:-4px;z-index:100;-moz-border-radius:6px 0 0 6px;-webkit-border-radius:6px 0 0 6px;border-radius:6px 0 0 6px;">{pigcms{:L('_B_PURE_MY_40_')}</button>
	
		<button id="integral" style="font-size:16px;width:45%;background-color:#fff;border:1px solid #00c4ac;padding:8px;color:#00c4ac;margin-left:-4px;-moz-border-radius:0 6px 6px 0;-webkit-border-radius:0 6px 6px 0;border-radius:0 6px 6px 0;">{pigcms{:L('_TICKET_TXT_')}</button>
	</div-->
		</if>
        <include file="Public:header"/>
        <div class="main">
            <div class="this_nav">
                <span id="back_span"></span>
                History
            </div>
            <div class="gray_line"></div>
	<dl id="newList" style="padding:0 10px;background-color:#fff;margin:10px auto;width: 98%"></dl>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
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
				if($(window).scrollTop() == $(document).height() - ($(window).height()*page)){
					$('#mais').remove();
					var jia	=	'';
    				jia	+=	'<div id="jia" class="text-center m-t m-b">{pigcms{:L("_LOADING_TXT_")}</div>';
    				$('#newList').append(jia);
					if($('#is_null').length < 1){
						destination	=	$('#destination').text();
						ride_price	=	$('#ride_price').text();
						remain_number	=	$('#remain_number').text();
                        page++;
						list();
					}else{
						$('#jia').remove();
					}
				}
			});
			function list(){
				$.ajax({
					type : "post",
					url : "{pigcms{:U('transaction_json')}",
					dataType : "json",
					data:{
						page	:	page,
					},
					async:false,
					success : function(result){
						var rideList	=	'';
						if(result){
							var	ride_list	=	result.money_list;
							if(ride_list){
								var	ride_list_length	=	ride_list.length;

								for(var x=0;x<ride_list_length;x++){
									rideList	+=	'<div class="titleBorder" style="padding:10px 5px;font-size:14px;">';
									rideList	+=	'	<div style="float:left;width:70%;">'+ride_list[x].desc+'</div>';
									if(ride_list[x].type == 2){
										rideList	+=	'	<div style="float:right;width:30%;text-align:right;">$ -'+ride_list[x].money+'</div>';
									}else{
										rideList	+=	'	<div style="float:right;width:30%;text-align:right;color:#ffa52d">$ +'+ride_list[x].money+'</div>';
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
            $('#back_span').click(function () {
                window.history.go(-1);
            });
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
        </div>
        <include file="Public:footer"/>
	</body>
</html>