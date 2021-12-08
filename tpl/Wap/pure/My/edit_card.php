<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_EDIT_CREDIT_CARD_')}</title>
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
	    .btn-wrapper {
	        margin: .2rem .2rem;
	        padding: 0;
	    }
	
	    dd>label.react {
	        padding: .28rem .2rem;
	    }
	
	    .kv-line h6 {
	        width: 10em;
            text-align: right;
	    }
		.btn {
			background: #ffa52d;
		}
		dl.list-in dd {
			border-bottom: 1px dashed #e5e5e5;
		}
        input.mt[type="checkbox"]:checked{
            background-color: #ffa52d;
        }
        .main{
            width: 100%;
            padding-top: 60px;
            max-width: 640px;
            min-width: 320px;
            margin: 0 auto;
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
	</style>
    <include file="Public:facebook"/>
</head>
<body id="index" data-com="pagecommon">
<include file="Public:google"/>
<include file="Public:header"/>
<div class="main">

        <div id="tips" class="tips"></div>
        <form id="form" method="post" action="{pigcms{:U('My/edit_card')}" class="detail_block">
		    <dl class="list list-in">
		    	<dd>
		    		<dl>
		        		<dd class="dd-padding kv-line">
		        			<input name="name" type="text" class="kv-v input-weak" placeholder="{pigcms{:L('_CREDITHOLDER_NAME_')}" pattern=".{2,}" data-err="{pigcms{:L('_B_PURE_MY_08_')}" value="{pigcms{$card.name}">
		        		</dd>
		        		<dd class="dd-padding kv-line">
		        			<input name="card_num" type="tel" class="kv-v input-weak" placeholder="{pigcms{:L('_CREDIT_CARD_NUM_')}" pattern="\d{3}[\d\*]{10,}" data-err="{pigcms{:L('_CREDIT_CARD_NUM_')}" value="{pigcms{$card.card_num}">
		        		</dd>
		        		<dd class="dd-padding kv-line">
		        			<input name="expiry" type="text" class="kv-v input-weak" placeholder="{pigcms{:L('_EXPRIRY_DATE_')}" pattern=".{4,}" data-err="{pigcms{:L('_EXPRIRY_DATE_')}" value="{pigcms{:transYM($card['expiry'])}">
		        		</dd>
		        		<dd>
			            	<label class="react">
                                <input type="checkbox" name="is_default" value="1" class="mt"  <if condition="$card"><if condition="$card['is_default']">checked="checked"</if><else />checked="checked"</if> />
			              		  {pigcms{:L('_DEFAULT_CARD_')}
			            	</label>
			        	</dd>
			    	</dl>
		   		</dd>
			</dl>
		    <div class="btn-wrapper">
                <input type="hidden" name="id" value="{pigcms{$card.id}">
				<button type="submit" class="btn btn-block btn-larger"><if condition="$card">{pigcms{:L('_B_PURE_MY_25_')}<else/>{pigcms{:L('_B_PURE_MY_26_')}</if></button>
				<if condition="$now_adress"><button type="button" class="btn btn-block btn-larger" style=" background:#fff; color:#000; margin-top:.1rem" id="address_del">{pigcms{:L('_B_PURE_MY_27_')}</button></if>
		    </div>
		</form>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/jquery.cookie.js"></script> 
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>
		<script>
			$(function(){
				$("select[name='province']").change(function(){
					show_city($(this).find('option:selected').attr('value'));
				});
				$("select[name='city']").change(function(){
					show_area($(this).find('option:selected').attr('value'));
				});
				$("#color-gray").click(function(){
					var detail = new Object();
					detail.name = $('input[name="name"]').val();
					detail.province = $('select[name="province"]').val();
					detail.area = $('select[name="area"]').val();
					detail.city = $('select[name="city"]').val();
					detail.defaul = $('input[name="default"]').val();
					detail.detail = $('input[name="detail"]').val();
					detail.zipcode = $('input[name="zipcode"]').val();
					detail.phone = $('input[name="phone"]').val();
					detail.id = $('input[name="adress_id"]').val();
					
					$.cookie("user_address", JSON.stringify(detail));
					location.href = "{pigcms{:U('My/adres_map', $params)}";
				});

				
				$('#form').submit(function(){
					$('#tips').removeClass('tips-err').empty();
					var form_input = $(this).find("input[type='text'],input[type='tel'],textarea");
					$.each(form_input,function(i,item){
						if($(item).attr('pattern')){
							var re = new RegExp($(item).attr('pattern'));
							if($(item).val().length == 0 || !re.test($(item).val())){
								$('#tips').addClass('tips-err').html($(item).attr('data-err'));
								return false;
							}
						}

						if(i+1 == form_input.size()){
							layer.open({type:2,content:"{pigcms{:L('_B_PURE_MY_28_')}"});
							$.post($('#form').attr('action'),$('#form').serialize(),function(result){
								layer.closeAll();
								if(result.status == 1){
									//return;
									<if condition="$_GET['order_id']">
										window.location.href="{pigcms{:U('Pay/check',array('type'=>'shop','order_id'=>$_GET['order_id'],'card_id'=>$card['id']))}";
									<else/>
										window.location.href="{pigcms{:U('My/credit',$params)}";
									</if>
								}else{
									$('#tips').addClass('tips-err').html(result.info);
								}
							});
						}
					});
			
					return false;
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