<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_CREDIT_CARD_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <style>
	    .address-container {
	        font-size: .3rem;
	        -webkit-box-flex: 1;
	    }
	    .kv-line h6 {
	        width: 10em;
	    }
	    .btn-wrapper {
	        margin: .2rem .2rem;
	        padding: 0;
	    }
	
	    .address-wrapper a {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .address-select {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	        padding-right: .2rem;
	        -webkit-box-align: center;
	        -webkit-box-pack: center;
	        -moz-box-align: center;
	        -moz-box-pack: center;
	        -ms-box-align: center;
	        -ms-flex-pack: justify;
	    }
	
	    .list.active dd {
	        background-color: #fff5e3;
	    }
	
	    .confirmlist {
	        display: -webkit-box;
	        display: -moz-box;
	        display: -ms-flex-box;
	    }
	
	    .confirmlist li {
	        -ms-flex: 1;
	        -moz-box-flex: 1;
	        -webkit-box-flex: 1;
	        height: .88rem;
	        line-height: .88rem;
	        border-right: 1px solid #C9C3B7;
	        text-align: center;
	    }
	
	    .confirmlist li a {
	        color: #2bb2a3;
	    }
	
	    .confirmlist li:last-child {
	        border-right: none;
	    }
	</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
        <div class="wrapper btn-wrapper">
            <if condition="$order_id">
                <a class="address-add btn btn-larger btn-warning btn-block" href="{pigcms{:U('My/edit_card',array('order_id'=>$order_id))}">{pigcms{:L('_ADD_CREDIT_CARD_')}</a>
            <else />
		        <a class="address-add btn btn-larger btn-warning btn-block" href="{pigcms{:U('My/edit_card')}">{pigcms{:L('_ADD_CREDIT_CARD_')}</a>
            </if>
		</div>
		<volist name="card_list" id="vo">
			<dl class="list <if condition="$vo['is_default']">active</if>">
                <dd class="address-wrapper <if condition="$order_id">dd-padding</if>">
                    <if condition="$order_id">
                        <a class="react" href="{pigcms{:U('Pay/check',array('type'=>'shop','order_id'=>$order_id,'card_id'=>$vo['id']))}">
                            <div class="address-select"><input class="mt" type="radio" name="card_s" <if condition="$vo['is_default']">checked="checked"</if>/></div>
                    </if>
			            <div class="address-container">
			                <div class="kv-line">
			                    <h6>{pigcms{:L('_CREDITHOLDER_NAME_')}：</h6><p>{pigcms{$vo.name}</p>
			                </div>
			                <div class="kv-line">
			                    <h6>{pigcms{:L('_CREDIT_CARD_NUM_')}：</h6><p>{pigcms{$vo.card_num}</p>
			                </div>
			                <div class="kv-line">
			                    <h6>{pigcms{:L('_EXPRIRY_DATE_')}：</h6><p>{pigcms{$vo.expiry}</p>
			                </div>
			            </div>
			        <if condition="$order_id">
		            	</a>
		            </if>
		        </dd>
		        <dd>
	                <ul class="confirmlist">
	                    <li><a class="react" href="{pigcms{:U('edit_card',array('id'=>$vo['id'],'order_id'=>$order_id))}">{pigcms{:L('_EDIT_TXT_')}</a></li>
                        <li><a class="react mj-del" href="{pigcms{:U('del_card',array('id'=>$vo['id']))}">{pigcms{:L('_B_PURE_MY_27_')}</a></li>
	                </ul>
		        </dd>
		    </dl>
	    </volist>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/jquery.cookie.js"></script> 
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			$(function(){
				$('.mj-del').click(function(){
					var now_dom = $(this);
					if(confirm("{pigcms{:L('_B_PURE_MY_84_')}")){
						$.post(now_dom.attr('href'),function(result){
							if(result.status == '1'){
								now_dom.closest('dl').remove();
							}else{
								alert(result.info);
							}
						});
					}
					return false;
				});
				$('.address-wrapper input.mt').click(function(){
					window.location.href = $(this).closest('a').attr('href');
				});
			});
		</script>
		<include file="Public:footer"/>

{pigcms{$hideScript}
</body>
</html>