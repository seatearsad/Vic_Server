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
    <script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link href="{pigcms{$static_path}js/mobiscroll/mobiscroll.custom.min.css" rel="stylesheet" type="text/css">
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
            width: 30%;
            background-color: #999;
        }
        .time_list ul li{
            width: 100%;
            text-align: center;
            height: 30px;
            line-height: 30px;
            border-bottom: 1px solid #ffffff;
            color: #ffffff;
            cursor: pointer;
        }
        .time_list ul li:hover{
            background-color: #ffa64d;
        }
        .h_week{
            background-color: #ffa64d;
        }
        .week_time{
            margin-left: 30%;
            margin-top: -217px;
            width: 70%;
            height: 267px;
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
        .week_time dl{
            width: 100%;
            height: 100%;
            background-color: #ffffff;
            margin: 0px;
        }
        .week_time dl dd{
            width: 49%;
            text-align: center;
            height: 30px;
            line-height: 30px;
            display: inline-block;
            background-color: #ffffff;
            color: #333;
            border: 0px;
            margin-left: 0px;
        }
        .week_time dl #time_dd{
            height: 40px;
        }
        .week_time dl dd input{
            width: 100px;
            text-align: center;
            height: 25px;
        }
        #time_form input{
            display: none;
        }
        .week_time dl dd.time_desc{
            width: 100%;
            text-align: center;
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
        #upload_img,#holiday{
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
    <div style="margin-bottom: 10px">
        <a href="{pigcms{:U('Storestaff/manage')}" >&lt;&lt; {pigcms{:L('_STORE_BACK_')} </a>
    </div>
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <td width="30%" style="text-align: right">{pigcms{:L('_C_MERCHANT_NAME_')}</td>
            <td width="70%" style="text-align: left">
                <input type="text" name="name_en" placeholder="{pigcms{:L('_ENGLISH_TXT_')}({pigcms{:L('_STORE_REQUIRED_')})" value="{pigcms{$store['en_name']}">
            </td>
        </tr>
        <tr>
            <td width="30%" style="text-align: right"></td>
            <td width="70%" style="text-align: left">
                <input type="text" name="name_cn" placeholder="{pigcms{:L('_CHINESE_STORE_NAME_')}" value="{pigcms{$store['cn_name']}">
            </td>
        </tr>
        <tr>
            <td width="30%" style="text-align: right">{pigcms{:L('_C_MERCHANT_PHONE_')}</td>
            <td width="70%" style="text-align: left">
                <input type="text" name="phone" placeholder="{pigcms{:L('_STORE_REQUIRED_')}" value="{pigcms{$store['phone']}">
            </td>
        </tr>
        <tr>
            <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PIC_')}</td>
            <td width="70%" style="text-align: left">
                <div id="upload_img">
                    {pigcms{:L('_STORE_UPLOAD_PIC_')}
                </div>
            </td>
        </tr>
        <tr>
            <td width="30%" style="text-align: right">{pigcms{:L('_STORE_PIC_PREVIEW_')}</td>
            <td width="70%" style="text-align: left" id="store_img">
                <if condition="$store">
                    <img src="{pigcms{$store.image}" width="200">
                    <input type="hidden" name="store_pic" value="{pigcms{$store[pic_info]}" >
                </if>
            </td>
        </tr>
        <tr>
            <td width="30%" style="text-align: right">{pigcms{:L('_STORE_DESC_')}</td>
            <td width="70%" style="text-align: left">
                <textarea name="txt_info">{pigcms{$store['txt_info']}</textarea>
            </td>
        </tr>
        <tr>
            <td width="30%" style="text-align: right">{pigcms{:L('_STORE_HOLIDAY_MANAGE_')}</td>
            <td width="70%" style="text-align: left">
                <div id="holiday">
                    <if condition="$store['status']">
                        {pigcms{:L('_STORE_TO_HOLIDAY_')}
                        <else/>
                        {pigcms{:L('_STORE_TO_NOT_HOLIDAY_')}
                    </if>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="submit">
    Submit
</div>
<form id="time_form" autocomplete="off" method="post" action="{pigcms{:U('Storestaff/manage_info')}">

</form>

<include file="Storestaff:footer"/>
</body>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&language=en"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    //var geocoder = new google.maps.Geocoder;
    //var latlng = {lat: parseFloat("48.4245834"), lng: parseFloat("-123.3666992")};
    // geocoder.geocode({'location': latlng}, function(results, status) {
    //     alert(results);
    // });
    // geocoder.geocode({'placeId':'ChIJcWGw3Ytzj1QR7Ui7HnTz6Dg'},function (results, status) {
    //    alert(result);
    // });

    var uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Storestaff/ajax_store_upload')}",
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
            $('#store_img').html('<img src="'+response.url+'" width="200" /><input type="hidden" name="store_pic" value="'+response.title+'"/>');
        }else{
            alert(response.info);
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });

    $('.submit').click(function () {
        var is_tip = checkAllTime();
        if(!is_tip){
            var re_data = {
                'en_name':$('input[name=name_en]').val(),
                'cn_name':$('input[name=name_cn]').val(),
                'phone':$('input[name=phone]').val(),
                'pic_info':$('input[name=store_pic]').val(),
                'txt_info':$('textarea[name=txt_info]').val()
            };

            $.post($('#time_form').attr('action'),re_data,function(data){
                if(data.status == 1){
                    layer.open({
                        title: "{pigcms{:L('_STORE_REMIND_')}",
                        time: 1,
                        content: data.info,
                        end:function () {
                            window.location.href = "{pigcms{:U('Storestaff/manage')}";
                        }
                    });
                }else{
                    alert('Fail');
                }
            });
        }else{
            layer.open({
                title: "{pigcms{:L('_STORE_REMIND_')}",
                time: 1,
                content: "{pigcms{:L('_PLEASE_INPUT_ALL_')}"
            });
        }
    });
    function checkAllTime() {
        var is_tip = false;
        if($('input[name=name_en]').val() == '' || $('input[name=phone]').val() == '' || $('input[name=store_pic]').val() == ''){
            is_tip = true;
        }

        return is_tip;
    }
    var store_status = '{pigcms{$store.status}';
    $('#holiday').click(function () {
        if(store_status == 1){//操作 店铺休假
            layer.open({
                title:"{pigcms{:L('_STORE_REMIND_')}",
                content:"{pigcms{:L('_STORE_HOLIDAY_TIP_')}",
                btn: ["{pigcms{:L('_B_D_LOGIN_CONIERM_')}","{pigcms{:L('_B_D_LOGIN_CANCEL_')}"],
                yes: function(index){
                    layer.close(index);
                    $.post("{pigcms{:U('Storestaff/manage_holiday')}",function(result){
                        layer.open({
                            title:"{pigcms{:L('_STORE_REMIND_')}",
                            content:result.info,
                            time: 1,
                            end:function () {
                                window.location.reload();
                            }
                        });

                    });
                }
            });
        }else{//操作 打开店铺
            $.post("{pigcms{:U('Storestaff/manage_holiday')}",function(result){
                layer.open({
                    title:"{pigcms{:L('_STORE_REMIND_')}",
                    content:result.info,
                    time: 1,
                    end:function () {
                        window.location.reload();
                    }
                });

            });
        }
    });


</script>
</html>