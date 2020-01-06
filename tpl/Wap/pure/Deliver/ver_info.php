<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta http-equiv="Expires" content="-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <meta http-equiv="Pragma" content="no-cache">
    <meta charset="utf-8">
    <title>{pigcms{:L('_COURIER_CENTER_')}</title>
    <meta name="description" content="{pigcms{$config.seo_description}"/>
    <link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
    <script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_public}js/laytpl.js"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
    <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css" media="all">
</head>
<style>
    body {
        padding: 0px;
        margin: 0px auto;
        font-size: 14px;
        min-width: 320px;
        max-width: 100%;
        background-color: white;
        color: #333333;
        position: relative;
        -webkit-tap-highlight-color: rgba(0,0,0,0);
    }
    section{
        position: absolute;
        top: 2%;
        width: 100%;
        font-size: 10px;
        color: #666666;
    }
    #step_now{
        width:80%;
        margin: 20px auto;
        font-size: 0;
    }
    #step_now div{
        font-size: 10px;
        text-align: left;
        padding-left: 25%;
    }
    #step_now ul{
        margin-top: 2px;
    }
    #step_now li{
        display: inline-block;
        width: 25%;
        height: 5px;
        background-color: #F4F4F4;
    }
    #step_now li:nth-child(1).act{
        background-color: #ffde59;
    }
    #step_now li:nth-child(2).act{
        background-color: #ffbd59;
    }
    #step_now li:nth-child(3).act{
        background-color: #ffa52d;
    }
    #step_now li:nth-child(4).act{
        background-color: #ffa99a;
    }
    #memo{
        width:80%;
        margin: 5px auto 5px auto;
        text-align: left;
    }
    #step_title{
        width:80%;
        margin: 20px auto 5px auto;
        font-size: 12px;
        color: #333333;
    }
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }
    #J_selectImage_0,#J_selectImage_1,#J_selectImage_2{
        background-color: #EEEEEE;
        background-image: url("{pigcms{$static_path}img/step2.png");
        background-size: 35px 35px;
        background-repeat: no-repeat;
        background-position:left 10px center;
        color: #666666;
        text-indent: 0px;
        font-size: 9px;
        border-radius: 5px;
        padding: 0px;
        height: 50px;
        line-height: 50px;
        padding-left: 55px;
        box-sizing: border-box;
        display: inline-block;
        width: 100%;
    }
    input{
        background-color: #ffa52d;
        color: white;
        padding: 10px 15px;
        border-radius: 3px;
    }
    .img_0,.img_1,.img_2{
        width: 100%;
        text-align: center;
    }
    .img_0 img,.img_1 img,.img_2 img{
        height: 100px;
    }
    li{
        text-align: center;
        margin-top: 10px;
    }
    li input {
        width: 55%;
        height: 15px;
        padding: 8px 0;
        text-indent: 10px;
        color: #333333;
        background-color: #EEEEEE;
        border-radius: 5px;
        margin-left: 1%;
        margin-top: 2px;
        font-size: 12px;
    }
    li select{
        width: 55%;
        height: 31px;
        text-indent: 5px;
        border-radius: 5px;
        background-color: #EEEEEE;
    }
    li.Landd input {
        background: #ffa52d;
        color: #fff;
        text-indent: 0px;
        font-size: 12px;
        margin-top: 30px;
        margin-left: 0;
        padding: 0px;
        height: 30px;
    }
    #reg_list li span{
        text-align: left;
        display: inline-block;
        width: 42%;
        font-size: 12px;
    }
    #reg_list{
        width: 80%;
        margin: 60px auto 0 auto;
    }
    #review_tip{
        width: 100%;
        margin: 20px auto;
        border-radius: 5px;
        border: 2px solid red;
        padding: 10px 20px 10px 50px;
        box-sizing: border-box;
        font-size: 10px;
        background-repeat: no-repeat;
        background-position: center left 9px;
        background-size:32px auto;
        background-image:url('{pigcms{$static_path}img/review_tip.png');
    }
