<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{:L('_STORE_CENTER_')}</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_public}js/laytpl.js"></script>
	<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<style>
	.startOrder{color: #fff;float: right;background: green;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: left}
	.stopOrder{color: #000;float: right;background: #ccc;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: left}
	.addorder{color: #000;float: right;color: #fff;background-color: #06c1ae;;padding: 10px 0px 10px 0px;width:50%;text-align:center;float: right}
</style>
    <style>
	    dl.list dd.dealcard {
	        overflow: visible;
	        -webkit-transition: -webkit-transform .2s;
	        position: relative;
	    }
	    .dealcard.orders-del {
	        -webkit-transform: translateX(1.05rem);
	    }
	    #orders .dealcard-block-right {
			margin-left:1px;
	        position: relative;
	    }
	    .dealcard .dealcard-brand {
	        margin-bottom: .18rem;
	    }
	    .dealcard small {
	        font-size: .24rem;
	        color: #9E9E9E;
	    }
	    .dealcard weak {
	        font-size: .24rem;
	        color: #999;
	        position: absolute;
	        bottom: 0;
	        left: 0;
	        display: block;
	        width: 100%;
	    }
	    .dealcard weak b {
	        color: #FDB338;
	    }
	    .dealcard weak a.btn{
	        margin: -.15rem 0;
	    }
	    .dealcard weak b.dark {
	        color: #fa7251;
	    }
	    .hotel-price {
	        color: #ff8c00;
	        font-size: .24rem;
	        display: block;
	    }
	    .del-btn {
	        display: block;
	        width: .45rem;
	        height: .45rem;
	        text-align: center;
	        line-height: .45rem;
	        position: absolute;
	        left: -.85rem;
	        top: 50%;
	        background-color: #EC5330;
	        color: #fff;
	        -webkit-transform: translateY(-50%);
	        border-radius: 50%;
	        font-size: .4rem;
	    }
	    .no-order {
	        color: #D4D4D4;
	        text-align: center;
	        margin-top: 1rem;
	        margin-bottom: 2.5rem;
	    }
	    .icon-line {
	        font-size: 2rem;
	        margin-bottom: .2rem;
	    }

	    .order-icon {
	        display: inline-block;
	        width: .5rem;
	        height: .5rem;
	        text-align: center;
	        line-height: .5rem;
	        border-radius: .06rem;
	        color: white;
	        margin-right: .25rem;
	        margin-top: -.06rem;
	        margin-bottom: -.06rem;
	        background-color: #F5716E;
	        vertical-align: initial;
	        font-size: .3rem;
	    }
	    .order-all {
	        background-color: #2bb2a3;
	    }
	    .order-zuo,.order-jiudian {
	        background-color: #F5716E;
	    }
	    .order-fav {
	        background-color: #0092DE;
	    }
	    .order-card {
	        background-color: #EB2C00;
	    }
	    .order-lottery {
	        background-color: #F5B345;
	    }
	    .color-gray{
	    	color:gray;
	    	border-color:gray;
	    }
	    .color-gray:active{
	    	background-color:gray;
	    }
		#nav-dropdown{height: 1.7rem;}
		#filtercon select{height: 100%;line-height: normal;width:100%;}
		#filtercon{margin: 0 .15rem;}
.find_div {
margin: .15rem 0;
}
	#filtercon input{background-color: #fff;
		width: 100%;
		border: none;
		background: rgba(255, 255, 255, 0);
		outline-style: none;
		display: block;
		line-height: .28rem;
		height: 100%;
		font-size: .28rem;
		padding: 0
}
		#find_submit{
			position: absolute;
			right: 0rem;
			top: .15rem;
			width: 1.2rem;
			height: .7rem;;
			-webkit-box-sizing: border-box;
		}
 .dealcard-block-right li{
    font-size: .266rem;
font-weight: 400;
 }
