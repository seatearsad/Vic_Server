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
}
.time_list ul{
    width: 100%;
}
.time_list ul li{
    width: 32%;
    text-align: center;
    height: 30px;
    line-height: 30px;
    border: 1px solid #999999;
    color: #999999;
    cursor: pointer;
    margin: 0px;
    display: inline-block;
}
.time_list ul li:hover{
    background-color: #ffa64d;
}
.table {
    width: 100%;
    max-width: 100%;
    margin-bottom: 20px;
    background-color: white;
}
.table-bordered, .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {
    border: 1px solid #ddd;
}
.table thead tr {
    color: #707070;
    font-weight: 400;
    background: #F2F2F2 repeat-x;
    background-image: none;
    background-image: -webkit-linear-gradient(top,#f8f8f8 0,#ececec 100%);
    background-image: -o-linear-gradient(top,#f8f8f8 0,#ececec 100%);
    background-image: linear-gradient(to bottom,#f8f8f8 0,#ececec 100%);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#fff8f8f8', endColorstr='#ffececec', GradientType=0);
}
.table tbody tr td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    padding: 8px;
    border-color: #ddd;
    border-top-color: rgb(221, 221, 221);
    border-left-color: rgb(221, 221, 221);
    font-weight: 700;
    text-align: center;
}
.table input{
    width: 80%;
    font-weight: normal;
}
.table textarea{
    width: 80%;
    height: 100px;
    font-weight: normal;
}
.submit{
    width: 100px;
    height: 30px;
    background-color: #ffa64d;
    text-align: center;
    line-height: 30px;
    margin: 20px auto;
    color: #ffffff;
    cursor: pointer;
}
.nav_list{
    margin: 0px;
}
.nav_list dd{
    width: 85px;
    height: 30px;
    float: left;
    text-align: center;
    line-height: 30px;
    cursor: pointer;
    background-color: white;
    margin: 0px;
    list-style: none;
    border-right: 1px solid #0A8DE4;
    border-top: 1px solid #0A8DE4;
    border-left: 1px solid #0A8DE4;
    font-size: 12px;
}
.nav_list .nav_act{
    border-top: 3px solid #0A8DE4;
    height: 33px;
    margin-top:-5px;
    color: #0A8DE4;
}
#upload_img{
    width: 80%;
    height: 30px;
    line-height: 30px;
    background-color: #ffa64d;
    color: white;
    cursor: pointer;
    text-align: center;
    display: inline-block;
}
input[type="file"] {
    position: absolute;
    display: block;
    opacity: 0;
    -ms-filter: 'alpha(opacity=0)';
}
.add_c{
    width: 100px;
    height: 30px;
    background-color: #ffa64d;
    text-align: center;
    line-height: 30px;
    margin-bottom: 10px;
    margin-top: 5px;
    color: #ffffff;
    cursor: pointer;
}
a{
    color: #ffa64d;
}
.pro_name_list{
    margin: 0px;
    width: 100%;
}
.pro_name_list dd{
    margin-left: 0px;
    line-height: 30px;
}
.edit_pro,.edit_pro_new,.del_pro,.del_pro_new{
    cursor: pointer;
}
.edit_pro:hover,.edit_pro_new:hover,.del_pro:hover,.del_pro_new:hover{
    color: #0e62cd;
}

.spec_act{
    background-color: #ffa64d;
    color: white;
}
.spec_main tr td{
    cursor: pointer;
}
.spec_edit_btn{
    width: 50px;

    height: 20px;
    line-height: 20px;
    border: 1px solid;
    cursor: pointer;
    float: left;
    margin: 20px auto 0px 10px;
}
.spec_del_btn{
    width: 50px;
    margin: 20px 10px 0px auto;
    height: 20px;
    line-height: 20px;
    border: 1px solid;
    cursor: pointer;
    float: right;
}
</style>
</head>
<body>
	<dl class="list"  style="border-top:none;margin-top:0rem;">
		<dd id="filtercon">
			<div class="find_div">
                <div style="height: 110px;">
                    <img src="{pigcms{$store.image}" width="100" height="100">
                    <div class="store_name">
                        <div>{pigcms{$store.name}</div>

                        <div style="margin-top: 10px;">
                            {pigcms{:L('_STORE_OPEN_CLOSE_')}:
                            <if condition="$store['status']">
                                <if condition="$store['is_close']">{pigcms{:L('_AT_REST_')}<else />{pigcms{:L('_AT_BUSINESS_')}</if>
                                <else />
                                {pigcms{:L('_AT_REST_')}
                            </if>
                        </div>
                    </div>
                </div>
			</div>
		</dd>
	</dl>
    <dl class="list"></dl>
    <div class="time_list">
        <div>
            <a href="{pigcms{:U('Storestaff/goods_list')}&sort_id={pigcms{$sort_id}" >&lt;&lt; {pigcms{:L('_STORE_BACK_')} </a>
        </div>
        <div style="text-align: center;font-size: 16px;margin-bottom: 10px">
            {pigcms{:L('_STORE_PRODUCT_CATE_')}:<a href="{pigcms{:U('Storestaff/goods_list',array('sort_id'=>$sort['sort_id']))}">{pigcms{$sort.sort_name}</a> >> <if condition="$goods">{pigcms{:lang_substr($goods['name'],C('DEFAULT_LANG'));}<else />{pigcms{:L('_STORE_ADD_PRODUCT_')}</if>
        </div>
        <dl class="nav_list">
            <dd data-id="good_info" class="nav_act">{pigcms{:L('_STORE_GOOD_INFO_')}</dd>
            <dd data-id="good_pic">{pigcms{:L('_STORE_GOOD_PIC_')}</dd>
            <dd data-id="good_spec">{pigcms{:L('_STORE_GOOD_SPEC_')}</dd>
            <dd data-id="good_pro">{pigcms{:L('_STORE_GOOD_PROPERTIES_')}</dd>
        </dl>
        <input type="hidden" name="goods_id" value="{pigcms{$goods_id}">
        <input type="hidden" name="sort_id" value="{pigcms{$sort_id}">
        <table class="table table-striped table-bordered table-hover" id="good_info">
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PRODUCT_NAME_')}</td>
                <td width="70%" style="text-align: left">
                    <input type="text" name="product_name_en" placeholder="{pigcms{:L('_ENGLISH_TXT_')}({pigcms{:L('_STORE_REQUIRED_')})" value="{pigcms{$goods['en_name']}">
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right"></td>
                <td width="70%" style="text-align: left">
                    <input type="text" name="product_name_cn" placeholder="{pigcms{:L('_CHINESE_TXT_')}" value="{pigcms{$goods['cn_name']}">
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PRODUCT_UNIT_')}</td>
                <td width="70%" style="text-align: left">
                    <input type="text" name="product_unit" placeholder="{pigcms{:L('_STORE_REQUIRED_')}" value="{pigcms{$goods['unit']}">
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PRODUCT_PRICE_')}</td>
                <td width="70%" style="text-align: left">
                    <input type="text" name="product_price" placeholder="{pigcms{:L('_STORE_REQUIRED_')}" value="{pigcms{$goods['price']}">
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PRODUCT_TAX_')}</td>
                <td width="70%" style="text-align: left">
                    <if condition="$goods['tax_num']">
                        <input type="text" name="product_tax" placeholder="{pigcms{:L('_STORE_REQUIRED_')}" value="{pigcms{$goods['tax_num']}">%
                    <else />
                        <input type="text" name="product_tax" placeholder="{pigcms{:L('_STORE_REQUIRED_')}" value="5">%
                    </if>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PRODUCT_DEPOSIT_')}</td>
                <td width="70%" style="text-align: left">
                    <if condition="$goods['tax_num']">
                        <input type="text" name="deposit_price" placeholder="{pigcms{:L('_STORE_REQUIRED_')}" value="{pigcms{$goods['deposit_price']}">
                    <else />
                        <input type="text" name="deposit_price" placeholder="{pigcms{:L('_STORE_REQUIRED_')}" value="0.00">
                    </if>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PRODUCT_STATUS_')}</td>
                <td width="70%" style="text-align: left">
                    <select name="product_status">
                        <option value="1" selected>{pigcms{:L('_STORE_GOOD_NORMAL_')}</option>
                        <option value="0">{pigcms{:L('_STORE_GOOD_DIS_')}</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_GOOD_DESC_')}</td>
                <td width="70%" style="text-align: left">
                    <textarea name="product_desc">{pigcms{$goods['des']}</textarea>
                </td>
            </tr>
        </table>

        <table class="table table-striped table-bordered table-hover" id="good_pic">
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_GOOD_PIC_')}</td>
                <td width="70%" style="text-align: left">
                    <div id="upload_img">
                        {pigcms{:L('_STORE_UPLOAD_PIC_')}
                    </div>
                </td>
            </tr>
            <tr>
                <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PIC_PREVIEW_')}</td>
                <td width="70%" style="text-align: left" id="product_img">
                    <if condition="$goods">
                        <img src="{pigcms{$goods['pic_arr'][0]['url']}" width="200">
                        <input type="hidden" name="product_pic" value="{pigcms{$goods[image]}" >
                    </if>
                </td>
            </tr>
        </table>
        <table class="table table-striped table-bordered table-hover" id="good_spec">
            <tr>
                <td width="100%" style="text-align: left">
                    <div class="add_c" data-id="goods_spec">{pigcms{:L('_STORE_ADD_SPEC_')}</div>
                    <if condition="$goods['spec_list']">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                        <tr id="spec_name_list">
                            <volist name="goods['spec_list']" id="vo">
                            <th>
                                <div class="spec_titel_name" data-id="{pigcms{$vo['id']}">{pigcms{$vo['name']}</div>
                                <div class="spec_edit_btn" data-id="{pigcms{$vo['id']}">Edit</div>
                                <div class="spec_del_btn" data-type="{pigcms{$i-1}" data-id="{pigcms{$vo['id']}">Del</div>
                            </th>
                            </volist>
                            <th id="price_title">{pigcms{:L('_PRICE_TXT_')}</th>
                        </tr>
                        </thead>
                        <tr id="spec_val">
                            <php>
                                $i = 0;
                                foreach($goods['spec_list'] as $vo){
                            </php>
                                <td valign="top" style="padding: 0px">
                                    <table class="table table-striped table-bordered table-hover spec_main" style="margin-bottom: 0px;" data-type="{pigcms{$i}" data-id="{pigcms{$vo['id']}">
                                    <php>
                                        $j = 0;
                                        foreach($vo['list'] as $vo_list){
                                    </php>
                                        <tr data-type="{pigcms{$j}" data-id="{pigcms{$vo_list['id']}">
                                            <td>{pigcms{$vo_list['name']}</td>
                                        </tr>
                                    <php>
                                            $j++;
                                        }
                                    </php>
                                    </table>
                                </td>
                            <php>
                                    $i++;
                                }
                            </php>
                            <td id="price_input_list" valign="top" style="padding: 0px">
                                <table class="table table-striped table-bordered table-hover" id="spec_price_table" style="margin-bottom: 0px;">

                                </table>
                            </td>
                        </tr>
                    </table>
                    <div style="display: none" id="spec_val_price_list">
                        <php>foreach($goods['list'] as $k=>$v){</php>
                            <span id="{pigcms{$k}">{pigcms{$v['price']}</span>
                        <php>}</php>
                    </div>
                    </if>
                </td>
            </tr>
        </table>

        <table class="table table-striped table-bordered table-hover" id="good_pro">
            <tr>
                <td width="100%" style="text-align: left">
                    <div class="add_c" data-id="goods_properties">{pigcms{:L('_STORE_ADD_PROPERTIES_')}</div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{pigcms{:L('_STORE_PRO_NAME_')}</th>
                                    <th>{pigcms{:L('_STORE_PRO_VALUE_')}</th>
                                    <th>{pigcms{:L('_STORE_PRO_SELECT_')}</th>
                                    <th>{pigcms{:L('_ACTION_')}</th>
                                </tr>
                            </thead>
                            <if condition="$goods['properties_list']">
                            <php>foreach($goods['properties_list'] as $k=>$v){</php>
                            <tr id="pro_{pigcms{$v['id']}">
                                <td width="30%" id="pro_name">
                                    {pigcms{$v['name']}
                                </td>
                                <td width="50%" id="pro_val">
                                    <dl class="pro_name_list">
                                        <php>foreach($v['val'] as $vo){</php>
                                        <dd>
                                            {pigcms{$vo}
                                        </dd>
                                        <php>}</php>
                                    </dl>
                                </td>
                                <td width="10%" id="pro_num">
                                    {pigcms{$v['num']}
                                </td>
                                <td width="10%">
                                    <div class="edit_pro" data-id="{pigcms{$v['id']}">Edit</div>
                                    　　
                                    <div class="del_pro" data-id="{pigcms{$v['id']}">Del</div>
                                </td>
                            </tr>
                            <php>}</php>
                            </if>
                        </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="submit">
        Submit
    </div>
    <include file="Storestaff:footer"/>
</body>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    function load_upload() {
        var uploader = WebUploader.create({
            auto: true,
            swf: '{pigcms{$static_public}js/Uploader.swf',
            server: "{pigcms{:U('Storestaff/ajax_upload')}",
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,png',
                mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
            }
        });
        uploader.addButton({
            id:'#upload_img',
            name:'image_0',
            multiple:false
        });
        uploader.on('uploadSuccess',function(file,response){
            if(response.error == 0){
                // var fid = file.source.ruid;
                // var ruid = fid.split('_');
                // var img = findImg(ruid[1],response.file);
                $('#product_img').html('<img src="'+response.url+'" width="200" /><input type="hidden" name="product_pic" value="'+response.title+'"/>');
            }else{
                alert(response.info);
            }
        });

        uploader.on('uploadError', function(file,reason){
            $('.loading'+file.id).remove();
            alert('上传失败！请重试。');
        });
    }

    $('.add_c').click(function () {
        //art.dialog.data('domid', 0);
        //art.dialog.open("{pigcms{:U('Storestaff/manage_add_cate')}",{lock:true,title:"{pigcms{:L('_STORE_ADD_PRO_CATE_')}",background: '#000',opacity: 0.45});
        var act = $(this).attr('data-id');
        var z_title = "{pigcms{:L('_STORE_ADD_SPEC_')}";
        if(act == 'goods_properties')
            z_title = "{pigcms{:L('_STORE_ADD_PROPERTIES_')}";

        art.dialog.open("?g=Wap&c=Storestaff&a="+act+"&goods_id={pigcms{$goods_id}",{lock:true,title:z_title,background: '#000',opacity: 0.45});
    });

    $('.nav_list').find('dd').each(function () {
        $(this).click(function () {
            changeNav($(this).attr('data-id'));
        });
    });

    changeNav('good_info');

    function changeNav(c_data_id) {
        if(c_data_id == 'good_pic'){
            load_upload();
        }
        var class_act = 'nav_act';
        $('#'+c_data_id).show();
        $('.nav_list').find('dd').each(function () {
            if($(this).attr('data-id') == c_data_id){
                $(this).attr('class',class_act);
            }else{
                $(this).removeClass();
                $('#'+$(this).attr('data-id')).hide();
            }
        });
    }

    $('.edit_pro').click(function () {
        var pro_id = $(this).attr('data-id');
        art.dialog.open("?g=Wap&c=Storestaff&a=goods_properties&goods_id={pigcms{$goods_id}&pro_id="+pro_id,{lock:true,title:"{pigcms{:L('_STORE_EDIT_PROPERTIES_')}",background: '#000',opacity: 0.45});
    });

    $('.del_pro').click(function () {
        var pro_id = $(this).attr('data-id');
        layer.open({
            title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",
            content:"{pigcms{:L('_B_PURE_MY_84_')}",
            btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
            yes: function(index){
                layer.close(index);
                $.post("{pigcms{:U('Storestaff/goods_pro_del')}", {'id':pro_id,'goods_id':$('input[name=goods_id]').val()}, function (result) {
                    if(result.status){
                        layer.open({
                            title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                            content: result.info,
                            time: 1,
                            end: function () {
                                $('#pro_'+pro_id).remove();
                                art.dialog.close();
                            }
                        });
                    }
                });
            }
        });
    });

    function updatePro(pro) {
        var pro_tr = $('#pro_'+pro['id']);
        pro_tr.find('#pro_name').html(pro['name']);
        pro_tr.find('#pro_num').html(pro['num']);
        var val_list = pro['val'].split(',');
        pro_tr.find('#pro_val').find('dl').remove();
        var val_html = '<dl class="pro_name_list">';
        for(var i=0;i<val_list.length;i++){
            val_html = val_html + '<dd>'+val_list[i]+'</dd>';
        }
        val_html = val_html + '</dl>';

        pro_tr.find('#pro_val').html(val_html);
    }

    function addNewProAndId(pro,new_id) {
        pro_new_list[new_id-1] = pro;

        var pro_tr = $('#pro_new_'+new_id);
        pro_tr.find('#pro_name').html(pro['name']);
        pro_tr.find('#pro_num').html(pro['num']);
        var val_list = pro['val'].split(',');
        pro_tr.find('#pro_val').find('dl').remove();
        var val_html = '<dl class="pro_name_list">';
        for(var i=0;i<val_list.length;i++){
            val_html = val_html + '<dd>'+val_list[i]+'</dd>';
        }
        val_html = val_html + '</dl>';

        pro_tr.find('#pro_val').html(val_html);
    }

    var pro_new_num = 0;
    var pro_new_list = [];
    function addNewPro(pro) {
        pro_new_list.push(pro);

        pro_new_num = pro_new_num+1;
        var val_html = '<tr id="pro_new_'+pro_new_num+'">';
        val_html += '<td width="30%" id="pro_name">' + pro['name'] + '</td>';
        val_html += '<td width="50%" id="pro_val">';
        var val_list = pro['val'].split(',');
        val_html += '<dl class="pro_name_list">';
        for(var i=0;i<val_list.length;i++){
            val_html = val_html + '<dd>'+val_list[i]+'</dd>';
        }
        val_html = val_html + '</dl>';
        val_html += '</td>';
        val_html += '<td width="10%" id="pro_num">' + pro['num'] + '</td>';

        val_html += '<td width="10%"><div class="edit_pro_new" data-id="'+pro_new_num+'">Edit</div>';
        val_html += '　　<div class="del_pro_new" data-id="'+pro_new_num+'">Del</div></td>';

        val_html += '</tr>';

        $('#good_pro').find('table').append(val_html);

        addNewEditClick();
    }
    function addNewEditClick() {
        $('.edit_pro_new').unbind("click");
        $('.edit_pro_new').click(function () {
            var new_id = $(this).attr('data-id');
            art.dialog.open("?g=Wap&c=Storestaff&a=goods_properties&new_id="+new_id,{lock:true,title:"{pigcms{:L('_STORE_EDIT_PROPERTIES_')}",background: '#000',opacity: 0.45});
        });

        $('.del_pro_new').unbind("click");
        $('.del_pro_new').click(function () {
            var new_id = parseInt($(this).attr('data-id'));
            layer.open({
                title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                content:"{pigcms{:L('_B_PURE_MY_84_')}",
                btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
                yes: function(index){
                    layer.close(index);
                    pro_new_list.splice(new_id-1,1);
                    $('#good_pro').find('table').find('#pro_new_'+new_id).remove();
                    if(pro_new_num > new_id){
                        for(var i=new_id+1;i<=pro_new_num;i++){
                            $('#pro_new_'+i).find('.edit_pro_new').attr('data-id',i-1);
                            $('#pro_new_'+i).find('.del_pro_new').attr('data-id',i-1);
                            $('#good_pro').find('table').find('#pro_new_'+i).attr('id','pro_new_'+(i-1));
                        }
                        pro_new_num = pro_new_num - 1;
                    }
                }
            });

        });
    }

    function getNewData(new_id) {
        var data = pro_new_list[new_id-1];
        return data;
    }

    function getNewSpecData(new_id){
        var data = new_spec_list[new_id-1];
        return data;
    }
    ///spec
    $('.spec_edit_btn').click(function () {
        var spec_id = $(this).attr('data-id');
        art.dialog.open("?g=Wap&c=Storestaff&a=goods_spec&goods_id={pigcms{$goods_id}&spec_id="+spec_id,{lock:true,title:"{pigcms{:L('_STORE_EDIT_PROPERTIES_')}",background: '#000',opacity: 0.45});
    });

    $('.spec_del_btn').click(function () {
        var spec_id = $(this).attr('data-id');
        var spec_type = $(this).attr('data-type');
        spec_del_val(spec_id,spec_type);
    });

    var spec_num = "{pigcms{$goods['spec_num']}";
    if(spec_num == '')
        spec_num = 0;
    else
        spec_num = parseInt(spec_num);

    var new_spec_num = 0;
    var new_spec_list = [];

    var del_spec_list = [];

    var spec_val_key = [];

    init_spec(-1,0);
    insert_spec_price();
    function init_spec(main_num,val_num) {
        var link_spec_str = '';
        spec_val_key = [];
        $('#spec_val').find('.spec_main').each(function () {
            var c_num = $(this).attr('data-type')
            if(c_num == spec_num-1){
                $(this).find('tr').each(function () {
                    $(this).removeClass();
                    if(link_spec_str == ''){
                        var push_str = $(this).attr('data-id');
                    }else{
                        var push_str = link_spec_str + '_' + $(this).attr('data-id');
                    }
                    spec_val_key.push(push_str);
                });
            }else{
                $(this).find('tr').each(function () {
                    if((main_num == -1 && $(this).attr('data-type') == 0) || (main_num == c_num && $(this).attr('data-type') == val_num)){
                        $(this).attr('class','spec_act');
                        if(link_spec_str == ''){
                            link_spec_str = $(this).attr('data-id');
                        }else{
                            link_spec_str = link_spec_str + '_' + $(this).attr('data-id');
                        }
                    }else if(main_num != -1 && main_num != c_num && $(this).attr('class') == 'spec_act'){
                        if(link_spec_str == ''){
                            link_spec_str = $(this).attr('data-id');
                        }else{
                            link_spec_str = link_spec_str + '_' + $(this).attr('data-id');
                        }
                    }else{
                        $(this).removeClass();
                    }
                    $(this).unbind("click");
                    $(this).click(function () {
                        init_spec(c_num,$(this).attr('data-type'));
                        insert_spec_price();
                    });
                });
            }
        });
    }

    function insert_spec_price() {
        $('#spec_price_table').html('');
        var insert_html = '';
        for(var i=0;i<spec_val_key.length;i++){
            var price = $('#'+spec_val_key[i]).html();
            if(price == null){
                price = 0;
                var new_price = '<span id="'+spec_val_key[i]+'">0</span>';
                $('#spec_val_price_list').append(new_price);
            }
            insert_html += '<tr><td style="padding: 4px">$<input type="text" name="price" data-id="'+spec_val_key[i]+'" value="'+price+'"></td></tr>';
        }
        $('#spec_price_table').html(insert_html);

        $('#spec_price_table').find('input').each(function () {
            //$(this).unbind("change");
            $(this).change(function () {
                $('#'+$(this).attr('data-id')).html($(this).val());
            });
        });
    }
    
    function updateSpec(data) {
        $('#good_spec').find('.spec_titel_name').each(function () {
            if($(this).attr('data-id') == data['id']){
                $(this).html(data['name']);
            }
        });
        $('#spec_val').find('table').each(function () {
            if($(this).attr('data-id') == data['id']){
                $(this).find('tr').remove();
                var html = '';
                for(var i=0;i<data['val'].length;i++){
                     html += '<tr data-type="'+i+'" data-id="'+data['val'][i]['id']+'"><td>'+data['val'][i]['name']+'</td></tr>';
                }
                $(this).html(html);
            }
        });

        init_spec(-1,0);
        insert_spec_price();
    }


    function addNewSpecAndId(data,new_id) {
        new_spec_list[new_id-1] = data;

        $('#good_spec').find('.spec_titel_name').each(function () {
            if($(this).attr('data-id') == 'new-'+new_id){
                $(this).html(data['name']);
            }
        });
        $('#spec_val').find('table').each(function () {
            if($(this).attr('data-id') == 'new-'+new_id){
                $(this).find('tr').remove();
                var html = '';
                for(var i=0;i<data['val'].length;i++){
                    html += '<tr data-type="'+i+'" data-id="new-'+new_id+'-'+i+'"><td>'+data['val'][i]+'</td></tr>';
                }
                $(this).html(html);
            }
        });
    }

    function addNewSpec(data) {
        new_spec_list.push(data);

        if(spec_num == 0 && $('#good_spec').find('table').length == 0){
            var html = '<table class="table table-striped table-bordered table-hover"><thead><tr id="spec_name_list"><th id="price_title">{pigcms{:L(\'_PRICE_TXT_\')}</th></tr></thead>';
            html += '<tr id="spec_val"><td id="price_input_list" valign="top" style="padding: 0px">';
            html += '<table class="table table-striped table-bordered table-hover" id="spec_price_table" style="margin-bottom: 0px;"></table></td></tr></table>';
            html += '<div style="display: none" id="spec_val_price_list"></div>';
            $('#good_spec').append(html);
        }
        spec_num = spec_num + 1;
        new_spec_num = new_spec_num + 1;

        var spec_title = '<th><div class="spec_titel_name" data-id="new-'+new_spec_num+'">'+data['name']+'</div>';
        spec_title += '<div class="spec_edit_btn" data-id="new-'+new_spec_num+'">Edit</div>';
        spec_title += '<div class="spec_del_btn" data-type="'+(spec_num-1)+'" data-id="new-'+new_spec_num+'">Del</div></th>';

        $('#price_title').before(spec_title);
        $('.spec_edit_btn').unbind('click');
        $('.spec_edit_btn').click(function () {
            var spec_id = $(this).attr('data-id');
            art.dialog.open("?g=Wap&c=Storestaff&a=goods_spec&goods_id={pigcms{$goods_id}&spec_id="+spec_id,{lock:true,title:"{pigcms{:L('_STORE_EDIT_PROPERTIES_')}",background: '#000',opacity: 0.45});
        });
        $('.spec_del_btn').unbind('click');
        $('.spec_del_btn').click(function () {
            var spec_id = $(this).attr('data-id');
            var spec_type = $(this).attr('data-type');
            spec_del_val(spec_id,spec_type);
        });

        var spec_val_html = '<td valign="top" style="padding: 0px">';
        spec_val_html += '<table class="table table-striped table-bordered table-hover spec_main" style="margin-bottom: 0px;" data-type="'+(spec_num-1)+'" data-id="new-'+new_spec_num+'">';
        for(var i=0;i<data['val'].length;i++){
            spec_val_html += '<tr data-type="'+i+'" data-id="new-'+new_spec_num+'-'+i+'">';
            spec_val_html += '<td>'+data['val'][i]+'</td></tr>';
        }
        spec_val_html += '</table></td>';

        $('#price_input_list').before(spec_val_html);


        init_spec(-1,0);
        insert_spec_price();
    }

    function spec_del_val(id,type) {
        layer.open({
            title:"{pigcms{:L('_B_D_LOGIN_TIP2_')}",
            content:"{pigcms{:L('_B_PURE_MY_84_')}",
            btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
            yes: function(index){
                layer.close(index);
                del_spec_list.push(id);
                $('#good_spec').find('table').find('th').each(function () {
                    if($(this).find('.spec_titel_name').attr('data-id') == id){
                        $(this).remove();
                    }
                });
                $('#spec_val').find('td').each(function () {
                    if($(this).find('table').attr('data-id') == id){
                        $(this).remove();
                    }

                    if($(this).find('table').attr('data-type') > type){
                        var new_type = parseInt($(this).find('table').attr('data-type')) - 1;
                        $(this).find('table').attr('data-type',new_type);
                    }
                });

                spec_num = spec_num - 1;
                if(spec_num > 0){
                    init_spec(-1,0);
                    insert_spec_price();
                }else {
                    $('#good_spec').find('table').remove();
                    $('#spec_val_price_list').remove();
                }

            }
        });
    }
    ///submit
    var spec_val = [];
    function getSpecData(){
        var spec_val = [];
        //规格是否存在
        if($('#good_spec').find('table').length > 0){
            $('#good_spec').find('table').find('#spec_name_list').find('.spec_titel_name').each(function () {
                var this_spec = {};
                this_spec['name'] = $(this).html();
                this_spec['id'] = $(this).attr('data-id');

                var this_id = $(this).attr('data-id');

                var this_val = [];
                $('#good_spec').find('table').find('#spec_val').find('table').each(function () {
                    if($(this).attr('data-id') == this_id){
                        $(this).find('tr').each(function () {
                            var t_val = {};
                            t_val['id'] = $(this).attr('data-id');
                            t_val['name'] = $(this).find('td').html();

                            this_val.push(t_val);
                        });
                    }
                });
                this_spec['val'] = this_val;

                spec_val.push(this_spec);
            });
        }

        return spec_val;
    }

    function getSpecPrice(data,num,old_arr){
        var new_arr = [];
        if(num < data.length){
            for(var i=0;i<data[num]['val'].length;i++){
                if(old_arr.length > 0) {
                    for (var j = 0; j < old_arr.length; j++) {
                        var str = old_arr[j]+'_'+data[num]['val'][i]['id'];
                        new_arr.push(str);
                    }
                }else{
                    new_arr.push(data[num]['val'][i]['id']);
                }
            }

            var next_num = num+1;
            if(next_num < data.length){
                var price_data = getSpecPrice(data,next_num,new_arr);
                return price_data;
            }else{
                var price_data = [];
                for(var i=0;i<new_arr.length;i++){
                    var t_data = {};
                    t_data['ids'] = new_arr[i];
                    var price = $('#spec_val_price_list').find('#'+new_arr[i]).html();
                    if(price == null){
                        price = 0;
                    }
                    t_data['price'] = price;

                    price_data.push(t_data);
                }
                return price_data;
            }
        }
    }

    function checkSubmit(spec_price_data){
        var is_tip = false;
        if($('input[name=product_name_en]').val() == '' || $('input[name=product_unit]').val() == '' || $('input[name=product_price]').val() == '' || $('input[name=product_tax]').val() == ''){
            is_tip = true;
            changeNav('good_info');
        }
        if(typeof(spec_price_data) != 'undefined'){
            for(var i=0;i<spec_price_data.length;i++){
                if(spec_price_data[i]['price'] == 0){
                    is_tip = true;
                    changeNav('good_spec');
                    break;
                }
            }
        }

        return is_tip;

    }
    
    $('.submit').click(function () {
        var spec_data = getSpecData();
        var spec_price_data = getSpecPrice(spec_data,0,[]);

        var is_tip = checkSubmit(spec_price_data);
        if(!/^\d+(\.\d{1,2})?$/.test($('input[name=product_price]').val())) {
            $('input[name=product_price]').focus();
            layer.open({
                title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                time: 1,
                content: "{pigcms{:L('_PLEASE_RIGHT_PRICE_')}"
            });
        }else if(!/^\d+(\.\d{1,2})?$/.test($('input[name=deposit_price]').val())){
            $('input[name=deposit_price]').focus();
            layer.open({
                title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                time: 1,
                content: "{pigcms{:L('_PLEASE_RIGHT_PRICE_')}"
            });
        }else if(!/^\d{1,2}$/.test($('input[name=product_tax]').val())){
            $('input[name=product_tax]').focus();
            layer.open({
                title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                time: 1,
                content: "{pigcms{:L('_STORE_TAX_TIP_')}"
            });
        }
        else if(is_tip){
            layer.open({
                title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                time: 1,
                content: "{pigcms{:L('_PLEASE_INPUT_ALL_')}"
            });
        }else {
            var product_data = {
                'goods_id':$('input[name=goods_id]').val(),
                'sort_id':$('input[name=sort_id]').val(),
                'en_name': $('input[name=product_name_en]').val(),
                'cn_name': $('input[name=product_name_cn]').val(),
                'unit': $('input[name=product_unit]').val(),
                'price': $('input[name=product_price]').val(),
                'tax': $('input[name=product_tax]').val(),
                'deposit': $('input[name=deposit_price]').val(),
                'status': $('select[name=product_status]').val(),
                'desc': $('textarea[name=product_desc').val(),
                'product_image': $('input[name=product_pic]').val(),
                'spec_all_list': spec_data,
                'spec_all_price': spec_price_data,
                'pro_new_list': pro_new_list,
                'spec_del_list':del_spec_list
            }

            $.post("{pigcms{:U('Storestaff/goods_add_edit')}", product_data, function (result) {
                if(result.status) {
                    layer.open({
                        title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                        content: result.info,
                        time: 1,
                        end: function () {
                            window.location.href = "{pigcms{:U('Storestaff/goods_list')}" + '&sort_id=' + "{pigcms{$sort_id}";
                        }
                    });
                }else{
                    layer.open({
                        title: "{pigcms{:L('_B_D_LOGIN_TIP2_')}",
                        content: result.info,
                    });
                }
            });
        }
    });

</script>
</html>