<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_EXCHANGE_COUPON_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no,viewport-fit=cover">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?215"/>
    <include file="Public:facebook"/>
</head>
<style>
    .main{
        width: 100%;
        padding-top: 60px;
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
    .btn{
        background-color: #ffa52d;
        width: 50%;
        margin: 0 auto;
    }
</style>
<body id="index">
    <include file="Public:header"/>
    <div class="main">
        <div class="this_nav">
            <span id="back_span"></span>
            {pigcms{:L('_EXCHANGE_COUPON_')}
        </div>
        <div class="gray_line"></div>
        <if condition="$error">
        	<div id="tips" class="tips tips-err" style="display:block;">{pigcms{$error}</div>
        <else/>
        	<div id="tips" class="tips"></div>
        </if>
		    <dl class="list">
		        <dd class="dd-padding">
		            <input placeholder="{pigcms{:L('_INPUT_EXCHANGE_CODE_')}" class="input-weak" type="text" name="code" value="">
		        </dd>
		    </dl>
		    <div class="btn-wrapper"><button type="submit" id="exchange" class="btn btn-block btn-larger">{pigcms{:L('_EXCHANGE_TXT_')}</button></div>
    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
            //garfunkel add
            $("#exchange").click(function(){
                var code = $("input[name='code']").val();
                if(code == ""){
                    alert("{pigcms{:L('_INPUT_EXCHANGE_CODE_')}");
                } else{
                    exchange_code(code);
                }
            })

            function exchange_code(code){
                $.ajax({url:"{pigcms{:U('My/exchangeCode')}",type:"post",data:"code="+code,dataType:"json",success:function(data){
                        if(data.error_code == 0){
                            alert('success');
                            window.location.reload();
                        }else{
                            alert(data.msg);
                        }
                    }
                });
            }
            $('#back_span').click(function () {
                window.history.go(-1);
            });
		</script>
    </div>
    <include file="Public:footer"/>
</body>
</html>