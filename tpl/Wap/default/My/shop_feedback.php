<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
<meta name="format-detection" content="telephone=no">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="keywords" content="{pigcms{$config.seo_keywords}">
<meta name="description" content="{pigcms{$config.seo_description}">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/index.js"></script>
<script src="{pigcms{$static_path}js/shop_reply.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<title>{pigcms{:L('_B_MY_COMMENT_')}</title>
</head>
<body class=" hPC" style="padding-bottom: initial; background:#fff;">
<div id="page-loader" style="width: 100%; height: 100%; position: fixed; top: 0px; left: 0px; z-index: 10000; text-align: center; display: none; background-color: rgba(200, 200, 200, 0.2);">
    <div style="margin-top:200px;color: white;background-color: rgba(30,30,30,0.8);padding: 10px;width: 140px;margin-left: auto;margin-right: auto;border-radius: 5px;font-size: 14px;  font-family: &#39;Helvetica&#39;;-webkit-box-shadow: 1px 1px 2px rgba(0,0,0,.4);">{pigcms{:L('_LOADING_TXT_')}</div>
</div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_5e96991.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/shop_57c5f10.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/shopcomment_4bd95ec.css">
<style>
    .comment-btn,
    #widget-shopcomment-add .add-list .add-list-span .select{
        background: #ffa52d;
    }
</style>
<div id="pager"> <img src="{pigcms{$static_path}images/hm.gif" width="0" height="0" style="display:block">
    <div id="wrapper" style="background:#fff">
        <div id="fis_elm__2">
            <div id="common-widget-nav" class="common-widget-nav ">
                <div class="left-slogan"> <a class="left-arrow icon-arrow-left2" data-node="navBack" id="goBackUrl" href="javascript:history.go(-1);"></a> </div>
                <div class="center-title"> <a href="javascript:void(0)">{pigcms{:L('_B_MY_COMMENT_')}</a> </div>
                <div class="right-slogan "> </div>
            </div>
        </div>
        <div id="fis_elm__3">
            <div id="shopcomment-add-wrapper">
                <div id="widget-shopcomment-add">
                    <div class="gradecon" id="Addnewskill_119">
                        <ul class="rev_pro clearfix">
                            <li class="clearfix"> <span class="revtit">{pigcms{:L('_RATE_TXT_')}</span>
                                <div class="revinp">
                                	<span class="level whole">
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                	</span> 
                                	<span class="revgrade"></span>
                                </div>
                            </li>
                            <!--li class="clearfix"> <span class="revtit">高品质量</span>
                                <div class="revinp"> 
                                	<span class="level mass"> 
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                		<i class="level_solid" cjmark=""></i> 
                                		</span> 
                                	<span class="revgrade">优</span> 
                                </div>
                            </li>
                            <li class="clearfix"> <span class="revtit">配送服务</span>
                                <div class="revinp"> 
	                                <span class="level send"> 
		                                <i class="level_solid" cjmark=""></i> 
		                                <i class="level_solid" cjmark=""></i>
		                                <i class="level_solid" cjmark=""></i> 
		                                <i class="level_solid" cjmark=""></i> 
		                                <i class="level_solid" cjmark=""></i> 
	                                </span> 
	                           		<span class="revgrade">优</span>
	                           </div>
                            </li-->
                        </ul>
                    </div>
                    <div class="add-list">
                        {pigcms{:L('_B_MY_COMMENT_')}
                        <textarea class="text-area comment-desc"></textarea>
                    </div>
                    <div class="add-list">
                        <div class="add-list-title">{pigcms{:L('_THOMB_YOUR_FAV_')}</div>
                        <div class="add-list-span recommend-list">
                        <volist name="now_order['info']" id="vo">
                        	<span data-goods-id="{pigcms{$vo.goods_id}">{pigcms{$vo.name}</span>
                        </volist>
                        </div>
                    </div>
                </div>
                <div class="comment-btn" shop_id="{pigcms{$now_order.store_id}" order_id="{pigcms{$now_order.order_id}" data-node="comment-btn">{pigcms{:L('_B_D_LOGIN_SUB_')}</div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
$(document).ready(function(){

	$('.comment-btn').click(function(){
// 		var mass = $(".mass").children(".level_solid").length;
// 		var send = $(".send").children(".level_solid").length;
		var whole = $(".whole").children(".level_solid").length;
		var textAre = $(".comment-desc").val();
		var oid = $(this).attr("order_id");
// 		var sid = $(this).prop("shop_id");
		var goods_ids = new Array();

		$('.select').each(function(i){
			goods_ids[i] = $(this).attr('data-goods-id');
		});
// 		var minutes = $(".minutes").attr("time");
		var postData = {'whole':whole, 'textAre':textAre, 'order_id':oid, 'goods_ids':goods_ids};
		$.post("{pigcms{:U('My/add_comment')}", postData, function(data) {
            if (data.status == 1) {
                layer.open({
                    title: "",
                    content: '' + data.msg + '',
                    btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"],
                    end: function () {
                        window.location.href = data.url;
                    }
                });
            } else {
                layer.open({
                    title: "",
                    content: '' + data.msg + '',
                    btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}"]
                });
            }
		}, 'json');
	});

    $(".add-list span").click(function(){
        $(this).toggleClass("select");
    });
});
</script>
<script>
// alert(navigator.userAgent.toLowerCase());
if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
	var reg = /versioncode=(\d+),/;
	var arr = reg.exec(navigator.userAgent.toLowerCase());
	if(arr == null){
		
	}else{
		var version = parseInt(arr[1]);
		if(version >= 50){
			if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
				$('#goBackUrl').click(function(){
					$('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none;"></iframe>');
					return false;
				});
			}else{
				$('#goBackUrl').click(function(){
					window.lifepasslogin.webViewGoBack();
					return false;
				});
			}
		}
	}
}
</script>
</html>