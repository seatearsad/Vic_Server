<include file="Public:header"/>
<style>
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }

    #J_selectImage_0, #J_selectImage_1, #J_selectImage_2 {
        background-color: #ffa52d;
        color: white;
        text-indent: 0px;
        border-radius: 5px;
        padding: 0px;
        height: 50px;
        line-height: 50px;
        box-sizing: border-box;
        display: inline-block;
        width: 100%;
    }

    .img_0, .img_1, .img_2 {
        width: 100%;
        text-align: center;
    }

    .img_0 img, .img_1 img, .img_2 img {
        height: 100px;
    }

    .btn {
        background: none;
        padding: 0 12px;
    }
    #upload_pic_box{margin-top:20px;height:150px;}
    #upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
    #upload_pic_box img{width:100px;height:70px;}
</style>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Deliver/bag_add')}" frame="true"
                              refresh="true">
                            <input type="hidden" name="uid" value="{pigcms{$now_user.uid}"/>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_BAG_NAME_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="bag_name"
                                                             value="" size="25" validate="maxlength:50,required:true"/>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_PRICE_TXT_')}</label>
                                <div class="col-sm-9"><input type="text" size="20" class="form-control"
                                                             name="bag_price"
                                                             value="" size="20" validate="maxlength:50,required:true"/>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_STORE_PRODUCT_TAX_')}</label>
                                <div class="col-sm-9"><input type="text" size="50" class="form-control"
                                                             name="bag_tax_rate"
                                                             value="" size="20" validate="maxlength:50,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('D_COURIER_NOTES')}</label>
                                <div class="col-sm-9"><textarea name="bag_description" class="form-control"></textarea>
                                </div>
                            </div>


                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('_BACK_STATUS_')}</label>
                                <div class="col-sm-9"><span class="cb-enable"><label class="cb-enable selected
                                        "><span>{pigcms{:L('_BACK_NORMAL_')}</span><input type="radio" name="bag_switch"
                                                                                          value="1"  checked="checked"/></label></span>
                                    <span class="cb-disable"><label class="cb-disable
                                        "><span>{pigcms{:L('_BACK_FORBID_')}</span><input type="radio" name="bag_switch"
                                                                                          value="0"  /></label></span>
                                </div>
                            </div>

                            <!--               图片上传                 /////-->
                            <div id="upload_image_box" class="form-group  row" style="margin-bottom: 10px;display: none;">
                                <div class="col-lg-12">
                                    <div class="ibox ">
                                        <div class="ibox-title  back-change">
                                            <label style="margin-bottom: 10px">Photo</label>
                                        </div>
                                        <div class="ibox-content">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="image-crop">
                                                        <img id="ori_image" src="{pigcms{$static_path}images/p3.jpg">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h4>{pigcms{:L('PREVIEW_BKADMIN')}</h4>
                                                    <div class="img-preview img-preview-sm"></div>
                                                    <p>
                                                        &nbsp;<div  id="upld" class="form-control btn-success" style="width: 90px;">Upload</div>
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--                                //---------------------------------->
                            <div class="form-group  row" >
                                <label class="col-sm-3 col-form-label">{pigcms{:L('UPLOAD_BKADMIN')}</label>
                                <div class="col-sm-9" style="display:inline-block;" id="J_selectImage">
                                    <div class="form-control btn-success" style="position:relative;width:90px;height:36px;text-align:center">
                                        <label title="Upload image file" for="inputImage" >
                                            <input type="file" accept="image/*" name="pic" id="inputImage" style="display:none">
                                            {pigcms{:L('UPLOAD_BKADMIN')}
                                        </label>
                                    </div>
                                </div>
                                <span class="form_tips"></span>
                            </div>

                            <div class="form-group tutti_hidden_obj">
                                <label class="col-sm-3">{pigcms{:L('IMAGE_SELECT_BKADMIN')}</label>
                                <div class="col-sm-9">111
                                    <a href="#modal-table" onclick="selectImg('upload_pic_ul','goods')">{pigcms{:L('IMAGE_SELECT_BKADMIN')}</a>
                                </div>
                            </div>

                            <div class="form-group  row">
                                <label class="col-sm-3">{pigcms{:L('PREVIEW_BKADMIN')}</label>
                                <div class="col-sm-9" id="upload_pic_box">
                                    <ul id="upload_pic_ul">
                                        <volist name="now_goods['pic_arr']" id="vo">
                                            <li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ {pigcms{:L('DELETE_BKADMIN')} ]</a></li>
                                        </volist>
                                    </ul>
                                </div>
                            </div>

                    </div>

                            <input type="hidden" name="driver_license" id="filename_0"
                                   value="{pigcms{$img['driver_license']}">
                            <input type="hidden" name="insurance" id="filename_1"
                                   value="{pigcms{$img['insurance']}">
                            <input type="hidden" name="certificate" id="filename_2"
                                   value="{pigcms{$img['certificate']}">

                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('_BACK_SUBMIT_')}"
                                       class="button tutti_hidden_obj"/>
                                <input type="reset" value="{pigcms{:L('_BACK_CANCEL_')}"
                                       class="button tutti_hidden_obj"/>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
        <script type="text/javascript" src="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.js"></script>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/mobiscroll/mobiscroll.custom.min.css"
              media="all">
<script>
    //----------------------------------- 以下为Crooper段 --------------------------------------
    var loaded = false;
    var $upload_image_box;
    var $inputImage;var $cropped;
    function load_cooper() {
        if (loaded == false) {
            loaded = true;

            $upload_image_box = $("#upload_image_box");

            $inputImage = $("#inputImage");

            if (window.FileReader) {//检测浏览器是否支持FileReader
                $inputImage.change(function () {
                    var fileReader = new FileReader(),
                        files = this.files,
                        file;
                    if (!files.length) {
                        return;
                    }
                    var $image = $(".image-crop > img");
                    $cropped = $($image).cropper({
                        aspectRatio: 1,
                        preview: ".img-preview",
                        done: function (data) {
                            // Output the result data for cropping image.
                        }
                    });
                    $upload_image_box.show();
                    file = files[0];
                    if (/^image\/\w+$/.test(file.type)) {
                        $upload_image_box.show();
                        fileReader.readAsDataURL(file);
                        fileReader.onload = function () {
                            $inputImage.val("");
                            $image.cropper("reset", true).cropper("replace", this.result);
                        };
                    } else {
                        showMessage("Please choose an image file.");
                    }
                });
            } else {
                $inputImage.addClass("hide");
            }

            $("#setDrag").click(function () {
                $image.cropper("setDragMode", "crop");
            });

            $("#upld").on("click", function () {
                //console.log("download");
                if ($("#ori_image").attr("src") == null) {
                    return false;
                } else {

                    var base64 = $cropped.cropper('getCroppedCanvas', {
                        width: 620,
                        height: 520
                    }).toDataURL("image/png");

                    //$("#finalImg").prop("src", base64);// 显示图片
                    uploadFile(base64)//编码后上传服务器
                    //closeTailor();// 关闭裁剪框
                }
            });
        }
    }
    function dataURLtoFile(dataURL, fileName, fileType) {
        var arr = dataURL.split(','), mime = arr[0].match(/:(.*?);/)[1],
            bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
        while(n--){
            u8arr[n] = bstr.charCodeAt(n);
        }
        return new File([u8arr], fileName, {type:fileType || 'image/png'});
    }
    //ajax请求上传
    function uploadFile(file) {
        var oData = new FormData();
        var nameImg=new Date().getTime()+".png";
        var ff=dataURLtoFile(file,nameImg);
        oData.append("file", ff);
        $.ajax({
            url : "{pigcms{:U('Deliver/ajax_upload')}&uid={pigcms{$now_user.uid}",
            type: "post",
            dataType:"json",
            data : oData,
            processData: false,
            contentType: false,
            async : true,
            success : function(data) {
                if(data.error == 0){$upload_image_box.hide();
                    $('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+data.url+'"/><input type="hidden" name="pic[]" value="'+data.title+'"/><br/><a href="#" onclick="deleteImage(\''+data.title+'\',this);return false;">[ {pigcms{:L('DELETE_BKADMIN')} ]</a></li>');
                }else{
                    alert(data.info);
                }
            },
            error:function(data){
                $('.loading'+file.id).remove();
                alert('Upload failed! Please try again.');
            }
        });
    }

    $(document).ready(function(){
        $("#inputImage").on("click", function () {
            load_cooper();
        });
        // $("#download").click(function (link) {
        //     link.target.href = $cropped.cropper('getCroppedCanvas', {
        //         width: 620,
        //         height: 520
        //     }).toDataURL("image/png").replace("image/png", "application/octet-stream");
        //     link.target.download = 'cropped.png';
        // });
        //----- 之前的 ------
    });

    //--------------------------------------  以上为Crooper段  --------------------------------------
</script>
<script type="text/javascript">
    function deleteImage(path,obj){
        $.post("{pigcms{:U('Deliver/ajax_del_pic')}",{path:path});
        $(obj).closest('.upload_pic_li').remove();
    }

</script>
<script type="text/javascript">

    var static_public="{pigcms{$static_public}",
        static_path="{pigcms{$static_path}",
        merchant_index="{pigcms{:U('Index/index')}",
        choose_province="{pigcms{:U('Area/ajax_province')}",
        choose_city="{pigcms{:U('Area/ajax_city')}",
        choose_area="{pigcms{:U('Area/ajax_area')}",
        choose_circle="{pigcms{:U('Area/ajax_circle')}",
        choose_city_name="{pigcms{:U('Area/ajax_city_name')}";

    var theme = "ios";
    var mode = "scroller";
    var display = "modal";
    var lang = "en";

    $('input[name="birthday"]').mobiscroll().date({
        theme: theme,
        mode: mode,
        display: display,
        dateFormat: 'yyyy-mm-dd',
        dateOrder: 'yymmdd',
        lang: lang
    });
    var uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Deliver/ajax_upload')}&uid={pigcms{$now_user.uid}",
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploader.addButton({
        id: '#J_selectImage_0',
        name: 'image_0',
        multiple: false
    });
    uploader.addButton({
        id: '#J_selectImage_1',
        name: 'image_1',
        multiple: false
    });
    uploader.addButton({
        id: '#J_selectImage_2',
        name: 'image_2',
        multiple: false
    });
    uploader.on('fileQueued', function (file) {
        if ($('.upload_pic_li').length >= 5) {
            uploader.cancelFile(file);
            alert('最多上传5个图片！');
            return false;
        }
    });
    uploader.on('uploadSuccess', function (file, response) {
        if (response.error == 0) {
            var fid = file.source.ruid;
            var ruid = fid.split('_');
            var img = findImg(ruid[1], response.file);
            img.html('<img src="' + response.url + '"/>');
            img.css("height", "100px");
        } else {
            alert(response.info);
        }
    });

    uploader.on('uploadError', function (file, reason) {
        $('.loading' + file.id).remove();
        alert('上传失败！请重试。');
    });

    function findImg(fid, file) {
        var img = '';
        var all = 3;
        var curr = 0;
        var is_addcss = false;
        for (var i = 0; i < all; i++) {
            $('#J_selectImage_' + i).children('div').each(function () {
                if (typeof($(this).attr('id')) != 'undefined') {
                    if (is_addcss && i > curr) {
                        var top = parseInt($(this).css("top"));
                    }
                    var arr = $(this).attr('id').split('_');
                    if (arr[2] == fid) {
                        curr = i;
                        img = $('.img_' + i);
                        if ($.trim(img.html()) == '') {
                            is_addcss = true;
                        } else {
                            is_addcss = false;
                        }

                        $('#filename_' + i).val(file);
                    }
                }
            });
        }

        return img;
    }

    $('img').click(function () {
        //alert($(this).attr('src'));
        window.top.artiframe($(this).attr('src'), '查看', 600, 500, true, false, false);
    });
</script>

<include file="Public:footer_inner"/>