.dealcard-block-right .dth{font-weight: bold;}
 .ulrightdiv{
	float: right;
	position: relative;
	top: -60px;
	margin-right: 15px;
	}
	dl.list .dd-padding{padding: .28rem 0.1rem;}
	.red{color:red;}
.top-btn-a a{color: #fff;margin-top: 10px;}
.top-btn-a .lb{margin-left: 20px;}
.top-btn-a .rb{float: right;margin-right: 20px;}
.dealcard-block-right{padding: 0 10px;}
#orders a{color: #333;}
#orders .td a{color: green;}
.find_type_div{
	position: absolute;
left: 0rem;
width: 1.7rem;
height: .7rem;
text-align: center;
background: white;
}
.find_txt_div{
vertical-align: middle;
position: relative;
margin-right: 1.3rem;
margin-left:1.8rem;
border-radius: .06rem;
border: 1px #CCC solid;
height: .7rem;
line-height: .7rem;
}
  .dealcard-block-right li.btm_li{
     margin-bottom: .18rem;
 }

.store_name{
    height: 20px;
    margin-left: 105px;
    margin-top: -100px;
}
.time_list{
    margin-top:.2rem;
    width: 98%;
    margin-left: 1%;
    height: 400px;
}
.time_list ul{
    width: 100%;
}
.time_list ul li{
    width: 100%;
    text-align: center;
    height: 40px;
    line-height: 20px;
    color: #999999;
    margin: 0px;
    display: inline-block;
}
.time_list ul li input{
    width: 80%;
}
.add_c{
    width: 100px;
    height: 30px;
    background-color: #0A8DE4;
    text-align: center;
    line-height: 30px;
    margin: 20px auto;
    color: #ffffff;
    cursor: pointer;
}
.add_val{
    width: 120px;
    height: 20px;
    background-color: #0A8DE4;
    text-align: center;
    line-height: 20px;
    margin: 0px auto;
    color: #ffffff;
    cursor: pointer;
}
#pro_val span:hover{
    color: #FF0000;
    cursor: pointer;
}

