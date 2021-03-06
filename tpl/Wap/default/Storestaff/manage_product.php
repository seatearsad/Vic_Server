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
    margin-top: -90px;
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
.add_c{
    width: 100px;
    height: 30px;
    background-color: #ffa64d;
    text-align: center;
    line-height: 30px;
    margin-bottom: 10px;
    margin-top: 10px;
    color: #ffffff;
    cursor: pointer;
}
a {
    color: #ffa64d;
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
                        <div style="font-size: 20px">{pigcms{$store.name}</div>

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
            <a href="{pigcms{:U('Storestaff/manage')}" >&lt;&lt; {pigcms{:L('_STORE_BACK_')} </a>
        </div>
        <div style="text-align: center;font-size: 16px;">
            {pigcms{:L('_STORE_PRDUCT_CATE_')}
        </div>
        <div class="add_c">{pigcms{:L('_STORE_ADD_PRO_CATE_')}</div>
        <table class="table table-striped table-bordered table-hover">
            <thead>
            <tr>
                <th width="60%">{pigcms{:L('_STORE_PRODUCT_CATE_')}</th>
                <th width="20%">{pigcms{:L('_B_PURE_MY_26_')}</th>
                <th width="20%">{pigcms{:L('_EDIT_TXT_')}</th>
            </tr>
            </thead>
            <tbody>
            <if condition="$sort_list">
                <volist name="sort_list" id="vo">
                    <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
            <td>{pigcms{$vo.sort_name}</td>
            <td>
                <if condition="$vo['operation_type'] eq 2">
                    <a style="width: 60px;" class="label label-sm label-purple" href="{pigcms{:U('Shop/goods_sort',array('fid' => $vo['sort_id'], 'store_id' => $vo['store_id']))}">子分类</a>
                    <a style="width: 60px;" class="label label-sm label-info handle_btn" href="{pigcms{:U('Storestaff/goods_list',array('sort_id'=>$vo['sort_id']))}">{pigcms{:L('_STORE_SUB_PRODUCT_')}</a>
                    <elseif condition="$vo['operation_type'] eq 1" />
                    <a style="width: 60px;" class="label label-sm label-purple" href="{pigcms{:U('Shop/goods_sort',array('fid' => $vo['sort_id'], 'store_id' => $vo['store_id']))}">子分类</a>
                    <else />
                    <a style="width: 60px;" class="label label-sm label-info handle_btn" href="{pigcms{:U('Storestaff/goods_list',array('sort_id'=>$vo['sort_id']))}">{pigcms{:L('_STORE_SUB_PRODUCT_')}</a>
                </if>
            </td>
            <td>
                <a title="{pigcms{:L('_EDIT_TXT_')}" class="green" href="javascript:edit_cate('{pigcms{$vo.sort_id}','{pigcms{$vo.fid}');">
                    {pigcms{:L('_STORE_EDIT_CATE_NAME_')}
                </a>
                <!--　　a title="删除" class="red" href="{pigcms{:U('Shop/sort_del',array('sort_id'=>$vo['sort_id']))}">
                    {pigcms{:L('_B_PURE_MY_27_')}
                </a-->
            </td>
            </tr>
            </volist>
            <else/>
            <tr class="odd"><td class="button-column" colspan="3" ></td></tr>
            </if>
            </tbody>
        </table>
    </div>

    <include file="Storestaff:footer"/>
</body>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
    function edit_cate(sort_id,fid){
        art.dialog.data('domid', sort_id);
        art.dialog.open('?g=Wap&c=Storestaff&a=manage_edit_cate&sort_id='+sort_id+'&fid='+fid,{lock:true,title:"{pigcms{:L('_STORE_EDIT_PRO_CATE_')}",background: '#000',opacity: 0.45});
    }
    $('.add_c').click(function () {
        art.dialog.data('domid', 0);
        art.dialog.open("{pigcms{:U('Storestaff/manage_add_cate')}",{lock:true,title:"{pigcms{:L('_STORE_ADD_PRO_CATE_')}",background: '#000',opacity: 0.45});
    });
</script>
</html>