<include file="Public:header"/>
<body style="background-color: #fff;">
<div id="wrapper-singlepage">

    <div id="page-wrapper-singlepage" class="white-bg">

        <!----------------------------------------    以上不要写代码     ------------------------------------------------>
        <div class="row wrapper wrapper-content animated fadeInRight">

            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">
                        <form id="myform" method="post" action="{pigcms{:U('Shop/cat_modify')}" frame="true" refresh="true">
                            <input type="hidden" name="cat_fid" id="cat_fid" value="{pigcms{$parentid}"/>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEGORYNAME')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cat_name" id="cat_name" size="25" placeholder="" validate="maxlength:20,required:true" tips=""/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEGORYURL')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cat_url" id="cat_url" size="25" placeholder="" validate="maxlength:20,required:true,en_num:true" tips=""/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_LISTORDER')}</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" name="cat_sort" value="0" size="10" placeholder="" validate="maxlength:6,required:true,number:true" tips=""/>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATESTAT')}</label>
                                <div class="col-sm-9 col-form-label">
                                    <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_CATEGORYSEN')}</span><input type="radio" name="cat_status" value="1" checked="checked" /></label></span>
                                    <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_CATEGORYDIS')}</span><input type="radio" name="cat_status" value="0" /></label></span>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEGORYSCS')}</label>
                                <div class="col-sm-9 col-form-label">
                                    <select name="show_method" class="form-control m-b">
                                        <option value="0" selected="selected">{pigcms{:L('C_CATEGORYSCS1')}</option>
                                        <option value="1">{pigcms{:L('C_CATEGORYSCS2')}</option>
                                        <option value="2">{pigcms{:L('C_CATEGORYSCS3')}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group  row">
                                <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATEICON')}</label>
                                <div class="col-sm-9 col-form-label">
                                    <div style="display:inline-block;position:relative;width:78px;height:30px;" id="J_selectImage">
                                        <div class="btn btn-sm btn-success">{pigcms{:L('BASE_UPLOADIMAGE')}</div>
                                    </div>
                                    <div id="upload_pic_ul">
                                    </div>
                                </div>
                            </div>
                            <if condition="$parentid eq 0">
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATETYPE')}</label>
                                    <div class="col-sm-9 col-form-label">
                                        <span class="cb-enable"><label class="cb-enable selected"><span>{pigcms{:L('C_CATEGORYNOR')}</span><input type="radio" name="cat_type" value="0" checked="checked" /></label></span>
                                        <span class="cb-disable"><label class="cb-disable"><span>{pigcms{:L('C_CATEGORYFT')}</span><input type="radio" name="cat_type" value="1" /></label></span>
                                    </div>
                                </div>
                                <div class="form-group  row" id="cat_desc" style="display: none">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('BASE_CITY')}</label>
                                    <div class="col-sm-9 col-form-label">
                                        <select name="city_id" class="form-control m-b">
                                            <option value="0" selected="selected">{pigcms{:L('G_UNIVERSAL')}</option>
                                            <volist name="city" id="vo">
                                                <option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
                                            </volist>
                                        </select>
                                    </div>
                                </div>
                                <else />
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('C_CATETYPE')}</label>
                                    <div class="col-sm-9 col-form-label">
                                        <if condition="$category['cat_type'] eq 0">
                                            {pigcms{:L('C_CATEGORYNOR')}
                                            <else />
                                            {pigcms{:L('C_CATEGORYFT')}
                                        </if>
                                        <input type="hidden" name="cat_type" value="{pigcms{$category.cat_type}" >
                                    </div>
                                </div>
                                <div class="form-group  row">
                                    <label class="col-sm-3 col-form-label">{pigcms{:L('BASE_CITY')}</label>
                                    <div class="col-sm-9 col-form-label">
                                        {pigcms{$category.city_name}
                                        <input type="hidden" name="city_id" value="{pigcms{$category.city_id}" >
                                    </div>
                                </div>
                            </if>
                            <div class="btn tutti_hidden_obj">
                                <input type="submit" name="dosubmit" id="dosubmit" value="{pigcms{:L('BASE_SUBMIT')}" class="button" />
                                <input type="reset" value="{pigcms{:L('BASE_CANCEL')}" class="button" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
    $('input:radio[name=cat_type]').click(function () {
        if ($(this).val() == 1) {//未通过
            $('#cat_desc').show();
        } else {
            $('#cat_desc').hide();
        }
    });
    var uploader = WebUploader.create({
        auto: true,
        swf: '{pigcms{$static_public}js/Uploader.swf',
        server: "{pigcms{:U('Shop/ajax_upload_pic', array('cat_fid'=>$parentid))}",
        pick: '#J_selectImage',
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,png',
            mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
        }
    });
    uploader.on('fileQueued',function(file){
        if($('.upload_pic_li').length >= 1){
            uploader.cancelFile(file);
            alert('最多上传1张图片！');
            return false;
        }
    });
    uploader.on('uploadSuccess',function(file,response){
        if(response.error == 0){
            $('#upload_pic_ul').html('<img src="'+response.url+'" width="80"/><input type="hidden" name="cat_img" value="'+response.title+'"/>');
        }else{
            alert(response.info);
        }
    });

    uploader.on('uploadError', function(file,reason){
        $('.loading'+file.id).remove();
        alert('上传失败！请重试。');
    });
</script>
<style>
    .webuploader-container div {
        width: 78px !important;
        height: 30px !important;
        box-sizing: border-box;
    }
    .btn-success, .btn-success:focus {
        background-color: #87b87f !important;
        border-color: #87b87f;
        color: white;
    }
    .btn-sm {
        border-width: 4px;
        font-size: 13px;
        line-height: 1.39;
    }
    .webuploader-element-invisible {
        position: absolute !important;
        clip: rect(1px 1px 1px 1px);
        clip: rect(1px,1px,1px,1px);
    }
    input[type="file"] {
        display: block;
        font-family: inherit;
        font-size: inherit;
        line-height: inherit;
    }
</style>
<include file="Public:footer_inc"/>