</style>
<body>
<include file="header" />
<section>
    <div id="reg_list">
        <if condition="$deliver_img['review_desc'] neq '' and $deliver_session['group'] neq 1">
        <div id="review_tip">
            Sorry, your application is disapproved for the following reason(s):
            {pigcms{$deliver_img['review_desc']}
        </div>
        </if>
        <ul>
            <li>
                <span>Delivery City:</span>
                <if condition="$deliver_session['group'] neq 1">
                <select name="city_id" id="city_id">
                    <volist name="city_list" id="city">
                        <option value="{pigcms{$city['area_id']}" <if condition="$deliver_session['city_id'] eq $city['area_id']">selected="selected"</if>>{pigcms{$city['area_name']}</option>
                    </volist>
                </select>
                <else />
                    <label style="width: 55%;display: inline-block;text-align: left">
                    {pigcms{$city_name}
                    </label>
                </if>
            </li>
            <li>
                <span>{pigcms{:L('_ADDRESS_TXT_')}:</span>
                <input type="text" placeholder="{pigcms{:L('_ADDRESS_TXT_')}" id="address" value="{pigcms{$deliver_session['site']}">
            </li>
            <li>
                <span>SIN Number:</span>
                <input type="text" placeholder="SIN Number" id="sin_num" value="{pigcms{$deliver_img['sin_num']}">
            </li>
        </ul>
        <input type="hidden" name="lng" id="lng" value="{pigcms{$deliver_session['lng']}">
        <input type="hidden" name="lat" id="lat" value="{pigcms{$deliver_session['lat']}">
    </div>
    <div id="step_title">
        Document Upload
    </div>
    <if condition="$deliver_session['group'] neq 1">
    <div id="memo">
        Please make sure the photo is clear, especially the name and expiration date. Photos that are unclear or invalid may result in verification failure and delay your application.
    </div>
    </if>
    <div id="step_title">
        Driver's License
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <if condition="$deliver_session['group'] neq 1">
        <div style="display:inline-block;" id="J_selectImage_0">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                Upload a photo of your Diver's License here
            </div>
        </div>
        </if>
        <if condition="$deliver_img['driver_license'] eq ''">
            <div class="img_0">

            </div>
         <else />
            <div class="img_0" style="height: 100px">
                <img src="{pigcms{$deliver_img['driver_license']}"/>
            </div>
        </if>
    </div>
    <div id="step_title">
        Vehicle Insurance
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <if condition="$deliver_session['group'] neq 1">
        <div style="display:inline-block;" id="J_selectImage_1">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                Upload a photo of your Vehicle Insurance here
            </div>
        </div>
        </if>
        <if condition="$deliver_img['insurance'] eq ''">
            <div class="img_1">

            </div>
        <else />
            <div class="img_1" style="height: 100px">
                <img src="{pigcms{$deliver_img['insurance']}"/>
            </div>
        </if>
    </div>
    <div id="step_title">
        Work Eligibility
    </div>
    <div id="memo">
        This may be a valid passport, residency card, birth certificate, citizenship card, work permit, or a study permit that allow off-campus work.
    </div>
    <div style="margin: 10px auto;width: 85%;">
        <if condition="$deliver_session['group'] neq 1">
        <div style="display:inline-block;" id="J_selectImage_2">
            <div class="btn btn-sm btn-success" style="position:relative;height:50px;line-height: 50px;text-align: left;">
                Upload Proof of Work Eligibility here
            </div>
        </div>
        </if>
        <if condition="$deliver_img['certificate'] eq ''">
            <div class="img_2">

            </div>
        <else />
            <div class="img_2" style="height: 100px">
                <img src="{pigcms{$deliver_img['certificate']}"/>
            </div>
        </if>
    </div>
    <div id="memo" style="text-align: center;margin-top: 20px">
        <span id="filename_0" style="display: none;">
            <if condition="$deliver_img['driver_license'] neq ''">
                {pigcms{$deliver_img['driver_license']}
            </if>
        </span>
        <span id="filename_1" style="display: none;">
            <if condition="$deliver_img['insurance'] neq ''">
                {pigcms{$deliver_img['insurance']}
            </if>
        </span>
        <span id="filename_2" style="display: none;">
            <if condition="$deliver_img['certificate'] neq ''">
                {pigcms{$deliver_img['certificate']}
            </if>
        </span>
        <if condition="$deliver_session['group'] neq 1">
        <input type="button" value="Save" id="reg_form">
        </if>
    </div>
</section>

<script src="{pigcms{$static_public}js/lang.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKlguA2QFIUVwWTo3danbOqSKv3nYbBCg&libraries=places&language=en" async defer></script>
<script type="text/javascript">
    $("body").css({"height":$(window).height()});

    var  uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Deliver/ajax_upload')}",
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploader.addButton({
        id:'#J_selectImage_0',
        name:'image_0',
        multiple:false
    });
    uploader.addButton({
        id:'#J_selectImage_1',
        name:'image_1',
        multiple:false
    });
    uploader.addButton({
        id:'#J_selectImage_2',
        name:'image_2',
        multiple:false
    });
    uploader.on('fileQueued',function(file){
        if($('.upload_pic_li').size() >= 5){
            uploader.cancelFile(file);
            alert('最多上传5个图片！');
            return false;
        }
    });
    uploader.on('uploadSuccess',function(file,response){
        if(response.error == 0){
            var fid = file.source.ruid;
            var ruid = fid.split('_');
            var img = findImg(ruid[1],response.file);
            img.html('<img src="'+response.url+'"/>');
            img.css("height","100px");
        }else{
            alert(response.info);
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });

    function findImg(fid,file) {
        var img = '';
        var all = 3;
        var curr = 0;
        var is_addcss = false;
        for(var i=0;i<all;i++) {
            $('#J_selectImage_' + i).children('div').each(function () {
                if (typeof($(this).attr('id')) != 'undefined') {
                    if(is_addcss && i > curr){
                        var top = parseInt($(this).css("top"));
                        $(this).css("top",top+100+"px");
                    }
                    var arr = $(this).attr('id').split('_');
                    if (arr[2] == fid) {
                        curr = i;
                        img = $('.img_' + i);
                        if($.trim(img.html()) == ''){
                            is_addcss = true;
                        }else{
                            is_addcss = false;
                        }

                        $('#filename_'+i).html(file);
                    }
                }
            });
        }

        return img;
    }

    $('#reg_form').click(function () {
        var is_next = true;
        // $('body').find('span').each(function () {
        //     if (typeof($(this).attr('id')) != 'undefined'){
        //         var span_id = $(this).attr('id').split('_');
        //         if(span_id[0] == 'filename'){
        //             if ($(this).html() == ''){
        //                 is_next = false;
        //             }
        //         }
        //     }
        // });

        // if($('#ahname').val() == '' || $('#transit').val() == '' || $('#institution').val() == '' || $('#account').val() == '' || $('#sin_num').val() == ''){
        //     is_next = false;
        // }

        if(!is_next)
            alert("{pigcms{:L('_PLEASE_INPUT_ALL_')}");
        else{
            var post_data = {
                'address':$('#address').val(),
                'lng':$('#lng').val(),
                'lat':$('#lat').val(),
                'city_id':$('#city_id').val(),
                'sin_num':$('#sin_num').val(),
                'img_0':$.trim($('#filename_0').html()),
                'img_1':$.trim($('#filename_1').html()),
                'img_2':$.trim($('#filename_2').html())
            };
            $.ajax({
                url: "{pigcms{:U('Deliver/ver_info')}",
                type: 'POST',
                dataType: 'json',
                data: post_data,
                success:function(date){
                    window.parent.location = "{pigcms{:U('Deliver/account')}";
                }

            });
        }
    });

    $('#address').focus(function () {
        initAutocomplete();
    });

    var autocomplete;
    function initAutocomplete() {
        autocomplete = new google.maps.places.Autocomplete(document.getElementById('address'), {types: ['geocode'],componentRestrictions: {country: ['ca']}});
        autocomplete.addListener('place_changed', fillInAddress);
    }
    function fillInAddress() {
        var place = autocomplete.getPlace();
        $("input[name='lng']").val(place.geometry.location.lng());
        $("input[name='lat']").val(place.geometry.location.lat());
    }


    var group = "{pigcms{$deliver_session['group']}";
    if(group == 1){
        $('body').find('input').each(function () {
            $(this).attr("readonly","readonly");
        });
    }
</script>
</body>
</html>