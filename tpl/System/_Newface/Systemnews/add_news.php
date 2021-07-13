<include file="Public:header"/>
<style>
    input[type="file"] {
        display: block;
        position: absolute;
        opacity: 0;
        -ms-filter: 'alpha(opacity=0)';
    }

    #J_selectImage_0, #J_selectImage_1 {
        background-color: #EEEEEE;
        color: #666666;
        text-indent: 0px;
        border-radius: 5px;
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
</style>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">
    <div id="page-wrapper-singlepage" class="white-bg">
        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Systemnews/add_news')}" frame="true"
                              refresh="true">
                            <input id="filename_0" type="hidden" name="cover">
                            <input id="filename_1" type="hidden" name="top_img">

                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('I_TITLE')}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="title" value=""
                                           size="95" placeholder="{pigcms{:L('I_TITLE')}"
                                           validate="maxlength:95,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('I_SUBTITLE')}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sub_title"
                                           value="" size="95"
                                           placeholder="{pigcms{:L('I_SUBTITLE')}"
                                           validate="maxlength:95,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('I_KEYWORDS')}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="keyword" size="100"
                                           value=""
                                           placeholder="{pigcms{:L('I_KEYWORDS')}"
                                           validate="maxlength:100,required:true"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('I_BRIEF_INTRODUCTION')}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="desc" size="200"
                                           value="" placeholder=""
                                           validate="maxlength:200,required:false"/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('QW_CATEGORY')}</label>
                                <div class="col-sm-10">
                                    <if condition="$category">
                                        <select name="category_id" class="form-control">
                                            <volist name="category" id="vo">
                                                <option value="{pigcms{$vo.id}"
                                                <if condition="$vo['id'] eq $_GET['category_id']">
                                                    selected="selected"
                                                </if>
                                                >{pigcms{$vo.name}</option>
                                            </volist>
                                        </select>
                                    </if>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('I_LISTING_ORDER')}</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="sort" value=""
                                           validate="maxlength:50,digits:true,required:true"/>
                                </div>
                            </div>
                            <if condition="$curr_cate['type'] eq 0">
                                    <div class="form-group  row">
                                        <label class="col-sm-2 col-form-label">{pigcms{:L('G_CITY')}</label>
                                        <div class="col-sm-10">
                                            <select id="city_id" name="city_id" class="form-control">
                                                <option value="0">{pigcms{:L('BASE_UNIVERSAL')}</option>
                                                <volist name="city" id="vo">
                                                    <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                                                </volist>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group  row">
                                        <label class="col-sm-2 col-form-label">{pigcms{:L('I_COVER_PAGE')}</label>
                                        <div class="col-sm-10">
                                            <div style="display:inline-block;" id="J_selectImage_0">
                                                <div class="btn btn-sm btn-success"
                                                     style="position:relative;text-align: left;border:1px solid #ffa52d;">
                                                    Upload
                                                </div>
                                            </div>
                                            <div class="img_0"></div>
                                        </div>
                                    </div>

                                    <div class="form-group  row">
                                        <label class="col-sm-2 col-form-label">{pigcms{:L('I_UPPER_IMAGE')}</label>
                                        <div class="col-sm-10">
                                            <div style="display:inline-block;" id="J_selectImage_1">
                                                <div class="btn btn-sm btn-success"
                                                     style="position:relative;text-align: left;border:1px solid #ffa52d;">
                                                    Upload
                                                </div>
                                            </div>
                                            <div class="img_1"></div>
                                        </div>
                                    </div>
                            </if>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('I_CONTENT')}</label>
                                <div class="col-sm-10">
                                     <textarea name="content" id="content"></textarea>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('G_STATUS')}</label>
                                <div class="col-sm-10">
                                        <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('I_ENABLE1')}</span><input
                                                     type="radio" name="status" value="1" checked="checked"/></label></span>
                                        <span class="cb-disable"><label class="cb-disable "><span>{pigcms{:L('I_DISABLE3')}</span><input
                                                    type="radio" name="status" value="0"/></label></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-2 col-form-label">{pigcms{:L('I_RECOMMENDED')}</label>
                                <div class="col-sm-10">
                                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('I_RECOMMENDED_TOP')}</span><input
                                                    type="radio" name="is_commend" value="1"
                                                    checked="checked"/></label></span>
                                    <span class="cb-disable"><label class="cb-disable "><span>{pigcms{:L('I_RECOMMENDED_NORMAL')}</span><input
                                                    type="radio" name="is_commend" value="0"/></label></span>
                                </div>
                            </div>


                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button"/>
                                <input type="reset" value="取消" class="button"/>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
            <script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
            <script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
            <script type="text/javascript">
                var uploader = WebUploader.create({
                    auto: true,
                    swf: '{pigcms{$static_public}js/Uploader.swf',
                    server: "{pigcms{:U('System/Systemnews/ajax_upload')}&cate_id={pigcms{$curr_cate.id}",
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
                                    $(this).css("top", top + 100 + "px");
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

                KindEditor.ready(function (K) {
                    kind_editor = K.create("#content", {
                        width:'312px',
                        height:'320px',
                        resizeType: 1,
                        allowPreviewEmoticons: false,
                        allowImageUpload: true,
                        filterMode: true,
                        items: [
                            'source', 'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
                            'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
                            'insertunorderedlist', '|', 'emoticons', 'image', 'link'
                        ],
                        emoticonsPath: './static/emoticons/',
                        uploadJson: "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=system/news"
                    });
                });
            </script>
            <include file="Public:footer_inner"/>