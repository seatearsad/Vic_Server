<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{:L('_B_PURE_MY_46_')} | {pigcms{:L('_VIC_NAME_')}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}coupon/css/card_new.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script type="text/javascript" src="{pigcms{$static_public}js/lang/{pigcms{:C('DEFAULT_LANG')}.js" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/common.js"></script>
<!--script src="{pigcms{$static_path}js/category.js"></script-->
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
</head>
<body id="settings" class="has-order-nav" style="position:static;">
<include file="Public:header_top"/>
 <div class="body pg-buy-process" style="margin: 0 auto">
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top">{pigcms{:L('_ALL_CLASSIF_')}</div>
					<div class="list">
						<!--ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul-->
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{:lang_substr($vo['name'],C('DEFAULT_LANG'))}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<include file="Public:scroll_msg"/>
		<div id="bdw" class="Coupon">
			<div id="bd" class="cf">
				<include file="Public:sidebar"/>
				<div id="content" class="coupons-box">
					<div class="mainbox mine" style="background-color: #f4f4f4">
						<ul class="filter cf">
							<li class="current">{pigcms{:L('_B_PURE_MY_46_')}</li>
						</ul>
						<div class="address-div">
							<div class="table-section">
                                <div style="width:350px; margin: 50px auto 10px;">
                                    <span>{pigcms{:L('_INPUT_EXCHANGE_CODE_')}：</span><input style="height: 24px; width: 150px;" type="text" class="input" name="code" value=""  autocomplete="off">
                                </div>
                                <div id="exchange" style="width:150px;margin: 20px auto;border: 1px solid;text-align: center;cursor: pointer">
                                    {pigcms{:L('_EXCHANGE_TXT_')}
                                </div>
							</div>
						</div>
						{pigcms{$pagebar}
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
     <script>
         $("#exchange").click(function(){
             var code = $("input[name='code']").val();
             if(code == ""){
                 alert("{pigcms{:L('_INPUT_EXCHANGE_CODE_')}");
             } else{
                 exchange_code(code);
             }
         })

         function exchange_code(code){
             $.ajax({url:"{pigcms{:U('Coupon/exchangeCode')}",type:"post",data:"code="+code,dataType:"json",success:function(data){
                     if(data.error_code == 0){
                         alert('success');
                         window.location.reload();
                     }else{
                         alert(data.msg);
                     }
                 }
             });
         }
     </script>
	<include file="Public:footer"/>

	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBt3Lu-FlQE5LgusybLzqGr8lIXmvTsLZU&libraries=places" async defer></script>
</body>
</html>