</style>
</head>
<body>
    <div class="time_list">
        <ul>
            <input style="display: none" type="text" name="goods_id" value="{pigcms{$goods_id}" >
            <input style="display: none" type="text" name="pro_id" value="{pigcms{$pro_id}" >
            <if condition="!$pro_id">
            <li>
                {pigcms{:L('_STORE_ADD_PROPERTIES_')}
            </li>
            </if>
            <li>
                <input type="text" placeholder="{pigcms{:L('_STORE_PRO_NAME_')}({pigcms{:L('_STORE_REQUIRED_')})" name="name" value="{pigcms{$pro.name}">
            </li>
            <li>
                <input type="text" placeholder="{pigcms{:L('_STORE_PRO_SELECT_')}" name="num" value="{pigcms{$pro.num}">
            </li>
            <li>
                <div>{pigcms{:L('_STORE_PRO_VALUE_')}</div>
                <div class="add_val">{pigcms{:L('_STORE_ADD_PRO_VAL_')}</div>
            </li>
            <li id="pro_val" style="margin-top: 10px">
                <if condition="$pro">
                    <volist name="pro['value']" id="vo">
                        <input type="text" placeholder="{pigcms{:L('_STORE_PRO_VALUE_')}" id="val_{pigcms{$i}" value="{pigcms{$vo}"> <span data-id="{pigcms{$i}">X</span>
                    </volist>
                <else/>
                    <input type="text" placeholder="{pigcms{:L('_STORE_PRO_VALUE_')}" id="val_1"> <span data-id="1">X</span>
                </if>
            </li>
            <li>
                <div class="add_c">
                    {pigcms{:L('_STORE_SAVE_')}
                </div>
            </li>
        </ul>
    </div>
</body>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    var val_num = "{pigcms{$val_num}";

    var new_id = "{pigcms{$new_id}";
    if(new_id != ''){
        var data = artDialog.open.origin.getNewData(new_id);
        $('input[name=name]').val(data['name']);
        $('input[name=num]').val(data['num']);
        $('#pro_val').find('input').remove();
        var val_list = data['val'].split(',');
        val_num = val_list.length;
        var input_html = '';
        for(var i=0;i<val_list.length;i++){
            var id_num = i+1;
            input_html += '<input type="text" placeholder="{pigcms{:L(\'_STORE_PRO_VALUE_\')}" id="val_'+id_num+'" value="'+val_list[i]+'"> <span data-id="'+id_num+'">X</span>';
        }
        $('#pro_val').html(input_html);
    }


    $('.add_val').click(function () {
        val_num = parseInt(val_num) + 1;
        var in_val = '<input type="text" placeholder="{pigcms{:L(\'_STORE_PRO_VALUE_\')}" id="val_'+val_num+'"> <span data-id="'+val_num+'">X</span>';
        $('#pro_val').append(in_val);
        link_click();
    });

    link_click();

    function link_click(){
        $('#pro_val').find('span').each(function () {
            $(this).click(function () {
                delVal($(this).attr('data-id'),$(this));
            });
        });
    }

    function delVal(val_id,del_span){
        del_span.remove();
        $('#val_'+val_id).remove();
    }

    function checkTable(){
        var is_tip = false;
        if($('input[name=name]').val() == ''){
            is_tip = true;
        }
        if($('input[name=num]').val() == ''){
            is_tip = true;
        }
        var val_str = getVal();
        if(val_str == ''){
            is_tip = true;
        }

        return is_tip;
    }

    function getVal(){
        var str = '';
        $('#pro_val').find('input').each(function () {
            var t_v = $(this).val();
            if(t_v != ''){
                if(str == '')
                    str = t_v;
                else
                    str = str + ',' + t_v;
            }
        });

        return str;
    }

    $('.add_c').click(function () {
        var is_tip = checkTable();
        if(is_tip){
            layer.open({
                title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                time: 1,
                content: "{pigcms{:L('_PLEASE_INPUT_ALL_')}"
            });
        }else {
            var pro_id = $('input[name=pro_id]').val();
            if (pro_id != '') {//修改属性
                var post_data = {
                    'id': pro_id,
                    'goods_id': $('input[name=goods_id]').val(),
                    'name': $('input[name=name]').val(),
                    'num': $('input[name=num]').val(),
                    'val':getVal()
                };
                $.post("{pigcms{:U('Storestaff/goods_pro_edit')}", post_data, function (result) {
                    layer.open({
                        title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                        content: result.info,
                        time: 1,
                        end: function () {
                            artDialog.open.origin.updatePro(post_data);
                            art.dialog.close();
                        }
                    });
                });
            } else {//新添属性
                var data = {
                    'name': $('input[name=name]').val(),
                    'num': $('input[name=num]').val(),
                    'val': getVal()
                };
                if(new_id != ''){
                    artDialog.open.origin.addNewProAndId(data,new_id);
                }else {
                    artDialog.open.origin.addNewPro(data);
                }
                art.dialog.close();
            }
        }
        //artDialog.open.origin.testFun();
        //art.dialog.close();
        // if($('input[name=cate_name_en]').val() == ''){
        //     $('input[name=cate_name_en]').focus();
        //     layer.open({
        //         title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
        //         time: 1,
        //         content: "{pigcms{:L('_STORE_PLEASE_CATENAME_')}"
        //     });
        // }else {
        //     var data = {
        //         'cate_name_en': $('input[name=cate_name_en]').val(),
        //         'cate_name_cn': $('input[name=cate_name_cn]').val()
        //     };
        //     $.post("{pigcms{:U('Storestaff/manage_edit_cate')}", data, function (result) {
        //         layer.open({
        //             title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
        //             content: result.info,
        //             time: 1,
        //             end: function () {
        //                 artDialog.open.origin.location.reload();
        //             }
        //         });
        //
        //     });
        //}
    });
</script>
</html>