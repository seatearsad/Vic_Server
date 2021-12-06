<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_CREDIT_CARD_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.peter.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
    <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
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
        .address-wrapper {
            border-bottom: 2px solid #e5e5e5;
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
	        color: #ffa52d;
	    }
	
	    .confirmlist li:last-child {
	        border-right: none;
	    }

        .main{
            width: 100%;
            padding-top: 60px;
            max-width: 640px;
            min-width: 320px;
            margin: 0 auto;
            padding-bottom: 40px;
        }
        .gray_line{
            width: 100%;
            height: 2px;
            margin-top: 15px;
            margin-bottom: 15px;
            background-color: #cccccc;
        }
        .this_nav{
            width: 100%;
            text-align: center;
            font-size: 1.8em;
            height: 30px;
            line-height: 30px;
            margin-top: 15px;
            position: relative;
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
        .btn-warning{
            background-color: #ffa52d;
        }
        .btn-warning:visited{
            color: white;
        }

        .address-wrapper{
            padding-left: 10px;
        }
        input.mt[type="radio"]:checked, input.mt[type="checkbox"]:checked{
            background-color: #ffa52d;
        }

    </style>
    <include file="Public:facebook"/>
</head>
<body id="index">
<include file="Public:google"/>
<include file="Public:header"/>
<div class="main">
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
                        <a class="react" href="{pigcms{:U('Pay/check',array('type'=>$_GET['type'],'order_id'=>$order_id,'card_id'=>$vo['id']))}">
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
			                    <h6>{pigcms{:L('_EXPRIRY_DATE_')}：</h6><p>{pigcms{:transYM($vo['expiry'])}</p>
			                </div>
			            </div>
			        <if condition="$order_id">
		            	</a>
		            </if>
		        </dd>
		        <dd>
	                <ul class="confirmlist">
                        <li><a class="blacktext react mj-del" href="{pigcms{:U('del_card',array('id'=>$vo['id']))}">{pigcms{:L('_B_PURE_MY_27_')}</a></li>
	                    <li><a class="orangetext react" href="{pigcms{:U('edit_card',array('id'=>$vo['id'],'order_id'=>$order_id))}">{pigcms{:L('_EDIT_TXT_')}</a></li>
	                </ul>
		        </dd>
		    </dl>
	    </volist>
</div>
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
            $('#back_span').click(function () {
                window.history.go(-1);
            });
		</script>
</div>
		<include file="Public:footer"/>
</body>
</html>